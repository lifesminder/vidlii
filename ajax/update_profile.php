<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST)
if (!$_USER->logged_in) { exit(); }
if (!isset($_POST))     { exit(); }


$Countries = array('AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');

$_GUMP->validation_rules(array(
    "Name_Value"        => "max_len,64",
    "Website_Value"     => "valid_url|max_len,128",
    "Description_Value" => "max_len,2600",
    "Occupation_Value"  => "max_len,128",
    "Schools_Value"     => "max_len,128",
    "Interests_Value"   => "max_len,128",
    "Movies_Value"      => "max_len,128",
    "Music_Value"       => "max_len,128",
    "Books_Value"       => "max_len,128",
    "Country_Value"     => "required"
));

$_GUMP->filter_rules(array(
    "Name_Value"        => "trim|NoHTML",
    "Website_Value"     => "trim|NoHTML",
    "Description_Value" => "trim|NoHTML",
    "Occupation_Value"  => "trim|NoHTML",
    "Schools_Value"     => "trim|NoHTML",
    "Interests_Value"   => "trim|NoHTML",
    "Movies_Value"      => "trim|NoHTML",
    "Music_Value"       => "trim|NoHTML",
    "Books_Value"       => "trim|NoHTML",
    "Country_Value"     => "trim"
));

$Validation = $_GUMP->run($_POST);

if ($Validation) {
    if ($Validation["Name_Checked"] == "true")          { $Name_Checked         = 1; } else { $Name_Checked = 0; }
    if ($Validation["Website_Checked"] == "true")       { $Website_Checked      = 1; } else { $Website_Checked = 0; }
    if ($Validation["Description_Checked"] == "true")   { $Description_Checked  = 1; } else { $Description_Checked = 0; }
    if ($Validation["Occupation_Checked"] == "true")    { $Occupation_Checked   = 1; } else { $Occupation_Checked = 0; }
    if ($Validation["Schools_Checked"] == "true")       { $Schools_Checked      = 1; } else { $Schools_Checked = 0; }
    if ($Validation["Interests_Checked"] == "true")     { $Interests_Checked    = 1; } else { $Interests_Checked = 0; }
    if ($Validation["Movies_Checked"] == "true")        { $Movies_Checked       = 1; } else { $Movies_Checked = 0; }
    if ($Validation["Music_Checked"] == "true")         { $Music_Checked        = 1; } else { $Music_Checked = 0; }
    if ($Validation["Books_Checked"] == "true")         { $Books_Checked        = 1; } else { $Books_Checked = 0; }
    if ($Validation["Age_Checked"] == "true")           { $Age_Checked          = 1; } else { $Age_Checked = 0; }
    if ($Validation["Last_Checked"] == "true")          { $Last_Checked         = 1; } else { $Last_Checked = 0; }
    if ($Validation["Subs_Checked"] == "true")          { $Subs_Checked         = 1; } else { $Subs_Checked = 0; }
    if ($Validation["Country_Checked"] == "true")       { $Country_Checked      = 1; } else { $Country_Checked = 0; }
    if ($Validation["Subs2_Checked"] == "true")         { $Subs2_Checked        = 1; } else { $Subs2_Checked = 0; }


    $DB->modify("UPDATE users SET
            a_subs2   = :ASUBS2,
            a_country = :ACOUNTRY,
            country = :COUNTRY,
            i_name = :NAME,
            i_occupation = :OCCUPATION,
            i_schools = :SCHOOLS,
            website = :WEBSITE,
            i_interests = :INTERESTS,
            i_movies = :MOVIES,
            i_music = :MUSIC,
            i_books = :BOOKS,
            channel_description = :DESCRIPTION,
            a_name = :ANAME,
            a_website = :AWEBSITE,
            a_description = :ADESCRIPTION,
            a_occupation = :AOCCUPATION,
            a_schools = :ASCHOOLS,
            a_interests = :AINTERESTS,
            a_movies = :AMOVIES,
            a_music = :AMUSIC,
            a_books = :ABOOKS,
            a_subs = :ASUBS,
            a_last = :ALAST,
            a_age = :AAGE
            WHERE username = :USERNAME
            ",
            [
            ":ASUBS2"       => $Subs2_Checked,
            ":ACOUNTRY"     => $Country_Checked,
            ":COUNTRY"      => $Validation["Country_Value"],
            ":ASUBS"        => $Subs_Checked,
            ":ALAST"        => $Last_Checked,
            ":AAGE"         => $Age_Checked,
            ":NAME"         => $Validation["Name_Value"],
            ":OCCUPATION"   => $Validation["Occupation_Value"],
            ":SCHOOLS"      => $Validation["Schools_Value"],
            ":WEBSITE"      => $Validation["Website_Value"],
            ":INTERESTS"    => $Validation["Interests_Value"],
            ":MOVIES"       => $Validation["Movies_Value"],
            ":MUSIC"        => $Validation["Music_Value"],
            ":BOOKS"        => $Validation["Books_Value"],
            ":DESCRIPTION"  => $Validation["Description_Value"],
            ":ANAME"        => $Name_Checked,
            ":AWEBSITE"     => $Website_Checked,
            ":ADESCRIPTION" => $Description_Checked,
            ":AOCCUPATION"  => $Occupation_Checked,
            ":ASCHOOLS"     => $Schools_Checked,
            ":AINTERESTS"   => $Interests_Checked,
            ":AMOVIES"      => $Movies_Checked,
            ":AMUSIC"       => $Music_Checked,
            ":ABOOKS"       => $Books_Checked,
            ":USERNAME"     => $_USER->username
            ]);

    echo $DB->RowNum;
} else {
    echo "error";
}