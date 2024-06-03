<?php
	if (isset($_COOKIE["cp"]))
		$custom = explode(",", $_COOKIE["cp"]);

	if (isset($_GET["p"]) && $_GET["p"] > 1) {
	    $Autoplay = 0;
    }
	
	$prev = file_exists($_SERVER['DOCUMENT_ROOT']."/usfi/prvw/$URL.jpg");
?>

<script id="heightAdjust">
	if (!window.videoInfo)
		var videoInfo = {};

	function adjustHeight(n) {
		var height;
		var par = $("#heightAdjust").parent();
		if (par[0].style.height) {
			height = par.height();
			par.height(height+n);
		}
	}
	
	// Easier way of setting cookies
	function setCookie(name, value) {
		var CookieDate = new Date;
		CookieDate.setFullYear(CookieDate.getFullYear() + 10);
		document.cookie = name+'='+value+'; expires=' + CookieDate.toGMTString( ) + '; path=/';
	}

	// Easier way of getting cookies
	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	
	function getTimeHash() {
		var h = 0;
		var st = 0;
		
		if ((h = window.location.href.indexOf("#t=")) >= 0) {
			st = window.location.href.substr(h+3);
			return parseInt(st);
		}
		
		return 0;
	}
	
	var vlpColors = "<?= isset($_COOKIE["vlpColors"]) ? $_COOKIE["vlpColors"] : "teal,white" ?>";
	vlpColors = vlpColors.split(",");
	
	<? switch($Player) {
		case 3:
			$PlayerSkin = "2012";
			break;
		case 2:
			$PlayerSkin = "2007HD";
			break;
		default:
			$PlayerSkin = "2009HD";
	} ?>
	<? if ($Status == 2) : ?>
	var viValues = {
		variable: "vlp",
		src: "/usfi/v/<?= $URL ?>.<?= $FILENAME ?>.mp4",
		hdsrc: "/usfi/v/<?= $URL ?>.<?= $FILENAME ?>.720.mp4",
		img: "/usfi/<?= $prev ? "prvw" : "thmp" ?>/<?= $URL ?>.jpg",
		url: "<?= $URL ?>",
		duration: <?= $Length ?>,
		autoplay: <?= $Autoplay == 1 ? "true" : "false" ?>,
		skin: "<?= $PlayerSkin ?>",
		btcolor: vlpColors[0],
		bgcolor: vlpColors[1],
		adjust: true,
		start: getTimeHash()
	};
	
	for (var i in viValues) {
		if (videoInfo[i] === void(0)) {
			videoInfo[i] = viValues[i];
		}
	}
	<? endif ?>
</script>
<? if ($Status == 2) : ?>
	<? if ($Player != 1) { ?>
		<!-- VidLii HTML5/Flash Player -->
		<div class="vlPlayer">
			<script>
				window[videoInfo.variable] = new VLPlayer({
					id: videoInfo.id,
					src: videoInfo.src,
					hdsrc: <?= $ISHD ? "videoInfo.hdsrc" : "null" ?>,
					preview: videoInfo.img,
					videoUrl: "/watch?v="+videoInfo.url,
					duration: videoInfo.duration,
					autoplay: videoInfo.autoplay,
					skin: "/vlPlayer/skins/"+videoInfo.skin,
					adjust: videoInfo.adjust,
					btcolor: videoInfo.btcolor,
					bgcolor: videoInfo.bgcolor,
					start: videoInfo.start,
					expand: videoInfo.expand,
					complete: videoInfo.complete,
					ended: videoInfo.ended
				});
				
				$(window).on('hashchange', function() {
					var t = getTimeHash();
					vlp.play();
					vlp.seek(t);
					$(window).scrollTop(0);
				});
			</script>
		</div>
	<? } else if ($Player == 1) { ?>
		<!-- JWPlayer HTML5 Player -->
		<div id="vlplayer"></div>
		<script type="text/javaScript">
			var modernPlayer = new ModernPlayer({
				instance: jwplayer("vlplayer"),
				duration: videoInfo.duration,
				videoUrl: "/watch?v="+videoInfo.url,
				src: videoInfo.src,
				hdsrc: <?= $ISHD ? "videoInfo.hdsrc" : "null" ?>,
				startinhd: <?= isset($_COOKIE["vlphd"]) && $_COOKIE["vlphd"] == "1" ? "true" : "false" ?>,
				preview: videoInfo.img,
				autoplay: videoInfo.autoplay,
				start: videoInfo.start,
				ended: videoInfo.ended
			});
			
			$(window).on('hashchange', function() {
				modernPlayer.seek(getTimeHash());
				$(window).scrollTop(0);
			});
		</script>
		
		<!-- Customization -->
		<? if (isset($custom) && $custom) : ?>
			<style>
				.jw-button-color {
					color: <?= $custom[0] ?> !important;
				}
				.jw-progress {
					background: <?= $custom[1] ?> !important;
				}
			</style>
		<? endif ?>
	<? } else { ?>
		<!-- Flash Player -->
		<div id="vlplayer">
			<a href="https://www.adobe.com/go/getflashplayer"><img src="https://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>
		</div>
		
		<script>
			adjustHeight(25);
			
			var flashvars = {};
			var params = {};
			var attributes = {};
			flashvars.video_id = videoInfo.url;
			flashvars.autoplay = videoInfo.autoplay ? "1" : "0";
			flashvars.thumbnailurl = "www.vidlii.com"+videoInfo.img;
			params.allowfullscreen = "true";
			attributes.id = "vlplayer";
			swfobject.embedSWF("/player/as.swf", "vlplayer", "100%", "100%", "9.0.0", false, flashvars, params, attributes);
		</script>
	<? } ?>
<? else : ?>
<? if ($Status != 1) : ?>
<div style="display:table;background:black;width:100%;height:100%;color:white;font-size:17px;">
	<div style="display:table-cell;text-align:center;vertical-align:middle;">
		<? if ($Status == 1 || $Status == 0) : ?>
		This video is still converting.....
		<? elseif ($Status == -1) : ?>
		This video is still uploading...
		<? elseif ($Status == -2) : ?>
		This video has failed conversion...
		<? elseif ($Status == -3) : ?>
		This video has been deleted for<br>violating our Community Guidelines.
		<? endif ?>
	</div>
</div>
<? else : ?>
<video width="320" height="240" style="width:100%;height:100%" autoplay loop>
  <source src="/img/Untitled.mp4" type="video/mp4">
</video>
<? endif ?>
<? endif ?>