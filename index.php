<?php
require_once "_includes/init.php";

if (isset($_POST["b"]) && $_POST["b"] == "1") {
	
	if ($_USER->logged_in) {
	
		$Username = $_USER->username;
		
	} else {
		
		$Username = "";
		
	}

	if ($DB->execute("SELECT ip FROM badboys WHERE ip = :IP AND submit_date > NOW() - INTERVAL 1 MINUTE", false, [":IP" => user_ip()]) == false) {
		
		$DB->modify("INSERT INTO badboys (ip, username, agent) VALUES (:IP, :USERNAME, :AGENT)", [":IP" => user_ip(), ":USERNAME" => $Username, ":AGENT" => $_SERVER['HTTP_USER_AGENT']]);
		
	}
	exit();
}

//LAST USERS ONLINE
if (!isset($_COOKIE["s"]) || !$_USER->logged_in) { $Online_Limit = 5; } else { $Online_Limit = 10; }
$Last_Online = $DB->execute("SELECT users.username, users.displayname, users.videos, users.favorites, users.friends FROM users WHERE users.activated = 1 ORDER BY users.last_login DESC LIMIT $Online_Limit");

//MOST VIEWED OF TODAY
$Most_Viewed            = new Videos($DB, $_USER);
$Most_Viewed->ORDER_BY  = "rand()";
$Most_Viewed->WHERE_C   = " AND videos.featured = 1 AND videos.url <> 'M3O9f9ddh6f' ";
$Most_Viewed->LIMIT     = 1;
$Most_Viewed->Blocked   = false;
$Most_Viewed->Racism    = false;
$Most_Viewed->get();

$Most_Viewed            = $Most_Viewed->fixed();

$Recommended_Channels = $DB->execute("SELECT username, subscribers, displayname, avatar, channel_description, video_views, (SELECT sum(videos_watched.watchtime) FROM videos_watched INNER JOIN videos ON videos.url = videos_watched.vid WHERE videos.uploaded_by = users.username AND users.displayname NOT LIKE '%moonman%' AND videos_watched.submit_date >= DATE_SUB(CURDATE(), INTERVAL 4 DAY)) as watchtime_amount FROM users WHERE shadowbanned = 0 ORDER BY watchtime_amount DESC LIMIT 9");
shuffle($Recommended_Channels);
$Recommended_Channels = array_slice($Recommended_Channels, 0, 3);

$Status     = 2;
$Autoplay   = false;
$URL        = $Most_Viewed["url"];
$FILENAME   = $Most_Viewed["file"];
$ISHD       = $Most_Viewed["hd"] == 1 ? true : false;
$Length     = $Most_Viewed["seconds"];

if (isset($_COOKIE["player"])) {
    $Player = (int)$_COOKIE["player"];
    if ($Player < 0 || $Player > 3) $Player = 2;
} else {
    $Player = 2;
}


if (!isset($_COOKIE["s"]) || !$_USER->logged_in) {
    if ($_USER->logged_in) {
        $Stats = $DB->execute("SELECT users.friends, users.subscribers, users.videos_watched, users.video_views, users.channel_views, users.subscriptions FROM users WHERE users.username = :USERNAME LIMIT 1", true, [":USERNAME" => $_USER->username]);

        if ($Stats["subscriptions"] > 0) {
            $Subscription_Videos                        = new Videos($DB, $_USER);
            $Subscription_Videos->JOIN                  = "INNER JOIN subscriptions ON subscriptions.subscription = videos.uploaded_by";
            $Subscription_Videos->WHERE_P               = ["subscriptions.subscriber" => $_USER->username];
			$Subscription_Videos->WHERE_C				= " AND videos.url <> 'OvQv1MiQN0X' ";
            $Subscription_Videos->LIMIT                 = 8;
            $Subscription_Videos->ORDER_BY              = "videos.uploaded_on DESC";
            $Subscription_Videos->Shadowbanned_Users    = false;
            $Subscription_Videos->get();

            if ($Subscription_Videos::$Videos) {
                $Subscription_Videos = $Subscription_Videos->fixed();
            } else {
                $Subscription_Videos = false;
            }
        }
    }


//MODULES
    if ($_USER->logged_in) {
        if (isset($_POST["save_modules"])) {
            if (isset($_POST["i_subs"])) {
                $i_subs = 1;
            } else {
                $i_subs = 0;
            }
            if (isset($_POST["i_in"])) {
                $i_in = 1;
            } else {
                $i_in = 0;
            }
            if (isset($_POST["i_rec"])) {
                $i_rec = 1;
            } else {
                $i_rec = 0;
            }
            if (isset($_POST["i_stat"])) {
                $i_stat = 1;
            } else {
                $i_stat = 0;
            }
            if (isset($_POST["i_bein"])) {
                $i_bein = 1;
            } else {
                $i_bein = 0;
            }
            if (isset($_POST["i_feat"])) {
                $i_feat = 1;
            } else {
                $i_feat = 0;
            }
            if (isset($_POST["i_pop"])) {
                $i_pop = 1;
            } else {
                $i_pop = 0;
            }

            setcookie("h", "a=$i_subs,b=$i_in,c=$i_rec,d=$i_stat,e=$i_bein,f=$i_feat,g=$i_pop", time() + 60 * 60 * 24 * 128, "/");
            notification("Homepage successfully updated!", "/", "green"); exit();
        }


        if (isset($_COOKIE["h"])) {
            $Modules = explode(",", $_COOKIE["h"]);
            $Modules = array("subscriptions" => str_replace("a=", "", $Modules[0]), "inbox" => str_replace("b=", "", $Modules[1]), "recommended" => str_replace("c=", "", $Modules[2]), "stats" => str_replace("d=", "", $Modules[3]), "being_watched" => str_replace("e=", "", $Modules[4]), "featured" => str_replace("f=", "", $Modules[5]), "most_popular" => str_replace("g=", "", $Modules[6]));
        } else {
            $Modules = array("subscriptions" => true, "recommended" => true, "being_watched" => false, "featured" => true, "most_popular" => true, "inbox" => true, "stats" => false);
        }
    }

    if ($_USER->logged_in) {
        if (isset($_COOKIE["po"])) {
            $Position = $_COOKIE["po"];
            $Position = str_replace("0=", "", str_replace("1=", "", str_replace("2=", "", str_replace("3=", "", str_replace("4=", "", $Position)))));
            $Position = explode(",", $Position);
            $Position = array(0 => $Position[0], 1 => $Position[1], 2 => $Position[2], 3 => $Position[3], 4 => $Position[4]);
        } else {
            $Position = array(0 => "s", 1 => "r", 2 => "b", 3 => "f", 4 => "m");
        }
    } else {
        $Position = array(0 => "r", 1 => "b", 2 => "f", 3 => "m");
    }


    //RECENTLY VIEWED
    if (!$_USER->logged_in || ($_USER->logged_in && $Modules["being_watched"])) {
        $Watched            = new Videos($DB, $_USER);
        $Watched->JOIN      = "INNER JOIN recently_viewed ON videos.url = recently_viewed.url";
        $Watched->ORDER_BY  = "recently_viewed.time_viewed DESC";
        $Watched->Blocked   = false;
        $Watched->LIMIT     = 4;
        $Watched->Racism    = false;
        $Watched->get();

        $Watched = $Watched->fixed();
    }


    //BANNED WORDS
    //Mostly due to adsense
    $Banned = ["cunt", "sex", "porn", "fag"];
    $LIKE   = "";
    foreach ($Banned as $Word) { $LIKE .= " videos.title NOT LIKE '%$Word %' AND videos.tags NOT LIKE '%$Word %' AND videos.description NOT LIKE '%$Word %' AND "; }

//GET POPULAR VIDEOS
    if (!$_USER->logged_in || ($_USER->logged_in && $Modules["most_popular"])) {
        $Today      = date("Y/m/d", strtotime('+1 day'));
        $Categories = ["Film & Animation" => "1", "Autos & Vehicles" => 2, "Music" => "3", "Pets & Animals" => "4", "Sports" => "5", "Travel & Events" => "6", "Gaming" => "7", "People & Blogs" => "8", "Comedy" => "9", "Entertainment" => "10", "News & Politics" => "11", "Howto & Style" => "12", "Education" => "13", "Science & Technology" => "14", "Nonprofits & Activism" => "15"];
        $Keys       = array_keys($Categories);
        shuffle($Keys);

        $Shuffle = [];
        foreach ($Keys as $key)
        {
            $Shuffle[$key] = $Categories[$key];
        }
        $Categories = array_splice($Shuffle,-8);
        unset($Shuffle);
        $Popular        = [];
        $Already_Users  = [];
        foreach ($Categories as $Category => $Num) {
            if (count($Already_Users) > 0) {
                $Users          = sql_IN_fix($Already_Users);
                $NOT_IN         = "videos.uploaded_by NOT IN ($Users) AND";
            } else {
                $NOT_IN         = "";
            }
            $Yesterday          = date("Y/m/d");

            $Query              = new Videos($DB, $_USER);
            $Query->JOIN        = "LEFT JOIN videos_watched ON videos_watched.vid = videos.url";
            $Query->WHERE_C     = " AND $NOT_IN videos.uploaded_on > DATE_SUB(NOW(), INTERVAL 7 HOUR) AND $LIKE videos.category = '".$Num."'";
            $Query->ORDER_BY    = "(videos.views 
                                  - (videos.1_star * 14) 
                                  + (videos.5_star * 9)
                                  + (LEAST(((videos_watched.watchtime) / 60 / 60) * 12, 300))
                                  + (videos.favorites * 4)) 
                                  DESC";
            $Query->Blocked     = false;
            $Query->LIMIT       = 1;
            $Query->get();

            if ($Query::$Videos) {
                $Query                      = $Query->fixed();
                $Popular[$Category]         = $Query;
                $Popular[$Category]["Num"]  = $Num;
                $Already_Users[]            = $Query["uploaded_by"];
            } else {
                $Query = $DB->execute("SELECT url FROM videos WHERE Category = '$Num' LIMIT 1");
                if ($DB->RowNum == 1) {
                    $Date   = 0;
                    $Found  = false;
                    while (!$Found) {
                        $Date++;
                        $Yesterday          = date("Y/m/d", strtotime("-$Date day"));

                        $Query              = new Videos($DB, $_USER);
                        $Query->JOIN        = "LEFT JOIN videos_watched ON videos_watched.vid = videos.url";
                        $Query->WHERE_C     = " AND $NOT_IN videos.uploaded_on <= '$Today' AND videos.category = '".$Num."' AND $LIKE videos.uploaded_on >= '$Yesterday' ";
                        $Query->ORDER_BY    = "(videos.views 
                                              - (videos.1_star * 14) 
                                              + (videos.5_star * 9)
                                              + (LEAST(((videos_watched.watchtime) / 60 / 60) * 12, 200))
                                              + (videos.favorites * 4)) 
                                              DESC";
                        $Query->LIMIT       = 1;
                        $Query->Blocked     = false;

                        $Query->get();
                        if ($Query::$Videos) {
                            $Query                      = $Query->fixed();
                            $Found = true;
                            $Already_Users[]            = $Query["uploaded_by"];
                            $Popular[$Category]         = $Query;
                            $Popular[$Category]["Num"]  = $Num;
                        }
                    }
                }
            }
        }
    }
//RECOMMENDED
    if ((!$_USER->logged_in && count($_USER->Viewed_Videos) >= 6) || ($_USER->logged_in && $Modules["recommended"] && count($_USER->Viewed_Videos) >= 6)) {

        $Viewed_Videos = array_slice($_USER->Viewed_Videos, -6);
        $Viewed_Videos = sql_IN_fix($Viewed_Videos);

        $Viewed_Videos_Titles = $DB->execute("SELECT title FROM videos WHERE url IN ($Viewed_Videos) ORDER BY FIELD(url,$Viewed_Videos)");

        $All_Titles = "";
        foreach ($Viewed_Videos_Titles as $Viewed_Title) {

            $All_Titles .= $Viewed_Title["title"] . " ";

        }

        $All_Titles = array_filter(explode(" ", $All_Titles));

        $Remove_Words = array("part", "episode", "Episode", "-", ":", "Lets", "Part", "PART", "video", "Video", "VIDEO", "the", "The");

        $New_All_Titles = array();

        foreach ($All_Titles as $Title) {

            if (!in_array($Title, $Remove_Words) && ctype_alnum($Title) && !is_numeric($Title) && strlen($Title) >= 3) {

                $New_All_Titles[] = strtolower($Title);

            }

        }

        $All_Titles = array_count_values($New_All_Titles);
        asort($All_Titles);
        $All_Titles = array_slice($All_Titles, -6);

        $New_All_Titles = "";
        $Count = 0;
        $Amount = count($All_Titles);
        foreach ($All_Titles as $Value => $Key) {

            $Count++;
            if ($Count !== $Amount) {

                $New_All_Titles .= $Value . " ";

            } else {

                $Main_Title = $Value;

            }

        }
        $All_Titles = $New_All_Titles;


        $Recommended_Videos              = new Videos($DB, $_USER);
        $Recommended_Videos->SELECT     .= ", (MATCH(videos.title) AGAINST(:MAIN_WORD)) as main_word,
                                              (MATCH(videos.title,videos.description,videos.tags) AGAINST (:OTHERS)) as other_words";

        $Recommended_Videos->ORDER_BY    = "((main_word * 5) + (other_words * 4) + (rand() * 19) + 
                                            ((videos.watched / 60 / 60) * 10) +
                                            (videos.views / 35) + 
                                            (CASE WHEN YEARWEEK(videos.uploaded_on) = YEARWEEK(NOW()) AND videos.views > 100 THEN 28 else 0 end))";

        $Recommended_Videos->WHERE_C     = " AND ((MATCH(videos.title,videos.description,videos.tags) AGAINST(:TITLES)) OR (YEARWEEK(videos.uploaded_on) = YEARWEEK(NOW()) AND videos.views > 100)) AND videos.url NOT IN ($Viewed_Videos)";
        $Recommended_Videos->Execute     = [
                                                ":TITLES"      => $All_Titles . $Main_Title,
                                                ":MAIN_WORD"   => $Main_Title,
                                                ":OTHERS"      => $All_Titles
                                           ];
        $Recommended_Videos->LIMIT       = 8;
        $Recommended_Videos->Blocked     = false;
        $Recommended_Videos->Racism      = false;
        $Recommended_Videos->get();

        $Recommended_Amount = $Recommended_Videos::$Amount;

        if ($Recommended_Videos::$Videos) {

            $Recommended_Videos = $Recommended_Videos->fixed();

        } else {

            $Recommended_Videos = false;

        }

    } else {

        $Recommended_Amount = 0;

    }


//STATS
    if ($_USER->logged_in && $Modules["stats"]) {
        $Stats = $DB->execute("SELECT video_views, channel_views, subscriptions, subscribers, friends FROM users WHERE username = '$_USER->username'", true);
    }


//FEATURED VIDEOS
    if (!$_USER->logged_in || ($_USER->logged_in && $Modules["featured"])) {
        $Featured_Videos             = new Videos($DB, $_USER);
        $Featured_Videos->WHERE_C    = "AND videos.featured = 1";
        $Featured_Videos->ORDER_BY   = "videos.uploaded_on DESC";
        $Featured_Videos->LIMIT      = 4;
        $Featured_Videos->Blocked    = false;
        $Featured_Videos->get();
        $Featured_Videos             = $Featured_Videos->fixed();

    }

    if ($_USER->logged_in) {
        $Achievements           = $DB->execute("SELECT achievement_text.text, achievement_text.amount, achievement_users.type, achievement_users.name, achievement_users.ach_date FROM achievement_users INNER JOIN achievement_text ON achievement_users.name = achievement_text.name WHERE achievement_users.username = '$_USER->username' AND achievement_users.closed = 0");
        $Achievements_Amount    = $DB->RowNum;
    }
} else {

    if (isset($_GET["act"])) {
        if ($_GET["act"] == 1) {
            setcookie( "st", "1",time() + 60 * 60 * 24 * 128 , "/");
        } else {
            setcookie('st', null, -1, '/');
        }
        redirect("/");
    }

    $Stats = $DB->execute("SELECT video_views, channel_views, subscriptions, subscribers, friends, avatar FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username]);
    $Your_Avatar = $Stats["avatar"];

    $Friends        = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscriptions, users.subscribers, users.video_views, users.friends, users.videos_watched, users.last_login FROM users INNER JOIN friends ON friends.friend_1 = users.username OR friends.friend_2 = users.username WHERE (friends.friend_1 = :USERNAME OR friends.friend_2 = :USERNAME) AND friends.status = 1 AND users.username <> :USERNAME", false, [":USERNAME" => $_USER->username]);
    $Friends_Amount = $DB->RowNum;

    $Friends_Array  = [];

    foreach($Friends as $Array) {
        $Friends_Array[] = $Array["username"];
    }

    $Avatar_Array = array();

    foreach ($Friends as $Avatar) {
        $Avatar_Array[$Avatar["username"]] = $Avatar["avatar"];
    }
    $Avatar_Array[$_USER->username] = $Your_Avatar;

    $Subscriptions          = $DB->execute("SELECT subscriptions.subscription as username, users.displayname, users.avatar FROM subscriptions INNER JOIN users ON subscriptions.subscription = users.username WHERE subscriptions.subscriber = :USERNAME", false, [":USERNAME" => $_USER->username]);
    $Subscriptions_Amount   = $DB->RowNum;

    $Subscriptions_Array = array();

    foreach($Subscriptions as $Subscription) {
        $Subscriptions_Array[] = $Subscription["username"];
    }


    $Subscription_Avatar_Array = array();

    foreach ($Subscriptions as $Avatar) {
        $Subscription_Avatar_Array[$Avatar["username"]] = $Avatar["avatar"];
    }

    if ($Subscriptions_Amount > 0 || $Friends_Amount > 0) {
        $SQL_USERS  = sql_IN_fix($Friends_Array);
        $SQL_SUBS   = sql_IN_fix($Subscriptions_Array);
        $SELECT     = "";
        if (!isset($_COOKIE["st"]) && $Friends_Amount > 0) {
            if (!isset($_GET["t"]) || $_GET["t"] == 1) { $SELECT = "SELECT 'bulletin' as type_name, by_user as id, content, date as date, '' as title, 'a' as video_by, 'a' as video_desc FROM bulletins WHERE (by_user IN ($SQL_USERS) OR by_user = '$_USER->username') AND bulletins.content NOT LIKE '%vanillo%' "; }
            if (!isset($_GET["t"]) || $_GET["t"] == 2) {if (!isset($_GET["t"])) { $SELECT .= "UNION ALL"; } $SELECT .= " SELECT 'comment' as type_name, videos.url as id, video_comments.comment as content, video_comments.date_sent as date, videos.title as title, video_comments.by_user as video_by, videos.description as video_desc FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE by_user IN ($SQL_USERS) "; }
            if (!isset($_GET["t"]) || $_GET["t"] == 3) {if (!isset($_GET["t"])) { $SELECT .= "UNION ALL"; } $SELECT .= " SELECT 'favorite' as type_name, videos.url as id, videos.description as content, video_favorites.date as date, videos.title as title, video_favorites.favorite_by as video_by, 'a' as video_desc FROM video_favorites INNER JOIN videos ON video_favorites.url = videos.url WHERE favorite_by IN ($SQL_USERS) ";}
            if (!isset($_GET["t"])) {$SELECT .= "UNION ALL SELECT 'friend' as type_name, friend_1, friend_2, sent_on, '' as title, 'a' as video_by, 'a' as video_desc FROM friends as date WHERE (friend_1 IN ($SQL_USERS) OR friend_2 IN ($SQL_USERS)) AND status = 2 "; }
            if (!isset($_GET["t"])) { $SELECT .= "UNION ALL SELECT 'sub' as type_name, videos.url, '' as comment, videos.uploaded_on as date, videos.title as title, videos.uploaded_by as video_by, videos.description as video_desc FROM videos WHERE videos.uploaded_by IN($SQL_SUBS) AND videos.status = 2 "; }
        } else {
            $SELECT .= "SELECT 'sub' as type_name, videos.url as id, '' as comment, videos.uploaded_on as date, videos.title as title, videos.uploaded_by as video_by, videos.description as video_desc FROM videos WHERE videos.uploaded_by IN($SQL_SUBS) AND videos.status = 2 ";
        }

        $Recent_Activity = $DB->execute("$SELECT ORDER BY date DESC LIMIT 20");

		foreach($Recent_Activity as $i => $ra) {
			if ($ra["type_name"] == "bulletin") {
				$Recent_Activity[$i]["displayname"] = $DB->execute("SELECT displayname FROM users WHERE username = :USER LIMIT 1", true, [":USER" => $ra["id"]])["displayname"];
			} elseif ($ra["type_name"] == "comment" || $ra["type_name"] == "favorite" || $ra["type_name"] == "sub") {
				$Recent_Activity[$i]["displayname"] = $DB->execute("SELECT displayname FROM users WHERE username = :USER LIMIT 1", true, [":USER" => $ra["video_by"]])["displayname"];
			}
		}
    }
}


$_PAGE->set_variables(array(
    "Page_Title"        => "VidLii - Display Yourself",
    "Page"              => "Home",
    "Page_Type"         => "Home"
));
require_once "_templates/page_structure.php";
