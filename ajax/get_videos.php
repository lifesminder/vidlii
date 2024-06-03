<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires ($_POST["type"]) AND ($_POST["user"])
if (!isset($_POST["type"]) || !isset($_POST["user"])) { exit(); }


$From_User = $_POST["user"];

$Channel_Info = $DB->execute("SELECT c_videos,c_favorites,c_playlists,displayname FROM users WHERE username = :USERNAME", true, [":USERNAME" => $From_User]);

if ($_POST["type"] == "uploads") {

    $Videos                     = new Videos($DB, $_USER);
    $Videos->WHERE_P            = ["uploaded_by" => $From_User];
    $Videos->ORDER_BY           = "uploaded_on DESC";
    $Videos->Shadowbanned_Users = true;
    $Videos->LIMIT              = 16;
    $Videos->get();

    if ($Videos::$Videos) {
        $Videos = $Videos->fixed();

        $Videos_Amount                      = new Videos($DB, $_USER);
        $Videos_Amount->WHERE_P             = ["uploaded_by" => $From_User];
        $Videos_Amount->Shadowbanned_Users  = true;
        $Videos_Amount->Count               = true;
        $Videos_Amount                      = $Videos_Amount->get();

    } else {

        $Videos = false;

    }


    if (count($Videos) < $Videos_Amount) { $Show_More = '<center><button id="show_more" onclick="show_more(\'videos\',\'1\')">Show More</button></center>'; } else { $Show_More = ""; }
    ?><div class="mnu_sct" style="border:0"><div>Uploads (<?= number_format($Videos_Amount) ?>)</div><?
    foreach($Videos as $Video) {
        if ($Video["url"] == $_POST["selected"]) { $ID = 'id="v_sel"'; } else { $ID = ""; }
        ?>
                <div class="mnu_vid" <?= $ID ?> watch="<?= $Video["url"] ?>">
                    <a href="javascript:void(0)"><img <?= $Video["thumbnail"] ?> width="100" height="75"></a>
                            <div>
                                <a href="javascript:void(0)"><?= htmlspecialchars($Video["title"]) ?></a>
                                <span><a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a> - <?= number_format($Video["views"]) ?> views</span>
                            </div>
                </div>
        <?
    }
    echo $Show_More;
    echo "</div>";
} elseif ($_POST["type"] == "favorites") {

    $Videos                     = new Videos($DB, $_USER);
    $Videos->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
    $Videos->Shadowbanned_Users = true;
    $Videos->Banned_Users       = true;
    $Videos->Private_Videos     = true;
    $Videos->Unlisted_Videos    = true;
    $Videos->ORDER_BY           = "video_favorites.date DESC";
    $Videos->WHERE_P            = ["video_favorites.favorite_by" => $From_User];
    $Videos->LIMIT              = 16;
    $Videos->get();

    if ($Videos::$Videos) {

        $Videos = $Videos->fixed();

        $Videos_Amount                              = new Videos($DB, $_USER);
        $Videos_Amount->JOIN                        = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
        $Videos_Amount->Shadowbanned_Users          = true;
        $Videos_Amount->Banned_Users                = true;
        $Videos_Amount->Private_Videos              = true;
        $Videos_Amount->Unlisted_Videos             = true;
        $Videos_Amount->Count                       = true;
        $Videos_Amount->Count_Column                = "video_favorites.url";
        $Videos_Amount->WHERE_P                     = ["video_favorites.favorite_by" => $From_User];
        $Videos_Amount                              = $Videos_Amount->get();

    } else {

        $Videos = false;

    }





    if (count($Videos) < $Videos_Amount) { $Show_More = '<center><button id="show_more" onclick="show_more(\'favorites\',\'1\')">Show More</button></center>'; } else { $Show_More = ""; }
    ?> <div class="mnu_sct" style="border:0"><div>Favorites (<?= number_format($Videos_Amount) ?>)</div><?
    foreach($Videos as $Video) {
        if ($Video["url"] == $_POST["selected"]) { $ID = 'id="v_sel"'; } else { $ID = ""; }
        ?>
               <div class="mnu_vid" <?= $ID ?> watch="<? if ($Video["privacy"] == 0) : ?><?= $Video["url"] ?><? endif ?>">
                    <a href="javascript:void(0)"><img <?= $Video["thumbnail"] ?> width="100" height="75"></a>
                            <div>
                                <a href="javascript:void(0)"><?= htmlspecialchars($Video["title"]) ?></a>
                                <span><a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a> - <?= number_format($Video["views"]) ?> views</span>
                            </div>
                </div>
                <?
    }
    echo $Show_More;
    echo "</div>";
} elseif ($_POST["type"] == "all") {
    $Selected = false;


    $Videos                     = new Videos($DB, $_USER);
    $Videos->WHERE_P            = ["uploaded_by" => $From_User];
    $Videos->ORDER_BY           = "uploaded_on DESC";
    $Videos->Shadowbanned_Users = true;
    $Videos->LIMIT              = 3;
    $Videos->get();

    if ($Videos::$Videos) {
        $Videos = $Videos->fixed();

        $Videos_Amount                      = new Videos($DB, $_USER);
        $Videos_Amount->WHERE_P             = ["uploaded_by" => $From_User];
        $Videos_Amount->Shadowbanned_Users  = true;
        $Videos_Amount->Count               = true;
        $Videos_Amount                      = $Videos_Amount->get();

    } else {
        $Videos_Amount = 0;
        $Videos        = false;

    }


    if ($Videos_Amount > 0 && $Channel_Info["c_videos"]) {
        ?><div class="mnu_sct"><div>Uploads (<?= number_format($Videos_Amount) ?>)</div><?
        foreach($Videos as $Video) {
            if ($Video["url"] == $_POST["selected"]) { $ID = 'id="v_sel"'; } else { $ID = ""; }
            ?>
                    <div class="mnu_vid" <?= $ID ?> watch="<?= $Video["url"] ?>">
                        <a href="javascript:void(0)"><img <?= $Video["thumbnail"] ?> width="100" height="75"></a>
                                <div>
                                    <a href="javascript:void(0)"><?= htmlspecialchars($Video["title"]) ?></a>
                                    <span><a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a> - <?= number_format($Video["views"]) ?> views</span>
                                </div>
                    </div>
            <?
        }
        echo "</div>";
    }
    if ($Channel_Info["c_playlists"]) {
        $Playlists = $DB->execute("SELECT * FROM playlists WHERE created_by = :OWNER", false, [":OWNER" => $From_User]);
        if ($DB->RowNum > 0) {
            $Playlist_Amount    = $DB->RowNum;
            $Playlists          = array_slice($Playlists, 0, 3);
        } else {
            $Playlist_Amount    = 0;
        }
    }



    $Videos                     = new Videos($DB, $_USER);
    $Videos->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
    $Videos->Shadowbanned_Users = true;
    $Videos->Banned_Users       = true;
    $Videos->Private_Videos     = true;
    $Videos->Unlisted_Videos    = true;
    $Videos->ORDER_BY           = "video_favorites.date DESC";
    $Videos->WHERE_P            = ["video_favorites.favorite_by" => $From_User];
    $Videos->LIMIT              = 3;
    $Videos->get();

    if ($Videos::$Videos) {

        $Videos = $Videos->fixed();

        $Videos_Amount                              = new Videos($DB, $_USER);
        $Videos_Amount->JOIN                        = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
        $Videos_Amount->Shadowbanned_Users          = true;
        $Videos_Amount->Banned_Users                = true;
        $Videos_Amount->Private_Videos              = true;
        $Videos_Amount->Unlisted_Videos             = true;
        $Videos_Amount->Count                       = true;
        $Videos_Amount->Count_Column                = "video_favorites.url";
        $Videos_Amount->WHERE_P                     = ["video_favorites.favorite_by" => $From_User];
        $Videos_Amount                              = $Videos_Amount->get();

    } else {

        $Videos_Amount = 0;
        $Videos = false;

    }



    if ($Videos_Amount > 0 && $Channel_Info["c_favorites"]) {
        if ($Channel_Info["c_playlists"] && $Playlist_Amount > 0) { $Style = ""; } else { $Style = 'style="border:0;margin:0"'; }

        echo '<div class="mnu_sct" '.$Style.'><div>Favorites ('.number_format($Videos_Amount).')</div>';
        foreach($Videos as $Video) {
            if ($Video["url"] == $_POST["selected"] && $Selected == false) { $ID = 'id="v_sel"'; $Selected = true; } else { $ID = ""; }
            ?>
                <div class="mnu_vid" <?= $ID ?> watch="<? if ($Video["privacy"] == 0) : ?><?= $Video["url"] ?><? endif ?>">
                    <a href="javascript:void(0)"><img <?= $Video["thumbnail"] ?> width="100" height="75"></a>
                    <div>
                         <a href="javascript:void(0)"><?= htmlspecialchars($Video["title"]) ?></a>
                         <span><a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a> - <?= number_format($Video["views"]) ?> views</span>
                    </div>
                </div>
            <?
        }
        echo "</div>";
    }
    if ($Channel_Info["c_playlists"] && $Playlist_Amount > 0) {
        echo '<div class="mnu_sct" style="border:0;margin:0;"><div>Playlists ('. $Playlist_Amount .')</div>';
        foreach ($Playlists as $Playlist) {
            if (!file_exists("../usfi/thmp/".$Playlist["thumbnail"].".jpg")) { $Playlist["thumbnail"] = "/img/no_th.jpg"; } else { $Playlist["thumbnail"] = "/usfi/thmp/".$Playlist["thumbnail"].".jpg"; }
            echo '<div class="mnu_vid" pl="'. $Playlist["purl"] .'" style="padding-bottom:0">
                       <div class="playlist" style="float:left;margin-right:8px;width:125px;position:relative;top:2px">
                           <img src="'. $Playlist["thumbnail"] .'" style="top:1.5px;width:117px;height:69px">
                        </div>
                        <div>
                            <a href="javascript:void(0)">'. $Playlist["title"] .'</a>
                            <span><a href="/user/'. $Channel_Info["displayname"] .'">'. $Channel_Info["displayname"] .'</a></span>
                        </div>
                    </div>';
                    }
        echo "</div>";
    }
} elseif ($_POST["type"] == "playlists") {
    $Playlists = $DB->execute("SELECT * FROM playlists WHERE created_by = :OWNER", false, [":OWNER" => $From_User]);
    if ($DB->RowNum > 0) {
        $Playlist_Amount = $DB->RowNum;
        echo '<div class="mnu_sct" style="border:0;margin:0;"><div>Playlists ('. $Playlist_Amount .')</div>';
        foreach ($Playlists as $Playlist) {
            if (!file_exists("../usfi/thmp/".$Playlist["thumbnail"].".jpg")) { $Playlist["thumbnail"] = "/img/no_th.jpg"; } else { $Playlist["thumbnail"] = "/usfi/thmp/".$Playlist["thumbnail"].".jpg"; }
            echo '<div class="mnu_vid" pl="'. $Playlist["purl"] .'" style="padding-bottom:0">
                        <div class="playlist" style="float:left;margin-right:8px;width:125px;position:relative;top:2px">
                            <img src="'. $Playlist["thumbnail"] .'" style="top:1.5px;width:117px;height:69px">
                        </div>
                        <div>
                            <a href="javascript:void(0)">'. $Playlist["title"] .'</a>
                            <span><a href="/user/'. $Channel_Info["displayname"] .'">'. $Channel_Info["displayname"] .'</a></span>
                        </div>
                    </div>';
        }
        echo "</div>";
    }
}