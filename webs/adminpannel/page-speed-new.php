<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php');

// check sign-up process complete
// checkSignupComplete($conn) ;


$user_id = $_SESSION["user_id"];	
$project_id = base64_decode($_GET["project"]);
					
						$project_data1 = getTableData($conn, " boost_website ", " id = '" . $project_id . "' AND manager_id = '" . $user_id . "' ");

					
$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");
// print_r($row) ;

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}

if($_GET['project']==null || count($project_data1)<=0 ){

	$_SESSION['error'] = "Requested URL is not valid!";

header("location: ".HOST_URL."adminpannel/dashboard.php");
die();

}

?>
<?php require_once("inc/style-and-script.php"); ?>
<script type="text/javascript">
	function changetime(btn) {
console.log(btn)
		var stype1 = $(btn).val();
		var changetime = $(btn).data("changetime");
		var changetime2 = $(btn).data("changetime2");

		//alert(stype1);

		$.ajax({
			type: 'POST',
			url: 'page-speed-update.php',
			dataType: "JSON",
			data: {
				'stype1': stype1,
				'changetime': changetime,
				'changetime2': changetime2
			},
			success: function(response) {

				// console.log(response.status);

				if (response.status == "done") {
					$(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				} else {
					$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Operation Failed.' + response.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}
			}
		});


	}
</script>
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
</style>
</head>

<body class="custom-tabel">
	<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>

		<!-- Page content wrapper-->
		<div id="page-content-wrapper">
		<div class="loader"><div class="loader_s"><dotlottie-player src="https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie"  background="transparent"  speed="1"  style="width: 300px; height: 300px;" loop autoplay></dotlottie-player><p id="loader_text">Please Wait...</p></div></div>
			<?php require_once("inc/topbar.php"); ?>

			<!-- Page content-->
			<div class="container-fluid page__speed content__up">

				<h1 class="mt-4">Google Insight Score</h1>

				

				<div class="profile_tabs ">
					<?php require_once("inc/alert-status.php"); ?>
					<div class="tool_type">
				<a type="button" class="Page_insight_btn active"><img src="https://www.gstatic.com/pagespeed/insights/ui/logo/favicon_48.png" width="24" height="24" alt="PageSpeed Insights logo"><div>PageSpeed Insights</div></a>
				<a href="javascript:void(0)" class="Gtmetrix_btn disabled"><div>GTmetrix <span>(coming soon)</span></div></a>
				<a href="javascript:void(0)" class="pingdom_btn disabled"><div>Pingdom <span>(coming soon)</span></div></a>
						</div>
			<div class="page_insight">
					<div class="wrapper_btn_form">
						<?php
						$user_id = $_SESSION["user_id"];
						
						
						$project_id = base64_decode($_GET["project"]);
						

						$project_data = getTableData($conn, " boost_website ", " id = '" . $project_id . "' AND manager_id = '" . $user_id . "' ");

						?>
						
						<!-- <div style="text-align: right;"><a href="<?= HOST_URL ?>adminpannel/addon.php?project=<?= base64_encode($project_id); ?>" class="btn btn-primary">Setup URL Tracking</a></div> -->

						<div class="page_web_s">

						<a class="btn btn-primary page-speed">Page Speed</a>

						<a class="btn btn-light core-web-vital showing">Core Web Vital</a>

                         </div>

						<div class="form-group">
							<?php
							if ($project_data["plan_type"] == "Subscription" && !empty($project_data["subscription_id"])) {
							?>
							<!-- <label><input type="checkbox" class="auto-scan-website" value="1" data-project="<?= $project_id ?>" <?php if ($project_data["auto_scan"] == 1) {
												echo "checked";
											}  ?>> Auto Scan</label> -->
							<?php
							} else {
							?>
								<!-- <label><input type="checkbox" disabled> Auto Scan</label>
								<small>(Purchase a subscription plan to use this feature.)</small> -->

							<?php
							}
							?>
						</div>
					</div>

					<div style="position:relative;" class="tabber tab-1 2">
						<div class="loader"><div class="loader_s"><dotlottie-player src="https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie"  background="transparent"  speed="1"  style="width: 300px; height: 300px;" loop autoplay></dotlottie-player><p id="loader_text">Please Wait...</p></div></div>
						<div class=" table-responsive">
							<form id="myform">
								<div class="table_S">
									<table class="table" id="page-speed-table" data-project="<?= $project_id ?>" data-type="page-speed">
										<thead>
											<tr>
												<th>Page</th>
												<th>Device</th>
												<!-- <th>Mobile</th> -->
												<th>PERFORMANCE</th>
												<th>ACCESSIBILITY</th>
												<th>BEST PRACTICES</th>
												<th>SEO</th>
												<!-- <th>PWA</th> -->
												<th>MONITORING</th>
												<th>REPORT INTERVAL</th>
												<th>LAST UPDATE</th>
												<th>REANALYZE</th>
												<th>REPORT</th>
											</tr>
										</thead>
										<tbody>
											<?php


											$query = $conn->query(" SELECT * FROM boost_website WHERE boost_website.id = '$project_id' ");

											if ($query->num_rows > 0) {
												$ps_last_date = $ps_performance = $ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $ps_mobile = "-";

												$website_data = $query->fetch_assoc();
												// print_r($pagespeed_data) ;

												$query = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id' ORDER BY id DESC LIMIT 1 ");
												if ($query->num_rows > 0) {
													$pagespeed_data = $query->fetch_assoc();

													$ps_categories = unserialize($pagespeed_data["categories"]);

													// =========================
													$ps_performance = round($ps_categories["performance"]["score"] * 100, 2);
													$ps_desktop = $ps_performance . "/100";
													$ps_accessibility = round($ps_categories["accessibility"]["score"] * 100, 2);
													$ps_best_practices = round($ps_categories["best-practices"]["score"] * 100, 2);
													$ps_seo = round($ps_categories["seo"]["score"] * 100, 2);
													$ps_pwa = round($ps_categories["pwa"]["score"] * 100, 2);
													// =========================

													$ps_mobile_categories = unserialize($pagespeed_data["mobile_categories"]);
													$ps_performance_m = round($ps_mobile_categories["performance"]["score"] * 100, 2);
													$ps_mobile =  $ps_performance_m. "/100";
													$ps_accessibility_m = round($ps_mobile_categories["accessibility"]["score"] * 100, 2);
													$ps_best_practices_m = round($ps_mobile_categories["best-practices"]["score"] * 100, 2);
													$ps_seo_m = round($ps_mobile_categories["seo"]["score"] * 100, 2);
													$ps_pwa_m = round($ps_mobile_categories["pwa"]["score"] * 100, 2);

													$date1 = date_create($pagespeed_data["created_at"]);
													$date2 = date_create(date('Y-m-d H:i:s'));
													$diff = date_diff($date1, $date2);
													// print_r($diff) ;

													if (!empty($diff->m)) {
														$s =  "";
														if($diff->m>1){$s="s";}
														$ps_last_date = $diff->m . " month".$s." ago";
													} elseif (!empty($diff->d)) {
														$s =  "";
														if($diff->d>1){$s="s";}
														$ps_last_date = $diff->d . " day".$s." ago";
													} elseif (!empty($diff->h)) {
														$s =  "";
														if($diff->h>1){$s="s";}
														$ps_last_date = $diff->h . " hour".$s." ago";
													} else {
														$s =  "";
														if($diff->i>1){$s="s";}
														$ps_last_date = $diff->i . " minute".$s." ago";
														// $ps_last_date = $diff->i . " minute".$s." ago";
													}
												}

												//  ============================================================

												$monitoring = ($website_data["monitoring"] == 1) ? "text-success" : "text-danger";
												$reanalyze_toggle = ($website_data["monitoring"] == 1) ? "" : "disabled";

											?>
												<tr>
													<td rowspan="2" class="two__elm__spc"><span><?= $website_data["website_name"] ?></span><br><a href="<?= $website_data["website_url"] ?>" target="_blank"><?= $website_data["website_url"] ?></a></td>
													<td class="desktop ttt">Desktop</td>
													<!-- <td class="mobile"><?= $ps_mobile ?></td> -->
													<td class="performance"><?= $ps_desktop ?></td>
													<td class="accessibility"><?= $ps_accessibility ?></td>
													<td class="best-practices"><?= $ps_best_practices ?></td>
													<td class="seo"><?= $ps_seo ?></td>
													<!-- <td class="pwa"><?= $ps_pwa ?></td> -->
													<td rowspan="2"><a href="javascript:void(0);" class="url-monitoring <?= $monitoring ?>" data-additional="0"><i class="fa fa-check-circle" aria-hidden="true"></i></a></td>
													<td rowspan="2">
														<?php

														$pid = $website_data["plan_id"];
														$changeid = $website_data["id"];
														$pid_sql="SELECT * FROM plans WHERE id='$pid'";
														// echo $pid_sql;
														$sele_qry = $conn->query($pid_sql);
														$run_qrys = $sele_qry->fetch_assoc();

														// echo($run_qrys);
														$stype = $run_qrys['s_type'];

														$period_ ="";
														$query = $conn->query(" SELECT 'period' FROM boost_website WHERE id = $changeid ") ;
														$row = getTableData($conn, " boost_website ", " id ='" . $changeid . "'  ");
															// print_r($query);

															 $period_ = $row['period'];

														//  print_r($value);
														?>
														<input type="hidden" style="display:none"  data-changetime="<?= $changeid ?>"  class="plan_period" value="<?php echo $period_;?> "/>
														<select class="form-control 1" onchange="changetime(this);" data-changetime="<?= $changeid ?>">
																
															<!-- <option value="Daily"    <?= $daily_disable ?> <?= $daily ?>>Daily(Booster Plan)</option>
															<option value="Weekly" <?= $weekly_disable ?> <?= $weekly ?>>Weekly(Super Plan)</option> -->
															<option value="monthly"<?= $monthly_enable ?> <?= $monthlys ?>>Monthly</option>
														</select>
														<?php //$pagespeed_data["period"]
														?>
													</td>
													<td rowspan="2" class="last-update"><?= $ps_last_date ?></td>
													<td rowspan="2">
														<button type="button" class="btn btn-primary reanalyze-btn" data-website_name="<?= $website_data["website_name"] ?>" data-ps_mobile="<?= $ps_mobile ?>" data-ps_performance="<?= $ps_performance ?>" data-ps_accessibility="<?= $ps_accessibility ?>" data-ps_best_practices="<?= $ps_best_practices ?>" data-ps_seo="<?= $ps_seo ?>" data-ps_pwa="<?= $ps_pwa ?>" data-website_url="<?= $website_data["website_url"] ?>" data-ps_desktop="<?= $ps_desktop ?>" data-additional="0" <?= $reanalyze_toggle ?>>
														


														<i class="fa fa-refresh" aria-hidden="true"></i>

														<img src="./img/Rounded blocks.gif" class="loader_icon" style="display:none;">
													
													
													   </button>
													</td>
													<td rowspan="2"><a href="<?= HOST_URL ?>adminpannel/lighthouse-report.php?project=<?= ($_GET["project"]) ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
												</tr>
												<tr>
													<!-- <td class="desktop ttt"><?= $ps_desktop ?></td> -->
													<td class="mobile">Mobile</td>
													<td class="performance_m"><?= $ps_mobile ?></td>
													<td class="accessibility_m"><?= $ps_accessibility_m ?></td>
													<td class="best-practices_m"><?= $ps_best_practices_m ?></td>
													<td class="seo_m"><?= $ps_seo_m ?></td>
													<!-- <td class="pwa_m"><?= $ps_pwa_m ?></td> -->
												</tr>

												<?php

												// get additional urls
												$au_query = $conn->query(" SELECT * FROM `additional_websites` WHERE manager_id = '$user_id' AND website_id = '$project_id' AND flag='true' and website_url <>'' ");

												// print_r($au_query) ;
												if ($au_query->num_rows > 0) {
													$au_data = $au_query->fetch_all(MYSQLI_ASSOC);

													foreach ($au_data as $key => $additional_url) {
														$ps_last_date = $ps_performance = $ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $ps_mobile = "-";

														// get there pagespeed data
														$ps_query = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '" . $additional_url["id"] . "' AND parent_website = '$project_id' ORDER BY id DESC LIMIT 1 ");

														if ($ps_query->num_rows > 0) {
															// echo $additional_url["id"];
															$ps_data = $ps_query->fetch_assoc();
															// print_r($ps_data);
															$ps_categories = unserialize($ps_data["categories"]);

															// =========================
															$ps_performance = round($ps_categories["performance"]["score"] * 100, 2);
															$ps_desktop = $ps_performance . "/100";
															$ps_accessibility = round($ps_categories["accessibility"]["score"] * 100, 2);
															$ps_best_practices = round($ps_categories["best-practices"]["score"] * 100, 2);
															$ps_seo = round($ps_categories["seo"]["score"] * 100, 2);
															$ps_pwa = round($ps_categories["pwa"]["score"] * 100, 2);
															// =========================

															$ps_mobile_categories = unserialize($ps_data["mobile_categories"]);
															$ps_performance_m = round($ps_mobile_categories["performance"]["score"] * 100, 2);
															$ps_mobile = $ps_performance_m . "/100";
															$ps_accessibility_m = round($ps_mobile_categories["accessibility"]["score"] * 100, 2);
															$ps_best_practices_m = round($ps_mobile_categories["best-practices"]["score"] * 100, 2);
															$ps_seo_m = round($ps_mobile_categories["seo"]["score"] * 100, 2);
															$ps_pwa_m = round($ps_mobile_categories["pwa"]["score"] * 100, 2);


															$date1 = date_create($ps_data["created_at"]);
															$date2 = date_create(date('Y-m-d H:i:s'));
															$diff = date_diff($date1, $date2);
															// print_r($diff) ;

															if (!empty($diff->m)) {
																$ps_last_date = $diff->m . " month ago";
															} elseif (!empty($diff->d)) {
																$ps_last_date = $diff->d . " day ago";
															} elseif (!empty($diff->h)) {
																$ps_last_date = $diff->h . " hour ago";
															} else {
																$s =  "";
																if($diff->i>1){$s="s";}
																$ps_last_date = $diff->i . " minute".$s." ago";
																// $ps_last_date = $diff->i . " minute ago";
															}
														}

														// print_r($ps_query) ;

														$monitoring = ($additional_url["monitoring"] == 1) ? "text-success" : "text-danger";
														$reanalyze_toggle = ($additional_url["monitoring"] == 1) ? "" : "disabled";
														$addwebid = $additional_url["website_url"];
														// echo "ps_desktop".$ps_desktop;
												?>
														<tr>
															<td rowspan="2"><?= $additional_url["website_name"] ?><br><a href="<?= $addwebid ?>" target="_blank"><?= $additional_url["website_url"] ?></a></td>
															<td class="desktop vvv">Desktop</td>
															<!-- <td class="mobile"><?= $ps_mobile ?></td> -->
															<td class="performance"><?= $ps_desktop ?></td>
															<td class="accessibility"><?= $ps_accessibility ?></td>
															<td class="best-practices"><?= $ps_best_practices ?></td>
															<td class="seo"><?= $ps_seo ?></td>
															<!-- <td class="pwa"><?= $ps_pwa ?></td> -->
															<td rowspan="2"><a href="javascript:void(0);" class="url-monitoring <?= $monitoring ?>" data-additional="<?= $additional_url["id"] ?>"><i class="fa fa-check-circle" aria-hidden="true"></i></a></td>
															<td rowspan="2">
																<?php
																
																$period_ ="";
																// echo $additional_url['id'];
																$row = getTableData($conn, " additional_websites ", " id ='" . $additional_url['id'] . "'  ");
																	// print_r($query);
																		
																	 $period_ = $row['period'];
																?>
														<input type="hidden" style="display:none"  data-changetime="<?= $additional_url["id"] ?>"  class="plan_period" value="<?php echo $period_;?> "/>

																<select class="form-control 2" onchange="changetime(this);" data-changetime2="<?= $additional_url["id"] ?>">
																	<!-- <option value="daily" <?= $daily_disable ?> <?= $dailys ?>>Daily(Booster Plan)</option>
																	<option value="weekly" <?= $weekly_disable ?> <?= $weeklys ?>>Weekly(Super Plan)</option> -->
																	<option value="monthly" <?= $monthly_enable ?> <?= $monthlyss ?>>Monthly</option>
																</select>
																<?php //$pagespeed_data["period"]
																?>
															</td>

															<td rowspan="2" class="last-update"><?= $ps_last_date ?></td>
															<td rowspan="2"><button type="button" class="btn btn-primary reanalyze-btn" data-website_name="<?= $additional_url["website_name"] ?>" data-ps_mobile="<?= $ps_mobile ?>" data-ps_performance="<?= $ps_performance ?>" data-ps_accessibility="<?= $ps_accessibility ?>" data-ps_best_practices="<?= $ps_best_practices ?>" data-ps_seo="<?= $ps_seo ?>" data-ps_pwa="<?= $ps_pwa ?>" data-website_url="<?= $additional_url["website_url"] ?>" data-ps_desktop="<?= $ps_desktop ?>" data-additional="<?= $additional_url["id"] ?>" <?= $reanalyze_toggle ?>>
															<i class="fa fa-refresh" aria-hidden="true"></i>
															<img src="./img/Rounded blocks.gif" class="loader_icon" style="display:none;">
														</button></td>
															<td rowspan="2"><a href="<?= HOST_URL ?>adminpannel/lighthouse-report.php?project=<?= ($_GET["project"]) ?>&additional=<?= $additional_url["id"] ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
														</tr>
												<tr>
													<!-- <td class="desktop ttt"><?= $ps_desktop ?></td> -->
													<td class="mobile">Mobile</td>
													<td class="performance_m"><?= $ps_mobile ?></td>
													<td class="accessibility_m"><?= $ps_accessibility_m ?></td>
													<td class="best-practices_m"><?= $ps_best_practices_m ?></td>
													<td class="seo_m"><?= $ps_seo_m ?></td>
													<!-- <td class="pwa_m"><?= $ps_pwa_m ?></td> -->
												</tr>


												<?php
													}
												}
											} else {
												?><tr>
													<td colspan="13">No data found.</td>
												</tr>
											<?php
											}

											?>
										</tbody>
									</table>
								</div>
							</form>
						</div>
						<div class="page_history">

								<div class="page_history_header"><div class="mt-3"><h4>Page Speed History</h4></div></div>

<div class="wrapper_btn_form">
												
						<div class="page_web_s">

						<a class="btn btn-primary old-speed-btn">Old Report</a>

						<a class="btn btn-light monthly-speed-btn showing">Monthly Report</a>

                         </div>
						<div class="form-group">
						</div>
</div>

							<?php

							$history_data = getTableData($conn, " website_speed_history ", "website_id='".$project_id."' ", "order by id desc", 1);
						
							?>
							<table class="table" >

									<thead>
											<tr>
												<th>Page</th>
												<th>Device</th>
												<th>PERFORMANCE</th>
												<th>ACCESSIBILITY</th>
												<th>BEST PRACTICES</th>
												<th>SEO</th>
												<!-- <th>PWA</th> -->
											</tr>
									</thead>
								<tbody>
<?php

											// $query = $conn->query(" SELECT * FROM boost_website WHERE boost_website.id = '$project_id' ");

											// if ($query->num_rows > 0) {
											// 	$ps_last_date = $ps_performance = $ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $ps_mobile = "-";

											// 	$website_data = $query->fetch_assoc();
											// 	// print_r($pagespeed_data) ;

												$query = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id' ORDER BY id ASC LIMIT 1 ");
												if ($query->num_rows > 0) {
													$pagespeed_data = $query->fetch_assoc();

													$ps_categories = unserialize($pagespeed_data["categories"]);

													// =========================
													$ps_performance = round($ps_categories["performance"]["score"] * 100, 2);
													$ps_desktop = $ps_performance . "/100";
													$ps_accessibility = round($ps_categories["accessibility"]["score"] * 100, 2);
													$ps_best_practices = round($ps_categories["best-practices"]["score"] * 100, 2);
													$ps_seo = round($ps_categories["seo"]["score"] * 100, 2);
													$ps_pwa = round($ps_categories["pwa"]["score"] * 100, 2);
													// =========================

													$ps_mobile_categories = unserialize($pagespeed_data["mobile_categories"]);
													$ps_performance_m = round($ps_mobile_categories["performance"]["score"] * 100, 2);
													$ps_mobile =  $ps_performance_m. "/100";
													$ps_accessibility_m = round($ps_mobile_categories["accessibility"]["score"] * 100, 2);
													$ps_best_practices_m = round($ps_mobile_categories["best-practices"]["score"] * 100, 2);
													$ps_seo_m = round($ps_mobile_categories["seo"]["score"] * 100, 2);
													$ps_pwa_m = round($ps_mobile_categories["pwa"]["score"] * 100, 2);

													$date1 = date_create($pagespeed_data["created_at"]);
													$date2 = date_create(date('Y-m-d H:i:s'));
													$diff = date_diff($date1, $date2);
													// print_r($diff) ;

													if (!empty($diff->m)) {
														$ps_last_date = $diff->m . " month ago";
													} elseif (!empty($diff->d)) {
														$ps_last_date = $diff->d . " day ago";
													} elseif (!empty($diff->h)) {
														$ps_last_date = $diff->h . " hour ago";
													} else {
														$s =  "";
														if($diff->i>1){$s="s";}
														$ps_last_date = $diff->i . " minute".$s." ago";
														// $ps_last_date = $diff->i . " minute ago";
													}
												}

												//  ============================================================

												$monitoring = ($website_data["monitoring"] == 1) ? "text-success" : "text-danger";
												$reanalyze_toggle = ($website_data["monitoring"] == 1) ? "" : "disabled";

?>
												<tr>
													<td rowspan="2" class="two__elm__spc"><span><?= $website_data["website_name"] ?></span><br><a href="<?= $website_data["website_url"] ?>" target="_blank"><?= $website_data["website_url"] ?></a></td>
													<td class="desktop ttt">Desktop</td>
													<td class="performance"><?= $ps_desktop ?></td>
													<td class="accessibility"><?= $ps_accessibility ?></td>
													<td class="best-practices"><?= $ps_best_practices ?></td>
													<td class="seo"><?= $ps_seo ?></td>
													<!-- <td class="pwa"><?= $ps_pwa ?></td> -->
												</tr>
												<tr>
													<td class="mobile">Mobile</td>
													<td class="performance_m"><?= $ps_mobile ?></td>
													<td class="accessibility_m"><?= $ps_accessibility_m ?></td>
													<td class="best-practices_m"><?= $ps_best_practices_m ?></td>
													<td class="seo_m"><?= $ps_seo_m ?></td>
													<!-- <td class="pwa_m"><?= $ps_pwa_m ?></td> -->
												</tr>	
<?php
												$au_query = $conn->query(" SELECT * FROM `additional_websites` WHERE manager_id = '$user_id' AND website_id = '$project_id' AND flag='true' and website_url <>'' ");

												// print_r($au_query) ;
												if ($au_query->num_rows > 0) {
													$au_data = $au_query->fetch_all(MYSQLI_ASSOC);

													foreach ($au_data as $key => $additional_url) {
														// get there pagespeed data
														$ps_query = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '" . $additional_url["id"] . "' AND parent_website = '$project_id' ORDER BY id ASC LIMIT 1 ");

														if ($ps_query->num_rows > 0) {
															// echo $additional_url["id"];
															$ps_data = $ps_query->fetch_assoc();
															// print_r($ps_data);
															$ps_categories = unserialize($ps_data["categories"]);

															// =========================
															$ps_performance = round($ps_categories["performance"]["score"] * 100, 2);
															$ps_desktop = $ps_performance . "/100";
															$ps_accessibility = round($ps_categories["accessibility"]["score"] * 100, 2);
															$ps_best_practices = round($ps_categories["best-practices"]["score"] * 100, 2);
															$ps_seo = round($ps_categories["seo"]["score"] * 100, 2);
															$ps_pwa = round($ps_categories["pwa"]["score"] * 100, 2);
															// =========================

															$ps_mobile_categories = unserialize($ps_data["mobile_categories"]);
															$ps_performance_m = round($ps_mobile_categories["performance"]["score"] * 100, 2);
															$ps_mobile = $ps_performance_m . "/100";
															$ps_accessibility_m = round($ps_mobile_categories["accessibility"]["score"] * 100, 2);
															$ps_best_practices_m = round($ps_mobile_categories["best-practices"]["score"] * 100, 2);
															$ps_seo_m = round($ps_mobile_categories["seo"]["score"] * 100, 2);
															$ps_pwa_m = round($ps_mobile_categories["pwa"]["score"] * 100, 2);


															$date1 = date_create($ps_data["created_at"]);
															$date2 = date_create(date('Y-m-d H:i:s'));
															$diff = date_diff($date1, $date2);
															// print_r($diff) ;

															if (!empty($diff->m)) {
																$ps_last_date = $diff->m . " month ago";
															} elseif (!empty($diff->d)) {
																$ps_last_date = $diff->d . " day ago";
															} elseif (!empty($diff->h)) {
																$ps_last_date = $diff->h . " hour ago";
															} else {
																$s =  "";
																if($diff->i>1){$s="s";}
																$ps_last_date = $diff->i . " minute".$s." ago";																
																// $ps_last_date = $diff->i . " minute ago";
															}

														// print_r($ps_query) ;

														$monitoring = ($additional_url["monitoring"] == 1) ? "text-success" : "text-danger";
														$reanalyze_toggle = ($additional_url["monitoring"] == 1) ? "" : "disabled";
														$addwebid = $additional_url["website_url"];
?> 




												<tr>
													<td rowspan="2"><?= $additional_url["website_name"] ?><br><a href="<?= $addwebid ?>" target="_blank"><?= $additional_url["website_url"] ?></a></td>
													<td >Device</td>
													<td class="performance"><?= $ps_desktop ?></td>
													<td class="accessibility"><?= $ps_accessibility ?></td>
													<td class="best-practices"><?= $ps_best_practices ?></td>
													<td class="seo"><?= $ps_seo ?></td>
													<!-- <td class="pwa"><?= $ps_pwa ?></td> -->
												</tr>
												<tr>
													<td>Mobile</td>
													<!-- <td class="mobile"><?= $ps_mobile ?></td> -->
													<td class="performance_m"><?= $ps_mobile ?></td>
													<td class="accessibility_m"><?= $ps_accessibility_m ?></td>
													<td class="best-practices_m"><?= $ps_best_practices_m ?></td>
													<td class="seo_m"><?= $ps_seo_m ?></td>
													<!-- <td class="pwa_m"><?= $ps_pwa_m ?></td> -->
												</tr>

<?php
}
}
}
?>


								</tbody>
								
							</table>
						</div>
					</div>

					<div style="position:relative; display: none;" class="tabber tab-2 1">
						
                        <div class="table_S">
						<table class="table" id="page-speed-table" data-project="<?= $project_id ?>" data-type="core-web-vital">
							<thead>
								<tr>
									<th>Page</th>
									<th>Device</th>
									<th>FCP</th>
									<th>LCP</th>
									<!-- <th>MPF</th> -->
									<th>CLS</th>
									
									<th>TBT</th>
									<th>MONITORING</th>
									<th>REPORT INTERVAL</th>
									<th>LAST UPDATE</th>
									<th>REANALYZE</th>
									<th>REPORT</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$query = $conn->query(" SELECT boost_website.id , boost_website.manager_id , boost_website.platform, boost_website.website_name , boost_website.website_url , boost_website.desktop_speed_new , boost_website.mobile_speed_new , pagespeed_report.audits, pagespeed_report.mobile_audits , pagespeed_report.id AS report_id , boost_website.monitoring , boost_website.period , boost_website.plan_id, pagespeed_report.created_at FROM boost_website , pagespeed_report WHERE boost_website.id = pagespeed_report.website_id AND boost_website.id = '$project_id' ORDER BY report_id DESC LIMIT 1 ");

								if ($query->num_rows > 0) {
									$pagespeed_data = $query->fetch_assoc();

									 
									$audits = unserialize($pagespeed_data["audits"]);
									$mobile_audits = unserialize($pagespeed_data["mobile_audits"]);
									 
									$date1 = date_create($pagespeed_data["created_at"]);
									$date2 = date_create(date('Y-m-d H:i:s'));
									$diff = date_diff($date1, $date2);
									 
									if (!empty($diff->m)) {
										$last_date = $diff->m . " month ago";
									} elseif (!empty($diff->d)) {
										$last_date = $diff->d . " day ago";
									} elseif (!empty($diff->h)) {
										$last_date = $diff->h . " hour ago";
									} else {
										$s =  "";
										if($diff->i>1){$s="s";}
										$last_date = $diff->i . " minute".$s." ago";
										// $last_date = $diff->i . " minute ago";
									}

									$monitoring = ($pagespeed_data["monitoring"] == 1) ? "text-success" : "text-danger";
									$reanalyze_toggle = ($pagespeed_data["monitoring"] == 1) ? "" : "disabled";

								?>
									<tr>
										<td rowspan="2" class="two__elm__spc"><span><?= $pagespeed_data["website_name"] ?></span><br><a href="<?= $pagespeed_data["website_url"] ?>" target="_blank"><?= $pagespeed_data["website_url"] ?></a></td>
										<td>Desktop</td>
										<td class="fcp"><?= $audits["first-contentful-paint"]["numericValue"] ?></td>
										<td class="lcp"><?= $audits["largest-contentful-paint"]["numericValue"] ?></td>
										<!-- <td class="mpf"><?= $audits["max-potential-fid"]["numericValue"] ?></td> -->
										<td class="cls"><?= round($audits["cumulative-layout-shift"]["numericValue"], 3) ?></td>
										<td class="tbt"><?= $audits["total-blocking-time"]["numericValue"] ?></td>

										<td rowspan="2"><a href="javascript:void(0);"  class="url-monitoring <?= $monitoring ?>" data-additional="0"><i class="fa fa-check-circle" aria-hidden="true"></i></a></td>
										<td rowspan="2">

											<?php

											// $pid = $pagespeed_data["manager_id"];
											$pid = $pagespeed_data["plan_id"];
											$changeid = $pagespeed_data["id"];
											$sele_qry = $conn->query(" SELECT * FROM plans WHERE id='$pid'");
											$run_qrys = $sele_qry->fetch_assoc();

											// echo($run_qrys);
											// print_r($run_qrys);
											$stype = $run_qrys['s_type'];


														$period_ ="";
														$query = $conn->query(" SELECT 'period' FROM boost_website WHERE id = $changeid ") ;
														$row = getTableData($conn, " boost_website ", " id ='" . $changeid . "'  ");
															// print_r($query);

															 $period_ = $row['period'];

											?>
														<input type="hidden" style="display:none"  data-changetime="<?= $changeid ?>"  class="plan_period" value="<?php echo $period_;?> "/>

											<select class="form-control 3" onchange="changetime(this);" data-changetime="<?= $changeid ?>">

												<!-- <option value="Daily" <?= $daily_disable ?> <?= $daily ?>>Daily(Booster Plan)</option>
												<option value="Weekly" <?= $weekly_disable ?> <?= $weekly ?>>Weekly(Super Plan)</option> -->
												<option value="monthly" <?= $monthly_enable ?> <?= $monthlys ?>>Monthly</option>
											</select>
											<?php //$pagespeed_data["period"]
											?>
										</td>
										<td  rowspan="2" class="last-update"><?= $last_date ?></td>
										<td rowspan="2"><button type="button" class="btn btn-primary reanalyze-btn" data-website_name="<?= $pagespeed_data["platform"] ?>" data-fcp="<?= $audits["first-contentful-paint"]["numericValue"] ?>" data-lcp="<?= $audits["largest-contentful-paint"]["numericValue"] ?>" data-mpf="<?= $audits["max-potential-fid"]["numericValue"] ?>" data-cls="<?= round($audits["cumulative-layout-shift"]["numericValue"], 3) ?>"  data-ps_tbt="<?= $audits["total-blocking-time"]["numericValue"] ?>"   data-website_url_core="<?= $pagespeed_data["website_url"] ?>"  data-ps_mobile="<?= $ps_mobile ?>" data-ps_performance="<?= $ps_performance ?>" data-ps_accessibility="<?= $ps_accessibility ?>" data-ps_best_practices="<?= $ps_best_practices ?>" data-ps_seo="<?= $ps_seo ?>" data-ps_pwa="<?= $ps_pwa ?>" data-website_url="<?= $website_data["website_url"] ?>" data-ps_desktop="<?= $ps_desktop ?>" data-additional="0" <?= $reanalyze_toggle ?>><i class="fa fa-refresh" aria-hidden="true"></i>
										<img src="./img/Rounded blocks.gif" class="loader_icon" style="display:none;">
									</button></td>
										<td rowspan="2"><a href="<?= HOST_URL ?>adminpannel/lighthouse-report.php?project=<?= ($_GET["project"]) ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
									</tr>
									<tr>
										<td>Mobile</td>
										<td class="fcp"><?= $mobile_audits["first-contentful-paint"]["numericValue"] ?></td>
										<td class="lcp"><?= $mobile_audits["largest-contentful-paint"]["numericValue"] ?></td>
										<!-- <td class="mpf"><?= $mobile_audits["max-potential-fid"]["numericValue"] ?></td> -->
										<td class="cls"><?= round($mobile_audits["cumulative-layout-shift"]["numericValue"], 3) ?></td>
										<td class="tbt"><?= $mobile_audits["total-blocking-time"]["numericValue"] ?></td>

									</tr>

									<?php

									// get additional urls
									$au_query = $conn->query(" SELECT * FROM `additional_websites` WHERE manager_id = '$user_id' AND website_id = '$project_id'  and website_url <>'' ");

									// print_r($au_query) ;
									if ($au_query->num_rows > 0) {
										$au_data = $au_query->fetch_all(MYSQLI_ASSOC);

										foreach ($au_data as $key => $additional_url) {
											$ps_last_date = $ps_fcp = $ps_lcp = $ps_mpf = $ps_cls = $ps_tbt = "-";

											// get there pagespeed data
											$ps_query = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '" . $additional_url["id"] . "' AND parent_website = '$project_id' ORDER BY id DESC LIMIT 1 ");

											if ($ps_query->num_rows > 0) {
												$ps_data = $ps_query->fetch_assoc();
												$ps_audits = unserialize($ps_data["audits"]);
												$ps_audits_m = unserialize($ps_data["mobile_audits"]);

												$ps_fcp = $ps_audits["first-contentful-paint"]["numericValue"];
												$ps_lcp = $ps_audits["largest-contentful-paint"]["numericValue"];
												$ps_mpf = $ps_audits["max-potential-fid"]["numericValue"];
												$ps_cls = round($ps_audits["cumulative-layout-shift"]["numericValue"], 3);
												$ps_tbt = $ps_audits["total-blocking-time"]["numericValue"];

												$ps_fcp_m = $ps_audits_m["first-contentful-paint"]["numericValue"];
												$ps_lcp_m = $ps_audits_m["largest-contentful-paint"]["numericValue"];
												$ps_mpf_m = $ps_audits_m["max-potential-fid"]["numericValue"];
												$ps_cls_m = round($ps_audits_m["cumulative-layout-shift"]["numericValue"], 3);
												$ps_tbt_m = $ps_audits_m["total-blocking-time"]["numericValue"];


												$date1 = date_create($ps_data["created_at"]);
												$date2 = date_create(date('Y-m-d H:i:s'));
												$diff = date_diff($date1, $date2);
												// print_r($diff) ;

												if (!empty($diff->m)) {
													$s =  "";
													if($diff->m>1){$s="s";}
													$ps_last_date = $diff->m . " month".$s." ago";
												} elseif (!empty($diff->d)) {
													$s =  "";
													if($diff->d>1){$s="s";}
													$ps_last_date = $diff->d . " day".$s." ago";
												} elseif (!empty($diff->h)) {
													$s =  "";
													if($diff->h>1){$s="s";}
													$ps_last_date = $diff->h . " hour".$s." ago";
												} else {

														$s =  "";
														if($diff->i>1){$s="s";}
														$ps_last_date = $diff->i . " minute".$s." ago";
													// $ps_last_date = $diff->i . " minute ago";
												}
											}

											// print_r($ps_query) ;

											$monitoring = ($additional_url["monitoring"] == 1) ? "text-success" : "text-danger";
											$reanalyze_toggle = ($additional_url["monitoring"] == 1) ? "" : "disabled";

									?>
											<tr>
												<td rowspan="2"><?= $additional_url["website_name"] ?><br><a href="<?= $additional_url["website_url"] ?>" target="_blank"><?= $additional_url["website_url"] ?></a></td>
		
												<td>Desktop</td>
												<td class="fcp"><?= $ps_fcp ?></td>
												<td class="lcp"><?= $ps_lcp ?></td>
												<!-- <td class="mpf"><?= $ps_mpf ?></td> -->
												<td class="cls"><?= $ps_cls ?></td>
												<td class="tbt"><?= $ps_tbt ?></td>
												<td rowspan="2"><a href="javascript:void(0);" class="url-monitoring <?= $monitoring ?>" data-additional="<?= $additional_url["id"] ?>"><i class="fa fa-check-circle" aria-hidden="true"></i></a></td>
												<td rowspan="2">
													<?php
													
													$period_ ="";
													$query = $conn->query(" SELECT 'period' FROM boost_website WHERE id = $changeid ") ;
													$row = getTableData($conn, " boost_website ", " id ='" . $changeid . "'  ");
														// print_r($query);

														 $period_ = $row['period'];
													?>
														<input type="hidden" style="display:none"  data-changetime="<?= $changeid ?>"  class="plan_period" value="<?php echo $period_;?> "/>

													<select class="form-control 4" onchange="changetime(this);" data-changetime2="<?= $additional_url["id"] ?>">
														<!-- <option value="daily" <?= $daily_disable ?> <?= $dailys ?>>Daily(GOLD)</option>
														<option value="weekly" <?= $weekly_disable ?> <?= $weeklys ?>>Weekly(SILVER)</option> -->
														<option value="monthly" <?= $monthly_enable ?> <?= $monthlyss ?>>Monthly</option>
													</select>
													<?php //$pagespeed_data["period"]
													?>
												</td>
												<td  rowspan="2" class="last-update"><?= $ps_last_date ?></td>
												<td  rowspan="2"><button type="button" class="btn btn-primary reanalyze-btn" data-website_name="<?= $additional_url["website_name"] ?>" data-fcp="<?= $ps_fcp; ?>" data-lcp="<?= $ps_lcp ?>" data-mpf="<?= $ps_mpf ?>" data-cls="<?=$ps_cls ?>" data-ps_tbt="<?=$ps_tbt ?>"  data-website_url_core="<?= $additional_url["website_url"] ?>"  data-additional="<?= $additional_url["id"] ?>" <?= $reanalyze_toggle ?>>
												
												<i class="fa fa-refresh" aria-hidden="true"></i>

												<img src="./img/Rounded blocks.gif" class="loader_icon" style="display:none;">
											
											
											</button></td>
												<td rowspan="2"><a href="<?= HOST_URL ?>adminpannel/lighthouse-report.php?project=<?= ($_GET["project"]) ?>&additional=<?= $additional_url["id"] ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
											</tr>
											<tr>
												<td>Mobile</td>
												<td class="fcp"><?= $ps_fcp_m ?></td>
												<td class="lcp"><?= $ps_lcp_m ?></td>
												<!-- <td class="mpf"><?= $ps_mpf_m ?></td> -->
												<td class="cls"><?= $ps_cls_m ?></td>
												<td class="tbt"><?= $ps_tbt_m ?></td>

											</tr>
									<?php
										}
									}
								} else {
									?><tr>
										<td colspan="11">No data found.</td>
									</tr>
								<?php
								}



								// pending -> MONITORING , 	PERIOD
								?>

							</tbody>
						</table>
					</div>
							</div>
					<div class="page_history core-vital-history" style="display: none;">
									<div class="page_history_header"><h4 class="mt-3"> Core Web Vital History</h4></div>

<div class="wrapper_btn_form">
												
						<div class="page_web_s">

						<a class="btn btn-primary old-speed-btn">Old Report</a>

						<a class="btn btn-light monthly-speed-btn showing">Monthly Report</a>

                         </div>
						<div class="form-group">
						</div>
</div>

							<?php


								$query = $conn->query(" SELECT boost_website.id , boost_website.manager_id , boost_website.platform , boost_website.website_url , boost_website.desktop_speed_new , boost_website.mobile_speed_new , pagespeed_report.audits, pagespeed_report.mobile_audits , pagespeed_report.id AS report_id , boost_website.monitoring , boost_website.period , boost_website.plan_id, pagespeed_report.created_at FROM boost_website , pagespeed_report WHERE boost_website.id = pagespeed_report.website_id AND boost_website.id = '$project_id' ORDER BY report_id ASC LIMIT 1 ");

								if ($query->num_rows > 0) {
									$pagespeed_data = $query->fetch_assoc();

									 
									$audits = unserialize($pagespeed_data["audits"]);
									$mobile_audits = unserialize($pagespeed_data["mobile_audits"]);
									 

							?>
							<div class="table_S">
							<table class="table" >
								<thead>
									<tr>
										<!-- <th>Date</th> -->
										<!-- <th>SITE</th> -->
										<th>Page</th>
										<th>Device</th>
										<th>FPC</th>
										<th>LCP</th>
										<!-- <th>MPF</th> -->
										<th>CLS</th>
										<th>TBT </th>
										

									</tr>
								</thead>
								<tbody>
									
											<tr>
												<td rowspan="2"><?= $pagespeed_data["website_name"] ?><br><a href="<?= $addwebid ?>" target="_blank"><?= $pagespeed_data["website_url"] ?></a></td>
												<td>Desktop</td>
												<td class="fcp"><?= $audits["first-contentful-paint"]["numericValue"] ?></td>
												<td class="lcp"><?= $audits["largest-contentful-paint"]["numericValue"] ?></td>
												<td class="cls"><?= round($audits["cumulative-layout-shift"]["numericValue"], 3) ?></td>
												<td class="tbt"><?= $audits["total-blocking-time"]["numericValue"] ?></td>
											
											</tr>
											<tr>
												<td>Mobile</td>
												<td class="fcp"><?= $mobile_audits["first-contentful-paint"]["numericValue"] ?></td>
												<td class="lcp"><?= $mobile_audits["largest-contentful-paint"]["numericValue"] ?></td>
												<td class="cls"><?= round($mobile_audits["cumulative-layout-shift"]["numericValue"], 3) ?></td>
												<td class="tbt"><?= $mobile_audits["total-blocking-time"]["numericValue"] ?></td>
											</tr>






											<?php

									// get additional urls
									$au_query = $conn->query(" SELECT * FROM `additional_websites` WHERE manager_id = '$user_id' AND website_id = '$project_id'  and website_url <>'' ");

									// print_r($au_query) ;
									if ($au_query->num_rows > 0) {
										$au_data = $au_query->fetch_all(MYSQLI_ASSOC);

										foreach ($au_data as $key => $additional_url) {
											$ps_last_date = $ps_fcp = $ps_lcp = $ps_mpf = $ps_cls = $ps_tbt = "-";

											// get there pagespeed data
											$ps_query = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '" . $additional_url["id"] . "' AND parent_website = '$project_id' ORDER BY id ASC LIMIT 1 ");

											if ($ps_query->num_rows > 0) {
												$ps_data = $ps_query->fetch_assoc();
												$ps_audits = unserialize($ps_data["audits"]);
												$ps_audits_m = unserialize($ps_data["mobile_audits"]);

												$ps_fcp = $ps_audits["first-contentful-paint"]["numericValue"];
												$ps_lcp = $ps_audits["largest-contentful-paint"]["numericValue"];
												$ps_mpf = $ps_audits["max-potential-fid"]["numericValue"];
												$ps_cls = round($ps_audits["cumulative-layout-shift"]["numericValue"], 3);
												$ps_tbt = $ps_audits["total-blocking-time"]["numericValue"];

												$ps_fcp_m = $ps_audits_m["first-contentful-paint"]["numericValue"];
												$ps_lcp_m = $ps_audits_m["largest-contentful-paint"]["numericValue"];
												$ps_mpf_m = $ps_audits_m["max-potential-fid"]["numericValue"];
												$ps_cls_m = round($ps_audits_m["cumulative-layout-shift"]["numericValue"], 3);
												$ps_tbt_m = $ps_audits_m["total-blocking-time"]["numericValue"];


												$date1 = date_create($ps_data["created_at"]);
												$date2 = date_create(date('Y-m-d H:i:s'));
												$diff = date_diff($date1, $date2);
												// print_r($diff) ;

												if (!empty($diff->m)) {
													$ps_last_date = $diff->m . " month ago";
												} elseif (!empty($diff->d)) {
													$ps_last_date = $diff->d . " day ago";
												} elseif (!empty($diff->h)) {
													$ps_last_date = $diff->h . " hour ago";
												} else {
												$s =  "";
												if($diff->i>1){$s="s";}
												$ps_last_date = $diff->i . " minute".$s." ago";

													// $ps_last_date = $diff->i . " minute ago";
												}
											}

											// print_r($ps_query) ;

											$monitoring = ($additional_url["monitoring"] == 1) ? "text-success" : "text-danger";
											$reanalyze_toggle = ($additional_url["monitoring"] == 1) ? "" : "disabled";


											?>
											<tr>
												<td rowspan="2"><?= $additional_url["website_name"] ?><br><a href="<?= $additional_url["website_url"] ?>" target="_blank"><?= $additional_url["website_url"] ?></a></td>
												<td>Desktop</td>
												<td class="fcp"><?= $ps_fcp ?></td>
												<td class="lcp"><?= $ps_lcp ?></td>
												<td class="cls"><?= $ps_cls ?></td>
												<td class="tbt"><?= $ps_tbt ?></td>
											</tr>
											<tr>
												<td>Mobile</td>
												<td class="fcp"><?= $ps_fcp_m ?></td>
												<td class="lcp"><?= $ps_lcp_m ?></td>
												<td class="cls"><?= $ps_cls_m ?></td>
												<td class="tbt"><?= $ps_tbt_m ?></td>
											</tr>

								<?php			

										}
									}

								


									} else { ?>
										<tr>
											<td colspan="9"><strong>No Page Speed History </strong></td>
										</tr>

									<?php } ?>
								</tbody>
							</table>
									</div>
						</div>

				</div>
			</div>
<!-- Page speed data end-->


<!-- Core web page data -->



				</div>


<!-- Core web page data end-->

		</div>
	</div>
	<script>	
$(".reanalyze-btn").click(function(){
    $(this).find('.fa-arrows-rotate').css('display','none');
    $(this).find('.loader_icon').css('display','block');
});


$(".monthly-speed-btn").click(function(){
	Swal.fire(
	  'Coming Soon',
	  'This feature is coming soon',
	  'info'
	)
});

// show select period
$('.form-control').each(function(){
		var period= $(this).prev('.plan_period').val();
		
			$(this).find("option[value='" + period.trim() + "']").attr('selected','selected');
			// if(period=="Weekly"){
			// $(this).find('option[value="Weekly"]').attr("selected", "selected");
			// }else if(period=="Daily"){
			// 	$(this).find('option[value="Daily"]').attr("selected", "selected");

			// }else if(period=="monthly"){
			// 	$(this).find('option[value="monthly"]').attr("selected", "selected");

			// }
		
})

		// code for select tool type
		setTimeout(() => {
				
				$('.tool_type .Page_insight_btn').on('click',function(){
					
			 if($('.page_insight').css('display')=='none'){
				
				$('.page_insight').css('display','block')
			}		
		});
		 $('.tool_type .Gtmetrix_btn').on('click',()=>{$('.page_insight').css('display','none')});
		 $('.tool_type .pingdom_btn').on('click',()=>{$('.page_insight').css('display','none')});
			},1000);
		// code end function 
		</script>

</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		//  $('.tool_type').on('click',()=>{$('.page_insight').css('display','block')});

		$("#page-speed-history").hide();

		$("#page-toggle-history").click(function() {
			$("#page-speed-history").toggle();
		});
// core-vital-history
setTimeout(function(){
		if($('.showing').hasClass('btn-primary')){
			$(".core-vital-history").show();
		}
},10);
		$(".core-web-vital").click(function(){
			$(".tab-1").hide();
			$(".tab-2").show();
			$(".core-vital-history").show();
			$(".core-web-vital").addClass("btn-primary");
			$(".core-web-vital").removeClass("btn-light");
			$(".page-speed").addClass("btn-light");
			$(".page-speed").removeClass("btn-primary");

		});
		$(".page-speed").click(function(){
			$(".core-vital-history").hide();
			$(".tab-2").hide();
			$(".tab-1").show();
			$(".page-speed").addClass("btn-primary");
			$(".page-speed").removeClass("btn-light");
			$(".core-web-vital").addClass("btn-light");
			$(".core-web-vital").removeClass("btn-primary");

		});
	});
	// by EMPLOPY 58  03-2-2023 ---------------  tab tabel dashbord

document.querySelector('.btn.core-web-vital').addEventListener('click',function(){
    localStorage.setItem("core_web_vital", "core_web_vital_val")
})
var get_core_web_vital = localStorage.getItem("core_web_vital");
    if(get_core_web_vital){
        document.querySelector('div.tabber.tab-1').style.display='none'
document.querySelector('.btn.core-web-vital').classList.add('btn-primary')
document.querySelector('.btn.page-speed').classList.remove('btn-primary')
document.querySelector('.btn.page-speed').classList.add('btn-light')
document.querySelector('div.tabber.tab-2').style.display = 'block'
} 
document.querySelector('.btn.page-speed').addEventListener('click',function(){  
localStorage.removeItem("core_web_vital");
})


</script>

