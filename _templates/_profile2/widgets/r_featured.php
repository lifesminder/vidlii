<div class="<? if ($Profile["featured_d"] == 1 && $Is_OWNER) : ?>hddn<? endif ?> in_box ib_col" id="ft_r" module="ft_r">
    <div class="box_title">
        <span id="ft_title2"><? if ($Profile["featured_title"] == "") : ?>Featured Channels<? else : ?><?= htmlspecialchars($Profile["featured_title"]) ?><? endif ?></span>
        <? if ($Is_OWNER) : ?><input type="text" class="hddn" id="ft_title_change2" value="<? if ($Profile["featured_title"] == "") : ?>Featured Channels<? else : ?><?= htmlspecialchars($Profile["featured_title"]) ?><? endif ?>" style="width:200px;font-size: 20px;border:1px solid gray;padding:0" maxlength="20"><? endif ?>
        <? if ($Is_OWNER) : ?>
            <a href="javascript:void(0)" style="position: absolute;right:77px;top:3px;font-size:18px" onclick="$('#ft_title_change2').toggleClass('hddn');$('#ft_title2').toggleClass('hddn');$('#add_ft2').toggleClass('hddn');save_ft_title(true)">Edit</a>
            <div style="float: right;position:relative;top:2.5px;word-spacing:-4px;cursor:pointer">
                <img src="/img/uaa1.png" onclick="c_move_up('ft_r')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('ft_r')"><img src="/img/laa1.png" onclick="move_hor('ft_r','ft_l')"> <img src="/img/raa0.png">
            </div>
        <? endif ?>
    </div>
    <div class="nm_mn" id="fc2">
        <? if ($Is_OWNER) : ?>
            <span id="add_ft2" class="hddn" style="text-align:center;margin-bottom:7px;display:block;">
                <input type="text" id="channel_add2" placeholder="Username..." maxlength="20" autocomplete="off" spellcheck="false"><button onclick="add_ft_channel()" type="button">Add</button>
            </span>
        <? endif ?>
        <? if (!empty($Profile["featured_channels"])) : ?>
            <? foreach ($Featured_Channels as $Channel) : ?>
                <div class="fc_sct2" id="fc2_<?= $Channel["username"] ?>">
                    <?= user_avatar2($Channel["displayname"],64,64,$Channel["avatar"],"pr_avt") ?>
                    <div style="float:left;width:390px">
                        <a href="/user/<?= $Channel["displayname"] ?>"><?= $Channel["displayname"] ?></a><br>
                        <? if (!empty($Channel["channel_description"])) : ?>
                            <?= limit_text($Channel["channel_description"], 70) ?>
                        <? else : ?>
                            <i>No description...</i>
                        <? endif ?><br>
                        <? if ($Is_OWNER) : ?><a href="javascript:void(0)" onclick="remove_ft('<?= $Channel["username"] ?>')">Remove</a><br><? endif ?>
                    </div>
                    <div>
                        Videos: <?= number_format($Channel["videos"]) ?><br>
                        Video Views: <?= number_format($Channel["video_views"]) ?><br>
                        Subscribers: <?= number_format($Channel["subscribers"]) ?>
                    </div>
                </div>
            <? endforeach ?>
        <? endif ?>
    </div>
</div>