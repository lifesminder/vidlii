<?php
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

require_once "_includes/init.php";
//$DB->modify("INSERT INTO badboys (ip, submit_date) VALUES (:IP, NOW())", [":IP" => $ip]);


if (isset($_POST["submit_suggestion"]) && $_USER->logged_in) {
    $_GUMP->validation_rules(array(
        "s_title"         => "required|max_len,100",
        "s_description"   => "required|max_len,1100"
    ));

    $_GUMP->filter_rules(array(
        "s_title"         => "trim|NoHTML",
        "s_description"   => "trim|NoHTML"
    )); 

    $Validation = $_GUMP->run($_POST);

    if ($Validation) {
        $Title       = $Validation["s_title"];
        $Description = $Validation["s_description"];

        $Feature_Exist = $DB->execute("SELECT id FROM feature_suggestions WHERE from_user = :USERNAME", false, [":USERNAME" => $_USER->username]);

        if ($DB->RowNum == 0) {
            $DB->modify("INSERT INTO feature_suggestions (title,description,from_user) VALUES (:TITLE,:DESCRIPTION,:USERNAME)",
                       [
                           ":TITLE"         => $Title,
                           ":DESCRIPTION"   => $Description,
                           ":USERNAME"      => $_USER->username
                       ]);
            notification("Your suggestion has been added! Thank you.","/community","green"); exit();
        } else {
            notification("You can only have one suggestion at once!","/community"); exit();
        }
    }
}
$Random_Video           = new Videos($DB, $_USER);
$Random_Video->Blocked  = false;
$Random_Video->LIMIT    = 1;

if (!isset($_GET["v"])) {

    $Random_Video->ORDER_BY = "rand()";

} elseif ($_GET["v"] == "l") {

    $Random_Video->ORDER_BY = "videos.uploaded_on DESC";

} elseif ($_GET["v"] == "b") {

    $Random_Video->JOIN     = "INNER JOIN recently_viewed ON videos.url = recently_viewed.url";
    $Random_Video->ORDER_BY = "recently_viewed.time_viewed DESC";

} else {
    redirect("/community");
}

$Random_Video->get();
$Random_Video = $Random_Video->fixed();

$Status   = $Random_Video["status"];
$URL      = $Random_Video["url"];
$FILENAME = $Random_Video["file"];
$ISHD     = $Random_Video["hd"] == 1 ? true : false;
$Length   = $Random_Video["seconds"];
$Autoplay = false;

if (isset($_COOKIE["player"])) {

	$Player = (int)$_COOKIE["player"];
	if ($Player < 0 || $Player > 3) $Player = 2;

} else {

    $Player = 2;

}


//RECENTLY FAVORITED VIDEOS
$Favorites              = new Videos($DB, $_USER);
$Favorites->ORDER_BY    = "video_favorites.date DESC";
$Favorites->SELECT     .= ", video_favorites.date";
$Favorites->JOIN        = "INNER JOIN video_favorites ON videos.url = video_favorites.url";
$Favorites->LIMIT       = 4;
$Favorites->Blocked     = false;
$Favorites->get();

$Favorites = $Favorites->fixed();

//RECENT COMMENTS
$Comments = $DB->execute("SELECT video_comments.comment, videos.url, video_comments.by_user, videos.title, users.avatar, users.displayname FROM video_comments INNER JOIN users ON video_comments.by_user = users.username INNER JOIN videos ON video_comments.url = videos.url WHERE videos.privacy = 0 AND videos.status = 2 AND videos.banned_uploader = 0 AND videos.shadowbanned_uploader = 0 ORDER BY video_comments.date_sent DESC LIMIT 5");

//FEATURE REQUESTS
$Requests = $DB->execute("SELECT users.avatar, users.displayname, feature_suggestions.title, feature_suggestions.description, feature_suggestions.from_user FROM feature_suggestions INNER JOIN users ON feature_suggestions.from_user = users.username WHERE users.banned = 0 ORDER BY rand() LIMIT 5");

if ($_USER->logged_in) {
    $Your_Request = $DB->execute("SELECT users.avatar, users.displayname, feature_suggestions.title, feature_suggestions.description, feature_suggestions.from_user FROM feature_suggestions INNER JOIN users ON feature_suggestions.from_user = users.username WHERE feature_suggestions.from_user = :USERNAME", true, [":USERNAME" => $_USER->username]);
    if ($DB->RowNum == 1) { $Has_Requested = true; } else { $Has_Requested = false; }
} else {
    $Has_Requested = false;
}

//CURRENTLY WINNING CONTEST
$Contest = $DB->execute("SELECT contest_entries.url, videos.title FROM contest_entries INNER JOIN videos ON contest_entries.url = videos.url ORDER BY votes DESC LIMIT 1", true);


$_PAGE->set_variables(array(
    "Page_Title"        => "Community - VidLii",
    "Page"              => "Community",
    "Page_Type"         => "Community"
));
require_once "_templates/page_structure.php";
