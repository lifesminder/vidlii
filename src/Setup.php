<?php
    /**
     * VidLii Instance Setup Routine
     */

    namespace Vidlii\Vidlii;

    class Setup extends Engine {
        public function process(array $data = []) {
            header("Content-Type: text/json");
            try {
                if(isset($data["step"]) && (int)$data["step"] > 0) {
                    $step = (int)$data["step"];
                    switch($step) {
                        case 1: {
                            $host = $data["host"] ?? ""; $username = $data["username"] ?? ""; $password = $data["password"] ?? ""; $db = $data["name"] ?? "";
                            if($host == "" || $username == "" || $db == "") {
                                throw new \Exception("Correctly fill your DB host, username and name");
                            }
                            // To-Do: mysqli replace with DB type
                            $node = "mysqli://".$username.($password != "" ? ":".urlencode($password) : "")."@".$host;

                            // Test the connection
                            try {
                                $dsnParser = new \Doctrine\DBAL\Tools\DsnParser();
                                $connectionParams = $dsnParser->parse($node);
                                $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

                                // Initialize schema manager and create DB, if exists, delete and make new one
                                $schemaManager = $conn->createSchemaManager();
                                $existingDatabases = array_map(function($e) { return strtolower($e); }, $schemaManager->listDatabases());
                                if(in_array(strtolower($db), $existingDatabases)) {
                                    $schemaManager->dropDatabase($db);
                                }
                                $schemaManager->createDatabase($db);

                                // Make a cookie with Step 1 details
                                $node .= "/".$db."?charset=utf8mb4";
                                setcookie("s1-data", $this->encrypt($node), [
                                    "expires" => time() + (86400 * 30),
                                    "path" => "/setup",
                                    "samesite" => "Strict",
                                    "secure" => false,
                                    "httponly" => false
                                ]);
                                $conn->close();
                                $data = ["status" => 1, "step" => 2];
                            } catch (\Exception $e) {
                                $data = ["status" => -1, "message" => (str_contains($e->getMessage(), ":") ? end(explode(": ", $e->getMessage())) : $e->getMessage())];
                            }
                            break;
                        }
                        case 2: {
                            $node = $this->decrypt($_COOKIE["s1-data"] ?? "");
                            if($node != "") {
                                try {
                                    $dsnParser = new \Doctrine\DBAL\Tools\DsnParser();
                                    $connectionParams = $dsnParser->parse($node);
                                    $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

                                    // Now importing the entire schema
                                    $schemaFile = $_SERVER["DOCUMENT_ROOT"]."/schema.sql";
                                    if(file_exists($schemaFile) && is_readable($schemaFile)) {
                                        $sql = file($schemaFile);
                                        $query = ""; $affectedRows = 0; $dbErrors = 0;

                                        try {
                                            $conn->beginTransaction();
                                            foreach($sql as $line) {
                                                $startWith = substr(trim($line), 0, 2);
                                                $endWith = substr(trim($line), -1, 1);

                                                if(empty(trim($line)) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                                                    continue;
                                                }

                                                $query .= $line;
                                                $query = str_replace("\r\n", "", $query);
                                                if($endWith == ';') {
                                                    try {
                                                        $conn->executeStatement($query);
                                                        $affectedRows++;
                                                    } catch(\Exception $e) {
                                                        $dbErrors++;
                                                    }
                                                    $query = "";
                                                }
                                            }
                                            $conn->commit();

                                            // If import done successfully, deal with env-file adding
                                            if($dbErrors == 0) {
                                                $envFile = $_SERVER["DOCUMENT_ROOT"]."/.env";
                                                if(!file_exists($envFile)) {
                                                    $file = fopen($envFile, "w");
                                                    fwrite($file, "database = \"".$node."\"\n");
                                                    fwrite($file, "host = \"".$_SESSION["host"]."\"\n");
                                                    fwrite($file, "db = \"".$_SESSION["name"]."\"\n");
                                                    fwrite($file, "username = \"".$_SESSION["username"]."\"\n");
                                                    if(isset($_SESSION["password"]) && $_SESSION["password"] != "")
                                                        fwrite($file, "password = \"".$_SESSION["password"]."\"\n");
                                                    fclose($file);
                                                } else $data = ["status" => -1, "message" => "Cannot modify existing env-file"];
                                            } else $data = ["status" => -1, "message" => $dbErrors." imports have been failed"];

                                            // And apply user-specified settings
                                            $title = $data["title"]; $slogan = $data["slogan"];
                                            $admin_username = $data["adminuser"]; $admin_email = $data["adminemail"]; $admin_password = $data["adminpass"]; $admin_password2 = $data["adminpass2"];

                                            $insert_title = $conn->executeQuery("INSERT into options (option_name, value) values ('title', '$title')");
                                            $insert_slogan = $conn->executeQuery("INSERT into options (option_name, value) values ('slogan', '$slogan')");
                                            if($insert_title == 1 && $insert_slogan == 1) {
                                                $register_admin = $conn->executeQuery("INSERT INTO users (username,displayname,email,password,reg_date,last_login,birthday,1st_latest_ip,country, partner, is_admin, is_mod) VALUES ('$admin_username', '$admin_username', '$admin_email', '".password_hash($admin_password, PASSWORD_BCRYPT)."', NOW(), NOW(), '1910-01-01', '127.0.0.1', 'US', 1, 1, 1)");
                                                if($register_admin == 1) {
                                                    $data = ["status" => 1, "step" => 3];
                                                } else throw new \Exception($register_admin);
                                            } else $data = ["status" => -1, "message" => "Cannot apply instance settings"];

                                            $conn->close();
                                        } catch(\Exception $e) {
                                            // Re-throw the exception after rolling back
                                            $conn->rollBack();
                                            throw $e;
                                        }
                                    } else $data = ["status" => -1, "message" => "Cannot find appropriate schema"];
                                } catch (\Exception $e) {
                                    $data = ["status" => -1, "message" => (str_contains($e->getMessage(), ":") ? end(explode(": ", $e->getMessage())) : $e->getMessage())];
                                }
                            } else $data = ["status" => -1, "message" => "Incorrect connection credentials"];
                            break;
                        }
                        default: $data = ["status" => -1, "message" => "Forbidden"];
                    }
                } else $data = ["status" => -1, "message" => "Forbidden"];
            } catch(\Exception $e) {
                $data = ["status" => -1, "message" => $e->getMessage()];
            }
            echo json_encode($data);
        }
        public function show() {
            $this->template("setup.html");
        }
    }
?>