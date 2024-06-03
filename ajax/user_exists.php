<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires ($_POST["user"])
if (!isset($_POST["user"])) { exit(); }

$User = $DB->execute("SELECT username FROM users WHERE displayname = :USER LIMIT 1", true, [":USER" => $_POST["user"]]);
if ($DB->RowNum == 1) {
    echo $User["username"];
} else {
    echo "false";
}