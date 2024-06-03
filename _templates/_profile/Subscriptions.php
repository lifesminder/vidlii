<section>
    <div class="nm_box">
        <div class="prbx_hd nm_hd">
            Subscriptions (<?= number_format($Profile["subscriptions"]) ?>)
        </div>
        <div class="prbx_in nm_in">
            <? $Count = 0 ?>
            <div class="pr_user_box">
                <? foreach ($Subscriptions as $Subscription) : ?>
                <? $Count++ ?>
                <? if ($Count === 11) : ?></div><div class="pr_user_box"> <? endif ?>
                <div>
                    <?= user_avatar2($Subscription["subscription"],68,68,$Subscription["avatar"],"pr_avt") ?><br>
                    <a href="/user/<?= $Subscription["subscription"] ?>"><?= $Subscription["subscription"] ?></a>
                </div>
                <? endforeach ?>
                <? if ($Count >= 11) : ?></div> <? endif ?>
            <? if ($Count <= 10) : ?></div><? endif ?>
        <div style="padding:5px;font-weight:bold;word-spacing:5px;text-align:right;font-size:15px;padding-bottom:0"><?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/subscriptions",true) ?></div>
    </div>
</section>