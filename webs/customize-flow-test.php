<?php

require_once("adminpannel/config.php") ;
require_once("adminpannel/inc/functions.php");

if ( !checkUserLogin() ) {
    header("location: ".HOST_URL."signup.php") ;
    die() ;
}

			$user_id = $_SESSION["user_id"] ; 

		$get_flow = $conn->query(" SELECT * FROM `admin_users` WHERE id = '$user_id' ");
		$d = $get_flow->fetch_assoc();
		$plan_country = "";
		if($d['phone'] == "" || $d['phone'] == NULL){
			header("location: ".HOST_URL."basic_details.php") ;
			die();		
		}

		if($d['country'] != "101"){
			$plan_country = "-us";
		}
		
		if($d['flow_step']==1){
		// header("location: ".HOST_URL."plan.php") ;

		if($d['sumo_code'] !="" && $d['sumo_code'] !="null"){
			// echo "Lifetime";
			header("location: ".HOST_URL."adminpannel/dashboard.php") ;
		die();
		}
		else{	
		$get_flow = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
		$d = $get_flow->fetch_assoc();

			header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($d['id'])) ;
		die();
		}

		}

$name_parts = preg_split("/[\s,]+/", $d['firstname']);
$h_fname = $name_parts[0];
$h_email = $d['email'];
$h_lname = array_slice($name_parts, -1)[0];
if($h_lname == ""){
	$h_lname = "Speddy";
}
$pass =  base64_decode($d['help_pass']);

if ( isset($_POST["customize-flow"]) ) {
	// echo "<pre>"; print_r($_POST) ; print_r($_SESSION) ; die();

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	$describe_company = $_POST['describe-company'] ;
	$website_platform = $_POST['website-platform'] ;
	$other_platform = $_POST['other-platform'] ;
	$website_source = $_POST['website-platform'] ;
	$other_source = $_POST['other-source'] ;
	$website_url = $_POST['website-url'] ;
	$shopify_url = $_POST['shopify-url'] ;

	if ( empty($describe_company) || empty($website_platform) || empty($website_url) ) {
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
 
		$desktop_score = 0 ; $mobile_score = 0 ;
		 

		$sql = " INSERT INTO `manager_company`( `user_id` , `company_type` ) VALUES ( '$user_id' , '$describe_company' ) ; " ;
		if($conn->query($sql) ){
		$sql2 = " INSERT INTO `boost_website`( `manager_id`, `platform`, `platform_name`, `website_url`, `shopify_url`, `desktop_speed_old`, `mobile_speed_old`, `desktop_speed_new`, `mobile_speed_new` ) VALUES ( '$user_id' , '$website_platform' , '$other_platform' , '$website_url' , '$shopify_url' , '$desktop_score' , '$mobile_score' , '$desktop_score' , '$mobile_score' ) ; " ;

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
			header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($last_website_id)) ;
		}



		die();
	}

	header("location: ".HOST_URL."customize-flow.php") ;
	die();

}
}




		$get_flow = $conn->query(" SELECT * FROM `flow_step` WHERE user_id = '$user_id' ");
		$describes_your_company = "";
		$platforms = "";
		$source = '';
		$website_url = "";
		$shopify_domain_url = "";
		$website_category ="";


		if($get_flow->num_rows > 0 ){
			$d = $get_flow->fetch_assoc();
			$describes_your_company = $d['describes_your_company'];
			$platforms = $d['platforms'];
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
  padding: 40px;
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

.alert__wrapper {
	width: 70%; 
	margin: 0px auto;
}


</style>
</head>
<body>
<div class="customize_wrapper">
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
		<h3>Let's customize your flow</h3>
		<p>Tell us a bit about yourself and help us match Website Speedy to your needs.</p>
	</div>
<div class="container">
<div class="stepper_wrapper">
                                <ul class="stepper stepper-horizontal">
                                   <!--  <li class='steps <?php if($describes_your_company!=""){echo "complete";} ?>' >
                                        <a href="#step1">
											<div class="step_name_s">
                                            <span class="circle p-2"><span class="step_no">1</span><?xml version="1.0" ?><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg></span>
                                        
										<span class="label">First step</span>
                                        </div>
										<div class="step_fill_sc">
										<div class="step_fill_s">
											<span></span>
                                        </div>	
                                      </div>
										</a>		
                                    </li> -->
                                    <li  class='steps <?php if($platforms!=""){echo "complete";} ?>'>
                                        <a href="#step1">
										<div class="step_name_s">
                                            <span class="circle p-2"><span class="step_no">1</span><?xml version="1.0" ?><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg></span>
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
                                            <span class="circle p-2"><span class="step_no">2</span><?xml version="1.0" ?><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg></span>
											<span class="label">Second step</span>
                                        </div>	
										<div class="step_fill_sc">
										<div class="step_fill_s">
											<span></span>
                                        </div>
                                          </div>
                                        </a>
										  
                                    </li>


									<li  class='steps <?php if($source!=""){echo "complete";} ?>'>
                                        <a href="#step3">
										<div class="step_name_s">
                                            <span class="circle p-2"><span class="step_no">3</span><?xml version="1.0" ?><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg></span>
											<span class="label">Third step</span>
										</div>
										<div class="step_fill_sc">
										<div class="step_fill_s">
											<span></span>
                                        </div>
                                          </div>
                                        </a>
										
                                    </li>

                                    <li  class='steps <?php if($website_url!=""){echo "complete";} ?>'>
                                        <a href="#step4">
										<div class="step_name_s">
                                            <span class="circle p-2"><span class="step_no">4</span><?xml version="1.0" ?><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg></span>
											<span class="label">Fourth step</span>
										</div>
                                        </a>
										
                                    </li>
                                </ul>
 </div>
</div>
<div class="container">
	<div class="alert__wrapper" >
	<?php require_once('inc/alert-message.php') ; ?>	
	</div>
						<div class="loader" style="display: none;"></div>

<form id="regForm" method="post">
	<input type="hidden" name="customize-flow" value="customize-flow">

<!-- Second Step -->


	<div class="tab">
		<label>Which category best describes your website?</label>
		<span class="error err4">Please select any one option from below.</span>
		
		<label class="form-control">
			<input type="radio" name="website_category" class="website_category" onclick="nextPrev(1)" value="Ecommerce & retail website" <?php if($website_category == 'Ecommerce & retail website'){echo 'checked';} ?>>
			Ecommerce & retail website
		</label>
		
		<label class="form-control">
		<input type="radio" name="website_category" class="website_category" onclick="nextPrev(1)" value="B2B website" <?php if($website_category == 'B2B website'){echo 'checked';} ?>>
		B2B website
	    </label>

		<label class="form-control">
			<input type="radio" name="website_category" class="website_category" onclick="nextPrev(1)" value="Lead generation website" <?php if($website_category == 'Lead generation website'){echo 'checked';} ?>>
		Lead generation website
	    </label>
		
		<label class="form-control">
			<input type="radio" name="website_category" class="website_category" onclick="nextPrev(1)" value="Non-ecommerce content website" <?php if($website_category == 'Non-ecommerce content website'){echo 'checked';} ?>>
		Non-ecommerce content website
	    </label>

		<label class="form-control">
			<input type="radio" name="website_category" class="website_category" onclick="nextPrev(1)" value="Landing pages" <?php if($website_category == 'Landing pages'){echo 'checked';} ?>>
		 Landing pages
	    </label>
		
		<label class="form-control">
			<input type="radio" name="website_category" class="website_category" onclick="nextPrev(1)" value="Blogs" <?php if($website_category == 'Blogs'){echo 'checked';} ?>>
		Directories/ Portals /Blogs
	   </label>

	   <label class="form-control">
			<input type="radio" name="website_category" class="website_category" onclick="nextPrev(1)" value="Company" <?php if($website_category == 'Company'){echo 'checked';} ?>>
		Company / Enterprise
	   </label>
	
	</div>

<!-- Third Step -->
	<div class="tab">
		<label>What is your website Platforms?</label>
		<span class="error err2">Please select any one option from below.</span>
		<label class="form-control">
			<input type="radio" class="select-platform" name="website-platform" onclick="nextPrev(1)" value="Shopify" <?php if($platforms == 'Shopify'){echo 'checked';} ?>>
		Shopify
	    </label>

		<label class="form-control">
			<input type="radio" class="select-platform" name="website-platform" onclick="nextPrev(1)" value="Wordpress" <?php if($platforms == 'Wordpress'){echo 'checked';} ?>>
		Wordpress</label>

		<label class="form-control">
			<input type="radio" class="select-platform" name="website-platform" onclick="nextPrev(1)" value="EKM" <?php if($platforms == 'EKM'){echo 'checked';} ?>>
		EKM</label>

		<label class="form-control">
			<input type="radio" class="select-platform" name="website-platform" onclick="nextPrev(1)" value="Bigcommerce" <?php if($platforms == 'Bigcommerce'){echo 'checked';} ?>>
		Bigcommerce</label>

		<label class="form-control">
			<input type="radio" class="select-platform" name="website-platform" onclick="nextPrev(1)" value="Americommerce" <?php if($platforms == 'Americommerce'){echo 'checked';} ?>>
		Americommerce</label>

		<label class="form-control">
			<input type="radio" class="select-platform" name="website-platform" onclick="nextPrev(1)" value="Neto" <?php if($platforms == 'Neto'){echo 'checked';} ?>>
		Neto</label>

		<label class="form-control">
			<input type="radio" class="select-platform" name="website-platform" value="Other" <?php if($platforms != 'Shopify' && $platforms != 'Wordpress' && $platforms != 'EKM' && $platforms != 'Bigcommerce' && $platforms != 'Americommerce' && $platforms != 'Neto'  && $platforms != '' && $platforms != 'NULL'){echo 'checked';} ?>>
		Other</label>

		<div class="form-group other-platform-input <?php if($platforms != 'Shopify' && $platforms != 'Wordpress' && $platforms != 'EKM' && $platforms != 'Bigcommerce' && $platforms != 'Americommerce' && $platforms != 'Neto' && $platforms != '' && $platforms != 'NULL'){}else{echo 'd-none';} ?>">
			
			<input type="text" class="form-control" id="other-platform" name="other-platform" placeholder="Enter your platform name" value="<?php if($platforms != 'Shopify' && $platforms != 'Wordpress' && $platforms != 'EKM' && $platforms != 'Bigcommerce' && $platforms != 'Americommerce' && $platforms != 'Neto'){echo $platforms;} ?>">
		</div>
	</div>


<!--Fourth Step -->	

<div class="tab">
		<label>How did you find us?</label>
		<span class="error err2">Please select any one option from below.</span>
		<label class="form-control">
			<input type="radio" class="select-source" name="website-source" onclick="nextPrev(1)" value="Google" <?php if($source == 'Google'){echo 'checked';} ?>>
		Google
	    </label>

		<label class="form-control">
			<input type="radio" class="select-source" name="website-source" onclick="nextPrev(1)" value="Quora" <?php if($source == 'Quora'){echo 'checked';} ?>>
		Quora</label>

		<label class="form-control">
			<input type="radio" class="select-source" name="website-source" onclick="nextPrev(1)" value="Reddit" <?php if($source == 'Reddit'){echo 'checked';} ?>>
		Reddit</label>

		<label class="form-control">
			<input type="radio" class="select-source" name="website-source" onclick="nextPrev(1)" value="Community - Shopify or Others" <?php if($source == 'Community'){echo 'checked';} ?>>
		Community - Shopify or Others</label>

		<label class="form-control">
			<input type="radio" class="select-source" name="website-source" onclick="nextPrev(1)" value="Facebook" <?php if($source == 'Facebook'){echo 'checked';} ?>>
		Facebook</label>

		<label class="form-control">
			<input type="radio" class="select-source" name="website-source" onclick="nextPrev(1)" value="Recommended By Someone" <?php if($source == 'Recommended'){echo 'checked';} ?>>
		Recommended By Someone</label>

		<label class="form-control">
			<input type="radio" class="select-source" name="website-source" value="Other" <?php if($source != 'Google' && $source != 'Quora' && $source != 'Reddit' && $source != 'Community' && $source != 'Facebook' && $source != 'Recommended'  && $source != '' && $source != 'NULL'){echo 'checked';} ?>>
		Other</label>

		<div class="form-group other-source-input <?php if($source != 'Google' && $source != 'Quora' && $source != 'Reddit' && $source != 'Community' && $source != 'Facebook' && $source != 'Recommended'  && $source != '' && $source != 'NULL'){}else{echo 'd-none';} ?>">
			
			<input type="text" class="form-control" id="other-source" name="other-source" placeholder="Enter your source name" value="<?php if($source != 'Google' && $source != 'Quora' && $source != 'Reddit' && $source != 'Community' && $source != 'Facebook' && $source != 'Recommended'){echo $source;} ?>">
		</div>
	</div>

<!--fifth step --->	


	<div class="tab">
		<span class="error err3">Please enter website url.</span>

		<div class="form-group website_url">
			<label>Add website URL</label>
			<input type="url" class="form-control" id="website-url" name="website-url" placeholder="https://abc.com"  value="<?php echo $website_url; ?>"  >
			<small>(eg. https://abc.com , http://xyz.com)</small>
		</div>

		<div class="form-group shopify-domain-input d-none">
			<label>Add shopify admin domain URL</label>
			<input type="url" class="form-control" id="shopify-url" name="shopify-url" placeholder="https://admin.shopify.com/store/abc" value="<?=$shopify_domain_url?>">
			<small>(eg. https://admin.shopify.com/store/abc , https://abc.myshopify.com )</small> <span class="shopify_pop" id="shopify_pop"><?xml version="1.0" ?><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208S370.7 464 256 464zM256 336c-18 0-32 14-32 32s13.1 32 32 32c17.1 0 32-14 32-32S273.1 336 256 336zM289.1 128h-51.1C199 128 168 159 168 198c0 13 11 24 24 24s24-11 24-24C216 186 225.1 176 237.1 176h51.1C301.1 176 312 186 312 198c0 8-4 14.1-11 18.1L244 251C236 256 232 264 232 272V288c0 13 11 24 24 24S280 301 280 288V286l45.1-28c21-13 34-36 34-60C360 159 329 128 289.1 128z"/></svg></span>
			
			<div class="shopify_pop_wrap" style="display:none;">
				<div class="shopify_pop_ss">
                 <img src="./img/Shopify_url_1.jpg"> 
                 <img src="./img/Shopify_url_	2.jpg">         

				<?xml version="1.0" ?><!DOCTYPE svg  PUBLIC '-//W3C//DTD SVG 1.1//EN'  'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'><svg  class="close_s_pp"id="Layer_1" style="enable-background:new 0 0 64 64;" version="1.1" viewBox="0 0 64 64" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><g id="Icon-Close" transform="translate(381.000000, 231.000000)"><polyline class="st0" id="#fff" points="-370.7,-174.7 -373,-177 -327,-223 -324.7,-220.7 -370.7,-174.7    "/><polyline class="st0" id="Fill-17" points="-327,-174.7 -373,-220.7 -370.7,-223 -324.7,-177 -327,-174.7    "/></g></g></svg>

				</div>
			</div>
		</div>
	</div>

	<div class="paginations_btn" style="overflow:auto;">
		<!-- Circles which indicates the steps of the form: -->
	<div class="paginations_dots">
		<!-- <span class="step"></span> -->
		<span class="step"></span>
		<span class="step"></span>
		<span class="step"></span>
		<span class="step"></span>
		
	</div>
	<div class="form_btns">
			<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
			<button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
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

// Current tab is set to be the first tab (0)
	var currentTab = 0; 

if('<?php echo $describes_your_company; ?>' != "")
	currentTab = 1; 
if('<?php echo $website_category; ?>' != "")
	currentTab = 2; 
if('<?php echo $platforms; ?>' != "")
	currentTab = 3; 
if('<?php echo $source; ?>' != "")
	currentTab = 4; 	
if('<?php echo $website_url; ?>' != "")
	currentTab = 4; 

// alert(currentTab);
// Display the current tab
showTab(currentTab); 

$(document).ready(function() {



	$(".select-platform").click(function(){
		var v = $(this).val() ;

		$("#other-platform").val("");
		$(".other-platform-input").addClass("d-none");
		if ( v == "Other" ) {
			$(".other-platform-input").removeClass("d-none");
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
	// This function will figure out which tab to display
	var x = document.getElementsByClassName("tab");
	var s = document.getElementsByClassName("steps");
	// Exit the function if any field in the current tab is invalid:
	if (n == 1 && !validateForm()) return false;
	// Hide the current tab:
	if ( currentTab < x.length ) {
		if ((currentTab + n) != x.length) {
			x[currentTab].style.display = "none";
		}
		if(n==1)
		s[currentTab].classList.add("complete");
	}
	// Increase or decrease the current tab by 1:
	currentTab = currentTab + n;
	// if you have reached the end of the form...
	if (currentTab == x.length) {
		// ... the form gets submitted:
		console.log("submitting");
		send_data(currentTab);
		currentTab  = 2;
		document.getElementById("regForm").submit();
		return false;
	}
	// Otherwise, display the correct tab:
	console.log(currentTab +", "+n);
	showTab(currentTab);
	if(n==1){
	send_data(currentTab);
	}
	

}

function validateForm() {
console.log("val");
	
	var valid = true;
	// if ( currentTab == 0 ) 
	// { console.log("val 1");
	// 	$(".select-company").parent().removeClass("invalid") ;

	// 	var company = $(".select-company:checked").val() ;
	// 	if ( company == undefined || company == '' || company == null ) {
	// 		$(".select-company").parent().addClass("invalid") ;
	// 			$(".err1").show();
	// 		setTimeout(function(){
	// 			$(".err1").hide();
	// 		},3000);

	// 		valid = false;
	// 	}
	// }
	// else 
	if ( currentTab == 0 ) 
	{
		$(".website_category").parent().removeClass("invalid") ;

		var company = $(".website_category:checked").val() ;
		if ( company == undefined || company == '' || company == null ) {
			$(".website_category").parent().addClass("invalid") ;
				$(".err4").show();
			setTimeout(function(){
				$(".err4").hide();
			},3000);

			valid = false;
		}
	}	
	else if ( currentTab == 1 ) 
	{
		$("#other-platform").removeClass("invalid") ;
		$(".select-platform").parent().removeClass("invalid") ;
		$(".shopify-domain-input").addClass("d-none") ;
		$("#shopify-url").val("") ;

		var platform = $(".select-platform:checked").val() ;
		if ( platform == undefined || platform == '' || platform == null ) {
			$(".select-platform").parent().addClass("invalid") ;
				$(".err2").show();
			setTimeout(function(){
				$(".err2").hide();
			},3000);
			valid = false;
		}
		else if( platform == "Other" ) 
		{
			var otherPlatform = $("#other-platform").val() ;
			if ( otherPlatform == undefined || otherPlatform == '' || otherPlatform == null ) {
				$("#other-platform").addClass("invalid") ;
				valid = false;
			}
		}
		else if( platform == "Shopify" ) {
			$(".shopify-domain-input").removeClass("d-none") ;
		}
	}
	else if(currentTab == 2){
		console.log("ss");
		$("#other-source").removeClass("invalid") ;
		$(".select-source").parent().removeClass("invalid") ;
		// $(".shopify-domain-input").addClass("d-none") ;
		// $("#shopify-url").val("") ;

		var source = $(".select-source:checked").val() ;
		if ( source == undefined || source == '' || source == null ) {
			$(".select-source").parent().addClass("invalid") ;
				$(".err2").show();
			setTimeout(function(){
				$(".err2").hide();
			},3000);
			valid = false;
		}
		else if( source == "Other" ) 
		{
			var otherSource = $("#other-source").val() ;
			if ( otherSource == undefined || otherSource == '' || otherSource == null ) {
				$("#other-source").addClass("invalid") ;
				valid = false;
			}
		}
		// else if( platform == "Shopify" ) {
		// 	$(".shopify-domain-input").removeClass("d-none") ;
		// }

	}	
	else if ( currentTab == 3 ) 
	{
		$.ajaxSetup({ async: false }); 
		console.log("ss");
		$("#website-url , #shopify-url").removeClass("invalid") ;
		var websiteUrl = $("#website-url").val() ;
		if ( websiteUrl == undefined || websiteUrl == '' || websiteUrl == null ) {
			$("#website-url").addClass("invalid") ;
			    $(".err3").html("Please enter website url.");
				$(".err3").show();
			setTimeout(function(){
				$(".err3").hide();
			},3000);
			valid = false;
		}
		else if ( !isUrlValid(websiteUrl) ) {
			$("#website-url").addClass("invalid") ;
			    $(".err3").html("Please enter website valid url.");
				$(".err3").show();
			setTimeout(function(){
				$(".err3").hide();
			},3000);
			valid = false;
		}

		var platform = $(".select-platform:checked").val() ;
		if( platform == "Shopify" ) 
		{
			var shopifyUrl = $("#shopify-url").val() ;
			if ( shopifyUrl == undefined || shopifyUrl == '' || shopifyUrl == null ) {
				$("#shopify-url").addClass("invalid") ;
			    $(".err3").html("Please enter website valid url.");
				$(".err3").show();
			setTimeout(function(){
				$(".err3").hide();
			},3000);
			valid = false;
			}
			else if ( !isUrlValid(shopifyUrl) ) {
				$("#website-url").addClass("invalid") ;
			    $(".err3").html("Please enter website valid url.");
				$(".err3").show();
			setTimeout(function(){
				$(".err3").hide();
			},3000);				
				valid = false;
			}
		}

		    if(valid == true){
		    	var user_id = '<?=$_SESSION["user_id"]?>';
				    $.ajax({
				      type: "POST",
				      url: "inc/check.php",
				      data: {"url":$("#website-url").val(),"user":user_id},
				      dataType: "json",
				      encode: true,
				      async:false,
				    }).done(function (data) {
				    	if(data == 0){
					      valid == false;
							    $(".err3").html("Website Url already exixts.");
								$(".err3").show();
							setTimeout(function(){
								$(".err3").hide();
							},10000);


				    	}
				    	else{
				    		valid = true;
				    		document.getElementById("regForm").submit();
				    	}

				    });
			}


	}
	else {
		valid = false;
	}

	// If the valid status is true, mark the step as finished and valid:
if(currentTab != 3){
	if (valid) {
		$(".step:eq("+currentTab+")").addClass("finish") ;
		// document.getElementsByClassName("step")[currentTab].className += " finish";
	}
// valid = false;
	return valid; // return the valid status
}

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
	console.log("sendind");
if(flow==1)
	flow = "describes_your_company";
else if(flow==2)
	flow = "website_category";
else if(flow==3)
	flow = "platforms";
else if(flow==4)
	flow = 'source';
else if(flow==5)
	flow = "website_url";

var user_id = '<?=$_SESSION["user_id"]?>';
var data = "";
if(flow=='describes_your_company')
{
data = $('input[name="describe-company"]:checked').val();
}
else if(flow=='website_category')
{
data = $('input[name="website_category"]:checked').val();
}
else if(flow=='platforms')
{
data = $('input[name="website-platform"]:checked').val();
	if(data == 'Other'){
		data = $("#other-platform").val();
	}

}
else if(flow=='source')
{
data = $('input[name="website-source"]:checked').val();
	if(data == 'Other'){
		data = $("#other-source").val();
	}

}
else if(flow=='website_url')
{
	if($('input[name="website-platform"]:checked').val()=='Shopify')
		data = $('#website-url').val()+","+$('#shopify-url').val();
	else
		data = $('#website-url').val();

}

var appUser = {"user_id":user_id , "flow":flow, "data":data};

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
      url: "https://help.websitespeedy.com/actions/account/register",
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
      
    });	   

});
	</script>





