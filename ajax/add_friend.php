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
if (!isset($_SESSION["deto"])) { exit(); }

$User = $DB->execute("SELECT username, can_friend FROM users WHERE username = :USERNAME LIMIT 1", true, [":USERNAME" => $_POST["user"]]);

if ($DB->RowNum == 1) {
	$Username = $User["username"];


    if ($User["username"] == $_USER->username) {
        die(json_encode(array("response" => "You cannot friend yourself.")));
    }

    $Friend_Status = $DB->execute("SELECT status, by_user, seen FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)", true,
                                 [
                                      ":USERNAME"    => $_USER->username,
                                      ":FRIEND"      => $Username
                                 ]);

    $DB->execute("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OTHER) OR (blocker = :OTHER AND blocked = :USERNAME)", false,
                [
                    ":USERNAME" => $_USER->username,
                    ":OTHER"    => $Username
                ]);

    if ($DB->RowNum == 0 && $Friend_Status) {
        $Status         = $Friend_Status["status"];
        $By             = $Friend_Status["by_user"];
        $Seen           = $Friend_Status["seen"];

        //Send To
        if (strtolower($_USER->username) == strtolower($By)) {
            $Send_To = $Username;
        } else {
            $Send_To = $_USER->username;
        }


        if ($Status == 0) {
            if ($By === $_USER->username) {
                $DB->modify("DELETE FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)",
                           [
                               ":USERNAME"  => $_USER->username,
                               ":FRIEND"    => $Username
                           ]);

                die(json_encode(array("response" => "0")));
            } else {
                $DB->modify("UPDATE friends SET status = '1', sent_on = NOW() WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)",
                           [
                               ":USERNAME"  => $_USER->username,
                               ":FRIEND"    => $Username
                           ]);

                $DB->modify("UPDATE users SET friends = friends + 1 WHERE username = :USERNAME OR username = :FRIEND",
                           [
                               ":USERNAME" => $_USER->username,
                               ":FRIEND"   => $Username
                           ]);

                die(json_encode(array("response" => "1")));
            }
        } else {
            $DB->modify("DELETE FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)",
                       [
                           ":USERNAME"  => $_USER->username,
                           ":FRIEND"    => $Username
                       ]);

            $DB->modify("UPDATE users SET friends = GREATEST(friends - 1, 0) WHERE username = :USERNAME OR username = :FRIEND",
                       [
                           ":USERNAME"  => $_USER->username,
                           ":FRIEND"    => $Username
                       ]);

            die(json_encode(array("response" => "2")));
        }
    } else {
        if ($User["can_friend"] == 0) {
            die(json_encode(array("response" => "This user has disabled friend requests.")));
        }

        $DB->modify("INSERT INTO friends (friend_1,friend_2,by_user,status,sent_on,seen) VALUES (:USERNAME,:FRIEND,:USERNAME,'0',NOW(),'0')",
                   [
                       ":USERNAME"  => $_USER->username,
                       ":FRIEND"    => $Username
                   ]);

        die(json_encode(array("response" => "3")));
    }
}
