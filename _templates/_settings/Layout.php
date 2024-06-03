<form action="/channel_version" method="POST">
    <div style="padding:5px 10px;border:1px solid #cccccc;width:740px;border-radius:5px;overflow:hidden;margin-bottom:15px">
        <img src="/img/channel10.png" style="float:left;height:85px;margin-right:15px">
        <div style="float:left;width:450px">
        <strong style="display:block;margin:0 0 5px">Channel 1.0</strong>
            - The original layout from YouTube 2006-2009<br>
            - Very simple and lightweight<br>
            - Very consistent presentation of your content<br>
            - Shows more information about YOU.
        </div>
        <div style="float:right">
            <input class="search_button" type="submit" style="padding:34px 4px" name="switch_1" value="Switch!"<? if ($Info["channel_version"] == 1) : ?> disabled<? else : ?>onclick="return confirm('Are you sure you want to switch? Your current channel colors and settings will be reset!')"<? endif ?>>
        </div>
    </div>
    <div style="padding:5px 10px;border:1px solid #cccccc;width:740px;border-radius:5px;overflow:hidden;margin-bottom:15px">
        <img src="/img/channel20.png" style="float:left;height:85px;margin-right:15px">
        <div style="float:left;width:450px">
            <strong style="display:block;margin:0 0 5px">Channel 2.0</strong>
            - The layout from YouTube 2009-2012<br>
            - Watch a users videos without leaving his channel page<br>
            - Design your channel with seeing the changes in realtime<br>
            - More modern than channel 1.0
        </div>
        <div style="float:right">
            <input class="search_button" type="submit" style="padding:34px 4px" name="switch_2" value="Switch!"<? if ($Info["channel_version"] == 2) : ?> disabled<? else : ?>onclick="return confirm('Are you sure you want to switch? Your current channel colors and settings will be reset!')"<? endif ?>>
        </div>
    </div>
    <div style="padding:5px 10px;border:1px solid #cccccc;width:740px;border-radius:5px;overflow:hidden">
        <img src="/img/nermals_favorite_layout.png" style="float:left;height:85px;margin-right:15px">
        <div style="float:left;width:450px">
            <strong style="display:block;margin:0 0 5px">Cosmic Panda (BETA)</strong>
            - The layout from YouTube 2012-2013<br>
            - Big Focus on Videos and Playlists<br>
            - Very Consistent across different channels<br>
            - Easy to manage
        </div>
        <div style="float:right">
            <input class="search_button" type="submit" style="padding:34px 4px" name="switch_3" value="Switch!"<? if ($Info["channel_version"] == 3) : ?> disabled<? else : ?>onclick="return confirm('Are you sure you want to switch? Your current channel colors and settings will be reset!')"<? endif ?>>
        </div>
    </div>
</form>