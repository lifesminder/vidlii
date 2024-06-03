<style>
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
    label {
        font-weight: bold;
        display: block;
        height: 23px;
        margin-top: 3px;
    }
</style>
<script>
    function change_friend() {
        var friend = $("#select_friend option:selected").val();
        if (friend != "!") {
            $("#to_user").val(friend);
        }
    }
</script>
<form action="/inbox?page=send_message" method="POST">
    <div style="line-height:25px;padding:0 0 5px 25px">
    <label style="margin:0" for="to_user">To User:</label>
    <input required type="text" name="to_user" id="to_user" size="30" placeholder="Message Recipient..." tabindex="1" value="<? if (isset($To)) { echo $To; } ?>" <? if (isset($To)) : ?>readonly<? else : ?> autofocus<? endif ?> maxlength="20" style="border-radius:0;width:512px">
        <select style="border-radius:0;height:23px;vertical-align:bottom;width:200px" id="select_friend" onchange="change_friend()">
            <option value="!">Select a Friend</option>
            <? foreach ($Friends as $Friend) : ?>
            <option value="<?= $Friend["displayname"] ?>"><?= $Friend["displayname"] ?></option>
            <? endforeach ?>
        </select>
        <br>
    <label for="subject">Subject:</label>
    <input required type="text" id="subject" name="subject" tabindex="2" placeholder="Message Subject..." style="width:95.5%;border-radius:0" value="<? if (isset($Subject)) : ?><?= $Subject ?><? endif ?>" maxlength="256"><br>
    <label for="message">Message:</label>
    <textarea required maxlength="5000" name="message" tabindex="3" placeholder="Write your Message..." rows="13" id="message"></textarea><br>
    <input type="submit" value="Send Message" tabindex="4" class="search_button" name="send_message" style="padding:4px 15px;border-radius:0;margin-bottom:3px"> <button type="button" class="search_button" tabindex="5" style="margin:0 0 0 5px;padding:4px 10px;border-radius:0" onclick="if (confirm('Are you sure?')) { $('#message').val('');$('#to_user').val('');$('#subject').val('');$('#to_user').select() }">Clear</button>
    </div>
</form>