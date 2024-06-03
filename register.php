<?php
require_once "_includes/init.php";
require_once "_includes/_libs/kaptcha_client.php";
header("Content-Security-Policy: frame-ancestors 'none'");
//REQUIREMENTS / PERMISSIONS
//- Requires Being Not Logged In
if ($_USER->logged_in)         { redirect("/"); exit(); }
if ($DB->execute("SELECT value FROM settings WHERE name = 'signup'", true)["value"] == 0) { notification("Registrations have been temporarily disabled!.", "/", "red"); exit(); }

function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 ||
        (substr($haystack, -$length) === $needle);
}

$Countries      = array('AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');
$Months_Array   = array('January' => 1,'February' => 2,'March' => 3,'April' => 4,'May' => 5,'June' => 6,'July' => 7,'August' => 8,'September' => 9,'October' => 10,'November' => 11,'December' => 12);


if (isset($_POST["submit_register"]) && ctype_alnum($_POST["vl_usernames"])) {

    $_GUMP->validation_rules(array(
        "email"         => "required|valid_email|max_len,128",
        "vl_usernames"   => "required|alpha_numeric|max_len,20|min_len,1",
        "password"      => "required|max_len,128|min_len,4",
        "password2"     => "required|equals,password",
        "day"           => "is_day",
        "month"         => "is_month",
        "year"          => "is_year",
        "age"           => "required",
        "country"       => "required"
    ));

    $_GUMP->filter_rules(array(
        "email"         => "trim|sanitize_email",
        "vl_usernames"  => "trim",
        "password"      => "trim",
        "day"           => "trim",
        "month"         => "trim",
        "year"          => "trim",
        "country"       => "trim"
    ));

    $Validation = $_GUMP->run($_POST);

    if ($Validation && !isTorRequest() && isset($_SESSION["deto"]) && !isset($_SESSION["beto"])) {
        //EMAIL FILTERING
//        $Email_Parts = explode("@",$Validation["email"]);
//        $Validation["email"] = str_replace(".","",$Email_Parts[0])."@".$Email_Parts[1];


//        $Email      = (string)filter_var($Validation["email"], str_replace("+","",$Validation["email"]), FILTER_SANITIZE_EMAIL);
		$Email      = (string)filter_var($Validation["email"], FILTER_SANITIZE_EMAIL);
        $Username   = (string)clean($Validation["vl_usernames"]);
        $Password   = $Validation["password"];
        $R_Password = $Validation["password2"];
        $Day        = (int)$Validation["day"];
        $Month      = (int)$Validation["month"];
        $Year       = (int)$Validation["year"];
        $Country    = (string)$Validation["country"];
        $Birthday   = "$Year-$Month-$Day";
        $IP         = (string)user_ip();
		
		$Ban_Amount = $DB->execute("SELECT count(*) as amount FROM users WHERE (1st_latest_ip = :IP OR 2nd_latest_ip = :IP)", true, [":IP" => $IP])["amount"]; 
        
		if ($Ban_Amount > 22 || isset($_COOKIE["colorsvl"])) {
			
			setcookie("colorsvl", "1s,4b", time() + (26400 * 1200), "/");
			notification("You have too many accounts.", "/register", "red"); exit();
			
		}
		
        //STOPFORUMSPAM
        $IP_VAL = file_get_contents("http://api.stopforumspam.org/api?ip=$IP"); if (strpos($IP_VAL,"<appears>yes</appears>") !== false) { setcookie("f4g", $Username, time() + (96400 * 1200), "/"); header("location: /redirect"); exit(); }
		
		$IP_VAL = file_get_contents("http://check.getipintel.net/check.php?ip=$IP&contact=supapowii@gmail.com&flags=m");
        if ((float)$IP_VAL > 0.7) { notification("Your IP looks suspicious.", "/register", "red"); exit(); }
	

        if (get_age($Birthday) >= 13 && array_key_exists($Country,$Countries)) {
            if (check_captcha()) {
                    $DB->execute("SELECT displayname FROM users WHERE displayname = :USERNAME", false, [":USERNAME" => $Username]);
                    $User_Exists = (bool)$DB->RowNum;

                    $DB->execute("SELECT displayname FROM users_oldnames WHERE displayname = :USERNAME", false, [":USERNAME" => $Username]);
                    $User_Exists2 = (bool)$DB->RowNum;

                    $DB->execute("SELECT email FROM users WHERE email = :EMAIL", false, [":EMAIL" => $Email]);
                    $Email_Exists = (bool)$DB->RowNum;

                    if (!$User_Exists && !$User_Exists2 && !$Email_Exists) {
                        $Allowed_Emails = ["protonmail.com","protonmail.ch","gmail.com", "googlemail.com", "zoho.com", "yahoo.com", "aol.com","hotmail.com","yahoo.mail","vivaldi.com","gmx.de","web.de","gmx.com","outlook.com","yahoo.de","yahoo.gr","rape.lol"];

                        if (get_age($Birthday) >= 120) {
                            $Birthday = "1999-01-01";
                        }

                        //CREATE PASSWORD HASH
                        $Hash = password_hash($Password, PASSWORD_BCRYPT);

                        //CREATE USER
                        $test = $DB->modify("INSERT INTO users (username,displayname,email,password,reg_date,last_login,birthday,1st_latest_ip,country) VALUES ('$Username',:DISPLAYNAME,:EMAIL,:PASSWORD,NOW(),NOW(),:BIRTHDAY,:IP,:COUNTRY)",
                                   [
                                       ":DISPLAYNAME"   => $Username,
                                       ":EMAIL"         => $Email,
                                       ":PASSWORD"      => $Hash,
                                       ":BIRTHDAY"      => $Birthday,
                                       ":IP"            => user_ip(),
                                       ":COUNTRY"       => $Country
                                   ]);

                        $Activation_Link = random_string("ABCDEFGHIJK123456789abcdefghijklmnop", 25);

                        $Insert = $DB->modify("INSERT INTO activations VALUES (:USERNAME,:SECRET)",
                                             [
                                                 ":USERNAME"    => $Username,
                                                 ":SECRET"      => $Activation_Link
                                             ]);
                        if ($DB->RowNum == 1) {

                            $_USER->username = $Username;
                            if ($_USER->login()) {


                                $Usernames = $DB->execute("SELECT username FROM users WHERE 1st_latest_ip = :IP OR 2nd_latest_ip = :IP", false, [":IP" => $IP]);
                                $Blocker_Array  = [];
                                if ($DB->RowNum > 0) {

                                    foreach ($Usernames as $Username) {

                                        $Blockers = $DB->execute("SELECT blocker FROM users_block WHERE blocked = :USERNAME", false, [":USERNAME" => $Username["username"]]);

                                        if ($DB->RowNum > 0) {


                                            foreach ($Blockers as $Blocker) {

                                                if (!in_array($Blocker["blocker"], $Blocker_Array)) {

                                                    $DB->modify("INSERT INTO users_block (blocker, blocked) VALUES (:BLOCKER, :BLOCKED)", [":BLOCKER" => $Blocker["blocker"], ":BLOCKED" => $_USER->username]);
                                                    $Blocker_Array[] = $Blocker["blocker"];

                                                }
                                            }

                                        }

                                    }

                                }


                                redirect("/"); exit();

                            }

                        }
                    } else {
                        if ($User_Exists || $User_Exists2) {
                            $_PAGE->add_error("The user <strong>$Username</strong> already exists!");
                            unset($Validation["vl_usernames"]);
                        } else {
                            $_PAGE->add_error("The email <strong>$Email</strong> is already in use!");
                            unset($Validation["email"]);
                        }
                    }
            } else {
                $_PAGE->add_error("You must prove that you're a real person!");
            }
        } else {
            $_PAGE->add_error("You must be at least 13 to join!");
        }
    } else {
        $Errors = $_GUMP->get_errors_array();

        if (isset($Errors["email"])) {
            $_PAGE->add_error("The email address must be valid!");
            unset($Validation["email"]);
        }
        if (isset($Errors["vl_usernames"])) {
            $_PAGE->add_error("Your username can only contain letters and numbers!");
            unset($Validation["vl_usernames"]);
        }
        if (isset($Errors["vl_password"])) {
            $_PAGE->add_error("Passwords must be at least 4 characters long!");
        }
        if (isset($Errors["r_password"])) {
            $_PAGE->add_error("The passwords don't match!");
        }
        if (isset($Errors["age"])) {
            $_PAGE->add_error("You must confirm that you're at least 13 years old!");
        }
        if (isTorRequest()) {
            $_PAGE->add_error("You cannot create accounts while using TOR!");
        }
        if (!isset($_SESSION["deto"]) || isset($_SESSION["beto"])) {
            $_PAGE->add_error("You cannot create accounts while using TOR!");
        }
    }
}

$_PAGE->set_variables(array(
    "Page_Title"        => "Sign Up - VidLii",
    "Page"              => "Sign_Up",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/page_structure.php";
