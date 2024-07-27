/*! For license information please see main.js.LICENSE.txt */
(() => {
    "use strict";
    var e = document.getElementById("getText"),
        t = document.getElementById("counter"),
        n = document.getElementById("actual"),
        r = document.getElementById("stripeErrorMessage"),
        o = document.getElementById("cancelProcessPaiment"),
        a = (document.getElementById("conditionalSubmit"), document.getElementsByClassName("chocoletrasPlg__wrapperCode-dataUser-form-input-price")[0]),
        i = document.getElementById("chocoTel"),
        l = document.getElementById("continuarBTN"),
        s = document.getElementById("hideDetails"),
        c = document.getElementById("backBTN"),
        u = document.getElementsByClassName("chocoletrasPlg__wrapperCode-firstHead-wrapper-ulWrapperFirst-liTable")[0],
        p = document.getElementsByClassName("chocoletrasPlg-spiner")[0],
        d = document.getElementsByClassName("chocoletrasPlg__wrapperCode-firstHead-dataUser")[0],
        f = (document.getElementsByClassName(" chocoletrasPlg__wrapperCode-payment")[0], document.getElementsByClassName("chocoletrasPlg__wrapperCode-firstHead")[0]),
        y = document.getElementsByName("chocofrase")[0],
        v = ["express", "action", "price", "chocofrase", "name", "email", "tel", "cp", "city", "address", "province", "message", "date"],
        m = document.getElementsByClassName("chocoletrasPlg__wrapperCode-dataUser-form")[0],
        b = ["ExpressActivator", "ExpressActivatorSwith", "chocoletrasPlg__wrapperCode-dataUser-form_envioNormal", "picDate"],
        g = ["reportAproblem_wrapper_triguer", "reportClosedApplyOpacity", "reportAproblem_wrapper", "reportAproblem", "reportClosed"],
        h = ["reportAproblem_wrapper_response", "reportAproblem_wrapper_response_span", "reportFormId"],
        w = ["ramdomNumberOne", "ramdomNumberTwo", "ramdomNumberResults"];


    // function calculateTotalPrice() {
    //     let totalPrice = 0;
    //     let totalCount = 0;

    //     // Calculate the price for #getText field
    //     const getTextValue = jQuery('#getText').val();
    //     if (getTextValue) {
    //         const { price, count } = E(getTextValue);
    //         totalPrice += parseFloat(price);
    //         totalCount += parseInt(count);
    //     }

    //     // Calculate the price for .fraseInput fields
    //     jQuery('.fraseInput').each(function () {
    //         const value = jQuery(this).val();
    //         const { price, count } = E(value);
    //         totalPrice += parseFloat(price);
    //         totalCount += parseInt(count);
    //     });

    //     const minPrice = parseFloat(ajax_variables.gastoMinimo);
    //     const shippingCost = parseFloat(ajax_variables.precEnvio);

    //     if (totalPrice > minPrice) {
    //         totalPrice += shippingCost;
    //     } else {
    //         totalPrice = minPrice + shippingCost;
    //     }

    //     jQuery('#ctf_form #counter').text(totalPrice.toFixed(1));
    //     jQuery('#actual').text(totalCount);
    //     jQuery('.chocoletrasPlg__wrapperCode-dataUser-form-input-price').val(totalPrice.toFixed(1));
    // }

    // jQuery('#getText').on('keyup', function () {
    //     calculateTotalPrice();
    // })
    // jQuery('.form-card .frasePanel .closeBtnTyper').click(function () {
    //     calculateTotalPrice();
    //     alert();
    // })
    document.getElementById("itemsEmaiBtn");
    var E = function (e) {
        var t = ajax_variables.precCoraz,
            n = ajax_variables.precLetra,
            r = e,
            o = Array.from(r),
            a = o.filter(function (e) {
                return "♥" === e;
            }),
            i = o.filter(function (e) {
                return "♥" != e;
            }),
            l = parseFloat(a.length) * parseFloat(t),
            s = parseFloat(i.length) * parseFloat(n);
        return { price: parseFloat(l) + parseFloat(s), count: o.length };
    },
        j = function (e, t, n, r, o) {
            if ("foward" === e)
                return (
                    (p.style.height = "100%"),
                    (p.style.opacity = 1),
                    (f.style.height = 0),
                    (d.style.height = "100%"),
                    (y.value = t),
                    void setTimeout(function () {
                        (p.style.height = 0), (p.style.opacity = 0);
                    }, 1e3)
                );
            if ("processStart" === e) return (p.style.height = "100%"), void (p.style.opacity = 1);
            if ("processEnd" !== e)
                return "goBack" === e
                    ? ((p.style.height = "100%"),
                        (p.style.opacity = 1),
                        (f.style.height = "100%"),
                        (d.style.height = 0),
                        void setTimeout(function () {
                            (p.style.height = 0), (p.style.opacity = 0);
                        }, 800))
                    : void 0;
            if (n)
                try {
                    (document.cookie = "chocol_cookie=".concat(r, "; expires=").concat(Date.now() + 6600, "; path=/")) ? location.reload() : console.error("We can not save the cookie to continue");
                } catch (e) {
                    alert("Necesitas activar el acceso a las cookies para continuar", e);
                }
        };
    const k = (function () {
        function r() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, r),
                this.init();
        }
        var o, i;
        return (
            (o = r),
            (i = [
                {
                    key: "init",
                    value: function () {
                        document.addEventListener("keyup", function (e) {
                            // calculateTotalPrice();
                        });
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(o.prototype, i),
            Object.defineProperty(o, "prototype", { writable: !1 }),
            r
        );
    })();

    const x = (function () {
        function t() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, t),
                this.init();
        }
        var n, r;
        return (
            (n = t),
            (r = [
                {
                    key: "init",
                    value: function () {
                        l.addEventListener("click", function (t) {
                            t.preventDefault(),
                                0 === e.value.length
                                    ? ((l.innerText = "Coloque una frase!"),
                                        (l.style.background = "red"),
                                        (e.value = ""),
                                        setTimeout(function () {
                                            (l.innerText = "continuar"), (l.style.background = "black");
                                        }, 1e3))
                                    : j("foward", e.value, null, null);
                        });
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(n.prototype, r),
            Object.defineProperty(n, "prototype", { writable: !1 }),
            t
        );
    })();
    function P(e, t) {
        (null == t || t > e.length) && (t = e.length);
        for (var n = 0, r = new Array(t); n < t; n++) r[n] = e[n];
        return r;
    }
    const T = (function () {
        function t() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, t),
                this.init();
        }
        var n, r;
        return (
            (n = t),
            (r = [
                {
                    key: "init",
                    value: function () {
                        m.addEventListener("submit", function (e) {
                            e.preventDefault(), j("processStart", null, null), t();
                        });
                        var t = function () {
                            var t, n, r, o = [],
                                a = (function (e, t) {
                                    var n = ("undefined" != typeof Symbol && e[Symbol.iterator]) || e["@@iterator"];
                                    if (!n) {
                                        if (Array.isArray(e) || (n = (function (e, t) {
                                            if (e) {
                                                if ("string" == typeof e) return P(e, t);
                                                var n = Object.prototype.toString.call(e).slice(8, -1);
                                                return (
                                                    "Object" === n && e.constructor && (n = e.constructor.name),
                                                    "Map" === n || "Set" === n ? Array.from(e) : "Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n) ? P(e, t) : void 0
                                                );
                                            }
                                        })(e))
                                        ) {
                                            n && (e = n);
                                            var r = 0,
                                                o = function () { };
                                            return {
                                                s: o,
                                                n: function () {
                                                    return r >= e.length ? { done: !0 } : { done: !1, value: e[r++] };
                                                },
                                                e: function (e) {
                                                    throw e;
                                                },
                                                f: o,
                                            };
                                        }
                                        throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
                                    }
                                    var a,
                                        i = !0,
                                        l = !1;
                                    return {
                                        s: function () {
                                            n = n.call(e);
                                        },
                                        n: function () {
                                            var e = n.next();
                                            return (i = e.done), e;
                                        },
                                        e: function (e) {
                                            (l = !0), (a = e);
                                        },
                                        f: function () {
                                            try {
                                                i || null == n.return || n.return();
                                            } finally {
                                                if (l) throw a;
                                            }
                                        },
                                    };
                                })(v);
                            try {
                                for (a.s(); !(n = a.n()).done;) {
                                    var i = n.value;
                                    o.push(document.getElementsByName(i));
                                }
                            } catch (e) {
                                a.e(e);
                            } finally {
                                a.f();
                            }
                            document.getElementsByName(v[11])[0].value || (t = "message=No Mensaje");
                            var l = o
                                .filter(function (t) {
                                    if ("date" == t[0].name && "" == t[0].value) {
                                        var currentDate = new Date();
                                        var year = currentDate.getFullYear();
                                        var month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
                                        var day = currentDate.getDate().toString().padStart(2, '0');
                                        t[0].value = year + '-' + month + '-' + day;
                                    }

                                    if (("express" == t[0].name && "on" == t[0].value && (r = !0), "price" == t[0].name && r)) {
                                        var n = E(e.value).price,
                                            o = ajax_variables.express,
                                            a = parseFloat(n) + parseFloat(o);
                                        t[0].value = parseFloat(a).toString().slice(0, 5);
                                    }
                                    if ("price" == t[0].name && !r) {
                                        var i = E(e.value).price;
                                        if (i < parseFloat(ajax_variables.gastoMinimo)) {
                                            var l = parseFloat(ajax_variables.gastoMinimo) + parseFloat(ajax_variables.precEnvio);
                                            t[0].value = l.toString().slice(0, 5);
                                        } else {
                                            var s = parseFloat(ajax_variables.precEnvio) + i;
                                            t[0].value = s.toString().slice(0, 5);
                                        }
                                    }
                                    return "" != t[0].value;
                                })
                                .map(function (e) {
                                    return "".concat(e[0].name, "=").concat(e[0].value);
                                })
                                .join("&"),
                                s = document.getElementById("uniqueOrderID").value;

                            var mainText = [y.value];
                            document.querySelectorAll('.fraseInput').forEach(function (input) {
                                mainText.push(input.value);
                            });

                            var chocofraseData = "chocofrase=" + encodeURIComponent(JSON.stringify(mainText));
                            l += "&" + chocofraseData + "&uoi=" + encodeURIComponent(s);

                            jQuery.ajax({
                                type: "post",
                                url: ajax_variables.ajax_url,
                                dataType: "text",
                                data: "".concat(l, "&nonce=").concat(ajax_variables.nonce, "&").concat(t),
                                error: function (e) {
                                    console.error(e);
                                },
                                success: function (e) {
                                    console.info("JSON.parse(response).Datos.Status ", e);
                                    debugger
                                    try {
                                        JSON.parse(e).Datos.Status ? j("processEnd", JSON.parse(e).Datos.amount, JSON.parse(e).Datos.cookie, JSON.parse(e).Datos.nonce, ajax_variables.haveNonce) : alert(JSON.parse(e).Datos);
                                    } catch (e) {
                                        console.error(JSON.parse(e));
                                    }
                                },
                            });
                        };

                        // m.addEventListener("submit", function (e) {
                        //     e.preventDefault();
                        //     j("processStart", null, null);
                        //     t();
                        // });
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(n.prototype, r),
            Object.defineProperty(n, "prototype", { writable: !1 }),
            t
        );
    })();
    function B(e) {
        return (
            (B =
                "function" == typeof Symbol && "symbol" == typeof Symbol.iterator
                    ? function (e) {
                        return typeof e;
                    }
                    : function (e) {
                        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e;
                    }),
            B(e)
        );
    }
    const C = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        document.getElementById("payment_strype") &&
                            document.getElementById("payment_strype").addEventListener("click", function () {
                                (p.style.height = "100%"),
                                    (p.style.opacity = 1),
                                    jQuery.ajax({
                                        type: "post",
                                        url: ajax_variables.ajax_url,
                                        dataType: "json",
                                        data: "action=stripeCreateSession&createCheckoutSession=1",
                                        error: function (e) {
                                            console.log(e);
                                        },
                                        success: function (e) {
                                            try {
                                                e &&
                                                    "object" == B(e) &&
                                                    e.id &&
                                                    e.payment_intent &&
                                                    (function (e, t, n, r) {
                                                        t
                                                            ? jQuery.ajax({
                                                                type: "post",
                                                                url: ajax_variables.ajax_url,
                                                                dataType: "json",
                                                                data: "action=saveStripeSectionId&id=".concat(e, "&intent=").concat(t),
                                                                error: function (e) {
                                                                    console.log(e),
                                                                        n && (n.classList.add("stripeErrorHiddeP"), (n.innerText = "error: idStripe no guardado en base de datos: =>".concat(e.responseText))),
                                                                        setTimeout(function () {
                                                                            (n.innerText = ""), n.classList.remove("stripeErrorHiddeP"), (r.style.height = 0), (r.style.opacity = 0);
                                                                        }, 5500);
                                                                },
                                                                success: function (t) {
                                                                    if (1 === t.result.id)
                                                                        try {
                                                                            Stripe(ajax_variables.publicKy)
                                                                                .redirectToCheckout({ sessionId: e })
                                                                                .then(function (e) {
                                                                                    console.log(e);
                                                                                });
                                                                        } catch (e) {
                                                                            console.error("Seccion id not redirect in externalPageStripe", e);
                                                                        }
                                                                    else
                                                                        n.classList.add("stripeErrorHiddeP"),
                                                                            (n.style.backgroundColor = "#f88"),
                                                                            (n.style.color = "white"),
                                                                            (n.innerText = "Ops! Algo salio mal al pasar al Checkout"),
                                                                            setTimeout(function () {
                                                                                (n.innerText = ""), n.classList.remove("stripeErrorHiddeP"), (window.location.href = "/");
                                                                            }, 7500);
                                                                },
                                                            })
                                                            : (n.classList.add("stripeErrorHiddeP"),
                                                                (n.style.backgroundColor = "#f88"),
                                                                (n.style.color = "white"),
                                                                (n.innerText = "Ops! Algo salio mal al pasar al Checkout"),
                                                                setTimeout(function () {
                                                                    (n.innerText = ""), n.classList.remove("stripeErrorHiddeP"), (window.location.href = "/");
                                                                }, 7500));
                                                    })(e.id, e.payment_intent, r, p);
                                            } catch (e) {
                                                !(function (e, t, n) {
                                                    t && e && (t.classList.add("stripeErrorHiddeP"), (t.innerText = e), (n.style.height = 0), (n.style.opacity = 0)),
                                                        setTimeout(function () {
                                                            (t.innerText = ""), t.classList.remove("stripeErrorHiddeP");
                                                        }, 7500);
                                                })(e, r, p);
                                            }
                                            (p.style.height = "100%"), (p.style.opacity = 1);
                                        },
                                    });
                            });
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const S = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        o && o.addEventListener("click", this.cancellprocess);
                    },
                },
                {
                    key: "cancellprocess",
                    value: function (e) {
                        e.preventDefault(),
                            jQuery.ajax({
                                type: "post",
                                url: ajax_variables.ajax_url,
                                dataType: "json",
                                data: "action=cancelProcess",
                                error: function (e) {
                                    console.log(e);
                                },
                                success: function (e) {
                                    !0 === e.result && ((document.cookie = "chocol_cookie=?; Secure; Max-Age=-35120; path=/"), location.reload());
                                },
                            });
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const _ = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        c.addEventListener("click", this.gobackAction);
                    },
                },
                {
                    key: "gobackAction",
                    value: function (e) {
                        e.preventDefault(), j("goBack", null, null, null);
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const I = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        var e = window.location.href;
                        if (e.split("?")[1] && e.split("?")[1].split("=")) {
                            var t = e.split("?")[1].split("=");
                            r && "payment" === t[0] && "true" === t[1]
                                ? (r.classList.add("stripeErrorHiddeP"), (r.style.backgroundColor = "#7dbf65"), (r.style.color = "white"), (r.innerText = "Enhorabuena! Gracias por confiar en Chocoletra!"))
                                : r &&
                                "paymentProcess" === t[0] &&
                                "error" === t[1] &&
                                (r.classList.add("stripeErrorHiddeP"), (r.style.backgroundColor = "red"), (r.style.color = "white"), (r.innerText = "Ops! Algo salio mal con el pago!")),
                                setTimeout(function () {
                                    location.replace(ajax_variables.plgPage), (r.innerText = ""), r.classList.remove("stripeErrorHiddeP");
                                }, 7500);
                        }
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const O = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        if (!document.getElementById("stripeLoad") || !ajax_variables.stripe) {
                            var e = document.createElement("script");
                            e.setAttribute("id", "stripeLoad"), e.setAttribute("src", "https://js.stripe.com/v3/"), document.head.appendChild(e), (document.cookie = "stripeLoaded=true; Secure; Max-Age=35120; path=/");
                        }
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const L = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        s && s.addEventListener("click", this.closeOpenPannel);
                    },
                },
                {
                    key: "closeOpenPannel",
                    value: function () {
                        u && (u.classList.contains("closedPannel") ? u.classList.remove("closedPannel") : u.classList.add("closedPannel"));
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    var A = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        i && i && i.addEventListener("keyup", this.filterCharterInTel);
                    },
                },
                {
                    key: "filterCharterInTel",
                    value: function () {
                        return console.log("work!");
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const N = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        if (document.getElementById(b[0]) && document.getElementById(b[1]) && document.getElementsByClassName(b[2][0])) {
                            var e = document.getElementById(b[0]),
                                t = document.getElementById(b[1]),
                                n = document.getElementsByClassName(b[2])[0],
                                r = document.getElementById(b[3]);
                            t.addEventListener("change", function (t) {
                                var o = new Date().getDay();
                                console.log('click');
                                (6 !== o && 0 !== o) || (alert("😓 No es posible realizar envíos expre0ss en Sabados y Domingos"), (t.currentTarget.checked = !1)),
                                    t.currentTarget.checked ? ((e.value = "on"), n.classList.add("showDatePannel"), (r.value = "")) : ((e.value = "off"), n.classList.remove("showDatePannel"));
                            });
                        }
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const F = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        var e = document.getElementsByClassName(g[0])[0],
                            t = document.getElementsByClassName(g[3])[0],
                            n = document.getElementsByClassName(g[2])[0];
                        e.addEventListener("click", function () {
                            t.classList.toggle(g[4]), n.classList.toggle(g[1]);
                        });
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    function D(e, t) {
        if (e) {
            if ("string" == typeof e) return H(e, t);
            var n = Object.prototype.toString.call(e).slice(8, -1);
            return "Object" === n && e.constructor && (n = e.constructor.name), "Map" === n || "Set" === n ? Array.from(e) : "Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n) ? H(e, t) : void 0;
        }
    }
    function H(e, t) {
        (null == t || t > e.length) && (t = e.length);
        for (var n = 0, r = new Array(t); n < t; n++) r[n] = e[n];
        return r;
    }
    const M = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init(),
                this.ajaxSendPost.bind(this),
                this.solveQuiz.bind(this);
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        var e = this,
                            t = ["nombreReport", "emailReport", "reporte"];
                        document.getElementById("reportFormId") &&
                            document.getElementById("reportFormId").addEventListener("submit", function (n) {
                                n.preventDefault();
                                var r,
                                    o = [],
                                    a = (function (e, t) {
                                        var n = ("undefined" != typeof Symbol && e[Symbol.iterator]) || e["@@iterator"];
                                        if (!n) {
                                            if (Array.isArray(e) || (n = D(e))) {
                                                n && (e = n);
                                                var r = 0,
                                                    o = function () { };
                                                return {
                                                    s: o,
                                                    n: function () {
                                                        return r >= e.length ? { done: !0 } : { done: !1, value: e[r++] };
                                                    },
                                                    e: function (e) {
                                                        throw e;
                                                    },
                                                    f: o,
                                                };
                                            }
                                            throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
                                        }
                                        var a,
                                            i = !0,
                                            l = !1;
                                        return {
                                            s: function () {
                                                n = n.call(e);
                                            },
                                            n: function () {
                                                var e = n.next();
                                                return (i = e.done), e;
                                            },
                                            e: function (e) {
                                                (l = !0), (a = e);
                                            },
                                            f: function () {
                                                try {
                                                    i || null == n.return || n.return();
                                                } finally {
                                                    if (l) throw a;
                                                }
                                            },
                                        };
                                    })(t);
                                try {
                                    for (a.s(); !(r = a.n()).done;) {
                                        var i = r.value;
                                        document.getElementsByName(i) && o.push(document.getElementsByName(i));
                                    }
                                } catch (e) {
                                    a.e(e);
                                } finally {
                                    a.f();
                                }
                                var l = o
                                    .filter(function (e) {
                                        return "" != e[0].value;
                                    })
                                    .map(function (e) {
                                        return "".concat(e[0].name, "=").concat(e[0].value);
                                    })
                                    .join("&");
                                e.ajaxSendPost(l);
                            });
                    },
                },
                {
                    key: "ajaxSendPost",
                    value: function (e) {
                        var t = document.getElementsByClassName(h[0])[0],
                            n = document.getElementsByClassName(h[1])[0],
                            r = document.getElementById(h[2]);
                        Number(this.solveQuiz()[0]) + Number(this.solveQuiz()[1]) === Number(this.solveQuiz()[2])
                            ? jQuery.ajax({
                                type: "post",
                                url: ajax_variables.ajax_url,
                                dataType: "text",
                                data: "action=reportForm&".concat(e),
                                error: function (e) {
                                    console.error(e);
                                },
                                success: function (e) {
                                    var o = 1 === Number(e.split(":")[1].replace("}", "")) || e.length < 20;
                                    if (!o)
                                        return (
                                            console.error(e),
                                            t.classList.add("responseActived"),
                                            t.classList.add("responseBad"),
                                            (n.innerText = "🤮 Lo sentimos, algo ha salido mal."),
                                            void setTimeout(function () {
                                                t.classList.remove("responseActived"), t.classList.remove("responseBad");
                                            }, 6e3)
                                        );
                                    if (o) {
                                        var a = document.getElementsByClassName(g[0])[0];
                                        return (
                                            t.classList.add("responseActived"),
                                            t.classList.add("responseOk"),
                                            (n.innerText = "🥈 Gracias por reportarnos el error."),
                                            void setTimeout(function () {
                                                t.classList.remove("responseActived"), t.classList.remove("responseOk"), r.reset(), a.click();
                                            }, 6e3)
                                        );
                                    }
                                },
                            })
                            : (t.classList.add("responseActived"),
                                t.classList.add("responseBad"),
                                (n.innerText = "🤮 Lo sentimos, Quiz incorrecto."),
                                setTimeout(function () {
                                    t.classList.remove("responseActived"), t.classList.remove("responseBad");
                                }, 6e3));
                    },
                },
                {
                    key: "solveQuiz",
                    value: function () {
                        var e,
                            t =
                                (3,
                                    (function (e) {
                                        if (Array.isArray(e)) return e;
                                    })((e = w)) ||
                                    (function (e, t) {
                                        var n = null == e ? null : ("undefined" != typeof Symbol && e[Symbol.iterator]) || e["@@iterator"];
                                        if (null != n) {
                                            var r,
                                                o,
                                                a = [],
                                                i = !0,
                                                l = !1;
                                            try {
                                                for (n = n.call(e); !(i = (r = n.next()).done) && (a.push(r.value), 3 !== a.length); i = !0);
                                            } catch (e) {
                                                (l = !0), (o = e);
                                            } finally {
                                                try {
                                                    i || null == n.return || n.return();
                                                } finally {
                                                    if (l) throw o;
                                                }
                                            }
                                            return a;
                                        }
                                    })(e) ||
                                    D(e, 3) ||
                                    (function () {
                                        throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
                                    })()),
                            n = t[0],
                            r = t[1],
                            o = t[2],
                            a = document.getElementById(o).value,
                            i = document.getElementById(n),
                            l = document.getElementById(r),
                            s = i.currentSrc.split("/"),
                            c = l.currentSrc.split("/");
                        return [
                            s
                                .filter(function (e) {
                                    return e.includes(".jpg");
                                })[0]
                                .split(".jpg")[0],
                            c
                                .filter(function (e) {
                                    return e.includes(".jpg");
                                })[0]
                                .split(".jpg")[0],
                            a,
                        ];
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const Q = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.quizIsSolved();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "quizIsSolved",
                    value: function () {
                        if (document.getElementById(w[2])) {
                            document.getElementById(w[2]);
                            var e = document.getElementById(w[0]),
                                t = document.getElementById(w[1]);
                            e.setAttribute("src", "".concat(ajax_variables.pluginUrl, "img/") + this.getRandomArbitrary()[0] + ".jpg"),
                                t.setAttribute("src", "".concat(ajax_variables.pluginUrl, "img/") + this.getRandomArbitrary()[1] + ".jpg");
                        }
                    },
                },
                {
                    key: "getRandomArbitrary",
                    value: function () {
                        var e = 9 * Math.random(),
                            t = 9 * Math.random();
                        return [Math.round(e), Math.round(t)];
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    const z = (function () {
        function e() {
            !(function (e, t) {
                if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function");
            })(this, e),
                this.init();
        }
        var t, n;
        return (
            (t = e),
            (n = [
                {
                    key: "init",
                    value: function () {
                        if (document.getElementById("chocoletrasPlg")) window.location.hostname.toString().split(".")[0], document.getElementById("chocoletrasPlg");
                    },
                },
            ]) &&
            (function (e, t) {
                for (var n = 0; n < t.length; n++) {
                    var r = t[n];
                    (r.enumerable = r.enumerable || !1), (r.configurable = !0), "value" in r && (r.writable = !0), Object.defineProperty(e, r.key, r);
                }
            })(t.prototype, n),
            Object.defineProperty(t, "prototype", { writable: !1 }),
            e
        );
    })();
    window.addEventListener("load", function () {
        (window.calculateprice = new k()),
            (window.confirmframse = new x()),
            (window.ajaxXubmitForm = new T()),
            (window.paymentStrype = new C()),
            (window.cancellprocess = new S()),
            (window.goback = new _()),
            (window.stripeShowPanel = new I()),
            (window.loadStripeV3 = new O()),
            (window.openAndCloseDetails = new L()),
            (window.filterChocoTel = new A()),
            (window.EnvioExpressSwitch = new N()),
            (window.reportAproblem = new F()),
            (window.semdInfoToSaveReport = new M()),
            (window.solveQuiz = new Q()),
            (window.FilterAccess = new z());
    });
})();
