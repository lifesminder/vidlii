<?php
require_once "_includes/init.php";


$Categories     = [0 => "Members", 7 => "Animators", 3 => "Comedians", 1 => "Directors", 4 => "Gamers", 6 => "Gurus", 2 => "Musicians",  5 => "Reporters"];
$Header         = ["mv" => "Most Viewed", "ms" => "Most Subscribed", "mc" => "Most Discussed", "ml" => "Top Rated"];
$_PAGINATION    = new Pagination(24,10);

if (isset($_GET["c"],$_GET["o"],$_GET["t"])) {
    if ($_GET["o"] == "ms" || $_GET["o"] == "mv" || $_GET["o"] == "mc" || $_GET["o"] == "ml") {
        $Current_Order = $_GET["o"];
    } else {
        $Current_Order = "mv";
    }

    if ($_GET["c"] >= 0 && $_GET["c"] <= 9) {
        $Current_Cat = (int)$_GET["c"];
    } else {
        $Current_Cat = 8;
    }

    if ($_GET["t"] > 0 && $_GET["t"] < 3) {
        $Current_time = (int)$_GET["t"];
    } else {
        $Current_time = 0; 
    }
} else {
    $Current_Cat    = 8;
    $Current_Order  = "mv";
    $Current_time   = 0;
}

//ORDER BY
$ORDER_BY = "ORDER BY ";
if ($Current_Order == "mv") {
    $ORDER_BY .= "users.video_views DESC, users.subscribers DESC";
} elseif ($Current_Order == "ms") {
    $ORDER_BY .= "users.subscribers DESC, users.video_views DESC";
} elseif ($Current_Order == "mc" || $Current_Order == "ml") {
    $ORDER_BY .= "sum_order DESC";
}

//CATEGORY
if ($Current_Cat == 8) {
    $WHERE = "WHERE users.banned = '0' AND users.privacy = '0'";
} elseif ($Current_Cat == 9) {
    $WHERE = "WHERE users.partner = 1 AND users.banned = '0' AND users.privacy = '0'";
} else {
    $WHERE = "WHERE users.channel_type = $Current_Cat AND users.banned = '0' AND users.privacy = '0'";
}


if ($Current_Order !== "ml" && $Current_Order != "mc") {

    if ($Current_time == 2) {
        $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos FROM users GROUP BY users.username $ORDER_BY LIMIT $_PAGINATION->From, $_PAGINATION->To");

        $_PAGINATION->Total = 240;
    } else {
        if ($Current_time == 1) {
            if ($Current_Order == "mv") {
                $Time_Type = "most_viewed_week";
            } else {
                $Time_Type = "most_subscribed_week";
            }
        } elseif ($Current_time == 0) {
            if ($Current_Order == "mv") {
                $Time_Type = "most_viewed_month";
            } else {
                $Time_Type = "most_subscribed_month";
            }
        }
        if ($Current_Order == "mv") {
            $Channels           = $DB->execute("SELECT users.username, users.displayname, users.avatar, $Time_Type.amount as video_views, users.videos FROM users INNER JOIN $Time_Type ON $Time_Type.username = users.username $WHERE ORDER BY $Time_Type.amount DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");

            $All_Channels       = $DB->execute("SELECT count(users.username) as amount FROM users INNER JOIN $Time_Type ON $Time_Type.username = users.username $WHERE ORDER BY $Time_Type.amount LIMIT 240", true)["amount"];
            $_PAGINATION->Total = $All_Channels;
        } else {
            $Channels           = $DB->execute("SELECT users.username, users.displayname, users.avatar, $Time_Type.amount as subscribers, users.videos FROM users INNER JOIN $Time_Type ON $Time_Type.username = users.username $WHERE ORDER BY $Time_Type.amount DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");

            $All_Channels       = $DB->execute("SELECT count(users.username) as amount FROM users INNER JOIN $Time_Type ON $Time_Type.username = users.username $WHERE LIMIT 240", true)["amount"];
            $_PAGINATION->Total = $All_Channels;
        }
    }

} else {

    if ($Current_time == 2) {
        
        if ($Current_Order == "ml") {
        
            $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 5 AND videos.uploaded_by = users.username) as sum_order, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 1 AND videos.uploaded_by = users.username) as sum_order2 FROM users $WHERE AND users.videos > 0 ORDER BY ((sum_order - sum_order2) / GREATEST((users.videos / 3), 1)) DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");

        } else {
            
            $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(DISTINCT CONCAT(video_comments.url, ' ', video_comments.by_user)) FROM video_comments INNER JOIN videos ON videos.url = video_comments.url WHERE videos.uploaded_by = users.username) as sum_order FROM users $WHERE AND users.videos > 0 ORDER BY sum_order DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
            
        }

        $_PAGINATION->Total = 240;
        
    } else {
        if ($Current_time == 1) {
            
            if ($Current_Order == "ml") {
            
                $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 5 AND videos.uploaded_by = users.username AND YEARWEEK(video_ratings.submit_date, 1) = YEARWEEK(CURRENT_DATE(), 1)) as sum_order, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 1 AND videos.uploaded_by = users.username AND YEARWEEK(video_ratings.submit_date, 1) = YEARWEEK(CURRENT_DATE(), 1)) as sum_order2 FROM users $WHERE AND users.videos > 0 ORDER BY ((sum_order - sum_order2) / GREATEST((users.videos / 3), 1)) DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
            
            } else {
                
                $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(DISTINCT CONCAT(video_comments.url, ' ', video_comments.by_user)) FROM video_comments INNER JOIN videos ON videos.url = video_comments.url WHERE videos.uploaded_by = users.username AND YEARWEEK(video_comments.date_sent, 1) = YEARWEEK(CURRENT_DATE(), 1)) as sum_order FROM users $WHERE AND users.videos > 0 ORDER BY sum_order DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                
            }
            
            $_PAGINATION->Total = 240;
            
        } elseif ($Current_time == 0) {
            
            if ($Current_Order == "ml") {
            
                $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 5 AND videos.uploaded_by = users.username AND MONTH(video_ratings.submit_date) = MONTH(CURRENT_DATE()) AND YEAR(video_ratings.submit_date) = YEAR(CURRENT_DATE())) as sum_order, (SELECT count(*) FROM video_ratings INNER JOIN videos ON videos.url = video_ratings.url WHERE video_ratings.stars = 1 AND videos.uploaded_by = users.username AND MONTH(video_ratings.submit_date) = MONTH(CURRENT_DATE()) AND YEAR(video_ratings.submit_date) = YEAR(CURRENT_DATE())) as sum_order2 FROM users $WHERE AND users.videos > 0 ORDER BY ((sum_order - sum_order2) / GREATEST((users.videos / 3), 1)) DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
            
            } else {
                
                $Channels = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscribers, users.video_views, users.videos, (SELECT count(DISTINCT CONCAT(video_comments.url, ' ', video_comments.by_user)) FROM video_comments INNER JOIN videos ON videos.url = video_comments.url WHERE videos.uploaded_by = users.username AND MONTH(video_comments.date_sent) = MONTH(CURRENT_DATE()) AND YEAR(video_comments.date_sent) = YEAR(CURRENT_DATE())) as sum_order FROM users $WHERE AND users.videos > 0 ORDER BY sum_order DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
                
            }
            
            $_PAGINATION->Total = 240;
            
        }
    }

}



$_PAGE->set_variables(array(
    "Page_Title"        => "Channels - VidLii",
    "Page"              => "Channels2",
    "Page_Type"         => "Channels"
));
require_once "_templates/page_structure.php";
