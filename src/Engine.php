<?php
    namespace Vidlii\Vidlii;

    class Engine {
        private $api;

        function __construct() {
            if(empty($_ENV)) {
                $env = \Dotenv\Dotenv::createImmutable($_SERVER["DOCUMENT_ROOT"]);
                $env->safeLoad();
                $_ENV["setup"] = (!file_exists($_SERVER["DOCUMENT_ROOT"]."/.env"));
            }
            $this->api = new \Vidlii\Vidlii\API($_SERVER["CONFIG_ROOT"]);
        }

        function template($file, $args = [], $return = false) {
            include_once "_includes/init.php";

            // Custom filters and functionality
            $filter = new \Twig\TwigFilter("url_decode", function($string) {
                return urldecode($string);
            });
            $helpArticle = new \Twig\TwigFunction('help', function($article) {
                $article = $this->api->db("SELECT id from help where guid = \"$article\"");
                if($article["count"] == 1) {
                    $id = $article["data"]["id"];
                    return "/help/{$id}";
                }
                return "/help";
            });
            $ratings = new \Twig\TwigFunction("ratings", function($Ratings, $width, $height) {
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
            });
            $ago = new \Twig\TwigFunction("ago", function($time) {
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
            });
            $secondsToTime = new \Twig\TwigFunction("secondsToTime", function($seconds) {
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $seconds = floor($seconds % 60);
                if($seconds < 10) $seconds = "0$seconds";
                return ($hours > 0) ? "$hours:$minutes:$seconds" : "$minutes:$seconds";
            });

            $path = "_templates";
            $loader = new \Twig\Loader\FilesystemLoader("$path/");
            $twig = new \Twig\Environment($loader);
            if(extension_loaded("intl"))
                $twig->addExtension(new \Twig\Extra\Intl\IntlExtension());
            $twig->addFilter($filter);
            $twig->addFunction($ago);
            $twig->addFunction($helpArticle);
            $twig->addFunction($secondsToTime);
            $twig->addFunction($ratings);
            $parsedown = new \Parsedown();
			$fullfile = "$path/$file";

            // Fetch configuration
            $settings = $this->api->db("SELECT * from settings", true);
            $args["config"] = [];
            if($settings["count"] >= 1) {
                foreach($settings["data"] as $key) {
                    $args["config"][$key["name"]] = $key["value"];
                }
            }
            $args["config"]["setup"] = $_ENV["setup"] ?? false;
            $args["config"]["top_text"] = $parsedown->text($args["config"]["top_text"]);
            $args["config"]["header"] = $_THEMES->Header ?? 1;
            $args["config"]["title"] = (isset($_ENV["title"]) && $_ENV["title"] != "") ? $_ENV["title"] : "VidLii";
            $args["config"]["slogan"] = (isset($_ENV["slogan"]) && $_ENV["slogan"] != "") ? $_ENV["slogan"] : "Display Yourself";
            $args["config"]["url"] = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $LOGO_VALUE = $this->api->db("SELECT value FROM settings WHERE name = 'logo'", true)["value"] ?? 0;
            $LOGO_VALUE = ($LOGO_VALUE == "0") ? "/img/Vidlii6.png" : "/img/$LOGO_VALUE.png";
            $args["config"]["logo"] = $LOGO_VALUE;
            $args["q"] = (isset($_GET["q"]) && $_GET["q"] != "") ? $_GET["q"] : null;
            $args["session"] = $this->api->session();
            $args["cookie"] = $_COOKIE;

            // Modify some "keywords" defined by us
            if(isset($args["content"])) {
                $args["content"] = $parsedown->text($args["content"]);
            }

            if(!$return) {
                if(file_exists($fullfile)) echo $twig->render($file, $args);
                else $this->template("error.html");
            } else {
                if(file_exists($fullfile)) return $twig->render($file, $args);
                else return -1;
            }
		}

        // Encrypt/Decrypt
        function encrypt(string $data, string $key = ""): string {
            $key = hash("sha256", $key, true);
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-cbc"));
            $encrypted = openssl_encrypt($data, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
            return base64_encode($iv . $encrypted);
        }
        function decrypt(string $data, string $key = ""): string {
            $key = hash("sha256", $key, true);
            $data = base64_decode($data);
            $iv_length = openssl_cipher_iv_length("aes-256-cbc");
            $iv = substr($data, 0, $iv_length);
            $ciphertext = substr($data, $iv_length);
            return openssl_decrypt($ciphertext, "aes-256-cbc", $key, OPENSSL_RAW_DATA, $iv);
        }

        // Additional functionality
        final public function countries() {
            return ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];
        }
        final public function months() {
            return ['January' => 1,'February' => 2,'March' => 3,'April' => 4,'May' => 5,'June' => 6,'July' => 7,'August' => 8,'September' => 9,'October' => 10,'November' => 11,'December' => 12];
        }
        final public function categories(int $index = -1) {
		    $categories =  [1 => "Film & Animation", 2 => "Autos & Vehicles", 3 => "Music", 4 => "Pets & Animals", 5 => "Sports", 6 => "Travel & Events", 7 => "Gaming", 8 => "People & Blogs", 9 => "Comedy", 10 => "Entertainment", 11 => "News & Politics", 12 => "Howto & Style", 13 => "Education", 14 => "Science & Technology", 15 => "Nonprofits & Activism"];
            if($index > -1) {
                return $categories[$index];
            }
            return $categories;
        }
        final public function channels() {
            $channels = [0 => "Members", 7 => "Animators", 3 => "Comedians", 1 => "Directors", 4 => "Gamers", 6 => "Gurus", 2 => "Musicians",  5 => "Reporters"];
            return $channels;
        }
    }
?>