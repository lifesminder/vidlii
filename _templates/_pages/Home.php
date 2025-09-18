<? if (isset($_GET["m"]) && $_GET["m"] == "cr") : ?>
    <div style="border: 1.5px solid green;padding:6px;text-align:center;margin-bottom:11px;font-size:14px;font-weight:bold">
        Your account is now activated!
    </div>
<? endif ?>

<section class="h_l">
    <? if (!isset($_COOKIE["s"]) || !$_USER->logged_in) : ?>
    <? if ($_USER->logged_in) : ?>
        <div class="adh">
            <a onclick="$('#mod_selector').toggleClass('hddn')" href="javascript:void(0)"><img src="/img/addh.png">Add / Remove Modules</a>
        </div>
        <div id="mod_selector" class="hddn">
            <div style="font-weight: bold;font-size:13px;margin-bottom:5px">Select the modules you want to appear on your homepage:</div>
                <form action="/" method="POST">
                    <table cellpadding="3">
                        <tr>
                            <td width="250px"><label><input type="checkbox" name="i_subs"<? if ($Modules["subscriptions"]) : ?> checked<? endif ?>> Subscriptions</label></td>
                            <td><label><input type="checkbox" name="i_in"<? if ($Modules["inbox"]) : ?> checked<? endif ?>> Inbox</label></td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" name="i_rec"<? if ($Modules["recommended"]) : ?> checked<? endif ?>> Recommended</label></td>
                            <td><label><input type="checkbox" name="i_stat"<? if ($Modules["stats"]) : ?> checked<? endif ?>> Channel Stats</label></td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" name="i_bein"<? if ($Modules["being_watched"]) : ?> checked<? endif ?>> Being Watched Now</label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" name="i_feat"<? if ($Modules["featured"]) : ?> checked<? endif ?>> Featured Videos</label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label><input type="checkbox" name="i_pop"<? if ($Modules["most_popular"]) : ?> checked<? endif ?>> Most Popular</label></td>
                            <td></td>
                        </tr>
                    </table>
                    <input style="margin-top: 11px" type="submit" value="Save Changes" name="save_modules">
                </form>
        </div>
    <? else: ?>
    <div id="iyt-login-suggest-box" style="margin-bottom: 10px;">
        <div class="yt-alert yt-alert-announce yt-rounded" style="height: 85px;">
            <div class="yt-alert-content" style="height: 85px;">
                <div class="signup-promo-message" style="margin-bottom: 14px">Join the open source video-sharing community!</div>
                <div class="signup-promo-links">
                    <a href="/register" class="yel_btn large"><span class="yt-uix-button-content">Create Account ›</span></a>
                    <span class="signup-promo-have-account" style="font-weight: normal">Already have an account? </span>
                    <a href="/login">Sign In</a>
                </div>
            </div>
        </div>
    </div>
    <? endif ?>
    <? if ($_USER->logged_in && $Achievements_Amount > 0) : ?>
    <div class="whats_new" id="home_congrats" type="<?= $Achievements[0]["type"] ?>" style="padding-bottom:17px;position:relative">
        <strong>Congratulations!</strong><br>
        <div style="margin-top:3px">You have reached <b><?= $Achievements[0]["amount"] ?> <? if ($Achievements[0]["type"] == "v") : ?>Views<? else : ?>Subscribers<? endif ?></b> on <b><?= date("M d, Y",strtotime($Achievements[0]["ach_date"])) ?></b>!</div>
        <div><?= $Achievements[0]["text"] ?></div>
        <a href="javascript:void(0)" onclick="close_achievement()" style="position:absolute;right:18px;top:4px;font-size:17px">Close</a>
    </div>
    <? endif ?>
    <? if ($_USER->logged_in) : ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[0].".php" ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[1].".php" ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[2].".php" ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[3].".php" ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[4].".php" ?>
    <? else : ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[0].".php" ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[1].".php" ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[2].".php" ?>
        <? require_once "_templates/_pages/home_widgets/".$Position[3].".php" ?>
    <? endif ?>
    <? else : ?>
        <style>
            .avt2{border-radius:8px;border:1px solid #b4b4b4}.avt2:hover{border:1px solid #7f86f4}
            #f_poster{border-radius:8px;display:block;border:1px solid #b4b4b4;width:100%;font-family:Arial, Helvetica, sans-serif;padding:6px;overflow-x:hidden;outline:0;height:77px;max-height:256px;resize:none}
            .f_button{outline:0;border-radius:8px;padding: 4px 11px;border: 1px solid #b4b4b4;font-size: 12px;background: #ffffff;background: -moz-linear-gradient(top, #ffffff 0%, #efefef 100%);  background: -webkit-linear-gradient(top, #ffffff 0%,#efefef 100%);  background: linear-gradient(to bottom, #ffffff 0%,#efefef 100%);  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#efefef',GradientType=0 );  cursor: pointer; }
            .f_button:hover { background: -moz-linear-gradient(top, #ffffff 0%, #E6E6E6 100%);  background: -webkit-linear-gradient(top, #ffffff 0%,#E6E6E6 100%);  background: linear-gradient(to bottom, #ffffff 0%,#E6E6E6 100%);  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#E6E6E6',GradientType=0 );  }
            .f_button:active { background: -moz-linear-gradient(top, #efefef 0%, #ffffff 100%);  background: -webkit-linear-gradient(top, #efefef 0%,#ffffff 100%);  background: linear-gradient(to bottom, #efefef 0%,#ffffff 100%);  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#efefef', endColorstr='#ffffff',GradientType=0 );}
        </style>
        <script>
            function p_bl() {
                var bl = $('#f_poster').val();
                if (bl.length > 0 && isEmptyOrSpaces(bl) == false) {
                    document.getElementById("post_button").disabled = true;
                    document.getElementById("post_button").innerHTML = "Posting...";
                    document.getElementById("clear_button").disabled = true;
                    $.ajax({
                        type: "POST",
                        url: "/ajax/ajax_bulletin",
                        data: {bulletin: bl},
                        success: function (output) {
                            $("#nof").remove();
                            <? if (!isset($_COOKIE["st"]) && (!isset($_GET["t"]) || $_GET["t"] == 1)) : ?>
                            $("#f_timeline").prepend(output);
                            <? endif ?>
                            $('#f_poster').val("");
                            document.getElementById("post_button").disabled = false;
                            document.getElementById("post_button").innerHTML = "Post";
                            document.getElementById("clear_button").disabled = false;
                            alert("Bulletin successfully posted!");
                        }
                    });
                } else {
                    alert("Bulletins can't be empty!");
                }
            }
            function fty() {
                var type = $("#fty option:selected").val();
                if (type == "all") {
                    window.location  = "/?act=0";
                } else {
                    window.location  = "/?act=1";
                }
            }
            function fto() {
                var type = $("#fto option:selected").val();
                if (type == "1") {
                    window.location  = "/";
                } else if(type == "2") {
                    window.location  = "/?t=1";
                } else if (type == "3") {
                    window.location  = "/?t=2";
                } else if (type == "4") {
                    window.location  = "/?t=3";
                }
            }
        </script>
        <div style="overflow: hidden;margin-bottom:12px;padding-bottom:7px;border-bottom:1px solid #ccc">
            <div style="margin-left:68px">
                <select onchange="fty()" id="fty" style="font-size:12px;padding:2px;border-radius:8px;outline:0;margin:0 8px 5px 0;border:1px solid #b4b4b4">
                    <option value="all"<? if (!isset($_COOKIE["st"])) : ?> selected<? endif ?>>From Everyone</option>
                    <option value="notall"<? if (isset($_COOKIE["st"])) : ?> selected<? endif ?>>From Subscriptions</option>
                </select>
                <select<? if (isset($_COOKIE["st"])) : ?> disabled<? endif ?> id="fto" onchange="fto()" style="font-size:12px;padding:2px;border-radius:8px;outline:0;margin:0 0 5px;border:1px solid #b4b4b4;margin-right:10px">
                    <option value="1"<? if (!isset($_GET["t"])) : ?> selected<? endif ?>>Everything</option>
                    <option value="2"<? if (isset($_GET["t"]) && $_GET["t"] == 1) : ?> selected<? endif ?>>Bulletins</option>
                    <option value="3"<? if (isset($_GET["t"]) && $_GET["t"] == 2) : ?> selected<? endif ?>>Comments</option>
                    <option value="4"<? if (isset($_GET["t"]) && $_GET["t"] == 3) : ?> selected<? endif ?>>Favorites</option>
                </select>
            </div>
            <div style="float:left;margin-right:25px;"><div style="position:relative;left:9px;top:24px"><?= user_avatar2($_USER->username,42,42,$Your_Avatar) ?></div></div>
            <div style="float:left;width:580px;position: relative"><div class="triangle-left"><div class="inner-triangle"></div></div><textarea maxlength="500" id="f_poster" placeholder="Write a bulletin..."></textarea></div>
            <div style="float:right;position:relative;right:1.5px;margin: 5px 0 0 0"><button type="button" class="f_button" onclick="$('#f_poster').val('');$('#f_poster').focus()" id="clear_button" style="margin:0 5px 0 0;">Clear</button><button type="button" id="post_button" class="f_button" onclick="p_bl()">Post</button></div>
        </div>
        <div id="f_timeline">
        <? if ($Subscriptions_Amount > 0 || $Friends_Amount > 0) : ?>
        <? if (count($Recent_Activity) > 0) : ?>
        <? foreach ($Recent_Activity as $Activity) : ?>
            <div class="friend_activity">
                <? if ($Activity["type_name"] == "friend") : ?>
                    <div>
                        <? if (in_array($Activity["id"],$Friends_Array)) : ?>
                            <?= user_avatar2($Activity["id"],60,60,$Avatar_Array[$Activity["id"]]) ?>
                        <? else : ?>
                            <?= user_avatar2($Activity["content"],60,60,$Avatar_Array[$Activity["content"]]) ?>
                        <? endif ?>
                    </div>
                    <div>
                        <div><a href="/user/<? if (in_array($Activity["id"],$Friends_Array)) { echo $Activity["id"]; } else { echo $Activity["content"]; }  ?>"><? if (in_array($Activity["id"],$Friends_Array)) { echo $Activity["id"]; } else { echo $Activity["content"]; }  ?></a></strong> added <a href="/user/<? if (in_array($Activity["id"],$Friends_Array)) { echo $Activity["content"]; } else { echo $Activity["id"]; }  ?>"><? if (in_array($Activity["id"],$Friends_Array)) { echo $Activity["content"]; } else { echo $Activity["id"]; }  ?></a> to his friends.</div>
                        <div class="f_btm"><?= get_time_ago($Activity["date"]) ?></div>
                    </div>
                <? elseif ($Activity["type_name"] == "bulletin") : ?>
                    <div>
                        <?= user_avatar2($Activity["displayname"],60,60,$Avatar_Array[$Activity["id"]]) ?>
                    </div>
                    <div>
                        <div><a href="/user/<?= $Activity["displayname"] ?>"><? if ($_USER->username != $Activity["id"]) : ?><?= $Activity["displayname"] ?><? else : ?>You<? endif ?></a></div>
                        <div class="f_msg"><?= hashtag_search(DoLinks(nl2br($Activity["content"]))) ?></div>
                        <div class="f_btm"><?= get_time_ago($Activity["date"]) ?></div>
                    </div>
                <? elseif ($Activity["type_name"] == "comment") : ?>
                    <div>
                        <?= user_avatar2($Activity["displayname"],60,60,$Avatar_Array[$Activity["video_by"]]) ?>
                    </div>
                    <div>
                        <div><a href="/user/<?= $Activity["displayname"] ?>"><?= $Activity["displayname"] ?></a> commented on a video.</div>
                        <div class="f_msg"><?= $Activity["content"] ?></div>
                        <div style="overflow: hidden;margin:9px 0 0 0">
                            <div style="float:left;margin-right:8px">
                                <?= video_thumbnail($Activity["id"],"",150,100) ?>
                            </div>
                            <div style="float:left;position:relative;bottom:1px;width:300px">
                                <strong><a href="/watch?v=<?= $Activity["id"] ?>"><?= $Activity["title"] ?></a></strong>
                                <div class="f_dsr"><?= cut_string($Activity["video_desc"],100) ?></div>
                            </div>
                        </div>
                        <div class="f_btm"><?= get_time_ago($Activity["date"]) ?></div>
                    </div>
                <? elseif ($Activity["type_name"] == "favorite") : ?>
                    <div>
                        <?= user_avatar2($Activity["displayname"],60,60,$Avatar_Array[$Activity["video_by"]]) ?>
                    </div>
                    <div>
                        <div><a href="/user/<?= $Activity["displayname"] ?>"><?= $Activity["displayname"] ?></a> favorited a video.</div>
                        <div style="overflow: hidden;margin:9px 0 0 0">
                            <div style="float:left;margin-right:8px">
                                <?= video_thumbnail($Activity["id"],"",150,100) ?>
                            </div>
                            <div style="float:left;position:relative;bottom:1px;width:300px">
                                <strong><a href="/watch?v=<?= $Activity["id"] ?>"><?= $Activity["title"] ?></a></strong>
                                <div class="f_dsr"><?= cut_string($Activity["content"],100) ?></div>
                            </div>
                        </div>
                        <div class="f_btm"><?= get_time_ago($Activity["date"]) ?></div>
                    </div>
                <? elseif ($Activity["type_name"] == "sub") : ?>
                    <div>
                        <?= user_avatar2($Activity["displayname"],60,60,$Subscription_Avatar_Array[$Activity["video_by"]]) ?>
                    </div>
                    <div>
                        <div><a href="/user/<?= $Activity["displayname"] ?>"><?= $Activity["displayname"] ?></a> uploaded a video.</div>
                        <div style="overflow: hidden;margin:9px 0 0 0">
                            <div style="float:left;margin-right:8px">
                                <?= video_thumbnail($Activity["id"],"",150,100) ?>
                            </div>
                            <div style="float:left;position:relative;bottom:1px;width:300px">
                                <strong><a href="/watch?v=<?= $Activity["id"] ?>"><?= $Activity["title"] ?></a></strong>
                                <div class="f_dsr"><?= cut_string($Activity["video_desc"],100) ?></div>
                            </div>
                        </div>
                        <div class="f_btm"><?= get_time_ago($Activity["date"]) ?></div>
                    </div>
                <? endif ?>
            </div>
        <? endforeach ?>
        <? else : ?>
                <div id="nof" style="text-align:center;font-size:20px;color:#999;margin-top:20px">Your subscriptions and friends haven't been active lately...</div>
        <? endif ?>
        <? else : ?>
            <div id="nof" style="text-align:center;font-size:20px;color:#999;margin-top:20px">Subscribe or befriend some VidLii users to have a Timeline!</div>
        <? endif ?>
        </div>
    <? endif ?>
</section>
<aside class="h_r">
<div style="text-align:center;">
    </div>
    <? if ($Most_Viewed != null && count($Most_Viewed) > 0) : ?>
        <div class="mv_wr">
            <div style="height:250px;border:0;border-radius:0;padding:0">
                <? require_once "_templates/_layout/player.php" ?>
            </div>
            <? if ($Player == 2) : ?>
            <style>
                .vlControls {
                    border-bottom-right-radius: 0 !important;
                    border-bottom-left-radius: 0 !important;
                }
            </style>
            <? endif ?>
        </div>
        <div class="mv_under" style="bottom:0;margin-bottom:12px">
            <?= user_avatar2($Most_Viewed["displayname"],50,50,$Most_Viewed["avatar"],"") ?>
            <div>
            <a href="/watch?v=<?= $Most_Viewed["url"] ?>"><?= $Most_Viewed["title"] ?></a>
                <? show_ratings($Most_Viewed,18,17) ?>
            </div>
        </div>
    <? endif ?>
    <? if ($_USER->logged_in && (isset($_COOKIE["s"]) || $Modules["inbox"])) : ?>
    <div class="wdg">
        <div>
            <span>Inbox</span>
        </div>
        <div id="inbox_wdg">
            <div><div><img src="/img/amsg<? if ($Inbox_Amounts["messages"] == 0) : ?>0<? else : ?>1<? endif ?>.png" style="width:24px;bottom:3.5px"></div> <a href="/inbox?page=messages" style="bottom:1px;top:inherit"><?= number_format($Inbox_Amounts["messages"]) ?> Personal Message<? if ($Inbox_Amounts["messages"] == 0 || $Inbox_Amounts["messages"] > 1) : ?>s<? endif ?></a></div>
            <div><div><img src="/img/acmt<? if ($Inbox_Amounts["comments"] == 0) : ?>0<? else : ?>1<? endif ?>.png" style="width:24px;height:18px;bottom:1px;left:0.5px"></div> <a href="/inbox?page=comments"><?= number_format($Inbox_Amounts["comments"]) ?> Comment<? if ($Inbox_Amounts["comments"] == 0 || $Inbox_Amounts["comments"] > 1) : ?>s<? endif ?></a></div>
            <div><div><img src="/img/brsp<? if ($Inbox_Amounts["responses"] == 0) : ?>0<? else : ?>1<? endif ?>.png" style="width:24px;height:18px;bottom:1px;left:0.5px"></div> <a href="/inbox?page=responses"><?= $Inbox_Amounts["responses"] ?> Video Response<? if ($Inbox_Amounts["responses"] == 0 || $Inbox_Amounts["responses"] > 1) : ?>s<? endif ?></a></div>
            <div><div><img src="/img/lfr<? if ($Inbox_Amounts["invites"] == 0) : ?>0<? else : ?>1<? endif ?>.png" style="width:24px;bottom:1.5px;left:0.5px;height:19px"></div> <a href="/inbox?page=invites"><?= number_format($Inbox_Amounts["invites"]) ?> Friend Invite<? if ($Inbox_Amounts["invites"] == 0 || $Inbox_Amounts["invites"] > 1) : ?>s<? endif ?></a></div>
            <div style="text-align:center;border:0"><a href="/inbox?page=send_message">Send Message</a></div>
        </div>
    </div>
    <? endif ?>
    <? if ($_USER->logged_in && (isset($_COOKIE["s"]) || $Modules["stats"])) : ?>
        <div class="wdg">
            <div>
                <span>Channel Statistics</span>
            </div>
            <div id="inbox_wdg" style="padding-bottom:1px">
                <div><div><img src="/img/sme.png" style="width:24px;bottom:1.5px;left:0.5px;height:18px"></div> <span><?= number_format($Stats["subscribers"]) ?> Subscribers</span></div>
                <div><div><img src="/img/sme.png" style="width:24px;bottom:1.5px;left:0.5px;height:18px"></div> <span><?= number_format($Stats["subscriptions"]) ?> Subscriptions</span></div>
                <div><div><img src="/img/sme.png" style="width:24px;bottom:1.5px;left:0.5px;height:18px"></div> <span><?= number_format($Stats["friends"]) ?> Friends</span></div>
                <div><div><img src="/img/brsp0.png" style="width:24px;height:18px;bottom:1px;left:0.5px"></div> <span style="top:2px"><?= number_format($Stats["video_views"]) ?> Video Views</span> </div>
                <div style="border:0"><div><img src="/img/bchn.png" style="width:24px;height:18px;bottom:1px;left:0.5px"></div> <span style="top:2px"><?= number_format($Stats["channel_views"]) ?> Channel Views</span> </div>
            </div>
        </div>
    <? endif ?>
    <?php
        if(count($feed["recommended"]["channels"]) > 0) {
    ?>
    <div class="wdg">
        <div>
            <span>Recommended Channels</span>
        </div>
        <div class="recommended-list">
            <?php
                for($i = 0; $i < count($feed["recommended"]["channels"]); $i++) {
                    $Recommended_Channel = $feed["recommended"]["channels"][$i];
            ?>
            <div class="recommended-list-item">
                <div class="avatar">
                    <?= user_avatar2($Recommended_Channel["username"], 56, 56, $Recommended_Channel["avatar"]) ?>
                </div>
                <div class="body">
                    <a href="/user/<?= $Recommended_Channel["displayname"] ?>" style="font-weight:bold;font-size:16px">
                        <?= urldecode(($Recommended_Channel["channel_title"] != "") ? $Recommended_Channel["channel_title"] : $Recommended_Channel["displayname"]) ?>
                    </a>
                    <? if (!empty($Recommended_Channel["channel_description"])) : ?>
                        <p>
                            <?php
                                echo urldecode((strlen($Recommended_Channel["channel_description"]) > 48) ? substr($Recommended_Channel["channel_description"], 0, 48)."..." : $Recommended_Channel["channel_description"]);
                            ?>
                        </p>
                    <? endif ?>
                    <div class="info">
                        <p>
                            <?= number_format($Recommended_Channel["video_views"]) ?> views · <?= number_format($Recommended_Channel["subscribers"]) ?> subscribers
                        </p>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
    <?php } ?>
    <div class="whats_new">
        <strong>What's New</strong>
        <a href="/<? if ($_USER->logged_in) : ?>channel_version<? else : ?>login<? endif ?>">Cosmic Panda</a>
        Say hello to the old and good 2012 Layout of YouTube! It clearly resembles it as far as possible ;)
        <a href="/">Awards</a>
        See how you compare to other channels this week, or this month, or this decade.
        <a href="/themes">Themes</a>
        Customize your layout of VidLii and make it yours!
    </div>
    <? if(!empty($feed["last_online"])): ?>
    <div class="last_5">
        <strong>Last <?php echo (isset($_ENV["show_online_count"]) && (int)$_ENV["show_online_count"] >= 1) ? (int)$_ENV["show_online_count"] : 5; ?> Users Online</strong>
        <? foreach ($feed["last_online"] as $Online) : ?>
            <div>
                <a href="/user/<?= $Online["displayname"] ?>"><?= $Online["displayname"] ?></a>
                <span><?= number_format($Online["videos"]) ?> videos</span><span><?= number_format($Online["favorites"]) ?> favorites</span><span><?= number_format($Online["friends"]) ?> friends</span></span>
            </div>
        <? endforeach ?>
    </div>
    <? endif ?>
</aside>
<div class="cl"></div>
