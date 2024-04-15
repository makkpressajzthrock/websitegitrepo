<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once('config.php');

ob_start() ;
session_regenerate_id() ;



require_once('inc/functions.php');
require_once('smtp/PHPMailerAutoload.php');

require_once('dompdf/autoload.inc.php'); // Include autoloader 


ob_clean();

	$user_id = $_SESSION['user_id'];
	$project_id = base64_decode($_GET["project"]);

	$website = $_GET["website"];

	$website_data = getTableData($conn, " boost_website ", " id = '$project_id' AND manager_id = '" . $_SESSION['user_id'] . "'");

// echo "string";	print_r($website_data) ;

    $installation = $website_data['installation'];
    $new_speed = $website_data['new_speed'];
    $satisfy = $website_data['satisfy'];

$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}

// Show Expire message //
	$plan_country = "";
		if($row['country'] != "101"){   // Matching user country to show plan link
		$plan_country = "-us";
	}
	include("error_message_bar_subscription.php");
// End Show Expire message //





$project_ids = base64_decode($_GET['project']);
$sqlURL = "SELECT * FROM `script_log` WHERE `site_id` = $project_ids order by id desc";
$resultURL = mysqli_query($conn, $sqlURL);

// print_r($resultURL) ;
if ( $resultURL->num_rows > 0 ) {
	$urlFetch = mysqli_fetch_assoc($resultURL);
	$url = $urlFetch['url'];

// 	echo "<pre>";
//   print_r($urlFetch); die;

	$urlLists = explode(',', $url);
}
else {
	$urlFetch = [];
	$url = '';
	$urlLists = [];
}


// print_r($urlFetch) ;


$count = 0;

$domain_url = "https://" . $_SERVER['HTTP_HOST'];

if($urlFetch['bunny']==1){
	$domain_url = "";
}


// print_r(($urlLists));
// echo count($urlLists);




while(count($urlLists) <3)
{
		// echo "Regenerate Script";

	$sqlURL = "DELETE from `script_log` WHERE `site_id` = $project_ids";
	mysqli_query($conn, $sqlURL);




	// echo $website_data['subscription_id']; 

		$upd = " UPDATE boost_website SET get_script = 0 where  manager_id='".$_SESSION['user_id']." ' and subscription_id = '".$website_data['subscription_id']."' and plan_type = 'Subscription'";
	// die;

			   $run= mysqli_query($conn,$upd);
			sleep(1);
			$user_id = $user_id;
			$site_id = $website_data['id'];
			if($website_data['plan_type']=="Free"){
				require_once('generate_script_free.php');
				// echo "subss";

			}else{
				// echo "sub";
				
				require_once('generate_script_paid.php');
			}

	sleep(1);
	  $sqlURL = "SELECT * FROM `script_log` WHERE `site_id` = $project_ids";
	$resultURL = mysqli_query($conn, $sqlURL);
	$urlFetch = mysqli_fetch_assoc($resultURL);

	$url = $urlFetch['url'];
	$urlLists = explode(',', $url);

	if($urlFetch['bunny']==1){
		$domain_url = "";
	}

	// print_r(($urlLists));
	// die;

	// echo "<hr>" ;
}

// die("kk") ;




$status_request = 0;
  $sele_sql="SELECT * FROM generate_script_request WHERE  website_id ='".$project_id."' AND manager_id = '".$_SESSION['user_id']. "' ";

$result=$conn -> query($sele_sql);
$request_sent = 0;
if ($result->num_rows > 0) {
	$data = $result->fetch_assoc() ;
	$request_sent = 1;
	$send_from = $data['wait_for_team'];
	// print_r($data['status']);
	$status_request = $data['status'];
}


	$timezone = '<div class="full__col"><label>Time Zone</label><select class="form-control" id="tz" name="country" required>';
	$timezone_2 = '<div class="full__col"><label>Time Zone</label><select class="form-control" id="tz-2" name="country" required>';
	$timezone .= '';
	$timezone_2 .= '';
	$list_countries = getTableData( $conn , " timezones " , " 1 " , "" , 1 ) ;
		foreach ($list_countries as $key => $country_data) {
											
   		   $timezone .= '<option value="'.$country_data["label"].'" >'.$country_data["label"].'</option>';
   		   $timezone_2 .= '<option value="'.$country_data["label"].'" >'.$country_data["label"].'</option>';
	}
											
	$timezone .= '</select></div>';
	$timezone_2 .= '</select></div>';

	$country__code = '<div><label>Country Code</label><select id="country-code" class="form-control" required  name="country" ><option value="">Select</option>';
	$country__code_2 = '<div><label>Country Code</label><select id="country-code-2" class="form-control" required  name="country" ><option value="">Select</option>';

	$country__code .= '';
	$country__code_2 .= '';
	$list_countries_code = getTableData( $conn , " list_countries " , "" , " group by sortname order by name" , 1 ) ;
		foreach ($list_countries_code as $key => $country_data) {

			$selected = "";
			if($country_data['id']==$row['country'])
				{
					$selected = "selected";
				}							
   		   $country__code .= '<option value="+'.$country_data["phonecode"].'" '.$selected.' >'.$country_data["name"].'+'.$country_data["phonecode"].'</option>';
   		   $country__code_2 .= '<option value="+'.$country_data["phonecode"].'" '.$selected.' >'.$country_data["name"].'+'.$country_data["phonecode"].'</option>';
	}
											
	$country__code .= '</select></div>';
	$country__code_2 .= '</select></div>';


$form = '<div class="support__form" ><div class="d__flex" ><span class="request-err d-none">Please Fill All Field.</span>'.$country__code.'<div><label>Contact Number</label><input id="contact" class="form-control" type="number" value="'.$row['phone'].'" required></div></div><div class="full__col"><label>Email</label><input type="email"  class="form-control" id="email"  value="'.$row['email'].'"></div>'.$timezone.'<div class="col-12"><span>Suitable Time For Contact</span><input id="suitable-time" type="date" class="form-control" required><div class="col-12"><div class="row"><div class="col-6">From : <input type="time" id="time-form" required></div><div class="col-6">To : <input type="time"  id="time-to" required></div></div></div></div><button class="mt-3 btn btn-success" id="request_form">Save</button>';


$form2 = '<div class="support__form" ><div class="d__flex" ><span class="request-err d-none">Please Fill All Field.</span>'.$country__code_2.'<div><label>Contact Number</label><input id="contact-2" class="form-control" type="number" required></div></div><div class="full__col"><label>Email</label><input type="email"  class="form-control" id="email-2"></div>'.$timezone_2.'<div class="col-12"><span>Suitable Time For Contact</span><input id="suitable-time-2" type="date" class="form-control" required><div class="col-12"><div class="row"><div class="col-6">From : <input type="time" id="time-form-2" required></div><div class="col-6">To : <input type="time"  id="time-to-2" required></div></div></div></div><button class="mt-3 btn btn-success" id="request_form_2">Save</button>';


?>
<?php require_once("inc/style-and-script.php"); ?>

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

// .tabs-3-head.tabs-head,.tabs-4-head.tabs-head,.tabs-5-head.tabs-head {
//     width: fit-content;
//     padding: 3px 0;
//     font-weight: bold;
//     color: #a2a2a2;
//     cursor: pointer;
//     border: 2px solid lightgray;
//     padding: 6px 12px;
//     border-radius: 4px;
//     margin-top: 15px;
// }

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

.swal2-popup .swal2-html-container .social__links {
	margin-top: 10px;
    display: flex;
    justify-content: center;
    gap: 12px;
	width: 95%;
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
.swal2-html-container{
	margin:1rem 1rem 0;
}
div#swal2-html-container h3 {
    font-size: 26px;
}
button.btn.btn-primary.manualbtnverify+p {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #595555;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>


</head>

<body class="custom-tabel">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

	<div class="loader" style="display:none"></div>


	<?php




	
    function get_dataa($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}
function check_url($url,$code) {

$variableee = get_dataa($url);
// echo $variableee;
    // $code = '';
    	
    
    return (strpos($variableee, $code))? 1:0;
  
	}


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
				<?php require_once("inc/alert-status.php"); ?>
 
				<div class="script_Icontainer">

					<div class="script_i_btn">
						<div class="text-right">

 
						</div>
					</div>

					<div class="container_i_web_spee_h">
						<div class="i_web_spee_h">
						<div class="page-head">
							<h3>Let’s Setup Website Speedy to automatically Boost your website loading speed</h3>
							<p class="desc">Website Speedy automatically fix Core Web Vitals, enhance your website DOM and improves the way your website is interpreted by different browsers, Leading to lighting fast loading speed for all browsers and devices.</p>
							 
						</div>
						<div class="Generate_Request_btn">
							<!-- <button class="alert-pop" >Generate Request</button> -->
						</div>
					 </div>

					 <div class="tabber__wrapper">
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


									<li class="warning <?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'super-overide__bar':''?>">
								
										<a  class="top-step-4" tab="#step4">
											<span class="circle  <?php if($installation >=4){echo "bg-info";}else{echo "bg-light";} ?>">4</span>
											<span class="label">Fourth step</span>
											<span class="step_fill_s"><span></span></span>
										</a>
									</li>


									<li class="warning  <?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'super-overide':''?>" >
								
										<a  class="top-step-5" tab="#step5">
											<span class="circle  <?php if($installation >=5){echo "bg-info";}else{echo "bg-light";} ?>">5</span>
											<span class="label">Fifth step</span>
											<span class="step_fill_s"><span></span></span>
										</a>
									</li>

									<li class="warning  <?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'super-overide':''?>">
								
										<a  class="top-step-6" tab="#step6">
											<span class="circle  <?php if($installation >=6){echo "bg-info";}else{echo "bg-light";} ?>">6</span>
											<span class="label">Sixth step</span>
											
										</a>
									</li>

								</ul>
								<!-- /.Stepers Wrapper -->

							</div>



							<div class="col-md-12" id="page-speed-table" data-project="<?=$website_data['id']?>" data-type="page-speed">


						   <div id="step1" class="form-tab complited">

<!--  Start tab 1 -->
							<div class="tabs-1-head tabs-head" tab="tabs-1">
								<span class="number" >1</span>
								<span class="text">Confirm current page speed</span>
								<span class="icon"><i class="las la-angle-down"></i></span>
							</div>
							 <div class="tabs-1 form-tab-"  style="<?php if($installation >=2){echo "display:none;";}?>">
								<div class="child-tab">
									<div class="row">
										<div class="tab-child active col-4" tab="child-tab-1">google page insights (<a href="https://pagespeed.web.dev/" target="_blank">https://pagespeed.web.dev/</a>)</div>
										<div class="tab-child disabled col-4" tab="child-tab-2">GTMatrix(coming soon)</div>
										<div class="tab-child disabled col-4" tab="child-tab-3">Pingdom(Coming soon)</div>
									</div>
								</div>
								<div class="child-tab-1">
								 <div class="inst_speed">
								 	<!-- Before Optimization Speed : -->
										<div class="page_web_speed">

											<a class="btn btn-primary page-speed1">Mobile</a>

											<a class="btn btn-light core-web-vital2">Desktop</a>

										</div>
						<?php
							$query = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id' and parent_website = 0  ORDER BY id ASC LIMIT 1 ");					
							$pagespeed_data = $query->fetch_assoc();



							$ps_categories_d = unserialize($pagespeed_data["categories"]);
							$audits_d = unserialize($pagespeed_data["audits"]);
		// echo 	$pagespeed_data["id"];

							$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
							$audits_m = unserialize($pagespeed_data["mobile_audits"]);

													// ====
													$ps_performance = round($ps_categories_d["performance"]["score"] * 100, 2);
													$ps_desktop = $ps_performance . "/100";
													$ps_accessibility = round($ps_categories_d["accessibility"]["score"] * 100, 2);
													$ps_best_practices = round($ps_categories_d["best-practices"]["score"] * 100, 2);
													$ps_seo = round($ps_categories_d["seo"]["score"] * 100, 2);
													$ps_pwa = round($ps_categories_d["pwa"]["score"] * 100, 2);
													// ====

													$ps_performance_m = round($ps_categories_m["performance"]["score"] * 100, 2);
													$ps_mobile = $ps_performance_m . "/100";
													$ps_accessibility_m = round($ps_categories_m["accessibility"]["score"] * 100, 2);
													$ps_best_practices_m = round($ps_categories_m["best-practices"]["score"] * 100, 2);
													$ps_seo_m = round($ps_categories_m["seo"]["score"] * 100, 2);
													$ps_pwa_m = round($ps_categories_m["pwa"]["score"] * 100, 2);

													// ==

						?>		
									<table class="table table-script-speed mobile main-table-page-cvw" >
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
																<!-- <th>Website</th> -->
																<!-- <th>Device</th> -->
																<th>Performance</th>
																<th>Accessibility</th>
																<th>Best Practices</th>
																<th>SEO</th>
																<!-- <th>PWA</th> -->
															</tr>
														</thead>
														<tbody>
															<tr>
												
																<!-- <td></td> -->
											   					<!-- <td class="mobile">Mobile</td> -->
											   					<td class="performance_m"><?=$ps_mobile?></td>
											   					<td class="accessibility_m"><?=$ps_accessibility_m?></td>
											   					<td class="best-practices_m"><?=$ps_best_practices_m?></td>
											   					<td class="seo_m"><?=$ps_seo_m?></td>
											   					<!-- <td class="pwa_m"><?=$ps_pwa_m?></td> -->
										   					</tr>
														</tbody>
													</table>
												</td>
												<td class="half-width-box">
													<table>
														<thead>
															<tr>
															<!-- <th>Website</th> -->
															<!-- <th>Device</th> -->
															<th>FCP</th>
															<th>LCP</th>
															<!-- <th>MPF</th> -->
															<th>CLS</th>
															<th>TBT</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<!-- <td></td> -->
																<!-- <td>Mobile</td> -->
																<td class="fcp_m"><?= $audits_m["first-contentful-paint"]["numericValue"] ?></td>
																<td class="lcp_m"><?= $audits_m["largest-contentful-paint"]["numericValue"] ?></td>
																<!-- <td class="mpf_m"><?= $audits_m["max-potential-fid"]["numericValue"] ?></td> -->
																<td class="cls_m"><?= round($audits_m["cumulative-layout-shift"]["numericValue"], 3) ?></td>
																<td class="tbt_m"><?= $audits_m["total-blocking-time"]["numericValue"] ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>

									</table>

									<table class="table main-table-page-cvw desktop table-script-core">
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
																<!-- <th>Website</th> -->
																<!-- <th>Device</th> -->
																<th>Performance</th>
																<th>Accessibility</th>
																<th>Best Practices</th>
																<th>SEO</th>
																<!-- <th>PWA</th> -->
															</tr>
														</thead>
														<tbody>
															<tr>
																<!-- <td rowspan="2"><?=$website_data['website_url']?></td> -->
																<!-- <td class="desktop">Desktop</td> -->
																<td class="performance"><?=$ps_desktop?></td>
																<td class="accessibility"><?=$ps_accessibility?></td>
																<td class="best-practices"><?=$ps_best_practices?></td>
																<td class="seo"><?=$ps_seo?></td>
																<!-- <td class="pwa"><?=$ps_pwa?></td> -->
															</tr>
														</tbody>
													</table>
												</td>
												<td class="half-width-box">
													<table>
														<thead>
															<tr>
																<!-- <th>Website</th> -->
																<!-- <th>Device</th> -->
																<th>FCP</th>
																<th>LCP</th>
																<th>CLS</th>
																<th>TBT</th>
																<!-- <th>PWA</th> -->
															</tr>
														</thead>
														<tbody>
															<tr>
																<!-- <td  rowspan="2"><?=$website_data['website_url']?></td> -->
																<!-- <td>Desktop</td> -->
																<td class="fcp"><?= $audits_d["first-contentful-paint"]["numericValue"] ?></td>
																<td class="lcp"><?= $audits_d["largest-contentful-paint"]["numericValue"] ?></td>
																<!-- <td class="mpf"><?= $audits_d["max-potential-fid"]["numericValue"] ?></td> -->
																<td class="cls"><?= round($audits_d["cumulative-layout-shift"]["numericValue"], 3) ?></td>
																<td class="tbt"><?= $audits_d["total-blocking-time"]["numericValue"] ?></td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								
									</div>
								</div>  <!-- end child 1 -->

 								<div class="child-tab-2">
 								</div>
 								<div class="child-tab-3">
 								</div>


								<p>To verify this data, you can visit <a href="https://pagespeed.web.dev/" target="_blank" >https://pagespeed.web.dev/</a>. There may be slight variations due to multiple factors, as explained by Google. Some of the significant factors include: Antivirus software, Browser extensions that inject JavaScript and alter network requests, Network firewalls, Server load, and DNS - Internet traffic routing changes. For more detailed information provided by Google, you can <a href="https://developer.chrome.com/docs/lighthouse/performance/performance-scoring/#fluctuations" target="_blank" >click here</a>.</p>
								
								<div class="action">
									 
									<button class="continue-to-step-2">Confirm and continue to step 2</button>

									 
										<button class="reanalyzeold" <?php if($installation >=4){}else{echo "style='display:none;'";}?> >Reanalyze</button>
									 
									
									<button  <?php if($installation <4){}else{echo "style='display:none;'";}?>  class="step-2-reanalyse reanalyze-btn-"  data-website_name="<?=$website_data["website_url"]?>"  data-table_id="old" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$website_data["website_url"]?>" data-ps_desktop="-" data-additional="0"  >Reanalyze</button>
								 
								</div>

								</div>
							</div>

			<!--  Step 1 end -->
			<!--  Step 2 Start -->



						   <div id="step2" class=" <?php if($installation >=2){echo "complited";}else{echo " d-none ";}?> form-tab">
							<div class="tabs-2-head tabs-head" tab="tabs-2">
								<span class="number" >2</span>
								<span class="text"> Generate loading Speed Enhancement script</span>
								<span class="icon"><i class="las la-angle-down"></i></span>
							</div>
							 <div class="tabs-2 form-tab-" style="<?php if($installation != 2 ){echo "display:none";}?>">

									<div class="verification_cover"><button type="button" class="generate_btn" urls="<?=$url?>"  data-id="<?=$website_data['id']?>" data-vari="<?=$website_data['website_url']?>" > Generate Script and go to step 3 </button>
							 </div>
							</div>
						   </div>


			<!--  Step 2 end -->
			<!--  Step 3 Start -->							

						   <div id="step3" class="form-tab  <?php if($installation >=3){echo "complited";}else{echo " d-none ";}?>">
							<div class="tabs-3-head tabs-head" tab="tabs-3">
								<span class="number" >3</span>
								<span class="text" >Install loading Speed Enhancement script </span>
								<span class="icon"><i class="las la-angle-down"></i></span>
							</div>
							 <div class="tabs-3 form-tab-"  style="<?php if($installation != 3){echo "display:none";}?>">

										 <div class="script_varify_s">
								         <div class="head_copy_copied" title="Copy">
										 <h5>1.a) Add this script code, before closing the '&lt;/head&gt;' tag in your website header.</h5>		
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
$script_url=[];  foreach ($urlLists as $urlList) { 
array_push($script_url, $urlList);?>
<code>&lt;script <?php 	echo "type='text/javascript'"; ?> src="<?php echo $domain_url . $urlList ?>"&gt;&lt;/script&gt;</code>
<br>
<?php $count++;
}
 ?>
<code>&lt;!-- This Script is the exclusive property of Website Speedy, Copyright © 2023. All rights reserved. --&gt;</code>
</div>

									<p class="small">Please click the button below once you have added the script to your website</p>

									 

									<div class="verification_cover"><button type="button" class="verification_btn btn btn-primary" urls="<?=$url?>"  data-id="<?=$website_data['id']?>" data-vari="<?=$website_data['website_url']?>" >Verify and go to step 4</button>
										
										<button class="p-instruction">Platform wise instruction</button>

										<button class="i-need-help">I need help to install the script</button>
									
									</div>


								</div>

							 </div>
							</div>
			<!--  Step 3 end -->
			<!--  Step 4 Start -->


 

						   <div id="step4" class="form-tab  <?php if($installation >=4){echo "complited";}else{echo " d-none ";}?>">
							<div class="tabs-4-head tabs-head" tab="tabs-4">
								<span class="number" >4</span>
								<span class="text">Analyse Updated Speed</span>
								<span class="icon"><i class="las la-angle-down"></i></span>
							</div>
							 <div class="tabs-4 form-tab-"  style="<?php if($installation != 4 ){echo "display:none";}?>">
							 		<p>Congratulations, You have successfully implemented the script for Automatic speed optimisation, our script works instantly, its still a good idea to give it 2 minutes to work efficiently , You can analyse updated speed score when the timer reaches 00:00 </p>
							 		
							 			<div class="countdown-container">
							 								<span id="countdown-" class="">00:00</span>	
							 			</div>					
							 		<div class="get-updated-script" >
							 				
											<button type="button" name="submit__btn"  class="new-analyze btn btn-primary reanalyze-btn-" style="display: :none;" tab="#step4" data-website_name="<?=$website_data["website_url"]?>" data-website_id="<?=$website_data["id"]?>" data-website_url="<?=$website_data["website_url"]?>"data-table_id="new" >Analyse Updated Speed</button>

							 		</div>	




								<div class="inst_speed_new-container" style="<?php if($new_speed == 0 ){echo "display:none";}?>" >
								<span class="text" >Congrats you can see the updated speed score below - </span>
								<div class="mobile-desktop-tabber">
									<div class="page_web_speed">
										<a class="btn btn-primary page-speed1">Mobile </a>
										<a class="btn btn-light core-web-vital2">Desktop</a>
									</div>
									<div class="website_url">
										<div class="website_heading">Website:</div>
										<div class="website_value"><?=$website_data['website_url']?></div>
									</div>
								</div>
								 <div class="inst_speed">
								 		<span class="heading" >Before script installation by website speedy.</span>
										<!-- <div class="page_web_speed">

											<a class="btn btn-primary page-speed1">Page Speed</a>

											<a class="btn btn-light core-web-vital2">Core Web Vital</a>

										</div> -->

										<div class="page-cvw-box">
											<!-- Desktop Data Starts Here -->
												<table class="table main-table-page-cvw desktop table-script-speed">
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
															<!-- <th>Website</th> -->
															<!-- <th>Device</th> -->
															<th>Performance</th>
															<th>Accessibility</th>
															<th>Best Practices</th>
															<th>SEO</th>
															<!-- <th>PWA</th> -->
															</tr>
														</thead>
														<tbody>
															<tr>
																<!-- <td></td> -->
																<!-- <td class="mobile">Mobile</td> -->
																<td class="performance_m"><?=$ps_mobile?></td>
																<td class="accessibility_m"><?=$ps_accessibility_m?></td>
																<td class="best-practices_m"><?=$ps_best_practices_m?></td>
																<td class="seo_m"><?=$ps_seo_m?></td>
																<!-- <td class="pwa_m"><?=$ps_pwa_m?></td> -->
															</tr>
														</tbody>
														</table>
													</td>
													<td class="half-width-box">
														<table>
														<thead>
															<tr>
															<!-- <th>Website</th> -->
															<!-- <th>Device</th> -->
															<th>FCP</th>
															<th>LCP</th>
															<!-- <th>MPF</th> -->
															<th>CLS</th>
															<th>TBT</th>
															</tr>
														</thead>
														<tbody>
															<tr>
															<!-- <td></td> -->
															<!-- <td>Mobile</td> -->
															<td class="fcp_m">
																<?= $audits_m["first-contentful-paint"]["numericValue"] ?>
															</td>
															<td class="lcp_m">
																<?= $audits_m["largest-contentful-paint"]["numericValue"] ?>
															</td>
															<!-- <td class="mpf_m"><?= $audits_m["max-potential-fid"]["numericValue"] ?></td> -->
															<td class="cls_m">
																<?= round($audits_m["cumulative-layout-shift"]["numericValue"], 3) ?>
															</td>
															<td class="tbt_m">
																<?= $audits_m["total-blocking-time"]["numericValue"] ?>
															</td>
															</tr>
														</tbody>
														</table>
													</td>
													</tr>
												</tbody>
												</table>
											<!-- Desktop Data Ends Here -->

											<!-- Mobile Data Starts Here -->

												<table class="table main-table-page-cvw mobile table-script-core">
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
															<!-- <th>Website</th> -->
															<!-- <th>Device</th> -->
															<th>Performance</th>
															<th>Accessibility</th>
															<th>Best Practices</th>
															<th>SEO</th>
															<!-- <th>PWA</th> -->
															</tr>
														</thead>
														<tbody>
															<tr>
															<!-- <td rowspan="2"><?=$website_data['website_url']?></td> -->

															<!-- <td class="desktop">Desktop</td> -->
															<td class="performance"><?=$ps_desktop?></td>
															<td class="accessibility"><?=$ps_accessibility?></td>
															<td class="best-practices"><?=$ps_best_practices?></td>
															<td class="seo"><?=$ps_seo?></td>
															<!-- <td class="pwa"><?=$ps_pwa?></td> -->
															</tr>
														</tbody>
														</table>
													</td>
													<td class="half-width-box">
														<table>
														<thead>
															<tr>
															<!-- <th>Website</th> -->
															<!-- <th>Device</th> -->
															<th>FCP</th>
															<th>LCP</th>
															<!-- <th>MPF</th> -->
															<th>CLS</th>
															<th>TBT</th>
															</tr>
														</thead>
														<tbody>
															<tr>
															<!-- <td  rowspan="2"><?=$website_data['website_url']?></td> -->
															<!-- <td>Desktop</td> -->
															<td class="fcp">
																<?= $audits_d["first-contentful-paint"]["numericValue"] ?>
															</td>
															<td class="lcp">
																<?= $audits_d["largest-contentful-paint"]["numericValue"] ?>
															</td>
															<!-- <td class="mpf"><?= $audits_d["max-potential-fid"]["numericValue"] ?></td> -->
															<td class="cls">
																<?= round($audits_d["cumulative-layout-shift"]["numericValue"], 3) ?>
															</td>
															<td class="tbt">
																<?= $audits_d["total-blocking-time"]["numericValue"] ?>
															</td>
															</tr>
														</tbody>
														</table>
													</td>
													</tr>
												</tbody>
												</table>
											<!-- Mobile Data Ends Here -->

										</div>										
									</div>

								 <div class="inst_speed_new">
								 	 
								 	 		<span class="heading" >After script installation by website speedy.</span>
											<!-- <div class="page_web_speed">

												<a class="btn btn-primary page-speed1">Page Speed</a>

												<a class="btn btn-light core-web-vital2">Core Web Vital</a>

											</div> -->

												<?php

													$query = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id' ORDER BY id DESC LIMIT 1 ");								
													$pagespeed_data = $query->fetch_assoc();
													$ps_categories_d = unserialize($pagespeed_data["categories"]);
													$audits_d = unserialize($pagespeed_data["audits"]);

													$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
													$audits_m = unserialize($pagespeed_data["mobile_audits"]);

													// ====
													$ps_performance = round($ps_categories_d["performance"]["score"] * 100, 2);
													$ps_desktop = $ps_performance . "/100";
													$ps_accessibility = round($ps_categories_d["accessibility"]["score"] * 100, 2);
													$ps_best_practices = round($ps_categories_d["best-practices"]["score"] * 100, 2);
													$ps_seo = round($ps_categories_d["seo"]["score"] * 100, 2);
													$ps_pwa = round($ps_categories_d["pwa"]["score"] * 100, 2);
													// ====

													$ps_performance_m = round($ps_categories_m["performance"]["score"] * 100, 2);
													$ps_mobile = $ps_performance_m . "/100";
													$ps_accessibility_m = round($ps_categories_m["accessibility"]["score"] * 100, 2);
													$ps_best_practices_m = round($ps_categories_m["best-practices"]["score"] * 100, 2);
													$ps_seo_m = round($ps_categories_m["seo"]["score"] * 100, 2);
													$ps_pwa_m = round($ps_categories_m["pwa"]["score"] * 100, 2);
												?>

											<div class="page-cvw-box">
												<!-- Desktop Data Starts Here -->
													<table class="table main-table-page-cvw desktop table-script-core">
													<thead>
														<tr>
														<th>Page Speed</th>
														<th>Core Web Vital</th>
														</tr>
													</thead>
													<tbody>
														<tr>
														<td>
															<table>
																<thead>
																<tr>
																	<!-- <th>Website</th> -->
																	<!-- <th>Device</th> -->
																	<th>Performance</th>
																	<th>Accessibility</th>
																	<th>Best Practices</th>
																	<th>SEO</th>
																	<!-- <th>PWA</th> -->
																</tr>
																</thead>
																<tbody>
																<tr>
																	<!-- <td rowspan="2" ><?=$website_data['website_url']?></td> -->
																	<!-- <td >Desktop</td> -->
																	<td class="performance"><?=$ps_desktop?></td>
																	<td class="accessibility"><?=$ps_accessibility?></td>
																	<td class="best-practices"><?=$ps_best_practices?></td>
																	<td class="seo"><?=$ps_seo?></td>
																	<!-- <td class="pwa"><?=$ps_pwa?></td> -->
																</tr>
																</tbody>
															</table>
														</td>
														<td>
															<table>
																<thead>
																<tr>
																	<!-- <th>Website</th> -->
																	<!-- <th>Device</th> -->
																	<th>FCP</th>
																	<th>LCP</th>
																	<!-- <th>MPF</th> -->
																	<th>CLS</th>
																	<th>TBT</th>
																</tr>
																</thead>
																<tbody>
																<tr>
																	<!-- <td rowspan="2"><?=$website_data['website_url']?></td> -->
																	<!-- <td>Desktop</td> -->
																	<td class="fcp">
																	<?= $audits_d["first-contentful-paint"]["numericValue"] ?>
																	</td>
																	<td class="lcp">
																	<?= $audits_d["largest-contentful-paint"]["numericValue"] ?>
																	</td>
																	<!-- <td class="mpf"><?= $audits_d["max-potential-fid"]["numericValue"] ?></td> -->
																	<td class="cls">
																	<?= round($audits_d["cumulative-layout-shift"]["numericValue"], 3) ?>
																	</td>
																	<td class="tbt">
																	<?= $audits_d["total-blocking-time"]["numericValue"] ?>
																	</td>
																</tr>
																</tbody>
															</table>
														</td>
														</tr>
													</tbody>
													</table>
												<!-- Desktop Data Ends Here -->
												<!-- Mobile Data Starts Here -->
												<table class="table main-table-page-cvw mobile table-script-speed">
												<thead>
													<tr>
													<th>Page Speed</th>
													<th>Core Web Vital</th>
													</tr>
												</thead>
												<tbody>
													<tr>
													<td>
														<table>
															<thead>
															<tr>
																<!-- <th>Website</th> -->
																<!-- <th>Device</th> -->
																<th>Performance</th>
																<th>Accessibility</th>
																<th>Best Practices</th>
																<th>SEO</th>
																<!-- <th>PWA</th> -->
															</tr>
															</thead>
															<tbody>
															<tr>
																<!-- <td></td> -->
																<!-- <td >Mobile</td> -->
																<td class="performance_m"><?=$ps_mobile?></td>
																<td class="accessibility_m"><?=$ps_accessibility_m?></td>
																<td class="best-practices_m"><?=$ps_best_practices_m?></td>
																<td class="seo_m"><?=$ps_seo_m?></td>
																<!-- <td class="pwa_m"><?=$ps_pwa_m?></td> -->
															</tr>
															</tbody>
														</table>
													</td>
													<td>
														<table>
															<thead>
															<tr>
																<!-- <th>Website</th> -->
																<!-- <th>Device</th> -->
																<th>FCP</th>
																<th>LCP</th>
																<!-- <th>MPF</th> -->
																<th>CLS</th>
																<th>TBT</th>
															</tr>
															</thead>
															<tbody>
															<tr>
																<!-- <td></td> -->
																<!-- <td>Mobile</td> -->
																<td class="fcp_m">
																<?= $audits_m["first-contentful-paint"]["numericValue"] ?>
																</td>
																<td class="lcp_m">
																<?= $audits_m["largest-contentful-paint"]["numericValue"] ?>
																</td>
																<!-- <td class="mpf_m"><?= $audits_m["max-potential-fid"]["numericValue"] ?></td> -->
																<td class="cls_m">
																<?= round($audits_m["cumulative-layout-shift"]["numericValue"], 3) ?>
																</td>
																<td class="tbt_m">
																<?= $audits_m["total-blocking-time"]["numericValue"] ?>
																</td>
															</tr>
															</tbody>
														</table>
													</td>
													</tr>
												</tbody>
												</table>
												<!-- Mobile Data Ends Here -->
											</div>										
								 </div>										
									

									<p>To verify this data, you can visit <a href="https://pagespeed.web.dev/" target="_blank" >https://pagespeed.web.dev/</a>. There may be slight variations due to multiple factors, as explained by Google. Some of the significant factors include: Antivirus software, Browser extensions that inject JavaScript and alter network requests, Network firewalls, Server load, and DNS - Internet traffic routing changes. For more detailed information provided by Google, you can <a href="https://developer.chrome.com/docs/lighthouse/performance/performance-scoring/#fluctuations" target="_blank" >click here</a>.</p>



						


						


								<button class="goto-5 " data-satisfy="1" >Satisfied with improved speed<?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'':'- <br>Go to Step 5'?> </button>

								<br>
 								<?php if(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' ||
 								strtolower($website_data['platform']) == 'squarespace') { ?>

								<span id="trusted"  style="<?php if($satisfy !=1){echo "display:none";}?> ">Click the button below to review our platform on Trust Pilot<br><br><a href="https://www.trustpilot.com/review/websitespeedy.com" target="_blank"><button style="background-image:url('https://shopifyspeedy.com/img/trustpilot.png');">Trust Pilot</button></a></span>

							<?php } ?>
						
								
								<p class="step_5_goto_text " <?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'style="display:none;"':''?>>Most of our users see a huge speed difference , if you do not see a satisfying speed score yet don’t worry, Go to Step 5 to further improve your website loading speed.</p>

								<button <?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'style="display:none;"':''?> class="goto-5 "  data-satisfy="0">Not Satisfied with improved speed - <br>Go to Step 5</button>

								<!-- <button class="goto-5">Go to Step 5</button> -->
									</div>
							

							 </div>
							</div>

	<!--  Step 4 end -->
	<!--  Step 5 start -->
 
						   <div id="step5" class="form-tab <?php if($installation >=5){echo "complited";}else{echo " d-none ";}?> <?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'super-overide':''?> ">
							<div class="tabs-5-head tabs-head" tab="tabs-5">
								<span class="number" >5</span>
								<span class="text">Add parameters to improve speed further</span>
								<span class="icon"><i class="las la-angle-down"></i></span>
							</div>
							 <div class="tabs-5 form-tab-"  style="<?php if($installation !=5){echo "display:none";}?>">
								  <div class="request">	
								 	<p>1- Request Website Speedy team to update the parameters </p>
								 	<button  class="<?php if($request_sent==0){echo "alert-pop";}else{echo "request_sent";}?> tabs-5-request btn btn-primary">Generate Request (free)</button>
								 	<!-- <button  class="request_sent d-none"> btn btn-primary">Generate Request (free)</button> -->

								  </div>
								 <div class="myself">
								 	<p>2- I want to do this myself(coming soon) </p>
								 </div>	

								 <div class="faq">
								 	<label>FAQs</label>
							 		<div>
								 		<label>1. What parameters need to be added?</label>
								 		<p class="no__margin">While website speedy Automatically improves loading time significantly. However, in some cases, it needs some support depending on your website's platform, structure and code. The following parameters will be added to your javascript & CSS links, it  will be updated by adding the “preload”, “pre-connect” or “dns-prefetch” depending on the requirement. For external scripts such as GTM, Analytics and other we will add “data-src” will be added instead of “src” or “data-href” instead of “href” to help the AI identify and load the parameters correctly. Below is a before and after look of these codes.</p>
										<div class="code" >
										<b style="font-weight:500;">Code before parameters update:</b><br>
										<code>
										&lt;link href="https://fonts.googleapis.com/css2"&gt;
										</code><br>
										<code>
										&lt;script async src="https://www.googletagmanager.com/gtag/js?id=AW-9289"&gt;&lt;/script&gt;
										</code><br>
										<code>
										&lt;link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font.min.css"&gt;
										</code>
										</div>
										<div class="code" >
										
										<b style="font-weight:500;">Code After parameters update:</b><br>
										<code>
										&lt;link rel="preconnect" href="https://fonts.googleapis.com/css2"&gt;
										</code><br>
										<code>
										&lt;script async data-src="https://www.googletagmanager.com/gtag/js?id=AW-9289">&gt;&lt;/script&gt;
										</code><br>
										<code>
										&lt;link rel="stylesheet" data-href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font.min.css"&gt;
										</code>
										</div>
										</div>
								 	

								 		<div class="margin__top__only" >
								 		<label>2. How much page speed can I expect after updating these parameters?</label>
								 		<p>After completing step 5, you can achieve Google PageSpeed scores of 70 or higher on mobile and 90 or higher on desktop. The exact amount of speed improvement can vary depending on a variety of factors, including the size and complexity of your website, as well as your hosting environment. You can generally expect to see a significant improvement in your Google PageSpeed scores. For more information, click here. (<a href="https://websitespeedy.com/speed-guarantee.php" target="_blank">https://websitespeedy.com/speed-guarantee.php</a>)</p>
								 	</div>


								 	<div class="margin__top__only" >
								 		<label>3. Will you take a backup?</label>
								 		<p>Yes, we will take a backup of your website. Our team will work on a duplicate theme if your platform allows it and we will connect with you before making changes live. This way, we can ensure that your website is backed up and that any changes we make are approved by you before going live.

										</p>
								 	</div>
								</div>	
								 </div>	
								 										
							 </div>


	<!--  Step 5 end -->
	<!--  Step 6 start -->

					<div id="step6" class="form-tab <?php if($installation >=6){echo "complited";}else{echo " d-none ";}?> <?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'super-overide':''?>">
							<div class="tabs-6-head tabs-head" tab="tabs-6">
								<span class="number" >6</span>
								<span class="text"> Parameter add request Status</span>
						
						<span class="icon <?php if($installation >=6){echo "rotate";}?>"><i class="las la-angle-down"></i></span>
							</div>
							 <div class="tabs-6 form-tab-"  style="<?php if($installation !=6){echo "display:none";}?>">
								  <div class="request">
								  		<div class="status-icon <?php if($status_request == 0){ echo "pending";}else {echo "completed";}  ?>">
								  			<img class="pending__img" src="../adminpannel/img/status.png"/>	
								  			<img class="completed__img" src="../adminpannel/img/status-green.png"/>	
								  		</div>
								  		<label class="status"> <span id="status_request"><?php if($status_request == 0){ echo "Pending";}else {echo "Completed";}  ?></span></label>
								 	 	<!-- <p>Your request has been sent to Website Speedy team.</p> -->
								 	 	<p>Our team will get in touch with you shortly to get access for updating parameters, please do check your email and spam folder to make sure you do not miss it.</p>


<?php if($status_request == 3) { ?>


										
						<div class="inst_speed_new-container">
							<span class="text" >Congratulations your website is loading faster now - </span>
								 <div class="inst_speed">
								 		<span class="heading">Before installing Website Speedy script</span>

 					<div class="child-tab-1">
								 <div class="inst_speed">
								 	<!-- Before Optimization Speed : -->
										<div class="page_web_speed">

											<a class="btn btn-primary page-speed1">Page Speed</a>

											<a class="btn btn-light core-web-vital2">Core Web Vital</a>

										</div>
						<?php
							$query = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id'  and parent_website = 0  ORDER BY id ASC LIMIT 1 ");					
							$pagespeed_data = $query->fetch_assoc();


							
							$ps_categories_d = unserialize($pagespeed_data["categories"]);
							$audits_d = unserialize($pagespeed_data["audits"]);
		// echo 	$pagespeed_data["id"];

							$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
							$audits_m = unserialize($pagespeed_data["mobile_audits"]);

													// ====
													$ps_performance = round($ps_categories_d["performance"]["score"] * 100, 2);
													$ps_desktop = $ps_performance . "/100";
													$ps_accessibility = round($ps_categories_d["accessibility"]["score"] * 100, 2);
													$ps_best_practices = round($ps_categories_d["best-practices"]["score"] * 100, 2);
													$ps_seo = round($ps_categories_d["seo"]["score"] * 100, 2);
													$ps_pwa = round($ps_categories_d["pwa"]["score"] * 100, 2);
													// ====

													$ps_performance_m = round($ps_categories_m["performance"]["score"] * 100, 2);
													$ps_mobile = $ps_performance_m . "/100";
													$ps_accessibility_m = round($ps_categories_m["accessibility"]["score"] * 100, 2);
													$ps_best_practices_m = round($ps_categories_m["best-practices"]["score"] * 100, 2);
													$ps_seo_m = round($ps_categories_m["seo"]["score"] * 100, 2);
													$ps_pwa_m = round($ps_categories_m["pwa"]["score"] * 100, 2);

													// ==

						?>		

 									<table class="table table-script-speed" >
										<thead>
											<tr>
												
												<th>Website</th>
												<th>Device</th>
												<th>Performance</th>
												<th>Accessibility</th>
												<th>Best Practices</th>
												<th>SEO</th>
												<!-- <th>PWA</th> -->
												
											
											</tr>
										</thead>
										<tbody>
											<tr>
												<td rowspan="2"><?=$website_data['website_url']?></td>

												<td class="desktop">Desktop</td>
												<td class="performance"><?=$ps_desktop?></td>
												<td class="accessibility"><?=$ps_accessibility?></td>
												<td class="best-practices"><?=$ps_best_practices?></td>
												<td class="seo"><?=$ps_seo?></td>
												<!-- <td class="pwa"><?=$ps_pwa?></td> -->
											</tr>

											<tr>
												
												 <!-- <td></td> -->
												<td class="mobile">Mobile</td>
												<td class="performance_m"><?=$ps_mobile?></td>
												<td class="accessibility_m"><?=$ps_accessibility_m?></td>
												<td class="best-practices_m"><?=$ps_best_practices_m?></td>
												<td class="seo_m"><?=$ps_seo_m?></td>
												<!-- <td class="pwa_m"><?=$ps_pwa_m?></td> -->
											</tr>

										</tbody>
										</table>

										<table class="table table-script-core">
										<thead>
											<tr>
												
												<th>Website</th>
												<th>Device</th>
												<th>FCP</th>
												<th>LCP</th>
												<!-- <th>MPF</th> -->
												<th>CLS</th>
												<th>TBT</th>
												
											
											</tr>
										</thead>
										<tbody>
											<tr>
											<td  rowspan="2"><?=$website_data['website_url']?></td>

												<td>Desktop</td>
												<td class="fcp"><?= $audits_d["first-contentful-paint"]["numericValue"] ?></td>
												<td class="lcp"><?= $audits_d["largest-contentful-paint"]["numericValue"] ?></td>
												<!-- <td class="mpf"><?= $audits_d["max-potential-fid"]["numericValue"] ?></td> -->
												<td class="cls"><?= round($audits_d["cumulative-layout-shift"]["numericValue"], 3) ?></td>
												<td class="tbt"><?= $audits_d["total-blocking-time"]["numericValue"] ?></td>

											</tr>

											<tr>
											<!-- <td></td> -->
												<td>Mobile</td>
												<td class="fcp_m"><?= $audits_m["first-contentful-paint"]["numericValue"] ?></td>
												<td class="lcp_m"><?= $audits_m["largest-contentful-paint"]["numericValue"] ?></td>
												<!-- <td class="mpf_m"><?= $audits_m["max-potential-fid"]["numericValue"] ?></td> -->
												<td class="cls_m"><?= round($audits_m["cumulative-layout-shift"]["numericValue"], 3) ?></td>
												<td class="tbt_m"><?= $audits_m["total-blocking-time"]["numericValue"] ?></td>
											</tr>


											</tbody>
											</table>										
									</div>
								</div>  <!-- end child 1 -->

								 <div class="inst_speed_new">
								 	 
								 	 <span class="heading" >After installing Website Speedy script</span>
										<div class="page_web_speed">

											<a class="btn btn-primary page-speed1">Page Speed</a>

											<a class="btn btn-light core-web-vital2">Core Web Vital</a>

										</div>

<?php

							$query = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id'  and parent_website = 0  ORDER BY id ASC LIMIT 1,1 ");								
							$pagespeed_data = $query->fetch_assoc();
							$ps_categories_d = unserialize($pagespeed_data["categories"]);
							$audits_d = unserialize($pagespeed_data["audits"]);


							$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
							$audits_m = unserialize($pagespeed_data["mobile_audits"]);

													// ====
													$ps_performance = round($ps_categories_d["performance"]["score"] * 100, 2);
													$ps_desktop = $ps_performance . "/100";
													$ps_accessibility = round($ps_categories_d["accessibility"]["score"] * 100, 2);
													$ps_best_practices = round($ps_categories_d["best-practices"]["score"] * 100, 2);
													$ps_seo = round($ps_categories_d["seo"]["score"] * 100, 2);
													$ps_pwa = round($ps_categories_d["pwa"]["score"] * 100, 2);
													// ====

													$ps_performance_m = round($ps_categories_m["performance"]["score"] * 100, 2);
													$ps_mobile = $ps_performance_m . "/100";
													$ps_accessibility_m = round($ps_categories_m["accessibility"]["score"] * 100, 2);
													$ps_best_practices_m = round($ps_categories_m["best-practices"]["score"] * 100, 2);
													$ps_seo_m = round($ps_categories_m["seo"]["score"] * 100, 2);
													$ps_pwa_m = round($ps_categories_m["pwa"]["score"] * 100, 2);
?>

 									<table class="table table-script-speed" >
										<thead>
											<tr>
												
												<th>Website</th>
												<th>Device</th>
												<th>Performance</th>
												<th>Accessibility</th>
												<th>Best Practices</th>
												<th>SEO</th>
												<!-- <th>PWA</th> -->
												
											
											</tr>
										</thead>
										<tbody>

											<tr>
												<td rowspan="2" ><?=$website_data['website_url']?></td>

												<td >Desktop</td>
												<td class="performance"><?=$ps_desktop?></td>
												<td class="accessibility"><?=$ps_accessibility?></td>
												<td class="best-practices"><?=$ps_best_practices?></td>
												<td class="seo"><?=$ps_seo?></td>
												<!-- <td class="pwa"><?=$ps_pwa?></td> -->
											</tr>

											<tr>
												
												 <!-- <td></td> -->
												<td >Mobile</td>
												<td class="performance_m"><?=$ps_mobile?></td>
												<td class="accessibility_m"><?=$ps_accessibility_m?></td>
												<td class="best-practices_m"><?=$ps_best_practices_m?></td>
												<td class="seo_m"><?=$ps_seo_m?></td>
												<!-- <td class="pwa_m"><?=$ps_pwa_m?></td> -->
											</tr>


										</tbody>
										</table>

										<table class="table table-script-core">
										<thead>
											<tr>
												
												<th>Website</th>
												<th>Device</th>
												<th>FCP</th>
												<th>LCP</th>
												<!-- <th>MPF</th> -->
												<th>CLS</th>
												<th>TBT</th>
												
											
											</tr>
										</thead>
										<tbody>
											<tr>
											<td rowspan="2"><?=$website_data['website_url']?></td>

												<td>Desktop</td>
												<td class="fcp"><?= $audits_d["first-contentful-paint"]["numericValue"] ?></td>
												<td class="lcp"><?= $audits_d["largest-contentful-paint"]["numericValue"] ?></td>
												<!-- <td class="mpf"><?= $audits_d["max-potential-fid"]["numericValue"] ?></td> -->
												<td class="cls"><?= round($audits_d["cumulative-layout-shift"]["numericValue"], 3) ?></td>
												<td class="tbt"><?= $audits_d["total-blocking-time"]["numericValue"] ?></td>


											</tr>

											<tr>
											<!-- <td></td> -->
												<td>Mobile</td>
												<td class="fcp_m"><?= $audits_m["first-contentful-paint"]["numericValue"] ?></td>
												<td class="lcp_m"><?= $audits_m["largest-contentful-paint"]["numericValue"] ?></td>
												<!-- <td class="mpf_m"><?= $audits_m["max-potential-fid"]["numericValue"] ?></td> -->
												<td class="cls_m"><?= round($audits_m["cumulative-layout-shift"]["numericValue"], 3) ?></td>
												<td class="tbt_m"><?= $audits_m["total-blocking-time"]["numericValue"] ?></td>
											</tr>


											</tbody>
											</table>										
									</div>	


<!-- After Paramiter Add -->
 

								 <div class="inst_speed_new">
								 	 
								 	 <span class="heading" >After Website speedy parameters update</span>
										<div class="page_web_speed">

											<a class="btn btn-primary page-speed1">Page Speed</a>

											<a class="btn btn-light core-web-vital2">Core Web Vital</a>

										</div>

<?php

							$queryx = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id'  and parent_website = 0  and track_source = 'paramiter_update' ORDER BY id desc LIMIT 0,1");
							if ($queryx->num_rows > 0) {								
							$pagespeed_datax = $queryx->fetch_assoc();
							$ps_categories_d = unserialize($pagespeed_datax["categories"]);
							$audits_d = unserialize($pagespeed_datax["audits"]);


							$ps_categories_m = unserialize($pagespeed_datax["mobile_categories"]);
							$audits_m = unserialize($pagespeed_datax["mobile_audits"]);

													// ====
													$ps_performance = round($ps_categories_d["performance"]["score"] * 100, 2);
													$ps_desktop = $ps_performance . "/100";
													$ps_accessibility = round($ps_categories_d["accessibility"]["score"] * 100, 2);
													$ps_best_practices = round($ps_categories_d["best-practices"]["score"] * 100, 2);
													$ps_seo = round($ps_categories_d["seo"]["score"] * 100, 2);
													$ps_pwa = round($ps_categories_d["pwa"]["score"] * 100, 2);
													// ====

													$ps_performance_m = round($ps_categories_m["performance"]["score"] * 100, 2);
													$ps_mobile = $ps_performance_m . "/100";
													$ps_accessibility_m = round($ps_categories_m["accessibility"]["score"] * 100, 2);
													$ps_best_practices_m = round($ps_categories_m["best-practices"]["score"] * 100, 2);
													$ps_seo_m = round($ps_categories_m["seo"]["score"] * 100, 2);
													$ps_pwa_m = round($ps_categories_m["pwa"]["score"] * 100, 2);
?>

 									<table class="table table-script-speed" >
										<thead>
											<tr>
												
												<th>Website</th>
												<th>Device</th>
												<th>Performance</th>
												<th>Accessibility</th>
												<th>Best Practices</th>
												<th>SEO</th>
												<!-- <th>PWA</th> -->
												
											
											</tr>
										</thead>
										<tbody>

											<tr>
												<td rowspan="2" ><?=$website_data['website_url']?></td>

												<td >Desktop</td>
												<td class="performance"><?=$ps_desktop?></td>
												<td class="accessibility"><?=$ps_accessibility?></td>
												<td class="best-practices"><?=$ps_best_practices?></td>
												<td class="seo"><?=$ps_seo?></td>
												<!-- <td class="pwa"><?=$ps_pwa?></td> -->
											</tr>

											<tr>
												
												 <!-- <td></td> -->
												<td >Mobile</td>
												<td class="performance_m"><?=$ps_mobile?></td>
												<td class="accessibility_m"><?=$ps_accessibility_m?></td>
												<td class="best-practices_m"><?=$ps_best_practices_m?></td>
												<td class="seo_m"><?=$ps_seo_m?></td>
												<!-- <td class="pwa_m"><?=$ps_pwa_m?></td> -->
											</tr>


										</tbody>
										</table>

										<table class="table table-script-core">
										<thead>
											<tr>
												
												<th>Website</th>
												<th>Device</th>
												<th>FCP</th>
												<th>LCP</th>
												<!-- <th>MPF</th> -->
												<th>CLS</th>
												<th>TBT</th>
												
											
											</tr>
										</thead>
										<tbody>
											<tr>
											<td rowspan="2"><?=$website_data['website_url']?></td>

												<td>Desktop</td>
												<td class="fcp"><?= $audits_d["first-contentful-paint"]["numericValue"] ?></td>
												<td class="lcp"><?= $audits_d["largest-contentful-paint"]["numericValue"] ?></td>
												<!-- <td class="mpf"><?= $audits_d["max-potential-fid"]["numericValue"] ?></td> -->
												<td class="cls"><?= round($audits_d["cumulative-layout-shift"]["numericValue"], 3) ?></td>
												<td class="tbt"><?= $audits_d["total-blocking-time"]["numericValue"] ?></td>


											</tr>

											<tr>
											<!-- <td></td> -->
												<td>Mobile</td>
												<td class="fcp_m"><?= $audits_m["first-contentful-paint"]["numericValue"] ?></td>
												<td class="lcp_m"><?= $audits_m["largest-contentful-paint"]["numericValue"] ?></td>
												<!-- <td class="mpf_m"><?= $audits_m["max-potential-fid"]["numericValue"] ?></td> -->
												<td class="cls_m"><?= round($audits_m["cumulative-layout-shift"]["numericValue"], 3) ?></td>
												<td class="tbt_m"><?= $audits_m["total-blocking-time"]["numericValue"] ?></td>
											</tr>


											</tbody>
											</table>
											<?php }else{echo "<table class='table table-script-speed'><thead><th></th></thead><tbody><tr><td>No data found. Please wait while we are updating new speed.</td></tr></tbody></table>";} ?>										
									</div>	


<!-- End after paramiter end -->


								<p>	You will get monthly status reports of you speed score , you can configure it <a href="<?= HOST_URL ?>adminpannel/page-speed.php?project=<?= base64_encode($project_id); ?>" >  here</a></p>								
							</div>
							</div>
							
 <?php } ?>


								  </div>
								 

									
							 </div>	
								 	





					 </div>


						</div>
					</div>	
						   

	<!--  Step 5 end -->
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
													<?=$country__code_2?>
												<div>
													<label>Contact Number</label>
													<input id="contact-2" class="form-control" type="number" value="<?=$row['phone']?>" required>
												</div>
											</div>
											<div class="full__col">
												<label>Email</label>
												<input type="email"  class="form-control" value="<?=$row['email']?>" id="email-2">
											</div>
											<?=$timezone_2?>
											 
											<div class="col-12">
												<span>Suitable Time For Contact</span>
												<input id="suitable-time-2" type="date" class="form-control" required>
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
											<button class="mt-3 btn btn-success" id="request_form_2">Submit</button>										
										
										</div>



								</div>
							</div>
							</div>

						<!-- Horizontal Steppers -->



							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>


</html>

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
	window.open('https://help.websitespeedy.com/', '_blank');
});

var from_tab = "5";
 
$(".i-need-help").click(function(){

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
  html: '<div class="wrapper__need__help__pop"><p>Check out Installation instructions</p> <a class="btn btn-primary text-primary instructions__btn__install" target="_blank" href="https://help.websitespeedy.com/">Go to instructions</a><p>Generate a Installation request for installation by Website speedy team</p></div>',
  
  icon: 'question',
  showCancelButton: false,
  showCloseButton: true,
  confirmButtonText: 'Create install request',
  cancelButtonText: 'Platform wise instruction',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
  	
  	if('<?=$request_sent?>'==0){
  		from_tab_t = from_tab;
  	 
  		$(".alert-pop").click();
 
  	}
  	else{
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
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {

		// window.location.href="instruction.php";
		 window.open('instruction.php', '_blank');

  }
})

});



var website_id_main = '<?=$website_data['id']?>';


$(".generate_btn").click(function () {

	$(".loader").show().html("<div class='loader_s'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analizing your website. It might take 2-3 mins<p>     </div>");
	
	setTimeout(function () {
		$(".loader").hide();


		// $(".tabs-1-head  .icon").removeClass("fa-angle-up");
		// $(".tabs-1-head  .icon").addClass("fa-angle-down");
		// $(".tabs-2-head").show();
		$("#step3").removeClass("d-none");
		$("#step3").addClass("complited");
		$(".top-step-3").click();
		// $("#step2").addClass("complited");  			
	}, 1500);
	installation_steps(3);

});



$("#website-platform, #traffic, #country_id").change(function(){
 
	// checK_dis();

});	
	// checK_dis();

// function checK_dis(){
// 		console.log("sss");
// 		var f1,f2,f3 = 0;
// 		if ($("#traffic").val() != "Select") {
// 			f1 = 0;
// 		} else {
// 			f1 = 1;
// 		}

// 		if ($("#website-platform").val() != "Select") {
// 		f2 = 0;
// 		} else {
// 		f2 = 1;
// 		}

// 		var url = $("#website-url").val();
// 		if (url != "") {
// 		f3 = 0;
// 		} else {
// 		f3 = 1;			 
// 		}
 
// 		if(f1==0 && f2==0 && f3==0){
// 			save_inate();
// 			$('.generate_btn').removeAttr("disabled");

// 		}
// 		else{
// 			$('.generate_btn').prop("disabled", true);
// 		}	
// }

// function save_inate(){
//     var id = '<?=$project_ids?>';
//     var traffic = $("#traffic").val() ;
//     var platform = $("#website-platform").val() ;
//     var country = $("#country_id").val() ;


// 	var req = $.ajax({
// 	    url:'inc/save_script_inst.php',
// 	    method: 'POST',
// 	    dataType: 'JSON',
// 	    async : false ,
// 	    data : {"id":id,"traffic":traffic , "platform":platform, "country":country }
// 	}) ;

//     req.done(function(reponse){

//     });

//     req.fail(function(reponse){
//         console.log(reponse);
//     });

// }

$(".next-step").click(function(){

	var id = $(this).attr("data-id");


  		$("#step1").hide();
    	$("#step2").show();

		$(".warning .circle ").removeClass("bg-light");
		$(".warning .circle ").addClass("bg-info");

});	

		$(".verification_btn").click(function() {

    console.log("s");          
 		$(".loader").show().html("<div class='loader_s'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>-0.1 seconds of loading can result in +8% conversions.<p>     </div>");

	var url=$(this).attr("data-vari");
	var script = $(this).attr("urls");
	var id = $(this).attr("data-id");


verify_script(url,script);

// manage_speed(id);



});


function manage_speed(id){
    $.ajax({
      type: "POST",
      url: "inc/manage_speed.php",
      data: {id:id,speedtype:speedtype},
      dataType: "json",
      encode: true,
    }).done(function (data) {
      // console.log(data);
    });	
    if(speedtype=="new"){
    	$(".inst_speed_new-container").show();
    }
}



$("body").on("click",".manualbtnverify",function(){
 
    Swal.fire({
      text: 'Use the button below if your website is still not being verified even after adding the script, By clicking this button you are agreeing that you have already added the script as per the instructions.',
      confirmButtonText: 'Agree',
      showCancelButton: true,
  			 cancelButtonText: 'Disagree',
    }
    ).then((result) => {
  if (result.isConfirmed) {

$("#step4").removeClass("d-none");
              			 $("#step4").addClass("complited"); 
             			 $(".top-step-4").click(); 
             			 localStorage.setItem("waitTime", new Date());
             			 $(".new-analyze").hide();
             			 $(".inst_speed_new-container").hide();
             			 setTimeout(function(){
             			 showTimer();
             			 },500);
             			 installation_steps(4, null, 'manual');
             			 $(".step-2-reanalyse.reanalyze-btn-").hide();
						 $(".reanalyzeold").show();

  }
});


});



function verify_script(url,script){

	var req = $.ajax({
	    url:'inc/verify_script.php',
	    method: 'POST',
	    dataType: 'JSON',
	    // async : false ,
       
        data : {"url":url , "script":script }
	}) ;

    req.done(function(reponse){

     	if(reponse == 0){
          $(".loader").hide();
					// 			new swal("Provided script is not added on the website <?=parse_url($website_data['website_url'])['host'];?>. please check and try again.");
					// 			Swal.fire({
					// 			  // icon: 'error',
					// 			  // title: 'Cannot Verify WebsiteSpeedy Script',
					// 			  showCloseButton: true,
					//    			  allowOutsideClick: false,
					//   			  allowEscapeKey: false,
					//   			  // text: 'Provided script is not added on the website "<?=parse_url($website_data['website_url'])['host'];?>". please check and try again.',
					// 			  html: '<p>Cannot Verify WebsiteSpeedy Script</p>',
					//   			  showCancelButton: true,
					//   			  cancelButtonText: 'Okay',
					//   			  confirmButtonText: 'Manual Verification'
								  
					// 			}).then((result) => {
					//   if (result.isConfirmed) {
					//     Swal.fire({
					//       text: 'Use the button below if your website is still not being verified even after adding the script, By clicking this button you are agreeing that you have already added the script as per the instructions.',
					//       confirmButtonText: 'Agree',
					//       showCancelButton: true,
					//   			 cancelButtonText: 'Disagree',
					//     }
					//     ).then((result) => {
					//   if (result.isConfirmed) {

					// $("#step4").removeClass("d-none");
					//               			 $("#step4").addClass("complited"); 
					//              			 $(".top-step-4").click(); 
					//              			 localStorage.setItem("waitTime", new Date());
					//              			 $(".new-analyze").hide();
					//              			 $(".inst_speed_new-container").hide();
					//              			 setTimeout(function(){
					//              			 showTimer();
					//              			 },500);
					//              			 installation_steps(4, null, 'manual');
					//              			 $(".step-2-reanalyse.reanalyze-btn-").hide();
					// 						 $(".reanalyzeold").show();

					//   }
					// })
					//   }
					// })

 			Swal.fire({
			  // icon: 'error',
			  // title: 'Cannot Verify WebsiteSpeedy Script',
			  showCloseButton: true,
   			  allowOutsideClick: false,
  			  allowEscapeKey: false,
  			  showCancelButton: false,
  			  showConfirmButton:false,
  			  // text: 'Provided script is not added on the website "<?=parse_url($website_data['website_url'])['host'];?>". please check and try again.',
			  html: '<h3>Cannot Verify WebsiteSpeedy Script</h3><div class="verify-icon-popup"><img src="img/website speedy.png" /></div>'+
			  		'<p>We were unable to verify the Script on your website. Please check and try again.</p>'+
			  		'<p>Please click “Manual verification” button below if you have added the script to your website.</p>'+
			  		'<button class="btn btn-primary manualbtnverify" >Manual Verification</button>'+
			  		'<p>or</p>'+
			  		'<p>Contact support if you need help with adding script.</p>'+
			  		'<a class="btn btn-primary manualbtnverify" target="_blank" href="https://help.websitespeedy.com/user/support/create_ticket">Contact support </a>'


			  			
  		 
			  
			});


     	}
     	else{
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

				    	 $("#step4").removeClass("d-none");
              			 $("#step4").addClass("complited"); 
             			 $(".top-step-4").click(); 
             			 localStorage.setItem("waitTime", new Date());
             			 $(".new-analyze").hide();
             			 $(".inst_speed_new-container").hide();
             			 setTimeout(function(){
             			 showTimer();
             			 },500);
             			 installation_steps(4);
             			 $(".step-2-reanalyse.reanalyze-btn-").hide();
						 $(".reanalyzeold").show();
              	 }
				})

     		 
	
     	}

    });

    req.fail(function(reponse){
        console.log(reponse);
    });

}		


		$(".table-script-core").hide();


		$(".page-speed1").click(function() {

			$(".table-script-core").hide();
			$(".table-script-speed").show();
			$(".page-speed1").addClass("btn-primary");
			$(".page-speed1").removeClass("btn-light");
			$(".core-web-vital2").addClass("btn-light");
			$(".core-web-vital2").removeClass("btn-primary");

		});
		$(".core-web-vital2 ").click(function() {
			$(".table-script-speed").hide();
			$(".table-script-core").show();

			$(".core-web-vital2").addClass("btn-primary");
			$(".core-web-vital2").removeClass("btn-light");
			$(".page-speed1").addClass("btn-light");
			$(".page-speed1").removeClass("btn-primary");
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

				$(".loader").show().html('<div class="loader"><p>Analizing your website. It might take 2-3 mins</p><dotlottie-player src="https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie"  background="transparent"  speed="1"  style="width: 300px; height: 300px;" loop autoplay></dotlottie-player></div>');

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


// if('<?=$count?>'<3){
// 	console.log("regenerate script");
// }


// $(".stepper a").click(function(){


// 	});


$(".tabs-head").click(function(){
	var tab = "."+$(this).attr("tab");


	$(tab).toggle();
	$(this).find(".icon").toggleClass("rotate");
	// $(this).find(".icon").toggleClass("fa-angle-down");

});


$(".stepper a").click(function(){

	var tab = $(this).attr("tab");
	if($(tab).hasClass('complited')){
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
	$(this).parent().nextAll().find(".circle").each(function(){
			$(this).removeClass("bg-info");
	});

	$(this).parent().prevAll().find(".circle").each(function(){
			$(this).removeClass("bg-light");
	});
	$(this).parent().prevAll().find(".circle").each(function(){
			$(this).addClass("bg-info");
	});

	
	$(this).parent().nextAll().find(".circle").each(function(){
			$(this).addClass("bg-light");
	});

	}

if(tab == "#step5"){
    $('html, body').animate({
        scrollTop: $("#step5").offset().top-100
    }, );

}


});



var script_loading = 0;
var speedtype="";

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
                    website_url: website_url,speedtype: speedtype,website_id:website_id,table_id:table_id
                },
                dataType: "JSON",
                timeout: 0,
                beforeSend: function () {
                    $(".loader").show().html("<div class='loader_s'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analizing your website. It might take 2-3 mins<p>     </div>");


                },
                success: function (data) {
                	script_loading++;
                    if (data.status == "done") {


                        var content = data.message;
                        console.log(content);
                                if(content.desktop=="0/100"){
                                	try_again($(".reanalyze-btn-new").attr("data-website_id"));
                                }

						
                            if (content.type == "desktop" && content.speedtype =='new') {
                                // console.log("new");

                                // $(".inst_speed_new").find(".desktop").text(content.desktop);
                                // $(".inst_speed_new").find(".mobile").text(content.mobile);
                                $(".inst_speed_new").find(".performance").text(content.desktop);
                                $(".inst_speed_new").find(".accessibility").text(content.accessibility);
                                $(".inst_speed_new").find(".best-practices").text(content.bestpractices);
                                $(".inst_speed_new").find(".seo").text(content.seo);
                                $(".inst_speed_new").find(".pwa").text(content.pwa);
                                $(".inst_speed_new").find(".fcp").text(content.FCP);
                                $(".inst_speed_new").find(".lcp").text(content.LCP);
                                $(".inst_speed_new").find(".mpf").text(content.MPF);
                                $(".inst_speed_new").find(".cls").text(content.CLS);
                                $(".inst_speed_new").find(".tbt").text(content.TBT);
                                
                                $(".inst_speed_new-container").show();
                               
                           


                            }

                            else{
                                       // $(".inst_speed").find(".desktop").text(content.desktop);
                                // $(".inst_speed").find(".mobile").text(content.desktop);
                                $(".inst_speed").find(".performance").text(content.desktop);
                                $(".inst_speed").find(".accessibility").text(content.accessibility);
                                $(".inst_speed").find(".best-practices").text(content.bestpractices);
                                $(".inst_speed").find(".seo").text(content.seo);
                                $(".inst_speed").find(".pwa").text(content.pwa);
                                $(".inst_speed").find(".fcp").text(content.FCP);
                                $(".inst_speed").find(".lcp").text(content.LCP);
                                $(".inst_speed").find(".mpf").text(content.MPF);
                                $(".inst_speed").find(".cls").text(content.CLS);
                                $(".inst_speed").find(".tbt").text(content.TBT);

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

                    if(tab == "#step4"){
    $('html, body').animate({
        scrollTop: $("#step4").offset().top+150
    }, );

}

                },





                error: function (xhr) { // if error occured
                	script_loading++;
                    console.error(xhr.statusText + xhr.responseText);
                },
                complete: function () {
                	if(script_loading==2){
                    	$(".loader").hide().html("Please Wait...");
                    	
                    	manage_speed($(".verification_btn").attr("data-id"));
                    }
                }
            });



            $.ajax({
                url: "inc/check-speed-mobile.php",
                method: "POST",
                data: {
                    website_url: website_url,speedtype: speedtype,website_id:website_id
                },
                dataType: "JSON",
                timeout: 0,
                beforeSend: function () {
                    $(".loader").show().html("<div class='loader_s'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analizing your website. It might take 2-3 mins<p>     </div>");


                },
                success: function (data) {
                    script_loading++;
                    if (data.status == "done") {


                        var content = data.message;
                        console.log(content);
						
                                if(content.desktop=="0/100"){
                                	try_again($(".reanalyze-btn-new").attr("data-website_id"));
                                }

                            if (content.type == "mobile" && content.speedtype =="new") {
                                // console.log("new");

                                // $(".inst_speed_new").find(".desktop").text(content.desktop);
                                // $(".inst_speed_new").find(".mobile").text(content.mobile);
                                $(".inst_speed_new").find(".performance_m").text(content.mobile);
                                $(".inst_speed_new").find(".accessibility_m").text(content.accessibility);
                                $(".inst_speed_new").find(".best-practices_m").text(content.bestpractices);
                                $(".inst_speed_new").find(".seo_m").text(content.seo);
                                $(".inst_speed_new").find(".pwa_m").text(content.pwa);
                                $(".inst_speed_new").find(".fcp_m").text(content.FCP);
                                $(".inst_speed_new").find(".lcp_m").text(content.LCP);
                                $(".inst_speed_new").find(".mpf_m").text(content.MPF);
                                $(".inst_speed_new").find(".cls_m").text(content.CLS);
                                $(".inst_speed_new").find(".tbt_m").text(content.TBT);
                                
                                $(".inst_speed_new").removeClass("d-none");

 								$(".inst_speed_new-container").show();                           


                            }
                            else{


                                $(".inst_speed").find(".performance_m").text(content.mobile);
                                $(".inst_speed").find(".accessibility_m").text(content.accessibility);
                                $(".inst_speed").find(".best-practices_m").text(content.bestpractices);
                                $(".inst_speed").find(".seo_m").text(content.seo);
                                $(".inst_speed").find(".pwa_m").text(content.pwa);
                                $(".inst_speed").find(".fcp_m").text(content.FCP);
                                $(".inst_speed").find(".lcp_m").text(content.LCP);
                                $(".inst_speed").find(".mpf_m").text(content.MPF);
                                $(".inst_speed").find(".cls_m").text(content.CLS);
                                $(".inst_speed").find(".tbt_m").text(content.TBT);
 
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
                	if(script_loading==2){
                    	$(".loader").hide().html("Please Wait...");

                    	manage_speed($(".verification_btn").attr("data-id"));
                    }
                    
                }


            });




        });


// Start generate request 
	$('.alert-pop').click(function(){
		 generate_request('step');
		
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


async function generate_request(f){

var days = 7;
var date = new Date();
var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

var afterDate = new Date(res);
 var month = afterDate.getUTCMonth()+ 1; //months from 1-12
var day = afterDate.getUTCDate();
var year = afterDate.getUTCFullYear();

// Plus 3 month after adding 7
var dayss = 3;

var ress = afterDate.setTime(date.getTime() + (dayss * 24 * 60 * 60 * 1000));
var afterDatess = new Date(ress);
 var months = afterDatess.getUTCMonth()+ 1; //months from 1-12
var days = afterDatess.getUTCDate();
var years = afterDatess.getUTCFullYear();
 
 console.log(from_tab); 
if(f=='step'){ 

		const { value: formValues } = await Swal.fire({
		  title: 'Please confirm your contact details and a Suitable time to get in touch',
		  html: '<?=$form?>',
		  focusConfirm: false,
		  showCloseButton: true,
		  allowOutsideClick: false,
		showConfirmButton: false,  
		  allowEscapeKey: false,
		  preConfirm: () => {
		  	return false;
		    
		  }
		})

		if (formValues) {
		  Swal.fire(JSON.stringify(formValues))
		}

}
else if(f=='direct'){


			const { value: formValues } = await Swal.fire({
			  title: 'Please confirm your contact details and a Suitable time to get in touch',
			  html: '<?=$form2?>',
			  focusConfirm: false,
			  showCloseButton: true,
			  allowOutsideClick: false,
			showConfirmButton: false,  
			  allowEscapeKey: false,
			  preConfirm: () => {
			  	return false;
			    
			  }
			})

			if (formValues) {
			  Swal.fire(JSON.stringify(formValues))
			}	
}

 
}


// $("#request_form").click(function(event){
$("body").on("click","#request_form_2", function(){
var days = 7;
var date = new Date();
var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

var afterDate = new Date(res);
 var month = afterDate.getUTCMonth()+ 1; //months from 1-12
var day = afterDate.getUTCDate();
var year = afterDate.getUTCFullYear();

// Plus 3 month after adding 7
var dayss = 3;

var ress = afterDate.setTime(date.getTime() + (dayss * 24 * 60 * 60 * 1000));
var afterDatess = new Date(ress);
 var months = afterDatess.getUTCMonth()+ 1; //months from 1-12
var days = afterDatess.getUTCDate();
var years = afterDatess.getUTCFullYear();

   var start_date=day+'-'+month+'-'+year;
   var end_date=days+'-'+months+'-'+years;

	// event.preventDefault();
	  var cc = document.getElementById('country-code-2').value;
      var contact = document.getElementById('contact-2').value;
      var suitable = document.getElementById('suitable-time-2').value;
      var form = document.getElementById('time-form-2').value;
      var to = document.getElementById('time-to-2').value;
      var email = document.getElementById('email-2').value;
      var tz = document.getElementById('tz-2').value;

      var e = ValidateEmail(email);

      if(cc!="" && contact!="" && suitable !="" && form!="" && to!="" && e ==1 && tz!="")
      {
      	// alert("saving");

		        
		      $.ajax({
		       url: "generate-script-save-request.php",
		        type: "POST",
		        dataType: "json",
		      data: {
		        start_date:start_date,
		        end_date:end_date,
		        platform:"",
		        traffic:"",
		        email:email, 
		        country_code:cc, 
		        contact:contact, 
		        suitable:suitable, 
		        from:form, 
		        to:to, 
		        tz:tz,
		        step:"1",
		        script:'<?=serialize($script_url)?>',   
		        website_url:'<?=$website_data['website_url']?>',    
		        id: '<?=$_GET["project"] ?>'

		        },
		      success: function (data) {
		      	console.log(data);
		      	if(data=='saved'){
		      		// installation_steps(6);

							  	     $(".request_form_2_sent").show();
							  	     $(".support__form.request_form_2").hide();					 


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
									      data: {id:'<?=$user_id?>'},
									      dataType: "json",
									      encode: true,
									    }).done(function (data) {
									      // console.log(data);
									});	
											    
									Swal.fire({
									  title: 'Request sent',
									  icon: 'success',
									  html:
									    'Great We will get in touch with you soon, in the mean time you can - <br>' 
									    
									    +'<div class="to__start"><a href="https://help.websitespeedy.com/" target="_blank">1. Explore our Knowledge base </a> <br>'
									    +'<a href="https://websitespeedy.com/why-website-speed-matters.php" target="_blank">2. Learn Why Speed Matters</a><br>'
									    +'<span>3. Follow us on Social</span></div><br>'
									    +'<div class="social__links">'
									    +'<a href="https://www.facebook.com/websitespeedy" target="_blank"><svg height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve"> <path style="fill:#385C8E;" d="M134.941,272.691h56.123v231.051c0,4.562,3.696,8.258,8.258,8.258h95.159  c4.562,0,8.258-3.696,8.258-8.258V273.78h64.519c4.195,0,7.725-3.148,8.204-7.315l9.799-85.061c0.269-2.34-0.472-4.684-2.038-6.44  c-1.567-1.757-3.81-2.763-6.164-2.763h-74.316V118.88c0-16.073,8.654-24.224,25.726-24.224c2.433,0,48.59,0,48.59,0  c4.562,0,8.258-3.698,8.258-8.258V8.319c0-4.562-3.696-8.258-8.258-8.258h-66.965C309.622,0.038,308.573,0,307.027,0  c-11.619,0-52.006,2.281-83.909,31.63c-35.348,32.524-30.434,71.465-29.26,78.217v62.352h-58.918c-4.562,0-8.258,3.696-8.258,8.258  v83.975C126.683,268.993,130.379,272.691,134.941,272.691z"/> </svg></a>'
									    +'<a href="https://www.instagram.com/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 32 32" fill="none"> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint0_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint1_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint2_radial_87_7153)"/> <path d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z" fill="white"/> <defs> <radialGradient id="paint0_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)"> <stop stop-color="#B13589"/> <stop offset="0.79309" stop-color="#C62F94"/> <stop offset="1" stop-color="#8A3AC8"/> </radialGradient> <radialGradient id="paint1_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)"> <stop stop-color="#E0E8B7"/> <stop offset="0.444662" stop-color="#FB8A2E"/> <stop offset="0.71474" stop-color="#E2425C"/> <stop offset="1" stop-color="#E2425C" stop-opacity="0"/> </radialGradient> <radialGradient id="paint2_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)"> <stop offset="0.156701" stop-color="#406ADC"/> <stop offset="0.467799" stop-color="#6A45BE"/> <stop offset="1" stop-color="#6A45BE" stop-opacity="0"/> </radialGradient> </defs> </svg></a>'
									    +'<a href="https://www.linkedin.com/company/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -2 44 44" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-702.000000, -265.000000)" fill="#007EBB"> <path d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z" id="LinkedIn"> </path> </g> </g> </svg></a>'
									    +'<a href="https://www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -7 48 48" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-200.000000, -368.000000)" fill="#CE1312"> <path d="M219.044,391.269916 L219.0425,377.687742 L232.0115,384.502244 L219.044,391.269916 Z M247.52,375.334163 C247.52,375.334163 247.0505,372.003199 245.612,370.536366 C243.7865,368.610299 241.7405,368.601235 240.803,368.489448 C234.086,368 224.0105,368 224.0105,368 L223.9895,368 C223.9895,368 213.914,368 207.197,368.489448 C206.258,368.601235 204.2135,368.610299 202.3865,370.536366 C200.948,372.003199 200.48,375.334163 200.48,375.334163 C200.48,375.334163 200,379.246723 200,383.157773 L200,386.82561 C200,390.73817 200.48,394.64922 200.48,394.64922 C200.48,394.64922 200.948,397.980184 202.3865,399.447016 C204.2135,401.373084 206.612,401.312658 207.68,401.513574 C211.52,401.885191 224,402 224,402 C224,402 234.086,401.984894 240.803,401.495446 C241.7405,401.382148 243.7865,401.373084 245.612,399.447016 C247.0505,397.980184 247.52,394.64922 247.52,394.64922 C247.52,394.64922 248,390.73817 248,386.82561 L248,383.157773 C248,379.246723 247.52,375.334163 247.52,375.334163 L247.52,375.334163 Z" id="Youtube"> </path> </g> </g> </svg></a>'
									    +'</div>'
									    ,
									showConfirmButton : true,
									confirmButtonText: 'Thanks'

									});



							  } else if (result.isDenied) {

							  	    $('html, body').animate({
								        scrollTop: $("#step1").offset().top-100
								    },1 );
							   
							  }
							})					

					$(".alert-pop").addClass("request_sent").removeClass("alert-pop").attr("disabled",true);   

						 // $("#step6").removeClass("d-none");
       //        			 $("#step6").addClass("complited"); 
       //       			 $(".top-step-6").click();
       $('.request_generate_top_btn').attr("sent","1");
       $('.request_generate_top_btn').attr("sent","1");
  


		      	}
		      	else if(data==2){
					Swal.fire(
					  'Already Saved',
					  'Your Request is already saved.',
					  'info'
					)		      		
		      	}
		      	else{
					Swal.fire(
					  'Opps..',
					  'Something went wrong.',
					  'error'
					)			      		
		      	}
		        
		      }
		    }); 
         	

      }
      else{
      	if(e == 0)
      	{
      		$(".request-err").html("Invalid Email.");
	      	
	      	$(".request-err").removeClass("d-none");
	      	setTimeout(function(){
	      	$(".request-err").addClass("d-none");

	      	},4000);

      	}
      	else{
      		$(".request-err").html("Please Fill All Field.");
	      	$(".request-err").removeClass("d-none");
	      	setTimeout(function(){
	      	$(".request-err").addClass("d-none");

	      	},4000);
	     }
      	// alert("please feel all fields");
      }

});


// $("#request_form").click(function(event){
$("body").on("click","#request_form", function(){
var days = 7;
var date = new Date();
var res = date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));

var afterDate = new Date(res);
 var month = afterDate.getUTCMonth()+ 1; //months from 1-12
var day = afterDate.getUTCDate();
var year = afterDate.getUTCFullYear();

// Plus 3 month after adding 7
var dayss = 3;

var ress = afterDate.setTime(date.getTime() + (dayss * 24 * 60 * 60 * 1000));
var afterDatess = new Date(ress);
 var months = afterDatess.getUTCMonth()+ 1; //months from 1-12
var days = afterDatess.getUTCDate();
var years = afterDatess.getUTCFullYear();

   var start_date=day+'-'+month+'-'+year;
    var end_date=days+'-'+months+'-'+years;

	// event.preventDefault();
	  var cc = document.getElementById('country-code').value;
      var contact = document.getElementById('contact').value;
      var suitable = document.getElementById('suitable-time').value;
      var form = document.getElementById('time-form').value;
      var to = document.getElementById('time-to').value;
      var email = document.getElementById('email').value;
      var tz = document.getElementById('tz').value;

      var e = ValidateEmail(email);

      if(cc!="" && contact!="" && suitable !="" && form!="" && to!="" && e ==1 && tz!="")
      {
      	// alert("saving");

      	var from_tab_btn = 3;
      	if(from_tab == "step3"){
      		from_tab_btn = 2;
       	}


		        
		      $.ajax({
		       url: "generate-script-save-request.php",
		        type: "POST",
		        dataType: "json",
		      data: {
		        start_date:start_date,
		        end_date:end_date,
		        platform:"",
		        traffic:"",
		        email:email, 
		        country_code:cc, 
		        contact:contact, 
		        suitable:suitable, 
		        from:form, 
		        to:to, 
		        tz:tz,
		        step:from_tab_btn,
		        script:'<?=serialize($script_url)?>',   
		        website_url:'<?=$website_data['website_url']?>',    
		        id: '<?=$_GET["project"] ?>'

		        },
		      success: function (data) {
		      	console.log(data);
		      	if(data=='saved'){

		<?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'installation_steps(3);':'installation_steps(6);'?>	      		
		      		

					Swal.fire({
					  title: 'Request sent',
					  icon: 'success',
					  html:
					    'Great We will get in touch with you soon, We will contact you via email or phone. Please make sure to check Spam folder in case you do not get an email in next 12 hours.' 
					    +'In the mean time you can - <br>' 
					    +'<div class="to__start" ><a href="https://help.websitespeedy.com/" target="_blank">1. Explore our Knowledge base </a> <br>'
					    +'<a href="https://websitespeedy.com/why-website-speed-matters.php" target="_blank">2. Learn Why Speed Matters</a><br>'
					    +'<span>3. Follow us on Social</span></div><br>'
					    +'<div class="social__links">'
					    +'<a href="https://www.facebook.com/websitespeedy" target="_blank"><svg height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve"> <path style="fill:#385C8E;" d="M134.941,272.691h56.123v231.051c0,4.562,3.696,8.258,8.258,8.258h95.159  c4.562,0,8.258-3.696,8.258-8.258V273.78h64.519c4.195,0,7.725-3.148,8.204-7.315l9.799-85.061c0.269-2.34-0.472-4.684-2.038-6.44  c-1.567-1.757-3.81-2.763-6.164-2.763h-74.316V118.88c0-16.073,8.654-24.224,25.726-24.224c2.433,0,48.59,0,48.59,0  c4.562,0,8.258-3.698,8.258-8.258V8.319c0-4.562-3.696-8.258-8.258-8.258h-66.965C309.622,0.038,308.573,0,307.027,0  c-11.619,0-52.006,2.281-83.909,31.63c-35.348,32.524-30.434,71.465-29.26,78.217v62.352h-58.918c-4.562,0-8.258,3.696-8.258,8.258  v83.975C126.683,268.993,130.379,272.691,134.941,272.691z"/> </svg></a>'
					    +'<a href="https://www.instagram.com/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 32 32" fill="none"> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint0_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint1_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint2_radial_87_7153)"/> <path d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z" fill="white"/> <defs> <radialGradient id="paint0_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)"> <stop stop-color="#B13589"/> <stop offset="0.79309" stop-color="#C62F94"/> <stop offset="1" stop-color="#8A3AC8"/> </radialGradient> <radialGradient id="paint1_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)"> <stop stop-color="#E0E8B7"/> <stop offset="0.444662" stop-color="#FB8A2E"/> <stop offset="0.71474" stop-color="#E2425C"/> <stop offset="1" stop-color="#E2425C" stop-opacity="0"/> </radialGradient> <radialGradient id="paint2_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)"> <stop offset="0.156701" stop-color="#406ADC"/> <stop offset="0.467799" stop-color="#6A45BE"/> <stop offset="1" stop-color="#6A45BE" stop-opacity="0"/> </radialGradient> </defs> </svg></a>'
					    +'<a href="https://www.linkedin.com/company/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -2 44 44" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-702.000000, -265.000000)" fill="#007EBB"> <path d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z" id="LinkedIn"> </path> </g> </g> </svg></a>'
					    +'<a href="https://www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -7 48 48" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-200.000000, -368.000000)" fill="#CE1312"> <path d="M219.044,391.269916 L219.0425,377.687742 L232.0115,384.502244 L219.044,391.269916 Z M247.52,375.334163 C247.52,375.334163 247.0505,372.003199 245.612,370.536366 C243.7865,368.610299 241.7405,368.601235 240.803,368.489448 C234.086,368 224.0105,368 224.0105,368 L223.9895,368 C223.9895,368 213.914,368 207.197,368.489448 C206.258,368.601235 204.2135,368.610299 202.3865,370.536366 C200.948,372.003199 200.48,375.334163 200.48,375.334163 C200.48,375.334163 200,379.246723 200,383.157773 L200,386.82561 C200,390.73817 200.48,394.64922 200.48,394.64922 C200.48,394.64922 200.948,397.980184 202.3865,399.447016 C204.2135,401.373084 206.612,401.312658 207.68,401.513574 C211.52,401.885191 224,402 224,402 C224,402 234.086,401.984894 240.803,401.495446 C241.7405,401.382148 243.7865,401.373084 245.612,399.447016 C247.0505,397.980184 247.52,394.64922 247.52,394.64922 C247.52,394.64922 248,390.73817 248,386.82561 L248,383.157773 C248,379.246723 247.52,375.334163 247.52,375.334163 L247.52,375.334163 Z" id="Youtube"> </path> </g> </g> </svg></a>'
					    +'</div>'
					    ,
					showConfirmButton : true,
					confirmButtonText: 'Thanks'

					})
					
					$(".alert-pop").addClass("request_sent").removeClass("alert-pop").attr("disabled",true);   

					<?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'':'	     
											 $("#step6").removeClass("d-none");
					              			 $("#step6").addClass("complited"); 
					             			 $(".top-step-6").click();

					'?>

		      	}
		      	else if(data==2){
					Swal.fire(
					  'Already Saved',
					  'Your Request is already saved.',
					  'info'
					)		      		
		      	}
		      	else{
					Swal.fire(
					  'Opps..',
					  'Something went wrong.',
					  'error'
					)			      		
		      	}
		        
		      }
		    }); 
         	

      }
      else{
      	$(".request-err").removeClass("d-none");
      	setTimeout(function(){
      	$(".request-err").addClass("d-none");

      	},4000);
      	// alert("please feel all fields");
      }

});



function ValidateEmail(mail) 
{
 // if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
if(mail.indexOf("@") >0 && mail.indexOf(".") >0 )
  {
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



$(".goto-5").click(function(){

	<?=(strtolower($website_data['platform']) == 'wix' || strtolower($website_data['platform_name']) == 'wix' || strtolower($website_data['platform_name']) == 'squarespace' || strtolower($website_data['platform']) == 'squarespace')?'$("#trusted").toggle();installation_steps(4,$(this).attr("data-satisfy"));':'$("#step5").removeClass("d-none");


               $("#step5").addClass("complited"); 
              $(".top-step-5").click(); 	
              installation_steps(5,$(this).attr("data-satisfy"));'?>

			



});


$(".continue-to-step-2").click(function(){
 		$(".loader").show().html("<div class='loader_s'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analizing your website. It might take 2-3 mins<p>     </div>");

  		setTimeout(function(){
 			$(".loader").hide();
               $("#step2").removeClass("d-none"); 	
                $("#step2").addClass("complited");  
               $(".top-step-2").click(); 			
 		},500);

installation_steps(2);

});



function installation_steps(step,satisfy_val=" ", method = null){
 var id = '<?=$project_ids?>';
 var satisfy=satisfy_val;
 // console.log(satisfy);
	var req = $.ajax({
	    url:'inc/update_installation_steps.php',
	    method: 'POST',
	    dataType: 'JSON',
	    async : false ,
	    data : {"id":id,"step":step,"satisfy":satisfy, "method":method}
	}) ;	

}


	});




	var tm = "";
if(localStorage.getItem("waitTime")!=null){
 tm = "start";
 showTimer();

}

 // $(".inst_speed_new-container").addClass("d-none");

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
	var old_date = new Date(old_d.getTime() + 2 *60000);

 
	 var date = new Date();

	 var Time = (old_date-date)/60000;


	 target_date = new Date(date.getTime() + Time *60000);


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
		if(!s.includes("NaN")){
			countdown.innerHTML = s;
		}

		if ((parseInt(minutes) <= 0) && (parseInt(seconds) <= 0)) {
			clearInterval(counterInterval);
			countdown.innerHTML = "00:00";
			

			console.log("Reached");
			$(".new-analyze").show();
			  // var sp = '<?=$new_speed?>';
			  // console.log(sp);
			  //  if(sp==0){
			 	// $(".inst_speed_new-container").addClass("d-none");
			  //  }
			  //  else{
			  //  	$(".inst_speed_new-container").removeClass("d-none");
			  //  }
			 // $(".inst_speed_new-container").removeClass("d-none");
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

$('.request_form_2').find('#country-code-2').niceSelect();
$('.request_form_2').find('#tz-2').niceSelect();

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

		function myFunction() {
		if (window.pageYOffset > sticky) {
			alert[0].classList.add("sticky");
			// content[0].classList.add('top-padding')  
		} else {
			alert[0].classList.remove("sticky");
			// content[0].classList.remove('top-padding')  
		}
		}

</script>


