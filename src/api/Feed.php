<?php
	namespace Vidlii\Vidlii\API;
	
	class Feed extends \Vidlii\Vidlii\API {
		function index($args = [], $files = null) {
			$data = [];
			$session = $this->session();

			// pure basis
			$data["last_online"] = $this->db("SELECT users.username, users.displayname, users.videos, users.favorites, users.friends FROM users WHERE users.activated = 1 ORDER BY users.last_login DESC LIMIT 5", true)["data"];
			$data["featured_videos"] = $this->db("SELECT url, title, description, tags, category, uploaded_by, uploaded_on, displayviews as views, length, featured from videos where status = 2 and featured = 1 order by uploaded_on desc limit 10", true);
			if($data["featured_videos"]["count"] > 0) {
				for($i = 0; $i < $data["featured_videos"]["count"]; $i++) {
					$data["featured_videos"]["data"][$i]["length"] = $this->seconds_to_time($data["featured_videos"]["data"][$i]["length"]);
				}
			}
			$data["popular_videos"] = $this->db("SELECT url, title, description, tags, category, uploaded_by, uploaded_on, displayviews as views, length, featured from videos where status >= 2 order by views desc limit 10", true);

			if($data["popular_videos"]["count"] > 0) {
				for($i = 0; $i < $data["popular_videos"]["count"]; $i++) {
					$data["popular_videos"]["data"][$i]["length"] = $this->seconds_to_time($data["popular_videos"]["data"][$i]["length"]);
				}
			}
			// recommended
			$data["recommended"]["channels"] = $this->db("SELECT username, subscribers, displayname, channel_title, channel_description, video_views, (SELECT sum(videos_watched.watchtime) FROM videos_watched INNER JOIN videos ON videos.url = videos_watched.vid WHERE videos.uploaded_by = users.username AND users.displayname NOT LIKE '%moonman%' AND videos_watched.submit_date >= DATE_SUB(CURDATE(), INTERVAL 4 DAY)) as watchtime_amount FROM users WHERE shadowbanned = 0 ORDER BY watchtime_amount DESC LIMIT 3", true)["data"];

			// stats
			if($session["session"] != -1) {
				$data["stats"] = $this->db("SELECT users.friends, users.subscribers, users.videos_watched, users.video_views, users.channel_views, users.subscriptions FROM users WHERE users.username = :USERNAME LIMIT 1", true, [":USERNAME" => $session["user"]["username"]])["data"];
			}

			if(isset($args["show"]) && $args["show"] != "") {
				switch(strtolower($args["show"])) {
					case "popular": $data = $data["popular_videos"]; break;
					case "featured": $data = $data["featured_videos"]; break;
				}
			}

			return $data;
		}

		function comment($args = [], $files = []) {
			$data = $this->api_message(-1, "Forbidden");
			if(isset($args["action"]) && !empty($args["action"])) {
				switch(strtolower($args["action"])) {
					case "remove": {
						if(isset($args["id"]) && !empty($args["id"])) {
							$id = (int)$args["id"];
							$removeComment = $this->db("DELETE from channel_comments where id = $id"); // To-Do: add unsanctioned comment removal
							$data = $removeComment;
						}
						break;
					}
				}
			}
			return $data;
		}

		function manage(array $args = []) {
			$data = [];
			$session = $this->session($_COOKIE["session"]);
			try {
				if($session["session"] == -1) {
					throw new \Exception();
				}

				$id = $session["user"]["id"];
				if(isset($args["action"]) && trim($args["action"]) != "") {
					switch(strtolower($args["action"])) {
						case "add_quicklist": {
							$videoGuid = !empty($args["guid"]) ? $args["guid"] : "";
							if($videoGuid != "") {
								$qlCheck = $this->db("SELECT count(*) from quicklist where user = $id and video = \"$videoGuid\"")["data"]["count(*)"];
								if($qlCheck["count"] == 0) {
									$sequence = $qlCheck + 1;
									$qlAdd = $this->db("INSERT into quicklist (user, video, sequence) values ($id, \"$videoGuid\", $sequence)");
									if($qlAdd["status"] == 1) {
										$data = $this->api_message(1, "Added to QuickList");
									} else throw new \Exception("This video is already in your QuickList");
								}
							} else throw new \Exception("Invalid Video");
							break;
						}
						default: throw new \Exception("Invalid Action");
					}
				} else throw new \Exception("Invalid Action");
			} catch(\Exception $e) {
				$data = $this->api_message(-1, $e->getMessage() ?? "Forbidden");
			}
			return $data;
		}
	}
?>