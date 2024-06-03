<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["pid"]) AND ($_POST["id"])
if (!$_USER->logged_in)                            { exit(); }
if (!isset($_POST["pid"]) || !isset($_POST["id"])) { exit(); }


$URL = $_POST["id"];
$PURL = $_POST["pid"];

//CHECK IF OWNS PLAYLIST
$DB->execute("SELECT purl FROM playlists WHERE purl = :PURL AND created_by = :USERNAME", false,
            [
                ":PURL"     => $PURL,
                ":USERNAME" => $_USER->username
            ]);

if ($DB->RowNum == 1) {
    $Video  = new Video($URL, $DB);
    $URL    = $Video->exists();

    if ($URL !== false) {
        $Check = $DB->execute("SELECT position FROM playlists_videos WHERE purl = :PURL ORDER BY position DESC LIMIT 1", true, [":PURL" => $PURL]);

        if ($DB->RowNum == 1) {
            $New_Position = $Check["position"] + 1;
        } else {
            $New_Position = 1;
        }

        $DB->modify("INSERT INTO playlists_videos (url,purl,position) VALUES (:URL,:PURL,:POSITION)",
                   [
                       ":URL"       => $URL,
                       ":PURL"      => $PURL,
                       ":POSITION"  => $New_Position
                   ]);
        if ($DB->RowNum == 1) {
            $DB->modify("UPDATE playlists SET thumbnail = :URL WHERE purl = :PURL AND created_by = :USERNAME",
                       [
                           ":URL"       => $URL,
                           ":PURL"      => $PURL,
                           ":USERNAME"  => $_USER->username
                       ]);

            die(json_encode(array("response" => "success")));
        } else {
            die(json_encode(array("response" => "already")));
        }
    }
}