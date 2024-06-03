<div class="pr_l">
	<section>
		<div style="display:none" id="ch_user"><?= $Profile["username"] ?></div>
		<div style="display:none" id="ch_displayname"><?= $Profile["displayname"] ?></div>
		<div class="prbx_hd hl_hd">
			<div><? if (!empty($Profile["channel_title"])) : ?><?= $Profile["channel_title"] ?><? else : ?><?= $Profile["displayname"] ?> Channel<? endif ?></div>
			<? if (!$Is_Blocked && !$Has_Blocked) : ?>
			<div><div class="valign"><? if (!$Is_OWNER) : ?><?= subscribe_button2($Profile["username"]) ?><? else : ?><a href="/my_account" class="yel_btn">Edit Channel</a><? endif ?></div></div>
			<? else : ?>
				<div><div class="valign"><?= subscribe_button2($Profile["username"],true) ?></div></div>
			<? endif ?>
		</div>
		<div class="prbx_in hl_in">
			<div class="hl_in_top">
				<div>
				<div>
					<div>
						<?= user_avatar2($Profile["displayname"],96,96,$Profile["avatar"]) ?>
					</div>
				</div>
					<? if (!empty($Channel_Type)) : ?>
					<div class="badge">
						<?= $Channel_Type ?>
					</div>
					<? endif ?>
				</div>
				<div>
					<strong><?= $Profile["displayname"] ?></strong>
					<span>Joined: <strong><?= date("M d, Y",strtotime($Profile["reg_date"])) ?></strong></span>
					<? if ($Profile["a_last"]) : ?><span>Last Sign In: <strong><?= get_time_ago($Profile["last_login"]) ?></strong></span><? endif ?>
					<span>Subscribers: <strong><?= number_format($Profile["subscribers"]) ?></strong></span>
					<? if ($Profile["videos"] > 0) : ?><span>Video Views: <strong><?= number_format($Profile["video_views"]) ?></strong></span><? endif ?>
					<span>Channel Views: <strong><?= number_format($Profile["channel_views"]) ?></strong></span>
				</div>
			</div>
			<div class="cl"></div>
			<? if ($Profile["a_age"] != 0) : ?><div class="hl_st">Age: <strong><?= get_age($Profile["birthday"]) ?></strong></div><? else : ?><div style="height:4px"></div><? endif ?>
			<? if (!empty($Profile["website"])) : ?><div class="hl_st">Website: <strong><a href="<?= htmlspecialchars($Profile["website"]) ?>"><?= htmlspecialchars($Profile["website"]) ?></a></strong></div><? endif ?>
			<? if (!empty($Profile["channel_description"])) : ?><div class="hl_d"><?= DoLinks(nl2br(htmlspecialchars($Profile["channel_description"]))) ?></div><? endif ?>
			<? if (!empty($Profile["i_occupation"])) : ?><div class="hl_st">Occupation: <strong><?= htmlspecialchars($Profile["i_occupation"]) ?></strong></div><? endif ?>
			<? if (!empty($Profile["i_schools"])) : ?><div class="hl_st">Schools: <strong><?= htmlspecialchars($Profile["i_schools"]) ?></strong></div><? endif ?>
			<? if (!empty($Profile["i_interests"])) : ?><div class="hl_st">Interests: <strong><?= htmlspecialchars($Profile["i_interests"]) ?></strong></div><? endif ?>
			<? if (!empty($Profile["i_movies"])) : ?><div class="hl_st">Movies: <strong><?= htmlspecialchars($Profile["i_movies"]) ?></strong></div><? endif ?>
			<? if (!empty($Profile["i_music"])) : ?><div class="hl_st">Music: <strong><?= htmlspecialchars($Profile["i_music"]) ?></strong></div><? endif ?>
			<? if (!empty($Profile["i_books"])) : ?><div class="hl_st">Books: <strong><?= htmlspecialchars($Profile["i_books"]) ?></strong></div><? endif ?>
			<? if (count($Awards) > 0) : ?>
			<div class="awards">
				<div>
					<img src="/img/awards.png" title="<?= $Profile["displayname"] ?>s Awards">
				</div>
				<div style="width:296px">
					<? $Count = 0; $Award_Amount = count($Awards); ?>
					<? foreach ($Awards as $Award_Name => $Ranking) : ?>
						<? if ($Count == 4 && $Award_Amount > 4) : ?><a href="javascript:void(0)" id="show_more_link" onclick="$('#show_more_awards').toggleClass('hddn');$(this).toggleClass('hddn');">(Show More)</a><div id="show_more_awards" class="hddn"><? endif ?>
						<? if ($Award_Name == "va") : ?><a href="/channels?c=8&o=mv&t=2">#<?= $Ranking ?> - Most Viewed (All Time)</a><? endif ?>
						<? if ($Award_Name == "vw") : ?><a href="/channels?c=8&o=mv&t=1">#<?= $Ranking ?> - Most Viewed (This Week)</a><? endif ?>
						<? if ($Award_Name == "vm") : ?><a href="/channels?c=8&o=mv&t=0">#<?= $Ranking ?> - Most Viewed (This Month)</a><? endif ?>
						<? if ($Award_Name == "vac" && $Profile["channel_type"] != 0) : ?><a href="/channels?c=<?= $Profile["channel_type"] ?>&o=mv&t=2">#<?= $Ranking ?> - Most Viewed (All Time) - <?= $Channel_Types[$Profile["channel_type"]] ?>s</a><? endif ?>
						<? if ($Award_Name == "vwc" && $Profile["channel_type"] != 0) : ?><a href="/channels?c=<?= $Profile["channel_type"] ?>&o=mv&t=1">#<?= $Ranking ?> - Most Viewed (This Week) - <?= $Channel_Types[$Profile["channel_type"]] ?>s</a><? endif ?>
						<? if ($Award_Name == "vmc" && $Profile["channel_type"] != 0) : ?><a href="/channels?c=<?= $Profile["channel_type"] ?>&o=mv&t=1">#<?= $Ranking ?> - Most Viewed (This Month) - <?= $Channel_Types[$Profile["channel_type"]] ?>s</a><? endif ?>
						<? if ($Award_Name == "sa") : ?><a href="/channels?c=8&o=ms&t=2">#<?= $Ranking ?> - Most Subscribed (All Time)</a><? endif ?>
						<? if ($Award_Name == "sw") : ?><a href="/channels?c=8&o=ms&t=1">#<?= $Ranking ?> - Most Subscribed (This Week)</a><? endif ?>
						<? if ($Award_Name == "sm") : ?><a href="/channels?c=8&o=ms&t=0">#<?= $Ranking ?> - Most Subscribed (This Month)</a><? endif ?>
						<? if ($Award_Name == "sac" && $Profile["channel_type"] != 0) : ?><a href="/channels?c=<?= $Profile["channel_type"] ?>&o=ms&t=2">#<?= $Ranking ?> - Most Subscribed (All Time) - <?= $Channel_Types[$Profile["channel_type"]] ?>s</a><? endif ?>
						<? if ($Award_Name == "swc" && $Profile["channel_type"] != 0) : ?><a href="/channels?c=<?= $Profile["channel_type"] ?>&o=ms&t=1">#<?= $Ranking ?> - Most Subscribed (This Week) - <?= $Channel_Types[$Profile["channel_type"]] ?>s</a><? endif ?>
						<? if ($Award_Name == "smc" && $Profile["channel_type"] != 0) : ?><a href="/channels?c=<?= $Profile["channel_type"] ?>&o=ms&t=1">#<?= $Ranking ?> - Most Subscribed (This Month) - <?= $Channel_Types[$Profile["channel_type"]] ?>s</a><? endif ?>
						<? $Count++ ?>
						<? if ($Count > 4 && $Count == $Award_Amount) : ?><a href="javascript:void(0)" onclick="$('#show_more_link').toggleClass('hddn');$('#show_more_awards').toggleClass('hddn')">(Show Less)</a></div><? endif ?>
					<? endforeach ?>
				</div>
			</div>
			<? endif ?>
		</div>
	</section>
	<section>
		<div class="prbx_hd nm_hd">
			<? if (empty($Profile["connect"])) : ?>Connect with <?= $Profile["displayname"] ?><? else : ?><?= $Profile["connect"] ?><? endif ?>
		</div>
		<div class="prbx_in nm_in">
			<table class="connect" width="100%">
				<tbody><tr>
					<td width="39%" align="right" valign="middle"><?= user_avatar2($Profile["displayname"],64,64,$Profile["avatar"],"pr_avt") ?></td>
					<td class="connectl">
						<a href="<? if (!$_USER->logged_in) : ?>javascript:void(0)<? else : ?>/inbox?page=send_message&to=<?= $Profile["displayname"] ?><? endif ?>" <? if (!$_USER->logged_in) : ?>onclick="alert('Please log in to message <?= $Profile["displayname"] ?>!')"<? else : ?> onmouseenter="$('#c_sm').attr('src','/img/mail1.png')" onmouseleave="$('#c_sm').attr('src','/img/mail0.png')"<? endif ?>><img class="c_l" id="c_sm" src="/img/mail0.png">Send Message</a><br>
						<a href="javascript:void(0)" <? if (!$_USER->logged_in) : ?>onclick="alert('Please log in to comment on <?= $Profile["displayname"] ?>s channel!')"<? else : ?> onmouseenter="$('#c_ac').attr('src','/img/comm1.png')" onclick="$('#comment_content').focus()" onmouseleave="$('#c_ac').attr('src','/img/comm0.png')"<? endif ?>><img class="c_l" id="c_ac" src="/img/comm0.png">Add Comment</a><br>
						<a href="javascript:void(0)" <? if (!$_USER->logged_in) : ?>onclick="alert('Please log in to share <?= $Profile["displayname"] ?>s channel!')"<? else : ?> onmouseenter="$('#c_sc').attr('src','/img/share1.png')" onmouseleave="$('#c_sc').attr('src','/img/share0.png')"<? endif ?>><img class="c_l" id="c_sc" src="/img/share0.png">Share Channel</a><br>
						<? if (!$_USER->logged_in) : ?>
							<a href="javascript:void(0)" onclick="alert('Please log in to block <?= $Profile["displayname"] ?>!')"><img class="c_l" id="c_bu" src="/img/block0.png">Block User</a><br>
						<? elseif (!$Is_Blocked && !$Has_Blocked && $_USER->username !== $Profile["username"]) : ?>
							<a href="javascript:void(0)" onclick="block_user('<?= $Profile["username"] ?>')" onmouseenter="$('#c_bu').attr('src','/img/block1.png')" onmouseleave="$('#c_bu').attr('src','/img/block0.png')"><img class="c_l" id="c_bu" src="/img/block0.png"><span id="bu">Block User</span></a><br>
						<? elseif ($Is_Blocked && $_USER->username !== $Profile["username"]) : ?>
							<a href="javascript:void(0)" onclick="alert('You have been blocked by <?= $Profile["displayname"] ?>!')"><img class="c_l" id="c_bu" src="/img/block0.png">You're Blocked</a><br>
						<? elseif ($Has_Blocked && $_USER->username !== $Profile["username"]) : ?>
							<a href="javascript:void(0)" onclick="block_user('<?= $Profile["username"] ?>')" onmouseenter="$('#c_bu').attr('src','/img/block1.png')" onmouseleave="$('#c_bu').attr('src','/img/block0.png')"><img class="c_l" id="c_bu" src="/img/block0.png"><span id="bu">Unblock User</span></a><br>
						<? else : ?>
							<a href="javascript:void(0)" onclick="alert('Why do you dislike yourself?')"><img class="c_l" id="c_bu" src="/img/block0.png">Block User</a><br>
						<? endif ?>
							<? if (!$_USER->logged_in) : ?>
								<a href="javascript:void(0)" onclick="alert('Please log in to add <?= $Profile["displayname"] ?> to friends!')"><img class="c_l" id="c_af" src="/img/friend0.png">Add as Friend</a>
							<? elseif ($Is_OWNER) : ?>
								<a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')" onclick="alert('You cannot add yourself as a friend!')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Add as Friend</span></a>
							<? elseif ($Is_Blocked == true || $Has_Blocked == true) : ?>
								<a href="javascript:void(0)" onclick="alert('You cannot interact with this user!')"><img class="c_l" id="c_af" src="/img/friend0.png">Add as Friend</a>
							<? elseif ($Is_Friends === false && $_USER->Is_Activated) : ?>
								<a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Add as Friend</span></a>
							<? elseif ($Is_Friends === 2 && $_USER->Is_Activated) : ?>
								<a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Cancel Invite</span></a>
							<? elseif ($Is_Friends === true && $_USER->Is_Activated) : ?>
								<a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Unfriend</span></a>
							<? elseif ($Is_Friends === 3 && $_USER->Is_Activated) : ?>
								<a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Accept Invite</span></a>
							<? elseif (!$_USER->Is_Activated) : ?>
								<a href="javascript:void(0)" onmouseenter="$('#c_af').attr('src','/img/friend1.png')" onmouseleave="$('#c_af').attr('src','/img/friend0.png')" onclick="alert('You must activate your account with the email we sent you to add <?= $Profile["displayname"] ?> as a friend!')"><img class="c_l" id="c_af" src="/img/friend0.png"><span id="aaf">Add as Friend</span></a>
							<? endif ?>
					</td>
				</tr>
				</tbody>
			</table>
			<div class="connect_lnk"><a href="/user/<?= $Profile["displayname"] ?>">/user/<?= $Profile["displayname"] ?></a></div>
		</div>
	</section>
    <? foreach (explode(",",$Profile["modules_vertical_l"]) as $Module_l) : ?>
        <? if ($Module_l == "cu") : ?>
            <? if ($Profile["partner"] == 1 && $Profile["c_custom"] && (!empty($Profile["custom"] || $Is_OWNER)) && ($Profile["custom_d"] == 0 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/l_custom.php" ?>
            <? endif ?>
        <? elseif ($Module_l == "re") : ?>
            <? if ($Profile["c_recent"] && ($Profile["recent_d"] == 0 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/l_recent.php" ?>
            <? endif ?>
        <? elseif ($Module_l == "ft") : ?>
            <? if ((!empty($Profile["featured_channels"]) || $Is_OWNER) && $Profile["c_featured_channels"] == 1 && ($Profile["featured_d"] == 1 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/l_featured.php" ?>
            <? endif ?>
        <? elseif ($Module_l == "s2") : ?>
            <? if ($Profile["subscribers"] > 0 and $Profile["c_subscriber"] && ($Profile["subscriber_d"] == 0 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/l_subscribers.php" ?>
            <? endif ?>
        <? elseif ($Module_l == "s1") : ?>
            <? if ($Profile["subscriptions"] > 0 and $Profile["c_subscription"] && ($Profile["subscription_d"] == 0 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/l_subscriptions.php" ?>
            <? endif ?>
        <? elseif ($Module_l == "fr") : ?>
            <? if ($Profile["friends"] > 0 and $Profile["c_friend"] && ($Profile["friends_d"] == 0 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/l_friends.php" ?>
            <? endif ?>
        <? elseif ($Module_l == "co") : ?>
            <? if ($Profile["c_comments"] && ($Profile["channel_d"] == 0 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/l_comments.php" ?>
            <? endif ?>
        <? endif ?>
    <? endforeach ?>
</div>
<div class="pr_r">
	<? if (isset($Featured_Video) && $Profile["c_featured"] && $Featured_Video["status"] == 2) : ?>
		<div style="width:640px; height:360px; margin-bottom:18px;">
			<?php
				$URL = $Featured_Video["url"];
				$FILENAME = $Featured_Video["file"];
				$ISHD = $Featured_Video["hd"] == 1 ? true : false;
				if (isset($Featured_Video["seconds"])) { $Length = $Featured_Video["seconds"]; } else { $Length = $Featured_Video["length"]; }
				$Status = $Featured_Video["status"];
				require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/player.php";
			?>
		</div>
		<div class="ft_video_info">
			<a href="/watch?v=<?= $Featured_Video["url"] ?>"><?= htmlspecialchars($Featured_Video["title"]) ?></a>
			From: <a href="/user/<?= $Featured_Video["displayname"] ?>"><?= $Featured_Video["displayname"] ?></a><br>
			Views: <?= number_format($Featured_Video["views"]) ?><br>
			Comments: <?= number_format($Featured_Video["comments"]) ?>
		</div>
	<? endif ?>
	<? if ($Profile["videos"] > 0 and $Profile["c_videos"]) : ?>
	<section>
		<div class="prbx_hd nm_hd">
			Videos (<a href="/user/<?= $Profile["displayname"] ?>/videos"><?= number_format($Profile["videos"]) ?></a>)
		</div>
		<div class="prbx_in nm_in prbx_video">
			<? $Count = 0 ?>
			<div class="vi_box">
				<? foreach ($Videos as $Video) : ?>
				<? $Count++ ?>
				<? if ($Count === 5) : ?></div><div class="vi_box"> <? endif ?>
				<div>
                    <div class="th">
                        <div class="th_t"><?= $Video["length"] ?></div>
                        <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="125" height="90"></a>
                    </div>
					<a href="/watch?v=<?= $Video["url"] ?>" class="ln2"><?= htmlspecialchars($Video["title"]) ?></a>
					<span><?= get_time_ago($Video["uploaded_on"]) ?></span><br>
					<?= number_format($Video["views"]) ?> views<br>
					<div class="st"><? show_ratings($Video,13,13) ?></div>
				</div>
				<? endforeach ?>
				<? if ($Count >= 5) : ?></div> <? endif ?>
		</div>
	</section>
	<? elseif ($Is_OWNER && $Profile["c_videos"]) : ?>
		<section>
			<div class="prbx_hd nm_hd">
				Videos
			</div>
			<div class="prbx_in nm_in" style="background-image: url('/img/demo2.png'); background-position: top right; background-repeat: no-repeat; padding:50px 269px 55px 15px">
				<div style="color: #<?= $Profile["h_in_fnt"] ?>; font-weight: bold; font-size: 19px;">
					You haven't uploaded any videos.
				</div>
				<div style="color: #<?= $Profile["h_in_fnt"] ?>;margin-left: 25px">
					On VidLii.com you can easily <strong><a href="/upload">upload</a></strong> videos and share<br> them with either the entire world or just friends and family.
				</div>
			</div>
		</section>
	<? endif ?>
	<? if ($Profile["favorites"] > 0 and $Profile["c_favorites"]) : ?>
	<section>
		<div class="prbx_hd nm_hd">
			Favorites (<a href="/user/<?= $Profile["displayname"] ?>/favorites"><?= number_format($Profile["favorites"]) ?></a>)
		</div>
		<div class="prbx_in nm_in prbx_video">
			<? $Count = 0 ?>
			<div class="vi_box">
				<? foreach ($Favorites as $Video) : ?>
				<? $Count++ ?>
				<? if ($Count === 5) : ?></div><div class="vi_box"> <? endif ?>
				<div>
                    <div class="th">
                        <div class="th_t"><?= $Video["length"] ?></div>
                        <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="125" height="90"></a>
                    </div>
					<a href="/watch?v=<?= $Video["url"] ?>" class="ln2"><?= htmlspecialchars($Video["title"]) ?></a>
					<span><?= get_time_ago($Video["uploaded_on"]) ?></span><br>
					<?= number_format($Video["views"]) ?> views<br>
					<div class="st"><? show_ratings($Video,13,13) ?></div>
				</div>
				<? endforeach ?>
				<? if ($Count >= 5) : ?></div> <? endif ?>
		</div>
	</section>
	<? elseif ($Is_OWNER && $Profile["c_favorites"]) : ?>
		<section>
			<div class="prbx_hd nm_hd">
				Favorites
			</div>
			<div class="prbx_in nm_in" style="background-image: url('/img/demo1.png'); background-position: top right; background-repeat: no-repeat; padding:50px 269px 55px 15px">
				<div style="color: #<?= $Profile["h_in_fnt"] ?>; font-weight: bold; font-size: 19px;">
					You haven't chosen any favorites.
				</div>
				<div style="color: #<?= $Profile["h_in_fnt"] ?>;margin-left: 25px">
					You can easily add any video you have watched to your favorites by clicking the "Favorite" link under the video.
				</div>
			</div>
		</section>
	<? endif ?>
	<? if (($Is_OWNER && $Profile["c_playlists"] == 1) || (!empty($Profile["playlists"]) && $Profile["c_playlists"] == 1)) : ?>
		<section>
			<div class="prbx_hd nm_hd">
				Playlists
				<? if ($Is_OWNER) : ?>
					<div style="float:right">
						<a href="javascript:void(0)" onclick="$('#edit_pl').toggleClass('hddn')">Add Playlist</a>
					</div>
				<? endif ?>
			</div>
			<? if (!empty($Profile["playlists"])) : ?>
				<div class="prbx_in nm_in" id="pl_box"<? if ($Playlists_Amount > 0) : ?> style="padding-bottom: 1px" <? endif ?>>
					<div id="edit_pl" class="hddn" style="text-align:center;margin: 8px 0 11px">
						<select id="pl_sel">
							<? if (count($All_Playlists) > 0) : ?>
							<? foreach ($All_Playlists as $Playlist) : ?>
								<option value="<?= $Playlist["purl"] ?>"><?= $Playlist["title"] ?></option>
							<? endforeach ?>
							<? else : ?>
								<option>No Playlists...</option>
							<? endif ?>
						</select>
						<? if (count($All_Playlists) > 0) : ?><button class="search_button" onclick="add_playlist()">Add</button><? endif ?>
					</div>
					<? foreach ($Playlists as $Playlist) : ?>
						<? if (!file_exists("usfi/thmp/".$Playlist["thumbnail"].".jpg")) { $Playlist["thumbnail"] = "/img/no_th.jpg"; } else { $Playlist["thumbnail"] = "/usfi/thmp/".$Playlist["thumbnail"].".jpg"; } ?>
						<div class="pl_row" id="pl_<?= $Playlist["purl"] ?>">
							<div class="playlist">
								<a href="/playlist?p=<?= $Playlist["purl"] ?>"><img src="<?= $Playlist["thumbnail"] ?>"></a>
							</div>
							<div class="pl_info">
								<a href="/playlist?p=<?= $Playlist["purl"] ?>"><?= $Playlist["title"] ?></a>
								<em>No Description...</em>
							</div>
							<div><a href="javascript:void(0)">Play All</a><br><a href="javascript:void(0)" onclick="copyToClipboard('#pls_<?= $Playlist["purl"] ?>');alert('Link copied to your clipboard!')">Share</a><? if ($Is_OWNER) : ?><br><a href="javascript:void(0)" onclick="remove_pl('<?= $Playlist["purl"] ?>')">Remove</a><? endif ?>
							<div id="pls_<?= $Playlist["purl"] ?>" style="display:none">/playlist?p=<?= $Playlist["purl"] ?></div>
							</div>
						</div>
					<? endforeach ?>
				</div>
			<? else : ?>
				<div class="prbx_in nm_in" id="pl_box">
					<div id="edit_pl" class="hddn" style="text-align:center;margin-bottom:11pxtext-align:center;margin: 8px 0 11px">
						<select id="pl_sel">
							<? if (count($All_Playlists) > 0) : ?>
							<? foreach ($All_Playlists as $Playlist) : ?>
								<option value="<?= $Playlist["purl"] ?>"><?= $Playlist["title"] ?></option>
							<? endforeach ?>
							<? else : ?>
								<option>No Playlists...</option>
							<? endif ?>
						</select>
						<? if (count($All_Playlists) > 0) : ?><button onclick="add_playlist()" class="search_button">Add</button><? endif ?>
					</div>
					<div id="pl_text" style="text-align: center;font-size: 14px">You have not added any playlists yet!</div>
				</div>
			<? endif ?>
		</section>
	<? endif ?>
    <? foreach (explode(",",$Profile["modules_vertical_r"]) as $Module_r) : ?>
        <? if ($Module_r == "cu") : ?>
            <? if ($Profile["partner"] == 1 && $Profile["c_custom"] && (!empty($Profile["custom"] || $Is_OWNER)) && ($Profile["custom_d"] == 1 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/r_custom.php" ?>
            <? endif ?>
        <? elseif ($Module_r == "re") : ?>
            <? if ($Profile["c_recent"] && ($Profile["recent_d"] == 1 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/r_recent.php" ?>
            <? endif ?>
        <? elseif ($Module_r == "ft") : ?>
            <? if ((!empty($Profile["featured_channels"]) || $Is_OWNER) && $Profile["c_featured_channels"] == 1 && ($Profile["featured_d"] == 0 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/r_featured.php" ?>
            <? endif ?>
        <? elseif ($Module_r == "s2") : ?>
            <? if ($Profile["subscribers"] > 0 and $Profile["c_subscriber"] && ($Profile["subscriber_d"] == 1 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/r_subscribers.php" ?>
            <? endif ?>
        <? elseif ($Module_r == "s1") : ?>
            <? if ($Profile["subscriptions"] > 0 and $Profile["c_subscription"] && ($Profile["subscription_d"] == 1 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/r_subscriptions.php" ?>
            <? endif ?>
        <? elseif ($Module_r == "fr") : ?>
            <? if ($Profile["friends"] > 0 and $Profile["c_friend"] && ($Profile["friends_d"] == 1 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/r_friends.php" ?>
            <? endif ?>
        <? elseif ($Module_r == "co") : ?>
            <? if ($Profile["c_comments"] && ($Profile["channel_d"] == 1 || $Is_OWNER)) : ?>
                <? require_once "_templates/_profile/_widgets/r_comments.php" ?>
            <? endif ?>
        <? endif ?>
    <? endforeach ?>
</div>