<?php
require_once "_includes/init.php";


$Guidelines = $DB->execute("SELECT value FROM settings WHERE name = 'guidelines'", true)["value"];


$_PAGE->set_variables(array(
    "Page_Title"        => "Community Guidelines - VidLii",
    "Page"              => "Guidelines",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/page_structure.php";