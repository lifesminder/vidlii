<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in) { exit(); }


$Query = $DB->execute("SELECT url FROM videos WHERE uploaded_by = :USERNAME ORDER BY uploaded_on DESC LIMIT 1", true, [":USERNAME" => $_USER->username]);
if ($DB->RowNum > 0) {
    echo $Query["url"];
}