<div style="border: 1.5px solid red;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold"><?= $Banned_Header ?></div>
<div class="pr_l">
    <section>
        <div style="display:none" id="ch_user"><?= $Profile["username"] ?></div>
        <div style="display:none" id="ch_displayname"><?= $Profile["displayname"] ?></div>
        <div class="prbx_hd hl_hd">
            <div><? if (empty($Profile["channel_title"])) : ?><?= $Profile["displayname"] ?> Channel<? else : ?><?= $Profile["channel_title"] ?><? endif ?></div>
            <? if (!$Is_Blocked && !$Has_Blocked) : ?>
            <div><div class="valign"><? if (!$Is_OWNER) : ?><?= subscribe_button2($Profile["username"]) ?><? else : ?><a href="/my_account" class="yel_btn">Edit Channel</a><? endif ?></div></div>
            <? else : ?>
                <div><div class="valign"><?= subscribe_button2($Profile["username"],true) ?></div></div>
            <? endif ?>
        </div>
        <div class="prbx_in hl_in" style="padding-bottom:5px">
            <div class="hl_in_top">
                <div>
                <div>
                    <div>
                        <?= user_avatar2($Profile["displayname"],96,96,"a") ?>
                    </div>
                </div>
                </div>
                <div>
                    <strong><?= $Profile["displayname"] ?></strong>
                    <span>Joined: <strong><?= date("M d, Y",strtotime($Profile["reg_date"])) ?></strong></span>
                    <span>Subscribers: <strong><?= number_format($Profile["subscribers"]) ?></strong></span>
                </div>
            </div>
            <div class="cl"></div>
        </div>
    </section>
    <section>
        <div class="prbx_hd nm_hd">
            <? if (empty($Profile["connect"])) : ?>Connect with <?= $Profile["displayname"] ?><? else : ?><?= $Profile["connect"] ?><? endif ?>
        </div>
        <div class="prbx_in nm_in">
            <table class="connect" width="100%">
                <tbody><tr>
                    <td width="39%" align="right" valign="middle"><?= user_avatar2($Profile["displayname"],64,64,"a","pr_avt") ?></td>
                    <td class="connectl">
                        <? if (!$_USER->logged_in) : ?>
                            <a href="javascript:void(0)" onclick="alert('Please log in to block <?= $Profile["displayname"] ?>!')"><img class="c_l" id="c_bu" src="/img/block0.png">Block User</a><br>
                        <? elseif (!$Is_Blocked && !$Has_Blocked && $_USER->username !== $Profile["username"]) : ?>
                            <a href="javascript:void(0)" onclick="block_user('<?= $Profile["username"] ?>')" onmouseenter="$('#c_bu').attr('src','/img/block1.png')" onmouseleave="$('#c_bu').attr('src','/img/block0.png')"><img class="c_l" id="c_bu" src="/img/block0.png"><span id="bu">Block User</span></a><br>
                        <? elseif ($Is_Blocked && $_USER->username !== $Profile["username"]) : ?>
                            <a href="javascript:void(0)" onclick="alert('You have been blocked by <?= $Profile["displayname"] ?>!')"><img class="c_l" id="c_bu" src="/img/block0.png">You're Blocked</a><br>
                        <? elseif ($Has_Blocked && $_USER->username !== $Profile["username"]) : ?>
                            <a href="javascript:void(0)" onclick="block_user('<?= $Profile["username"] ?>')" onmouseenter="$('#c_bu').attr('src','/img/block1.png')" onmouseleave="$('#c_bu').attr('src','/img/block0.png')"><img class="c_l" id="c_bu" src="/img/block0.png"><span id="bu">Unblock User</span></a><br>
                        <? else : ?>
                            <a href="javascript:void(0)" onclick="alert('Why do you dislike yourself?')"><img class="c_l" id="c_bu" src="/img/block0.png">Block User</a><br>
                        <? endif ?>
                            <? if (!$_USER->logged_in) : ?>
                                <a href="javascript:void(0)" onclick="alert('Please log in to add <?= $Profile["displayname"] ?> to friends!')"><img class="c_l" id="c_af" src="/img/friend0.png">Add as Friend</a>
                            <? elseif ($Is_OWNER) : ?>
                                <a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')" onclick="alert('You cannot add yourself as a friend!')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Add as Friend</span></a>
                            <? elseif ($Is_Blocked == true || $Has_Blocked == true) : ?>
                                <a href="javascript:void(0)" onclick="alert('You cannot interact with this user!')"><img class="c_l" id="c_af" src="/img/friend0.png">Add as Friend</a>
                            <? elseif ($Is_Friends === false && $_USER->Is_Activated) : ?>
                                <a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Add as Friend</span></a>
                            <? elseif ($Is_Friends === 2 && $_USER->Is_Activated) : ?>
                                <a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Cancel Invite</span></a>
                            <? elseif ($Is_Friends === true && $_USER->Is_Activated) : ?>
                                <a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Unfriend</span></a>
                            <? elseif ($Is_Friends === 3 && $_USER->Is_Activated) : ?>
                                <a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Accept Invite</span></a>
                            <? elseif (!$_USER->Is_Activated) : ?>
                                <a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')" onclick="alert('You must activate your account with the email we sent you to add <?= $Profile["displayname"] ?> as a friend!')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Add as Friend</span></a>
                            <? endif ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="connect_lnk"><a href="/user/<?= $Profile["displayname"] ?>">/user/<?= $Profile["displayname"] ?></a></div>
        </div>
    </section>
</div>
<div class="pr_r">

</div>
