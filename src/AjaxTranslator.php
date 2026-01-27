<?php
namespace Vidlii\Vidlii;

class AjaxTranslator {
    
    private $method, $params;
    private $playlistName;
    private $view;
    private $playlistSort;
    private $encryptedPlaylistId;
    private $encryptedShmoovieId;
    private $searchQuery;
    private $postData;
    private $box;

    private \Vidlii\Vidlii\API $api;
    private \Vidlii\Vidlii\Engine $engine;
    
    public function __construct() {
        // Parse the raw POST data (messages parameter contains JSON)
        $rawInput = file_get_contents('php://input');
        $parsed = [];
        parse_str($rawInput, $parsed);

        $this->method = '';
        $this->engine = new \Vidlii\Vidlii\Engine();
        $this->api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);

        if (!empty($parsed['messages'])) {
            $messages = json_decode($parsed['messages'], true);

            if (!empty($messages[0])) {
                $msg = $messages[0];

                // 1. Method
                if (!empty($msg['request']['method'])) {
                    $this->method = $msg['request']['method'];
                } elseif (!empty($msg['type'])) {
                    $this->method = $msg['type'];
                }

                // 2. Params (THIS IS THE IMPORTANT PART)
                $params = $msg['request']['params'] ?? [];
                $this->params = $params;

                $this->box = $msg['request']['box_name'] ?? '';
                $this->playlistName = $this->params['playlist_name'] ?? '';
                $this->view = $this->params['view'] ?? 'list';
                $this->playlistSort = $this->params['playlist_sort'] ?? 'date_added';
                $this->encryptedPlaylistId = $this->params['encrypted_playlist_id'] ?? '';
                $this->encryptedShmoovieId = $this->params['encrypted_shmoovie_id'] ?? '';
                $this->searchQuery = $this->params['query'] ?? '';
            }
        }
    }
    
    public function run() {
        // Use text/javascript for compatibility with eval()
        header("Content-Type: text/javascript; charset=utf-8");
        
        // Clear output buffers to prevent whitespace
        while (ob_get_level()) {
            ob_end_clean();
        }

        try {
            $videoApi = new \Vidlii\Vidlii\API\Video($_SERVER["DOCUMENT_ROOT"]);
            $user = new \Vidlii\Vidlii\API\User($_SERVER["DOCUMENT_ROOT"]);
            $session = $this->api->session();

            switch($this->method) {
                // friends, block and etc
                case "add_friend": {
                    $response = $this->handleAddFriend();
                    break;
                }
                case "remove_friend": {
                    $response = $this->handleRemoveFriend();
                    break;
                }
                // page navigation
                case "load_playlist": {
                    switch($this->playlistName) {
                        case "uploads": {
                            $videos = $user->index(["u" => $_GET["user"], "p" => "videos", "sort" => $this->params["playlist_sort"] ?? "date"]);
                            if($videos["count"] > 0) {
                                $videos = $videos["data"];
                                foreach($videos as $i => $v) {
                                    $videos[$i] = $videoApi->index(["id" => $v["url"]])["data"] ?? ["url" => $v["url"]];
                                }
                            } else {
                                $videos = [];
                            }
                            break;
                        }
                        case "favorites": {
                            $videos = $this->api->db("SELECT url from video_favorites where favorite_by = :user", true, [
                                "user" => $_GET["user"]
                            ]);
                            if($videos["count"] > 0) {
                                foreach($videos["data"] as $i => $fav) {
                                    $videos["data"][$i] = $videoApi->index(["id" => $fav["url"]])["data"] ?? [];
                                }
                            }
                            break;
                        }
                    }

                    $response = [
                        "type" => $this->method,
                        "success" => true,
                        "data" => $this->engine->template("nouveau/2/pages/".$this->playlistName.".html", ["videos" => $videos], true)
                    ];
                    break;
                }
                case "load_popup_comments": {
                    $video = $videoApi->index(["id" => $this->params["video_id"]]);
                    $comments = $this->api->db("SELECT * from video_comments where url = :video limit 20", true, ["video" => $this->params["video_id"]]);
                    $response = [
                        "type" => $this->method,
                        "success" => true,
                        "data" => $this->engine->template("nouveau/2/popup/comments.html", ["video" => $video, "comments" => $comments, "session" => $session], true)
                    ];
                    break;
                }
                // ajax facilities
                case "get_video_metadata_ajax": {
                    $video = $videoApi->index(["id" => $this->params["video_id"]]);
                    if($video["status"] == 1) {
                        $video = $video["data"];
                        $response = [
                            "type" => $this->method,
                            "success" => true,
                            "data" => [
                                "info_panel_html" => $this->engine->template("nouveau/2/popup/info.html", ["video" => $video, "session" => $session], true),
                                "js_exec" => "console.log('hey');"
                            ]
                        ];
                    } else {
                        $response = [
                            "type" => $this->method,
                            "success" => true,
                            "data" => [
                                "error" => "Video not found (".json_encode($video).")"
                            ]
                        ];
                    }
                    break;
                }
                // other
                default: {
                    throw new \Exception("Unknown method: " . $this->method);
                }
            }
        } catch (\Exception $e) {
            $response = [
                "type" => $this->method,
                "success" => false,
                "code" => 'ERROR',
                "error" => $e->getMessage(),
                "data" => [
                    "html" => '<div class="error">' . htmlspecialchars($e->getMessage()) . '</div>'
                ]
            ];
        }

        echo $this->formatResponse($response);
        exit;
    }
    
    private function handleAddFriend() {
        // Parse username from the request
        $session = $this->api->session();
        $rawInput = file_get_contents('php://input');
        $parsed = [];
        parse_str($rawInput, $parsed);

        $username = 'Unknown';
        if (!empty($parsed['messages'])) {
            $messages = json_decode($parsed['messages'], true);
            if (!empty($messages[0]['request']['params']['username'])) {
                $username = $messages[0]['request']['params']['username'];
            }
        }

        try {
            $checkUser = $this->api->db("SELECT username, can_friend FROM users WHERE username = :USERNAME LIMIT 1", false, ["USERNAME" => $username]);
            if($checkUser["count"] == 1) {
                $username = $checkUser["data"]["username"];
                if($username != $session["user"]["username"] || $username != $session["user"]["displayname"]) {
                    $friendshipStatus = $this->api->db("SELECT status, by_user, seen FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)", false, ["USERNAME" => $session["user"]["username"], "FRIEND" => $username]);
                    $blockedStatus = $this->api->db("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OTHER) OR (blocker = :OTHER AND blocked = :USERNAME)", false, ["USERNAME" => $session["user"]["username"], "OTHER" => $username]);
                    if($blockedStatus["count"] == 0 && $friendshipStatus["status"] == 1) {
                        $status = (int)$friendshipStatus["data"]["status"];
                        $by = $friendshipStatus["data"]["by_user"];
                        $seen = $friendshipStatus["data"]["seen"];

                        if($status == 0) {
                            
                        } else {

                        }
                        return [
                            "type" => $this->method,
                            "success" => true,
                            "data" => [
                                "requested" => false,
                                "success" => false,
                                "message" => "status=$status,by=$by,seen=$seen"
                            ]
                        ];
                    } else throw new \Exception("You cannot friend with account blocked by you");
                } else throw new \Exception("You cannot friend to yourself");
            } else throw new \Exception($checkUser["data"]);
        } catch(\Exception $e) {
            return [
                "type" => $this->method,
                "success" => true,
                "data" => [
                    "requested" => false,
                    "success" => false,
                    "message" => $e->getMessage()
                ]
            ];
        }

        return [
            "type" => $this->method,
            "success" => true,
            "data" => [
                "requested" => true,
                "success" => false,
                "message" => "Friend request sent to " . htmlspecialchars($username)
            ]
        ];
    }

    private function handleRemoveFriend() {
        // Parse username from the request
        $rawInput = file_get_contents('php://input');
        $parsed = [];
        parse_str($rawInput, $parsed);

        $username = 'Unknown';
        if (!empty($parsed['messages'])) {
            $messages = json_decode($parsed['messages'], true);
            if (!empty($messages[0]['request']['params']['username'])) {
                $username = $messages[0]['request']['params']['username'];
            }
        }

        return [
            "type" => $this->method,
            "success" => true,
            "data" => [
                "requested" => false,
                "success" => false,
                "message" => "Friend request cancelled to " . htmlspecialchars($username)
            ]
        ];
    }

    // ============= RESPONSE FORMATTER =============
    private function formatResponse($response) {
        $json = json_encode([$response], JSON_UNESCAPED_SLASHES);
        // NO whitespace between prefix and JSON
        return 'while(1);' . $json;
    }
}
?>