<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["response"])
if (!$_USER->logged_in) { echo json_encode(array("response" => "not_logged_in")); exit(); }


$Get_Playlists = $DB->execute("SELECT purl, title FROM playlists WHERE created_by = :USERNAME ORDER BY created_on DESC LIMIT 20", false, [":USERNAME" => $_USER->username]);

if (count($Get_Playlists) > 0) {
    $Playlist_Select = "<select id='playlist_select' style='width:200px' size='6'>";
    foreach ($Get_Playlists as $Playlist) {
        $Playlist_Select .= "<option value='" . $Playlist["purl"] . "'>" . $Playlist["title"] . "</option>";
    }
    $Playlist_Select .= "</select>";
    echo json_encode(array("response" => "logged_in", "select" => $Playlist_Select."<br><button onclick='add_to_playlist()' style='margin-top:4px;'>Add To Playlist</button>"));
} else {
    echo json_encode(array("response" => "logged_in", "select" => "You have no playlists! <a href='/my_playlists'>Create one</a>."));
}