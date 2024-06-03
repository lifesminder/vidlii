<style>
    p {
        margin: 0 0 4px;
    }
    .input_l {
        float: left;
        width:350px;
    }

    .input_r {
        float: left;
    }

    p {
        display: block;
        clear: both;
        margin-bottom: 14px
    }

    input[type="text"], select {
        position: relative;
        bottom: 4px
    }
</style>
<h1 class="pg_hd">Partnership</h1>
<div class="vc_l">
    <div class="vc_cats">
        <div>Help & Info</div>
        <ul>
            <li><a href="/help">Help Center</a></li>
            <li><a href="/developers">Developer API</a></li>
            <li style="font-weight:bold;cursor:default">Partnership</li>
            <li><a href="/copyright">Copyright</a></li>
            <li><a href="/guidelines">Community Guidelines</a></li>
        </ul>
    </div>
</div>
<div class="vc_r">
    <form action="/partners" method="POST">
        <div style="margin-bottom:32px">
            <strong style="margin:0 0 3px;font-size:17px;display:block">Information</strong>
            Every Text Field needs to be filled out!
        </div>
        <p>
        <div class="input_l">
            VidLii Username:
        </div>
        <div class="input_r">
            <strong><?= $_USER->displayname ?></strong>
        </div>
        </p>
        <p>
        <div class="input_l">
            VidLii Email:
        </div>
        <div class="input_r">
            <strong><?= $Info["email"] ?></strong>
        </div>
        </p>
        <div style="border-bottom:1px solid #d0d1c6;margin-bottom:21px"></div>
        <p>
        <div class="input_l">
            First Name:
        </div>
        <div class="input_r">
            <input maxlength="100" type="text" name="f_name">
        </div>
        </p>
        <p>
        <div class="input_l">
            Last Name:
        </div>
        <div class="input_r">
            <input maxlength="100" type="text" name="l_name">
        </div>
        </p>
        <p>
        <div class="input_l">
            Date of Birth:
        </div>
        <div class="input_r">
            <select name="month">
                <? foreach($Months_Array as $item => $value) : ?>
                    <option value="<?= $value ?>"><?= $item ?></option>
                <?php endforeach ?>
            </select>
            <select name="day">
                <? for ($x = 1; $x <= 31; $x++) : ?>
                    <option value="<?= $x ?>"><?= $x ?></option>
                <? endfor ?>
            </select>
            <select name="year">
                <? for($x = date("Y");$x >= 1910;$x--) : ?>
                    <option value="<?= $x ?>"><?= $x ?></option>
                <? endfor ?>
            </select>
        </div>
        </p>
        <p>
        <div class="input_l">
            Country:
        </div>
        <div class="input_r">
            <select name="country" style="width:214px">
                <? foreach ($Countries as $Country => $Name) : ?>
                    <option value="<?= $Country ?>"<? if (isset($_POST["country"]) && $Country == $_POST["country"]) : ?>selected<? endif ?>><?= $Name ?></option>
                <? endforeach ?>
            </select>
        </div>
        </p>
        <div style="border-bottom:1px solid #d0d1c6;margin-bottom:21px"></div>
        <p>
        <div class="input_l">
            What you do on VidLii:
        </div>
        <div class="input_r">
            <textarea rows="4" maxlength="500" name="what" style="width:210px;border-radius:4px;border: 1px solid #d5d5d5;outline:0"></textarea>
        </div>
        </p>
        <p>
        <div class="input_l">
            How would becoming partner help you:
        </div>
        <div class="input_r">
            <textarea rows="4" maxlength="500" name="why" style="width:210px;border-radius:4px;border: 1px solid #d5d5d5;outline:0"></textarea>
        </div>
        </p>
        <div style="border-bottom:1px solid #d0d1c6;margin-bottom:14px;clear:both"></div>
        <div style="font-size:12px">By clicking on the "Apply" button below, you agree with the Terms Of Service, the VidLii Guidelines and the VidLii Partnership qualifications.</div>
        <input name="submit" style="margin-top:8px" type="submit" value="Apply For Partnership" onclick="if(confirm('Have you read all the requirements listed above?') { } else { return false }">
    </form>
</div>
