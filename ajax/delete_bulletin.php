<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["bulletin"])
if (!$_USER->logged_in)             { exit(); }
if (!isset($_POST["bulletin"]))     { exit(); }


$Bulletin_ID = (int)$_POST["bulletin"];

$DB->modify("DELETE FROM bulletins WHERE id = :ID AND by_user = :USERNAME",
           [
               ":ID"        => $Bulletin_ID,
               ":USERNAME"  => $_USER->username
           ]);