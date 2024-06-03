<? if ((!$_USER->logged_in && $Recommended_Amount >= 4) || ($_USER->logged_in && $Modules["recommended"] && $Recommended_Amount >= 4)) : ?>
<div class="wdg" id="rec_widget">
    <div>
        <img src="/img/rec.png" alt="Recommended Videos"><span>Recommended Videos</span>
        <div class="wdg_sel">
            <? if ($_USER->logged_in) : ?>
                <div class="up1" onclick="move_up('rec_widget')"></div><div class="do1" onclick="move_down('rec_widget')"></div>
            <? endif ?>
        </div>
    </div>
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
</div>
<? endif ?>