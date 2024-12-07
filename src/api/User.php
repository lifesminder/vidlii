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
			$data = [];
			if(isset($_COOKIE["session"]) && $this->session($_COOKIE["session"])["session"] != -1) {
				$session = $this->session($_COOKIE["session"]);
				if(isset($_GET["to"]) && $_GET["to"] != "") {
					$to = $_GET["to"];
					$check_existance = $this->db("SELECT username, displayname from users where username = '$to' or displayname = '$to'");
					if($check_existance["count"] == 1) {
						$from = $session["user"]["displayname"]; $to = $check_existance["data"]["displayname"];
						if($from !== $to) {
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
	}
?>