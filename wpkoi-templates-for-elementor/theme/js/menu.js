(() => {
    var e = document.querySelectorAll(".main-nav .sub-menu, .main-nav .children");
    if (
        (e &&
            e.forEach(function (e) {
                var t,
                    n = e.closest("li"),
                    s = n.querySelector('.dropdown-menu-toggle[role="button"]');
                e.id || ((t = n.id || "menu-item-" + Math.floor(1e5 * Math.random())), (e.id = t + "-sub-menu")), (s = s || n.querySelector('a[role="button"]')) && s.setAttribute("aria-controls", e.id);
            }),
        "querySelector" in document && "addEventListener" in window)
    ) {
        Element.prototype.matches || (Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector),
            Element.prototype.closest ||
                (Element.prototype.closest = function (e) {
                    var t = this;
                    if (document.documentElement.contains(this))
                        do {
                            if (t.matches(e)) return t;
                        } while (null !== (t = t.parentElement));
                    return null;
                });
        var o = function (t) {
                return Array.prototype.filter.call(t.parentNode.children, function (e) {
                    return e !== t;
                });
            },
            t = document.querySelectorAll(".menu-toggle"),
            n = document.querySelectorAll("nav .dropdown-menu-toggle"),
            s = document.querySelectorAll("nav .main-nav ul a"),
            l = document.querySelector(".mobile-menu-control-wrapper"),
            c = document.body,
            u = document.documentElement,
            d = function (e) {
                if (e && c.classList.contains("dropdown-hover")) {
                    var t = e.querySelectorAll("li.menu-item-has-children");
                    for (h = 0; h < t.length; h++)
                        t[h].querySelector(".dropdown-menu-toggle").removeAttribute("tabindex"),
                            t[h].querySelector(".dropdown-menu-toggle").setAttribute("role", "presentation"),
                            t[h].querySelector(".dropdown-menu-toggle").removeAttribute("aria-expanded"),
                            t[h].querySelector(".dropdown-menu-toggle").removeAttribute("aria-controls"),
                            t[h].querySelector(".dropdown-menu-toggle").removeAttribute("aria-label");
                }
            },
            r = function (e) {
                "false" !== e.getAttribute("aria-expanded") && e.getAttribute("aria-expanded")
                    ? (e.setAttribute("aria-expanded", "false"), e.setAttribute("aria-label", wpkoiMenu.openSubMenuLabel))
                    : (e.setAttribute("aria-expanded", "true"), e.setAttribute("aria-label", wpkoiMenu.closeSubMenuLabel));
            },
            a = function (e, t) {
                var n = "";
                if ((n = (t = t || this).getAttribute("data-nav") ? document.getElementById(t.getAttribute("data-nav")) : document.getElementById(t.closest("nav").getAttribute("id")))) {
                    var s = !1,
                        o = (t.closest(".mobile-menu-control-wrapper") && (s = !0), n.getElementsByTagName("ul")[0]);
                    if (n.classList.contains("toggled"))
                        n.classList.remove("toggled"),
                            u.classList.remove("mobile-menu-open"),
                            o && o.setAttribute("aria-hidden", "true"),
                            t.setAttribute("aria-expanded", "false"),
                            (s || (l && n.classList.contains("main-navigation"))) && l.classList.remove("toggled"),
                            d(o);
                    else {
                        n.classList.add("toggled"),
                            u.classList.add("mobile-menu-open"),
                            o && o.setAttribute("aria-hidden", "false"),
                            t.setAttribute("aria-expanded", "true"),
                            s
                                ? (l.classList.add("toggled"), l.querySelector(".search-item") && l.querySelector(".search-item").classList.contains("active") && l.querySelector(".search-item").click())
                                : l && n.classList.contains("main-navigation") && l.classList.add("toggled");
                        t = o;
                        if (t && c.classList.contains("dropdown-hover")) {
                            var r = t.querySelectorAll("li.menu-item-has-children");
                            for (h = 0; h < r.length; h++) {
                                var a = r[h].querySelector(".dropdown-menu-toggle"),
                                    i = a.closest("li").querySelector(".sub-menu, .children");
                                a.setAttribute("tabindex", "0"),
                                    a.setAttribute("role", "button"),
                                    a.setAttribute("aria-expanded", "false"),
                                    a.setAttribute("aria-controls", i.id),
                                    a.setAttribute("aria-label", wpkoiMenu.openSubMenuLabel);
                            }
                        }
                    }
                }
            };
        for (h = 0; h < t.length; h++) t[h].addEventListener("click", a, !1);
        var i = function (e, t) {
            if (((t = t || this).closest("nav").classList.contains("toggled") || u.classList.contains("slide-opened")) && !c.classList.contains("dropdown-click")) {
                e.preventDefault();
                var n,
                    t = t.closest("li");
                if ((r(t.querySelector(".dropdown-menu-toggle")), (n = t.querySelector(".sub-menu") ? t.querySelector(".sub-menu") : t.querySelector(".children")), wpkoiMenu.toggleOpenedSubMenus)) {
                    var s = o(t);
                    for (h = 0; h < s.length; h++) s[h].classList.contains("sfHover") && (s[h].classList.remove("sfHover"), s[h].querySelector(".toggled-on").classList.remove("toggled-on"), r(s[h].querySelector(".dropdown-menu-toggle")));
                }
                t.classList.toggle("sfHover"), n.classList.toggle("toggled-on");
            }
            e.stopPropagation();
        };
        for (h = 0; h < n.length; h++)
            n[h].addEventListener("click", i, !1),
                n[h].addEventListener(
                    "keypress",
                    function (e) {
                        ("Enter" !== e.key && " " !== e.key) || i(e, this);
                    },
                    !1
                );
        e = function () {
            
        };
        if ((window.addEventListener("resize", e, !1), window.addEventListener("orientationchange", e, !1), c.classList.contains("dropdown-hover")))
            for (h = 0; h < s.length; h++)
                s[h].addEventListener(
                    "click",
                    function (e) {
                        var t;
                        this.hostname !== window.location.hostname && document.activeElement.blur(),
                            (this.closest("nav").classList.contains("toggled") || u.classList.contains("slide-opened")) &&
                                ("#" === (t = this.getAttribute("href")) || "" === t) &&
                                (e.preventDefault(), (t = this.closest("li")).classList.toggle("sfHover"), (e = t.querySelector(".sub-menu"))) &&
                                e.classList.toggle("toggled-on");
                    },
                    !1
                );
        if (c.classList.contains("dropdown-hover")) {
            for (
                var m = document.querySelectorAll(".menu-bar-items .menu-bar-item > a"),
                    g = function () {
                        if (!this.closest("nav").classList.contains("toggled") && !this.closest("nav").classList.contains("slideout-navigation"))
                            for (var e = this; -1 === e.className.indexOf("main-nav"); ) "li" === e.tagName.toLowerCase() && e.classList.toggle("sfHover"), (e = e.parentElement);
                    },
                    v = function () {
                        if (!this.closest("nav").classList.contains("toggled") && !this.closest("nav").classList.contains("slideout-navigation"))
                            for (var e = this; -1 === e.className.indexOf("menu-bar-items"); ) e.classList.contains("menu-bar-item") && e.classList.toggle("sfHover"), (e = e.parentElement);
                    },
                    h = 0;
                h < s.length;
                h++
            )
                s[h].addEventListener("focus", g), s[h].addEventListener("blur", g);
            for (h = 0; h < m.length; h++) m[h].addEventListener("focus", v), m[h].addEventListener("blur", v);
        }
        if ("ontouchend" in document.documentElement && document.body.classList.contains("dropdown-hover")) {
            var f = document.querySelectorAll(".sf-menu .menu-item-has-children");
            for (h = 0; h < f.length; h++)
                f[h].addEventListener("touchend", function (e) {
                    if (!(this.closest("nav").classList.contains("toggled") || (1 !== e.touches.length && 0 !== e.touches.length) || (e.stopPropagation(), this.classList.contains("sfHover")))) {
                        (e.target !== this && e.target.parentNode !== this && !e.target.parentNode.parentNode) || e.preventDefault();
                        var e = this.closest("li"),
                            t = o(e);
                        for (h = 0; h < t.length; h++) t[h].classList.contains("sfHover") && t[h].classList.remove("sfHover");
                        this.classList.add("sfHover");
                        var n,
                            s = this;
                        document.addEventListener(
                            "touchend",
                            (n = function (e) {
                                e.stopPropagation(), s.classList.remove("sfHover"), document.removeEventListener("touchend", n);
                            })
                        );
                    }
                });
        }
    }
})();
document.querySelectorAll("div.site-branding").forEach(function(e){""===e.textContent.trim()&&(e.style.border="none")});