<!doctype html>
<html lang="en">
    <head>
        <?php require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_head/main_head.php"; ?>
        <script src="/js/main.js"></script>
    </head>
    <body>
	<?php
        $Top_text = $DB->execute("SELECT value FROM settings WHERE name = 'top_text'", true)["value"];
        if(!empty($Top_text)) {
    ?>
        <div class="top">
            <?php echo $Top_text; ?>
        </div>
    <?php } ?>
    <div class="wrapper">
        <?php
            require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php";

            if ($_USER->logged_in && !$_USER->Is_Activated) { ?>
            <div style="border: 1.5px solid red;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold">Click the activation link we sent to your email to use VidLii to its fullest! (Check the spam folder!)</div>
        <?php } else if(isset($_COOKIE["notification"])) {
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
        <main class="bottom_wrapper">
            <?php
                if(!isset($twig) || (isset($twig) && $twig == false)) {
                    require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_pages/".$_PAGE->Page.".php";
                } else {
                    $engine->template($page, (isset($args) && count($args) > 0) ? $args : []);
                }
            ?>
        </main>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/footer.php" ?>
    </div>
    <script src="<?= MAIN_JS_FILE ?>"></script>
    </body>
</html>