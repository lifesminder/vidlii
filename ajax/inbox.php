<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activation
if (!$_USER->logged_in)         { redirect("/login"); exit();   }
if (!$_USER->Is_Activated)    { redirect("/"); exit();        }


if (!isset($_GET["page"])) {
    $Page_Pr = "Main";
} else {
    $Page_Pr = $_GET["page"];
}

switch($Page_Pr) {
    case "messages" :
        $Page = "Messages";
        break;
    case "comments" :
        $Page = "Comments";
        break;
    case "invites" :
        $Page = "Invites";
        break;
    case "sent" :
        $Page = "Sent";
        break;
    case "responses" :
        $Page = "Responses";
        break;
    case "send_message" :
        $Page = "Send";
        break;
    default :
        $Page = "Messages";
}

$_PAGINATION = new Pagination(25,100);

if ($Page == "Messages") {

    if (!isset($_POST["search_inbox"])) {
        $Inbox = $DB->execute("SELECT private_messages.id, private_messages.from_user, private_messages.message, private_messages.subject, private_messages.date_sent, private_messages.seen, users.avatar, users.displayname FROM private_messages INNER JOIN users ON private_messages.from_user = users.username WHERE private_messages.to_user = :USERNAME ORDER BY private_messages.date_sent DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

        $_PAGINATION->Total = $DB->execute("SELECT count(private_messages.id) as amount FROM private_messages WHERE to_user = :USERNAME", true, [":USERNAME" => $_USER->username])["amount"];
    } else {
        if (isset($_POST["search_input"]) && mb_strlen($_POST["search_input"]) >= 4 && mb_strlen($_POST["search_input"]) <= 64) {
            $Filtered_Search_Query = str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $_POST["search_input"]);

            $Inbox = $DB->execute("SELECT private_messages.id, private_messages.from_user, private_messages.message, private_messages.subject, private_messages.date_sent, private_messages.seen, users.avatar, users.displayname FROM private_messages INNER JOIN users ON private_messages.from_user = users.username WHERE private_messages.to_user = :USERNAME AND (private_messages.message LIKE '% $Filtered_Search_Query %' OR private_messages.subject LIKE '% $Filtered_Search_Query %') ORDER BY private_messages.date_sent DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

            if ($DB->RowNum > 0) {
                foreach ($Inbox as $ID => $Inbox_Highlight) {
                    $Inbox[$ID]["message"] = preg_replace("/\b($Filtered_Search_Query)\b/i",'<strong>\1</strong>',$Inbox[$ID]["message"]);
                    $Inbox[$ID]["subject"] = preg_replace("/\b($Filtered_Search_Query)\b/i",'<strong>\1</strong>',$Inbox[$ID]["subject"]);
                }

                $_PAGINATION->Total = $DB->execute("SELECT count(private_messages.id) as amount FROM private_messages WHERE to_user = :USERNAME AND (private_messages.message LIKE '% $Filtered_Search_Query %' OR private_messages.subject LIKE '% $Filtered_Search_Query %')", true, [":USERNAME" => $_USER->username])["amount"];;
            } else {
                notification("No messages could be found!","/inbox?page=messages","red"); exit();
            }
        } elseif (mb_strlen($_POST["search_input"]) < 4) {
            notification("Your search must be at least 4 characters long!","/inbox?page=messages","red"); exit();
        }
    }
} elseif ($Page == "Comments") {
    if (!isset($_GET["t"])) {
        $Inbox = $DB->execute("SELECT 'comment' as type_name, video_comments.id, videos.length, video_comments.comment, video_comments.by_user, video_comments.date_sent as date_sent, video_comments.seen, videos.url, videos.title, videos.description, users.avatar, users.displayname, '2' as under_type FROM video_comments INNER JOIN videos ON video_comments.url = videos.url INNER JOIN users ON users.username = video_comments.by_user WHERE videos.uploaded_by = :USERNAME AND video_comments.by_user <> :USERNAME AND reply_to = 0 AND video_comments.seen <> 2
                                  UNION ALL SELECT 'mention' as type_name, mentions.video as id, videos.length, video_comments.comment, video_comments.by_user, video_comments.date_sent as date_sent, mentions.seen as seen, videos.url, videos.title, videos.description, users.avatar, users.displayname, type as under_type FROM mentions INNER JOIN video_comments ON video_comments.id = mentions.video INNER JOIN videos ON videos.url = video_comments.url INNER JOIN users ON users.username = video_comments.by_user WHERE mentions.username = :USERNAME AND mentions.seen <> 2
                                  UNION ALL SELECT 'mention' as type_name, mentions.channel as id, '' as length, channel_comments.comment, channel_comments.by_user, channel_comments.date as date_sent, mentions.seen as seen, '' as url, channel_comments.on_channel as title, '' as description, users.avatar, users.displayname, type as under_type FROM mentions INNER JOIN channel_comments ON channel_comments.id = mentions.channel INNER JOIN users ON users.username = channel_comments.by_user WHERE mentions.username = :USERNAME AND mentions.seen <> 2
                                  UNION ALL SELECT 'reply' as type_name, replies.id as id, videos.length, video_comments.comment, video_comments.by_user, video_comments.date_sent as date_sent, replies.seen as seen, videos.url, videos.title, videos.description, users.avatar, users.displayname, '' as description FROM replies INNER JOIN video_comments ON video_comments.id = replies.id INNER JOIN videos ON videos.url = video_comments.url INNER JOIN users ON users.username = video_comments.by_user WHERE replies.for_user = :USERNAME AND replies.seen <> 2
                                  UNION ALL SELECT 'channel' as type_name, channel_comments.id as id, '' as length, channel_comments.comment, channel_comments.by_user, channel_comments.date as date_sent, channel_comments.seen as seen, '' as url, channel_comments.on_channel as title, '' as description, users.avatar, users.displayname, '' as under_type FROM channel_comments INNER JOIN users ON users.username = channel_comments.by_user WHERE channel_comments.on_channel = :USERNAME AND channel_comments.by_user <> :USERNAME AND channel_comments.seen <> 2
                                  ORDER BY date_sent DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

        $Total   = $DB->execute("SELECT video_comments.id FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE videos.uploaded_by = :USERNAME AND video_comments.by_user <> :USERNAME AND reply_to = 0
                                 UNION ALL SELECT mentions.type FROM mentions INNER JOIN video_comments ON video_comments.id = mentions.video INNER JOIN videos ON videos.url = video_comments.url WHERE mentions.username = :USERNAME
                                 UNION ALL SELECT mentions.type FROM mentions INNER JOIN channel_comments ON channel_comments.id = mentions.channel WHERE mentions.username = :USERNAME
                                 UNION ALL SELECT replies.id FROM replies INNER JOIN video_comments ON video_comments.id = replies.id INNER JOIN videos ON videos.url = video_comments.url WHERE replies.for_user = :USERNAME
                                 UNION ALL SELECT id FROM channel_comments WHERE on_channel = :USERNAME AND by_user <> :USERNAME
                                 ", true, [":USERNAME" => $_USER->username]);

        $_PAGINATION->Total = $DB->RowNum;
    } elseif ($_GET["t"] == 2) {
        $Inbox = $DB->execute("SELECT 'comment' as type_name, video_comments.id, videos.length, video_comments.comment, video_comments.by_user, video_comments.date_sent as date_sent, video_comments.seen, videos.url, videos.title, videos.description, users.avatar, users.displayname, '2' as under_type FROM video_comments INNER JOIN videos ON video_comments.url = videos.url INNER JOIN users ON users.username = video_comments.by_user WHERE videos.uploaded_by = :USERNAME AND video_comments.by_user <> :USERNAME AND reply_to = 0 AND video_comments.seen <> 2 ORDER BY date_sent DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

        $_PAGINATION->Total = $DB->execute("SELECT count(id) as amount FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE videos.uploaded_by = :USERNAME AND video_comments.by_user <> :USERNAME AND seen <> 2", true, [":USERNAME" => $_USER->username])["amount"];
    } elseif ($_GET["t"] == 3) {
        $Inbox = $DB->execute("SELECT 'channel' as type_name, channel_comments.id as id, '' as length, channel_comments.comment, channel_comments.by_user, channel_comments.date as date_sent, channel_comments.seen as seen, '' as url, channel_comments.on_channel as title, '' as description, users.avatar, users.displayname, '' as under_type FROM channel_comments INNER JOIN users ON users.username = channel_comments.by_user WHERE channel_comments.on_channel = :USERNAME AND channel_comments.by_user <> :USERNAME AND channel_comments.seen <> 2 ORDER BY date_sent DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

        $_PAGINATION->Total = $DB->execute("SELECT count(id) as amount FROM channel_comments WHERE on_channel = :USERNAME AND by_user <> :USERNAME AND seen <> 2", true, [":USERNAME" => $_USER->username])["amount"];;
    } elseif ($_GET["t"] == 4) {
        $Inbox = $DB->execute("SELECT 'mention' as type_name, mentions.video as id, videos.length, video_comments.comment, video_comments.by_user, video_comments.date_sent as date_sent, mentions.seen as seen, videos.url, videos.title, videos.description, users.avatar, users.displayname, type as under_type FROM mentions INNER JOIN video_comments ON video_comments.id = mentions.video INNER JOIN videos ON videos.url = video_comments.url INNER JOIN users ON users.username = video_comments.by_user WHERE mentions.username = :USERNAME AND mentions.seen <> 2 UNION ALL SELECT 'mention' as type_name, mentions.channel as id, '' as length, channel_comments.comment, channel_comments.by_user, channel_comments.date as date_sent, mentions.seen as seen, '' as url, channel_comments.on_channel as title, '' as description, users.avatar, users.displayname, type as under_type FROM mentions INNER JOIN channel_comments ON channel_comments.id = mentions.channel INNER JOIN users ON users.username = channel_comments.by_user WHERE mentions.username = :USERNAME AND mentions.seen <> 2 ORDER BY date_sent DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

        $_PAGINATION->Total = $DB->execute("SELECT count(type) as amount FROM mentions WHERE username = :USERNAME AND seen <> 2", true, [":USERNAME" => $_USER->username])["amount"];
    } elseif ($_GET["t"] == 5) {
        $Inbox = $DB->execute("SELECT 'reply' as type_name, replies.id as id, videos.length, video_comments.comment, video_comments.by_user, video_comments.date_sent as date_sent, replies.seen as seen, videos.url, videos.title, videos.description, users.avatar, users.displayname, '' as description FROM replies INNER JOIN video_comments ON video_comments.id = replies.id INNER JOIN videos ON videos.url = video_comments.url INNER JOIN users ON users.username = video_comments.by_user WHERE replies.for_user = :USERNAME AND replies.seen <> 2 ORDER BY date_sent DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

        $_PAGINATION->Total = $DB->execute("SELECT count(id) as amount FROM replies WHERE for_user = :USERNAME AND seen <> 2", true, [":USERNAME" => $_USER->username])["amount"];
    }

} elseif ($Page == "Sent") {
    $Inbox = $DB->execute("SELECT private_messages.id, private_messages.to_user, private_messages.message, private_messages.subject, private_messages.date_sent, private_messages.seen, users.avatar, users.displayname FROM private_messages INNER JOIN users ON private_messages.to_user = users.username WHERE private_messages.from_user = :USERNAME ORDER BY private_messages.date_sent DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

    $_PAGINATION->Total = $DB->execute("SELECT count(id) as amount FROM private_messages WHERE from_user = :USERNAME", true, [":USERNAME" => $_USER->username])["amount"];
} elseif ($Page == "Invites") {
    $Inbox = $DB->execute("SELECT friends.seen, friends.id, users.username, users.avatar, users.displayname, friends.sent_on FROM users INNER JOIN friends ON friends.friend_1 = users.username OR friends.friend_2 = users.username WHERE (friends.friend_1 = :USERNAME OR friends.friend_2 = :USERNAME) AND friends.status = 0 AND users.username <> :USERNAME AND friends.by_user <> :USERNAME ORDER BY friends.sent_on DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => $_USER->username]);

    $_PAGINATION->Total = $DB->execute("SELECT count(by_user) as amount FROM friends WHERE (friend_1 = :USERNAME OR friend_2 = :USERNAME) AND status = 0 AND by_user <> :USERNAME", true, [":USERNAME" => $_USER->username])["amount"];
} elseif ($Page == "Responses") {
    $Inbox = $DB->execute("SELECT video_responses.id, video_responses.url as response_to, videos.length, videos.url, video_responses.date as sent_on, videos.title, videos.description, videos.uploaded_by as username, users.avatar, users.displayname, video_responses.seen FROM video_responses INNER JOIN videos ON video_responses.url_response = videos.url INNER JOIN users ON users.username = videos.uploaded_by WHERE video_responses.accepted = 0 AND video_responses.response_user = :USERNAME ORDER BY video_responses.date DESC", false, [":USERNAME" => $_USER->username]);
} elseif ($Page == "Send") {
    $Friends = $DB->execute("SELECT users.displayname FROM users INNER JOIN friends ON friends.friend_1 = users.username OR friends.friend_2 = users.username WHERE (friends.friend_1 = :USERNAME OR friends.friend_2 = :USERNAME) AND friends.status = 1 AND users.username <> :USERNAME", false, [":USERNAME" => $_USER->username]);
    if ($DB->RowNum == 0) {
        unset($Friends);
    }


    if (isset($_GET["s"])) {
        $Subject = $_GET["s"];
    }
    if (isset($_GET["to"])) {
        $To = $_GET["to"];
    }
    if (isset($_POST["send_message"])) {
        $_GUMP->validation_rules(array(
            "to_user"         => "required|alpha_numeric|max_len,21",
            "subject"         => "required|max_len,256|min_len,1",
            "message"         => "required|max_len,5000|min_len,1"
        ));

        $_GUMP->filter_rules(array(
            "to_user"         => "trim",
            "subject"         => "trim",
            "message"         => "trim"
        ));

        $Validation = $_GUMP->run($_POST);

        if ($Validation) {
			die("Fuck this code");
            $To_User = $Validation["to_user"];
            $Subject = $Validation["subject"];
            $Message = $Validation["message"];
			
            $Get_User = $DB->execute("SELECT username, can_message FROM users WHERE displayname = :USERNAME", true, [":USERNAME" => $To_User]);
			if ($DB->RowNum == 1) {
				$To_User = $Get_User["username"];
				
				if ($Get_User["can_message"] == 1) {
					$Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = :USERNAME AND blocked = :TO_USER) OR (blocker = :TO_USER AND blocked = :USERNAME)", false, [":USERNAME" => $_USER->username, ":TO_USER" => $To_User]);

					if ($DB->RowNum == 0) {
						$count = $DB->execute("SELECT count(*) as amount FROM private_messages INNER JOIN users ON users.username = private_messages.from_user WHERE users.1st_latest_ip = :IP AND private_messages.date_sent >= date_sub(now(), interval 20 minute)", true, [":IP" => user_ip()])["amount"];

						if ($count < 7) {
						$DB->modify("INSERT INTO private_messages (from_user,to_user,message,subject,date_sent) VALUES (:FROM,:TO,:MESSAGE,:SUBJECT,NOW())",
								   [
									   ":FROM"      => $_USER->username,
									   ":TO"        => $To_User,
									   ":MESSAGE"   => $Message,
									   ":SUBJECT"   => $Subject
								   ]);
						}
						if ($DB->RowNum == 1) {
							notification("Message successfully sent!","/inbox","green"); exit();
						} else {
							notification("Something went wrong!","/inbox","red"); exit();
						}
					} else {
						notification("You cannot interact with this user!", "/inbox?page=send_message", "red"); exit();
					}
				} else {
					notification("This user has disabled private messaging.", "/inbox?page=send_message", "red"); exit();
				}
			} else {
				notification("This user doesn't exist", "/inbox?page=send_message", "red"); exit();
			}
        }
    }
}

if ($Page == "Messages") {
    $Inbox_Title = "Personal Messages";
} elseif ($Page == "Comments") {
    $Inbox_Title = "Comments";
} elseif ($Page == "Invites") {
    $Inbox_Title = "Friend Invitations";
} elseif ($Page == "Responses") {
    $Inbox_Title = "Video Responses";
}elseif ($Page == "Send") {
    $Inbox_Title = "Send Message";
} else {
    $Inbox_Title = "Sent Messages";
}

$_PAGE->set_variables(array(
    "Page_Title"        => "$Inbox_Title - VidLii",
    "Page"              => $Page,
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/inbox_structure.php";
