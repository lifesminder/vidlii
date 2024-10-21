<?php
	namespace Vidlii\Vidlii\API;
	
	class User extends \Vidlii\Vidlii\API {
		function index($args, $files) {
			$data = [];
			if(isset($args["u"]) && $args["u"] != "") {
				$username = $args["u"];
				$user = $this->db("SELECT username, displayname, reg_date, last_login, strikes, channel_views, channel_comments, video_views, videos, favorites, subscribers, subscriptions, friends, birthday, country, website, about, channel_title, channel_description, channel_tags from users where username = '$username' or displayname = '$username'");
				if($user["count"] >= 1) {
					$data["status"] = 1;
					$data["data"] = $user["data"];
				} else $data = $this->api_message(-1, "User Not Found");
			} else $data = $this->api_message(-1, "Enter Username");
			return $data;
		}
	}
?>