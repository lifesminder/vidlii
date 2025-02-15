function VLPSkin(t, e, l) {
    var n, i, s, a, o, d, c, v, p, r;
    this.setIntervals = function() {}, this.clearIntervals = function() {
        clearInterval(n), clearInterval(i), clearInterval(s), clearInterval(d), a = clearTimeout(a)
    }, this.resize = function() {}, this.changeElapsed = function(e, l) {
        var n = e / t.duration * 100;
        X.html(h.format(e)), l || (st.css("left", n + "%"), it.css("width", n + "%"))
    }, this.changeDuration = function(e) {
        q.html(h.format(e)), r = !1, t.hd ? (tt.show(), tt.removeClass("hidden")) : (tt.hide(), tt.addClass("hidden"))
    }, this.changeBuffer = function(t) {
        nt.css("width", t)
    }, this.changeVolume = function(t) {
        K.css("bottom", 100 * t + "%")
    }, this.changePreview = function(t) {
        k.css("background-image", ""), $("<img />").attr("src", t).on("load", function() {
            k.css("background-image", "url(" + t + ")")
        })
    }, this.format = function(e, l) {
        var n, i, s, a, o;
        return l || 0 === l || (l = t.duration), this.parse = function(t, e) {
            return (t = parseInt(t)) < 10 ? e + t : t > 99 ? 99 : t
        }, a = "", o = "", l >= 600 && (a = "0"), l >= 36e3 && (o = "0"), n = this.parse(e % 60, "0"), i = this.parse(e / 60, a), s = this.parse(e / 3600, o), l < 3600 ? i + ":" + n : s + ":" + i + ":" + n
    }, this.calcSeek = function() {
        return 0 == t.duration ? 0 : parseFloat(st.css("left")) / lt.width() * t.duration
    }, this.showBuffer = function() {
        if (u.hasClass("playing") && null == n) {
            var t = 0;
            B.show(), n = setInterval(function() {
                360 == t && (t = 0), B.css("transform", "rotate(" + t + "deg)"), t += 45
            }, 77)
        }
    }, this.hideBuffer = function() {
        null != n && (clearInterval(n), B.hide(), n = null)
    }, this.seekTo = function(e) {
        if (null == i && 1 == e.which) {
            var l, n, s = "click" == e.type;
            st.addClass("active"), i = setInterval(function() {
                l = t.mouseX - lt.offset().left, n = lt.width(), l < 0 && (l = 0), l > n && (l = n), v = !0, st.css("left", l), h.changeElapsed(h.calcSeek(), !0), h.showTime(), s && $(document).mouseup()
            }, 26)
        }
    }, this.showTime = function(e) {
        var l = lt.width(),
            n = u.offset().left,
            i = lt.offset().left,
            s = t.mouseX - n,
            a = t.mouseX - i;
        v && (s = st.offset().left - n + 8, a = parseFloat(st.css("left"))), s < i - n && (s = i - n), s > i + l && (s = i + l), a < 0 && (a = 0), a > l && (a = l), a = parseInt(a / l * t.duration), H.html(h.format(a, a)), V.show(), V.css("margin-left", s - V.width() / 2)
    }, this.hideTime = function(t) {
        p = !1, v = !1, null == i && V.hide()
    }, this.volumeTo = function(e) {
        if (null == s && 1 == e.which) {
            var l, n, i, a = "click" == e.type;
            s = setInterval(function() {
                l = t.mouseY - J.offset().top - 6, n = J.height(), l < 0 && (l = 0), l > n && (l = n), i = (n - l) / n, t.setVolume(i), a && $(document).mouseup()
            }, 26)
        }
    }, this.volumeWheel = function(e) {
        var l = t.videoObj.volume;
        return e.originalEvent.deltaY < 0 ? t.setVolume(l + .1) : t.setVolume(l - .1), !1
    }, this.showVolume = function() {
        o = !0, a = clearTimeout(a);
        var t = W.height();
        W.stop().css("height", "auto");
        var e = W.height();
        W.stop().css("height", t), W.stop().animate({
            height: e
        }, 250)
    }, this.hideVolume = function() {
        o = !1, a = setTimeout(function() {
            null == s && 0 != W.height() && (W.stop().animate({
                height: 0
            }, 250), a = void 0)
        }, 500)
    }, this.expand = function() {
        e && e()
    }, this.toggleHD = function() {
        t.hd && h.hideEndScreen(function() {
            r = !0, h.showBuffer(), t.toggleHD(), u.hasClass("ended") && t.play()
        })
    }, this.timeUpdate = function() {
        r || u.hasClass("ended") || (null == i && (h.changeElapsed(t.videoObj.currentTime), v && h.showTime()), t.bufferUpdate(), h.hideEndScreen())
    }, this.toggle = function() {
        u.hasClass("ended") ? h.hideEndScreen(t.toggle) : (h.showPlayAnimation(), t.toggle())
    }, this.ended = function() {
        u.hasClass("ended") || (u.hasClass("loop") || x.stop().show().css("opacity", 0).animate({
            opacity: 1
        }, 100, function() {
            S.find("span").html("Share"), T.find("span").html("Replay")
        }), t.ended())
    }, this.hideEndScreen = function(t) {
        x.is(":visible") ? (S.find("span").html(""), T.find("span").html(""), x.stop().animate({
            opacity: 0
        }, 100, function() {
            x.hide(), "function" == typeof t && t()
        })) : "function" == typeof t && t()
    }, this.showPlayAnimation = function() {
        var t, e;
        e = u.hasClass("playing") ? E : C, (t = e.find("svg")).stop(), t.css({
            opacity: 0,
            width: 50,
            height: 50
        }), t.animate({
            width: 125,
            height: 125
        }, {
            duration: 500,
            queue: !1
        }), t.animate({
            opacity: .5
        }, {
            duration: 250,
            queue: !1,
            complete: function() {
                t.animate({
                    opacity: 0
                }, 250, function() {
                    e.hide()
                })
            }
        }), e.show()
    }, this.mouseUp = function(e) {
        this.seek = function() {
            u.hasClass("ended") ? (t.seek(h.calcSeek()), t.play()) : (t.seek(h.calcSeek()), u.hasClass("started") || t.play())
        }, null != i && (clearInterval(i), i = null, 0 != t.duration ? h.hideEndScreen(this.seek) : st.css("left", 0), p || h.hideTime()), null != s && (clearInterval(s), s = null, 0 == o && h.hideVolume()), u.find(".vlButton").removeClass("active")
    }, this.fullBtnEnter = function() {
        if (c = !0, et.addClass("hover"), !d && !u.hasClass("full")) {
            et.removeClass("hover");
            var t = 0,
                e = 0,
                l = 0,
                n = et.find("> s > i"),
                i = n.css("background-position");
            i = parseFloat(i.split(" ")[1]), et.addClass("hover");
            var s = n.css("background-position");
            s = parseFloat(s.split(" ")[1]), d = setInterval(function() {
                (t < 7 || t > 27) && (t < 7 ? e++ : 28 == t ? e = 5 : 29 == t ? e = 2 : 30 == t ? e = 1 : 31 == t && (e = 0)), l = c ? s : i, et.find("> s > i").css("background-position", "-" + (242 + 30 * e) + "px " + l + "px"), (35 == ++t || u.hasClass("full")) && (c && !u.hasClass("full") || (d = clearInterval(d), et.find("> s > i").css("background-position", "")), t = 0)
            }, 50)
        }
    }, this.fullBtnLeave = function() {
        et.removeClass("hover"), c = !1
    };
    var u = t.obj,
        h = this,
        f = $('<div class="vlHidden"></div>'),
        g = $('<input type="text" tabindex="-1" />'),
        m = $('<div class="vlContainer"/>'),
        y = $('<div class="vlScreen"/>'),
        k = $('<div class="vlPreview"/>'),
        w = $('<div class="vlPlay"/>'),
        B = $('<div class="vlLoad vlHdIcon"/>'),
        x = $('<div class="vlEndScreen" style="display:none"/>'),
        P = $('<div class="vlEndScreenLeft"/>'),
        b = $('<div class="vlEndScreenRight"/>'),
        S = $('<div class="vlEndScreenBtn"><i class="vlHdIcon"></i><span></span></div>'),
        T = $('<div class="vlEndScreenBtn"><i class="vlHdIcon"></i><span></span></div>'),
        C = $('<div class="vlPlayAnimation" style="display:none">\t\t<div><svg width="100%" height="100%" viewbox="0 0 100 100">\t\t\t<rect width="100" height="100" style="fill:black" />\t\t\t<polygon points="32,28 66,50 32,72" style="fill:white" />\t\t</svg></div>\t</div>'),
        E = $('<div class="vlPauseAnimation" style="display:none">\t\t<div><svg width="100%" height="100%" viewbox="0 0 100 100">\t\t\t<rect width="100" height="100" style="fill:black" />\t\t\t<rect x="34" y="28" width="10" height="44" style="fill:white" />\t\t\t<rect x="56" y="28" width="10" height="44" style="fill:white" />\t\t</svg></div>\t</div>'),
        I = $('<div class="vlTimeShow"></div>'),
        V = $('<div class="vlTimeShowContainer"></div>'),
        H = $('<div class="vlTimeShowText"></div>'),
        F = $('<div class="vlTimeShowSvg">\t\t<div>\t\t\t<svg width="12" height="8">\t\t\t\t<polygon points="0,2 12,2 6,8" fill="#999999"/>\t\t\t\t<polygon points="1,0 11,0 11,2 6,7 1,2" fill="#101010"/>\t\t\t</svg>\t\t</div>\t</div>'),
        D = t.video,
        L = $('<div class="vlControls"/>'),
        U = $('<div class="vlcLeft"/>'),
        M = $('<div class="vlcCenter"/>'),
        j = $('<div class="vlcRight"/>'),
        A = $('<div class="vlTime"/>'),
        R = $('<div class="vlTimestamp"/>'),
        X = $('<span class="vlElapsed">0:00</span>'),
        q = $('<span class="vlDuration">0:00</span>'),
        z = $("<span> / </span>"),
        O = $('<div class="vlcVolume"/>'),
        W = $('<div class="vlcVolumeDiv"/>'),
        Y = $('<div class="vlcVolumeBar1"/>'),
        _ = $('<div class="vlcVolumeBar2"/>'),
        G = $('<div class="vlcVolumeBar3"/>'),
        J = $('<div class="vlcVolumeBar4"/>'),
        K = $('<s class="vlHdIcon"/>'),
        N = $('<div class="vlcPlay vlcBtn vlButton"><s><i/></s></div>'),
        Q = $('<div class="vlcMute vlcBtn vlButton"><s><i/></s></div>'),
        Z = $('<div class="vlcExpand vlcBtn vlButton"><s><i/></s></div>'),
        tt = $('<div class="vlcToggleHD vlcBtn vlButton"><s><i/></s></div>'),
        et = $('<div class="vlcFullScreen vlcBtn vlButton"><s><i/></s></div>'),
        lt = $('<div class="vlProgressBar"/>'),
        nt = $('<div class="vlBuffer"/>'),
        it = $('<div class="vlPosition"/>'),
        st = $('<div class="vlSeeker vlButton vlHdIcon"><i class="vlHdIcon"></i></div>');
    this.hiddenUrl = g;
    var at = t.skinPath + "/skin.css?" + window.vlpv,
        ot = t.skinPath + "/img";
    $("#vlPlayer2009css").remove(), $('<link id="vlPlayer2009css" rel="stylesheet"></link>').attr("href", at).appendTo("head").on("load", function() {
        u.addClass("vlPlayer2009"), u.append(m), u.append(L), u.append(V), u.append(f), f.append(g), m.append(y), y.append(k), y.append(w), y.append(B), y.append(D), y.append(x), y.append(C), y.append(E), x.append(P), x.append(b), P.append(S), b.append(T), L.append(U), L.append(j), L.append(M), U.append(N), M.append(lt), j.append(A), j.append(O), j.append(Z), j.append(tt), j.append(et), V.append(I), I.append(H), I.append(F), lt.append(nt), lt.append(it), lt.append(st), A.append(R), R.append(X), R.append(z), R.append(q), O.append(W), O.append(Q), W.append(Y), Y.append(_), _.append(G), G.append(J), J.append(K), D.dblclick(t.toggleFull), w.dblclick(t.toggleFull), Q.click(t.toggleMute), et.click(t.toggleFull), D.click(h.toggle), D.on("timeupdate", h.timeUpdate), D.on("waiting", h.showBuffer), D.on("ended", h.ended), D.on("playing canplay canplaythrough timeupdate pause", h.hideBuffer), D.on("playing seeked", function() {
            r = !1
        }), w.click(h.toggle), N.click(h.toggle), T.click(h.toggle), et.mouseenter(h.fullBtnEnter), et.mouseleave(h.fullBtnLeave), Z.click(h.expand), tt.click(h.toggleHD), lt.click(h.seekTo), lt.mousedown(h.seekTo), lt.mousemove(h.showTime), lt.mouseenter(function() {
            p = !0
        }), lt.mouseleave(h.hideTime), st.mouseenter(function() {
            v = !0
        }), st.mouseleave(function() {
            v = !1
        }), O.mouseenter(h.showVolume), O.mouseleave(h.hideVolume), O.mouseenter(function() {
            h.allowScroll = !1
        }), O.mouseleave(function() {
            h.allowScroll = !0
        }), O.on("wheel", h.volumeWheel), J.mousedown(h.volumeTo), J.click(h.volumeTo), $(document).mouseup(h.mouseUp), $(document).on("wheel scroll", h.allowScroll);
        var n = ["red", "orange", "gold", "olive", "green", "teal", "blue", "violet", "pink", "magenta", "white"];
        if (0 == $("#vlPlayer2009css2").length)
            for (var i = $('<style id="#vlPlayer2009css2" />').appendTo("head"), s = 0; s < n.length; s++) i.append("\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcPlay:hover > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcPlay.active > s > i { background-position: -2px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt.playing .vlcPlay:hover > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt.playing .vlcPlay.active > s > i { background-position: -32px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlSeeker > i { background-position: -67px -" + (25 * (s + 1) + 5) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcMute:hover > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcMute.active > s > i { background-position: -92px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt.muted .vlcMute > s > i { background-position: -122px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcFullScreen.hover > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcFullScreen.active > s > i { background-position: -242px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt.full .vlcFullScreen.hover > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt.full .vlcFullScreen.active > s > i { background-position: -152px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcExpand:hover > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcExpand.active > s > i { background-position: -182px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt.expanded .vlcExpand:hover > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt.expanded .vlcExpand.active > s > i { background-position: -212px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcToggleHD:hover > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlcToggleHD.active > s > i,\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt.hd720p .vlcToggleHD > s > i { background-position: -482px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlEndScreenBtn i { background-position: -" + 50 * s + "px -350px; }\t\t\t\t\t.vlPlayer2009." + n[s] + "Bt .vlEndScreenRight .vlEndScreenBtn i { background-position: -" + 50 * s + "px -400px; }\t\t\t\t");
        e || Z.remove(), t.hd || (tt.hide(), tt.addClass("hidden")), t.toggleFull(!0) || et.hide(), t.adjust && (u.css("padding-bottom", 25), u.parent().css("padding-bottom", 25));
        var a = t.buttonColor,
            o = t.background,
            d = ["buttonshd.png", "gradients.png", "play" + ("black" == o ? "_black" : "") + ".png"],
            c = 0;
        for (s = 0; s < d.length; s++) $("<img />").attr("src", ot + "/" + d[s]).on("load", function() {
            ++c == d.length && (t.changeButtonColor(a), t.changeBackground(o), u.addClass("initialized"), u.prop("tabindex", 0), l())
        })
    })
}