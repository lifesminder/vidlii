<section class="<? if ($Profile["subscription_d"] == 0 && $Is_OWNER) : ?>hddn<? endif ?>" id="su1_r" module="s1_r">
    <div class="prbx_hd nm_hd">
        Subscriptions (<a href="/user/<?= $Profile["displayname"] ?>/subscriptions"><?= number_format($Profile["subscriptions"]) ?></a>)
        <? if ($Is_OWNER) : ?>
            <div style="float: right;position:relative;top:3px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('su1_r')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('su1_r')"><img src="/img/laa1.png" onclick="move_hor('su1_r','su1_l')"> <img src="/img/raa0.png">
            </div>
        <? endif ?>
        <? if ($Has_Subscribed) : ?>
            <div style="float: right;position:relative;top:0.5px;font-size:10px">
                <?= $Profile["displayname"] ?> is subscribed to you
            </div>
        <? endif ?>
    </div>
    <div class="prbx_in nm_in prbx_user">
        <div class="us_box big_user_box2 big_user_box2">
            <? foreach ($Subscriptions as $Subscription) : ?>
                <div>
                    <?= user_avatar2($Subscription["subscription"],80,80,$Subscription["avatar"],"pr_avt") ?><br>
                    <a href="/user/<?= $Subscription["subscription"] ?>"><?= $Subscription["subscription"] ?></a>
                </div>
            <? endforeach ?>
            </div>
    </div>
</section>