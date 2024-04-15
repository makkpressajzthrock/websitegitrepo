<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
require_once('inc/functions.php');

// check sign-up process complete
// checkSignupComplete($conn) ;

$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");
// print_r($row) ;

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}

?>
<?php require_once("inc/style-and-script.php"); ?>
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

			<?php require_once("inc/topbar.php"); ?>

			<!-- Page content-->
			<div class="container-fluid core_web_S content__up">

				<h1 class="mt-4">Core Web Vital</h1>
				<?php
				$user_id = $_SESSION["user_id"];
				$project_id = base64_decode($_GET["project"]);

				$project_data = getTableData($conn, " boost_website ", " id = '" . $project_id . "' AND manager_id = '" . $user_id . "' ");
				?>

				<?php require_once("inc/alert-status.php"); ?>

				<div class="profile_tabs">
					<div class="wrapper_btn_form">
						<a href="<?= HOST_URL ?>adminpannel/page-speed.php?project=<?= base64_encode($project_id); ?>" class="btn btn-primary">Page Speed</a>

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

					<div style="position:relative;">
						<div class="loader">Please Wait...</div>
                        <div class="table_S">
						<table class="table" id="page-speed-table" data-project="<?= $project_id ?>" data-type="core-web-vital">
							<thead>
								<tr>
									<th>PAGE</th>
									<th>FCP</th>
									<th>LCP</th>
									<th>MPF</th>
									<th>CLS</th>
									<!-- <th>SPDI</th>
										<th>INT</th> -->
									<th>TBT</th>
									<th>MONITORING</th>
									<th>PERIOD</th>
									<th>LAST DATE</th>
									<th>REANALYZE</th>
									<th>REPORT</th>
								</tr>
							</thead>
							<tbody>
								<?php

								$query = $conn->query(" SELECT boost_website.id , boost_website.manager_id , boost_website.platform , boost_website.website_url , boost_website.desktop_speed_new , boost_website.mobile_speed_new , pagespeed_report.audits , pagespeed_report.id AS report_id , boost_website.monitoring , boost_website.period , boost_website.plan_id, pagespeed_report.created_at FROM boost_website , pagespeed_report WHERE boost_website.id = pagespeed_report.website_id AND boost_website.id = '$project_id' ORDER BY report_id DESC LIMIT 1 ");

								if ($query->num_rows > 0) {
									$pagespeed_data = $query->fetch_assoc();

									// echo "<pre>";
									$audits = unserialize($pagespeed_data["audits"]);
									// print_r(array_keys($audits))  ;

									// print_r($audits["first-contentful-paint"]) ;
									// echo "</pre>";

									$date1 = date_create($pagespeed_data["created_at"]);
									$date2 = date_create(date('Y-m-d H:i:s'));
									$diff = date_diff($date1, $date2);
									// print_r($diff) ;

									if (!empty($diff->m)) {
										$last_date = $diff->m . " month ago";
									} elseif (!empty($diff->d)) {
										$last_date = $diff->d . " day ago";
									} elseif (!empty($diff->h)) {
										$last_date = $diff->h . " hour ago";
									} else {
										$last_date = $diff->i . " minute ago";
									}

									$monitoring = ($pagespeed_data["monitoring"] == 1) ? "text-success" : "text-danger";
									$reanalyze_toggle = ($pagespeed_data["monitoring"] == 1) ? "" : "disabled";

								?>
									<tr>
										<td><?= $pagespeed_data["platform"] ?><br><a href="<?= $pagespeed_data["website_url"] ?>" target="_blank"><?= $pagespeed_data["website_url"] ?></a></td>
										<td class="fcp"><?= $audits["first-contentful-paint"]["numericValue"] ?></td>
										<td class="lcp"><?= $audits["largest-contentful-paint"]["numericValue"] ?></td>
										<td class="mpf"><?= $audits["max-potential-fid"]["numericValue"] ?></td>
										<td class="cls"><?= round($audits["cumulative-layout-shift"]["numericValue"], 3) ?></td>
										<!-- <td></td>
											<td></td> -->
										<td class="tbt"><?= $audits["total-blocking-time"]["numericValue"] ?></td>
										<td><a href="javascript:void(0);"  class="url-monitoring <?= $monitoring ?>" data-additional="0"><i class="fa fa-check-circle" aria-hidden="true"></i></a></td>
										<td>

											<?php

											// $pid = $pagespeed_data["manager_id"];
											$pid = $pagespeed_data["plan_id"];
											$changeid = $pagespeed_data["id"];
											$sele_qry = $conn->query(" SELECT * FROM plans WHERE id='$pid'");
											$run_qrys = $sele_qry->fetch_assoc();

											// echo($run_qrys);
											// print_r($run_qrys);
											$stype = $run_qrys['s_type'];

											// echo "pid". $pid;
											// echo "stype". $stype;
											// echo 'period'.$website_data["period"];
											// die();

											$daily = '';
											$daily_disable = '';
											if ($website_data["period"] == "daily") {
												$daily = "selected";
											}
											if (!($stype == "Diamond" || $stype == "Pro" || $stype == "Gold" )) {
															$daily_disable = "disabled";
														}

											$weekly = '';
											$weekly_disable = '';
											if ($website_data["period"] == "weekly") {
												$weekly = "selected";
											}
											if (!($stype == "Silver" || $stype == "Gold" || $stype == "Silver" || $stype == "Gold")) {
															$weekly_disable = "disabled";
														}

											$monthlys = '';
											$monthly_enable = '';
											if ($website_data["period"] == "monthly") {
												$monthlys = "selected";
											}
											if (!($stype == "Silver" || $stype == "Gold" || $stype == "Diamond" || $stype == "Pro" || $stype == "Silver" || $stype == "Gold" || $stype == "Free")) {
															$monthly_enable = "disabled";
														}



											?>
											<select class="form-control" onchange="changetime(this);" data-changetime="<?= $changeid ?>">

												<option value="Daily" <?= $daily_disable ?> <?= $daily ?>>Daily(Booster Plan)</option>
												<option value="Weekly" <?= $weekly_disable ?> <?= $weekly ?>>Weekly(Super Plan)</option>
												<option value="monthly" <?= $monthly_enable ?> <?= $monthlys ?>>Monthly</option>
											</select>
											<?php //$pagespeed_data["period"]
											?>
										</td>
										<td class="last-update"><?= $last_date ?></td>
										<td><button type="button" class="btn btn-primary reanalyze-btn" data-website_name="<?= $pagespeed_data["platform"] ?>" data-fcp="<?= $audits["first-contentful-paint"]["numericValue"] ?>" data-lcp="<?= $audits["largest-contentful-paint"]["numericValue"] ?>" data-mpf="<?= $audits["max-potential-fid"]["numericValue"] ?>" data-cls="<?= round($audits["cumulative-layout-shift"]["numericValue"], 3) ?>"  data-ps_tbt="<?= $audits["total-blocking-time"]["numericValue"] ?>"   data-website_url_core="<?= $pagespeed_data["website_url"] ?>" data-additional="0" <?= $reanalyze_toggle ?>><i class="fa fa-refresh" aria-hidden="true"></i></button></td>
										<td><a href="<?= HOST_URL ?>adminpannel/lighthouse-report.php?project=<?= ($_GET["project"]) ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
									</tr>
									<?php

									// get additional urls
									$au_query = $conn->query(" SELECT * FROM `additional_websites` WHERE manager_id = '$user_id' AND website_id = '$project_id' ");

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

												$ps_fcp = $ps_audits["first-contentful-paint"]["numericValue"];
												$ps_lcp = $ps_audits["largest-contentful-paint"]["numericValue"];
												$ps_mpf = $ps_audits["max-potential-fid"]["numericValue"];
												$ps_cls = round($ps_audits["cumulative-layout-shift"]["numericValue"], 3);
												$ps_tbt = $ps_audits["total-blocking-time"]["numericValue"];


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
													$ps_last_date = $diff->i . " minute ago";
												}
											}

											// print_r($ps_query) ;

											$monitoring = ($additional_url["monitoring"] == 1) ? "text-success" : "text-danger";
											$reanalyze_toggle = ($additional_url["monitoring"] == 1) ? "" : "disabled";

									?>
											<tr>
												<td><?= $additional_url["website_name"] ?><br><a href="<?= $additional_url["website_url"] ?>" target="_blank"><?= $additional_url["website_url"] ?></a></td>

												<td class="fcp"><?= $ps_fcp ?></td>
												<td class="lcp"><?= $ps_lcp ?></td>
												<td class="mpf"><?= $ps_mpf ?></td>
												<td class="cls"><?= $ps_cls ?></td>
												<td class="tbt"><?= $ps_tbt ?></td>
												<td><a href="javascript:void(0);" class="url-monitoring <?= $monitoring ?>" data-additional="<?= $additional_url["id"] ?>"><i class="fa fa-check-circle" aria-hidden="true"></i></a></td>
												<td>
													<?php
													//echo $additional_url["period"] ;

													$dailys = '';
													$weeklys = '';
													$monthlyss = '';
													if ($additional_url["period"] == "daily") {
														$dailys = "selected";
													}
													if ($additional_url["period"] == "weekly") {
														$weeklys = "selected";
													}
													if ($additional_url["period"] == "monthly") {
														$monthlyss = "selected";
													}

													?>
													<select class="form-control" onchange="changetime(this);" data-changetime2="<?= $additional_url["id"] ?>">
														<option value="daily" <?= $daily_disable ?> <?= $dailys ?>>Daily(GOLD)</option>
														<option value="weekly" <?= $weekly_disable ?> <?= $weeklys ?>>Weekly(SILVER)</option>
														<option value="monthly" <?= $monthly_enable ?> <?= $monthlyss ?>>Monthly</option>
													</select>
													<?php //$pagespeed_data["period"]
													?>
												</td>
												<td class="last-update"><?= $ps_last_date ?></td>
												<td><button type="button" class="btn btn-primary reanalyze-btn" data-website_name="<?= $additional_url["website_name"] ?>" data-fcp="<?= $ps_fcp; ?>" data-lcp="<?= $ps_lcp ?>" data-mpf="<?= $ps_mpf ?>" data-cls="<?=$ps_cls ?>" data-ps_tbt="<?=$ps_tbt ?>"  data-website_url_core="<?= $additional_url["website_url"] ?>"  data-additional="<?= $additional_url["id"] ?>" <?= $reanalyze_toggle ?>><i class="fa fa-refresh" aria-hidden="true"></i></button></td>
												<td><a href="<?= HOST_URL ?>adminpannel/lighthouse-report.php?project=<?= ($_GET["project"]) ?>&additional=<?= $additional_url["id"] ?>" class="btn btn-primary"><i class="fa-solid fa-arrow-right"></i></a></td>
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
					<div class="page_history">
									<div class="page_history_header"><h4 class="mt-3"> Core Web Vital History</h4></div>
							<?php

							$history_data = getTableData($conn, " website_speed_history t1 INNER JOIN ( SELECT DATE(created_at) AS trade_date, MAX(created_at) AS max_trade_time FROM website_speed_history", "website_id='".$project_id."' GROUP BY DATE(created_at) ) t2 ON t2.trade_date = DATE(t1.created_at) AND t2.max_trade_time = t1.created_at ORDER BY t1.created_at ","",1);
							
							?>
							<div class="table_S">
							<table class="table table-bordered" >
								<thead>
									<tr>
										<th>DATE</th>
										<!-- <th>SITE</th> -->
										<th>PAGE</th>
										<th>FPC</th>
										<th>LCP</th>
										<th>MPF</th>
										<th>CLS</th>
										<th>TBT </th>
										

									</tr>
								</thead>
								<tbody>
									<?php
									if (count($history_data) > 0 ) {

										foreach ($history_data as $key => $value) {
											// code...
									?>
											<tr>
												<td class="site">
													<?php $date = date_create($value['created_at']);
													echo date_format($date, "d-m-Y h:i "); ?>
												</td>
												
												<td><?= $value["website_name"] ?><br><a href="<?= $addwebid ?>" target="_blank"><?= $value["website_url"] ?></a></td>
												<td class="fcp"><?= $value['fcp'] ?></td>
												<td class="lcp"><?= $value['lcp'] ?></td>
												<td class="mpf"><?= $value['mpf'] ?></td>
												<td class="cls"><?= $value['cls'] ?></td>
												<td class="tbt"><?= $value['tbt'] ?></td>
											
											</tr>
											<?php
									} } else { ?>
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
		</div>
	</div>

</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	function changetime(btn) {

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
	
	$(document).ready(function() {
		$("#page-speed-history").hide();

		$("#page-toggle-history").click(function() {
			$("#page-speed-history").toggle();
		});
	});

</script>