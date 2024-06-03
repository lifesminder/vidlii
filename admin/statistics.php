<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
    
    if (!$_USER->Is_Admin) { redirect("/admin/login"); exit(); } 

    if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod) && isset($_SESSION["admin_panel"])) {
        $Users_Stats = $DB->execute("SELECT count(username) as amount, reg_date FROM users GROUP BY DATE(reg_date)");

        $Videos_Stats = $DB->execute("SELECT count(url) as amount, uploaded_on FROM videos GROUP BY DATE(uploaded_on)");

        $Video_Comments = $DB->execute("SELECT count(url) as amount, date_sent FROM video_comments GROUP BY DATE(date_sent)");

        $Channel_Comments = $DB->execute("SELECT count(id) as amount, date FROM channel_comments GROUP BY DATE(date)");

        $Video_Favorites = $DB->execute("SELECT count(url) as amount, date FROM video_favorites GROUP BY DATE(date)");

        $Video_Responses = $DB->execute("SELECT count(id) as amount, date FROM video_responses GROUP BY DATE(date)");

        $Page_Title = "Statistics";
        $Page = "statistics";
        require_once "_templates/admin_structure.php";
    } elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
        redirect("/admin/login"); die();
    } else {
        redirect("/");
    }