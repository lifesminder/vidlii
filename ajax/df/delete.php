<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activation
if (!$_USER->logged_in)			{ die(); }
if (!$_USER->Is_Activated)	{ die(); }

//VIDEOS
if (isset($_POST["v"]) || (isset($_GET["v"]) && strpos($_SERVER["HTTP_REFERER"],"vidlii.com") !== false)) {
    if (isset($_GET["v"])) { $_POST["v"] = $_GET["v"]; }
	$Video = new Video($_POST["v"],$DB);
	$URL = $Video->exists();
	if ($URL !== false) {
		$Video->get_info();
		if ($_USER->username === $Video->Info["uploaded_by"] || $_USER->Is_Admin) {
			$Video->delete();
			if (!isset($_GET["v"]))  { die("1"); }
			else { redirect(previous_page()); exit(); }
		}
	}
}

//PLAYLIST
if (isset($_POST["p"]) || (isset($_GET["p"]))) {
    if (isset($_GET["p"])) { $_POST["p"] = $_GET["p"]; }
	$DB->modify("DELETE FROM playlists WHERE purl = :URL AND created_by = :USERNAME",
               [
                   ":URL"       => $_POST["p"],
                   ":USERNAME"  => $_USER->username
               ]);

	if ($DB->RowNum == 1) {
		$DB->modify("DELETE FROM playlists_videos WHERE purl = :PURL", [":PURL" => $_POST["p"]]);

		$Playlist_Channel = $DB->execute("SELECT playlists FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username])["playlists"];

		$Playlist_Channel = explode(",", $Playlist_Channel);


		$New_Playlists = "";
		foreach($Playlist_Channel as $Playlist) {
			if ($Playlist !== $_POST["p"]) {
				$New_Playlists .= $Playlist . ",";
			}
		}
		$New_Playlists = substr($New_Playlists,0,strlen($New_Playlists) - 1);

		$DB->modify("UPDATE users SET playlists = :PLAYLISTS WHERE username = :USERNAME",
                   [
                       ":PLAYLISTS" => $New_Playlists,
                       ":USERNAME"  => $_USER->username
                   ]);
        redirect($_SERVER["HTTP_REFERER"]); exit();
	}
}