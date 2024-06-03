<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["vc_id"])
if (!$_USER->logged_in)            { exit(); }
if (!isset($_POST["vc_id"]))       { exit(); }


$ID    = (int)$_POST["vc_id"];
$Check = $DB->execute("SELECT id, by_user, comment, url, seen, reply_to FROM video_comments WHERE id = $ID", true);

if ($DB->RowNum == 0) { die(); }

$Uploaded_By    = $DB->execute("SELECT uploaded_by FROM videos WHERE url = :URL", true, [":URL" => $Check["url"]])["uploaded_by"];

if ($_USER->Is_Admin || $_USER->Is_Mod || $Uploaded_By === $_USER->username || $Check["by_user"] === $_USER->username) {
    if ($Check["has_replies"] == 1) {
        $DB->modify("DELETE FROM video_comments WHERE reply_to = :ID", [":ID" => $ID]);
    }

    $DB->modify("DELETE FROM video_comments WHERE id = $ID");

    if ($DB->RowNum == 1 && $Check["reply_to"] == 0) {
        $DB->modify("UPDATE videos SET comments = GREATEST(comments - 1, 0) WHERE url = :URL", [":URL" => $Check["url"]]);
    }
}