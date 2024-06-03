<?php
require_once "_includes/init.php";


if (isset($_GET["f"]) && $_GET["f"] == "All") { unset($_GET["f"]); }

if (isset($_GET["q"]) && strlen($_GET["q"]) >= 2) {
    $_PAGINATION = new Pagination(15,10);
    $Filtered_Search_Query = str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $_GET["q"]);
    $Normal_Search_Query = trim($_GET["q"]);

    if (isset($_GET["f"])) {
        if ($_GET["f"] == 1) {
            $Page_Type = "Videos";

            $Videos                  = new Videos($DB, $_USER);
            $Videos->SELECT         .= ", MATCH(videos.title) AGAINST (:SEARCH IN BOOLEAN MODE) as title_rel,
                                        MATCH(videos.title, videos.description, videos.tags) AGAINST (:SEARCH IN NATURAL LANGUAGE MODE) as all_rel";
            $Videos->WHERE_C         = " AND (MATCH (videos.title, videos.description, videos.tags) AGAINST (:SEARCH IN BOOLEAN MODE) OR videos.uploaded_by = :SEARCH OR users.displayname = :SEARCH)";
            $Videos->Blocked         = false;
            $Videos->ORDER_BY        = "(0 + (title_rel * 1) 
                                        + (videos.views / 150) 
                                        + (videos.5_star / 50)
                                        + (videos.watched / 60 / 60 / 2) 
                                        - (videos.1_star))
                                        + (CASE WHEN YEARWEEK(videos.uploaded_on) = YEARWEEK(NOW()) THEN 1 else 0 end) DESC";
            $Videos->LIMIT           = $_PAGINATION;
            $Videos->Execute         = [":SEARCH" => $Normal_Search_Query];
            $Videos->get();

            if ($Videos::$Videos) {

                $Videos = $Videos->fixed();

                $Total                  = new Videos($DB, $_USER);
                $Total->Count           = true;
                $Total->Blocked         = false;
                $Total->WHERE_C         = " AND (MATCH (videos.title, videos.description, videos.tags) AGAINST (:SEARCH IN BOOLEAN MODE) OR videos.uploaded_by = :SEARCH OR users.displayname = :SEARCH)";
                $Total->Execute         = [":SEARCH" => $Normal_Search_Query];
                $Total                  = $Total->get();

                $_PAGINATION->Total = $Total;

            } else {

                redirect("/results?q=".urlencode($Normal_Search_Query)."&f=2"); exit();

            }


        } elseif ($_GET["f"] == 2) {
            $Page_Type = "Channels";
            $Channels = $DB->execute("SELECT username, displayname, subscribers, video_views, avatar, channel_views, channel_description FROM users WHERE displayname LIKE :USERNAME AND shadowbanned = 0 LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":USERNAME" => "%$Filtered_Search_Query%"]);

            $Total = $DB->execute("SELECT COUNT(username) AS amount FROM users WHERE shadowbanned = 0 AND displayname LIKE :USERNAME ESCAPE '/'", true, [":USERNAME" => "%$Filtered_Search_Query%"])["amount"];
            if ($Total == 0) {
                notification("No Results!","/"); exit();
            }

            $_PAGINATION->Total = $Total;


        } else {
            redirect("/");
        }


    } else {
        $Page_Type = "Videos";

        $Videos                  = new Videos($DB, $_USER);
        $Videos->SELECT         .= ", MATCH(videos.title) AGAINST (:SEARCH IN BOOLEAN MODE) as title_rel,
                                     MATCH(videos.title, videos.description, videos.tags) AGAINST (:SEARCH IN NATURAL LANGUAGE MODE) as all_rel";
        $Videos->WHERE_C         = " AND (MATCH (videos.title, videos.description, videos.tags) AGAINST (:SEARCH IN BOOLEAN MODE) OR videos.uploaded_by = :SEARCH OR users.displayname = :SEARCH)";
        $Videos->Blocked         = false;
        $Videos->ORDER_BY        = "(0 + (title_rel * 1) 
                                   + (videos.views / 150) 
                                   + (videos.5_star / 50) 
                                   + (videos.watched / 60 / 60 / 2) 
                                   - (videos.1_star))
                                   + (CASE WHEN YEARWEEK(videos.uploaded_on) = YEARWEEK(NOW()) THEN 1 else 0 end) DESC";
        $Videos->LIMIT           = $_PAGINATION;
        $Videos->Execute         = [":SEARCH" => $Normal_Search_Query];
        $Videos->get();

        if ($Videos::$Videos) {

            $Videos = $Videos->fixed();

            $Total                  = new Videos($DB, $_USER);
            $Total->Count           = true;
            $Total->Blocked         = false;
            $Total->WHERE_C         = " AND (MATCH (videos.title, videos.description, videos.tags) AGAINST (:SEARCH IN BOOLEAN MODE) OR videos.uploaded_by = :SEARCH OR users.displayname = :SEARCH)";
            $Total->Execute         = [":SEARCH" => $Normal_Search_Query];
            $Total                  = $Total->get();

            $_PAGINATION->Total = $Total;

        } else {

            redirect("/results?q=".urlencode($Normal_Search_Query)."&f=2"); exit();

        }

        if (!$_USER->logged_in) {

            $User = $DB->execute("SELECT username, displayname, subscribers, video_views, avatar, channel_views, channel_description FROM users WHERE displayname = :USERNAME AND shadowbanned = 0", true, [":USERNAME" => $Normal_Search_Query]);

        } else {

            $User = $DB->execute("SELECT username, displayname, subscribers, video_views, avatar, channel_views, channel_description FROM users LEFT JOIN users_block ON (('$_USER->username' = users_block.blocker AND users.username = users_block.blocked) OR ('$_USER->username' = users_block.blocked AND users.username = users_block.blocker)) WHERE displayname = :USERNAME AND users_block.blocker IS NULL AND shadowbanned = 0", true, [":USERNAME" => $Normal_Search_Query]);

        }

        if ($DB->RowNum == 0) {
            unset($User);
        }
    }

} else {
    notification("You must enter a valid search term!","/"); exit();
}


$_PAGE->set_variables(array(
    "Page_Title"        => "$Normal_Search_Query - VidLii",
    "Page"              => "Search",
    "Page_Type"         => $Page_Type
));
require_once "_templates/page_structure.php";
