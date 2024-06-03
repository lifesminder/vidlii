<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Logged In
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


$_USER->logout();
redirect(previous_page());