<? if (!isset($_GET["v"])) : ?>
<style>
    .vid_th {
        border-radius: 0 !important;
        border: 0 !important;
        padding: 0;
        margin: 0;
        display: block;
        transform: scaleY(1.06);
        position: relative;
        top: 2px;
    }
    table.atable, .atable td,.atable th {
        border: 1.5px solid #ccc;
        text-align: left;
    }
    table.atable {
        border-collapse: collapse;
        width: 100%;
    }
    .atable th,.atable td {
        padding: 5px;
    }
</style>
<div>
    <div class="panel_box">
        <div style="overflow:hidden;margin-bottom:7px">
            <div style="float:left;position:relative;top:1.5px">
                <strong style="font-size:18px">Videos</strong>
                <form action="/admin/videos" method="GET" style="display:inline-block" onchange="(this).submit()">
                    <? if (isset($_GET["search"])) : ?>
                        <input type="hidden" name="search" value="<?= $_GET["search"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["ti"])) : ?>
                        <input type="hidden" name="ti" value="<?= $_GET["ti"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["da"])) : ?>
                        <input type="hidden" name="da" value="<?= $_GET["da"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["vi"])) : ?>
                        <input type="hidden" name="vi" value="<?= $_GET["vi"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["co"])) : ?>
                        <input type="hidden" name="co" value="<?= $_GET["co"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["ra"])) : ?>
                        <input type="hidden" name="ra" value="<?= $_GET["ra"] ?>">
                    <? endif ?>
                    <select name="n" style="padding:1px;position:relative;bottom:2px;left:6px">
                        <option value="16"<? if (isset($_GET["n"]) && $_GET["n"] == 16) : ?> selected<? endif ?>>16</option>
                        <option value="32"<? if (isset($_GET["n"]) && $_GET["n"] == 32) : ?> selected<? endif ?>>32</option>
                        <option value="64"<? if (isset($_GET["n"]) && $_GET["n"] == 64) : ?> selected<? endif ?>>64</option>
                        <option value="128"<? if (isset($_GET["n"]) && $_GET["n"] == 128) : ?> selected<? endif ?>>128</option>
                        <option value="256"<? if (isset($_GET["n"]) && $_GET["n"] == 256) : ?> selected<? endif ?>>256</option>
                        <option value="512"<? if (isset($_GET["n"]) && $_GET["n"] == 512) : ?> selected<? endif ?>>512</option>
                    </select>
                </form>
            </div>
            <div style="float:right">
                <form action="/admin/videos" method="GET">
                    <? if (isset($_GET["n"])) : ?>
                    <input type="hidden" name="n" value="<?= $_GET["n"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["ti"])) : ?>
                        <input type="hidden" name="ti" value="<?= $_GET["ti"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["da"])) : ?>
                        <input type="hidden" name="da" value="<?= $_GET["da"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["vi"])) : ?>
                        <input type="hidden" name="vi" value="<?= $_GET["vi"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["co"])) : ?>
                        <input type="hidden" name="co" value="<?= $_GET["co"] ?>">
                    <? endif ?>
                    <? if (isset($_GET["ra"])) : ?>
                        <input type="hidden" name="ra" value="<?= $_GET["ra"] ?>">
                    <? endif ?>
                    <input type="search" name="search"<? if (isset($_GET["search"])) : ?> value="<?= htmlspecialchars($_GET["search"], ENT_QUOTES) ?>"<? endif ?> maxlength="128" style="width:255px" placeholder="Search Videos..." required> <input type="submit" value="Search">
                </form>
            </div>
        </div>

        <div style="overflow-y:auto;max-height:455px">
            <table style="width:100%" cellspacing="0" cellpadding="0" class="atable">
                <thead>
                    <td><strong>Picture</strong></td>
                    <td><strong>Title</strong> <? if (!isset($_GET["ti"])) : ?><a href="/admin/videos?ti=1<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↔</a><? elseif ($_GET["ti"] != 0) : ?><a href="/admin/videos?ti=0<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↑</a><? elseif ($_GET["ti"] == 0) : ?><a href="/admin/videos<? if (isset($_GET["search"])) : ?>?search=<?= urlencode($_GET["search"]) ?><? endif ?>">↓</a><? endif ?></td>
                    <td><strong>Date</strong> <? if (!isset($_GET["da"])) : ?><a href="/admin/videos?da=1<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↔</a><? elseif ($_GET["da"] != 0) : ?><a href="/admin/videos?da=0<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↑</a><? elseif ($_GET["da"] == 0) : ?><a href="/admin/videos<? if (isset($_GET["search"])) : ?>?search=<?= urlencode($_GET["search"]) ?><? endif ?>">↓</a><? endif ?></td>
                    <td><strong>Views</strong> <? if (!isset($_GET["vi"])) : ?><a href="/admin/videos?vi=1<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↔</a><? elseif ($_GET["vi"] != 0) : ?><a href="/admin/videos?vi=0<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↑</a><? elseif ($_GET["vi"] == 0) : ?><a href="/admin/videos<? if (isset($_GET["search"])) : ?>?search=<?= urlencode($_GET["search"]) ?><? endif ?>">↓</a><? endif ?></td>
                    <td><strong>Comments</strong> <? if (!isset($_GET["co"])) : ?><a href="/admin/videos?co=1<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↔</a><? elseif ($_GET["co"] != 0) : ?><a href="/admin/videos?co=0<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↑</a><? elseif ($_GET["co"] == 0) : ?><a href="/admin/videos<? if (isset($_GET["search"])) : ?>?search=<?= urlencode($_GET["search"]) ?><? endif ?>">↓</a><? endif ?></td>
                    <td><strong>Ratings</strong> <? if (!isset($_GET["ra"])) : ?><a href="/admin/videos?ra=1<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↔</a><? elseif ($_GET["ra"] != 0) : ?><a href="/admin/videos?ra=0<? if (isset($_GET["search"])) : ?>&search=<?= urlencode($_GET["search"]) ?><? endif ?>">↑</a><? elseif ($_GET["ra"] == 0) : ?><a href="/admin/videos<? if (isset($_GET["search"])) : ?>?search=<?= urlencode($_GET["search"]) ?><? endif ?>">↓</a><? endif ?></td>
                    <td><strong>By</strong></td>
                    <td><strong>Edit</strong></td>
                </thead>
                <? foreach ($Videos as $Video) : ?>
                <tr>
                    <td width="100px" style="padding:0"><?= video_thumbnail2($Video["url"],$Video["length"],100,66) ?></td>
                    <td valign="center"><a href="/watch?v=<?= $Video["url"] ?>" class="ln2" style="font-weight:bold;position:relative;top:9px"><?= cut_string($Video["title"],36) ?></a></td>
                    <td style="text-align:center"><?= get_time_ago($Video["uploaded_on"]) ?></td>
                    <td style="text-align:center"><?= number_format($Video["views"]) ?></td>
                    <td style="text-align:center"><?= number_format($Video["comments"]) ?></td>
                    <td style="text-align:center"><?= number_format(($Video["1_star"] + $Video["2_star"] + $Video["3_star"] + $Video["4_star"] + $Video["5_star"])) ?></td>
                    <td style="text-align:center"><a href="/admin/users?u=<?= $Video["uploaded_by"] ?>"><?= $Video["uploaded_by"] ?></a></td>
                    <td style="text-align:center"><a href="/admin/videos?v=<?= $Video["url"] ?>" style="font-weight:bold">Edit!</a></td>
                </tr>
                <? endforeach ?>
            </table>
        </div>
    </div>
</div>
    <div style="width:48%;margin-right:2%;float:left;">
    <div class="panel_box">
        <strong>Edit Video</strong>
        <form action="/admin/videos" method="POST">
            <div style="width:320px;margin:0 auto">
                <div style="width:250px;float:left;margin-right:15px">
                    <input type="text" style="width:100%" name="v" placeholder="Enter Video URL" >
                </div>
                <input type="submit" value="Edit" style="float:left" name="search_video" class="search_button">
            </div>
        </form>
    </div>
</div>
<div style="width:50%;float:left;">
    <div class="panel_box" style="overflow-y:auto;max-height:450px">
        <strong>Recent Ratings</strong>
        <? foreach ($Ratings as $Rating) : ?>
            <div style="margin-bottom:5px">
                <a href="/user/<?= $Rating["user_rated"] ?>" style="font-weight:bold"><?= $Rating["user_rated"] ?></a> rated <a href="/watch?v=<?= $Rating["url"] ?>" style="font-weight:bold"><?= cut_string($Rating["title"],25) ?></a> with <strong><?= $Rating["stars"] ?> Stars</strong>
            </div>
        <? endforeach ?>
    </div>
    <div class="panel_box" style="overflow-y:auto;max-height:450px">
        <strong>Recent Favorites</strong>
        <? foreach ($Favorites as $Favorite) : ?>
            <div style="margin-bottom:5px">
                <a href="/user/<?= $Favorite["favorite_by"] ?>" style="font-weight:bold"><?= $Favorite["favorite_by"] ?></a> favorited <a href="/watch?v=<?= $Favorite["url"] ?>" style="font-weight:bold"><?= cut_string($Favorite["title"],25) ?></a>
            </div>
        <? endforeach ?>
    </div>
</div>
<? else : ?>
    <div style="padding-bottom:11px;margin-bottom:11px;border-bottom:1px solid #e2e2e2;overflow:hidden">
        <div style="float:left">
            <?= video_thumbnail2($Video["url"],$Video["length"],142,90) ?>
        </div>
        <div style="float:left;margin-left:8px;position:relative;bottom:1px">
            <a href="/watch?v=<?= $Video["url"] ?>" style="font-weight: bold"><?= $Video["title"] ?></a><br>
            Uploaded on: <?= get_date($Video["uploaded_on"]) ?><br>
            Uploaded by: <a href="/user/<?= $Video["uploaded_by"] ?>"><?= $Video["displayname"] ?></a><br>
            Views: <?= number_format($Video["views"]) ?><br>
            Comments: <?= number_format($Video["comments"]) ?><br>
            Rating: <? if (($Video["1_star"] + $Video["2_star"] + $Video["3_star"] + $Video["4_star"] + $Video["5_star"]) > 0) : ?> <?= ($Video["1_star"] + $Video["2_star"] * 2 +$Video["3_star"] * 3 + $Video["4_star"] * 4 + $Video["5_star"] * 5) / ($Video["1_star"] + $Video["2_star"] + $Video["3_star"] + $Video["4_star"] + $Video["5_star"]) ?><? else : ?>0<? endif ?>
                </div>
        <form action="/admin/videos?v=<?= $Video["url"] ?>" method="POST">
            <div style="float:left;margin-left:225px">
                <input type="submit" name="delete_video" value="Delete Video" style="padding: 7px 55px;position:relative;top:30px">
            </div>
        </form>
    </div>
    <form action="/admin/videos?v=<?= $Video["url"] ?>" method="POST">
        <div style="float:left;width:49%;padding-right:1%;border-right:1px solid #ccc">
            <table cellpadding="4">
                <tr>
                    <td>Title:</td>
                    <td><input type="text" name="video_title" style="width:250px" value="<?= htmlspecialchars($Video["title"],ENT_QUOTES) ?>"></td>
                </tr>
                <tr>
                    <td valign="top">Description:</td>
                    <td><textarea name="video_description" rows="6" style="resize:vertical;border-radius:4px;border:1px solid #d5d5d5;width:300px"><?= $Video["description"] ?></textarea></td>
                </tr>
                <tr>
                    <td>Tags:</td>
                    <td><input type="text" name="video_tags" style="width:250px" value="<?= htmlspecialchars($Video["tags"],ENT_QUOTES) ?>"></td>
                </tr>
                <tr>
                    <td>Category:</td>
                    <td>
                        <select name="video_category">
                            <? foreach ($Categories as $Category => $ID) : ?>
                                <option value="<?= $Category ?>" <? if ($Video["category"] == $Category) : ?>selected<? endif ?>><?= $ID ?></option>
                            <? endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Upload Date:</td>
                    <td>
                        <select name="month">
                            <? foreach($Months as $item => $value) : ?>
                                <option value="<?= $value ?>"<? if ($value == $Month) : ?> selected<? endif ?>><?= $item ?></option>
                            <?php endforeach ?>
                        </select>
                        <select name="day">
                            <? for ($x = 1; $x <= 31; $x++) : ?>
                                <option value="<?= $x ?>"<? if ($x == $Day) : ?> selected<? endif ?>><?= $x ?></option>
                            <? endfor ?>
                        </select>
                        <select name="year">
                            <? for($x = date("Y");$x >= 1910;$x--) : ?>
                                <option value="<?= $x ?>"<? if ($x == $Year) : ?> selected<? endif ?>><?= $x ?></option>
                            <? endfor ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div style="float:left;width:48%;padding-left:1%">
            <table cellpadding="4">
                <tr>
                    <td>Featured:</td>
                    <td>
                        <select name="featured">
                            <option value="0"<? if ($Video["featured"] == 0) : ?> selected<? endif ?>>False</option>
                            <option value="1"<? if ($Video["featured"] == 1) : ?> selected<? endif ?>>True</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        <select disabled>
                            <option<? if ($Video["status"] == 0) : ?> selected<? endif ?>>Uploading</option>
                            <option<? if ($Video["status"] == 1) : ?> selected<? endif ?>>Converting</option>
                            <option<? if ($Video["status"] == 2) : ?> selected<? endif ?>>Live</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Watch Time:</td>
                    <td>
                        <input <? if ($_USER->Is_Mod) : ?>disabled<? endif ?> type="number" name="watchtime" value="<?= $Video["watched"] ?>" style="padding: 3px 4px;border-radius: 3px;outline: 0;width:111px;border: 1px solid #d5d5d5">
                    </td>
                </tr>
                <tr>
                    <td>Real Views:</td>
                    <td>
                        <input <? if ($_USER->Is_Mod) : ?>disabled<? endif ?> type="number" name="video_views" value="<?= $Video["views"] ?>" style="padding: 3px 4px;border-radius: 3px;outline: 0;;width:86px;border: 1px solid #d5d5d5">
                    </td>
                </tr>
                <tr>
                    <td>Display Views:</td>
                    <td>
                        <input <? if ($_USER->Is_Mod) : ?>disabled<? endif ?> type="number" name="displayviews" value="<?= $Video["displayviews"] ?>" style="padding: 3px 4px;border-radius: 3px;outline: 0;;width:86px;border: 1px solid #d5d5d5">
                    </td>
                </tr>
                <tr>
                    <td>Ratings:</td>
                    <td><input type="number" name="1_star" title="1 Star" placeholder="1*" value="<?= $Video["1_star"] ?>" style="padding: 3px 4px;border-radius: 3px;outline: 0;;width:44px;border: 1px solid #d5d5d5"> <input type="number" name="2_star" title="2 Star" placeholder="2*" value="<?= $Video["2_star"] ?>" style="padding: 3px 4px;border-radius: 3px;outline: 0;;width:44px;border: 1px solid #d5d5d5"> <input type="number" name="3_star" title="3 Star" placeholder="3*" value="<?= $Video["3_star"] ?>" style="padding: 3px 4px;border-radius: 3px;outline: 0;;width:44px;border: 1px solid #d5d5d5"> <input type="number" name="4_star" title="4 Star" placeholder="4*" value="<?= $Video["4_star"] ?>" style="padding: 3px 4px;border-radius: 3px;outline: 0;;width:44px;border: 1px solid #d5d5d5"> <input type="number" name="5_star" title="5 Star" placeholder="5*" value="<?= $Video["5_star"] ?>" style="padding: 3px 4px;border-radius: 3px;outline: 0;;width:44px;border: 1px solid #d5d5d5"></td>
                </tr>
            </table>
        </div>
        <div style="clear:both"></div>
        <div style="text-align:center;margin-top:17px">
            <input type="submit" value="Save Video Changes" name="save_video" style="padding: 5px 20px">
        </div>
    </form>
<? endif ?>
