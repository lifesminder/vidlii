<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod) && isset($_SESSION["admin_panel"])) {

		$Wrong_Logins = $DB->execute("SELECT wrong_logins.*, (SELECT displayname FROM users WHERE wrong_logins.ip = users.1st_latest_ip ORDER BY subscribers DESC LIMIT 1) as displayname FROM wrong_logins WHERE wrong_logins.channel = :CHANNEL ORDER BY wrong_logins.submit_date DESC", false, [":CHANNEL" => $_GET["channel"]]);
		
		foreach ($Wrong_Logins as $Login) {
			
			if ($Login["displayname"] != "VidLii" && $Login["displayname"] != "Jan") {

				if (isset($Login["displayname"])) {

					echo $Login["displayname"]. " - ";

				} else {

					if ($_USER->Is_Admin) {

						echo $Login["ip"]. " - ";

					} else {

						echo "Hidden - ";

					}
					

				}

				echo $Login["channel"] . " - ". $Login["submit_date"]."<br>";

			}
			
		}
		
    } elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
        redirect("/admin/login"); die();
    } else {
        redirect("/");
    }