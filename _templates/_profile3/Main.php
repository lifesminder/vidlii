<div class="cosmic_l">
    <div>
        <? if (!isset($Nothing_To_Show)) : ?>
        <? if (($Profile["videos"] > 0 || $Profile["favorites"] > 0) and $Profile["c_featured"] && isset($Featured_Video["status"]) && $Featured_Video["status"] == 2) : ?>
            <div id="csm_v_plyr" style="width:653.5px; height:367.5px">
                <?php
                $URL        = $Featured_Video["url"];
                $FILENAME   = $Featured_Video["file"];
				$ISHD       = $Featured_Video["hd"] == 1 ? true : false;
                $Length     = $Featured_Video["length"];
                $Status     = $Featured_Video["status"];
                require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/player.php";
                ?>
            </div>
            <div class="cosmic_featured_video_info">
                <a href="/watch?v=<?= $Featured_Video["url"] ?>"><?= htmlspecialchars($Featured_Video["title"]) ?></a>
                <div>by <a href="/user/<?= $Featured_Video["displayname"] ?>"><?= $Featured_Video["displayname"] ?></a> <?= get_time_ago($Featured_Video["uploaded_on"]) ?></div>
                <? if (!$Is_OWNER) : ?>
                <div class="featured_views">
                    <span><?= number_format($Featured_Video["views"]) ?></span>
                    <span>views</span>
                </div>
                <? else : ?>
                    <div style="position: absolute;right:17px;top:15px">
                        <button type="button" class="cosmic_button" onclick="$('#csm_v_plyr').toggleClass('hddn');$('.cosmic_featured_video_info').toggleClass('hddn');$('#edit_csm_ft').toggleClass('hddn')">Edit</button>
                    </div>
                <? endif ?>
            </div>
        <? if ($Is_OWNER) : ?>
            <div id="edit_csm_ft" class="hddn" style="position:relative;background-color:#7696e6;padding:13px;padding-top:59px;font-size:14px;border-radius:5px;margin-bottom:8px;border:1px solid #4f87d4;color:white;font-weight:bold">
                <form action="/user/<?= $_USER->displayname ?>" method="POST">
                <div style="margin:0 auto;width:64%">
                    <div style="text-align:center;border-radius:3px;white-space:nowrap;background-color:white;padding:6px">
                        <input type="url" maxlength="128" autocomplete="off" name="n_url" value="<? if (!empty($Profile["featured_n_url"])) : ?>/watch?v=<?= $Profile["featured_n_url"] ?><? endif ?>" style="width:371px;padding:7px;background-color:#f5f5f5" placeholder="Video URL for Non-Subscribers">
                    </div>
                    <div style="text-align:center;border-radius:3px;margin-top:15px;white-space:nowrap;background-color:white;padding:6px">
                        <input type="url" maxlength="128" autocomplete="off" name="s_url" value="<? if (!empty($Profile["featured_s_url"])) : ?>/watch?v=<?= $Profile["featured_s_url"] ?><? endif ?>" style="width:371px;padding:7px;background-color:#f5f5f5" placeholder="Video URL for Subscribers">
                    </div>
                </div>
                <div style="position:absolute;top:10px;left:10px">
                    <button class="cosmic_button" type="button" onclick="$('#csm_v_plyr').toggleClass('hddn');$('.cosmic_featured_video_info').toggleClass('hddn');$('#edit_csm_ft').toggleClass('hddn')" style="margin-right:3px">Cancel</button><input class="cosmic_button" type="submit" name="save_featured" value="Save">
                </div>
                </form>
                <form action="/user/<?= $_USER->displayname ?>" method="POST" style="display:block;text-align:center;margin-top:13px">
                    <button type="submit" name="hide_featured" class="cosmic_button" style="width:175px">Hide Featured Video</button>
                </form>
            </div>
        <? endif ?>
        <? endif ?>
        <? if (!$Profile["c_featured"] && $Is_OWNER) : ?>
            <div style="background-color:#7696e6;padding:13px;font-size:14px;border-radius:5px;margin-bottom:8px;border:1px solid #4f87d4;color:white;font-weight:bold">
                Do you want to display a Featured Video on your channel?
                <form action="/user/<?= $_USER->displayname ?>" method="POST" style="display:inline">
                    <input type="submit" class="cosmic_button" name="show_featured" value="Show Featured Video" style="position: relative;left:49px;">
                </form>
            </div>
        <? endif ?>
        <? if ($Profile["videos"] > 0 && $Profile["c_videos"]) : ?>
        <div class="cosmic_featured_videos">
            <div<? if (!$Profile["c_featured"]) : ?> style="margin-top:13px"<? endif ?>>
                <div>Uploaded Videos</div>
                <span>1-<? if ($Profile["videos"] >= 10) : ?>10<? else : ?><?= $Profile["videos"] ?><? endif ?> of <?= $Profile["videos"] ?></span>
            </div>
            <? foreach ($Videos as $Video) : ?>
            <div class="cosmic_big_video">
                <a href="/watch?v=<?= $Video["url"] ?>">
                    <div style="display:inline-block;position: relative;width:295px"><div class="th_t"><?= $Video["length"] ?></div><img class="vid_th" <?= $Video["thumbnail"] ?> width="295px" height="155px"></div>
                    <div class="cosmic_big_video_info">
                        <div><?= $Video["title"] ?></div>
                        <div class="cosmic_big_small_stats"><span><?= $Profile["displayname"] ?></span><span style="color:#444"><?= $Video["views"] ?> views</span><span><?= get_time_ago($Video["uploaded_on"]) ?></span></div>
                        <div class="big_video_description"><? if (!empty($Video["description"])) { echo cut_string($Video["description"],128); } else { echo "<em>No Description...</em>"; } ?></div>
                    </div>
                </a>
            </div>
            <? endforeach ?>
            <? if ($Show_More) : ?>
            <button type="button" id="show_more" onclick="show_more('videos',1,'<?= $Profile["username"] ?>')" class="cosmic_button" style="width:100%">Load 10 more videos</button>
            <? endif ?>
        </div>
        <? elseif ($Profile["favorites"] > 0 && $Profile["c_favorites"]) : ?>
            <div class="cosmic_featured_videos">
                <div<? if (!$Profile["c_featured"]) : ?> style="margin-top:13px"<? endif ?>>
                    <div>Favorited Videos</div>
                    <span>1-<? if ($Profile["favorites"] >= 10) : ?>10<? else : ?><?= $Profile["favorites"] ?><? endif ?> of <?= $Profile["favorites"] ?></span>
                </div>
                <? foreach ($Favorites as $Video) : ?>
                    <div class="cosmic_big_video">
                        <a href="/watch?v=<?= $Video["url"] ?>">
                            <div style="display:inline-block;position: relative;width:295px"><div class="th_t"><?= $Video["length"] ?></div><img class="vid_th" <?= $Video["thumbnail"] ?> width="295px" height="155px"></div>
                            <div class="cosmic_big_video_info">
                                <div><?= $Video["title"] ?></div>
                                <div class="cosmic_big_small_stats"><span><?= $Video["displayname"] ?></span><span style="color:#444"><?= $Video["views"] ?> views</span><span><?= get_time_ago($Video["uploaded_on"]) ?></span></div>
                                <div class="big_video_description"><? if (!empty($Video["description"])) { echo cut_string($Video["description"],128); } else { echo "<em>No Description...</em>"; } ?></div>
                            </div>
                        </a>
                    </div>
                <? endforeach ?>
                <? if ($Show_More) : ?>
                    <button type="button" id="show_more" onclick="show_more('favorites',1,'<?= $Profile["username"] ?>')" class="cosmic_button" style="width:100%">Load 10 more videos</button>
                <? endif ?>
            </div>
        <? elseif ($Profile["c_playlists"] && $Playlist_Amount > 0 && isset($All_Playlists)) : ?>
                <div class="cosmic_featured_videos">
                    <div<? if (!$Profile["c_featured"]) : ?> style="margin-top:13px"<? endif ?>>
                        <div>Playlists</div>
                        <span>1-<? if ($Playlist_Amount >= 15) : ?>15<? else : ?><?= $Playlist_Amount ?><? endif ?> of <?= $Playlist_Amount ?></span>
                    </div>
                    <div class="cosmic_playlists_list" style="margin:0">
                    <? foreach ($All_Playlists as $Playlist) : ?>
                        <?
                        $Playlist_Videos        = $DB->execute("SELECT videos.url, videos.privacy FROM playlists_videos LEFT JOIN videos ON playlists_videos.url = videos.url WHERE playlists_videos.purl = :PURL ORDER BY playlists_videos.position", false, [":PURL" => $Playlist["purl"]]);
                        $Playlist_Videos_Amount = $DB->RowNum;
                        ?>
                        <a href="/user/<?= $Profile["displayname"] ?>/playlist/<?= $Playlist["purl"] ?>">
                            <div style="width:250px"><?= $Playlist["title"] ?></div>
                            <div>
                                <? if ($Playlist_Videos_Amount > 3) : ?>
                                    <? for ($x = 0;$x < 4;$x++) : ?><? if (file_exists("usfi/thmp/".$Playlist_Videos[$x]["url"].".jpg") && $Playlist_Videos_Amount[$x]["privacy"] == 0) : ?><img src="/usfi/thmp/<?= $Playlist_Videos[$x]["url"] ?>.jpg"><? else : ?><img src="/img/no_th.jpg"><? endif ?><? endfor ?>
                                <? else : ?>
                                    <? if ($Playlist_Videos_Amount == 3) : ?>
                                        <? for ($x = 0;$x < 3;$x++) : ?><? if (file_exists("usfi/thmp/".$Playlist_Videos[$x]["url"].".jpg") && $Playlist_Videos_Amount[$x]["privacy"] == 0) : ?><img style="width:33.3%" src="/usfi/thmp/<?= $Playlist_Videos[$x]["url"] ?>.jpg"><? else : ?><img style="width:33.3%" src="/img/no_th.jpg"><? endif ?><? endfor ?>
                                    <? endif ?>
                                    <? if ($Playlist_Videos_Amount == 2) : ?>
                                        <? for ($x = 0;$x < 2;$x++) : ?><? if (file_exists("usfi/thmp/".$Playlist_Videos[$x]["url"].".jpg") && $Playlist_Videos_Amount[$x]["privacy"] == 0) : ?><img style="width:50%" src="/usfi/thmp/<?= $Playlist_Videos[$x]["url"] ?>.jpg"><? else : ?><img style="width:50%" src="/img/no_th.jpg"><? endif ?><? endfor ?>
                                    <? endif ?>
                                    <? if ($Playlist_Videos_Amount == 1) : ?>
                                        <? if (file_exists("usfi/thmp/".$Playlist_Videos[0]["url"].".jpg") && $Playlist_Videos_Amount[$x]["privacy"] == 0) : ?><img style="width:100%" src="/usfi/thmp/<?= $Playlist_Videos[0]["url"] ?>.jpg"<? else : ?><img style="width:100%" src="/img/no_th.jpg"><? endif ?>
                                    <? endif ?>
                                    <? if ($Playlist_Videos_Amount == 0) : ?>
                                        <img style="width:100%" src="/img/no_th.jpg">
                                    <? endif ?>
                                <? endif ?>
                                <div><?= $Playlist_Videos_Amount ?> videos</div>
                            </div>
                        </a>
                    <? endforeach ?>
                    </div>
                </div>
        <? endif ?>
        <? else : ?>
        <div style="margin-top:51px;display:block;color: #808080;text-align: center;font-size:20px;">
            <?= $Profile["displayname"] ?> has nothing to show
        </div>
        <? endif ?>
    </div>
</div>
<div class="cosmic_d">
    <? require_once "right_side.php" ?>
</div>