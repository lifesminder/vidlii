<div class="cosmic_l cosmic_singleplane" style="width: 100%">
    <div class="cosmic_videos">
        <div>
            <? if ($Profile["c_videos"] && $Profile["videos"] > 0) : ?><a href="/user/<?= $Profile["displayname"] ?>/videos" <? if ($_GET["page"] == "videos") : ?>class="cselected"<? endif ?>>Uploaded Videos</a><? endif ?>
            <? if ($Profile["c_favorites"] && $Profile["favorites"] > 0) : ?><a href="/user/<?= $Profile["displayname"] ?>/favorites" <? if ($_GET["page"] == "favorites") : ?>class="cselected"<? endif ?>>Favorited Videos</a><? endif ?>
            <? if ($Profile["c_playlists"] && $Playlist_Amount > 0) : ?><a href="/user/<?= $Profile["displayname"] ?>/playlists" <? if ($_GET["page"] == "playlists") : ?>class="cselected"<? endif ?>>Playlists</a><? endif ?>
        </div>
        <div>
            <? if ($_GET["page"] == "videos") : ?>
            <div class="cosmic_video_header">
                Uploaded Videos <span>(<?= number_format($Profile["videos"]) ?>)</span>
            </div>
            <div class="cosmic_seperator"></div>
            <div class="cosmic_videos_list">
                <? if (isset($_POST["q"]) && count($Videos) == 0) : ?>
                    <span style="margin-top:60px;display:block;color: #808080;text-align: center;font-size:20px;">No videos under this search term were found!</span>

                <? endif ?>
                <? foreach ($Videos as $Video) : ?>
                    <div>
                        <div class="th">
                            <div class="th_t"><?= $Video["length"] ?></div>
                            <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="235" height="140"></a>
                        </div>
                        <a href="/watch?v=<?= $Video["url"] ?>" class="ctitle"><?= $Video["title"] ?></a>
                        <div><?= number_format($Video["views"]) ?> views <span><?= get_time_ago($Video["uploaded_on"]) ?></span></div>
                    </div>
                <? endforeach ?>
            </div>
            <div class="cl"></div>
            <? if ($Profile["videos"] > 21 && !isset($_POST["q"])) : ?>
            <div class="cosmic_pagination">
                <?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/videos",true) ?>
            </div>
            <? endif ?>
            <? elseif ($_GET["page"] == "favorites") : ?>
                <div class="cosmic_video_header">
                    Favorited Videos <span>(<?= number_format($Profile["favorites"]) ?>)</span>
                </div>
                <div class="cosmic_seperator"></div>
                <div class="cosmic_videos_list">
                    <? foreach ($Videos as $Video) : ?>
                        <div>
                            <div class="th">
                                <div class="th_t"><?= $Video["length"] ?></div>
                                <a href="/watch?v=<?= $Video["url"] ?>"><img class="vid_th" <?= $Video["thumbnail"] ?> width="235" height="140"></a>
                            </div>
                            <a href="/watch?v=<?= $Video["url"] ?>" class="ctitle"><?= $Video["title"] ?></a>
                            <div><?= number_format($Video["views"]) ?> views <span><?= get_time_ago($Video["date"]) ?></span></div>
                        </div>
                    <? endforeach ?>
                </div>
                <div class="cl"></div>
                <? if ($Profile["favorites"] > 21) : ?>
                <div class="cosmic_pagination">
                    <?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/favorites",true) ?>
                </div>
                <? endif ?>
            <? elseif ($_GET["page"] == "playlists") : ?>
                <div class="cosmic_video_header">
                    Playlists <span>(<?= number_format($Playlist_Amount) ?>)</span>
                </div>
                <div class="cosmic_seperator"></div>
                <div class="cosmic_playlists_list">
                    <? foreach ($Playlists as $Playlist) : ?>
                        <?
                        $Playlist_Videos        = $DB->execute("SELECT videos.url, videos.privacy FROM playlists_videos LEFT JOIN videos ON videos.url = playlists_videos.url WHERE playlists_videos.purl = :PURL ORDER BY playlists_videos.position", false, [":PURL" => $Playlist["purl"]]);
                        $Playlist_Videos_Amount = $DB->RowNum;
                        ?>
                        <a href="/user/<?= $Profile["displayname"] ?>/playlist/<?= $Playlist["purl"] ?>">
                            <div><?= $Playlist["title"] ?></div>
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
                                        <? if (file_exists("usfi/thmp/".$Playlist_Videos[0]["url"].".jpg") && $Playlist_Videos_Amount[$x]["privacy"] == 0) : ?><img style="width:100%" src="/usfi/thmp/<?= $Playlist_Videos[0]["url"] ?>.jpg"><? else : ?><img style="width:100%" src="/img/no_th.jpg"><? endif ?>
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
                <div class="cl"></div>
                <? if ($Playlist_Amount > 10) : ?>
                    <div class="cosmic_pagination">
                        <?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/playlists",true) ?>
                    </div>
                <? endif ?>
            <? endif ?>
        </div>
    </div>
</div>
