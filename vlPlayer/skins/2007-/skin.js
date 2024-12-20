function VLPSkin(e, l, n) {
    var s, t, i, o, a, c, d, u, r, v, p, h, m, f, g, k, w, b, C, I, S, B, T, y, V, P, x, F, A, E, U, z, j, L, R, W, _, D, O;
    this.setIntervals = function() {
        D = setInterval(s.checkResize, 200)
    }, this.clearIntervals = function() {
        clearInterval(j), clearInterval(L), clearInterval(R), clearInterval(W), clearInterval(_), clearInterval(D)
    }, this.changeElapsed = function(l, n) {
        var t = l / e.duration * 100;
        B.html(s.format(l)), n || (C.css("margin-left", t + "%"), b.css("width", t + "%"))
    }, this.changeDuration = function(e) {
        T.html(s.format(e))
    }, this.changeBuffer = function(e) {
        I.css("width", e)
    }, this.changeVolume = function(e) {
        P.css("margin-left", 100 * e + "%")
    }, this.changePreview = function(e) {
        d.css("background-image", ""), $("<img />").attr("src", e).on("load", function() {
            d.css("background-image", "url(" + e + ")")
        })
    }, this.format = function(e) {
        var l, n, s;
        return this.parse = function(e) {
            return e = parseInt(e), e < 10 ? "0" + e : e > 99 ? 99 : e
        }, l = this.parse(e % 60), n = this.parse(e / 60), s = this.parse(e / 3600), "00" == s ? n + ":" + l : s + ":" + n + ":" + l
    }, this.calcSeek = function() {
        if (0 == e.duration) return 0;
        var l, n;
        return l = parseFloat(C.css("margin-left")), n = w.width(), l / n * e.duration
    }, this.showVolume = function() {
        A = !1, t.removeClass("hideVol")
    }, this.hideVolume = function() {
        null == _ ? t.addClass("hideVol") : A = !0
    }, this.showBuffer = function() {
        if (t.hasClass("playing") && null == j) {
            var e = 0;
            u.show(), j = setInterval(function() {
                360 == e && (e = 0), u.css("transform", "rotate(" + e + "deg)"), e += 45
            }, 77)
        }
    }, this.hideBuffer = function() {
        null != j && (clearInterval(j), u.hide(), j = null)
    }, this.seekTo = function(l) {
        if (null == W && 1 == l.which) {
            var n, t, i = "click" == l.type;
            C.addClass("active"), W = setInterval(function() {
                n = e.mouseX - w.offset().left, t = w.width(), n < 0 && (n = 0), n > t && (n = t), C.css("margin-left", n), s.changeElapsed(s.calcSeek(), !0), i && $(document).mouseup()
            }, 26)
        }
    }, this.volumeTo = function(l) {
        if (null == _ && 1 == l.which) {
            var n, s, t, i = "click" == l.type;
            _ = setInterval(function() {
                n = e.mouseX - y.offset().left, s = y.width(), n < 0 && (n = 0), n > s && (n = s), t = n / s, e.setVolume(t), i && $(document).mouseup()
            }, 26)
        }
    }, this.volumeWheel = function(l) {
        var n = e.videoObj.volume;
        return l.originalEvent.deltaY < 0 ? e.setVolume(n + .1) : e.setVolume(n - .1), !1
    }, this.timeUpdate = function() {
        null == W && s.changeElapsed(e.videoObj.currentTime), e.bufferUpdate()
    }, this.ended = function() {
        t.removeClass("started"), e.ended()
    }, this.startExpAnim = function() {
        if (null == L) {
            var e = 1;
            L = setInterval(function() {
                4 == e && (e = 0), e < 3 ? E.css("background-position", "-" + (220 + 22 * e) + "px 0px") : E.css("background-position", ""), e++
            }, 100)
        }
    }, this.stopExpAnim = function() {
        null != L && (clearInterval(L), E.css("background-position", ""), L = null)
    }, this.startFullAnim = function() {
        if (null == R) {
            var e = 1,
                l = 1;
            R = setInterval(function() {
                if (10 == e) e = 0, l = 0;
                else if (l >= 5 && l <= 8) return void l++;
                U.css("background-position", "-" + 22 * e + "px 0px"), e++, l++
            }, 77)
        }
    }, this.stopFullAnim = function() {
        null != R && (clearInterval(R), U.css("background-position", ""), R = null)
    }, this.checkResize = function() {
        O != t.width() && (O = t.width(), s.resize())
    }, this.resize = function() {
        t.removeClass("compact hideTimer"), h.width() < 100 && (t.addClass("compact hideVol"), h.width() < 100 && t.addClass("hideTimer"))
    }, this.mouseUp = function(l) {
        null != W && (clearInterval(W), W = null, C.removeClass("active"), 0 != e.duration ? (e.seek(s.calcSeek()), t.hasClass("started") || e.play()) : C.css("margin-left", 0)), null != _ && (clearInterval(_), _ = null, A && s.hideVolume()), t.find(".vlButton").removeClass("active")
    }, t = e.obj, s = this, a = $('<div class="vlScreen"></div>'), i = $('<div class="vlPreload"></div>'), o = $('<input type="text" tabindex="-1" />'), u = $('<div class="vlsLoad"></div>'), r = $('<div class="vlsPlay vlButton"></div>'), d = $('<div class="vlPreview"></div>'), c = $('<div class="vlScreenContainer"></div>'), v = $('<div class="vlControls"></div>'), p = $('<div class="vlcLeft"></div>'), h = $('<div class="vlcCenter"></div>'), m = $('<div class="vlcRight"></div>'), f = $('<div class="vlSeparator"></div>'), g = $('<div class="vlcPlay vlButton"></div>'), k = $('<div class="vlcStop vlButton"></div>'), w = $('<div class="vlProgress"></div>'), b = $('<div class="vlPosition"></div>'), C = $('<div class="vlSeeker vlButton"></div>'), I = $('<div class="vlBuffer"></div>'), S = $('<div class="vlTimer"> / </div>'), B = $('<span class="vltPos">00:00</span>'), T = $('<span class="vltDur">' + this.format(e.duration) + "</span>"), y = $('<div class="vlcSoundBar"></div>'), V = $('<div class="vlcSound vlButton"></div>'), P = $('<div class="vlcSoundSlider vlButton"></div>'), x = $('<span class="vlcSoundContainer"></span>'), F = $('<span class="vlcSoundContainerAbsolute"></span>'), E = $('<div class="vlcExpand vlButton"></div>'), U = $('<div class="vlcFull vlButton"></div>'), z = $('<div class="vlcCloseFull vlButton"></div>'), this.hiddenUrl = o;
    var X = e.skinPath + "/skin.css",
        M = e.skinPath + "/img";
    $("#vlPlayer2007css").remove(), $('<link id="vlPlayer2007css" rel="stylesheet"></link>').attr("href", X).appendTo("head").on("load", function() {
        t.addClass("vlPlayer2007"), t.append(i), t.append(c), t.append(v), i.append(o), c.append(a), a.append(d), a.append(u), a.append(r), a.append(e.video), v.append(p), v.append(m), v.append(h), p.append(g), p.append(k), h.append(w), m.append(S), m.append(f.clone()), m.append(x), m.append(f.clone()), m.append(E), m.append(U), m.append(z), x.append(F), F.append(y), x.append(V), y.append(P), w.append(b), w.append(C), w.append(I), S.prepend(B), S.append(T), e.video.click(e.toggle), e.video.dblclick(e.toggleFull), g.click(e.toggle), r.click(e.toggle), r.dblclick(e.toggleFull), k.click(e.stop), V.click(e.toggleMute), U.click(e.toggleFull), z.click(e.toggleFull), U.on("mouseenter focus", s.startFullAnim), U.on("mouseleave blur", s.stopFullAnim), E.on("mouseenter focus", s.startExpAnim), E.on("mouseleave blur", s.stopExpAnim), w.click(s.seekTo), w.mousedown(s.seekTo), b.mousedown(s.seekTo), b.click(s.seekTo), C.mousedown(s.seekTo), I.mousedown(s.seekTo), x.focus(s.showVolume), x.mouseenter(s.showVolume), x.mouseleave(s.hideVolume), x.blur(s.hideVolume), P.mousedown(s.volumeTo), y.mousedown(s.volumeTo), y.click(s.volumeTo), y.on("wheel", s.volumeWheel), y.mouseenter(function() {
            s.allowScroll = !1
        }), y.mouseleave(function() {
            s.allowScroll = !0
        }), V.mouseenter(function() {
            s.allowScroll = !1
        }), V.mouseleave(function() {
            s.allowScroll = !0
        }), V.on("wheel", s.volumeWheel), e.video.on("timeupdate", s.timeUpdate), e.video.on("ended", s.ended), e.video.on("waiting", s.showBuffer), e.video.on("playing canplay canplaythrough timeupdate pause", s.hideBuffer), $(document).mouseup(s.mouseUp), $(document).on("wheel scroll", s.allowScroll), l ? E.click(l) : E.hide(), e.toggleFull(!0) || (U.hide(), z.hide()), e.adjust);
        for (var A = e.buttonColor, j = e.background, L = ["loop.png", "buttons_" + A + ".png", "play" + ("black" == j ? "_black" : "") + ".png", "full" + ("black" == j ? "_black" : "") + ".png", "buffer.png"], R = 0, W = 0; W < L.length; W++) $("<img />").attr("src", M + "/" + L[W]).on("load", function() {
            ++R == L.length && ("red" != A && t.addClass(A + "Bt"), "white" != j && t.addClass(j + "Bg"), t.addClass("initialized"), t.prop("tabindex", 0), n())
        })
    })
}