<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activated
//- Requires ($_POST["vl_comment"]) AND ($_POST["video_url"])
if (!$_USER->logged_in)                                             { exit(); }
if (!$_USER->Is_Activated)                                          { exit(); }
if (!isset($_POST["vl_comment"]) || !isset($_POST["video_url"]))    { exit(); }
if (!isset($_SESSION["deto"]))                                      { exit(); }


$_GUMP->validation_rules(array(
    "vl_comment"    => "required|max_len,1005|min_len,1",
    "video_url"     => "required"
));

$_GUMP->filter_rules(array(
    "vl_comment"    => "trim|NoHTML",
    "video_url"     => "trim|NoHTML"
));

$Validation = $_GUMP->run($_POST);

if ($Validation) {
    $URL     = $Validation["video_url"];
    $Comment = $Validation["vl_comment"];

    $Video  = new Video($URL,$DB);
    $URL    = $Video->exists();


    if ($URL !== false) {
        //CHECK IF COMMENTS HAS BEEN WRITTEN BEFORE
        $Check = $DB->execute("SELECT id FROM video_comments WHERE comment = :COMMENT AND by_user = :BY_USER AND url = :URL", false,
                             [
                                 ":URL"     => $URL,
                                 ":COMMENT" => $Comment,
                                 ":BY_USER" => $_USER->username
                             ]);

        //CHECK IF LAST 5 COMMENTS ARE BY THIS USER
        $Check2 = $DB->execute("SELECT by_user FROM video_comments WHERE url = :URL ORDER BY date_sent DESC LIMIT 5", false, [":URL" => $URL]);

        if (isset($Check2[0]["by_user"],$Check2[1]["by_user"],$Check2[2]["by_user"],$Check2[3]["by_user"],$Check2[4]["by_user"])) {
            if ($Check2[0]["by_user"] == $_USER->username && $Check2[1]["by_user"] == $_USER->username && $Check2[2]["by_user"] == $_USER->username && $Check2[3]["by_user"] == $_USER->username && $Check2[4]["by_user"] == $_USER->username ) {
                $Not_Spam = false;
            } else {
                $Not_Spam = true;
            }
        } else {
            $Not_Spam = true;
        }

        if (!$Check && $Not_Spam) {
            $Video_Info = $DB->execute("SELECT uploaded_by, s_comments FROM videos WHERE url = :URL", true, [":URL" => $URL]);

            $Username = $Video_Info["uploaded_by"];

            if ($Video_Info["s_comments"] == 0 && $_USER->username != $Username) { die(); }
            if ($Video_Info["s_comments"] == 2 && $_USER->username != $Username) {

                $DB->execute("SELECT id FROM friends WHERE ((friend_1 = :USERNAME AND friend_2 = :UPLOADER) OR (friend_1 = :UPLOADER AND friend_2 = :USERNAME)) AND status = 1", false,
                            [
                                ":USERNAME" => $_USER->username,
                                ":UPLOADER" => $Username
                            ]);

                if ($DB->RowNum == 0) { die(); }

            }

            $DB->execute("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OTHER) OR (blocker = :OTHER AND blocked = :USERNAME)", false,
                        [
                            ":USERNAME" => $_USER->username,
                            ":OTHER"    => $Username
                        ]);

            if ($DB->RowNum == 0) {
                if ($Username !== $_USER->username) {
                    $DB->modify("INSERT INTO video_comments (url,comment,by_user,date_sent) VALUES (:URL,:COMMENT,:BY_USER,NOW())",
                               [
                                   ":URL"       => $URL,
                                   ":COMMENT"   => $Comment,
                                   ":BY_USER"   => $_USER->username
                               ]);
                } else {
                    $DB->modify("INSERT INTO video_comments (url,comment,by_user,date_sent,seen) VALUES (:URL,:COMMENT,:BY_USER,NOW(),1)",
                               [
                                   ":URL"       => $URL,
                                   ":COMMENT"   => $Comment,
                                   ":BY_USER"   => $_USER->username
                               ]);
                }
                $Last_ID = $DB->last_id();


                $DB->modify("UPDATE videos SET comments = comments + 1 WHERE url = :URL", [":URL" => $URL]);


                $Select = $DB->execute("SELECT video_comments.id, video_comments.by_user, video_comments.comment, users.avatar FROM video_comments INNER JOIN users ON video_comments.by_user = users.username WHERE video_comments.id = $Last_ID", true);


                if (strpos($Select["avatar"], "u=") !== false) {
                    $Avatar = str_replace("u=", "", $Select["avatar"]);
                    $Folder = "avt";
                } elseif (!empty($Select["avatar"])) {
                    $Upload = false;
                    $Folder = "thmp";
                    $Avatar = $Select["avatar"];
                } else {
                    $Avatar = "";
                    $Folder = "a";
                }

                if (empty($Avatar) || !file_exists($_SERVER['DOCUMENT_ROOT'] . "/usfi/$Folder/$Avatar.jpg")) {
                    $Select["avatar"] = "/img/no.jpg";
                } else {
                    if ($Folder == "avt") {
                        $Select["avatar"] = "/usfi/avt/$Avatar.jpg";
                    } else {
                        $Select["avatar"] = "/usfi/thmp/$Avatar.jpg";
                    }
                }


                $Select["comment"]  = showBBcodes(hashtag_search(mention(nl2br($Select["comment"]))));
                $Select["response"] = "success";
				$Select["by_user"]  = $_USER->displayname;

                if (strpos($Comment, "@") !== false) {
                    preg_match_all("/(?<!\S)@([0-9a-zA-Z]+)/", $Comment, $Mentions);
                    foreach ($Mentions[1] as $Mention) {
						$Exist = $DB->execute("SELECT username FROM users WHERE displayname = :USER AND can_mention = 1 LIMIT 1", true, [":USER" => $Mention]);
                        if (ctype_alnum($Mention) && $DB->RowNum > 0 && strtolower($_USER->username) !== strtolower($Mention)) {
							$Exist = $Exist["username"];

							$DB->execute("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OTHER) OR (blocker = :OTHER AND blocked = :USERNAME)", false,
                                        [
                                            ":USERNAME" => $_USER->username,
                                            ":OTHER"    => $Exist
                                        ]);

							if ($DB->RowNum == 0) {
                                $DB->modify("INSERT INTO mentions (video,type,date,username) VALUES (:COMMENT_ID,0,NOW(),:USERNAME)",
                                            [
                                                ":COMMENT_ID"   => $Select["id"],
                                                ":USERNAME"     => $Exist
                                            ]);
                            }
                        }
                    }
                }


                die(json_encode($Select));
            }
        } else {
            if ($Not_Spam == false) {
                die(json_encode(array("response" => "spam")));
            } elseif ($Check) {
                die(json_encode(array("response" => "spam2")));
            }
        }
    }
}