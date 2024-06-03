<form action="my_playlists?pl=<?= $Playlist["purl"] ?>" method="POST">
    <div style="background: #e2e2e2;padding: 12px">
        <strong><?= $Playlist["title"] ?></strong>
    </div>
    <div style="border: 2px solid #e2e2e2; border-top: 0;padding: 10px;">
        <div style="border-bottom: 1px dashed #ccc;padding-bottom: 9px;margin-bottom: 10px">
            <table style="margin:0;width:100%">
                <tr>
                    <td align="left">
                        <input type="text" placeholder="Change Playlist Name" value="<?= $Playlist["title"] ?>" name="playlist_name" maxlength="128"> <input type="submit" name="update_playlist" value="Update"><br><br>
                        <input type="text" placeholder="Add Video" name="video" maxlength="128"> <input type="submit" name="add_video" value="Add">
                    </td>
                    <td align="right">
                        <table style="margin:0" cellspacing="5">
                            <tr>
                                <td align="right">Created On:</td>
                                <td><?= get_date($Playlist["created_on"]) ?></td>
                            </tr>
                            <tr>
                                <td align="right">Created By:</td>
                                <td> <?= $_USER->displayname ?></td>
                            </tr>
                            <tr>
                                <td align="right">Videos:</td>
                                <td><?= $_PAGINATION->Total ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <? if ($Playlist_Videos) : ?>
            <? foreach($Playlist_Videos as $Video) : ?>
                <table class="mv_sct">
                    <tr>
                        <td width="5%" align="center" valign="top"><input type="checkbox"></td>
                        <td width="20%" valign="top" align="center">
                            <div class="th">
                                <div class="th_t"><?= $Video["length"] ?></div>
                                <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="190" height="131"></a>
                            </div>
                        </td>
                        <td width="70%" valign="top" class="mv_info">
                            <strong><a href="watch?v=<?= $Video["url"] ?>"><?= $Video["title"] ?></a></strong><br>
                            <div class="mv_descr"><?= $Video["description"] ?></div>
                            <span>Tags:</span> <?= $Video["tags"] ?><br>
                            <span>Added:</span> <?= get_date($Video["uploaded_on"])." | ".get_time($Video["uploaded_on"]); ?><br>
                            <span>Ratings:</span> <?= $Video["1_star"] + $Video["2_star"] + $Video["3_star"] + $Video["4_star"] + $Video["5_star"]?><br>
                            <span>Comments:</span> <?= $Video["comments"] ?> | <span>Views:</span> <?= $Video["views"] ?><br>
                            <span>Status:</span> <strong><? if ($Video["status"] == 1) : ?><font color="red">Converting.</font><? else : ?><font color="green">Live!</font><? endif ?></strong><br>
                            <a href="/ajax/df/remove_playlist?v=<?= $Video["video_url"] ?>&p=<?= $Video["purl"] ?>"><button type="button">Remove from Playlist</button></a>
                        </td>
                    </tr>
                </table>
            <? endforeach ?>
            <div style="padding-top: 5px;text-align: left;border-top:0" class="vc_pagination"><?= $_PAGINATION->new_show(NULL,"pl=".$_GET["pl"]) ?></div>
        <? else : ?>
            <center style="font-size: 20px;color:gray">This Playlist doesn't have any videos!<br><span style="font-size:16px"></center>
        <? endif ?>
    </div>
</form>