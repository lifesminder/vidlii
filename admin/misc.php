<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod) && isset($_SESSION["admin_panel"])) {
        $Page_Title = "Misc";
        $Page = "misc";

        $Logo = $DB->execute("SELECT value FROM settings WHERE name = 'logo'", true)["value"];

        if (isset($_POST["save_logo"])) {


            if ($_POST["logo"] == 0) {
                $DB->modify("UPDATE settings SET value = 0 WHERE name = 'logo'");
                @unlink($_SERVER["DOCUMENT_ROOT"]."/img/$Logo.png");
                notification("Logo changed to default!","/admin/misc","green"); exit();
            } else {
                if (isset($_FILES["logo_file"])) {
                    $ID = random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",4);
                    $Uploader = new upload($_FILES["logo_file"]);
                    $Uploader->file_new_name_body      = $ID;
                    $Uploader->image_resize            = true;
                    $Uploader->file_overwrite          = true;
                    $Uploader->image_x                 = 500;
                    $Uploader->image_y                 = 196;
                    $Uploader->image_convert           = 'png';
                    $Uploader->image_ratio_fill        = false;
                    $Uploader->file_max_size           = 5000000;
                    $Uploader->jpeg_quality            = 95;
                    $Uploader->allowed                 = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/x-windows-bmp');
                    $Uploader->process($_SERVER["DOCUMENT_ROOT"]."/img/");
                    if ($Uploader->processed) {
                        $DB->modify("UPDATE settings SET value = '$ID' WHERE name = 'logo'");
                        notification("Logo changed!","/admin/misc","green"); exit();
                    }
                } else {
                    notification("Nothing changed!","/admin/misc","red"); exit();
                }
            }
        }

        if (isset($_POST["save_pages"])) {
            if (isset($_POST["signup"])) { $Sign_Up = 1; } else { $Sign_Up = 0; }
            if (isset($_POST["signin"])) { $Sign_In = 1; } else { $Sign_In = 0; }
            if (isset($_POST["uploader"])) { $Uploader = 1; } else { $Uploader = 0; }
            if (isset($_POST["videos"])) { $Videos = 1; } else { $Videos = 0; }
            if (isset($_POST["channels"])) { $Channels = 1; } else { $Channels = 0; }

            $DB->modify("UPDATE settings SET value = $Sign_Up WHERE name = 'signup'");
            $DB->modify("UPDATE settings SET value = $Sign_In WHERE name = 'login'");
            $DB->modify("UPDATE settings SET value = $Uploader WHERE name = 'uploader'");
            $DB->modify("UPDATE settings SET value = $Videos WHERE name = 'videos'");
            $DB->modify("UPDATE settings SET value = $Channels WHERE name = 'channels'");
            notification("Changed!","/admin/misc","green"); exit();
        }

        if (isset($_POST["save_text"])) {
            if (mb_strpos($_POST["guidelines"],"<script>") !== false) { notification("You cannot use scripts!","/admin/misc"); exit(); }
            $Guidelines = $_POST["guidelines"];
            
            if (mb_strpos($_POST["help"],"<script>") !== false) { notification("You cannot use scripts!","/admin/misc"); exit(); }
            $Help = $_POST["help"];

            $DB->modify("UPDATE settings SET value = :VALUE WHERE name = 'guidelines'", [":VALUE" => $Guidelines]);

            if (mb_strpos($_POST["top_message"],"<script>") !== false) { notification("You cannot use scripts!","/admin/misc"); exit(); }
            $Top_Text = htmlspecialchars_decode($_POST["top_message"]);

            $DB->modify("UPDATE settings SET value = :VALUE WHERE name = 'top_text'", [":VALUE" => $Top_Text]);
            $DB->modify("UPDATE settings SET value = :VALUE WHERE name = 'help'", [":VALUE" => $Help]);
            
            notification("Changed!","/admin/misc","green"); exit();
        }

        $Guidelines = $DB->execute("SELECT value FROM settings WHERE name = 'guidelines'", true)["value"];
        
        $Help = $DB->execute("SELECT value FROM settings WHERE name = 'help'", true)["value"];

        $Sign_Up = $DB->execute("SELECT value FROM settings WHERE name = 'signup'", true)["value"];

        $Sign_In = $DB->execute("SELECT value FROM settings WHERE name = 'login'", true)["value"];

        $Uploader = $DB->execute("SELECT value FROM settings WHERE name = 'uploader'", true)["value"];

        $Channels = $DB->execute("SELECT value FROM settings WHERE name = 'channels'", true)["value"];

        $Videos = $DB->execute("SELECT value FROM settings WHERE name = 'videos'", true)["value"];

        $Top_Message = $DB->execute("SELECT value FROM settings WHERE name = 'top_text'", true)["value"];

        require_once "_templates/admin_structure.php";
    } elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
        redirect("/admin/login"); die();
    } else {
        redirect("/");
    }