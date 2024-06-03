<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

if (isset($_SESSION["beto"])) { exit(); }

if (!empty($_SERVER["HTTP_USER_AGENT"])) {
    $_SESSION["deto"] = 1;
} else {
    $_SESSION["beto"] = 1;
}