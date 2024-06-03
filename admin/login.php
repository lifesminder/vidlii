<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod) && !isset($_SESSION["admin_panel"])) {


    if (isset($_POST["submit_password"])) {
        // Check validity of parameters
        if (strlen($_POST["user_password"]) <= 128 && strlen($_POST["panel_password"]) <= 128) {
            // Fetch user's password hash
            $Password = $DB->execute("SELECT password FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username])["password"];

            // Verify user password against password hash and provided admin password against configured admin password
            if (password_verify($_POST["user_password"],$Password)) { //  && $_POST["panel_password"] === ADMIN_PASSWORD
                // Allow access to panel, redirect to panel dashboard
                $_SESSION["admin_panel"] = true;
                redirect("/admin/dashboard"); exit();
            }
        }

        // If the above conditions aren't met, deny access
        notification("Incorrect!","/admin/login");
        exit();
    }

    $Page_Title = "Login";
    $Page = "login";
    require_once "_templates/admin_structure.php";

    } elseif (isset($_SESSION["admin_panel"])) {
        redirect("/admin/dashboard");
    } else {
        redirect("/"); die();
    }