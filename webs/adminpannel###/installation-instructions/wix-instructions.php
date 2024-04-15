<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="row mb-4">
	<h3>Installation Process For Wix</h3>
	<div class="col-md-12">
		<!-- <div> <h3> Basic
     &nbsp; <span id="togglebasic"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
   </h3></div> -->
       <!-- <div id="hidebasic"> -->
                        <div style="text-align: right;"><span id="clickbtn">Copy</span></div>
                        <div style="text-align:right;"> <span style="display:none; text-align:right; color: green;" id="showtext">   Copied </span></div>
                        <div id="copyscript">
		<!-- <h5>Add this script code, before closing the '&lt;/head&gt;' tag in custom code,</h5> -->
		<?= $script_urls ?>
	</div></div></div>
<!-- </div> -->
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