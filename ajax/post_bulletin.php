<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["bulletin"]) to not be empty
if (!$_USER->logged_in)                                       { exit(); }
if (!isset($_POST["bulletin"]) && !empty($_POST["bulletin"])) { exit(); }


$_GUMP->validation_rules(array(
    "bulletin"          => "required|max_len,510"
));

$_GUMP->filter_rules(array(
    "bulletin"         => "trim|NoHTML"
));

$Validation = $_GUMP->run($_POST);

if ($Validation) {
    $DB->execute("SELECT id FROM bulletins WHERE by_user = :USERNAME AND content = :BULLETIN", false,
                [
                    ":USERNAME" => $_USER->username,
                    ":BULLETIN" => $Validation["bulletin"]
                ]);
    if ($DB->RowNum == 0) {
        $DB->modify("INSERT INTO bulletins (content,by_user,date) VALUES(:BULLETIN,:USERNAME,NOW())",
                   [
                       ":BULLETIN" => $Validation["bulletin"],
                       ":USERNAME" => $_USER->username
                   ]);
    }
}