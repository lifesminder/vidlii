<? if (!$_USER->logged_in || ($_USER->logged_in && $Modules["being_watched"])) : ?>
<div class="wdg" id="be_widget">
    <div>
        <a href="/special_videos?c=0&t=b"><img src="/img/bw.png" alt="Videos Being Watched Now"><span>Videos Being Watched Now</span></a>
        <div class="wdg_sel">
            <? if ($_USER->logged_in) : ?>
                <div class="up1" onclick="move_up('be_widget')"></div><div class="do1" onclick="move_down('be_widget')"></div>
            <? endif ?>
        </div>
    </div>
    <div>
        <div class="bwn_l">
            <div class="th">
                <div class="th_t"><?= $Watched[0]["length"] ?></div>
                <a href="/watch?v=<?= $Watched[0]["url"] ?>"><img class="vid_th" <?= $Watched[0]["thumbnail"] ?> width="238" height="152"></a>
            </div>
            <a href="/watch?v=<?= $Watched[0]["url"] ?>" class="ba"><?= $Watched[0]["title"] ?></a>
            <div class="vw"><?= number_format($Watched[0]["views"]) ?> views</div>
            <a href="/user/<?= $Watched[0]["displayname"] ?>" class="ch_l"><?= $Watched[0]["displayname"] ?></a>
            <div class="s_r"><?= show_ratings($Watched[0],14,13) ?></div>
        </div>
        <div class="bwn_r">
            <div>
                <div class="th">
                    <div class="th_t"><?= $Watched[1]["length"] ?></div>
                    <a href="/watch?v=<?= $Watched[1]["url"] ?>"><img class="vid_th" <?= $Watched[1]["thumbnail"] ?> width="140" height="88"></a>
                </div>
                <div class="vr_i">
                    <a href="/watch?v=<?= $Watched[1]["url"] ?>" class="ln2"><?= $Watched[1]["title"] ?></a>
                    <div class="vw s"><?= number_format($Watched[1]["views"]) ?> views</div>
                    <a href="/user/<?= $Watched[1]["displayname"] ?>" class="ch_l s"><?= $Watched[1]["displayname"] ?></a>
                    <div class="s_r"><?= show_ratings($Watched[1],14,13) ?></div>
                </div>
            </div>
            <div>
                <div class="th">
                    <div class="th_t"><?= $Watched[2]["length"] ?></div>
                    <a href="/watch?v=<?= $Watched[2]["url"] ?>"><img class="vid_th" <?= $Watched[2]["thumbnail"] ?> width="140" height="88"></a>
                </div>
                <div class="vr_i">
                    <a href="/watch?v=<?= $Watched[2]["url"] ?>" class="ln2"><?= $Watched[2]["title"] ?></a>
                    <div class="vw s"><?= number_format($Watched[2]["views"]) ?> views</div>
                    <a href="/user/<?= $Watched[2]["displayname"] ?>" class="ch_l s"><?= $Watched[2]["displayname"] ?></a>
                    <div class="s_r"><?= show_ratings($Watched[2],14,13) ?></div>
                </div>
            </div>
            <div>
                <div class="th">
                    <div class="th_t"><?= $Watched[3]["length"] ?></div>
                    <a href="/watch?v=<?= $Watched[3]["url"] ?>"><img class="vid_th" <?= $Watched[3]["thumbnail"] ?> width="140" height="88"></a>
                </div>
                <div class="vr_i">
                    <a href="/watch?v=<?= $Watched[3]["url"] ?>" class="ln2"><?= $Watched[3]["title"] ?></a>
                    <div class="vw s"><?= number_format($Watched[3]["views"]) ?> views</div>
                    <a href="/user/<?= $Watched[3]["displayname"] ?>" class="ch_l s"><?= $Watched[3]["displayname"] ?></a>
                    <div class="s_r"><?= show_ratings($Watched[3],14,13) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<? endif ?>