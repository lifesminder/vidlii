<form action="my_favorites" method="POST">
    <div style="background: #e2e2e2;padding: 12px">
        <input type="submit" value="Remove Selected Videos" name="remove favorites">
    </div>
    <div style="border: 2px solid #e2e2e2; border-top: 0;padding: 10px;">
        <? if ($Videos) : ?>
        <? foreach($Videos as $Video) : ?>
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
                            <span title="1 Star [<?= $Video["1_star"] ?>, 2 Stars [<?= $Video["2_star"] ?>], 3 Stars [<?= $Video["3_star"] ?>], 4 Stars [<?= $Video["4_star"] ?>], 5 Stars [<?= $Video["5_star"] ?>]">Ratings:</span> <?= $Video["1_star"] + $Video["2_star"] + $Video["3_star"] + $Video["4_star"] + $Video["5_star"]?><br>
                            <span>Comments:</span> <?= $Video["comments"] ?> | <span>Views:</span> <?= $Video["views"] ?><br>
                            <span>Status:</span> <strong><? if ($Video["status"] == 1) : ?><font color="red">Converting.</font><? else : ?><font color="green">Live!</font><? endif ?></strong><br>
                            <a href="/ajax/df/favorite?v=<?= $Video["url"] ?>"><button type="button">Remove from Favorites</button></a>
                        </td>
                    </tr>
                </table>
        <? endforeach ?>
            <div style="padding-top: 5px;text-align: left;border-top:0" class="vc_pagination"><?= $_PAGINATION->new_show(NULL,"") ?></div>
        <? else : ?>
        <center style="font-size: 20px;color:gray">You don't have any favorites!<br><span style="font-size:16px"><a href="/videos">Watch</a> some more awesome videos! :)</span></center>
        <? endif ?>
    </div>
</form>