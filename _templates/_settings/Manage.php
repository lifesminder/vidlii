<?php
	if ($_USER->Info["strikes"] == 2) {
		$standing_color = "red";
		$standing_status = "bad";
	} elseif ($_USER->Info["strikes"] == 1) {
		$standing_color = "orange";
		$standing_status = "dubious";
	} else {
		$standing_color = "green";
		$standing_status = "good";
	}
?>
<div style="margin-bottom:22px;overflow: hidden">
    <div style="width:31%;border-right:1px solid #ccc;padding-right:1.5%;margin-right:1.5%;float:left;">
        <div style="text-align:center;font-weight:bold;margin:0 0 9px 0">Change Email</div>
        <form action="/manage_account" method="POST" style="text-align:center">
            <input type="email" name="email" required maxlength="128" spellcheck="false" style="width:195px" placeholder="Your Email..." value="<?= $_USER->Info["email"] ?>">
            <input type="password" name="password" required maxlength="128" style="margin:15px 0" placeholder="Your Password...">
            <input type="hidden" name="cst" value="<?= $_SESSION["token"] ?>">
			<input type="submit" name="save_email" class="search_button" style="width:172px" value="Save Email">
        </form>
    </div>
    <div style="width:31%;border-right:1px solid #ccc;padding-right:1.5%;margin-right:1.5%;float:left;">
        <div style="text-align:center;font-weight:bold;margin:0 0 9px 0">Change Password</div>
        <form action="/manage_account" method="POST" style="text-align:center">
            <input type="password" name="current_password" required maxlength="128" style="width:180px;margin-bottom:15px" placeholder="Current Password...">
            <input type="password" name="new_password" required maxlength="128" style="width:180px;margin-bottom:15px" placeholder="New Password...">
            <input type="password" name="new_password2" required maxlength="128" style="width:180px;margin-bottom:15px" placeholder="Repeat New Password...">
            <input type="hidden" name="cst" value="<?= $_SESSION["token"] ?>">
			<input type="submit" name="save_password" class="search_button" style="width:172px" value="Save Password">
        </form>
    </div>
    <div style="width:31%;float:left;">
        <div style="text-align:center;font-weight:bold;margin:0 0 9px 0">Change Username</div>
		<? if ($uchange === true) : ?>
        <form id="change_username" action="/manage_account" method="POST" style="text-align:center" autocomplete="off">
            <input type="text" name="current_username" required maxlength="20" readonly style="width:180px;margin-bottom:15px" value="<?= $_USER->displayname ?>" placeholder="Current Username...">
            <input type="text" name="new_username" required maxlength="20" style="width:180px;margin-bottom:15px" placeholder="New Username...">
            <input type="submit" name="save_username" class="search_button" style="width:172px" value="Change Username" disabled>
			<div><i style="font-size:9px; color: #666;">* You won't be able to perform this action again for the six months that will follow this change.</i></div>
        </form>
		<? else : ?>
		<div style="font-weight:bold; color: #f00; text-align: center;"><?= $uchange ?></div>
		<? endif ?>
    </div>
	<div style="clear:both;"></div>
</div>
<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-bottom:13px">
    <img src="/img/clp00.png">
    <span class="u_sct_hd">Account Status: Community Guidelines</span>
</div>
<div style="display:none;position:relative;left:6.5px;margin-bottom:20px">
    Your account is in <span style="color:<?=$standing_color?>;font-weight:bold"><?=$standing_status?></span> standing.
</div>
<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-bottom:13px">
    <img src="/img/clp00.png">
    <span class="u_sct_hd">Account Status: Copyright</span>
</div>
<div style="display:none;position:relative;left:6.5px;margin-bottom:20px">
    Your account is in <span style="color:green;font-weight:bold">good</span> standing.
</div>
<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px">
    <img src="/img/clp00.png">
    <span class="u_sct_hd">Delete Account</span>
</div>
<div style="display:none;position:relative;left:6.5px">
    Delete your account: <a href="/delete_account"><button class="search_button">Delete!</button></a>
</div>

<script>
	$("#change_username").on("submit", function() {
		var conf = confirm("Warning: you won't be able to change your username again for the following 6 months. Proceed?");
		if (!conf) {
			$("input[name='new_username']").val("");
		}
		
		return conf;
	});
	
	$("input[name='new_username']").on("keyup keydown keypress change", function() {
		var len = ($(this).val()).length;
		var bool = !(len > 0 && len <= 20);
		$("input[name='save_username']").prop("disabled", bool);
	});
</script>