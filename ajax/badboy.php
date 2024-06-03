<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_USER->logged_in) {
	
	$Username = $_USER->username;
	
} else {
	
	$Username = "";
	
}
if ($DB->execute("SELECT ip FROM badboys WHERE submit_date > NOW() - INTERVAL 1 MINUTE") == false) {
	
	$DB->modify("INSERT INTO badboys (ip, username, agent) VALUES (:IP, :USERNAME, :AGENT)", [":IP" => user_ip(), ":USERNAME" => $Username, ":AGENT" => $_SERVER['HTTP_USER_AGENT']]);
	
}