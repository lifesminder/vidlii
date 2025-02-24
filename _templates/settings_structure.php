<!doctype html>
<html lang="en">
    <head>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_head/main_head.php" ?>
        <script src="/js/main.js"></script>
    </head>
    <body>
    <?php
    $Top_text = $DB->execute("SELECT value FROM settings WHERE name = 'top_text'", true)["value"];
    ?>
    <? if (!empty($Top_text)) : ?>
        <div class="top"><?= $Top_text ?></div>
    <? endif ?>

    <div class="wrapper">
        <?php
            require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php";

            if(isset($_COOKIE["notification"])) {
                $_msg = explode(";", $_COOKIE["notification"]);
                $message = $_msg[0];
                $color = (count($_msg) > 1) ? $_msg[1] : null;
        ?>
            <div class="notification<?php echo ($color != "") ? " $color" : null; ?>">
                <?php echo $message; ?>
            </div>
        <?php
                setcookie('notification', '', -1, '/', $_SERVER['SERVER_NAME']); 
            }
        ?>
        <main class="bottom_wrapper" id="st">
        <div style="font-size:20px;padding-bottom:9px;border-bottom:1px solid #ccc">
            <a href="/my_account">My Account</a><span style="word-spacing:10px"> / </span><?= $Account_Title ?>
        </div>
            <div class="settings_menu">
                <a href="/my_account" <? if ($_PAGE->Page == "my_account") : ?>id="nav_sel"<? endif ?>>Overview</a>
                <a href="/channel_setup" <? if ($_PAGE->Page == "Main") : ?>id="nav_sel"<? endif ?>>Channel Setup</a>
                <? if ($Channel_Version < 2) : ?><a href="/channel_theme" <? if ($_PAGE->Page == "Customize") : ?>id="nav_sel"<? endif ?>>Channel Theme</a><? endif ?>
                <a href="/channel_version" <? if ($_PAGE->Page == "Layout") : ?>id="nav_sel"<? endif ?>>Channel Version</a>
                <? if ($Channel_Version != 2) : ?><a href="/my_profile"<? if ($_PAGE->Page == "Profile") : ?>id="nav_sel"<? endif ?>>Profile Setup</a><? endif ?>
                <a href="/analytics" <? if ($_PAGE->Page == "Analytics") : ?>id="nav_sel"<? endif ?>>Channel Analytics</a>
                <a href="/my_playback" <? if ($_PAGE->Page == "Playback") : ?>id="nav_sel"<? endif ?>>Playback Setup</a>
                <? if ($_USER->Is_Partner) : ?><a href="/partner_settings" <? if ($_PAGE->Page == "Partner") : ?>id="nav_sel"<? endif ?>>Partner Settings</a><? endif ?>
                <a href="/manage_account" <? if ($_PAGE->Page == "Manage" || $_PAGE->Page == "Delete") : ?>id="nav_sel"<? endif ?>>Manage Account</a>
            </div>
            <div class="subpage">
                <div class="subpage-content">
                    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_settings/$_PAGE->Page.php" ?>
                </div>
            </div>
        </main>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/footer.php" ?>
    </div>
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
    </body>
</html>
