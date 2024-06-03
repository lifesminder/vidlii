<form action="my_playlists" method="POST">
    <div style="background: #e2e2e2;padding: 12px">
        <input type="submit" value="Delete Selected Playlists" name="delete_playlists"> <button type="button" onclick="_('cr_playlist').style.display= 'inline'; this.style.display = 'none'">Create New Playlist</button> <span id="cr_playlist" style="display:none"><input type="text" name="playlist_name" maxlength="128" placeholder="Playlist Name"> <input type="submit" name="create_playlist" value="Create"></span>
    </div>
    <div style="border: 2px solid #e2e2e2; border-top: 0;padding: 10px;">
        <? if (count($Playlists) > 0) : ?>
            <? foreach($Playlists as $Playlist) : ?>
                <table class="mv_sct" style="min-height:175px;padding-bottom:25px">
                    <tr>
                        <td width="5%" align="center" valign="top"><input type="checkbox"></td>
                        <td width="20%" valign="top" align="center">
                            <img src="<? if ($Playlist["privacy"] == 0 && !empty($Playlist["thumbnail"])) : ?>/usfi/thmp/<?= $Playlist["thumbnail"] ?>.jpg<? else : ?>/img/no_thumbnail.png<? endif ?>" width="189px" height="130px" style="border: 3px double #999999">
                            <br><a href="/ajax/df/delete?p=<?= $Playlist["purl"] ?>"><button type="button">Delete Playlist</button></a></td>
                        <td width="70%" valign="top" class="mv_info">
                            <strong><a href="/my_playlists?pl=<?= $Playlist["purl"] ?>"><?= $Playlist["title"] ?></a></strong><br>
                            <span>Added:</span> <?= get_date($Playlist["created_on"]).", ".get_time($Playlist["created_on"]); ?><br>
                        </td>
                    </tr>
                </table>
            <? endforeach ?>
        <? else : ?>
            <center style="font-size: 20px;color:gray">You don't have any Playlists!<br><span style="font-size:16px">Create one by clicking "Create New Playlist" on the header.</span></center>
        <? endif ?>
    </div>
</form>