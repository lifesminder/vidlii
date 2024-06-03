<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activated
if (!$_USER->logged_in)         { exit(); }
if (!$_USER->Is_Activated)    { exit(); }
if ($_USER->username == "APPle5auc31995") { exit(); }


$Videos_Today = $DB->execute("SELECT COUNT(url) as amount FROM videos WHERE uploaded_by = :USERNAME AND DATE(uploaded_on) = CURDATE()", true, [":USERNAME" => $_USER->username])["amount"];

if ($Videos_Today >= 25) {

    echo "error"; exit();

}


$_GUMP->validation_rules(array(
    "title"          => "required|max_len,101|min_len,1"
));

$_GUMP->filter_rules(array(
    "title"          => "trim|NoHTML"
));

$Validation = $_GUMP->run($_POST);

if ($Validation) {

    $Title = $Validation["title"];

    //GENERATE URL
    $Found = false;
    while ($Found === false) {

        $URL = random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_",11);

        $Video_ID_Check     = $DB->execute("SELECT url FROM videos WHERE url = :URL", false, [":URL" => $URL]);
        $Delete_ID_Check    = $DB->execute("SELECT id FROM videos_deleted WHERE id = :URL", false, [":URL" => $URL]);

        if (!$Video_ID_Check && !$Delete_ID_Check) {

            $Found = true;

        }
    }


    $DB->modify("INSERT INTO videos (url,title,uploaded_by,uploaded_on,status) VALUES (:URL,:TITLE,:UPLOADED_BY,NOW(),0)",
               [
                   ":URL"           => $URL,
                   ":TITLE"         => $Title,
                   ":UPLOADED_BY"   => $_USER->username
               ]);

    $DB->modify("UPDATE users SET videos = videos + 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);

    echo $URL; exit();

}

echo "error";