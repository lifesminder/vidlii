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
    textarea {
        border: 1px solid #d5d5d5;
        padding: 3px 4px;
        border-radius: 4px;
        outline: 0;
        font-family: Arial;
        font-size: 13px;
        width: 98.5%;
        resize: vertical;
        border-radius: 0;
        width:95.5%;
        min-height: 32px;
    }
    textarea:hover {
        border: 1px solid #ababab;
    }
    textarea:focus {
        border: 1px solid #9d9efd;
    }
</style>
<div class="inbox_bulk_actions">
    <img src="/img/inbox_arrow.png"><button id="inblk_del" class="in_bulk search_button" disabled>Delete</button><button id="inblk_read" class="in_bulk search_button" disabled>Mark Read</button><button id="inblk_unread" class="in_bulk search_button" disabled>Mark Unread</button>
    <div class="inbox_search">
        <form action="/inbox?page=messages" method="POST">
            <input type="text" maxlength="64" name="search_input" required<? if (isset($_POST["search_input"])) : ?> value="<?= $_POST["search_input"] ?>"<? endif ?> placeholder="Search Messages"<? if (count($Inbox) == 0) : ?> disabled<? endif ?>><button name="search_inbox" class="search_button" type="submit"<? if (count($Inbox) == 0) : ?> disabled<? endif ?>>Search</button>
        </form>
    </div>
</div>
<form id="inblk_form" method="post" action="/ajax/inbox_actions">
	<table cellpadding="5px" cellspacing="0" border="0" style="width:100%" id="inbox_in">
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
			<tr class="in_sct<? if ($Section["seen"] == 0) : ?> in_not<? endif ?>" inbox="<?= $Section["id"] ?>" seen="<?= $Section["seen"] ?>" type="m" <? if ($Count % 2) : ?>style="background:#ededed"<? endif ?>>
				<td>
					<input type="checkbox" style="position:relative;left:1px" name="selectedPM[]" value="<?=$Section["id"]?>">
				</td>
				<td>
					<a href="/user/<?= $Section["displayname"] ?>" onclick="event.stopPropagation()"><?= $Section["displayname"] ?></a>
				</td>
				<td>
					<a href="javascript:void(0)" class="in_sub"><? if (empty($Section["subject"])) : ?><?= htmlspecialchars(cut_string($Section["message"],25)) ?><? else : ?><?= htmlspecialchars($Section["subject"]) ?><?endif?></a>
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
					<?= nl2br(htmlspecialchars($Section["message"])) ?>
					<div class="inbox_reply_section">
						<textarea maxlength="5000" rows="11" placeholder="Reply to this message..."></textarea>
						<input type="hidden" class="irs_user" value="<?= $Section["displayname"] ?>" />
						<input type="hidden" class="irs_subject" value="<?= htmlspecialchars("RE: ".substr($Section["subject"],0,mb_strlen($Section["subject"])), ENT_QUOTES) ?>" />
						
						<div class="irs_buttons">
							<a class="irs_reply" href="/inbox?page=send_message&to=<?= $Section["displayname"] ?>&s=<?= urlencode("RE: ".substr($Section["subject"],0,mb_strlen($Section["subject"]))) ?>"><button class="search_button" style="padding:2.5px 9px !important;">Reply</button></a>
							
							<button type="button" class="irs_delete search_button" style="padding:2.5px 9px !important;">Delete</button>
							<button type="button" class="irs_cancel search_button" style="padding:2.5px 9px !important;">Cancel</button>
						</div>
					</div>
				</td>
			</tr>
		<? endforeach ?>
		<? else : ?>
			<tr>
				<td colspan="4" align="center" style="font-size:19px;padding:55px">You don't seem to have any new messages.</td>
			</tr>
		<? endif ?>
	</table>
	
	<input type="hidden" id="inblk_action" name="action" value="" />
    <input type="hidden" name="page" id="inbox_page" value="<? if (isset($_GET["p"])) : ?><?= (int)$_GET["p"] ?><? else : ?>1<? endif ?>">
</form>
<? if (count($Inbox) > 0) : ?>
<div style="background:#e2e2e2;padding:5px;font-size:13px;word-spacing:4px;font-weight:bold">
    <? $_PAGINATION->new_show(null,"page=messages") ?>
</div>
<? endif ?>