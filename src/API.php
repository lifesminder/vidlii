<?php
    namespace Vidlii\Vidlii;

    class API extends \Vidlii\Vidlii\Engine {
        protected $env, $dir;

        function __construct($path = __dir__, $config = ".env") {
            $this->dir = $path;
            $this->path = "$path/$config";
            if(file_exists($this->path)) {
                try {
                    $this->env = \Dotenv\Dotenv::createImmutable($path);
                    $this->env->load();
                } catch(\Exception $e) {
                    echo "Configuration not found (must be <code>.env</code>)";
                    exit(1);
                }
            } else {
                echo "";
            }
        }

        function db($query, $alwaysKeyed = false) {
			$data = []; $database = $_ENV["database"];
			if(isset($database) && $database != "") {
				$dsnParser = new \Doctrine\DBAL\Tools\DsnParser();
				$params = $dsnParser->parse($database);
				$conn = \Doctrine\DBAL\DriverManager::getConnection($params);
				try {
					$stmt = $conn->prepare($query);
					if(strtolower(substr($query, 0, strlen("select"))) == "select" || strtolower(substr($query, 0, strlen("show"))) == "show") {
						$result = $stmt->executeQuery();
						$datas = $result->fetchAllAssociative();
						if($alwaysKeyed) $data = ["count" => count($datas), "data" => $datas];
						else $data = ["count" => count($datas), "data" => (count($datas) == 1) ? $datas[0] : $datas];
					} else {
						$result = $stmt->executeStatement();
						$data["status"] = $result;
                        if(!$result) $data["message"] = $stmt->error;
					}
				} catch(\Doctrine\DBAL\DBALException $e) {
					$data = ["status" => -1, "message" => $e->getMessage()];
                } catch(\Doctrine\DBAL\Exception $e) {
					$message = end(explode(": ", $e->getMessage()));
					$data = ["status" => -1, "message" => $message];
				}
				$conn->close();
			} else $data = ["status" => -1, "message" => "Connection node is missing"];
			return $data;
        }

        function api_message($status, $message = null) {
            $data = [];
            $data["status"] = $status;
            if($message != null && $message != "") $data["message"] = $message;
            return $data;
        }

        function point($query, $method, $args = [], $files = []) {
            $points = explode("/", $query);
            $query = $points[0]; $class = __namespace__."\\API\\".ucfirst($query);
            $fx = ($method != "GET") ? strtolower($method)."_" : "";
            $fx .= (count($points) > 1) ? end($points) : "index";
            $headers = getallheaders();

            if(class_exists($class)) {
                if(method_exists($class, $fx)) {
                    $entry = new $class($_SERVER["DOCUMENT_ROOT"]);
                    $data = $entry->$fx($args, $files);
                } else $data = $this->api_message(-1, "Forbidden");
            } else $data = $this->api_message(-1, "Forbidden");
            
            header("Content-Type: application/json");
            header("Cache-Control: no-cache no-store");
            header("X-Content-Type-Options: nosniff");
            header("X-XSS-Protection: 1; mode=block");
            header("X-Frame-Options: Deny");
            echo json_encode($data);
        }

        public function seconds_to_time($Seconds) {
            $hours = floor($Seconds / 3600);
            $minutes = floor(($Seconds % 3600) / 60);
            $seconds = floor($Seconds % 60);
            if($seconds < 10) $seconds = "0$seconds";
            return ($hours > 0) ? "$hours:$minutes:$seconds" : "$minutes:$seconds";
        }

        function session($token = null, $action = null) {
            $cookie = ($token != null) ? $token : (isset($_COOKIE["session"]) && $_COOKIE["session"] != "" ? $_COOKIE["session"] : null);
            $session = $this->db("SELECT session, user, ip, remembered from sessions where session = '$cookie'");
            if($session["count"] == 1) {
                $session = $session["data"];
                $session["user"] = $this->db("SELECT id, username, displayname from users where id = ".$session["user"]);
                if($session["user"]["count"] == 1) $session["user"] = $session["user"]["data"];
            } else {
                $session = ["session" => -1, "user" => ["id" => -1, "username" => "Guest", "displayname" => "Guest"]];
            }

            if($action != null) {
                $result = null;
                switch(strtolower($action)) {
                    case "logout": {
                        $id = $session["user"]["id"];
                        $logout = $this->db("DELETE from sessions where user = $id");
                        return $logout;
                        break;
                    }
                    default: return $session;
                }
                return $result;
            } else {
                return $session;
            }
        }
    }
?>