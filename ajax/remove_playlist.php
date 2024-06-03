<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["pid"])
if (!$_USER->logged_in)           { exit(); }
if (!isset($_POST["pid"]))        { exit(); }


$PURL = $_POST["pid"];

$Playlists = $DB->execute("SELECT playlists FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username])["playlists"];

$Playlists = explode(",", $Playlists);

$New_Playlists = "";
foreach($Playlists as $Playlist) {
    if ($Playlist !== $PURL) {
        $New_Playlists .= $Playlist . ",";
    }
}
$New_Playlists = substr($New_Playlists,0,strlen($New_Playlists) - 1);

$DB->modify("UPDATE users SET playlists = :PLAYLISTS WHERE username = :USERNAME",
           [
               ":PLAYLISTS" => $New_Playlists,
               ":USERNAME"  => $_USER->username
           ]);