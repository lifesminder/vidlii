<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


$_PAGINATION = new Pagination(10,50);

$Videos = $DB->execute("SELECT * FROM video_favorites INNER JOIN videos ON video_favorites.url = videos.url WHERE video_favorites.favorite_by = :USERNAME ORDER BY video_favorites.date DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

$Videos                     = new Videos($DB, $_USER);
$Videos->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
$Videos->SELECT            .= ", video_favorites.url as url";
$Videos->Shadowbanned_Users = true;
$Videos->Banned_Users       = true;
$Videos->Private_Videos     = true;
$Videos->Unlisted_Videos    = true;
$Videos->ORDER_BY           = "video_favorites.date DESC";
$Videos->WHERE_P            = ["video_favorites.favorite_by" => $_USER->username];
$Videos->LIMIT              = $_PAGINATION;
$Videos->get();

if ($Videos::$Videos) {

    $Videos = $Videos->fixed();

    $Videos_Amount                     = new Videos($DB, $_USER);
    $Videos_Amount->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
    $Videos_Amount->Shadowbanned_Users = true;
    $Videos_Amount->Banned_Users       = true;
    $Videos_Amount->Private_Videos     = true;
    $Videos_Amount->Unlisted_Videos    = true;
    $Videos_Amount->WHERE_P            = ["video_favorites.favorite_by" => $_USER->username];
    $Videos_Amount->Count              = true;
    $Videos_Amount->Count_Column       = "video_favorites.url";
    $_PAGINATION->Total                = $Videos_Amount->get();

} else {

    $Videos = false;

}


$Header = "My Favorites";


$_PAGE->set_variables(array(
    "Page_Title"        => "My Favorites - VidLii",
    "Page"              => "Favorites",
    "Page_Type"         => "Videos",
    "Show_Search"       => false
));
require_once "_templates/videos_structure.php";