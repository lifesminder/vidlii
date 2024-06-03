<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activated
//- Requires ($_POST["user"])
if (!$_USER->logged_in)         { exit(); }
if (!$_USER->Is_Activated)    { exit(); }
if (!isset($_POST["user"]))     { exit(); }


if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"])) {

    if (mb_strpos($_SERVER["HTTP_REFERER"], "/watch?v=") !== false) {

        $parts = parse_url($_SERVER["HTTP_REFERER"]);
        parse_str($parts['query'], $query);
        $URL =  $query['v'];

        $_VIDEO = new Video($URL, $DB);

        if ($URL = $_VIDEO->exists()) {

            $Source = $URL;

        }

    } elseif (mb_strpos($_SERVER["HTTP_REFERER"], "/user/") !== false) {

        $Source = "c";

    } else {

        $Source = "";

    }

} else {

    $Source = "";

}


$Subscription = $DB->execute("SELECT username FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_POST["user"]]);

if ($DB->RowNum > 0 && strtolower($_USER->username) !== strtolower($Subscription["username"])) {

    $DB->execute("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OTHER) OR (blocker = :OTHER AND blocked = :USERNAME)", false,
                [
                    ":USERNAME" => $_USER->username,
                    ":OTHER"    => $Subscription["username"]
                ]);

    if ($DB->RowNum == 0) {

        if ($_USER->subscribe_to($Subscription["username"], $Source)) {

            echo json_encode(array("response" => "subscribed"));

        } else {

            echo json_encode(array("response" => "unsubscribed"));

        }

    }
}