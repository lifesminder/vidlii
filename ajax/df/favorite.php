<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_GET["v"])
if (!$_USER->logged_in)         { redirect("/login"); exit();   }
if (!isset($_GET["v"]))         { redirect("/"); exit();        }


$Video = new Video($_GET["v"],$DB);
$URL = $_GET["v"];

if ($URL !== false) {
    if (!$Video->favorited_by($_USER->username)) {
        $URL = $Video->exists(false);
        if ($URL !== false) {
            $_USER->favorite_video($URL);
        }
    } else {
        $_USER->remove_favorite($URL);
    }
}
redirect(previous_page());