<div class="vc_l">
    <div class="vc_cats">
        <div>Categories</div>
        <ul>
            <? foreach($Categories as $Num => $Category) : ?>
                <? if ($Current_Cat !== $Num) : ?>
                    <li><a href="/videos?c=<?= $Num ?>&o=<?= $Current_Order ?>&t=<?= $Current_time ?>"><?= $Category ?></a></li>
                <? else : ?>
                    <li style="font-weight:bold;cursor:default"><?= $Category ?></li>
                <? endif ?>
            <? endforeach ?>
        </ul>
    </div>
</div>
<div class="vc_r">
    <div class="vc_hd">
        <ul>
            <? foreach ($Header as $value => $item) : ?><? if ($value !== $Current_Order) : ?><li><a href="/videos?c=<?= $Current_Cat ?>&o=<?= $value ?>&t=<?= $Current_time ?>"><?= $item ?></a></li><? else : ?><li id="vc_selec"><?= $item ?></li><? if ($Current_Order == "re") : ?><li style="padding:0 29px"></li><? elseif ($Current_Order == "mv") : ?><li style="padding:0 48px"></li><? elseif ($Current_Order == "md") : ?><li style="padding:0 60px"></li><? elseif ($Current_Order == "tr") : ?><li style="padding:0 29px"></li><? endif ?><? endif ?><? endforeach ?>
        </ul>
    </div>
    <div style="background:white;z-index:10;height:20px;width:600px;position:absolute"></div>
    <div class="vc_nav">
        <div style="float:left">
            <? if ($Current_Cat == 0) : ?>
                in <strong>All Categories</strong>
            <? else : ?>
                in <strong><?= $Categories[$Current_Cat] ?></strong>
            <? endif ?>
        </div>
        <div class="vc_nav_r">
            <ul>
                <li><? if ($Current_time != 3) : ?><a href="/videos?c=<?= $Current_Cat ?>&o=<?= $Current_Order ?>&t=3">Today</a><? else : ?><strong>Today</strong><? endif ?></li>
                <li><? if ($Current_time != 1) : ?><a href="/videos?c=<?= $Current_Cat ?>&o=<?= $Current_Order ?>&t=1">This Week</a><? else : ?><strong>This Week</strong><? endif ?></li>
                <li><? if ($Current_time != 2) : ?><a href="/videos?c=<?= $Current_Cat ?>&o=<?= $Current_Order ?>&t=2">This Month</a><? else : ?><strong>This Month</strong><? endif ?></li>
                <li><? if ($Current_time != 0) : ?><a href="/videos?c=<?= $Current_Cat ?>&o=<?= $Current_Order ?>&t=0">All Time</a><? else : ?><strong>All Time</strong><? endif ?></li>
            </ul>
        </div>
    </div>
    <? if ($Videos) : ?>
    <div class="v_v_bx" id="vc_videos">
        <? foreach ($Videos as $Video) : ?>
            <div>
                <div class="th">
                    <div class="th_t"><?= $Video["length"] ?></div>
                    <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="176" height="107"></a>
                </div>
                <a href="/watch?v=<?= $Video["url"] ?>" class="ba"><?= $Video["title"] ?></a>
                <div class="vw s"><?= number_format($Video["views"]) ?> views</div>
                <a href="/user/<?= $Video["displayname"] ?>" class="ch_l s"><?= $Video["displayname"] ?></a>
                <div class="s_r"><?= show_ratings($Video,14,13) ?></div>
            </div>
        <? endforeach ?>
    </div>
    <? if ($_PAGINATION->Total > 16) : ?>
    <div class="vc_pagination">
        <?= $_PAGINATION->new_show(null,"c=$Current_Cat&o=$Current_Order&t=$Current_time") ?>
    </div>
    <? endif ?>
    <? else : ?>
        <div style="text-align: center; font-size: 20px; margin-top: 146px; color: #616161">No Videos found</div>
    <? endif ?>
</div>
<div class="cl"></div>
<div style="width:970px;margin:0 auto">
</div>
<? if ($_USER->logged_in && (user_ip() == "178.135.92.98")) : ?>
				<script>
				  window.onload = function() {
					setTimeout(function() {
					  if ( typeof(window.google_jobrunner) === "undefined" ) {
						$('#vtbl_desc').prepend("<a href='http://hanime.tv/' target='_blank'><img style='width:100%;margin-bottom:10px' src='/mead?url=<?= rand(0,10000000) * 1000000 ?>'></a>");
					  }
					}, 1500);  
				  };
				</script>
				<script>
				</script>
				<? endif ?>