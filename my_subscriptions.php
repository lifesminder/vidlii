<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


$_PAGINATION = new Pagination(16,10);

$Subscriptions = $DB->execute("SELECT subscriptions.subscription, users.displayname FROM subscriptions INNER JOIN users ON subscriptions.subscription = users.username WHERE subscriptions.subscriber = :USERNAME AND users.username = subscriptions.subscription ORDER BY subscriptions.subscription ASC", false, [":USERNAME" => $_USER->username]);

foreach ($Subscriptions as $s) {
	if (isset($_GET["c"]) && $s["displayname"] == $_GET["c"]) {
		$c_un = $s["subscription"];
		break;
	}
}

if (!isset($c_un) && count($Subscriptions) > 0) {

    $Videos                     = new Videos($DB, $_USER);
    $Videos->JOIN               = "INNER JOIN subscriptions ON subscriptions.subscription = videos.uploaded_by";
    $Videos->WHERE_P            = ["subscriptions.subscriber" => $_USER->username];
    $Videos->ORDER_BY           = "videos.uploaded_on DESC";
    $Videos->Shadowbanned_Users = true;
    $Videos->LIMIT              = $_PAGINATION;
    $Videos->get();

    if ($Videos::$Videos) {

        $Videos = $Videos->fixed();

        $Videos_Amount                      = new Videos($DB, $_USER);
        $Videos_Amount->JOIN                = "INNER JOIN subscriptions ON subscriptions.subscription = videos.uploaded_by";
        $Videos_Amount->WHERE_P             = ["subscriptions.subscriber" => $_USER->username];
        $Videos_Amount->Shadowbanned_Users  = true;
        $Videos_Amount->LIMIT               = $_PAGINATION;
        $_PAGINATION->Total                 = $Videos_Amount->get();

    } else {

        $Videos = false;

    }

    $_PAGINATION->Total = $DB->execute("SELECT COUNT(videos.title) AS amount FROM videos INNER JOIN subscriptions ON subscriptions.subscription = videos.uploaded_by WHERE subscriptions.subscriber = :USERNAME AND videos.status = 2 LIMIT 160", true, [":USERNAME" => $_USER->username])["amount"];

} elseif (isset($c_un)) {

    $Videos                     = new Videos($DB, $_USER);
    $Videos->JOIN               = "INNER JOIN subscriptions ON subscriptions.subscription = videos.uploaded_by";
    $Videos->WHERE_P            = ["subscriptions.subscriber" => $_USER->username, "subscriptions.subscription" => $c_un];
    $Videos->ORDER_BY           = "videos.uploaded_on DESC";
    $Videos->Shadowbanned_Users = true;
    $Videos->LIMIT              = $_PAGINATION;
    $Videos->get();

    if ($Videos::$Videos) {

        $Videos = $Videos->fixed();

        $Videos_Amount                      = new Videos($DB, $_USER);
        $Videos_Amount->JOIN                = "INNER JOIN subscriptions ON subscriptions.subscription = videos.uploaded_by";
        $Videos_Amount->WHERE_P             = ["subscriptions.subscriber" => $_USER->username, "subscriptions.subscription" => $c_un];
        $Videos_Amount->Shadowbanned_Users  = true;
        $Videos_Amount->LIMIT               = $_PAGINATION;
        $_PAGINATION->Total                 = $Videos_Amount->get();

    } else {

        $Videos = false;

    }

    $_PAGINATION->Total               = $DB->execute("SELECT COUNT(videos.title) AS amount FROM videos WHERE videos.uploaded_by = :FROMUSER AND videos.status = 2 LIMIT 160", true, [":FROMUSER" => $c_un])["amount"];

} else {

    redirect("/my_videos");

}





$Header = "My Subscriptions";

$_PAGE->set_variables(array(
    "Page_Title"        => "My Subscriptions - VidLii",
    "Page"              => "Subscriptions",
    "Page_Type"         => "Videos",
    "Show_Search"       => false
));
require_once "_templates/videos_structure.php";
