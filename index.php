<?php
    require "vendor/autoload.php";
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

    $router = new \Bramus\Router\Router();
    $api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);

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
        $router->get("/cover/(.*).jpg", function($id) {
            $cdn = new \Vidlii\Vidlii\CDN($_SERVER["DOCUMENT_ROOT"]);
            $cdn->cover($id, $_GET);
        });
        $router->get("/(.*)/(.*).jpg", function($id, $type) {
            $cdn = new \Vidlii\Vidlii\CDN($_SERVER["DOCUMENT_ROOT"]); $params = [];
            switch(strtolower($type)) {
                case "maxdefault": $params["percent"] = 1.0; break;
                case "hqdefault": default: $params["percent"] = 0.5; break;
                case "sqdefault": $params["percent"] = 0.25; break;
            }
            $cdn->thumbnail($id, $params);
        });
        $router->get("/(.*).jpg", function($id) {
            $cdn = new \Vidlii\Vidlii\CDN($_SERVER["DOCUMENT_ROOT"]);
            $cdn->thumbnail($id, $_GET);
        });
    });
    $router->mount("/blog", function() use($router) {
        $router->get("/(\d+)", function($id) {
            global $api;
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
        global $api;
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
    $router->get("/logout", function() {
        require_once "_includes/init.php";
        global $api;
        if(!$_USER->logged_in) {
            redirect("/");
        }
        $_USER->logout();
        $api->session(null, "logout");
        redirect((isset($_GET["next"]) && $_GET["next"] != "") ? "/".$_GET["next"] : previous_page());
    });

    // Portations!
    $router->get("/test", function() {
        require_once "_includes/init.php";
        $db = new \Vidlii\Vidlii\DB($_SERVER["DOCUMENT_ROOT"]);

        echo "<pre>";
        print_r($db->query("SHOW CREATE TABLE users"));
        echo "</pre>";
    });
    $router->get("/playlist", function() {
        require_once "_includes/init.php";
        $db = new \Vidlii\Vidlii\DB($_SERVER["DOCUMENT_ROOT"]);

        if(isset($_GET["p"]) && $_GET["p"] != "") {
            $id = $_GET["p"];
            switch($id) {
                case "WL": {
                    echo "Watch later";
                    break;
                }
                case "liked": {
                    echo "Liked videos";
                    break;
                }
                default: {
                    $playlist = new \Vidlii\Vidlii\API\Playlist($_SERVER["DOCUMENT_ROOT"]);
                    $playlist_info = $playlist->index(["id" => $id], []);

                    if($playlist_info["status"] == 1) {
                        $playlist_info = $playlist_info["data"];
                        $twig = true; $title = $playlist_info["title"]." - VidLii"; $page = "nouveau/playlist.html"; $args = ["playlist" => $playlist_info];
                        require_once "_templates/page_structure.php";
                    }
                    break;
                }
            }
        } else {
            header("Location: /");
        }
    });

    $router->all("/(.*)", function($url) {
        require_once "_includes/init.php";

        $url_chk = strtolower($url);
        $static_page = "static/pages/$url.md";
        $system_page = "$url.php";
        $profile_page = (bool)$DB->query("SELECT count(*) from users where username = '".explode("/", $url)[0]."' or displayname = '".explode("/", $url)[0]."'")["data"][0]["count(*)"];
        
        if($profile_page) {
            $_GET["user"] = $url;
            include_once "profile.php";
        } else if(file_exists($static_page)) {
            $content = file_get_contents($static_page);
            $title = preg_split('#\r?\n#', ltrim($content), 2)[0];
            if($title[0] == ';' || $title[0] == '#') {
                if($title[0] == ';') $content = preg_replace('/^.+\n/', '', $content);
                $title = substr($title, 1, strlen($title));
            } else {
                $title = "Static";
            }

            $_PAGE->set_variables(array(
                "Page_Title" => "$title - VidLii",
                "Page" => "Static",
                "Content" => $content,
                "Page_Type" => "Static"
            ));
            require_once "_templates/page_structure.php";
        } else {
            if(file_exists($system_page)) include_once $system_page;
            else {
                $feed = new \Vidlii\Vidlii\API\Feed($_SERVER["DOCUMENT_ROOT"]);

                $twig = true;
                $title = "404 - Page Not Found | VidLii";
                $page = "nouveau/error.html";
                $args["featured"] = $feed->index(["show" => "featured"]);
                require_once "_templates/page_structure.php";
            }
        }
    });
    $router->run();
?>