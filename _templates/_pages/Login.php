<h1 class="pg_hd">Sign In</h1>
<aside class="lg_l">
    <div style="font-size:16px;font-weight:bold">Sign in now, and use your VidLii account to:</div>
    <table style="margin: 17px 0 0 0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <div style="font-size: 16px;">Customize your experience on VidLii</div>
                <div style="color:#2f2f2f;padding-bottom:43px">VidLii allows you to personalize your channel and homepage to whatever you want it to look like!</div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-size: 16px;">Be part of the VidLii community</div>
                <div style="color:#2f2f2f;padding-bottom:43px"">Having a VidLii account allows you to interact and be part of the VidLii community in many unique and fun ways!</div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-size: 16px;">Share with your friends</div>
                <div style="color:#2f2f2f;padding-bottom:31px">See videos shared by your friends across all your social networks --- all in one place!</div>
            </td>
        </tr>
    </table>
</aside>
<section class="lg_r">
    <div class="you_wnt" id="login_box">
        <div>
            <form action="/login<? if (isset($_GET["activate"])) : ?>?activate=<?= $_GET["activate"] ?><? endif ?>" method="POST">
            <img src="/img/vidlii.png" height="37" width="92" alt="VidLii" title="VidLii - Display Yourself.">
            <? if ($_PAGE->has_errors()) : ?>
                <div style="color:red;text-align: center"><?= $_PAGE->return_errors()[0] ?></div>
            <? endif ?>
            <table cellpadding="3">
                <? if (mt_rand(0,1) == 1) : ?>
                <tr style="display:none"><input type="text" style="display:none"><input type="password" style="display:none"</tr>
                <? endif ?>
                <tr>
                    <input type="hidden" name="jf1" value="acnla">
                    <td align="right"><label for="lg_username">Username:</label></td>
                    <td align="left"><input type="text" name="v_username" id="lg_username" <? if (isset($Username)) : ?>value="<?= $Username ?>"<? endif ?> required <? if (!isset($Username)) : ?>autofocus<? endif ?>></td>
                </tr>
                <tr>
                    <input type="hidden" name="<?= mt_rand(0,1000) ?>" value="<?= mt_rand(0,1000) ?>">
                    <td align="right"><label for="<?= substr($_SESSION["secret_id"], 1, 2) ?>_password">Password:</label></td>
                    <td align="left"><input id="<?= $login_secret ?>_password" name="<?= $login_secret ?>_password" type="password" <? if (isset($Username)) : ?>autofocus<? endif ?> required></td>
                </tr>
                <? if (mt_rand(0,1) == 1) : ?>
                    <input type="hidden" name="<?= substr($_SESSION["secret_id"], 6, 4) ?>" value="<?= substr($_SESSION["secret_id"], 1, 5).substr(user_ip(), 0, 2) ?>">
                    <input type="hidden" name="gaa87" value="g16Ah4">
                    <input type="hidden" name="<?= substr($_SESSION["secret_id"], 8, 4) ?>" value="<?= substr($_SESSION["secret_id"], 3, 5).substr(user_ip(), 0, 2) ?>">
                <? else : ?>
                    <input type="hidden" name="<?= substr($_SESSION["secret_id"], 8, 4) ?>" value="<?= substr($_SESSION["secret_id"], 3, 5).substr(user_ip(), 0, 2) ?>">
                    <input type="hidden" name="gaa87" value="<?= mt_rand(0,1000) ?>">
                    <input type="hidden" name="<?= substr($_SESSION["secret_id"], 6, 4) ?>" value="<?= substr($_SESSION["secret_id"], 1, 5).substr(user_ip(), 0, 2) ?>">
                <? endif ?>
                <? if (mt_rand(0,1) == 1) : ?>
                <tr style="display:none"><input type="text" style="display:none"><input type="password" style="display:none"</tr>
                <? endif ?>
                <tr>
                    <td></td>
                    <td align="left"><input type="submit" class="search_button" style="padding:2.5px 10px" name="submit_login" value="Sign In"></td>
                </tr>
            </table>
            <? if($require_captcha): ?>
                <?= captcha(5,10,8, true) ?>
            <? endif; ?>
            <div class="log_fgt"><a href="javascript:void(0)" onclick="alert('You can also log in with your email instead of your username!')">Forgot Username</a> | <a href="/forgot_password">Forgot Password</a></div>
            </form>
        </div>
    </div>
    <div class="you_wnt">
        <div>
            <strong>No Account?</strong><br>
            <strong><a href="/register">Sign Up for a free VidLii Account!</a></strong>
        </div>
    </div>
</section>