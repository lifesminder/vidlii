<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activated
//- Requires ($_POST["url"]) AND ($_POST["video_title"]) AND ($_POST["video_description"]) AND ($_POST["video_tags"]) AND ($_POST["video_category"])
if (!$_USER->logged_in)               { exit(); }
if (!$_USER->Is_Activated)          { exit(); }
if (!isset($_POST["url"]) || !isset($_POST["video_title"]) || !isset($_POST["video_description"]) ||
    !isset($_POST["video_tags"]) || !isset($_POST["video_category"]) ||
    !isset($_POST["privacy"]) || !isset($_POST["schedule"]))                                        { exit(); }


$_GUMP->validation_rules(array(
    "video_title"           => "required|max_len,100|min_len,1",
    "video_description"     => "max_len,1100",
    "video_tags"            => "max_len,256",
    "video_category"        => "required",
    "url"                   => "required|min_len,1|max_len,20"
));

$_GUMP->filter_rules(array(
    "video_title"           => "trim|NoHTML",
    "video_description"     => "trim|NoHTML",
    "video_tags"            => "trim|NoHTML",
    "video_category"        => "trim|NoHTML",
    "url"                   => "trim"
));

$Validation = $_GUMP->run($_POST);

if ($Validation) {
    $Title = $Validation["video_title"];
    $Description = $Validation["video_description"];
    $Tags        = $Validation["video_tags"];
    $URL         = $Validation["url"];

    if ($Validation["video_category"] > 0 && $Validation["video_category"] <= 15) {
        $Category = $Validation["video_category"];
    } else {
        $Category = 1;
    }


    $Current_Privacy = $DB->execute("SELECT privacy FROM videos WHERE url = :URL", true, [":URL" => $URL])["privacy"];


    if ($_POST["privacy"] == 0)     { $Privacy = 0; }
    elseif ($_POST["privacy"] == 1) { $Privacy = 1; }
    elseif ($_POST["privacy"] == 2) { $Privacy = 2; }
    elseif ($_POST["privacy"] == 3) { $Privacy = 3; }
    else                            { $Privacy = 0; }


    if ($Current_Privacy == 0 && ($Privacy == 1 || $Privacy == 2 || $Privacy == 3)) {
        $DB->modify("UPDATE users SET videos = videos - 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
    } elseif (($Current_Privacy == 1 || $Current_Privacy == 2) && $Privacy == 0) {
        $DB->modify("UPDATE users SET videos = videos + 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
    }

    if ($Privacy != 3) {
        $DB->modify("DELETE FROM videos_schedule WHERE id = :ID", [":ID" => $URL]);
    } else {
        $DB->modify("INSERT INTO videos_schedule (id, date) VALUES (:ID, :DATE) ON DUPLICATE KEY UPDATE date = :DATE", [":ID" => $URL, ":DATE" => str_replace("/","-",$_POST["schedule"])]);
        $Privacy = 2;
    }



    $DB->modify("UPDATE videos SET privacy = $Privacy, title = :TITLE, description = :DESCRIPTION, tags = :TAGS, category = :CATEGORY WHERE url = :URL AND uploaded_by = :USERNAME",
               [
                   ":TITLE"         => $Title,
                   ":DESCRIPTION"   => $Description,
                   ":TAGS"          => $Tags,
                   ":CATEGORY"      => $Category,
                   ":URL"           => $URL,
                   ":USERNAME"      => $_USER->username
               ]);
}