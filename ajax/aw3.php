<?php
header_remove();
// If referer is not from VidLii, reject
$origin = $_SERVER['HTTP_REFERER'];
if (strpos($origin, "") !== 0) {
	if (strpos($origin, "https://vidlii.com") !== 0) {
		die("error");
	}	
}



define("DB_HOST",           "localhost");
define("DB_DATABASE",       "vidlii_main");
define("DB_USER",           "vidlii");
define("DB_PASSWORD",       "7vTp<6TZ");

class Database {
    public      $RowNum;

    protected   $Connection;

    function __construct(bool $Show_Errors = false) {
        try {
            $this->Connection = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DATABASE, DB_USER, DB_PASSWORD);

            if ($Show_Errors) { $this->Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); }

            return true;
        } catch (PDOException $e) {
            header($_SERVER['SERVER_PROTOCOL'] . ' Database Unavailable', true, 503);
            die("<center>Database Error</center>");
        }
    }

    public function execute(string $SQL, bool $Single = false, array $Execute = []): array {
        $Query = $this->Connection->prepare($SQL);
        $Query->execute($Execute);

        $this->RowNum = $Query->rowCount();

        if ($this->RowNum == 0) {
            return [];
        } elseif ($Single) {
            return @$Query->fetch(PDO::FETCH_ASSOC);
        } else {
            return @$Query->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function modify(string $SQL, array $Execute = []): bool {
        $Query = $this->Connection->prepare($SQL);
        $Query->execute($Execute);

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
$DB = new Database(false);
session_start();



// Get video ID
$vid_id = preg_replace_callback("/http[s]?:\/\/(?:[(a-z)]+\.)?vidlii\.com\/.*(?=[\&\?]v=([a-zA-Z0-9\_\-]{11})).*$/", function($matches) {
	return $matches[1];
}, $_POST["u"]);

$token = (int)$_POST["t"];

if (isset($_SESSION["watch_tokens"]) && $_SESSION["watch_tokens"][$vid_id] == $token) {

    $DB->modify("UPDATE videos SET watched = watched + 1 WHERE url = '$vid_id'");
    $DB->modify("INSERT INTO videos_watched (vid, watchtime, submit_date) VALUES ('$vid_id', 1, NOW()) ON DUPLICATE KEY UPDATE watchtime = watchtime + 1");
}