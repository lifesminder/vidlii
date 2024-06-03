<div style="border: 1.5px solid red;padding:6px;text-align:center;margin-top:11px;font-size:14px;font-weight:bold"><?= $Banned_Header ?></div>
<div class="pr_spacer"></div>
<div style="display:none" id="ch_user"><?= $Profile["username"] ?></div>
<div style="display:none" id="ch_displayname"><?= $Profile["displayname"] ?></div>
<div class="out_box ob_col">
    <div class="in_box ib_col">
        <div class="pr_tp_hd">
            <div class="pr_pl_title ob_col">
                <?= user_avatar2($Profile["displayname"],36,36,"","ob_img") ?>
                <div class="pr_pl_title_name">
                    <?= $Profile["displayname"] ?>'s Channel
                </div>
                <div class="pr_pl_title_sub">
                    <? if (!$Is_Blocked && !$Has_Blocked) : ?>
                        <?= subscribe_button2($Profile["username"]) ?>
                    <? else : ?>
                        <?= subscribe_button2($Profile["username"], true) ?>
                    <? endif ?>
                </div>
            </div>
            <div class="pr_pl_title_sty">
                &nbsp;
            </div>
            <div class="pr_pl_nav">
                <div style="position: relative;font-size: 14px; top: 15px;margin-left:15px">
                    <?= $Profile["displayname"] ?> has been banned.
                </div>
            </div>
        </div>
    </div>
</div>
<div style="clear:both;height:10px"></div>
<div class="out_box ob_col" id="btm_pr" style="width:305px">
    <div class="pr_btm_l" style="width:unset;float:none;margin-right:0">
        <div class="in_box ib_col" id="pr_avt_box">
            <?= user_avatar2($Profile["displayname"],96,96,"a","pr_avt") ?>
            <div>
                <?= $Profile["displayname"] ?><br>
                <? if (!$Is_OWNER) : ?>
                    <? if (!$Is_Blocked && !$Has_Blocked) : ?>
                        <?= subscribe_button2($Profile["username"]) ?>
                    <? else : ?>
                        <?= subscribe_button2($Profile["username"], true) ?>
                    <? endif ?><br>
                <div>
                    <? if (!$_USER->logged_in) : ?>
                        <a href="/login">Add as Friend</a>
                    <? elseif ($Is_Friends === false && $_USER->Is_Activated && !$Is_Blocked && !$Has_Blocked) : ?>
                        <a href="javascript:void(0)" id="aaf">Add as Friend</a>
                    <? elseif ($Is_Blocked || $Has_Blocked) : ?>
                        <a href="javascript:void(0)" onclick="alert('You cannot interact with this user!')">Add as Friend</a>
                    <? elseif ($Is_Friends === 2 && $_USER->Is_Activated) : ?>
                        <a href="javascript:void(0)" id="aaf">Cancel Invite</a>
                    <? elseif ($Is_Friends === true && $_USER->Is_Activated) : ?>
                        <a href="javascript:void(0)" id="aaf">Unfriend</a>
                    <? elseif ($Is_Friends === 3 && $_USER->Is_Activated) : ?>
                        <a href="javascript:void(0)" id="aaf">Accept Invite</a>
                    <? elseif (!$_USER->Is_Activated) : ?>
                        <a href="javascript:void(0)" id="aaf" onclick="alert('You must activate your account with the email we sent you to add <?= $Profile["displayname"] ?> as a friend!')">Add as Friend</a>
                    <? endif ?><br>
                    <? if (!$Has_Blocked && !$Is_Blocked && $_USER->logged_in) : ?>
                        <a href="javascript:void(0)" onclick="block_user('<?= $Profile['username'] ?>')" id="bu">Block User</a><br>
                    <? elseif ($Has_Blocked) : ?>
                        <a href="javascript:void(0)" onclick="block_user('<?= $Profile['username'] ?>')" id="bu">Unblock User</a><br>
                    <? elseif ($Is_Blocked) : ?>
                        <a href="javascript:void(0)" id="bu">You're Blocked</a><br>
                    <? else : ?>
                        <a href="javascript:void(0)" onclick="alert('Please log in to block <?= $Profile["displayname"] ?>!')" id="bu">Block User</a><br>
                    <? endif ?>
                </div>
                <? else : ?>
                <div style="opacity:0.6;position:relative;bottom:2px;font-size:12.5px;line-height:16px">Your channel viewers will see links here, including "subscribe" and "add as friend".</div>
                <? endif ?>
            </div>
        </div>
    </div>
    <div class="pr_btm_r">

    </div>
</div>
