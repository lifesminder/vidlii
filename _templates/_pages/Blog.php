<h1 class="pg_hd">Blog</h1>
<div class="vc_l">
    <div class="vc_cats">
        <div>About VidLii</div>
        <ul>
            <li class="active">Blog</li>
            <li><a href="/about">About Us</a></li>
            <li><a href="/terms">Terms of Use</a></li>
            <li><a href="/open-source">Open Source</a></li>
            <li><a href="/privacy">Privacy Policy</a></li>
            <li><a href="/themes">Themes</a></li>
            <li><a href="/contact">Contact</a></li>
            <li><a href="/testlii">Testlii</a></li>
        </ul>
    </div>
</div>
<div class="vc_r" style="margin-bottom:0">
    <?php
        if(!empty($post) && $post["count"] == 1) {
    ?>
        <div style="border-bottom: 1px solid #ccc; padding-bottom: 0.5rem">
            <h2><?= $post["data"]["title"] ?></h2>
            <div style="display: flex; justify-content: space-between">
                <em><?= $post["data"]["date"] ?></em>
                <a href="/blog">Back to Blog</a>
            </div>
        </div>
        <article style="margin: 1rem 0 1rem 0">
            <?php
                $parsedown = new \Parsedown();
                echo $parsedown->text($post["data"]["content"]);
            ?>
        </article>
    <?php
        } else {
    ?>
    <? foreach($Blog_Posts as $Post) : ?>
        <div class="blog-box">
            <div>
                <h2 style="font-size: 19px"><a href="/blog/<?= $Post["id"] ?>"><?= $Post["title"] ?></a></h2>
            </div>
            <div>
                <?= $Post["date"] ?>
            </div>
        </div>
    <? endforeach ?>
    <?php
        }
    ?>
</div>