<? if ($_THEMES->Header == 1) : ?>
<header id="pr_hd">
    <div class="pr_hd_wrapper">
        <a href="/"><img src="/<?= $LOGO_VALUE ?>" alt="VidLii" id="hd_vidlii"></a>
        <nav>
            <ul>
                <a href="/"><li>Home</li></a><a href="/videos"><li>Videos</li></a><a href="/channels" id="pr_sel"><li>Channels</li></a><a href="/community"><li>Community</li></a>
            </ul>
        </nav>
        <nav id="sm_nav">
            <? if (!$_USER->logged_in) : ?>
            <a href="/register">Sign Up</a><a href="/help">Help</a><a href="/login">Sign In</a>
            <div id="login_modal">
                <form action="/login" method="POST">
                    <input type="password" class="search_bar" placeholder="Your Password" style="display:none">
                        <? if (mt_rand(0,1) == 1) : ?><input type="password" class="search_bar" placeholder="Your Password" style="display:none"><? endif ?>
                        <input type="text" name="v_username" class="search_bar" placeholder="Username/E-Mail">
                        <input type="password" name="<?= substr($_SESSION["secret_id"], 1, 3) ?>_password" class="search_bar" placeholder="Your Password">
                        <? if (mt_rand(0,1) == 1) : ?><input type="password" class="search_bar" placeholder="Your Password" style="display:none"><? endif ?>
                        <? if (mt_rand(0,1) == 1) : ?>
                            <input type="hidden" name="<?= substr($_SESSION["secret_id"], 6, 4) ?>" value="<?= substr($_SESSION["secret_id"], 1, 5).substr(user_ip(), 0, 2) ?>">
                            <input type="hidden" name="<?= mt_rand(0,1000) ?>" value="<?= mt_rand(0,1000) ?>">
                            <input type="hidden" name="<?= substr($_SESSION["secret_id"], 8, 4) ?>" value="<?= substr($_SESSION["secret_id"], 3, 5).substr(user_ip(), 0, 2) ?>">
                        <? else : ?>
                            <input type="hidden" name="<?= substr($_SESSION["secret_id"], 8, 4) ?>" value="<?= substr($_SESSION["secret_id"], 3, 5).substr(user_ip(), 0, 2) ?>">
                            <input type="hidden" name="fA6aavb" value="<?= mt_rand(0,1000) ?>cd">
                            <input type="hidden" name="<?= substr($_SESSION["secret_id"], 6, 4) ?>" value="<?= substr($_SESSION["secret_id"], 1, 5).substr(user_ip(), 0, 2) ?>">
                        <? endif ?>
                        <input type="submit" name="submit_login" class="search_button" value="Sign In">
                        <div class="forgot_pass"><a href="/forgot_password">Forgot Password?</a></div>
                </form>
            </div>
            <? else : ?>
                <a href="/user/<?= $_USER->displayname ?>" id="hd_name"><?= $_USER->displayname ?><img id="n_ar" src="/img/dar.png"></a><a href="/my_account">Account</a><a href="/inbox" id="inbox_hd"<? if ($Inbox_Amount > 0) : ?>style="padding-left:34px !important;"<? endif ?>><img src="/img/amsg<? if ($Inbox_Amount == 0) : ?>0<? else : ?>1<? endif ?>.png"<? if ($Inbox_Amount > 0) : ?> style="bottom:2px"<? endif ?>><span>(<?= $Inbox_Amount ?>)</span></a><a href="/help">Help</a><a href="/logout">Log Out</a>
                <div id="name_nav">
                    <div>
                        <a href="/user/<?= $_USER->displayname ?>">My Channel</a>
                        <? if ($_USER->Is_Admin || $_USER->Is_Mod) : ?><a href="/admin/login">Admin Panel</a><? endif ?>
                        <a href="/my_videos">My Videos</a>
                        <a href="/my_favorites">My Favorites</a>
                        <a href="/my_subscriptions">Subscriptions</a>
                        <a href="/friends">Friends</a>
                        <a href="/inbox">Inbox</a>
                    </div>
                </div>
            <? endif ?>
        </nav>
        <div class="pr_hd_bar">
            <form action="/results" method="GET">
                <input type="search" name="q" maxlength="256" class="search_bar" autofocus> 
                <select name="f">
					<option>All</option>
					<option value="1">Videos</option>
					<option value="2">Members</option>
				</select>
                <input type="submit" class="search_button" value="Search">
            </form>
            <a href="/upload" class="yel_btn">Upload</a>
        </div>
    </div>
</header>
<? else : ?>
    <header class="s_head" style="background: white;margin-top:0;padding: 6px 5px;margin-bottom:0">
        <div style="width:1000px;margin:0 auto;position:relative">
        <div style="overflow:hidden">
            <a href="/"><img src="/<?= $LOGO_VALUE ?>" alt="VidLii" title="VidLii - Display Yourself."></a>
            <div class="s_search">
                <form action="/results" method="GET">
                    <input type="search" name="q" maxlength="256" <? if ($_PAGE->Current_Page !== "login" && $_PAGE->Current_Page !== "register" && !isset($_GET["q"])) : ?>autofocus<? elseif (isset($_GET["q"])) : ?> value="<?= $_GET["q"] ?>"<? endif ?>><input type="submit" value="Search">
                </form>
            </div>
            <a href="javascript:void(0)" class="s_a" onclick="$('#s_toggle2').toggleClass('hddn')">
                Browse
            </a>
            <div id="s_toggle2" class="hddn">
                <a href="/videos">Videos</a>
                <a href="/channels">Channels</a>
                <a href="/community">Community</a>
            </div>
            <a href="/upload" class="s_a">
                Upload
            </a>
        </div>
        <div class="s_center" style="top:17px;right:7px">
            <? if (!$_USER->logged_in) : ?>
                <a href="/register" style="margin-right:13px;padding-right:13px;border-right: 1px solid #ccc;">
                    Create Account
                </a>
                <a href="/login" class="sign_out">
                    Sign In
                </a>
            <? else : ?>
                <div id="s_username" onclick="$('#s_toggle').toggleClass('hddn'); $('#s_username').toggleClass('s_username_clicked')">
                    <?= $_USER->displayname ?>
                </div>
                <span id="s_toggle" class="hddn">
                <div>
                <a href="/user/<?= $_USER->displayname ?>">My Channel</a>
                <a href="/inbox">Inbox</a>
                </div>
                <div>
                <a href="/my_account">Account</a>
                <a href="/my_subscriptions">Subscriptions</a>
                </div>
                <div>
                <a href="/my_videos">Videos</a>
                <a href="/friends">Friends</a>
                </div>
                    <? if ($_USER->Is_Admin || $_USER->Is_Mod) : ?>
                        <div style="width:100%">
                    <a style="width:91%;text-align:center" href="/admin/login">Admin Panel</a>
                </div>
                    <? endif ?>
            </span>
                <a href="/logout" class="sign_out">
                    Sign Out
                </a>
            <? endif ?>
        </div>
        </div>
    </header>
<? endif ?>
<? if ($_USER->logged_in && $_USER->username === $Profile["username"]) : ?>
<div class="pr_edit_ch">
    <div>
        <div class="pr_edit_btn" id="settings">Settings</div><div class="pr_edit_btn" id="themes">Themes and Colors</div><div class="pr_edit_btn" id="modules">Modules</div><div class="pr_edit_btn" id="vap">Videos and Playlists</div>
        <div class="pr_edit_box hddn" id="edit_settings">
            <form action="/user/<?= $_USER->displayname ?>" method="POST">
                <div style="padding: 0 10px 0 0;border-right:1px dotted #bbb;width:50%;float:left;">
                    <table cellpadding="7" cellspacing="0" style="width: 100%;">
                        <tr>
                            <td>URL:</td>
                            <td align="right"><a href="/user/<?= $_USER->displayname ?>">/user/<?= $_USER->displayname ?></a></td>
                        </tr>
                        <tr>
                            <td>Channel Title:</td>
                            <td align="right"><input type="text" name="channel_title" maxlength="80" style="width: 225px" value="<?= $Profile["channel_title"] ?>"></td>
                        </tr>
                        <tr>
                            <td style="border: 0">Channel Type:</td>
                            <td style="border: 0" align="right">
                                <select name="channel_type" style="padding:1.5px;">
                                    <option value="0"<? if ($Profile["channel_type"] == 0) : ?> selected<? endif ?>>Default</option>
                                    <option value="1"<? if ($Profile["channel_type"] == 1) : ?> selected<? endif ?>>Director</option>
                                    <option value="2"<? if ($Profile["channel_type"] == 2) : ?> selected<? endif ?>>Musician</option>
                                    <option value="3"<? if ($Profile["channel_type"] == 3) : ?> selected<? endif ?>>Comedian</option>
                                    <option value="4"<? if ($Profile["channel_type"] == 4) : ?> selected<? endif ?>>Gamer</option>
                                    <option value="5"<? if ($Profile["channel_type"] == 5) : ?> selected<? endif ?>>Reporter</option>
                                    <option value="6"<? if ($Profile["channel_type"] == 6) : ?> selected<? endif ?>>Guru</option>
                                    <option value="7" <? if ($Profile["channel_type"] == 7) : ?> selected<? endif ?>>Animator</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            <div style="width:47%;float:left;padding-left:8px">
                <table cellpadding="7" cellspacing="0" style="width: 100%">
                    <tr>
                        <td style="border: 0" valign="top">Channel Tags:</td>
                        <td style="border: 0" align="right"><textarea name="channel_tags" maxlength="256" style="width:330px;height:62px;resize:none"><?= $Profile["channel_tags"] ?></textarea><br><span style="font-size:12.5px;position:relative;left:-16px;">Tags are keywords used to help people find your channel.</span></td>
                    </tr>
                </table>
            </div>
            <div style="clear:both"></div>
            <div style="height:10px;"></div>
            <div style="width: 100%; border-top: 1px solid #ccc;padding-top:6px">
                <input type="submit" value="Save Changes" name="save_settings">
            </div>
            </form>
        </div>
        <div class="pr_edit_box hddn" id="edit_themes">
            <div style="margin-bottom:10px">
                <div id="default" onclick="theme_select('grey')" class="theme_selector<? if ($Profile["theme"] == 0) : ?> theme_sel<? endif ?>" style="font-family:arial"><div style="background-color: #CCCCCC;color:#333333;padding: 3px;line-height:120%"><div style="background-color: #999999;color: #000000;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#eeeeff;font-size:9px;padding-left:1px;color:#333333"><span style="color:#000000;font-size:120%">A</span> &nbsp;<span style="color:#0000cc;text-decoration:underline">url</span><br>abc</div><span style="color:#0000cc;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Grey</span></div></div>
                <div id="blue" onclick="theme_select('blue')" class="theme_selector<? if ($Profile["theme"] == 1) : ?> theme_sel<? endif ?>" style="font-family:arial"><div style="background-color: #003366;color:#ffffff;padding: 3px;line-height:120%"><div style="background-color: #0066CC;color: #ffffff;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#3D8BD8;font-size:9px;padding-left:1px;color:#ffffff"><span style="color:#ffffff;font-size:120%">A</span> &nbsp;<span style="color:#99C2EB;text-decoration:underline">url</span><br>abc</div><span style="color:#0000CC;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Blue</span><br></div></div>
                <div id="red" onclick="theme_select('red')" class="theme_selector<? if ($Profile["theme"] == 2) : ?> theme_sel<? endif ?>" style="font-family:arial"><div style="background-color: #660000;color:#FFFFFF;padding: 3px;line-height:120%"><div style="background-color: #990000;color: #FFFFFF;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#660000;font-size:9px;padding-left:1px;color:#FFFFFF"><span style="color:#FFFFFF;font-size:120%">A</span> &nbsp;<span style="color:#FF0000;text-decoration:underline">url</span><br>abc</div><span style="color:#FF0000;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Red</span><br></div></div>
                <div id="yellow" onclick="theme_select('yellow')" class="theme_selector<? if ($Profile["theme"] == 3) : ?> theme_sel<? endif ?>" style="font-family:arial"><div style="background-color: #FFE599;color:#E69138;padding: 3px;line-height:120%"><div style="background-color: #E69138;color: #FFFFFF;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#FFD966;font-size:9px;padding-left:1px;color:#E69138"><span style="color:#E69138;font-size:120%">A</span> &nbsp;<span style="color:#E69138;text-decoration:underline">url</span><br>abc</div><span style="color:#FFD966;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Sunlight</span><br></div></div>
                <div id="green" onclick="theme_select('green')" class="theme_selector<? if ($Profile["theme"] == 4) : ?> theme_sel<? endif ?>" style="font-family:Arial"><div style="background-color: #274E13;color:#274E13;padding: 3px;line-height:120%"><div style="background-color: #38761D;color: #ffffff;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#6AA84F;font-size:9px;padding-left:1px;color:#274E13"><span style="color:#274E13;font-size:120%">A</span> &nbsp;<span style="color:#38761D;text-decoration:underline">url</span><br>abc</div><span style="color:#FFFFFF;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Forest</span><br></div></div>
                <div id="black" onclick="theme_select('black')" class="theme_selector<? if ($Profile["theme"] == 5) : ?> theme_sel<? endif ?>" style="font-family:Courier New"><div style="background-color: #666666;color:#666666;padding: 3px;line-height:120%"><div style="background-color: #444444;color: #FFFFFF;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#000000;font-size:9px;padding-left:1px;color:#666666"><span style="color:#AAAAAA;font-size:120%">A</span> &nbsp;<span style="color:#FF0000;text-decoration:underline">url</span><br>abc</div><span style="color:#FF0000;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">8-bit</span><br></div></div>
                <div id="pink" onclick="theme_select('pink')" class="theme_selector<? if ($Profile["theme"] == 6) : ?> theme_sel<? endif ?>" style="font-family:arial"><div style="background-color: #ff99cc;color:#333366;padding: 3px;line-height:120%"><div style="background-color: #aa66cc;color: #ffffff;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#ffffff;font-size:9px;padding-left:1px;color:#333366"><span style="color:#8a2c87;font-size:120%">A</span> &nbsp;<span style="color:#351C75;text-decoration:underline">url</span><br>abc</div><span style="color:#351C75;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Princess</span><br></div></div>
                <div id="fire" onclick="theme_select('fire')" class="theme_selector<? if ($Profile["theme"] == 7) : ?> theme_sel<? endif ?>" style="font-family:arial"><div style="background-color: #660000;color:#ffffff;padding: 3px;line-height:120%"><div style="background-color: #FF0000;color: #ffffff;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#FF9900;font-size:9px;padding-left:1px;color:#ffffff"><span style="color:#FFFF00;font-size:120%">A</span> &nbsp;<span style="color:#FFDBA6;text-decoration:underline">url</span><br>abc</div><span style="color:#FFFF00;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Fire</span><br></div></div>
                <div id="stealth" onclick="theme_select('stealth')" class="theme_selector<? if ($Profile["theme"] == 8) : ?> theme_sel<? endif ?>" style="font-family:arial"><div style="background-color: #000000;color:#444444;padding: 3px;line-height:120%"><div style="background-color: #444444;color: #000000;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#666666;font-size:9px;padding-left:1px;color:#444444"><span style="color:#000000;font-size:120%">A</span> &nbsp;<span style="color:#444444;text-decoration:underline">url</span><br>abc</div><span style="color:#CCCCCC;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Stealth</span><br></div></div>
                <div id="custom" onclick="theme_select('custom')" class="theme_selector<? if ($Profile["theme"] == 9) : ?> theme_sel<? endif ?>" style="font-family:arial"><div style="background-color: #CCCCCC;color:#333333;padding: 3px;line-height:120%"><div style="background-color: #999999;color: #000000;padding:3px;font-size:10px"><div style="float:right;width:4em;background-color:#eeeeff;font-size:9px;padding-left:1px;color:#333333"><span style="color:#000000;font-size:120%">A</span> &nbsp;<span style="color:#0000cc;text-decoration:underline">url</span><br>abc</div><span style="color:#0000cc;text-decoration:underline">url</span><br>abc</div></div><div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">Custom</span></div></div>
            </div>
            <div style="clear:both"></div>
            <form action="/user/<?= $_USER->displayname ?>" method="POST" enctype="multipart/form-data">
            <div style="background-color: #eee;border-radius:5px;padding:12px;margin:15px 0 0;overflow:hidden">
                <div style="float:left;font-size:20px;position: relative;top:2px;">"<span id="theme_title"><? if ($Profile["theme"] == 0) : ?>Grey<? elseif ($Profile["theme"] == 1) : ?>Blue<? elseif ($Profile["theme"] == 2) : ?>Red<? elseif ($Profile["theme"] == 3) : ?>Sunlight<? elseif ($Profile["theme"] == 4) : ?>Forest<? elseif ($Profile["theme"] == 5) : ?>8-Bit<? elseif ($Profile["theme"] == 6) : ?>Princess<? elseif ($Profile["theme"] == 7) : ?>Fire<? elseif ($Profile["theme"] == 8) : ?>Stealth<? else : ?>Custom<? endif ?></span>"</div>
                <a href="javascript:void(0)" onclick="show_advanced_custom()" id="show_advanced_btn" style="position:relative;top:5.5px;left:16px;">show advanced options</a>
                <div style="float:right"><input type="submit" value="Save Changes" name="save_customization" style="padding:3px"></div>
            </div>
            <div id="advanced_customization" style="display:none;margin-top:14px">
            <input type="hidden" name="theme" id="theme_selectnum" value="<?= $Profile["theme"] ?>">
            <div style="width:49%;float:left;padding:0 15px;border-right:1px dotted #aaa">
                <span>General</span>
                <table cellpadding="6" cellspacing="0" style="width:100%">
                    <tr>
                        <td>Background Color</td>
                        <td align="right"><input type="text" id="ed_bg_color" name="bgcolor" value="#<?= $Profile["bg"] ?>" class="jscolor {mode:'HVS', hash:true, onFineChange:'bg(this)'}"></td>
                    </tr>
                    <tr>
                        <td>Wrapper Color</td>
                        <td align="right"><input type="text" id="ed_wrp_color" name="wrappercolor" value="#<?= $Profile["h_head"] ?>" class="jscolor {mode:'HVS', hash:true, onFineChange:'wrapper(this)'}"></td>
                    </tr>
                    <tr>
                        <td>Wrapper Text Color</td>
                        <td align="right"><input type="text" id="ed_wrptxt_color" name="wrappertxtcolor" value="#<?= $Profile["h_in_fnt"] ?>" class="jscolor {mode:'HVS', hash:true, onFineChange:'wrapper_text(this)'}"></td>
                    </tr>
                    <tr>
                        <td>Wrapper Link Color</td>
                        <td align="right"><input type="text" id="ed_wrplnk_color" name="wrapperlinkcolor" value="#<?= $Profile["h_head_fnt"] ?>" class="jscolor {mode:'HVS', hash:true, onFineChange:'wrapper_links(this)'}"></td>
                    </tr>
                    <tr>
                        <td>Wrapper Transparency</td>
                        <td style="text-align:right"><input type="range" maxlength="7" min="0" max="100" step="1" name="n_trans" value="<?= $Profile["n_trans"] ?>" oninput="trans1 = (100 - this.value);wrapper(document.getElementById('ed_wrp_color').value.replace('#',''));document.getElementById('hb_v2').innerHTML = this.value+'%'" onchange="document.getElementById('hb_v2').innerHTML = this.value+'%'"><span style="position:relative;font-size: 14px;bottom: 5.5px; left:6px" id="hb_v2"><?= $Profile["n_trans"] ?>%</span></td>
                    </tr>
                    <tr>
                        <td>Channel Background</td>
                        <td align="right"><input type="file" name="bg_upload" id="bg_upload"<? if ($Has_Background) : ?> style="display: none;width:250px"<? endif ?>style="width:250px"><button id="bg_delete" onclick="delete_background()" type="button"<? if (!$Has_Background) : ?> style="display: none"<? endif ?>>Delete Background</button><span id="bg_info" style="display:block;font-size:11px;width:250px;text-align:left;color:gray;margin:3px 0 0;<? if ($Has_Background == true ) : ?>display:none<? endif ?>">Upload an image that will display as a background on your channel (maximum 500KB).</span></td>
                    </tr>
                    <tr>
                        <td>Background Repeat / Position</td>
                        <td align="right">
                            <select name="bg_repeat">
                                <option value="1"<? if ($Profile["bg_repeat"] == 1) : ?> selected<? endif ?>>No Repeat</option>
                                <option value="2"<? if ($Profile["bg_repeat"] == 2) : ?> selected<? endif ?>>Repeat</option>
                                <option value="3"<? if ($Profile["bg_repeat"] == 3) : ?> selected<? endif ?>>Repeat X</option>
                                <option value="4"<? if ($Profile["bg_repeat"] == 4) : ?> selected<? endif ?>>Repeat Y</option>
                            </select>
                            <select name="bg_position">
                                <option value="1"<? if ($Profile["bg_position"] == 1) : ?> selected<? endif ?>>Top</option>
                                <option value="2"<? if ($Profile["bg_position"] == 2) : ?> selected<? endif ?>>Middle</option>
                                <option value="3"<? if ($Profile["bg_position"] == 3) : ?> selected<? endif ?>>Bottom</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="middle"><label><input type="checkbox" name="bg_stretch"<? if ($Profile["bg_stretch"] == 1) : ?> checked<? endif ?>> <span style="position:relative;bottom:2px">Stretch Background</span></label> <label style="margin:0 0 0 100px"><input type="checkbox" name="bg_fixed"<? if ($Profile["bg_fixed"] == 1) : ?> checked<? endif ?>> <span style="position:relative;bottom:2px">Fixed Background</span></label></td>
                    </tr>
                </table>
            </div>
            <div style="width:46%;float:left;padding-left:15px">
                <span>Other Options</span>
                <table cellpadding="6" cellspacing="0" style="width:100%">
                    <tr>
                        <td>Background Color</td>
                        <td align="right"><input id="in_wrapper" name="inbgcolor" value="#<?= $Profile["n_in"] ?>" type="text" class="jscolor {mode:'HVS', hash:true, onFineChange:'in_bg(this)'}"></td>
                    </tr>
                    <tr>
                        <td>Title Text Color</td>
                        <td align="right"><input type="text" name="titletxtcolor" value="#<?= $Profile["n_head_fnt"] ?>" id="col_wrapper" class="jscolor {mode:'HVS', hash:true, onFineChange:'in_hd(this)'}"></td>
                    </tr>
                    <tr>
                        <td>Link Color</td>
                        <td align="right"><input type="text" id="in_link" name="inlinkcolor" value="#<?= $Profile["links"] ?>" class="jscolor {mode:'HVS', hash:true, onFineChange:'in_link(this)'}"></td>
                    </tr>
                    <tr>
                        <td>Body Text Color</td>
                        <td align="right"><input type="text" id="in_txt" name="intxtcolor" value="#<?= $Profile["n_in_fnt"] ?>" class="jscolor {mode:'HVS', hash:true, onFineChange:'in_text(this)'}"></td>
                    </tr>
                    <tr>
                        <td>Transparency</td>
                        <td style="text-align:right"><input type="range" maxlength="7" min="0" max="100" step="1" name="h_trans" value="<?= $Profile["h_trans"] ?>" oninput="trans2 = (100 - this.value);in_bg(document.getElementById('in_wrapper').value.replace('#',''));document.getElementById('hb_v1').innerHTML = this.value+'%'" onchange="document.getElementById('hb_v1').innerHTML = this.value+'%'"><span style="position:relative;font-size: 14px;bottom: 5.5px; left:6px" id="hb_v1"><?= $Profile["h_trans"] ?>%</span></td>
                    </tr>
                    <tr>
                        <td>Font</td>
                        <td style="text-align:right">
                            <select name="ch_fnt" onchange="fntpreview()" id="ch_fnt">
                                <option value="0"<? if ($Profile["font"] == 0) : ?> selected<? endif ?>>Arial</option>
                                <option value="1"<? if ($Profile["font"] == 1) : ?> selected<? endif ?>>Georgia</option>
                                <option value="2"<? if ($Profile["font"] == 2) : ?> selected<? endif ?>>Times New Roman</option>
                                <option value="3"<? if ($Profile["font"] == 3) : ?> selected<? endif ?>>Comic Sans MS</option>
                                <option value="4"<? if ($Profile["font"] == 4) : ?> selected<? endif ?>>Impact</option>
                                <option value="5"<? if ($Profile["font"] == 5) : ?> selected<? endif ?>>Tahoma</option>
                                <option value="6"<? if ($Profile["font"] == 6) : ?> selected<? endif ?>>Courier New</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Channel Radius</td>
                        <td style="text-align:right"><input type="range" maxlength="7" min="0" max="9" step="1" name="chn_radius" value="<?= $Profile["chn_radius"] ?>" oninput="$('.ob_col, .mnu_vid, .in_box, .pr_tp_pl_inf, .pr_pl_mnu').css('border-radius',this.value+'px');$('.pr_pl_title').css('border-bottom-right-radius','0');$('.pr_pl_title').css('border-top-right-radius','0');document.getElementById('hb_v4').innerHTML = this.value+'px'" onchange="$('.ob_col, .pr_pl_mnu, .in_box, .mnu_vid, .pr_tp_pl_inf').css('border-radius',this.value+'px');$('.pr_pl_title').css('border-bottom-right-radius','0');$('.pr_pl_title').css('border-top-right-radius','0');document.getElementById('hb_v4').innerHTML = this.value+'px'"><span style="position:relative;font-size: 14px;bottom: 5.5px; left:6px" id="hb_v4"><?= $Profile["chn_radius"] ?>px</span></td>
                    </tr>
                    <tr>
                        <td>Avatar Radius</td>
                        <td style="text-align:right"><input type="range" maxlength="7" min="0" max="9" step="1" name="avt_radius" value="<?= $Profile["avt_radius"] ?>" oninput="$('.pr_avt, .avt2').css('border-radius',this.value+'px');document.getElementById('hb_v3').innerHTML = this.value+'px'" onchange="$('.pr_avt, .avt2').css('border-radius',this.value+'px');document.getElementById('hb_v3').innerHTML = this.value+'px'"><span style="position:relative;font-size: 14px;bottom: 5.5px; left:6px" id="hb_v3"><?= $Profile["avt_radius"] ?>px</span></td>
                    </tr>
                </table>
            </div>
            </div>
        </div>
        </form>
        <form action="/user/<?= $_USER->displayname ?>" method="POST">
        <div class="pr_edit_box hddn" id="edit_modules">
            <div style="width:32%;float:left;padding:4px"><label><input style="position:relative;top:1.5px" type="checkbox" name="comments"<? if ($Profile["c_comments"]) : ?> checked<? endif ?>> Comments</label></div>
            <div style="width:32%;float:left;padding:4px"><label><input style="position:relative;top:1.5px" type="checkbox" name="friends"<? if ($Profile["c_friend"]) : ?> checked<? endif ?>> Friends</label></div>
            <div style="width:32%;float:left;padding:4px"><label><input style="position:relative;top:1.5px" type="checkbox" name="featured_channels"<? if ($Profile["c_featured_channels"]) : ?> checked<? endif ?>> Featured Channels</label></div>
            <div style="width:32%;float:left;padding:4px"><label><input style="position:relative;top:1.5px" type="checkbox" name="recent"<? if ($Profile["c_recent"]) : ?> checked<? endif ?>> Recent Activity</label></div>
            <div style="width:32%;float:left;padding:4px"><label><input style="position:relative;top:1.5px" type="checkbox" name="subscribers"<? if ($Profile["c_subscriber"]) : ?> checked<? endif ?>> Subscribers</label></div>
            <div style="width:32%;float:left;padding:4px"><label><input style="position:relative;top:1.5px" type="checkbox" name="subscriptions"<? if ($Profile["c_subscription"]) : ?> checked<? endif ?>> Subscriptions</label></div>
            <? if ($_USER->Is_Partner) : ?><div style="width:32%;float:left;padding:4px"><label><input style="position:relative;top:1.5px" type="checkbox" name="custom"<? if ($Profile["c_custom"]) : ?> checked<? endif ?>> Custom Box</label></div><? endif ?>
            <div style="clear:both"></div>
            <div style="height:10px;"></div>
            <div style="width: 100%; border-top: 1px solid #ccc;padding-top:6px">
                <input type="submit" value="Save Changes" name="save_modules">
            </div>
        </form>
        </div>
        <div class="pr_edit_box hddn" id="edit_vap">
            <form action="/user/<?= $_USER->displayname ?>" method="POST">
            <div style="padding: 3px 10px 0 4px;border-right:1px dotted #bbb;width:33%;float:left;height:140px;font-size:13px;">
                <div style="margin-bottom:5px">Which content would you like to display?</div>
                <div style="border:1px solid #bbb;padding:8px 10px 10px">
                    <style>
                        .show_label input {
                            margin: 6px 7px 6px 0;
                            position: relative;
                            top: 1.5px
                        }
                    </style>
                    <label class="show_label"><input type="checkbox" name="show_all"<? if ($Profile["c_all"] == 1) : ?> checked<? endif ?>>All</label><br>
                    <label class="show_label"><input type="checkbox" name="show_videos"<? if ($Profile["c_videos"] == 1) : ?> checked<? endif ?>>My Uploads</label><br>
                    <label class="show_label"><input type="checkbox" name="show_favorites"<? if ($Profile["c_favorites"] == 1) : ?> checked<? endif ?>>My Favorites</label><br>
                    <label class="show_label"><input type="checkbox" name="show_playlists"<? if ($Profile["c_playlists"] == 1) : ?> checked<? endif ?>>My Playlists</label>
                </div>
            </div>
            <div style="padding: 0 10px 0 10px;border-right:1px dotted #bbb;width:31%;float:left;height:143px;font-size:13px;line-height:23px">
                Featured Layout<br>
                <select name="pl_layout" style="width:180px;padding:2px;">
                    <option value="0"<? if ($Profile["default_view"] == 0) : ?> selected<? endif ?>>Player View</option>
                    <option value="1"<? if ($Profile["default_view"] == 1) : ?> selected<? endif ?>>Grid View</option>
                </select>
                <br>
                <br>
                <br>
                Featured Video<br>
                <select name="ft_video" style="width:180px;padding:2px;" onchange="$('#f_cha').toggleClass('hddn')">
                    <option<? if ($Profile["c_featured"]) : ?> selected<? endif ?> value="1">Most Recent Video</option>
                    <option<? if (!$Profile["c_featured"]) : ?> selected<? endif ?> value="0">Custom Video</option>
                </select>
            </div>
            <div style="padding: 0 10px 0 0;width:30%;float:left;">
                <div id="f_cha"<? if ($Profile["c_featured"]) : ?> class="hddn"<? endif ?> style="padding:5px 0 0 10px">
                    Featured Video
                    <div style="margin: 18px 0 13px">
                        <div style="margin-bottom:4px">For Non-Subscribers</div>
                        <input type="url" style="width:225px" name="n_url" value="<? if (!empty($Profile["featured_n_url"])) : ?>/watch?v=<?= $Profile["featured_n_url"] ?><? endif ?>" placeholder="/watch?v=..." maxlength="128" autocomplete="off">
                    </div>
                    <div>
                        <div style="margin-bottom:4px">For Subscribers</div>
                        <input type="url" style="width:225px" name="s_url" value="<? if (!empty($Profile["featured_s_url"])) : ?>/watch?v=<?= $Profile["featured_s_url"] ?><? endif ?>" placeholder="/watch?v=..." maxlength="128" autocomplete="off">
                    </div>
                </div>
            </div>
            <div style="clear:both"></div>
            <div style="height:10px;"></div>
            <div style="width: 100%; border-top: 1px solid #ccc;padding-top:6px">
                <input type="submit" value="Save Changes" name="save_players">
            </div>
            </form>
        </div>
    </div>
</div>
    <script>$("#modules").trigger( "click" );</script>
<? endif ?>