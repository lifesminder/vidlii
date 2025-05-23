<?php
	namespace Vidlii\Vidlii\API;
	
	class Video extends \Vidlii\Vidlii\API {
		function index($args, $files = []) {
			$data = [];
			if(isset($args["id"]) && $args["id"] != "") {
				$id = $args["id"];
				$video_info = $this->db("SELECT url, title, description, tags, category, uploaded_on, uploaded_by, length, length as duration, views, watched, comments, responses, favorites, featured from videos WHERE status > 1 and url = '$id'");
				if($video_info["count"] == 1) {
					$data["status"] = 1;
					$data["data"] = $video_info["data"];
					$data["data"]["length"] = $data["data"]["duration"];
					$data["data"]["duration"] = $this->seconds_to_time($data["data"]["duration"]);
				} else $data = $video_info;
			} else $data = $this->api_message(-1, "Enter valid ID");
			return $data;
		}
	}
?>