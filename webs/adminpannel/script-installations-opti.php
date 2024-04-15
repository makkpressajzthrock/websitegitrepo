<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('config.php');
require_once('inc/functions.php');

$user_id = $_SESSION['user_id'];
$encode_project_id = $_GET["project"] ;
$project_id = base64_decode($encode_project_id);

$check_page = "script-installations-opti" ;


// get user data
$admin_user = $conn->query(" SELECT id, country, phone, email FROM `admin_users` WHERE `id` = '".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' ; ");

if ( $admin_user->num_rows > 0 ) {
	$user_data = $admin_user->fetch_assoc() ;

	// Matching user country to show plan link
	$plan_country = ($user_data['country'] != "101") ? "-us" : "";
	
}
else {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}

// get website data
$boost_web = $conn->query(" SELECT id, installation, url2_new_speed, url3_new_speed, new_speed, satisfy, website_name, website_url, new_website, check_first_speed, subscription_id, plan_type, platform, platform_name, self_install, self_install_team FROM `boost_website` WHERE `id` = '".$project_id."' AND manager_id = '".$_SESSION['user_id']."' ; ");

if ( $boost_web->num_rows > 0 ) {
    $website_data = $boost_web->fetch_assoc() ;

    $installation = $website_data['installation'];
    $new_speed = $website_data['new_speed'];
    $new_speed_URL2 = $website_data['url2_new_speed'];
    $new_speed_URL3 = $website_data['url3_new_speed'];
    $satisfy = $website_data['satisfy'];
    $websiteName =  $website_data['website_name']; 
    $websiteUrl =  trim($website_data['website_url']);
    $new_website =  trim($website_data['new_website']);
    $check_first_speed =  trim($website_data['check_first_speed']);//123
}

// Show Expire message 
require_once("error_message_bar_subscription.php");


// get additional urls
$sele2 = "SELECT url_priority , website_url , new_speed , id FROM `additional_websites` WHERE `manager_id` = '$user_id' and  `website_id` = '$project_id' ORDER BY id DESC ";
$sele_con2 = $conn->query($sele2) ;

$url2 = $url3 = '' ;
$urlId2 = $urlId3 = 0 ;
$url2_new_speed = $url3_new_speed = 0 ;

if ( $sele_con2->num_rows > 0 ) {
	$additionalWebsites = $sele_con2->fetch_all(MYSQLI_ASSOC) ;

	foreach($additionalWebsites as $key => $value){

		if($value['url_priority']=='2'){
			$url2 =  trim($value['website_url']);
			$url2_new_speed=  $value['new_speed'];
			$urlId2=  $value['id'];
			// break ;
		}
		
		if($value['url_priority']=='3'){
			$url3 =  trim($value['website_url']);
			$url3_new_speed=  $value['new_speed'];
			$urlId3=  $value['id'];
			// break ;
		}
	}

	$url2_new_speed = empty($url2_new_speed) ?  ( empty($urlId2) ? 0 : 1 ) : 0 ;
	$url3_new_speed = empty($url3_new_speed) ?  ( empty($urlId3) ? 0 : 1 ) : 0 ;

}


// script create logic
$urlLists = [];
$sqlURL = "SELECT id , site_id , url , bunny FROM `script_log` WHERE `site_id` = $project_id ORDER BY id DESC";

$resultURL = $conn->query($sqlURL) ;
if ($resultURL->num_rows > 0) {

	$urlFetch = $resultURL->fetch_assoc();

	$url = $urlFetch['url'];
	$urlLists = explode(',', $url);

	$count = 0;

	$domain_url = ($urlFetch['bunny']==1) ? "" : "//".$_SERVER['HTTP_HOST'] ;

	while( count($urlLists) < 3){
		// echo "Regenerate Script";
		$sqlURL = "DELETE FROM `script_log` WHERE `site_id` = $project_id";
		$conn->query($sqlURL);

		$upd = " UPDATE boost_website SET get_script = 0 WHERE manager_id='".$user_id."' AND id = '".$project_id."' ";
		$conn->query($upd);

		// sleep(1);

		if($website_data['plan_type']=="Free"){
			// echo "generate_script_free" ;
			require_once('generate_script_free.php');
		}
		else{
			require_once('generate_script_paid.php');
		}

		sleep(1);

		$sqlURL = "SELECT id , site_id , url , bunny FROM `script_log` WHERE `site_id` = $project_id ORDER BY id DESC";
		$resultURL = $conn->query($sqlURL) ;
		if ($resultURL->num_rows > 0) {

			$urlFetch = $resultURL->fetch_assoc();

			$url = $urlFetch['url'];
			$urlLists = explode(',', $url);

			$domain_url = ($urlFetch['bunny']==1) ? "" : "//".$_SERVER['HTTP_HOST'] ;
		}


	}

}

$status_request = 0;
$gs_data = [] ;
$sele_sql="SELECT id , wait_for_team , status , website_id , manager_id , manager_id , status , access_requested , optimisation_is_progress FROM generate_script_request WHERE website_id ='".$project_id."' AND manager_id = '".$user_id. "' ";
$result=$conn->query($sele_sql);
$request_sent = 0;
if ($result->num_rows > 0) {
	$data = $result->fetch_assoc() ;
	$gs_data = $data ;
	$request_sent = 1;
	$send_from = $data['wait_for_team'];
	$status_request = $data['status'];
}





/*** For Instruction jaha jaha aata hai jo customer dalta hai uske according URL aayega. ***/ 

$website_platform = strtolower($website_data["platform"]) ;

$website_instructions = HOST_HELP_URL ;
$website_instructions_label = 'Platform Instruction' ;

switch ($website_platform) {
	case 'shopify':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-shopify';
		$website_instructions_label = 'Shopify Instructions' ;
		break;

	case 'shift4shop':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-shift4shop';
		$website_instructions_label = 'Shift4shop Instructions' ;
		break;

	case 'bigcommerce':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-bigcommerce';
		$website_instructions_label = 'Bigcommerce Instructions' ;
		break;

	case 'weebly':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-weebly' ;
		$website_instructions_label = 'Weebly Instructions' ;
		break;

	case 'webflow':
	case 'Webflow':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-webflow' ;
		$website_instructions_label = 'Webflow Instructions' ;
		break;

	case 'wix':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-wix-editor-x' ;
		$website_instructions_label = 'Wix Instruction' ;
		break;

	case 'squarespace':
	case 'squarespace.':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-squarespace' ;
		$website_instructions_label = 'Squarespace Instructions' ;
		break;

	case 'clickfunnels':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-clickfunnels' ;
		$website_instructions_label = 'Clickfunnels Instructions' ;
		break;

	case 'webwave':
		$website_instructions = HOST_HELP_URL.'knowledge-base/article/how-to-install-website-speedy-on-webwave' ;
		$website_instructions_label = 'Webwave Instructions' ;
		break;

	case 'magento':
	case 'wordpress':
	case 'ekm':
	case 'neto':
	case 'americommerce':
		$website_instructions = HOST_HELP_URL.'' ;
		$website_instructions_label = 'Platform Wise Instruction' ;
		break;
	
	default:
		$website_instructions = HOST_HELP_URL.'' ;
		$website_instructions_label = 'Platform Wise Instruction' ;
		break;
}

/*** END Instruction jaha jaha aata hai jo customer dalta hai uske according URL aayega. ***/ 

?>

<?php require_once("inc/style-and-script.php");  ?>

<style>
	.loader {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100vh;
		background: #00000066;
		color: white;
		font-size: 22px;
		z-index: 4;
	}


	.input-container input {
	    border: none;
	    box-sizing: border-box;
	    outline: 0;
	    padding: .75rem;
	    position: relative;
	    width: 100%;
	}

	input[type="date"]::-webkit-calendar-picker-indicator {
	    background: transparent;
	    bottom: 0;
	    color: transparent;
	    cursor: pointer;
	    height: auto;
	    left: 0;
	    position: absolute;
	    right: 0;
	    top: 0;
	    width: auto;
	}
	input[type="time"]::-webkit-calendar-picker-indicator {
	    background: transparent;
	    bottom: 0;
	    color: transparent;
	    cursor: pointer;
	    height: auto;
	    left: 0;
	    position: absolute;
	    right: 0;
	    top: 0;
	    width: auto;
	}	



	/* CSS 18-04-2023 */

	.swal2-popup .swal2-html-container {
		overflow: unset;
		display: flex !important;
	    flex-direction: column;
	    gap: 8px;
		font-size:16px;
		color:#212529;
	}

	.swal2-popup .swal2-html-container > a {
		color:#212529;
		border-bottom:1px solid transparent;
		transition: all 200ms ease;
	}

	.swal2-popup .swal2-html-container > a:hover {
		border-bottom:1px solid #212529;
	}



	.swal2-popup .swal2-html-container .social__links a i {
	    font-size: 30px;
	}

	.swal2-html-container #request_form_2 {
		grid-column: 1/3;
	    max-width: 200px;
	    min-width: 200px;
	    margin-left: auto;
	    margin-right: auto;
	}

	.swal2-html-container br {
	  display: none;
	}

	.swal2-html-container .support__form {
		width:100%;
	}
	a.btn.btn-primary.manualbtnverify {
	    color: #ffffff;
	}
	.swal2-container .swal2-close {
	    width: 25px;
	    height: 25px;
		font-size:22px;
		right:4px;

	}


	button.btn.btn-primary.manualbtnverify+p {
	    margin: 0;
	    font-size: 24px;
	    font-weight: 600;
	    color: #595555;
	}

	.verify-icon-popup {
	max-width: 90px;
	margin: 15px auto;

	}
	.verify-icon-popup img {
	width: 100%;
	}


	.progress {
	margin: 20px auto;
	width: 80%;
	height: 25px;
	position: relative;
	background-color: #ddd;
	position: absolute;
	bottom: 115px;
	left: 0;
	right: 0;
	border-radius: 12px;
	overflow: hidden;
	}
	p.or_s {
	margin-bottom: 15px !important;
	line-height: 1;
	}
	.bar {
	background-color: #f23640;
	width: 10px;
	height: 40px;
	position: absolute;
	animation: aniWidth 500000ms linear;
	}




	@keyframes aniWidth {
		0% {
			width: 0%;
		}
		100% {
			width: 100%;
		}
	}

	.url-old-speed-score {
	border: 2px solid #f23640 !important;
	}
</style>

<!-- <script src="//cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> -->


</head>


	
<body class="custom-tabel">


	<div class="loader rmg_bg" style="display:none"></div>


	<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>
		<!-- Page content wrapper-->
		<div id="page-content-wrapper">
			<?php require_once("inc/topbar.php"); ?>
			<!-- Page content-->
			<div class="container-fluid script_I content__up">
				<h1 class="mt-4 page_heading">Install Websitespeedy</h1>
				<?php require_once("inc/alert-status.php"); ?>

				
 
				<div class="script_Icontainer">

					<div class="script_i_btn">
						<div class="text-right">
						</div>
					</div>

					<div class="container_i_web_spee_h">
						


				<div class="wrap__with__sidebar" >
					 <div class="tabber__wrapper">

					 <div class="i_web_spee_h">
							<div class="page-head">
								<h3>Let’s Setup Website Speedy to automatically Boost your website loading speed</h3>
								<p class="desc">Website Speedy automatically fix Core Web Vitals, enhance your website DOM and improves the way your website is interpreted by different browsers, Leading to lighting fast loading speed for all browsers and devices.</p>
								
							</div>
							<div class="Generate_Request_btn">
								<!-- <button class="alert-pop" >Generate Request</button> -->
							</div>

					 	</div>

							<ul class="nav nav-tabs">
								<li class="active-tab"><a href="#tab-1">Self Installation</a></li>
								<li class=""><a href="#tab-2">Install by Website Speedy Team</a></li>
							</ul>
							<div class="tab-content">


							<!-- First Tab Install by myself -->
								<div id="tab-1" class="tab-pane active-tab"> 
									<div class="request_generate_top_btn__wrapper">
										<p class="text">Please follow the Steps Below to improve your website Speed Score - </p>	
									</div>

<?php 

echo "skylight" ;

// old speedy for url1
$query = $conn->query(" SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits FROM pagespeed_report WHERE website_id = '$project_id' and parent_website = 0  ORDER BY id ASC LIMIT 1 ");

if ( $query->num_rows > 0 ) {

	$pagespeed_data = $query->fetch_assoc();
	// echo "<pre>";
	// print_r($pagespeed_data); die;
	
	$ps_categories_d = unserialize($pagespeed_data["categories"]);
	$audits_d = unserialize($pagespeed_data["audits"]);
	// echo 	$pagespeed_data["id"];

	$ps_performance_osu1 = number_format($ps_categories_d["performance"]["score"] * 100, 2, '.', '');
	$ps_desktop_osu1 = (int)$ps_performance_osu1 . "/100";
	$ps_accessibility_osu1 = number_format($ps_categories_d["accessibility"]["score"] * 100, 2, '.', '');
	$ps_best_practices_osu1 = number_format($ps_categories_d["best-practices"]["score"] * 100, 2, '.', '');
	$ps_seo_osu1 = number_format($ps_categories_d["seo"]["score"] * 100, 2, '.', '');
	$ps_pwa_osu1 = number_format($ps_categories_d["pwa"]["score"] * 100, 2, '.', '');

	$psa_fcp_d_osu1 = number_format($audits_d["first-contentful-paint"]["numericValue"],2, '.', '') ;
	$psa_lcp_d_osu1 = number_format($audits_d["largest-contentful-paint"]["numericValue"],2, '.', '') ;
	$psa_cls_d_osu1 = number_format($audits_d["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
	$psa_tbt_d_osu1 = number_format($audits_d["total-blocking-time"]["numericValue"],2, '.', '') ;
	$psa_si_d_osu1 = $audits_d["speed-index"]["displayValue"] ;
	
	// ==================================================================

	$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
	$audits_m = unserialize($pagespeed_data["mobile_audits"]);
	

	$ps_performance_m_osu1 = number_format($ps_categories_m["performance"]["score"] * 100, 2, '.', '');
	$ps_mobile_osu1 = (int)$ps_performance_m_osu1 . "/100";
	$ps_accessibility_m_osu1 = number_format($ps_categories_m["accessibility"]["score"] * 100, 2, '.', '');
	$ps_best_practices_m_osu1 = number_format($ps_categories_m["best-practices"]["score"] * 100, 2, '.', '');
	$ps_seo_m_osu1 = number_format($ps_categories_m["seo"]["score"] * 100, 2, '.', '');
	$ps_pwa_m_osu1 = number_format($ps_categories_m["pwa"]["score"] * 100, 2, '.', '');

	$psa_fcp_m_osu1 = number_format($audits_m["first-contentful-paint"]["numericValue"],2, '.', '') ;
	$psa_lcp_m_osu1 = number_format($audits_m["largest-contentful-paint"]["numericValue"],2, '.', '') ;
	$psa_cls_m_osu1 = number_format($audits_m["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
	$psa_tbt_m_osu1 = number_format($audits_m["total-blocking-time"]["numericValue"],2, '.', '') ;
	$psa_si_m_osu1 = $audits_m["speed-index"]["displayValue"] ;

	// ==================================================================

}
else {

	$ps_mobile_osu1 = $ps_desktop_osu1 = "0/100" ;

	$ps_performance_osu1 = $ps_accessibility_osu1 = $ps_best_practices_osu1 = $ps_seo_osu1 = $ps_pwa_osu1 = $psa_fcp_d_osu1 = $psa_lcp_d_osu1 = $psa_cls_d_osu1 = $psa_tbt_d_osu1 = $psa_si_d_osu1 = 0 ;

	$ps_performance_m_osu1 = $ps_accessibility_m_osu1 = $ps_best_practices_m_osu1 = $ps_seo_m_osu1 = $ps_pwa_m_osu1 = $psa_fcp_m_osu1 = $psa_lcp_m_osu1 = $psa_cls_m_osu1 = $psa_tbt_m_osu1 = $psa_si_m_osu1 = 0 ;
}				
							

// old speed for url2
$ps_mobile_osu2 = $ps_desktop_osu2 = "0/100" ;

$ps_performance_osu2 = $ps_accessibility_osu2 = $ps_best_practices_osu2 = $ps_seo_osu2 = $ps_pwa_osu2 = $psa_fcp_d_osu2 = $psa_lcp_d_osu2 = $psa_cls_d_osu2 = $psa_tbt_d_osu2 = $psa_si_d_osu2 = 0 ;

$ps_performance_m_osu2 = $ps_accessibility_m_osu2 = $ps_best_practices_m_osu2 = $ps_seo_m_osu2 = $ps_pwa_m_osu2 = $psa_fcp_m_osu2 = $psa_lcp_m_osu2 = $psa_cls_m_osu2 = $psa_tbt_m_osu2 = $psa_si_m_osu2 = 0 ;

if ( ! empty($urlId2) ) {

	// $query = $conn->query("SELECT * FROM pagespeed_report WHERE website_id = '$project_id' and requestedUrl = '$url2' ORDER BY id DESC LIMIT 1 ");

	$query = $conn->query("SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits FROM pagespeed_report WHERE website_id = '$urlId2' AND parent_website = '$project_id'  AND no_speedy='1' ORDER BY id desc LIMIT 1 ");	
	
	
	if ( $query->num_rows > 0 ) {
		$nospeedyUrl2=1;
		$pagespeed_data = $query->fetch_assoc();
		
		
		$ps_categories_d = unserialize($pagespeed_data["categories"]);
		$audits_d = unserialize($pagespeed_data["audits"]);
		// echo 	$pagespeed_data["id"];

		$ps_performance_osu2 = number_format($ps_categories_d["performance"]["score"] * 100, 2, '.', '');
		$ps_desktop_osu2 = (int)$ps_performance_osu2 . "/100";
		$ps_accessibility_osu2 = number_format($ps_categories_d["accessibility"]["score"] * 100, 2, '.', '');
		$ps_best_practices_osu2 = number_format($ps_categories_d["best-practices"]["score"] * 100, 2, '.', '');
		$ps_seo_osu2 = number_format($ps_categories_d["seo"]["score"] * 100, 2, '.', '');
		$ps_pwa_osu2 = number_format($ps_categories_d["pwa"]["score"] * 100, 2, '.', '');

		$psa_fcp_d_osu2 = number_format($audits_d["first-contentful-paint"]["numericValue"],2, '.', '') ;
		$psa_lcp_d_osu2 = number_format($audits_d["largest-contentful-paint"]["numericValue"],2, '.', '') ;
		$psa_cls_d_osu2 = number_format($audits_d["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
		$psa_tbt_d_osu2 = number_format($audits_d["total-blocking-time"]["numericValue"],2, '.', '') ;
		$psa_si_d_osu2 = $audits_d["speed-index"]["displayValue"] ;
		
		// ==================================================================

		$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
		$audits_m = unserialize($pagespeed_data["mobile_audits"]);
		

		$ps_performance_m_osu2 = number_format($ps_categories_m["performance"]["score"] * 100, 2, '.', '');
		$ps_mobile_osu2 = (int)$ps_performance_m_osu2 . "/100";
		$ps_accessibility_m_osu2 = number_format($ps_categories_m["accessibility"]["score"] * 100, 2, '.', '');
		$ps_best_practices_m_osu2 = number_format($ps_categories_m["best-practices"]["score"] * 100, 2, '.', '');
		$ps_seo_m_osu2 = number_format($ps_categories_m["seo"]["score"] * 100, 2, '.', '');
		$ps_pwa_m_osu2 = number_format($ps_categories_m["pwa"]["score"] * 100, 2, '.', '');

		$psa_fcp_m_osu2 = number_format($audits_m["first-contentful-paint"]["numericValue"],2, '.', '') ;
		$psa_lcp_m_osu2 = number_format($audits_m["largest-contentful-paint"]["numericValue"],2, '.', '') ;
		$psa_cls_m_osu2 = number_format($audits_m["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
		$psa_tbt_m_osu2 = number_format($audits_m["total-blocking-time"]["numericValue"],2, '.', '') ;
		$psa_si_m_osu2 = $audits_m["speed-index"]["displayValue"] ;

		// ==================================================================

	}

}


// old speed for url3

$ps_mobile_osu3 = $ps_desktop_osu3 = "0/100" ;

$ps_performance_osu3 = $ps_accessibility_osu3 = $ps_best_practices_osu3 = $ps_seo_osu3 = $ps_pwa_osu3 = $psa_fcp_d_osu3 = $psa_lcp_d_osu3 = $psa_cls_d_osu3 = $psa_tbt_d_osu3 = $psa_si_d_osu3 = 0 ;

$ps_performance_m_osu3 = $ps_accessibility_m_osu3 = $ps_best_practices_m_osu3 = $ps_seo_m_osu3 = $ps_pwa_m_osu3 = $psa_fcp_m_osu3 = $psa_lcp_m_osu3 = $psa_cls_m_osu3 = $psa_tbt_m_osu3 = $psa_si_m_osu3 = 0 ;

if ( !empty($urlId3) ) {

	// $query = $conn->query("SELECT * FROM pagespeed_report WHERE website_id = '$project_id' and requestedUrl = '$url3' ORDER BY id DESC LIMIT 1 ");

	$query = $conn->query("SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits FROM pagespeed_report WHERE website_id = '$urlId3' AND parent_website = '$project_id' AND no_speedy='1'  ORDER BY id desc LIMIT 1 ");	

	if ( $query->num_rows > 0 ) {
		$nospeedyUrl3 = 1;
		$pagespeed_data = $query->fetch_assoc();
		
		
		$ps_categories_d = unserialize($pagespeed_data["categories"]);
		$audits_d = unserialize($pagespeed_data["audits"]);
		// echo 	$pagespeed_data["id"];

		$ps_performance_osu3 = number_format($ps_categories_d["performance"]["score"] * 100, 2, '.', '');
		$ps_desktop_osu3 = (int)$ps_performance_osu3 . "/100";
		$ps_accessibility_osu3 = number_format($ps_categories_d["accessibility"]["score"] * 100, 2, '.', '');
		$ps_best_practices_osu3 = number_format($ps_categories_d["best-practices"]["score"] * 100, 2, '.', '');
		$ps_seo_osu3 = number_format($ps_categories_d["seo"]["score"] * 100, 2, '.', '');
		$ps_pwa_osu3 = number_format($ps_categories_d["pwa"]["score"] * 100, 2, '.', '');

		$psa_fcp_d_osu3 = number_format($audits_d["first-contentful-paint"]["numericValue"],2, '.', '') ;
		$psa_lcp_d_osu3 = number_format($audits_d["largest-contentful-paint"]["numericValue"],2, '.', '') ;
		$psa_cls_d_osu3 = number_format($audits_d["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
		$psa_tbt_d_osu3 = number_format($audits_d["total-blocking-time"]["numericValue"],2, '.', '') ;
		$psa_si_d_osu3 = $audits_d["speed-index"]["displayValue"] ;
		
		// ==================================================================

		$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
		$audits_m = unserialize($pagespeed_data["mobile_audits"]);
		

		$ps_performance_m_osu3 = number_format($ps_categories_m["performance"]["score"] * 100, 2, '.', '');
		$ps_mobile_osu3 = (int)$ps_performance_m_osu3 . "/100";
		$ps_accessibility_m_osu3 = number_format($ps_categories_m["accessibility"]["score"] * 100, 2, '.', '');
		$ps_best_practices_m_osu3 = number_format($ps_categories_m["best-practices"]["score"] * 100, 2, '.', '');
		$ps_seo_m_osu3 = number_format($ps_categories_m["seo"]["score"] * 100, 2, '.', '');
		$ps_pwa_m_osu3 = number_format($ps_categories_m["pwa"]["score"] * 100, 2, '.', '');

		$psa_fcp_m_osu3 = number_format($audits_m["first-contentful-paint"]["numericValue"],2, '.', '') ;
		$psa_lcp_m_osu3 = number_format($audits_m["largest-contentful-paint"]["numericValue"],2, '.', '') ;
		$psa_cls_m_osu3 = number_format($audits_m["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
		$psa_tbt_m_osu3 = number_format($audits_m["total-blocking-time"]["numericValue"],2, '.', '') ;
		$psa_si_m_osu3 = $audits_m["speed-index"]["displayValue"] ;

		// ==================================================================

	}

}


?>

						<!-- Horizontal Steppers -->
						<div class="stepper_wrapper_con <?php if($send_from == 1 and $request_sent==1 ){ echo 'disabled_div';} ?>">
							<div class="stepper_wrapper">

								<!-- Stepers Wrapper -->
								<ul class="stepper stepper-horizontal">
									<!-- First Step  completed -->
									<li class="active">
										<a class="top-step-1" tab="#step1">
										<span class="circle bg-info">1</span>
										<span class="label">First step</span>
										<span class="step_fill_s"><span></span></span>
										</a>
									</li>
									<li class="warning">
										<a  class="top-step-2" tab="#step2">
										<span class="circle <?php if($installation >=2){echo "bg-info";}else{echo "bg-light";} ?>">2</span>
										<span class="label">Second step</span>
										<span class="step_fill_s"><span></span></span>
										</a>
									</li>
									<li class="warning">
										<a  class="top-step-3" tab="#step3">
										<span class="circle  <?php if($installation >=3){echo "bg-info";}else{echo "bg-light";} ?>">3</span>
										<span class="label">Third step</span>
										<span class="step_fill_s"><span></span></span>
										</a>
									</li>
									<li class="warning">
										<a  class="top-step-4" tab="#step4">
										<span class="circle  <?php if($installation >=4){echo "bg-info";}else{echo "bg-light";} ?>">4</span>
										<span class="label">Fourth step</span>
										</a>
									</li>

								</ul>
								<!-- /.Stepers Wrapper -->

							</div>



<div class="col-md-12" id="page-speed-table" data-project="<?=$website_data['id']?>" data-type="page-speed">

	<!--  Start Step 1 -->
	<div id="step1" class="form-tab complited">
		
		<div class="tabs-1-head tabs-head" tab="tabs-1">
			<span class="number" >1</span>
			<span class="text">Confirm current page speed</span>
			<span class="icon"><i class="las la-angle-down"></i></span>
		</div>
		<div class="tabs-1 form-tab-"  style="<?php if($installation >=2){echo "display:none;";}?>">
		<!-- //123 -->
			<div class="child-tab" style="display:none">
				<div class="row">
					<div class="tab-child active col-4" tab="child-tab-1">google page insights (<a href="//pagespeed.web.dev/" target="_blank">//pagespeed.web.dev/</a>)</div>
					<div class="tab-child disabled col-4" tab="child-tab-2">GTMatrix(coming soon)</div>
					<div class="tab-child disabled col-4" tab="child-tab-3">Pingdom(Coming soon)</div>
				</div>
			</div>
			
			<!-- end child 1 -->
			<div class="child-tab-2">
			</div>
			<div class="child-tab-3">
			</div>
			<!-- //123 -->
			<p style="display:none">To verify this data, you can visit <a href="//pagespeed.web.dev/" target="_blank" >//pagespeed.web.dev/</a>. There may be slight variations due to multiple factors, as explained by Google. Some of the significant factors include: Antivirus software, Browser extensions that inject JavaScript and alter network requests, Network firewalls, Server load, and DNS - Internet traffic routing changes. For more detailed information provided by Google, you can <a href="//developer.chrome.com/docs/lighthouse/performance/performance-scoring/#fluctuations" target="_blank" >click here</a>.</p>
			
				<h3>Enter URLs of 3 important Pages from your Website</h3>
				<p class="desc">WebsiteSpeedy Boosts the speed of Whole website i.e all page on your website, Please enter URLs of Homepage and 2 other most important pages from your website which will be used to display updated speed optimisation score.</p>
			<!-- //123 -->
			<div class="get-updated-script"><div class="enterurl_con"><span><h4>URL 1- Homepage </h4></span><b><a  style="pointer-events: none; " href="<?=$website_data["website_url"]?>" target="_blank" disabled ><?=$website_data["website_url"]?></a> </b></span></div> </div> 
			
			<!-- for ur2-start -->
			<?php 
				$hideform ='';
				$hideAnyBtn ='';
				
				if(!empty($site_id) && !empty($url2) ){
					$hideform = 'none';
					$hideAnyBtn = 'block';
					$disabledurl2 = " ";
				}
				else{
					// echo 2; 
					$hideform = 'block';
					$hideAnyBtn = 'none';
					$disabledurl2 = "disabled";
				}

				// $hideform = $hideAnyBtn = 'block';

			?> 
			<div id="hideAddedNewUrlForm">

				<form id="addedNewUrl" method="post" style="display:<?=$hideform?>">
					<div class="enterurl_con">
						<h4>Enter URL 2 -</h4>		
						<input type="text"  name="newUrl"  value="<?=$url2?>" onblur="removeHashAndQueryParams(this);" id="newUrl" placeholder="Category/Service Page or Similar Page URL" class="enterurl" > 
						
						
						<!-- <span class="tooltip-icon"><i class="fa fa-info-circle"></i></span> <a href="#" data-toggle="tooltip" title="We suggest adding URL’s for following pages - Ecommerce site - Category page - Non -E-commerce - Enter Services page or similar page URL">  </a>  -->

						<div class="tooltip1"><i class="fa fa-info-circle" style="display:none; fill: #f23640; color: #f23640;  cursor: pointer;"></i>
							<span class="tooltiptext1">Category page or services page or similar page URL</span>
						</div>
						
						<button type="submit" name="addedNewUrlBtn" id="addedNewUrlBtn" class="btn btn-danger">Submit</button>
						<span class="errroUrl"></span>						
						<span class="subDomainUrlError2"></span>						
						<span class="sameEnterUrl2"></span>						
					</div>
				</form>
			</div>
			<!-- //123 -->
			<div class="get-updated-script"  id="step4AnalyseBtn" style="display:<?=$hideAnyBtn?>" >
                 <div class="enterurl_con">
				<span class="additional-url2-analyse"><span><h4>URL 2-</h4></span><b> <a  style="pointer-events: none;" href="<?=$url2?>" target="_blank" disabled ><?=$url2?></a> </b></span>
				<!-- <button type="button" name="submit__btn" class="new-analyze btn btn-primary  addedvalueincstattr url2reanalyze-btn-"  tab="#step4" data-website_name="<?=$url2?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$url2?>"data-table_id="newUrl2" >Analyse Updated Speed</button> -->
				<!-- <button type="button" name="submit__btn" class="additional-reanalyse-updated-speed additional-reanalyse2-speed" data-website_name="<?=$url2?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$url2?>" data-additional-id="<?=$urlId2?>" data-table_id="newUrl2" data-boosted="url2_inst_speed_new" data-oldy="url2_inst_speed" data-container="url2_inst_speed_new-container" >Analyse URL2 Updated Speed</button> -->
			</div>
			</div>

			<!-- for url3-start -->
			
			<?php 
				if(!empty($site_id) && !empty($url3) ){
					$hideform = 'none';
					$hideAnyBtn = 'block';
					$disabled = " ";
				}else{
					$hideform = 'block';
					$hideAnyBtn = 'none';
					$disabled = "disabled";
				}
			?>
			<div id="hideAddedNewUrl3Form" >

				<form id="addedNewUrl3" method="post" style="display: <?=$hideform?>">
					<div class="enterurl_con">	
					<h4>Enter URL 3 -</h4>		
						<input type="text"  name="newUrl3" onblur="removeHashAndQueryParams(this);" id="newUrl3" value="<?=$url3?>" placeholder="Product/Landing Page or Similar Page URL" class="enterurl" >  
						<!-- <a href="#" data-toggle="tooltip" title="We suggest adding URL’s for following pages - Ecommerce site - Category page - Non -E-commerce - Enter Services page or similar page URL"> <i class="fa fa-info-circle"></i></a> -->

						<div class="tooltip1"><i class="fa fa-info-circle" style="display:none; fill: #f23640; color: #f23640;  cursor: pointer;"></i>
							<span class="tooltiptext1">product page or lead generation page or similar page URL</span>
						</div>

						<button type="submit" name="addedNewUrl3Btn" id="addedNewUrl3Btn" class="btn btn-danger">Submit</button>
						<span class="errorUrl3"></span>						
						<span class="subDomainUrlError3"></span>						
						<span class="sameEnterUrl3"></span>						
					</div>
				</form>
			</div>

				<!-- //123 -->
				<div class="get-updated-script"  id="step4Url3AnalyseBtn" style="display:<?=$hideAnyBtn?>" >
                    <div class="enterurl_con">
					<span class="additional-url3-analyse"><span><h4>URL 3-</h4></span><b> <a href="<?=$url3?>" style="pointer-events: none;" target="_blank" disabled ><?=$url3?></a> </b></span>
					<br>

					<!-- <button type="button" name="submit__btn" class="new-analyze btn btn-primary  addedvalueinurl3cstattr url3reanalyze-btn-"  tab="#step5" data-website_name="<?=$url3?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$url3?>"data-table_id="newUrl3" >Analyse Updated Speed</button> -->

					<!-- <button type="button" name="submit__btn" class="additional-reanalyse-updated-speed additional-reanalyse3-speed" data-website_name="<?=$url3?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$url3?>" data-additional-id="<?=$urlId3?>" data-table_id="newUrl3" data-boosted="url3_inst_speed_new" data-oldy="url3_inst_speed" data-container="url3_inst_speed_new-container" >Analyse URL3 Updated Speed</button> -->
                    </div>
				</div>
				<!-- //123-start -->
				<div class="child-tab-1" style="display:none">
					<div class="inst_speed">
						<!-- Before Optimization Speed : -->
						<div class="page_web_speed">
							<a class="btn btn-primary page-speed1 analyse-updated-speed-mobile">Mobile</a>
							<a class="btn btn-light core-web-vital2 analyse-updated-speed-desktop">Desktop</a>
						</div>

						<div class="url_inst_speed_old">
						
						<!-- <span class="heading" >Old Speed Homepage URL Without website Speedy : </span> -->

						<span class="heading">Old Speed URL1 <a style="pointer-events: none; " href="<?=$website_data['website_url']?>" class="url1-table-head" target="_blank" disabled=""><?=$website_data['website_url']?></a> <br> Without website Speedy:</span>
							
						<table class="table table-script-speed mobile main-table-page-cvw url1-old-speed-table" >
							<thead>
								<tr>
									<th class="half-width-box">Page Speed</th>
									<th class="half-width-box">Core Web Vital</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="half-width-box">
										<table>
											<thead>
												<tr>
													<th>Performance</th>
													<th style="display:none !important;">Accessibility</th>
													<th style="display:none !important;">Best Practices</th>
													<th style="display:none !important;">SEO</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="performance_m"><?=$ps_mobile_osu1?></td>
													<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m_osu1?></td>
													<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m_osu1?></td>
													<td class="seo_m" style="display:none !important;"><?=$ps_seo_m_osu1?></td>
												</tr>
											</tbody>
										</table>
									</td>
									<td class="half-width-box">
										<table>
											<thead>
												<tr>
													<th>FCP</th>
													<th>LCP</th>
													<th>CLS</th>
													<th>TBT</th>
													<th>SI</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="fcp_m"><?=$psa_fcp_m_osu1 ?></td>
													<td class="lcp_m"><?=$psa_lcp_m_osu1 ?></td>
													<td class="cls_m"><?=number_format($psa_cls_m_osu1, 2, '.', '') ?></td>
													<td class="tbt_m"><?=$psa_tbt_m_osu1?></td>
													<td class="si_m"><?=$psa_si_m_osu1?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>

						<table class="table main-table-page-cvw desktop table-script-core url1-old-speed-table">
							<thead>
								<tr>
									<th class="half-width-box">Page Speed</th>
									<th class="half-width-box">Core Web Vital</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="half-width-box">
										<table>
											<thead>
												<tr>
													<th>Performance</th>
													<th style="display:none !important;">Accessibility</th>
													<th style="display:none !important;">Best Practices</th>
													<th style="display:none !important;">SEO</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="performance"><?=$ps_desktop_osu1?></td>
													<td class="accessibility" style="display:none !important;"><?=$ps_accessibility_osu1?></td>
													<td class="best-practices" style="display:none !important;"><?=$ps_best_practices_osu1?></td>
													<td class="seo" style="display:none !important;"><?=$ps_seo_osu1?></td>
												</tr>
											</tbody>
										</table>
									</td>
									<td class="half-width-box">
										<table>
											<thead>
												<tr>
													<th>FCP</th>
													<th>LCP</th>
													<th>CLS</th>
													<th>TBT</th>
													<th>SI</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="fcp"><?=$psa_fcp_d_osu1 ?></td>
													<td class="lcp"><?=$psa_lcp_d_osu1 ?></td>
													<td class="cls"><?=number_format($psa_cls_d_osu1, 2, '.', '') ?></td>
													<td class="tbt"><?=$psa_tbt_d_osu1?></td>
													<td class="si"><?=$psa_si_d_osu1?></td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>


						<span class="refresh-nospeedy-parent" <?php if ( $ps_performance_m_osu1 > 0 && $ps_performance_osu1 > 0 ) { echo "style='display:none;'" ; } ?> >
							<p>There was an issue in fetching your Website Speed</p>
							<button type="button" class="btn btn-primary refresh-nospeedy-url" table="url1-old-speed-table" website="<?=$website_data["id"]?>" additional="0" url="<?=$website_data["website_url"]?>" title="Refresh old speed records" >
								<svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg>
								Retry Now
							</button>
						</span>


						</div>

						
					</div>

					<!-- //123 -->
					<div class="url2_inst_speed" >
						<!-- <span class="heading" >Old Speed URL 2 Without website Speedy: </span> -->

						<span class="heading">Old Speed URL2 <a style="pointer-events: none;" href="<?=$url2?>" target="_blank" disabled class="url2-table-head" ><?=$url2?></a><br> Without website Speedy: </span>

						<div class="page-cvw-box">
							<!-- Desktop Data Starts Here -->
							<table class="table main-table-page-cvw mobile table-script-speed url2-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box" >Page Speed</th>
										<th class="half-width-box" >Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="half-width-box">
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance_m"><?=$ps_mobile_osu2?></td>
														<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m_osu2?></td>
														<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m_osu2?></td>
														<td class="seo_m" style="display:none !important;"><?=$ps_seo_m_osu2?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td class="half-width-box">
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp_m"><?=$psa_fcp_m_osu2 ?></td>
														<td class="lcp_m"><?=$psa_lcp_m_osu2 ?></td>
														<td class="cls_m"><?=number_format($psa_cls_m_osu2, 2, '.', '') ?></td>
														<td class="tbt_m"><?=$psa_tbt_m_osu2?></td>
														<td class="si_m"><?=$psa_si_m_osu2?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Desktop Data Ends Here -->

							<!-- Mobile Data Starts Here -->
							<table class="table main-table-page-cvw desktop table-script-core url2-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box">Page Speed</th>
										<th class="half-width-box">Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="half-width-box">
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance"><?=$ps_desktop_osu2?></td>
														<td class="accessibility" style="display:none !important;"><?=$ps_accessibility_osu2?></td>
														<td class="best-practices" style="display:none !important;"><?=$ps_best_practices_osu2?></td>
														<td class="seo" style="display:none !important;"><?=$ps_seo_osu2?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td class="half-width-box">
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp"><?=$psa_fcp_d_osu2 ?></td>
														<td class="lcp"><?=$psa_lcp_d_osu2 ?></td>
														<td class="cls"><?=number_format($psa_cls_d_osu2, 2, '.', '') ?></td>
														<td class="tbt"><?=$psa_tbt_d_osu2?></td>
														<td class="si"><?=$psa_si_d_osu2?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Mobile Data Ends Here -->

							<span class="refresh-nospeedy-parent" <?php if ( $ps_performance_m_osu2 > 0 && $ps_performance_osu2 > 0 ) { echo "style='display:none;'" ; } ?> >
								<p>There was an issue in fetching your Website Speed</p>
								<button type="button" class="btn btn-primary refresh-nospeedy-url" table="url2-old-speed-table" website="<?=$website_data["id"]?>" additional="<?=$urlId2?>" url="<?=$url2?>" title="Refresh old speed records" >
									<svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg>
									Retry Now
								</button>
							</span>
						</div>
					</div>

					<!-- //123 -->
					<div class="url3_inst_speed" >
						<!-- <span class="heading" >Old Speed URL3 Without website Speedy: </span> -->

						<span class="heading">Old Speed URL3 <a href="<?=$url3?>" style="pointer-events: none;" target="_blank" disabled class="url3-table-head" ><?=$url3?></a><br> Without website Speedy: </span>

						<div class="page-cvw-box">
							<!-- Desktop Data Starts Here -->
							<table class="table main-table-page-cvw mobile table-script-speed url3-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box" >Page Speed</th>
										<th class="half-width-box" >Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="half-width-box">
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance_m"><?=$ps_mobile_osu3?></td>
														<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m_osu3?></td>
														<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m_osu3?></td>
														<td class="seo_m" style="display:none !important;"><?=$ps_seo_m_osu3?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td class="half-width-box">
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp_m"><?=$psa_fcp_m_osu3 ?></td>
														<td class="lcp_m"><?=$psa_lcp_m_osu3 ?></td>
														<td class="cls_m"><?=number_format($psa_cls_m_osu3, 2, '.', '') ?></td>
														<td class="tbt_m"><?=$psa_tbt_m_osu3?></td>
														<td class="si_m"><?=$psa_si_m_osu3?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Desktop Data Ends Here -->

							<!-- Mobile Data Starts Here -->
							<table class="table main-table-page-cvw desktop table-script-core url3-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box">Page Speed</th>
										<th class="half-width-box">Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="half-width-box">
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance"><?=$ps_desktop_osu3?></td>
														<td class="accessibility" style="display:none !important;"><?=$ps_accessibility_osu3?></td>
														<td class="best-practices" style="display:none !important;"><?=$ps_best_practices_osu3?></td>
														<td class="seo" style="display:none !important;"><?=$ps_seo_osu3?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td class="half-width-box">
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp"><?=$psa_fcp_d_osu3 ?></td>
														<td class="lcp"><?=$psa_lcp_d_osu3 ?></td>
														<td class="cls"><?=number_format($psa_cls_d_osu3, 2, '.', '') ?></td>
														<td class="tbt"><?=$psa_tbt_d_osu3?></td>
														<td class="si"><?=$psa_si_d_osu3?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Mobile Data Ends Here -->


							<span class="refresh-nospeedy-parent" <?php if ( $ps_performance_m_osu3 > 0 && $ps_performance_osu3 > 0 ) { echo "style='display:none;'" ; } ?> >
								<p>There was an issue in fetching your Website Speed</p>
								<button type="button" class="btn btn-primary refresh-nospeedy-url" table="url3-old-speed-table" website="<?=$website_data["id"]?>" additional="<?=$urlId3?>" url="<?=$url3?>" title="Get old speed records" >
									<svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg>
									Retry Now
								</button>
							</span>

						</div>
					</div>
					<p>To verify this data, you can visit <a href="//pagespeed.web.dev/" target="_blank">//pagespeed.web.dev/.</a> There may be slight variations due to multiple factors, as explained by Google. Some of the significant factors include: Antivirus software, Browser extensions that inject JavaScript and alter network requests, Network firewalls, Server load, and DNS - Internet traffic routing changes. For more detailed information provided by Google, you can <a href="//developer.chrome.com/docs/lighthouse/performance/performance-scoring/#fluctuations" target="_blank" >click here</a>.</p>	

				</div>
				<!-- //123-end -->
					<?php 
					$check_first_speedBtnNone ='';
					$check_first_speedBtnNone ='none';
					if(isset($check_first_speed) && $check_first_speed=='checkedspeed'){
						$check_first_speedBtnNone= 'none';
						$showBtn = 'block';
					}else{
						$check_first_speedBtnNone= 'block';
						$showBtn='none';
					} ?>


				<div class="action">
				<!-- <button class="continue-to-step-2">Confirm and Generate Script</button> -->
				<!-- <button type="button"  class="goto-next-step" id="boostBtn" data-satisfy="1" data-plaform="0" data-next="2" >Boost Speed Now</button> -->

				<!-- class="goto-next-step" -->
				<button type="button" <?=$disabled?>  <?=$disabledurl2?> style="display:<?=$check_first_speedBtnNone?>" id="boostBtn" class="goto-next-step" data-satisfy="1" data-plaform="0" data-next="2" >Boost Speed Now</button>

				<button type="button" <?=$disabled?> <?=$disabledurl2?> style="display:<?=$check_first_speedBtnNone?>" id="continueBtn" class="checkCurrentSpeedFirst" >Check Current Speed First</button>
				<button type="button"  style="display:<?=$showBtn?>"  id="confAndGoto2" class="confAndGoto2 goto-next-step" data-satisfy="1" data-plaform="0" data-next="2"  >Confirm and go to step 2</button>
				<!-- <button type="button" id="continueBtn" class="continue-to-step-2" urls="<?=$url?>"  data-id="<?=$website_data['id']?>" data-vari="<?=$website_data['website_url']?>" >Confirm and go to step 2</button> -->
				
				<!-- <button class="reanalyzeold" <?php if($installation >=4){}else{echo "style='display:none;'";}?> >Reanalyze</button>
				<button  <?php if($installation <4){}else{echo "style='display:none;'";}?>  class="step-2-reanalyse reanalyze-btn-"  data-website_name="<?=$website_data["website_url"]?>"  data-table_id="old" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$website_data["website_url"]?>" data-ps_desktop="-" data-additional="0"  >Reanalyze</button> -->
			</div>
		</div>
	</div>
	<!--  END Step 1 -->


	<!--  Start Step 2 -->

	<div id="step2" class=" <?php if($installation >=2){echo "complited";}else{echo " d-none ";}?> form-tab">
		<div class="tabs-2-head tabs-head" tab="tabs-2">
			<span class="number" >2</span>
			<span class="text">Install Speed Enhancement Script</span>
			<span class="icon"><i class="las la-angle-down"></i></span>
		</div>
		<div class="tabs-2 form-tab-" style="<?php if($installation != 2 ){echo "display:none";}?>">
			<div class="script_varify_s">
				<div class="head_copy_copied" title="Copy">
					<h5>Add this script code, before closing the '&lt;/head&gt;' tag in your website header.</h5>
					<span id="copiedText" style="visibility:hidden;" >Copied</span>	
					<div class="copy_copied">
						<div style="text-align: right;">
							<span id="clic_kbtn">
							<span class="doc__file__icon"></span>
							<span class="doc__file__icon"></span>
							</span>
						</div>
						<!-- <div style="text-align:right;"> 
							<span style="display:none; text-align:right; color: green;" id="show_text">copied</span>
						</div> -->
					</div>
				</div>

				<div title="Click to Copy" class="script-code" id="script_code">
					<code>&lt;!-- This Script is the exclusive property of Website Speedy, Copyright © 2023. All rights reserved. --&gt;</code><br>
					<?php
						$script_url=[];  
						foreach ($urlLists as $urlList) { 
							array_push($script_url, $urlList);
							?>
							<code>&lt;script <?php 	echo "type='text/javascript'"; ?> src="<?php echo $domain_url . $urlList ?>"&gt;&lt;/script&gt;</code>
							<br>
							<?php 
							$count++;
						}
					?>
					<code>&lt;!-- This Script is the exclusive property of Website Speedy, Copyright © 2023. All rights reserved. --&gt;</code>
				</div>

				<!-- <p class="small">Our Script Boosts the speed of Whole website i.e all pages on (<?=$website_data["website_url"]?>),Please click the button Verification below once you have added the script to the header section your website.</p> -->
				<p class="small">Please add the scripts above in the header section of your website as per the <a href="<?=$website_instructions?>" target="_blank">instructions</a> below, Please Click the Verification button below once you have added the script.</p>

				<div class="verification_cover">
					<button type="button" class="verification_btn btn btn-primary" urls="<?=$url?>"  data-id="<?=$website_data['id']?>" data-vari="<?=$website_data['website_url']?>" >Verify and go to step 3</button>

					<button class="p-instruction"><?=$website_instructions_label?></button>
					<button class="i-need-help">I need help to install the script</button>

				</div>
			</div>
		</div>
	</div>
	<!--  END Step 2 -->



	<!--  Start Step 3 -->	
	<div id="step3" class="form-tab  <?php if($installation >=3){echo "complited";}else{echo " d-none ";}?>">
		<div class="tabs-3-head tabs-head" tab="tabs-3">
			<span class="number" >3</span>
			<span class="text" >Check updated Speed Score</span>
			<!-- <span>URL 1- Homepage</span>  -->
			<!-- //123 -->
		
			<span class="icon"><i class="las la-angle-down"></i></span>
		</div>

		<div class="tabs-3 form-tab-"  style="<?php if($installation != 3){echo "display:none";}?>">

			<p>Congratulations, You have successfully implemented the script for Automatic speed optimisation. our script works instantly, it’s still a good idea to give it 30 seconds to work efficiently, You can analyse updated speed score when the timer reaches 00:00.
			</p>
			<div class="countdown-container" style="display:block">
				<span id="countdown-" class="">00:00</span>	
			</div>
				
			<!-- <div class="new-analysee" style="display:none;"> -->
				<div class="get-updated-script new-analysee">
					<!-- <span><b>URL 1- Homepage </b></span>
					<a href="<?=$website_data["website_url"]?>" target="_blank" >(<?=$website_data["website_url"]?>)</a> -->
						<!-- //123 -->
					<!-- <span><b> <span>URL 1- Homepage </span> &nbsp;<span><b><a  style="pointer-events: none; " href="<?=$website_data["website_url"]?>" target="_blank" disabled ><?=$website_data["website_url"]?></a> </b></span> </b></span> -->
					<!-- <span id="url2AddedInThirdTab"><b> <span>URL 2- </span> &nbsp;<span><b><a  style="pointer-events: none; " href="<?=$url2?>" target="_blank" disabled ><?=$url2?></a> </b></span> </b></span> -->
					<!-- <span id="url3AddedInThirdTab"><b> <span>URL 3- </span> &nbsp;<span><b><a  style="pointer-events: none; " href="<?=$url3?>" target="_blank" disabled ><?=$url3?></a> </b></span> </b></span> -->
					<br>
					<!-- <button type="button" name="submit__btn" class="new-analyze btn btn-primary reanalyze-btn-" style="display:;" tab="#step3" data-website_name="<?=$website_data["website_url"]?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$website_data["website_url"]?>"data-table_id="new" >Analyse Updated Speed</button> -->

					<!-- <button type="button" name="submit__btn" class="new-analyze btn btn-primary  addedvalueincstattr url2reanalyze-btn-"  tab="#step4" data-website_name="<?=$url2?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$url2?>"data-table_id="newUrl2" >Analyse Updated Speed</button> -->

					<!-- <button type="button" name="submit__btn" class="additional-reanalyse-updated-speed additional-reanalyse2-speed" data-website_name="<?=$url2?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$url2?>" data-additional-id="<?=$urlId2?>" data-table_id="newUrl2" data-boosted="url2_inst_speed_new" data-oldy="url2_inst_speed" data-container="url2_inst_speed_new-container" >Analyse URL2 Updated Speed</button> -->
					<!-- ////////////////////end -->
					<!-- ////// -->
					<!-- <button type="button" name="submit__btn" class="additional-reanalyse-updated-speed additional-reanalyse3-speed" data-website_name="<?=$url3?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$url3?>" data-additional-id="<?=$urlId3?>" data-table_id="newUrl3" data-boosted="url3_inst_speed_new" data-oldy="url3_inst_speed" data-container="url3_inst_speed_new-container" >Analyse URL3 Updated Speed</button> -->

					<!-- ///end -->


					<!-- //123 -->
					<button type="button" name="submit__btn" class="new-analyze-speed-for-3url btn btn-primary new-analyse"  tab="#step3" data-website_url1_name="<?=$website_data["website_url"]?>" data-website_url1_id="<?=$website_data["id"]?>" data-website_url1="<?=$website_data["website_url"]?>"data-table_url_id="new"  data-website__url2_name="<?=$url2?>" data-website_url2_id="<?=$urlId2?>" data-website_url2="<?=$url2?>" data-table_url2_id="newUrl2" data-website_url3_name="<?=$url3?>" data-website_url3_id="<?=$urlId3?>" data-website_url3="<?=$url3?>" data-table_url2_id="newUrl3" data-container="inst_speed_new-container"  data-boosted="url_inst_speed_new" data-boosted2="url2_inst_speed_new" data-boosted3="url3_inst_speed_new" data-table_url3_id="newUrl3" data-oldy="url_inst_speed"   >Analyse Updated Speed</button>

				</div>
			<!-- </div> -->

			<!-- if 1 or 2 pages got more than old score but less than 5 points -->
			<?php
				$request_manual_audit = $request_score_difference = 0 ;
				
				$query7 = $conn->query(" SELECT id , website_id , score_difference , request_manual_audit FROM `website_improve_needed` WHERE website_id = '".$website_data["id"]."' ; ") ;
				
				if ( $query7->num_rows > 0 ) {
				
					$win_data = $query7->fetch_assoc() ;

					// print_r($win_data) ;
				
					$request_manual_audit = ($win_data["request_manual_audit"] == 1) ? 1 : 0 ;
					$request_score_difference = $win_data["score_difference"] ;
				}

				$show_step2_tables = $tpr_rows = 0 ;
				$aw_query = $conn->query(" SELECT id , website_id FROM `additional_websites` WHERE `website_id` = '$project_id' ; ") ;
				if ( $aw_query->num_rows > 0 && $aw_query->num_rows >= 2 ) {

					$nspr_rows = $wspr_rows = 0 ;

					$nspr_query = $conn->query(" SELECT id , website_id , parent_website FROM `pagespeed_report` WHERE (`website_id` = '$project_id' OR parent_website = '$project_id') AND no_speedy = 1 ORDER BY `id`  DESC; ") ;

					if ( $nspr_query->num_rows > 0 ) {
						$nspr_rows = $nspr_query->num_rows ;
					}

					$wspr_query = $conn->query(" SELECT id , website_id , parent_website FROM `pagespeed_report` WHERE (`website_id` = '$project_id' OR parent_website = '$project_id') AND no_speedy = 0 ORDER BY `id`  DESC; ") ;

					if ( $wspr_query->num_rows > 0 ) {
						$wspr_rows = $wspr_query->num_rows ;
					}

					$tpr_rows = $nspr_rows + $wspr_rows ;
					// echo "nspr_rows : ".$nspr_rows."<br>" ;
					// echo "wspr_rows : ".$wspr_rows."<br>" ;

					// if ( $tpr_rows >= 6 ) {}
					if ( $nspr_rows >= 2 && $wspr_rows >= 1 ) {
						$show_step2_tables = 1 ;
					}

					
				}

				// echo "show_step2_tables : ".$show_step2_tables ;

			?>

			<div class="popup-section-manual-audit" <?php if ( $request_manual_audit == 0 || ( $request_manual_audit == 1 && $request_score_difference != 'All' ) || ($show_step2_tables == 0) ) { echo "style='display:none;'" ; } ?> >
				<h5 style="font-size:18px;">It looks like there was one of the following issues -</h5>
				<ol class="pp_list">
					<li>Code was not installed correctly All URLs.</li>
					<li>Your website doesn’t support latest performance optimisation standards.</li>
					<li>Issue with google speed insights API .</li>
				</ol>
				<h5 style="margin-top:20px; font-size:18px;">We suggest you do the following -</h5>
				<ol class="pp_list">
					<li>Remove the code from your website.</li>
					<li>Request installation by our team. Our team will contact you to get access to your website and will assist you installing website speedy correctly.</li>
					<li>Issue with google speed insights API .</li>
				</ol>
				<button type="button" class="btn btn-primary Request_ibt" onclick="popupManualAudit();" style="display: inline-block;">Request installation By team</button>
				<button type="button" class="btn btn-primary Request_ibt" onclick="$('.new-analyze-speed-for-3url').click();" style="display: inline-block;">Reanalyse Speed Again</button>
			</div>

			<div class="url-speed-tables" <?php if ( ($request_manual_audit == 1 && $request_score_difference == 'All') || ($show_step2_tables == 0) ) { echo "style='display:none;'" ; } ?> >

				<!-- style="<?php if($new_speed == 0 ){echo "display:none";}?>" -->
				<div class="inst_speed_new-container"  >
					<div style="display: none !important;">
						<!--url_inst_speed_new for-url1-count1  -->
						<div class="url_inst_speed_new">
							<p class="heading" >Boosted Speed Score First Time For URL1 :</p>						
							<textarea class="mobileFirstTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopFirstTimeForUrl1" cols="30" rows="10"></textarea>					
						</div>
						<!--url_inst_speed_new for-url1-count1-end  -->

						<!--url_inst_speed_new for-url1-count2  -->
						<div class="url_inst_speed_new">
							<p class="heading" >Boosted Speed Score Second Time For URL1:</p>				
							<textarea class="mobileSecondTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopSecondTimeForUrl1" cols="30" rows="10"></textarea>	
						</div>
						<!--url_inst_speed_new for-url1-count2-end  -->

						<!--url_inst_speed_new for-url1-count3  -->
						<div class="url_inst_speed_new">
							<p class="heading" >Boosted Speed Score Third Time For URL1:</p>						
							<textarea class="mobileThirdTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopThirdTimeForUrl1" cols="30" rows="10"></textarea>	
						</div>
						<!--url_inst_speed_new for-url1-count3-end  -->

						
						<!--url2_inst_speed_new for-url1-count1  -->
						<div class="url2_inst_speed_new">
							<p class="heading" >Boosted Speed Score First Time For URL2 :</p>
							<textarea class="mobileFirstTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopFirstTimeForUrl1" cols="30" rows="10"></textarea>	
						
						</div>
						<!--url2_inst_speed_new for-url1-count1-end  -->

						<!--url2_inst_speed_new for-url1-count2  -->
						<div class="url2_inst_speed_new">
							<p class="heading" >Boosted Speed Score Second Time For URL2:</p>
							<textarea class="mobileSecondTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopSecondTimeForUrl1" cols="30" rows="10"></textarea>		
						
						</div>
						<!--url2_inst_speed_new for-url1-count2-end  -->

						<!--url2_inst_speed_new for-url1-count3  -->
						<div class="url2_inst_speed_new">
							<p class="heading" >Boosted Speed Score Third Time For URL2:</p>					
							<textarea class="mobileThirdTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopThirdTimeForUrl1" cols="30" rows="10"></textarea>
						</div>
						<!--url2_inst_speed_new for-url1-count3-end  -->

						<!--url3_inst_speed_new for-url1-count1  -->
						<div class="url3_inst_speed_new">
							<p class="heading" >Boosted Speed Score First Time For URL3 :</p>
							<textarea class="mobileFirstTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopFirstTimeForUrl1" cols="30" rows="10"></textarea>									
						</div>
						<!--url3_inst_speed_new for-url1-count1-end  -->

						<!--url3_inst_speed_new for-url1-count2  -->
						<div class="url3_inst_speed_new">
							<p class="heading" >Boosted Speed Score Second Time For URL3:</p>
							<textarea class="mobileSecondTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopSecondTimeForUrl1" cols="30" rows="10"></textarea>						
						</div>
						<!--url3_inst_speed_new for-url1-count2-end  -->

						<!--url3_inst_speed_new for-url1-count3  -->
						<div class="url3_inst_speed_new">
							<p class="heading" >Boosted Speed Score Third Time For URL3:</p>
							<textarea class="mobileThirdTimeForUrl1" cols="30" rows="10"></textarea>	
							<textarea class="desktopThirdTimeForUrl1" cols="30" rows="10"></textarea>					
						</div>
						<!--url3_inst_speed_new for-url1-count3-end  -->
					</div>

					<span class="text" >Congrats you can see the updated speed score below - </span>
					<div class="mobile-desktop-tabber">
						<div class="page_web_speed desk_mb_speed">
							<a class="btn btn-primary page-speed1"> <i class="fa fa-mobile" aria-hidden="true"></i> Mobile</a>
							<a class="btn btn-light core-web-vital2"><i class="fa fa-desktop" aria-hidden="true"></i> Desktop</a>
						</div>
						<!-- <div class="website_url">
							<div class="website_heading">Website:</div>
							<div class="website_value"><?=$website_data['website_url']?></div>
							</div> -->
						<!-- //123 -->
						<!-- <p class="text" style="text-align:center">Boosted Speed Score With website speedy:</p> -->
					</div>
				</div>

				<!-- Speed URL1 Score -->
				<?php

					$list_popup_urls = '' ;

					// $query = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '$project_id'  and requestedUrl = '$websiteUrl' ORDER BY id DESC LIMIT 1 ");	
						// echo "SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits , requestedUrl FROM pagespeed_report WHERE website_id = '$project_ids' AND requestedUrl LIKE '$websiteUrl%' AND parent_website = 0 ORDER BY id DESC LIMIT 1 "; die;

					$query = $conn->query(" SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits , requestedUrl , blank_record , ws_status FROM pagespeed_report WHERE website_id = '$project_ids' AND requestedUrl LIKE '$websiteUrl%' AND parent_website = 0 AND no_speedy = 0 AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ; ");
					

					if ( $query->num_rows > 0 ) {

						$pagespeed_data = $query->fetch_assoc();
						
						
						$ps_categories_d = unserialize($pagespeed_data["categories"]);
						$audits_d = unserialize($pagespeed_data["audits"]);
						// echo 	$pagespeed_data["id"];

						$ps_performance = number_format($ps_categories_d["performance"]["score"] * 100, 2, '.', '');


						$ps_desktop =(int)$ps_performance . "/100";
						$ps_accessibility = number_format($ps_categories_d["accessibility"]["score"] * 100, 2, '.', '');
						$ps_best_practices = number_format($ps_categories_d["best-practices"]["score"] * 100, 2, '.', '');
						$ps_seo = number_format($ps_categories_d["seo"]["score"] * 100, 2, '.', '');
						$ps_pwa = number_format($ps_categories_d["pwa"]["score"] * 100, 2, '.', '');

						$psa_fcp_d = number_format($audits_d["first-contentful-paint"]["numericValue"], 2, '.', '');
						$psa_lcp_d = number_format($audits_d["largest-contentful-paint"]["numericValue"], 2, '.', '');
						$psa_cls_d = number_format($audits_d["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
						$psa_tbt_d = number_format($audits_d["total-blocking-time"]["numericValue"], 2, '.', '');
						$psa_si_d = $audits_d["speed-index"]["displayValue"] ;

						// ==================================================================

						$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
						$audits_m = unserialize($pagespeed_data["mobile_audits"]);
						

						$ps_performance_m = number_format($ps_categories_m["performance"]["score"] * 100, 2, '.', '');

						$ps_mobile = (int)$ps_performance_m . "/100";
						$ps_current_mobile = $ps_performance_m;
						$ps_accessibility_m = number_format($ps_categories_m["accessibility"]["score"] * 100, 2, '.', '');
						$ps_best_practices_m = number_format($ps_categories_m["best-practices"]["score"] * 100, 2, '.', '');
						$ps_seo_m = number_format($ps_categories_m["seo"]["score"] * 100, 2, '.', '');
						$ps_pwa_m = number_format($ps_categories_m["pwa"]["score"] * 100, 2, '.', '');

						$psa_fcp_m = number_format($audits_m["first-contentful-paint"]["numericValue"], 2, '.', '') ;
						$psa_lcp_m = number_format($audits_m["largest-contentful-paint"]["numericValue"], 2, '.', '') ;
						$psa_cls_m = number_format($audits_m["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
						$psa_tbt_m = number_format($audits_m["total-blocking-time"]["numericValue"], 2, '.', '') ;
						$psa_si_m = $audits_m["speed-index"]["displayValue"] ;

						// ==================================================================

						// print_r($pagespeed_data) ;

						$blank_record = $pagespeed_data["blank_record"] ;
						$ws_status = $pagespeed_data["ws_status"] ;

						$ps_performance_m_val = $ps_performance_m ;
						$ps_performance_val = $ps_performance ;
						if ( $ws_status == "popup" ) {

							if ( $blank_record == "both" ) {
								$ps_performance_m = "" ;
								$ps_performance = "" ;


								if ( $ps_performance_val <= 85 && $ps_performance_m_val <= 70 ) {
									$list_popup_urls .= "<li>URL 1 - ".$website_data["website_url"]." for Mobile / Desktop</li>" ;
								}
								elseif ( $ps_performance_val <= 85 ) {
									$list_popup_urls .= "<li>URL 1 - ".$website_data["website_url"]." for Desktop</li>" ;
								}
								elseif ( $ps_performance_m_val <= 70 ) {
									$list_popup_urls .= "<li>URL 1 - ".$website_data["website_url"]." for Mobile</li>" ;
								}

								
							}

							if ( $blank_record == "mobile" ) {
								$ps_performance_m = "" ;
								if ( $ps_performance_m_val <= 70 ) {
									$list_popup_urls .= "<li>URL 1 - ".$website_data["website_url"]." for Mobile</li>" ;
								}
							}

							if ( $blank_record == "desktop" ) {
								$ps_performance = "" ;
								if ( $ps_performance_val <= 85 ) {
									$list_popup_urls .= "<li>URL 1 - ".$website_data["website_url"]." for Desktop</li>" ;
								}
							}	

													
						}


						$hide_url1 = 1 ;

					}
					else {

						$ps_performance = $ps_performance_m = $ps_performance_val = $ps_performance_m_val = 0 ;
						// $ps_mobile = $ps_desktop = "0/100" ;

						$ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $psa_fcp_d = $psa_lcp_d = $psa_cls_d = $psa_tbt_d = "" ;

						$ps_mobile = $ps_accessibility_m = $ps_best_practices_m = $ps_seo_m = $ps_pwa_m = $psa_fcp_m = $psa_lcp_m = $psa_cls_m = $psa_tbt_m = "" ;

						$blank_record = "both" ;
						$ws_status = "popup";
						$list_popup_urls .= "<li>URL 1 - ".$website_data["website_url"]." for Mobile / Desktop</li>" ;

						$hide_url1 = 0 ;
						$psa_si_d = $psa_si_m = 0 ;
					
					}

				?>
				<!-- style="<?php if($new_speed == 0 || $hide_url1 == 0 ){echo "display:none";}?>" data-hide_url1="<?=$hide_url1?>" -->
				<div class="inst_speed_new-container speed_con"  >
					<!-- || $ws_status == "nonew"  -->
					<div class="url_inst_speed_new one url1-newspeed-container <?php if ( $ws_status == "popup" && in_array($blank_record, array("both","mobile")) ){ echo "url-old-speed-score"; } ?> " data-ws_status="<?=$ws_status?>" data-blank_record="<?=$blank_record?>">
						<span class="heading " >Boosted Speed Score URL1 <a style="pointer-events: none; " href="<?=$website_data["website_url"]?>" class="url1-table-head" target="_blank" disabled ><?=$website_data["website_url"]?></a><br>With website speedy:</span>
						<!-- <div class="page_web_speed">
							<a class="btn btn-primary page-speed1">Page Speed</a>
							<a class="btn btn-light core-web-vital2">Core Web Vital</a>
						</div> -->


						<div class="page-cvw-box">
							<!-- Desktop Data Starts Here -->
							<input type="hidden" id="ws-mobile-speed" value="<?=$ps_performance_m?>" data-value="<?=$ps_performance_m_val?>">
							<input type="hidden" id="ws-desktop-speed" value="<?=$ps_performance?>" data-value="<?=$ps_performance_val?>">

							<table class="table main-table-page-cvw desktop table-script-core url1-new-speed-table" <?php //if ( !empty($blank_record) && ($blank_record == "both" || $blank_record == "desktop" ) ) { echo "style='display:none;'" ; } ?> >
								<thead>
									<tr>
										<th>Page Speed</th>
										<th>Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr class="url1-data-tr desktop" <?php if ( $ws_status == "popup" && in_array($blank_record, array("both","desktop")) ){ echo "style='display:none;'"; } ?> >
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance"><?=$ps_desktop?></td>
														<td class="accessibility" style="display:none !important;"><?=$ps_accessibility?></td>
														<td class="best-practices" style="display:none !important;"><?=$ps_best_practices?></td>
														<td class="seo" style="display:none !important;"><?=$ps_seo?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp"><?=$psa_fcp_d ?></td>
														<td class="lcp"><?=$psa_lcp_d ?></td>
														<td class="cls"><?=empty($psa_cls_d)?$psa_cls_d:number_format($psa_cls_d, 2, '.', '') ?></td>
														<td class="tbt"><?=$psa_tbt_d?></td>
														<td class="si"><?=$psa_si_d?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr class="url1-popup-tr desktop" <?php if ( $ws_status != "popup" || !in_array($blank_record, array("both","desktop")) ){ echo "style='display:none;'"; } ?> >
										<td colspan="2"><p class="nospeed-text">We were not able to fetch updated speed, this might be due to a technical error or a network issue, you can generate request for Manual Audit by clicking "optimize for more speed" button at end of step 3. Please check the updated speed for other pages in the meantime.</p></td>
									</tr>
								</tbody>
							</table>
							<!-- Desktop Data Ends Here -->

							<!-- Mobile Data Starts Here -->
							<table class="table main-table-page-cvw mobile table-script-speed url1-new-speed-table" <?php //if ( !empty($blank_record) && ($blank_record == "both" || $blank_record == "mobile" ) ) { echo "style='display:none;'" ; } ?> >
								<thead>
									<tr>
										<th>Page Speed</th>
										<th>Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr class="url1-data-tr mobile" <?php if ( $ws_status == "popup" && in_array($blank_record, array("both","mobile")) ){ echo "style='display:none;'"; } ?>>
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance_m"><?=$ps_mobile?></td>
														<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m?></td>
														<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m?></td>
														<td class="seo_m" style="display:none !important;"><?=$ps_seo_m?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp_m"><?=$psa_fcp_m ?></td>
														<td class="lcp_m"><?=$psa_lcp_m ?></td>
														<td class="cls_m"><?=empty($psa_cls_m)?$psa_cls_m:number_format($psa_cls_m, 2, '.', '') ?></td>
														<td class="tbt_m"><?=$psa_tbt_m?></td>
														<td class="si_m"><?=$psa_si_m?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr class="url1-popup-tr mobile" <?php if ( $ws_status != "popup" || !in_array($blank_record, array("both","mobile"))  ){ echo "style='display:none;'"; } ?> >
										<td colspan="2"><p class="nospeed-text">We were not able to fetch updated speed, this might be due to a technical error or a network issue, you can generate request for Manual Audit by clicking "optimize for more speed" button at end of step 3. Please check the updated speed for other pages in the meantime.</p></td>
									</tr>
								</tbody>
							</table>
							<!-- Mobile Data Ends Here -->
						</div>
					</div>

					<div class="url_inst_speed_new" style="">
						<span class="heading" >Old Speed URL1 <a  style="pointer-events: none; " href="<?=$website_data["website_url"]?>" class="url1-table-head" target="_blank" disabled ><?=$website_data["website_url"]?></a><br>Without website Speedy:</span>
						<!-- <div class="page_web_speed">
							<a class="btn btn-primary page-speed1">Page Speed</a>
							<a class="btn btn-light core-web-vital2">Core Web Vital</a>
						</div> -->
						
						<div class="page-cvw-box">
							
							<!-- Desktop Data Starts Here -->
							<input type="hidden" id="wos-mobile-speed" value="<?=$ps_performance_m_osu1?>">
							<input type="hidden" id="wos-desktop-speed" value="<?=$ps_performance_osu1?>">



							<table class="table main-table-page-cvw desktop table-script-core url1-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box" >Page Speed</th>
										<th class="half-width-box" >Core Web Vital </th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance"><?=$ps_desktop_osu1?></td>
														<td class="accessibility" style="display:none !important;"><?=$ps_accessibility_osu1?></td>
														<td class="best-practices" style="display:none !important;"><?=$ps_best_practices_osu1?></td>
														<td class="seo" style="display:none !important;"><?=$ps_seo_osu1?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp"><?=$psa_fcp_d_osu1 ?></td>
														<td class="lcp"><?=$psa_lcp_d_osu1 ?></td>
														<td class="cls"><?=empty($psa_cls_d_osu1)?$psa_cls_d_osu1:number_format($psa_cls_d_osu1, 2, '.', '') ?></td>
														<td class="tbt"><?=$psa_tbt_d_osu1?></td>
														<td class="si"><?=$psa_si_d_osu1?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Desktop Data Ends Here -->

							<!-- Mobile Data Starts Here -->
							<table class="table main-table-page-cvw mobile table-script-speed url1-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box">Page Speed</th>
										<th class="half-width-box">Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance_m"><?=$ps_mobile_osu1?></td>
														<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m_osu1?></td>
														<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m_osu1?></td>
														<td class="seo_m" style="display:none !important;"><?=$ps_seo_m_osu1?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp_m"><?=$psa_fcp_m_osu1 ?></td>
														<td class="lcp_m"><?=$psa_lcp_m_osu1 ?></td>
														<td class="cls_m"><?=empty($psa_cls_m_osu1)?$psa_cls_m_osu1:number_format($psa_cls_m_osu1, 2, '.', '') ?></td>
														<td class="tbt_m"><?=$psa_tbt_m_osu1?></td>
														<td class="si_m"><?=$psa_si_m_osu1?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Mobile Data Ends Here -->

							<span class="refresh-nospeedy-parent" <?php if ( $ps_performance_m_osu1 > 0 && $ps_performance_osu1 > 0 ) { echo "style='display:none;'" ; } ?> >
								<p>There was an issue in fetching your Website Speed</p>
								<button type="button" class="btn btn-primary refresh-nospeedy-url" table="url1-old-speed-table" website="<?=$website_data["id"]?>" additional="0" url="<?=$website_data["website_url"]?>" title="Refresh old speed records" >
									<svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg>
									Retry Now
								</button>
							</span>
						</div>
					</div>

				</div>

				<!-- Speed URL2 Score -->
				<?php

					if ( empty($urlId2) ) {

						$blank_record = "both" ;
						$ws_status = "popup";
						$list_popup_urls .= "<li>URL 2 - ".$url2." for Mobile / Desktop</li>" ;

						$ps_performance = $ps_performance_m = $ps_performance_val = $ps_performance_m_val = 0 ;
						// $ps_mobile = $ps_desktop = "0/100" ;

						$ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $psa_fcp_d = $psa_lcp_d = $psa_cls_d = $psa_tbt_d = "" ;

						$ps_mobile = $ps_accessibility_m = $ps_best_practices_m = $ps_seo_m = $ps_pwa_m = $psa_fcp_m = $psa_lcp_m = $psa_cls_m = $psa_tbt_m = "" ;

						$hide_url2 = 0 ;
						$psa_si_d = $psa_si_m = 0 ;


					}
					else {

						// $query = $conn->query("SELECT * FROM pagespeed_report WHERE website_id = '$project_id' and requestedUrl = '$url2' ORDER BY id DESC LIMIT 1 ");
						// echo	$sql = "SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits , requestedUrl FROM pagespeed_report WHERE website_id = '$urlId2' AND parent_website = '$project_id' AND requestedUrl LIKE '$url2%' ORDER BY id DESC LIMIT 1";

						// $query = $conn->query(" SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits , requestedUrl FROM pagespeed_report WHERE website_id = '$urlId2' AND parent_website = '$project_id' AND requestedUrl LIKE '$url2%' ORDER BY id DESC LIMIT 1 ");

						$query = $conn->query(" SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits , requestedUrl , blank_record ,ws_status FROM pagespeed_report WHERE website_id = '$urlId2' AND parent_website = '$project_id' AND no_speedy = 0 AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ");

						if ( $query->num_rows > 0 ) {

							$pagespeed_data = $query->fetch_assoc();
							
							
							$ps_categories_d = unserialize($pagespeed_data["categories"]);
							$audits_d = unserialize($pagespeed_data["audits"]);
							// echo 	$pagespeed_data["id"];

							$ps_performance = number_format($ps_categories_d["performance"]["score"] * 100, 2, '.', '');
							$ps_desktop = (int)$ps_performance . "/100";
							$ps_accessibility = number_format($ps_categories_d["accessibility"]["score"] * 100, 2, '.', '');
							$ps_best_practices = number_format($ps_categories_d["best-practices"]["score"] * 100, 2, '.', '');
							$ps_seo = number_format($ps_categories_d["seo"]["score"] * 100, 2, '.', '');
							$ps_pwa = number_format($ps_categories_d["pwa"]["score"] * 100, 2, '.', '');

							$psa_fcp_d = number_format($audits_d["first-contentful-paint"]["numericValue"],2, '.', '') ;
							$psa_lcp_d = number_format($audits_d["largest-contentful-paint"]["numericValue"],2, '.', '') ;
							$psa_cls_d = number_format($audits_d["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
							$psa_tbt_d = number_format($audits_d["total-blocking-time"]["numericValue"],2, '.', '') ;
							
							// ==================================================================

							$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
							$audits_m = unserialize($pagespeed_data["mobile_audits"]);
							

							$ps_performance_m = number_format($ps_categories_m["performance"]["score"] * 100, 2, '.', '');
							$ps_mobile = (int)$ps_performance_m . "/100";
							$url2currentmscore =  $ps_performance_m; //123
							$ps_accessibility_m = number_format($ps_categories_m["accessibility"]["score"] * 100, 2, '.', '');
							$ps_best_practices_m = number_format($ps_categories_m["best-practices"]["score"] * 100, 2, '.', '');
							$ps_seo_m = number_format($ps_categories_m["seo"]["score"] * 100, 2, '.', '');
							$ps_pwa_m = number_format($ps_categories_m["pwa"]["score"] * 100, 2, '.', '');

							$psa_fcp_m = number_format($audits_m["first-contentful-paint"]["numericValue"],2, '.', '') ;
							$psa_lcp_m = number_format($audits_m["largest-contentful-paint"]["numericValue"],2, '.', '') ;
							$psa_cls_m = number_format($audits_m["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
							$psa_tbt_m = number_format($audits_m["total-blocking-time"]["numericValue"],2, '.', '') ;

							// ==================================================================
							$blank_record = $pagespeed_data["blank_record"] ;
							$ws_status = $pagespeed_data["ws_status"] ;
							$hide_url2 = 1 ;

							$psa_si_d = $audits_d["speed-index"]["displayValue"] ;
							$psa_si_m = $audits_m["speed-index"]["displayValue"] ;

							$ps_performance_m_val = $ps_performance_m ;
							$ps_performance_val = $ps_performance ;

							if ( $ws_status == "popup" ) {
								// if ( $blank_record == "mobile" ) {
								// 	$ps_performance_m = "" ;
								// }

								// if ( $blank_record == "desktop" ) {
								// 	$ps_performance = "" ;
								// }	

								if ( $blank_record == "both" ) {
									$ps_performance_m = "" ;
									$ps_performance = "" ;
									// $list_popup_urls .= "<li>URL 2 - ".$url2." for Mobile / Desktop</li>" ;

									if ( $ps_performance_val <= 85 && $ps_performance_m_val <= 70 ) {
										$list_popup_urls .= "<li>URL 2 - ".$url2." for Mobile / Desktop</li>" ;
									}
									elseif ( $ps_performance_val <= 85 ) {
										$list_popup_urls .= "<li>URL 2 - ".$url2." for Desktop</li>" ;
									}
									elseif ( $ps_performance_m_val <= 70 ) {
										$list_popup_urls .= "<li>URL 2 - ".$url2." for Mobile</li>" ;
									}
								}

								if ( $blank_record == "mobile" ) {
									$ps_performance_m = "" ;
									if ( $ps_performance_m_val <= 70 ) {
										$list_popup_urls .= "<li>URL 2 - ".$url2." for Mobile</li>" ;
									}
								}

								if ( $blank_record == "desktop" ) {
									$ps_performance = "" ;
									if ( $ps_performance_val <= 85 ) {
										$list_popup_urls .= "<li>URL 2 - ".$url2." for Desktop</li>" ;
									}
								}						
							}



						}
						else {

							$ps_performance = $ps_performance_m = $ps_performance_val = $ps_performance_m_val = 0 ;
							// $ps_mobile = $ps_desktop = "0/100" ;

							$ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $psa_fcp_d = $psa_lcp_d = $psa_cls_d = $psa_tbt_d = "" ;

							$ps_mobile = $ps_accessibility_m = $ps_best_practices_m = $ps_seo_m = $ps_pwa_m = $psa_fcp_m = $psa_lcp_m = $psa_cls_m = $psa_tbt_m = "" ;
							
							$blank_record = "both" ;
							$ws_status = "popup";
							$list_popup_urls .= "<li>URL 2 - ".$url2." for Mobile / Desktop</li>" ;
							
							$hide_url2 = 0 ;
							$psa_si_d = $psa_si_m = 0 ;

						}	

					}

				?>
				<!-- style="<?php if($new_speed_URL2 == 0 || $hide_url2 == 0 ){echo "display:none";}?>" data-hide_url2="<?=$hide_url2?>" -->
				<div class="inst_speed_new-container speed_con" >

					<!-- //123-url2 -->				
					<!-- //123 -->
					<!--  || $ws_status == "nonew" -->
					<div class="url2_inst_speed_new url2-newspeed-container <?php if ( $ws_status == "popup" && in_array($blank_record, array("both","mobile")) ){ echo "url-old-speed-score"; } ?> "  data-ws_status="<?=$ws_status?>" data-blank_record="<?=$blank_record?>" >
						<span class="heading" >Boosted Speed URL2 <a style="pointer-events: none;" href="<?=$url2?>" target="_blank" disabled class="url2-table-head" ><?=$url2?></a><br> Score With website speedy:</span>
						<!-- <div class="page_web_speed">
							<a class="btn btn-primary page-speed1">Page Speed</a>
							<a class="btn btn-light core-web-vital2">Core Web Vital</a>
						</div> -->
						
						<div class="page-cvw-box">
							<!-- Desktop Data Starts Here -->
							<input type="hidden" id="wsa1-mobile-speed" value="<?=$ps_performance_m?>" data-value="<?=$ps_performance_m_val?>" >
							<input type="hidden" id="wsa1-desktop-speed" value="<?=$ps_performance?>" data-value="<?=$ps_performance_val?>" >

							<table class="table main-table-page-cvw desktop table-script-core url2-new-speed-table" <?php //if ( !empty($blank_record) && ($blank_record == "both" || $blank_record == "desktop" ) ) { echo "style='display:none;'" ; } ?> >
								<thead>
									<tr>
										<th>Page Speed</th>
										<th>Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr class="url2-data-tr desktop" <?php if ( $ws_status == "popup" && in_array($blank_record, array("both","desktop")) ){ echo "style='display:none;'"; } ?>  >
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance"><?=$ps_desktop?></td>
														<td class="accessibility" style="display:none !important;"><?=$ps_accessibility?></td>
														<td class="best-practices" style="display:none !important;"><?=$ps_best_practices?></td>
														<td class="seo" style="display:none !important;"><?=$ps_seo?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp"><?=$psa_fcp_d ?></td>
														<td class="lcp"><?=$psa_lcp_d ?></td>
														<td class="cls"><?=empty($psa_cls_d)?$psa_cls_d:number_format($psa_cls_d, 2, '.', '') ?></td>
														<td class="tbt"><?=$psa_tbt_d?></td>
														<td class="si"><?=$psa_si_d?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr class="url2-popup-tr desktop" <?php if ( $ws_status != "popup" || !in_array($blank_record, array("both","desktop"))  ){ echo "style='display:none;'"; } ?> >
										<td colspan="2"><p class="nospeed-text">We were not able to fetch updated speed, this might be due to a technical error or a network issue, you can generate request for Manual Audit by clicking "optimize for more speed" button at end of step 3. Please check the updated speed for other pages in the meantime.</p></td>
									</tr>
								</tbody>
							</table>
							<!-- Desktop Data Ends Here -->

							<!-- Mobile Data Starts Here -->
							<table class="table main-table-page-cvw mobile table-script-speed url2-new-speed-table" <?php //if ( !empty($blank_record) && ($blank_record == "both" || $blank_record == "mobile" ) ) { echo "style='display:none;'" ; } ?> >
								<thead>
									<tr>
										<th>Page Speed</th>
										<th>Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr class="url2-data-tr mobile" <?php if ( $ws_status == "popup" && in_array($blank_record, array("both","mobile")) ){ echo "style='display:none;'"; } ?>>
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance_m"><?=$ps_mobile?></td>
														<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m?></td>
														<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m?></td>
														<td class="seo_m" style="display:none !important;"><?=$ps_seo_m?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp_m"><?=$psa_fcp_m ?></td>
														<td class="lcp_m"><?=$psa_lcp_m ?></td>
														<td class="cls_m"><?=empty($psa_cls_m)?$psa_cls_m:number_format($psa_cls_m, 2, '.', '') ?></td>
														<td class="tbt_m"><?=$psa_tbt_m?></td>
														<td class="si_m"><?=$psa_si_m?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr class="url2-popup-tr mobile" <?php if ( $ws_status != "popup" || !in_array($blank_record, array("both","mobile"))  ){ echo "style='display:none;'"; } ?> >
										<td colspan="2"><p class="nospeed-text">We were not able to fetch updated speed, this might be due to a technical error or a network issue, you can generate request for Manual Audit by clicking "optimize for more speed" button at end of step 3. Please check the updated speed for other pages in the meantime.</p></td>
									</tr>
								</tbody>
							</table>
							<!-- Mobile Data Ends Here -->
						</div>
					</div>

					<!-- //123 url2-->
					<div class="url2_inst_speed" style="">
						<span class="heading" >Old Speed URL2 <a style="pointer-events: none;" href="<?=$url2?>" target="_blank" disabled class="url2-table-head" ><?=$url2?></a><br> Without website Speedy: </span>
						<!-- <div class="page_web_speed">
							<a class="btn btn-primary page-speed1">Page Speed</a>
							<a class="btn btn-light core-web-vital2">Core Web Vital</a>
						</div> -->


						<div class="page-cvw-box">
							<!-- Desktop Data Starts Here -->
							<input type="hidden" id="wosa1-mobile-speed" value="<?=$ps_performance_m_osu2?>">
							<input type="hidden" id="wosa1-desktop-speed" value="<?=$ps_performance_osu2?>">
							
							<table class="table main-table-page-cvw desktop table-script-core url2-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box" >Page Speed</th>
										<th class="half-width-box" >Core Web Vital  </th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance"><?=$ps_desktop_osu2?></td>
														<td class="accessibility" style="display:none !important;"><?=$ps_accessibility_osu2?></td>
														<td class="best-practices" style="display:none !important;"><?=$ps_best_practices_osu2?></td>
														<td class="seo" style="display:none !important;"><?=$ps_seo_osu2?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp"><?=$psa_fcp_d_osu2 ?></td>
														<td class="lcp"><?=$psa_lcp_d_osu2 ?></td>
														<td class="cls"><?=empty($psa_cls_d_osu2)?$psa_cls_d_osu2:number_format($psa_cls_d_osu2, 2, '.', '') ?></td>
														<td class="tbt"><?=$psa_tbt_d_osu2?></td>
														<td class="si"><?=$psa_si_d_osu2?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Desktop Data Ends Here -->

							<!-- Mobile Data Starts Here -->
							<table class="table main-table-page-cvw mobile table-script-speed url2-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box">Page Speed</th>
										<th class="half-width-box">Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance_m"><?=$ps_mobile_osu2?></td>
														<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m_osu2?></td>
														<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m_osu2?></td>
														<td class="seo_m" style="display:none !important;"><?=$ps_seo_m_osu2?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp_m"><?=$psa_fcp_m_osu2 ?></td>
														<td class="lcp_m"><?=$psa_lcp_m_osu2 ?></td>
														<td class="cls_m"><?=empty($psa_cls_m_osu2)?$psa_cls_m_osu2:number_format($psa_cls_m_osu2, 2, '.', '') ?></td>
														<td class="tbt_m"><?=$psa_tbt_m_osu2?></td>
														<td class="si_m"><?=$psa_si_m_osu2?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Mobile Data Ends Here -->

							<span class="refresh-nospeedy-parent" <?php if ( $ps_performance_m_osu2 > 0 && $ps_performance_osu2 > 0 ) { echo "style='display:none;'" ; } ?> >
								<p>There was an issue in fetching your Website Speed</p>
								<button type="button" class="btn btn-primary refresh-nospeedy-url" table="url2-old-speed-table" website="<?=$website_data["id"]?>" additional="<?=$urlId2?>" url="<?=$url2?>" title="Refresh old speed records" >
									<svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg>
									Retry Now
								</button>
							</span>
						</div>
					</div>

				</div>
				
				<!-- Speed URL3 Score -->
				<?php

					if ( empty($urlId3) ) {

						$blank_record = "both" ;
						$ws_status = "popup";
						$list_popup_urls .= "<li>URL 3 - ".$url3." for Mobile / Desktop</li>" ;

						$ps_performance = $ps_performance_m = $ps_performance_val = $ps_performance_m_val = 0 ;
						// $ps_mobile = $ps_desktop = "0/100" ;

						$ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $psa_fcp_d = $psa_lcp_d = $psa_cls_d = $psa_tbt_d = "" ;

						$ps_mobile = $ps_accessibility_m = $ps_best_practices_m = $ps_seo_m = $ps_pwa_m = $psa_fcp_m = $psa_lcp_m = $psa_cls_m = $psa_tbt_m = "" ;

						$hide_url3 = 0 ;
						$psa_si_d = $psa_si_m = 0 ;

					}
					else {

						// $query = $conn->query("SELECT * FROM pagespeed_report WHERE website_id = '$project_id' and requestedUrl = '$url3' ORDER BY id DESC LIMIT 1 ");	

						// $query = $conn->query("SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits , requestedUrl FROM pagespeed_report WHERE website_id = '$urlId3' AND parent_website = '$project_id' AND requestedUrl LIKE '$url3%' ORDER BY id DESC LIMIT 1 ");

						$query = $conn->query("SELECT id , website_id , parent_website , categories , audits , mobile_categories , mobile_audits , requestedUrl , blank_record , ws_status FROM pagespeed_report WHERE website_id = '$urlId3' AND parent_website = '$project_id' AND no_speedy = 0 AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ");	

						if ( $query->num_rows > 0 ) {
							$pagespeed_data = $query->fetch_assoc();
							
							
							$ps_categories_d = unserialize($pagespeed_data["categories"]);
							$audits_d = unserialize($pagespeed_data["audits"]);
							// echo 	$pagespeed_data["id"];

							$ps_performance = number_format($ps_categories_d["performance"]["score"] * 100, 2, '.', '');
							$ps_desktop = (int)$ps_performance . "/100";
							$ps_accessibility = number_format($ps_categories_d["accessibility"]["score"] * 100, 2);
							$ps_best_practices = number_format($ps_categories_d["best-practices"]["score"] * 100, 2, '.', '');
							$ps_seo = number_format($ps_categories_d["seo"]["score"] * 100, 2, '.', '');
							$ps_pwa = number_format($ps_categories_d["pwa"]["score"] * 100, 2, '.', '');

							$psa_fcp_d = number_format($audits_d["first-contentful-paint"]["numericValue"],2, '.', '') ;
							$psa_lcp_d = number_format($audits_d["largest-contentful-paint"]["numericValue"],2, '.', '') ;
							$psa_cls_d = number_format($audits_d["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
							$psa_tbt_d = number_format($audits_d["total-blocking-time"]["numericValue"],2, '.', '') ;
							
							// ==================================================================

							$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
							$audits_m = unserialize($pagespeed_data["mobile_audits"]);
							

							$ps_performance_m = number_format($ps_categories_m["performance"]["score"] * 100, 2, '.', '');
							$ps_mobile =(int)$ps_performance_m . "/100";
							$url3currentmscore =$ps_performance_m; //123
							$ps_accessibility_m = number_format($ps_categories_m["accessibility"]["score"] * 100, 2, '.', '');
							$ps_best_practices_m = number_format($ps_categories_m["best-practices"]["score"] * 100, 2, '.', '');
							$ps_seo_m = number_format($ps_categories_m["seo"]["score"] * 100, 2, '.', '');
							$ps_pwa_m = number_format($ps_categories_m["pwa"]["score"] * 100, 2, '.', '');

							$psa_fcp_m = number_format($audits_m["first-contentful-paint"]["numericValue"],2, '.', '') ;
							$psa_lcp_m = number_format($audits_m["largest-contentful-paint"]["numericValue"],2, '.', '') ;
							$psa_cls_m = number_format($audits_m["cumulative-layout-shift"]["numericValue"], 2, '.', '') ;
							$psa_tbt_m = number_format($audits_m["total-blocking-time"]["numericValue"],2, '.', '') ;

							// ==================================================================
							$blank_record = $pagespeed_data["blank_record"] ;
							$ws_status = $pagespeed_data["ws_status"] ;

							$hide_url3 = 1 ;
							$psa_si_d = $audits_d["speed-index"]["displayValue"] ;
							$psa_si_m = $audits_m["speed-index"]["displayValue"] ;

							$ps_performance_m_val = $ps_performance_m ;
							$ps_performance_val = $ps_performance ;
							if ( $ws_status == "popup" ) {
								if ( $blank_record == "both" ) {
									$ps_performance_m = "" ;
									$ps_performance = "" ;
									// $list_popup_urls .= "<li>URL 3 - ".$url3." for Mobile / Desktop</li>" ;

									if ( $ps_performance_val <= 85 && $ps_performance_m_val <= 70 ) {
										$list_popup_urls .= "<li>URL 3 - ".$url3." for Mobile / Desktop</li>" ;
									}
									elseif ( $ps_performance_val <= 85 ) {
										$list_popup_urls .= "<li>URL 3 - ".$url3." for Desktop</li>" ;
									}
									elseif ( $ps_performance_m_val <= 70 ) {
										$list_popup_urls .= "<li>URL 3 - ".$url3." for Mobile</li>" ;
									}
								}

								if ( $blank_record == "mobile" ) {
									$ps_performance_m = "" ;
									if ( $ps_performance_m_val <= 70 ) {
										$list_popup_urls .= "<li>URL 3 - ".$url3." for Mobile</li>" ;
									}
								}

								if ( $blank_record == "desktop" ) {
									$ps_performance = "" ;
									if ( $ps_performance_val <= 85 ) {
										$list_popup_urls .= "<li>URL 3 - ".$url3." for Desktop</li>" ;
									}
								}
							}						
						}
						else {

							$ps_performance = $ps_performance_m = $ps_performance_val = $ps_performance_m_val = 0 ;
							// $ps_mobile = $ps_desktop = "0/100" ;

							$ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $psa_fcp_d = $psa_lcp_d = $psa_cls_d = $psa_tbt_d = "" ;

							$ps_mobile = $ps_accessibility_m = $ps_best_practices_m = $ps_seo_m = $ps_pwa_m = $psa_fcp_m = $psa_lcp_m = $psa_cls_m = $psa_tbt_m = "" ;

							$blank_record = "both" ;
							$ws_status = "popup";
							$list_popup_urls .= "<li>URL 3 - ".$url3." for Mobile / Desktop</li>" ;
							$hide_url3 = 0 ;

							$psa_si_d = $psa_si_m = 0 ;
						}	

					}

				?>
				<!-- style="<?php if($new_speed_URL3 == 0 || $hide_url3 == 0 ){echo "display:none";}?>" data-hide_url3="<?=$hide_url3?>" -->
				<div class="inst_speed_new-container speed_con  "  >
					<!-- //123 url3-->
					<!--  || $ws_status == "nonew" -->
					<div class="url3_inst_speed_new url3-newspeed-container <?php if ( $ws_status == "popup" && in_array($blank_record, array("both","mobile")) ){ echo "url-old-speed-score"; } ?> " data-ws_status="<?=$ws_status?>" data-blank_record="<?=$blank_record?>" >
						<span class="heading" >Boosted Speed URL3 <a href="<?=$url3?>" style="pointer-events: none;" target="_blank" disabled class="url3-table-head" ><?=$url3?></a><br> Score With website speedy:</span>
						<!-- <div class="page_web_speed">
							<a class="btn btn-primary page-speed1">Page Speed</a>
							<a class="btn btn-light core-web-vital2">Core Web Vital</a>
						</div> -->

						<div class="page-cvw-box">
							<!-- Desktop Data Starts Here -->
							<input type="hidden" id="wsa2-mobile-speed" value="<?=$ps_performance_m?>" data-value="<?=$ps_performance_m_val?>">
							<input type="hidden" id="wsa2-desktop-speed" value="<?=$ps_performance?>" data-value="<?=$ps_performance_val?>">
							<?php 
							// var_dump($ws_status == "popup") ;
							// var_dump(in_array($blank_record, array("both","desktop"))) ;

							// if ( $ws_status == "popup" && in_array($blank_record, array("both","desktop")) ){ echo "style='display:none;'"; } 
							?>
							<table class="table main-table-page-cvw desktop table-script-core url3-new-speed-table " <?php //if ( !empty($blank_record) && ($blank_record == "both" || $blank_record == "desktop" ) ) { echo "style='display:none;'" ; } ?> >
								<thead>
									<tr>
										<th>Page Speed</th>
										<th>Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr class="url3-data-tr desktop" <?php if ( ($ws_status == "popup") && in_array($blank_record, array("both","desktop")) ){ echo "style='display:none;'"; } ?> >
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance"><?=$ps_desktop?></td>
														<td class="accessibility" style="display:none !important;"><?=$ps_accessibility?></td>
														<td class="best-practices" style="display:none !important;"><?=$ps_best_practices?></td>
														<td class="seo" style="display:none !important;"><?=$ps_seo?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp"><?=$psa_fcp_d ?></td>
														<td class="lcp"><?=$psa_lcp_d ?></td>
														<td class="cls"><?=empty($psa_cls_d)?$psa_cls_d:number_format($psa_cls_d, 2, '.', '') ?></td>
														<td class="tbt"><?=$psa_tbt_d?></td>
														<td class="si"><?=$psa_si_d?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr class="url3-popup-tr desktop" <?php if ( $ws_status != "popup" || !in_array($blank_record, array("both","desktop"))  ){ echo "style='display:none;'"; } ?> >
										<td colspan="2"><p class="nospeed-text">We were not able to fetch updated speed, this might be due to a technical error or a network issue, you can generate request for Manual Audit by clicking "optimize for more speed" button at end of step 3. Please check the updated speed for other pages in the meantime.</p></td>
									</tr>
								</tbody>
							</table>
							<!-- Desktop Data Ends Here -->

							<!-- Mobile Data Starts Here -->
							<table class="table main-table-page-cvw mobile table-script-speed url3-new-speed-table" <?php //if ( !empty($blank_record) && ($blank_record == "both" || $blank_record == "mobile" ) ) { echo "style='display:none;'" ; } ?> >
								<thead>
									<tr>
										<th>Page Speed</th>
										<th>Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr class="url3-data-tr mobile" <?php if ( ($ws_status == "popup") && in_array($blank_record, array("both","mobile")) ){ echo "style='display:none;'"; } ?> >
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance_m"><?=$ps_mobile?></td>
														<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m?></td>
														<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m?></td>
														<td class="seo_m" style="display:none !important;"><?=$ps_seo_m?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp_m"><?=$psa_fcp_m ?></td>
														<td class="lcp_m"><?=$psa_lcp_m ?></td>
														<td class="cls_m"><?=empty($psa_cls_m)?$psa_cls_m:number_format($psa_cls_m, 2, '.', '') ?></td>
														<td class="tbt_m"><?=$psa_tbt_m?></td>
														<td class="si_m"><?=$psa_si_m?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr class="url3-popup-tr mobile" <?php if ( $ws_status != "popup" || !in_array($blank_record, array("both","mobile")) ){ echo "style='display:none;'"; } ?> >
										<td colspan="2"><p class="nospeed-text">We were not able to fetch updated speed, this might be due to a technical error or a network issue, you can generate request for Manual Audit by clicking "optimize for more speed" button at end of step 3. Please check the updated speed for other pages in the meantime.</p></td>
									</tr>
								</tbody>
							</table>
							<!-- Mobile Data Ends Here -->
						</div>
					</div>

					<!-- //123 url3 -->
					<div class="url3_inst_speed" style="">
						<span class="heading" >Old Speed URL3 <a href="<?=$url3?>" style="pointer-events: none;" target="_blank" disabled class="url3-table-head" ><?=$url3?></a><br> Without website Speedy: </span>

						<!-- <div class="page_web_speed">
							<a class="btn btn-primary page-speed1">Page Speed</a>
							<a class="btn btn-light core-web-vital2">Core Web Vital</a>	
						</div> -->

						<div class="page-cvw-box">
							<!-- Desktop Data Starts Here -->
							<input type="hidden" id="wosa2-mobile-speed" value="<?=$ps_performance_m_osu3?>">
							<input type="hidden" id="wosa2-desktop-speed" value="<?=$ps_performance_osu3?>">

							<table class="table main-table-page-cvw desktop table-script-core url3-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box" >Page Speed</th>
										<th class="half-width-box" >Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance"><?=$ps_desktop_osu3?></td>
														<td class="accessibility" style="display:none !important;"><?=$ps_accessibility_osu3?></td>
														<td class="best-practices" style="display:none !important;"><?=$ps_best_practices_osu3?></td>
														<td class="seo" style="display:none !important;"><?=$ps_seo_osu3?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp"><?=$psa_fcp_d_osu3 ?></td>
														<td class="lcp"><?=$psa_lcp_d_osu3 ?></td>
														<td class="cls"><?=empty($psa_cls_d_osu3)?$psa_cls_d_osu3:number_format($psa_cls_d_osu3, 2, '.', '') ?></td>
														<td class="tbt"><?=$psa_tbt_d_osu3?></td>
														<td class="si"><?=$psa_si_d_osu3?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Desktop Data Ends Here -->
							
							<!-- Mobile Data Starts Here -->
							<table class="table main-table-page-cvw mobile table-script-speed url3-old-speed-table">
								<thead>
									<tr>
										<th class="half-width-box">Page Speed</th>
										<th class="half-width-box">Core Web Vital</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<table>
												<thead>
													<tr>
														<th>Performance</th>
														<th style="display:none !important;">Accessibility</th>
														<th style="display:none !important;">Best Practices</th>
														<th style="display:none !important;">SEO</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="performance_m"><?=$ps_mobile_osu3?></td>
														<td class="accessibility_m" style="display:none !important;"><?=$ps_accessibility_m_osu3?></td>
														<td class="best-practices_m" style="display:none !important;"><?=$ps_best_practices_m_osu3?></td>
														<td class="seo_m" style="display:none !important;"><?=$ps_seo_m_osu3?></td>
													</tr>
												</tbody>
											</table>
										</td>
										<td>
											<table>
												<thead>
													<tr>
														<th>FCP</th>
														<th>LCP</th>
														<th>CLS</th>
														<th>TBT</th>
														<th>SI</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="fcp_m"><?=$psa_fcp_m_osu3 ?></td>
														<td class="lcp_m"><?=$psa_lcp_m_osu3 ?></td>
														<td class="cls_m"><?=empty($psa_cls_m_osu3)?$psa_cls_m_osu3:number_format($psa_cls_m_osu3, 2, '.', '') ?></td>
														<td class="tbt_m"><?=$psa_tbt_m_osu3?></td>
														<td class="si_m"><?=$psa_si_m_osu3?></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
							<!-- Mobile Data Ends Here -->

							<span class="refresh-nospeedy-parent" <?php if ( $ps_performance_m_osu3 > 0 && $ps_performance_osu3 > 0 ) { echo "style='display:none;'" ; } ?> >
								<p>There was an issue in fetching your Website Speed</p>
								<button type="button" class="btn btn-primary refresh-nospeedy-url" table="url3-old-speed-table" website="<?=$website_data["id"]?>" additional="<?=$urlId3?>" url="<?=$url3?>" title="Get old speed records" >
									<svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg>
									Retry Now
								</button>
							</span>

							
						</div>
					</div>

				</div>

				<!-- bottom -->
				<!-- style="<?php if($new_speed == 0 ){echo "display:none";}?>" -->
				<div class="inst_speed_new-container" >
					<button type="button" style="display:none;"  name="submit__btn" class="new-analyze-speed-for-url2 btn btn-primary" tab="#step3" data-website__url2_name="<?=$url2?>" data-website_url2_id="<?=$urlId2?>" data-website_url2="<?=$url2?>" data-table_url2_id="newUrl2" data-website_url3_name="<?=$url3?>" data-website_url3_id="<?=$urlId3?>" data-website_url3="<?=$url3?>" data-table_url2_id="newUrl3" data-container=" inst_speed_newUrl2-container"  data-boosted="url2_inst_speed_new" data-oldy="url2_inst_speed" data-oldmscore="<?=isset($url2oldmscore)?$url2oldmscore:'0'?>" data-currentmscore="<?=isset($url2currentmscore)?$url2currentmscore:$url2oldmscore?>"  >Analyse Updated Speed Url2</button>

					<button type="button" style="display:none;" name="submit__btn" class="new-analyze-speed-for-url3 btn btn-primary" style="display:;" tab="#step3"   data-website_url3_name="<?=$url3?>" data-website_url3_id="<?=$urlId3?>" data-website_url3="<?=$url3?>" data-table_url3_id="newUrl3" data-container=" inst_speed_newUrl3-container"  data-boosted="url3_inst_speed_new" data-oldy="url3_inst_speed" data-oldmscore="<?=isset($url3oldmscore)?$url3oldmscore:'0'?>" data-currentmscore="<?=isset($url3currentmscore)?$url3currentmscore:$url3oldmscore?>"  >Analyse Updated Speed Url3</button>

					<p id="remove_md">To verify this data, you can visit <a href="//pagespeed.web.dev/" target="_blank" >//pagespeed.web.dev/</a>. There may be slight variations due to multiple factors, as explained by Google. Some of the significant factors include: Antivirus software, Browser extensions that inject JavaScript and alter network requests, Network firewalls, Server load, and DNS - Internet traffic routing changes. For more detailed information provided by Google, you can <a href="//developer.chrome.com/docs/lighthouse/performance/performance-scoring/#fluctuations" target="_blank" >click here</a>.<br></p>


					<style type="text/css">
					.request-manual-audit{
						display: none;
						border: 2px solid red;
						border-radius: 10px;
						padding: 10px;
						margin-bottom: 10px;
					}
					</style>

					<!-- <p class="request-manual-audit" <?php if ( $request_manual_audit == 1 && !empty($list_popup_urls) ) { echo "style='display:block;'" ; } ?> >We noticed some of your website pages didn’t improve much. Please check the button below to get them optimized manually.</p> -->

					<div class="request-manual-audit" <?php if ( $request_manual_audit == 1 && !empty($list_popup_urls) ) { echo "style='display:block;'" ; } ?> >
						<p>
							We noticed the following website pages didn’t improve much - </p>
						<ol class="list-popup-urls">
							<?=$list_popup_urls?>
						</ol>
						<p>Please click the button below to request a free audit and manual optimisation by our Experts team.</p>
					</div>

					<?php 
						$btn_label4 = '' ;
						$basic_platform = 0 ;
						
						if (strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace') {
							$btn_label4 = 'Satisfied with improved speed' ;
							$basic_platform = 1 ;
						}
						else {
							$btn_label4 = 'Satisfied with improved speed - <br>Go to Step 4' ;
							$btn_label4 = 'Satisfied with improved speed - Go to Next Step' ;
							$basic_platform = 0 ;
						}
						
					?> 
					<button type="button" id="compareurl2oldspeed" style="display:none" class="compare-to-old-speed compare-url2-old-speed" data-url="<?=$url2?>" data-step="4" data-website_name="<?=$url2?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$url2?>" data-additional-id="<?=$urlId2?>" data-table_id="newUrl2" data-boosted="url2_inst_speed_new" data-oldy="url2_inst_speed" data-container="url2_inst_speed_new-container" data-nospeedy=<?=$nospeedyUrl2?>>Compare to old speed</button>
					<!-- <button class="step5completeIns" data-next="4">Can my Website get faster than this?</button> -->

					<?php
						$goto_next_optimize = 0 ;
						$satified_or_not = $rating = $trust_pilot_review = $feedback = $improve = '' ;
						$query = $conn->query( " SELECT step_completed , satified_or_not , rating , trust_pilot_review , feedback , improve FROM `website_review_feedback` WHERE website_id = '$site_id' ; " ) ;
						
						if ( $query->num_rows > 0 ) {
							$data = $query->fetch_assoc() ;
							$goto_next_optimize = $data["step_completed"] ;
							$satified_or_not = $data["satified_or_not"] ;
							$rating = $data["rating"] ;
							$trust_pilot_review = $data["trust_pilot_review"] ;
							$feedback = $data["feedback"] ;
							$improve = $data["improve"] ;
						}
						
						//123
						if(isset($feedback)){
							$feebbackBtn = "none";
						}else{
							$feebbackBtn = "block";
						}
						
						$optimize_more_speed =  empty($goto_next_optimize) ? "goto-next-optimize" : (($goto_next_optimize == 3 || $goto_next_optimize == 4 || $goto_next_optimize == 5 ) ? "goto-next-step" : "goto-next-optimize") ;
						
					?>
					<div class="btn_group">
						<input type="hidden" id="step_completed" value="<?=$goto_next_optimize?>" data-satified_or_not="<?=$satified_or_not?>" data-rating="<?=$rating?>" data-trust_pilot_review="<?=$trust_pilot_review?>" data-feedback="<?=$feedback?>" data-improve="<?=$improve?>" >
						<button class="<?=$optimize_more_speed?> optimize-speed-btn" data-fstep="1" data-plaform="0" data-next="4">Optimize For More Speed</button>

						<button style="display:<?=$feebbackBtn?>" class="goto-next-optimize" data-fstep="1" data-plaform="0" data-next="4">Share a Feedback</button>

						<!-- ishan sir 
						<a href="//tfft.io/sTK6EOi" class="btn btn-success" target="_blank">Support for Web Design and Development</a> -->
					</div>
					<!-- if 1 or 2 pages got more than old score but less than 5 points -->
					<?php
						// $request_manual_audit = 0 ;
						
						// $query7 = $conn->query(" SELECT id , website_id , score_difference , request_manual_audit FROM `website_improve_needed` WHERE website_id = '".$website_data["id"]."' ; ") ;
						
						// if ( $query7->num_rows > 0 ) {
						
						// 	$win_data = $query7->fetch_assoc() ;
						
						// 	$request_manual_audit = ($win_data["request_manual_audit"] == 1) ? 1 : 0 ;
						// }
					?>


					<!-- <p class="request-manual-audit" <?php if ( $request_manual_audit == 1 ) { echo "style='display:block;'" ; } ?> >We noticed some of your website pages didn’t improve much. <button type="button" class="request-manual-audit-btn" title="Request manual audit for pages that didn’t improve">Request manual audit</button></p> -->

					<!-- <p><strong>Note:</strong> Website speedy Boosts whole website i.e all page on your website. We only show results for 3 important pages for sake of ease of tracking. You can check other pages manually as per <a href="<?=$website_instructions?>" target="_blank">instructions here</a>.</p> -->
					<?php 
						if(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' ||
							strtolower($website_data['platform']) == 'squarespace') { 
								?>
								<span id="trusted"  style="<?php if($satisfy !=1){echo "display:none";}?> ">Click the button below to review our platform on Trust Pilot<br><br><a href="//www.trustpilot.com/review/websitespeedy.com" target="_blank"><button style="background-image:url('//shopifyspeedy.com/img/trustpilot.png');">Trust Pilot</button></a></span>
								<?php 
						} 
					?>
				</div>
			</div>

		</div>
	</div>
	<!--  END Step 3 -->		
 

	<!--  Start Step 4-->
	<div id="step4" class="form-tab <?php if($installation >=4){echo "complited";}else{echo " d-none ";}?>">

		<div class="tabs-4-head tabs-head" tab="tabs-4">
			<span class="number" >4</span>
			<span class="text"> Manual Audit and optimization </span>
			<span class="icon"><i class="las la-angle-down"></i></span>
		</div>

		<div class="tabs-4 form-tab-"  style="<?php if( $installation == 4){echo "display:none";}elseif($installation != 4){echo "display:none";}?>">

			<!-- <div class="margin__top__only" >
				<label>Can parameters be added to any platform ?</label>
				<p>No, Some platforms like Wix or Clickfunnels do not provide access to raw code hence we can not add parameters there, You can still contact our team to help with further speed improvement with other possible methods.</p>
			</div> -->


			<?php 

			// if (strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace') {

				?>
				<!-- <div class="request" style="display:none !important;">	
					<p>1- Request Website Speedy team to update the parameters </p>
					<button  class="<?php if($request_sent==0){echo "alert-pop";}else{echo "request_sent";}?> tabs-5-request btn btn-primary" style="display: none !important;">Generate Request (free)</button>
				</div> -->
				<?php

			// }
			// else {

				?>
				<div class="request">

					<?php


					$hst_active = 0 ;
					$hst_id = 0 ;
					$hst_url = HOST_HELP_URL."user/support/tickets/all" ;

					$hst_query = $conn->query(" SELECT ticket_id , ticket_type , status FROM `help_support_tickets` WHERE `website_id` LIKE '".$website_data["id"]."' AND ticket_type LIKE 'Manual Audit By Team' AND `status` = 1 LIMIT 1 ; ") ;

					if ( $hst_query->num_rows > 0 ) {
						$hst_data = $hst_query->fetch_assoc() ;
						$hst_id = $hst_data["ticket_id"] ;
						$hst_active = 1 ;

						$hst_url = HOST_HELP_URL."user/support/ticket/".$hst_id ;
					}

					// $genrate_status = $conn->query("SELECT `manager_id`,`status`,`access_requested`,`optimisation_is_progress` FROM `generate_script_request`  WHERE `manager_id` = $user_id");
					// if($genrate_status->num_rows > 0){
					// 	$gs_data = $genrate_status->fetch_assoc() ;
					// }

					?>

					<span class="step4-manual-audit-request" <?php if(!empty($hst_active)){ echo "style='display:none;'"; } ?> >
						<p>Request WebsiteSpeedy team to Perform a manual Audit and make optimizations on my website to further speed improvement.</p>
						<button  class="<?php if($request_sent==0){echo "alert-pop";}else{echo "request_sent";}?> tabs-5-request btn btn-primary" data-subject="Manual Audit By Team" >Generate Manual Audit Request (Free)</button>
					</span>

					<span class="step4-manual-audit-sent" <?php if( empty($hst_active)){ echo "style='display:none;'"; } ?> >
					<?php if(empty($hst_id)){ $hst_id = ''; } ?>
					<p>Your Ticket is submitted.<br>Your Ticket Number is - <span id="ticketId"></span><?=$hst_id?>,
						Our Experts will get in touch with you soon</p>
						<a href="<?=$hst_url?>" class="btn btn-primary view_ticket" target="_blank">View Ticket</a>
						<div class="btn_group arrow">
						<a href="javascript:void(0);" class="btn text-white bg-success view_ticket">Request Submitted</a>
						<?php if($gs_data['access_requested'] == '1'){?>
						<a href="javascript:void(0);" class="btn text-white bg-success view_ticket">Access Requested</a>
						<?php }else{ ?>
							<a href="javascript:void(0);" class="btn text-white bg-secondary view_ticket">Access Requested</a>
							<?php }if($gs_data['optimisation_is_progress'] == '1'){ ?>
						<a href="javascript:void(0);" class="btn text-white bg-success view_ticket">Optimisation is progress</a>
						<?php }else{ ?>
							<a href="javascript:void(0);" class="btn text-white bg-secondary view_ticket">Optimisation is progress</a>
							<?php }if($gs_data['status'] == '1'){ ?>
						<a href="javascript:void(0);" class="btn text-white bg-success view_ticket">Optimisation Completed</a>
						<?php }else{ ?>
						<a href="javascript:void(0);" class="btn text-white bg-secondary view_ticket">Optimisation Completed</a>
							<?php } ?>
					    </div>		
					</span>
				</div>

				<div class="myself" style="display:none;">
					<p>2- I want to do this myself(coming soon) </p>
				</div>

				<div class="faq">
					<label>FAQs</label>
					<div>
						<label>1. What needs to be audited and what kind of optimizations will you make?</label>
						<p class="no__margin">While website speedy Automatically improves loading time significantly. However, in some cases, it needs some updates are required depending on your website's platform, structure and code. Here are some samples -</p>
						<p>i) Adding parameters in code to meet latest performance standards -</p>
						<p>The following parameters will be added to your javascript & CSS links, it will be updated by adding the “preload”, “pre-connect” or “dns-prefetch” depending on the requirement. For external scripts such as GTM, Analytics and other we will add “data-src” will be added instead of “src” or “data-href” instead of “href” to help WebsiteSpeedy identify and load the parameters correctly. Below is a before and after look of these codes.</p>
						<div class="code" >
							<b style="font-weight:500;">Code before parameters update:</b><br>
							<code>
							&lt;link href="//fonts.googleapis.com/css2"&gt;
							</code><br>
							<code>
							&lt;script async src="//www.googletagmanager.com/gtag/js?id=AW-9289"&gt;&lt;/script&gt;
							</code><br>
							<code>
							&lt;link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font.min.css"&gt;
							</code>
						</div>
						<div class="code" >
							<b style="font-weight:500;">Code After parameters update:</b><br>
							<code>
							&lt;link rel="preconnect" href="//fonts.googleapis.com/css2"&gt;
							</code><br>
							<code>
							&lt;script async data-src="//www.googletagmanager.com/gtag/js?id=AW-9289">&gt;&lt;/script&gt;
							</code><br>
							<code>
							&lt;link rel="stylesheet" data-href="//cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font.min.css"&gt;
							</code>
						</div>
						<p>ii) Review and update code for specific pages to make sure Specific pages or templates are not loading any scripts or code that is not required in that specific page.</p>
						<p>Example if you have a Hero slider on homepage but its scripts are loading on other important pages like product page or request a quote page, that might make it slow. Our team will fix this.</p>
						<p>iii) optimise any specific images, js or css that is not optimised automatically by our script.</p>
						<p>iv) More optimisations specific to platforms, servers or other 3rd party tool you might be using like google tag manager, outside scripts etc</p>
					</div>
					<div class="margin__top__only" >
						<label>2. How much page speed can I expect after these optimizations ?</label>
						<p>After completing step 4, you can achieve Google PageSpeed scores of 70 or higher on mobile and 90 or higher on desktop. The exact amount of speed improvement can vary depending on a variety of factors, including the size and complexity of your website, as well as your hosting environment. You can generally expect to see a significant improvement in your Google PageSpeed scores. For more information, click here. (<a href="<?=HOST_URL?>speed-guarantee.php" target="_blank">//websitespeedy.com/speed-guarantee.php</a>)</p>
					</div>
					<div class="margin__top__only" >
						<label>3. Will you take a backup?</label>
						<p>Yes, we will take a backup of your website. Our team will work on a duplicate theme if your platform allows it and we will connect with you before making changes live. This way, we can ensure that your website is backed up and that any changes we make are approved by you before going live.
						</p>
					</div>

					<div class="margin__top__only" >
						<label>4. Does Website Speedy support all platforms for speed optimization?
						</label>
						<p>Website Speedy is compatible with various popular platforms, including major CMS and e-commerce systems. Website Speedy currently does not work for WordPress and woocommerce. For other specific platform inquiries, please contact our support team.</p>
					</div>
				</div>
				<?php

			// }

			?>
		</div>


	</div>
	<!--  END Step 4 -->






			</div>
		</div>	
	</div> 



										<!-- Second Tab Install by Speedy Team -->
										<div id="tab-2" class="tab-pane">
											<!-- <button class="btn btn-success <?php if($request_sent==0){echo "request_generate_top_btn";}else{echo "request_sent";}?>">Request Installation</button> -->


											<div class="col-12">

												<div class="request_form_2_sent" style="<?php if($request_sent!=1){echo "display:none";}?>">
													<div class="status-icon <?php if($status_request == 0){ echo "pending";}else {echo "completed";}  ?>">
														<img class="pending__img" src="../adminpannel/img/status.png"/>	
														<img class="completed__img" src="../adminpannel/img/status-green.png"/>	
													</div>
													<label class="status"> <span id="status_request"><?php if($status_request == 0){ echo "Pending";}else {echo "Completed";}  ?></span></label>
													<?php if($status_request == 0){ ?>
													<p>Our team will get in touch with you shortly to get access for updating parameters, please do check your email and spam folder to make sure you do not miss it.</p>
												<?php } ?>

												</div>	

												<div class="support__form request_form_2" style="<?php if($request_sent==1){echo "display:none";}?>" >
													<div class="d__flex" >
														<span class="request-err d-none text-danger">Please Fill All Field.</span>
														

														<div>
															<label>Country Code</label>
															<select id="country-code-2" class="form-control country-code-list" required name="country" >
																<option value="">Select</option>
															</select>
														</div>

														<div>
															<label>Contact Number</label>
															<input id="contact-2" class="form-control" type="number" value="<?=$row['phone']?>" required>
														</div>
													</div>
													<div class="full__col">
														<label>Email</label>
														<input type="email"  class="form-control" value="<?=$row['email']?>" id="email-2">
													</div>

													<div class="full__col">
														<label>Time Zone</label>
														<select class="form-control timezone-list" id="tz-2" name="timezone" required>
														</select>
													</div>
														
													<div class="col-12">
														<label>Suitable Time For Contact</label>
														<input id="suitable-time-2"  type="date" class="form-control suitable-time-2" required>
														<div class="col-12">
															<div class="row">
																<div class="col-6">From : 
																	<input type="time" id="time-form-2" required>
																</div>
																<div class="col-6">To : 
																	<input type="time"  id="time-to-2" required>
																</div>
															</div>
														</div>
													</div>
													<button class="mt-3 btn btn-success" id="request_form_2" data-subject="Script Installtion Help Request">Submit</button>										
												
												</div>



										</div>
									</div>
								</div>

								<!-- Horizontal Steppers -->
							</div>


							<div class="container_sld">
    <div class="accordion_s">
        <h3>FAQ’s</h3>

        <ul class="accordion_sn">
            <li>
                <a target="blank" class="toggle" href="javascript:void(0);">How To Compare Updated Speed With Old Speed</a>
                <ul class="inner show" style="display:block;">
                    <li>
                        <a target="blank" href="#" class="toggle">Google page speed insights</a>
                        <div class="inner">
                            <div class="acc_con">
                                <p><strong>
                                        Website speedy allows you to compare the updated speed of a website with its old
                                        speed in real-time.
                                    </strong></p>
                                <p><strong>To compare the old and updated speed</strong></p>
                                <ul>
                                    <li>Open <a target="blank" href="//pagespeed.web.dev/">//pagespeed.web.dev/</a> in two different tabs
                                            <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/visit-pagespeed-in-two-tabs.gif" alt="visit pagespeed in two tabs">
                                    </li>

                                    <li>In the first tab enter your website URL</li>

                                    <li>Click on "Analyze." Initiate the speed test.
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/google-page-speed-insights.avif"
                                            class="visit pagespeed in two tabs">
                                    </li>

                                    <li>In the second tab enter your website URL</li>
                                    <li>Input your site's address with “?nospeedy” code at the end of the
                                        URL<br>Example: <strong>www.abc.com/?nospeedy</strong></li>
                                    <li>Click on "Analyze." Initiate the speed test</li>
                                    <li>Review Results. Examine and compare the performance insights.</li>
                                    <li>Now you can compare side by side performance and speed insights
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/gaint-teddytt-w-sp-m.avif"
                                        alt="">
                                    <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/gaint-teddytt-w-sp-d.avif"
                                        alt="">
										Note : speed might fluctuate depending upon different factors. Read question 2 for details
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li>
                        <a target="blank" href="#" class="toggle">GTmetrix</a>
                        <div class="inner">
                            <div class="acc_con">

                                <p><strong>Website speedy allows you to compare the updated speed of a website with its
                                        old speed in real time.</strong></p>
                                <p><strong>To compare the old and updated speed</strong></p>
                                <ul>
                                    <li>Open GTmetrix.com in two different tabs
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/visit-GTmetrix-in-two-tabs.gif"
                                            alt="visit GTmetrix in two tabs">
                                    </li>

                                    <li>In the first tab enter your website URL</li>
                                    <li>Click on "Test your site"
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/gtmetrix.avif" alt="">

                                    </li>
                                    <li>In the second tab enter your website URL</li>
                                    <li>Input your site's address with “?nospeedy” code at the end of the URL<br>
                                        Example: <strong>www.abc.com/?nospeedy</strong></li>
                                    <li>Click on "Test your site" & Initiate the performance test.</li>
                                    <li>Review Page Performance Examine detailed speed metrics.& Compare old speed with boosted speed.</li>
                                    <li>
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/Gtmetrix-website-speedy-giant-teddy-with-nospeedy.avif"
                                        alt="">
										Note : speed might fluctuate depending upon different factors. Read question 2 for details
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a target="blank" href="#" class="toggle">Pingdom</a>
                        <div class="inner">
                            <div class="acc_con">
                                <p><strong>Website speedy allows you to compare the updated speed of a website with its
                                        old speed in real time.</strong></p>
                                <p><strong>To compare the old and updated speed</strong></p>
                                <ul>
                                    <li>Open <a target="blank" href="//tools.pingdom.com/">//tools.pingdom.com/</a> in two different tabs
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/visit-Pingdom-in-two-tabs.gif"
                                            alt="visit Pingdom in two tabs">
                                    </li>
                                    <li>In the first tab enter your website URL
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/Pingdom.avif" alt="">
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/Step-1-Visit-the-Pingdom-Website.avif"
                                        alt="">
                                    </li>

                                    <li>Choose a geographic location for testing.
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/step-3-Choose-a-Test-Location.avif"
                                            alt="">
                                    </li>
                                    <li>Click "Start Test" & Initiate the speed test.
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/step-4-Initiate-th-Speed-Test.avif"
                                            alt="">
                                    </li>
                                    <li>In the second tab enter your website URL</li>
                                    <li>Input your site's address with “?nospeedy” code at the end of the URL<br>
                                        Example: <a target="blank"
                                            href="http://www.abc.com/?nospeedy">www.abc.com/?nospeedy</a></li>
                                    <li>Now you can review results side by side & check the detailed metrics provided by Pingdom.</li>
                                    <li><img src="//websitespeedycdn.b-cdn.net/speedyweb/images/Pingdom-website-speedy-flowbiz-collection-with-nospeedy.avif
                                        " alt="">
										Note : speed might fluctuate depending upon different factors. Read question 2 for details
                                        
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li>
                        <a target="blank" href="#" class="toggle">Webpagetest</a>
                        <div class="inner">
                            <div class="acc_con">
                                <p><strong>Website speedy allows you to compare the updated speed of a website with its
                                        old speed in real time. </strong></p>
                                <p><strong>To compare the old and updated speed</strong></p>
                                <ul>
                                    <li>Open <a target="blank" href="//www.webpagetest.org/">//www.webpagetest.org/</a> in two different tabs
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/visit-Webpagetest-in-two-tabs.gif"
                                            alt="visit Webpagetest in two tabs">
                                    </li>
                                    <li>In the first tab enter your website URL
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/webpagetest-step-4.avif"
                                            alt="">
                                    </li>
                                    <li>Choose a preferred location for testing.
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/webpagetest-step-2.avif"
                                            alt="">
                                    </li>
                                    <li>Click "Start Test" & Initiate the speed test.
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/webpagetest-step-5.avif"
                                            alt="">
                                    </li>
                                    <li>In the second tab enter your website URL</li>
                                    <li>Input your site's address with “?nospeedy” code at the end of the UR<br>
                                        Example: <a target="blank"
                                            href="http://www.abc.com/?nospeedy">www.abc.com/?nospeedy</a></li>
                                    <li>Now you can review side by side Performance Results & check the detailed metrics provided by webpagetest.</li>
                                    <li>
                                        <img src="//websitespeedycdn.b-cdn.net/speedyweb/images/webpagetest-hocho-knife-with-and-without-nospeedy-code.avif"
                                        alt="">
										Note : speed might fluctuate depending upon different factors. Read question 2 for details
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>

            <li>
                <a target="blank" class="toggle" href="javascript:void(0);">Why Your Score Fluctuates?</a>
                <ul class="inner">
                    <li>
						<div class="acc_con">
							<p>A lot of the variability in overall Performance score & metric values is not due to Lighthouse. When your
								Performance score fluctuates it's usually because of changes in underlying conditions. Common problems
								include:<br>
								A/B tests or changes in ads being served. Internet traffic routing changes
								Testing on different devices, such as a high-performance desktop and a low-performance laptop. Browser
								extensions that inject JavaScript and add/modify network requests. Antivirus software
							</p>
						
							<ul class="block_a">
								<li><a href="//developer.chrome.com/docs/lighthouse/performance/performance-scoring/#fluctuations">Google
									</a></li>
								<li><a href="//gtmetrix.com/blog/why-is-my-performance-score-always-changing/">GTMetrix</a></li>
								<li><a href="<?=HOST_URL?>blog/why-does-website-page-speed-fluctuate/">Website Speedy</a></li>
							</ul>
						
						
						</div>
                    </li>
                </ul>
            </li>

            <li>
                <a target="blank" class="toggle" href="javascript:void(0);">Will Website Speedy work for all pages of my website?</a>
                <ul class="inner">

                    <li>

                        <div class="acc_con">
                            <p> Website Speedy boosts the speed of your entire website, including all its pages. While
                                we
                                showcase results for three important pages for ease of tracking, it's important to note
                                that the
                                improvements apply globally. You can manually check the speed of other pages as well to
                                see the
                                overall enhancement across your entire website. To check other pages you can directly check on -
                            </p>
                            <ul class="block_a">
                                <li><a href="//pagespeed.web.dev/">Google Page Insight</a></li>
                                <li><a href="//gtmetrix.com/">GTMetrix</a></li>
                                <li><a href="//tools.pingdom.com/">Pingdom</a></li>
                                <li><a href="//www.webpagetest.org/">Websitespeedtest</a></li>
                                <li>Using methods described in question 1</li>
                            </ul>
                        </div>

                    </li>

                </ul>
            </li>



			<li>
                <a target="blank" class="toggle" href="javascript:void(0);">Does Website Speedy provide a speed guarantee?</a>
                <ul class="inner">

                    <li>

                        <div class="acc_con">
                            <p>Yes, Website Speedy guarantees to improve your website's speed by up to 5 times your current speed.<br>
								If in peak traffic or server load time your website speed score  is 30 without speedy for mobile on google page speed insights , it would be 50 with website speedy, similarly without much load if its 55 without speedy, it will be 80 with website speedy.<br>
								Website Speedy does not make any changes to your website's code or server. It works on resolving render-blocking issues and fixing core web vital issues that hinder your website's speed. Website Speedy helps make your website load faster and improves your overall website performance score.<br>
								To check the performance change, add parameter ?nospeedy at the end of the testing URL which will disable our tool from loading so you can test the website performance with and without Website Speedy. Refer Question 1 for details.</p>
                            </ul>
                        </div>

                    </li>

                </ul>
            </li>	

			
            

        </ul>


    </div>
</div>
</div>
    </div>

						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div>

		</div>

		<div class="popup_img__wrap" >
			<button class="closeBtnImgPopup" >
				<svg fill="#333" width="30px" height="30px" viewBox="0 0 16 16">
					<path d="M0 14.545L1.455 16 8 9.455 14.545 16 16 14.545 9.455 8 16 1.455 14.545 0 8 6.545 1.455 0 0 1.455 6.545 8z" fill-rule="evenodd"/>
				</svg>
			</button>
			<div class="popup_img" >
				<img class="pop_up_img" src="" alt="">
			</div>
		</div>


    <script>

    // Sticky Mobile and desktop icon
    window.addEventListener('scroll', function() {
    var tabber = document.querySelector('.mobile-desktop-tabber');
    var tabber_child = document.querySelector('.desk_mb_speed');
    var remove_md = document.getElementById('remove_md');
    var currentPoss = remove_md.getBoundingClientRect().top;
    var currentPosition = tabber.getBoundingClientRect().top;

    if (currentPosition <= 150 ) {
        tabber_child.classList.add('sticky_s');
    } else if (currentPosition > 150) {
        tabber_child.classList.remove('sticky_s');
    }

    if(currentPoss < 150) {
        tabber_child.classList.remove('sticky_s');
    }
    });



var alertBtnSI = document.querySelector('.script_I > .alert-status .close');
alertBtnSI.addEventListener('click', () => {
    document.querySelector('.page_web_speed.desk_mb_speed').classList.add('no__alert')
})





        $('.toggle').click(function (e) {
            e.preventDefault();

            var $this = $(this);

            if ($this.next().hasClass('show')) {
                $this.next().removeClass('show')
                $this.next().slideUp(350);
            } else {
                $this.parent().parent().find('li .inner').removeClass('show');
                $this.parent().parent().find('li .inner').slideUp(350);
                $this.next().toggleClass('show');
                $this.next().slideToggle(350);
				
            }
        });
		
    </script>

		<script>
			document.addEventListener('DOMContentLoaded', () => {
				var allImgPop = document.querySelectorAll('.popImg');
				allImgPop.forEach(function(imgPop) {
					imgPop.addEventListener('click', () => {
						const videoImg = document.querySelector('.pop_up_img');
						const curImgSrc = imgPop.getAttribute('src');
						videoImg.setAttribute('src', curImgSrc);
						document.querySelector('.popup_img__wrap').classList.add('active')
					})
				})
			})

			document.querySelector('.closeBtnImgPopup').addEventListener('click', () => {
				document.querySelector('.popup_img__wrap').classList.remove('active')
			})
			
		</script>
	
	</body>


</html>

<script>
	// function loaderest() {
	// 	var typed = new Typed('.auto-type', {   
    //     strings: ['500 milliseconds of extra loading results in a traffic drop of 20% - Google', 'Every extra 100 milliseconds of loading decreases sales by 1%  - Amazon', 'With a 0.1s improvement in site speed, we observed that retail consumers spent almost 10% more - Deloitte', 'President Obama 2012 fundraising success strategy was based on his site loading instantly - Obama', '82% of B2B pages load in 5 seconds or less - Portent', 'An ecommerce site that loads within a second converts 2.5x than a site that loads in 5 seconds - portent', 'Facebook’s prefetching reduces the load time of a website by up to 25%. -  blogging wizard'],
    //     typeSpeed: 20,
    //     backSpeed: 20,
    //     backDelay: 3000,
    //     loop: true,
    //   });
	// }
</script>

<script>

var new_interval_analyse = '' ;
var started_reanlyse_additional_url = 0 ;

document.querySelectorAll('.accordion-item h2').forEach((accordionToggle) => { 
  accordionToggle.addEventListener('click', () => { 
  const accordionItem = accordionToggle.parentNode; 
  const accordionContent = accordionToggle.nextElementSibling; 

        // If this accordion item is already open, close it.
   if (accordionContent.style.maxHeight) { 
       accordionContent.style.maxHeight = null; 
       accordionItem.classList.remove('active'); 
      } else {
        // accordionContent.style.maxHeight = accordionContent.scrollHeight + 'px'; 
        accordionContent.style.maxHeight = 300 + 'px'; 
            accordionItem.classList.add('active'); 
        }
    });
});

</script>


<script>

var support_subject = "Team Installation Request" ;

const items = document.querySelectorAll('.accordion_s button');

function toggleAccordion() {
  const itemToggle = this.getAttribute('aria-expanded');

  for (i = 0; i < items.length; i++) {
    items[i].setAttribute('aria-expanded', 'false');
  }

  if (itemToggle == 'false') {
    this.setAttribute('aria-expanded', 'true');
  }
}

items.forEach((item) => item.addEventListener('click', toggleAccordion));
	</script>

<script>
$(document).ready(function() {


	// Copy to clip board
	var span = document.querySelector("#script_code");
	var clicks = document.querySelector("#clic_kbtn");

	clicks.onclick = function() {
	document.execCommand("copy");
	}

	clicks.addEventListener("copy", function(event) {
	event.preventDefault();
	if (event.clipboardData) {
		event.clipboardData.setData("text/plain", span.textContent);
		console.log(event.clipboardData.getData("text"))
	}

	$('#clic_kbtn').addClass('copied');

	$('#copiedText').css('visibility', 'visible').css('right', '0px')

	setTimeout(function(){
		$('#clic_kbtn').removeClass('copied');
		$('#copiedText').css('visibility', 'hidden').css('right', '-120px')
	}, 5000)

	});

// Copy to clip board end
// Copy to clip board

   var span2 = document.querySelector("#script_code");
   var clicks2 = document.querySelector("#script_code");

	clicks2.onclick = function() {
	document.execCommand("copy");
	}

	clicks2.addEventListener("copy", function(event) {
		event.preventDefault();
		if (event.clipboardData) {
			event.clipboardData.setData("text/plain", span2.textContent);
			console.log(event.clipboardData.getData("text"))
		}

		$('#clic_kbtn').addClass('copied');

		$('#copiedText').css('visibility', 'visible').css('right', '0px')

		setTimeout(function(){
			$('#clic_kbtn').removeClass('copied');
			$('#copiedText').css('visibility', 'hidden').css('right', '-120px')
		}, 5000)

	});

	$(".reanalyzeold").click(function(){
		Swal.fire(
			'Initial report can not be Reanalyze',
			'This is your Initial report and cant be modify after installed script',
			'info'
		)  	
	});					

// Copy to clip board end

$(".p-instruction").click(function(){
	window.open('<?=$website_instructions?>', '_blank');
});

var from_tab = "5";
 
$(".i-need-help").click(function () {

	from_tab = $(this).parents(".form-tab").attr("id");

	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			confirmButton: 'btn btn-success',
			cancelButton: 'btn btn-danger'
		},
		buttonsStyling: false
	})

	swalWithBootstrapButtons.fire({
		title: 'Are you facing any trouble while installing Websitespeedy? ',
		html: '<div class="wrapper__need__help__pop"><p>Check out Installation instructions</p> <a class="btn btn-primary text-primary instructions__btn__install" target="_blank" href="<?=$website_instructions?>">Go to instructions</a><p>Generate a Installation request for installation by Website speedy team</p></div>',

		icon: 'question',
		showCancelButton: false,
		showCloseButton: true,
		confirmButtonText: 'Create install request',
		cancelButtonText: '<?=$website_instructions_label?>',
		reverseButtons: true
	}).then((result) => {

		if (result.isConfirmed) {

			if ('<?=$request_sent?>' == 0) {
				from_tab_t = from_tab;
				$(".alert-pop").click();
				support_subject = "Team Installation Request" ;
			}
			else {
				Swal.fire(
					'Already Sent',
					'Your Request is already sent to Website Speedy team.',
					'info'
				)
			}
			// swalWithBootstrapButtons.fire(
			//   'Deleted!',
			//   'Your file has been deleted.',
			//   'success'
			// )
		}
		else if (
			/* Read more about handling dismissals below */
			result.dismiss === Swal.DismissReason.cancel
		) {

			// window.location.href="instruction.php";
			window.open('instruction.php', '_blank');

		}
	})

});



var website_id_main = '<?=$website_data['id']?>';


$(".next-step").click(function(){

	var id = $(this).attr("data-id");


  		$("#step1").hide();
    	$("#step2").show();

		$(".warning .circle ").removeClass("bg-light");
		$(".warning .circle ").addClass("bg-info");

});	


$("body").on("click",".manualbtnverify",function(){
 
    Swal.fire({
	  title: '<img src="//websitespeedycdn.b-cdn.net/speedyweb/images/script_added_s.png" style="max-width:80px; display:block;">',
      html: '<p class="utbbiy">Use the button below if your website is still not being verified even after adding the script, By clicking this button you are agreeing that you have already added the script as per the instructions.<p>',
      confirmButtonText: 'Agree',
      showCancelButton: true,
  	cancelButtonText: 'Disagree',
     }
     ).then((result) => {
		if (result.isConfirmed) {
			$("#step3").removeClass("d-none");
			$("#step3").addClass("complited"); 
			$(".top-step-3").click(); 
			 localStorage.setItem("waitTime", new Date());
			 $(".new-analysee").hide();
			$(".inst_speed_new-container").hide();
			setTimeout(function(){
			showTimer();
			},500);
			installation_steps(3, null, 'manual');
			$(".step-2-reanalyse.reanalyze-btn-").hide();
			$(".reanalyzeold").show();

		}
	});


});



	


		$(".table-script-core").hide();
		$(".page-speed1").click(function() {

			$(".table-script-core").hide();
			$(".table-script-speed").show();
			$(".page-speed1").addClass("btn-primary");
			$(".page-speed1").removeClass("btn-light");
			$(".core-web-vital2").addClass("btn-light");
			$(".core-web-vital2").removeClass("btn-primary");

			var ws_status = $(".url1-newspeed-container").attr("data-ws_status") ;
			var blank_record = $(".url1-newspeed-container").attr("data-blank_record") ;
			$(".url1-newspeed-container").removeClass("url-old-speed-score");
			if ( ws_status == "popup" && (blank_record == "both" || blank_record == "mobile") ) {
				$(".url1-newspeed-container").addClass("url-old-speed-score");
			}

			var ws_status = $(".url2-newspeed-container").attr("data-ws_status") ;
			var blank_record = $(".url2-newspeed-container").attr("data-blank_record") ;
			$(".url2-newspeed-container").removeClass("url-old-speed-score");
			if ( ws_status == "popup" && (blank_record == "both" || blank_record == "mobile") ) {
				$(".url2-newspeed-container").addClass("url-old-speed-score");
			}

			var ws_status = $(".url3-newspeed-container").attr("data-ws_status") ;
			var blank_record = $(".url3-newspeed-container").attr("data-blank_record") ;
			$(".url3-newspeed-container").removeClass("url-old-speed-score");
			if ( ws_status == "popup" && (blank_record == "both" || blank_record == "mobile") ) {
				$(".url3-newspeed-container").addClass("url-old-speed-score");
			}


		});
		$(".core-web-vital2 ").click(function() {
			$(".table-script-speed").hide();
			$(".table-script-core").show();

			$(".core-web-vital2").addClass("btn-primary");
			$(".core-web-vital2").removeClass("btn-light");
			$(".page-speed1").addClass("btn-light");
			$(".page-speed1").removeClass("btn-primary");

			var ws_status = $(".url1-newspeed-container").attr("data-ws_status") ;
			var blank_record = $(".url1-newspeed-container").attr("data-blank_record") ;
			$(".url1-newspeed-container").removeClass("url-old-speed-score");
			if ( ws_status == "popup" && (blank_record == "both" || blank_record == "desktop") ) {
				$(".url1-newspeed-container").addClass("url-old-speed-score");
			}

			var ws_status = $(".url2-newspeed-container").attr("data-ws_status") ;
			var blank_record = $(".url2-newspeed-container").attr("data-blank_record") ;
			$(".url2-newspeed-container").removeClass("url-old-speed-score");
			if ( ws_status == "popup" && (blank_record == "both" || blank_record == "desktop") ) {
				$(".url2-newspeed-container").addClass("url-old-speed-score");
			}

			var ws_status = $(".url3-newspeed-container").attr("data-ws_status") ;
			var blank_record = $(".url3-newspeed-container").attr("data-blank_record") ;
			$(".url3-newspeed-container").removeClass("url-old-speed-score");
			if ( ws_status == "popup" && (blank_record == "both" || blank_record == "desktop") ) {
				$(".url3-newspeed-container").addClass("url-old-speed-score");
			}

		});

 
		$("#add-website-form").submit(function(e) {

			var valid = true;
			$(".additonal-urls , .additonal-names , #website_name").removeClass("invalid");

			//  ============================================
			// e.preventDefault() ;

			// console.log("call") ;

			var website = $("#website-url").val();
			if (website == undefined || website == '' || website == null) {
				$("#website-url").addClass("invalid");
				valid = false;
			}


			var website_parse = new URL(website);
			var website_origin = website_parse.origin;

			var f = 0;


			if (valid) {

				$(".loader").show().html('<div class="loader"><p>Analyzing your website. It might take 2-3 mins</p><dotlottie-player src="//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie"  background="transparent"  speed="1"  style="width: 300px; height: 300px;" loop autoplay></dotlottie-player></div>');
                loaderest()
			}
		});
		var select = $("select[name='website-platform']").val();

		if (select == 'Other') {

			$('#other_platform').show();
		} else {
			$('#other_platform').hide();

		}

		$('select[name="website-platform"]').change(function() {
			// $(this).val() will work here
			var type = $(this).find('option:selected').val();
			if (type == 'Other') {
				$('#other_platform').show();
			} else {
				$('#other_platform').hide();

			}
		});

var reloadSpeed = null;

function try_again(){

	$.ajax({
      type: "POST",
      url: "inc/maege_speed_remove.php",
      data: {id:website_id_main},
      dataType: "json",
      encode: true,
    }).done(function (data) {
      // console.log(data);
    });	
			Swal.fire({
				  title: 'Someting Went Wrong!',
				  icon: 'error',
				  text: "We couldn't retrieve website speed data right now. Click the reload button to try again or contact support if the error persists.",
				  showDenyButton: true,
				  showCancelButton: false,
   				  allowOutsideClick: false,
  				  allowEscapeKey: false,
  				  confirmButtonText: 'Reload',
				  denyButtonText: `Dismiss`,
				}).then((result) => {
				  /* Read more about isConfirmed, isDenied below */
				  if (result.isConfirmed) {
				   // $(".loader").show();
				   $(reloadSpeed).click();
				  }
				})

}

$(".tabs-head").click(function(){
	var tab = "."+$(this).attr("tab");


	$(tab).toggle();
	$(this).find(".icon").toggleClass("rotate");
	// $(this).find(".icon").toggleClass("fa-angle-down");

});


$(".stepper a").click(function () {

	var tab = $(this).attr("tab");
	if ($(tab).hasClass('complited')) {
		$(".form-tab-").hide();
		$(".tabs-head").find('.icon').addClass('rotate');
		// $(".form-tab").hide();
		// $(tab).show();
		console.log("sss");


		$(tab).find(".form-tab-").show();


		// if($('.stepper-horizontal li').hasClass('open')){

		// }else {
		// 	$('.stepper-horizontal li').addClass('open')
		// }

		$(this).find(".circle").addClass("bg-info");
		$(this).parent().nextAll().find(".circle").each(function () {
			$(this).removeClass("bg-info");
		});

		$(this).parent().prevAll().find(".circle").each(function () {
			$(this).removeClass("bg-light");
		});
		$(this).parent().prevAll().find(".circle").each(function () {
			$(this).addClass("bg-info");
		});


		$(this).parent().nextAll().find(".circle").each(function () {
			$(this).addClass("bg-light");
		});

	}

	if (tab == "#step5") {
		$('html, body').animate({
			scrollTop: $("#step5").offset().top - 100
		}, );

	}

});








// Start generate request 
	$('.alert-pop').click(function(){

		if ( $(this).attr("data-subject") ) {
			support_subject = $(this).attr("data-subject") ;
		}

		generate_request('step');

		setTimeout(function(){

		// Get the current date in the format 'YYYY-MM-DD'
		const currentDate = new Date().toISOString().split('T')[0];

		console.log(currentDate) ;

		// Set the min attribute of the date input to the current date
		document.getElementById('suitable-time').min = currentDate;

		},500);
		
    });

	$('.request_generate_top_btn').click(function(){
		var sent = $('.request_generate_top_btn').attr("sent");
		if(sent!=1){
		 generate_request('direct');
		}
		else{
			Swal.fire(
				'Already Saved',
				'Your Request is already saved.',
				'info'
			)				
		}
    });

$('.request-manual-audit-btn').click(function(){

	if ('<?=$request_sent?>' == 0) {
		// generate_request('direct');
		generate_request('step');

		support_subject = "Manual Audit Request" ;

		setTimeout(function(){

		// Get the current date in the format 'YYYY-MM-DD'
		const currentDate = new Date().toISOString().split('T')[0];

		console.log(currentDate) ;

		// Set the min attribute of the date input to the current date
		document.getElementById('suitable-time').min = currentDate;

		},500);
	}
	else {
		Swal.fire(
			'Already Sent',
			'Your Request is already sent to Website Speedy team.',
			'info'
		)
	}
	
});

async function generate_request(f) {

	var days = 7;
	var date = new Date();
	var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

	var afterDate = new Date(res);
	var month = afterDate.getUTCMonth() + 1; //months from 1-12
	var day = afterDate.getUTCDate();
	var year = afterDate.getUTCFullYear();

	// Plus 3 month after adding 7
	var dayss = 3;

	var ress = afterDate.setTime(date.getTime() + (dayss * 24 * 60 * 60 * 1000));
	var afterDatess = new Date(ress);
	var months = afterDatess.getUTCMonth() + 1; //months from 1-12
	var days = afterDatess.getUTCDate();
	var years = afterDatess.getUTCFullYear();

	var country_code_list = '<option value="">Select</option>' ;
    var timezone_list = '' ;

	$.ajax({
      type: "POST",
      url: "inc/get-timezone-country.php",
      data: {action:"get-timezone-country"},
      dataType: "json",
      async:false,
    }).done(function (data) {
        // console.log(data);
        for ( var i in data.country_codes ) {
            var country = data.country_codes[i] ;
            country_code_list += '<option value="+'+country.phonecode+'" '+country.selected+'>'+country.name+'</option>' ;
        }
       
        for ( var i in data.timezones ) {
            var timezone = data.timezones[i] ;
            timezone_list += '<option value="+'+timezone.label+'">'+timezone.label+'</option>' ;
        }

    }).fail(function(status){
        console.error("Unable to get country & timezones");
    }).always(function(){
    });

	console.log(from_tab);

	if (f == 'step') {

		const { value: formValues } = await Swal.fire({
			title: 'Please confirm your contact details and a Suitable time to get in touch',
			html: '<div class="support__form" ><div class="d__flex" ><span class="request-err d-none">Please Fill All Field.</span><div><label>Country Code</label><select id="country-code" class="form-control" required  name="country" >'+country_code_list+'</select></div><div><label>Contact Number</label><input id="contact" class="form-control" type="number" value="<?=$user_data['phone']?>" required></div></div><div class="full__col"><label>Email</label><input type="email"  class="form-control" id="email"  value="<?=$user_data['email']?>"></div><div class="full__col"><label>Time Zone</label><select class="form-control" id="tz" name="country" required>'+timezone_list+'</select></div><div class="col-12"><label>Suitable Time For Contact</label><input id="suitable-time" min="" type="date" class="form-control" required><div class="col-12"><div class="row"><div class="col-6">From : <input type="time" id="time-form" required></div><div class="col-6">To : <input type="time"  id="time-to" required></div></div></div></div><button class="mt-3 btn btn-success" id="request_form">Save</button>',
			focusConfirm: false,
			showCloseButton: true,
			allowOutsideClick: false,
			showConfirmButton: false,
			allowEscapeKey: false,
			//	closeOnClickOutside: false,
			preConfirm: () => {
				return false;
			}
		})

		if (formValues) {
			Swal.fire(JSON.stringify(formValues))
		}


	}
	else if (f == 'direct') {


		const { value: formValues } = await Swal.fire({
			title: 'Please confirm your contact details and a Suitable time to get in touch',
			html: '<div class="support__form" ><div class="d__flex" ><span class="request-err d-none">Please Fill All Field.</span><div><label>Country Code</label><select id="country-code-2" class="form-control" required  name="country" >'+country_code_list+'</select></div><div><label>Contact Number</label><input id="contact-2" class="form-control" type="number" value="<?=$user_data['phone']?>" required></div></div><div class="full__col"><label>Email</label><input type="email"  value="<?=$user_data['email']?>"  class="form-control" id="email-2"></div><div class="full__col"><label>Time Zone</label><select class="form-control" id="tz-2" name="country" required>'+timezone_list+'</select></div><div class="col-12"><label>Suitable Time For Contact</label><input id="suitable-time-2" type="date" class="form-control suitable-time-2" required><div class="col-12"><div class="row"><div class="col-6">From : <input type="time" id="time-form-2" required></div><div class="col-6">To : <input type="time"  id="time-to-2" required></div></div></div></div><button class="mt-3 btn btn-success" id="request_form_2">Save</button>',
			focusConfirm: false,
			showCloseButton: true,
			allowOutsideClick: false,
			showConfirmButton: false,
			allowEscapeKey: false,
			//	closeOnClickOutside: false,
			preConfirm: () => {
				return false;

			}
		})

		if (formValues) {
			Swal.fire(JSON.stringify(formValues))
		}
	}

}


// sky 
// $("#request_form").click(function(event){
$("body").on("click", "#request_form_2", function () {

	console.log("click call") ;

	var days = 7;
	var date = new Date();
	var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

	var afterDate = new Date(res);
	var month = afterDate.getUTCMonth() + 1; //months from 1-12
	var day = afterDate.getUTCDate();
	var year = afterDate.getUTCFullYear();

	// Plus 3 month after adding 7
	var dayss = 3;

	var ress = afterDate.setTime(date.getTime() + (dayss * 24 * 60 * 60 * 1000));
	var afterDatess = new Date(ress);
	var months = afterDatess.getUTCMonth() + 1; //months from 1-12
	var days = afterDatess.getUTCDate();
	var years = afterDatess.getUTCFullYear();

	var start_date = day + '-' + month + '-' + year;
	var end_date = days + '-' + months + '-' + years;

	// event.preventDefault();
	var cc = document.getElementById('country-code-2').value;
	var contact = document.getElementById('contact-2').value;
	var suitable = document.getElementById('suitable-time-2').value;
	var form = document.getElementById('time-form-2').value;
	var to = document.getElementById('time-to-2').value;
	var email = document.getElementById('email-2').value;
	var tz = document.getElementById('tz-2').value;

	var e = ValidateEmail(email);

	console.log("cc : "+cc) ;
	console.log("contact : "+contact) ;
	console.log("suitable : "+suitable) ;
	console.log("form : "+form) ;
	console.log("to : "+to) ;
	console.log("email : "+email) ;
	console.log("tz : "+tz) ;
	console.log("e : "+e) ;

	var data_subject = $(this).attr("data-subject") ;

	if (cc != "" && contact != "" && suitable != "" && form != "" && to != "" && e == 1 && tz != "") {
		// alert("saving");

		$.ajax({
			url: "generate-script-save-request.php",
			type: "POST",
			dataType: "json",
			data: {
				start_date: start_date,
				end_date: end_date,
				platform: "",
				traffic: "",
				email: "",
				country_code: cc,
				contact: contact,
				suitable: suitable,
				from: form,
				to: to,
				tz: tz,
				step: "1",
				script: '<?=serialize($script_url)?>',
				website_url: '<?=$website_data['website_url']?>',
				id: '<?=$_GET["project"]?>'

			},
			beforeSend:function(){
				$(".loader").show().html("<div class='loader_s rty'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Building your request. It might take few seconds<p><p><span class='auto-type'></span></p></div>");
				loaderest()
			},
			success: function (data) {

				console.log(data);

				if (data == 'saved') {

					var userEmailId = '<?=$row['email']?>';
					//123
					var message = `
						Need to install website speedy script and parameters for my website
						website_url : <?=$website_data['website_url']?> 
						Contact Number : ${cc} ${contact} 
						Email : ${email} 
						Time Zone : ${tz} 
						From  : ${form} 
						To : ${to} 
					`;				

				    $.ajax({
				        url: "<?=HOST_HELP_URL?>actions/user/support/create_ticket1",
				        type: "post",
				        data: {
				            'subject': data_subject,
				            'priority': 'medium',
				            'department': '2',
				            'userEmail': userEmailId,
				            'message': message,

				        },
				        success: function (response) {
				     	
				            var obj = $.parseJSON(response);
				            console.log(obj.data);

				            if (obj.status == 1) {

				            	$(".loader").hide().html("") ;

				                // save help.websitespeedy ticket details
				                $.ajax({
				                    url: "inc/help-support-tickets.php",
				                    type: "post",
				                    data: {
				                        action:"insert-support-ticket",
				                        website_id: <?=$project_id?>,
				                        ticket_type: 'Script Installtion Help Request',
				                        ticket_id: obj.data,
				                    },
				                    dataType: "JSON",
				                    success:function(response){ console.log(response) },
				                    error:function(response){ console.log(response) },
				                });


				                // old process used code 
			                    $(".request_form_2_sent").show();
			                    $(".support__form.request_form_2").hide();

			                    var view_ticket_url = "<?=HOST_HELP_URL?>user/support/tickets/all" ;
			                    if ( obj.data ) {
			                    	 view_ticket_url = "<?=HOST_HELP_URL?>user/support/ticket/"+obj.data ;
			                    }

								Swal.fire({
									title: 'Request sent',
									icon: 'success',
									html: 'We will get in touch soon, We will contact you via email or phone. Please make sure to check Spam folder in case you do not get an email in next 12 hours.',
									showDenyButton: true,
									showCancelButton: false,
									confirmButtonText: 'Thanks, I will wait for the team to contact me',
									denyButtonText: `I want try self Installation`,
								}).then((result) => {
									/* Read more about isConfirmed, isDenied below */
									if (result.isConfirmed) {
										$('.stepper_wrapper_con').addClass("disabled_div");
										$.ajax({
											type: "POST",
											url: "inc/mark_wait_for_team.php",
											data: {
												id: '<?=$user_id?>'
											},
											dataType: "json",
											encode: true,
										}).done(function (data) {
											// console.log(data);
										});

										Swal.fire({
											title: 'Request sent',
											icon: 'success',
											html: '<div class="pp_heading"><label> Great We will get in touch with you soon, in the mean time you can -</label></div>'+
												'<div class="form-group social_s_s"><a href="<?=HOST_HELP_URL?>" target="_blank">1. Explore our Knowledge base </a>' +
												'<a href="<?=HOST_URL?>why-website-speed-matters.php" target="_blank">2. Learn Why Speed Matters</a>' +
												'<span>3. Follow us on Social</span>' +
												'<div class="social__links">' +
												'<a href="//www.facebook.com/websitespeedy" target="_blank"><svg height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve"> <path style="fill:#385C8E;" d="M134.941,272.691h56.123v231.051c0,4.562,3.696,8.258,8.258,8.258h95.159  c4.562,0,8.258-3.696,8.258-8.258V273.78h64.519c4.195,0,7.725-3.148,8.204-7.315l9.799-85.061c0.269-2.34-0.472-4.684-2.038-6.44  c-1.567-1.757-3.81-2.763-6.164-2.763h-74.316V118.88c0-16.073,8.654-24.224,25.726-24.224c2.433,0,48.59,0,48.59,0  c4.562,0,8.258-3.698,8.258-8.258V8.319c0-4.562-3.696-8.258-8.258-8.258h-66.965C309.622,0.038,308.573,0,307.027,0  c-11.619,0-52.006,2.281-83.909,31.63c-35.348,32.524-30.434,71.465-29.26,78.217v62.352h-58.918c-4.562,0-8.258,3.696-8.258,8.258  v83.975C126.683,268.993,130.379,272.691,134.941,272.691z"/> </svg></a>' +
												'<a href="//www.instagram.com/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 32 32" fill="none"> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint0_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint1_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint2_radial_87_7153)"/> <path d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z" fill="white"/> <defs> <radialGradient id="paint0_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)"> <stop stop-color="#B13589"/> <stop offset="0.79309" stop-color="#C62F94"/> <stop offset="1" stop-color="#8A3AC8"/> </radialGradient> <radialGradient id="paint1_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)"> <stop stop-color="#E0E8B7"/> <stop offset="0.444662" stop-color="#FB8A2E"/> <stop offset="0.71474" stop-color="#E2425C"/> <stop offset="1" stop-color="#E2425C" stop-opacity="0"/> </radialGradient> <radialGradient id="paint2_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)"> <stop offset="0.156701" stop-color="#406ADC"/> <stop offset="0.467799" stop-color="#6A45BE"/> <stop offset="1" stop-color="#6A45BE" stop-opacity="0"/> </radialGradient> </defs> </svg></a>' +
												'<a href="//www.linkedin.com/company/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -2 44 44" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-702.000000, -265.000000)" fill="#007EBB"> <path d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z" id="LinkedIn"> </path> </g> </g> </svg></a>' +
												'<a href="//www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -7 48 48" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-200.000000, -368.000000)" fill="#CE1312"> <path d="M219.044,391.269916 L219.0425,377.687742 L232.0115,384.502244 L219.044,391.269916 Z M247.52,375.334163 C247.52,375.334163 247.0505,372.003199 245.612,370.536366 C243.7865,368.610299 241.7405,368.601235 240.803,368.489448 C234.086,368 224.0105,368 224.0105,368 L223.9895,368 C223.9895,368 213.914,368 207.197,368.489448 C206.258,368.601235 204.2135,368.610299 202.3865,370.536366 C200.948,372.003199 200.48,375.334163 200.48,375.334163 C200.48,375.334163 200,379.246723 200,383.157773 L200,386.82561 C200,390.73817 200.48,394.64922 200.48,394.64922 C200.48,394.64922 200.948,397.980184 202.3865,399.447016 C204.2135,401.373084 206.612,401.312658 207.68,401.513574 C211.52,401.885191 224,402 224,402 C224,402 234.086,401.984894 240.803,401.495446 C241.7405,401.382148 243.7865,401.373084 245.612,399.447016 C247.0505,397.980184 247.52,394.64922 247.52,394.64922 C247.52,394.64922 248,390.73817 248,386.82561 L248,383.157773 C248,379.246723 247.52,375.334163 247.52,375.334163 L247.52,375.334163 Z" id="Youtube"> </path> </g> </g> </svg></a>' +
												'</div></div>' +
												`<div class="btns_flex"><a target="blank" class="button" href="<?=HOST_HELP_URL?>user/support/tickets/all">View Ticket</a><a class="button" href="<?=HOST_URL?>adminpannel/project-dashboard.php<?=$get_project;?>">Dashboard</a>
												<a href="/adminpannel/add-website.php"class="button" >Add Website</a></div>`,
											showCloseButton: true,
											showDenyButton: false,
											showCancelButton: false,
											showConfirmButton: false,
											confirmButtonText: `View Ticket`,
											denyButtonText: `Go To Dashboard`,
											cancelButtonText: `Add New Website`
										}).then((result) => {
											if (result.isConfirmed) {
												// View Ticket
												window.open(view_ticket_url, '_blank');
											}
											else if (result.isDenied) {
												// Go To Dashboard
												window.location.href = window.location.origin+"/adminpannel/project-dashboard.php"+window.location.search ;
											}
											else if (result.isDismissed && result.dismiss == "cancel") {
												// Add New Website
												window.location.href = window.location.origin+"/adminpannel/add-website.php" ;
											}
										});

									}
									else if (result.isDenied) {

										$('html, body').animate({
											scrollTop: $("#step1").offset().top - 100
										}, 1);

									}
								})


			                    $(".alert-pop").addClass("request_sent").removeClass("alert-pop").attr("disabled", true);

								//  $("#step6").removeClass("d-none");
								//  $("#step6").addClass("complited"); 
								//  $(".top-step-6").click();
								$('.request_generate_top_btn').attr("sent", "1");
								$('.request_generate_top_btn').attr("sent", "1");


				            }
				            else {
				                console.error(obj.message) ;
				                $(".loader").hide().html("") ;
				            }
				        }
				    });



				}
				else if (data == 2) {
					$(".loader").hide().html("") ;
					Swal.fire(
						'Already Saved',
						'Your Request is already saved.',
						'info'
					)
				}
				else {
					$(".loader").hide().html("") ;
					Swal.fire(
						'Opps..',
						'Something went wrong.',
						'error'
					)
				}

			}
		});


	}
	else {
		$(".request-err").removeClass("d-none");
		setTimeout(function () {
			$(".request-err").addClass("d-none");
		}, 4000);
		// alert("please feel all fields");
	}
});


// $("#request_form").click(function(event){
$("body").on("click", "#request_form", function () {
	var days = 7;
	var date = new Date();
	var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

	var afterDate = new Date(res);
	var month = afterDate.getUTCMonth() + 1; //months from 1-12
	var day = afterDate.getUTCDate();
	var year = afterDate.getUTCFullYear();

	// Plus 3 month after adding 7
	var dayss = 3;

	var ress = afterDate.setTime(date.getTime() + (dayss * 24 * 60 * 60 * 1000));
	var afterDatess = new Date(ress);
	var months = afterDatess.getUTCMonth() + 1; //months from 1-12
	var days = afterDatess.getUTCDate();
	var years = afterDatess.getUTCFullYear();

	var start_date = day + '-' + month + '-' + year;
	var end_date = days + '-' + months + '-' + years;

	// event.preventDefault();
	var cc = document.getElementById('country-code').value;
	var contact = document.getElementById('contact').value;
	var suitable = document.getElementById('suitable-time').value;
	var form = document.getElementById('time-form').value;
	var to = document.getElementById('time-to').value;
	var email = document.getElementById('email').value;
	var tz = document.getElementById('tz').value;

	var e = ValidateEmail(email);

	if (cc != "" && contact != "" && suitable != "" && form != "" && to != "" && e == 1 && tz != "") {
		// alert("saving");

		var from_tab_btn = 3;
		if (from_tab == "step3") {
			from_tab_btn = 2;
		}


		$.ajax({
			url: "generate-script-save-request.php",
			type: "POST",
			dataType: "json",
			data: {
				start_date: start_date,
				end_date: end_date,
				platform: "",
				traffic: "",
				email: email,
				country_code: cc,
				contact: contact,
				suitable: suitable,
				from: form,
				to: to,
				tz: tz,
				step: from_tab_btn,
				script: '<?=serialize($script_url)?>',
				website_url: '<?=$website_data['
				website_url ']?>',
				id: '<?=$_GET["project"]?>'

			},
			beforeSend: function () {
				$(".loader").show().html("<div class='loader_s rty'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Building your request. It might take few seconds</p><p><span class='auto-type'></span></p></div>");
				loaderest()
			},
			success: function (data) {
				console.log(data);
				if (data == 'saved') {

					var userEmailId = '<?=$row['email']?>';
					var message = `
						Need to install website speedy script and parameters for my website
						website_url : <?=$website_data['website_url']?> 
						Contact Number : ${cc} ${contact} 
						Email : ${email} 
						Time Zone : ${tz} 
						From  : ${form} 
						To : ${to} 
					`;
					$.ajax({
						url: "<?=HOST_HELP_URL?>actions/user/support/create_ticket1",
						type: "post",
						data: {
							'subject': support_subject,
							'priority': 'medium',
							'department': '2',
							'userEmail': userEmailId,
							'message': message,
						},
						success: function (response) {
							console.log(response);
							var obj = $.parseJSON(response);
							console.log(obj.data);

							if (obj.status == 1) {
								$(".loader").hide();

								// save help.websitespeedy ticket details
								$.ajax({
									url: "inc/help-support-tickets.php",
									type: "post",
									data: {
										action: "insert-support-ticket",
										website_id: <?=$project_id?> ,
										ticket_type: support_subject,
										ticket_id: obj.data
									},
									dataType: "JSON",
									success: function (response) {
										console.log(response) ;
										// var object = $.parseJSON(responses);
										// if(object.status=='success'){
										// 	$('#ticketId').html(object.message.ticket_id)
										// }

										if ( support_subject == "Manual Audit By Team" ) {
											$(".step4-manual-audit-sent").show();
											$(".step4-manual-audit-sent a").attr("href","<?=HOST_HELP_URL?>user/support/ticket/"+obj.data);
											$('#ticketId').html(obj.data)
											$(".step4-manual-audit-request").hide();
										}

									},
									error: function (response) {
										console.log(response)
									},
								});


								<?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace') ? 'installation_steps(3);' : 'installation_steps(6);' ?>

								var view_ticket_url = "<?=HOST_HELP_URL?>user/support/tickets/all";
								if (obj.data) {
									var view_ticket_url = "<?=HOST_HELP_URL?>user/support/ticket/" + obj.data;
								}

								Swal.fire({
									title: 'Request sent',
									icon: 'success',
									html: '<div class="pp_heading"><label>Great We will get in touch with you soon, We will contact you via email or phone. Please make sure to check Spam folder in case you do not get an email in next 12 hours.' +
										'In the mean time you can - </label></div>' +
										'<div class="form-group social_s_s" ><a href="<?=HOST_HELP_URL?>" target="_blank">1. Explore our Knowledge base </a>' +
										'<a href="<?=HOST_URL?>why-website-speed-matters.php" target="_blank">2. Learn Why Speed Matters</a>' +
										'<span>3. Follow us on Social</span>' +
										'<div class="social__links">' +
										'<a href="//www.facebook.com/websitespeedy" target="_blank"><svg height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve"> <path style="fill:#385C8E;" d="M134.941,272.691h56.123v231.051c0,4.562,3.696,8.258,8.258,8.258h95.159  c4.562,0,8.258-3.696,8.258-8.258V273.78h64.519c4.195,0,7.725-3.148,8.204-7.315l9.799-85.061c0.269-2.34-0.472-4.684-2.038-6.44  c-1.567-1.757-3.81-2.763-6.164-2.763h-74.316V118.88c0-16.073,8.654-24.224,25.726-24.224c2.433,0,48.59,0,48.59,0  c4.562,0,8.258-3.698,8.258-8.258V8.319c0-4.562-3.696-8.258-8.258-8.258h-66.965C309.622,0.038,308.573,0,307.027,0  c-11.619,0-52.006,2.281-83.909,31.63c-35.348,32.524-30.434,71.465-29.26,78.217v62.352h-58.918c-4.562,0-8.258,3.696-8.258,8.258  v83.975C126.683,268.993,130.379,272.691,134.941,272.691z"/> </svg></a>' +
										'<a href="//www.instagram.com/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 32 32" fill="none"> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint0_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint1_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint2_radial_87_7153)"/> <path d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z" fill="white"/> <defs> <radialGradient id="paint0_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)"> <stop stop-color="#B13589"/> <stop offset="0.79309" stop-color="#C62F94"/> <stop offset="1" stop-color="#8A3AC8"/> </radialGradient> <radialGradient id="paint1_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)"> <stop stop-color="#E0E8B7"/> <stop offset="0.444662" stop-color="#FB8A2E"/> <stop offset="0.71474" stop-color="#E2425C"/> <stop offset="1" stop-color="#E2425C" stop-opacity="0"/> </radialGradient> <radialGradient id="paint2_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)"> <stop offset="0.156701" stop-color="#406ADC"/> <stop offset="0.467799" stop-color="#6A45BE"/> <stop offset="1" stop-color="#6A45BE" stop-opacity="0"/> </radialGradient> </defs> </svg></a>' +
										'<a href="//www.linkedin.com/company/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -2 44 44" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-702.000000, -265.000000)" fill="#007EBB"> <path d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z" id="LinkedIn"> </path> </g> </g> </svg></a>' +
										'<a href="//www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -7 48 48" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-200.000000, -368.000000)" fill="#CE1312"> <path d="M219.044,391.269916 L219.0425,377.687742 L232.0115,384.502244 L219.044,391.269916 Z M247.52,375.334163 C247.52,375.334163 247.0505,372.003199 245.612,370.536366 C243.7865,368.610299 241.7405,368.601235 240.803,368.489448 C234.086,368 224.0105,368 224.0105,368 L223.9895,368 C223.9895,368 213.914,368 207.197,368.489448 C206.258,368.601235 204.2135,368.610299 202.3865,370.536366 C200.948,372.003199 200.48,375.334163 200.48,375.334163 C200.48,375.334163 200,379.246723 200,383.157773 L200,386.82561 C200,390.73817 200.48,394.64922 200.48,394.64922 C200.48,394.64922 200.948,397.980184 202.3865,399.447016 C204.2135,401.373084 206.612,401.312658 207.68,401.513574 C211.52,401.885191 224,402 224,402 C224,402 234.086,401.984894 240.803,401.495446 C241.7405,401.382148 243.7865,401.373084 245.612,399.447016 C247.0505,397.980184 247.52,394.64922 247.52,394.64922 C247.52,394.64922 248,390.73817 248,386.82561 L248,383.157773 C248,379.246723 247.52,375.334163 247.52,375.334163 L247.52,375.334163 Z" id="Youtube"> </path> </g> </g> </svg></a>' +
										'</div></div>' +
										`<div class="btns_flex"><a target="blank" class="button" href="<?=HOST_HELP_URL?>user/support/tickets/all">View Ticket</a><a class="button" href="<?=HOST_URL?>adminpannel/project-dashboard.php<?=$get_project;?>">Dashboard</a>
												<a href="/adminpannel/add-website.php"class="button" >Add Website</a></div>`,
											showCloseButton: true,
											showDenyButton: false,
											showCancelButton: false,
											showConfirmButton: false,
											confirmButtonText: `View Ticket`,
											denyButtonText: `Go To Dashboard`,
											cancelButtonText: `Add New Website`
								}).then((result) => {
									if (result.isConfirmed) {
										// View Ticket
										window.open(view_ticket_url, '_blank');
									}
									else if (result.isDenied) {
										// Go To Dashboard
										window.location.href = window.location.origin+"/adminpannel/project-dashboard.php"+window.location.search ;
									}
									else if (result.isDismissed && result.dismiss == "cancel") {
										// Add New Website
										window.location.href = window.location.origin+"/adminpannel/add-website.php" ;
									}
								});

								$(".alert-pop").addClass("request_sent").removeClass("alert-pop").attr("disabled", true);

								<?= (strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace') ? '' : '       
								$("#step6").removeClass("d-none");
								$("#step6").addClass("complited");
								$(".top-step-6").click();
								'?> 


							}
							else {
								$(".loader").hide();
								console.error(obj.message)
							}
						},
						error: function () {
							$(".loader").hide();
						},
						complete: function () {
							var support_subject = "Team Script Installtion Request";
						}

					});

				}
				else if (data == 2) {
					$(".loader").show().html("");
					Swal.fire(
						'Already Saved',
						'Your Request is already saved.',
						'info'
					)
				}
				else {
					$(".loader").show().html("");
					Swal.fire(
						'Opps..',
						'Something went wrong.',
						'error'
					)
				}

			},
			error: function (argument) {
				$(".loader").show().html("");
			}
		});


	}
	else {
		$(".request-err").removeClass("d-none");
		setTimeout(function () {
			$(".request-err").addClass("d-none");

		}, 4000);
		// alert("please feel all fields");
	}

});



	function ValidateEmail(mail){
      	// if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
		if(mail.indexOf("@") >0 && mail.indexOf(".") >0 ){
			return 1;
		}
			
			return 0;
	}


  $(".request_sent").click(function(){

		Swal.fire(
			'Already Sent',
			'Your Request is already sent to Website Speedy team.',
			'info'
		)		
  });

// End generate request 

  });




	var tm = "";
	if(localStorage.getItem("waitTime")!=null){
		console.log('hello');
	tm = "start";
	showTimer();

	}

//  $(".inst_speed_new-container").addClass("d-none");  //123

    var countdown = document.getElementById("countdown-"); // get tag element
	countdown.style.display = "none";

	var target_date;
	var days, hours, minutes, seconds; // variables for time units

	var countdown; // get tag element
	var counterInterval;

	function showTimer(){	

		tm = "start";
		var old = localStorage.getItem("waitTime");
		var old_d = new Date(old);
		var old_date = new Date(old_d.getTime() + 0.5 *60000);

	
		var date = new Date();

		var Time = (old_date-date)/30000;


		target_date = new Date(date.getTime() + Time *30000);


		countdown = document.getElementById("countdown-"); // get tag element
		countdown.style.display = "block";
		console.log("thq="+tm);
		clearInterval(counterInterval);
		counterInterval = setInterval(function() {
			getCountdown();
		}, 1000);

	}


	getCountdown();

	 counterInterval = setInterval(function() {
		getCountdown();
	}, 1000);



	function getCountdown() {
		// console.log("th="+tm);
		if(tm=="start"){
		// find the amount of "seconds" between now and target
		var current_date = new Date().getTime();
		var seconds_left = (target_date - current_date) / 1000;

		days = pad(parseInt(seconds_left / 86400));
		seconds_left = seconds_left % 86400;

		hours = pad(parseInt(seconds_left / 3600));
		seconds_left = seconds_left % 3600;

		minutes = pad(parseInt(seconds_left / 60));
		seconds = pad(parseInt(seconds_left % 60));

		countdown.style.display = "block";
		// format countdown string + set tag value
		// console.log("days : " + days + " hours : " + hours + " minutes : " + minutes + " seconds : " + seconds ) ;
		var s = minutes + ":" + seconds;
		// var s =   seconds;
		if(!s.includes("NaN")){
			countdown.innerHTML = s;
		}

		if ((parseInt(minutes) <= 0) && (parseInt(seconds) <= 0)) {
			clearInterval(counterInterval);
			countdown.innerHTML = "00:00";
			

			console.log("Reached");
			$(".new-analysee").show();
			  var sp = '<?=$new_speed?>';
			  // console.log(sp);
			//    if(sp==0){
			//  	$(".inst_speed_new-container").addClass("d-none");
			//    }
			//    else{
			//    	$(".inst_speed_new-container").removeClass("d-none");
			//    }
			//  $(".inst_speed_new-container").removeClass("d-none");
		}
	 }
	}

	function pad(n) {
		return (n < 10 ? '0' : '') + n;
	}
</script>


<script>
    (function($) {

$.fn.niceSelect = function(method) {

    // Methods
    if (typeof method == 'string') {
        if (method == 'update') {
            this.each(function() {
                var $select = $(this);
                var $dropdown = $(this).next('.nice-select');
                var open = $dropdown.hasClass('open');

                if ($dropdown.length) {
                    $dropdown.remove();
                    create_nice_select($select);

                    if (open) {
                        $select.next().trigger('click');
                    }
                }
            });
        } else if (method == 'destroy') {
            this.each(function() {
                var $select = $(this);
                var $dropdown = $(this).next('.nice-select');

                if ($dropdown.length) {
                    $dropdown.remove();
                    $select.css('display', '');
                }
            });
            if ($('.nice-select').length == 0) {
                $(document).off('.nice_select');
            }
        } else {
            console.log('Method "' + method + '" does not exist.')
        }
        return this;
    }

    // Hide native select
    this.hide();

    // Create custom markup
    this.each(function() {
        var $select = $(this);

        if (!$select.next().hasClass('nice-select')) {
            create_nice_select($select);
        }
    });

    function create_nice_select($select) {
        $select.after($('<div></div>')
            .addClass('nice-select')
            .addClass($select.attr('class') || '')
            .addClass($select.attr('disabled') ? 'disabled' : '')
            .addClass($select.attr('multiple') ? 'has-multiple' : '')
            .attr('tabindex', $select.attr('disabled') ? null : '0')
            .html($select.attr('multiple') ? '<span class="multiple-options"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Search"/></div><ul class="list"></ul>' : '<span class="current"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Search"/></div><ul class="list"></ul>')
        );

        var $dropdown = $select.next();
        var $options = $select.find('option');
        if ($select.attr('multiple')) {
            var $selected = $select.find('option:selected');
            var $selected_html = '';
            $selected.each(function() {
                $selected_option = $(this);
                $selected_text = $selected_option.data('display') ||  $selected_option.text();

                if (!$selected_option.val()) {
                    return;
                }

                $selected_html += '<span class="current">' + $selected_text + '</span>';
            });
            $select_placeholder = $select.data('js-placeholder') || $select.attr('js-placeholder');
            $select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
            $selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
            $dropdown.find('.multiple-options').html($selected_html);
        } else {
            var $selected = $select.find('option:selected');
            $dropdown.find('.current').html($selected.data('display') ||  $selected.text());
        }


        $options.each(function(i) {
            var $option = $(this);
            var display = $option.data('display');

            $dropdown.find('ul').append($('<li></li>')
                .attr('data-value', $option.val())
                .attr('data-display', (display || null))
                .addClass('option' +
                    ($option.is(':selected') ? ' selected' : '') +
                    ($option.is(':disabled') ? ' disabled' : ''))
                .html($option.text())
            );
        });
    }

    /* Event listeners */

    // Unbind existing events in case that the plugin has been initialized before
    $(document).off('.nice_select');

    // Open/close
    $(document).on('click.nice_select', '.nice-select', function(event) {
        var $dropdown = $(this);

        $('.nice-select').not($dropdown).removeClass('open');
        $dropdown.toggleClass('open');

        if ($dropdown.hasClass('open')) {
            $dropdown.find('.option');
            $dropdown.find('.nice-select-search').val('');
            $dropdown.find('.nice-select-search').focus();
            $dropdown.find('.focus').removeClass('focus');
            $dropdown.find('.selected').addClass('focus');
            $dropdown.find('ul li').show();
        } else {
            $dropdown.focus();
        }
    });

    $(document).on('click', '.nice-select-search-box', function(event) {
        event.stopPropagation();
        return false;
    });
    $(document).on('keyup.nice-select-search', '.nice-select', function() {
        var $self = $(this);
        var $text = $self.find('.nice-select-search').val();
        var $options = $self.find('ul li');
        if ($text == '')
            $options.show();
        else if ($self.hasClass('open')) {
            $text = $text.toLowerCase();
            var $matchReg = new RegExp($text);
            if (0 < $options.length) {
                $options.each(function() {
                    var $this = $(this);
                    var $optionText = $this.text().toLowerCase();
                    var $matchCheck = $matchReg.test($optionText);
                    $matchCheck ? $this.show() : $this.hide();
                })
            } else {
                $options.show();
            }
        }
        $self.find('.option'),
            $self.find('.focus').removeClass('focus'),
            $self.find('.selected').addClass('focus');
    });

    // Close when clicking outside
    $(document).on('click.nice_select', function(event) {
        if ($(event.target).closest('.nice-select').length === 0) {
            $('.nice-select').removeClass('open').find('.option');
        }
    });

    // Option click
    $(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function(event) {
        
        var $option = $(this);
        var $dropdown = $option.closest('.nice-select');
        if ($dropdown.hasClass('has-multiple')) {
            if ($option.hasClass('selected')) {
                $option.removeClass('selected');
            } else {
                $option.addClass('selected');
            }
            $selected_html = '';
            $selected_values = [];
            $dropdown.find('.selected').each(function() {
                $selected_option = $(this);
                var attrValue = $selected_option.data('value');
                var text = $selected_option.data('display') ||  $selected_option.text();
                $selected_html += (`<span class="current" data-id=${attrValue}> ${text} <span class="remove">X</span></span>`);
                $selected_values.push($selected_option.data('value'));
            });
            $select_placeholder = $dropdown.prev('select').data('js-placeholder') ||                                   $dropdown.prev('select').attr('js-placeholder');
            $select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
            $selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
            $dropdown.find('.multiple-options').html($selected_html);
            $dropdown.prev('select').val($selected_values).trigger('change');
        } else {
            $dropdown.find('.selected').removeClass('selected');
            $option.addClass('selected');
            var text = $option.data('display') || $option.text();
            $dropdown.find('.current').text(text);
            $dropdown.prev('select').val($option.data('value')).trigger('change');
        }
      console.log($('.mySelect').val())
    });
  //---------remove item
  $(document).on('click','.remove', function(){
    var $dropdown = $(this).parents('.nice-select');
    var clickedId = $(this).parent().data('id')
    $dropdown.find('.list li').each(function(index,item){
      if(clickedId == $(item).attr('data-value')) {
        $(item).removeClass('selected')
      }
    })
    $selected_values.forEach(function(item, index, object) {
      if (item === clickedId) {
        object.splice(index, 1);
      }
    });
    $(this).parent().remove();
    console.log($('.mySelect').val())
   })
  
    // Keyboard events
    $(document).on('keydown.nice_select', '.nice-select', function(event) {
        var $dropdown = $(this);
        var $focused_option = $($dropdown.find('.focus') || $dropdown.find('.list .option.selected'));

        // Space or Enter
        if (event.keyCode == 32 || event.keyCode == 13) {
            if ($dropdown.hasClass('open')) {
                $focused_option.trigger('click');
            } else {
                $dropdown.trigger('click');
            }
            return false;
            // Down
        } else if (event.keyCode == 40) {
            if (!$dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            } else {
                var $next = $focused_option.nextAll('.option:not(.disabled)').first();
                if ($next.length > 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $next.addClass('focus');
                }
            }
            return false;
            // Up
        } else if (event.keyCode == 38) {
            if (!$dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            } else {
                var $prev = $focused_option.prevAll('.option:not(.disabled)').first();
                if ($prev.length > 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $prev.addClass('focus');
                }
            }
            return false;
            // Esc
        } else if (event.keyCode == 27) {
            if ($dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            }
            // Tab
        } else if (event.keyCode == 9) {
            if ($dropdown.hasClass('open')) {
                return false;
            }
        }
    });

    // Detect CSS pointer-events support, for IE <= 10. From Modernizr.
    var style = document.createElement('a').style;
    style.cssText = 'pointer-events:auto';
    if (style.pointerEvents !== 'auto') {
        $('html').addClass('no-csspointerevents');
    }

    return this;

};

}(jQuery));


$('.request_generate_top_btn').on('click', function(){
		setTimeout(() => {
			$('#country-code').niceSelect();
    		$('#tz').niceSelect();

			$('#country-code-2').niceSelect();
    		$('#tz-2').niceSelect();

		}, 100);
})

$('.i-need-help').on('click', function(){
		setTimeout(() => {
			$('#country-code').niceSelect();
    		$('#tz').niceSelect();

			$('#country-code-2').niceSelect();
    		$('#tz-2').niceSelect();
		}, 1500);
})

$('.alert-pop').on('click', function(){
		setTimeout(() => {
			$('#country-code').niceSelect();
    		$('#tz').niceSelect();

			$('#country-code-2').niceSelect();
    		$('#tz-2').niceSelect();
		}, 100);
})


$('.request_form_2').find('#country-code').niceSelect();
$('.request_form_2').find('#tz').niceSelect();

// $('.request_form_2').find('#country-code-2').niceSelect();
// $('.request_form_2').find('#tz-2').niceSelect();

</script>

<script>
    window.addEventListener("load", function() {
	// store tabs variable
	var myTabs = document.querySelectorAll("ul.nav-tabs > li");
		function myTabClicks(tabClickEvent) {
				for (var i = 0; i < myTabs.length; i++) {
					myTabs[i].classList.remove("active-tab");
				}
				var clickedTab = tabClickEvent.currentTarget;
				clickedTab.classList.add("active-tab");
				tabClickEvent.preventDefault();
				var myContentPanes = document.querySelectorAll(".tab-pane");
				for (i = 0; i < myContentPanes.length; i++) {
					myContentPanes[i].classList.remove("active-tab");
				}
				var anchorReference = tabClickEvent.target;
				var activePaneId = anchorReference.getAttribute("href");
				var activePane = document.querySelector(activePaneId);
				activePane.classList.add("active-tab");
			}
			for (i = 0; i < myTabs.length; i++) {
				myTabs[i].addEventListener("click", myTabClicks)
			}
		});


		window.onscroll = function() {myFunction()};

		var alert = document.getElementsByClassName("alert-status");
		// var content = document.getElementsByClassName('script_Icontainer');
		// var content = document.getElementsByClassName('script_I ');
		var sticky = alert[0].offsetTop;
		var sidebar = document.querySelector('.container_sld');
		var tabWrap = document.querySelector('.tabber__wrapper');

		function myFunction() {
		if (window.pageYOffset > sticky) {
			alert[0].classList.add("sticky");
			sidebar.classList.add('sticky');
			// tabWrap.classList.add('padd-left');
			// content[0].classList.add('top-padding')  
		} else {
			alert[0].classList.remove("sticky");
			sidebar.classList.remove('sticky');
			// tabWrap.classList.remove('padd-left');
			// content[0].classList.remove('top-padding')  
		}
		}

</script>


<script type="text/javascript">

/*** FOR Loader remove karna hai jab URL 2 and 3 dalte hai. Ajax ka ulternate dekhna hai. ***/ 
var additional1_url_ajax = additional2_url_ajax = 0 ;
var additional_url_ajaxInterval = '' ;

function beforeUnloadHandler(event) {
	var confirmationMessage = 'Are you sure you want to leave?';
	// Cancel the default event to suppress the default browser message
	event.preventDefault();

	Swal.fire(
		'Wait...',
		'Speed is currently getting from google api.',
		'info'
	)

	// For some older browsers
	event.returnValue = confirmationMessage;
}

function stopReloadingInterval() {
	if ( additional1_url_ajax == 0 && additional2_url_ajax == 0 ) {
		window.removeEventListener('beforeunload', beforeUnloadHandler);
		clearInterval(additional_url_ajaxInterval) ;
	}
	else {

	  // window.addEventListener('beforeunload', function (event) {
	  // 	var confirmationMessage = 'Are you sure you want to leave?';
	  //   // Cancel the default event to suppress the default browser message
	  //   event.preventDefault();
	    
	  //   // For some older browsers
	  //   event.returnValue = confirmationMessage;
	  // });

	  window.addEventListener('beforeunload', beforeUnloadHandler);

	}
}
/*** END Loader remove karna hai jab URL 2 and 3 dalte hai. Ajax ka ulternate dekhna hai. ***/ 

function installation_steps(step, satisfy_val = " ", method = null) {
	var id = '<?=$project_ids?>';
	var satisfy = satisfy_val;
	// console.log(satisfy);
	var req = $.ajax({
		url: 'inc/update_installation_steps.php',
		method: 'POST',
		dataType: 'JSON',
		async: false,
		data: {
			"id": id,
			"step": step,
			"satisfy": satisfy,
			"method": method
		}
	});

}

function verify_script(url, script) {

	var req = $.ajax({
		url: 'inc/verify_script.php',
		method: 'POST',
		dataType: 'JSON',
		// async : false ,
		data: {
			"url": url,
			"script": script
		}
	});

	req.done(function (reponse) {

		if (reponse == 0) {
			$(".loader").hide();
	
			Swal.fire({
				// icon: 'error',
				// title: 'Cannot Verify WebsiteSpeedy Script',
				showCloseButton: true,
				allowOutsideClick: false,
				allowEscapeKey: false,
				showCancelButton: false,
				showConfirmButton: false,
				// text: 'Provided script is not added on the website "<?=parse_url($website_data['website_url'])['host'];?>". please check and try again.',
				html: '<div class="verify_script"><h3>Cannot Verify WebsiteSpeedy Script</h3><div class="verify-icon-popup"><img src="img/website speedy.png" /></div>' +
					'<p>We were unable to verify the Script on your website. Please check and try again.</p>' +
					'<p>Please click “Manual Verification” button below if you have added the script to your website.</p>' +
					'<button class="btn btn-primary manualbtnverify" >Manual Verification</button>' +
					'<p class="or_s">or</p>' +
					'<p>Contact support if you need help with adding script.</p>' +
					'<a class="btn btn-primary manualbtnverify" target="_blank" href="<?=HOST_HELP_URL?>user/support/create_ticket">Contact Support </a></div>'


			});


		}
		else {

			$(".loader").hide();

			Swal.fire({
				title: 'Great!',
				icon: 'success',
				text: 'Congrats you have successfully added the Website Speedy Script to your website.',
				showDenyButton: false,
				showCancelButton: false,
				allowOutsideClick: false,
				allowEscapeKey: false,
				confirmButtonText: 'Ok',
				denyButtonText: `Don't save`,
			}).then((result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {

					$("#step3").removeClass("d-none");
					$("#step3").addClass("complited");
					$(".top-step-3").click();
					localStorage.setItem("waitTime", new Date());
					
					$(".new-analysee").hide();
					$(".inst_speed_new-container").hide();
					setTimeout(function () {
						// showTimer();
						// $(".new-analyze").show();
						showTimer();
						// setTimeout(function(){ $('html, body').animate({ scrollTop: $("#step3").offset().top }, 500); },500) ;
					}, 500);
					installation_steps(3);
					$(".step-2-reanalyse.reanalyze-btn-").hide();
					$(".reanalyzeold").show();
				}
			})


		}

	});

	req.fail(function (reponse) {
		console.log(reponse);
	});

}

function manage_speed(id) {

	$.ajax({
		type: "POST",
		url: "inc/manage_speed.php",
		data: {
			id: id,
			speedtype: speedtype
		},
		dataType: "json",
		encode: true,
	}).done(function (data) {
		// console.log(data);
	});

	if (speedtype == "new") {
		$(".inst_speed_new-container").show();
	}
	else if (speedtype == "newUrl2") {
		$(".url2_inst_speed_new-container").show();
	}
}

	


/*****************************/

var getting_no_speedy1 = 0 ;
var getting_no_speedy2 = 0 ;

$(document).ready(function(){



	$(".continue-to-step-2").click(function () {



		$(".loader").show().html("<div class='loader_s 123'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
        loaderest()
		setTimeout(function () {
			$(".loader").hide();
			$("#step2").removeClass("d-none");
			$("#step2").addClass("complited");
			$(".top-step-2").click();

			setTimeout(function(){ $('html, body').animate({ scrollTop: $("#step2").offset().top }, 500); },500) ;
		}, 1500);

		installation_steps(2);

	});


	$(".verification_btn").click(function () {

		$(".loader").show().html("<div class='loader_s 234'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>-0.1 seconds of loading can result in +8% conversions.</p> <p><span class='auto-type'></span></p></div>");
        loaderest()
		var url = $(this).attr("data-vari");
		var script = $(this).attr("urls");
		var id = $(this).attr("data-id");


		verify_script(url, script);

		// manage_speed(id);
	});



	$(".goto-4").click(function(){
		var plaform = $(this).attr("data-plaform") ;
		var satisfy = $(this).attr("data-satisfy") ;

		if ( plaform == 1 ) {
			$("#trusted").toggle();
			installation_steps(3,satisfy);
		}
		else {

			$("#step4").removeClass("d-none");
			$("#step4").addClass("complited"); 
			$(".top-step-4").click(); 	
			installation_steps(4,satisfy);
		}
	});


	$(".step5completeIns").click(function () {

		from_tab = $(this).parents(".form-tab").attr("id");

		var next = $(this).attr("data-next");

		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})

		swalWithBootstrapButtons.fire({
			title: 'You can improve speed even further on step 6, Please complete installation to reach step 6 ',
			showCancelButton: false,
			showCloseButton: true,
			reverseButtons: true,
			confirmButtonText: 'Go to next step',
		}).then((result) => {

			console.log(result) ;

			if (result.isConfirmed) {

				$("#step"+next).removeClass("d-none");
				$("#step"+next).addClass("complited"); 
				$(".top-step-"+next).click(); 	
				installation_steps(next);
			}

		})

	});

	function extractDomain(url) {
		try {
			const domain = new URL(url).hostname;
			return domain.startsWith('www.') ? domain.replace('www.', '') : domain;
		}
		catch (error) {
			console.error('Invalid URL:', error.message);
			return null;
		}
	}


	function compareAndStoreSubdomain(previousUrl, newUrl) {
		const previousDomain = extractDomain(previousUrl);
		const newDomain = extractDomain(newUrl);

		if ( previousDomain && newDomain && (previousDomain == newDomain) ) {
			return true;
		}
		else {
			return false;
		}
	}



	$(document).on('click','.goto-next-step',function(){
		var next = $(this).attr("data-next") ;

		// $(".loader").show().html("<div class='loader_s'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player></div>");

		installation_steps(next);

		setTimeout(function () {
			$(".loader").hide();
			$("#step"+next).removeClass("d-none");
			$("#step"+next).addClass("complited"); 
			$(".top-step-"+next).click(); 	
			// $('html, body').animate({ scrollTop: $("#step"+next).offset().top - 230 }, 1);
			setTimeout(function(){ 
				$('html, body').animate({ scrollTop: $("#step"+next).offset().top - 230 }, 1);
			},500) ;
		}, 1000);
		$('.swal2-container ').css('display','none');      		
		
	});
	$(document).on('click','.goto-next-optimize',function(){

	    const swalWithBootstrapButtons = Swal.mixin({
	        customClass: {
	            confirmButton: 'btn btn-success',
	            cancelButton: 'btn btn-danger'
	        },
	        buttonsStyling: false
	    });

	    swalWithBootstrapButtons.fire({
	        title: '<div class="icon_txt"><img src="//websitespeedycdn.b-cdn.net/speedyweb/images/review_png_s.png" style="max-width:60px; margin:0 auto 20px;display:block;">Review & Feedback</div>',
	        html: `<form id="radioForm" class="review-feedback-form">
	        		<div class="review_popup">
						<span id="validationErr" style="display:none;color: red;font-weight: 500;">Please fill all field </span>
						<span id="submitFeedback"> </span>
						<div id="satisfiedDiv" class="satisfiediv ratingForm">
							<h5>1- Are you satisfied with the updated speed ?</h5>
							<label><input type="radio" name="answer" value="yes">Yes</label>
							<label><input type="radio" name="answer" value="no">No</label>
							<p style="margin:30px 0 0;">Once you complete the feedback process, you can go to step 4 and access further speed optimization options</p>
						</div>
					</div>

					<div class="faq feedback-review-form">
						<div id="ratingDiv" class="rating_s ratingForm" style="display:none">
							<h5>2- How would you rate your experience?</h5>
							<fieldset>
								<!-- <legend>scale of 1 to 10</legend> -->
								<label><input type="radio" name="rating" value="1">1</label>
								<label><input type="radio" name="rating" value="2">2</label>
								<label><input type="radio" name="rating" value="3">3</label>
								<label><input type="radio" name="rating" value="4">4</label>
								<label><input type="radio" name="rating" value="5">5</label>
								<label><input type="radio" name="rating" value="6">6</label>
								<label><input type="radio" name="rating" value="7">7</label>
								<label><input type="radio" name="rating" value="8">8</label>
								<label><input type="radio" name="rating" value="9">9</label>
								<label><input type="radio" name="rating" value="10">10</label>
							</fieldset>
						</div>

						<div id="notSatisfiedForm" style="display:none;">
							<label for="improve-text">Please tell us how can we improve:</label><br>
							<textarea id="improve-text" class="form-control" name="improve-text" rows="3" cols="50" placeholder="Please tell us how can we improve"></textarea>
							<button type="button" class="improve-feedback-btn btn btn-success mt-3">Save Feedback</button>
						</div>

						<div id="togglefeedbackForm" style="display:none;">
							<label for="feedback">Please tell us how can we improve:</label><br>
							<textarea id="feedback" class="form-control" name="feedback" rows="3" cols="50" placeholder="What did we miss box"></textarea>
							<span id="feedbackError"></span>
							<button type="button" class="review-feedback-btn btn btn-success mt-3">Save Feedback</button>
						</div>
						<span id="trustedPilot" style="display:none;"  class="mb-4 trustedPilot">

							<span id="trusted" class="trusted" >Click the button below to review our platform on Trust Pilot<br><br><a href="//www.trustpilot.com/review/websitespeedy.com" target="_blank"><button type="button" class="reviewFeedback btn btn-success"  style="background-image:url('//shopifyspeedy.com/img/trustpilot.png');">Trust Pilot</button></a></span>

							<div id="reviewLeftOrNot" class="reviewLeftOrNot">
								<label class="" >
								<input type="radio" name="reviewLeft" class="trust-pilot-review" value="review left" > I don’t wanna leave review 
								</label>
								<label class="" > 
								<input type="radio" name="reviewLeft" class="trust-pilot-review" value="review added"> I have left review 
								</label>
							</div>
							</span>
							<button type="button" style="display:none"  class="goto-next-step nextBtn btn btn-success" data-satisfy="1" data-plaform="0" data-next="4">Continue To Step 4</button>

						<span id="reviewLeftError" class="reviewLeftError"> </span>
					</div>
				</form>`,
	        showCancelButton: false,
	        showCloseButton: false,
	        reverseButtons: false,
	        showConfirmButton: false,
	        // confirmButtonText: 'Save Feedback & Go to Step 6',
			preConfirm: () => {
				return false;
			}
	    }).then((result) => {
			if (result.isConfirmed) {
				// return false;
	        }
	    });

	    $("#satisfiedDiv , #ratingDiv , #togglefeedbackForm , #trustedPilot , #notSatisfiedForm").hide();
		setTimeout(function(){
			var step_completed = $("#step_completed").val();

			// fill satisfied with updated speed
			var satified_or_not = $("#step_completed").attr("data-satified_or_not") ;
			if ( satified_or_not != '' && satified_or_not != null && satified_or_not != undefined ) {
				$("input[name='answer'][value='"+satified_or_not+"']").prop("checked",true) ;
			}

			// fill rate your experience
			var rating = $("#step_completed").attr("data-rating") ;
			if ( rating != '' && rating != null && rating != undefined ) {
				$("input[name='rating'][value='"+rating+"']").prop("checked",true) ;
			}

			// fill trust Pilot review
			var trust_pilot_review = $("#step_completed").attr("data-trust_pilot_review") ;
			if ( trust_pilot_review != '' && trust_pilot_review != null && trust_pilot_review != undefined ) {
				$("input.trust-pilot-review[value='"+trust_pilot_review+"']").prop("checked",true) ;
			}

			// fill improve feedback
			var improve = $("#step_completed").attr("data-improve") ;
			$("input[id='improve-text']").val(improve) ;

			// fill improve feedback
			var feedback = $("#step_completed").attr("data-feedback") ;
			$("input[id='feedback']").val(feedback) ;

			if( step_completed == 1 ) {
				if ( satified_or_not == "yes" ) {
					$("#ratingDiv").show();
					$(".review-feedback-btn").attr("data-fstep",2) ;
				}
				else {
					$("#notSatisfiedForm").show();
					$(".review-feedback-btn").attr("data-fstep",3) ;
				}
			}
			else if( step_completed == 2 ) {
				$(".review-feedback-btn").attr("data-fstep",2) ;
				if ( rating <= 7 ) {
					$("#togglefeedbackForm").show();
				}
				else {
					$("#trustedPilot").show();
				}
			}
			else if( step_completed == 3 ) {
				$("#trustedPilot").show();
				$(".review-feedback-btn").attr("data-fstep",2) ;
			}
			else if( step_completed == 4 ) {
				$("#togglefeedbackForm").show();
				$(".review-feedback-btn").attr("data-fstep",2) ;
			}
			else if( step_completed == 5 ) {
				$("#notSatisfiedForm").show();
				$(".review-feedback-btn").attr("data-fstep",3) ;
			}
			else {
				$("#satisfiedDiv").show();
				$(".review-feedback-btn").attr("data-fstep",1) ;
			}

		},100);

	});


	/*** For review-feedback-form ***/ 

	// Are you satisfied with updated speed ?
	$(document).on('change','input[name="answer"]', function() {

		$("#validationErr").hide().text("");

		// Get selected radio button value
		var satifiedVal = $('input[name="answer"]:checked').val();
		
		if ( satifiedVal != '' && satifiedVal != null && satifiedVal != undefined ) {

			var boostId = <?=$site_id?>;
			var managerId = <?=$user_id?>;

            $.ajax({
                url: "inc/save-feedback.php",
                type: "post",
                data: {
                	action:"save-satisfied",
                    satifiedVal: satifiedVal,
                    website_id: boostId,
                    manager_id: managerId,
                },
                success: function (response) {
					if ( response == 1 ) {

						$("#validationErr").hide().text("");

						$("#satisfiedDiv , #notSatisfiedForm , #ratingDiv").hide();
						$("#step_completed").val(1).attr("data-satified_or_not",satifiedVal) ;

						if ( satifiedVal == "no" ) {
							$("#notSatisfiedForm").show();
							$(".optimize-speed-btn").attr("data-fstep","3") ;
						}
						else {
							$("#ratingDiv").show();
							$(".optimize-speed-btn").attr("data-fstep","2") ;	
						}

					}
					else{
						$("#satisfiedDiv").show();
						$("#ratingDiv , #notSatisfiedForm").hide();
						
						$(".optimize-speed-btn").attr("data-fstep","1") ;
						$("#step_completed").val(0);

						// show error
						$("#validationErr").show().text("Unable to save the option.") ;
						setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
					}
                }
            });
		} 
		else{
			$("#satisfiedDiv").show();
			$("#ratingDiv , #notSatisfiedForm").hide();
			
			$(".optimize-speed-btn").attr("data-fstep","1") ;
			$("#step_completed").val(0);

			// show error
			$("#validationErr").show().text("Please select any one option.") ;
			setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
		}
	});

	// How will you rate your experience ?
	$(document).on('change','input[name="rating"]', function() {

		$("#satisfiedDiv , #notSatisfiedForm").hide();

		// Get selected radio button value
		var rating = $('input[name="rating"]:checked').val();
		
		if ( rating != '' && rating != null && rating != undefined ) {
			var boostId = <?= $site_id ?> ;

            $.ajax({
                url: "inc/save-feedback.php",
                type: "post",
                data: {
                	action:"save-rating",
                    rating: rating,
                    website_id: boostId
                },
                success: function (response) {

					if( response == 1 ) {

						$(".optimize-speed-btn").attr("data-fstep","2") ;	
						$("#step_completed").val(2).attr("data-rating",rating) ;

						$("#validationErr").hide().text("");

						if (rating <= 7) {
							$('#togglefeedbackForm').show();
							$("#ratingDiv , #trustedPilot").hide();
						}
						else {
							$('#trustedPilot').show();
							$("#ratingDiv , #togglefeedbackForm").hide();
						}

					}
					else {
						$("#ratingDiv").show();
						$("#togglefeedbackForm , #trustedPilot").hide();
						
						$(".optimize-speed-btn").attr("data-fstep","2") ;
						$("#step_completed").val(1);

						// show error
						$("#validationErr").show().text("Unable to save the option.") ;
						setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
					}
                }
            });
		}
		else {
			$("#ratingDiv").show();
			$("#togglefeedbackForm , #trustedPilot").hide();
			
			$(".optimize-speed-btn").attr("data-fstep","2") ;
			$("#step_completed").val(1);

			// show error
			$("#validationErr").show().text("Please rate your experience.") ;
			setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
		}
	});

	// How will you rate your experience ?
	$(document).on('change','.trust-pilot-review', function() {

		$("#satisfiedDiv , #ratingDiv , #togglefeedbackForm , #notSatisfiedForm").hide();

		var trust_pilot_review = $('input.trust-pilot-review:checked').val();
		
		if ( trust_pilot_review != '' && trust_pilot_review != null && trust_pilot_review != undefined ) {

			$("#ratingDiv , #togglefeedbackForm").hide();
			$('#trustedPilot').show();
			$(".review-feedback-btn").attr("data-fstep","2") ;	

			var boostId = <?=$site_id?> ;

            $.ajax({
                url: "inc/save-feedback.php",
                type: "post",
                data: {
                	action:"save-trust-pilot",
                    trust_pilot_review: trust_pilot_review,
                    website_id: boostId
                },
                success: function (response) {

					if ( response == 1 ) {
						$('#reviewLeftOrNot').hide(); 
						$('.nextBtn').show(); 
						$("#validationErr").hide().text("");

						$(".optimize-speed-btn").attr("data-fstep","2") ;
						$("#step_completed").val(3).attr("data-trust_pilot_review",trust_pilot_review);

						var next = $(".goto-next-optimize").attr("data-next") ;
						installation_steps(next);

						$(".optimize-speed-btn").addClass("goto-next-step").removeClass("goto-next-optimize");

						// show the next step
						$("#step"+next).removeClass("d-none");
						$("#step"+next).addClass("complited");
					
						// $(".top-step-"+next).click(); 	

						// setTimeout(function(){ $('html, body').animate({ scrollTop: $("#step"+next).offset().top }, 500); },500) ;

						// setTimeout(function () {
						// 	// $('.swal2-container').css('display','none');
						// 	Swal.close() ;
						// }, 1000);
					}
					else {
						$("#trustedPilot").show();
						$("#togglefeedbackForm , #ratingDiv").hide();
						
						$(".optimize-speed-btn").attr("data-fstep","2") ;
						$("#step_completed").val(2);

						// show error
						$("#validationErr").show().text("Unable to save the option.") ;
						setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
					}
                }
            });
		} 
		else {
			$("#trustedPilot").show();
			$("#togglefeedbackForm , #ratingDiv").hide();
			
			$(".optimize-speed-btn").attr("data-fstep","2") ;
			$("#step_completed").val(2);

			// show error
			$("#validationErr").show().text("Please select any one option.") ;
			setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
		}
	});

	// Feedback:
	$(document).on("click",".review-feedback-btn",function() {
	
		$("#satisfiedDiv , #ratingDiv , #trustedPilot , #notSatisfiedForm").hide(); 
		$("#togglefeedbackForm").show(); 

		$("#validationErr").hide().text("");
		
		// now check for feedback
		var user_feedback = $("#feedback").val().trim() ;

		if ( user_feedback == '' || user_feedback == null || user_feedback == undefined ) {
			$(".optimize-speed-btn").attr("data-fstep","2") ;	
			$("#step_completed").val(2) ;

			$("#validationErr").show().text("Please enter your feedback.") ;
			setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
		}
		else {

			var boostId = <?= $site_id ?> ;

            $.ajax({
                url: "inc/save-feedback.php",
                type: "post",
                data: {
                	action:"save-feedback",
                    user_feedback: user_feedback,
                    website_id: boostId
                },
                success: function (response) {

					if( response == 1 ) {

						$("#step_completed").val(4).attr("data-feedback",user_feedback);
						$("#validationErr").hide().text("");
						
						var next = $(".goto-next-optimize").attr("data-next") ;
						installation_steps(next);

						$(".optimize-speed-btn").addClass("goto-next-step").removeClass("goto-next-optimize");

						// show next step
						// $(".loader").hide();
						$("#step"+next).removeClass("d-none");
						$("#step"+next).addClass("complited"); 
						$(".top-step-"+next).click(); 	
						// $('html, body').animate({ scrollTop: $("#step"+next).offset().top - 230 }, 1);
						setTimeout(function(){ 
							$('html, body').animate({ scrollTop: $("#step"+next).offset().top - 230 }, 1);
						},500) ;

						
						
						setTimeout(function () {
							// $('.swal2-container ').css('display','none');
							Swal.close() ;
						}, 1000);
					}
					else{
						$(".optimize-speed-btn").attr("data-fstep","2") ;	
						$("#step_completed").val(2) ;

						$("#validationErr").show().text("Unable to save the option.") ;
						setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
					}
                }
            });

		}
	}) ;

	// Please tell us how can we improve:
	$(document).on("click",".improve-feedback-btn",function() {
	
		$("#satisfiedDiv , #ratingDiv , #trustedPilot , #togglefeedbackForm").hide(); 
		$("#notSatisfiedForm").show(); 

		$("#validationErr").hide().text("");
		
		// now check for feedback
		var user_feedback = $("#improve-text").val().trim() ;

		if ( user_feedback == '' || user_feedback == null || user_feedback == undefined ) {
			$(".optimize-speed-btn").attr("data-fstep","3") ;	
			$("#step_completed").val(1) ;

			$("#validationErr").show().text("Please enter your feedback.") ;
			setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
		}
		else {

			var boostId = <?=$site_id?> ;

            $.ajax({
                url: "inc/save-feedback.php",
                type: "post",
                data: {
                	action:"save-improve",
                    user_feedback: user_feedback,
                    website_id: boostId
                },
                success: function (response) {

					if( response == 1 ) {

						$("#step_completed").val(5).attr("data-feedback",user_feedback);
						$("#validationErr").hide().text("");
						
						var next = $(".goto-next-optimize").attr("data-next") ;
						installation_steps(next);

						$(".optimize-speed-btn").addClass("goto-next-step").removeClass("goto-next-optimize");

						// show next step
						// $(".loader").hide();
						$("#step"+next).removeClass("d-none");
						$("#step"+next).addClass("complited"); 
						$(".top-step-"+next).click(); 	
						// $('html, body').animate({ scrollTop: $("#step"+next).offset().top - 230 }, 1);
						setTimeout(function(){ 
							$('html, body').animate({ scrollTop: $("#step"+next).offset().top - 230 }, 1);
						},500) ;
						
						setTimeout(function () {
							// $('.swal2-container ').css('display','none');
							Swal.close() ;
						}, 1000);
					}
					else{
						$(".optimize-speed-btn").attr("data-fstep","3") ;	
						$("#step_completed").val(1) ;

						$("#validationErr").show().text("Unable to save the option.") ;
						setTimeout(function(){ $("#validationErr").hide().text("") ; },5000);
					}
                }
            });

		}
	}) ;

	/*** END review-feedback-form ***/ 


	$('#addedNewUrl').on('submit', function (e) {

		e.preventDefault();

		

		$(".refresh-nospeedy-parent:eq(1),.refresh-nospeedy-parent:eq(4)").hide() ;

		var is_valid = true;
		var managerId = <?= $user_id ?> ;
		var boostId = <?= $site_id ?> ;
		var websiteUrl = '<?=$websiteUrl?>';
		var urlId2 = '<?=$urlId2?>';
		var websiteName = '<?=$websiteName?>';
		var url_priority = '2';
		var url2 = $('#newUrl').val();
		var url3Validation = $('#newUrl3').val();

		var submitBtn = $('#addedNewUrlBtn').html();
		websiteUrlWithSlash = websiteUrl+'/';	
		url3ValidationWithSlash = url3Validation+'/';	
		if (url2 == '') {
			$('.errroUrl').html('Please enter valid url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
			is_valid = false;
		}
		else if(websiteUrl==url2 || websiteUrlWithSlash==url2){
			$('.subDomainUrlError2').html("Don't enter same url").css('color', 'red').delay(3000).fadeOut().css('display', 'block');
			is_valid = false
		}

		else if(url3Validation==url2 || url3ValidationWithSlash== url2 ){
			$('.sameEnterUrl2').html("Don't enter same url").css('color', 'red').delay(3000).fadeOut().css('display', 'block');
			is_valid = false
		}
		else {

			removeHashAndQueryParams('#newUrl');
			var url2 = $('#newUrl').val();

			var check_url = compareAndStoreSubdomain(websiteUrl, url2) ;
			var checkDomainVerification = checkDomainVerification1(websiteUrl,url2) ;

			$('.errroUrl').html('');
			if (check_url ) {
				is_valid = true
			}
			else {
				var expurl2 = websiteUrl+'abc';
				$('.subDomainUrlError2').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
				is_valid = false
			}


			if ( !checkDomainVerification ) {
				is_valid = true;
			}
			else {
				var expurl2 = websiteUrl+'abc';
				$('.subDomainUrlError2').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
				is_valid = false
			}

		}


		if (is_valid) {

			var table_id = "newUrl2" ; 
			var data_oldy = "url2_inst_speed" ;
			$(this).hide();
			$('#step4AnalyseBtn').show();
			$(".additional-url2-analyse").html('<b><span><h4>URL 2-</h4></span> <a href="'+url2+'" style="pointer-events: none;" target="_blank" >'+url2+'</a> </b>') ;

			/** remove the disabled step1 buttons **/
			setTimeout(function() {
				var flag_add1 = flag_add2 = 0 ;

				var add1_url_text = $("#step4AnalyseBtn a").text().trim();
				var add1_url_input = $('#newUrl').val();

				if ( add1_url_text != '' && add1_url_input != '' ) {
					flag_add1 = 1 ;
				}

				var add2_url_text = $("#step4Url3AnalyseBtn a").text().trim();
				var add2_url_input = $('#newUrl3').val();

				if ( add2_url_text != '' && add2_url_input != '' ) {
					flag_add2 = 1 ;
				}

				if ( flag_add1 == 1 && flag_add2 == 1 ) {
					$('#boostBtn').removeAttr('disabled');
					$('#continueBtn').removeAttr('disabled');
				}
				else {
					$('#boostBtn').prop('disabled', true);
					$('#continueBtn').prop('disabled', true);
				} 
			},500);
			/** END remove the disabled step1 buttons **/ 

			// Replace with your actual values
			var apiKey = $("meta[name='google_pagespeed_api']").attr("content") ;

	    	if ( url2.includes("?") ) {
	    		var request_website_url = url2+"&nospeedy" ;
	    	}
	    	else {
	    		var request_website_url = url2+"?nospeedy" ;
	    	}

			// Construct the API endpoint
			var apiEndpoint = `//www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_website_url)}&key=${apiKey}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

			getting_no_speedy1 = 1 ;

		    fetch(apiEndpoint).then(response => {
	        	 console.log('test');
	            if (!response.ok) {

					$('#boostBtn').prop('disabled', true);
					$('#continueBtn').prop('disabled', true);

					$("#addedNewUrl").show();
					$("#step4AnalyseBtn").hide();
					$("#step4AnalyseBtn a").html("");

					$(".tabs-2-head").click() ;
					$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

					$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

					back_installtion_steps(boostId, 1 , "step1") ;

	                throw new Error('Please thoroughly review your URL ('+url2+') within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
	            }
	            return response.json();
	        })
	        .then(data => {
	            // Process your data here

	            if ( data.hasOwnProperty("lighthouseResult") ) {

					var lighthouseResult = data.lighthouseResult ;

					var requestedUrl = lighthouseResult.requestedUrl ;
					var finalUrl = lighthouseResult.finalUrl ;
					var userAgent = lighthouseResult.userAgent ;
					var fetchTime = lighthouseResult.fetchTime ;
					var environment = JSON.stringify(lighthouseResult.environment) ;
					var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
					var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
					var audits = JSON.stringify(lighthouseResult.audits) ;
					var categories = JSON.stringify(lighthouseResult.categories) ;
					var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
					var i18n = JSON.stringify(lighthouseResult.i18n) ;

					var desktop = lighthouseResult.categories.performance.score ;
					desktop = Math.round(desktop * 100) ;

					if ( desktop > 0 ) {

						$.ajax({
							url: "inc/check-additional-speed-fetch.php",
							type: "post",
							data: {
								managerId: managerId,
								boostId: boostId,
								website_url: url2,
								websiteName: websiteName,
								url_priority: url_priority,
								urlId2: urlId2,
								submitBtn: submitBtn,
								table_id:table_id,
								// lighthouseResult:lighthouseResult,
								requestedUrl:requestedUrl,
								finalUrl:finalUrl,
								userAgent:userAgent,
								fetchTime:fetchTime,
								environment:environment,
								runWarnings:runWarnings,
								configSettings:configSettings,
								audits:audits,
								categories:categories,
								categoryGroups:categoryGroups,
								i18n:i18n,
							},
							dataType: "JSON",
							beforeSend: function () {
								// $(".loader").show().html("<div class='loader_s'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analyzing your website. It might take 2-3 mins<p></div>");
							},
							success: function (obj) {

								if (obj.status == 'done') {

									$(".refresh-nospeedy-url[table='url2-old-speed-table']").attr("additional",obj.id).attr("url",url2);
									$(".url2-table-head").html(url2);

									$(".new-analyze-speed-for-3url").attr("data-website__url2_name",url2).attr("data-website_url2",url2) ;
									$(".new-analyze-speed-for-3url").attr("data-website_url2_id",obj.id) ;

									$('#newUrl').val(url2);
									$('#addedNewUrl').hide();
									$('#step4AnalyseBtn').css('display', 'block');

									$(".additional-url2-analyse").html('<b><span><h4>URL 2-</h4></span> <a href="'+url2+'" style="pointer-events: none;" target="_blank" >'+url2+'</a> </b>') ;
									$('#url2AddedInThirdTab').html('<b><span><h4>URL 2-</h4></span> <a href="'+url2+'" style="pointer-events: none; " target="_blank" >'+url2+'</a> </b>');
									
									$(".additional-reanalyse-updated-speed.additional-reanalyse2-speed").attr("data-website_name",url2).attr("data-website_url",url2).attr("data-additional-id",obj.id) ;

									
									$('.new-analyze-speed-for-url2').attr("data-website__url2_name",url2).attr("data-website_url2",url2).attr("data-website_url2_id",obj.id);

									// fill desktop data ===========================
									// addNospeedyData(obj.message.website_id, obj.message.id, obj.message.website_url, 'newUrl2','url2_inst_speed');

									var content = obj.message;
									// Old Speed Score :
									$("."+data_oldy).find(".performance").text(content.desktop);
									$("."+data_oldy).find(".accessibility").text(content.accessibility);
									$("."+data_oldy).find(".best-practices").text(content.bestpractices);
									$("."+data_oldy).find(".seo").text(content.seo);
									$("."+data_oldy).find(".pwa").text(content.pwa);
									$("." + data_oldy).find(".fcp").text(parseFloat(content.FCP).toFixed(2));
									$("." + data_oldy).find(".lcp").text(parseFloat(content.LCP).toFixed(2));
									$("." + data_oldy).find(".mpf").text(parseFloat(content.MPF).toFixed(2));
									$("." + data_oldy).find(".cls").text(parseFloat(content.CLS).toFixed(2));
									$("." + data_oldy).find(".tbt").text(parseFloat(content.TBT).toFixed(2));
									$("." + data_oldy).find(".si").text(content.SI);


									$("#wosa1-desktop-speed").val(content.performance);
									$("#wsa1-desktop-speed").val(0);

									if ( content.performance == 0 || content.performance == "0" ) {
										$(".refresh-nospeedy-parent:eq(1),.refresh-nospeedy-parent:eq(4)").show() ;
									}

									// now get mobile speed ===========================

									var apiEndpoint = `//www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_website_url)}&key=${apiKey}&strategy=mobile&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

							    	fetch(apiEndpoint).then(response => {

							            if (!response.ok) {

											$('#boostBtn').prop('disabled', true);
											$('#continueBtn').prop('disabled', true);

											$("#addedNewUrl").show();
											$("#step4AnalyseBtn").hide();
											$("#step4AnalyseBtn a").html("");

											$(".tabs-2-head").click() ;
											$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

											$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

											back_installtion_steps(boostId, 1 , "step1") ;

							                throw new Error('Please thoroughly review your URL ('+url2+') within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
							            }
							            return response.json();
							        })
							        .then(data => {
							            // Process your data here

							            if ( data.hasOwnProperty("lighthouseResult") ) {

								            var lighthouseResult = data.lighthouseResult ;

											var requestedUrl = lighthouseResult.requestedUrl ;
											var finalUrl = lighthouseResult.finalUrl ;
											var userAgent = lighthouseResult.userAgent ;
											var fetchTime = lighthouseResult.fetchTime ;
											var environment = JSON.stringify(lighthouseResult.environment) ;
											var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
											var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
											var audits = JSON.stringify(lighthouseResult.audits) ;
											var categories = JSON.stringify(lighthouseResult.categories) ;
											var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
											var i18n = JSON.stringify(lighthouseResult.i18n) ;

											$.ajax({
												url: "inc/check-additional-speed-mobile-fetch.php",
												method: "POST",
												data: {
													managerId: managerId,
													boostId: boostId,
													website_url: url2,
													websiteName: websiteName,
													url_priority: url_priority,
													urlId2: urlId2,
													submitBtn: submitBtn,
													table_id:table_id,
													request_website_url:request_website_url,
													speedtype: table_id,
													website_id: boostId,
													additional_id: obj.id,
													// lighthouseResult:lighthouseResult,
													requestedUrl:requestedUrl,
													finalUrl:finalUrl,
													userAgent:userAgent,
													fetchTime:fetchTime,
													environment:environment,
													runWarnings:runWarnings,
													configSettings:configSettings,
													audits:audits,
													categories:categories,
													categoryGroups:categoryGroups,
													i18n:i18n,
												},
												dataType: "JSON",
												timeout: 0,
												success: function (data) {

													getting_no_speedy1 = 0 ;

													if (data.status == "done") {

														var content = data.message;

														// Old Speed Score :
														$("."+data_oldy).find(".performance_m").text(content.mobile)
														$("."+data_oldy).find(".accessibility_m").text(content.accessibility);
														$("."+data_oldy).find(".best-practices_m").text(content.bestpractices);
														$("."+data_oldy).find(".seo_m").text(content.seo);
														$("."+data_oldy).find(".pwa_m").text(content.pwa);
														$("." + data_oldy).find(".fcp_m").text(parseFloat(content.FCP).toFixed(2));
														$("." + data_oldy).find(".lcp_m").text(parseFloat(content.LCP).toFixed(2));
														$("." + data_oldy).find(".mpf_m").text(parseFloat(content.MPF).toFixed(2));
														$("." + data_oldy).find(".cls_m").text(parseFloat(content.CLS).toFixed(2));
														$("." + data_oldy).find(".tbt_m").text(parseFloat(content.TBT).toFixed(2));

														$("." + data_oldy).find(".si_m").text(content.SI);


														
														var mobile = content.mobile;
														var myArray = mobile.split("/");
														var arr = myArray['0'];
														$('.new-analyze-speed-for-url2').attr('data-oldmscore',arr).attr('data-currentmscore',arr)

														$("#wsa1-mobile-speed , #wosa1-mobile-speed").val(0);
														$("#wosa1-mobile-speed").val(content.performance);

														if ( content.performance == 0 || content.performance == "0" ) {
															$(".refresh-nospeedy-parent:eq(1),.refresh-nospeedy-parent:eq(4)").show() ;
														}

														manage_additional_nospeedy_speed(boostId,obj.id,table_id);
														// setTimeout(function(){ $(".loader").hide().html('') ; },1000);

													}
													else {

														$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

														$('#boostBtn').prop('disabled', true);
														$('#continueBtn').prop('disabled', true);

														$("#addedNewUrl").show();
														$("#step4AnalyseBtn").hide();
														$("#step4AnalyseBtn a").html("");

														back_installtion_steps(boostId, 1 , "step1") ;

														Swal.fire({
															title: 'Error!',
															icon: 'error',
															text: data.message,
															showDenyButton: false,
															showCancelButton: false,
															allowOutsideClick: false,
															allowEscapeKey: false,
															confirmButtonText: 'Close',
														}).then((result) => {
															
														}) ;

														// setTimeout(function(){ $(".loader").hide().html('') ; },1000);
													}

												},
												error: function (xhr) { // if error occured
													getting_no_speedy1 = 0 ;
													script_loading++;
													console.error(xhr.statusText + xhr.responseText);
													back_installtion_steps(boostId, 1 , "step1") ;
													// setTimeout(function(){ $(".loader").hide().html('') ; },1000);
												},
												complete: function () {
												}

											});

							            }
							            else {

											$('#boostBtn').prop('disabled', true);
											$('#continueBtn').prop('disabled', true);

											$("#addedNewUrl").show();
											$("#step4AnalyseBtn").hide();
											$("#step4AnalyseBtn a").html("");

											$(".tabs-2-head").click() ;
											$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;
							            	
							            	back_installtion_steps(boostId, 1 , "step1") ;

							            	getting_no_speedy1 = 0 ;

											Swal.fire({
												title: "Error!",
												icon: "error",
												text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
												showDenyButton: false,
												showCancelButton: false,
												allowOutsideClick: false,
												allowEscapeKey: false,
												confirmButtonText: 'Close',
											}).then((result) => {
												if (result.isConfirmed) {
													// $("#addedNewUrl")[0].reset() ;
												}
											}) ; 

											// Old Speed Score :
											$("."+data_oldy).find(".performance_m").text("");
											$("."+data_oldy).find(".accessibility_m").text("");
											$("."+data_oldy).find(".best-practices_m").text("");
											$("."+data_oldy).find(".seo_m").text("");
											$("."+data_oldy).find(".pwa_m").text("");
											$("."+data_oldy).find(".fcp_m").text("");
											$("."+data_oldy).find(".lcp_m").text("");
											$("."+data_oldy).find(".mpf_m").text("");
											$("."+data_oldy).find(".cls_m").text("");
											$("."+data_oldy).find(".tbt_m").text("");

											$("."+data_oldy).find(".performance").text("");
											$("."+data_oldy).find(".accessibility").text("");
											$("."+data_oldy).find(".best-practices").text("");
											$("."+data_oldy).find(".seo").text("");
											$("."+data_oldy).find(".pwa").text("");
											$("."+data_oldy).find(".fcp").text("");
											$("."+data_oldy).find(".lcp").text("");
											$("."+data_oldy).find(".mpf").text("");
											$("."+data_oldy).find(".cls").text("");
											$("."+data_oldy).find(".tbt").text("");


											$('.new-analyze-speed-for-url2').attr('data-oldmscore',"").attr('data-currentmscore',"")

											$("#wosa1-desktop-speed, #wsa1-desktop-speed, #wsa1-mobile-speed , #wosa1-mobile-speed").val(0);

											$.ajax({
												url: "inc/remove-additional-url.php",
												method: "POST",
												data: {
													website_id: boostId,
													additional_id: obj.id,
													action: 'remove-additional-url' 
												},
												dataType: "JSON",
												timeout: 0,
												success: function (data) {

												},
												error: function (xhr) { 
													console.error(xhr.statusText + xhr.responseText);
												},
												complete: function () {
												}

											});
							            	
							            }


							        })
							        .catch(error => {

										$('#boostBtn').prop('disabled', true);
										$('#continueBtn').prop('disabled', true);

										$("#addedNewUrl").show();
										$("#step4AnalyseBtn").hide();
										$("#step4AnalyseBtn a").html("");

										$(".tabs-2-head").click() ;
										$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

										$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

							        	getting_no_speedy1 = 0 ;

							            console.error('Fetch error:', error);

										Swal.fire({
											title: "Error!",
											icon: "error",
											text: error,
											showDenyButton: false,
											showCancelButton: false,
											allowOutsideClick: false,
											allowEscapeKey: false,
											confirmButtonText: 'Close',
										}).then((result) => {
											if (result.isConfirmed) {
												// $("#addedNewUrl")[0].reset() ;
											}
										}) ; 

										$('#boostBtn').prop('disabled', true);
										$('#continueBtn').prop('disabled', true);

										// setTimeout(function(){$(".loader").hide().html('') ;},1000);
							        });

								}
								else {

									$('#boostBtn').prop('disabled', true);
									$('#continueBtn').prop('disabled', true);

									$("#addedNewUrl").show();
									$("#step4AnalyseBtn").hide();
									$("#step4AnalyseBtn a").html("");

									$(".tabs-2-head").click() ;
									$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

									back_installtion_steps(boostId, 1 , "step1") ;

									$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

									getting_no_speedy1 = 0 ;

									Swal.fire({
										title: 'Error!',
										icon: 'error',
										text: obj.message,
										showDenyButton: false,
										showCancelButton: false,
										allowOutsideClick: false,
										allowEscapeKey: false,
										confirmButtonText: 'Close',
									}).then((result) => {
										if (result.isConfirmed) {
											// $("#addedNewUrl")[0].reset() ;
										}
									}) ;

									// setTimeout(function(){$(".loader").hide().html('') ;},1000);
								}

							},
							error: function (xhr, status, error) {

								getting_no_speedy1 = 0 ;
								back_installtion_steps(boostId, 1 , "step1") ;
								console.error(xhr.responseText);
								// setTimeout(function(){$(".loader").hide().html('') ;},1000);
							}
						});

					}
					else {

						$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

						$('#boostBtn').prop('disabled', true);
						$('#continueBtn').prop('disabled', true);

						$("#addedNewUrl").show();
						$("#step4AnalyseBtn").hide();
						$("#step4AnalyseBtn a").html("");

						$(".tabs-2-head").click() ;
						$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

						back_installtion_steps(boostId, 1 , "step1") ;

						getting_no_speedy1 = 0 ;

						Swal.fire({
							title: "Error!",
							icon: "error",
							text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
							showDenyButton: false,
							showCancelButton: false,
							allowOutsideClick: false,
							allowEscapeKey: false,
							confirmButtonText: 'Close',
						}).then((result) => {
							if (result.isConfirmed) {
								// $("#addedNewUrl")[0].reset() ;
							}
						}) ; 

						// setTimeout(function(){$(".loader").hide().html('') ;},1000);

					}

	            }
	            else {

					$('#boostBtn').prop('disabled', true);
					$('#continueBtn').prop('disabled', true);

					$("#addedNewUrl").show();
					$("#step4AnalyseBtn").hide();
					$("#step4AnalyseBtn a").html("");

					$(".tabs-2-head").click() ;
					$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

					$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

					back_installtion_steps(boostId, 1 , "step1") ;

	            	getting_no_speedy1 = 0 ;

					Swal.fire({
						title: "Error!",
						icon: "error",
						text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
						showDenyButton: false,
						showCancelButton: false,
						allowOutsideClick: false,
						allowEscapeKey: false,
						confirmButtonText: 'Close',
					}).then((result) => {
						if (result.isConfirmed) {
							// $("#addedNewUrl")[0].reset() ;
						}
					}) ; 

					// setTimeout(function(){$(".loader").hide().html('') ;},1000);

	            }

	            // You can access various properties of data to get speed and performance metrics
	        })
	        .catch(error => {

				$('#boostBtn').prop('disabled', true);
				$('#continueBtn').prop('disabled', true);

				$("#addedNewUrl").show();
				$("#step4AnalyseBtn").hide();
				$("#step4AnalyseBtn a").html("");

				$(".tabs-2-head").click() ;
				$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

				$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

	        	getting_no_speedy1 = 0 ;

	            console.error('Fetch error:', error);

				Swal.fire({
					title: "Error!",
					icon: "error",
					text: error,
					showDenyButton: false,
					showCancelButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					confirmButtonText: 'Close',
				}).then((result) => {
					if (result.isConfirmed) {
						// $("#addedNewUrl")[0].reset() ;
					}
				}) ; 

				// setTimeout(function(){$(".loader").hide().html('') ;},1000);
	        });

		}
	});


	$('#addedNewUrl3').on('submit', function (e) {

		$(".refresh-nospeedy-parent:eq(2),.refresh-nospeedy-parent:eq(5)").hide() ;
		
		e.preventDefault();

		

		// var is_valid = true;
		var managerId = <?=$user_id ?> ;
		var boostId = <?=$site_id ?> ;
		var websiteUrl = '<?=$websiteUrl?>';
		var urlId3 = '<?=$urlId3?>';
		var websiteName = '<?=$websiteName?>';
		var url_priority = '3';
		var url3 = $('#newUrl3').val().trim();
		var submitBtn = $('#addedNewUrl3Btn').html();
		var url1Validation =  $('#newUrl').val();

		websiteUrlWithSlash = websiteUrl+'/';	
		url1ValidationWithSlash =  url1Validation+'/';
		if (url3 == '' || !url3) {
			$('.errorUrl3').html('Please enter valid url ').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
			is_valid = false;
		}	
		else if(websiteUrl==url3 || websiteUrlWithSlash==url3){
			$('.subDomainUrlError3').html("Don't enter same url").css('color', 'red').delay(3000).fadeOut().css('display', 'block');
			is_valid = false
		}
		else if(url1Validation==url3 || url1ValidationWithSlash== url3 ){
			$('.sameEnterUrl3').html("Don't enter same url").css('color', 'red').delay(3000).fadeOut().css('display', 'block');
			is_valid = false
		}
		else {

			removeHashAndQueryParams('#newUrl3');
			var url3 = $('#newUrl3').val().trim();

			var check_url = compareAndStoreSubdomain(websiteUrl, url3)
			var checkDomainVerification = checkDomainVerification1(websiteUrl,url3) ;

			$('.errorUrl3').html('');
			if (check_url) {
				is_valid = true
			}
			else {
				var expurl3 = websiteUrl+'abc';
				$('.subDomainUrlError3').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
				is_valid = false
			}

			if ( !checkDomainVerification ) {
				is_valid = true;
			}
			else {
				var expurl3 = websiteUrl+'abc';
				$('.subDomainUrlError3').html('Please enter domain page url').css('color', 'red').delay(3000).fadeOut().css('display', 'block');
				is_valid = false
			}
		}

		if (is_valid) {

			var table_id = "newUrl3" ; 
			var data_oldy = "url3_inst_speed" ;
			$(this).hide();
			$('#step4Url3AnalyseBtn').show()
			$("span.additional-url3-analyse").html('<b><span><h4>URL 3-</h4></span> <a href="'+url3+'" style="pointer-events: none; " target="_blank" >'+url3+'</a> </b>') ;
			
			/** remove the disabled step1 buttons **/
			setTimeout(function() {
				var flag_add1 = flag_add2 = 0 ;

				var add1_url_text = $("#step4AnalyseBtn a").text().trim();
				var add1_url_input = $('#newUrl').val();

				if ( add1_url_text != '' && add1_url_input != '' ) {
					flag_add1 = 1 ;
				}

				var add2_url_text = $("#step4Url3AnalyseBtn a").text().trim();
				var add2_url_input = $('#newUrl3').val();

				if ( add2_url_text != '' && add2_url_input != '' ) {
					flag_add2 = 1 ;
				}

				if ( flag_add1 == 1 && flag_add2 == 1 ) {
					$('#boostBtn').removeAttr('disabled');
					$('#continueBtn').removeAttr('disabled');
				}
				else {
					$('#boostBtn').prop('disabled', true);
					$('#continueBtn').prop('disabled', true);
				} 
			},500);
			/** END remove the disabled step1 buttons **/ 

			// Replace with your actual values
			var apiKey = 'AIzaSyDw2nckjNQeVLGw_BxcfIvLTw3NYONCuRE';

	    	if ( url3.includes("?") ) {
	    		var request_website_url = url3+"&nospeedy" ;
	    	}
	    	else {
	    		var request_website_url = url3+"?nospeedy" ;
	    	}

			// Construct the API endpoint
			var apiEndpoint = `//www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_website_url)}&key=${apiKey}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

			getting_no_speedy2 = 1 ;

		    fetch(apiEndpoint).then(response => {

	            if (!response.ok) {

	            	$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

					$('#boostBtn').prop('disabled', true);
					$('#continueBtn').prop('disabled', true);

					$("#addedNewUrl3").show();
					$("#step4Url3AnalyseBtn").hide();
					$("#step4Url3AnalyseBtn a").html("");

					$(".tabs-2-head").click() ;
					$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

	                throw new Error('Please thoroughly review all your URLs within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
	            }
	            return response.json();
	        })
	        .then(data => {
	            // Process your data here

	            if ( data.hasOwnProperty("lighthouseResult") ) {

					var lighthouseResult = data.lighthouseResult ;

					var requestedUrl = lighthouseResult.requestedUrl ;
					var finalUrl = lighthouseResult.finalUrl ;
					var userAgent = lighthouseResult.userAgent ;
					var fetchTime = lighthouseResult.fetchTime ;
					var environment = JSON.stringify(lighthouseResult.environment) ;
					var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
					var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
					var audits = JSON.stringify(lighthouseResult.audits) ;
					var categories = JSON.stringify(lighthouseResult.categories) ;
					var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
					var i18n = JSON.stringify(lighthouseResult.i18n) ;

					var desktop = lighthouseResult.categories.performance.score ;
					desktop = Math.round(desktop * 100) ;

					if ( desktop > 0 ) {

						$.ajax({
							url: "inc/check-additional-speed-fetch.php",
							type: "post",
							data: {
								managerId: managerId,
								boostId: boostId,
								website_url: url3,
								websiteName: websiteName,
								url_priority: url_priority,
								urlId3: urlId3,
								submitUrl3Btn: submitBtn,
								table_id:table_id,
								// lighthouseResult:lighthouseResult,
								requestedUrl:requestedUrl,
								finalUrl:finalUrl,
								userAgent:userAgent,
								fetchTime:fetchTime,
								environment:environment,
								runWarnings:runWarnings,
								configSettings:configSettings,
								audits:audits,
								categories:categories,
								categoryGroups:categoryGroups,
								i18n:i18n,
							},
							dataType: "JSON",
							beforeSend: function () {
								// $(".loader").show().html("<div class='loader_s'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analyzing your website. It might take 2-3 mins<p></div>");

							},
							success: function (obj) {

								if (obj.status == 'done') {

									$(".refresh-nospeedy-url[table='url3-old-speed-table']").attr("additional",obj.id).attr("url",url3);
									$(".url3-table-head").html(url3);

									$(".new-analyze-speed-for-3url").attr("data-website_url3_name",url3).attr("data-website_url3",url3) ;
									$('#newUrl3').val(url3);

									$(".new-analyze-speed-for-3url").attr("data-website_url3_id",obj.id) ;

									$(".additional-url3-analyse").html('<b><span><h4>URL 3-</h4></span> <a href="'+url3+'" style="pointer-events: none; " target="_blank" >'+url3+'</a> </b>') ;
									$('#url3AddedInThirdTab').html('<b><span><h4>URL 3-</h4></span> <a href="'+url3+'" style="pointer-events: none; " target="_blank" >'+url3+'</a> </b>');
									$('#addedNewUrl3').hide();
									$('#step4Url3AnalyseBtn').css('display', 'block');

									$(".compare-url3-old-speed").attr("data-url",url3).attr("data-website_name",url3).attr("data-website_url",url3).attr("data-additional-id",obj.id);//123 ;

									$(".additional-reanalyse-updated-speed.additional-reanalyse3-speed").attr("data-website_name",url3).attr("data-website_url",url3).attr('data-additional-id',obj.id) ;
									
									$('.new-analyze-speed-for-url3').attr("data-website__url3_name",url3).attr("data-website_url3",url3).attr("data-website_url3_id",obj.id);



									// fill desktop data ===========================

									var content = obj.message;
									// Old Speed Score :
									$("."+data_oldy).find(".performance").text(content.desktop);
									$("."+data_oldy).find(".accessibility").text(content.accessibility);
									$("."+data_oldy).find(".best-practices").text(content.bestpractices);
									$("."+data_oldy).find(".seo").text(content.seo);
									$("."+data_oldy).find(".pwa").text(content.pwa);
									$("." + data_oldy).find(".fcp").text(parseFloat(content.FCP).toFixed(2));
									$("." + data_oldy).find(".lcp").text(parseFloat(content.LCP).toFixed(2));
									$("." + data_oldy).find(".mpf").text(parseFloat(content.MPF).toFixed(2));
									$("." + data_oldy).find(".cls").text(parseFloat(content.CLS).toFixed(2));
									$("." + data_oldy).find(".tbt").text(parseFloat(content.TBT).toFixed(2));

									$("." + data_oldy).find(".si").text(content.SI);

									$("#wosa2-desktop-speed").val(content.performance);
									$("#wsa2-desktop-speed").val(0);

									if ( content.performance == 0 || content.performance == "0" ) {
										$(".refresh-nospeedy-parent:eq(2),.refresh-nospeedy-parent:eq(5)").show() ;
									}

									/** remove the disabled step1 buttons **/
								      var flag_add1 = flag_add2 = 0 ;

								      var add1_url_text = $("#step4AnalyseBtn a").text().trim();
								      var add1_url_input = $('#newUrl').val();

								      if ( add1_url_text != '' && add1_url_input != '' ) {
								         flag_add1 = 1 ;
								      }

								      var add2_url_text = $("#step4Url3AnalyseBtn a").text().trim();
								      var add2_url_input = $('#newUrl3').val();

								      if ( add2_url_text != '' && add2_url_input != '' ) {
								         flag_add2 = 1 ;
								      }

								      if ( flag_add1 == 1 && flag_add2 == 1 ) {
								         $('#boostBtn').removeAttr('disabled');
								         $('#continueBtn').removeAttr('disabled');
								      }
								      else {
								         $('#boostBtn').prop('disabled', true);
								         $('#continueBtn').prop('disabled', true);
								      } 
									/** END remove the disabled step1 buttons **/ 


									// now get mobile speed ===========================

									var apiEndpoint = `//www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_website_url)}&key=${apiKey}&strategy=mobile&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

							    	fetch(apiEndpoint).then(response => {
							            if (!response.ok) {

							            	$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

											$('#boostBtn').prop('disabled', true);
											$('#continueBtn').prop('disabled', true);

											$("#addedNewUrl3").show();
											$("#step4Url3AnalyseBtn").hide();
											$("#step4Url3AnalyseBtn a").html("");

											$(".tabs-2-head").click() ;
											$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

											back_installtion_steps(boostId, 1 , "step1") ;	

							                throw new Error('Please thoroughly review all your URLs within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
							            }
							            return response.json();
							        })
							        .then(data => {
							            // Process your data here

							            if ( data.hasOwnProperty("lighthouseResult") ) {

								            var lighthouseResult = data.lighthouseResult ;

											var requestedUrl = lighthouseResult.requestedUrl ;
											var finalUrl = lighthouseResult.finalUrl ;
											var userAgent = lighthouseResult.userAgent ;
											var fetchTime = lighthouseResult.fetchTime ;
											var environment = JSON.stringify(lighthouseResult.environment) ;
											var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
											var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
											var audits = JSON.stringify(lighthouseResult.audits) ;
											var categories = JSON.stringify(lighthouseResult.categories) ;
											var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
											var i18n = JSON.stringify(lighthouseResult.i18n) ;

											$.ajax({
												url: "inc/check-additional-speed-mobile-fetch.php",
												method: "POST",
												data: {
													managerId: managerId,
													boostId: boostId,
													website_url: url3,
													websiteName: websiteName,
													url_priority: url_priority,
													urlId3: urlId3,
													submitUrl3Btn: submitBtn,
													table_id:table_id,
													request_website_url:request_website_url,
													speedtype: table_id,
													website_id: boostId,
													additional_id: obj.id,
													// lighthouseResult:lighthouseResult,
													requestedUrl:requestedUrl,
													finalUrl:finalUrl,
													userAgent:userAgent,
													fetchTime:fetchTime,
													environment:environment,
													runWarnings:runWarnings,
													configSettings:configSettings,
													audits:audits,
													categories:categories,
													categoryGroups:categoryGroups,
													i18n:i18n,
												},
												dataType: "JSON",
												timeout: 0,
												success: function (data) {

													if (data.status == "done") {

														var content = data.message;

														// Old Speed Score :
														$("."+data_oldy).find(".performance_m").text(content.mobile);
														$("."+data_oldy).find(".accessibility_m").text(content.accessibility);
														$("."+data_oldy).find(".best-practices_m").text(content.bestpractices);
														$("."+data_oldy).find(".seo_m").text(content.seo);
														$("."+data_oldy).find(".pwa_m").text(content.pwa);
														$("." + data_oldy).find(".fcp_m").text(parseFloat(content.FCP).toFixed(2));
														$("." + data_oldy).find(".lcp_m").text(parseFloat(content.LCP).toFixed(2));
														$("." + data_oldy).find(".mpf_m").text(parseFloat(content.MPF).toFixed(2));
														$("." + data_oldy).find(".cls_m").text(parseFloat(content.CLS).toFixed(2));
														$("." + data_oldy).find(".tbt_m").text(parseFloat(content.TBT).toFixed(2));
														$("." + data_oldy).find(".si_m").text(content.SI);


														
														var mobile = content.mobile;
														var myArray = mobile.split("/");
														var arr = myArray['0'];
														$('.new-analyze-speed-for-url3').attr('data-oldmscore',arr).attr('data-currentmscore',arr) ;

														$("#wosa2-mobile-speed").val(content.performance);
														$("#wsa2-mobile-speed").val(0);

														if ( content.performance == 0 || content.performance == "0" ) {
															$(".refresh-nospeedy-parent:eq(2),.refresh-nospeedy-parent:eq(5)").show() ;
														}

														manage_additional_nospeedy_speed(boostId,obj.id,table_id);
														// setTimeout(function(){ $(".loader").hide().html('') ; },1000);

													}
													else {

														$('#boostBtn').prop('disabled', true);
														$('#continueBtn').prop('disabled', true);

														$("#addedNewUrl3").show();
														$("#step4Url3AnalyseBtn").hide();
														$("#step4Url3AnalyseBtn a").html("");

														$(".tabs-2-head").click() ;
														$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

														back_installtion_steps(boostId, 1 , "step1") ;	

														Swal.fire({
															title: 'Error!',
															icon: 'error',
															text: data.message,
															showDenyButton: false,
															showCancelButton: false,
															allowOutsideClick: false,
															allowEscapeKey: false,
															confirmButtonText: 'Close',
														}).then((result) => {
															
														}) ;

														$('#boostBtn').prop('disabled', true);
														$('#continueBtn').prop('disabled', true);


														// setTimeout(function(){ $(".loader").hide().html('') ; },1000);
													}

												},
												error: function (xhr) { // if error occured
													back_installtion_steps(boostId, 1 , "step1") ;	
													script_loading++;
													console.error(xhr.statusText + xhr.responseText);
													// setTimeout(function(){ $(".loader").hide().html('') ; },1000);

													$('#boostBtn').prop('disabled', true);
													$('#continueBtn').prop('disabled', true);

												},
												complete: function () {
													getting_no_speedy2 = 0 ;
												}

											});

										}
										else {

											$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

											$('#boostBtn').prop('disabled', true);
											$('#continueBtn').prop('disabled', true);

											$("#addedNewUrl3").show();
											$("#step4Url3AnalyseBtn").hide();
											$("#step4Url3AnalyseBtn a").html("");

											$(".tabs-2-head").click() ;
											$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

											getting_no_speedy2 = 0 ;

											back_installtion_steps(boostId, 1 , "step1") ;	

											Swal.fire({
												title: "Error!",
												icon: "error",
												text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
												showDenyButton: false,
												showCancelButton: false,
												allowOutsideClick: false,
												allowEscapeKey: false,
												confirmButtonText: 'Close',
											}).then((result) => {
												if (result.isConfirmed) {
													// $("#addedNewUrl")[0].reset() ;
												}
											}) ; 

											$('#boostBtn').prop('disabled', true);
											$('#continueBtn').prop('disabled', true);

											// Old Speed Score :
											$("."+data_oldy).find(".performance_m").text("");
											$("."+data_oldy).find(".accessibility_m").text("");
											$("."+data_oldy).find(".best-practices_m").text("");
											$("."+data_oldy).find(".seo_m").text("");
											$("."+data_oldy).find(".pwa_m").text("");
											$("."+data_oldy).find(".fcp_m").text("");
											$("."+data_oldy).find(".lcp_m").text("");
											$("."+data_oldy).find(".mpf_m").text("");
											$("."+data_oldy).find(".cls_m").text("");
											$("."+data_oldy).find(".tbt_m").text("");

											$("."+data_oldy).find(".performance").text("");
											$("."+data_oldy).find(".accessibility").text("");
											$("."+data_oldy).find(".best-practices").text("");
											$("."+data_oldy).find(".seo").text("");
											$("."+data_oldy).find(".pwa").text("");
											$("."+data_oldy).find(".fcp").text("");
											$("."+data_oldy).find(".lcp").text("");
											$("."+data_oldy).find(".mpf").text("");
											$("."+data_oldy).find(".cls").text("");
											$("."+data_oldy).find(".tbt").text("");

											$('.new-analyze-speed-for-url3').attr('data-oldmscore',"").attr('data-currentmscore',"") ;
											$("#wsa2-mobile-speed , #wosa2-mobile-speed , #wsa2-desktop-speed , #wosa2-desktop-speed").val(0);

											$("#addedNewUrl3").show() ;
											$("#step4Url3AnalyseBtn").hide();
											$("#step4Url3AnalyseBtn a").text("");

											$(".tabs-2-head").click() ;
											$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

											$.ajax({
												url: "inc/remove-additional-url.php",
												method: "POST",
												data: {
													website_id: boostId,
													additional_id: obj.id,
													action: 'remove-additional-url' 
												},
												dataType: "JSON",
												timeout: 0,
												success: function (data) {

												},
												error: function (xhr) { 
													console.error(xhr.statusText + xhr.responseText);
												},
												complete: function () {
												}

											});

										}


							        })
							        .catch(error => {

							        	$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

										$('#boostBtn').prop('disabled', true);
										$('#continueBtn').prop('disabled', true);

										$("#addedNewUrl3").show();
										$("#step4Url3AnalyseBtn").hide();
										$("#step4Url3AnalyseBtn a").html("");

										$(".tabs-2-head").click() ;
										$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

										back_installtion_steps(boostId, 1 , "step1") ;	

							        	getting_no_speedy2 = 0 ;

							            console.error('Fetch error:', error);

										Swal.fire({
											title: "Error!",
											icon: "error",
											text: error,
											showDenyButton: false,
											showCancelButton: false,
											allowOutsideClick: false,
											allowEscapeKey: false,
											confirmButtonText: 'Close',
										}).then((result) => {
											if (result.isConfirmed) {
												// $("#addedNewUrl")[0].reset() ;
											}
										}) ; 

										// setTimeout(function(){$(".loader").hide().html('') ;},1000);
							        });

								}
								else {

									$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

									$('#boostBtn').prop('disabled', true);
									$('#continueBtn').prop('disabled', true);

									$("#addedNewUrl3").show();
									$("#step4Url3AnalyseBtn").hide();
									$("#step4Url3AnalyseBtn a").html("");

									$(".tabs-2-head").click() ;
									$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

									back_installtion_steps(boostId, 1 , "step1") ;	

									getting_no_speedy2 = 0 ;

									Swal.fire({
										title: 'Error!',
										icon: 'error',
										text: obj.message,
										showDenyButton: false,
										showCancelButton: false,
										allowOutsideClick: false,
										allowEscapeKey: false,
										confirmButtonText: 'Close',
									}).then((result) => {
										if (result.isConfirmed) {
											// $("#addedNewUrl")[0].reset() ;
										}
									}) ;

									// setTimeout(function(){$(".loader").hide().html('') ;},1000);
								}

							},
							error: function (xhr, status, error) {

								back_installtion_steps(boostId, 1 , "step1") ;	
								console.error(xhr.responseText);
								// setTimeout(function(){$(".loader").hide().html('') ;},1000);
							}
						});

					}
					else {

						$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

						$('#boostBtn').prop('disabled', true);
						$('#continueBtn').prop('disabled', true);

						$("#addedNewUrl3").show();
						$("#step4Url3AnalyseBtn").hide();
						$("#step4Url3AnalyseBtn a").html("");

						$(".tabs-2-head").click() ;
						$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

						getting_no_speedy2 = 0 ;

						back_installtion_steps(boostId, 1 , "step1") ;	

						Swal.fire({
							title: "Error!",
							icon: "error",
							text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
							showDenyButton: false,
							showCancelButton: false,
							allowOutsideClick: false,
							allowEscapeKey: false,
							confirmButtonText: 'Close',
						}).then((result) => {
							if (result.isConfirmed) {
								// $("#addedNewUrl")[0].reset() ;
							}
						}) ; 

						// setTimeout(function(){$(".loader").hide().html('') ;},1000);

					}

	            }
	            else {

	            	$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

					$('#boostBtn').prop('disabled', true);
					$('#continueBtn').prop('disabled', true);

					$("#addedNewUrl3").show();
					$("#step4Url3AnalyseBtn").hide();
					$("#step4Url3AnalyseBtn a").html("");

					$(".tabs-2-head").click() ;
					$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

					back_installtion_steps(boostId, 1 , "step1") ;	

	            	getting_no_speedy2 = 0 ;

					Swal.fire({
						title: "Error!",
						icon: "error",
						text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
						showDenyButton: false,
						showCancelButton: false,
						allowOutsideClick: false,
						allowEscapeKey: false,
						confirmButtonText: 'Close',
					}).then((result) => {
						if (result.isConfirmed) {
							// $("#addedNewUrl")[0].reset() ;
						}
					}) ; 


					// setTimeout(function(){$(".loader").hide().html('') ;},1000);

	            }

	            // You can access various properties of data to get speed and performance metrics
	        })
	        .catch(error => {

	        	$('html, body').animate({ scrollTop: $("#step1").offset().top - 100 }, 500);

				$('#boostBtn').prop('disabled', true);
				$('#continueBtn').prop('disabled', true);

				$("#addedNewUrl3").show();
				$("#step4Url3AnalyseBtn").hide();
				$("#step4Url3AnalyseBtn a").html("");

				$(".tabs-2-head").click() ;
				$("#step2 , #step3 , #step4").addClass("d-none").removeClass("complited") ;

	        	getting_no_speedy2 = 0 ;

	            console.error('Fetch error:', error);

	            back_installtion_steps(boostId, 1 , "step1") ;

				Swal.fire({
					title: "Error!",
					icon: "error",
					text: error,
					showDenyButton: false,
					showCancelButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					confirmButtonText: 'Close',
				}).then((result) => {
					if (result.isConfirmed) {
						// $("#addedNewUrl")[0].reset() ;
					}
				}) ; 

				// setTimeout(function(){$(".loader").hide().html('') ;},1000);
	        });

	    	//  ============================================

		}
	});	
	
}) ;



	var script_loading = 0;
	var speedtype="";
	//url1-anaylse-speed
	$(".reanalyze-btn-").click(function () {

		reloadSpeed = $(this);
		script_loading = 0;
		var tab = $(this).attr("tab");
		// console.log(tab);

		var website_url = $(this).attr("data-website_url");
		speedtype = $(this).attr("data-table_id");
		var website_id = $(this).attr("data-website_id");
		var table_id = $(this).attr("data-table_id");


		$.ajax({
			url: "inc/check-speed.php",
			method: "POST",
			data: {
				website_url: website_url,
				speedtype: speedtype,
				website_id: website_id,
				table_id: table_id
			},
			dataType: "JSON",
			timeout: 0,
			beforeSend: function () {
				$(".loader").show().html("<div class='loader_s 456'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
				loaderest()
			},
			success: function (data) {
				script_loading++;
				if (data.status == "done") {

					var content = data.message;
					console.log(content);
					if (content.desktop == "0/100") {
						try_again($(".reanalyze-btn-new").attr("data-website_id"));
					}

					if (content.type == "desktop" && content.speedtype == 'new') {
						// console.log("new");

						// $(".inst_speed_new").find(".desktop").text(content.desktop);
						// $(".inst_speed_new").find(".mobile").text(content.mobile);
						$(".inst_speed_new").find(".performance").text(content.desktop);
						$(".inst_speed_new").find(".accessibility").text(content.accessibility);
						$(".inst_speed_new").find(".best-practices").text(content.bestpractices);
						$(".inst_speed_new").find(".seo").text(content.seo);
						$(".inst_speed_new").find(".pwa").text(content.pwa);
						$(".inst_speed_new").find(".fcp").text(content.FCP.toFixed(2));
						$(".inst_speed_new").find(".lcp").text(content.LCP.toFixed(2));
						$(".inst_speed_new").find(".mpf").text(content.MPF.toFixed(2));
						$(".inst_speed_new").find(".cls").text(content.CLS.toFixed(2));
						$(".inst_speed_new").find(".tbt").text(content.TBT.toFixed(2));

						$(".inst_speed_new-container").show();

					}
					else {
						// $(".inst_speed").find(".desktop").text(content.desktop);
						// $(".inst_speed").find(".mobile").text(content.desktop);
						$(".inst_speed").find(".performance").text(content.desktop);
						$(".inst_speed").find(".accessibility").text(content.accessibility);
						$(".inst_speed").find(".best-practices").text(content.bestpractices);
						$(".inst_speed").find(".seo").text(content.seo);
						$(".inst_speed").find(".pwa").text(content.pwa);
						$(".inst_speed").find(".fcp").text(content.FCP.toFixed(2));
						$(".inst_speed").find(".lcp").text(content.LCP.toFixed(2));
						$(".inst_speed").find(".mpf").text(content.MPF.toFixed(2));
						$(".inst_speed").find(".cls").text(content.CLS.toFixed(2));
						$(".inst_speed").find(".tbt").text(content.TBT.toFixed(2));

						// $(".tabs-1-head  .icon").removeClass("fa-angle-up");
						// $(".tabs-1-head  .icon").addClass("fa-angle-down");
						// $(".tabs-2-head").show();
						// $(".tabs-1").hide();
						// $(".tabs-2").show();                           	
					}


				}
				else {
					$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}

				if (tab == "#step3") {
					$('html, body').animate({ scrollTop: $("#step3").offset().top + 150 }, 500 );
				}

			},
			error: function (xhr) { // if error occured
				script_loading++;
				console.error(xhr.statusText + xhr.responseText);
			},
			complete: function () {
				if (script_loading == 2) {
					$(".loader").hide().html("Please Wait...");

					manage_speed($(".verification_btn").attr("data-id"));
				}
			}
		});


		$.ajax({
			url: "inc/check-speed-mobile.php",
			method: "POST",
			data: {
				website_url: website_url,
				speedtype: speedtype,
				website_id: website_id
			},
			dataType: "JSON",
			timeout: 0,
			beforeSend: function () {
				$(".loader").show().html("<div class='loader_s 567'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analyzing your website. It might take 2-3 mins</p>  <p><span class='auto-type'></span></p>   </div>");
				loaderest()

			},
			success: function (data) {
				script_loading++;
				if (data.status == "done") {


					var content = data.message;
					console.log(content);

					if (content.desktop == "0/100") {
						try_again($(".reanalyze-btn-new").attr("data-website_id"));
					}

					if (content.type == "mobile" && content.speedtype == "new") {
						// console.log("new");

						// $(".inst_speed_new").find(".desktop").text(content.desktop);
						// $(".inst_speed_new").find(".mobile").text(content.mobile);
						$(".inst_speed_new").find(".performance_m").text(content.mobile);
						$(".inst_speed_new").find(".accessibility_m").text(content.accessibility);
						$(".inst_speed_new").find(".best-practices_m").text(content.bestpractices);
						$(".inst_speed_new").find(".seo_m").text(content.seo);
						$(".inst_speed_new").find(".pwa_m").text(content.pwa);
						$(".inst_speed_new").find(".fcp_m").text(content.FCP.toFixed(2));
						$(".inst_speed_new").find(".lcp_m").text(content.LCP.toFixed(2));
						$(".inst_speed_new").find(".mpf_m").text(content.MPF.toFixed(2));
						$(".inst_speed_new").find(".cls_m").text(content.CLS.toFixed(2));
						$(".inst_speed_new").find(".tbt_m").text(content.TBT.toFixed(2));

						$(".inst_speed_new").removeClass("d-none");

						$(".inst_speed_new-container").show();


					}
					else {


						$(".inst_speed").find(".performance_m").text(content.mobile);
						$(".inst_speed").find(".accessibility_m").text(content.accessibility);
						$(".inst_speed").find(".best-practices_m").text(content.bestpractices);
						$(".inst_speed").find(".seo_m").text(content.seo);
						$(".inst_speed").find(".pwa_m").text(content.pwa);
						$(".inst_speed").find(".fcp_m").text(content.FCP.toFixed(2));
						$(".inst_speed").find(".lcp_m").text(content.LCP.toFixed(2));
						$(".inst_speed").find(".mpf_m").text(content.MPF.toFixed(2));
						$(".inst_speed").find(".cls_m").text(content.CLS.toFixed(2));
						$(".inst_speed").find(".tbt_m").text(content.TBT.toFixed(2));

						// $(".tabs-1-head  .icon").removeClass("fa-angle-up");
						// $(".tabs-1-head  .icon").addClass("fa-angle-down");
						// $(".tabs-2-head").show();
						// $(".tabs-1").hide();
						// $(".tabs-2").show();                           	
					}


				}
				else {
					$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}

			},
			error: function (xhr) { // if error occured
				script_loading++;
				console.error(xhr.statusText + xhr.responseText);
			},
			complete: function () {
				if (script_loading == 2) {
					$(".loader").hide().html("Please Wait...");

					manage_speed($(".verification_btn").attr("data-id"));
				}

			}

		});

	});




	// for additional urls
	$(".additional-reanalyse-updated-speed").click(function(){

		reloadSpeed = $(this);
		var script_loading = 0;
		var tab = $(this).attr("tab");
		// console.log(tab);

		var website_url = $(this).attr("data-website_url");
		speedtype = $(this).attr("data-table_id");
		var website_id = $(this).attr("data-website_id");
		var table_id = $(this).attr("data-table_id");


		var additional_id = $(this).attr("data-additional-id");
		var data_boosted = $(this).attr("data-boosted");
		var data_oldy = $(this).attr("data-oldy");
		var data_container = $(this).attr("data-container");

		$.ajax({
			url: "inc/check-additional-speed.php",
			method: "POST",
			data: {
				website_url: website_url,
				speedtype: speedtype,
				website_id: website_id,
				table_id: table_id,
				additional_id: additional_id,
				action: "check-additional-speed"
			},
			dataType: "JSON",
			timeout: 0,
			beforeSend: function () {

				$(".loader").show().html("<div class='loader_s 678'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
				loaderest()
			},
			success: function (data) {
				console.log(data) ;
				script_loading++;
				if (data.status == "done") {

					var content = data.message;

					var boosted_desktop = $("."+data_boosted).find("table.desktop") ;
					var boosted_mobile = $("."+data_boosted).find("table.mobile") ;
					

					// Boosted Speed Score With website speedy :
					$(boosted_desktop).find(".performance").text(content.desktop);
					$(boosted_desktop).find(".accessibility").text(content.accessibility);
					$(boosted_desktop).find(".best-practices").text(content.bestpractices);
					$(boosted_desktop).find(".seo").text(content.seo);
					$(boosted_desktop).find(".pwa").text(content.pwa);
					$(boosted_desktop).find(".fcp").text(content.FCP.toFixed(2));
					$(boosted_desktop).find(".lcp").text(content.LCP.toFixed(2));
					$(boosted_desktop).find(".mpf").text(content.MPF.toFixed(2));
					$(boosted_desktop).find(".cls").text(content.CLS.toFixed(2));
					$(boosted_desktop).find(".tbt").text(content.TBT.toFixed(2));
					$("."+data_container).show();

				}
				else {
					$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}
			},
			error: function (xhr) {
				console.error(xhr.statusText + xhr.responseText);
			},
			complete: function () {
				setTimeout(() => {
					$(".loader").hide();				
				}, 2500);
				// $(".loader").hide();

				if (script_loading >= 2) {
					manage_additional_speed(website_id,additional_id,table_id) ;
				}
			}
		});

		$.ajax({
			url: "inc/check-additional-speed-mobile.php",
			method: "POST",
			data: {
				website_url: website_url,
				speedtype: speedtype,
				website_id: website_id,
				additional_id: additional_id,
				action: "check-additional-speed-mobile"
			},
			dataType: "JSON",
			timeout: 0,
			beforeSend: function () {
				
				$(".loader").show().html("<div class='loader_s 789'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
			    loaderest()
			},
			success: function (data) {

				console.log(data);
				script_loading++;

				if (data.status == "done") {

					var content = data.message;

					// if (content.desktop == "0/100") {
					// 	try_again($(".reanalyze-btn-new").attr("data-website_id"));
					// }

					var boosted_desktop = $("."+data_boosted).find("table.desktop") ;
					var boosted_mobile = $("."+data_boosted).find("table.mobile") ;

					// Old Speed Score :
					/***** *****/
					// $("."+data_oldy).find(".performance_m").text($(boosted_mobile).find(".performance_m").text());
					// $("."+data_oldy).find(".accessibility_m").text($(boosted_mobile).find(".accessibility_m").text());
					// $("."+data_oldy).find(".best-practices_m").text($(boosted_mobile).find(".best-practices_m").text());
					// $("."+data_oldy).find(".seo_m").text($(boosted_mobile).find(".seo_m").text());
					// $("."+data_oldy).find(".pwa_m").text($(boosted_mobile).find(".pwa_m").text());
					// $("."+data_oldy).find(".fcp_m").text($(boosted_mobile).find(".fcp_m").text());
					// $("."+data_oldy).find(".lcp_m").text($(boosted_mobile).find(".lcp_m").text());
					// $("."+data_oldy).find(".mpf_m").text($(boosted_mobile).find(".mpf_m").text());
					// $("."+data_oldy).find(".cls_m").text($(boosted_mobile).find(".cls_m").text());
					// $("."+data_oldy).find(".tbt_m").text($(boosted_mobile).find(".tbt_m").text());
					

					// Boosted Speed Score With website speedy :
					$(boosted_mobile).find(".performance_m").text(content.mobile);
					$(boosted_mobile).find(".accessibility_m").text(content.accessibility);
					$(boosted_mobile).find(".best-practices_m").text(content.bestpractices);
					$(boosted_mobile).find(".seo_m").text(content.seo);
					$(boosted_mobile).find(".pwa_m").text(content.pwa);
					$(boosted_mobile).find(".fcp_m").text(content.FCP.toFixed(2));
					$(boosted_mobile).find(".lcp_m").text(content.LCP.toFixed(2));
					$(boosted_mobile).find(".mpf_m").text(content.MPF.toFixed(2));
					$(boosted_mobile).find(".cls_m").text(content.CLS.toFixed(2));
					$(boosted_mobile).find(".tbt_m").text(content.TBT.toFixed(2));
					$("."+data_container).show();

				}
				else {
					$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}

			},
			error: function (xhr) { // if error occured
				script_loading++;
				console.error(xhr.statusText + xhr.responseText);
			},
			complete: function () {
				setTimeout(() => {
					$(".loader").hide();				
				}, 2500);

				if (script_loading >= 2) {
					manage_additional_speed(website_id,additional_id,table_id) ;
				}
			}

		});

	});




function manage_additional_speed(website_id,additional_id,additional_url) {

	$.ajax({
		type: "POST",
		url: "inc/manage_additional_speed.php",
		data: {
			website_id: website_id,
			additional_id: additional_id,
			additional_url: additional_url,
			action: "manage_additional_speed"
		},
		dataType: "json",
		encode: true,
	}).done(function (data) {
		console.log(data);

		// $(".url3_inst_speed_new-container , .url2_inst_speed_new-container").hide();

		if (additional_url == "newUrl3") {
			$(".url3_inst_speed_new-container").show();
		}
		else if (speedtype == "newUrl2") {
			$(".url2_inst_speed_new-container").show();
		}

		setTimeout(function(){ $(".loader").hide().html('') ; },1000);
	});

}



//manage-additional-speed-for-nospeedy
function manage_additional_nospeedy_speed(website_id,additional_id,additional_url) {

	$.ajax({
		type: "POST",
		url: "inc/manage_additional_speed.php",
		data: {
			website_id: website_id,
			additional_id: additional_id,
			additional_url: additional_url,
			action: "manage_additional_nospeedy_speed"
		},
		dataType: "json",
		encode: true,
	}).done(function (data) {
		console.log(data);

		// $(".url3_inst_speed_new-container , .url2_inst_speed_new-container").hide();

		if (additional_url == "newUrl3") {
			$(".url3_inst_speed_new-container").show();
		}
		else if (speedtype == "newUrl2") {
			$(".url2_inst_speed_new-container").show();
		}

		// setTimeout(function(){ $(".loader").hide().html('') ; },1000);


	}).always(function (dataOrjqXHR, textStatus, jqXHRorErrorThrown) { 

		var flag_add1 = flag_add2 = 0 ;

		var add1_url_text = $("#step4AnalyseBtn a").text().trim();
		var add1_url_input = $('#newUrl').val();

		if ( add1_url_text != '' && add1_url_input != '' ) {
			flag_add1 = 1 ;
		}

		var add2_url_text = $("#step4Url3AnalyseBtn a").text().trim();
		var add2_url_input = $('#newUrl3').val();

		if ( add2_url_text != '' && add2_url_input != '' ) {
			flag_add2 = 1 ;
		}

		if ( flag_add1 == 1 && flag_add2 == 1 ) {
			$('#boostBtn').removeAttr('disabled');
			$('#continueBtn').removeAttr('disabled');

			setTimeout(function(){

				started_reanlyse_additional_url = 1 ;

				if ( getting_no_speedy1 == 0 && getting_no_speedy2 == 0 ) {

					setTimeout(function() {

						reloadSpeed = $(".new-analyze-speed-for-3url");
						script_loading = 0;
						var tab = $(".new-analyze-speed-for-3url").attr("tab");

						var website_url = $(".new-analyze-speed-for-3url").attr("data-website_url1");
						speedtype = $(".new-analyze-speed-for-3url").attr("data-table_url_id");
						var website_id = $(".new-analyze-speed-for-3url").attr("data-website_url1_id");
						var table_id = $(".new-analyze-speed-for-3url").attr("data-table_url_id");
						var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted");
						
						var old_mobile_speed = Number($("#wos-mobile-speed").val()) ; //before script-install
						var current_mobile_speed = Number($("#ws-mobile-speed").val()) ; //after script-install

						reanalyze_count = dreanalyze_count = 1 ;
						analyse_updated_speed = reanalyze_add1_count = dreanalyze_add1_count = reanalyze_add2_count = dreanalyze_add2_count = 0 ;

						check_reanalyze_speed(website_url,speedtype,website_id,table_id,boosted_speed,old_mobile_speed,current_mobile_speed) ;
						check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id,boosted_speed,old_mobile_speed,current_mobile_speed) ;

					},500);
				}
				else {

					new_interval_analyse = setInterval(function(argument) {

						if ( getting_no_speedy1 == 0 && getting_no_speedy2 == 0 ) {

							clearInterval(new_interval_analyse) ;

							reloadSpeed = $(".new-analyze-speed-for-3url");
							script_loading = 0;
							var tab = $(".new-analyze-speed-for-3url").attr("tab");

							var website_url = $(".new-analyze-speed-for-3url").attr("data-website_url1");
							speedtype = $(".new-analyze-speed-for-3url").attr("data-table_url_id");
							var website_id = $(".new-analyze-speed-for-3url").attr("data-website_url1_id");
							var table_id = $(".new-analyze-speed-for-3url").attr("data-table_url_id");
							var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted");

							reanalyze_count = dreanalyze_count = 1 ;
							analyse_updated_speed = reanalyze_add1_count = dreanalyze_add1_count = reanalyze_add2_count = dreanalyze_add2_count = 0 ;

							check_reanalyze_speed(website_url,speedtype,website_id,table_id,boosted_speed,old_mobile_speed,current_mobile_speed) ;
							check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id,boosted_speed,old_mobile_speed,current_mobile_speed) ;
						}

					},1000);

				}

			},1000);

		}
		else {
			$('#boostBtn').prop('disabled', true);
			$('#continueBtn').prop('disabled', true);
		}

		// setTimeout(function(){ $(".loader").hide().html('') ; },1000);
	});

}


// $(".compare-to-old-speed").click(function(){
// 	var noSpeedyVal = $(this).attr('data-nospeedy');
// 	var hideBlock = $(this).attr("data-oldy");
// 	// console.log(hideBlock);
// 	if(noSpeedyVal==1){
// 		console.log('nospeedy')
// 		$('.' + hideBlock).css('display', 'block');
// 		$('.' + hideBlock).prop('disabled', true);
// 		return false;
// 	}else{

// 	reloadSpeed = $(this);
// 	var $clickedElement = $(this); 
// 	var script_loading = 0;
// 	var tab = $(this).attr("tab");
// 	// console.log(tab);

// 	var website_url = $(this).attr("data-website_url");
// 	speedtype = $(this).attr("data-table_id");
// 	var website_id = $(this).attr("data-website_id");
// 	var table_id = $(this).attr("data-table_id");


// 	var additional_id = $(this).attr("data-additional-id");
// 	var data_boosted = $(this).attr("data-boosted");
// 	var data_oldy = $(this).attr("data-oldy");
// 	var data_container = $(this).attr("data-container");

// 	if ( url == "" ) {
// 		alert(" URL not found. ") ;
// 	} 
// 	else {

// 		var url = $(this).attr("data-url") ;

// 		if ( url.indexOf("?") >= 0 ) {
// 			url = url + "&nospeedy" ;
// 		}
// 		else {
// 			url = url + "?nospeedy" ;
// 		}
// 	}
// 	// var compare_url = "//pagespeed.web.dev/analysis/https-swasticlothing-com/o8o9ljk8j5?form_factor=mobile" ;
// 	// var compare_url = "//pagespeed.web.dev/analysis?url=https%3A%2F%2Fecommerceseotools.com%2F" ;

// 	var compare_url = "//pagespeed.web.dev/analysis?url="+url ;

// 	$.ajax({
// 		url: "inc/check-additional-speed.php",
// 		method: "POST",
// 		data: {
// 			website_url: compare_url,
// 			speedtype: speedtype,
// 			website_id: website_id,
// 			table_id: table_id,
// 			additional_id: additional_id,
// 			action: "check-additional-speed-nospeedy"
// 		},
// 		dataType: "JSON",
// 		timeout: 0,
// 		beforeSend: function () {

// 			$(".loader").show().html("<div class='loader_s'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins<p></div>");
// 		},
// 		success: function (data) {
// 			console.log(data) ;
// 			$('.' + hideBlock).attr('disabled', true);
// 			script_loading++;
// 			if (data.status == "done") {
// 				$clickedElement.attr('data-nospeedy', 1);
// 				var content = data.message;

// 				var boosted_desktop = $("."+data_boosted).find("table.desktop") ;
// 				var boosted_mobile = $("."+data_boosted).find("table.mobile") ;

// 				// Old Speed Score :
// 				/*****  *****/
// 				$("."+data_oldy).find(".performance").text(content.desktop);
// 				$("."+data_oldy).find(".accessibility").text(content.accessibility);
// 				$("."+data_oldy).find(".best-practices").text(content.bestpractices);
// 				$("."+data_oldy).find(".seo").text(content.seo);
// 				$("."+data_oldy).find(".pwa").text(content.pwa);
// 				$("."+data_oldy).find(".fcp").text(content.FCP);
// 				$("."+data_oldy).find(".lcp").text(content.LCP);
// 				$("."+data_oldy).find(".mpf").text(content.MPF);
// 				$("."+data_oldy).find(".cls").text(content.CLS);
// 				$("."+data_oldy).find(".tbt").text(content.TBT);
// 				$('.' + hideBlock).show();
				

// 				// // Boosted Speed Score With website speedy :
// 				// $(boosted_desktop).find(".performance").text(content.desktop);
// 				// $(boosted_desktop).find(".accessibility").text(content.accessibility);
// 				// $(boosted_desktop).find(".best-practices").text(content.bestpractices);
// 				// $(boosted_desktop).find(".seo").text(content.seo);
// 				// $(boosted_desktop).find(".pwa").text(content.pwa);
// 				// $(boosted_desktop).find(".fcp").text(content.FCP);
// 				// $(boosted_desktop).find(".lcp").text(content.LCP);
// 				// $(boosted_desktop).find(".mpf").text(content.MPF);
// 				// $(boosted_desktop).find(".cls").text(content.CLS);
// 				// $(boosted_desktop).find(".tbt").text(content.TBT);
// 				// $("."+data_container).show();

// 			}
// 			else {
// 				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
// 			}
// 		},
// 		error: function (xhr) {
// 			console.error(xhr.statusText + xhr.responseText);
// 		},
// 		complete: function () {
// 			setTimeout(() => {
// 					$(".loader").hide();				
// 			}, 2500);
// 			// $(".loader").hide();

// 			if (script_loading >= 2) {
// 				manage_additional_nospeedy_speed(website_id,additional_id,table_id) ;
// 			}
// 		}
// 	});

// 	$.ajax({
// 		url: "inc/check-additional-speed-mobile.php",
// 		method: "POST",
// 		data: {
// 			website_url: compare_url,
// 			speedtype: speedtype,
// 			website_id: website_id,
// 			additional_id: additional_id,
// 			action: "check-additional-speed-mobile-nospeedy"
// 		},
// 		dataType: "JSON",
// 		timeout: 0,
// 		beforeSend: function () {
			
// 			$(".loader").show().html("<div class='loader_s'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins<p></div>");
// 		},
// 		success: function (data) {

// 			console.log(data);
// 			script_loading++;

// 			if (data.status == "done") {

// 				var content = data.message;

// 				// if (content.desktop == "0/100") {
// 				// 	try_again($(".reanalyze-btn-new").attr("data-website_id"));
// 				// }

// 				var boosted_desktop = $("."+data_boosted).find("table.desktop") ;
// 				var boosted_mobile = $("."+data_boosted).find("table.mobile") ;
// 				// console.log(content.mobile);return false;

// 				// Old Speed Score :
// 				/***** *****/
// 				$("."+data_oldy).find(".performance_m").text(content.mobile);
// 				$("."+data_oldy).find(".accessibility_m").text(content.accessibility);
// 				$("."+data_oldy).find(".best-practices_m").text(content.bestpractices);
// 				$("."+data_oldy).find(".seo_m").text(content.seo);
// 				$("."+data_oldy).find(".pwa_m").text(content.pwa);
// 				$("."+data_oldy).find(".fcp_m").text(content.FCP);
// 				$("."+data_oldy).find(".lcp_m").text(content.LCP);
// 				$("."+data_oldy).find(".mpf_m").text(content.MPF);
// 				$("."+data_oldy).find(".cls_m").text(content.CLS);
// 				$("."+data_oldy).find(".tbt_m").text(content.TBT);
// 				$('.' + hideBlock).show();
				

// 				// // Boosted Speed Score With website speedy :
// 				// $(boosted_mobile).find(".performance_m").text(content.mobile);
// 				// $(boosted_mobile).find(".accessibility_m").text(content.accessibility);
// 				// $(boosted_mobile).find(".best-practices_m").text(content.bestpractices);
// 				// $(boosted_mobile).find(".seo_m").text(content.seo);
// 				// $(boosted_mobile).find(".pwa_m").text(content.pwa);
// 				// $(boosted_mobile).find(".fcp_m").text(content.FCP);
// 				// $(boosted_mobile).find(".lcp_m").text(content.LCP);
// 				// $(boosted_mobile).find(".mpf_m").text(content.MPF);
// 				// $(boosted_mobile).find(".cls_m").text(content.CLS);
// 				// $(boosted_mobile).find(".tbt_m").text(content.TBT);
// 				// $("."+data_container).show();

// 			}
// 			else {
// 				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
// 			}

// 		},
// 		error: function (xhr) { // if error occured
// 			script_loading++;
// 			console.error(xhr.statusText + xhr.responseText);
// 		},
// 		complete: function () {
// 			setTimeout(() => {
// 					$(".loader").hide();				
// 				}, 2500);
// 			// $(".loader").hide();

// 			if (script_loading >= 2) {
// 				manage_additional_nospeedy_speed(website_id,additional_id,table_id) ;
// 			}
// 		}

// 	});
//    }

// });
</script>


<!-- //123boost-speed-now -->

<script>
/*** Analyse Updated Speed ***/ 

var analyse_updated_speed = 0 ;
var reanalyze_count = 1 ;
var dreanalyze_count = 1 ;
var reanalyze_add1_count = 0 ;
var dreanalyze_add1_count = 0 ;
var reanalyze_add2_count = 0 ;
var dreanalyze_add2_count = 0 ;

var reanalyze_interval = '' ;
var reanalyze_interval_request = [] ;

var analyse_updated_speed_mobile = 0 ; 
var analyse_updated_speed_desktop = 1 ; 

function generateRandomAlphanumeric(length) {
	const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	let result = '';

	for (let i = 0; i < length; i++) {
		const randomIndex = Math.floor(Math.random() * characters.length);
		result += characters.charAt(randomIndex);
	}

	return result;
}


function check_reanalyze_speed(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional_id=0) {

	if ( dreanalyze_count <= 3 && dreanalyze_add1_count == 0 && dreanalyze_add2_count == 0 ) {
		tdreanalyze_count = dreanalyze_count ;
	}
	else if ( dreanalyze_count == 3 && dreanalyze_add1_count <= 3 && dreanalyze_add2_count == 0 ) {
		tdreanalyze_count = dreanalyze_add1_count ;
	}
	else if ( dreanalyze_count == 3 && dreanalyze_add1_count == 3 && dreanalyze_add2_count <= 3 ) {
		tdreanalyze_count = dreanalyze_add2_count ;
	}
	else {
		tdreanalyze_count = 0 ;
	}
	
	if ( tdreanalyze_count > 0 ) {

		if ( started_reanlyse_additional_url != 1 ) {
			$(".loader").show().html("<div class='loader_s 890'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
			loaderest() ;
		}

         
		var api_key = $("meta[name='google_pagespeed_api']").attr("content") ;
		var random_string = generateRandomAlphanumeric(8);

        if ( website_url.includes("?") ) {
            var request_url = website_url+"&"+random_string ;
        }
        else {
            var request_url = website_url+"?"+random_string ;
        }


		// get speed for desktop
		var apiEndpoint = `//www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

	    fetch(apiEndpoint).then(response => {
	        console.log('test');
	        if (!response.ok) {
	            throw new Error('Please thoroughly review all your Domain ( '+website_url+' ) URL. Ensure that URL is correct.');
	        }
	        return response.json();
	    })
	    .then(data => {

	        // Process your data here
	        if ( data.hasOwnProperty("lighthouseResult") ) {

				var lighthouseResult = data.lighthouseResult ;

				var requestedUrl = lighthouseResult.requestedUrl ;
				var finalUrl = lighthouseResult.finalUrl ;
				var userAgent = lighthouseResult.userAgent ;
				var fetchTime = lighthouseResult.fetchTime ;
				var environment = JSON.stringify(lighthouseResult.environment) ;
				var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
				var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
				var audits = JSON.stringify(lighthouseResult.audits) ;
				var categories = JSON.stringify(lighthouseResult.categories) ;
				var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
				var i18n = JSON.stringify(lighthouseResult.i18n) ;

				var desktop = lighthouseResult.categories.performance.score ;
				desktop = Math.round(desktop * 100) ;

				if ( desktop > 0 ) {

					$.ajax({
						url: "inc/check-reanalyze-speed.php",
						method: "POST",
						data: {
							website_url: website_url,
							speedtype: speedtype,
							website_id: website_id,
							table_id: table_id,
							reanalyze_count:tdreanalyze_count,
							additional_id:additional_id,
							// ========================
	                        // lighthouseResult:lighthouseResult,
	                        requestedUrl:requestedUrl,
	                        finalUrl:finalUrl,
	                        userAgent:userAgent,
	                        fetchTime:fetchTime,
	                        environment:environment,
	                        runWarnings:runWarnings,
	                        configSettings:configSettings,
	                        audits:audits,
	                        categories:categories,
	                        categoryGroups:categoryGroups,
	                        i18n:i18n,
						},
						dataType: "JSON",
						timeout: 0,
						success: function (data1) {

							// $(".inst_speed_new-container").show() ;

							// script_loading++;

							console.log("Speed data : "+website_url) ; 
							console.log(data1) ;
							
						},
						error: function (xhr) { 
							// if error occured
							// script_loading++;
							console.error(xhr.statusText + xhr.responseText);
						},
						complete: function () {

							analyse_updated_speed++ ; 

							if ( dreanalyze_count < 3 ) {
								dreanalyze_count++ ;
								
								check_reanalyze_speed(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) ;
							}

							if ( dreanalyze_count == 3 && dreanalyze_add1_count < 3 ) {
								dreanalyze_add1_count++ ;
								var website_url2 = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
								var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url2_id") ;
								var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted2") ;
								var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

								check_reanalyze_speed(website_url2,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional2_id) ;
							}

							if ( dreanalyze_count == 3 && dreanalyze_add1_count == 3 && dreanalyze_add2_count < 3 ) {
								dreanalyze_add2_count++ ;
								var website_url3 = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
								var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url3_id") ;
								var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted3") ;
								var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;

								check_reanalyze_speed(website_url3,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional3_id) ;
							}

							console.log("analyse_updated_speed : "+analyse_updated_speed) ;

							if ( analyse_updated_speed >= 18 ) {
								process_reanalyze_speed_update() ;
							}

						}
					});

				}
				else {
					console.error('desktop : '+desktop) ;

					analyse_updated_speed++ ; 

					if ( dreanalyze_count < 3 ) {
						dreanalyze_count++ ;
						
						check_reanalyze_speed(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) ;
					}

					if ( dreanalyze_count == 3 && dreanalyze_add1_count < 3 ) {
						dreanalyze_add1_count++ ;
						var website_url2 = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
						var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url2_id") ;
						var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted2") ;
						var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

						check_reanalyze_speed(website_url2,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional2_id) ;
					}

					if ( dreanalyze_count == 3 && dreanalyze_add1_count == 3 && dreanalyze_add2_count < 3 ) {
						dreanalyze_add2_count++ ;
						var website_url3 = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
						var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url3_id") ;
						var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted3") ;
						var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;

						check_reanalyze_speed(website_url3,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional3_id) ;
					}

					console.log("analyse_updated_speed : "+analyse_updated_speed) ;

					if ( analyse_updated_speed >= 18 ) {
						process_reanalyze_speed_update() ;
					}
				}

	        }
	        else {
	        	console.error(data) ;

				analyse_updated_speed++ ; 

				if ( dreanalyze_count < 3 ) {
					dreanalyze_count++ ;
					
					check_reanalyze_speed(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) ;
				}

				if ( dreanalyze_count == 3 && dreanalyze_add1_count < 3 ) {
					dreanalyze_add1_count++ ;
					var website_url2 = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
					var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url2_id") ;
					var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted2") ;
					var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

					check_reanalyze_speed(website_url2,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional2_id) ;
				}

				if ( dreanalyze_count == 3 && dreanalyze_add1_count == 3 && dreanalyze_add2_count < 3 ) {
					dreanalyze_add2_count++ ;
					var website_url3 = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
					var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url3_id") ;
					var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted3") ;
					var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;

					check_reanalyze_speed(website_url3,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional3_id) ;
				}

				console.log("analyse_updated_speed : "+analyse_updated_speed) ;

				if ( analyse_updated_speed >= 18 ) {
					process_reanalyze_speed_update() ;
				}

	        }

	    }).catch(error => {
	    	console.error('Fetch error:', error);

			analyse_updated_speed++ ; 

			if ( dreanalyze_count < 3 ) {
				dreanalyze_count++ ;
				
				check_reanalyze_speed(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) ;
			}

			if ( dreanalyze_count == 3 && dreanalyze_add1_count < 3 ) {
				dreanalyze_add1_count++ ;
				var website_url2 = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
				var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url2_id") ;
				var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted2") ;
				var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

				check_reanalyze_speed(website_url2,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional2_id) ;
			}

			if ( dreanalyze_count == 3 && dreanalyze_add1_count == 3 && dreanalyze_add2_count < 3 ) {
				dreanalyze_add2_count++ ;
				var website_url3 = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
				var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url3_id") ;
				var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted3") ;
				var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;

				check_reanalyze_speed(website_url3,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional3_id) ;
			}

			console.log("analyse_updated_speed : "+analyse_updated_speed) ;

			if ( analyse_updated_speed >= 18 ) {
				process_reanalyze_speed_update() ;
			}
	    });

		// ===================================================================

	}

}

function check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional_id=0) {

	if ( reanalyze_count <= 3 && reanalyze_add1_count == 0 && reanalyze_add2_count == 0 ) {
		treanalyze_count = reanalyze_count ;
	}
	else if ( reanalyze_count == 3 && reanalyze_add1_count <= 3 && reanalyze_add2_count == 0 ) {
		treanalyze_count = reanalyze_add1_count ;
	}
	else if ( reanalyze_count == 3 && reanalyze_add1_count == 3 && reanalyze_add2_count <= 3 ) {
		treanalyze_count = reanalyze_add2_count ;
	}
	else {
		treanalyze_count = 0 ;
	}

	if ( treanalyze_count > 0 ) {

		if ( started_reanlyse_additional_url != 1 ) {
			$(".loader").show().html("<div class='loader_s 890'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
			loaderest() ;
		}

		var api_key = $("meta[name='google_pagespeed_api']").attr("content") ;
		var random_string = generateRandomAlphanumeric(8);

        if ( website_url.includes("?") ) {
            var request_url = website_url+"&"+random_string ;
        }
        else {
            var request_url = website_url+"?"+random_string ;
        }


		// get speed for desktop
		var apiEndpoint = `//www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_url)}&key=${api_key}&strategy=mobile&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

	    fetch(apiEndpoint).then(response => {
	        console.log('test');
	        if (!response.ok) {
	            throw new Error('Please thoroughly review all your Domain ( '+website_url+' ) URL. Ensure that URL is correct.');
	        }
	        return response.json();
	    })
	    .then(data => {
	        // Process your data here
	        if ( data.hasOwnProperty("lighthouseResult") ) {

				var lighthouseResult = data.lighthouseResult ;

				var requestedUrl = lighthouseResult.requestedUrl ;
				var finalUrl = lighthouseResult.finalUrl ;
				var userAgent = lighthouseResult.userAgent ;
				var fetchTime = lighthouseResult.fetchTime ;
				var environment = JSON.stringify(lighthouseResult.environment) ;
				var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
				var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
				var audits = JSON.stringify(lighthouseResult.audits) ;
				var categories = JSON.stringify(lighthouseResult.categories) ;
				var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
				var i18n = JSON.stringify(lighthouseResult.i18n) ;

				var desktop = lighthouseResult.categories.performance.score ;
				desktop = Math.round(desktop * 100) ;

				if ( desktop > 0 ) {

					$.ajax({
						url: "inc/check-reanalyze-speed-mobile.php",
						method: "POST",
						data: {
							website_url: website_url,
							speedtype: speedtype,
							website_id: website_id,
							table_id: table_id,
							reanalyze_count:treanalyze_count,
							additional_id:additional_id,
							// ========================
	                        // lighthouseResult:lighthouseResult,
	                        requestedUrl:requestedUrl,
	                        finalUrl:finalUrl,
	                        userAgent:userAgent,
	                        fetchTime:fetchTime,
	                        environment:environment,
	                        runWarnings:runWarnings,
	                        configSettings:configSettings,
	                        audits:audits,
	                        categories:categories,
	                        categoryGroups:categoryGroups,
	                        i18n:i18n,
						},
						dataType: "JSON",
						timeout: 0,
						success: function (data1) {

							// script_loading++;

							console.log("Speed data : "+website_url) ; 
							console.log(data1) ;

						},
						error: function (xhr) { 
							// if error occured
							// script_loading++;
							console.error(xhr.statusText + xhr.responseText);
						},
						complete: function () {

							analyse_updated_speed++ ;

							if ( reanalyze_count < 3 ) {
								reanalyze_count++ ;
								check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) ;
							}

							if ( reanalyze_count == 3 && reanalyze_add1_count < 3 ) {
								reanalyze_add1_count++ ;

								var website_url2 = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
								var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url2_id") ;
								var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted2") ;
								var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

								check_reanalyze_speed_mobile(website_url2,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional2_id) ;
							}

							if ( reanalyze_count == 3 && reanalyze_add1_count == 3 && reanalyze_add2_count < 3 ) {
								reanalyze_add2_count++ ;
								 

								var website_url3 = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
								var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url3_id") ;
								var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted3") ;
								var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;

								check_reanalyze_speed_mobile(website_url3,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional3_id) ;
							}

							console.log("analyse_updated_speed : "+analyse_updated_speed) ;

							if ( analyse_updated_speed >= 18 ) {
								process_reanalyze_speed_update() ;
							}

						}
					});

				}
				else {
					console.error('desktop : '+desktop) ;

					analyse_updated_speed++ ;

					if ( reanalyze_count < 3 ) {
						reanalyze_count++ ;
						check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) ;
					}

					if ( reanalyze_count == 3 && reanalyze_add1_count < 3 ) {
						reanalyze_add1_count++ ;

						var website_url2 = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
						var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url2_id") ;
						var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted2") ;
						var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

						check_reanalyze_speed_mobile(website_url2,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional2_id) ;
					}

					if ( reanalyze_count == 3 && reanalyze_add1_count == 3 && reanalyze_add2_count < 3 ) {
						reanalyze_add2_count++ ;
						 

						var website_url3 = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
						var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url3_id") ;
						var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted3") ;
						var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;

						check_reanalyze_speed_mobile(website_url3,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional3_id) ;
					}

					console.log("analyse_updated_speed : "+analyse_updated_speed) ;

					if ( analyse_updated_speed >= 18 ) {
						process_reanalyze_speed_update() ;
					}
				}

	        }
	        else {
	        	console.error(data) ;

				analyse_updated_speed++ ;

				if ( reanalyze_count < 3 ) {
					reanalyze_count++ ;
					check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) ;
				}

				if ( reanalyze_count == 3 && reanalyze_add1_count < 3 ) {
					reanalyze_add1_count++ ;

					var website_url2 = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
					var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url2_id") ;
					var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted2") ;
					var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

					check_reanalyze_speed_mobile(website_url2,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional2_id) ;
				}

				if ( reanalyze_count == 3 && reanalyze_add1_count == 3 && reanalyze_add2_count < 3 ) {
					reanalyze_add2_count++ ;
					 

					var website_url3 = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
					var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url3_id") ;
					var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted3") ;
					var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;

					check_reanalyze_speed_mobile(website_url3,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional3_id) ;
				}

				console.log("analyse_updated_speed : "+analyse_updated_speed) ;

				if ( analyse_updated_speed >= 18 ) {
					process_reanalyze_speed_update() ;
				}

	        }


	    }).catch(error => {
	    	console.error('Fetch error:', error);

			analyse_updated_speed++ ;

			if ( reanalyze_count < 3 ) {
				reanalyze_count++ ;
				check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) ;
			}

			if ( reanalyze_count == 3 && reanalyze_add1_count < 3 ) {
				reanalyze_add1_count++ ;

				var website_url2 = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
				var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url2_id") ;
				var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted2") ;
				var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

				check_reanalyze_speed_mobile(website_url2,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional2_id) ;
			}

			if ( reanalyze_count == 3 && reanalyze_add1_count == 3 && reanalyze_add2_count < 3 ) {
				reanalyze_add2_count++ ;
				 

				var website_url3 = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
				var speedtype = table_id = $(".new-analyze-speed-for-3url").attr("data-table_url3_id") ;
				var boosted_speed = $(".new-analyze-speed-for-3url").attr("data-boosted3") ;
				var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;

				check_reanalyze_speed_mobile(website_url3,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed,additional3_id) ;
			}

			console.log("analyse_updated_speed : "+analyse_updated_speed) ;

			if ( analyse_updated_speed >= 18 ) {
				process_reanalyze_speed_update() ;
			}

	    });

		// ===================================================================



	}

}

var process_reanalyze_called = 0 ;


function process_reanalyze_speed_update() {
	// analyse_updated_speed = 18 ;
	console.log("process_reanalyze_speed_update : ") ;
	console.log("analyse_updated_speed : "+analyse_updated_speed) ;

	if ( analyse_updated_speed == 18 ) {

		analyse_updated_speed = 0 ;

		var website_url = $('.new-analyze-speed-for-3url').attr("data-website_url1_name");
		var website_id = $('.new-analyze-speed-for-3url').attr("data-website_url1_id");
		//after script-install
		// var website_ws_mobile = Number($("#ws-mobile-speed").val());
		// var website_ws_desktop = Number($("#ws-desktop-speed").val());
		var website_ws_mobile = Number($("#ws-mobile-speed").attr("data-value"));
		var website_ws_desktop = Number($("#ws-desktop-speed").attr("data-value"));
		//before script-install
		var website_wos_mobile = Number($("#wos-mobile-speed").val());
		var website_wos_desktop = Number($("#wos-desktop-speed").val());

		// additional 1
		var additional1_url = $('.new-analyze-speed-for-3url').attr("data-website__url2_name");
		var additional1_id = $('.new-analyze-speed-for-3url').attr("data-website_url2_id");
		//after script-install
		// var additional1_ws_mobile = Number($("#wsa1-mobile-speed").val());
		// var additional1_ws_desktop = Number($("#wsa1-desktop-speed").val());
		var additional1_ws_mobile = Number($("#wsa1-mobile-speed").attr("data-value"));
		var additional1_ws_desktop = Number($("#wsa1-desktop-speed").attr("data-value"));
		//before script-install
		var additional1_wos_mobile = Number($("#wosa1-mobile-speed").val());
		var additional1_wos_desktop = Number($("#wosa1-desktop-speed").val());

		// additional 2
		var additional2_url = $('.new-analyze-speed-for-3url').attr("data-website_url3_name");
		var additional2_id = $('.new-analyze-speed-for-3url').attr("data-website_url3_id");
		//after script-install
		// var additional2_ws_mobile = Number($("#wsa2-mobile-speed").val());
		// var additional2_ws_desktop = Number($("#wsa2-desktop-speed").val());
		var additional2_ws_mobile = Number($("#wsa2-mobile-speed").attr("data-value"));
		var additional2_ws_desktop = Number($("#wsa2-desktop-speed").attr("data-value"));
		//before script-install
		var additional2_wos_mobile = Number($("#wosa2-mobile-speed").val());
		var additional2_wos_desktop = Number($("#wosa2-desktop-speed").val());

		$.ajax({
			url: "inc/reanalyze-speed-compare-update.php",
			method: "POST",
			data: {
				website_url: website_url,
				website_id: website_id,
				website_ws_mobile: website_ws_mobile ,
				website_ws_desktop: website_ws_desktop ,
				website_wos_mobile: website_wos_mobile ,
				website_wos_desktop: website_wos_desktop ,
				additional1_url: additional1_url ,
				additional1_id: additional1_id ,
				additional1_ws_mobile: additional1_ws_mobile,
				additional1_ws_desktop: additional1_ws_desktop,
				additional1_wos_mobile: additional1_wos_mobile,
				additional1_wos_desktop: additional1_wos_desktop,
				additional2_url: additional2_url ,
				additional2_id: additional2_id ,
				additional2_ws_mobile: additional2_ws_mobile,
				additional2_ws_desktop: additional2_ws_desktop,
				additional2_wos_mobile: additional2_wos_mobile,
				additional2_wos_desktop: additional2_wos_desktop,
				speedtype:'new',
				action:"reanalyze-speed-compare-update"
			},
			dataType: "JSON",
			timeout: 0,
			beforeSend: function () {
				// $(".loader").show().html("<div class='loader_s asd'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
				// loaderest()
			},
			success: function (data) {

				var hide_flag = 0 ;

				console.log("process_reanalyze_speed:") ; 
				console.log(data);

				// $(".url1-new-speed-table.mobile,.url2-new-speed-table.mobile,.url3-new-speed-table.mobile").show();
				// $(".url1-new-speed-table.desktop,.url2-new-speed-table.desktop,.url3-new-speed-table.desktop").hide();

				if ( data.czs_flag == 0 || data.czs_flag == "0" ) {

					$(".popup-section-manual-audit").show();
					$(".url-speed-tables").hide();


					// Swal.fire({
					// 	title: 'Error!',
					// 	icon: 'error',
					// 	text: data.message,
					// 	showDenyButton: false,
					// 	showCancelButton: false,
					// 	allowOutsideClick: false,
					// 	allowEscapeKey: false,
					// 	confirmButtonText: 'Close',
					// }).then((result) => {
					// 	if (result.isConfirmed) {}
					// }) ;

				}
				else {

					$(".popup-section-manual-audit").hide();
					$(".url-speed-tables").show();

					var popup_urls = [] ;
					var popup_links = '' ;

					if ( data.request_manual_audit == 1 || data.request_manual_audit == "1" ) {
						$(".request-manual-audit").show() ;


						var list_popup_urls = '' ;
						for (const property in data) {

							var url_obj = data[property];

							if ( property == "url1" || property == "url2" || property == "url3" ) {

								if ( url_obj.wi_status == "popup" ) {

									if ( url_obj.platform_less == "both" ) {
										list_popup_urls += "<li>URL 1 - "+url_obj.url+" for Mobile / Desktop</li>" ;
									}
									else if ( url_obj.platform_less == "desktop" ) {
										list_popup_urls += "<li>URL 1 - "+url_obj.url+" for Desktop</li>" ;
									}
									else {
										list_popup_urls += "<li>URL 1 - "+url_obj.url+" for Mobile</li>" ;
									}

								}

							}
						}

						if ( list_popup_urls == '' ) {
							$(".request-manual-audit").hide() ;
						}
						$(".list-popup-urls").html(list_popup_urls) ;


						if ( data.manual_audit_score == "All" ) {
							$(".popup-section-manual-audit").show();
							$(".url-speed-tables").hide();
						}
					}
					else {
						$(".request-manual-audit").hide() ;
					}


					$(".url1-newspeed-container , .url2-newspeed-container , .url3-newspeed-container").removeClass("url-old-speed-score") ;
					$(".url1-data-tr , .url2-data-tr , .url3-data-tr").show() ;
					$(".url1-popup-tr , .url2-popup-tr , .url3-popup-tr").hide() ;
					$(".inst_speed_new-container").show() ;

					for (const property in data) {

						var url_obj = data[property];

						if ( property == "url1" || property == "url2" || property == "url3" ) {

							console.log(url_obj.wi_status);

							var speed_data = url_obj.stats;	

							// add red border according to status 
							if (property == "url1") {
								var table = $(".url1-new-speed-table");
								$("#ws-mobile-speed").val(speed_data.ps_performance_m).attr("data-value",speed_data.ps_performance_m) ;
								$("#ws-desktop-speed").val(speed_data.ps_performance).attr("data-value",speed_data.ps_performance) ;

								$("#ws-mobile-speed , #ws-desktop-speed").attr("type","hidden") ;

								$(".url1-newspeed-container").attr("data-ws_status",url_obj.wi_status).attr("data-blank_record",url_obj.platform_less) ;
								
								if (url_obj.wi_status == "nonew" || url_obj.wi_status == "popup") {
									// $(".url1-newspeed-container").addClass("url-old-speed-score") ;
									// $("#ws-mobile-speed , #ws-desktop-speed").attr("type","hidden") ;

									if ( url_obj.wi_status == "popup" ) {
										$(".url1-newspeed-container").addClass("url-old-speed-score") ;
										
										if ( url_obj.platform_less == "both" ) {

											if ( website_wos_desktop > 85 ) {
												$(".url1-popup-tr.desktop").hide() ;
												$(".url1-data-tr.desktop").show() ;
												$(".url1-newspeed-container").attr("data-blank_record","mobile") ;
											}
											else {
												$(".url1-popup-tr.desktop").show() ;
												$(".url1-data-tr.desktop").hide() ;
												$("#ws-desktop-speed").val("") ;
											}

											if ( website_wos_mobile > 70 ) {
												$(".url1-popup-tr.mobile").hide() ;
												$(".url1-data-tr.mobile").show() ;

												var temp_blank_record = $(".url1-newspeed-container").attr("data-blank_record") ;

												if ( temp_blank_record == "mobile" ) {
													$(".url1-newspeed-container").attr("data-blank_record","") ;
												}
												else if ( blank_record == "both" ) {
													$(".url1-newspeed-container").attr("data-blank_record","desktop") ;
												}
											}
											else {
												$(".url1-popup-tr.mobile").show() ;
												$(".url1-data-tr.mobile").hide() ;
												$("#ws-mobile-speed").val("") ;
											}

											/*** $(".url1-popup-tr").show() ;
											$(".url1-data-tr").hide() ;
											$("#ws-mobile-speed , #ws-desktop-speed").val("") ; ***/
										}
										else if ( url_obj.platform_less == "mobile" ) {
											if ( website_wos_mobile > 70 ) {
												$(".url1-popup-tr.mobile").hide() ;
												$(".url1-data-tr.mobile").show() ;
												$(".url1-newspeed-container").attr("data-blank_record","") ;
											}
											else {
												$(".url1-popup-tr.mobile").show() ;
												$(".url1-data-tr.mobile").hide() ;
												$("#ws-mobile-speed").val("") ;
											}
										}
										else if ( url_obj.platform_less == "desktop" ) {
											if ( website_wos_desktop > 85 ) {
												$(".url1-popup-tr.desktop").hide() ;
												$(".url1-data-tr.desktop").show() ;
												$(".url1-newspeed-container").attr("data-blank_record","") ;
											}
											else {
												$(".url1-popup-tr.desktop").show() ;
												$(".url1-data-tr.desktop").hide() ;
												$("#ws-desktop-speed").val("") ;
											}
										}
									}								
									
								}
								else {
									$(".inst_speed_new-container:eq(1)").show().attr("data-hide_url1",1) ;
								}
							}
							else if (property == "url2") {
								var table = $(".url2-new-speed-table");

								$("#wsa1-mobile-speed").val(speed_data.ps_performance_m).attr("data-value",speed_data.ps_performance_m) ;
								$("#wsa1-desktop-speed").val(speed_data.ps_performance).attr("data-value",speed_data.ps_performance) ;

								$("#wsa1-mobile-speed , #wsa1-desktop-speed").attr("type","hidden") ;

								$(".url2-newspeed-container").attr("data-ws_status",url_obj.wi_status).attr("data-blank_record",url_obj.platform_less) ;
								
								if (url_obj.wi_status == "nonew" || url_obj.wi_status == "popup") {
									// $(".url2-newspeed-container").addClass("url-old-speed-score") ;
									

									if ( url_obj.wi_status == "popup" ) {
										$(".url2-newspeed-container").addClass("url-old-speed-score") ;
										// $("#wsa1-mobile-speed , #wsa1-desktop-speed").attr("type","hidden") ;

										if ( url_obj.platform_less == "both" ) {

											if ( additional1_wos_desktop > 85 ) {
												$(".url2-popup-tr.desktop").hide() ;
												$(".url2-data-tr.desktop").show() ;
												$(".url2-newspeed-container").attr("data-blank_record","mobile") ;
											}
											else {
												$(".url2-popup-tr.desktop").show() ;
												$(".url2-data-tr.desktop").hide() ;
												$("#wsa1-desktop-speed").val("") ;
											}

											if ( additional1_wos_mobile > 70 ) {
												$(".url2-popup-tr.mobile").hide() ;
												$(".url2-data-tr.mobile").show() ;

												var temp_blank_record = $(".url2-newspeed-container").attr("data-blank_record") ;

												if ( temp_blank_record == "mobile" ) {
													$(".url2-newspeed-container").attr("data-blank_record","") ;
												}
												else if ( blank_record == "both" ) {
													$(".url2-newspeed-container").attr("data-blank_record","desktop") ;
												}

											}
											else {
												$(".url2-popup-tr.mobile").show() ;
												$(".url2-data-tr.mobile").hide() ;
												$("#wsa1-mobile-speed").val("") ;
											}

											/*** $(".url2-popup-tr").show() ;
											$(".url2-data-tr").hide() ;
											$("#wsa1-mobile-speed , #wsa1-desktop-speed").val("") ; ***/
										}
										else if ( url_obj.platform_less == "mobile" ) {
											if ( additional1_wos_mobile > 70 ) {
												$(".url2-popup-tr.mobile").hide() ;
												$(".url2-data-tr.mobile").show() ;
												$(".url2-newspeed-container").attr("data-blank_record","") ;
											}
											else {
												$(".url2-popup-tr.mobile").show() ;
												$(".url2-data-tr.mobile").hide() ;
												$("#wsa1-mobile-speed").val("") ;
											}
										}
										else if ( url_obj.platform_less == "desktop" ) {
											if ( additional1_wos_desktop > 85 ) {
												$(".url2-popup-tr.desktop").hide() ;
												$(".url2-data-tr.desktop").show() ;
												$(".url2-newspeed-container").attr("data-blank_record","") ;
											}
											else {
												$(".url2-popup-tr.desktop").show() ;
												$(".url2-data-tr.desktop").hide() ;
												$("#wsa1-desktop-speed").val("") ;
											}
										}										
									}

	
								}
								else {
									$(".inst_speed_new-container:eq(2)").show().attr("data-hide_url2",1) ;
								}
							}
							else if (property == "url3") {
								var table = $(".url3-new-speed-table");

								$("#wsa2-mobile-speed").val(speed_data.ps_performance_m).attr("data-value",speed_data.ps_performance_m) ;
								$("#wsa2-desktop-speed").val(speed_data.ps_performance).attr("data-value",speed_data.ps_performance) ;

								$("#wsa2-mobile-speed , #wsa2-desktop-speed").attr("type","hidden") ;

								$(".url3-newspeed-container").attr("data-ws_status",url_obj.wi_status).attr("data-blank_record",url_obj.platform_less) ;

								if (url_obj.wi_status == "nonew" || url_obj.wi_status == "popup") {
									// $(".url3-newspeed-container").addClass("url-old-speed-score") ;
									// $("#wsa2-mobile-speed , #wsa2-desktop-speed").attr("type","hidden") ;

									if ( url_obj.wi_status == "popup" ) {

										$(".url3-newspeed-container").addClass("url-old-speed-score") ;

										if ( url_obj.platform_less == "both" ) {

											if ( additional2_wos_desktop > 85 ) {
												$(".url3-popup-tr.desktop").hide() ;
												$(".url3-data-tr.desktop").show() ;
												$(".url3-newspeed-container").attr("data-blank_record","mobile") ;
											}
											else {
												$(".url3-popup-tr.desktop").show() ;
												$(".url3-data-tr.desktop").hide() ;
												$("#wsa2-desktop-speed").val("") ;
											}

											if ( additional2_wos_mobile > 70 ) {
												$(".url3-popup-tr.mobile").hide() ;
												$(".url3-data-tr.mobile").show() ;

												var temp_blank_record = $(".url3-newspeed-container").attr("data-blank_record") ;

												if ( temp_blank_record == "mobile" ) {
													$(".url3-newspeed-container").attr("data-blank_record","") ;
												}
												else if ( blank_record == "both" ) {
													$(".url3-newspeed-container").attr("data-blank_record","desktop") ;
												}
											}
											else {
												$(".url3-popup-tr.mobile").show() ;
												$(".url3-data-tr.mobile").hide() ;
												$("#wsa2-mobile-speed").val("") ;
											}

											/*** $(".url3-popup-tr").show() ;
											$(".url3-data-tr").hide() ;
											$("#wsa2-mobile-speed , #wsa2-desktop-speed").val("") ; ***/
										}
										else if ( url_obj.platform_less == "mobile" ) {
											if ( additional2_wos_mobile > 70 ) {
												$(".url3-popup-tr.mobile").hide() ;
												$(".url3-data-tr.mobile").show() ;
												$(".url3-newspeed-container").attr("data-blank_record","") ;
											}
											else {
												$(".url3-popup-tr.mobile").show() ;
												$(".url3-data-tr.mobile").hide() ;
												$("#wsa2-mobile-speed").val("") ;
											}
										}
										else if ( url_obj.platform_less == "desktop" ) {
											if ( additional2_wos_desktop > 85 ) {
												$(".url3-popup-tr.desktop").hide() ;
												$(".url3-data-tr.desktop").show() ;
												$(".url3-newspeed-container").attr("data-blank_record","") ;
											}
											else {
												$(".url3-popup-tr.desktop").show() ;
												$(".url3-data-tr.desktop").hide() ;
												$("#wsa2-desktop-speed").val("") ;
											}
										}	
									}

								}
								else {
									$(".inst_speed_new-container:eq(3)").show().attr("data-hide_url3",1) ;
								}
							}



							
							// console.log(speed_data) ;

							// console.log(speed_data.ps_performance_m) ;

							// if ( Object.keys(speed_data).length > 0 ) {}

							// mobile
							// console.log(table) ;
							table.find(".performance_m").text(speed_data.ps_mobile);
							table.find(".accessibility_m").text(speed_data.ps_accessibility_m);
							table.find(".best-practices_m").text(speed_data.ps_best_practices_m);
							table.find(".seo_m").text(speed_data.ps_seo_m);
							table.find(".fcp_m").text(speed_data.ps_fcp_m.toFixed(2));
							table.find(".lcp_m").text(speed_data.ps_lcp_m.toFixed(2));
							table.find(".cls_m").text(speed_data.ps_cls_m.toFixed(2));
							table.find(".tbt_m").text(speed_data.ps_tbt_m.toFixed(2));
							table.find(".si_m").text(speed_data.ps_si_m);

							// desktop
							table.find(".performance").text(speed_data.ps_desktop);
							table.find(".accessibility").text(speed_data.ps_accessibility);
							table.find(".best-practices").text(speed_data.ps_best_practices);
							table.find(".seo").text(speed_data.ps_seo);
							table.find(".fcp").text(speed_data.ps_fcp.toFixed(2));
							table.find(".lcp").text(speed_data.ps_lcp.toFixed(2));
							table.find(".cls").text(speed_data.ps_cls.toFixed(2));
							table.find(".tbt").text(speed_data.ps_tbt.toFixed(2));
							table.find(".si").text(speed_data.ps_si);

							/***
							if ( speed_data.blank_record == "desktop" ) {
								if (property == "url1") {
									$(".url1-new-speed-table.desktop").hide();
									$("#ws-mobile-speed").val(0) ;
									$("#ws-desktop-speed").val(0) ;
								}
								else if (property == "url2") {
									$(".url2-new-speed-table.desktop").hide();
									$("#wsa1-mobile-speed").val(0) ;
									$("#wsa1-desktop-speed").val(0) ;
								}
								else {
									$(".url3-new-speed-table.desktop").hide();
									$("#wsa2-mobile-speed").val(0) ;
									$("#wsa2-desktop-speed").val(0) ;
								}
							}
							else if ( speed_data.blank_record == "mobile" ) {

								if (property == "url1") {
									$(".url1-new-speed-table.mobile").hide();
									$("#ws-mobile-speed").val(0) ;
									$("#ws-desktop-speed").val(0) ;
								}
								else if (property == "url2") {
									$(".url2-new-speed-table.mobile").hide();
									$("#wsa1-mobile-speed").val(0) ;
									$("#wsa1-desktop-speed").val(0) ;
								}
								else {
									$(".url3-new-speed-table.mobile").hide();
									$("#wsa2-mobile-speed").val(0) ;
									$("#wsa2-desktop-speed").val(0) ;
								}
							}
							***/

							

							if (url_obj.wi_status == "popup") {

								popup_urls.push({url:property,link:url_obj.url});

								if ( popup_links != '' ) {
									popup_links += ',' ;
								}

								popup_links += property ;
								if ( url_obj.platform_less == "both" ) {
									popup_links += "(mobile, desktop)" ;
								}
								else {
									popup_links += "("+url_obj.platform_less+")" ;
								}	

								/*** if (property == "url1") {

									var hide_url1 = $(".inst_speed_new-container:eq(1)").attr("data-hide_url1") ;
									if ( hide_url1 == 1 || hide_url1 == "1" ) {
										$(".inst_speed_new-container:eq(1)").show();
									}
									else {
										$(".inst_speed_new-container:eq(1)").hide();
										hide_flag++ ;
									}
								}
								else if (property == "url2") {

									var hide_url2 = $(".inst_speed_new-container:eq(2)").attr("data-hide_url2") ;
									if ( hide_url2 == 1 || hide_url2 == "1" ) {
										$(".inst_speed_new-container:eq(2)").show();
									}
									else {
										$(".inst_speed_new-container:eq(2)").hide();
										hide_flag++ ;
									}
								}
								else {

									var hide_url3 = $(".inst_speed_new-container:eq(3)").attr("data-hide_url3") ;
									if ( hide_url3 == 1 || hide_url3 == "1" ) {
										$(".inst_speed_new-container:eq(3)").show();
									}
									else {
										$(".inst_speed_new-container:eq(3)").hide();
										hide_flag++ ;
									}
								} ***/

								

								/***
								if ( url_obj.platform_less == "both" ) {

									if (property == "url1") {
										$(".url1-new-speed-table.desktop,.url1-new-speed-table.mobile").hide();
										$("#ws-mobile-speed").val(0) ;
										$("#ws-desktop-speed").val(0) ;
									}
									else if (property == "url2") {
										$(".url2-new-speed-table.desktop,.url2-new-speed-table.mobile").hide();
										$("#wsa1-mobile-speed").val(0) ;
										$("#wsa1-desktop-speed").val(0) ;
									}
									else {
										$(".url3-new-speed-table.desktop,.url3-new-speed-table.mobile").hide();
										$("#wsa2-mobile-speed").val(0) ;
										$("#wsa2-desktop-speed").val(0) ;
									}

								}
								else if ( url_obj.platform_less == "mobile" ) {

									var speed_data = url_obj.stats;	

									if ( Object.keys(speed_data).length > 0 ) {

										if (property == "url1") {

											var table = $(".url1-new-speed-table");

											if ( speed_data.ps_mobile ) {
												$("#ws-mobile-speed").val(speed_data.ps_mobile.split("/")[0]) ;
											}
											else {
												$("#ws-mobile-speed").val(0) ;
											}

											if ( speed_data.ps_desktop ) {
												$("#ws-desktop-speed").val(speed_data.ps_desktop.split("/")[0]) ;
											}
											else {
												$("#ws-desktop-speed").val(0) ;
											}

										}
										else if (property == "url2") {
											var table = $(".url2-new-speed-table");

											if ( speed_data.ps_mobile ) {
												$("#wsa1-mobile-speed").val(speed_data.ps_mobile.split("/")[0]) ;
											}
											else {
												$("#wsa1-mobile-speed").val(0) ;
											}

											if ( speed_data.ps_desktop ) {
												$("#wsa1-desktop-speed").val(speed_data.ps_desktop.split("/")[0]) ;
											}
											else {
												$("#wsa1-desktop-speed").val(0) ;
											}

											// $("#wsa1-mobile-speed").val(speed_data.ps_mobile.split("/")[0]) ;
											// $("#wsa1-desktop-speed").val(speed_data.ps_desktop.split("/")[0]) ;
										}
										else {
											var table = $(".url3-new-speed-table");
											if ( speed_data.ps_mobile ) {
												$("#wsa2-mobile-speed").val(speed_data.ps_mobile.split("/")[0]) ;
											}
											else {
												$("#wsa2-mobile-speed").val(0) ;
											}

											if ( speed_data.ps_desktop ) {
												$("#wsa2-desktop-speed").val(speed_data.ps_desktop.split("/")[0]) ;
											}
											else {
												$("#wsa2-desktop-speed").val(0) ;
											}

										}

										// mobile
										// console.log(table) ;
										table.find(".performance_m").text(speed_data.ps_mobile);
										table.find(".accessibility_m").text(speed_data.ps_accessibility_m);
										table.find(".best-practices_m").text(speed_data.ps_best_practices_m);
										table.find(".seo_m").text(speed_data.ps_seo_m);
										table.find(".fcp_m").text(speed_data.ps_fcp_m);
										table.find(".lcp_m").text(speed_data.ps_lcp_m);
										table.find(".cls_m").text(speed_data.ps_cls_m);
										table.find(".tbt_m").text(speed_data.ps_tbt_m);

										// desktop
										table.find(".performance").text(speed_data.ps_desktop);
										table.find(".accessibility").text(speed_data.ps_accessibility);
										table.find(".best-practices").text(speed_data.ps_best_practices);
										table.find(".seo").text(speed_data.ps_seo);
										table.find(".fcp").text(speed_data.ps_fcp);
										table.find(".lcp").text(speed_data.ps_lcp);
										table.find(".cls").text(speed_data.ps_cls);
										table.find(".tbt").text(speed_data.ps_tbt);

									}

									if (property == "url1") {
										$(".url1-new-speed-table.mobile").hide();
										$("#ws-mobile-speed").val(0) ;
										$("#ws-desktop-speed").val(0) ;
									}
									else if (property == "url2") {
										$(".url2-new-speed-table.mobile").hide();
										$("#wsa1-mobile-speed").val(0) ;
										$("#wsa1-desktop-speed").val(0) ;
									}
									else {
										$(".url3-new-speed-table.mobile").hide();
										$("#wsa2-mobile-speed").val(0) ;
										$("#wsa2-desktop-speed").val(0) ;
									}

								}
								else if ( url_obj.platform_less == "desktop" ) {

									var speed_data = url_obj.stats;	

									if ( Object.keys(speed_data).length > 0 ) {

										if (property == "url1") {

											var table = $(".url1-new-speed-table");

											if ( speed_data.ps_mobile ) {
												$("#ws-mobile-speed").val(speed_data.ps_mobile.split("/")[0]) ;
											}
											else {
												$("#ws-mobile-speed").val(0) ;
											}

											if ( speed_data.ps_desktop ) {
												$("#ws-desktop-speed").val(speed_data.ps_desktop.split("/")[0]) ;
											}
											else {
												$("#ws-desktop-speed").val(0) ;
											}

										}
										else if (property == "url2") {
											var table = $(".url2-new-speed-table");

											if ( speed_data.ps_mobile ) {
												$("#wsa1-mobile-speed").val(speed_data.ps_mobile.split("/")[0]) ;
											}
											else {
												$("#wsa1-mobile-speed").val(0) ;
											}

											if ( speed_data.ps_desktop ) {
												$("#wsa1-desktop-speed").val(speed_data.ps_desktop.split("/")[0]) ;
											}
											else {
												$("#wsa1-desktop-speed").val(0) ;
											}

											// $("#wsa1-mobile-speed").val(speed_data.ps_mobile.split("/")[0]) ;
											// $("#wsa1-desktop-speed").val(speed_data.ps_desktop.split("/")[0]) ;
										}
										else {
											var table = $(".url3-new-speed-table");
											if ( speed_data.ps_mobile ) {
												$("#wsa2-mobile-speed").val(speed_data.ps_mobile.split("/")[0]) ;
											}
											else {
												$("#wsa2-mobile-speed").val(0) ;
											}

											if ( speed_data.ps_desktop ) {
												$("#wsa2-desktop-speed").val(speed_data.ps_desktop.split("/")[0]) ;
											}
											else {
												$("#wsa2-desktop-speed").val(0) ;
											}

										}

										// mobile
										// console.log(table) ;
										table.find(".performance_m").text(speed_data.ps_mobile);
										table.find(".accessibility_m").text(speed_data.ps_accessibility_m);
										table.find(".best-practices_m").text(speed_data.ps_best_practices_m);
										table.find(".seo_m").text(speed_data.ps_seo_m);
										table.find(".fcp_m").text(speed_data.ps_fcp_m);
										table.find(".lcp_m").text(speed_data.ps_lcp_m);
										table.find(".cls_m").text(speed_data.ps_cls_m);
										table.find(".tbt_m").text(speed_data.ps_tbt_m);

										// desktop
										table.find(".performance").text(speed_data.ps_desktop);
										table.find(".accessibility").text(speed_data.ps_accessibility);
										table.find(".best-practices").text(speed_data.ps_best_practices);
										table.find(".seo").text(speed_data.ps_seo);
										table.find(".fcp").text(speed_data.ps_fcp);
										table.find(".lcp").text(speed_data.ps_lcp);
										table.find(".cls").text(speed_data.ps_cls);
										table.find(".tbt").text(speed_data.ps_tbt);

									}

									if (property == "url1") {
										$(".url1-new-speed-table.desktop").hide();
										$("#ws-mobile-speed").val(0) ;
										$("#ws-desktop-speed").val(0) ;
									}
									else if (property == "url2") {
										$(".url2-new-speed-table.desktop").hide();
										$("#wsa1-mobile-speed").val(0) ;
										$("#wsa1-desktop-speed").val(0) ;
									}
									else {
										$(".url3-new-speed-table.desktop").hide();
										$("#wsa2-mobile-speed").val(0) ;
										$("#wsa2-desktop-speed").val(0) ;
									}

								}
								***/

							}

						}
					}

					// 8. ager 3 url ki speed badi (5+) h toh table show hogi. nhi toh popup ka content show hoga.
					/*** if ( popup_urls.length > 0 && data.popup_count <= 3 ) {

						Swal.fire({
						    icon: 'info',
						    html:`<h5 style="font-size:18px;">It looks like there was one of the following issues -</h5>
						    	<ol class="pp_list">
						    	<li>Code was not installed correctly ${popup_links}.</li>
						    	<li>Your website doesn’t support latest performance optimisation standards.</li>
						    	<li>Issue with google speed insights API .</li>
						    	</ol>
						    	<h5 style="margin-top:20px; font-size:18px;">we suggest you do the following -</h5>
						    	<ol class="pp_list">
						    	<li>Remove the code from your website.</li>
						    	<li>Request installation by our team. Our team will contact you to get access to your website and will assist you installing website speedy correctly.</li>
						    	<li>Issue with google speed insights API .</li>
						    	</ol>
						    `,
						    showDenyButton: false,
						    showCancelButton: false,
						    showCloseButton: true,
						    allowOutsideClick: false,
						    allowEscapeKey: false,
						    showConfirmButton: true,
						    confirmButtonText: 'Request installation By team',
						}).then((result) => {

						    if (result.isConfirmed) {
						        $(".nav.nav-tabs li").toggleClass("active-tab") ;
						        $(".tab-content div.tab-pane").toggleClass("active-tab") ;

						        $('html, body').animate({
									scrollTop: $("html, body").offset().top + 50
								},500);
						    }
						}) ;

					} ***/

					// console.log(popup_urls) ;

					// if ( hide_flag >= 3 ) {
					// 	$(".inst_speed_new-container").hide();

					// 	if ( data.request_manual_audit == 1 || data.request_manual_audit == "1" ) {
					// 		$(".inst_speed_new-container:eq(4)").show();
					// 	}
					// }

				}

			},
			error: function (xhr) { 
				// if error occured
				// script_loading++;
				console.error(xhr.statusText + xhr.responseText);
			},
			complete: function (data) {
				var jsonString  = JSON.stringify(data)
				var obj =  JSON.parse(jsonString);
				// console.log(obj) 
				console.log(obj.responseJSON.speedtype);

				// console.log('speedtype '+data.speedtype);
				process_reanalyze_called = 0 ;
				$(".loader").hide().html("");

				started_reanlyse_additional_url = 0 ;

				setTimeout(function(){ $(".inst_speed_new-container:eq(0),.inst_speed_new-container:eq(4)").show(); },1000) ;
			}

		});

	}

}

function process_reanalyze_speed(website_url,speedtype,website_id,table_id, boosted_speed,old_mobile_speed,current_mobile_speed) {

	// dreanalyze_count = 3 ; reanalyze_count = 3 ;
	if ( dreanalyze_count == 3 && reanalyze_count == 3 && process_reanalyze_called == 0 ) {

		process_reanalyze_called = 1 ;
		var boostId = <?=$project_id?>;
		// var current_mobile_speed = Number($("#ws-mobile-speed").val()) ; //after script-install
		// var wos_mobile_speed = Number($("#wos-mobile-speed").val()) ; //before script-install

		$.ajax({
			url: "inc/reanalyze-speed-compare.php",
			method: "POST",
			data: {
				website_url: website_url,
				speedtype: speedtype,
				website_id: website_id,
				project_id : boostId,
				table_id: table_id,
				wos_mobile_speed:old_mobile_speed,
				current_mobile_speed:current_mobile_speed,
				action:"reanalyze-speed-compare"
			},
			dataType: "JSON",
			timeout: 0,
			beforeSend: function () {
				$(".loader").show().html("<div class='loader_s zxc'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
				loaderest()
			},
			success: function (data) {
				// script_loading++;

				console.log("process_reanalyze_speed:") ; 
				console.log(data);
				if(data.speedtype=='new'){
					messages = 'for URL 1';
				}else if(data.speedtype=='newUrl2'){
					messages = 'for URL 2';
				}else if(data.speedtype=='newUrl3'){
					messages = 'for URL 3';
				}

				if (data.status == "done") {

					var speed_data = data.message ;

					// mobile
					$("."+boosted_speed).find(".performance_m").text(speed_data.ps_mobile);
					$("."+boosted_speed).find(".accessibility_m").text(speed_data.ps_accessibility_m);
					$("."+boosted_speed).find(".best-practices_m").text(speed_data.ps_best_practices_m);
					$("."+boosted_speed).find(".seo_m").text(speed_data.ps_seo_m);
					// $(".inst_speed_new").find(".pwa_m").text(speed_data.pwa);
					$("."+boosted_speed).find(".fcp_m").text(speed_data.ps_fcp_m.toFixed(2));
					$("."+boosted_speed).find(".lcp_m").text(speed_data.ps_lcp_m.toFixed(2));
					// $(".inst_speed_new").find(".mpf_m").text(speed_data.MPF);
					$("."+boosted_speed).find(".cls_m").text(speed_data.ps_cls_m.toFixed(2));
					$("."+boosted_speed).find(".tbt_m").text(speed_data.ps_tbt_m.toFixed(2));

					// desktop
					$("."+boosted_speed).find(".performance").text(speed_data.ps_desktop);
					$("."+boosted_speed).find(".accessibility").text(speed_data.ps_accessibility);
					$("."+boosted_speed).find(".best-practices").text(speed_data.ps_best_practices);
					$("."+boosted_speed).find(".seo").text(speed_data.ps_seo);
					$("."+boosted_speed).find(".fcp").text(speed_data.ps_fcp.toFixed(2));
					$("."+boosted_speed).find(".lcp").text(speed_data.ps_lcp.toFixed(2));
					$("."+boosted_speed).find(".cls").text(speed_data.ps_cls.toFixed(2));
					$("."+boosted_speed).find(".tbt").text(speed_data.ps_tbt.toFixed(2));

					if(boosted_speed=='url2_inst_speed_new'){
						$(".new-analyze-speed-for-url2").attr('data-currentmscore',speed_data.ps_performance_m);
					}else if(boosted_speed=='url3_inst_speed_new'){
						$(".new-analyze-speed-for-url3").attr('data-currentmscore',speed_data.ps_performance_m);
					}else{
						$("#ws-mobile-speed").val(speed_data.ps_performance_m)
					}


					$(".inst_speed_new-container").show() ;

				}
				else if (data.status == "popup") {

					Swal.fire({
					    icon: 'info',
					    html:`<h5>It looks like there was one of the following issues -</h5>
					    	<ol>
					    	<li>Code was not installed correctly ${messages}.</li>
					    	<li>Your website doesn’t support latest performance optimisation standards.</li>
					    	<li>Issue with google speed insights API .</li>
					    	</ol>
					    	<h5>we suggest you do the following -</h5>
					    	<ol>
					    	<li>Remove the code from your website.</li>
					    	<li>Request installation by our team. Our team will contact you to get access to your website and will assist you installing website speedy correctly.</li>
					    	<li>Issue with google speed insights API .</li>
					    	</ol>
					    `,
					    showDenyButton: false,
					    showCancelButton: false,
					    showCloseButton: true,
					    allowOutsideClick: false,
					    allowEscapeKey: false,
					    showConfirmButton: true,
					    confirmButtonText: 'Request installation By team',
					}).then((result) => {

					    if (result.isConfirmed) {
					        $(".nav.nav-tabs li").toggleClass("active-tab") ;
					        $(".tab-content div.tab-pane").toggleClass("active-tab") ;

					        $('html, body').animate({
								scrollTop: $("html, body").offset().top + 50
							},500);
					    }
					}) ;

				}
				// else if (data.status == "error") {

				// 	Swal.fire({
				// 	    title: 'Error!',
				// 	    icon: 'error',
				// 	    html:'<p>'+data.message+'</p>',
				// 	    denyButtonText: `Close`,
				// 	}) ;
				// }

			},
			error: function (xhr) { 
				// if error occured
				// script_loading++;
				console.error(xhr.statusText + xhr.responseText);
			},
			complete: function (data) {
				var jsonString  = JSON.stringify(data)
				var obj =  JSON.parse(jsonString);
				// console.log(obj) 
				console.log(obj.responseJSON.speedtype);

				// console.log('speedtype '+data.speedtype);
				process_reanalyze_called = 0 ;
				setTimeout(function(){
					// stop loader
					$(".loader").hide().html("");
				},1000) ;
				if(obj.responseJSON.speedtype=='new'){
					setTimeout(function(){
					// stop loader
					$('.new-analyze-speed-for-url2').click();
				},10000) ;
				}else if(obj.responseJSON.speedtype=='newUrl2'){
					setTimeout(function(){
					// stop loader
					$('.new-analyze-speed-for-url3').click();
				},10000) 
				}
			}
		});

	}
	// else {
	// 	process_reanalyze_speed(website_url,speedtype,website_id,table_id) ;
	// }

}

// $(".reanalyze-btn-update").click(function () {
// 	reloadSpeed = $(this);
// 	script_loading = 0;
// 	var tab = $(this).attr("tab");
// 	// console.log(tab);

// 	var website_url = $(this).attr("data-website_url");
// 	speedtype = $(this).attr("data-table_id");
// 	var website_id = $(this).attr("data-website_id");
// 	var table_id = $(this).attr("data-table_id");

// 	console.log("================================================") ;

// 	console.log("website_url : "+website_url) ;
// 	console.log("speedtype : "+speedtype) ;
// 	console.log("website_id : "+website_id) ;
// 	console.log("table_id : "+table_id) ;
// 	console.log("tab : "+tab) ;

// 	// start loader
// 	$(".loader").show().html("<div class='loader_s'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins<p></div>");



// 	setTimeout(function(){

// 		dreanalyze_count = 1 ;
// 		reanalyze_count = 1 ;

// 		check_reanalyze_speed(website_url,speedtype,website_id,table_id) ;
// 		check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id) ;

// 	},500);

// 	if (tab == "#step4") {
// 		$('html, body').animate({
// 			scrollTop: $("#step4").offset().top + 150
// 		},500);
// 	}
	


// 	console.log("================================================") ;

// });

/*** END Analyse Updated Speed ***/ 

function showUpdatedSpeed() {

	var website_id = <?=$site_id?> ;
	var additional3_id = $(".new-analyze-speed-for-3url").attr("data-website_url3_id") ;
	var additional2_id = $(".new-analyze-speed-for-3url").attr("data-website_url2_id") ;

	var url = 0 ;
	var website_url = website_ws_mobile = website_ws_desktop = website_wos_mobile = website_wos_desktop = '' ;

	if ( reanalyze_count == 3 && dreanalyze_count == 3 && !reanalyze_interval_request.includes(1) ) {
		url = 1 ;
		reanalyze_interval_request.push(url) ;

		website_url = $(".new-analyze-speed-for-3url").attr("data-website_url1_name") ;
		website_ws_mobile = $("#ws-mobile-speed").val() ;
		website_ws_desktop = $("#ws-desktop-speed").val() ;
		website_wos_mobile = $("#wos-mobile-speed").val() ;
		website_wos_desktop = $("#wos-desktop-speed").val() ;

	}
	else if ( reanalyze_add1_count == 3 && dreanalyze_add1_count == 3 && !reanalyze_interval_request.includes(2) ) {
		url = 2 ;
		reanalyze_interval_request.push(url) ;

		website_url = $(".new-analyze-speed-for-3url").attr("data-website__url2_name") ;
		website_ws_mobile = $("#wsa1-mobile-speed").val() ;
		website_ws_desktop = $("#wsa1-desktop-speed").val() ;
		website_wos_mobile = $("#wosa1-mobile-speed").val() ;
		website_wos_desktop = $("#wosa1-desktop-speed").val() ;
	}
	else if ( reanalyze_add2_count == 3 && dreanalyze_add2_count == 3 && !reanalyze_interval_request.includes(3) ) {
		url = 3 ;
		reanalyze_interval_request.push(url) ;

		website_url = $(".new-analyze-speed-for-3url").attr("data-website_url3_name") ;
		website_ws_mobile = $("#wsa2-mobile-speed").val() ;
		website_ws_desktop = $("#wsa2-desktop-speed").val() ;
		website_wos_mobile = $("#wosa2-mobile-speed").val() ;
		website_wos_desktop = $("#wosa2-desktop-speed").val() ;
	}

	// console.log("url : "+url);

	if ( url > 0 ) {
		$.ajax({
			url:"inc/compare-url-speed.php",
			method:"POST",
			data:{ 
				website_id:website_id,
				website_url:website_url,
				additional2_id:additional2_id,
				additional3_id:additional3_id,
				url:url,
				website_ws_mobile:website_ws_mobile,
				website_ws_desktop:website_ws_desktop,
				website_wos_mobile:website_wos_mobile,
				website_wos_desktop:website_wos_desktop,
				action:"compare-url-speed",
			},
			dataType:"JSON",
			timeout: 0,
			success:function(response){

				if ( response.wi_status == "error" ) {
					console.error("Can't compare speed score!") ;
				}
				else if ( response.wi_status == "done" ) {

					var speed_data = response.stats ;

					if (url == 1) {
						var table = $(".url1-new-speed-table");
						$("#ws-mobile-speed").val(speed_data.ps_performance_m) ;
						$("#ws-desktop-speed").val(speed_data.ps_performance) ;
					}
					else if (url == 2) {
						var table = $(".url2-new-speed-table");
						$("#wsa1-mobile-speed").val(speed_data.ps_performance_m) ;
						$("#wsa1-desktop-speed").val(speed_data.ps_performance) ;
					}
					else if (url == 3) {
						var table = $(".url3-new-speed-table");
						$("#wsa2-mobile-speed").val(speed_data.ps_performance_m) ;
						$("#wsa2-desktop-speed").val(speed_data.ps_performance) ;
					}

					// mobile
					// console.log(table) ;
					table.find(".performance_m").text(speed_data.ps_mobile);
					table.find(".accessibility_m").text(speed_data.ps_accessibility_m);
					table.find(".best-practices_m").text(speed_data.ps_best_practices_m);
					table.find(".seo_m").text(speed_data.ps_seo_m);
					table.find(".fcp_m").text(speed_data.ps_fcp_m.toFixed(2));
					table.find(".lcp_m").text(speed_data.ps_lcp_m.toFixed(2));
					table.find(".cls_m").text(speed_data.ps_cls_m.toFixed(2));
					table.find(".tbt_m").text(speed_data.ps_tbt_m.toFixed(2));
					table.find(".si_m").text(speed_data.ps_si_m);

					// desktop
					table.find(".performance").text(speed_data.ps_desktop);
					table.find(".accessibility").text(speed_data.ps_accessibility);
					table.find(".best-practices").text(speed_data.ps_best_practices);
					table.find(".seo").text(speed_data.ps_seo);
					table.find(".fcp").text(speed_data.ps_fcp.toFixed(2));
					table.find(".lcp").text(speed_data.ps_lcp.toFixed(2));
					table.find(".cls").text(speed_data.ps_cls.toFixed(2));
					table.find(".tbt").text(speed_data.ps_tbt.toFixed(2));
					table.find(".si").text(speed_data.ps_si);

					$(".inst_speed_new-container:eq("+url+")").show() ;

				}
				else {

					if (url == 1) {
						var hide = $(".inst_speed_new-container:eq("+url+")").attr("data-hide_url1");
						if ( hide == 1 || hide == "1" ) {
							$(".inst_speed_new-container:eq("+url+")").show() ;
						}
					}
					else if (url == 2) {
						var hide = $(".inst_speed_new-container:eq("+url+")").attr("data-hide_url2");
						if ( hide == 1 || hide == "1" ) {
							$(".inst_speed_new-container:eq("+url+")").show() ;
						}
					}
					else if (url == 3) {
						var hide = $(".inst_speed_new-container:eq("+url+")").attr("data-hide_url3");
						if ( hide == 1 || hide == "1" ) {
							$(".inst_speed_new-container:eq("+url+")").show() ;
						}
					}

				}

			},
			error:function(){	
				$(".loader").css({"opacity":1}) ;	
				$(".inst_speed_new-container:eq("+url+")").hide() ;
			}
		});		
	}

	if ( reanalyze_interval_request.length >= 3 ) {
		clearInterval(reanalyze_interval) ;
		reanalyze_interval = '' ;
		reanalyze_interval_request = [] ;
		$(".loader").css({"opacity":1}) ;
	}
}


//123 for-url1
$('.new-analyze-speed-for-3url').on('click', function(){

	reloadSpeed = $(this);
	script_loading = 0;
	var tab = $(this).attr("tab");
	// console.log(tab);

	var website_url = $(this).attr("data-website_url1");
	speedtype = $(this).attr("data-table_url_id");
	var website_id = $(this).attr("data-website_url1_id");
	var table_id = $(this).attr("data-table_url_id");
	var boosted_speed = $(this).attr("data-boosted");
	
	var old_mobile_speed = Number($("#wos-mobile-speed").val()) ; //before script-install
	var current_mobile_speed = Number($("#ws-mobile-speed").val()) ; //after script-install


	console.log("================================================") ;
	console.log("website_url : "+website_url) ;
	console.log("speedtype : "+speedtype) ;
	console.log("website_id : "+website_id) ;
	console.log("tab : "+tab) ;
	console.log("boosted_speed : "+boosted_speed);
	console.log("old_mobile_speed : "+old_mobile_speed);
	console.log("current_mobile_speed : "+current_mobile_speed);

	if ( started_reanlyse_additional_url == 1 ) {
		// start loader
		$(".loader").show().html("<div class='loader_s dfg'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
		loaderest() ;
	}
	else {

		// start loader
		$(".loader").show().html("<div class='loader_s dfg'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
		loaderest() ;

		if ( getting_no_speedy1 == 0 && getting_no_speedy2 == 0 ) {
			setTimeout(function(){

				/*** For badi hui speed dikhni cahiye and popup ko light karna hai. ***/ 
				// $(".inst_speed_new-container").hide() ;
				// reanalyze_interval = setInterval(function(){ showUpdatedSpeed(); },1000);
				/*** END badi hui speed dikhni cahiye and popup ko light karna hai. ***/ 

				reanalyze_count = dreanalyze_count = 1 ;
				analyse_updated_speed = reanalyze_add1_count = dreanalyze_add1_count = reanalyze_add2_count = dreanalyze_add2_count = 0 ;

				check_reanalyze_speed(website_url,speedtype,website_id,table_id,boosted_speed,old_mobile_speed,current_mobile_speed) ;
				check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id,boosted_speed,old_mobile_speed,current_mobile_speed) ;

			},500);
		}
		else {

			new_interval_analyse = setInterval(function(argument) {

				if ( getting_no_speedy1 == 0 && getting_no_speedy2 == 0 ) {

					clearInterval(new_interval_analyse) ;

					/*** For badi hui speed dikhni cahiye and popup ko light karna hai. ***/ 
					// $(".inst_speed_new-container").hide() ;
					// reanalyze_interval = setInterval(function(){ showUpdatedSpeed(); },1000);
					/*** END badi hui speed dikhni cahiye and popup ko light karna hai. ***/ 

					reanalyze_count = dreanalyze_count = 1 ;
					analyse_updated_speed = reanalyze_add1_count = dreanalyze_add1_count = reanalyze_add2_count = dreanalyze_add2_count = 0 ;

					check_reanalyze_speed(website_url,speedtype,website_id,table_id,boosted_speed,old_mobile_speed,current_mobile_speed) ;
					check_reanalyze_speed_mobile(website_url,speedtype,website_id,table_id,boosted_speed,old_mobile_speed,current_mobile_speed) ;
				}

			},1000);

		}

	}

	if (tab == "#step3") {
		$('html, body').animate({
			scrollTop: $("#step3").offset().top + 150
		},500);
	}
	
	console.log("================================================") ;

})




/////nospeedy-data-add/////////
function addNospeedyData(website_id, additional_id, compare_url, table_id, data_oldy ){

	var script_loading = 0;

	if ( url == "" ) {
		alert(" URL not found. ") ;
		setTimeout(function(){$(".loader").hide().html('') ;},1000);
	} 
	else {

		var url = compare_url ;

		if ( url.indexOf("?") >= 0 ) {
			url = url + "&nospeedy" ;
		}
		else {
			url = url + "?nospeedy" ;
		}
	}
	

	var compare_url = url ;
	// console.log(compare_url); return false;

	$.ajax({
		url: "inc/check-additional-speed.php",
		method: "POST",
		data: {
			website_url: compare_url,
			speedtype: table_id,
			website_id: website_id,
			table_id: table_id,
			additional_id: additional_id,
			action: "check-additional-speed-nospeedy"
		},
		dataType: "JSON",
		timeout: 0,
		success: function (data) {
			console.log(data) ;
			script_loading++;
			if (data.status == "done") {
				var content = data.message;


				// Old Speed Score :
				/*****  *****/
				$("."+data_oldy).find(".performance").text(content.desktop);
				$("."+data_oldy).find(".accessibility").text(content.accessibility);
				$("."+data_oldy).find(".best-practices").text(content.bestpractices);
				$("."+data_oldy).find(".seo").text(content.seo);
				$("."+data_oldy).find(".pwa").text(content.pwa);
				$("."+data_oldy).find(".fcp").text(content.FCP);
				$("."+data_oldy).find(".lcp").text(content.LCP);
				$("."+data_oldy).find(".mpf").text(content.MPF);
				$("."+data_oldy).find(".cls").text(content.CLS);
				$("."+data_oldy).find(".tbt").text(content.TBT);


				if ( data_oldy == "url2_inst_speed" ) {
					$("#wsa1-desktop-speed").val(0);
					$("#wosa1-desktop-speed").val(content.performance);

				}

				if ( data_oldy == "url3_inst_speed" ) {
					$("#wsa2-desktop-speed").val(0);
					$("#wosa2-desktop-speed").val(content.performance);

				}

			}
			else {
				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

				setTimeout(function(){ $(".loader").hide().html('') ; },1000);
			}
		},
		error: function (xhr) {
			console.error(xhr.statusText + xhr.responseText);
			setTimeout(function(){ $(".loader").hide().html('') ; },1000);
		},
		complete: function () {

			if (script_loading >= 2) {
				manage_additional_nospeedy_speed(website_id,additional_id,table_id) ;

				setTimeout(function(){$(".loader").hide().html('') ;},1000);
			}
		}
	});

	$.ajax({
		url: "inc/check-additional-speed-mobile.php",
		method: "POST",
		data: {
			website_url: compare_url,
			speedtype: table_id,
			website_id: website_id,
			additional_id: additional_id,
			action: "check-additional-speed-mobile-nospeedy"
		},
		dataType: "JSON",
		timeout: 0,
		success: function (data) {

			console.log(data);
			script_loading++;

			if (data.status == "done") {

				var content = data.message;


				// Old Speed Score :
				// /***** *****/
				$("."+data_oldy).find(".performance_m").text(content.mobile);
				$("."+data_oldy).find(".accessibility_m").text(content.accessibility);
				$("."+data_oldy).find(".best-practices_m").text(content.bestpractices);
				$("."+data_oldy).find(".seo_m").text(content.seo);
				$("."+data_oldy).find(".pwa_m").text(content.pwa);
				$("."+data_oldy).find(".fcp_m").text(content.FCP);
				$("."+data_oldy).find(".lcp_m").text(content.LCP);
				$("."+data_oldy).find(".mpf_m").text(content.MPF);
				$("."+data_oldy).find(".cls_m").text(content.CLS);
				$("."+data_oldy).find(".tbt_m").text(content.TBT);
				
				if(data.speedyType=='newUrl2'){
					var mobile = content.mobile;
					var myArray = mobile.split("/");
					var arr = myArray['0'];
					$('.new-analyze-speed-for-url2').attr('data-oldmscore',arr).attr('data-currentmscore',arr)
				}else if(data.speedyType=='newUrl3'){
					var mobile = content.mobile;
					var myArray = mobile.split("/");
					var arr = myArray['0'];
					$('.new-analyze-speed-for-url3').attr('data-oldmscore',arr).attr('data-currentmscore',arr)
				}

				if ( data_oldy == "url2_inst_speed" ) {
					$("#wsa1-mobile-speed , #wosa1-mobile-speed").val(0);
					$("#wosa1-mobile-speed").val(content.performance);



				}

				if ( data_oldy == "url3_inst_speed" ) {
					$("#wosa2-mobile-speed").val(content.performance);
					$("#wsa2-mobile-speed").val(0);

				}

			}
			else {
				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

				setTimeout(function(){ $(".loader").hide().html('') ; },1000);
			}

		},
		error: function (xhr) { // if error occured
			script_loading++;
			console.error(xhr.statusText + xhr.responseText);
			setTimeout(function(){ $(".loader").hide().html('') ; },1000);
		},
		complete: function () {

			if (script_loading >= 2) {
				manage_additional_nospeedy_speed(website_id,additional_id,table_id);

				setTimeout(function(){$(".loader").hide().html('') ;},1000);
			}
		}

	});
//    }
}




</script>
<script>
//new-process-installation mode
$(document).on('change','input[name="self_install"]', function() {

	// Get selected radio button value
	var managerId = <?= $user_id ?>;
	var boostId = <?= $project_id ?>;
	var installModeVal = $('input[name="self_install"]:checked').val();
	var installModeRadio = 'Submit';
	if (installModeVal=='yes') {
		$(".team-installation-confirm").show();
		$(".select-installation-mode").hide();
		
	} 
	else{		
		$('.swal2-container ').css('display','none');
		Swal.close();
	}

	// AJAX submission logic goes here
	$.ajax({
		url: "./common.php",
		type: "post",
		data: {                        
			managerId: managerId,
			boostId : boostId,
			installModeVal : installModeVal,
			installModeRadio : installModeRadio
		},
		success: function (response) {
			console.log(response);
			var obj = $.parseJSON(response);
			if(obj.status==1){
				if(obj.message.self_install=='yes'){
					$(".team-installation-confirm").show();
					$(".select-installation-mode").hide();
				
				}
				else{
					$('.swal2-container ').css('display','none');
					Swal.close();
				}									
					
			}else{
				console.log(obj.message)
			}
		}
	});

});
	
	//self-installation
	$(document).on('click','.update-btn', function() {
			var managerId = <?= $user_id ?>;
			var boostId = <?= $project_id ?>;
			var selfVal  = $(this).data('value');
			// console.log(selfVal); return false;
			var selfInsBtn = 'Submit';
				$.ajax({
					url: "./common.php",
					type: "post",
					data: {          
									
						managerId: managerId,
						boostId : boostId,
						selfVal : selfVal,
						selfInsBtn : selfInsBtn
					},
					success: function (response) {
						console.log(response);
						var obj = $.parseJSON(response);
						if(obj.status==1){
							if(obj.message.self_install_team=='self'){
								$('.swal2-container ').css('display','none');
								
							}else{
								// $('#ThanksInstall').css('display','block');
								// $('#InstallationRadioBtn').css('display','none');
							}										
								
						}else{
							console.log(obj.message)
						}
					}
		});
	});
	

//support-ticket-installation
$(document).on('click', '.supportTicket', function () {
	var managerId = <?= $user_id ?> ;
	var userEmailId = '<?=$row['email']?>';
	var selfVal = $(this).data('value');
	// console.log(selfVal); return false;
	var selfInsBtn = 'Submit';
	$.ajax({
		url: "<?=HOST_HELP_URL?>actions/user/support/create_ticket1",
		type: "post",
		data: {
			'subject': 'Fully Installation Request',
			'priority': 'medium',
			'department': '2',
			'userEmail': userEmailId,
			'message': 'Need to install website speedy script and parameters for my website',

		},
		beforeSend: function () {
			$(".loader").show().html("<div class='loader_s rty'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Building your request. It might take few seconds</p><p><span class='auto-type'></span></p></div>");
			loaderest()
		},
		success: function (response) {
			console.log(response);
			var obj = $.parseJSON(response);
			console.log(obj.data);
			if (obj.status == 1) {
				$(".loader").hide()

				$("#selfInsBtn button").hide();
				$('.supportTicket').hide();

				$('#viewTicketBtn').html('<a type="button" target="_blank" href="<?=HOST_HELP_URL?>user/support/tickets/all" class="btn btn-primary mt-2">View Request</a>').css('display', 'block'); 

				// var ticketUrl = "<?=HOST_HELP_URL?>user/support/ticket/"+obj.data;
				// localStorage.setItem("ticketUrl", ticketUrl);

				// save help.websitespeedy ticket details
				$.ajax({
					url: "inc/help-support-tickets.php",
					type: "post",
					data: {
						action:"insert-support-ticket",
						website_id: <?=$project_id?>,
						ticket_type: 'Script Installtion By Team',
						ticket_id: obj.data,
					},
					dataType: "JSON",
					success:function(response){ console.log(response) },
					error:function(response){ console.log(response) },
				});

			}
			else {
				console.error(obj.message)
			}
		}
	});
});



</script>


<?php 
$check_first_speedBtn ='';
if(isset($check_first_speed) && $check_first_speed=='checkedspeed'){
	$check_first_speedBtn= 'none';
}else{
	$check_first_speedBtn= 'block';
} ?>

<script>
var clickCount = 0;
$(document).on('click', '.boost_speed_first', function () {

clickCount++;
if (clickCount == '1') {

	Swal.close();

	if ( getting_no_speedy1 == 0 && getting_no_speedy2 == 0 ) {
		$('.child-tab-1').css('display', 'block');
		$('#checkspeedId').hide();
	}
	else {

		$(".loader").show().html("<div class='loader_s hjk'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p> <p><span class='auto-type'></span></p><p><span class='auto-type'></span></p></div>") ;
         loaderest()


		var myInterval = setInterval(function() {
			
			if ( getting_no_speedy1 == 0 && getting_no_speedy2 == 0 ) {
				$(".loader").hide();
				$('.child-tab-1').css('display', 'block');
				$('#checkspeedId').hide();
				clearInterval(myInterval) ;
			}

		}, 1000);

		// $('.swal2-container ').css('display', 'none');
	}
	var managerId = <?= $user_id ?> ;
	var boostId = <?= $project_id ?> ;
	var checkedspeed = 'checkedspeed';
	var checkConfirmSpeedFirstBtn = 'Submit';

	$.ajax({
		url: "./common.php",
		type: "post",
		data: {
			managerId: managerId,
			boostId: boostId,
			checkedspeed: checkedspeed,
			checkConfirmSpeedFirstBtn: checkConfirmSpeedFirstBtn
		},
		success: function (response) {
			console.log(response);
			var obj = $.parseJSON(response);
			var checkedMessage = obj.message.check_first_speed;
			if (obj.message.check_first_speed == 'checkedspeed') {
				$('#checkspeedId').hide();
				$('#boostBtn').hide();
				$('#continueBtn').hide();
				$('#confAndGoto2').show();
			}
			else {
				$('#checkspeedId').show();
			}

		}
	})

}
else {

	var managerId = <?= $user_id ?> ;
	var boostId = <?= $project_id ?> ;
	var checkedspeed = 'checkedspeed';
	var checkConfirmSpeedFirstBtn = 'Submit';

	$.ajax({
		url: "./common.php",
		type: "post",
		data: {
			managerId: managerId,
			boostId: boostId,
			checkedspeed: checkedspeed,
			checkConfirmSpeedFirstBtn: checkConfirmSpeedFirstBtn
		},
		success: function (response) {
			console.log(response);
			var obj = $.parseJSON(response);
			var checkedMessage = obj.message.check_first_speed;
			if (obj.message.check_first_speed == 'checkedspeed') {
				$('#checkspeedId').hide();
			}
			else {
				$('#checkspeedId').show();
			}

		}
	})
}
});



////////////////check-speed-first////////////////

//123
$(".checkCurrentSpeedFirst").click(function () {
	console.log(clickCount);
	if(clickCount>=1){
				const swalWithBootstrapButtons = Swal.mixin({
				customClass: {
					confirmButton: 'btn btn-success',
					cancelButton: 'btn btn-danger'
				},
				buttonsStyling: false
			});

			swalWithBootstrapButtons.fire({
				title: 'This will take upto 5 minutes, You can do this later too after boosting speed.',
				html: `<div class="review_popup_dd ">	
							
						<button type="button" class="goto-next-step btn btn-success" data-satisfy="1" data-plaform="0" data-next="2" style="display: inline-block;">Boost Speed first</button>
						<span style="display:none" id="checkspeedId"> 
						<button type="button" class="boost_speed_first btn btn-success" aria-label="" style="display: inline-block;">Confirm & Get current speed first</button>
						</span>
							<span class="stp_three">   
							   <span class="enterurl_cons">
							    How can I check old speed later? 
								<span class="tooltiptext1">To check old speed add "?nospeedy" parameter while checking the speed in the end of your url, this code disable the website speedy script. You can check speed with "?nospeedy" in Google Page Speed Insight, GTMetrix & Pingdom. To check in detail go to FAQ section in dashboard question 1</span>
								</span>
							</span>
						</div>`,
				showCancelButton: false,
				showCloseButton: true,
				reverseButtons: true,
				showConfirmButton: false,
				confirmButtonText: 'Boost Speed first',
				// preConfirm: () => {
				// 	return false;
				// }
			}).then((result) => {
				// alert('hiii')
				
				if (result.isConfirmed) {
					

				}
			});
	}else{
				const swalWithBootstrapButtons = Swal.mixin({
				customClass: {
					confirmButton: 'btn btn-success',
					cancelButton: 'btn btn-danger'
				},
				buttonsStyling: false
			});

			swalWithBootstrapButtons.fire({
				title: 'This will take upto 5 minutes, You can do this later too after boosting speed.',
				html: `<div class="review_popup_dd ">	
							
						<button type="button" class="goto-next-step btn btn-success" data-satisfy="1" data-plaform="0" data-next="2" style="display: inline-block;">Boost Speed first</button>
						<span style="display: <?php echo $check_first_speedBtn; ?>" id="checkspeedId"> 
						<button type="button" class="boost_speed_first btn btn-success" aria-label="" style="display: inline-block;">Confirm & Get current speed first</button>
						</span>
							<span class="stp_three">  <span class="enterurl_cons">
							How can I check old speed later?  
								<span class="tooltiptext1">To check old speed add "?nospeedy" parameter while checking the speed in the end of your url, this code disable the website speedy script. You can check speed with "?nospeedy" in Google Page Speed Insight, GTMetrix & Pingdom. To check in detail go to FAQ section in dashboard question 1</span>

								</span>
							</span>
						</div>`,
				showCancelButton: false,
				showCloseButton: true,
				reverseButtons: true,
				showConfirmButton: false,
				confirmButtonText: 'Boost Speed first',
				
					
			}).then((result) => {
				// alert('hiii')
				
				if (result.isConfirmed) {
					

				}
			});
	}
			$(".tooltip1").click(function () {
				$(this).next(".tooltiptext1").toggleClass("active");
			});
   
});




/////////////////end//////////////////////////


$(document).ready(function(){

	$(".refresh-nospeedy-url").click(function(){
		refreshNospeedyScript(this) ;
		// setTimeout(function(){ $(".loader").hide().html(""); },10000);
	});

});

function refreshNospeedyScript(btn) {

	var table = $(btn).attr("table") ;
	var website = $(btn).attr("website") ;
	var additional = $(btn).attr("additional") ;
	var url = $(btn).attr("url") ;

	var mobile_content = desktop_content = '' ;

	if ( table && website && additional && url ) {

		// start loader
		$(".loader").show().html("<div class='loader_s dfg'><dotlottie-player src='//lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player><p id='loader_text'>Analyzing your website. It might take 2-3 mins</p><p><span class='auto-type'></span></p></div>");
		loaderest() ;
		// end loader

		var api_key = $("meta[name='google_pagespeed_api']").attr("content") ;

		if ( url.includes("?") ) {
			var request_website_url = url+"&nospeedy" ;
		}
		else {
			var request_website_url = url+"?nospeedy" ;
		}

		// Construct the API endpoint
		var apiEndpoint = `//www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_website_url)}&key=${api_key}&strategy=desktop&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;


		fetch(apiEndpoint).then(response => {

		    if (!response.ok) {
		    	$(".loader").hide().html("");
		        throw new Error('Please thoroughly review your URL ('+url+') within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
		    }
		    return response.json();
		})
		.then(data => {
		    // Process your data here

		    if ( data.hasOwnProperty("lighthouseResult") ) {

				var lighthouseResult = data.lighthouseResult ;

				var requestedUrl = lighthouseResult.requestedUrl ;
				var finalUrl = lighthouseResult.finalUrl ;
				var userAgent = lighthouseResult.userAgent ;
				var fetchTime = lighthouseResult.fetchTime ;
				var environment = JSON.stringify(lighthouseResult.environment) ;
				var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
				var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
				var audits = JSON.stringify(lighthouseResult.audits) ;
				var categories = JSON.stringify(lighthouseResult.categories) ;
				var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
				var i18n = JSON.stringify(lighthouseResult.i18n) ;

				var desktop = lighthouseResult.categories.performance.score ;
				desktop = Math.round(desktop * 100) ;

				if ( desktop > 0 ) {

					$.ajax({
						url: "inc/refresh-nospeedy-fetch.php",
						// url: "inc/check-additional-speed-fetch.php",
						type: "post",
						data: {
							url: url,
							additional: additional,
							website: website,
							table:table,
							// lighthouseResult:lighthouseResult,
							requestedUrl:requestedUrl,
							finalUrl:finalUrl,
							userAgent:userAgent,
							fetchTime:fetchTime,
							environment:environment,
							runWarnings:runWarnings,
							configSettings:configSettings,
							audits:audits,
							categories:categories,
							categoryGroups:categoryGroups,
							i18n:i18n,
							action:"refresh-nospeedy-desktop",
						},
						dataType: "JSON",
						beforeSend: function () {},
						success: function (obj) {

							if (obj.status == 'done') {

								// fill desktop data ===========================
								desktop_content = obj.message;

								// now get mobile speed ===========================
								var apiEndpoint = `//www.googleapis.com/pagespeedonline/v5/runPagespeed?url=${encodeURIComponent(request_website_url)}&key=${api_key}&strategy=mobile&category=ACCESSIBILITY&category=BEST_PRACTICES&category=PERFORMANCE&category=PWA&category=SEO`;

						    	fetch(apiEndpoint).then(response => {

						            if (!response.ok) {
						            	$(".loader").hide().html("");
						                throw new Error('Please thoroughly review your URL ('+url+') within the domain. Ensure there are no duplicate URLs and that all URLs are correct.');
						            }
						            return response.json();
						        })
						        .then(data => {
						            // Process your data here

						            if ( data.hasOwnProperty("lighthouseResult") ) {

							            var lighthouseResult = data.lighthouseResult ;

										var requestedUrl = lighthouseResult.requestedUrl ;
										var finalUrl = lighthouseResult.finalUrl ;
										var userAgent = lighthouseResult.userAgent ;
										var fetchTime = lighthouseResult.fetchTime ;
										var environment = JSON.stringify(lighthouseResult.environment) ;
										var runWarnings = JSON.stringify(lighthouseResult.runWarnings) ;
										var configSettings = JSON.stringify(lighthouseResult.configSettings) ;
										var audits = JSON.stringify(lighthouseResult.audits) ;
										var categories = JSON.stringify(lighthouseResult.categories) ;
										var categoryGroups = JSON.stringify(lighthouseResult.categoryGroups) ;
										var i18n = JSON.stringify(lighthouseResult.i18n) ;

										$.ajax({
											url: "inc/refresh-nospeedy-fetch.php",
											method: "POST",
											data: {
												url: url,
												additional: additional,
												website: website,
												table:table,
												// lighthouseResult:lighthouseResult,
												requestedUrl:requestedUrl,
												finalUrl:finalUrl,
												userAgent:userAgent,
												fetchTime:fetchTime,
												environment:environment,
												runWarnings:runWarnings,
												configSettings:configSettings,
												audits:audits,
												categories:categories,
												categoryGroups:categoryGroups,
												i18n:i18n,
												action:"refresh-nospeedy-mobile",
											},
											dataType: "JSON",
											timeout: 0,
											success: function (obj) {

												if (obj.status == "done") {

													// fill mobile data ===========================
													mobile_content = obj.message;

													// merge both desktop & mobile scores
													$.ajax({
														url: "inc/refresh-nospeedy-fetch.php",
														method: "POST",
														data: {
															url: url,
															additional: additional,
															website: website,
															table:table,
															action:"refresh-nospeedy-merge",
														},
														dataType: "JSON",
														timeout: 0,
														success: function (res) {

															if( res.status == "done" ) {

																// fill desktop data ===========================

																console.log("desktop_content : ") ;
																console.log(desktop_content) ;

																console.log("mobile_content : ") ;
																console.log(mobile_content) ;

																if ( table == "url3-old-speed-table" ) {
																	$("#wosa2-desktop-speed").val(desktop_content.performance);
																	$("#wosa2-mobile-speed").val(mobile_content.performance);

																	if ( desktop_content.performance > 0 && mobile_content.performance > 0 ) {

																		$(".refresh-nospeedy-parent:eq(2),.refresh-nospeedy-parent:eq(5)").hide() ;
																	}
																	else {
																		$(".refresh-nospeedy-parent:eq(2),.refresh-nospeedy-parent:eq(5)").show() ;
																	}


																}
																else if ( table == "url2-old-speed-table" ) {
																	$("#wosa1-desktop-speed").val(desktop_content.performance);
																	$("#wosa1-mobile-speed").val(mobile_content.performance);

																	if ( desktop_content.performance > 0 && mobile_content.performance > 0 ) {

																		$(".refresh-nospeedy-parent:eq(1),.refresh-nospeedy-parent:eq(4)").hide() ;
																	}
																	else {
																		$(".refresh-nospeedy-parent:eq(1),.refresh-nospeedy-parent:eq(4)").show() ;
																	}
																}
																else {
																	$("#wos-desktop-speed").val(desktop_content.performance);
																	$("#wos-mobile-speed").val(mobile_content.performance);

																	if ( desktop_content.performance > 0 && mobile_content.performance > 0 ) {

																		$(".refresh-nospeedy-parent:eq(0),.refresh-nospeedy-parent:eq(3)").hide() ;
																	}
																	else {
																		$(".refresh-nospeedy-parent:eq(0),.refresh-nospeedy-parent:eq(3)").show() ;
																	}
																}


																// Old Speed Score :
																$("."+table+".desktop").find(".performance").text(desktop_content.desktop);
																$("."+table+".desktop").find(".accessibility").text(desktop_content.accessibility);
																$("."+table+".desktop").find(".best-practices").text(desktop_content.bestpractices);
																$("."+table+".desktop").find(".seo").text(desktop_content.seo);
																$("."+table+".desktop").find(".pwa").text(desktop_content.pwa);
																$("." + table+".desktop").find(".fcp").text(parseFloat(desktop_content.FCP).toFixed(2));
																$("." + table+".desktop").find(".lcp").text(parseFloat(desktop_content.LCP).toFixed(2));
																$("." + table+".desktop").find(".mpf").text(parseFloat(desktop_content.MPF).toFixed(2));
																$("." + table+".desktop").find(".cls").text(parseFloat(desktop_content.CLS).toFixed(2));
																$("." + table+".desktop").find(".tbt").text(parseFloat(desktop_content.TBT).toFixed(2));
																$("." + table+".desktop").find(".si").text(desktop_content.SI);

																// fill mobile data ===========================
																$("."+table+".mobile").find(".performance_m").text(mobile_content.mobile)
																$("."+table+".mobile").find(".accessibility_m").text(mobile_content.accessibility);
																$("."+table+".mobile").find(".best-practices_m").text(mobile_content.bestpractices);
																$("."+table+".mobile").find(".seo_m").text(mobile_content.seo);
																$("."+table+".mobile").find(".pwa_m").text(mobile_content.pwa);
																$("."+table+".mobile").find(".fcp_m").text(parseFloat(mobile_content.FCP).toFixed(2));
																$("."+table+".mobile").find(".lcp_m").text(parseFloat(mobile_content.LCP).toFixed(2));
																$("."+table+".mobile").find(".mpf_m").text(parseFloat(mobile_content.MPF).toFixed(2));
																$("."+table+".mobile").find(".cls_m").text(parseFloat(mobile_content.CLS).toFixed(2));
																$("."+table+".mobile").find(".tbt_m").text(parseFloat(mobile_content.TBT).toFixed(2));
																$("."+table+".mobile").find(".si_m").text(mobile_content.SI);

															}
															else {
																Swal.fire({
																	title: 'Error!',
																	icon: 'error',
																	text: obj.message,
																	showDenyButton: false,
																	showCancelButton: false,
																	allowOutsideClick: false,
																	allowEscapeKey: false,
																	confirmButtonText: 'Close',
																}).then((result) => {
																	
																}) ;
															}

														},
														error: function (xhr) {
															console.error(xhr.statusText + xhr.responseText);
														},
														complete: function () {
															$(".loader").hide().html("");
														}
													})


												}
												else {

													$(".loader").hide().html("");

													Swal.fire({
														title: 'Error!',
														icon: 'error',
														text: obj.message,
														showDenyButton: false,
														showCancelButton: false,
														allowOutsideClick: false,
														allowEscapeKey: false,
														confirmButtonText: 'Close',
													}).then((result) => {
														
													}) ;

													// setTimeout(function(){ $(".loader").hide().html('') ; },1000);
												}

											},
											error: function (xhr) {
												$(".loader").hide().html(""); 
												// if error occured
												console.error(xhr.statusText + xhr.responseText);
												// setTimeout(function(){ $(".loader").hide().html('') ; },1000);
											},
											complete: function () {
												// $(".loader").hide().html("");
											}

										});

						            }
						            else {

						            	$(".loader").hide().html("");

										Swal.fire({
											title: "Error!",
											icon: "error",
											text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
											showDenyButton: false,
											showCancelButton: false,
											allowOutsideClick: false,
											allowEscapeKey: false,
											confirmButtonText: 'Close',
										}).then((result) => {
											if (result.isConfirmed) {
												// $("#addedNewUrl")[0].reset() ;
											}
										}) ; 
						            	
						            }


						        })
						        .catch(error => {

						        	$(".loader").hide().html("");

						            console.error('Fetch error:', error);

									Swal.fire({
										title: "Error!",
										icon: "error",
										text: error,
										showDenyButton: false,
										showCancelButton: false,
										allowOutsideClick: false,
										allowEscapeKey: false,
										confirmButtonText: 'Close',
									}).then((result) => {
										if (result.isConfirmed) {
											// $("#addedNewUrl")[0].reset() ;
										}
									}) ; 

									// setTimeout(function(){$(".loader").hide().html('') ;},1000);
						        });

							}
							else {

								$(".loader").hide().html("");

								Swal.fire({
									title: 'Error!',
									icon: 'error',
									text: obj.message,
									showDenyButton: false,
									showCancelButton: false,
									allowOutsideClick: false,
									allowEscapeKey: false,
									confirmButtonText: 'Close',
								}).then((result) => {
									if (result.isConfirmed) {
										// $("#addedNewUrl")[0].reset() ;
									}
								}) ;

								// setTimeout(function(){$(".loader").hide().html('') ;},1000);
							}

						},
						error: function (xhr, status, error) {

							$(".loader").hide().html("");
							console.error(xhr.responseText);
							// setTimeout(function(){$(".loader").hide().html('') ;},1000);
						}
					});

				}
				else {

					$(".loader").hide().html("");

					Swal.fire({
						title: "Error!",
						icon: "error",
						text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
						showDenyButton: false,
						showCancelButton: false,
						allowOutsideClick: false,
						allowEscapeKey: false,
						confirmButtonText: 'Close',
					}).then((result) => {}) ; 

					// setTimeout(function(){$(".loader").hide().html('') ;},1000);

				}

		    }
		    else {

		    	$(".loader").hide().html("");

				Swal.fire({
					title: "Error!",
					icon: "error",
					text: "Invalid URL, can't get the speedscore. Please check entered URL & try again.",
					showDenyButton: false,
					showCancelButton: false,
					allowOutsideClick: false,
					allowEscapeKey: false,
					confirmButtonText: 'Close',
				}).then((result) => {
					if (result.isConfirmed) {
						// $("#addedNewUrl")[0].reset() ;
					}
				}) ; 

				// setTimeout(function(){$(".loader").hide().html('') ;},1000);

		    }

		    // You can access various properties of data to get speed and performance metrics
		})
		.catch(error => {

			$(".loader").hide().html("");
		    console.error('Fetch error:', error);

			Swal.fire({
				title: "Error!",
				icon: "error",
				text: error,
				showDenyButton: false,
				showCancelButton: false,
				allowOutsideClick: false,
				allowEscapeKey: false,
				confirmButtonText: 'Close',
			}).then((result) => {
				if (result.isConfirmed) {}
			}) ; 

		});


	}
	else {

		Swal.fire({
			title: 'Error!',
			icon: 'error',
			text: 'Invalid request',
			showDenyButton: false,
			showCancelButton: false,
			allowOutsideClick: false,
			allowEscapeKey: false,
			confirmButtonText: 'Close',
		}).then((result) => {}) ;

	}
}

function popupManualAudit() {
	$(".nav.nav-tabs li").toggleClass("active-tab") ;
	$(".tab-content div.tab-pane").toggleClass("active-tab") ;

	$('html, body').animate({
		scrollTop: $("html, body").offset().top + 50
	},500);
}

</script>



<?php

	if( isset($website_data['self_install']) &&  $website_data['self_install']=='yes'){
		$self = 'block';
		$instalHide =  'none';
		$checked = 'checked';
	}else{
		$self = 'none';
		$checked = '';
	}
	if(isset($website_data['self_install_team']) && $website_data['self_install_team'] == 'self') {
	
		$self = 'none';
		$allDiv = 'none';
		$container =   '$(".swal2-container").css("display", "none")';
	}else{
		$container= '';
	}


	// echo $row['self_install'];
	$selfInstall = $website_data['self_install'];
	if ( ($new_website=='new_website' && $user_id!='' ) || $selfInstall =='yes' ) {

		$hst_query = $conn->query(" SELECT ticket_id , ticket_type FROM `help_support_tickets` WHERE website_id = '$project_id' AND ticket_type LIKE 'Script Installtion By Team' AND status = 1 ") ;

		if ( $hst_query->num_rows > 0 ) {

			$hst_data = $hst_query->fetch_assoc() ;

			$request_buttons = '<button type="button" class="btn btn-primary update-btn"  data-value="self" style="display:none">Try self installation</button>
				<button type="button" class="btn btn-primary mt-2 supportTicket" style="display:none">See your installation request</button>
				<div id="viewTicketBtn"><a type="button" target="_blank" href="<?=HOST_HELP_URL?>user/support/ticket/'.$hst_data["ticket_id"].'" class="btn btn-primary mt-2">View Request</a></div>' ;
		}
		else {
			$request_buttons = '<button type="button" class="btn btn-primary update-btn"  data-value="self">Try self installation</button>
				<button type="button" class="btn btn-primary mt-2 supportTicket" >See your installation request</button>
				<div id="viewTicketBtn" style="display:none"></div>' ;
		}

			echo '<script>
				$(document).ready(function() {


					// Display Swal modal on page load
					Swal.fire({
						title: `<img src="//websitespeedycdn.b-cdn.net/speedyweb/images/Select_Installation_Mode.png" style="max-width: 60px;display: block;">`,
						html: `
<div class="installation_ss" id="InstallationRadioBtn" style="display:">
<label>
		<h2> Select Installation Mode </h2>
	</label>

	<div class="select-installation-mode">
		<label class="text_smn">Would you like Website Speedy team to do the installation on your website ?</label>
		<div class="form-check">
			<input type="radio" class="form-check-input" id="install1" name="self_install" value="yes"  '.$checked.'>
			<div class="check" ></div>
			<label class="form-check-label" for="install1">I want website Speedy team to do the installation</label>
		</div>
		<div class="form-check">
			<input type="radio" class="form-check-input" id="install2" name="self_install" value="no" >
			<div class="check" ></div>
			<label class="form-check-label" for="install2">I want to install myself</label>
		</div>
	</div>

	<div class="form-group thanksinstall team-installation-confirm" id="ThanksInstall" style="display:'.$self.'">
		<div class="flex-col">
			<div class="pp_heading">
				<label>Thanks for selecting WebsiteSpeedy 
				Our team will contact you shortly to assist you with installation, Please make sure to check your email, check spam email in you do not hear from us in 24 hours ( Except Saturday and Sunday ),<br>in the mean time you can -</label>
			</div>
		</div>
		<div class="form-group social_s_s">
			<span>1. <a href="<?=HOST_HELP_URL?>" target="_blank">Explore our Knowledge base </a> </span>
			<span>2. <a href="'.HOST_URL.'why-website-speed-matters.php" target="_blank">Learn Why Speed Matters</a></span>
			3. Follow us on Social
			<div class="social__links">
				<a href="//www.facebook.com/websitespeedy" target="_blank">
					<svg height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve">
						<path style="fill:#385C8E;" d="M134.941,272.691h56.123v231.051c0,4.562,3.696,8.258,8.258,8.258h95.159  c4.562,0,8.258-3.696,8.258-8.258V273.78h64.519c4.195,0,7.725-3.148,8.204-7.315l9.799-85.061c0.269-2.34-0.472-4.684-2.038-6.44  c-1.567-1.757-3.81-2.763-6.164-2.763h-74.316V118.88c0-16.073,8.654-24.224,25.726-24.224c2.433,0,48.59,0,48.59,0  c4.562,0,8.258-3.698,8.258-8.258V8.319c0-4.562-3.696-8.258-8.258-8.258h-66.965C309.622,0.038,308.573,0,307.027,0  c-11.619,0-52.006,2.281-83.909,31.63c-35.348,32.524-30.434,71.465-29.26,78.217v62.352h-58.918c-4.562,0-8.258,3.696-8.258,8.258  v83.975C126.683,268.993,130.379,272.691,134.941,272.691z"/>
					</svg>
				</a>
				<a href="//www.instagram.com/websitespeedy/" target="_blank">
					<svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 32 32" fill="none">
						<rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint0_radial_87_7153)"/>
						<rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint1_radial_87_7153)"/>
						<rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint2_radial_87_7153)"/>
						<path d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z" fill="white"/>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z" fill="white"/>
						<path fill-rule="evenodd" clip-rule="evenodd" d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z" fill="white"/>
						<defs>
							<radialGradient id="paint0_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)">
								<stop stop-color="#B13589"/>
								<stop offset="0.79309" stop-color="#C62F94"/>
								<stop offset="1" stop-color="#8A3AC8"/>
							</radialGradient>
							<radialGradient id="paint1_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)">
								<stop stop-color="#E0E8B7"/>
								<stop offset="0.444662" stop-color="#FB8A2E"/>
								<stop offset="0.71474" stop-color="#E2425C"/>
								<stop offset="1" stop-color="#E2425C" stop-opacity="0"/>
							</radialGradient>
							<radialGradient id="paint2_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)">
								<stop offset="0.156701" stop-color="#406ADC"/>
								<stop offset="0.467799" stop-color="#6A45BE"/>
								<stop offset="1" stop-color="#6A45BE" stop-opacity="0"/>
							</radialGradient>
						</defs>
					</svg>
				</a>
				<a href="//www.linkedin.com/company/websitespeedy/" target="_blank">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -2 44 44" version="1.1">
						<g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<g id="Color-" transform="translate(-702.000000, -265.000000)" fill="#007EBB">
								<path d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z" id="LinkedIn"> </path>
							</g>
						</g>
					</svg>
				</a>
				<a href="//www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" target="_blank">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -7 48 48" version="1.1">
						<g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<g id="Color-" transform="translate(-200.000000, -368.000000)" fill="#CE1312">
								<path d="M219.044,391.269916 L219.0425,377.687742 L232.0115,384.502244 L219.044,391.269916 Z M247.52,375.334163 C247.52,375.334163 247.0505,372.003199 245.612,370.536366 C243.7865,368.610299 241.7405,368.601235 240.803,368.489448 C234.086,368 224.0105,368 224.0105,368 L223.9895,368 C223.9895,368 213.914,368 207.197,368.489448 C206.258,368.601235 204.2135,368.610299 202.3865,370.536366 C200.948,372.003199 200.48,375.334163 200.48,375.334163 C200.48,375.334163 200,379.246723 200,383.157773 L200,386.82561 C200,390.73817 200.48,394.64922 200.48,394.64922 C200.48,394.64922 200.948,397.980184 202.3865,399.447016 C204.2135,401.373084 206.612,401.312658 207.68,401.513574 C211.52,401.885191 224,402 224,402 C224,402 234.086,401.984894 240.803,401.495446 C241.7405,401.382148 243.7865,401.373084 245.612,399.447016 C247.0505,397.980184 247.52,394.64922 247.52,394.64922 C247.52,394.64922 248,390.73817 248,386.82561 L248,383.157773 C248,379.246723 247.52,375.334163 247.52,375.334163 L247.52,375.334163 Z" id="Youtube"> </path>
							</g>
						</g>
					</svg>
				</a>
			</div>
		</div>
		<div class="form-group selfInsBtn_s ">
			<form id="selfInsBtn" method="post">
				'.$request_buttons.'
			</form>
		</div>
	</div>

</div>
					
								`,
						allowOutsideClick: false,
						showConfirmButton: false,
						allowEscapeKey: false,
						showCloseButton: true,
						showCancelButton: false,
						allowEnterKey: false,
						onOpen: function(modalElement) {
							// Disable interaction with overlay
							modalElement.style.pointerEvents = "none";
						}
					});
					'.$container.'
				});


// other process
setTimeout(function(){

	$(".team-installation-confirm").hide();
	$(".select-installation-mode").show();

	var self_install = $("input[name=\'self_install\']:checked").val() ;
	if ( self_install && self_install == "yes" ) {
	    $(".select-installation-mode").hide();
	    $(".team-installation-confirm").show();
	}
},500);


				</script>';
			// Stop further execution of PHP script
			// die();
	}
	

?>

<script>
function addProShorts() {
	window.location.href = "<?=HOST_URL?>adminpannel/add-website.php";
}

$(document).ready(function () {
	var today = new Date();
	var dd = String(today.getDate()).padStart(2, '0');
	var mm = String(today.getMonth() + 1).padStart(2, '0');
	var yyyy = today.getFullYear();

	today = yyyy + '-' + mm + '-' + dd;
	$('#suitable-time-2').attr('min', today);
});

document.addEventListener('DOMContentLoaded', function() {

	// for url1
	var ws_mobile = Number($("#ws-mobile-speed").attr("data-value")) ;
	var ws_desktop = Number($("#ws-desktop-speed").attr("data-value")) ;

	var wos_mobile = Number($("#wos-mobile-speed").val()) ;
	var wos_desktop = Number($("#wos-desktop-speed").val()) ;

	var ws_status = $(".url1-newspeed-container").attr("data-ws_status") ;
	var blank_record = $(".url1-newspeed-container").attr("data-blank_record") ;


	if ( wos_desktop > 85 ) {

		$(".url1-popup-tr.desktop").hide() ;
		$(".url1-data-tr.desktop").show() ;

		if ( ws_status == "popup" ) {
			if ( blank_record == "desktop" ) {
				$(".url1-newspeed-container").attr("data-blank_record","") ;
			}
			else if ( blank_record == "both" ) {
				$(".url1-newspeed-container").attr("data-blank_record","mobile") ;
			}
		}
	}

	if ( wos_mobile > 70 ) {
		$(".url1-popup-tr.mobile").hide() ;
		$(".url1-data-tr.mobile").show() ;

		if ( ws_status == "popup" ) {
			if ( blank_record == "mobile" ) {
				$(".url1-newspeed-container").attr("data-blank_record","") ;
			}
			else if ( blank_record == "both" ) {
				$(".url1-newspeed-container").attr("data-blank_record","desktop") ;
			}

			if ( $(".url1-newspeed-container").hasClass("url-old-speed-score") ) {
				$(".url1-newspeed-container").removeClass("url-old-speed-score") ;
			}
		}
	}

	// for url2
	var wsa1_mobile = Number($("#wsa1-mobile-speed").attr("data-value")) ;
	var wsa1_desktop = Number($("#wsa1-desktop-speed").attr("data-value")) ;

	var wosa1_mobile = Number($("#wosa1-mobile-speed").val()) ;
	var wosa1_desktop = Number($("#wosa1-desktop-speed").val()) ;

	var ws_status = $(".url2-newspeed-container").attr("data-ws_status") ;
	var blank_record = $(".url2-newspeed-container").attr("data-blank_record") ;

	if ( wosa1_desktop > 85 ) {
		$(".url2-popup-tr.desktop").hide() ;
		$(".url2-data-tr.desktop").show() ;

		if ( ws_status == "popup" ) {
			if ( blank_record == "desktop" ) {
				$(".url2-newspeed-container").attr("data-blank_record","") ;
			}
			else if ( blank_record == "both" ) {
				$(".url2-newspeed-container").attr("data-blank_record","mobile") ;
			}
		}
	}

	if ( wosa1_mobile > 70 ) {
		$(".url2-popup-tr.mobile").hide() ;
		$(".url2-data-tr.mobile").show() ;

		if ( ws_status == "popup" ) {
			if ( blank_record == "mobile" ) {
				$(".url2-newspeed-container").attr("data-blank_record","") ;
			}
			else if ( blank_record == "both" ) {
				$(".url2-newspeed-container").attr("data-blank_record","desktop") ;
			}

			if ( $(".url2-newspeed-container").hasClass("url-old-speed-score") ) {
				$(".url2-newspeed-container").removeClass("url-old-speed-score");
			}
		}
	}

	// for url3
	var wsa2_mobile = Number($("#wsa2-mobile-speed").attr("data-value")) ;
	var wsa2_desktop = Number($("#wsa2-desktop-speed").attr("data-value")) ;

	var wosa2_mobile = Number($("#wosa2-mobile-speed").val()) ;
	var wosa2_desktop = Number($("#wosa2-desktop-speed").val()) ;

	var ws_status = $(".url3-newspeed-container").attr("data-ws_status") ;
	var blank_record = $(".url3-newspeed-container").attr("data-blank_record") ;

	if ( wosa2_desktop > 85 ) {
		$(".url3-popup-tr.desktop").hide() ;
		$(".url3-data-tr.desktop").show() ;

		if ( ws_status == "popup" ) {
			if ( blank_record == "desktop" ) {
				$(".url3-newspeed-container").attr("data-blank_record","") ;
			}
			else if ( blank_record == "both" ) {
				$(".url3-newspeed-container").attr("data-blank_record","mobile") ;
			}
		}
	}

	if ( wosa2_mobile > 70 ) {
		$(".url3-popup-tr.mobile").hide() ;
		$(".url3-data-tr.mobile").show() ;

		if ( ws_status == "popup" ) {
			if ( blank_record == "mobile" ) {
				$(".url3-newspeed-container").attr("data-blank_record","") ;
			}
			else if ( blank_record == "both" ) {
				$(".url3-newspeed-container").attr("data-blank_record","desktop") ;
			}

			if ( $(".url3-newspeed-container").hasClass("url-old-speed-score") ) {
				$(".url3-newspeed-container").removeClass("url-old-speed-score");
			}
		}
	}

}, false);


function back_installtion_steps(website_id, step = 1, step_id = "step1") {

	$.ajax({
		type: "POST",
		url: "inc/back-installtion-steps.php",
		data: {
			website_id: website_id,
			step: step,
			action: "back-installtion-steps",
		},
		dataType: "json",
		encode: true,
	}).done(function (data) {
		// console.log(data);
	}).fail(function () {

	}).always(function () {

		if ( step == 1 ) {
			$(".child-tab-1 , .confAndGoto2").hide();
			$("#boostBtn , #continueBtn").show();
		}

		var index = Number(step) - 1 ;

		$(".stepper.stepper-horizontal li a:eq("+index+")").click();

		$(".form-tab").removeClass("complited").addClass("d-none");

		$(".form-tab").each(function (i, o) {
			if (i < step) {
				$(o).addClass("complited").removeClass("d-none");
			}
		});

		$('html, body').animate({
			scrollTop: $("#" + step_id).offset().top - 200
		}, );
	});

}

</script>

<script src="<?=HOST_URL?>/adminpannel/js/script-installations.js"></script>