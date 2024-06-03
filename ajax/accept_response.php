<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["response"])
if (!$_USER->logged_in)         { exit(); }
if (!isset($_POST["response"])) { exit(); }


$ID = (int)$_POST["response"];

$Check = $DB->execute("SELECT url,id FROM video_responses WHERE id = :ID AND response_user = :USERNAME", true,
                     [
                         ":ID"          => $ID,
                         ":USERNAME"    => $_USER->username
                     ]);

if ($DB->RowNum == 1) {
    $URL    = $Check["url"];
    $ID     = $Check["id"];

    $DB->modify("UPDATE videos SET responses = responses + 1 WHERE url = :URL", [":URL" => $URL]);
    $DB->modify("UPDATE video_responses SET accepted = 1 WHERE id = :ID", [":ID" => $ID]);
}