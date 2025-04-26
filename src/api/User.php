<?php
	namespace Vidlii\Vidlii\API;
	
	class User extends \Vidlii\Vidlii\API {
		function index($args, $files) {
			$data = []; $session = $this->session($_COOKIE["session"]);
			if(isset($args["u"]) && $args["u"] != "") {
				$username = $args["u"];
				$user = $this->db("SELECT username, displayname, reg_date, last_login, strikes, channel_views, channel_comments, video_views, videos, favorites, (select count(*) from subscriptions where subscription = users.username) as subscribers, subscriptions, friends, birthday, country, website, about, channel_title, channel_description, channel_tags from users where username = '$username' or displayname = '$username'");
				if($user["count"] >= 1) {
					if(isset($args["p"]) && $args["p"] != "") {
						switch(strtolower($args["p"])) {
							case "subscribers": {
								$subscribers = $this->db("SELECT subscriber from subscriptions where subscription = '$username'");
								if($subscribers["count"] > 0) {
									$subscribers = $subscribers["data"];
									for($i = 0; $i < count($subscribers); $i++) {
										$subscriber = $subscribers[$i]["subscriber"];
										$subscribers[$i] = $this->db("SELECT username, displayname, (select count(*) from subscriptions where subscription = users.username) as subscribers from users where username = '$subscriber' or displayname = '$subscriber'");
										if($subscribers[$i]["count"] == 1)
											$subscribers[$i] = $subscribers[$i]["data"];
									}

									$data["status"] = 1;
									$data["data"] = $subscribers;
								} else $data = $this->api_message(-1, "This user have no any subscribers");
								break;
							}
							case "subscriptions": {
								$subscribers = $this->db("SELECT subscription from subscriptions where subscriber = '$username'");
								$data = $subscribers;
								if($subscribers["count"] > 0) {
									$subscribers = $subscribers["data"];
									for($i = 0; $i < count($subscribers); $i++) {
										$subscriber = $subscribers[$i]["subscription"];
										$subscribers[$i] = $this->db("SELECT username, displayname, (select count(*) from subscriptions where subscription = users.username) as subscribers from users where username = '$subscriber' or displayname = '$subscriber'");
										if($subscribers[$i]["count"] == 1)
											$subscribers[$i] = $subscribers[$i]["data"];
									}

									$data["status"] = 1;
									$data["data"] = $subscribers;
								} else $data = $this->api_message(-1, "This user have no any subscriptions");
								break;
							}
						}
					} else {
						$data["status"] = 1;
						$data["data"] = $user["data"];
						if($session["session"] != -1) {
							$data["data"]["subscribed"] = (bool)$this->db("SELECT count(*) from subscriptions where subscriber = '".$session["user"]["username"]."' and subscription = '".$data["data"]["username"]."'")["data"]["count(*)"];
						}
					}
				} else $data = $this->api_message(-1, "User Not Found");
			} else $data = $this->api_message(-1, "Enter Username");
			return $data;
		}

		function subscribe($args, $files = []) {
			$data = []; $session = $this->session($_COOKIE["session"]);
			if(isset($_COOKIE["session"]) && $session["session"] != -1) {
				if(isset($_GET["to"]) && $_GET["to"] != "") {
					$to = $_GET["to"];
					$check_existance = $this->db("SELECT username, displayname from users where username = \"$to\" or displayname = \"$to\"");
					if($check_existance["count"] == 1) {
						$from = $session["user"]["displayname"]; $to = $check_existance["data"]["displayname"];
						if($from != $to) {
							$is_subscribed = (bool)$this->db("SELECT count(*) from subscriptions where subscriber = '$from' and subscription = '$to'")["data"]["count(*)"];
							if($is_subscribed) {
								$unsubscribe = $this->db("DELETE from subscriptions where subscriber = '$from' and subscription = '$to'");
								if($unsubscribe["status"] == 1) {
									$unsubscription_visual = $this->db("UPDATE users set subscriptions = subscriptions - 1 where username = '$from'");
									$unsubscribe_visual = $this->db("UPDATE users set subscribers = subscribers - 1 where username = '$to'");
									if($unsubscribe_visual["status"] >= 1 && $unsubscription_visual["status"] >= 1) {
										$data = $this->api_message(1, "Unsubscribed successfully");
									} else $data = $unsubscribe_visual;
								} else $data = $unsubscribe;
							} else {
								$subscribe = $this->db("INSERT into subscriptions (subscriber, subscription, submit_date, source) values ('$from', '$to', '".date("Y-m-d")."', 'channels')");
								if($subscribe["status"] == 1) {
									$subscription_visual = $this->db("UPDATE users set subscriptions = subscriptions + 1 where username = '$from'");
									$subscribe_visual = $this->db("UPDATE users set subscribers = subscribers + 1 where username = '$to'");
									if($subscribe_visual["status"] == 1 && $subscription_visual["status"] == 1) {
										$data = $this->api_message(1, "Subscribed successfully");
									} else $data = $subscribe_visual;
								} else $data = $subscribe;
							}
						} else $data = $this->api_message(-1, "You can't subscribe to yourself");
					} else $data = $this->api_message(-1, "User not found");
				} else $data = $this->api_message(-1, "Select channel where you want to subscribe");
			} else $data = $this->api_message(-1, "You must log in to subscribe to others");
			return $data;
		}

		function check($args, $files = null) {
			$data = [];
			if(isset($args["u"]) && $args["u"] != "") {
				$username = htmlspecialchars($args["u"]);
				$availability = (bool)$this->db("SELECT count(*) from users where username = \"$username\" or displayname = \"$username\"")["data"]["count(*)"];
				$data["available"] = !$availability;
			} else $data = $this->api_message(-1, "Enter username to check");
			return $data;
		}

		function inbox($args, $files = null) {
			$data = []; $session = $this->session($_COOKIE["session"]);
			if($session["session"] != -1) {
				$username = $session["user"]["username"];
				/*
				$data["invites"] = $api->db("SELECT count(id) as amount FROM friends WHERE (friend_1 = :USERNAME OR friend_2 = :USERNAME) AND status = 0 AND seen = 0 AND by_user <> \"$username\" ORDER BY rand()", true);
				$Messages = $DB->execute("SELECT count(id) as amount FROM private_messages WHERE to_user = :USERNAME AND seen = 0", true, [":USERNAME" => $_USER->username]);
				$Responses  = $DB->execute("SELECT count(video_responses.id) as amount FROM video_responses INNER JOIN videos ON video_responses.url_response = videos.url INNER JOIN users ON users.username = videos.uploaded_by WHERE video_responses.accepted = 0 AND video_responses.response_user = :USERNAME AND video_responses.seen = 0 AND video_responses.accepted = 0 ORDER BY rand() DESC", true, [":USERNAME" => $_USER->username]);
				$Comments = $DB->execute("SELECT video_comments.id FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE videos.uploaded_by = :USERNAME AND video_comments.by_user <> :USERNAME AND reply_to = 0 AND video_comments.seen = 0
                                 UNION ALL SELECT mentions.type FROM mentions INNER JOIN video_comments ON video_comments.id = mentions.video INNER JOIN videos ON videos.url = video_comments.url WHERE mentions.username = :USERNAME AND mentions.seen = 0
                                 UNION ALL SELECT mentions.type FROM mentions INNER JOIN channel_comments ON channel_comments.id = mentions.channel WHERE mentions.username = :USERNAME AND mentions.seen = 0
                                 UNION ALL SELECT replies.id FROM replies INNER JOIN video_comments ON video_comments.id = replies.id INNER JOIN videos ON videos.url = video_comments.url WHERE replies.for_user = :USERNAME AND replies.seen = 0
                                 UNION ALL SELECT id FROM channel_comments WHERE on_channel = :USERNAME AND by_user <> :USERNAME AND seen = 0 ORDER BY rand()
                                 ", true, [":USERNAME" => $_USER->username]);

				$Inbox_Amounts["messages"]  = $Messages["amount"];
				$Inbox_Amounts["comments"]  = $DB->RowNum;
				$Inbox_Amounts["invites"]   = $Invites["amount"];
				$Inbox_Amounts["responses"] = $Responses["amount"];

				$Inbox_Amount = $Inbox_Amounts["messages"] + $Inbox_Amounts["comments"] + $Inbox_Amounts["invites"] + $Inbox_Amounts["responses"];
				*/
				if(isset($args["type"]) && $args["type"] != "") {
					$type = strtolower($args["type"]);
					$data["i_type"] = $type;
				} else {
					$messages = $this->db("SELECT count(id) as count from private_messages where to_user = \"$username\" and seen = 0")["data"]["count"];
					$comments = $this->db("SELECT count(video_comments.id) as count from video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE videos.uploaded_by = \"$username\" and seen = 0")["data"]["count"];
					$invites = $this->db("SELECT count(id) as count from friends WHERE (friend_1 = \"$username\" OR friend_2 = \"$username\") AND status = 0 AND seen = 0 AND by_user <> \"$username\"")["data"]["count"];
					$responses = $this->db("SELECT count(video_responses.id) as count from video_responses INNER JOIN videos ON video_responses.url_response = videos.url INNER JOIN users ON users.username = videos.uploaded_by WHERE video_responses.accepted = 0 AND video_responses.response_user = \"$username\" AND video_responses.seen = 0 AND video_responses.accepted = 0")["data"]["count"];

					$data["notifications"] = $messages + $comments + $invites + $responses;
				}
			} else $data = $this->api_message(-1, "Forbidden");
			return $data;
		}

		function featureds($args, $files = []) {
			$data = []; $session = $this->session($_COOKIE["session"]);
			if($session["session"] != -1) {
				$username = $session["user"]["username"];
				$featured_stuff = $this->db("SELECT featured_channels as channels, playlists from users where displayname = \"$username\"");
				$data = $featured_stuff;
			} else {
				$data = $this->api_message(-1, "Forbidden");
			}
			return $data;
		}
	}
?>