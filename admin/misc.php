<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
    $api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);

    if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod)) {
        $Page_Title = "Misc";
        $Page = "misc";

        $Logo = $api->db("SELECT value FROM settings WHERE name = 'logo'");
        $Logo = ($Logo["count"] == 0) ? 0 : $Logo["data"]["value"];

        if (isset($_POST["save_logo"])) {
            $existence = $api->db("SELECT value FROM settings WHERE name = 'logo'");
            if ($_POST["logo"] == 0) {
                $update_logo = ($existence["count"] == 0) ? $api->db("INSERT INTO settings (name, value) values ('logo', 0)") : $api->db("UPDATE settings set value = 0 where name = 'logo'");
                @unlink($_SERVER["DOCUMENT_ROOT"]."/img/$Logo.png");
                notification("Logo changed to default!", "/admin/misc", "green"); exit();
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
                        $update_logo = ($existence["count"] == 0) ? $api->db("INSERT INTO settings (name, value) values ('logo', '$ID')") : $api->db("UPDATE settings set value = '$ID' where name = 'logo'");
                        notification("Logo changed!","/admin/misc","green"); exit();
                    }
                } else {
                    notification("Nothing changed!","/admin/misc","red"); exit();
                }
            }
        }

        if(isset($_POST["save_pages"])) {
            $Sign_Up = (isset($_POST["signup"])) ? 1 : 0;
            $Sign_In = (isset($_POST["signin"])) ? 1 : 0;
            $Uploader = (isset($_POST["uploader"])) ? 1 : 0;
            $Videos = (isset($_POST["videos"])) ? 1 : 0;
            $Channels = (isset($_POST["channels"])) ? 1 : 0;

            $update_signup = ($api->db("SELECT count(*) from settings where name = 'signup'")["data"]["count(*)"] == 0) ? $api->db("INSERT INTO settings (name, value) values ('signup', $Sign_Up)") : $api->db("UPDATE settings SET value = $Sign_Up WHERE name = 'signup'");
            $update_signin = ($api->db("SELECT count(*) from settings where name = 'login'")["data"]["count(*)"] == 0) ? $api->db("INSERT INTO settings (name, value) values ('login', $Sign_In)") : $api->db("UPDATE settings SET value = $Sign_In WHERE name = 'login'");
            $update_uploader = ($api->db("SELECT count(*) from settings where name = 'uploader'")["data"]["count(*)"] == 0) ? $api->db("INSERT INTO settings (name, value) values ('uploader', $Uploader)") : $api->db("UPDATE settings SET value = $Uploader WHERE name = 'uploader'");
            $update_videos = ($api->db("SELECT count(*) from settings where name = 'videos'")["data"]["count(*)"] == 0) ? $api->db("INSERT INTO settings (name, value) values ('videos', $Videos)") : $api->db("UPDATE settings SET value = $Videos WHERE name = 'videos'");
            $update_channels = ($api->db("SELECT count(*) from settings where name = 'channels'")["data"]["count(*)"] == 0) ? $api->db("INSERT INTO settings (name, value) values ('channels', $Channels)") : $api->db("UPDATE settings SET value = $Channels WHERE name = 'channels'");

            notification("Settings changed!", "/admin/misc", "green"); exit();
        }

        if (isset($_POST["save_text"])) {
            if(mb_strpos($_POST["top_message"],"<script>") !== false) {
                notification("You cannot use scripts!","/admin/misc"); exit();
            }
            $Top_Text = htmlspecialchars_decode($_POST["top_message"]);
            $update_top_text = ($api->db("SELECT count(*) from settings where name = 'top_text'")["data"]["count(*)"] == 0) ? $api->db("INSERT INTO settings (name, value) values ('top_text', \"$Top_Text\")") : $api->db("UPDATE settings SET value = \"$Top_Text\" WHERE name = 'top_text'");
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