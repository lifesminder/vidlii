<?php

require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


$_PAGINATION = new Pagination(10,50);

if (isset($_POST["create_playlist"])) {
    $_GUMP->validation_rules(array(
        "playlist_name"          => "required|max_len,130"
    ));

    $_GUMP->filter_rules(array(
        "playlist_name"         => "trim|NoHTML"
    ));

    $Validation = $_GUMP->run($_POST);

    if ($Validation) {
        $Playlist_Title = (string)$Validation["playlist_name"];
        $PURL           = (string)random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_",11);

        $Create = $DB->modify("INSERT INTO playlists (purl,title,created_by,thumbnail,created_on) VALUES (:PURL,:TITLE,:USERNAME,:THUMBNAIL,NOW())",
                             [
                                 ":PURL"        => $PURL,
                                 ":TITLE"       => $Playlist_Title,
                                 ":THUMBNAIL"   => "",
                                 ":USERNAME"    => $_USER->username
                             ]);
        redirect("my_playlists?pl=$PURL"); exit();
    }
}


//GET PLAYLISTS

if (!isset($_GET["pl"])) {
    $Playlists = $DB->execute("SELECT playlists.purl, playlists.title, playlists.created_on, playlists.thumbnail, videos.privacy FROM playlists LEFT JOIN videos ON playlists.thumbnail = videos.url WHERE playlists.created_by = :USERNAME", false, [":USERNAME" => $_USER->username]);

    $Page = "Playlists";
} else {
    $Playlist = $DB->execute("SELECT purl, title, created_on, thumbnail FROM playlists WHERE purl = :URL AND created_by = :USERNAME", true,
                            [
                                ":URL"      => $_GET["pl"],
                                ":USERNAME" => $_USER->username
                            ]);

    if ($DB->RowNum == 1) {
        $Playlist_Videos                        = new Videos($DB, $_USER);
        $Playlist_Videos->JOIN                  = "RIGHT JOIN playlists_videos ON playlists_videos.url = videos.url";
        $Playlist_Videos->WHERE_P               = ["playlists_videos.purl" => $Playlist["purl"]];
        $Playlist_Videos->ORDER_BY              = "playlists_videos.position";
        $Playlist_Videos->SELECT               .= ", playlists_videos.purl, playlists_videos.url as video_url";
        $Playlist_Videos->LIMIT                 = $_PAGINATION;
        $Playlist_Videos->Banned_Users          = true;
        $Playlist_Videos->Shadowbanned_Users    = true;
        $Playlist_Videos->Unlisted_Videos       = true;
        $Playlist_Videos->Private_Videos        = true;
        $Playlist_Videos->get();


        if ($Playlist_Videos::$Videos) {

            $Playlist_Videos = $Playlist_Videos->fixed();


            $Playlist_Videos_Amount                        = new Videos($DB, $_USER);
            $Playlist_Videos_Amount->JOIN                  = "RIGHT JOIN playlists_videos ON playlists_videos.url = videos.url";
            $Playlist_Videos_Amount->WHERE_P               = ["playlists_videos.purl" => $Playlist["purl"]];
            $Playlist_Videos_Amount->Count                 = true;
            $Playlist_Videos_Amount->Banned_Users          = true;
            $Playlist_Videos_Amount->Shadowbanned_Users    = true;
            $Playlist_Videos_Amount->Unlisted_Videos       = true;
            $Playlist_Videos_Amount->Private_Videos        = true;
            $_PAGINATION->Total                            = $Playlist_Videos_Amount->get();

        } else {

            $Playlist_Videos = false;

        }

        if (isset($_POST["update_playlist"])) {
            $_GUMP->validation_rules(array(
                "playlist_name"          => "required|max_len,130"
            ));

            $_GUMP->filter_rules(array(
                "playlist_name"         => "trim|NoHTML"
            ));

            $Validation = $_GUMP->run($_POST);

            if ($Validation) {
                $Name = $Validation["playlist_name"];

                $DB->modify("UPDATE playlists SET title = :TITLE WHERE purl = :PURL AND created_by = :USERNAME",
                           [
                               ":TITLE"     => $Name,
                               ":PURL"      => $Playlist["purl"],
                               ":USERNAME"  => $_USER->username
                           ]);
                redirect("my_playlists?pl=".$Playlist["purl"]); exit();
            }
        }


        if (isset($_POST["add_video"])) {
            $_GUMP->validation_rules(array(
                "video"       => "required|max_len,128|valid_url"
            ));

            $_GUMP->filter_rules(array(
                "video"       => "trim|NoHTML"
            ));

            $Validation = $_GUMP->run($_POST);

            if ($Validation) {
                if (strpos($Validation["video"],"watch?v=") !== false) {
                    $URL    = url_parameter($Validation["video"], "v");
                    $PURL   = $Playlist["purl"];

                    $Video = new Video($URL,$DB);
                    $URL = $Video->exists();

                    if ($URL !== false) {
                        $Check = $DB->execute("SELECT position FROM playlists_videos WHERE purl = :PURL ORDER BY position DESC LIMIT 1", true, [":PURL" => $PURL]);

                        if ($DB->RowNum == 1) {
                            $New_Position = $Check["position"] + 1;
                        } else {
                            $New_Position = 1;
                        }

                        $DB->modify("INSERT IGNORE INTO playlists_videos (url,purl,position) VALUES (:URL,:PURL,:POSITION)",
                                   [
                                       ":URL"       => $URL,
                                       ":PURL"      => $PURL,
                                       ":POSITION"  => $New_Position
                                   ]);
                        if ($DB->RowNum == 1) {
                            $DB->modify("UPDATE playlists SET thumbnail = :URL WHERE purl = :PURL AND created_by = :USERNAME",
                                       [
                                           ":URL"       => $URL,
                                           ":PURL"      => $PURL,
                                           ":USERNAME"  => $_USER->username
                                       ]);
                            notification("You successfully added the video to this playlist!", "/my_playlists?pl=".$_GET["pl"], "green"); exit();
                        } else {
                            notification("This video is already inside this playlist!", "/my_playlists?pl=".$_GET["pl"], "red"); exit();
                        }
                    }
                }
            }
        }



        $Page = "Playlist";
    } else {
        redirect("my_playlists"); exit();
    }
}


$Header = "My Playlists";


$_PAGE->set_variables(array(
    "Page_Title"        => "My Playlists - VidLii",
    "Page"              => $Page,
    "Page_Type"         => "Videos",
    "Show_Search"       => false
));
require_once "_templates/videos_structure.php";