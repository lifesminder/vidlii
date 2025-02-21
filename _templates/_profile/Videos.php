<section>
    <div class="nm_box">
        <div class="prbx_hd nm_hd">
            Videos (<?= number_format($Profile["videos"]) ?>)
            <div style="float:right">
                <a href="#">Subscribe to <?= $Profile["displayname"] ?>'s videos</a>
            </div>
        </div>
        <div class="prbx_in nm_in">
            <?php if(number_format($Profile["videos"]) > 1) { ?>
            <div style="padding: 5px; display: block; margin-bottom: 30px;">
                <div style="margin: 5px 0 5px 5px; float: left;">
                    <?php if(!isset($_GET["sort"])) { ?><span>Videos</span><?php } else { ?><a href="/user/<?= $Profile["displayname"] ?>/videos">Videos</a><?php } ?> | 
                    <?php if(isset($_GET["sort"]) && strtolower($_GET["sort"]) == "v") { ?><span>Most Viewed</span><?php } else { ?><a href="/user/<?= $Profile["displayname"] ?>/videos?sort=v">Most Viewed</a><?php } ?> | 
                    <?php if(isset($_GET["sort"]) && strtolower($_GET["sort"]) == "d") { ?><span>Most Discussed</span><?php } else { ?><a href="/user/<?= $Profile["displayname"] ?>/videos?sort=d">Most Discussed</a><?php } ?>
                </div>
                <div style="margin: 5px 0 5px 5px; float: right;">
                    <form action="/user/<?= $Profile["displayname"] ?>/videos" method="POST" style="position:relative;bottom:1px" id="searcher">
                        <input type="text" name="search" maxlength="64"<? if (isset($_POST["search"])) : ?> value="<?= $_POST["search"] ?>" <? endif ?> id="search_input" style="width:200px;border-radius:0;">
                        <input type="submit" value="Search" name="search_input" class="search_button" style="border-radius:0;">
                    </form>
                </div>
            </div>
            <?php } ?>
            <? $Count = 0 ?>
            <? $Amount = count($Videos) ?>
            <div class="vi_box nm_big">
                <? foreach ($Videos as $Video) : ?>
                <? $Count++ ?>
                <? if ($Count === 6) : ?></div><div class="vi_box nm_big"> <? endif ?>
                <div>
                    <div class="th">
                        <div class="th_t"><?= $Video["length"] ?></div>
                        <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="160" height="110"></a>
                    </div>
                    <div style="text-align: left">
                        <a href="/watch?v=<?= $Video["url"] ?>" class="ln2"><?= htmlspecialchars($Video["title"]) ?></a>
                        <span><?= get_time_ago($Video["uploaded_on"]) ?></span><br>
                        <?= number_format($Video["views"]) ?> views<br>
                        <div class="st"><? show_ratings($Video,13,13) ?></div>
                    </div>
                </div>
                <? if ($Count === 6) { $Count = 1; } ?>
                <? endforeach ?>
        </div>
        <? if (!isset($_POST["search_input"])) : ?>
        <div style="padding:5px;font-weight:bold;word-spacing:5px;text-align:right;font-size:15px;padding-bottom:0"><?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/videos",true) ?></div>
        <? endif ?>
    </div>
</section>