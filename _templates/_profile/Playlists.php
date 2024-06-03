<section>
    <div class="nm_box">
        <div class="prbx_hd nm_hd">
            Playlists (<?= number_format($_PAGINATION->Total) ?>)
        </div>
        <div class="prbx_in nm_in">
            <? foreach ($Playlists as $Playlist) : ?>
                <? if (!file_exists("usfi/thmp/".$Playlist["thumbnail"].".jpg")) { $Playlist["thumbnail"] = "/img/no_th.jpg"; } else { $Playlist["thumbnail"] = "/usfi/thmp/".$Playlist["thumbnail"].".jpg"; } ?>
                <div class="pl_row" id="pl_<?= $Playlist["purl"] ?>">
                    <div class="playlist">
                        <a href="/playlist?p=<?= $Playlist["purl"] ?>"><img src="<?= $Playlist["thumbnail"] ?>"></a>
                    </div>
                    <div class="pl_info" style="width:735px">
                        <a href="/playlist?p=<?= $Playlist["purl"] ?>"><?= $Playlist["title"] ?></a>
                        <em>No Description...</em>
                    </div>
                    <div><a href="javascript:void(0)">Play All</a><br><a href="javascript:void(0)" onclick="copyToClipboard('#pls_<?= $Playlist["purl"] ?>');alert('Link copied to your clipboard!')">Share</a>
                        <div id="pls_<?= $Playlist["purl"] ?>" style="display:none">/playlist?p=<?= $Playlist["purl"] ?></div>
                    </div>
                </div>
            <? endforeach ?>
            <div style="padding:5px;font-weight:bold;word-spacing:5px;text-align:right;font-size:15px;padding-bottom:0"><?= $_PAGINATION->new_show(NULL,"/user/".$Profile["displayname"]."/playlists",true) ?></div>
        </div>
    </div>
</section>