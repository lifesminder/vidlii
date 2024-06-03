<div class="in_box ib_col channel_comments_big<? if ($Profile["channel_d"] == 1 && $Is_OWNER) : ?> hddn<? endif ?>" id="cmt_l" module="co_l">
    <div class="box_title">
        Channel Comments
        <? if ($Is_OWNER) : ?>
            <a href="javascript:void(0)" style="position: absolute;right:68px;top:3px;font-size:18px" onclick="$('#edit_cc2').toggleClass('hddn')">Edit</a>
            <div style="float: right;position:relative;top:2.5px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('cmt_l')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('cmt_l')"><img src="/img/laa0.png"> <img src="/img/raa1.png" onclick="move_hor('cmt_l','cmt_r')">
            </div>
        <? endif ?>
    </div>
    <? if ($Is_OWNER) : ?>
        <div class="ch_edit_info hddn" id="edit_cc2" style="padding: 7px;margin-bottom: 14px">
            <strong style="color:#454545;display:block;font-size:15px;margin-bottom: 3px">Who can comment:</strong>
            <label style="padding-bottom:2px;display:block"><input type="radio" name="cc_setting2" id="cc_setting4"<? if ($Profile["channel_comment_privacy"] == 0) : ?> checked<? endif ?>> <strong>Everyone</strong> can comment on my channel</label>
            <label style="padding-bottom:2px;display:block"><input type="radio" name="cc_setting2" id="cc_setting5"<? if ($Profile["channel_comment_privacy"] == 1) : ?> checked<? endif ?>> <strong>Only Friends</strong> can comment on my channel</label>
            <label style="padding-bottom:2px;display:block"><input type="radio" name="cc_setting2" id="cc_setting6"<? if ($Profile["channel_comment_privacy"] == 2) : ?> checked<? endif ?>> <strong>Nobody</strong> can comment on my channel</label>
            <div style="border-top: 1px solid #cccccc;padding-top:7px;margin-top:3px"><button onclick="update_cc_privacy(true)">Save Changes</button></div>
        </div>
    <? endif ?>
    <div id="channel_comments2"<? if ($Profile["channel_comments"] == 0) : ?>class="no_border"<? endif ?>>
        <? if ($Profile["channel_comments"] > 0) : ?>
            <? foreach ($Comments as $Comment) : ?>
                <div class="chn_cmt_sct" id="cc2_<?= $Comment["id"] ?>">
                    <?= user_avatar2($Comment["displayname"],55,55,$Comment["avatar"],"pr_avt") ?>
                    <div>
                        <span><a href="/user/<?= $Comment["displayname"] ?>"><?= $Comment["displayname"] ?></a> <span>(<?= get_time_ago($Comment["date"]) ?>)</span></span>
                        <?= showBBcodes(hashtag_search(mention(nl2br(strip_tags($Comment["comment"]))))) ?>
                    </div>
                    <? if ($_USER->logged_in && ($_USER->username == $Comment["username"] || $Is_OWNER)) : ?>
                        <a href="javascript:void(0)" onclick="delete_cc(<?= $Comment["id"] ?>)" style="position: absolute;top:0;right:0">Delete</a>
                    <? endif ?>
                </div>
            <? endforeach ?>
        <? else : ?>
            <div id="no_comments2" style="text-align: center;font-size:13px;margin:15px 0">
                There are no comments for this user.
            </div>
        <? endif ?>
    </div>
    <? if ($Profile["channel_comments"] > 1) : ?>
        <div class="cc_pagination">
            <?= $Comment_Pagination->new_show($Profile["channel_comments"],"/user/".$Profile["displayname"],true) ?>
        </div>
    <? endif ?>
    <? if ($_USER->logged_in && $_USER->Is_Activated && (empty($_GET["page"]) || $_GET["page"] == "1") && !$Is_Blocked && !$Has_Blocked) : ?>
        <? if ($Profile["channel_comment_privacy"] == 0 || (($Profile["channel_comment_privacy"] == 1 && $Is_Friends == 1) || $Is_OWNER) || ($Profile["channel_comment_privacy"] == 1 && $Is_OWNER)) : ?>
            <div class="comment_box">
                <strong>Add Comment</strong>
                <textarea rows="4" maxlength="500" style="width:97%" id="comment_content2"></textarea>
                <button type="button" id="post_comment2">Post Comment</button>
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