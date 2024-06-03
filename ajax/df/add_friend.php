<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activation
//- Requires ($_GET["u"])
if (!$_USER->logged_in)         { redirect("/login"); exit();   }
if (!$_USER->Is_Activated)    { redirect("/"); exit();        }
if (!isset($_GET["u"]))         { redirect("/"); exit();        }


$User = $DB->execute("SELECT username, can_friend FROM users WHERE displayname = :USER OR username = :USER LIMIT 1", true, [":USER" => $_GET["u"]]);
if ($DB->RowNum == 1) {
	$CanFriend  = $User["can_friend"];
	$User       = $User["username"];
	
	if ($CanFriend == 1 && $User !== $_USER->username) {
		$Friend_Status = $DB->execute("SELECT status, by_user, seen FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)", true,
                                     [
                                          ":USERNAME"    => $_USER->username,
                                          ":FRIEND"      => $User
                                     ]);

		$DB->execute("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OTHER) OR (blocker = :OTHER AND blocked = :USERNAME)", false,
                    [
                        ":USERNAME" => $_USER->username,
                        ":OTHER"    => $User
                    ]);

		if ($DB->RowNum == 0) {
			if ($Friend_Status) {
				$Status        = $Friend_Status["status"];
				$By            = $Friend_Status["by_user"];
				$Seen          = $Friend_Status["seen"];

				//Send To
				if (strtolower($_USER->username) == strtolower($By)) {
					$Send_To = $User;
				} else {
					$Send_To = $_USER->username;
				}

				if ($Status == 0) {
					if ($By === $_USER->username) {
						$DB->modify("DELETE FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)",
                                   [
                                       ":USERNAME"  => $_USER->username,
                                       ":FRIEND"    => $User
                                   ]);
					} else {
						$DB->modify("UPDATE friends SET status = '1', sent_on = NOW() WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)",
                                   [
                                       ":USERNAME"  => $_USER->username,
                                       ":FRIEND"    => $User
                                   ]);

						$DB->modify("UPDATE users SET friends = friends + 1 WHERE username = :USERNAME OR username = :FRIEND",
                                   [
                                       ":USERNAME"  => $_USER->username,
                                       ":FRIEND"    => $User
                                   ]);
					}
				} else {
					$DB->modify("DELETE FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :FRIEND) OR (friend_1 = :FRIEND AND friend_2 = :USERNAME)",
                               [
                                   ":USERNAME"  => $_USER->username,
                                   ":FRIEND"    => $User
                               ]);
				}
			} else {
				$DB->modify("INSERT INTO friends (friend_1,friend_2,by_user,status,sent_on,seen) VALUES (:USERNAME,:FRIEND,:USERNAME,'0',NOW(),'0')",
                           [
                               ":USERNAME"  => $_USER->username,
                               ":FRIEND"    => $User
                           ]);
			}
		}
	}
}
redirect(previous_page());
