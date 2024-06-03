<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Not To Use TOR
//- Requires ($_GET["v"])
if (!$_USER->logged_in)         { redirect("/login"); exit();   }
if (isTorRequest())             { redirect("/contest"); exit(); }
if (!isset($_GET["v"]))         { redirect("/contest"); exit(); }


$Video = new Video($_GET["v"],$DB);
$URL = $Video->exists();
if ($URL !== false) {
    $DB->execute("SELECT ip FROM contest_votes WHERE ip = :IP", false, [":IP" => user_ip()]);
    if ($DB->RowNum == 0) {
        $DB->modify("INSERT INTO contest_votes VALUES(:URL,:IP)",
                   [
                       ":URL"   => $URL,
                       ":IP"    => user_ip()
                   ]);

        $DB->execute("SELECT url FROM contest_entries WHERE url = :URL", false, [":URL" => $URL]);
        if ($DB->RowNum > 0) {
            $DB->modify("UPDATE contest_entries SET votes = votes + 1 WHERE url = :URL", [":URL" => $URL]);
        } else {
            $DB->modify("INSERT INTO contest_entries VALUES(:URL,1)", [":URL" => $URL]);
        }
    }
}
redirect($_SERVER["HTTP_REFERER"]);