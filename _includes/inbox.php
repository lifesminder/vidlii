<?php
$Invites    = $DB->execute("SELECT count(id) as amount FROM friends WHERE (friend_1 = :USERNAME OR friend_2 = :USERNAME) AND status = 0 AND seen = 0 AND by_user <> :USERNAME ORDER BY rand()", true, [":USERNAME" => $_USER->username]);
$Messages   = $DB->execute("SELECT count(id) as amount FROM private_messages WHERE to_user = :USERNAME AND seen = 0", true, [":USERNAME" => $_USER->username]);
$Responses  = $DB->execute("SELECT count(video_responses.id) as amount FROM video_responses INNER JOIN videos ON video_responses.url_response = videos.url INNER JOIN users ON users.username = videos.uploaded_by WHERE video_responses.accepted = 0 AND video_responses.response_user = :USERNAME AND video_responses.seen = 0 AND video_responses.accepted = 0 ORDER BY rand() DESC", true, [":USERNAME" => $_USER->username]);
$Comments   = $DB->execute("SELECT video_comments.id FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE videos.uploaded_by = :USERNAME AND video_comments.by_user <> :USERNAME AND reply_to = 0 AND video_comments.seen = 0
                                 UNION ALL SELECT mentions.type FROM mentions INNER JOIN video_comments ON video_comments.id = mentions.video INNER JOIN videos ON videos.url = video_comments.url WHERE mentions.username = :USERNAME AND mentions.seen = 0
                                 UNION ALL SELECT mentions.type FROM mentions INNER JOIN channel_comments ON channel_comments.id = mentions.channel WHERE mentions.username = :USERNAME AND mentions.seen = 0
                                 UNION ALL SELECT replies.id FROM replies INNER JOIN video_comments ON video_comments.id = replies.id INNER JOIN videos ON videos.url = video_comments.url WHERE replies.for_user = :USERNAME AND replies.seen = 0
                                 UNION ALL SELECT id FROM channel_comments WHERE on_channel = :USERNAME AND by_user <> :USERNAME AND seen = 0 ORDER BY rand()
                                 ", true, [":USERNAME" => $_USER->username]);


$Inbox_Amounts["messages"]  = $Messages["amount"];
$Inbox_Amounts["comments"]  = $DB->RowNum;
$Inbox_Amounts["invites"]   = $Invites["amount"];
$Inbox_Amounts["responses"] = $Responses["amount"];

$Inbox_Amount = $Inbox_Amounts["messages"] + $Inbox_Amounts["comments"] + $Inbox_Amounts["invites"] + $Inbox_Amounts["responses"];

unset($Invites);
unset($Comments);
unset($Messages);
unset($Responses);