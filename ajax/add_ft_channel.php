<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["user"])
if (!$_USER->logged_in)         { exit(); }
if (!isset($_POST["user"]))     { exit(); }


$Username = $DB->execute("SELECT username FROM users WHERE displayname = :USERNAME", true, [":USERNAME" => $_POST["user"]]);

if ($DB->RowNum == 1) {
    $Username = $Username["username"];

    $Featured_Channels = $DB->execute("SELECT featured_channels FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username]);

    if (!empty($Featured_Channels["featured_channels"])) {
        $Featured_Channels = explode(",", $Featured_Channels["featured_channels"]);
    } else {
        $Featured_Channels = [];
    }

    foreach($Featured_Channels as $Channel) {
        if (strtolower($Channel) === strtolower($Username)) {
            echo "u_e";
            exit();
        }
    }

    if (count($Featured_Channels) < 8) {
        $Featured_Channels[] = $Username;

        $New_Featured_Channels = "";
        foreach($Featured_Channels as $Channel) {
            $New_Featured_Channels .= $Channel.",";
        }
        $New_Featured_Channels = substr($New_Featured_Channels,0,strlen($New_Featured_Channels) - 1);

        $DB->modify("UPDATE users SET featured_channels = :FEATURED WHERE username = :USERNAME",
                   [
                       ":FEATURED" => $New_Featured_Channels,
                       ":USERNAME" => $_USER->username
                   ]);

        $Get = $DB->execute("SELECT username, displayname, channel_description, subscribers, video_views, videos, avatar FROM users WHERE username = :USERNAME", true, [":USERNAME" => $Username]);


        if (strpos($Get["avatar"], "u=") !== false) {
            $Avatar = str_replace("u=", "", $Get["avatar"]);
            $Folder = "avt";
        } elseif (!empty($Get["avatar"])) {
            $Upload = false;
            $Folder = "thmp";
            $Avatar = $Get["avatar"];
        } else {
            $Avatar = "";
            $Folder = "a";
        }

        $Get["video_views"] = number_format($Get["video_views"]);

        if (empty($Avatar) || !file_exists($_SERVER['DOCUMENT_ROOT'] . "/usfi/$Folder/$Avatar.jpg")) {
            $Get["avatar"] = "/img/no.png";
        } else {
            if ($Folder == "avt") {
                $Get["avatar"] = "/usfi/avt/$Avatar.jpg";
            } else {
                $Get["avatar"] = "/usfi/thmp/$Avatar.jpg";
            }
        }

        if (!empty($Get["channel_description"])) {
            $Get["channel_description"] = limit_text($Get["channel_description"], 70);
        } else {
            $Get["channel_description"] = "<i>No Description...</i>";
        }

        echo json_encode($Get);
    } else {
        echo "u_m";
        exit();
    }
} else {
    echo "u_d";
    exit();
}