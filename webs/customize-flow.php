<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');

require_once("/var/www/html/adminpannel/env.php") ;
require_once("adminpannel/config.php") ;
require_once("adminpannel/inc/functions.php");
require 'adminpannel/smtp-send-grid/vendor/autoload.php';

// if ( !checkUserLogin() ) {
//     header("location: ".HOST_URL."signup.php") ;
//     die() ;
// }

	

$user_id = $_SESSION["user_id"] ; 

$get_flow = $conn->query(" SELECT * FROM `admin_users` WHERE id = '$user_id' ");
$d = $get_flow->fetch_assoc();
$plan_country = "";


$user_email = $d["email"] ;
$user_name = $d["firstname"] ;

//123
// if($d['phone'] == "" || $d['phone'] == NULL){
// 	header("location: ".HOST_URL."basic_details.php") ;
// 	die();		
// }


$self_install = $d['self_install'];

if($d['country'] != "101"){
	$plan_country = "-us";
}
		
if($d['flow_step']==1) {

	// header("location: ".HOST_URL."plan.php") ;

	if($d['user_type'] == "AppSumo" || $d['user_type'] == "Dealify" || $d['user_type'] == "DealFuel") {
		// echo "Lifetime";
		// echo 1; die;
		// die("dashboard") ;
		header("location: ".HOST_URL."adminpannel/dashboard.php") ;
		die();
	}
	else {

		$get_flow_web = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
		$d_web = $get_flow_web->fetch_assoc();

		if($self_install == 'yes'){
			// echo 2;die;
			// header("location: ".HOST_URL."thanks-installation.php") ;
			header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($d_web['id'])) ;//123
		}	
		else{
			// echo 3;die;
			// echo "<script>alert(2)</script>" ;
			header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($d_web['id'])) ;
			die;
		}

		die();
	}

}

$customer_Name = $d['firstname'];
$name_parts = preg_split("/[\s,]+/", $d['firstname']);
$h_fname = $name_parts[0];
$h_email = $d['email'];

$email_status = $d['email_status'];

$h_lname = array_slice($name_parts, -1)[0];
if($h_lname == ""){
	$h_lname = "Speddy";
}
$pass =  base64_decode($d['help_pass']);

if ( isset($_POST["customize-flow"]) ) {
	// echo "<pre>"; print_r($_POST) ;  die;
	//  print_r($_SESSION) ;

	//123
	
	
	if($_POST['website-platform']=='Wordpress/Woocommerce'){
		  $customisePlt = 'not_service';
		//  die;
	}
	else{
	
		if($_POST['role']=='Other'){
			$company_role = $_POST['other-input-data'];
		}else{
			$company_role = $_POST['role'];
		}
		
			// echo $company_role; die;
		//  die();
		//123-start
		$country      = $_POST['country'];	
		$self_install = $_POST['self_install'];	
		//123
		$sql = "UPDATE `admin_users` SET  self_install = '$self_install', country = '$country', company_role='$company_role'  WHERE `id` = '" . $user_id . "'; "; 
		$conn->query($sql);	//123

		if($conn->query($sql)==true){
			// echo "country updated";
		}		 
		unset($_POST['country']);
		//123-ends
		
		foreach ($_POST as $key => $value) {
			$_POST[$key] = $conn->real_escape_string($value) ;
		}

		$describe_company = $_POST['describe-company'] ;
		$website_platform = $_POST['website-platform'] ;
		$other_platform = $_POST['other-platform'];
		if($other_platform=="" || $other_platform == null){
			$other_platform = $_POST['other-platform-custom'];
		}


		$website_source = $_POST['website-platform'] ;
		$other_source = $_POST['other-source'] ;
		$website_url = strtolower($_POST['website-url']) ;
		$shopify_url = strtolower($_POST['shopify-url']) ;

		if ( empty($website_platform) || empty($website_url) ) {
			$_SESSION['error'] = "Please fill all required values." ;
		}
		elseif ( ($website_platform == "Other") && empty($other_platform) ) {
			$_SESSION['error'] = "Please provide other platform name." ;
		}
		elseif ( ! filter_var($website_url, FILTER_VALIDATE_URL) ) {
			$_SESSION['error'] = "Please enter a valid website url." ;
		}
		elseif ( $website_platform == "Shopify" && ( empty($shopify_url) || (! filter_var($shopify_url, FILTER_VALIDATE_URL)) ) ) {
			$_SESSION['error'] = "Please enter a valid shopify admin url." ;
		}
		else {
		

			$user_id = $_SESSION["user_id"] ;


			/*** flow_step ***/ 
			$website_category = $conn->real_escape_string($_POST['website_category']) ;
			$website_platform = $conn->real_escape_string($_POST['website-platform']) ;
			$platforms = ($website_platform == "Other") ? $conn->real_escape_string($_POST['other-platform']) : $conn->real_escape_string($_POST['other-platform-custom']) ;
			$website_source = $conn->real_escape_string($_POST['website-source']) ;
			$other_source_name = $conn->real_escape_string($_POST['other_source_name']) ;//123
			$website_url = $conn->real_escape_string($_POST['website-url']) ;
			$shopify_url = $conn->real_escape_string($_POST['shopify-url']) ;

			$query = $conn->query(" SELECT * FROM `flow_step` WHERE `user_id` = '$user_id' LIMIT 1 ; ") ;
			if ( $query->num_rows > 0 ) {

				$fs_data = $query->fetch_assoc() ;

				$id = $fs_data["id"] ;
				//123
				$sql = " UPDATE `flow_step` SET `website_category`='$website_category' , `platforms_name`='$website_platform' , `platforms`='$platforms' , `source`='$website_source' , `other_source_name`='$other_source_name', `website_url`='$website_url' , `shopify_domain_url`='$shopify_url' WHERE `id`='$id' ; " ;
			}
			else {
				//123
				$sql = " INSERT INTO `flow_step`( `user_id` , `website_category`, `platforms_name`, `platforms`, `source`,`other_source_name`, `website_url`, `shopify_domain_url` ) VALUES ( '$user_id' , '$website_category' , '$website_platform' , '$platforms' , '$website_source' , '$other_source_name', '$website_url' , '$shopify_url' ) ; " ;
			}
			$conn->query($sql); 
			/*** END flow_step ***/ 
	
			$desktop_score = 0 ; $mobile_score = 0 ;
			

			$sql = " INSERT INTO `manager_company`( `user_id` , `company_type` ) VALUES ( '$user_id' , '$describe_company' ) ; " ;
			if($conn->query($sql) ){
			//123
				$sql2 = " INSERT INTO `boost_website`( `manager_id`, `platform`, `platform_name`, `website_url`, `shopify_url`, `desktop_speed_old`, `mobile_speed_old`, `desktop_speed_new`, `mobile_speed_new`,`new_website` ) VALUES ( '$user_id' , '$website_platform' , '$other_platform' , '$website_url' , '$shopify_url' , '$desktop_score' , '$mobile_score' , '$desktop_score' , '$mobile_score','new_website' ) ; " ;

				if(	$conn->query($sql2) ){
					$last_website_id = mysqli_insert_id($conn);
					$sql3 = " UPDATE admin_users SET flow_step = 1 where id = '$user_id'" ;
					$conn->query($sql3); 
		
				}

				if($d['sumo_code'] !="" && $d['sumo_code'] !="null"){
					// echo "Lifetime";





								$plan_period_end =Date('Y-m-d', strtotime('+18250 days'));
						sleep(1);
						$queryL = "INSERT INTO user_subscriptions (user_id,plan_id,payment_method,stripe_subscription_id,stripe_customer_id,stripe_payment_intent_id,paid_amount,
							total_tax,plan_price,paid_amount_currency,site_count,plan_interval,plan_interval_count,payer_email,created,plan_period_start,plan_period_end,status) VALUES ('$user_id','15','stripe','xxxxxxxxxxxx','xxxxxxxxxxx','xxxxxxxxxxxxx','0','0','0','USD','1','Lifetime','1','$email',now(),now(),'$plan_period_end','succeeded')"; 	

							$execute = mysqli_query($conn,$queryL);
							$last_id = mysqli_insert_id($conn);
						sleep(1);
							$sql3 = " UPDATE boost_website SET subscription_id = '$last_id' , plan_id='15', plan_type = 'Subscription' where id = '$last_website_id'" ;
							$conn->query($sql3); 

					$sql3 = " UPDATE admin_users SET flow_step = 2 where id = '$user_id'" ;
					$conn->query($sql3); 






								header("location: ".HOST_URL."adminpannel/dashboard.php") ;


				}
				else{	

					if($self_install == 'yes'){



								$emailContent = getEmailContent($conn, 'Fully Install Website Speddy');
								$smtpDetail = getSMTPDetail($conn);
			
								$emailsss = new \SendGrid\Mail\Mail(); 
								$emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
								$emailsss->setSubject($emailContent["subject"]);
								$emailsss->addTo($h_email,"Website Speddy");
								$emailsss->addContent("text/html",$emailContent["body"]);
								$sendgrid = new \SendGrid($smtpDetail["password"]);





							$sql = "INSERT INTO generate_script_request (manager_id, website_id, website_url, request_from) VALUES('".$d['id']."','".$last_website_id."','".$website_url."','Register Page')";
				
							if ($conn->query($sql)) { 
								
							}

							if($email_status == 1){
								$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('".$d['id']."', '".$emailContent["subject"]."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE())");

									$sendgrid->Send($emailsss);
							}
							else{
								$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at, status) VALUES('".$d['id']."', '".$emailContent["subject"]."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE(), 'Email Notification Off')");			       	   	
							}

								if (1==1) {



							$emailContent = getEmailContent($conn, 'Admin Emails');
							$body = "
								<tr><td><b>Request From  : Register Page </td></tr>

								<tr><td><b>Type  : Fully Install Website Speddy </td></tr>
								
								<tr><td>Email : $h_email </td></tr>
								<tr><td>Name : $customer_Name </td></tr>
								<tr><td>Website Url : $website_url </td></tr></tr> 

							";
						
								$emailContent = str_replace('{{body}}', $body, $emailContent["body"]);
						

							// get SMTP detail ---------------
							$smtpDetail = getSMTPDetail($conn);
		
							$emailsssA = new \SendGrid\Mail\Mail(); 
							$emailsssA->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
							$emailsssA->setSubject("Fully Install & Parameter add request from ".$h_email);
							$emailsssA->addTo("service@websitespeedy.com","Website Speddy");
							$emailsssA->addContent("text/html",$emailContent);
							$sendgrid = new \SendGrid($smtpDetail["password"]);
						

							$sub = "Fully Install & Parameter add request from ".$h_email;


								$get_flow_web = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
								$d_web = $get_flow_web->fetch_assoc();

								$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('1', '".$sub."', '".$conn->real_escape_string($emailContent)."', CURDATE())");

									if ( $sendgrid->Send($emailsssA)) {
										// echo 5;
										$_SESSION['selfInstallation'] = 'yes';
										// echo $d_web['id']; die;
										header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($d_web['id'])) ;//123
		
									// header("location: ".HOST_URL."thanks-installation.php") ;//123

									}
								}

					
					}	
					else{

						if($d['sumo_new'] ==1){
							header("location: ".HOST_URL."adminpannel/dashboard.php") ;
							exit();
						}

						echo "<script>alert(1)</script>" ;
						header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($last_website_id)) ;


					}			
				}
				die();
			}

			header("location: ".HOST_URL."customize-flow.php") ;
			die();

		}
	}
	
	
}




		$get_flow = $conn->query(" SELECT * FROM `flow_step` WHERE user_id = '$user_id' ");
		$describes_your_company = "";
		$platforms = "";
		$platforms_other = "";
		$source = '';
		$website_url = "";
		$shopify_domain_url = "";
		$website_category ="";


		if($get_flow->num_rows > 0 ){
			$d = $get_flow->fetch_assoc();
			$describes_your_company = $d['describes_your_company'];
			$platforms = $d['platforms'];
			$platforms_other = $d['platforms_name'];
			$source = $d['source'];
			$website_url = $d['website_url'];
			$shopify_domain_url = $d['shopify_domain_url'];
			$website_category = $d['website_category'];
		}



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home</title>
	<link rel="icon" type="image/x-icon" href="img/favicon.ico" />

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->
	
<?php 
if(preg_match('/www/', $_SERVER['HTTP_HOST']))
{
  $url = str_replace("www.","",$_SERVER['HTTP_HOST']);
  header("location: https://$url$_SERVER[REQUEST_URI]");
  die();
}

 $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 $actual_link = explode("?", $actual_link)
?>
<link rel="canonical" href="<?=$actual_link[0]?>" />

	
	<?php require_once('inc/style-script.php'); ?>

<style>

* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}
.error{
	display: none;
	color:red;
}



#regForm {
  background-color: #ffffff;
  margin:auto;
  padding: 15px 30px;
  width: 70%;
  min-width: 300px;
}

h1 {
  text-align: center;  
}

input {
  padding: 10px;
/*  width: 100%;*/
  font-size: 17px;
  border: 1px solid #aaaaaa;
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
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}
.steps.active span.label {
    color: #27ae60 !important;
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

.steps.active .circle {
    background: #27ae60;
    color: #fff !important;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}

.alert__wrapper {
	width: 70%; 
	margin: 0px auto;
}
.form_btns {
    display: flex;
    align-items: center;
    gap: 10px;
}

</style>
</head>
<body>


	
<div class="customize_wrapper new__page">
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="glass"></div>
	<div class="form_topHead">
		<h3>Let’s Speed Up your Website</h3>
		<p>Let WebsiteSpeedy Understand Your Requirements for Tailored Results!</p>
	</div>
	<div class="container customize_flow">

		<input type="hidden" id="user_id" value="<?=$user_id?>">
		<input type="hidden" id="user_email" value="<?=$user_email?>">
		<input type="hidden" id="firstname" value="<?=$user_name?>">

		<div class="stepper_wrapper">
			<ul class="stepper stepper-horizontal">
				<li class='steps active <?php if($website_category!= "" && $source!=""){echo "complete";} ?>' >
					<a href="#step1">
						<div class="step_name_s">
							<span class="circle p-2">
								<span class="step_no">1</span><?xml version="1.0" ?>
								<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
									<path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/>
								</svg>
							</span>
							<span class="label">First step</span>
						</div>
						<div class="step_fill_sc">
							<div class="step_fill_s">
								<span></span>
							</div>
						</div>
					</a>
				</li>
				<li  class='steps <?php if($platforms!=""){echo "complete";} ?>'>
					<a href="#step2">
						<div class="step_name_s">
							<span class="circle p-2">
								<span class="step_no">2</span><?xml version="1.0" ?>
								<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
									<path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/>
								</svg>
							</span>
							<span class="label">Second step</span>
						</div>
						<div class="step_fill_sc">
							<div class="step_fill_s">
								<span></span>
							</div>
						</div>
					</a>
				</li>
				<!--     <li  class='steps <?php if($website_url!=""){echo "complete";} ?>'>
					<a href="#step4">
					<div class="step_name_s">
					    <span class="circle p-2"><span class="step_no">5</span><?xml version="1.0" ?><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg></span>
					<span class="label">Fifth step</span>
					</div>
					</a>
					
					</li> -->
			</ul>
		</div>
	</div>
	<div class="container customize_flow">
		<div class="alert__wrapper" >
			<?php require_once('inc/alert-message.php') ; ?>	
		</div>
		<div class="loader" style="display: none;"></div>
		<form id="regForm" method="post">
			<!-- //123 -->
			<input type="hidden" name="customize-flow" value="customize-flow">
			<div class="tab">
				<!-- //123 -->
				<div class="col-12">
					<div class="form-group">
						<label for="Country">Country</label>
						<div class="for__arrow country">
							<select id="Country" class="form-control" name="country" required>
								<?php
									$selected = "231";
										$list_countries = getTableData( $conn , " list_countries " , " 1 " , "" , 1 ) ;
										foreach ($list_countries as $key => $country_data) { 
									?>          
								<option value='<?=$country_data["id"]?>' <?php if($country_data["id"]==$selected){echo "selected";} ?> ><?=$country_data["name"]?></option>
								<?php
									}
									?>
							</select>
						</div>
					</div>
				</div>
				<div class="sub sub1 stp_con">
					<label>Which of the following describes you/your company?</label>
					<span class="error err4">Please select any one option from below.</span><br>
					<label class="form-control">
						<input type="radio" name="website_category" class="website_category" value="Ecommerce & retail website" <?php if($website_category == 'Ecommerce & retail website'){echo 'checked';} ?>>
						<div class="check"></div>
						Ecommerce & retail website
					</label>
					<label class="form-control">
						<input type="radio" name="website_category" class="website_category" value="B2B website" <?php if($website_category == 'B2B website'){echo 'checked';} ?>>
						<div class="check"></div>
						B2B website
					</label>
					<label class="form-control">
						<input type="radio" name="website_category" class="website_category" value="Agency" <?php if($website_category == 'Agency'){echo 'checked';} ?>>
						<div class="check"></div>
						Agency
					</label>
					<label class="form-control">
						<input type="radio" name="website_category" class="website_category" value="Consultant/Freelancer" <?php if($website_category == 'Consultant/Freelancer'){echo 'checked';} ?>>
						<div class="check"></div>
						Consultant / Freelancer
					</label>
					<label class="form-control">
						<input type="radio" name="website_category" class="website_category" value="Lead generation website" <?php if($website_category == 'Lead generation website'){echo 'checked';} ?>>
						<div class="check"></div>
						Lead generation website
					</label>
					<label class="form-control">
						<input type="radio" name="website_category" class="website_category" value="Non-ecommerce content website" <?php if($website_category == 'Non-ecommerce content website'){echo 'checked';} ?>>
						<div class="check"></div>
						Non-ecommerce content website
					</label>
					<label class="form-control">
						<input type="radio" name="website_category" class="website_category" value="Blogs" <?php if($website_category == 'Blogs'){echo 'checked';} ?>>
						<div class="check"></div>
						Directories / Portals / Blogs
					</label>
					<label class="form-control">
						<input type="radio" name="website_category" class="website_category" value="Company" <?php if($website_category == 'Company'){echo 'checked';} ?>>
						<div class="check"></div>
						Company / Enterprise
					</label>
					
					
				</div>
				<!-- //123 -->
				<!-- //123 -->
				<div class="sub sub1 stp_con" >
					<label>What is your Role in the company?</label>
					<span class="error roleErr">Please select any one option from below.</span><br>
					<label class="form-control">
						<input type="radio" name="role" class="role" value="owner/Founder">
						<div class="check"></div>
						Owner / Founder
					</label>
					<label class="form-control">
						<input type="radio" name="role" class="role" value="Developer" >
						<div class="check"></div>
						Developer
					</label>
					<label class="form-control">
						<input type="radio" name="role" class="role" value="Marketing" >
						<div class="check"></div>
						Marketing 
					</label>
					<label class="form-control">
						<input type="radio" name="role" class="role otherRole" value="Other" >
						<div class="check"></div>
						Other 
					</label>
					<div class="form-group other-input-data" style="display:none" >
						<input type="text" class="form-control" id="other-input-data" name="other-input-data" placeholder="Enter your other role" value="">
						<span class="error otherRoleErr">Please enter your role.</span>
					</div>
				</div>
			</div>
			<!-- Third Step -->
			<div class="tab">
				<div class="sub sub1 stp_con">
					<label>What is your website Platforms?</label>
					<span class="error err2">Please select any one option from below.</span><br>
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Shopify" <?php if($platforms == 'Shopify'){echo 'checked';} ?>>
						<div class="check"></div>
						Shopify
					</label>
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Bigcommerce" <?php if($platforms == 'Bigcommerce'){echo 'checked';} ?>>
						<div class="check"></div>
						Bigcommerce
					</label>
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Wix" <?php if($platforms == 'Wix'){echo 'checked';} ?>>
						<div class="check"></div>
						Wix
					</label>
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="SquareSpace" <?php if($platforms == 'SquareSpace'){echo 'checked';} ?>>
						<div class="check"></div>
						SquareSpace
					</label>
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Clickfunnels" <?php if($platforms == 'Clickfunnels'){echo 'checked';} ?>>
						<div class="check"></div>
						Clickfunnels
					</label>
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Shift4Shop" <?php if($platforms == 'Shift4Shop'){echo 'checked';} ?>>
						<div class="check"></div>
						Shift4Shop
					</label>
					<!-- //123 -->
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Wordpress" <?php if($platforms_other == 'Wordpress'){echo 'checked';} ?>>
						<div class="check"></div>
						Wordpress / Woocommerce
					</label>
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Webflow" <?php if($platforms_other == 'Webflow'){echo 'checked';} ?>>
						<div class="check"></div>
						Webflow
					</label>
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Saas" <?php if($platforms_other == 'Saas'){echo 'checked';} ?>>
						<div class="check"></div>
						Saas Platform
					</label>
					<!-- //123 -->
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Custom Website" <?php if($platforms_other == 'Custom Website'){echo 'checked';} ?>>
						<div class="check"></div>
						Custom Website
					</label>				
					
					<label class="form-control">
						<input type="radio" class="select-platform" name="website-platform" value="Other" <?php if($platforms_other == 'Other'){echo 'checked';} ?>>
						<div class="check"></div>
						Other
					</label>
					<div class="form-group other-platform-input-custom <?php if($platforms_other == 'Custom Website'){}else{echo 'd-none';} ?>">
						<input type="text" class="form-control" id="other-platform-custom" name="other-platform-custom" placeholder="Enter your platform name" value="<?php if($platforms_other == 'Custom Website'){echo $platforms;} ?>">
					</div>
					<div class="form-group other-platform-input <?php if($platforms_other == 'Other'){}else{echo 'd-none';} ?>">
						<input type="text" class="form-control" id="other-platform" name="other-platform" placeholder="Enter your platform name" value="<?php if($platforms_other == 'Other'){echo $platforms;} ?>">
					</div>
				</div>
				<div class="sub sub2">
					<div class="form-group website_url">
						<label>Add website URL</label>
						<span class="error err3">Please enter website url.</span><br>
						<input type="url" class="form-control" id="website-url" name="website-url" placeholder="https://abc.com"  value="<?php echo $website_url; ?>"  >
						<small>(eg. https://abc.com , http://xyz.com)</small>
					</div>
					<div class="form-group shopify-domain-input d-none">
						<label>Add shopify admin domain URL</label>
						<span class="error err4">Please enter website url.</span><br>
						<input type="url" class="form-control" id="shopify-url" name="shopify-url" placeholder="https://admin.shopify.com/store/abc" value="<?=$shopify_domain_url?>">
						<small>(eg. https://admin.shopify.com/store/abc , https://abc.myshopify.com )</small> 
						<span class="shopify_pop" id="shopify_pop">
							<?xml version="1.0" ?>
							<svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
								<path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208S370.7 464 256 464zM256 336c-18 0-32 14-32 32s13.1 32 32 32c17.1 0 32-14 32-32S273.1 336 256 336zM289.1 128h-51.1C199 128 168 159 168 198c0 13 11 24 24 24s24-11 24-24C216 186 225.1 176 237.1 176h51.1C301.1 176 312 186 312 198c0 8-4 14.1-11 18.1L244 251C236 256 232 264 232 272V288c0 13 11 24 24 24S280 301 280 288V286l45.1-28c21-13 34-36 34-60C360 159 329 128 289.1 128z"/>
							</svg>
						</span>
						<div class="shopify_pop_wrap" style="display:none;">
							<div class="shopify_pop_ss">
								<img src="./img/Shopify_url_1.jpg"> 
								<img src="./img/Shopify_url_	2.jpg">         
								<?xml version="1.0" ?><!DOCTYPE svg  PUBLIC '-//W3C//DTD SVG 1.1//EN'  'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'>
								<svg  class="close_s_pp"id="Layer_1" style="enable-background:new 0 0 64 64;" version="1.1" viewBox="0 0 64 64" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
									<g>
										<g id="Icon-Close" transform="translate(381.000000, 231.000000)">
											<polyline class="st0" id="#fff" points="-370.7,-174.7 -373,-177 -327,-223 -324.7,-220.7 -370.7,-174.7    "/>
											<polyline class="st0" id="Fill-17" points="-327,-174.7 -373,-220.7 -370.7,-223 -324.7,-177 -327,-174.7    "/>
										</g>
									</g>
								</svg>
							</div>
						</div>
					</div>
					<!-- //123 -->
					<!-- <div class="col-12">
						<label>Select Installation Mode</label>
						<label class="text_smn">Would you like Website Speedy team to do the installation on your website ?</label>
						
						<div class="form-check">
							<input type="radio" class="form-check-input" id="install1" name="self_install" value="yes" >
							<div class="check" ></div>
							<label class="form-check-label" for="install1">I want website Speedy team to do the installation</label>
						</div>
						<div class="form-check">
							<input type="radio" class="form-check-input" id="install2" name="self_install" value="no" checked>
							<div class="check" ></div>
							<label class="form-check-label" for="install2">I want to install myself</label>
						</div>
														
						</div>	
						-->
					<!-- //123 -->
					<div class="sub sub3" >											
						<label>How did you find Website Speedy?</label>
							<span class="error err5">Please select any one option from below.</span>
							<!-- <input type="text" name="website-source" class="select-source form-control" value="<?=$source?>">	 -->
							<select name="website-source" id="website-source" class="select-source form-control">
								<option value="" style="display:none;">Choose any option</option>
								<option value="Google">Google</option>
								<option value="Linkedin">Linkedin </option>
								<option value="X (Twitter)">X (Twitter) </option>
								<option value="Youtube">Youtube</option>
								<option value="Referred by a friend">Referred by a friend</option>
								<option value="Facebook">Facebook</option>
								<option value="Instagram">Instagram</option>
								<option value="Other">Other</option>
							</select>

							<div class="form-group other_source_input" style="display:none" >
								<input type="text" class="form-control" id="other_source_name" name="other_source_name" placeholder="Enter how did you find us." >
								<span class="error other_source_nameErr">Please fill the field.</span>
							</div>			
					</div>
				</div>
			</div>
			<!--Fourth Step -->	
			<div class="paginations_btn" style="overflow:auto;">
				<!-- Circles which indicates the steps of the form: -->
				<div class="paginations_dots">
					<span class="step"></span>
					<span class="step"></span>
					<!-- <span class="step"></span> -->
				</div>
				<div class="form_btns">
					<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
					<button class="btn btn-primary" type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
				</div>
			</div>
		</form>
	</div>
</div>


<script>

// Retrieve

document.getElementById("website-url").value =localStorage.getItem("website_urls");
</script>
</body>
</html>

<script>

function getDomainFromUrl(url) {
	// Extract the protocol, www, domain, and path
	const match = url.match(/^((https?:\/\/)?(www\.)?([^\/]+))(\/[^\?#]*)?/i);

	// Check if a match is found
	if (match && match.length >= 1) {
		return match[1];
	}

	// Return null if no match is found
	return null;
}

$(document).ready(function(){

	$("#website-url").on("blur",function(){

		var invalid_url = 0 ;
		var websiteUrl = $("#website-url").val() ;
		if ( websiteUrl == undefined || websiteUrl == '' || websiteUrl == null ) {
			invalid_url = 1 ;
		}
		else if ( !isUrlValid(websiteUrl) ) {
			invalid_url = 1 ;
		}

		if ( invalid_url == 0 ) {
			websiteUrl = getDomainFromUrl(websiteUrl);
			$("#website-url").val(websiteUrl);
		}
			
	});
});



// Current tab is set to be the first tab (0)
	var currentTab = 0; 

// if('<?php echo $describes_your_company; ?>' != "")
// 	currentTab = 1; 
// if('<?php echo $website_category; ?>' != "")
// 	currentTab = 1; 
// if('<?php echo $platforms; ?>' != "")
// 	currentTab = 2; 
// if('<?php echo $source; ?>' != "")
// 	currentTab = 3; 	
// if('<?php echo $website_url; ?>' != "")
// 	currentTab = 3; 



if('<?php echo $website_category; ?>' != "" && '<?php echo $source; ?>' != "")
	currentTab = 1; 
if('<?php echo $platforms; ?>' != "" && '<?php echo $website_url; ?>' != "")
	currentTab = 1; 

// alert(currentTab);
// Display the current tab
showTab(currentTab); 

$(document).ready(function() {



	$(".select-platform").click(function(){
		var v = $(this).val() ;
		$(".shopify-domain-input").addClass("d-none");
		
		$(".other-platform-input").addClass("d-none");
		if ( v == "Other" ) {
			$("#other-platform").val("");
			$(".other-platform-input").removeClass("d-none");
			$(".other-platform-input-custom").addClass("d-none");
			$(".other-platform").focus();
		}
		if ( v == "Custom Website" ) {
			$("#other-platform-custom").val("");
			$(".other-platform-input").addClass("d-none");
			$(".other-platform-input-custom").removeClass("d-none");
			$(".other-platform-custom").focus();
		}	

		if ( v != "Custom Website" ) {
			$(".other-platform-input-custom").addClass("d-none");
        }	

		if ( v == "Shopify" ) {

			$(".shopify-domain-input").removeClass("d-none");
 
		}	

	});

	$(".select-source").click(function(){
		var so = $(this).val() ;

		$("#other-source").val("");
		$(".other-source-input").addClass("d-none");
		if ( so == "Other" ) {
			$(".other-source-input").removeClass("d-none");
		}
	});

	


});

function showTab(n) {

	// This function will display the specified tab of the form...
	var x = document.getElementsByClassName("tab");
	var s = document.getElementsByClassName("steps");
	console.log('tab show class'+x)	
	console.log('tab show steps'+s)	
	console.log("showtab="+n);
	x[n].style.display = "block";
	//... and fix the Previous/Next buttons:
	if (n == 0) {
		document.getElementById("prevBtn").style.display = "none";
	} else {
		document.getElementById("prevBtn").style.display = "inline";
	}
	if (n == (x.length - 1)) {
		document.getElementById("nextBtn").innerHTML = "Submit";
		// document.getElementById("nextBtn").type = "submit";
	
	} else {
		document.getElementById("nextBtn").innerHTML = "Next";
		s[currentTab].classList.remove("complete");
	}
	//... and run a function that will display the correct step indicator:
	fixStepIndicator(n)
}


function nextPrev(n) {

	// echo console.log(n);
	// This function will figure out which tab to display
	var x = document.getElementsByClassName("tab");
	var s = document.getElementsByClassName("steps");
	console.log('tab nextpre class' + x)
	console.log('tab nextpre steps' + s)
	// Exit the function if any field in the current tab is invalid:
	console.log(n)
	console.log(x.length)

	if (n == 1 && !validateForm()) return false;

	// Hide the current tab:
	if (currentTab < x.length) {
		if ((currentTab + n) != x.length) {
			x[currentTab].style.display = "none";
		}
		if (n == 1)
			s[currentTab].classList.add("complete");
	}

	// Increase or decrease the current tab by 1:
	currentTab = currentTab + n;

	// if you have reached the end of the form...
	if (currentTab == x.length) {
		// ... the form gets submitted:
		if (currentTab == 2) {
			send_data(currentTab);
			send_data(4);

		}


		console.log("submitting = " + currentTab);
		currentTab = 2;
		document.getElementById("regForm").submit();
		return false;
	}

	// Otherwise, display the correct tab:
	console.log(currentTab + ", " + n);
	showTab(currentTab);
	if (n == 1) {
		console.log("first Step");
		send_data(currentTab);
		send_data(3);
	}

}

function checkBlockedPlatform(substringToCheck) {

	let exist = 0;

	// Declare an array of strings
	let stringsToCheck = ["wordpress", "wocommerce" , "woocommerce", "wp"];

	// Declare a single substring to check
	substringToCheck = substringToCheck.toLowerCase();

	// Iterate over the array of strings and check if the substring exists in each string
	stringsToCheck.forEach(myString => {
		if (myString.includes(substringToCheck)) {
			exist = 1;
		}
	});

	return exist;
}

/** function validateForm() {

	console.log("val");

	var valid = true;

	if (currentTab == 0) {
		console.log('currenttab1');
		$(".website_category").parent().removeClass("invalid");

		var company = $(".website_category:checked").val();
		if (company == undefined || company == '' || company == null) {
			$(".website_category").parent().addClass("invalid");
			$(".err4").show();
			setTimeout(function () {
				$(".err4").hide();
			}, 3000);

			valid = false;
		}

		//123
		$(".role").removeClass("invalid");
		$(".role").parent().removeClass("invalid");
		var role = $(".role:checked").val();
		if (role == undefined || role == '' || role == null) {
			$(".role").parent().addClass("invalid");
			$(".roleErr").html("Please select any one option from below.");
			$(".roleErr").show();
			setTimeout(function () {
				$(".roleErr").hide();
			}, 3000);
			valid = false;
		}
		else if (role == 'Other') {
			var otherRole = $("#other-input-data").val();
			if (otherRole == undefined || otherRole == '' || otherRole == null) {
				$(".otherRoleErr").html("Please enter your role.");
				$(".otherRoleErr").show();
				setTimeout(function () {
					$(".otherRoleErr").hide();
				}, 3000);
				valid = false;
			}
		}


		// $("#other-source").removeClass("invalid") ;
		// $(".select-source").parent().removeClass("invalid") ;

		// var source = $(".select-source").val() ;
		// if ( source == undefined || source == '' || source == null ) {
		// 	$(".select-source").addClass("invalid") ;
		// 		$(".err2").show();
		// 	setTimeout(function(){
		// 		$(".err2").hide();
		// 	},3000);
		// 	valid = false; //123
		// }
		// else if( source == "Other" ) 
		// {
		// 	var otherSource = $("#other-source").val() ;
		// 	if ( otherSource == undefined || otherSource == '' || otherSource == null ) {
		// 		$("#other-source").addClass("invalid") ;
		// 		valid = false;
		// 	}
		// }//123


	}
	else if (currentTab == 1) {
		var wp_site = 0;
		//123
		$(".select-source").parent().removeClass("invalid");

		var source = $(".select-source").val();
		if (source == undefined || source == '' || source == null) {
			$(".select-source").addClass("invalid");
			$(".err5").show();
			setTimeout(function () {
				$(".err5").hide();
			}, 3000);
			valid = false; //123
		}
		else if (source == "Other") {
			var otherSource = $("#other-source").val();
			if (otherSource == undefined || otherSource == '' || otherSource == null) {
				$("#other-source").addClass("invalid");
				valid = false;
			}
		} //123


		$.ajaxSetup({
			async: false
		});


		//123
		console.log("ss");
		$("#website-url , #shopify-url").removeClass("invalid");
		var websiteUrl = $("#website-url").val();
		if (websiteUrl == undefined || websiteUrl == '' || websiteUrl == null) {
			$("#website-url").addClass("invalid");
			$(".err3").html("Please enter website url.");
			$(".err3").show();
			setTimeout(function () {
				$(".err3").hide();
			}, 3000);
			valid = false;
		}
		else if (!isUrlValid(websiteUrl)) {
			$("#website-url").addClass("invalid");
			$(".err3").html("Please enter website valid url.");
			$(".err3").show();
			setTimeout(function () {
				$(".err3").hide();
			}, 3000);
			valid = false;
		}
		else {
			websiteUrl = getDomainFromUrl(websiteUrl);
			$("#website-url").val(websiteUrl);
		}


		var platform = $(".select-platform:checked").val();
		if (platform == "Shopify") {
			var shopifyUrl = $("#shopify-url").val();
			if (shopifyUrl == undefined || shopifyUrl == '' || shopifyUrl == null) {
				$("#shopify-url").addClass("invalid");
				$(".err4").html("Please enter website valid url.");
				$(".err4").show();
				setTimeout(function () {
					$(".err4").hide();
				}, 3000);
				valid = false;
			}
			else if (!isUrlValid(shopifyUrl)) {
				$("#website-url").addClass("invalid");
				$(".err4").html("Please enter website valid url.");
				$(".err4").show();
				setTimeout(function () {
					$(".err4").hide();
				}, 3000);
				valid = false;
			}
		}

		if (valid == true) {
			send_data(4);
			var user_id = '<?=$_SESSION["user_id"]?>';
			$.ajax({
				type: "POST",
				url: "inc/check.php",
				data: {
					"url": $("#website-url").val(),
					"user": user_id
				},
				dataType: "json",
				encode: true,
				async: false,
			}).done(function (data) {
				if (data == 0) {
					valid == false;
					// $(".err3").html("Website Url already exixts.");
					$(".err3").show();
					setTimeout(function () {
						$(".err3").hide();
					}, 10000);


				}
				else {
					valid = true;
					document.getElementById("regForm").submit();
				}

			});
		}

		$("#other-platform").removeClass("invalid");
		$(".select-platform").parent().removeClass("invalid");
		$(".shopify-domain-input").addClass("d-none");
		// $("#shopify-url").val("") ;

		var source = $(".select-source").val();
		var platform = $(".select-platform:checked").val();
		if (platform == undefined || platform == '' || platform == null) {
			$(".select-platform").parent().addClass("invalid");
			$(".err2").html("Please fill the field.");
			$(".err2").show();
			setTimeout(function () {
				$(".err2").hide();
			}, 3000);
			valid = false;
		}


		// else if ( source == undefined || source == '' || source == null ) {
		// 	$(".select-source").addClass("invalid") ;
		// 		$(".err2").show();
		// 	setTimeout(function(){
		// 		$(".err2").hide();
		// 	},3000);
		// 	valid = false; //123
		// }
		else if (platform == "Other") {
			var otherPlatform = $("#other-platform").val();
			if (otherPlatform == undefined || otherPlatform == '' || otherPlatform == null) {
				$(".err2").html("Please fill the field.");
				$(".err2").show();
				setTimeout(function () {
					$(".err2").hide();
				}, 3000);
				$("#other-platform").addClass("invalid");
				valid = false;
			}
			else if (checkBlockedPlatform(otherPlatform) == 1) {
				wp_site = 1;
				$("#other-platform").addClass("invalid");
				valid = false;
			}


		}

		else if (platform == "Custom Website") {
			var otherPlatform = $("#other-platform-custom").val();
			if (otherPlatform == undefined || otherPlatform == '' || otherPlatform == null) {
				$(".err2").html("Please fill the field.");
				$(".err2").show();
				setTimeout(function () {
					$(".err2").hide();
				}, 3000);
				$("#other-platform-custom").addClass("invalid");
				valid = false;
			}
			else if (checkBlockedPlatform(otherPlatform) == 1) {
				wp_site = 1;
				$("#other-platform-custom").addClass("invalid");
				valid = false;
			}


		}

		else if (platform == "Shopify") {
			$(".err2").html("Please select any one option from below.");
			$(".shopify-domain-input").removeClass("d-none");
		}


	}
	else if (currentTab == 2) {

		// else if( platform == "Shopify" ) {
		// 	$(".shopify-domain-input").removeClass("d-none") ;
		// }

	}
	else if (currentTab == 3) {

	}
	else {
		valid = false;
	}

	// If the valid status is true, mark the step as finished and valid:
	if (currentTab != 3) {
		if (valid) {
			$(".step:eq(" + currentTab + ")").addClass("finish");
			// document.getElementsByClassName("step")[currentTab].className += " finish";
		}
		// valid = false;


		if (!valid) {
			e.preventDefault();

			Swal.fire({
				title: "Hey",
				icon: "info",
				html: `<p>You are in waiting list.<br> We are creating a plugin for you for boost your website.</p>`,
				showCloseButton: true,
				showCancelButton: false,
				showConfirmButton: false,
			});

		}


		return valid; // return the valid status
	}
} **/

function validateForm() {

	var wp_site = 0;
	var valid = true;

	if (currentTab == 0) {

		var country = $("#Country").val();
		$("#Country").parent().removeClass('invalid') ;
		if (country == undefined || country == '' || country == null) {
			$("#Country").parent().addClass('invalid') ;
			valid = false;
		}


		var company = $(".website_category:checked").val();
		$(".website_category").parent().removeClass("invalid");
		if (company == undefined || company == '' || company == null) {
			$(".website_category").parent().addClass("invalid");
			$(".err4").show();
			setTimeout(function () { $(".err4").hide();}, 3000);
			valid = false;
		}


		var role = $(".role:checked").val();
		// $(".role").removeClass("invalid");
		$(".role").parent().removeClass("invalid");
		$("#other-input-data").parent().removeClass("invalid");

		if (role == undefined || role == '' || role == null) {
			$(".role").parent().addClass("invalid");
			$(".roleErr").html("Please select any one option from below.").show();
			setTimeout(function () {$(".roleErr").html("").hide();}, 3000);
			valid = false;
		}
		else if (role == 'Other') {
			var otherRole = $("#other-input-data").val();
			if (otherRole == undefined || otherRole == '' || otherRole == null) {
				$(".otherRoleErr").html("Please enter your role.").show();
				setTimeout(function () { $(".otherRoleErr").html("").hide(); }, 3000);
				$("#other-input-data").parent().addClass("invalid");
				valid = false;
			}
		}

	}
	else if (currentTab == 1) {

		$.ajaxSetup({ async: false });

		$(".err2 , .err3 , .err4").hide().html("");
		$("#other-platform,#other-platform-custom").parent().removeClass("invalid");
		$("#website-url , #shopify-url").removeClass("invalid");
		$(".select-source").removeClass("invalid");

		var platform = $(".select-platform:checked").val();
		if (platform == undefined || platform == '' || platform == null) {
			$(".select-platform").parent().addClass("invalid");
			$(".err2").show().html("Please select the platform.");
			setTimeout(function () { $(".err2").hide().html(""); }, 3000);
			valid = false;
		}
		else if ( platform == "Other" ) {

			var otherPlatform = $.trim($("#other-platform").val());
			if (otherPlatform == undefined || otherPlatform == '' || otherPlatform == null) {
				$(".err2").show().html("Please fill other platform name.");
				$("#other-platform").parent().addClass("invalid");
				setTimeout(function () { $(".err2").hide().html(""); }, 3000);
				valid = false;
			}
			else if (checkBlockedPlatform(otherPlatform) == 1) {
				wp_site = 1;
				$("#other-platform").parent().addClass("invalid");
				valid = false;
			}

		}
		else if (platform == "Custom Website") {
			var otherPlatform = $.trim($("#other-platform-custom").val());
			if (otherPlatform == undefined || otherPlatform == '' || otherPlatform == null) {
				$(".err2").show().html("Please fill Custom Website platform name.");
				$("#other-platform-custom").parent().addClass("invalid");
				setTimeout(function () { $(".err2").hide().html(""); }, 3000);
				valid = false;
			}
			else if (checkBlockedPlatform(otherPlatform) == 1) {
				wp_site = 1;
				$("#other-platform-custom").parent().addClass("invalid");
				valid = false;
			}
		}
		else if (platform == "Shopify") {
			var shopifyUrl = $("#shopify-url").val();
			if (shopifyUrl == undefined || shopifyUrl == '' || shopifyUrl == null) {
				$("#shopify-url").addClass("invalid");
				$(".err4").show().html("Please enter website valid url.");
				setTimeout(function () { $(".err4").hide().html(""); }, 3000);
				valid = false;
			}
			else if (!isUrlValid(shopifyUrl)) {
				$("#website-url").addClass("invalid");
				$(".err4").show().html("Please enter website valid url.");
				setTimeout(function () { $(".err4").hide().html(""); }, 3000);
				valid = false;
			}
		}
		else if (platform == "Wordpress") {
			wp_site = 1;
			valid = false;
		}

		
		var websiteUrl = $("#website-url").val();
		if (websiteUrl == undefined || websiteUrl == '' || websiteUrl == null) {
			$("#website-url").addClass("invalid");
			$(".err3").show().html("Please enter website url.");
			setTimeout(function () { $(".err3").hide().html("");}, 3000);
			valid = false;
		}
		else if (!isUrlValid(websiteUrl)) {
			$("#website-url").addClass("invalid");
			$(".err3").show().html("Please enter website valid url.");
			setTimeout(function () { $(".err3").hide().html("");}, 3000);
			valid = false;
		}
		else {
			websiteUrl = getDomainFromUrl(websiteUrl);
			$("#website-url").val(websiteUrl);
		}

		var source = $(".select-source").val();
		if (source == undefined || source == '' || source == null) {
			$(".select-source").addClass("invalid");
			$(".err5").show();
			setTimeout(function () { $(".err5").hide();}, 3000);
			valid = false;
		}
		//123
		else if(source=='Other'){
			var other_source_name = $("#other_source_name").val();
			if (other_source_name == undefined || other_source_name == '' || other_source_name == null) {
			$(".other_source_nameErr").show();
			setTimeout(function () { $(".other_source_nameErr").hide();}, 3000);
			valid = false;
			}
		}

		if (valid == true) {
			send_data(4);
			var user_id = '<?=$_SESSION["user_id"]?>';
			$.ajax({
				type: "POST",
				url: "inc/check.php",
				data: {
					"url": $("#website-url").val(),
					"user": user_id
				},
				dataType: "json",
				encode: true,
				async: false,
			}).done(function (data) {
				if (data == 0) {
					valid == false;
					// $(".err3").html("Website Url already exixts.");
					$(".err3").show();
					setTimeout(function () { $(".err3").hide(); }, 10000);
				}
				else {
					valid = true;
					document.getElementById("regForm").submit();
				}
			});
		}

	}
	else {
		valid = false;
	}

	if (!valid) {
		event.preventDefault();

		if ( wp_site == 1 && websiteUrl ) {
			Swal.fire({
				title: "<h4>Please Note</h4>",
				icon: "info",
				html: `<p>Our WordPress plugin is still in development, so we currently do not offer Speed Improvement Services for WordPress and WooCommerce. Join our waiting list, and we'll notify you as soon as these services are available!</p><button type="button" id="join-wishlist" onclick="joinWishlist();" class="btn btn-primary mb-3">Join Waitlist</button>  <button type="button" onclick="tryWithAnother();" class="btn btn-primary mb-3">Try With Another Platform</button>`,
				showCloseButton: true,
				showCancelButton: false,
				showConfirmButton: false,
				confirmButtonText: `Keep Me Posted`,
			}).then((result)=>{
				if (result.isConfirmed) {

					let user_id = $("#user_id").val();
					let user_email = $("#user_email").val();

					if ( user_id && user_email ) {

						$.ajax({
							url:"adminpannel/inc/wp-plugin-posted.php",
							method:"POST",
							dataType:"JSON",
							data:{
								user_id:user_id,
								user_email:user_email,
								action:"keep-me-posted"
							},
							success:function(obj){
								if ( obj == 1 || obj == "1" ) {
									Swal.fire("Successfully subscribed for WordPress plugin update!", "", "success");

								}
								else {
									Swal.fire("Can't subscribed for WordPress plugin update!", "", "error");
								}
							},
							error:function(){
								Swal.fire("Internal error.", "", "error");
							},
							complete:function(){
								setTimeout(function(){ Swal.close(); },3000);
							}
						});

						
					}
					else {
						Swal.fire("Customer details not found.", "", "error");
					}
				}
			});
		}

	}

	// valid = false;
	return valid;
}

function fixStepIndicator(n) {
	// This function removes the "active" class of all steps...
	var i, x = document.getElementsByClassName("step");
	for (i = 0; i < x.length; i++) {
		x[i].className = x[i].className.replace(" active", "");
	}
	//... and adds the "active" class on the current step:
	x[n].className += " active";
}

function isUrlValid(userInput) {

	    var lc = userInput.toLowerCase();
		var isMatch = lc.substr(0, 8) == 'https://' || lc.substr(0, 7) == 'http://';

		return isMatch;

    // var res = userInput.match(/(http(s)?:\/\/.)(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/);
    // if(res == null)
    //     return false;
    // else
    //     return true;
}


function send_data(flow){
	console.log("sendind to flow="+flow);
// if(flow==1)
// 	flow = "describes_your_company";
if(flow==1)
	flow = "website_category";
else if(flow==2)
	flow = "platforms";
else if(flow==3)
	flow = 'source';
else if(flow==4)
	flow = "website_url";

var user_id = '<?=$_SESSION["user_id"]?>';
var data = "";
var plateform = "";
// if(flow=='describes_your_company')
// {
// data = $('input[name="describe-company"]:checked').val();
// }
// else 
if(flow=='website_category')
{
data = $('input[name="website_category"]:checked').val();
}
else if(flow=='platforms')
{
data = $('input[name="website-platform"]:checked').val();
	if(data == 'Other'){
		data = $("#other-platform").val();
		plateform = "Other";
	}
	else if (data == 'Custom Website'){
		data = $("#other-platform-custom").val();
		plateform = "Custom Website";
	}
	

}
else if(flow=='source')
{
data = $('input[name="website-source"]').val();
 

}
else if(flow=='website_url')
{
	if($('input[name="website-platform"]:checked').val()=='Shopify')
		data = $('#website-url').val()+","+$('#shopify-url').val();
	else
		data = $('#website-url').val();

}
 



dataCC = $('input[name="website-platform"]:checked').val();
	if(dataCC == 'Other'){
		 
		plateform = "Other";
	}
	else if (dataCC == 'Custom Website'){
		 
		plateform = "Custom Website";
	}

var appUser = {"user_id":user_id , "flow":flow, "plateform":plateform, "data":data};

 

var xhttp = new XMLHttpRequest();
xhttp.open("POST", "inc/flow.php", true); 

xhttp.onreadystatechange = function() { 
   if (this.readyState == 4 && this.status == 200) {
   }
};

xhttp.send(JSON.stringify(appUser));



}

if('<?=$platforms?>'=='Shopify')
{
	$(".shopify-domain-input").removeClass("d-none");
}
$("#website-url").val('<?=$website_url?>');
$("#shopify-url").val('<?=$shopify_domain_url?>');

</script>

<script>
$(document).ready(function(){
  $(".shopify_pop").click(function(){
    $(".shopify_pop_wrap").show();
  });

  $(".close_s_pp").click(function(){
    $(".shopify_pop_wrap").hide();
  });

       $.ajax({
      type: "POST",
      url: "<?=HOST_HELP_URL?>actions/account/register",
      data: {
	     first_name:"<?=$h_fname?>",
	     last_name: "<?=$h_lname?>",
	     email_address : "<?=$h_email?>",
	     password : "<?=$pass?>",
	     retype_password : "<?=$pass?>",
	     terms : "on"			
	     },
      dataType: "json",
      encode: true,
    }).done(function (data) {
      console.log(data)
    });	   

});
	//123
$('.role').on('change',function(){
	var other =   $('input[name="role"]:checked').val()
	if(other=='Other'){
	$('.other-input-data').show();
	}else{
	$('.other-input-data').hide();
	}   
})

//123
$('#website-source').on('change',function(){
	var other =   $(this).val()
	if(other=='Other'){
	$('.other_source_input').show();
	}else{
	$('.other_source_input').hide();
	}   
})


function joinWishlist() {

	let user_id = $("#user_id").val();
	let user_email = $("#user_email").val();
	let user_name = $("#firstname").val();
	let website_url = $("#website-url").val();

	if (user_id && user_email) {

		$.ajax({
			url: "adminpannel/inc/wp-plugin-posted.php",
			method: "POST",
			dataType: "JSON",
			data: {
				user_id: user_id,
				user_email: user_email,
				user_name: user_name,
				website_url: website_url,
				action: "keep-me-posted"
			},
			beforeSend: function () {
				$("#join-wishlist").text("Please Wait...");
				// $(".loader").show().html("<div class='loader_s 123'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analizing your website. It might take 2-3 mins<p></div><div class='progress'> <div class='bar'></div> </div>");
			},
			success: function (obj) {
				if (obj == 1 || obj == "1") {
					Swal.fire("Successfully subscribed for WordPress plugin update!", "", "success");
				}
				else {
					Swal.fire("Can't subscribed for WordPress plugin update!", "", "error");
				}
			},
			error: function () {
				Swal.fire("Internal error.", "", "error");
			},
			complete: function () {
				if ($("#join-wishlist").length > 0) {
					$("#join-wishlist").text("Join Wishlist");
				}

				setTimeout(function () {
					Swal.close();
				}, 3000);
			}
		});


	}
	else {
		Swal.fire("Customer details not found.", "", "error");
	}

}


//manual_request
function manualAuditRequest(){
	
let user_id = $("#user_id").val();
let user_email = $("#user_email").val();

if (user_id && user_email) {

	$.ajax({
		url: "adminpannel/inc/wp-plugin-posted.php",
		method: "POST",
		dataType: "JSON",
		data: {
			user_id: user_id,
			user_email: user_email,
			action: "manualAuditRequest"
		},
		beforeSend:function(){
			$("#join-wishlist").text("Please Wait...");
			// $(".loader").show().html("<div class='loader_s 123'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analizing your website. It might take 2-3 mins<p></div><div class='progress'> <div class='bar'></div> </div>");
		},
		success: function (obj) {
			if (obj == 1 || obj == "1") {
				Swal.fire("Successfully we recieved your query for WordPress plugin update!", "", "success");
			}
			else {
				Swal.fire("Can't subscribed for WordPress plugin update!", "", "error");
			}
		},
		error: function () {
			Swal.fire("Internal error.", "", "error");
		},
		complete: function () {
			if ( $("#join-wishlist").length > 0 ) {
				$("#join-wishlist").text("Join Wishlist");
			}

			setTimeout(function () {
				Swal.close();
			}, 3000);
		}
	});


}
else {
	Swal.fire("Customer details not found.", "", "error");
}
}

function tryWithAnother() {
	Swal.close();
}

</script>





