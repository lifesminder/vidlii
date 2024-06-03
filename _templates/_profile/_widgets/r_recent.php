<section id="recent_activity3" <? if ($Profile["recent_d"] == 0 && $Is_OWNER) : ?>class="hddn"<? endif ?> module="re_r">
    <div class="nm_box">
        <div class="prbx_hd nm_hd">
            Recent Activity
            <? if ($Is_OWNER) : ?>
                <div style="float: right;position:relative;top:2.5px;word-spacing:-4px;cursor:pointer">
                    <img src="/img/uaa1.png" onclick="c_move_up('recent_activity3')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('recent_activity3')"><img src="/img/laa1.png" onclick="move_hor('recent_activity3','recent_activity2')"> <img src="/img/raa0.png">
                </div>
                <div style="margin-right:10px;float:right">
                    <a href="javascript:void(0)" onclick="$('#edit_ra').toggleClass('hddn')">Edit</a>
                </div>
            <? endif ?>
        </div>
        <? if ($Is_OWNER) : ?><span id="us" style="display:none"><?= $Profile["displayname"] ?></span><? endif ?>
        <div class="prbx_in nm_in ra1" id="nm_ra">
            <? if ($Is_OWNER) : ?>
                <div id="edit_ra" class="hddn" style="border:1.5px solid <?= hexToRgb($Profile["n_head"],$Normal_Trans) ?>;padding: 2px 10px 10px 10px;margin: 9px 0 7px">
                    <strong style="display: block;margin-bottom:6px;position:relative;right:5px">Recent Activity Settings</strong>
                    <table width="80%" style="margin:0 auto;position:relative;left: 45px">
                        <form action="/user/<?= $Profile["displayname"] ?>" method="POST">
                            <tr>
                                <td><label><input name="ra_comments" type="checkbox" style="position:relative;top:1.6px"<? if ($Profile["ra_comments"] == 1) : ?> checked<? endif ?>> Recent Comments</label></td>
                                <td><label><input name="ra_favorites" type="checkbox" style="position:relative;top:1.6px"<? if ($Profile["ra_favorites"] == 1) : ?> checked<? endif ?>> Recent Favorites</label></td>
                            </tr>
                            <tr>
                                <td><label><input name="ra_friends" type="checkbox" style="position:relative;top:1.6px"<? if ($Profile["ra_friends"] == 1) : ?> checked<? endif ?>> Recent Friends</label></td>
                                <td></td>
                            </tr>
                    </table>
                    <div style="text-align:center;margin-top:10px"><input type="submit" name="change_ra" style="font-size: 13px" value="Update Recent Activity"></div>
                    </form>
                </div>
            <? endif ?>
            <? if ($Is_OWNER) : ?><div style="margin-bottom: 2px;padding-bottom:6px;padding-top:7px;border-bottom: 1px solid <?= hexToRgb($Profile["n_head"],$Normal_Trans) ?>"><table cellspacing="0" cellpadding="0"><tr><td style="background-color:white;border:1px solid #666;color:#333;border-right:0;padding:1px;font-weight:bold;padding-left:3px"><?= $Profile["displayname"] ?></td><td width="100%"><input id="bulletin" style="border-radius:0;width:99%;background-color:white;border:1px solid #666;padding:2px;border-left:0;outline:0!important;padding-left: 4px;font-size:12px;color:dimgray;border-right:0" autocomplete="off" type="text" placeholder="..." maxlength="500"></td><td><input type="button" onclick="post_bulletin()" style="font-size:12px;padding:1px 14px" value="Post"></td></tr></table></div><? endif ?>
            <table class="ra" id="ra_in">
                <? if (count($Recent_Activity) > 0) : ?>
                    <? $Amount = count($Recent_Activity) ?>
                    <? $Count = 0 ?>
                    <? foreach ($Recent_Activity as $Activity) : ?>
                        <? $Count++ ?>
                        <? if ($Activity["type_name"] == "bulletin") : ?>
                            <tr id="b_<?= $Activity["id"] ?>">
                                <td valign="top" width="20"<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><img src="/img/ra1.png"></td>
                                <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><strong><?= $Profile["displayname"] ?></strong> <?= $Activity["content"] ?> <span>(<?= str_replace(" ","&nbsp;",time_ago($Activity["date"])) ?>)</span></td>
                                <? if ($Is_OWNER) : ?><td width="18" valign="middle" <? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><a href="javascript:void(0)" style="text-decoration: none" title="Delete" onclick="delete_bulletin(<?= $Activity["id"] ?>)">X</a></td><? endif ?>
                            </tr>
                        <? elseif ($Activity["type_name"] == "comment") : ?>
                            <tr>
                                <td valign="top" width="20"<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><img src="/img/ra2.png" style="height:16px;position:relative;left:1px"></td>
                                <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><strong><?= $Profile["displayname"] ?> commented on a video </strong><span>(<?= str_replace(" ","&nbsp;",time_ago($Activity["date"])) ?>)</span><br>
                                    <div style="float:left;margin:4px 4px 0 0"><?= video_thumbnail($Activity["id"],"",74,56,$Activity["title"]) ?></div><div style="width:72%;float:left;margin-top:4px;"><a href="/watch?v=<?= $Activity["id"] ?>"><?= cut_string($Activity["title"],35) ?></a><br><?= cut_string($Activity["content"],150) ?></div>
                                </td>
                                <td></td>
                            </tr>
                        <? elseif ($Activity["type_name"] == "favorite") : ?>
                            <tr>
                                <td valign="top" width="20"<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><img src="/img/ra3.png" style="height:15.5px;width:15.5px;position:relative;left:1.2px;top:0.5px"></td>
                                <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><strong><?= $Profile["displayname"] ?> favorited a video </strong><span>(<?= str_replace(" ","&nbsp;",time_ago($Activity["date"])) ?>)</span><br>
                                    <div style="float:left;margin:4px 4px 0 0"><?= video_thumbnail($Activity["id"],"",74,56,$Activity["title"]) ?></div><div style="width:72%;float:left;margin-top:4px;"><a href="/watch?v=<?= $Activity["id"] ?>"><?= cut_string($Activity["title"],35) ?></a><br><?= cut_string($Activity["content"],150) ?></div>
                                </td>
                                <td></td>
                            </tr>
                        <? elseif ($Activity["type_name"] == "friend") : ?>
                            <tr id="b_<?= $Activity["id"] ?>">
                                <td valign="top" width="20"<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><img src="/img/ra4.png" style="width: 15px;height:16px;position:relative;left:1.5px"></td>
                                <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><strong><?= $Profile["displayname"] ?> became friends with <? if ($Activity["id"] == $Profile["displayname"]) : ?><a href="/user/<?= $Activity["content"] ?>"><?= $Activity["content"] ?></a><? else : ?><a href="/user/<?= $Activity["id"] ?>"><?= $Activity["id"] ?></a><? endif ?></strong> <span>(<?= str_replace(" ","&nbsp;",time_ago($Activity["date"])) ?>)</span></td>
                                <td></td>
                            </tr>
                        <? endif ?>
                    <? endforeach ?>
                <? else : ?>
                    <div id="no_ra" style="font-size: 18px; text-align: center; padding: 12px 10px 9px">No Recent Activity</div>
                <? endif ?>
            </table>
        </div>
    </div>
</section>