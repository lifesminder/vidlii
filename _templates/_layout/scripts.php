<? if (!$_USER->logged_in || $_USER->username != "VidLii") : ?><script src="<?= MAIN_JS_FILE ?>"></script><? else : ?>
<script src="/js/testmain.js?<?= rand(0,100000) ?>"></script>
<? endif ?>