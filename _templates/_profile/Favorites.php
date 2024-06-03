<section>
    <div class="nm_box">
        <div class="prbx_hd nm_hd">
            Favorites (<?= number_format($Profile["favorites"]) ?>)
            <div style="float:right">
                <a<? if (isset($_POST["search_input"])) : ?> class="hddn"<? endif ?> href="javascript:void(0)" onclick="$(this).toggleClass('hddn');$('#searcher').toggleClass('hddn');$('#search_input').focus()">Search Favorites</a>
                <form action="/user/<?= $Profile["displayname"] ?>/favorites" method="POST" <? if (!isset($_POST["search_input"])) : ?>class="hddn"<? endif ?> style="position:relative;bottom:1px" id="searcher">
                    <input type="text" name="search" maxlength="64"<? if (isset($_POST["search"])) : ?> value="<?= $_POST["search"] ?>" <? endif ?>placeholder="Enter Search Term..." id="search_input" style="width:200px;border-radius:0;padding:1px"> <input type="submit" value="Search" name="search_input" class="search_button" style="border-radius:0;padding:1px 5px">
                </form>
            </div>
        </div>
        <div class="prbx_in nm_in">
            <? $Count = 0 ?>
            <? $Amount = count($Favorites) ?>
            <div class="vi_box nm_big">
                <? foreach ($Favorites as $Video) : ?>
                <? $Count++ ?>
                <? if ($Count === 6) : ?></div><div class="vi_box nm_big"> <? endif ?>
                <div>
                    <div class="th">
                        <div class="th_t"><?= $Video["length"] ?></div>
                        <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="160" height="110"></a>
                    </div>
                    <a href="/watch?v=<?= $Video["url"] ?>" class="ln2"><?= htmlspecialchars($Video["title"]) ?></a>
                    <span><?= get_time_ago($Video["uploaded_on"]) ?></span><br>
                    <?= number_format($Video["views"]) ?> views
                </div>
                <? if ($Count === 6) { $Count = 1; } ?>
                <? endforeach ?>
            </div>
            <? if (!isset($_POST["search_input"])) : ?>
            <div style="padding:5px;font-weight:bold;word-spacing:5px;text-align:right;font-size:15px;padding-bottom:0"><?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/favorites",true) ?></div>
            <? endif ?>
        </div>
</section>