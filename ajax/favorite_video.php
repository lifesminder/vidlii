<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["id"])
if (!$_USER->logged_in)            { echo json_encode(array("response" => "not_logged_in")); exit(); }
if (!isset($_POST["id"]))          { exit(); }


$Video = new Video($_POST["id"],$DB);
$URL = $Video->exists();

if ($URL !== false) {
    if (!$Video->favorited_by($_USER->username)) {
        $_USER->favorite_video($URL);
        echo json_encode(array("response" => "added"));
    } else {
        $_USER->remove_favorite($URL);
        echo json_encode(array("response" => "removed"));
    }
}