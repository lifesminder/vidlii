<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activation
//- Requires ($_GET["u"])
if (!$_USER->logged_in)         { redirect("/login"); exit();   }
if (!$_USER->Is_Activated)    { redirect("/"); exit();        }
if (!isset($_GET["u"]))         { redirect("/"); exit();        }


$User = $DB->execute("SELECT username FROM users WHERE displayname = :USER OR username = :USER LIMIT 1", true, [":USER" => $_GET["u"]]);

if ($DB->RowNum == 1) {
	$User = $User["username"];
	if ($_USER->username != $User) {
		$_USER->subscribe_to($User);
	}
}

redirect($_SERVER["HTTP_REFERER"]);