<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["v"]) AND ($_POST["r"])
if (!$_USER->logged_in)                          { exit(); }
if (!isset($_POST["v"]) || !isset($_POST["r"]))  { exit(); }
if (!isset($_SESSION["deto"]))                   { exit(); }


$Video = new Video($_POST["v"],$DB);
$Video->get_info();

if ($Video->Info["s_ratings"] == 0) { die(); }

$_USER->rate_video($Video,$_POST["r"]);