<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid">
					<?php require_once("inc/alert-status.php") ; ?>
					<h1 class="mt-4">Installation Process</h1>

					<?php
						$data = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " ) ;
						
						// print_r($data["shopify_url"]) ;
					?>

					<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="inst-shopify-tab" data-toggle="pill" href="#inst-shopify" role="tab" aria-controls="inst-shopify" aria-selected="true">Shopify</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="inst-bigcommerce-tab" data-toggle="pill" href="#inst-bigcommerce" role="tab" aria-controls="inst-bigcommerce" aria-selected="false">Bigcommerce</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" id="inst-wordpress-tab" data-toggle="pill" href="#inst-wordpress" role="tab" aria-controls="inst-wordpress" aria-selected="false">Wordpress</a>
						</li>
							<li class="nav-item">
							<a class="nav-link" id="inst-shift-tab" data-toggle="pill" href="#inst-shift" role="tab" aria-controls="inst-shift" aria-selected="false">Shift4Shop</a>
						</li>

						</li>
							<li class="nav-item">
							<a class="nav-link" id="inst-wix-tab" data-toggle="pill" href="#inst-wix" role="tab" aria-controls="inst-wix" aria-selected="false">Wix</a>
						</li>
						</li>
							<li class="nav-item">
							<a class="nav-link" id="inst-magento-tab" data-toggle="pill" href="#inst-magento" role="tab" aria-controls="inst-magento" aria-selected="false">Magento</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="inst-others-tab" data-toggle="pill" href="#inst-others" role="tab" aria-controls="inst-others" aria-selected="false">Others</a>
						</li>
					</ul>

					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="inst-shopify" role="tabpanel" aria-labelledby="inst-shopify-tab">
							<div class="row">
								<div class="col-md-12">
									<h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in your theme.liquid file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-gc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
									   <h5>b) Add this script code,before closing the '&lt;/body&gt;'tag in your theme.liquid file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>2. Add this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '{%- render 'social-meta-tags' -%}' code  in your theme.liquid file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc1.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>3. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '{%- render 'social-meta-tags' -%}' code  in your theme.liquid file,</h5>
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
									<h5>4. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
								</div>

								<div class="col-md-12 mt-5">
									<h5>5. In external script In the place of src used data-src like</h5>
									<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
								</div>	

								<div class="col-md-12 mt-5">
									<h5>6. In external script In the place of href used data-href like</h5>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
								</div>

								<div class="col-md-12 mt-5">
									<h5>7. Add this function on theme.liquid file
									before closing the head tag</h5>
									<code>&lt;script&gt;defer themeJsInit();&lt;/script&gt;</code> 
								</div>	

								<div class="col-md-12 mt-5">
									<h5>8. And also add this before closing the head tag.<br></h5>
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
									<h5>9. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
									<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
								</div>

								  <div class="col-md-12 mt-5">
									<h5>10. For Eliminate render block</h5>
									a)async non-critical CSS<br>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
									b)optionally increase loading priority<br>
                                    <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
                                    c)For external Script<br>
                                    <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
                                    d)To Reduce Font Loading Impact<br>
                                     <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
								</div>							
								&nbsp;
								&nbsp;

							</div>
						</div>
						<div class="tab-pane fade" id="inst-bigcommerce" role="tabpanel" aria-labelledby="inst-bigcommerce-tab">
								<div class="row">
								<div class="col-md-12">
									<h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in base.html file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-gc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
									  <h5>b) Add this script code,before closing the '&lt;/body&gt;' tag in base.html file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>2. Add this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your base.html file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc1.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>3. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your base.html file,</h5>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/jquery/jquery-3.6.0.min.js" as="script"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/files/roboto-v18-latin-regular.woff2" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>4. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
								</div>

								<div class="col-md-12 mt-5">
									<h5>5. In external script In the place of src used data-src like</h5>
									<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
								</div>	

								<div class="col-md-12 mt-5">
									<h5>6. In external script In the place of href used data-href like</h5>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
								</div>


									<div class="col-md-12 mt-5">
									<h5>7. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
									<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
								</div>

								  <div class="col-md-12 mt-5">
									<h5>8. For Eliminate render block</h5>
									a)async non-critical CSS<br>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
									b)optionally increase loading priority<br>
                                    <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
                                    c)For external Script<br>
                                    <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
                                    d)To Reduce Font Loading Impact<br>
                                     <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
								</div>							
								&nbsp;
								&nbsp;

							</div>
						</div>
						<div class="tab-pane fade" id="inst-wordpress" role="tabpanel" aria-labelledby="inst-wordpress-tab">
							<div class="row">
								<div class="col-md-12">
									<h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in header.php file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-gc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
									  <h5>b) Add this script code,before closing the '&lt;/body&gt;' tag in footer.php file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>2. Add this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header.php file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc1.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>3. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header.php file,</h5>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/jquery/jquery-3.6.0.min.js" as="script"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/files/roboto-v18-latin-regular.woff2" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>4. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
								</div>

								<div class="col-md-12 mt-5">
									<h5>5. In external script In the place of src used data-src like</h5>
									<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
								</div>	

								<div class="col-md-12 mt-5">
									<h5>6. In external script In the place of href used data-href like</h5>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
								</div>


									<div class="col-md-12 mt-5">
									<h5>7. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
									<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
								</div>

								  <div class="col-md-12 mt-5">
									<h5>8. For Eliminate render block</h5>
									a)async non-critical CSS<br>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
									b)optionally increase loading priority<br>
                                    <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
                                    c)For external Script<br>
                                    <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
                                    d)To Reduce Font Loading Impact<br>
                                     <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
								</div>							
								&nbsp;
								&nbsp;

							</div>
						</div>
						<div class="tab-pane fade" id="inst-shift" role="tabpanel" aria-labelledby="inst-shift-tab">
							<div class="row">
								<div class="col-md-12">
									<h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in frame.html file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-gc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
									  <h5>b) Add this script code,before closing the '&lt;/body&gt;' tag in frame.html file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>2. Add this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your frame.html file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc1.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>3. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your frame.html file,</h5>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/jquery/jquery-3.6.0.min.js" as="script"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/files/roboto-v18-latin-regular.woff2" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>4. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
								</div>

								<div class="col-md-12 mt-5">
									<h5>5. In external script In the place of src used data-src like</h5>
									<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
								</div>	

								<div class="col-md-12 mt-5">
									<h5>6. In external script In the place of href used data-href like</h5>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
								</div>


									<div class="col-md-12 mt-5">
									<h5>7. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
									<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
								</div>

								  <div class="col-md-12 mt-5">
									<h5>8. For Eliminate render block</h5>
									a)async non-critical CSS<br>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
									b)optionally increase loading priority<br>
                                    <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
                                    c)For external Script<br>
                                    <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
                                    d)To Reduce Font Loading Impact<br>
                                     <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
								</div>							
								&nbsp;
								&nbsp;

							</div>
						</div>
						<div class="tab-pane fade" id="inst-others" role="tabpanel" aria-labelledby="inst-others-tab">
									<div class="row">
								<div class="col-md-12">
									<h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in header/top/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-gc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
									  <h5>b) Add this script code,before closing the '&lt;/body&gt;' tag in footer/bottom/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>2. Add this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header/top/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc1.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>3. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header/top/base file,</h5>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/jquery/jquery-3.6.0.min.js" as="script"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="files/1/2659/0940/files/roboto-v18-latin-regular.woff2<" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>4. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
								</div>

								<div class="col-md-12 mt-5">
									<h5>5. In external script In the place of src used data-src like</h5>
									<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
								</div>	

								<div class="col-md-12 mt-5">
									<h5>6. In external script In the place of href used data-href like</h5>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
								</div>


									<div class="col-md-12 mt-5">
									<h5>7. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
									<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
								</div>

								  <div class="col-md-12 mt-5">
									<h5>8. For Eliminate render block</h5>
									a)async non-critical CSS<br>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
									b)optionally increase loading priority<br>
                                    <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
                                    c)For external Script<br>
                                    <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
                                    d)To Reduce Font Loading Impact<br>
                                     <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
								</div>							
								&nbsp;
								&nbsp;

							</div>
						</div>
						<div class="tab-pane fade" id="inst-wix" role="tabpanel" aria-labelledby="inst-wix-tab">
									<div class="row">
								<div class="col-md-12">
									<h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in header/top/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-gc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
									  <h5>b) Add this script code,before closing the '&lt;/body&gt;' tag in footer/bottom/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>2. Add this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header/top/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc1.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>3. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header/top/base file,</h5>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/jquery/jquery-3.6.0.min.js" as="script"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="files/1/2659/0940/files/roboto-v18-latin-regular.woff2<" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>4. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
								</div>

								<div class="col-md-12 mt-5">
									<h5>5. In external script In the place of src used data-src like</h5>
									<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
								</div>	

								<div class="col-md-12 mt-5">
									<h5>6. In external script In the place of href used data-href like</h5>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
								</div>


									<div class="col-md-12 mt-5">
									<h5>7. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
									<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
								</div>

								  <div class="col-md-12 mt-5">
									<h5>8. For Eliminate render block</h5>
									a)async non-critical CSS<br>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
									b)optionally increase loading priority<br>
                                    <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
                                    c)For external Script<br>
                                    <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
                                    d)To Reduce Font Loading Impact<br>
                                     <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
								</div>							
								&nbsp;
								&nbsp;

							</div>
						</div>
						<div class="tab-pane fade" id="inst-magento" role="tabpanel" aria-labelledby="inst-magento-tab">
									<div class="row">
								<div class="col-md-12">
									<h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in header/top/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-gc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
									  <h5>b) Add this script code,before closing the '&lt;/body&gt;' tag in footer/bottom/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>2. Add this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header/top/base file,</h5>
									<code>&lt;script type="text/javascript" src="https://ecommerceseotools.com/ecommercespeedy/es-vc1.js?theme=<?=$data["shopify_url"];?>"&gt;&lt;/script&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>3. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header/top/base file,</h5>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/jquery/jquery-3.6.0.min.js" as="script"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="files/1/2659/0940/files/roboto-v18-latin-regular.woff2<" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
								</div>

								<div class="col-md-12 mt-5">
									<h5>4. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
								</div>

								<div class="col-md-12 mt-5">
									<h5>5. In external script In the place of src used data-src like</h5>
									<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
								</div>	

								<div class="col-md-12 mt-5">
									<h5>6. In external script In the place of href used data-href like</h5>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
								</div>


									<div class="col-md-12 mt-5">
									<h5>7. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
									<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
								</div>

								  <div class="col-md-12 mt-5">
									<h5>8. For Eliminate render block</h5>
									a)async non-critical CSS<br>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
									b)optionally increase loading priority<br>
                                    <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
                                    c)For external Script<br>
                                    <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
                                    d)To Reduce Font Loading Impact<br>
                                     <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
								</div>							
								&nbsp;
								&nbsp;

							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		
	</body>
</html>
