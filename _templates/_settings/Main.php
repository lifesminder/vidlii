<form action="/channel_setup" method="POST" enctype="multipart/form-data">
    <div style="position: relative; font-weight: bold; text-align: center;overflow:hidden;<? if ($Channel_Version == 1) : ?>margin: 0 0 11px 0;<? endif ?> width:771px">
        <div style="float: left"><?= user_avatar2($_USER->displayname,100,100,$Info["avatar"]) ?><? if ($Is_Uploaded_Avatar === true) : ?><a href="javascript:void(0)" onclick="filter_box()" style="display: block; font-weight: normal">Filters</a><? endif ?></div>
        <div style="float: left;margin: 0 0 0 15px;font-weight:normal;width: 442px;text-align: left">Video: <input style="width:280px" type="url" name="v_url" id="st_em" <? if ($Is_Uploaded_Avatar === false) : ?>value="<?= $Avatar ?>" <? endif ?>placeholder="/watch?v=..." maxlength="100" size="40"<? if ($Is_Uploaded_Avatar === true) : ?> disabled<? endif ?>> <button class="search_button" type="button" onclick="latest_video()"<? if ($Is_Uploaded_Avatar === true) : ?> disabled<? endif ?>>Latest Video</button></div>
        <div style="float: left;font-weight:normal;margin: 59px 0 0 15px;width: 442px;text-align: left">Upload: <? if ($Is_Uploaded_Avatar === false) : ?><input type="file" name="avatar_upload"><? else : ?><input type="submit" name="delete_avatar" class="search_button" value="Delete Avatar"><? endif ?></div>
        <div style="position: absolute; right: 48px; top: 1px; width: 30px"><input class="search_button" type="submit" value="Update" name="update_avatar" style="padding: 43px 13px"<? if ($Is_Uploaded_Avatar === true) : ?> disabled<? endif ?>></div>
        <div style="position:absolute;color:#e6e7e0;font-weight:normal;font-size:35px;right:145px;top:-2px;line-height:57px">><br>></div>
        <div style="position:absolute;left:122px;top:47px;font-weight:normal">Or</div>
    </div>
</form>
<div id="filter_box" style="display: none; position: fixed; z-index:200;margin: 5% auto;left: 0;right: 0; bottom: 30%; height: 250px; padding: 15px; width: 400px; background-color: white; border: 1px solid #adadad">
    <h3 style="margin: 0 0 8px; text-align: center">Filters</h3>
    <center>
        <form action="/channel_setup" method="POST">
            <select name="filter_type" id="filter_type" onchange="fpreview()">
                <option value="0">No Filter</option>
                <option value="1">Black and White</option>
                <option value="2">Negative</option>
                <option value="3">High Contrast</option>
                <option value="4">Flip Horizontal</option>
                <option value="5">Flip Vertical</option>
            </select><br>
            <img style="margin: 20px 0" class="avt" id="f_demo" src="/img/filters/filter0.jpg" width="135px" height="135px"><br>
            <input type="submit" name="Apply_Filter" value="Apply Filter">
        </form>
    </center>
</div>
<div class="cl"></div>
<div style="border-top:1px solid #ccc;margin-bottom:10px"></div>
<h4>Holidays</h4>
<form action="/channel_setup" method="POST">
    <table cellspacing="6">
        <tr>
            <td><label for="ch_title">Snow:</label></td>
            <td><select name="ch_privacy">
                    <option value="0" <?= $Info["snow"] == 0 ? "selected" : "" ?>>Disabled</option>
                    <option value="1" <?= $Info["snow"] == 1 ? "selected" : "" ?>>Enabled</option>
                </select></td>
        </tr>
        <? if ($Info["mondo"] == 1) : ?>
        <tr>
            <td><label for="ch_title">Mondo:</label></td>
            <td><select name="ch_mondo">
                    <option value="0" <?= $Info["mondo"] == 0 ? "selected" : "" ?>>Disabled</option>
                    <option value="1" <?= $Info["mondo"] == 1 ? "selected" : "" ?>>Enabled</option>
                </select></td>
        </tr>
        <? endif ?>
        <tr>
            <td colspan="2"><input type="submit" value="Update" name="update_holiday"></td>
        </tr>
    </table>
</form>
<h4>Privacy Settings</h4>
<form action="/channel_setup" method="POST">
    <table cellspacing="6">
        <tr>
            <td><label for="ch_title">Who can access your channel:</label></td>
            <td><select name="ch_privacy">
				<option value="0" <?= $Info["privacy"] == 0 ? "selected" : "" ?>>Everyone</option>
				<option value="1" <?= $Info["privacy"] == 1 ? "selected" : "" ?>>Unlisted</option>
				<option value="2" <?= $Info["privacy"] == 2 ? "selected" : "" ?>>Friends only</option>
			</select></td>
        </tr>
        <tr>
            <td><label for="ch_title">Allow users to friend you:</label></td>
            <td><select name="can_friend">
				<option value="0" <?= $Info["can_friend"] == 0 ? "selected" : "" ?>>No</option>
				<option value="1" <?= $Info["can_friend"] == 1 ? "selected" : "" ?>>Yes</option>
			</select></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Update" name="update_privacy"></td>
        </tr>
    </table>
</form>
<? if ($Channel_Version == 1) : ?>
<div style="border-top:1px solid #ccc;margin-bottom:10px"></div>
<h4>Channel Settings</h4>
<form action="/channel_setup" method="POST">
    <table cellspacing="6">
        <tr>
            <td align="right"><label for="ch_title">Channel Title:</label></td>
            <td><input type="text" name="ch_title" id="ch_title" size="40" value="<?= htmlspecialchars($Info["channel_title"], ENT_QUOTES) ?>" maxlength="80" placeholder="<?= $_USER->displayname?> Channel"></td>
        </tr>
        <tr>
            <td align="right" valign="top"><label for="ch_description">Channel Description:</label></td>
            <td><textarea maxlength="2500" name="ch_description" cols="50" rows="6" id="ch_description" placeholder="Describe your channel..."><?= $Info["channel_description"] ?></textarea>
        </tr>
        <tr>
            <td align="right"><label for="ch_tags">Channel Tags:</label></td>
            <td><input type="text" name="ch_tags" id="ch_tags" size="40" value="<?= htmlspecialchars($Info["channel_tags"], ENT_QUOTES) ?>" maxlength="256" placeholder="Describe your channel in keywords..."></td>
        </tr>
        <tr>
            <td align="right"><label for="st_opw">Channel Type:</label></td>
            <td>
                <select name="channel_type">
                    <option value="0"<? if ($Info["channel_type"] == 0) : ?> selected<? endif ?>>Default</option>
                    <option value="1"<? if ($Info["channel_type"] == 1) : ?> selected<? endif ?>>Director</option>
                    <option value="2"<? if ($Info["channel_type"] == 2) : ?> selected<? endif ?>>Musician</option>
                    <option value="3"<? if ($Info["channel_type"] == 3) : ?> selected<? endif ?>>Comedian</option>
                    <option value="4"<? if ($Info["channel_type"] == 4) : ?> selected<? endif ?>>Gamer</option>
                    <option value="5"<? if ($Info["channel_type"] == 5) : ?> selected<? endif ?>>Reporter</option>
                    <option value="6"<? if ($Info["channel_type"] == 6) : ?> selected<? endif ?>>Guru</option>
                    <option value="7" <? if ($Info["channel_type"] == 7) : ?> selected<? endif ?>>Animator</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Update" name="update_channel"></td>
        </tr>
    </table>
</form>
<? endif ?>