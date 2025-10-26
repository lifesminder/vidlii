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
    $router->all("/login", function() use($api, $engine) {
        header("Content-Security-Policy: frame-ancestors 'none'");
        header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate");
        header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
        header("Pragma: no-cache");

        $args = []; $session = $api->session();
        if(isset($_GET["next"]) && $_GET["next"] != "") $args["next"] = $_GET["next"];
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if($session["user"]["id"] >= 0) {
                $args["message"] = "You already signed in";
            } else {
                $username = $_POST["username"] ?? "";
                $password = $_POST["password"] ?? "";
                $remember = (isset($_POST["remember"]) && $_POST["remember"] == "on") ? true : false;

                if($username == "" || $password == "") {
                    $args["message"] = "All fields are required";
                } else {
                    $auth = new \Vidlii\Vidlii\API\Auth($_SERVER["DOCUMENT_ROOT"]);
                    $result = $auth->login([
                        "username" => $username,
                        "password" => $password,
                        "remember" => $remember
                    ]);
                    if($result["status"] == 0) {
                        $path = "/";
                        $next = $_GET["next"] ?? "";
                        if($next != "")
                            $path .= $next;
                        header("Location: $path");
                    } else {
                        $args["message"] = $result["message"];
                    }
                }
            }
        } else if($session["user"]["id"] >= 0) {
            header("Location: /");
        }

        $engine->template("nouveau/login.html", $args);
    });
    $router->all("/signup", function() use($api, $engine) {
        $args = [
            "countries" => $engine->countries(),
            "months" => $engine->months()
        ]; $session = $api->session();

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if($session["user"]["id"] >= 0) {
                $args["message"] = "You already registered";
            } else {
                $email = $_POST["email"] ?? "";
                $username = $_POST["username"] ?? "";
                $password = $_POST["password"] ?? "";
                $password2 = $_POST["password2"] ?? "";
                $country = $_POST["country"] ?? "US";
                $birthday = ($_POST["year"] ?? "1999")."-".($_POST["month"] ?? "01")."-".($_POST["day"] ?? "01");
                $accept = (isset($_POST["accept"]) && $_POST["accept"] == "on") ? true : false;

                if($email == "" || $username == "" || $password == "" || $password2 == "") {
                    $args["message"] = "All fields are required";
                } else if(!$accept) {
                    $args["message"] = "You must agree with terms";
                } else if(strtolower($password) != strtolower($password2)) {
                    $args["message"] = "Passwords don't match";
                } else {
                    $birthDate = new DateTime($birthday);
                    $currentDate = new DateTime("today");
                    $interval = $birthDate->diff($currentDate);
                    $age = $interval->y;
                    if($age < 18) {
                        $args["message"] = "You must be 18 years old to register";
                    } else {
                        $auth = new \Vidlii\Vidlii\API\Auth($_SERVER["DOCUMENT_ROOT"]);
                        $result = $auth->register([
                            "username" => $username,
                            "password" => $password,
                            "email" => $email,
                            "country" => $country,
                            "birthday" => $birthday
                        ]);
                        if($result["status"] == 1) {
                            header("Location: /login");
                        } else {
                            $args["message"] = $result["message"] ?? "Cannot register";
                        }
                    }
                }
            }
        } else if($session["user"]["id"] >= 0) {
            header("Location: /");
        }

        $engine->template("nouveau/signup.html", $args);
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
    $router->get("/help/(\d+)", function($helpID) use($api, $engine) {
        $article = $api->db("SELECT * from help where id = $helpID");
        if($article["count"] == 1) {
            $article = $article["data"];
            $parsedown = new \Parsedown();
            $article["content"] = $parsedown->parse($article["content"]);
            $engine->template("help.html", $article);
        } else {
            $feed = new \Vidlii\Vidlii\API\Feed($_SERVER["DOCUMENT_ROOT"]);
            $engine->template("nouveau/error.html", ["featured" => $feed->index(["show" => "featured"])]);
        }
    });
    $router->all("/my_(\w+)", function($action) use($api, $engine) {
        if(file_exists("my_$action.php")) {
            include_once "my_$action.php";
        } else if(file_exists("_templates/nouveau/account/my_$action.html")) {
            $args = []; $session = $api->session();
            if($session["user"]["id"] < 0) {
                header("Location: /login?next=/my_quicklist");
            }
            switch(strtolower($action)) {
                case "quicklist": {
                    $args["videos"] = $api->db("SELECT * from quicklist where user = ".$session["user"]["id"]." order by position asc", true)["data"] ?? [];
                    foreach($args["videos"] as $i => $video) {
                        $video["video"] = $api->db("SELECT url, title, description, tags, uploaded_by, uploaded_on, displayviews, watched, comments, favorites, length from videos where url = \"".$video["video"]."\" and status >= 2")["data"] ?? [];
                        $args["videos"][$i] = $video;
                    }
                    break;
                }
            }
            $engine->template("nouveau/account/my_$action.html", $args);
        } else {
            $feed = new \Vidlii\Vidlii\API\Feed($_SERVER["DOCUMENT_ROOT"]);
            $engine->template("nouveau/error.html", ["featured" => $feed->index(["show" => "featured"])]);
        }
    });
    $router->all("/(.*)", function($url) {
        global $api, $engine;

        $url_chk = strtolower($url); $urls = str_contains($url, "/") ? explode("/", $url) : [$url];
        $static_page = "static/pages/$url.md";
        $system_page = "$url.php";
        $handle_type = 0;

        if($urls[0] == "c") {
            $handle_type = 1;
            unset($urls[0]); $urls = array_values($urls);
            $handle = $api->db("SELECT * from handles where handle = \"".$urls[0]."\" and type = $handle_type");
            if($handle["count"] == 1) {
                $urls[0] = $api->db("SELECT username from users where id = '".$handle["data"]["user"]."'")["data"]["username"];
            }
        } else if($urls[0][0] == "@") {
            $handle_type = 2;
            $urls[0] = substr($urls[0], 1, strlen($urls[0]));
            $handle = $api->db("SELECT * from handles where handle = \"".$urls[0]."\" and type = $handle_type");
            if($handle["count"] == 1) {
                $urls[0] = $api->db("SELECT username from users where id = '".$handle["data"]["user"]."'")["data"]["username"];
            }
        }
        if($handle_type > 0) {
            $profile_page = (bool)$api->db("SELECT count(*) from users where id = '".$handle["data"]["user"]."'")["data"]["count(*)"];
        } else {
            $profile_page = (bool)$api->db("SELECT count(*) from users where username = '".$urls[0]."' or displayname = '".$urls[0]."'")["data"]["count(*)"];
        }

        if($profile_page) {
            $_GET["user"] = $urls[0];
            if((count($urls) > 1))
                $_GET["page"] = $urls[1];
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