function remove_comment(id) {
    fetch(`/api/feed/comment?action=remove&id=${id}`, {
        method: "GET"
    }).then(response => response.json()).then(data => {
        if(data.status == 1) {
            let comment_parent = document.querySelector("post-item-list"),
                comment_block = document.querySelector(`li[data-comment-id="${id}"]`);
            if(comment_parent) {
                if(comment_block)
                    comment_block.remove();
                if(comment_parent.innerHTML == null) {
                    let feed_container = document.querySelector(".feed-list-container"),
                        load_more = document.querySelector(".yt-uix-c3-load-more");
                    comment_parent.remove();
                    load_more.remove();
                    feed_container.innerHTML += `<p class="feed-unavailable-message">No channel comments.</p>`;
                }
            }
        } else {
            alert(data.message);
            console.error(data.message);
        }
    });
}

if(document.getElementById("bg_cover_remove") != null) {
    document.getElementById("bg_cover_remove").addEventListener("click", function(e) {
        e.preventDefault();
        let bg_cover_box = document.getElementById("bg_cover_box");
        bg_cover_box.innerHTML = `<input type="hidden" name="bg_remove" value="1">
        <input type="file" name="bg_cover" id="bg_cover">`;
    });
}

if(document.querySelector("#channel-editor-btn") != null) {
    if(document.querySelector("#channel-cancel") != null) {
        document.querySelector("#channel-cancel").addEventListener("click", (e) => {
            e.preventDefault();
            $('.store-editor').toggleClass('hddn');
            document.querySelector("#channel-editor-btn").classList.remove("hidden");
        })
    }
    document.querySelector("#channel-editor-btn").addEventListener("click", (e) => {
        e.preventDefault();
        $('.store-editor').toggleClass('hddn');
        document.querySelector("#channel-editor-btn").classList.add("hidden");
    });
}

const tabContainer = document.getElementById("store-editor");
const tabs = tabContainer.querySelectorAll("[data-tab]");
const panels = tabContainer.querySelectorAll("[data-tab-name]");

function activateTab(tabName) {
    // update tab buttons
    tabs.forEach(tab => {
        tab.classList.toggle(
            "active",
            tab.dataset.tab === tabName
        );
    });

    // update tab panels
    panels.forEach(panel => {
        panel.classList.toggle(
            "tab-active",
            panel.dataset.tabName === tabName
        );
    });
}

tabContainer.addEventListener("click", (e) => {
    const tab = e.target.closest("[data-tab]");
    if (!tab) return;

    e.preventDefault();
    activateTab(tab.dataset.tab);
});

activateTab("appearance");

var channels = [], playlists = [];

function cosmic_add_channel() {
    let desired_username = document.getElementById("ft_channel_name").value,
        channels_small_box = document.getElementById("featured_channels_small");

    if(channels_small_box != null) {
        if(desired_username.length > 0) {
            fetch(`/api/user?u=${desired_username}`, {
                method: "GET"
            }).then(response => {
                if(response) {
                    return response.json();
                } else {
                    throw new Error("Network error");
                }
            }).then(data => {
                if(data.status == 1) {
                    channels.push(data.data.username);
                    channels_small_box.innerHTML += `<div id="fc_${data.data.username}" style="overflow:hidden;background-color: #fff;border-radius:4px;padding:4px;margin-top:12px">
                        <strong style="color: #282828">${data.data.username}</strong>
                        <div style="float:right"><a onclick="cosmic_remove_ft('${data.data.username}')">Remove</a></div>
                    </div>`;
                } else {
                    alert("This user doesn't exist");
                }
            });
        } else {
            alert("Enter channel username");
        }
    }
}
function cosmic_add_playlist() {
    let select_playlist = document.querySelector("#select_playlist option:checked"),
        playlists_small_input = document.getElementById("playlists_small_input"),
        featured_playlists = document.getElementById("featured_playlists_small");

    fetch(`/api/playlist?id=${select_playlist.value}`, {
        method: "GET"
    }).then(response => {
        if(response) {
            return response.json();
        } else {
            throw new Error("Network error");
        }
    }).then(data => {
        if(data.status == 1) {
            playlists.push(data.data.purl);
            featured_playlists.innerHTML += `<div id="pl_${data.data.purl}" style="overflow:hidden;background-color: #fff;border-radius:4px;padding:4px;margin-top:12px">
                <strong style="color: #282828;">${data.data.title}</strong>
                <div style="float:right"><a href="javascript:void(0)" onclick="cosmic_remove_pl(\'${data.data.purl}')\">Remove</a></div>
            </div>`;
            playlists_small_input = playlists.join();
            document.getElementById("playlists_small_input").setAttribute("value", playlists.join());
        } else {
            alert(data.message);
            console.error(data.message);
        }
    });
}

function cosmic_remove_channel(channel) {
    console.log(`removing ${channel}...`);
    console.log(channels);
    if(channels.includes(channel)) {
        channels.splice(channels.indexOf(channel), 1);
    }
    document.querySelector(`#fc_${channel}`)?.remove();
}

document.querySelector("#playlists-save")?.addEventListener("click", (e) => {
    e.preventDefault();

    let fd = new FormData(); fd.append("action", "edit"); fd.append("f_playlists", playlists.join());
    fetch(`/api/user/update`, {
        method: "POST",
        body: fd
    }).then(resp => resp.json()).then(data => {
        if(data.status >= 0) {
            window.location.reload();
        } else {
            alert(data.message);
        }
    });
});
document.querySelector("#channels-save")?.addEventListener("click", (e) => {
    e.preventDefault();

    let fd = new FormData(); fd.append("action", "edit"); fd.append("f_channels", channels.join());
    fetch(`/api/user/update`, {
        method: "POST",
        body: fd
    }).then(resp => resp.json()).then(data => {
        if(data.status >= 0) {
            window.location.reload();
        } else {
            alert(data.message);
        }
    });
});

document.querySelector(".more-videos").addEventListener("click", async(e) => {
    e.preventDefault();
    e.target.setAttribute("disabled", "");

    await fetch(`/api/user`)
});
