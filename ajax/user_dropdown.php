<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/_includes/init.php";


if (isset($_POST["search"]) && ctype_alnum($_POST["search"])) {
    $Word = $_POST["search"];
    $Search = $DB->execute("SELECT username FROM users WHERE username LIKE '$Word%' ORDER BY username ASC LIMIT 3");
    foreach($Search as $User) {
        $Username = $User["username"];
        echo "<a href='/admin/users?u=$Username'><li>".str_ireplace($Word,"<strong>$Word</strong>",$Username)."</li></a>";
    }
}