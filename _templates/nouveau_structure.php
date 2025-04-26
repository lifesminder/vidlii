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
    <link rel="stylesheet" href="/css/m.css">
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
    <script src="https://www.youtube.com/yts/jslib/angular.min-vflsDVE7V.js"></script>
    <script src="https://www.youtube.com/yts/jsbin/player-vflVZNDz1/en_US/remote.js"></script>
    <script src="https://www.youtube.com/yts/jsbin/player-vflVZNDz1/en_US/annotations_module.js"></script>
    <script src="https://www.youtube.com/yts/jsbin/player-vflVZNDz1/en_US/endscreen.js"></script>
    <script src="https://www.youtube.com/yts/jsbin/player-vflVZNDz1/en_US/captions.js"></script>
    <script src="https://www.youtube.com/yts/jsbin/player-vflVZNDz1/en_US/remote.js"></script>
    <script>
        (function(){var b={a:"content-snap-width-1",b:"content-snap-width-2",c:"content-snap-width-3"};function f(){var a=[],c;for(c in b)a.push(b[c]);return a}
function h(a){var c=f().concat(["guide-pinned","show-guide"]),e=c.length,g=[];a.replace(/\S+/g,function(a){for(var d=0;d<e;d++)if(a==c[d])return;g.push(a)});
return g}
;function k(a,c,e){var g=document.getElementsByTagName("html")[0],d=h(g.className);a&&1251<=(window.innerWidth||document.documentElement.clientWidth)&&(d.push("guide-pinned"),c&&d.push("show-guide"));e&&(e=(window.innerWidth||document.documentElement.clientWidth)-21-50,1251<=(window.innerWidth||document.documentElement.clientWidth)&&a&&c&&(e-=230),d.push(1262<=e?"content-snap-width-3":1056<=e?"content-snap-width-2":"content-snap-width-1"));g.className=d.join(" ")}
var l=["yt","www","masthead","sizing","runBeforeBodyIsReady"],m=this;l[0]in m||!m.execScript||m.execScript("var "+l[0]);for(var n;l.length&&(n=l.shift());)l.length||void 0===k?m[n]&&m[n]!==Object.prototype[n]?m=m[n]:m=m[n]={}:m[n]=k;}).call(this);

      try {window.ytbuffer = {};ytbuffer.handleClick = function(e) {var element = e.target || e.srcElement;while (element.parentElement) {if (/(^| )yt-can-buffer( |$)/.test(element.className)) {window.ytbuffer = {bufferedClick: e};element.className += ' yt-is-buffered';break;}element = element.parentElement;}};if (document.addEventListener) {document.addEventListener('click', ytbuffer.handleClick);} else {document.attachEvent('onclick', ytbuffer.handleClick);}} catch(e) {}

    yt.www.masthead.sizing.runBeforeBodyIsReady(true,true,true);
  </script>
    <script src="https://www.youtube.com/yts/jsbin/scheduler-vfl2BcQXs/scheduler.js" type="text/javascript" name="scheduler/scheduler" class="js-httpswwwyoutubecomytsjsbinschedulervfl2BcQXsschedulerjs"></script>
    <link rel="stylesheet" href="https://www.youtube.com/yts/cssbin/www-core-vflJlY1EV.css" name="www-core" class="css-httpswwwyoutubecomytscssbinwwwcorevflJlY1EVcss">
    <link rel="stylesheet" href="https://www.youtube.com/yts/cssbin/player-vfl8_2Wov/www-player.css" name="player" class="css-httpswwwyoutubecomytscssbinplayervfl8_2Wovwwwplayercss">
    <link rel="stylesheet" href="https://www.youtube.com/yts/cssbin/www-pageframe-vflBE-miK.css" name="www-pageframe" class="css-httpswwwyoutubecomytscssbinwwwpageframevflBEmiKcss">
    <link rel="stylesheet" href="https://www.youtube.com/yts/cssbin/www-guide-vflY8baDX.css" name="www-guide" class="css-httpswwwyoutubecomytscssbinwwwguidevflY8baDXcss">
    <link rel="stylesheet" href="https://www.youtube.com/yts/cssbin/www-home-c4-vflALM-TN.css" name="www-home-c4" class="css-httpswwwyoutubecomytscssbinwwwhomec4vflALMTNcss">
    <style>.exp-invert-logo .hats-logo {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/logo_mini_gray-vflfanGkh.png);width: 65px;height: 15px;}.exp-invert-logo #header:before,.exp-invert-logo .ypc-join-family-header .logo,.exp-invert-logo #footer-logo .footer-logo-icon,.exp-invert-logo #yt-masthead #logo-container .logo,.exp-invert-logo #masthead #logo-container,.exp-invert-logo .admin-masthead-logo a,.exp-invert-logo #yt-sidebar-styleguide-logo #logo {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/logo_small-vflHpzGZm.png);width: 100px;height: 30px;}.exp-invert-logo.inverted-hdpi #header:before,.exp-invert-logo.inverted-hdpi .ypc-join-family-header .logo,.exp-invert-logo.inverted-hdpi #footer-logo .footer-logo-icon,.exp-invert-logo.inverted-hdpi #yt-masthead #logo-container .logo,.exp-invert-logo.inverted-hdpi #masthead #logo-container,.exp-invert-logo.inverted-hdpi .admin-masthead-logo a,.exp-invert-logo.inverted-hdpi #yt-sidebar-styleguide-logo #logo {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/logo_small_2x-vfl4_cFqn.png);background-size: 100px 30px;width: 100px;height: 30px;}.exp-invert-logo.exp-fusion-nav-redesign .masthead-logo-renderer-logo {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/yt_play_logo-vflLfk4yD.png);width: 40px;height: 28px;}.exp-invert-logo.inverted-hdpi.exp-fusion-nav-redesign .masthead-logo-renderer-logo {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/yt_play_logo_2x-vflXx5Pg3.png);width: 40px;height: 28px;}@media screen and (max-width: 656px) {.exp-invert-logo #yt-masthead #logo-container .logo {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/yt_play_logo-vflLfk4yD.png);width: 40px;height: 28px;}.exp-invert-logo.inverted-hdpi #yt-masthead #logo-container .logo {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/yt_play_logo_2x-vflXx5Pg3.png);background-size: 40px 28px;width: 40px;height: 28px;}}@media only screen and (min-width: 0px) and (max-width: 498px),only screen and (min-width: 499px) and (max-width: 704px) {.exp-invert-logo.exp-responsive #yt-masthead #logo-container {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/yt_play_logo-vflLfk4yD.png);width: 40px;height: 28px;}.exp-invert-logo.inverted-hdpi.exp-responsive #yt-masthead #logo-container {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/yt_play_logo_2x-vflXx5Pg3.png);background-size: 40px 28px;width: 40px;height: 28px;}}.exp-invert-logo #yt-masthead #logo-container .logo-red {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/logo_youtube_red-vflZxcSR1.png);width: 132px;height: 30px;}.exp-invert-logo.inverted-hdpi #yt-masthead #logo-container .logo-red {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/logo_youtube_red_2x-vflOSHA_n.png);background-size: 132px 30px;width: 132px;height: 30px;}.exp-invert-logo .guide-item .guide-video-youtube-red-icon {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/video_youtube_red-vflovGTdz.png);width: 20px;height: 20px;}.exp-invert-logo.inverted-hdpi .guide-item .guide-video-youtube-red-icon {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/video_youtube_red_2x-vflqMdgEM.png);background-size: 20px 20px;width: 20px;height: 20px;}.exp-invert-logo .guide-item:hover .guide-video-youtube-red-icon,.exp-invert-logo .guide-item.guide-item-selected .guide-video-youtube-red-icon {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/video_youtube_red_hover-vflgV4Gv0.png);width: 20px;height: 20px;}.exp-invert-logo.inverted-hdpi .guide-item:hover .guide-video-youtube-red-icon,.exp-invert-logo.inverted-hdpi .guide-item.guide-item-selected .guide-video-youtube-red-icon {background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/video_youtube_red_hover_2x-vflYjZHvf.png);background-size: 20px 20px;width: 20px;height: 20px;}.exp-invert-logo li.guide-section h3,.exp-invert-logo li.guide-section h3 a {color: #f00;}.exp-invert-logo a.yt-uix-button-epic-nav-item:hover,.exp-invert-logo a.yt-uix-button-epic-nav-item.selected,.exp-invert-logo a.yt-uix-button-epic-nav-item.yt-uix-button-toggled,.exp-invert-logo a.yt-uix-button-epic-nav-item.partially-selected,.exp-invert-logo a.yt-uix-button-epic-nav-item.partially-selected:hover,.exp-invert-logo button.yt-uix-button-epic-nav-item:hover,.exp-invert-logo button.yt-uix-button-epic-nav-item.selected,.exp-invert-logo button.yt-uix-button-epic-nav-item.yt-uix-button-toggled,.exp-invert-logo .epic-nav-item:hover,.exp-invert-logo .epic-nav-item.selected,.exp-invert-logo .epic-nav-item.yt-uix-button-toggled,.exp-invert-logo .epic-nav-item-heading,.exp-invert-logo .yt-gb-shelf-item-thumbtab.yt-gb-selected-shelf-tab::before {border-color: #f00;}.exp-invert-logo .resume-playback-progress-bar,.exp-invert-logo .yt-uix-button-subscribe-branded,.exp-invert-logo .yt-uix-button-subscribe-branded[disabled],.exp-invert-logo .yt-uix-button-subscribe-branded[disabled]:hover,.exp-invert-logo .yt-uix-button-subscribe-branded[disabled]:active,.exp-invert-logo .yt-uix-button-subscribe-branded[disabled]:focus,.exp-invert-logo .sb-notif-on .yt-uix-button-content,.exp-invert-logo .guide-item.guide-item-selected,.exp-invert-logo .guide-item.guide-item-selected:hover,.exp-invert-logo .guide-item.guide-item-selected .yt-deemphasized-text,.exp-invert-logo .guide-item.guide-item-selected:hover .yt-deemphasized-text {background-color: #f00;}.exp-invert-logo .yt-uix-button-subscribe-branded:hover {background-color: #d90a17;}.exp-invert-logo .yt-uix-button-subscribe-branded.yt-is-buffered,.exp-invert-logo .yt-uix-button-subscribe-branded:active,.exp-invert-logo .yt-uix-button-subscribe-branded.yt-uix-button-toggled,.exp-invert-logo .yt-uix-button-subscribe-branded.yt-uix-button-active,.exp-invert-logo .yt-uix-button-subscribed-branded.external,.exp-invert-logo .yt-uix-button-subscribed-branded.external[disabled],.exp-invert-logo .yt-uix-button-subscribed-branded.external:active,.exp-invert-logo .yt-uix-button-subscribed-branded.external.yt-uix-button-toggled,.exp-invert-logo .yt-uix-button-subscribed-branded.external.yt-uix-button-active {background-color: #a60812;}</style><style>.exp-invert-logo #header:before, .exp-invert-logo .ypc-join-family-header .logo, .exp-invert-logo #footer-logo .footer-logo-icon, .exp-invert-logo #yt-masthead #logo-container .logo, .exp-invert-logo #masthead #logo-container, .exp-invert-logo .admin-masthead-logo a, .exp-invert-logo #yt-sidebar-styleguide-logo #logo { background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/logo_small_2x-vfl4_cFqn.png); background-size: 100px 30px; } .exp-invert-logo #yt-masthead #logo-container .logo-red { background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/logo_youtube_red_2x-vflOSHA_n.png); background-size: 132px 30px; } @media only screen and (min-width: 0px) and (max-width: 498px), only screen and (min-width: 499px) and (max-width: 704px) { .exp-invert-logo.exp-responsive #yt-masthead #logo-container { background: no-repeat url(https://www.youtube.com/yts/img/ringo/hitchhiker/yt_play_logo_2x-vflXx5Pg3.png); background-size: 40px 28px; } } .guide-sort-container {display: none}</style>
    <link rel="manifest" href="https://web.archive.org/web/20180131232212/https://www.youtube.com/manifest.json">
    <link class="css-httpswwwyoutubecomytscssbinwwwpageframedelayloadedvflNXOrSacss" rel="stylesheet" href="https://www.youtube.com/yts/cssbin/www-pageframedelayloaded-vflNXOrSa.css" name="www-pageframedelayloaded">
    <!--
    <script src="/js/one/scheduler.js" type="text/javascript" name="scheduler/scheduler" class="js-httpyoutubecomytsjsbinschedulervfl3S_KkKschedulerjs"></script>
    <link rel="stylesheet" href="/css/one/www-core.css" name="www-core" class="css-httpyoutubecomytscssbinwwwcorevflRlRixQcss">
    <link rel="stylesheet" href="/css/one/www-player.css" name="player/www-player" class="css-httpyoutubecomytscssbinplayervflyJJRIpwwwplayercss">
    <link rel="stylesheet" href="/css/one/www-pageframe.css" name="www-pageframe" class="css-httpyoutubecomytscssbinwwwpageframevfldKQBmrcss">
    <link rel="stylesheet" href="/css/one/www-guide.css" name="www-guide" class="css-httpyoutubecomytscssbinwwwguidevflybhooecss">
    <link rel="stylesheet" href="/css/one/www-home.css" name="www-home-c4" class="css-httpyoutubecomytscssbinwwwhomec4vflSOyhuzcss">
    -->
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
    <script src="/js/modern_player.js"></script>
    <script src="/jwplayer2/jwplayer.js"></script>
    <script src="/vlPlayer/main19.js"></script>
    <? if ($Profile["mondo"]) : ?>
        <canvas class="snow" height="100%" style="width:100%;z-index:1000000;position:fixed;height:100vh !important;pointer-events:none"></canvas>
        <script src="/js/let_it_mondo.js"></script>
        <script>
        $("canvas.snow").let_it_snow({
            speed: 0,
            interaction: true,
            size: 7,
            count: 50,
            opacity: 0,
            color: "#ffffff",
            windPower: 0,
            image: "/img/cursor2.png"
        });
        </script>
    <? endif ?>
</head>
<body>
    <?php
        require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php";
    ?>
    <div style="background: transparent; background-color: transparent;">
        <?php
            switch($Profile["channel_version"]) {
                case 3: {
                    $twoColumns = true;
                    $renderablePage = (file_exists($_SERVER["DOCUMENT_ROOT"]."/_templates/nouveau/3/$page.html")) ? "nouveau/3/$page.html" : "nouveau/3/index.html";

                    $args["two_columns"] = $twoColumns;
                    $args["video_count"] = $api->db("SELECT count(*) from videos where uploaded_by = '".$Profile["displayname"]."'")["data"]["count(*)"];
                    $args["playlist_count"] = $api->db("SELECT count(*) from playlists where created_by = '".$Profile["displayname"]."'")["data"]["count(*)"];
                    $args["playlists"] = $api->db("SELECT * from playlists where created_by = '".$Profile["displayname"]."' order by created_on $sort", true);
                    $args["bg_color"] = ($Profile["bg"] == "f9f9f9") ? "transparent" : "#".$Profile["bg"];
                    $args["bg_cover"] = (strlen($Profile["cover"]) > 0) ? "/vi/cover/".$Profile["displayname"].".jpg" : null;

                    // If there is a notification, pass it
                    if(isset($_COOKIE["notification"])) {
                        $notificationParts = explode(";", $_COOKIE["notification"]);
                        $args["notification"] = $notificationParts[0];
                        $args["notification_type"] = $notificationParts[1];
                        setcookie("notification", "", time() - 3600);
                        unset($_COOKIE["notification"]);
                    }

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
                        if(!file_exists($_SERVER["DOCUMENT_ROOT"]."/_templates/nouveau/3/".$page.".html"))
                            $page = "index";
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
                                            $args["videos"] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where status > 1 and uploaded_by = '".$Profile["displayname"]."' $sort", true);
                                            if($args["videos"]["count"] > 0) {
                                                for($i = 0; $i < $args["videos"]["count"]; $i++) {
                                                    $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                                    $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                                                }
                                            } else if($args["playlist_count"] > 0) {
                                                header("Location: /user/".$Profile["displayname"]."/videos?view=1");
                                            }
                                        } else if($view == 1) {
                                            $sort = (isset($_GET["sort"]) && $_GET["sort"] == "da") ? "asc" : "desc";
                                            if($args["playlists"]["count"] > 0) {
                                                $playlists = [];
                                                foreach($args["playlists"] as $playlist) {
                                                    if(!empty($playlist[0])) {
                                                        $purl = $playlist[0]["purl"];
                                                        $playlist[0]["videos"] = $api->db("SELECT url, position from playlists_videos where purl = \"$purl\" order by position asc", true);
                                                        if(empty($playlist[0]["videos"])) $playlist[0]["videos"]["count"] = 0;
                                                        array_push($playlists, $playlist[0]);
                                                    }
                                                }
                                                $args["playlists"] = $playlists;
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
                                        $args["comments"] = $api->db("SELECT * from channel_comments where on_channel = '".$Profile["displayname"]."' order by id desc", true);
                                        if($args["comments"]["count"] > 0) {
                                            for($i = 0; $i < count($args["comments"]["data"]); $i++) {
                                                $args["comments"]["data"][$i]["date"] = get_time_ago($args["comments"]["data"][$i]["date"]);
                                            }
                                        }
                                    } else {
                                        $args["bulletins"] = $api->db("SELECT * from bulletins where by_user = '".$Profile["displayname"]."' order by id desc", true);
                                        if($args["bulletins"]["count"] > 0) {
                                            for($i = 0; $i < count($args["bulletins"]["data"]); $i++) {
                                                $args["bulletins"]["data"][$i]["date"] = get_time_ago($args["bulletins"]["data"][$i]["date"]);
                                            }
                                        }
                                    }

                                    // Remove bulletins. Available only for owner.
                                    if($args["owner"] && isset($_GET["remove_post"]) && (int)$_GET["remove_post"] > 0) {
                                        $remove_post = (int)$_GET["remove_post"];
                                        $exists = (bool)$api->db("SELECT count(*) from bulletins where id = $remove_post")["data"]["count(*)"];
                                        if($exists) {
                                            $remove_post = $api->db("DELETE from bulletins where id = $remove_post");
                                            if($remove_post["status"] == 1) {
                                                notification("Post removed successfully", "/user/".$Profile["displayname"], "success");
                                            } else {
                                                notification($remove_post["message"], "/user/".$Profile["displayname"], "error");
                                            }
                                        }
                                    } else if(isset($_GET["remove_comment"]) && (int)$_GET["remove_comment"] > 0) {
                                        $remove_comment = (int)$_GET["remove_post"];
                                        // If it isn't owner, then there is a huge need to check relativity to the comment owner,
                                        // otherwise allow in all cases
                                        if($args["owner"])
                                            $exists = (bool)$api->db("SELECT count(*) from channel_comments where id = $remove_post")["data"]["count(*)"];
                                        else
                                            $exists = (bool)$api->db("SELECT count(*) from channel_comments where id = $remove_post and by_user = ".$_USER->username)["data"]["count(*)"];
                                        
                                        if($exists) {
                                            $remove_comment = $api->db("DELETE from channel_comments where id = $remove_post");
                                            if($remove_comment["status"] == 1) {
                                                notification("Comment removed successfully", "/user/".$Profile["displayname"], "success");
                                            } else {
                                                notification($remove_comment["message"], "/user/".$Profile["displayname"], "error");
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
                    $args["bg_cover"] = (strlen($Profile["cover"]) > 0) ? "/vi/cover/".$Profile["displayname"].".jpg" : null;
                    $page = ($page != "" && file_exists($_SERVER["DOCUMENT_ROOT"]."/_templates/nouveau/4/$page.html")) ? strtolower($page) : "index";

                    // Grid or sort show
                    $args["flow"] = (isset($_GET["flow"]) && $_GET["flow"] == "list") ? "list" : "grid";
                    $args["sort"] = (isset($_GET["sort"]) && $_GET["sort"] != "") ? strtolower($_GET["sort"]) : "dd";

                    // Sorting
                    if($args["sort"] == "da") $sort = "order by uploaded_on asc";
                    else if($args["sort"] == "p" && $view == 0) $sort = "order by displayviews desc";
                    else $sort = "order by uploaded_on desc";

                    // Featured channels block existence
                    $args["featuredblock"] = "true";

                    // Understand the page
                    switch($page) {
                        case "index": {
                            $args["videos"] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where status > 1 and title like \"%$query%\" and uploaded_by = '".$Profile["displayname"]."' order by uploaded_on desc limit 5", true);
                            if($args["videos"]["count"] > 0) {
                                // Get featured video
                                $args["featured_video"] = $args["videos"]["data"][0];
                                $args["featured_video"]["autoplay"] = 1;
                                $args["featured_video"]["uploaded"] = get_time_ago($args["featured_video"]["uploaded_on"]);
                                // Go through other videos
                                for($i = 0; $i < $args["videos"]["count"]; $i++) {
                                    $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                    $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                                }
                            }
                            break;
                        }
                        case "videos": {
                            $args["featuredblock"] = "false";
                            $args["favorites_count"] = $api->db("SELECT count(*) from video_favorites where favorite_by = \"".$Profile["displayname"]."\"")["data"]["count(*)"];

                            // Parse view type
                            if(isset($_GET["view"]) && (int)$_GET["view"] > 0) {
                                $view = (int)$_GET["view"];
                                switch($view) {
                                    case 2: {
                                        // Favorite videos
                                        $args["videos"] = [];
                                        $args["view"] = "favorite";
                                        $args["viewn"] = $view;
                                        $favorites = $api->db("SELECT * from video_favorites where favorite_by = \"".$Profile["displayname"]."\"", true);
                                        if($favorites["count"] > 0) {
                                            $i = 0;
                                            $args["videos"]["count"] = $favorites["count"];
                                            foreach($favorites["data"] as $favorite) {
                                                $url = $favorite["url"];
                                                $args["videos"]["data"][$i] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where status > 1 and url = \"$url\" $sort", true);
                                                if($args["videos"]["data"][$i]["count"] == 1) {
                                                    $args["videos"]["data"][$i] = $args["videos"]["data"][$i]["data"][0];
                                                    $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                                    $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                                                }
                                                $i++;
                                            }
                                        } else {
                                            header("Location: /user/".$Profile["displayname"]."/videos");
                                        }
                                        break;
                                    }
                                    case 57: {
                                        $args["view"] = "all";
                                        // All shields, including both uploads and favorites
                                        $favorites = $api->db("SELECT * from video_favorites where favorite_by = \"".$Profile["displayname"]."\"", true);
                                        if($favorites["count"] > 0) {
                                            $i = 0;
                                            $args["favorites"]["count"] = $favorites["count"];
                                            foreach($favorites["data"] as $favorite) {
                                                $url = $favorite["url"];
                                                $args["favorites"]["data"][$i] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where status > 1 and url = \"$url\" $sort", true);
                                                if($args["favorites"]["data"][$i]["count"] == 1) {
                                                    $args["favorites"]["data"][$i] = $args["videos"]["data"][$i]["data"][0];
                                                    $args["favorites"]["data"][$i]["length"] = seconds_to_time($args["favorites"]["data"][$i]["length"]);
                                                    $args["favorites"]["data"][$i]["uploaded_on"] = get_time_ago($args["favorites"]["data"][$i]["uploaded_on"]);
                                                }
                                                $i++;
                                            }
                                        }
                                        $args["videos"] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where status > 1 and uploaded_by = '".$Profile["displayname"]."' $sort", true);
                                        if($args["videos"]["count"] > 0) {
                                            for($i = 0; $i < $args["videos"]["count"]; $i++) {
                                                $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                                $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                                            }
                                        }
                                        break;
                                    }
                                    default: {
                                        $args["view"] = "uploads";
                                        $args["videos"] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where status > 1 and uploaded_by = '".$Profile["displayname"]."' $sort", true);
                                        if($args["videos"]["count"] > 0) {
                                            for($i = 0; $i < $args["videos"]["count"]; $i++) {
                                                $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                                $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                                            }
                                        }
                                        break;
                                    }
                                }
                            } else {
                                $args["view"] = "uploads";
                                $args["videos"] = $api->db("SELECT url, title, description, uploaded_on, length, displayviews from videos where status > 1 and uploaded_by = '".$Profile["displayname"]."' $sort", true);
                                if($args["videos"]["count"] > 0) {
                                    for($i = 0; $i < $args["videos"]["count"]; $i++) {
                                        $args["videos"]["data"][$i]["length"] = seconds_to_time($args["videos"]["data"][$i]["length"]);
                                        $args["videos"]["data"][$i]["uploaded_on"] = get_time_ago($args["videos"]["data"][$i]["uploaded_on"]);
                                    }
                                }
                            }
                            
                            break;
                        }
                        case "playlists": {
                            $args["featuredblock"] = "false";
                            $args["playlists"] = $api->db("SELECT * FROM playlists WHERE created_by = '".$Profile["displayname"]."'", true);
                            if($args["playlists"]["count"] > 0) {
                                for($i = 0; $i < $args["playlists"]["count"]; $i++) {
                                    $args["playlists"]["data"][$i]["count"] = $api->db("SELECT count(*) as count from playlists_videos where purl = '".$args["playlists"]["data"][$i]["purl"]."'")["data"]["count"];
                                    $args["playlists"]["data"][$i]["videos"] = $api->db("SELECT * from playlists_videos where purl = '".$args["playlists"]["data"][$i]["purl"]."' order by position asc limit 1");
                                    if($args["playlists"]["data"][$i]["videos"]["count"] > 0)
                                        $args["playlists"]["data"][$i]["videos"] = $args["playlists"]["data"][$i]["videos"]["data"];
                                }
                            }
                            break;
                        }
                        case "community": {
                            $args["featuredblock"] = "false";
                            break;
                        }
                        case "channels": {
                            $args["featuredblock"] = "false";
                            $args["view"] = (isset($_GET["view"]) && (int)$_GET["view"] != 60) ? (int)$_GET["view"] : 60;
                            if(strlen($Profile["featured_channels"]) > 0) {
                                $featured_channels = [];
                                $args["featured_channels"] = explode(",", $Profile["featured_channels"]);
                                foreach($args["featured_channels"] as $channel) {
                                    $channel = $api->db("SELECT displayname, channel_title, channel_description, (select count(*) from subscriptions where subscription = '$channel') as subscribers, partner, is_admin, is_mod from users where username = '$channel'");
                                    if($channel["count"] == 1) {
                                        $channel = $channel["data"];
                                        $channel["videos"] = $api->db("SELECT count(*) from videos where uploaded_by = \"".$channel["displayname"]."\" and status > 0")["data"]["count(*)"];
                                    }
                                    array_push($featured_channels, $channel);
                                }
                                $args["featured_channels"] = $featured_channels;
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
    <?php if($Profile["channel_version"] == 4) { ?>
    <script src="https://www.youtube.com/yts/jsbin/spf-vflRfjT3b/spf.js" type="text/javascript" name="spf/spf" class="js-httpswwwyoutubecomytsjsbinspfvflRfjT3bspfjs"></script>
    <script src="https://www.youtube.com/yts/jsbin/www-en_US-vfle_h60h/base.js" name="www/base" class="js-httpswwwyoutubecomytsjsbinwwwen_USvfle_h60hbasejs"></script>
    <script>spf.script.path({'www/': 'https://youtube.com/yts/jsbin/www-en_US-vfle_h60h/'});var ytdepmap = {"www/base": null, "www/common": "www/base", "www/angular_base": "www/common", "www/channels_accountupload": "www/common", "www/channels": "www/common", "www/dashboard": "www/common", "www/downloadreports": "www/common", "www/experiments": "www/common", "www/feed": "www/common", "www/instant": "www/common", "www/legomap": "www/common", "www/promo_join_network": "www/common", "www/results_harlemshake": "www/common", "www/results": "www/common", "www/results_starwars": "www/common", "www/subscriptionmanager": "www/common", "www/unlimited": "www/common", "www/watch": "www/common", "www/ypc_bootstrap": "www/common", "www/ypc_core": "www/common", "www/channels_edit": "www/channels", "www/live_dashboard": "www/angular_base", "www/videomanager": "www/angular_base", "www/watch_autoplayrenderer": "www/watch", "www/watch_edit": "www/watch", "www/watch_editor": "www/watch", "www/watch_live": "www/watch", "www/watch_promos": "www/watch", "www/watch_speedyg": "www/watch", "www/watch_transcript": "www/watch", "www/watch_videoshelf": "www/watch", "www/ct_advancedsearch": "www/videomanager", "www/my_videos": "www/videomanager"};spf.script.declare(ytdepmap);</script><script>if (window.ytcsi) {window.ytcsi.tick("je", null, '');}</script>  
    <script>
        yt.setConfig('ANGULAR_JS', "https:\/\/youtube.com\/yts\/jslib\/angular.min-vflsDVE7V.js");
        yt.setConfig('TRANSLATIONEDITOR_JS', "https:\/\/youtube.com\/yts\/jsbin\/www-translationeditor-vflsAtKMl\/www-translationeditor.js");
        yt.setMsg('UNSAVED_CHANGES_WARNING', "Some of the changes you have made to channel settings have not been saved and will be lost if you navigate away from this page.");
        yt.setConfig('JS_PAGE_MODULES', ["www\/channels","www\/ypc_bootstrap"]);
        yt.setConfig('CHANNEL_ID', "UCBR8-60-B28hp2BmDPdntcQ");
        yt.setConfig('CHANNEL_TAB', "featured");
        yt.setConfig('DISMISS_THROUGH_IT', true);
        yt.setConfig({
            'GUIDE_SELECTED_ITEM': "0qDduQEoEhhVQ0JSOC02MC1CMjhocDJCbURQZG50Y1EaDE9BRmdBV29BdUFFQQ%3D%3D"
        });
        yt.setConfig({
            'GUIDED_HELP_LOCALE': "en_US",
            'GUIDED_HELP_ENVIRONMENT': "prod"
        });
    </script>
    <script>
        document.getElementsByClassName("show-search")[0].addEventListener("click", function(e) {
            e.preventDefault();
            let cSearch = document.getElementById("channel-search");
            if(!cSearch.hasAttribute("class")) {
                cSearch.setAttribute("class", "expanded");  
            }
        });
    </script>
     <script>var ytplayer = ytplayer || {};ytplayer.config = {"html5":true,"url":"","attrs":{"id":"movie_player"},"sts":17555,"min_version":"8.0.0","params":{"allowscriptaccess":"always","allowfullscreen":"true","bgcolor":"#000000"},"args":{"gapi_hint_params":"m;\/_\/scs\/abc-static\/_\/js\/k=gapi.gapi.en.yoTdpQipo6s.O\/m=__features__\/am=AAE\/rt=j\/d=1\/rs=AHpOoo9_VhuRoUovwpPPf5LqLZd-dmCnxw","ssl":"1","xhr_apiary_host":"youtubei.youtube.com","cver":"1.20180130","fexp":"23708158,23708491,23708904,23708906,23708910,23710476,23713235,23713711,23716257,23716381,23716971,23717909,23718341,23719518,23719525,23720438,23721898,23722555,23722805,23722825,23722828,23723588,23723735,9406175,9413137,9422596,9431754,9441347,9441382,9449243,9452652,9471239,9471659,9474594,9485000,9488572,9489758","player_error_log_fraction":"1.0","innertube_api_key":"AIzaSyAO_FJ2SlqU8Q4STEHLGCilw_Y9_11qcW8","apiary_host":"","c":"WEB","innertube_api_version":"v1","apiary_host_firstparty":"","enablejsapi":"1","innertube_context_client_version":"1.20180130","host_language":"en","autoplay":"0","cr":"US","is_html5_mobile_device":false,"fflags":"html5_request_size_min_secs=0.0\u0026html5_drm_generate_request_delay=0\u0026web_player_disable_flash_playerproxy=true\u0026show_countdown_on_bumper=true\u0026mweb_playsinline=true\u0026html5_prefer_server_bwe3=true\u0026html5_start_off_live=0\u0026midroll_notify_time_seconds=5\u0026ad_duration_threshold_for_showing_endcap_seconds=15\u0026html5_throttle_rate=0.0\u0026html5_subsegment_readahead_tail_margin_secs=0.2\u0026html5_tight_max_buffer_allowed_bandwidth_stddevs=0.0\u0026limit_of_rebuffering_event_on_html5=1000\u0026show_thumbnail_on_standard=true\u0026live_chunk_readahead=3\u0026vss_dni_delayping=0\u0026web_embedded_player_service=true\u0026use_forced_linebreak_preskip_text=true\u0026html5_ultra_low_latency_streaming_responses=true\u0026html5_deadzone_multiplier=1.0\u0026flex_theater_mode=true\u0026html5_sticky_ignore_capped=true\u0026html5_disable_audio_slicing=true\u0026html5_incremental_parser_coalesce_slice_buffers=true\u0026dynamic_ad_break_seek_threshold_sec=0\u0026html5_incremental_parser_buffer_extra_bytes=16384\u0026html5_live_abr_head_miss_fraction=0.0\u0026persist_text_on_preview_button=true\u0026disable_new_pause_state3=true\u0026fast_autonav_in_background=true\u0026show_thumbnail_behind_ypc_offer_module=true\u0026html5_new_e2e_latency_tracking=true\u0026show_interstitial_white=true\u0026html5_always_enable_timeouts=true\u0026html5_allowable_liveness_drift_chunks=2\u0026forced_brand_precap_duration_ms=2000\u0026html5_subsegment_readahead_progress_timeout_fraction=0.8\u0026html5_adunit_from_adformat=true\u0026disable_max_adsense_channel_limit=true\u0026html5_aspect_from_adaptive_format=true\u0026html5_min_secs_between_format_selections=8.0\u0026html5_preload_size_excludes_metadata=true\u0026html5_repredict_interval_secs=0.0\u0026html5_env_data_update_app_only=true\u0026html5_sticky_reduces_discount_by=0.0\u0026disable_mweb_iphone_ad_click_handling_functionality=true\u0026html5_report_conn=true\u0026disable_trusted_ad_domains_player_check=true\u0026use_new_skip_icon=true\u0026disable_set_awesome_html5=true\u0026playready_first_play_expiration=-1\u0026fixed_padding_skip_button=true\u0026html5_hopeless_mode_request_size_secs=15\u0026autoplay_time=8000\u0026disable_reporting_html5_no_vast_ads_as_error=true\u0026deprecate_get_video_metadata=true\u0026call_release_video_in_bulleit=true\u0026html5_quality_cap_min_age_secs=0\u0026use_survey_skip_in_0s=true\u0026html5_mobile_perf_cap_240=true\u0026king_crimson_player_redux=true\u0026html5_min_buffer_to_resume=6\u0026html5_el_migration=true\u0026dash_manifest_version=5\u0026html5_enable_bandwidth_estimation_type=true\u0026html5_use_adaptive_live_readahead=true\u0026hide_preskip=true\u0026html5_msi_error_fallback=true\u0026html5_request_size_max_secs=31\u0026html5_manifestless_accurate_sliceinfo=true\u0026html5_stop_video_in_cancel_playback=true\u0026mpu_visible_threshold_count=2\u0026html5_new_peg_to_live_v2=true\u0026html5_readahead_ratelimit=3000\u0026use_new_style=true\u0026enable_bulleit_lidar_integration=true\u0026html5_max_headm_for_streaming_xhr=0\u0026www_for_videostats=true\u0026segment_volume_reporting=true\u0026mweb_enable_skippables_on_iphone=true\u0026html5_live_disable_dg_pacing=true\u0026html5_mweb_client_cap=true\u0026website_actions_cta_redesign=true\u0026html5_minimum_readahead_seconds=0.0\u0026enable_prefetch_for_postrolls=true\u0026html5_observe_live_start_seconds=true\u0026use_fast_fade_in_0s=true\u0026html5_elbow_tracking_tweaks=true\u0026html5_get_video_info_timeout_ms=0\u0026disable_set_awesome_mweb=true\u0026enable_spherical_ps4=true\u0026mweb_muted_autoplay=true\u0026web_player_api_logging_fraction=0.01\u0026enable_afv_div_reset_in_kevlar=true\u0026stop_using_ima_sdk_gpt_request_activity=true\u0026mweb_cougar=true\u0026html5_use_equirect_mesh=true\u0026html5_local_max_byterate_lookahead=15\u0026fix_gpt_pos_params=true\u0026max_resolution_for_white_noise=360\u0026disable_client_side_midroll_freq_capping=true\u0026html5_live_low_latency_bandwidth_window=0.0\u0026ad_video_end_renderer_duration_milliseconds=7000\u0026youtubei_for_web=true\u0026live_readahead_seconds_multiplier=0.8\u0026html5_widevine_robustness_strings=true\u0026html5_serverside_call_server_on_biscotti_timeout=true\u0026html5_stale_dash_manifest_retry_factor=1.0\u0026html5_add_ratebypass_param=true\u0026html5_fludd_suspend=true\u0026skip_restore_on_abandon_in_bulleit=true\u0026legacy_autoplay_flag=true\u0026html5_enable_ms_playready_hd=true\u0026html5_default_quality_cap=0\u0026kevlar_allow_multistep_video_init=true\u0026live_fresca_v2=true\u0026html5_subsegment_readahead_always_delay_appends=true\u0026html5_request_sizing_multiplier=0.8\u0026mweb_cougar_ads_backend=true\u0026html5_stall_pctile=true\u0026html5_pipeline_manifestless=true\u0026sdk_wrapper_levels_allowed=0\u0026html5_suspended_state=true\u0026enable_live_state_auth=true\u0026html5_disable_preserve_reference=true\u0026html5_video_tbd_min_kb=0\u0026web_player_tabindex_killswitch=true\u0026html5_min_readbehind_secs=0\u0026set_interstitial_start_button=true\u0026html5_new_fallback=true\u0026html5_incremental_parser_buffer_duration_secs=1.5\u0026html5_default_ad_gain=0.5\u0026html5_live_pin_to_tail=true\u0026mweb_autonav=true\u0026mweb_autonav_paddles=true\u0026postroll_notify_time_seconds=5\u0026html5_platform_minimum_readahead_seconds=0.0\u0026website_actions_throttle_percentage=1.0\u0026html5_subsegment_readahead_timeout_secs=2.0\u0026html5_break_deadlocks=true\u0026html5_disable_webgl_antialias=true\u0026html5_disable_non_contiguous=true\u0026desktop_cleanup_companion_on_instream_begin=true\u0026html5_sticky_disables_variability=true\u0026html5_progressive_signature_reload=true\u0026html5_ad_stats_bearer=true\u0026html5_serverside_biscotti_id_wait_ms=1000\u0026tvhtml5_background_su=true\u0026player_unified_fullscreen_transitions=true\u0026playready_on_borg=true\u0026web_player_edge_autohide_killswitch2=true\u0026html5_no_shadow_env_data_redux=true\u0026html5_maximum_readahead_seconds=0.0\u0026html5_live_4k_more_buffer=true\u0026html5_ignore_bad_bitrates=true\u0026html5_variability_no_discount_thresh=1.0\u0026html5_adjust_effective_request_size=true\u0026safari_enable_spherical=true\u0026spacecast_uniplayer_decorate_manifest=true\u0026html5_tight_max_buffer_allowed_impaired_time=0.0\u0026html5_connect_timeout_secs=7.0\u0026send_html5_api_stats_ads_abandon=true\u0026lightweight_watch_video_swf=true\u0026html5_jumbo_ull_subsegment_readahead_target=1.0\u0026set_interstitial_advertisers_question_text=true\u0026html5_clearance_fix=true\u0026html5_disable_move_pssh_to_moov=true\u0026html5_restrict_streaming_xhr_on_sqless_requests=true\u0026html5_live_ultra_low_latency_bandwidth_window=0.0\u0026html5_streaming_xhr_buffer_mdat=true\u0026html5_use_has_subfragmented_fmp4=true\u0026html5_min_upgrade_health=0\u0026enable_mesh_ps4=true\u0026embed_show_watchlater_login=true\u0026html5_ad_no_buffer_abort_after_skippable=true\u0026html5_strip_emsg=true\u0026html5_player_autonav_logging=true\u0026dynamic_ad_break_pause_threshold_sec=0\u0026player_destroy_old_version=true\u0026show_interstitial_for_3s=true\u0026mweb_cougar_big_controls=true\u0026html5_defer_background_errors=true\u0026html5_license_constraint_delay=5000\u0026html5_live_abr_repredict_fraction=0.0\u0026html5_get_video_info_promiseajax=true\u0026html5_manifestless_no_redundant_seek_to_head=true\u0026html5_live_probe_primary_host=true\u0026html5_enable_embedded_player_visibility_signals=true\u0026html5_aux_pctile=true\u0026html5_variability_full_discount_thresh=3.0\u0026html5_live_normal_latency_bandwidth_window=0.0\u0026html5_disable_urgent_upgrade_for_quality=true\u0026html5_vp9_live_blacklist_edge=true\u0026html5_pause_video_fix=true\u0026html5_post_interrupt_readahead=20\u0026html5_error_reload_cooldown_ms=30000\u0026html5_ignore_public_setPlaybackQuality=true\u0026doubleclick_gpt_retagging=true\u0026html5_min_secs_over_max_bytes=true\u0026use_html5_player_event_timeout=true\u0026disable_indisplay_adunit_on_embedded=true\u0026html5_enable_mesh_projection=true\u0026html5_max_buffer_health_for_downgrade=15\u0026mweb_muted_autoplay_animation=shrink\u0026html5_max_buffer_duration=0\u0026html5_serverside_call_server_on_biscotti_error=true\u0026html5_throttle_burst_secs=15.0\u0026html5_pipeline_ultra_low_latency=true\u0026html5_max_av_sync_drift=50\u0026html5_suspend_manifest_on_pause=true\u0026allow_live_autoplay=true\u0026interaction_click_on_gel_web=true\u0026html5_suspend_loader=true\u0026html5_bandwidth_window_size=0\u0026html5_vp9_live_whitelist=true\u0026html5_max_readahead_bandwidth_cap=0\u0026html5_reselect_bandwidth_factor=3.0\u0026variable_load_timeout_ms=0\u0026html5_timeupdate_readystate_check=true\u0026html5_deferred_source_buffer_creation=true\u0026sdk_ad_prefetch_time_seconds=-1\u0026html5_streaming_xhr_optimize_lengthless_mp4=true\u0026mweb_playsinline_webview=true\u0026html5_min_readbehind_cap_secs=0\u0026html5_variability_discount=0.5\u0026html5_nnr_downgrade_count=4\u0026html5_subsegment_readahead_target_buffer_health_secs=0.5\u0026player_external_control_on_classic_desktop=true\u0026html5_spherical_bicubic_mode=1\u0026html5_manifestless_captions=true\u0026html5_background_cap_idle_secs=60\u0026uniplayer_dbp=true\u0026html5_background_quality_cap=360\u0026html5_composite_stall=true\u0026html5_min_startup_smooth_target=10.0","hl":"en_US","external_play_video":"1"},"assets":{"css":"\/yts\/cssbin\/player-vfl8_2Wov\/www-player.css","js":"\/yts\/jsbin\/player-vflVZNDz1\/en_US\/base.js"}};ytplayer.load = function() {yt.player.Application.create("player-api", ytplayer.config);ytplayer.config.loaded = true;};</script>
    <?php } ?>
    <script src="/js/main.js"></script>
    <?php if($Profile["channel_version"] == 3) { ?>    
    <script src="/js/2012/main.js"></script>
    <?php } ?>
</body>
</html>