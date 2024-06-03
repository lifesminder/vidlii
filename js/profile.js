function c_move_up(e) {
    var t = $("#" + e).prev().attr("id");
    if (void 0 != t && $("#" + t).hasClass("hddn"))
        for (var s = !1; 0 == s;) {
            var l = $("#" + t).prev().attr("id");
            s = !$("#" + l).hasClass("hddn"), t = l
        }
    if (void 0 != t) {
        $("#" + e).swapWith("#" + t);
        var a = $("#" + e).attr("module"),
            n = $("#" + t).attr("module");
        a.indexOf("_r") >= 0 ? (a = a.replace("_r", ""), n = n.replace("_r", ""), vertical_r = vertical_r.replace(a, n), vertical_r = vertical_r.replace(n, a), $.ajax({
            type: "POST",
            url: "/ajax/change_vert",
            data: {
                type: 0,
                vert: vertical_r
            }
        })) : (a = a.replace("_l", ""), n = n.replace("_l", ""), vertical_l = vertical_l.replace(a, n), vertical_l = vertical_l.replace(n, a), $.ajax({
            type: "POST",
            url: "/ajax/change_vert",
            data: {
                type: 1,
                vert: vertical_l
            }
        })), i
    }
}

function c_move_down(e) {
    var t = $("#" + e).next().attr("id");
    if (void 0 != t && $("#" + t).hasClass("hddn"))
        for (var s = !1; 0 == s;) {
            var l = $("#" + t).next().attr("id");
            s = !$("#" + l).hasClass("hddn"), t = l
        }
    if (void 0 != t) {
        $("#" + e).swapWith("#" + t);
        var a = $("#" + e).attr("module"),
            n = $("#" + t).attr("module");
        a.indexOf("_r") >= 0 ? (a = a.replace("_r", ""), n = n.replace("_r", ""), vertical_r = vertical_r.replace(n, a), vertical_r = vertical_r.replace(a, n), $.ajax({
            type: "POST",
            url: "/ajax/change_vert",
            data: {
                type: 0,
                vert: vertical_r
            }
        })) : (a = a.replace("_l", ""), n = n.replace("_l", ""), vertical_l = vertical_l.replace(n, a), vertical_l = vertical_l.replace(a, n), $.ajax({
            type: "POST",
            url: "/ajax/change_vert",
            data: {
                type: 1,
                vert: vertical_l
            }
        }))
    }
}

function get_video_info(e) {
    $("#pl_inf_i").addClass("pl_nav_sel"), $("#pl_inf_f").removeClass("pl_nav_sel"), $("#pl_inf_s").removeClass("pl_nav_sel"), $("#pl_inf_p").removeClass("pl_nav_sel"), $("#pl_inf_fl").removeClass("pl_nav_sel"), $.ajax({
        type: "POST",
        url: "/ajax/get_video_info",
        data: {
            id: e
        },
        success: function(t) {
            $("#pl_inf").html("<a href='/watch?v=" + t.url + "'>" + t.title + "</a><span>From: <a href='/user/" + t.displayname + "'>" + t.displayname + "</a> | " + t.uploaded_on + " | " + t.views + " views<br><div class='pr_pl_descr'>" + t.description + "</div></span><div style='position:absolute;bottom:16px;left:19px;font-size:15px;font-weight:bold'><a href='/watch?v=" + t.url + "'>View comments, related videos, and more</a></div><div style='position:absolute;top:17px;right:19px;padding: 2px 5px 0px;background:white;border-radius:8px'>" + t.rating + "</div>");
            if(vplayer === 2) {
                window.modernPlayer = new ModernPlayer({
                    instance: jwplayer("vlplayer"),
                    duration: t.length,
                    videoUrl: "/watch?v=" + e,
                    src: "/usfi/v/" + e + "." + t.file + ".mp4",
                    hdsrc: 1 == t.hd ? "/usfi/v/" + e + "." + t.file + ".720.mp4" : null,
                    startinhd: "1" == getCookie("vlphd"),
                    preview: "/usfi/thmp/" + e + ".jpg",
                    autoplay: !0
                });
            } else {
                window.vlp.change({
                    src: "/usfi/v/" + e + "." + t.file + ".mp4",
                    hdsrc: 1 == t.hd ? "/usfi/v/" + e + "." + t.file + ".720.mp4" : null,
                    preview: "/usfi/thmp/" + e + ".jpg",
                    duration: t.length,
                    videoUrl: "/watch?v=" + e,
                    autoplay: !0
                });
            }
        }
    })
}

function fntpreview() {
    var e = $("#ch_fnt").val();
    if (0 == e) var t = "Arial";
    else if (1 == e) t = "Georgia";
    else if (2 == e) t = "Times New Roman";
    else if (3 == e) t = "Comic Sans MS";
    else if (4 == e) t = "Impact";
    else if (5 == e) t = "Tahoma";
    else if (6 == e) t = "Courier New";
    $(".wrapper").css("font-family", t)
}

function copyToClipboard(e) {
    var t = $("<input>");
    $("body").append(t), t.val($(e).text()).select(), document.execCommand("copy"), t.remove()
}

function add_playlist() {
    var e = $("#pl_sel option:selected").val();
    $.ajax({
        type: "POST",
        url: "/ajax/add_playlist",
        data: {
            pid: e
        },
        success: function(e) {
            "success" == e.response ? ($("#pl_text").remove(), $("#pl_box").append('<div class="pl_row" id="pl_' + e.purl + '"><div class="playlist"><a href="/playlist?p=' + e.purl + '"><img src="/usfi/thmp/' + e.thumbnail + '.jpg"></a></div><div class="pl_info"><a href="/playlist?p=' + e.purl + '">' + e.title + '</a><em>No Description...</em></div><div><a href="javascript:void(0)">Play All</a><br><a href="javascript:void(0)">Share</a><br><a href="javascript:void(0)" onclick="remove_pl(\'' + e.purl + "')\">Remove</a></div></div>"), $("#pl_box").css("padding-bottom", "1px")) : "too_many" == e.response ? alert("You can display 3 playlists on your channel!") : "already" == e.response && alert("You already have this playlist displayed on your channel!")
        }
    })
}

function remove_pl(e) {
    $("#pl_" + e).remove(), 0 == $(".pl_row").length && $("#pl_box").append('<div id="pl_text" style="text-align: center;font-size: 14px;margin-bottom:4px">You have not added any playlists yet!</div>'), $.ajax({
        type: "POST",
        url: "/ajax/remove_playlist",
        data: {
            pid: e
        }
    })
}

function add_to_playlist() {
    var e = $("#playlist_select option:selected").val(),
        t = $("#pl_url").html();
    void 0 != e ? $.ajax({
        type: "POST",
        url: "/ajax/add_to_playlist",
        data: {
            pid: e,
            id: t
        },
        success: function(e) {
            "success" == e.response ? $("#pl_inf").html("<div style='font-size:14px;text-align:center'><div style='font-size:16px;margin-bottom:10px'>Video successfully added to playlist!</div></div>") : "already" == e.response && alert("Video is already in this playlist!")
        }
    }) : alert("Please select a playlist!")
}

function move_hor(e, t) {
    $("#" + e).toggleClass("hddn"), $("#" + t).toggleClass("hddn"), $.ajax({
        type: "POST",
        url: "/ajax/move_hor_module",
        data: {
            Module: e
        }
    })
}

function update_cc_privacy(e) {
    if (0 == e) {
        if ($("#cc_setting1").is(":checked")) var t = 0;
        else if ($("#cc_setting2").is(":checked")) t = 1;
        else if ($("#cc_setting3").is(":checked")) t = 2
    } else if ($("#cc_setting4").is(":checked")) t = 0;
    else if ($("#cc_setting5").is(":checked")) t = 1;
    else if ($("#cc_setting6").is(":checked")) t = 2;
    $.ajax({
        type: "POST",
        url: "/ajax/update_comment_privacy",
        data: {
            setting: t
        },
        success: function(e) {
            $("#edit_cc").toggleClass("hddn"), alert("Channel comment privacy successfully updated!")
        }
    })
}

function delete_cc(e) {
    $("#cc2_" + e).remove(), $("#cc_" + e).remove(), 0 == $(".ch_cmt").length && ($("#ch_cmt_sct").html('<div id="no_comments" style="margin:7px 0;text-align: center;font-size: 13px">There are no comments for this user.</div>'), $("#ch_cmt_sct2").html('<div id="no_comments2" style="margin:7px 0;text-align: center;font-size: 13px">There are no comments for this user.</div>'), $(".prbx_in .cc_pagination").remove()), 0 == $(".chn_cmt_sct").length && ($("#channel_comments").html('<div id="no_comments">There are no comments for this user.</div>'), $("#channel_comments2").html('<div id="no_comments2" style="text-align: center;font-size:13px;margin:15px 0">There are no comments for this user.</div>'), $("#channel_comments").toggleClass("no_border"), $(".in_box .cc_pagination").remove()), $.ajax({
        type: "POST",
        url: "/ajax/delete_ch_comment",
        data: {
            c_c_id: e
        },
        success: function(e) {
            1 == e.new_com && 1 == e.can_delete ? ($("#channel_comments").append('<div class="chn_cmt_sct" id="cc_' + e.id + '"><a href="/user/VidLii"><img onerror="this.src = \'/img/no.png\'" src="' + e.avatar + '.jpg" width="55" height="55" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><span><a href="/user/' + e.by_user + '">' + e.by_user + "</a> <span>(" + e.date + ")</span></span>" + e.comment + '</div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" style="position: absolute;top:0;right:0">Delete</a></div></div>'), $("#channel_comments2").append('<div class="chn_cmt_sct" id="cc_' + e.id + '"><a href="/user/VidLii"><img onerror="this.src = \'/img/no.png\'" src="' + e.avatar + '.jpg" width="55" height="55" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><span><a href="/user/' + e.by_user + '">' + e.by_user + "</a> <span>(" + e.date + ")</span></span>" + e.comment + '</div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" style="position: absolute;top:0;right:0">Delete</a></div></div>')) : 1 == e.new_com && ($("#channel_comments").append('<div class="chn_cmt_sct" id="cc_' + e.id + '"><a href="/user/VidLii"><img onerror="this.src = \'/img/no.png\'" src="' + e.avatar + '.jpg" width="55" height="55" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><span><a href="/user/' + e.by_user + '">' + e.by_user + "</a> <span>(" + e.date + ")</span></span>" + e.comment + "</div></div></div>"), $("#channel_comments2").append('<div class="chn_cmt_sct" id="cc_' + e.id + '"><a href="/user/VidLii"><img onerror="this.src = \'/img/no.png\'" src="' + e.avatar + '.jpg" width="55" height="55" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><span><a href="/user/' + e.by_user + '">' + e.by_user + "</a> <span>(" + e.date + ")</span></span>" + e.comment + "</div></div></div>"));
            var t = document.getElementById("cc_count").innerText;
            t.indexOf(",") > -1 ? (t = t.replace(",", ""), t = (--t).toLocaleString("us")) : t--, $("#cc_count").html(t)
        }
    })
}

function save_information() {
    var e = $("#check_name").is(":checked"),
        t = $("#check_website").is(":checked"),
        s = $("#check_description").is(":checked"),
        l = $("#check_occupation").is(":checked"),
        a = $("#check_schools").is(":checked"),
        n = $("#check_interests").is(":checked"),
        r = $("#check_movies").is(":checked"),
        i = $("#check_music").is(":checked"),
        o = $("#check_books").is(":checked"),
        c = $("#check_subs").is(":checked"),
        d = $("#check_last").is(":checked"),
        _ = $("#check_age").is(":checked"),
        m = $("#check_country").is(":checked"),
        h = $("#check_subs2").is(":checked"),
        v = $("#name_value").val(),
        p = $("#website_value").val(),
        u = $("#description_value").val(),
        f = $("#occupation_value").val(),
        g = $("#schools_value").val(),
        b = $("#interests_value").val(),
        y = $("#movies_value").val(),
        C = $("#music_value").val(),
        x = $("#books_value").val(),
        w = $("#country option:selected").val();
    $.ajax({
        type: "POST",
        url: "/ajax/update_profile",
        data: {
            Subs2_Checked: h,
            Country_Value: w,
            Country_Checked: m,
            Age_Checked: _,
            Last_Checked: d,
            Subs_Checked: c,
            Name_Checked: e,
            Website_Checked: t,
            Description_Checked: s,
            Occupation_Checked: l,
            Schools_Checked: a,
            Interests_Checked: n,
            Movies_Checked: r,
            Music_Checked: i,
            Books_Checked: o,
            Name_Value: v,
            Website_Value: p,
            Description_Value: u,
            Occupation_Value: f,
            Schools_Value: g,
            Interests_Value: b,
            Movies_Value: y,
            Music_Value: C,
            Books_Value: x
        },
        success: function(e) {
            "error" !== e ? document.location.reload(!0) : alert("Your information isn't valid!")
        }
    })
}

function get_videos(e) {
    var t = $("#ch_user").html(),
        s = $("#pl_url").html();
    $.ajax({
        type: "POST",
        url: "/ajax/get_videos",
        data: {
            type: e,
            user: t,
            selected: s
        },
        success: function(e) {
            $("#pl_list").html(e), $(".pr_pl_mnu").animate({
                scrollTop: 0
            }, "fast")
        }
    })
}

function getTimeHash() {
    var e, t = 0;
    return (e = window.location.href.indexOf("#t=")) >= 0 ? (t = window.location.href.substr(e + 3), parseInt(t)) : 0
}

function edit_channel_info() {
    "none" == $("#ch_edit_info").css("display") ? ($("#ch_info_sct").css("display", "none"), $("#ch_edit_info").css("display", "block")) : ($("#ch_edit_info").css("display", "none"), $("#ch_info_sct").css("display", "block"))
}

function post_bulletin() {
    var e = document.getElementById("bulletin").value,
        t = document.getElementById("bulletin2").value,
        s = $("#ch_displayname").html();
    if (e.length > 0 && e.length < 501 || t.length > 0 && t.length < 501) {
        document.getElementById("bulletin").value = "", document.getElementById("bulletin2").value = "";
        var l = new FormData;
        if (window.XMLHttpRequest) var a = new XMLHttpRequest;
        else if (window.ActiveXObject) a = new ActiveXObject("Microsoft.XMLHTTP");
        e.length > 0 ? l.append("bulletin", e) : l.append("bulletin", t), a.open("POST", "/ajax/post_bulletin"), a.send(l), t.length > 0 ? (document.getElementById("no_ra2") && (document.getElementById("no_ra2").outerHTML = ""), document.getElementById("ra_in2").style.display = "table", document.getElementById("ra_in2").innerHTML = '<tr><td valign="top" width="21"><img src="/img/ra1.png"></td><td><strong>' + s + "</strong> " + t + ' <span>(1 second ago)</span></td><td width="21"><a style="text-decoration: none" href="javascript:void(0)">X</a></td> </tr>' + document.getElementById("ra_in2").innerHTML) : (document.getElementById("no_ra") && (document.getElementById("no_ra").outerHTML = ""), document.getElementById("ra_in").style.display = "table", document.getElementById("ra_in").innerHTML = '<tr><td valign="top" width="21"><img src="/img/ra1.png"></td><td><strong>' + s + "</strong> " + e + ' <span>(1 second ago)</span></td><td width="21"><a style="text-decoration: none" href="javascript:void(0)">X</a></td> </tr>' + document.getElementById("ra_in").innerHTML)
    } else alert("Bulletins can't be empty!")
}

function delete_bulletin(e) {
    document.getElementById("b_" + e).outerHTML = "", document.getElementById("b2_" + e).outerHTML = "";
    var t = new FormData;
    if (window.XMLHttpRequest) var s = new XMLHttpRequest;
    else if (window.ActiveXObject) s = new ActiveXObject("Microsoft.XMLHTTP");
    t.append("bulletin", e), s.open("POST", "/ajax/delete_bulletin"), s.send(t)
}

function add_ft_channel() {
    var e = document.getElementById("channel_add").value,
        t = document.getElementById("channel_add2").value;
    if (e.length > 1 || t.length > 1) {
        var s = new FormData;
        if (window.XMLHttpRequest) var l = new XMLHttpRequest;
        else if (window.ActiveXObject) l = new ActiveXObject("Microsoft.XMLHTTP");
        0 != e.length ? s.append("user", e) : s.append("user", t), l.addEventListener("load", add_ft_channel_succ, !1), l.open("POST", "/ajax/add_ft_channel"), l.send(s)
    } else alert("You must type in a valid channel!")
}

function add_ft_channel_succ(e) {
    if (e.target.responseText.length > 5) {
        $("#add_fttxt").css("display", "none");
        var t = JSON.parse(e.target.responseText);
        document.getElementById("fc").innerHTML = document.getElementById("fc").innerHTML + '<div class="fc_sct"> <a href="/user/' + t.displayname + '">' + t.displayname + '</a><img src="' + t.avatar + '" class="avt pr_avt" width="50" height="50"><br><a href="javascript:void(0)" onclick="remove_ft(' + t.username + ')">Remove</a><br>Videos: ' + t.videos + "<br>Video Views: " + t.video_views + "<br>Subscribers: " + t.subscribers + " </div>", document.getElementById("fc2").innerHTML = document.getElementById("fc2").innerHTML + '<div class="fc_sct2" id="fc2_' + t.username + '"><img src="' + t.avatar + '" class="avt pr_avt" width="64" height="64"><div style="float:left;width:390px"> <a href="/user/' + t.displayname + '">' + t.displayname + "</a><br> " + t.channel_description + ' <br> <a href="javascript:void(0)" onclick="remove_ft(\'' + t.username + "')\">Remove</a><br> </div> <div>Videos: " + t.videos + "<br>Video Views: " + t.video_views + "<br>Subscribers: " + t.subscribers + " </div> </div>"
    } else "u_d" == e.target.responseText ? alert("User doesn't exist!") : "u_m" == e.target.responseText ? alert("You can have at most 8 featured channels!") : "u_e" == e.target.responseText && alert("You already have this channel featured!")
}

function remove_ft(e) {
    if (e.length > 1) {
        var t = new FormData;
        if (window.XMLHttpRequest) var s = new XMLHttpRequest;
        else if (window.ActiveXObject) s = new ActiveXObject("Microsoft.XMLHTTP");
        t.append("user", e), s.open("POST", "/ajax/delete_ft_channel"), s.send(t), document.getElementById("fc_" + e).outerHTML = "", document.getElementById("fc2_" + e).outerHTML = ""
    }
}

function save_ft_title(e) {
    var t = $("#ft_title_change").val(),
        s = $("#ft_title_change2").val();
    t.length > 0 || s.length > 0 ? 0 == e && t.length > 0 ? ($("#ft_title").html($("#ft_title_change").val()), $("#ft_title2").html($("#ft_title_change").val()), $("#ft_title_change2").val($("#ft_title_change").val())) : 1 == e && t.length > 0 ? ($("#ft_title").html($("#ft_title_change2").val()), $("#ft_title2").html($("#ft_title_change2").val()), $("#ft_title_change").val($("#ft_title_change2").val()), t = s) : ($("#ft_title").html("Featured Channels"), $("#ft_title2").html("Featured Channels"), $("#ft_title_change").val("Featured Channels"), $("#ft_title_change2").val("Featured Channels")) : ($("#ft_title").html("Featured Channels"), $("#ft_title2").html("Featured Channels"), $("#ft_title_change").val("Featured Channels"), $("#ft_title_change2").val("Featured Channels")), $.ajax({
        type: "POST",
        url: "/ajax/save_ft_title",
        data: {
            title: t
        }
    })
}

function hex2rgba(e, t) {
    return e = e.replace("#", ""), r = parseInt(e.substring(0, e.length / 3), 16), g = parseInt(e.substring(e.length / 3, 2 * e.length / 3), 16), b = parseInt(e.substring(2 * e.length / 3, 3 * e.length / 3), 16), result = "rgba(" + r + "," + g + "," + b + "," + t / 100 + ")", result
}

function bg(e) {
    $("#gbg").css("background-color", hex2rgba("#" + e, 100))
}

function wrapper(e) {
    $(".ob_col").css("background-color", hex2rgba("#" + e, trans1)), $("#v_sel").css("background-color", hex2rgba("#" + e, 100)), $(".pr_pl_title_sty").css("border-left-color", hex2rgba("#" + e, trans1)), $(".pr_inf_sct").css("border-bottom-color", hex2rgba("#" + e, trans1)), $(".ra tr td").css("border-bottom-color", hex2rgba("#" + e, trans1)), $(".ra").css("border-color", hex2rgba("#" + e, trans1)), $(".mnu_sct").css("border-bottom-color", hex2rgba("#" + e, trans1)), $("#no_comments, #channel_comments").css("border-color", hex2rgba("#" + e, trans1))
}

function wrapper_text(e) {
    $(".ob_col").css("color", hex2rgba("#" + e, 100))
}

function wrapper_links(e) {
    $(".pr_tp_pl_nav a").css("color", hex2rgba("#" + e, 100))
}

function in_bg(e) {
    $(".ib_col").css("background-color", hex2rgba("#" + e, trans2)), $("#pl_toggle_sel b").css("background-color", hex2rgba("#" + e, 100)), $("#pl_toggle_sel em").css("background-color", hex2rgba("#" + e, 100)), $(".pl_nav_sel_hd").attr("style", "color: " + hex2rgba("#" + e, 100) + " !important"), $("#nav_ind").css("border-bottom-color", hex2rgba("#" + e, trans2))
}

function in_hd(e) {
    $(".box_title").css("color", hex2rgba("#" + e, 100))
}

function in_link(e) {
    $(".ib_col a").css("color", hex2rgba("#" + e, 100)), $(".pr_avt").css("border-color", hex2rgba("#" + e, 100)), $(".pr_pl_toggles a > i > b, em").css("background-color", hex2rgba("#" + e, 100))
}

function in_text(e) {
    $(".ib_col").css("color", hex2rgba("#" + e, 100)), $(".pl_nav_sel_hd").css("background", hex2rgba("#" + e, 100)), $("#pl_toggle_sel").css("background", hex2rgba("#" + e, 100))
}

function delete_background() {
    document.getElementById("bg_delete").disabled = !0;
    var e = new FormData;
    if (window.XMLHttpRequest) var t = new XMLHttpRequest;
    else if (window.ActiveXObject) t = new ActiveXObject("Microsoft.XMLHTTP");
    e.append("bg", "ar"), t.addEventListener("load", bg_del_comp, !1), t.open("POST", "/ajax/delete_background"), t.send(e)
}

function bg_del_comp() {
    document.getElementById("bg_upload").style.display = "block", document.getElementById("bg_delete").style.display = "none", document.getElementById("bg_info").style.display = "block", $("#gbg").css("background-image", "url('')")
}

function theme_select(e) {
    if ("grey" == e) {
        $("#default").addClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("Grey"), $("#theme_selectnum").val("0");
        var t = "CCCCCC",
            s = "999999",
            l = "000000",
            a = "0000cc",
            n = "eeeeff",
            r = "000000",
            i = "0000cc",
            o = "333333"
    } else if ("blue" == e) {
        $("#default").removeClass("theme_sel"), $("#blue").addClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("Blue"), $("#theme_selectnum").val("1");
        t = "003366", s = "0066CC", l = "ffffff", a = "0000CC", n = "3D8BD8", r = "ffffff", i = "99C2EB", o = "ffffff"
    } else if ("red" == e) {
        $("#default").removeClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").addClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("Red"), $("#theme_selectnum").val("2");
        t = "660000", s = "990000", l = "FFFFFF", a = "FF0000", n = "660000", r = "FFFFFF", i = "FF0000", o = "FFFFFF"
    } else if ("yellow" == e) {
        $("#default").removeClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").addClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("Sunlight"), $("#theme_selectnum").val("3");
        t = "FFE599", s = "E69138", l = "FFFFFF", a = "FFD966", n = "FFD966", r = "E69138", i = "E69138", o = "E69138"
    } else if ("green" == e) {
        $("#default").removeClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").addClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("Forest"), $("#theme_selectnum").val("4");
        t = "274E13", s = "38761D", l = "ffffff", a = "FFFFFF", n = "6AA84F", r = "274E13", i = "38761D", o = "274E13"
    } else if ("black" == e) {
        $("#default").removeClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").addClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("8-Bit"), $("#theme_selectnum").val("5");
        t = "666666", s = "444444", l = "FFFFFF", a = "FF0000", n = "000000", r = "AAAAAA", i = "FF0000", o = "666666"
    } else if ("pink" == e) {
        $("#default").removeClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").addClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("Princess"), $("#theme_selectnum").val("6");
        t = "ff99cc", s = "aa66cc", l = "ffffff", a = "351C75", n = "ffffff", r = "8a2c87", i = "351C75", o = "333366"
    } else if ("fire" == e) {
        $("#default").removeClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").addClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("Fire"), $("#theme_selectnum").val("7");
        t = "660000", s = "FF0000", l = "ffffff", a = "FFFF00", n = "FF9900", r = "FFFF00", i = "FFDBA6", o = "ffffff"
    } else if ("stealth" == e) {
        $("#default").removeClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").addClass("theme_sel"), $("#custom").removeClass("theme_sel"), $("#theme_title").html("Stealth"), $("#theme_selectnum").val("8");
        t = "000000", s = "444444", l = "000000", a = "CCCCCC", n = "666666", r = "000000", i = "444444", o = "444444"
    } else "custom" == e && ($("#default").removeClass("theme_sel"), $("#blue").removeClass("theme_sel"), $("#red").removeClass("theme_sel"), $("#yellow").removeClass("theme_sel"), $("#green").removeClass("theme_sel"), $("#black").removeClass("theme_sel"), $("#pink").removeClass("theme_sel"), $("#fire").removeClass("theme_sel"), $("#stealth").removeClass("theme_sel"), $("#custom").addClass("theme_sel"), $("#theme_title").html("Custom"), "none" == document.getElementById("advanced_customization").style.display && show_advanced_custom(), $("#theme_selectnum").val("9"));
    "custom" !== e && (document.getElementById("ed_bg_color").jscolor.fromString(t), document.getElementById("ed_wrp_color").jscolor.fromString(s), document.getElementById("ed_wrptxt_color").jscolor.fromString(l), document.getElementById("ed_wrplnk_color").jscolor.fromString(a), document.getElementById("in_wrapper").jscolor.fromString(n), document.getElementById("col_wrapper").jscolor.fromString(r), document.getElementById("in_link").jscolor.fromString(i), document.getElementById("in_txt").jscolor.fromString(o), bg(t), wrapper(s), wrapper_text(l), wrapper_links(a), in_hd(r), in_link(i), in_bg(n), in_text(o))
}

function show_advanced_custom() {
    "none" == document.getElementById("advanced_customization").style.display ? (document.getElementById("advanced_customization").style.display = "block", document.getElementById("show_advanced_btn").innerHTML = "hide advanced options") : (document.getElementById("advanced_customization").style.display = "none", document.getElementById("show_advanced_btn").innerHTML = "show advanced options")
}

function show_more(e, t) {
    document.getElementById("show_more").disabled = !0, document.getElementById("show_more").innerHTML = "Loading...";
    var s = $("#ch_user").html();
    $.ajax({
        type: "POST",
        url: "/ajax/show_more",
        data: {
            type: e,
            page: t,
            user: s
        },
        success: function(e) {
            document.getElementById("show_more").outerHTML = "", $(".mnu_sct").append(e)
        }
    })
}
$("#pl_inf_i").click(function() {
    var e = $("#pl_url").html();
    $("#nav_ind").css("left", "15px"), get_video_info(e)
}), $("#pl_inf_f").click(function() {
    var e = $("#pl_url").html();
    $("#pl_inf_i").removeClass("pl_nav_sel"), $("#pl_inf_f").addClass("pl_nav_sel"), $("#pl_inf_s").removeClass("pl_nav_sel"), $("#pl_inf_p").removeClass("pl_nav_sel"), $("#pl_inf_fl").removeClass("pl_nav_sel"), $("#nav_ind").css("left", "95px"), $.ajax({
        type: "POST",
        url: "/ajax/favorite_video",
        data: {
            id: e
        },
        success: function(e) {
            "added" == e.response ? $("#pl_inf").html("<div style='padding:12px;background:white;font-size:14px'>This video has been <strong>added</strong> to your favorites.</div>") : "removed" == e.response ? $("#pl_inf").html("<div style='padding:12px;background:white;font-size:14px'>This video has been <strong>removed</strong> from your favorites.</div>") : "not_logged_in" == e.response && $("#pl_inf").html("<div style='padding:12px;background:white;font-size:14px'>You must <strong><a href='/login'>log in</a></strong> to favorite this video.</div>")
        }
    })
}), $("#pl_inf_s").click(function() {
    var e = $("#pl_url").html();
    $("#pl_inf_i").removeClass("pl_nav_sel"), $("#pl_inf_f").removeClass("pl_nav_sel"), $("#pl_inf_s").addClass("pl_nav_sel"), $("#pl_inf_p").removeClass("pl_nav_sel"), $("#pl_inf_fl").removeClass("pl_nav_sel"), $("#nav_ind").css("left", "179px"), $("#pl_inf").html("<div style='font-size:14px;text-align:center'><div style='font-size:16px;margin-bottom:10px'>Share this Video with others:</div>Permalink:<br><input style='width: 300px;-moz-user-select: all;-ms-user-select: all;-webkit-user-select: all;user-select: all;text-align:center' type='text' value='/watch?v=" + e + "' readonly><br><br>Embed Link:<br><input style='width: 300px;-moz-user-select: all;-ms-user-select: all;-webkit-user-select: all;user-select: all;text-align:center' type='text' value='/embed?v=" + e + "' readonly><br><br><br><a href='#'>Twitter</a> || <a href='#'>Facebook</a> || <a href='#'>Reddit</a></div>")
}), $("#pl_inf_p").click(function() {
    var e = $("#pl_url").html();
    $("#pl_inf_i").removeClass("pl_nav_sel"), $("#pl_inf_f").removeClass("pl_nav_sel"), $("#pl_inf_s").removeClass("pl_nav_sel"), $("#pl_inf_p").addClass("pl_nav_sel"), $("#pl_inf_fl").removeClass("pl_nav_sel"), $("#nav_ind").css("left", "265px"), $.ajax({
        type: "POST",
        url: "/ajax/get_playlists",
        data: {
            id: e
        },
        success: function(e) {
            "logged_in" == e.response ? $("#pl_inf").html("<div style='font-size:14px;text-align:center'><div style='font-size:16px;margin-bottom:10px'>Select a Playlist:</div>" + e.select + "</div>") : $("#pl_inf").html("<div style='font-size:14px;text-align:center'><div style='font-size:16px;margin-bottom:10px'>Please <a href='/login'>log in</a> to add videos to playlists!</div></div>")
        }
    })
}), $("#post_comment").click(function() {
    var e = $("#comment_content").val(),
        t = $("#ch_user").html();
    $("#comment_content").val(""), e.length > 1 ? ($("#post_comment").attr("disabled", "disabled"), $.ajax({
        type: "POST",
        url: "/ajax/post_channel_comment",
        data: {
            comment: e,
            on_channel: t
        },
        success: function(e) {
            var t = $(".chn_cmt_sct").length;
            10 != t && 20 != t || ($("#channel_comments2").children().last().remove(), $("#channel_comments").children().last().remove()), $("#no_comments2").remove(), $("#no_comments").length > 0 && ($("#no_comments").remove(), $("#channel_comments").removeClass("no_border")), $("#channel_comments2").prepend('<div class="chn_cmt_sct" id="cc_' + e.id + '"><a href="/user/' + e.by_user + '"><img src="' + e.avatar + '" width="55" height="55" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><span><a href="/user/' + e.by_user + '">' + e.by_user + "</a> <span>(1 second ago)</span></span>" + e.comment + '</div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" style="position: absolute;top:0;right:0">Delete</a></div></div>'), $("#channel_comments").prepend('<div class="chn_cmt_sct" id="cc2_' + e.id + '"><a href="/user/' + e.by_user + '"><img src="' + e.avatar + '" width="55" height="55" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><span><a href="/user/' + e.by_user + '">' + e.by_user + "</a> <span>(1 second ago)</span></span>" + e.comment + '</div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" style="position: absolute;top:0;right:0">Delete</a></div></div>'), $("#post_comment").removeAttr("disabled");
            var s = document.getElementById("cc_count").innerText;
            s.indexOf(",") > -1 ? (s = s.replace(",", ""), s = (++s).toLocaleString("us")) : s++, $("#cc_count").html(s), $("html, body").animate({
                scrollTop: $("#cc_count").offset().top
            }, 400)
        }
    })) : alert("Your comment must be at least 2 characters long!")
}), $("#post_comment2").click(function() {
    var e = $("#comment_content2").val(),
        t = $("#ch_user").html();
    if ($("#comment_content2").val(""), e.length > 1) {
        $("#post_comment2").attr("disabled", "disabled"), $.ajax({
            type: "POST",
            url: "/ajax/post_channel_comment",
            data: {
                comment: e,
                on_channel: t
            },
            success: function(e) {
                var t = $(".chn_cmt_sct").length;
                10 != t && 20 != t || ($("#channel_comments2").children().last().remove(), $("#channel_comments").children().last().remove()), $("#no_comments").length > 0 && ($("#no_comments").remove(), $("#channel_comments").removeClass("no_border")), $("#no_comments2").length > 0 && ($("#no_comments2").remove(), $("#no_comments").remove(), $("#channel_comments2").removeClass("no_border")), $("#channel_comments").prepend('<div class="chn_cmt_sct" id="cc_' + e.id + '"><a href="/user/' + e.by_user + '"><img src="' + e.avatar + '" width="55" height="55" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><span><a href="/user/' + e.by_user + '">' + e.by_user + "</a> <span>(1 second ago)</span></span>" + e.comment + '</div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" style="position: absolute;top:0;right:0">Delete</a></div></div>'), $("#channel_comments2").prepend('<div class="chn_cmt_sct" id="cc2_' + e.id + '"><a href="/user/' + e.by_user + '"><img src="' + e.avatar + '" width="55" height="55" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><span><a href="/user/' + e.by_user + '">' + e.by_user + "</a> <span>(1 second ago)</span></span>" + e.comment + '</div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" style="position: absolute;top:0;right:0">Delete</a></div></div>'), $("#post_comment2").removeAttr("disabled")
            }
        });
        var s = document.getElementById("cc_count").innerText;
        s.indexOf(",") > -1 ? (s = s.replace(",", ""), s = (++s).toLocaleString("us")) : s++, $("#cc_count").html(s)
    } else alert("Your comment must be at least 2 characters long!")
}), $("#post_comment1").click(function() {
    var e = $("#comment_content").val(),
        t = $("#ch_user").html();
    $("#comment_content").val(""), e.length > 1 ? ($("#post_comment1").attr("disabled", "disabled"), $.ajax({
        type: "POST",
        url: "/ajax/post_channel_comment",
        data: {
            comment: e,
            on_channel: t
        },
        success: function(e) {
            var t = $(".ch_cmt").length;
            10 != t && 20 != t || ($("#ch_cmt_sct").children().last().remove(), $("#ch_cmt_sct2").children().last().remove()), ($("#no_comments").length > 0 || $("#no_comments2").length > 0) && ($("#no_comments").remove(), $("#no_comments2").remove()), $("#ch_cmt_sct").prepend('<div class="ch_cmt" id="cc_' + e.id + '"><a href="/user/' + e.by_user + '"><img src="' + e.avatar + '" width="68" height="68" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><a href="/user/' + e.by_user + '">' + e.by_user + '</a> (1 second ago)<div class="cmt_msg">' + e.comment + '</div></div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" class="cd">Delete</a></div></div>'), $("#ch_cmt_sct2").prepend('<div class="ch_cmt" id="cc2_' + e.id + '"><a href="/user/' + e.by_user + '"><img src="' + e.avatar + '" width="68" height="68" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><a href="/user/' + e.by_user + '">' + e.by_user + '</a> (1 second ago)<div class="cmt_msg">' + e.comment + '</div></div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" class="cd">Delete</a></div></div>'), $("#post_comment1").removeAttr("disabled");
            var s = document.getElementById("cc_count").innerText;
            s.indexOf(",") > -1 ? (s = s.replace(",", ""), s = (++s).toLocaleString("us")) : s++, $("#cc_count").html(s), $("html, body").animate({
                scrollTop: $("#cc_count").offset().top
            }, 400)
        }
    })) : alert("Your comment must be at least 2 characters long!")
}), $("#post_comment4").click(function() {
    var e = $("#comment_content2").val(),
        t = $("#ch_user").html();
    $("#comment_content2").val(""), e.length > 1 ? ($("#post_comment4").attr("disabled", "disabled"), $.ajax({
        type: "POST",
        url: "/ajax/post_channel_comment",
        data: {
            comment: e,
            on_channel: t
        },
        success: function(e) {
            var t = $(".ch_cmt").length;
            10 != t && 20 != t || ($("#ch_cmt_sct").children().last().remove(), $("#ch_cmt_sct2").children().last().remove()), ($("#no_comments").length > 0 || $("#no_comments2").length > 0) && ($("#no_comments").remove(), $("#no_comments2").remove()), $("#ch_cmt_sct2").prepend('<div class="ch_cmt" id="cc2_' + e.id + '"><a href="/user/' + e.by_user + '"><img src="' + e.avatar + '" width="68" height="68" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><a href="/user/' + e.by_user + '">' + e.by_user + '</a> (1 second ago)<div class="cmt_msg">' + e.comment + '</div></div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" class="cd">Delete</a></div></div>'), $("#ch_cmt_sct").prepend('<div class="ch_cmt" id="cc_' + e.id + '"><a href="/user/' + e.by_user + '"><img src="' + e.avatar + '" width="68" height="68" class="avt2 pr_avt" alt="' + e.by_user + '"></a><div><a href="/user/' + e.by_user + '">' + e.by_user + '</a> (1 second ago)<div class="cmt_msg">' + e.comment + '</div></div><a href="javascript:void(0)" onclick="delete_cc(' + e.id + ')" class="cd">Delete</a></div></div>'), $("#post_comment4").removeAttr("disabled");
            var s = document.getElementById("cc_count").innerText;
            s.indexOf(",") > -1 ? (s = s.replace(",", ""), s = (++s).toLocaleString("us")) : s++, $("#cc_count").html(s)
        }
    })) : alert("Your comment must be at least 2 characters long!")
}), $("#aaf").click(function() {
    var e = $("#aaf").html(),
        t = $("#ch_user").html();
    if ("Cancel Invite" == e) {
        if (0 == confirm("Are you sure you want to cancel the friend invite?")) return !1
    } else if ("Unfriend" == e && 0 == confirm("Are you sure you want to remove this channel as a friend?")) return !1;
    $.ajax({
        type: "POST",
        url: "/ajax/add_friend",
        data: {
            user: t
        },
        success: function(e) {
            "0" == e.response ? $("#aaf").html("Add as Friend") : "1" == e.response ? $("#aaf").html("Unfriend") : "2" == e.response ? $("#aaf").html("Add as Friend") : "3" == e.response ? $("#aaf").html("Cancel Invite") : alert(e.response)
        }
    })
}), $("#pr_all").click(function() {
    $("#pr_favorites").removeClass("pl_nav_sel_hd"), $("#pr_all").addClass("pl_nav_sel_hd"), $("#pr_playlists").removeClass("pl_nav_sel_hd"), $("#pr_uploads").removeClass("pl_nav_sel_hd"), get_videos("all")
}), $("#pr_uploads").click(function() {
    $("#pr_all").removeClass("pl_nav_sel_hd"), $("#pr_uploads").addClass("pl_nav_sel_hd"), $("#pr_playlists").removeClass("pl_nav_sel_hd"), $("#pr_favorites").removeClass("pl_nav_sel_hd"), get_videos("uploads")
}), $("#pr_playlists").click(function() {
    $("#pr_all").removeClass("pl_nav_sel_hd"), $("#pr_playlists").addClass("pl_nav_sel_hd"), $("#pr_uploads").removeClass("pl_nav_sel_hd"), $("#pr_favorites").removeClass("pl_nav_sel_hd"), get_videos("playlists")
}), $("#pr_favorites").click(function() {
    $("#pr_all").removeClass("pl_nav_sel_hd"), $("#pr_playlists").removeClass("pl_nav_sel_hd"), $("#pr_favorites").addClass("pl_nav_sel_hd"), $("#pr_uploads").removeClass("pl_nav_sel_hd"), get_videos("favorites")
}), $(".pl_toggler").click(function() {
    $(".pl_toggler").attr("id", ""), $(this).attr("id", "pl_toggle_sel"), "Switch to Player View" == $(this).attr("title") ? $(".pr_tp_btm").removeClass("grid") : $(".pr_tp_btm").addClass("grid")
}), $(".info_toggle").click(function() {
    $(this).next().toggleClass("opa"), $(this).parent().parent().next().find("input").toggleClass("opa"), $(this).parent().parent().next().find("select").toggleClass("opa"), $(this).parent().parent().next().find("textarea").toggleClass("opa"), $(this).parent().parent().next().find("span").toggleClass("opa")
}), $(document.body).on("click", ".mnu_vid", function() {
    $(this);
    var e = $(this).attr("watch");
    $("#v_sel").attr("id", "");
    if (void 0 != e) {
        if ($(this).attr("id", "v_sel"), "Switch to Player View" !== $("#pl_toggle_sel").attr("title")) {
            $(".pr_tp_btm").removeClass("grid");
            var t = $("#pl_toggle_sel");
            $(".pl_toggler").attr("id", "pl_toggle_sel"), $(t).attr("id", "")
        }
        $("#pl_url").html(e), get_video_info(e)
    } else PURL = $(this).attr("pl"), SELECTED = $("#pl_url").html(), $.ajax({
        type: "POST",
        url: "/ajax/get_playlist_videos",
        data: {
            pid: PURL,
            selected: SELECTED
        },
        success: function(e) {
            $("#pl_list").html(e), $(".pr_pl_mnu").animate({
                scrollTop: 0
            }, "fast")
        }
    })
}), $(".pr_edit_btn").click(function() {
    var e = $(this).attr("id");
    if ($("#" + e).is(".pr_edit_btn_sel")) return $("#" + e).removeClass("pr_edit_btn_sel"), void $("#edit_" + e).toggleClass("hddn");
    "settings" == e ? ($("#settings").addClass("pr_edit_btn_sel"), $("#themes").removeClass("pr_edit_btn_sel"), $("#modules").removeClass("pr_edit_btn_sel"), $("#vap").removeClass("pr_edit_btn_sel"), $("#edit_themes").is(".hddn") || $("#edit_themes").addClass("hddn"), $("#edit_modules").is(".hddn") || $("#edit_modules").addClass("hddn"), $("#edit_vap").is(".hddn") || $("#edit_vap").addClass("hddn"), $("#edit_settings").toggleClass("hddn")) : "themes" == e ? ($("#settings").removeClass("pr_edit_btn_sel"), $("#themes").addClass("pr_edit_btn_sel"), $("#modules").removeClass("pr_edit_btn_sel"), $("#vap").removeClass("pr_edit_btn_sel"), $("#edit_settings").is(".hddn") || $("#edit_settings").addClass("hddn"), $("#edit_modules").is(".hddn") || $("#edit_modules").addClass("hddn"), $("#edit_vap").is(".hddn") || $("#edit_vap").addClass("hddn"), $("#edit_themes").toggleClass("hddn")) : "modules" == e ? ($("#settings").removeClass("pr_edit_btn_sel"), $("#themes").removeClass("pr_edit_btn_sel"), $("#modules").addClass("pr_edit_btn_sel"), $("#vap").removeClass("pr_edit_btn_sel"), $("#edit_settings").is(".hddn") || $("#edit_settings").addClass("hddn"), $("#edit_themes").is(".hddn") || $("#edit_themes").addClass("hddn"), $("#edit_vap").is(".hddn") || $("#edit_vap").addClass("hddn"), $("#edit_modules").toggleClass("hddn")) : "vap" == e && ($("#settings").removeClass("pr_edit_btn_sel"), $("#themes").removeClass("pr_edit_btn_sel"), $("#modules").removeClass("pr_edit_btn_sel"), $("#vap").addClass("pr_edit_btn_sel"), $("#edit_settings").is(".hddn") || $("#edit_settings").addClass("hddn"), $("#edit_themes").is(".hddn") || $("#edit_themes").addClass("hddn"), $("#edit_modules").is(".hddn") || $("#edit_modules").addClass("hddn"), $("#edit_vap").toggleClass("hddn"))
}), $(".jscolor").click(function() {
    "9" !== $("#theme_selectnum").val() && theme_select("custom")
});