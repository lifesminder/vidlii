<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Admin or Mod
//- Requires ($_POST["user"])
if (!$_USER->logged_in)                       { exit(); }
if (!$_USER->Is_Admin && !$_USER->Is_Mod) { exit(); }
if (!isset($_POST["user"]))                   { exit(); }


$Shadowbanned = $DB->execute("SELECT shadowbanned FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_POST["user"]])["shadowbanned"];

if ($Shadowbanned) {

    $DB->modify("UPDATE users SET shadowbanned = 0 WHERE username = :USERNAME", [":USERNAME" => $_POST["user"]]);
    $DB->modify("UPDATE videos SET shadowbanned_uploader = 0 WHERE uploaded_by = :USERNAME", [":USERNAME" => $_POST["user"]]);
    echo "d";

} else {

    $DB->modify("UPDATE users SET shadowbanned = 1 WHERE username = :USERNAME", [":USERNAME" => $_POST["user"]]);
    $DB->modify("UPDATE videos SET shadowbanned_uploader = 1 WHERE uploaded_by = :USERNAME", [":USERNAME" => $_POST["user"]]);
    echo "i";

}