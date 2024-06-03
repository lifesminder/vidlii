<? if (!$_USER->logged_in || ($_USER->logged_in && $Modules["featured"])) : ?>
<div class="wdg" id="ft_widget">
    <div style="background:#dae9fe">
        <a href="/special_videos?c=0&t=v"><img src="/img/ft.png" alt="Featured Videos"><span>Featured Videos</span></a>
        <div class="wdg_sel">
            <? if ($_USER->logged_in) : ?>
                <div class="up1" onclick="move_up('ft_widget')"></div><div class="do1" onclick="move_down('ft_widget')"></div>
            <? endif ?>
        </div>
    </div>
    <div>
        <div class="v_v_bx">
            <? foreach ($Featured_Videos as $Video) : ?>
                <div>
                    <div class="th">
                        <div class="th_t"><?= $Video["length"] ?></div>
                        <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="140" height="88"></a>
                    </div>
                    <a href="/watch?v=<?= $Video["url"] ?>" class="ba"><?= $Video["title"] ?></a>
                    <div class="vw s"><?= number_format($Video["views"]) ?> views</div>
                    <a href="/user/<?= $Video["displayname"] ?>" class="ch_l s"><?= $Video["displayname"] ?></a>
                    <div class="s_r"><?= show_ratings($Video,14,13) ?></div>
                </div>
            <? endforeach ?>
        </div>
    </div>
</div>
<? endif ?>