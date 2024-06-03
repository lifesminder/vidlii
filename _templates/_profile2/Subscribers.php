<div class="pr_spacer"></div>
<div class="out_box ib_col" style="font-size: 12.5px;margin-bottom: 7px">
    <a href="/user/<?= $Profile["displayname"] ?>">« back to <?= $Profile["displayname"] ?>'s channel</a>
</div>
<div class="out_box ib_col user_section">
    <div class="box_title">
        Subscribers (<?= number_format($Profile["subscribers"]) ?>)
    </div>
    <div class="nm_mn">
        <? $Count = 0 ?>
        <div class="pr_user_box">
            <? foreach ($Subscribers as $Subscriber) : ?>
            <? $Count++ ?>
            <? if ($Count === 11) : ?></div><div class="pr_user_box"> <? endif ?>
            <div>
                <?= user_avatar2($Subscriber["subscriber"],68,68,$Subscriber["avatar"],"pr_avt") ?><br>
                <a href="/user/<?= $Subscriber["subscriber"] ?>"><?= $Subscriber["subscriber"] ?></a>
            </div>
            <? endforeach ?>
            <? if ($Count >= 11) : ?></div> <? endif ?>
    </div>
    <? if ($Count <= 10) : ?></div><? endif ?>
    <div style="padding:5px;font-weight:bold;word-spacing:5px;text-align:right"><?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/subscribers",true) ?></div>
</div>