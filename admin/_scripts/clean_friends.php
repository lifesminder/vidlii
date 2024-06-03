<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
    $Users = $DB->execute("SELECT username FROM users");

    foreach($Users as $User) {
        $User = $User["username"];

        $Friends_Amount = $DB->execute("SELECT COUNT(*) as amount FROM friends WHERE (friend_1 = '$User' OR friend_2 = '$User') AND status = 1", true)["amount"];

        $Invite_Amount = $DB->execute("SELECT COUNT(*) as amount FROM friends WHERE friend_2 = '$User' AND status = 0 AND seen = 0", true)["amount"];

        $DB->modify("UPDATE users SET friends = $Friends_Amount WHERE username = '$User'");
    }