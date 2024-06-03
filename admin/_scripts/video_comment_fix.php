<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    echo "Fixing Comments / Messages... Please wait until it redirects you back...";

    $Query = $DB->query("SELECT username FROM users");
    $Users = $Query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($Users as $User) {
        $Username = $User["username"];

        $Comments = $DB->query("SELECT COUNT(*) FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE videos.uploaded_by = '$Username' AND video_comments.seen = 0 AND video_comments.reply_to = 0 AND video_comments.by_user <> '$Username'");
        $Comments_Amount = $Comments->fetchColumn();

        $Mentions = $DB->query("SELECT COUNT(*) FROM mentions INNER JOIN video_comments ON video_comments.id = mentions.video INNER JOIN videos ON video_comments.url = videos.url WHERE mentions.username = '$Username' AND mentions.seen = 0");

        $Replies = $DB->query("SELECT COUNT(*) FROM replies INNER JOIN video_comments ON replies.id = video_comments.id WHERE replies.for_user = '$Username' AND replies.seen = 0");

        $Channel_Comments = $DB->query("SELECT COUNT(*) FROM channel_comments WHERE on_channel = '$Username' AND seen = 0 AND by_user <> '$Username'");

        $Total_Amount = $Comments_Amount + $Mentions->fetchColumn() + $Replies->fetchColumn() + $Channel_Comments->fetchColumn();

        $DB->query("INSERT INTO inbox_amount (username,comments) VALUES('$Username',$Total_Amount) ON DUPLICATE KEY UPDATE comments = $Total_Amount");
    }

    redirect("/admin/misc");