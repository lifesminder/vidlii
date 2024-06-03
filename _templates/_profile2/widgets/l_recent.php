<div id="recent_activity2" <? if ($Profile["recent_d"] == 1 && $Is_OWNER) : ?>class="hddn"<? endif ?> module="re_l">
<div class="in_box ib_col" id="recent_activity">
        <div class="box_title">
            Recent Activity
            <? if ($Is_OWNER) : ?>
                <a href="javascript:void(0)" style="position: absolute;right:77px;top:3px;font-size:18px" onclick="$('#edit_ra2').toggleClass('hddn')">Edit</a>
                <div style="float: right;position:relative;top:2.5px;word-spacing:-4px;cursor:pointer">
                    <img src="/img/uaa1.png" onclick="c_move_up('recent_activity2')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('recent_activity2')"><img src="/img/laa0.png"> <img src="/img/raa1.png" onclick="move_hor('recent_activity2','recent_activity3')">
                </div>
            <? endif ?>
        </div>
        <? if ($Is_OWNER) : ?>
            <div class="ch_edit_info hddn" id="edit_ra2" style="padding: 7px;margin-bottom: 14px">
                <strong style="color:#454545;display:block;font-size:15px;margin-bottom: 3px">Recent Activity Privacy:</strong>
                <form action="/user/<?= $Profile["displayname"] ?>" method="POST">
                    <label style="display:block;padding-bottom:3px"><input name="ra_comments" type="checkbox"<? if ($Profile["ra_comments"] == 1) : ?> checked<? endif ?>> Recent Comments</label>
                    <label style="display:block;padding-bottom:3px"><input name="ra_favorites" type="checkbox"<? if ($Profile["ra_favorites"] == 1) : ?> checked<? endif ?>> Recent Favorites</label>
                    <label style="display:block;padding-bottom:3px"><input name="ra_friends" type="checkbox"<? if ($Profile["ra_friends"] == 1) : ?> checked<? endif ?>> Recent Friends</label>
                    <div style="border-top: 1px solid #cccccc;padding-top:7px;margin-top:3px"><input type="submit" name="save_ra" value="Save Changes"></div>
                </form>
            </div>
        <? endif ?>
        <? if ($Is_OWNER) : ?><div style="margin-bottom: 2px;padding-bottom:9px"><table cellspacing="0" cellpadding="0"><tr><td style="background-color:white;border:1px solid #666;color:#333;border-right:0;padding:2px 1px 1px;font-weight:bold;padding-left:3px"><?= $Profile["displayname"] ?></td><td width="100%"><input id="bulletin2" style="border-radius:0;width:99%;background-color:white;border:1px solid #666;padding:4px;border-left:0;outline:0!important;padding-left: 4px;font-size:13px;color:dimgray;border-right:0" autocomplete="off" type="text" placeholder="..." maxlength="500"></td><td><input type="button" onclick="post_bulletin()" style="font-size:13px;padding:3px 14px" value="Post"></td></tr></table></div><? endif ?>
        <table class="ra" id="ra_in2" <? if (count($Recent_Activity) == 0) : ?>style="display:none"<? endif ?>>
            <? if (count($Recent_Activity) > 0) : ?>
                <? $Amount = count($Recent_Activity) ?>
                <? $Count = 0 ?>
                <? foreach ($Recent_Activity as $Activity) : ?>
                    <? $Count++ ?>
                    <? if ($Activity["type_name"] == "bulletin") : ?>
                        <tr id="b2_<?= $Activity["id"] ?>">
                            <td valign="top" width="21"<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><img src="/img/ra1.png"></td>
                            <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><strong><?= $Profile["displayname"] ?></strong> <?= $Activity["content"] ?> <span>(<?= str_replace(" ","&nbsp;",time_ago($Activity["date"])) ?>)</span></td>
                            <? if ($Is_OWNER) : ?><td width="21" valign="middle" <? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><a href="javascript:void(0)" style="text-decoration: none" title="Delete" onclick="delete_bulletin(<?= $Activity["id"] ?>)">X</a></td><? else : ?><td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>></td><? endif ?>
                        </tr>
                    <? elseif ($Activity["type_name"] == "comment") : ?>
                        <tr>
                            <td valign="top" width="21"<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><img src="/img/ra2.png" style="height:16px;position:relative;left:1px"></td>
                            <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><strong><?= $Profile["displayname"] ?> commented on a video </strong><span>(<?= str_replace(" ","&nbsp;",time_ago($Activity["date"])) ?>)</span><br>
                                <div style="float:left;margin:4px 4px 0 0"><?= video_thumbnail($Activity["id"],"",74,56,$Activity["title"]) ?></div><div style="word-break:break-all;width:61%;float:left;margin-top:4px;"><a href="/watch?v=<?= $Activity["id"] ?>"><?= cut_string($Activity["title"],35) ?></a><br><?= cut_string($Activity["content"],150) ?></div>
                            </td>
                            <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>></td>
                        </tr>
                    <? elseif ($Activity["type_name"] == "favorite") : ?>
                        <tr>
                            <td valign="top" width="21"<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><img src="/img/ra3.png" style="height:15.5px;width:15.5px;position:relative;left:1.2px;top:1px"></td>
                            <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><strong><?= $Profile["displayname"] ?> favorited a video </strong><span>(<?= str_replace(" ","&nbsp;",time_ago($Activity["date"])) ?>)</span><br>
                                <div style="float:left;margin:4px 4px 0 0"><?= video_thumbnail($Activity["id"],"",74,56,$Activity["title"]) ?></div><div style="word-break:break-all;width:61%;float:left;margin-top:4px;"><a href="/watch?v=<?= $Activity["id"] ?>"><?= cut_string($Activity["title"],35) ?></a><br><?= cut_string($Activity["content"],150) ?></div>
                            </td>
                            <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>></td>
                        </tr>
                    <? elseif ($Activity["type_name"] == "friend") : ?>
                        <tr id="b_<?= $Activity["id"] ?>">
                            <td valign="top" width="21"<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><img src="/img/ra4.png" style="width: 15px;height:16px;position:relative;left:1.5px"></td>
                            <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>><strong><?= $Profile["displayname"] ?> became friends with <? if ($Activity["id"] == $Profile["displayname"]) : ?><a href="/user/<?= $Activity["content"] ?>"><?= $Activity["content"] ?></a><? else : ?><a href="/user/<?= $Activity["id"] ?>"><?= $Activity["id"] ?></a><? endif ?></strong> <span>(<?= str_replace(" ","&nbsp;",time_ago($Activity["date"])) ?>)</span></td>
                            <td<? if ($Count == $Amount) : ?> style="border:0"<? endif ?>></td>
                        </tr>
                    <? endif ?>
                <? endforeach ?>
            <? else : ?>
                <div id="no_ra2" style="font-size: 18px; text-align: center; padding: 10px 10px 14px">No Recent Activity</div>
            <? endif ?>
        </table>
    </div>
</div>