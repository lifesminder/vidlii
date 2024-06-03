<h1 class="pg_hd">Sign Up</h1>
<aside class="re_l">
    <div style="font-size:16px;font-weight:bold">Sign up now, and use your VidLii account to:</div>
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
<section class="re_r">
    <div class="you_wnt" id="re_box">
        <script src="/js/pw_check2.js"></script>
        <div>
            <form action="/register" method="POST" name="userform" onsubmit="return submit_button(document.forms.userform.password.value)">
            <h3>Join VidLii</h3>
            <div style="font-size:12px;margin:1px 0 7px">It's free and easy. Just fill in the account info below.</div>

            <? if ($_PAGE->has_errors()) : ?>
            <div style="color:red;margin:0 0 6px;text-align:center"><?= $_PAGE->return_errors()[0] ?></div>
            <? endif ?>

            <table cellpadding="6" style="width:85%;margin:0 auto">
                <tr>
                    <td align="right"><label for="re_email">Email:</label></td>
                    <td><input type="email" name="email" id="re_email" style="width: 205px;" maxlength="128"<? if (isset($Validation["email"])) : ?>value="<?= $Validation["email"] ?>"<? endif ?> spellcheck="false" autofocus required></td>
                </tr>
                <tr>
                    <td align="right" valign="top"><label for="re_username" style="position:relative;top:3px">Username:</label></td>
                    <td>
                        <input type="text" maxlength="20" name="vl_usernames" id="re_username" autocomplete="off" <? if (isset($Validation["vl_username"])) : ?>value="<?= $Validation["vl_username"] ?>"<? endif ?> required pattern="[a-zA-Z0-9 ]+" spellcheck="false" onblur="if(this.value !== '') { user_exists(this.value); }">
                        <div id="user_exists" style="display:none;color:red;font-size:12px;position:relative;top:1px;left:1px"></div>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="top"><label for="pw1" style="position:relative; top:3px">Password:</label></td>
                    <style>
                        #pwd_bar {  border-radius: 3px; }
                    </style>
                    <td>
                        <input type="password" maxlength="128" name="password" id="pw1" onkeyup="if (this.value.length > 0) { check_password(document.forms.userform.password.value) }" required><br>
                        <div id="pwd_bar" style="position: relative; top:7px"></div>
                        <span id="pwd_meter" style="position: relative; top:7px;font-size:12.5px"></span>
                    </td>
                </tr>
                <tr>
                    <td align="right"><label for="pw2">Repeat Password:</label></td>
                    <td><input type="password" maxlength="128" name="password2" required id="pw2"></td>
                </tr>
                <tr>
                    <td align="right"><label>Country:</label></td>
                    <td>
                        <select name="country" style="width:214px">
                            <? foreach ($Countries as $Country => $Name) : ?>
                                <option value="<?= $Country ?>"<? if (isset($_POST["country"]) && $Country == $_POST["country"]) : ?>selected<? endif ?>><?= $Name ?></option>
                            <? endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right"><label>Birthday:</label></td>
                    <td>
                        <select name="month">
                            <? foreach($Months_Array as $item => $value) : ?>
                                <option value="<?= $value ?>"<? if (!empty($_POST) and $value == $_POST["month"]) : ?> selected<? endif ?>><?= $item ?></option>
                            <?php endforeach ?>
                        </select>
                        <select name="day">
                            <? for ($x = 1; $x <= 31; $x++) : ?>
                                <option value="<?= $x ?>"<? if (!empty($_POST) and $x == $_POST["day"]) : ?> selected<? endif ?>><?= $x ?></option>
                            <? endfor ?>
                        </select>
                        <select name="year">
                            <? for($x = date("Y");$x >= 1910;$x--) : ?>
                                <option value="<?= $x ?>"<? if (!empty($_POST) and $x == $_POST["year"]) : ?> selected<? endif ?>><?= $x ?></option>
                            <? endfor ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <label style="position: relative; right: 2.5px;"><input type="checkbox" name="age" <? if (isset($_POST["age"])) : ?>checked<? endif ?> required> <span style="position:relative;bottom:1.5px">I certify I am over 13 years old!</span></label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center" style="padding-bottom:65px">
                    	<script src="https://sys.kolyma.org/kaptcha/kaptcha.js"></script>
                    	<noscript>
                    		<input type="hidden" name="_KAPTCHA">
							<input type="hidden" name="_KAPTCHA_NOJS">
							<iframe src="https://sys.kolyma.org/kaptcha/kaptcha.php?nojs" style="border:none;width:400px;height:150px"></iframe><br>
							<input type="text" name="_KAPTCHA_KEY" placeholder="Paste here"><br>
                    	</noscript>
                    	<!-- div class="g-recaptcha" data-sitekey="6LfTZiQUAAAAADx03HPfPAGYdGP1NuM8LNd9lvUG" style="transform:scale(0.75,0.75);position:absolute;right:25px;bottom:37px"></div -->
                    </td>
                </tr>
            </table>
            <div style="text-align: center;margin:8px 0 0 0"><input name="submit_register" class="search_button" onclick="if(document.getElementById('pw1').value !== document.getElementById('pw2').value) { alert('The passwords do not match!'); document.getElementById('pw2').value = ''; document.getElementById('pw1').value = ''; $('#pw1').focus(); check_password(document.forms.userform.password.value); return false; }" id="reg_submit" type="submit" value="Sign Up!"></div>
            </form>
        </div>
    </div>
</section>
