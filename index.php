<?php
    require "vendor/autoload.php";
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

    $router = new \Bramus\Router\Router();
    $router->all("/", function() {
        include_once "indexold.php";
    });
    $router->all("/setup", function() {
        if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/.env")) {
            include_once "setup.php";
        } else header("Location: /");
    });
    $router->mount("/vi", function() use($router) {
        $router->get("/ava/(.*).jpg", function($id) {
            $cdn = new \Vidlii\Vidlii\CDN($_SERVER["DOCUMENT_ROOT"]);
            $cdn->avatar($id, $_GET);
        });
        $router->get("/(.*).jpg", function($id) {
            $cdn = new \Vidlii\Vidlii\CDN($_SERVER["DOCUMENT_ROOT"]);
            $cdn->thumbnail($id, $_GET);
        });
    });
    $router->mount("/blog", function() use($router) {
        $router->get("/(\d+)", function($id) {
            $api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);
            require_once "_includes/init.php";

            // Get blog post, according by ID
            $post = $api->db("SELECT * from blog where id = $id");
            if($post["count"] == 1) {
                $post["data"]["date"] = get_date($post["data"]["date"]);
            }

            $_PAGE->set_variables([
                "Page_Title" => "VidLii Blog",
                "Page" => "Blog",
                "Page_Type" => "Home",
                "Show_Search" => true
            ]);
            require_once "_templates/page_structure.php";
        });
        $router->get("/", function() {
            require_once "_includes/init.php";

            // Blog posts
            $sort = (isset($_GET["sort"]) && strtolower($_GET["sort"]) == "old") ? "asc" : "desc";
            $Blog_Posts = $DB->execute("SELECT * FROM blog ORDER BY date $sort");
            foreach($Blog_Posts as $Key => $Post) {
                $Blog_Posts[$Key]["content"] = nl2br($Post["content"]);
                $Blog_Posts[$Key]["date"] = get_date($Post["date"]);
            }

            $_PAGE->set_variables([
                "Page_Title" => "VidLii Blog",
                "Page" => "Blog",
                "Page_Type" => "Home",
                "Show_Search" => true
            ]);
            require_once "_templates/page_structure.php";
        });
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
        $router->get("/", function() {
            require_once "_includes/init.php";
            if($_USER->displayname != null && $_USER->displayname != "") {
                header("Location: /user/".$_USER->displayname);
            } else header("Location: /login?next=user");
        });
    });
    $router->all("/(.*)", function($url) {
        require_once "_includes/init.php";

        $url_chk = strtolower($url);
        $static_page = "static/pages/$url.md";
        $system_page = "$url.php";
        
        if(file_exists($static_page)) {
            $content = file_get_contents($static_page);

            $_PAGE->set_variables(array(
                "Page_Title" => "Static Page - VidLii",
                "Page" => "Static",
                "Content" => $content,
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