<?php
    require "vendor/autoload.php";
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

    use \Vidlii\Vidlii\API;
    use \Vidlii\Vidlii\Engine;
    use \Vidlii\Vidlii\Setup;
    $router = new \Bramus\Router\Router();
    $api = new API($_SERVER["DOCUMENT_ROOT"]);
    $engine = new Engine();

    $router->before("GET|POST", "/(.*)", function() {
        global $engine;
        if($_ENV["setup"] && !str_starts_with(strtolower($_SERVER["REQUEST_URI"]), "/setup")) {
            header("Location: /setup");
        }
    });
    $router->all("/", function() use($api, $engine) {
        require_once "_includes/init.php";
        $message = ""; $messageColor = "";
        if(isset($_COOKIE["old"]) && (bool)$_COOKIE["old"]) {
            include_once "indexold.php";
        } else {
            $session = $api->session();
            $feed = new \Vidlii\Vidlii\API\Feed($_SERVER["DOCUMENT_ROOT"]);
            $feed = $feed->index();
            // selected modules
            if(!empty($_COOKIE["h"])) {
                $modules = explode(",", $_COOKIE["h"]);
                $modules = ["subscriptions" => str_replace("a=", "", $modules[0]), "inbox" => str_replace("b=", "", $modules[1]), "recommended" => str_replace("c=", "", $modules[2]), "stats" => str_replace("d=", "", $modules[3]), "being_watched" => str_replace("e=", "", $modules[4]), "featured" => str_replace("f=", "", $modules[5]), "most_popular" => str_replace("g=", "", $modules[6])];
            } else {
                $modules = ["subscriptions" => true, "recommended" => true, "being_watched" => false, "featured" => true, "most_popular" => true, "inbox" => true, "stats" => false];
            }
            // widgets positions
            if($session["user"]["id"] != -1) {
                if(isset($_POST["save_modules"])) {
                    $i_subs = isset($_POST["i_subs"]) ? 1 : 0;
                    $i_in = isset($_POST["i_in"])   ? 1 : 0;
                    $i_rec = isset($_POST["i_rec"])  ? 1 : 0;
                    $i_stat = isset($_POST["i_stat"]) ? 1 : 0;
                    $i_bein = isset($_POST["i_bein"]) ? 1 : 0;
                    $i_feat = isset($_POST["i_feat"]) ? 1 : 0;
                    $i_pop = isset($_POST["i_pop"])  ? 1 : 0;

                    setcookie("h", "a=$i_subs,b=$i_in,c=$i_rec,d=$i_stat,e=$i_bein,f=$i_feat,g=$i_pop", time() + 60 * 60 * 24 * 128, "/");

                    $modules = [
                        "subscriptions" => (bool)$i_subs,
                        "inbox" => (bool)$i_in,
                        "recommended" => (bool)$i_rec,
                        "stats" => (bool)$i_stat,
                        "being_watched" => (bool)$i_bein,
                        "featured" => (bool)$i_feat,
                        "most_popular" => (bool)$i_pop
                    ];

                    $message = "Homepage successfully updated";
                    $messageColor = "green";
                }
                if(!empty($_COOKIE["po"])) {
                    $position = explode(",", str_replace("0=", "", str_replace("1=", "", str_replace("2=", "", str_replace("3=", "", str_replace("4=", "", $_COOKIE["po"]))))));
                    $position = [0 => $position[0], 1 => $position[1], 2 => $position[2], 3 => $position[3], 4 => $position[4]];
                } else {
                    $position = [0 => "s", 1 => "r", 2 => "b", 3 => "f", 4 => "m"];
                }
            } else {
                $position = [0 => "r", 1 => "b", 2 => "f", 3 => "m"];
            }
            // statistics
            $stats = $api->db("SELECT video_views, channel_views, subscriptions, subscribers, friends FROM users WHERE username = :user", false, ["user" => $session["user"]["displayname"]]);
            $stats = ($stats["count"] == 1) ? $stats["data"] : [];
            if($stats["subscriptions"] > 0) {
                $Subscription_Videos = new Videos($DB, $_USER);
                $Subscription_Videos->JOIN = "INNER JOIN subscriptions ON subscriptions.subscription = videos.uploaded_by";
                $Subscription_Videos->WHERE_P = ["subscriptions.subscriber" => $session["user"]["username"]];
                $Subscription_Videos->WHERE_C = " AND videos.url <> 'OvQv1MiQN0X' ";
                $Subscription_Videos->LIMIT = 8;
                $Subscription_Videos->ORDER_BY = "videos.uploaded_on DESC";
                $Subscription_Videos->Shadowbanned_Users = false;
                $Subscription_Videos->get();

                if ($Subscription_Videos::$Videos) {
                    $Subscription_Videos = $Subscription_Videos->fixed();
                } else {
                    $Subscription_Videos = false;
                }
            }
            // watched videos
            $Watched = new Videos($DB, $_USER);
            $Watched->JOIN = "INNER JOIN recently_viewed ON videos.url = recently_viewed.url";
            $Watched->ORDER_BY = "recently_viewed.time_viewed DESC";
            $Watched->Blocked = false;
            $Watched->LIMIT = 4;
            $Watched->Racism = false;
            $Watched->get();
            $Watched = $Watched->fixed();
            // recommended videos
            if($session["user"]["id"] != -1) {
                $Recommended_Videos = new Videos($DB, $_USER);
                $Recommended_Videos->LIMIT = 8;
                $Recommended_Videos->Blocked = false;
                $Recommended_Videos->Racism = false;
                $Recommended_Videos->get();

                $Recommended_Amount = $Recommended_Videos::$Amount;
                if($Recommended_Videos::$Videos) {
                    $Recommended_Videos = $Recommended_Videos->fixed();
                } else {
                    $Recommended_Videos = false;
                }
            } else {
                $Recommended_Amount = 0;
            }

            $engine->template("nouveau/index.html", [
                "categories" => [
                    1 => "Film & Animation",
                    2 => "Autos & Vehicles",
                    3 => "Music",
                    4 => "Pets & Animals",
                    5 => "Sports",
                    6 => "Travel & Events",
                    7 => "Gaming",
                    8 => "People & Blogs",
                    9 => "Comedy",
                    10 => "Entertainment",
                    11 => "News & Politics",
                    12 => "Howto & Style",
                    13 => "Education",
                    14 => "Science & Technology",
                    15 => "Nonprofits & Activism"
                ],
                "feed" => $feed,
                "modules" => $modules,
                "position" => $position,
                "stats" => [
                    "channel" => $stats,
                    "inbox" => []
                ],
                "watched" => $Watched,
                "recommended" => [
                    "videos" => $Recommended_Videos,
                    "amount" => $Recommended_Amount,
                ],
                "subscriptions" => $Subscription_Videos,
                "message" => $message,
                "messageColor" => $messageColor,
                "page_type" => "Home"
            ]);
        }
    });
    $router->all("/videos", function() {
        require_once "_includes/init.php";

        if(isset($_COOKIE["old"]) && (bool)$_COOKIE["old"]) {
            include_once "videos.php";
        } else {
            $_PAGINATION = new Pagination(16, 20);
            $categories = $engine->categories();
            if(isset($_GET["c"], $_GET["o"], $_GET["t"])) {
                $Current_Order = ($_GET["o"] == "re" || $_GET["o"] == "mv" || $_GET["o"] == "md" || $_GET["o"] == "tr") ? $_GET["o"] : "re";
                $Current_Cat = ($_GET["c"] > 0 && $_GET["c"] < 16) ? (int)$_GET["c"] : 0;
                $Current_time = ($_GET["t"] > 0 && $_GET["t"] < 4)?  (int)$_GET["t"] : 0;
            } else {
                $Current_Cat = 0;
                $Current_Order = "re";
                $Current_time = 2;
            }

            // order
            if($Current_Order == "re") $ORDER_BY = "videos.uploaded_on DESC";
            else if($Current_Order == "mv") $ORDER_BY = "videos.displayviews DESC";
            else if($Current_Order == "tr") $ORDER_BY = "(videos.1_star + videos.2_star * 2 + videos.3_star * 3 + videos.4_star * 4 + videos.5_star * 5) DESC, videos.views DESC";
            // category
            $WHERE = ($Current_Cat == 0) ? " videos.category <> 100 AND videos.url <> 'CndS9berMs3' " : " videos.category = $Current_Cat ";
            // time
            if($Current_time == 0) {
                $WHERE .= " ";
            } else if($Current_time == 1) {
                $WHERE .= " AND YEARWEEK(videos.uploaded_on)=YEARWEEK(NOW()) ";
            } else if($Current_time == 2) {
                $WHERE .= " AND MONTH(videos.uploaded_on) = MONTH(CURDATE()) AND YEAR(videos.uploaded_on) = YEAR(CURDATE()) ";
            } else if($Current_time == 3) {
                $WHERE .= " AND DATE(videos.uploaded_on) = CURDATE() ";
            }

            $Videos = new Videos($DB, $_USER);
            $Videos->Blocked = false;
            $Videos->WHERE_C = " AND $WHERE";
            $Videos->LIMIT = $_PAGINATION;

            if($Current_Order !== "md") {
                $Videos->ORDER_BY = $ORDER_BY;
            } else {
                $Videos->Distinct = true;
                $Videos->JOIN = "INNER JOIN video_comments ON videos.url = video_comments.url";
                $Videos->ORDER_BY = "(SELECT count(DISTINCT video_comments.by_user) as amount FROM video_comments WHERE video_comments.url = videos.url) DESC";
            }

            $Videos->get();
            $Videos = $Videos->fixed();
            $Video_Amount = new Videos($DB, $_USER);
            $Video_Amount->LIMIT = 320;
            $Video_Amount->WHERE_C = " AND $WHERE";
            $Video_Amount->Uploader = true;
            $Video_Amount->Blocked = false;
            $Video_Amount->Count = true;
            $_PAGINATION->Total = $Video_Amount->get();

            $engine->template("nouveau/videos.html", [
                "page_type" => "Videos",
                "filters" => [
                    "re" => "Newest",
                    "mv" => "Most Viewed",
                    "md" => "Most Discussed",
                    "tr" => "Top Rated"
                ],
                "categories" => $categories,
                "category" => $Current_Cat,
                "order" => $Current_Order,
                "time" => $Current_time,
                "videos" => $Videos,
                "pagination" => [
                    "current" => $_PAGINATION->Current_Page ?? 1,
                    "total" => $_PAGINATION->Total,
                ]
            ]);
        }
    });
    $router->all("/channels", function() {
        require_once "_includes/init.php";

        if(isset($_COOKIE["old"]) && (bool)$_COOKIE["old"]) {
            include_once "videos.php";
        } else {
            $categories = $engine->channels();
            $_PAGINATION = new Pagination(24,10);
            if(isset($_GET["c"], $_GET["o"], $_GET["t"])) {
                $Current_Order = ($_GET["o"] == "ms" || $_GET["o"] == "mv" || $_GET["o"] == "mc" || $_GET["o"] == "ml") ? $_GET["o"] : "mv";
                $Current_Cat = ($_GET["c"] >= 0 && $_GET["c"] <= 9) ? (int)$_GET["c"] : 8;
                $Current_time = ($_GET["t"] > 0 && $_GET["t"] < 3)?  (int)$_GET["t"] : 0;
            } else {
                $Current_Cat = 8;
                $Current_Order = "mv";
                $Current_time = 8;
            }
            // order
            $ORDER_BY = "ORDER BY ";
            if($Current_Order == "mv") {
                $ORDER_BY .= "users.video_views DESC, users.subscribers DESC";
            } else if($Current_Order == "ms") {
                $ORDER_BY .= "users.subscribers DESC, users.video_views DESC";
            } else if($Current_Order == "mc" || $Current_Order == "ml") {
                $ORDER_BY .= "sum_order DESC";
            }
            // category
            if ($Current_Cat == 8) {
                $WHERE = "WHERE users.banned = '0' AND users.privacy = '0'";
            } elseif ($Current_Cat == 9) {
                $WHERE = "WHERE users.partner = 1 AND users.banned = '0' AND users.privacy = '0'";
            } else {
                $WHERE = "WHERE users.channel_type = $Current_Cat AND users.banned = '0' AND users.privacy = '0'";
            }

            if ($Current_Order != "ml" && $Current_Order != "mc") {
                if ($Current_time == 2) {
                    $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos FROM users GROUP BY users.username $ORDER_BY LIMIT $_PAGINATION->From, $_PAGINATION->To");
                } else {
                    if ($Current_time == 1) {
                        $Time_Type = ($Current_Order == "mv") ? "most_viewed_week" : "most_subscribed_week";
                    } else if ($Current_time == 0) {
                        $Time_Type = ($Current_Order == "mv") ? "most_viewed_month" : "most_subscribed_month";
                    }

                    if ($Current_Order == "mv") {
                        $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, $Time_Type.amount as video_views, users.videos FROM users INNER JOIN $Time_Type ON $Time_Type.username = users.username $WHERE ORDER BY $Time_Type.amount DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                        $All_Channels = $DB->execute("SELECT count(users.username) as amount FROM users INNER JOIN $Time_Type ON $Time_Type.username = users.username $WHERE ORDER BY $Time_Type.amount LIMIT 240", true)["amount"];
                        $_PAGINATION->Total = $All_Channels;
                    } else {
                        $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, $Time_Type.amount as subscribers, users.videos FROM users INNER JOIN $Time_Type ON $Time_Type.username = users.username $WHERE ORDER BY $Time_Type.amount DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                        $All_Channels = $DB->execute("SELECT count(users.username) as amount FROM users INNER JOIN $Time_Type ON $Time_Type.username = users.username $WHERE LIMIT 240", true)["amount"];
                        $_PAGINATION->Total = $All_Channels;
                    }
                }
            } else if ($Current_time == 2) {
                if ($Current_Order == "ml") {
                    $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 5 AND videos.uploaded_by = users.username) as sum_order, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 1 AND videos.uploaded_by = users.username) as sum_order2 FROM users $WHERE AND users.videos > 0 ORDER BY ((sum_order - sum_order2) / GREATEST((users.videos / 3), 1)) DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                } else {
                    $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(DISTINCT CONCAT(video_comments.url, ' ', video_comments.by_user)) FROM video_comments INNER JOIN videos ON videos.url = video_comments.url WHERE videos.uploaded_by = users.username) as sum_order FROM users $WHERE AND users.videos > 0 ORDER BY sum_order DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                }
            } else {
                if ($Current_time == 1) {
                    if ($Current_Order == "ml") {
                        $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 5 AND videos.uploaded_by = users.username AND YEARWEEK(video_ratings.submit_date, 1) = YEARWEEK(CURRENT_DATE(), 1)) as sum_order, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 1 AND videos.uploaded_by = users.username AND YEARWEEK(video_ratings.submit_date, 1) = YEARWEEK(CURRENT_DATE(), 1)) as sum_order2 FROM users $WHERE AND users.videos > 0 ORDER BY ((sum_order - sum_order2) / GREATEST((users.videos / 3), 1)) DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                    } else {
                        $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(DISTINCT CONCAT(video_comments.url, ' ', video_comments.by_user)) FROM video_comments INNER JOIN videos ON videos.url = video_comments.url WHERE videos.uploaded_by = users.username AND YEARWEEK(video_comments.date_sent, 1) = YEARWEEK(CURRENT_DATE(), 1)) as sum_order FROM users $WHERE AND users.videos > 0 ORDER BY sum_order DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                    }
                } else if ($Current_time == 0) {
                    if ($Current_Order == "ml") {
                        $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 5 AND videos.uploaded_by = users.username AND MONTH(video_ratings.submit_date) = MONTH(CURRENT_DATE()) AND YEAR(video_ratings.submit_date) = YEAR(CURRENT_DATE())) as sum_order, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 1 AND videos.uploaded_by = users.username AND MONTH(video_ratings.submit_date) = MONTH(CURRENT_DATE()) AND YEAR(video_ratings.submit_date) = YEAR(CURRENT_DATE())) as sum_order2 FROM users $WHERE AND users.videos > 0 ORDER BY ((sum_order - sum_order2) / GREATEST((users.videos / 3), 1)) DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                    } else {
                        $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(DISTINCT CONCAT(video_comments.url, ' ', video_comments.by_user)) FROM video_comments INNER JOIN videos ON videos.url = video_comments.url WHERE videos.uploaded_by = users.username AND MONTH(video_comments.date_sent) = MONTH(CURRENT_DATE()) AND YEAR(video_comments.date_sent) = YEAR(CURRENT_DATE())) as sum_order FROM users $WHERE AND users.videos > 0 ORDER BY sum_order DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                    }
                }
            }

            $engine->template("nouveau/channels.html", [
                "page_type" => "Channels",
                "filters" => [
                    "mv" => "Most Viewed",
                    "ms" => "Most Subscribed",
                    "mc" => "Most Discussed",
                    "ml" => "Top Rated"
                ],
                "categories" => $categories,
                "category" => $Current_Cat,
                "order" => $Current_Order,
                "time" => $Current_time,
                "channels" => $Channels,
                "pagination" => [
                    "current" => $_PAGINATION->Current_Page ?? 1,
                    "total" => $_PAGINATION->Total ?? 1,
                ]
            ]);
        }
    });
    $router->all("/community", function() {
        require_once "_includes/init.php";
        if(isset($_COOKIE["old"]) && (bool)$_COOKIE["old"]) {
            include_once "community.php";
        } else {
            global $api;
            $session = $api->session();
            $message = "";

            if (isset($_POST["submit_suggestion"]) && $session["user"]["username"] != -1) {
                $_GUMP->validation_rules([
                    "s_title" => "required|max_len,100",
                    "s_description" => "required|max_len,1100"
                ]);
                $_GUMP->filter_rules([
                    "s_title" => "trim|NoHTML",
                    "s_description" => "trim|NoHTML"
                ]);

                $Validation = $_GUMP->run($_POST);
                if ($Validation) {
                    $Title = $Validation["s_title"];
                    $Description = $Validation["s_description"];
                    $Feature_Exist = $api->db("SELECT id FROM feature_suggestions WHERE from_user = :USERNAME", false, ["USERNAME" => $session["user"]["username"]]);

                    if($Feature_Exist["count"] == 0) {
                        $DB->modify("INSERT INTO feature_suggestions (title,description,from_user) VALUES (:TITLE,:DESCRIPTION,:USERNAME)", false, [
                            ":TITLE" => $Title,
                            ":DESCRIPTION" => $Description,
                            ":USERNAME" => $session["user"]["username"]
                        ]);
                        $message = "Your suggestion has been added! Thank you.";
                        // notification("","/community","green"); exit();
                    } else {
                        $message = "You can only have one suggestion at once!";
                        // notification("You can only have one suggestion at once!","/community"); exit();
                    }
                }
            }

            $Random_Video = new Videos($DB, $_USER);
            $Random_Video->Blocked = false;
            $Random_Video->LIMIT = 1;

            if (!isset($_GET["v"])) {
                $Random_Video->ORDER_BY = "rand()";
            } elseif ($_GET["v"] == "l") {
                $Random_Video->ORDER_BY = "videos.uploaded_on DESC";
            } elseif ($_GET["v"] == "b") {
                $Random_Video->JOIN     = "INNER JOIN recently_viewed ON videos.url = recently_viewed.url";
                $Random_Video->ORDER_BY = "recently_viewed.time_viewed DESC";
            } else {
                redirect("/community");
            }

            $Random_Video->get();
            $Random_Video = $Random_Video->fixed();

            $Status = $Random_Video["status"];
            $URL = $Random_Video["url"];
            $FILENAME = $Random_Video["file"];
            $ISHD = $Random_Video["hd"] == 1 ? true : false;
            $Length = $Random_Video["seconds"];
            $Autoplay = false;

            if (isset($_COOKIE["player"])) {
                $Player = (int)$_COOKIE["player"];
                if ($Player < 0 || $Player > 3) $Player = 2;
            } else {
                $Player = 2;
            }

            // RECENTLY FAVORITED VIDEOS
            $Favorites = new Videos($DB, $_USER);
            $Favorites->ORDER_BY = "video_favorites.date DESC";
            $Favorites->SELECT .= ", video_favorites.date";
            $Favorites->JOIN = "INNER JOIN video_favorites ON videos.url = video_favorites.url";
            $Favorites->LIMIT = 4;
            $Favorites->Blocked = false;
            $Favorites->get();
            $Favorites = $Favorites->fixed();

            // RECENT COMMENTS
            $Comments = $api->db("SELECT video_comments.comment, videos.url, video_comments.by_user, videos.title, users.avatar, users.displayname FROM video_comments INNER JOIN users ON video_comments.by_user = users.username INNER JOIN videos ON video_comments.url = videos.url WHERE videos.privacy = 0 AND videos.status = 2 AND videos.banned_uploader = 0 AND videos.shadowbanned_uploader = 0 ORDER BY video_comments.date_sent DESC LIMIT 5", true);

            // FEATURE REQUESTS
            $Requests = $api->db("SELECT users.avatar, users.displayname, feature_suggestions.title, feature_suggestions.description, feature_suggestions.from_user FROM feature_suggestions INNER JOIN users ON feature_suggestions.from_user = users.username WHERE users.banned = 0 ORDER BY rand() LIMIT 5", true);

            if ($session["session"] != -1) {
                $Your_Request = $api->db("SELECT users.avatar, users.displayname, feature_suggestions.title, feature_suggestions.description, feature_suggestions.from_user FROM feature_suggestions INNER JOIN users ON feature_suggestions.from_user = users.username WHERE feature_suggestions.from_user = :USERNAME", true, ["USERNAME" => $session["user"]["username"]]);
                if ($Your_Request["count"] == 1) { $Has_Requested = true; } else { $Has_Requested = false; }
            } else {
                $Has_Requested = false;
            }

            // CURRENTLY WINNING CONTEST
            $Contest = $api->db("SELECT contest_entries.url, videos.title FROM contest_entries INNER JOIN videos ON contest_entries.url = videos.url ORDER BY votes DESC LIMIT 1", true);

            $engine->template("nouveau/community.html", [
                "page_type" => "Community",
                "get" => $_GET,
                "random" => $Random_Video,
                "favorites" => $Favorites,
                "has_requested" => $Has_Requested,
                "your_request" => $Your_Request,
                "comments" => $Comments["data"] ?? [],
                "requests" => $Requests["data"] ?? [],
                "contenst" => $Contest,
            ]);
        }
    });
    $router->all("/admin/(.*)", function($page) {
        include_once "admin/$page.php";
    });
    $router->mount("/setup", function() use($router) {
        $router->post("/", function() {
            $setup = new Setup();
            $setup->process($_POST);
        });
        $router->get("/", function() {
            $setup = new Setup();
            $setup->show();
        });
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
            global $api, $engine;
            require_once "_includes/init.php";

            // Get blog post, according by ID
            $post = $api->db("SELECT * from blog where id = $id");
            if($post["count"] == 1) {
                $post["data"]["date"] = get_date($post["data"]["date"]);
            }

            $engine->template("nouveau/blog.html", ["post" => $post]);
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

            $engine->template("nouveau/blog.html", ["posts" => $Blog_Posts]);
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
                        $next = $_GET["next"] ?? "";
                        if(trim($next) != "") {
                            header("Location: $next");
                        } else {
                            header("Location: /");
                        }
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
                            $result = $auth->login([
                                "username" => $username,
                                "password" => $password,
                                "remember" => true
                            ]);
                            if($result["status"] == 0) {
                                header("Location: /");
                            } else {
                                $args["message"] = $result["message"];
                            }
                        } else {
                            $args["message"] = $result["message"] ?? "Cannot create your account";
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
                header("Location: /login?next=my_quicklist");
            }
            switch(strtolower($action)) {
                case "quicklist": {
                    $args["videos"] = $api->db("SELECT * from quicklist where user = ".$session["user"]["id"]." order by sequence asc", true);
                    if($args["videos"]["count"] > 0) {
                        foreach($args["videos"]["data"] as $i => $video) {
                            $video["video"] = $api->db("SELECT url, title, description, tags, uploaded_by, uploaded_on, displayviews, watched, comments, favorites, length from videos where url = \"".$video["video"]."\" and status >= 2")["data"] ?? [];
                            $args["videos"]["data"][$i] = $video;
                        }
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
    // RSS feed
    $router->get("/feed/(.*).xml", function(string $user) {
        $rss = new \Vidlii\Vidlii\RSS($_SERVER["DOCUMENT_ROOT"]);
        $rss->show($user);
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