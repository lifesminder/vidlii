<?php
require_once "_includes/init.php";


if (isset($_GET["t"]) && $_GET["t"] == "h") {
    if (!isset($_COOKIE["hd"])) {
        setcookie( "hd", "1",time() + 60 * 60 * 24 * 128 , "/");
        notification("You're now part of the 'Compact Header' experiment!","/testlii","green");
    } else {
        setcookie('hd', null, -1, '/');
        notification("You're no longer part of this TestLii experiment!","/testlii","green");
    }
}

if (isset($_GET["t"]) && $_GET["t"] == "s") {
    if (!isset($_COOKIE["s"])) {
        setcookie( "s", "1",time() + 60 * 60 * 24 * 128 , "/");
        notification("You're now part of the 'Social Homepage' experiment!","/testlii","green");
    } else {
        setcookie('s', null, -1, '/');
        notification("You're no longer part of this TestLii experiment!","/testlii","green");
    }
}


$_PAGE->set_variables(array(
    "Page_Title"        => "Testlii - VidLii",
    "Page"              => "Testlii",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/page_structure.php";