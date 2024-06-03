<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires ($_GET["p"])
if (!isset($_GET["p"]))         { redirect("/"); exit(); }


$Playlist = $DB->execute("SELECT * FROM playlists WHERE purl = :PURL", true, [":PURL" => $_GET["p"]]);

$Playlist_Stats = $DB->execute("SELECT sum(videos.displayviews) as total_views, sum(videos.comments) as total_comments, sum(videos.responses) as total_responses, sum(videos.favorites) as total_favorites FROM playlists INNER JOIN playlists_videos ON playlists_videos.purl = playlists.purl INNER JOIN videos ON playlists_videos.url = videos.url WHERE playlists.purl = :PURL", true, [":PURL" => $_GET["p"]]);


$Playlist_Videos                     = new Videos($DB, $_USER);
$Playlist_Videos->WHERE_P            = ["playlists_videos.purl" => $_GET["p"]];
$Playlist_Videos->JOIN               = "RIGHT JOIN playlists_videos ON playlists_videos.url = videos.url";
$Playlist_Videos->Shadowbanned_Users = true;
$Playlist_Videos->Banned_Users       = true;
$Playlist_Videos->Private_Videos     = true;
$Playlist_Videos->Unlisted_Videos    = true;
$Playlist_Videos->ORDER_BY           = "playlists_videos.position";
$Playlist_Videos->LIMIT              = 512;
$Playlist_Videos->get();


if ($Playlist_Videos::$Videos) {

    $Playlist_Videos = $Playlist_Videos->fixed();

} else {

    notification("This playlist doesn't have any videos!","videos","red"); exit();

}


$_PAGE->set_variables(array(
    "Page_Title"        => "Playlist:".$Playlist["title"]." - VidLii",
    "Page"              => "Playlist",
    "Page_Type"         => "Videos",
    "Show_Search"       => false
));
require_once "_templates/page_structure.php";