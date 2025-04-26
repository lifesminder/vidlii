<?php
    class User {
        public  $username,
                $displayname,
                $logged_in,
                $Viewed_Videos      = [],
                $Viewed_Channels    = [],
                $Owns_Video         = [],
                $Info,
                $Is_Partner,
                $Is_Admin,
                $Is_Mod,
                $Is_Activated,
                $Theme,
                $Shadowbanned,
                $Header,
                $session;

        protected $api, $DB;

        function __construct($ID = NULL, \Vidlii\Vidlii\DB $Database, $re = NULL) {
			$this->api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);
            $this->session = $this->api->session();
            $this->DB = $Database;

            // Try to fetch out sessions which are marked from this IP as rememberable
            if(!isset($this->session["remembered"])) {
                $ip = user_ip();
                $browser = browser_name();
                $last_remembered = $this->api->db("SELECT * from sessions where ip = \"$ip\" and remembered = 1 and browser = \"$browser\"");
                if($last_remembered["count"] >= 1) {
                    // We fetched remembered sessions!
                    // Only first in occurance will be selected (at least, for now)
                    $last_remembered = ($last_remembered["count"] > 1) ? $last_remembered = $last_remembered["data"][0] : $last_remembered["data"];

                    // Automatically log in to the last remembered session.
                    $this->session["session"] = $last_remembered["session"];
                    $this->session["user"] = $this->api->db("SELECT id, username, displayname from users where id = ".$last_remembered["user"], true);
                    if($this->session["user"]["count"] >= 1)
                        $this->session["user"] = $this->session["user"]["data"][0];
                    $this->logged_in = true;
                }
            }

            if(isset($ID)) {
                // IF ID HAS BEEN SET WHEN CREATING NEW USER CLASS (Means it's not the main Users class)
                $this->username = (string)clean($ID);
                $this->logged_in = false;
            } else if($this->session["user"]["id"] != -1) {
                // If user actually logged in with valid user (can be possible to log in via deleted one)
                $this->username = (string)clean($this->session["user"]["username"]);
                $this->logged_in = true;

                // Watched videos and channels will be moved into separate DB.
                if(!isset($_SESSION["watched_videos"]))
                    $_SESSION["watched_videos"] = [];
                else
                    $this->Viewed_Videos = $_SESSION["watched_videos"];

                if(isset($_SESSION["viewed_channels"]))
                    $this->Viewed_Channels = $_SESSION["viewed_channels"];
                else
                    $_SESSION["viewed_channels"] = [];

                // If status is unfetchable, then log out
                if(!$this->get_status()) {
                    $this->logout();
                    redirect("/");
                    exit();
                }
            } else {
                // IF ID HAS NOT BEEN SET AND THE USER ISN'T LOGGED IN YET DURING THIS SESSION
                $this->logged_in = false;

                // Watched videos and channels will be moved into separate DB.
                if(!isset($_SESSION["watched_videos"]))
                    $_SESSION["watched_videos"] = [];
                else
                    $this->Viewed_Videos = $_SESSION["watched_videos"];

                if(isset($_SESSION["viewed_channels"]))
                    $this->Viewed_Channels = $_SESSION["viewed_channels"];
                else
                    $_SESSION["viewed_channels"] = [];
            }
        }

        public function owns_video($URL) {
            return (bool)($this->api->db("SELECT count(*) from videos where url = \"$URL\" and uploaded_by = \"".$session["user"]["username"]."\"")["data"]["count(*)"] == 1);
        }

        public function view_channel($Channel) {
            if(count($this->Viewed_Channels) > 0 && !in_array($Channel,$this->Viewed_Channels)) {
                $update_channel_views = $this->api->db("UPDATE users SET channel_views = channel_views + 1 WHERE username = \"".$session["user"]["username"]."\"");
                return ($update_channel_views["status"] == 1) ? true : false;
            } return false;
        }

        public function watch_video($URL) {
            if(!in_array($URL,$this->Viewed_Videos)) {
                $_SESSION["watched_videos"][] = $URL;
                $this->Viewed_Videos[] = $URL;
                $this->DB->modify("UPDATE users SET videos_watched = videos_watched + 1 WHERE username = :USERNAME", [":USERNAME" => $this->username]);
            }
        }

        // GET STATUS INFORMATION ABOUT THE MAIN USER
        // RETURNS FALSE IF THE USER IS BANNED | TRUE IF NOT
        private function get_status(): bool {
            $Status = $this->DB->execute("SELECT username, banned, partner, is_mod, is_admin, displayname, shadowbanned, activated FROM users WHERE username = :USERNAME", true, [":USERNAME" => $this->username]);
            if($this->DB->RowNum == 1 && $Status["banned"] == 0) {
                $this->username     = (string)$Status["username"];
                $this->displayname  = (string)clean($Status["displayname"]);
                $this->Shadowbanned = (int)$Status["shadowbanned"];
                $this->Is_Admin     = (bool)$Status["is_admin"];
                $this->Is_Mod       = (bool)$Status["is_mod"];
                $this->Is_Partner   = (bool)$Status["partner"];
                $this->Is_Activated = (bool)$Status["activated"];
                return true;
            }
            return false;
        }

        private function ban() {
            if($this->logged_in) {
                $this->logout();
                redirect("/?bn=1"); exit();
            }
        }

        //LOG THE MAIN USER IN
        public function login($Remember = true): bool {
            if(!$this->logged_in && isset($this->username) && !empty($this->username)) {
                $this->logged_in = true;

                $IP_Address = user_ip(); $Browser = browser_name();
                $Info = $this->DB->execute("SELECT id, 1st_latest_ip AS first_latest_ip, displayname, is_admin, is_mod FROM users WHERE username = :USERNAME", true, [":USERNAME" => $this->username]);

                $_SESSION["username"] = (string)$this->username;
                session_regenerate_id();

                // Sessionizing! Soon on an official class
                $get_remembered_sessions = $this->api->db("SELECT * from sessions where ip = \"$IP_Address\" and browser = \"$Browser\" and remembered = 1 limit 1");
                if($get_remembered_sessions["count"] == 1) {
                    $session = $get_remembered_sessions["data"]["session"];
                    $authorize = $this->api->db("UPDATE sessions set last_access = NOW() where session = \"$session\"");
                } else {
                    $session = hash("sha256", random_bytes(6));
                    $authorize = $this->api->db("INSERT into sessions (session, user, ip, remembered, browser) values (\"$session\", \"$Info[id]\", \"$IP_Address\", ".($Remember ? 1: 0).", \"$Browser\")");
                }

                if($authorize["status"] >= 1) {
                    // Set session cookie
                    setcookie("session", $session, time() + (86400 * 30), "/", null, null, true);
                    // Refresh user logon information
                    $this->DB->modify("UPDATE users SET last_login = NOW(), 1st_latest_ip = :FIRST_IP, 2nd_latest_ip = :SECOND_IP WHERE username = :UID AND banned = 0", [
                        ":UID" => $this->username,
                        ":FIRST_IP" => $IP_Address,
                        ":SECOND_IP" => $Info["first_lastest_ip"]
                    ]);
                }

                return true;
            }
            return false;
        }

        public function logout(): bool {
            $session = $this->api->session();
            $is_authorized = (bool)($session["session"] != -1);
            if($is_authorized) {
                $session_hash = $session["session"];
                $log_out = $this->api->db("DELETE from sessions where session = \"$session_hash\"");
                if($log_out["status"] >= 1) {
                    setcookie('h', null, -1, '/');
                    setcookie('po', null, -1, '/');
                    setcookie('re', null, -1, '/');
                    setcookie('session', null, -1, '/');
                    $this->logged_in = false;
                    $this->username = NULL;
                    $this->displayname = NULL;
                }
                return (bool)($log_out["status"] >= 1);
            } return false;
        }

        public function check_password($Password) {
            $Hash = $this->DB->execute("SELECT password FROM users WHERE username = :USERNAME", true, [":USERNAME" => $this->username]);
            if (password_verify($Password,$Hash["password"]))   { return true;  }
            else                                                { return false; }
        }

        public function change_password($New_Password) {
            $New_Hash = password_hash($New_Password, PASSWORD_BCRYPT);
            $this->DB->modify("UPDATE users SET password = :NEW_HASH WHERE username = :USERNAME",
                             [
                                 ":NEW_HASH" => $New_Hash,
                                 ":USERNAME" => $this->username
                             ]);

            if ($this->DB->RowNum == 1)     { return true;  }
            else                            { return false; }
        }

        public function get_profile() {
            $Info = $this->DB->execute("SELECT * FROM users WHERE username = :USERNAME", true, [":USERNAME" => $this->username]);
            $this->Info = $Info;
            return $Info;
        }

        public function rate_video(Video $Video, int $Rating) {
            $URL = $Video->exists();
            $Has_Rated = $this->has_rated_video($URL);

            if ($URL !== false && $Rating >= 1 && $Rating <= 5 && !$Has_Rated && !$this->owns_video($URL)) {
                $this->DB->modify("INSERT INTO video_ratings (url, user_rated, stars) VALUES(:URL, :USER, :RATING)",
                                 [
                                     ":URL"     => $Video->URL,
                                     ":USER"    => $this->username,
                                     ":RATING"  => $Rating
                                 ]);

                $Star_Rating = $Rating."_star";

                $this->DB->modify("UPDATE videos SET $Star_Rating = $Star_Rating + 1 WHERE url = :URL", [":URL" => $Video->URL]);
                return true;
            } elseif ($URL !== false && $Has_Rated && $Rating >= 1 && $Rating <= 5) {

                $Column_Name     = $Has_Rated."_star";
                $New_Column_Name = $Rating."_star";

                $this->DB->modify("UPDATE video_ratings SET stars = $Rating WHERE url = :URL AND user_rated = :USERNAME", [":URL" => $URL, ":USERNAME" => $this->username]);
                $this->DB->modify("UPDATE videos SET $Column_Name = $Column_Name - 1, $New_Column_Name = $New_Column_Name + 1 WHERE url = :URL", [":URL" => $URL]);

                return true;

            } else {
                return false;
            }
        }

        public function has_rated_video(string $Video_url) {
            $Rating = $this->DB->execute("SELECT stars FROM video_ratings WHERE url = :VIDEO_URL AND user_rated = :USERNAME", true,
                                       [
                                           ":VIDEO_URL" => $Video_url,
                                           ":USERNAME"  => $this->username
                                       ]);

            if ($this->DB->RowNum == 1) {
                return $Rating["stars"];
            } else {
                return false;
            }
        }

        public function favorite_video(string $Video) {
            $this->DB->modify("INSERT IGNORE INTO video_favorites (url, favorite_by, date) VALUES(:URL,:USERNAME,NOW())",
                             [
                                 ":URL"         => $Video,
                                 ":USERNAME"    => $this->username
                             ]);

            if ($this->DB->RowNum == 1) {
                $this->DB->modify("UPDATE users SET favorites = favorites + 1 WHERE username = :USERNAME", [":USERNAME" => $this->username]);
                $this->DB->modify("UPDATE videos SET favorites = favorites + 1 WHERE url = :URL", [":URL" => $Video]);
            }
        }

        public function remove_favorite($Video) {
            $this->DB->modify("DELETE FROM video_favorites WHERE url = :VIDEO AND favorite_by = :USER",
                             [
                                 ":VIDEO"   => $Video,
                                 ":USER"    => $this->username
                             ]);

            if ($this->DB->RowNum == 1) {
                $this->DB->modify("UPDATE users SET favorites = favorites - 1 WHERE username = :USERNAME", [":USERNAME" => $this->username]);
                $this->DB->modify("UPDATE videos SET favorites = favorites - 1 WHERE url = :URL", [":URL" => $Video]);
            }
        }

        public function subscribe_to(string $User, string $Source): bool {
            if (!$this->is_subscribed_to($User)) {
                $this->DB->modify("INSERT IGNORE INTO subscriptions VALUES (:SUBSCRIBER, :SUBSCRIPTION, NOW(), :SOURCE)",
                                 [
                                     ":SUBSCRIBER"      => $this->username,
                                     ":SUBSCRIPTION"    => $User,
                                     ":SOURCE"          => $Source
                                 ]);

                if ($this->DB->RowNum == 1) {
                    $this->DB->modify("UPDATE users SET subscribers = subscribers + 1 WHERE username = :SUBSCRIPTION", [":SUBSCRIPTION" => $User]);
                    $this->DB->modify("INSERT INTO most_subscribed_week (username,amount) VALUES(:USERNAME,1) ON DUPLICATE KEY UPDATE amount = amount + 1", [":USERNAME" => $User]);
                    $this->DB->modify("INSERT INTO most_subscribed_month (username,amount) VALUES(:USERNAME,1) ON DUPLICATE KEY UPDATE amount = amount + 1", [":USERNAME" => $User]);
                    $this->DB->modify("UPDATE users SET subscriptions = subscriptions + 1 WHERE username = :SUBSCRIBER", [":SUBSCRIBER" => $this->username]);
                    return true;
                }
            } else {
                $this->DB->modify("DELETE FROM subscriptions WHERE subscriber = :SUBSCRIBER AND subscription = :SUBSCRIPTION",
                                 [
                                     ":SUBSCRIBER"      => $this->username,
                                     ":SUBSCRIPTION"    => $User
                                 ]);

                if ($this->DB->RowNum == 1) {
                    $this->DB->modify("UPDATE users SET subscribers = subscribers - 1 WHERE username = :SUBSCRIPTION", [":SUBSCRIPTION" => $User]);
                    $this->DB->modify("INSERT INTO most_subscribed_week (username,amount) VALUES(:USERNAME,1) ON DUPLICATE KEY UPDATE amount = amount - 1", [":USERNAME" => $User]);
                    $this->DB->modify("INSERT INTO most_subscribed_month (username,amount) VALUES(:USERNAME,1) ON DUPLICATE KEY UPDATE amount = amount - 1", [":USERNAME" => $User]);
                    $this->DB->modify("UPDATE users SET subscriptions = subscriptions - 1 WHERE username = :SUBSCRIBER", [":SUBSCRIBER" => $this->username]);
                    return false;
                }
            }
            return false;
        }

        public function get_rankings($Channel_Type) {
            function get_rank($username, $array) {
                foreach ($array as $rank => $val) {
                    if ($val['username'] === $username) {
                        return $rank + 1;
                    }
                }
                return null;
            }

            if (file_exists(ROOT_FOLDER."/cache/".$this->username."_aw.txt")) {
                $Age = filemtime("cache/".$this->username."_aw.txt");
                $Created_Ago = time() - $Age;
                if ($Created_Ago >= 3600) {
                    unlink(ROOT_FOLDER."/cache/".$this->username."_aw.txt");
                } else {
                    return unserialize(file_get_contents(ROOT_FOLDER."/cache/".$this->username."_aw.txt"));
                }
            }

            $Views_Alltime      = get_rank($this->username, $this->DB->execute("SELECT username, video_views as amount FROM users WHERE users.banned = '0' AND users.privacy = '0' AND users.displayname NOT LIKE 'officer%' AND users.displayname NOT LIKE '0fficer%' ORDER BY video_views DESC LIMIT 100"));
            $Views_Week_Total   = get_rank($this->username, $this->DB->execute("SELECT username, amount FROM most_viewed_week ORDER BY amount DESC LIMIT 100"));
            $Views_Month_Total  = get_rank($this->username, $this->DB->execute("SELECT username, amount FROM most_viewed_month ORDER BY amount DESC LIMIT 100"));
            $Subs_Alltime       = get_rank($this->username, $this->DB->execute("SELECT username, subscribers as amount FROM users WHERE users.banned = '0' AND users.privacy = '0' AND users.displayname NOT LIKE 'officer%' AND users.displayname NOT LIKE '0fficer%' ORDER BY subscribers DESC LIMIT 100"));
            $Subs_Week_Total    = get_rank($this->username, $this->DB->execute("SELECT username, amount FROM most_subscribed_week ORDER BY amount DESC LIMIT 100"));
            $Subs_Month_Total   = get_rank($this->username, $this->DB->execute("SELECT username, amount FROM most_subscribed_month ORDER BY amount DESC LIMIT 100"));

            if ($Channel_Type != 0) {
                $Views_Alltime_Cat      = get_rank($this->username, $this->DB->execute("SELECT username, video_views as amount FROM users WHERE channel_type = $Channel_Type AND users.banned = '0' AND users.privacy = '0' AND users.displayname NOT LIKE 'officer%' AND users.displayname NOT LIKE '0fficer%' ORDER BY video_views DESC LIMIT 100"));
                $Views_Week_Total_Cat   = get_rank($this->username, $this->DB->execute("SELECT most_viewed_week.username, most_viewed_week.amount FROM most_viewed_week INNER JOIN users ON users.username = most_viewed_week.username WHERE users.channel_type = $Channel_Type ORDER BY most_viewed_week.amount DESC LIMIT 100"));
                $Views_Month_Total_Cat  = get_rank($this->username, $this->DB->execute("SELECT most_viewed_month.username, most_viewed_month.amount FROM most_viewed_month INNER JOIN users ON users.username = most_viewed_month.username WHERE users.channel_type = $Channel_Type ORDER BY most_viewed_month.amount DESC LIMIT 100"));
                $Subs_Alltime_Cat       = get_rank($this->username, $this->DB->execute("SELECT username, subscribers as amount FROM users WHERE channel_type = $Channel_Type AND users.banned = '0' AND users.privacy = '0' AND users.displayname NOT LIKE 'officer%' AND users.displayname NOT LIKE '0fficer%' ORDER BY subscribers DESC LIMIT 100"));
                $Subs_Week_Total_Cat    = get_rank($this->username, $this->DB->execute("SELECT most_subscribed_week.username, most_subscribed_week.amount FROM most_subscribed_week INNER JOIN users ON users.username = most_subscribed_week.username WHERE users.channel_type = $Channel_Type ORDER BY most_subscribed_week.amount DESC LIMIT 100"));
                $Subs_Month_Total_Cat   = get_rank($this->username, $this->DB->execute("SELECT most_subscribed_month.username, most_subscribed_month.amount FROM most_subscribed_month INNER JOIN users ON users.username = most_subscribed_month.username WHERE users.channel_type = $Channel_Type ORDER BY most_subscribed_month.amount DESC LIMIT 100"));
            }

            $Rankings = array();

            if ($Views_Alltime != NULL)     { $Rankings["va"] = $Views_Alltime; }
            if ($Views_Week_Total != NULL)  { $Rankings["vw"] = $Views_Week_Total; }
            if ($Views_Month_Total != NULL) { $Rankings["vm"] = $Views_Month_Total; }
            if ($Subs_Alltime != NULL)      { $Rankings["sa"] = $Subs_Alltime; }
            if ($Subs_Week_Total != NULL)   { $Rankings["sw"] = $Subs_Week_Total; }
            if ($Subs_Month_Total != NULL)  { $Rankings["sm"] = $Subs_Month_Total; }

            if ($Channel_Type != 0) {
                if ($Views_Alltime_Cat != NULL)     { $Rankings["vac"] = $Views_Alltime_Cat; }
                if ($Views_Week_Total_Cat != NULL)  { $Rankings["vwc"] = $Views_Week_Total_Cat; }
                if ($Views_Month_Total_Cat != NULL) { $Rankings["vmc"] = $Views_Month_Total_Cat; }
                if ($Subs_Alltime_Cat != NULL)      { $Rankings["sac"] = $Subs_Alltime_Cat; }
                if ($Subs_Week_Total_Cat != NULL)   { $Rankings["swc"] = $Subs_Week_Total_Cat; }
                if ($Subs_Month_Total_Cat != NULL)  { $Rankings["smc"] = $Subs_Month_Total_Cat; }
            }

            $File = fopen(ROOT_FOLDER."/cache/".$this->username."_aw.txt", "w");
            fwrite($File, serialize($Rankings));
            fclose($File);

            return $Rankings;
        }

        public function is_subscribed_to(string $User): bool {
            $this->DB->execute("SELECT subscription FROM subscriptions WHERE subscriber = :SUBSCRIBER AND subscription = :SUBSCRIPTION", true,
                              [
                                  ":SUBSCRIBER"     => $this->username,
                                  ":SUBSCRIPTION"   => $User
                              ]);
            if ($this->DB->RowNum == 1) {
                return true;
            } else {
                return false;
            }
        }

        public function channel_type($Type = NULL) {
            static $Types = array(
                -1 => "Administrator",
                0 => "",
                1 => "Director",
                2 => "Musician",
                3 => "Comedian",
                4 => "Gamer",
                5 => "Reporter",
                6 => "Guru",
                7 => "Animator"
            );
            if (isset($this->Info["channel_type"])) {
                $Type = $this->Info["channel_type"];
            }
            if ($Type == 0 or $Type == 4 or $Type == 5 or $Type == 6 or $Type == 7) {
                $Icon = "";
            }
            if ($Type == 1) {
                $Icon = "ct_2.png";
            }
            if ($Type == 2) {
                $Icon = "ct_4.png";
            }
            if ($Type == 3) {
                $Icon = "ct_6.png";
            }

            $Type = $Types[$Type];
            return array($Type,$Icon);

        }
    }
