<?php
require_once "_includes/init.php";


//GET BLOG POSTS
$Blog_Posts = $DB->execute("SELECT * FROM blog ORDER BY date DESC");
foreach($Blog_Posts as $Key => $Post) {
    $Blog_Posts[$Key]["content"]    = nl2br($Post["content"]);
    $Blog_Posts[$Key]["date"]       = get_date($Post["date"]);
}


$_PAGE->set_variables(array(
    "Page_Title"        => "Blog - VidLii",
    "Page"              => "Blog",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/page_structure.php"; 