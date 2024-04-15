/*!
 * Glide.js v3.0.2
 * (c) 2013-2018 Jędrzej Chałubek <jedrzej.chalubek@gmail.com> (http://jedrzejchalubek.com/)
 * Released under the MIT License.
*/ !function(t,e){"object"==typeof exports&&"undefined"!=typeof module?module.exports=e():"function"==typeof define&&define.amd?define(e):t.Glide=e()}(this,function(){"use strict";var t={type:"slider",startAt:0,perView:1,focusAt:0,gap:10,autoplay:!1,hoverpause:!0,keyboard:!0,swipeThreshold:80,dragThreshold:120,perTouch:!1,touchRatio:.5,touchAngle:45,animationDuration:400,rewindDuration:800,animationTimingFunc:"cubic-bezier(0.165, 0.840, 0.440, 1.000)",throttle:10,direction:"ltr",peek:0,breakpoints:{},classes:{direction:{ltr:"glide--ltr",rtl:"glide--rtl"},slider:"glide--slider",carousel:"glide--carousel",swipeable:"glide--swipeable",dragging:"glide--dragging",cloneSlide:"glide__slide--clone",activeNav:"glide__bullet--active",activeSlide:"glide__slide--active",disabledArrow:"glide__arrow--disabled"}};function e(t){console.error("[Glide warn]: "+t)}var n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},i=function(t,e){if(!(t instanceof e))throw TypeError("Cannot call a class as a function")},r=function(){function t(t,e){for(var n=0;n<e.length;n++){var i=e[n];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(t,i.key,i)}}return function(e,n,i){return n&&t(e.prototype,n),i&&t(e,i),e}}(),o=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var i in n)Object.prototype.hasOwnProperty.call(n,i)&&(t[i]=n[i])}return t},s=function t(e,n,i){null===e&&(e=Function.prototype);var r=Object.getOwnPropertyDescriptor(e,n);if(void 0===r){var o=Object.getPrototypeOf(e);if(null===o)return;return t(o,n,i)}if("value"in r)return r.value;var s=r.get;if(void 0!==s)return s.call(i)},u=function(t,e){if("function"!=typeof e&&null!==e)throw TypeError("Super expression must either be null or a function, not "+typeof e);t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,enumerable:!1,writable:!0,configurable:!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(t,e):t.__proto__=e)},a=function(t,e){if(!t)throw ReferenceError("this hasn't been initialised - super() hasn't been called");return e&&("object"==typeof e||"function"==typeof e)?e:t};function c(t){return parseInt(t)}function l(t){return"string"==typeof t}function f(t){var e=void 0===t?"undefined":n(t);return"function"===e||"object"===e&&!!t}function d(t){return"function"==typeof t}function h(t){return void 0===t}function p(t){return t.constructor===Array}function v(t,e,n){Object.defineProperty(t,e,n)}var m=function(){function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};i(this,t),this.events=e,this.hop=e.hasOwnProperty}return r(t,[{key:"on",value:function t(e,n){if(p(e))for(var i=0;i<e.length;i++)this.on(e[i],n);this.hop.call(this.events,e)||(this.events[e]=[]);var r=this.events[e].push(n)-1;return{remove:function t(){delete this.events[e][r]}}}},{key:"emit",value:function t(e,n){if(p(e))for(var i=0;i<e.length;i++)this.emit(e[i],n);this.hop.call(this.events,e)&&this.events[e].forEach(function(t){t(n||{})})}}]),t}(),g=function(){function n(e){var r,s,u,a=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};i(this,n),this._c={},this._e=new m,this.disabled=!1,this.selector=e,this.settings=(r=t,u=o({},r,s=a),s.hasOwnProperty("classes")&&(u.classes=o({},r.classes,s.classes),s.classes.hasOwnProperty("direction")&&(u.classes.direction=o({},r.classes.direction,s.classes.direction))),u),this.index=this.settings.startAt}return r(n,[{key:"mount",value:function t(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return this._e.emit("mount.before"),f(n)?this._c=function t(n,i,r){var o={};for(var s in i)d(i[s])?o[s]=i[s](n,o,r):e("Extension must be a function");for(var u in o)d(o[u].mount)&&o[u].mount();return o}(this,n,this._e):e("You need to provide a object on `mount()`"),this._e.emit("mount.after"),this}},{key:"update",value:function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return this.settings=o({},this.settings,e),e.hasOwnProperty("startAt")&&(this.index=e.startAt),this._e.emit("update"),this}},{key:"go",value:function t(e){return this._c.Run.make(e),this}},{key:"move",value:function t(e){return this._c.Transition.disable(),this._c.Move.make(e),this}},{key:"destroy",value:function t(){return this._e.emit("destroy"),this}},{key:"play",value:function t(){var e=arguments.length>0&&void 0!==arguments[0]&&arguments[0];return e&&(this.settings.autoplay=e),this._e.emit("play"),this}},{key:"pause",value:function t(){return this._e.emit("pause"),this}},{key:"disable",value:function t(){return this.disabled=!0,this}},{key:"enable",value:function t(){return this.disabled=!1,this}},{key:"on",value:function t(e,n){return this._e.on(e,n),this}},{key:"isType",value:function t(e){return this.settings.type===e}},{key:"settings",get:function t(){return this._o},set:function t(n){f(n)?this._o=n:e("Options must be an `object` instance.")}},{key:"index",get:function t(){return this._i},set:function t(e){this._i=c(e)}},{key:"type",get:function t(){return this.settings.type}},{key:"disabled",get:function t(){return this._d},set:function t(e){this._d=!!e}}]),n}(),y=function(t,e,n){var i={mount:function t(){this._o=!1},make:function i(r){var o=this;t.disabled||(t.disable(),this.move=r,n.emit("run.before",this.move),this.calculate(),n.emit("run",this.move),e.Transition.after(function(){(o.isOffset("<")||o.isOffset(">"))&&(o._o=!1,n.emit("run.offset",o.move)),n.emit("run.after",o.move),t.enable()}))},calculate:function e(){var i,r=this.move,o=this.length,s=r.steps,u=r.direction,a="number"==typeof(i=c(s))&&0!==c(s);switch(u){case">":">"===s?t.index=o:this.isEnd()?(this._o=!0,t.index=0,n.emit("run.end",r)):a?t.index+=Math.min(o-t.index,-c(s)):t.index++;break;case"<":"<"===s?t.index=0:this.isStart()?(this._o=!0,t.index=o,n.emit("run.start",r)):a?t.index-=Math.min(t.index,c(s)):t.index--;break;case"=":t.index=s}},isStart:function e(){return 0===t.index},isEnd:function e(){return t.index===this.length},isOffset:function t(e){return this._o&&this.move.direction===e}};return v(i,"move",{get:function t(){return this._m},set:function t(e){this._m={direction:e.substr(0,1),steps:e.substr(1)?e.substr(1):0}}}),v(i,"length",{get:function t(){return e.Html.slides.length-1}}),v(i,"offset",{get:function t(){return this._o}}),i};function $(){return new Date().getTime()}function b(t,e,n){var i=void 0,r=void 0,o=void 0,s=void 0,u=0;n||(n={});var a=function e(){u=!1===n.leading?0:$(),i=null,s=t.apply(r,o),i||(r=o=null)},c=function c(){var l=$();u||!1!==n.leading||(u=l);var f=e-(l-u);return r=this,o=arguments,f<=0||f>e?(i&&(clearTimeout(i),i=null),u=l,s=t.apply(r,o),i||(r=o=null)):i||!1===n.trailing||(i=setTimeout(a,f)),s};return c.cancel=function(){clearTimeout(i),u=0,i=r=o=null},c}var w={ltr:["marginLeft","marginRight"],rtl:["marginRight","marginLeft"]},k=function(t,e,n){var i={mount:function e(){this.value=t.settings.gap},apply:function t(n){for(var i=0,r=n.length;i<r;i++){var o=n[i].style,s=e.Direction.value;0!==i?o[w[s][0]]=this.value/2+"px":o[w[s][0]]="",i!==n.length-1?o[w[s][1]]=this.value/2+"px":o[w[s][1]]=""}},remove:function t(e){for(var n=0,i=e.length;n<i;n++){var r=e[n].style;r.marginLeft="",r.marginRight=""}}};return v(i,"value",{get:function t(){return i._v},set:function t(e){i._v=c(e)}}),v(i,"grow",{get:function t(){return i.value*(e.Sizes.length-1)}}),v(i,"reductor",{get:function e(){var n=t.settings.perView;return i.value*(n-1)/n}}),n.on("update",function(){i.mount()}),n.on(["build.after","update"],b(function(){i.apply(e.Html.wrapper.children)},30)),n.on("destroy",function(){i.remove(e.Html.wrapper.children)}),i};function _(t){for(var e=t.parentNode.firstChild,n=[];e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n}function H(t){return!!t&&t instanceof window.HTMLElement}var S='[data-glide-el="track"]',x=function(t,n){var i={mount:function e(){this.root=t.selector,this.track=this.root.querySelector(S),this.slides=Array.from(this.wrapper.children).filter(function(e){return!e.classList.contains(t.settings.classes.cloneSlide)})}};return v(i,"root",{get:function t(){return i._r},set:function t(n){l(n)&&(n=document.querySelector(n)),H(n)?i._r=n:e("Root element must be a existing Html node")}}),v(i,"track",{get:function t(){return i._t},set:function t(n){H(n)?i._t=n:e("Could not find track element. Please use "+S+" attribute.")}}),v(i,"wrapper",{get:function t(){return i.track.children[0]}}),i},T=function(t,e,n){var i={mount:function e(){this.value=t.settings.peek}};return v(i,"value",{get:function t(){return i._v},set:function t(e){f(e)?(e.before=c(e.before),e.after=c(e.after)):e=c(e),i._v=e}}),v(i,"reductor",{get:function e(){var n=i.value,r=t.settings.perView;return f(n)?n.before/r+n.after/r:2*n/r}}),n.on(["resize","update"],function(){i.mount()}),i},A=function(t,e,n){var i={mount:function t(){this._o=0},make:function t(){var i=this,r=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0;this.offset=r,n.emit("move",{movement:this.value}),e.Transition.after(function(){n.emit("move.after",{movement:i.value})})}};return v(i,"offset",{get:function t(){return i._o},set:function t(e){i._o=h(e)?0:c(e)}}),v(i,"translate",{get:function n(){return e.Sizes.slideWidth*t.index}}),v(i,"value",{get:function t(){var n=this.offset,i=this.translate;return e.Direction.is("rtl")?i+n:i-n}}),n.on(["build.before","run"],function(){i.make()}),i},C=function(t,e,n){var i={setupSlides:function t(){for(var n=e.Html.slides,i=0;i<n.length;i++)n[i].style.width=this.slideWidth+"px"},setupWrapper:function t(n){e.Html.wrapper.style.width=this.wrapperSize+"px"},remove:function t(){for(var n=e.Html.slides,i=0;i<n.length;i++)n[i].style.width="";e.Html.wrapper.style.width=""}};return v(i,"length",{get:function t(){return e.Html.slides.length}}),v(i,"width",{get:function t(){return e.Html.root.offsetWidth}}),v(i,"wrapperSize",{get:function t(){return i.slideWidth*i.length+e.Gaps.grow+e.Clones.grow}}),v(i,"slideWidth",{get:function n(){return i.width/t.settings.perView-e.Peek.reductor-e.Gaps.reductor}}),n.on(["build.before","resize","update"],function(){i.setupSlides(),i.setupWrapper()}),n.on("destroy",function(){i.remove()}),i},z=function(t,e,n){var i={mount:function t(){n.emit("build.before"),this.typeClass(),this.activeClass(),n.emit("build.after")},typeClass:function n(){e.Html.root.classList.add(t.settings.classes[t.settings.type])},activeClass:function n(){var i=t.settings.classes,r=e.Html.slides[t.index];r.classList.add(i.activeSlide),_(r).forEach(function(t){t.classList.remove(i.activeSlide)})},removeClasses:function n(){var i=t.settings.classes;e.Html.root.classList.remove(i[t.settings.type]),e.Html.slides.forEach(function(t){t.classList.remove(i.activeSlide)})}};return n.on(["destroy","update"],function(){i.removeClasses()}),n.on(["resize","update"],function(){i.mount()}),n.on("move.after",function(){i.activeClass()}),i},P=function(t,e,n){var i={mount:function e(){this.items=[],t.isType("carousel")&&(this.pattern=this.map(),this.items=this.collect())},map:function n(){for(var i=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],r=t.settings.perView,o=e.Html.slides.length,s=0;s<Math.max(1,Math.floor(r/o));s++){for(var u=0;u<=o-1;u++)i.push(""+u);for(var a=o-1;a>=0;a--)i.unshift("-"+a)}return i},collect:function n(){for(var i=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],r=this.pattern,o=0;o<r.length;o++){var s=e.Html.slides[Math.abs(r[o])].cloneNode(!0);s.classList.add(t.settings.classes.cloneSlide),i.push(s)}return i},append:function t(){for(var n=this.items,i=this.pattern,r=0;r<n.length;r++){var o=n[r];o.style.width=e.Sizes.slideWidth+"px","-"===i[r][0]?e.Html.wrapper.insertBefore(o,e.Html.slides[0]):e.Html.wrapper.appendChild(o)}},remove:function t(){for(var e=this.items,n=0;n<e.length;n++)e[n].remove()}};return v(i,"grow",{get:function t(){return(e.Sizes.slideWidth+e.Gaps.value)*i.items.length}}),n.on("update",function(){i.remove(),i.mount(),i.append()}),n.on("build.before",function(){t.isType("carousel")&&i.append()}),n.on("destroy",function(){i.remove()}),i},D=function(){function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};i(this,t),this.listeners=e}return r(t,[{key:"on",value:function t(e,n,i){l(e)&&(e=[e]);for(var r=0;r<e.length;r++)this.listeners[e[r]]=i,n.addEventListener(e[r],this.listeners[e[r]],!1)}},{key:"off",value:function t(e,n){l(e)&&(e=[e]);for(var i=0;i<e.length;i++)n.removeEventListener(e[i],this.listeners[e[i]],!1)}},{key:"destroy",value:function t(){delete this.listeners}}]),t}(),L=function(t,e,n){var i=new D,r={mount:function t(){this.bind()},bind:function e(){i.on("resize",window,b(function(){n.emit("resize")},t.settings.throttle))},unbind:function t(){i.off("resize",window)}};return n.on("destroy",function(){r.unbind(),i.destroy()}),r},O=["ltr","rtl"],R={">":"<","<":">","=":"="},E=function(t,n,i){var r={mount:function e(){this.value=t.settings.direction},resolve:function t(e){var n=e.slice(0,1);return this.is("rtl")?e.split(n).join(R[n]):e},is:function t(e){return this.value===e},addClass:function e(){n.Html.root.classList.add(t.settings.classes.direction[this.value])},removeClass:function e(){n.Html.root.classList.remove(t.settings.classes.direction[this.value])}};return v(r,"value",{get:function t(){return r._v},set:function t(n){O.includes(n)?r._v=n:e("Direction value must be `ltr` or `rtl`")}}),i.on(["destroy","update"],function(){r.removeClass()}),i.on("update",function(){r.mount()}),i.on(["build.before","update"],function(){r.addClass()}),r},M=[function(t,e){return{modify:function n(i){return i+e.Gaps.value*t.index}}},function(t,e){return{modify:function t(n){return n+e.Clones.grow/2}}},function(t,e){return{modify:function n(i){if(t.settings.focusAt>=0){var r=e.Peek.value;return f(r)?i-r.before:i-r}return i}}},function(t,e){return{modify:function n(i){var r=e.Gaps.value,o=e.Sizes.width,s=t.settings.focusAt,u=e.Sizes.slideWidth;return"center"===s?i-(o/2-u/2):i-u*s-r*s}}},function(t,e){return{modify:function t(n){return e.Direction.is("rtl")?-n:n}}}],W=function(t,e,n){var i={set:function n(i){var r,o,s=(r=t,o=e,{mutate:function t(e){for(var n=0;n<M.length;n++)e=M[n](r,o).modify(e);return e}}).mutate(i);e.Html.wrapper.style.transform="translate3d("+-1*s+"px, 0px, 0px)"},remove:function t(){e.Html.wrapper.style.transform=""}};return n.on("move",function(r){var o=e.Gaps.value,s=e.Sizes.length,u=e.Sizes.slideWidth;return t.isType("carousel")&&e.Run.isOffset("<")?(e.Transition.after(function(){n.emit("translate.jump"),i.set(u*(s-1))}),i.set(-u-o*s)):t.isType("carousel")&&e.Run.isOffset(">")?(e.Transition.after(function(){n.emit("translate.jump"),i.set(0)}),i.set(u*s+o*s)):i.set(r.movement)}),n.on("destroy",function(){i.remove()}),i},j=function(t,e,n){var i=!1,r={compose:function e(n){var r=t.settings;return i?n+" 0ms "+r.animationTimingFunc:n+" "+this.duration+"ms "+r.animationTimingFunc},set:function t(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"transform";e.Html.wrapper.style.transition=this.compose(n)},remove:function t(){e.Html.wrapper.style.transition=""},after:function t(e){setTimeout(function(){e()},this.duration)},enable:function t(){i=!1,this.set()},disable:function t(){i=!0,this.set()}};return v(r,"duration",{get:function n(){var i=t.settings;return t.isType("slider")&&e.Run.offset?i.rewindDuration:i.animationDuration}}),n.on("move",function(){r.set()}),n.on(["build.before","resize","translate.jump"],function(){r.disable()}),n.on("run",function(){r.enable()}),n.on("destroy",function(){r.remove()}),r},G=["touchstart","mousedown"],B=["touchmove","mousemove"],q=["touchend","touchcancel","mouseup","mouseleave"],N=["mousedown","mousemove","mouseup","mouseleave"],V=function(t,e,n){var i=new D,r=0,o=0,s=0,u=!1,a={mount:function t(){this.bindSwipeStart()},start:function e(i){if(!u&&!t.disabled){this.disable();var a=this.touches(i);r=null,o=c(a.pageX),s=c(a.pageY),this.bindSwipeMove(),this.bindSwipeEnd(),n.emit("swipe.start")}},move:function i(u){if(!t.disabled){var a,l=t.settings,f=this.touches(u),d=c(f.pageX)-o,h=Math.abs(c(f.pageY)-s<<2);if(180*(r=Math.asin(Math.sqrt(h)/Math.sqrt(Math.abs(d<<2)+h)))/Math.PI<l.touchAngle&&e.Move.make(d*parseFloat(l.touchRatio)),!(180*r/Math.PI<l.touchAngle))return!1;u.stopPropagation(),u.preventDefault(),e.Html.root.classList.add(l.classes.dragging),n.emit("swipe.move")}},end:function i(s){if(!t.disabled){var u=t.settings,a=this.touches(s),l=this.threshold(s),f=a.pageX-o,d=180*r/Math.PI,h=Math.round(f/e.Sizes.slideWidth);this.enable(),f>l&&d<u.touchAngle?(u.perTouch&&(h=Math.min(h,c(u.perTouch))),e.Direction.is("rtl")&&(h=-h),e.Run.make(e.Direction.resolve("<"+h))):f<-l&&d<u.touchAngle?(u.perTouch&&(h=Math.max(h,-c(u.perTouch))),e.Direction.is("rtl")&&(h=-h),e.Run.make(e.Direction.resolve(">"+h))):e.Move.make(),e.Html.root.classList.remove(u.classes.dragging),this.unbindSwipeMove(),this.unbindSwipeEnd(),n.emit("swipe.end")}},bindSwipeStart:function n(){var r=t.settings;r.swipeThreshold&&i.on(G[0],e.Html.wrapper,this.start.bind(this)),r.dragThreshold&&i.on(G[1],e.Html.wrapper,this.start.bind(this))},unbindSwipeStart:function t(){i.off(G[0],e.Html.wrapper),i.off(G[1],e.Html.wrapper)},bindSwipeMove:function n(){i.on(B,e.Html.wrapper,b(this.move.bind(this),t.settings.throttle))},unbindSwipeMove:function t(){i.off(B,e.Html.wrapper)},bindSwipeEnd:function t(){i.on(q,e.Html.wrapper,this.end.bind(this))},unbindSwipeEnd:function t(){i.off(q,e.Html.wrapper)},touches:function t(e){return N.includes(e.type)?e:e.touches[0]||e.changedTouches[0]},threshold:function e(n){var i=t.settings;return N.includes(n.type)?i.dragThreshold:i.swipeThreshold},enable:function t(){return u=!1,e.Transition.enable(),this},disable:function t(){return u=!0,e.Transition.disable(),this}};return n.on("build.after",function(){e.Html.root.classList.add(t.settings.classes.swipeable)}),n.on("destroy",function(){a.unbindSwipeStart(),a.unbindSwipeMove(),a.unbindSwipeEnd(),i.destroy()}),a},I=function(t,e,n){var i=new D,r={mount:function t(){this.bind()},bind:function t(){i.on("dragstart",e.Html.wrapper,this.dragstart)},unbind:function t(){i.off("dragstart",e.Html.wrapper)},dragstart:function t(e){e.preventDefault()}};return n.on("destroy",function(){r.unbind(),i.destroy()}),r},F=function(t,e,n){var i=new D,r=!1,o=!1,s={mount:function t(){this._a=e.Html.wrapper.querySelectorAll("a"),this.bind()},bind:function t(){i.on("click",e.Html.wrapper,this.click)},unbind:function t(){i.off("click",e.Html.wrapper)},click:function t(e){e.stopPropagation(),o&&e.preventDefault()},detach:function t(){if(o=!0,!r){for(var e=0;e<this.items.length;e++)this.items[e].draggable=!1,this.items[e].dataset.href=this.items[e].getAttribute("href"),this.items[e].removeAttribute("href");r=!0}return this},attach:function t(){if(o=!1,r){for(var e=0;e<this.items.length;e++)this.items[e].draggable=!0,this.items[e].setAttribute("href",this.items[e].dataset.href),delete this.items[e].dataset.href;r=!1}return this}};return v(s,"items",{get:function t(){return s._a}}),n.on("swipe.move",function(){s.detach()}),n.on("swipe.end",function(){e.Transition.after(function(){s.attach()})}),n.on("destroy",function(){s.attach(),s.unbind(),i.destroy()}),s},X=function(t,e,n){var i=new D,r={mount:function t(){this._n=e.Html.root.querySelectorAll('[data-glide-el="controls[nav]"]'),this._i=e.Html.root.querySelectorAll('[data-glide-el^="controls"]'),this.addBindings()},setActive:function t(){for(var e=0;e<this._n.length;e++)this.addClass(this._n[e].children)},removeActive:function t(){for(var e=0;e<this._n.length;e++)this.removeClass(this._n[e].children)},addClass:function e(n){var i=t.settings,r=n[t.index];r.classList.add(i.classes.activeNav),_(r).forEach(function(t){t.classList.remove(i.classes.activeNav)})},removeClass:function e(n){n[t.index].classList.remove(t.settings.classes.activeNav)},addBindings:function t(){for(var e=0;e<this._i.length;e++)this.bind(this._i[e].children)},removeBindings:function t(){for(var e=0;e<this._i.length;e++)this.unbind(this._i[e].children)},bind:function t(e){for(var n=0;n<e.length;n++)i.on(["click","touchstart"],e[n],this.click)},unbind:function t(e){for(var n=0;n<e.length;n++)i.off(["click","touchstart"],e[n])},click:function t(n){n.preventDefault(),e.Run.make(e.Direction.resolve(n.currentTarget.dataset.glideDir))}};return v(r,"items",{get:function t(){return r._i}}),n.on(["mount.after","move.after"],function(){r.setActive()}),n.on("destroy",function(){r.removeBindings(),r.removeActive(),i.destroy()}),r},Y=function(t,e,n){var i=new D,r={mount:function e(){t.settings.keyboard&&this.bind()},bind:function t(){i.on("keyup",document,this.press)},unbind:function t(){i.off("keyup",document)},press:function t(n){39===n.keyCode&&e.Run.make(e.Direction.resolve(">")),37===n.keyCode&&e.Run.make(e.Direction.resolve("<"))}};return n.on(["destroy","update"],function(){r.unbind()}),n.on("update",function(){r.mount()}),n.on("destroy",function(){i.destroy()}),r},K=function(t,e,n){var i=new D,r={mount:function e(){this.start(),t.settings.hoverpause&&this.bind()},start:function n(){var i=this;t.settings.autoplay&&h(this._i)&&(this._i=setInterval(function(){i.stop(),e.Run.make(">"),i.start()},this.time))},stop:function t(){this._i=clearInterval(this._i)},bind:function t(){var n=this;i.on("mouseover",e.Html.root,function(){n.stop()}),i.on("mouseout",e.Html.root,function(){n.start()})},unbind:function t(){i.off(["mouseover","mouseout"],e.Html.root)}};return v(r,"time",{get:function n(){var i=e.Html.slides[t.index].getAttribute("data-glide-autoplay");return i?c(i):c(t.settings.autoplay)}}),n.on(["destroy","update"],function(){r.unbind()}),n.on(["run.before","pause","destroy","swipe.start","update"],function(){r.stop()}),n.on(["run.after","play","swipe.end"],function(){r.start()}),n.on("update",function(){r.mount()}),n.on("destroy",function(){i.destroy()}),r};function J(t){if(f(t)){var n;return Object.keys(n=t).sort().reduce(function(t,e){return t[e]=n[e],t[e],t},{})}return e("Breakpoints option must be an object"),{}}var Q={Html:x,Translate:W,Transition:j,Direction:E,Peek:T,Sizes:C,Gaps:k,Move:A,Clones:P,Resize:L,Build:z,Run:y,Swipe:V,Images:I,Anchors:F,Controls:X,Keyboard:Y,Autoplay:K,Breakpoints:function(t,e,n){var i=new D,r=t.settings,s=r.breakpoints;s=J(s);var u=o({},r),a={match:function t(e){if(void 0!==window.matchMedia){for(var n in e)if(e.hasOwnProperty(n)&&window.matchMedia("(max-width: "+n+"px)").matches)return e[n]}return u}};return o(r,a.match(s)),i.on("resize",window,b(function(){o(r,a.match(s))},t.settings.throttle)),n.on("update",function(){s=J(s),u=o({},r)}),n.on("destroy",function(){i.off("resize",window)}),a}};return function(t){function e(){return i(this,e),a(this,(e.__proto__||Object.getPrototypeOf(e)).apply(this,arguments))}return u(e,t),r(e,[{key:"mount",value:function t(){var n=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return s(e.prototype.__proto__||Object.getPrototypeOf(e.prototype),"mount",this).call(this,o({},Q,n))}}]),e}(g)});