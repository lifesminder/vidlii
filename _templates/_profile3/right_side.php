<div>
    <? if ($Is_OWNER) : ?>
        <button type="button" class="cosmic_button" onclick="$('#edit_about').toggleClass('hddn');$('.cosmic_more_about').toggleClass('hddn');$('.cosmic_about').toggleClass('hddn');$(this).toggleClass('hddn')" id="edit_about_button" style="position: absolute;right:28px;top:20px;height:25px">Edit</button>
    <? elseif ($_USER->logged_in && !$Is_Blocked && !$Has_Blocked) : ?>
        <a href="/inbox?page=send_message&to=<?= $Profile["displayname"] ?>"><button type="button" class="cosmic_button" style="position: absolute;right:25px;top:20px;height:21px">Message</button></a>
    <? endif ?>
    <div class="cosmic_about"<? if (!$Profile["a_country"] && !$Profile["a_last"]) : ?> style="border:0"<? endif ?>>
        <h3>About <?= $Profile["displayname"] ?></h3>
        <div>
            <? if (!empty($Profile["channel_description"])) { echo DoLinks(nl2br(htmlspecialchars($Profile["channel_description"]))); } else { echo "<em>No Description...</em>"; } ?>
        </div>
        <? if (!empty($Profile["website"])) : ?>
            <div class="cosmic_profile">
                <div>Website</div>
                <div><a href="<?= htmlspecialchars($Profile["website"]) ?>" rel="nofollow" target="_blank"><?= cut_string(htmlspecialchars($Profile["website"]), 35) ?></a></div>
            </div>
        <? endif ?>
    </div>
    <div class="cosmic_more_about">
        <? if (!empty($Profile["country"]) or $Profile["a_last"]) : ?>
            <? if ($Profile["a_last"]) : ?>
                <div class="cosmic_profile">
                    <div>Last Login</div>
                    <div><?= date("M d, Y",strtotime($Profile["last_login"])) ?></div>
                </div>
            <? endif ?>
            <? if (!empty($Profile["country"]) && $Profile["a_country"]) : ?>
                <div class="cosmic_profile">
                    <div>Country</div>
                    <div><?= $Countries[$Profile["country"]] ?></div>
                </div>
            <? endif ?>
        <? endif ?>
    </div>
	<? if (!$Is_OWNER && $_USER->logged_in) : ?>
    <div style="overflow:hidden;margin:<? if ((!empty($Profile["country"]) && $Profile["a_country"]) || ($Profile["a_last"])) : ?>20px<? else : ?>5px<? endif ?> 0 0 0">
        <? if ($Is_Blocked || $Has_Blocked) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" onclick="alert('You cannot interact with this user!')">Add Friend</button>
        <? elseif ($Is_Friends === false && $_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Add Friend</button>
        <? elseif ($Is_Friends === 2 && $_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Cancel Invite</button>
        <? elseif ($Is_Friends === true && $_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Unfriend</button>
        <? elseif ($Is_Friends === 3 && $_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Accept Invite</button>
        <? elseif (!$_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Add Friend</button>
        <? endif ?>
        <? if (!$Is_Blocked && !$Has_Blocked) : ?>
            <button class="cosmic_button" style="height:23px;width:46%;float:right" id="bu" onclick="block_user('<?= $Profile["username"] ?>')">Block User</button>
        <? elseif ($Has_Blocked) : ?>
            <button class="cosmic_button" style="height:23px;width:46%;float:right" id="bu" onclick="block_user('<?= $Profile["username"] ?>')">Unblock User</button>
        <? else : ?>
            <button class="cosmic_button" style="height:23px;width:46%;float:right">You are blocked!</button>
        <? endif ?>
    </div>
    <? endif ?>
    <? if ($Is_OWNER) : ?>
    <div id="edit_about" class="hddn">
        <form action="/user/<?= $_USER->displayname ?><? if (!empty($_GET["page"])) : ?>/<? if ($Profile["c_recent"]) : ?>feed<? else : ?>comments<? endif ?><? endif ?>" method="POST">
        <button type="button" class="cosmic_button" onclick="$('#edit_about').toggleClass('hddn');$('.cosmic_more_about').toggleClass('hddn');$('.cosmic_about').toggleClass('hddn');$('#edit_about_button').toggleClass('hddn')" style="margin-right: 5px">Cancel</button><input type="submit" name="cosmic_save_about" class="cosmic_button" value="Apply">
        <h3 style="padding: 0;color: #000;font-weight: normal;font-size: 14px;margin: 8px 0;">About <?= $Profile["displayname"] ?></h3>
        <textarea name="cosmic_description" maxlength="2500" style="width:98%;resize:vertical" rows="6" placeholder="Edit your description"><?= htmlspecialchars($Profile["channel_description"]) ?></textarea>
        <table cellpadding="6" style="position:relative;right:8px;font-size: 14px">
            <tr>
                <td valign="middle"><label>Website</label></td>
                <td><input type="url" name="cosmic_website" style="width:146px" maxlength="128" style="position:relative;left:4px"<? if (!empty($Profile["website"])) : ?> value="<?= $Profile["website"] ?>" <? endif ?> placeholder="https://..."></td>
            </tr>
            <tr>
                <td valign="middle"><label>Last Login</td>
                <td valign="middle"><input name="cosmic_last" type="checkbox"<? if ($Profile["a_last"]) : ?> checked<? endif ?>></td>
            </tr>
            <tr>
                <td valign="middle"><label>Show Country</td>
                <td valign="middle"><input name="cosmic_show_country" type="checkbox"<? if ($Profile["a_country"]) : ?> checked<? endif ?>></td>
            </tr>
            <tr>
                <td valign="middle"><label>Country</td>
                <td valign="middle">
                    <select name="cosmic_country" style="width:139px;position: relative;left:4px">
                        <? foreach ($Countries as $Country => $Name) : ?>
                            <option value="<?= $Country ?>"<? if ($Country == $Profile["country"]) : ?>selected<? endif ?>><?= $Name ?></option>
                        <? endforeach ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    </form>
    <? endif ?>
    <? if (!empty($Profile["playlists"]) || $Is_OWNER) : ?>
        <div class="cosmic_featured_playlists">
            <h3>Featured Playlists</h3>
            <? if (!empty($Profile["playlists"])) : ?>
            <? foreach ($Playlists as $Playlist) : ?>
                <?
                $Playlist_Videos        = $DB->execute("SELECT videos.url, videos.privacy FROM playlists_videos LEFT JOIN videos ON playlists_videos.url = videos.url WHERE playlists_videos.purl = :PURL ORDER BY playlists_videos.position", false, [":PURL" => $Playlist["purl"]]);
                $Playlist_Videos_Amount = $DB->RowNum;
                ?>
                <a href="/user/<?= $Profile["displayname"] ?>/playlist/<?= $Playlist["purl"] ?>">
                    <div>
                        <? if ($Playlist_Videos_Amount > 3) : ?>
                            <? for ($x = 0;$x < 4;$x++) : ?><? if (file_exists("usfi/thmp/".$Playlist_Videos[$x]["url"].".jpg") && $Playlist_Videos[$x]["privacy"] == 0) : ?><img src="/usfi/thmp/<?= $Playlist_Videos[$x]["url"] ?>.jpg"><? else : ?><img src="/img/no_th.jpg"><? endif ?><? endfor ?>
                        <? else : ?>
                            <? if ($Playlist_Videos_Amount == 3) : ?>
                                <? for ($x = 0;$x < 3;$x++) : ?><? if (file_exists("usfi/thmp/".$Playlist_Videos[$x]["url"].".jpg") && $Playlist_Videos[$x]["privacy"] == 0) : ?><img style="width:33.3%" src="/usfi/thmp/<?= $Playlist_Videos[$x]["url"] ?>.jpg"><? else : ?><img style="width:33.3%" src="/img/no_th.jpg"><? endif ?><? endfor ?>
                            <? endif ?>
                            <? if ($Playlist_Videos_Amount == 2) : ?>
                                <? for ($x = 0;$x < 2;$x++) : ?><? if (file_exists("usfi/thmp/".$Playlist_Videos[$x]["url"].".jpg") && $Playlist_Videos[$x]["privacy"] == 0) : ?><img style="width:50%" src="/usfi/thmp/<?= $Playlist_Videos[$x]["url"] ?>.jpg"><? else : ?><img style="width:50%" src="/img/no_th.jpg"><? endif ?><? endfor ?>
                            <? endif ?>
                            <? if ($Playlist_Videos_Amount == 1) : ?>
                                <? if (file_exists("usfi/thmp/".$Playlist_Videos[0]["url"].".jpg") && $Playlist_Videos[$x]["privacy"] == 0) : ?><img style="width:100%" src="/usfi/thmp/<?= $Playlist_Videos[0]["url"] ?>.jpg"><? else : ?><img style="width:100%" src="/img/no_th.jpg"><? endif ?>
                            <? endif ?>
                            <? if ($Playlist_Videos_Amount == 0) : ?>
                                <img style="width:100%" src="/img/no_th.jpg">
                            <? endif ?>
                        <? endif ?>
                        <div><?= $Playlist_Videos_Amount ?> videos</div>
                    </div>
                    <div>
                        <div><?= $Playlist["title"] ?></div>
                        <div>by <?= $Profile["displayname"] ?></div>
                    </div>
                </a>
            <? endforeach ?>
            <? endif ?>
            <? if ($Is_OWNER) : ?>
                <button class="cosmic_button" id="edit_playlists" style="position:absolute;top:-2px;right:0;height:20px" onclick="$(this).toggleClass('hddn');$('.cosmic_featured_playlists > a').toggleClass('hddn');$('#playlists_add').toggleClass('hddn')">Edit</button>
                <div id="playlists_add" class="hddn" style="background-color:#7696e6;padding:52px 13px 13px 13px;font-size:14px;border-radius:5px;margin-top:5px;margin-bottom:25px;position:relative;border:1px solid #4f87d4;color:white;font-weight:bold">
                    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" style="position:absolute;top:12px;left:12px">
                        <input type="hidden" id="playlists_small_input" value="<?= $Profile["playlists"] ?>," name="playlists">
                        <button type="button" class="cosmic_button" onclick="$('#playlists_add').toggleClass('hddn');$('#edit_playlists').toggleClass('hddn');$('.cosmic_featured_playlists > a').toggleClass('hddn')">Cancel</button> <input type="submit" class="cosmic_button" name="save_playlists" value="Save">
                    </form>
                    <div style="border-radius:3px;background-color:white;padding:6px">
                        <select id="select_playlist" style="background: #f5f5f5;padding:6px;width:166px">
                            <? if (count($Playlists_Select) > 0) : ?>
                            <? foreach ($Playlists_Select as $Select) : ?>
                                <option value="<?= $Select["purl"] ?>"><?= $Select["title"] ?></option>
                            <? endforeach ?>
                            <? else : ?>
                                <option>No Playlists...</option>
                            <? endif ?>
                        </select>
                        <button onclick="<? if (count($Playlists_Select) > 0) : ?>cosmic_add_playlist()<? else : ?>alert('You have no playlists to add!')<? endif ?>" type="button" class="cosmic_button" style="height:31px;position:relative;left:12px;bottom:0.9px">Add</button>
                    </div>
                    <div id="featured_playlists_small">
                        <? if (!empty($Profile["playlists"])) : ?>
                            <? foreach ($Playlists as $Playlist) : ?>
                                <div id="pl_<?= $Playlist["purl"] ?>" style="overflow:hidden;background:white;border-radius:3px;padding:4px;margin-top:11px">
                                    <div style="float:left;color:black;font-weight:bold"><?= $Playlist["title"] ?></div>
                                    <div style="float:right"><a href="javascript:void(0)" onclick="cosmic_remove_pl('<?= $Playlist["purl"] ?>')">Remove</a></div>
                                </div>
                            <? endforeach ?>
                        <? endif ?>
                    </div>
                </div>
            <? endif ?>
        </div>
    <? endif ?>
    <? if ($Profile["c_featured_channels"] == "1" && (!empty($Profile["featured_channels"]) || $Is_OWNER)) : ?>
        <div class="cosmic_featured_channels">
            <h3><? if (empty($Profile["featured_title"])) : ?>Featured Channels<? else : ?><?= $Profile["featured_title"] ?><? endif ?></h3>
            <? if (!empty($Profile["featured_channels"])) : ?>
            <? foreach ($Featured_Channels as $Featured_Channel) : ?>
                <a href="/user/<?= $Featured_Channel["displayname"] ?>">
                    <div>
                        <?= no_link_avatar($Featured_Channel["displayname"],46,46,$Featured_Channel["avatar"]) ?>
                    </div>
                    <div>
                        <div><?= $Featured_Channel["displayname"] ?>'s Channel</div>
                        <div><?= number_format($Featured_Channel["subscribers"]) ?> subscribers</div>
                    </div>
                </a>
            <? endforeach ?>
            <? endif ?>
            <? if ($Is_OWNER) : ?>
                <button class="cosmic_button" id="edit_featured" style="position:absolute;top:-2px;right:0;height:20px" onclick="$(this).toggleClass('hddn');$('.cosmic_featured_channels > a').toggleClass('hddn');$('#featured_users_add').toggleClass('hddn')">Edit</button>
                <div id="featured_users_add" class="hddn" style="background-color:#7696e6;padding:91px 13px 13px 13px;font-size:14px;border-radius:5px;margin-top:5px;margin-bottom:25px;position:relative;border:1px solid #4f87d4;color:white;font-weight:bold">
                    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="POST" style="position:absolute;top:12px;left:12px">
                        <input type="hidden" id="featured_channels_small_input" value="<?= $Profile["featured_channels"] ?>," name="featured_channels">
                        <button type="button" class="cosmic_button" onclick="$('#featured_users_add').toggleClass('hddn');$('#edit_featured').toggleClass('hddn');$('.cosmic_featured_channels > a').toggleClass('hddn')">Cancel</button> <input type="submit" class="cosmic_button" name="save_featured_channels" value="Save">
                        <div><input type="text" maxlength="64" name="featured_title" style="padding:7px;width:238px;margin-top:7px" placeholder="Featured Channels" value="<? if (empty($Profile["featured_title"])) : ?>Featured Channels<? else : ?><?= $Profile["featured_title"] ?><? endif ?>"></div>
                    </form>
                    <div style="border-radius:3px;background-color:white;padding:6px">
                        <input type="text" maxlength="64" style="padding:7px;background-color:#f5f5f5" placeholder="Channel Name" id="ft_channel_name"><button onclick="cosmic_add_channel()" type="button" class="cosmic_button" style="height:31px;position:relative;left:12px;bottom:0.9px">Add</button>
                    </div>
                    <div id="featured_channels_small">
                        <? if (!empty($Profile["featured_channels"])) : ?>
                            <? foreach ($Featured_Channels as $Featured_Channel) : ?>
                                <div id="fc_<?= $Featured_Channel["username"] ?>" style="overflow:hidden;background:white;border-radius:3px;padding:4px;margin-top:11px">
                                    <div style="float:left;color:black;font-weight:bold"><?= $Featured_Channel["displayname"] ?></div>
                                    <div style="float:right"><a href="javascript:void(0)" onclick="cosmic_remove_ft('<?= $Featured_Channel["username"] ?>')">Remove</a></div>
                                </div>
                            <? endforeach ?>
                        <? endif ?>
                    </div>
                </div>
            <? endif ?>
        </div>
    <? endif ?>
</div>
