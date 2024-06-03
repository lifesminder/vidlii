<style>
    strong {
        margin: 15px 0 3px;
        font-size: 19px;
        display: block;
    }
    p {
        margin: 0 0 4px;
    }
</style>
<h1 class="pg_hd">Partnership</h1>
<div class="vc_l">
    <div class="vc_cats">
        <div>Help & Info</div>
        <ul>
            <li><a href="/help">Help Center</a></li>
            <li><a href="/developers">Developer API</a></li>
            <li style="font-weight:bold;cursor:default">Partnership</li>
            <li><a href="/copyright">Copyright</a></li>
            <li><a href="/guidelines">Community Guidelines</a></li>
        </ul>
    </div>
</div>
<div class="vc_r">
    <div class="partner_hd">
        <ul>
            <li><a href="javascript:void(0)" id="pa_1" class="pa_sel" onclick="switch_partner('overview')">Overview</a></li>
            <li><a href="javascript:void(0)" id="pa_2" onclick="switch_partner('benefits')">Partner Benefits</a></li>
            <li><a href="javascript:void(0)" id="pa_3" onclick="switch_partner('qualifications')">Qualifications & FAQ</a></li>
        </ul>
    </div>
    <div id="partner_main">
        <div class="partner_box">
            <div style="float:left;width:56%">
                <img src="/img/patne.png" style="width:100%;height:292px;">
            </div>
            <div style="float:left;width:36%;margin-left:45px;">
                <div style="font-size:18px;font-weight:bold;margin-bottom:20px">
                    Partner with VidLii
                </div>
                <div style="margin-bottom:33px">
                    You've got great videos and a growing audience. Let VidLii help you take it to the next level through our Partner Program.
                </div>
                <a <? if (!$_USER->logged_in) : ?>href="javascript:void(0)" onclick="alert('Please log in to apply for partner!')"<? elseif ($_USER->Is_Partner) : ?>href="javascript:void(0)" onclick="alert('You have already been accepted!')"<? elseif ($Application_Sent) : ?>href="javascript:void(0)" onclick="alert('You already sent an application!')"<? else : ?>href="/partners?signup"<? endif ?>class="yel_btn" style="padding: 7px 10px;font-size:17px">
                     Apply for Partner
                </a>
            </div>
        </div>
        <div class="wdg" style="float:left;width:48%;">
            <div style="height:23px"><span>Partner Benefits</span></div>
            <div style="padding: 12px;height:83px">
                <div>
                    There are many benefits to becoming a VidLii Partner. Learn about opportunities to earn money, gain deeper insight into your content, and reach more viewers with higher quality video playback options.
                </div>
            </div>
        </div>
        <div class="wdg" style="float:right;width:48%">
            <div style="height:23px"><span>Qualifications & FAQ</span></div>
            <div style="padding: 12px;height:83px">
                <div>
                    To qualify for the VidLii Partner program, you must meet some minimum criteria, including owning the copyrights and distribution rights for the video content that you upload. For a full list of qualifying criteria, check out this section before you apply.            </div>
            </div>
        </div>
    </div>
    <div id="partner_benefits" class="hddn">
        <table cellpadding="15">
            <tbody>
            <tr style="vertical-align: top">
                <td>
                    <h3>Monetization<img src="/img/money.gif" width="44" height="40" style="vertical-align: middle;position:relative;bottom:3px;left: 11px;"></h3>
                    <p>As a VidLii partner, you will be able to make money off your content.</p>
                    <ul>
                        <li>Connect your existing Adsense account to your VidLii account.</li>
                        <li>Choose of which of your videos you want to make money off of.</li>
                        <li>Put custom links to your website inside the video player.</li>
                    </ul>
                </td>
                <td>
                    <h3>Insight<img src="/img/chart.gif" width="44" height="40" style="vertical-align: middle;position:relative;bottom:3px;left: 11px;"></h3>
                    <p>Use our Insight analytics tools to optimize your existing content and create more targeted content to satisfy your audience and advertisers.</p>
                    <ul>
                        <li>Learn about your audience demographics, and what they are watching</li>
                        <li>Learn more about how your audience discovers your content</li>
                        <li>Compare how your content is doing against competition</li>
                        <li>Learn what content you have is the most popular &amp; engaging</li>
                        <li>Determine at a video level where your content is really successful, and where it falls flat</li>
                    </ul>
                </td>
            </tr>
            <tr style="vertical-align: top">
                <td>
                    <h3>Features<img src="/img/secret.gif" width="44" height="40" style="vertical-align: middle;position:relative;bottom:3px;left: 11px;"></h3>
                    <p>VidLii can help you enhance your content and brand.</p>
                    <ul>
                        <li>Add a custom banner image above your video description.</li>
                        <li>Upload higher file sizes and longer videos.</li>
                        <li>Give your video a custom thumbnail to get others to click on it.</li>
                    </ul>
                </td>
                <td>
                    <h3>Quality<img src="/img/check.gif" width="44" height="40" style="vertical-align: middle;position:relative;bottom:3px;left: 11px;"></h3>
                    <p>We will serve your videos reliably, and at the highest resolution possible.</p>
                    <ul>
                        <li>VidLii offers High Quality to it's users for particular content that merits a richer, lean-back viewing experience.</li>
                        <li>The player offers 16:9 aspect ratio</li>
                        <li>Join the ranks of our other high-quality content providers already in the Partner Program</li>
                    </ul>
                </td>
            </tr>
            </tbody></table>
    </div>
    <div id="partner_qualifications" class="hddn">
        <div style="margin-top: 13px">
            <div>To become a VidLii Partner, you must meet these minimum requirements:</div>
            <ul style="margin-left: 1em; margin-top: 12px;">
                <li>You create original videos suitable for online streaming.</li>
                <li>You own or have express permission to use and monetize the videos that you upload -- no exceptions.</li>
                <li>You regularly upload videos.</li>
            </ul>
            <div>Please note: all uploaded videos are subject to the VidLii Community Guidelines and Terms of Use.</div>
        </div>
        <div style="margin-top:12px;background: #ebece0;border-radius: 8px;border: 1px solid #dcddd2;padding: 12px 14px">
            <dl style="margin-top: 20px;">
                <h3 style="margin-bottom: 20px;">Partner Application FAQ</h3>
                <dt>Why should I become a VidLii Partner?</dt>
                <dd>For more information on the benefits of becoming a partner, please see the Partner Benefits page</dd>
                <dt>How long does the application process take?</dt>
                <dd>During the week it generally takes around 3 days while on the weekends you'll get a reply in around 1 day.</dd>
                <dt>How do VidLii Partners earn money?</dt>
                <dd>Our Partner Program is a revenue-sharing program that allows creators and producers of original content to earn money from their videos on VidLii. You can earn revenue from relevant advertisements that run against your videos using Adsense.</dd>
                <dt>How much money will I make?</dt>
                <dd>There are no guarantees under the VidLii Partner agreement about how much you will be paid.</dd>
                <dt>What type of ads will run on my content?</dt>
                <dd>The ads we display on your VidLii channel are determined automatically by the Adsense system. They are based on a number of contextual factors relating to your video, such as video category, for example.</dd>
                <dt>I applied to become a VidLii Partner, but was not accepted. Why not?</dt>
                <dd>Applications are reviewed for a variety of criteria, including but not limited to the size of your audience, country of residence, quality of content, your age, and consistency with our Community Guidelines and Terms of Use.</dd>
            </dl>
        </div>
    </div>
</div>
