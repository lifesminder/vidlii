<?php
	namespace Vidlii\Vidlii\API;
	
	class Playlist extends \Vidlii\Vidlii\API {
		function index($args, $files = []) {
			$data = [];
			if(isset($args["id"]) && $args["id"] != "") {
				$id = $args["id"];
				$playlist_info = $this->db("SELECT * FROM playlists WHERE purl = '$id'");
				if($playlist_info["count"] == 1) {
                    $playlist_stats = $this->db("SELECT sum(videos.displayviews) as total_views, sum(videos.comments) as total_comments, sum(videos.responses) as total_responses, sum(videos.favorites) as total_favorites FROM playlists INNER JOIN playlists_videos ON playlists_videos.purl = playlists.purl INNER JOIN videos ON playlists_videos.url = videos.url WHERE playlists.purl = '$id'");
                    $playlist_videos = $this->db("SELECT url from playlists_videos WHERE purl = '$id' order by position asc", true);

                    if($playlist_videos["count"] > 0) {
                        $apivideo = new \Vidlii\Vidlii\API\Video($_SERVER["DOCUMENT_ROOT"]);
                        for($i = 0; $i < $playlist_videos["count"]; $i++) {
                            $playlist_videos["data"][$i] = $apivideo->index(["id" => $playlist_videos["data"][$i]["url"]], []);
                            if($playlist_videos["data"][$i]["status"] == 1)
                                $playlist_videos["data"][$i] = $playlist_videos["data"][$i]["data"];
                        }
                    } else $playlist_videos = [];

					$data["status"] = 1;
					$data["data"] = $playlist_info["data"];
                    $data["data"]["stats"] = $playlist_stats["data"];
                    $data["data"]["videos"] = $playlist_videos["data"];
				} else $data = $playlist_info;
			} else $data = $this->api_message(-1, "Enter valid ID");
			return $data;
		}
	}
?>