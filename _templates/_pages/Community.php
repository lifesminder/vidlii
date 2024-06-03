<div class="h_l">
    <div class="wdg" style="margin-bottom: 6px">
        <div style="height:23px;border-bottom:1px solid #ccc"> <? if (!isset($_GET["v"])) : ?><span>Randomly Selected Video</span><? elseif ($_GET["v"] == "l") : ?><span>Latest Uploaded Video</span><? elseif ($_GET["v"] == "b") : ?><span>Being Watched Right Now</span><? endif ?>
            <div class="wdg_sel" style="font-size: 13px;position:relative;top:2.5px">
                <? if (!isset($_GET["v"])) : ?><strong>Random</strong><? else : ?><a href="/community">Random</a><? endif ?> |
                <? if (isset($_GET["v"]) && $_GET["v"] == "l") : ?><strong>Latest</strong><? else : ?><a href="/community?v=l">Latest</a><? endif ?> |
                <? if (isset($_GET["v"]) && $_GET["v"] == "b") : ?><strong>Being Watched Now</strong><? else : ?><a href="/community?v=b">Being Watched Now</a><? endif ?>
            </div>
        </div>
        <div style="padding:0;overflow:hidden;border:0">
        </div>
        <div style="height:380px;border:0;border-radius:0;padding:0">
            <? require_once "_templates/_layout/player.php" ?>
        </div>
    </div>
    <div style="border:1px solid #ccc;padding:5px;margin:10px 0 11px;line-height: 17px">
        <a href="/watch?v=<?= $Random_Video["url"] ?>" style="font-weight: bold"><?= $Random_Video["title"] ?></a><br>
        <div style="font-size: 12px;margin-top:1px">
        From: <a href="/user/<?= $Random_Video["displayname"] ?>"><?= $Random_Video["displayname"] ?></a><br>
        Uploaded On: <?= date("M d, Y", strtotime($Random_Video["uploaded_on"])) ?>
        </div>
    </div>
    <div class="wdg">
        <div style="height:23px;background:#fbaa9d"><span>Recently Favorited Videos</span></div>
        <div>
            <div class="v_v_bx">
                <? foreach ($Favorites as $Favorite) : ?>
                    <div>
                        <div class="th">
                            <div class="th_t"><?= $Favorite["length"] ?></div>
                            <a href="/watch?v=<?= $Favorite["url"] ?>"><img class="vid_th" <?= $Favorite["thumbnail"] ?> width="140" height="88"></a>
                        </div>
                        <a href="/watch?v=<?= $Favorite["url"] ?>" class="ba"><?= $Favorite["title"] ?></a>
                        <div class="vw s"><?= number_format($Favorite["views"]) ?> views</div>
                        <a href="/user/<?= $Favorite["displayname"] ?>" class="ch_l s"><?= $Favorite["displayname"] ?></a>
                        <div class="s_r"><?= show_ratings($Favorite,14,13) ?></div>
                    </div>
                <? endforeach ?>
            </div>
        </div>
    </div>
    <div class="wdg">
        <div style="height:23px;background:#fbea9f"><span>Feature Suggestions</span></div>
        <div>
            <? if ($_USER->logged_in) : ?>
            <div style="padding-bottom:11px;margin-bottom:4px;border-bottom:1px solid #ccc">
            <form action="/community" method="POST">
                <input type="text" name="s_title" required maxlength="100" placeholder="Feature Title" style="margin-bottom: 5px;border-radius:0;width: 250px;font-weight:bold">
                <textarea rows="5" name="s_description" required maxlength="1000" style="width:99%;resize:vertical;margin-bottom:2px;" placeholder="Explain it in greater detail here..."></textarea>
                <input type="submit" value="Submit the Request!" name="submit_suggestion">
            </form>
            </div>
            <? endif ?>
            <div id="feature_suggestions">
            <? if ($Has_Requested) : ?>
                <style>
                    .y_avt {
                        border-color: #b8ab2a !important;
                    }
                </style>
                <div style="padding-top:0">
                    <div style="font-weight:bold;margin-bottom:5px">Your Suggestion:</div>
                    <div style="float:left;margin:0 5px 0 0"><?= user_avatar2($Your_Request["displayname"],77,77,$Your_Request["avatar"],"y_avt") ?></div>
                    <div style="float:left;width:553px;position:relative;bottom:1px">
                        <strong style="display:block"><?= $Your_Request["title"] ?></strong>
                        <?= nl2br($Your_Request["description"]) ?>
                        <div style="font-size: 12px"><a href="/ajax/df/remove_suggestion">Remove Suggestion</a></div>
                    </div>
                </div>
            <? endif ?>
            <? foreach ($Requests as $Request) : ?>
                <div>
                    <div style="float:left;margin:0 5px 0 0"><?= user_avatar2($Request["displayname"],77,77,$Request["avatar"]) ?></div>
                    <div style="float:left;width:553px;position:relative;bottom:1px">
                        <strong style="display:block"><?= $Request["title"] ?></strong>
                        <?= nl2br($Request["description"]) ?>
                        <div style="font-size: 12px">Suggestion by: <a href="/user/<?= $Request["displayname"] ?>"><?= $Request["displayname"] ?></a></div>
                    </div>
                </div>
            <? endforeach ?>
            </div>
        </div>
    </div>
</div>
<div class="h_r">
</div>
<div class="h_r">
    <!--<div class="wdg">
        <div style="height:23px"><span>Support VidLii</span></div>
        <div style="text-align:center">
            <div style="font-size:13px;margin:0 0 5px"><strong>Donate</strong> to let us be able to improve video quality and add more awesome features</div>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_donations">
                <input type="hidden" name="business" value="supapowii@gmail.com">
                <input type="hidden" name="lc" value="US">
                <input type="hidden" name="item_name" value="VidLii.com">
                <input type="hidden" name="no_note" value="0">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form><br>
            <div style="margin-bottom:3px">Or <strong>donate bitcoin directly</strong>:</div>
            <strong style="display:block">17CTYVZhsBiySwf4sAtGUPVt1<br>SuW7vkt5X</strong>
        </div>
    </div>-->
<!--<iframe src="https://discord.com/widget?id=754064080255451153&theme=dark" width="320" height="500" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>-->
<a class="twitter-timeline" data-height="600" data-dnt="true" href="https://twitter.com/VidLii">Tweets by VidLii</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
	<? if (1 == 2) : ?>
    <div class="wdg">
        <div style="height:23px;background:#d4c9fb"><span>Current #1 in Contest</span></div>
        <div style="text-align: center">
            <a href="/watch?v=<?= $Contest["url"] ?>" style="display:block;font-size:17px;margin-bottom:3px;font-weight:bold"><?= $Contest["title"] ?></a>
            <?= video_thumbnail($Contest["url"],"",175,115) ?><br>
            <a href="/upload?c=j">Enter Now</a> | <a href="/contest">Other Entries</a>
        </div>
    </div>
    <? endif ?>
    <div class="wdg">
        <div style="height:23px;background:#bafbc7"><span>Recent Comments</span></div>
        <div id="recent_comments">
            <? foreach ($Comments as $Comment) : ?>
                <div>
                    On Video: <a href="/watch?v=<?= $Comment["url"] ?>"><?= cut_string($Comment["title"],25) ?></a><br>
                    By: <a href="/user/<?= $Comment["displayname"] ?>"><?= $Comment["displayname"] ?></a><br>
                    <?= user_avatar2($Comment["displayname"],40,40,$Comment["avatar"]) ?>
                    <div><?= limit_text($Comment["comment"], 125) ?></div>
                </div>
            <? endforeach ?>
        </div>
    </div>
</div>
