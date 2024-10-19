<?php
    namespace Vidlii\Vidlii;

    class API {
        protected $env, $dir;

        function __construct($path = __dir__, $config = ".env") {
            $this->dir = $path;
            $this->path = "$path/$config";
            if(file_exists($this->path)) {
                try {
                    $this->env = \Dotenv\Dotenv::createImmutable($path);
                    $this->env->load();
                } catch(\Exception $e) {
                    echo "Configuration not found (must be <pre>.env</pre>)";
                    exit(1);
                }
            } else {
                echo "Configuration not found";
                exit(1);
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
					if(strtolower(substr($query, 0, strlen("select"))) == "select") {
						$result = $stmt->executeQuery();
						$datas = $result->fetchAllAssociative();
						if($alwaysKeyed) $data = ["count" => count($datas), "data" => $datas];
						else $data = ["count" => count($datas), "data" => (count($datas) == 1) ? $datas[0] : $datas];
					} else {
						$result = $stmt->executeStatement();
						$data["status"] = $result;
					}
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
    }
?>