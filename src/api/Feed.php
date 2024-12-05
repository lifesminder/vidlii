<?php
	namespace Vidlii\Vidlii\API;
	
	class Feed extends \Vidlii\Vidlii\API {
		function seconds_to_time($Seconds) {
			$min = intval($Seconds / 60);
			return $min . ':' . str_pad(($Seconds % 60), 2, '0', STR_PAD_LEFT);
		}

		function index($args, $files) {
			$data = [];

			// pure basis
			$data["last_online"] = $this->db("SELECT users.username, users.displayname, users.videos, users.favorites, users.friends FROM users WHERE users.activated = 1 ORDER BY users.last_login DESC LIMIT 5", true)["data"];
			$data["featured_videos"] = $this->db("SELECT url, title, description, tags, category, uploaded_by, uploaded_on, views, length, featured from videos where privacy = 0 and featured = 1 order by uploaded_on desc", true);
			if($data["featured_videos"]["count"] > 0) {
				for($i = 0; $i < $data["featured_videos"]["count"]; $i++) {
					$data["featured_videos"]["data"][$i]["length"] = $this->seconds_to_time((int)$data["featured_videos"]["data"][$i]["length"]);
				}
			}
			$data["popular_videos"] = $this->db("SELECT url, title, description, tags, category, uploaded_by, uploaded_on, views, length, featured from videos where privacy = 0 order by views desc", true);

			if($data["popular_videos"]["count"] > 0) {
				for($i = 0; $i < $data["popular_videos"]["count"]; $i++) {
					$data["popular_videos"]["data"][$i]["length"] = $this->seconds_to_time((int)$data["popular_videos"]["data"][$i]["length"]);
				}
			}
			// recommended
			$data["recommended"]["channels"] = $this->db("SELECT username, subscribers, displayname, channel_title, channel_description, video_views, (SELECT sum(videos_watched.watchtime) FROM videos_watched INNER JOIN videos ON videos.url = videos_watched.vid WHERE videos.uploaded_by = users.username AND users.displayname NOT LIKE '%moonman%' AND videos_watched.submit_date >= DATE_SUB(CURDATE(), INTERVAL 4 DAY)) as watchtime_amount FROM users WHERE shadowbanned = 0 ORDER BY watchtime_amount DESC LIMIT 9", true)["data"];

			// stats
			$data["stats"] = $this->db("SELECT users.friends, users.subscribers, users.videos_watched, users.video_views, users.channel_views, users.subscriptions FROM users WHERE users.username = :USERNAME LIMIT 1", true, [":USERNAME" => "armenianpublictv1"])["data"];

			return $data;
		}
	}
?>