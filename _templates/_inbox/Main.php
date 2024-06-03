<div class="inbox_div">
    <? if (count($Messages) > 0) : ?>
    <? foreach ($Messages as $Message) : ?>
        <div class="inbox_sct">
            <div class="inbox_hd" onclick="sh_in('m_<?= $Message["id"] ?>')">
                <div><a href="/user/<?= $Message["from_user"] ?>"><?= $Message["from_user"] ?></a></div>
                <div><?= htmlspecialchars(cut_string($Message["message"],30)) ?></div>
                <div><?= get_date($Message["date_sent"]) ?></div>
            </div>
            <div id="m_<?= $Message["id"] ?>" style="display: none"><?= htmlspecialchars($Message["message"]) ?></div>
        </div>
    <? endforeach ?>
    <div style="margin-top: 6.5px">
        <?= $_PAGINATION->show($_PAGINATION->Total,"") ?>
    </div>
    <? else : ?>
        <div class="no_related" style="margin-top: 0">No Messages...</div>
    <? endif ?>
</div>
