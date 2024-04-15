
<!doctype html>
<html class="no-js" lang="en">
	<head>

		<style>span.loading-bar{display:none!important}html,html body{opacity:1!important}</style>
<style id="w3_bg_load">div:not(.w3_bg), section:not(.w3_bg), iframelazy:not(.w3_bg){background-image:none !important;}</style>
<script>
	var w3_lazy_load_by_px = 200,
		blank_image_webp_url = "https://d2pk8plgu825qi.cloudfront.net/wp-content/uploads/blank.pngw3.webp",
		google_fonts_delay_load = 1e4,
		w3_mousemoveloadimg = !1,
		w3_page_is_scrolled = !1,
		w3_lazy_load_js = 1,
		w3_excluded_js = 0;
	class w3_loadscripts {
		



		constructor(e) {
			this.triggerEvents = e, this.eventOptions = {
				passive: !0
			}, this.userEventListener = this.triggerListener.bind(this), this.lazy_trigger, this.style_load_fired, this.lazy_scripts_load_fired = 0, this.scripts_load_fired = 0, this.scripts_load_fire = 0, this.excluded_js = w3_excluded_js, this.w3_lazy_load_js = w3_lazy_load_js, this.w3_fonts = "undefined" != typeof w3_googlefont ? w3_googlefont : [], this.w3_styles = [], this.w3_scripts = {
				normal: [],
				async: [],
				defer: [],
				lazy: []
			}, this.allJQueries = []
		}


		user_events_add(e) {
			this.triggerEvents.forEach(t => window.addEventListener(t, e.userEventListener, e.eventOptions))
		}



		user_events_remove(e) {
			this.triggerEvents.forEach(t => window.removeEventListener(t, e.userEventListener, e.eventOptions))
		}



		triggerListener_on_load() {
			"loading" === document.readyState ? document.addEventListener("DOMContentLoaded", this.load_resources.bind(this)) : this.load_resources()
		}



		triggerListener() {
			this.user_events_remove(this), this.lazy_scripts_load_fired = 1, this.add_html_class("w3_user"), "loading" === document.readyState ? (document.addEventListener("DOMContentLoaded", this.load_style_resources.bind(this)), this.scripts_load_fire || document.addEventListener("DOMContentLoaded", this.load_resources.bind(this))) : (this.load_style_resources(), this.scripts_load_fire || this.load_resources())
		}



		async load_style_resources() {
			this.style_load_fired || (this.style_load_fired = !0, this.register_styles(), document.getElementsByTagName("html")[0].setAttribute("data-css", this.w3_styles.length), document.getElementsByTagName("html")[0].setAttribute("data-css-loaded", 0), this.preload_scripts(this.w3_styles), this.load_styles_preloaded())
		}



		async load_styles_preloaded() {
			setTimeout(function(e) {
				document.getElementsByTagName("html")[0].classList.contains("css-preloaded") ? e.load_styles(e.w3_styles) : e.load_styles_preloaded()
			}, 200, this)
		}



		async load_resources() {
			this.scripts_load_fired || (this.scripts_load_fired = !0, this.hold_event_listeners(), this.exe_document_write(), this.register_scripts(), this.add_html_class("w3_start"), "function" == typeof w3_events_on_start_js && w3_events_on_start_js(), this.preload_scripts(this.w3_scripts.normal), this.preload_scripts(this.w3_scripts.defer), this.preload_scripts(this.w3_scripts.async), this.wnwAnalytics(), this.wnwBoomerang(), await this.load_scripts(this.w3_scripts.normal), await this.load_scripts(this.w3_scripts.defer), await this.load_scripts(this.w3_scripts.async), await this.execute_domcontentloaded(), await this.execute_window_load(), window.dispatchEvent(new Event("w3-scripts-loaded")), this.add_html_class("w3_js"), "function" == typeof w3_events_on_end_js && w3_events_on_end_js(), this.lazy_trigger = setInterval(this.w3_trigger_lazy_script, 500, this))
		}



		async w3_trigger_lazy_script(e) {
			e.lazy_scripts_load_fired && (await e.load_scripts(e.w3_scripts.lazy), e.add_html_class("jsload"), clearInterval(e.lazy_trigger))
		}



		add_html_class(e) {
			document.getElementsByTagName("html")[0].classList.add(e)
		}



		register_scripts() {
			document.querySelectorAll("script[type=lazyload_int]").forEach(e => {
				e.hasAttribute("data-src") ? e.hasAttribute("async") && !1 !== e.async ? this.w3_scripts.async.push(e) : e.hasAttribute("defer") && !1 !== e.defer || "module" === e.getAttribute("data-w3-type") ? this.w3_scripts.defer.push(e) : this.w3_scripts.normal.push(e) : this.w3_scripts.normal.push(e)
			}), document.querySelectorAll("script[type=lazyload_ext]").forEach(e => {
				this.w3_scripts.lazy.push(e)
			})
		}




		register_styles() {
			document.querySelectorAll("link[data-href]").forEach(e => {
				this.w3_styles.push(e)
			})
		}



		async execute_script(e) {
			return await this.repaint_frame(), new Promise(t => {
				let s = document.createElement("script"),
					a;
				[...e.attributes].forEach(e => {
					let t = e.nodeName;
					"type" !== t && "data-src" !== t && ("data-w3-type" === t && (t = "type", a = e.nodeValue), s.setAttribute(t, e.nodeValue))
				}), e.hasAttribute("data-src") ? (s.setAttribute("src", e.getAttribute("data-src")), s.addEventListener("load", t), s.addEventListener("error", t)) : (s.text = e.text, t()), null !== e.parentNode && e.parentNode.replaceChild(s, e)
			})
		}



		async execute_styles(e) {
			var t;
			let s;
			return t = e, void((s = document.createElement("link")).href = t.getAttribute("data-href"), s.rel = "stylesheet", document.head.appendChild(s), t.parentNode.removeChild(t))
		}



		async load_scripts(e) {
			let t = e.shift();
			return t ? (await this.execute_script(t), this.load_scripts(e)) : Promise.resolve()
		}


		async load_styles(e) {
			let t = e.shift();
			return t ? (this.execute_styles(t), this.load_styles(e)) : "loaded"
		}



		async load_fonts(e) {
			var t = document.createDocumentFragment();
			e.forEach(e => {
				let s = document.createElement("link");
				s.href = e, s.rel = "stylesheet", t.appendChild(s)
			}), setTimeout(function() {
				document.head.appendChild(t)
			}, google_fonts_delay_load)
		}



		preload_scripts(e) {
			var t = document.createDocumentFragment(),
				s = 0,
				a = this;
			[...e].forEach(i => {
				let r = i.getAttribute("data-src"),
					n = i.getAttribute("data-href");
				if (r) {
/*
                    let d = document.createElement("link");
					d.href = r;
					console.log("d ke module ki value:"+d.getAttribute("data-w3-type") );
					if(d.getAttribute("data-w3-type") == "module") {
						d.rel="modulepreload";
					} else {
						d.rel = "preload";
					}

					d.as = "script", t.appendChild(d)
*/
				} else if (n) {
					let l = document.createElement("link");
					l.href = n;

					console.log("e ke module ki value:"+e.getAttribute("data-w3-type") );
					if(e.getAttribute("data-w3-type") == "module") {
						console.log("e ke module ki value:"+e.getAttribute("data-w3-type") );
						l.rel="modulepreload";
					} else {
						l.rel = "preload";
					}

					l.as = "style", s++, e.length == s && (l.dataset.last = 1), t.appendChild(l), l.onload = function() {
						fetch(this.href).then(e => e.blob()).then(e => {
							a.update_css_loader()
						}).catch(e => {
							a.update_css_loader()
						})
					}, l.onerror = function() {
						a.update_css_loader()
					}
				}
			}), document.head.appendChild(t)
		}





		update_css_loader() {
			document.getElementsByTagName("html")[0].setAttribute("data-css-loaded", parseInt(document.getElementsByTagName("html")[0].getAttribute("data-css-loaded")) + 1), document.getElementsByTagName("html")[0].getAttribute("data-css") == document.getElementsByTagName("html")[0].getAttribute("data-css-loaded") && document.getElementsByTagName("html")[0].classList.add("css-preloaded")
		}



		hold_event_listeners() {
			let e = {};

			function t(t, s) {
				! function(t) {
					function s(s) {
						return e[t].eventsToRewrite.indexOf(s) >= 0 ? "w3-" + s : s
					}
					e[t] || (e[t] = {
						originalFunctions: {
							add: t.addEventListener,
							remove: t.removeEventListener
						},
						eventsToRewrite: []
					}, t.addEventListener = function() {
						arguments[0] = s(arguments[0]), e[t].originalFunctions.add.apply(t, arguments)
					}, t.removeEventListener = function() {
						arguments[0] = s(arguments[0]), e[t].originalFunctions.remove.apply(t, arguments)
					})
				}(t), e[t].eventsToRewrite.push(s)
			}

			function s(e, t) {
				let s = e[t];
				Object.defineProperty(e, t, {
					get: () => s || function() {},
					set(a) {
						e["w3" + t] = s = a
					}
				})
			}
			t(document, "DOMContentLoaded"), t(window, "DOMContentLoaded"), t(window, "load"), t(window, "pageshow"), t(document, "readystatechange"), s(document, "onreadystatechange"), s(window, "onload"), s(window, "onpageshow")
		}


		
		hold_jquery(e) {
			let t = window.jQuery;
			Object.defineProperty(window, "jQuery", {
				get: () => t,
				set(s) {
					if (s && s.fn && !e.allJQueries.includes(s)) {
						s.fn.ready = s.fn.init.prototype.ready = function(t) {
							if (void 0 !== t) return e.scripts_load_fired ? e.domReadyFired ? t.bind(document)(s) : document.addEventListener("w3-DOMContentLoaded", () => t.bind(document)(s)) : t.bind(document)(s), s(document)
						};
						let a = s.fn.on;
						s.fn.on = s.fn.init.prototype.on = function() {
							if ("ready" == arguments[0]) {
								if (this[0] !== document) return a.apply(this, arguments), this;
								arguments[1].bind(document)(s)
							}
							if (this[0] === window) {
								function e(e) {
									return e.split(" ").map(e => "load" === e || 0 === e.indexOf("load.") ? "w3-jquery-load" : e).join(" ")
								}
								"string" == typeof arguments[0] || arguments[0] instanceof String ? arguments[0] = e(arguments[0]) : "object" == typeof arguments[0] && Object.keys(arguments[0]).forEach(t => {
									Object.assign(arguments[0], {
										[e(t)]: arguments[0][t]
									})[t]
								})
							}
							return a.apply(this, arguments), this
						}, e.allJQueries.push(s)
					}
					t = s
				}
			})
		}
		async execute_domcontentloaded() {
			this.domReadyFired = !0, await this.repaint_frame(), document.dispatchEvent(new Event("w3-DOMContentLoaded")), await this.repaint_frame(), window.dispatchEvent(new Event("w3-DOMContentLoaded")), await this.repaint_frame(), document.dispatchEvent(new Event("w3-readystatechange")), await this.repaint_frame(), document.w3onreadystatechange && document.w3onreadystatechange()
		}
		async execute_window_load() {
			await this.repaint_frame(), setTimeout(function() {
				window.dispatchEvent(new Event("w3-load"))
			}, 100), await this.repaint_frame(), window.w3onload && window.w3onload(), await this.repaint_frame(), this.allJQueries.forEach(e => e(window).trigger("w3-jquery-load")), window.dispatchEvent(new Event("w3-pageshow")), await this.repaint_frame(), window.w3onpageshow && window.w3onpageshow()
		}
		exe_document_write() {
			let e = new Map;
			document.write = document.writeln = function(t) {
				let s = document.currentScript,
					a = document.createRange(),
					i = s.parentElement,
					r = e.get(s);
				void 0 === r && (r = s.nextSibling, e.set(s, r));
				let n = document.createDocumentFragment();
				a.setStart(n, 0), n.appendChild(a.createContextualFragment(t)), i.insertBefore(n, r)
			}
		}
		async repaint_frame() {
			return new Promise(e => requestAnimationFrame(e))
		}
		static execute() {
			let e = new w3_loadscripts(["keydown", "mousemove", "touchmove", "touchstart", "touchend", "wheel"]);
			e.load_fonts(e.w3_fonts), e.user_events_add(e), e.excluded_js || e.hold_jquery(e), e.w3_lazy_load_js || (e.scripts_load_fire = 1, e.triggerListener_on_load());
			let t = setInterval(function e(s) {
				null != document.body && (document.body.getBoundingClientRect().top < -30 && s.triggerListener(), clearInterval(t))
			}, 500, e)
		}
		static run() {
			let e = new w3_loadscripts(["keydown", "mousemove", "touchmove", "touchstart", "touchend", "wheel"]);
			e.load_fonts(e.w3_fonts), e.user_events_add(e), e.excluded_js || e.hold_jquery(e), e.w3_lazy_load_js || (e.scripts_load_fire = 1, e.triggerListener_on_load());
			e.triggerListener();
		}
		wnwAnalytics() {
			document.querySelectorAll(".analytics").forEach(function(e) {
				trekkie.integrations = !1;
				var t = document.createElement("script");
				t.innerHTML = e.innerHTML, e.parentNode.insertBefore(t, e.nextSibling), e.parentNode.removeChild(e)
			})
		}
		wnwBoomerang() {
			document.querySelectorAll(".boomerang").forEach(function(e) {
				window.BOOMR.version = !1;
				var t = document.createElement("script");
				t.innerHTML = e.innerHTML, e.parentNode.insertBefore(t, e.nextSibling), e.parentNode.removeChild(e)
			})
			setTimeout(function() {
				document.querySelectorAll(".critical2").forEach(function(a) {
					a.remove();
				});
			}, 8000);
		}
	}
	setTimeout(function() {
		w3_loadscripts.execute();
	}, 1000);
</script>
		 
 
				  

<script>
  // IE11 does not have support for CSS variables, so we have to polyfill them
  if (!(((window || {}).CSS || {}).supports && window.CSS.supports('(--a: 0)'))) {
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://cdn.jsdelivr.net/npm/css-vars-ponyfill@2';
    script.onload = function() {
      cssVars({});
    };

    document.getElementsByTagName('head')[0].appendChild(script);
  }
</script>


			<script>window.performance && window.performance.mark && window.performance.mark('shopify.content_for_header.start');</script>



<script type="lazyload_int" data-src="/checkouts/internal/preloads.js?permanent-domain=carthealth.myshopify.com&locale=en-US"></script>
<script type="lazyload_int" data-src="https://shop.app/checkouts/internal/preloads.js?permanent-domain=carthealth.myshopify.com&locale=en-US" crossorigin2="anonymous"></script>


 
 
 
<script data-w3-type="module" type="lazyload_int">!function(o){(o.Shopify=o.Shopify||{}).modules=!0}(window);</script>
<script>!function(o){function n(){var o=[];function n(){o.push(Array.prototype.slice.apply(arguments))}return n.q=o,n}var t=o.Shopify=o.Shopify||{};t.loadFeatures=n(),t.autoloadFeatures=n()}(window);</script>
<script>window.ShopifyPay = window.ShopifyPay || {};
window.ShopifyPay.apiHost = "shop.app\/pay";</script>
<script>
  window.Shopify = window.Shopify || {};
  if (!window.Shopify.featureAssets) window.Shopify.featureAssets = {};
  window.Shopify.featureAssets['shop-js'] = {"pay-button":["modules/client.pay-button_a063092a.en.esm.js","modules/chunk.common_a01b8c42.esm.js"],"init-shop-email-lookup-coordinator":["modules/client.init-shop-email-lookup-coordinator_41e2e190.en.esm.js","modules/chunk.common_a01b8c42.esm.js"],"init-shop-for-new-customer-accounts":["modules/client.init-shop-for-new-customer-accounts_b17df2aa.en.esm.js","modules/chunk.common_a01b8c42.esm.js"],"init-shop-user-avatar":["modules/client.init-shop-user-avatar_bf7218b7.en.esm.js","modules/chunk.common_a01b8c42.esm.js"],"init-customer-accounts":["modules/client.init-customer-accounts_903cbaed.en.esm.js","modules/chunk.common_a01b8c42.esm.js"],"init-customer-accounts-sign-up":["modules/client.init-customer-accounts-sign-up_c9dfb5ae.en.esm.js","modules/chunk.common_a01b8c42.esm.js"],"shop-pay-payment-request":["modules/client.shop-pay-payment-request_ccd27007.en.esm.js","modules/chunk.common_a01b8c42.esm.js","modules/chunk.shop-pay_d935535d.esm.js"],"login-button":["modules/client.login-button_bd75cc63.en.esm.js","modules/chunk.common_a01b8c42.esm.js"],"discount-app":["modules/client.discount-app_e9d3287d.en.esm.js","modules/chunk.common_a01b8c42.esm.js"],"payment-terms":["modules/client.payment-terms_7dc12547.en.esm.js","modules/chunk.common_a01b8c42.esm.js"]};
</script>
<script>(function() {
  function asyncLoad() {
    var urls = ["\/\/productreviews.shopifycdn.com\/embed\/loader.js?shop=carthealth.myshopify.com","\/\/api-na1.hubapi.com\/scriptloader\/v1\/21273136.js?shop=carthealth.myshopify.com","\/\/cdn.shopify.com\/proxy\/f5cebf63bd7a62314503eadd0bd64bf5f9c5955b2cec79c859dbcacea418bf76\/bingshoppingtool-t2app-prod.trafficmanager.net\/uet\/tracking_script?shop=carthealth.myshopify.com\u0026sp-cache-control=cHVibGljLCBtYXgtYWdlPTkwMA","https:\/\/www.dwin1.com\/19038.js?shop=carthealth.myshopify.com","https:\/\/static.shareasale.com\/json\/shopify\/deduplication.js?shop=carthealth.myshopify.com","https:\/\/sprout-app.thegoodapi.com\/app\/assets\/js\/badges\/cart_badge_script?shop=carthealth.myshopify.com","https:\/\/sprout-app.thegoodapi.com\/app\/badges\/product_script?shop=carthealth.myshopify.com","https:\/\/sprout-app.thegoodapi.com\/app\/assets\/js\/badges\/tree_count_banner_script?shop=carthealth.myshopify.com","https:\/\/www.linkwhisper.com\/shopify\/assets\/js\/frontend.js?shop=carthealth.myshopify.com","https:\/\/assets1.adroll.com\/shopify\/latest\/j\/shopify_rolling_bootstrap_v2.js?adroll_adv_id=MT7SYAA5D5FLPIDWU3CMRW\u0026adroll_pix_id=HRDGRJ4SVFC2TKZDJJXOB6\u0026shop=carthealth.myshopify.com"];
    for (var i = 0; i < urls.length; i++) {
      var s = document.createElement('script');
      s.type = 'text/javascript';
      s.async = true;
      s.src = urls[i];
      var x = document.getElementsByTagName('script')[0];
      x.parentNode.insertBefore(s, x);
    }
  };
  if(window.attachEvent) {
    window.attachEvent('onload', asyncLoad);
  } else {
    window.addEventListener('w3-DOMContentLoaded', asyncLoad, false);
  }
})();</script>
 




 


 <script>window.performance && window.performance.mark && window.performance.mark('shopify.content_for_header.end');</script>
 
 
 
<script>(function(){if ("sendBeacon" in navigator && "performance" in window) {var session_token = document.cookie.match(/_shopify_s=([^;]*)/);function handle_abandonment_event(e) {var entries = performance.getEntries().filter(function(entry) {return /monorail-edge.shopifysvc.com/.test(entry.name);});if (!window.abandonment_tracked && entries.length === 0) {window.abandonment_tracked = true;var currentMs = Date.now();var navigation_start = performance.timing.navigationStart;var payload = {shop_id: 56275468333,url: window.location.href,navigation_start,duration: currentMs - navigation_start,session_token: session_token && session_token.length === 2 ? session_token[1] : "",page_type: "index"};window.navigator.sendBeacon("https://monorail-edge.shopifysvc.com/v1/produce", JSON.stringify({schema_id: "online_store_buyer_site_abandonment/1.1",payload: payload,metadata: {event_created_at_ms: currentMs,event_sent_at_ms: currentMs}}));}}window.addEventListener('pagehide', handle_abandonment_event);}}());</script>
<script id="web-pixels-manager-setup">(function e(e,n,a,t,r){var o="function"==typeof BigInt&&-1!==BigInt.toString().indexOf("[native code]")?"modern":"legacy";window.Shopify=window.Shopify||{};var i=window.Shopify;i.analytics=i.analytics||{};var s=i.analytics;s.replayQueue=[],s.publish=function(e,n,a){return s.replayQueue.push([e,n,a]),!0};try{self.performance.mark("wpm:start")}catch(e){}var l=[a,"/wpm","/b",r,o.substring(0,1),".js"].join("");!function(e){var n=e.src,a=e.async,t=void 0===a||a,r=e.onload,o=e.onerror,i=document.createElement("script"),s=document.head,l=document.body;i.async=t,i.src=n,r&&i.addEventListener("load",r),o&&i.addEventListener("error",o),s?s.appendChild(i):l?l.appendChild(i):console.error("Did not find a head or body element to append the script")}({src:l,async:!0,onload:function(){var a=window.webPixelsManager.init(e);n(a);var t=window.Shopify.analytics;t.replayQueue.forEach((function(e){var n=e[0],t=e[1],r=e[2];a.publishCustomEvent(n,t,r)})),t.replayQueue=[],t.publish=a.publishCustomEvent,t.visitor=a.visitor},onerror:function(){var n=e.storefrontBaseUrl.replace(/\/$/,""),a="".concat(n,"/.well-known/shopify/monorail/unstable/produce_batch"),r=JSON.stringify({metadata:{event_sent_at_ms:(new Date).getTime()},events:[{schema_id:"web_pixels_manager_load/2.0",payload:{version:t||"latest",page_url:self.location.href,status:"failed",error_msg:"".concat(l," has failed to load")},metadata:{event_created_at_ms:(new Date).getTime()}}]});try{if(self.navigator.sendBeacon.bind(self.navigator)(a,r))return!0}catch(e){}var o=new XMLHttpRequest;try{return o.open("POST",a,!0),o.setRequestHeader("Content-Type","text/plain"),o.send(r),!0}catch(e){console&&console.warn&&console.warn("[Web Pixels Manager] Got an unhandled error while logging a load error.")}return!1}})})({shopId: 56275468333,storefrontBaseUrl: "https://carthealth.com",cdnBaseUrl: "https://carthealth.com/cdn",surface: "storefront-renderer",enabledBetaFlags: [],webPixelsConfigList: [{"id":"80183586","configuration":"{\"myshopifyDomain\":\"carthealth.myshopify.com\"}","eventPayloadVersion":"v1","runtimeContext":"STRICT","scriptVersion":"1282d524bd666027c0baee20e1a094c0","type":"APP","apiClientId":2775569,"privacyPurposes":null},{"id":"shopify-app-pixel","configuration":"{}","eventPayloadVersion":"v1","runtimeContext":"STRICT","scriptVersion":"0575","apiClientId":"shopify-pixel","type":"APP","purposes":["ANALYTICS"]},{"id":"shopify-custom-pixel","eventPayloadVersion":"v1","runtimeContext":"LAX","scriptVersion":"0575","apiClientId":"shopify-pixel","type":"CUSTOM","purposes":["ANALYTICS"]}],initData: {"cart":null,"checkout":null,"customer":null,"productVariants":[]},},function pageEvents(webPixelsManagerAPI) {webPixelsManagerAPI.publish("page_viewed");},"https://carthealth.com/cdn","0.0.447","7cbd2f7fwd7b20ec2p0bbe3509m5e05bfbb",);</script>  <script>window.ShopifyAnalytics = window.ShopifyAnalytics || {};
window.ShopifyAnalytics.meta = window.ShopifyAnalytics.meta || {};
window.ShopifyAnalytics.meta.currency = 'USD';
var meta = {"page":{"pageType":"home"}};
for (var attr in meta) {
  window.ShopifyAnalytics.meta[attr] = meta[attr];
}</script>
 
<script class="analytics">(function () {
    var customDocumentWrite = function(content) {
      var jquery = null;

      if (window.jQuery) {
        jquery = window.jQuery;
      } else if (window.Checkout && window.Checkout.$) {
        jquery = window.Checkout.$;
      }

      if (jquery) {
        jquery('body').append(content);
      }
    };

    var hasLoggedConversion = function(token) {
      if (token) {
        return document.cookie.indexOf('loggedConversion=' + token) !== -1;
      }
      return false;
    }

    var setCookieIfConversion = function(token) {
      if (token) {
        var twoMonthsFromNow = new Date(Date.now());
        twoMonthsFromNow.setMonth(twoMonthsFromNow.getMonth() + 2);

        document.cookie = 'loggedConversion=' + token + '; expires=' + twoMonthsFromNow;
      }
    }

    var trekkie = window.ShopifyAnalytics.lib = window.trekkie = window.trekkie || [];
    if (trekkie.integrations) {
      return;
    }
    trekkie.methods = [
      'identify',
      'page',
      'ready',
      'track',
      'trackForm',
      'trackLink'
    ];
    trekkie.factory = function(method) {
      return function() {
        var args = Array.prototype.slice.call(arguments);
        args.unshift(method);
        trekkie.push(args);
        return trekkie;
      };
    };
    for (var i = 0; i < trekkie.methods.length; i++) {
      var key = trekkie.methods[i];
      trekkie[key] = trekkie.factory(key);
    }
    trekkie.load = function(config) {
      trekkie.config = config || {};
      trekkie.config.initialDocumentCookie = document.cookie;
      var first = document.getElementsByTagName('script')[0];
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.onerror = function(e) {
        var scriptFallback = document.createElement('script');
        scriptFallback.type = 'text/javascript';
        scriptFallback.onerror = function(error) {
                var Monorail = {
      produce: function produce(monorailDomain, schemaId, payload) {
        var currentMs = new Date().getTime();
        var event = {
          schema_id: schemaId,
          payload: payload,
          metadata: {
            event_created_at_ms: currentMs,
            event_sent_at_ms: currentMs
          }
        };
        return Monorail.sendRequest("https://" + monorailDomain + "/v1/produce", JSON.stringify(event));
      },
      sendRequest: function sendRequest(endpointUrl, payload) {
        // Try the sendBeacon API
        if (window && window.navigator && typeof window.navigator.sendBeacon === 'function' && typeof window.Blob === 'function' && !Monorail.isIos12()) {
          var blobData = new window.Blob([payload], {
            type: 'text/plain'
          });

          if (window.navigator.sendBeacon(endpointUrl, blobData)) {
            return true;
          } // sendBeacon was not successful

        } // XHR beacon

        var xhr = new XMLHttpRequest();

        try {
          xhr.open('POST', endpointUrl);
          xhr.setRequestHeader('Content-Type', 'text/plain');
          xhr.send(payload);
        } catch (e) {
          console.log(e);
        }

        return false;
      },
      isIos12: function isIos12() {
        return window.navigator.userAgent.lastIndexOf('iPhone; CPU iPhone OS 12_') !== -1 || window.navigator.userAgent.lastIndexOf('iPad; CPU OS 12_') !== -1;
      }
    };
    Monorail.produce('monorail-edge.shopifysvc.com',
      'trekkie_storefront_load_errors/1.1',
      {shop_id: 56275468333,
      theme_id: 163013853474,
      app_name: "storefront",
      context_url: window.location.href,
      source_url: "//carthealth.com/cdn/s/trekkie.storefront.64022fdadec5c8ec4be5f67dbeb0521916405ce3.min.js"});

        };
        scriptFallback.async = true;
        scriptFallback.src = '//carthealth.com/cdn/s/trekkie.storefront.64022fdadec5c8ec4be5f67dbeb0521916405ce3.min.js';
        first.parentNode.insertBefore(scriptFallback, first);
      };
      script.async = true;
      script.src = '//carthealth.com/cdn/s/trekkie.storefront.64022fdadec5c8ec4be5f67dbeb0521916405ce3.min.js';
      first.parentNode.insertBefore(script, first);
    };
    trekkie.load(
      {"Trekkie":{"appName":"storefront","development":false,"defaultAttributes":{"shopId":56275468333,"isMerchantRequest":null,"themeId":163013853474,"themeCityHash":"14674050340071592444","contentLanguage":"en","currency":"USD"},"isServerSideCookieWritingEnabled":true,"monorailRegion":"shop_domain"},"Google Gtag Pixel":{"conversionId":"G-HJWMS5B9B1","eventLabels":[{"type":"begin_checkout","action_label":"G-HJWMS5B9B1"},{"type":"search","action_label":"G-HJWMS5B9B1"},{"type":"view_item","action_label":["G-HJWMS5B9B1","MC-CZYYJ8MF6N"]},{"type":"purchase","action_label":["G-HJWMS5B9B1","MC-CZYYJ8MF6N"]},{"type":"page_view","action_label":["G-HJWMS5B9B1","MC-CZYYJ8MF6N"]},{"type":"add_payment_info","action_label":"G-HJWMS5B9B1"},{"type":"add_to_cart","action_label":"G-HJWMS5B9B1"}],"targetCountry":"US"},"Session Attribution":{},"S2S":{"facebookCapiEnabled":false,"source":"trekkie-storefront-renderer"}}
    );

    var loaded = false;
    trekkie.ready(function() {
      if (loaded) return;
      loaded = true;

      window.ShopifyAnalytics.lib = window.trekkie;

  
      var originalDocumentWrite = document.write;
      document.write = customDocumentWrite;
      try { window.ShopifyAnalytics.merchantGoogleAnalytics.call(this); } catch(error) {};
      document.write = originalDocumentWrite;

      window.ShopifyAnalytics.lib.page(null,{"pageType":"home"});

      var match = window.location.pathname.match(/checkouts\/(.+)\/(thank_you|post_purchase)/)
      var token = match? match[1]: undefined;
      if (!hasLoggedConversion(token)) {
        setCookieIfConversion(token);
        
      }
    });


        var eventsListenerScript = document.createElement('script');
        eventsListenerScript.async = true;
        eventsListenerScript.src = "//carthealth.com/cdn/shopifycloud/shopify/assets/shop_events_listener-a7c63dba65ccddc484f77541dc8ca437e60e1e9e297fe1c3faebf6523a0ede9b.js";
        document.getElementsByTagName('head')[0].appendChild(eventsListenerScript);

})();</script>
<script class="boomerang">
(function () {
  if (window.BOOMR && (window.BOOMR.version || window.BOOMR.snippetExecuted)) {
    return;
  }
  window.BOOMR = window.BOOMR || {};
  window.BOOMR.snippetStart = new Date().getTime();
  window.BOOMR.snippetExecuted = true;
  window.BOOMR.snippetVersion = 12;
  window.BOOMR.application = "storefront-renderer";
  window.BOOMR.themeName = "Warehouse";
  window.BOOMR.themeVersion = "4.4.1";
  window.BOOMR.shopId = 56275468333;
  window.BOOMR.themeId = 163013853474;
  window.BOOMR.renderRegion = "gcp-asia-southeast1";
  window.BOOMR.url =
    "https://carthealth.com/cdn/shopifycloud/boomerang/shopify-boomerang-1.0.0.min.js";
  var where = document.currentScript || document.getElementsByTagName("script")[0];
  var parentNode = where.parentNode;
  var promoted = false;
  var LOADER_TIMEOUT = 3000;
  function promote() {
    if (promoted) {
      return;
    }
    var script = document.createElement("script");
    script.id = "boomr-scr-as";
    script.src = window.BOOMR.url;
    script.async = true;
    parentNode.appendChild(script);
    promoted = true;
  }
  function iframeLoader(wasFallback) {
    promoted = true;
    var dom, bootstrap, iframe, iframeStyle;
    var doc = document;
    var win = window;
    window.BOOMR.snippetMethod = wasFallback ? "if" : "i";
    bootstrap = function(parent, scriptId) {
      var script = doc.createElement("script");
      script.id = scriptId || "boomr-if-as";
      script.src = window.BOOMR.url;
      BOOMR_lstart = new Date().getTime();
      parent = parent || doc.body;
      parent.appendChild(script);
    };
    if (!window.addEventListener && window.attachEvent && navigator.userAgent.match(/MSIE [67]./)) {
      window.BOOMR.snippetMethod = "s";
      bootstrap(parentNode, "boomr-async");
      return;
    }
    iframe = document.createElement("IFRAME");
    iframe.src = "about:blank";
    iframe.title = "";
    iframe.role = "presentation";
    iframe.loading = "eager";
    iframeStyle = (iframe.frameElement || iframe).style;
    iframeStyle.width = 0;
    iframeStyle.height = 0;
    iframeStyle.border = 0;
    iframeStyle.display = "none";
    parentNode.appendChild(iframe);
    try {
      win = iframe.contentWindow;
      doc = win.document.open();
    } catch (e) {
      dom = document.domain;
      iframe.src = "javascript:var d=document.open();d.domain='" + dom + "';void(0);";
      win = iframe.contentWindow;
      doc = win.document.open();
    }
    if (dom) {
      doc._boomrl = function() {
        this.domain = dom;
        bootstrap();
      };
      doc.write("<body onload='document._boomrl();'>");
    } else {
      win._boomrl = function() {
        bootstrap();
      };
      if (win.addEventListener) {
        win.addEventListener("load", win._boomrl, false);
      } else if (win.attachEvent) {
        win.attachEvent("onload", win._boomrl);
      }
    }
    doc.close();
  }
  var link = document.createElement("link");
  if (link.relList &&
    typeof link.relList.supports === "function" &&
    link.relList.supports("preload") &&
    ("as" in link)) {
    window.BOOMR.snippetMethod = "p";
    link.href = window.BOOMR.url;
    link.rel = "preload";
    link.as = "script";
    link.addEventListener("load", promote);
    link.addEventListener("error", function() {
      iframeLoader(true);
    });
    setTimeout(function() {
      if (!promoted) {
        iframeLoader(true);
      }
    }, LOADER_TIMEOUT);
    BOOMR_lstart = new Date().getTime();
    parentNode.appendChild(link);
  } else {
    iframeLoader(false);
  }
  function boomerangSaveLoadTime(e) {
    window.BOOMR_onload = (e && e.timeStamp) || new Date().getTime();
  }
  if (window.addEventListener) {
    window.addEventListener("load", boomerangSaveLoadTime, false);
  } else if (window.attachEvent) {
    window.attachEvent("onload", boomerangSaveLoadTime);
  }
  if (document.addEventListener) {
    document.addEventListener("onBoomerangLoaded", function(e) {
      e.detail.BOOMR.init({
        ResourceTiming: {
          enabled: true,
          trackedResourceTypes: ["script", "img", "css"]
        },
      });
      e.detail.BOOMR.t_end = new Date().getTime();
    });
  } else if (document.attachEvent) {
    document.attachEvent("onpropertychange", function(e) {
      if (!e) e=event;
      if (e.propertyName === "onBoomerangLoaded") {
        e.detail.BOOMR.init({
          ResourceTiming: {
            enabled: true,
            trackedResourceTypes: ["script", "img", "css"]
          },
        });
        e.detail.BOOMR.t_end = new Date().getTime();
      }
    });
  }
})();</script>
</head>


















	<body class="warehouse--v4  template-index " data-instant-intensity="viewport">
  
<div id="shopify-section-sections--21635063513378__announcement-bar" class="shopify-section shopify-section-group-header-group shopify-section--announcement-bar"> 

 

<script>document.documentElement.style.removeProperty('--announcement-bar-button-width');document.documentElement.style.setProperty('--announcement-bar-height', document.getElementById('shopify-section-sections--21635063513378__announcement-bar').clientHeight + 'px');
</script>

</div><div id="shopify-section-sections--21635063513378__header" class="shopify-section shopify-section-group-header-group shopify-section__header"><section data-section-id="sections--21635063513378__header" data-section-type="header" data-section-settings='{
  "navigationLayout": "inline",
  "desktopOpenTrigger": "hover",
  "useStickyHeader": false
}'>
  <header class="header header--inline " role="banner">
  

 

<script>
  document.documentElement.style.setProperty('--header-height', document.getElementById('shopify-section-sections--21635063513378__header').clientHeight + 'px');
</script>

</div>


<!-- END sections: overlay-group --><main id="main" role="main">
			<section id="shopify-section-template--21635066724642__f06747fd-bcaf-4055-aed7-566df2313b61" class="shopify-section kava-hero hero-kamila-01">
 


 

 

 
</section>

</div> 

 


</section> 
		</main><!-- BEGIN sections: footer-group -->

 
		<!-- Start of HS Embed Code -->
		<script type="lazyload_int" id="hs-script-loader" data-src="//js.hs-scripts.com/21273136.js"></script>
		<script type="lazyload_int" id="hs-script-loader" data-src="//js-na1.hs-scripts.com/21273136.js"></script>
		<!-- End of HS Embed Code -->


 
		<div id="shopify-block-4015264832934714879" class="shopify-block shopify-app-block">
	<script type="lazyload_int"> 
		function createEcomSendMainStyleEle() {
			const ele = document.createElement("link")
			ele.rel = "stylesheet"
			ele.href = 'https://cdn.shopify.com/extensions/4bc4f158-0cf7-442a-b372-a59c15de7a9f/3.20.0/assets/style.css'
			ele.dataset.ecomsendTag = "load-alternate-css"
			return ele
		}
		if (window.EcomSendApps?.enableAlternateCSSLoading ?? false) {
			document.head.appendChild(createEcomSendMainStyleEle())
		}
		function createEcomSendMainJSEle() {
			const ele = document.createElement("script")
			ele.defer = true
			ele.id = "ecomsend-main-js"
			ele.src = 'https://cdn.shopify.com/extensions/4bc4f158-0cf7-442a-b372-a59c15de7a9f/3.20.0/assets/ecomsend.js'
			if (null === document.getElementById(ele.id)) {
				document.head.appendChild(ele)
			}
		}
	</script>
	<style id="ecomsend-custom-style"></style>
	<div id="ecomsend-widget"></div>
	<!-- BEGIN app snippet: ecomsend-app -->
	<script type="lazyload_int">
		//EcomSend APPS COMMON JS CODE
		window.EcomSendApps = window.EcomSendApps || {}
		window.EcomSendApps.design_mode = false
		window.EcomSendApps.common = window.EcomSendApps.common || {}
		window.EcomSendApps.common.shop = {
			permanent_domain: 'carthealth.myshopify.com',
			currency: "USD",
			money_format: "$",
			id: 56275468333,
		}
		window.EcomSendApps.common.template = 'index'
	</script>
	<!-- END app snippet -->
	<script type="lazyload_int">window.shopLocale = 'en'</script>
	<script type="lazyload_int" data-src="https://cdn.shopify.com/extensions/4bc4f158-0cf7-442a-b372-a59c15de7a9f/3.20.0/assets/react_react-dom.min.js"></script>
	<script type="lazyload_int" data-src="https://cdn.shopify.com/extensions/4bc4f158-0cf7-442a-b372-a59c15de7a9f/3.20.0/assets/mobx_react-custom-roulette.min.js" onload="createEcomSendMainJSEle()"></script>
</div>
<div id="shopify-block-2965712708760319418" class="shopify-block shopify-app-block"></div>
		



<script>
	w3_bglazyload = 1;
	(function() {
		var img = new Image();
		img.onload = function() {
			w3_hasWebP = !!(img.height > 0 && img.width > 0);
		};
		img.onerror = function() {
			w3_hasWebP = false;
		};
	});

	function w3_events_on_end_js() {
		const lazy_bg_style = document.getElementById("w3_bg_load");
		lazy_bg_style.remove();
		w3_bglazyload = 0;
		lazyloadimages(0);
		if(window.site_nav_link_burger == false) {
			var mobileNavToggle = document.querySelector('.header__mobile-nav-toggle');
			mobileNavToggle.click();
			window.site_nav_link_burger = false;
		}
	}

	function w3_start_img_load() {
		var top = this.scrollY;
		lazyloadimages(top);
		lazyloadiframes(top);
	}

	function w3_events_on_start_js() {
		var lazyvideos = document.getElementsByTagName("videolazy");
		convert_to_video_tag(lazyvideos);
		w3_start_img_load();
	}

	window.addEventListener("scroll", function(event) {
		w3_start_img_load();
	}, {
		passive: true
	});
	var w3_is_mobile = (window.matchMedia("(max-width: 767px)").matches ? 1 : 0);
	var win_width = screen.availWidth;
	var bodyRectMain = {};
	bodyRectMain.top = 1;
	setInterval(function() {
		lazyloadiframes(top);
	}, 8000);
	setInterval(function() {
		lazyloadimages(0);
	}, 1000);
	document.addEventListener("click", function() {
		lazyloadimages(0);
	});

	function getDataUrl(img1, width, height) {
		var myCanvas = document.createElement("canvas");
		var ctx = myCanvas.getContext("2d");
		var img = new Image();
		myCanvas.width = parseInt(width);
		myCanvas.height = parseInt(height);
		ctx.drawImage(img, 0, 0);
		img1.src = myCanvas.toDataURL("image/png");
	}

	function lazyload_img(imgs, bodyRect, window_height, win_width) {
		for (var i = 0; i < imgs.length; i++) {
			if (imgs[i].getAttribute("data-class") == "LazyLoad") {
				var elem = imgs[i],
					elemRect = imgs[i].getBoundingClientRect();
				if (elemRect.top != 0 && (elemRect.top - (window_height - bodyRect.top)) < w3_lazy_load_by_px) {
					compStyles = window.getComputedStyle(imgs[i]);
					if (compStyles.getPropertyValue("opacity") == 0) {
						continue;
					}
					if (elem.tagName == "IFRAMELAZY") {
						var elem = document.createElement("iframe");
						var index;
						for (index = imgs[i].attributes.length - 1; index >= 0; --index) {
							elem.attributes.setNamedItem(imgs[i].attributes[index].cloneNode());
						}
						imgs[i].parentNode.replaceChild(elem, imgs[i]);
					}
					var src = elem.getAttribute("data-src") ? elem.getAttribute("data-src") : elem.src;
					if (w3_is_mobile && elem.getAttribute("data-mob-src")) {
						src = elem.getAttribute("data-mob-src");
					}
					var srcset = elem.getAttribute("data-srcset") ? elem.getAttribute("data-srcset") : "";
					if (!srcset) {
						elem.onload = function() {
							this.setAttribute("data-done", "Loaded");
							if (typeof(w3speedup_after_iframe_img_load) == "function") {
								w3speedup_after_iframe_img_load(this);
							}
						}
						elem.onerror = function() {
							if (this.getAttribute("data-mob-src") && w3_is_mobile && this.getAttribute("data-src")) {
								this.src = this.getAttribute("data-src");
							}
						}
					}
					elem.src = src;
					if (srcset != null & srcset != "") {
						elem.srcset = srcset;
					}
					delete elem.dataset.class;
				}
			}
		}
	}

	function w3_load_dynamic_blank_img(imgs) {
		for (var i = 0; i < imgs.length; i++) {
			if (imgs[i].getAttribute("data-class") == "LazyLoad") {
				var blanksrc = imgs[i].src;
				if (typeof(blanksrc) != "undefined" && blanksrc.indexOf("data:") == -1) {
					if (imgs[i].getAttribute("width") != null && imgs[i].getAttribute("height") != null) {
						var width = parseInt(imgs[i].getAttribute("width"));
						var height = parseInt(imgs[i].getAttribute("height"));
						getDataUrl(imgs[i], width, height);
					}
				}
			}
		}
	}

	function convert_to_video_tag(imgs) {
		const t = imgs.length > 0 ? imgs[0] : "";
		if (t) {
			delete imgs[0];
			var newelem = document.createElement("video");
			var index;
			for (index = t.attributes.length - 1; index >= 0; --index) {
				newelem.attributes.setNamedItem(t.attributes[index].cloneNode());
			}
			newelem.innerHTML = t.innerHTML;
			t.parentNode.replaceChild(newelem, t);
			if (typeof(newelem.getAttribute("data-poster")) == "string") {
				newelem.setAttribute("poster", newelem.getAttribute("data-poster"));
			}
			convert_to_video_tag(imgs);
		}
	}

	function lazyload_video(imgs, bodyRect, top, window_height, win_width) {
		for (var i = 0; i < imgs.length; i++) {
			var elem = imgs[i],
				elemRect = imgs[i].getBoundingClientRect();
			if (elemRect.top != 0 && (elemRect.top - (window_height - bodyRect.top)) < w3_lazy_load_by_px) {
				if (typeof(imgs[i].getElementsByTagName("source")[0]) == "undefined") {
					lazyload_video_source(imgs[i], top, window_height, win_width, elemRect, bodyRect);
				} else {
					var sources = imgs[i].getElementsByTagName("source");
					for (var j = 0; j < sources.length; j++) {
						var source = sources[j];
						lazyload_video_source(source, top, window_height, win_width, elemRect, bodyRect);
					}
				}
			}
		}
	}

	function lazyload_video_source(source, top, window_height, win_width, elemRect, bodyRect) {
		if (typeof source != "undefined" && source.getAttribute("data-class") == "LazyLoad") {
			if (elemRect.top != 0 && (elemRect.top - (window_height - bodyRect.top)) < w3_lazy_load_by_px) {
				var src = source.getAttribute("data-src") ? source.getAttribute("data-src") : source.src;
				var srcset = source.getAttribute("data-srcset") ? source.getAttribute("data-srcset") : "";
				if (source.srcset != null & source.srcset != "") {
					source.srcset = srcset;
				}
				if (typeof(source.getElementsByTagName("source")[0]) == "undefined") {
					if (source.tagName == "SOURCE") {
						source.parentNode.src = src;
						source.parentNode.load();
						if (source.parentNode.getAttribute("autoplay") !== null) {
							source.parentNode.play();
						}
					} else {
						source.src = src;
						source.load();
						if (source.getAttribute("autoplay") !== null) {
							source.play();
						}
					}
				} else {
					source.parentNode.src = src;
				}
				delete source.dataset.class;
				source.setAttribute("data-done", "Loaded");
			}
		}
	}

	function lazyload_imgbgs(imgbgs, bodyRect, window_height, win_width) {
		for (var i = 0; i < imgbgs.length; i++) {
			var elem = imgbgs[i],
				elemRect = imgbgs[i].getBoundingClientRect(),
				offset = elemRect.top - bodyRect.top;
			if ((elemRect.top - (window_height - bodyRect.top)) < w3_lazy_load_by_px) {
				elem.classList.add("w3_bg");
			}
		}
	}

	function lazyloadimages(top) {
		var imgs = document.querySelectorAll("img[data-class=LazyLoad]");
		var imgbgs = document.querySelectorAll("div:not(.w3_js), section:not(.w3_js), iframelazy:not(.w3_js)");
		var sources = document.getElementsByTagName("video");
		var sources_audio = document.getElementsByTagName("audio");
		var bodyRect = document.body.getBoundingClientRect();
		var window_height = window.innerHeight;
		var win_width = screen.availWidth;
		if (typeof(load_dynamic_img) != "undefined") {
			w3_load_dynamic_blank_img(imgs);
			delete load_dynamic_img;
		}
		if (w3_bglazyload && ((bodyRect.top < 50 && bodyRectMain.top == 1) || Math.abs(bodyRectMain.top) - Math.abs(bodyRect.top) < -50 || Math.abs(bodyRectMain.top) - Math.abs(bodyRect.top) > 50)) {
			bodyRectMain = bodyRect;
			lazyload_imgbgs(imgbgs, bodyRect, window_height, win_width);
		}
		lazyload_img(imgs, bodyRect, window_height, win_width);
		lazyload_video(sources, bodyRect, top, window_height, win_width);
		lazyload_video(sources_audio, bodyRect, top, window_height, win_width);
	}
	lazyloadimages(0);

	function lazyloadiframes(top) {
		var bodyRect = document.body.getBoundingClientRect();
		var window_height = window.innerHeight;
		var win_width = screen.availWidth;
		var iframes = document.querySelectorAll("iframelazy[data-class=LazyLoad]");
		lazyload_img(iframes, bodyRect, window_height, win_width);
	}
</script>
<script type="lazyload_int" data-src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="lazyload_int">
	setTimeout(function() {
		console.log("In setTimeout");
		jQuery.ajax({
			url: "//carthealth.com/cdn/shop/t/32/assets/mobile-menu2.liquid?v=126826200235650818251707414072",
			success: function(result) {
				console.log("In success");
				jQuery("#mobile-menu").html(result);
			}
		});
	});
</script>
 
</body>
</html>