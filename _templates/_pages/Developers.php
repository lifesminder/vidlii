<style>
    p {
        margin: 0 0 4px;
    }
    .cgul li {
        margin-bottom:3px
    }
</style>

<h1 class="pg_hd">Developers</h1>
<div class="vc_l">
    <div class="vc_cats">
        <div>Help & Info</div>
        <ul>
            <li><a href="/help">Help Center</a></li>
            <li style="font-weight:bold;cursor:default">Developer API</li>
            <li><a href="/partners">Partnership</a></li>
            <li><a href="/copyright">Copyright</a></li>
            <li><a href="/guidelines">Community Guidelines</a></li>
        </ul>
    </div>
</div>
<div class="vc_r" style="margin-bottom:0">
    <img src="/img/vidlii.png" style="width:122px;height:52px;float:left;margin-right:8px"><strong>VidLii anywhere, any time</strong>. The VidLii API allows you to integrate VidLii's video content and functionality into your website, software application, or device.
    <div style="clear:both"></div>
    <div style="margin-top: 33px">
        <img src="/img/chart_api.gif" style="float:left;margin-right:8px"><strong style="font-size:16px">Data API</strong><div style="font-size:14px">The VidLii Data API allows you to <strong>easily</strong> retrieve information of different parts on the website for your own use.</div>
    </div>
    <div style="clear:both"></div>
    <div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
        <img src="/img/clp00.png">
        <span class="u_sct_hd">User Data</span>
    </div>
    <div style="display:none">
        <div style="margin-bottom:3px;padding-bottom:3px" id="user_output">
            Example Call: <b>/api/user?u=</b><input type="text" id="user_argument" value="VidLii" required>
            <button id="user_runner">Run</button>
        </div>
        <div id="user_output_block"></div>
    </div>
    <div class="u_sct" style="border-bottom:1px solid #ccc;padding-bottom:6px;margin-top:15px">
        <img src="/img/clp00.png">
        <span class="u_sct_hd">Video Data</span>
    </div>
    <div style="display:none">
        <div style="margin-bottom:3px;padding-bottom:3px" id="video_output">
            Example Call: <b>/api/video?id=</b><input type="text" id="video_argument" value="BgGiE_u0i8o">
            <button id="video_runner">Run</button>
        </div>
        <div id="video_output_block"></div>
    </div>
</div>

<script>
    function hooker(path, output) {
        if(path != null || path != "") {
            fetch(path).then(resp => { return resp.json() }).then(data => {
                let response = JSON.stringify(data, null, 3);
                document.getElementById(output).style.display = "block";
                document.getElementById(output).style.border = "1px solid #ccc";
                document.getElementById(output).style.borderRadius = "4px";
                document.getElementById(output).style.padding = "6px";
                document.getElementById(output).innerHTML = `<b>Output:</b><pre style="margin: 6px 0; padding: 12px; border-radius: 6px; background-color: #f0f0f0">${response}</pre>`;
            });
        } else {
            alert("Enter valid path!");
        }
    }

    document.getElementById("user_runner").addEventListener("click", function(e) {
        e.preventDefault();
        hooker(`/api/user?u=${document.getElementById("user_argument").value}`, "user_output_block");
    });
    document.getElementById("video_runner").addEventListener("click", function(e) {
        e.preventDefault();
        hooker(`/api/video?id=${document.getElementById("video_argument").value}`, "video_output_block");
    });
</script>