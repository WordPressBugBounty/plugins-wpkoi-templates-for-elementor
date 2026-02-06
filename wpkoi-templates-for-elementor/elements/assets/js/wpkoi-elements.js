!(function (e, t) {
    "use strict";
    var i = function (e, t) {
            var i;
            return function (n) {
                i && clearTimeout(i),
                    (i = setTimeout(function () {
                        t.call(this, n), (i = null);
                    }, e));
            };
        },
        n = function (e, t) {
            var i = Object.keys(e),
                n = i.indexOf(t),
                o = (n += 1);
            return !(o >= i.length) && i[o];
        },
        o = function (e, t) {
            var i = Object.keys(e),
                n = i.indexOf(t),
                o = (n -= 1);
            return !(0 > n) && i[o];
        },
        a = function () {
            var e,
                t = !1;
            return (
                (e = navigator.userAgent || navigator.vendor || window.opera),
                (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(
                    e
                ) ||
                    /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
                        e.substr(0, 4)
                    )) &&
                    (t = !0),
                t
            );
        },
        r = {
            init: function () {
                var i = {
                    "wpkoi-animated-text.default": r.widgetAnimatedText,
                };
                e.each(i, function (e, i) {
                    t.hooks.addAction("frontend/element_ready/" + e, i);
                });
            },
            
            widgetAnimatedText: function (e) {
                var t,
                    i = e.find(".wpkoi-animated-text");
                i.length && ((t = i.data("settings")), new wpkoiAnimatedText(i, t).init());
            },
        };
    e(window).on("elementor/frontend/init", r.init);
	class TextScramble {
	  constructor(el) {
		this.el = el;
		this.chars = '!<>-_\\/[]{}—=+*^?#_____';
		this.update = this.update.bind(this);
	  }
	  setText(newText) {
		const oldText = this.el.innerText;
		const length = Math.max(oldText.length, newText.length);
		const promise = new Promise(resolve => this.resolve = resolve);
		this.queue = [];
		for (let i = 0; i < length; i++) {
		  const from = oldText[i] || '';
		  const to = newText[i] || '';
		  const start = Math.floor(Math.random() * 40);
		  const end = start + Math.floor(Math.random() * 40);
		  this.queue.push({
			from,
			to,
			start,
			end
		  });
		}
		cancelAnimationFrame(this.frameRequest);
		this.frame = 0;
		this.update();
		return promise;
	  }
	  update() {
		let output = '';
		let complete = 0;
		for (let i = 0, n = this.queue.length; i < n; i++) {
		  let {
			from,
			to,
			start,
			end,
			char
		  } = this.queue[i];
		  if (this.frame >= end) {
			complete++;
			output += to;
		  } else if (this.frame >= start) {
			if (!char || Math.random() < 0.28) {
			  char = this.randomChar();
			  this.queue[i].char = char;
			}
			output += `<span class="dud">${char}</span>`;
		  } else {
			output += from;
		  }
		}
		this.el.innerHTML = output;
		if (complete === this.queue.length) {
		  this.resolve();
		} else {
		  this.frameRequest = requestAnimationFrame(this.update);
		  this.frame++;
		}
	  }
	  randomChar() {
		return this.chars[Math.floor(Math.random() * this.chars.length)];
	  }
	};
    (window.wpkoiAnimatedText = function (t, i) {
            var n,
                o = this,
                a = e(".wpkoi-animated-text__animated-text", t),
                r = e(".wpkoi-animated-text__animated-text-item", a),
                s = null,
                c = ((i = i || {}), 0);
            (n = { effect: "fx1", delay: 3e3 }),
                e.extend(n, i),
                (o.avaliableEffects = {
                    fx1: {
                        in: {
                            duration: 1e3,
                            delay: function (e, t) {
                                return 75 + 100 * t;
                            },
                            easing: "easeOutElastic",
                            elasticity: 650,
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            translateY: ["100%", "0%"],
                        },
                        out: {
                            duration: 300,
                            delay: function (e, t) {
                                return 40 * t;
                            },
                            easing: "easeInOutExpo",
                            opacity: 0,
                            translateY: "-100%",
                        },
                    },
                    fx2: {
                        in: {
                            duration: 800,
                            delay: function (e, t) {
                                return 50 * t;
                            },
                            easing: "easeOutElastic",
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            translateY: function (e, t) {
                                return t % 2 == 0 ? ["-80%", "0%"] : ["80%", "0%"];
                            },
                        },
                        out: {
                            duration: 300,
                            delay: function (e, t) {
                                return 20 * t;
                            },
                            easing: "easeOutExpo",
                            opacity: 0,
                            translateY: function (e, t) {
                                return t % 2 == 0 ? "80%" : "-80%";
                            },
                        },
                    },
                    fx3: {
                        in: {
                            duration: 700,
                            delay: function (e, t) {
                                return 80 * (e.parentNode.children.length - t - 1);
                            },
                            easing: "easeOutElastic",
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            translateY: function (e, t) {
                                return t % 2 == 0 ? ["-80%", "0%"] : ["80%", "0%"];
                            },
                            rotateZ: [90, 0],
                        },
                        out: {
                            duration: 300,
                            delay: function (e, t) {
                                return 50 * (e.parentNode.children.length - t - 1);
                            },
                            easing: "easeOutExpo",
                            opacity: 0,
                            translateY: function (e, t) {
                                return t % 2 == 0 ? "80%" : "-80%";
                            },
                            rotateZ: function (e, t) {
                                return t % 2 == 0 ? -25 : 25;
                            },
                        },
                    },
                    fx4: {
                        in: {
                            duration: 700,
                            delay: function (e, t) {
                                return 550 + 50 * t;
                            },
                            easing: "easeOutQuint",
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            translateY: ["-150%", "0%"],
                            rotateY: [180, 0],
                        },
                        out: {
                            duration: 200,
                            delay: function (e, t) {
                                return 30 * t;
                            },
                            easing: "easeInQuint",
                            opacity: { value: 0, easing: "linear" },
                            translateY: "100%",
                            rotateY: -180,
                        },
                    },
                    fx5: {
                        in: {
                            duration: 250,
                            delay: function (e, t) {
                                return 200 + 25 * t;
                            },
                            easing: "easeOutCubic",
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            translateY: ["-50%", "0%"],
                        },
                        out: {
                            duration: 250,
                            delay: function (e, t) {
                                return 25 * t;
                            },
                            easing: "easeOutCubic",
                            opacity: 0,
                            translateY: "50%",
                        },
                    },
                    fx6: {
                        in: {
                            duration: 400,
                            delay: function (e, t) {
                                return 50 * t;
                            },
                            easing: "easeOutSine",
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            rotateY: [-90, 0],
                        },
                        out: {
                            duration: 200,
                            delay: function (e, t) {
                                return 50 * t;
                            },
                            easing: "easeOutSine",
                            opacity: 0,
                            rotateY: 45,
                        },
                    },
                    fx7: {
                        in: {
                            duration: 1e3,
                            delay: function (e, t) {
                                return 100 + 30 * t;
                            },
                            easing: "easeOutElastic",
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            rotateZ: function (e, t) {
                                return [anime.random(20, 40), 0];
                            },
                        },
                        out: { duration: 300, opacity: { value: [1, 0], easing: "easeOutExpo" } },
                    },
                    fx8: {
                        in: {
                            duration: 400,
                            delay: function (e, t) {
                                return 200 + 20 * t;
                            },
                            easing: "easeOutExpo",
                            opacity: 1,
                            rotateY: [-90, 0],
                            translateY: ["50%", "0%"],
                        },
                        out: {
                            duration: 250,
                            delay: function (e, t) {
                                return 20 * t;
                            },
                            easing: "easeOutExpo",
                            opacity: 0,
                            rotateY: 90,
                        },
                    },
                    fx9: {
                        in: {
                            duration: 400,
                            delay: function (e, t) {
                                return 200 + 30 * t;
                            },
                            easing: "easeOutExpo",
                            opacity: 1,
                            rotateX: [90, 0],
                        },
                        out: {
                            duration: 250,
                            delay: function (e, t) {
                                return 30 * t;
                            },
                            easing: "easeOutExpo",
                            opacity: 0,
                            rotateX: -90,
                        },
                    },
                    fx10: {
                        in: {
                            duration: 400,
                            delay: function (e, t) {
                                return 100 + 50 * t;
                            },
                            easing: "easeOutExpo",
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            rotateX: [110, 0],
                        },
                        out: {
                            duration: 250,
                            delay: function (e, t) {
                                return 50 * t;
                            },
                            easing: "easeOutExpo",
                            opacity: 0,
                            rotateX: -110,
                        },
                    },
                    fx11: {
                        in: {
                            duration: function (e, t) {
                                return anime.random(800, 1e3);
                            },
                            delay: function (e, t) {
                                return anime.random(100, 300);
                            },
                            easing: "easeOutExpo",
                            opacity: { value: [0, 1], easing: "easeOutExpo" },
                            translateY: ["-150%", "0%"],
                            rotateZ: function (e, t) {
                                return [anime.random(-50, 50), 0];
                            },
                        },
                        out: {
                            duration: function (e, t) {
                                return anime.random(200, 300);
                            },
                            delay: function (e, t) {
                                return anime.random(0, 80);
                            },
                            easing: "easeInQuart",
                            opacity: 0,
                            translateY: "50%",
                            rotateZ: function (e, t) {
                                return anime.random(-50, 50);
                            },
                        },
                    },
                    fx12: {
                        in: {
                            duration: 1,
                            delay: function (e, t) {
                                return 200 * t + anime.random(0, 200);
                            },
                            width: [
                                0,
                                function (t, i) {
                                    return e(t).width();
                                },
                            ],
                        },
                        out: {
                            duration: 1,
                            delay: function (e, t) {
                                return 100 * (e.parentNode.children.length - t - 1);
                            },
                            easing: "linear",
                            width: { value: 0 },
                        },
                    },
                    fx55: {
                        custom: true, // Flag indicating custom handling
            			duration: i.delay,
                    },
                }),
                (o.textChange = function () {
                    s && clearTimeout(s),
                        (s = setTimeout(function () {
                            var e, t;
                            (e = r.eq(c)),
                                c < r.length - 1 ? c++ : (c = 0),
                                (t = r.eq(c)),
                                o.hideText(e, i.effect, null, function (n) {
                                    e.toggleClass("visible"),
                                        o.showText(
                                            t,
                                            i.effect,
                                            function () {
                                                t.toggleClass("active"), e.toggleClass("active"), t.toggleClass("visible"), o.textChange();
                                            },
                                            null
                                        );
                                });
                        }, i.delay));
                }),
                (o.showText = function (t, i, n, a) {
					var effect = o.avaliableEffects[i];

					// Check for custom fx55 handling
					if (effect && effect.custom && i === "fx55") {
						// Extract phrases dynamically from span elements within 't'
						const phrases = [];
						e("span", t).each(function () {
							phrases.push(e(this).text()); // Get the text of each span
						});

						const fx = new TextScramble(t[0]); // Initialize TextScramble
						let counter = 0;
						
						const next = () => {
							fx.setText(phrases[counter]).then(() => {
								setTimeout(next, effect.duration);
							});
							counter = (counter + 1) % phrases.length;
						};

						next();
						if (a) a(); // Call completion callback if provided
					} else {
						// Standard animation for other effects
						var spans = [];
						e("span", t).each(function () {
							e(this).css({ width: "auto", opacity: 1, WebkitTransform: "", transform: "" });
							spans.push(this);
						});
						o.animateText(spans, "in", i, n, a);
					}
				}),
                (o.hideText = function (t, i, n, a) {
                    var r = [];
                    e("span", t).each(function () {
                        r.push(this);
                    }),
                        o.animateText(r, "out", i, n, a);
                }),
                (o.animateText = function (elements, direction, effect, begin, complete) {
					var animProps = (o.avaliableEffects[effect] || {})[direction];
					if (effect === "fx55" && o.avaliableEffects[effect].custom) return; // Skip animating for fx55 here

					animProps.targets = elements;
					animProps.begin = begin;
					animProps.complete = complete;
					anime(animProps);
				}),
                (o.init = function () {
                    var e = r.eq(c);
                    o.showText(e, i.effect, null, function () {
                        o.textChange();
                    });
                });
        })
        ;
})(jQuery, window.elementorFrontend);
