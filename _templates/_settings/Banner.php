<form action="/partner_settings" method="POST" enctype="multipart/form-data">
	<div style="width:50%;padding-right:10px;margin-right:10px;">		
		<? if ($Has_Channel_Banner) : ?>
		<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/banner.php"; ?>
		
		<div class="banner_disclaimer">
			<i>* Use the mouse right click to either add, edit or remove links.</i>
			<i>** Hold down the mouse left click to either resize or drag the links around.</i>
		</div>
		
		<input type="button" onclick="bannerAction('save')" value="Save">
		<input type="button" onclick="bannerAction('delete')" value="Delete">
		<? else : ?>
		<div style="color:#303030;font-size:13px;margin-bottom: 12px">
			Your Channel Page Banner appears on top of your channel page.<br>
			Max File Size: <strong>300KB</strong><br>
			Exact Resolution: <strong>1000x150</strong>
		</div>
		
		<input type="file" name="channel_page_banner">
		<input type="button" onclick="bannerAction('upload')" value="Upload">
		<? endif ?>
	</div>
	
	<input type="hidden" name="channel_banner_links" value="">
	<input type="hidden" name="channel_banner_action" value="">
</form>

<script src="/js/bannerEditor.js"></script>
<script>
	function bannerAction(action) {
		if (action == "save")
			$("input[name='channel_banner_links']").val(ban.save());
		
		if (action == "delete") {
			if (!confirm("Are you sure you want to delete your channel banner?"))
				return false;
		}
		
		var input = $("input[name='channel_banner_action']");
		input.val(action).parent()[0].submit();
	}
	
	<? if ($Has_Channel_Banner) : ?>
	var ban = new ChannelBannerEditor($(".channel_banner"));
	<? endif ?>
</script>