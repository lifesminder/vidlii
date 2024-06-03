<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["bulletin"]) AND ($_POST["url"])
//- ($_POST["flag"]) must be between 1 and 5
if (!$_USER->logged_in)                               { exit(); }
if (!isset($_POST["flag"]) || !isset($_POST["url"]))  { exit(); }
if ($_POST["flag"] < 1 || $_POST["flag"] > 6)         { exit(); }


$Flag_Type = (int)$_POST["flag"];
$URL       = $_POST["url"];

$Check = $DB->execute("SELECT url FROM videos WHERE url = :URL", true, [":URL" => $URL]);

if ($DB->RowNum == 1) {
    $URL = $Check["url"];

    $DB->modify("INSERT IGNORE INTO videos_flags(url,by_user,reason,submit_on) VALUES(:URL,:USERNAME,:REASON,NOW())",
               [
                   ":URL"       => $URL,
                   ":USERNAME"  => $_USER->username,
                   ":REASON"    => $Flag_Type
               ]);

    if ($DB->RowNum == 0) {
        echo "error";
    }
}