<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in) { redirect("/login"); exit(); }

    /*
        on all versions, we preserve names but no custom styles
        so, these for comment only
        ---
            channel_title = '',
            channel_tags = '',
            channel_description = '',
            website = '',
            playlists = '',
            featured_channels = '',
            featured_title = '',
    */
if (isset($_POST["switch_1"])) {
    $DB->modify("UPDATE users SET
            font = '0',
            c_subscriber = '1',
            c_subscription = '1',
            c_friend = '1',
            c_featured = '1',
            c_videos = '1',
            c_favorites = '1',
            c_comments = '1',
            c_featured_channels = '0',
            c_recent = '1',
            c_custom = '0', 
            c_playlists = '0',
            bg = 'ffffff',
            nav = '89857F',
            h_head = '666666',
            h_head_fnt = 'ffffff',
            h_in = 'eeeeee',
            h_in_fnt = '6d6d6d',
            n_head = '666666',
            n_head_fnt = 'ffffff',
            n_in = 'ffffff',
            n_in_fnt = '000000',
            links = '89857F',
            b_avatar = '999999',
            connect = '',
            bg_position = '0',
            bg_repeat = '0',
            bg_fixed = '0',
            bg_stretch = '0',
            h_trans = '0',
            n_trans = '0',
            channel_version = '1',
            friends_d = '0',
            subscriber_d = '0',
            subscription_d = '0',
            featured_d = '1',
            recent_d = '1',
            channel_d = '1',
            featured_n_url = '',
            featured_s_url = '',
            avt_radius = 0,
            chn_radius = 0
            WHERE username = :USERNAME
            ",
            [
                ":USERNAME" => $_USER->username
            ]);
    $Background = @glob("usfi/bg/$_USER->username.*")[0];
    @unlink($Background);
    @unlink("usfi/bner/$_USER->username.png");
    $DB->modify("DELETE FROM channel_banners WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
}

if (isset($_POST["switch_2"])) {
    $DB->modify("UPDATE users SET
            font = '0',
            c_subscriber = '1',
            c_subscription = '1',
            c_friend = '1',
            c_featured = '1',
            c_videos = '1',
            c_favorites = '1',
            c_playlists = '0',
            c_custom = '0',
            c_custom = '0',
            c_comments = '1',
            c_featured_channels = '0',
            c_recent = '1',
            bg = 'CCCCCC',
            nav = '89857F',
            h_head = '999999',
            h_head_fnt = '0000CC',
            h_in = 'eeeeee',
            h_in_fnt = '000000',
            n_head = '666666',
            n_head_fnt = '000000',
            n_in = 'EEEEFF',
            n_in_fnt = '333333',
            links = '0000CC',
            b_avatar = '999999',
            connect = '',
            bg_position = '0',
            bg_repeat = '0',
            bg_fixed = '0',
            bg_stretch = '0',
            h_trans = '0',
            n_trans = '0',
            channel_version = '2',
            friends_d = '0',
            subscriber_d = '0',
            subscription_d = '0',
            featured_d = '1',
            recent_d = '1',
            channel_d = '1',
            featured_n_url = '',
            featured_s_url = '',
            avt_radius = 4,
            chn_radius = 5
            WHERE username = :USERNAME
            ",
            [
                ":USERNAME" => $_USER->username
            ]);
    $Background = @glob("usfi/bg/$_USER->username.*")[0];
    @unlink($Background);
    @unlink("usfi/bner/$_USER->username.png");
    $DB->modify("DELETE FROM channel_banners WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
}

if (isset($_POST["switch_3"])) {
    $Update = $DB->modify("UPDATE users SET
            font = '0',
            c_subscriber = '1',
            c_subscription = '1',
            c_friend = '1',
            c_featured = '1',
            c_videos = '1',
            c_favorites = '1',
            c_playlists = '0',
            c_comments = '1',
            c_featured_channels = '1',
            c_recent = '1',
            bg = 'f9f9f9',
            nav = '',
            h_head = '',
            h_head_fnt = '',
            h_in = '',
            h_in_fnt = '',
            n_head = '',
            n_head_fnt = '',
            n_in = '',
            n_in_fnt = '',
            links = '',
            b_avatar = '',
            connect = '',
            bg_position = '0',
            bg_repeat = '0',
            bg_fixed = '0',
            bg_stretch = '0',
            h_trans = '0',
            n_trans = '0',
            channel_version = '3',
            friends_d = '0',
            subscriber_d = '0',
            subscription_d = '0',
            featured_d = '1',
            recent_d = '1',
            channel_d = '1',
            featured_n_url = '',
            featured_s_url = '',
            avt_radius = 4,
            chn_radius = 5
            WHERE username = :USERNAME
            ",
            [
                ":USERNAME" => $_USER->username
            ]);
    $Background = @glob("usfi/bg/$_USER->username.*")[0];
    @unlink($Background);
    @unlink("usfi/bner/$_USER->username.png");
    $DB->modify("DELETE FROM channel_banners WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
}

if(isset($_POST["switch_4"])) {
    $is_nouveau = (bool)($api->db("SELECT nouveau from users where id = ".$api->session()["user"]["id"])["data"]["nouveau"] == 1);
    if($is_nouveau) {
        $update_layout = $api->db("UPDATE users set channel_version = 4 where id = ".$api->session()["user"]["id"]);
        if($update_layout["status"] >= 1) {
            notification("Layout changed successfully", "/channel_version", "green");
        } else {
            notification($update_layout["message"], "/channel_version", "red");
        }
    } else {
        notification("You are not eligible for this layout", "/channel_version", "red");
    }
}

//GET INFO
$Info = $_USER->get_profile();
$Channel_Version = $Info["channel_version"];
$Account_Title = "Channel Version";

$_PAGE->set_variables(array(
    "Page_Title"        => "Channel Version - VidLii",
    "Page"              => "Layout",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/settings_structure.php";