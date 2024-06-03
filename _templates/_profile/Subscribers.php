<section>
    <div class="nm_box">
        <div class="prbx_hd nm_hd">
            Subscribers (<?= number_format($Profile["subscribers"]) ?>)
        </div>
        <div class="prbx_in nm_in">
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
            <? if ($Count <= 10) : ?></div><? endif ?>
        <div style="padding:5px;font-weight:bold;word-spacing:5px;text-align:right;font-size:15px;padding-bottom:0"><?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/subscribers",true) ?></div>
    </div>
</section>