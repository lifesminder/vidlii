<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["user"])
if (!$_USER->logged_in)           { exit(); }
if (!isset($_POST["user"]))       { exit(); }


$Username = $DB->execute("SELECT username FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_POST["user"]]);

if ($DB->RowNum == 1) {
    $Username = $Username["username"];

    $Featured_Channels = $DB->execute("SELECT featured_channels FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username]);

    $Featured_Channels = explode(",", $Featured_Channels["featured_channels"]);

    $New_Featured_Channels = "";
    foreach($Featured_Channels as $Channel) {
        if ($Channel !== $Username) {
            $New_Featured_Channels .= $Channel . ",";
        }
    }
    $New_Featured_Channels = substr($New_Featured_Channels,0,strlen($New_Featured_Channels) - 1);

    $DB->modify("UPDATE users SET featured_channels = :FEATURED WHERE username = :USERNAME",
               [
                   ":FEATURED" => $New_Featured_Channels,
                   ":USERNAME" => $_USER->username
               ]);
}