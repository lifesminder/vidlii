<script>
	function user_search_box() {
		var search = $("#user_searcher").val();
		if (search.length > 1) {
			$("#search_container").css("display","block");
			$.ajax({
				type: "POST",
				url: "/ajax/user_dropdown",
				data: {search: search},
				success: function (output) {
					$("#user_search").html(output);
				}
			});
		} else {
			$("#user_search").html("");
			$("#search_container").css("display","none");
		}
	}
</script>
<? if (!isset($_GET["u"])) : ?>
<div style="width:48%;margin-right:2%;float:left;">
	<div class="panel_box">
		<strong>Edit Channel</strong>
		<form action="/admin/users" method="GET">
			<div style="width:320px;margin:0 auto">
				<div style="width:250px;float:left;margin-right:15px">
					<input autocomplete="off" aria-autocomplete="none" type="text" style="width:100%" name="u" placeholder="Enter username" id="user_searcher" onkeyup="user_search_box()">
					<div style="background: white;border:1px solid #ccc;border-top:0;width:103%;display:none" id="search_container">
						<ul id="user_search">

						</ul>
					</div>
				</div>
				<input type="submit" value="Edit" style="float:left" class="search_button">
			</div>
		</form>
	</div>
	<div class="panel_box" style="overflow-y:auto;max-height:390px">
		<strong>New Users</strong>
		<? foreach ($New_Users as $User) : ?>
			<div style="margin-bottom: 5px;overflow:hidden">
				<div style="float: left;">
					<?= user_avatar3($User["username"],77,77,$User["avatar"]) ?>
				</div>
				<div style="float:left;margin-left:6px;line-height:18px;position:relative;bottom:3px">
					<strong><a href="/user/<?= $User["username"] ?>"><?= $User["displayname"] ?></a></strong><br>
					<?= $User["email"] ?><br>
					Registered <?= get_time_ago($User["reg_date"]) ?><br>
					IP: <? if ($_USER->Is_Admin) : ?><?= $User["1st_latest_ip"] ?><? else : ?>Hidden<? endif ?><br>
					<strong><a href="/admin/users?u=<?= $User["username"] ?>">Edit User</a></strong>
				</div>
			</div>
		<? endforeach ?>
	</div>
</div>
<div style="width:50%;float:left;">
	<div class="panel_box" style="overflow-y:auto;max-height:450px">
		<strong>Partner Applications</strong>
		<? foreach ($Applications as $Application) : ?>
			<div class="u_sct">
				<img src="/img/clp00.png">
				<span class="u_sct_hd"<? if ($Application["partner"] == 1) : ?> style="color:green"<? endif ?>><?= $Application["displayname"] ?> (<? if ($_USER->Is_Admin) : ?><?= $Application["name"] ?><? else : ?>Hidden<? endif ?>)</span>
			</div>
			<div style="display:none;margin-bottom:15px">
				<strong>Age:</strong> <?= get_age($Application["birthday"]) ?><br>
				<strong>Country:</strong> <? if ($_USER->Is_Admin) : ?><?= $Countries[$Application["country"]] ?><? else : ?>Hidden<? endif ?><br>
				<strong>What I'm doing:</strong><br>
				<?= nl2br(htmlspecialchars($Application["what"])) ?><br>
				<strong>Why I want to be a Partner:</strong><br>
				<?= nl2br(htmlspecialchars($Application["why"])) ?><br>
				<? if ($Application["partner"] == 0) : ?><strong><a href="/admin/users?a=<?= $Application["username"] ?>">Accept Application</a></strong> | <strong><a href="javascript:void(0)" onclick="document.getElementById('deny_app_<?= $Application["displayname"] ?>').style.display = 'inline-block';(this).style.display = 'none'">Deny Application</a>
                <div id="deny_app_<?= $Application["displayname"] ?>" style="display:none">
                    <form action="/admin/users" method="GET" style="display:inline-block" onchange="if ((this).value != 0) { (this).submit(); }">
                        <input type="hidden" name="n" value="<?= $Application["username"] ?>">
                        <select name="d" style="padding: 1px;">
                            <option value="0">SELECT A REASON:</option>
                            <option value="1">Copyrighted Content</option>
                            <option value="2">Not Active Enough</option>
                            <option value="3">Too Young</option>
                            <option value="4">Low Quality Content</option>
                        </select>
                    </form>
                </div>
                </strong><? endif ?>
			</div>
		<? endforeach ?>
	</div>
</div>
<? else : ?>
	<div style="padding-bottom:11px;margin-bottom:11px;border-bottom:1px solid #e2e2e2;overflow:hidden">
		<div style="float:left">
			<?= user_avatar3($User_Info["username"],69,69,$User_Info["avatar"]) ?>
		</div>
		<div style="float:left;margin-left:8px;position:relative;bottom:2px">
			<strong><a href="/user/<?= $User_Info["username"] ?>"><?= $User_Info["username"] ?></a></strong>
			<div style="font-size:13px">
			Signed Up: <?= get_date($User_Info["reg_date"]) ?><br>
			Last Sign In: <?= get_time_ago($User_Info["last_login"]) ?><br>
			IP: <? if ($_USER->Is_Admin) : ?><?= $User_Info["1st_latest_ip"] ?><? else : ?>Hidden<? endif ?><br>
			Email: <?= $User_Info["email"] ?>
			</div>
		</div>
		<form action="/admin/users?u=<?= $User_Info["username"] ?>" method="POST">
			<div style="float:left;margin-left:65px">
				<input type="submit" name="delete_avatar" value="Delete Avatar" style="padding: 7px 22px;position:relative;margin-top:10px">
			</div>
			<div style="float:left;margin-left:50px">
				<input type="submit" name="delete_background" value="Delete Background" style="padding: 7px 22px;position:relative;margin-top:10px">
			</div>
			<div style="float:left;margin-left:50px">
				<input type="submit" name="delete_videos" value="Delete Videos" style="padding: 7px 22px;position:relative;margin-top:10px" onclick="return confirm('Are you sure you want to delete this user\'s videos?')">
			</div>
			<div style="float:left;margin-left:50px">
				<input type="button" onclick="$('#strike_form').toggle()" value="Ban / Strike <?= $User_Info["username"] ?>" style="padding: 7px 22px;position:relative;margin-top:10px">
			</div>
			<? if ($_USER->Is_Admin || $_USER->Is_Mod) : ?>
			<div style="float:left;margin-left:50px">
				<input type="submit" name="track_alts" value="Track Alts" style="padding: 7px 22px;position:relative;margin-top:10px">
			</div>
			<div style="float:left;margin-left:50px">
				<input type="submit" name="ban_all_alts" value="Ban Alts" onclick="return confirm('Are you sure you want to do this?')" style="padding: 7px 22px;position:relative;margin-top:10px">
			</div>
            <div style="float:left;margin-left:50px">
                <input type="submit" name="delete_comments" value="Delete Comments" onclick="return confirm('Are you sure you want to delete all of this user\'s video and channel comments?')" style="padding: 7px 22px;position:relative;margin-top:10px">
            </div>
            <div style="float:left;margin-left:50px">
                <input type="submit" name="delete_subs" value="Delete Subscribers" onclick="return confirm('Are you sure you want to delete all of this user\'s subscribers? Users that are subscribed to this user will need to re-subscribe!')" style="padding: 7px 22px;position:relative;margin-top:10px">
            </div>
			<? endif ?>
			<div style="clear:both"></div>
			<div style="display:none; margin-top:10px; text-align: center" id="strike_form">
				<div>Reason: <select name="strike_reason" style="width:400px; margin-bottom:5px;">
					<option value="none">---Don't Strike---</option>
					<? foreach($Ban_Reasons as $Reason) { echo "<option value=\"$Reason[id]\">$Reason[reason]</option>"; } ?>
				</select></div>
				<div style="margin-bottom:5px;"><textarea name="strike_note" placeholder="(Optional) Write a note to the user explaining the situation." style="width:480px;height:90px"></textarea></div>
				<div style="margin-bottom:5px;"><textarea name="strike_videos" placeholder="(Optional) List here the videos that violated the community guidelines." style="width:480px;height:90px"></textarea></div>
				<div>
					<input type="submit" name="strike_user" value="Strike <?= $User_Info["username"] ?>" style="padding: 7px 22px;">
					<input type="submit" name="ban_user" value="<? if ($User_Info["banned"] == 0) : ?>Ban<? else : ?>Unban<? endif ?> <?= $User_Info["username"] ?>" style="padding: 7px 22px;">
				</div>
			</div>
			<? if (isset($_POST["track_alts"])) : ?>
            <? if ($Alts) : ?>
			<div style="margin-top:10px; text-align: center">
				<table border="1" style="margin:0 auto">
					<thead>
						<th>Displayname:</th>
						<th>E-mail:</th>
						<th>1st Latest IP:</th>
						<th>2nd Latest IP:</th>
						<th>Banned:</th>
					</thead>
					
					<? foreach($Alts as $a) { ?>
					<tr>
						<td><a href="/admin/users?u=<?= $a["displayname"] ?>"><?= $a["displayname"] ?></a></td>
						<td><?= $a["email"] ?></td>
						<td><? if ($_USER->Is_Admin) : ?><?= $a["1st_latest_ip"] ?><? else : ?>Hidden<? endif ?></td>
						<td><? if ($_USER->Is_Admin) : ?><?= $a["2nd_latest_ip"] ?><? else : ?>Hidden<? endif ?></td>
						<td><?= $a["banned"] == 1 ? "Yes" : ($a["banned"] == 2 ? "Left" : "No") ?></td>
					</tr>
					<? } ?>
				</table>
			</div>
            <? else : ?>
            <div style="margin-top:10px;text-align:center">No Alts could be found</div>
            <? endif ?>
			<? endif ?>
		</form>
		
		<script>
			var pageUrl = location.href;
			if (pageUrl.indexOf("strike=") != -1) {
				var videoUrl = "/watch?v=" + pageUrl.substr(pageUrl.indexOf("strike=") + 7, 11);
				$('#strike_form').show();
				$("textarea[name='strike_videos']").focus().val(videoUrl);
				$("textarea[name='strike_note']").focus();
			}
			
			if (pageUrl.indexOf("abuseReport=true") != -1) {
				$('#strike_form').show();
				$("select[name='strike_reason']").val(14);
				$("textarea[name='strike_note']").focus();
			}
		</script>
	</div>
	<form action="/admin/users?u=<?= $User_Info["username"] ?>" method="POST">
	<div style="float:left;width:49%;padding-right:1%">
		<table cellpadding="4">
			<tr>
				<td>Website:</td>
				<td><input type="url" name="website" value="<?= htmlspecialchars($User_Info["website"]) ?>" maxlength="128"></td>
			</tr>
			<tr>
				<td valign="top">About:</td>
				<td><textarea name="about" rows="6" style="resize:vertical;width:255px;border-radius:4px;border:1px solid #d5d5d5"><?= $User_Info["about"] ?></textarea></td>
			</tr>
			<tr>
				<td>Channel Type:</td>
				<td>
					<select name="channel_type">
						<option value="0"<? if ($User_Info["channel_type"] == 0) : ?> selected<? endif ?>>Default</option>
						<option value="1"<? if ($User_Info["channel_type"] == 1) : ?> selected<? endif ?>>Director</option>
						<option value="2"<? if ($User_Info["channel_type"] == 2) : ?> selected<? endif ?>>Musician</option>
						<option value="3"<? if ($User_Info["channel_type"] == 3) : ?> selected<? endif ?>>Comedian</option>
						<option value="4"<? if ($User_Info["channel_type"] == 4) : ?> selected<? endif ?>>Gamer</option>
						<option value="5"<? if ($User_Info["channel_type"] == 5) : ?> selected<? endif ?>>Reporter</option>
						<option value="6"<? if ($User_Info["channel_type"] == 6) : ?> selected<? endif ?>>Guru</option>
						<option value="7" <? if ($User_Info["channel_type"] == 7) : ?> selected<? endif ?>>Animator</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Channel Title:</td>
				<td><input type="text" name="channel_title" value="<?= $User_Info["channel_title"] ?>" maxlength="80"></td>
			</tr>
			<tr>
				<td valign="top">Channel Description:</td>
				<td><textarea maxlength="2500" name="description" rows="6" style="resize:vertical;width:255px;border-radius:4px;border:1px solid #d5d5d5"><?= $User_Info["channel_description"] ?></textarea></td>
			</tr>
			<tr>
				<td>Channel Tags:</td>
				<td><input type="text" maxlength="270" name="tags" value="<?= $User_Info["channel_tags"] ?>" maxlength="128"></td>
			</tr>
			<tr>
				<td>Channel Version:</td>
				<td>
					<select name="channel_version">
						<option value="1"<? if ($User_Info["channel_version"] == 1) : ?> selected<? endif ?>>Channel 1.0</option>
						<option value="2"<? if ($User_Info["channel_version"] == 2) : ?> selected<? endif ?>>Channel 2.0</option>
                        <option value="3"<? if ($User_Info["channel_version"] == 3) : ?> selected<? endif ?>>Cosmic Panda</option>
                    </select>
				</td>
			</tr>
		</table>
	</div>
	<div style="float:left;width:48%;padding-left:1%;border-left:1px solid #ccc">
		<table cellpadding="4">
			<tr>
				<td><strong>Display Name:</strong></td>
				<td><input type="text" name="displayname" value="<?= $User_Info["displayname"] ?>" required maxlength="20"></td>
			</tr>
			<tr>
				<td>Name:</td>
				<td><input type="text" name="name" value="<?= $User_Info["i_name"] ?>" maxlength="64"></td>
			</tr>
			<tr>
				<td>Occupation:</td>
				<td><input type="text" name="occupation" value="<?= $User_Info["i_occupation"] ?>" maxlength="128"></td>
			</tr>
			<tr>
				<td>Schools:</td>
				<td><input type="text" name="schools" value="<?= $User_Info["i_schools"] ?>" maxlength="128"></td>
			</tr>
			<tr>
				<td>Interests:</td>
				<td><input type="text" name="interests" value="<?= $User_Info["i_interests"] ?>" maxlength="128"></td>
			</tr>
			<tr>
				<td>Movies:</td>
				<td><input type="text" name="movies" value="<?= $User_Info["i_movies"] ?>" maxlength="128"></td>
			</tr>
			<tr>
				<td>Music:</td>
				<td><input type="text" name="music" value="<?= $User_Info["i_music"] ?>" maxlength="128"></td>
			</tr>
			<tr>
				<td>Books:</td>
				<td><input type="text" name="books" value="<?= $User_Info["i_books"] ?>" maxlength="128"></td>
			</tr>
            <tr>
                <td>Country:</td>
                <td>
                    <select name="country" style="width:214px">
                        <? foreach ($Countries as $Country => $Name) : ?>
                            <option value="<?= $Country ?>"<? if ($Country == $User_Info["country"]) : ?>selected<? endif ?>><?= $Name ?></option>
                        <? endforeach ?>
                    </select>
                </td>
            </tr>
			<tr>
				<td>Birthday:</td>
				<td>
					<select name="month">
						<? foreach($Months as $item => $value) : ?>
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
					</select>
				</td>
			</tr>
			<tr>
				<td>Activated:</td>
				<td>
					<select name="activated">
						<option value="0"<? if ($User_Info["activated"] == 0) : ?> selected<? endif ?>>False</option>
						<option value="1"<? if ($User_Info["activated"] == 1) : ?> selected<? endif ?>>True</option>
					</select>
				</td>
			</tr>
            <? if ($User_Info["partner"] == 1) : ?>
                <tr>
                    <td>Partnered:</td>
                    <td>
                        <select name="partnered">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </td>
                </tr>
            <? endif ?>
			<tr>
				<td>Strikes:</td>
				<td><b style="<?=$User_Info["strikes"] == 1 ? "color:orange" : ($User_Info["strikes"] >= 2 ? "color:red" : "")?>"><?=$User_Info["strikes"]?></b></td>
			</tr>
		</table>
	</div>
	<div style="clear:both"></div>
	<div style="text-align:center;margin-top:17px">
		<input type="submit" value="Save User Changes" name="save_user" style="padding: 5px 20px">
	</div>
	</form>
<? endif ?>
