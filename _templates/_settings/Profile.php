<? if ($Channel_Version == 1) : ?>
<style>
	.p_text_area {
		border: 1px solid #d5d5d5;
		padding: 3px 4px;
		border-radius: 3px;
		outline: 0;
		resize: none;
		overflow: hidden;
	}
	.p_text_area:hover {
		border: 1px solid #ababab;
	}

	.p_text_area:focus {
		border: 1px solid #9d9efd;
	}
</style>
<h4>Profile Setup</h4>
<form action="/my_profile" method="POST">
	<div style="margin:15px 0 21px">
		<label for="st_in" style="display:block;font-size:13px;position:relative;bottom:1px">About You:</label>
		<textarea class="p_text_area" name="about" maxlength="500" style="width:300px;height:79px"><?= $Info["about"] ?></textarea><br><br>
		<label for="st_we" style="display:block;font-size:13px;position:relative;bottom:1px">Website:</label>
		<input type="text" id="st_we" name="website" value="<?= htmlspecialchars($Info["website"]) ?>" maxlength="128" size="36"><br>
	</div>
	<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
		<img src="/img/clp00.png">
		<span class="u_sct_hd">Personal Details</span>
	</div>
	<div style="display:none;position:relative;left:6.5px">
		<label style="display:block;font-size:13px;position:relative;bottom:1px">Birthday:</label>
		<select name="month">
			<? foreach($Months_Array as $item => $value) : ?>
				<option value="<?= $value ?>"<? if ($value == $Birth_Month) : ?> selected<? endif ?>><?= $item ?></option>
			<?php endforeach ?>
		</select>
		<select name="day">
			<? for ($x = 1; $x <= 31; $x++) : ?>
				<option value="<?= $x ?>"<? if ($x == $Birth_Day) : ?> selected<? endif ?>><?= $x ?></option>
			<? endfor ?>
		</select>
		<select name="year">
			<? for($x = date("Y");$x >= 1910;$x--) : ?>
				<option value="<?= $x ?>"<? if ($x == $Birth_Year) : ?> selected<? endif ?>><?= $x ?></option>
			<? endfor ?>
		</select><br><br>
		<label for="st_in" style="display:block;font-size:13px;position:relative;bottom:1px">Interests:</label>
		<input type="text" id="st_in" name="interests" value="<?= htmlspecialchars($Info["i_interests"]) ?>" maxlength="128" size="36"><br><br>
		<label for="st_mu" style="display:block;font-size:13px;position:relative;bottom:1px">Movies:</label>
		<input type="text" id="st_mu" name="movies" value="<?= htmlspecialchars($Info["i_movies"]) ?>" maxlength="128" size="36"><br><br>
		<label for="st_mzb" style="display:block;font-size:13px;position:relative;bottom:1px">Music:</label>
		<input type="text" id="st_mzb" name="music" value="<?= htmlspecialchars($Info["i_music"]) ?>" maxlength="128" size="36"><br><br>
		<label for="boo" style="display:block;font-size:13px;position:relative;bottom:1px">Books:</label>
		<input type="text" id="boo" name="books" value="<?= htmlspecialchars($Info["i_books"]) ?>" maxlength="128" size="36">
	</div>
	<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
		<img src="/img/clp00.png">
		<span class="u_sct_hd">Education / Career</span>
	</div>
	<div style="display:none;position:relative;left:6.5px">
		<label for="st_sh" style="display:block;font-size:13px;position:relative;bottom:1px">Schools:</label>
		<input type="text" id="st_sh" name="schools" value="<?= htmlspecialchars($Info["i_schools"]) ?>" maxlength="128" size="36"><br><br>
		<label for="st_bo" style="display:block;font-size:13px;position:relative;bottom:1px">Occupation:</label>
		<input type="text" id="st_bo" name="occupation" value="<?= htmlspecialchars($Info["i_occupation"]) ?>" maxlength="128" size="36">
	</div>
	<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
		<img src="/img/clp00.png">
		<span class="u_sct_hd">Visibility Options</span>
	</div>
	<div style="display:none;position:relative;left:6.5px">
		<div><label><input type="checkbox" name="show_age" <?= $Info["a_age"] ? 'checked=""' : "" ?>/> Show age</label></div>
		<div><label><input type="checkbox" name="show_country" <?= $Info["a_country"] ? 'checked=""' : "" ?>/> Show country</label></div>
		<div><label><input type="checkbox" name="show_signin" <?= $Info["a_last"] ? 'checked=""' : "" ?>/> Show last sign in</label></div>
	</div>
	<div style="margin-top:25px">
		<input class="search_button" type="submit" name="update_info" value="Save Changes">
	</div>
</form>
<? endif ?>