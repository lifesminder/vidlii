<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
{    
  exit;    
}
session_start();

// If referer is not from VidLii, reject
$origin = $_SERVER['HTTP_REFERER'];
if (strpos($origin, "") !== 0) {
	if (strpos($origin, "https://vidlii.com") !== 0) {
		die("error");
	}	
}

// Get video ID
$vid_id = preg_replace_callback("/http[s]?:\/\/(?:[(a-z)]+\.)?vidlii\.com\/.*(?=[\&\?]v=([a-zA-Z0-9\_\-]{11})).*$/", function($matches) {
	return $matches[1];
}, $_POST["u"]);

if ($vid_id != $_POST["u"] && strlen($vid_id) == 11) {
	if ($_POST["a"] == 1) { // Set Watch Token		
		if (!isset($_SESSION["watch_tokens"])) {
			$_SESSION["watch_tokens"] = [];
		}
		
		$token = time();
		$_SESSION["watch_tokens"][$vid_id] = $token;
		
		die("1." . $token); // Success
	} elseif ($_POST["a"] == 2) { // Use Watch Token
		$token = (int)$_POST["t"];
		if (isset($_SESSION["watch_tokens"][$vid_id])) {
			if ($_SESSION["watch_tokens"][$vid_id] == $token) {
				// Token exists, check if time watched is valid
				if (!isset($_POST["v"]) || !is_array($_POST["v"]) || count($_POST["v"]) == 0) {
					die("error");
				}
				
				// Check if video exists
				require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";
				$time_watched = time() - $token;
				
				$_VIDEO = new Video($vid_id, $DB);
				if ($_VIDEO->exists() !== false) {
					$_VIDEO->get_info();
					$Length = $_VIDEO->Info["length"];
					$Watched = count($_POST["v"]);
					
					// Check if time watched is valid
					while(count($_POST["v"]) > 0) {
						$second = array_shift($_POST["v"]);
						if ((int)$second != $second) die("error");
						if (in_array($second, $_POST["v"])) die("error");
						if ($second < 0 || $second > $Length) die("error");
					}

					if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"])) {

					    $Source = $_SERVER["HTTP_REFERER"];

                        if (mb_strpos($Source, "/community") !== false) {

                            $Source = "c";

                        } elseif (mb_strpos($Source, "/user/") !== false) {

                            $Source = str_replace("/user/", "", $Source);

                            $Username = $DB->execute("SELECT username FROM users WHERE displayname = :SOURCE", true, [":SOURCE" => $Source]);

                            if ($DB->RowNum == 0) { $Source = ""; }
                            else                  { $Source = "?".$Username["username"]; }

                        } elseif ($Source == "/") {

                            $Source = "h";

                        } else {

                            $Source = "";

                        }

                    } else {

					    $Source = "";

                    }
					
					// If video is shorter than a minute, user needs to watch 60% of it
					if ($Length <= 60 && $Watched >= $Length * 0.60 && $time_watched >= $Length * 0.60) {
						$_VIDEO->view($_USER, 0, $Source);
						$_USER->Viewed_Videos[] = $_VIDEO->URL;
						die("1"); // Success
					}
					
					// If video is longer than a minute, user needs to watch 32 seconds or more of it
					if ($Length > 60 && $Watched >= 32 && $time_watched >= 32) {
						$_VIDEO->view($_USER, 0, $Source);
                        $_USER->Viewed_Videos[] = $_VIDEO->URL;
						die("1"); // Success
					}
					
					die("error");
				}
			}
		}
	}
}

die("error: $vid_id - $_POST[u]");