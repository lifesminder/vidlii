<? if ($Modules["recommended"] && $Recommended_Amount >= 4) : ?>
<div class="wdg" id="rec_widget">
    <div>
        <img src="/img/rec.png" alt="Recommended Videos"><span>Recommended Videos</span>
        <div class="wdg_sel">
            <? if ($_USER->logged_in) : ?>
                <div class="up1" onclick="move_up('rec_widget')"></div><div class="do1" onclick="move_down('rec_widget')"></div>
            <? endif ?>
        </div>
    </div>
    <? if ($Recommended_Amount > 0) : ?>
    <div style="padding-bottom:0">
        <? if ($Recommended_Amount >= 4 && $Recommended_Amount < 8) : ?>
            <div class="v_v_bx">
                <? for ($x = 0; $x <= 3; $x++) : ?>
                    <div style="margin-bottom: 8px">
                        <div class="th">
                            <div class="th_t"><?= $Recommended_Videos[$x]["length"] ?></div>
                            <a href="/watch?v=<?= $Recommended_Videos[$x]["url"] ?>"><img class="vid_th" <?= $Recommended_Videos[$x]["thumbnail"] ?> width="140" height="88"></a>
                        </div>
                        <a href="/watch?v=<?= $Recommended_Videos[$x]["url"] ?>" class="ba"><?= $Recommended_Videos[$x]["title"] ?></a>
                        <div class="vw s"><?= number_format($Recommended_Videos[$x]["views"]) ?> views</div>
                        <a href="/user/<?= $Recommended_Videos[$x]["displayname"] ?>" class="ch_l s"><?= $Recommended_Videos[$x]["displayname"] ?></a>
                        <div class="s_r"><?= show_ratings($Recommended_Videos[$x],14,13) ?></div>
                    </div>
                <? endfor ?>
            </div>
        <? else : ?>
            <div class="v_v_bx">
                <? for ($x = 0; $x <= 3; $x++) : ?>
                    <div style="margin-bottom: 8px">
                        <div class="th">
                            <div class="th_t"><?= $Recommended_Videos[$x]["length"] ?></div>
                            <a href="/watch?v=<?= $Recommended_Videos[$x]["url"] ?>"><img class="vid_th" <?= $Recommended_Videos[$x]["thumbnail"] ?> width="140" height="88"></a>
                        </div>
                        <a href="/watch?v=<?= $Recommended_Videos[$x]["url"] ?>" class="ba"><?= $Recommended_Videos[$x]["title"] ?></a>
                        <div class="vw s"><?= number_format($Recommended_Videos[$x]["views"]) ?> views</div>
                        <a href="/user/<?= $Recommended_Videos[$x]["displayname"] ?>" class="ch_l s"><?= $Recommended_Videos[$x]["displayname"] ?></a>
                        <div class="s_r"><?= show_ratings($Recommended_Videos[$x],14,13) ?></div>
                    </div>
                <? endfor ?>
            </div>
            <div class="v_v_bx">
                <? for ($x = 4; $x <= 7; $x++) : ?>
                    <div style="margin-bottom: 8px">
                        <div class="th">
                            <div class="th_t"><?= $Recommended_Videos[$x]["length"] ?></div>
                            <a href="/watch?v=<?= $Recommended_Videos[$x]["url"] ?>"><img class="vid_th" <?= $Recommended_Videos[$x]["thumbnail"] ?> width="140" height="88"></a>
                        </div>
                        <a href="/watch?v=<?= $Recommended_Videos[$x]["url"] ?>" class="ba"><?= $Recommended_Videos[$x]["title"] ?></a>
                        <div class="vw s"><?= number_format($Recommended_Videos[$x]["views"]) ?> views</div>
                        <a href="/user/<?= $Recommended_Videos[$x]["displayname"] ?>" class="ch_l s"><?= $Recommended_Videos[$x]["displayname"] ?></a>
                        <div class="s_r"><?= show_ratings($Recommended_Videos[$x],14,13) ?></div>
                    </div>
                <? endfor ?>
            </div>
        <? endif ?>
    </div>
    <? else : ?>
        <div style="margin-bottom: 10px">
            <strong style="display:block">You have no subscriptions.</strong>
            <span style="font-size:12.5px;color:black">There are tons of awesome <a href="/channels" style="font-weight:bold">channels</a> on VidLii to watch and to subscribe to, so that you're always up to date when it comes to their newest videos!</span>
        </div>
    <? endif ?>
</div>
<? endif ?>