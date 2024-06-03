<section class="<? if ($Profile["friends_d"] == 0 && $Is_OWNER) : ?>hddn<? endif ?>" id="fr_r" module="fr_r">
    <div class="prbx_hd nm_hd">
        Friends (<a href="/user/<?= $Profile["displayname"] ?>/friends"><?= number_format($Profile["friends"]) ?></a>)
        <? if ($Is_OWNER) : ?>
            <div style="float: right;position:relative;top:3px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('fr_r')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('fr_r')"><img src="/img/laa1.png" onclick="move_hor('fr_r','fr_l')"> <img src="/img/raa0.png">
            </div>
        <? endif ?>
    </div>
    <div class="prbx_in nm_in prbx_user">
        <div class="us_box big_user_box2">
            <? foreach ($Friends as $Friend) : ?>
            <div>
                <?= user_avatar2($Friend["displayname"],80,80,$Friend["avatar"],"pr_avt") ?><br>
                <a href="/user/<?= $Friend["displayname"] ?>"><?= $Friend["displayname"] ?></a>
            </div>
            <? endforeach ?>
            </div>
    </div>
</section>