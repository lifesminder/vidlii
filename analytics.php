<?php
require_once "_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
if (!$_USER->logged_in)         { redirect("/login"); exit(); }



$Countries      = array('AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');

if (isset($_GET["page"])) {

    switch ($_GET["page"]) {

        case "subscribers" :
            $Analytics_Page = "Subscribers";
            break;
        case "videos" :
            $Analytics_Page = "Videos";
            break;
        default :
            redirect("/analytics"); exit();

    }

} else {

    $Analytics_Page = "Default";

}
 

if (isset($_GET["time"])) {

    switch ($_GET["time"]) {

        case "0" :
            $Date = 0;         //ALL TIME
            break;

        case "1" :
            $Date = 1;         //THIS MONTH
            break;

        case "2" :
            $Date = 2;         //THIS WEEK
            break;

        case "3" :
            $Date = 3;         //THIS YEAR
            break;

        case "4" :
            $Date = 4;         //TODAY
            break;

        default :
            redirect("/analytics"); exit();

    }

} else {

    $Date = 0;                 // ALL TIME

}



switch ($Date) {

    case 0 :
        $Subscriber_Date = "";
        break;

    case 1 :
        $Subscriber_Date = " AND MONTH(subscriptions.submit_date) = MONTH(CURDATE()) AND YEAR(subscriptions.submit_date) = YEAR(CURDATE())";
        break;

    case 2 :
        $Subscriber_Date = " AND YEARWEEK(subscriptions.submit_date) = YEARWEEK(NOW())";
        break;

    case 3 :
        $Subscriber_Date = " AND YEAR(subscriptions.submit_date) = YEAR(CURDATE())";
        break;

    case 4 :
        $Subscriber_Date = " AND DATE(subscriptions.submit_date) = CURDATE() ";
        break;
}


switch ($Date) {

    case 0 :
        $Video_Date = "";
        break;

    case 1 :
        $Video_Date = " AND MONTH(videos_views.submit_date) = MONTH(CURDATE()) AND YEAR(videos_views.submit_date) = YEAR(CURDATE())";
        break;

    case 2 :
        $Video_Date = " AND YEARWEEK(videos_views.submit_date) = YEARWEEK(NOW())";
        break;

    case 3 :
        $Video_Date = " AND YEAR(videos_views.submit_date) = YEAR(CURDATE())";
        break;

    case 4 :
        $Video_Date = " AND DATE(videos_views.submit_date) = CURDATE() ";
        break;
}






$Videos = $DB->execute("SELECT url, title FROM videos WHERE uploaded_by = :USERNAME ORDER BY title ASC", false, [":USERNAME" => $_USER->username]);

if ($Analytics_Page == "Default") {

    $Subscribers = $DB->execute("SELECT count(users.username) as amount, users.country FROM subscriptions INNER JOIN users ON users.username = subscriptions.subscriber WHERE subscriptions.subscription = :USERNAME $Subscriber_Date GROUP BY users.country ORDER BY amount DESC LIMIT 5", false,
                               [
                                   ":USERNAME" => $_USER->username
                               ]);


    $Average_Age = $DB->execute("SELECT AVG(TIMESTAMPDIFF(YEAR, users.birthday, CURDATE())) as average FROM subscriptions INNER JOIN users ON users.username = subscriptions.subscriber WHERE subscriptions.subscription = :USERNAME AND (TIMESTAMPDIFF(YEAR, users.birthday, CURDATE())) > 13 AND (TIMESTAMPDIFF(YEAR, users.birthday, CURDATE())) < 70", true,
                               [
                                   ":USERNAME" => $_USER->username
                               ]);

    if (empty($Average_Age["average"])) {

        $Average_Age = false;

    }

    $Average_Rating = $DB->execute("SELECT (1_star * 1 + 2_star * 2 + 3_star * 3 + 4_star * 4 + 5_star * 5) / (1_star + 2_star + 3_star + 4_star + 5_star) as average FROM videos WHERE uploaded_by = :USERNAME", false,
                                  [
                                      ":USERNAME" => $_USER->username
                                  ]);

    if ($DB->RowNum > 0) {

        $Array = [];
        foreach ($Average_Rating as $Rating) {

            $Array[] = $Rating["average"];

        }
        $a = array_filter($Array);
        $Average_Rating = array_sum($a)/count($a);

    } else {

        $Average_Rating = false;

    }

    $Views_Per_Day = $DB->execute("SELECT SUM(videos_views.views) as average FROM videos_views INNER JOIN videos ON videos_views.vid = videos.url WHERE videos.uploaded_by = :USERNAME GROUP BY DATE(videos_views.submit_date)", false,
                                 [
                                     ":USERNAME" => $_USER->username
                                 ]);


    switch ($Date) {

        case 0 :
            $Watch_Date = "";
            break;

        case 1 :
            $Watch_Date = " AND MONTH(videos_watched.submit_date) = MONTH(CURDATE()) AND YEAR(videos_watched.submit_date) = YEAR(CURDATE())";
            break;

        case 2 :
            $Watch_Date = " AND YEARWEEK(videos_watched.submit_date) = YEARWEEK(NOW())";
            break;

        case 3 :
            $Watch_Date = " AND YEAR(videos_watched.submit_date) = YEAR(CURDATE())";
            break;

        case 4 :
            $Watch_Date = " AND DATE(videos_watched.submit_date) = CURDATE() ";
            break;
    }

    $Watchtime_Per_Day = $DB->execute("SELECT (SUM(videos_watched.watchtime) / 60) as amount, videos_watched.submit_date FROM videos_watched INNER JOIN videos ON videos_watched.vid = videos.url WHERE videos.uploaded_by = :USERNAME $Watch_Date GROUP BY DATE(videos_watched.submit_date)", false,
                                    [
                                        ":USERNAME" => $_USER->username
                                    ]);

    if ($DB->RowNum == 0) {

        $Watchtime_Per_Day = false;

    }

    if ($DB->RowNum > 0) {

        $Array = [];
        foreach ($Views_Per_Day as $Views) {

            $Array[] = $Views["average"];

        }
        $a = array_filter($Array);
        $Views_Per_Day = array_sum($a)/count($a);

    } else {

        $Views_Per_Day = false;

    }

    $Popular_Videos                     = new Videos($DB, $_USER);
    $Popular_Videos->Shadowbanned_Users = true;
    $Popular_Videos->Banned_Users       = true;
    $Popular_Videos->Private_Videos     = true;
    $Popular_Videos->Unlisted_Videos    = true;
    if ($Date != 0) {
        $Popular_Videos->JOIN           = "INNER JOIN videos_views ON videos_views.vid = videos.url";
        $Popular_Videos->SELECT        .= ", sum(videos_views.views) as day_views";
        $Popular_Videos->Group_By       = " GROUP BY videos.url ";
    } else {
        $Popular_Videos->SELECT        .= ", videos.displayviews as day_views";
    }
    $Popular_Videos->ORDER_BY           = "day_views DESC";
    $Popular_Videos->WHERE_C            = $Video_Date;
    $Popular_Videos->WHERE_P            = ["uploaded_by" => $_USER->username];
    $Popular_Videos->LIMIT              = 5;
    $Popular_Videos->get();

    if ($Popular_Videos::$Videos) {

        $Popular_Videos = $Popular_Videos->fixed();

    } else {

        $Popular_Videos = [];

    }

} elseif ($Analytics_Page == "Subscribers") {

    if (!isset($_GET["type"]) || $_GET["type"] == 0) {

        if (isset($_GET["country"])) {

            if (!isset($Countries[$_GET["country"]])) { redirect("/analytics"); exit(); }

            $Country = " AND users.country = '".$_GET["country"]."'";

        } else {

            $Country = "";

        }

        $Subscribers_Growth = $DB->execute("SELECT count(subscriptions.subscriber) as amount, subscriptions.submit_date FROM subscriptions INNER JOIN users ON users.username = subscriptions.subscriber WHERE subscriptions.subscription = :USERNAME $Subscriber_Date $Country AND subscriptions.submit_date <> '0000-00-00' GROUP BY DATE(subscriptions.submit_date)", false,
                                          [
                                              ":USERNAME" => $_USER->username
                                          ]);


        $Subscribers = $DB->execute("SELECT count(users.username) as amount, users.country FROM subscriptions INNER JOIN users ON users.username = subscriptions.subscriber WHERE subscriptions.subscription = :USERNAME $Subscriber_Date $Country GROUP BY users.country ORDER BY amount DESC LIMIT 100", false,
                                    [
                                        ":USERNAME" => $_USER->username
                                    ]);
    } else {

        $Subscribers = $DB->execute("SELECT count(subscriptions.subscriber) as amount, subscriptions.source FROM subscriptions WHERE subscriptions.subscription = :USERNAME $Subscriber_Date GROUP BY subscriptions.source ORDER BY amount DESC LIMIT 30", false,
                                    [
                                        ":USERNAME" => $_USER->username
                                    ]);

    }

} elseif ($Analytics_Page == "Videos") {

    if (!isset($_GET["video"])) {

        $Popular_Videos                     = new Videos($DB, $_USER);
        if ($Date != 0) {
            $Popular_Videos->JOIN           = "INNER JOIN videos_views ON videos_views.vid = videos.url";
            $Popular_Videos->SELECT        .= ", sum(videos_views.views) as day_views";
            $Popular_Videos->Group_By       = " GROUP BY videos.url ";
        } else {
            $Popular_Videos->SELECT        .= ", videos.displayviews as day_views";
        }
        $Popular_Videos->Shadowbanned_Users = true;
        $Popular_Videos->Banned_Users       = true;
        $Popular_Videos->Private_Videos     = true;
        $Popular_Videos->WHERE_C            = $Video_Date;
        $Popular_Videos->Unlisted_Videos    = true;
        $Popular_Videos->ORDER_BY           = "day_views DESC";
        $Popular_Videos->WHERE_P            = ["uploaded_by" => $_USER->username];
        $Popular_Videos->LIMIT              = 25;
        $Popular_Videos->get();

        if ($Popular_Videos::$Videos) {

            $Popular_Videos = $Popular_Videos->fixed();

        } else {
            $Popular_Videos = [];
        }


        $Videos_Growth = $DB->execute("SELECT sum(videos_views.views) as amount, videos_views.vid, videos_views.submit_date FROM videos_views INNER JOIN videos ON videos_views.vid = videos.url WHERE videos.uploaded_by = :USERNAME $Video_Date GROUP BY DATE(videos_views.submit_date), videos.uploaded_by", false,
                                    [
                                        ":USERNAME" => $_USER->username
                                    ]);
    } else {

        $Videos_Growth = $DB->execute("SELECT sum(videos_views.views) as amount, videos_views.vid, videos_views.submit_date FROM videos_views INNER JOIN videos ON videos_views.vid = videos.url WHERE (videos.uploaded_by = :USERNAME OR '$_USER->Is_Admin' = '1' OR '$_USER->Is_Mod' = '1') AND videos.url = :URL $Video_Date GROUP BY DATE(videos_views.submit_date)", false,
                                     [
                                         ":URL"      => $_GET["video"],
                                         ":USERNAME" => $_USER->username
                                     ]);

        $Selected_Video = $DB->execute("SELECT sum(videos_views.views) as amount, videos_views.source FROM videos_views RIGHT JOIN videos ON videos_views.vid = videos.url WHERE (videos.uploaded_by = :USERNAME OR '$_USER->Is_Admin' = '1' OR '$_USER->Is_Mod' = '1') AND videos.url = :URL $Video_Date GROUP BY videos_views.source ORDER BY amount DESC", false,
                                      [
                                          ":URL"      => $_GET["video"],
                                          ":USERNAME" => $_USER->username
                                      ]);


        if ($DB->RowNum == 0) { redirect("/analytics"); exit(); }

    }

}


$_USER->get_profile();

$Channel_Version = $_USER->Info["channel_version"];
$Popular_Videos = [];
$Types = array(
    0 => "Default",
    1 => "Director",
    2 => "Musician",
    3 => "Comedian",
    4 => "Gamer",
    5 => "Reporter",
    6 => "Guru",
    7 => "Animator"
);


$Account_Title = "Channel Analytics";


$_PAGE->set_variables(array(
    "Page_Title"        => "Analytics - VidLii",
    "Page"              => "Analytics",
    "Page_Type"         => "Home",
    "Show_Search"       => false
));
require_once "_templates/settings_structure.php";