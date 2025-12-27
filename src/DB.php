<?php
    namespace Vidlii\Vidlii;

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/init.php";

    class DB {
        public $RowNum, $active = false;
        protected $Connection;

        function __construct(bool $Show_Errors = false) {
            try {
                $dsnParser = new \Doctrine\DBAL\Tools\DsnParser();
                if(!empty($_ENV["database"])) {
                    $params = $dsnParser->parse($_ENV["database"]);
                    $pdoConn = "mysql:host=".$params["host"].";dbname=".$params["dbname"].";charset=utf8mb4";
                    $this->Connection = new \PDO($pdoConn, $params["user"] ?? "", $params["password"] ?? "");
                    if ($Show_Errors)
                        $this->Connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    $this->active = true;
                }
            } catch (\PDOException $e) {
                die("<center>".$e."</center>");
            }
        }

        public function query($query) {
            $data = [];
            if(isset($_ENV["database"]) && $_ENV["database"] != "") {
                $dsnParser = new \Doctrine\DBAL\Tools\DsnParser();
                $params = $dsnParser->parse($_ENV["database"]);
                $conn = \Doctrine\DBAL\DriverManager::getConnection($params);
                try {
                    $stmt = $conn->prepare($query);
                    if(
                        strtolower(substr($query, 0, strlen("select"))) == "select" ||
                        strtolower(substr($query, 0, strlen("show"))) == "show"
                    ) {
                        $result = $stmt->executeQuery();
                        $datas = $result->fetchAllAssociative();
                        $data = ["count" => count($datas), "data" => $datas];
                    } else {
                        $result = $stmt->executeStatement();
                        $data["status"] = $result;
                    }
                } catch(\Doctrine\DBAL\Exception $e) {
                    $data = ["status" => -1, "message" => $e->getMessage()];
                }
                $conn->close();
            } else $data = ["status" => -1, "message" => "Connection node is missing"];
            return $data;
        }

        public function execute(string $SQL, bool $Single = false, array $Execute = []): array {
            if(!$this->Connection) {
                return [];
            }

			try {
            	$Query = $this->Connection->prepare($SQL);
            	$Query->execute($Execute);
            } catch (\Exception $e) {die($e);}

            $this->RowNum = $Query->rowCount();

            if ($this->RowNum == 0) {
                return [];
            } elseif ($Single) {
                return @$Query->fetch(\PDO::FETCH_ASSOC);
            } else {
                return @$Query->fetchAll(\PDO::FETCH_ASSOC);
            }
        }

        public function modify(string $SQL, array $Execute = []): bool {
            
            if ($Execute) {
                
                foreach ($Execute as $Key => $Value) {
                    
                    $Execute[$Key] = str_ireplace("eval", "evaI", $Value);
                    if (is_null($Value)) {
                    	$Execute[$Key] = 0;
                    }
                }
                
            }
            try {
            	$Query = $this->Connection->prepare($SQL);
            	$Query->execute($Execute);
            } catch (\Exception $e) {die($e);}

            $this->RowNum = $Query->rowCount();

            if ($this->RowNum > 0) {
                return true;
            }
            return false;
        }

        public function last_id() {
            return $this->Connection->lastInsertId();
        }
    }
