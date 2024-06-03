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
    .inbox_bulk_actions .search_button, #inblk_form .search_button {
        border-radius: 0;
        padding: 3px 10px !important;
    }
</style>
<div class="inbox_bulk_actions">
    <img src="/img/inbox_arrow.png"><button id="inblki_accept" class="in_bulk search_button" disabled>Accept</button><button id="inblki_decline" class="in_bulk search_button" disabled>Decline</button><button id="inblki_read" class="in_bulk search_button" disabled>Mark Read</button><button id="inblki_unread" class="in_bulk search_button" disabled>Mark Unread</button>
</div>
<form id="inblk_form" method="post" action="/ajax/inbox_actions">
<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="inbox_in">
    <tr class="inbox_seperation">
        <td style="width:22px"><input style="position:relative;left:1px" type="checkbox" id="selectAllPM"></td>
        <td style="width:18%">From</td>
        <td width="66.3%">Subject</td>
        <td style="width:12%">Date</td>
    </tr>
    <? $Count = 0 ?>
    <? if (count($Inbox) > 0) : ?>
    <? foreach ($Inbox as $Section) : ?>
        <? $Count++ ?>
        <tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" id="invite_<?= $Section["username"] ?>" type="se" inbox="<?= $Section["id"] ?>" seen="<?= $Section["seen"] ?>" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
            <td>
                <input type="checkbox" style="position:relative;left:1px" value="<?= $Section["id"] ?>" name="selectedPM[]">
            </td>
            <td>
                <a href="/user/<?= $Section["displayname"] ?>" onclick="event.stopPropagation()"><?= $Section["displayname"] ?></a>
            </td>
            <td>
                <a href="javascript:void(0)" class="in_sub"><?= $Section["displayname"] ?> sent you a friend invite</a>
            </td>
            <td align="left">
                <span><?= get_date($Section["sent_on"]) ?></span>
            </td>
        </tr>
        <tr class="in_message hddn" id="i_<?= $Section["id"] ?>" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
            <td colspan="2" align="middle" valign="middle">
                <?= user_avatar2($Section["displayname"],66,66,$Section["avatar"]) ?>
            </td>
            <td colspan="2" valign="top">
                I sent you a friend invite and I would like you to accept it.<br><br><br>
                <button type="button" onclick="add_friend_in('<?= $Section["username"] ?>',<?= $Section["id"] ?>)">Accept Invite</button> <button type="button" onclick="if (confirm('Are you sure you want to deny this friend request?')) { deny_friend_in('<?= $Section["username"] ?>',<?= $Section["id"] ?>) }">Decline Invite</button>
            </td>
        </tr>
    <? endforeach ?>
    <? else : ?>
        <tr>
            <td colspan="4" align="center" style="font-size:19px;padding:55px">You don't seem to have any friend requests.</td>
        </tr>
    <? endif ?>
</table>
    <input type="hidden" id="inblk_action" name="action" value="" />
    <input type="hidden" name="page" id="inbox_page" value="<? if (isset($_GET["p"])) : ?><?= (int)$_GET["p"] ?><? else : ?>1<? endif ?>">
</form>
<? if (count($Inbox) > 0) : ?>
    <div style="background:#e2e2e2;padding:5px;font-size:13px;word-spacing:4px;font-weight:bold" id="in_pag">
        <? $_PAGINATION->new_show(null,"page=invites") ?>
    </div>
<? endif ?>