<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Being Not Logged In OR Being Logged In and rejecting the recovery email
if ($_USER->logged_in && !isset($_GET["reject"])) { redirect("/"); exit(); }


if (!isset($_GET["code"]) && !isset($_GET["reject"])) {
    if (isset($_POST["submit_forgot"])) {
        if (isset($_POST["vl_username"]) && ctype_alnum($_POST["vl_username"]) && check_captcha()) {
            $Username = $DB->execute("SELECT username FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_POST["vl_username"]]);
            if ($DB->RowNum == 1) {
                $Username   = $Username["username"];
                $Code       = random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_", 30);
                $Insert     = $DB->modify("INSERT INTO forgot_password VALUES(:USERNAME, :CODE)",
                                         [
                                             ":USERNAME" => $Username,
                                             ":CODE"     => $Code
                                         ]);
                if ($DB->RowNum == 1) {
                    $Email = $DB->execute("SELECT email FROM users WHERE username = :USERNAME", true, [":USERNAME" => $Username])["email"];
                    require_once "_includes/_libs/PHPMailerAutoload.php";

                    $mail = new PHPMailer;

                    $mail->Host         = 'smtp.mandrillapp.com';
                    $mail->SMTPAuth     = true;
                    $mail->Username     = 'Vlare';
					$mail->Password     = 'UUpQA5Dp5CgI3Ok36zIGpA';
					$mail->SMTPSecure   = 'tls';
					$mail->Port         = 587;
                    $mail->isSMTP();
					$mail->setFrom('team@vidlii.com', 'VidLii');
                    $mail->addAddress($Email, $Username);
                    $mail->addReplyTo('team@vidlii.com', 'Don\'t Reply!');
					$mail->addCC('team@vidlii.com');
					$mail->addBCC('team@vidlii.com');

                    $mail->isHTML(true);

                    $mail->Subject = 'VidLii Password';
                    $mail->Body = 'You requested that your password should be reset, click on the link below if that is true:<br><br><strong><a href="/forgot_password?code=' . $Code . '">RECOVER</a></strong><br><br><br>If that is not true, please click the link below:<br><br><strong><a href="/forgot_password?reject=' . $Code . '">REJECT</a></strong>';

                    $mail->send();
                    notification("Now check your email for instructions on how to recover your password!", "/login", "green");

                } else {
                    notification("We already sent you a recovery mail!", "/login", "red");
                }
            } else {
                notification("This user doesn't exist!", "/forgot_password", "red");
            }
        } else {
            notification("This user doesn't exist!", "/forgot_password", "red");
        }
    }
    $_PAGE->set_variables(array(
        "Page_Title"        => "Forgot Password - VidLii",
        "Page"              => "Forgot",
        "Page_Type"         => "Home",
        "Show_Search"       => false
    ));
    require_once "_templates/page_structure.php";
} elseif (isset($_GET["code"])) {
    $Check = $DB->execute("SELECT * FROM forgot_password WHERE code = :CODE", true, [":CODE" => $_GET["code"]]);
    if ($DB->RowNum == 1) {
        if (isset($_POST["submit_password"])) {

            if (isset($_POST["vl_password"],$_POST["vl_password2"]) && strlen($_POST["vl_password"]) <= 128 && strlen($_POST["vl_password"]) > 4 && $_POST["vl_password"] === $_POST["vl_password2"]) {
                $Password = password_hash($_POST["vl_password"], PASSWORD_BCRYPT);
                $Username = $Check["username"];

                $DB->modify("UPDATE users SET password = :PASSWORD WHERE username = :USERNAME",
                           [
                               ":USERNAME" => $Username,
                               ":PASSWORD" => $Password
                           ]);
                if ($DB->RowNum == 1) {
                    $DB->modify("DELETE FROM forgot_password WHERE username = :USERNAME AND code = :CODE",
                               [
                                   ":USERNAME"  => $Username,
                                   ":CODE"      => $Check["code"]
                               ]);
                    notification("You can now log in with your new password!","/login","green"); exit();
                }
            } else {
                notification("The Passwords must match!", "/forgot_password?code=".$_GET["code"], "red"); exit();
            }
        }

        $_PAGE->set_variables(array(
            "Page_Title"        => "Recover Password - VidLii",
            "Page"              => "Forgot",
            "Page_Type"         => "Home",
            "Show_Search"       => false
        ));
        require_once "_templates/page_structure.php";
    } else {
        notification("This is an invalid code!", "/login", "red"); exit();
    }
} elseif (isset($_GET["reject"])) {
    $DB->modify("DELETE FROM forgot_password WHERE code = :CODE", [":CODE" => $_GET["reject"]]);

    if ($DB->RowNum == 1)           { notification("Thank you!", "/", "green"); exit();                             }
    else                            { notification("The password has already been recovered!", "/", "red"); exit(); }

} else {
    notification("This is an invalid code!", "/login", "red"); exit();
}