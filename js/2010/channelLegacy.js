bind = function (fn, self, var_args) {
    var boundArgs = fn.boundArgs_;
    if (arguments.length > 2) {
        var args = Array.prototype.slice.call(arguments, 2);
        if (boundArgs) {
            args.unshift.apply(args, boundArgs);
        }
        boundArgs = args;
    }
    self = fn.boundSelf_ || self;
    fn = fn.boundFn_ || fn;
    var newfn;
    if (boundArgs) {
        newfn = function () {
            var args = Array.prototype.slice.call(arguments);
            args.unshift.apply(args, boundArgs);
            return fn.apply(self, args);
        };
    } else {
        newfn = function () {
            return fn.apply(self, arguments);
        };
    }
    newfn.boundArgs_ = boundArgs;
    newfn.boundSelf_ = self;
    newfn.boundFn_ = fn;
    return newfn;
};
Function.prototype.bind = function (self, var_args) {
    if (arguments.length > 1) {
        var args = Array.prototype.slice.call(arguments, 1);
        args.unshift(this, self);
        return bind.apply(null, args);
    } else {
        return bind(this, self);
    }
};
Function.prototype.inherits = function (parentCtor) {
    inherits(this, parentCtor);
};
inherits = function (childCtor, parentCtor) {
    function tempCtor() {}
    tempCtor.prototype = parentCtor.prototype;
    childCtor.superClass_ = parentCtor.prototype;
    childCtor.prototype = new tempCtor();
    childCtor.prototype.constructor = childCtor;
};
var goog = window.goog ? window.goog : {};
window.goog = goog;
goog.typeOf = function (value) {
    var s = typeof value;
    if (s == "object") {
        if (value) {
            if (
                typeof value.length == "number" &&
                typeof value.splice == "function" &&
                typeof value.propertyIsEnumerable == "function" &&
                !value.propertyIsEnumerable("length")
            ) {
                return "array";
            }
        } else {
            return "null";
        }
    }
    return s;
};
function htmlEscape(str) {
    var result = str;
    result = result.replace(/&/g, "&amp;");
    result = result.replace(/</g, "&lt;");
    result = result.replace(/>/g, "&gt;");
    return result;
}
(function () {
    var Thread = {};
    window.Thread = Thread;
    var queue = [];
    var timeout = null;
    var tasks_length = 0;
    var named_tasks = {};
    var start_time = 0;
    var done_callback = null;
    function start() {
        timeout = window.setTimeout(process, 0);
        start_time = 0;
    }
    var MAX_TIME_PER_CYCLE = 100;
    function process() {
        if (!start_time) {
            start_time = new Date().getTime();
        }
        timeout = null;
        if (queue.length == 0) {
            tasks_length = 0;
            if (done_callback) {
                done_callback();
            }
            return;
        }
        var task = queue.shift();
        if (task.name) {
            if (named_tasks[task.name]) {
                task = named_tasks[task.name];
                named_tasks[task.name] = null;
            } else {
                return process();
            }
        }
        try {
            task.func.apply(task.obj ? task.obj : task.func, task.args);
        } catch (e) {
            start();
            throw e;
            return;
        }
        if (new Date().getTime() - start_time < MAX_TIME_PER_CYCLE) {
            return process();
        }
        start();
    }
    Thread.queue = function (func, args, obj, opt_name) {
        tasks_length++;
        var task = { func: func, args: args, obj: obj, name: opt_name };
        queue.push(task);
        if (opt_name) {
            named_tasks[opt_name] = task;
        }
        if (!timeout) {
            start();
        }
    };
    Thread.busy = function () {
        return queue.length > 0;
    };
    Thread.bind = function (func, opt_name) {
        var out_func = function () {
            var t = this;
            var args = [];
            for (var i = 0; i < arguments.length; i++) {
                args.push(arguments[i]);
            }
            Thread.queue(func, args, t, opt_name);
        };
        return out_func;
    };
    Thread.progress = function () {
        return tasks_length ? 1 - queue.length / tasks_length : 0;
    };
    Thread.tasks_remaining = function () {
        return queue.length;
    };
    Thread.set_done_callback = function (callback) {
        done_callback = callback;
    };
})();
function Iter(obj) {
    var str = Object.prototype.toString.apply(obj);
    if (str === "[object Array]" || str === "[object NodeList]" || obj instanceof Array) {
        return new ArrayIterator(obj);
    }
    return new IteratorBase(obj);
}
function IteratorBase(obj) {
    this.obj = obj;
}
IteratorBase.prototype.each = function (callback) {
    for (var i in this.obj) {
        if (this.obj.hasOwnProperty(i)) {
            callback(this.obj[i], i);
        }
    }
};
IteratorBase.prototype.merge = function (obj) {
    for (var i in obj) {
        if (obj.hasOwnProperty(i)) {
            this.obj[i] = obj[i];
        }
    }
};
IteratorBase.prototype.collect = function (callback) {
    var results = [];
    this.each(function (v, i) {
        var r = callback(v, i);
        r != undefined && r != null && results.push(r);
    });
    return results;
};
function ArrayIterator(obj) {
    IteratorBase.call(this, obj);
}
ArrayIterator.prototype = new IteratorBase();
ArrayIterator.prototype.constructor = ArrayIterator;
ArrayIterator.prototype.each = function (callback) {
    var len = this.obj.length;
    for (var i = 0; i < len; ++i) {
        callback(this.obj[i], i);
    }
};
(function () {
    window.updateEllipses = function (opt_el) {
        if (navigator.userAgent.indexOf("Firefox") == -1) {
            return;
        }
        if (!opt_el.getElementsByTagName) {
            opt_el = null;
        }
        var els = goog.dom.getElementsByTagNameAndClass("div", "ellipsis", opt_el);
        for (var i = els.length - 1; i >= 0; i--) {
            setupEllipsis(els[i]);
        }
    };
    function setupEllipsis(el) {
        if (!el.__span) {
            var dots = document.createElement("div");
            dots.appendChild(document.createTextNode("..."));
            dots.style.display = "none";
            el.appendChild(dots);
            if (el.className) {
                dots.className = el.className.replace("ellipsis", "ellipsis-dots");
            }
            var ds = dots.style;
            el.__dotstyle = ds;
            el.__span = el.getElementsByTagName("span")[0] || el.getElementsByTagName("a")[0];
            el.addEventListener(
                "overflow",
                function (e) {
                    handleEllipsis(el);
                },
                false
            );
            el.addEventListener(
                "underflow",
                function (e) {
                    handleEllipsis(el);
                },
                false
            );
        }
        handleEllipsis(el);
    }
    function handleEllipsis(el) {
        window.setTimeout(function () {
            if (el.clientWidth == 0) return;
            el.__dotstyle.display = el.__span.scrollWidth >= el.clientWidth ? "block" : "none";
        }, 5);
    }
    function getStyle(el, styleProp) {
        var y = null;
        if (el.currentStyle) {
            y = el.currentStyle[styleProp];
        } else if (window.getComputedStyle) {
            var computed = document.defaultView.getComputedStyle(el, "");
            var hyphen = toHyphen(styleProp);
            if (computed && computed.getPropertyValue(hyphen)) {
                y = computed.getPropertyValue(hyphen);
            }
        }
        return y;
    }
    function toHyphen(property) {
        if (property.indexOf("-") > -1) {
            return property;
        }
        var converted = "";
        var len = property.length;
        for (var i = 0; i < len; i++) {
            if (property.charAt(i) == property.charAt(i).toUpperCase()) {
                converted = converted + "-" + property.charAt(i).toLowerCase();
            } else {
                converted = converted + property.charAt(i);
            }
        }
        return converted;
    }
    window.initEllipsis = function () {
        var sTextOverFlow = getStyle(document.body, "textOverflow");
        if (typeof sTextOverFlow == "string" || !document.getBoxObjectFor) {
            return;
        }
        window.setTimeout(window.updateEllipses, 5);
    };
    yt.pubsub.subscribe("init", window.initEllipsis);
})();
(function () {
    var YT_cache = function (opt_max_age) {
        this.cache_ = new Object();
        this.timestamp_ = new Object();
        if (!opt_max_age || opt_max_age < 0) {
            opt_max_age = -1;
        }
        this.expiration_diff_ = opt_max_age;
    };
    window.YT_cache = YT_cache;
    YT_cache.prototype.put = function (key, value) {
        if (key == undefined || value == undefined) {
            return null;
        }
        this.cache_[key] = value;
        this.timestamp_[key] = new Date();
    };
    YT_cache.prototype.get = function (key, opt_default_value) {
        if (this.contains(key)) {
            return this.cache_[key];
        } else {
            return opt_default_value;
        }
    };
    YT_cache.prototype.invalidate = function () {
        this.cache_ = new Object();
    };
    YT_cache.prototype.contains = function (key) {
        var contains_key = this.cache_[key] != null && this.cache_[key] != undefined;
        return contains_key && !this.isExpired(key);
    };
    YT_cache.prototype.isExpired = function (key) {
        var currDate = new Date();
        if (this.expiration_diff_ != -1 && currDate - this.timestamp_[key] > this.expiration_diff_) {
            return true;
        }
        return false;
    };
})();
(function () {
    var AjaxQueue = function (url, opt_session_param, opt_get) {
        this.messages_ = [];
        this.logging_info_ = [];
        this.url_ = url;
        this.post_ = !opt_get;
        this.session_param_ = opt_session_param;
        this.last_delivery_ = new Date().getTime();
        this.response_handlers_ = {};
        this.outstanding_delivery_ = null;
        this.idle_send_messages_timeout_ = null;
        this.delivery_timeout_ = null;
        this.error_handler_ = null;
        this.add_unload_handler();
    };
    window.AjaxQueue = AjaxQueue;
    var MAX_DELAY_BEFORE_DELIVERY = 15000;
    var MAX_UNSENT_MESSAGES = 10;
    var MAGIC_PREFIX = "while(1);";
    AjaxQueue.prototype.add_unload_handler = function () {
        var old_unload = function () {};
        if (window.onunload) {
            old_unload = window.onunload;
        }
        var aq = this;
        window.onunload = function () {
            if (aq.messages_ && aq.messages_.length > 0) {
                aq.deliver_messages();
            }
            old_unload();
        };
    };
    AjaxQueue.prototype.should_send_messages_ = function () {
        var time_to_go = new Date().getTime() - this.last_delivery_ > MAX_DELAY_BEFORE_DELIVERY;
        var too_many = this.messages_.length > MAX_UNSENT_MESSAGES;
        return time_to_go || too_many;
    };
    AjaxQueue.prototype.set_timeout_ = function () {
        if (this.should_send_messages_()) {
            this.deliver_messages();
        } else {
            this.idle_send_messages_timeout_ = window.setTimeout(
                function () {
                    this.set_timeout_();
                }.bind(this),
                5000
            );
        }
    };
    AjaxQueue.prototype.send_message = function (message, type, opt_critical, opt_logging_info) {
        var real_message = { type: type, request: message };
        this.messages_.push(real_message);
        if (opt_logging_info) {
            this.logging_info_.push(opt_logging_info);
        }
        if (opt_critical || this.should_send_messages_()) {
            this.deliver_messages();
        } else if (!this.idle_send_messages_timeout_) {
            this.set_timeout_();
        }
    };
    AjaxQueue.prototype.quick_send = function (message, type, callback, opt_logging_info) {
        var real_message = { type: message.method ? message.method : type, request: message };
        var successCallback = function (response) {
            var responseText = response.responseText.substring(MAGIC_PREFIX.length);
            if (this.check_redirect_or_reload(responseText)) {
                return;
            }
            var responses;
            try {
                responses = goog.json.unsafeParse(responseText);
            } catch (e) {
                this.report_error(status, yt.getMsg("BAD_RESPONSE"));
                return;
            }
            for (var i = 0; i < responses.length; i++) {
                var response = responses[i];
                if (response.type == type) {
                    callback(response);
                } else if (response.code === "ERROR") {
                    console.error("Server error:", response.error);
                }
            }
        }.bind(this);
        var error_callback = function (response) {
            this.report_error(status, yt.getMsg("UNABLE_TO_CONTACT_SERVER"));
        }.bind(this);
        this.contact_server(
            [real_message],
            successCallback,
            error_callback,
            opt_logging_info ? [opt_logging_info] : null
        );
    };
    AjaxQueue.prototype.register_handler = function (type, handler) {
        this.response_handlers_[type] = handler;
    };
    AjaxQueue.prototype.register_error_handler = function (handler) {
        this.error_handler_ = handler;
    };
    AjaxQueue.prototype.report_error = function (http_code, error_message) {
        if (this.error_handler_) {
            this.error_handler_(http_code, error_message);
        } else {
            alert(error_message);
        }
    };
    AjaxQueue.prototype.handle_message_responses_ = function (responses) {
        if (!responses) return;
        for (var i in responses) {
            var response = responses[i];
            if (response.type && this.response_handlers_[response.type]) {
                this.response_handlers_[response.type](response);
            }
        }
    };
    var REDIRECT_COMMAND = "LOGIN_REDIRECT:";
    var RELOAD_COMMAND = "RELOAD_PAGE:";
    AjaxQueue.prototype.redirect_login = function (url) {
        url = "/login?next=" + encodeURIComponent(url);
        window.location.href = url;
    };
    AjaxQueue.prototype.reload_page = function (url) {
        var href = window.location.href;
        if (href.indexOf(url) != href.length - url.length) {
            window.location.href = url;
        } else {
            window.location.reload(true);
        }
    };
    AjaxQueue.prototype.check_redirect_or_reload = function (responseText) {
        if (responseText.indexOf(REDIRECT_COMMAND) == 0) {
            this.redirect_login(responseText.substring(REDIRECT_COMMAND.length));
            return true;
        }
        if (responseText.indexOf(RELOAD_COMMAND) == 0) {
            this.reload_page(responseText.substring(RELOAD_COMMAND.length));
            return true;
        }
        return false;
    };
    AjaxQueue.prototype.deliver_messages = function () {
        if (this.outstanding_delivery_) {
            this.redelivery_ = true;
            return;
        }
        this.redelivery_ = false;
        if (this.idle_send_messages_timeout_) {
            window.clearTimeout(this.idle_send_messages_timeout_);
            this.idle_send_messages_timeout_ = null;
        }
        var callback = function (response) {
            var responseText = response.responseText.substring(MAGIC_PREFIX.length);
            if (this.check_redirect_or_reload(responseText)) {
                return;
            }
            var responses;
            try {
                responses = goog.json.unsafeParse(responseText);
            } catch (e) {
                this.report_error(status, yt.getMsg("BAD_RESPONSE"));
                return;
            }
            this.handle_message_responses_(responses);
            if (this.redelivery_) {
                window.setTimeout(
                    function () {
                        this.deliver_messages();
                    }.bind(this),
                    10
                );
            }
            this.outstanding_delivery_ = null;
            this.last_delivery_ = new Date().getTime();
        }.bind(this);
        var error_callback = function (response) {
            if (this.outstanding_delivery_) {
                this.messages_ = this.outstanding_delivery_.concat(this.messages_);
            }
            this.report_error(status, yt.getMsg("UNABLE_TO_CONTACT_SERVER"));
            this.outstanding_delivery_ = null;
            this.last_delivery_ = new Date().getTime();
        }.bind(this);
        this.contact_server(this.messages_, callback, error_callback, this.logging_info_);
        this.outstanding_delivery_ = this.messages_;
        this.messages_ = [];
        this.logging_info_ = [];
    };
    AjaxQueue.prototype.contact_server = function (messages, callback, error_callback, opt_logging_info) {
        var message_param = "messages=" + encodeURIComponent(goog.json.serialize(messages));
        var url = this.url_;
        if (opt_logging_info && opt_logging_info.length > 0) {
            url += "&" + opt_logging_info.join("&");
        }
        if (this.post_) {
            if (this.session_param_) {
                yt.net.ajax.sendRequest(url, {
                    postBody: this.session_param_ + "&" + message_param,
                    onComplete: callback,
                    onException: error_callback,
                });
            } else {
                yt.net.ajax.sendRequest(url, {
                    postBody: message_param,
                    onComplete: callback,
                    onException: error_callback,
                });
            }
        } else {
            yt.net.ajax.sendRequest(url + "&" + message_param, {
                method: "GET",
                onComplete: callback,
                onException: error_callback,
            });
        }
    };
})();
(function () {
    window.goog = window.goog ? window.goog : {};
    var goog = window.goog;
    goog.json = {};
    goog.json.isValid_ = function (s) {
        if (s == "") {
            return false;
        }
        s = s.replace(/"(\\.|[^"\\])*"/g, "");
        return s == "" || !/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(s);
    };
    goog.json.parse = function (s) {
        s = String(s);
        if (typeof s.parseJSON == "function") {
            return s.parseJSON();
        }
        if (goog.json.isValid_(s)) {
            try {
                return eval("(" + s + ")");
            } catch (ex) {}
        }
        throw Error("Invalid JSON string: " + s);
    };
    goog.json.unsafeParse = function (s) {
        return eval("(" + s + ")");
    };
    goog.json.serializer_ = null;
    goog.json.serialize = function (object) {
        if (!goog.json.serializer_) {
            goog.json.serializer_ = new goog.json.Serializer();
        }
        return goog.json.serializer_.serialize(object);
    };
    goog.json.Serializer = function () {};
    goog.json.Serializer.prototype.serialize = function (object) {
        if (object != null && typeof object.toJSONString == "function") {
            return object.toJSONString();
        }
        var sb = [];
        this.serialize_(object, sb);
        return sb.join("");
    };
    goog.json.Serializer.prototype.serialize_ = function (object, sb) {
        switch (goog.typeOf(object)) {
            case "string":
                this.serializeString_(object, sb);
                break;
            case "number":
                this.serializeNumber_(object, sb);
                break;
            case "boolean":
                sb.push(object);
                break;
            case "undefined":
                sb.push("null");
                break;
            case "null":
                sb.push("null");
                break;
            case "array":
                this.serializeArray_(object, sb);
                break;
            case "object":
                this.serializeObject_(object, sb);
                break;
            default:
                throw Error("Unknown type: " + typeof object);
        }
    };
    goog.json.Serializer.charToJsonCharCache_ = {
        '"': '\\"',
        "\\": "\\\\",
        "/": "\\/",
        "\b": "\\b",
        "\f": "\\f",
        "\n": "\\n",
        "\r": "\\r",
        "\t": "\\t",
        "\x0B": "\\u000b",
    };
    goog.json.Serializer.prototype.serializeString_ = function (s, sb) {
        sb.push(
            '"',
            s.replace(/[\\\"\x00-\x1f\x80-\uffff]/g, function (c) {
                if (c in goog.json.Serializer.charToJsonCharCache_) {
                    return goog.json.Serializer.charToJsonCharCache_[c];
                }
                var cc = c.charCodeAt(0);
                var rv = "\\u";
                if (cc < 16) {
                    rv += "000";
                } else if (cc < 256) {
                    rv += "00";
                } else if (cc < 4096) {
                    rv += "0";
                }
                return (goog.json.Serializer.charToJsonCharCache_[c] = rv + cc.toString(16));
            }),
            '"'
        );
    };
    goog.json.Serializer.prototype.serializeNumber_ = function (n, sb) {
        sb.push(isFinite(n) && !isNaN(n) ? n : "null");
    };
    goog.json.Serializer.prototype.serializeArray_ = function (arr, sb) {
        var l = arr.length;
        sb.push("[");
        var sep = "";
        for (var i = 0; i < l; i++) {
            sb.push(sep);
            this.serialize_(arr[i], sb);
            sep = ",";
        }
        sb.push("]");
    };
    goog.json.Serializer.prototype.serializeObject_ = function (obj, sb) {
        sb.push("{");
        var sep = "";
        for (var key in obj) {
            sb.push(sep);
            this.serializeString_(key, sb);
            sb.push(":");
            this.serialize_(obj[key], sb);
            sep = ",";
        }
        sb.push("}");
    };
})();
window.History = {};
(function () {
    var self = this.History;
    var iframeURL = "/blank.html?";
    var once = true;
    self.initialize = function () {
        if (!isIE_) return;
        firstLoad_ = false;
        fireOnNewListener_ = PageLoaded();
    };
    self.addListener = function (callback) {
        listener_ = callback;
        if (fireOnNewListener_) {
            FireHistoryEvent(currentLocation_);
            fireOnNewListener_ = false;
        }
    };
    self.add = function (newLocation, historyData) {
        var addImpl = function () {
            if (currentWaitTime_ > 0) currentWaitTime_ -= waitTime_;
            newLocation = RemoveHash(newLocation);
            var idCheck = _gel(newLocation);
            if (idCheck == undefined || idCheck == null) {
                self.historyStorage_.put(newLocation, historyData);
                ignoreLocationChange_ = true;
                ieAtomicLocationChange_ = true;
                currentLocation_ = newLocation;
                window.location.hash = newLocation || "#";
                if (isIE_) iframe_.src = iframeURL + newLocation;
                ieAtomicLocationChange_ = false;
            }
        };
        window.setTimeout(addImpl, currentWaitTime_);
        currentWaitTime_ += waitTime_;
    };
    self.isFirstLoad = function () {
        return firstLoad_ == true;
    };
    var SECRET_DATA_KEY = "_secret_data";
    self.setSecretData = function (data) {
        self.historyStorage_.put(SECRET_DATA_KEY, data);
    };
    self.getSecretData = function () {
        return self.historyStorage_.get(SECRET_DATA_KEY);
    };
    function GetCurrentLocation() {
        return RemoveHash(window.location.hash);
    }
    var currentLocation_ = null;
    var listener_ = null;
    var iframe_ = null;
    var ignoreLocationChange_ = null;
    var waitTime_ = 200;
    var currentWaitTime_ = 0;
    var fireOnNewListener_ = null;
    var firstLoad_ = null;
    var ieAtomicLocationChange_ = null;
    var isIE_;
    function Create() {
        isIE_ = document.all && navigator.userAgent.toLowerCase().indexOf("msie") != -1;
        var initialHash = GetCurrentLocation();
        currentLocation_ = initialHash;
        simpleBindEvent(window, "unload", function () {
            firstLoad_ = null;
        });
        if (!isIE_) {
            if (!(ignoreLocationChange_ = !PageLoaded())) fireOnNewListener_ = true;
        } else {
            document.write(
                "<iframe style='border:0px;width:1px;" +
                    "height:1px;position:absolute;bottom:0px;" +
                    "right:0px;visibility:visible' " +
                    "name=HistoryFrame id=HistoryFrame " +
                    'src="' +
                    iframeURL +
                    encodeURIComponent(initialHash) +
                    '"></iframe>'
            );
            waitTime_ = 1000;
            ignoreLocationChange_ = true;
            iframe_ = _gel("HistoryFrame");
        }
        setInterval(function () {
            self.CheckLocation();
        }, 500);
    }
    function PageLoaded() {
        var result = true;
        var FLAG = "History_pageLoaded";
        if (!self.historyStorage_.hasKey(FLAG)) {
            self.historyStorage_.put(FLAG, true);
            firstLoad_ = true;
            result = false;
        }
        return result;
    }
    function FireHistoryEvent(newHash) {
        var historyData = self.historyStorage_.get(newHash);
        listener_.call(null, newHash, historyData);
    }
    self.CheckLocation = function () {
        if (!isIE_ && ignoreLocationChange_ == true) {
            ignoreLocationChange_ = false;
            return;
        }
        if (!isIE_ && ieAtomicLocationChange_ == true) {
            return;
        }
        var hash = GetCurrentLocation();
        if (hash == currentLocation_) return;
        ieAtomicLocationChange_ = true;
        if (isIE_) {
            var doc = iframe_.contentDocument || iframe_.contentWindow.document;
            var frHash = new String(doc.location.search);
            var qAt0 = frHash.charAt(0) == "?";
            if (frHash.length == 1 && qAt0) frHash = "";
            else if (frHash.length >= 2 && qAt0) frHash = frHash.substring(1);
            if (frHash != hash) {
                iframe_.src = iframeURL + hash;
            } else {
                return;
            }
        }
        currentLocation_ = hash;
        ieAtomicLocationChange_ = false;
        FireHistoryEvent(hash);
    };
    function RemoveHash(hashValue) {
        var result = hashValue;
        if (hashValue == null || hashValue == undefined) result = "";
        else {
            var c0h = hashValue.charAt(0) == "#";
            if (hashValue == "" || (hashValue.length == 1 && c0h)) result = "";
            else if (hashValue.length > 1 && c0h) result = hashValue.substring(1);
        }
        return result;
    }
    self.iframeLoaded = function (newLocation) {
        if (ignoreLocationChange_ == true) {
            ignoreLocationChange_ = false;
            return;
        }
        var hash = new String(newLocation.search);
        if (hash.charAt(0) == "?" && hash.length > 0) hash = hash.substring(1);
        window.location.hash = hash;
        FireHistoryEvent(hash);
    };
    (function () {
        var hsSelf = {};
        self.historyStorage_ = hsSelf;
        var storageHash_ = {};
        var hashLoaded_ = false;
        hsSelf.put = function (key, value) {
            if (hsSelf.isValidKey(key)) {
                storageHash_[key] = value;
                SaveHashTable();
            }
        };
        hsSelf.get = function (key) {
            var result = null;
            if (hsSelf.isValidKey(key)) {
                LoadHashTable();
                var value = storageHash_[key];
                if (value != undefined) result = value;
            }
            return result;
        };
        hsSelf.hasKey = function (key) {
            var result = false;
            if (hsSelf.isValidKey(key)) {
                LoadHashTable();
                if (typeof storageHash_[key] != "undefined") result = true;
            }
            return result;
        };
        hsSelf.isValidKey = function (key) {
            return typeof key == "string";
        };
        var storageField_ = null;
        hsSelf.init = function () {
            if (hsSelf.initialized_) return;
            var fieldName = "historyStorageField";
            storageField_ = _gel(fieldName);
            if (!storageField_) {
                var styleValue = " style='position:absolute;top:-1000px;left:-1000px'";
                document.write(
                    "<form id=historyStorageForm method=GET" +
                        styleValue +
                        "><textarea id=" +
                        fieldName +
                        " name=" +
                        fieldName +
                        styleValue +
                        " left=-1000px></textarea></form>"
                );
                storageField_ = _gel(fieldName);
            }
            hsSelf.initialized_ = true;
        };
        function LoadHashTable() {
            if (!hashLoaded_) {
                hsSelf.init();
                var serializedHashTable = storageField_.value;
                if (serializedHashTable != "" && serializedHashTable != null) {
                    storageHash_ = eval("(" + serializedHashTable + ")");
                }
                hashLoaded_ = true;
            }
        }
        hsSelf.stringify = function (arg) {
            var c,
                i,
                l,
                s = "",
                v;
            switch (typeof arg) {
                case "object":
                    if (arg) {
                        if (
                            arg instanceof Array ||
                            (typeof arg.join == "function" && typeof arg.reverse == "function")
                        ) {
                            for (i = 0; i < arg.length; ++i) {
                                v = hsSelf.stringify(arg[i]);
                                if (s) {
                                    s += ",";
                                }
                                s += v;
                            }
                            return "[" + s + "]";
                        } else if (typeof arg.toString != "undefined") {
                            for (i in arg) {
                                v = arg[i];
                                if (typeof v != "undefined" && typeof v != "function") {
                                    v = hsSelf.stringify(v);
                                    if (s) {
                                        s += ",";
                                    }
                                    s += hsSelf.stringify(i) + ":" + v;
                                }
                            }
                            return "{" + s + "}";
                        }
                    }
                    return "null";
                case "number":
                    return isFinite(arg) ? String(arg) : "null";
                case "string":
                    l = arg.length;
                    s = '"';
                    for (i = 0; i < l; i += 1) {
                        c = arg.charAt(i);
                        if (c >= " ") {
                            if (c == "\\" || c == '"') {
                                s += "\\";
                            }
                            s += c;
                        } else {
                            switch (c) {
                                case "\b":
                                    s += "\\b";
                                    break;
                                case "\f":
                                    s += "\\f";
                                    break;
                                case "\n":
                                    s += "\\n";
                                    break;
                                case "\r":
                                    s += "\\r";
                                    break;
                                case "\t":
                                    s += "\\t";
                                    break;
                                default:
                                    c = c.charCodeAt();
                                    s += "\\u00" + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
                                    break;
                            }
                        }
                    }
                    return s + '"';
                case "boolean":
                    return String(arg);
                default:
                    return "null";
            }
        };
        function SaveHashTable() {
            LoadHashTable();
            storageField_.value = hsSelf.stringify(storageHash_);
        }
    })();
    self.getStorage = function () {
        return self.historyStorage_;
    };
    Create();
    function simpleBindEvent(element, event, listener) {
        var onevent = "on" + event;
        if (element.addEventListener) {
            element.addEventListener(event, listener, false);
        } else if (element.attachEvent) {
            element.attachEvent(onevent, listener);
        } else {
            var old = element[onevent];
            element[onevent] = function () {
                var rv1 = old.apply(this, arguments);
                var rv2 = listener.apply(this, arguments);
                return rv1 == undefined ? rv2 : rv2 == undefined ? rv1 : rv2 && rv1;
            };
        }
    }
})();
function $(id) {
    return document.getElementById(id);
}
function $ce(name, attributes, children) {
    var att,
        el = document.createElement(name);
    if (attributes) {
        Iter(attributes).each(function (attValue, attName) {
            switch (attName) {
                case "id":
                    el.id = attValue;
                    break;
                case "c":
                    el.className = attValue;
                    break;
                default:
                    el.setAttribute(attName, attValue);
                    break;
            }
        });
    }
    if (typeof children == "string") {
        el.innerHTML = children;
    } else if (children instanceof Array) {
        Iter(children).each(function (node) {
            el.appendChild(node);
        });
    } else if (children) {
        el.appendChild(children);
    }
    return el;
}
function $ctn(text) {
    return document.createTextNode(text);
}
function $children(node) {
    var c = [];
    for (var i = 0; i < node.childNodes.length; i++) {
        c.push(node.childNodes[i]);
    }
    return c;
}
function removeChildren(node) {
    if (node)
        while (node.firstChild) {
            node.removeChild(node.firstChild);
        }
}
function buildDom(html) {
    var d = document.createElement("div");
    d.innerHTML = html;
    return d;
}
function $id() {
    var counter = 0;
    $id = function () {
        return ++counter;
    };
    return counter;
}
function $listen(el, event, handler) {
    if (window.addEventListener) {
        el.addEventListener(event, handler, false);
    } else if (window.attachEvent) {
        el.attachEvent("on" + event, handler);
    }
}
function $find(node, text) {
    if (node.nodeValue && node.nodeValue.indexOf(text) != -1) {
        return node;
    }
    var cur = node.firstChild;
    while (cur) {
        var tmp;
        if ((tmp = $find(cur, text))) return tmp;
        cur = cur.nextSibling;
    }
}
(function () {
    var callbacks = [];
    var monitoring = false;
    var oldLocation = null;
    function resetLocation() {
        oldLocation = window.location.hash;
    }
    function checkLocation() {
        if (oldLocation == null) {
            resetLocation();
        }
        var newLocation = window.location.hash;
        if (oldLocation != newLocation) {
            Iter(callbacks).each(function (f) {
                f(oldLocation, newLocation);
            });
            oldLocation = newLocation;
        }
    }
    var timerStarted = false;
    function timerLoop() {
        timerStarted = true;
        checkLocation();
        setTimeout(timerLoop, 100);
    }
    function monitorLocation(callback) {
        if (callback) {
            callbacks.push(callback);
        }
        if (!timerStarted) {
            timerLoop();
        }
    }
    window.monitorLocation = monitorLocation;
    window.checkLocation = checkLocation;
    window.resetLocation = resetLocation;
})();
(function () {
    var ANIMATION_SLICE_MS = 10;
    var animations = [];
    var timerId;
    var sorted;
    function animationSorter(a, b) {
        if (a.startTime == b.startTime) {
            return a.property ? 0 : 1;
        }
        return a.startTime - b.startTime;
    }
    function defragmentAnimations() {
        var old = animations;
        animations = [];
        for (var i = 0; i < old.length; ++i) {
            if (old[i] != null) {
                animations.push(old[i]);
            }
        }
    }
    function animate() {
        sorted = false;
        if (timerId) {
            window.clearTimeout(timerId);
            timerId = null;
        }
        timerId = window.setTimeout(animationLoop, 0);
    }
    function animateNow() {
        sorted = false;
        if (timerId) {
            window.clearTimeout(timerId);
            timerId = null;
        }
        animationLoop();
    }
    function animationLoop() {
        var now = new Date().valueOf();
        var active = false;
        var defrag = false;
        if (!sorted) {
            animations.sort(animationSorter);
            sorted = true;
        }
        for (var i = 0; i < animations.length; ++i) {
            var ani = animations[i];
            try {
                if (ani.startTime > now) {
                    break;
                }
                if (ani.endTime <= now) {
                    if (ani.property) {
                        ani.object[ani.property] = ani.interpolator
                            ? ani.interpolator(ani.startValue, ani.targetValue, 1, ani.timeline)
                            : ani.targetValue;
                    } else if (typeof ani.targetValue == "function") {
                        ani.targetValue(ani.object, now);
                    }
                    animations[i] = null;
                    defrag = true;
                    continue;
                }
                var duration = ani.endTime - ani.startTime;
                if (ani.property) {
                    ani.object[ani.property] = ani.interpolator(
                        ani.startValue,
                        ani.targetValue,
                        (now - ani.startTime) / duration,
                        ani.timeline
                    );
                    active = true;
                }
            } catch (e) {
                animations[i] = null;
                defrag = true;
            }
        }
        if (defrag) {
            defragmentAnimations();
        }
        if (active) {
            timerId = window.setTimeout(animationLoop, ANIMATION_SLICE_MS);
        } else if (animations.length > 0) {
            timerId = window.setTimeout(animationLoop, animations[0].startTime - now);
        }
    }
    function animateProperty(
        element,
        object,
        property,
        startValue,
        targetValue,
        delay,
        duration,
        interpolator,
        timeline,
        opt_restore,
        opt_hold
    ) {
        var startTime = new Date().valueOf() + delay * 1000;
        var endTime = startTime + duration * 1000;
        var animationProperties = {
            element: element,
            object: object,
            property: property,
            startValue: startValue,
            targetValue: targetValue,
            startTime: startTime,
            endTime: endTime,
            interpolator: typeof interpolator == "function" ? interpolator : interpolators[interpolator],
            timeline: timelines[timeline],
        };
        animations.push(animationProperties);
        if (opt_restore) {
            opt_hold = opt_hold || 0;
            animations.push({
                element: element,
                object: object,
                property: property,
                startValue: null,
                targetValue: object[property],
                startTime: opt_hold * 1000 + endTime + 1,
                endTime: opt_hold * 1000 + endTime + 1,
            });
        }
        animate();
        return animationProperties;
    }
    function animatePropertyDiscrete(element, object, property, values, delay, duration, opt_restore, opt_hold) {
        function interpolator(start, end, position, timeline) {
            var pos = timeline(start, end, position);
            return values[Math.round(pos * (values.length - 1))];
        }
        animateProperty(
            element,
            object,
            property,
            0,
            1,
            delay,
            duration,
            interpolator,
            "linear",
            opt_restore,
            opt_hold
        );
    }
    function delaySetProperty(element, object, property, targetValue, delay, opt_restore, opt_hold) {
        animateProperty(
            element,
            object,
            property,
            targetValue,
            targetValue,
            delay,
            0,
            null,
            null,
            opt_restore,
            opt_hold
        );
    }
    function pad2(str) {
        if (str.length < 2) {
            return "0" + str;
        }
        return str;
    }
    function scheduleAction(element, object, callback, delay, opt_callback_cleanup, opt_hold) {
        animateProperty(element, object, null, null, callback, delay, 0);
        if (opt_hold) {
            var runTime = new Date().valueOf() + delay * 1000;
            var cleanupTime = runTime + opt_hold * 1000;
            animations.push({
                element: element,
                object: object,
                targetValue: opt_callback_cleanup,
                startTime: cleanupTime,
                endTime: cleanupTime,
            });
        }
        animate();
    }
    function repeatAction(element, object, callback, delay, interval, times) {
        var nextTime = new Date().valueOf() + delay * 1000;
        var i = 0;
        (function eachTime(object, now) {
            if (i > 0) {
                callback(i - 1, now < nextTime);
            }
            if (i < times) {
                animations.push({
                    element: element,
                    object: object,
                    startTime: nextTime,
                    endTime: nextTime,
                    targetValue: eachTime,
                });
                nextTime = nextTime + interval * 1000;
                animate();
            }
            i++;
        })();
    }
    function isAncestorOrSelf(old, young) {
        try {
            var cur = young;
            do {
                if (cur == old) {
                    return true;
                }
            } while ((cur = cur.parentNode));
            return false;
        } catch (e) {
            return false;
        }
    }
    function endAllAnimations() {
        var now = new Date().valueOf();
        for (var i = 0; i < animations.length; ++i) {
            var cur = animations[i];
            cur.startTime = now - 1;
            cur.endTime = now;
        }
        animateNow();
    }
    function endAnimations(element) {
        animateNow();
        var now = new Date().valueOf();
        for (var i = 0; i < animations.length; ++i) {
            var cur = animations[i];
            if (isAncestorOrSelf(element, cur.element)) {
                cur.startTime = now;
                cur.endTime = now;
            }
        }
        animateNow();
    }
    function parseRgb(rgb) {
        if (rgb.indexOf("rgb") == 0) {
            segs = rgb.split(/[^0-9]+/);
            return (
                "#" +
                pad2(parseInt(segs[1]).toString(16)) +
                pad2(parseInt(segs[2]).toString(16)) +
                pad2(parseInt(segs[3]).toString(16))
            );
        } else if (rgb.length == 4) {
            return "#" + rgb.charAt(1) + "0" + rgb.charAt(2) + "0" + rgb.charAt(3) + "0";
        } else {
            return rgb;
        }
    }
    function parsePx(px) {
        if (!px) return 0;
        if (typeof px == "number") return px;
        return parseInt(px.replace("px", ""));
    }
    var timelines = {
        linear: function (v0, v1, pos) {
            return v0 * (1 - pos) + v1 * pos;
        },
        decelerate: function (v0, v1, pos) {
            pos = 1 - (pos - 1) * (pos - 1);
            return v0 * (1 - pos) + v1 * pos;
        },
        sine: function (v0, v1, pos) {
            var rads = Math.PI / 2;
            var scomp = Math.sin(pos * rads);
            var ccomp = 1 - Math.sin(rads + pos * rads);
            var pos = ccomp * (1 - pos) + scomp * pos;
            return v0 * (1 - pos) + v1 * pos;
        },
    };
    var interpolators = {
        rgb3: function (color0, color1, position, timeline) {
            var i,
                result = "",
                digit0,
                digit1,
                newvalue,
                newdigit;
            for (i = 1; i < 4; ++i) {
                digit0 = parseInt(color0.charAt(i), 16);
                digit1 = parseInt(color1.charAt(i), 16);
                newvalue = Math.round(timeline(digit0 * 16, digit1 * 16, position));
                result += pad2(newvalue.toString(16));
            }
            return "#" + result;
        },
        rgb6: function (color0, color1, position, timeline) {
            var i,
                result = "",
                digit0,
                digit1,
                newvalue,
                newdigit;
            for (i = 0; i < 3; ++i) {
                digit0 = parseInt(color0.substr(i * 2 + 1, 2), 16);
                digit1 = parseInt(color1.substr(i * 2 + 1, 2), 16);
                newvalue = Math.round(timeline(digit0, digit1, position));
                result += pad2(newvalue.toString(16));
            }
            return "#" + result;
        },
        number: function (num0, num1, position, timeline) {
            return timeline(num0, num1, position);
        },
        integer: function (num0, num1, position, timeline) {
            return Math.round(timeline(num0, num1, position));
        },
        px: function (px0, px1, position, timeline) {
            return Math.round(timeline(parsePx(px0), parsePx(px1), position)) + "px";
        },
        filter: function (num0, num1, position, timeline) {
            return "alpha(opacity=" + timeline(num0 * 100, num1 * 100, position) + ")";
        },
    };
    window.scheduleAction = scheduleAction;
    window.repeatAction = repeatAction;
    window.animateProperty = animateProperty;
    window.animatePropertyDiscrete = animatePropertyDiscrete;
    window.endAnimations = endAnimations;
    window.endAllAnimations = endAllAnimations;
    window.delaySetProperty = delaySetProperty;
    window.parseRgb = parseRgb;
    window.parsePx = parsePx;
    window.animateProperty.interpolators = interpolators;
    window.animateProperty.timelines = timelines;
})();
function ani() {}
ani.DEFAULT_MESSAGE_DELAY = 10;
ani.DEFAULT_MESSAGE_FADEOUT = 2;
ani.DEFAULT_MESSAGE_SLIDE = 0.5;
ani.USE_OPACITY = 0;
ani.USE_FILTER = 1;
ani.TRANSPARENCY_METHOD = null;
function poller(interval, limit, callback) {
    if (limit > 0) {
        try {
            callback();
        } catch (e) {
            setTimeout(function () {
                poller(interval, limit - interval, callback);
            }, interval);
        }
    }
}
ani.isIE = !!eval("/*@cc_on!@*/false");
if (ani.isIE) {
    ani.TRANSPARENCY_METHOD = ani.USE_FILTER;
} else {
    ani.TRANSPARENCY_METHOD = ani.USE_OPACITY;
}
ani.fade = function (el, start, end, delay, duration, opt_restore, opt_restore_delay) {
    if (ani.TRANSPARENCY_METHOD == ani.USE_OPACITY) {
        animateProperty(
            el,
            el.style,
            "opacity",
            start,
            end,
            delay,
            duration,
            "number",
            "linear",
            opt_restore,
            opt_restore_delay
        );
    } else if (ani.TRANSPARENCY_METHOD == ani.USE_FILTER) {
        animateProperty(
            el,
            el.style,
            "filter",
            start,
            end,
            delay,
            duration,
            "filter",
            "linear",
            opt_restore,
            opt_restore_delay
        );
    }
};
ani.fadeDuringSlideUp = function (el, delay, duration) {
    ani.wrap(
        el,
        function (wrapper) {
            endAnimations(wrapper);
            animateProperty(
                wrapper,
                wrapper.style,
                "height",
                wrapper.offsetHeight + "px",
                "1px",
                delay,
                duration,
                "px",
                "sine",
                true
            );
            animateProperty(wrapper, wrapper.style, "opacity", "1.0", "0.0", delay, duration, "number", "sine", true);
            animateProperty(wrapper, wrapper.style, "filter", "1.0", "0.0", delay, duration, "filter", "sine", true);
            delaySetProperty(el, el.style, "display", "none", delay + duration);
        },
        delay,
        duration
    );
};
ani.fadeAndSlideUp = function (el, delay, fadeDuration, slideDuration) {
    var wrapper = ani.insertWrapper(el);
    wrapper.style.height = wrapper.offsetHeight + 10 + "px";
    wrapper.style.overflow = "hidden";
    wrapper.style.position = "relative";
    animateProperty(
        wrapper,
        wrapper.style,
        "height",
        wrapper.offsetHeight + "px",
        wrapper.offsetHeight + "px",
        delay,
        fadeDuration,
        "px",
        "linear"
    );
    ani.fade(wrapper, "1.0", "0.0", delay, fadeDuration);
    animateProperty(
        wrapper,
        wrapper.style,
        "height",
        wrapper.offsetHeight + "px",
        "1px",
        delay + fadeDuration,
        slideDuration,
        "px",
        "sine"
    );
    delaySetProperty(el, el.style, "display", "none", delay + fadeDuration + slideDuration);
    scheduleAction(wrapper, wrapper, ani.removeWrapper, delay + fadeDuration + slideDuration + 1);
};
ani.slideDownAndFadeIn = function (el, delay, fadeDuration, slideDuration) {
    ani.wrap(
        el,
        function (wrapper) {
            wrapper.style.visibility = "hidden";
            var height = ani.getDimensions(wrapper).height;
            animateProperty(wrapper, wrapper.style, "height", "1px", height + "px", delay, slideDuration, "px", "sine");
            animateProperty(
                wrapper,
                wrapper.style,
                "height",
                height + "px",
                height + "px",
                delay + slideDuration,
                fadeDuration,
                "px",
                "linear"
            );
            ani.fade(wrapper, 0, 1, delay + slideDuration, fadeDuration);
            wrapper.style.height = "1px";
            delaySetProperty(wrapper, wrapper.style, "visibility", "visible", delay + slideDuration);
            scheduleAction(wrapper, wrapper, ani.exciseNode, delay + fadeDuration + slideDuration);
        },
        delay,
        -1,
        10
    );
};
ani.ajaxSlideOpen = function (el, delay, duration, opt_start_height, opt_end_height) {
    var height = ani.getDimensions(el).height;
    if (!opt_end_height) {
        opt_end_height = height;
    }
    if (!opt_start_height) {
        opt_start_height = 1;
    }
    if (el.animation_property_margin) {
        var prop = el.animation_property_margin;
        prop.startTime = new Date().valueOf();
        prop.endTime = new Date().valueOf() + duration * 1000;
        prop.startValue = el.wrapper.style.marginTop;
        prop.targetValue = opt_end_height - height + "px";
        prop.timeline = window.animateProperty.timelines.decelerate;
        var prop_h = el.animation_property_height;
        prop_h.startTime = new Date().valueOf();
        prop_h.endTime = new Date().valueOf() + duration * 1000;
        prop_h.startValue = el.style.height;
        prop_h.targetValue = opt_end_height + "px";
        prop_h.timeline = window.animateProperty.timelines.decelerate;
    } else {
        ani.wrap(
            el,
            function (wrapper) {
                wrapper.style.height = opt_start_height + "px";
                el.wrapper = wrapper;
                el.animation_property_margin = animateProperty(
                    el,
                    el.style,
                    "marginTop",
                    opt_start_height - height + "px",
                    opt_end_height - height + "px",
                    delay,
                    duration,
                    "px",
                    "sine"
                );
                el.animation_property_height = animateProperty(
                    wrapper,
                    wrapper.style,
                    "height",
                    opt_start_height + "px",
                    opt_end_height + "px",
                    delay,
                    duration,
                    "px",
                    "sine"
                );
                delaySetProperty(el, el, "animation_property_margin", null, delay + duration);
                delaySetProperty(el, el.style, "marginTop", "0", delay + duration);
                delaySetProperty(el, el.style, "display", "block", delay);
            },
            delay,
            duration,
            0
        );
    }
};
ani.ajaxSlideSideways = function (el, delay, duration, opt_only_reveal, opt_start_width, opt_end_width) {
    var width = ani.getDimensions(el).width;
    if (!opt_end_width) {
        opt_end_width = width;
    }
    if (!opt_start_width) {
        opt_start_width = 1;
    }
    if (el.animation_property_margin) {
        var prop = el.animation_property_margin;
        prop.startTime = new Date().valueOf();
        prop.endTime = new Date().valueOf() + duration * 1000;
        prop.startValue = el.wrapper.style.marginTop;
        prop.targetValue = opt_end_width - width + "px";
        prop.timeline = window.animateProperty.timelines.decelerate;
        var prop_h = el.animation_property_width;
        prop_h.startTime = new Date().valueOf();
        prop_h.endTime = new Date().valueOf() + duration * 1000;
        prop_h.startValue = el.style.width;
        prop_h.targetValue = opt_end_width + "px";
        prop_h.timeline = window.animateProperty.timelines.decelerate;
    } else {
        ani.wrap(
            el,
            function (wrapper) {
                wrapper.style.width = opt_start_width + "px";
                el.wrapper = wrapper;
                if (!opt_only_reveal) {
                    el.animation_property_margin = animateProperty(
                        el,
                        el.style,
                        "marginLeft",
                        opt_start_width - width + "px",
                        opt_end_width - width + "px",
                        delay,
                        duration,
                        "px",
                        "sine"
                    );
                }
                el.animation_property_width = animateProperty(
                    wrapper,
                    wrapper.style,
                    "width",
                    opt_start_width + "px",
                    opt_end_width + "px",
                    delay,
                    duration,
                    "px",
                    "sine"
                );
                delaySetProperty(el, el, "animation_property_margin", null, delay + duration);
                delaySetProperty(el, el.style, "marginLeft", "0", delay + duration);
                delaySetProperty(el, el.style, "display", "block", delay);
            },
            delay,
            duration,
            0
        );
    }
};
ani.slideClosedBySpeed = function (el, pxPerSecond, callback) {
    var duration = el.offsetHeight / pxPerSecond;
    ani.wrap(
        el,
        function (wrapper) {
            var start_height = wrapper.offsetHeight;
            animateProperty(
                el,
                el.style,
                "marginTop",
                "0px",
                1 - start_height + "px",
                0,
                duration,
                "px",
                "linear",
                true,
                0
            );
            animateProperty(wrapper, wrapper.style, "height", start_height + "px", "1px", 0, duration, "px", "linear");
        },
        0,
        duration
    );
    scheduleAction(el, el, callback, duration);
};
ani.measureHeight = function (el) {
    var oldPosition = el.style.position;
    el.style.position = "absolute";
    el.style.visibility = "hidden";
    el.style.display = "block";
    var height = el.offsetHeight;
    el.style.display = "none";
    el.style.visibility = "visible";
    el.style.position = oldPosition;
    return height;
};
ani.slideOpenBySpeed = function (el, pxPerSecond, callback) {
    var end_height = ani.measureHeight(el);
    var duration = end_height / pxPerSecond;
    ani.wrap(
        el,
        function (wrapper) {
            wrapper.style.height = "0px";
            el.style.display = "block";
            animateProperty(el, el.style, "marginTop", -end_height + "px", "0px", 0, duration, "px", "linear", true, 0);
            animateProperty(wrapper, wrapper.style, "height", "0px", end_height + "px", 0, duration, "px", "linear");
        },
        0,
        duration
    );
    if (callback) scheduleAction(el, el, callback, duration);
};
ani.slideClosed = function (el, delay, duration) {
    ani.wrap(
        el,
        function (wrapper) {
            var start_height = wrapper.offsetHeight;
            animateProperty(
                el,
                el.style,
                "marginTop",
                "0px",
                1 - start_height + "px",
                delay,
                duration,
                "px",
                "sine",
                true
            );
            animateProperty(
                wrapper,
                wrapper.style,
                "height",
                start_height + "px",
                "1px",
                delay,
                duration,
                "px",
                "sine"
            );
            delaySetProperty(el, el.style, "display", "none", delay + duration, true);
            scheduleAction(
                wrapper,
                wrapper,
                function (div) {
                    div.parentNode.removeChild(div);
                },
                delay + duration
            );
        },
        delay,
        -1,
        0
    );
};
ani.wrap = function (el, callback, delay, duration, opt_extra_height) {
    var wrapper = ani.insertWrapper(el);
    opt_extra_height = opt_extra_height || 0;
    wrapper.style.height = wrapper.offsetHeight + opt_extra_height + "px";
    wrapper.style.overflow = "hidden";
    wrapper.style.position = "relative";
    callback(wrapper);
    function teardown(el) {
        ani.removeWrapper(wrapper);
    }
    if (duration > 0) {
        scheduleAction(el, el, null, delay, teardown, duration);
    }
};
ani.insertWrapper = function (el) {
    var wrapper = document.createElement("div");
    wrapper.style.margin = "0px";
    wrapper.style.padding = "0px";
    el.parentNode.insertBefore(wrapper, el);
    el.parentNode.removeChild(el);
    wrapper.appendChild(el);
    return wrapper;
};
ani.removeWrapper = function (wrapper) {
    var el = wrapper.firstChild;
    wrapper.removeChild(el);
    wrapper.parentNode.insertBefore(el, wrapper);
    wrapper.parentNode.removeChild(wrapper);
};
ani.exciseNode = function (el) {
    var par = el.parentNode;
    var node = el.firstChild;
    while ((node = el.firstChild)) {
        el.removeChild(node);
        par.insertBefore(node, el);
    }
    par.removeChild(el);
};
ani.getPosition = function (target) {
    var left = 0;
    var top = 0;
    do {
        left += target.offsetLeft;
        top += target.offsetTop;
    } while ((target = target.offsetParent));
    return { x: left, y: top };
};
ani.getDimensions = function (el) {
    var dimensions = {};
    ani.wrap(el, function (wrapper) {
        dimensions.width = wrapper.offsetWidth;
        dimensions.height = wrapper.offsetHeight;
        dimensions.top = wrapper.offsetTop;
        dimensions.left = wrapper.offsetLeft;
        ani.exciseNode(wrapper);
    });
    return dimensions;
};
ani.hideAndFloatClone = function (el, opt_duration) {
    var clone;
    ani.wrap(el, function (wrapper) {
        clone = wrapper.cloneNode(true);
        clone.style.left = wrapper.offsetLeft + "px";
        clone.style.top = wrapper.offsetTop + "px";
        clone.style.width = wrapper.offsetWidth + "px";
        clone.style.height = wrapper.offsetHeight + "px";
        ani.exciseNode(wrapper);
    });
    clone.firstChild.id = el.id + "-clone";
    clone.style.position = "absolute";
    el.style.visibility = "hidden";
    el.offsetParent.insertBefore(clone, el.offsetParent.firstChild);
    clone.style.zIndex = el.style.zIndex ? el.style.zIndex + 1 : "100";
    clone.style.clear = "both";
    if (opt_duration) {
        function teardown(el) {
            el.style.visibility = "visible";
            setTimeout(function () {
                removeNode(clone);
            }, 0);
        }
        scheduleAction(el, el, null, 0, teardown, opt_duration);
    }
    return clone;
};
ani.slideTo = function (el, start_top, start_left, end_top, end_left, duration, opt_use_clone) {
    var ani_obj = opt_use_clone ? ani.hideAndFloatClone(el, duration) : el;
    ani_obj.style.visibility = "visible";
    animateProperty(ani_obj, ani_obj.style, "top", start_top, end_top, 0, duration, "px", "decelerate");
    animateProperty(ani_obj, ani_obj.style, "left", start_left, end_left, 0, duration, "px", "decelerate");
};
ani.swapDivs = function (a, b, duration) {
    var a_clone = ani.hideAndFloatClone(a, duration + 0.25);
    var b_clone = ani.hideAndFloatClone(b, duration + 0.25);
    ani.fade(a_clone, 1, 0.5, 0, duration / 4);
    ani.fade(b_clone, 1, 0.5, 0, duration / 4);
    ani.fade(a_clone, 0.5, 1, (3 * duration) / 4, duration / 4);
    ani.fade(b_clone, 0.5, 1, (3 * duration) / 4, duration / 4);
    var b_next = b.nextSibling;
    a.parentNode.insertBefore(b, a.nextSibling);
    b.parentNode.insertBefore(a, b_next);
    var a_dim = ani.getDimensions(a);
    var b_dim = ani.getDimensions(b);
    if (a.offsetTop != b.offsetTop) {
        animateProperty(a_clone, a_clone.style, "top", a_clone.style.top, a_dim.top + "px", 0, duration, "px", "sine");
        animateProperty(b_clone, b_clone.style, "top", b_clone.style.top, b_dim.top + "px", 0, duration, "px", "sine");
    }
    if (a.offsetLeft != b.offsetLeft) {
        animateProperty(
            a_clone,
            a_clone.style,
            "left",
            a_clone.style.left,
            a_dim.left + "px",
            0,
            duration,
            "px",
            "sine"
        );
        animateProperty(
            b_clone,
            b_clone.style,
            "left",
            b_clone.style.left,
            b_dim.left + "px",
            0,
            duration,
            "px",
            "sine"
        );
    }
};
ani.crossfadeReveal = function (hide, show, reveal, speed, opt_height, opt_oncompleteCallback) {
    var w = hide.offsetWidth;
    var h = hide.offsetHeight;
    hide.style.position = "absolute";
    hide.style.width = w + "px";
    hide.style.height = h + "px";
    var origHeight = h;
    hide._origHeight = origHeight;
    var duration;
    var targetHeight;
    function animateWrapper(wrapper) {
        reveal.style.display = "block";
        targetHeight = opt_height ? opt_height : ani.getDimensions(reveal).height;
        duration = Math.min(Math.abs(targetHeight - origHeight) / speed, 2.0);
        ani.fade(hide, "1.0", "0.0", 0, duration);
        if (show) {
            ani.fade(show, "0.0", "1.0", 0, duration);
        }
        wrapper.style.overflow = "hidden";
        wrapper.style.height = origHeight;
        animateProperty(
            wrapper,
            wrapper.style,
            "height",
            origHeight + "px",
            targetHeight + "px",
            0,
            duration,
            "px",
            "decelerate"
        );
        scheduleAction(wrapper, wrapper, ani.exciseNode, duration);
        if (opt_oncompleteCallback) {
            scheduleAction(reveal, reveal, opt_oncompleteCallback, duration);
        }
    }
    ani.wrap(reveal, animateWrapper, 0, -1);
    return { height: targetHeight, duration: duration };
};
ani.crossfadeRevealUndo = function (hide, show, reveal, speed, opt_height, opt_oncompleteCallback) {
    var duration;
    var origHeight;
    var targetHeight;
    function animateWrapper(wrapper) {
        origHeight = hide._origHeight;
        targetHeight = opt_height ? opt_height : ani.getDimensions(reveal).height;
        wrapper.style.overflow = "hidden";
        wrapper.style.height = targetHeight;
        duration = Math.min(Math.abs(targetHeight - origHeight) / speed, 2.0);
        ani.fade(hide, "0.0", "1.0", 0, duration);
        if (show) {
            ani.fade(show, "1.0", "0.0", 0, duration);
        }
        delaySetProperty(reveal, reveal.style, "display", "none", duration - 0.1);
        delaySetProperty(hide, hide.style, "position", "static", duration);
        animateProperty(
            wrapper,
            wrapper.style,
            "height",
            targetHeight + "px",
            origHeight + "px",
            0,
            duration,
            "px",
            "decelerate"
        );
        scheduleAction(wrapper, wrapper, ani.exciseNode, duration);
        if (opt_oncompleteCallback) {
            scheduleAction(reveal, reveal, opt_oncompleteCallback, duration);
        }
    }
    ani.wrap(reveal, animateWrapper, 0, -1);
    return { height: targetHeight, duration: duration };
};
function removeNode(el) {
    el = _gel(el);
    if (el && el.parentNode) el.parentNode.removeChild(el);
}
ani.fadeAndMakeUnclickable = function (el, opt_opacity, opt_background) {
    var opacity = opt_opacity || 0.3;
    var background = opt_background || "white";
    var pos = ani.getPosition(el);
    var curtain = document.createElement("iframe");
    curtain.frameBorder = "0";
    curtain.scrolling = "no";
    curtain.style.position = "absolute";
    var border_size = el.clientLeft || 0;
    curtain.style.top = pos.y - border_size + "px";
    curtain.style.left = pos.x - border_size + "px";
    curtain.style.width = el.offsetWidth + border_size * 2 + "px";
    curtain.style.height = el.offsetHeight + border_size * 2 + "px";
    curtain.style.background = background;
    curtain.style.opacity = opacity;
    curtain.style.filter = "alpha(opacity=" + Math.round(opacity * 100) + ")";
    curtain.style.zIndex = el.style.zIndex ? el.style.zIndex + 1 : 101;
    curtain.style.border = "0";
    curtain.style.margin = "0";
    curtain.style.overflow = "hidden";
    return curtain;
};
ani.scrollUntilVisible = function (el, opt_duration) {
    if (!opt_duration) {
        opt_duration = 0.75;
    }
    var top = ani.getPosition(el).y;
    var bottom = top + ani.getDimensions(el).height;
    var docEl =
        document.body.scrollTop >= document.documentElement.scrollTop ? document.body : document.documentElement;
    var browserHeight = window.innerHeight ? window.innerHeight : docEl.clientHeight;
    if (self.pageYOffset) {
        var scrollTop = Math.max(self.pageYOffset, docEl.scrollTop);
    } else {
        var scrollTop = docEl.scrollTop;
    }
    var scrollBottom = scrollTop + browserHeight;
    var targetTop = -1;
    var FRIENDLY_PADDING = 10;
    if (top < scrollTop) {
        targetTop = top - FRIENDLY_PADDING;
    } else if (bottom > scrollBottom) {
        targetTop = bottom - browserHeight + FRIENDLY_PADDING;
    }
    if (targetTop > 0) {
        animateProperty(docEl, docEl, "scrollTop", scrollTop, targetTop, 0, opt_duration, "number", "decelerate");
        return true;
    }
    return false;
};
var ZIPPY_ANIMATION_DURATION = 0.05;
var ZIPPY_ANIMATION_OFFSETS = ["0 0", "-13px 0", "-26px 0", "-39px 0", "-52px 0", "-65px 0", "-78px 0"];
var ZIPPY_ANIMATION_OFFSETS_REVERSED = ZIPPY_ANIMATION_OFFSETS.slice().reverse();
function animateZippyOpen(element) {
    animatePropertyDiscrete(
        element,
        element.style,
        "backgroundPosition",
        ZIPPY_ANIMATION_OFFSETS,
        0,
        ZIPPY_ANIMATION_DURATION
    );
}
function animateZippyClosed(element) {
    animatePropertyDiscrete(
        element,
        element.style,
        "backgroundPosition",
        ZIPPY_ANIMATION_OFFSETS_REVERSED,
        0,
        ZIPPY_ANIMATION_DURATION
    );
}
(function () {
    var ChannelBackend = function (user, group, bored) {
        if (group) {
            this.aq_ = new AjaxQueue("/groups_new?action_ajax=1&name=" + group, window.session_info);
        } else if (bored) {
            this.aq_ = new AjaxQueue(
                "/bored?action_ajax=1&cat=" + window.cat + "&q=" + window.bored_q + "&sort_order=" + window.sort_order,
                window.session_info
            );
        } else {
            var new_ui_flag = window.channel_new_ui ? "&new=1" : "";
            this.aq_ = new AjaxQueue("/profile?action_ajax=1&user=" + user + new_ui_flag, window.session_info);
        }
    };
    ChannelBackend.prototype.call_box_method = function (box_info, params, method, callback, opt_logging) {
        var request = box_info;
        request.method = method;
        request.params = params;
        var unpack_callback = function (response) {
            callback(response.data);
        };
        var logging = "box_method=" + method + "&box_name=" + box_info.name + (opt_logging || "");
        this.aq_.quick_send(request, method, unpack_callback, logging); // method was "box_method"
    };
    ChannelBackend.singleton_ = null;
    ChannelBackend.get = function () {
        if (!ChannelBackend.singleton_) {
            ChannelBackend.singleton_ = new ChannelBackend(window.username, window.groupname, window.bored);
        }
        return ChannelBackend.singleton_;
    };
    function get_box_info(box_id) {
        return window.boxes[box_id];
    }
    function get_page(box_id, start, num, view_all_mode, opt_sort, opt_query) {
        var box_info = get_box_info(box_id);
        var backend = ChannelBackend.get();
        var el = _gel(box_id + "-body");
        draw_loading_div(el);
        var params = { start: start, num: num, view_all_mode: view_all_mode };
        if (opt_sort) {
            params.sort = opt_sort;
        }
        if (opt_query) {
            params.query = opt_query;
        }
        var callback = function (html) {
            channel_replace_div(el, html);
        };
        backend.call_box_method(box_info, params, "draw_page_internal", callback);
    }
    var subscribeAllTimer;
    function subscribe_all(token) {
        if (isLoggedIn) {
            window.clearTimeout(subscribeAllTimer);
            var callback = function (result) {
                var subscribeMsgNode = _gel("subscribeMessage");
                subscribeMsgNode.innerHTML = yt.net.ajax.getNodeValue(yt.net.ajax.getRootNode(result), "html_content");
                subscribeMsgNode.style.display = "block";
                subscribeAllTimer = window.setTimeout("_hidediv('subscribeMessage')", 5000);
            };
            var usernames = "";
            var subscribeAllForm = _gel("subscribeAllForm");
            for (var i = 0; i < subscribeAllForm.length; ++i) {
                if (subscribeAllForm.elements[i].checked) {
                    usernames += subscribeAllForm.elements[i].value + " ";
                }
            }
            if (usernames.length > 0) {
                var subscribeAllDiv = _gel("subscribeAllDiv");
                subscribeAllDiv.style.display = "none";
                yt.net.ajax.sendRequest("/ajax_subscriptions?subscribe_to_users=" + usernames, {
                    postBody: "session_token=" + token,
                    onComplete: callback,
                });
            }
        } else {
            subscribeMsgNode = _gel("subscribeLoginInvite");
            subscribeMsgNode.style.display = "block";
            yt.analytics.urchinTracker("/Events/VideoWatch/Subscription/" + username + "/Loggedout");
        }
    }
    function delete_feed_item(box_id, xp, at, pi, tu) {
        var delete_callback = function (response) {
            if (response && response.success) {
                channel_replace_div(_gel(box_id + "-body"), response.html);
                _show_success_or_error_message("feed_success", "feed_success_custom", response.message);
            } else {
                _show_target_and_hide_spinny("feed_table", "feed_loading");
                _show_success_or_error_message("feed_error", "feed_error_custom", response.message);
            }
        };
        return _edit_feed_item(box_id, xp, at, pi, tu, "delete", delete_callback);
    }
    function undelete_feed_item(box_id, xp, at, pi, tu) {
        var undelete_callback = function (response) {
            if (response && response.success) {
                channel_replace_div(_gel(box_id + "-body"), response.html);
            } else {
                _show_target_and_hide_spinny("feed_table", "feed_loading");
                _show_success_or_error_message("feed_error", "feed_error_custom", response.message);
            }
        };
        return _edit_feed_item(box_id, xp, at, pi, tu, "undelete", undelete_callback);
    }
    function _edit_feed_item(box_id, xp, at, pi, tu, action, callback) {
        var box_info = get_box_info(box_id);
        box_info.x_position = xp;
        var backend = ChannelBackend.get();
        _hide_target_and_show_spinny("feed_table", "feed_loading");
        endAllAnimations();
        _hidediv("feed_success");
        _hidediv("feed_success_custom");
        _hidediv("feed_error");
        _hidediv("feed_error_custom");
        backend.call_box_method(box_info, { at: at, pi: pi, tu: tu, action: action }, "edit_feed_item", callback);
        return false;
    }
    function post_feed_bulletin_handler(box_id, opt_callback) {
        if (_gel(box_id)) {
            return function (response) {
                if (response && response.success) {
                    channel_replace_div(_gel(box_id + "-body"), response.html);
                    _show_success_or_error_message("feed_success", "feed_success_custom", response.message);
                } else if (response) {
                    _show_target_and_hide_spinny("feed_table", "feed_loading");
                    _show_success_or_error_message("feed_error", "feed_error_custom", response.message);
                } else {
                    _show_target_and_hide_spinny("feed_table", "feed_loading");
                    _show_success_or_error_message("feed_error");
                }
                if (opt_callback) {
                    opt_callback(response);
                }
            };
        } else {
            return opt_callback;
        }
    }
    function post_feed_bulletin(box_id, form, opt_callback) {
        var box_info = { name: box_id, x_position: 0, do_not_render: true };
        if (_gel(box_id)) {
            box_info = get_box_info(box_id);
            _hide_target_and_show_spinny("feed_table", "feed_loading");
            endAllAnimations();
            _hidediv("feed_success");
            _hidediv("feed_success_custom");
            _hidediv("feed_error");
            _hidediv("feed_error_custom");
        }
        var video_url = form.bulletin_video_input.value;
        if (video_url == form.bulletin_video_default.value) {
            video_url = "";
        }
        var params = {
            bulletin: form.bulletin_input.value,
            video_url: video_url,
        };
        ChannelBackend.get().call_box_method(
            box_info,
            params,
            "post_feed_bulletin",
            post_feed_bulletin_handler(box_id, opt_callback)
        );
        return false;
    }
    function preview_feed_bulletin(box_id, url, opt_callback) {
        var box_info = { name: box_id, x_position: 0 };
        var params = { video_url: url };
        ChannelBackend.get().call_box_method(box_info, params, "preview_feed_bulletin", opt_callback);
    }
    function feed_bulletin_onblur(input_id, default_id, opt_force_reset) {
        var form = _gel("feed_bulletin").getElementsByTagName("form")[0];
        if (form[input_id].value == "" || opt_force_reset) {
            form[input_id].value = form[default_id].value;
        }
    }
    function feed_bulletin_onfocus(input_id, default_id) {
        var form = _gel("feed_bulletin").getElementsByTagName("form")[0];
        if (form[input_id].value == form[default_id].value) {
            form[input_id].value = "";
        }
        _gel("post_button_input").disabled = false;
        _gel("other_post_button_input").disabled = false;
    }
    function reset_feed_bulletin_attachment(input_id, input_row_id, attach_link_id, default_id) {
        _showdiv(attach_link_id);
        _hidediv(input_row_id);
        var form = _gel("feed_bulletin").getElementsByTagName("form")[0];
        form[input_id].value = form[default_id].value;
    }
    function _show_success_or_error_message(div_name, opt_custom_div_name, opt_custom_message) {
        if (opt_custom_div_name && opt_custom_message) {
            var div = _gel(opt_custom_div_name);
            div.innerHTML = opt_custom_message;
        } else {
            var div = _gel(div_name);
        }
        _showdiv(div);
        ani.fadeAndSlideUp(div, ani.DEFAULT_MESSAGE_DELAY, ani.DEFAULT_MESSAGE_FADEOUT, ani.DEFAULT_MESSAGE_SLIDE);
    }
    function _show_target_and_hide_spinny(target_name, spinny_name) {
        var target = _gel(target_name);
        var spinny = _gel(spinny_name);
        _showdiv(target);
        target.style.display = "";
        _hidediv(spinny);
    }
    function _hide_target_and_show_spinny(target_name, spinny_name) {
        var target = _gel(target_name);
        var spinny = _gel(spinny_name);
        var original_height = target.parentNode.offsetHeight;
        _showdiv(spinny);
        _showdiv(target);
        target.style.display = "";
        spinny.style.paddingTop = "0px";
        spinny.style.paddingBottom = "0px";
        var spinny_padding = Math.ceil((original_height - spinny.offsetHeight) / 2) + "px";
        spinny.style.paddingTop = spinny_padding;
        spinny.style.paddingBottom = spinny_padding;
        _hidediv(target);
    }
    function draw_loading_div(el) {
        var loading_div = document.createElement("div");
        loading_div.className = "box-bg box-fg inner-box loading-div";
        loading_div.innerHTML =
            "<table cellspacing=0 cellpadding=0 width='" +
            el.offsetWidth +
            "' height='" +
            el.offsetHeight +
            "'><tr><td align=center valign=middle><img src='http://s.ytimg.com/yt/img/icn_loading_animated-vfl24663.gif'></td></tr></table>";
        if (navigator.userAgent.indexOf("MSIE 6") != -1) {
            el.innerHTML = "<div style='display:none'>" + el.innerHTML + "</div>";
        }
        el.appendChild(loading_div);
    }
    function encode_checkbox_values(form) {
        var checked_ids = [];
        var form_size = form.elements.length;
        for (i = 0; i < form_size; i++) {
            element = form[i];
            if (element.checked) {
                checked_ids.push(element.value);
            }
        }
        return { item_ids: checked_ids };
    }
    function approve_comments(box_id, form, view_all_mode) {
        var box_info = get_box_info(box_id);
        var backend = ChannelBackend.get();
        var callback = function (json_resp) {
            if (json_resp.success) {
                channel_replace_div(_gel(box_id), json_resp.html);
            }
        };
        var params = encode_checkbox_values(form);
        params["view_all_mode"] = view_all_mode;
        backend.call_box_method(box_info, params, "approve_comments", callback);
    }
    function approve_comment(box_id, comment_id) {
        var box_info = get_box_info(box_id);
        var backend = ChannelBackend.get();
        var callback = function (json_resp) {
            if (json_resp.success) {
                channel_replace_div(_gel(box_id), json_resp.html);
            }
        };
        var params = { item_ids: [comment_id] };
        params["view_all_mode"] = "False";
        backend.call_box_method(box_info, params, "approve_comments", callback);
    }
    function remove_comments(box_id, form, view_all_mode) {
        var box_info = get_box_info(box_id);
        var backend = ChannelBackend.get();
        var callback = function (json_resp) {
            if (json_resp.success) {
                channel_replace_div(_gel(box_id), json_resp.html);
            }
        };
        var params = encode_checkbox_values(form);
        params["view_all_mode"] = view_all_mode;
        backend.call_box_method(box_info, params, "remove_comments", callback);
    }
    function remove_comment(box_id, comment_id) {
        var box_info = get_box_info(box_id);
        var backend = ChannelBackend.get();
        var callback = function (json_resp) {
            if (json_resp.success) {
                channel_replace_div(_gel(box_id), json_resp.html);
            }
        };
        var params = { item_ids: [comment_id] };
        params["view_all_mode"] = "False";
        backend.call_box_method(box_info, params, "remove_comments", callback);
    }
    function add_comment(box_id, comment, view_all_mode, items_per_page, opt_challenge, opt_response) {
        var comment_entry_submit_button = _gel("comment_entry_submit_button");
        var box_info = get_box_info(box_id);
        var backend = ChannelBackend.get();
        var table = _gel("profile_comments_table");
        var div_containing_table = table.parentNode;
        var box_div = div_containing_table.parentNode;
        var comment_entry_box = _gel("comment_entry_box");
        var callback = function (json_response) {
            if (json_response["success"]) {
                comment_entry_submit_button.disabled = false;
                ani.scrollUntilVisible(box_div.parentNode, 0.75);
                _gel("add_comment_link").style.display = "block";
                comment_entry_box.style.display = "none";
                if (!json_response["moderate"] && table.rows.length == items_per_page) {
                    table.deleteRow(table.rows.length - 1);
                }
                var new_row = document.createElement("div");
                new_row.innerHTML = json_response["new_row"];
                div_containing_table.insertBefore(new_row, table);
                ani.ajaxSlideOpen(new_row, 0.75, 0.5);
            } else {
                if (json_response["errors"]) {
                    alert(json_response["errors"]);
                }
                if (json_response["captcha_required"]) {
                    comment_entry_box.innerHTML = json_response["captcha_html"];
                    _gel("comment").value = comment;
                    _gel("view_all_mode").value = view_all_mode;
                    _gel("items_per_page").value = items_per_page;
                } else {
                    get_page(box_id, 0, items_per_page, view_all_mode);
                }
            }
        };
        if (comment) {
            comment_entry_submit_button.disabled = true;
            comment_entry_submit_button.value = yt.getMsg("POSTING_COMMENT");
            var params = { comment: comment, view_all_mode: view_all_mode };
            if (opt_challenge) {
                params["challenge"] = opt_challenge;
                params["response"] = opt_response;
            }
            backend.call_box_method(box_info, params, "add_comment", callback);
        }
    }
    function report_spam(box_id, encrypted_comment_id) {
        var box_info = get_box_info(box_id);
        var backend = ChannelBackend.get();
        var callback = function (json_response) {
            if (json_response["success"]) {
                _gel(encrypted_comment_id + "-mark_spam_link").style.display = "none";
                _gel(encrypted_comment_id + "-marked_as_spam_text").style.display = "inline";
            } else {
                if (json_response["errors"]) {
                    alert(json_response["errors"]);
                }
            }
        };
        if (encrypted_comment_id) {
            backend.call_box_method(box_info, { encrypted_comment_id: encrypted_comment_id }, "report_spam", callback);
        } else {
            callback({ success: true });
        }
    }
    function display_message(id, color, message) {
        var message_box = _gel(id);
        if (!message_box) {
            message_box = _gel("global-messages");
        }
        message_box.style.backgroundColor = color;
        message_box.style.padding = "1em";
        message_box.style.margin = "1em";
        message_box.style.textAlign = "center";
        message_box.innerHTML = message;
        message_box.style.display = "";
        ani.fadeAndSlideUp(
            message_box,
            ani.DEFAULT_MESSAGE_DELAY,
            ani.DEFAULT_MESSAGE_FADEOUT,
            ani.DEFAULT_MESSAGE_SLIDE
        );
    }
    function add_friend(username) {
        var box_info = get_box_info("user_profile");
        var backend = ChannelBackend.get();
        var callback = function (json_resp) {
            if (json_resp.requested) {
                display_message("user_profile-messages", CHANNEL_SUCCESS_COLOR, json_resp.message);
                _showdiv("aProfileInvitedFriend");
                _hidediv("aProfileAddFriend");
            } else if (json_resp.success) {
                display_message("user_profile-messages", CHANNEL_SUCCESS_COLOR, json_resp.message);
                _showdiv("aProfileRemoveFriend");
                _hidediv("aProfileAddFriend");
            } else {
                display_message("user_profile-messages", CHANNEL_ERROR_COLOR, json_resp.message);
            }
        };
        params = { username: username };
        backend.call_box_method(box_info, params, "add_friend", callback);
    }
    function remove_friend(username) {
        var box_info = get_box_info("user_profile");
        var backend = ChannelBackend.get();
        var callback = function (json_resp) {
            if (json_resp.success) {
                display_message("user_profile-messages", CHANNEL_SUCCESS_COLOR, json_resp.message);
                _showdiv("aProfileAddFriend");
                _hidediv("aProfileRemoveFriend");
            } else {
                display_message("user_profile-messages", CHANNEL_ERROR_COLOR, json_resp.message);
            }
        };
        params = { username: username };
        backend.call_box_method(box_info, params, "remove_friend", callback);
    }
    window.get_page = get_page;
    window.draw_loading_div = draw_loading_div;
    window.approve_comments = approve_comments;
    window.approve_comment = approve_comment;
    window.remove_comments = remove_comments;
    window.remove_comment = remove_comment;
    window.add_comment = add_comment;
    window.report_spam = report_spam;
    window.subscribe_all = subscribe_all;
    window.delete_feed_item = delete_feed_item;
    window.feed_bulletin_onblur = feed_bulletin_onblur;
    window.feed_bulletin_onfocus = feed_bulletin_onfocus;
    window.post_feed_bulletin = post_feed_bulletin;
    window.preview_feed_bulletin = preview_feed_bulletin;
    window.reset_feed_bulletin_attachment = reset_feed_bulletin_attachment;
    window.get_channel_backend = ChannelBackend.get;
    window.get_channel_box_info = get_box_info;
    window.display_message = display_message;
    window.add_friend = add_friend;
    window.remove_friend = remove_friend;
})();
function replace_div(el, new_html) {
    if (el.outerHTML) {
        el.outerHTML = new_html;
        return el;
    }
    var next_sibling = el.nextSibling;
    var parent_node = el.parentNode;
    parent_node.removeChild(el);
    var replacement = document.createElement("div");
    replacement.innerHTML = new_html;
    var node = null;
    if (replacement.firstChild) {
        node = replacement.firstChild;
        if (next_sibling) {
            parent_node.insertBefore(node, next_sibling);
        } else {
            parent_node.appendChild(node);
        }
    }
    return node;
}
function channel_replace_div(el, new_html) {
    if (window.channel_new_ui && el.id.match(/-body$/)) {
        el.innerHTML = new_html;
    } else {
        replace_div(el, new_html);
    }
    run_scripts_in_el(el.id);
}
function safeGetElementsByTagName(el, name) {
    name = name.toLowerCase();
    if (el.getElementsByTagName) {
        return el.getElementsByTagName(name);
    }
    var childNodes = el.childNodes;
    output = new Array();
    for (var i = 0; i < childNodes.length; i++) {
        var child = childNodes[i];
        if (child.tagName && child.tagName.toLowerCase() == name) {
            output.push(child);
        } else if (child.childNodes) {
            output = output.concat(safeGetElementsByTagName(child, name));
        }
    }
    return output;
}
function run_scripts_in_el(el) {
    el = _gel(el);
    var scripts = safeGetElementsByTagName(el, "script");
    for (var i = 0; i < scripts.length; i++) {
        window.script_executed = false;
        var script_content = scripts[i].innerHTML + ";\nwindow.script_executed=true;";
        var new_script = document.createElement("script");
        new_script.type = "text/javascript";
        new_script.text = script_content;
        document.getElementsByTagName("head")[0].appendChild(new_script);
        if (!window.script_executed) {
            eval(script_content);
        }
    }
    var styles = safeGetElementsByTagName(el, "style");
    for (var i = 0; i < styles.length; i++) {
        document.getElementsByTagName("head")[0].appendChild(styles[i]);
    }
}
function simpleCallback(xhr, callback, domEl) {
    if (xhr.responseXML == null) {
        callback(yt.getMsg("ERROR_WHILE_PROCESSING"), domEl);
        return;
    }
    var root_node = yt.net.ajax.getRootNode(xhr);
    var return_code = yt.net.ajax.getNodeValue(root_node, "return_code");
    if (return_code == 0) {
        var success_message = yt.net.ajax.getNodeValue(root_node, "success_message");
        if (success_message != null) {
            callback(success_message, domEl);
        }
    } else {
        var error_msg = yt.net.ajax.getNodeValue(root_node, "error_message");
        if (error_msg == null || error_msg.length == 0) {
            error_msg = yt.getMsg("UNKOWN_ERROR");
        }
        callback(error_msg, domEl);
    }
    redirect_val = yt.net.ajax.getNodeValue(root_node, "redirect_on_success");
    if (redirect_val != null) {
        window.location = redirect_val;
    }
}
function postSimpleXR(url, data, callback, domEl) {
    yt.net.ajax.sendRequest(url, {
        postBody: data,
        onComplete: function (xmlHttpReq) {
            simpleCallback(xmlHttpReq, callback, domEl);
        },
    });
}
function showConfMsg(msg, domEl) {
    if (domEl && domEl.parentNode) {
        domEl.parentNode.style.backgroundColor = "#fff";
        domEl.parentNode.innerHTML = msg;
    }
}
function displayAndSelectEmbedCode() {
    _gel("embed_input_div").style.display = "";
    _gel("embed_display").style.display = "none";
    _gel("embed_input").focus();
    _gel("embed_input").select();
}
function handleSubscribe() {
    var subscribe_buttons = document.getElementsByClassName("subscribe-div");
    var unsubscribe_buttons = document.getElementsByClassName("unsubscribe-div");
    for (var i = 0; i < subscribe_buttons.length; ++i) {
        yt.style.toggle(subscribe_buttons[i]);
    }
    for (var i = 0; i < unsubscribe_buttons.length; ++i) {
        yt.style.toggle(unsubscribe_buttons[i]);
    }
}
var CHANNEL_SUCCESS_COLOR = "#d0ffd8";
var CHANNEL_ERROR_COLOR = "#F99";
(function () {
    var current_channel_edit_tab = null;
    var current_theme_name_backup = null;
    var current_tab_modified = false;
    function channel_edit_tab(tab_name, opt_cancel) {
        if (!window.scripts_are_loaded) {
            return false;
        }
        var current_tab_contents = _gel("tab_contents_" + current_channel_edit_tab);
        if (current_tab_contents) {
            if (current_tab_modified) {
                if (
                    !opt_cancel &&
                    !confirm(yt.getMsg("CONFIRM_NAVIGATE_AWAY") + "\n\n" + yt.getMsg("CONFIRM_UNSAVED_CHANGES"))
                ) {
                    return;
                }
            }
            if (current_channel_edit_tab == "colors") {
                window.theme_map = copy_object(theme_obj_backups);
                if (current_theme_name_backup) {
                    set_theme_obj(current_theme_name_backup);
                }
                if (current_tab_modified) {
                    current_tab_modified = false;
                }
                if (current_theme_name_backup) window.current_theme_name = current_theme_name_backup;
            }
            if (current_tab_modified) {
                current_tab_contents.innerHTML = current_tab_contents.__backup;
                current_tab_modified = false;
            }
            if (current_channel_edit_tab == "layout") {
                var layout_status = get_layout_status(_gel("channel_layout"));
                for (var box_name in layout_status) {
                    if (layout_status[box_name]) {
                        if (!_gel(box_name)) {
                            add_box(box_name);
                        }
                    } else {
                        if (_gel(box_name)) {
                            remove_box(box_name);
                        }
                    }
                }
            }
            current_subtab_modified = false;
            current_tab_contents.style.display = "none";
        }
        if (current_channel_edit_tab) {
            _removeclass(_gel("channel_tab_" + current_channel_edit_tab), "channel_settings_tab_active");
        }
        if (current_channel_edit_tab == tab_name || tab_name == "close") {
            current_channel_edit_tab = null;
            _gel("channel_edit_close").style.display = "none";
            return;
        }
        var tab_contents = _gel("tab_contents_" + tab_name);
        if (tab_contents) {
            tab_contents.style.display = "";
            tab_contents.__backup = tab_contents.innerHTML;
        }
        if (tab_name) {
            _addclass(_gel("channel_tab_" + tab_name), "channel_settings_tab_active");
            if (tab_name == "branding_options") {
                branding_options_subtab("banners");
            }
        }
        force_IE_redraw();
        current_channel_edit_tab = tab_name;
        _gel("channel_edit_close").style.display = "";
        return true;
    }
    window.channel_edit_tab = channel_edit_tab;
    var current_subtab_name;
    var current_subtab_modified = false;
    function branding_options_subtab(subtab_name, opt_cancel) {
        if (!window.scripts_are_loaded) {
            return false;
        }
        var current_subtab_contents = _gel("subtab_contents_" + current_subtab_name);
        if (current_subtab_contents) {
            if (current_subtab_modified) {
                if (
                    !opt_cancel &&
                    !confirm(yt.getMsg("CONFIRM_NAVIGATE_AWAY") + "\n\n" + yt.getMsg("CONFIRM_UNSAVED_CHANGES"))
                ) {
                    return;
                }
            }
            current_subtab_contents.style.display = "none";
            _removeclass(_gel("branding_options_subtab_" + current_subtab_name), "branding_options_subtab_active");
            current_tab_modified = false;
            current_subtab_modified = false;
        }
        var subtab_contents = _gel("subtab_contents_" + subtab_name);
        if (subtab_contents) {
            subtab_contents.style.display = "";
            _addclass(_gel("branding_options_subtab_" + subtab_name), "branding_options_subtab_active");
        }
        current_subtab_name = subtab_name;
    }
    window.branding_options_subtab = branding_options_subtab;
    function force_IE_redraw() {
        window.setTimeout(function () {
            _addclass(_gel("channel-body"), "dummy");
            _removeclass(_gel("channel-body"), "dummy");
        }, 0);
    }
    var stylesheet = null;
    function update_theme_css(name, value, opt_prefix) {
        current_tab_modified = true;
        get_theme_css();
    }
    function hide_all_children(el) {
        var child_nodes = el.childNodes;
        for (var i = 0; i < child_nodes.length; i++) {
            var node = child_nodes[i];
            if (node && node.style) {
                node.style.display = "none";
            }
        }
    }
    var last_option_div = null;
    function set_theme_obj(theme_name, opt_first_time) {
        if (window.deleting_theme) {
            return;
        }
        if (window.current_theme_name) {
            var t = [_gel("delete_" + window.current_theme_name), _gel("delete_" + theme_name)];
            if (t[0]) {
                t[0].style.display = "inline";
            }
            if (t[1]) {
                t[1].style.display = "none";
            }
        }
        var theme_obj = window.theme_map[theme_name];
        window.current_theme_obj = theme_obj;
        if (window.current_theme_name && _gel(window.current_theme_name)) {
            _removeclass(_gel(window.current_theme_name), "theme_selected");
        }
        _addclass(_gel(theme_name), "theme_selected");
        window.current_theme_name = theme_name;
        update_theme_inputs(theme_obj);
        current_palette = theme_obj.palettes["default"];
        _gel("theme_display_name").innerHTML = htmlEscape(theme_obj.display_name || theme_name);
        _gel("theme_edit_name").value = theme_obj.display_name || theme_name;
        if (theme_obj.builtin) {
            _gel("theme_display_name").style.display = "inline";
            _gel("theme_edit_name").style.display = "none";
        } else {
            _gel("theme_display_name").style.display = "none";
            _gel("theme_edit_name").style.display = "inline";
            try {
                _gel("theme_edit_name").focus();
            } catch (e) {}
        }
        if (!opt_first_time) {
            update_theme_css();
        }
    }
    window.set_theme_obj = set_theme_obj;
    function setDropdownTo(dropdownId, value, isLowerCaseCompare) {
        var dropdownEl = _gel(dropdownId);
        if (isLowerCaseCompare) {
            value = value.toLowerCase();
        }
        for (var i = 0, len = dropdownEl.length; i < len; i++) {
            var optionValue = dropdownEl.options[i].value;
            if (isLowerCaseCompare) optionValue = optionValue.toLowerCase();
            if (optionValue && value == optionValue) {
                dropdownEl.selectedIndex = i;
                break;
            }
        }
    }
    function update_theme_inputs(theme_obj) {
        setDropdownTo("font", theme_obj.font, true);
        setDropdownTo("wrapper_opacity", theme_obj.wrapper_opacity);
        var colors = ["background_color", "wrapper_color", "wrapper_text_color", "wrapper_link_color"];
        for (var i = 0; i < colors.length; i++) {
            var color = colors[i];
            var value = theme_obj[color];
            _gel(color + "-preview").style.backgroundColor = value;
            _gel(color).value = value;
        }
        _gel("background_image").value = theme_obj["background_image"];
        try {
            if (theme_obj["background_image"]) {
                window.frames["background_frame"].hide_file_picker();
            } else {
                window.frames["background_frame"].show_file_picker();
            }
        } catch (e) {}
        _gel("background_repeat").value = theme_obj["background_repeat"];
        _gel("background_repeat_check").checked = theme_obj["background_repeat"] == "repeat";
        set_palette("default", false);
    }
    function set_palette(name, opt_set_css) {
        var palette = window.current_theme_obj.palettes[name];
        current_palette = palette;
        setDropdownTo("box_opacity", palette.box_opacity);
        var colors = ["box_background_color", "link_color", "title_text_color", "body_text_color"];
        for (var i = 0; i < colors.length; i++) {
            var color = colors[i];
            _gel(color + "-preview").style.backgroundColor = palette[color];
            _gel(color).value = palette[color];
            if (opt_set_css) {
                update_theme_css(color, palette[color]);
            }
        }
    }
    window.set_palette = set_palette;
    function open_theme_editor() {
        _showdiv("theme_advanced_editor");
        _showdiv("theme_edit_link_hide");
        _hidediv("theme_edit_link");
        force_IE_redraw();
    }
    window.open_theme_editor = open_theme_editor;
    function delete_theme(theme_name) {
        if (window.current_theme_name == theme_name) {
            return;
        }
        var textdiv = _gel("are_you_sure_you_want_to_delete_text");
        if (textdiv) {
            if (!confirm(textdiv.innerHTML)) {
                return;
            }
            window.deleting_theme = true;
            request = [theme_name];
            var backend = get_channel_backend();
            _addclass(_gel("tab_contents_colors"), "saving");
            backend.aq_.quick_send(request, "delete_theme", delete_theme_handler);
        }
    }
    window.delete_theme = delete_theme;
    window.deleting_theme = false;
    function delete_theme_handler(response) {
        var theme_name = null;
        var tdiv = null;
        if (response && response.success) {
            for (var i = 0; i < response.deleted.length; i++) {
                theme_name = response.deleted[i];
                delete window.theme_map[theme_name];
                tdiv = _gel(theme_name);
                tdiv.parentNode.removeChild(tdiv);
            }
        }
        window.deleting_theme = false;
        _removeclass(_gel("tab_contents_colors"), "saving");
    }
    window.delete_theme_handler = delete_theme_handler;
    function hide_theme_editor() {
        _hidediv("theme_advanced_editor");
        _hidediv("theme_edit_link_hide");
        _showdiv("theme_edit_link");
        force_IE_redraw();
    }
    window.hide_theme_editor = hide_theme_editor;
    var THEME_DIV_HTML =
        "<div id=\"^theme_name^\" class='theme_selector_div' " +
        'style="font-family:^font^"' +
        'onclick="set_theme_obj(this.id);"' +
        " ^delete_stuff^ " +
        '><div style="background-color: ^background_color^;' +
        'color:^body_text_color^;padding: 3px;line-height:120%"' +
        '><div style="background-color: ^wrapper_color^;color: ^wrapper_text_color^;padding:3px;font-size:10px">' +
        '<div style="float:right;width:4em;background-color:^box_background_color^;font-size:9px;padding-left:1px;color:^body_text_color^">' +
        '<span style="color:^title_text_color^;font-size:120%">' +
        "A" +
        "</span>" +
        ' &nbsp;<span style="color:^link_color^;text-decoration:underline">url</span><br>' +
        "abc</div>" +
        '<span style="color:^wrapper_link_color^;text-decoration:underline">url</span><br>' +
        "abc" +
        "</div></div>" +
        '<div style="text-align:center;"><span class="theme_title" style="padding:2px;height:2em;overflow:hidden">^theme_display_name^</span><br>' +
        '<a href="#" class="hLink" onclick="delete_theme(\'^theme_name^\');return false;"' +
        'style="font-size:75%;visibility:hidden" id="delete_^theme_name^">^delete_text^</a></div>' +
        "</div>\n";
    var THEME_DIV_DELETE =
        "onmouseover=\"_gel('delete_^theme_name^').style.visibility='visible';\" " +
        "onmouseout=\"_gel('delete_^theme_name^').style.visibility='hidden';\"";
    function get_default_palette(theme_obj) {
        var default_palette = theme_obj.palettes["default"];
        default_palette.name = "default";
        return default_palette;
    }
    function generate_theme_box_html(theme_name, theme_obj) {
        if (!theme_obj || !theme_obj.palettes) {
            return;
        }
        var output_html = THEME_DIV_HTML;
        if (theme_obj.builtin) {
            output_html = output_html.replace(/\^delete_stuff\^/g, "");
        } else {
            output_html = output_html.replace(/\^delete_stuff\^/g, THEME_DIV_DELETE);
        }
        output_html = output_html.replace(/\^theme_name\^/g, theme_name);
        output_html = output_html.replace(/\^background_color\^/g, theme_obj.background_color);
        output_html = output_html.replace(/\^wrapper_color\^/g, theme_obj.wrapper_color);
        output_html = output_html.replace(/\^wrapper_text_color\^/g, theme_obj.wrapper_text_color);
        output_html = output_html.replace(/\^wrapper_link_color\^/g, theme_obj.wrapper_link_color);
        output_html = output_html.replace(/\^theme_display_name\^/g, htmlEscape(theme_obj.display_name));
        output_html = output_html.replace(/\^font\^/g, theme_obj.font);
        var default_palette = get_default_palette(theme_obj);
        output_html = output_html.replace(/\^box_background_color\^/g, default_palette.box_background_color);
        output_html = output_html.replace(/\^title_text_color\^/g, default_palette.title_text_color);
        output_html = output_html.replace(/\^link_color\^/g, default_palette.link_color);
        output_html = output_html.replace(/\^body_text_color\^/g, default_palette.body_text_color);
        output_html = output_html.replace(/\^delete_text\^/g, _gel("delete_link_text").innerHTML);
        return output_html;
    }
    function add_theme_div(theme_name, theme_obj) {
        var output_html = generate_theme_box_html(theme_name, theme_obj);
        var temp_div = document.createElement("div");
        temp_div.innerHTML = output_html;
        _gel("theme_container").appendChild(temp_div.firstChild);
    }
    window.add_theme_div = add_theme_div;
    var current_palette;
    function edit_main_theme(name, value, opt_skip_css) {
        var theme_name = window.current_theme_name;
        var theme_obj = window.current_theme_obj;
        if (theme_obj.builtin) {
            create_new_theme();
            return edit_main_theme(name, value);
        }
        if (typeof theme_obj[name] != "undefined") {
            theme_obj[name] = value;
            if (!opt_skip_css) {
                update_theme_css(name, value);
            }
        } else {
            current_palette[name] = value;
            var prefix = current_palette.name == "default" ? null : ".palette-" + current_palette.name;
            if (!opt_skip_css) {
                update_theme_css(name, value, prefix);
            }
        }
        replace_div(_gel(theme_name), generate_theme_box_html(theme_name, theme_obj, true));
        _addclass(_gel(theme_name), "theme_selected");
    }
    window.edit_main_theme = edit_main_theme;
    var theme_obj_backups = {};
    function add_theme_selectors(default_theme_name, theme_ordering) {
        for (var i in theme_ordering) {
            var theme_name = theme_ordering[i];
            var theme_obj = window.theme_map[theme_name];
            if (theme_obj) {
                add_theme_div(theme_name, theme_obj);
                theme_obj_backups[theme_name] = copy_object(theme_obj);
            }
        }
        current_theme_name_backup = default_theme_name;
        set_theme_obj(default_theme_name, true);
    }
    window.add_theme_selectors = add_theme_selectors;
    function generate_theme_name(old_name) {
        var numeric_suffix = old_name.match("[0-9]+$");
        if (numeric_suffix) {
            return old_name.substr(0, old_name.length - numeric_suffix[0].length) + (parseInt(numeric_suffix[0]) + 1);
        }
        return old_name + " 2";
    }
    function create_new_theme() {
        if (window.theme_map.length >= 20) {
            display_message("colors-messages", "#F99", yt.getMsg("THEME_LIMIT"));
            return;
        }
        var new_theme_obj = copy_object(window.current_theme_obj);
        new_theme_obj.builtin = false;
        new_theme_obj.display_name = generate_theme_name(new_theme_obj.display_name);
        var suffix = window.current_theme_name.match("[0-9]+$");
        var new_theme_name = window.current_theme_name.slice(
            0,
            suffix ? -suffix[0].length : window.current_theme_name.length
        );
        suffix = suffix != null ? parseInt(suffix[0]) + 1 : 2;
        while (window.theme_map[new_theme_name + suffix]) {
            suffix++;
        }
        new_theme_name += suffix;
        window.theme_map[new_theme_name] = new_theme_obj;
        add_theme_div(new_theme_name, new_theme_obj);
        set_theme_obj(new_theme_name, true);
        open_theme_editor();
    }
    window.create_new_theme = create_new_theme;
    function save_theme_handler(response) {
        if (response && response.success) {
            current_tab_modified = false;
            theme_obj_backups = copy_object(window.theme_map);
            current_theme_name_backup = window.current_theme_name;
            var tab_contents = _gel("tab_contents_" + current_channel_edit_tab);
            tab_contents.__backup = tab_contents.innerHTML;
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
            channel_edit_tab("close");
        } else if (response && response.errors) {
            display_message("colors-messages", "#F99", response.errors.join("<br>"));
        }
        _removeclass(_gel("tab_contents_colors"), "saving");
    }
    function save_themes() {
        var backend = get_channel_backend();
        var request = { themes: copy_object(window.theme_map) };
        request.theme_name = window.current_theme_name;
        request.background_image_counter = window.background_image_counter;
        backend.aq_.quick_send(request, "save_theme", save_theme_handler);
        _addclass(_gel("tab_contents_colors"), "saving");
    }
    window.save_themes = save_themes;
    function replace_stylesheet(cssId, cssText) {
        var cssEl = _gel(cssId);
        if (!cssEl) return;
        try {
            cssEl.innerHTML = cssText;
        } catch (e) {
            try {
                cssEl.innerText = cssText;
            } catch (e) {
                var css = document.styleSheets[cssId];
                css.cssText = cssText;
            }
        }
    }
    function get_theme_css_handler(data) {
        replace_stylesheet("channel-theme-css", data.html);
    }
    function get_theme_css() {
        var backend = get_channel_backend();
        var theme_obj = window.theme_map[window.current_theme_name];
        var request = { theme: theme_obj };
        backend.aq_.quick_send(request, "get_theme_css", get_theme_css_handler);
    }
    function update_theme_name(obj, evt) {
        var e = evt ? evt : window.event;
        if (e.keyCode == 13 || e.keyCode == 27) {
            obj.blur();
            return false;
        }
        length_check(e, obj, 15);
        window.setTimeout(function () {
            var new_name = _gel("theme_edit_name").value;
            window.current_theme_obj.display_name = new_name;
            _gel("theme_display_name").innerHTML = htmlEscape(new_name);
            var current_theme_div = _gel(window.current_theme_name);
            var title_div = goog.dom.getElementsByTagNameAndClass("SPAN", "theme_title", current_theme_div)[0];
            title_div.innerHTML = htmlEscape(new_name);
        }, 0);
    }
    window.update_theme_name = update_theme_name;
    function length_check(evt, obj, max_length) {
        var old_value = obj.value;
        window.setTimeout(function () {
            if (obj.value.length > max_length) {
                var old_scroll_top = obj.scrollTop;
                obj.value = old_value.substr(0, max_length);
                obj.scrollTop = old_scroll_top;
            }
        }, 0);
        var e = evt ? evt : window.event;
        if (obj.value.length >= max_length && e.keyCode > 46) {
            return false;
        }
        return true;
    }
    window.length_check = length_check;
    var popup_prop_name = null;
    function popup_color_grid(name) {
        document.onclick = null;
        var grid = _gel("popup_color_grid");
        if (popup_prop_name) {
            grid.style.display = "none";
            popup_prop_name = null;
            return;
        }
        var pos = ani.getPosition(_gel(name));
        grid.style.top = pos.y + 20 + "px";
        grid.style.left = pos.x - 20 + "px";
        grid.style.display = "";
        document.body.appendChild(grid);
        popup_prop_name = name;
        window.setTimeout(function () {
            document.onclick = function (opt_evt) {
                var e = opt_evt ? opt_evt : window.event;
                var t = e.target ? e.target : e.srcElement;
                if (t.nodeType == 3) t = t.parentNode;
                grid.style.display = "none";
                document.onclick = null;
                popup_prop_name = null;
                var preview_node = _gel(name + "_option");
                if (t == preview_node || yt.dom.hasAncestor(t, preview_node)) {
                    yt.events.stopPropagation(e);
                    return false;
                }
            };
        }, 1);
    }
    window.popup_color_grid = popup_color_grid;
    function select_popup_color(color) {
        if (popup_prop_name) {
            edit_main_theme(popup_prop_name, color);
            _gel(popup_prop_name + "-preview").style.backgroundColor = color;
            _gel(popup_prop_name).value = color;
        }
    }
    window.select_popup_color = select_popup_color;
    var VALID_COLOR_REGEX = new RegExp(
        "^(#[0-9a-f]{3,6}|white|black|blue|red|green|yellow|cyan|purple|violet|pink|salmon|orange|navy|gray|lightgrey|darkgray|brown)$",
        "i"
    );
    function blur_color_picker(el) {
        if (el.value.match(VALID_COLOR_REGEX)) {
            edit_main_theme(el.id, el.value);
            _gel(el.id + "-preview").style.backgroundColor = el.value;
        } else {
            el.value = el.__old_value || "";
        }
    }
    window.blur_color_picker = blur_color_picker;
    function color_picker_keypress(input, evt) {
        if (evt.keyCode == 13) {
            this.blur();
            if (this.onblur) this.onblur();
            return false;
        } else if (evt.keyCode == 27) {
            this.value = this.__old_value;
            this.blur();
            if (this.onblur) this.onblur();
            return false;
        }
    }
    window.color_picker_keypress = color_picker_keypress;
    function draw_box_handler(response) {
        if (response && response.data) {
            var old_node = _gel(response.data.box_info.name);
            if (old_node) {
                window.setTimeout(function () {
                    removeNode(old_node);
                    draw_box_handler(response);
                }, 200);
                return;
            }
            var box_info = response.data.box_info;
            window.boxes[box_info.name] = box_info;
            var temp_div = document.createElement("div");
            temp_div.innerHTML = response.data.html;
            var node = temp_div.firstChild;
            insert_box(box_info, node);
        } else {
        }
    }
    function insert_box(box_info, node) {
        box_status[box_info.name] = true;
        var parent_div;
        if (box_info.y_position < 0) {
            parent_div = _gel("channel-base-div");
        } else if (box_info.x_position == 1) {
            parent_div = _gel("main-channel-right");
        } else {
            parent_div = _gel("main-channel-left");
        }
        var next_box = get_box_after(box_info);
        if (next_box) {
            var next_box_div = _gel(next_box);
            next_box_div.parentNode.insertBefore(node, next_box_div);
        } else if (box_info.y_position < 0) {
            parent_div.insertBefore(node, _gel("main-channel-content"));
        } else {
            parent_div.appendChild(node);
        }
        if (box_info.name == "user_events") {
            run_scripts_in_el(node);
        }
        fix_box_arrows();
        ani.slideDownAndFadeIn(node, 0, 0.4, 0.25);
    }
    function polarity(num) {
        return num >= 0;
    }
    function get_box_after(box_info) {
        var next_box = null;
        for (var name in window.boxes) {
            var box = window.boxes[name];
            if (polarity(box.y_position) != polarity(box_info.y_position)) {
                continue;
            }
            if (
                box.y_position > box_info.y_position &&
                x_pos_matches(box, box_info) &&
                box.name != box_info.name &&
                box_status[box.name] &&
                (!next_box || box.y_position < next_box.y_position)
            ) {
                next_box = box;
            }
        }
        return next_box ? next_box.name : null;
    }
    function x_pos_matches(box1, box2) {
        if (box1.y_position < 0 && box2.y_position < 0) {
            return true;
        }
        return box1.x_position == box2.x_position;
    }
    function get_box_before(box_info) {
        var previous_box = null;
        for (var name in window.boxes) {
            var box = window.boxes[name];
            if (
                box.x_position == box_info.x_position &&
                box.name != box_info.name &&
                box_status[box.name] &&
                box.y_position < box_info.y_position &&
                (!previous_box || box.y_position > previous_box.y_position)
            ) {
                previous_box = box;
            }
        }
        return previous_box ? previous_box.name : null;
    }
    function add_box(name, opt_save) {
        var backend = get_channel_backend();
        var request = { name: name };
        if (window.boxes[name]) {
            request.x_position = window.boxes[name].x_position;
            request.y_position = window.boxes[name].y_position;
        }
        request.theme_name = window.current_theme_name;
        request.save = opt_save ? true : false;
        backend.aq_.quick_send(request, "draw_box", draw_box_handler);
    }
    function remove_box(name) {
        var node = _gel(name);
        ani.fadeAndSlideUp(node, 0, 0.3, 0.4);
        scheduleAction(
            node,
            node,
            function () {
                removeNode(node);
            },
            1.7
        );
        box_status[name] = false;
        fix_box_arrows();
    }
    function add_or_remove_box(input) {
        window.setTimeout(function () {
            if (input.checked) {
                add_box(input.value);
            } else {
                _gel("box_removed_message").style.display = "";
                remove_box(input.value);
            }
            if (input.value == "user_playlist_navigator") {
                _gel("channel_tab_playnav").style.display = input.checked ? "block" : "none";
            }
        }, 0);
        current_tab_modified = true;
    }
    window.add_or_remove_box = add_or_remove_box;
    function hide_if_there(id) {
        var el = _gel(id);
        if (el) _addclass(el, "disabled");
    }
    function show_if_there(id) {
        var el = _gel(id);
        if (el) _removeclass(el, "disabled");
    }
    function fix_box_arrows() {
        var top_left = null;
        var top_right = null;
        var bottom_left = null;
        var bottom_right = null;
        var top_top = null;
        var bottom_top = null;
        for (var name in window.boxes) {
            var box = window.boxes[name];
            show_if_there(name + "-up-arrow");
            show_if_there(name + "-down-arrow");
            if (box && box_status[name] && !IMMOVABLE_BOX[name]) {
                if (box.y_position >= 0) {
                    if (box.x_position == 1) {
                        if (!top_right || top_right.y_position > box.y_position) {
                            top_right = box;
                        }
                        if (!bottom_right || bottom_right.y_position < box.y_position) {
                            bottom_right = box;
                        }
                    } else {
                        if (!top_left || top_left.y_position > box.y_position) {
                            top_left = box;
                        }
                        if (!bottom_left || bottom_left.y_position < box.y_position) {
                            bottom_left = box;
                        }
                    }
                } else {
                    if (!top_top || top_top.y_position > box.y_position) {
                        top_top = box;
                    }
                    if (!bottom_top || bottom_top.y_position < box.y_position) {
                        bottom_top = box;
                    }
                }
            }
        }
        if (top_right) hide_if_there(top_right.name + "-up-arrow");
        if (top_left) hide_if_there(top_left.name + "-up-arrow");
        if (bottom_right) hide_if_there(bottom_right.name + "-down-arrow");
        if (bottom_left) hide_if_there(bottom_left.name + "-down-arrow");
        if (top_top) hide_if_there(top_top.name + "-up-arrow");
        if (bottom_top) hide_if_there(bottom_top.name + "-down-arrow");
    }
    window.fix_box_arrows = fix_box_arrows;
    function move_up(name) {
        if (_hasclass(_gel(name + "-up-arrow"), "disabled")) {
            return;
        }
        swap_boxes(name, get_box_before(window.boxes[name]));
    }
    window.move_up = move_up;
    function move_down(name) {
        if (_hasclass(_gel(name + "-down-arrow"), "disabled")) {
            return;
        }
        swap_boxes(name, get_box_after(window.boxes[name]));
    }
    window.move_down = move_down;
    function swap_boxes(name1, name2) {
        var el1 = _gel(name1);
        var el2 = _gel(name2);
        var tmp = window.boxes[name1].y_position;
        window.boxes[name1].y_position = window.boxes[name2].y_position;
        window.boxes[name2].y_position = tmp;
        fix_box_arrows();
        ani.swapDivs(el1, el2, 0.7);
        var backend = get_channel_backend();
        backend.aq_.send_message({ boxes: [name1, name2] }, "swap_boxes", true);
    }
    function move_left(name) {
        if (_hasclass(_gel(name + "-left-arrow"), "disabled")) {
            return;
        }
        window.boxes[name].x_position = 0;
        remove_box(name);
        add_box(name, true);
    }
    window.move_left = move_left;
    function move_right(name) {
        if (_hasclass(_gel(name + "-right-arrow"), "disabled")) {
            return;
        }
        window.boxes[name].x_position = 1;
        remove_box(name);
        add_box(name, true);
    }
    window.move_right = move_right;
    var IMMOVABLE_BOX = { user_profile: true };
    var box_status = {};
    yt.pubsub.subscribe("init", function () {
        for (var name in window.boxes) {
            var box = window.boxes[name];
            if (box && box.name && _gel(name)) {
                box_status[name] = true;
            }
        }
        if (window.channel_new_ui) {
            _addclass(_gel("channel-body"), "jsloaded");
            fix_box_arrows();
        }
    });
    function save_boxes_handler(response) {
        if (response && response.data && response.data.success) {
            current_tab_modified = false;
            var tab_contents = _gel("tab_contents_" + current_channel_edit_tab);
            tab_contents.__backup = tab_contents.innerHTML;
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
            channel_edit_tab("close");
        } else if (response && response.errors) {
            display_message("layout-messages", "#F99", response.errors.join("<br>"));
        }
        _removeclass(_gel("tab_contents_layout"), "saving");
    }
    function get_layout_status(form) {
        var statusRequest = {};
        for (var i = 0; i < form.elements.length; i++) {
            var input = form.elements[i];
            if (input.type == "checkbox") {
                statusRequest[input.value] = input.checked;
            }
        }
        return statusRequest;
    }
    function save_boxes() {
        var backend = get_channel_backend();
        backend.aq_.register_handler("save_boxes", save_boxes_handler);
        backend.aq_.send_message({ boxes: get_layout_status(_gel("channel_layout")) }, "save_boxes", true);
        _addclass(_gel("tab_contents_layout"), "saving");
    }
    window.save_boxes = save_boxes;
    function save_watch_header_handler(response) {
        if (response && response.data && response.data.success) {
            current_tab_modified = false;
            current_subtab_modified = false;
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
        } else if (response && response.errors) {
            display_message("video-messages", "#F99", response.errors.join("<br>"));
        }
        _removeclass(_gel("subtab_contents_videopage"), "saving");
    }
    function save_watch_header() {
        var form = _gel("watch_header_form");
        var request = form_to_dict(form);
        var backend = get_channel_backend();
        backend.aq_.register_handler("save_watch_header", save_watch_header_handler);
        backend.aq_.send_message(request, "save_watch_header", true);
        _addclass(_gel("subtab_contents_videopage"), "saving");
    }
    window.save_watch_header = save_watch_header;
    function save_banners_handler(response) {
        if (response && response.data && response.data.success) {
            current_tab_modified = false;
            current_subtab_modified = false;
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
        } else if (response && response.errors) {
            display_message("banners-messages", "#F99", response.errors.join("<br>"));
        }
        _removeclass(_gel("subtab_contents_banners"), "saving");
    }
    function save_banners() {
        var form = _gel("banners_form");
        var request = form_to_dict(form);
        var backend = get_channel_backend();
        backend.aq_.register_handler("save_banners", save_banners_handler);
        backend.aq_.send_message(request, "save_banners", true);
        _addclass(_gel("subtab_contents_banners"), "saving");
    }
    window.save_banners = save_banners;
    function save_tracking_handler(response) {
        if (response && response.data && response.data.success) {
            current_tab_modified = false;
            current_subtab_modified = false;
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
        } else if (response && response.errors) {
            display_message("tracking-messages", "#F99", response.errors.join("<br>"));
        }
        _removeclass(_gel("subtab_contents_tracking"), "saving");
    }
    function save_tracking() {
        var form = _gel("tracking_form");
        var request = form_to_dict(form);
        var backend = get_channel_backend();
        backend.aq_.register_handler("save_tracking", save_tracking_handler);
        backend.aq_.send_message(request, "save_tracking", true);
        _addclass(_gel("subtab_contents_tracking"), "saving");
    }
    window.save_tracking = save_tracking;
    function close_edit_mode(box_id) {
        var box = _gel(box_id);
        if (_hasclass(box, "edit_mode")) {
            _removeclass(box, "edit_mode");
        }
    }
    function cancel_edit_mode(box_id) {
        close_edit_mode(box_id);
        var box = _gel(box_id);
        box.innerHTML = box.__backup;
    }
    window.cancel_edit_mode = cancel_edit_mode;
    function toggle_edit_mode(box_id) {
        var box = _gel(box_id);
        if (!_hasclass(box, "edit_mode")) {
            box.__backup = box.innerHTML;
        }
        if (_hasclass(box, "edit_mode")) {
            _removeclass(box, "edit_mode");
        } else {
            _addclass(box, "edit_mode");
        }
    }
    window.toggle_edit_mode = toggle_edit_mode;
    function save_box_settings(box_id) {
        var box_info = get_channel_box_info(box_id);
        var backend = get_channel_backend();
        _addclass(_gel(box_id), "saving");
        var callback = function (response) {
            _removeclass(_gel(box_id), "saving");
            if (response.success) {
                close_edit_mode(box_id);
                if (response.html) {
                    channel_replace_div(_gel(box_id), response.html);
                    fix_box_arrows();
                }
                if (response.js_exec) {
                    eval(response.js_exec);
                }
                display_message(box_id + "-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
            } else if (response.errors) {
                display_message(box_id + "-messages", "#F99", response.errors.join("<br>"));
            }
        };
        var params = form_to_dict(_gel("edit_form_" + box_id));
        backend.call_box_method(box_info, params, "save", callback);
    }
    window.save_box_settings = save_box_settings;
    function save_playnav_settings() {
        var box_id = "user_playlist_navigator";
        var box_info = get_channel_box_info(box_id);
        var backend = get_channel_backend();
        _addclass(_gel("tab_contents_playnav"), "saving");
        var callback = function (response) {
            if (response.success) {
                if (_gel(box_id)) {
                    if (response.html) {
                        channel_replace_div(_gel(box_id), response.html);
                        fix_box_arrows();
                    }
                    if (response.js_exec) {
                        eval(response.js_exec);
                    }
                }
                current_tab_modified = false;
                var tab_contents = _gel("tab_contents_" + current_channel_edit_tab);
                tab_contents.__backup = tab_contents.innerHTML;
                display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
                channel_edit_tab("close");
            } else if (response && response.errors) {
                display_message("info-messages", "#F99", response.errors.join("<br>"));
            }
            _removeclass(_gel("tab_contents_playnav"), "saving");
        };
        var params = form_to_dict(_gel("edit_form_" + box_id));
        backend.call_box_method(box_info, params, "save", callback);
    }
    window.save_playnav_settings = save_playnav_settings;
    function swap_with_input(id, focus, opt_update_height) {
        yt.style.toggle("profile_temp_" + id, "profile_edit_" + id);
        if (focus) {
            _gel("profile_edit_" + id).focus();
            if (opt_update_height) {
                _gel("profile_edit_" + id).style.height = Math.max(_gel("profile_temp_" + id).offsetHeight, 45) + "px";
            }
        } else {
            var new_value = _gel("profile_edit_" + id).value;
            if (new_value) {
                var view_toggle = _gel("hide_" + id);
                if (view_toggle) {
                    view_toggle.checked = false;
                }
            }
            _gel("profile_temp_" + id).innerHTML = new_value ? htmlEscape(new_value).replace(/\n/g, "<br>") : "&nbsp;";
        }
    }
    window.swap_with_input = swap_with_input;
    function save_profile_box() {
        var box_id = "user_profile";
        var box_info = get_channel_box_info(box_id);
        var backend = get_channel_backend();
        _addclass(_gel(box_id), "saving");
        var callback = function (response) {
            _removeclass(_gel(box_id), "saving");
            if (response.success) {
                close_edit_mode(box_id);
                _gel(box_id).innerHTML = response.html;
                display_message(box_id + "-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
            } else if (response.error_fields) {
                for (var field_name in response.error_fields) {
                    var bad_field = _gel("profile_edit_" + field_name);
                    if (bad_field) {
                        bad_field.focus();
                        var error_field = _gel("profile_errors_" + field_name);
                        error_field.style.display = "";
                        error_field.innerHTML = escapeHTML(response.error_fields[field_name]);
                    }
                }
            } else if (response.errors) {
                display_message(box_id + "-messages", "#F99", response.errors.join("<br>"));
            }
        };
        var params = form_to_dict(_gel("edit_form_" + box_id));
        backend.call_box_method(box_info, params, "save", callback);
    }
    window.save_profile_box = save_profile_box;
    function set_channel_title(title) {
        var title_div = _gel("channel_title");
        var title_base_div = _gel("channel_base_title");
        title_div.innerHTML = htmlEscape(title);
        if (title) {
            title_base_div.style.fontSize = "11px";
            title_base_div.style.paddingTop = "0";
            title_div.style.display = "";
        } else {
            title_base_div.style.fontSize = "16px";
            title_base_div.style.paddingTop = "9px";
            title_div.style.display = "none";
        }
    }
    function save_channel_settings_handler(response) {
        if (response && response.data && response.data.success) {
            set_channel_title(_gel("channel_title_input").value);
            save_profile_box();
            current_tab_modified = false;
            var tab_contents = _gel("tab_contents_" + current_channel_edit_tab);
            tab_contents.__backup = tab_contents.innerHTML;
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
            channel_edit_tab("close");
        } else if (response && response.errors) {
            display_message("info-messages", "#F99", response.errors.join("<br>"));
        }
        _removeclass(_gel("tab_contents_info"), "saving");
    }
    function settings_tab_keypress() {
        current_tab_modified = true;
    }
    window.settings_tab_keypress = settings_tab_keypress;
    function save_channel_settings() {
        var form = _gel("channel_settings");
        var request = form_to_dict(form);
        var backend = get_channel_backend();
        backend.aq_.register_handler("save_channel_settings", save_channel_settings_handler);
        backend.aq_.send_message(request, "save_channel_settings", true);
        _addclass(_gel("tab_contents_info"), "saving");
    }
    window.save_channel_settings = save_channel_settings;
    function swap_input_for_placeholder(name) {
        var input = _gel(name);
        var placeholder = _gel(name + "__");
        placeholder.style.display = "none";
        input.value = "";
        input.style.display = "";
        input.focus();
    }
    window.swap_input_for_placeholder = swap_input_for_placeholder;
    function feed_bulletin_preview_onfocus(input) {
        input.hasFocus__ = true;
        feed_bulleting_preview_periodic(input, _gel("preview_bulletin_message"));
    }
    window.feed_bulletin_preview_onfocus = feed_bulletin_preview_onfocus;
    function feed_bulletin_preview_onblur(input) {
        input.hasFocus__ = false;
    }
    window.feed_bulletin_preview_onblur = feed_bulletin_preview_onblur;
    function feed_bulleting_preview_periodic(input, previewMessage) {
        previewMessage.innerHTML = "";
        if (input.style.display != "none") {
            feed_bulletin_maxlength(input);
            previewMessage.appendChild(document.createTextNode(input.value));
        }
        if (input.hasFocus__) {
            window.setTimeout(function () {
                feed_bulleting_preview_periodic(input, previewMessage);
            }, 500);
        }
    }
    function feed_bulletin_maxlength(input) {
        var MAX_LENGTH = 500;
        if (input.value.length > MAX_LENGTH) {
            input.value = input.value.substr(0, MAX_LENGTH);
        }
    }
    window.feed_bulletin_maxlength = feed_bulletin_maxlength;
    function feed_bulletin_preview_video(input) {
        var previewLoading = _gel("preview_bulletin_loading");
        var previewVideo = _gel("preview_bulletin_video");
        var videoUrl = input.value;
        previewLoading.style.display = "none";
        previewVideo.innerHTML = "";
        if (input.style.display != "none" && videoUrl.match(/^(https?:\/)?\/[-\w.\/&?+=~:;!%()@#,*]*$/)) {
            var callback = function (response) {
                previewLoading.style.display = "none";
                if (response && response.success && response.html) {
                    previewVideo.innerHTML = response.html;
                }
            };
            previewLoading.style.display = "";
            preview_feed_bulletin("user_recent_activity", videoUrl, callback);
        }
    }
    window.feed_bulletin_preview_video = feed_bulletin_preview_video;
    function reset_feed_bulletin_fields() {
        var form = _gel("post_feed_bulletin_tab");
        form.bulletin_input.style.display = "none";
        form.bulletin_input.value = "";
        form.bulletin_input__.value = form.bulletin_default.value;
        form.bulletin_input__.style.display = "";
        form.bulletin_video_input.style.display = "none";
        form.bulletin_video_input.value = "";
        form.bulletin_video_input__.value = form.bulletin_video_default.value;
        form.bulletin_video_input__.style.display = "";
        _gel("preview_bulletin_message").innerHTML = "";
        _gel("preview_bulletin_video").innerHTML = "";
        _gel("preview_bulletin_loading").style.display = "none";
    }
    function post_feed_bulletin_from_tab_handler(response) {
        if (response && response.success) {
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, response.message);
            reset_feed_bulletin_fields();
            channel_edit_tab("close");
        } else if (response) {
            display_message("bulletin-messages", "#F99", response.message);
        }
    }
    function post_feed_bulletin_from_tab() {
        var form = _gel("post_feed_bulletin_tab");
        if (form.bulletin_input.style.display == "none") {
            form.bulletin_input.value = "";
        }
        if (form.bulletin_video_input.style.display == "none") {
            form.bulletin_video_input.value = "";
        }
        post_feed_bulletin("user_recent_activity", form, post_feed_bulletin_from_tab_handler);
    }
    window.post_feed_bulletin_from_tab = post_feed_bulletin_from_tab;
    function cancel_feed_bulletin_from_tab() {
        reset_feed_bulletin_fields();
        channel_edit_tab("close", true);
    }
    window.cancel_feed_bulletin_from_tab = cancel_feed_bulletin_from_tab;
    function save_watch_branding_handler(response) {
        if (response && response.data && response.data.success) {
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
            current_tab_modified = false;
            var tab_contents = _gel("tab_contents_" + current_channel_edit_tab);
            tab_contents.__backup = tab_contents.innerHTML;
        } else if (response && response.errors) {
            display_message("watch_branding-messages", "#F99", response.errors.join("<br>"));
        }
        _removeclass(_gel("tab_contents_watch_branding"), "saving");
    }
    function save_watch_branding() {
        var form = _gel("watch_branding");
        var request = form_to_dict(form);
        var backend = get_channel_backend();
        backend.aq_.quick_send(request, "save_watch_branding", save_watch_branding_handler);
        _addclass(_gel("tab_contents_watch_branding"), "saving");
    }
    window.save_watch_branding = save_watch_branding;
    function save_live_stream_handler(response) {
        if (response && response.data && response.data.success) {
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
            current_tab_modified = false;
            var tab_contents = _gel("tab_contents_" + current_channel_edit_tab);
            tab_contents.__backup = tab_contents.innerHTML;
        } else if (response && response.errors) {
            display_message("live_stream-messages", "#F99", response.errors.join("<br>"));
        }
        _removeclass(_gel("tab_contents_live_stream"), "saving");
    }
    function save_live_stream() {
        var form = _gel("live_stream");
        var request = form_to_dict(form);
        var backend = get_channel_backend();
        backend.aq_.quick_send(request, "save_live_stream", save_live_stream_handler);
        _addclass(_gel("tab_contents_live_stream"), "saving");
    }
    window.save_live_stream = save_live_stream;
    function channel_hierarchy_response_handler(response) {
        if (response && response.data && response.data.success) {
            display_message("edit-save-messages", CHANNEL_SUCCESS_COLOR, yt.getMsg("SUCCESS"));
            current_tab_modified = false;
            var tab_contents = _gel("tab_contents_" + current_channel_edit_tab);
            tab_contents.__backup = tab_contents.innerHTML;
        } else if (response && response.errors) {
            display_message("channel_hierarchy-messages", "#F99", response.errors.join("<br>"));
        }
    }
    function change_hierarchy_passcode() {
        var form = _gel("hierarchy_passcode");
        var request = form_to_dict(form);
        var backend = get_channel_backend();
        backend.aq_.quick_send(request, "change_hierarchy_passcode", channel_hierarchy_response_handler);
    }
    window.change_hierarchy_passcode = change_hierarchy_passcode;
    function modify_child_channel_handler(response) {
        if (response.data && response.data.html) _gel("children_list").innerHTML = response.data.html;
        channel_hierarchy_response_handler(response);
    }
    function add_child_channel() {
        var form = _gel("add_child");
        var request = form_to_dict(form);
        var backend = get_channel_backend();
        backend.aq_.quick_send(request, "add_child_channel", modify_child_channel_handler);
    }
    window.add_child_channel = add_child_channel;
    function remove_child_channel(childname) {
        var request = { childname: childname };
        var backend = get_channel_backend();
        backend.aq_.quick_send(request, "remove_child_channel", modify_child_channel_handler);
    }
    window.remove_child_channel = remove_child_channel;
    function changeCommentPermissions(friend_only, moderation) {
        _gel("profile_comment_friend_only").value = friend_only;
        _gel("profile_comment_moderation").value = moderation;
    }
    window.changeCommentPermissions = changeCommentPermissions;
    function uploaded_background_image(upload_response_variables) {
        if (upload_response_variables.upload_type == "channel_background") {
            if (upload_response_variables.success) {
                _gel("background_image").value = upload_response_variables.image_url;
                edit_main_theme("background_image", upload_response_variables.image_url, true);
                update_theme_css("background_image", upload_response_variables.preview_url);
            } else {
                display_message("colors-messages", "#F99", upload_response_variables.error_msg);
            }
            window.frames["background_frame"].document.getElementById("channel_background_counter").value =
                ++window.background_image_counter;
        } else if (upload_response_variables.upload_type == "watch_background") {
            if (!upload_response_variables.success) {
                display_message("watch_branding-messages", "#F99", upload_response_variables.error_msg);
            }
            _gel("delete_watch_background").value = "False";
        } else if (upload_response_variables.upload_type == "watch_header") {
            if (!upload_response_variables.success) {
                display_message("video-messages", "#F99", upload_response_variables.error_msg);
            } else {
                _gel("delete_watch_header").value = "False";
            }
        } else if (upload_response_variables.upload_type == "watch_icon") {
            if (!upload_response_variables.success) {
                display_message("video-messages", "#F99", upload_response_variables.error_msg);
            } else {
                _gel("delete_watch_icon").value = "False";
                _gel("delete_new_watch_icon").value = "False";
            }
        } else if (upload_response_variables.upload_type == "profile_header") {
            if (upload_response_variables.success) {
                _gel("branding_image_visible").value = "True";
            } else {
                display_message("banners-messages", "#F99", upload_response_variables.error_msg);
            }
        } else if (upload_response_variables.upload_type == "side_column") {
            if (upload_response_variables.success) {
                _gel("side_column_image_visible").value = "True";
            } else {
                display_message("banners-messages", "#F99", upload_response_variables.error_msg);
            }
        }
    }
    window.uploaded_background_image = uploaded_background_image;
    function delete_background_upload(upload_type) {
        switch (upload_type) {
            case "channel_background":
                _gel("background_image").value = "";
                edit_main_theme("background_image", "");
                break;
            case "watch_background":
                _gel("delete_watch_background").value = "True";
                current_subtab_modified = true;
                break;
            case "new_watch_background":
                _gel("delete_new_watch_background").value = "True";
                current_subtab_modified = true;
                break;
            case "new_watch_small_banner":
                _gel("delete_new_watch_small_banner").value = "True";
                current_subtab_modified = true;
                break;
            case "new_watch_large_banner":
                _gel("delete_new_watch_large_banner").value = "True";
                current_subtab_modified = true;
                break;
            case "watch_header":
                _gel("delete_watch_header").value = "True";
                current_subtab_modified = true;
                break;
            case "watch_icon":
                _gel("delete_watch_icon").value = "True";
                _gel("delete_new_watch_icon").value = "True";
                current_subtab_modified = true;
                break;
            case "profile_header":
                _gel("branding_image_visible").value = "False";
                current_subtab_modified = true;
                break;
            case "side_column":
                _gel("side_column_image_visible").value = "False";
                current_subtab_modified = true;
                break;
        }
        current_tab_modified = true;
    }
    window.delete_background_upload = delete_background_upload;
    function update_hidden_field(name) {
        window.setTimeout(function () {
            var displayed = _gel("show_" + name).checked;
            _gel("hide_" + name).checked = !displayed;
            if (!displayed) {
                _addclass(_gel("edit_info_" + name), "opacity40");
            } else {
                _removeclass(_gel("edit_info_" + name), "opacity40");
            }
        }, 1);
    }
    window.update_hidden_field = update_hidden_field;
    function update_merged_date(name) {
        var year = _gel(name + "_yr").value;
        var month = _gel(name + "_mon").value;
        if (month.length < 2) {
            month = "0" + month;
        }
        var day = _gel(name + "_day").value;
        if (day.length < 2) {
            day = "0" + day;
        }
        _gel(name + "_merged").value = year + month + day;
    }
    window.update_merged_date = update_merged_date;
    function toggle_playnav_edit_mode() {
        _gel("playnav_edit_info").__backup = _gel("playnav_edit_info").innerHTML;
        toggle_edit_mode("user_playlist_navigator");
    }
    window.toggle_playnav_edit_mode = toggle_playnav_edit_mode;
    function profile_remove_user(username, box) {
        if (!confirm(yt.getMsg("SRSLY_GTFO"))) {
            return;
        }
        var backend = get_channel_backend();
        var request = { username: username, box: box };
        backend.aq_.quick_send(request, "profile_remove_user", profile_remove_user_handler);
    }
    window.profile_remove_user = profile_remove_user;
    function profile_remove_user_handler(response) {
        window.location.reload(true);
    }
    function toggle_remover(el_name, show) {
        var name = "rm-user-" + el_name;
        if (show) {
            _showdiv(name);
        } else {
            _hidediv(name);
        }
    }
    window.toggle_remover = toggle_remover;
    function gather_form_data(form) {
        var formdata = {};
        var form_elements = form.elements;
        var input_names = [];
        for (var i = 0; i < form_elements.length; i++) {
            var input = form_elements[i];
            input_names.push(input.name);
            if ((input.type == "checkbox" || input.type == "radio") && input.checked == false) {
                continue;
            }
            var value = input.value;
            if (input.type == "select-multiple") {
                value = [];
                var options = input.options;
                for (var j = 0; j < options.length; j++) {
                    var option = options[j];
                    value.push(option.value);
                }
            }
            formdata[input.name] = value;
        }
        return formdata;
    }
    function copy_object(obj) {
        var temp = {};
        if (typeof obj == "object") {
            for (var prop in obj) {
                if (typeof obj[prop] == "object") {
                    if (obj[prop].__isArray) {
                        temp[prop] = copy_array(obj[prop]);
                    } else {
                        temp[prop] = copy_object(obj[prop]);
                    }
                } else {
                    temp[prop] = obj[prop];
                }
            }
            return temp;
        }
        return obj;
    }
    function copy_array(arr) {
        return arr.slice(0);
    }
    function form_to_dict(form) {
        var form_vars = {};
        for (var i = 0; i < form.elements.length; i++) {
            var input = form.elements[i];
            var name = input.name;
            if (input.type == "checkbox") {
                if (input.checked) {
                    if (!form_vars[name]) {
                        form_vars[name] = new Array();
                    }
                    form_vars[name].push(input.value);
                }
            } else if (input.type != "radio" || input.checked) {
                var value = input.value;
                if (input.type == "select-multiple") {
                    value = [];
                    var options = input.options;
                    for (var j = 0; j < options.length; j++) {
                        var option = options[j];
                        value.push(option.value);
                    }
                }
                form_vars[name] = value;
            }
        }
        return form_vars;
    }
})();
function extendInputSelect(select) {
    if (select != null && select._isExtended === undefined) {
        select.maxItems = -1;
        select.setMaxItems = function (count) {
            this.maxItems = count >= 0 ? count : -1;
        };
        select.moveSelectedTo = function (list) {
            while (this.selectedIndex >= 0) {
                if (!list.addWithCheck(this.options[this.selectedIndex])) break;
                this.options[this.selectedIndex] = null;
            }
        };
        select.moveAllTo = function (list) {
            while (this.options.length > 0) {
                if (!list.addWithCheck(this.options[0])) break;
                this.options[0] = null;
            }
        };
        select.addWithCheck = function (option) {
            if (this.maxItems == -1 || this.options.length < this.maxItems) {
                var newOption = new Option();
                newOption.text = option.text;
                newOption.value = option.value;
                newOption.selected = option.selected;
                if (option.dir) {
                    newOption.dir = option.dir;
                }
                if (this.isSorted) {
                    this.addSorted(newOption);
                } else {
                    this.addAppend(newOption);
                }
                return true;
            }
            return false;
        };
        select.addAppend = function (option) {
            this.addInsert(option, this.options.length);
        };
        select.addInsert = function (option, index) {
            if (index >= 0 && index <= this.options.length) {
                for (var i = this.options.length; i > index; i--) {
                    this.options[i] = new Option();
                    this.options[i].text = this.options[i - 1].text;
                    this.options[i].value = this.options[i - 1].value;
                    this.options[i].selected = this.options[i - 1].selected;
                    if (this.options[i - 1].dir) {
                        this.options[i].dir = this.options[i - 1].dir;
                    }
                }
                this.options[index] = option;
            }
        };
        select.addSorted = function (option) {
            if (!this.isSorted) this.setSorted(true);
            var index = this.options.length;
            for (var i = 0; i < this.options.length; i++) {
                if (this.options[i].text > option.text) {
                    index = i;
                    break;
                }
            }
            this.addInsert(option, index);
        };
        select.importOption = function (option) {
            var newOption = new Option();
            newOption.text = option.text;
            newOption.value = option.value;
            newOption.selected = option.selected;
            if (option.dir) {
                newOption.dir = option.dir;
            }
            return newOption();
        };
        select.sort = function () {
            if (this.options.length < 2) return;
            for (var i = 0; i < this.options.length - 1; i++) {
                if (this.options[i].text > this.options[i + 1].text) {
                    var swap = new Option();
                    swap.text = this.options[i].text;
                    swap.value = this.options[i].value;
                    swap.selected = this.options[i].selected;
                    if (this.options[i].dir) {
                        swap.dir = this.options[i].dir;
                    }
                    this.options[i].text = this.options[i + 1].text;
                    this.options[i].value = this.options[i + 1].value;
                    this.options[i].selected = this.options[i + 1].selected;
                    this.options[i + 1].text = swap.text;
                    this.options[i + 1].value = swap.value;
                    this.options[i + 1].selected = swap.selected;
                    if (swap.dir) {
                        this.options[i + 1].dir = swap.dir;
                    }
                    i = -1;
                }
            }
        };
        select.isSorted = false;
        select.setSorted = function (bit) {
            this.isSorted = bit;
            if (this.isSorted) {
                this.sort();
            }
        };
        select.getSelectedValue = function () {
            if (this.selectedIndex < 0) return null;
            return this.options[this.selectedIndex].value;
        };
        select._isExtended = true;
    }
}
function DualListBoxController(left, right) {
    this.left = _gel(left);
    this.right = _gel(right);
    extendInputSelect(this.left);
    extendInputSelect(this.right);
    this.moveAllRight = function () {
        this.left.moveAllTo(this.right);
    };
    this.moveSelectedRight = function () {
        this.left.moveSelectedTo(this.right);
    };
    this.moveAllLeft = function () {
        this.right.moveAllTo(this.left);
    };
    this.moveSelectedLeft = function () {
        this.right.moveSelectedTo(this.left);
    };
}
window.box_editor_loaders = {};
var playlistDualListBoxController = null;
window.box_editor_loaders.user_playlists = function (box_id) {
    playlistDualListBoxController = new DualListBoxController(box_id + "-allplaylists", box_id + "-addedplaylists");
    playlistDualListBoxController.right.setMaxItems(max_playlists);
    playlistDualListBoxController.left.setSorted(true);
};
function change_peeps_rows(box_id, incr) {
    var current_value_div = _gel(box_id + "-current_value");
    var current_value = parseInt(current_value_div.innerHTML);
    current_value += incr;
    if (current_value < 1) {
        current_value = 1;
    } else if (current_value > 3) {
        current_value = 3;
    }
    current_value_div.innerHTML = current_value;
    return false;
}
var playnav = function () {};
playnav.onPlayerLoadedFunctions = [];
playnav.onDomLoaded = [];
(function () {
    var OVERSCROLL = 500;
    var AUTOSKIP_ERROR_TIMEOUT = 3000;
    var ARRANGER_WAIT_DELAY = 100;
    var PlayState = {
        UNSTARTED: -1,
        ENDED: 0,
        PLAYING: 1,
        PAUSED: 2,
        BUFFERING: 3,
        CUED: 5,
    };
    var isAutoskip_ = false;
    var isAutoplay_ = true;
    var boxId_ = "user_playlist_navigator";
    var backend_;
    var boxInfo_ = { name: boxId_, x_position: 0 };
    var player_;
    var currentPlayState_ = PlayState.UNSTARTED;
    var isWritePlayerPending_ = false;
    var initialLocationHash_;
    var curViewName_ = "";
    var curPlaylistName_;
    var curPlaylistId_;
    var curSearchQuery_;
    var curSortName_ = "default";
    var invalidatedPlaylists_ = {};
    var curSelection_;
    var curVideoId_;
    var curVideoIndex_;
    var curPanelName_ = "info";
    var isSkipping_ = false;
    var isDomLoaded_ = false;
    var isViewUpdateRequested_ = false;
    var isViewUpdatePending_ = false;
    var arrangerOpenRequested_ = null;
    var arrangerRestoreParams_ = null;
    var ageVerificationRequired_ = false;
    var scrollableItemSetupCallback_ = null;
    var arranger_ = null;
    function setupScrollableItems(callback) {
        scrollableItemSetupCallback_ = callback;
        if (callback) {
            var currentScrollbox = goog.dom.$(getCurrentScrollboxId());
            callback(currentScrollbox);
        }
    }
    function getCurrentScrollboxId() {
        return ["playnav", curViewName_, curPlaylistId_, "scrollbox"].join("-");
    }
    function setBoxInfo(boxId) {
        boxId_ = boxId;
        boxInfo_ = { name: boxId_, x_position: 0 };
    }
    function handlePendingFunction(fname) {
        if (playnav[fname]) {
            playnav[fname]();
            playnav[fname] = null;
        }
    }
    function executeAll(callbacks) {
        while (callbacks.length > 0) {
            callbacks.shift()();
        }
    }
    function setViewElementStyle(id, param, value) {
        var element = goog.dom.$(id);
        if (element) {
            element.style[param] = value;
        }
    }
    function removeLoadingClasses(elementName) {
        var element = goog.dom.$(elementName);
        _removeclass(element, "playnav-visible");
        _removeclass(element, "playnav-hidden");
        _removeclass(element, "playnav-show");
        _removeclass(element, "playnav-hide");
    }
    function stripLocationHash(locationHash) {
        if (locationHash.charAt(0) == "#") {
            locationHash = locationHash.substr(1);
        }
        return locationHash.split("&")[0];
    }
    function getLocationHash() {
        return stripLocationHash(window.location.hash);
    }
    function parseLocationHash(opt_hash) {
        var locationHash = {
            length: 0,
            view: "",
            playlistName: "",
            playlistId: "",
            videoIndex: 0,
            videoId: "",
        };
        var hash = opt_hash || getLocationHash();
        if (hash && hash != "") {
            var parts = hash.split("/");
            parts = Iter(parts).collect(function (str) {
                return decodeURIComponent(str);
            });
            var view = parts[0];
            if (view == "v") {
                locationHash.videoId = parts[1] || "";
            } else {
                view = attemptToMap(viewNameMap, "+", view);
                if (view != "grid" && view != "play") {
                    view = "";
                    parts.unshift(view);
                }
                locationHash.view = view;
                locationHash.length = parts.length;
                if (locationHash.length > 1) {
                    locationHash.playlistName = attemptToMap(tabNameMap, "+", parts[1]);
                }
                switch (locationHash.length) {
                    case 3:
                        locationHash.playlistId = attemptToMap(playlistIdMap, "+", parts[2]);
                        break;
                    case 4:
                        locationHash.videoIndex = parts[2];
                        locationHash.videoId = parts[3];
                        break;
                    case 5:
                        locationHash.playlistId = parts[2];
                        if (locationHash.playlistName == "all") {
                            if (locationHash.playlistId.indexOf("-all") < 0) {
                                locationHash.playlistId += "-all";
                            }
                            locationHash.playlistId = attemptToMap(playlistIdMap, "+", locationHash.playlistId);
                        }
                        locationHash.videoIndex = parts[3];
                        locationHash.videoId = parts[4];
                        break;
                }
            }
        }
        return locationHash;
    }
    function handleLocationHash(locationHash, opt_isInitialLocationHash) {
        var wasVideoRequested = false;
        var handlerFunctions = [];
        if (!locationHash) {
            return false;
        }
        if (locationHash.view != "" && locationHash.view != curViewName_) {
            playnav.selectView(locationHash.view);
        }
        var playlistId = locationHash.playlistName;
        var loadPlaylistFunction;
        if (locationHash.playlistId != "") {
            if (locationHash.playlistId != curPlaylistId_) {
                playlistId = locationHash.playlistId;
                loadPlaylistFunction = playnav.selectPlaylist.bind(null, locationHash.playlistName, playlistId);
            }
        } else if (locationHash.playlistName != "") {
            handlerFunctions.push(playnav.selectPlaylist.bind(null, locationHash.playlistName));
        }
        if (locationHash.videoId != "") {
            handlerFunctions.push(
                playnav.playVideo.bind(
                    null,
                    playlistId,
                    locationHash.videoIndex,
                    locationHash.videoId,
                    null,
                    null,
                    opt_isInitialLocationHash
                )
            );
            wasVideoRequested = true;
        } else if (loadPlaylistFunction) {
            handlerFunctions.push(loadPlaylistFunction);
        }
        executeAll(handlerFunctions);
        return wasVideoRequested;
    }
    function handleLocationHashUpdate(windowLocationHash) {
        var locationHash = stripLocationHash(windowLocationHash);
        handleLocationHash(parseLocationHash(locationHash));
    }
    var viewNameMap = {
        "-grid": "g",
        "-play": "p",
        "+p": "play",
        "+g": "grid",
    };
    var tabNameMap = {
        "-all": "a",
        "+a": "all",
        "-favorites": "f",
        "+f": "favorites",
        "-uploads": "u",
        "+u": "uploads",
        "-playlists": "p",
        "+p": "playlists",
        "-user": "c",
        "+c": "user",
        "-topics": "t",
        "+t": "topics",
        "-recent": "r",
        "+r": "recent",
    };
    var playlistIdMap = {
        "-uploads-all": "u-all",
        "-favorites-all": "f-all",
        "+u-all": "uploads-all",
        "+f-all": "favorites-all",
    };
    function attemptToMap(map, prefix, key) {
        var tmp = map[prefix + key];
        if (tmp) return tmp;
        return key;
    }
    function updateHistory(viewName, tabName, playlistId, opt_videoIndex, opt_videoId) {
        viewName = attemptToMap(viewNameMap, "-", viewName);
        tabName = attemptToMap(tabNameMap, "-", tabName);
        playlistId = attemptToMap(tabNameMap, "-", playlistId);
        parts = [viewName, tabName];
        if (playlistId != tabName) {
            playlistId = attemptToMap(playlistIdMap, "-", playlistId);
            if (tabName == "a") {
                playlistId = playlistId.replace("-all", "");
            }
            parts.push(playlistId);
        }
        if (opt_videoIndex && opt_videoId) {
            parts.push(opt_videoIndex);
            parts.push(opt_videoId);
        }
        var windowLocationHash = getLocationHash();
        var locationHash = parts.join("/");
        if (windowLocationHash.indexOf(locationHash) != 0) {
            History.add(locationHash);
        }
    }
    function navigatorNotReady() {
        if (!isDomLoaded_) {
            if (window.console && window.console["warn"]) {
                window.console["warn"]("Function called before navigator initialized.");
                window.console["trace"] && window.console["trace"]();
            }
            return true;
        }
        return false;
    }
    function setInitialView(viewName) {
        curViewName_ = viewName;
    }
    function setInitialTab(tabName) {
        curPlaylistName_ = tabName;
    }
    function initDom() {
        if (isDomLoaded_) {
            return;
        }
        isDomLoaded_ = true;
        backend_ = get_channel_backend();
        boxInfo_ = get_channel_box_info(boxId_);
        removeLoadingClasses("playnav-player");
        removeLoadingClasses("playnav-playview");
        removeLoadingClasses("playnav-gridview");
        initTab(curPlaylistName_);
        initView(curViewName_);
        updateViewOnly();
        resizeView();
        executeAll(playnav.onDomLoaded);
        handlePendingFunction("mostRecentSelectViewFunction");
        handlePendingFunction("mostRecentSelectTabFunction");
    }
    function initPlayer(playerId, tabName) {
        player_.addEventListener("onStateChange", "playnav.onPlayerStateChange");
        player_.addEventListener("onError", "playnav.onPlayerError");
        if (curViewName_ == "play") {
            executeAll(playnav.onPlayerLoadedFunctions);
            handlePendingFunction("mostRecentStopFunction");
        } else {
            handlePendingFunction("mostRecentStopFunction");
        }
    }
    var infoPanelData = { html: "<div></div>" };
    function setVideoPlayerConfigVars(data) {
        var swfArgs = data["swf_args"];
        var swfUrl = data["swf_url"];
        swfArgs && yt.setConfig("SWF_ARGS", swfArgs);
        swfUrl && yt.setConfig("SWF_URL", swfUrl);
    }
    function disableAutoplaySwfArg() {
        var swfArgs = yt.getConfig("SWF_ARGS");
        swfArgs && (swfArgs["autoplay"] = "0");
        yt.setConfig("SWF_ARGS", swfArgs);
    }
    function handleVideoMetadata(videoId, data, opt_embeddedVideo) {
        var okToWritePlayer = true;
        if (opt_embeddedVideo) {
            goog.dom.$("profile-noplayer-div").style.display = "none";
            initialLocationHash_ = parseLocationHash();
            if (handleLocationHash(initialLocationHash_, true) || initialLocationHash_.view == "grid") {
                okToWritePlayer = false;
            }
            if (curViewName_ == "grid") {
                okToWritePlayer = false;
            }
        }
        var error = data["error"];
        if (error) {
            display_message("user_playlist_navigator-messages", "#F99", error);
        } else if (data["play_response_error"] || data["play_response_check"]) {
            playnav.verifyAge(
                data["play_response_id"],
                data["play_response_title"],
                data["redirect_url"],
                data["play_response_error_message"]
            );
            onPlayerError();
        } else {
            playnav.verifyNotRequired();
            setVideoPlayerConfigVars(data);
            if (okToWritePlayer) {
                writePlayer();
            } else {
                isWritePlayerPending_ = true;
            }
            infoPanelData = { html: data["info_panel_html"], js_exec: data["js_exec"] };
            playnav.selectPanel("info");
        }
    }
    function writePlayer() {
        isWritePlayerPending_ = false;
        var div = goog.dom.$("profile-noplayer-div");
        if (div) {
            div.style.display = "";
        }
        yt.www.watch.player.write("playnav-player", null, false, "onChannelPlayerReady", "640", "385");
        player_ = goog.dom.$("movie_player");
    }
    function deletePlayer() {
        if (player_) {
            goog.dom.removeNode(player_);
            player_ = null;
        }
    }
    function requestPlayback(videoId, opt_isFirstplayVideo) {
        var params = {
            video_id: videoId,
            playlist_name: curPlaylistName_,
            encrypted_playlist_id: curPlaylistId_,
            is_firstplay: !!opt_isFirstplayVideo,
        };
        if (opt_isFirstplayVideo) {
            params["request_uri"] = yt.getConfig("request_uri");
            params["http_referer"] = yt.getConfig("http_referer");
        }
        backend_.call_box_method(boxInfo_, params, "get_video_metadata_ajax", handleVideoMetadata.bind(null, videoId));
    }
    function pausePlayerVideo() {
        if (player_) {
            player_.pauseVideo();
        }
    }
    function stopPlayerVideo() {
        if (player_) {
            player_.stopVideo();
        }
    }
    function highlightViewButton(buttonName, isHighlighted) {
        var button = goog.dom.$(buttonName);
        if (!button) {
            return;
        }
        if (isHighlighted) {
            _addclass(button, "view-button-selected");
        } else {
            _removeclass(button, "view-button-selected");
        }
    }
    function highlightViewButtons(viewName) {
        highlightViewButton("playview-icon", viewName == "play");
        highlightViewButton("gridview-icon", viewName == "grid");
    }
    function initView(viewName) {
        curViewName_ = viewName;
        highlightViewButtons(viewName);
    }
    function selectView(viewName) {
        if (!isDomLoaded_) {
            highlightViewButtons(viewName);
            playnav.mostRecentSelectViewFunction = selectView.bind(null, viewName);
            return;
        }
        if (curViewName_ != viewName) {
            if (!destructArranger()) {
                return;
            }
            initView(viewName);
            if (viewName != "play") {
                pausePlayerVideo();
            } else {
                if (isWritePlayerPending_) {
                    disableAutoplaySwfArg();
                    writePlayer();
                }
            }
            if (!isViewUpdateRequested_) {
                isViewUpdateRequested_ = true;
                requestViewUpdate();
            }
        }
    }
    var playlistNameToTabNameMap = {
        user: "playlists",
        search: "uploads",
        season_episodes: "shows",
        season_clips: "shows",
        show: "shows",
    };
    function playlistNameToTabName(name) {
        return attemptToMap(playlistNameToTabNameMap, "", name);
    }
    function highlightTab(name) {
        var tabBar = goog.dom.$("playnav-navbar");
        if (tabBar) {
            var tabs = goog.dom.$$("a", "navbar-tab", tabBar);
            var numberOfTabs = tabs.length;
            for (var i = 0; i < numberOfTabs; i++) {
                _removeclass(tabs[i], "navbar-tab-selected");
            }
        }
        var className = playlistNameToTabName(name);
        var tab = goog.dom.$("playnav-navbar-tab-" + className);
        if (tab) {
            _addclass(tab, "navbar-tab-selected");
        }
        var arrangeLinkContainer = goog.dom.$("playnav-arrange-links");
        if (arrangeLinkContainer) {
            var arrangeLinks = goog.dom.$$("a", "channel-cmd", arrangeLinkContainer);
            var numberOfArrangeLinks = arrangeLinks.length;
            for (var j = 0; j < numberOfArrangeLinks; j++) {
                _addclass(arrangeLinks[j], "hide-link");
            }
        }
        var arrangeLink = goog.dom.$("arrange-link-" + className);
        if (arrangeLink) {
            _removeclass(arrangeLink, "hide-link");
        }
    }
    function initTab(name) {
        curPlaylistName_ = name;
        highlightTab(curPlaylistName_);
        switch (name) {
            case "uploads":
            case "favorites":
            case "all":
                curPlaylistId_ = null;
        }
    }
    function selectTab(tabName, opt_suppressUpdate, opt_preserveArranger) {
        if (!isDomLoaded_) {
            highlightTab(tabName);
            if (!opt_suppressUpdate) {
                playnav.mostRecentSelectTabFunction = selectTab.bind(null, tabName, opt_suppressUpdate);
            }
            return;
        }
        if (opt_preserveArranger && tabName == playlistNameToTabName(curPlaylistId_) && arranger_) {
            arrangerRestoreParams_ = arranger_.save(true);
            arrangerOpenRequested_ = tabName;
            destructArranger(true);
        } else {
            if (!destructArranger()) {
                return;
            }
        }
        initTab(tabName);
        if (!opt_suppressUpdate && !isViewUpdateRequested_) {
            isViewUpdateRequested_ = true;
            requestViewUpdate();
        }
    }
    function selectPlaylist(playlistName, opt_playlistId, opt_searchQuery) {
        curPlaylistId_ = opt_playlistId;
        curSearchQuery_ = opt_searchQuery;
        selectTab(playlistName);
    }
    function runPanelScriptsLater(name) {
        return function () {
            run_scripts_in_el("playnav-panel-" + name);
        };
    }
    function handlePanelLoaded(name, panel) {
        return function (data) {
            var html = data.html ? data.html : data;
            var d = goog.dom.$("threed-view-option-div");
            if (html && html.indexOf && html.indexOf("threed-view-option-div") > -1 && d) {
                d.parentNode.removeChild(d);
            }
            panel.innerHTML = html;
            var scrollable = _hasclass(panel, "scrollable");
            goog.dom.$("playnav-video-panel-inner").style.overflow = scrollable ? "auto" : "hidden";
            if (data.css) {
                var styleElement = document.createElement("style");
                styleElement.setAttribute("type", "text/css");
                if (styleElement.styleSheet) {
                    styleElement.styleSheet.cssText = data.css;
                } else {
                    styleElement.appendChild(document.createTextNode(data.css));
                }
                document.getElementsByTagName("head")[0].appendChild(styleElement);
            }
            if (data.js) {
                var scriptElement = document.createElement("script");
                scriptElement.text = data.js;
                document.getElementsByTagName("head")[0].appendChild(scriptElement);
            }
            if (data.js_exec) {
                eval(data.js_exec);
            }
            window.setTimeout(runPanelScriptsLater(name), 0);
        };
    }
    function selectPanel(name, opt_params, opt_dont_hide) {
        isAutoplay_ = name.search(/info|favorite/) >= 0;
        var panel = goog.dom.$("playnav-panel-" + name);
        var panelTab = goog.dom.$("playnav-panel-tab-" + name);
        if (!panel || !panelTab) return;
        _removeclass(goog.dom.$("playnav-panel-tab-" + curPanelName_), "panel-tab-selected");
        _addclass(panelTab, "panel-tab-selected");
        if (!opt_dont_hide) {
            goog.dom.$("playnav-panel-" + curPanelName_).style.display = "none";
        }
        removePoppedElements();
        panel.style.display = "block";
        curPanelName_ = name;
        if (name == "info") {
            handlePanelLoaded(name, panel)(infoPanelData);
            return;
        }
        if (!opt_dont_hide) {
            panel.innerHTML = goog.dom.$("playnav-spinny-graphic").innerHTML;
        }
        var params = {
            video_id: curVideoId_,
            playlist_id: curPlaylistId_,
            playlist_name: curPlaylistName_,
        };
        if (name == "info") {
            params["video_index"] = curVideoIndex_;
        }
        if (curSelection_) {
            var _tmp = goog.dom.$("ID2POST-" + curSelection_.id);
            if (_tmp) {
                params["comment"] = _tmp.attributes["name"].value;
            }
        }
        if (opt_params) {
            for (n in opt_params) {
                params[n] = opt_params[n];
            }
        }
        backend_.call_box_method(boxInfo_, params, "load_popup_" + name, handlePanelLoaded(name, panel));
    }
    function updateViewOnly() {
        isViewUpdateRequested_ = false;
        if (curViewName_ == "play") {
            setViewElementStyle("playnav-playview", "display", "block");
            setViewElementStyle("playnav-gridview", "display", "none");
            hideCachedPages("grid");
            var verifyCoverDiv = goog.dom.$("playnav-player-restricted");
            var isVerifyCoverVisible = verifyCoverDiv && verifyCoverDiv.style.display != "none";
            goog.dom.$("playnav-player").style.visibility = isVerifyCoverVisible ? "hidden" : "visible";
        } else {
            goog.dom.$("playnav-player").style.visibility = "hidden";
            setViewElementStyle("playnav-playview", "display", "none");
            setViewElementStyle("playnav-gridview", "display", "block");
            hideCachedPages("play");
        }
    }
    function updateTab() {
        switch (curPlaylistName_) {
            case "user":
            case "show":
            case "season_clips":
            case "season_episodes":
                loadPlaylist(curPlaylistName_, curPlaylistId_);
                break;
            case "search":
                loadPlaylist(curPlaylistName_, null, curSearchQuery_);
                break;
            case "uploads":
                clearSearchQueryFields();
                loadPlaylist(curPlaylistName_);
                break;
            case "favorites":
            case "all":
            case "recent":
            case "playlists":
            case "topics":
            case "shows":
                loadPlaylist(curPlaylistName_);
                break;
        }
    }
    function updateView() {
        if (isViewUpdateRequested_) {
            updateViewOnly();
        }
        updateTab();
        if (isViewUpdateRequested_) {
            isViewUpdateRequested_ = false;
            isViewUpdatePending_ = true;
            setTimeout(updateViewLater(), 100);
        } else {
            isViewUpdatePending_ = false;
        }
    }
    function updateViewLater() {
        return function () {
            updateView();
        };
    }
    function requestViewUpdate() {
        if (!isViewUpdatePending_) {
            isViewUpdatePending_ = true;
            setTimeout(updateViewLater(), 100);
        } else {
            isViewUpdateRequested_ = true;
        }
    }
    function clearSearchQueryFields() {
        var base = "upload_search_query-";
        Iter(["grid", "play"]).each(function (post) {
            try {
                goog.dom.$(base + post).value = "";
            } catch (e) {}
        });
    }
    var updateScrollbox = function (id) {
        var box = goog.dom.$(id);
        if (!box) return;
        if (!_hasclass(box, "outer-scrollbox")) {
            box = goog.dom.$$("div", "outer-scrollbox", box)[0];
            if (!box) return;
        }
        var top = box.scrollTop;
        var bottom = top + box.offsetHeight;
        top -= OVERSCROLL;
        bottom += OVERSCROLL;
        var pages = goog.dom.$$("div", "scrollbox-page", box);
        var len = pages.length;
        for (var i = 0; i < len; i++) {
            var page = pages[i];
            var pageTop = page.offsetTop;
            var pageBottom = pageTop + page.offsetHeight;
            if ((pageTop > top && pageTop < bottom) || (pageBottom > top && pageBottom < bottom)) {
                page.style.visibility = "visible";
                if (page.className.indexOf("loaded") < 0 && page.className.indexOf("loading") < 0) {
                    var pageNum = parseInt(page.id.split("-").pop());
                    _addclass(page, "loading");
                    loadPlaylistPage(curPlaylistName_, pageNum, curPlaylistId_, curSearchQuery_);
                }
            } else {
                page.style.visibility = "hidden";
            }
        }
    };
    updateScrollbox = Thread.bind(updateScrollbox, "updatePlaynavScrollbox");
    var userAgent = navigator.userAgent.toLowerCase();
    var isIE6 = userAgent.indexOf("msie 6") != -1 && userAgent.indexOf("opera") == -1;
    var forceLayoutLater = function (el) {
        return function () {
            el.style.zoom = "1";
        };
    };
    var resizeScrollbox = function (content, height) {
        if (!content) return;
        var body = goog.dom.$$("div", "scrollbox-body", content)[0];
        if (body) {
            if (isIE6) {
                content.style.zoom = "0";
            }
            var padding = 5;
            var outerScrollbox = goog.dom.$$("div", "outer-scrollbox", body)[0];
            var scrollHeight = height - outerScrollbox.offsetTop - padding;
            body.style.height = scrollHeight + "px";
            body.style.zoom = "1";
            if (isIE6) {
                content.style.height = scrollHeight + "px";
                setTimeout(forceLayoutLater(content), 0);
            }
        }
    };
    var resizeScrollboxes = function (node) {
        var scrollboxes = goog.dom.$$("div", "scrollbox-content", node);
        for (var i = 0; i < scrollboxes.length; i++) {
            resizeScrollbox(scrollboxes[i], 595);
        }
    };
    var resizePlayviewWrapper = function () {
        setTimeout(resizePlayview, 0);
    };
    var resizePlayview = function () {
        var container = goog.dom.$("playnav-play-panel");
        var content = goog.dom.$("playnav-play-content");
        if (container.style.display == "none" || content.style.display == "none") {
            return;
        }
        content.style.height = container.offsetHeight - content.offsetTop + "px";
        resizeScrollboxes(content);
    };
    var resizeView = function () {
        if (curViewName_ == "play") {
            resizePlayview();
        } else {
            resizeScrollboxes(goog.dom.$("playnav-grid-content"));
        }
    };
    resizeScrollboxes = Thread.bind(resizeScrollboxes, "resizePlaynavScrollbox");
    function loadPlaylistPage(playlistName, pageNum, opt_playlistId, opt_searchQuery) {
        if (navigatorNotReady()) {
            return;
        }
        var method = "load_playlist_page";
        var playlistId = playlistName == "user" ? opt_playlistId : playlistName;
        var params = {
            playlist_name: playlistName,
            encrypted_playlist_id: curPlaylistId_ || "",
            query: curSearchQuery_ || "",
            encrypted_shmoovie_id: curPlaylistId_ == null ? "" : curPlaylistId_.substring(0, 11),
            page_num: pageNum,
            view: curViewName_,
            playlist_sort: curSortName_,
        };
        var sortEl = goog.dom.$([playlistId, "sort"].join("-"));
        var sort = (sortEl && sortEl.innerHTML) || "";
        backend_.call_box_method(
            boxInfo_,
            params,
            method,
            onPlaylistPageLoaded.bind(this, curViewName_, playlistId, pageNum)
        );
    }
    function onPlaylistPageLoaded(viewName, playlistId, pageNum, html) {
        var id = ["playnav", viewName, playlistId, "page", pageNum].join("-");
        var page = goog.dom.$(id);
        if (page) {
            page.innerHTML = html;
            updateEllipses(page);
            _removeclass(page, "loading");
            _addclass(page, "loaded");
            selectCurrentVideo();
            if (scrollableItemSetupCallback_) {
                scrollableItemSetupCallback_(page);
            }
        }
    }
    function hideCachedPages(opt_viewName) {
        if (!opt_viewName) {
            opt_viewName = curViewName_;
        }
        var viewNode = goog.dom.$("playnav-" + opt_viewName + "-content");
        if (!viewNode) {
            return;
        }
        var otherEls = goog.dom.$$("div", "playnav-playlist-holder", viewNode);
        var len = otherEls.length;
        for (var i = 0; i < len; i++) {
            var el = otherEls[i];
            try {
                el.style.display = "none";
            } catch (e) {}
        }
    }
    function invalidateTab(tabName) {
        invalidatedPlaylists_[tabName] = { play: true, grid: true };
    }
    var elementsToDelete = [];
    function loadPlaylist(playlistName, opt_playlistId, opt_searchQuery, opt_forceReload) {
        if (navigatorNotReady()) {
            return;
        }
        var playlistNameOrId = opt_playlistId || playlistName;
        if (opt_playlistId) {
            curPlaylistId_ = opt_playlistId;
        }
        selectTab(playlistName, true);
        updateHistory(curViewName_, curPlaylistName_, playlistNameOrId);
        var cachedEl = goog.dom.$(["playnav", curViewName_, "playlist", playlistNameOrId, "holder"].join("-"));
        var isInvalidated = invalidatedPlaylists_[playlistName] && invalidatedPlaylists_[playlistName][curViewName_];
        if (opt_forceReload || isInvalidated) {
            if (isInvalidated) {
                delete invalidatedPlaylists_[playlistName][curViewName_];
            }
            if (cachedEl) {
                elementsToDelete.push(cachedEl);
                cachedEl = null;
            }
        }
        if (cachedEl) {
            hideCachedPages();
            cachedEl.style.display = "block";
            resizeView();
            var scrollArea = goog.dom.$$("div", "outer-scrollbox", cachedEl)[0];
            if (scrollArea) scrollArea.scrollTop = 0;
            updateEllipses(cachedEl);
            selectCurrentVideo();
            showArrangerIfRequested();
        } else {
            var method = "load_playlist";
            var params = {};
            var logging = "&playlistName=" + playlistName;
            switch (playlistName) {
                case "uploads":
                    logging += "&sort=" + curSortName_;
                    break;
                case "favorites":
                case "playlists":
                case "topics":
                    break;
                case "all":
                case "recent":
                    method = "load_playlist_videos_multi";
                    break;
                case "user":
                    params["encrypted_playlist_id"] = opt_playlistId;
                    break;
                case "show":
                case "season_episodes":
                case "season_clips":
                    params["encrypted_shmoovie_id"] = opt_playlistId.substring(0, 11);
                    break;
                case "search":
                    params["query"] = opt_searchQuery || "";
                    curSearchQuery_ = opt_searchQuery;
                    break;
            }
            params["playlist_name"] = playlistName;
            params["view"] = curViewName_;
            params["playlist_sort"] = curSortName_;
            var view = curViewName_;
            backend_.call_box_method(
                boxInfo_,
                params,
                method,
                playlistLoadedResponse.bind(this, view, playlistNameOrId),
                logging
            );
            setViewLoading(curViewName_, true);
        }
    }
    function playlistLoadedResponse(viewName, playlistNameOrId, html) {
        hideCachedPages();
        Iter(elementsToDelete).each(function (el) {
            goog.dom.removeNode(el);
        });
        elementsToDelete = [];
        var viewNode = goog.dom.$(["playnav", viewName, "content"].join("-"));
        var node = document.createElement("div");
        node.className = "playnav-playlist-holder";
        node.id = ["playnav", viewName, "playlist", playlistNameOrId, "holder"].join("-");
        var old = goog.dom.$(node.id);
        if (old) {
            goog.dom.removeNode(old);
        }
        node.innerHTML = html;
        viewNode.appendChild(node);
        resizeView();
        selectCurrentVideo();
        setViewLoading(viewName, false);
        showArrangerIfRequested();
    }
    function selectCurrentVideo() {
        selectVideo(curSelection_);
    }
    function setViewLoading(view, isLoading) {
        goog.dom.$("playnav-" + view + "-loading").style.display = isLoading ? "block" : "none";
    }
    function setVideoId(videoId) {
        curVideoId_ = videoId;
    }
    function setPlaylistId(playlistId) {
        curPlaylistId_ = playlistId;
        selectTab("user", true);
    }
    function goToWatchPage() {
        window.location.href = "/watch?v=" + curVideoId_;
    }
    function selectionTargets() {
        return [
            ["playnav-video-play", curSelection_.p, curSelection_.i, curSelection_.v].join("-"),
            ["playnav-video-grid", curSelection_.p, curSelection_.i, curSelection_.v].join("-"),
            ["playnav-video-play", curSelection_.p + "-all", curSelection_.i, curSelection_.v].join("-"),
            ["playnav-video-grid", curSelection_.p + "-all", curSelection_.i, curSelection_.v].join("-"),
        ];
    }
    function selectVideoClass(classFunction) {
        return function (id) {
            var element = goog.dom.$(id);
            if (element) {
                classFunction(element, "playnav-item-selected");
            }
        };
    }
    function selectVideo(selection) {
        if (curSelection_) {
            Iter(selectionTargets()).each(selectVideoClass(_removeclass));
        }
        curSelection_ = selection;
        if (curSelection_) {
            Iter(selectionTargets()).each(selectVideoClass(_addclass));
        }
    }
    function playVideo(playlistNameOrId, videoIndex, videoId, opt_startSecs, opt_postId, opt_isFirstplayVideo) {
        isWritePlayerPending_ = false;
        var id = null;
        if (!videoIndex && opt_postId) {
            var _tmp = goog.dom.$("POST2ID-" + opt_postId);
            if (_tmp) {
                id = _tmp.attributes["name"].value;
            }
        }
        if (!id) {
            id = [curViewName_, playlistNameOrId, videoIndex, videoId].join("-");
        }
        if (!isSkipping_) {
            updateHistory("play", curPlaylistName_, playlistNameOrId, videoIndex, videoId);
        }
        closePopup();
        if (curViewName_ == "grid" && !isSkipping_) {
            selectView("play");
        }
        selectVideo({ p: playlistNameOrId.replace("-all", ""), i: videoIndex, v: videoId, id: id });
        curVideoIndex_ = videoIndex;
        curVideoId_ = videoId;
        currentPlayState_ = PlayState.UNSTARTED;
        hideAds();
        resizePlayview();
        requestPlayback(videoId, opt_isFirstplayVideo);
        if (window.groupname) {
            selectPanel("discussion");
        } else {
            selectPanel("info");
        }
        if (videoIndex != null) {
            try {
                goog.dom.$("playnav-curplaylist-count").innerHTML = goog.dom.$(
                    "playnav-playlist-" + playlistNameOrId + "-count"
                ).value;
                goog.dom.$("playnav-curplaylist-title").innerHTML = goog.dom.$(
                    "playnav-playlist-" + playlistNameOrId + "-title"
                ).innerHTML;
            } catch (e) {}
        }
        if (videoIndex == null) {
            if (goog.dom.$("playnav-curvideo-controls")) {
                goog.dom.$("playnav-curvideo-controls").style.visibility = "hidden";
            }
        } else if (curSelection_) {
        }
    }
    function hideAds() {
        function clearAndUnhide(id) {
            var el = goog.dom.$(id);
            if (el) {
                el.innerHTML = "";
                _showdiv(id);
            }
        }
        _hidediv("watch-channel-brand-div");
        _hidediv("watch-longform-ad");
        clearAndUnhide("ad300x250");
        clearAndUnhide("google_companion_ad_div");
        clearAndUnhide("instream_google_companion_ad_div");
        clearAndUnhide("watch-longform-ad-placeholder");
    }
    function getNext(id) {
        var ind = id.lastIndexOf("-");
        var ind2 = id.lastIndexOf("-", ind - 1);
        return id.slice(0, ind2 + 1) + (parseInt(id.slice(ind2 + 1, ind)) + 1);
    }
    function getPrev(id) {
        var ind = id.lastIndexOf("-");
        var ind2 = id.lastIndexOf("-", ind - 1);
        return id.slice(0, ind2 + 1) + Math.max(parseInt(id.slice(ind2 + 1, ind)) - 1, 0);
    }
    function skip(increment) {
        isSkipping_ = true;
        var currentIndex = parseInt(curSelection_.i);
        var newIndex = currentIndex + increment;
        if (newIndex < 0) return;
        var el = goog.dom.$("playnav-video-play-" + curSelection_.p + "-" + newIndex);
        if (!el) return;
        var videoId = el.innerHTML;
        playVideo(curSelection_.p, newIndex, videoId);
        isSkipping_ = false;
    }
    function skipNext() {
        skip(1);
    }
    function skipPrev() {
        skip(-1);
    }
    function playAll(playlistId, firstVideoId) {
        playVideo(playlistId, 0, firstVideoId, 0, true);
        selectPlaylist("user", playlistId);
    }
    var currentBottomPopup = null;
    var currentPopupArrow = null;
    function openBottomPopup(name, opt_params) {
        if (navigatorNotReady()) {
            return;
        }
        var popup = goog.dom.$(name + "-popup");
        popup.style.display = "";
        arrow.style.display = "";
        goog.dom.$(name + "-popup-inner").innerHTML = goog.dom.$("playnav-spinny-graphic").innerHTML;
        var callback = function (html) {
            goog.dom.$(name + "-popup-inner").innerHTML = html;
            window.setTimeout(function () {
                run_scripts_in_el("playnav-panel-" + name);
            }, 0);
        };
        var params = { video_id: curVideoId_ };
        if (opt_params) {
            for (n in opt_params) {
                params[n] = opt_params[n];
            }
        }
        backend_.call_box_method(boxInfo_, params, "load_popup_" + name, callback);
        currentBottomPopup = popup;
    }
    function closePopup() {
        if (currentBottomPopup) {
            currentBottomPopup.style.display = "none";
            currentPopupArrow.style.display = "none";
            var flag_floatie = goog.dom.$("popup_flagging_menu");
            if (flag_floatie) {
                goog.dom.removeNode(flag_floatie);
            }
        }
    }
    function searchChannel(elementId) {
        var el = goog.dom.$(elementId);
        var query = el.value;
        if (query) {
            if (arranger_) {
                arrangerRestoreParams_ = arranger_.save(true);
                arrangerOpenRequested_ = "search";
                arranger_.destruct(false);
            }
            invalidateTab("search");
            selectPlaylist("search", null, query);
        }
    }
    function clearFirstTime(inp) {
        if (!inp.__touched) {
            inp.__stored_value = inp.value;
            inp.__stored_color = inp.style.color;
            inp.value = "";
            inp.style.color = "#333";
            inp.__touched = true;
            if (!inp.onblur) {
                inp.onblur = function () {
                    if (!inp.value) {
                        inp.value = inp.__stored_value;
                        inp.style.color = inp.__stored_color;
                        inp.__touched = false;
                    }
                };
            }
        }
    }
    function onPlayerStateChange(newState) {
        switch (newState) {
            case PlayState.ENDED:
                if (currentPlayState_ == PlayState.PLAYING && isAutoplay_) {
                    currentPlayState_ = PlayState.UNSTARTED;
                    if (curSelection_.p && curSelection_.p.search(/uploads|favorites|search/) < 0) {
                        isAutoskip_ = true;
                        skipNext();
                    }
                }
                break;
            case PlayState.PLAYING:
                currentPlayState_ = newState;
                break;
        }
    }
    function onPlayerError() {
        if (isAutoskip_) {
            setTimeout(function () {
                if (isAutoskip_) {
                    skipNext();
                }
            }, AUTOSKIP_ERROR_TIMEOUT);
        }
        currentPlayState_ = PlayState.UNSTARTED;
    }
    function toggleFullVideoDescription(state) {
        var display = state ? "block" : "none";
        var displayNot = state ? "none" : "block";
        goog.dom.$("playnav-curvideo-description-more-holder").style.display = state ? "none" : "block";
        goog.dom.$("playnav-curvideo-description-less").style.display = state ? "inline" : "none";
        goog.dom.$("playnav-curvideo-description-container").style.height = state ? "auto" : "56px";
    }
    function verifyNotRequired() {
        ageVerificationRequired_ = false;
        if (curViewName_ == "play") {
            goog.dom.$("playnav-player").style.visibility = "visible";
        }
        goog.dom.$("playnav-left-panel").style.display = "block";
        goog.dom.$("playnav-player-restricted").style.display = "none";
    }
    function verifyAge(id, title, opt_url, opt_message) {
        ageVerificationRequired_ = true;
        var nextUrl = encodeURIComponent(document.location.href.toString());
        var redirectUrl = "";
        if (opt_url) {
            redirectUrl = opt_url.replace("url_placeholder", nextUrl);
            redirectUrl = redirectUrl.replace("url_encode2", encodeURIComponent(nextUrl));
        }
        deletePlayer();
        goog.dom.$("playnav-left-panel").style.display = "none";
        goog.dom.$("playnav-player").style.visibility = "hidden";
        goog.dom.$("playnav-restricted-title").innerHTML = title;
        if (opt_message) {
            goog.dom.$("playnav-custom-error-message").innerHTML = opt_message;
        }
        var overlayDiv = goog.dom.$("playnav-player-restricted");
        var textDivs = goog.dom.$$("div", "playnav-restricted-msg", overlayDiv);
        for (var i = 0; i < textDivs.length; i++) {
            textDivs[i].style.display = "none";
        }
        var anchorDivs = goog.dom.$$("a", "playnav-restricted-link", overlayDiv);
        for (var i = 0; i < anchorDivs.length; i++) {
            anchorDivs[i].href = redirectUrl;
        }
        goog.dom.$("playnav-" + id).style.display = "block";
        overlayDiv.style.display = "block";
    }
    function makeUserAction(fref) {
        return function () {
            isAutoskip_ = false;
            isAutoplay_ = true;
            fref.apply(this, arguments);
        };
    }
    function requireDomLoadedDecorator(callback) {
        return function () {
            var args = arguments;
            if (!isDomLoaded_) {
                playnav.onDomLoaded.push(function () {
                    callback.apply(this, args);
                });
            } else {
                callback.apply(this, args);
            }
        };
    }
    function destructArranger(opt_doNotShowConfirmation) {
        if (arranger_) {
            return arranger_.destruct(!opt_doNotShowConfirmation);
        }
        return true;
    }
    function saveArranger() {
        arranger_.save();
    }
    function cancelArranger() {
        arranger_.cancel();
    }
    function updateArrangerItemCount(count) {
        arranger_.updateItemCount(count);
    }
    function arrangerReady(playlistName) {
        return (
            playlistName == curPlaylistName_ && curViewName_ == "grid" && goog.dom.$("playnav-arranger-" + playlistName)
        );
    }
    function showArranger(playlistName) {
        if (!arranger_) {
            arranger_ = new Arranger(
                playlistName,
                function () {
                    arranger_ = null;
                },
                arrangerRestoreParams_
            );
        }
        arrangerOpenRequested_ = null;
        arrangerRestoreParams_ = null;
    }
    function showArrangerIfRequested() {
        if (arrangerOpenRequested_ && arrangerReady(arrangerOpenRequested_)) {
            showArranger(arrangerOpenRequested_);
        }
    }
    function toggleArranger(playlistName) {
        if (arranger_) {
            destructArranger(true);
        } else if (arrangerReady(playlistName)) {
            showArranger(playlistName);
        } else {
            arrangerOpenRequested_ = playlistName;
            selectTab(playlistName);
            selectView("grid");
        }
    }
    function sort(sortName) {
        if (arranger_) {
            arrangerRestoreParams_ = arranger_.save(true);
            arrangerOpenRequested_ = curPlaylistId_;
            arranger_.destruct(false);
        }
        curSortName_ = sortName;
        invalidateTab(curPlaylistName_);
        selectPlaylist(curPlaylistName_);
    }
    playnav["getPlayer"] = function () {
        return player_;
    };
    playnav["getPlaylistId"] = function () {
        return curPlaylistId_;
    };
    playnav["setInitialView"] = setInitialView;
    playnav["setInitialTab"] = setInitialTab;
    playnav["initDom"] = initDom;
    playnav["initPlayer"] = initPlayer;
    playnav["isInitted"] = function () {
        return player_ ? true : false;
    };
    playnav["invalidateTab"] = invalidateTab;
    playnav["setBoxInfo"] = setBoxInfo;
    playnav["selectTab"] = requireDomLoadedDecorator(selectTab);
    playnav["selectView"] = requireDomLoadedDecorator(selectView);
    playnav["openBottomPopup"] = requireDomLoadedDecorator(openBottomPopup);
    playnav["closePopup"] = closePopup;
    playnav["setVideoId"] = setVideoId;
    playnav["setPlaylistId"] = setPlaylistId;
    playnav["searchChannel"] = searchChannel;
    playnav["goToWatchPage"] = goToWatchPage;
    playnav["updateScrollbox"] = updateScrollbox;
    playnav["clearFirstTime"] = clearFirstTime;
    playnav["resizePlayview"] = requireDomLoadedDecorator(resizePlayviewWrapper);
    playnav["verifyNotRequired"] = requireDomLoadedDecorator(verifyNotRequired);
    playnav["verifyAge"] = requireDomLoadedDecorator(verifyAge);
    playnav["onPlayerStateChange"] = onPlayerStateChange;
    playnav["onPlayerError"] = onPlayerError;
    playnav["handleLocationHashUpdate"] = handleLocationHashUpdate;
    playnav["toggleFullVideoDescription"] = toggleFullVideoDescription;
    playnav["hideCachedPages"] = hideCachedPages;
    playnav["playVideo"] = requireDomLoadedDecorator(playVideo);
    playnav["loadPlaylist"] = requireDomLoadedDecorator(loadPlaylist);
    playnav["selectPlaylist"] = requireDomLoadedDecorator(loadPlaylist);
    playnav["selectPanel"] = requireDomLoadedDecorator(selectPanel);
    playnav["selectVideo"] = requireDomLoadedDecorator(selectVideo);
    playnav["playAll"] = playAll;
    playnav["skipNext"] = makeUserAction(skipNext);
    playnav["skipPrev"] = makeUserAction(skipPrev);
    playnav["sort"] = makeUserAction(sort);
    playnav["setupScrollableItems"] = setupScrollableItems;
    playnav["resizeScrollbox"] = resizeScrollboxes;
    playnav["getBoxInfo"] = function () {
        return boxInfo_;
    };
    playnav["getCurPlaylistName"] = function () {
        return playlistNameToTabName(curPlaylistName_);
    };
    playnav["getCurrentScrollboxId"] = getCurrentScrollboxId;
    playnav["saveArranger"] = saveArranger;
    playnav["cancelArranger"] = cancelArranger;
    playnav["updateArrangerItemCount"] = updateArrangerItemCount;
    playnav["toggleArranger"] = toggleArranger;
    playnav["handleVideoMetadata"] = handleVideoMetadata;
})();
function removeElementById(id) {
    var el = goog.dom.$(id);
    if (el) {
        removeElement(el);
    }
}
function removeElement(el) {
    el.parentNode.removeChild(el);
}
window.poppedElements = [];
function removePoppedElements() {
    Iter(window.poppedElements).each(function (el) {
        el.parentNode.removeChild(el);
    });
    window.poppedElements = [];
}
function popDivToTop(el) {
    el = goog.dom.$(el);
    if (!el.__popped) {
        poppedElements.push(el);
        var pos = ani.getPosition(el);
        el.style.position = "absolute";
        el.style.top = pos.y + "px";
        el.style.left = pos.x + "px";
        document.body.appendChild(el);
        el.__popped = true;
    }
}
function onChannelPlayerReady() {
    playnav.initPlayer("movie_player", window.defaultPlaylistName);
}
function submitToPlaylist() {
    var form = document.forms["addfavsform"];
    if (!form.playlist_id.value) {
        return;
    }
    if (!goog.dom.$("playlist_comment").__touched) {
        goog.dom.$("playlist_comment").value = "";
    }
    var successCallback = function (xhr) {
        playnav.selectPanel("playlists", { success: true });
    };
    var errorCallback = function (xhr) {
        playnav.selectPanel("playlists", { error: true });
    };
    if (form.playlist_id.value == "N") {
        var z = form.new_playlist_name.value.toLowerCase();
        var y = -1;
        var x = "";
        for (var i = 0; i < form.playlist_id.options.length; i++) {
            x = form.playlist_id.options[i].text;
            y = x.lastIndexOf("(") - z.length;
            y = y < 0 ? 2 : y;
            if (x.slice(0, z.length).toLowerCase() == z && y < 2) {
                form.playlist_id.selectedIndex = i;
                break;
            }
        }
    }
    yt.net.ajax.sendRequest(form.action, {
        postBody: goog.dom.forms.getFormDataString(form),
        onComplete: successCallback,
        onException: errorCallback,
    });
}
function addToPlaylistClose() {
    _showdiv("popup_playlist_result");
    window.setTimeout(playnav.closePopup, 3000);
}
function handleAddToPlaylistsChange(el) {
    if (el.value == "N") {
        goog.dom.$("new_playlist_area").style.display = "block";
    } else {
        goog.dom.$("new_playlist_area").style.display = "none";
    }
}
function update_featured(input) {
    goog.dom.$("featured_content").style.visibility = "visible";
    var feature_option = goog.dom.$("feature_" + input.value);
    if (input.checked) {
        feature_option.style.display = "";
        feature_option.disabled = false;
    } else {
        feature_option.style.display = "none";
        feature_option.disabled = true;
        if (feature_option.selected || has_selected_child(feature_option)) {
            var select = feature_option.parentNode;
            if (select.tagName.toLowerCase() != "select") select = select.parentNode;
            if (!pick_first_option(select)) {
                goog.dom.$("featured_content").style.visibility = "hidden";
            }
        }
    }
    if (input.value == "playlists") {
        goog.dom.$("arrange_playlists").style.display = input.checked ? "" : "none";
    }
    var playlists_available = 0;
    var feature_playlists = goog.dom.$("feature_playlists");
    feature_playlists.style.display = "none";
    feature_playlists.disabled = true;
    var all_playlists_option = null;
    for (var i = 1; i < feature_playlists.childNodes.length; i++) {
        if (feature_playlists.childNodes[i].value == "playlists") {
            all_playlists_option = feature_playlists.childNodes[i];
        } else if (feature_playlists.childNodes[i].style && feature_playlists.childNodes[i].style.display == "") {
            playlists_available++;
        }
    }
    if (playlists_available > 0) {
        feature_playlists.style.display = "";
        feature_playlists.disabled = false;
        if (all_playlists_option) {
            all_playlists_option.style.display = playlists_available > 1 ? "" : "none";
            all_playlists_option.disabled = playlists_available < 2;
        }
    }
    var num_displayed = 0;
    if (goog.dom.$("display_uploads").checked) num_displayed++;
    if (goog.dom.$("display_favorites").checked) num_displayed++;
    if (goog.dom.$("display_playlists").checked && playlists_available > 0) num_displayed++;
    if (num_displayed > 1) {
        goog.dom.$("display_all").disabled = false;
        _removeclass(goog.dom.$("display_all_container"), "opacity50");
    } else {
        goog.dom.$("display_all").disabled = true;
        _addclass(goog.dom.$("display_all_container"), "opacity50");
    }
}
window.update_featured = Thread.bind(update_featured);
function pick_first_option(select) {
    for (var i = 0; i < select.options.length; i++) {
        var option = select.options[i];
        if (option.style.display == "" && option.parentNode.style.display == "") {
            option.selected = true;
            return true;
        }
    }
    return false;
}
function has_selected_child(optgroup) {
    if (optgroup.tagName.toLowerCase() != "optgroup") {
        return false;
    }
    for (var i = 0; i < optgroup.childNodes.length; i++) {
        if (optgroup.childNodes[i].selected) {
            return true;
        }
    }
    return false;
}
function handleAdLoaded() {
    playnav.resizePlayview();
}
yt.www.watch.ads.handleAdLoaded = handleAdLoaded;
function checkCurrentVideo() {}
yt.www.watch.player.checkCurrentVideo = checkCurrentVideo;
window.checkCurrentVideo = checkCurrentVideo;
function checkAll(formObj, is_checked) {
    for (var i = 0; i < formObj.length; i++) {
        fldObj = formObj.elements[i];
        if (fldObj.type == "checkbox") {
            fldObj.checked = is_checked;
        }
    }
}
is_checked_all = false;
function toggleCheckAll(formObj) {
    is_checked_all = !is_checked_all;
    checkAll(formObj, is_checked_all);
}
function resetCheckAllValue(formObj, is_checked) {
    if (!is_checked) {
        main_checkbox = document.getElementById("checkall_checkbox");
        if (main_checkbox) {
            main_checkbox.checked = false;
        }
        is_checked_all = false;
    }
}
function hasStr(src, sample) {
    return src.indexOf(sample) >= 0;
}
function addIfNotInStr(src, sample) {
    if (hasStr(src, sample)) {
        return src;
    } else {
        return src + sample;
    }
}
function removeOccurances(src, sample) {
    splitstring = src.split(sample);
    tstring = "";
    for (i = 0; i < splitstring.length; i++) {
        tstring += splitstring[i];
    }
    return tstring;
}
function ghettoCheckAll(oldStr, formObj, is_checked) {
    for (var i = 0; i < formObj.length; i++) {
        fldObj = formObj.elements[i];
        if (fldObj.type == "checkbox" && fldObj.checked != is_checked) {
            fldObj.checked = is_checked;
            if (is_checked) {
                oldStr = addIfNotInStr(oldStr, fldObj.name + ",");
            } else {
                oldStr = removeOccurances(oldStr, fldObj.name + ",");
            }
        }
    }
    return oldStr;
}
function confirmSubmit(formObj, message) {
    var agree = confirm(message);
    if (agree) return true;
    else return false;
}
