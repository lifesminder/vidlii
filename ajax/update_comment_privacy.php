<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["setting"])
//- ($_POST["setting"] must be between 0 and 2
if (!$_USER->logged_in)                             { exit(); }
if (!isset($_POST["setting"]))                      { exit(); }
if ($_POST["setting"] < 0 || $_POST["setting"] > 2) { exit(); }


$Setting = (int)$_POST["setting"];

$DB->modify("UPDATE users SET channel_comment_privacy = :SETTING WHERE username = :USERNAME",
           [
               ":SETTING"   => $Setting,
               ":USERNAME"  => $_USER->username
           ]);
