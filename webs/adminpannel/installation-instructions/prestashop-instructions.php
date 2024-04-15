<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="row mb-4">
	<h3>Installation Process For PrestaShop</h3>
	<div class="col-md-12">
		<!-- 	<div> <h3> Basic 
				&nbsp; <span id="togglebasic"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
			</h3></div> -->
			<!-- <div id="hidebasic"> -->
                        <div style="text-align: right;"><span id="clickbtn">Copy </span></div>
                        <div style="text-align:right;"> <span style="display:none; text-align:right; color: green;" id="showtext">  Copied </span></div>
                        <div id="copyscript">
		<!-- <h5>1. Add this script code, before closing the '&lt;/head&gt;' tag in header/top/base file,</h5> -->
		<?= $script_urls ?>										
	</div></div>
<!-- </div> -->
	<!-- <div class="col-md-12 mt-5"> -->
		<div style="display:none;"> <h3> Advanced &nbsp; <span id="toggleadvance"><i class="fa fa-chevron-up" aria-hidden="true"></i></span> </h3></div>
		 <div id="hideadvance">
		<h5>1. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header/top/base file,</h5>
		<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/jquery/jquery-3.6.0.min.js" as="script"&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
		<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="files/1/2659/0940/files/roboto-v18-latin-regular.woff2<" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
	<!-- </div>
	<div class="col-md-12 mt-5"> -->
		<h5>2. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5>
	<!-- </div>
	<div class="col-md-12 mt-5"> -->
		<h5>3. In external script In the place of src used data-src like</h5>
		<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
	<!-- </div>
	<div class="col-md-12 mt-5"> -->
		<h5>4. In external script In the place of href used data-href like</h5>
		<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
	<!-- </div>
	<div class="col-md-12 mt-5"> -->
		<h5>5. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
		<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
<!-- 	</div>
	<div class="col-md-12 mt-5"> -->
		<h5>6. For Eliminate render block</h5>
		a)async non-critical CSS<br>
		<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
		b)optionally increase loading priority<br>
		<code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
		c)For external Script<br>
		<code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
		d)To Reduce Font Loading Impact<br>
		<code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
	<!-- </div> -->
</div></div>
 <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script type="text/javascript">
    

    const span = document.querySelector("#copyscript");
const clicks = document.querySelector("#clickbtn");

clicks.onclick = function() {
  document.execCommand("copy");
}

clicks.addEventListener("copy", function(event) {
  event.preventDefault();
  if (event.clipboardData) {
    event.clipboardData.setData("text/plain", span.textContent);
    console.log(event.clipboardData.getData("text"))
  }

  $('#clickbtn').hide();
});

$(document).ready(function(){

  $("#clickbtn").on('click',function(){

     $("#showtext").show();
// setTimeout(function() {
//     $('#showtext').fadeOut('fast');
// }, 2000);
  });


});

$(document).ready(function(){
$("#hidebasic").hide();
  $("#togglebasic").on('click',function(){

     $("#hidebasic").toggle(); 
      $('#togglebasic .svg-inline--fa').toggleClass('fa-chevron-up');
    $('#togglebasic .svg-inline--fa').toggleClass('fa-chevron-down');
   
});
  });


  $(document).ready(function(){
$("#hideadvance").hide();
    $("#toggleadvance").on('click',function(){

    $("#hideadvance").toggle();
       $('#toggleadvance .svg-inline--fa').toggleClass('fa-chevron-up');
    $('#toggleadvance .svg-inline--fa').toggleClass('fa-chevron-down');
});

});


</script>