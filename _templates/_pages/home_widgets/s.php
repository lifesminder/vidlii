<? if ($_USER->logged_in && $Modules["subscriptions"]) : ?>
<div class="wdg" id="sub_widget">
    <div>
        <a href="/my_subscriptions"><img src="/img/sub.png" alt="Subscriptions"><span>Subscriptions</span></a>
        <div class="wdg_sel">
            <? if ($_USER->logged_in) : ?>
                <div class="up1" onclick="move_up('sub_widget')"></div><div class="do1" onclick="move_down('sub_widget')"></div>
            <? endif ?>
        </div>
    </div>
    <div>
        <? if ($Stats["subscriptions"] > 0) : ?>
            <? if ($Subscription_Videos) : ?>
                <div class="v_v_bx">
                    <? foreach ($Subscription_Videos as $Video) : ?>
                        <div>
                            <div class="th">
                                <div class="th_t"><?= $Video["length"] ?></div>
                                <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="140" height="88"></a>
                            </div>
                            <a href="/watch?v=<?= $Video["url"] ?>" class="ba"><?= $Video["title"] ?></a>
                            <div class="vw s"><?= get_time_ago($Video["uploaded_on"]) ?></div>
                            <div class="vw s"><?= number_format($Video["views"]) ?> views</div>
                            <a href="/user/<?= $Video["displayname"] ?>" class="ch_l s"><?= $Video["displayname"] ?></a>
                            <div class="s_r"><?= show_ratings($Video,14,13) ?></div>
                        </div>
                    <? endforeach ?>
                </div>
            <? else : ?>
                <div style="margin-bottom: 10px">
                    <strong style="display:block">Your subscriptions haven't uploaded videos yet!</strong>
                    <span style="font-size:12.5px;color:black">In the meantime, why don't you search for some other cool <a href="/channels" style="font-weight:bold">channels</a> to subscribe to?</span>
                </div>
            <? endif ?>
        <? else : ?>
            <div style="margin-bottom: 10px">
                <strong style="display:block">You have no subscriptions.</strong>
                <span style="font-size:12.5px;color:black">There are tons of awesome <a href="/channels" style="font-weight:bold">channels</a> on VidLii to watch and to subscribe to, so that you're always up to date when it comes to their newest videos!</span>
            </div>
        <? endif ?>
    </div>
</div>
<? endif ?>