<form method="post" action="" autocomplete="off">
	<? if ($phase < 2) : ?>
		<h4>WARNING!</h4>
		By deleting your account, you will not be able to log in again, you will not be able to use the same e-mail address twice, all of your videos will be permanently deleted, and you will take full responsibility over the implications of voluntarily terminating your account. <strong>This action cannot be undone! You will lose your videos and your account forever!</strong><br>
		<br>
		From this point on, you will be required to enter your password, and re-enter your password. Before you proceed, please take some time to reflect on your decision and make yourself sure you are entirely settled on the voluntary termination of your account.<br>
		<br>
		We are sad to see you go, but at the same time, we want to make sure that you don't come back and blame us for your decision, and your decision alone.
		<? if ($phase == 0) : ?>
			If you are one hundred percent sure of what you're about to do, click the button below, it should be clickable about some time after you have finished reading this message:<br>
			<br>
			<button name="delete_agree" disabled>I have read the statement above and take full responsibility over my account's termination!</button>
			<script>
				setTimeout(function() {
					$("button[name='delete_agree']").prop("disabled", false);
				}, 50000);
			</script>
		<? else : ?>
			If you are one hundred percent sure of what you're about to do, fill out the information below:<br>
			<br>
			<h4>Enter your password:</h4>
			<input type="password" name="vl_password1" /><br>
			<br>
			<h4>Re-enter your password:</h4>
			<input type="password" name="vl_password2" /><br>
			<br>
			<button name="delete_submit">Delete my account!</button>
		<? endif ?>
	<? elseif ($phase == 2) : ?>
		<h4>Deletion Confirmation E-mail Sent!</h4>
		You should receive an e-mail at your address' inbox, containing a link with the confirmation code that will allow you to terminate your account forever. Please, check the inbox of the following e-mail address: <strong><?= $Info["email"] ?></strong>. If you can't find the confirmation code under normal circumstances, please check your address' SPAM folder.<br>
		<br>
		Just a reminder before you go: if you click that e-mail, <strong>your account and your files are gone</strong>. Are you sure you want to proceed with this? If there is a time to reflect on your decision, now is it. We don't have a reactivation service, so when we say your data is gone, we mean it.<br>
		<br>
		We hope you have enjoyed VidLii while it lasted and that you'll still consider staying.<br>
		If not, then we thank you for your support and wish you good-bye.<br>
		<br>
		The link in your e-mail is only valid for the following 24 hours.
	<? elseif ($phase == 3) : ?>
		<h4>Account Termination</h4>
		We won't ask you again, this is your final chance to reflect and decide against your account's termination. If you are completely sure, we ask you to input your password one final time in the fields below to terminate your account once and for all. But before that, we'd like to remind you again: once you do it, <strong>you won't have it back!</strong><br>
		<br>
		<h4>Enter your password:</h4>
		<input type="password" name="vl_password1" /><br>
		<br>
		<h4>Re-enter your password:</h4>
		<input type="password" name="vl_password2" /><br>
		<br>
		<input type="hidden" name="vl_code" value="<?=$Secret_Code?>" />
		<button name="terminate_submit">Terminate my account!</button><br>
	<? endif ?>
</form>
