<?php
    $Countries = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BQ' => 'Bonaire', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CW' => 'Curacao', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'CD' => 'DR of the Congo', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TL' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'XK' => 'Kosovo', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'KP' => 'North Korea', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'CG' => 'Republic of the Congo', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SX' => 'Sint Maarten', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'KR' => 'South Korea', 'SS' => 'South Sudan', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'VI' => 'U.S. Virgin Islands', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VA' => 'Vatican', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ($Profile["channel_title"] != "") ? $Profile["channel_title"] : $Profile["displayname"]."'s Channel" ?> - VidLii</title>
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_head/profile_head.php" ?>
<?php
    switch($Profile["channel_version"]) {
        case 3: {
?>
    <link rel="stylesheet" href="/css/2012/channels3.css">
    <link rel="stylesheet" href="/css/2012/core.css">
    <link rel="stylesheet" href="/css/2012/guide.css">
    <link rel="stylesheet" href="/css/2012/rest.css">
    <script src="/js/2012/core.js"></script>
    <script src="/js/2012/channels.js"></script>
    <script src="/js/main3.js"></script>
    <script src="/js/cosmicpanda.js"></script>
    <script src="<?= COSMIC_JS_FILE ?>"></script>
<?php
            break;
        }
        case 4: {
?>
    <script src="/js/one/scheduler.js" type="text/javascript" name="scheduler/scheduler" class="js-httpyoutubecomytsjsbinschedulervfl3S_KkKschedulerjs"></script>
    <link rel="stylesheet" href="/css/one/www-core.css" name="www-core" class="css-httpyoutubecomytscssbinwwwcorevflRlRixQcss">
    <link rel="stylesheet" href="/css/one/www-player.css" name="player/www-player" class="css-httpyoutubecomytscssbinplayervflyJJRIpwwwplayercss">
    <link rel="stylesheet" href="/css/one/www-pageframe.css" name="www-pageframe" class="css-httpyoutubecomytscssbinwwwpageframevfldKQBmrcss">
    <link rel="stylesheet" href="/css/one/www-guide.css" name="www-guide" class="css-httpyoutubecomytscssbinwwwguidevflybhooecss">
    <link rel="stylesheet" href="/css/one/www-home.css" name="www-home-c4" class="css-httpyoutubecomytscssbinwwwhomec4vflSOyhuzcss">
    <style>
        .n_head, .n_head input, .n_head select {
            font-family: sans-serif !important;
        }
        .n_head {
            background-color: #fff;
            padding-bottom: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .1);
        } .n_head #sm_nav a {
            color: blue !important;
        } .n_head a:focus {
            border: none;
            outline: none;
        }
    </style>
<?php
            break;
        }
    }
?>
    <? if($Profile["snow"]): ?>
    <script src="/js/snowstorm.js"></script>
    <? endif ?>
    <style>
        .wrapper {
            width: 100%;
            margin: 10px auto;
            <? if ($Has_Background) : ?>background-image: url("<?= $Background ?>");<? endif ?>background-color: #<?= $Profile["bg"] ?>; <? if ($Profile["bg_fixed"] == 1) : ?>background-attachment: fixed<? endif ?>; background-position: <? if ($Profile["bg_position"] == 1) : ?>top<? elseif ($Profile["bg_position"] == 2) : ?>center<? elseif ($Profile["bg_position"] == 3) : ?>bottom<? endif ?>; background-repeat: <? if ($Profile["bg_repeat"] == 1) : ?>no-repeat<? elseif ($Profile["bg_repeat"] == 2) : ?>repeat<? elseif ($Profile["bg_repeat"] == 3) : ?>repeat-x<? elseif ($Profile["bg_repeat"] == 4) : ?>repeat-y<? endif ?>; background-size: <? if ($Profile["bg_stretch"] == 0) : ?>auto<? else : ?>cover<? endif ?>
        }
        #footer-container {
            margin-top: -10px;
        }
        .nouveau-acc-panel {
            display: flex;
            align-items: center;
            justify-content: space-evenly;
            margin: 0.5rem;
            height: 80px;
        }
    </style>
    <script src="/js/main.js"></script>
</head>
<body>
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php" ?>
    <div style="background: transparent; background-color: transparent;">
        <?php
            switch($Profile["channel_version"]) {
                case 3: {
                    $twoColumns = true;
                    $renderablePage = (file_exists($_SERVER["DOCUMENT_ROOT"]."/_templates/nouveau/3/$page.html")) ? "nouveau/3/$page.html" : "nouveau/3/index.html";

                    $args["two_columns"] = $twoColumns;
                    $args["video_count"] = $api->db("SELECT count(*) from videos where uploaded_by = '".$Profile["displayname"]."'")["data"]["count(*)"];
                    $args["playlist_count"] = $api->db("SELECT count(*) from playlists where created_by = '".$Profile["displayname"]."'")["data"]["count(*)"];

                    if(strtolower($page) == "featured" || strtolower($page) == "index" || strtolower($page) == "") {
                        $args["page"] = "index";
                        if($args["videos"]["count"] > 0) {
                            for($i = 0; $i < $args["videos"]["count"]; $i++) {
                                $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                            }
                        }
                        $engine->template("nouveau/3/index.html", $args);
                    } else {
                        if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/_templates/nouveau/3/".$page.".html")) $page = "index";
                        else {
                            switch(strtolower($page)) {
                                case "videos": {
                                    if($args["video_count"] > 0 || $args["playlist_count"] > 0) {
                                        $args["two_columns"] = false;
                                        $view = (isset($_GET["view"]) && (int)$_GET["view"] >= 0) ? (int)$_GET["view"] : 0;
                                        $args["view"] = $view;
                                        
                                        $args["sort"] = (isset($_GET["sort"]) && $_GET["sort"] != "") ? strtolower($_GET["sort"]) : "dd";
                                        if($args["sort"] == "da") $sort = "order by uploaded_on asc";
                                        else if($args["sort"] == "p" && $view == 0) $sort = "order by displayviews desc";
                                        else $sort = "order by uploaded_on desc";
            
                                        if($view == 0) {
                                            $args["videos"] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where uploaded_by = '".$Profile["displayname"]."' $sort", true);
                                            if($args["videos"]["count"] > 0) {
                                                for($i = 0; $i < $args["videos"]["count"]; $i++) {
                                                    $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                                    $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                                                }
                                            } else if($args["playlist_count"] > 0) {
                                                header("Location: /user/".$Profile["displayname"]."/videos?view=1");
                                            }
                                        } else if($view == 1) {
                                            $args["playlists"] = $api->db("SELECT * from playlists where created_by = '".$Profile["displayname"]."' order by created_on desc", true);
                                            if($args["playlists"]["count"] > 0) {
                                                for($i = 0; $i < count($args["playlists"]["data"]); $i++) {
                                                    $args["playlists"]["data"][$i]["videos"] = $api->db("SELECT url, position from playlists_videos where purl = '".$args["playlists"]["data"][$i]["purl"]."' order by position asc");
                                                    if($args["playlists"]["data"][$i]["videos"]["count"] > 0)
                                                        $args["playlists"]["data"][$i]["videos"] = $args["playlists"]["data"][$i]["videos"]["data"];
                                                }
                                            }
                                        }
                                    } else {
                                        $args["page"] = "index";
                                        $page = "index";
                                    }
                                    break;
                                }
                                case "feed": {
                                    $args["filter"] = (isset($_GET["filter"]) && (int)$_GET["filter"] >= 1) ? (int)$_GET["filter"] : 2;
                                    if($args["filter"] == 1) {
                                        $args["comments"] = $api->db("SELECT * from channel_comments where on_channel = '".$Profile["displayname"]."' order by date desc");
                                        if($args["comments"]["count"] > 0) {
                                            for($i = 0; $i < count($args["comments"]["data"]); $i++) {
                                                $args["comments"]["data"][$i]["date"] = get_time_ago($args["comments"]["data"][$i]["date"]);
                                            }
                                        }
                                    } else {
                                        $args["bulletins"] = $api->db("SELECT * from bulletins where by_user = '".$Profile["displayname"]."' order by date desc");
                                        if($args["bulletins"]["count"] > 0) {
                                            for($i = 0; $i < count($args["bulletins"]["data"]); $i++) {
                                                $args["bulletins"]["data"][$i]["date"] = get_time_ago($args["bulletins"]["data"][$i]["date"]);
                                            }
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                        
                        $engine->template("nouveau/3/$page.html", $args);
                    }
                    break;
                }
                case 4: {
                    $page = ($page != "" && file_exists($_SERVER["DOCUMENT_ROOT"]."/_templates/nouveau/4/$page.html")) ? strtolower($page) : "index";
                    $args["featuredblock"] = "true";
                    switch($page) {
                        case "videos": {
                            $args["featuredblock"] = "false";
                            $args["videos"] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where status > 1 and uploaded_by = '".$Profile["displayname"]."' order by uploaded_on desc", true);
                            if($args["videos"]["count"] > 0) {
                                for($i = 0; $i < $args["videos"]["count"]; $i++) {
                                    $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                    $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                                }
                            }
                            break;
                        }
                    }
                    $engine->template("nouveau/4/$page.html", $args);
                    break;
                }
                default: {
                    $engine->template("nouveau/notification.html");
                    break;
                }
            }
        ?>
        <? if($Profile["channel_version"] <= 2): ?>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/footer.php" ?>
        <? endif ?>
        <?php if($Profile["channel_version"] == 3) { ?>
        <div id="footer-container">
            <!-- begin footer -->
            <div id="footer">
                <div class="yt-horizontal-rule ">
                    <span class="first"></span>
                    <span class="second"></span>
                    <span class="third"></span>
                </div>
                <div id="footer-logo">
                    <a href="/" title="VidLii home">
                        <img src="https://s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif" alt="VidLii home">
                    </a>
                    <span id="footer-divider"></span>
                </div>
                <div id="footer-main">
                    <ul id="footer-links-primary">
                        <li><a href="/help">Help</a></li>
                        <li><a href="/about">About</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/copyright">Copyright</a></li>
                        <li><a href="/partners">Partnership</a></li>
                        <li><a href="/developers">Developers</a></li>
                    </ul>
                    <ul id="footer-links-secondary">
                        <li><a href="/terms">Terms of Use</a></li>
                        <li><a href="/privacy">Privacy</a></li>
                        <li><a href="/themes">Themes</a></li>
                        <li><a href="/testlii">Try something new!</a></li>
                    </ul>
                    <ul id="footer-links-secondary">
                        <li><a href="/my_videos">My Videos</a></li>
                        <li><a href="/my_favorites">My Favorites</a></li>
                        <li><a href="/my_subscriptions">My Subscriptions</a></li>
                        <li><a href="/my_account">My Account</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php } ?>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/scripts.php" ?>
    </div>
    <?php if($Profile["channel_version"] == 3) { ?>
    <script>
        var params_open = false;
        document.getElementById("s_username").addEventListener("click", function(e) {
            e.preventDefault();
            if(params_open == false) {
                document.getElementById("masthead").style.height = "140px";
                params_open = true;
            } else {
                document.getElementById("masthead").style.height = "50px";
                params_open = false;
            }
        });
    </script>
    <?php } else if($Profile["channel_version"] == 4) { ?>
    <script src="/js/one/spf.js" type="text/javascript" name="spf/spf" class="js-httpswwwyoutubecomytsjsbinspfvflQdKYDspfjs"></script>
    <script src="/js/one/base.js" name="www/base" class="js-httpswwwyoutubecomytsjsbinwwwen_USvfl7dueWabasejs"></script>
    <?php } ?>
</body>
</html>