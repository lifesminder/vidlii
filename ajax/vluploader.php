<?php
	require_once "../_includes/init.php";
	$err = "An error has occurred.";
	$result = 0;

	// Parameters
	$uploadLimit = (int)$_ENV["upload_limit"] ?? 1073741824;

	if ($_USER->logged_in) {
		switch($_POST["action"]) {
			case "check":
				//Sanitize fields
				$name = isset($_POST["filename"]) ? $_POST["filename"] : "";
				$type = isset($_POST["type"]) && $_POST["type"] == 1 ? 1 : 0;
				$url = isset($_POST["url"]) ? $_POST["url"] : "";
				$url = preg_replace("/[^a-zA-Z0-9\-\_]+/", "", $url);
				$thumb = "/img/no_th.jpg";
				
				//Check if user is uploading too many videos a day
				$check = $DB->execute("SELECT COUNT(*) as amount FROM videos WHERE uploaded_by = :USER AND uploaded_on > SUBDATE(NOW(),1)", true, [":USER" => $_USER->username])["amount"];
				if (($_USER->Is_Partner && $check >= 10) || (!$_USER->Is_Partner && $check >= 8)) {
					$err = "You've uploaded too many videos today, come back tomorrow!";
					$result = -1;
					break;
				}
				
				//Check partnership status if changing files
				if ($type == 1 && !$_USER->Is_Partner) {
					$err = "You're not a partner!";
					$result = -1;
					break;
				}

				//Check file format support
				$invalidFile = true;
				$extension = strrpos($name, ".");
				if ($extension !== false) {
					$extension = substr($name, $extension+1);
					$extension = strtoupper($extension);
					if (in_array($extension, ALLOWED_FORMATS)) {
						$invalidFile = false;
					}
				}
				
				//Throw error if file extension is invalid
				if ($invalidFile) {
					$err = "Invalid file format! Upload aborted!";
					$result = -1;
					break;
				}
				
				//Check filesize
				if ($_POST["filesize"] > $uploadLimit) {
					$err = "The file you're trying to upload is too large!";
					$result = -1;
					break;
				}
				
				//Check upload existence
				if ($type == 0) {
					//When uploading new videos
					$check = $DB->execute("SELECT url FROM uploads WHERE filesize = :SIZE AND filetype = :FILETYPE AND modified = :MODIFIED AND type = :TYPE AND user = :USER LIMIT 1", true,
                                         [
                                             ":SIZE"     => $_POST["filesize"],
                                             ":FILETYPE" => $_POST["filetype"],
                                             ":MODIFIED" => $_POST["modified"],
                                             ":TYPE"     => $type,
                                             ":USER"     => $_USER->username
                                         ]);

					//If upload exists, resume
					if ($DB->RowNum > 0) {
						$url    = $check["url"];
						$size   = (int)@filesize("../usfi/conv_2/$url.file");
						$result = 2;
						
						if (file_exists("../usfi/thmp/$url.jpg")) {
							$thumb = "/usfi/thmp/$url.jpg";
						}
					}
				} else {
					//When changing videos
					$check = $DB->execute("SELECT filesize, filetype, modified FROM uploads WHERE url = :URL AND type = :TYPE AND user = :USER LIMIT 1", true,
                                         [
                                             ":URL"     => $url,
                                             ":TYPE"    => $type,
                                             ":USER"    => $_USER->username
                                         ]);

					//If upload exists, check file match
					if ($DB->RowNum > 0) {
						if ($check["filesize"] == $_POST["filesize"] && $check["filetype"] == $_POST["filetype"] && $check["modified"] == $_POST["modified"]) {
							//If file matches, resume
							$size = (int)@filesize("../usfi/conv_2/$url.file");
							$result = 2;
						} else {
							//If not, delete previous files
							$DB->modify("DELETE FROM uploads WHERE url = :URL LIMIT 1", [":URL" => $url]);
							@unlink("../usfi/conv_2/$url.file");
						}
					}
				}

				//If upload doesn't exist, insert into table
				if ($result != 2) {
					if ($type == 0) {
						//Create video URL
						while(true) {
							$url = random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_", 11);
							$check = $DB->execute("SELECT COUNT(*) as amount FROM videos WHERE url='$url'", true)["amount"];
							if ($check == 0) break;
						}
						
						//Create video title
						$title = substr($name, 0, strrpos($name, "."));
						$title = strlen($title) > 3 ? $title : "Untitled";
						
						//Create video row in the table
						$DB->modify("INSERT INTO videos (url,file,hd,title,uploaded_by,uploaded_on,status,shadowbanned_uploader) VALUES (:URL,:FILE,:HD,:TITLE,:UPLOADED_BY,NOW(),-1,'$_USER->Shadowbanned')",
                                    [
                                        ":URL"          => $url,
                                        ":FILE"         => "",
                                        ":HD"           => 0,
                                        ":TITLE"        => $title,
                                        ":UPLOADED_BY"  => $_USER->username
                                    ]);
						
						//If insert operation throws error
						if ($DB->RowNum == 0) {
							$err = "Fail to insert data into our database!";
							$result = -1;
							break;
						}
						
						//Add video to user videos count
						$DB->modify("UPDATE users SET videos = videos + 1 WHERE username = :USERNAME", [":USERNAME" => $_USER->username]);
					} else {
						//Get video URL
						$check = $DB->execute("SELECT COUNT(*) as amount FROM videos WHERE url = :URL AND status IN (-2,2)", true, [":URL" => $url])["amount"];
						
						//If video doesn't exist, throw error
						if ($check == 0) {
							$err = "The video you're trying to change either doesn't exist or hasn't been converted yet!";
							$result = -1;
							break;
						}
						
						//Check if there's already a video converting
						$check = $DB->execute("SELECT COUNT(*) as amount FROM converting WHERE url = :URL", true, [":URL" => $url])["amount"];
						
						//If there's already a video replacement being converted, abort
						if ($check > 0) {
							$err = "There's already a video in conversion! Upload aborted!";
							$result = -1;
							break;
						}
					}
					
					$DB->modify("INSERT INTO uploads SET url = :URL, type = :TYPE, user = :USER, filetype = :FILETYPE, filesize = :SIZE, modified = :MODIFIED, token = ''",
                               [
                                   ":URL"       => $url,
                                   ":TYPE"      => $type,
                                   ":USER"      => $_USER->username,
                                   ":FILETYPE"  => $_POST["filetype"],
                                   ":SIZE"      => $_POST["filesize"],
                                   ":MODIFIED"  => $_POST["modified"]
                               ]);

					//If insert operation throws error
					if ($DB->RowNum == 0) {
						$err = "Fail to insert data into our database!";
						$result = -1;
						break;
					}
					
					//Return result to the uploader
					$result = 1;
					$size = 0;
				}
				
				//Update table
				$token = random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_", 11);
				$DB->modify("UPDATE uploads SET token='$token' WHERE url='$url'");
				
				//Deliver result to the user
				echo json_encode(["result" => $result, "url" => $url, "uploaded" => $size, "token" => $token, "thumb" => $thumb]);
				exit;
			case "upload":
				//Sanitize URL
				$url = preg_replace("/[^a-zA-Z0-9\-\_]+/", "", $_POST["url"]);
				
				//Check upload existence
				$path = "../usfi/conv_2/$url.file";
				$check = $DB->execute("SELECT filesize FROM uploads WHERE url = :URL AND token = :TOKEN AND user = :USER LIMIT 1", true,
                                     [
                                         ":URL"     => $_POST["url"],
                                         ":TOKEN"   => $_POST["token"],
                                         ":USER"    => $_USER->username
                                     ]);

				//If upload does not exist, throw error
				if ($DB->RowNum == 0) {
					@unlink($path);
					$err = "File not found in database! Upload aborted!";
					$result = -1;
					break;
				} else {
					$size = $check["filesize"];
				}

				//Check if chunk data exists
				if (isset($_POST["data"])) {
					$data = $_POST["data"];
				} else {
					$err = "Upload data is missing. Retrying...";
					break;
				}
				
				//Decode base64 before writing to file
				$data = substr($data, strpos($data, ","));
				$data = str_replace(" ", "+", $data);
				$data = base64_decode($data);
				
				//Check if chunk data is valid
				$chunkSize = strlen($data);
				if ($chunkSize < 1 || $chunkSize > 1024 * 1024 || $chunkSize != $_POST["filesize"]) {
					$err = "Upload data is corrupted. Retrying...";
					break;
				}
				
				//If nothing's wrong, write chunk to file
				$fh = fopen($path, "a");
				fwrite($fh, $data);
				fclose($fh);
				$result = 1;
				
				//Check file size and delete if too big
				if (filesize($path) > $uploadLimit) {
					$DB->modify("DELETE FROM uploads WHERE url = :URL AND user = :USER LIMIT 1",
                               [
                                   ":URL"   => $_POST["url"],
                                   ":USER"  => $_USER->username
                               ]);
					unlink($path);
					
					$err = "The file you're trying to upload is too large!";
					$result = -1;
					break;
				}
				
				//Check if upload is complete and move file if it is
				if (filesize($path) >= $size) {
					rename($path, "../usfi/conv/$url.file");
					$DB->modify("DELETE FROM uploads WHERE url = :URL AND user = :USER LIMIT 1",
                               [
                                   ":URL"   => $_POST["url"],
                                   ":USER"  => $_USER->username
                               ]);

					$DB->modify("UPDATE videos SET status='0' WHERE url = :URL AND status='-1' AND uploaded_by = :USER",
                               [
                                   ":URL"   => $_POST["url"],
                                   ":USER"  => $_USER->username
                               ]);

					$queue = $DB->execute("SELECT COUNT(*) as amount FROM converting", true)["amount"];
					$DB->modify("INSERT INTO converting SET url = :URL, uploaded_on = NOW(), convert_status = 0, queue = :QUEUE",
                               [
                                   ":URL"   => $_POST["url"],
                                   ":QUEUE" => $queue
                               ]);
					$result = 2;
				}
				
				echo json_encode(["result" => $result]);
				exit;
		}
	} else {
		$err = "Please, log in in order to upload!";
		$result = -1;
	}
	
	echo json_encode(["error" => $err, "result" => $result]);
