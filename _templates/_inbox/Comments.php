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
    <img src="/img/inbox_arrow.png"><button id="inblkc_del" class="in_bulk search_button" disabled>Delete</button><button id="inblkc_read" class="in_bulk search_button" disabled>Mark Read</button><button id="inblkc_unread" class="in_bulk search_button" disabled>Mark Unread</button>
    <div class="inbox_search">
        <select onchange="change_comment_inbox()" id="comment_filter" style="border-radius:0;padding:3px;margin-right:5px">
            <option value="all"<? if (!isset($_GET["t"])) : ?> selected<? endif ?>>All</option>
            <option value="video"<? if (isset($_GET["t"]) && $_GET["t"] == 2) : ?> selected<? endif ?>>Video Comments</option>
            <option value="channel"<? if (isset($_GET["t"]) && $_GET["t"] == 3) : ?> selected<? endif ?>>Channel Comments</option>
            <option value="mention"<? if (isset($_GET["t"]) && $_GET["t"] == 4) : ?> selected<? endif ?>>Mentions</option>
            <option value="reply"<? if (isset($_GET["t"]) && $_GET["t"] == 5) : ?> selected<? endif ?>>Replies</option>
        </select>
    </div>
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
        <? if ($Section["type_name"] == "comment") : ?>
        <? $Count++ ?>
        <tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" inbox="<?= $Section["id"] ?>" seen="<?= $Section["seen"] ?>" type="c" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
            <td>
                <input type="checkbox" style="position:relative;left:1px" value="c_<?= $Section["id"] ?>" name="selectedPM[]">
            </td>
            <td>
                <a href="/user/<?= $Section["displayname"] ?>" onclick="event.stopPropagation()"><?= $Section["displayname"] ?></a>
            </td>
            <td>
                <a href="javascript:void(0)" class="in_sub"><?= $Section["displayname"] ?> has commented on: <?= htmlspecialchars(cut_string($Section["title"],19)) ?></a>
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
                <?= nl2br($Section["comment"]) ?>
                <div style="margin-top:13px;overflow:hidden">
                    <div style="float:left;margin:0 5px 0 0">
                    <?= video_thumbnail($Section["url"],$Section["length"],99,69) ?>
                    </div>
                    <a href="/watch?v=<?= $Section["url"] ?>" style="font-weight:bold"><?= $Section["title"] ?></a>
                    <div style="width:328px;font-size:12px"><?= htmlspecialchars(cut_string($Section["description"],200)) ?></div>
                </div>
            </td>
        </tr>
        <? elseif ($Section["type_name"] == "mention") : ?>
            <? if ($Section["under_type"] == "0") : ?>
                    <? $Count++ ?>
                    <tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" inbox="<?= $Section["id"] ?>" seen="<?= $Section["seen"] ?>" type="mv" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
                        <td>
                            <input type="checkbox" value="mv_<?= $Section["id"] ?>" style="position:relative;left:1px" name="selectedPM[]">
                        </td>
                        <td>
                            <a href="/user/<?= $Section["displayname"] ?>"><?= $Section["displayname"] ?></a>
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="in_sub"><?= $Section["displayname"] ?> has mentioned you on a video</a>
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
                            <?= nl2br($Section["comment"]) ?>
                            <div style="margin-top:13px;overflow:hidden">
                                <div style="float:left;margin:0 5px 0 0">
                                    <?= video_thumbnail($Section["url"],$Section["length"],99,69) ?>
                                </div>
                                <a href="/watch?v=<?= $Section["url"] ?>" style="font-weight:bold"><?= $Section["title"] ?></a>
                                <div style="width:328px;font-size:12px"><?= htmlspecialchars(cut_string($Section["description"],200)) ?></div>
                            </div>
                        </td>
                    </tr>
            <? elseif ($Section["under_type"] == "1") : ?>
                    <? $Count++ ?>
                    <tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" inbox="<?= $Section["id"] ?>" seen="<?= $Section["seen"] ?>" type="mcn" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
                        <td>
                            <input type="checkbox" value="mcn_<?= $Section["id"] ?>" style="position:relative;left:1px" name="selectedPM[]">
                        </td>
                        <td>
                            <a href="/user/<?= $Section["displayname"] ?>"><?= $Section["displayname"] ?></a>
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="in_sub"><?= $Section["displayname"] ?> has mentioned you on <? if ($_USER->username !== $Section["title"]) : ?>a channel<? else : ?>your channel<? endif ?></a>
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
                            <?= nl2br($Section["comment"]) ?>
                            <div style="margin-top:13px;overflow:hidden">
                                On <a href="/user/<?= $Section["title"] ?>" style="font-weight:bold"><?= $Section["title"] ?></a>
                            </div>
                        </td>
                    </tr>
            <? endif ?>

        <? elseif ($Section["type_name"] == "reply") : ?>
                <? $Count++ ?>
                <tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" inbox="<?= $Section["id"] ?>" seen="<?= $Section["seen"] ?>" type="mrp" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
                    <td>
                        <input type="checkbox" value="mrp_<?= $Section["id"] ?>" style="position:relative;left:1px" name="selectedPM[]">
                    </td>
                    <td>
                        <a href="/user/<?= $Section["displayname"] ?>"><?= $Section["displayname"] ?></a>
                    </td>
                    <td>
                        <a href="javascript:void(0)" class="in_sub"><?= $Section["displayname"] ?> has replied to your comment</a>
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
                        <?= nl2br($Section["comment"]) ?>
                        <div style="margin-top:13px;overflow:hidden">
                            <div style="float:left;margin:0 5px 0 0">
                                <?= video_thumbnail($Section["url"],$Section["length"],99,69) ?>
                            </div>
                            <a href="/watch?v=<?= $Section["url"] ?>" style="font-weight:bold"><?= $Section["title"] ?></a>
                            <div style="width:328px;font-size:12px"><?= htmlspecialchars(cut_string($Section["description"],200)) ?></div>
                        </div>
                    </td>
                </tr>
        <? elseif ($Section["type_name"] == "channel") : ?>
                <? $Count++ ?>
                <tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" inbox="<?= $Section["id"] ?>" seen="<?= $Section["seen"] ?>" type="zz" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
                    <td>
                        <input type="checkbox" value="zz_<?= $Section["id"] ?>" style="position:relative;left:1px" name="selectedPM[]">
                    </td>
                    <td>
                        <a href="/user/<?= $Section["displayname"] ?>"><?= $Section["displayname"] ?></a>
                    </td>
                    <td>
                        <a href="javascript:void(0)" class="in_sub"><?= $Section["displayname"] ?> has commented on your channel</a>
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
                        <?= nl2br($Section["comment"]) ?>
                        <div style="margin-top:13px;overflow:hidden">
                           <a href="/user/<?= $_USER->displayname ?>" style="font-weight:bold">Go to your channel</a>
                        </div>
                    </td>
                </tr>
        <? endif ?>
    <? endforeach ?>
    <? else : ?>
        <tr>
            <td colspan="4" align="center" style="font-size:19px;padding:55px">You don't seem to have any new comments on your videos.</td>
        </tr>
    <? endif ?>
</table>
    <input type="hidden" id="inblk_action" name="action" value="" />
    <input type="hidden" name="page" id="inbox_page" value="<? if (isset($_GET["p"])) : ?><?= (int)$_GET["p"] ?><? else : ?>1<? endif ?>">
    <input type="hidden" name="type" id="inbox_type" value="<? if (isset($_GET["t"])) : ?><?= (int)$_GET["t"] ?><? else : ?>1<? endif ?>">
</form>
<? if (count($Inbox) > 0) : ?>
    <div style="background:#e2e2e2;padding:5px;font-size:13px;word-spacing:4px;font-weight:bold">
        <? if (!isset($_GET["t"])) : ?>
            <? $_PAGINATION->new_show(null,"page=comments") ?>
        <? else : ?>
            <? $_PAGINATION->new_show(null,"page=comments&t=".(int)$_GET["t"]) ?>
        <? endif ?>
    </div>
<? endif ?>