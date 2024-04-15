<?php 


// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


require_once('config.php');
require_once('inc/functions.php') ;
require_once('smtp/PHPMailerAutoload.php');




require_once('dompdf/autoload.inc.php'); // Include autoloader 

use Dompdf\Dompdf;  // Reference the Dompdf namespace 
ob_clean();


if ( isset($_POST["script-installation"]) ) {

	$project_id = base64_decode($_GET['project']) ;
	// echo "<pre>"; print_r($_POST) ; 
	// die() ;

	$flag = 0 ;
	$sqlticket="";

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	// check installation already submit or not
	$check = getTableData( $conn , " check_installation " , " website_id = '$project_id' " ) ;

	// var_dump(empty(count($check))) ;

	// if ( !empty(count($check)) ) 
	if ( 1!=1) 
	{
		$_SESSION["error"] = " Script installation record already saved in our database. " ;
		// die();
	}
	else 
	{
		$installationtype = $_POST["installation-type"] ;
		$complete = $_POST["complete-installation"] ;

		if ( $installationtype == 2 ) {

			$developerName = $_POST["developer-name"] ;
			$developerEmail = $_POST["developer-email"] ;
			$developerMessage = $_POST["developer-message"] ;

			if (  empty($developerName) || empty($developerEmail) || empty($developerMessage) ) {
				$_SESSION["error"] = "Please fill all required fields of web developer form." ;
				$flag = 1 ;
			}
			else {

			if(count($check)>=1){
				 $sql = " Update check_installation set developer_name = '$developerName' , developer_email = '$developerEmail',developer_message = '$developerMessage' where id = ".$check['id'].";";

			}	
			else{
				 $sql = " INSERT INTO check_installation ( manager_id , website_id , script_installed , installation_type , developer_name , developer_email , developer_message ) VALUES ( '".$_SESSION['user_id']."' , '$project_id' , 1 , '$installationtype' , '$developerName' , '$developerEmail' , '$developerMessage' ) ; " ;
				}
			}
		}
		elseif ( $installationtype == 3 ) 
		{

			$website_data = getTableData( $conn , " boost_website " , " id = '$project_id' " ) ;
			// print_r($website_data) ;

			if ( ($website_data["plan_type"] == "Free") && ($_POST['hire-payment'] == 0) ) {

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
				// die("kkk") ;
				$scriptExpertHelp = $_POST["script-expert-help"] ;
				$collaboratorAccess = $_POST["collaborator-access"] ;
				if (  empty($scriptExpertHelp)  ) {
					$_SESSION["error"] = "Please fill all required fields of hire us form." ;
					$flag = 1 ;
				}
				else {

			if(count($check)>=1){
				 $sql = " Update check_installation set developer_name = '' , developer_email = '',developer_message = '', installation_type = '$installationtype', script_expert_help = '$scriptExpertHelp', collaborator_access = '$collaboratorAccess'   where id = ".$check['id'].";";

			}	
			else{

					 $sql = " INSERT INTO check_installation ( manager_id , website_id , installation_type , script_expert_help , collaborator_access ) VALUES ( '".$_SESSION['user_id']."' , '$project_id' , '$installationtype' , '$scriptExpertHelp' , '$collaboratorAccess' ) ; " ;
				}
					$userId = $_SESSION['user_id'];
					$check_user = getTableData( $conn , " admin_users " , " id = '$userId' " ) ;

// print_r($check_user);
// echo $check_user['email'];
					$phone = "null";
					if($check_user['phone']!=""){
						$phone = $check_user['phone'];
					}

					 $sqlticket = " INSERT INTO `support_tickets` ( `manager_id`, `issue`, `website`, `email`, `phone` ) VALUES ( '".$_SESSION['user_id']."' , '$scriptExpertHelp' , '$project_id' , '".$check_user['email']."' , $phone ) ; " ;
// die;
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
			 $sql = " UPDATE boost_website SET script_installed = 1  WHERE id = '$project_id' ; " ;

// echo $sqlticket;
			 if($sqlticket!=""){

					if ($conn->query($sqlticket) === TRUE) {
					  $last_id = $conn->insert_id;
					  $msg .=" Your ticket id is : $last_id.";
					} 
			 }
			 // die;
			$conn->multi_query($sql) ;

			$_SESSION["success"] = "Script installation details are saved." ;
			if ( !empty($msg) ) {
				$_SESSION["success"] = $msg ; 
			}

			// send pdf content
			if ( $installationtype == 2 ) 
			{
				// $site_url=$website_data["website_url"]; // *

				$project_data = getTableData( $conn , " boost_website " , " id = '$project_id' " ) ;

				$content = '' ;
				switch ($project_data["platform"]) {
					case 'Shopify':
						$content .= '<div><h3>Installation Process For Shopify</h3></div>' ;
						$content .= file_get_contents("script-installation/shopify-inst.php") ;
						break;

					case 'Bigcommerce':
						$content .= '<div><h3>Installation Process For Bigcommerce</h3></div>' ;
						$content .= file_get_contents("script-installation/bigcommerce-inst.php") ;
						break;

					case 'Wordpress':
						$content .= '<div><h3>Installation Process For Wordpress</h3></div>' ;
						$content .= file_get_contents("script-installation/wordpress-inst.php") ;
						break;

					case 'Shift4Shop':
						$content .= '<div><h3>Installation Process For Shift4Shop</h3></div>' ;
						$content .= file_get_contents("script-installation/shift4shop-inst.php") ;
						break;

					case 'Wix':
						$content .= '<div><h3>Installation Process For Wix</h3></div>' ;
						$content .= file_get_contents("script-installation/wix-inst.php") ;
						break;

					case 'Magento':
						$content .= '<div><h3>Installation Process For Magento</h3></div>' ;
						$content .= file_get_contents("script-installation/magento-inst.php") ;
						break;

					case 'Opencart':
						$content .= '<div><h3>Installation Process For Opencart</h3></div>' ;
						$content .= file_get_contents("script-installation/opencart-inst.php") ;
						break;

					case 'Prestashop':
						$content .= '<div><h3>Installation Process For Prestashop</h3></div>' ;
						$content .= file_get_contents("script-installation/prestashop-inst.php") ;
						break;

					default:
						$content .= '<div><h3>Installation Process</h3></div>' ;
						$content .= file_get_contents("script-installation/other-inst.php") ;
						break;
				}

				// get script urls -------------------------
				$script_urls = '' ;

				$sql = " SELECT * FROM `script_log` WHERE `site_id` = '$project_id' ";
				$result = mysqli_query($conn,$sql);
				$urlFetch = mysqli_fetch_assoc($result);


				$url = $urlFetch['url'];

				$urlLists = explode(',' , $url);
				// die;
				$domain_url = "https://".$_SERVER['HTTP_HOST'];

				$count = 0;
				foreach ($urlLists as $urlList) 
				{
					$defer = ($count == 0) ? "defer" : "" ;
					$script_urls .= '<code>&lt;script type="text/javascript" src="'.$domain_url.$urlList.'" '.$defer.' &gt;&lt;/script&gt;</code><br>' ;
					$count++; 
				}

				$script_variables = array("script_urls"=> $script_urls , "website_url" => $project_data["website_url"] ) ;
				foreach($script_variables as $key => $value) {
					$content = str_replace('{{'.$key.'}}', $value, $content);
				}

				// -------------------------------------------
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
				// print_r($content);

				$website_name = empty($project_data["website_name"]) ? parse_url($project_data["website_url"])["host"] : $project_data["website_name"] ;

				$pdf_name = $project_data["platform"].'-'.$website_name."-script-installation-instructions.pdf" ;
				// print_r($pdf_name) ;


				// Instantiate and use the dompdf class 
				$dompdf = new Dompdf();
				$dompdf->loadHtml($content); 
				$dompdf->setPaper('A4', 'portrait'); 
				$dompdf->render(); 
				$output = $dompdf->output();
				file_put_contents('installation-documents/'.$pdf_name, $output);

				// die("<hr>");

         // require 'smtp/class.phpmailer.php';            

				$mail = new PHPMailer(); 
				$mail->SMTPDebug=3;
				$mail->IsSMTP(); 
				$mail->SMTPAuth = true; 
				$mail->SMTPSecure = 'tls'; 
				$mail->Host = "smtp.gmail.com";
				$mail->Port = "587"; 
				$mail->IsHTML(true);
				$mail->CharSet = 'UTF-8';
				$mail->Username = SMTP_USER ;
				$mail->Password = SMTP_PASSWARD ;
				$mail->SetFrom("info@ecommercespeedy.com","WEBSITE SPEEDY");
				// $mail->addReplyTo($developerEmail,$developerName);
				$mail->Subject = " Developer Installation Script ";
				$mail->Body = "Hey $developerName, <br> <b> Please find that Attachment document for installation proccess for website speedy script in your ".$project_data["website_url"]." </b> <br>  ". str_replace('\r\n', ' ', $developerMessage)."<br>click here <a target='_blank' href='".HOST_URL."adminpannel/installation-documents/".$pdf_name."'>download</a> <br> Thanks,<br> Website Speedy";
				$mail->AddAddress($developerEmail);
				// $mail->AddAddress("akash@makkpress.com");


				// $mail->AddAttachment("installation-documents/".$pdf_name);
				
// $a=(!$mail->AddAttachment("https://websitespeedy.com/adminpannel/installation-documents/Shopify.pdf.pdf"))?0:1;
			
			
				// $mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));

				var_dump($mail->Send()) ;
				// echo $a;	

// print_r($pdf_name);
				// die;
				// die("kkkk") ;
			}
		}
	}

	// print_r($_SESSION) ;
	header("location:".HOST_URL."adminpannel/script-installation1.php?project=".base64_encode($project_id)) ;
	die();
}

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

 

?>
<?php require_once("inc/style-and-script.php") ; ?>

<!-- Stripe JS library -->
<script src="https://js.stripe.com/v2/"></script>
<?php $payment_gateway = getTableData( $conn , " payment_gateway " , " name LIKE 'stripe' " ) ; ?>
<script>
	Stripe.setPublishableKey('<?php echo $payment_gateway["public_key"]; ?>');
</script>

		<style>

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
	</head>
<body class="custom-tabel">
	<?php
		$user_id = $_SESSION['user_id'] ;
		$project_id = base64_decode($_GET["project"]) ;
		
		$website = $_GET["website"] ;
		
		$website_data = getTableData( $conn , " boost_website " , " id = '$project_id' " ) ;
		
		$is_data = getTableData( $conn , " check_installation " , " website_id = '$project_id' " ) ;
		// print_r($is_data) ;
		
		?>
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
				<div class="script_Icontainer">

					<div class="script_i_btn">
						<div class="text-right">
							<!-- <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button> -->
							<!-- <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button> -->
						</div>
					</div>

					<form  method="post" id="paymentFrm" data-complete="<?php if (empty(count($is_data))) {echo "0";}else{echo "1";}?>">
						<input type="hidden" name="script-installation" value="script-installation">
						<!-- tab 1 -->
						<div class="tab">
							<h3>Let’s install your Website Speedy script</h3>
							<p>The tracking script lets Website Speedy track your visitors. Without it, our report won't pick up any of your users' clicks.</p>
							<p>This AI based script will enhance your website loading speed by 3x leading to better Conversion, reduce your ad spent, better customer engagement and improved SEO ranking </p>
							<p class="tab_H">Select one of these other options:</p>
							<div class="row" style="justify-content: space-around;">
								<div class="col-md-4  it-radio <?php if($is_data["installation_type"] == 1 ){echo "is-selected";} ?>" >
									<label for="script_m">
										<input type="radio" name="installation-type" class="installation-type" id="script_m" value="1" <?php if($is_data["installation_type"] == 1 ){echo "checked";} ?>>
										<img src="data:image/png;base64,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" alt="Manual install" width="55px">
										<div class="text-body-2">I can install the tracking script myself</div>
									</label>
								</div>
								<div class="col-md-4  it-radio <?php if($is_data["installation_type"] == 2 ){echo "is-selected";} ?>">
									<label for="script_d">
										<input type="radio" name="installation-type" class="installation-type" id="script_d" value="2" <?php if($is_data["installation_type"] == 2 ){echo "checked";} ?>>
										<img src="data:image/png;base64,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" alt="Developer install" width="55px">
										<div class="text-body-2">I need my developer to add the script for me</div>
									</label>
								</div>

							</div>
							<div class="row sss" style="justify-content: space-around;margin-top:20px;">
								<div class="col-md-4  it-radio <?php if($is_data["installation_type"] == 3 ){echo "is-selected";} ?>" >
									<label for="script_h">
										<input type="radio" name="installation-type" class="installation-type"  id="script_h" value="3" <?php if($is_data["installation_type"] == 3 ){echo "checked";} ?>>
										<i class="fab fa-hire-a-helper"></i>
										<div class="text-body-2">Hire Us.</div>
									</label>
								</div>								
							</div>

							<div class="row mt-4">
								<div class="col-md-12" >
								Platforms we support - 

								</div>
<div class="row sel i_process_s platforms_suppot">

									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/shopify-inst.php?project=<?php echo base64_encode($project_id); ?>">
												<div class="logo_wrap">
								            <img src="./img/icons8-shopify-144.png">
										    </div>
												<h4>Shopify</h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
											<a href="https://websitespeedy.com/adminpannel/bigcommerce-inst.php?project=<?php echo base64_encode($project_id); ?>">
											<div class="logo_wrap">
								            <img src="./img/icons8-bigcommerce-144.png">
										    </div>
											<h4>Bigcommerce</h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/wordpress-inst.php?project=<?php echo base64_encode($project_id); ?>">
												<div class="logo_wrap">
								                    <img src="./img/icons8-wordpress-144.png">
								                </div>
												<h4 > Wordpress </h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												
													<a href="https://websitespeedy.com/adminpannel/shift4shop-inst.php?project=<?php echo base64_encode($project_id); ?>">
													<div class="logo_wrap">
								                       <img src="./img/Shift4Shop-Official-Logo.png">
										            </div>
													<h4 >Shift4shop</h4></a>
												
											</div>
										</div>
									</div>
								
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/magento-inst.php?project=<?php echo base64_encode($project_id); ?>">
												<div class="logo_wrap">
								                       <img src="./img/icons8-magento-144.png">
										            </div>
												<h4>Magento</h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/opencart-inst.php?project=<?php echo base64_encode($project_id); ?>">
												<div class="logo_wrap">
								                       <img src="./img/icons8-opencart-144.png">
										            </div>
												<h4>Opencart</h4> </a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/prestashop-inst.php?project=<?php echo base64_encode($project_id); ?>"> 
												<div class="logo_wrap">
								                       <img src="./img/Prestashop-logo.png">
										            </div>
												<h4>prestashop </h4></a>
											</div>
										</div>
									</div>
										<div class="col-md-3">
										<div class="card">
										<div class="body-card">
										<a href="https://websitespeedy.com/adminpannel/wix-inst.php?project=<?php echo base64_encode($project_id); ?>"> 
										<div class="logo_wrap">
								                       <img src="./img/icons8-wix-144.png">
										            </div>
										<h4>Wix </h4></a>
										</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/others-inst.php?project=<?php echo base64_encode($project_id); ?>">other</a>
											</div>
										</div>
									</div>
									<div class="col-md-3 show_more_btn">
										<div class="card" id="show_more">
												<a class="btn btn-primary">Show More</a>	
										</div>
									</div>

								</div>	
													

							</div>


						</div>

						<!-- tab 2 -->
						<div class="tab">
							<div class="script-text d-none">
								<?php
									// $website_data["platform"] = 'others' ;
									// echo $website_data["platform"] ;

									// get script urls -------------------------
									$script_urls = '' ;

									$sql = " SELECT * FROM `script_log` WHERE `site_id` = '$project_id' ";
									$result = $conn->query($sql);
									$urlFetch = $result->fetch_assoc();

									$url = $urlFetch['url'];

									$urlLists = explode(',' , $url);
									// die;
									$domain_url = "https://".$_SERVER['HTTP_HOST'];

									$count = 0;
									foreach ($urlLists as $urlList) 
									{
										// $defer = ($count == 0) ? "defer" : "" ;
										$defer ="" ;

										$script_urls .= '<code>&lt;script type="text/javascript" src="'.$domain_url.$urlList.'" '.$defer.' &gt;&lt;/script&gt;</code><br>' ;
										$count++; 
									}

									// print_r($script_urls) ;
									// -------------------------------------------

									switch ($website_data["platform"]) {
										case 'Shopify':
											require_once("installation-instructions/shopify-instructions.php") ;
											break;
									
										case 'Bigcommerce':
											require_once("installation-instructions/bigcommerce-instructions.php") ;
											break;
									
										case 'Wordpress':
											require_once("installation-instructions/wordpress-instructions.php") ;
											break;
									
										case 'Shift4Shop':
											require_once("installation-instructions/shift4shop-instructions.php") ;
											break;
									
										case 'Wix':
											require_once("installation-instructions/wix-instructions.php") ;
											break;
									
										case 'Magento':
											require_once("installation-instructions/magento-instructions.php") ;
											break;
									
										case 'Opencart':
											require_once("installation-instructions/opencart-instructions.php") ;
											break;
									
										case 'Prestashop':
											require_once("installation-instructions/prestashop-instructions.php") ;
											break;
										
										default:
											require_once("installation-instructions/others-instructions.php") ;
											break;
									}
								?>
								<div class="row sel i_process_s">
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/shopify-inst.php?project=<?php echo base64_encode($project_id); ?>">
												<div class="logo_wrap">
								            <img src="./img/icons8-shopify-144.png">
										    </div>
												<h4>Shopify</h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
											<a href="https://websitespeedy.com/adminpannel/bigcommerce-inst.php?project=<?php echo base64_encode($project_id); ?>">
											<div class="logo_wrap">
								            <img src="./img/icons8-bigcommerce-144.png">
										    </div>
											<h4>Bigcommerce</h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/wordpress-inst.php?project=<?php echo base64_encode($project_id); ?>">
												<div class="logo_wrap">
								                    <img src="./img/icons8-wordpress-144.png">
								                </div>
												<h4 > Wordpress </h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												
													<a href="https://websitespeedy.com/adminpannel/shift4shop-inst.php?project=<?php echo base64_encode($project_id); ?>">
													<div class="logo_wrap">
								                       <img src="./img/Shift4Shop-Official-Logo.png">
										            </div>
													<h4 >Shift4shop</h4></a>
												
											</div>
										</div>
									</div>
									
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/magento-inst.php?project=<?php echo base64_encode($project_id); ?>">
												<div class="logo_wrap">
								                       <img src="./img/icons8-magento-144.png">
										            </div>
												<h4>Magento</h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/opencart-inst.php?project=<?php echo base64_encode($project_id); ?>">
												<div class="logo_wrap">
								                       <img src="./img/icons8-opencart-144.png">
										            </div>
												<h4>Opencart</h4> </a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/prestashop-inst.php?project=<?php echo base64_encode($project_id); ?>"> 
												<div class="logo_wrap">
								                       <img src="./img/Prestashop-logo.png">
										            </div>
												<h4>prestashop </h4></a>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
										<div class="body-card">
										<a href="https://websitespeedy.com/adminpannel/wix-inst.php?project=<?php echo base64_encode($project_id); ?>"> 
										<div class="logo_wrap">
								                       <img src="./img/icons8-wix-144.png">
										            </div>
										<h4>Wix </h4></a>
										</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="card">
											<div class="body-card">
												<a href="https://websitespeedy.com/adminpannel/others-inst.php?project=<?php echo base64_encode($project_id); ?>"><h4>other</h4></a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="need-developer d-none">

								<div class="row">
									<div class="text-center">
										<h3>Alright, let's contact your web developer!</h3>
									</div>
									<div class="text-center mb-2">
										<p>Tell us about your web developer and we'll contact them with all the details they need:</p>
									</div>
									<?php
										// if (empty(count($is_data))) 
										if (1==1) 

										{
											?>
											<div class="form_h">
												<div class="form-group">
													<label>Web developer's name</label>
													<input type="text" class="form-control need-developer-input" id="name" name="developer-name" value="<?=$is_data["developer_name"];?>">
												</div>
												<div class="form-group">
													<label for="exampleInputPassword1">Web developer's email</label>
													<input type="email" class="form-control need-developer-input" id="email" name="developer-email" value="<?=$is_data["developer_email"]?>">
												</div>
												<div class="form-group">
													<label>Site to install the tracking script</label>
													<input type="text" class="form-control" value="<?=$website_data["website_url"]?>" readonly>
												</div>
												<div class="form-group">
													<label for="message">Send a personalized message to your favorite developer (optional)</label>
													<textarea class="form-control" id="message" name="developer-message"><?=$is_data["developer_message"];?></textarea>
												</div>
												<div class="form-group"  id="complete-installation-section" >
								                   <label  class="form-check-label mb-2 ml-4" for="complete-installation">
								                        <input type="checkbox" class="form-check-input" id="complete-installation" name="complete-installation" value="1"  >I have completed the all installation process.
							                       </label>
								                  <div class="form_h_submit">
								                    <button type="button" id="nextBtn" onclick="nextPrev(1)">Submit</button>
									              </div>	
							                    </div>
											</div>

											<?php
										}
										elseif ( $is_data["installation_type"] == 2 ) 
										{
											?>
											Script Installation manual is sent to your developer. 
											<?php
										}
										else {
											?>
											Your Script installation response is already sent.
											<?php
										}
									?>

								</div>
							</div>
							<div class="hire-us d-none">

								<div class="row">
									<div class="text-center my-3">
									<?php

										// print_r($website_data) ;

										 $plan_type = $website_data["plan_type"]  ;

										if ( $plan_type == "Free" ) 
										{
											$swlplan = " SELECT plans.s_type , plans.name , user_subscriptions_free.* FROM user_subscriptions_free , plans WHERE user_subscriptions_free.user_id = '$user_id' AND user_subscriptions_free.plan_id = plans.id AND user_subscriptions_free.status = 1 ";
										}
										else 
										{
											$swlplan = " SELECT plans.s_type , plans.name , user_subscriptions.* FROM user_subscriptions , plans WHERE user_subscriptions.user_id = '$user_id' AND user_subscriptions.plan_id = plans.id AND user_subscriptions.is_active = 1 ORDER BY user_subscriptions.id  DESC LIMIT 1 ";
										}

										$us_query = $conn->query($swlplan) ;
										$us_data =  ( $us_query->num_rows > 0 ) ? $us_query->fetch_assoc() : [] ;

										// echo "swlplan : ".$swlplan."<hr>";
										// print_r($us_data) ; echo "<hr>" ; 
										
										$hireUsHeading = '' ;
										if ( $plan_type == "Free" ) {
											$hireUsHeading = '';
										}
										else 
										{
											if ( $us_data["s_type"] == "Free"  ) {
												$hireUsHeading = 'Pay Only 50$ our team member will check and add the script process.';
											}
											elseif ( $us_data["s_type"] == "Silver" || $us_data["s_type"] == "Gold" ) {
												$hireUsHeading = 'Expert Help for installation';
											}
											elseif ( $us_data["s_type"] == "Diamond" || $us_data["s_type"] == "Pro" ) {
												$hireUsHeading = 'Expert Help for installation'; 
											}
										}
									?>
									<h3><?=$hireUsHeading?></h3>
									</div>
									<div class="form_h">
									<?php 

										// print_r($is_data) ; echo "<hr>" ;

										if ( $plan_type == "Free not show" ) 
										{
											// if (empty(count($is_data))) 
											if (1==1) 
											{
												?>
												<input type="hidden" name="hire-payment" value="0">
												<input type="hidden" name="item-name" id="item-name" value="Script Installation Payment">
												<input type="hidden" name="amount" id="amount" value="50">
												<input type="hidden" name="currency" id="currency" value="USD">
												<div class="form-group">
													<label>Name on Card</label>
													<input type="text" name="hire-cardname" id="card" placeholder="Name On Card" class="form-control hire-us-input" required>
												</div>
												<div class="form-group">
													<label>Card Number</label>
													<input type="text" name="hire-cardnumber" id="cardNumber" placeholder="Enter Card Number" class="form-control hire-us-input" maxlength="18" required>
												</div>
												<div class="row">
													<div class="form-group col-md-6">
														<label>MM/YYYY</label>
														<div class="row">
															<input type="text" name="hire-cardmm" id="cardExpMonth" placeholder="MM" class="form-control" maxlength="2">
															<input type="text" name="hire-cardyyyy" id="cardExpYear" placeholder="YYYY" class="form-control hire-us-input" maxlength="4">
														</div>
													</div>
													<div class="form-group col-md-6">
														<label>CVV</label>
														<input type="text" name="hire-cardcvv" id="cardCVC" placeholder="CVV" class="form-control hire-us-input" maxlength="5">
													</div>
												</div>
												<button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
												<?php
											}
											elseif ( ($is_data["installation_type"] == 3) && ($is_data["status"] == "succeeded") ) {
												?>
												Thanks for your payment our expert will help for script installation shortly.
												<?php
											}
											else {
												?>
												Your Script installation response is already sent.
												<?php
											}
										}
										else 
										{
											if ( $us_data["s_type"] == "Free"  ) 
											{
												if ( ($is_data["installation_type"] == 3) && ($is_data["status"] == "succeeded") ) 
												{
													?>
													Thanks for your payment our expert will help for script installation shortly.
													<?php
												}
												else 
												{
													?>
													<input type="hidden" name="hire-payment" value="0">
													<input type="hidden" name="item-name" id="item-name" value="Script Installation Payment">
													<input type="hidden" name="amount" id="amount" value="50">
													<input type="hidden" name="currency" id="currency" value="USD">
													<div class="form-group">
														<label>Name on Card</label>
														<input type="text" name="hire-cardname" id="card" placeholder="Name On Card" class="form-control hire-us-input" required value="<?=$is_data["hire_cardname"];?>">
													</div>
													<div class="form-group">
														<label>Card Number</label>
														<input type="text" name="hire-cardnumber" id="cardNumber" placeholder="Enter Card Number" class="form-control hire-us-input" required value="<?=$is_data["hire_cardnumber"];?>">
													</div>
													<div class="row">
														<div class="form-group col-md-6">
															<label>MM/YYYY</label>
															<div class="row">
																<input type="text" name="hire-cardmm" id="cardExpMonth" placeholder="MM" class="form-control" maxlength="2" value="<?=$is_data["hire_cardmm"];?>">
																<input type="text" name="hire-cardyyyy" id="cardExpYear" placeholder="YYYY" class="form-control hire-us-input" maxlength="4" value="<?=$is_data["hire_cardyyy"];?>">
															</div>
														</div>
														<div class="form-group col-md-6">
															<label>CVV</label>
															<input type="text" name="hire-cardcvv" id="cardCVC" placeholder="CVV" class="form-control hire-us-input" maxlength="5" value="<?=$is_data["hire_cardcvv"];?>">
														</div>
													</div>
													<?php
												}
											}
											elseif ( $us_data["s_type"] == "Silver" || $us_data["s_type"] == "Gold" || $us_data["s_type"] == "Diamond" || $us_data["s_type"] == "Pro" ) 
											{
											// echo $us_data["s_type"];
											 if($plan_type !="Free"){			
												if ( !empty($is_data["script_expert_help"]) ) 
												{
													?>
													Thanks your query. Our expert will contact you soon. Please wait for response.
													<p>Or</p>
													<a href="<?=HOST_URL?>adminpannel/support-ticket.php" class="btn btn-primary">Create New Ticket</a>

													<?php
												}
												else 
												{
													?>
													<input type="hidden" name="hire-payment" value="1">
													<div class="form-group">
														<label>Please provide you query about script installation.</label>
														<textarea class="form-control hire-us-input" name="script-expert-help" id="script-expert-help" placeholder="Enter your details query for script installation..."></textarea>
													</div>
													<?php if($website_data["platform"]=="Shopify")
													{
													 ?>
													<div class="form-group" id="collaborator-access-section" >
														<label class="form-check-label mb-2 ml-4"><input type="checkbox" class="form-check-input" id="collaborator-access" name="collaborator-access" value="1">Have you sent collaborator access to ishan@makkpress.com</label>
													</div>
												<?php
												 }  

												?> <div class="form_h_submit">
													<button type="button" id="nextBtn" onclick="nextPrev(1)">Submit</button>	
												</div>
													<?php
												}
												}
												else{
													?>
													Please get plan to use this service. <a href="<?=HOST_URL?>plan.php" class="btn btn-primary">Get Subscription</a>

													<?php
												}

											}
										}
											
									?>
									</div>
								</div>
							</div>
						</div>
					</form>


					<div class="script_i_btn">
						<div>
							<!-- <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button> -->
							<!-- <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<script>
var scriptTab = 0; 
<?php //if (count($planssubscriptions_free)>0) { echo "showTab(1)" ;}?>
// Display the current tab
$(".installation-type").click(function(){
var v= $(".installation-type:checked").val();
console.log(v);
nextPrev(1);
// setTimeout(function(){
// 	$("#nextBtn").click();
// },200);
	// $("#nextBtn").click();
});
	$(".platforms_suppot .col-md-3").eq(3).nextAll('.col-md-3:not(:last)').hide();

$("#show_more").click(function(){
	if($(this).attr("show")==0){
	$(".platforms_suppot .col-md-3").eq(3).nextAll('.col-md-3:not(:last)').hide();
	$(this).find("a").html("Show More");
	$(this).attr("show","1");

	}
	else{
	$(".platforms_suppot .col-md-3").show();
	$(this).find("a").html("Less");
	$(this).attr("show","0");
	}

});


showTab(scriptTab); 

function showTab(n) {

	console.log("n : "+n) ;

	$(".tab:eq("+n+")").css({"display":"block"}) ;
	if (n == 0) {
		document.getElementById("prevBtn").style.display = "none";
	} else {
		document.getElementById("prevBtn").style.display = "inline";
	}

	var x = $(".tab") ;

	if (n == (x.length - 1)) {

		var complete = $("#paymentFrm").attr("data-complete") ;
		// if ( complete == 1 ) { $("#nextBtn").hide(); }else { $("#nextBtn").show(); }

		document.getElementById("nextBtn").innerHTML = "Submit";
	} 
	else {
		$("#nextBtn").show() ;
		document.getElementById("nextBtn").innerHTML = "Next";
	}
}

function nextPrev(n) {
console.log("nsssss="+n);
	$(".it-radio , .hire-us-input , .need-developer-input").removeClass("invalid") ;
	$("#installation-wraning").remove(); 

	// This function will figure out which tab to display
	var x = document.getElementsByClassName("tab");
	// Exit the function if any field in the current tab is invalid:
	if (n == 1 && !validateForm()) return false;
	// Hide the current tab:
	if ( scriptTab < x.length ) {
		x[scriptTab].style.display = "none";
	}
	// Increase or decrease the current tab by 1:
	scriptTab = scriptTab + n;
	// if you have reached the end of the form...
	if (scriptTab >= x.length) {
		// ... the form gets submitted:

		var type = $(".installation-type:checked").val() ;
		if ( type == '3' ) 
		{
			if ( $('#cardNumber').length > 0 ) {
				Stripe.createToken({
					number:$('#cardNumber').val(),
					cvc:$('#cardCVC').val(),
					exp_month : $('#cardExpMonth').val(),
					exp_year : $('#cardExpYear').val()
				}, stripeResponseHandler);
			}
			else {
				document.getElementById("paymentFrm").submit();
			}
		}
		else {
			document.getElementById("paymentFrm").submit();
		}
		return false;
	}
	// Otherwise, display the correct tab:
	showTab(scriptTab);
}

function validateForm() {

	var valid = true;

	if ( scriptTab == 0 ) 
	{

		$(".it-radio").removeClass("invalid") ;
		var type = $(".installation-type:checked").val() ;
		if ( type == undefined || type == '' || type == null ) {
			$(".it-radio").addClass("invalid") ;
			valid = false;
		}
		else 
		{
			$(".script-text , .need-developer , .hire-us").addClass("d-none");
			$("#complete-installation-section").removeClass("d-none");

			switch (type) { 
				case '2': 
					$(" .need-developer").removeClass("d-none");
					 $(".form-check-label").hide();
					break;
				case '3': 
					$(".hire-us").removeClass("d-none");
					$("#complete-installation-section").addClass("d-none");
					break;
				default:
					$(".script-text").removeClass("d-none");

		           $("#complete-installation-section").hide();
					break;
			}	
		}

	}
	else if( scriptTab == 1 ) 
	{
		if( $("#installation-wraning").length > 0 ) { $("#installation-wraning").remove(); }

		var type = $(".installation-type:checked").val() ;

		if ( type != '3' ) 
		{
			var check = $("#complete-installation").prop("checked") ;
			// if ( ! check ) {
			// 	$("#complete-installation-section").append("<p id='installation-wraning' style='color:red;'>Please select the above option.</p>") ;
			// 	valid = false;
			// }
		}


		if ( type == '2' ) 
		{
			$(".need-developer-input").removeClass("invalid") ;
			$(".need-developer-input").each(function(i,o)
			{
				var v = $(o).val() ;
				if ( v == undefined || v == '' || v == null ) {
					$(o).addClass("invalid") ;
					valid = false;
				}
			});
		}
		else if ( type == '3' ) 
		{
			var hu_flag = 0 ;
			$(".hire-us-input").removeClass("invalid") ;
			$(".hire-us-input").each(function(i,o)
			{
				var v = $(o).val() ;
				if ( v == undefined || v == '' || v == null ) {
					$(o).addClass("invalid") ;
					valid = false;
					hu_flag = 1 ;
				}
			});

			if ( $("#collaborator-access").length > 0 ) 
			{
				var check = $("#collaborator-access").prop("checked") ;
				if ( ! check ) {
					$("#collaborator-access-section").append("<p id='installation-wraning' style='color:red;'>Please select the above option.</p>") ;
					valid = false;
				}
			}
		}
	}
	else {
		valid = false;
	}

	// If the valid status is true, mark the step as finished and valid:
	if (valid) {
		$(".step:eq("+scriptTab+")").addClass("finish") ;
		// document.getElementsByClassName("step")[scriptTab].className += " finish";
	}

	return valid; // return the valid status
}


function stripeResponseHandler(status, response) {
	$('.alert-status').html('');
	if(response.error) {
		$('.alert-status').html(response.error.message);
	} 
	else 
	{
		var stripeToken = response['id'];

		if ($("input[name='stripeToken']").length > 0) {
			$("input[name='stripeToken']").val(stripeToken) ;
		}
		else {
			$('#paymentFrm').append("<input type='hidden' name='stripeToken' value='" + stripeToken + "' />");
		}
		$('#paymentFrm').submit();
	}
}

$(document).ready(function() {

	// $("#stripe-btn").click(function(e){
	// 	e.preventDefault() ;

	// 	Stripe.createToken({
	// 		number:$('#cardNumber').val(),
	// 		cvc:$('#cardCVC').val(),
	// 		exp_month : $('#cardExpMonth').val(),
	// 		exp_year : $('#cardExpYear').val()
	// 	}, stripeResponseHandler);

	// 	return false;
	// });


	$(".select-platform").click(function(){
		var v = $(this).val() ;

		$("#other-platform").val("");
		$(".other-platform-input").addClass("d-none");
		if ( v == "Other" ) {
			$(".other-platform-input").removeClass("d-none");
		}
	});

});
</script>
