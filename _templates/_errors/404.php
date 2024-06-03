<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

    $Page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    if (ctype_alnum($Page)) {
        $Username = $DB->execute("SELECT displayname FROM users WHERE displayname = :USERNAME", true, [":USERNAME" => substr($Page, 0, 20)]);
        if ($DB->RowNum == 1) {
            $Username = $Username["displayname"];
            redirect("/user/$Username"); exit();
        }
		
        $Username = $DB->execute("SELECT u.displayname FROM users_oldnames r, users u WHERE r.displayname = :USERNAME AND u.username = r.username LIMIT 1", true, [":USERNAME" => substr($Page, 0, 20)]);
        if ($DB->RowNum == 1) {
            $Username = $Username["displayname"];
            redirect("/user/$Username"); exit();
        }
    }

    $Featured_Videos = $DB->execute("SELECT videos.title, videos.length, videos.1_star, videos.2_star, videos.3_star, videos.4_star, videos.5_star, videos.url, videos.views, videos.uploaded_by, users.displayname FROM videos, users WHERE videos.featured = 1 AND users.username = videos.uploaded_by ORDER BY rand() DESC LIMIT 3");

function video_thumbnail2($URL,$LENGTH,$Width,$Height,$Title = NULL) {
    if (!empty($LENGTH) || $LENGTH == "0") { $Length = seconds_to_time((int)$LENGTH); } else { $Length = $LENGTH; }
    if (file_exists($_SERVER['DOCUMENT_ROOT']."/usfi/thmp/$URL.jpg")) { $Thumbnail = "../usfi/thmp/$URL.jpg"; } else { $Thumbnail = "/img/no_th.jpg"; }

    return '<div class="th"><div class="th_t">'.$Length.'</div><a href="/watch?v='.$URL.'"><img class="vid_th" src="'.$Thumbnail.'" width="'.$Width.'" height="'.$Height.'"></a></div>';
}
?>
<!doctype html>
<html>
<head>
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" type="text/css" href="<?= CSS_FILE ?>">
    <? $_THEMES->load_themes() ?>
</head>
<body>
<div class="wrapper">
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/header.php"; ?>
    <div style="width:90%;margin:37px auto 0">
        <div style="width:45%;float:left">
            <img src="/img/404.png" style="width:344px;position:relative;top:9px">
        </div>
        <div style="float:left;width:55%">
            <div style="font-size:33px;font-weight:bold;text-align:center;color:#404040">404 - Page Not Found</div>
            <div style="font-size:23px;font-weight:bold;text-align:center;color:#606060">Sorry about that. :(</div>

            <div class="wdg" id="ft_widget" style="margin-top:15px">
                <div style="background:#dae9fe">
                    <img src="/img/ft.png" alt="Featured Videos"><span>Featured Videos</span>
                </div>
                <div>
                    <div class="v_v_bx">
                        <div style="margin-right:15px">
                            <?= video_thumbnail2($Featured_Videos[0]["url"],$Featured_Videos[0]["length"],140,88) ?>
                            <a href="/watch?v=<?= $Featured_Videos[0]["url"] ?>" class="ba" style="height:2.2em"><?= $Featured_Videos[0]["title"] ?></a>
                            <div class="vw s"><?= number_format($Featured_Videos[0]["views"]) ?> views</div>
                            <a href="/user/<?= $Featured_Videos[0]["displayname"] ?>" class="ch_l s"><?= $Featured_Videos[0]["displayname"] ?></a>
                            <div class="s_r"><?= show_ratings($Featured_Videos[0],14,13) ?></div>
                        </div>
                        <div style="margin-right:15px">
                            <?= video_thumbnail2($Featured_Videos[1]["url"],$Featured_Videos[1]["length"],140,88) ?>
                            <a href="/watch?v=<?= $Featured_Videos[1]["url"] ?>" class="ba" style="height:2.2em"><?= $Featured_Videos[1]["title"] ?></a>
                            <div class="vw s"><?= number_format($Featured_Videos[1]["views"]) ?> views</div>
                            <a href="/user/<?= $Featured_Videos[1]["displayname"] ?>" class="ch_l s"><?= $Featured_Videos[1]["displayname"] ?></a>
                            <div class="s_r"><?= show_ratings($Featured_Videos[0],14,13) ?></div>
                        </div>
                        <div style="margin-right:0">
                            <?= video_thumbnail2($Featured_Videos[2]["url"],$Featured_Videos[2]["length"],140,88) ?>
                            <a href="/watch?v=<?= $Featured_Videos[2]["url"] ?>" class="ba" style="height:2.2em"><?= $Featured_Videos[2]["title"] ?></a>
                            <div class="vw s"><?= number_format($Featured_Videos[2]["views"]) ?> views</div>
                            <a href="/user/<?= $Featured_Videos[2]["displayname"] ?>" class="ch_l s"><?= $Featured_Videos[2]["displayname"] ?></a>
                            <div class="s_r"><?= show_ratings($Featured_Videos[2],14,13) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/footer.php"; ?>
</div>
</body>
</html>
