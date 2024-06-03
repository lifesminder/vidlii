<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in) { redirect("/login"); exit(); }


$DB->modify("DELETE FROM feature_suggestions WHERE from_user = :USERNAME", [":USERNAME" => $_USER->username]);
if ($DB->RowNum == 1) {
    notification("Your suggestion has been successfully removed!","/community","green");
} else {
    notification("You have no suggestion!","/community","red");
}