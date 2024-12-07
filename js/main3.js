function _(element) {
    return document.getElementById(element)
}

function isEmptyOrSpaces(str) {
    return str === null || str.match(/^ *$/) !== null
}

function new_upload() {
    var video = $("#selectedFile")[0].files[0];
    var extension = video.name.lastIndexOf(".");
    var page_title = $("title").html();
    var video_title = video.name;
    var video_thumb = 0;
    if (extension != -1) video_title = video_title.substring(0, extension);
    if (video_title.length < 4) video_title = "Untitled";
    $("#video_title").val(video_title);
    $("#video_title_header").html(video_title);
    $("#custom_thumb_button").on("click mousedown", function() {
        if (!$(this).hasClass("loading")) {
            $("#custom_thumb_file").click();
            return !1
        }
    });
    $("#custom_thumb_file").change(function() {
        var file = $(this)[0].files[0];
        var name = file.name;
        var extension = name.lastIndexOf(".");
        if (extension != -1) {
            extension = name.substring(extension + 1);
            extension = extension.toUpperCase();
            if (["BMP", "PNG", "JPG", "JPEG"].indexOf(extension) != -1) {
                $("#custom_thumb_button").addClass("loading");
                var data = new FormData();
                data.append("ajax", "1");
                data.append("c_thmp_uploader", file);
                $.ajax({
                    url: '/edit_video?v=' + $("#vurl").html(),
                    data: data,
                    cache: !1,
                    contentType: !1,
                    processData: !1,
                    method: 'POST',
                    type: 'POST',
                    success: function(r) {
                        var success = r.substring(0, 1);
                        var data = r.substring(2);
                        if (success == "1") {
                            video_thumb++;
                            $("#custom_thumb_button").attr("src", data + "?v=" + video_thumb)
                        } else {
                            alert(data)
                        }
                    },
                    error: function() {
                        alert("An error has occurred while submitting your thumbnail!")
                    },
                    complete: function() {
                        $("#custom_thumb_button").removeClass("loading")
                    }
                });
                $(this).val("");
                return
            }
        }
        $(this).val("");
        alert("Only images, please!")
    });
    $("#custom_thumb_button").parent().hide();
    $("#save_upload_changes_button").hide();
    $("#selectedFile").prop("disabled", !0);
    $("#uploader").animate({
        opacity: 0
    }, 500, function() {
        $("#old_upload_box").hide();
        $("#upload_select_box").show();
        $("#video_uploader").css("opacity", 0).animate({
            opacity: 1
        }, 500)
    });
    new vlUploader({
        file: video,
        progress: function(p) {
            p = Math.round(p * 10000) / 100;
            $("#video_progress_in").css("width", p + "%");
            $("#video_progress_in").html(p + "%");
            $("#upload_status").removeClass("error").html("Uploading...");
            $(document).prop('title', "(" + parseInt(p) + "%) " + page_title)
        },
        complete: function() {
            $("#video_progress_in").css("width", "100%").html("100%");
            $("#upload_status").html("Upload complete!");
            $(document).prop('title', "(100%) " + page_title)
        },
        error: function(e, fatal) {
            $("#upload_status").addClass("error").html(e);
            $(document).prop('title', "(!) " + page_title)
        },
        start: function(url, thumb) {
            $("#vurl").html(url);
            $("#save_upload_changes_button").show();
            $("#custom_thumb_button").parent().show();
            $("#custom_thumb_button").attr("src", thumb + "?v=" + video_thumb)
        }
    })
}

function upload() {
    var video = _("selectedFile").files[0];
    var video_size = video.size / 1024 / 1024 / 1024;
    var extensions = ["flv", "mp4", "wmv", "avi", "mov", "m4v", "mpg", "mpeg", "webm", "mov", "mkv", "3gp"];
    var extension = video.name.toLowerCase().split(".");
    extension = extension[extension.length - 1];
    if (extensions.indexOf(extension) == -1) {
        alert("Video Extension not allowed!");
        return
    }
    if (video_size <= 2.01 && video.name.length < 100 && isEmptyOrSpaces(video.name) == !1) {
        var video_title = video.name.replace(/\.[^/.]+$/, "");
        _("video_title").value = video_title;
        $("#video_title_header").html(video_title);
        $("#old_upload_box").css("display", "none");
        $("#upload_select_box").css("display", "block");
        var formdata = new FormData();
        formdata.append("title", video_title);
        if (window.XMLHttpRequest) {
            var ajax = new XMLHttpRequest()
        } else if (window.ActiveXObject) {
            var ajax = new ActiveXObject("Microsoft.XMLHTTP")
        }
        ajax.addEventListener("load", completeHandler, !1);
        ajax.open("POST", "/ajax/uploader_url.php");
        ajax.send(formdata)
    } else {
        if (video_size > 2.01) {
            alert("Video File is too big!" + video_size)
        } else {
            alert("File name is too long!")
        }
    }
}

function move_hor(box, box2) {
    $("#" + box).toggleClass("hddn");
    $("#" + box2).toggleClass("hddn");
    $.ajax({
        type: "POST",
        url: "/ajax/move_hor_module",
        data: {
            Module: box
        }
    })
}

function completeHandler(e) {
    var r = e.target.responseText;
    if (r.indexOf("error") == 0) {
        alert("Error!")
    } else {
        $("#vurl").html(r);
        upload2()
    }
}

function upload2() {
    var video = _("selectedFile").files[0];
    var formdata = new FormData();
    formdata.append("video", video);
    formdata.append("url", $("#vurl").html());
    if (window.XMLHttpRequest) {
        var ajax = new XMLHttpRequest()
    } else if (window.ActiveXObject) {
        var ajax = new ActiveXObject("Microsoft.XMLHTTP")
    }
    ajax.upload.onprogress = function(e) {
        var percent = Math.round((e.loaded / e.total) * 100) + "%";
        _("video_progress_in").style.width = percent;
        _("video_progress_in").innerHTML = percent
    }
    ajax.addEventListener("error", on_error, !1);
    ajax.addEventListener("abort", on_abort, !1);
    ajax.addEventListener("load", completeUpload, !1);
    ajax.open("POST", "/ajax/vluploader.php");
    ajax.send(formdata)
}

function switch_partner(to) {
    switch (to) {
        case "overview":
            $('#pa_1').addClass("pa_sel");
            $('#pa_2').removeClass("pa_sel");
            $('#pa_3').removeClass("pa_sel");
            $('#partner_main').removeClass("hddn");
            $('#partner_benefits').addClass("hddn");
            $('#partner_qualifications').addClass("hddn");
            break;
        case "benefits":
            $('#pa_1').removeClass("pa_sel");
            $('#pa_2').addClass("pa_sel");
            $('#pa_3').removeClass("pa_sel");
            $('#partner_main').addClass("hddn");
            $('#partner_benefits').removeClass("hddn");
            $('#partner_qualifications').addClass("hddn");
            break;
        case "qualifications":
            $('#pa_1').removeClass("pa_sel");
            $('#pa_2').removeClass("pa_sel");
            $('#pa_3').addClass("pa_sel");
            $('#partner_main').addClass("hddn");
            $('#partner_benefits').addClass("hddn");
            $('#partner_qualifications').removeClass("hddn");
            break
    }
}

function completeUpload(e) {
    var r = e.target.responseText;
    if (r.indexOf("error") == 0) {
        alert("Error: " + r.split("||")[1])
    } else {
        alert("Video got successfully uploaded!")
    }
}

function on_error(e) {
    alert("Upload Failed!")
}

function feature_video(url) {
    $.ajax({
        type: "POST",
        url: "/ajax/feature_video",
        data: {
            url: url
        },
        success: function(output) {
            if (output == "1") {
                $("#feature_video").html("Remove Feature")
            } else {
                $("#feature_video").html("Feature Video")
            }
        }
    })
}

function on_abort(e) {
    alert("Upload Aborted!")
}

function save_video_changes() {
    var category = $("#video_category option:selected").val();
    var title = $("#video_title").val();
    var description = $("#video_description").val();
    var tags = $("#video_tags").val();
    var URL = $("#vurl").html();
    var Privacy = $("#privacy").val();
    var Schedule = $("#schedule_up").val();
    $.ajax({
        type: "POST",
        url: "/ajax/save_upload_changes",
        data: {
            video_title: title,
            video_description: description,
            video_tags: tags,
            video_category: category,
            privacy: Privacy,
            schedule: Schedule,
            url: URL
        },
        success: function(output) {
            alert("Information succesfully updated!")
        }
    })
}

function showstars(stars) {
    var i = 0;
    for (x = 0; x < stars; x++) {
        i++;
        document.getElementById(i).src = '/img/full_star.gif'
    }
}

function removestars(stars) {
    var i = 0;
    for (x = 0; x < stars; x++) {
        i++;
        document.getElementById(i).src = '/img/no_star.gif'
    }
}

function commentf() {
    window.scrollTo(0, 920);
    var comment_form = _('cm_form');
    comment_form.focus()
}

function latest_video() {
    var formdata = new FormData();
    if (window.XMLHttpRequest) {
        var ajax = new XMLHttpRequest()
    } else if (window.ActiveXObject) {
        var ajax = new ActiveXObject("Microsoft.XMLHTTP")
    }
    ajax.addEventListener("load", lv_on_complete, !1);
    ajax.open("POST", "/ajax/newest_video");
    ajax.send(formdata)
}

function lv_on_complete(e) {
    if (e.target.responseText !== "") {
        _("st_em").value = "/watch?v=" + e.target.responseText
    } else {
        alert("You don't have any videos!")
    }
}

function show_commentbox(user) {
    _("cmt_loc").innerHTML = "<form action='/ajax/df/ch_comment' method='POST'><input type='hidden' value='" + user + "' name='channel'><textarea id='ch_comment' rows='5' cols='50' maxlength='500' name='comment'></textarea><br><input type='submit' value='Post Comment'></form>";
    var comment_form = _("ch_comment");
    comment_form.focus()
}

function d_cc(comment) {
    var formdata = new FormData();
    if (window.XMLHttpRequest) {
        var ajax = new XMLHttpRequest()
    } else if (window.ActiveXObject) {
        var ajax = new ActiveXObject("Microsoft.XMLHTTP")
    }
    formdata.append("c_c_id", comment);
    ajax.addEventListener("load", d_cc_on_complete, !1);
    ajax.open("POST", "/ajax/delete_ch_comment");
    ajax.send(formdata)
}

function d_cc_on_complete(e) {
    var Array = JSON.parse(e.target.responseText);
    if (Array.new_com == !0) {
        var HTML = '<div class="ch_cmt" id="c_' + Array.id + '">';
        if (Array.can_delete == !0) {
            HTML += '<a href="javascript:void(0)" onclick="d_cc(' + Array.id + ')" class="cd">Delete</a>'
        }
        HTML += '<img src="' + Array.avatar + '.jpg" class="avt" width="90" height="71">';
        HTML += '<div><a href="/user/' + Array.by_user + '">' + Array.by_user + '</a> | <strong>' + Array.date + '</strong><div class="cmt_msg">' + Array.comment + '</div></div></div>';
        _("ch_cmt_sct").innerHTML += HTML
        _("c_" + Array.old_id).outerHTML = ""
    } else {
        _("c_" + Array.old_id).outerHTML = ""
    }
    _("ch_nm").innerHTML = _("ch_nm").innerText - 1
}

function add_ft_channel() {
    var channel = _("channel_add").value;
    if (channel.length > 1) {
        var formdata = new FormData();
        if (window.XMLHttpRequest) {
            var ajax = new XMLHttpRequest()
        } else if (window.ActiveXObject) {
            var ajax = new ActiveXObject("Microsoft.XMLHTTP")
        }
        formdata.append("user", channel);
        ajax.addEventListener("load", add_ft_channel_succ, !1);
        ajax.open("POST", "/ajax/add_ft_channel");
        ajax.send(formdata)
    } else {
        alert("You must type in a valid channel!")
    }
}

function add_ft_channel_succ(e) {
    if (e.target.responseText.length > 5) {
        var User = JSON.parse(e.target.responseText);
        _("fc").innerHTML = _("fc").innerHTML + '<div class="fc_sct"> <a href="/user/' + User.username + '">' + User.username + '</a><img src="' + User.avatar + '" class="avt" width="50" height="50"><br>Videos: ' + User.videos + '<br>Video Views: ' + User.video_views + '<br>Subscribers: ' + User.subscribers + ' </div>'
    } else {
        if (e.target.responseText == "u_d") {
            alert("User doesn't exist!")
        } else if (e.target.responseText == "u_m") {
            alert("You can have at most 8 featured channels!")
        } else if (e.target.responseText == "u_e") {
            alert("You already have this channel featured!")
        }
    }
}

function remove_ft(channel) {
    if (channel.length > 1) {
        var formdata = new FormData();
        if (window.XMLHttpRequest) {
            var ajax = new XMLHttpRequest()
        } else if (window.ActiveXObject) {
            var ajax = new ActiveXObject("Microsoft.XMLHTTP")
        }
        formdata.append("user", channel);
        ajax.open("POST", "/ajax/delete_ft_channel");
        ajax.send(formdata);
        _("fc_" + channel).outerHTML = ""
    }
}

function post_bulletin() {
    var bulletin = document.getElementById("bulletin").value;
    var bulletin = document.getElementById("bulletin2").value;
    var username = _("us").innerText;
    if ((bulletin.length > 0 && bulletin.length < 501) || (bulletin2.length > 0 && bulletin2.length < 501)) {
        _("bulletin").value = "";
        var formdata = new FormData();
        if (window.XMLHttpRequest) {
            var ajax = new XMLHttpRequest()
        } else if (window.ActiveXObject) {
            var ajax = new ActiveXObject("Microsoft.XMLHTTP")
        }
        formdata.append("bulletin", bulletin);
        ajax.open("POST", "/ajax/post_bulletin");
        ajax.send(formdata);
        if (bulletin.length > 0) {
            if (_("no_ra")) {
                _("no_ra").outerHTML = ""
            }
            _("ra_in").innerHTML = '<tr><td valign="top" width="18.5"><img src="/img/ra1.png"></td><td><strong>' + username + '</strong> ' + bulletin + ' <span>(1 second ago)</span></td> </tr>' + _("ra_in").innerHTML
        } else {
            if (_("no_ra2")) {
                _("no_ra2").outerHTML = ""
            }
            _("ra_in2").innerHTML = '<tr><td valign="top" width="18.5"><img src="/img/ra1.png"></td><td><strong>' + username + '</strong> ' + bulletin + ' <span>(1 second ago)</span></td> </tr>' + _("ra_in").innerHTML
        }
    } else {
        alert("Bulletins can't be empty!")
    }
}

function delete_bulletin(id) {
    _("b_" + id).outerHTML = "";
    var formdata = new FormData();
    if (window.XMLHttpRequest) {
        var ajax = new XMLHttpRequest()
    } else if (window.ActiveXObject) {
        var ajax = new ActiveXObject("Microsoft.XMLHTTP")
    }
    formdata.append("bulletin", id);
    ajax.open("POST", "/ajax/delete_bulletin");
    ajax.send(formdata)
}

function filter_box() {
    _("filter_box").style.display = "block"
}

function fpreview() {
    var num = _("filter_type").value;
    _('f_demo').src = "/img/filters/filter" + num + ".jpg"
}

function sh_in(inbox) {
    if (_(inbox).style.display == "none") {
        _(inbox).style.display = "block";
        var formdata = new FormData();
        if (window.XMLHttpRequest) {
            var ajax = new XMLHttpRequest()
        } else if (window.ActiveXObject) {
            var ajax = new ActiveXObject("Microsoft.XMLHTTP")
        }
        formdata.append("id", inbox);
        ajax.open("POST", "/ajax/view_inbox");
        ajax.send(formdata)
    } else {
        _(inbox).style.display = "none"
    }
}

function delete_background() {
    _("bg_delete").disabled = !0;
    var formdata = new FormData();
    if (window.XMLHttpRequest) {
        var ajax = new XMLHttpRequest()
    } else if (window.ActiveXObject) {
        var ajax = new ActiveXObject("Microsoft.XMLHTTP")
    }
    formdata.append("bg", "ar");
    ajax.addEventListener("load", bg_del_comp, !1);
    ajax.open("POST", "/ajax/delete_background");
    ajax.send(formdata)
}

function bg_del_comp() {
    _("bg_upload").style.display = "block";
    _("bg_delete").style.display = "none"
}

$(".sub_button").click(function() {
    var user = $(".sub_button").attr("user");
    fetch(`/api/user/subscribe?to=${user}`).then(resp => { return resp.json() }).then(data => {
        if(data.status == 1) {
            if(data.message == "Unsubscribed successfully") {
                $(".sub_button").text("Subscribe");
            } else {
                $(".sub_button").text("Unsubscribe");
            }
        } else alert(data.message);
    });
});

function user_exists(user) {
    $.ajax({
        type: "POST",
        url: "/ajax/user_exists",
        data: {
            user: user
        },
        success: function(output) {
            if (output == "true") {
                document.getElementById("reg_submit").disabled = !0;
                $("#user_exists").css("display", "block");
                $("#user_exists").html("<strong>" + user + "</strong> is already in use!")
            } else {
                document.getElementById("reg_submit").disabled = !1;
                $("#user_exists").css("display", "none");
                $("#user_exists").html("")
            }
        }
    })
}
current_page = 0;

function wn(type) {
    if (type == "w_sh") {
        $("#w_sh").attr("src", "/img/shhd1.png")
    } else if (type == "w_fv") {
        $("#w_fv").attr("src", "/img/hehd1.png")
    } else if (type == "w_pl") {
        $("#w_pl").attr("src", "/img/plhd1.png")
    } else if (type == "w_fl") {
        $("#w_fl").attr("src", "/img/flhd1.png")
    }
}

function wl(type) {
    if (type == "w_sh" && current_page !== 0) {
        $("#w_sh").attr("src", "/img/shhd0.png")
    } else if (type == "w_fv" && current_page !== 1) {
        $("#w_fv").attr("src", "/img/hehd0.png")
    } else if (type == "w_pl" && current_page !== 2) {
        $("#w_pl").attr("src", "/img/plhd0.png")
    } else if (type == "w_fl" && current_page !== 3) {
        $("#w_fl").attr("src", "/img/flhd0.png")
    }
}

function wc(type) {
    if (type == "w_sh" && current_page !== 0) {
        current_page = 0;
        $("#w_sh").attr("src", "/img/shhd1.png");
        $("#w_fv").attr("src", "/img/hehd0.png");
        $("#w_pl").attr("src", "/img/plhd0.png");
        $("#w_fl").attr("src", "/img/flhd0.png");
        $("#w_sel").css("left", "84px");
        $("#w_sh_cnt").removeClass("hddn");
        $("#w_fv_cnt").addClass("hddn");
        $("#w_pl_cnt").addClass("hddn");
        $("#w_fl_cnt").addClass("hddn")
    } else if (type == "w_fv" && current_page !== 1) {
        current_page = 1;
        $("#w_fv").attr("src", "/img/hehd1.png");
        $("#w_sh").attr("src", "/img/shhd0.png");
        $("#w_pl").attr("src", "/img/plhd0.png");
        $("#w_fl").attr("src", "/img/flhd0.png");
        $("#w_sel").css("left", "241px");
        $("#w_sh_cnt").addClass("hddn");
        $("#w_fv_cnt").removeClass("hddn");
        $("#w_pl_cnt").addClass("hddn");
        $("#w_fl_cnt").addClass("hddn")
    } else if (type == "w_pl" && current_page !== 2) {
        current_page = 2;
        $("#w_pl").attr("src", "/img/plhd1.png");
        $("#w_sh").attr("src", "/img/shhd0.png");
        $("#w_fv").attr("src", "/img/hehd0.png");
        $("#w_fl").attr("src", "/img/flhd0.png");
        $("#w_sel").css("left", "401px");
        $("#w_sh_cnt").addClass("hddn");
        $("#w_fv_cnt").addClass("hddn");
        $("#w_pl_cnt").removeClass("hddn");
        $("#w_fl_cnt").addClass("hddn")
    } else if (type == "w_fl" && current_page !== 3) {
        current_page = 3;
        $("#w_fl").attr("src", "/img/flhd1.png");
        $("#w_pl").attr("src", "/img/plhd0.png");
        $("#w_fv").attr("src", "/img/hehd0.png");
        $("#w_sh").attr("src", "/img/shhd0.png");
        $("#w_sel").css("left", "561px");
        $("#w_sh_cnt").addClass("hddn");
        $("#w_fv_cnt").addClass("hddn");
        $("#w_pl_cnt").addClass("hddn");
        $("#w_fl_cnt").removeClass("hddn")
    }
}

function add_video_favorite(video) {
    $.ajax({
        type: "POST",
        url: "/ajax/favorite_video",
        data: {
            id: video
        },
        success: function(output) {
            if (output.response == "added") {
                $("#w_ff").html("The video has been successfully <strong>added</strong> to your favorites!")
            } else if (output.response == "removed") {
                $("#w_ff").html("The video has been successfully <strong>removed</strong> from your favorites!")
            }
        }
    })
}

function add_to_playlist(video) {
    var PURL = $("#watch_playlist option:selected").val();
    if (PURL != undefined) {
        $.ajax({
            type: "POST",
            url: "/ajax/add_to_playlist",
            data: {
                pid: PURL,
                id: video
            },
            success: function(output) {
                if (output.response == "success") {
                    $("#wnn").html("Video successfully added to playlist!")
                } else if (output.response == "already") {
                    alert("Video is already in this playlist!")
                }
            }
        })
    } else {
        alert("Please select a playlist!")
    }
}

function rate_video(video, rate) {
    if (rate == 1) {
        $("#ratings").html("Terrible...")
    } else if (rate == 2) {
        $("#ratings").html("Bad..")
    } else if (rate == 3) {
        $("#ratings").html("OK.")
    } else if (rate == 4) {
        $("#ratings").html("Good!")
    } else if (rate == 5) {
        $("#ratings").html("Very Good!")
    }
    $.ajax({
        type: "POST",
        url: "/ajax/rate_video",
        data: {
            r: rate,
            v: video
        }
    })
}
$("#w_com").click(function() {
    $("#w_com_sct").removeClass("hddn");
    if ($("#w_stats_sct").hasClass("hddn") == !1) {
        $("#w_stats_sct").addClass("hddn")
    }
    $("#w_stats").removeClass("big_sel");
    $("#w_com").addClass("big_sel")
});
$("#w_stats").click(function() {
    $("#w_stats_sct").removeClass("hddn");
    if ($("#w_com_sct").hasClass("hddn") == !1) {
        $("#w_com_sct").addClass("hddn")
    }
    $("#w_com").removeClass("big_sel");
    $("#w_stats").addClass("big_sel")
});
$(".u_sct").click(function() {
    if ($(this).next().css("display") == "block") {
        $(this).next().css("display", "none");
        $(this).children('img').first().attr("src", "/img/clp00.png")
    } else {
        $(this).next().css("display", "block");
        $(this).children('img').first().attr("src", "/img/clp11.png")
    }
});

function textCounter(e, s, n) {
    var t = document.getElementById(s);
    return e.value.length > n ? (e.value = e.value.substring(0, n), !1) : void(t.innerHTML = e.value.length)
}

function show_reply(id, is_owner, reply_to) {
    if ($("#wtr_" + id).length == 0) {
        $("#r_cmt_" + id).html('' + '<span style="display:block;margin-left:54px;margin-bottom:14px" id="wtr_' + id + '">' + '<textarea id="txt_' + id + '" placeholder="Reply to comment..." style="border: 1px solid #d5d5d5;outline:0;border-radius:4px;padding:3px;margin-top:7px;margin-bottom:4px;width:482px;height:64px" maxlength="1000"></textarea>' + '<div><button class="search_button" onclick="add_reply(' + id + ',' + is_owner + ',\'' + reply_to + '\')">Post Comment</button> <button class="search_button" onclick="show_reply(' + id + ')">Cancel</button></div>' + '' + '' + '' + '</span>');
        $("#r_cmt_" + id).find("textarea").focus();
        if (reply_to) $("#r_cmt_" + id).find("textarea").val("@" + reply_to + " ").focus()
    } else {
        $("#r_cmt_" + id).html('')
    }
}

function add_reply(id, is_owner, reply_to) {
    if ($("#txt_" + id).val().length > 2) {
        var Text = $("#txt_" + id).val();
        if (reply_to) {
            if (Text.indexOf("@" + reply_to + " ") == -1) {
                Text = "@" + reply_to + " " + Text
            }
        }
        show_reply(id);
        $.ajax({
            type: "POST",
            url: "/ajax/reply_comment",
            data: {
                comment_id: id,
                vl_comment: Text
            },
            success: function(output) {
                if (is_owner) {
                    var style = 'style="background:#fffcc2"'
                } else {
                    var style = ''
                }
                if (output.response == "success") {
                    $("#r_cmt_" + id).before('' + '<div class="wt_c_sct wt_r_sct" id="wt_' + output.id + '" op="true">' + '<div ' + style + '>' + '<a href="/user/' + output.by_user + '">' + output.by_user + '</a> <span>(1 second ago)</span>' + '<div>' + '<a href="javascript:void(0)" onclick="delete_wtc(' + output.id + ')">Delete</a>' + '</div>' + '</div>' + '<div>' + '<a href="/user/' + output.by_user + '"><img src="' + output.avatar + '" class="avt2 wp_avt" alt="' + output.by_user + '" width="41" height="41"></a>' + '<div>' + '<span>0</span>' + '<img src="/img/td0.png"><img src="/img/tu0.png">' + '</div>' + '<div style="width:442px">' + output.comment + '</div>' + '</div>' + '</div>' + '' + '' + '' + '')
                } else if (output.response == "block") {
                    alert("You cannot interact with this user!")
                } else {
                    alert("You cannot write the same comment twice!")
                }
            }
        })
    } else {
        alert("Your reply must have more than 2 characters!")
    }
}

function show_all_replies(id) {
    $.ajax({
        type: "POST",
        url: "/ajax/all_replies",
        data: {
            comment_id: id
        },
        success: function(output) {
            $("#sa_" + id).replaceWith(output)
        }
    })
}

function post_video_comment(url, is_owner) {
    var comment = document.getElementById("comment_textarea").value;
    document.getElementById("video_button").disabled = !0;
    document.getElementById("video_button").innerHTML = "Posting Comment...";
    if (comment.length > 0) {
        $("#no_video_comments").remove();
        $.ajax({
            type: "POST",
            url: "/ajax/df_comment",
            data: {
                video_url: url,
                vl_comment: comment
            },
            success: function(output) {
                if (output.response == "success") {
                    document.getElementById("comment_textarea").value = "";
                    document.getElementById("video_button").disabled = !1;
                    document.getElementById("video_button").innerHTML = "Post Comment";
                    document.getElementById("counter").innerHTML = "0";
                    if (is_owner) {
                        var style = 'style="background:#fffcc2"'
                    } else {
                        var style = ''
                    }
                    $("#video_comments_section").prepend('' + '<div class="wt_c_sct" id="wt_' + output.id + '">' + '<div ' + style + '>' + '<a href="/user/' + output.by_user + '">' + output.by_user + '</a> <span>(1 second ago)</span>' + '<div>' + '<a href="javascript:void(0)" onclick="show_reply(' + output.id + ',false,\'' + output.by_user + '\')">Reply</a><a href="javascript:void(0)" onclick="delete_wtc(' + output.id + ')" style="padding-left:9px;margin-left:9px;border-left:1px solid #7d7d7d">Delete</a>' + '</div>' + '</div>' + '<div>' + '<a href="/user/' + output.by_user + '"><img src="' + output.avatar + '" class="avt2 wp_avt" alt="' + output.by_user + '" width="41" height="41"></a>' + '<div>' + '<span>0</span>' + '<img src="/img/td0.png"><img src="/img/tu0.png">' + '</div>' + '<div>' + output.comment + '</div>' + '</div>' + '</div>' + '<div id="r_cmt_' + output.id + '"></div>' + '' + '' + '');
                    var Number = document.getElementById("cmt_num").innerText;
                    Number++;
                    $("#cmt_num").html(Number);
                    $('html, body').animate({
                        scrollTop: $("#cmt_num").offset().top
                    }, 400)
                } else if (output.response == "spam") {
                    alert("You cannot make 5 comments right after each other.");
                    document.getElementById("video_button").disabled = !1;
                    document.getElementById("video_button").innerHTML = "Post Comment"
                } else if (output.response == "spam2") {
                    alert("You cannot make the same comment on one video twice.");
                    document.getElementById("video_button").disabled = !1;
                    document.getElementById("video_button").innerHTML = "Post Comment";
                    document.getElementById("comment_textarea").value = ""
                }
            }
        })
    } else {
        alert("Your comment can't be empty!");
        document.getElementById("video_button").disabled = !1;
        document.getElementById("video_button").innerHTML = "Post Comment"
    }
}

function flag_video(url) {
    var flag = $("#flag_select option:selected").val();
    $.ajax({
        type: "POST",
        url: "/ajax/flag_video",
        data: {
            flag: flag,
            url: url
        },
        success: function(output) {
            if (output != "error") {
                $('#w_flag').html('<strong>Thank you for the report! We will take a look at it!</strong>')
            } else {
                $('#w_flag').html('<strong>You have already reported this video!</strong>')
            }
        }
    })
}

function delete_wtc(id) {
    if (confirm("Are you sure you want to delete this comment?")) {
        $("#wt_" + id).fadeOut(300, function() {
            $(this).remove()
        });
        $("div[op=" + id + "]").fadeOut(300, function() {
            $("div[op=" + id + "]").remove()
        });
        if (document.getElementById("wt_" + id).hasAttribute("op") == !1) {
            var Number = document.getElementById("cmt_num").innerText;
            Number--;
            $("#cmt_num").html(Number)
        }
        $.ajax({
            type: "POST",
            url: "/ajax/delete_video_comment",
            data: {
                vc_id: id
            }
        })
    }
}
$("#show_more").click(function() {
    $("#des_info").toggleClass("hddn");
    if ($("#des_text").css("max-height") == "84px") {
        $(this).html("less info");
        $("#des_text").css("max-height", "none");
        $("#des_text").css("webkit-line-clamp", "unset")
    } else {
        $(this).html("more info");
        $("#des_text").css("max-height", "84px");
        $("#des_text").css("webkit-line-clamp", "5")
    }
});

function wr(comment, type, thumps) {
    if (type == 1) {
        $(thumps).attr("onclick", "");
        $(thumps).prev().attr("onclick", "wr(" + comment + ",'0',this)");
        $(thumps).attr("src", "/img/tu1.png");
        $(thumps).prev().css("opacity", "");
        var Number = $(thumps).prev().prev().text();
        if ($(thumps).prev().attr("src") == "/img/td1.png") {
            Number++;
            Number++
        } else {
            Number++
        }
        $(thumps).prev().attr("src", "/img/td0.png");
        if (Number > 0) {
            $(thumps).prev().prev().css("color", "green")
        } else if (Number < 0) {
            $(thumps).prev().prev().css("color", "red")
        } else if (Number == 0) {
            $(thumps).prev().prev().css("color", "gray")
        }
        $(thumps).prev().prev().html(Number)
    } else if (type == 0) {
        $(thumps).attr("onclick", "");
        $(thumps).next().attr("onclick", "wr(" + comment + ",'1',this)");
        $(thumps).attr("src", "/img/td1.png");
        $(thumps).next().css("opacity", "");
        var Number = $(thumps).prev().text()
        if ($(thumps).next().attr("src") == "/img/tu1.png") {
            Number -= 2
        } else {
            Number -= 1
        }
        $(thumps).next().attr("src", "/img/tu0.png");
        if (Number > 0) {
            $(thumps).prev().css("color", "green")
        } else if (Number < 0) {
            $(thumps).prev().css("color", "red")
        } else if (Number == 0) {
            $(thumps).prev().css("color", "gray")
        }
        $(thumps).prev().html(Number)
    }
    $(thumps).css("opacity", "0.75");
    $.ajax({
        type: "POST",
        url: "/ajax/rate_video_comment",
        data: {
            id: comment,
            rate: type
        }
    })
}

function add_friend_in(username, id) {
    $.ajax({
        type: "POST",
        url: "/ajax/add_friend",
        data: {
            user: username
        },
        success: function(output) {
            $("#invite_" + username).remove();
            $("#i_" + id).remove();
            if ($(".in_sct").length == 0) {
                $(".inbox_seperation").parent().append('<tr><td colspan="4" align="center" style="font-size:19px;padding:55px">You don\'t seem to have any friend requests.</td></tr>')
                $("#in_pag").remove()
            }
        }
    })
}

function block_user(username) {
    $.ajax({
        type: "POST",
        url: "/ajax/block_user",
        data: {
            user: username
        },
        success: function(output) {
            if (output == 0) {
                $("#bu").html("Unblock User")
            } else {
                $("#bu").html("Block User")
            }
        }
    })
}

function accept_response(response) {
    $.ajax({
        type: "POST",
        url: "/ajax/accept_response",
        data: {
            response: response
        },
        success: function(output) {
            $("#rsp_" + response).remove();
            $("#i_" + response).remove();
            if ($(".in_sct").length == 0) {
                $(".inbox_seperation").parent().append('<tr><td colspan="4" align="center" style="font-size:19px;padding:55px">No one has submitted any responses to your videos.</td></tr>')
                $("#in_pag").remove()
            }
        }
    })
}

function deny_response(response) {
    $.ajax({
        type: "POST",
        url: "/ajax/deny_response",
        data: {
            response: response
        },
        success: function(output) {
            $("#rsp_" + response).remove();
            $("#i_" + response).remove();
            if ($(".in_sct").length == 0) {
                $(".inbox_seperation").parent().append('<tr><td colspan="4" align="center" style="font-size:19px;padding:55px">No one has submitted any responses to your videos.</td></tr>')
                $("#in_pag").remove()
            }
        }
    })
}

function deny_friend_in(username, id) {
    $.ajax({
        type: "POST",
        url: "/ajax/deny_friend",
        data: {
            user: username
        },
        success: function(output) {
            $("#invite_" + username).remove();
            $("#i_" + id).remove();
            if ($(".in_sct").length == 0) {
                $(".inbox_seperation").parent().append('<tr><td colspan="4" align="center" style="font-size:19px;padding:55px">You don\'t seem to have any friend requests.</td></tr>')
                $("#in_pag").remove()
            }
        }
    })
}
$(".in_sct").click(function() {
    var id = $(this).attr("inbox");
    $("#i_" + id).toggleClass("hddn");
    if ($(this).attr("seen") == 0 && $(this).attr("type") != "nt") {
        $(this).toggleClass("in_not");
        $(this).attr("seen", "1");
        if ($(this).attr("type") == "m") {
            $.ajax({
                url: "/ajax/inbox_actions",
                type: "post",
                data: {
                    action: "inblk_read",
                    selectedPM: [id],
                    ajax: !0
                }
            })
        } else {
            var formdata = new FormData();
            if (window.XMLHttpRequest) {
                var ajax = new XMLHttpRequest()
            } else if (window.ActiveXObject) {
                var ajax = new ActiveXObject("Microsoft.XMLHTTP")
            }
            formdata.append("id", $(this).attr("type") + "_" + id);
            ajax.open("POST", "/ajax/view_inbox");
            ajax.send(formdata)
        }
    }
});

function change_comment_inbox() {
    var type = $("#comment_filter option:selected").val();
    if (type == "all") {
        window.location = "/inbox?page=comments"
    } else if (type == "video") {
        window.location = "/inbox?page=comments&t=2"
    } else if (type == "channel") {
        window.location = "/inbox?page=comments&t=3"
    } else if (type == "mention") {
        window.location = "/inbox?page=comments&t=4"
    } else if (type == "reply") {
        window.location = "/inbox?page=comments&t=5"
    }
}
$(".irs_cancel").click(function() {
    var irs = $(this).parents(".inbox_reply_section");
    irs.removeClass("open");
    irs.find("textarea").val("")
});
$(".irs_delete").click(function() {
    if (confirm("Are you sure you want to delete this message?")) {
        var bt = $(this);
        var m_id = bt.parents(".in_message").attr("id");
        m_id = m_id.substr(2);
        var page = $("#inbox_page").val();
        bt.prop("disabled", !0);
        $.ajax({
            url: "/ajax/inbox_actions",
            type: "post",
            data: {
                selectedPM: [m_id],
                action: "inblk_del",
                ajax: !0,
                page: page
            },
            success: function(r) {
                if (r == 0) alert("An error has occurred while processing your request.");
                else return location.href = r
            },
            error: function() {
                alert("An error has occurred while processing your request.")
            },
            complete: function() {
                bt.prop("disabled", !1)
            }
        })
    }
});

function showBulk() {
    if ($("input[name='selectedPM[]']:checked").length) {
        $(".in_bulk").prop("disabled", !1)
    } else {
        $(".in_bulk").prop("disabled", !0)
    }
}
$("input[name='selectedPM[]']").click(function(e) {
    showBulk();
    e.stopPropagation()
});
$("#selectAllPM").click(function() {
    var c = $(this).prop("checked");
    var pms = $("input[name='selectedPM[]']");
    for (var i = 0; i < pms.length; i++) {
        pms.eq(i).prop("checked", c)
    }
    showBulk()
});
$(".in_bulk").click(function() {
    var id = $(this).attr("id");
    var bt = $(".in_bulk");
    if (id == "inblk_del") {
        if (!confirm("Are you sure you want to delete all these messages?")) {
            return
        }
    } else if (id == "inblkc_del") {
        if (!confirm("Are you sure you want to delete all these comments from your inbox?")) {
            return
        }
    } else if (id == "inblkr_accept") {
        if (!confirm("Are you sure you want to accept all these video responses?")) {
            return
        }
    } else if (id == "inblkr_decline") {
        if (!confirm("Are you sure you want to decline all these video responses?")) {
            return
        }
    } else if (id == "inblki_accept") {
        if (!confirm("Are you sure you want to accept all these invitations?")) {
            return
        }
    } else if (id == "inblki_decline") {
        if (!confirm("Are you sure you want to decline all these invitations?")) {
            return
        }
    }
    bt.prop("disabled", !0);
    $("#inblk_action").val(id);
    $("#inblk_form")[0].submit()
});
$("a.irs_reply").click(function() {
    var bt = $(this).children("button");
    if (bt.prop("disabled")) return !1;
    var irs = $(this).parents(".inbox_reply_section");
    if (!irs.hasClass("open")) {
        irs.addClass("open");
        irs.find("textarea").focus()
    } else {
        var txt = irs.find("textarea").val();
        var su = irs.find(".irs_subject").val();
        var tu = irs.find(".irs_user").val();
        var page = $("#inbox_page").val();
        if (txt == "") {
            alert("Please, fill out the message body before sending a message.");
            return !1
        }
        bt.prop("disabled", !0);
        $.ajax({
            url: "/ajax/inbox_actions",
            type: "post",
            data: {
                to_user: tu,
                subject: su,
                message: txt,
                action: "send",
                page: page
            },
            success: function(r) {
                if (r == 0) alert("An error has occurred while processing your request.");
                else location.href = r
            },
            error: function() {
                alert("An error has occurred while processing your request.")
            },
            complete: function() {
                bt.prop("disabled", !1)
            }
        })
    }
    return !1
});

function move_up(id) {
    var next = $("#" + id).prev().attr('id');
    if (next != "mod_selector" && next != "home_congrats") {
        $("#" + id).swapWith("#" + next);
        var p_cookie = getCookie("po");
        if (p_cookie == "") {
            p_cookie = "0=s,1=r,2=b,3=f,4=m"
        }
        var e1 = id.charAt(0);
        var e2 = next.charAt(0);
        p_cookie = p_cookie.replace(e1, e2);
        p_cookie = p_cookie.replace(e2, e1);
        setCookie("po", p_cookie, 31)
    }
}

function close_achievement() {
    var type = $("#home_congrats").attr("type");
    $("#home_congrats").remove();
    $.ajax({
        url: "/ajax/close_achievement",
        type: "post",
        data: {
            type: type
        },
        success: function(r) {
            if (r.length > 0) {
                $("#mod_selector").after(r)
            }
        }
    })
}

function move_down(id) {
    var prev = $("#" + id).next().attr('id');
    if (prev != undefined) {
        $("#" + id).swapWith("#" + prev);
        var p_cookie = getCookie("po");
        if (p_cookie == "") {
            p_cookie = "0=s,1=r,2=b,3=f,4=m"
        }
        var e1 = id.charAt(0);
        var e2 = prev.charAt(0);
        p_cookie = p_cookie.replace(e2, e1);
        p_cookie = p_cookie.replace(e1, e2);
        setCookie("po", p_cookie, 31)
    }
}
jQuery.fn.swapWith = function(to) {
    return this.each(function() {
        var copy_to = $(to).clone(!0);
        var copy_from = $(this).clone(!0);
        $(to).replaceWith(copy_from);
        $(this).replaceWith(copy_to)
    })
};

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1)
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length)
        }
    }
    return ""
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/"
}

// Show JS player if present
$(document).ready(function() {
    var jsPlayerContainer = document.getElementById("vtbl_pl");
    if(jsPlayerContainer)
        jsPlayerContainer.style.display = "inline-block";
});