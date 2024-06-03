<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
//REQUIREMENTS / PERMISSIONS
if (!$_USER->logged_in) {
	exit();
}
if (!$_USER->Is_Mod && !$_USER->Is_Admin) {
	exit();
}
$count = $DB->execute("SELECT COUNT(*) as amount FROM videos_flags WHERE url = :URL", true, [":URL" => $_POST["url"]])["amount"];
if ($count == 0) {
	die("1");
}
$DB->modify("DELETE FROM videos_flags WHERE url = :URL", [":URL" => $_POST["url"]]);
if ($DB->RowNum > 0) {
	die("1");
}
