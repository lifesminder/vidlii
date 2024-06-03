<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


if (isset($_POST["update_customization"])) {
    $_GUMP->validation_rules(array(
        "bgcolor"       => "required|is_hex",
        "navcolor"      => "required|is_hex",
        "hghdcolor"     => "required|is_hex",
        "hghdcolorfont" => "required|is_hex",
        "hgincolor"     => "required|is_hex",
        "hgincolorfont" => "required|is_hex",
        "nmhdcolor"     => "required|is_hex",
        "nmhdcolorfont" => "required|is_hex",
        "nmincolor"     => "required|is_hex",
        "nmincolorfont" => "required|is_hex",
        "lnkcolor"      => "required|is_hex",
        "avcolor"       => "required|is_hex",
        "conn_text"     => "max_len,32", 
        "ch_fnt"        => "required",
        "n_url"         => "max_len,128|valid_url",
        "s_url"         => "max_len,128|valid_url"
    ));

    $_GUMP->filter_rules(array(
        "bgcolor"       => "trim",
        "navcolor"      => "trim",
        "hghdcolor"     => "trim",
        "hghdcolorfont" => "trim",
        "hgincolor"     => "trim",
        "hgincolorfont" => "trim",
        "nmhdcolor"     => "trim",
        "nmhdcolorfont" => "trim",
        "nmincolor"     => "trim",
        "nmincolorfont" => "trim",
        "lnkcolor"      => "trim",
        "avcolor"       => "trim",
        "conn_text"     => "trim|NoHTML",
        "ch_fnt"        => "trim",
        "n_url"         => "trim",
        "s_url"         => "trim"
    ));

    $Validation = $_GUMP->run($_POST);

    if ($Validation) {
        $BG_COLOR         = str_replace("#","",$Validation["bgcolor"]);
        $NAV_COLOR        = str_replace("#","",$Validation["navcolor"]);
        $HG_HD_COLOR      = str_replace("#","",$Validation["hghdcolor"]);
        $HG_HD_COLOR_FONT = str_replace("#","",$Validation["hghdcolorfont"]);
        $HG_IN_COLOR      = str_replace("#","",$Validation["hgincolor"]);
        $HG_IN_COLOR_FONT = str_replace("#","",$Validation["hgincolorfont"]);
        $NM_HD_COLOR      = str_replace("#","",$Validation["nmhdcolor"]);
        $NM_HD_COLOR_FONT = str_replace("#","",$Validation["nmhdcolorfont"]);
        $NM_IN_COLOR      = str_replace("#","",$Validation["nmincolor"]);
        $NM_IN_COLOR_FONT = str_replace("#","",$Validation["nmincolorfont"]);
        $LINK_COLOR       = str_replace("#","",$Validation["lnkcolor"]);
        $AVATAR_COLOR     = str_replace("#","",$Validation["avcolor"]);
        $CONNECT          = $Validation["conn_text"];


        if (!empty($Validation["n_url"])) {
            $N_URL      = url_parameter($Validation["n_url"], "v");
            $URL        = $DB->execute("SELECT url FROM videos WHERE privacy <> 2 AND banned_uploader = 0 AND url = :URL AND status = 2", true, [":URL" => $N_URL]);
            if ($DB->RowNum == 0) {
                $N_URL  = "";
            } else {
                $N_URL = $URL["url"];
            }
        }  else {
            $N_URL = "";
        }

        if (!empty($Validation["s_url"])) {
            $S_URL      = url_parameter($Validation["s_url"], "v");
            $URL        = $DB->execute("SELECT url FROM videos WHERE privacy <> 2 AND banned_uploader = 0 AND url = :URL AND status = 2", true, [":URL" => $S_URL]);
            if ($DB->RowNum == 0) {
                $S_URL  = "";
            } else {
                $S_URL = $URL["url"];
            }
        } else {
            $S_URL = "";
        }


        if (isset($Validation["subscribers"]))          { $c_subscriber      = 1; } else { $c_subscriber = 0; }
        if (isset($Validation["featured"]))             { $c_featured        = 1; } else { $c_featured = 0; }
        if (isset($Validation["subscriptions"]))        { $c_subscriptions   = 1; } else { $c_subscriptions = 0; }
        if (isset($Validation["friends"]))              { $c_friends         = 1; } else { $c_friends = 0; }
        if (isset($Validation["videos"]))               { $c_videos          = 1; } else { $c_videos = 0; }
        if (isset($Validation["favorites"]))            { $c_favorites       = 1; } else { $c_favorites = 0; }
        if (isset($Validation["comments"]))             { $c_comments        = 1; } else { $c_comments = 0; }
        if (isset($Validation["custom"]))               { $c_custom          = 1; } else { $c_custom = 0; }
        if (isset($Validation["bg_fixed"]))             { $bg_fixed          = 1; } else { $bg_fixed = 0; }
        if (isset($Validation["bg_stretch"]))           { $bg_stretch        = 1; } else { $bg_stretch = 0; }
        if (isset($Validation["featured_channels"]))    { $Featured_Channels = 1; } else { $Featured_Channels = 0; }
        if (isset($Validation["recent"]))               { $Recent_Activity   = 1; } else { $Recent_Activity = 0; }
        if (isset($Validation["playlists"]))            { $Playlists         = 1; } else { $Playlists = 0; }


        if (isset($Validation["n_trans"]) && $Validation["n_trans"] >= 0 && $Validation["n_trans"] <= 100)          { $Normal_Trans     = (int)$Validation["n_trans"]; } else { $Normal_Trans = 0; }
        if (isset($Validation["h_trans"]) && $Validation["h_trans"] >= 0 && $Validation["h_trans"] <= 100)          { $Highlight_Trans  = (int)$Validation["h_trans"]; } else { $Highlight_Trans = 0; }
        if (isset($Validation["ch_fnt"]) && $Validation["ch_fnt"] >= 0 && $Validation["ch_fnt"] <= 6)               { $Channel_Font     = (int)$Validation["ch_fnt"]; } else { $Channel_Font = 0; }
        if (isset($Validation["chn_radius"]) && $Validation["chn_radius"] >= 0 && $Validation["chn_radius"] <= 9)   { $Channel_Radius   = (int)$Validation["chn_radius"]; } else { $Channel_Radius = 5; }
        if (isset($Validation["avt_radius"]) && $Validation["avt_radius"] >= 0 && $Validation["avt_radius"] <= 9)   { $Avatar_Radius    = (int)$Validation["avt_radius"]; } else { $Avatar_Radius = 4; }



        if ($Validation["bg_repeat"] >= 1 && $Validation["bg_repeat"] <= 4) {
            $bg_repeat      = (int)$Validation["bg_repeat"];
        }

        if ($Validation["bg_position"] >= 1 && $Validation["bg_position"] <= 4) {
            $bg_position    = (int)$Validation["bg_position"];
        }


        if (!empty($_FILES["bg_upload"]["name"])) {
            $Allowed_Types  = ["jpg","jpeg","gif","png","bmp"];
            $Image_Type     = pathinfo($_FILES["bg_upload"]["name"], PATHINFO_EXTENSION);

            if (convert_filesize($_FILES["bg_upload"]["size"],"kb") <= 500 && in_array(strtolower($Image_Type),$Allowed_Types)) {
                $File = @glob("usfi/bg/$_USER->username.*")[0];
                if ($File === NULL) {
                    move_uploaded_file($_FILES["bg_upload"]["tmp_name"],"usfi/bg/$_USER->username.$Image_Type");
                    $DB->modify("UPDATE users SET bg_version = bg_version + 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
                }
            }
        }


        $DB->modify("UPDATE users SET c_custom = :C_CUSTOM, avt_radius = :AVT_RADIUS, chn_radius = :CHN_RADIUS, featured_n_url = :N_URL, featured_s_url = :S_URL, c_playlists = :PLAYLISTS, font = :FONT, c_recent = :RECENT, c_featured_channels = :FEATURED_CHANNELS, h_trans = :HTRANS, n_trans = :NTRANS, bg_stretch = :STRETCH, bg_position = :POSITION, bg_repeat = :REPEAT, bg_fixed = :FIXED, c_friend = :FRIENDS, c_subscriber = :SUBSCRIBER, c_subscription = :SUBSCRIPTION, c_featured = :FEATURED, c_videos = :VIDEOS, c_favorites = :FAVORITES, bg = :BG, nav = :NAV, h_head = :H_HEAD, h_head_fnt = :H_HEAD_FONT, h_in = :H_IN, h_in_fnt = :H_IN_FONT, n_head = :N_HEAD, n_head_fnt = :N_HEAD_FONT, n_in = :N_IN, n_in_fnt = :N_IN_FONT, links = :LINK, b_avatar = :AVATAR, c_comments = :COMMENTS, connect = :CONNECT WHERE username = :USERNAME",
                   [
                        ":C_CUSTOM"             => $c_custom,
                        ":AVT_RADIUS"           => $Avatar_Radius,
                        ":CHN_RADIUS"           => $Channel_Radius,
                        ":N_URL"                => $N_URL,
                        ":S_URL"                => $S_URL,
                        ":PLAYLISTS"            => $Playlists,
                        ":FONT"                 => $Channel_Font,
                        ":RECENT"               => $Recent_Activity,
                        ":FEATURED_CHANNELS"    => $Featured_Channels,
                        ":HTRANS"               => $Highlight_Trans,
                        ":NTRANS"               => $Normal_Trans,
                        ":STRETCH"              => $bg_stretch,
                        ":POSITION"             => $bg_position,
                        ":REPEAT"               => $bg_repeat,
                        ":FIXED"                => $bg_fixed,
                        ":FRIENDS"              => $c_friends,
                        ":SUBSCRIBER"           => $c_subscriber,
                        ":SUBSCRIPTION"         => $c_subscriptions,
                        ":FEATURED"             => $c_featured,
                        ":VIDEOS"               => $c_videos,
                        ":FAVORITES"            => $c_favorites,
                        ":BG"                   => $BG_COLOR,
                        ":NAV"                  => $NAV_COLOR,
                        ":H_HEAD"               => $HG_HD_COLOR,
                        ":H_HEAD_FONT"          => $HG_HD_COLOR_FONT,
                        ":H_IN"                 => $HG_IN_COLOR,
                        ":H_IN_FONT"            => $HG_IN_COLOR_FONT,
                        ":N_HEAD"               => $NM_HD_COLOR,
                        ":N_HEAD_FONT"          => $NM_HD_COLOR_FONT,
                        ":N_IN"                 => $NM_IN_COLOR,
                        ":N_IN_FONT"            => $NM_IN_COLOR_FONT,
                        ":LINK"                 => $LINK_COLOR,
                        ":USERNAME"             => $_USER->username,
                        ":CONNECT"              => $CONNECT,
                        ":AVATAR"               => $AVATAR_COLOR,
                        ":COMMENTS"             => $c_comments
                   ]);
        notification("Your channel theme has successfully been updated!","/channel_theme","green"); exit();
    }
}


$Design = $DB->execute("SELECT * FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username]);

if ($Design["videos"] > 0) {
    $Latest_Video = $DB->execute("SELECT url FROM videos WHERE uploaded_by = :USERNAME ORDER BY uploaded_on DESC LIMIT 1", true, [":USERNAME" => $_USER->username])["url"];
}


$HightLight_Num = round(100 - $Design["h_trans"]);

if ($HightLight_Num > 10) {
    $HightLight_Trans = "0.".$HightLight_Num;
} else {
    $HightLight_Trans = "0.0".$HightLight_Num;
}

if ($HightLight_Trans == "0.100") {
    $HightLight_Trans = "1";
}


$Normal_Num = round(100 - $Design["n_trans"]);

if ($Normal_Num > 10) {
    $Normal_Trans = "0.".$Normal_Num;
} else {
    $Normal_Trans = "0.0".$Normal_Num;
}

if ($Normal_Trans == "0.100") {
    $Normal_Trans = "1";
}

$Background = @glob("usfi/bg/".$Design["username"].".*")[0];
if ($Background === NULL) {
    $Has_Background = false;
} else {
    $Background = "/".$Background."?".$Profile["bg_version"];
    $Has_Background = true;
}






if (isset($Design)) {
    $Channel_Version = $Design["channel_version"];
} else {
    $Channel_Version = $Info["channel_version"];
}

if ($Channel_Version > 1) {
    redirect("/my_account");
}


$Account_Title = "Channel Theme";


$_PAGE->set_variables(array(
    "Page_Title"        => "Channel Theme - VidLii",
    "Page"              => "Customize",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/settings_structure.php";