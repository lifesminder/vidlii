<?php
require_once "_includes/init.php";

$Help = $DB->execute("SELECT value FROM settings WHERE name = 'help'", true)["value"];


$_PAGE->set_variables(array(
    "Page_Title"        => "Help - VidLii",
    "Page"              => "Help",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/page_structure.php";