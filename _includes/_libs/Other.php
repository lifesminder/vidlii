<?php
/* doesn't work on php 8.1 and 8.2
function captcha($Num, $Left = 0, $Right = 0, $Permanent = false) {
	/*
	$Pos = "";
	if ($Left !== 0) { $Pos .= "left:".$Left."px;"; }
	if ($Right !== 0) { $Pos .= "right:".$Right."px;"; }
	if ($Permanent or $_SESSION["sec_actions"] >= $Num) {
		return '<script src="https://sys.kolyma.org/kaptcha/kaptcha.js"></script><noscript><input type="hidden" name="_KAPTCHA"><input type="hidden" name="_KAPTCHA_NOJS"><iframe src="https://sys.kolyma.org/kaptcha/kaptcha.php?nojs" style="border:none;width:400px;height:150px"></iframe><br><input type="text" name="_KAPTCHA_KEY" placeholder="Paste here"><br></noscript>';
	} else {
		return false;
	}
}
if (!isset($_SESSION["ten2020Holiday"])) {
	sleep(0.78); //For Apache keep-alive and ddos
}
function check_captcha_sp($Num) {
	return true;
}
*/
?>