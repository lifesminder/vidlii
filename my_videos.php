<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


$_PAGINATION = new Pagination(10,50);


$Videos                     = new Videos($DB, $_USER);
$Videos->Shadowbanned_Users = true;
$Videos->Banned_Users       = true;
$Videos->Private_Videos     = true;
$Videos->Unlisted_Videos    = true;
$Videos->STATUS             = 3;
$Videos->ORDER_BY           = "videos.uploaded_on DESC";
$Videos->WHERE_P            = ["uploaded_by" => $_USER->username];
$Videos->LIMIT              = $_PAGINATION;
$Videos->get();

if ($Videos::$Videos) {

    $Videos = $Videos->fixed();

    $Videos_Amount                     = new Videos($DB, $_USER);
    $Videos_Amount->Shadowbanned_Users = true;
    $Videos_Amount->Banned_Users       = true;
    $Videos_Amount->Private_Videos     = true;
    $Videos_Amount->STATUS             = 3;
    $Videos_Amount->Unlisted_Videos    = true;
    $Videos_Amount->WHERE_P            = ["uploaded_by" => $_USER->username];
    $Videos_Amount->Count              = true;
    $_PAGINATION->Total                = $Videos_Amount->get();

    foreach ($Videos as $i => $v) {

        if ($v["status"] == 0) {

            $queue = $DB->execute("SELECT queue FROM converting WHERE url = :URL", true, [":URL" => $v["url"]])["queue"];
            $Videos[$i]["queue"] = $queue + 1;

        }

    }

} else {

    $Videos = false;

}


$Header = "My Videos";


$_PAGE->set_variables(array(
    "Page_Title"        => "My Videos - VidLii",
    "Page"              => "Main",
    "Page_Type"         => "Videos",
    "Show_Search"       => false
));
require_once "_templates/videos_structure.php";