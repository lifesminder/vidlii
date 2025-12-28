<?php
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);
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

    $bin_path = (PHP_OS == "WINNT") ? "bin\\win" : "bin/linux";
    if(PHP_OS == "WINNT") {
        $config = [
            'ffmpeg.binaries' => "$bin_path\\ffmpeg.exe",
            'ffprobe.binaries' => "$bin_path\\ffprobe.exe"
        ];
    } else {
        $config = [
            'ffmpeg.binaries' => "$bin_path/ffmpeg",
            'ffprobe.binaries' => "$bin_path/ffprobe"
        ];
    }
    $config += [
        'timeout' => 12000,
        'ffmpeg.threads' => 12
    ];
    $ffmpeg = \FFMpeg\FFMpeg::create($config);
    $ffprobe = \FFMpeg\FFProbe::create();
    
    $preConvs = scandir("usfi/conv");
    for($i = 0; $i < count($preConvs); $i++) {
        $id = $preConvs[$i];

        // These are directory listing-specific dots
        if($id == "." || $id == "..") {
            continue;
        }

        if(end(explode(".", $id)) == "file") {
            $id = explode(".", $id)[0];
            $full_path = "usfi/conv/$id";
            $video = $ffmpeg->open($full_path.".file"); 
            $duration = floor($ffprobe->format($full_path.".file")->get('duration'));

            if(!file_exists($full_path.".jpg")) {
                // 1: Generating a good thumbnail
                echo "[$id] Generating thumbnail...\n";
                $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(rand(0, $duration)));
                $frame->save("usfi/conv/$id.jpg");
            }
            if(!file_exists($full_path.".mp4")) {
                // 2: Converting to MP4 (soon for "partners" will be a HD too)
                $mark_as_converting = db("UPDATE videos set length = $duration, status = 1 where url = '$id'");
                $format = new \FFMpeg\Format\Video\X264();
                $format->on("progress", function($video, $format, $percentage) {
                    global $id;
                    echo "\r[$id] Converting ($percentage%)";
                });
                $video->filters()->resize(new \FFMpeg\Coordinate\Dimension(854, 480))->synchronize();
                $video->save($format, "usfi/conv/$id.mp4");
                echo "\n";
            }
        }

        // 3: Finalizing (there should be an alternative way to hold videos)
        if(file_exists("usfi/conv/$id.mp4")) {
            rename("usfi/conv/$id.mp4", "usfi/v/$id.mp4");
            @unlink("usfi/conv/$id.file");
        }
        // 3.1: Uploading thumbnail to DB
        $id = explode(".", $id)[0];
        $thumbnail = base64_encode(file_get_contents("usfi/conv/$id.jpg"));
        $update_thumb = db("UPDATE videos set thumbnail = '$thumbnail' where url = '$id'");
        if($update_thumb["status"] >= 0) unlink("usfi/conv/$id.jpg");
        // 3.2: Updating status
        $mark_as_complete = db("UPDATE videos set status = 2 where url = '$id'");
        $delete_from_converting = db("DELETE from converting where url = '$id'");
    }
?>