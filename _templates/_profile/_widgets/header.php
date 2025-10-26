<? if ($_THEMES->Header == 1) : ?>
	<header id="pr_hd" class="pr_hd1">
		<div class="pr_hd_wrapper">
			<a href="/"><img src="<?= $LOGO_VALUE ?>" alt="VidLii" id="hd_vidlii"></a>
			<nav>
				<ul>
                    <a href="/"<? if ($_PAGE->Page_Type == "Home") : ?> id="pr_sel"<? endif ?>><li><?php echo __("navbar_home", "Home"); ?></li></a>
                    <a href="/videos"<? if ($_PAGE->Page_Type == "Videos") : ?> id="pr_sel"<? endif ?>><li><?php echo __("navbar_videos", "Videos"); ?></li></a>
                    <a href="/channels" id="pr_sel"><li><?php echo __("navbar_channels", "Channels"); ?></li></a>
                    <a href="/community"<? if ($_PAGE->Page_Type == "Community") : ?> id="pr_sel"<? endif ?>><li><?php echo __("navbar_community", "Community"); ?></li></a>
				</ul>
			</nav>
			<nav id="sm_nav">
				<? if (!$_USER->logged_in) : ?>
					<a href="/signup">Sign Up</a><a href="/help">Help</a><a href="/login">Sign In</a>
                <div id="login_modal">
                    <form action="/login" method="POST">
                        <input type="text" name="username" class="search_bar" placeholder="Username/E-Mail">
                        <input type="password" name="password" class="search_bar" placeholder="Your Password">
                        <button type="submit" name="submit_login" class="search_button">Sign In</button>
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
					<input type="submit" value="Search" class="search_button">
				</form>
				<a href="/upload" class="yel_btn">Upload</a>
			</div>
		</div>
	</header>
<? else : ?>
    <header class="s_head" style="background: white;margin-top:0;padding: 6px 5px;border-bottom-left-radius: 6px;border-bottom-right-radius: 6px">
        <div style="overflow:hidden">
            <a href="/"><img src="<?= $LOGO_VALUE ?>" alt="VidLii" title="VidLii - Display Yourself."></a>
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
        <div class="s_center" style="top:23px;right:7px">
            <? if (!$_USER->logged_in) : ?>
                <a href="/signup" style="margin-right:13px;padding-right:13px;border-right: 1px solid #ccc;">
                    Create Account
                </a>
                <a href="/login" class="sign_out">
                    Sign In
                </a>
            <? else : ?>
                <div id="s_username" onclick="$('#s_toggle').toggleClass('hddn'); $('#s_username').toggleClass('s_username_clicked')">
                    <img src="/img/no.png" alt="<?= $_USER->displayname ?>" style="width: 24px; height: 24px; margin: -3px 2px 0 0; display: inline-block;">
                    <?= ($_USER->channel_title != "") ? $_USER->channel_title : $_USER->displayname ?>
                </div>
                <span id="s_toggle" class="hddn">
                    <div>
                    <a href="/user/<?= $_USER->displayname ?>">My Channel</a>
                    <a href="/inbox">Inbox</a>
                    <? if ($_USER->Is_Admin || $_USER->Is_Mod) : ?>
                    <div>
                        <a href="/admin/login">Admin Panel</a>
                    </div>
                    <? endif ?>
                    </div>
                    <div>
                    <a href="/my_account">Account</a>
                    <a href="/my_subscriptions">Subscriptions</a>
                    </div>
                    <div>
                    <a href="/my_videos">Videos</a>
                    <a href="/friends">Friends</a>
                    </div>
                    <div>
                        <a href="/logout" class="sign_out">Sign Out</a>
                    </div>
                </span>
            <? endif ?>
        </div>
    </header>
<? endif ?>
<?php
    if($Profile["banned"] == 0) {
        if($Banner_Links !== false)
            require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/banner.php";
        
        $username = $Profile["displayname"];
        $active = (isset($_GET["page"]) && $_GET["page"] != "") ? strtolower($_GET["page"]) : null;
?>
<div class="pr_lks" style="margin-top: 17px">
    <?php if(isset($_GET["page"])) { ?>
        <a href="<?= $handle ?>">Channel</a>
    <?php } if ($Profile["c_videos"] && $Profile["videos"] > 0) { ?>
        <a href="<?= $handle ?>/videos"<?php if($active == "videos") echo "class=\"active\""; ?>>Videos</a>
    <?php } if ($Profile["c_favorites"] && $Profile["favorites"] > 0) { ?>
        <a href="<?= $handle ?>/favorites"<?php if($active == "favorites") echo "class=\"active\""; ?>>Favorites</a>
    <?php } if($Profile["c_subscriber"] && $Profile["subscribers"] > 0) { ?>
        <a href="<?= $handle ?>/subscribers"<?php if($active == "subscribers") echo "class=\"active\""; ?>>Subscribers</a>
    <?php } if($Profile["c_subscription"] && $Profile["subscriptions"] > 0) { ?>
        <a href="<?= $handle ?>/subscriptions"<?php if($active == "subscriptions") echo "class=\"active\""; ?>>Subscriptions</a>
    <?php } if($Profile["c_friend"] && $Profile["friends"] > 0) { ?>
        <a href="<?= $handle ?>/friends"<?php if($active == "friends") echo "class=\"active\""; ?>>Friends</a>
    <?php } if($Profile["c_playlists"]) { ?>
        <a href="<?= $handle ?>/playlists"<?php if($active == "playlists") echo "class=\"active\""; ?>>Playlists</a>
    <?php } ?>
</div>
<?php } ?>
