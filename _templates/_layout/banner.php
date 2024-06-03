<div class="channel_banner" style="background-image:url(<?="/usfi/bner/$Banner_Image.png?$Banner_Version" ?>)"><?
    foreach($Banner_Links as $l) {
        if ($l["href"]) $l["href"] = 'href="'.str_replace('"', '&quot;', $l["href"]).'"';
        echo "<a $l[href] style=\"width:$l[width]%; height:$l[height]%; left:$l[left]%; top:$l[top]%;\" target=\"_blank\"></a>";
    }
    ?></div>