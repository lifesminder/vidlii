<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_libs/kaptcha_client.php";

function user_ip() {
	return  getenv('HTTP_CLIENT_IP')?:
			getenv('HTTP_X_FORWARDED_FOR')?:
			getenv('HTTP_X_FORWARDED')?:
			getenv('HTTP_FORWARDED_FOR')?:
			getenv('HTTP_FORWARDED')?:
			getenv('REMOTE_ADDR');
}

function in_array_r($needle, $haystack, $strict = false) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			return true;
		}
	}

	return false;
}

function showBBcodes($text) {
	$find = array(
		'~\[b\](.*?)\[/b\]~s',
		'~\[i\](.*?)\[/i\]~s',
		'~\[u\](.*?)\[/u\]~s'
	);
	$replace = array(
		'<b>$1</b>',
		'<i>$1</i>',
		'<t style="text-decoration:underline;">$1</t>'
	);
	return preg_replace($find,$replace,$text);
}

function hashtag_search($text) {
	return preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="/results?q=$1">#$1</a>', $text);
}

function process_clickable_length($str) {
	$ex = array_reverse(explode(":", $str[1]));
	$sec = 0;
	
	for($i = 0; $i < count($ex); $i++) {
		$sec += (int)$ex[$i] * pow(60, $i);
	}
	
	return "<a href=\"#t=$sec\" onclick=\"$(window).trigger('hashchange')\">$str[1]</a>";
}

function mention($text) {
	$text = preg_replace_callback('/\b((\d+:){1,2}+\d+)\b/', 'process_clickable_length', $text);
	return preg_replace('/(?<!\S)@([0-9a-zA-Z]+)/', '<a href="/user/$1">@$1</a>', $text);
}

function notification($Message,$Redirect,$Color = "red") {
	$_SESSION["notification"] = $Message;
	$_SESSION["n_color"] = $Color;

	if ($Redirect != false) {
        redirect($Redirect);
    }
}

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

function isTorRequest() {
	if (isset($_SERVER["HTTP_CF_IPCOUNTRY"]) && $_SERVER["HTTP_CF_IPCOUNTRY"] == "T1") {
		
		return true;
		
	} else {
		
		return false;
		
	}
}

function DoLinks($text){

	return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);

}

function sql_IN_fix($Array,$key = NULL) {
	$New_Array = "";
	if (!isset($key)) {
		foreach ($Array as $Value) {
			$New_Array .= "'" . $Value . "'" . ',';
		}
	} else {
		foreach ($Array as $Key => $Value) {
			$New_Array .= "'" . $Key . "'" . ',';
		}
	}
	return substr($New_Array,0,strlen($New_Array) - 1);
}

function url_parameter($URL,$Parameter) {
	parse_str(parse_url($URL)['query'], $Query);
	return $Query[$Parameter];
}

function get_time_ago($time) {
	$time = time() - strtotime($time);
	$time = ($time < 1)? 1 : $time;
	$tokens = array (
		31536000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);

	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
	}
	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
	}
	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
	}
}

function subscribe_button($User,$Is_Subscribed = false,$Blocked = false) {
	global $_USER;

	if ($_USER->logged_in) {
		if ($_USER->username !== $User && !$Blocked) {
			if ($Is_Subscribed) {
				return '<a href="/ajax/df/subscribe?u='.$User.'" class="sub is"><img src="/img/sub_check.png">Subscribed</a>';
			} else {
				return '<a href="/ajax/df/subscribe?u='.$User.'" class="sub"><img src="/img/sub_check.png">Subscribe</a>';
			}
		} else {
			return '<a href="javascript:void(0)" onclick="alert(\'You cannot subscribe to yourself!\')" class="sub"><img src="/img/sub_check.png">Subscribe</a>';
		}
	} elseif (!$Blocked) {
		return '<a href="javascript:void(0)" onclick="alert(\'You must be logged in to subscribe!\')" class="sub"><img src="/img/sub_check.png">Subscribe</a>';
	} else {
		return '<a href="javascript:void(0)" onclick="alert(\'You cannot interact with this user!\')" class="sub"><img src="/img/sub_check.png">Subscribe</a>';
	}
}

function convert_filesize($Bytes,$Format) {
	switch(mb_strtolower($Format)) {
		case "kb" :
			return $Bytes / 1024;
			break;
		case "mb" :
			return $Bytes / 1048576;
			break;
		case "gb" :
			return $Bytes / 1073741824;
			break;
	}
}

function browser_name() {
    $AGENT = $_SERVER['HTTP_USER_AGENT'];

    if (mb_strpos($AGENT,"Chrome") !== false && mb_strpos($AGENT,"OPR") === false) {
        return "Chrome";
    } elseif (mb_strpos($AGENT,"Chrome") !== false && mb_strpos($AGENT, "OPR") !== false) {
        return "Opera";
    } elseif (mb_strpos($AGENT,"Firefox") !== false) {
        return "Firefox";
    } elseif (mb_strpos($AGENT,"Trident") !== false) {
        return "IE";
    } elseif (mb_strpos($AGENT,"Edge") !== false) {
        return "Edge";
    } elseif (mb_strpos($AGENT,"Safari") !== false) {
        return "Safari";
    }
    return "Unknown";
}

function old_show_ratings($Ratings,$width,$height) {
	if (is_array($Ratings)) {
		$Star_1 = $Ratings["1_star"];
		$Star_2 = $Ratings["2_star"];
		$Star_3 = $Ratings["3_star"];
		$Star_4 = $Ratings["4_star"];
		$Star_5 = $Ratings["5_star"];

		$Rating_Num = $Star_1 + $Star_2 + $Star_3 + $Star_4 + $Star_5;

		if ($Rating_Num > 0) {
			$Rating = ($Star_1 + $Star_2 * 2 + $Star_3 * 3 + $Star_4 * 4 + $Star_5 * 5) / $Rating_Num;
		} else {
			$Rating = 0;
		}
	} else {
		$Rating = $Ratings;
	}

	$Full_Stars = substr($Rating, 0, 1);
	$Half_Stars = substr($Rating, 2, 1);

	$StarNum    = 0;
	for($x = 0;$x < $Full_Stars;$x++) {
		$StarNum++;
		echo "<img src='/img/full_star.png' width='$width' height='$height'> ";
	}
	if ($Half_Stars !== false) {
		$StarNum++;
		if ($Full_Stars !== "4") {
			echo "<img src='/img/half_star.png' width='$width' height='$height'> ";
		} else {
			if ($Half_Stars == "8" or $Half_Stars == "9") {
				echo "<img src='/img/full_star.png' width='$width' height='$height'> ";
			} else {
				echo "<img src='/img/full_star.png' width='$width' height='$height'> ";
			}
		}
	}
	while($StarNum !== 5) {
		$StarNum++;
		echo "<img src='/img/no_star.png' width='$width' height='$height'> ";
	}
}

function show_ratings($Ratings,$width,$height) {
	if (is_array($Ratings)) {
		$Star_1 = $Ratings["1_star"] / 5;
		$Star_2 = $Ratings["2_star"] / 4;
		$Star_3 = $Ratings["3_star"] / 3;
		$Star_4 = $Ratings["4_star"] / 2;
		$Star_5 = $Ratings["5_star"] / 1;
	

		$Rating_Num = $Star_1 + $Star_2 + $Star_3 + $Star_4 + $Star_5;

		if ($Rating_Num > 0) {
			$Rating = ($Star_1 + $Star_2 * 2 + $Star_3 * 3 + $Star_4 * 4 + $Star_5 * 5) / $Rating_Num;
		} else {
			$Rating = 0;
		}
	} else {
		$Rating = $Ratings;
	}

	$Full_Stars = substr($Rating, 0, 1);
	$Half_Stars = substr($Rating, 2, 1);

	$StarNum    = 0;
	for($x = 0;$x < $Full_Stars;$x++) {
		$StarNum++;
		echo "<img src='/img/full_star.png' width='$width' height='$height'>";
	}
	if ($Half_Stars !== false) {
		$StarNum++;
		if ($Full_Stars !== "4") {
			echo "<img src='/img/half_star.png' width='$width' height='$height'>";
		} else {
			if ($Half_Stars == "8" or $Half_Stars == "9") {
				echo "<img src='/img/full_star.png' width='$width' height='$height'>";
			} else {
				echo "<img src='/img/full_star.png' width='$width' height='$height'>";
			}
		}
	}
	while($StarNum !== 5) {
		$StarNum++;
		echo "<img src='/img/no_star.png' width='$width' height='$height'>";
	}
}

function return_ratings($Ratings,$width,$height) {
	$Return = "";
	if (is_array($Ratings)) {
		$Star_1 = $Ratings["1_star"];
		$Star_2 = $Ratings["2_star"];
		$Star_3 = $Ratings["3_star"];
		$Star_4 = $Ratings["4_star"];
		$Star_5 = $Ratings["5_star"];

		$Rating_Num = $Star_1 + $Star_2 + $Star_3 + $Star_4 + $Star_5;

		if ($Rating_Num > 0) {
			$Rating = ($Star_1 + $Star_2 * 2 + $Star_3 * 3 + $Star_4 * 4 + $Star_5 * 5) / $Rating_Num;
		} else {
			$Rating = 0;
		}
	} else {
		$Rating = $Ratings;
	}

	$Full_Stars = substr($Rating, 0, 1);
	$Half_Stars = substr($Rating, 2, 1);

	$StarNum    = 0;
	for($x = 0;$x < $Full_Stars;$x++) {
		$StarNum++;
		$Return .= "<img src='/img/full_star.png' width='$width' height='$height'>";
	}
	if ($Half_Stars !== false) {
		$StarNum++;
		if ($Full_Stars !== "4") {
			$Return .= "<img src='/img/half_star.png' width='$width' height='$height'>";
		} else {
			if ($Half_Stars == "8" or $Half_Stars == "9") {
				$Return .= "<img src='/img/full_star.png' width='$width' height='$height'>";
			} else {
				$Return .= "<img src='/img/full_star.png' width='$width' height='$height'>";
			}
		}
	}
	while($StarNum !== 5) {
		$StarNum++;
		$Return .=  "<img src='/img/no_star.png' width='$width' height='$height'>";
	}
	return $Return;
}

include "_libs/Other.php";

function get_time($time) {
	if (!is_numeric($time)) {
		return date("h:i:s A", strtotime($time));
	} else {
		return date("h:i:s A", $time);
	}
}

function random_string($Characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", $Length) {
	$charactersLength = mb_strlen($Characters);
	$randomString = '';
	for ($i = 0; $i < $Length; $i++) {
		$randomString .= $Characters[mt_rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function redirect($url = "") {
	if ($url == "") return;
	
	if(!headers_sent()) {
		header('Location: '.$url);
		exit;
	} else {
		echo "<script>window.location.href='$url'</script>";
		exit;
	}
}

function get_date($date) {
	if (!is_numeric($date)) {
		return date("M d, Y", strtotime($date));
	} else {
		return date("M d, Y", $date);
	}
}

function email_domain($Email) {
	return substr(strrchr($Email, "@"), 1);
}

	function video_thumbnail($URL, $LENGTH, $Width, $Height, $Title = null) {
		$Length = (!empty($LENGTH) || $LENGTH == "0") ? seconds_to_time((int)$LENGTH) : $LENGTH;
		$Thumbnail = "/vi/$URL/default.jpg";

		return '<div class="th">
					<div class="th_t">'.$Length.'</div>
					<a href="/watch?v='.$URL.'">
						<img class="vid_th" loading="lazy" src="'.$Thumbnail.'" width="'.$Width.'" height="'.$Height.'">
					</a>
				</div>';
	}

	function user_avatar($User,$Width,$Height,$Avatar,$Border = "") {
		$Avatar = "/vi/ava/$User.jpg";
		return '<a href="/user/'.$User.'"><img src="'.$Avatar.'" width="'.$Width.'" loading="lazy" height="'.$Height.'" class="avt '.$Border.'" alt="'.$User.'"></a>';
	}

	function user_avatar2($User,$Width,$Height,$Avatar,$Extra_Class = "") {
		$Avatar = "/vi/ava/$User.jpg";
		return '<a href="/user/'.$User.'"><img src="'.$Avatar.'" width="'.$Width.'" height="'.$Height.'" class="avt2 '.$Extra_Class.'" alt="'.$User.'"></a>';
	}

	function get_age($Date) {
		$today = new DateTime();
  		$diff = $today->diff(new DateTime($Date));
		return $diff->y;
	}

	function time_ago($time) {
		$time = time() - strtotime($time);
		$time = ($time < 1)? 1 : $time;
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);

		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':' ago');
		}
	}

function colourBrightness($hex, $percent) {
	// Work out if hash given
	$hash = '';
	if (stristr($hex,'#')) {
		$hex = str_replace('#','',$hex);
		$hash = '#';
	}
	/// HEX TO RGB
	$rgb = array(hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2)));
	//// CALCULATE
	for ($i=0; $i<3; $i++) {
		// See if brighter or darker
		if ($percent > 0) {
			// Lighter
			$rgb[$i] = round($rgb[$i] * $percent) + round(255 * (1-$percent));
		} else {
			// Darker
			$positivePercent = $percent - ($percent*2);
			$rgb[$i] = round($rgb[$i] * $positivePercent) + round(0 * (1-$positivePercent));
		}
		// In case rounding up causes us to go to 256
		if ($rgb[$i] > 255) {
			$rgb[$i] = 255;
		}
	}
	//// RBG to Hex
	$hex = '';
	for($i=0; $i < 3; $i++) {
		// Convert the decimal digit to hex
		$hexDigit = dechex($rgb[$i]);
		// Add a leading zero if necessary
		if(strlen($hexDigit) == 1) {
			$hexDigit = "0" . $hexDigit;
		}
		// Append to the hex string
		$hex .= $hexDigit;
	}
	return $hash.$hex;
}


	function cut_string($text,$length) {
		if (strlen($text) > $length) {
			return substr($text,0,$length)."...";
		} else {
			return $text;
		}
	}

	function subscribe_button2($For, $Blocked = false) {
		$api = new \Vidlii\Vidlii\API($_SERVER["DOCUMENT_ROOT"]);
		$session = $api->session($_COOKIE["session"]);
		$is_subscribed = $api->db("SELECT count(*) from subscriptions where subscriber = '".$session["user"]["username"]."' and subscription = '$For'")["data"]["count(*)"];
		if($session["session"] != -1) {
			if($is_subscribed) {
				return '<a href="javascript:void(0)" class="yel_btn sub_button" user="'.$For.'" id="sub">Unsubscribe</a>';
			} else {
				return '<a href="javascript:void(0)" class="yel_btn sub_button" user="'.$For.'" id="sub">Subscribe</a>';
			}
		} else {
			return '<a href="javascript:void(0)" class="yel_btn sub_button" user="'.$For.'" id="sub">Subscribe</a>';
		}
	}

function limit_text($text, $length) {
	$length = abs((int)$length);
	if(mb_strlen($text) > $length) {
		$text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1', $text);
	}
	return($text);
}


function check_captcha() {
	return true; // temporary behavior
//	if (isset($_POST["g-recaptcha-response"]) and $_POST["g-recaptcha-response"]) {
//		return true;
//	} else {
//		return false;
//	}
	/*
	if (isset($_POST["_KAPTCHA"]))
		return kaptcha_validate($_POST["_KAPTCHA_KEY"]);
	return false;
	*/
}

function previous_page() {
	if (!empty($_SESSION["previous_page"])) {
		return $_SESSION["previous_page"];
	} else {
		return "/";
	}
}

function seconds_to_time($Seconds) {
	$hours = floor($Seconds / 3600);
    $minutes = floor(($Seconds % 3600) / 60);
	$seconds = floor($Seconds % 60);
	if($seconds < 10) $seconds = "0$seconds";
	return ($hours > 0) ? "$hours:$minutes:$seconds" : "$minutes:$seconds";
}

function return_category($Number) {
	$Categories = array(1 => "Film & Animation", 2 => "Autos & Vehicles", 3 => "Music", 4 => "Pets & Animals", 5 => "Sports", 6 => "Travel & Events", 7 => "Gaming", 8 => "People & Blogs", 9 => "Comedy", 10 => "Entertainment", 11 => "News & Politics", 12 => "Howto & Style", 13 => "Education", 14 => "Science & Technology", 15 => "Nonprofits & Activism");
	return $Categories[$Number];
}

function return_categories() {
	return array(1 => "Film & Animation", 2 => "Autos & Vehicles", 3 => "Music", 4 => "Pets & Animals", 5 => "Sports", 6 => "Travel & Events", 7 => "Gaming", 8 => "People & Blogs", 9 => "Comedy", 10 => "Entertainment", 11 => "News & Politics", 12 => "Howto & Style", 13 => "Education", 14 => "Science & Technology", 15 => "Nonprofits & Activism");
}

function hexToRgb($hex, $alpha = false) {
	$hex      = str_replace('#', '', $hex);
	$length   = strlen($hex);
	$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
	$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
	$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
	if ( $alpha ) {
		$rgb['a'] = $alpha;
	}
	return "rgba(".$rgb["r"].",".$rgb["g"].",".$rgb["b"].",".$rgb["a"].")";
}

	function return_country($country) {
		$Countries = array('AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');
		return $Countries[$country];
	}
