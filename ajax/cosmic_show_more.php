<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires ($_POST["user"]) AND ($_POST["type"]) AND ($_POST["page"])
if (!isset($_POST["user"]) || !isset($_POST["type"]) || !isset($_POST["page"])) { exit(); }


$User = $_POST["user"];
$Page = (int)$_POST["page"];

$Channel_Info = $DB->execute("SELECT channel_comments FROM users WHERE username = :USERNAME", true, [":USERNAME" => $User]);

function thumbnail_picture($URL,$LENGTH,$Width,$Height,$Title = NULL) {
    if (!empty($LENGTH) || $LENGTH == "0") { $Length = seconds_to_time((int)$LENGTH); } else { $Length = $LENGTH; }
    if (file_exists($_SERVER['DOCUMENT_ROOT']."/usfi/thmp/$URL.jpg")) { $Thumbnail = "/usfi/thmp/$URL.jpg"; } else { $Thumbnail = "/img/no_th.jpg"; }

    return '<div style="display:inline-block;position: relative;width:'.$Width.'px"><div class="th_t">'.$Length.'</div><img class="vid_th" src="'.$Thumbnail.'" width="'.$Width.'" height="'.$Height.'"></div>';
}

if ($_POST["type"] == "videos") {

    $From = ($Page * 10);

    //$Videos = $DB->execute("SELECT title,url,views,uploaded_by, uploaded_on, description, length FROM videos WHERE uploaded_by = :FROMUSER ORDER BY uploaded_on DESC LIMIT $From, 10", false, [":FROMUSER" => $User]);


    $Videos                     = new Videos($DB, $_USER);
    $Videos->WHERE_P            = ["uploaded_by" => $User];
    $Videos->ORDER_BY           = "uploaded_on DESC";
    $Videos->Shadowbanned_Users = true;
    $Videos->LIMIT              = $From. ", 10";
    $Videos->get();

    if ($Videos::$Videos) {
        $Videos = $Videos->fixed();

        $Videos_Amount                      = new Videos($DB, $_USER);
        $Videos_Amount->WHERE_P             = ["uploaded_by" => $User];
        $Videos_Amount->Shadowbanned_Users  = true;
        $Videos_Amount->Count               = true;
        $Videos_Amount                      = $Videos_Amount->get();

    } else {

        $Videos = false;

    }



    if ((10 * ($Page + 1)) < $Videos_Amount) { $Show_More = '<button type="button" id="show_more" onclick="show_more(\'videos\','.($Page + 1).',\''.$User.'\')" class="cosmic_button" style="width:100%">Load 10 more videos</button>'; } else { $Show_More = ""; }

    ?>
    <? foreach ($Videos as $Video) : ?>
        <div class="cosmic_big_video">
            <a href="/watch?v=<?= $Video["url"] ?>">
                <div style="display:inline-block;position: relative;width:295px"><div class="th_t"><?= $Video["length"] ?></div><img class="vid_th" <?= $Video["thumbnail"] ?> width="295px" height="155px"></div>
                <div class="cosmic_big_video_info">
                    <div><?= $Video["title"] ?></div>
                    <div class="cosmic_big_small_stats"><span><?= $Video["uploaded_by"] ?></span><span style="color:#444"><?= $Video["views"] ?> views</span><span><?= get_time_ago($Video["uploaded_on"]) ?></span></div>
                    <div class="big_video_description"><? if (!empty($Video["description"])) { echo cut_string($Video["description"],128); } else { echo "<em>No Description...</em>"; } ?></div>
                </div>
            </a>
        </div>
    <? endforeach ?>
    <?
    echo $Show_More;
} elseif ($_POST["type"] == "favorites") {

    $From = ($Page * 10);


    $Videos                     = new Videos($DB, $_USER);
    $Videos->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
    $Videos->Shadowbanned_Users = true;
    $Videos->Banned_Users       = true;
    $Videos->Private_Videos     = true;
    $Videos->Unlisted_Videos    = true;
    $Videos->ORDER_BY           = "video_favorites.date DESC";
    $Videos->WHERE_P            = ["video_favorites.favorite_by" => $User];
    $Videos->LIMIT              = $From.", 10";
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
        $Videos_Amount->WHERE_P                     = ["video_favorites.favorite_by" => $User];
        $Videos_Amount                              = $Videos_Amount->get();

    } else {

        $Videos = false;

    }


    if ((10 * ($Page + 1)) < $Videos_Amount) { $Show_More = '<button type="button" id="show_more" onclick="show_more(\'favorites\','.($Page + 1).',\''.$User.'\')" class="cosmic_button" style="width:100%">Load 10 more videos</button>'; } else { $Show_More = ""; }


    ?>
    <? foreach ($Videos as $Video) : ?>
        <div class="cosmic_big_video">
            <a href="/watch?v=<?= $Video["url"] ?>">
                <div style="display:inline-block;position: relative;width:295px"><div class="th_t"><?= $Video["length"] ?></div><img class="vid_th" <?= $Video["thumbnail"] ?> width="295px" height="155px"></div>
                <div class="cosmic_big_video_info">
                    <div><?= $Video["title"] ?></div>
                    <div class="cosmic_big_small_stats"><span><?= $Video["uploaded_by"] ?></span><span style="color:#444"><?= $Video["views"] ?> views</span><span><?= get_time_ago($Video["uploaded_on"]) ?></span></div>
                    <div class="big_video_description"><? if (!empty($Video["description"])) { echo cut_string($Video["description"],128); } else { echo "<em>No Description...</em>"; } ?></div>
                </div>
            </a>
        </div>
    <? endforeach ?>
    <?
    echo $Show_More;
} elseif ($_POST["type"] == "comments") {
    function no_link_avatar($User,$Width,$Height,$Avatar,$Extra_Class = "") {
        if (strpos($Avatar,"u=") !== false) { $Avatar = str_replace("u=","",$Avatar); $Folder = "avt"; } else { $Upload = false; $Folder = "thmp"; }

        if (empty($Avatar) or !file_exists("../usfi/$Folder/$Avatar.jpg")) {
            $Avatar = "/img/no.png";
        } else {
            if ($Folder == "avt") {
                $Avatar = "/usfi/avt/$Avatar.jpg";
            } else {
                $Avatar = "/usfi/thmp/$Avatar.jpg";
            }
        }
        return '<div href="/user/'.$User.'"><img src="'.$Avatar.'" width="'.$Width.'" height="'.$Height.'" class="avt2 '.$Extra_Class.'" alt="'.$User.'"></div>';
    }
    $From = ($Page * 20);

    $Channel_Comments = $DB->execute("SELECT channel_comments.*, users.avatar FROM channel_comments INNER JOIN users ON channel_comments.by_user = users.username WHERE channel_comments.on_channel = :OWNER ORDER BY channel_comments.date DESC LIMIT $From, 20", false, [":OWNER" => $User]);
    if ((20 * ($Page + 1)) < $Channel_Info["channel_comments"]) { $Show_More = '<button type="button" id="show_more_comments" onclick="show_more_comments('.($Page + 1).',\''.$User.'\')" class="cosmic_button" style="width:100%">Load 20 more comments</button>'; } else { $Show_More = ""; }
    ?>
    <? foreach ($Channel_Comments as $Channel_Comment) : ?>
        <div class="cosmic_comment" id="cc_<?= $Channel_Comment["id"] ?>">
            <div>
                <?= no_link_avatar($Channel_Comment["by_user"],26,26,$Channel_Comment["avatar"]) ?>
                <div>
                    <a href="/user/<?= $Channel_Comment["by_user"] ?>"><?= $Channel_Comment["by_user"] ?></a> posted a comment <span><?= get_time_ago($Channel_Comment["date"]) ?></span>
                </div>
            </div>
            <? if ($_USER->logged_in && ($_USER->username == $Channel_Comment["by_user"] || $_USER->username == $User)) : ?><a href="javascript:void(0)" class="cosmic_delete" onclick="delete_comment(<?= $Channel_Comment["id"] ?>)">Delete</a><? endif ?>
            <div>
                <div>
                    <?= nl2br(hashtag_search(mention($Channel_Comment["comment"]))) ?>
                </div>
            </div>
        </div>
    <? endforeach ?>
    <?
    echo $Show_More;
} else {
    echo "error";
}