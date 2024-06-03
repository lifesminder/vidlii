<h1 class="pg_hd">Forgot Password</h1>
<aside class="lg_l">
    <div style="color:#2f2f2f;padding-bottom:43px">Simply enter your username, solve the captcha, and we'll email instructions on how to reset your password.</div></aside>
<section class="lg_r">
    <div class="you_wnt" id="login_box">
        <div>
            <? if (!isset($_GET["code"])) : ?>
            <form action="/forgot_password" method="POST">
            <img src="/img/vidlii.png" height="37" width="92" alt="VidLii" title="VidLii - Display Yourself.">
            <table cellpadding="3">
                <tr>
                    <td align="right"><label for="lg_username">Username:</label></td>
                    <td align="left"><input type="text" name="vl_username" id="lg_username" maxlength="20" required pattern="[a-zA-Z0-9 ]+" autofocus></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?= captcha(0) ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left"><input type="submit" class="search_button" name="submit_forgot" value="Reset my Password!"></td>
                </tr>
            </table>
            </form>
            <? else : ?>
                <form action="/forgot_password?code=<?= $_GET["code"] ?>" method="POST">
                    <img src="/img/vidlii.png" height="37" width="92" alt="VidLii" title="VidLii - Display Yourself.">
                    <table cellpadding="3">
                        <tr>
                            <td align="right"><label for="lg_password">New Password:</label></td>
                            <td align="left"><input type="password" name="vl_password" style="width:170px" id="lg_password" maxlength="128" required autofocus></td>
                        </tr>
                        <tr>
                            <td align="right"><label for="lg_password2">Repeat:</label></td>
                            <td align="left"><input type="password" name="vl_password2" style="width:170px" id="lg_password2" maxlength="128" required></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td align="left"><input type="submit" class="search_button" name="submit_password" value="Change Password!"></td>
                        </tr>
                    </table>
                </form>
            <? endif ?>
        </div>
    </div>
</section>