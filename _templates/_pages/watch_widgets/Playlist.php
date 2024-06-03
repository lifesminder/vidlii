<? if ($Has_Playlist) : ?>
<div class="playlist_box" oncontextmenu="event.preventDefault()">
	<div style=";margin-bottom:8px;position:relative">
		<div style="font-weight:bold;font-size:15px;"><?= $Playlist_Videos[0]["ptitle"] ?></div>
		<div style="margin-top: 3px;font-size: 13px">by. <a href="/user/<?= $Playlist_Videos[0]["created_by"] ?>"><?= $Playlist_Videos[0]["created_by"] ?></a></div>
		<? if (isset($Playlist_Videos[$NextVideo])) : ?>
		<a href="/watch?v=<?= $Playlist_Videos[$NextVideo]["url"] ?>&pl=<?= $_GET["pl"] ?>" style="font-weight:bold;color:#222222;text-decoration:none;position:absolute;right:2px;top:7px;font-size:18px">NEXT</a>
		<? endif ?>
	</div>
	<div id="plcontainer">
		<? foreach($Playlist_Videos as $PVideo) : ?>
		<a href="/watch?v=<?= $PVideo["url"] ?>&pl=<?= $_GET["pl"] ?>"<? if ($PVideo["url"] == $URL) : ?> style="background:#dddddd" id="plwatching"<? endif ?>>
			<img src="<? if (file_exists("usfi/thmp/".$PVideo["url"].".jpg")) : ?>/usfi/thmp/<?= $PVideo["url"] ?><? else : ?>/img/no_th<? endif ?>.jpg">
			<div>
				<div style="color: #000000;font-weight:bold;width:194px;margin-left: 10px;height:35px;overflow:hidden"><? if (!empty($PVideo["title"])) : ?><?= $PVideo["title"] ?><? else : ?>Deleted Video<? endif ?></div>
				<div style="margin-top:3px;color:#111111;margin-left: 10px;width:200px;font-size:12px"><? if (!empty($PVideo["uploaded_by"])) : ?>By <?= $PVideo["uploaded_by"] ?> | <?= get_time_ago($PVideo["uploaded_on"]) ?><? endif ?></div>
			</div>
		</a>
		<? endforeach ?>
	</div>
</div>

<script>
	<? if ($NextVideo !== 1) : ?>
	$("#plcontainer").animate({ scrollTop: $('#plwatching').offset().top - $('#plcontainer').offset().top }, 500);
	<? endif ?>
</script>
<? endif ?>