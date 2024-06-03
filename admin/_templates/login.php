<div style="width:60%;text-align:center;padding: 100px 0;float:left">
    <h1>VidLii Admin Panel</h1>
    This page is being heavily monitored, so don't do anything bad.
</div>
<div style="width:40%;float:left;text-align: center;padding: 20px 0">
    <form action="/admin/login" method="POST">
        Your Username:<br>
        <input type="text" name="user_name" readonly value="<?= $_USER->username ?>"><br><br>
        Your Password:<br>
        <input type="password" name="user_password" maxlength="128" style="width: 230px"><br><br>
        Admin Panel Password:<br>
        <input type="password" name="panel_password" maxlength="128" style="width: 230px"><br>
        <div>
			<script src="https://sys.kolyma.org/kaptcha/kaptcha.js"></script>
			<noscript>
				<input type="hidden" name="_KAPTCHA">
				<input type="hidden" name="_KAPTCHA_NOJS">
				<iframe src="https://sys.kolyma.org/kaptcha/kaptcha.php?nojs" style="border:none;width:400px;height:150px"></iframe><br>
				<input type="text" name="_KAPTCHA_KEY" placeholder="Paste here"><br>
			</noscript>
		</div>
        <!-- div class="g-recaptcha" data-sitekey="6LfTZiQUAAAAADx03HPfPAGYdGP1NuM8LNd9lvUG" style="transform:scale(0.8,0.8);width:301px;margin: 0 auto"></div -->

        <input type="submit" name="submit_password" value="Submit Password">
    </form>
</div>
