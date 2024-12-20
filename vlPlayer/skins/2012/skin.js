function VLPSkin(e, s, i) {
    var t, n, o, l, a, d = !1,
        v = !1,
        c = !1,
        p = !1,
        u = !0,
        h = !1;
    this.setIntervals = function() {}, this.clearIntervals = function() {
        clearInterval(n), clearInterval(o), clearInterval(l)
    }, this.resize = function() {}, this.changeElapsed = function(s, i) {
        var t = s / e.duration * 100;
        re.html(f.format(s)), i || (b.css("margin-left", t + "%"), ue.css("width", t + "%"))
    }, this.changeDuration = function(s) {
        fe.html(f.format(s)), d = !1, e.hd ? M.show() : M.hide()
    }, this.changeBuffer = function(e) {
        y.css("width", e)
    }, this.changeVolume = function(e) {
        var s = 60 + E.width() - E.width() * e;
        pe.css("margin-left", 100 * e + "%"), E.css("background-position", "-" + s + "px -41px")
    }, this.loopChange = function() {
        r.hasClass("loop") ? (Y.removeClass("active"), _.addClass("active")) : (Y.addClass("active"), _.removeClass("active"))
    }, this.rateChange = function(e) {
        var s = [.25, .5, 1, 2, 4].indexOf(e);
        A.find(".vloButton").removeClass("active"), A.find(".vloButton").eq(s).addClass("active")
    }, this.changePreview = function(e) {
        ae.css("background-image", ""), $("<img />").attr("src", e).on("load", function() {
            ae.css("background-image", "url(" + e + ")")
        })
    }, this.format = function(s, i) {
        var t, n, o, l, a;
        return i || 0 === i || (i = e.duration), this.parse = function(e, s) {
            return (e = parseInt(e)) < 10 ? s + e : e > 99 ? 99 : e
        }, l = "", a = "", i >= 600 && (l = "0"), i >= 36e3 && (a = "0"), t = this.parse(s % 60, "0"), n = this.parse(s / 60, l), o = this.parse(s / 3600, a), i < 3600 ? n + ":" + t : o + ":" + n + ":" + t
    }, this.calcSeek = function() {
        return 0 == e.duration ? 0 : (parseFloat(b.css("margin-left")) + 8) / k.width() * e.duration
    }, this.showBuffer = function() {
        if (r.hasClass("playing") && null == n) {
            var e = 0;
            ve.show(), n = setInterval(function() {
                360 == e && (e = 0), ve.css("transform", "rotate(" + e + "deg)"), ve.css("-ms-transform", "rotate(" + e + "deg)"), e += 45
            }, 77)
        }
    }, this.hideBuffer = function() {
        null != n && (clearInterval(n), ve.hide(), n = null)
    }, this.seekTo = function(s) {
        if (null == o && 1 == s.which) {
            var i, n, l, a = "click" == s.type;
            b.addClass("active"), o = setInterval(function() {
                l = k.width(), i = e.mouseX - k.offset().left, (n = i - 8) < 0 && (n = 0), n > l - 16 && (n = l - 16), i < 0 && (i = 0), i > l && (i = l), t = e.duration * (i / l), b.css("margin-left", n), f.changeElapsed(t, !0), f.showTime(), a && $(document).mouseup()
            }, 26)
        }
    }, this.showTime = function(s) {
        var i, t, n, o = k.offset().left,
            l = k.width(),
            a = e.mouseX - o;
        a < 0 && (a = 0), a > l && (a = l), i = e.duration * (a / l), Ce.html(f.format(i, i)), we.find("svg").hide(), we.find("svg").eq(1).show(), ge.show(), n = -6, (o = a - (t = ge.width()) / 2) < 4 && ((n += o - 4) <= -12 && (n = -12, we.find("svg").hide(), we.find("svg").eq(0).show()), o = 4), o > l - 4 - t && ((n += o + t - l + 4) >= 0 && (n = 4, we.find("svg").hide(), we.find("svg").eq(2).show()), o = l - 4 - t), ge.css("margin-left", o), we.find("svg").css("margin-left", n)
    }, this.hideTime = function(e) {
        v = !1, null == o && ge.hide()
    }, this.volumeTo = function(s) {
        if (!l && 1 == s.which) {
            var i, t, n, o = function() {
                    return e.mouseX - E.offset().left
                },
                a = "click" == s.type;
            I.addClass("active"), a ? h || (i = o(), t = E.width(), i < 0 ? e.toggleMute() : (i > t && (i = t), n = i / t, e.setVolume(n))) : l = setInterval(function() {
                i = o(), t = E.width(), !h && i < 0 || (h = !0, i < 0 && (i = 0), i > t && (i = t), n = i / t, e.setVolume(n), a && $(document).mouseup())
            }, 26), h = !1, f.showControls(), f.showProgress()
        }
    }, this.volumeWheel = function(s) {
        var i = e.videoObj.volume;
        return s.originalEvent.deltaY < 0 ? e.setVolume(i + .1) : e.setVolume(i - .1), !1
    }, this.showVolume = function() {
        l || V.stop().animate({
            width: 65
        }, 200), p = !0
    }, this.hideVolume = function() {
        l || V.stop().animate({
            width: 0
        }, 500), p = !1
    }, this.hideControls = function() {
        f.hideProgress(), u && null == l && null == o && (F.hasClass("active") || r.hasClass("playing") && (g.stop().animate({
            "margin-bottom": -27
        }, 800), C.stop().animate({
            "padding-bottom": 0
        }, 800), u = !1))
    }, this.showControls = function() {
        u || (g.stop().animate({
            "margin-bottom": 0
        }, 200), C.stop().animate({
            "padding-bottom": 27
        }, 200), u = !0)
    }, this.hideProgress = function() {
        c && null == l && null == o && (F.hasClass("active") || (w.stop().animate({
            height: 3
        }, 500), he.stop().animate({
            height: 0,
            width: 0
        }, 500), c = !1))
    }, this.showProgress = function() {
        c || (w.stop().animate({
            height: 8
        }, 200), he.stop().animate({
            height: 16,
            width: 16
        }, 200), c = !0)
    }, this.showOptions = function() {
        F.toggleClass("active"), q.toggle(), f.showControls(), f.showProgress()
    }, this.hideOptions = function() {
        F.hasClass("active") && L.click()
    }, this.expand = function() {
        s && s()
    }, this.toggleHD = function() {
        e.hd && f.hideEndScreen(function() {
            d = !0, f.showBuffer(), e.toggleHD(), r.hasClass("hd720p") ? (Z.addClass("active"), N.removeClass("active")) : (Z.removeClass("active"), N.addClass("active")), r.hasClass("ended") && e.play()
        })
    }, this.timeUpdate = function() {
        d || r.hasClass("ended") || (null == o && f.changeElapsed(e.videoObj.currentTime), e.bufferUpdate(), f.hideEndScreen())
    }, this.toggle = function() {
        r.hasClass("ended") ? f.hideEndScreen(e.toggle) : e.toggle()
    }, this.ended = function() {
        r.hasClass("ended") || (r.hasClass("loop") || de.stop().show().css("opacity", 0).animate({
            opacity: 1
        }, 100), f.showControls(), e.ended())
    }, this.hideEndScreen = function(e) {
        de.is(":visible") && r.hasClass("ended") ? de.stop().animate({
            opacity: 0
        }, 100, function() {
            de.hide(), "function" == typeof e && e()
        }) : "function" == typeof e && e()
    }, this.mouseUp = function(s) {
        this.seek = function() {
            r.hasClass("ended") ? (e.seek(t), e.play()) : (e.seek(t), r.hasClass("started") || e.play())
        }, null != o && (clearInterval(o), o = null, 0 != e.duration ? f.hideEndScreen(this.seek) : b.css("margin-left", 0), v || f.hideTime()), null != l && (clearInterval(l), l = null, p || f.hideVolume(), I.removeClass("active")), overContainer || f.hideControls()
    };
    var r = e.obj,
        f = this,
        m = ($('<div class="vlHidden"></div>'), $('<input type="text" tabindex="-1" />')),
        g = $('<div class="vlControls"></div>'),
        C = $('<div class="vlScreenContainer"></div>'),
        w = $('<div class="vlProgressAll"></div>'),
        k = $('<div class="vlProgressBar"></div>'),
        B = $('<div class="vlEmptyBar"></div>'),
        y = $('<div class="vlBuffer"></div>'),
        T = $('<div class="vlProgress"><b></b></div>'),
        S = $('<div class="vlSeekerBar"></div>'),
        b = $('<div class="vlSeeker"><s><i></i></s></div>'),
        P = $('<div class="vlControlsBar"></div>'),
        x = $('<div class="vlPlay vlButtons vlButtonsLeft"><i></i></div>'),
        I = $('<div class="vlSound vlButtons vlButtonsLeft"></div>'),
        O = $('<div class="vlMute"></div>'),
        V = $('<div class="vlSoundBar"></div>'),
        E = $('<div class="vlVolumeBar"><s></s></div>'),
        D = $('<div class="vlTime vlButtons vlButtonsLeft"></div>'),
        R = $('<div class="vlTimeText"><span class="vlCurrent">0:00</span> / <span class="vlDuration">0:00</span></div>'),
        H = $('<div class="vlFullScreen vlButtons vlButtonsRight"><i></i></div>'),
        U = $('<div class="vlExpand vlButtons vlButtonsRight"><i></i></div>'),
        F = $('<div class="vlOptions vlButtons vlButtonsRight"></div>'),
        L = $('<div class="vlGearIcon"><b><i></i></b></div>'),
        q = $('<div class="vlOptionsPopup"></div>'),
        j = $('<div class="vloTable"></div>'),
        X = $('<div class="vloTable"></div>'),
        z = $('<div class="vloRow"></div>'),
        M = $('<div class="vloRow"></div>'),
        W = $('<div class="vloRow"></div>'),
        A = $('<div class="vloRow vloPlaySpeed"></div>'),
        G = $('<div class="vloCell">Loop:</div>'),
        Q = $('<div class="vloCell vloCell2"></div>'),
        Y = $('<div class="vloButton active">Off</div>'),
        _ = $('<div class="vloButton">On</div>'),
        J = $('<div class="vloCell">Quality:</div>'),
        K = $('<div class="vloCell vloCell2"></div>'),
        N = $('<div class="vloButton">SD</div>'),
        Z = $('<div class="vloButton">HD</div>'),
        ee = $('<div class="vloCell">Play Speed:</div><div class="vloCell"></div>'),
        se = $('<div class="vloCell"><div class="vloButton">.25x</div></div>'),
        ie = $('<div class="vloCell"><div class="vloButton">.5x</div></div>'),
        te = $('<div class="vloCell"><div class="vloButton">1x</div></div>'),
        ne = $('<div class="vloCell"><div class="vloButton">2x</div></div>'),
        oe = $('<div class="vloCell"><div class="vloButton">4x</div></div>'),
        le = $('<div class="vlScreen"></div>'),
        ae = $('<div class="vlPreview"></div>'),
        de = $('<div class="vlPlayScreen"><i></i></div>'),
        ve = $('<div class="vlBuffering"></div>'),
        ce = e.video,
        pe = E.find("> s"),
        ue = T.find("> b"),
        he = b.find("> s > i"),
        re = R.find("> .vlCurrent"),
        fe = R.find("> .vlDuration"),
        me = $('<div class="vlTimeShow"></div>'),
        ge = $('<div class="vlTimeShowContainer"></div>'),
        Ce = $('<div class="vlTimeShowText">0:00</div>'),
        we = $('<div class="vlTimeShowSvg">\t\t<div>\t\t\t<svg width="8" height="8">\t\t\t\t<polygon points="0,2 8,2 0,8" fill="#999999"/>\t\t\t\t<polygon points="1,0 7,0 7,2 1,7 1,2" fill="#101010"/>\t\t\t</svg>\t\t\t<svg width="12" height="8">\t\t\t\t<polygon points="0,2 12,2 6,8" fill="#999999"/>\t\t\t\t<polygon points="1,0 11,0 11,2 6,7 1,2" fill="#101010"/>\t\t\t</svg>\t\t\t<svg width="8" height="8">\t\t\t\t<polygon points="0,2 8,2 8,8" fill="#999999"/>\t\t\t\t<polygon points="1,0 7,0 7,7 1,2" fill="#101010"/>\t\t\t</svg>\t\t</div>\t</div>');
    this.hiddenUrl = m;
    var $e = e.skinPath + "/skin.css?" + window.vlpv,
        ke = e.skinPath + "/img";
    $("#vlPlayer2012css").remove(), $('<link id="vlPlayer2012css" rel="stylesheet"></link>').attr("href", $e).appendTo("head").on("load", function() {
        r.addClass("vlPlayer2012"), r.append(g), r.append(C), C.append(le), le.append(ae), le.append(ve), le.append(de), le.append(ce), g.append(w), g.append(P), w.append(S), w.append(k), w.append(q), w.append(ge), S.append(b), k.append(B), k.append(y), k.append(T), ge.append(me), me.append(Ce), me.append(we), P.append(x), P.append(I), P.append(D), P.append(H), P.append(U), P.append(F), I.append(O), I.append(V), V.append(E), D.append(R), F.append(L), q.append(j), q.append(X), j.append(z), j.append(M), j.append(W), X.append(A), z.append(G), z.append(Q), Q.append(Y), Q.append(_), M.append(J), M.append(K), K.append(N), K.append(Z), W.append(ee), A.append(se), A.append(ie), A.append(te), A.append(ne), A.append(oe), le.dblclick(e.toggleFull), le.click(f.toggle), S.mousemove(f.showTime), k.mousemove(f.showTime), S.mouseleave(f.hideTime), k.mouseleave(f.hideTime), S.on("click mousedown", f.seekTo), k.on("click mousedown", f.seekTo), S.mousemove(function() {
            v = !0
        }), k.mousemove(function() {
            v = !0
        }), x.click(f.toggle), I.click(f.volumeTo), I.mousedown(f.volumeTo), I.mouseenter(f.showVolume), I.mouseleave(f.hideVolume), I.on("wheel", f.volumeWheel), U.click(f.expand), H.click(e.toggleFull), ce.on("timeupdate", f.timeUpdate), ce.on("waiting", f.showBuffer), ce.on("playing canplay canplaythrough timeupdate pause", f.hideBuffer), ce.on("playing seeked", function() {
            d = !1
        }), ce.on("ended", f.ended), q.mousedown(!1), L.mousedown(!1), L.click(f.showOptions), Y.click(function() {
            r.removeClass("loop"), f.loopChange()
        }), _.click(function() {
            r.addClass("loop"), f.loopChange()
        }), Z.click(function() {
            r.hasClass("hd720p") || f.toggleHD()
        }), N.click(function() {
            r.hasClass("hd720p") && f.toggleHD()
        }), A.find(".vloButton").click(function() {
            var s = A.find(".vloButton").index($(this));
            e.setRate([.25, .5, 1, 2, 4][s])
        }), le.mouseenter(function() {
            de.find("> i").stop().animate({
                opacity: 1
            }, 250)
        }), le.mouseleave(function() {
            de.find("> i").stop().animate({
                opacity: 0
            }, 250)
        }), r.on("mousemove keydown", function() {
            f.showControls(), f.showProgress(), null != a && clearTimeout(a), a = setTimeout(function() {
                f.hideControls(), a = null, overContainer = !1
            }, 3e3), overContainer = !0
        }), r.mouseleave(function() {
            f.hideControls(), overContainer = !1
        }), r.mousedown(f.hideOptions), $(document).mousedown(f.hideOptions), $(document).mouseup(f.mouseUp), $(document).on("wheel scroll", function() {
            return !p
        }), s || U.remove(), e.hd || M.hide(), r.hasClass("hd720p") ? (Z.addClass("active"), N.removeClass("active")) : (Z.removeClass("active"), N.addClass("active")), e.toggleFull(!0) || H.hide(), f.rateChange(1);
        for (var t = ["sprites.png", "play.png", "play_red.png", "seeker.png"], n = 0, o = 0; o < t.length; o++) $("<img />").attr("src", ke + "/" + t[o]).on("load", function() {
            ++n == t.length && (r.mousemove(), r.addClass("initialized"), r.prop("tabindex", 0), i())
        })
    })
}