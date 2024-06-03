<style>
    .p_text_area {
        border: 1px solid #d5d5d5;
        padding: 3px 4px;
        border-radius: 3px;
        outline: 0;
        resize: none;
        overflow: hidden;
    }
    .p_text_area:hover {
        border: 1px solid #ababab;
    }

    .p_text_area:focus {
        border: 1px solid #9d9efd;
    }
</style>
<h1 class="pg_hd">Themes</h1>
<div class="vc_l">
    <div class="vc_cats">
        <div>About VidLii</div>
        <ul>
            <li><a href="/blog">Blog</a></li>
            <li><a href="/about">About Us</a></li>
            <li><a href="/terms">Terms of Use</a></li>
            <li><a href="/privacy">Privacy Policy</a></li>
            <li style="font-weight:bold;cursor:default">Themes</li>
            <li><a href="/contact">Contact</a></li>
            <li><a href="/testlii">Testlii</a></li>
        </ul>
    </div>
</div>
<div class="vc_r" style="margin-bottom:0">
    <? if (!isset($_GET["t"]) && !isset($_GET["upload"]) && !isset($_GET["your"]) && !isset($_GET["pending"])) : ?>
    <div style="margin-bottom:15px;border-bottom:1px solid #ccc;padding-bottom:13px">
        <div style="float:left">
            <form action="/themes" method="GET">
                <input type="text" maxlength="128" name="search"<? if (isset($_GET["search"])) : ?> value="<?= htmlspecialchars($_GET["search"],ENT_QUOTES) ?>"<? endif ?> placeholder="Search Themes..."> <input type="submit" class="search_button" value="Search"><? if (isset($_COOKIE["css"]) || $_USER->logged_in) : ?> <a href="/themes?your=1">Your Themes</a><? endif ?><? if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod)) : ?> | <a href="/themes?pending=1">Pending Themes</a><? endif ?>
            </form>
        </div>
        <? if ($_USER->logged_in) : ?>
        <div style="float:right">
            <a href="/themes?upload" class="yel_btn" style="position:relative;top:3.5px">Upload Theme</a>
        </div>
        <? else : ?>
            <div style="float:right">
                <a href="/login" class="yel_btn" style="position:relative;top:3.5px">Log in to upload a Theme</a>
            </div>
        <? endif ?>
        <div style="clear:both"></div>
    </div>
    <? foreach ($Themes as $Theme) : ?>
    <div class="you_wnt">
        <div class="con_bx">
            <div style="border-bottom:1px solid #ccc;padding-bottom:4px;margin-bottom:8px"><?= date("M d, Y",strtotime($Theme["upload_date"])) ?> by <a href="/user/<?= $Theme["owner"] ?>"><?= $Theme["owner"] ?></a></div>
            <a href="/themes?t=<?= $Theme["url"] ?>"><img src="/usfi/img/<?= $Theme["url"] ?>_1.jpg" style="float:left;margin-right:7px" width="171" height="120" class="vid_th"></a>
            <a href="/themes?t=<?= $Theme["url"] ?>" style="font-size:16px;font-weight:bold"><?= $Theme["title"] ?></a>
            <div style="margin: 2px 0 0"><?= cut_string($Theme["description"],250) ?></div>
        </div>
    </div>
    <? endforeach ?>
    <div style="font-size:18px;font-weight:bold;text-align:center;word-spacing:7px">
        <? if (!isset($_GET["search"])) : ?>
            <?= $_PAGINATION->new_show($_PAGINATION->Total,"") ?>
        <? else : ?>
            <?= $_PAGINATION->new_show($_PAGINATION->Total,"search=".$_GET["search"]) ?>
        <? endif ?>
    </div>
</div>
<? elseif (isset($_GET["t"])) : ?>
    <div style="margin-bottom:15px;border-bottom:1px solid #ccc;padding-bottom:13px">
        <div style="float:left">
            <form action="/themes" method="GET">
                <input type="text" maxlength="128" name="search" placeholder="Search Themes..."> <input type="submit" class="search_button" value="Search"><? if (isset($_COOKIE["css"]) || $_USER->logged_in) : ?> <a href="/themes?your=1">Your Themes</a><? endif ?>
            </form>
        </div>
        <div style="float:right">
            <a href="/themes" class="yel_btn" style="position:relative;top:3.5px">Back to Themes</a>
        </div>
        <div style="clear:both"></div>
    </div>
    <div style="margin-bottom:1px;padding-bottom:15px;border-bottom:1px solid #ccc;overflow:hidden">
        <div style="text-align:center;font-size:20px;font-weight:bold;margin-bottom:9px"><?= $Theme["title"] ?></div>
        <img src="/usfi/img/<?= $Theme["url"] ?>_1.jpg" class="vid_th" style="width:322px;height:211px;float:left">
        <img src="/usfi/img/<?= $Theme["url"] ?>_2.jpg" class="vid_th" style="width:322px;height:211px;float:right">
    </div>
    <div style="margin-bottom:15px;padding-bottom:1px;border-bottom:1px solid #ccc;overflow:hidden">
        <table cellspacing="25" style="width:80%;margin:0 auto">
            <tr>
                <td>Internet Explorer: <? if ($Theme["internet"]) : ?>✔<? else : ?><strong>X</strong><? endif ?></td>
                <td>Firefox: <? if ($Theme["firefox"]) : ?>✔<? else : ?><strong>X</strong><? endif ?></td>
                <td>Edge: <? if ($Theme["edge"]) : ?>✔<? else : ?><strong>X</strong><? endif ?></td>
                <td>Chrome: <? if ($Theme["chrome"]) : ?>✔<? else : ?><strong>X</strong><? endif ?></td>
                <td>Opera: <? if ($Theme["opera"]) : ?>✔<? else : ?><strong>X</strong><? endif ?></td>
            </tr>
        </table>
    </div>
    <div style="margin-bottom:15px;padding-bottom:1px;border-bottom:1px solid #ccc;overflow:hidden">
        <table cellspacing="25" style="width:99%;margin:0 auto">
            <tr>
                <td>Forces Header: <? if ($Theme["header"] == 1) : ?><strong>X</strong><? elseif ($Theme["header"] == 2) : ?>Default<? else : ?>Compact<? endif ?></td>
                <td>Forces Login: <? if ($Theme["logged_in"] == 1) : ?><strong>X</strong><? elseif($Theme["logged_in"] == 2) : ?>✔<? else : ?>Logged Out<? endif ?></td>
                <td>Creator: <a href="/user/<?= $Theme["displayname"] ?>"><?= $Theme["displayname"] ?></a></td>
                <td>Installs: ~<?= number_format($Theme["installs"]) ?></td>
                <td>Upload Date: <?= date("M d, Y",strtotime($Theme["upload_date"])) ?></td>
            </tr>
        </table>
    </div>
    <div style="float:left;width:48%;border-right:1px solid #ccc;padding-right:1%;margin-right:1%">
        <?= $Theme["description"] ?>
    </div>
    <div style="float:left;text-align:center;width:49%">
        <a href="themes?a=<?= $Theme["url"] ?>" style="font-size:16px;font-weight:bold"><? if (!$_THEMES->has_installed_theme($Theme["url"])) : ?>INSTALL THEME<? else : ?>UNINSTALL THEME<? endif ?></a>
    </div>
    <? if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod)) : ?>
       <div class="cl"></div>
       <div style="margin-top:15px">
            <textarea style="width:99.5%" rows="10" readonly><?= file_get_contents("/usfi/css/".$_GET["t"].".css") ?></textarea>
           <div style="text-align:center">
            <? if ($Theme["accepted"] == 0) : ?>
            <a href="/themes?accept=<?= $_GET["t"] ?>">Accept Theme</a> |
            <? endif ?>
            <a href="/themes?delete=<?= $_GET["t"] ?>" onclick="if (!confirm('Are you sure you want to remove this theme?')) { return false; }">Remove Theme</a>
           </div>
       </div>
    <? endif ?>
    <? if ($_USER->logged_in && $_USER->Is_Mod == false && $_USER->Is_Admin == false && $_USER->username == $Theme["owner"]) : ?>
        <div style="margin-top:52px">
            <div style="text-align:center">
                <a href="/themes?delete=<?= $_GET["t"] ?>" onclick="if (!confirm('Are you sure you want to remove this theme?')) { return false; }">Remove Theme</a>
            </div>
        </div>
    <? endif ?>
<? elseif ($_USER->logged_in && isset($_GET["upload"])) : ?>
    <div style="margin-bottom:15px;border-bottom:1px solid #ccc;padding-bottom:13px">
        <div style="float:left">
            <form action="/themes" method="GET">
                <input type="text" maxlength="128" name="search" placeholder="Search Themes..."> <input type="submit" class="search_button" value="Search">
            </form>
        </div>
        <? if ($_USER->logged_in) : ?>
            <div style="float:right">
                <a href="/themes" class="yel_btn" style="position:relative;top:3.5px">Back to Themes</a>
            </div>
        <? else : ?>
            <div style="float:right">
                <a href="/login" class="yel_btn" style="position:relative;top:3.5px">Log in to upload a Theme</a>
            </div>
        <? endif ?>
        <div style="clear:both"></div>
    </div>
    <div style="margin-bottom:15px;border-bottom:1px solid #ccc;padding-bottom:13px">
        By uploading a theme you agree that your theme doesn't break the <a href="/terms">Terms Of Service</a>, <a href="/guidelines">Community Guidelines</a> and doesn't have the intent of harming its users!
    </div>
    <form action="/themes?upload" method="POST" enctype="multipart/form-data">
        <div id="upload_select_box" style="background:#ebebe1;border-radius:8px;padding:16px;overflow:hidden">
            <div style="background:white;border-radius:7px;border:1px solid #ccc;padding: 15px;padding-top:9px;width:95.5%;float:left;margin-right:10px">
                <div style="font-size:16px;font-weight:bold">Theme Information:</div>
                <div style="margin-bottom:15px">No text field can be empty!</div>
                <strong style="display:block;margin-bottom:2px;">Theme Name:</strong>
                <input type="text" style="width:320px" placeholder="Your Theme Title" name="theme_title" maxlength="100" required>
                <strong style="display:block;margin-top:12px;margin-bottom:2px;">Theme Description:</strong>
                <textarea style="width:403px" placeholder="Describe your theme with as much detail as possible!" class="p_text_area" rows="8" name="theme_description" required maxlength="1000"></textarea>
                <strong style="display:block;margin-top:8px;margin-bottom:2px;">Theme Category:</strong>
                <select name="theme_category" required>
                    <option value="1">Palette Change</option>
                    <option value="2">Graphic Change</option>
                    <option value="3">Revamp</option>
                    <option value="4">Fix</option>
                </select>
                <strong style="display:block;margin-top:8px;margin-bottom:2px;">Force Header:</strong>
                <select name="theme_header" required>
                    <option value="1">No</option>
                    <option value="2">Default Header</option>
                    <option value="3">Compact Header</option>
                </select>
                <strong style="display:block;margin-top:8px;margin-bottom:2px;">Logged In:</strong>
                <select name="theme_logged" required>
                    <option value="1">Doesn't Matter</option>
                    <option value="2">Must Be Logged In</option>
                    <option value="3">Must not be Logged In</option>
                </select>
                <div style="margin-top: 16px">
                    <strong>Compatible With</strong>: <label><input type="checkbox" name="chrome" checked> Chrome</label> <label><input type="checkbox" name="firefox" checked> Firefox</label> <label><input type="checkbox" name="edge" checked> Edge</label> <label><input type="checkbox" name="internet" checked> Internet Explorer</label> <label><input type="checkbox" name="opera" checked> Opera</label>
                </div>
                <div style="margin: 20px 0 4px 0; border-bottom:1px solid #ccc"></div>
                <div style="font-size:16px;font-weight:bold">Upload:</div>
                <div style="margin-bottom:5px">All 3 uploads have to be set!</div>
                <table border="0" style="position:relative;left: -10px" cellspacing="9">
                    <tr>
                        <td><strong>Main Picture: </strong></td>
                        <td><input type="file" name="main_picture" required> (Must be < 1MB)</td>
                    </tr>
                    <tr>
                        <td><strong>Secondary Picture: </strong></td>
                        <td><input type="file" name="secondary_picture" required> (Must be < 1MB)</td>
                    </tr>
                    <tr>
                        <td><strong>CSS File: </strong></td>
                        <td><input type="file" name="css_file" required> (Must be < 50KB)</td>
                    </tr>
                </table>
                <input type="submit" value="Upload Theme" name="upload_theme" class="search_button">
            </div>
        </div>
    </form>
<? elseif (isset($_GET["pending"]) && ($_USER->Is_Mod || $_USER->Is_Admin)) : ?>
    <div style="margin-bottom:15px;border-bottom:1px solid #ccc;padding-bottom:13px">
        <div style="float:left">
            <form action="/themes" method="GET">
                <input type="text" maxlength="128" name="search" placeholder="Search Themes..."> <input type="submit" class="search_button" value="Search"><? if (isset($_COOKIE["css"]) || $_USER->logged_in) : ?> <a href="/themes?your=1">Your Themes</a><? endif ?>
            </form>
        </div>
        <? if ($_USER->logged_in) : ?>
            <div style="float:right">
                <a href="/themes" class="yel_btn" style="position:relative;top:3.5px">Back to Themes</a>
            </div>
        <? else : ?>
            <div style="float:right">
                <a href="/login" class="yel_btn" style="position:relative;top:3.5px">Log in to upload a Theme</a>
            </div>
        <? endif ?>
        <div style="clear:both"></div>
    </div>
    <? if (isset($Accept)) : ?>
        <? foreach ($Accept as $Theme) : ?>
            <div class="you_wnt">
                <div class="con_bx" style="background: gray">
                    <div style="border-bottom:1px solid #ccc;overflow:hidden;padding-bottom:4px;margin-bottom:8px"><div style="float:left"><?= date("M d, Y",strtotime($Theme["upload_date"])) ?> by <a href="/user/<?= $Theme["owner"] ?>"><?= $Theme["owner"] ?></a></div><div style="float:right;font-weight:bold">Pending!</div></div>
                    <a href="/themes?t=<?= $Theme["url"] ?>"><img src="/usfi/img/<?= $Theme["url"] ?>_1.jpg" style="float:left;margin-right:7px" width="171" height="120" class="vid_th"></a>
                    <a href="/themes?t=<?= $Theme["url"] ?>" style="font-size:16px;font-weight:bold"><?= $Theme["title"] ?></a>
                    <div style="margin: 2px 0 0"><?= cut_string($Theme["description"],250) ?></div>
                </div>
            </div>
        <? endforeach ?>
    <? else : ?>
        <div style="font-size:22px;text-align:center;margin-top:63px;color:#787878">You Themes are pending!</div>
    <? endif ?>
    </div>
<? else : ?>
    <div style="margin-bottom:15px;border-bottom:1px solid #ccc;padding-bottom:13px">
        <div style="float:left">
            <form action="/themes" method="GET">
                <input type="text" maxlength="128" name="search" placeholder="Search Themes..."> <input type="submit" class="search_button" value="Search"><? if ($_USER->logged_in && ($_USER->Is_Admin || $_USER->Is_Mod)) : ?> <a href="/themes?pending=1">Pending Themes</a><? endif ?>
            </form>
        </div>
        <div style="float:right">
            <a href="/themes" class="yel_btn" style="position:relative;top:3.5px">Back to Themes</a>
        </div>
        <div style="clear:both"></div>
    </div>
    <? if ($Installed_Themes != false || $Your_Themes != false) : ?>
    <? if ($Installed_Themes) : ?>
        <? foreach ($Installed_Themes as $Installed) : ?>
        <div class="you_wnt">
            <div class="con_bx" style="background: #feb" title="Installed Theme">
                <div style="border-bottom:1px solid #ccc;overflow:hidden;padding-bottom:4px;margin-bottom:8px"><div style="float:left"><?= date("M d, Y",strtotime($Installed["upload_date"])) ?> by <a href="/user/<?= $Installed["owner"] ?>"><?= $Installed["owner"] ?></a></div><div style="float:right;font-weight:bold">Installed!</div></div>
                <a href="/themes?t=<?= $Installed["url"] ?>"><img src="/usfi/img/<?= $Installed["url"] ?>_1.jpg" style="float:left;margin-right:7px" width="171" height="120" class="vid_th"></a>
                <a href="/themes?t=<?= $Installed["url"] ?>" style="font-size:16px;font-weight:bold"><?= $Installed["title"] ?></a>
                <div style="margin: 2px 0 0"><?= cut_string($Installed["description"],250) ?></div>
            </div>
        </div>
        <? endforeach ?>
    <? endif ?>
    <? if ($Your_Themes) : ?>
        <? foreach ($Your_Themes as $Theme) : ?>
            <div class="you_wnt">
                <div class="con_bx" style="background: <? if ($Theme["accepted"] == 1) : ?>#d4e4ff<? else : ?>gray<? endif ?>">
                    <div style="border-bottom:1px solid #ccc;overflow:hidden;padding-bottom:4px;margin-bottom:8px"><div style="float:left"><?= date("M d, Y",strtotime($Theme["upload_date"])) ?> by <a href="/user/<?= $Theme["owner"] ?>"><?= $Theme["owner"] ?></a></div><div style="float:right;font-weight:bold">Made by you! <? if ($Theme["accepted"] == 0) : ?> | Pending!<? endif ?></div></div>
                    <a href="/themes?t=<?= $Theme["url"] ?>"><img src="/usfi/img/<?= $Theme["url"] ?>_1.jpg" style="float:left;margin-right:7px" width="171" height="120" class="vid_th"></a>
                    <a href="/themes?t=<?= $Theme["url"] ?>" style="font-size:16px;font-weight:bold"><?= $Theme["title"] ?></a>
                    <div style="margin: 2px 0 0"><?= cut_string($Theme["description"],250) ?></div>
                </div>
            </div>
        <? endforeach ?>
    <? endif ?>
    <? else : ?>
    <div style="font-size:22px;text-align:center;margin-top:63px;color:#787878">You have no themes!</div>
    <? endif ?>
<? endif ?>
