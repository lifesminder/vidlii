<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activated
//- Requires ($_POST["comment"]) AND ($_POST["on_channel"])
if (!$_USER->logged_in)                                          { exit(); }
if (!$_USER->Is_Activated)                                     { exit(); }
if (!isset($_POST["comment"]) || !isset($_POST["on_channel"]))   { exit(); }
if (!isset($_SESSION["deto"]))                                      { exit(); }


$_GUMP->validation_rules(array(
    "comment"       => "required|max_len,505|min_len,2",
    "on_channel"    => "max_len,21|alpha_numeric|required"
));

$_GUMP->filter_rules(array(
    "comment"       => "trim|NoHTML",
    "on_channel"    => "trim|NoHTML"
));

$Validation = $_GUMP->run($_POST);

if ($Validation) {
    $User    = $DB->execute("SELECT username FROM users WHERE username = :ON_CHANNEL", true, [":ON_CHANNEL" => $Validation["on_channel"]]);
    $Comment = $Validation["comment"];

    if ($DB->RowNum == 1) {
        $User = $User["username"];
        //CHECK IF COMMENTS HAS BEEN WRITTEN BEFORE
        $Check = $DB->execute("SELECT id FROM channel_comments WHERE comment = :COMMENT AND by_user = :BY_USER AND on_channel = :CHANNEL AND DATE(date) = CURDATE()", false,
                             [
                                 ":CHANNEL" => $User,
                                 ":COMMENT" => $Comment,
                                 ":BY_USER" => $_USER->username
                             ]);

        $Check_Spam = $DB->execute("SELECT count(*) as amount FROM channel_comments WHERE by_user = :USERNAME AND date > DATE_SUB(NOW(), INTERVAL 16 MINUTE)", true, [":USERNAME" => $_USER->username])["amount"];

        //CHECK IF BLOCKED
        $DB->execute("SELECT blocker FROM users_block WHERE (blocker = :USERNAME AND blocked = :OTHER) OR (blocker = :OTHER AND blocked = :USERNAME)", false,
                    [
                        ":USERNAME" => $_USER->username,
                        ":OTHER"    => $User
                    ]);

        if (!$Check && $Check_Spam < 9 && $DB->RowNum == 0) {
            $DB->modify("INSERT INTO channel_comments (on_channel,by_user,comment,date) VALUES (:CHANNEL,:BY_USER,:COMMENT,NOW())",
                       [
                           ":CHANNEL" => $User,
                           ":COMMENT" => $Comment,
                           ":BY_USER" => $_USER->username
                       ]);
            $Last_ID = $DB->last_id();
            $DB->modify("UPDATE users SET channel_comments = channel_comments + 1 WHERE username = :CHANNEL", [":CHANNEL" => $User]);

            $ID     = $DB->execute("SELECT channel_comments.id, users.avatar FROM channel_comments INNER JOIN users ON channel_comments.by_user = users.username WHERE channel_comments.id = $Last_ID", true);
            $CID    = $ID["id"];


            if   (strpos($ID["avatar"],"u=") !== false) { $Avatar = str_replace("u=","",$ID["avatar"]); $Folder = "avt"; }
            elseif (!empty($ID["avatar"])) { $Upload = false; $Folder = "thmp"; $Avatar = $ID["avatar"]; } else { $Avatar = ""; $Folder = "a"; }

            if (empty($Avatar) || !file_exists($_SERVER['DOCUMENT_ROOT']."/usfi/$Folder/$Avatar.jpg")) {
                $AVATAR = "/img/no.png";
            } else {
                if ($Folder == "avt") {
                    $AVATAR = "/usfi/avt/$Avatar.jpg";
                } else {
                    $AVATAR = "/usfi/thmp/$Avatar.jpg";
                }
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

                            $DB->modify("INSERT INTO mentions (channel,type,date,username) VALUES (:COMMENT_ID,1,NOW(),:USERNAME)",
                                       [
                                           ":COMMENT_ID"    => $CID,
                                           ":USERNAME"      => $Exist
                                       ]);

                        }
                    }
                }
            }

			$Username = $_USER->username;
			$AVATAR = "";

            echo json_encode(array("response" => "success", "comment" => showBBcodes(hashtag_search(mention(nl2br($Validation["comment"])))), "id" => $CID, "avatar" => $AVATAR, "by_user" => $Username));
        }
    }
}