<form action="my_favorites" method="POST">
    <div style="background: #e2e2e2;padding: 22.5px;position:relative">
        <? if (isset($c_un)) : ?>
            <div style="position:absolute;right:0;top:13px"><?= subscribe_button2($c_un) ?></div>
        <? endif ?>
    </div>
    <div style="border: 2px solid #e2e2e2; border-top: 0;padding: 10px;">
        <? if ($Videos) : ?>
            <div class="v_v_bx" id="big_subs">
                <? foreach ($Videos as $Video) : ?>
                    <div>
                        <div class="th">
                            <div class="th_t"><?= $Video["length"] ?></div>
                            <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="160" height="100"></a>
                        </div>
                        <a href="/watch?v=<?= $Video["url"] ?>" class="ba"><?= $Video["title"] ?></a>
                        <div class="vw s"><?= get_time_ago($Video["uploaded_on"]) ?></div>
                        <div class="vw s"><?= number_format($Video["views"]) ?> views</div>
                        <a href="/user/<?= $Video["displayname"] ?>" class="ch_l s"><?= $Video["displayname"] ?></a>
                        <div class="s_r"><?= show_ratings($Video,14,13) ?></div>
                    </div>
                <? endforeach ?>
            </div>
            <? if (!isset($_GET["c"])) : ?>
                <div style="padding-top: 5px"><?= $_PAGINATION->new_show(NULL,"") ?></div>
            <? else : ?>
                <div style="padding-top: 5px"><?= $_PAGINATION->new_show(NULL,"c=".$_GET["c"]) ?></div>
            <? endif ?>
        <? else : ?>
        <center style="font-size: 20px;color:gray; padding: 109px 0">Your subscriptions haven't uploaded any videos yet!</span></center>
        <? endif ?>
    </div>
</form>