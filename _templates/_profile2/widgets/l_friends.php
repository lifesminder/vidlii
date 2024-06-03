<div class="in_box ib_col user_section <? if ($Profile["friends_d"] == 1 && $Is_OWNER) : ?>hddn<? endif ?>" id="fr_l" module="fr_l">
    <div class="box_title">
        Friends (<a href="/user/<?= $Profile["displayname"] ?>/friends"><?= number_format($Profile["friends"]) ?></a>)
        <? if ($Is_OWNER) : ?>
            <div style="float: right;position:relative;top:2px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('fr_l')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('fr_l')"><img src="/img/laa0.png"> <img src="/img/raa1.png" onclick="move_hor('fr_l','fr_r')">
            </div>
        <? endif ?>
    </div>
    <div class="nm_mn">
        <? $Count = 0 ?>
        <div class="pr_user_box">
            <? foreach ($Friends as $Friend) : ?>
            <? $Count++ ?>
            <? if ($Count === 4) : ?></div><div class="pr_user_box"> <? endif ?>
            <div>
                <?= user_avatar2($Friend["displayname"],68,68,$Friend["avatar"],"pr_avt") ?><br>
                <a href="/user/<?= $Friend["displayname"] ?>"><?= $Friend["displayname"] ?></a>
            </div>
            <? endforeach ?>
            <? if ($Count >= 4) : ?></div> <? endif ?>
    </div>
    <? if ($Count <= 3) : ?></div><? endif ?>
</div>