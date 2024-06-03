<h4>Partner Settings</h4>
<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
    <img src="/img/clp01.png">
    <span class="u_sct_hd">Watch Page Banner</span>
</div>
<div style="display:block;position:relative;left:6.5px">
    <form action="/partner_settings" method="POST" enctype="multipart/form-data" id="watch_page_uploader">
        <div style="width:50%;float:left;padding-right:10px;margin-right:10px;<? if ($Has_Banner) : ?>border-right:1px solid #ccc<? endif ?>">
            <div style="color:#303030;font-size:13px;margin-bottom: 12px">
                Your Watch Page Banner appears next to every of your videos.<br>
                Max File Size: <strong>300KB</strong><br>
                Exact Resolution: <strong>340x50</strong>
            </div>
            <? if (!$Has_Banner) : ?>
            <input type="file" name="watch_page_banner">
            <input type="submit" name="submit" value="Upload">
            <? else : ?>
            <input type="submit" name="delete" value="Delete">
            <? endif ?>
        </div>
        <? if ($Has_Banner) : ?>
        <div style="float:left">
            <img src="/usfi/wbner/<?= $_USER->username ?>.png" style="width:340px;height:50px;position:relative;left:40px;left:4px;top:15px">
        </div>
        <? endif ?>
    </form>
</div>
<div style="clear:both"></div>
<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
    <img src="/img/clp00.png">
    <span class="u_sct_hd">Channel Page Banner</span>
</div>
<div style="display:none;position:relative;left:6.5px">
	<? require_once("Banner.php"); ?>
</div>
<div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
    <img src="/img/clp00.png">
    <span class="u_sct_hd">Adsense</span>
</div>
<div style="display:none;position:relative;left:6.5px">
    <div style="color:#303030;font-size:13px;margin-bottom: 12px">
        <? if (empty($Info["adsense"])) : ?>
        Enter you Adsense publisher ID into the text field below.<br>
        Remember that for it to work, you need to have a independent adsense account that isn't only connected to YouTube!<br>
        Also please make sure that the code you enter is correct because you won't be able to change it again!
        <form action="/partner_settings" method="POST" style="margin-top:5px">
            <input type="text" name="adsense" maxlength="50" style="width:250px"> <input type="submit" value="Update" class="search_button" name="update_adsense">
        </form>
        <? else : ?>
        Your adsense publisher ID is: <strong><?= $Info["adsense"] ?></strong>
        <? endif ?>
    </div>
</div>