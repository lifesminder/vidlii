	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $Page_Title ?></title>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@VidLii">
	<meta name="twitter:title" content="<?= $Profile["displayname"] ?> - VidLii">
	<meta name="twitter:description" content="<?= $Page_Description ?>">

	<meta property="og:title" content="<?= $Profile["displayname"] ?> - VidLii">
	<meta property="og:type" content="website">
	<meta property="og:image" content="<?= $Avatar ?>">
	<meta property="og:description" content="<?= $Page_Description ?>">

	<meta property="og:site_name" content="VidLii">
	<meta property="og:url" content="/user/<?= $Profile["displayname"] ?>">

	<link rel="shortcut icon" href="/img/favicon.png" type="image/png">
	<link rel="apple-touch-icon" href="/img/vl_app.png">

	<? if ($_USER->logged_in && $Profile["channel_version"] == 1) : ?>
	<link rel="prefetch" href="/img/mail1.png">
	<link rel="prefetch" href="/img/comm1.png">
	<link rel="prefetch" href="/img/share1.png">
	<link rel="prefetch" href="/img/block1.png">
	<link rel="prefetch" href="/img/friend1.png">
	<? endif ?>

	<? if ($Profile["c_comments"]) : ?>
	<? if (isset($_GET["page"]) && $_GET["page"] !== "1" && !empty($_GET["page"]) && is_numeric($_GET["page"]) && ($_GET["page"] * 10) < $Profile["channel_comments"]) : ?>
	<link rel="next" href="/user/<?= $Profile["displayname"] ?>/<?= $_GET["page"] + 1 ?>">
	<link rel="prev" href="/user/<?= $Profile["displayname"] ?><? if ((int)$_GET["page"] !== 2) { echo "/"; echo (int)$_GET["page"] - 1; } ?>">
	<? elseif (($_GET["page"] == 1 || $_GET["page"] == "") && $Profile["channel_comments"] > 10) : ?>
	<link rel="next" href="/user/<?= $Profile["displayname"] ?>/2">
	<? elseif (is_numeric($_GET["page"]) && ($_GET["page"] * 10) >= $Profile["channel_comments"]) : ?>
	<link rel="prev" href="/user/<?= $Profile["displayname"] ?>/<?= $_GET["page"] - 1 ?>">
	<? endif ?>
	<? endif ?>

	<meta name="description" content="<?= htmlspecialchars($Page_Description) ?>">
	<meta name="keywords" content="<?= htmlspecialchars($Page_Keywords) ?>">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="theme-color" content="#<?= $Profile["h_head"] ?>">

	<?php if($Profile["channel_version"] >= 3) { ?>
	<link rel="stylesheet" type="text/css" href="<?= CSS_FILE ?>">
	<?php } else { ?>
	<? if ($Profile["channel_version"] == 1 || $Profile["channel_version"] == 2) : ?>
	<link rel="stylesheet" type="text/css" href="<?= CSS_FILE ?>">
	<link rel="stylesheet" type="text/css" href="<?= PROFILE_CSS_FILE ?>">
	<? else : ?>
	<link rel="stylesheet" type="text/css" href="<?= COSMIC_CSS_FILE ?>">
	<? endif ?>
	<?php } ?>

	<? $_THEMES->load_themes() ?>

	<style>.channel_banner{ border:none; overflow:hidden; width:1000px; height:150px; margin-top: 12px; }</style>
	<? if ($Is_OWNER): ?>
		<script src="/js/jscolor.min.js" async></script>
	<? endif ?>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script>
			zd = true;
		</script>

	<? if ($Player == 1) : ?>
		<script src="/jwplayer2/jwplayer.js"></script>
		<script>jwplayer.key="pC5h3OO+44j2Ht66GosiwI/yBi8Kp1KC8cZL/g=="</script>
		<script src="/js/modern_player.js?<?=$VLPVERSION?>"></script>
	<? else : ?>
		<script src="/vlPlayer/main19.js?<?=$VLPVERSION?>"></script>
		<script>swfobject.registerObject("flPlayer", "9.0.0");</script>
	<? endif ?>
