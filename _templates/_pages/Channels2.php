<div class="vc_l">
    <div class="vc_cats">
        <div>Categories</div>
        <ul>
            <? if ($Current_Cat == 9) : ?>
                <li style="font-weight:bold;cursor:default">Partners</li>
            <? else : ?>
                <li><a href="/channels?c=9&o=<?= $Current_Order ?>&t=<?= $Current_time ?>">Partners</a></li>
            <? endif ?>
            <? foreach($Categories as $Num => $Category) : ?>
                <? if ($Current_Cat !== $Num) : ?>
                    <li><a href="/channels?c=<?= $Num ?>&o=<?= $Current_Order ?>&t=<?= $Current_time ?>"><?= $Category ?></a></li>
                <? else : ?>
                    <li style="font-weight:bold;cursor:default"><?= $Category ?></li>
                <? endif ?>
            <? endforeach ?>
        </ul>
    </div>
</div>
<div class="vc_r">
    <div class="vc_hd">
        <ul>
            <? foreach ($Header as $value => $item) : ?><? if ($value !== $Current_Order) : ?><li><a href="/channels?c=<?= $Current_Cat ?>&o=<?= $value ?>&t=<?= $Current_time ?>"><?= $item ?></a></li><? else : ?><li id="vc_selec"><?= $item ?></li><li style="padding:0 <? if ($Current_Order == "ms" || $Current_Order == "mc") : ?>60px<? elseif($Current_Order == "mv") : ?>48px<? else : ?>38px<? endif ?>"></li><? endif ?><? endforeach ?>
        </ul>
    </div>
    <div style="background:white;z-index:10;height:20px;width:700px;position:absolute"></div>
    <div class="vc_nav">
        <div style="float:left">
            <? if ($Current_Cat == 8) : ?>
                in <strong>All Channels</strong>
            <? else : ?>
                in <strong><? if ($Current_Cat != 9) : ?><?= $Categories[$Current_Cat] ?><? else : ?>Partners<? endif ?></strong>
            <? endif ?>
        </div>
        <div class="vc_nav_r">
            <ul>
                <li><? if ($Current_time != 1) : ?><a href="/channels?c=<?= $Current_Cat ?>&o=<?= $Current_Order ?>&t=1">This Week</a><? else : ?><strong>This Week</strong><? endif ?></li>
                <li><? if ($Current_time != 0) : ?><a href="/channels?c=<?= $Current_Cat ?>&o=<?= $Current_Order ?>&t=0">This Month</a><? else : ?><strong>This Month</strong><? endif ?></li>
                <li><? if ($Current_time != 2) : ?><a href="/channels?c=<?= $Current_Cat ?>&o=<?= $Current_Order ?>&t=2">All Time</a><? else : ?><strong>All Time</strong><? endif ?></li>
            </ul>
        </div>
    </div>
    <div class="vc_channels">
            <? if ($Current_Order == "mv") : ?>
            <? foreach ($Channels as $Channel) : ?>
                <div>
                <a href="/user/<?= $Channel["displayname"] ?>"><?= $Channel["displayname"] ?></a>
                <?= user_avatar2($Channel["displayname"],80,80,$Channel["avatar"]) ?>
                <div><?= number_format($Channel["videos"]) ?><br>Videos<br><br><?= number_format($Channel["video_views"]) ?><br>Views</div>
                </div>
            <? endforeach ?>
            <? elseif ($Current_Order == "ms") : ?>
                <? foreach ($Channels as $Channel) : ?>
                    <div>
                    <a href="/user/<?= $Channel["displayname"] ?>"><?= $Channel["displayname"] ?></a>
                    <?= user_avatar2($Channel["displayname"],80,80,$Channel["avatar"]) ?>
                    <div><?= number_format($Channel["videos"]) ?><br>Videos<br><br><?= number_format($Channel["subscribers"]) ?><br>Subscribers</div>
                    </div>
                <? endforeach ?>
            <? elseif ($Current_Order == "ml") : ?>
                <? foreach ($Channels as $Channel) : ?>
                    <div>
                    <a href="/user/<?= $Channel["displayname"] ?>"><?= $Channel["displayname"] ?></a>
                    <?= user_avatar2($Channel["displayname"],80,80,$Channel["avatar"]) ?>
                    <div><?= number_format($Channel["videos"]) ?><br>Videos<br><br><?= number_format((ceil(($Channel["sum_order"] - $Channel["sum_order2"]) / (MAX($Channel["videos"] / 4, 1))))) ?><br>Rating lvl.</div>
                    </div>
                <? endforeach ?>
            <? elseif ($Current_Order == "mc") : ?>
                <? foreach ($Channels as $Channel) : ?>
                    <div>
                    <a href="/user/<?= $Channel["displayname"] ?>"><?= $Channel["displayname"] ?></a>
                    <?= user_avatar2($Channel["displayname"],80,80,$Channel["avatar"]) ?>
                    <div><?= number_format($Channel["videos"]) ?><br>Videos<br><br><?= number_format($Channel["sum_order"]) ?><br>Comments</div>
                    </div>
                <? endforeach ?>
            <? endif ?>
    </div>
    <div class="cl"></div>
    <? if ($_PAGINATION->Total > 24) : ?>
    <div class="vc_pagination">
        <?= $_PAGINATION->new_show(null,"c=$Current_Cat&o=$Current_Order&t=$Current_time") ?>
    </div>
    <? endif ?>
</div>
<div class="cl"></div>