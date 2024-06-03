<section class="channel_comments_big <? if ($Profile["channel_d"] == 1 && $Is_OWNER) : ?> hddn<? endif ?>" id="cmt_l" module="co_l">
    <div class="prbx_hd nm_hd">
        Channel Comments
        <? if ($Is_OWNER) : ?>
            <div style="float: right;position:relative;top:2.5px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('cmt_l')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('cmt_l')"><img src="/img/laa0.png"> <img src="/img/raa1.png" onclick="move_hor('cmt_l','cmt_r')">
            </div>
            <div style="margin-right:10px;float:right">
                <a href="javascript:void(0)" onclick="$('#edit_cc2').toggleClass('hddn')">Edit</a>
            </div>
        <? endif ?>
    </div>
    <div class="prbx_in nm_in">
        <? if ($Is_OWNER) : ?>
            <div class="hddn" id="edit_cc2" style="border:1.5px solid <?= hexToRgb($Profile["n_head"],$Normal_Trans) ?>;padding: 2px 10px 10px 10px;font-size:13px;margin: 9px 0 7px">
                <strong style="display: block;margin-bottom:6px;position:relative;right:5px">Who can comment</strong>
                <label style="padding-bottom:2px;display:block"><input type="radio" name="cc_setting2" id="cc_setting4"<? if ($Profile["channel_comment_privacy"] == 0) : ?> checked<? endif ?>> <strong>Everyone</strong> can comment on my channel</label>
                <label style="padding-bottom:2px;display:block"><input type="radio" name="cc_setting2" id="cc_setting5"<? if ($Profile["channel_comment_privacy"] == 1) : ?> checked<? endif ?>> <strong>Only Friends</strong> can comment on my channel</label>
                <label style="padding-bottom:2px;display:block"><input type="radio" name="cc_setting2" id="cc_setting6"<? if ($Profile["channel_comment_privacy"] == 2) : ?> checked<? endif ?>> <strong>Nobody</strong> can comment on my channel</label>
                <div style="padding-top:7px;text-align:center"><button onclick="update_cc_privacy(true)">Update Comment Privacy</button></div>
            </div>
        <? endif ?>
        <? if ($Profile["channel_comments"] > 0) : ?>
            <div id="ch_cmt_sct2">
                <? foreach ($Comments as $Comment) : ?>
                    <div class="ch_cmt" id="cc2_<?= $Comment["id"] ?>">
                        <? if (($_USER->logged_in && $_USER->username == $Profile["username"]) or ($_USER->logged_in && $_USER->username == $Comment["username"])) : ?>
                            <a href="javascript:void(0)" onclick="delete_cc(<?= $Comment["id"] ?>)" class="cd">Delete</a>
                        <? endif ?>
                        <?= user_avatar2($Comment["displayname"],68,68,$Comment["avatar"],"pr_avt"); ?>
                        <div>
                            <a href="/user/<?= $Comment["displayname"] ?>"><?= $Comment["displayname"] ?></a> (<?= get_time_ago($Comment["date"]) ?>)
                            <div class="cmt_msg">
                                <?= showBBcodes(hashtag_search(mention(nl2br(strip_tags($Comment["comment"]))))) ?>
                            </div>
                        </div>
                    </div>
                <? endforeach ?>
            </div>
            <div class="cc_pagination"><?= $Comment_Pagination->new_show($Profile["channel_comments"],"/user/".$Profile["displayname"],true) ?></div>
        <? else : ?>
            <div id="ch_cmt_sct2">
                <div id="no_comments2" style="margin:7px 0;text-align: center;font-size: 13px">There are no comments for this user.</div>
            </div>
        <? endif ?>
        <? if ($_USER->logged_in && $_USER->Is_Activated && (empty($_GET["page"]) || $_GET["page"] == "1") && !$Is_Blocked && !$Has_Blocked) : ?>
            <? if ($Profile["channel_comment_privacy"] == 0 || (($Profile["channel_comment_privacy"] == 1 && $Is_Friends == 1) || $Is_OWNER) || ($Profile["channel_comment_privacy"] == 1 && $Is_OWNER)) : ?>
                <div class="comment_box">
                    <strong>Add Comment</strong>
                    <textarea style="width:98.5%" rows="5" maxlength="500" id="comment_content2"></textarea>
                    <button type="button" id="post_comment4">Post Comment</button>
                </div>
            <? elseif ($Profile["channel_comment_privacy"] == 1) : ?>
                <div style="text-align: center;margin-top:9px">You must be friends with <strong><?= $Profile["displayname"] ?></strong> to post a comment!</div>
            <? elseif ($Profile["channel_comment_privacy"] == 2) : ?>
                <div style="text-align: center;margin-top:9px"><strong><?= $Profile["displayname"] ?></strong> has disabled his channel comments!</div>
            <? endif ?>
        <? elseif (!$_USER->logged_in) : ?>
            <div style="text-align: center;margin-top:9px">Please <strong><a href="/login">log in</a></strong> to post a comment!</div>
        <? elseif (!$_USER->Is_Activated) : ?>
            <div style="text-align: center;margin-top:9px">Please <strong>click on the activation link</strong> we sent to your email to post a comment!</div>
        <? elseif($Is_Blocked || $Has_Blocked) : ?>
            <div style="text-align: center;margin-top:9px">You cannot interact with <strong><?= $Profile["displayname"] ?></strong>!</div>
        <? endif ?>
    </div>
</section>