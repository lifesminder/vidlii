var pageUrl = location.href;

// Create element used to copy text
var copyInput = document.createElement('input');
copyInput.type = 'text';
copyInput.style.visibility = 'invisible';
copyInput.style.zIndex = '0';
copyInput.style.position = 'fixed';
$(document).ready(function() {
    document.body.appendChild(copyInput);
});

// Copies text to the clipboard
// This will only work if called from a user-generated event, like a click
function copyText(text) {
    copyInput.value = text;
    copyInput.focus();
    copyInput.select();
    document.execCommand('copy');
}

function setCookie(e, t) {
    var n = new Date;
    n.setFullYear(n.getFullYear() + 10), document.cookie = e + "=" + t + "; expires=" + n.toGMTString() + "; path=/"
}

function createContextMenuHook(player) {
    var menuItems = {
        copy: { text: "Copy URL", element: null },
        copyT: { text: "Copy URL at current time", element: null },
        efull: { text: "Enter Full Screen", element: null },
        thd: { text: "HD Quality", element: null },
        loop: { text: "Loop", element: null },
        mute: { text: "Mute", element: null },
        vlp: { text: "VL Modern Player", element: null }
    };
    var menuElem = null;
    var menuPos = -1;

    // Add hook
    var playerElem = $('#vlplayer');
    var videoElem = document.getElementsByClassName('jw-video')[0];
    playerElem.contextmenu(function(e) {
        e.preventDefault();

        var mouseX = e.pageX;
        var mouseY = e.pageY;

        // Remove menu if it already exists
        if(menuElem !== null)
            menuElem.remove();

        // Create new menu
        menuElem = $('<ul class="modernPlayerMenu" tabindex="0"></ul>');
        menuElem.css({
            left: mouseX,
            top: mouseY
        });

        // Add menu items
        for (var item in menuItems) {
            var itemElem = $('<li tabindex="-1">' + menuItems[item].text + "</li>");
            menuItems[item].element = itemElem;
            menuElem.append(itemElem);
        }

        // Add menu hooks
        menuElem.blur(function() {
            if(menuElem !== null) {
                menuElem.remove();
                menuElem = null;
            }
        });
        menuElem.contextmenu(function(e) {
            e.preventDefault();
            if(menuElem !== null) {
                menuElem.remove();
                menuElem = null;
            }
        });
        menuElem.children().mouseenter(function() {
            menuElem.trigger("mouseleave");
            $(this).addClass("hover");
        });
        menuElem.mouseleave(function() {
            menuElem.children().removeClass("hover");
        });
        menuElem.keydown(function(e) {
            var key = e.keyCode;
            var itemCount = menuElem.children().length;
            switch(key) {
                case 27:
                    menuElem.blur();
                    playerElem.focus();
                    break;
                case 32:
                    menuElem.children().eq(menuPos).click();
                    break;
                case 38:
                    (--menuPos < 0 || menuPos >= itemCount) && (menuPos = itemCount - 1), menuElem.trigger("mouseleave"), menuElem.children().eq(s).addClass("hover");
                    break;
                case 40:
                    (++menuPos < 0 || menuPos >= itemCount) && (menuPos = 0), menuElem.trigger("mouseleave"), menuElem.children().eq(s).addClass("hover")
                }
                return false;
        });

        // Menu item events
        menuItems.copy.element.click(function() {
            menuElem.blur();
            playerElem.focus();
            copyText(pageUrl);
        });
        menuItems.copyT.element.click(function() {
            menuElem.blur();
            playerElem.focus();
            var time = Math.round(videoElem.currentTime);
            copyText(pageUrl+'#t='+time);
        });
        menuItems.loop.element.click(function() {
            videoElem.loop = !videoElem.loop;
            if(player.loopChange)
                player.loopChange();
            menuElem.blur();
            playerElem.focus();
            menuElem.toggleClass("loop");
        });
        menuItems.mute.element.click(function() {
            menuElem.blur();
            playerElem.focus();
            videoElem.muted = !videoElem.muted;
            menuElem.toggleClass("mute");
        });
        menuItems.efull.element.click(function() {
            menuElem.blur();
            playerElem.focus();
            player.instance.setFullscreen(true);
        });
        menuItems.thd.element.click(function() {
            menuElem.blur();
            playerElem.focus();
            player.instance.next();
            //     f.blur(), t.focus(), i.toggleHD()
        });
        if(videoElem.loop)
            menuItems.loop.element.addClass("checked");
        if(videoElem.muted)
            menuItems.mute.element.addClass("checked");
        if(playerElem.hasClass("hd720p"))
            menuItems.thd.element.addClass("checked");
        else
            menuItems.thd.element.remove();

        menuElem.mousedown(false);

        // Append menu to player
        playerElem.append(menuElem);
        $('body').append(menuElem);

        // Make visible and focus
        menuElem.animate({
            opacity: 1
        }, 250);
        menuElem.focus();
    });
}

function ModernPlayer(e) {
    var lastVtoken = new Date();

    var player = e;
    var t, n, a, o, i, u, r, s, l, p, c;
    o = function() {
        // Don't call if last call was less than 3 seconds ago
        var secondsAgo = new Date();
        secondsAgo.setSeconds(secondsAgo.getSeconds()-3);
        if(secondsAgo < lastVtoken)
            return;
        lastVtoken = new Date();

        if (paused = !1, !u && t && ($.ajax({
            url: "/ajax/vtoken.php",
            type: "post",
            data: {
                a: 1,
                u: t
            },
            timeout: 1e4,
            success: function(e) {
                0 == e.indexOf("1.") ? u = e.substr(2) : "1" == e ? r = !0 : u = null
            },
            error: function() {
                u = null
            }
        }), u = "123"), l || r) return !1;
        l = setInterval(function() {
            if (!a) return i();
            var e = Math.round(n); - 1 == s.indexOf(e) && s.push(e);
            var o = a <= 60 && s.length >= .6 * a,
                l = a > 60 && s.length >= 32;
            (o || l) && u && "123" != u && !r && ($.ajax({
                url: "/ajax/vtoken.php",
                type: "post",
                data: {
                    a: 2,
                    u: t,
                    t: u,
                    v: s
                },
                timeout: 1e4,
                success: function(e) {
                    "1" == e ? i() : r = !1
                },
                error: function() {
                    r = !1
                }
            }), r = !0)
        }, 4_000), "undefined" == typeof watchinit && setInterval(function() {
            watchinit = !0, 0 == paused && $.ajax({
                url: "/ajax/aw2",
                type: "post",
                data: {
                    a: 2,
                    u: t,
                    t: u
                }
            })
        }, 1e3)
    }, i = function() {
        l = clearInterval(l), paused = !0
    }, this.seek = function(e) {
        p.play(), p.seek(e)
    }, p = e.instance, t = e.videoUrl, s = [], p = jwplayer("vlplayer"), c = [{
        image: e.preview,
        sources: [{
            file: e.src
        }]
    }], e.hdsrc && c[0].sources.push({
        default: e.startinhd,
        file: e.hdsrc,
        label: "HD 720p"
    }), p.setup({
        autostart: e.autoplay,
        playlist: c,
        width: "100%",
        height: "100%",
        preload: "none"
    }), p.on("play", o), p.on("time", o), p.on("pause", i), p.on("idle", i), p.on("seek", i), p.on("complete", i), p.on("playAttemptFailed", i), p.on("ready", function(t) {
        createContextMenuHook(player);
        e.start && e.start > 0 && (p.play(), p.seek(e.start))
    }), p.on("time", function(e) {
        a = e.duration, n = e.position
    }), p.on("complete", function(t) {
        e.ended && e.ended()
    }), p.onQualityChange(function(e) {
        setCookie("vlphd", 1 == e.currentQuality ? 1 : 0)
    })
}