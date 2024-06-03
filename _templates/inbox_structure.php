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
            <a href="/inbox?page=messages">Inbox</a><span style="word-spacing:10px"> / </span><?= $Inbox_Title ?>
        </div>
        <div class="settings_menu">
            <a href="/inbox?page=messages" <? if ($_PAGE->Page == "Messages" || $_PAGE->Page == "Send") : ?>id="nav_sel"<? endif ?>>Personal Messages<? if ($Inbox_Amounts["messages"] > 0) : ?><div style="float:right">(<?= $Inbox_Amounts["messages"] ?>)</div><? endif ?></a>
            <a href="/inbox?page=comments" <? if ($_PAGE->Page == "Comments") : ?>id="nav_sel"<? endif ?>>Comments<? if ($Inbox_Amounts["comments"] > 0) : ?><div style="float:right">(<?= $Inbox_Amounts["comments"] ?>)</div><? endif ?></a>
            <a href="/inbox?page=invites" <? if ($_PAGE->Page == "Invites") : ?>id="nav_sel"<? endif ?>>Invites<? if ($Inbox_Amounts["invites"] > 0) : ?><div style="float:right">(<?= $Inbox_Amounts["invites"] ?>)</div><? endif ?></a>
            <a href="/inbox?page=responses" <? if ($_PAGE->Page == "Responses") : ?>id="nav_sel"<? endif ?>>Video Responses<? if ($Inbox_Amounts["responses"] > 0) : ?><div style="float:right">(<?= $Inbox_Amounts["responses"] ?>)</div><? endif ?></a>
            <a href="/inbox?page=sent" <? if ($_PAGE->Page == "Sent") : ?>id="nav_sel"<? endif ?>>Sent Messages</a>
            <div>
                <a href="/inbox?page=send_message" style="padding:0;width:150px;margin:0 auto;position:relative;top:5px"><button <? if ($_PAGE->Page == "Send") : ?> disabled<? endif ?> class="search_button" style="border-radius:0;margin-top:4px;padding:5px 10px;margin-bottom:1px;width:100%">Send Message</button></a>
            </div>
        </div>
        <div style="float:left;width:800px;-moz-box-sizing: border-box;-ms-box-sizing: border-box;-webkit-box-sizing: border-box;padding-right: 11px;padding-left: 11px;border-left: 1px solid #ccc;border: 1px solid #ccc;border-right:1px solid #ccc;border-top: 0;background: #e2e2e2;">
            <div style="background:white;border-left:1px solid #e2e2e2;">
                <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_inbox/$_PAGE->Page.php" ?>
            </div>
        </div>
        <div style="width:970px;margin:0 auto">

        </div>
    </main>
<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/footer.php" ?>
    </div>
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
    </body>
</html>