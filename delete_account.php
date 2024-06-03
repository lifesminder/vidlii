<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }

//GET INFO
$Info = $_USER->get_profile();
if (isset($Info))
    $Channel_Version = $Info["channel_version"];

//PARSE DATA
if (isset($_POST["delete_submit"]) || isset($_POST["delete_agree"])) {
	$DB->execute("SELECT username FROM terminations WHERE username = :USERNAME LIMIT 1", false, [":USERNAME" => $_USER->username]);
	if ($DB->RowNum > 0) { // Deletion Code already exists
		$Count = $DB->execute("SELECT issued FROM terminations WHERE username = :USERNAME LIMIT 1", true, [":USERNAME" => $_USER->username])["issued"];
		$Count = time() - $Count;
		
		if ($Count > 86400) { // Deletion Code is expired
			$Count      = $DB->modify("DELETE FROM terminations WHERE username = :USERNAME LIMIT 1", [":USERNAME" => $_USER->username]);
			$CodeExists = false;
		} else {
			$Count = 86400 - $Count * 2;
			if ($Count < 60) $Count = "a minute";
			elseif ($Count < 3600) $Count = (int)($Count / 60) . " minute(s)";
			else $Count = (int)($Count / 3600) . " hour(s)";
			$CodeExists = true;
		}
	} else { // Deletion Code doesn't exist
		$CodeExists = false;
	}
	
	if ($CodeExists) {
		$_SESSION["notification"]   = "A termination code has already been sent to your e-mail address,<br>please wait for $Count before trying again.";
		$_SESSION["n_color"]        = "red";
	}
}

if (isset($_POST["delete_agree"]) && !$CodeExists) {
	$phase = 1;
} elseif (isset($_POST["delete_submit"]) && !$CodeExists) {
	$phase = 1;
	
    $_GUMP->validation_rules(array(
        "vl_password1"   => "required|max_len,128",
        "vl_password2"   => "required|max_len,128"
    ));

    $_GUMP->filter_rules(array(
        "vl_password1"   => "trim",
        "vl_password2"   => "trim"
    ));
	
	$Validation = $_GUMP->run($_POST);
	if ($Validation && !isTorRequest()) {
		if ($_USER->check_password($Validation["vl_password1"]) && $Validation["vl_password1"] == $Validation["vl_password2"]) {			
			$Deletion_Code = random_string("ABCDEFGHIJK123456789abcdefghijklmnop", 25);
			$DB->modify("INSERT INTO terminations VALUES (:USERNAME,:SECRET,:TIME)",
                       [
                           ":USERNAME"  => $_USER->username,
                           ":SECRET"    => $Deletion_Code,
                           ":TIME"      => time()
                       ]);
			if ($DB->RowNum > 0) {
//				require_once ROOT_FOLDER."/_includes/_libs/PHPMailerAutoload.php";
//				$mail               = new PHPMailer;
//				$mail->Host         = 'mail.vidlii.com';
//				$mail->SMTPAuth     = true;
//				$mail->Username     = 'noreply@vidlii.com';
//				$mail->Password     = 'Q@MmH=Yqy0Q5';
//				$mail->SMTPSecure   = 'ssl';
//				$mail->Port         = 465;
//                $mail->isSMTP();
//				$mail->setFrom('noreply@vidlii.com', 'VidLii');
//				$mail->addAddress($Info["email"], $_USER->username);
//				$mail->addReplyTo('noreply@vidlii.com', 'Don\'t Reply!');
//				$mail->addCC('noreply@vidlii.com');
//				$mail->addBCC('noreply@vidlii.com');
//				$mail->isHTML(true);
//				$mail->Subject = 'VidLii Termination';
//				$mail->Body = '
//					<body style="background-color:#eff2ff;font-family:Arial, Helvetica, sans-serif">
//					<div style="margin: 10px 0 10px 0;text-align:center;padding-top:10px"><img src="/img/Vidlii6.png" width="200px"></div>
//					<div style="background-color:white;border-radius:8px;width:50%;padding:25px;margin:0 auto 10px">
//					<div style="text-align:center;font-size:23px;font-weight:bold;color:#333333;margin:0 0 15px">Delete Your Account</div>
//					<div style="font-size:14px;line-height: 24px;color:#666666;text-align:center">Click The button below to prove your ownership of the account you are about to terminate.</div>
//					<div style="margin-top:15px;text-align:center">
//					<a href="/terminate?code=' . $Deletion_Code . '" style="padding:10px 20px;border-radius:6px;font-size:16px;color:white;font-weight:bold;text-decoration: none;background-color:#4d90fe">DELETE</a>
//					</div>
//					</div>
//					<div style="height:15px"></div>
//					</body>';
//					
//				$mail->send();
				header("Location: /terminate?code=".$Deletion_Code);
				$phase = 2;

			} else {
				$_SESSION["notification"]   = "An error has occurred while processing your request.";
				$_SESSION["n_color"]        = "red";
			}
		} else {
			$_SESSION["notification"]   = "Passwords either don't match or are incorrect.";
			$_SESSION["n_color"]        = "red";
		}
	} else {
		$_SESSION["notification"]   = "An error has occurred while processing your request.";
		$_SESSION["n_color"]        = "red";
	}
} else {
	$phase = 0;
}

//SHOW PAGE
$Account_Title = "Delete Account";

$_PAGE->set_variables(array(
    "Page_Title"        => "Delete Account - VidLii",
    "Page"              => "Delete",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));

require_once "_templates/settings_structure.php";
