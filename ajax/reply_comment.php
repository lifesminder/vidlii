<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activated
//- Requires ($_POST["vl_comment"])
if (!$_USER->logged_in)               { exit(); }
if (!$_USER->Is_Activated)          { exit(); }
if (!isset($_POST["vl_comment"]))     { exit(); }
if (!isset($_SESSION["deto"]))      { exit(); }


$_GUMP->validation_rules(array(
    "vl_comment"    => "required|max_len,1005|min_len,1"
));

$_GUMP->filter_rules(array(
    "vl_comment"    => "trim|NoHTML"
));

$Validation = $_GUMP->run($_POST);

if ($Validation) {
    $Comment = $Validation["vl_comment"];
    $ID      = (int)$Validation["comment_id"];

    $Check_OP = $DB->execute("SELECT id, url, by_user FROM video_comments WHERE id = :ID", true, [":ID" => $ID]);

    if ($DB->RowNum == 0) { die(); }

    $URL = $Check_OP["url"];
    $OP  = $Check_OP["by_user"];


    //CHECK IF COMMENTS HAS BEEN WRITTEN BEFORE
    $Check = $DB->execute("SELECT id FROM video_comments WHERE comment = :COMMENT AND by_user = :BY_USER AND url = :URL", true,
                         [
                             ":URL"     => $URL,
                             ":COMMENT" => $Comment,
                             ":BY_USER" => $_USER->username
                         ]);

    if ($_USER->username !== $OP) {
        $Blocked = $DB->execute("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OP) OR (blocker = :OP AND blocked = :USERNAME)", true,
                               [
                                   ":USERNAME"  => $_USER->username,
                                   ":OP"        => $OP
                               ]);

        if ($DB->RowNum == 1) { $Blocked = true; } else { $Blocked = false; }
    } else {
        $Blocked = false;
    }

    if (!$Check && $Blocked == false) {

        if ($OP !== $_USER->username) {
            $DB->modify("INSERT INTO video_comments (url,comment,by_user,date_sent,reply_to) VALUES (:URL,:COMMENT,:BY_USER,NOW(),:ID)",
                       [
                           ":URL"       => $URL,
                           ":COMMENT"   => $Comment,
                           ":BY_USER"   => $_USER->username,
                           ":ID"        => $ID
                       ]);
        } else {
            $DB->modify("INSERT INTO video_comments (url,comment,by_user,date_sent,seen,reply_to) VALUES (:URL,:COMMENT,:BY_USER,NOW(),1,:ID)",
                       [
                           ":URL"       => $URL,
                           ":COMMENT"   => $Comment,
                           ":BY_USER"   => $_USER->username,
                           ":ID"        => $ID
                       ]);
        }
        $Last_ID = $DB->last_id();
        $DB->modify("UPDATE video_comments SET has_replies = 1 WHERE id = :ID", [":ID" => $ID]);


        $Select = $DB->execute("SELECT video_comments.id, video_comments.by_user, video_comments.comment, users.avatar FROM video_comments INNER JOIN users ON video_comments.by_user = users.username WHERE video_comments.id = $Last_ID", true);

        if   (strpos($Select["avatar"],"u=") !== false) { $Avatar = str_replace("u=","",$Select["avatar"]); $Folder = "avt"; }
        elseif (!empty($Select["avatar"])) { $Upload = false; $Folder = "thmp"; $Avatar = $Select["avatar"]; } else { $Avatar = ""; $Folder = "a"; }

        if (empty($Avatar) || !file_exists($_SERVER['DOCUMENT_ROOT']."/usfi/$Folder/$Avatar.jpg")) {
            $Select["avatar"] = "/img/no.png";
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

        if ($OP !== $_USER->username) {
            $DB->modify("INSERT INTO replies (id,for_user,seen) VALUES (:ID,:USER,0)",
                       [
                           ":ID"    => $Select["id"],
                           ":USER"  => $OP
                       ]);
        }


        if (strpos($Comment,"@") !== false) {

            preg_match_all("/(?<!\S)@([0-9a-zA-Z]+)/", $Comment, $Mentions);

            foreach ($Mentions[1] as $Mention) {

				$Exist = $DB->execute("SELECT username FROM users WHERE displayname = :USER AND can_mention = 1 LIMIT 1", true, [":USER" => $Mention]);

                if (ctype_alnum($Mention) && $DB->RowNum > 0 && strtolower($_USER->displayname) !== strtolower($Mention)) {
					$Exist = $Exist["username"];

					$DB->execute("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OTHER) OR (blocker = :OTHER AND blocked = :USERNAME)", false,
                                [
                                    ":USERNAME" => $_USER->username,
                                    ":OTHER"    => $Exist
                                ]);

                    if ($DB->RowNum == 0) {

                        $DB->modify("INSERT INTO mentions (video,type,date,username) VALUES (:COMMENT_ID,0,NOW(),:USERNAME)",
                                   [
                                       ":COMMENT_ID" => $Select["id"],
                                       ":USERNAME"   => $Exist
                                   ]);

                    }
                }
            }
        }

        echo json_encode($Select);
    } else {
        if ($Blocked) {
            echo json_encode(array("response" => "block"));
        } else {
            echo json_encode(array("response" => "spam2"));
        }
    }
}