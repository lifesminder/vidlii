<div style="margin-bottom:14px;padding-bottom:7px;border-bottom:1px solid #ccc;overflow:hidden">
    <img src="<? if (file_exists(ROOT_FOLDER."/usfi/thmp/".$Playlist["thumbnail"].".jpg")) : ?>/usfi/thmp/<?= $Playlist["thumbnail"] ?>.jpg<? else : ?>/img/no_th.jpg<? endif ?>" style="display: block;float: left;width: 227px;height:137px;border: 5px double #ccc;">
    <div style="float:left;margin-left: 8px;position:relative;bottom:1.5px;width:481px;height:147px;padding-right:60px;margin-right:14px;border-right:1px solid #ccc">
        <a href="/playlist?p=<?= $Playlist["purl"] ?>" style="font-weight:bold;font-size:20px;display:block;margin-bottom:4px;"><?= $Playlist["title"] ?></a>
        <div>
            <em>No Description...</em>
        </div>
    </div>
    <div>
        <table cellpadding="6" style="position:relative;top:13px">
            <tr>
                <td>Total Views:</td>
                <td><?= number_format($Playlist_Stats["total_views"]) ?></td>
            </tr>
            <tr>
                <td>Total Comments:</td>
                <td><?= number_format($Playlist_Stats["total_comments"]) ?></td>
            </tr>
            <tr>
                <td>Total Favorites:</td>
                <td><?= number_format($Playlist_Stats["total_favorites"]) ?></td>
            </tr>
            <tr>
                <td>Total Responses:</td>
                <td><?= number_format($Playlist_Stats["total_responses"]) ?></td>
            </tr>
        </table>
    </div>
</div>
<div>
    <? foreach ($Playlist_Videos as $Video) : ?>
        <div class="result">
            <div class="th">
                <div class="th_t"><?= $Video["length"] ?></div>
                <a href="/watch?v=<?= $Video["url"] ?>&pl=<?= $Playlist["purl"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="186" height="111"></a>
            </div>
            <a href="/watch?v=<?= $Video["url"] ?>&pl=<?=$Playlist["purl"] ?>" class="r_title"><?= $Video["title"] ?></a>
            By. <a href="/user/<?= $Video["uploaded_by"] ?>"><?= $Video["uploaded_by"] ?></a><br>
            <?= get_time_ago($Video["uploaded_on"]) ?> - <?= number_format($Video["views"]) ?> views
            <div class="r_descr">
                <?= cut_string($Video["description"],135) ?>
            </div>
            <?= show_ratings($Video,15,14) ?>
        </div>
    <? endforeach ?>
</div>