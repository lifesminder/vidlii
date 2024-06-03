<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["user"])
//- User doesn't equal Block
if (!$_USER->logged_in)                                         { exit(); }
if (!isset($_POST["user"]))                                     { exit(); }
if (strtolower($_USER->username) == strtolower($_POST["user"])) { exit(); }


$User = $DB->execute("SELECT username FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_POST["user"]]);


if ($DB->RowNum == 1) {
    $User = $User["username"];
    //CHECK BLOCK STATUS
    $Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = '$_USER->username' AND blocked = :USER) OR (blocker = :USER AND blocked = '$_USER->username')", true, [":USER" => $User]);

    if ($DB->RowNum > 0) {
        if ($Blocked["blocker"] == $_USER->username) {
            $Has_Blocked    = true;
            $Is_Blocked     = false;
        } else {
            $Has_Blocked    = false;
            $Is_Blocked     = true;
        }
    } else {
        $Has_Blocked    = false;
        $Is_Blocked     = false;
    }

    if (!$Has_Blocked && !$Is_Blocked) {
        //BLOCK USER
        $DB->modify("INSERT INTO users_block VALUES (:YOU,:USER)",
                   [
                       ":YOU"   => $_USER->username,
                       ":USER"  => $User
                   ]);


        //REMOVE SUBSCRIPTIONS
        $DB->modify("DELETE FROM subscriptions WHERE subscriber = :USER AND subscription = :YOU",
                   [
                       ":YOU"   => $_USER->username,
                       ":USER"  => $User
                   ]);
        if ($DB->RowNum == 1) {
            $DB->modify("UPDATE users SET subscriptions = subscriptions - 1 WHERE username = :USER", [":USER" => $User]);
            $DB->modify("UPDATE users SET subscribers = subscribers - 1 WHERE username = :YOU", [":YOU" => $_USER->username]);
        }

        $DB->modify("DELETE FROM subscriptions WHERE subscription = :USER AND subscriber = :YOU",
                   [
                       ":YOU"   => $_USER->username,
                       ":USER"  => $User
                   ]);
        if ($DB->RowNum == 1) {
            $DB->modify("UPDATE users SET subscribers = subscribers - 1 WHERE username = :USER", [":USER" => $User]);
            $DB->modify("UPDATE users SET subscriptions = subscriptions - 1 WHERE username = :YOU", [":YOU" => $_USER->username]);
        }


        //REMOVE FRIENDS
        $Friends = $DB->execute("Select friend_1, friend_2, status, seen, by_user FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :OTHER) OR (friend_1 = :OTHER AND friend_2 = :USERNAME)", true,
                               [
                                   ":USERNAME"  => $_USER->username,
                                   ":OTHER"     => $User
                               ]);

        if ($DB->RowNum > 0) {
            $Status     = $Friends["status"];
            $Friend_1   = $Friends["friend_1"];
            $Friend_2   = $Friends["friend_2"];
            $Seen       = $Friends["seen"];

            //Send To
            if (strtolower($_USER->username) == strtolower($Friends["by_user"])) {
                $Send_To = $User;
            } else {
                $Send_To = $_USER->username;
            }

            $DB->modify("DELETE FROM friends WHERE friend_1 = '$Friend_1' AND friend_2 = '$Friend_2' AND status = $Status");
            if ($DB->RowNum == 1 && $Status == 1) {
                $DB->modify("UPDATE users SET friends = GREATEST(friends - 1, 0) WHERE username = '$Friend_1'");
                $DB->modify("UPDATE users SET friends = GREATEST(friends - 1, 0) WHERE username = '$Friend_2'");
            }
        }


        echo "0";
    } elseif ($Has_Blocked == true) {
        //UNBLOCK USER
        $DB->modify("DELETE FROM users_block WHERE blocker = :YOU AND blocked = :USER",
                   [
                       ":YOU"   => $_USER->username,
                       ":USER"  => $User
                   ]);
        echo "1";
    }
}