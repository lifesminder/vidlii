<script>
	function getCookie(name) {
		var value = "; " + document.cookie;
		var parts = value.split("; " + name + "=");
		if (parts.length == 2) return parts.pop().split(";").shift();
	}
	
	function expandPlayer() {
		var cook = getCookie("cp2");
		cook = (cook == "" ? ["1","0"] : cook.split(","));
		
		$("#vtbl").toggleClass("expanded");
		cook[1] = $("#vtbl").hasClass("expanded") ? "1" : "0";
		
		var CookieDate = new Date;
		CookieDate.setFullYear(CookieDate.getFullYear() + 10);
		document.cookie = 'cp2='+cook.join(",")+'; expires=' + CookieDate.toGMTString( ) + ';';
		$(".vlPlayer").toggleClass("expanded");
	}
	
	var videoInfo = {
		expand: expandPlayer,
		complete: function() {
			expandPlayer();
			expandPlayer();
		},
		ended: function() {
			<? if (isset($NextVideo) && isset($Playlist_Videos[$NextVideo])) : ?>
				window.location = '/watch?v=<?= $Playlist_Videos[$NextVideo]["url"] ?>&pl=<?= $_GET["pl"] ?>';
			<? endif ?>
		}
	};
</script>

<style>
	#vtbl_pl,
	#vtbl_actions,
	#vtbl_desc {
		display:inline-block;
		vertical-align:top;
	}
	
	#vtbl_pl,
	#vtbl_actions {
		width:640px;
		margin-right:20px;
		overflow:hidden;
	}
	
	#vtbl_pl {
		height:360px;
	}
	
	#vtbl_desc {
		width:340px;
		float:right;
	}
	
	#vtbl.expanded #vtbl_pl {
		width:1000px;
		height:563px;
		margin-right:0;
	}
	
	#vtbl.expanded #vtbl_desc {
		margin-top:11px;
	}
</style>

<? if ($Status == 2) : ?>
<div class="w_title">
    <h1<? if ($Size == 1) : ?> style="width:auto"<? endif ?>><?= $Title ?></h1>
</div>
<? endif ?>

<div id="vtbl">
        <noscript>
            <div id="noscript-player">
                <video id="noscript-player-video" src="/usfi/v/<?= $URL ?>.<?= $FILENAME ?><?= ($ISHD and $HD_Enabled) ? ".720" : "" ?>.mp4" controls autoplay></video>
                <? if($ISHD): ?>
                    <form action="" method="GET" id="noscript-player-hd">
                        <input type="hidden" name="v" value="<?= $_GET["v"] ?>">
                        <input type="hidden" name="hd" value="<?= (int) (!$HD_Enabled) ?>">
                        <? if($HD_Enabled): ?>
                            <input type="submit" value="Switch to SD" id="noscript-player-hd-button">
                        <? else: ?>
                            <input type="submit" value="Switch to HD" id="noscript-player-hd-button">
                        <? endif; ?>
                    </form>
                <? endif; ?>
            </div>
        </noscript>
		<div id="vtbl_pl" style="display: none">
			<? require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/player.php"; ?>
		</div><? if ($Status == 2) : ?><div id="vtbl_desc">
			<? if ($Size == 1) : ?><script>expandPlayer();</script><? endif ?>
			<? require_once("watch_widgets/Playlist.php"); ?>
						
			<? if ($_COOKIE["advPlace"] == "down") : ?>
				<? $advPlace = "down"; ?>
				<? require_once("watch_widgets/Description.php"); ?>
			<? else : ?>
				<? $advPlace = "up"; ?>
				<? require_once("watch_widgets/Description.php"); ?>
			<? endif ?>
			
			<? if ($Other_Videos) : ?>
			<div class="u_sct" style="margin:0 0 10px">
				<img src="/img/clp00.png">
				<span class="u_sct_hd" style="font-size: 17px;position:relative;top:1px;left:5px">More From: <?= $Uploader["displayname"] ?></span>
			</div>
			<div class="w_videos" style="display:none">
				<div>
					<? foreach ($Other_Videos as $Video) : ?>
						<div>
                            <div class="th">
                                <div class="th_t"><?= $Video["length"] ?></div>
                                <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="121" height="78"></a>
                            </div>
							<div>
								<a href="/watch?v=<?= $Video["url"] ?>" class="ln2"><?= $Video["title"] ?></a>
								<span class="vw s"><?= number_format($Video["views"]) ?> views</span><br>
								<? show_ratings($Video,14,14) ?>
							</div>
						</div>
					<? endforeach ?>
				</div>
			</div>
			<? endif ?>
			<? if ($Related_Videos && $_VIDEO->Info["s_related"] == 1) : ?>
			<div class="u_sct">
				<img src="/img/clp11.png">
				<span class="u_sct_hd" style="font-size: 17px;position:relative;top:1px;left:5px">Related Videos</span>
			</div>
			<div class="w_videos" style="display:block">
				<div>
					<? foreach ($Related_Videos as $Video) : ?>
					<div>
                        <div class="th">
                            <div class="th_t"><?= $Video["length"] ?></div>
                            <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="121" height="78"></a>
                        </div>
						<div>
							<a href="/watch?v=<?= $Video["url"] ?>" class="ln2"><?= $Video["title"] ?></a>
							<span class="vw s"><?= number_format($Video["views"]) ?> views</span><br>
							<? show_ratings($Video,14,14) ?>
						</div>
					</div>
					<? endforeach ?>
				</div>
			</div>
			<? endif ?>
		</div>
        <div id="vtbl_actions">
		<script> vitag.videoConfig = { width: 640, height: 480, loadingText: "Loading advertisement..", complete: function () { }, error: function () { }, hidden: function () { } }; function loadAd() { (vitag.Init = window.vitag.Init || []).push(function () { viAPItag.startPreRoll("vi_2050787313"); }); } </script>
            <? if (isset($Single_Response)) : ?>
            <div style="    background: #ffc;border: 1px solid #fc3;font-weight: bold;padding: 5px; margin-top: 11px;">This video is a response to: <a href="/watch?v=<?= $Single_Response["url"] ?>"><?= $Single_Response["title"] ?></a></div>
            <? endif ?>
            <div class="w_actions">
				<div id="rateYo"></div>
				<div id="ratings"><? if ($_VIDEO->Info["s_ratings"] != 0 ) : ?><?= number_format($Total_Ratings) ?> rating<? if ($Total_Ratings !== 1) : ?>s<? endif ?><? else : ?>Disabled<? endif ?></div>
				<div class="w_views"><strong><?= number_format($_VIDEO->Info["views"]) ?></strong> view<? if ($_VIDEO->Info["views"] != 1) : ?>s<? endif ?></div>
				<script>
                    <? if ($_VIDEO->Info["s_ratings"] != 0) : ?>
                    $(function () {
                        $("#rateYo").rateYo({
                            ratedFill: "#<? if ($_VIDEO->Info["featured"] == 0) : ?>E74C3C<? else : ?>E3BF00<? endif ?>",
                            normalFill: "#c7c7c7",
                            fullStar: true,
                            starWidth: "19px"<? if (!$_USER->logged_in || $Is_Blocked || $Has_Blocked) : ?>,
                            <? if ($Total_Ratings > 0) : ?>
                            rating: <?= ($_VIDEO->Info["1_star"] + $_VIDEO->Info["2_star"] * 2 + $_VIDEO->Info["3_star"] * 3 + $_VIDEO->Info["4_star"] * 4 + $_VIDEO->Info["5_star"] * 5) / ($Total_Ratings); ?>,
                            <? else : ?>
                            rating: 0,
                            <? endif ?>
                            readOnly: true
                            <? elseif ($_USER->logged_in) : ?>,
                            <? if ($Total_Ratings > 0) : ?>
                            rating: <?= ($_VIDEO->Info["1_star"] + $_VIDEO->Info["2_star"] * 2 + $_VIDEO->Info["3_star"] * 3 + $_VIDEO->Info["4_star"] * 4 + $_VIDEO->Info["5_star"] * 5) / ($Total_Ratings); ?>,
                            onChange: function (rating, rateYoInstance) {
                                if (rating == 1) {
                                    $("#ratings").html("Terrible...")
                                } else if (rating == 2) {
                                    $("#ratings").html("Bad..")
                                } else if (rating == 3) {
                                    $("#ratings").html("OK.")
                                } else if (rating == 4) {
                                    $("#ratings").html("Good!")
                                } else if (rating == 5) {
                                    $("#ratings").html("Very Good!")
                                }
                            }
                            <? else : ?>
                            rating: 0,
                            onChange: function (rating, rateYoInstance) {
                                if (rating == 1) {
                                    $("#ratings").html("Terrible...")
                                } else if (rating == 2) {
                                    $("#ratings").html("Bad..")
                                } else if (rating == 3) {
                                    $("#ratings").html("OK.")
                                } else if (rating == 4) {
                                    $("#ratings").html("Good!")
                                } else if (rating == 5) {
                                    $("#ratings").html("Very Good!")
                                }
                            }
                            <? endif ?>
                            <? elseif ($_USER->logged_in && $Has_Rated_Video !== false) : ?>,
                            <? if ($Total_Ratings > 0) : ?>
                            rating: <?= ($_VIDEO->Info["1_star"] + $_VIDEO->Info["2_star"] * 2 + $_VIDEO->Info["3_star"] * 3 + $_VIDEO->Info["4_star"] * 4 + $_VIDEO->Info["5_star"] * 5) / ($Total_Ratings); ?>,
                            <? else : ?>
                            rating: 0,
                            <? endif ?>
                            readOnly: true
                            <? endif ?>
                        });

                        <? if ($_USER->logged_in && ($Has_Blocked || $Is_Blocked)) : ?>
                        $("#rateYo").click(function() {
                            alert("You cannot interact with <?= $_VIDEO->Info["uploaded_by"] ?>!");
                        });
                        <? endif ?>

                        <? if ($_USER->logged_in) : ?>
                        $("#rateYo").rateYo()
                            .on("rateyo.set", function (e, data) {
                                var rating = data.rating;
                                rate_video("<?= $URL ?>",rating);
                                rated = true;
                            });
                        rated = false;

                        $("#rateYo").mouseleave(function () {
                            if (rated == false) {
                                $("#ratings").html("<?= number_format($Total_Ratings) ?> rating<? if ($Total_Ratings !== 1) : ?>s<? endif ?>");
                            }
                        });
                        <? elseif (!$_USER->logged_in) : ?>
                        $("#rateYo").click(function() {
                            alert("You must be logged in to rate videos!");
                        });
                        <? elseif ($_USER->logged_in && $Has_Rated_Video !== false) : ?>
                        $("#rateYo").mouseenter(function() {
                            $("#rateYo").rateYo("option", "rating", <?= $Has_Rated_Video ?>);
                        });
                        $("#rateYo").mouseleave(function() {
                            $("#rateYo").rateYo("option", "rating", <?= ($_VIDEO->Info["1_star"] + $_VIDEO->Info["2_star"] * 2 + $_VIDEO->Info["3_star"] * 3 + $_VIDEO->Info["4_star"] * 4 + $_VIDEO->Info["5_star"] * 5) / ($Total_Ratings); ?>);
                        });
                        <? endif ?>
                    });
                    <? else : ?>
                    $(function () {
                        $("#rateYo").rateYo({
                            ratedFill: "#E74C3C",
                            normalFill: "#c7c7c7",
                            fullStar: true,
                            starWidth: "19px",
                            rating: 0,
                            readOnly: true
                        });
                    });
                    <? endif ?>
				</script>
				<div class="w_lnks">
					<a href="javascript:void(0)" onmouseenter="wn('w_sh')" onmouseleave="wl('w_sh')" onclick="wc('w_sh')"><img src="/img/shhd1.png" id="w_sh"><span style="top:2px">Share</span></a><a href="javascript:void(0)" onmouseenter="wn('w_fv')" onmouseleave="wl('w_fv')" onclick="wc('w_fv')"><img src="/img/hehd0.png" id="w_fv"><span>Favorite</span></a><a href="javascript:void(0)" onmouseenter="wn('w_pl')" onmouseleave="wl('w_pl')" onclick="wc('w_pl')"><img src="/img/plhd0.png" id="w_pl"><span>Playlists</span></a><a href="javascript:void(0)" onmouseenter="wn('w_fl')" onmouseleave="wl('w_fl')" onclick="wc('w_fl')"><img src="/img/flhd0.png" id="w_fl"><span>Flag</span></a>
				</div>
				<div id="w_l_cnts">
					<img src="/img/wse.png" id="w_sel" style="left:84px">
					<div id="w_sh_cnt">
						<span><a href="https://www.facebook.com/sharer/sharer.php?u=https%3A//www.vidlii.com/watch?v=<?= $URL ?>" target="_blank" onclick="playerInstance.pause(true)">Facebook</a></span><span><a href="https://twitter.com/home?status=I%20just%20watched%20this%20awesome%20video%3A%20https%3A//www.vidlii.com/watch?v=<?= $URL ?>" target="_blank" onclick="playerInstance.pause(true)">Twitter</a></span><span><a href="https://www.reddit.com/submit?url=/watch?v=<?= $URL ?>&title=<?= $Title ?>" target="_blank" onclick="playerInstance.pause(true)">Reddit</a></span>
					</div>
					<div id="w_fv_cnt" class="hddn">
						<? if (!$_USER->logged_in) : ?>
						<div class="you_wnt">
							<div>
								<strong>Want to add this video to your favorites?</strong><br>
								<strong><a href="/login">Sign in to VidLii now!</a></strong>
							</div>
						</div>
						<? elseif (!$Has_Favorited) : ?>
						 <div style="text-align:center" id="w_ff">
							Are you sure you want to <strong>add</strong> this video to your favorites? <a href="javascript:void(0)" class="a_btn" onclick="add_video_favorite('<?= $URL ?>')">Yes!</a>
						 </div>
						<? else : ?>
							<div style="text-align:center" id="w_ff">
							Are you sure you want to <strong>remove</strong> this video to your favorites? <a href="javascript:void(0)" class="a_btn" onclick="add_video_favorite('<?= $URL ?>')">Yes!</a>
							</div>
						<? endif ?>
					</div>
					<div id="w_pl_cnt" class="hddn">
						<? if (!$_USER->logged_in) : ?>
						<div class="you_wnt">
							<div>
								<strong>Want to add this video to your playlists?</strong><br>
								<strong><a href="/login">Sign in to VidLii now!</a></strong>
							</div>
						</div>
						<? elseif (count($Playlists) > 0) : ?>
							<div style="text-align:center" id="wnn">
							<div style="font-weight:bold">Add To Your Playlist</div>
							<select style="width:230px;margin:4px 0 0 0" id="watch_playlist">
								<? foreach ($Playlists as $Playlist) : ?>
									<option value="<?= $Playlist["purl"] ?>"><?= $Playlist["title"] ?></option>
								<? endforeach ?>
							</select>
							<button type="button" class="search_button" onclick="add_to_playlist('<?= $URL ?>')" style="height:24px;position:relative;bottom:0.5px">Add</button>
							</div>
						<? else : ?>
							<div style="text-align:center;line-height:17px">
								<div style="font-weight:bold">You don't seem to have any Playlists.</div>
								Do you want to <strong><a href="/my_playlists">create one</a>?</strong>
							</div>
						<? endif ?>
					</div>
					<div id="w_fl_cnt" class="hddn">
						<? if (!$_USER->logged_in) : ?>
						<div class="you_wnt">
							<div>
								<strong>Want to flag this video?</strong><br>
								<strong><a href="/login">Sign in to VidLii now!</a></strong>
							</div>
						</div>
						<? else : ?>
							<div style="text-align:center" id="w_flag">
								<div style="font-weight:bold">Flag this Video</div>
								<select style="width:230px;margin:4px 0 0 0" id="flag_select">
									<option value="1">Sexual Content</option>
									<option value="2">Spam or Misleading</option>
								</select>
								<button type="button" class="search_button" style="height:24px;position:relative;bottom:0.5px" onclick="flag_video('<?= $URL ?>')">Flag</button>
							</div>
						<? endif ?>
                        <script async>setTimeout(function(){$.ajax({type:"POST",url:"/ajax/aw",data:{u:'<?= $_VIDEO->Info["url"] ?>',a:<? if (isset($_SERVER["HTTP_REFERER"])) : ?>'<?= $_SERVER["HTTP_REFERER"] ?>'<? else : ?>''<? endif ?>}})},1750);</script>
					</div>
				</div>
			</div>
			<script> vitag.outStreamConfig = { type: "slider", position: "right" }; </script>
			<div class="w_btm">
				<div style="display:table;width:100%">
					<div class="w_big_btn big_sel" id="w_com">
						<a href="javascript:void(0)">Commentary</a>
					</div>
					<div class="w_big_btn" id="w_stats">
						<a href="javascript:void(0)">Statistics</a>
					</div>
				</div>
				<div class="cl"></div>
				<div id="w_com_sct">
					<div class="u_sct">
						<? if ($_VIDEO->Info["responses"] > 0) : ?>
							<img src="/img/clp11.png">
							<span class="u_sct_hd">Video Responses <span>(<span><?= number_format($_VIDEO->Info["responses"]) ?></span>)</span></span>
						<? else : ?>
							<img src="/img/clp00.png">
							<span class="u_sct_hd">Video Responses <span>(<span>0</span>)</span></span>
						<? endif ?>
                        <? if ($_VIDEO->Info["s_responses"] != 0) : ?>
						<? if (!$_USER->logged_in) : ?><a href="/login" onclick="event.stopPropagation()">Sign in to make a video response</a><? else : ?><a href="/post_response?v=<?= $URL ?>" onclick="event.stopPropagation();">Submit a video response</a><? endif ?>
					    <? endif ?>
                    </div>
					<div style="<? if ($_VIDEO->Info["responses"] > 0) : ?>display:block;margin-bottom:8px<? else : ?>display:none;margin-bottom:20px<? endif ?>">
						<? if ($_VIDEO->Info["responses"] > 0) : ?>
							<? foreach ($Responses as $Response) : ?>
								<div class="v_resp">
									<?= video_thumbnail($Response["url_response"],$Response["length"],111,75) ?>
									<a href="/watch?v=<?= $Response["url_response"] ?>"><?= limit_text($Response["title"],50) ?></a>
								</div>
							<? endforeach ?>
							<div class="cl"></div>
						<? else : ?>
							<div style="text-align:center;margin-top:22px">This video doesn't have any video responses!</div>
						<? endif ?>
					</div>
					<div class="u_sct">
						<img src="/img/clp11.png">
						<span class="u_sct_hd">Text Comments <span>(<span id="cmt_num"><?= $Comments_Num ?></span>)</span></span>
						<? if (!$_USER->logged_in) : ?><a href="/login" onclick="event.stopPropagation()">Sign in to post a comment</a><? else : ?><a href="javascript:void(0)" onclick="$('#comment_textarea').focus();event.stopPropagation();">Write Comment</a><? endif ?>
					</div>
					<div style="display:block; word-wrap: break-word">
						<? if ($Top_Comments !== false && (!isset($_GET["p"]) || $_GET["p"] == 1)) : ?>
						<div id="top_comments">
							<? foreach ($Top_Comments as $Comment) : ?>
								<?
								if (!empty($Comment["raters"]) && $_USER->logged_in) {
									if (strpos($Comment["raters"],$_USER->username."+") !== false) {
										$Rated = "1";
									} elseif (strpos($Comment["raters"],$_USER->username."-") !== false) {
										$Rated = "-1";
									} else {
										$Rated = 2;
									}
								} else {
									$Rated = 2;
								}
								?>
								<div class="wt_c_sct" id="wt_<?= $Comment["id"] ?>">
									<div style="background:#cfffc4">
										<a href="/user/<?= $Comment["displayname"] ?>"><?= $Comment["displayname"] ?></a> <span>(<?= get_time_ago($Comment["date_sent"]) ?>)</span>
									</div>
									<div>
										<?= user_avatar2($Comment["displayname"],41,41,$Comment["avatar"],"wp_avt") ?>
										<div>
											<span<? if ($Comment["rating"] < 0) : ?> style="color:red"<? elseif ($Comment["rating"] > 0) : ?> style="color:green"<? endif ?>><?= $Comment["rating"] ?></span>
											<? if (!$_USER->logged_in) : ?><img src="/img/td0.png" onclick="alert('Please sign in to rate this comment')"><img src="/img/tu0.png" onclick="alert('Please sign in to rate this comment')"><? else : ?><img <? if ($Rated == "-1") : ?>src="/img/td1.png" style="opacity:0.75" <? else : ?>src="/img/td0.png" onclick="wr(<?= $Comment["id"] ?>,'0',this)"<? endif ?>><img <? if ($Rated == "1") : ?> src="/img/tu1.png" style="opacity:0.75" <? else : ?>src="/img/tu0.png" onclick="wr(<?= $Comment["id"] ?>,'1',this)"<? endif ?>><? endif ?>
										</div>
										<div>
											<?= showBBcodes(hashtag_search(mention(nl2br($Comment["comment"])))) ?>
										</div>
									</div>
								</div>
							<? endforeach ?>
						</div>
						<? endif ?>
						<div id="video_comments_section">
							<? if ($_VIDEO->Info["comments"] > 0) : ?>
								<? foreach ($Comments_Array as $Comment) : ?>
									<? $OP_ID = $Comment["id"] ?>
									<? $OP_USER = $Comment["by_user"] ?>
									<?
										if (!empty($Comment["raters"]) && $_USER->logged_in) {
											if (strpos($Comment["raters"],$_USER->username."+") !== false) {
												$Rated = "1";
											} elseif (strpos($Comment["raters"],$_USER->username."-") !== false) {
												$Rated = "-1";
											} else {
												$Rated = 2;
											}
										} else {
											$Rated = 2;
										}
									?>
								<div class="wt_c_sct<? if ($Comment["rating"] < -4) : ?> op_c<? endif ?>" id="wt_<?= $Comment["id"] ?>">
									<div <? if ($Uploaded_By == $Comment["by_user"]) : ?> style="background:#fffcc2"<? elseif ("VidLii" == $Comment["by_user"]) : ?> style="background:#d2ebff"<? endif ?>>
										<a href="/user/<?= $Comment["displayname"] ?>"><?= $Comment["displayname"] ?></a> <span>(<?= get_time_ago($Comment["date_sent"]) ?>)</span>
										<div>
											<? if ($_USER->logged_in && !$Has_Blocked && !$Is_Blocked) : ?><a href="javascript:void(0)" onclick="show_reply(<?= $Comment["id"] ?>,<? if ($_USER->logged_in && $Uploaded_By == $_USER->username) : ?>true<? else : ?>false<? endif ?>,'<?=$Comment["displayname"]?>')">Reply</a><? endif ?><? if ($_USER->logged_in && ($Uploaded_By == $_USER->username || $Comment["by_user"] == $_USER->username || $_USER->Is_Admin || $_USER->Is_Mod)) : ?><a href="javascript:void(0)" onclick="delete_wtc(<?= $Comment["id"] ?>)" style="padding-left:9px;margin-left:9px;border-left:1px solid #7d7d7d">Delete</a><? endif ?>
										</div>
									</div>
									<div>
										<?= user_avatar2($Comment["displayname"],41,41,$Comment["avatar"],"wp_avt") ?>
										<div>
											<span<? if ($Comment["rating"] < 0) : ?> style="color:red"<? elseif ($Comment["rating"] > 0) : ?> style="color:green"<? endif ?>><?= $Comment["rating"] ?></span>
											<? if (!$_USER->logged_in) : ?><img src="/img/td0.png" onclick="alert('Please sign in to rate this comment')"><img src="/img/tu0.png" onclick="alert('Please sign in to rate this comment')"><? else : ?><img <? if ($Rated == "-1") : ?>src="/img/td1.png" style="opacity:0.75" <? else : ?>src="/img/td0.png" onclick="wr(<?= $Comment["id"] ?>,'0',this)"<? endif ?>><img <? if ($Rated == "1") : ?> src="/img/tu1.png" style="opacity:0.75" <? else : ?>src="/img/tu0.png" onclick="wr(<?= $Comment["id"] ?>,'1',this)"<? endif ?>><? endif ?>
										</div>
										<div>
											<?= showBBcodes(hashtag_search(mention(nl2br($Comment["comment"])))) ?>
										</div>
									</div>
								</div>
								<?
								if ($Comment["has_replies"]) {
									$Replies = $DB->execute("SELECT video_comments.*, users.avatar, users.displayname FROM video_comments INNER JOIN users ON video_comments.by_user = users.username WHERE video_comments.reply_to = :ID ORDER BY date_sent DESC LIMIT 2", false, [":ID" => $Comment["id"]]);
									if ($DB->RowNum > 0) {
										if ($DB->RowNum == 2) {
											$Show_More = $DB->execute("SELECT id FROM video_comments WHERE reply_to = ".$Comment["id"]);
											$Show_More = $DB->RowNum;
										} else {
											$Show_More = false;
										}
										$Replies = array_reverse($Replies);
									} else {
										$Replies = false;
									}
								} else {
									$Replies = false;
								}
								?>
								<? if ($Replies !== false) : ?>
									<? if ($Show_More !== false && $Show_More > 2) : ?>
									<a href="javascript:void(0)" class="show_more" onclick="show_all_replies(<?= $OP_ID ?>)" id="sa_<?= $OP_ID ?>">Show all <?= $Show_More ?> replies</a>
									<? endif ?>
									
									<? foreach ($Replies as $Comment) : ?>
											<?
											if (!empty($Comment["raters"]) && $_USER->logged_in) {
												if (strpos($Comment["raters"],$_USER->username."+") !== false) {
													$Rated = "1";
												} elseif (strpos($Comment["raters"],$_USER->username."-") !== false) {
													$Rated = "-1";
												} else {
													$Rated = 2;
												}
											} else {
												$Rated = 2;
											}
											?>
											<div class="wt_c_sct wt_r_sct<? if ($Comment["rating"] < -4) : ?> op_c<? endif ?>" id="wt_<?= $Comment["id"] ?>" op="<?= $OP_ID ?>" data-op-user="<?= $Comment["by_user"] ?>">
												<div <? if ($Uploaded_By == $Comment["by_user"]) : ?> style="background:#fffcc2"<? elseif ("VidLii" == $Comment["by_user"]) : ?> style="background:#d2ebff"<? endif ?>>
													<a href="/user/<?= $Comment["displayname"] ?>"><?= $Comment["displayname"] ?></a> <span>(<?= get_time_ago($Comment["date_sent"]) ?>)</span>
													<div>
														<? if ($_USER->logged_in && !$Has_Blocked && !$Is_Blocked) : ?><a href="javascript:void(0)" onclick="show_reply(<?= $OP_ID ?>,<? if ($_USER->logged_in && $Uploaded_By == $_USER->username) : ?>true<? else : ?>false<? endif ?>,'<?=$Comment["displayname"]?>')">Reply</a><? endif ?><? if ($_USER->logged_in && ($Uploaded_By == $_USER->username || $Comment["by_user"] == $_USER->username || $_USER->Is_Admin || $_USER->Is_Mod)) : ?><a href="javascript:void(0)" onclick="delete_wtc(<?= $Comment["id"] ?>)" style="padding-left:9px;margin-left:9px;border-left:1px solid #7d7d7d">Delete</a><? endif ?>
													</div>
												</div>
												<div>
													<?= user_avatar2($Comment["displayname"],41,41,$Comment["avatar"],"wp_avt") ?>
													<div>
														<span<? if ($Comment["rating"] < 0) : ?> style="color:red"<? elseif ($Comment["rating"] > 0) : ?> style="color:green"<? endif ?>><?= $Comment["rating"] ?></span>
														<? if (!$_USER->logged_in) : ?><img src="/img/td0.png" onclick="alert('Please sign in to rate this comment')"><img src="/img/tu0.png" onclick="alert('Please sign in to rate this comment')"><? else : ?><img <? if ($Rated == "-1") : ?>src="/img/td1.png" style="opacity:0.75" <? else : ?>src="/img/td0.png" onclick="wr(<?= $Comment["id"] ?>,'0',this)"<? endif ?>><img <? if ($Rated == "1") : ?> src="/img/tu1.png" style="opacity:0.75" <? else : ?>src="/img/tu0.png" onclick="wr(<?= $Comment["id"] ?>,'1',this)"<? endif ?>><? endif ?>
													</div>
													<div style="width:442px">
														<?= showBBcodes(hashtag_search(mention(nl2br($Comment["comment"])))) ?>
													</div>
												</div>
											</div>
									<? endforeach ?>
								<? endif ?>
								<? if ($_USER->logged_in) : ?>
								<div id="r_cmt_<?= $OP_ID ?>"></div>
								<? endif ?>
								<? endforeach ?>
							<? else : ?>
							<div id="no_video_comments">This video has no comments yet!</div>
							<? endif ?>
						</div>
						<? if ($_VIDEO->Info["comments"] > 15) : ?>
							<div class="w_pag">
								<?= $_PAGINATION->new_show($_VIDEO->Info["comments"],"v=$URL") ?>
							</div>
						<? endif ?>
						<? if ($_USER->logged_in && $_USER->Is_Activated && !$Is_Blocked && !$Has_Blocked && ($_VIDEO->Info["s_comments"] == 1 || ($_VIDEO->Info["s_comments"] == 2 && $_USER->logged_in && $Is_Friends) || ($_USER->logged_in && $_USER->username == $_VIDEO->Info["uploaded_by"]))) : ?>
							<? if (!isset($_GET["p"]) || $_GET["p"] == 1) : ?>
								<div style="margin: 13px 0 5px">
									<div style="font-weight:bold;font-size:16px;margin:0 0 4px">Post a Comment:</div>
									<textarea id="comment_textarea" style="height:80px" onkeyup="textCounter(this,'counter',1000);" rows="5" maxlength="1000" placeholder="Your Comment..."></textarea>
									<div>
										<button type="button" class="search_button" id="video_button" onclick="post_video_comment('<?= $URL ?>'<? if ($Uploaded_By == $_USER->username) : ?>,true<? else : ?>,false<? endif ?>)">Post Comment</button><span class="tiny_text"> <span id="counter">0</span> / 1000</span>
									</div>
								</div>
							<? endif ?>
						<? elseif (!$_USER->logged_in) : ?>
							<div style="text-align:center;margin: 13px 0 5px">
								<a href="/register">Sign up</a> for a free account, or <a href="/login">sign in</a> to post a comment.
							</div>
						<? elseif (!$_USER->Is_Activated) : ?>
							<div style="text-align:center;margin: 13px 0 5px">
								You must activate your account before you can post a comment.
							</div>
						<? elseif ($Is_Blocked || $Has_Blocked) : ?>
							<div style="text-align:center;margin: 13px 0 5px">
								You cannot interact with this user.
							</div>
						<? else : ?>
                            <div style="text-align:center;margin: 13px 0 5px">
                                <? if ($_VIDEO->Info["s_comments"] == 2) : ?>
                                    You must be friends with the uploader to post a comment.
                                <? else : ?>
                                    Comments under this video have been disabled.
                                <? endif ?>
                            </div>
                        <? endif ?>
					</div>
				</div>
				<div id="w_stats_sct" class="hddn">
					<table style="width:88%;margin:0 0 0 61px" cellpadding="5">
						<tr>
							<td>Date: <strong><date><?= date("M d, Y",strtotime($_VIDEO->Info["uploaded_on"])) ?></date></strong></td>
							<td>Views: <strong><?= $Views ?></strong></td>
							<td>Ratings: <strong><?= number_format($Total_Ratings) ?></strong></td>
						</tr>
						<tr>
							<td>Time: <strong><time><?= date("h:i A",strtotime($_VIDEO->Info["uploaded_on"])) ?></time></strong></td>
							<td>Comments: <strong><?= $Comments_Num ?></strong></td>
							<td>Favorites: <strong><?= $_VIDEO->Info["favorites"] ?></strong></td>
						</tr>
					</table>
				</div>
			</div>
			<div style="width:468px;margin:8px auto">
			</div>
		</div>
	<? endif ?>
</div>
