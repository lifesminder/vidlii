<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activation
//- Requires ($_GET["v"])
if (!$_USER->logged_in)         { redirect("/login"); exit();       }
if (!$_USER->Is_Activated)    { redirect("/"); exit();            }
if (!isset($_GET["v"]))         { redirect("/my_videos"); exit();   }

//Check if AJAX call
$ajaxCall = isset($_POST["ajax"]) && $_POST["ajax"] == "1";

if (!$ajaxCall) {
	$Categories = return_categories();
}

$Video = new Video($_GET["v"], $DB);
$URL = $Video->exists();

if (!$ajaxCall) {
	if ($URL !== false) {
		$Video->get_info();
		if ($Video->Info["uploaded_by"] !== $_USER->username) { notification("You don't have the permissions to edit this video!","/my_videos","red"); }
	} else {
		notification("This video doesn't exist!","/my_videos","red"); exit();
	}
} else {
	if ($URL === false) {
		die("0|Video doesn't exist!");
	}
}

if (file_exists("usfi/thmp/".$Video->Info["url"].".jpg")) {
    $Has_Thumbnail = true;
} else {
    $Has_Thumbnail = false;
}

if (file_exists("usfi/thmp/".$Video->Info["url"]."_.jpg")) {
    $Has_Custom_Thumbnail = true;
	if ($ajaxCall) $_POST["c_thmp"] = 1;
} else {
    $Has_Custom_Thumbnail = false;
	if ($ajaxCall) $_POST["c_thmp"] = 2;
}

if (isset($_POST["update_info"]) || $ajaxCall) {
	if (!$ajaxCall) {
		$_GUMP->validation_rules(array(
			"title"          => "required|max_len,101|min_len,1",
			"description"    => "max_len,1100",
			"tags"           => "max_len,260",
			"category"       => "required"
		));

		$_GUMP->filter_rules(array(
			"title"          => "trim|NoHTML",
			"description"    => "trim|NoHTML",
			"tags"           => "trim|NoHTML",
			"category"       => "trim"
		));

		$Validation = $_GUMP->run($_POST);
	}
	
    if ($Validation || $ajaxCall) {
		if (!$ajaxCall) {
			if ($Validation["category"] > 0 && $Validation["category"] <= 15) {
				$Category = $Validation["category"];
			} else {
				$Category = 1;
			}
		}

        if ($_POST["c_thmp"] != 0) {
			if (!($_POST["c_thmp"] == 1 && $Has_Custom_Thumbnail)) {
				for ($i=0; $i < 2; $i++) {
					if ($i == 0) {
						$DIR = "thmp";
						$WID = 256;
						$HEI = 144;
					} else {
						$DIR = "prvw";
						$WID = 856;
						$HEI = 480;
					}
					
					if ($Has_Custom_Thumbnail) {
						rename("usfi/$DIR/$URL.jpg", "usfi/$DIR/$URL.temp.jpg");
					} else {
						rename("usfi/$DIR/$URL.jpg","usfi/$DIR/".$URL."_.jpg");					
					}

					$Uploader = new upload($_FILES["c_thmp_uploader"]);
					$Uploader->file_new_name_body = $URL;
					$Uploader->image_resize = true;
					$Uploader->file_overwrite          = true;
					$Uploader->image_x                 = $WID;
					$Uploader->image_y                 = $HEI;
					$Uploader->image_background_color  = '#000000';
					$Uploader->image_convert           = 'jpg';
					$Uploader->file_max_size           = 1000000;
					$Uploader->image_ratio_crop        = true;
					$Uploader->jpeg_quality            = 75;
					$Uploader->allowed                 = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/x-windows-bmp');
					$Uploader->process("usfi/$DIR/");
					if (!$Uploader->processed) {
						if ($Has_Custom_Thumbnail) {
							rename("usfi/$DIR/$URL.temp.jpg","usfi/$DIR/$URL.jpg");
						} else {
							rename("usfi/$DIR/".$URL."_.jpg","usfi/$DIR/$URL.jpg");
						}
						
						$err = "Something went wrong while uploading the custom thumbnail!";
						if (!$ajaxCall) notification($err, "/edit_video?v=$URL", "red");
						else die("0|$err");
					} else {
						@unlink("usfi/$DIR/$URL.temp.jpg");
						if ($ajaxCall) die("1|/usfi/$DIR/$URL.jpg");
					}
				}
			}
        } elseif ($Has_Custom_Thumbnail && $_POST["c_thmp"] == 0) {
            unlink("usfi/thmp/$URL.jpg");
            rename("usfi/thmp/".$URL."_.jpg","usfi/thmp/$URL.jpg");
            rename("usfi/prvw/".$URL."_.jpg","usfi/prvw/$URL.jpg");
        }

		if (!$ajaxCall || !check_captcha_sp(4)) {
			if ($Video->Info["category"] != $Category) {
				$Most_Popular = 0;
			} else {
				$Most_Popular = (int)$Video->Info["most_popular"];
			}


            if ($_POST["privacy"] == 0)     { $Privacy = 0; }
            elseif ($_POST["privacy"] == 1) { $Privacy = 1; }
            elseif ($_POST["privacy"] == 2) { $Privacy = 2; }
         else                            { $Privacy = 0; }

            if ($Video->Info["privacy"] == 0 && ($Privacy == 1 || $Privacy == 2)) {
                $DB->modify("UPDATE users SET videos = videos - 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
            } elseif (($Video->Info["privacy"] == 1 || $Video->Info["privacy"] == 2) && $Privacy == 0) {
                $DB->modify("UPDATE users SET videos = videos + 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
            }

            if ($_POST["comments"] == 1)     { $Comments = 1; }
            elseif ($_POST["comments"] == 2) { $Comments = 2; }
            else                             { $Comments = 0; }

            if ($_POST["responses"] == 1)     { $Responses = 1; }
            elseif ($_POST["responses"] == 2) { $Responses = 2; }
            else                              { $Responses = 0; }

            if ($_POST["ratings"] == 1)     { $Ratings = 1; }
            else                            { $Ratings = 0; }

            if ($_POST["related"] == 1)     { $Related = 1; }
            else                            { $Related = 0; }

			$DB->modify("UPDATE videos SET s_related = $Related, s_comments = $Comments, s_responses = $Responses, s_ratings = $Ratings, privacy = $Privacy, most_popular = $Most_Popular, title = :TITLE, description = :DESCRIPTION, tags = :TAGS, category = :CATEGORY WHERE url = :URL AND uploaded_by = :USERNAME",
                       [":CATEGORY" => $Category, ":TITLE" => $Validation["title"], ":DESCRIPTION" => $Validation["description"], ":TAGS" => $Validation["tags"], ":URL" => $URL, ":USERNAME" => $_USER->username]);
			notification("Video successfully updated","/edit_video?v=$URL","green"); exit();
		}
    }
}

if ($ajaxCall) die("0|Something went wrong.");

//Check if Partner can upload video replacement
$Can_Change_Video = false;
if ($_USER->Is_Partner) {
	if ($Video->Info["status"] == 2 || $Video->Info["status"] == -2) {
		$check = $DB->execute("SELECT COUNT(*) as amount FROM converting WHERE url = :URL", true, [":URL" => $URL])["amount"];
		if ($check == 0) {
			$Can_Change_Video = true;
		}
	}
}

$Header = "Edit Video";

$_PAGE->set_variables(array(
    "Page_Title"        => "Edit Video - VidLii",
    "Page"              => "Edit",
    "Page_Type"         => "Videos",
    "Show_Search"       => false
));
require_once "_templates/videos_structure.php";