<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in) { redirect("/login"); exit(); }

//GET INFO
$Info = $_USER->get_profile();
if (isset($Info))
    $Channel_Version = $Info["channel_version"];

//PARSE DATA
$phase = 0;
$Secret_Code = isset($_POST["vl_code"]) ? $_POST["vl_code"] : $_GET["code"];
if ($Secret_Code) {
	$Count = $DB->execute("SELECT COUNT(*) as amount FROM terminations WHERE username = :USERNAME AND secret = BINARY :CODE LIMIT 1", true, [":USERNAME" => $_USER->username, ":CODE" => $_GET["code"]])["amount"];
	if ($Count == 1) { // Deletion Code exists
		$Count = $DB->execute("SELECT issued FROM terminations WHERE username = :USERNAME AND secret = BINARY :CODE LIMIT 1", true, [":USERNAME" => $_USER->username, ":CODE" => $_GET["code"]])["issued"];
		$Count = time() - $Count;
		
		if ($Count <= 86400) { // Deletion Code is valid
			$phase = 3;
		} else { // Deletion Code is expired
			$DB->modify("DELETE FROM terminations WHERE username = :USERNAME LIMIT 1", [":USERNAME" => $_USER->username]);
			$_SESSION["notification"]   = "Your termination code has expired, please issue a new one.";
			$_SESSION["n_color"]        = "red";
		}
	} else { // Deletion Code doesn't exist
		$_SESSION["notification"] = "Invalid termination code.";
		$_SESSION["n_color"] = "red";
	}
} else {
	$_SESSION["notification"] = "No termination code entered.";
	$_SESSION["n_color"] = "red";
}

if ($phase == 3 && isset($_POST["terminate_submit"])) {
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
			$DB->modify("DELETE FROM terminations WHERE username = :USERNAME LIMIT 1", [":USERNAME" => $_USER->username]);
			$DB->modify("UPDATE users SET banned = 2 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
            $DB->modify("UPDATE videos SET banned_uploader = 1 WHERE uploaded_by = :USERNAME", [":USERNAME" => $_USER->username]);

            $_USER->logout();
			notification("Your account has been terminated!","/","green"); exit();
		} else {
			$_SESSION["notification"]   = "Passwords either don't match or are incorrect.";
			$_SESSION["n_color"]        = "red";
		}
	} else {
		$_SESSION["notification"]   = "An error has occurred while processing your request.";
		$_SESSION["n_color"]        = "red";
	}
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
