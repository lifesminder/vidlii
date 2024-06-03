<div>
    <div class="cosmic_about" style="border:0">
        <h3>About <?= $Profile["displayname"] ?></h3>
	<? if (!$Is_OWNER && $_USER->logged_in) : ?>
    <div style="overflow:hidden;margin:15px 0 0 0">
        <? if ($Is_Blocked || $Has_Blocked) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" onclick="alert('You cannot interact with this user!')">Add Friend</button>
        <? elseif ($Is_Friends === false && $_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Add Friend</button>
        <? elseif ($Is_Friends === 2 && $_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Cancel Invite</button>
        <? elseif ($Is_Friends === true && $_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Unfriend</button>
        <? elseif ($Is_Friends === 3 && $_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Accept Invite</button>
        <? elseif (!$_USER->Is_Activated) : ?>
            <button class="cosmic_button" style="height:23px;width:46%" id="aaf" onclick="add_friend('<?= $Profile["username"] ?>')">Add Friend</button>
        <? endif ?>
        <? if (!$Is_Blocked && !$Has_Blocked) : ?>
            <button class="cosmic_button" style="height:23px;width:46%;float:right" id="bu" onclick="block_user('<?= $Profile["username"] ?>')">Block User</button>
        <? elseif ($Has_Blocked) : ?>
            <button class="cosmic_button" style="height:23px;width:46%;float:right" id="bu" onclick="block_user('<?= $Profile["username"] ?>')">Unblock User</button>
        <? else : ?>
            <button class="cosmic_button" style="height:23px;width:46%;float:right">You are blocked!</button>
        <? endif ?>
    </div>
    <? endif ?>