<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php";
    ini_set( 'session.cookie_httponly', 1 );
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

    // initializing environment
    $env = \Dotenv\Dotenv::createImmutable($_SERVER["DOCUMENT_ROOT"]);
    $env->load();
	
    // Constraints
    define("ROOT_FOLDER", $_SERVER["DOCUMENT_ROOT"]);

    define("UPLOAD_LIMIT",      1024 * 1024 * 1024 * 2.01);
    define("ALLOWED_FORMATS",   ["FLV","MP4","WMV","AVI","MOV","M4V","MPG","MPEG","WEBM","MOV","MKV","3GP"]);

    define("ADMIN_PASSWORD",    "poops");

    define("DB_HOST", $_ENV["host"]);
    define("DB_DATABASE", $_ENV["db"]);
    define("DB_USER", $_ENV["username"]);
    define("DB_PASSWORD", (isset($_ENV["password"]) && $_ENV["password"] != "") ? $_ENV["password"] : "");
    define("DB_CHARSET", "utf8");

    define("CSS_FILE",          "/css/m.css?8");
    define("PROFILE_CSS_FILE",  "/css/profile.css?5");
    define("COSMIC_CSS_FILE",   "/css/cosmicpanda.css?5");
    define("PROFILE_JS_FILE",   "/js/profile.js?9");
    define("COSMIC_JS_FILE",    "/js/cosmicpanda.js?3");
    define("MAIN_JS_FILE",      "/js/main3.js?22");

    // AUTOLOAD CLASSES
    spl_autoload_register(function ($class) { include ROOT_FOLDER."/_includes/_classes/" . $class . ".class.php"; });
    require_once ROOT_FOLDER."/_includes/PHPfunctions.php";
    
    // create folders
    if(!is_dir(ROOT_FOLDER."/cache")) mkdir(ROOT_FOLDER."/cache", 0777, true);
    if(!is_dir(ROOT_FOLDER."/usfi")) {
        mkdir(ROOT_FOLDER."/usfi", 0777, true);
        mkdir(ROOT_FOLDER."/usfi/v/", 0777, true);
        mkdir(ROOT_FOLDER."/usfi/thmp/", 0777, true);
        mkdir(ROOT_FOLDER."/usfi/conv/", 0777, true);
        mkdir(ROOT_FOLDER."/usfi/conv_2/", 0777, true);
        mkdir(ROOT_FOLDER."/usfi/prvw/", 0777, true);
    }

    session_start(["cookie_lifetime" => 0, "gc_maxlifetime" => 455800]);
    if (!isset($_SESSION["sec_actions"])) $_SESSION["sec_actions"] = 0;
    if (isset($_COOKIE["css"]) && $_COOKIE["css"] == "deleted") setcookie("css", null, -1);

    // SETUP CLASSES
    $DB = new \Vidlii\Vidlii\DB(false);
    $_USER = new User(NULL,$DB,true);
    $_PAGE = new Page();
    $_GUMP = new GUMP();
    $_THEMES = new Themes($DB, $_USER);

    // New classes, intended to replace previous ones
    $db = new \Vidlii\Vidlii\DB($_SERVER["DOCUMENT_ROOT"]);

    if($_USER->logged_in && (!isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
        require_once ROOT_FOLDER."/_includes/inbox.php";
    }

    //VidLii Player Script/CSS Version
    $VLPVERSION = round(mt_rand(0,10000));

    $LOGO_VALUE = $DB->execute("SELECT value FROM settings WHERE name = 'logo'", true)["value"] ?? 0;

    if ($LOGO_VALUE == "0") { $LOGO_VALUE = "/img/Vidlii6.png"; } else { $LOGO_VALUE = "/img/$LOGO_VALUE.png"; }

    if (!isset($_SESSION["secret_id"])) {

        $_SESSION["secret_id"] = random_string("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", 14);

    }


    $_SESSION["ten2020Holiday"] = date("d", time());

    //Load for traffic regulation
    if ($_SESSION["ten2020Holiday"] > 20) { $_SESSION["ten2020Holiday"] = "Today"; }

    if ($_USER->logged_in && $_USER->username == "VidLssii") {
        session_destroy();
        $_USER->logout();
        redirect("/"); exit();
    }
?>