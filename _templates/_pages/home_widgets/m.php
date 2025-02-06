<? if ($Modules["most_popular"]) : ?>
<div class="wdg" id="most_popular">
    <div>
        <a href="#"><img src="/img/pop.png" alt="Most Popular Videos"><span>Most Popular</span></a>
        <div class="wdg_sel">
            <? if ($_USER->logged_in) : ?>
                <div class="up1" onclick="move_up('most_popular')"></div><div class="do1" onclick="move_down('most_popular')"></div>
            <? endif ?>
        </div>
    </div>
    <div>
        <div class="mp_hr">
            <?php
                $categories = [
                    1 => "Film & Animation",
                    2 => "Autos & Vehicles",
                    3 => "Music",
                    4 => "Pets & Animals",
                    5 => "Sports",
                    6 => "Travel & Events",
                    7 => "Gaming",
                    8 => "People & Blogs",
                    9 => "Comedy",
                    10 => "Entertainment",
                    11 => "News & Politics",
                    12 => "Howto & Style",
                    13 => "Education",
                    14 => "Science & Technology",
                    15 => "Nonprofits & Activism"
                ];
            ?>
            <? foreach ($feed["popular_videos"]["data"] as $Video => $Info) : ?>
                <div>
                    <a href="/videos?c=<?= $Info["category"] ?>&o=re&t=0"><?= $categories[$Info["category"]] ?></a>
                    <div class="th">
                        <div class="th_t"><?= $Info["length"] ?></div>
                        <a href="/watch?v=<?= $Info["url"] ?>"><img class="vid_th" src="/vi/<?= $Info["url"] ?>/hqresdefault.jpg" width="140" height="88"></a>
                    </div>
                    <div class="vr_i">
                        <a href="/watch?v=<?= $Info["url"] ?>" class="ln2"><?= $Info["title"] ?></a>
                        <div class="vw s"><?= number_format($Info["views"]) ?> views</div>
                        <a href="/user/<?= $Info["uploaded_by"] ?>" class="ch_l s"><?= $Info["uploaded_by"] ?></a>
                        <div class="s_r"><?= show_ratings($Info,14,13) ?></div>
                    </div>
                </div>
            <? endforeach ?>
        </div>
    </div>
</div>
<? endif ?>