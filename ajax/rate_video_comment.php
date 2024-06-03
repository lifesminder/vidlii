<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["id"]) AND ($_POST["rate"])
//- ($_POST["rate"]) must equal 1 or 2
if (!$_USER->logged_in)                             { exit(); }
if (!isset($_POST["id"]) || !isset($_POST["rate"])) { exit(); }
if ($_POST["rate"] != 1 && $_POST["rate"] != 0)     { exit(); }
if (!isset($_SESSION["deto"]))                      { exit(); }


$ID     = (int)$_POST["id"];
$Rate   = (int)$_POST["rate"];

$Raters = $DB->execute("SELECT raters,rating FROM video_comments WHERE id = $ID", true);
$Rating = $Raters["rating"];
$Raters = $Raters["raters"];

if (strpos($Raters,$_USER->username."+") !== false) {
    $Positive = true;
    if ($Rate == 1) { die(); }
    $Raters = str_replace($_USER->username."+",$_USER->username."-",$Raters);
    $Rating -= 2;
} elseif (strpos($Raters,$_USER->username."-") !== false) {
    $Negative = true;
    if ($Rate == 0) { die(); }
    $Raters = str_replace($_USER->username."-",$_USER->username."+",$Raters);
    $Rating += 2;
} else {
    $Nothing = true;
    if ($Rate == 1) {
        $Raters .= "$_USER->username+,";
        $Rating++;
    } else {
        $Raters .= "$_USER->username-,";
        $Rating--;
    }
}
$DB->modify("UPDATE video_comments SET rating = $Rating, raters = '$Raters' WHERE id = $ID");