<h1 class="pg_hd">Blog</h1>
<div class="vc_l">
    <div class="vc_cats">
        <div>About VidLii</div>
        <ul>
            <li style="font-weight:bold;cursor:default">Blog</li>
            <li><a href="/about">About Us</a></li>
            <li><a href="/terms">Terms of Use</a></li>
            <li><a href="/privacy">Privacy Policy</a></li>
            <li><a href="/themes">Themes</a></li>
            <li><a href="/contact">Contact</a></li>
            <li><a href="/testlii">Testlii</a></li>
        </ul>
    </div>
</div>
<div class="vc_r" style="margin-bottom:0">
    <? foreach($Blog_Posts as $Post) : ?>
        <div style="border-bottom:1px solid #ccc;margin-bottom: 30px;padding-bottom:30px;">
            <h2 style="font-size:19px"><?= $Post["title"] ?></h2>
            <em><?= $Post["date"] ?></em>
            <div>
                <?= $Post["content"] ?>
            </div>
        </div>
    <? endforeach ?>
</div>