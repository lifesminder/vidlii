<!doctype html>
<html lang="en">
<head>
    <title><?= $Page_Title ?> - VidLii Admin</title>
    <link rel="stylesheet" type="text/css" href="<?= CSS_FILE ?>">
    <script src="/js/jquery.js"></script>
    <style>
        .panel_box {
            padding: 6px;
            border: 1px solid #dddddd;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 20px
        }

        .panel_box > strong:first-of-type {
            display: block;
            margin-bottom: 6px;
            font-size: 15px;
        }

        #user_search {
            padding: 0;
            margin: 0;
        }

        #user_search li {
            padding: 3px;
            margin: 0;
            list-style: none;
        }

        #user_search a {
            text-decoration: none;
            color: black;
        }

        #user_search li:hover {
            background-color: #cccccc;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <?php
        require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php";

        if(isset($_COOKIE["notification"])) {
            $_msg = explode(";", $_COOKIE["notification"]);
            $message = $_msg[0];
            $color = (count($_msg) > 1) ? $_msg[1] : null;
    ?>
        <div class="notification<?php echo ($color != "") ? " $color" : null; ?>">
            <?php echo $message; ?>
        </div>
    <?php
            setcookie('notification', '', -1, '/', $_SERVER['SERVER_NAME']); 
        }
    ?>
    <main class="bottom_wrapper">
        <div class="crumb-header">
            <a href="/my_account">My Account</a>
            <span class="spacer">/</span>
            Admin Panel
        </div>
        <div class="settings_menu">
            <a href="/admin/dashboard"<? if ($Page == "dashboard") : ?> id="nav_sel"<? endif ?>>Dashboard</a>
            <a href="/admin/statistics"<? if ($Page == "statistics") : ?> id="nav_sel"<? endif ?>>Charts</a>
            <a href="/admin/users"<? if ($Page == "Users") : ?> id="nav_sel"<? endif ?>>Users</a>
            <a href="/admin/videos"<? if ($Page == "Videos") : ?> id="nav_sel"<? endif ?>>Videos</a>
            <a href="/admin/misc"<? if ($Page == "misc") : ?> id="nav_sel"<? endif ?>>Misc</a>
        </div>
        <div class="subpage">
            <div class="subpage-content">
                <?php
                    if(isset($twig) && isset($twig_dest) && isset($twig_args))
                        echo "D";
                    else
                        require_once $_SERVER['DOCUMENT_ROOT']."/admin/_templates/$Page.php";
                ?>
            </div>
        </div>
    </main>
</div>
<script src="<?= MAIN_JS_FILE ?>"></script>
</body>
</html>
