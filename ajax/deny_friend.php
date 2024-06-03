<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["user"])
if (!$_USER->logged_in)            { exit(); }
if (!isset($_POST["user"]))        { exit(); }


$User = $DB->execute("SELECT username FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_POST["user"]]);
if ($DB->RowNum == 1 && $User["username"] !== $_USER->username) {
    $User = $User["username"];

    $Query = $DB->execute("SELECT id, seen FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME) AND status = 0 AND by_user <> :USERNAME", true,
                         [
                             ":USERNAME" => $_USER->username,
                             ":FRIEND"   => $User
                         ]);

    if ($DB->RowNum > 0) {
        $DB->modify("DELETE FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME) AND status = 0 AND by_user <> :USERNAME",
                   [
                       ":USERNAME" => $_USER->username,
                       ":FRIEND"   => $User
                   ]);
    }
}