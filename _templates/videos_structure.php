<!doctype html>
<html lang="en">
    <head>
<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_head/main_head.php" ?>
    </head>
    <body>
    <?php
    $Top_text = $DB->execute("SELECT value FROM settings WHERE name = 'top_text'", true)["value"];
    ?>
    <? if (!empty($Top_text)) : ?><div style="background:#f0f0f0;border-bottom:1px solid #989898;text-align:center;padding:4px 0;margin-bottom:3px"><?= $Top_text ?></div><? endif ?>

    <div class="wrapper">
<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php" ?>
    <main class="bottom_wrapper" id="st">
        <div style="font-size:20px;padding-bottom:9px">
            <a href="/my_account">My Account</a><span style="word-spacing:10px"> / </span><?= $Header ?>
        </div>
        <? if (isset($_SESSION["notification"])) : ?>
            <div style="border: 1.5px solid <?= $_SESSION["n_color"] ?>;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold"><?= $_SESSION["notification"] ?></div>
            <? unset($_SESSION["notification"]); unset($_SESSION["n_color"]); ?>
        <? endif ?>
        <div style="border-bottom:1px solid #ccc"></div>
        <div class="settings_menu">
            <a href="/my_videos" <? if ($_PAGE->Page == "Main" || $_PAGE->Page == "Edit") : ?>id="nav_sel"<? endif ?>>My Videos</a>
            <a href="/my_favorites" <? if ($_PAGE->Page == "Favorites") : ?>id="nav_sel"<? endif ?>>My Favorites</a>
            <a href="/my_playlists" <? if ($_PAGE->Page == "Playlists" || $_PAGE->Page == "Playlist") : ?>id="nav_sel"<? endif ?>>My Playlists</a>
            <a href="/my_subscriptions" <? if ($_PAGE->Page == "Subscriptions") : ?>id="nav_sel"<? endif ?>>My Subscriptions</a>
            <? if ($_PAGE->Page == "Subscriptions") : ?>
                <?
                    if (!$Videos || count($Videos) <= 4)        { $Height = 180; }
                    elseif (count($Videos) <= 8)                { $Height = 400; }
                    elseif (count($Videos) <= 12)               { $Height = 622; }
                    elseif (count($Videos) <= 16)               { $Height = 841; }
                ?>
            <div style="padding-left:10px; max-height: <?= $Height ?>px; overflow-y: auto; overflow-x: hidden" id="sub_users">
                <? foreach ($Subscriptions as $subscription) : ?>
                    <a href="/my_subscriptions?c=<?= $subscription["displayname"] ?>"<? if (isset($_GET["c"]) && $_GET["c"] == $subscription["displayname"]) : ?> class="sub_sel"<? endif ?>><?= $subscription["displayname"] ?></a>
                <? endforeach ?>
            </div>
            <? endif ?>
        </div>
        <div style="float:left;width:800px;-moz-box-sizing: border-box;-ms-box-sizing: border-box;-webkit-box-sizing: border-box;padding-right: 9px;padding-left: 11px;border-left: 1px solid #ccc;border: 1px solid #ccc;border-right:1px solid #ccc;border-top: 0;background: #e2e2e2;">
            <div style="background:white;border-left:1px solid #e2e2e2;">
                <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_videos/$_PAGE->Page.php" ?>
            </div>
        </div>
    </main>
<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/footer.php" ?>
    </div>
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
    </body>
</html>