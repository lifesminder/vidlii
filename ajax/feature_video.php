<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Admin or Mod
//- Requires ($_POST["url"])
if (!$_USER->logged_in)                       { exit(); }
if (!$_USER->Is_Admin && !$_USER->Is_Mod) { exit(); }
if (!isset($_POST["url"]))                    { exit(); }


$Check = $DB->execute("SELECT url, uploaded_by, featured FROM videos WHERE url = :URL", true, [":URL" => $_POST["url"]]);
if ($DB->RowNum == 1) {
    $URL   = $Check["url"];

    if ($Check["uploaded_by"] !== $_USER->username) {
        if ($Check["featured"] == 0) {
            $Update = $DB->modify("UPDATE videos SET featured = 1 WHERE url = '$URL'");
            echo 1;
        } else {
            $Update = $DB->modify("UPDATE videos SET featured = 0 WHERE url = '$URL'");
            echo 0;
        }
    }
}