<?php
require_once "_includes/init.php";

if (strtoupper($_GET["user"]) == "WUFF") {
	notification("Wuff is working hard, so piss off and let him work!","/","red");
} else {
$profiles_enabled = $DB->execute("SELECT value FROM settings WHERE name = 'channels'", true)["value"] ?? 1;
if ($profiles_enabled == 0) { notification("Channels have been temporarily disabled!","/"); exit(); }


if (isset($_GET["user"])) {
    $Channel_Owner = $DB->execute("SELECT username FROM users WHERE displayname = :USERNAME LIMIT 1", true, [":USERNAME" => $_GET["user"]]);
	$Exist = ($DB->RowNum > 0);
	
	if (!$Exist) {
		$Channel_Owner = $DB->execute("SELECT u.displayname FROM users_oldnames r, users u WHERE r.displayname = :USERNAME AND u.username = r.username LIMIT 1", true, [":USERNAME" => $_GET["user"]]);
		if ($DB->RowNum > 0) {
			header("Location: ".str_replace($_GET["user"], $Channel_Owner["displayname"], $_SERVER[REQUEST_URI]));
			exit;
		}
	}
	
    if ($Exist) {
		$Channel_Owner  = $Channel_Owner["username"];
        $OWNER          = new User($Channel_Owner,$DB);

        $Profile        = $OWNER->get_profile();
        $OWNER_USERNAME = clean($Profile["username"]);


        $Videos_Amount                      = new Videos($DB, $_USER);
        $Videos_Amount->WHERE_P             = ["uploaded_by" => $Profile["username"]];
        $Videos_Amount->ORDER_BY            = "uploaded_on DESC";
        $Videos_Amount->Shadowbanned_Users  = true;
        $Videos_Amount->Count               = true;
        $Profile["videos"]                  = $Videos_Amount->get();


        $Favorites_Amount                     = new Videos($DB, $_USER);
        $Favorites_Amount->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
        $Favorites_Amount->Shadowbanned_Users = true;
        $Favorites_Amount->Banned_Users       = true;
        $Favorites_Amount->Private_Videos     = true;
        $Favorites_Amount->Unlisted_Videos    = true;
        $Favorites_Amount->WHERE_P            = ["video_favorites.favorite_by" => $Profile["username"]];
        $Favorites_Amount->Count              = true;
        $Favorites_Amount->Count_Column       = "video_favorites.url";
        $Profile["favorites"]                 = $Favorites_Amount->get();


        if ($Profile["channel_version"] == 1 && $Profile["banned"] == 0) {
            if ($_USER->logged_in && $_USER->username === $Profile["username"]) {
                $Is_OWNER = true;
                $Is_Subscribed = false;
                $Is_Friends = false;
                $Is_Blocked = false;
                $Has_Blocked = false;

                if (isset($_POST["change_ra"])) {
                    if (isset($_POST["ra_comments"]))   { $RA_COMMENTS  = 1; } else { $RA_COMMENTS = 0; }
                    if (isset($_POST["ra_favorites"]))  { $RA_FAVORITES = 1; } else { $RA_FAVORITES = 0; }
                    if (isset($_POST["ra_friends"]))    { $RA_FRIENDS   = 1; } else { $RA_FRIENDS = 0; }

                    $DB->modify("UPDATE users SET ra_comments = :COMMENTS, ra_favorites = :FAVORITES, ra_friends = :FRIENDS WHERE username = :USERNAME",
                               [
                                   ":COMMENTS"  => $RA_COMMENTS,
                                   ":FAVORITES" => $RA_FAVORITES,
                                   ":FRIENDS"   => $RA_FRIENDS,
                                   ":USERNAME"  => $_USER->username
                               ]);
                    redirect("/user/".$Profile["displayname"]); exit();
                }

            } elseif ($_USER->logged_in) {
                $Is_OWNER = false;

                //CHECK IF FRIENDS
                $Friend_Status = $DB->execute("SELECT status, by_user FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :OWNER) OR (friend_1 = :OWNER AND friend_2 = :USERNAME)", true,
                                             [
                                                  ":USERNAME"    => $_USER->username,
                                                  ":OWNER"       => $Profile["username"]
                                             ]);

                if ($DB->RowNum > 0) {
                    $Status = $Friend_Status["status"];
                    $By     = $Friend_Status["by_user"];
                    if ($Status == 0) {
                        if ($By === $_USER->username) {
                            $Is_Friends = 2;
                        } else {
                            $Is_Friends = 3;
                        }
                    } else {
                        $Is_Friends = true;
                    }
                } else {
                    $Is_Friends = false;
                }

                //CHECK IF BLOCKED
                $Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = '$_USER->username' AND blocked = '$OWNER_USERNAME') OR (blocker = '$OWNER_USERNAME' AND blocked = '$_USER->username')", true);
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

                $Is_Subscribed = $_USER->is_subscribed_to($Profile["username"]);
            } else {
                $Is_OWNER   = false;
                $Is_Friends = false;
                $Is_Subscribed = false;
                $Is_Blocked = false;
                $Has_Blocked = false;
            }

			if ($Profile["privacy"] == 2 && $Is_Friends == false && $_USER->username != $Profile["username"]) {
				notification("You're not allowed to see this page!","/","red"); exit();
			}

			if ($Profile["privacy"] == 1 && (!$_USER->logged_in || $_USER->username != $Profile["username"])) {
                notification("You're not allowed to see this page!","/","red"); exit();
            }


            if ($_GET["page"] == "" || is_numeric($_GET["page"])) {
                $LIMIT_VIDEO = 8;
                $LIMIT_USERS = 6;
                $_PAGINATION = new Pagination(10,10);
            } else {
                if ($_GET["page"] == "videos" || $_GET["page"] == "favorites") {
                    $_PAGINATION = new Pagination(10,50);
                } elseif ($_GET["page"] == "playlists") {
                    $_PAGINATION = new Pagination(8,10);
                } else {
                    $_PAGINATION = new Pagination(20,50);
                }

                $LIMIT_VIDEO = "$_PAGINATION->From,$_PAGINATION->To";
                $LIMIT_USERS = "$_PAGINATION->From,$_PAGINATION->To";
                $LIMIT_COMMENTS = "$_PAGINATION->From,$_PAGINATION->To";
            }


            if ($Profile["subscribers"] > 0 and $Profile["c_subscriber"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "subscribers")) {
                $_PAGINATION->Total = $Profile["subscribers"];
                $Subscribers        = $DB->execute("SELECT users.displayname as subscriber, users.avatar FROM subscriptions INNER JOIN users ON subscriptions.subscriber = users.username WHERE subscriptions.subscription = :OWNER LIMIT $LIMIT_USERS", false, [":OWNER" => $Profile["username"]]);
            }

            if ($Profile["subscriptions"] > 0 and $Profile["c_subscription"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "subscriptions")) {
                $_PAGINATION->Total = $Profile["subscriptions"];
                $Subscriptions      = $DB->execute("SELECT users.displayname as subscription, users.avatar FROM subscriptions INNER JOIN users ON subscriptions.subscription = users.username WHERE subscriptions.subscriber = :OWNER LIMIT $LIMIT_USERS", false, [":OWNER" => $Profile["username"]]);

                if ($_USER->logged_in && $Profile["subscriptions"] > 0 && !$Is_OWNER) {
                    $DB->execute("SELECT subscriber FROM subscriptions WHERE subscriber = :USERNAME AND subscription = :YOU", false,
                                [
                                    ":USERNAME" => $Profile["username"],
                                    ":YOU"      => $_USER->username
                                ]);
                    if ($DB->RowNum > 0) {
                        $Has_Subscribed = true;
                    } else {
                        $Has_Subscribed = false;
                    }
                } else {
                    $Has_Subscribed = false;
                }
            } else {
                $Has_Subscribed = false;
            }

            if ($Profile["videos"] > 0 and $Profile["c_videos"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "videos")) {
                $_PAGINATION->Total = $Profile["videos"];
                if ($Profile["c_featured"]) {
                    $Featured_Video = $DB->execute("SELECT url, file, hd, title, displayviews as views, length, uploaded_by, comments, status, '$Profile[displayname]' as displayname FROM videos WHERE uploaded_by = :OWNER AND status = 2 AND privacy = 0 AND banned_uploader = 0 ORDER BY uploaded_on DESC LIMIT 1", true, [":OWNER" => $Profile["username"]]);
                }

                if ($Profile["c_videos"]) {
                    $Videos                     = new Videos($DB, $_USER);
                    $Videos->WHERE_P            = ["uploaded_by" => $Profile["username"]];
                    $Videos->ORDER_BY           = "uploaded_on DESC";
                    $Videos->Shadowbanned_Users = true;
                    $Videos->LIMIT              = $LIMIT_VIDEO;
                    $Videos->get();

                    if ($Videos::$Videos) {

                        $Videos = $Videos->fixed();

                    } else {

                        $Videos = [];

                    }
                }
                if (isset($_POST["search_input"]) && !empty($_POST["search"]) && mb_strlen($_POST["search"]) >= 3 && mb_strlen($_POST["search"]) <= 64) {

                    $Videos                     = new Videos($DB, $_USER);
                    $Videos->WHERE_C            = " AND MATCH(title) AGAINST (:SEARCH) AND uploaded_by = :OWNER";
                    $Videos->Execute            = [":OWNER"  => $Profile["username"], ":SEARCH" => $_POST["search"]];
                    $Videos->ORDER_BY           = "uploaded_on DESC";
                    $Videos->Shadowbanned_Users = true;
                    $Videos->LIMIT              = 10000;
                    $Videos->get();

                    if ($Videos::$Videos) {
                        $Videos = $Videos->fixed();
                    } else {
                        redirect("/user/".$Profile["displayname"]."/videos"); exit();
                    }
                }
            }

            if ($Profile["favorites"] > 0 and $Profile["c_favorites"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "favorites")) {
                $Favorites                     = new Videos($DB, $_USER);
                $Favorites->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
                $Favorites->Shadowbanned_Users = true;
                $Favorites->Banned_Users       = true;
                $Favorites->Private_Videos     = true;
                $Favorites->Unlisted_Videos    = true;
                $Favorites->ORDER_BY           = "video_favorites.date DESC";
                $Favorites->WHERE_P            = ["video_favorites.favorite_by" => $Profile["username"]];
                $Favorites->LIMIT              = $LIMIT_VIDEO;
                $Favorites->get();


                if ($Favorites::$Videos) {

                    $Favorites = $Favorites->fixed();

                    $_PAGINATION->Total = $Profile["favorites"];

                }

                if (!isset($Featured_Video) && !empty($Favorites[0]["title"])) {
                    $Featured_Video = $Favorites[0];
                }



                if (isset($_POST["search_input"]) && !empty($_POST["search"]) && mb_strlen($_POST["search"]) >= 3 && mb_strlen($_POST["search"]) <= 64) {
                    $Favorites                     = new Videos($DB, $_USER);
                    $Favorites->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
                    $Favorites->Shadowbanned_Users = true;
                    $Favorites->Banned_Users       = true;
                    $Favorites->Private_Videos     = true;
                    $Favorites->Unlisted_Videos    = true;
                    $Favorites->Execute            = [":OWNER"   => $Profile["username"], ":SEARCH"  => $_POST["search"]];
                    $Favorites->WHERE_C            = " AND MATCH(videos.title) AGAINST (:SEARCH) AND video_favorites.favorite_by = :OWNER";
                    $Favorites->LIMIT              = $LIMIT_VIDEO;
                    $Favorites->get();

                    if ($Favorites::$Videos) {
                        $Favorites = $Favorites->fixed();
                    } else {
                        redirect("/user/".$Profile["displayname"]."/favorites"); exit();
                    }
                }
            }


            if ($_USER->logged_in && isset($_POST["save_custom"])) {
                $BBCODE = str_ireplace("vanillo", "", $_POST["bbcode"]);

                if (mb_strlen($BBCODE) <= 1024) {
                    $DB->modify("UPDATE users SET custom = :CUSTOM WHERE username = :USERNAME",
                               [
                                   ":CUSTOM"    => $BBCODE,
                                   ":USERNAME"  => $_USER->username
                               ]);
                    redirect("/user/$_USER->username"); exit();
                }
            }


            if ($Profile["c_featured"] && ((!empty($Profile["featured_n_url"])) || ($Is_Subscribed && !empty($Profile["featured_s_url"])))) {
                if (!$Is_Subscribed && !empty($Profile["featured_n_url"])) {
                    $F_URL = $Profile["featured_n_url"];
                    $Featured_Video_Find = $DB->execute("SELECT v.url, v.file, v.hd, v.title, v.displayviews as views, v.length, v.uploaded_by, v.comments, v.status, u.displayname FROM videos v, users u WHERE v.url = :URL AND v.privacy = 0 AND v.status = 2 AND u.username = v.uploaded_by", true, [":URL" => $F_URL]);
                    if ($DB->RowNum == 1) {
                        $Featured_Video = $Featured_Video_Find;
                    }
                } elseif ($Is_Subscribed && !empty($Profile["featured_s_url"])) {
                    $F_URL = $Profile["featured_s_url"];
                    $Featured_Video_Find = $DB->execute("SELECT v.url, v.file, v.hd, v.title, v.displayviews as views, v.length, v.uploaded_by, v.comments, v.status, u.displayname FROM videos v, users u WHERE v.url = :URL AND v.privacy = 0 AND v.status = 2 AND u.username = v.uploaded_by", true, [":URL" => $F_URL]);
                    if ($DB->RowNum == 1) {
                        $Featured_Video = $Featured_Video_Find;
                    }
                }
            }


            if (isset($_GET["page"])) {
                $_GET["p"] = (int)$_GET["page"];
            } else {
                $_GET["p"] = 1;
            }


            if ($Profile["channel_comments"] > 0 and $Profile["c_comments"]) {
                $Comment_Pagination = new Pagination(10,100,$_GET["p"]);
                $Comments           = $DB->execute("SELECT users.username, users.avatar, users.displayname, channel_comments.date, channel_comments.id, channel_comments.comment FROM users INNER JOIN channel_comments ON users.username = channel_comments.by_user WHERE channel_comments.on_channel = :OWNER ORDER BY channel_comments.date DESC LIMIT $Comment_Pagination->From,$Comment_Pagination->To", false, [":OWNER" => $Profile["username"]]);
            }

            if ($Profile["friends"] > 0 and $Profile["c_friend"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "friends")) {
                $_PAGINATION->Total = $Profile["friends"];
                $Friends            = $DB->execute("SELECT users.displayname, users.avatar FROM users INNER JOIN friends ON friends.friend_1 = users.username OR friends.friend_2 = users.username WHERE (friends.friend_1 = :USERNAME OR friends.friend_2 = :USERNAME) AND friends.status = 1 AND users.username <> :USERNAME LIMIT $LIMIT_USERS", false, [":USERNAME" => $Profile["username"]]);
            }


            if ($Is_OWNER) {
                $All_Playlists = $DB->execute("SELECT purl, title FROM playlists WHERE created_by = :USERNAME ORDER BY created_on DESC", false, [":USERNAME" => $_USER->username]);
            }

            if (!empty($Profile["playlists"]) or $_GET["page"] == "playlists") {
                if (empty($_GET["page"]) or is_numeric($_GET["page"])) {
                    $Playlists_IN = sql_IN_fix(explode(",", $Profile["playlists"]));
                    $Playlists = $DB->execute("SELECT * FROM playlists WHERE purl IN ($Playlists_IN) ORDER BY FIELD(purl,$Playlists_IN)");
                    $Playlists_Amount = $DB->RowNum;
                } else {
                    $Playlists_Num = $DB->execute("SELECT purl FROM playlists WHERE created_by = :OWNER ORDER BY created_on DESC", false, [":OWNER" => $Profile["username"]]);
                    if ($_GET["page"] == "playlists") {
                        $_PAGINATION->Total = $DB->RowNum;

                        $Playlists = $DB->execute("SELECT p.*, '$Profile[displayname]' as displayname FROM playlists p WHERE p.created_by = :OWNER ORDER BY p.created_on DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":OWNER" => $Profile["username"]]);
                    }
                }
            }

            if ($Profile["partner"] !== 2) {
                function custombb($text) {
                    // BBcode array
                    $find = array(
                        '~\[b\](.*?)\[/b\]~s',
                        '~\[i\](.*?)\[/i\]~s',
                        '~\[u\](.*?)\[/u\]~s',
                        '~\[marquee\](.*?)\[/marquee\]~s',
                        '~\[left\](.*?)\[/left\]~s',
                        '~\[center\](.*?)\[/center\]~s',
                        '~\[right\](.*?)\[/right\]~s',
                        '~\[quote\](.*?)\[/quote\]~s',
                        '~\[size=(.*?)\](.*?)\[/size\]~s',
                        '~\[color=(.*?)\](.*?)\[/color\]~s',
                        '~\[url=(.*?)\](.*?)\[/url\]~s',
                        '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
                        '~\[blink=(.*?)\](.*?)\[/blink\]~s',
                        '~\[twitter=(.*?)\](.*?)\[/twitter\]~s',
                    );
                    // HTML tags to replace BBcode
                    $replace = array(
                        '<b>$1</b>',
                        '<i>$1</i>',
                        '<span style="text-decoration:underline;">$1</span>',
                        '<marquee>$1</marquee>',
                        '<div style="text-align:left">$1</div>',
                        '<div style="text-align:center">$1</div>',
                        '<div style="text-align:right">$1</div>',
                        '<pre>$1</'.'pre>',
                        '<span style="font-size:$1px;">$2</span>',
                        '<span style="color:$1;">$2</span>',
                        '<a href="$1" rel="nofollow" target="_blank">$2</a>',
                        '<img src="/displayimage?url=$1" style="width:200px" alt="" />',
                        '<span style="animation: blinker $1s linear infinite;">$2</span>',
                        '<a class="twitter-timeline" data-height="600" data-dnt="true" href="https://twitter.com/$1">Tweets by $1</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>'
                    );
                    return preg_replace($find, $replace, $text);
                }

            }


            $_USER->view_channel($Profile["username"]);

            $Channel_Type       = $_USER->channel_type($Profile["channel_type"]);
            $Channel_Type_Icon  = $Channel_Type[1];
            $Channel_Type       = $Channel_Type[0];


            $Background = @glob("usfi/bg/".$Profile["username"].".*")[0];
            if ($Background === NULL) {
                $Has_Background = false;
            } else {
                $Background = "/".$Background."?".$Profile["bg_version"];
                $Has_Background = true;
            }


            $HightLight_Num = round(100 - $Profile["h_trans"]);

            if ($HightLight_Num > 10) {
                $HightLight_Trans = "0.".$HightLight_Num;
            } else {
                $HightLight_Trans = "0.0".$HightLight_Num;
            }

            if ($HightLight_Trans == "0.100") {
                $HightLight_Trans = "1";
            }


            $Normal_Num = round(100 - $Profile["n_trans"]);

            if ($Normal_Num > 10) {
                $Normal_Trans = "0.".$Normal_Num;
            } else {
                $Normal_Trans = "0.0".$Normal_Num;
            }

            if ($Normal_Trans == "0.100") {
                $Normal_Trans = "1";
            }


            if ($Profile["c_featured_channels"] == "1" && !empty($Profile["featured_channels"])) {
                $Featured_Channels_Array = sql_IN_fix(explode(",",$Profile["featured_channels"]));
                if (strpos($Featured_Channels_Array,",") !== false) { $ORDER = "ORDER BY FIELD($Featured_Channels_Array)"; } else { $ORDER = ""; }
                $Featured_Channels = $DB->execute("SELECT username, displayname, channel_description, subscribers, videos, video_views, avatar FROM users WHERE username IN ($Featured_Channels_Array) ORDER BY FIELD(username,$Featured_Channels_Array)");
            }


            //RECENT ACTIVITY
            if ($Profile["c_recent"] == "1") {
                $SELECT = "SELECT 'bulletin' as type_name, id, content, date as date, '' as title FROM bulletins WHERE by_user = :OWNER ";
                if ($Profile["ra_comments"] == 1)   { $SELECT .= "UNION ALL SELECT 'comment' as type_name, videos.url, video_comments.comment, video_comments.date_sent as date, videos.title as title FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE by_user = :OWNER AND videos.status = 2 AND videos.privacy = 0 AND videos.banned_uploader = 0 "; }
                if ($Profile["ra_favorites"] == 1)  { $SELECT .= "UNION ALL SELECT 'favorite' as type_name, videos.url, videos.description as comment, video_favorites.date as date, videos.title as title FROM video_favorites INNER JOIN videos ON video_favorites.url = videos.url WHERE favorite_by = :OWNER AND videos.status = 2 AND videos.privacy = 0 AND videos.banned_uploader = 0 "; }
                if ($Profile["ra_friends"] == 1)    { $SELECT .= "UNION ALL SELECT 'friend' as type_name, friend_1, friend_2, sent_on, '' as title FROM friends as date WHERE (friend_1 = :OWNER OR friend_2 = :OWNER) AND status = 1"; }

                $Recent_Activity = $DB->execute("$SELECT ORDER BY date DESC LIMIT 5", false, [":OWNER" => $Profile["username"]]);
				
				for ($i=0; $i < count($Recent_Activity); $i++) {
					if ($Recent_Activity[$i]["type_name"] == "friend") {
                        $username1 = $DB->execute("SELECT displayname FROM users WHERE username='".$Recent_Activity[$i]["id"]."' LIMIT 1", true)["displayname"];
                        $username2 = $DB->execute("SELECT displayname FROM users WHERE username='".$Recent_Activity[$i]["content"]."' LIMIT 1", true)["displayname"];
						
						$Recent_Activity[$i]["id"]      = $username1;
						$Recent_Activity[$i]["content"] = $username2;
					}
				}
            }
			
			//CHANNEL BANNER
			$Banner_Links = false;
			if ($Profile["partner"] !== "2") {
				if (file_exists("usfi/bner/$Profile[username].png")) {
					$Banner_Links = $DB->execute("SELECT links FROM channel_banners WHERE username='$Profile[username]' LIMIT 1", true);
					if ($DB->RowNum > 0) {
						$Banner_Links = json_decode($Banner_Links["links"], true);
						$Banner_Image = $Profile["username"];
					} else {
						$Banner_Links = false;
					}
                    $Banner_Version = $Profile["banner_version"];
				}
			}

            //LOAD PAGE
            if (!is_numeric($_GET["page"]) && !empty($_GET["page"])) {
                switch ($_GET["page"]) {
                    case "" :
                        $Page_File = "Main";
                        break;
                    case "videos" :
                        $Page_File = "Videos";
                        if ($Profile["videos"] < 1 or !$Profile["c_videos"]) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    case "favorites" :
                        $Page_File = "Favorites";
                        if ($Profile["favorites"] < 1 or !$Profile["c_favorites"]) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    case "subscribers" :
                        $Page_File = "Subscribers";
                        if ($Profile["subscribers"] < 1 or !$Profile["c_subscriber"]) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    case "subscriptions" :
                        $Page_File = "Subscriptions";
                        if ($Profile["subscriptions"] < 1 or !$Profile["c_subscription"]) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    case "friends" :
                        $Page_File = "Friends";
                        if ($Profile["friends"] < 1 or !$Profile["c_comments"]) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    case "playlists" :
                        $Page_File = "Playlists";
                        if (!$Profile["c_playlists"] or $_PAGINATION->Total == 0) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    default :
                        redirect("/user/" . $Profile["displayname"]);
                }
            } else {
                $Page_File = "Main";
            }



            //GET META DESCRIPTION
            if (!empty($Profile["channel_description"])) {
                $Page_Description = htmlspecialchars(preg_replace( "/\r|\n/", "", limit_text($Profile["channel_description"],160)));
            } else {
                $Page_Description = "";
            }

            if (!empty($Profile["channel_tags"])) {
                $Page_Keywords = htmlspecialchars(preg_replace( "/\r|\n/", "", limit_text($Profile["channel_tags"],100)));
            } else {
                $Page_Keywords = "";
            }


            if (strpos($Profile["avatar"],"u=") !== false) {
                $Avatar = str_replace("u=","",$Profile["avatar"]);
                $Folder = "avt";
            } elseif (!empty($Profile["avatar"])) {
                $Avatar = $Profile["avatar"];
                $Upload = false;
                $Folder = "thmp";
            } else {
                $Upload = false;
                $Folder = "";
            }

            if (empty($Folder)) {
                $Avatar = "/img/no.png";
            } else {
                $Avatar = "/usfi/$Folder/$Avatar.jpg";
            }

            $Channel_Types = array(
                0 => "",
                1 => "Director",
                2 => "Musician",
                3 => "Comedian",
                4 => "Gamer",
                5 => "Reporter",
                6 => "Guru",
                7 => "Animator"
            );


            $Awards = $OWNER->get_rankings($Profile["channel_type"]);


            $Page_Title = $Profile["displayname"]." - VidLii";

            //VALUES
            if (isset($_COOKIE["cp"])) {
                $Player1 = explode(",",$_COOKIE["cp"]);
            }

			if (isset($_COOKIE["player"])) {
				$Player = (int)$_COOKIE["player"];
				if ($Player < 0 || $Player > 3) $Player = 2;
			} else {
				$Player = 2;
			}

            require_once "_templates/profile_structure.php";




        } elseif ($Profile["channel_version"] == 2 && $Profile["banned"] == 0) {
            //CHANNEL 2.0

            if ($_USER->logged_in && $_USER->username === $Profile["username"]) {

                $Is_OWNER = true;
                $Is_Subscribed = false;
                $Is_Friends = false;
                $Is_Blocked = false;
                $Has_Blocked = false;


                if (isset($_POST["save_settings"])) {
                    $_GUMP->validation_rules(array(
                        "channel_title"          => "max_len,80",
                        "channel_tags"           => "max_len,270"
                    ));

                    $_GUMP->filter_rules(array(
                        "channel_title"         => "trim|NoHTML",
                        "channel_tags"          => "trim|NoHTML"
                    ));

                    $Validation = $_GUMP->run($_POST);

                    if ($Validation) {
                        $Channel_Title       = $Validation["channel_title"];
                        $Channel_Tags        = $Validation["channel_tags"];
                        $Type                = (int)$_POST["channel_type"];
                        if ($Type >= 0 && $Type <= 7) {
                            $DB->modify("UPDATE users SET channel_type = '$Type', channel_title = :TITLE, channel_tags = :TAGS WHERE username = :USERNAME",
                                       [
                                           ":TITLE"     => $Channel_Title,
                                           ":TAGS"      => $Channel_Tags,
                                           ":USERNAME"  => $_USER->username
                                       ]);
                            redirect("/user/".$_USER->displayname); exit();
                        }
                    }
                }


            } elseif ($_USER->logged_in) {
                $Is_OWNER = false;

                //CHECK IF FRIENDS
                $Friend_Status = $DB->execute("SELECT status, by_user FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :OWNER) OR (friend_1 = :OWNER AND friend_2 = :USERNAME)", true,
                                             [
                                                 ":USERNAME"    => $_USER->username,
                                                 ":OWNER"       => $Profile["username"]
                                             ]);

                if ($DB->RowNum > 0) {
                    $Status = $Friend_Status["status"];
                    $By     = $Friend_Status["by_user"];
                    if ($Status == 0) {
                        if ($By === $_USER->username) {
                            $Is_Friends = 2;
                        } else {
                            $Is_Friends = 3;
                        }
                    } else {
                        $Is_Friends = true;
                    }
                } else {
                    $Is_Friends = false;
                }


                //CHECK IF BLOCKED
                $Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = '$_USER->username' AND blocked = '$OWNER_USERNAME') OR (blocker = '$OWNER_USERNAME' AND blocked = '$_USER->username')", true);
                if ($DB->RowNum > 0) {
                    if ($Blocked["blocker"] == $_USER->username) {
                        $Has_Blocked    = true;
                        $Is_Blocked     = false;
                    } else {
                        $Has_Blocked    = false;
                        $Is_Blocked     = true;
                    }
                } else {
                    $Has_Blocked    = false;
                    $Is_Blocked     = false;
                }


                $Is_Subscribed = $_USER->is_subscribed_to($Profile["username"]);
            } else {
                $Is_OWNER   = false;
                $Is_Friends = false;
                $Is_Subscribed = false;
                $Is_Blocked = false;
                $Has_Blocked = false;
            }

            if ($Is_OWNER || ($Profile["a_country"] && $Profile["a_country"] == 1)) {
                $Countries = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];
            }

            $Channel_Types = [
                0 => "",
                1 => "Director",
                2 => "Musician",
                3 => "Comedian",
                4 => "Gamer",
                5 => "Reporter",
                6 => "Guru",
                7 => "Animator"
            ];


            if ($Profile["privacy"] == 2 && $Is_Friends == false && $_USER->username != $Profile["username"]) {
                notification("You're not allowed to see this page!","/","red"); exit();
            }

            if ($Profile["privacy"] == 1 && (!$_USER->logged_in || $_USER->username != $Profile["username"])) {
                notification("You're not allowed to see this page!","/","red"); exit();
            }


            $Awards = $OWNER->get_rankings($Profile["channel_type"]);

            $HightLight_Num = round(100 - $Profile["h_trans"]);

            if ($HightLight_Num > 10) {
                $HightLight_Trans = "0.".$HightLight_Num;
            } else {
                $HightLight_Trans = "0.0".$HightLight_Num;
            }

            if ($HightLight_Trans == "0.100") {
                $HightLight_Trans = "1";
            }


            $Normal_Num = round(100 - $Profile["n_trans"]);

            if ($Normal_Num > 10) {
                $Normal_Trans = "0.".$Normal_Num;
            } else {
                $Normal_Trans = "0.0".$Normal_Num;
            }

            if ($Normal_Trans == "0.100") {
                $Normal_Trans = "1";
            }




            //CHECK IF USER HAS ANY VIDEOS TO SHOWCASE
            $Showcase = false;

            if ($Profile["c_featured"] && (($Profile["c_videos"] && $Profile["videos"] > 0) or ($Profile["c_favorites"] && $Profile["favorites"] > 0) or ($Profile["c_all"] && $Profile["videos"] > 0 && $Profile["favorites"] > 0))) {
                $Featured_Video = $DB->execute("SELECT url, file, hd, length FROM videos WHERE uploaded_by = :OWNER AND status = 2 AND privacy = 0 AND banned_uploader = 0 ORDER BY uploaded_on DESC LIMIT 1", true, [":OWNER" => $Profile["username"]]);
                if ($DB->RowNum > 0) {
                    $Showcase = true;
                } else {
                    unset($Featured_Video);
                }
            }


            if ($Profile["c_playlists"] == 1) {
                if (!$Profile["c_all"]) { $LIMIT = 16; } else { $LIMIT = 3; }
                $Playlists = $DB->execute("SELECT p.*, '$Profile[displayname]' as displayname FROM playlists p WHERE p.created_by = :OWNER", false, [":OWNER" => $Profile["username"]]);
                if ($DB->RowNum > 0) {
                    $Playlist_Amount = $DB->RowNum;
                    if ($LIMIT = 3) { $Playlists = array_slice($Playlists,0,3); }
                    $Shows_Playlists = true;
                    $Showcase = true;
                    if (!isset($Featured_Video)) {
                        $Playlists_Videos = $DB->execute("SELECT videos.url, videos.file, videos.hd, videos.length FROM playlists INNER JOIN playlists_videos ON playlists_videos.purl = playlists.purl INNER JOIN videos ON playlists_videos.url = videos.url WHERE created_by = :OWNER AND videos.status = 2 AND videos.privacy = 0 AND videos.banned_uploader = 0 ORDER BY playlists_videos.position LIMIT 1", false, [":OWNER" => $Profile["username"]]);
                        if (isset($Playlists_Videos[0])) {
                            $Featured_Video = $Playlists_Videos[0];
                        } else {
                            $Showcase = false;
                        }
                    }
                } else {
                    $Showcase        = false;
                    $Shows_Playlists = false;
                    $Playlist_Amount = 0;
                }
            } else {
                $Showcase       = false;
                $Shows_Playlists = false;
                $Playlist_Amount = 1;
            }


            if ($Profile["videos"] > 0 && $Profile["c_videos"] == 1) {
                if ((($Profile["favorites"] == 0 || !$Profile["c_favorites"]) && (!$Profile["c_playlists"] || $Playlist_Amount == 0)) || (!$Profile["c_all"])) { $LIMIT = 16; } else { $LIMIT = 3; }

                $Videos                     = new Videos($DB, $_USER);
                $Videos->WHERE_P            = ["videos.uploaded_by" => $Profile["username"]];
                $Videos->LIMIT              = $LIMIT;
                $Videos->Shadowbanned_Users = true;
                $Videos->ORDER_BY           = "videos.uploaded_on DESC";
                $Videos->get();

                if ($Videos::$Videos) {

                    if ($Videos::$Amount < $Profile["videos"]) { $Show_More = '<center><button id="show_more" onclick="show_more(\'videos\',\'1\')">Show More</button></center>'; } else { $Show_More = ""; }
                    $Videos = $Videos->fixed();
                    if ($Showcase == false) {
                        $Featured_Video = $Videos[0];
                        $Showcase = true;
                    }
                    $Shows_Videos = true;

                }
            } else {
                if ($Shows_Playlists == false) { $Showcase = false; }
                $Shows_Videos = false;
            }



            $Autoplay = false;

            if ($Profile["favorites"] > 0 && $Profile["c_favorites"] == 1) {
                if ((($Profile["videos"] == 0 || !$Profile["c_videos"]) && (!$Profile["c_playlists"])) || (!$Profile["c_all"])) { $LIMIT = 16; } else { $LIMIT = 3; }

                $Favorites = new Videos($DB, $_USER);
                $Favorites->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
                $Favorites->Shadowbanned_Users = true;
                $Favorites->Banned_Users       = true;
                $Favorites->Private_Videos     = true;
                $Favorites->Unlisted_Videos    = true;
                $Favorites->ORDER_BY           = "video_favorites.date DESC";
                $Favorites->WHERE_P            = ["video_favorites.favorite_by" => $Profile["username"]];
                $Favorites->LIMIT              = $LIMIT;
                $Favorites->get();

                if ($Favorites::$Videos) {

                    if (!isset($Show_More) && $Favorites::$Amount < $Profile["favorites"]) { $Show_More = '<center><button id="show_more" onclick="show_more(\'favorites\',\'1\')">Show More</button></center>'; } elseif (!isset($Show_More)) { $Show_More = ""; }
                    $Favorites = $Favorites->fixed();
                    if ($Showcase == false or ($Shows_Playlists == true && isset($Featured_Video) && $Shows_Videos == false)) {

                        $Featured_Video = $Favorites[0];
                        $Showcase       = true;

                    }
                    $Shows_Favorites = true;

                }
            } else {
                if ($Shows_Playlists == false && $Shows_Videos == false) { $Showcase = false; }
                $Shows_Favorites = false;
            }


            if ($Showcase && !$Profile["c_featured"] && ((!empty($Profile["featured_n_url"])) || ($Is_Subscribed && !empty($Profile["featured_s_url"])))) {
                if (!$Is_Subscribed && !empty($Profile["featured_n_url"])) {
                    $F_URL = $Profile["featured_n_url"];
                    $Featured_Video_Find = $DB->execute("SELECT v.url, v.file, v.hd, v.title, v.displayviews as views, v.length, v.uploaded_by, v.comments, v.status, u.displayname FROM videos v, users u WHERE v.url = :URL AND v.privacy = 0 AND v.status = 2 AND u.username = v.uploaded_by", true, [":URL" => $F_URL]);
                    if ($DB->RowNum == 1) {
                        $Featured_Video = $Featured_Video_Find;
                    }
                } elseif ($Is_Subscribed && !empty($Profile["featured_s_url"])) {
                    $F_URL = $Profile["featured_s_url"];
                    $Featured_Video_Find = $DB->execute("SELECT v.url, v.file, v.hd, v.title, v.displayviews as views, v.length, v.uploaded_by, v.comments, v.status, u.displayname FROM videos v, users u WHERE v.url = :URL AND v.privacy = 0 AND v.status = 2 AND u.username = v.uploaded_by", true, [":URL" => $F_URL]);
                    if ($DB->RowNum == 1) {
                        $Featured_Video = $Featured_Video_Find;
                    }
                }
            }


            if ($_GET["page"] == "" || is_numeric($_GET["page"])) {
                $LIMIT_USERS = 6;
                $_PAGINATION = new Pagination(10,10);
            } else {
                $_PAGINATION = new Pagination(20,100);
                $LIMIT_USERS = "$_PAGINATION->From,$_PAGINATION->To";
            }


            if ($Profile["partner"] !== "2") {
                function custombb($text) {
                    // BBcode array
                    $find = array(
                        '~\[b\](.*?)\[/b\]~s',
                        '~\[i\](.*?)\[/i\]~s',
                        '~\[u\](.*?)\[/u\]~s',
                        '~\[marquee\](.*?)\[/marquee\]~s',
                        '~\[left\](.*?)\[/left\]~s',
                        '~\[center\](.*?)\[/center\]~s',
                        '~\[right\](.*?)\[/right\]~s',
                        '~\[quote\](.*?)\[/quote\]~s',
                        '~\[size=(.*?)\](.*?)\[/size\]~s',
                        '~\[color=(.*?)\](.*?)\[/color\]~s',
                        '~\[url=(.*?)\](.*?)\[/url\]~s',
                        '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
                        '~\[blink=(.*?)\](.*?)\[/blink\]~s',
                        '~\[twitter=(.*?)\](.*?)\[/twitter\]~s',

                    );
                    // HTML tags to replace BBcode
                    $replace = array(
                        '<b>$1</b>',
                        '<i>$1</i>',
                        '<span style="text-decoration:underline;">$1</span>',
                        '<marquee>$1</marquee>',
                        '<div style="text-align:left">$1</div>',
                        '<div style="text-align:center">$1</div>',
                        '<div style="text-align:right">$1</div>',
                        '<pre>$1</'.'pre>',
                        '<span style="font-size:$1px;">$2</span>',
                        '<span style="color:$1;">$2</span>',
                        '<a href="$1" rel="nofollow" target="_blank">$2</a>',
                        '<img src="/displayimage?url=$1" style="width:200px" alt="" />',
                        '<span style="animation: blinker $1s linear infinite;">$2</span>',
                        '<a class="twitter-timeline" data-height="600" data-dnt="true" href="https://twitter.com/$1">Tweets by $1</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>'
                    );
                    return preg_replace($find,$replace,$text);
                }

            }

            if ($_USER->logged_in && isset($_POST["save_custom"])) {
                $BBCODE = str_ireplace("vanillo", "", $_POST["bbcode"]);
                
                if (mb_strlen($BBCODE) <= 1024) {
                    $DB->modify("UPDATE users SET custom = :CUSTOM WHERE username = :USERNAME",
                               [
                                   ":CUSTOM"    => $BBCODE,
                                   ":USERNAME"  => $_USER->username
                               ]);
                    redirect("/user/$_USER->username"); exit();
                }
            }


            if ($Profile["subscribers"] > 0 and $Profile["c_subscriber"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "subscribers")) {
                $_PAGINATION->Total = $Profile["subscribers"];
                $Subscribers        = $DB->execute("SELECT users.displayname as subscriber, users.avatar FROM subscriptions INNER JOIN users ON subscriptions.subscriber = users.username WHERE subscriptions.subscription = :OWNER LIMIT $LIMIT_USERS", false, [":OWNER" => $Profile["username"]]);
            }

            if ($Profile["subscriptions"] > 0 and $Profile["c_subscription"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "subscriptions")) {
                $_PAGINATION->Total = $Profile["subscriptions"];
                $Subscriptions      = $DB->execute("SELECT users.displayname as subscription, users.avatar FROM subscriptions INNER JOIN users ON subscriptions.subscription = users.username WHERE subscriptions.subscriber = :OWNER LIMIT $LIMIT_USERS", false, [":OWNER" => $Profile["username"]]);

                if ($_USER->logged_in && $Profile["subscriptions"] > 0 && !$Is_OWNER) {
                    $DB->execute("SELECT subscriber FROM subscriptions WHERE subscriber = :USERNAME AND subscription = :YOU", false,
                                [
                                    ":USERNAME"   => $Profile["username"],
                                    ":YOU"        => $_USER->username
                                ]);
                    if ($DB->RowNum > 0) {
                        $Has_Subscribed = true;
                    } else {
                        $Has_Subscribed = false;
                    }
                } else {
                    $Has_Subscribed = false;
                }
            } else {
                $Has_Subscribed = false;
            }

            if ($Profile["friends"] > 0 and $Profile["c_friend"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "friends")) {
                $_PAGINATION->Total = $Profile["friends"];
                $Friends = $DB->execute("SELECT users.displayname, users.avatar FROM users INNER JOIN friends ON friends.friend_1 = users.username OR friends.friend_2 = users.username WHERE (friends.friend_1 = :USERNAME OR friends.friend_2 = :USERNAME) AND friends.status = 1 AND users.username <> :USERNAME LIMIT $LIMIT_USERS", false, [":USERNAME" => $Profile["username"]]);
            }


            if (isset($_GET["page"])) {
                $_GET["p"] = (int)$_GET["page"];
            } else {
                $_GET["p"] = 1;
            }

            $Comment_Pagination = new Pagination(10,500,$_GET["p"]);
            $Comment_Pagination->Total = $Profile["channel_comments"];
            if ($Profile["channel_comments"] > 0 and $Profile["c_comments"]) {
                $Comments = $DB->execute("SELECT users.username, users.displayname, users.avatar, channel_comments.date, channel_comments.id, channel_comments.comment FROM users INNER JOIN channel_comments ON users.username = channel_comments.by_user WHERE channel_comments.on_channel = :OWNER ORDER BY channel_comments.date DESC LIMIT $Comment_Pagination->From,$Comment_Pagination->To", false, [":OWNER" => $Profile["username"]]);
            }

            //RECENT ACTIVITY
            if ($Profile["c_recent"] == "1") {
                $SELECT = "SELECT 'bulletin' as type_name, id, content, date as date, '' as title FROM bulletins WHERE by_user = :OWNER ";
                if ($Profile["ra_comments"] == 1) { $SELECT .= "UNION ALL SELECT 'comment' as type_name, videos.url, video_comments.comment, video_comments.date_sent as date, videos.title as title FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE by_user = :OWNER AND videos.status = 2 AND videos.privacy = 0 AND videos.banned_uploader = 0 "; }
                if ($Profile["ra_favorites"] == 1) { $SELECT .= "UNION ALL SELECT 'favorite' as type_name, videos.url, videos.description as comment, video_favorites.date as date, videos.title as title FROM video_favorites INNER JOIN videos ON video_favorites.url = videos.url WHERE favorite_by = :OWNER AND videos.status = 2 AND videos.privacy = 0 AND videos.banned_uploader = 0 "; }
                if ($Profile["ra_friends"] == 1) { $SELECT .= "UNION ALL SELECT 'friend' as type_name, friend_1, friend_2, sent_on, '' as title FROM friends as date WHERE (friend_1 = :OWNER OR friend_2 = :OWNER) AND status = 1 "; }

                $Recent_Activity = $DB->execute("$SELECT ORDER BY date DESC LIMIT 5", false, [":OWNER" => $Profile["username"]]);

				for ($i=0; $i < count($Recent_Activity); $i++) {
					if ($Recent_Activity[$i]["type_name"] == "friend") {
                        $username1 = $DB->execute("SELECT displayname FROM users WHERE username='".$Recent_Activity[$i]["id"]."' LIMIT 1", true)["displayname"];
                        $username2 = $DB->execute("SELECT displayname FROM users WHERE username='".$Recent_Activity[$i]["content"]."' LIMIT 1", true)["displayname"];

                        $Recent_Activity[$i]["id"]      = $username1;
                        $Recent_Activity[$i]["content"] = $username2;
					}
				}
            }

            //CHANNEL BANNER
            $Banner_Links = false;
            if ($Profile["partner"] !== "2") {
                if (file_exists("usfi/bner/$Profile[username].png")) {
                    $Banner_Links = $DB->execute("SELECT links FROM channel_banners WHERE username='$Profile[username]' LIMIT 1", true);
                    if ($DB->RowNum > 0) {
                        $Banner_Links = json_decode($Banner_Links["links"], true);
                        $Banner_Image = $Profile["username"];
                    } else {
                        $Banner_Links = false;
                    }
                    $Banner_Version = $Profile["banner_version"];
                }
            }

            if (isset($_POST["save_ra"])) {
                if (isset($_POST["ra_comments"]))   { $RA_COMMENTS  = 1; } else { $RA_COMMENTS = 0; }
                if (isset($_POST["ra_favorites"]))  { $RA_FAVORITES = 1; } else { $RA_FAVORITES = 0; }
                if (isset($_POST["ra_friends"]))    { $RA_FRIENDS   = 1; } else { $RA_FRIENDS = 0; }

                $DB->modify("UPDATE users SET ra_comments = :COMMENTS, ra_favorites = :FAVORITES, ra_friends = :FRIENDS WHERE username = :USERNAME",
                    [
                        ":COMMENTS"  => $RA_COMMENTS,
                        ":FAVORITES" => $RA_FAVORITES,
                        ":FRIENDS"   => $RA_FRIENDS,
                        ":USERNAME"  => $_USER->username
                    ]);
                redirect("/user/".$Profile["displayname"]); exit();
            }

            if (isset($_POST["save_modules"])) {
                if (isset($_POST["subscribers"]))       { $c_subscriber         = 1; } else { $c_subscriber = 0; }
                if (isset($_POST["subscriptions"]))     { $c_subscriptions      = 1; } else { $c_subscriptions = 0; }
                if (isset($_POST["friends"]))           { $c_friends            = 1; } else { $c_friends = 0; }
                if (isset($_POST["comments"]))          { $c_comments           = 1; } else { $c_comments = 0; }
                if (isset($_POST["featured_channels"])) { $Featured_Channels    = 1; } else { $Featured_Channels = 0; }
                if (isset($_POST["recent"]))            { $Recent_Activity      = 1; } else { $Recent_Activity = 0; }
                if (isset($_POST["custom"]))            { $Custom               = 1; } else { $Custom = 0; }

                $Update = $DB->modify("UPDATE users SET c_custom = :CUSTOM, c_comments = :COMMENTS, c_friend = :FRIENDS, c_featured_channels = :FEATURED_CHANNELS, c_recent = :RECENT, c_subscriber = :SUBSCRIBER, c_subscription = :SUBSCRIPTIONS WHERE username = :USERNAME",
                                     [
                                         ":CUSTOM"              => $Custom,
                                         ":COMMENTS"            => $c_comments,
                                         ":FRIENDS"             => $c_friends,
                                         ":FEATURED_CHANNELS"   => $Featured_Channels,
                                         ":RECENT"              => $Recent_Activity,
                                         ":SUBSCRIBER"          => $c_subscriber,
                                         ":SUBSCRIPTIONS"       => $c_subscriptions,
                                         ":USERNAME"            => $_USER->username
                                     ]);
                redirect("/user/".$Profile["displayname"]); exit();
            }

            if (isset($_POST["save_players"])) {
                if (isset($_POST["show_all"]))          { $Show_All         = 1; } else { $Show_All = 0; }
                if (isset($_POST["show_videos"]))       { $Show_Videos      = 1; } else { $Show_Videos = 0; }
                if (isset($_POST["show_favorites"]))    { $Show_Favorites   = 1; } else { $Show_Favorites = 0; }
                if (isset($_POST["show_playlists"]))    { $Show_playlists   = 1; } else { $Show_playlists = 0; }

                if ($_POST["pl_layout"] == 0)   { $Layout   = 0; } else { $Layout = 1; }

                if ($_POST["ft_video"] == 0)    { $FT       = 0; } else { $FT = 1; }

                if (!empty($_POST["n_url"])) {
                    $N_URL      = url_parameter($_POST["n_url"], "v");
                    $N_VIDEO    = new Video($N_URL,$DB);
                    if (!$N_VIDEO->exists()) {
                        $N_URL = "";
                    }
                }  else {
                    $N_URL = "";
                }

                if (!empty($_POST["s_url"])) {
                    $S_URL      = url_parameter($_POST["s_url"], "v");
                    $S_VIDEO    = new Video($S_URL,$DB);
                    if (!$S_VIDEO->exists()) {
                        $S_URL = "";
                    }
                } else {
                    $S_URL = "";
                }

                $DB->modify("UPDATE users SET featured_n_url = :N_URL, featured_s_url = :S_URL, c_featured = :FT,  c_playlists = :C_PLAYLISTS, c_all = :C_ALL, c_videos = :C_VIDEOS, c_favorites = :C_FAVORITES, default_view = :LAYOUT WHERE username = :USERNAME",
                           [
                               ":FT"            => $FT,
                               ":N_URL"         => $N_URL,
                               ":S_URL"         => $S_URL,
                               ":C_PLAYLISTS"   => $Show_playlists,
                               ":C_ALL"         => $Show_All,
                               ":C_VIDEOS"      => $Show_Videos,
                               ":C_FAVORITES"   => $Show_Favorites,
                               ":LAYOUT"        => $Layout,
                               ":USERNAME"      => $_USER->username
                           ]);
                redirect("/user/".$_USER->displayname); exit();
            }

            if (isset($_POST["save_customization"])) {
                $_GUMP->validation_rules(array(
                    "bgcolor"           => "required|is_hex",
                    "wrappercolor"      => "required|is_hex",
                    "wrappertxtcolor"   => "required|is_hex",
                    "wrapperlinkcolor"  => "required|is_hex",
                    "inbgcolor"         => "required|is_hex",
                    "titletxtcolor"     => "required|is_hex",
                    "inlinkcolor"       => "required|is_hex",
                    "intxtcolor"        => "required|is_hex",
                    "ch_fnt"            => "required"
                ));

                $_GUMP->filter_rules(array(
                    "bgcolor"           => "trim",
                    "wrappercolor"      => "trim",
                    "wrappertxtcolor"   => "trim",
                    "wrapperlinkcolor"  => "trim",
                    "inbgcolor"         => "trim",
                    "titletxtcolor"     => "trim",
                    "inlinkcolor"       => "trim",
                    "intxtcolor"        => "trim",
                    "ch_fnt"            => "trim"
                ));

                $Validation = $_GUMP->run($_POST);

                if ($Validation) {
                    $BG_COLOR         = str_replace("#","",$Validation["bgcolor"]);
                    $WRP_COLOR        = str_replace("#","",$Validation["wrappercolor"]);
                    $WRP_TXT_COLOR    = str_replace("#","",$Validation["wrappertxtcolor"]);
                    $WRP_LINK_COLOR   = str_replace("#","",$Validation["wrapperlinkcolor"]);
                    $NM_IN_COLOR      = str_replace("#","",$Validation["inbgcolor"]);
                    $TITLE_TXT_COLOR  = str_replace("#","",$Validation["titletxtcolor"]);
                    $IN_LINK_COLOR    = str_replace("#","",$Validation["inlinkcolor"]);
                    $IN_TXT_COLOR     = str_replace("#","",$Validation["intxtcolor"]);

                    if (isset($Validation["n_trans"]) && $Validation["n_trans"] >= 0 && $Validation["n_trans"] <= 100) { $Normal_Trans = (int)$Validation["n_trans"]; } else { $Normal_Trans = 0; }
                    if (isset($Validation["h_trans"]) && $Validation["h_trans"] >= 0 && $Validation["h_trans"] <= 100) { $Highlight_Trans = (int)$Validation["h_trans"]; } else { $Highlight_Trans = 0; }
                    if (isset($Validation["ch_fnt"]) && $Validation["ch_fnt"] >= 0 && $Validation["ch_fnt"] <= 6) { $Channel_Font = (int)$Validation["ch_fnt"]; } else { $Channel_Font = 0; }

                    if ($Validation["bg_repeat"] >= 1 && $Validation["bg_repeat"] <= 4) {
                        $bg_repeat = (int)$Validation["bg_repeat"];
                    }

                    if ($Validation["bg_position"] >= 1 && $Validation["bg_position"] <= 4) {
                        $bg_position = (int)$Validation["bg_position"];
                    }


                    if (!empty($_FILES["bg_upload"]["name"])) {
                        $Allowed_Types = array("jpg","jpeg","gif","png","bmp");
                        $Image_Type = pathinfo($_FILES["bg_upload"]["name"], PATHINFO_EXTENSION);

                        if (convert_filesize($_FILES["bg_upload"]["size"],"kb") <= 502 && in_array(strtolower($Image_Type),$Allowed_Types)) {
                            $File = @glob("usfi/bg/$_USER->username.*")[0];
                            if ($File === NULL) {
                                move_uploaded_file($_FILES["bg_upload"]["tmp_name"],"usfi/bg/$_USER->username.$Image_Type");
                                $DB->modify("UPDATE users SET bg_version = bg_version + 1 WHERE username = '$_USER->username'");
                            }
                        }
                    }


                    if (isset($Validation["bg_fixed"])) { $bg_fixed = 1; } else { $bg_fixed = 0; }
                    if (isset($Validation["bg_stretch"])) { $bg_stretch = 1; } else { $bg_stretch = 0; }
                    if (isset($Validation["chn_radius"]) && $Validation["chn_radius"] >= 0 && $Validation["chn_radius"] <= 9) { $Channel_Radius = (int)$Validation["chn_radius"]; } else { $Channel_Radius = 5; }
                    if (isset($Validation["avt_radius"]) && $Validation["avt_radius"] >= 0 && $Validation["avt_radius"] <= 9) { $Avatar_Radius = (int)$Validation["avt_radius"]; } else { $Avatar_Radius = 4; }


                    if ($Validation["theme"] >= 0 && $Validation["theme"] <= 9) { $Theme = (int)$Validation["theme"]; } else { $Theme = 0; }

                    $DB->modify("UPDATE users SET avt_radius = :AVT_RADIUS, chn_radius = :CHN_RADIUS, font = :FONT, theme = :THEME, n_trans = :N_TRANS, h_trans = :H_TRANS, bg_repeat = :BG_REPEAT, bg_position = :BG_POSITION, bg_fixed = :BG_FIXED, bg_stretch = :BG_STRETCH, bg = :BG_COLOR, h_head = :WRP_COLOR, h_in_fnt = :WRP_TXT_COLOR, h_head_fnt = :WRP_LINK_COLOR, n_in = :NM_IN_COLOR, n_head_fnt = :TITLE_TXT_COLOR, links = :IN_LINK_COLOR, n_in_fnt = :IN_TXT_COLOR WHERE username = :USERNAME",
                               [
                                   ":AVT_RADIUS"        => $Avatar_Radius,
                                   ":CHN_RADIUS"        => $Channel_Radius,
                                   ":FONT"              => $Channel_Font,
                                   ":THEME"             => $Theme,
                                   ":N_TRANS"           => $Normal_Trans,
                                   ":H_TRANS"           => $Highlight_Trans,
                                   ":BG_REPEAT"         => $bg_repeat,
                                   ":BG_POSITION"       => $bg_position,
                                   ":BG_FIXED"          => $bg_fixed,
                                   ":BG_STRETCH"        => $bg_stretch,
                                   ":BG_COLOR"          => $BG_COLOR,
                                   ":WRP_COLOR"         => $WRP_COLOR,
                                   ":WRP_TXT_COLOR"     => $WRP_TXT_COLOR,
                                   ":WRP_LINK_COLOR"    => $WRP_LINK_COLOR,
                                   ":NM_IN_COLOR"       => $NM_IN_COLOR,
                                   ":TITLE_TXT_COLOR"   => $TITLE_TXT_COLOR,
                                   ":IN_LINK_COLOR"     => $IN_LINK_COLOR,
                                   ":IN_TXT_COLOR"      => $IN_TXT_COLOR,
                                   ":USERNAME"          => $_USER->username
                               ]);

                    redirect("/user/".$_USER->displayname); exit();
                }
            }

            $Background = @glob("usfi/bg/".$Profile["username"].".*")[0];
            if ($Background === NULL) {
                $Has_Background = false;
            } else {
                $Background = "/".$Background."?".$Profile["bg_version"];
                $Has_Background = true;
            }


            if ($Profile["c_featured_channels"] == "1" && !empty($Profile["featured_channels"])) {
                $Featured_Channels_Array = sql_IN_fix(explode(",",$Profile["featured_channels"]));
                if (strpos($Featured_Channels_Array,",") !== false) { $ORDER = "ORDER BY FIELD($Featured_Channels_Array)"; } else { $ORDER = ""; }
                $Featured_Channels = $DB->execute("SELECT username, displayname, channel_description, subscribers, videos, video_views, avatar FROM users WHERE username IN ($Featured_Channels_Array) ORDER BY FIELD(username,$Featured_Channels_Array)", false);
            }

            $Video_Selected = false;



            //GET META DESCRIPTION
            if (!empty($Profile["channel_description"])) {
                $Page_Description = htmlspecialchars(preg_replace( "/\r|\n/", "", limit_text($Profile["channel_description"],160)));
            } else {
                $Page_Description = "";
            }

            if (!empty($Profile["channel_tags"])) {
                $Page_Keywords = htmlspecialchars(preg_replace( "/\r|\n/", "", limit_text($Profile["channel_tags"],100)));
            } else {
                $Page_Keywords = "";
            }



            if (strpos($Profile["avatar"],"u=") !== false) {
                $Avatar = str_replace("u=","",$Profile["avatar"]);
                $Folder = "avt";
            } elseif (!empty($Profile["avatar"])) {
                $Avatar = $Profile["avatar"];
                $Upload = false;
                $Folder = "thmp";
            } else {
                $Upload = false;
                $Folder = "";
            }

            if (empty($Folder)) {
                $Avatar = "/img/no.png";
            } else {
                $Avatar = "/usfi/$Folder/$Avatar.jpg";
            }


            $_USER->view_channel($Profile["username"]);



            if (!is_numeric($_GET["page"]) && !empty($_GET["page"])) {
                switch ($_GET["page"]) {
                    case "subscribers" :
                        $Page_File = "Subscribers";
                        if ($Profile["subscribers"] < 1 or !$Profile["c_subscriber"]) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    case "subscriptions" :
                        $Page_File = "Subscriptions";
                        if ($Profile["subscriptions"] < 1 or !$Profile["c_subscription"]) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    case "friends" :
                        $Page_File = "Friends";
                        if ($Profile["channel_comments"] < 1 or !$Profile["c_comments"]) {
                            redirect("/user/" . $Profile["displayname"]);
                        }
                        break;
                    default :
                        redirect("/user/" . $Profile["displayname"]);
                }
            } else {
                $Page_File = "Main";
            }


            $Page_Title = $Profile["displayname"]." - VidLii";

            //VALUES
            if (isset($_COOKIE["cp"])) {
                $Player1 = explode(",",$_COOKIE["cp"]);
            }

			if (isset($_COOKIE["player"])) {
				$Player = (int)$_COOKIE["player"];
				if ($Player < 0 || $Player > 3) $Player = 2;
			} else {
				$Player = 2;
			}

            require_once "_templates/profile2_structure.php";
        } elseif ($Profile["channel_version"] == 3 && $Profile["banned"] == 0) {
            //COSMIC PANDA

            if ($_USER->logged_in && $_USER->username === $Profile["username"]) {
                $Is_OWNER = true;
                $Is_Subscribed = false;
                $Is_Friends = false;
                $Is_Blocked = false;
                $Has_Blocked = false;

                if (isset($_POST["change_ra"])) {
                    if (isset($_POST["ra_comments"]))   { $RA_COMMENTS  = 1; } else { $RA_COMMENTS = 0; }
                    if (isset($_POST["ra_favorites"]))  { $RA_FAVORITES = 1; } else { $RA_FAVORITES = 0; }
                    if (isset($_POST["ra_friends"]))    { $RA_FRIENDS   = 1; } else { $RA_FRIENDS = 0; }

                    $DB->modify("UPDATE users SET ra_comments = :COMMENTS, ra_favorites = :FAVORITES, ra_friends = :FRIENDS WHERE username = :USERNAME",
                               [
                                   ":COMMENTS"  => $RA_COMMENTS,
                                   ":FAVORITES" => $RA_FAVORITES,
                                   ":FRIENDS"   => $RA_FRIENDS,
                                   ":USERNAME"  => $_USER->username
                               ]);
                    redirect("/user/".$Profile["displayname"]); exit();
                }

            } elseif ($_USER->logged_in) {
                $Is_OWNER = false;

                //CHECK IF FRIENDS
                $Friend_Status = $DB->execute("SELECT status, by_user FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :OWNER) OR (friend_1 = :OWNER AND friend_2 = :USERNAME)", true,
                    [
                        ":USERNAME"    => $_USER->username,
                        ":OWNER"       => $Profile["username"]
                    ]);

                if ($DB->RowNum > 0) {
                    $Status = $Friend_Status["status"];
                    $By     = $Friend_Status["by_user"];
                    if ($Status == 0) {
                        if ($By === $_USER->username) {
                            $Is_Friends = 2;
                        } else {
                            $Is_Friends = 3;
                        }
                    } else {
                        $Is_Friends = true;
                    }
                } else {
                    $Is_Friends = false;
                }

                //CHECK IF BLOCKED
                $Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = '$_USER->username' AND blocked = '$OWNER_USERNAME') OR (blocker = '$OWNER_USERNAME' AND blocked = '$_USER->username')", true);
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

                $Is_Subscribed = $_USER->is_subscribed_to($Profile["username"]);
            } else {
                $Is_OWNER       = false;
                $Is_Friends     = false;
                $Is_Subscribed  = false;
                $Is_Blocked     = false;
                $Has_Blocked    = false;
            }



            if ($Profile["privacy"] == 2 && $Is_Friends == false && $_USER->username != $Profile["username"]) {
                notification("You're not allowed to see this page!","/","red"); exit();
            }

            if ($Profile["privacy"] == 1 && (!$_USER->logged_in || $_USER->username != $Profile["username"])) {
                notification("You're not allowed to see this page!","/","red"); exit();
            }




            //CHANNEL BANNER
            $Banner_Links = false;
            if ($Profile["partner"] !== "2") {
                if (file_exists("usfi/bner/$Profile[username].png")) {
                    $Banner_Links = $DB->execute("SELECT links FROM channel_banners WHERE username='$Profile[username]' LIMIT 1", true);
                    if ($DB->RowNum > 0) {
                        $Banner_Links = json_decode($Banner_Links["links"], true);
                        $Banner_Image = $Profile["username"];
                    } else {
                        $Banner_Links = false;
                    }
                    $Banner_Version = $Profile["banner_version"];
                }
            }


            if (!empty($Profile["country"]) or $Is_OWNER) {
                $Countries = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];
            }


            $DB->execute("SELECT purl FROM playlists WHERE created_by = :OWNER", false, [":OWNER" => $Profile["username"]]);
            $Playlist_Amount = $DB->RowNum;


            if ($_GET["page"] != "feed" or $_GET["page"] != "comments" or $_GET["page"] != "videos" or $_GET["page"] != "playlists") {
                if ($Profile["videos"] > 0 and $Profile["c_videos"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "videos")) {
                    if ($Profile["c_featured"]) {
                        $Featured_Video = $DB->execute("SELECT url, file, hd, title, displayviews as views, length, uploaded_by, uploaded_on, status, '$Profile[displayname]' as displayname FROM videos WHERE uploaded_by = :OWNER AND status = 2 AND privacy = 0 AND banned_uploader = 0 ORDER BY uploaded_on DESC LIMIT 1", true, [":OWNER" => $Profile["username"]]);
                    }

                    if ($Profile["c_videos"]) {

                        $Videos                     = new Videos($DB, $_USER);
                        $Videos->WHERE_P            = ["uploaded_by" => $Profile["username"]];
                        $Videos->ORDER_BY           = "uploaded_on DESC";
                        $Videos->Shadowbanned_Users = true;
                        $Videos->LIMIT              = 10;
                        $Videos->get();

                        if ($Videos::$Videos) {

                            $Videos = $Videos->fixed();

                        } else {

                            $Videos = [];

                        }

                        if ($DB->RowNum < $Profile["videos"]) {
                            $Show_More = true;
                        } else {
                            $Show_More = false;
                        }
                    }
                }


                if ($Profile["favorites"] > 0 and $Profile["c_favorites"] && (empty($_GET["page"]) || is_numeric($_GET["page"]) || $_GET["page"] == "favorites")) {

                    $Favorites = new Videos($DB, $_USER);
                    $Favorites->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
                    $Favorites->Shadowbanned_Users = true;
                    $Favorites->Banned_Users       = true;
                    $Favorites->Private_Videos     = true;
                    $Favorites->Unlisted_Videos    = true;
                    $Favorites->ORDER_BY           = "video_favorites.date DESC";
                    $Favorites->WHERE_P            = ["video_favorites.favorite_by" => $Profile["username"]];
                    $Favorites->LIMIT              = 10;
                    $Favorites->get();

                    if ($Favorites::$Videos) {

                        $Favorites = $Favorites->fixed();

                    } else {

                        $Favorites = false;

                    }

                    if (!isset($Show_More)) {
                        if ($DB->RowNum < $Profile["favorites"]) {
                            $Show_More = true;
                        } else {
                            $Show_More = false;
                        }
                    }
                    if (!isset($Featured_Video) && !empty($Favorites[0]["title"])) {
                        $Featured_Video = $Favorites[0];
                    }
                }
            }


            if (!empty($Profile["playlists"])) {
                if (empty($_GET["page"]) or $_GET["page"] == "feed" or $_GET["page"] == "comments" or $_GET["page"] == "playlist") {
                    $Playlists_IN       = sql_IN_fix(explode(",", $Profile["playlists"]));
                    $Playlists          = $DB->execute("SELECT * FROM playlists WHERE purl IN ($Playlists_IN) ORDER BY FIELD(purl,$Playlists_IN)");
                    $Playlists_Amount   = $DB->RowNum;
                }
            }


            if ($_GET["page"] === "playlist") {
                $Main_Playlist = $_GET["p"];
                if (!empty($Main_Playlist) && $Main_Playlist != 1) {
                    $Check_Playlist = $DB->execute("SELECT purl, title FROM playlists WHERE created_by = :OWNER AND purl = :PURL", true,
                                                  [
                                                      ":OWNER"  => $Profile["username"],
                                                      ":PURL"   => $Main_Playlist
                                                  ]);

                    if ($DB->RowNum == 1) {

                        $Main_Playlist_Videos                       = new Videos($DB, $_USER);
                        $Main_Playlist_Videos->WHERE_P              = ["playlists_videos.purl" => $Main_Playlist];
                        $Main_Playlist_Videos->JOIN                 = "RIGHT JOIN playlists_videos ON playlists_videos.url = videos.url";
                        $Main_Playlist_Videos->ORDER_BY             = "playlists_videos.position";
                        $Main_Playlist_Videos->Shadowbanned_Users   = true;
                        $Main_Playlist_Videos->LIMIT                = 512;
                        $Main_Playlist_Videos->get();

                        if ($Main_Playlist_Videos::$Videos) {

                            $Main_Playlist_Videos = $Main_Playlist_Videos->fixed();

                        } else {

                            $Main_Playlist_Videos = [];

                        }

                    } else {
                        redirect("/user/".$Profile["displayname"]); exit();
                    }
                } else {
                    redirect("/user/".$Profile["displayname"]); exit();
                }
            }


            if ($Is_OWNER) {
                $Playlists_Select = $DB->execute("SELECT title, purl FROM playlists WHERE created_by = '$_USER->username'");
            }


            if (!isset($Featured_Video) && $Profile["c_featured"] && $_GET["page"] == "") {
                $Featured_Video = $DB->execute("SELECT videos.url, videos.file, videos.hd, videos.title, videos.displayviews as views, videos.length, videos.uploaded_by, videos.uploaded_on, videos.status, users.displayname FROM playlists INNER JOIN playlists_videos ON playlists.purl = playlists_videos.purl INNER JOIN videos ON playlists_videos.url = videos.url INNER JOIN users ON videos.uploaded_by = users.username WHERE videos.status = 2 AND playlists.created_by = :OWNER AND videos.privacy = 0 ORDER BY videos.uploaded_on DESC LIMIT 1", false, [":OWNER" => $Profile["username"]]);
                if (isset($Featured_Video[0])) {
                    $Featured_Video = $Featured_Video[0];
                }
            }

            if ($Profile["c_featured"] && ((!empty($Profile["featured_n_url"])) || ($Is_Subscribed && !empty($Profile["featured_s_url"])))) {
                if (!$Is_Subscribed && !empty($Profile["featured_n_url"])) {
                    $F_URL                  = $Profile["featured_n_url"];
                    $Featured_Video_Find    = $DB->execute("SELECT v.url, v.file, v.hd, v.title, v.uploaded_on, v.displayviews as views, v.length, v.uploaded_by, v.comments, v.status, u.displayname FROM videos v, users u WHERE v.url = :URL AND v.status = 2 AND v.privacy = 0 AND u.username = v.uploaded_by", true, [":URL" => $F_URL]);
                    if ($DB->RowNum == 1) {
                        $Featured_Video = $Featured_Video_Find;
                    }
                } elseif ($Is_Subscribed && !empty($Profile["featured_s_url"])) {
                    $F_URL                  = $Profile["featured_s_url"];
                    $Featured_Video_Find    = $DB->execute("SELECT v.url, v.file, v.hd, v.title, v.uploaded_on, v.displayviews as views, v.length, v.uploaded_by, v.comments, v.status, u.displayname FROM videos v, users u WHERE v.url = :URL AND v.status = 2 AND v.privacy = 0 AND u.username = v.uploaded_by", true, [":URL" => $F_URL]);
                    if ($DB->RowNum == 1) {
                        $Featured_Video = $Featured_Video_Find;
                    }
                }
            }


            if (!isset($Favorites) && !isset($Videos) && $Playlist_Amount > 0 && $_GET["page"] == "") {
                $All_Playlists = $DB->execute("SELECT p.*, '$Profile[displayname]' as displayname FROM playlists p WHERE p.created_by = :OWNER ORDER BY p.created_on DESC LIMIT 15", false, [":OWNER" => $Profile["username"]]);
            }


            //RECENT ACTIVITY
            if ($Profile["c_recent"] == "1") {
                $SELECT = "SELECT 'bulletin' as type_name, id, content, date as date, '' as title, '' as length, '' as views FROM bulletins WHERE by_user = :OWNER ";
                if ($Profile["ra_comments"] == 1) { $SELECT .= "UNION ALL SELECT 'comment' as type_name, videos.url, videos.description, video_comments.date_sent as date, videos.title as title, videos.length as length, videos.displayviews as views FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE by_user = :OWNER "; }
                if ($Profile["ra_favorites"] == 1) { $SELECT .= "UNION ALL SELECT 'favorite' as type_name, videos.url, videos.description as comment, video_favorites.date as date, videos.title as title, videos.length as length, videos.displayviews as views FROM video_favorites INNER JOIN videos ON video_favorites.url = videos.url WHERE favorite_by = :OWNER "; }
                if ($Profile["ra_friends"] == 1) { $SELECT .= "UNION ALL SELECT 'friend' as type_name, friend_1, friend_2, sent_on, '' as title, '' as length, '' as views FROM friends as date WHERE (friend_1 = :OWNER OR friend_2 = :OWNER) AND status = 1 "; }


                $Recent_Activity = $DB->execute("$SELECT ORDER BY date DESC LIMIT 10", false, [":OWNER" => $Profile["username"]]);


				for ($i=0; $i < count($Recent_Activity); $i++) {
					if ($Recent_Activity[$i]["type_name"] == "friend") {
                        $username1 = $DB->execute("SELECT displayname FROM users WHERE username='".$Recent_Activity[$i]["id"]."' LIMIT 1", true)["displayname"];
                        $username2 = $DB->execute("SELECT displayname FROM users WHERE username='".$Recent_Activity[$i]["content"]."' LIMIT 1", true)["displayname"];

                        $Recent_Activity[$i]["id"]      = $username1;
                        $Recent_Activity[$i]["content"] = $username2;
					}
				}
            }

            if (isset($_POST["cosmic_save_about"]) && $_USER->logged_in) {
                $_GUMP->validation_rules(array(
                    "cosmic_description"    => "max_len,2600",
                    "cosmic_website"        => "valid_url|max_len,128"
                ));

                $_GUMP->filter_rules(array(
                    "cosmic_description"   => "trim|NoHTML",
                    "cosmic_website"       => "trim"
                ));

                $Validation = $_GUMP->run($_POST);

                if ($Validation) {
                    $Channel_Description = $Validation["cosmic_description"];

                    if (isset($_POST["cosmic_last"])) { $Last_Login = 1; } else { $Last_Login = 0; }
                    if (isset($_POST["cosmic_show_country"])) { $Show_Country = 1; } else { $Show_Country = 0; }
                    if(array_key_exists($Validation["cosmic_country"],$Countries)) { $Country = $Validation["cosmic_country"]; } else { $Country = $Profile["country"]; }

                    $DB->modify("UPDATE users SET website = :WEBSITE, country = :COUNTRY, channel_description = :DESCRIPTION, a_country = :SHOW_COUNTRY, a_last = :SHOW_LAST WHERE username = :USERNAME",
                               [
                                   ":WEBSITE"       => $Validation["cosmic_website"],
                                   ":COUNTRY"       => $Country,
                                   ":SHOW_COUNTRY"  => $Show_Country,
                                   ":SHOW_LAST"     => $Last_Login,
                                   ":DESCRIPTION"   => $Channel_Description,
                                   ":USERNAME"      => $_USER->username
                               ]);

                    redirect($_SERVER['REQUEST_URI']); exit();
                }
            }

            if ($_USER->logged_in && isset($_POST["save_featured"])) {
                if (!empty($_POST["n_url"])) {
                    $N_URL = url_parameter($_POST["n_url"], "v");
                    $N_VIDEO = new Video($N_URL,$DB);
                    if (!$N_VIDEO->exists()) {
                        $N_URL = "";
                    }
                }  else {
                    $N_URL = "";
                }

                if (!empty($_POST["s_url"])) {
                    $S_URL = url_parameter($_POST["s_url"], "v");
                    $S_VIDEO = new Video($S_URL,$DB);
                    if (!$S_VIDEO->exists()) {
                        $S_URL = "";
                    }
                } else {
                    $S_URL = "";
                }

                $DB->modify("UPDATE users SET featured_n_url = :N_URL, featured_s_url = :S_URL WHERE username = :USERNAME",
                           [
                               ":N_URL"     => $N_URL,
                               ":S_URL"     => $S_URL,
                               ":USERNAME"  => $_USER->username
                           ]);
                redirect("/user/".$_USER->displayname); exit();
            }

            if (isset($_POST["hide_featured"]) && $_USER->logged_in) {
                $DB->modify("UPDATE users SET c_featured = 0 WHERE username = '$_USER->username'");
                redirect("/user/$_USER->displayname"); exit();
            }


            if (isset($_POST["show_featured"]) && $_USER->logged_in) {
                $DB->modify("UPDATE users SET c_featured = 1 WHERE username = '$_USER->username'");
                redirect("/user/$_USER->displayname"); exit();
            }

            if (isset($_POST["save_recent"]) && $_USER->logged_in) {
                if (isset($_POST["recent_comments"])) { $Recent_Comments = 1; } else { $Recent_Comments = 0; }
                if (isset($_POST["recent_favorites"])) { $Recent_Favorites = 1; } else { $Recent_Favorites = 0; }
                if (isset($_POST["recent_friends"])) { $Recent_Friends = 1; } else { $Recent_Friends = 0; }

                $DB->modify("UPDATE users SET ra_comments = $Recent_Comments, ra_favorites = $Recent_Favorites, ra_friends = $Recent_Friends WHERE username = '$_USER->username'");
                redirect("/user/$_USER->displayname/feed"); exit();
            }

            if (isset($_POST["save_comments"]) && $_USER->logged_in) {
                if ($_POST["cosmic_comment_setting"] >= 0 && $_POST["cosmic_comment_setting"] <= 2) {
                    $Comment_Privacy = (int)$_POST["cosmic_comment_setting"];
                } else {
                    $Comment_Privacy = 0;
                }

                $DB->modify("UPDATE users SET channel_comment_privacy = $Comment_Privacy WHERE username = '$_USER->username'");
                redirect("/user/$_USER->displayname/comments"); exit();
            }

            if (isset($_POST["save_channel_branding"]) && $_USER->logged_in) {
                $_GUMP->validation_rules(array(
                    "ch_title"          => "max_len,80",
                    "ch_tags"           => "max_len,270",
                    "bg_color"           => "required|is_hex"
                ));

                $_GUMP->filter_rules(array(
                    "ch_title"         => "trim|NoHTML",
                    "ch_tags"          => "trim|NoHTML",
                    "bg_color"         => "trim"
                ));

                $Validation = $_GUMP->run($_POST);

                if ($Validation) {
                    if (!empty($_FILES["bg_upload"]["name"])) {
                        $Allowed_Types = array("jpg","jpeg","gif","png","bmp");
                        $Image_Type = pathinfo($_FILES["bg_upload"]["name"], PATHINFO_EXTENSION);

                        if (convert_filesize($_FILES["bg_upload"]["size"],"kb") <= 500 && in_array(strtolower($Image_Type),$Allowed_Types)) {
                            $File = @glob("usfi/bg/$_USER->username.*")[0];
                            if ($File === NULL) {
                                move_uploaded_file($_FILES["bg_upload"]["tmp_name"],"usfi/bg/$_USER->username.$Image_Type");
                                $DB->modify("UPDATE users SET bg_version = bg_version + 1 WHERE username = '$_USER->username'");
                            }
                        }
                    }

                    if (isset($Validation["bg_fixed"])) { $bg_fixed = 1; } else { $bg_fixed = 0; }
                    if ($Validation["bg_repeat"] >= 1 && $Validation["bg_repeat"] <= 4) {
                        $bg_repeat = (int)$Validation["bg_repeat"];
                    } else {
                        $bg_repeat = 1;
                    }

                    if ($Validation["bg_position"] >= 1 && $Validation["bg_position"] <= 4) {
                        $bg_position = (int)$Validation["bg_position"];
                    } else {
                        $bg_position = 1;
                    }

                    $Validation["bg_color"] = str_replace("#","",$Validation["bg_color"]);

                    if (isset($_POST["c_recent"])) { $Recent = 1; } else { $Recent = 0; }
                    if (isset($_POST["c_comments"])) { $Comments = 1; } else { $Comments = 0; }
                    if (isset($_POST["c_videos"])) { $Videos = 1; } else { $Videos = 0; }
                    if (isset($_POST["c_favorites"])) { $Favorites = 1; } else { $Favorites = 0; }
                    if (isset($_POST["c_playlists"])) { $Playlists = 1; } else { $Playlists = 0; }

                    $DB->modify("UPDATE users SET bg = :BG_COLOR, channel_title = :TITLE, channel_tags = :TAGS, c_recent = $Recent, c_comments = $Comments, c_videos = $Videos, c_favorites = $Favorites, c_playlists = $Playlists, bg_position = :POSITION, bg_repeat = :REPEAT, bg_fixed = :FIXED WHERE username = '$_USER->username'",
                               [
                                   ":TITLE"     => $Validation["ch_title"],
                                   ":TAGS"      => $Validation["ch_tags"],
                                   ":POSITION"  => $bg_position,
                                   ":REPEAT"    => $bg_repeat,
                                   ":FIXED"     => $bg_fixed,
                                   ":BG_COLOR"  => $Validation["bg_color"]
                               ]);
                    redirect($_SERVER['REQUEST_URI']); exit();
                }
            }


            if (isset($_POST["save_featured_channels"]) && $_USER->logged_in) {
                if(strlen($_POST["featured_title"]) <= 64) {
                    $Featured_Title = $_POST["featured_title"];
                } else {
                    $Featured_Title = "";
                }
                $Channels = array_unique(array_filter(explode(",",$_POST["featured_channels"])));

                $Error = false;
                $Channels_String = "";
                if (count($Channels) <= 8) {
                    foreach($Channels as $Channel) {
                        $Username = $DB->execute("SELECT username FROM users WHERE username = :USERNAME", true, [":USERNAME" => $Channel])["username"];
                        if (!ctype_alnum($Channel) || $DB->RowNum == 0) {
                            $Error = true;
                        } else {
                            $Channels_String .= $Username.",";
                        }
                    }
                    if ($Error == false) {
                        $Channels   = substr($Channels_String,0,strlen($Channels_String) - 1);
                        $Update     = $DB->modify("UPDATE users SET featured_channels = :CHANNELS, featured_title = :TITLE WHERE username = '$_USER->username'",
                                                 [
                                                     ":CHANNELS"    => $Channels,
                                                     ":TITLE"       => $Featured_Title
                                                 ]);
                    }
                }
                redirect($_SERVER['REQUEST_URI']); exit();
            }


            if (isset($_POST["save_playlists"])) {
                $Playlists = array_unique(array_filter(explode(",",$_POST["playlists"])));

                if (count($Playlists) <= 3) {
                    $Error = false;
                    $Playlists_String = "";

                    foreach($Playlists as $Playlist) {
                        $Check = $DB->execute("SELECT purl FROM playlists WHERE purl = :PURL AND created_by = '$_USER->username'", true, [":PURL" => $Playlist])["purl"];
                        if ($DB->RowNum == 0) {
                            $Error = true;
                        } else {
                            $Playlists_String .= $Check.",";
                        }
                    }
                    if ($Error == false) {
                        $Playlists  = substr($Playlists_String, 0, strlen($Playlists_String) - 1);
                        $DB->modify("UPDATE users SET playlists = :PLAYLISTS WHERE username = '$_USER->username'", [":PLAYLISTS" => $Playlists]);
                    }
                }
                redirect($_SERVER['REQUEST_URI']); exit();
            }


            if ($_GET["page"] == "videos") {
                if (!isset($_POST["q"]) or strlen($_POST["q"]) > 64) {
                    $_PAGINATION = new Pagination(21, 50);
                    $_PAGINATION->Total = $Profile["videos"];

                    $Videos                     = new Videos($DB, $_USER);
                    $Videos->WHERE_P            = ["uploaded_by" => $Profile["username"]];
                    $Videos->ORDER_BY           = "uploaded_on DESC";
                    $Videos->Shadowbanned_Users = true;
                    $Videos->LIMIT              = $_PAGINATION;
                    $Videos->get();


                    if ($Videos::$Videos) {

                        $Videos = $Videos->fixed();

                    } else {

                        $Videos = [];

                    }


                } else {

                    $Videos                     = new Videos($DB, $_USER);
                    $Videos->WHERE_C            = " AND MATCH(videos.title,videos.description,videos.tags) AGAINST (:SEARCH) AND videos.uploaded_by = :OWNER";
                    $Videos->Execute            = [":OWNER"  => $Profile["username"], ":SEARCH" => $_POST["q"]];
                    $Videos->ORDER_BY           = "uploaded_on DESC";
                    $Videos->Shadowbanned_Users = true;
                    $Videos->LIMIT              = 100;
                    $Videos->get();


                    if ($Videos::$Videos) {

                        $Videos = $Videos->fixed();

                    } else {

                        $Videos = [];

                    }

                }
            }


            if ($_GET["page"] == "favorites") {
                $_PAGINATION = new Pagination(21,50);
                $_PAGINATION->Total = $Profile["favorites"];


                $Videos = new Videos($DB, $_USER);
                $Videos->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
                $Videos->SELECT            .= ", video_favorites.date";
                $Videos->Shadowbanned_Users = true;
                $Videos->Banned_Users       = true;
                $Videos->Private_Videos     = true;
                $Videos->Unlisted_Videos    = true;
                $Videos->ORDER_BY           = "video_favorites.date DESC";
                $Videos->WHERE_P            = ["video_favorites.favorite_by" => $Profile["username"]];
                $Videos->LIMIT              = $_PAGINATION;
                $Videos->get();


                if ($Videos::$Videos) {

                    $Videos = $Videos->fixed();

                } else {

                    $Videos = [];

                }
            }



            if ($_GET["page"] == "playlists") {
                if ($Playlist_Amount > 0) {
                    $Has_Playlists = true;
                    $_PAGINATION = new Pagination(10,50);
                    $_PAGINATION->Total = $Playlist_Amount;

                    $Playlists = $DB->execute("SELECT p.*, '$Profile[displayname]' as displayname FROM playlists p WHERE p.created_by = :OWNER ORDER BY p.created_on DESC LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":OWNER" => $Profile["username"]]);
                } else {
                    $Has_Playlists = false;
                }
            }



            if (isset($_COOKIE["cp"])) {
                $Player1 = explode(",",$_COOKIE["cp"]);
            }

			if (isset($_COOKIE["player"])) {
				$Player = (int)$_COOKIE["player"];
				if ($Player < 0 || $Player > 3) $Player = 2;
			} else {
				$Player = 2;
			}


            if ($Profile["c_featured_channels"] == "1" && !empty($Profile["featured_channels"])) {
                $Featured_Channels_Array = sql_IN_fix(explode(",",$Profile["featured_channels"]));
                if (strpos($Featured_Channels_Array,",") !== false) { $ORDER = "ORDER BY FIELD($Featured_Channels_Array)"; } else { $ORDER = ""; }
                $Featured_Channels = $DB->execute("SELECT username, displayname, subscribers, avatar FROM users WHERE username IN ($Featured_Channels_Array) ORDER BY FIELD(username,$Featured_Channels_Array)");
            }




            switch ($_GET["page"]) {
                case "" :
                    if (($Profile["c_videos"] && $Profile["videos"] > 0) || ($Profile["c_favorites"] && $Profile["favorites"] > 0) || ($Profile["c_playlists"] && $Playlist_Amount > 0)) {
                        $Page_File = "Main";
                    } elseif ($Profile["c_recent"] || $Profile["c_comments"]) {
                        $Page_File = "Feed";
                        if ($Profile["c_recent"]) {
                            $_GET["page"] = "feed";
                        } else {
                            $_GET["page"] = "comments";
                        }
                    } else {
                        $Page_File = "Main";
                        $Nothing_To_Show = true;
                    }
                    break;
                case "feed" :
                    $Page_File = "Feed";
                    if (!$Profile["c_recent"]) {
                        redirect("/user/" . $Profile["displayname"]);
                    }
                    break;
                case "comments" :
                    $Page_File = "Feed";
                    if (!$Profile["c_comments"]) {
                        redirect("/user/" . $Profile["displayname"]);
                    }
                    break;
                case "videos" :
                    $Page_File = "Videos";
                    if (!$Profile["c_videos"] || $Profile["videos"] == 0) {
                        redirect("/user/" . $Profile["displayname"]);
                    }
                    break;
                case "favorites" :
                    $Page_File = "Videos";
                    if (!$Profile["c_favorites"] || $Profile["favorites"] == 0) {
                        redirect("/user/" . $Profile["displayname"]);
                    }
                    break;
                case "playlists" :
                    $Page_File = "Videos";
                    if (!$Profile["c_playlists"] || !$Has_Playlists) {
                        redirect("/user/" . $Profile["displayname"]);
                    }
                    break;
                case "playlist" :
                    $Page_File = "Playlist";
                    break;
                default :
                    redirect("/user/".$Profile["displayname"]);
            }
            $Page_Title = $Profile["displayname"]." - VidLii";


            if ($_GET["page"] == "comments" && $Profile["c_comments"] && $Profile["channel_comments"] > 0) {
                $Channel_Comments = $DB->execute("SELECT channel_comments.*, users.avatar, users.displayname FROM channel_comments INNER JOIN users ON channel_comments.by_user = users.username WHERE channel_comments.on_channel = :OWNER ORDER BY channel_comments.date DESC LIMIT 20", false, [":OWNER" => $Profile["username"]]);
                if ($DB->RowNum < $Profile["channel_comments"]) { $Show_More = true; } else { $Show_More = false; }
            }


            function no_link_avatar($User,$Width,$Height,$Avatar,$Extra_Class = "") {
                if (strpos($Avatar,"u=") !== false) { $Avatar = str_replace("u=","",$Avatar); $Folder = "avt"; } else { $Upload = false; $Folder = "thmp"; }

                if (empty($Avatar) or !file_exists("usfi/$Folder/$Avatar.jpg")) {
                    $Avatar = "/img/no.png";
                } else {
                    if ($Folder == "avt") {
                        $Avatar = "/usfi/avt/$Avatar.jpg";
                    } else {
                        $Avatar = "/usfi/thmp/$Avatar.jpg";
                    }
                }
                return '<div href="/user/'.$User.'"><img src="'.$Avatar.'" width="'.$Width.'" height="'.$Height.'" class="avt2 '.$Extra_Class.'" alt="'.$User.'"></div>';
            }


            function thumbnail_picture($URL,$LENGTH,$Width,$Height,$Title = NULL) {
                if (!empty($LENGTH) || $LENGTH == "0") { $Length = seconds_to_time((int)$LENGTH); } else { $Length = $LENGTH; }
                if (file_exists("usfi/thmp/$URL.jpg")) { $Thumbnail = "/usfi/thmp/$URL.jpg"; } else { $Thumbnail = "/img/no_th.jpg"; }

                return '<div style="display:inline-block;position: relative;width:'.$Width.'px"><div class="th_t">'.$Length.'</div><img class="vid_th" src="'.$Thumbnail.'" width="'.$Width.'" height="'.$Height.'"></div>';
            }


            $Background = @glob("usfi/bg/".$Profile["username"].".*")[0];
            if ($Background === NULL) {
                $Has_Background = false;
            } else {
                $Background = "/".$Background."?".$Profile["bg_version"];
                $Has_Background = true;
            }


            //GET META DESCRIPTION
            if (!empty($Profile["channel_description"])) {
                $Page_Description = htmlspecialchars(preg_replace( "/\r|\n/", "", limit_text($Profile["channel_description"],160)));
            } else {
                $Page_Description = "";
            }

            if (!empty($Profile["channel_tags"])) {
                $Page_Keywords = htmlspecialchars(preg_replace( "/\r|\n/", "", limit_text($Profile["channel_tags"],100)));
            } else {
                $Page_Keywords = "";
            }


            if (strpos($Profile["avatar"],"u=") !== false) {
                $Avatar = str_replace("u=","",$Profile["avatar"]);
                $Folder = "avt";
            } elseif (!empty($Profile["avatar"])) {
                $Avatar = $Profile["avatar"];
                $Upload = false;
                $Folder = "thmp";
            } else {
                $Upload = false;
                $Folder = "";
            }


            if (empty($Folder)) {
                $Avatar = "/img/no.png";
            } else {
                $Avatar = "/usfi/$Folder/$Avatar.jpg";
            }


            require_once "_templates/profile3_structure.php";


        } else {
			if ($Profile["banned"] == 1) {
				$Ban_Reasons = [];
				preg_match_all("/\[(\d+?)\]/", $Profile["ban_reasons"], $matches);
				if (count($matches) > 0) {
					foreach($matches[1] as $m) {
						$Reason = $DB->execute("SELECT reason FROM ban_reasons WHERE id = :ID", true, [":ID" => $m]);
						if ($DB->RowNum > 0)
							$Ban_Reasons[] = $Reason["reason"] . ".";
					}
				}
				
				if (count($Ban_Reasons) > 0) {
					$Banned_Header = "$Profile[displayname] has been banned for the following reasons:<br><div style=\"font-size:12px; margin: 0 200px;\">" . implode("<br>", $Ban_Reasons) . "</div>";
				} else {
				    if ($Profile["displayname"] != "banned") {
                        $Banned_Header = "$Profile[displayname] has been banned!";
                    } else {
                        $Banned_Header = "$Profile[displayname] has not been banned!";
                    }
				}
				
				if ($Profile["channel_version"] == 1) {
                    //CHECK IF FRIENDS
                    $Friend_Status = $DB->execute("SELECT status, by_user FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :OWNER) OR (friend_1 = :OWNER AND friend_2 = :USERNAME)", true,
                        [
                            ":USERNAME"    => $_USER->username,
                            ":OWNER"       => $Profile["username"]
                        ]);

                    if ($DB->RowNum > 0) {
                        $Status = $Friend_Status["status"];
                        $By     = $Friend_Status["by_user"];
                        if ($Status == 0) {
                            if ($By === $_USER->username) {
                                $Is_Friends = 2;
                            } else {
                                $Is_Friends = 3;
                            }
                        } else {
                            $Is_Friends = true;
                        }
                    } else {
                        $Is_Friends = false;
                    }

                    //CHECK IF BLOCKED
                    $Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = '$_USER->username' AND blocked = '$OWNER_USERNAME') OR (blocker = '$OWNER_USERNAME' AND blocked = '$_USER->username')", true);
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

                    $Is_Subscribed = $_USER->is_subscribed_to($Profile["username"]);

                    $Page_File = "Banned";
                    $Is_OWNER  = false;

                    $Page_Title       = $Profile["displayname"]." - Banned - VidLii";
                    $Page_Description = "This user has been banned!";
                    $Page_Keywords = "banned";

                    $Player = false;

                    $Profile["bg"] = "ffffff";
                    $Profile["nav"] = "89857F";
                    $Profile["h_head"] = "666666";
                    $Profile["h_head_fnt"] = "ffffff";
                    $Profile["h_in"] = "eeeeee";
                    $Profile["h_in_fnt"] = "6d6d6d";
                    $Profile["n_head"] = "666666";
                    $Profile["n_head_fnt"] = "ffffff";
                    $Profile["n_in"] = "ffffff";
                    $Profile["n_in_fnt"] = "000000";
                    $Profile["links"] = "89857F";
                    $Profile["b_avatar"] = "999999";
                    $Profile["avt_radius"] = 0;
                    $Profile["chn_radius"] = 0;
                    $HightLight_Trans = 1;
                    $Normal_Trans = 1;
                    $Has_Background = false;

                    require_once "_templates/profile_structure.php";
                } elseif ($Profile["channel_version"] == 2) {

                    //CHECK IF FRIENDS
                    $Friend_Status = $DB->execute("SELECT status, by_user FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :OWNER) OR (friend_1 = :OWNER AND friend_2 = :USERNAME)", true,
                        [
                            ":USERNAME"    => $_USER->username,
                            ":OWNER"       => $Profile["username"]
                        ]);

                    if ($DB->RowNum > 0) {
                        $Status = $Friend_Status["status"];
                        $By     = $Friend_Status["by_user"];
                        if ($Status == 0) {
                            if ($By === $_USER->username) {
                                $Is_Friends = 2;
                            } else {
                                $Is_Friends = 3;
                            }
                        } else {
                            $Is_Friends = true;
                        }
                    } else {
                        $Is_Friends = false;
                    }

                    //CHECK IF BLOCKED
                    $Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = '$_USER->username' AND blocked = '$OWNER_USERNAME') OR (blocker = '$OWNER_USERNAME' AND blocked = '$_USER->username')", true);
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

                    $Is_Subscribed = $_USER->is_subscribed_to($Profile["username"]);

                    $Page_File = "Banned";
                    $Is_OWNER  = false;

                    $Page_Title       = $Profile["displayname"]." - Banned - VidLii";
                    $Page_Description = "This user has been banned!";
                    $Page_Keywords = "banned";

                    $Player = false;

                    $Profile["bg"] = "ffffff";
                    $Profile["nav"] = "89857F";
                    $Profile["h_head"] = "999999";
                    $Profile["h_head_fnt"] = "0000CC";
                    $Profile["h_in"] = "eeeeee";
                    $Profile["h_in_fnt"] = "000000";
                    $Profile["n_head"] = "666666";
                    $Profile["n_head_fnt"] = "000000";
                    $Profile["n_in"] = "EEEEFF";
                    $Profile["n_in_fnt"] = "333333";
                    $Profile["links"] = "0000CC";
                    $Profile["b_avatar"] = "999999";
                    $Profile["avt_radius"] = 4;
                    $Profile["chn_radius"] = 5;
                    $HightLight_Trans = 1;
                    $Normal_Trans = 1;
                    $Has_Background = false;


                    require_once "_templates/profile2_structure.php";
                } elseif ($Profile["channel_version"] == 3) {
                    //CHECK IF FRIENDS
                    $Friend_Status = $DB->execute("SELECT status, by_user FROM friends WHERE (friend_1 = :USERNAME AND friend_2 = :OWNER) OR (friend_1 = :OWNER AND friend_2 = :USERNAME)", true,
                        [
                            ":USERNAME"    => $_USER->username,
                            ":OWNER"       => $Profile["username"]
                        ]);

                    if ($DB->RowNum > 0) {
                        $Status = $Friend_Status["status"];
                        $By     = $Friend_Status["by_user"];
                        if ($Status == 0) {
                            if ($By === $_USER->username) {
                                $Is_Friends = 2;
                            } else {
                                $Is_Friends = 3;
                            }
                        } else {
                            $Is_Friends = true;
                        }
                    } else {
                        $Is_Friends = false;
                    }

                    //CHECK IF BLOCKED
                    $Blocked = $DB->execute("SELECT blocker, blocked FROM users_block WHERE (blocker = '$_USER->username' AND blocked = '$OWNER_USERNAME') OR (blocker = '$OWNER_USERNAME' AND blocked = '$_USER->username')", true);
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

                    $Is_Subscribed = $_USER->is_subscribed_to($Profile["username"]);

                    $Page_File = "Banned";
                    $Is_OWNER  = false;

                    $Profile["bg"] = "f9f9f9";
                    $Profile["avatar"] = "a";

                    $Page_Title       = $Profile["displayname"]." - Banned - VidLii";
                    $Page_Description = "This user has been banned!";
                    $Page_Keywords = "banned";

                    $Player = false;

                    require_once "_templates/profile3_structure.php";
                }

			} else {
				notification("This user has voluntarily terminated their own account!","/","red");
			}
        }
    } else {
        notification("User not found!","/","red");
    }
} else {
    redirect("/");
}
}