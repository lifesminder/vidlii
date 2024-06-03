<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activation
if (!$_USER->logged_in)         { redirect("/login"); exit();   }
if (!$_USER->Is_Activated)    { redirect("/"); exit();        }
if ($DB->execute("SELECT value FROM settings WHERE name = 'uploader'", true)["value"] == 0) { notification("The uploader has been temporarily disabled!","/"); exit(); }


$Videos_Today = $DB->execute("SELECT COUNT(url) as amount FROM videos WHERE uploaded_by = :USERNAME AND uploaded_on > DATE_SUB(now(), INTERVAL 1 DAY)", true, [":USERNAME" => $_USER->username])["amount"];
// TODO Move this to a centralized place, preferably in a database setting row
$Max_Daily_Videos = 10;
if ($Videos_Today >= $Max_Daily_Videos) {
    notification("You cannot upload more than $Max_Daily_Videos videos in a 24-hour period! Sorry about that.","/","red"); exit();
}

$Categories = return_categories();

$_PAGE->set_variables(array(
    "Page_Title"        => "Upload - VidLii",
    "Page"              => "Upload",
    "Page_Type"         => "Videos",
    "Show_Search"       => false
));

require_once "_templates/page_structure.php";
