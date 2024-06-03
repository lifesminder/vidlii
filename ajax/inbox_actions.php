<?php
// Exits if user is not logged in
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in) { exit(); }


// Escapes and prepares checkbox array for IN query
function processPMs($arr) {
	$pms = "";
	foreach($arr as $pm) {
		$pm = (int)$pm;
		$pms .= $pm . ",";
	}
	
	if ($pms == "")
		notification("You haven't selected any messages!", "/inbox", "red");
	
	return substr($pms, 0, -1);
}

switch($_POST["action"]) {
	case "send": // Send Message (copied from inbox.php)
        $_GUMP->validation_rules(array(
            "to_user"         => "required|alpha_numeric|max_len,21",
            "subject"         => "required|max_len,256|min_len,1",
            "message"         => "required|max_len,5000|min_len,1"
        ));

        $_GUMP->filter_rules(array(
            "to_user"         => "trim",
            "subject"         => "trim|NoHTML",
            "message"         => "trim|NoHTML"
        ));

        $Validation = $_GUMP->run($_POST);

        if ($Validation) {
            $To_User = $Validation["to_user"];
            $Subject = $Validation["subject"];
            $Message = $Validation["message"];
            $Check   = $DB->execute("SELECT username FROM users WHERE displayname = :USERNAME", true, [":USERNAME" => $To_User]);

			if ($DB->RowNum > 0) {
				$To_User = $Check["username"];
				$DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = '$_USER->username' AND blocked = '$To_User') OR (blocker = '$To_User' AND blocked = '$_USER->username')");
				if ($_POST["page"] !== 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }

				if ($DB->RowNum == 0) {
					$count = $DB->execute("SELECT count(*) as amount FROM private_messages INNER JOIN users ON users.username = private_messages.from_user WHERE users.1st_latest_ip = :IP AND private_messages.date_sent >= date_sub(now(), interval 5 minute)", true, [":IP" => user_ip()])["amount"];

					if ($count < 8) {
					$DB->modify("INSERT INTO private_messages (from_user,to_user,message,subject,date_sent) VALUES (:FROM,:TO,:MESSAGE,:SUBJECT,NOW())",
                               [
                                   ":FROM"      => $_USER->username,
                                   ":TO"        => $To_User,
                                   ":MESSAGE"   => $Message,
                                   ":SUBJECT"   => $Subject
                               ]);
					}
					if ($DB->RowNum == 1) {
						notification("Reply successfully sent to <strong>$Validation[to_user]</strong>!","","green");
					} else {
						notification("Something went wrong!","","red");
					}

                    die("/inbox?page=messages$Page");
                } else {
					notification("You cannot interact with this user!", "", "red");
					die("/inbox?page=send_message$Page");
				}
			} else {
				notification("This user doesn't exist", "", "red");
				die("/inbox?page=send_message$Page");
			}
		}
	case "inblk_del": // Delete Messages
		$pms = processPMs($_POST["selectedPM"]);
		$DB->modify("DELETE FROM private_messages WHERE id IN ($pms) AND to_user='$_USER->username'");
        if ($_POST["page"] !== 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }

        if ($DB->RowNum > 0) {
			if ($DB->RowNum > 1) { $Extra = "s were"; } else { $Extra = " was"; }
			notification("Message$Extra successfully deleted!","","green");
		} else {
			notification("Something went wrong!","","red");
		}

        if (!$_POST["ajax"]) redirect("/inbox");
        else die("/inbox");
	case "inblk_unread": // Mark Messages as Unread
		$pms = processPMs($_POST["selectedPM"]);
		$DB->modify("UPDATE private_messages SET seen=0 WHERE id IN($pms)");
		$affected = $DB->RowNum;
        if ($_POST["page"] !== 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if ($affected > 0) {
            if ($affected > 1) { $Extra = "s"; } else { $Extra = ""; }
			notification("Message$Extra successfully marked as unread!","/inbox?page=messages$Page","green");
		} else {
			notification("Something went wrong!","/inbox?page=messages$Page","red");
		}
	case "inblk_read": // Mark Messages as Read
		$pms = processPMs($_POST["selectedPM"]);
		$DB->modify("UPDATE private_messages SET seen=1 WHERE id IN($pms)");
		$affected = $DB->RowNum;
        if (isset($_POST["page"]) && $_POST["page"] !== 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if ($affected > 0) {
            if ($affected > 1) { $Extra = "s"; } else { $Extra = ""; }
			if (!$_POST["ajax"]) notification("Message$Extra successfully marked as read!","/inbox?page=messages$Page","green");
			else die("read");
		} else {
			if (!$_POST["ajax"])
				notification("Something went wrong!","/inbox?page=messages$Page","red");
		}
    case "inblkc_read" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            if (strpos($Bulk_ID,"c_") !== false) {
                $ID = (int)str_replace("c_","",$Bulk_ID);
                $DB->execute("SELECT video_comments.id FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE video_comments.id = :ID AND video_comments.seen = 0 AND videos.uploaded_by = :USERNAME", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);

                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE video_comments SET seen = 1 WHERE id = :ID", [":ID" => $ID]);
                }
            } elseif (strpos($Bulk_ID,"mv_") !== false) {
                $ID = (int)str_replace("mv_","",$Bulk_ID);
                $DB->execute("SELECT username FROM mentions WHERE video = :ID AND username = :USERNAME AND seen = 0", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);

                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE mentions SET seen = 1 WHERE video = :ID AND username = :USERNAME",
                               [
                                   ":ID"        => $ID,
                                   ":USERNAME"  => $_USER->username
                               ]);
                }
            } elseif (strpos($Bulk_ID,"mcn_") !== false) {
                $ID = (int)str_replace("mcn_","",$Bulk_ID);
                $DB->execute("SELECT username FROM mentions WHERE channel = :ID AND username = :USERNAME AND seen = 0", false,
                            [
                                ":ID" => $ID, ":USERNAME" => $_USER->username
                            ]);

                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE mentions SET seen = 1 WHERE channel = :ID AND username = :USERNAME",
                               [
                                   ":ID"       => $ID,
                                   ":USERNAME" => $_USER->username
                               ]);
                }
            } elseif (strpos($Bulk_ID,"mrp") !== false) {
                $ID = (int)str_replace("mrp_","",$Bulk_ID);
                $DB->execute("SELECT for_user FROM replies WHERE id = :ID AND for_user = :USERNAME AND seen = 0", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);

                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE replies SET seen = 1 WHERE id = :ID", [":ID" => $ID]);
                }
            } elseif (strpos($Bulk_ID,"zz") !== false) {
                $ID = (int)str_replace("zz_", "", $Bulk_ID);
                $DB->execute("SELECT on_channel FROM channel_comments WHERE id = :ID AND on_channel = :USERNAME AND seen = 0", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);

                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE channel_comments SET seen = 1 WHERE id = :ID", [":ID" => $ID]);
                }
            }
        }
        if ($_POST["page"] != 1)                { $Page     = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if ($_POST["type"] != 1)                { $Type     = "&t=".(int)$_POST["type"]; } else { $Type = ""; }
        if (count($_POST["selectedPM"]) > 1)    { $Extra    = "s were";                  } else { $Extra = " was"; }

        notification("Comment$Extra successfully marked as read!","/inbox?page=comments$Page$Type","green");
        break;
    case "inblkr_read" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            $DB->modify("UPDATE video_responses SET seen = 1 WHERE id = :ID AND seen = 0 AND response_user = :USERNAME",
                       [
                           ":ID"        => $Bulk_ID,
                           ":USERNAME"  => $_USER->username
                       ]);
        }
        if ($_POST["page"] != 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if (count($_POST["selectedPM"]) > 1) { $Extra = "s were"; } else { $Extra = " was"; }

        notification("Video Response$Extra successfully marked as read!","/inbox?page=responses$Page","green");
        break;
    case "inblkr_unread" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            $DB->modify("UPDATE video_responses SET seen = 0 WHERE id = :ID AND seen = 1 AND response_user = :USERNAME",
                       [
                           ":ID"        => $Bulk_ID,
                           ":USERNAME"  => $_USER->username
                       ]);
        }
        if ($_POST["page"] != 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if (count($_POST["selectedPM"]) > 1) { $Extra = "s were"; } else { $Extra = " was"; }

        notification("Video Response$Extra successfully marked as unread!","/inbox?page=responses$Page","green");
        break;
    case "inblki_unread" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            $DB->modify("UPDATE friends SET seen = 0 WHERE id = :ID AND seen = 1 AND by_user <> :USERNAME AND (friend_1 = :USERNAME OR friend_2 = :USERNAME)",
                       [
                           ":ID"        => $Bulk_ID,
                           ":USERNAME"  => $_USER->username
                       ]);
        }
        if ($_POST["page"] != 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if (count($_POST["selectedPM"]) > 1) { $Extra = "s were"; } else { $Extra = " was"; }

        notification("Friend Invite$Extra successfully marked as unread!","/inbox?page=invites$Page","green");
        break;
    case "inblki_read" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            $DB->modify("UPDATE friends SET seen = 1 WHERE id = :ID AND seen = 0 AND by_user <> :USERNAME AND (friend_1 = :USERNAME OR friend_2 = :USERNAME)",
                       [
                           ":ID"        => $Bulk_ID,
                           ":USERNAME"  => $_USER->username
                       ]);
        }
        if ($_POST["page"] != 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if (count($_POST["selectedPM"]) > 1) { $Extra = "s were"; } else { $Extra = " was"; }

        notification("Friend Invite$Extra successfully marked as read!","/inbox?page=invites$Page","green");
        break;
    case "inblki_decline" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            $Check = $DB->execute("SELECT seen FROM friends WHERE id = :ID AND status = 0 AND by_user <> :USERNAME AND (friend_1 = :USERNAME OR friend_2 = :USERNAME)", true,
                                 [
                                     ":ID"          => $Bulk_ID,
                                     ":USERNAME"    => $_USER->username
                                 ]);

            if ($DB->RowNum > 0) {
                $Seen = $Check["seen"];
                $DB->modify("DELETE FROM friends WHERE id = :ID AND by_user <> :USERNAME AND (friend_1 = :USERNAME OR friend_2 = :USERNAME)",
                           [
                               ":ID"        => $Bulk_ID,
                               ":USERNAME"  => $_USER->username
                           ]);
            }
        }
        if ($_POST["page"] != 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if (count($_POST["selectedPM"]) > 1) { $Extra = "s were"; } else { $Extra = " was"; }

        notification("Friend Invite$Extra successfully declined!","/inbox?page=invites$Page","green");
        break;
    case "inblki_accept" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            $Check = $DB->execute("SELECT seen, friend_1 FROM friends WHERE id = :ID AND status = 0 AND by_user <> :USERNAME AND (friend_1 = :USERNAME OR friend_2 = :USERNAME)", true,
                                 [
                                     ":ID"          => $Bulk_ID,
                                     ":USERNAME"    => $_USER->username
                                 ]);

            if ($DB->RowNum > 0) {
                $Seen   = $Check["seen"];
                $Friend = $Check["friend_1"];

                $DB->modify("UPDATE friends SET status = 1 WHERE id = :ID AND status = 0 AND by_user <> :USERNAME AND (friend_1 = :USERNAME OR friend_2 = :USERNAME)",
                           [
                               ":ID"        => $Bulk_ID,
                               ":USERNAME"  => $_USER->username
                           ]);

                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE users SET friends = friends + 1 WHERE username = :USERNAME OR username = :FRIEND",
                               [
                                   ":USERNAME"  => $_USER->username,
                                   ":FRIEND"    => $Friend
                               ]);
                }
            }
        }
        if ($_POST["page"] != 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if (count($_POST["selectedPM"]) > 1) { $Extra = "s were"; } else { $Extra = " was"; }

        notification("Friend Invite$Extra successfully accepted!","/inbox?page=invites$Page","green");
        break;
    case "inblkr_decline" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            $Check = $DB->execute("SELECT seen FROM video_responses WHERE id = :ID AND accepted = 0 AND response_user = :USERNAME", true,
                                 [
                                     ":ID"          => $Bulk_ID,
                                     ":USERNAME"    => $_USER->username
                                 ]);

            if ($DB->RowNum > 0) {
                $Seen = $Check["seen"];
                $DB->modify("DELETE FROM video_responses WHERE id = :ID", [":ID" => $Bulk_ID]);
            }
        }
        if ($_POST["page"] != 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if (count($_POST["selectedPM"]) > 1) { $Extra = "s were"; } else { $Extra = " was"; }

        notification("Video Response$Extra successfully declined!","/inbox?page=responses$Page","green");
        break;
    case "inblkr_accept" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            $Check = $DB->execute("SELECT seen, url FROM video_responses WHERE id = :ID AND accepted = 0 AND response_user = :USERNAME", true,
                                 [
                                     ":ID"          => $Bulk_ID,
                                     ":USERNAME"    => $_USER->username
                                 ]);
            if ($DB->RowNum > 0) {
                $Seen = $Check["seen"];
                $URL  = $Check["url"];
                $DB->modify("UPDATE videos SET responses = responses + 1 WHERE url = :URL", [":URL" => $URL]);
                $DB->modify("UPDATE video_responses SET accepted = 1 WHERE id = :ID", [":ID" => $Bulk_ID]);
            }
        }
        if ($_POST["page"] != 1)                { $Page     = "&p=".(int)$_POST["page"];    } else { $Page = ""; }
        if (count($_POST["selectedPM"]) > 1)    { $Extra    = "s were";                     } else { $Extra = " was"; }

        notification("Video Response$Extra successfully accepted!","/inbox?page=responses$Page","green");
        break;
    case "inblkc_unread" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            if (strpos($Bulk_ID,"c_") !== false) {
                $ID = (int)str_replace("c_","",$Bulk_ID);
                $DB->execute("SELECT video_comments.id FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE video_comments.id = :ID AND video_comments.seen = 1 AND videos.uploaded_by = :USERNAME", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);

                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE video_comments SET seen = 0 WHERE id = :ID", [":ID" => $ID]);
                }
            } elseif (strpos($Bulk_ID,"mv_") !== false) {
                $ID = (int)str_replace("mv_","",$Bulk_ID);
                $DB->execute("SELECT username FROM mentions WHERE video = :ID AND username = :USERNAME AND seen = 1", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);
                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE mentions SET seen = 0 WHERE video = :ID AND username = :USERNAME",
                               [
                                   ":ID"        => $ID,
                                   ":USERNAME"  => $_USER->username
                               ]);
                }
            } elseif (strpos($Bulk_ID,"mcn_") !== false) {
                $ID = (int)str_replace("mcn_","",$Bulk_ID);
                $DB->execute("SELECT username FROM mentions WHERE channel = :ID AND username = :USERNAME AND seen = 1", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);
                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE mentions SET seen = 0 WHERE channel = :ID AND username = :USERNAME",
                               [
                                   ":ID"        => $ID,
                                   ":USERNAME"  => $_USER->username
                               ]);
                }
            } elseif (strpos($Bulk_ID,"mrp") !== false) {
                $ID = (int)str_replace("mrp_","",$Bulk_ID);
                $DB->execute("SELECT for_user FROM replies WHERE id = :ID AND for_user = :USERNAME AND seen = 1", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);
                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE replies SET seen = 0 WHERE id = :ID", [":ID" => $ID]);
                }
            } elseif (strpos($Bulk_ID,"zz") !== false) {
                $ID = (int)str_replace("zz_", "", $Bulk_ID);
                $DB->execute("SELECT on_channel FROM channel_comments WHERE id = :ID AND on_channel = :USERNAME AND seen = 1", false,
                            [
                                ":ID"       => $ID,
                                ":USERNAME" => $_USER->username
                            ]);
                if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE channel_comments SET seen = 0 WHERE id = :ID", [":ID" => $ID]);
                }
            }
        }
        if ($_POST["page"] != 1) { $Page = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if ($_POST["type"] != 1) { $Type = "&t=".(int)$_POST["type"]; } else { $Type = ""; }
        if (count($_POST["selectedPM"]) > 1) { $Extra = "s were"; } else { $Extra = " was"; }

        notification("Comment$Extra successfully marked as unread!","/inbox?page=comments$Page$Type","green");
        break;
    case "inblkc_del" :
        foreach($_POST["selectedPM"] as $Bulk_ID) {
            if (strpos($Bulk_ID,"c_") !== false) {
                $ID    = (int)str_replace("c_","",$Bulk_ID);
                $Check = $DB->execute("SELECT video_comments.id, video_comments.seen FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE video_comments.id = :ID AND videos.uploaded_by = :USERNAME", true,
                                     [
                                         ":ID"          => $ID,
                                         ":USERNAME"    => $_USER->username
                                     ]);

                if ($DB->RowNum == 1) {
                    $Seen = $Check["seen"];

                    $DB->modify("UPDATE video_comments SET seen = 2 WHERE id = :ID", [":ID" => $ID]);
                }
            } elseif (strpos($Bulk_ID,"mv_") !== false) {
                $ID    = (int)str_replace("mv_","",$Bulk_ID);
                $Check = $DB->execute("SELECT seen FROM mentions WHERE video = :ID AND username = :USERNAME", true,
                                     [
                                         ":ID"          => $ID,
                                         ":USERNAME"    => $_USER->username
                                     ]);

                if ($DB->RowNum == 1) {
                    $Seen = $Check["seen"];

                    $DB->modify("UPDATE mentions SET seen = 2 WHERE video = :ID AND username = :USERNAME",
                               [
                                   ":ID"        => $ID,
                                   ":USERNAME"  => $_USER->username
                               ]);
                }
            } elseif (strpos($Bulk_ID,"mcn_") !== false) {
                $ID    = (int)str_replace("mcn_","",$Bulk_ID);
                $Check = $DB->execute("SELECT seen FROM mentions WHERE channel = :ID AND username = :USERNAME", true,
                                     [
                                         ":ID"          => $ID,
                                         ":USERNAME"    => $_USER->username
                                     ]);

                if ($DB->RowNum == 1) {
                    $Seen = $Check["seen"];

                    $DB->modify("UPDATE mentions SET seen = 2 WHERE channel = :ID AND username = :USERNAME",
                               [
                                   ":ID"        => $ID,
                                   ":USERNAME"  => $_USER->username
                               ]);
                }
            } elseif (strpos($Bulk_ID,"mrp") !== false) {
                $ID    = (int)str_replace("mrp_","",$Bulk_ID);
                $Check = $DB->execute("SELECT for_user, seen FROM replies WHERE id = :ID AND for_user = :USERNAME", true,
                                     [
                                         ":ID"          => $ID,
                                         ":USERNAME"    => $_USER->username
                                     ]);

                if ($DB->RowNum == 1) {
                    $Seen = $Check["seen"];

                    $DB->modify("UPDATE replies SET seen = 2 WHERE id = :ID", [":ID" => $ID]);
                }
            } elseif (strpos($Bulk_ID,"zz") !== false) {
                $ID    = (int)str_replace("zz_", "", $Bulk_ID);
                $Check = $DB->execute("SELECT on_channel, seen FROM channel_comments WHERE id = :ID AND on_channel = :USERNAME", true,
                                     [
                                         ":ID"          => $ID,
                                         ":USERNAME"    => $_USER->username
                                     ]);

                if ($DB->RowNum == 1) {
                    $Seen = $Check["seen"];

                    $DB->modify("UPDATE channel_comments SET seen = 2 WHERE id = :ID", [":ID" => $ID]);
                }
            }
        }
        if ($_POST["page"] != 1)                { $Page     = "&p=".(int)$_POST["page"]; } else { $Page = ""; }
        if ($_POST["type"] != 1)                { $Type     = "&t=".(int)$_POST["type"]; } else { $Type = ""; }
        if (count($_POST["selectedPM"]) > 1)    { $Extra    = "s were";                  } else { $Extra = " was"; }

        notification("Comment$Extra successfully deleted from your inbox!","/inbox?page=comments$Page$Type","green");

        break;
	default: echo 0;
}