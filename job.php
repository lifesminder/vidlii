<?php
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
    require "vendor/autoload.php";
    require "_includes/_classes/upload.class.php";
    require "_includes/_classes/ffmpeg.class.php";

    $dotenv = \Dotenv\Dotenv::createImmutable(__dir__);
    $dotenv->load();

    function db($query) {
        $database = $_ENV["database"];
        $data = [];
        if(isset($database) && $database != "") {
            $dsnParser = new \Doctrine\DBAL\Tools\DsnParser();
            $params = $dsnParser->parse($database);
            $conn = \Doctrine\DBAL\DriverManager::getConnection($params);
            try {
                $stmt = $conn->prepare($query);
                if(strtolower(substr($query, 0, strlen("select"))) == "select") {
                    $result = $stmt->executeQuery();
                    $datas = $result->fetchAssociative();
                    $data = ["count" => count($datas), "data" => $datas];
                } else {
                    $result = $stmt->executeStatement();
                    $data["status"] = (int)$result;
                }
            } catch(\Doctrine\DBAL\Exception $e) {
                $data = ["status" => -1, "message" => $e->getMessage()];
            }
            $conn->close();
        } else $data = ["status" => -1, "message" => "Connection node is missing"];
        return $data;
    }
    
    $preConvs = scandir("usfi/conv");
    if(count($preConvs) >= 3) {
        foreach($preConvs as $x) {
            if($x == '.' || $x == '..') continue;
            else {
                $id = explode(".", $x)[0];
                echo "[$id]\n";
                $ffmpeg = new \ffmpeg("usfi/conv/$x", "usfi/v/$id.mp4");
                $ffmpeg->Get_Info();
                $ffmpeg->Make_Thumbnails(3, $x);
                
                if(file_exists(__dir__."/usfi/prvw/$id.file.jpg")) {
                    $_thumb = base64_encode(file_get_contents(__dir__."/usfi/prvw/$id.file.jpg"));
                    $updateThumb = db("update videos set thumbnail = '$_thumb' where url = '$id'");
                    if($updateThumb["status"] == 0) {
                        @unlink(__dir__."/usfi/prvw/$id.file.jpg"); @unlink(__dir__."/usfi/thmp/$id.file.jpg");
                        echo "Thumbnail Updated! Next...\n\n";
                        $makeConverting = db("update videos set status = 1 where url = '$id'");
                        if($makeConverting["status"] == 0) {
                            $ffmpeg->Resize(480); $ffmpeg->Convert();
                            if(file_exists(__dir__."/usfi/v/$id.mp4")) {
                                foreach(glob(__dir__."/usfi/conv/$id*") as $f) {
                                    unlink($f);
                                }
                                $deleteConverting = db("update videos set status = 2 where url = '$id'");
                                if($deleteConverting["status"] == 0) {
                                    $deleteConvertingTotally = db("delete from converting where uri = '$id'");
                                    rename(__dir__."/usfi/v/$id.mp4", __dir__."/usfi/v/$id..mp4");
                                } else echo "ERROR!: ".$makeConverting["message"]."\n";
                            } else echo "ERROR!: File Doesn't exists\n";
                        } else echo "ERROR!: ".$makeConverting["message"]."\n";
                    } else echo "ERROR!: ".$updateThumb["message"]."\n";
                }
            }
        }
    } else echo "Nothing to do. Exiting...\n";
?>