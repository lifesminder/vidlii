<h1 class="pg_hd">Friends</h1>
<div class="h_l">
    <? if (!isset($_GET["list"])) : ?>
    <? if ($Friends_Amount > 0) : ?>
    <? foreach ($Recent_Activity as $Activity) : ?>
        <div class="friend_activity">
            <? if ($Activity["type_name"] == "friend") : ?>
                <div>
                    <? if (in_array($Activity["id"],$Friends_Array)) : ?>
                        <?= user_avatar2($Activity["id_name"],60,60,$Avatar_Array[$Activity["id"]]) ?>
                    <? else : ?>
                        <?= user_avatar2($Activity["content_name"],60,60,$Avatar_Array[$Activity["content"]]) ?>
                    <? endif ?>
                </div>
                <div style="float: left;line-height:20px;width:555px">

                    <strong><a href="/user/<?= $Activity["id"] ?>"><?= $Activity["id"] ?></a></strong> added <strong><a href="/user/<?= $Activity["content"] ?>"><?= $Activity["content"] ?></a></strong> to his friends.<br>
                    <?= get_time_ago($Activity["date"]) ?>
                </div>
            <? elseif ($Activity["type_name"] == "bulletin") : ?>
                <div>
                    <?= user_avatar2($Activity["displayname"],60,60,$Avatar_Array[$Activity["id"]]) ?>
                </div>
                <div style="float: left;line-height:20px;width:555px">
                    <strong><a href="/user/<?= $Activity["displayname"] ?>"><?= $Activity["displayname"] ?></a></strong> posted a bulletin:<br>
                    <?= $Activity["content"] ?><br>
                    <?= get_time_ago($Activity["date"]) ?>
                </div>
            <? elseif ($Activity["type_name"] == "comment") : ?>
                <div>
                    <?= user_avatar2($Activity["displayname"],60,60,$Avatar_Array[$Activity["video_by"]]) ?>
                </div>
                <div style="float: left;line-height:20px;width:555px">
                    <strong><a href="/user/<?= $Activity["displayname"] ?>"><?= $Activity["displayname"] ?></a></strong> commented on a video.<br>
                    <?= $Activity["content"] ?><br>
                    <div style="margin: 9px 0 0;overflow: hidden">
                        <div style="float:left;margin-right:8px">
                            <?= video_thumbnail($Activity["id"],"",150,100) ?>
                        </div>
                        <div style="float:left;position:relative;bottom:4px;width:300px">
                            <strong><a href="/watch?v=<?= $Activity["id"] ?>"><?= $Activity["title"] ?></a></strong><br>
                            <?= cut_string($Activity["video_desc"],100) ?>
                        </div>
                    </div>
                    <?= get_time_ago($Activity["date"]) ?>
                </div>
            <? elseif ($Activity["type_name"] == "favorite") : ?>
                <div>
                    <?= user_avatar2($Activity["displayname"],60,60,$Avatar_Array[$Activity["video_by"]]) ?>
                </div>
                <div style="float: left;line-height:20px;width:555px">
                    <strong><a href="/user/<?= $Activity["displayname"] ?>"><?= $Activity["displayname"] ?></a></strong> favorited a video.<br>
                    <div style="margin: 9px 0 0;overflow: hidden">
                        <div style="float:left;margin-right:8px">
                            <?= video_thumbnail($Activity["id"],"",150,100) ?>
                        </div>
                        <div style="float:left;position:relative;bottom:4px;width:300px">
                            <strong><a href="/watch?v=<?= $Activity["id"] ?>"><?= $Activity["title"] ?></a></strong><br>
                            <?= cut_string($Activity["content"],100) ?>
                        </div>
                    </div>
                    <?= get_time_ago($Activity["date"]) ?>
                </div>
            <? endif ?>
        </div>
    <? endforeach ?>
    <? else : ?>
        <div style="color:#373737;font-size:18px;text-align:center;padding:5px">
            You need to add friends first before posts in your timeline show up!
        </div>
    <? endif ?>
    <? else : ?>
    <? if ($Friends_Amount > 0) : ?>
    <? foreach ($Friends as $Friend) : ?>
            <div class="friend_sct">
                <div style="float:left;margin-right:8px">
                    <?= user_avatar2($Friend["displayname"],101,101,$Friend["avatar"]) ?>
                </div>
                <div style="float: left;position: relative;bottom:3px;line-height:19px">
                    <strong><a href="/user/<?= $Friend["displayname"] ?>"><?= $Friend["displayname"] ?></a></strong>
                    <div style="font-size:12px">
                    Last Login: <?= get_time_ago($Friend["last_login"]) ?><br>
                    Subscribers: <?= number_format($Friend["subscribers"]) ?><br>
                    Subscriptions: <?= number_format($Friend["subscriptions"]) ?> <br>
                    Video Views: <?= number_format($Friend["video_views"]) ?> <br>
                    Friends: <?= number_format($Friend["friends"]) ?>
                    </div>
                </div>
                <div class="f_btns">
                <a href="/ajax/df/add_friend?u=<?= $Friend["displayname"] ?>"><button>Remove Friend</button></a>
                <a href="/ajax/df/subscribe?u=<?= $Friend["displayname"] ?>"><button><? if (!$_USER->is_subscribed_to($Friend["username"])) : ?>Subscribe<? else : ?>Unsubscribe<? endif ?></button></a>
                <a href="/inbox?page=send_message&to=<?= $Friend["displayname"] ?>"><button>Send Message</button></a>
                </div>
            </div>
    <? endforeach ?>
            <div style="font-weight:bold;word-spacing:4px">
                <? $_PAGINATION->new_show(null,"list=friends") ?>
            </div>
    <? else : ?>
            <div style="color:#373737;font-size:18px;text-align:center;padding:5px">
                You need to add friends first before they show up in your friends list!
            </div>
    <? endif ?>
    <? endif ?>
</div>
<div class="h_r">
    <div style="margin-bottom:10px;overflow:hidden">
        <div style="float:left;margin-right:8px;">
            <?= user_avatar2($_USER->displayname,88,88,$Info["avatar"],"round_avt") ?>
        </div>
        <div style="float: left">
            <a href="/user/<?= $_USER->displayname ?>" style="font-weight: bold;font-size:16px"><?= $_USER->displayname ?></a><br>
            <div style="color:gray;line-height:20px;font-size:13px">
            Friends: <?= number_format($Info["friends"]) ?><br>
            Channel Comments <?= number_format($Info["channel_comments"]) ?><br>
            Channel Views <?= number_format($Info["channel_views"]) ?>
            </div>
        </div>
    </div>
    <? if ($Friends_Amount > 0) : ?>
    <div style="margin-bottom:13px;padding:3px;border:1px solid #ccc;border-radius:8px;text-align: center">
        <? if (!isset($_GET["list"])) : ?>
            <a href="/friends?list=friends">Show All Friends</a>
        <? else : ?>
            <a href="/friends">Show Friends Time-Line</a>
        <? endif ?>
    </div>
    <? endif ?>
    <div class="wdg">
        <div style="height:23px">
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
</div>