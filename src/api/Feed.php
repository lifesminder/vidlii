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
				$data["stats"] = $this->db("SELECT users.friends, users.subscribers, users.videos_watched, users.video_views, users.channel_views, users.subscriptions FROM users WHERE users.username = :USERNAME LIMIT 1", true, ["USERNAME" => $session["user"]["username"]])["data"];
				// inbox
				$invites = $this->db("SELECT count(id) as amount FROM friends WHERE (friend_1 = :USERNAME OR friend_2 = :USERNAME) AND status = 0 AND seen = 0 AND by_user <> :USERNAME ORDER BY rand()", false, ["USERNAME" => $session["user"]["displayname"]])["data"]["amount"] ?? 0;
				$messages = $this->db("SELECT count(id) as amount FROM private_messages WHERE to_user = :USERNAME AND seen = 0", false, ["USERNAME" => $session["user"]["displayname"]])["data"]["amount"] ?? 0;
				$responses = $this->db("SELECT count(video_responses.id) as amount FROM video_responses INNER JOIN videos ON video_responses.url_response = videos.url INNER JOIN users ON users.username = videos.uploaded_by WHERE video_responses.accepted = 0 AND video_responses.response_user = :USERNAME AND video_responses.seen = 0 AND video_responses.accepted = 0 ORDER BY rand() DESC", false, ["USERNAME" => $session["user"]["displayname"]])["data"]["amount"] ?? 0;
				$comments = $this->db("SELECT video_comments.id FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE videos.uploaded_by = :USERNAME AND video_comments.by_user <> :USERNAME AND reply_to = 0 AND video_comments.seen = 0
                                 UNION ALL SELECT mentions.type FROM mentions INNER JOIN video_comments ON video_comments.id = mentions.video INNER JOIN videos ON videos.url = video_comments.url WHERE mentions.username = :USERNAME AND mentions.seen = 0
                                 UNION ALL SELECT mentions.type FROM mentions INNER JOIN channel_comments ON channel_comments.id = mentions.channel WHERE mentions.username = :USERNAME AND mentions.seen = 0
                                 UNION ALL SELECT replies.id FROM replies INNER JOIN video_comments ON video_comments.id = replies.id INNER JOIN videos ON videos.url = video_comments.url WHERE replies.for_user = :USERNAME AND replies.seen = 0
                                 UNION ALL SELECT id FROM channel_comments WHERE on_channel = :USERNAME AND by_user <> :USERNAME AND seen = 0 ORDER BY rand()
                                 ", false, ["USERNAME" => $session["user"]["displayname"]])["count"] ?? 0;
				$data["inbox"] = [
					"invites" => $invites,
					"messages" => $messages,
					"responses" => $responses,
					"comments" => $comments // <- moot, should be checked
				];
			}

			// blog
			$data["blog"] = $this->db("SELECT id, title, content from blog where date >= DATE_SUB(CURDATE(), INTERVAL 2 WEEK) limit 2", true)["data"];

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