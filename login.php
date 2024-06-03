<?php
require_once "_includes/init.php";

// Set page headers
header("Content-Security-Policy: frame-ancestors 'none'");
header("Cache-Control: max-age=0, no-cache, no-store, must-revalidate");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
header("Pragma: no-cache");

// Check if login is enabled in site settings
$Sign_In = $DB->execute("SELECT value FROM settings WHERE name = 'login'", true)["value"] ?? 1;
if ($Sign_In == 0) {
    notification("Signing in has been temporarily disabled!","/");
    exit();
}


//REQUIREMENTS / PERMISSIONS
//- Requires Being Not Logged In
if ($_USER->logged_in)         { redirect("/"); exit(); }

// Hash IP address to generate secret
$ip_hash = base64_encode(md5(user_ip()));
// Create secret from a small piece offset by date
$login_secret = substr($ip_hash, (int) date('d'), 3);

// Fetched failed login count in the past X minutes
// If it exceeds Y, enforce captcha
// TODO When configuration and everything else is setup, make these values configurable
$failed_logins_minutes = 10;
$failed_logins_attempts = 5;

$failed_logins_res = $DB->execute(
    "SELECT COUNT(*) AS wrong_login_count FROM wrong_logins WHERE (ip = :IP AND submit_date > DATE_SUB(NOW(), INTERVAL :MINUTES MINUTE))",
    true,
    [":IP" => user_ip(), "MINUTES" => $failed_logins_minutes]
);
$failed_logins = (int) $failed_logins_res["wrong_login_count"];
$require_captcha = $failed_logins > $failed_logins_attempts;

if (isset($_POST["submit_login"])) {

    $_GUMP->validation_rules(array(
        "v_username" => "required|max_len,128",
        $login_secret . "_password" => "required|max_len,128"
    ));

    $_GUMP->filter_rules(array(
        "v_username" => "trim",
        $login_secret . "_password" => "trim"
    ));

    $Validation = $_GUMP->run($_POST);

    // Skip bot checking validation
    // This code seems to be causing issues where users cannot sign up because they were incorrectly flagged as a bot
    // TODO Check on this code and evaluate whether it's necessary and how it can be improved
    // Ideally it should use the IP-based solution that was used for the password field
//    if (isset($_POST[substr($_SESSION["secret_id"], 6, 4)]) && $_POST[substr($_SESSION["secret_id"], 6, 4)] == substr($_SESSION["secret_id"], 1, 5).substr(user_ip(), 0, 2) && isset($_SESSION["deto"]) && !isset($_SESSION["beto"])) {
//
//        $Bot_Validated = true;
//
//    } else {
//
//        $Bot_Validated = false;
//        unset($_SESSION["deto"]);
//        $_SESSION["beto"] = 1;
//
//    }
//
//    $Bot_Validated = true;

    $continue = true;

    // Check request validation result
    if (!$Validation) {
        $_PAGE->add_error("Validation failed.");
        $continue = false;
    }

    // Check whether the request is coming from a Tor IP
    if ($continue && isTorRequest()) {
        $_PAGE->add_error("You cannot login while using TOR!");
        $continue = false;
    }

    // Check captcha result if required
    if ($continue && $require_captcha && !check_captcha()) {
        $_PAGE->add_error("Please solve the captcha!");
        $continue = false;
    }

    // Check IP against banned IP ranges
    if ($continue) {
        $ipRanges = $DB->execute("SELECT ip_range FROM iprange_bans", false);
        if ($DB->RowNum > 0) {
            foreach ($ipRanges as $ipRange) {
                if (strpos(user_ip(), $ipRange["ip_range"]) === 0) {
                    notification("You cannot log in if you've been banned already!", "/login");
                    exit();
                }
            }
        }
    }

    if ($continue) {
        // Check credentials
        $Username = $Validation["v_username"];
        $Password = $Validation[$login_secret . "_password"];

        $Query = $DB->execute("SELECT username, password, banned FROM users WHERE (displayname = :USERNAME or email = :USERNAME)", true, [":USERNAME" => $Username]);

        if ($DB->RowNum == 1) {
            $Username = $Query["username"];
            $Hash = $Query["password"];
            $Banned = $Query["banned"];

            // Check if the user is banned and verify password so that the message will be the same regardless of whether they're wrong or the user is simply banned
            if ($Banned == 0 && password_verify($Password, $Hash)) {
                $_USER->username = $Username;

                // Log the user in
                if ($_USER->login()) {
                    // Redirect to activation page if the account needs to be activated, otherwise go to the previous page the user was on
                    if (!isset($_GET["activate"])) {
                        redirect(previous_page());
                        exit();
                    } else {
                        redirect("/activate?code=" . $_GET["activate"]);
                        exit();
                    }
                }
            } else {
                $_PAGE->add_error("Your credentials are incorrect!");

                $DB->modify("INSERT INTO wrong_logins (ip, submit_date, channel) VALUES (:IP, NOW(), :CHANNEL)", [":IP" => user_ip(), ":CHANNEL" => $Username]);

                $_SESSION["sec_actions"] += 1;
            }
        } else {
            $_PAGE->add_error("This user doesn't exist!");
            unset($Username);
            $_SESSION["sec_actions"] += 1;
        }
    }
}

$_PAGE->set_variables(array(
    "Page_Title"        => "Sign In - VidLii",
    "Page"              => "Login",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/page_structure.php";
