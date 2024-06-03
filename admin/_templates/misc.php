
<div style="width:40%;margin-right:2%;float:left;">
    <div class="panel_box">
        <strong>Pages</strong>
        <form action="/admin/misc" method="POST" style="line-height:27px">
            <label><input type="checkbox" name="signup" style="position:relative;top:2px"<? if ($Sign_Up == 1) : ?> checked<? endif ?>> Sign Up</label><br />
            <label><input type="checkbox" name="signin" style="position:relative;top:2px"<? if ($Sign_In == 1) : ?> checked<? endif ?>> Sign In</label><br />
            <label><input type="checkbox" name="channels" style="position:relative;top:2px"<? if ($Channels == 1) : ?> checked<? endif ?>> Profiles</label><br />
            <label><input type="checkbox" name="uploader" style="position:relative;top:2px"<? if ($Uploader == 1) : ?> checked<? endif ?>> Uploader</label><br />
            <label><input type="checkbox" name="videos" style="position:relative;top:2px"<? if ($Videos == 1) : ?> checked<? endif ?>> Videos</label><br />
            <input type="submit" value="Save Changes" name="save_pages" style="margin-top:8px">
        </form>
    </div>
    <? if ($_USER->username == "VidLii") : ?>
    <div class="panel_box">
        <strong>VidLii Fixes</strong>
        <table cellpadding="4">
            <tr>
                <td>Friends / Invite Count Fix:</td>
                <td><a href="/admin/_scripts/clean_friends"><button type="button">Fix Counts!</button></a></td>
            </tr>
        </table>
    </div>
    </div>
    <? endif ?>

    <div style="float:left;width:58%">
        <div class="panel_box">
            <strong>VidLii Logo</strong>
            <form action="/admin/misc" method="POST" enctype="multipart/form-data">
                <div style="width:50%;text-align:center;float:left">
                    <label style="cursor: pointer">
                    <img src="/img/Vidlii6.png" style="width:166px"><br />
                    <input type="radio" name="logo" value="0"<? if ($Logo == "0") : ?> checked<? endif ?>>
                    </label>
                </div>
                <div style="width:50%;text-align:center;float:right">
                    <label style="cursor: pointer">
                    <? if ($Logo != "0") : ?>
                    <img src="/img/<?= $Logo ?>.png" style="width:166px"><br />
                    <? else : ?>
                        <input type="file" name="logo_file" style="margin-top:25px"><br />
                    <? endif ?>
                    <input type="radio" name="logo" <? if ($Logo == "0") : ?> style="position:relative;top:22px"<? endif ?> value="1"<? if ($Logo != "0") : ?> checked<? endif ?>>
                    </label>
                </div>
                <div style="clear:both"></div>
                <div style="text-align:center;margin-top:9px"><input type="submit" name="save_logo" value="Save Changes"></div>
            </form>
            </table>
        </div>
        <div class="panel_box">
            <strong>Text</strong>
            <form action="/admin/misc" method="POST">
                <div style="font-weight:bold;font-size:13px;margin-bottom:2px">Top Message:</div>
                <input type="text" name="top_message" style="width:98%;margin-bottom:8px" value="<?= htmlspecialchars($Top_Message, ENT_QUOTES) ?>">
            <div style="font-weight:bold;font-size:13px;margin-bottom:2px">Guidelines:</div>
                <textarea name="guidelines" rows="7" style="width:99%;resize:vertical"><?= $Guidelines ?></textarea>
                <div style="font-weight:bold;font-size:13px;margin-bottom:2px">Help:</div>
                <textarea name="help" rows="7" style="width:99%;resize:vertical"><?= $Help ?></textarea>
                <input type="submit" value="Save Changes" name="save_text" style="margin-top:8">
            </form>
            </table>
        </div>
    </div>


