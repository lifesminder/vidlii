<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["title"])
if (!$_USER->logged_in)          { exit(); }
if (!isset($_POST["title"]))     { exit(); }


$_GUMP->validation_rules(array(
    "title"          => "max_len,20"
));

$_GUMP->filter_rules(array(
    "title"         => "trim|NoHTML"
));

$Validation = $_GUMP->run($_POST);

if ($Validation) {
    $Title = $Validation["title"];

    $DB->modify("UPDATE users SET featured_title = :TITLE WHERE username = :USERNAME",
               [
                   ":TITLE"     => $Title,
                   ":USERNAME"  => $_USER->username
               ]);
}