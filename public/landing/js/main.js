! function(e) {
    "use strict";

    function a() {
        if (-1 != navigator.userAgent.indexOf("MSIE")) var e = /MSIE (\d+\.\d+);/;
        else e = /Trident.*rv[ :]*(\d+\.\d+)/;
        if (e.test(navigator.userAgent) && new Number(RegExp.$1) >= 9) return !0;
        return !1
    }

    function t() {
        return Math.max(e(window).width(), window.innerWidth)
    }

    function i() {
        if (m.hasClass("slideshow-background") && m.vegas({
            preload: !0,
            timer: !1,
            delay: 5e3,
            transition: "fade",
            transitionDuration: 1e3,
            slides: [{
                src: "demo/images/image-12.jpg"
            }, {
                src: "demo/images/image-2.jpg"
            }, {
                src: "demo/images/image-3.jpg"
            }, {
                src: "demo/images/image-4.jpg"
            }]
        }), m.hasClass("slideshow-zoom-background") && m.vegas({
            preload: !0,
            timer: !1,
            delay: 7e3,
            transition: "zoomOut",
            transitionDuration: 4e3,
            slides: [{
                src: "demo/images/image-12.jpg"
            }, {
                src: "demo/images/image-2.jpg"
            }, {
                src: "demo/images/image-3.jpg"
            }, {
                src: "demo/images/image-4.jpg"
            }]
        }), m.hasClass("slideshow-video-background") && m.vegas({
            preload: !0,
            timer: !1,
            delay: 5e3,
            transition: "fade",
            transitionDuration: 1e3,
            slides: [{
                src: "demo/images/image-2.jpg"
            }, {
                src: "demo/video/marine.jpg",
                video: {
                    src: ["demo/video/marine.mp4", "demo/video/marine.webm", "demo/video/marine.ogv"],
                    loop: !1,
                    mute: !0
                }
            }, {
                src: "demo/images/image-3.jpg"
            }, {
                src: "demo/images/image-4.jpg"
            }, {
                src: "demo/images/image-12.jpg"
            }]
        }), m.hasClass("kenburns-background")) {
            m.vegas({
                preload: !0,
                transition: "swirlLeft2",
                transitionDuration: 4e3,
                timer: !1,
                delay: 1e4,
                slides: [{
                    src: "demo/images/image-2.jpg",
                    valign: "top"
                }, {
                    src: "demo/images/image-3.jpg",
                    valign: "top"
                }, {
                    src: "demo/images/image-9.jpg",
                    valign: "top"
                }, {
                    src: "demo/images/image-10.jpg",
                    valign: "top"
                }],
                walk: function(e) {}
            })
        }
        if (e("#youtube-background").length > 0) {
            var a = [{
                videoURL: "0pXYp72dwl0",
                showControls: !1,
                containment: ".overlay-video",
                autoPlay: !0,
                mute: !1,
                startAt: 0,
                opacity: 1,
                loop: !1,
                showYTLogo: !1,
                realfullscreen: !0,
                addRaster: !0
            }];
            e(".player").YTPlaylist(a, !0)
        }
        if (e("#youtube-multiple-background").length > 0) {
            a = [{
                videoURL: "0pXYp72dwl0",
                showControls: !1,
                containment: ".overlay-video",
                autoPlay: !0,
                mute: !0,
                startAt: 0,
                opacity: 1,
                loop: !1,
                showYTLogo: !1,
                realfullscreen: !0,
                addRaster: !0
            }, {
                videoURL: "9d8wWcJLnFI",
                showControls: !1,
                containment: ".overlay-video",
                autoPlay: !0,
                mute: !0,
                startAt: 20,
                opacity: 1,
                loop: !1,
                showYTLogo: !1,
                realfullscreen: !0,
                addRaster: !1
            }, {
                videoURL: "nam90gorcPs",
                showControls: !1,
                containment: ".overlay-video",
                autoPlay: !0,
                mute: !0,
                startAt: 20,
                opacity: 1,
                loop: !1,
                showYTLogo: !1,
                realfullscreen: !0,
                addRaster: !0
            }];
            e(".player").YTPlaylist(a, !0)
        }(m.hasClass("mobile") && e(".video-wrapper, .player").css("display", "none"), e("#gmap-background").length) && new GMaps({
            div: "#gmap-background",
            lat: 37.752797,
            lng: -122.409132,
            zoom: 14,
            scrollwheel: !1
        }).addMarker({
            lat: 37.752797,
            lng: -122.409132
        });
        if (e("#constellation-background").length) {
            if ("undefined" == typeof particlesJS) return console.log("Constellation Background: particlesJS not Defined."), !0;
            particlesJS("constellation-background", {
                particles: {
                    number: {
                        value: 120,
                        density: {
                            enable: !0,
                            value_area: 800
                        }
                    },
                    color: {
                        value: "#ffffff"
                    },
                    shape: {
                        type: "circle",
                        stroke: {
                            width: 0,
                            color: "#000000"
                        }
                    },
                    opacity: {
                        value: 1,
                        random: !1,
                        anim: {
                            enable: !1,
                            speed: 1,
                            opacity_min: .9,
                            sync: !1
                        }
                    },
                    size: {
                        value: 3,
                        random: !0,
                        anim: {
                            enable: !1,
                            speed: 40,
                            size_min: .1,
                            sync: !1
                        }
                    },
                    line_linked: {
                        enable: !0,
                        distance: 150,
                        color: "#ffffff",
                        opacity: .9,
                        width: 1
                    },
                    move: {
                        enable: !0,
                        speed: 4,
                        random: !0
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: {
                            enable: !1,
                            mode: "grab"
                        },
                        onclick: {
                            enable: !1,
                            mode: "push"
                        },
                        resize: !0
                    },
                    modes: {
                        grab: {
                            distance: 400,
                            line_linked: {
                                opacity: 1
                            }
                        },
                        bubble: {
                            distance: 400,
                            size: 40,
                            duration: 2,
                            opacity: 8,
                            speed: 3
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
                    }
                },
                retina_detect: !0
            })
        }
        if (e("#edge-background").length) {
            if ("undefined" == typeof particlesJS) return console.log("Edge Background: particlesJS not Defined."), !0;
            particlesJS("edge-background", {
                particles: {
                    number: {
                        value: 10,
                        density: {
                            enable: !0,
                            value_area: 800
                        }
                    },
                    color: {
                        value: "#ffffff"
                    },
                    shape: {
                        type: "edge",
                        stroke: {
                            width: 0,
                            color: "#000"
                        }
                    },
                    opacity: {
                        value: .3,
                        random: !0,
                        anim: {
                            enable: !1,
                            speed: 1,
                            opacity_min: .1,
                            sync: !1
                        }
                    },
                    size: {
                        value: 170,
                        random: !0,
                        anim: {
                            enable: !0,
                            speed: 10,
                            size_min: 40,
                            sync: !1
                        }
                    },
                    line_linked: {
                        enable: !1
                    },
                    move: {
                        enable: !0,
                        speed: 5,
                        direction: "none",
                        random: !0,
                        straight: !1,
                        out_mode: "out",
                        bounce: !1,
                        attract: {
                            enable: !1,
                            rotateX: 600,
                            rotateY: 1200
                        }
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: {
                            enable: !1,
                            mode: "grab"
                        },
                        onclick: {
                            enable: !1,
                            mode: "push"
                        },
                        resize: !0
                    },
                    modes: {
                        grab: {
                            distance: 400,
                            line_linked: {
                                opacity: 1
                            }
                        },
                        bubble: {
                            distance: 400,
                            size: 40,
                            duration: 2,
                            opacity: 8,
                            speed: 3
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
                    }
                },
                retina_detect: !0
            })
        }
        if (e("#bubble-background").length) {
            if ("undefined" == typeof particlesJS) return console.log("Bubble Background: particlesJS not Defined."), !0;
            particlesJS("bubble-background", {
                particles: {
                    number: {
                        value: 10,
                        density: {
                            enable: !0,
                            value_area: 800
                        }
                    },
                    color: {
                        value: "#ffffff"
                    },
                    shape: {
                        type: "circle",
                        stroke: {
                            width: 0,
                            color: "#000"
                        }
                    },
                    opacity: {
                        value: .3,
                        random: !0,
                        anim: {
                            enable: !1,
                            speed: 1,
                            opacity_min: .1,
                            sync: !1
                        }
                    },
                    size: {
                        value: 170,
                        random: !0,
                        anim: {
                            enable: !0,
                            speed: 10,
                            size_min: 40,
                            sync: !1
                        }
                    },
                    line_linked: {
                        enable: !1
                    },
                    move: {
                        enable: !0,
                        speed: 5,
                        direction: "none",
                        random: !0,
                        straight: !1,
                        out_mode: "out",
                        bounce: !1,
                        attract: {
                            enable: !1,
                            rotateX: 600,
                            rotateY: 1200
                        }
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: {
                            enable: !1,
                            mode: "grab"
                        },
                        onclick: {
                            enable: !1,
                            mode: "push"
                        },
                        resize: !0
                    },
                    modes: {
                        grab: {
                            distance: 400,
                            line_linked: {
                                opacity: 1
                            }
                        },
                        bubble: {
                            distance: 400,
                            size: 40,
                            duration: 2,
                            opacity: 8,
                            speed: 3
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
                    }
                },
                retina_detect: !0
            })
        }
    }

    function o() {
        1024 >= t() || m.hasClass("mobile") ? k.each((function() {
            e(this).css("height", "auto")
        })) : k.each((function() {
            var a = e(this),
                t = Math.max(e(window).height(), window.innerHeight),
                i = a.find(".table-container").outerHeight() + parseInt(a.css("padding-top"), 10) + parseInt(a.css("padding-bottom"), 10);
            t >= i ? a.css("height", "100vh") : t < i && a.css("height", "auto")
        }))
    }

    function n() {
        v.off("click"), w.off("click"), 1024 >= t() || m.hasClass("mobile") ? (g.css("display", "block") && g.css("display", "none"), v.hasClass("open") && g.css("display", "block"), C.length && C.hasClass("fullpage-wrapper") && !C.hasClass("fp-destroyed") && (e.fn.fullpage.destroy("all"), f.each((function() {
            var a = e(this),
                t = a.data("animation");
            a.removeClass(t + " visible")
        }))), v.on("click", (function(a) {
            a.preventDefault(), e(this).hasClass("open") ? (g.slideUp(500), e(this).removeClass("open")) : (e(this).addClass("open"), g.slideDown(500))
        })), w.on("click", (function(a) {
            a.preventDefault();
            var t = e(this),
                i = e("[data-id='" + t.attr("href").substr(1) + "']");
            null === i && (sScroll_target = "#"), e.smoothScroll({
                offset: 0,
                easing: "swing",
                speed: 800,
                scrollTarget: i,
                preventDefault: !1
            })
        }))) : (g.css("display", "none") && g.css("display", "table"), v.hasClass("open") && v.removeClass("open"), C.length && (C.hasClass("fullpage-wrapper") && !C.hasClass("fp-destroyed") || C.fullpage({
            menu: "#menu",
            lockAnchors: !1,
            anchors: ["home", "events", "map", "il", "documentation", "design_projects", "employees", "feedback"],
            scrollingSpeed: 500,
            autoScrolling: !0,
            controlArrows: !0,
            verticalCentered: !0,
            paddingTop: "0",
            paddingBottom: "0",
            scrollOverflow: !0,
            sectionSelector: ".ed-section",
            slideSelector: ".ed-slide",
            afterLoad: function(t, i) {
                a() ? f.css({
                    display: "block",
                    visibility: "visible"
                }) : f.each((function() {
                    var a = e(this);
                    if (a.parents(".fp-section").hasClass("active")) {
                        if (!a.hasClass("visible")) {
                            var t = a.data("animation-delay"),
                                i = a.data("animation");
                            t ? setTimeout((function() {
                                a.addClass(i + " visible")
                            }), t) : a.addClass(i + " visible")
                        }
                    } else if (!a.hasClass("onstart") && a.hasClass("visible")) {
                        i = a.data("animation");
                        a.removeClass(i + " visible")
                    }
                }))
            },
            onLeave: function(e, a, t) {
                1 === a ? m.removeClass("fp-active") : m.addClass("fp-active")
            }
        }), w.on("click", (function(a) {
            a.preventDefault();
            var t = e(this).attr("href").substr(1);
            e.fn.fullpage.moveTo(t)
        }))))
    }

    function s() {
        e(".mfp-image").magnificPopup({
            type: "image",
            closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="ion-android-close"></i></button>',
            removalDelay: 300,
            mainClass: "mfp-fade"
        }), e(".mfp-gallery").each((function() {
            e(this).magnificPopup({
                delegate: "a",
                type: "image",
                gallery: {
                    enabled: !0
                },
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="ion-android-close"></i></button>',
                removalDelay: 300,
                mainClass: "mfp-fade"
            })
        })), e(".mfp-iframe").magnificPopup({
            type: "iframe",
            iframe: {
                patterns: {
                    youtube: {
                        index: "youtube.com/",
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
                },
                srcAction: "iframe_src"
            },
            closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="ion-android-close"></i></button>',
            removalDelay: 300,
            mainClass: "mfp-fade"
        }), e(".mfp-ajax").magnificPopup({
            type: "ajax",
            ajax: {
                settings: null,
                cursor: "mfp-ajax-cur",
                tError: '<a href="%url%">The content</a> could not be loaded.'
            },
            midClick: !0,
            closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="ion-android-close"></i></button>',
            removalDelay: 300,
            mainClass: "mfp-fade",
            callbacks: {
                ajaxContentAdded: function(e) {
                    initFlexslider()
                }
            }
        }), e(".open-popup-link").magnificPopup({
            type: "inline",
            midClick: !0,
            closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="ion-android-close"></i></button>',
            removalDelay: 300,
            mainClass: "mfp-zoom-in"
        })
    }

    function l() {
        return e().slick ? (e(".ed-slider").length > 0 && e(".ed-slider").each((function() {
            var a = e(this),
                t = a.attr("data-arrows"),
                i = a.attr("data-dots"),
                o = a.attr("data-fade"),
                n = a.attr("data-autoplay"),
                s = a.attr("data-autoplayspeed");
            t = "false" !== t, i = "false" !== i, o = "true" === o, n = "true" === n, s || (s = 3e3), a.slick({
                arrows: t,
                dots: i,
                fade: o,
                infinite: !0,
                slide: ".slider-item",
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: n,
                autoplaySpeed: s,
                speed: 700
            })
        })), void(e(".ed-carousel").length > 0 && e(".ed-carousel").each((function() {
            var a = e(this),
                t = a.data("arrows") || !0,
                i = a.data("dots") || !0,
                o = a.data("loop") || !1;
            a.slick({
                arrows: t,
                dots: i,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 10000,
                speed: 1200,
                waitForAnimate: false,
                slide: ".carousel-item",
                slidesToShow: 3,
                slidesToScroll: 3,
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: !0,
                        dots: !0
                    }
                }, {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }, {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            })
        }))), void(e(".ed-carousel2").length > 0 && e(".ed-carousel2").each((function() {
            var a = e(this),
                t = a.data("arrows") || !0,
                i = a.data("dots") || !0,
                o = a.data("loop") || !1;
            a.slick({
                arrows: t,
                dots: i,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 10000,
                speed: 1200,
                waitForAnimate: false,
                slide: ".carousel-item2",
                slidesToShow: 4,
                slidesToScroll: 4,
                pauseOnHover: false,
                cssEase: 'linear',
                responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: !0,
                        dots: !0
                    }
                }, {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }, {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            })
        })))) : (console.log("Slider: Slick not Defined."), !0)
    }

    function r() {
        e(".video-container").fitVids(), S.length > 0 && S.each((function() {
            var a = e(this),
                t = a.data("countdown");
            a.countdown(t, (function(e) {
                a.html(e.strftime('<div class="counter-container"><div class="counter-box first"><div class="number">%-D</div><span>Day%!d</span></div><div class="counter-box"><div class="number">%H</div><span>Hours</span></div><div class="counter-box"><div class="number">%M</div><span>Minutes</span></div><div class="counter-box last"><div class="number">%S</div><span>Seconds</span></div></div>'))
            }))
        })), e("input, textarea").placeholder(), e('[data-toggle="tooltip"]').tooltip(), e('[data-toggle="popover"]').popover()
    }

    function d() {
        e(".subscribe-form").ajaxChimp({
            callback: function(a) {
                "success" === a.result ? (e(".subscribe-result").html(a.msg).fadeIn(1e3), setTimeout((function() {
                    e(".subscribe-result").fadeOut(), e('.subscribe-form input[type="email"]').val("")
                }), 3e3)) : "error" === a.result && e(".subscribe-result").html(a.msg).fadeIn(1e3)
            },
            url: ""
        }), e('.subscribe-form input[type="email"]').focus((function() {
            e(".subscribe-result").fadeOut()
        })), e('.subscribe-form input[type="email"]').on("keydown", (function() {
            e(".subscribe-result").fadeOut()
        }))
    }

    function c() {
        var a = e(".gmap");
        (a.length > 0 && a.each((function() {
            var t = e(this).data("height");
            t && a.css("height", t)
        })), e("#gmap-contact").length) && new GMaps({
            div: "#gmap-contact",
            lat: 37.752797,
            lng: -122.409132,
            zoom: 14,
            scrollwheel: !1
        }).addMarker({
            lat: 37.752797,
            lng: -122.409132,
            title: "Lunar",
            infoWindow: {
                content: '<p class="mb-0">Cali Agency</p>'
            }
        })
    }

    function u() {
        var a = e(".contact-form");
        return a.length < 1 || void a.each((function() {
            var a = e(this),
                t = (a.attr("data-alert-type"), a.find(".contact-form-result"));
            a.find("form").validate({
                submitHandler: function(a) {
                    t.hide(), e(a).ajaxSubmit({
                        target: t,
                        dataType: "json",
                        success: function(i) {
                            t.html(i.message).fadeIn(400), "error" != i.alert && e(a).clearForm()
                        }
                    })
                }
            })
        }))
    }

    function p() {
        ! function(e) {
            for (var a = function e(a, t) {
                return a && (t(a) ? a : e(a.parentNode, t))
            }, t = function(e) {
                // console.log();
                if(e.target.id === 'download-button'){
                    return;
                }
                (e = e || window.event).preventDefault ? e.preventDefault() : e.returnValue = !1;
                var t = e.target || e.srcElement,
                    o = a(t, (function(e) {
                        return "ARTICLE" === e.tagName
                    }));
                if (o) {
                    for (var n, s = o.parentNode, l = o.parentNode.childNodes, r = l.length, d = 0, c = 0; c < r; c++)
                        if (1 === l[c].nodeType) {
                            if (l[c] === o) {
                                n = d;
                                break
                            }
                            d++
                        } return n >= 0 && i(n, s), !1
                }
            }, i = function(e, a, t) {
                var i, o, n, s = document.querySelectorAll(".pswp")[0];
                if (n = function(e) {
                    for (var a, t, i, o, n = e.childNodes, s = n.length, l = [], r = 0; r < s; r++) 1 === (a = n[r]).nodeType && (i = (t = a.children[0].children[0]).getAttribute("data-size").split("x"), (o = {
                        src: t.getAttribute("href"),
                        w: parseInt(i[0], 10),
                        h: parseInt(i[1], 10)
                    }).title = !0, o.el = a, a.children[0].children.length > 1 && (o.details = a.children[0].children[1].outerHTML), t.children.length > 0 && (o.msrc = t.children[0].getAttribute("src")), o.o = {
                        src: o.src,
                        w: o.w,
                        h: o.h
                    }, l.push(o));
                    return l
                }(a), o = {
                    index: e,
                    bgOpacity: .87,
                    loop: !0,
                    closeOnScroll: !1,
                    history: !1,
                    galleryUID: a.getAttribute("data-pswp-uid"),
                    focus: !1,
                    modal: !1,
                    addCaptionHTMLFn: function(e, a, t) {
                        return e.details ? (a.children[0].innerHTML = e.details, !0) : (a.children[0].innerText = "", !1)
                    },
                    closeEl: !0,
                    captionEl: !0,
                    fullscreenEl: !0,
                    zoomEl: !0,
                    shareEl: !0,
                    counterEl: !0,
                    arrowEl: !0,
                    preloaderEl: !0
                }, !isNaN(o.index)) {
                    t && (o.showAnimationDuration = 0), i = new PhotoSwipe(s, PhotoSwipeUI_Default, n, o);
                    var l, r, d = !1,
                        c = !0;
                    i.listen("beforeResize", (function() {
                        var e = window.devicePixelRatio ? window.devicePixelRatio : 1;
                        e = Math.min(e, 2.5), (l = i.viewportSize.x * e) >= 1200 || !i.likelyTouchDevice && l > 800 || screen.width > 1200 ? d || (d = !0, r = !0) : d && (d = !1, r = !0), r && !c && i.invalidateCurrItems(), c && (c = !1), r = !1
                    })), i.listen("gettingData", (function(e, a) {
                        a.src = a.o.src, a.w = a.o.w, a.h = a.o.h
                    })), i.init()
                }
            }, o = document.querySelectorAll(e), n = 0, s = o.length; n < s; n++) o[n].setAttribute("data-pswp-uid", n + 1), o[n].onclick = t;
            var l = function() {
                var e = window.location.hash.substring(1),
                    a = {};
                if (e.length < 5) return a;
                for (var t = e.split("&"), i = 0; i < t.length; i++)
                    if (t[i]) {
                        var o = t[i].split("=");
                        o.length < 2 || (a[o[0]] = o[1])
                    } return a.gid && (a.gid = parseInt(a.gid, 10)), a
            }();
            l.pid && l.gid && i(l.pid, o[l.gid - 1], !0, !0)
        }(".portfolio-gallery")
    }
    var m = e("body"),
        f = e(".animated"),
        g = e("nav.header-nav"),
        v = (e("nav.header-nav li"), e('nav.header-nav li a[href="#home"]'), e(".nav-toggle")),
        b = e("#preloader"),
        h = 350,
        y = 800,
        w = e("a.moveto"),
        k = e(".section.fullscreen-element"),
        C = e("#ed-fullpage"),
        S = e(".countdown[data-countdown]");
    /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && m.addClass("mobile"), e(window).on("load", (function() {
        o(), b.delay(h).fadeOut(y), m.hasClass("mobile") || (a() ? f.css({
            display: "block",
            visibility: "visible"
        }) : e(".onstart").each((function() {
            var a = e(this);
            if (!a.hasClass("visible")) {
                var t = a.data("animation-delay"),
                    i = a.data("animation");
                t ? setTimeout((function() {
                    a.addClass(i + " visible")
                }), t) : a.addClass(i + " visible")
            }
        }))), m.addClass("loaded")
    })), jQuery(document).ready((function(e) {
        i(), n(), s(), l(), r(), d(), c(), u(), p()
    })), e(window).on("resize", (function() {
        n(), o()
    }))
}(jQuery);