<?php
require_once "_includes/init.php";


function format_float($f) {
	return round($f * 100) / 100;
}

if (file_exists("usfi/bner/$_USER->username.png")) {
    $Has_Channel_Banner = true;
	$Banner_Links = $DB->execute("SELECT links FROM channel_banners WHERE username = :USERNAME LIMIT 1", true, [":USERNAME" => $_USER->username]);
	if ($DB->RowNum == 1) {
		$Banner_Links = json_decode($Banner_Links["links"], true);
	} else {
		$Banner_Links = [];
	}
	
	$Banner_Image = $_USER->username;
} else {
    $Has_Channel_Banner = false;
}

if (file_exists("usfi/wbner/$_USER->username.png")) {
    $Has_Banner = true;
} else {
    $Has_Banner = false;
}

if (isset($_POST["update_adsense"])) {
    $Update = $DB->modify("UPDATE users SET adsense = :ADSENSE WHERE username = :USERNAME AND adsense = ''",
                         [
                             ":USERNAME"    => $_USER->username,
                             ":ADSENSE"     => (string)$_POST["adsense"]
                         ]);
    if ($DB->RowNum == 1)
    notification("Your adsense ads will now appear next to your videos!", "/partner_settings", "green"); exit();
}

if (isset($_POST["channel_banner_action"])) {
	switch($_POST["channel_banner_action"]) {
		case "upload": // Upload
			$Uploader = new upload($_FILES["channel_page_banner"]);
			$Uploader->file_new_name_body = $_USER->username;
			$Uploader->image_resize = true;
			$Uploader->file_overwrite          = true;
			$Uploader->image_max_height        = 150;
			$Uploader->image_max_width         = 1000;
			$Uploader->image_min_height        = 150;
			$Uploader->image_min_width         = 1000;
			$Uploader->image_x                 = 1000;
			$Uploader->image_y                 = 150;
			$Uploader->image_convert           = 'png';
			$Uploader->file_max_size           = 300555;
			$Uploader->png_compression         = 9;
			$Uploader->allowed                 = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/x-windows-bmp');
			$Uploader->process("usfi/bner/");
			if ($Uploader->processed) {
				$DB->modify("INSERT INTO channel_banners SET username = :USERNAME, links = '[]'", [":USERNAME" => $_USER->username]);
				if ($DB->RowNum == 1) {
                    $DB->modify("UPDATE users SET banner_version = banner_version + 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
					notification("Channel Page Banner got successfully uploaded", "/partner_settings", "green"); exit();
				}
			}
			
			notification("The uploaded image doesn't match the stipulations!", "/partner_settings", "red"); exit();
		case "save": // Save
			$new = [];
			$links = json_decode($_POST["channel_banner_links"], true);

			// Make sure JSON is escaped
			foreach($links as $l) {
				$new[] = array(
					"href"      => $l["href"],
					"width"     => round($l["width"], 2)."",
					"height"    => round($l["height"], 2)."",
					"left"      => round($l["left"], 2)."",
					"top"       => round($l["top"], 2).""
				);
			}
			
			$links = json_encode($new);
			
			// Update Database
			$DB->modify("UPDATE channel_banners SET links = :LINKS WHERE username = :USERNAME",
                       [
                           ":USERNAME" => $_USER->username,
                           ":LINKS"    => $links
                       ]);

			if ($DB->RowNum == 1) {
				notification("Channel Page Banner got successfully uploaded!", "/partner_settings", "green"); exit();
			}
			
			break;
		default: // Delete
			if (unlink("usfi/bner/$_USER->username.png")) {
				$Update = $DB->modify("DELETE FROM channel_banners WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
				if ($DB->RowNum == 1) {
					notification("Channel Page Banner got successfully uploaded!", "/partner_settings", "green"); exit();
				}
			}
	}
	
	notification("Something went wrong.", "/partner_settings", "red");
}

if (isset($_POST["submit"])) {
    $Uploader = new upload($_FILES["watch_page_banner"]);
    $Uploader->file_new_name_body = $_USER->username;
    $Uploader->image_resize = true;
    $Uploader->file_overwrite          = true;
    $Uploader->image_max_height        = 50;
    $Uploader->image_max_width         = 340;
    $Uploader->image_min_height        = 50;
    $Uploader->image_min_width         = 340;
    $Uploader->image_x                 = 340;
    $Uploader->image_y                 = 50;
    $Uploader->image_background_color  = '#000000';
    $Uploader->image_convert           = 'png';
    $Uploader->file_max_size           = 300555;
    $Uploader->png_compression         = 9;
    $Uploader->allowed                 = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/x-windows-bmp');
    $Uploader->process("usfi/wbner/");
    if ($Uploader->processed) {
        notification("Watch Page Banner got successfully uploaded", "/partner_settings", "green"); exit();
    } else {
        notification("The uploaded image doesn't match the stipulations!", "/partner_settings", "red"); exit();
    }
}

if (isset($_POST["delete"])) {
    if (unlink("usfi/wbner/$_USER->username.png")) {
        notification("Watch Page Banner got successfully deleted", "/partner_settings", "green"); exit();
    }
}


//GET INFO
$Info = $_USER->get_profile();
$Banner_Version = $Info["banner_version"];


if (isset($Info)) {
    $Channel_Version = $Info["channel_version"];
} else {
    $Channel_Version = $Info["channel_version"];
}

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activation
//- Requires Partner
if (!$_USER->logged_in)         { redirect("/login"); exit();   }
if (!$_USER->Is_Activated)    { redirect("/"); exit();        }
if (!$_USER->Is_Partner)        { redirect("/"); exit();        }

$Account_Title = "Partner Settings";


$_PAGE->set_variables(array(
    "Page_Title"        => "Partner Settings - VidLii",
    "Page"              => "Partner",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/settings_structure.php";