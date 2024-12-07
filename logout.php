<?php
    require_once "_includes/init.php";
    if(!$_USER->logged_in) {
        echo "404 Not Found";
        exit();
    }
    $_USER->logout();
    redirect((isset($_GET["next"]) && $_GET["next"] != "") ? "/".$_GET["next"] : previous_page());
?>