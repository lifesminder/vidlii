<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


$_USER->get_profile();

$Channel_Version = $_USER->Info["channel_version"];

$Types = array(
    0 => "Default",
    1 => "Director",
    2 => "Musician",
    3 => "Comedian",
    4 => "Gamer",
    5 => "Reporter",
    6 => "Guru",
    7 => "Animator"
);


$Account_Title = "Overview";

$Rated = $DB->execute("SELECT count(*) as amount FROM video_ratings WHERE user_rated = '$_USER->username'", true)["amount"];
$Comments = $DB->execute("SELECT count(*) as amount FROM video_comments WHERE by_user = '$_USER->username'", true)["amount"];


$_PAGE->set_variables(array(
    "Page_Title"        => "My Account - VidLii",
    "Page"              => "my_account",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/settings_structure.php";