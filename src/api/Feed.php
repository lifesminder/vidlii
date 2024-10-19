<?php
	namespace Vidlii\Vidlii\API;
	
	class Feed extends \Vidlii\Vidlii\API {
		function index($args, $files) {
			$data = [];

			// pure basis
			$data["last_online"] = $this->db("SELECT users.username, users.displayname, users.videos, users.favorites, users.friends FROM users WHERE users.activated = 1 ORDER BY users.last_login DESC LIMIT 5", true)["data"];

			// recommended
			$data["recommended"]["channels"] = array_slice($this->db("SELECT username, subscribers, displayname, avatar, channel_description, video_views, (SELECT sum(videos_watched.watchtime) FROM videos_watched INNER JOIN videos ON videos.url = videos_watched.vid WHERE videos.uploaded_by = users.username AND users.displayname NOT LIKE '%moonman%' AND videos_watched.submit_date >= DATE_SUB(CURDATE(), INTERVAL 4 DAY)) as watchtime_amount FROM users WHERE shadowbanned = 0 ORDER BY watchtime_amount DESC LIMIT 9", true)["data"], 0, 3);

			// stats
			$data["stats"] = $this->db("SELECT users.friends, users.subscribers, users.videos_watched, users.video_views, users.channel_views, users.subscriptions FROM users WHERE users.username = :USERNAME LIMIT 1", true, [":USERNAME" => "armenianpublictv1"])["data"];

			return $data;
		}
	}
?>