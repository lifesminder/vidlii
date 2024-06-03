<div style="margin-bottom:16px;overflow: hidden;position:relative">
    <div style="float:left;width:106px;margin-right:10px">
    <?= user_avatar2($_USER->displayname,100,100,$_USER->Info["avatar"]) ?><br>
        <center><a href="/channel_setup">Change</a></center>
    </div>
    <a href="/user/<?= $_USER->displayname ?>" style="font-size: 16px;font-weight: bold"><?= $_USER->displayname ?></a>
    <span class="sm_stat">Videos Uploaded: <span><?= number_format($_USER->Info["videos"]) ?></span></span>
    <span class="sm_stat">Subscriptions: <span><?= number_format($_USER->Info["subscriptions"]) ?></span></span>
    <span class="sm_stat">Friends: <span><?= number_format($_USER->Info["friends"]) ?></span></span>
    <span class="sm_stat">Favorites: <span><?= number_format($_USER->Info["favorites"]) ?></span></span>
    <div style="position:absolute;top:18px;left:291px">
        <span class="sm_stat">Channel Type: <span><?= $Types[$_USER->Info["channel_type"]] ?></span></span>
        <span class="sm_stat">Channel Views: <span><?= number_format($_USER->Info["channel_views"]) ?></span></span>
        <span class="sm_stat">Video Views: <span><?= number_format($_USER->Info["video_views"]) ?></span></span>
        <span class="sm_stat">Subscribers: <span><?= number_format($_USER->Info["subscribers"]) ?></span></span>
    </div>
    <div style="position:absolute;top:18px;left:490px">
        <span class="sm_stat">Videos Watched: <span><?= number_format($_USER->Info["videos_watched"]) ?></span></span>
        <span class="sm_stat">Videos Rated: <span><?= number_format($Rated) ?></span></span>
        <span class="sm_stat">Comments Written: <span><?= number_format($Comments) ?></span></span>
    </div>
</div>

<div style="border: 1px solid #d2edff;padding: 10px 10px 10px 25px;overflow:hidden;line-height:24px;font-weight:bold;text-decoration: none;width:95.4%;background:#e7f5fe">
    <div style="float:left;width:39%;margin-left:1%">
        My Videos<br>
        <a href="/my_videos">Uploaded Videos</a><br>
        <a href="/my_favorites">Favorites</a><br>
        <a href="/my_playlists">Playlists</a><br>
        <a href="/my_subscriptions">Subscriptions</a>
    </div>
    <div style="float:left;width:42%">
        My Network<br>
        <a href="/inbox">Inbox</a><br>
        <a href="/inbox?page=comments">Comments</a><br>
        <a href="/inbox?page=invites">Friend Invites</a><br>
        <a href="/inbox?page=responses">Video Responses</a><br>
    </div>
    <div style="float:left;width:18%">
        More<br>
        <a href="/user/<?= $_USER->displayname ?>">Your Channel</a><br>
        <a href="/friends">Your Friends</a><br>
        <a href="/channel_setup">Change Avatar</a><br>
        <? if ($Channel_Version == 1) : ?>
        <a href="/channel_theme">Channel Design</a><br>
        <? else : ?>
        <a href="/user/<?= $_USER->displayname ?>">Channel Design</a><br>
        <? endif ?>
    </div>
</div>