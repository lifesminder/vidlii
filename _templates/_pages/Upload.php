<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js"></script>
<style>
	#uploader:hover > .yel_btn {
		text-decoration: underline;
	}
	#drag_and_drop_link {
		font-weight:bold;
		font-size:15px;
		position: relative;
		top: 35px;
		text-decoration: none;
		pointer-events: none;
	}
	
	#upload_status {
		margin-bottom:10px;
	}
	
	#upload_status.error {
		color:#f00;
	}
	
	#custom_thumb_button {
		border:3px double #cccccc;
		width:136px;
		height:80px;
		cursor:pointer;
	}
	
	#custom_thumb_button:hover,
	#custom_thumb_button.loading {
		border-color: #4d4ccc;
	}
	
	#custom_thumb_button.loading {
		opacity: 0.5;
		cursor:auto;
	}
	
	#custom_thumb_hiding {
		position:relative;
		overflow:hidden;
		width:0px;
		height:0px;
	}
	
	#custom_thumb_file {
		position:absolute;
	}
</style>
<script src="/js/vlUploader.js?4"></script>
<div class="h_l">
	<div class="you_wnt" id="old_upload_box">
		<div>
			<div style="background:white;margin:5px;border:1px solid #ccc;border-radius:8px;position:relative;padding:130px 80px">
				<form action="/upload" method="POST" id="uploader">
					<input type="file" id="selectedFile" style="cursor:pointer;opacity:0;position:absolute;left:0;top:0;width:633px;height:298px" onchange="new_upload()">
					<a href="javascript:void(0)" onclick="document.getElementById('selectedFile').click();" class="yel_btn" id="upload_button" style="border-radius:9px;padding:19px 37px;font-size:22px">Select a file to Upload</a><br>
					<a id="drag_and_drop_link" href="javascript:void(0)">Or drag and drop a video file</a>
				</form>
			</div>
		</div>
	</div>
	<div class="you_wnt" id="upload_select_box" style="overflow:hidden;display:none">
		<div style="text-align:left;overflow:hidden">
			<div id="vurl" style="display:none"></div>
			<div style="background:white;border:1px solid #ccc;padding: 15px;width:603px;float:left;margin:5px;border-radius:8px" id="video_uploader">
				<div id="video_title_header" style="font-weight:bold;margin-bottom:10px"></div>
				<div id="video_progress" style="margin-bottom:5px">
					<div id="video_progress_in">0%</div>
				</div>
				<div id="upload_status"></div>
				<div style="border-bottom:1px solid #dcdcdc;margin-bottom:17px"></div>
				<div>
					<div style="float:right; display:inline-block; width: 142px">
                        <div style="margin: 20px 0; text-align-last:center">
                            <select id="privacy" style="width:100%" onchange="if (this.value != 3) { $('#schedule_up').addClass('hddn'); } else { $('#schedule_up').removeClass('hddn'); }">
                                <option value="0">Public</option>
                                <option value="1">Unlisted</option>
                                <option value="2">Private</option>
                                <option value="3">Scheduled</option>
                            </select>
                            <input type="text" id="schedule_up" class="hddn" style="width:93%; margin-top:10px" placeholder="Pick a date">
                            <script>$('#schedule_up').datetimepicker();</script>
                        </div>
						<img id="custom_thumb_button" src="">
						<i style="display:block; text-align:center; font-size:10px;">*click to upload thumbnail</i>
					</div>
					<div id="custom_thumb_hiding"><input type="file" id="custom_thumb_file"></div>
					<strong style="display:block;margin-bottom:2px;">Video Title:</strong>
					<input type="text" style="width:320px" id="video_title" maxlength="100">
					<strong style="display:block;margin-top:12px;margin-bottom:2px;">Video Description:</strong>
					<textarea style="width:403px" rows="5" id="video_description" maxlength="1000"></textarea>
					<strong style="display:block;margin-top:8px;margin-bottom:2px;">Video Tags:</strong>
					<input type="text" style="width:320px" id="video_tags" maxlength="256"<? if (isset($_GET["c"])) : ?> value="vidliibit"<? endif ?>>
					<strong style="display:block;margin-top:8px;margin-bottom:2px;">Category</strong>
					<select id="video_category">
						<? foreach ($Categories as $Category => $ID) : ?>
						<option value="<?= $Category ?>"><?= $ID ?></option>
						<? endforeach ?>
					</select>
					<div style="display:block;margin-top:22px">
						<input type="button" id="save_upload_changes_button" value="Save Changes" onclick="if (isEmptyOrSpaces(_('video_title').value) == false) {save_video_changes()} else { alert('Please enter a title') }">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="line-height:25px;margin-top:3px">
	Do you need help with your upload?<br>
	Try out the <a href="/help">Help Section</a> or write us an email!
	</div>
</div>
<div class="h_r">
	<div class="you_wnt" id="upload_select_box">
		<div style="text-align:left;padding:11px">
			<strong style="text-align:center;display:block;margin-bottom:9px">Videos can be:</strong>
			<ul style="padding:0;margin:0 0 0 13px">
				<li style="margin-bottom:2px">Up to 2GB in size.</li>
				<li style="margin-bottom:2px">Up to <?= $_USER->Is_Partner ? 35 : 25 ?> minutes in length.</li>
				<li style="margin-bottom:2px">Any Resolution.</li>
				<li style="margin-bottom:2px">A wide variety of formats.</li>
			</ul>
		</div>
	</div>
	<div class="you_wnt" id="upload_select_box">
		<div style="text-align:left;padding:11px">
			<strong style="text-align:center;display:block;margin-bottom:9px">Important:</strong>
			Do not upload any TV shows, music videos, music concerts, or commercials without permission unless they consist entirely of content you created yourself.<br><br>
			The Copyright page and the community guidelines can help you determine whether your video infringes someones else's copyright.<br><br>
			By clicking "Upload Video", you are representing that this video does not violate VidLii's Terms of Use and that you own all copyrights in this video or have authorization to upload it.
		</div>
	</div>
</div>
<script>
	var drop = document.getElementById("selectedFile");
	drop.addEventListener("dragenter", change, false);
	drop.addEventListener("dragleave",change_back,false);

	function change() {
		$("#upload_button").html("Drop the Video File");
	};

	function change_back() {
		$("#upload_button").html("Select a file to Upload");
	};
</script>