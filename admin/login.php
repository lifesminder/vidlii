<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    if($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod) && !isset($_SESSION["admin_panel"])) {
        $_SESSION["admin_panel"] = true;
        redirect("/admin/dashboard");
    } elseif (isset($_SESSION["admin_panel"])) {
        redirect("/admin/dashboard");
    } else {
        redirect("/login");
    }
?>