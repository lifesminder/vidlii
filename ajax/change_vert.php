<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires Login
//- Requires ($_POST["vert"]) AND $_POST["type"]
if (!$_USER->logged_in)       { exit(); }
if (!isset($_POST["vert"]))   { exit(); }
if (!isset($_POST["type"]))   { exit(); }


$Modules = strtolower($_POST["vert"]);


$Modules_Array = explode(",",$_POST["vert"]);

$New_Modules = "";
$Already     = array();

foreach ($Modules_Array as $Module) {
    if (($Module == "cu" || $Module == "re" || $Module == "ft" || $Module == "s2" || $Module == "s1" || $Module == "fr" || $Module == "co") && !in_array($Module,$Already)) {
        $New_Modules .= $Module.",";
        $Already[]   = $Module;
    } else {
        $New_Modules = "cu,re,ft,s2,s1,fr,co,";
        goto a;
    }
}
a:
echo $New_Modules;

$New_Modules = substr($New_Modules,0,strlen($New_Modules) - 1);

if ($_POST["type"] == 0) {
    $Execute = $DB->modify("UPDATE users SET modules_vertical_r = :MODULES WHERE username = :USERNAME",
                          [
                              ":MODULES"    => $New_Modules,
                              ":USERNAME"   => $_USER->username
                          ]);
} else {
    $Execute = $DB->modify("UPDATE users SET modules_vertical_l = :MODULES WHERE username = :USERNAME",
                          [
                              ":MODULES"    => $New_Modules,
                              ":USERNAME"   => $_USER->username
                          ]);
}