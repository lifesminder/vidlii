<section class="<? if ($Profile["subscriber_d"] == 0 && $Is_OWNER) : ?>hddn<? endif ?>" id="su2_r" module="s2_r">
    <div class="prbx_hd nm_hd">
        Subscribers (<a href="/user/<?= $Profile["displayname"] ?>/subscribers"><?= number_format($Profile["subscribers"]) ?></a>)
        <? if ($Is_OWNER) : ?>
            <div style="float: right;position:relative;top:3px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('su2_r')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('su2_r')"><img src="/img/laa1.png" onclick="move_hor('su2_r','su2_l')"> <img src="/img/raa0.png">
            </div>
        <? endif ?>
    </div>
    <div class="prbx_in nm_in prbx_user">
        <div class="us_box big_user_box2">
            <? foreach ($Subscribers as $Subscriber) : ?>
            <div>
                <?= user_avatar2($Subscriber["subscriber"],80,80,$Subscriber["avatar"],"pr_avt") ?><br>
                <a href="/user/<?= $Subscriber["subscriber"] ?>"><?= $Subscriber["subscriber"] ?></a>
            </div>
            <? endforeach ?>
            </div>
    </div>
</section>