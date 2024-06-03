<?php
require_once "_includes/init.php";

if (isset($_POST["upload_theme"]) && $_USER->logged_in) {
	//throw new Exception("Error");
	$_GUMP->validation_rules(array(
		"theme_title"           => "required|max_len,100|min_len,1",
		"theme_description"     => "required|max_len,1100"
	));

	$_GUMP->filter_rules(array(
		"theme_title"           => "trim|NoHTML",
		"theme_description"     => "trim|NoHTML"
	));

	$Validation = $_GUMP->run($_POST);
	if ($Validation) {
		$Title          = $Validation["theme_title"];
		$Description    = $Validation["theme_description"];
		$Category       = $Validation["theme_category"];
		$Logged         = $Validation["theme_logged"];
		$Header         = $Validation["theme_header"];


		if (isset($Validation["chrome"]))   { $Chrome   = 1; } else { $Chrome   = 0; }
		if (isset($Validation["firefox"]))  { $Firefox  = 1; } else { $Firefox  =  0; }
		if (isset($Validation["edge"]))     { $Edge     = 1; } else { $Edge     = 0; }
		if (isset($Validation["internet"])) { $Internet = 1; } else { $Internet = 0; }
		if (isset($Validation["opera"]))    { $Opera    = 1; } else { $Opera    = 0; }

		if ($Category < 1 || $Category > 4) {
			redirect("/");
		}

		if ($Logged < 1 || $Logged > 3) {
			redirect("/");
		}

		if ($Header < 1 || $Header > 3) {
			redirect("/");
		}

		$URL = random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_",11);


		if (!empty($_FILES["main_picture"]["name"]) && !empty($_FILES["secondary_picture"]["name"]) && !empty($_FILES["css_file"]["name"])) {
			$Main_Size      = convert_filesize($_FILES["main_picture"]["size"],"kb");
			$Second_Size    = convert_filesize($_FILES["secondary_picture"]["size"],"kb");
			$CSS_Size       = convert_filesize($_FILES["css_file"]["size"],"kb");

			if ($Main_Size > 1000 || $Second_Size > 1000) {
				notification("Images must be under 500KB!","/themes?upload","red"); exit();
			}

			if ($CSS_Size > 50) {
				notification("The CSS File must be under 25KB!","/themes?upload","red"); exit();
			}
			
			$Uploader = new upload($_FILES["main_picture"]);
			$Uploader->file_new_name_body = $URL."_1";
			$Uploader->image_resize = true;
			$Uploader->file_overwrite          = true;
			$Uploader->image_x                 = 230;
			$Uploader->image_y                 = 200;
			$Uploader->image_background_color  = '#000000';
			$Uploader->image_convert           = 'jpg';
			$Uploader->image_ratio_fill        = false;
			$Uploader->file_max_size           = 1000000;
			$Uploader->jpeg_quality            = 45;
			$Uploader->allowed                 = array('image/jpeg','image/pjpeg','image/png','image/bmp','image/x-windows-bmp');
			$Uploader->process("usfi/img/");

			if (!$Uploader->processed) {
                notification("Something went wrong!","/themes?upload","red"); exit();
			}

			$Uploader = new upload($_FILES["secondary_picture"]);
			$Uploader->file_new_name_body = $URL."_2";
			$Uploader->image_resize = true;
			$Uploader->file_overwrite          = true;
			$Uploader->image_x                 = 230;
			$Uploader->image_y                 = 200;
			$Uploader->image_background_color  = '#000000';
			$Uploader->image_convert           = 'jpg';
			$Uploader->image_ratio_fill        = false;
			$Uploader->file_max_size           = 1000000;
			$Uploader->jpeg_quality            = 45;
			$Uploader->allowed                 = array('image/jpeg','image/pjpeg','image/png','image/bmp','image/x-windows-bmp');
			$Uploader->process("usfi/img/");

			if (!$Uploader->processed) {
                notification("Something went wrong!","/themes?upload","red"); exit();
			}

			$CSS_Type = pathinfo($_FILES["css_file"]["name"], PATHINFO_EXTENSION);
			if (strtolower($CSS_Type) == "css") {
				if (move_uploaded_file($_FILES["css_file"]["tmp_name"],"usfi/tmp/$URL.css")) {
					rename("usfi/tmp/" . $URL . ".css", "usfi/css/" . $URL . ".css");
                    $File = "usfi/css/". $URL .".css";

                    $CSS = file_get_contents("usfi/css/$URL.css");

                    $file_handle = fopen($File, 'w');
                    fwrite($file_handle, str_replace("<?php","",str_replace("<?","",str_replace("<?=","",$CSS))));
                    fclose($file_handle);
				} else {
					notification("Something went wrong!","/themes?upload","red"); exit();
				}
			} else {
				notification("You must upload a .css file!","/themes?upload","red"); exit();
			}


		} else {
			notification("You must upload a main image, secondary image and a CSS File!","/themes?upload","red");
		}

		$DB->modify("INSERT INTO themes VALUES(:URL, :TITLE, :DESCRIPTION, :CATEGORY, :LOGGED, :HEADER, :CHROME, :FIREFOX, :EDGE, :INTERNET, :OPERA, :UPLOADER, NOW(), 0, 0)",
                   [
                       ":URL"           => $URL,
                       ":TITLE"         => $Title,
                       ":DESCRIPTION"   => $Description,
                       ":CATEGORY"      => $Category,
                       ":LOGGED"        => $Logged,
                       ":HEADER"        => $Header,
                       ":CHROME"        => $Chrome,
                       ":FIREFOX"       => $Firefox,
                       ":EDGE"          => $Edge,
                       ":INTERNET"      => $Internet,
                       ":OPERA"         => $Opera,
                       ":UPLOADER"      => $_USER->username
                   ]);
		if ($DB->RowNum == 1) {
			notification("Theme successfully submitted. Now it just needs to be approved!","/themes","green"); exit();
		}

	}
}


if (isset($_GET["a"]) && strlen($_GET["a"]) < 30) {

    if ($_THEMES->has_installed_theme($_GET["a"])) {
        $_THEMES->uninstall_theme($_GET["a"]);
        if (count($_THEMES->Themes) > 0) {

            notification("Theme got successfully uninstalled!","/themes?your=1","green"); exit();

        } else {

            notification("Theme got successfully uninstalled!","/themes","green"); exit();

        }

    } else {
        $_THEMES->install_theme($_GET["a"]);
        $DB->modify("UPDATE themes SET installs = installs + 1 WHERE url = :URL", [":URL" => $_GET["a"]]);
        notification("Theme got successfully installed!","/themes?your=1","green"); exit();

    }

}


if (isset($_GET["accept"]) && ($_USER->Is_Mod || $_USER->Is_Admin)) {
	$DB->modify("UPDATE themes SET accepted = 1 WHERE url = :URL", [":URL" => $_GET["accept"]]);
	notification("Theme successfully accepted!","/themes","green"); exit();
}

if (!isset($_GET["t"])) {
    if (isset($_GET["search"]) && !empty($_GET["search"]) && mb_strlen($_GET["search"]) <= 128) {
        $Amount = $DB->execute("SELECT count(url) as amount FROM themes WHERE themes.accepted = 1 AND MATCH(title,description) AGAINST (:SEARCH)", true, [":SEARCH" => $_GET["search"]])["amount"];
    } else {
        $Amount = $DB->execute("SELECT count(url) as amount FROM themes WHERE themes.accepted = 1", true)["amount"];
    }
    $_PAGINATION        = new Pagination(10,50);
    $_PAGINATION->Total = $Amount;
    if (isset($_GET["search"]) && !empty($_GET["search"]) && mb_strlen($_GET["search"]) <= 128) {
        $Themes = $DB->execute("SELECT themes.*, users.displayname as owner FROM themes INNER JOIN users ON users.username = themes.owner WHERE themes.accepted = 1 AND MATCH(title,description) AGAINST (:SEARCH) LIMIT $_PAGINATION->From, $_PAGINATION->To", false, [":SEARCH" => $_GET["search"]]);
        if ($DB->RowNum == 0) {
            notification("No themes could be found!","/themes"); exit();
        }
    } else {
        $Themes = $DB->execute("SELECT themes.*, users.displayname as owner FROM themes INNER JOIN users ON users.username = themes.owner WHERE themes.accepted = 1 ORDER BY themes.upload_date DESC LIMIT $_PAGINATION->From, $_PAGINATION->To");
    }



    if (isset($_GET["your"]) && $_THEMES->has_installed_themes()) {

        $Installed_Themes = $_THEMES->themes_array();

    } else {

        $Installed_Themes = false;

    }

    if (isset($_GET["your"]) && $_USER->logged_in) {
        $Your_Themes = $DB->execute("SELECT themes.*, users.displayname as owner FROM themes INNER JOIN users ON themes.owner = users.username WHERE themes.owner = :OWNER", false, [":OWNER" => $_USER->username]);
        if ($DB->RowNum == 0) {
            $Your_Themes = false;
        }
    } elseif (isset($_GET["your"]) && !$_USER->logged_in) {
        $Your_Themes = false;
    }

    if (isset($_GET["delete"]) && $_USER->logged_in) {
        $Delete_Theme = $DB->execute("SELECT url,owner FROM themes WHERE url = :URL", true, [":URL" => $_GET["delete"]]);
        if ($DB->RowNum == 1) {

            if ($Delete_Theme["owner"] == $_USER->username || $_USER->Is_Admin || $_USER->Is_Mod) {
                $URL = $Delete_Theme["url"];

                $DB->modify("DELETE FROM themes WHERE url = :URL", [":URL" => $URL]);

                if ($DB->RowNum == 1) {
                    unlink("usfi/img/" . $URL . "_1.jpg");
                    unlink("usfi/img/" . $URL . "_2.jpg");
                    unlink("usfi/css/". $URL .".css");
                    if ($_USER->username == $Delete_Theme["owner"]) {
                        notification("Theme successfully deleted!","/themes?your=1","green"); exit();
                    } else {
                        notification("Theme successfully deleted!","/themes","green"); exit();
                    }
                } else {
                    header("location: /themes"); exit();
                }
            }
        } else {
            header("location: /themes"); exit();
        }
    }


	if ($_USER->logged_in && ($_USER->Is_Mod || $_USER->Is_Admin)) {
		$Accept = $DB->execute("SELECT * FROM themes WHERE accepted = 0 ORDER BY upload_date DESC");
		if ($DB->RowNum == 0) {
            unset($Accept);
		}
	}
} elseif (strlen($_GET["t"]) < 30) {
	$Theme = $DB->execute("SELECT t.*, u.displayname FROM themes t, users u WHERE t.url = :URL AND u.username = t.owner", true, [":URL" => $_GET["t"]]);
	if ($DB->RowNum == 1) {
        if ($_USER->logged_in || $Theme["accepted"] == 1) {
            if ($_USER->username != $Theme["owner"] && !$_USER->Is_Admin && !$_USER->Is_Mod && $Theme["accepted"] == 0) {
                redirect("/themes"); exit();
            }
        } else {
            redirect("/themes"); exit();
        }
	} else {
		redirect("/themes"); exit();
	}

}

$_PAGE->set_variables(array(
	"Page_Title"        => "Themes - VidLii",
	"Page"              => "Themes",
	"Page_Type"         => "Home",
	"Show_Search"       => false
));
require_once "_templates/page_structure.php";
