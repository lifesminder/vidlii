<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

if (!isset($_POST["u"]))                                     { exit(); }
if (!isset($_POST["a"]))                                     { exit(); }
if ($_SERVER['HTTP_X_REQUESTED_WITH'] !== "XMLHttpRequest")  { exit(); }



if (!empty($_POST["a"])) {

    $Source = $_POST["a"];

    if (mb_strpos($Source, "/watch?v=") !== false) {

        $parts = parse_url($Source);
        parse_str($parts['query'], $query);
        $URL =  $query['v'];

        if ($URL != $_POST["u"]) {

            $Source = "!".$URL;

        }

    } elseif (mb_strpos($Source, "/results?q=") !== false) {

        $parts = parse_url($Source);
        parse_str($parts['query'], $query);
        $URL =  $query['q'];

        $Source = ")".urldecode($URL);

    } elseif (mb_strpos($Source, "/videos") !== false) {

        $Source = "v";

    } elseif ($Source == "/") {

        $Source = "h";

    } elseif (mb_strpos($Source, "/community") !== false) {

        $Source = "c";

    } elseif (mb_strpos($Source, "/user/") !== false) {

        $Source = str_replace("/user/", "", $Source);

        $Username = $DB->execute("SELECT username FROM users WHERE displayname = :SOURCE", true, [":SOURCE" => $Source]);

        if ($DB->RowNum == 0) { $Source = ""; }
        else                  { $Source = "?".$Username["username"]; }

    } elseif (mb_strpos($Source, "") === false && filter_var($Source, FILTER_VALIDATE_URL)) {

        $Source = $Source;

    } else {

        $Source = "";

    }

} else {

    $Source = "";

}


$_VIDEO = new Video($_POST["u"],$DB);

if ($_VIDEO->exists()) {
    $_VIDEO->view($_USER, 1, $Source);
}