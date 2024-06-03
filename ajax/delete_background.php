<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["bg"])
if (!$_USER->logged_in)       { exit(); }
if (!isset($_POST["bg"]))     { exit(); }


$Background = @glob("../usfi/bg/$_USER->username.*")[0];
if ($Background !== false && !empty($Background)) {
    unlink($Background);
}