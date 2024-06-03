<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { exit(); }


if (isset($_POST["id"])) {

    if (strpos($_POST["id"],"c_") !== false) {

        $ID = (int)str_replace("c_","",$_POST["id"]);
        $DB->execute("SELECT video_comments.id FROM video_comments INNER JOIN videos ON video_comments.url = videos.url WHERE video_comments.id = :ID AND video_comments.seen = 0 AND videos.uploaded_by = :USERNAME", false,
                    [
                        ":ID"       => $ID,
                        ":USERNAME" => $_USER->username
                    ]);

        if ($DB->RowNum == 1) {

            $DB->modify("UPDATE video_comments SET seen = 1 WHERE id = :ID", [":ID" => $ID]);

        }

    } elseif (strpos($_POST["id"],"mv_") !== false) {

        $ID = (int)str_replace("mv_","",$_POST["id"]);
        $DB->execute("SELECT username FROM mentions WHERE video = :ID AND username = :USERNAME AND seen = 0", false,
                    [
                        ":ID"       => $ID,
                        ":USERNAME" => $_USER->username
                    ]);

        if ($DB->RowNum == 1) {

            $DB->modify("UPDATE mentions SET seen = 1 WHERE video = :ID AND username = :USERNAME",
                       [
                           ":ID"        => $ID,
                           ":USERNAME"  => $_USER->username
                       ]);

        }

    } elseif (strpos($_POST["id"],"mcn_") !== false) {

        $ID = (int)str_replace("mcn_","",$_POST["id"]);
        $DB->execute("SELECT username FROM mentions WHERE channel = :ID AND username = :USERNAME AND seen = 0", false,
                    [
                        ":ID"       => $ID,
                        ":USERNAME" => $_USER->username
                    ]);

        if ($DB->RowNum == 1) {

            $DB->modify("UPDATE mentions SET seen = 1 WHERE channel = :ID AND username = :USERNAME",
                       [
                           ":ID"        => $ID,
                           ":USERNAME"  => $_USER->username
                       ]);

        }

    } elseif (strpos($_POST["id"],"rsp") !== false) {

        $ID = (int)str_replace("rsp_","",$_POST["id"]);
        $DB->execute("SELECT response_user FROM video_responses WHERE id = :ID AND response_user = :USERNAME AND seen = 0", false,
                    [
                        ":ID"       => $ID,
                        ":USERNAME" => $_USER->username
                    ]);

        if ($DB->RowNum == 1) {

            $DB->modify("UPDATE video_responses SET seen = 1 WHERE id = :ID", [":ID" => $ID]);

        }

    } elseif (strpos($_POST["id"],"mrp") !== false) {

        $ID = (int)str_replace("mrp_","",$_POST["id"]);
        $DB->execute("SELECT for_user FROM replies WHERE id = :ID AND for_user = :USERNAME AND seen = 0", false,
                    [
                        ":ID"       => $ID,
                        ":USERNAME" => $_USER->username
                    ]);

        if ($DB->RowNum == 1) {

            $DB->modify("UPDATE replies SET seen = 1 WHERE id = :ID", [":ID" => $ID]);

        }

    } elseif (strpos($_POST["id"],"zz") !== false) {

        $ID = (int)str_replace("zz_", "", $_POST["id"]);
        $DB->execute("SELECT on_channel FROM channel_comments WHERE id = :ID AND on_channel = :USERNAME AND seen = 0", false,
                    [
                        ":ID"       => $ID,
                        ":USERNAME" => $_USER->username
                    ]);

        if ($DB->RowNum == 1) {

            $DB->modify("UPDATE channel_comments SET seen = 1 WHERE id = :ID", [":ID" => $ID]);

        }

    } elseif (strpos($_POST["id"],"se") !== false) {

        $ID = (int)str_replace("se_", "", $_POST["id"]);
        $DB->execute("SELECT id FROM friends WHERE id = :ID AND by_user <> :USERNAME AND seen = 0 AND (friend_1 = :USERNAME OR friend_2 = :USERNAME)", false,
                    [
                        ":ID"       => $ID,
                        ":USERNAME" => $_USER->username
                    ]);

        if ($DB->RowNum == 1) {

            $DB->modify("UPDATE friends SET seen = 1 WHERE id = :ID", [":ID" => $ID]);

        }

    }
}