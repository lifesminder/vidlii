<div class="in_box ib_col user_section <? if ($Profile["subscription_d"] == 0 && $Is_OWNER) : ?>hddn<? endif ?>" id="su1_r" module="s1_r">
    <div class="box_title">
        Subscriptions (<a href="/user/<?= $Profile["displayname"] ?>/subscriptions"><?= number_format($Profile["subscriptions"]) ?></a>)
        <? if ($Is_OWNER) : ?>
            <div style="float: right;position:relative;top:2px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('su1_r')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('su1_r')"><img src="/img/laa1.png" onclick="move_hor('su1_r','su1_l')"> <img src="/img/raa0.png">
            </div>
        <? endif ?>
        <? if ($Has_Subscribed) : ?>
            <div style="float: right;position:relative;top:7px;font-size:11px">
                <?= $Profile["displayname"] ?> is subscribed to you
            </div>
        <? endif ?>
    </div>
    <div class="nm_mn">
        <div class="pr_user_box big_user_box">
            <? foreach ($Subscriptions as $Subscription) : ?>
            <div>
                <?= user_avatar2($Subscription["subscription"],80,80,$Subscription["avatar"],"pr_avt") ?><br>
                <a href="/user/<?= $Subscription["subscription"] ?>"><?= $Subscription["subscription"] ?></a>
            </div>
            <? endforeach ?>
    </div>
    </div>
</div>