<?php
require_once $_SERVER['DOCUMENT_ROOT']."/_includes/init.php";

//REQUIREMENTS / PERMISSIONS
//- Requires ($_POST["user"]) AND ($_POST["page"]) AND ($_POST["type"])
if (!isset($_POST["user"]) || !isset($_POST["page"]) || !isset($_POST["type"])) { exit(); }


$From_User  = $_POST["user"];
$Type       = $_POST["type"];
$Page       = (int)$_POST["page"];

$From       = ($Page * 16);


if ($Type == "videos") {

    $Videos                     = new Videos($DB, $_USER);
    $Videos->WHERE_P            = ["uploaded_by" => $From_User];
    $Videos->ORDER_BY           = "uploaded_on DESC";
    $Videos->Shadowbanned_Users = true;
    $Videos->LIMIT              = $From. ", 16";
    $Videos->get();

    if ($Videos::$Videos) {
        $Videos = $Videos->fixed();

        $Videos_Amount                      = new Videos($DB, $_USER);
        $Videos_Amount->WHERE_P             = ["uploaded_by" => $From_User];
        $Videos_Amount->Shadowbanned_Users  = true;
        $Videos_Amount->Count               = true;
        $Videos_Amount                      = $Videos_Amount->get();

    } else {

        $Videos = false;

    }


    if ((16 * ($Page + 1)) < $Videos_Amount) { $Show_More = '<center><button id="show_more" onclick="show_more(\'videos\','.($Page + 1).')">Show More</button></center>'; } else { $Show_More = ""; }

    foreach($Videos as $Video) {
        ?>
            <div class="mnu_vid" watch="<? if ($Video["privacy"] == 0) : ?><?= $Video["url"] ?><? endif ?>">
                <a href="javascript:void(0)"><img <?= $Video["thumbnail"] ?> width="100" height="75"></a>
                <div>
                    <a href="javascript:void(0)"><?= htmlspecialchars($Video["title"]) ?></a>
                    <span><a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a> - <?= number_format($Video["views"]) ?> views</span>
                </div>
            </div>
        <?
    }
    echo $Show_More;
    echo "</div>";
} elseif ($Type == "favorites") {

    $Videos = $DB->execute("SELECT videos.title, videos.url, videos.uploaded_by, videos.displayviews as views FROM video_favorites INNER JOIN videos ON video_favorites.url = videos.url WHERE video_favorites.favorite_by = :FROMUSER AND videos.status='2' ORDER BY video_favorites.date DESC LIMIT $From, 16", false, [":FROMUSER" => $From_User]);

    $Videos                     = new Videos($DB, $_USER);
    $Videos->JOIN               = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
    $Videos->Shadowbanned_Users = true;
    $Videos->Banned_Users       = true;
    $Videos->Private_Videos     = true;
    $Videos->Unlisted_Videos    = true;
    $Videos->ORDER_BY           = "video_favorites.date DESC";
    $Videos->WHERE_P            = ["video_favorites.favorite_by" => $From_User];
    $Videos->LIMIT              = $From.", 16";
    $Videos->get();

    if ($Videos::$Videos) {

        $Videos = $Videos->fixed();

        $Videos_Amount                              = new Videos($DB, $_USER);
        $Videos_Amount->JOIN                        = "RIGHT JOIN video_favorites ON video_favorites.url = videos.url";
        $Videos_Amount->Shadowbanned_Users          = true;
        $Videos_Amount->Banned_Users                = true;
        $Videos_Amount->Private_Videos              = true;
        $Videos_Amount->Unlisted_Videos             = true;
        $Videos_Amount->Count                       = true;
        $Videos_Amount->Count_Column                = "video_favorites.url";
        $Videos_Amount->WHERE_P                     = ["video_favorites.favorite_by" => $From_User];
        $Videos_Amount                              = $Videos_Amount->get();

    } else {

        $Videos = false;

    }

    if ((16 * ($Page + 1)) < $Videos_Amount) { $Show_More = '<center><button id="show_more" onclick="show_more(\'favorites\','.($Page + 1).')">Show More</button></center>'; } else { $Show_More = ""; }

    foreach($Videos as $Video) {
        ?>
                <div class="mnu_vid" watch="<? if ($Video["privacy"] == 0) : ?><?= $Video["url"] ?><? endif ?>"">
                    <a href="javascript:void(0)"><img <?= $Video["thumbnail"] ?> width="100" height="75"></a>
                            <div>
                                <a href="javascript:void(0)"><?= htmlspecialchars($Video["title"]) ?></a>
                                <span><a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a> - <?= number_format($Video["views"]) ?> views</span>
                            </div>
                </div>
        <?
    }
    echo $Show_More;
    echo "</div>";
}