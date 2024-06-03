<div style="margin-bottom:7px;overflow:hidden">
    <div style="float:left;margin-right:5px">
        <?= video_thumbnail($Video["url"],"",79,50) ?>
    </div>
    <div style="display:block;margin-bottom:21px"><strong>You're posting a video response to: </strong><a href="/watch?v=<?= $Video["url"] ?>" ><?= $Video["title"] ?></a></div>
    <strong><?= $Video["responses"] ?></strong> video responses so far!
</div>
<div style="float:left;width:650px;margin-right:30px">
    <div style="border-radius:8px;padding:0 10px;background:#ebece0;overflow:hidden;margin-bottom:8px">
        <div style="background:#a9aaa0;padding:8px 11px;float:left;font-size:16px;font-weight:bold;color:white;margin-right:7px">
            Choose a Video
        </div>
        <div style="padding:8px 11px;float:left;font-size:16px;font-weight:bold">
            <a href="/upload" style="color:black;text-decoration: none">Upload a Video</a>
        </div>
    </div>
    <div style="float:left;width:275px;margin-right:50px">
        <h2 style="font-size:18px">Choose one of your existing Videos as a response</h2>
        <div style="margin-top:3px">Your submitted video response will appear under the selected video once the owner accepted it!</div>
    </div>
    <div style="float:left;width:325px;text-align: center">
        <form action="/post_response?v=<?= $Video["url"] ?>" method="POST">
            <div style="margin:3px 0 2px;font-size:13px;text-align: left">Choose one of your uploaded videos:</div>
            <select size="12" style="width:323px" name="response">
                <? foreach ($Uploaded_Videos as $Uploaded) : ?>
                    <option value="<?= $Uploaded["url"] ?>"><?= $Uploaded["title"] ?></option>
                <? endforeach ?>
            </select>
            <input type="submit" name="submit_response" value="Submit Video Response" style="margin-top:4px">
        </form>
    </div>
</div>
<div style="float:left;width:320px">
    <div style="background:#ebece0;padding:8px;border-radius:8px">
        <div style="margin-bottom:15px">
            <strong style="display:block">What is a video response?</strong>
            Ever wanted to talk back to a video? Now's your chance -- you can upload a response to this video and we will link them together.
        </div>
        <div style="margin-bottom:15px">
            <strong style="display:block">How do I post a Video Response?</strong>
            You can record a new video, choose from the videos you already have, or create and upload a new video. Select the option at the left that best suits your needs.
        </div>
        <div style="margin-bottom:15px">
            <strong style="display:block">Oops! I actually meant to post a text comment. How do I do this?</strong>
            Return to this video (by hitting "back" button on your browser or clicking on the title of the video at the top of this page) and click on "Post a Text Comment".
        </div>
    </div>
</div>