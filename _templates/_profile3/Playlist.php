<style>
    .vid_th {
        display:block
    }
    .th_t {
        bottom: unset !important;
        top: 68px !important;
        right: 7px !important;
    }
</style>
<div class="cosmic_l">
    <div style="padding:12px 15px 15px">
        <div style="font-size:20px">
            <?= $Check_Playlist["title"] ?>
        </div>
        <div style="font-size: 13px;margin-top:5px">
            <em>No Description...</em>
        </div>
    </div>
    <? if (count($Main_Playlist_Videos) > 0) : ?>
    <div>
        <? $Count = 0 ?>
        <? foreach ($Main_Playlist_Videos as $Playlist_Video) : ?>
            <? $Count++ ?>
            <a href="/watch?v=<?= $Playlist_Video["url"] ?>&pl=<?= $Check_Playlist["purl"] ?>" style="overflow:hidden;display:block;padding:11px;background:<? if ($Count % 2) : ?>#f3f3f3<? else : ?>#ffffff<? endif ?>;border-bottom:1px solid #cccccc">
                <div style="float:left;display:flex">
                    <?= thumbnail_picture($Playlist_Video["url"],$Playlist_Video["length"],155,88) ?>
                </div>
                <div style="float:left;color:black;margin-left:11px;font-size:14px">
                    <div><?= $Playlist_Video["title"] ?></div>
                    <div style="font-size:12px;margin: 4px 0 5px;color:#888888"><?= $Playlist_Video["displayname"] ?> <span style="display:inline-block;margin:0 10px;color:#222222"><?= number_format($Playlist_Video["views"]) ?> views</span> <?= get_time_ago($Playlist_Video["uploaded_on"]) ?></div>
                    <div style="width:400px;font-size:12px;color:#888888"><?= cut_string($Playlist_Video["description"],128) ?></div>
                </div>
            </a>
        <? endforeach ?>
    </div>
    <? else : ?>
    <div>
        <div style="margin-top:44px;display:block;color: #808080;text-align: center;font-size:20px;">
            This Playlist has no videos!
        </div>
    </div>
    <? endif ?>
</div>
<div class="cosmic_d">
    <? require_once "right_side.php" ?>
</div>
