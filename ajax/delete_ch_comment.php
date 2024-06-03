<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
header("Content-Type: application/json", true);

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["bg"])
if (!$_USER->logged_in)           { exit(); }
if (!isset($_POST["c_c_id"]))     { exit(); }


$Comment_ID = (int)$_POST["c_c_id"];
$Info       = $DB->execute("SELECT id, on_channel, by_user, comment, seen, date FROM channel_comments WHERE id = :ID", true, [":ID" => $Comment_ID]);
$Comment_ID = $Info["id"];
$Channel    = $Info["on_channel"];
$By         = $Info["by_user"];
$Date       = $Info["date"];
$Comment    = $Info["comment"];
$Seen       = $Info["seen"];

if ($Channel === $_USER->username || $By === $_USER->username) {
    $Delete = $DB->modify("DELETE FROM channel_comments WHERE id = $Comment_ID");
    $Update = $DB->modify("UPDATE users SET channel_comments = GREATEST(channel_comments - 1, 0) WHERE username = '$Channel'");


    if (strpos($Comment,"@") !== false) {
        preg_match_all("/(?<!\S)@([0-9a-zA-Z]+)/", $Comment, $Mentions);
        foreach ($Mentions[1] as $Mention) {
            $Exist = $DB->execute("SELECT username FROM users WHERE username = :USERNAME", false, [":USERNAME" => $Mention]);
            if (ctype_alnum($Mention) && $DB->RowNum == 1) {
                $Check = $DB->execute("SELECT seen FROM mentions WHERE channel = :COMMENT_ID AND username = :USERNAME", true,
                                     [
                                         ":COMMENT_ID"  => $Comment_ID,
                                         ":USERNAME"    => $Exist["username"]
                                     ]);

                $DB->modify("DELETE FROM mentions WHERE channel = :COMMENT_ID AND username = :USERNAME",
                           [
                               ":COMMENT_ID"    => $Comment_ID,
                               ":USERNAME"      => $Exist["username"]
                           ]);
            }
        }
    }


    $New_Comment = $DB->execute("SELECT users.avatar, channel_comments.id, channel_comments.by_user, channel_comments.comment, channel_comments.on_channel, channel_comments.date FROM channel_comments INNER JOIN users ON channel_comments.by_user = users.username WHERE channel_comments.on_channel = '$Channel' AND channel_comments.date <= '$Date' ORDER BY channel_comments.date ASC LIMIT 10");
    if (isset($New_Comment[9])) {
        $New_Comment[9]["old_id"]   = $Comment_ID;
        $New_Comment[9]["new_com"]  = true;
        $New_Comment[9]["date"]     = get_time_ago($New_Comment[9]["date"]);


        if (strpos($New_Comment[9]["avatar"],"u=") !== false) {
            $New_Comment[9]["avatar"] = "/usfi/avt/".str_replace("u=","",$New_Comment[9]["avatar"]);
        } else {
            $New_Comment[9]["avatar"] = "/usfi/thmp/".$New_Comment["9"]["avatar"];
        }


        if ($New_Comment[9]["by_user"] === $_USER->username || $New_Comment[9]["on_channel"] === $_USER->username) { $New_Comment[9]["can_delete"] = true; } else { $New_Comment[9]["can_delete"] = false; }
        echo json_encode($New_Comment[9]);
    } else {
        echo json_encode(array("new_com" => false, "old_id" => $Comment_ID));
    }
}