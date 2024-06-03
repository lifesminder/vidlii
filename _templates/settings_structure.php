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
        <? if (isset($_SESSION["notification"])) : ?>
            <div style="border: 1.5px solid <?= $_SESSION["n_color"] ?>;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold"><?= $_SESSION["notification"] ?></div>
            <? unset($_SESSION["notification"]); unset($_SESSION["n_color"]); ?>
        <? endif ?>
    <main class="bottom_wrapper" id="st">
    <div style="font-size:20px;padding-bottom:9px;border-bottom:1px solid #ccc">
        <a href="/my_account">My Account</a><span style="word-spacing:10px"> / </span><?= $Account_Title ?>
    </div>
        <div class="settings_menu">
            <a href="/my_account" <? if ($_PAGE->Page == "my_account") : ?>id="nav_sel"<? endif ?>>Overview</a>
            <a href="/channel_setup" <? if ($_PAGE->Page == "Main") : ?>id="nav_sel"<? endif ?>>Channel Setup</a>
            <? if ($Channel_Version < 2) : ?><a href="/channel_theme" <? if ($_PAGE->Page == "Customize") : ?>id="nav_sel"<? endif ?>>Channel Theme</a><? endif ?>
            <a href="/channel_version" <? if ($_PAGE->Page == "Layout") : ?>id="nav_sel"<? endif ?>>Channel Version</a>
            <? if ($Channel_Version < 2) : ?><a href="/my_profile"<? if ($_PAGE->Page == "Profile") : ?>id="nav_sel"<? endif ?>>Profile Setup</a><? endif ?>
            <a href="/analytics" <? if ($_PAGE->Page == "Analytics") : ?>id="nav_sel"<? endif ?>>Channel Analytics</a>
            <a href="/my_playback" <? if ($_PAGE->Page == "Playback") : ?>id="nav_sel"<? endif ?>>Playback Setup</a>
            <? if ($_USER->Is_Partner) : ?><a href="/partner_settings" <? if ($_PAGE->Page == "Partner") : ?>id="nav_sel"<? endif ?>>Partner Settings</a><? endif ?>
            <a href="/manage_account" <? if ($_PAGE->Page == "Manage" || $_PAGE->Page == "Delete") : ?>id="nav_sel"<? endif ?>>Manage Account</a>
        </div>
        <div style="float:left;width:800px;-moz-box-sizing: border-box;-ms-box-sizing: border-box;-webkit-box-sizing: border-box;padding-left: 11px;border-left: 1px solid #ccc;border: 1px solid #ccc;border-top: 0;background: #e2e2e2;">
            <div style="background:white;padding:1px;border-left:1px solid #ccc;padding: 12px 14px <? if ($_PAGE->Page !== "Manage") : ?>11px<? else : ?>8px<? endif ?> 14px">
                <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_settings/$_PAGE->Page.php" ?>
            </div>
        </div>
    </main>
<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/footer.php" ?>
    </div>
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
    </body>
</html>
