<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod) && isset($_SESSION["admin_panel"])) {

        if (isset($_POST["submit_blog"])) {
            $_GUMP->validation_rules(array(
                "blog_title"  => "required|max_len,256",
                "blog_post"   => "required|max_len,50000"
            ));

            $_GUMP->filter_rules(array(
                "blog_title"  => "trim",
                "blog_post"   => "trim"
            ));

            $Validation = $_GUMP->run($_POST);

            if ($Validation) {
                $Title = $Validation["blog_title"];
                $Post   = $Validation["blog_post"];

                $DB->modify("INSERT INTO blog (title,content,date) VALUES (:TITLE,:CONTENT,NOW())",
                           [
                               ":TITLE"     => $Title,
                               ":CONTENT"   => $Post
                           ]);
                if ($DB->RowNum > 0) {
                    notification("Blog Post successfully submitted!","/admin/dashboard","green");
                } else {
                    notification("Something went wrong!","/admin/dashboard","red");
                }
            }
        }

        if (!isset($_POST["search_string"])) {
            $Channel_Comments = $DB->execute("SELECT * FROM channel_comments INNER JOIN users ON channel_comments.by_user = users.username ORDER BY date DESC LIMIT 25");
        } else {
            $Search           = $_POST["search_string"];
            $Channel_Comments = $DB->execute("SELECT * FROM channel_comments INNER JOIN users ON channel_comments.by_user = users.username WHERE comment LIKE '%$Search%' ORDER BY date DESC LIMIT 25");
        }

        $Suggestions            = $DB->execute("SELECT * FROM feature_suggestions INNER JOIN users ON feature_suggestions.from_user = users.username ORDER BY id DESC");

        $Bulletins              = $DB->execute("SELECT * FROM bulletins INNER JOIN users ON users.username = bulletins.by_user ORDER BY date DESC LIMIT 20");

        $Converting             = $DB->execute("SELECT url, uploaded_on, convert_status FROM converting ORDER BY uploaded_on");

        $Total_Users            = $DB->execute("SELECT count(*) as amount FROM users", true)["amount"];

        $Total_Channel1         = $DB->execute("SELECT count(*) as amount FROM users WHERE channel_version = 1", true)["amount"];

        $Total_Partner          = $DB->execute("SELECT count(*) as amount FROM users WHERE partner = 1", true)["amount"];

        $Total_Channel2         = $DB->execute("SELECT count(*) as amount FROM users WHERE channel_version = 2", true)["amount"];
        
        $Total_Channel3         = $DB->execute("SELECT count(*) as amount FROM users WHERE channel_version = 3", true)["amount"];

        $Total_Watched          = $DB->execute("SELECT sum(videos_watched) as amount FROM users", true)["amount"];
        
        $Total_Channel_Views    = $DB->execute("SELECT sum(channel_views) as amount FROM users", true)["amount"];
        
        $Total_Subscriptions    = $DB->execute("SELECT sum(subscriptions) as amount FROM users", true)["amount"];
        
        $Total_Favorites        = $DB->execute("SELECT sum(favorites) as amount FROM users", true)["amount"];

        $Total_Views            = $DB->execute("SELECT sum(displayviews) as amount FROM videos", true)["amount"];

        $Total_Channel_Comments = $DB->execute("SELECT count(*) as amount FROM channel_comments", true)["amount"];
        
        $Total_Watchtime        = $DB->execute("SELECT sum(watched) as amount FROM videos", true)["amount"] / 60;

        $Total_Video_Comments   = $DB->execute("SELECT count(*) as amount FROM video_comments", true)["amount"];

        $Total_Playlists        = $DB->execute("SELECT count(*) as amount FROM playlists", true)["amount"];

        $Total_Videos           = $DB->execute("SELECT count(*) as amount FROM videos", true)["amount"];

        $Flags                  = $DB->execute("SELECT videos.title, videos.uploaded_by, videos_flags.*, users.displayname FROM videos_flags INNER JOIN videos ON videos.url = videos_flags.url INNER JOIN users ON users.username = videos_flags.by_user ORDER BY videos_flags.submit_on DESC LIMIT 32");

        $Page_Title = "Dashboard";
        $Page       = "dashboard";
        require_once "_templates/admin_structure.php";
    } elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
        redirect("/admin/login"); die();
    } else {
        redirect("/");
    }