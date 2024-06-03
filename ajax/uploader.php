<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activated
if (!$_USER->logged_in)         { exit(); }



$Video_TMP  = $_FILES["video"]["tmp_name"];
$Video_Type = pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION);
$Video_Size = filesize($_FILES["video"]["size"]) / 1024 / 1024 / 1024; //GB

$VURL = substr($_POST["url"], 0, 11);
$DB->execute("SELECT url FROM videos WHERE uploaded_by = :USERNAME AND url = :URL", false,
            [
                ":USERNAME" => $_USER->username,
                ":URL"      => $VURL
            ]);

if ($DB->RowNum == 0) {

    echo "error||Video not found in the database!"; exit();

}

if (isset($_FILES) && $Video_TMP && $Video_Size <= 2.01 && in_array(mb_strtoupper($Video_Type), ALLOWED_FORMATS) && strlen($_POST["url"]) < 20) {

    $DB->modify("UPDATE videos SET status = 1 WHERE url = :URL AND uploaded_by = :USERNAME",
               [
                   ":USERNAME"  => $_USER->username,
                   ":URL"       => $VURL
               ]);

    $DB->modify("INSERT INTO converting VALUES (:URL,NOW(),0,0)", [":URL" => $VURL]);

    move_uploaded_file($Video_TMP,"../usfi/conv/$VURL.$Video_Type");
    exit();

} else {

    $Video = new Video($VURL, $DB);
    $Video->get_info();
    if ($_USER->username == $Video->Info["uploaded_by"]) {

        @$Video->delete();

    }

}

if (!isset($_FILES)) die("error||File not found in the server! (0)");
if (!$Video_TMP) die("error||File not found in the server! (1)");
if ($Video_Size > 2.01) die("error||File too big!");
if (!in_array(mb_strtoupper($Video_Type), ALLOWED_FORMATS)) die("error||File extension $Video_Type not allowed!");
if (strlen($_POST["url"]) >= 20) die("error||Something went wrong!");
echo "error||Something definitely went wrong!";