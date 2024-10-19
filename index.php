<?php
    require "vendor/autoload.php";
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

    $router = new \Bramus\Router\Router();
    $router->all("/", function() {
        include_once "indexold.php";
    });
    $router->all("/setup", function() {
        include_once "setup.php";
    });
    $router->all("/api/(.*)", function($query) {
        $api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);
        $api->point($query, $_SERVER['REQUEST_METHOD'], ($_SERVER['REQUEST_METHOD'] == "POST") ? $_POST : $_GET, $_FILES);
    });
    $router->mount("/user", function() use($router) {
        $router->all("/(.*)/(.*)", function($user, $page) {
            $_GET["user"] = $user; $_GET["page"] = $page;
            include_once "profile.php";
        });
        $router->all("/(.*)", function($user) {
            $_GET["user"] = $user;
            include_once "profile.php";
        });
    });
    $router->all("/(.*)", function($url) {
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