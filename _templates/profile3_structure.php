<!doctype html>
<html lang="en">
    <head>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_head/profile_head.php" ?>
    </head>
    <style>
        body {
            background-color: #<?= $Profile["bg"] ?>
        }
        #gbg {
            <? if ($Has_Background) : ?>background-image: url("<?= $Background ?>");<? endif ?>background-color: #<?= $Profile["bg"] ?>; <? if ($Profile["bg_fixed"] == 1) : ?>background-attachment: fixed<? endif ?>; background-position: <? if ($Profile["bg_position"] == 1) : ?>top<? elseif ($Profile["bg_position"] == 2) : ?>center<? elseif ($Profile["bg_position"] == 3) : ?>bottom<? endif ?>; background-repeat: <? if ($Profile["bg_repeat"] == 1) : ?>no-repeat<? elseif ($Profile["bg_repeat"] == 2) : ?>repeat<? elseif ($Profile["bg_repeat"] == 3) : ?>repeat-x<? elseif ($Profile["bg_repeat"] == 4) : ?>repeat-y<? endif ?>; background-size: <? if ($Profile["bg_stretch"] == 0) : ?>auto<? else : ?>cover<? endif ?>
        }
    </style>
    <? if ($Profile["snow"]) : ?><script src="https://cdnjs.cloudflare.com/ajax/libs/Snowstorm/20131208/snowstorm.js"></script><? endif ?>
    <body>
        <? if ($Profile["mondo"]) : ?><canvas class="snow" height="100%" style="width:100%;z-index:1000000;position:fixed;height:100vh !important;pointer-events:none"></canvas>
        <script src="/js/let_it_mondo.js"></script>
        <script>
        $("canvas.snow").let_it_snow({
            speed: 0,
            interaction: true,
            size: 7,
            count: 50,
            opacity: 0,
            color: "#ffffff",
            windPower: 0,
            image: "/img/cursor2.png"
          });
          </script>
        <? endif ?>
    <div id="cm_usn"><?= $Profile["username"] ?></div>
    <input type="hidden" id="cm_dpn" value="<?= $Profile["displayname"] ?>" />
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_profile3/Header.php" ?>
			<div class="wrapper">
				<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_profile3/$Page_File.php" ?>
			</div>
		</div>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
        <script src="<?= COSMIC_JS_FILE ?>"></script>
    </body>
</html>