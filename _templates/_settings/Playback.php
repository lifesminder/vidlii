<?php $HDPlayback = (isset($_COOKIE["vlphd"]) && $_COOKIE["vlphd"] == "1"); ?>
<style>
    .bwfilter {
        -webkit-filter: grayscale(77%);
        filter: grayscale(77%);
        cursor: pointer;
    }

    .bwfilter:hover {
        -webkit-filter: grayscale(37%);
        filter: grayscale(37%);
    }
	
	.pcvlTable {
		display: table;
	}
	
	.pcvlTableRow {
		display: table-row;
	}
	
	.pcvlTableCell {
		display: table-cell;
		padding: 10px 20px 10px 0;
		vertical-align: bottom;
	}
	
	.vlColorPick {
		display: inline-block;
		vertical-align: bottom;
		position: relative;
	}
	
	.vlColorPick > b {
		display: none;
		left: 100%;
		top: 0;
		position: absolute;
		width: 75px;
		padding: 5px;
		background: #fff;
		border: 1px solid #666;
		border-radius: 2px;
		z-index: 1;
	}
	
	.vlColorPick.open > b {
		display: block;
	}
	
	.vlColorPick button {
		display:inline-block;
		vertical-align:top;
		width:25px;
		height:25px;
		padding: 0;
	}
	
	.vlColorPick button.red { background: #f66; }
	.vlColorPick button.orange { background: #fb6; }
	.vlColorPick button.gold { background: #ff6; }
	.vlColorPick button.olive { background: #bf6; }
	.vlColorPick button.green { background: #6f6; }
	.vlColorPick button.teal { background: #6cf; }
	.vlColorPick button.blue { background: #36f; }
	.vlColorPick button.violet { background: #86f; }
	.vlColorPick button.pink { background: #f6f; }
	.vlColorPick button.magenta { background: #f6a; }
	.vlColorPick button.white { background: #fff; }
	.vlColorPick button.black { background: #666; }
	.vlColorPick#vlBtColor button.black { display:none; }
	
	#playerSelection {
		display:table;
		overflow:hidden;
		width:100%;
		margin:0 auto;
		margin-top:12px;
	}
	
	#playerSelection > div {
		display:table-cell;
		text-align:center;
	}
</style>

<? if ($Player !== 2) : ?>
<script src="/vlPlayer/main15.js?<?=$VLPVERSION?>"></script>
<script>swfobject.registerObject("flPlayer", "9.0.0");</script>
<script>window.vlpv = <?=$VLPVERSION?>;</script>
<? endif ?>

<h4>Video Player</h4>
<form action="/my_playback" method="POST">
    <div id="playerSelection">
        <div>
            <label>
                <div style="font-size:12.5px">Classic Player</div>
                <img src="/img/cplayer2.png" id="cpp" style="height:96px;-webkit-user-drag: none;-moz-user-select: none;-webkit-user-select: none" <? if ($Player != 2) : ?> class="bwfilter"<? endif ?>><br>
				<input type="radio" id="cppb" size="30" value="classic" name="player"<? if ($Player == 2) : ?> checked <? endif ?>>
            </label>
        </div>
        <div>
            <label>
                <div style="font-size:12.5px">Modern Player</div>
                <img src="/img/hp.png" id="hpp" style="height:96px;-webkit-user-drag: none;-moz-user-select: none;-webkit-user-select: none" <? if ($Player != 1) : ?> class="bwfilter"<? endif ?>><br>
				<input type="radio" id="hppb" size="30" value="html5" name="player"<? if ($Player == 1) : ?> checked <? endif ?>>
            </label>
        </div>
        <div>
            <label>
                <div style="font-size:12.5px">Renaissance Player</div>
                <img src="/img/fp.png" id="fpp" style="height:96px;-webkit-user-drag: none;-moz-user-select: none;-webkit-user-select: none" <? if ($Player != 0) : ?> class="bwfilter"<? endif ?>><br>
				<input type="radio" size="30" id="fppb" value="flash" name="player"<? if ($Player == 0) : ?> checked<? endif ?>>
            </label>
        </div>
        <div>
            <label>
                <div style="font-size:12.5px">Penguin Player</div>
                <img src="/img/fp.png" id="ppp" style="height:96px;-webkit-user-drag: none;-moz-user-select: none;-webkit-user-select: none" <? if ($Player != 3) : ?> class="bwfilter"<? endif ?>><br>
				<input type="radio" size="30" id="pppb" value="penguin" name="player"<? if ($Player == 3) : ?> checked<? endif ?>>
            </label>
        </div>
    </div>
    <div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
        <img src="/img/clp00.png">
        <span class="u_sct_hd">Video Playback</span>
    </div>
    <div style="display:none">
        <div style="margin-bottom:3px">
            <label><input type="radio" name="autoplay" <? if ($Autoplay == 1) : ?>checked<? endif ?> value="1" style="position:relative;top:1px"> Start Video Automatically</label>
        </div>
        <div style="margin-bottom:8px">
            <label><input type="radio" name="autoplay" <? if ($Autoplay == 0) : ?>checked<? endif ?> value="0"  style="position:relative;top:1px"> Start Video after clicking on it</label>
        </div>
        <div style="margin-bottom:3px">
            <label><input type="radio" name="hdplayback" <?= $HDPlayback ? "checked" : "" ?> value="1" style="position:relative;top:1px"> Always play videos in HD quality</label>
        </div>
        <div style="margin-bottom:8px">
            <label><input type="radio" name="hdplayback" <?= !$HDPlayback ? "checked" : "" ?> value="0"  style="position:relative;top:1px"> Never play any videos in HD quality</label>
        </div>
    </div>
    <div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
        <img src="/img/clp00.png">
        <span class="u_sct_hd">Video Size</span>
    </div>
    <div style="display:none">
        <div style="margin-bottom:8px">
            <label><input type="radio" name="size" <? if ($Size == 0) : ?>checked<? endif ?> value="0" style="position:relative;top:1px"> Regular Player Size</label>
        </div>
        <div style="margin-bottom:3px">
            <label><input type="radio" name="size" <? if ($Size == 1) : ?>checked<? endif ?> value="1" style="position:relative;top:1px"> Theater Player Size</label>
        </div>
    </div>

    <div id="player_custom">
		<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
			<img src="/img/clp00.png">
			<span class="u_sct_hd">Player Customization</span>
		</div>
		
		<div style="display:none">
			<div id="player_custom_JW" style="display:none">
				<div style="margin-bottom:8px">
					<div style="margin-bottom: 12px">
						<div style="float:left;width:110px;position:relative;top:3px">Button Color:</div> <input type="text" id="avcolor" name="bcolor" maxlength="7" size="6" value="<?= $Bcolor ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true}">
					</div>
					<div>
						<div style="float:left;width:110px;position:relative;top:3px">Progress Color:</div> <input type="text" id="avcolor" name="pcolor" maxlength="7" size="6" value="<?= $Pcolor ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true}">
					</div>
				</div>
			</div>
			
			<div id="player_custom_VL" style="display:none">
				<div style="float:right; width:320px; height:240px;">
					<div class="vlPlayer" id="vlPlayerTheme2007" style="display:none"></div>
					<div class="vlPlayer" id="vlPlayerTheme2009" style="display:none"></div>
				</div>
				
				<div class="pcvlTable">
					<div class="pcvlTableRow">
						<div class="pcvlTableCell">Buttons Color:</div>
						<div class="pcvlTableCell"><span id="vlBtColor" class="vlColorPick"></span></div>
					</div>
					
					<div class="pcvlTableRow">
						<div class="pcvlTableCell">Background Color:</div>
						<div class="pcvlTableCell"><span id="vlBgColor" class="vlColorPick"></span></div>
					</div>
				</div>
				
				<input type="hidden" name="vlpButton" />
				<input type="hidden" name="vlpBackground" />
			</div>
		</div>
    </div>
    <div style="margin-top:25px">
    <input type="submit" value="Save Changes" name="update_player" class="search_button">
    </div>
	<div style="clear:both"></div>
</form>

<script>
	var vlp1, vlp2;
	var currentPlayer = <?=(int)$Player?>;
	var currentColors = "<?= isset($_COOKIE["vlpColors"]) ? $_COOKIE["vlpColors"] : "teal,white" ?>";
	var playerColors = ["red","orange","gold","olive","green","teal","blue","violet","pink","magenta","white","black"];
	currentColors = currentColors.split(",");

	if (playerColors.indexOf(currentColors[0]) == -1) {
		currentColors[0] = "teal";
	}
	
	if (playerColors.indexOf(currentColors[1]) == -1) {
		currentColors[1] = "white";
	}
	
	if (currentPlayer == 1) {
		$("#player_custom_JW").show();
	} else if (currentPlayer == 2) {
		$("#player_custom_VL").show();
	} else if (currentPlayer == 3) {
		$("#player_custom").hide();
	}
	
	$("input[name='vlpButton']").val(currentColors[0]);
	$("input[name='vlpBackground']").val(currentColors[1]);
	
	$("input[name='player']").click(function() {
		$("input[name='player']").siblings("img").addClass("bwfilter");
		$(this).siblings("img").removeClass("bwfilter");
		$('#player_custom_JW').hide();
		$('#player_custom_VL').hide();
		$('#vlPlayerTheme2007').hide();
		$('#vlPlayerTheme2009').hide();
		$("#player_custom").hide();
		
		if ($(this).val() != "penguin") {
			$("#player_custom").show();
		}
		
		if ($(this).val() == "flash") {
			$('#player_custom_VL').show();
			$('#vlPlayerTheme2009').show();
		} else {
			if ($(this).val() == "html5") {
				$("#player_custom_JW").show();
			} else {
				$('#player_custom_VL').show();
				$('#vlPlayerTheme2007').show();
			}
		}
	});	
	
	var videoInfo = {
		id: $("#vlPlayerTheme2007"),
		skin: "/vlPlayer/skins/2007HD",
		btcolor: currentColors[0],
		bgcolor: currentColors[1],
		duration: 60,
		start: 40,
		complete: function() {
			$("#vlBtColor").html('<button class="'+currentColors[0]+'"></button>');
			$("#vlBgColor").html('<button class="'+currentColors[1]+'"></button>');
			
			$(".vlColorPick").mousedown(function(e) {
				e.stopPropagation();
			});
			
			$(".vlColorPick > button").click(function() {
				var thisBt = $(this);
				var thisCt = $(this).parent();
				
				if ($(this).next("b").length == 0) {
					var buttons = $("<b></b>");
					for (var i=0; i < 12 ;i++) {
						buttons.append('<button class="'+playerColors[i]+'" data-id="'+playerColors[i]+'"></button>');
					}
					
					$(this).after(buttons);
					buttons.on("click", "> button", function() {
						var color = $(this).attr("data-id");
						thisBt.removeClass();
						thisBt.addClass(color);
						thisBt.val(color);
						thisCt.trigger("colorchange");
						thisBt.click();
						thisBt.focus();
						
						return false;
					});
				}
				
				if (thisCt.hasClass("open")) {
					$(".vlColorPick").removeClass("open");
				} else {
					$(".vlColorPick").removeClass("open");
					thisCt.addClass("open");
				}
				
				return false;
			});
			
			$(document).mousedown(function() {
				$(".vlColorPick").removeClass("open");
			});
			
			$(document).keydown(function(e) {
				if (e.keyCode == 27) {
					$(".vlColorPick").removeClass("open");
				}
			});
			
			$("#vlBtColor").on("colorchange", function() {
				var c = $(this).find("> button").val();
				vlp1.changeButtonColor(c);
				vlp2.changeButtonColor(c);
				$("input[name='vlpButton']").val(c);
			});
			
			$("#vlBgColor").on("colorchange", function() {
				var c = $(this).find("> button").val();
				vlp1.changeBackground(c);
				vlp2.changeBackground(c);
				$("input[name='vlpBackground']").val(c);
			});
			
			$(".vlPlayer .vlPosition").css("width", "25%"); 
			$(".vlPlayer .vlBuffer").css("width", "50%"); 
			$(".vlPlayer2007 .vlSeeker").css("margin-left", "25%"); 
			$(".vlPlayer2009 .vlSeeker").css("left", "25%"); 
		}
	}
	
	vlp1 = new VLPlayer(videoInfo);
	videoInfo.id = $("#vlPlayerTheme2009");
	videoInfo.skin = "/vlPlayer/skins/2009HD";
	vlp2 = new VLPlayer(videoInfo);
	
	$("input[name='player']:checked").click();
</script>
<script src="/js/jscolor.min.js"></script>