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
    <img src="/img/inbox_arrow.png"><button id="inblkr_accept" class="in_bulk search_button" disabled>Accept</button><button id="inblkr_decline" class="in_bulk search_button" disabled>Decline</button><button id="inblkr_read" class="in_bulk search_button" disabled>Mark Read</button><button id="inblkr_unread" class="in_bulk search_button" disabled>Mark Unread</button>
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
        <tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" id="rsp_<?= $Section["id"] ?>" inbox="<?= $Section["id"] ?>" type="rsp" seen="<?= $Section["seen"] ?>" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
            <td>
                <input type="checkbox" style="position:relative;left:1px" value="<?= $Section["id"] ?>" name="selectedPM[]">
            </td>
            <td>
                <a href="/user/<?= $Section["displayname"] ?>" onclick="event.stopPropagation()"><?= $Section["displayname"] ?></a>
            </td>
            <td>
                <a href="javascript:void(0)" class="in_sub"><?= $Section["displayname"] ?> submitted a video response</a>
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
                <?
                    $Response_To = $DB->execute("SELECT url, title FROM videos WHERE url = :URL", true, [":URL" => $Section["response_to"]]);

                    if ($DB->RowNum == 1) {
                        $Title = cut_string($Response_To["title"], 40);
                        $URL   = "/watch?v=".$Response_To["url"];
                    } else {
                        $Title = "Deleted Video";
                        $URL   = "/videos";
                    }

                ?>
                Please accept my video response to your video: <a href="<?= $URL ?>"><?= $Title ?></a>.<br>
                <div style="margin-top:13px;overflow:hidden">
                    <div style="float:left;margin:0 5px 0 0">
                        <?= video_thumbnail($Section["url"],$Section["length"],99,69) ?>
                    </div>
                    <a href="/watch?v=<?= $Section["url"] ?>" style="font-weight:bold"><?= $Section["title"] ?></a>
                    <div style="width:328px;font-size:12px"><?= cut_string($Section["description"],200) ?></div>
                </div><br>
                <button type="button" onclick="accept_response(<?= $Section["id"] ?>)">Accept Response</button> <button type="button" onclick="if (confirm('Are you sure you want to deny this video response?')) { deny_response(<?= $Section["id"] ?>) }">Decline Response</button>
            </td>
        </tr>
    <? endforeach ?>
    <? else : ?>
        <tr>
            <td colspan="4" align="center" style="font-size:19px;padding:55px">No one has submitted any responses to your videos.</td>
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