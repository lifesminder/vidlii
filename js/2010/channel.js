(function () {
    function g(a) {
        throw a;
    }
    var j = true,
        k = null,
        l = false,
        m = playnav,
        n = window,
        aa = Object,
        ba = display_message,
        ca = _removeclass,
        q = undefined,
        da = parseInt,
        ea = parseFloat,
        fa = String,
        ga = _addclass,
        ha = get_channel_backend,
        r = document,
        s = Math;
    function ja(a, b) {
        return (a.width = b);
    }
    function ka(a, b) {
        return (a.innerHTML = b);
    }
    function la(a, b) {
        return (a.getDate = b);
    }
    function ma(a, b) {
        return (a.value = b);
    }
    function na(a, b) {
        return (a.currentTarget = b);
    }
    function oa(a, b) {
        return (a.left = b);
    }
    function pa(a, b) {
        return (a.keyCode = b);
    }
    function qa(a, b) {
        return (a.type = b);
    }
    function ra(a, b) {
        return (a.visibility = b);
    }
    function sa(a, b) {
        return (a.setDate = b);
    }
    function ua(a, b) {
        return (a.toString = b);
    }
    function va(a, b) {
        return (a.length = b);
    }
    function wa(a, b) {
        return (a.position = b);
    }
    function t(a, b) {
        return (a.className = b);
    }
    function xa(a, b) {
        return (a.target = b);
    }
    function ya(a, b) {
        return (a.colSpan = b);
    }
    function za(a, b) {
        return (a.display = b);
    }
    function Aa(a, b) {
        return (a.height = b);
    }
    var v = "appendChild",
        x = "push",
        Ba = "getCurPlaylistName",
        Ca = "getBoundingClientRect",
        y = "width",
        Da = "text",
        Ea = "round",
        Fa = "slice",
        Ga = "replace",
        Ha = "nodeType",
        Ia = "floor",
        Ja = "resizeScrollbox",
        Ka = "innerHTML",
        La = "offsetWidth",
        Ma = "charAt",
        Na = "createTextNode",
        z = "getDate",
        A = "preventDefault",
        B = "indexOf",
        Oa = "dispatchEvent",
        Pa = "capture",
        C = "left",
        Qa = "screenX",
        Ra = "screenY",
        Sa = "getBoxObjectFor",
        Ta = "setMonth",
        Ua = "charCode",
        Va = "focus",
        D = "createElement",
        Wa = "setFullYear",
        E = "keyCode",
        Xa = "firstChild",
        Ya = "clientLeft",
        Za = "addEventListener",
        $a = "clientTop",
        ab = "handleEvent",
        F = "type",
        bb = "setMinutes",
        cb = "defaultView",
        H = "bind",
        db = "name",
        eb = "getHours",
        fb = "getTime",
        gb = "clientX",
        hb = "clientY",
        ib = "documentElement",
        I = "setDate",
        jb = "fill",
        kb = "scrollTop",
        lb = "previousSibling",
        mb = "toString",
        J = "getMonth",
        K = "length",
        nb = "propertyIsEnumerable",
        ob = "position",
        L = "prototype",
        pb = "getDay",
        qb = "clientWidth",
        rb = "setTimeout",
        sb = "document",
        tb = "removeEventListener",
        ub = "getSeconds",
        vb = "ctrlKey",
        wb = "split",
        xb = "offsetParent",
        yb = "stopPropagation",
        zb = "offsetLeft",
        M = "style",
        N = "body",
        Ab = "removeChild",
        Bb = "parent",
        Cb = "target",
        O = "call",
        Db = "setSeconds",
        Eb = "getYear",
        P = "getFullYear",
        Fb = "detachEvent",
        Gb = "clientHeight",
        Hb = "scrollLeft",
        Ib = "bottom",
        Jb = "substring",
        Kb = "apply",
        Lb = "tagName",
        Mb = "setHours",
        Nb = "empty",
        Ob = "getUTCHours",
        Pb = "parentNode",
        Qb = "getMinutes",
        Rb = "offsetTop",
        Q = "height",
        Sb = "offsetHeight",
        Tb = "join",
        Ub = "toLowerCase",
        Vb = "right",
        Wb = "event",
        Xb = "getTimezoneOffset",
        R;
    var S = this,
        T = function (a, b, c) {
            a = a[wb](".");
            c = c || S;
            !(a[0] in c) && c.execScript && c.execScript("var " + a[0]);
            for (var d; a[K] && (d = a.shift()); )
                if (!a[K] && b !== q) c[d] = b;
                else c = c[d] ? c[d] : (c[d] = {});
        },
        Yb = function (a, b) {
            a = a[wb](".");
            b = b || S;
            for (var c; (c = a.shift()); )
                if (b[c]) b = b[c];
                else return k;
            return b;
        },
        Zb = function () {},
        $b = function (a) {
            var b = typeof a;
            if (b == "object")
                if (a) {
                    if (
                        a instanceof Array ||
                        (!(a instanceof aa) && aa[L][mb][O](a) == "[object Array]") ||
                        (typeof a[K] == "number" &&
                            typeof a.splice != "undefined" &&
                            typeof a[nb] != "undefined" &&
                            !a[nb]("splice"))
                    )
                        return "array";
                    if (
                        !(a instanceof aa) &&
                        (aa[L][mb][O](a) == "[object Function]" ||
                            (typeof a[O] != "undefined" && typeof a[nb] != "undefined" && !a[nb]("call")))
                    )
                        return "function";
                } else return "null";
            else if (b == "function" && typeof a[O] == "undefined") return "object";
            return b;
        },
        ac = function (a) {
            return $b(a) == "array";
        },
        bc = function (a) {
            var b = $b(a);
            return b == "array" || (b == "object" && typeof a[K] == "number");
        },
        cc = function (a) {
            return typeof a == "string";
        },
        dc = function (a) {
            return $b(a) == "function";
        },
        ec = function (a) {
            a = $b(a);
            return a == "object" || a == "array" || a == "function";
        },
        hc = function (a) {
            if (a.hasOwnProperty && a.hasOwnProperty(fc)) return a[fc];
            a[fc] || (a[fc] = ++gc);
            return a[fc];
        },
        fc = "closure_hashCode_" + s[Ia](s.random() * 2147483648)[mb](36),
        gc = 0,
        ic = function (a) {
            var b = $b(a);
            if (b == "object" || b == "array") {
                if (a.t) return a.t[O](a);
                b = b == "array" ? [] : {};
                for (var c in a) b[c] = ic(a[c]);
                return b;
            }
            return a;
        },
        jc = function (a, b) {
            var c = b || S;
            if (arguments[K] > 2) {
                var d = Array[L][Fa][O](arguments, 2);
                return function () {
                    var e = Array[L][Fa][O](arguments);
                    Array[L].unshift[Kb](e, d);
                    return a[Kb](c, e);
                };
            } else
                return function () {
                    return a[Kb](c, arguments);
                };
        },
        kc =
            Date.now ||
            function () {
                return +new Date();
            },
        U = function (a, b) {
            function c() {}
            c.prototype = b[L];
            a.k = b[L];
            a.prototype = new c();
        };
    Function[L].bind = function (a) {
        if (arguments[K] > 1) {
            var b = Array[L][Fa][O](arguments, 1);
            b.unshift(this, a);
            return jc[Kb](k, b);
        } else return jc(this, a);
    };
    var lc = Array[L],
        mc = lc[B]
            ? function (a, b, c) {
                  return lc[B][O](a, b, c);
              }
            : function (a, b, c) {
                  c = c == k ? 0 : c < 0 ? s.max(0, a[K] + c) : c;
                  if (cc(a)) {
                      if (!cc(b) || b[K] != 1) return -1;
                      return a[B](b, c);
                  }
                  for (c = c; c < a[K]; c++) if (c in a && a[c] === b) return c;
                  return -1;
              },
        nc = lc.forEach
            ? function (a, b, c) {
                  lc.forEach[O](a, b, c);
              }
            : function (a, b, c) {
                  for (var d = a[K], e = cc(a) ? a[wb]("") : a, f = 0; f < d; f++) f in e && b[O](c, e[f], f, a);
              },
        oc = function (a, b) {
            b = mc(a, b);
            var c;
            if ((c = b >= 0)) lc.splice[O](a, b, 1)[K] == 1;
            return c;
        },
        pc = function () {
            return lc.concat[Kb](lc, arguments);
        },
        qc = function (a) {
            if (ac(a)) return pc(a);
            else {
                for (var b = [], c = 0, d = a[K]; c < d; c++) b[c] = a[c];
                return b;
            }
        },
        sc = function (a) {
            return lc.splice[Kb](a, rc(arguments, 1));
        },
        rc = function (a, b, c) {
            return arguments[K] <= 2 ? lc[Fa][O](a, b) : lc[Fa][O](a, b, c);
        };
    var tc;
    var uc = function (a) {
            return (a = a.className) && typeof a[wb] == "function" ? a[wb](/\s+/) : [];
        },
        vc = function (a) {
            var b = uc(a),
                c;
            c = rc(arguments, 1);
            for (var d = 0, e = 0; e < c[K]; e++)
                if (!(mc(b, c[e]) >= 0)) {
                    b[x](c[e]);
                    d++;
                }
            c = d == c[K];
            t(a, b[Tb](" "));
            return c;
        },
        wc = function (a) {
            var b = uc(a),
                c;
            c = rc(arguments, 1);
            for (var d = 0, e = 0; e < b[K]; e++)
                if (mc(c, b[e]) >= 0) {
                    sc(b, e--, 1);
                    d++;
                }
            c = d == c[K];
            t(a, b[Tb](" "));
            return c;
        },
        xc = function (a, b) {
            return mc(uc(a), b) >= 0;
        };
    var yc = function (a, b) {
        this.x = a !== q ? a : 0;
        this.y = b !== q ? b : 0;
    };
    yc[L].t = function () {
        return new yc(this.x, this.y);
    };
    ua(yc[L], function () {
        return "(" + this.x + ", " + this.y + ")";
    });
    var zc = function (a, b) {
        return new yc(a.x - b.x, a.y - b.y);
    };
    var Ac = function (a, b) {
        ja(this, a);
        Aa(this, b);
    };
    Ac[L].t = function () {
        return new Ac(this[y], this[Q]);
    };
    ua(Ac[L], function () {
        return "(" + this[y] + " x " + this[Q] + ")";
    });
    Ac[L].floor = function () {
        ja(this, s[Ia](this[y]));
        Aa(this, s[Ia](this[Q]));
        return this;
    };
    Ac[L].round = function () {
        ja(this, s[Ea](this[y]));
        Aa(this, s[Ea](this[Q]));
        return this;
    };
    var Bc = function (a, b, c) {
            for (var d in a) b[O](c, a[d], d, a);
        },
        Cc = function (a, b, c) {
            for (var d in a) if (b[O](c, a[d], d, a)) return d;
        },
        Dc = function (a, b) {
            var c;
            if ((c = b in a)) delete a[b];
            return c;
        },
        Ec = function (a, b, c) {
            if (b in a) return a[b];
            return c;
        },
        Fc = [
            "constructor",
            "hasOwnProperty",
            "isPrototypeOf",
            "propertyIsEnumerable",
            "toLocaleString",
            "toString",
            "valueOf",
        ],
        Gc = function (a) {
            for (var b, c, d = 1; d < arguments[K]; d++) {
                c = arguments[d];
                for (b in c) a[b] = c[b];
                for (var e = 0; e < Fc[K]; e++) {
                    b = Fc[e];
                    if (aa[L].hasOwnProperty[O](c, b)) a[b] = c[b];
                }
            }
        };
    var Hc = function (a) {
            for (var b = 1; b < arguments[K]; b++) {
                var c = fa(arguments[b])[Ga](/\$/g, "$$$$");
                a = a[Ga](/\%s/, c);
            }
            return a;
        },
        Ic = function (a) {
            return a[Ga](/^[\s\xa0]+|[\s\xa0]+$/g, "");
        },
        Oc = function (a, b) {
            if (b) return a[Ga](Jc, "&amp;")[Ga](Kc, "&lt;")[Ga](Lc, "&gt;")[Ga](Mc, "&quot;");
            else {
                if (!Nc.test(a)) return a;
                if (a[B]("&") != -1) a = a[Ga](Jc, "&amp;");
                if (a[B]("<") != -1) a = a[Ga](Kc, "&lt;");
                if (a[B](">") != -1) a = a[Ga](Lc, "&gt;");
                if (a[B]('"') != -1) a = a[Ga](Mc, "&quot;");
                return a;
            }
        },
        Jc = /&/g,
        Kc = /</g,
        Lc = />/g,
        Mc = /\"/g,
        Nc = /[&<>\"]/,
        V = function (a, b, c) {
            a = c !== q ? a.toFixed(c) : fa(a);
            c = a[B](".");
            if (c == -1) c = a[K];
            return new Array(s.max(0, b - c) + 1)[Tb]("0") + a;
        },
        Qc = function (a, b) {
            var c = 0;
            a = Ic(fa(a))[wb](".");
            b = Ic(fa(b))[wb](".");
            for (var d = s.max(a[K], b[K]), e = 0; c == 0 && e < d; e++) {
                var f = a[e] || "",
                    h = b[e] || "",
                    i = new RegExp("(\\d*)(\\D*)", "g"),
                    o = new RegExp("(\\d*)(\\D*)", "g");
                do {
                    var p = i.exec(f) || ["", "", ""],
                        u = o.exec(h) || ["", "", ""];
                    if (p[0][K] == 0 && u[0][K] == 0) break;
                    c =
                        Pc(p[1][K] == 0 ? 0 : da(p[1], 10), u[1][K] == 0 ? 0 : da(u[1], 10)) ||
                        Pc(p[2][K] == 0, u[2][K] == 0) ||
                        Pc(p[2], u[2]);
                } while (c == 0);
            }
            return c;
        },
        Pc = function (a, b) {
            if (a < b) return -1;
            else if (a > b) return 1;
            return 0;
        };
    var Rc,
        Sc,
        Tc,
        Uc,
        Vc,
        Wc,
        Xc = function () {
            return S.navigator ? S.navigator.userAgent : k;
        },
        Yc = function () {
            return S.navigator;
        };
    Vc = Uc = Tc = Sc = Rc = l;
    var Zc;
    if ((Zc = Xc())) {
        var $c = Yc();
        Rc = Zc[B]("Opera") == 0;
        Sc = !Rc && Zc[B]("MSIE") != -1;
        Uc = (Tc = !Rc && Zc[B]("WebKit") != -1) && Zc[B]("Mobile") != -1;
        Vc = !Rc && !Tc && $c.product == "Gecko";
    }
    var ad = Rc,
        W = Sc,
        bd = Vc,
        X = Tc,
        cd = Uc,
        dd = Yc();
    Wc = ((dd && dd.platform) || "")[B]("Mac") != -1;
    var ed = !!Yc() && (Yc().appVersion || "")[B]("X11") != -1,
        fd = "",
        gd;
    if (ad && S.opera) {
        var hd = S.opera.version;
        fd = typeof hd == "function" ? hd() : hd;
    } else {
        if (bd) gd = /rv\:([^\);]+)(\)|;)/;
        else if (W) gd = /MSIE\s+([^\);]+)(\)|;)/;
        else if (X) gd = /WebKit\/(\S+)/;
        if (gd) {
            var id = gd.exec(Xc());
            fd = id ? id[1] : "";
        }
    }
    var jd = fd,
        kd = {},
        ld = function (a) {
            return kd[a] || (kd[a] = Qc(jd, a) >= 0);
        };
    var od = function (a) {
            return a ? new md(nd(a)) : tc || (tc = new md());
        },
        Y = function (a) {
            return cc(a) ? r.getElementById(a) : a;
        },
        Z = function (a, b, c) {
            c = c || r;
            a = a && a != "*" ? a.toUpperCase() : "";
            if (c.querySelectorAll && (a || b) && (!X || pd(r) || ld("528")))
                b = c.querySelectorAll(a + (b ? "." + b : ""));
            else if (b && c.getElementsByClassName) {
                c = c.getElementsByClassName(b);
                if (a) {
                    for (var d = {}, e = 0, f = 0, h; (h = c[f]); f++) if (a == h.nodeName) d[e++] = h;
                    va(d, e);
                    b = d;
                } else b = c;
            } else {
                c = c.getElementsByTagName(a || "*");
                if (b) {
                    d = {};
                    for (f = e = 0; (h = c[f]); f++) {
                        a = h.className;
                        if (typeof a[wb] == "function" && mc(a[wb](/\s+/), b) >= 0) d[e++] = h;
                    }
                    va(d, e);
                    b = d;
                } else b = c;
            }
            return b;
        },
        rd = function (a, b) {
            Bc(b, function (c, d) {
                if (d == "style") a[M].cssText = c;
                else if (d == "class") t(a, c);
                else if (d == "for") a.htmlFor = c;
                else if (d in qd) a.setAttribute(qd[d], c);
                else a[d] = c;
            });
        },
        qd = {
            cellpadding: "cellPadding",
            cellspacing: "cellSpacing",
            colspan: "colSpan",
            rowspan: "rowSpan",
            valign: "vAlign",
            height: "height",
            width: "width",
            usemap: "useMap",
            frameborder: "frameBorder",
            type: "type",
        },
        td = function (a, b) {
            var c = b[0],
                d = b[1];
            if (W && d && (d[db] || d[F])) {
                c = ["<", c];
                d[db] && c[x](' name="', Oc(d[db]), '"');
                if (d[F]) {
                    c[x](' type="', Oc(d[F]), '"');
                    d = ic(d);
                    delete d[F];
                }
                c[x](">");
                c = c[Tb]("");
            }
            var e = a[D](c);
            if (d)
                if (cc(d)) t(e, d);
                else rd(e, d);
            if (b[K] > 2) {
                d = function (h) {
                    if (h) e[v](cc(h) ? a[Na](h) : h);
                };
                for (c = 2; c < b[K]; c++) {
                    var f = b[c];
                    bc(f) && !(ec(f) && f[Ha] > 0) ? nc(sd(f) ? qc(f) : f, d) : d(f);
                }
            }
            return e;
        },
        pd = function (a) {
            return a.compatMode == "CSS1Compat";
        },
        ud = function (a) {
            return a && a[Pb] ? a[Pb][Ab](a) : k;
        },
        vd = function (a, b) {
            for (; a && a[Ha] != 1; ) a = b ? a.nextSibling : a[lb];
            return a;
        },
        wd = function (a, b) {
            if (a.contains && b[Ha] == 1) return a == b || a.contains(b);
            if (typeof a.compareDocumentPosition != "undefined")
                return a == b || Boolean(a.compareDocumentPosition(b) & 16);
            for (; b && a != b; ) b = b[Pb];
            return b == a;
        },
        nd = function (a) {
            return a[Ha] == 9 ? a : a.ownerDocument || a[sb];
        },
        xd = function (a, b) {
            if ("textContent" in a) a.textContent = b;
            else if (a[Xa] && a[Xa][Ha] == 3) {
                for (; a.lastChild != a[Xa]; ) a[Ab](a.lastChild);
                a[Xa].data = b;
            } else {
                for (var c; (c = a[Xa]); ) a[Ab](c);
                a[v](nd(a)[Na](b));
            }
        },
        sd = function (a) {
            if (a && typeof a[K] == "number")
                if (ec(a)) return typeof a.item == "function" || typeof a.item == "string";
                else if (dc(a)) return typeof a.item == "function";
            return l;
        },
        md = function (a) {
            this.g = a || S[sb] || r;
        };
    R = md[L];
    R.Sb = od;
    R.h = function (a) {
        return cc(a) ? this.g.getElementById(a) : a;
    };
    R.df = function (a) {
        a = a || this.ed() || n;
        var b = a[sb];
        if (X && !ld("500") && !cd) {
            if (typeof a.innerHeight == "undefined") a = n;
            b = a.innerHeight;
            var c = a[sb][ib].scrollHeight;
            if (a == a.top) if (c < b) b -= 15;
            a = new Ac(a.innerWidth, b);
        } else {
            a = pd(b) && (!ad || (ad && ld("9.50"))) ? b[ib] : b[N];
            a = new Ac(a[qb], a[Gb]);
        }
        return a;
    };
    R.o = function () {
        return td(this.g, arguments);
    };
    R.createElement = function (a) {
        return this.g[D](a);
    };
    R.createTextNode = function (a) {
        return this.g[Na](a);
    };
    R.kd = function () {
        return pd(this.g);
    };
    R.ed = function () {
        return this.g.parentWindow || this.g[cb];
    };
    R.Ue = function () {
        return !X && pd(this.g) ? this.g[ib] : this.g[N];
    };
    R.Rb = function () {
        var a = !X && pd(this.g) ? this.g[ib] : this.g[N];
        return new yc(a[Hb], a[kb]);
    };
    R.appendChild = function (a, b) {
        a[v](b);
    };
    R.removeNode = ud;
    R.contains = wd;
    T("yt.config_", (n.yt && n.yt.config_) || {}, void 0);
    T("yt.globals_", (n.yt && n.yt.globals_) || {}, void 0);
    var yd = (n.yt && n.yt.msgs_) || {};
    T("yt.msgs_", yd, void 0);
    T("yt.timeouts_", (n.yt && n.yt.timeouts_) || [], void 0);
    T("yt.intervals_", (n.yt && n.yt.intervals_) || [], void 0);
    var zd = function (a, b, c) {
        b = b || {};
        if ((a = a in yd ? yd[a] : c)) for (var d in b) a = a[Ga](new RegExp("\\$" + d, "gi"), b[d]);
        return a;
    };
    eval("/*@cc_on!@*/false");
    var Ad = {},
        Bd = 0,
        Cd = function (a, b, c) {
            return Cc(Ad, function (d) {
                return d[0] == a && d[1] == b && d[2] == c;
            });
        },
        Dd = (function () {
            return n[Za]
                ? function (a, b, c) {
                      var d = ++Bd + "";
                      Ad[d] = [a, b, c];
                      a[Za](b, c, l);
                      return d;
                  }
                : n.attachEvent
                  ? function (a, b, c) {
                        var d = ++Bd + "";
                        Ad[d] = [a, b, c];
                        var e = function () {
                            return c[O](a, n[Wb]);
                        };
                        if (!a.z) a.z = {};
                        a.z[b] || (a.z[b] = {});
                        a.z[b][c] = e;
                        a.attachEvent("on" + b, e);
                        return d;
                    }
                  : function () {
                        return "";
                    };
        })(),
        Ed = (function () {
            return n[tb]
                ? function (a, b, c) {
                      a[tb](b, c, l);
                      (a = Cd(a, b, c)) && delete Ad[a];
                  }
                : n[Fb]
                  ? function (a, b, c) {
                        a.z && a.z[b] && a.z[b][c] && a[Fb]("on" + b, a.z[b][c]);
                        (a = Cd(a, b, c)) && delete Ad[a];
                    }
                  : function () {};
        })();
    (function () {
        return n[tb]
            ? function (a) {
                  if (a in Ad) {
                      var b = Ad[a];
                      b[0][tb](b[1], b[2], l);
                      delete Ad[a];
                  }
              }
            : n[Fb]
              ? function (a) {
                    if (a in Ad) {
                        var b = Ad[a],
                            c = b[0],
                            d = b[1];
                        b = b[2];
                        c.z && c.z[d] && c.z[d][b] && c[Fb]("on" + d, c.z[d][b]);
                        delete Ad[a];
                    }
                }
              : function () {};
    })();
    var Fd = function (a) {
            a = a || n[Wb];
            a = a[Cb] || a.srcElement;
            if (a[Ha] == 3) a = a[Pb];
            return a;
        },
        Gd = function (a) {
            a = a || n[Wb];
            a.returnValue = l;
            a[A] && a[A]();
            return l;
        };
    var Hd = {};
    (function () {
        function a(e) {
            var f = e.getElementsByTagName("img");
            if (f[K]) {
                f = f[0];
                if (!f.complete) {
                    za(e[M], "none");
                    Dd(f, "load", function () {
                        za(e[M], "");
                    });
                }
            }
        }
        function b(e, f, h) {
            this.v = {
                kb: this.kb[H](this),
                lb: this.lb[H](this),
                jb: this.jb[H](this),
                ib: this.ib[H](this),
                hb: this.hb[H](this),
                nb: this.nb[H](this),
                mb: this.mb[H](this),
            };
            this.Bg = e;
            this.wd = f;
            this.Y = Y("playnav-arranger-" + e);
            this.Ob = [];
            this.Wc = {};
            this.Xc = l;
            this.xg = this.vg = this.p = k;
            this.ya = { x: 0, y: 0 };
            this.pa = { x: 0, y: 0 };
            wa(this, k);
            m.selectVideo(k);
            this.Re(h);
            this.ua = Y("playnav-body");
            e = Z("div", "pinned", this.ua);
            f = 0;
            for (h = e[K]; f < h; f++) za(e[f][M], "none");
            this.Gb = b.za(this.ua);
            this.yb = Y(m.getCurrentScrollboxId());
            za(this.Y[M], "block");
            this.Ib = Z("select", "count-selector", this.Y)[0];
            ma(this.Ib, "6");
            this.m = [];
            this.Yc = Z("div", "featured", this.Y)[0];
            this.Ec(6, 1);
            this.Zb = l;
            this.Pa = new c(0, this);
            this.m[5].ha = this.Pa;
            m.setupScrollableItems(this.pd[H](this));
            Dd(r[N], "mousemove", this.v.kb);
            Dd(r[N], "mouseup", this.v.lb);
            Dd(r[N], "mousedown", this.v.jb);
            n[rb](this.gc[H](this), 0);
            this.Ag = [];
            this.Hb = l;
            n[rb](
                function () {
                    m[Ja](this.yb);
                }[H](this),
                50
            );
        }
        function c(e, f) {
            this.ud = e;
            this.parent = f;
            this.i = $ce("div", { c: "handle dropzone" });
            this.Oa = $ce("div", { c: "target" }, [$ce("div", { c: "number" }, $ctn(this.ud)), this.i]);
            this.Da = $ce("div", { c: "target-holder" }, this.Oa);
            Dd(this.i, "mouseover", this[Bb].v.ib);
            Dd(this.i, "mouseout", this[Bb].v.hb);
            this.i.T = this;
            return this;
        }
        function d(e, f) {
            e.T = this;
            this.parent = f;
            this.e = e;
            this.i = $ce("div", { c: "handle" });
            this.i.T = this;
            Dd(this.i, "mouseover", this[Bb].v.nb);
            Dd(this.i, "mouseout", this[Bb].v.mb);
            this.cc();
            this.e[v](this.i);
        }
        b[L].pe = function () {
            function e() {
                var f = b.za(this.Y);
                if (this[ob]) {
                    if (f.x != this[ob].x || f.y != this[ob].y) {
                        this.gc();
                        this.Gb = b.za(this.ua);
                        wa(this, f);
                    }
                } else wa(this, f);
                this.Hb = l;
            }
            if (!this.Hb) {
                this.Hb = j;
                n[rb](e[H](this), 100);
            }
        };
        b[L].V = function (e) {
            if (this.Y) {
                if (e && this.Zb) if (!confirm(zd("CONFIRM_UNSAVED_CHANGES_ARRANGER"))) return l;
                za(this.Y[M], "none");
                e = 0;
                for (var f = this.m[K]; e < f; e++) this.m[e].V();
                this.Pa.V();
                m.setupScrollableItems(this.og[H](this));
                m.setupScrollableItems(k);
                Ed(r[N], "mousemove", this.v.kb);
                Ed(r[N], "mouseup", this.v.lb);
                Ed(r[N], "mousedown", this.v.jb);
                var h = Z("div", "pinned", this.ua);
                e = 0;
                for (f = h[K]; e < f; e++) za(h[e][M], "");
                m[Ja](this.yb);
                this.wd && this.wd();
                return j;
            }
        };
        b.qe = $ce("div", { style: "clear:both" });
        b[L].Ec = function (e, f) {
            for (var h = k, i = 0; i < e; i++) {
                var o = new c(f + i, this);
                if (h) h.ha = o;
                h = o;
                this.Yc[v](o.Da);
                this.m[x](o);
            }
            this.Yc[v](b.qe.cloneNode(l));
        };
        b[L].fd = function () {
            if (this.m[K] == 6) {
                this.Ec(6, 7);
                this.m[5].ha = this.m[6];
                this.m[11].ha = this.Pa;
            }
            ma(this.Ib, "12");
            m[Ja](this.yb);
            this.gc();
        };
        b[L].fg = function () {
            if (this.m[K] == 12) {
                for (var e = 0; e < 6; e++) this.m.pop().V();
                this.m[5].ha = this.Pa;
            }
            ma(this.Ib, "6");
            m[Ja](this.yb);
        };
        b[L].Gd = function () {
            za(Z("div", "loading", this.Y)[0][M], "block");
        };
        b[L].Wd = function () {
            za(Z("div", "loading", this.Y)[0][M], "none");
        };
        b[L].Ve = function () {
            for (var e = 0, f = this.m[K]; e < f; e++) if (!this.m[e].ca()) return this.m[e];
        };
        c.w = k;
        c[L].V = function () {
            Ed(this.i, "mouseover", this[Bb].v.ib);
            Ed(this.i, "mouseout", this[Bb].v.hb);
            ud(this.i);
            ud(this.Da);
        };
        b[L].gc = function () {
            for (var e = this.m, f = 0, h = e[K]; f < h; f++) b.wf(e[f].i);
        };
        b.wf = function (e) {
            var f = b.za(e.T.Oa);
            ja(e[M], e[La] + "px");
            Aa(e[M], e[Sb] + "px");
            e[M].top = f.y + "px";
            oa(e[M], f.x + "px");
            r[N][v](e);
        };
        c[L].ca = function () {
            return !!this.e;
        };
        c[L].Na = function () {
            c.Sa();
            c.w = this;
            ga(this.Da, "focused");
        };
        c[L].jc = function () {
            c.pc();
            c.Ja = this;
        };
        c.Sa = function () {
            var e = c.w;
            if (e) {
                ca(e.Da, "focused");
                c.w = k;
            }
        };
        c.pc = function () {
            c.Ja = k;
        };
        c[L].fill = function (e, f) {
            this.$ = e;
            e = f || this.$.Te();
            this.$.wb();
            this.e = e;
            ga(this.Da, "draggable");
            ga(this.Oa, "target-filled");
            this.Oa[v](e);
            ga(this.i, "dropzone-filled");
            this.$.Uf(this);
        };
        c[L].empty = function () {
            var e = this.e;
            ud(this.e);
            this.e = k;
            ca(this.Da, "draggable");
            ca(this.Oa, "target-filled");
            this.$.Xd();
            this.$ = k;
            ca(this.i, "dropzone-filled");
            return e;
        };
        c[L].qd = function (e, f) {
            var h = this.$,
                i = k;
            if (h) i = this[Nb]();
            e && this[jb](e, f);
            h && this.ha && this.ha.qd(h, i);
        };
        c[L].Dc = function (e) {
            var f = this.$;
            if (e) {
                if (f) {
                    var h = this[Nb]();
                    e[jb](f, h);
                }
            } else f && this[Nb]();
            this.ha && this.ha.Dc(this);
        };
        c[L].gb = function () {
            if (this.ca()) {
                var e = m[Ba]() == "playlists" ? "encryptedPlaylistId" : "encryptedVideoId";
                if ((e = Z("div", e, this.e)) && e[K] > 0) return e[0][Ka];
                return k;
            }
        };
        d.w = k;
        d.K = k;
        d[L].V = function () {
            this.e.T = k;
            Ed(this.i, "mouseover", this[Bb].v.nb);
            Ed(this.i, "mouseout", this[Bb].v.mb);
            this.rb && this.Xd();
            ud(this.i);
            delete this.i;
            ca(this.e, "draggable");
            ca(this.e, "in-featured");
        };
        d[L].gb = function () {
            var e = m[Ba]() == "playlists" ? "encryptedPlaylistId" : "encryptedVideoId";
            if ((e = Z("div", e, this.e)) && e[K] > 0) return e[0][Ka];
            return k;
        };
        d[L].Na = function () {
            d.w = this;
            ga(this.e, "focused");
        };
        d[L].jc = function () {
            d.Ja = this;
        };
        d.Sa = function () {
            var e = d.w;
            if (e) {
                ca(e.e, "focused");
                d.w = k;
            }
        };
        d.pc = function () {
            d.Ja = k;
        };
        d[L].bd = function () {
            var e = this.e.cloneNode(j),
                f = $ce("div", { c: "dragging" }, e);
            a(f);
            ga(f, "inner-box-colors");
            f.e = e;
            return f;
        };
        d[L].Te = function () {
            var e = this.i;
            ud(this.i);
            var f = this.e.cloneNode(j);
            a(f);
            this.e[v](e);
            return f;
        };
        d[L].cc = function () {
            ga(this.e, "draggable");
            this.Rc = j;
        };
        d[L].wb = function () {
            ca(this.e, "draggable");
            this.Rc = l;
        };
        d[L].Uf = function (e) {
            this.rb = e;
            this.wb();
            ga(this.e, "in-featured");
            this.vd = $ce("div", { c: "number" }, $ctn(e.ud));
            this.ie = Z("div", "content", this.e)[0];
            this.ie[v](this.vd);
        };
        d[L].Xd = function () {
            this.rb = k;
            this.cc();
            ca(this.e, "in-featured");
            ud(this.vd);
        };
        b[L].pd = function (e) {
            e = Z("div", "playnav-item", e);
            for (var f = [], h = 0, i = e[K]; h < i; h++)
                if (!_hasclass(e[h], "pinned-item")) {
                    var o = new d(e[h], this);
                    f[x](o);
                    this.Ob[x](o);
                }
            this.Cc();
            return f;
        };
        b[L].og = function (e) {
            e = Z("div", "playnav-item", e);
            for (var f = 0, h = e[K]; f < h; f++) {
                var i = e[f];
                i && i.T && i.T.V();
            }
        };
        b.za = function (e) {
            for (var f = { x: 0, y: 0 }; e; e = e[xb]) {
                f.x += e[zb] - (e == r[N] ? 0 : e[Hb]);
                f.y += e[Rb] - (e == r[N] ? 0 : e[kb]);
            }
            return f;
        };
        b[L].kb = function (e) {
            var f = e,
                h = { x: 0, y: 0 };
            f = f || n[Wb];
            if (f.pageX || f.pageY) h = { x: f.pageX, y: f.pageY };
            else if (f[gb] || f[hb]) h = { x: f[gb] + r[N][Hb] + r[ib][Hb], y: f[hb] + r[N][kb] + r[ib][kb] };
            this.pa = h;
            if (this.p) {
                this.qc();
                return Gd(e);
            } else this.pe();
        };
        b[L].lb = function () {
            if (d.K && c.w) {
                var e = this.Ve();
                e[jb](d.K);
                this.Zb = j;
                e != c.w && !c.w.ca() && c.Sa();
                this.Pa.ca() && this.Pa[Nb]();
            }
            if (this.p) {
                ud(this.p);
                this.p = k;
                d.Sa();
                if (d.K) {
                    d.K.rb || d.K.cc();
                    d.K = k;
                }
                d.Ja && d.Ja.Na();
            }
        };
        b[L].ib = function (e) {
            e = Fd(e).T;
            if (this.p) {
                e.ca() && e.qd();
                e.Na();
                ga(this.p, "generictheme");
            } else e.ca() && e.Na();
            e.jc();
        };
        b[L].hb = function (e) {
            e = Fd(e).T;
            if (this.p) {
                ca(this.p, "generictheme");
                e.ca() || e.Dc();
            }
            c.Sa();
        };
        b[L].nb = function (e) {
            e = Fd(e).T;
            e.jc();
            this.p || e.Na();
        };
        b[L].mb = function () {
            d.pc();
            this.p || d.Sa();
        };
        b[L].qc = function () {
            oa(this.p[M], this.pa.x - this.Gb.x - this.ya.x + "px");
            this.p[M].top = this.pa.y - this.Gb.y - this.ya.y + "px";
        };
        b[L].jb = function (e) {
            switch (Fd(e)[Lb][Ub]()) {
                case "select":
                case "option":
                case "input":
                    return j;
            }
            if (d.w) {
                var f = d.w;
                if (!f.Rc) return Gd(e);
                var h = b.za(f.e);
                d.K = f;
                f.wb();
                this.p = f.bd();
                this.ua[v](this.p);
                this.ya.x = this.pa.x - h.x;
                this.ya.y = this.pa.y - h.y;
                this.qc();
                return Gd(e);
            } else if (c.w) {
                f = c.w;
                h = b.za(f.Oa);
                d.K = f.$;
                d.K.Na();
                f[Nb]();
                this.p = d.K.bd();
                ga(this.p, "generictheme");
                d.K.wb();
                this.ua[v](this.p);
                this.ya.x = this.pa.x - h.x;
                this.ya.y = this.pa.y - h.y;
                this.qc();
                return Gd(e);
            } else if (c.Ja) return Gd(e);
        };
        b[L].save = function (e) {
            e || this.Gd();
            var f = [],
                h = [],
                i = Iter(this.m).collect(
                    function (o) {
                        if (o.ca()) return o.gb();
                    }[H](this)
                );
            if (m[Ba]() == "playlists") h = i;
            else f = i;
            f = { playlist_name: m[Ba](), video_id_list: f, playlist_id_list: h };
            if (!e) {
                ha().call_box_method(m.getBoxInfo(), f, "save_arranged_items", this.Pf[H](this));
                m[Ba]() == "uploads" && m.sort("default");
            }
            return f;
        };
        b[L].Pf = function () {
            this.Wd();
            this.V();
            m.invalidateTab("all");
            m.invalidateTab(m[Ba]());
            m.selectTab(m[Ba]());
        };
        b[L].cancel = function () {
            this.V(l);
        };
        b[L].Re = function (e) {
            this.Gd();
            var f = e || { playlist_name: m[Ba]() };
            ha().call_box_method(m.getBoxInfo(), f, "get_arranged_items", this.gf[H](this));
            if (e) this.Zb = j;
        };
        b[L].gf = function (e) {
            var f = $ce("div");
            ka(f, e);
            e = this.pd(f);
            e[K] > this.m[K] && this.fd();
            for (f = 0; f < e[K]; f++) {
                var h = e[f];
                this.Wc[h.gb()] = h;
                this.m[f][jb](e[f]);
            }
            this.Xc = j;
            this.Wd();
            this.Cc();
        };
        b[L].Cc = function () {
            if (this.Xc)
                for (; this.Ob[K]; ) {
                    var e = this.Ob.pop(),
                        f = this.Wc[e.gb()];
                    if (f)
                        if ((f = f.rb)) {
                            f[Nb]();
                            f[jb](e);
                        }
                }
        };
        b[L].pg = function (e) {
            var f = this.m[K];
            e = da(e, 10);
            if (e < f) this.fg();
            else e > f && this.fd();
        };
        b[L].destruct = b[L].V;
        b[L].save = b[L].save;
        b[L].cancel = b[L].cancel;
        b[L].updateItemCount = b[L].pg;
        Hd = b;
    })();
    var Id = function (a) {
        this.stack = new Error().stack || "";
        if (a) this.message = fa(a);
    };
    U(Id, Error);
    Id[L].name = "CustomError";
    var Jd = function (a, b) {
        b.unshift(a);
        Id[O](this, Hc[Kb](k, b));
        b.shift();
        this.zg = a;
    };
    U(Jd, Id);
    Jd[L].name = "AssertionError";
    var Kd = function (a, b, c, d) {
            var e = "Assertion failed";
            if (c) {
                e += ": " + c;
                var f = d;
            } else if (a) {
                e += ": " + a;
                f = b;
            }
            g(new Jd("" + e, f || []));
        },
        Ld = function (a, b) {
            !a && Kd("", k, b, Array[L][Fa][O](arguments, 2));
        };
    var $ = {
        sc: ["BC", "AD"],
        Zd: ["Before Christ", "Anno Domini"],
        be: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
        de: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
        sa: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ],
        ce: [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December",
        ],
        uc: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        fe: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        wc: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        he: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        Bb: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        ge: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        ug: ["S", "M", "T", "W", "T", "F", "S"],
        ee: ["S", "M", "T", "W", "T", "F", "S"],
        vc: ["Q1", "Q2", "Q3", "Q4"],
        tc: ["1st quarter", "2nd quarter", "3rd quarter", "4th quarter"],
        rc: ["AM", "PM"],
        Ta: ["EEEE, MMMM d, y", "MMMM d, y", "MMM d, y", "M/d/yy"],
        db: ["h:mm:ss a zzzz", "h:mm:ss a z", "h:mm:ss a", "h:mm a"],
        tg: { Md: "M/d", MMMMd: "MMMM d", MMMd: "MMM d" },
        $d: 6,
        xc: [5, 6],
        ae: 2,
    };
    var Md = function () {},
        Od = function (a) {
            if (typeof a == "number") {
                var b = new Md();
                b.Sd = a;
                b.Vd = Nd(a);
                a = a;
                if (a == 0) a = "UTC";
                else {
                    var c = ["UTC", a < 0 ? "+" : "-"];
                    a = s.abs(a);
                    c[x](s[Ia](a / 60) % 100);
                    a %= 60;
                    a != 0 && c[x](":", a);
                    a = c[Tb]("");
                }
                b.nc = [a, a];
                b.zb = [];
                return b;
            }
            b = new Md();
            b.Vd = a.id;
            b.Sd = -a.std_offset;
            b.nc = a.names;
            b.zb = a.transitions;
            return b;
        },
        Nd = function (a) {
            if (a == 0) return "Etc/GMT";
            var b = ["Etc/GMT", a < 0 ? "-" : "+"];
            a = s.abs(a);
            b[x](s[Ia](a / 60) % 100);
            a %= 60;
            a != 0 && b[x](":", V(a, 2));
            return b[Tb]("");
        };
    R = Md[L];
    R.ad = function (a) {
        a = Date.UTC(a.getUTCFullYear(), a.getUTCMonth(), a.getUTCDate(), a[Ob](), a.getUTCMinutes()) / 36e5;
        for (var b = 0; b < this.zb[K] && a >= this.zb[b]; ) b += 2;
        return b == 0 ? 0 : this.zb[b - 1];
    };
    R.We = function (a) {
        a = this.Wb(a);
        var b = ["GMT"];
        b[x](a <= 0 ? "+" : "-");
        a = s.abs(a);
        b[x](V(s[Ia](a / 60) % 100, 2), ":", V(a % 60, 2));
        return b[Tb]("");
    };
    R.Ze = function (a) {
        return this.nc[this.ld(a) ? 3 : 1];
    };
    R.Wb = function (a) {
        return this.Sd - this.ad(a);
    };
    R.bf = function (a) {
        a = -this.Wb(a);
        var b = [a < 0 ? "-" : "+"];
        a = s.abs(a);
        b[x](V(s[Ia](a / 60) % 100, 2), V(a % 60, 2));
        return b[Tb]("");
    };
    R.cf = function (a) {
        return this.nc[this.ld(a) ? 2 : 0];
    };
    R.ld = function (a) {
        return this.ad(a) > 0;
    };
    var Pd = function (a) {
            Ld(a !== q, "Pattern must be defined");
            this.d = [];
            typeof a == "number" ? this.eb(a) : this.Ua(a);
        },
        Qd = [
            /^\'(?:[^\']|\'\')*\'/,
            /^(?:G+|y+|M+|k+|S+|E+|a+|h+|K+|H+|c+|L+|Q+|d+|m+|s+|v+|z+|Z+)/,
            /^[^\'GyMkSEahKHcLQdmsvzZ]+/,
        ];
    R = Pd[L];
    R.Ua = function (a) {
        for (; a; )
            for (var b = 0; b < Qd[K]; ++b) {
                var c = a.match(Qd[b]);
                if (c) {
                    c = c[0];
                    a = a[Jb](c[K]);
                    if (b == 0)
                        if (c == "''") c = "'";
                        else {
                            c = c[Jb](1, c[K] - 1);
                            c = c[Ga](/\'\'/, "'");
                        }
                    this.d[x]({ text: c, type: b });
                    break;
                }
            }
    };
    R.we = function (a, b) {
        var c = b ? (a[Xb]() - b.Wb(a)) * 6e4 : 0,
            d = c ? new Date(a[fb]() + c) : a,
            e = d;
        if (b && d[Xb]() != a[Xb]()) {
            c += c > 0 ? -86400000 : 864e5;
            e = new Date(a[fb]() + c);
        }
        c = [];
        for (var f = 0; f < this.d[K]; ++f) {
            var h = this.d[f][Da];
            1 == this.d[f][F] ? c[x](this.Fe(h, a, d, e, b)) : c[x](h);
        }
        return c[Tb]("");
    };
    R.eb = function (a) {
        if (a < 4) a = $.Ta[a];
        else if (a < 8) a = $.db[a - 4];
        else if (a < 12) a = $.Ta[a - 8] + " " + $.db[a - 8];
        else {
            this.eb(10);
            return;
        }
        this.Ua(a);
    };
    R.Ee = function (a, b) {
        b = b[P]() > 0 ? 1 : 0;
        return a >= 4 ? $.Zd[b] : $.sc[b];
    };
    R.Qe = function (a, b) {
        b = b[P]();
        if (b < 0) b = -b;
        return a == 2 ? V(b % 100, 2) : fa(b);
    };
    R.Ie = function (a, b) {
        b = b[J]();
        switch (a) {
            case 5:
                return $.be[b];
            case 4:
                return $.sa[b];
            case 3:
                return $.uc[b];
            default:
                return V(b + 1, a);
        }
    };
    R.Ae = function (a, b) {
        return V(b[eb]() || 24, a);
    };
    R.Ge = function (a, b) {
        return ((b[fb]() % 1e3) / 1e3).toFixed(s.min(3, a)).substr(2) + (a > 3 ? V(0, a - 3) : "");
    };
    R.De = function (a, b) {
        b = b[pb]();
        return a >= 4 ? $.wc[b] : $.Bb[b];
    };
    R.Be = function (a, b) {
        a = b[eb]();
        return $.rc[a >= 12 && a < 24 ? 1 : 0];
    };
    R.ze = function (a, b) {
        return V(b[eb]() % 12 || 12, a);
    };
    R.xe = function (a, b) {
        return V(b[eb]() % 12, a);
    };
    R.ye = function (a, b) {
        return V(b[eb](), a);
    };
    R.Le = function (a, b) {
        b = b[pb]();
        switch (a) {
            case 5:
                return $.ee[b];
            case 4:
                return $.he[b];
            case 3:
                return $.ge[b];
            default:
                return V(b, 1);
        }
    };
    R.Me = function (a, b) {
        b = b[J]();
        switch (a) {
            case 5:
                return $.de[b];
            case 4:
                return $.ce[b];
            case 3:
                return $.fe[b];
            default:
                return V(b + 1, a);
        }
    };
    R.Je = function (a, b) {
        b = s[Ia](b[J]() / 3);
        return a < 4 ? $.vc[b] : $.tc[b];
    };
    R.Ce = function (a, b) {
        return V(b[z](), a);
    };
    R.He = function (a, b) {
        return V(b[Qb](), a);
    };
    R.Ke = function (a, b) {
        return V(b[ub](), a);
    };
    R.Oe = function (a, b, c) {
        c = c || Od(b[Xb]());
        return a < 4 ? c.bf(b) : c.We(b);
    };
    R.Pe = function (a, b, c) {
        c = c || Od(b[Xb]());
        return a < 4 ? c.cf(b) : c.Ze(b);
    };
    R.Ne = function (a, b) {
        b = b || Od(a[Xb]());
        return b.Vd;
    };
    R.Fe = function (a, b, c, d, e) {
        var f = a[K];
        switch (a[Ma](0)) {
            case "G":
                return this.Ee(f, c);
            case "y":
                return this.Qe(f, c);
            case "M":
                return this.Ie(f, c);
            case "k":
                return this.Ae(f, d);
            case "S":
                return this.Ge(f, d);
            case "E":
                return this.De(f, c);
            case "a":
                return this.Be(f, d);
            case "h":
                return this.ze(f, d);
            case "K":
                return this.xe(f, d);
            case "H":
                return this.ye(f, d);
            case "c":
                return this.Le(f, c);
            case "L":
                return this.Me(f, c);
            case "Q":
                return this.Je(f, c);
            case "d":
                return this.Ce(f, c);
            case "m":
                return this.He(f, d);
            case "s":
                return this.Ke(f, d);
            case "v":
                return this.Ne(b, e);
            case "z":
                return this.Pe(f, b, e);
            case "Z":
                return this.Oe(f, b, e);
            default:
                return "";
        }
    };
    var Sd = function (a) {
        this.d = [];
        typeof a == "number" ? this.eb(a) : this.Ua(a);
    };
    R = Sd[L];
    R.Ua = function (a) {
        for (var b = l, c = "", d = 0; d < a[K]; d++) {
            var e = a[Ma](d);
            if (e == " ") {
                if (c[K] > 0) {
                    this.d[x]({ text: c, U: 0, ta: l });
                    c = "";
                }
                for (this.d[x]({ text: " ", U: 0, ta: l }); d < a[K] - 1 && a[Ma](d + 1) == " "; ) d++;
            } else if (b)
                if (e == "'")
                    if (d + 1 < a[K] && a[Ma](d + 1) == "'") {
                        c += "'";
                        d++;
                    } else b = l;
                else c += e;
            else if ("GyMdkHmsSEDahKzZvQ"[B](e) >= 0) {
                if (c[K] > 0) {
                    this.d[x]({ text: c, U: 0, ta: l });
                    c = "";
                }
                var f = this.$e(a, d);
                this.d[x]({ text: e, U: f, ta: l });
                d += f - 1;
            } else if (e == "'")
                if (d + 1 < a[K] && a[Ma](d + 1) == "'") {
                    c += "'";
                    d++;
                } else b = j;
            else c += e;
        }
        c[K] > 0 && this.d[x]({ text: c, U: 0, ta: l });
        this.uf();
    };
    R.eb = function (a) {
        if (a > 11) a = 10;
        this.Ua(a < 4 ? $.Ta[a] : a < 8 ? $.db[a - 4] : $.Ta[a - 8] + " " + $.db[a - 8]);
    };
    R.hg = function (a, b, c) {
        return this.sf(a, b, c || 0, j);
    };
    R.sf = function (a, b, c, d) {
        for (var e = new Td(), f = [c], h = -1, i = 0, o = 0, p = 0; p < this.d[K]; p++)
            if (this.d[p].U > 0) {
                if (h < 0 && this.d[p].ta) {
                    h = p;
                    i = c;
                    o = 0;
                }
                if (h >= 0) {
                    var u = this.d[p].U;
                    if (p == h) {
                        u -= o;
                        o++;
                        if (u == 0) return 0;
                    }
                    if (!this.Td(a, f, this.d[p], u, e)) {
                        p = h - 1;
                        f[0] = i;
                    }
                } else {
                    h = -1;
                    if (!this.Td(a, f, this.d[p], 0, e)) return 0;
                }
            } else {
                h = -1;
                if (this.d[p][Da][Ma](0) == " ") {
                    u = f[0];
                    this.Rd(a, f);
                    if (f[0] > u) continue;
                } else if (a[B](this.d[p][Da], f[0]) == f[0]) {
                    f[0] += this.d[p][Da][K];
                    continue;
                }
                return 0;
            }
        return e.oe(b, d) ? f[0] - c : 0;
    };
    R.$e = function (a, b) {
        for (var c = a[Ma](b), d = b + 1; d < a[K] && a[Ma](d) == c; ) d++;
        return d - b;
    };
    R.$b = function (a) {
        if (a.U <= 0) return l;
        var b = "MydhHmsSDkK"[B](a[Da][Ma](0));
        return b > 0 || (b == 0 && a.U < 3);
    };
    R.uf = function () {
        for (var a = l, b = 0; b < this.d[K]; b++)
            if (this.$b(this.d[b])) {
                if (!a && b + 1 < this.d[K] && this.$b(this.d[b + 1])) {
                    a = j;
                    this.d[b].ta = j;
                }
            } else a = l;
    };
    R.Rd = function (a, b) {
        if ((a = a[Jb](b[0]).match(/^\s+/))) b[0] += a[0][K];
    };
    R.Td = function (a, b, c, d, e) {
        this.Rd(a, b);
        var f = b[0],
            h = c[Da][Ma](0),
            i = -1;
        if (this.$b(c))
            if (d > 0) {
                if (f + d > a[K]) return l;
                i = this.ab(a[Jb](0, f + d), b);
            } else i = this.ab(a, b);
        switch (h) {
            case "G":
                e.Uc = this.fa(a, b, $.sc);
                return j;
            case "M":
                return this.kg(a, b, e, i);
            case "E":
                return this.ig(a, b, e);
            case "a":
                e.yc = this.fa(a, b, $.rc);
                return j;
            case "y":
                return this.mg(a, b, f, i, c, e);
            case "Q":
                return this.lg(a, b, e, i);
            case "d":
                e.va = i;
                return j;
            case "S":
                return this.jg(i, b, f, e);
            case "h":
                if (i == 12) i = 0;
            case "K":
            case "H":
            case "k":
                e.l = i;
                return j;
            case "m":
                e.r = i;
                return j;
            case "s":
                e.s = i;
                return j;
            case "z":
            case "Z":
            case "v":
                return this.ng(a, b, e);
            default:
                return l;
        }
    };
    R.mg = function (a, b, c, d, e, f) {
        var h;
        if (d < 0) {
            h = a[Ma](b[0]);
            if (h != "+" && h != "-") return l;
            b[0]++;
            d = this.ab(a, b);
            if (d < 0) return l;
            if (h == "-") d = -d;
        }
        if (!h && b[0] - c == 2 && e.U == 2) f.Xf(d);
        else f.W = d;
        return j;
    };
    R.kg = function (a, b, c, d) {
        if (d < 0) {
            d = this.fa(a, b, $.sa);
            if (d < 0) d = this.fa(a, b, $.uc);
            if (d < 0) return l;
            c.Ia = d;
        } else c.Ia = d - 1;
        return j;
    };
    R.lg = function (a, b, c, d) {
        if (d < 0) {
            d = this.fa(a, b, $.tc);
            if (d < 0) d = this.fa(a, b, $.vc);
            if (d < 0) return l;
            c.Ia = d * 3;
            c.va = 1;
            return j;
        }
        return l;
    };
    R.ig = function (a, b, c) {
        var d = this.fa(a, b, $.wc);
        if (d < 0) d = this.fa(a, b, $.Bb);
        if (d < 0) return l;
        c.Kb = d;
        return j;
    };
    R.jg = function (a, b, c, d) {
        b = b[0] - c;
        d.fc = b < 3 ? a * s.pow(10, 3 - b) : s[Ea](a / s.pow(10, b - 3));
        return j;
    };
    R.ng = function (a, b, c) {
        if (a[B]("GMT", b[0]) == b[0]) {
            b[0] += 3;
            return this.zd(a, b, c);
        }
        return this.zd(a, b, c);
    };
    R.zd = function (a, b, c) {
        if (b[0] >= a[K]) {
            c.oc = 0;
            return j;
        }
        var d = 1;
        switch (a[Ma](b[0])) {
            case "-":
                d = -1;
            case "+":
                b[0]++;
        }
        var e = b[0],
            f = this.ab(a, b);
        if (f == 0 && b[0] == e) return l;
        var h;
        if (b[0] < a[K] && a[Ma](b[0]) == ":") {
            h = f * 60;
            b[0]++;
            e = b[0];
            f = this.ab(a, b);
            if (f == 0 && b[0] == e) return l;
            h += f;
        } else {
            h = f;
            if (h < 24 && b[0] - e <= 2) h *= 60;
            else h = (h % 100) + (h / 100) * 60;
        }
        h *= d;
        c.oc = -h;
        return j;
    };
    R.ab = function (a, b) {
        a = a[Jb](b[0]).match(/^\d+/);
        if (!a) return -1;
        b[0] += a[0][K];
        return da(a[0], 10);
    };
    R.fa = function (a, b, c) {
        var d = 0,
            e = -1;
        a = a[Jb](b[0])[Ub]();
        for (var f = 0; f < c[K]; f++) {
            var h = c[f][K];
            if (h > d && a[B](c[f][Ub]()) == 0) {
                e = f;
                d = h;
            }
        }
        if (e >= 0) b[0] += d;
        return e;
    };
    var Td = function () {};
    Td[L].Xf = function (a) {
        var b = new Date()[P]() - 80,
            c = b % 100;
        this.me = a == c;
        a += s[Ia](b / 100) * 100 + (a < c ? 100 : 0);
        return (this.W = a);
    };
    Td[L].oe = function (a, b) {
        if (this.Uc != q && this.W != q && this.Uc == 0 && this.W > 0) this.W = -(this.W - 1);
        this.W != q && a[Wa](this.W);
        var c = a[z]();
        a[I](1);
        this.Ia != q && a[Ta](this.Ia);
        this.va != q ? a[I](this.va) : a[I](c);
        if (this.l == q) this.l = a[eb]();
        if (this.yc != q && this.yc > 0) if (this.l < 12) this.l += 12;
        a[Mb](this.l);
        this.r != q && a[bb](this.r);
        this.s != q && a[Db](this.s);
        this.fc != q && a.setMilliseconds(this.fc);
        if (
            b &&
            ((this.W != q && this.W != a[P]()) ||
                (this.Ia != q && this.Ia != a[J]()) ||
                (this.va != q && this.va != a[z]()) ||
                this.l >= 24 ||
                this.r >= 60 ||
                this.s >= 60 ||
                this.fc >= 1e3)
        )
            return l;
        if (this.oc != q) {
            b = a[Xb]();
            a.setTime(a[fb]() + (this.oc - b) * 60 * 1e3);
        }
        if (this.me) {
            b = new Date();
            b[Wa](b[P]() - 80);
            a[fb]() < b[fb]() && a[Wa](b[P]() + 100);
        }
        if (this.Kb != q)
            if (this.va == q) {
                b = (7 + this.Kb - a[pb]()) % 7;
                if (b > 3) b -= 7;
                c = a[J]();
                a[I](a[z]() + b);
                if (a[J]() != c) a[I](a[z]() + (b > 0 ? -7 : 7));
            } else if (this.Kb != a[pb]()) return l;
        return j;
    };
    var Ud = function (a, b) {
            switch (b) {
                case 1:
                    return a % 4 == 0 && (a % 100 != 0 || a % 400 == 0) ? 29 : 28;
                case 5:
                case 8:
                case 10:
                case 3:
                    return 30;
            }
            return 31;
        },
        Vd = function (a, b, c, d, e, f) {
            if (cc(a)) {
                this.I = a == "y" ? b : 0;
                this.H = a == "m" ? b : 0;
                this.B = a == "d" ? b : 0;
                this.l = a == "h" ? b : 0;
                this.r = a == "n" ? b : 0;
                this.s = a == "s" ? b : 0;
            } else {
                this.I = a || 0;
                this.H = b || 0;
                this.B = c || 0;
                this.l = d || 0;
                this.r = e || 0;
                this.s = f || 0;
            }
        };
    Vd[L].cb = function (a) {
        var b = s.min(this.I, this.H, this.B, this.l, this.r, this.s),
            c = s.max(this.I, this.H, this.B, this.l, this.r, this.s);
        if (b < 0 && c > 0) return k;
        if (!a && b == 0 && c == 0) return "PT0S";
        c = [];
        b < 0 && c[x]("-");
        c[x]("P");
        if (this.I || a) c[x](s.abs(this.I) + "Y");
        if (this.H || a) c[x](s.abs(this.H) + "M");
        if (this.B || a) c[x](s.abs(this.B) + "D");
        if (this.l || this.r || this.s || a) {
            c[x]("T");
            if (this.l || a) c[x](s.abs(this.l) + "H");
            if (this.r || a) c[x](s.abs(this.r) + "M");
            if (this.s || a) c[x](s.abs(this.s) + "S");
        }
        return c[Tb]("");
    };
    Vd[L].Nb = function (a) {
        return a.I == this.I && a.H == this.H && a.B == this.B && a.l == this.l && a.r == this.r && a.s == this.s;
    };
    Vd[L].t = function () {
        return new Vd(this.I, this.H, this.B, this.l, this.r, this.s);
    };
    Vd[L].add = function (a) {
        this.I += a.I;
        this.H += a.H;
        this.B += a.B;
        this.l += a.l;
        this.r += a.r;
        this.s += a.s;
    };
    var Wd = function (a, b, c) {
        if (typeof a == "number") {
            this.a = new Date(a, b || 0, c || 1);
            this.dc(c || 1);
        } else if (ec(a)) {
            this.a = new Date(a[P](), a[J](), a[z]());
            this.dc(a[z]());
        } else {
            this.a = new Date();
            this.a[Mb](0);
            this.a[bb](0);
            this.a[Db](0);
            this.a.setMilliseconds(0);
        }
    };
    R = Wd[L];
    R.ba = 0;
    R.Xa = 3;
    R.t = function () {
        var a = new Wd(this.a);
        a.ba = this.ba;
        a.Xa = this.Xa;
        return a;
    };
    R.getFullYear = function () {
        return this.a[P]();
    };
    R.getYear = function () {
        return this[P]();
    };
    R.getMonth = function () {
        return this.a[J]();
    };
    la(R, function () {
        return this.a[z]();
    });
    R.getTime = function () {
        return this.a[fb]();
    };
    R.getDay = function () {
        return this.a[pb]();
    };
    R.Ye = function () {
        return (this[pb]() + 6) % 7;
    };
    R.ff = function () {
        return (this.Ye() - this.ba + 7) % 7;
    };
    R.getUTCFullYear = function () {
        return this.a.getUTCFullYear();
    };
    R.getUTCMonth = function () {
        return this.a.getUTCMonth();
    };
    R.getUTCDate = function () {
        return this.a.getUTCDate();
    };
    R.getUTCHours = function () {
        return this.a[Ob]();
    };
    R.getUTCMinutes = function () {
        return this.a.getUTCMinutes();
    };
    R.dd = function () {
        return Ud(this[P](), this[J]());
    };
    R.ef = function () {
        var a = this[z]();
        a = new Date(this[P](), this[J](), a);
        var b = this.Xa || 3,
            c = this.ba || 0,
            d = (((a[pb]() + 6) % 7) - c + 7) % 7;
        b = (b - c + 7) % 7;
        a = a.valueOf() + (b - d) * 864e5;
        d = new Date(new Date(a)[P](), 0, 1).valueOf();
        return s[Ia](s[Ea]((a - d) / 864e5) / 7) + 1;
    };
    R.getTimezoneOffset = function () {
        return this.a[Xb]();
    };
    R.Xb = function () {
        var a;
        a = this[Xb]();
        if (a == 0) a = "Z";
        else {
            var b = s.abs(a) / 60,
                c = s[Ia](b);
            b = (b - c) * 60;
            a = (a > 0 ? "-" : "+") + V(c, 2) + ":" + V(b, 2);
        }
        return a;
    };
    R.Qf = function (a) {
        this.a = new Date(a[P](), a[J](), a[z]());
    };
    R.setFullYear = function (a) {
        this.a[Wa](a);
    };
    R.setMonth = function (a) {
        this.a[Ta](a);
    };
    sa(R, function (a) {
        this.a[I](a);
    });
    R.setTime = function (a) {
        this.a.setTime(a);
    };
    R.Hd = function (a) {
        this.ba = a;
    };
    R.Id = function (a) {
        this.Xa = a;
    };
    R.add = function (a) {
        if (a.I || a.H) {
            var b = this[J]() + a.H + a.I * 12,
                c = this[Eb]() + s[Ia](b / 12);
            b %= 12;
            if (b < 0) b += 12;
            var d = s.min(Ud(c, b), this[z]());
            this[I](1);
            this[Wa](c);
            this[Ta](b);
            this[I](d);
        }
        if (a.B) {
            b = new Date(this[Eb](), this[J](), this[z](), 12);
            a = new Date(b[fb]() + a.B * 864e5);
            this[I](1);
            this[Wa](a[P]());
            this[Ta](a[J]());
            this[I](a[z]());
            this.dc(a[z]());
        }
    };
    R.cb = function (a, b) {
        return [this[P](), V(this[J]() + 1, 2), V(this[z](), 2)][Tb](a ? "-" : "") + (b ? this.Xb() : "");
    };
    R.Nb = function (a) {
        return this[Eb]() == a[Eb]() && this[J]() == a[J]() && this[z]() == a[z]();
    };
    ua(R, function () {
        return this.cb();
    });
    R.dc = function (a) {
        if (this[z]() != a) {
            a = this[z]() < a ? 1 : -1;
            this.a.setUTCHours(this.a[Ob]() + a);
        }
    };
    R.valueOf = function () {
        return this.a.valueOf();
    };
    var Xd = function (a, b, c, d, e, f, h) {
        this.a =
            typeof a == "number"
                ? new Date(a, b || 0, c || 1, d || 0, e || 0, f || 0, h || 0)
                : new Date(a ? a[fb]() : kc());
    };
    U(Xd, Wd);
    R = Xd[L];
    R.getHours = function () {
        return this.a[eb]();
    };
    R.getMinutes = function () {
        return this.a[Qb]();
    };
    R.getSeconds = function () {
        return this.a[ub]();
    };
    R.getUTCHours = function () {
        return this.a[Ob]();
    };
    R.getUTCMinutes = function () {
        return this.a.getUTCMinutes();
    };
    R.setHours = function (a) {
        this.a[Mb](a);
    };
    R.setMinutes = function (a) {
        this.a[bb](a);
    };
    R.setSeconds = function (a) {
        this.a[Db](a);
    };
    R.setMilliseconds = function (a) {
        this.a.setMilliseconds(a);
    };
    R.setUTCHours = function (a) {
        this.a.setUTCHours(a);
    };
    R.add = function (a) {
        Wd[L].add[O](this, a);
        a.l && this[Mb](this.a[eb]() + a.l);
        a.r && this[bb](this.a[Qb]() + a.r);
        a.s && this[Db](this.a[ub]() + a.s);
    };
    R.cb = function (a, b) {
        var c = Wd[L].cb[O](this, a);
        if (a)
            return c + " " + V(this[eb](), 2) + ":" + V(this[Qb](), 2) + ":" + V(this[ub](), 2) + (b ? this.Xb() : "");
        return c + "T" + V(this[eb](), 2) + V(this[Qb](), 2) + V(this[ub](), 2) + (b ? this.Xb() : "");
    };
    R.Nb = function (a) {
        return this[fb]() == a[fb]();
    };
    ua(R, function () {
        return this.cb();
    });
    R.t = function () {
        var a = new Xd(this.a);
        a.Hd(this.ba);
        a.Id(this.Xa);
        return a;
    };
    var Yd = function () {};
    Yd[L].Qc = l;
    Yd[L].C = function () {
        if (!this.Qc) {
            this.Qc = j;
            this.f();
        }
    };
    Yd[L].f = function () {};
    var Zd = function (a, b) {
        qa(this, a);
        xa(this, b);
        na(this, this[Cb]);
    };
    U(Zd, Yd);
    R = Zd[L];
    R.f = function () {
        delete this[F];
        delete this[Cb];
        delete this.currentTarget;
    };
    R.ia = l;
    R.Ma = j;
    R.stopPropagation = function () {
        this.ia = j;
    };
    R.preventDefault = function () {
        this.Ma = l;
    };
    var $d = function (a, b) {
        a && this.Ya(a, b);
    };
    U($d, Zd);
    R = $d[L];
    xa(R, k);
    R.relatedTarget = k;
    R.offsetX = 0;
    R.offsetY = 0;
    R.clientX = 0;
    R.clientY = 0;
    R.screenX = 0;
    R.screenY = 0;
    R.button = 0;
    pa(R, 0);
    R.charCode = 0;
    R.ctrlKey = l;
    R.altKey = l;
    R.shiftKey = l;
    R.metaKey = l;
    R.Gf = l;
    R.ma = k;
    R.Ya = function (a, b) {
        var c = qa(this, a[F]);
        xa(this, a[Cb] || a.srcElement);
        na(this, b);
        if ((b = a.relatedTarget)) {
            if (bd)
                try {
                    b = b.nodeName && b;
                } catch (d) {
                    b = k;
                }
        } else if (c == "mouseover") b = a.fromElement;
        else if (c == "mouseout") b = a.toElement;
        this.relatedTarget = b;
        this.offsetX = a.offsetX !== q ? a.offsetX : a.layerX;
        this.offsetY = a.offsetY !== q ? a.offsetY : a.layerY;
        this.clientX = a[gb] !== q ? a[gb] : a.pageX;
        this.clientY = a[hb] !== q ? a[hb] : a.pageY;
        this.screenX = a[Qa] || 0;
        this.screenY = a[Ra] || 0;
        this.button = a.button;
        pa(this, a[E] || 0);
        this.charCode = a[Ua] || (c == "keypress" ? a[E] : 0);
        this.ctrlKey = a[vb];
        this.altKey = a.altKey;
        this.shiftKey = a.shiftKey;
        this.metaKey = a.metaKey;
        this.Gf = Wc ? a.metaKey : a[vb];
        this.ma = a;
        delete this.Ma;
        delete this.ia;
    };
    R.stopPropagation = function () {
        this.ia = j;
        if (this.ma[yb]) this.ma[yb]();
        else this.ma.cancelBubble = j;
    };
    var ae = W && !ld("8");
    $d[L].preventDefault = function () {
        this.Ma = l;
        var a = this.ma;
        if (a[A]) a[A]();
        else {
            a.returnValue = l;
            if (ae)
                try {
                    if (a[vb] || (a[E] >= 112 && a[E] <= 123)) pa(a, -1);
                } catch (b) {}
        }
    };
    $d[L].f = function () {
        $d.k.f[O](this);
        this.ma = k;
        xa(this, k);
        na(this, k);
        this.relatedTarget = k;
    };
    var be = function () {},
        ce = 0;
    R = be[L];
    R.key = 0;
    R.La = l;
    R.Ac = l;
    R.Ya = function (a, b, c, d, e, f) {
        if (dc(a)) this.md = j;
        else if (a && a[ab] && dc(a[ab])) this.md = l;
        else g(Error("Invalid listener argument"));
        this.Ga = a;
        this.Cd = b;
        this.src = c;
        qa(this, d);
        this.capture = !!e;
        this.ob = f;
        this.Ac = l;
        this.key = ++ce;
        this.La = l;
    };
    R.handleEvent = function (a) {
        if (this.md) return this.Ga[O](this.ob || this.src, a);
        return this.Ga[ab][O](this.Ga, a);
    };
    var de = function (a, b) {
        this.rd = b;
        this.na = [];
        this.se(a);
    };
    U(de, Yd);
    R = de[L];
    R.Jb = k;
    R.Pc = k;
    R.bb = function (a) {
        this.Jb = a;
    };
    R.Aa = function () {
        if (this.na[K]) return this.na.pop();
        return this.Hc();
    };
    R.Ka = function (a) {
        this.na[K] < this.rd ? this.na[x](a) : this.Oc(a);
    };
    R.se = function (a) {
        if (a > this.rd) g(Error("[goog.structs.SimplePool] Initial cannot be greater than max"));
        for (var b = 0; b < a; b++) this.na[x](this.Hc());
    };
    R.Hc = function () {
        return this.Jb ? this.Jb() : {};
    };
    R.Oc = function (a) {
        if (this.Pc) this.Pc(a);
        else if (dc(a.C)) a.C();
        else for (var b in a) delete a[b];
    };
    R.f = function () {
        de.k.f[O](this);
        for (var a = this.na; a[K]; ) this.Oc(a.pop());
        delete this.na;
    };
    var ee;
    var fe = (ee = "ScriptEngine" in S && S.ScriptEngine() == "JScript")
        ? S.ScriptEngineMajorVersion() + "." + S.ScriptEngineMinorVersion() + "." + S.ScriptEngineBuildVersion()
        : "0";
    var ge, he, ie, je, ke, le, me, ne, oe, pe, qe;
    (function () {
        function a() {
            return { P: 0, O: 0 };
        }
        function b() {
            return [];
        }
        function c() {
            var w = function (ta) {
                return h[O](w.src, w.key, ta);
            };
            return w;
        }
        function d() {
            return new be();
        }
        function e() {
            return new $d();
        }
        var f = ee && !(Qc(fe, "5.7") >= 0),
            h;
        le = function (w) {
            h = w;
        };
        if (f) {
            ge = function () {
                return i.Aa();
            };
            he = function (w) {
                i.Ka(w);
            };
            ie = function () {
                return o.Aa();
            };
            je = function (w) {
                o.Ka(w);
            };
            ke = function () {
                return p.Aa();
            };
            me = function () {
                p.Ka(c());
            };
            ne = function () {
                return u.Aa();
            };
            oe = function (w) {
                u.Ka(w);
            };
            pe = function () {
                return G.Aa();
            };
            qe = function (w) {
                G.Ka(w);
            };
            var i = new de(0, 600);
            i.bb(a);
            var o = new de(0, 600);
            o.bb(b);
            var p = new de(0, 600);
            p.bb(c);
            var u = new de(0, 600);
            u.bb(d);
            var G = new de(0, 600);
            G.bb(e);
        } else {
            ge = a;
            he = Zb;
            ie = b;
            je = Zb;
            ke = c;
            me = Zb;
            ne = d;
            oe = Zb;
            pe = e;
            qe = Zb;
        }
    })();
    var re = {},
        se = {},
        te = {},
        ue = {},
        ve = function (a, b, c, d, e) {
            if (b)
                if (ac(b)) {
                    for (var f = 0; f < b[K]; f++) ve(a, b[f], c, d, e);
                    return k;
                } else {
                    d = !!d;
                    var h = se;
                    b in h || (h[b] = ge());
                    h = h[b];
                    if (!(d in h)) {
                        h[d] = ge();
                        h.P++;
                    }
                    h = h[d];
                    var i = hc(a),
                        o;
                    h.O++;
                    if (h[i]) {
                        o = h[i];
                        for (f = 0; f < o[K]; f++) {
                            h = o[f];
                            if (h.Ga == c && h.ob == e) {
                                if (h.La) break;
                                return o[f].key;
                            }
                        }
                    } else {
                        o = h[i] = ie();
                        h.P++;
                    }
                    f = ke();
                    f.src = a;
                    h = ne();
                    h.Ya(c, f, a, b, d, e);
                    c = h.key;
                    f.key = c;
                    o[x](h);
                    re[c] = h;
                    te[i] || (te[i] = ie());
                    te[i][x](h);
                    if (a[Za]) {
                        if (a == S || !a.Ic) a[Za](b, f, d);
                    } else a.attachEvent(we(b), f);
                    return c;
                }
            else g(Error("Invalid event type"));
        },
        xe = function (a, b, c, d, e) {
            if (ac(b)) {
                for (var f = 0; f < b[K]; f++) xe(a, b[f], c, d, e);
                return k;
            }
            d = !!d;
            a = ye(a, b, d);
            if (!a) return l;
            for (f = 0; f < a[K]; f++) if (a[f].Ga == c && a[f][Pa] == d && a[f].ob == e) return ze(a[f].key);
            return l;
        },
        ze = function (a) {
            if (!re[a]) return l;
            var b = re[a];
            if (b.La) return l;
            var c = b.src,
                d = b[F],
                e = b.Cd,
                f = b[Pa];
            if (c[tb]) {
                if (c == S || !c.Ic) c[tb](d, e, f);
            } else c[Fb] && c[Fb](we(d), e);
            c = hc(c);
            e = se[d][f][c];
            if (te[c]) {
                var h = te[c];
                oc(h, b);
                h[K] == 0 && delete te[c];
            }
            b.La = j;
            e.sd = j;
            Ae(d, f, c, e);
            delete re[a];
            return j;
        },
        Ae = function (a, b, c, d) {
            if (!d.vb)
                if (d.sd) {
                    for (var e = 0, f = 0; e < d[K]; e++)
                        if (d[e].La) {
                            var h = d[e].Cd;
                            h.src = k;
                            me(h);
                            oe(d[e]);
                        } else {
                            if (e != f) d[f] = d[e];
                            f++;
                        }
                    va(d, f);
                    d.sd = l;
                    if (f == 0) {
                        je(d);
                        delete se[a][b][c];
                        se[a][b].P--;
                        if (se[a][b].P == 0) {
                            he(se[a][b]);
                            delete se[a][b];
                            se[a].P--;
                        }
                        if (se[a].P == 0) {
                            he(se[a]);
                            delete se[a];
                        }
                    }
                }
        },
        Be = function (a, b, c) {
            var d = 0,
                e = a == k,
                f = b == k,
                h = c == k;
            c = !!c;
            if (e)
                Bc(te, function (o) {
                    for (var p = o[K] - 1; p >= 0; p--) {
                        var u = o[p];
                        if ((f || b == u[F]) && (h || c == u[Pa])) {
                            ze(u.key);
                            d++;
                        }
                    }
                });
            else {
                a = hc(a);
                if (te[a]) {
                    a = te[a];
                    for (e = a[K] - 1; e >= 0; e--) {
                        var i = a[e];
                        if ((f || b == i[F]) && (h || c == i[Pa])) {
                            ze(i.key);
                            d++;
                        }
                    }
                }
            }
            return d;
        },
        ye = function (a, b, c) {
            var d = se;
            if (b in d) {
                d = d[b];
                if (c in d) {
                    d = d[c];
                    a = hc(a);
                    if (d[a]) return d[a];
                }
            }
            return k;
        },
        we = function (a) {
            if (a in ue) return ue[a];
            return (ue[a] = "on" + a);
        },
        De = function (a, b, c, d, e) {
            var f = 1;
            b = hc(b);
            if (a[b]) {
                a.O--;
                a = a[b];
                if (a.vb) a.vb++;
                else a.vb = 1;
                try {
                    for (var h = a[K], i = 0; i < h; i++) {
                        var o = a[i];
                        if (o && !o.La) f &= Ce(o, e) !== l;
                    }
                } finally {
                    a.vb--;
                    Ae(c, d, b, a);
                }
            }
            return Boolean(f);
        },
        Ce = function (a, b) {
            b = a[ab](b);
            a.Ac && ze(a.key);
            return b;
        };
    le(function (a, b) {
        if (!re[a]) return j;
        a = re[a];
        var c = a[F],
            d = se;
        if (!(c in d)) return j;
        d = d[c];
        var e, f;
        if (W) {
            e = b || Yb("window.event");
            b = j in d;
            var h = l in d;
            if (b) {
                if (e[E] < 0 || e.returnValue != q) return j;
                a: {
                    var i = l;
                    if (e[E] == 0)
                        try {
                            pa(e, -1);
                            break a;
                        } catch (o) {
                            i = j;
                        }
                    if (i || e.returnValue == q) e.returnValue = j;
                }
            }
            i = pe();
            i.Ya(e, this);
            e = j;
            try {
                if (b) {
                    for (var p = ie(), u = i.currentTarget; u; u = u[Pb]) p[x](u);
                    f = d[j];
                    f.O = f.P;
                    for (var G = p[K] - 1; !i.ia && G >= 0 && f.O; G--) {
                        na(i, p[G]);
                        e &= De(f, p[G], c, j, i);
                    }
                    if (h) {
                        f = d[l];
                        f.O = f.P;
                        for (G = 0; !i.ia && G < p[K] && f.O; G++) {
                            na(i, p[G]);
                            e &= De(f, p[G], c, l, i);
                        }
                    }
                } else e = Ce(a, i);
            } finally {
                if (p) {
                    va(p, 0);
                    je(p);
                }
                i.C();
                qe(i);
            }
            return e;
        }
        f = new $d(b, this);
        try {
            e = Ce(a, f);
        } finally {
            f.C();
        }
        return e;
    });
    var Ee = function (a) {
        this.D = a;
    };
    U(Ee, Yd);
    var Fe = new de(0, 100);
    R = Ee[L];
    R.q = function (a, b, c, d, e) {
        if (ac(b)) for (var f = 0; f < b[K]; f++) this.q(a, b[f], c, d, e);
        else this.Jf(ve(a, b, c || this, d || l, e || this.D || this));
        return this;
    };
    R.Jf = function (a) {
        if (this.F) this.F[a] = j;
        else if (this.da) {
            this.F = Fe.Aa();
            this.F[this.da] = j;
            this.da = k;
            this.F[a] = j;
        } else this.da = a;
    };
    R.Ra = function (a, b, c, d, e) {
        if (this.da || this.F)
            if (ac(b)) for (var f = 0; f < b[K]; f++) this.Ra(a, b[f], c, d, e);
            else {
                a: {
                    c = c || this;
                    e = e || this.D || this;
                    d = !!(d || l);
                    if ((a = ye(a, b, d)))
                        for (b = 0; b < a[K]; b++)
                            if (a[b].Ga == c && a[b][Pa] == d && a[b].ob == e) {
                                a = a[b];
                                break a;
                            }
                    a = k;
                }
                if (a) {
                    a = a.key;
                    ze(a);
                    if (this.F) Dc(this.F, a);
                    else if (this.da == a) this.da = k;
                }
            }
        return this;
    };
    R.ic = function () {
        if (this.F) {
            for (var a in this.F) {
                ze(a);
                delete this.F[a];
            }
            Fe.Ka(this.F);
            this.F = k;
        } else this.da && ze(this.da);
    };
    R.f = function () {
        Ee.k.f[O](this);
        this.ic();
    };
    R.handleEvent = function () {
        g(Error("EventHandler.handleEvent not implemented"));
    };
    var Ge = function () {};
    U(Ge, Yd);
    R = Ge[L];
    R.Ic = j;
    R.xb = k;
    R.kc = function (a) {
        this.xb = a;
    };
    R.addEventListener = function (a, b, c, d) {
        ve(this, a, b, c, d);
    };
    R.removeEventListener = function (a, b, c, d) {
        xe(this, a, b, c, d);
    };
    R.dispatchEvent = function (a) {
        a = a;
        if (cc(a)) a = new Zd(a, this);
        else if (a instanceof Zd) xa(a, a[Cb] || this);
        else {
            var b = a;
            a = new Zd(a[F], this);
            Gc(a, b);
        }
        b = 1;
        var c,
            d = a[F],
            e = se;
        if (d in e) {
            e = e[d];
            d = j in e;
            var f;
            if (d) {
                c = [];
                for (f = this; f; f = f.xb) c[x](f);
                f = e[j];
                f.O = f.P;
                for (var h = c[K] - 1; !a.ia && h >= 0 && f.O; h--) {
                    na(a, c[h]);
                    b &= De(f, c[h], a[F], j, a) && a.Ma != l;
                }
            }
            if (l in e) {
                f = e[l];
                f.O = f.P;
                if (d)
                    for (h = 0; !a.ia && h < c[K] && f.O; h++) {
                        na(a, c[h]);
                        b &= De(f, c[h], a[F], l, a) && a.Ma != l;
                    }
                else
                    for (c = this; !a.ia && c && f.O; c = c.xb) {
                        na(a, c);
                        b &= De(f, c, a[F], l, a) && a.Ma != l;
                    }
            }
            a = Boolean(b);
        } else a = j;
        return a;
    };
    R.f = function () {
        Ge.k.f[O](this);
        Be(this);
        this.xb = k;
    };
    var He = function (a, b, c, d) {
        this.top = a;
        this.right = b;
        this.bottom = c;
        oa(this, d);
    };
    He[L].t = function () {
        return new He(this.top, this[Vb], this[Ib], this[C]);
    };
    ua(He[L], function () {
        return "(" + this.top + "t, " + this[Vb] + "r, " + this[Ib] + "b, " + this[C] + "l)";
    });
    He[L].contains = function (a) {
        return !this || !a
            ? l
            : a instanceof He
              ? a[C] >= this[C] && a[Vb] <= this[Vb] && a.top >= this.top && a[Ib] <= this[Ib]
              : a.x >= this[C] && a.x <= this[Vb] && a.y >= this.top && a.y <= this[Ib];
    };
    var Ie = function (a, b, c, d) {
        oa(this, a);
        this.top = b;
        ja(this, c);
        Aa(this, d);
    };
    Ie[L].t = function () {
        return new Ie(this[C], this.top, this[y], this[Q]);
    };
    ua(Ie[L], function () {
        return "(" + this[C] + ", " + this.top + " - " + this[y] + "w x " + this[Q] + "h)";
    });
    Ie[L].tf = function (a) {
        var b = s.max(this[C], a[C]),
            c = s.min(this[C] + this[y], a[C] + a[y]);
        if (b <= c) {
            var d = s.max(this.top, a.top);
            a = s.min(this.top + this[Q], a.top + a[Q]);
            if (d <= a) {
                oa(this, b);
                this.top = d;
                ja(this, c - b);
                Aa(this, a - d);
                return j;
            }
        }
        return l;
    };
    Ie[L].contains = function (a) {
        return a instanceof Ie
            ? this[C] <= a[C] &&
                  this[C] + this[y] >= a[C] + a[y] &&
                  this.top <= a.top &&
                  this.top + this[Q] >= a.top + a[Q]
            : a.x >= this[C] && a.x <= this[C] + this[y] && a.y >= this.top && a.y <= this.top + this[Q];
    };
    var Je = function (a, b) {
            var c = nd(a);
            if (c[cb] && c[cb].getComputedStyle) if ((a = c[cb].getComputedStyle(a, ""))) return a[b];
            return k;
        },
        Ke = function (a, b) {
            return Je(a, b) || (a.currentStyle ? a.currentStyle[b] : k) || a[M][b];
        },
        Le = function (a) {
            var b = a[Ca]();
            if (W) {
                a = a.ownerDocument;
                b.left -= a[ib][Ya] + a[N][Ya];
                b.top -= a[ib][$a] + a[N][$a];
            }
            return b;
        },
        Me = function (a) {
            if (W) return a[xb];
            var b = nd(a),
                c = Ke(a, "position"),
                d = c == "fixed" || c == "absolute";
            for (a = a[Pb]; a && a != b; a = a[Pb]) {
                c = Ke(a, "position");
                d = d && c == "static" && a != b[ib] && a != b[N];
                if (!d && (a.scrollWidth > a[qb] || a.scrollHeight > a[Gb] || c == "fixed" || c == "absolute"))
                    return a;
            }
            return k;
        },
        Ne = function (a) {
            var b,
                c = nd(a),
                d = Ke(a, "position"),
                e = bd && c[Sa] && !a[Ca] && d == "absolute" && (b = c[Sa](a)) && (b[Qa] < 0 || b[Ra] < 0),
                f = new yc(0, 0),
                h;
            b = c ? (c[Ha] == 9 ? c : nd(c)) : r;
            h = W && !od(b).kd() ? b[N] : b[ib];
            if (a == h) return f;
            if (a[Ca]) {
                b = Le(a);
                a = od(c).Rb();
                f.x = b[C] + a.x;
                f.y = b.top + a.y;
            } else if (c[Sa] && !e) {
                b = c[Sa](a);
                a = c[Sa](h);
                f.x = b[Qa] - a[Qa];
                f.y = b[Ra] - a[Ra];
            } else {
                b = a;
                do {
                    f.x += b[zb];
                    f.y += b[Rb];
                    if (b != a) {
                        f.x += b[Ya] || 0;
                        f.y += b[$a] || 0;
                    }
                    if (X && Ke(b, "position") == "fixed") {
                        f.x += c[N][Hb];
                        f.y += c[N][kb];
                        break;
                    }
                    b = b[xb];
                } while (b && b != a);
                if (ad || (X && d == "absolute")) f.y -= c[N][Rb];
                for (b = a; (b = Me(b)) && b != c[N] && b != h; ) {
                    f.x -= b[Hb];
                    if (!ad || b[Lb] != "TR") f.y -= b[kb];
                }
            }
            return f;
        },
        Oe = function (a) {
            var b = new yc();
            if (a[Ha] == 1)
                if (a[Ca]) {
                    var c = Le(a);
                    b.x = c[C];
                    b.y = c.top;
                } else {
                    c = od(a).Rb();
                    a = Ne(a);
                    b.x = a.x - c.x;
                    b.y = a.y - c.y;
                }
            else {
                b.x = a[gb];
                b.y = a[hb];
            }
            return b;
        },
        Pe = function (a, b, c) {
            if (b instanceof Ac) {
                c = b[Q];
                b = b[y];
            } else {
                if (c == q) g(Error("missing height argument"));
                c = c;
            }
            ja(a[M], typeof b == "number" ? s[Ea](b) + "px" : b);
            Aa(a[M], typeof c == "number" ? s[Ea](c) + "px" : c);
        },
        Qe = function (a) {
            var b = ad && !ld("10");
            if (Ke(a, "display") != "none") return b ? new Ac(a[La] || a[qb], a[Sb] || a[Gb]) : new Ac(a[La], a[Sb]);
            var c = a[M],
                d = c.display,
                e = c.visibility,
                f = c[ob];
            ra(c, "hidden");
            wa(c, "absolute");
            za(c, "inline");
            if (b) {
                b = a[La] || a[qb];
                a = a[Sb] || a[Gb];
            } else {
                b = a[La];
                a = a[Sb];
            }
            za(c, d);
            wa(c, f);
            ra(c, e);
            return new Ac(b, a);
        },
        Re = function (a, b) {
            za(a[M], b ? "" : "none");
        },
        Se = function (a) {
            return "rtl" == Ke(a, "direction");
        };
    var Te = function () {};
    (function (a) {
        a.cd = function () {
            return a.rf || (a.rf = new a());
        };
    })(Te);
    Te[L].yf = 0;
    Te[L].af = function () {
        return ":" + (this.yf++)[mb](36);
    };
    Te.cd();
    var Ve = function (a) {
        this.ka = a || od();
        this.Of = Ue;
    };
    U(Ve, Ge);
    Ve[L].pf = Te.cd();
    var Ue = k;
    R = Ve[L];
    R.qb = k;
    R.ka = k;
    R.u = l;
    R.b = k;
    R.Of = k;
    R.vf = k;
    R.N = k;
    R.A = k;
    R.Z = k;
    R.Yd = l;
    R.Tb = function () {
        return this.qb || (this.qb = this.pf.af());
    };
    R.h = function () {
        return this.b;
    };
    R.Tf = function (a) {
        this.b = a;
    };
    R.R = function () {
        return this.Ba || (this.Ba = new Ee(this));
    };
    R.Jd = function (a) {
        if (this == a) g(Error("Unable to set parent component"));
        if (a && this.N && this.qb && this.N.Zc(this.qb) && this.N != a) g(Error("Unable to set parent component"));
        this.N = a;
        Ve.k.kc[O](this, a);
    };
    R.kc = function (a) {
        if (this.N && this.N != a) g(Error("Method not supported"));
        Ve.k.kc[O](this, a);
    };
    R.Sb = function () {
        return this.ka;
    };
    R.o = function () {
        this.b = this.ka[D]("div");
    };
    R.Mf = function (a, b) {
        if (this.u) g(Error("Component already rendered"));
        this.b || this.o();
        a ? a.insertBefore(this.b, b || k) : this.ka.g[N][v](this.b);
        if (!this.N || this.N.u) this.M();
    };
    R.Mc = function (a) {
        if (this.u) g(Error("Component already rendered"));
        else if (a && this.Bc(a)) {
            this.Yd = j;
            if (!this.ka || this.ka.g != nd(a)) this.ka = od(a);
            this.wa(a);
            this.M();
        } else g(Error("Invalid element to decorate"));
    };
    R.Bc = function () {
        return j;
    };
    R.wa = function (a) {
        this.b = a;
    };
    R.M = function () {
        this.u = j;
        this.Pb(function (a) {
            !a.u && a.h() && a.M();
        });
    };
    R.aa = function () {
        this.Pb(function (a) {
            a.u && a.aa();
        });
        this.Ba && this.Ba.ic();
        this.u = l;
    };
    R.f = function () {
        Ve.k.f[O](this);
        this.u && this.aa();
        if (this.Ba) {
            this.Ba.C();
            delete this.Ba;
        }
        this.Pb(function (a) {
            a.C();
        });
        !this.Yd && this.b && ud(this.b);
        this.N = this.vf = this.b = this.Z = this.A = k;
    };
    R.je = function (a, b) {
        this.ke(a, this.$c(), b);
    };
    R.ke = function (a, b, c) {
        if (a.u && (c || !this.u)) g(Error("Component already rendered"));
        if (b < 0 || b > this.$c()) g(Error("Child component index out of bounds"));
        if (!this.Z || !this.A) {
            this.Z = {};
            this.A = [];
        }
        if (a.N == this) {
            this.Z[a.Tb()] = a;
            oc(this.A, a);
        } else {
            var d = this.Z,
                e = a.Tb();
            if (e in d) g(Error('The object already contains the key "' + e + '"'));
            d[e] = a;
        }
        a.Jd(this);
        sc(this.A, b, 0, a);
        if (a.u && this.u && a.N == this) {
            c = this.b;
            c.insertBefore(a.h(), c.childNodes[b] || k);
        } else if (c) {
            this.b || this.o();
            b = this.Se(b + 1);
            a.Mf(this.b, b ? b.b : k);
        } else this.u && !a.u && a.b && a.M();
    };
    R.$c = function () {
        return this.A ? this.A[K] : 0;
    };
    R.Zc = function (a) {
        return this.Z && a ? Ec(this.Z, a) || k : k;
    };
    R.Se = function (a) {
        return this.A ? this.A[a] || k : k;
    };
    R.Pb = function (a, b) {
        this.A && nc(this.A, a, b);
    };
    R.removeChild = function (a, b) {
        if (a) {
            var c = cc(a) ? a : a.Tb();
            a = this.Zc(c);
            if (c && a) {
                Dc(this.Z, c);
                oc(this.A, a);
                if (b) {
                    a.aa();
                    a.b && ud(a.b);
                }
                a.Jd(k);
            }
        }
        if (!a) g(Error("Child is not in parent component"));
        return a;
    };
    var We = function () {};
    We[L].ra = function () {};
    var Xe = function (a, b) {
        this.element = a;
        this.re = b;
    };
    U(Xe, We);
    Xe[L].ra = function (a, b, c) {
        var d = this.element,
            e = this.re,
            f,
            h = a[xb];
        if (h) {
            var i = h[Lb] == "HTML" || h[Lb] == "BODY";
            if (!i || Ke(h, "position") != "static") {
                f = Ne(h);
                i || (f = zc(f, new yc(h[Hb], h[kb])));
            }
        }
        h = Ne(d);
        i = Qe(d);
        h = new Ie(h.x, h.y, i[y], i[Q]);
        var o;
        i = new He(0, Infinity, Infinity, 0);
        for (var p = od(d), u = p.g[N], G = p.Ue(), w = d; (w = Me(w)); )
            if (
                (!W || w[qb] != 0) &&
                (!X || w[Gb] != 0 || w != u) &&
                (w.scrollWidth != w[qb] || w.scrollHeight != w[Gb]) &&
                Ke(w, "overflow") != "visible"
            ) {
                var ta = Ne(w),
                    ia;
                ia = w;
                if (bd && !ld("1.9")) {
                    var Rd = ea(Je(ia, "borderLeftWidth"));
                    if (Se(ia)) {
                        var vf = ia[La] - ia[qb] - Rd - ea(Je(ia, "borderRightWidth"));
                        Rd += vf;
                    }
                    ia = new yc(Rd, ea(Je(ia, "borderTopWidth")));
                } else ia = new yc(ia[Ya], ia[$a]);
                ta.x += ia.x;
                ta.y += ia.y;
                i.top = s.max(i.top, ta.y);
                i.right = s.min(i[Vb], ta.x + w[qb]);
                i.bottom = s.min(i[Ib], ta.y + w[Gb]);
                oa(i, s.max(i[C], ta.x));
                o = o || w != G;
            }
        u = G[Hb];
        G = G[kb];
        if (X) {
            i.left += u;
            i.top += G;
        } else {
            oa(i, s.max(i[C], u));
            i.top = s.max(i.top, G);
        }
        if (!o || X) {
            i.right += u;
            i.bottom += G;
        }
        o = p.df();
        i.right = s.min(i[Vb], u + o[y]);
        i.bottom = s.min(i[Ib], G + o[Q]);
        (o = i.top >= 0 && i[C] >= 0 && i[Ib] > i.top && i[Vb] > i[C] ? i : k) &&
            h.tf(new Ie(o[C], o.top, o[Vb] - o[C], o[Ib] - o.top));
        o = od(d);
        p = od(a);
        if (o.g != p.g) {
            i = o.g[N];
            p = p.ed();
            G = new yc(0, 0);
            u = nd(i) ? nd(i).parentWindow || nd(i)[cb] : n;
            w = i;
            do {
                ta = u == p ? Ne(w) : Oe(w);
                G.x += ta.x;
                G.y += ta.y;
            } while (u && u != p && (w = u.frameElement) && (u = u[Bb]));
            p = G;
            p = zc(p, Ne(i));
            if (W && !o.kd()) p = zc(p, o.Rb());
            h.left += p.x;
            h.top += p.y;
        }
        d = (e & 4 && Se(d) ? e ^ 2 : e) & -5;
        d = new yc(d & 2 ? h[C] + h[y] : h[C], d & 1 ? h.top + h[Q] : h.top);
        if (f) d = zc(d, f);
        f = d;
        f = f.t();
        d = (b & 4 && Se(a) ? b ^ 2 : b) & -5;
        b = Qe(a);
        if (c || d != 0) {
            if (d & 2) f.x -= b[y] + (c ? c[Vb] : 0);
            else if (c) f.x += c[C];
            if (d & 1) f.y -= b[Q] + (c ? c[Ib] : 0);
            else if (c) f.y += c.top;
        }
        d = f;
        f = bd && (Wc || ed) && ld("1.9");
        if (d instanceof yc) {
            c = d.x;
            d = d.y;
        } else {
            c = d;
            d = void 0;
        }
        oa(a[M], typeof c == "number" ? (f ? s[Ea](c) : c) + "px" : c);
        a[M].top = typeof d == "number" ? (f ? s[Ea](d) : d) + "px" : d;
        (b == b ? j : !b || !b ? l : b[y] == b[y] && b[Q] == b[Q]) || Pe(a, b);
    };
    var Ye = function (a, b) {
        if (bd) {
            a.setAttribute("role", b);
            a.Cg = b;
        }
    };
    var $e = function (a, b, c, d, e) {
            if (!W && !(X && ld("525"))) return j;
            if (Wc && e) return Ze(a);
            if (e && !d) return l;
            if (W && !c && (b == 17 || b == 18)) return l;
            if (W && d && b == a) return l;
            switch (a) {
                case 13:
                    return j;
                case 27:
                    return !X;
            }
            return Ze(a);
        },
        Ze = function (a) {
            if (a >= 48 && a <= 57) return j;
            if (a >= 96 && a <= 106) return j;
            if (a >= 65 && a <= 90) return j;
            switch (a) {
                case 32:
                case 63:
                case 107:
                case 109:
                case 110:
                case 111:
                case 186:
                case 189:
                case 187:
                case 188:
                case 190:
                case 191:
                case 192:
                case 222:
                case 219:
                case 220:
                case 221:
                    return j;
                default:
                    return l;
            }
        };
    var af = function (a) {
        a && this.Fb(a);
    };
    U(af, Ge);
    R = af[L];
    R.b = k;
    R.sb = k;
    R.ac = k;
    R.tb = k;
    R.Za = -1;
    R.Ea = -1;
    var bf = {
            3: 13,
            12: 144,
            63232: 38,
            63233: 40,
            63234: 37,
            63235: 39,
            63236: 112,
            63237: 113,
            63238: 114,
            63239: 115,
            63240: 116,
            63241: 117,
            63242: 118,
            63243: 119,
            63244: 120,
            63245: 121,
            63246: 122,
            63247: 123,
            63248: 44,
            63272: 46,
            63273: 36,
            63275: 35,
            63276: 33,
            63277: 34,
            63289: 144,
            63302: 45,
        },
        cf = {
            Up: 38,
            Down: 40,
            Left: 37,
            Right: 39,
            Enter: 13,
            F1: 112,
            F2: 113,
            F3: 114,
            F4: 115,
            F5: 116,
            F6: 117,
            F7: 118,
            F8: 119,
            F9: 120,
            F10: 121,
            F11: 122,
            F12: 123,
            "U+007F": 46,
            Home: 36,
            End: 35,
            PageUp: 33,
            PageDown: 34,
            Insert: 45,
        },
        df = { 61: 187, 59: 186 },
        ef = W || (X && ld("525"));
    R = af[L];
    R.kf = function (a) {
        if (ef && !$e(a[E], this.Za, a.shiftKey, a[vb], a.altKey)) this[ab](a);
        else this.Ea = bd && a[E] in df ? df[a[E]] : a[E];
    };
    R.lf = function () {
        this.Ea = this.Za = -1;
    };
    R.handleEvent = function (a) {
        var b = a.ma,
            c,
            d;
        if (W && a[F] == "keypress") {
            c = this.Ea;
            d = c != 13 && c != 27 ? b[E] : 0;
        } else if (X && a[F] == "keypress") {
            c = this.Ea;
            d = b[Ua] >= 0 && b[Ua] < 63232 && Ze(c) ? b[Ua] : 0;
        } else if (ad) {
            c = this.Ea;
            d = Ze(c) ? b[E] : 0;
        } else {
            c = b[E] || this.Ea;
            d = b[Ua] || 0;
            if (Wc && d == 63 && !c) c = 191;
        }
        var e = c,
            f = b.keyIdentifier;
        if (c)
            if (c >= 63232 && c in bf) e = bf[c];
            else {
                if (c == 25 && a.shiftKey) e = 9;
            }
        else if (f && f in cf) e = cf[f];
        a = e == this.Za;
        this.Za = e;
        b = new ff(e, d, a, b);
        try {
            this[Oa](b);
        } finally {
            b.C();
        }
    };
    R.h = function () {
        return this.b;
    };
    R.Fb = function (a) {
        this.tb && this.detach();
        this.b = a;
        this.sb = ve(this.b, "keypress", this);
        this.ac = ve(this.b, "keydown", this.kf, l, this);
        this.tb = ve(this.b, "keyup", this.lf, l, this);
    };
    R.detach = function () {
        if (this.sb) {
            ze(this.sb);
            ze(this.ac);
            ze(this.tb);
            this.tb = this.ac = this.sb = k;
        }
        this.b = k;
        this.Za = -1;
    };
    R.f = function () {
        af.k.f[O](this);
        this.detach();
    };
    var ff = function (a, b, c, d) {
        d && this.Ya(d, void 0);
        qa(this, "key");
        pa(this, a);
        this.charCode = b;
        this.repeat = c;
    };
    U(ff, $d);
    var gf = function (a, b) {
        Ve[O](this);
        this.S = b || $;
        this.sg = this.S.Bb;
        this.a = new Wd(a);
        this.a.Id(this.S.ae);
        this.a.Hd(this.S.$d);
        this.n = this.a.t();
        this.n[I](1);
        this.Ab = ["", "", "", "", "", "", ""];
        this.Ab[this.S.xc[0]] = "goog-date-picker-wkend-start";
        this.Ab[this.S.xc[1]] = "goog-date-picker-wkend-end";
        this.Fa = {};
    };
    U(gf, Ve);
    R = gf[L];
    R.Nd = j;
    R.ag = j;
    R.ve = j;
    R.lc = j;
    R.Qd = j;
    R.Eb = j;
    R.Pd = j;
    R.gg = l;
    R.Nc = k;
    var hf = 0;
    R = gf[L];
    R.rg = function () {
        Re(this.Tc, this.Pd);
        Re(this.Sc, this.Eb);
        Re(this.Ud, this.Pd || this.Eb);
    };
    R.Bd = function () {
        this.n.add(new Vd("m", -1));
        this.ja();
    };
    R.td = function () {
        this.n.add(new Vd("m", 1));
        this.ja();
    };
    R.If = function () {
        this.n.add(new Vd("y", -1));
        this.ja();
    };
    R.zf = function () {
        this.n.add(new Vd("y", 1));
        this.ja();
    };
    R.Fd = function () {
        this[I](new Wd());
    };
    R.Ed = function () {
        this.Eb && this[I](k);
    };
    la(R, function () {
        return this.a;
    });
    sa(R, function (a) {
        var b =
            a != this.a && !(a && this.a && a[P]() == this.a[P]() && a[J]() == this.a[J]() && a[z]() == this.a[z]());
        this.a = a && new Wd(a);
        if (a) {
            this.n.Qf(this.a);
            this.n[I](1);
        }
        this.ja();
        this[Oa](new jf("select", this, this.a));
        b && this[Oa](new jf("change", this, this.a));
    });
    R.qg = function () {
        if (this.Mb) {
            for (var a = this.Mb; a[Xa]; ) a[Ab](a[Xa]);
            var b = od(a),
                c;
            if (this.gg) {
                c = b[D]("td");
                ya(c, this.lc ? 1 : 2);
                this.Q(c, "\u00ab", this.Bd);
                a[v](c);
                c = b[D]("td");
                ya(c, this.lc ? 6 : 5);
                t(c, "goog-date-picker-monthyear");
                a[v](c);
                this.Lb = c;
                c = b[D]("td");
                this.Q(c, "\u00bb", this.td);
                a[v](c);
            } else {
                var d = this.S.Ta[0][Ub]();
                c = b[D]("td");
                ya(c, 5);
                this.Q(c, "\u00ab", this.Bd);
                this.la = this.Q(c, "", this.$f, "goog-date-picker-month");
                this.Q(c, "\u00bb", this.td);
                b = b[D]("td");
                ya(b, 3);
                this.Q(b, "\u00ab", this.If);
                this.Va = this.Q(b, "", this.dg, "goog-date-picker-year");
                this.Q(b, "\u00bb", this.zf);
                if (d[B]("y") < d[B]("m")) {
                    a[v](b);
                    a[v](c);
                } else {
                    a[v](c);
                    a[v](b);
                }
            }
        }
    };
    R.wa = function (a) {
        gf.k.wa[O](this, a);
        t(a, "goog-date-picker");
        var b = od(a),
            c = b[D]("table"),
            d = b[D]("thead"),
            e = b[D]("tbody"),
            f = b[D]("tfoot");
        Ye(e, "grid");
        e.tabIndex = "0";
        this.mc = e;
        this.Ud = f;
        var h = b.o("tr", "goog-date-picker-head");
        this.Mb = h;
        this.qg();
        d[v](h);
        var i;
        this.L = [];
        for (var o = 0; o < 7; o++) {
            h = b[D]("tr");
            this.L[o] = [];
            for (var p = 0; p < 8; p++) {
                i = b[D](p == 0 || o == 0 ? "th" : "td");
                if ((p == 0 || o == 0) && p != o) {
                    t(i, p == 0 ? "goog-date-picker-week" : "goog-date-picker-wday");
                    Ye(i, p == 0 ? "rowheader" : "columnheader");
                }
                h[v](i);
                this.L[o][p] = i;
            }
            e[v](h);
        }
        h = b.o("tr", "goog-date-picker-foot");
        i = b.o("td", { colSpan: 2, className: "goog-date-picker-today-cont" });
        this.Tc = this.Q(i, "Today", this.Fd);
        h[v](i);
        i = b.o("td", { colSpan: 4 });
        h[v](i);
        i = b[D]("td");
        ya(i, 2);
        t(i, "goog-date-picker-none-cont");
        this.Sc = this.Q(i, "None", this.Ed);
        h[v](i);
        f[v](h);
        this.rg();
        c.cellSpacing = "0";
        c.cellPadding = "0";
        c[v](d);
        c[v](e);
        c[v](f);
        a[v](c);
        this.Lf();
        this.ja();
        a.tabIndex = 0;
    };
    R.o = function () {
        gf.k.o[O](this);
        this.wa(this.h());
    };
    R.M = function () {
        gf.k.M[O](this);
        var a = this.R();
        a.q(this.mc, "click", this.hf);
        a.q(this.Vb(this.h()), "key", this.jf);
    };
    R.aa = function () {
        gf.k.aa[O](this);
        this.xa();
        for (var a in this.Fa) this.Fa[a].C();
        this.Fa = {};
    };
    R.f = function () {
        gf.k.f[O](this);
        this.Sc = this.Tc = this.Va = this.Lb = this.la = this.Mb = this.Ud = this.mc = this.L = k;
    };
    R.hf = function (a) {
        if (a[Cb][Lb] == "TD") {
            var b,
                c = -2,
                d = -2;
            for (b = a[Cb]; b; b = b[lb], c++);
            for (b = a[Cb][Pb]; b; b = b[lb], d++);
            this[I](this.Ca[d][c].t());
        }
    };
    R.jf = function (a) {
        var b, c;
        switch (a[E]) {
            case 33:
                a[A]();
                b = -1;
                break;
            case 34:
                a[A]();
                b = 1;
                break;
            case 37:
                a[A]();
                c = -1;
                break;
            case 39:
                a[A]();
                c = 1;
                break;
            case 38:
                a[A]();
                c = -7;
                break;
            case 40:
                a[A]();
                c = 7;
                break;
            case 36:
                a[A]();
                this.Fd();
            case 46:
                a[A]();
                this.Ed();
            default:
                return;
        }
        if (this.a) {
            a = this.a.t();
            a.add(new Vd(0, b, c));
        } else {
            a = this.n.t();
            a[I](1);
        }
        this[I](a);
    };
    R.$f = function (a) {
        a[yb]();
        a = [];
        for (var b = 0; b < 12; b++) a[x](this.S.sa[b]);
        this.Gc(this.la, a, this.mf, this.S.sa[this.n[J]()]);
    };
    R.dg = function (a) {
        a[yb]();
        a = [];
        for (var b = this.n[P]() - 5, c = 0; c < 11; c++) a[x](fa(b + c));
        this.Gc(this.Va, a, this.nf, fa(this.n[P]()));
    };
    R.mf = function (a) {
        a = a;
        for (var b = -1; a; a = vd(a[lb], l), b++);
        this.n[Ta](b);
        this.ja();
        this.la[Va] && this.la[Va]();
    };
    R.nf = function (a) {
        if (a[Xa][Ha] == 3) {
            this.n[Wa](Number(a[Xa].nodeValue));
            this.ja();
        }
        this.Va[Va]();
    };
    R.Gc = function (a, b, c, d) {
        this.xa();
        var e = od(a),
            f = e.o("div", "goog-date-picker-menu");
        this.Ha = k;
        for (var h = e[D]("ul"), i = 0; i < b[K]; i++) {
            var o = e.o("li", k, b[i]);
            if (b[i] == d) this.Ha = o;
            h[v](o);
        }
        f[v](h);
        oa(f[M], a[zb] + a[Pb][zb] + "px");
        f[M].top = a[Rb] + "px";
        ja(f[M], a[qb] + "px");
        this.la[Pb][v](f);
        this.ga = f;
        if (!this.Ha) this.Ha = h[Xa];
        t(this.Ha, "goog-date-picker-menu-selected");
        this.ec = c;
        a = this.R();
        a.q(this.ga, "click", this.gd);
        a.q(this.Vb(this.ga), "key", this.hd);
        a.q(e.g, "click", this.xa);
        f.tabIndex = 0;
        f[Va]();
    };
    R.gd = function (a) {
        a[yb]();
        this.xa();
        this.ec && this.ec(a[Cb]);
    };
    R.hd = function (a) {
        a[yb]();
        var b,
            c = this.Ha;
        switch (a[E]) {
            case 35:
                a[A]();
                b = c[Pb].lastChild;
                break;
            case 36:
                a[A]();
                b = c[Pb][Xa];
                break;
            case 38:
                a[A]();
                b = c[lb];
                break;
            case 40:
                a[A]();
                b = c.nextSibling;
                break;
            case 13:
            case 9:
            case 0:
                a[A]();
                this.xa();
                this.ec(c);
                break;
        }
        if (b && b != c) {
            t(c, "");
            t(b, "goog-date-picker-menu-selected");
            this.Ha = b;
        }
    };
    R.xa = function () {
        if (this.ga) {
            var a = od(this.ga),
                b = this.R();
            b.Ra(this.ga, "click", this.gd);
            b.Ra(this.Vb(this.ga), "key", this.hd);
            b.Ra(a.g, "click", this.xa);
            a.removeNode(this.ga);
            delete this.ga;
        }
    };
    R.Q = function (a, b, c, d) {
        var e = ["goog-date-picker-btn"];
        d && e[x](d);
        d = od(a);
        var f = d[D]("button");
        t(f, e[Tb](" "));
        f[v](d[Na](b));
        a[v](f);
        this.R().q(f, "click", c);
        return f;
    };
    R.ja = function () {
        if (this.h()) {
            var a = this.n.t();
            a[I](1);
            this.Lb && xd(this.Lb, this.S.sa[a[J]()] + (" " + a[P]()));
            this.la && xd(this.la, this.S.sa[a[J]()]);
            this.Va && xd(this.Va, fa(a[P]()));
            var b = a.ff(),
                c = a.dd();
            a.add(new Vd("m", -1));
            a[I](a.dd() - (b - 1));
            this.Nd && !this.ve && c + b < 33 && a.add(new Vd("d", -7));
            b = new Vd("d", 1);
            this.Ca = [];
            for (c = 0; c < 6; c++) {
                this.Ca[c] = [];
                for (var d = 0; d < 7; d++) {
                    this.Ca[c][d] = a.t();
                    a.add(b);
                }
            }
            this.Kf();
        }
    };
    R.Kf = function () {
        if (this.h()) {
            var a = this.n[J](),
                b = new Wd(),
                c = b[P](),
                d = b[J]();
            b = b[z]();
            for (var e = 0; e < 6; e++) {
                if (this.lc) {
                    xd(this.L[e + 1][0], this.Ca[e][0].ef());
                    t(this.L[e + 1][0], "goog-date-picker-week");
                } else {
                    xd(this.L[e + 1][0], "");
                    t(this.L[e + 1][0], "");
                }
                for (var f = 0; f < 7; f++) {
                    var h = this.Ca[e][f],
                        i = this.L[e + 1][f + 1];
                    if (!i.id) i.id = "goog-dp-" + hf++;
                    Ye(i, "gridcell");
                    var o = ["goog-date-picker-date"];
                    if (this.ag || h[J]() == a) {
                        h[J]() != a && o[x]("goog-date-picker-other-month");
                        var p = (f + this.n.ba + 7) % 7;
                        this.Ab[p] && o[x](this.Ab[p]);
                        h[z]() == b && h[J]() == d && h[P]() == c && o[x]("goog-date-picker-today");
                        if (this.a && h[z]() == this.a[z]() && h[J]() == this.a[J]() && h[P]() == this.a[P]()) {
                            o[x]("goog-date-picker-selected");
                            bd && this.mc.setAttribute("aria-activedescendant", i.id);
                        }
                        if (this.Nc) (p = this.Nc(h)) && o[x](p);
                        xd(i, h[z]());
                    } else xd(i, "");
                    t(i, o[Tb](" "));
                }
                if (e >= 4) Re(this.L[e + 1][0][Pb], this.Ca[e][0][J]() == a || this.Nd);
            }
        }
    };
    R.Lf = function () {
        if (this.h()) {
            if (this.Qd) for (var a = 0; a < 7; a++) xd(this.L[0][a + 1], this.sg[(((a + this.n.ba + 7) % 7) + 1) % 7]);
            Re(this.L[0][0][Pb], this.Qd);
        }
    };
    R.Vb = function (a) {
        var b = hc(a);
        b in this.Fa || (this.Fa[b] = new af(a));
        return this.Fa[b];
    };
    var jf = function (a, b, c) {
        Zd[O](this, a, b);
        this.Jc = c;
    };
    U(jf, Zd);
    var kf = S.window,
        lf = function (a, b, c) {
            if (dc(a)) {
                if (c) a = jc(a, c);
            } else if (a && typeof a[ab] == "function") a = jc(a[ab], a);
            else g(Error("Invalid listener argument"));
            return b > 2147483647 ? -1 : kf[rb](a, b || 0);
        };
    var mf = function (a, b) {
        this.D = new Ee(this);
        this.Sf(a || k);
        b && this.Yf(b);
    };
    U(mf, Ge);
    R = mf[L];
    R.b = k;
    R.ne = j;
    R.zc = k;
    R.oa = l;
    R.Zf = l;
    R.od = -1;
    R.nd = -1;
    R.of = l;
    R.te = j;
    R.Qa = "toggle_display";
    R.Yf = function (a) {
        this.Qa = a;
    };
    R.h = function () {
        return this.b;
    };
    R.Sf = function (a) {
        this.ue();
        this.b = a;
    };
    R.ue = function () {
        if (this.oa) g(Error("Can not change this state of the popup while showing."));
    };
    R.Kd = function (a) {
        a ? this.eg() : this.pb();
    };
    R.ra = Zb;
    R.eg = function () {
        if (!this.oa)
            if (this.Bf()) {
                if (!this.b) g(Error("Caller must call setElement before trying to show the popup"));
                this.ra();
                var a = nd(this.b);
                this.of && this.D.q(a, "keydown", this.Cf, j);
                if (this.ne) {
                    this.D.q(a, "mousedown", this.yd, j);
                    if (W) {
                        for (var b = a.activeElement; b && b.nodeName == "IFRAME"; ) {
                            try {
                                var c = X ? b[sb] || b.contentWindow[sb] : b.contentDocument || b.contentWindow[sb];
                            } catch (d) {
                                break;
                            }
                            a = c;
                            b = a.activeElement;
                        }
                        this.D.q(a, "mousedown", this.yd, j);
                        this.D.q(a, "deactivate", this.xd);
                    } else this.D.q(a, "blur", this.xd);
                }
                if (this.Qa == "toggle_display") this.cg();
                else this.Qa == "move_offscreen" && this.ra();
                this.oa = j;
                this.Ff();
            }
    };
    R.pb = function (a) {
        if (!this.oa || !this.Af(a)) return l;
        this.D && this.D.ic();
        if (this.Qa == "toggle_display") this.Zf ? lf(this.jd, 0, this) : this.jd();
        else this.Qa == "move_offscreen" && this.xf();
        this.oa = l;
        this.Df(a);
        return j;
    };
    R.cg = function () {
        ra(this.b[M], "visible");
        Re(this.b, j);
    };
    R.jd = function () {
        ra(this.b[M], "hidden");
        Re(this.b, l);
    };
    R.xf = function () {
        oa(this.b[M], "-200px");
        this.b[M].top = "-200px";
    };
    R.Bf = function () {
        return this[Oa]("beforeshow");
    };
    R.Ff = function () {
        this.od = kc();
        this.nd = -1;
        this[Oa]("show");
    };
    R.Af = function (a) {
        return this[Oa]({ type: "beforehide", target: a });
    };
    R.Df = function (a) {
        this.nd = kc();
        this[Oa]({ type: "hide", target: a });
    };
    R.yd = function (a) {
        a = a[Cb];
        if (!wd(this.b, a) && (!this.zc || wd(this.zc, a)) && !this.Ld()) this.pb(a);
    };
    R.Cf = function (a) {
        if (a[E] == 27)
            if (this.pb(a[Cb])) {
                a[A]();
                a[yb]();
            }
    };
    R.xd = function (a) {
        if (this.te) {
            var b = nd(this.b);
            if (W || ad) {
                if ((a = b.activeElement) && wd(this.b, a)) return;
            } else if (a[Cb] != b) return;
            this.Ld() || this.pb();
        }
    };
    R.Ld = function () {
        return kc() - this.od < 150;
    };
    R.f = function () {
        mf.k.f[O](this);
        this.D.C();
        delete this.b;
        delete this.D;
    };
    var nf = function (a, b) {
        this.Hf = 4;
        this.hc = b || q;
        mf[O](this, a);
    };
    U(nf, mf);
    nf[L].Wf = function (a) {
        this.hc = a || q;
        this.oa && this.ra();
    };
    nf[L].ra = function () {
        if (this.hc) {
            var a = !this.oa && this.Qa != "move_offscreen",
                b = this.h();
            if (a) {
                ra(b[M], "hidden");
                Re(b, j);
            }
            this.hc.ra(b, this.Hf, this.yg);
            a && Re(b, l);
        }
    };
    var of = function (a, b) {
        Ve[O](this, b);
        this.J = a || new gf();
    };
    U(of, Ve);
    R = of[L];
    R.J = k;
    R.qa = k;
    R.ub = k;
    R.Db = j;
    R.o = function () {
        of.k.o[O](this);
        t(this.h(), "goog-popupdatepicker");
        this.qa = new nf(this.h());
    };
    R.M = function () {
        of.k.M[O](this);
        if (!this.J.u) {
            var a = this.h();
            ra(a[M], "hidden");
            Re(a, l);
            this.J.Mc(a);
        }
        this.R().q(this.J, "change", this.$a);
    };
    R.f = function () {
        of.k.f[O](this);
        if (this.qa) {
            this.qa.C();
            this.qa = k;
        }
        this.J.C();
        this.ub = this.J = k;
    };
    R.Bc = function () {
        return l;
    };
    la(R, function () {
        return this.J[z]();
    });
    sa(R, function (a) {
        this.J[I](a);
    });
    R.Fb = function (a) {
        this.R().q(a, "mousedown", this.Od);
    };
    R.detach = function (a) {
        this.R().Ra(a, "mousedown", this.Od);
    };
    R.Rf = function (a) {
        this.Db = a;
    };
    R.bg = function (a) {
        this.ub = a;
        this.qa.Wf(new Xe(a, 5));
        this.R().Ra(this.J, "change", this.$a);
        this.J[I](k);
        this[Oa]("show");
        this.R().q(this.J, "change", this.$a);
        this.qa.Kd(j);
        this.Db && this.h()[Va]();
    };
    R.Od = function (a) {
        this.bg(a.currentTarget);
    };
    R.Yb = function () {
        this.qa.Kd(l);
        this.Db && this.ub && this.ub[Va]();
    };
    R.$a = function (a) {
        this.Yb();
        this[Oa](a);
    };
    var pf = function (a, b, c, d) {
        Ve[O](this, d);
        this.Kc = a;
        this.Lc = b;
        this.j = new of(c, d);
        this.je(this.j);
        this.j.Rf(l);
    };
    U(pf, Ve);
    R = pf[L];
    R.Kc = k;
    R.Lc = k;
    R.j = k;
    R.Ad = k;
    la(R, function () {
        var a = this.Ub(),
            b = this.j[z]();
        if (a && b) a.Nb(b) || this.j[I](a);
        else this.j[I](k);
        return a;
    });
    sa(R, function (a) {
        this.j[I](a);
    });
    R.Vf = function (a) {
        var b = this.h();
        if (b.bc) b.bc.Dg(a);
        else ma(b, a);
    };
    R.Xe = function () {
        var a = this.h();
        return a.bc ? a.bc.wg() : a.value;
    };
    R.Ub = function () {
        var a = Ic(this.Xe());
        if (a) {
            var b = new Xd();
            if (this.Lc.hg(a, b) > 0) return b;
        }
        return k;
    };
    R.o = function () {
        this.Tf(this.Sb().o("input", { type: "text" }));
        this.j.o();
    };
    R.M = function () {
        pf.k.M[O](this);
        var a = this.h();
        (this.Ad || this.Sb().g[N])[v](this.j.h());
        this.j.M();
        this.j.Fb(a);
        this.j[I](this.Ub());
        a = this.R();
        a.q(this.j, "change", this.$a);
        a.q(this.j, "show", this.Ef);
    };
    R.aa = function () {
        pf.k.aa[O](this);
        this.j.detach(this.h());
        this.j.aa();
        ud(this.j.h());
    };
    R.wa = function (a) {
        pf.k.wa[O](this, a);
        this.j.o();
    };
    R.f = function () {
        pf.k.f[O](this);
        this.j.C();
        this.Ad = this.j = k;
    };
    R.Yb = function () {
        this.j.Yb();
    };
    R.Ef = function () {
        this[I](this.Ub());
    };
    R.$a = function (a) {
        this.Vf(a.Jc ? this.Kc.we(a.Jc) : "");
    };
    var qf = function () {};
    R = qf[L];
    R.G = k;
    R.Qb = k;
    R.ea = {};
    R.Wa = [];
    R.Vc = 0;
    R.le = function (a) {
        var b = this;
        return function (c) {
            if (c) {
                var d = new GMarker(c);
                GEvent.addListener(d, "click", function () {
                    d.openInfoWindowHtml(
                        '<div class="user_event_marker" style="width:256px;margin-right:14px">' + a[Ka] + "</div>"
                    );
                });
                b.ea[a.id] = d;
                b.G && b.G.addOverlay(d);
            }
            if (!b.G) {
                b.Vc += 1;
                b.Vc == b.Wa[K] && b.Fc();
            }
        };
    };
    R.Cb = function (a) {
        this.Qb.getLatLng(Z("span", "geocoder_address", a)[0][Ka], this.le(a));
    };
    R.Dd = function (a) {
        this.G.removeOverlay(this.ea[a]);
        this.ea[a] = k;
    };
    R.Nf = function (a, b) {
        this.Dd(a);
        this.Cb(b);
    };
    R.fb = function () {
        var a = new GLatLngBounds();
        for (var b in this.ea) this.ea[b] && a.extend(this.ea[b].getLatLng());
        this.G.setCenter(a.getCenter(), 13);
        this.G.setZoom(s.min(13, this.G.getBoundsZoomLevel(a)));
    };
    R.Fc = function () {
        this.G = new GMap2(Y("user_event_map_canvas"));
        this.fb();
        this.G.addControl(new GSmallZoomControl3D());
        this.G.addControl(new GMenuMapTypeControl());
        this.G.addControl(new GScaleControl());
        for (var a in this.ea) this.G.addOverlay(this.ea[a]);
    };
    R.qf = function () {
        if (GBrowserIsCompatible()) {
            wc(Y("user_event_map_container"), "user_event_map_loading");
            if ((this.Qb = new GClientGeocoder())) {
                var a = Y("user_event_info");
                this.Wa = Z("div", "user_event_single", a);
                if (this.Wa[K]) for (a = 0; a < this.Wa[K]; a++) this.Cb(this.Wa[a]);
                else this.Fc();
            }
        }
    };
    var rf = new Pd("MM'/'dd'/'yyyy"),
        sf = new Sd("MM'/'dd'/'yyyy"),
        tf = l,
        uf = k,
        wf = function (a) {
            a = Z("input", "edit_date", a);
            for (var b = 0; b < a[K]; b++) {
                var c = a[b];
                new pf(rf, sf).Mc(c);
            }
        },
        yf = function (a) {
            var b = Y("edit_form_user_events");
            if (a) {
                xf();
                b.X = b[Ka];
                vc(Y("user_events"), "edit_mode");
            } else {
                wc(Y("user_events"), "edit_mode");
                if (b.X) ka(b, b.X);
            }
        },
        zf = function (a) {
            var b = Y("create_event");
            if (a) {
                xf();
                b.X = b[Ka];
                vc(Y("user_events"), "event_add_mode");
            } else {
                wc(Y("user_events"), "event_add_mode");
                if (b.X) {
                    ka(b, b.X);
                    wf(b);
                }
            }
        },
        Af = function (a, b) {
            a = Y("event_" + a);
            if (b) {
                xf();
                a.X = a[Ka];
                vc(a, "editing_event");
            } else {
                wc(a, "editing_event");
                if (a.X) {
                    ka(a, a.X);
                    wf(a);
                }
            }
        },
        xf = function () {
            yf(l);
            zf(l);
            for (var a = Z("div", "user_event_single", Y("user_event_info")), b = 0; b < a[K]; b++) Af(a[b].id, l);
        },
        Bf = function (a) {
            for (var b = {}, c = 0; c < a.elements[K]; c++) {
                var d = a.elements[c];
                b[d[db]] = d.value;
            }
            return b;
        },
        Cf = function (a, b, c) {
            var d = ha(),
                e = get_channel_box_info("user_events");
            d.call_box_method(e, b, a, function (f) {
                if (f.success) {
                    var h = Y("user_event_info");
                    ka(h, f.html);
                    wf(h);
                    c && c(f);
                    ba("user_events-messages", "#d0ffd8", zd("SUCCESS"));
                } else f.errors && ba("user_events-messages", "#F99", f.errors[Tb]("<br>"));
            });
        };
    T("goog.dom.$", Y, void 0);
    T("goog.dom.$$", Z, void 0);
    T("goog.dom.removeNode", ud, void 0);
    T("Arranger", Hd, void 0);
    T(
        "initEventDates",
        function () {
            var a = Y("user_event_map_container") != k;
            if (a && !tf) {
                tf = j;
                a = r[D]("script");
                qa(a, "text/javascript");
                a.src =
                    "http://maps.google.com/maps?file=api&v=2&sensor=false&key=ABQIAAAAYMcxl3UidHj1TfgZY5wwAxSHXJsp5oogWH5jZodYSc2VMsh-GBRPvc9p8ICx6LDtcul957CGKg5HKQ&async=2&callback=initEventDates";
                r.getElementsByTagName("head")[0][v](a);
            } else {
                wf();
                if (a) {
                    uf = new qf();
                    uf.qf();
                }
            }
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.editEvent",
        function (a) {
            Af(a, j);
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.cancelEdit",
        function (a) {
            Af(a, l);
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.toggleAdd",
        function () {
            zf(!xc(Y("user_events"), "event_add_mode"));
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.cancelAdd",
        function () {
            zf(l);
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.toggleBoxEdit",
        function () {
            yf(!xc(Y("user_events"), "edit_mode"));
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.cancelBoxEdit",
        function () {
            yf(l);
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.createEvent",
        function () {
            var a = Bf(Y("create_event_form"));
            Cf("create_event", a, function (b) {
                wc(Y("user_events"), "event_add_mode");
                if (uf) {
                    b = Y("show_event_" + b.event_id);
                    uf.Cb(Z("div", "user_event_single", b)[0]);
                    uf.fb();
                }
            });
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.updateEvent",
        function (a) {
            var b = Bf(Y("edit_event_form_" + a));
            b.event_id = a;
            Cf("update_event", b, function () {
                if (uf) {
                    var c = Y("show_event_" + a);
                    uf.Nf(a, Z("div", "user_event_single", c)[0]);
                    uf.fb();
                }
            });
        },
        void 0
    );
    T(
        "yt.www.channel.EventDates.deleteEvent",
        function (a) {
            Cf("delete_event", { event_id: a }, function () {
                if (uf) {
                    uf.Dd(a);
                    uf.fb();
                }
            });
        },
        void 0
    );
})();
