<?php
    require "vendor/autoload.php";

    $router = new \Bramus\Router\Router();
    $router->get("/", function() {
        include_once "indexold.php";
    });
    $router->get("/setup", function() {
        include_once "setup.php";
    });
    $router->get("/(.*)", function($url) {
        require_once "_includes/init.php";

        $url_chk = strtolower($url);
        $static_page = "static/pages/$url.md";
        $system_page = "$url.php";
        
        if(file_exists($static_page)) {
            $_PAGE->set_variables(array(
                "Page_Title" => "Static Page - VidLii",
                "Page" => "Static",
                "Content" => file_get_contents($static_page),
                "Page_Type" => "Static"
            ));
            require_once "_templates/page_structure.php";
        } else {
            if(file_exists($system_page)) include_once $system_page;
            else echo "404 Not Found";
        }
    });
    $router->run();
?>