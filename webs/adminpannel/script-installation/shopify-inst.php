<!-- <div class="row">
	<div class="col-md-12 mt-5">
		<h5>1. Add this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '{%- render 'social-meta-tags' -%}' code  in your theme.liquid file,</h5>
		<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/do-not-delete/expertbrand/excv_10.js"&gt;&lt;/script&gt;</code>
		<br>
		<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/do-not-delete/expertbrand/excv_11.js"&gt;&lt;/script&gt;</code>
	</div> -->
<div class="row">
	<div class="col-md-12 mt-5">
		<h5>1. Add this script code, before closing the '&lt;/head&gt;' tag ,</h5>
		{{script_urls}}
	</div>

<!-- 	<div class="col-md-12 mt-5">
		<h5>2. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '{%- render 'social-meta-tags' -%}'  code  in your theme.liquid file,</h5>
		<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="{{ jquery.min.js | asset_url }}" as="script"&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">dns-prefetch</b>" href="//cdn.shopify.com"&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">dns-prefetch</b>" href="//cdn.shopify.com"&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="//cdn.shopify.com"&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//fonts.shopifycdn.com"&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">dns-prefetch</b>" href="https://{{shop.domain}}" crossorigin&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="https://cdn.shopify.com/s/files/1/2659/0940/files/roboto-v18-latin-regular.woff2" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
	</div>

	<div class="col-md-12 mt-5">
		<h5>3. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
	</div>

	<div class="col-md-12 mt-5">
		<h5>4. In external script In the place of src used data-src like</h5>
		<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
	</div>	

	<div class="col-md-12 mt-5">
		<h5>5. In external script In the place of href used data-href like</h5>
		<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
	</div>

	<div class="col-md-12 mt-5">
		<h5>6. Add this function on theme.liquid file
		before closing the head tag</h5>
		<code>&lt;script&gt;defer themeJsInit();&lt;/script&gt;</code> 
	</div>	

	<div class="col-md-12 mt-5">
		<h5>7. And also add this before closing the head tag.<br></h5>
		<b style="font-size:23px">{% render 'var-theme' %}</b><br>
		Create snippets “<b style="font-size:23px">var-theme.liquid</b>”<br>
		Add this code in “<b style="font-size:23px">var-theme.liquid</b>” file<br>
		<code>
              {% assign kGxh = "if(window.attachEvent)" %} 
              {% assign snOi = "document.addEventListener('asyncLazyLoad',function(event){asyncLoad();});if(window.attachEvent)" %} {% assign rapp = ", asyncLoad" %} 
             {% assign napp = ", function(){}" %} 
              {% assign wjhx = "document.addEventListener('DOMContentLoaded'" %} 
              {% assign sSOd = "document.addEventListener('asyncLazyLoad'" %} 
             {%if template == 'cart' %}
             {{content_for_header}} 
             {% elsif content_for_header contains "Shopify.designMode" %} 
                {{content_for_header}} 
              {% else %} 
              {{content_for_header | replace: wjhx, sSOd | replace: gedH, vNla | replace: rapp, napp | replace: kGxh, snOi}}
              {% endif %}
              </code> 
	</div>	

		<div class="col-md-12 mt-5">
		<h5>8. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
		<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
	</div>

	  <div class="col-md-12 mt-5">
		<h5>9. For Eliminate render block</h5>
		a)async non-critical CSS<br>
		<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
		b)optionally increase loading priority<br>
               <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
               c)For external Script<br>
               <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
               d)To Reduce Font Loading Impact<br>
                <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
	</div>-->							
</div> 