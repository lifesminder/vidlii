<style>
    strong {
        margin-bottom: 3px;
        display:block
    }

    .e_thmp {
        -webkit-user-drag: none;
    }

    .e_thmp:hover {
        cursor: pointer;
        border: 3px double #4d4ccc !important;
    }

    #thmp_sel {
        border: 3px double #4d4ccc !important;
    }
	
	#uploadBar {
		position:absolute;
		left:0; top:0; right:0; bottom:0;
		padding:2px;
		box-sizing: border-box;
		background-clip: content-box;
		background-color: #96d3f7;
		width:0%;
	}
	
	#uploadBarText {
		text-align:left;
		font-size:10px;
		position:relative;
		height:30px;
	}
	
	#uploadBarStatus {
		display:block;
		position:absolute;
		top:0;
		left:0;
		right:50px;
	}
	
	#uploadBarStatus.error {
		color: #f00;
	}
	
	#uploadBarPercent {
		display:block;
		position:absolute;
		top:0;
		right:0;
	}
    input[type="text"], textarea {
        width: 388px;
        box-sizing: border-box;
    }
    textarea {
        border: 1px solid #d5d5d5;
        padding: 3px 4px;
        border-radius: 3px;
        outline: 0;
        resize: vertical;
        font-family: Arial
    }
    textarea:hover {
        border: 1px solid #ababab;
    }
    textarea:focus {
        border: 1px solid #9d9efd;
    }
</style>

<? if ($_USER->Is_Partner) : ?>
<script src="/js/vlUploader.js?3"></script>
<? endif ?>

<div style="background: #e2e2e2;padding: 12px">
</div>
<div style="border: 2px solid #e2e2e2; border-top: 0;padding: 10px;text-align:center">
    <form id="editVideoForm" action="/edit_video?v=<?= $Video->Info["url"] ?>" method="POST" enctype="multipart/form-data">
		<table style="width:100%">
			<tr>
				<td style="vertical-align:top; width:60%">
					<div style="margin-bottom: 14px;text-align:left"><strong>Title</strong><input type="text" name="title" maxlength="100" placeholder="Your Videos Title..." value="<?= htmlspecialchars($Video->Info["title"],ENT_QUOTES) ?>" size="40"></div>
					<div style="margin-bottom: 14px;text-align:left"><strong>Description</strong><textarea placeholder="Describe your video..." name="description" maxlength="1000" rows="10" cols="45"><?= $Video->Info["description"] ?></textarea></div>
                    <div style="margin-bottom: 14px;text-align:left"><strong>Tags</strong><textarea name="tags" placeholder="Relevant keywords of your video (seperated by commas)" maxlength="250" rows="3"><?= $Video->Info["tags"] ?></textarea></div>
					<div style="margin-bottom: 14px;text-align:left"><strong>Category</strong>
						<select name="category">
						<? foreach ($Categories as $Category => $ID) : ?>
							<option value="<?= $Category ?>" <? if ($Video->Info["category"] == $Category) : ?>selected<? endif ?>><?= $ID ?></option>
						<? endforeach ?>
						</select>
					</div>
				</td>
				<td style="vertical-align:top">
                    <div style="margin: 19px">
                        <select name="privacy" style="width:100%; text-align-last:center;">
                            <option value="0"<? if ($Video->Info["privacy"] == 0) : ?> selected<? endif ?>>Public</option>
                            <option value="1"<? if ($Video->Info["privacy"] == 1) : ?> selected<? endif ?>>Unlisted</option>
                            <option value="2"<? if ($Video->Info["privacy"] == 2) : ?> selected<? endif ?>>Private</option>
                        </select>
                    </div>
                    <div style="margin: 11px 0 0 19px;text-align:left">
                        <div style="font-weight: bold;margin-bottom:3px">Commenting</div>
                        <label><input type="radio" name="comments" style="position:relative;top:1.5px;margin:0" value="1"<? if ($Video->Info["s_comments"] == 1) : ?> checked<? endif ?>> Everyone</label><label style="padding-left:9px"><input type="radio" name="comments" style="position:relative;top:1.5px" value="2"<? if ($Video->Info["s_comments"] == 2) : ?> checked<? endif ?>> Friends</label><label style="padding-left:9px"><input type="radio" name="comments" style="position:relative;top:1.5px" value="0"<? if ($Video->Info["s_comments"] == 0) : ?> checked<? endif ?>> Nobody</label>
                    </div>
                    <div style="margin: 11px 0 0 19px;text-align:left">
                        <div style="font-weight: bold;margin-bottom:3px">Video Responses</div>
                        <label><input type="radio" name="responses" style="position:relative;top:1.5px;margin:0" value="1" <? if ($Video->Info["s_responses"] == 1) : ?> checked<? endif ?>> Accept</label><label style="padding-left:9px"><input type="radio" name="responses" style="position:relative;top:1.5px" value="2" <? if ($Video->Info["s_responses"] == 2) : ?> checked<? endif ?>> Auto</label><label style="padding-left:9px"><input type="radio" name="responses" style="position:relative;top:1.5px" value="0"<? if ($Video->Info["s_responses"] == 0) : ?> checked<? endif ?>> Disabled</label>
                    </div>
                    <div style="margin: 11px 0 0 19px;text-align:left">
                        <div style="font-weight: bold;margin-bottom:3px">Rating</div>
                        <label><input type="radio" name="ratings" value="1" style="position:relative;top:1.5px;margin:0"<? if ($Video->Info["s_ratings"] == 1) : ?> checked<? endif ?>> Enabled</label><label style="padding-left:9px"><input type="radio" name="ratings" value="0" style="position:relative;top:1.5px"<? if ($Video->Info["s_ratings"] == 0) : ?> checked<? endif ?>> Disabled</label>
                    </div>
                    <div style="margin: 11px 0 16px 19px;text-align:left">
                        <div style="font-weight: bold;margin-bottom:3px">Related Videos</div>
                        <label><input type="radio" name="related" value="1" style="position:relative;top:1.5px;margin:0"<? if ($Video->Info["s_related"] == 1) : ?> checked<? endif ?>> On</label><label style="padding-left:9px"><input type="radio" name="related" value="0" style="position:relative;top:1.5px"<? if ($Video->Info["s_related"] == 0) : ?> checked<? endif ?>> Off</label>
                    </div>
					<div style="margin-bottom: 10px"><strong>Thumbnail</strong>
						<? if ($Has_Custom_Thumbnail) : ?>
							<label>
								<img class="e_thmp" style="border:3px double #cccccc;width:136px;height:80px" id="thmp_sel" src="/usfi/thmp/<?= $Video->Info["url"] ?>.jpg?<?= rand(0,99) ?>">
								<input type="radio" style="display:none" name="c_thmp" id="c_thmp1" value="1" checked>
							</label>
							<label>
								<img class="e_thmp" style="border:3px double #cccccc;width:136px;height:80px;" src="/usfi/thmp/<?= $Video->Info["url"] ?>_.jpg?<?= rand(0,99) ?>">
								<input style="display:none" type="radio" name="c_thmp" id="c_thmp2"  value="0">
							</label>
							<label>
								<img class="e_thmp" onclick="document.getElementById('c_thmp_uploader').click()" style="border:3px double #cccccc;width:136px;height:80px" src="/img/no_th.jpg">
								<input type="radio" style="display:none" name="c_thmp" id="c_thmp3" value="2">
								<input type="file" style="display:none" name="c_thmp_uploader" id="c_thmp_uploader" onchange="$('#uploadWarning').html('Update the video info for the new thumbnail to show up.<br>Try clearing your cache if it does not appear right away')">
							</label>
						<? else : ?>
							<label <? if (!$Has_Thumbnail) echo "style=\"display:none\"" ?>>
								<img class="e_thmp" style="border:3px double #cccccc;width:136px;height:80px" id="thmp_sel" src="/usfi/thmp/<?= $Video->Info["url"] ?>.jpg?<?= rand(0,99) ?>">
								<input style="display:none" type="radio" name="c_thmp" id="c_thmp2"  value="0" checked>
							</label>
							<label>
								<img class="e_thmp" onclick="document.getElementById('c_thmp_uploader').click()" style="border:3px double #cccccc;width:136px;height:80px" src="/img/no_th.jpg">
								<input type="radio" style="display:none" name="c_thmp" id="c_thmp1" value="1">
								<input type="file" style="display:none" name="c_thmp_uploader" id="c_thmp_uploader" onchange="$('#uploadWarning').html('Update the video info for the new thumbnail to show up.<br>Try clearing your cache if it does not appear right away')">
							</label>
						<? endif ?>
					</div>
					<div id="uploadWarning" style="color:red; margin-bottom:15px; text-align:center; font-size: 10px;"></div>
					<? if ($_USER->Is_Partner) : ?>
						<div style="margin-bottom: 10px;"><strong>Upload New Video Version</strong>
							<? if ($Can_Change_Video) : ?>
							<div style="display:inline-block; margin-bottom:25px;">
								<div style="margin-bottom:2px; border:1px solid #ccc; padding:2px; position:relative;">
									<input id="uploadFile" type="file" style="width:250px;">
									<div id="uploadBar"></div>
								</div>
								<div id="uploadBarText" style="display:none">
									<span id="uploadBarPercent">0%</span>
									<span id="uploadBarStatus">Uploading...</span>
								</div>
							</div>
							
							<script>
								$("#uploadFile").change(function() {
									var file = $(this)[0].files[0];
									
									$(this).prop("disabled", true);
									$(this).animate({"opacity":0}, 500, function() {
										$("#uploadBarText").css("opacity", 0).show();
										$("#uploadBarText").animate({"opacity":1}, 500);
										$("#uploadBarPercent").html("0%");
										$("#uploadBarStatus").removeClass("error").html("Uploading...");
										
										new vlUploader({
											type: 1,
											url: "<?=$Video->Info["url"]?>",
											file: file,
											progress: function(p) {						
												p = Math.round(p * 10000) / 100;
												$("#uploadBar").stop().animate({"width": p+"%"}, 150);
												$("#uploadBarPercent").html(p+"%");
												$("#uploadBarStatus").removeClass("error").html("Uploading...");
											},
											complete: function() {
												$("#uploadBar").stop().animate({"width": "100%"}, 150);
												$("#uploadBarPercent").html("100%");
												$("#uploadBarStatus").html("Upload Complete!<br>Video will be available after conversion.");
											},
											error: function(e, fatal) {
												$("#uploadBarStatus").addClass("error").html(e);
												if (fatal) setTimeout(function() {
													$("#uploadBar").stop().animate({"width": "0%"}, 150);
													$("#uploadBarText").animate({"opacity":0}, 500, function() {
														$("#uploadBarText").hide();
														$("#uploadFile").val("").prop("disabled", false);
														$("#uploadFile").animate({"opacity":1}, 500);
													});
												}, 5000);
											}
										});
									});
								});
								
								$("#editVideoForm").on("submit", function() {
									$("#uploadFile").val("").prop("disabled", true);
									return true;
								});
							</script>
							<? else : ?>
							<div style="color:#f00; font-size: 10px; margin-bottom:25px; display:inline-block; width:250px;">
								Please, wait until the current video converts in order to upload a new replacement video.
							</div>
							<? endif ?>
						</div>
					<? endif ?>
				</td>
			</tr>
		</table>
        <input type="submit" value="Update Video Info" class="search_button" name="update_info">
    </form>
</div>
<script>
    $(".e_thmp").click(function() {
        $(".e_thmp").attr("id","");
        $(this).attr("id","thmp_sel");
    });
</script>