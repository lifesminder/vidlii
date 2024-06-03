<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }


//GET INFO
$Info = $_USER->get_profile();

//GET LAST USERNAME CHANGE
$uchange = time() - $Info["last_username_update"];
if ($uchange >= 15552000) {
	$uchange = true;
} else {
	$uchange = 15552000 - $uchange;
	if ($uchange < 120) $time = "1 minute";
	else if ($uchange < 3600) $time = (int)($uchange / 60)+1 . " minutes";
	else if ($uchange < 86400) $time = (int)($uchange / 3600)+1 . " hours";
	else if ($uchange < 2592000) $time = (int)($uchange / 86400)+1 . " days";
	else $time = (int)($uchange / 2592000)+1 . " months";
	
	$uchange = "You must wait $time before your next username change!";
}

if (isset($_POST["save_email"]) && $_SESSION["token"] == $_POST["cst"]) {
    $_GUMP->validation_rules(array(
        "email"       => "required|valid_email|max_len,128",
        "password"    => "required|max_len,128|min_len,4"
    ));

    $_GUMP->filter_rules(array(
        "email"       => "trim|sanitize_email",
        "password"    => "trim"
    ));

    $Validation = $_GUMP->run($_POST);

    if ($Validation && $_USER->Info["email"] !== $_POST["email"]) {
        $Email    = filter_var($Validation["email"], FILTER_SANITIZE_EMAIL);
        $Password = $Validation["password"];

        $DB->execute("SELECT email FROM users WHERE email = :EMAIL", false, [":EMAIL" => $Email]);

        if (password_verify($Password, $_USER->Info["password"]) && $DB->RowNum == 0) {
            $DB->modify("UPDATE users SET email = :EMAIL WHERE username = :USERNAME",
                       [
                           ":EMAIL"     => $Validation["email"],
                           ":USERNAME"  => $_USER->username
                       ]);
            notification("Email was successfully updated!", "/manage_account", "green"); exit();
        } else {
            if (!password_verify($Password, $_USER->Info["password"])) {
                notification("The password you entered is incorrect!", "/manage_account", "red"); exit();
            } elseif ($_DATABASE->exists($Email,"email","users")) {
                notification("This email is already in use!", "/manage_account", "red"); exit();
            }
        }
    } else {
        if (mb_strlen($_POST["password"]) < 4) {
            notification("The password must be over 4 letters long!", "/manage_account", "red"); exit();
        } elseif ($_USER->Info["email"] == $_POST["email"]) {
            notification("You're using this email at the moment!", "/manage_account", "red"); exit();
        }
    }
}

if (isset($_POST["save_password"]) && $_SESSION["token"] == $_POST["cst"]) {
    $_GUMP->validation_rules(array(
        "current_password"      => "required|max_len,128|min_len,4",
        "new_password"          => "required|max_len,128|min_len,4",
        "new_password2"         => "required|equals,new_password"
    ));

    $_GUMP->filter_rules(array(
        "current_password"   => "trim",
        "new_password"       => "trim",
        "new_password2"      => "trim"
    ));

    $Validation = $_GUMP->run($_POST);

    if ($Validation) {
        if (password_verify($Validation["current_password"],$_USER->Info["password"])) {
            $New_Password = password_hash($Validation["new_password"], PASSWORD_BCRYPT);

            $DB->modify("UPDATE users SET password = :PASSWORD WHERE username = :USERNAME",
                       [
                           ":PASSWORD" => $New_Password,
                           ":USERNAME" => $_USER->username
                       ]);
            notification("Password was successfully changed!", "/manage_account", "green"); exit();
        } else {
            notification("The password you entered is incorrect!", "/manage_account", "red"); exit();
        }
    } else {
        if ($_POST["new_password"] !== $_POST["new_password2"]) {
            notification("The two passwords you entered don't match!", "/manage_account", "red"); exit();
        } elseif (mb_strlen($_POST["current_password"]) < 4 || mb_strlen($_POST["new_password"]) < 4) {
            notification("Passwords must be over 4 letters long!", "/manage_account", "red"); exit();
        }
    }
}

if (isset($_POST["save_username"]) && $uchange === true && ctype_alnum($_POST["new_username"])) {
    $_GUMP->validation_rules(array(
        "new_username"   => "required|alpha_numeric|max_len,20|min_len,1"
    ));

    $_GUMP->filter_rules(array(
        "new_username"   => "trim"
    ));

    $Validation = $_GUMP->run($_POST);
    if ($Validation) {
        $New_Name = (string)clean($Validation["new_username"]);
		if ($New_Name != $_USER->displayname && $New_Name !== "0" && $New_Name !== 0) {
			$ExistsError = false;
			$Exists1 = $DB->execute("SELECT username FROM users WHERE displayname = :USER LIMIT 1", true, [":USER" => $New_Name]);

			if ($DB->RowNum == 1) { $Exists1 = $Exists1["username"]; } else { $Exists1 = false; }

			if ($Exists1 && $Exists1 != $_USER->username) {
				$ExistsError = true;
			}
			
			if (!$ExistsError) {
				$Exists2 = $DB->execute("SELECT username FROM users_oldnames WHERE displayname = :USER LIMIT 1", true, [":USER" => $New_Name]);

				if ($DB->RowNum == 1) { $Exists2 = $Exists2["username"]; } else { $Exists2 = false; }

				if ($Exists2 && $Exists2 != $_USER->username) {
					$ExistsError = true;
				}
			}
			
			if (!$ExistsError) {
				$UpdateError = false;
				if (!$Exists1 && !$Exists2) {
					$DB->modify("INSERT INTO users_oldnames SET displayname = :DNAME, username = :UNAME",
                               [
                                   ":DNAME" => $_USER->displayname,
                                   ":UNAME" => $_USER->username
                               ]);
					if ($DB->RowNum == 0) {
						$UpdateError = true;
					}
				}
				
				if (!$UpdateError) {
					$DB->modify("UPDATE users SET displayname = :DNAME, last_username_update = :LAST WHERE username = :UNAME LIMIT 1",
                               [
                                   ":DNAME" => $New_Name,
                                   ":UNAME" => $_USER->username,
                                   ":LAST"  => time()
                               ]);

					if ($DB->RowNum == 0) {
						$UpdateError = true;
					}
				}
				
				if (!$UpdateError) {
					$uchange                    = "You must wait 6 months before your next username change!";
					$_USER->displayname         = $New_Name;
					
					notification("Your username has been successfully changed to $New_Name!","","green");
				} else {
					notification("Oops, something went wrong!" . $Exists1 . $Exists2,"","red");
				}
			} else {
				notification("The user $New_Name already exists!","","red");
			}
		}
	} else {
		notification("The username you've provided is invalid!","","red");
	}
}

if (isset($Info)) {
    $Channel_Version = $Info["channel_version"];
}


$Account_Title = "Manage Account";


$_PAGE->set_variables(array(
    "Page_Title"        => "Manage Account - VidLii",
    "Page"              => "Manage",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/settings_structure.php";
