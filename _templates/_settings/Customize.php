<script>
    var trans1 = <?= 100 - $Design["n_trans"] ?>;
    var trans2 = <?= 100 - $Design["h_trans"] ?>;
</script>
<style>
    #ch_prv {
        font-family: <? if ($Design["font"] == 1) : ?>Georgia, Arial<? elseif ($Design["font"] == 2) : ?>"Times New Roman", Arial<? elseif ($Design["font"] == 3) : ?>"Comic Sans MS", Arial<? elseif ($Design["font"] == 4) : ?>Impact, Arial<? elseif ($Design["font"] == 5) : ?>Tahoma, Arial<? elseif ($Design["font"] == 6) : ?>"Courier New", Arial<? endif ?>;
        <? if ($Has_Background) : ?>background-image: url("<?= $Background ?>");<? endif ?>background-color: #<?= $Design["bg"] ?>; <? if ($Design["bg_fixed"] == 1) : ?>background-attachment: fixed<? endif ?>; background-position: <? if ($Design["bg_position"] == 1) : ?>top<? elseif ($Design["bg_position"] == 2) : ?>center<? elseif ($Design["bg_position"] == 3) : ?>bottom<? endif ?>; background-repeat: <? if ($Design["bg_repeat"] == 1) : ?>no-repeat<? elseif ($Design["bg_repeat"] == 2) : ?>repeat<? elseif ($Design["bg_repeat"] == 3) : ?>repeat-x<? elseif ($Design["bg_repeat"] == 4) : ?>repeat-y<? endif ?>; background-size: <? if ($Design["bg_stretch"] == 0) : ?>auto<? else : ?>cover<? endif ?>
    }
    #ch_prv_nav {
        text-align:center;
        margin: 5px 0 10px;
    }
    #ch_prv_nav > a {
        border-right: 1px solid #<?= $Design["h_in_fnt"] ?>;
        padding-right: 6px;
        margin-right: 6px;
    }
    #ch_prv_nav > a:last-of-type {
        margin: 0;
        padding: 0;
        border: 0;
    }
    .ch_prv_hl_hd {
        padding: 4px;
        background: <?= hexToRgb($Design["h_head"],$HightLight_Trans) ?>;
        color: #<?= $Design["h_head_fnt"] ?>;
        font-weight: bold;
    }
    .ch_prv_n_hd {
        padding: 3px;
        background: <?= hexToRgb($Design["n_head"],$Normal_Trans) ?>;
        color: #<?= $Design["n_head_fnt"] ?>;
        font-weight: bold;
    }
    .ch_prv_hl_in {
        background: <?= hexToRgb($Design["h_in"],$HightLight_Trans) ?>;
        color: #<?= $Design["h_in_fnt"] ?>;
        border: 1px solid <?= hexToRgb($Design["h_head"],$HightLight_Trans) ?>;
        border-top: 0;
        padding: 4px 2px 3px;
        overflow: hidden;
    }
    .ch_prv_n_in {
        background: <?= hexToRgb($Design["n_in"],$Normal_Trans) ?>;
        color: #<?= $Design["n_in_fnt"] ?>;
        border: 1px solid <?= hexToRgb($Design["n_head"],$Normal_Trans) ?>;
        border-top: 0;
        padding: 5px 4px 0 4px;
        overflow: hidden;
    }
    .sample_avt {
        border: 1.5px solid white !important;
    }
    .ch_prv_n_in > center img {
        display: inline-block;
        margin-right: 8px;
        margin-bottom: 3px
    }
    .ch_prv_hl_hd, .ch_prv_n_hd {
        border-top-right-radius: <?= $Design["chn_radius"] ?>px;
        border-top-left-radius: <?= $Design["chn_radius"] ?>px;
    }
    .ch_prv_hl_in, .ch_prv_n_in {
        border-bottom-right-radius: <?= $Design["chn_radius"] ?>px;
        border-bottom-left-radius: <?= $Design["chn_radius"] ?>px;
    }
    .avt2, .ch_prv_n_in img {
        border-radius: <?= $Design["avt_radius"] ?>px;
    }
    .not_me {
        border-radius: 0 !important;
    }
</style>
<form action="/channel_theme" method="POST" enctype="multipart/form-data">
    <div style="font-size:16px;font-weight:bold;margin-bottom:6px">Layout Properties</div>
    <div style="font-size:13px">Check the sections your want to display on your channel.</div>
    <div style="margin-left:24px;margin-top:21px">
        <div style="float:left;width:35%;margin-left:15%">
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="recent"<? if ($Design["c_recent"]) : ?> checked<? endif ?> onclick="$('#c_recent').toggleClass('hddn')"> Recent Activity</label>
            </div>
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="featured_channels"<? if ($Design["c_featured_channels"]) : ?> checked<? endif ?> onclick="$('#c_channels').toggleClass('hddn')"> Featured Channels</label>
            </div>
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="subscribers"<? if ($Design["c_subscriber"]) : ?> checked<? endif ?> onclick="$('#c_subscribers').toggleClass('hddn')"> Subscribers</label>
            </div>
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="subscriptions"<? if ($Design["c_subscription"]) : ?> checked<? endif ?> onclick="$('#c_subscriptions').toggleClass('hddn')"> Subscriptions</label>
            </div>
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="friends"<? if ($Design["c_friend"]) : ?> checked<? endif ?> onclick="$('#c_friends').toggleClass('hddn')"> Friends</label>
            </div>
        </div>
        <div style="float:left;width:35%;margin-left:10%">
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input id="ch_fv" type="checkbox" name="featured"<? if ($Design["c_featured"]) : ?> checked<? endif ?> onclick="$('#featured_video_info').toggleClass('hddn');$('#ch_prv_ft').toggleClass('hddn')"> Featured Video</label>
            </div>
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="videos"<? if ($Design["c_videos"]) : ?> checked<? endif ?> onclick="$('#c_videos').toggleClass('hddn')"> Videos</label>
            </div>
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="favorites"<? if ($Design["c_favorites"]) : ?> checked<? endif ?> onclick="$('#c_favorites').toggleClass('hddn')"> Favorites</label>
            </div>
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="playlists"<? if ($Design["c_playlists"]) : ?> checked<? endif ?> onclick="$('#c_playlists').toggleClass('hddn')"> Playlists</label>
            </div>
            <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                <label><input type="checkbox" name="comments"<? if ($Design["c_comments"]) : ?> checked<? endif ?> onclick="$('#c_comments').toggleClass('hddn')"> Channel Comments</label>
            </div>
            <? if ($_USER->Is_Partner) : ?>
                <div style="font-weight:bold;margin-bottom:13px" class="cc_checkboxes">
                    <label><input type="checkbox" name="custom"<? if ($Design["c_custom"]) : ?> checked<? endif ?> onclick="$('#c_custom').toggleClass('hddn')"> Custom Box</label>
                </div>
            <? endif ?>
        </div>
    </div>
    <div class="cl"></div>
    <div id="featured_video_info" style="margin-top:15px"<? if (!$Design["c_featured"]) : ?> class="hddn"<? endif ?>>
        <div style="font-size:16px;font-weight:bold;margin: 16px 0 6px">Featured Video Properties</div>
        <div style="font-size:13px">Change which Featured Video is displayed on your channel.</div>
        <div style="margin-top:18px;font-weight:bold">
            <div style="float:left;width:50%;text-align:center">
                For Non-Subscribers:<br>
                <input type="url" placeholder="/watch?v=..." name="n_url" autocomplete="off" value="<? if (!empty($Design["featured_n_url"])) : ?>/watch?v=<?= $Design["featured_n_url"] ?><? endif ?>" maxlength="128" style="width:222px;margin-top:4px;border: 1px solid #ababab;border-radius:0">
            </div>
            <div style="float:right;width:50%;text-align:center">
                For Subscribers:<br>
                <input type="url" placeholder="/watch?v=..." name="s_url" value="<? if (!empty($Design["featured_s_url"])) : ?>/watch?v=<?= $Design["featured_s_url"] ?><? endif ?>" maxlength="128" autocomplete="off" style="width:222px;margin-top:4px;border: 1px solid #ababab;border-radius:0">
            </div>
        </div>
    </div>
<div style="float:left;width:48%">
    <div style="font-size:16px;font-weight:bold;margin: 30px 0 6px">Basic Box Properties</div>
    <div style="font-size:13px">Customize the colors of your regular content boxes.</div>
    <div style="margin-top:18px;font-weight:bold">
        <table cellspacing="11">
            <tr>
                <td width="145px" align="right"><label for="nmhdcolor">Border Color:</label></td>
                <td><input type="text" id="nmhdcolor" name="nmhdcolor" maxlength="7" size="6" value="#<?= $Design["n_head"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'nm_b(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label for="nmhdcolorfont">Border Text Color:</label></td>
                <td><input type="text" id="nmhdcolorfont" name="nmhdcolorfont" maxlength="7" size="6" value="#<?= $Design["n_head_fnt"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'nm_bf(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label for="nmincolor">Inside Color:</label></td>
                <td><input type="text" id="nmincolor" name="nmincolor" maxlength="7" size="6" value="#<?= $Design["n_in"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'nm_i(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label for="nmincolorfont">Inside Text Color:</label></td>
                <td><input type="text" id="nmincolorfont" name="nmincolorfont" maxlength="7" size="6" value="#<?= $Design["n_in_fnt"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'nm_if(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label>Transparency:</label></td>
                <td><input style="width:95px;margin:0;position:relative;top:3px" type="range" maxlength="7" min="0" max="100" step="1" name="n_trans" value="<?= $Design["n_trans"] ?>" oninput="nm_b(document.getElementById('nmhdcolor').value.replace('#',''));nm_i(document.getElementById('nmincolor').value.replace('#',''));_('hb_v2').innerHTML = this.value+'%';trans1 = (100 - this.value)" onchange="_('hb_v2').innerHTML = this.value+'%'"><span style="position:relative;top:-3px;font-weight:normal;font-size: 14px;bottom: 5.5px; left:8px" id="hb_v2"><?= $Design["n_trans"] ?>%</span></td>
            </tr>
        </table>
    </div>
    <div style="font-size:16px;font-weight:bold;margin: 30px 0 6px">Highlight Box Properties</div>
    <div style="font-size:13px">Customize the colors of your description box.</div>
    <div style="margin-top:18px;font-weight:bold">
        <table cellspacing="11">
            <tr>
                <td width="145px" align="right"><label for="hghdcolor">Border Color:</label></td>
                <td><input type="text" id="hghdcolor" name="hghdcolor" maxlength="7" size="6" value="#<?= $Design["h_head"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'hl_b(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label for="hghdcolorfont">Border Text Color:</label></td>
                <td><input type="text" id="hghdcolorfont" name="hghdcolorfont" maxlength="7" size="6" value="#<?= $Design["h_head_fnt"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'hl_bf(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label for="hgincolor">Inside Color:</label></td>
                <td><input type="text" id="hgincolor" name="hgincolor" maxlength="7" size="6" value="#<?= $Design["h_in"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'hl_i(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label for="hgincolorfont">Inside Text Color:</label></td>
                <td><input type="text" id="hgincolorfont" name="hgincolorfont" maxlength="7" size="6" value="#<?= $Design["h_in_fnt"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'hl_if(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label>Transparency:</label></td>
                <td><input style="width:95px;margin:0;position:relative;top:3px" type="range" maxlength="7" min="0" max="100" step="1" name="h_trans" value="<?= $Design["h_trans"] ?>" oninput="hl_b(document.getElementById('hghdcolor').value.replace('#',''));hl_i(document.getElementById('hgincolor').value.replace('#',''));_('hb_v').innerHTML = this.value+'%';trans2 = (100 - this.value)" onchange="_('hb_v').innerHTML = this.value+'%'"><span style="position:relative;top:-3px;font-size: 14px;bottom: 5.5px; left:8px;font-weight:normal" id="hb_v"><?= $Design["h_trans"]?>%</span></td>
            </tr>
        </table>
    </div>
    <div style="font-size:16px;font-weight:bold;margin: 30px 0 6px">Advanced Design Options</div>
    <div style="font-size:13px">Customize the look and feel of your channel even further.</div>
    <div style="margin-top:18px;font-weight:bold">
        <table cellspacing="11">
            <tr>
                <td width="200px" align="right"><label for="lnkcolor">Link Color:</label></td>
                <td><input type="text" id="lnkcolor" name="lnkcolor" maxlength="7" size="6" value="#<?= $Design["links"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'links(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label>Channel Font:</label></td>
                <td>
                    <select name="ch_fnt" id="ch_fnt" style="width:135px" onchange="cu_fnt()">
                        <option value="0"<? if ($Design["font"] == 0) : ?> selected<? endif ?>>Arial</option>
                        <option value="1"<? if ($Design["font"] == 1) : ?> selected<? endif ?>>Georgia</option>
                        <option value="2"<? if ($Design["font"] == 2) : ?> selected<? endif ?>>Times New Roman</option>
                        <option value="3"<? if ($Design["font"] == 3) : ?> selected<? endif ?>>Comic Sans MS</option>
                        <option value="4"<? if ($Design["font"] == 4) : ?> selected<? endif ?>>Impact</option>
                        <option value="5"<? if ($Design["font"] == 5) : ?> selected<? endif ?>>Tahoma</option>
                        <option value="6"<? if ($Design["font"] == 6) : ?> selected<? endif ?>>Courier New</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><label>Channel Radius:</label></td>
                <td><input type="range" style="width:95px;margin:0;position:relative;top:3px" maxlength="7" min="0" max="9" step="1" name="chn_radius" value="<?= $Design["chn_radius"] ?>" oninput="$('.ch_prv_hl_hd').css('border-top-right-radius',this.value+'px');$('.ch_prv_hl_hd').css('border-top-left-radius',this.value+'px');$('.ch_prv_n_hd').css('border-top-right-radius',this.value+'px');$('.ch_prv_n_hd').css('border-top-left-radius',this.value+'px');$('.ch_prv_n_in').css('border-bottom-right-radius',this.value+'px');$('.ch_prv_n_in').css('border-bottom-left-radius',this.value+'px');;$('.ch_prv_hl_in').css('border-bottom-right-radius',this.value+'px');$('.ch_prv_hl_in').css('border-bottom-left-radius',this.value+'px');document.getElementById('hb_v4').innerHTML = this.value+'px'" onchange="$('.ch_prv_hl_hd').css('border-top-right-radius',this.value+'px');$('.ch_prv_hl_hd').css('border-top-left-radius',this.value+'px');$('.ch_prv_n_hd').css('border-top-right-radius',this.value+'px');$('.ch_prv_n_hd').css('border-top-left-radius',this.value+'px');$('.ch_prv_n_in').css('border-bottom-right-radius',this.value+'px');$('.ch_prv_n_in').css('border-bottom-left-radius',this.value+'px');$('.ch_prv_hl_in').css('border-bottom-right-radius',this.value+'px');$('.ch_prv_hl_in').css('border-bottom-left-radius',this.value+'px');document.getElementById('hb_v4').innerHTML = this.value+'px'"><span style="font-weight:normal;position:relative;font-size: 14px;bottom: 3px; left:8px" id="hb_v4"><?= $Design["chn_radius"] ?>px</span></td>
            </tr>
            <tr>
                <td align="right"><label>Avatar Radius:</label></td>
                <td><input type="range" style="width:95px;margin:0;position:relative;top:3px" maxlength="7" min="0" max="9" step="1" name="avt_radius" value="<?= $Design["avt_radius"] ?>" oninput="$('.avt2, .ch_prv_n_in img').css('border-radius',this.value+'px');$('.not_me').css('border-radius','0');document.getElementById('hb_v3').innerHTML = this.value+'px'" onchange="$('.avt2, .ch_prv_n_in img').css('border-radius',this.value+'px');$('.not_me').css('border-radius','0');document.getElementById('hb_v3').innerHTML = this.value+'px'"><span style="position:relative;font-size: 14px;bottom: 3px; left:8px; font-weight:normal" id="hb_v3"><?= $Design["avt_radius"] ?>px</span></td>
            </tr>
            <tr>
                <td align="right"><label for="avcolor">Avatar Border Color:</label></td>
                <td><input type="text" id="avcolor" name="avcolor" maxlength="7" size="6" value="#<?= $Design["b_avatar"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true}"></td>
            </tr>
            <tr>
                <td align="right"><label for="navcolor">Navigation Color:</label></td>
                <td><input type="text" id="navcolor" name="navcolor" maxlength="7" size="6" value="#<?= $Design["nav"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'nav(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label for="st_bg">Background Color:</label></td>
                <td><input type="text" id="st_bg" name="bgcolor" maxlength="7" size="6" value="#<?= $Design["bg"] ?>" placeholder="#" class="jscolor {mode:'HVS', hash:true, onFineChange:'bg(this)'}"></td>
            </tr>
            <tr>
                <td align="right"><label>Background Image:</label></td>
                <td><input type="file" name="bg_upload" id="bg_upload"<? if ($Has_Background) : ?> style="width:178px;display: none"<? else : ?> style="width:178px"<? endif ?>><button id="bg_delete" onclick="delete_background_new()" type="button"<? if (!$Has_Background) : ?> style="display: none"<? endif ?>>Delete Background</button></td>
            </tr>
            <tr>
                <td align="right"><label>Background Position:</label></td>
                <td>
                    <select name="bg_position" id="ch_position" onchange="change_position()">
                        <option value="1"<? if ($Design["bg_position"] == 1) : ?> selected<? endif ?>>Top</option>
                        <option value="2"<? if ($Design["bg_position"] == 2) : ?> selected<? endif ?>>Middle</option>
                        <option value="3"<? if ($Design["bg_position"] == 3) : ?> selected<? endif ?>>Bottom</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><label for="st_bg">Background Repeat:</label></td>
                <td>
                    <select name="bg_repeat" id="ch_repeat" onclick="change_repeat()">
                        <option value="1"<? if ($Design["bg_repeat"] == 1) : ?> selected<? endif ?>>No Repeat</option>
                        <option value="2"<? if ($Design["bg_repeat"] == 2) : ?> selected<? endif ?>>Repeat</option>
                        <option value="3"<? if ($Design["bg_repeat"] == 3) : ?> selected<? endif ?>>Repeat X</option>
                        <option value="4"<? if ($Design["bg_repeat"] == 4) : ?> selected<? endif ?>>Repeat Y</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right"><label for="bg_stretch">Stretch Background:</label></td>
                <td><input type="checkbox" id="bg_stretch" name="bg_stretch"<? if ($Design["bg_stretch"] == 1) : ?> checked<? endif ?>></td>
            </tr>
            <tr>
                <td align="right"><label for="bg_fixed">Fixed Background:</label></td>
                <td><input type="checkbox" name="bg_fixed"<? if ($Design["bg_fixed"] == 1) : ?> checked<? endif ?>></td>
            </tr>
            <tr>
                <td align="right"><label for="conn_text">Connect Text:</label></td>
                <td><input type="text" id="conn_text" name="conn_text" maxlength="32" style="width:165px" value="<?= $Design["connect"] ?>" placeholder="Contact <?= $_USER->displayname ?>"></td>
            </tr>
        </table>
    </div>
</div>
<div style="float:right;width:52%">
    <div style="height:1085px;position:relative">
        <div id="channel_preview_container">
            <div style="font-weight:bold;font-size:16px;margin-bottom:1px;text-align:center">Channel Preview</div>
            <div id="ch_prv" style="font-size:11px">
                <div id="ch_prv_nav">
                    <a href="javascript:void(0)" style="color: #<?= $Design["nav"] ?> !important;">Channel</a><a href="javascript:void(0)" style="color: #<?= $Design["nav"] ?> !important;">Videos</a><a href="javascript:void(0)" style="color: #<?= $Design["nav"] ?> !important;">Favorites</a><a href="javascript:void(0)" style="color: #<?= $Design["nav"] ?> !important;">Subscribers</a><a href="javascript:void(0)" style="color: #<?= $Design["nav"] ?> !important;">Subscriptions</a><a href="javascript:void(0)" style="color: #<?= $Design["nav"] ?> !important;">Friends</a>
                </div>
                <div style="width:39%;float:left;margin-right: 3%">
                    <div style="margin-bottom: 12px">
                        <div class="ch_prv_hl_hd">
                            <?= $_USER->displayname ?>'s Channel
                        </div>
                        <div class="ch_prv_hl_in">
                            <div style="float:left;margin-right:3px">
                                <?= user_avatar2( $_USER->displayname,40,40,$Design["avatar"],"sample_avt") ?>
                            </div>
                            <div style="float:left">
                                <div style="font-weight: bold; font-size: 10.5px"><?= $_USER->displayname ?></div>
                                <div style="font-size: 10px">Joined...</div>
                                <div style="font-size: 10px">Channel Views...</div>
                            </div>
                            <div class="cl"></div>
                            <div style="font-size:10px"><? if (!empty($Design["channel_description"])) : ?><?= cut_string($Design["channel_description"],50) ?><? else : ?>Hey guys, this is my VidLii Channel!<? endif ?></div>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px" id="c_channels" <? if (!$Design["c_featured_channels"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                             Featured Channels
                        </div>
                        <div class="ch_prv_n_in">
                            <div style="float:left;width:45%;margin-right:5%;margin-left:5%;margin-bottom:10px">
                                <a class="cu_link" style="color:#<?= $Design["links"] ?>" href="javascript:void(0)">User</a><br>
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                            </div>
                            <div style="float:right;width:33%;margin-bottom:10px">
                                <a class="cu_link" style="color:#<?= $Design["links"] ?>" href="javascript:void(0)">User</a><br>
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                            </div>
                            <div style="float:left;width:45%;margin-right:5%;margin-left:5%;margin-bottom:3px">
                                <a class="cu_link" style="color:#<?= $Design["links"] ?>" href="javascript:void(0)">User</a><br>
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                            </div>
                            <div style="float:right;width:33%;margin-bottom:3px">
                                <a class="cu_link" style="color:#<?= $Design["links"] ?>" href="javascript:void(0)">User</a><br>
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px" id="c_subscribers" <? if (!$Design["c_subscriber"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            Subscribers (3)
                        </div>
                        <div class="ch_prv_n_in">
                            <center>
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                                <img style="margin:0" src="/img/no.png" width="33" height="33">
                            </center>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px" id="c_subscriptions" <? if (!$Design["c_subscription"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            Subscriptions (5)
                        </div>
                        <div class="ch_prv_n_in">
                            <center>
                                <img src="/img/no.png" width="33" height="33">
                                <img src="/img/no.png" width="33" height="33">
                                <img style="margin:0 0 3px 0" src="/img/no.png" width="33" height="33">
                            </center>
                            <center>
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                                <img style="margin:0" src="/img/no.png" width="33" height="33">
                            </center>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px" id="c_friends" <? if (!$Design["c_friend"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            Friends (13)
                        </div>
                        <div class="ch_prv_n_in">
                            <center>
                                <img src="/img/no.png" width="33" height="33">
                                <img src="/img/no.png" width="33" height="33">
                                <img style="margin:0 0 3px 0" src="/img/no.png" width="33" height="33">
                            </center>
                            <center>
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                                <img style="margin-bottom:0" src="/img/no.png" width="33" height="33">
                                <img style="margin:0" src="/img/no.png" width="33" height="33">
                            </center>
                        </div>
                    </div>
                </div>
                <div style="width:58%;float:left">
                    <div id="ch_prv_ft" style="position:relative" <? if (!$Design["c_featured"]) : ?>class="hddn"<? endif ?>>
                        <? if (isset($Latest_Video) && file_exists("usfi/thmp/$Latest_Video.jpg")) : ?>
                            <img src="/usfi/thmp/<?= $Latest_Video ?>.jpg" style="width:211px;height:132px">
                        <? else : ?>
                            <img src="/img/no_th.jpg" style="width:211px;height:132px">
                        <? endif ?>
                        <img src="/img/play.png" style="position: absolute;width:50px;left:80px;top:43px;opacity:0.75">
                        <img src="/img/channel_player.png" width="211" height="13" style="border-bottom-left-radius: 4px;margin-bottom:5px;border-bottom-right-radius: 4px;position:relative;bottom:4px">
                    </div>
                    <div style="margin-bottom: 12px" id="c_videos" <? if (!$Design["c_videos"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            Videos (2)
                        </div>
                        <div class="ch_prv_n_in">
                            <center>
                                <img class="not_me" style="margin: 0 10px 0 0; width: 55px; height: 38px" src="/img/JGOzBj6bmWl.jpg" width="33" height="33">
                                <img class="not_me" style="margin: 0; width: 60; width: 55px; height: 38px" src="/img/QZUth1pWngI.jpg" width="33" height="33">
                            </center>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px;margin-top:6px" id="c_favorites" <? if (!$Design["c_favorites"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            Favorites (3)
                        </div>
                        <div class="ch_prv_n_in">
                            <center>
                                <img class="not_me" style="margin: 0 10px 0 0; width: 55px; height: 38px" src="/img/JGOzBj6bmWl.jpg" width="33" height="33">
                                <img class="not_me" style="margin: 0 10px 0 0; width: 55px; height: 38px" src="/img/JGOzBj6bmWl.jpg" width="33" height="33">
                                <img class="not_me" style="margin: 0; width: 55px; height: 38px" src="/img/QZUth1pWngI.jpg" width="33" height="33">
                            </center>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px;margin-top:6px" id="c_playlists" <? if (!$Design["c_playlists"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            Playlists (1)
                        </div>
                        <div class="ch_prv_n_in">
                            <div style="float:left">
                                <img class="not_me" style="margin: 0; width: 55px; height: 38px" src="/img/QZUth1pWngI.jpg" width="33" height="33">
                            </div>
                            <div style="float:left;margin-left: 5px">
                                <a href="javascript:void(0)" class="cu_link" style="color:#<?= $Design["links"] ?>;font-weight:bold">Playlist Title</a><br>
                                This is a playlist
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px" id="c_custom" <? if (!$Design["c_custom"] || !$_USER->Is_Partner) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            <?= $_USER->displayname ?>
                        </div>
                        <div class="ch_prv_n_in">
                            <b style="color:red">This is my custom box</b>
                            <div style="text-align:right;text-decoration: underline">It belongs to me!</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px;margin-top:6px" id="c_recent" <? if (!$Design["c_recent"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            Recent Activity
                        </div>
                        <div class="ch_prv_n_in">
                            <table>
                                <tr>
                                    <td valign="bottom"><img width="12" class="not_me" src="/img/ra1.png"></td>
                                    <td valign="middle"><div style="position: relative;bottom:2px;left:4px">I wonder how old I am......</div></td>
                                </tr>
                                <tr>
                                    <td valign="bottom"><img width="12" class="not_me" src="/img/ra1.png"></td>
                                    <td valign="middle"><div style="position: relative;bottom:1px;left:4px">Why are birds so dumb?</div></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style="margin-bottom: 12px" id="c_comments" <? if (!$Design["c_comments"]) : ?>class="hddn"<? endif ?>>
                        <div class="ch_prv_n_hd">
                            Channel Comments (2)
                        </div>
                        <div class="ch_prv_n_in">
                            <div style="margin-bottom: 5px;overflow:hidden">
                                <div style="float:left;margin-right: 4px">
                                    <img src="/img/no.png" width="33" height="33">
                                </div>
                                <div style="float:left;width:130px;position:relative;bottom:2px">
                                    <strong>SomeUser1</strong><br>
                                    I really like your content!
                                </div>
                            </div>
                            <div class="cl"></div>
                            <div>
                                <div style="float:left;margin-right: 4px">
                                    <img src="/img/no.png" width="33" height="33">
                                </div>
                                <div style="float:left;position:relative;bottom:2px">
                                    <strong>SomeUser31</strong><br>
                                    Let's have a talk!!!!11
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="cl"></div>
    <input type="submit" value="Save Changes" name="update_customization">
</form>
<script src="/js/jscolor.min.js"></script>
<script>
    function hex2rgba(hex,opacity){
        hex = hex.replace('#','');
        r = parseInt(hex.substring(0, hex.length/3), 16);
        g = parseInt(hex.substring(hex.length/3, 2*hex.length/3), 16);
        b = parseInt(hex.substring(2*hex.length/3, 3*hex.length/3), 16);

        result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
        return result;
    }

    function bg(c) {
        $("#ch_prv").css("background-color",hex2rgba("#"+c,100));
    }
    function nav(c) {
        $("#ch_prv_nav").find("a").css("color",hex2rgba("#"+c,100));
    }
    function links(c) {
        $(".cu_link").css("color",hex2rgba("#"+c,100));
    }
    function hl_b(c) {
        $(".ch_prv_hl_hd").css("background-color",hex2rgba("#"+c,trans2));
        $(".ch_prv_hl_in").css("border","1px solid "+hex2rgba("#"+c,trans2));
        $(".ch_prv_hl_in").css("border-top","");

    }
    function hl_bf(c) {
        $(".ch_prv_hl_hd").css("color",hex2rgba("#"+c,100));
    }
    function hl_i(c) {
        $(".ch_prv_hl_in").css("background-color",hex2rgba("#"+c,trans2));
    }
    function hl_if(c) {
        $(".ch_prv_hl_in").css("color",hex2rgba("#"+c,100));
        $("#ch_prv_nav").find("a").css("border-right-color",hex2rgba("#"+c,100));
    }
    function nm_b(c) {
        $(".ch_prv_n_hd").css("background-color",hex2rgba("#"+c,trans1));
        $(".ch_prv_n_in").css("border","1px solid "+hex2rgba("#"+c,trans1));
        $(".ch_prv_n_in").css("border-top","");
    }
    function nm_bf(c) {
        $(".ch_prv_n_hd").css("color",hex2rgba("#"+c,100));
    }
    function nm_i(c) {
        $(".ch_prv_n_in").css("background-color",hex2rgba("#"+c,trans1));
    }
    function nm_if(c) {
        $(".ch_prv_n_in").css("color",hex2rgba("#"+c,100));
    }
    function delete_background_new() {
        $("#ch_prv").css("background-image","url('')");
        _("bg_delete").disabled = true;
        var formdata = new FormData();
        if (window.XMLHttpRequest) {
            var ajax = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            var ajax = new ActiveXObject("Microsoft.XMLHTTP");
        }
        formdata.append("bg", "ar");

        ajax.addEventListener("load", bg_del_comp_new, false);

        ajax.open("POST", "/ajax/delete_background");
        ajax.send(formdata);
    }

    function bg_del_comp_new() {
        _("bg_upload").style.display = "block";
        _("bg_delete").style.display = "none";
    }

    function cu_fnt() {
        var num = $("#ch_fnt").val();
        if (num == 0) {
            var font = "Arial";
        } else if (num == 1) {
            var font = "Georgia";
        } else if (num == 2) {
            var font = "Times New Roman";
        } else if (num == 3) {
            var font = "Comic Sans MS";
        } else if (num == 4) {
            var font = "Impact";
        } else if (num == 5) {
            var font = "Tahoma";
        } else if (num == 6) {
            var font = "Courier New";
        }

        $("#ch_prv").css("font-family",font);
    }

    function change_repeat() {
        var num = $("#ch_repeat").val();
        if (num == 1) {
            var font = "no-repeat";
        } else if (num == 2) {
            var font = "repeat";
        } else if (num == 3) {
            var font = "repeat-x";
        } else if (num == 4) {
            var font = "repeat-y";
        }

        $("#ch_prv").css("background-repeat",font);
    }

    function change_position() {
        var num = $("#ch_position").val();
        if (num == 1) {
            var font = "top";
        } else if (num == 2) {
            var font = "center";
        } else if (num == 3) {
            var font = "bottom";
        }

        $("#ch_prv").css("background-position",font);
    }
</script>