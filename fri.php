<?php
require_once "_includes/init.php";

var_dump($DB->execute("SELECT friends.id, users.displayname, users.avatar FROM users FORCE INDEX (username) INNER JOIN friends FORCE INDEX (friend_1_3) ON ((friends.friend_1 = users.username AND friends.friend_1 <> :USERNAME) OR (friends.friend_2 = users.username AND friends.friend_2 <> :USERNAME)) WHERE (friends.friend_1 = :USERNAME OR friends.friend_2 = :USERNAME) AND friends.status = 1 ORDER BY friends.id DESC LIMIT 100", false, [":USERNAME" => "thevideogamer64"]));
