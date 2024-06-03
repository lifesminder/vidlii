<div style="width:48%;margin-right:2%;float:left;">
	<div class="panel_box">
		<strong>VidLii Statistics</strong>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Total Users:
		</div>
		<div style="float:left">
			<?= number_format($Total_Users) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Channel 1.0:
		</div>
		<div style="float:left">
			<?= number_format($Total_Channel1) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Channel 2.0:
		</div>
		<div style="float:left">
			<?= number_format($Total_Channel2) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Channel 3.0:
		</div>
		<div style="float:left">
			<?= number_format($Total_Channel3) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Channel Views:
		</div>
		<div style="float:left">
			<?= number_format($Total_Channel_Views) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Channel Comments:
		</div>
		<div style="float:left">
			<?= number_format($Total_Channel_Comments) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Subscriptions:
		</div>
		<div style="float:left">
			<?= number_format($Total_Subscriptions) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:8px">
			Partners:
		</div>
		<div style="float:left">
			<?= number_format($Total_Partner) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Total Videos:
		</div>
		<div style="float:left">
			<?= number_format($Total_Videos) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Total Views:
		</div>
		<div style="float:left">
			<?= number_format($Total_Views) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Minutes Watched:
		</div>
		<div style="float:left">
			<?= number_format($Total_Watchtime) ?> <i style="font-size:10px">since august 2018</i>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Video Comments:
		</div>
		<div style="float:left">
			<?= number_format($Total_Video_Comments) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Total Favorites:
		</div>
		<div style="float:left">
			<?= number_format($Total_Favorites) ?>
		</div>
		<div style="clear:both"></div>
		<div style="width:150px;float:left;font-weight:bold;margin-bottom:3px">
			Total Playlists:
		</div>
		<div style="float:left">
			<?= number_format($Total_Playlists) ?>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="panel_box" style="max-height:300px;overflow-y:auto">
		<strong>Recent Bulletins</strong>
		<? foreach ($Bulletins as $Bulletin) : ?>
			<div style="font-weight:bold"><a href="/user/<?= $Bulletin["by_user"] ?>"><?= $Bulletin["displayname"] ?></a> wrote:</div>
			<div style="font-size:13px"><?= $Bulletin["content"] ?></div>
			<div style="margin-bottom:10px"><?= get_time_ago($Bulletin["date"]) ?></div>
		<? endforeach ?>
	</div>
	<div class="panel_box" style="max-height:300px;overflow-y:auto">
		<strong>Channel Comments</strong>
		<div style="padding-bottom:7px;margin-bottom:7px;border-bottom: 1px solid #cccccc;text-align:center">
			<form action="/admin/dashboard" method="POST">
				<input type="text" name="search_string"<? if (isset($_POST["search_string"])) : ?> value="<?= $_POST["search_string"] ?>" <? endif ?>maxlength="64" placeholder="Search" style="width:300px"> <input type="submit" class="search_button" name="search_submit" value="Submit">
			</form>
		</div>
		<? foreach ($Channel_Comments as $Channel_Comment) : ?>
			<div style="font-weight:bold"><a href="/user/<?= $Channel_Comment["by_user"] ?>"><?= $Channel_Comment["displayname"] ?></a> wrote on <a href="/user/<?= $Channel_Comment["on_channel"] ?>"><?= $Channel_Comment["on_channel"] ?>s</a> channel:</div>
			<div style="font-size:13px"><? if (isset($_POST["search_string"])) : ?><?= str_replace($_POST["search_string"],"<strong>".$_POST['search_string']."</strong>",$Channel_Comment["comment"]) ?><? else : ?><?= $Channel_Comment["comment"] ?><? endif ?></div>
			<div style="margin-bottom:10px"><?= get_time_ago($Channel_Comment["date"]) ?></div>
		<? endforeach ?>
	</div>
</div>
<div style="width:50%;float:left;">
	<div class="panel_box">
		<strong>Write Blog Post</strong>
		<form action="/admin/dashboard" method="POST">
			<input style="margin-bottom:5px;width:250px" type="text" name="blog_title" maxlength="256" required placeholder="Blog Post Title"><br>
			<textarea name="blog_post" maxlength="50000" placeholder="Blog Post" required style="width:450px;border-radius:5px;margin-bottom:4px;border:1px solid #d5d5d5" rows="8"></textarea><br>
			<input type="submit" value="Submit Blog Post" name="submit_blog">
		</form>
	</div>
	<div class="panel_box">
		<strong>Video Reports</strong>
		<div style="max-height:300px;overflow-y:auto">
			<div style="display:table; width:100%; text-align: left">
			<? foreach ($Flags as $Flag) : ?>
				<div style="display:table-row;" id="reportRow<?=$Flag["url"]?>">
					<div style="display:table-cell; vertical-align:top; padding: 4px 0; border-bottom:1px solid #ccc; padding-right: 5px;">
						<a href="/watch?v=<?=$Flag["url"]?>" title="<?=$Flag["title"]?>"><img src="/usfi/thmp/<?=$Flag["url"]?>.jpg" width="96" style="border:3px double"></a>
					</div>
					
					<? switch($Flag["reason"]) {
						case 1:
							$FlagReason = "Sexually Explicit Content";
							break;
						case 2:
							$FlagReason = "SPAM/Misleading Content";
							break;
						default:
							$FlagReason = "Reason Not Found";
					} ?>
					
					<div style="display:table-cell; vertical-align:top; padding: 4px 0; border-bottom:1px solid #ccc; word-wrap: break-word; font-size:10px; text-align:left; width:100%;">
						<div style="font-size:12px"><b><a href="/watch?v=<?=$Flag["url"]?>" title="<?=$Flag["title"]?>"><?=cut_string($Flag["title"],15)?></a></b></div>
						<div><b><?= get_time_ago($Flag["submit_on"]) ?></b></div>
						<div><b>Reported by:</b> <a href="/admin/users?u=<?= $Flag["displayname"] ?>"><?=$Flag["displayname"]?></a></div>
						<div><b>Reason:</b> <?=$FlagReason?></div>
					</div>
					
					<div style="display:table-cell; vertical-align:middle; padding: 4px 20px; border-bottom:1px solid #ccc; text-align:center">
						<div><input type="button" value="Dismiss Report" onclick="dismissReport('<?=$Flag["url"]?>')"></div>
						<div><input type="button" value="Strike Uploader" onclick="strikeVideo('<?=$Flag["uploaded_by"]?>','<?=$Flag["url"]?>')"></div>
						<div><input type="button" value="Strike Reporter" onclick="strikeUploader('<?=$Flag["displayname"]?>')"></div>
					</div>
				</div>
			<? endforeach ?>
			</div>
			
			<script>
				function dismissReport(url) {
					$("#reportRow"+url).css("opacity",0.5);
					$("#reportRow"+url).find("input").prop("disabled",true);

					var err = function(al) {
						$("#reportRow"+url).css("opacity","");
						$("#reportRow"+url).find("input").prop("disabled",false);
						if (al) alert("An error has occurred, please try again later!");
					};
					
					if (!confirm("Are you sure you want to dismiss this report?")) {
						return err();
					}
					
					$.ajax({
						url: "/admin/ajax/dismiss_report",
						type: "post",
						data: {url: url},
						success: function(r) {
							if (r == "1") {
								$("#reportRow"+url).animate({"opacity":0}, 500, function() {
									$("#reportRow"+url).remove();
								});
							} else {
								err(true);
								alert(r);
							}
						},
						error: function() {
							err(true);
						}
					});
				}
				
				function strikeVideo(uploader, url) {
					location.href="/admin/users?u="+uploader+"&strike="+url;
				}
				
				function strikeUploader(uploader) {
					location.href="/admin/users?u="+uploader+"&abuseReport=true";
				}
			</script>
		</div>
	</div>
	<div class="panel_box" style="max-height:300px;overflow-y:auto">
		<strong>Feature Suggestions</strong>
		<? foreach ($Suggestions as $Suggestion) : ?>
			<div id="suggestion<?=$Suggestion["id"]?>">
				<div style="float:right"><input type="button" value="X" onclick="removeSuggestion('<?=$Suggestion["id"]?>')"></div>
				<div style="font-weight:bold"><?= $Suggestion["title"] ?></div>
				<div style="font-size:13px"><?= $Suggestion["description"] ?></div>
				<a href="/user/<?= $Suggestion["from_user"] ?>" style="display: block;margin-bottom: 10px"><?= $Suggestion["displayname"] ?></a>
				<div style="clear:both"></div>
			</div>
		<? endforeach ?>
		
		<script>
			function removeSuggestion(id) {
				$("#suggestion"+id).find("input").prop("disabled", true);
				var err = function() {
					$("#suggestion"+id).find("input").prop("disabled", false);
					if (al) alert("An error has occurred, please try again later!");
				}
				
				$.ajax({
					url: "/admin/ajax/remove_suggestion",
					type: "post",
					data: {id:id},
					error: err,
					success: function(r) {
						if (r == 1) {
							$("#suggestion"+id).animate({opacity:0}, 500, function() {
								$("#suggestion"+id).remove();
							});
						} else {
							err();
						}
					}
				});
			}
		</script>
	</div>
	<div class="panel_box" style="max-height:300px;overflow-y:auto">
		<strong>Converting Videos</strong>
		<? if (count($Converting) > 0) : ?>
			<table style="width:100%">
				<tr>
					<td style="font-weight:bold">URL</td>
					<td style="font-weight:bold">Date</td>
					<td style="font-weight:bold">Status</td>
				</tr>
				<? foreach ($Converting as $Convert) : ?>
					<tr>
						<td><?= $Convert["url"] ?></td>
						<td><?= get_time_ago($Convert["uploaded_on"]) ?></td>
						<td><? if ($Convert["convert_status"] == 1) : ?>Converting<? else : ?>Waiting<? endif ?></td>
					</tr>
				<? endforeach ?>
			</table>

		<? else : ?>
		There are no videos converting right now.
		<? endif ?>
	</div>
</div>