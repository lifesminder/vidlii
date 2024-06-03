<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["bulletin"]) to not be empty
if (!$_USER->logged_in)                                       { exit(); }
if (!isset($_POST["bulletin"]) && !empty($_POST["bulletin"])) { exit(); }


function no_link_avatar($User,$Width,$Height,$Avatar,$Extra_Class = "") {
    if (strpos($Avatar,"u=") !== false) { $Avatar = str_replace("u=","",$Avatar); $Folder = "avt"; } else { $Upload = false; $Folder = "thmp"; }

    if (empty($Avatar) or !file_exists("../usfi/$Folder/$Avatar.jpg")) {
        $Avatar = "/img/no.png";
    } else {
        if ($Folder == "avt") {
            $Avatar = "/usfi/avt/$Avatar.jpg";
        } else {
            $Avatar = "/usfi/thmp/$Avatar.jpg";
        }

    }
    return '<div href="/user/'.$User.'"><img src="'.$Avatar.'" width="'.$Width.'" height="'.$Height.'" class="avt2 '.$Extra_Class.'" alt="'.$User.'"></div>';
}


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

        $Avatar = $DB->execute("SELECT avatar FROM users WHERE username = '$_USER->username'", true)["avatar"];
        ?>
        <div>
            <div>
                <div><?= no_link_avatar($_USER->displayname,50,50,$Avatar) ?></div>
                <span>1 second ago | <a href="javascript:void(0)" style="color:#999" onclick="delete_cosmic_bulletin()">Delete</a></span>
                <div>
                    <div><a href="/user/<?= $_USER->displayname ?>"><?= $_USER->displayname ?></a> posted:</div>
                    <div><?= $Validation["bulletin"] ?></div>
                </div>
            </div>
        </div>
        <?
    }
}