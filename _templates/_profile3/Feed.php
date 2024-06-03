<div class="cosmic_l">
    <div style="padding: 20px 50px;">
        <div class="cosmic_feed_selector">
            <? if ($Profile["c_recent"]) : ?><a href="/user/<?= $Profile["displayname"] ?>/feed" <? if ($_GET["page"] == "feed") : ?>class="selected"<? endif ?>>Activity</a><? endif ?>
            <? if ($Profile["c_comments"]) : ?><a href="/user/<?= $Profile["displayname"] ?>/comments" <? if ($_GET["page"] == "comments") : ?>class="selected"<? endif ?>>Channel Comments</a><? endif ?>
            <? if ($Is_OWNER) : ?>
                <? if ($_GET["page"] == "feed") : ?>
                    <button type="button" id="show_feed_settings" class="cosmic_button" style="position:absolute;right:0" onclick="$(this).toggleClass('hddn');$('#feed_settings').toggleClass('hddn')">Activity Settings</button>
                <? elseif ($_GET["page"] == "comments") : ?>
                    <button type="button" id="show_feed_settings" class="cosmic_button" style="position:absolute;right:0" onclick="$(this).toggleClass('hddn');$('#feed_settings').toggleClass('hddn')">Comment Settings</button>
                <? endif ?>
            <? endif ?>
        </div>
        <? if ($_GET["page"] == "feed") : ?>
            <? if ($Is_OWNER) : ?>
                <div id="feed_settings" class="hddn" style="background-color:#7696e6;padding:13px;font-size:14px;border-radius:5px;margin-top:22px;margin-bottom:8px;position:relative;border:1px solid #4f87d4;color:white;font-weight:bold">
                    <div style="font-size:18px;margin-bottom:5px;font-weight:normal">Activity Settings</div>
                    <form action="/user/<?= $_USER->displayname ?>/feed" method="POST">
                        <div>
                            <table style="font-weight:normal;position:relative;top:2px;right:7px">
                                <tr>
                                    <td><input type="checkbox" name="recent_comments" id="cosmic_every"<? if ($Profile["ra_comments"]) : ?> checked<? endif ?>></td>
                                    <td><label for="cosmic_every" style="font-weight:bold">Recent Comments</label></td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="recent_favorites" id="cosmic_friends"<? if ($Profile["ra_favorites"]) : ?> checked<? endif ?>></td>
                                    <td><label for="cosmic_friends" style="font-weight:bold">Recent Favorites</label></td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="recent_friends" id="cosmic_nobody"<? if ($Profile["ra_friends"]) : ?> checked<? endif ?>></td>
                                    <td><label for="cosmic_nobody" style="font-weight:bold">Recent Friends</label></td>
                                </tr>
                            </table>
                        </div>
                        <div style="position:absolute;top:14px;right:15px">
                            <button type="button" class="cosmic_button" onclick="$('#feed_settings').toggleClass('hddn');$('#show_feed_settings').toggleClass('hddn')">Cancel</button>
                            <input type="submit" class="cosmic_button" name="save_recent" value="Apply">
                        </div>
                    </form>
                </div>
            <? endif ?>
            <? if ($Is_OWNER) : ?>
                <input type="text" maxlength="500" id="cosmic_textarea" placeholder="Write a bulletin..." style="width:468px;display:inline-block"><button type="button" class="cosmic_button" id="cosmic_post_bulletin" onclick="<? if ($_USER->Is_Activated) : ?>post_cosmic_bulletin()<? else : ?>alert('You must activate your account before you are able to post bulletins!')<? endif ?>" style="margin-left:5px;width:85px;height:22px">Post</button>
            <? endif ?>
        <div class="cosmic_recent">
            <? if (count($Recent_Activity) > 0) : ?>
            <? foreach ($Recent_Activity as $Activity) : ?>
                <? if ($Activity["type_name"] == "bulletin") : ?>
                    <div id="cb_<?= $Activity["id"] ?>">
                        <div>
                            <div><?= no_link_avatar($Profile["displayname"],50,50,$Profile["avatar"]) ?></div>
                            <span><?= time_ago($Activity["date"]) ?><? if ($Is_OWNER) : ?> | <a href="javascript:void(0)" style="color:#999" onclick="delete_cosmic_bulletin(<?= $Activity["id"] ?>)">Delete</a><? endif ?></span>
                            <div>
                                <div><a href="/user/<?= $Profile["displayname"] ?>"><?= $Profile["displayname"] ?></a> posted:</div>
                                <div><?= $Activity["content"] ?></div>
                            </div>
                        </div>
                    </div>
                <? elseif ($Activity["type_name"] == "comment") : ?>
                    <div>
                        <div>
                            <div><?= video_thumbnail($Activity["id"],$Activity["length"],100,66,$Activity["title"]) ?></div>
                            <span><?= time_ago($Activity["date"]) ?></span>
                            <div>
                                <div><a href="/watch?v=<?= $Activity["id"] ?>"><?= cut_string($Activity["title"],35) ?></a></div>
                                <div>
                                    <?= cut_string($Activity["content"],150) ?>
                                    <div style="color:#333"><?= number_format($Activity["views"]) ?> views</div>
                                    <div><?= no_link_avatar($Profile["displayname"],21,21,$Profile["avatar"]) ?> <a href="/user/<?= $Profile["displayname"] ?>"><?= $Profile["displayname"] ?></a> commented on</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? elseif ($Activity["type_name"] == "favorite") : ?>
                    <div>
                        <div>
                            <div><?= video_thumbnail($Activity["id"],$Activity["length"],100,66,$Activity["title"]) ?></div>
                            <span><?= time_ago($Activity["date"]) ?></span>
                            <div>
                                <div><a href="/watch?v=<?= $Activity["id"] ?>"><?= cut_string($Activity["title"],35) ?></a></div>
                                <div>
                                    <?= cut_string($Activity["content"],150) ?>
                                    <div style="color:#333"><?= number_format($Activity["views"]) ?> views</div>
                                    <div><?= no_link_avatar($Profile["displayname"],21,21,$Profile["avatar"]) ?> <a href="/user/<?= $Profile["displayname"] ?>"><?= $Profile["displayname"] ?></a> favorited</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? elseif ($Activity["type_name"] == "friend") : ?>
                    <div>
                        <div>
                            <div><?= no_link_avatar($Profile["displayname"],50,50,$Profile["avatar"]) ?></div>
                            <span><?= time_ago($Activity["date"]) ?></span>
                            <div>
                                <div><a href="/user/<?= $Profile["displayname"] ?>"><?= $Profile["displayname"] ?></a> and <? if ($Activity["id"] == $Profile["displayname"]) : ?><a href="/user/<?= $Activity["content"] ?>"><?= $Activity["content"] ?></a><? else : ?><a href="/user/<?= $Activity["id"] ?>"><?= $Activity["id"] ?></a><? endif ?> are friends!</div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                <? endif ?>
            <? endforeach ?>
            <? else : ?>
                <span id="no_recent_activity" style="margin-top:40px;display:block;color: #808080;text-align: center;font-size:20px;">This user doesn't have any activity!</span>
            <? endif ?>

        </div>
        <? else : ?>
            <? if ($Is_OWNER) : ?>
                <div id="feed_settings" class="hddn" style="background-color:#7696e6;padding:13px;font-size:14px;border-radius:5px;margin-top:22px;margin-bottom:25px;position:relative;border:1px solid #4f87d4;color:white;font-weight:bold">
                    <div style="font-size:18px;margin-bottom:5px;font-weight:normal">Comment Settings</div>
                    <form action="/user/<?= $_USER->displayname ?>/feed" method="POST">
                        <div>
                            <table style="font-weight:normal;position:relative;top:2px;right:7px">
                                <tr>
                                    <td><input type="radio" value="0" name="cosmic_comment_setting" id="cosmic_every"<? if ($Profile["channel_comment_privacy"] == 0) : ?> checked<? endif ?>></td>
                                    <td><label for="cosmic_every"><strong>Everyone</strong> can comment on my channel</label></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" value="1" name="cosmic_comment_setting" id="cosmic_friends"<? if ($Profile["channel_comment_privacy"] == 1) : ?> checked<? endif ?>></td>
                                    <td><label for="cosmic_friends"><strong>Only Friends</strong> can comment on my channel</label></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" value="2" name="cosmic_comment_setting" id="cosmic_nobody"<? if ($Profile["channel_comment_privacy"] == 2) : ?> checked<? endif ?>></td>
                                    <td><label for="cosmic_nobody"><strong>Nobody</strong> can comment on my channel</label></td>
                                </tr>
                            </table>
                        </div>
                        <div style="position:absolute;top:14px;right:15px">
                            <button type="button" class="cosmic_button" onclick="$('#feed_settings').toggleClass('hddn');$('#show_feed_settings').toggleClass('hddn')">Cancel</button>
                            <input type="submit" class="cosmic_button" name="save_comments" value="Apply">
                        </div>
                    </form>
                </div>
            <? endif ?>
            <? if ($_USER->logged_in && $_USER->Is_Activated && !$Is_Blocked && !$Has_Blocked) : ?>
                <? if ($Profile["channel_comment_privacy"] == 0 || (($Profile["channel_comment_privacy"] == 1 && $Is_Friends == 1) || $Is_OWNER) || ($Profile["channel_comment_privacy"] == 1 && $Is_OWNER)) : ?>
                    <textarea id="cosmic_textarea" maxlength="500" rows="4" placeholder="Write a channel comment..."></textarea>
                    <button type="button" style="height:22px" class="cosmic_button" id="cosmic_comment_post" onclick="<? if ($_USER->Is_Activated) : ?>post_channel_comment()<? else : ?>alert('You must activate your account before you are able to comment!')<? endif ?>">Post Comment</button>
                <? elseif ($Profile["channel_comment_privacy"] == 1) : ?>
                    <div style="text-align: center;margin-top:25px;color:#666">You must be friends with <strong><?= $Profile["displayname"] ?></strong> to post a comment!</div>
                <? elseif ($Profile["channel_comment_privacy"] == 2) : ?>
                    <div style="text-align: center;margin-top:25px;color:#666"><strong><?= $Profile["displayname"] ?></strong> has disabled his channel comments!</div>
                <? endif ?>
            <? elseif (!$_USER->logged_in) : ?>
            <? elseif (!$_USER->Is_Activated) : ?>
                <div style="text-align: center;margin-top:25px;color:#666">Please <strong>click on the activation link</strong> we sent to your email to post a comment!</div>
            <? elseif($Is_Blocked || $Has_Blocked) : ?>
                <div style="text-align: center;margin-top:25px;color:#666">You cannot interact with <strong><?= $Profile["displayname"] ?></strong>!</div>
            <? endif ?>
        <div id="cosmic_channel_comments">
            <? if ($Profile["channel_comments"] > 0) : ?>
            <? foreach ($Channel_Comments as $Channel_Comment) : ?>
                    <div class="cosmic_comment" id="cc_<?= $Channel_Comment["id"] ?>">
                        <div>
                            <?= no_link_avatar($Channel_Comment["displayname"],26,26,$Channel_Comment["avatar"]) ?>
                            <div>
                                <a href="/user/<?= $Channel_Comment["displayname"] ?>"><?= $Channel_Comment["displayname"] ?></a> posted a comment <span><?= get_time_ago($Channel_Comment["date"]) ?></span>
                            </div>
                        </div>
                        <? if ($_USER->logged_in && ($_USER->username == $Channel_Comment["by_user"] || $Is_OWNER)) : ?><a href="javascript:void(0)" class="cosmic_delete" onclick="delete_comment(<?= $Channel_Comment["id"] ?>)">Delete</a><? endif ?>
                        <div>
                            <div>
                                <?= nl2br(hashtag_search(mention(strip_tags($Channel_Comment["comment"])))) ?>
                            </div>
                        </div>
                    </div>
            <? endforeach ?>
            <? if ($Show_More) : ?><button type="button" onclick="show_more_comments(1,'<?= $Profile["username"] ?>')" class="cosmic_button" id="show_more_comments" style="width:100%">Load 20 more comments</button><? endif ?>
            <? else : ?>
                <span id="no_comments" style="margin-top:23px;display:block;color: #808080;text-align: center;font-size:20px;">This user doesn't have any comments!</span>
            <? endif ?>
        </div>
        <? endif ?>
    </div>
</div>
<div class="cosmic_d">
    <? require_once "right_side.php" ?>
</div>