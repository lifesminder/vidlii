<!doctype html>
<html lang="en">
<head>
<script>adblock_installed = false; zd = false; </script>
    <title><?= $Page_Title ?> - VidLii Admin</title>
    <link rel="stylesheet" type="text/css" href="<?= CSS_FILE ?>">
    <!-- script src='https://www.google.com/recaptcha/api.js'></script -->
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
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php" ?>
    <? if (isset($_SESSION["notification"])) : ?>
        <div style="border: 1.5px solid <?= $_SESSION["n_color"] ?>;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold"><?= $_SESSION["notification"] ?></div>
        <? unset($_SESSION["notification"]); unset($_SESSION["n_color"]); ?>
    <? endif ?>
    <main class="bottom_wrapper">
        <div style="word-spacing:20px;text-align:center;padding-bottom:8px;margin-bottom:13px;border-bottom:1px solid #e2e2e2">
            <a href="/admin/dashboard"<? if ($Page == "dashboard") : ?> style="font-weight:bold;color:black"<? endif ?>>Dashboard</a> <? if ($_USER->Is_Admin) : ?>| <a href="/admin/statistics"<? if ($Page == "statistics") : ?> style="font-weight:bold;color:black"<? endif ?>>Charts</a> <? endif ?>| <a href="/admin/users"<? if ($Page == "Users") : ?> style="font-weight:bold;color:black"<? endif ?>>Users</a> | <a href="/admin/videos" <? if ($Page == "Videos") : ?> style="font-weight:bold;color:black"<? endif ?>>Videos</a> | <a href="/admin/misc" <? if ($Page == "misc") : ?> style="font-weight:bold;color:black"<? endif ?>>Misc</a>
        </div>
        <? require_once $_SERVER['DOCUMENT_ROOT']."/admin/_templates/$Page.php" ?>
    </main>
</div>
<script src="<?= MAIN_JS_FILE ?>"></script>
</body>
</html>
