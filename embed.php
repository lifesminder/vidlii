<?php
	require_once "_includes/init.php";

	$URL = false;
	if (!isset($_GET["a"])) { $_GET["a"] = 0; }
	
	if (isset($_GET["v"])) {
		$_VIDEO = new Video($_GET["v"], $DB);
		if ($_VIDEO->exists() !== false) {			
			$_VIDEO->get_info();
			$URL        = $_VIDEO->Info["url"];
			$FILENAME   = $_VIDEO->Info["file"];
			$ISHD       = $_VIDEO->Info["hd"] == 1 ? true : false;
			$Length     = $_VIDEO->Info["length"];
			$Status     = $_VIDEO->Info["status"];
		}
	}

	//VALUES
	if (isset($_COOKIE["player"])) {
		$Player = (int)$_COOKIE["player"];
		if ($Player < 0 || $Player > 3) $Player = 2;
	} else {
		$Player = 2;
	}
	
	$Autoplay = $_GET["a"];
?>


<!DOCTYPE html>
<html>
	<head>
		<style>
			body, html {
				margin: 0;
				padding: 0;
				overflow: hidden;
				height: 100%;
				width: 100%;
            <? if ($_VIDEO->Info["privacy"] != 2) : ?>background: black<? endif ?>
			}
			.vlScreenContainer {
				background: #000;
			}
		</style>
		<? if ($_VIDEO->Info["privacy"] != 2) : ?>
		<? if ($URL) : ?>
			<? if ($Player != 1) : ?>
				<script src="/js/jquery.js"></script>
				<script src="/vlPlayer/main15.js?<?=$VLPVERSION?>"></script>
				<script>window.vlpv = <?=$VLPVERSION?>;</script>
				<script>swfobject.registerObject("flPlayer", "9.0.0");</script>
			<? elseif ($Player == 1) : ?>
				<script src="/jwplayer2/jwplayer.js"></script>
				<script>jwplayer.key="pC5h3OO+44j2Ht66GosiwI/yBi8Kp1KC8cZL/g=="</script>
				<script src="/js/modern_player.js?<?=$VLPVERSION?>"></script>
			<? endif ?>
		<? endif ?>
        <? endif ?>
	</head>
	<body>
    <? if ($_VIDEO->Info["privacy"] != 2) : ?>
		<script>var videoInfo = { adjust: false };</script>
		<? if ($URL) require_once($_SERVER['DOCUMENT_ROOT']."/_templates/_layout/player.php"); ?>
    <? else : ?>
        <div style="color:white;text-align:center">This is a private video!</div>
    <? endif ?>
	</body>
</html>
