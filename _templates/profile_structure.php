<!doctype html>
<html lang="en">
    <head>
<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_head/profile_head.php" ?>
        <style>
            @keyframes blinker {
                50% {
                    opacity: 0;
                }
            }
            body { background: <? if ($Has_Background) : ?> url("<?= $Background ?>")<? endif ?> #<?= $Profile["bg"] ?> <? if ($Profile["bg_fixed"] == 1) : ?>fixed<? endif ?>; background-position: <? if ($Profile["bg_position"] == 1) : ?>top<? elseif ($Profile["bg_position"] == 2) : ?>center<? elseif ($Profile["bg_position"] == 3) : ?>bottom<? endif ?>; background-repeat: <? if ($Profile["bg_repeat"] == 1) : ?>no-repeat<? elseif ($Profile["bg_repeat"] == 2) : ?>repeat<? elseif ($Profile["bg_repeat"] == 3) : ?>repeat-x<? elseif ($Profile["bg_repeat"] == 4) : ?>repeat-y<? endif ?>; background-size: <? if ($Profile["bg_stretch"] == 0) : ?>auto<? else : ?>100% auto<? endif ?> }
            .pr_lks a { color: #<?= $Profile["nav"] ?> !important; }
            .bottom_wrapper a { color: #<?= $Profile["links"] ?> }
            .vid_th { border: 2px solid #<?= $Profile["links"] ?>;border-radius:0!important; }
            .vid_th:hover { border: 2px solid <?= colourBrightness("#".$Profile["links"],0.5) ?> }
            .pr_avt {  border: 2px solid #<?= $Profile["links"] ?>;border-radius:0!important;  }
            .pr_avt:hover {  border: 2px solid <?= colourBrightness("#".$Profile["links"],0.5) ?>  }
            .pr_lks > a { border-right-color: #<?= $Profile["h_in_fnt"] ?>; }
            .avt2 { border-radius:<?= $Profile["avt_radius"] ?>px !important }
            .hl_in_top > div:first-of-type > div:first-of-type { border-radius: <?= $Profile["avt_radius"] ?>px; }
            .hl_hd { border-top-left-radius: <?= $Profile["chn_radius"] ?>px; border-top-right-radius: <?= $Profile["chn_radius"] ?>px;background: <?= hexToRgb($Profile["h_head"],$HightLight_Trans) ?>; color: #<?= $Profile["h_head_fnt"] ?>; }
            .nm_hd { border-top-left-radius: <?= $Profile["chn_radius"] ?>px; border-top-right-radius: <?= $Profile["chn_radius"] ?>px;background: <?= hexToRgb($Profile["n_head"],$Normal_Trans) ?>; color: #<?= $Profile["n_head_fnt"] ?>; }
            .hl_in { border-bottom-left-radius: <?= $Profile["chn_radius"] ?>px; border-bottom-right-radius: <?= $Profile["chn_radius"] ?>px;background: <?= hexToRgb($Profile["h_in"],$HightLight_Trans) ?>; border: 1px solid <?= hexToRgb($Profile["h_head"],$HightLight_Trans) ?>; border-top: 0; color: #<?= $Profile["h_in_fnt"] ?>; }
            .nm_in { border-bottom-left-radius: <?= $Profile["chn_radius"] ?>px; border-bottom-right-radius: <?= $Profile["chn_radius"] ?>px;background: <?= hexToRgb($Profile["n_in"],$Normal_Trans) ?>; border: 1px solid <?= hexToRgb($Profile["n_head"],$Normal_Trans) ?>; border-top: 0; color: #<?= $Profile["n_in_fnt"] ?>; }
            .ft_video_info { border-radius: <?= $Profile["chn_radius"] ?>px;color: #<?= $Profile["n_in_fnt"] ?>; border: 1px solid <?= hexToRgb($Profile["n_head"],$Normal_Trans) ?>; background: <?= hexToRgb($Profile["n_in"],$Normal_Trans) ?> }
            .ch_cmt { border-bottom: 1px solid <?= hexToRgb($Profile["n_head"],$Normal_Trans) ?> }
            .avt { border: 3px solid #<?= $Profile["b_avatar"] ?> }
            .nm_hd a { color: #<?= $Profile["n_head_fnt"] ?> !important; text-decoration: none }
            .ra tr td { border-bottom: 1px dotted <?= hexToRgb($Profile["n_head"],$Normal_Trans) ?> }
            <? if ($Profile["font"] != 0) : ?>
            .bottom_wrapper, .pr_lks {  font-family: <? if ($Profile["font"] == 1) : ?>Georgia, Arial<? elseif ($Profile["font"] == 2) : ?>"Times New Roman", Arial<? elseif ($Profile["font"] == 3) : ?>"Comic Sans MS", Arial<? elseif ($Profile["font"] == 4) : ?>Impact, Arial<? elseif ($Profile["font"] == 5) : ?>Tahoma, Arial<? elseif ($Profile["font"] == 6) : ?>"Courier New", Arial<? endif ?>  }
            <? endif ?>
        </style>
        <? if ($Profile["snow"]) : ?><script src="https://cdnjs.cloudflare.com/ajax/libs/Snowstorm/20131208/snowstorm.js"></script><? endif ?>
    </head>
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
    <div class="wrapper">
<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_profile/_widgets/header.php" ?>
    <main class="bottom_wrapper" id="prfle">
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_profile/$Page_File.php" ?>
    </main>
    </div>
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
	<script src="<?= PROFILE_JS_FILE ?>"></script>
    <? if (isset($_GET["page"]) && $_GET["page"] !== "0" && !empty($_GET["page"]) && is_numeric($_GET["page"])) : ?>
        <script>
            $('html, body').animate({
                scrollTop: $("#cc_count").offset().top
            }, 200);
        </script>
    <? endif ?>
	<? if ($_USER->logged_in && $Profile["username"] == $_USER->username) : ?>
    <script>
        vertical_r = '<?= $Profile["modules_vertical_r"] ?>';
        vertical_l = '<?= $Profile["modules_vertical_l"] ?>';
    </script>
    <? endif ?>
    </body>
</html>