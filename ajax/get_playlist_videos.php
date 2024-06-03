<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires ($_POST["pid"])
if (!isset($_POST["pid"])) { exit(); }


$Playlists_Videos                        = new Videos($DB, $_USER);
$Playlists_Videos->JOIN                  = "RIGHT JOIN playlists_videos ON playlists_videos.url = videos.url RIGHT JOIN playlists ON playlists_videos.purl = playlists.purl";
$Playlists_Videos->WHERE_P               = ["playlists_videos.purl" => $_POST["pid"]];
$Playlists_Videos->ORDER_BY              = "playlists_videos.position";
$Playlists_Videos->SELECT               .= ", playlists_videos.purl, playlists.title as playlist_title";
$Playlists_Videos->LIMIT                 = 10000;
$Playlists_Videos->Banned_Users          = true;
$Playlists_Videos->Shadowbanned_Users    = true;
$Playlists_Videos->Unlisted_Videos       = true;
$Playlists_Videos->Private_Videos        = true;
$Playlists_Videos->get();


if ($Playlists_Videos::$Videos) {

    $Playlists_Videos = $Playlists_Videos->fixed();


    echo '<div class="mnu_sct" style="border:0"><div>' . $Playlists_Videos[0]["playlist_title"] . '</div>';
    foreach ($Playlists_Videos as $Video) {
        if ($Video["url"] == $_POST["selected"]) {
            $ID = 'id="v_sel"';
        } else {
            $ID = "";
        }
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

} else {
    echo "<div style='text-align: center;position: relative;top: 274px;font-size: 18px;'>No videos are in this playlist!</div>";
}