<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_GET["v"]), ($_GET["p"])
if (!$_USER->logged_in)                         { redirect("/login"); exit();   }
if (!isset($_GET["v"]) || !isset($_GET["p"]))   { redirect("/"); exit();        }


$DB->execute("SELECT purl FROM playlists WHERE purl = :PURL AND created_by = :USERNAME", false,
            [
                ":PURL"      => $_GET["p"],
                ":USERNAME"  => $_USER->username
            ]);
if ($DB->RowNum == 1) {
    $Position = $DB->execute("SELECT position FROM playlists_videos WHERE purl = :PURL AND url = :URL", true,
                            [
                                ":PURL" => $_GET["p"],
                                ":URL"  => $_GET["v"]
                            ]);
    if ($DB->RowNum == 1) {
        $Position = (int)$Position["position"];

        $DB->modify("UPDATE playlists_videos SET position = position - 1 WHERE position > :POSITION AND purl = :PURL",
                   [
                       ":POSITION"  => $Position,
                       ":PURL"      => $_GET["p"]
                   ]);

        $DB->modify("DELETE FROM playlists_videos WHERE purl = :PURL AND url = :URL",
                   [
                       ":PURL"  => $_GET["p"],
                       ":URL"   => $_GET["v"]
                   ]);
    }
}
redirect($_SERVER["HTTP_REFERER"]);