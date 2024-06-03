<section class="<? if ($Profile["subscription_d"] == 1 && $Is_OWNER) : ?>hddn<? endif ?>" id="su1_l" module="s1_l">
    <div class="prbx_hd nm_hd">
        Subscriptions (<a href="/user/<?= $Profile["displayname"] ?>/subscriptions"><?= number_format($Profile["subscriptions"]) ?></a>)
        <? if ($Is_OWNER) : ?>
            <div style="float: right;position:relative;top:3px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('su1_l')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('su1_l')"><img src="/img/laa0.png"> <img src="/img/raa1.png" onclick="move_hor('su1_l','su1_r')">
            </div>
        <? endif ?>
        <? if ($Has_Subscribed) : ?>
        <div style="float: right;position:relative;top:1px;font-size:11px">
            Subscribed to you!
        </div>
        <? endif ?>
    </div>
    <div class="prbx_in nm_in prbx_user">
        <? $Count = 0 ?>
        <div class="us_box">
            <? foreach ($Subscriptions as $Subscription) : ?>
            <? $Count++ ?>
            <? if ($Count === 4) : ?></div><div class="us_box"> <? endif ?>
            <div>
                <?= user_avatar2($Subscription["subscription"],68,68,$Subscription["avatar"],"pr_avt") ?><br>
                <a href="/user/<?= $Subscription["subscription"] ?>"><?= $Subscription["subscription"] ?></a>
            </div>
            <? endforeach ?>
            <? if ($Count >= 4) : ?></div> <? endif ?>
    </div>
</section>