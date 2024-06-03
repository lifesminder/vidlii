<?php
    class Video {
        public  $URL,
                $Info = [];

        protected $DB;

        function __construct(string $URL, Database $DB) {
            $this->URL = $URL;
            $this->DB  = $DB;
        }

        public function get_info() {
            $this->Info = $this->DB->execute("SELECT * FROM videos WHERE url = :URL", true, [":URL" => $this->URL]);
        }

        public function delete() {
            if (count($this->Info) == 0) {
                $this->get_info();
            }

            $Query      = $this->DB->execute("SELECT displayviews, file, status, privacy FROM videos WHERE url = :URL", true, [":URL" => $this->URL]);
            $File       = $Query["file"];
            $Views      = $Query["displayviews"];
            $Status     = $Query["status"];
            $Privacy    = $Query["privacy"];

            $this->DB->modify("DELETE FROM videos WHERE url = :URL", [":URL" => $this->URL]);
            $this->DB->modify("DELETE from uploads WHERE url = :URL", [":URL" => $this->URL]);
            $this->DB->modify("DELETE from converting WHERE url = :URL", [":URL" => $this->URL]);
			$this->DB->modify("UPDATE converting SET queue = GREATEST(0, queue - 1)");
			$this->DB->modify("DELETE FROM video_comments WHERE url = :URL", [":URL" => $this->URL]);
			$this->DB->modify("DELETE FROM recently_viewed WHERE url = :URL", [":URL" => $this->URL]);

			if ($Privacy == 0) {
                $this->DB->modify("UPDATE users SET videos = videos - 1 WHERE username = :USERNAME",
                                 [
                                     ":USERNAME" => $this->Info["uploaded_by"]
                                 ]);
            }

            $this->DB->modify("UPDATE users SET video_views = video_views - $Views WHERE username = :USERNAME", [":USERNAME" => $this->Info["uploaded_by"]]);
            $this->DB->modify("UPDATE most_viewed_month SET amount = amount - $Views WHERE username = :USERNAME", [":USERNAME" => $this->Info["uploaded_by"]]);
            $this->DB->modify("UPDATE most_viewed_week SET amount = amount - $Views WHERE username = :USERNAME", [":USERNAME" => $this->Info["uploaded_by"]]);


			$this->DB->modify("INSERT INTO videos_deleted (id) VALUES (:URL)", [":URL" => $this->URL]);

			/* Delete the files on disk */
            @unlink(ROOT_FOLDER."/usfi/thmp/$this->URL.jpg");
            @unlink(ROOT_FOLDER."/usfi/prvw/$this->URL.jpg");

            foreach(glob(ROOT_FOLDER."/usfi/conv/$this->URL.*") as $f) {
				@unlink($f);
			}
			
            foreach(glob(ROOT_FOLDER."/usfi/conv_2/$this->URL.*") as $f) {
				@unlink($f);
			}

            foreach(glob(ROOT_FOLDER."/usfi/v/$this->URL.*") as $f) {
				@unlink($f);
			}
        }

        public function exists(bool $exclude_banned = true) {
            $Check = $this->DB->execute("SELECT url, uploaded_by FROM videos WHERE url = :URL", true, [":URL" => $this->URL]);
            if ($this->DB->RowNum == 1) {
				$CheckUser = $this->DB->execute("SELECT COUNT(*) as amount FROM users WHERE username = :USER " . ($exclude_banned ? "AND banned = 0" : ""), true, [":USER" => $Check["uploaded_by"]])["amount"];
				if ($CheckUser > 0) {
					return $Check["url"];
				}
            }
            return false;
        }

        public function view(User $User,$type = 0, $Source = "") {
            if ((!in_array($this->URL,$User->Viewed_Videos) || $type == 0) and (!empty($_SERVER['HTTP_REFERER']))) {
                if ($type == 0) {
                    $this->DB->modify("UPDATE videos SET views = views + 1 WHERE url = :URL", [":URL" => $this->URL]);
                }

                if (!in_array($this->URL, $User->Viewed_Videos)) {
                    
                    $this->DB->modify("UPDATE videos SET displayviews = displayviews + 1 WHERE url = :URL", [":URL" => $this->URL]);
                    $this->DB->modify("INSERT IGNORE INTO videos_views (vid, views, submit_date, source) VALUES (:VID, 1, NOW(), :SOURCE) ON DUPLICATE KEY UPDATE views = views + 1",
                                     [
                                         ":VID"     => $this->URL,
                                         ":SOURCE"  => $Source
                                     ]);

                }

                if ($type == 1 || !in_array($this->URL, $User->Viewed_Videos)) {
                    $Username = $this->DB->execute("SELECT uploaded_by FROM videos WHERE url = :URL", true, [":URL" => $this->URL])["uploaded_by"];

                    $this->DB->modify("UPDATE users SET video_views = video_views + 1 WHERE username = '$Username'");
                    $this->DB->modify("INSERT INTO most_viewed_week (username,amount) VALUES('$Username',1) ON DUPLICATE KEY UPDATE amount = amount + 1");
                    $this->DB->modify("INSERT INTO most_viewed_month (username,amount) VALUES('$Username',1) ON DUPLICATE KEY UPDATE amount = amount + 1");
                }
            }

            //RECENTLY VIEWED
            if ($type == 1) {
                $this->DB->execute("SELECT url FROM recently_viewed");
                if ($this->DB->RowNum <= 25) {
                    if ($this->DB->RowNum == 25) {
                        $this->DB->modify("DELETE FROM recently_viewed ORDER BY time_viewed ASC LIMIT 1");
                        $this->DB->modify("INSERT IGNORE INTO recently_viewed VALUES(:URL, NOW()) ON DUPLICATE KEY UPDATE time_viewed = NOW()", [":URL" => $this->URL]);
                    } else {
                        $this->DB->modify("INSERT INTO recently_viewed VALUES(:URL, NOW()) ON DUPLICATE KEY UPDATE time_viewed = NOW()", [":URL" => $this->URL]);
                    }
                } else {
                    $Delete_Amount = $this->DB->RowNum - 25;
                    $this->DB->modify("DELETE FROM recently_viewed ORDER BY time_viewed ASC LIMIT $Delete_Amount");
                }
            }
            $User->watch_video($this->URL);
        }

        public function comments(Pagination $Pagination = NULL,$Avatars = false) {
            if ($Avatars == false) {
                $SELECT = "video_comments.*";
                $FROM   = "video_comments";
            } else {
                $SELECT = "video_comments.*, users.avatar, users.displayname";
                $FROM   = "video_comments INNER JOIN users ON video_comments.by_user = users.username";
            }
            if (!isset($Pagination)) {
                $Query = $this->DB->execute("SELECT $SELECT FROM $FROM WHERE video_comments.url = :URL AND reply_to = 0 ORDER BY video_comments.date_sent DESC", false, [":URL" => $this->URL]);
                return $Query;
            } else {
                $From = $Pagination->From;
                $To   = $Pagination->To;

                $Query = $this->DB->execute("SELECT $SELECT FROM $FROM WHERE video_comments.url = :URL AND reply_to = 0 ORDER BY video_comments.date_sent DESC LIMIT $From,$To", false, [":URL" => $this->URL]);
                return $Query;
            }
        }

        public function favorited_by($User): bool {
            $this->DB->execute("SELECT url FROM video_favorites WHERE url = :URL AND favorite_by = :USERNAME", false,
                              [
                                  ":URL"       => $this->URL,
                                  ":USERNAME"  => $User
                              ]);

            if ($this->DB->RowNum == 1) {
                return true;
            }
            return false;
        }
    }
