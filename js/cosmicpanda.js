function show_more(e, t, a) {
    (document.getElementById("show_more").disabled = !0),
        (document.getElementById("show_more").innerHTML = "Loading..."),
        $.ajax({
            type: "POST",
            url: "/ajax/cosmic_show_more",
            data: { type: e, page: t, user: a },
            success: function (e) {
                (document.getElementById("show_more").outerHTML = ""), $(".cosmic_featured_videos").append(e);
            },
        });
}
function show_more_comments(e, t) {
    (document.getElementById("show_more_comments").disabled = !0),
        (document.getElementById("show_more_comments").innerHTML = "Loading..."),
        $.ajax({
            type: "POST",
            url: "/ajax/cosmic_show_more",
            data: { type: "comments", page: e, user: t },
            success: function (e) {
                (document.getElementById("show_more_comments").outerHTML = ""), $("#cosmic_channel_comments").append(e);
            },
        });
}
function post_cosmic_bulletin() {
    var e = $("#cosmic_textarea").val();
    e.length > 0
        ? ($("#no_recent_activity").remove(),
          $("#cosmic_textarea").val(""),
          $.ajax({
              type: "POST",
              url: "/ajax/post_cosmic_bulletin",
              data: { bulletin: e },
              success: function (e) {
                  $(".cosmic_recent").prepend(e);
              },
          }))
        : alert("Bulletins can't be empty!");
}
function delete_cosmic_bulletin(e) {
    $("#cb_" + e).remove(),
        0 == $(".cosmic_recent").html().trim().length &&
            $(".cosmic_recent").html('<span id="no_recent_activity" style="margin-top:40px;display:block;color: #808080;text-align: center;font-size:20px;">This user doesn\'t have any activity!</span>');
    var t = new FormData();
    if (window.XMLHttpRequest) a = new XMLHttpRequest();
    else if (window.ActiveXObject) var a = new ActiveXObject("Microsoft.XMLHTTP");
    t.append("bulletin", e), a.open("POST", "/ajax/delete_bulletin"), a.send(t);
}
function cosmic_bg(e) {
    $("#gbg").css("background-color", "#" + e, 100), $("#bg_color").val("#" + e);
}
function cosmic_remove_ft(e) {
    $("#fc_" + e).remove();
    var t = $("#featured_channels_small_input").val();
    (t = t.toLowerCase().replace(e.toLowerCase(), "")), $("#featured_channels_small_input").val(t);
}
function cosmic_remove_pl(e) {
    $("#pl_" + e).remove();
    var t = $("#playlists_small_input").val();
    (t = t.toLowerCase().replace(e.toLowerCase(), "")), $("#playlists_small_input").val(t);
}
function cosmic_add_channel() {
    var e = $("#ft_channel_name").val(),
        t = $("#featured_channels_small_input").val();
    $("#featured_channels_small > div").length < 8
        ? e.length > 0 &&
          (t.toLowerCase().indexOf(e + ",".toLowerCase()) >= 0
              ? alert("You have already added this channel to your featured channels")
              : $.ajax({
                    type: "POST",
                    url: "/ajax/user_exists",
                    data: { user: e },
                    success: function (a) {
                        "false" != a
                            ? ($("#ft_channel_name").val(""),
                              $("#featured_channels_small_input").val(t + a + ","),
                              $("#featured_channels_small").append(
                                  '<div id="fc_' +
                                      a +
                                      '" style="overflow:hidden;background:white;border-radius:3px;padding:4px;margin-top:11px"><div style="float:left;color:black;font-weight:bold">' +
                                      e +
                                      '</div><div style="float:right"><a href="javascript:void(0)" onclick="cosmic_remove_ft(\'' +
                                      a +
                                      "')\">Remove</a></div></div>"
                              ))
                            : alert("This user doesn't exist!");
                    },
                }))
        : alert("You can only have up to 8 channels in your featured channels box!");
}
function cosmic_add_playlist() {
    var e = $("#select_playlist option:selected").val(),
        t = $("#select_playlist option:selected").html(),
        a = $("#playlists_small_input").val();
    $("#featured_playlists_small > div").length < 3
        ? a.toLowerCase().indexOf(e.toLowerCase()) >= 0
            ? alert("You have already added this playlist to your featured playlists!")
            : ($("#playlists_small_input").val(a + e + ","),
              $("#featured_playlists_small").append(
                  '<div id="pl_' +
                      e +
                      '" style="overflow:hidden;background:white;border-radius:3px;padding:4px;margin-top:11px"><div style="float:left;color:black;font-weight:bold">' +
                      t +
                      '</div><div style="float:right"><a href="javascript:void(0)" onclick="cosmic_remove_pl(\'' +
                      e +
                      "')\">Remove</a></div></div>"
              ))
        : alert("You can only feature up to 3 playlists!");
}
function delete_background_new() {
    document.getElementById("bg_delete").disabled = !0;
    var e = new FormData();
    if (window.XMLHttpRequest) t = new XMLHttpRequest();
    else if (window.ActiveXObject) var t = new ActiveXObject("Microsoft.XMLHTTP");
    e.append("bg", "ar"), t.addEventListener("load", bg_del_comp, !1), t.open("POST", "/ajax/delete_background"), t.send(e);
}
function post_channel_comment() {
    var e = $("#cosmic_textarea").val(),
        t = $("#cm_usn").html();
    e.length > 1
        ? ((document.getElementById("cosmic_comment_post").disabled = !0),
          $("#cosmic_textarea").val(""),
          $.ajax({
              type: "POST",
              url: "/ajax/post_channel_comment",
              data: { comment: e, on_channel: t },
              success: function (e) {
                  (document.getElementById("cosmic_comment_post").disabled = !1),
                      $("#no_comments").remove(),
                      $("#cosmic_channel_comments").prepend(
                          '<div class="cosmic_comment" id="cc_' +
                              e.id +
                              '"><div><div><img src="' +
                              e.avatar +
                              '" width="26" height="26" class="avt2 " alt="' +
                              e.by_user +
                              '"></div> <div><a href="/user/' +
                              e.by_user +
                              '">' +
                              e.by_user +
                              '</a> posted a comment <span>1 second ago</span></div></div><a href="javascript:void(0)" class="cosmic_delete" onclick="delete_comment(' +
                              e.id +
                              ')">Delete</a><div><div>' +
                              e.comment +
                              "</div></div></div>"
                      );
              },
          }))
        : alert("Your comment must be at least 2 characters long!");
}
function cosmic_subscribe(e) {
    $.ajax({
        type: "POST",
        url: "/ajax/subscribe",
        data: { user: e },
        success: function (e) {
            '<div id="sub-icon"></div>Subscribe' == $(".cosmic_sub").html()
                ? ($(".cosmic_sub").html('<div id="sub-icon"></div>Unsubscribe'), $(".cosmic_sub").addClass("cosmic_subbed"))
                : ($(".cosmic_sub").html('<div id="sub-icon"></div>Subscribe'), $(".cosmic_sub").removeClass("cosmic_subbed"));
        },
    });
}
function delete_comment(e) {
    $("#cc_" + e).remove(), $.ajax({ type: "POST", url: "/ajax/delete_ch_comment", data: { c_c_id: e }, success: function (e) {} });
}
function add_friend(e) {
    var t = $("#aaf").html(),
        a = e;
    if ("Cancel Invite" == t) {
        if (0 == confirm("Are you sure you want to cancel the friend invite?")) return !1;
    } else if ("Unfriend" == t && 0 == confirm("Are you sure you want to remove this channel as a friend?")) return !1;
    $.ajax({
        type: "POST",
        url: "/ajax/add_friend",
        data: { user: a },
        success: function (e) {
            "0" == e.response ? $("#aaf").html("Add Friend") : "1" == e.response ? $("#aaf").html("Unfriend") : "2" == e.response ? $("#aaf").html("Add Friend") : "3" == e.response ? $("#aaf").html("Cancel Invite") : alert(e.response);
        },
    });
}
