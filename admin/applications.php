<?php

    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
    if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod)) {
        $Page_Title="Partner Applications";
        $Page="applications";
        require_once "_templates/admin_structure.php";
    }

    elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
        redirect("/admin/login");
        die();
    }

    else {
        redirect("/");
    }
?>