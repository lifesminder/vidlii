<?php
require_once "_includes/init.php";
$Videos_enabled = $DB->execute("SELECT value FROM settings WHERE name = 'videos'", true)["value"] ?? 1;
if ($Videos_enabled == 0) { notification("The video page has been temporarily disabled!","/"); exit(); }

$_VIDEO = new Video($_GET["v"],$DB);

if ($_VIDEO->exists() !== false) {
    $_VIDEO->get_info();


    if ($_VIDEO->Info["privacy"] == 2 && (!$_USER->logged_in || $_USER->username != $_VIDEO->Info["uploaded_by"])) {

        notification("You cannot watch this private video!", "/videos", "red"); exit();

    } elseif ($_VIDEO->Info["privacy"] == 1) {

        notification("This is an unlisted video!", false, "red");

    }


    $_VIDEO->Info["views"] = $_VIDEO->Info["displayviews"];
	
	//if (!$_USER->logged_in) {
	//	if (strpos(strtolower($_VIDEO->Info["title"]), "butt") !== false ||strpos($_VIDEO->Info["title"], "ELANTICRISTO2007") !== false || strpos(strtolower($_VIDEO->Info["title"]), "grand wizard") !== false || strpos(strtolower($_VIDEO->Info["title"]), "endstufe") !== false || strpos(strtolower($_VIDEO->Info["title"]), "christchurch") !== false || strpos(strtolower($_VIDEO->Info["title"]), "johnny rebel") !== false ||  strpos(strtolower($_VIDEO->Info["tags"]), "rape") !== false || strpos(strtolower($_VIDEO->Info["title"]), "rape") !== false || strpos(strtolower($_VIDEO->Info["tags"]), "hitler") !== false || strpos(strtolower($_VIDEO->Info["tags"]), "nazi") !== false || strpos(strtolower($_VIDEO->Info["title"]), "nazi") !== false || strpos(strtolower($_VIDEO->Info["title"]), "sex") !== false || strpos(strtolower($_VIDEO->Info["title"]), "cum") !== false || strpos(strtolower($_VIDEO->Info["title"]), "moonman") !== false || strpos(strtolower($_VIDEO->Info["title"]), "negro") !== false || strpos(strtolower($_VIDEO->Info["title"]), "nigger") !== false || strpos(strtolower($_VIDEO->Info["title"]), "nigga") !== false || strpos(strtolower($_VIDEO->Info["title"]), "faggot") !== false) {
	//		notification("Deleted!", "/", "red");
	//	}
	//}
	
	
    $URL            = $_VIDEO->Info["url"];
	$FILENAME       = $_VIDEO->Info["file"];
	$ISHD           = $_VIDEO->Info["hd"] == 1 ? true : false;
    $Title          = htmlspecialchars($_VIDEO->Info["title"]);
    $Description    = htmlspecialchars($_VIDEO->Info["description"]);
    $Tags           = explode(",",htmlspecialchars($_VIDEO->Info["tags"]));
    $Category       = (int)$_VIDEO->Info["category"];
    $Views          = number_format($_VIDEO->Info["views"]);
    $Comments_Num   = number_format($_VIDEO->Info["comments"]);
    $Uploaded_By    = $_VIDEO->Info["uploaded_by"];
    $Uploaded_On    = get_date($_VIDEO->Info["uploaded_on"]).", ".get_time($_VIDEO->Info["uploaded_on"]);
    $Total_Ratings  = $_VIDEO->Info["1_star"] + $_VIDEO->Info["2_star"] + $_VIDEO->Info["3_star"] + $_VIDEO->Info["4_star"] + $_VIDEO->Info["5_star"];
    $Length         = $_VIDEO->Info["length"];
    $Status         = $_VIDEO->Info["status"];
    $Show_Ads       = $_VIDEO->Info["show_ads"];

    // Whether HD is enabled by query params (for use with noscript player)
    $HD_Enabled = isset($_GET["hd"]) and $_GET["hd"] == "1";

    $Banned_Words = ["pussy","porno","dick","penis","porn","nigger","sexy","striptease","fuck","sex","vagina","tits","naked","bitch"];

    foreach($Banned_Words as $Banned_Word) {
        if (strpos(strtolower($Title),$Banned_Word) !== false) {
            $Show_Ads = false;
        }
        if (strpos(strtolower($Description),$Banned_Word) !== false) {
            $Show_Ads = false;
        }
        if (strpos(strtolower($_VIDEO->Info["tags"]),$Banned_Word) !== false) {
            $Show_Ads = false;
        }
    }

    $Categories = return_categories();

    $Has_Favorited = $_VIDEO->favorited_by($_USER->username);


    $Uploader = $DB->execute("SELECT avatar, subscribers, partner, adsense, displayname, shadowbanned FROM users WHERE username = :USERNAME", true, [":USERNAME" => $Uploaded_By]);

    if ($Uploader["partner"] == 1 && file_exists("usfi/wbner/$Uploaded_By.png")) {
        $Has_Banner = true;
    } else {
        $Has_Banner = false;
    }


    $_PAGINATION = new Pagination(15,100);

    if ($_VIDEO->Info["comments"] > 0) {
        $Comments_Array = $_VIDEO->comments($_PAGINATION,true);

        if ($_VIDEO->Info["comments"] > 3 && !isset($_GET["p"])) {
            $Top_Comments = $DB->execute("SELECT video_comments.*, users.avatar, users.displayname FROM video_comments INNER JOIN users ON video_comments.by_user = users.username WHERE video_comments.url = :URL AND video_comments.rating > 2 ORDER BY video_comments.rating DESC LIMIT 2", false, [":URL" => $URL]);
            if ($DB->RowNum == 0) {
                $Top_Comments = false;
            }
        } else {
            $Top_Comments = false;
        }
    } else {
        $Top_Comments = false;
    }

    if ($_VIDEO->Info["responses"] > 0) {
        $Responses = $DB->execute("SELECT video_responses.url_response, videos.length, videos.title FROM video_responses INNER JOIN videos ON video_responses.url_response = videos.url WHERE video_responses.url = :URL AND accepted = 1 ORDER BY video_responses.date DESC LIMIT 4", false, [":URL" => $URL]);
    }

    if ($_USER->logged_in) {
        //GET PLAYLISTS
        $Playlists = $DB->execute("SELECT purl, title FROM playlists WHERE created_by = :USERNAME ORDER BY created_on DESC LIMIT 25", false, [":USERNAME" => $_USER->username]);
    }

    //RELATES VIDEOS
    $Related_Videos                 = new Videos($DB, $_USER);
    $Related_Videos->WHERE_C        = " AND MATCH(videos.title, videos.description) AGAINST (:TITLE :DESCRIPTION) AND videos.url <> :URL AND videos.s_related = 1";
    $Related_Videos->Execute        = [":TITLE" => $Title, ":URL" => $URL, ":DESCRIPTION" => $Description];
    $Related_Videos->SELECT        .= ", MATCH(videos.title, videos.description) AGAINST (:TITLE :DESCRIPTION) as rel ";

    $Related_Videos->ORDER_BY       = "((rel * 100) * least(2, greatest(1, videos.views / 20))) DESC"; // This line adds a modifier on the likelihood a video will be shown as a recommendation based on its views. It multiplies the relevance score by (views / 20), but cannot multiply it more than x2. This means that a view must have at least 20 views to gain an advantage, but any more than 40 views will not give extra advantage.
    $Related_Videos->LIMIT          = 13;
    $Related_Videos->Blocked        = false;
    $Related_Videos->Racism         = false;
    $Related_Videos->Banned_Users   = false;
    $Related_Videos->get();

    if ($Related_Videos::$Videos) {

        $Related_Videos = $Related_Videos->fixed();

    } else {

        $Related_Videos = false;

    }


    if ($_USER->logged_in && ($_VIDEO->Info["s_comments"] == 2)) {

        $DB->execute("SELECT id FROM friends WHERE ((friend_1 = :USERNAME AND friend_2 = :UPLOADER) OR (friend_1 = :UPLOADER AND friend_2 = :USERNAME)) AND status = 1", false,
                    [
                        ":USERNAME" => $_USER->username,
                        ":UPLOADER" => $_VIDEO->Info["uploaded_by"]
                    ]);

        if ($DB->RowNum == 1) {

            $Is_Friends = true;

        } else {

             $Is_Friends = false;

        }

    } else {

        $Is_Friends = false;

    }


    $Video_Date = $_VIDEO->Info["uploaded_on"];
    $Single_Response = $DB->execute("SELECT videos.url, videos.title FROM videos INNER JOIN video_responses ON video_responses.url = videos.url WHERE video_responses.url_response = :URL AND DATE(videos.uploaded_on) <= DATE('$Video_Date')", false, [":URL" => $_VIDEO->URL]);

    if ($DB->RowNum == 1) {


        $Single_Response = $Single_Response[0];

    } else {

        unset($Single_Response);

    }


    //OTHER VIDEOS
    $Other_Videos                       = new Videos($DB, $_USER);
    $Other_Videos->WHERE_P              = ["videos.uploaded_by" => $Uploaded_By];
    $Other_Videos->WHERE_C              = " AND videos.url <> :URL";
    $Other_Videos->Execute              = [":URL" => $URL];
    $Other_Videos->ORDER_BY             = "videos.uploaded_on DESC";
    $Other_Videos->LIMIT                = 20;
    $Other_Videos->Shadowbanned_Users   = true;
    $Other_Videos->get();

    if ($Other_Videos::$Videos) {

        $Other_Videos = $Other_Videos->fixed();

    } else {

        $Other_Videos = false;

    }



    $Has_Playlist = false;
    if (isset($_GET["pl"]) && strlen($_GET["pl"]) == 11) {
        $Playlist_Videos = $DB->execute("SELECT *, playlists.title as 'ptitle' FROM playlists INNER JOIN playlists_videos ON playlists.purl = playlists_videos.purl LEFT JOIN videos ON playlists_videos.url = videos.url WHERE playlists.purl = :PURL ORDER BY playlists_videos.position ASC", false, [":PURL" => $_GET["pl"]]);

        if ($DB->RowNum > 1) {
            if (in_array_r($URL, $Playlist_Videos)) {
                //FIND NEXT VIDEO ACCORDING TO POSITION
                foreach($Playlist_Videos as $PLVideo) {
                    if ($PLVideo["url"] == $URL) {
                        $NextVideo = $PLVideo["position"]++;
                    }
                }

                $Has_Playlist = true;
            } else {
                header("location: /watch?v=".$URL); exit();
            }
        }
    }


    if ($_USER->logged_in && $_USER->username === $Uploaded_By) {
        $_USER->Owns_Video = true;
    } else {
        $_USER->Owns_Video = false;
    }
    if ($_USER->logged_in) {
        $Has_Rated_Video   = (float)$_USER->has_rated_video($URL);
        $Is_Subscribed     = $_USER->is_subscribed_to($Uploaded_By);


        //CHECK IF BLOCKED
        $Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = :USERNAME AND blocked = :UPLOADER) OR (blocker = :UPLOADER AND blocked = :USERNAME)", true,
                               [
                                   ":USERNAME" => $_USER->username,
                                   ":UPLOADER" => $Uploaded_By
                               ]);

        if ($DB->RowNum > 0) {
            if ($Blocked["blocker"] == $_USER->username) {
                $Has_Blocked = true;
                $Is_Blocked = false;
            } else {
                $Has_Blocked = false;
                $Is_Blocked = true;
            }
        } else {
            $Has_Blocked = false;
            $Is_Blocked = false;
        }

    } else {
        $Has_Rated_Video = false;
        $Is_Subscribed = false;
        $Has_Blocked = false;
        $Is_Blocked = false;
    }

	if (isset($_COOKIE["player"])) {
		$Player = (int)$_COOKIE["player"];
		if ($Player < 0 || $Player > 3) $Player = 2;
	} else {
		$Player = 2;
	}

    if (isset($_COOKIE["cp2"])) {
        $Player1 = explode(",",$_COOKIE["cp2"]);

        $Autoplay = $Player1[0];
        $Size = $Player1[1];
    } else {
        $Autoplay = 1;
        $Size = 0;
    }

    //VALUES
    if (isset($_COOKIE["cp"])) {
        $Player1 = explode(",",$_COOKIE["cp"]);
    }

} else {
    notification("This video doesn't exist!","/","red"); exit();
}


$_PAGE->set_variables(array(
    "Page_Title"        => "$Title - VidLii",
    "Page"              => "Watch",
    "Page_Type"         => "Videos",
    "Page_Description"  => cut_string($Description,140)
));

require_once "_templates/page_structure.php";
