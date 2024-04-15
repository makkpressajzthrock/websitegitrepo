<?php
error_reporting(1);

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php');
$last__id="";
// $requestd_url="";
// check sign-up process complete
// checkSignupComplete($conn) ;
$subsc_id = base64_decode($_REQUEST['sid']);
$subsc_id_url = $_REQUEST['sid'];
$user_id = $_SESSION['user_id'];
$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");
		

		$newappsumo = "";
		if($row['user_type'] == "AppSumo" || $row['user_type'] == "Dealify" || $row['user_type'] == "DealFuel"){
			$newappsumo = $row['sumo_new'];
		}	

		$plan_country = "";
		if($row['country'] != "101"){
			$plan_country = "-us";
		}
// Show Expire message //
	include("error_message_bar_subscription.php");
// End Show Expire message //	

		

$boost_website_temp = getTableData($conn, " boost_website_temp ", " manager_id ='" . $_SESSION['user_id'] . "'");

$temp_platform = $boost_website_temp['platform'];
$temp_platform_name = $boost_website_temp['platform_name'];
$temp_website_name = $boost_website_temp['website_name'];
$temp_website_url = $boost_website_temp['website_url'];
$temp_shopify_url = $boost_website_temp['shopify_url'];

$rowSubs_t = getTableData($conn, " user_subscriptions ", " id ='" . $subsc_id . "' ");

$websites = getTableData($conn, " boost_website ", " manager_id = '" . $user_id . "' and subscription_id = '" . $subsc_id . "'  ", "", 1);
$av = $rowSubs_t['site_count'] - count($websites);
$rowSubs_t_id = $rowSubs_t['plan_id'];
//$rowSubs_t_id =4;
if ($rowSubs_t['site_count'] == "Unlimited") {
	$av = 1;
}
$cancled = $rowSubs_t['is_active'];


if (isset($_POST["add-new"])) {

	// print_r($_POST);die();


	$website_platform = $_POST['website-platform'];
	$other_platform = $_POST['other-platform'];
	$website_url = strtolower($_POST['website-url']);
	$shopify_url = strtolower($_POST['shopify-url']);
	$website_name = $_POST['website-name'];
	$sid = $subsc_id;
	//$sid = 4 ;


	if (empty($website_platform) || empty($website_url)) {
		$_SESSION['error'] = "Please fill all required values.";
	} elseif (($website_platform == "Other") && empty($other_platform)) {
		$_SESSION['error'] = "Please provide other platform name.";
	} elseif (!filter_var($website_url, FILTER_VALIDATE_URL)) {
		$_SESSION['error'] = "Please enter a valid website url.";
	} elseif ($website_platform == "Shopify" && (empty($shopify_url) || (!filter_var($shopify_url, FILTER_VALIDATE_URL)))) {
		$_SESSION['error'] = "Please enter a valid shopify admin url.";
	} else {


 $web_parsed =  parse_url($website_url)["host"];


 $domain_name = $web_parsed;

$websites_check = getTableData($conn, " boost_website ", " manager_id = '" . $user_id . "' and website_url like '%" . $domain_name . "%'", "", 1);
 
if(count($websites_check)>0)
{
	$_SESSION['error'] = "This website is already added, Please go to all sites section or <a href='".HOST_URL."adminpannel/dashboard.php'> click here </a>, If you have added the wrong url, you can edit it from All sites section too ";
	header("location: ".HOST_URL."adminpannel/add-website.php") ;
	die;

}



		$user_id = $_SESSION["user_id"];

		$desktop_score = 0;
		$mobile_score = 0;

		$sid = "111111";
		$rowSubs_t_id = "999";
		// $query = $conn->query("SELECT * FROM boost_website WHERE website_url = '$website_url'");
		// if ($query->num_rows <= 0) {

			 $sql = " INSERT INTO `boost_website`( `manager_id`, `platform`, `platform_name`, `website_url`, `shopify_url`, `desktop_speed_old`, `mobile_speed_old`, `desktop_speed_new`, `mobile_speed_new` , subscription_id , website_name,plan_type,plan_id ) VALUES ( '$user_id' , '$website_platform' , '$other_platform' , '$website_url' , '$shopify_url' , '$desktop_score' , '$mobile_score' , '$desktop_score' , '$mobile_score' , '$sid' , '$website_name','Subscription','$rowSubs_t_id' ) ; ";

// die;
			// if ( $conn->query($sql) === TRUE ) {

			if ($conn->query($sql)  === TRUE) {

				// echo "if";

				sleep(1);
				$last_id = $conn->insert_id;

				$site_id = $last_id ;
// die;
		 $sqldel = "DELETE FROM `boost_website_temp` WHERE manager_id = '$user_id'";
		$conn->query($sqldel);

				$additional_website_name = $_POST['additional_website_name'];
				$additional_website_url = $_POST['additional_website_url'];
				// $current_additional_website_id = $_POST['additional_web_id'];
				// $deleted_fields = $_POST['deleted_fields'];

				$user_id = $_SESSION['user_id'];
				$website_platform = $_POST['website-platform'];
				$plusbtn =	$_POST['additonal_website'];
				if (isset($last_id)) {

					$data = google_page_speed_insight($website_url, "desktop");

					if (is_array($data)) {
						$lighthouseResult = $data["lighthouseResult"];
						$requestedUrl = $lighthouseResult["requestedUrl"];
						$finalUrl = $lighthouseResult["finalUrl"];
						$userAgent = $lighthouseResult["userAgent"];
						$fetchTime = $lighthouseResult["fetchTime"];
						$environment = $conn->real_escape_string(serialize($lighthouseResult["environment"]));
						$runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"]));
						$configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"]));
						$audits = $conn->real_escape_string(serialize($lighthouseResult["audits"]));
						$categories = $conn->real_escape_string(serialize($lighthouseResult["categories"]));
						$categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"]));
						$i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"]));


						// mobile details
						$mobile_data = google_page_speed_insight($website_url, "mobile");

						if (is_array($mobile_data)) {
							$mobile_lighthouseResult = $mobile_data["lighthouseResult"];

							$mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"]));
							$mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"]));
							$mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"]));
							$mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"]));
							$mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"]));
							$mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"]));
							$mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"]));
						} else {
							$mobile_lighthouseResult = $mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n = null;
						}


						if ($last_id) {
							$sql = " INSERT INTO pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$last_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) ";
							// echo "sql ".$sql."<br>";   
							// die(); 
							if ($conn->query($sql) == true) {
								// echo "success";
								$last__id=$conn->insert_id;
								
								$sql_2="SELECT * FROM `pagespeed_report` WHERE `id` = '".$last__id."' ORDER BY `pagespeed_report`.`id` DESC";
					$result2=$conn->query($sql_2);
					$requested_Url1=$result2->fetch_assoc();
					$ps_mobile_categories1 = unserialize($requested_Url1["mobile_categories"]);
					$ps_mobile1 = round($ps_mobile_categories1["performance"]["score"] * 100, 2);
					$ps_categories1 = unserialize($requested_Url["categories"]);
					$ps_desktop1 = round($ps_categories1["performance"]["score"] * 100, 2);
					
					if($ps_desktop1==0 || $ps_mobile1==0){
						$requestd_url=0;
					}else{
						$requestd_url=1;
						

					}


							}
						}
					}
				}

				if (!empty($additional_website_name)) {
					for ($i = 0; $i < count($additional_website_name); $i++) {


						$sql2 = "INSERT INTO `additional_websites`(`manager_id`, `website_id`, `website_platform`, `website_name`, `website_url`) VALUES ('$user_id','$last_id','$website_platform','" . $additional_website_name[$i] . "','" . $additional_website_url[$i] . "')";

						$dones = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
						$additional = mysqli_insert_id($conn);
						$data = google_page_speed_insight($additional_website_url[$i], "desktop");

						if (is_array($data)) {
							$lighthouseResult = $data["lighthouseResult"];
							$requestedUrl = $lighthouseResult["requestedUrl"];
							$finalUrl = $lighthouseResult["finalUrl"];
							$userAgent = $lighthouseResult["userAgent"];
							$fetchTime = $lighthouseResult["fetchTime"];
							$environment = $conn->real_escape_string(serialize($lighthouseResult["environment"]));
							$runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"]));
							$configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"]));
							$audits = $conn->real_escape_string(serialize($lighthouseResult["audits"]));
							$categories = $conn->real_escape_string(serialize($lighthouseResult["categories"]));
							$categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"]));
							$i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"]));


							// mobile details
							$mobile_data = google_page_speed_insight($additional_website_url[$i], "mobile");

							if (is_array($mobile_data)) {
								$mobile_lighthouseResult = $mobile_data["lighthouseResult"];

								$mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"]));
								$mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"]));
								$mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"]));
								$mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"]));
								$mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"]));
								$mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"]));
								$mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"]));
							} else {
								$mobile_lighthouseResult = $mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n = null;
							}


							if ($additional) {
								$sql = " INSERT INTO pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$additional' , '$last_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) ";
								// echo "sql ".$sql."<br>";    
								if ($conn->query($sql) == true) {
									// echo "success";

								}
							}
						}
					}
				}

				// die;

				$_SESSION['success'] = " New website is added successfully. ";
				require_once("generate_script_paid.php");
			} 
			else {
				// echo $conn->error;
				$_SESSION['error'] = "Operation Failed.";
			}
		// }
		// else {
		// 	$_SESSION['error'] = 'Already registered in our portal.';
		// }
	}




	if ($_SESSION['error'] == null) {
		// code...

		if($newappsumo==1)
		{
				header("location: ".HOST_URL."adminpannel/dashboard.php?popup=".base64_encode($last_id)) ;
				die();
		}else{		
				header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($last_id)) ;
				die();
		}
	}
}

// if (empty(count($row))) {
// 	header("location: " . HOST_URL . "adminpannel/");
// 	die();
// }

// if ($cancled == 0) {
// 	header("location: " . HOST_URL . "plan.php");
// 	// header("location: ".$_SERVER['HTTP_REFERER']);
// 	// $_SESSION['error'] = "Inactive subscription you can't add site here." ;
// 	die();
// }

// if ($av <= 0) {
// 	header("location: " . HOST_URL . "plan.php");
// 	// $_SESSION['error'] = "Limit Reached Please Add New Plan For Adding New Website. <a href='/ecommercespeedy/plan.php' class='btn btn-primary'>Get Plan</a>" ;
// 	die();
// }


?>
<?php require_once("inc/style-and-script.php"); ?>
<style type="text/css">
	/* Mark input boxes that gets an error on validation: */
	.invalid {
		background-color: #ffdddd;
	}
	.loader {
		background-color: #ffffff5e;
		height: 100%;
		position: absolute;
		text-align: center;
		margin: auto;
		display: none;
		width: 100%;
	}
</style>
</head>

<body class="custom-tabel">
	<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>

		<!-- Page content wrapper-->
		<div id="page-content-wrapper">

			<?php require_once("inc/topbar.php"); ?>

			<!-- Page content-->
			<div class="container-fluid add_website_SS content__up">
				<h1 class="mt-4">Add New Website</h1>
				<h5><!-- Will affect your subscription amount. --></h5>
				<?php require_once("inc/alert-status.php"); ?>
				<div class="profile_tabs">

				<div class="subscription_plan_ws">

					<div class="subscription_plan" style="display: none;">
						You Are Adding Site In
						<select id="change_plan">
							<?php
							$projects = getTableData($conn, " user_subscriptions ", " user_id = '" . $_SESSION['user_id'] . "' and  is_active = 1", "", 1);
							foreach ($projects as $project_data) {
								# code...


								$webs = getTableData($conn, " boost_website ", " manager_id = '" . $_SESSION['user_id'] . "' and subscription_id='" . $project_data['id'] . "' and plan_type = 'Subscription' ", "", 1);

								// echo $project_data['id'];
								 $av = $project_data['site_count'] - count($webs);
								// echo "asdasdadas";
							?>


								<?php
								if ($av >= 1) {
									// echo $project_data['plan_id'];
									$plan = getTableData($conn, " plans ", " id ='" . $project_data['plan_id'] . "' and status = 1");
									// print_r($plan);
									// echo $plan['name'];
								?>
									<option value="<?= base64_encode($project_data['id']) ?>" <?php if ($subsc_id == $project_data['id']) {
																									echo "selected";
																								} ?>><?= $plan['name'] ?> (<?=ucfirst($plan["interval"])?>)</option>

								<?php


								}

								?>


							<?php

							}

							?>
						</select>
					</div>

					<div class="back_btn_wrap ">
						<a href="<?= HOST_URL ?>adminpannel/my-subscriptions.php" class="btn btn-primary">Back</a>
					</div>
						</div>
					<?php
					$user_id = $_SESSION["user_id"];
					?>
						<div class="loader"></div>
					<div class="form_h">
						<form id="add-website-form" name="form-add-website" method="post">

							<div class="form-group">
								<h3>What is your website Platforms?</h3>
								<label class="form-control">

									<input type="radio" class="select-platform" required name="website-platform" value="Shopify" <?php if($temp_platform == 'Shopify'){echo 'checked';} ?>>
									Shopify

								</label>

								<label class="form-control"><input type="radio" class="select-platform" required name="website-platform" value="Bigcommerce" <?php if($temp_platform == 'Bigcommerce'){echo 'checked';} ?> >Bigcommerce</label>

								<label class="form-control"><input type="radio" class="select-platform" required name="website-platform" value="Wix" <?php if($temp_platform == 'Wix'){echo 'checked';} ?>>Wix</label>

								<label class="form-control"><input type="radio" class="select-platform" required name="website-platform" value="SquareSpace" <?php if($temp_platform == 'SquareSpace'){echo 'checked';} ?> >SquareSpace</label>

								<label class="form-control"><input type="radio" class="select-platform" required name="website-platform" value="Clickfunnels" <?php if($temp_platform == 'Clickfunnels'){echo 'checked';} ?>>Clickfunnels</label>

								<label class="form-control"><input type="radio" class="select-platform" required name="website-platform" value="Shift4Shop" <?php if($temp_platform == 'Shift4Shop'){echo 'checked';} ?>>Shift4Shop</label>

								<label class="form-control"><input type="radio" class="select-platform" required name="website-platform" value="Custom Website" <?php if($temp_platform == 'Custom Website'){echo 'checked';} ?>>Custom Website</label>
							
								<div class="form-group other-platform-input-custom <?php if($temp_platform == 'Custom Website'){}else{echo 'd-none';} ?> " >

									<input type="text" class="form-control" id="other-platform-custom" name="other-platform" placeholder="Enter platform name"  value="<?php if($temp_platform == 'Custom Website'){echo $temp_platform_name;} ?>">
								</div>


								<label class="form-control"><input type="radio" class="select-platform" required name="website-platform" value="Other"  <?php if($temp_platform == 'Other'){echo 'checked';} ?>>Other</label>

								<div class="err_platform"></div>
							</div>
							<div class="form-group other-platform-input <?php if($temp_platform == 'Other'){}else{echo 'd-none';} ?> " >

								<input type="text" class="form-control" id="other-platform" name="other-platform" placeholder="Enter platform name"  value="<?php if($temp_platform == 'Other'){echo $temp_platform_name;} ?>">
							</div>

							<div class="form-group_S">
								<div class="form-group">
									<label>Add Website Name</label>
									<input type="text" class="form-control" id="website-name" name="website-name" required placeholder="website Name" value="<?=$temp_website_name?>">
								</div>
								<div class="form-group">
									<label>Add Website URL</label>
									<input type="url" class="form-control" id="website-url" name="website-url" placeholder="https://abc.com"  value="<?=$temp_website_url?>">
									<small>(eg. https://abc.com , http://xyz.com)</small>
								</div>
							</div>

							<div class="form-group shopify-domain-input  <?php if($temp_platform == 'Shopify'){}else{echo 'd-none';} ?> ">
								<label>Add shopify domain URL</label>
								<input type="url" class="form-control" id="shopify-url" name="shopify-url" placeholder="https://abc.myshopify.com"  value="<?=$temp_shopify_url?>">
								<small>(eg. https://abc.myshopify.com , http://xyz.myshopify.com)</small>
							</div>
							<div id="new_website">
								<?php

								$i = 0;

								//gettting additional website's data in arrays
								foreach ($additional_data as $additional_data_key => $additional_data_value) {

									$additional_website_name[] = $additional_data_value['website_name'];

									$additional_website_url[] = $additional_data_value['website_url'];

									$num = $i + 2;

									echo '<div class="additional_websites"><div class="col-md-6"><div class="form-group"><label>Add Website Name ' . $num . '</label><input type="hidden" name="additional_web_id[]"  value="' . $additional_data_value['id'] . '"><input type="text" class="form-control additonal-names " id="website_name' . $i . '" value="' . $additional_website_name[$i] . '" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" ></div></div><div class="col-md-6"><div class="form-group"><label>Add Website URL ' . $num . '</label><input type="url" class="form-control additonal-urls" value="' . $additional_website_url[$i] . '" id="website_url' . $i . '"  name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" ><small>(eg. https://abc.com , http://xyz.com)</small></div></div><button type="button" class="btn btn-danger" id="remove_website" style="width: 3%" name="remove_website[]" onclick="$(this).parent().remove(); btn_check(' . $additional_data_value['id'] . ');">-</button></div>';

									$i++;
								}
								?>
							</div>

							<div class="form-group add_web">
								<button type="button" class="btn btn-danger" id="additonal_website" name="additonal_website" onclick="add_more_websites(this);">+</button>
							</div>
							<div class="form_h_submit">
								<button type="submit" class="btn btn-primary add_new" value="add-new" name="add-new">Submit</button>
							</div>
							<?php

							$speed = getTableData( $conn , " pagespeed_report " , " id ='$last__id' " );
							 $ps_mobile_categories1 = unserialize($speed["mobile_categories"]);
                                $ps_mobile1 = round($ps_mobile_categories1["performance"]["score"] * 100, 2);
                                $ps_categories1 = unserialize($speed["categories"]);
                                $ps_desktop1 = round($ps_categories1["performance"]["score"] * 100, 2);

					 if($ps_desktop1==0 || $ps_mobile1==0){

								?>
								
								<button type="button" hidden class="btn btn-primary reanalyze-btn" data-website_name="<?=$project_data["website_url"]?>" data-ps_mobile="-" data-ps_performance="-" data-ps_accessibility="-" data-ps_best_practices="-" data-ps_seo="-" data-ps_pwa="-" data-website_url="<?=$project_data["website_url"]?>" data-ps_desktop="-" data-additional="0"><svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg><!-- <i class="fa fa-refresh" aria-hidden="true"></i> Font Awesome fontawesome.com --></button>
								<script type="text/javascript">
									$(document).ready(function(){
									$(".reanalyze-btn").click();
									});
								</script>

								<?php
									}		
								?>						
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>

</body>

</html>

<script type="text/javascript">
	$(document).ready(function() {



$(".select-platform").click(function(){
	var data  = $('input[name="website-platform"]:checked').val();
 		var appUser = {"user_id":<?=$user_id?> , "field":'platform', "data":data};
		SaveData(appUser);
});

$("#other-platform").keyup(function(){
	var data  = $("#other-platform").val();
 		var appUser = {"user_id":<?=$user_id?> , "field":'platform_name', "data":data};
		SaveData(appUser);
});
$("#other-platform-custom").keyup(function(){
	var data  = $("#other-platform-custom").val();
 		var appUser = {"user_id":<?=$user_id?> , "field":'platform_name', "data":data};
		SaveData(appUser);
});


$("#website-name").keyup(function(){
	var data  = $("#website-name").val();
		var appUser = {"user_id":<?=$user_id?> , "field":'website_name', "data":data};
		SaveData(appUser);
});

$("#website-url").keyup(function(){
	var data  = $("#website-url").val();
		var appUser = {"user_id":<?=$user_id?> , "field":'website_url', "data":data};
		SaveData(appUser);
});
$("#shopify-url").keyup(function(){
	var data  = $("#shopify-url").val();
		var appUser = {"user_id":<?=$user_id?> , "field":'shopify_url', "data":data};
		SaveData(appUser);
});
function SaveData(appUser){
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "add_website_temp.php", true); 

		xhttp.onreadystatechange = function() { 
		   if (this.readyState == 4 && this.status == 200) {
		   }
		};

		xhttp.send(JSON.stringify(appUser));	
}


		$('#change_plan').change(function() {
			var sid = $(this).val();
			window.location.href = "<?= HOST_URL ?>adminpannel/add-website.php?sid=" + sid;
		});

		var valid = true;
		var f=0;

		function addDomain(btn) {

			var box = $(btn).attr("data-box");

			$("." + box).append('<div class="form-group more-domains"><input type="text" class="form-control" id="" name="additional_url[]" value=""><button type="button" class="btn btn-danger" onclick="removeDomain(this);"><i class="fa fa-minus" aria-hidden="true"></i></button></div>');

			var len = $(".more-domains").length;
			if (len >= 5) {
				$("button[data-box='domain-box']").remove();
			}

		}

		function removeDomain(btn, id = null) {

			$(btn).parent().remove();

			if (id != null) {

				if ($('#deleted_urls').val() == "") {
					$('#deleted_urls').append(id);
				} else {
					$('#deleted_urls').append(',' + id)
				}
			}

		}

		$(".select-platform").click(function() {
			var v = $(this).val();

			$("#other-platform").val("");
			$(".other-platform-input,.shopify-domain-input").addClass("d-none");

			if (v == "Other") {
				$(".other-platform-input").removeClass("d-none");
				$(".other-platform-input-custom").addClass("d-none");
			} else if (v == "Shopify") {
				$(".shopify-domain-input").removeClass("d-none");
			}
			else if( v == "Custom Website" ) {
				$(".other-platform-input").addClass("d-none");
				$(".other-platform-input-custom").removeClass("d-none");
				
			}	

		});

		$("#add-website-form").submit(function(e) {
			var valid = true;
			f = 1;

			$(".select-platform").parent().removeClass("invalid");
			$("#website-url , #shopify-url").removeClass("invalid");

			var platform = $(".select-platform:checked").val();
			if (platform == undefined || platform == '' || platform == null) {
				$(".select-platform").parent().addClass("invalid");
				valid = false;
			} else if (platform == "Other") {
				var otherPlatform = $("#other-platform").val();
				if (otherPlatform == undefined || otherPlatform == '' || otherPlatform == null) {
					$("#other-platform").addClass("invalid");
					valid = false;
				}
			}


			var websiteUrl = $("#website-url").val();
			if (websiteUrl == undefined || websiteUrl == '' || websiteUrl == null) {
				$("#website-url").addClass("invalid");
				valid = false;
			} else if (!isUrlValid(websiteUrl)) {
				$("#website-url").addClass("invalid");
				valid = false;
			}


			if (platform == "Shopify") {
				var shopifyUrl = $("#shopify-url").val();
				if (shopifyUrl == undefined || shopifyUrl == '' || shopifyUrl == null) {
					$("#shopify-url").addClass("invalid");
					valid = false;
				} else if (!isUrlValid(shopifyUrl)) {
					$("#website-url").addClass("invalid");
					valid = false;
				}
			}



			$(".additonal-urls , .additonal-names , #website_name").removeClass("invalid");

			//  ============================================
			// e.preventDefault() ;

			// console.log("call") ;

			// var website_name = $("#website_name").val() ;
			// if ( website_name == undefined || website_name == '' || website_name == null ) {
			// 	$("#website_name").addClass("invalid") ;
			// 	valid = false;
			// }

			// var website = $("#website-url").val() ;
			// var website_parse = new URL(website) ;
			// var website_origin = website_parse.origin ;

			// console.log("website_origin : "+website_origin) ;
			// console.log($(".additonal-urls").length) ;

			$(".additonal-names").each(function(i, o) {
				var name = $(o).val();
				if (name == "" || name == null || name == undefined) {
					valid = false;
					$(o).addClass("invalid");
				}
			});


			var f = 0;
			$(".additonal-urls").each(function(i, o) {

				var url = $(o).val();
				if (url == "" || url == null || url == undefined) {
					valid = false;
					$(o).addClass("invalid");
				} else {
					url = new URL(url);
					var url_origin = url.origin;
					console.log("url_origin : " + url_origin);

					if (website_origin != url_origin) {
						$(o).addClass("invalid");
						f = 1;
						valid = false;
					}
				}
			});

			if (f == 1) {
				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Invalid site domain for additional urls.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
			}

	if (valid) {

	$(".loader").show().html("<div class='loader_s'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analizing your website. It might take 2-3 mins<p>     </div>");
 

	}
		});

		if (!valid) {
			e.preventDefault();
		}

	});



	function isUrlValid(userInput) {
		var res = userInput.match(/(http(s)?:\/\/.)(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/);
		if (res == null)
			return false;
		else
			return true;

	}





	var i = <?= $total_additional_websites ?> + 1;


	if (i >= 4) {

		$('#additonal_website').hide();

	}

	function btn_check(id = '') {

		$('#additonal_website').show();

		i--;

		if (id != '') {

			if ($('#deleted_fields').val().length <= 0) {

				$('#deleted_fields').append(id);

			} else {

				$('#deleted_fields').append(',' + id);
			}
		}


	}




	function add_more_websites(btn) {
		i++;

		if (i >= 5) {

			$(btn).hide();


		}

		$('#new_website').append('<div class="additional_websites"><div class="form-group"><label>Add Website Name ' + i + '</label><input type="hidden" name="additional_web_id[]"  value="new"><input type="text" required class="form-control additonal-names" id="website_name' + i + '" value="" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" ></div><div class="form-group"><label>Add Website URL ' + i + '</label><input type="url" class="form-control required additonal-urls" value="" id="website_url' + i + '"  name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" ><small>(eg. https://abc.com , http://xyz.com)</small></div><button type="button" class="btn btn-danger" id="remove_website" style="width: 3%" name="remove_website[]" onclick="$(this).parent().remove(); btn_check();">-</button></div></div>')



	}

	

</script>