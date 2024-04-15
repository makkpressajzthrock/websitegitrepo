<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;

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



if ( isset($_POST["script-installation"]) ) {

	$project_id = $_GET['project'] ;
	// echo "<pre>";
	// print_r($_POST) ;
	// die() ;

	$flag = 0 ;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	// check installation already submit or not
	$check = getTableData( $conn , " check_installation " , " website_id = '$project_id' " ) ;

	// var_dump(empty(count($check))) ;

	if ( !empty(count($check)) ) {
		$_SESSION["error"] = " Script installation record already saved in our database. " ;
		die();
	}
	else 
	{
		$installationtype = $_POST["installation-type"] ;
		$complete = $_POST["complete-installation"] ;

		if ( $installationtype == 2 ) {

			$developerName = $_POST["developer-name"] ;
			$developerEmail = $_POST["developer-email"] ;
			$developerMessage = $_POST["developer-message"] ;

			if ( empty($complete) || empty($developerName) || empty($developerEmail) || empty($developerMessage) ) {
				$_SESSION["error"] = "Please fill all required fields of web developer form." ;
				$flag = 1 ;
			}
			else {
				$sql = " INSERT INTO check_installation ( manager_id , website_id , script_installed , installation_type , developer_name , developer_email , developer_message ) VALUES ( '".$_SESSION['user_id']."' , '$project_id' , 1 , '$installationtype' , '$developerName' , '$developerEmail' , '$developerMessage' ) ; " ;
			}
		}
		elseif ( $installationtype == 3 ) 
		{

			$website_data = getTableData( $conn , " boost_website " , " id = '$project_id' " ) ;

			if ( $website_data["plan_type"] == "Free" ) {

				// processing for free plan
				$hireCardname = $_POST["hire-cardname"] ;
				$hireCardnumber = $_POST["hire-cardnumber"] ;
				$hireCardmm = $_POST["hire-cardmm"] ;
				$hireCardyyyy = $_POST["hire-cardyyyy"] ;
				$hireCardcvv = $_POST["hire-cardcvv"] ;
				$stripeToken = $_POST["stripeToken"] ;
				$itemName = $_POST["item-name"] ;
				$amount = $_POST["amount"] ;
				$currency = $_POST["currency"] ;
				// empty($complete) ||
				if (  empty($hireCardname) || empty($hireCardnumber) || empty($hireCardmm) || empty($hireCardyyyy) || empty($hireCardcvv) ) {
					$_SESSION["error"] = "Please fill all required fields of hire us form." ;
					$flag = 1 ;
				}
				else 
				{

					require_once('gateway/stripe-php/init.php') ;

					$user_data = getTableData( $conn , " admin_users " , " id = '".$_SESSION['user_id']."' AND userstatus LIKE 'manager' " ) ;
					$payment_gateway = getTableData( $conn , " payment_gateway " , " name LIKE 'stripe' " ) ;

					\Stripe\Stripe::setApiKey($payment_gateway['secret_key']);
					//add customer to stripe
				    $customer = \Stripe\Customer::create(array(
						'name' => $user_data["firstname"].''.$user_data["lastname"],
						'description' => 'Script Installation Payment',
				        'email' => $user_data["email"],
				        'source'  => $stripeToken
				    ));  

				    // details for which payment performed
				    $payDetails = \Stripe\Charge::create(array(
				        'customer' => $customer->id,
				        'amount'   => $amount,
				        'currency' => $currency,
				        'description' => $itemName,
				        'metadata' => array(
				            'order_id' => time()
				        )
				    ));

					// get payment details
					$paymenyResponse = $payDetails->jsonSerialize();

				    // check whether the payment is successful
				    if($paymenyResponse['amount_refunded'] == 0 && empty($paymenyResponse['failure_code']) && $paymenyResponse['paid'] == 1 && $paymenyResponse['captured'] == 1) 
				    {

						// transaction details 
				        $amountPaid = $paymenyResponse['amount'];
				        $txn_id = $paymenyResponse['balance_transaction'];
				        $paidCurrency = $paymenyResponse['currency'];
				        $paymentStatus = $paymenyResponse['status'];
				        $paymentDate = date("Y-m-d H:i:s");  

				        $json_data = serialize($paymenyResponse) ;  
				       

				    	$sql = " INSERT INTO check_installation ( manager_id , website_id , script_installed , installation_type , hire_cardname , hire_cardnumber , hire_cardmm , hire_cardyyy , hire_cardcvv , payment_method , stripe_customer_id , txn_id , paid_amount , paid_amount_currency , json_data , status , paymentDate ) VALUES ( '".$_SESSION['user_id']."' , '$project_id' , 0 , '$installationtype' , '$hireCardname' , '$hireCardnumber' , '$hireCardmm' , '$hireCardyyyy' , '$hireCardcvv' , 'stripe' , '".$customer->id."' , '$txn_id' , '$amountPaid' , '$paidCurrency' , '$json_data' , '$paymentStatus' , '$paymentDate' ) ; " ;
				    }
				    else {
				    	$flag = 1 ;
				    	$_SESSION["success"] = "Payment Failed." ;
				    }
				}
				// =====================================
			}
			else 
			{
				$scriptExpertHelp = $_POST["script-expert-help"] ;
				$collaboratorAccess = $_POST["collaborator-access"] ;
				if (  empty($scriptExpertHelp) || empty($collaboratorAccess) ) {
					$_SESSION["error"] = "Please fill all required fields of hire us form." ;
					$flag = 1 ;
				}
				else {
					$sql = " INSERT INTO check_installation ( manager_id , website_id , installation_type , script_expert_help , collaborator_access ) VALUES ( '".$_SESSION['user_id']."' , '$project_id' , '$installationtype' , '$scriptExpertHelp' , '$collaboratorAccess' ) ; " ;

					$msg = " Our expert will contact you soon.  " ;
				}
			}
		}
		else {

			if ( empty($complete) ) {
				$_SESSION["error"] = "Please provide valide response for installation complete." ;
				$flag = 1 ;
			}
			else {
				$sql = " INSERT INTO check_installation ( manager_id , website_id , script_installed , installation_type ) VALUES ( '".$_SESSION['user_id']."' , '$project_id' , 1 , '$installationtype' ) ; " ;
			}
		}

		if ( empty($flag) ) 
		{
			$sql .= " UPDATE boost_website SET script_installed = 1  WHERE id = '$project_id' ; " ;
			$conn->multi_query($sql) ;
			$_SESSION["success"] = "Script installation details are saved." ;
			if ( !empty($msg) ) {
				$_SESSION["success"] = $msg ; 
			}
		}
	}

	header("location:".HOST_URL."adminpannel/script-installation1.php?project=".$project_id) ;
	die();
}

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}


?>
<?php require_once("inc/style-and-script.php") ; ?>
		<style>
		.previous{
				font-family: inherit;
    background-color: #f23640 !important;
    color: #ffffff;
    border: none;
    padding: 6px 12px;
    font-size: 16px;
    border-radius: 5px;
			}
		/* Mark input boxes that gets an error on validation: */
		.invalid {
		  background-color: #ffdddd;
		}

		/* Hide all steps by default: */
		.tab {
		  display: none;
		}

		button {
		  background-color: #04AA6D;
		  color: #ffffff;
		  border: none;
		  padding: 10px 20px;
		  font-size: 17px;
		  font-family: Raleway;
		  cursor: pointer;
		}

		button:hover {
		  opacity: 0.8;
		}

		#prevBtn {
		  background-color: #bbbbbb;
		}

		/* Make circles that indicate the steps of the form: */
		.step {
		  height: 15px;
		  width: 15px;
		  margin: 0 2px;
		  background-color: #bbbbbb;
		  border: none;  
		  border-radius: 50%;
		  display: inline-block;
		  opacity: 0.5;
		}

		.step.active {
		  opacity: 1;
		}

		/* Mark the steps that are finished and valid: */
		.step.finish {
		  background-color: #04AA6D;
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
							<?php require_once("inc/alert-status.php") ; ?>

				<!-- <style type="text/css">
					.is-selected {
						border: 2px solid green !important;
					}
				</style> -->

				<?php
				if ( isset($_GET["venom"]) ) {
					$salt="WEBSITE_SPEEDY";
					$encrypted_id = base64_encode($user_id.'-'.$project_id.'-'.$salt);
					?>
					<div>&lt;script src="https://ecommerceseotools.com/ecommercespeedy/update/website-speedy.js?portal=<?=$encrypted_id;?>"&gt;&lt;/script&gt;</div>
					<?php
				}
				?>
					<div class="profile_tabs">
					<div class="row">
						<h3>Installation Process For Wordpress</h3>
								<div class="col-md-12">
								<!-- 	<div> <h3> Basic 
										&nbsp; <span id="togglebasic"><i class="fa fa-chevron-up" aria-hidden="true"></i></span>
									</h3></div> -->
									<!-- <div id="hidebasic"> -->
                              <div style="text-align: right;"><span id="clickbtn">Copy </span></div>
                              <div style="text-align:right;"> <span style="display:none; text-align:right; color: green;" id="showtext">  Copied </span></div>
                              <div id="copyscript">
									<!-- <h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in header.php file,</h5> -->
											<?php  foreach ($urlLists as $urlList){ ?>
									<code>&lt;script <?php if($count==0){ echo "type='text/javascript'"; }?> src="<?php echo $domain_url.$urlList ?>"  <?php if($count == 0){ echo "defer"; }  ?>&gt;&lt;/script&gt;</code>
		<br>
		<?php $count++; }?>	
								</div></div>
							<!-- </div> -->


								<!-- <div class="col-md-12 mt-5"> -->
											 <div style="display:none;"> <h3> Advanced &nbsp; <span id="toggleadvance"><i class="fa fa-chevron-up" aria-hidden="true"></i></span> </h3></div>
											  <div id="hideadvance">
									<h5>1. Add also this script code, in your head tag ('&lt;head&gt; ... &lt;/head&gt;') after '&lt;meta&gt;' tag code  in your header.php file,</h5>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/jquery/jquery-3.6.0.min.js" as="script"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preconnect</b>" href="https//ajax.googleapis.com"&gt;</code><br>
									<code>&lt;link rel="<b style="font-size:23px">preload</b>" href="/files/roboto-v18-latin-regular.woff2" as="font" type="font/woff2" crossorigin="anonymous"&gt;</code>
								<!-- </div> -->

								<!-- <div class="col-md-12 mt-5"> -->
									<!-- <h5>2. Add this<code>&lt;script type="<b style="font-size:23px">lazyloadscript</b>"&gt;</code>use instead of <code>&lt;script type="<b style="font-size:23px">text/javascript</b>"&gt;</code>In internal script.</h5> -->
								<!-- </div> -->

								<!-- <div class="col-md-12 mt-5"> -->
									<h5>3. In external script In the place of src used data-src like</h5>
									<code>&lt;script async <b style="font-size:23px">data-src</b>="https://www.googletagmanager.com/gtag/js?id=AW-928967429"&gt;&lt;/script&gt;</code> 
								<!-- </div>	 -->

								<!-- <div class="col-md-12 mt-5"> -->
									<h5>4. In external script In the place of href used data-href like</h5>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">data-href</b>="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css"&gt;</code> 
								<!-- </div> -->


									<!-- <div class="col-md-12 mt-5"> -->
									<h5>5. Add <b style="font-size:23px">loading="lazy"</b> in image tag for off-screen images</h5>
									<code>&lt;img src="/w3images/paris.jpg" alt="Paris" style="width:100%" <b style="font-size:23px">loading="lazy"</b>&gt;</code> 
								<!-- </div> -->

								  <!-- <div class="col-md-12 mt-5"> -->
									<h5>6. For Eliminate render block</h5>
									a)async non-critical CSS<br>
									<code>&lt;link rel="stylesheet" <b style="font-size:23px">media="print" onload="this.onload=null;this.removeAttribute('media');" </b>href="non-critical.css"&gt;</code><br>
									b)optionally increase loading priority<br>
                                    <code>&lt;link <b style="font-size:23px">rel="preload"</b> as="style" href="non-critical.css"&gt;</code><br> 
                                    c)For external Script<br>
                                    <code>&lt;script <b style="font-size:23px">defer</b> src="sitewide.js"&gt;&lt;/script&gt;</code><br>
                                    d)To Reduce Font Loading Impact<br>
                                     <code>&lt;link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900%7CPoppins:300,400,700,900<b style="font-size:23px">&display=swap</b>" rel="stylesheet">&gt;</code><br>
								<!-- </div>							 -->
								&nbsp;
								&nbsp;
								</div>
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
