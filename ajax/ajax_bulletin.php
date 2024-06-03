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

        $Avatar = $DB->execute("SELECT avatar FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_USER->username])["avatar"];

        function user_avatar5($User,$Width,$Height,$Avatar,$Extra_Class = "") {
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
            return '<a href="/user/'.$User.'"><img src="'.$Avatar.'" width="'.$Width.'" height="'.$Height.'" class="avt2 '.$Extra_Class.'" alt="'.$User.'"></a>';
        }

        ?>
        <div class="friend_activity">
        <div>
            <?= user_avatar5($_USER->username, 60, 60, $Avatar) ?>
        </div>
        <div>
            <div><a href="/user/<?= $_USER->username ?>"><?= $_USER->username ?></a></div>
            <div class="f_msg"><?= hashtag_search(DoLinks(nl2br($Validation["bulletin"]))) ?></div>
            <div class="f_btm">1 second ago</div>
        </div>
        </div>
        <?
    }
}