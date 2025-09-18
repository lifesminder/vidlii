<?php
    require "vendor/autoload.php";
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

    $router = new \Bramus\Router\Router();
    $api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);
    $engine = new \Vidlii\Vidlii\Engine();

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
        } else {
            $_USER->logout();
            $api->session(null, "logout");
            redirect((isset($_GET["next"]) && $_GET["next"] != "") ? "/".$_GET["next"] : previous_page());
        }
    });
    $router->get("/playlist", function() {
        global $engine, $session;

        if(isset($_GET["p"]) && $_GET["p"] != "") {
            $id = $_GET["p"]; $playlist = [];
            switch($id) {
                case "WL": {
                    if($session["session"] != -1) {
                        echo "Watch later";
                    } else header("Location: /login?next=playlist?pl=WL");
                    break;
                }
                case "liked": {
                    if($session["session"] != -1) {
                        echo "Liked videos";
                    } else header("Location: /login?next=playlist?pl=WL");
                    break;
                }
                default: {
                    $playlist = new \Vidlii\Vidlii\API\Playlist($_SERVER["DOCUMENT_ROOT"]);
                    $playlist_info = $playlist->index(["id" => $id], []);

                    if($playlist_info["status"] == 1) {
                        $playlist_info = $playlist_info["data"];
                        /* echo "<pre>"; print_r($playlist_info); echo "</pre>"; */
                        $engine->template("playlist.html", ["playlist" => $playlist_info]);
                    } else notification("Playlist not found", "/", "red");
                    break;
                }
            }
        } else {
            header("Location: /");
        }
    });

    $router->all("/(.*)", function($url) {
        global $api, $engine;

        $url_chk = strtolower($url); $urls = str_contains($url, "/") ? explode("/", $url) : [$url];
        $static_page = "static/pages/$url.md";
        $system_page = "$url.php";
        $profile_page = (bool)$api->db("SELECT count(*) from users where username = '".$urls[0]."' or displayname = '".$urls[0]."'")["data"]["count(*)"];

        if($profile_page) {
            $_GET["user"] = $urls[0];
            $_GET["page"] = (count($urls) > 1) ? $urls[1] : "";
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

            $engine->template("page.html", ["title" => $title, "content" => $content, "page_type" => "Static"]);
        } else {
            if(file_exists($system_page)) include_once $system_page;
            else {
                $feed = new \Vidlii\Vidlii\API\Feed($_SERVER["DOCUMENT_ROOT"]);
                $engine->template("nouveau/error.html", ["featured" => $feed->index(["show" => "featured"])]);
            }
        }
    });
    $router->run();
?>