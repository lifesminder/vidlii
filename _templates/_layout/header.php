<? if ($_THEMES->Header == 1) : ?>
<header class="n_head">
    <div class="pr_hd_wrapper">
        <a href="/"><img src="/<?= $LOGO_VALUE ?>" alt="VidLii" title="VidLii - Display Yourself." id="hd_vidlii"></a>
        <nav>
            <ul>
                <a href="/"<? if ($_PAGE->Page_Type == "Home") : ?> id="pr_sel"<? endif ?>><li>Home</li></a><a href="/videos"<? if ($_PAGE->Page_Type == "Videos") : ?> id="pr_sel"<? endif ?>><li>Videos</li></a><a href="/channels"<? if ($_PAGE->Page_Type == "Channels") : ?> id="pr_sel"<? endif ?>><li>Channels</li></a><a href="/community"<? if ($_PAGE->Page_Type == "Community") : ?> id="pr_sel"<? endif ?>><li>Community</li></a>
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
                <input type="search" name="q" class="search_bar" maxlength="256" <? if ($_PAGE->Current_Page !== "login" && $_PAGE->Current_Page !== "register" && $_PAGE->Page !== "Send" && !isset($_GET["q"])) : ?>autofocus<? elseif (isset($_GET["q"])) : ?> value="<?= htmlspecialchars($_GET["q"], ENT_QUOTES) ?>"<? endif ?>> 
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
<header class="s_head">
    <div style="overflow:hidden">
    <a href="/"><img src="/<?= $LOGO_VALUE ?>" alt="VidLii" title="VidLii - Display Yourself."></a>
    <div class="s_search">
        <form action="/results" method="GET">
            <input type="search" name="q" maxlength="256" <? if ($_PAGE->Current_Page !== "login" && $_PAGE->Current_Page !== "register" && $_PAGE->Page !== "Send" && !isset($_GET["q"])) : ?>autofocus<? elseif (isset($_GET["q"])) : ?> value="<?= htmlspecialchars($_GET["q"], ENT_QUOTES) ?>"<? endif ?>><input type="submit" value="Search"<? if (strpos($_SERVER['HTTP_USER_AGENT'],"PaleMoon") !== false) : ?> style="padding:3px 7px"<? endif ?>>
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
    <div class="s_center">
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
</header>
<? endif ?>
