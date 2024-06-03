<div class="vc_l">
    <div class="vc_cats">
        <div>Categories</div>
        <ul>
            <? foreach($Categories as $Num => $Category) : ?>
                <? if ($Current_Cat !== $Num) : ?>
                    <li><a href="/special_videos?c=<?= $Num ?>&t=<?= $Current_Order ?>"><?= $Category ?></a></li>
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
            <? foreach ($Header as $value => $item) : ?><? if ($value !== $Current_Order) : ?><li><a href="/special_videos?c=<?= $Current_Cat ?>&t=<?= $value ?>"><?= $item ?></a></li><? else : ?><li id="vc_selec"><?= $item ?></li><? if ($Current_Order == "f") : ?><li style="padding:0 32px"></li><? elseif ($Current_Order == "mv") : ?><li style="padding:0 48px"></li><? elseif ($Current_Order == "md") : ?><li style="padding:0 60px"></li><? elseif ($Current_Order == "tr") : ?><li style="padding:0 29px"></li><? endif ?><? endif ?><? endforeach ?>
        </ul>
    </div>
    <div style="background:white;z-index:10;height:20px;width:600px;position:absolute"></div>
    <div class="vc_nav">
        <div style="float:left">
            <? if ($Current_Cat == 0) : ?>
                in <strong>All Categories</strong>
            <? else : ?>
                in <strong><?= $Categories[$Current_Cat] ?></strong>
            <? endif ?>
        </div>
    </div>
    <? if ($Videos) : ?>
    <div class="v_v_bx" id="vc_videos">
        <? foreach ($Videos as $Video) : ?>
            <div>
                <div class="th">
                    <div class="th_t"><?= $Video["length"] ?></div>
                    <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="176" height="107"></a>
                </div>
                <a href="/watch?v=<?= $Video["url"] ?>" class="ba"><?= $Video["title"] ?></a>
                <div class="vw s"><?= number_format($Video["views"]) ?> views</div>
                <a href="/user/<?= $Video["uploaded_by"] ?>" class="ch_l s"><?= $Video["uploaded_by"] ?></a>
                <div class="s_r"><?= show_ratings($Video,14,13) ?></div>
            </div>
        <? endforeach ?>
    </div>
    <? if ($Current_Order !== "b" && $_PAGINATION->Total > 16) : ?>
    <div class="vc_pagination">
        <?= $_PAGINATION->new_show(null,"c=$Current_Cat&t=$Current_Order") ?>
    </div>
    <? endif ?>
    <? else : ?>
        <div style="text-align: center; font-size: 20px; margin-top: 146px; color: #616161">No Videos found</div>
    <? endif ?>
</div>
<div class="cl"></div>