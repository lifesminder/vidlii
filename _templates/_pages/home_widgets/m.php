<? if (!$_USER->logged_in || ($_USER->logged_in && $Modules["most_popular"])) : ?>
<div class="wdg" id="most_popular">
    <div>
        <img src="/img/pop.png" alt="Most Popular Videos"><span>Most Popular</span>
        <div class="wdg_sel">
            <? if ($_USER->logged_in) : ?>
                <div class="up1" onclick="move_up('most_popular')"></div><div class="do1" onclick="move_down('most_popular')"></div>
            <? endif ?>
        </div>
    </div>
    <div>
        <div class="mp_hr">
            <? foreach ($Popular as $Video => $Info) : ?>
                <div>
                    <a href="/videos?c=<?= $Info["category"] ?>&o=re&t=0"><?= $Video ?></a>
                    <div class="th">
                        <div class="th_t"><?= $Info["length"] ?></div>
                        <a href="/watch?v=<?= $Info["url"] ?>"><img class="vid_th" <?= $Info["thumbnail"] ?> width="140" height="88"></a>
                    </div>
                    <div class="vr_i">
                        <a href="/watch?v=<?= $Info["url"] ?>" class="ln2"><?= $Info["title"] ?></a>
                        <div class="vw s"><?= number_format($Info["views"]) ?> views</div>
                        <a href="/user/<?= $Info["displayname"] ?>" class="ch_l s"><?= $Info["displayname"] ?></a>
                        <div class="s_r"><?= show_ratings($Info,14,13) ?></div>
                    </div>
                </div>
            <? endforeach ?>
        </div>
    </div>
</div>
<? endif ?>