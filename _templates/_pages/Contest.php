<section class="h_l" id="contest">
    <? foreach ($Contest_Entries as $Entry) : ?>
        <div class="you_wnt">
            <div class="con_bx">
                <div style="border-bottom:1px solid #ccc;padding-bottom:4px;margin-bottom:8px"><? if (!empty($Entry["votes"])) : ?> <?= $Entry["votes"] ?> <? else : ?> 0 <? endif ?> Votes<? if ($_USER->logged_in && $Has_Voted == 0 && !isTorRequest()) : ?> | <a href="/ajax/df/upvote?v=<?= $Entry["url"] ?>">Upvote</a><? endif ?></div>
                <?= video_thumbnail($Entry["url"],"",145,100) ?>
                <a href="/watch?v=<?= $Entry["url"] ?>" style="font-size:16px;font-weight:bold"><?= $Entry["title"] ?></a>
                <div style="margin: 2px 0 0"><?= cut_string($Entry["description"],333) ?></div>
            </div>
        </div>
    <? endforeach ?>
</section>
<aside class="h_r" style="float:right">
    <div class="you_wnt">
        <div>
            <div style="font-weight: bold;font-size:17px;margin:0 0 5px">June Video Contest</div>
            Make a video, upload it, share it<br>
            Theme of June: <strong>VidLii</strong><br>
            <img src="/img/Vidlii6.png" width="136" height="88" style="border:3px double dodgerblue;margin: 5px 0 0">
        </div>
    </div>
    <div class="you_wnt">
        <div>
            <a href="/upload?c=j" style="font-weight:bold;font-size:17px;padding:5px;display:block">Enter Now!</a>
        </div>
    </div>
    <div class="you_wnt">
        <div>
            <strong style="display:block;text-align:center">What can I win?</strong>
            You can win $5 and a permanent feature on the contest page.
        </div>
    </div>
    <div class="wdg" style="margin-bottom: 6px">
        <div style="height:23px;border-bottom:1px solid #ccc"><span>Random Contest Video</span></div>
        <div style="padding:0;overflow:hidden;border:0">
        </div>
        <iframe allowfullscreen src="/embed?v=<?= $Contest_Video["url"] ?>&a=0" frameborder="0" width="320" height="240"></iframe>
    </div>
    <div style="border:1px solid #ccc;padding:5px;margin-bottom:11px;line-height: 17px">
        <a href="/watch?v=<?= $Contest_Video["url"] ?>" style="font-weight: bold"><?= $Contest_Video["title"] ?></a><br>
        <div style="font-size: 12px;margin-top:1px">
            From: <a href="/user/<?= $Contest_Video["displayname"] ?>"><?= $Contest_Video["displayname"] ?></a><br>
            Uploaded On: <?= date("M d, Y", strtotime($Contest_Video["uploaded_on"])) ?>
        </div>
    </div>
	<div class="you_wnt">
        <div style="background:#feb">
            <div style="border-bottom:1px solid #ccc;padding-bottom:4px">November 2017 Winner</div>
            <img src="/usfi/thmp/YBJrSxpIPOL.jpg" style="border:3px double dodgerblue;margin: 6px 0 0"><br>
            <a href="/watch?v=YBJrSxpIPOL" style="font-size:16px;font-weight:bold">GameMaker 1.1?
</a>
        </div>
    </div>
    <div class="you_wnt">
        <div style="background:#feb">
            <div style="border-bottom:1px solid #ccc;padding-bottom:4px">October 2017 Winner</div>
            <img src="/usfi/thmp/Z2CpB4rZXrg.jpg" style="border:3px double dodgerblue;margin: 6px 0 0"><br>
            <a href="/watch?v=Z2CpB4rZXrg" style="font-size:16px;font-weight:bold">Idaho Couch Potato Urban Exploration Parody
</a>
        </div>
    </div>
    <div class="you_wnt">
        <div style="background:#feb">
            <div style="border-bottom:1px solid #ccc;padding-bottom:4px">August 2017 Winner</div>
            <img src="/usfi/thmp/vB1OzZbMEr3.jpg" style="border:3px double dodgerblue;margin: 6px 0 0"><br>
            <a href="/watch?v=vB1OzZbMEr3" style="font-size:16px;font-weight:bold">Food</a>
        </div>
    </div>
    <div class="you_wnt">
        <div style="background:#feb">
            <div style="border-bottom:1px solid #ccc;padding-bottom:4px">July 2017 Winner</div>
            <img src="/usfi/thmp/HmGDuRDwOew.jpg" style="border:3px double dodgerblue;margin: 6px 0 0"><br>
            <a href="/watch?v=HmGDuRDwOew" style="font-size:16px;font-weight:bold">Making my Dogs Howl</a>
        </div>
    </div>
    <div class="you_wnt">
        <div style="background:#feb">
            <div style="border-bottom:1px solid #ccc;padding-bottom:4px">June 2017 Winner</div>
            <img src="/usfi/thmp/ZAVZesbypfo.jpg" style="border:3px double dodgerblue;margin: 6px 0 0"><br>
            <a href="/watch?v=ZAVZesbypfo" style="font-size:16px;font-weight:bold">Jump</a>
        </div>
    </div>
</aside>