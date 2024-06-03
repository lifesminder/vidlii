<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

	if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod) && isset($_SESSION["admin_panel"])) {

		function user_avatar3($User,$Width,$Height,$Avatar,$Extra_Class = "") {
			if (strpos($Avatar,"u=") !== false) { $Avatar = str_replace("u=","",$Avatar); $Folder = "avt"; } else { $Upload = false; $Folder = "thmp"; }
			if (empty($Avatar) || !file_exists("../usfi/$Folder/$Avatar.jpg")) {
				$Avatar = "/img/no.png";
			} else {
				$Avatar = "/usfi/$Folder/$Avatar.jpg";
			}
			return '<a href="/user/'.$User.'"><img src="'.$Avatar.'" width="'.$Width.'" height="'.$Height.'" class="avt2 '.$Extra_Class.'" alt="'.$User.'"></a>';
		}


		$Countries = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];

		if (!isset($_GET["u"]) && !isset($_GET["d"])) {
			$New_Users = $DB->execute("SELECT username, email, reg_date, avatar, 1st_latest_ip, displayname FROM users ORDER BY reg_date DESC LIMIT 25");

			$Applications = $DB->execute("SELECT applications.username, applications.name, applications.birthday, applications.country, applications.what, applications.why, users.partner, users.displayname FROM applications INNER JOIN users ON applications.username = users.username ORDER BY applications.date DESC");

			if (isset($_GET["a"]) && ctype_alnum($_GET["a"])) {
			    $DB->execute("SELECT username FROM users WHERE username = :USERNAME", false, [":USERNAME" => $_GET["a"]]);
				if ($DB->RowNum == 1) {
					$DB->modify("UPDATE users SET partner = 1 WHERE username = :USERNAME AND partner = 0", [":USERNAME" => $_GET["a"]]);
					if ($DB->RowNum == 1) {

						$Message = "You just got accepted to the VidLii Partner Program.

									This means you're now able to do a lot of new things and do a lot of things you've done before even better!
									
									For example you're now able to upload a custom video thumbnail, have a little banner next to your videos and have higher quality videos in general.
									
									You're also able to connect your adsense account with VidLii.
									
									I hope you'll enjoy these new features.
									
									~ VidLii
									";

						$DB->modify("INSERT INTO private_messages (from_user,to_user,message,subject,date_sent) VALUES ('VidLii',:USERNAME,:MESSAGE,'Partnership',NOW())",
                                   [
                                       ":USERNAME"  => $_GET["a"],
                                       ":MESSAGE"   => $Message
                                   ]);

						notification("<strong>" . $_GET["a"] . "</strong> is now a VidLii Partner!", "/admin/users", "green"); exit();
					}
				}
			}
		} elseif (isset($_GET["d"])) {
            $DB->modify("DELETE FROM applications WHERE username = :USERNAME", [":USERNAME" => $_GET["n"]]);

            if ($DB->RowNum == 1) {
                if ($_GET["d"] == 1) {
                    $Message = "Unfortunately you did not get accepted for the VidLii Partner Program.

									The reason for that is: 'You have uploaded copyrighted content which does not count as fair use!'.
									
									That does not mean that you can't apply for partner ever again. But we recommend you to not do it now but instead to improve your content and apply again later.
									
									We wish you luck and a lot of fun with VidLii.
									
									~ VidLii (".$_USER->displayname.")
									";
                } elseif ($_GET["d"] == 2) {
                    $Message = "Unfortunately you did not get accepted for the VidLii Partner Program.

									The reason for that is: 'Not being active enough or not having been a member for long!'.
									
									That does not mean that you can't apply for partner ever again. But we recommend you to not do it now but instead to improve your content and apply again later.
									
									We wish you luck and a lot of fun with VidLii.
									
									~ VidLii (".$_USER->displayname.")
									";
                } elseif ($_GET["d"] == 3) {
                    $Message = "Unfortunately you did not get accepted for the VidLii Partner Program.

									The reason for that is: 'Being under the age of 14!'.
									
									That does not mean that you can't apply for partner ever again. But we recommend you to not do it now but instead to improve your content and apply again later.
									
									We wish you luck and a lot of fun with VidLii.
									
									~ VidLii (".$_USER->displayname.")
									";
                } else {
                    $Message = "Unfortunately you did not get accepted for the VidLii Partner Program.

									The reason for that is: 'Making low quality content! (Ask ".$_USER->displayname." for more info)'.
									
									That does not mean that you can't apply for partner ever again. But we recommend you to not do it now but instead to improve your content and apply again later.
									
									We wish you luck and a lot of fun with VidLii.
									
									~ VidLii (".$_USER->displayname.")
									";
                }
                $DB->modify("INSERT INTO private_messages (from_user,to_user,message,subject,date_sent) VALUES ('VidLii',:USERNAME,:MESSAGE,'Partnership denied',NOW())",
                           [
                               ":USERNAME"  => $_GET["n"],
                               ":MESSAGE"   => $Message
                           ]);

                notification("<strong>" . $_GET["n"] . "</strong>'s application has been denied!", "/admin/users", "green"); exit();
            }
        } elseif (ctype_alnum($_GET["u"])) {
            $Countries      = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];
            $User = $DB->execute("SELECT username FROM users WHERE username = :USER OR displayname = :USER LIMIT 1", true, [":USER" => $_GET["u"]]);
			if ($DB->RowNum > 0) {
				$Edit_User = new User($User["username"],$DB);
				$_GET["u"] = $User["username"];

                $Ban_Reasons           = $DB->execute("SELECT * FROM ban_reasons ORDER BY id ASC", false);

				if ($Edit_User->Is_Admin) {
					notification("You cannot edit admins!","/admin/users","red"); exit();
				}

				if (isset($_POST["delete_avatar"])) {
					$Avatar = $DB->execute("SELECT avatar FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_GET["u"]])["avatar"];

					$DB->modify("UPDATE users SET avatar = '' WHERE username = :USERNAME", [":USERNAME" => $_GET["u"]]);
					if ($DB->RowNum == 1) {
						unlink("../usfi/avt/".str_replace("u=","",$Avatar).".jpg");
						notification("Avatar successfully deleted!","/admin/users?u=".$_GET["u"],"green"); exit();
					} else {
						notification("This user doesn't have an avatar!","/admin/users?u=".$_GET["u"],"red"); exit();
					}
				}

				if (isset($_POST["delete_background"])) {
					$Background = @glob("../usfi/bg/".$_GET["u"].".*")[0];
					if ($Background !== NULL) {
						unlink($Background);
						notification("Background successfully deleted!","/admin/users?u=".$_GET["u"],"green"); exit();
					} else {
						notification("This user doesn't have a background!","/admin/users?u=".$_GET["u"],"red"); exit();
					}
				}
				
				if (isset($_POST["delete_videos"])) {
					$Videos = $DB->execute("SELECT url FROM videos WHERE uploaded_by = :USERNAME", false, [":USERNAME" => $_GET["u"]]);
					if ($DB->RowNum > 0) {
					    foreach ($Videos as $Video) {
                            $Video = new Video($Video["url"], $DB);
                            $Video->delete();
                        }
						
						notification("User videos successfully deleted!","/admin/users?u=".$_GET["u"],"green"); exit();
					} else {
						notification("This user doesn't have any videos!","/admin/users?u=".$_GET["u"],"red"); exit();
					}
				}
				
				if ((isset($_POST["strike_user"]) || isset($_POST["ban_user"])) && $_POST["strike_reason"] != "none") {
					$videos = "";
					$VidsArray = [];
					
					if (isset($_POST["strike_videos"]) && strlen($_POST["strike_videos"]) > 0) {
						preg_replace_callback("/http[s]?:\/\/(?:[(a-z)]+\.)?vidlii\.com\/.*?(?=[\&\?]v=([a-zA-Z0-9\_\-]{11}))/", function($matches) {
							global $DB;
							global $_GET;
							global $videos;
							global $VidsArray;
							
							for ($i=1; $i < count($matches); $i += 2) {
								$url = $matches[$i];
								if (!in_array($url, $VidsArray)) {

									$check = $DB->execute("SELECT title, description FROM videos WHERE url = :URL AND uploaded_by = :USER", true,
                                                         [
                                                             ":URL"     => $url,
                                                             ":USER"    => $_GET["u"]
                                                         ]);

									if ($DB->RowNum > 0) {
										$title = $check["title"];
										$description = $check["description"];
										$description = str_replace("\r\n", "\n", $description);
										$description = str_replace("\n", " ", $description);
										$description = str_replace("<br>", " ", $description);
										$thumb = "/usfi/thmp/$url.jpg";
										$VidsArray[] = $url;
										
										if (strlen($description) > 300) {
											$description = substr($description, 0, 300) . "...";
										}
										
										if ($description == "") {
											$description = "<i>No description.</i>";
										}
										
										if (count($VidsArray) < 3) {
											$videos .= '
												<div style="margin-bottom:20px;">
													<div style="float:left; margin-right: 5px;">
														<a href="/watch?v='.$url.'" title="'.$title.'"><img src="'.$thumb.'" alt="'.$title.'" style="width:120px; border:3px double"></a>
													</div>
													
													<div><a href="/watch?v='.$url.'" title="'.$title.'"><b>'.$title.'</b></a></div>
													<div>'.$description.'</div>
													<div style="clear:both"></div>
												</div>
											';
										}
										
										$DB->modify("UPDATE videos SET status = '-3' WHERE url = :URL AND uploaded_by = :USER",
                                                   [
                                                       ":URL"   => $url,
                                                       ":USER"  => $_GET["u"]
                                                   ]);

										$DB->modify("UPDATE users SET videos = videos - 1 WHERE username = :USER",
                                                   [
                                                       ":USER" => $_GET["u"]
                                                   ]);

										$DB->modify("DELETE FROM videos_flags WHERE url = :URL",
                                                   [
                                                       ":URL" => $url
                                                   ]);
									}
								}
							}
						}, $_POST["strike_videos"]);
						
						if (strlen($videos) > 0) {
							if (count($VidsArray) >= 3) {
								$videos .= "And other <b>".(count($VidsArray)-2)."</b> more.</b><br>";
							}
							
							$videos = "<b>Below is a list of some inappropriate videos you've uploaded:</b><br>" . $videos;
						} else {
							notification("The videos you've entered are invalid.","/admin/users?u=".$_GET["u"],"red");
						}
					}

                    // Start with note from POST
					$note = $_POST["strike_note"];

                    // Find ban reason matching the provided strike reason ID
                    $res_name = null;
                    $res_id = (int) $_POST["strike_reason"];
                    foreach ($Ban_Reasons as $ban_res) {
                        if($ban_res["id"] == $res_id) {
                            $res_name = $ban_res["reason"];
                            break;
                        }
                    }

                    // Check if reason was found for ID
					if($res_name === null) {
						notification("Invalid ban reason.","/admin/users?u=".$_GET["u"],"red");
					} else {
						$note = $res_name.". ".$note;
					}
					
					$subject = "Warning: You've received a strike!";
					$message = '
						This message has been auto-generated to inform you that your account has received a strike for violating our Community Guidelines. All strikes will be removed within 6 (six) months starting from the last strike received, so please use this as a chance to reflect on your actions on VidLii towards the site and the community as a whole. Below is a note from VidLii\'s administrators:
						
						"' .$note . '"
						
						' . $videos . '
						You can check where your account\'s current standing by following the link below:
						/manage_account
						
						-Att, the VidLii staff.
					';
					
					$message = str_replace("\r\n", "\n", $message);
					$message = str_replace("\t", "", $message);
					
					$DB->modify("INSERT INTO strikes SET username = :USER, issued_by = :ISSUER, issued_on = :TIME, video_links = :LINKS, comment = :COMMENT",
                               [
                                   ":USER"      => $_GET["u"],
                                   ":ISSUER"    => $_USER->username,
                                   ":TIME"      => time(),
                                   ":LINKS"     => implode(",", $VidsArray),
                                   ":COMMENT"   => $note
                               ]);

					if ($DB->RowNum == 0) {
						notification("Something went wrong, strike not inserted into table.","/admin/users?u=".$_GET["u"],"red"); exit();
					}
					
					$DB->modify("INSERT INTO private_messages SET to_user = :USER, from_user = :ISSUER, subject = :SUBJECT, message = :MESSAGE, date_sent = NOW()",
                               [
                                   ":USER"      => $_GET["u"],
                                   ":ISSUER"    => $_USER->username,
                                   ":SUBJECT"   => $subject,
                                   ":MESSAGE"   => $message
                               ]);

					if ($DB->RowNum == 0) {
						notification("Something went wrong, PM not sent to user.","/admin/users?u=".$_GET["u"],"red"); exit();
					}

					
					$ban_reason = "[$ban_reason]";
					$Check = $DB->execute("SELECT COUNT(*) as amount FROM users WHERE username = :USER AND ban_reasons LIKE :BAN", true, [":USER" => $_GET["u"], ":BAN" => "%$ban_reason%"])["amount"];
					if ($Check > 0) {
						$ban_reason = "";
					}
					
					$DB->modify("UPDATE users SET strikes = strikes + 1, ban_reasons = CONCAT(ban_reasons, :BAN) WHERE username = :USER LIMIT 1",
                               [
                                   ":USER"  => $_GET["u"],
                                   ":BAN"   => $ban_reason
                               ]);

					if ($DB->RowNum == 0) {
						notification("Something went wrong, user strikes not updated.","/admin/users?u=".$_GET["u"],"red"); exit();
					}

					$Strikes = $DB->execute("SELECT strikes FROM users WHERE username = :USER LIMIT 1", true, [":USER" => $_GET["u"]])["strikes"];

					if ($DB->RowNum == 0) {
						notification("Something went wrong, not able to check user strikes.","/admin/users?u=".$_GET["u"],"red"); exit();
					}

					if ($Strikes >= 3 || isset($_POST["ban_user"])) {
						$_POST["ban_user"] = "yes";
					} else {
						notification("Strike successfully sent.","/admin/users?u=".$_GET["u"],"green"); exit();
					}
				}

				if (isset($_POST["ban_user"])) {
					$Check = $DB->execute("SELECT banned FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_GET["u"]])["banned"];
					if ($Check == 0) {
						$DB->modify("UPDATE users SET banned = 1 WHERE username = :USERNAME", [":USERNAME" => $_GET["u"]]);
						$DB->modify("UPDATE videos SET banned_uploader = 1 WHERE uploaded_by = :USERNAME", [":USERNAME" => $_GET["u"]]);
						
						notification($_GET["u"]." has been banned!","/admin/users?u=".$_GET["u"],"green"); exit();
					} else {
						$DB->modify("UPDATE users SET banned = 0 WHERE username = :USERNAME", [":USERNAME" => $_GET["u"]]);
                        $DB->modify("UPDATE videos SET banned_uploader = 0 WHERE uploaded_by = :USERNAME", [":USERNAME" => $_GET["u"]]);

						notification($_GET["u"]." has been unbanned!","/admin/users?u=".$_GET["u"],"green"); exit();
					}
				}

				$Months = ['January' => 1, 'February' => 2, 'March' => 3, 'April' => 4, 'May' => 5, 'June' => 6, 'July ' => 7, 'August' => 8, 'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12];

				if (isset($_POST["save_user"])) {
					$_GUMP->validation_rules(array(
						"name"          => "max_len,64",
						"channel_title" => "max_len,80",
						"description" => "max_len,2600",
						"tags"        => "max_len,256",
						"website"       => "valid_url|max_len,128",
						"day"           => "is_day",
						"month"         => "is_month",
						"year"          => "is_year",
						"occupation"    => "max_len,128",
						"schools"       => "max_len,128",
						"interests"     => "max_len,128",
						"movies"        => "max_len,128",
						"music"         => "max_len,128",
						"books"         => "max_len,128",
						"about"         => "max_len,500",
						"channel_type"  => "required",
						"channel_version"   => "required",
						"activated"         => "required",
						"displayname"       => "required|max_len,20|alpha_numeric"
					));

					$_GUMP->filter_rules(array(
						"channel_version"   => "trim",
						"channel_type"  => "trim",
						"name"          => "trim|NoHTML",
						"website"       => "trim|NoHTML",
						"channel_title" => "trim|NoHTML",
						"description" => "trim|NoHTML",
						"tags"        => "trim|NoHTML",
						"day"           => "trim",
						"month"         => "trim",
						"year"          => "trim",
						"occupation"    => "trim|NoHTML",
						"schools"       => "trim|NoHTML",
						"interests"     => "trim|NoHTML",
						"movies"        => "trim|NoHTML",
						"music"         => "trim|NoHTML",
						"books"         => "trim|NoHTML",
						"about"         => "trim|NoHTML",
						"activated"     => "trim",
						"displayname"   => "trim"
					));

					$Validation = $_GUMP->run($_POST);

					if ($Validation) {
						$Day        = (int)$Validation["day"];
						$Month      = (int)$Validation["month"];
						$Year       = (int)$Validation["year"];
						$Birthday   = "$Year-$Month-$Day";

						if ($Validation["channel_type"] >= 0 && $Validation["channel_type"] <= 7) { $Channel_Type = $Validation["channel_type"]; } else { $Channel_Type = 0; }
						if ($Validation["channel_version"] == 1 || $Validation["channel_version"] == 2 || $Validation["channel_version"] == 3) { $Channel_Version = $Validation["channel_version"]; } else { $Channel_Version = 1; }
						if ($Validation["activated"] == 1 || $Validation["activated"] == 2) { $Activated = $Validation["activated"]; } else { $Activated = 0; }
                        if (array_key_exists($Validation["country"],$Countries)) { $Country = $Validation["country"]; } else { $Country = $User_Info["country"]; }

						if (get_age($Birthday) >= 13) {

							if (strcasecmp($Validation["displayname"],$User["username"]) !== 0) {
								$Display_Name = $Validation["displayname"];

								$Exists1 = $DB->execute("SELECT displayname FROM users WHERE displayname = :DISPLAYNAME", true, [":DISPLAYNAME" =>  $Display_Name]);
                                $Exists2 = $DB->execute("SELECT displayname FROM users_oldnames WHERE displayname = :DISPLAYNAME", true, [":DISPLAYNAME" =>  $Display_Name]);

								if (!$Exists1 && !$Exists2) {
									$Edit_User->get_profile();

									$DB->modify("INSERT INTO users_oldnames SET displayname = :DNAME, username = :UNAME",
                                               [
                                                   ":DNAME" => $Edit_User->Info["displayname"],
                                                   ":UNAME" => $Edit_User->Info["username"]
                                               ]);

									$DB->modify("UPDATE users SET displayname = :DNAME WHERE username = :UNAME LIMIT 1",
                                               [
                                                   ":DNAME" => $Display_Name,
                                                   ":UNAME" => $Edit_User->Info["username"]
                                               ]);
								}
							}

							if (!isset($_POST["partnered"]) || (isset($_POST["partnered"]) && $_POST["partnered"] == 0)) {
							    $Partnered = 0;
                            } else {
							    $Partnered = 1;
                            }


							$DB->modify("UPDATE users SET partner = :PARTNER, country = :COUNTRY, activated = :ACTIVATED, channel_version = :CHANNEL_VERSION, channel_type = :CHANNEL_TYPE, i_name = :NAME, channel_title = :CHANNEL_TITLE, channel_description = :CHANNEL_DESCRIPTION, channel_tags = :CHANNEL_TAGS, about = :ABOUT, website = :WEBSITE, birthday = :BIRTHDAY, i_occupation = :OCCUPATION, i_schools = :SCHOOLS, i_interests = :INTERESTS, i_movies = :MOVIES, i_music = :MUSIC, i_books = :BOOKS WHERE username = :USERNAME",
                                       [":PARTNER" => $Partnered, ":COUNTRY" => $Country, ":ACTIVATED" => $Activated, ":CHANNEL_VERSION" => $Channel_Version, ":CHANNEL_TYPE" => $Channel_Type, ":NAME" => $Validation["name"], ":CHANNEL_TITLE" => $Validation["channel_title"], ":CHANNEL_DESCRIPTION" => $Validation["description"], ":CHANNEL_TAGS" => $Validation["tags"], ":ABOUT" => $Validation["about"], ":WEBSITE" => $Validation["website"], ":BIRTHDAY" => $Birthday, ":USERNAME" => $_GET["u"], ":OCCUPATION" => $Validation["occupation"], ":SCHOOLS" => $Validation["schools"], ":INTERESTS" => $Validation["interests"], ":MOVIES" => $Validation["movies"], ":MUSIC" => $Validation["music"], ":BOOKS" => $Validation["books"]]);
							notification("User successfully updated!", "/admin/users?u=".$_GET["u"], "green"); exit();
						} else {
							notification("The user must be at least 13 years old!","/admin/users?u=".$_GET["u"],"red"); exit();
						}
					}
				}

				$User_Info = $DB->execute("SELECT * FROM users WHERE username = :USERNAME", true, [":USERNAME" => $_GET["u"]]);

				if (isset($_POST["track_alts"]) || isset($_POST["ban_all_alts"])) {
					$IP1 = $User_Info["1st_latest_ip"];
					$IP2 = $User_Info["2nd_latest_ip"];
					
					$Alts = $DB->execute("SELECT username, displayname, email, 1st_latest_ip, 2nd_latest_ip, banned FROM users WHERE 1st_latest_ip = '$IP1' OR 2nd_latest_ip = '$IP1' OR 1st_latest_ip = '$IP2' OR 2nd_latest_ip = '$IP2'");
					if ($DB->RowNum == 0) {
                        $Alts = false;
					}

				}
				
				if (isset($_POST["ban_all_alts"]) && $Alts !== false) {
					foreach($Alts as $a) {
						if (!in_array($a["username"],$_USER::mods) && !in_array($a["username"],$_USER::admins)) {
							$DB->modify("UPDATE users SET banned = 1 WHERE username = :USER", [":USER" => $a["username"]]);
                            $DB->modify("UPDATE videos SET banned_uploader = 1 WHERE uploaded_by = :USERNAME", [":USERNAME" => $a["username"]]);
                        }
					}
					
					notification($_GET["u"]." and all of their alts have been banned!","/admin/users?u=".$_GET["u"],"green");
				}

                if (isset($_POST["delete_comments"])) {
                    $username = $_GET["u"];
                    $DB->modify("DELETE FROM channel_comments WHERE by_user = :USER", [":USER" => $username]);
                    $DB->modify("DELETE FROM video_comments WHERE by_user = :USER", [":USER" => $username]);

                    notification("Deleted all of ".$username."'s comments","/admin/users?u=".$_GET["u"],"green");
                }

                if (isset($_POST["delete_subs"])) {
                    $username = $_GET["u"];
                    $DB->modify("UPDATE users SET subscribers = 0 WHERE username = :USER", [":USER" => $username]);
                    $DB->modify("DELETE FROM subscriptions WHERE subscription = :USER", [":USER" => $username]);

                    notification("Deleted all of ".$username."'s subscribers","/admin/users?u=".$_GET["u"],"green");
                }

				$Birthday = $User_Info["birthday"];
				$Birth_Year = date("Y",strtotime($Birthday));
				$Birth_Month = ltrim(date("m",strtotime($Birthday)),0);
				$Birth_Day = ltrim(date("d",strtotime($Birthday)),0);
			} else {
				notification("This user doesn't exist!","/admin/users","red");
			}
		} else {
			redirect("/admin/users");
		}


		$Page_Title = "Users";
		$Page = "Users";
		require_once "_templates/admin_structure.php";
	} elseif ($_USER->Is_Mod || $_USER->Is_Admin) {
		redirect("/admin/login"); die();
	} else {
		redirect("/");
	}
