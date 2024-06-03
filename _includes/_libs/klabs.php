<?php

// evil cache
header('cache-control: no-cache,no-store,must-revalidate');
header('pragma: no-cache');
header('expires: 0');

function auto_link_callback($matches){
	return (strtolower($matches[3]) == "</a>") ? $matches[0] : preg_replace('/([a-zA-Z]+)(:\/\/[\w\+\$\;\?\.\{\}%,!#~*\/:@&=_-]+)/u', '<a href="$1$2" target="_blank" rel="nofollow noreferrer">$1$2</a>', $matches[0]);
}

function auto_link($proto){
	$proto = preg_replace('|<br\s*/?>|',"\n",$proto);
	$proto = preg_replace_callback('/(>|^)([^<]+?)(<.*?>|$)/m','auto_link_callback',$proto);
	return str_replace("\n",'<br />',$proto);
}

function baniphostdnsblcheck($IP, $HOST, &$baninfo){
	$BANPATTERN = array(); // IP/Hostname
	$DNSBLservers = array(0, 'sbl-xbl.spamhaus.org', 'list.dsbl.org', 'bl.blbl.org', 'bl.spamcop.net'); 
	$DNSBLWHlist = array(); // DNSBL whitelist

	// IP/Hostname Check
	$HOST = strtolower($HOST);
	$checkTwice = ($IP != $HOST);
	$IsBanned = false;
	foreach($BANPATTERN as $pattern){
		$slash = substr_count($pattern, '/');
		if($slash==2){ // RegExp
			$pattern .= 'i';
		}elseif($slash==1){ // CIDR Notation
			if(match_cidr($IP, $pattern)){ $IsBanned = true; break; }
			continue;
		}elseif(strpos($pattern, '*')!==false || strpos($pattern, '?')!==false){ // Wildcard
			$pattern = '/^'.str_replace(array('.', '*', '?'), array('\.', '.*', '.?'), $pattern).'$/i';
		}else{ // Full-text
			if($IP==$pattern || ($checkTwice && $HOST==strtolower($pattern))){ $IsBanned = true; break; }
			continue;
		}
		if(preg_match($pattern, $HOST) || ($checkTwice && preg_match($pattern, $IP))){ $IsBanned = true; break; }
	}
	if($IsBanned){ $baninfo = 'Listed in IP/Hostname Blacklist'; return true; }

	if(!$DNSBLservers[0]) return false; // Skip check
	if(array_search($IP, $DNSBLWHlist)!==false) return false;
	$rev = implode('.', array_reverse(explode('.', $IP)));
	$lastPoint = count($DNSBLservers) - 1; if($DNSBLservers[0] < $lastPoint) $lastPoint = $DNSBLservers[0];
	$isListed = false;
	for($i = 1; $i <= $lastPoint; $i++){
		$query = $rev.'.'.$DNSBLservers[$i].'.'; // FQDN
		$result = gethostbyname($query);
		if($result && ($result != $query)){ $isListed = $DNSBLservers[$i]; break; }
	}
	if($isListed){ $baninfo = "Listed in DNSBL($isListed) Blacklist"; return true; }
	return false;
}
function match_cidr($addr, $cidr) {
	list($ip, $mask) = explode('/', $cidr);
	return (ip2long($addr) >> (32 - $mask) == ip2long($ip.str_repeat('.0', 3 - substr_count($ip, '.'))) >> (32 - $mask));
}

function getremoteaddr_cloudflare() {
    $addr = $_SERVER['REMOTE_ADDR'];
    $cloudflare_v4 = array('199.27.128.0/21', '173.245.48.0/20', '103.21.244.0/22', '103.22.200.0/22', '103.31.4.0/22', '141.101.64.0/18', '108.162.192.0/18', '190.93.240.0/20', '188.114.96.0/20', '197.234.240.0/22', '198.41.128.0/17', '162.158.0.0/15', '104.16.0.0/12');
    $cloudflare_v6 = array('2400:cb00::/32', '2606:4700::/32', '2803:f800::/32', '2405:b500::/32', '2405:8100::/32');

    if(filter_var($addr, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)) { //v4 address
        foreach ($cloudflare_v4 as &$cidr) {
            if(match_cidr($addr, $cidr)) {
                return $_SERVER['HTTP_CF_CONNECTING_IP'];
            }
        }
    } else { // v6 address
        foreach ($cloudflare_v6 as &$cidr) {
            if(match_cidrv6($addr, $cidr)) {
                return $_SERVER['HTTP_CF_CONNECTING_IP'];
            }
        }
    }
    return '';
}

function getremoteaddr_openshift() {
    if (isset($_ENV['OPENSHIFT_REPO_DIR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return '';
}

function getremoteaddr_proxy() {
    global $PROXYHEADERlist;

    if (!defined('TRUST_HTTP_X_FORWARDED_FOR') || !TRUST_HTTP_X_FORWARDED_FOR) {
        return '';
    }
    $ip='';
    $proxy = $PROXYHEADERlist;

	foreach ($proxy as $key) {
		if (array_key_exists($key, $_SERVER)) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				$ip = trim($ip);
				if (filter_var($ip, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4 |FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !==false) {
					return $ip;
				}
			}
		}
	}

    return '';
}

function getremoteaddr(){
	static $ip_cache;
	if ($ip_cache) return $ip_cache;

    $ipCloudFlare = getremoteaddr_cloudflare();
    if (!empty($ipCloudFlare)) {
        return $ip_cache = $ipCloudFlare;
    }

    $ipOpenShift = getremoteaddr_openshift();
    if (!empty($ipOpenShift)) {
        return $ip_cache = $ipOpenShift;
    }

    $ipProxy = getremoteaddr_proxy();
    if (!empty($ipProxy)) {
        return $ip_cache = $ipProxy;
    }

    return $ip_cache = $_SERVER['REMOTE_ADDR'];
}

function anti_sakura($str){
	return preg_match('/[\x{E000}-\x{F848}]/u', $str);
}

/* Error */
function error($title='ERROR', $title2='Something went wrong', $description='') {
	?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?=$title?></title>
		<meta name="robots" content="nofollow,noarchive" />
		<!-- META -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<!-- EVIL CACHE -->
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="pragma" content="no-cache" />
		<!-- STYLE -->
		<link type="text/css" rel="stylesheet" href="common.css" media="all" />
		<style>
body {
	background-image: url("bloodybrickwall.png");
	color: #000;
	font-size: 90%;
}

.doc {
	background-color: #211A;
	color: #EEE;
	padding: 0.5em 0.2em;
}

a { color: #77F; }
a:hover { color: #F77; }
		</style>
	</head>
	<body dir="ltr" bgcolor="#F00" text="#000">
		<div id="upper" align="RIGHT">
			[<a href="javascript:void(0);" onclick="history.back();">Return</a>]
		</div>
		<h1><?=$title2?></h1>
		<?=$description?"<p class=\"doc\">$description</p>":'<!--NO DESCRIPTION-->'?>
	</body>
</html><?php

	exit;
}

/* MySQLi functions */

function HTM_sqltable($result, $fieldtl=[], $input='') {
	mysqli_data_seek($result, 0);
	mysqli_field_seek($result, 0);
	$htm = '<table class="n_sql n_table" border="1" cellspacing="0"><thead><tr>';
	if ($input) {
		$htm.= '<th></th>';
	}
	while ($field=mysqli_fetch_field($result)) {
		$htm.= '<th class="n_col n_col_'.$field->name.'"><nobr>'.
			($fieldtl[$field->name]??('<small>'.ucfirst($field->name).'</small>')).'</nobr></th>';
	}
	$htm.= '</tr></thead><tbody>';
	while ($ass=mysqli_fetch_assoc($result)) {
		$htm.= '<tr>';
		if ($input) {
			$htm.= '<td><input type="checkbox" name="'.$ass[$input].'" value="true" /></td>';
		}
		foreach ($ass as $key=>$val) {
			$htm.= "<td class=\"n_col n_col_$key\">$val</td>";
		}
		$htm.= '</tr>';
	}
	$htm.= '</tbody></table>';
	return $htm;
}

/* HTML functions */
function HTM_redirect($to, $time=0) {
	if($to=='back') {
		$to = $_SERVER['HTTP_REFERER']??'';
	}
	$tojs = $to==($_SERVER['HTTP_REFERER']??'') ? 'history.go(-1);' : "location.href=\"$to\";";
	?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Redirecting...</title>
		<meta name="robots" content="nofollow,noarchive" />
		<!-- META -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<!-- EVIL CACHE -->
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="pragma" content="no-cache" />
		<!-- SCRIPT -->
		<meta http-equiv="refresh" content="<?=$time+1?>;URL=<?=$to?>" />
		<script>
setTimeout(function(){<?=$tojs?>}, <?=$time*1000?>);
		</script>
	</head>
	<body>
		Redirecting...
		<p>If your browser doesn't redirect for you, please click: <a href="<?=$to?>" onclick="event.preventDefault();<?=$tojs?>">Go</a></p>
	</body>
</html><?php
	exit;
}

?>
