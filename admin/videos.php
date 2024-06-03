<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod) && isset($_SESSION["admin_panel"])) {

        function video_thumbnail2($URL,$LENGTH,$Width,$Height,$Title = NULL) {
            if (!empty($LENGTH) || $LENGTH == "0") { $Length = seconds_to_time((int)$LENGTH); } else { $Length = $LENGTH; }
            if (file_exists("../usfi/thmp/$URL.jpg")) { $Thumbnail = "../usfi/thmp/$URL.jpg"; } else { $Thumbnail = "/img/no_th.jpg"; }

            return '<div class="th"><div class="th_t">'.$Length.'</div><a href="/watch?v='.$URL.'"><img class="vid_th" src="'.$Thumbnail.'" width="'.$Width.'" height="'.$Height.'"></a></div>';
        }

        if (!isset($_GET["v"])) {
            if (isset($_POST["search_video"])) {
                $URL = url_parameter($_POST["v"], "v");
                if (is_string($URL) && ctype_alnum($URL)) {

                    $DB->execute("SELECT url FROM videos WHERE url = :URL", true, [":URL" => $URL]);

                    if ($DB->RowNum == 1) {
                        redirect("/admin/videos?v=$URL");
                        exit();
                    } else {
                        notification("This video doesn't exist!", "/admin/videos", "red");
                    }
                } else {
                    notification("This isn't a valid URL!", "/admin/videos", "red");
                }
            }

            if (!isset($_GET["ti"]) && !isset($_GET["da"]) && !isset($_GET["vi"]) && !isset($_GET["co"]) && !isset($_GET["ra"])) {
                $ORDER_BY = "ORDER BY uploaded_on DESC";
            } elseif (isset($_GET["ti"])) {
                if ($_GET["ti"] == 0) {
                    $ORDER_BY = "ORDER BY title ASC";
                } else {
                    $ORDER_BY = "ORDER BY title DESC";
                }
            } elseif (isset($_GET["da"])) {
                if ($_GET["da"] == 0) {
                    $ORDER_BY = "ORDER BY uploaded_on ASC";
                } else {
                    $ORDER_BY = "ORDER BY uploaded_on DESC";
                }
            } elseif (isset($_GET["vi"])) {
                if ($_GET["vi"] == 0) {
                    $ORDER_BY = "ORDER BY views ASC";
                } else {
                    $ORDER_BY = "ORDER BY views DESC";
                }
            } elseif (isset($_GET["co"])) {
                if ($_GET["co"] == 0) {
                    $ORDER_BY = "ORDER BY comments ASC";
                } else {
                    $ORDER_BY = "ORDER BY comments DESC" ;
                }
            } elseif (isset($_GET["ra"])) {
                if ($_GET["ra"] == 0) {
                    $ORDER_BY = "ORDER BY (1_star + 2_star + 3_star + 4_star + 5_star) ASC";
                } else {
                    $ORDER_BY = "ORDER BY (1_star + 2_star + 3_star + 4_star + 5_star) DESC";
                }
            } else {
                redirect("/admin/videos"); exit();
            }

            if(isset($_GET["n"]) && $_GET["n"] <= 512) {
                $LIMIT = "LIMIT ".(int)$_GET["n"];
            } else {
                $LIMIT = "LIMIT 16";
            }

            if (isset($_GET["search"]) && mb_strlen($_GET["search"]) <= 128) {
                $WHERE = "WHERE MATCH (title,description,tags) AGAINST (:SEARCH) OR uploaded_by = :SEARCH";
                $EXECUTE = array(":SEARCH" => $_GET["search"]);
            } else {
                $WHERE = "";
                $EXECUTE = array();
            }

            $Videos = $DB->execute("SELECT * FROM videos $WHERE $ORDER_BY $LIMIT", false, $EXECUTE);
            if ($DB->RowNum == 0) { notification("No videos could be found!","/admin/videos"); exit(); }

            $Ratings = $DB->execute("SELECT * FROM video_ratings INNER JOIN videos ON video_ratings.url = videos.url ORDER BY videos.uploaded_on DESC LIMIT 20");

            $Favorites = $DB->execute("SELECT * FROM video_favorites INNER JOIN videos ON video_favorites.url = videos.url ORDER BY video_favorites.date DESC LIMIT 20");

        } else {
            $Video = $DB->execute("SELECT *, users.displayname FROM videos INNER JOIN users ON videos.uploaded_by = users.username WHERE url = :URL", true, [":URL" => $_GET["v"]]);
            if ($DB->RowNum == 1) {
                if (isset($_POST["delete_video"])) {
                    $Video = new Video($Video["url"],$DB);
                    $Video->delete();
                    notification("This video has been successfully deleted!", "/admin/videos", "green");
                }

                $Categories = return_categories();
                $Months = ['January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6, 'July ' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12];
                $Year = date("Y",strtotime($Video["uploaded_on"]));
                $Month = date("m",strtotime($Video["uploaded_on"]));
                $Day = date("d",strtotime($Video["uploaded_on"]));
                $Date   = "$Year-$Month-$Day";

                if (isset($_POST["save_video"])) {
                    $_GUMP->validation_rules(array(
                        "video_title"          => "required|max_len,101|min_len,1",
                        "video_description"    => "max_len,1100",
                        "video_tags"           => "max_len,260",
                        "video_category"       => "required|integer",
                        "featured"             => "required|integer",
                        "day"                  => "is_day",
                        "month"                => "is_month",
                        "year"                 => "is_year"
                    ));

                    $_GUMP->filter_rules(array(
                        "video_title"          => "trim|NoHTML",
                        "video_description"    => "trim|NoHTML",
                        "video_tags"           => "trim|NoHTML",
                        "video_category"       => "trim",
                        "featured"             => "trim",
                        "day"                  => "trim",
                        "month"                => "trim",
                        "year"                 => "trim"
                    ));

                    $Validation = $_GUMP->run($_POST);

                    if ($Validation) {
                        if ($Validation["month"] > 0 && $Validation["month"] <= 9) { $Validation["month"] = "0".$Validation["month"]; }

                        if ($Date == $Validation["year"]."-".$Validation["month"]."-".$Validation["day"]) { $Upload_Date = $Video["uploaded_on"]; } else { $Upload_Date = $Validation["year"]."-".$Validation["month"]."-".$Validation["day"]; }
                        if ($Validation["featured"] == 0 || $Validation["featured"] == 1) { $Featured = $Validation["featured"]; } else { $Featured = 0; }
                        if ($Validation["video_category"] > 0 && $Validation["video_category"] <= 15) {
                            $Category = $Validation["video_category"];
                        } else {
                            $Category = 1;
                        }

                        if ($_USER->Is_Admin) {
                            $Video_Views = $Validation["video_views"];
                            if ($Validation["video_views"] != $Video["views"]) {
                                $New_Views = abs($Validation["video_views"] - $Video["views"]);
                                if ($Validation["video_views"] > $Video["views"]) {
                                    $Action = "+";
                                } else {
                                    $Action = "-";
                                }
                            } else {
                                $New_Views = 0;
                            }
                        } else {
                            $Video_Views = $Video["views"];
                            $New_Views = 0;
                        }

                        if ($_USER->Is_Admin) {
                            $Video_Display_Views = $Validation["displayviews"];
                            if ($Validation["displayviews"] != $Video["displayviews"]) {
                                $New_Display_Views = abs($Validation["displayviews"] - $Video["displayviews"]);
                                if ($Validation["displayviews"] > $Video["displayviews"]) {
                                    $Action2 = "+";
                                } else {
                                    $Action2 = "-";
                                }
                            } else {
                                $New_Display_Views = 0;
                            }
                        } else {
                            $Video_Display_Views = $Video["displayviews"];
                            $New_Display_Views = 0;
                        }

                        $New_Watchtime = $Validation["watchtime"] - $Video["watched"];


                        $DB->modify("UPDATE videos SET watched = watched + $New_Watchtime WHERE url = :URL", [":URL" => $Video["url"]]);
                        $DB->modify("INSERT INTO videos_watched (vid, watchtime, submit_date) VALUES (:URL, $New_Watchtime, NOW()) ON DUPLICATE KEY UPDATE watchtime = watchtime + $New_Watchtime", [":URL" => $Video["url"]]);


                        $DB->modify("UPDATE videos SET title = :TITLE, description = :DESCRIPTION, tags = :TAGS, category = :CATEGORY, views = :VIEWS, displayviews = :DISPLAY_VIEWS, 1_star = :1_STAR, 2_star = :2_STAR, 3_star = :3_STAR, 4_star = :4_STAR, 5_star = :5_STAR, featured = :FEATURED WHERE url = :URL",
                                   [":TITLE" => $Validation["video_title"], ":DESCRIPTION" => $Validation["video_description"], ":TAGS" => $Validation["video_tags"], ":CATEGORY" => $Category, ":VIEWS" => $Video_Views, ":DISPLAY_VIEWS" => $Video_Display_Views, ":1_STAR" => $Validation["1_star"], ":2_STAR" => $Validation["2_star"], ":3_STAR" => $Validation["3_star"], ":4_STAR" => $Validation["4_star"], ":5_STAR" => $Validation["5_star"], ":FEATURED" => $Validation["featured"], ":URL" => $Video["url"]]);
                        if ($DB->RowNum == 1) {
                            if ($New_Display_Views != 0) {
                                $DB->modify("UPDATE users SET video_views = video_views $Action2 $New_Display_Views WHERE username = :USERNAME", [":USERNAME" => $Video["uploaded_by"]]);
                                $DB->modify("INSERT INTO videos_views (vid, views, submit_date, source) VALUES (:VID, $Action2$New_Display_Views, NOW(), :SOURCE) ON DUPLICATE KEY UPDATE views = views $Action2 $New_Display_Views",
                                    [
                                        ":VID"     => $Video["url"],
                                        ":SOURCE"  => "v"
                                    ]);
                                if ($Action2 == "+") {
                                    $DB->modify("INSERT INTO most_viewed_week (username,amount) VALUES(:USERNAME,$New_Display_Views) ON DUPLICATE KEY UPDATE amount = amount + $New_Display_Views", [":USERNAME" => $Video["uploaded_by"]]);
                                    $DB->modify("INSERT INTO most_viewed_month (username,amount) VALUES(:USERNAME,$New_Display_Views) ON DUPLICATE KEY UPDATE amount = amount + $New_Display_Views", [":USERNAME" => $Video["uploaded_by"]]);
                                } elseif ($Action2 == "-") {
                                    $DB->modify("UPDATE most_viewed_week SET amount = GREATEST(amount - $New_Display_Views, 0) WHERE username = :USERNAME", [":USERNAME" => $Video["uploaded_by"]]);
                                    $DB->modify("UPDATE most_viewed_month SET amount = GREATEST(amount - $New_Display_Views, 0) WHERE username = :USERNAME", [":USERNAME" => $Video["uploaded_by"]]);
                                }
                            }

                        }
                        notification("Video successfully updated!","/admin/videos?v=".$Video["url"],"green"); exit();
                    }
                }

            } else {
                notification("This video doesn't exist!", "/admin/videos", "red"); exit();
            }
        }

        $Page_Title = "Videos";
        $Page = "Videos";
        require_once "_templates/admin_structure.php";
    } elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
        redirect("/admin/login"); die();
    } else {
        redirect("/");
    }