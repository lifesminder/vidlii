<!doctype html>
<html lang="en">
    <head>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_head/profile_head.php" ?>
    </head>
    <style>
        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
        body {
            background-color: #<?= $Profile["bg"] ?>
        }
        #gbg {
            <? if ($Has_Background) : ?>background-image: url("<?= $Background ?>");<? endif ?>background-color: #<?= $Profile["bg"] ?>; <? if ($Profile["bg_fixed"] == 1) : ?>background-attachment: fixed<? endif ?>; background-position: <? if ($Profile["bg_position"] == 1) : ?>top<? elseif ($Profile["bg_position"] == 2) : ?>center<? elseif ($Profile["bg_position"] == 3) : ?>bottom<? endif ?>; background-repeat: <? if ($Profile["bg_repeat"] == 1) : ?>no-repeat<? elseif ($Profile["bg_repeat"] == 2) : ?>repeat<? elseif ($Profile["bg_repeat"] == 3) : ?>repeat-x<? elseif ($Profile["bg_repeat"] == 4) : ?>repeat-y<? endif ?>; background-size: <? if ($Profile["bg_stretch"] == 0) : ?>auto<? else : ?>cover<? endif ?>
        }
        .ob_col {
            background: <?= hexToRgb($Profile["h_head"],$Normal_Trans) ?>;
            color: #<?= $Profile["h_in_fnt"] ?>;
            border-radius: <?= $Profile["chn_radius"] ?>px;
        }
        .pr_tp_pl_nav a {
            color: #<?= $Profile["h_head_fnt"] ?>;
        }
        .box_title {
            color: #<?= $Profile["n_head_fnt"] ?>;
        }
        .mnu_vid, .in_box, .pr_tp_pl_inf, .pr_pl_mnu {
            border-radius: <?= $Profile["chn_radius"] ?>px;
        }
        .pr_avt, .avt2 {
            border-radius: <?= $Profile["avt_radius"] ?>px;
        }
        .pr_pl_title {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        .mnu_sct {
            border-bottom: 1px solid <?= hexToRgb($Profile["h_head"],$Normal_Trans) ?>;
        }
        .pr_pl_title_sty {
            border-left-color: <?= hexToRgb($Profile["h_head"],$Normal_Trans) ?>;
        }
        .ib_col {
            background: <?= hexToRgb($Profile["n_in"],$HightLight_Trans) ?>;
            color: #<?= $Profile["n_in_fnt"] ?>;
        }
        .ib_col a {
            color: #<?= $Profile["links"] ?>;
        }
        .ra {
            border: 1px solid <?= hexToRgb($Profile["h_head"],$Normal_Trans) ?>;
        }
        .ra tr td {
            border-bottom: 1px dotted <?= hexToRgb($Profile["h_head"],$Normal_Trans) ?>;
        }
        #no_comments,#channel_comments {
            border: 1px solid <?= hexToRgb($Profile["h_head"],$Normal_Trans) ?>;
        }
        #nav_ind {
            border-bottom-color: <?= hexToRgb($Profile["n_in"],$HightLight_Trans) ?>
        }
        .ob_img {
            border: 1px solid #<?= $Profile["h_in_fnt"] ?> !important;
        }
        .pr_pl_nav a:hover {
            color: #<?= $Profile["n_in"] ?>;
            background: #<?= $Profile["n_in_fnt"] ?>;
            opacity: 0.6;
        }
        #v_sel {
            background: #<?= $Profile["h_head"] ?>;
        }
        #pl_nav_sel, #pl_toggle_sel {
            color: #<?= $Profile["n_in"] ?>; <? //BACKGROUND COLOR INNER BOX ?>
            background: #<?= $Profile["n_in_fnt"] ?>; <? //FONT COLOR OUTER BOX ?>
            opacity: 1 !important;
        }
        .pl_nav_sel_hd {
            color: #<?= $Profile["n_in"] ?> !important; <? //BACKGROUND COLOR INNER BOX ?>
            background: #<?= $Profile["n_in_fnt"] ?>; <? //FONT COLOR OUTER BOX ?>
            opacity: 1 !important;
        }
        #pl_toggle_sel b, #pl_toggle_sel em {
            background: #<?= $Profile["n_in"] ?>;
        }
        .pr_pl_toggles a > i > b,em {
            background: #<?= $Profile["links"] ?>; <? //LINK COLOR INNER BOX ?>
        }
        .pr_avt {
            border: 1.5px solid #<?= $Profile["links"] ?>;
        }
        .pr_avt:hover {
            border: 1.5px solid <?= colourBrightness("#".$Profile["links"],0.5) ?>
        }
        .pr_inf_sct {
            border-bottom: 1px dotted <?= hexToRgb($Profile["h_head"],$Normal_Trans) ?>;
        }
        .mnu_vid > a:first-of-type {
            border: 1px solid blue; <? //LINK COLOR INNER BOX ?>
            padding: 3px;
            border-radius: 5px;
            background: white;
        }
        .pr_pl_toggles a:hover {
            opacity: 0.5;
            background: #<?= $Profile["n_in_fnt"] ?>; <? //FONT COLOR OUTER BOX ?>
        }
        .pr_pl_toggles a:hover b {
            background: #<?= $Profile["n_in"] ?> !important; <? //BACKGROUND COLOR INNER BOX ?>
        }
        .pr_pl_toggles a:hover em {
            background: #<?= $Profile["n_in"] ?> !important; <? //BACKGROUND COLOR INNER BOX ?>
        }
        <? if ($Profile["font"] != 0) : ?>
        .wrapper {
            font-family: <? if ($Profile["font"] == 1) : ?>Georgia, Arial<? elseif ($Profile["font"] == 2) : ?>"Times New Roman", Arial<? elseif ($Profile["font"] == 3) : ?>"Comic Sans MS", Arial<? elseif ($Profile["font"] == 4) : ?>Impact, Arial<? elseif ($Profile["font"] == 5) : ?>Tahoma, Arial<? elseif ($Profile["font"] == 6) : ?>"Courier New", Arial<? endif ?>
        }
        <? endif ?>
    </style>
    <? if ($Profile["snow"]) : ?><script src="https://cdnjs.cloudflare.com/ajax/libs/Snowstorm/20131208/snowstorm.js"></script><? endif ?>
    <script>
        var trans1 = <?= 100 - $Profile["n_trans"] ?>;
        var trans2 = <?= 100 - $Profile["h_trans"] ?>;
        <? if ($Player == 0) : ?>
        var vplayer = 1;
        <? elseif ($Player == 1) : ?>
        var vplayer = 2;
        <? else : ?>
        var vplayer = 3;
        <? endif ?>
    </script>
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
        
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_profile2/Header.php" ?>
		<div id="gbg">
			<div class="wrapper">
				<? if ($Profile["banned"] == 0 && $Banner_Links !== false)
					require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/banner.php"; ?>
				<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_profile2/$Page_File.php" ?>
			</div>
		</div>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
        <script src="<?= PROFILE_JS_FILE ?>"></script>
        <script>get_video_info($("#pl_url").html());</script>
        <? if (isset($_GET["page"]) && $_GET["page"] !== "0" && !empty($_GET["page"]) && is_numeric($_GET["page"])) : ?>
            <script>
                $('html, body').animate({
                    scrollTop: $("#cc_count").offset().top
                }, 200);
            </script>
        <? endif ?>
		<? if ($_USER->logged_in && $_USER->username == $Profile["username"]) : ?>
        <script>
            vertical_r = '<?= $Profile["modules_vertical_r"] ?>';
            vertical_l = '<?= $Profile["modules_vertical_l"] ?>';
        </script>
        <? endif ?>
    </body>
</html>