<div class="in_box ib_col user_section <? if ($Profile["custom_d"] == 1 && $Is_OWNER) : ?>hddn<? endif ?>" id="cu_l" module="cu_l">
    <div class="box_title">
        <?= $Profile["displayname"] ?>
        <? if ($Is_OWNER) : ?>
            <div style="float: right;position:relative;top:2px;word-spacing:-4px;cursor:pointer">
                <? if ($Is_OWNER) : ?>
                    <form action="/user/<?= $_USER->displayname ?>" method="POST" style="display:inline-block" onsubmit="document.getElementById('bbcodeval1').value = document.getElementById('bbtextarea1').value">
                        <div style="margin-right:10px" id="savebbcode1link">
                            <a href="javascript:void(0)" onclick="$('#savebbcode1link').css('display','none');$('#bbcustom1').css('display','none');$('#bbtextarea1').css('display','block');$('#save_bb1').css('display','inline-block');">Edit</a>
                        </div>
                        <input type="hidden" name="bbcode" id="bbcodeval1">
                        <input type="submit" name="save_custom" id="save_bb1" value="Save Box" style="position:relative;bottom:3.5px;right:8px;display:none">
                    </form>
                <? endif ?>
                <img src="/img/uaa1.png" onclick="c_move_up('cu_l')"> <img src="/img/daa1.png" style="margin-right:2px" onclick="c_move_down('cu_l')"><img src="/img/laa0.png"> <img src="/img/raa1.png" onclick="move_hor('cu_l','cu_r')">
            </div>
        <? endif ?>
    </div>
    <div class="nm_mn" style="overflow:hidden">
        <div id="bbcustom1"><?= custombb(nl2br(htmlspecialchars($Profile["custom"]))) ?></div>
        <? if ($Is_OWNER) : ?>
            <textarea maxlength="1024" id="bbtextarea1" style="display:none;width:100%;margin:-5px;padding:5px;resize: vertical" rows="10"><?= $Profile["custom"] ?></textarea>
        <? endif ?>
    </div>
</div>