<form action="my_videos" method="POST">
    <div style="background: #e2e2e2;padding: 12px">
        <input type="submit" value="Delete Selected Videos" name="delete_videos">
    </div>
    <div style="border: 2px solid #e2e2e2; border-top: 0;padding: 10px;">
        <? if ($Videos) : ?>
        <? foreach($Videos as $Video) : ?>
			<?php
				switch($Video["status"]) {
					case "-3":
						$Video_Status = "Striked.";
						break;
					case "-2":
						$Video_Status = "Conversion failed.";
						break;
					case "-1":
						$Video_Status = "Uploading.";
						break;
					case "0":
						$Video_Status = "#$Video[queue] in queue.";
						break;
					case "1":
						$Video_Status = "Converting.";
						break;
					default:
						$Video_Status = "Live!";
				}

				switch($Video["status"]) {
					case "-1":
						break;
					case "1":
						$Video_Status = '<font color="orange">' . $Video_Status . '</font>';
						break;
					case "2":
						$Video_Status = '<font color="green">' . $Video_Status . '</font>';
						break;
					default:
						$Video_Status = '<font color="red">' . $Video_Status . '</font>';
				}	
			?>
			<div class="mv_div">
				<table class="mv_sct">
					<tr>
						<td width="5%" align="center" valign="top"><input type="checkbox"></td>
						<td width="20%" valign="top" align="center">
                            <div class="th">
                                <div class="th_t"><?= $Video["length"] ?></div>
                                <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="190" height="131"></a>
                            </div>
                        </td>
						<td width="70%" valign="top" class="mv_info">
							<strong><a href="watch?v=<?= $Video["url"] ?>"><?= $Video["title"] ?></a></strong><br>
							<div class="mv_descr"><?= $Video["description"] ?></div>
							<span>Tags:</span> <?= $Video["tags"] ?><br>
							<span>Added:</span> <?= get_date($Video["uploaded_on"])." | ".get_time($Video["uploaded_on"]); ?><br>
							<span title="1 Star [<?= $Video["1_star"] ?>, 2 Stars [<?= $Video["2_star"] ?>], 3 Stars [<?= $Video["3_star"] ?>], 4 Stars [<?= $Video["4_star"] ?>], 5 Stars [<?= $Video["5_star"] ?>]">Ratings:</span> <?= $Video["1_star"] + $Video["2_star"] + $Video["3_star"] + $Video["4_star"] + $Video["5_star"]?><br>
							<span>Comments:</span> <?= $Video["comments"] ?> | <span>Views:</span> <?= $Video["views"] ?><br>
							<span>Status:</span> <strong><?= $Video_Status ?></strong><br>
							<a href="/edit_video?v=<?= $Video["url"] ?>"><button type="button">Edit Video</button></a> <input type="button" value="Delete Video" class="delvidbtn" data-id="<?=$Video["url"]?>">
						</td>
					</tr>
				</table>
			</div>
        <? endforeach ?>
        <div style="padding-top: 5px;text-align: left;border-top:0" class="vc_pagination"><?= $_PAGINATION->new_show(NULL,"") ?></div>
        <? else : ?>
        <center style="font-size: 20px;color:gray">You don't have any videos!<br><span style="font-size:16px"><a href="/upload">Upload</a> some! :)</span></center>
        <? endif ?>
    </div>
</form>

<script>
	$(".delvidbtn").click(function() {
		if (confirm('Are you sure you want to delete this video?')) {
			var url = $(this).attr("data-id");
			var button = $(this);
			var container = button.parents(".mv_div");
			button.prop("disabled", true);
			
			$.ajax({
				url: '/ajax/df/delete',
				type: 'post',
				data: {v:url},
				success: function(r) {
					if (r == "1") {
						container.css("overflow", "hidden").animate({height: 0}, 500, container.remove);
						return false;
					}
					
					alert("Something went wrong, please try again later!");
					button.prop("disabled", false);
				},
				error: function() {
					alert("Something went wrong, please try again later!");
					button.prop("disabled", false);
				}
			});
		}
	});
</script>