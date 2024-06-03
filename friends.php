<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


$_PAGINATION    = new Pagination(15,10);

$Info           = $_USER->get_profile();

$Invites        = $DB->execute("SELECT count(*) as amount FROM friends WHERE (by_user <> :USERNAME) AND (friend_1 = :USERNAME OR friend_2 = :USERNAME) AND status = 0", true, [":USERNAME" => $_USER->username])["amount"];

if (isset($_GET["list"])) {
    $_PAGINATION        = new Pagination(15,10);
    $LIMIT              = "LIMIT $_PAGINATION->From, $_PAGINATION->To";
    $_PAGINATION->Total = $Info["friends"];
} else {
    $LIMIT = "";
}


$Friends        = $DB->execute("SELECT users.username, users.displayname, users.avatar, users.subscriptions, users.subscribers, users.video_views, users.friends, users.videos_watched, users.last_login FROM users INNER JOIN friends ON friends.friend_1 = users.username OR friends.friend_2 = users.username WHERE (friends.friend_1 = :USERNAME OR friends.friend_2 = :USERNAME) AND friends.status = 1 AND users.username <> :USERNAME $LIMIT", false, [":USERNAME" => $_USER->username]);
$Friends_Amount = $Info["friends"];

if (!isset($_GET["list"]) && $Friends_Amount > 0) {
    $Friends_Array = [];

    foreach($Friends as $Array) {
        $Friends_Array[] = $Array["username"];
    }

    $Avatar_Array = [];

    foreach ($Friends as $Avatar) {
        $Avatar_Array[$Avatar["username"]] = $Avatar["avatar"];
    }

    $SQL_USERS = sql_IN_fix($Friends_Array);
    $SELECT = "SELECT 'bulletin' as type_name, by_user as id, content, date as date, '' as title, 'a' as video_by, 'a' as video_desc FROM bulletins WHERE by_user IN ($SQL_USERS) ";
    $SELECT .= "UNION ALL SELECT 'comment' as type_name, videos.url, video_comments.comment, video_comments.date_sent as date, videos.title as title, video_comments.by_user as video_by, videos.description as video_desc FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE by_user IN ($SQL_USERS) ";
    $SELECT .= "UNION ALL SELECT 'favorite' as type_name, videos.url, videos.description as comment, video_favorites.date as date, videos.title as title, video_favorites.favorite_by as video_by, 'a' as video_desc FROM video_favorites INNER JOIN videos ON video_favorites.url = videos.url WHERE favorite_by IN ($SQL_USERS) ";
    $SELECT .= "UNION ALL SELECT 'friend' as type_name, friend_1, friend_2, sent_on, '' as title, 'a' as video_by, 'a' as video_desc FROM friends as date WHERE (friend_1 IN ($SQL_USERS) OR friend_2 IN ($SQL_USERS)) AND status = 1 ";

    $Recent_Activity = $DB->execute("$SELECT ORDER BY date DESC LIMIT 25");

	function find_displayname($index, $var) {
		global $Friends, $Recent_Activity;
		for ($i=0; $i < count($Friends); $i++) {
			if ($Recent_Activity[$index][$var] == $Friends[$i]["username"]) {
				$Recent_Activity[$index]["displayname"] = $Friends[$i]["displayname"];
				break;
			}
		}
	}
	
	foreach ($Recent_Activity as $i => $ra) {
		if ($ra["type_name"] == "friend") {
			$Username = $DB->execute("SELECT displayname FROM users WHERE username='".$Recent_Activity[$i]["id"]."' OR username='".$Recent_Activity[$i]["content"]."' LIMIT 1", true,
                                    [
                                        ":ID"       => $Recent_Activity[$i]["id"],
                                        ":USERNAME" => $Recent_Activity[$i]["content"]
                                    ]);
			
			$Recent_Activity[$i]["id_name"]      = $Username["displayname"];
			$Recent_Activity[$i]["content_name"] = $Username["displayname"];
		} elseif ($ra["type_name"] == "bulletin") {
			find_displayname($i, "id");
		} elseif ($ra["type_name"] == "comment") {
			find_displayname($i, "video_by");
		} elseif ($ra["type_name"] == "favorite") {
			find_displayname($i, "video_by");
		}
	}
}



$_PAGE->set_variables(array(
    "Page_Title"        => "Friends - VidLii",
    "Page"              => "Friends",
    "Page_Type"         => "Community",
    "Show_Search"       => false
));
require_once "_templates/page_structure.php";
