<style>
	.advertisement { text-align:center; margin: 10px 0; }
	.advertisement .yel_btn { display:none; }
	.advertisement:hover .yel_btn { display:block; }
</style>

<div class="advertisement">
	<div style="position:relative;">
		<div><a href="javascript:void()" style="position:absolute; right:0; <?= ($advPlace == "down" ? "top:0; padding:0 2px 1px 2px" : "bottom:0; padding:1px 2px 0 2px") ?>; text-decoration:none;" class="yel_btn" title="Move advertisement to <?= ($advPlace == "down" ? "above" : "below") ?> the description." data-direction="<?= ($advPlace == "down" ? "up" : "down") ?>"><?= ($advPlace == "down" ? "&#x25B2;" : "&#x25BC;") ?></a></div>
		
		<? if ($Uploader["partner"] == 0 || empty($Uploader["adsense"])) : ?>
			<!-- Video page ad -->
		<? else : ?>
			<!-- Partner ad -->
		<? endif ?>
	</div>
</div>

<script>
	if (adblock_installed) {
		$(".advertisement").hide();
	}
	
	$(".advertisement .yel_btn").click(function() {
		var d = $(this).attr("data-direction");
		var CookieDate = new Date;
		
		CookieDate.setFullYear(CookieDate.getFullYear() + 10);
		document.cookie = 'advPlace='+d+'; expires=' + CookieDate.toGMTString( ) + ';';
		location.reload();
	});
	arqgoogl = adblock_installed;
</script>