<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["response"])
if (!$_USER->logged_in)                { exit(); }
if (!isset($_POST["response"]))        { exit(); }


$ID     = (int)$_POST["response"];
$Check  = $DB->execute("SELECT url,id FROM video_responses WHERE id = :ID AND response_user = :USERNAME", true,
                      [
                          ":ID"         => $ID,
                          ":USERNAME"   => $_USER->username
                      ]);

if ($DB->RowNum == 1) {
    $URL    = $Check["url"];
    $ID     = $Check["id"];

    $DB->modify("DELETE FROM video_responses WHERE id = $ID");
}