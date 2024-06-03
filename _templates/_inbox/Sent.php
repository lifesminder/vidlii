<style>
    .in_sct {
        height: 31px
    }
    textarea {
        overflow: hidden;
    }
    .in_sct {
        text-decoration: none;
        transition: background-color 50ms
    }
    .in_sct:hover {
        background-color: #f6f6f6 !important;
        transition: background-color 50ms
    }
    .in_message {
        transition: background-color 50ms
    }
    .in_sct:hover + .in_message {
        background-color: #f6f6f6 !important;
        transition: background-color 50ms
    }
    .in_sct > td:nth-of-type(3) > a {
        text-decoration: none !important;
    }
    .in_message td:nth-of-type(2) {
        cursor: auto !important;
    }
</style>
<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="inbox_in">
    <tr class="inbox_seperation">
        <td style="width:22px"><input style="position:relative;left:1px" type="checkbox"></td>
        <td style="width:18%">To</td>
        <td width="66.3%">Subject</td>
        <td style="width:12%">Date</td>
    </tr>
    <? $Count = 0 ?>
    <? if (count($Inbox) > 0) : ?>
    <? foreach ($Inbox as $Section) : ?>
        <? $Count++ ?>
        <tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" inbox="<?= $Section["id"] ?>" seen="<?= $Section["seen"] ?>" type="nt" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
            <td>
                <input type="checkbox" style="position:relative;left:1px" onclick="event.stopPropagation()">
            </td>
            <td>
                <a href="/user/<?= $Section["displayname"] ?>" onclick="event.stopPropagation()"><?= $Section["displayname"] ?></a>
            </td>
            <td>
                <a href="javascript:void(0)" class="in_sub"><? if (empty($Section["subject"])) : ?><?= cut_string($Section["message"],25) ?><? else : ?><?= $Section["subject"] ?><?endif?></a>
            </td>
            <td align="left">
                <span><?= get_date($Section["date_sent"]) ?></span>
            </td>
        </tr>
        <tr class="in_message hddn" id="i_<?= $Section["id"] ?>" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
            <td colspan="2" align="middle" valign="middle">
                <?= user_avatar2($Section["displayname"],66,66,$Section["avatar"]) ?>
            </td>
            <td colspan="2" valign="top">
                <?= nl2br($Section["message"]) ?>
            </td>
        </tr>
    <? endforeach ?>
    <? else : ?>
        <tr>
            <td colspan="4" align="center" style="font-size:19px;padding:55px">You haven't sent any private messages.</td>
        </tr>
    <? endif ?>
</table>
<? if (count($Inbox) > 0) : ?>
    <div style="background:#e2e2e2;padding:5px;font-size:13px;word-spacing:4px;font-weight:bold">
        <? $_PAGINATION->new_show(null,"page=sent") ?>
    </div>
<? endif ?>