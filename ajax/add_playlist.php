<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["pid"])
if (!$_USER->logged_in)         { exit(); }
if (!isset($_POST["pid"]))      { exit(); }


$Check = $DB->execute("SELECT purl FROM playlists WHERE purl = :PURL AND created_by = :USERNAME", true,
                     [
                         ":PURL"        => $_POST["pid"],
                         ":USERNAME"    => $_USER->username
                     ]);

if ($DB->RowNum == 1) {
    $PURL = $Check["purl"];

    $Playlists = $DB->execute("SELECT playlists FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username])["playlists"];

    if (strpos(strtolower($Playlists),strtolower($PURL)) !== false) {
        echo json_encode(array("response" => "already"));
        exit();
    }

    if (!empty($Playlists)) {
        $Playlists = explode(",", $Playlists);
    } else {
        $Playlists = array();
    }

    if (count($Playlists) < 3) {
        $Playlists[] = $PURL;

        $New_Playlists = "";
        foreach($Playlists as $Playlist) {
            $New_Playlists .= $Playlist.",";
        }
        $New_Playlists = substr($New_Playlists,0,strlen($New_Playlists) - 1);

        $DB->modify("UPDATE users SET playlists = :PLAYLISTS WHERE username = :USERNAME",
                   [
                       ":PLAYLISTS" => $New_Playlists,
                       ":USERNAME"  => $_USER->username
                   ]);

        $Get = $DB->execute("SELECT * FROM playlists WHERE purl = :PURL", true, [":PURL" => $PURL]);

        $Get["response"] = "success";

        echo json_encode($Get);
    } else {
        echo json_encode(array("response" => "too_many"));
    }
}