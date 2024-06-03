<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["type"])
if (!$_USER->logged_in)       { exit(); }
if (!isset($_POST["type"]))   { exit(); }


$Type = $_POST["type"];

$DB->modify("UPDATE achievement_users SET closed = 1 WHERE type = :TYPE AND username = :USERNAME", [":USERNAME" => $_USER->username, ":TYPE" => $Type]);

$Check = $DB->execute("SELECT * FROM achievement_users INNER JOIN achievement_text ON achievement_text.name = achievement_users.name WHERE achievement_users.username = '$_USER->username' AND achievement_users.type <> :TYPE AND achievement_users.closed = 0", true, [":TYPE" => $Type]);
if ($DB->RowNum > 0) {
    if ($Check["type"] == "v") { $Text = "Views"; } else { $Text = "Subscribers"; }
    echo '<div class="whats_new" id="home_congrats" type="'.$Check["type"].'" style="padding-bottom:17px;position:relative"><strong>Congratulations!</strong><br><div style="margin-top:3px">You have reached <b>'. $Check["amount"] .' '.$Text.'</b> on <b>'.date("M d, Y",strtotime($Check["ach_date"])).'</b>!</div><div>'.$Check["text"] .'</div><a href="javascript:void(0)" onclick="close_achievement()" style="position:absolute;right:18px;top:4px;font-size:17px">Close</a></div>';
}