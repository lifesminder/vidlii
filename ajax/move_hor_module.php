<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["user"])
if (!$_USER->logged_in)            { exit(); }
if (!isset($_POST["Module"]))      { exit(); }


$Module = $_POST["Module"];

if ($Module == "ft_r") {
    $DB->modify("UPDATE users SET featured_d = 1 WHERE username = '$_USER->username'");
} elseif ($Module == "ft_l") {
    $DB->modify("UPDATE users SET featured_d = 0 WHERE username = '$_USER->username'");
} elseif ($Module == "su2_l") {
    $DB->modify("UPDATE users SET subscriber_d = 1 WHERE username = '$_USER->username'");
} elseif ($Module == "su2_r") {
    $DB->modify("UPDATE users SET subscriber_d = 0 WHERE username = '$_USER->username'");
} elseif ($Module == "su1_l") {
    $DB->modify("UPDATE users SET subscription_d = 1 WHERE username = '$_USER->username'");
} elseif ($Module == "su1_r") {
    $DB->modify("UPDATE users SET subscription_d = 0 WHERE username = '$_USER->username'");
} elseif ($Module == "fr_l") {
    $DB->modify("UPDATE users SET friends_d = 1 WHERE username = '$_USER->username'");
} elseif ($Module == "fr_r") {
    $DB->modify("UPDATE users SET friends_d = 0 WHERE username = '$_USER->username'");
} elseif ($Module == "recent_activity2") {
    $DB->modify("UPDATE users SET recent_d = 1 WHERE username = '$_USER->username'");
} elseif ($Module == "recent_activity3") {
    $DB->modify("UPDATE users SET recent_d = 0 WHERE username = '$_USER->username'");
} elseif ($Module == "cmt_l") {
    $DB->modify("UPDATE users SET channel_d = 1 WHERE username = '$_USER->username'");
} elseif ($Module == "cmt_r") {
    $DB->modify("UPDATE users SET channel_d = 0 WHERE username = '$_USER->username'");
} elseif ($Module == "cu_l") {
    $DB->modify("UPDATE users SET custom_d = 1 WHERE username = '$_USER->username'");
} elseif ($Module == "cu_r") {
    $DB->modify("UPDATE users SET custom_d = 0 WHERE username = '$_USER->username'");
}