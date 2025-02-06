<!doctype html>
<html lang="en">
    <head>
        <?php require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_head/main_head.php"; ?>
        <script src="/js/main.js"></script>
    </head>
    <body>
	<?php
		$Has_Adblock = false;
		
		//if (!$_USER->logged_in || (strtolower($_USER->username) != "bloxed" && user_ip() != "85.29.252.14")) {
		//if ($_USER->logged_in && $_USER->username == "VidLii") {
			$Check = $DB->execute("SELECT submit_date FROM badboys WHERE ip = :IP AND submit_date > NOW() - INTERVAL 1 MINUTE", true, [":IP" => user_ip()]);
			//if ($Check || isTorRequest() || (isset($_SERVER["HTTP_CF_IPCOUNTRY"]) && $_SERVER["HTTP_CF_IPCOUNTRY"] == "MA")) {
			if ($Check || isTorRequest()) {
	
				$Has_Adblock = false;
				if ($Check) $Adblock_Time = strtotime($Check["submit_date"]);
				else 		$Adblock_Time = time() - rand(0, 20);
			}
			
		//}
		//}
        $Top_text = $DB->execute("SELECT value FROM settings WHERE name = 'top_text'", true)["value"];
        if (!empty($Top_text)) {
    ?>
    <div class="top"><?php echo $Top_text; ?></div>
    <?php
        }
    ?>
    <div class="wrapper">
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php" ?>
        <?php if ($_USER->logged_in && !$_USER->Is_Activated) { ?>
            <div style="border: 1.5px solid red;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold">Click the activation link we sent to your email to use VidLii to its fullest! (Check the spam folder!)</div>
        <?php } if (isset($_SESSION["notification"])) { ?>
            <div style="border: 1.5px solid <?= $_SESSION["n_color"] ?>;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold"><?= $_SESSION["notification"] ?></div>
            <? unset($_SESSION["notification"]); unset($_SESSION["n_color"]); ?>
        <?php } ?>
        <main class="bottom_wrapper">
            <?php
                if(!isset($twig) || (isset($twig) && $twig == false)) {
                    require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_pages/".$_PAGE->Page.".php";
                } else {
                    $engine->template($page, $args);
                }
            ?>
        </main>

        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/footer.php" ?>
    </div>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
    </body>
</html>