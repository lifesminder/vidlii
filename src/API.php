<?php
    namespace Vidlii\Vidlii;

    class API extends \Vidlii\Vidlii\Engine {
        protected $api, $env, $dir;
        private static \Doctrine\DBAL\Connection|null $conn;

        function __construct($path = __dir__, $config = ".env") {
            $this->dir = $path;
            $this->path = "$path/$config";
            $this->conn = null;

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

        public function db(string $query, bool $alwaysKeyed = false, array $arguments = []): array {
			$data = [];
            if($this->conn == null || !$this->conn->isConnected()) {
                $database = (isset($_ENV["database"]) && trim($_ENV["database"]) != "") ? trim($_ENV["database"]) : null;
                if($database == null) {
                    return ["status" => -1, "message" => "Connection node DSN is missing"];
                }
                $dsnParser = new \Doctrine\DBAL\Tools\DsnParser(["mysql" => "mysqli", "postgres" => "pdo_pgsql"]);
                $params = $dsnParser->parse($database);
                // Connection persistency
                $params["persistent"] = true;
                $params["memory"] = true;
				$this->conn = \Doctrine\DBAL\DriverManager::getConnection($params);
            }
            
			try {
                $isReadQuery = preg_match('/^\s*(SELECT|SHOW|DESCRIBE|PRAGMA)\b/i', $query);
				if($isReadQuery) {
                    $result = $this->conn->executeQuery($query, $arguments);
                    $rows = $result->fetchAllAssociative();
                    return [
                        "status" => 1,
                        "count" => count($rows),
                        "data" => $alwaysKeyed ? $rows : (count($rows) === 1 ? $rows[0] : $rows)
                    ];
                }

                // Write queries (INSERT / UPDATE / DELETE)
                $affected = $this->conn->executeStatement($query, $arguments);
                return [
                    "status" => 1,
                    "affected" => $affected,
                    "last" => $this->conn->lastInsertId()
                ];
			} catch(\Exception $e) {
                $data = ["status" => -1, "message" => $e->getMessage()];
            }
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
                $session["user"] = $this->db("SELECT id, username, displayname, is_admin, is_mod from users where id = ".$session["user"]);
                if($session["user"]["count"] == 1) $session["user"] = $session["user"]["data"];
            } else {
                $session = ["session" => -1, "user" => ["id" => -1, "username" => "Guest", "displayname" => "Guest"]];
            }

            if($action != null) {
                switch(strtolower($action)) {
                    case "logout": {
                        $id = $session["user"]["id"];
                        $logout = $this->db("DELETE from sessions where user = $id");
                        return $logout;
                    }
                    default: return $session;
                }
            } else {
                return $session;
            }
        }
    }
?>