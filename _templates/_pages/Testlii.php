<style>
    strong {
        margin: 15px 0 3px;
        font-size: 19px;
        display: block;
    }
    p {
        margin: 0 0 4px;
    }
</style>
<h1 class="pg_hd">Testlii</h1>
<div class="vc_l">
    <div class="vc_cats">
        <div>About VidLii</div>
        <ul>
            <li><a href="/blog">Blog</a></li>
            <li><a href="/about">About Us</a></li>
            <li><a href="/terms">Terms of Use</a></li>
            <li><a href="/privacy">Privacy Policy</a></li>
            <li><a href="/themes">Themes</a></li>
            <li><a href="/contact">Contact</a></li>
            <li style="font-weight:bold;cursor:default">Testlii</li>
        </ul>
    </div>
</div>
<div class="vc_r" style="margin-bottom:0">
    <h2 style="font-size:19px;margin-bottom:5px">
        What's Testlii?
    </h2>
    Testlii let's you try out new features before anyone else so that VidLii can get feedback on them before they're publicly released.<br><br>
    <? if ($_USER->logged_in) : ?>
    <div style="position:relative;right:0">
        <img src="/img/bell.png" style="float: left">
        <div style="float:left;margin-left: 6px">
            <strong style="font-size:16px;display:block;margin-bottom:3px;margin-top:0">Social Homepage</strong>
            A more social homepage displayed in a Timeline. It shows the activity of your friends and subscriptions.<br>
            After testing it will be able to be turned on straight from the homepage.<br>
            <? if (!isset($_COOKIE["s"])) : ?><a href="/testlii?t=s" style="font-weight:bold;position:relative;top:3px">Try it out</a><? else : ?><a href="/testlii?t=s" style="font-weight:bold;position:relative;top:3px">Stop</a><? endif ?>
        </div>
    </div>
    <div class="cl"></div>
    <? endif ?>
    <div style="position:relative;right:0;<? if ($_USER->logged_in) : ?>margin-top: 25px<? endif ?>">
        <img src="/img/testlii1.png" style="float: left">
        <div style="float:left;margin-left: 6px">
            <strong style="font-size:16px;display:block;margin-bottom:3px;margin-top:0">Compact Header</strong>
            This is a optional header which makes the website look a lot slimmer and a tiny bit more modern.<br>
            It's based on the Youtube 2010-2011 header.<br>
            <? if (!isset($_COOKIE["hd"])) : ?><a href="/testlii?t=h" style="font-weight:bold;position:relative;top:3px">Try it out</a><? else : ?><a href="/testlii?t=h" style="font-weight:bold;position:relative;top:3px">Stop</a><? endif ?>
        </div>
    </div>
</div>