<?php
require_once "_includes/init.php";


$Users = $DB->execute("SELECT username FROM users");


foreach ($Users as $User) {
    $Username = $User["username"];
    $Video_Views  = $DB->execute("SELECT sum(displayviews) as amount FROM videos WHERE uploaded_by = '$Username'", true)["amount"];

    if (empty($Video_Views)) { $Video_Views = 0; }

    $DB->modify("UPDATE users SET video_views = $Video_Views WHERE username = '$Username'");
}
echo "Done";