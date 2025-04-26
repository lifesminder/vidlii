<?php
    require_once "_includes/init.php";

    $api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);

    //REQUIREMENTS / PERMISSIONS
    //- Requires Login
    if(!$_USER->logged_in) {
        redirect("/login");
        exit();
    }

    $Info = $_USER->get_profile();
    if(isset($_POST["update_holiday"])) {
        $Snow = ($_POST["ch_privacy"] == 0) ? 0 : 1;
        $Mondo = (isset($_POST["ch_mondo"]) && $_POST["ch_mondo"] == 1) ? 1 : 0;
        $DB->modify("UPDATE users SET snow = $Snow, mondo = $Mondo WHERE username = '$_USER->username'");
        notification("Holiday settings updated!","/channel_setup","green"); exit();
    }

if (isset($_POST["update_channel"])) {
    $_GUMP->validation_rules(array(
        "ch_title"          => "max_len,80",
        "ch_description"    => "max_len,2600",
        "ch_tags"           => "max_len,270"
    ));

    $_GUMP->filter_rules(array(
        "ch_title"         => "trim|NoHTML",
        "ch_description"   => "trim|NoHTML",
        "ch_tags"          => "trim|NoHTML"
    ));

    $Validation = $_GUMP->run($_POST);

    if ($Validation) {
        $Channel_Title = (string)$Validation["ch_title"];
        $Channel_Description = (string)$Validation["ch_description"];
        $Channel_Tags = (string)$Validation["ch_tags"];
        $Type = (int)$_POST["channel_type"];
        $type_min = ($Info["is_admin"]) ? -1 : 0;

        if($Type >= $type_min && $Type <= 7) {
            $DB->modify("UPDATE users SET channel_type = '$Type', channel_title = :TITLE, channel_description = :DESCRIPTION, channel_tags = :TAGS WHERE username = :USERNAME",
                       [
                           ":TITLE"         => $Channel_Title,
                           ":DESCRIPTION"   => $Channel_Description,
                           ":TAGS"          => $Channel_Tags,
                           ":USERNAME"      => $_USER->username
                       ]);
            notification("Account successfully updated!","/channel_setup","green"); exit();
        }
    }
}

if (isset($_POST["update_privacy"])) {
	$can_friend = (int)$_POST["can_friend"];
	$ch_privacy = (int)$_POST["ch_privacy"];
	if ($can_friend != 1) $can_friend = 0;
	if ($ch_privacy != 1 && $ch_privacy != 2) $ch_privacy = 0;

	$DB->modify("UPDATE users SET privacy = :PRIVACY, can_friend= :CAN_FRIEND WHERE username = :USERNAME",
               [
                   ":PRIVACY"      => $ch_privacy,
                   ":CAN_FRIEND"   => $can_friend,
                   ":USERNAME"     => $_USER->username
               ]);
	notification("Account successfully updated!","/channel_setup","green"); exit();
}

//GET INFO
$Is_Uploaded_Avatar = (bool)($api->db("SELECT IF(avatar IS NULL or avatar = '', 'empty', avatar) as avatar from users where username = '".$_USER->username."'")["data"]["avatar"] != "empty");

if (isset($_POST["update_avatar"]) || isset($_POST["delete_avatar"])) {
    $_GUMP->validation_rules(array(
        "v_url"       => "max_len,128|valid_url"
    ));

    $_GUMP->filter_rules(array(
        "v_url"       => "trim"
    ));

    $Validation = $_GUMP->run($_POST);

    if ($Validation) {
        $user = $_USER->username;
        if(!empty($_FILES["avatar_upload"]["name"])) {
            /*
                NEW Avatar uploading logic, which includes getting rid of `usfi` save and placing it into DB instead.
                Avatar will be fetched via CDN newer modules.
            */
            $imgsize = getimagesize($_FILES["avatar_upload"]["tmp_name"]);
            if($imgsize) {
                $avatar = base64_encode(file_get_contents($_FILES["avatar_upload"]["tmp_name"]));
                $upload = $api->db("UPDATE users SET avatar = \"$avatar\" WHERE username = '$user'");
                if($upload["status"] == 1) {
                    notification("Avatar have been updated!", "/channel_setup", "green");
                } else {
                    notification($upload["message"], "/channel_setup", "red");
                }
            } else {
                notification("You must use a valid image", "/channel_setup", "red");
                exit();
            }
        } elseif (isset($_POST["delete_avatar"])) {
            $upload = $api->db("UPDATE users SET avatar = NULL WHERE username = '$user'");
            if($upload["status"] == 1) notification("Avatar have been removed!", "/channel_setup", "red");
            else notification($upload["message"], "/channel_setup", "red");
        } elseif (isset($_POST["v_url"]) && !file_exists("usfi/avt/$Avatar_FURL.jpg")) {
            if(!empty($Validation["v_url"])) {
                if(strpos($Validation["v_url"], "watch") !== false) {
                    // Since thumbnails of videos are now stored directly in DB, we simply fetch it, if it isn't empty
                    $URL = url_parameter($Validation["v_url"], "v");
                    $thumbnail = $api->db("SELECT thumbnail from videos where url = \"$URL\"");
                    if($thumbnail["count"] == 1) {
                        $thumbnail = $thumbnail["data"]["thumbnail"];
                        if(!empty($thumbnail)) {
                            $upload = $api->db("UPDATE users SET avatar = \"$thumbnail\" WHERE username = '$user'");
                            if($upload["status"] == 1) {
                                notification("Avatar have been updated!", "/channel_setup", "green");
                            } else {
                                notification($upload["message"], "/channel_setup", "red");
                            }
                        } else {
                            notification("Thumbnail of video doesn't exist", "/channel_setup", "red");
                        }
                    } else {
                        notification("Video doesn't exist", "/channel_setup", "red");
                    }
                } else {
                    $_PAGE->add_error(array("vidlii_av" => "You must use a vidlii video!"));
                }
            } else {
                $DB->modify("UPDATE users SET avatar = :AVATAR WHERE username = :USERNAME",
                           [
                               ":AVATAR"   => "",
                               ":USERNAME" => $_USER->username
                           ]);
                redirect("/channel_setup"); exit();
            }
        }
    }
}

if (isset($_POST["Apply_Filter"]) && $_POST["filter_type"] !== "0") {
    $URL = random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",11);
    $Uploader = new upload("usfi/avt/$Avatar_FURL.jpg");
    $Uploader->file_overwrite          = true;
    if ($_POST["filter_type"] == "1") {
        $Uploader->image_greyscale  = true;
    } elseif ($_POST["filter_type"] == "2") {
        $Uploader->image_negative   = true;
    } elseif ($_POST["filter_type"] == "3") {
        $Uploader->image_contrast   = 85;
    } elseif ($_POST["filter_type"] == "5") {
        $Uploader->image_flip       = 'h';
    } elseif ($_POST["filter_type"] == "4") {
        $Uploader->image_flip       = 'v';
    }
    $Uploader->image_convert        = 'jpg';
    $Uploader->file_new_name_body   = $URL;
    $Uploader->process("usfi/avt/");

    unlink("usfi/avt/$Avatar_FURL.jpg");
    /*
    $DB->modify("UPDATE users SET avatar = :AVATAR WHERE username = :USERNAME",
               [
                   ":AVATAR"    => "u=".$URL,
                   ":USERNAME"  => $_USER->username
               ]);
    */
    redirect("/channel_setup");


}

if (isset($Info)) {
    $Channel_Version = $Info["channel_version"];
} else {
    $Channel_Version = $Info["channel_version"];
}

$Account_Title = "Channel Setup";


$_PAGE->set_variables(array(
    "Page_Title"        => "Channel Setup - VidLii",
    "Page"              => "Main",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/settings_structure.php";
