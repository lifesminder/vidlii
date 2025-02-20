function VLPSkin(t, e, l) {
    var i, n, s, a, o, c, d, v, p;
    this.setIntervals = function() {}, this.clearIntervals = function() {
        clearInterval(i), clearInterval(n), clearInterval(s), clearInterval(c), a = clearTimeout(a)
    }, this.resize = function() {}, this.changeElapsed = function(e, l) {
        var i = e / t.duration * 100;
        D.html(u.format(e)), l || (it.css("left", i + "%"), lt.css("width", i + "%"))
    }, this.changeDuration = function(t) {
        X.html(u.format(t))
    }, this.changeBuffer = function(t) {
        et.css("width", t)
    }, this.changeVolume = function(t) {
        J.css("bottom", 100 * t + "%")
    }, this.changePreview = function(t) {
        y.css("background-image", ""), $("<img />").attr("src", t).on("load", function() {
            y.css("background-image", "url(" + t + ")")
        })
    }, this.format = function(e, l) {
        var i, n, s, a, o;
        return l || 0 === l || (l = t.duration), this.parse = function(t, e) {
            return t = parseInt(t), t < 10 ? e + t : t > 99 ? 99 : t
        }, a = "", o = "", l >= 600 && (a = "0"), l >= 36e3 && (o = "0"), i = this.parse(e % 60, "0"), n = this.parse(e / 60, a), s = this.parse(e / 3600, o), l < 3600 ? n + ":" + i : s + ":" + n + ":" + i
    }, this.calcSeek = function() {
        if (0 == t.duration) return 0;
        var e, l;
        return e = parseFloat(it.css("left")), l = tt.width(), e / l * t.duration
    }, this.showBuffer = function() {
        if (r.hasClass("playing") && null == i) {
            var t = 0;
            w.show(), i = setInterval(function() {
                360 == t && (t = 0), w.css("transform", "rotate(" + t + "deg)"), t += 45
            }, 77)
        }
    }, this.hideBuffer = function() {
        null != i && (clearInterval(i), w.hide(), i = null)
    }, this.seekTo = function(e) {
        if (null == n && 1 == e.which) {
            var l, i, s = "click" == e.type;
            it.addClass("active"), n = setInterval(function() {
                l = t.mouseX - tt.offset().left, i = tt.width(), l < 0 && (l = 0), l > i && (l = i), v = !0, it.css("left", l), u.changeElapsed(u.calcSeek(), !0), u.showTime(), s && $(document).mouseup()
            }, 26)
        }
    }, this.showTime = function(e) {
        var l = tt.width(),
            i = r.offset().left,
            n = tt.offset().left,
            s = t.mouseX - i,
            a = t.mouseX - n;
        v && (s = it.offset().left - i + 8, a = parseFloat(it.css("left"))), s < n - i && (s = n - i), s > n + l && (s = n + l), a < 0 && (a = 0), a > l && (a = l), a = parseInt(a / l * t.duration), V.html(u.format(a, a)), I.show(), I.css("margin-left", s - I.width() / 2)
    }, this.hideTime = function(t) {
        p = !1, v = !1, null == n && I.hide()
    }, this.volumeTo = function(e) {
        if (null == s && 1 == e.which) {
            var l, i, n, a = "click" == e.type;
            s = setInterval(function() {
                l = t.mouseY - G.offset().top - 6, i = G.height(), l < 0 && (l = 0), l > i && (l = i), n = (i - l) / i, t.setVolume(n), a && $(document).mouseup()
            }, 26)
        }
    }, this.volumeWheel = function(e) {
        var l = t.videoObj.volume;
        return e.originalEvent.deltaY < 0 ? t.setVolume(l + .1) : t.setVolume(l - .1), !1
    }, this.showVolume = function() {
        o = !0, a = clearTimeout(a);
        var t = O.height();
        O.stop().css("height", "auto");
        var e = O.height();
        O.stop().css("height", t), O.stop().animate({
            height: e
        }, 250)
    }, this.hideVolume = function() {
        o = !1, a = setTimeout(function() {
            null == s && 0 != O.height() && (O.stop().animate({
                height: 0
            }, 250), a = void 0)
        }, 500)
    }, this.expand = function() {
        e && e()
    }, this.timeUpdate = function() {
        null == n && (u.changeElapsed(t.videoObj.currentTime), v && u.showTime()), t.bufferUpdate()
    }, this.toggle = function() {
        r.hasClass("ended") ? u.hideEndScreen(t.toggle) : (u.showPlayAnimation(), t.toggle())
    }, this.ended = function() {
        r.hasClass("loop") || B.stop().show().css("opacity", 0).animate({
            opacity: 1
        }, 100, function() {
            P.find("span").html("Share"), S.find("span").html("Replay")
        }), t.ended()
    }, this.hideEndScreen = function(t) {
        B.is(":visible") ? (P.find("span").html(""), S.find("span").html(""), B.stop().animate({
            opacity: 0
        }, 100, function() {
            B.hide(), t && t()
        })) : t && t()
    }, this.showPlayAnimation = function() {
        var t, e;
        e = r.hasClass("playing") ? E : T, (t = e.find("svg")).stop(), t.css({
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
            r.hasClass("ended") ? (t.seek(u.calcSeek()), t.play()) : (t.seek(u.calcSeek()), r.hasClass("started") || t.play())
        }, null != n && (clearInterval(n), n = null, 0 != t.duration ? u.hideEndScreen(this.seek) : it.css("left", 0), p || u.hideTime()), null != s && (clearInterval(s), s = null, 0 == o && u.hideVolume()), r.find(".vlButton").removeClass("active")
    }, this.fullBtnEnter = function() {
        if (d = !0, Z.addClass("hover"), !c && !r.hasClass("full")) {
            Z.removeClass("hover");
            var t = 0,
                e = 0,
                l = 0,
                i = Z.find("> s > i"),
                n = i.css("background-position");
            n = parseFloat(n.split(" ")[1]), Z.addClass("hover");
            var s = i.css("background-position");
            s = parseFloat(s.split(" ")[1]), c = setInterval(function() {
                (t < 7 || t > 27) && (t < 7 ? e++ : 28 == t ? e = 5 : 29 == t ? e = 2 : 30 == t ? e = 1 : 31 == t && (e = 0)), l = d ? s : n, Z.find("> s > i").css("background-position", "-" + (242 + 30 * e) + "px " + l + "px"), (35 == ++t || r.hasClass("full")) && (d && !r.hasClass("full") || (c = clearInterval(c), Z.find("> s > i").css("background-position", "")), t = 0)
            }, 50)
        }
    }, this.fullBtnLeave = function() {
        Z.removeClass("hover"), d = !1
    };
    var r = t.obj,
        u = this,
        h = $('<div class="vlHidden"></div>'),
        f = $('<input type="text" tabindex="-1" />'),
        g = $('<div class="vlContainer"/>'),
        m = $('<div class="vlScreen"/>'),
        y = $('<div class="vlPreview"/>'),
        k = $('<div class="vlPlay"/>'),
        w = $('<div class="vlLoad vlHdIcon"/>'),
        B = $('<div class="vlEndScreen" style="display:none"/>'),
        x = $('<div class="vlEndScreenLeft"/>'),
        b = $('<div class="vlEndScreenRight"/>'),
        P = $('<div class="vlEndScreenBtn"><i class="vlHdIcon"></i><span></span></div>'),
        S = $('<div class="vlEndScreenBtn"><i class="vlHdIcon"></i><span></span></div>'),
        T = $('<div class="vlPlayAnimation" style="display:none">\t\t<div><svg width="100%" height="100%" viewbox="0 0 100 100">\t\t\t<rect width="100" height="100" style="fill:black" />\t\t\t<polygon points="32,28 66,50 32,72" style="fill:white" />\t\t</svg></div>\t</div>'),
        E = $('<div class="vlPauseAnimation" style="display:none">\t\t<div><svg width="100%" height="100%" viewbox="0 0 100 100">\t\t\t<rect width="100" height="100" style="fill:black" />\t\t\t<rect x="34" y="28" width="10" height="44" style="fill:white" />\t\t\t<rect x="56" y="28" width="10" height="44" style="fill:white" />\t\t</svg></div>\t</div>'),
        C = $('<div class="vlTimeShow"></div>'),
        I = $('<div class="vlTimeShowContainer"></div>'),
        V = $('<div class="vlTimeShowText"></div>'),
        F = $('<div class="vlTimeShowSvg">\t\t<div>\t\t\t<svg width="12" height="8">\t\t\t\t<polygon points="0,2 12,2 6,8" fill="#999999"/>\t\t\t\t<polygon points="1,0 11,0 11,2 6,7 1,2" fill="#101010"/>\t\t\t</svg>\t\t</div>\t</div>'),
        H = t.video,
        L = $('<div class="vlControls"/>'),
        U = $('<div class="vlcLeft"/>'),
        M = $('<div class="vlcCenter"/>'),
        j = $('<div class="vlcRight"/>'),
        A = $('<div class="vlTime"/>'),
        R = $('<div class="vlTimestamp"/>'),
        D = $('<span class="vlElapsed">0:00</span>'),
        X = $('<span class="vlDuration">0:00</span>'),
        q = $("<span> / </span>"),
        z = $('<div class="vlcVolume"/>'),
        O = $('<div class="vlcVolumeDiv"/>'),
        W = $('<div class="vlcVolumeBar1"/>'),
        Y = $('<div class="vlcVolumeBar2"/>'),
        _ = $('<div class="vlcVolumeBar3"/>'),
        G = $('<div class="vlcVolumeBar4"/>'),
        J = $('<s class="vlHdIcon"/>'),
        K = $('<div class="vlcPlay vlcBtn vlButton"><s><i/></s></div>'),
        N = $('<div class="vlcMute vlcBtn vlButton"><s><i/></s></div>'),
        Q = $('<div class="vlcExpand vlcBtn vlButton"><s><i/></s></div>'),
        Z = $('<div class="vlcFullScreen vlcBtn vlButton"><s><i/></s></div>'),
        tt = $('<div class="vlProgressBar"/>'),
        et = $('<div class="vlBuffer"/>'),
        lt = $('<div class="vlPosition"/>'),
        it = $('<div class="vlSeeker vlButton vlHdIcon"><i class="vlHdIcon"></i></div>');
    this.hiddenUrl = f;
    var nt = t.skinPath + "/skin.css",
        st = t.skinPath + "/img";
    $("#vlPlayer2009css").remove(), $('<link id="vlPlayer2009css" rel="stylesheet"></link>').attr("href", nt).appendTo("head").on("load", function() {
        r.addClass("vlPlayer2009"), r.append(g), r.append(L), r.append(I), r.append(h), h.append(f), g.append(m), m.append(y), m.append(k), m.append(w), m.append(H), m.append(B), m.append(T), m.append(E), B.append(x), B.append(b), x.append(P), b.append(S), L.append(U), L.append(j), L.append(M), U.append(K), M.append(tt), j.append(A), j.append(z), j.append(Q), j.append(Z), I.append(C), C.append(V), C.append(F), tt.append(et), tt.append(lt), tt.append(it), A.append(R), R.append(D), R.append(q), R.append(X), z.append(O), z.append(N), O.append(W), W.append(Y), Y.append(_), _.append(G), G.append(J), H.dblclick(t.toggleFull), k.dblclick(t.toggleFull), N.click(t.toggleMute), Z.click(t.toggleFull), H.click(u.toggle), H.on("timeupdate", u.timeUpdate), H.on("waiting", u.showBuffer), H.on("ended", u.ended), H.on("playing canplay canplaythrough timeupdate pause", u.hideBuffer), H.on("play playing", u.hideEndScreen), k.click(u.toggle), K.click(u.toggle), S.click(u.toggle), Z.mouseenter(u.fullBtnEnter), Z.mouseleave(u.fullBtnLeave), Q.click(u.expand), tt.click(u.seekTo), tt.mousedown(u.seekTo), tt.mousemove(u.showTime), tt.mouseenter(function() {
            p = !0
        }), tt.mouseleave(u.hideTime), it.mouseenter(function() {
            v = !0
        }), it.mouseleave(function() {
            v = !1
        }), z.mouseenter(u.showVolume), z.mouseleave(u.hideVolume), z.mouseenter(function() {
            u.allowScroll = !1
        }), z.mouseleave(function() {
            u.allowScroll = !0
        }), z.on("wheel", u.volumeWheel), G.mousedown(u.volumeTo), G.click(u.volumeTo), $(document).mouseup(u.mouseUp), $(document).on("wheel scroll", u.allowScroll);
        var i = ["red", "orange", "gold", "olive", "green", "teal", "blue", "violet", "pink", "magenta", "white"];
        if (0 == $("#vlPlayer2009css2").length)
            for (var n = $('<style id="#vlPlayer2009css2" />').appendTo("head"), s = 0; s < i.length; s++) n.append("\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlcPlay:hover > s > i,\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlcPlay.active > s > i { background-position: -2px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt.playing .vlcPlay:hover > s > i,\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt.playing .vlcPlay.active > s > i { background-position: -32px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlSeeker > i { background-position: -67px -" + (25 * (s + 1) + 5) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlcMute:hover > s > i,\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlcMute.active > s > i { background-position: -92px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt.muted .vlcMute > s > i { background-position: -122px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlcFullScreen.hover > s > i,\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlcFullScreen.active > s > i { background-position: -242px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt.full .vlcFullScreen.hover > s > i,\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt.full .vlcFullScreen.active > s > i { background-position: -152px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlcExpand:hover > s > i,\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlcExpand.active > s > i { background-position: -182px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt.expanded .vlcExpand:hover > s > i,\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt.expanded .vlcExpand.active > s > i { background-position: -212px -" + (25 * (s + 1) + 1) + "px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlEndScreenBtn i { background-position: -" + 50 * s + "px -350px; }\t\t\t\t\t.vlPlayer2009." + i[s] + "Bt .vlEndScreenRight .vlEndScreenBtn i { background-position: -" + 50 * s + "px -400px; }\t\t\t\t");
        e || Q.remove(), t.toggleFull(!0) || Z.hide(), t.adjust && (r.css("padding-bottom", 25), r.parent().css("padding-bottom", 25));
        for (var a = t.buttonColor, o = t.background, c = ["buttonsbig.png", "gradients.png", "play" + ("black" == o ? "_black" : "") + ".png"], d = 0, s = 0; s < c.length; s++) $("<img />").attr("src", st + "/" + c[s]).on("load", function() {
            ++d == c.length && (t.changeButtonColor(a), t.changeBackground(o), r.addClass("initialized"), r.prop("tabindex", 0), l())
        })
    })
}