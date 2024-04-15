<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

$project_ids = base64_decode($_GET['project']);

// die;

$sqlURL = "SELECT * FROM `script_log` WHERE `site_id` = $project_ids";
$resultURL = mysqli_query($conn,$sqlURL);
$urlFetch = mysqli_fetch_assoc($resultURL);

$url = $urlFetch['url'];
// echo $url;
// die;
$urlLists = explode(',' , $url);
$count = 0;
$domain_url = "https://".$_SERVER['HTTP_HOST'];
foreach ($urlLists as $urlList) 
                    {
                         $defer = ($count == 0) ? "defer" : "" ;
                         $script_urls .= '<code>&lt;script type="text/javascript" src="'.$domain_url.$urlList.'" '.$defer.' &gt;&lt;/script&gt;</code><br>' ;
                         $count++; 
                    }

?>
<?php require_once("inc/style-and-script.php") ; ?>
		<style>
			.loader {
			    background-color: #ffffff5e;
			    height: 100%;
			    position: absolute;
			    text-align: center;
			    margin: auto;
			    display: none;
			    width: 100%;
			}
				.previous{
				font-family: inherit;
    background-color: #f23640 !important;
    color: #ffffff;
    border: none;
    padding: 6px 12px;
    font-size: 16px;
    border-radius: 5px;
			}
		</style>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid script_I content__up">

					<h1 class="mt-4">Script Installation</h1>
					<div class="profile_tabs">
					<div class="row">
						<h3>Installation Process For Wix</h3>
								<div class="col-md-12">
									<!-- <div> <h3> Basic
									 &nbsp; <span id="togglebasic"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
									</h3>
                            </div> -->
<!-- <div id="hidebasic"> -->
                              <div style="text-align: right;"><span id="clickbtn">Copy </span></div>
                              <div style="text-align:right;"> <span style="display:none; text-align:right; color: green;" id="showtext">   Copied </span></div>
                              <div id="copyscript">
									<!-- <h5>Add this script code, before closing the '&lt;/head&gt;' tag in custom code,</h5> -->
									<?php  foreach ($urlLists as $urlList){ ?>
									<code>&lt;script <?php if($count==0){ echo "type='text/javascript'"; }?> src="<?php echo $domain_url.$urlList ?>"  <?php if($count == 0){ echo "defer"; }  ?>&gt;&lt;/script&gt;</code>
		<br>
		<?php $count++; }?>	
		
		
								</div></div>
							<!-- </div> -->

						
								&nbsp;
							<div class="script_i_btn">
						<div>
							<button onclick="location.href='<?=HOST_URL?>adminpannel/script-installation1.php?project=<?=base64_encode($project_ids)?>';" class="previous">Previous</button>
						</div>
					</div>
							</div>
			</div>
			</div>
		</div>
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
	</body>
</html>
