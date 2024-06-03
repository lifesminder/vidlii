<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login AND ($_GET["code"]). If not Logged In send to Login Page with Code for activation
//- Requires Not Being Activated
//- Requires ($_GET["code"])
if (!$_USER->logged_in) {
    if (!isset($_GET["code"])) {
        redirect("/login"); exit();
    } else {
        notification("Please log in to activate your account!","/login?activate=".$_GET["code"],"red"); exit();
    }
}
if ($_USER->Is_Activated)     { notification("You've already activated your account!","/","red"); exit(); }
if (!isset($_GET["code"]))      { redirect("/"); exit();                                                                     }


$DB->modify("DELETE FROM activations WHERE username = :USERNAME AND secret = BINARY :CODE",
            [ 
                ":USERNAME"   => $_USER->username,
                ":CODE"       => $_GET["code"]
            ]);
if ($DB->RowNum == 1) {
    $DB->modify("UPDATE users SET activated = 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
    redirect("/index?m=cr"); exit();
} else {
    notification("This activation code isn't valid!","/","red");
}