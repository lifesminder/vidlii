<div class="pr_spacer"></div>
<div style="display:none" id="ch_user"><?= $Profile["username"] ?></div>
<div style="display:none" id="ch_displayname"><?= $Profile["displayname"] ?></div>
<div class="out_box ob_col">
    <div class="in_box ib_col">
        <div class="pr_tp_hd">
            <? if ($Showcase) : ?>
            <div class="pr_pl_toggles">
                <a href="javascript:void(0)" title="Switch to Player View" class="pl_toggler" <? if ($Profile["default_view"] == 0) : ?>id="pl_toggle_sel"<? endif ?>>
                    <em></em>
                    <i><b></b><b></b><b></b></i>
                </a>
                <a href="javascript:void(0)" class="pl_toggler" title="Switch to Grid View" <? if ($Profile["default_view"] == 1) : ?>id="pl_toggle_sel"<? endif ?>>
                    <i><b></b><b></b><b></b></i>
                    <i><b></b><b></b><b></b></i>
                    <i><b></b><b></b><b></b></i>
                </a>
            </div>
            <? endif ?>
            <div class="pr_pl_title ob_col">
                <?= user_avatar2($Profile["displayname"],36,36,$Profile["avatar"],"ob_img") ?>
                <? if (empty($Profile["channel_title"])) : ?>
                <div class="pr_pl_title_name">
                    <?= $Profile["displayname"] ?>'s Channel
                </div>
                <? else : ?>
                <div class="pr_pl_title_custom">
                    <div><?= $Profile["channel_title"] ?></div>
                    <div><?= $Profile["displayname"] ?>'s Channel</div>
                </div>
                <? endif ?>
                <div class="pr_pl_title_sub">
                    <? if (!$Is_Blocked && !$Has_Blocked) : ?>
                        <?= subscribe_button2($Profile["username"]) ?>
                    <? else : ?>
                        <?= subscribe_button2($Profile["username"], true) ?>
                    <? endif ?>
                </div>
            </div>
            <div class="pr_pl_title_sty">
                &nbsp;
            </div>
            <div class="pr_pl_nav">
                <? if ($Showcase) : ?>
                <? if (($Shows_Videos && $Shows_Favorites && $Profile["c_all"]) || ($Shows_Videos && $Shows_Playlists && $Profile["c_all"]) || ($Shows_Playlists && $Shows_Favorites && $Profile["c_all"])) : ?><a href="javascript:void(0)" class="pl_nav_sel_hd" id="pr_all">All</a><? endif ?>
                <? if ($Shows_Videos) : ?><a href="javascript:void(0)" <? if ((!$Shows_Favorites && !$Shows_Playlists) || !$Profile["c_all"]) : ?> class="pl_nav_sel_hd"<? endif ?> id="pr_uploads">Uploads</a><? endif ?>
                <? if ($Shows_Favorites) : ?><a href="javascript:void(0)" <? if ((!$Shows_Videos && !$Shows_Playlists) || ($Shows_Playlists && !$Shows_Videos && !$Profile["c_all"])) : ?> class="pl_nav_sel_hd"<? endif ?> id="pr_favorites">Favorites</a><? endif ?>
                <? if ($Shows_Playlists) : ?><a href="javascript:void(0)" <? if (!$Shows_Videos && !$Shows_Favorites) : ?> class="pl_nav_sel_hd"<? endif ?> id="pr_playlists">Playlists</a><? endif ?>
                <? else : ?>
                    <div style="position: relative;font-size: 14px; top: 15px;margin-left:15px">
                        <?= $Profile["displayname"] ?> has no videos available.
                    </div>
                <? endif ?>
            </div>
        </div>
    </div>
    <? if ($Showcase) : ?>
    <div class="pr_tp_btm<? if ($Profile["default_view"] == 1) : ?> grid<? endif ?>">
        <div class="pr_tp_pl">
			<div style="width:640px; height:360px;">
				<?php
					$URL = $Featured_Video["url"];
					$FILENAME = $Featured_Video["file"];
					$ISHD = $Featured_Video["hd"] == 1 ? true : false;
					if (isset($Featured_Video["seconds"])) { $Length = $Featured_Video["seconds"]; } else { $Length = $Featured_Video["length"]; }
					$Status = 2;
					require_once $_SERVER['DOCUMENT_ROOT']."/_templates/_layout/player.php";				
				?>
			</div>
            <div style="display:none" id="pl_url"><?= $Featured_Video["url"] ?></div>
            <div class="pr_tp_pl_nav">
                <a href="javascript:void(0)" id="pl_inf_i"><img src="/img/smi/1.png">Info</a><a href="javascript:void(0)" id="pl_inf_f"><img src="/img/smi/2.png">Favorite</a><a href="javascript:void(0)" id="pl_inf_s"><img src="/img/smi/3.png">Share</a><a href="javascript:void(0)" id="pl_inf_p"><img src="/img/smi/4.png">Playlists</a><a href="javascript:void(0)" id="pl_inf_fl"><img src="/img/smi/5.png">Flag</a>
                <div id="nav_ind"></div>
            </div>
            <div class="pr_tp_pl_inf ib_col" id="pl_inf" <? if ($Player == 0) : ?>style="height:150px"<? elseif($Player == 2) : ?>style="height:143px"<? endif ?>>

            </div>
            <? if ($Player == 1) : ?>
            <style>
                .pr_pl_descr {
                    height: 96px;
                }
            </style>
            <? endif ?>
        </div>
        <div class="pr_pl_mnu ib_col" id="pl_list">
            <? if ($Shows_Videos) : ?>
                <div class="mnu_sct"<? if ((!$Shows_Favorites && !$Shows_Playlists) || !$Profile["c_all"]) : ?>style="border:0"<? endif ?>>
                    <div>Uploads (<?= $Profile["videos"] ?>)</div>
                    <? foreach ($Videos as $Video) : ?>
                        <div class="mnu_vid" <? if ($Video["url"] == $Featured_Video["url"] && $Video_Selected == false) : ?>id="v_sel"<? $Video_Selected = true ?><? endif ?> watch="<?= $Video["url"] ?>">
                            <a href="javascript:void(0)"><img <?= $Video["thumbnail"] ?> width="100" height="75"></a>
                            <div>
                                <a href="javascript:void(0)"><?= $Video["title"] ?></a>
                                <span><a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a> - <?= number_format($Video["views"]) ?> views</span>
                            </div>
                        </div>
                    <? endforeach ?>
                    <? if ((!$Shows_Favorites && !$Shows_Playlists) || !$Profile["c_all"]) : ?><?= $Show_More ?><? endif ?>
                </div>
            <? endif ?>
            <? if (($Profile["c_all"] && $Shows_Favorites) || (!$Shows_Videos && $Shows_Favorites)) : ?>
                    <div class="mnu_sct" style="<? if (!$Shows_Playlists || !$Profile["c_all"]) : ?>border:0<? if (!$Shows_Favorites || !$Profile["c_all"] || ($Profile["c_all"] && !$Profile["c_videos"] && !$Profile["c_playlists"])) : ?><? else : ?>;margin:0<? endif ?><? endif ?>">
                    <div>Favorites (<?= $Profile["favorites"] ?>)</div>
                <? foreach ($Favorites as $Video) : ?>
                    <div class="mnu_vid" <? if ($Video["url"] == $Featured_Video["url"] && $Video_Selected == false) : ?>id="v_sel"<? $Video_Selected = true ?><? endif ?> watch="<?= $Video["url"] ?>">
                        <a href="javascript:void(0)"><img <?= $Video["thumbnail"] ?> width="100" height="75"></a>
                        <div>
                            <a href="javascript:void(0)"><?= $Video["title"] ?></a>
                            <span><a href="/user/<?= $Video["displayname"] ?>"><?= $Video["displayname"] ?></a> - <?= $Video["views"] ?> views</span>
                        </div>
                    </div>
                <? endforeach ?>
                <? if (!$Shows_Favorites || !$Profile["c_all"] || ($Profile["c_all"] && !$Profile["c_videos"] && !$Profile["c_playlists"])) : ?><?= $Show_More ?><? endif ?>
                </div>
            <? endif ?>
            <? if (($Profile["c_all"] && $Shows_Playlists) || (!$Shows_Videos && !$Shows_Favorites && $Shows_Playlists)) : ?>
            <div class="mnu_sct" style="border:0;margin:0;">
                <div>Playlists (<?= $Playlist_Amount ?>)</div>
                <? foreach ($Playlists as $Playlist) : ?>
                    <div class="mnu_vid" pl="<?= $Playlist["purl"] ?>" style="padding-bottom:0">
                        <div class="playlist" style="float:left;margin-right:8px;width:125px;position:relative;top:2px">
                            <img src="/usfi/thmp/<?= $Playlist["thumbnail"] ?>.jpg" style="top:1.5px;width:117px;height:69px">
                        </div>
                        <div>
                            <a href="javascript:void(0)"><?= $Playlist["title"] ?></a>
                            <span><a href="/user/<?= $Playlist["displayname"] ?>"><?= $Playlist["displayname"] ?></a></span>
                        </div>
                    </div>
                <? endforeach ?>
            </div>
            <? endif ?>
        </div>
    </div>
    <? elseif ($Is_OWNER) : ?>
    <div style="margin: 13px 0 9px 3px;font-size:20px">Welcome to your VidLii channel!</div>
    <div style="margin-left:5px">You can:</div>
        <ul style="margin:2px 0 5px 20px;padding:0;font-size: 15px;">
            <li>Upload videos, favorite others' videos, or group videos into playlists -- they'll all show up here for your channel viewers to watch and enjoy.</li>
            <li>Customize the look and feel of your channel by clicking the "Edit Channel" buttons at the top of the page or "edit" links thoughout the channel.</li>
        </ul>
    <? endif ?>

</div>
    <div style="clear:both;height:10px"></div>
<div class="out_box ob_col" id="btm_pr">
    <div class="pr_btm_l">
        <div class="in_box ib_col" id="pr_avt_box">
            <?= user_avatar2($Profile["displayname"],96,96,$Profile["avatar"],"pr_avt") ?>
            <div>
                <?= $Profile["displayname"] ?><br>
                <? if (!$Is_OWNER) : ?>
                    <? if (!$Is_Blocked && !$Has_Blocked) : ?>
                        <?= subscribe_button2($Profile["username"]) ?>
                    <? else : ?>
                        <?= subscribe_button2($Profile["username"], true) ?>
                    <? endif ?><br>
                <div>
                    <? if (!$_USER->logged_in) : ?>
                        <a href="/login">Add as Friend</a>
                    <? elseif ($Is_Friends === false && $_USER->Is_Activated && !$Is_Blocked && !$Has_Blocked) : ?>
                        <a href="javascript:void(0)" id="aaf">Add as Friend</a>
                    <? elseif ($Is_Blocked || $Has_Blocked) : ?>
                        <a href="javascript:void(0)" onclick="alert('You cannot interact with this user!')">Add as Friend</a>
                    <? elseif ($Is_Friends === 2 && $_USER->Is_Activated) : ?>
                        <a href="javascript:void(0)" id="aaf">Cancel Invite</a>
                    <? elseif ($Is_Friends === true && $_USER->Is_Activated) : ?>
                        <a href="javascript:void(0)" id="aaf">Unfriend</a>
                    <? elseif ($Is_Friends === 3 && $_USER->Is_Activated) : ?>
                        <a href="javascript:void(0)" id="aaf">Accept Invite</a>
                    <? elseif (!$_USER->Is_Activated) : ?>
                        <a href="javascript:void(0)" id="aaf" onclick="alert('You must activate your account with the email we sent you to add <?= $Profile["displayname"] ?> as a friend!')">Add as Friend</a>
                    <? endif ?><br>
                    <? if (!$Has_Blocked && !$Is_Blocked && $_USER->logged_in) : ?>
                        <a href="javascript:void(0)" onclick="block_user('<?= $Profile['username'] ?>')" id="bu">Block User</a><br>
                    <? elseif ($Has_Blocked) : ?>
                        <a href="javascript:void(0)" onclick="block_user('<?= $Profile['username'] ?>')" id="bu">Unblock User</a><br>
                    <? elseif ($Is_Blocked) : ?>
                        <a href="javascript:void(0)" id="bu">You're Blocked</a><br>
                    <? else : ?>
                        <a href="javascript:void(0)" onclick="alert('Please log in to block <?= $Profile["displayname"] ?>!')" id="bu">Block User</a><br>
                    <? endif ?>
                <? if ($_USER->logged_in) : ?><a href="/inbox?page=send_message&to=<?= $Profile["displayname"] ?>">Send Message</a><? else : ?><a href="javascript:void(0)" onclick="alert('Please log in to send <?= $Profile["displayname"] ?> a message!')">Send Message</a><? endif ?>
                </div>
                <? else : ?>
                <div style="opacity:0.6;position:relative;white-space: normal;bottom:2px;font-size:12.5px;line-height:16px">Your channel viewers will see links here, including "subscribe" and "add as friend".</div>
                <? endif ?>
            </div>
        </div>
        <div class="in_box ib_col" id="ch_info">
            <div class="box_title">
                Profile
            </div>
            <div id="ch_info_sct">
                <? if (!empty($Profile["i_name"] && $Profile["a_name"])) : ?>
                <div class="pr_inf_sct">
                    <div>Name:</div>
                    <div><?= $Profile["i_name"] ?></div>
                </div>
                <? endif ?>
                <div class="pr_inf_sct">
                    <div>Channel Views:</div>
                    <div><?= number_format($Profile["channel_views"]) ?></div>
                </div>
                <? if ($Profile["videos"] > 0) : ?>
                <div class="pr_inf_sct">
                    <div>Total Upload Views:</div>
                    <div><?= number_format($Profile["video_views"]) ?></div>
                </div>
                <? endif ?>
                <? if ($Profile["a_age"]) : ?>
                <div class="pr_inf_sct">
                    <div>Age:</div>
                    <div><?= get_age($Profile["birthday"]) ?></div>
                </div>
                <? endif ?>
                <div class="pr_inf_sct">
                    <div>Joined:</div>
                    <div><?= date("M d, Y",strtotime($Profile["reg_date"])) ?></div>
                </div>
                <? if ($Profile["a_last"]) : ?>
                <div class="pr_inf_sct">
                    <div>Last Visit Date:</div>
                    <div><?= get_time_ago($Profile["last_login"]) ?></div>
                </div>
                <? endif ?>
                <? if ($Profile["a_subs"]) : ?>
                <div class="pr_inf_sct">
                    <div>Subscribers:</div>
                    <div><?= number_format($Profile["subscribers"]) ?></div>
                </div>
                <? endif ?>
                <? if ($Profile["a_subs2"]) : ?>
                    <div class="pr_inf_sct">
                        <div>Subscriptions:</div>
                        <div><?= number_format($Profile["subscriptions"]) ?></div>
                    </div>
                <? endif ?>
                <? if (!empty($Profile["website"] && $Profile["a_website"])) : ?>
                <div class="pr_inf_sct">
                    <div>Website:</div>
                    <div><a rel="nofollow" href="<?= htmlspecialchars($Profile["website"]) ?>"><?= cut_string(htmlspecialchars($Profile["website"]), 31) ?></a></div>
                </div>
                <? endif ?>
                <? if (!empty($Profile["channel_description"] && $Profile["a_description"])) : ?>
                <div class="pr_inf_sct">
                    <div style="display:none"></div>
                    <div style="float:none"><?= DoLinks(nl2br(htmlspecialchars($Profile["channel_description"]))) ?></div>
                </div>
                <? endif ?>
                <? if (!empty($Profile["country"] && $Profile["a_country"])) : ?>
                    <div class="pr_inf_sct">
                        <div>Country:</div>
                        <div><?= $Countries[$Profile["country"]] ?></div>
                    </div>
                <? endif ?>
                <? if (!empty($Profile["i_occupation"] && $Profile["a_occupation"])) : ?>
                <div class="pr_inf_sct">
                    <div>Occupation:</div>
                    <div><?= htmlspecialchars($Profile["i_occupation"]) ?></div>
                </div>
                <? endif ?>
                <? if (!empty($Profile["i_schools"] && $Profile["a_schools"])) : ?>
                <div class="pr_inf_sct">
                    <div>Schools:</div>
                    <div><?= htmlspecialchars($Profile["i_schools"]) ?></div>
                </div>
                <? endif ?>
                <? if (!empty($Profile["i_interests"] && $Profile["a_interests"])) : ?>
                <div class="pr_inf_sct">
                    <div>Interests:</div>
                    <div><?= htmlspecialchars($Profile["i_interests"]) ?></div>
                </div>
                <? endif ?>
                <? if (!empty($Profile["i_movies"] && $Profile["a_movies"])) : ?>
                <div class="pr_inf_sct">
                    <div>Movies:</div>
                    <div><?= htmlspecialchars($Profile["i_movies"]) ?></div>
                </div>
                <? endif ?>
                <? if (!empty($Profile["i_music"] && $Profile["a_music"])) : ?>
                <div class="pr_inf_sct">
                    <div>Music:</div>
                    <div><?= htmlspecialchars($Profile["i_music"]) ?></div>
                </div>
                <? endif ?>
                <? if (!empty($Profile["i_books"] && $Profile["a_books"])) : ?>
                <div class="pr_inf_sct">
                    <div>Books:</div>
                    <div><?= htmlspecialchars($Profile["i_books"]) ?></div>
                </div>
                <? endif ?>
                <? if (count($Awards) > 0) : ?>
                    <div class="awards" style="margin: 17px 0 0">
                        <div>
                            <img src="/img/awards.png" title="<?= $Profile["displayname"] ?>s Awards">
                        </div>
                        <div style="width:250px">
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
            <? if ($Is_OWNER) : ?>
            <div id="ch_edit_info" class="ch_edit_info" style="display:none">
                <div><button type="button" onclick="save_information()">Save Changes</button> <span>or <a href="javascript:void(0)" onclick="edit_channel_info()">cancel</a></span></div>
                <table class="ch_info_inputs" cellpadding="6">
                    <tr>
                        <td align="left" valign="middle"><label><input id="check_age" type="checkbox" class="info_toggle" <? if ($Profile["a_age"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_age"]) : ?>class="opa"<? endif ?> style="font-size:13px">Age:</span></label></td>
                        <td align="right"><span <? if (!$Profile["a_age"]) : ?>class="opa"<? endif ?>><?= get_age($Profile["birthday"]) ?></span></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input id="check_last" type="checkbox" class="info_toggle" <? if ($Profile["a_last"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_last"]) : ?>class="opa"<? endif ?> style="font-size:13px">Last Visit:</span></label></td>
                        <td align="right"><span <? if (!$Profile["a_last"]) : ?>class="opa"<? endif ?>><?= get_time_ago($Profile["last_login"]) ?></span></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input id="check_subs" type="checkbox" class="info_toggle" <? if ($Profile["a_subs"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_subs"]) : ?>class="opa"<? endif ?> style="font-size:13px">Subscribers:</span></label></td>
                        <td align="right"><span <? if (!$Profile["a_subs"]) : ?>class="opa"<? endif ?>><?= $Profile["subscribers"] ?></span></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label style="position:relative"><input id="check_subs2" type="checkbox" class="info_toggle" <? if ($Profile["a_subs2"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_subs2"]) : ?>class="opa"<? endif ?> style="font-size:13px;position:absolute;left:18px;top:0px">Subscriptions:</span></label></td>
                        <td align="right"><span <? if (!$Profile["a_subs2"]) : ?>class="opa"<? endif ?>><?= $Profile["subscriptions"] ?></span></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input id="check_name" type="checkbox" class="info_toggle" <? if ($Profile["a_name"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_name"]) : ?>class="opa"<? endif ?>>Name:</span></label></td>
                        <td align="right"><input maxlength="64" <? if (!$Profile["a_name"]) : ?>class="opa"<? endif ?> id="name_value" type="text"<? if ($Profile["i_name"] !== "") : ?> value="<?= $Profile["i_name"] ?>"<? endif ?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input type="checkbox" id="check_website" class="info_toggle" <? if ($Profile["a_website"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_website"]) : ?>class="opa"<? endif ?>>Website:</span></label></td>
                        <td align="right"><input maxlength="128" <? if (!$Profile["a_website"]) : ?>class="opa"<? endif ?> id="website_value" type="text"<? if ($Profile["website"] !== "") : ?> value="<?= $Profile["website"] ?>"<? endif ?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="top"><label><input type="checkbox" id="check_description" class="info_toggle" <? if ($Profile["a_description"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_description"]) : ?>class="opa"<? endif ?>>Description:</span></label></td>
                        <td align="right"><textarea maxlength="2500" <? if (!$Profile["a_description"]) : ?>class="opa"<? endif ?> id="description_value" rows="5"><?= $Profile["channel_description"] ?></textarea></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input type="checkbox" id="check_country" class="info_toggle" <? if ($Profile["a_country"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_country"]) : ?>class="opa"<? endif ?>>Country:</span></label></td>
                        <td align="right">
                            <select id="country" style="width:139px" <? if (!$Profile["a_country"]) : ?>class="opa"<? endif ?>>
                                <? foreach ($Countries as $Country => $Name) : ?>
                                    <option value="<?= $Country ?>"<? if ($Country == $Profile["country"]) : ?>selected<? endif ?>><?= $Name ?></option>
                                <? endforeach ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input type="checkbox" id="check_occupation" class="info_toggle" <? if ($Profile["a_occupation"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_occupation"]) : ?>class="opa"<? endif ?>>Occupation:</span></label></td>
                        <td align="right"><input maxlength="128" <? if (!$Profile["a_occupation"]) : ?>class="opa"<? endif ?> id="occupation_value" type="text"<? if ($Profile["i_occupation"] !== "") : ?> value="<?= $Profile["i_occupation"] ?>"<? endif ?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input type="checkbox" id="check_schools" class="info_toggle" <? if ($Profile["a_schools"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_schools"]) : ?>class="opa"<? endif ?>>Schools:</span></label></td>
                        <td align="right"><input maxlength="128" <? if (!$Profile["a_schools"]) : ?>class="opa"<? endif ?> id="schools_value" type="text"<? if ($Profile["i_schools"] !== "") : ?> value="<?= $Profile["i_schools"] ?>"<? endif ?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input type="checkbox" id="check_interests" class="info_toggle" <? if ($Profile["a_interests"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_interests"]) : ?>class="opa"<? endif ?>>Interests:</span></label></td>
                        <td align="right"><input maxlength="128" <? if (!$Profile["a_interests"]) : ?>class="opa"<? endif ?> id="interests_value" type="text"<? if ($Profile["i_interests"] !== "") : ?> value="<?= $Profile["i_interests"] ?>"<? endif ?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input type="checkbox" id="check_movies" class="info_toggle" <? if ($Profile["a_movies"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_movies"]) : ?>class="opa"<? endif ?>>Movies:</span></label></td>
                        <td align="right"><input maxlength="128" <? if (!$Profile["a_movies"]) : ?>class="opa"<? endif ?> id="movies_value" type="text"<? if ($Profile["i_movies"] !== "") : ?> value="<?= $Profile["i_movies"] ?>"<? endif ?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input type="checkbox" id="check_music" class="info_toggle" <? if ($Profile["a_music"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_music"]) : ?>class="opa"<? endif ?>>Music:</span></label></td>
                        <td align="right"><input maxlength="128" <? if (!$Profile["a_music"]) : ?>class="opa"<? endif ?> id="music_value" type="text" <? if ($Profile["i_music"] !== "") : ?> value="<?= $Profile["i_music"] ?>"<? endif ?>></td>
                    </tr>
                    <tr>
                        <td align="left" valign="middle"><label><input type="checkbox" id="check_books" class="info_toggle" <? if ($Profile["a_books"]) : ?>checked<? endif ?>><span <? if (!$Profile["a_books"]) : ?>class="opa"<? endif ?>>Books:</span></label></td>
                        <td align="right"><input maxlength="128" <? if (!$Profile["a_books"]) : ?>class="opa"<? endif ?> id="books_value" type="text"<? if ($Profile["i_books"] !== "") : ?> value="<?= $Profile["i_books"] ?>"<? endif ?>></td>
                    </tr>
                </table>
            </div>
            <a href="javascript:void(0)" onclick="edit_channel_info()" style="display: block;position: absolute;top:15px;right:12px;text-decoration: underline;font-size:13px;">edit</a>
            <? endif ?>
        </div>
        <? foreach (explode(",",$Profile["modules_vertical_l"]) as $Module_l) : ?>
            <? if ($Module_l == "cu") : ?>
                <? if ($Profile["partner"] == 1 && $Profile["c_custom"] && (!empty($Profile["custom"] || $Is_OWNER)) && ($Profile["custom_d"] == 0 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/l_custom.php" ?>
                <? endif ?>
            <? elseif ($Module_l == "re") : ?>
                <? if ($Profile["c_recent"] && ($Profile["recent_d"] == 0 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/l_recent.php" ?>
                <? endif ?>
            <? elseif ($Module_l == "ft") : ?>
                <? if ((!empty($Profile["featured_channels"]) || $Is_OWNER) && $Profile["c_featured_channels"] == 1 && ($Profile["featured_d"] == 1 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/l_featured.php" ?>
                <? endif ?>
            <? elseif ($Module_l == "s2") : ?>
                <? if ($Profile["subscribers"] > 0 and $Profile["c_subscriber"] && ($Profile["subscriber_d"] == 0 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/l_subscribers.php" ?>
                <? endif ?>
            <? elseif ($Module_l == "s1") : ?>
                <? if ($Profile["subscriptions"] > 0 and $Profile["c_subscription"] && ($Profile["subscription_d"] == 0 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/l_subscriptions.php" ?>
                <? endif ?>
            <? elseif ($Module_l == "fr") : ?>
                <? if ($Profile["friends"] > 0 and $Profile["c_friend"] && ($Profile["friends_d"] == 0 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/l_friends.php" ?>
                <? endif ?>
            <? elseif ($Module_l == "co") : ?>
                <? if ($Profile["c_comments"] && ($Profile["channel_d"] == 0 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/l_comments.php" ?>
                <? endif ?>
            <? endif ?>
        <? endforeach ?>
    </div>
    <div class="pr_btm_r">
        <? foreach (explode(",",$Profile["modules_vertical_r"]) as $Module_r) : ?>
            <? if ($Module_r == "cu") : ?>
                <? if ($Profile["partner"] == 1 && $Profile["c_custom"] && (!empty($Profile["custom"] || $Is_OWNER)) && ($Profile["custom_d"] == 1 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/r_custom.php" ?>
                <? endif ?>
            <? elseif ($Module_r == "re") : ?>
                <? if ($Profile["c_recent"] && ($Profile["recent_d"] == 1 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/r_recent.php" ?>
                <? endif ?>
            <? elseif ($Module_r == "ft") : ?>
                <? if ((!empty($Profile["featured_channels"]) || $Is_OWNER) && $Profile["c_featured_channels"] == 1 && ($Profile["featured_d"] == 0 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/r_featured.php" ?>
                <? endif ?>
            <? elseif ($Module_r == "s2") : ?>
                <? if ($Profile["subscribers"] > 0 and $Profile["c_subscriber"] && ($Profile["subscriber_d"] == 1 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/r_subscribers.php" ?>
                <? endif ?>
            <? elseif ($Module_r == "s1") : ?>
                <? if ($Profile["subscriptions"] > 0 and $Profile["c_subscription"] && ($Profile["subscription_d"] == 1 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/r_subscriptions.php" ?>
                <? endif ?>
            <? elseif ($Module_r == "fr") : ?>
                <? if ($Profile["friends"] > 0 and $Profile["c_friend"] && ($Profile["friends_d"] == 1 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/r_friends.php" ?>
                <? endif ?>
            <? elseif ($Module_r == "co") : ?>
                <? if ($Profile["c_comments"] && ($Profile["channel_d"] == 1 || $Is_OWNER)) : ?>
                    <? require_once "_templates/_profile2/widgets/r_comments.php" ?>
                <? endif ?>
            <? endif ?>
        <? endforeach ?>
    </div>
</div>