<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires Activation
//- Requires ($_GET["v"])
if (!$_USER->logged_in)         { redirect("/login"); exit();   }
if (!$_USER->Is_Activated)    { redirect("/"); exit();        }
if (!isset($_GET["v"]))         { redirect("/"); exit();        }


$URL = $_GET["v"];

$Uploaded_Videos = $DB->execute("SELECT title, url FROM videos WHERE uploaded_by = :USERNAME ORDER BY uploaded_on DESC", false, [":USERNAME" => $_USER->username]);

$Video = $DB->execute("SELECT url, title, responses, length, uploaded_by, responses, s_responses FROM videos WHERE url = :URL", true, [":URL" => $URL]);

if ($DB->RowNum > 0) {

    if ($Video["s_responses"] == 0) { redirect("/videos"); exit(); }

    if (isset($_POST["submit_response"])) {
        $Response_URL = $DB->execute("SELECT url FROM videos WHERE url = :URL AND uploaded_by = :USERNAME", true,
                                    [
                                        ":URL"      => $_POST["response"],
                                        ":USERNAME" => $_USER->username
                                    ]);

        if ($DB->RowNum == 1) {
            $Response_URL = $Response_URL["url"];
            $DB->execute("SELECT url FROM video_responses WHERE url_response = :RESPONSE AND url = :URL", false,
                        [
                            ":RESPONSE" => $Response_URL,
                            ":URL"      => $Video["url"]
                        ]);
            if ($DB->RowNum == 0) {
                if ($Video["s_responses"] == 1) {

                    $DB->modify("INSERT INTO video_responses (url, url_response, date,response_user) VALUES (:URL,:RESPONSE,NOW(),:USER)",
                        [
                            ":URL"       => $Video["url"],
                            ":RESPONSE"  => $Response_URL,
                            ":USER"      => $Video["uploaded_by"]
                        ]);
                    notification("Response was successfully submitted! Now it needs to get approved by the video owner!", "/watch?v=" . $Video["url"], "green"); exit();

                } else {

                    $DB->modify("INSERT INTO video_responses (url, url_response, date,response_user,seen,accepted) VALUES (:URL,:RESPONSE,NOW(),:USER,0,1)",
                        [
                            ":URL"       => $Video["url"],
                            ":RESPONSE"  => $Response_URL,
                            ":USER"      => $Video["uploaded_by"]
                        ]);

                    $DB->modify("UPDATE videos SET responses = responses + 1 WHERE url = :URL", [":URL" => $Video["url"]]);
                    notification("Response was successfully submitted!", "/watch?v=" . $Video["url"], "green"); exit();

                }

            } else {
                notification("You already submitted this video as a video response!", "/post_response?v=" . $Video["url"], "red"); exit();
            }
        }
    }
} else {
    redirect("/");
}


$_PAGE->set_variables(array(
    "Page_Title"        => "Submit Response - VidLii",
    "Page"              => "Response",
    "Page_Type"         => "Videos"
));
require_once "_templates/page_structure.php";