<style>
    p {
        margin: 0 0 4px;
    }
    .cgul li {
        margin-bottom:3px
    }
</style>
<h1 class="pg_hd">Developers</h1>
<div class="vc_l">
    <div class="vc_cats">
        <div>Help & Info</div>
        <ul>
            <li><a href="/help">Help Center</a></li>
            <li style="font-weight:bold;cursor:default">Developer API</li>
            <li><a href="/partners">Partnership</a></li>
            <li><a href="/copyright">Copyright</a></li>
            <li><a href="/guidelines">Community Guidelines</a></li>
        </ul>
    </div>
</div>
<div class="vc_r" style="margin-bottom:0">
    <img src="/img/vidlii.png" style="width:122px;height:52px;float:left;margin-right:8px"><strong>VidLii anywhere, any time</strong>. The VidLii API allows you to integrate VidLii's video content and functionality into your website, software application, or device.
    <div style="clear:both"></div>
    <div style="margin-top: 33px">
        <img src="/img/chart_api.gif" style="float:left;margin-right:8px"><strong style="font-size:16px">Data API</strong><div style="font-size:14px">The VidLii Data API allows you to <strong>easily</strong> retrieve information of different parts on the website for your own use.</div>
    </div>
    <div style="clear:both"></div>
    <div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
        <img src="/img/clp00.png">
        <span class="u_sct_hd">User Data</span>
    </div>
    <div style="display:none">
        <div style="margin-bottom:3px;padding-bottom:3px">Example Call: <a href="/api?ty=user&ta=VidLii">/api?ty=user&ta=VidLii</a></div>
        <div style="border:1px solid #dddddd;padding:5px;margin-bottom:4px">
            <strong style="display:block">Output:</strong>
            <pre style="margin:0">
{
    "r":"success",
    "username":"VidLii",
    "registered":"2017-05-23 02:15:12",
    "last_login":false,
    "videos_watched":"1764",
    "channel_views":"2762",
    "video_views":"366",
    "videos":"2",
    "subscribers":false,
    "subscriptions":"19",
    "friends":"71",
    "channel_comments":"197",
    "featured_channels":false,
    "partnered":"1",
    "channel_version":"2",
    "country":false,
    "title":"",
    "description":"",
    "tags":"",
    "avatar":"/usfi/avt/qBXfvJKwfJ1.jpg"
}</pre>
        </div>
        <strong>Important:</strong> Getting the result: "false" means that this user has hidden this value from his / her channel.<br>
    </div>
    <div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
        <img src="/img/clp00.png">
        <span class="u_sct_hd">Video Data</span>
    </div>
    <div style="display:none">
        <div style="margin-bottom:3px;padding-bottom:3px">Example Call: <a href="/api?ty=video&ta=xsc2P_KnbWI">/api?ty=video&ta=xsc2P_KnbWI</a></div>
        <div style="border:1px solid #dddddd;padding:5px;margin-bottom:4px">
            <strong style="display:block">Output:</strong>
            <pre style="margin:0">
{
    r: "success"
    url: "EjdZOfJNwJE"
    title: "Vidlii."
    description: "the first time i'm doing any unironic editing for something with my new editing software."
    tags: "Vidlii, Vlare, formidable, Redexec, HeXa, Thebestbrosgamershow, bestbrosgamershow, Killsabyte, edray1416, edray, WACV, CL21, Craftinglord21, jreviews, jaycommitsmassmurder"
    category: "Film & Animation"
    upload_date: "2019-03-27 23:15:34"
    upload_by: "JTVLive"
    duration: "42"
    ranking_views: "40"
    display_views: "68"
    watchtime_minutes: "52"
    comment_num: "2"
    response_num: "5"
    favorite_num: "2"
    featured: "1"
    ratings: "0,0,0,0,3"
    ads_enabled: "1"
}</pre>
        </div>
        <strong>Optional Parameters:</strong><br>
        "&o=comments" returns the comments instead<br>
        "&o=responses" returns the video responses instead<br>
        "&limit=16,0" sets the amount and offset of returned comments/video responses (Default is "16, Offset 0")
    </div>
    <div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
        <img src="/img/clp00.png">
        <span class="u_sct_hd">VidLii Data</span>
    </div>
    <div style="display:none">
        <div style="margin-bottom:3px;padding-bottom:3px">Example Call: <a href="/api?ty=Vidlii&ta=featured">/api?ty=Vidlii&ta=featured</a></div>
        <div style="border:1px solid #dddddd;padding:5px;margin-bottom:4px">
            <strong style="display:block">Output:</strong>
            <pre style="margin:0">
{
    "0":{
        "url":"xVngY44FiWX",
        "title":"Sonic the hedgehog music: green hill zone",
        "views":"6",
        "upload_by":"thatonecoolguy",
        "upload_date":"2017-08-14 00:00:00"
    },
    "1":{
        "url":"thKTgErNMnT",
        "title":"Howto Revert to Classic Google Layout (Chrome & Firefox)",
        "views":"93",
        "upload_by":"11ryanc",
        "upload_date":"2017-08-12 21:14:10"
    },
    "2":{
        "url":"8h1e2t8l1xV",
        "title":"Vidlii Time Capsule - Submit Vidlii Screenshots and Videos!",
        "views":"147",
        "upload_by":"AM",
        "upload_date":"2017-08-11 01:14:40"
    },
    "3":{
        "url":"QU10coLMwyg",
        "title":"Test Animation (inspired By Alan Becker)",
        "views":"151",
        "upload_by":"NermalCat79",
        "upload_date":"2017-08-06 18:10:48"
     }
}</pre>
        </div>
        <strong>Possible Targets (&ta=):</strong><br>
        "featured": Returns the 16 most recent featured videos<br>
        "search": Returns the 16 most relevant results according to what you searched for. (&o=)<br>
        "watched": Returns the 16 most recently watched videos.<br>
        "new": Return the 16 most recently uploaded videos. Set (&o=) to a username and it will return this users most recent videos.

    </div>
</div>