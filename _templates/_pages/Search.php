<div class="search_hd">
    <div>
        <strong>Search: </strong>
        <a href="/results?q=<?= urlencode($Normal_Search_Query) ?>"><strong<? if (!isset($_GET["f"])) : ?> style="color:black"<? endif ?>>All</strong></a>
        <a href="/results?q=<?= urlencode($Normal_Search_Query) ?>&f=1"><strong<? if (isset($_GET["f"]) && $_GET["f"] == 1) : ?> style="color:black"<? endif ?>>Videos</strong></a>
        <a href="/results?q=<?= urlencode($Normal_Search_Query) ?>&f=2"><strong<? if (isset($_GET["f"]) && $_GET["f"] == 2) : ?> style="color:black"<? endif ?>>Users</strong></a></div>
    <div>
        About <?= number_format($Total) ?> "<?= htmlspecialchars($Normal_Search_Query) ?>" results
    </div>
</div>
<div>
    <? if (!isset($_GET["f"]) || $_GET["f"] == 1) : ?>
        <? if (isset($User) && !isset($_GET["p"])) : ?>
            <div class="result" style="margin-top:20px;width: 500px;">
                <div style="float:left;margin-right:10px">
                <?= user_avatar2($User["displayname"], 100,100,$User["avatar"]) ?>
                </div>
                <a href="/user/<?= $User["displayname"] ?>" class="r_title"><?= $User["displayname"] ?></a>
                <? if (!empty($User["channel_description"])) { echo cut_string($User["channel_description"],100); } else { echo "<em>No Description</em>"; } ?>
                <div style="margin-top:6px"><?= number_format($User["subscribers"]) ?> subscribers - <?= number_format($User["video_views"]) ?> video views - <?= number_format($User["channel_views"]) ?> channel views</div>
            </div>
        <? endif ?>
        <? foreach ($Videos as $Video) : ?>
            <div class="result">
                <div class="th">
                    <div class="th_t"><?= $Video["length"] ?></div>
                    <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="186" height="111"></a>
                </div>
                <a href="/watch?v=<?= $Video["url"] ?>" class="r_title"><?= $Video["title"] ?></a>
                By. <a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a><br>
                <?= get_time_ago($Video["uploaded_on"]) ?> - <?= number_format($Video["displayviews"]) ?> views
                <div class="r_descr">
                    <?= cut_string($Video["description"],135) ?>
                </div>
                <?= show_ratings($Video,15,14) ?>
            </div>
        <? endforeach ?>
    <? else : ?>
        <? foreach ($Channels as $User) : ?>
            <div class="result" style="margin-top:20px;width: 500px;">
                <div style="float:left;margin-right:10px">
                    <?= user_avatar2($User["displayname"], 100,100,$User["avatar"]) ?>
                </div>
                <a href="/user/<?= $User["displayname"] ?>" class="r_title"><?= $User["displayname"] ?></a>
                <? if (!empty($User["channel_description"])) { echo cut_string($User["channel_description"],100); } else { echo "<em>No Description</em>"; } ?>
                <div style="margin-top:6px"><?= number_format($User["subscribers"]) ?> subscribers - <?= number_format($User["video_views"]) ?> video views - <?= number_format($User["channel_views"]) ?> channel views</div>
            </div>
        <? endforeach ?>
    <? endif ?>
</div>
<div style="padding-top:5px;margin-top:17px;border-top: 1px solid #ccc;font-weight: bold;word-spacing:4px">
    <? if (!isset($_GET["f"])) : ?>
        <?= $_PAGINATION->new_show($_PAGINATION->Total,"q=".urlencode($_GET["q"])) ?>
    <? elseif ($_GET["f"] == 1) : ?>
        <?= $_PAGINATION->new_show($_PAGINATION->Total,"q=".urlencode($_GET["q"])."&f=1") ?>
    <? elseif ($_GET["f"] == 2) : ?>
        <?= $_PAGINATION->new_show($_PAGINATION->Total,"q=".urlencode($_GET["q"])."&f=2") ?>
    <? endif ?>
</div>
