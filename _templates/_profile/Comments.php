<section>
    <div class="nm_box">
        <div class="nm_hd">
            Channel Comments (<a id="ch_nm" href="/user/<?= $Profile["displayname"] ?>/comments"><?= number_format($Profile["channel_comments"]) ?></a>)
        </div>
        <div class="nm_mn">
            <div id="ch_cmt_sct">
                <? foreach ($Comments as $Comment) : ?>
                    <div class="ch_cmt" id="c_<?= $Comment["id"] ?>">
                        <? if (($_USER->logged_in && $_USER->username == $Profile["username"]) or ($_USER->logged_in && $_USER->username == $Comment["username"])) : ?>
                            <a href="javascript:void(0)" onclick="d_cc(<?= $Comment["id"] ?>)" class="cd" style="left: 828px">Delete</a>
                        <? endif ?>
                        <?= user_avatar($Comment["displayname"],90,71,$Comment["avatar"]); ?>
                        <div>
                            <a href="/user/<?= $Comment["displayname"] ?>"><?= $Comment["displayname"] ?></a> | <strong><?= date("M d, Y",strtotime($Comment["date"])) ?></strong>
                            <div class="cmt_msg">
                                <?= htmlspecialchars($Comment["comment"]) ?>
                            </div>
                        </div>
                    </div>
                <? endforeach ?>
        </div>
            <div class="cmt_cmt" style="text-align: right"><?= $_PAGINATION->show(NULL,"/user/".$Profile["displayname"]."/".strtolower($Page),true) ?></div>
    </div>
</section>