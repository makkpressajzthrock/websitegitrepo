<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;

// Include autoloader 
require_once ('../dompdf/autoload.inc.php'); 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 

ob_clean();



$output='
$user_id = $_SESSION["user_id"] ;
$project_id = $_GET["project"] ;

function getIndicator($score) {

	$score = $score*100 ;
	$color = $score < 49 ? " indicator-red " : ( $score < 89 ? " indicator-orange " : " indicator-green " ) ;
	return $color ;
}

function backgroundIndicator($score) {

	$score = $score*100 ;
	$color = $score < 49 ? " background-red " : ( $score < 89 ? " background-orange " : " background-green " ) ;
	return $color ;
}

?>

<?php require_once("inc/style-and-script.php") ; ?>

<style type="text/css">
	/* ----- The actual thing ----- */

	/* Variables */

	:root {
		--rating-size: 5rem;
		--bar-size: 0.3rem;
		--background-color: #e7f2fa;
		--rating-color-default: #2980b9;
		--rating-color-background: #c7e1f3;
		--rating-color-good: #27ae60;
		--rating-color-meh: #f1c40f;
		--rating-color-bad: #e74c3c;
	}

	/* Rating item */
	.rating {
		margin: auto;
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 100%;
		overflow: hidden;

		background: var(--rating-color-default);
		color: var(--rating-color-default);
		width: var(--rating-size);
		height: var(--rating-size);

		/* Basic style for the text */
		font-size: calc(var(--rating-size) / 3);
		line-height: 1;
	}

	/* Rating circle content */
	.rating span {
		position: relative;
		display: flex;
		font-weight: bold;
		z-index: 2;
	}

	.rating span small {
		font-size: 0.5em;
		font-weight: 900;
		align-self: center;
	}

	/* Bar mask, creates an inner circle with the same color as thee background */
	.rating::after {
		content: "";
		position: absolute;
		top: var(--bar-size);
		right: var(--bar-size);
		bottom: var(--bar-size);
		left: var(--bar-size);
		background: var(--background-color);
		border-radius: inherit;
		z-index: 1;
	}

	/* Bar background */
	.rating::before {
		content: "";
		position: absolute;
		top: var(--bar-size);
		right: var(--bar-size);
		bottom: var(--bar-size);
		left: var(--bar-size);
		border-radius: inherit;
		box-shadow: 0 0 0 1rem var(--rating-color-background);
		z-index: -1;
	}

	/* Classes to give different colors to ratings, based on their score */
	.rating.good {
		background: var(--rating-color-good);
		color: var(--rating-color-good);
	}

	.rating.meh {
		background: var(--rating-color-meh);
		color: var(--rating-color-meh);
	}

	.rating.bad {
		background: var(--rating-color-bad);
		color: var(--rating-color-bad);
	}

	.indicator-red {
		color: red !important;
	}

	.indicator-orange {
		color: orange !important;
	}

	.indicator-green {
		color: green !important;
	}

	.background-red {
		background-color: red !important;
	}

	.background-orange {
		background-color: orange !important;
	}

	.background-green {
		background-color: green !important;
	}

	.t_img img {
		width: 100%;
		height: 100%;
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
			<div class="container-fluid  Lighthouse_Report content__up">
				<h1>Lighthouse Report</h1>
				<div class="page_speed_btn">
					<a href="<?=HOST_URL?>adminpannel/page-speed.php?project=<?=$project_id;?>"
						class="btn btn-primary">Page Speed</a>
				</div>
				<div class="alert-status">
					<?php require_once("inc/alert-status.php") ; ?>
				</div>

				<?php


					// for main main project ===========================================
					$boost_website = getTableData( $conn , " boost_website " , " id = "$project_id" AND manager_id = '$user_id' " ) ;
					// print_r($boost_website) ;

					// get latest speed reportg data

					if ( empty($_GET["additional"]) ) {
						// code...
						$query = $conn->query( " SELECT * FROM pagespeed_report WHERE website_id = "$project_id" AND parent_website = 0 ORDER BY id DESC ; " ) ;
					}
					else {
						$additional = $_GET["additional"] ;
						$query = $conn->query( " SELECT * FROM pagespeed_report WHERE website_id = "$additional" AND parent_website = "$project_id" ORDER BY id DESC ; " ) ;
					}


					$pagespeed_report = $query->fetch_assoc() ;
					// print_r($pagespeed_report) ;

					// show all desktop data
					$desktop_categories = unserialize($pagespeed_report["categories"]) ;
					$desktop_audits = unserialize($pagespeed_report["audits"]) ;

					$ps_performance = round($desktop_categories["performance"]["score"]*100,2) ;
					$ps_accessibility = round($desktop_categories["accessibility"]["score"]*100,2) ;
					$ps_best_practices = round($desktop_categories["best-practices"]["score"]*100,2) ;
					$ps_seo = round($desktop_categories["seo"]["score"]*100,2) ;
					$ps_pwa = round($desktop_categories["pwa"]["score"]*100,2) ;

					?>

				<!-- <iframe src="https://pagespeed.web.dev/report?url=https%3A%2F%2Fecommerceseotools.com%2F" width="100%"></iframe> -->

				<div class="profile_tabs">
					<div class="row text-center border-bottom">
						<div class="lh-scores-container">
							<div class="col-md-2">
								<div class="rating">
									<?=$ps_performance?>
								</div>
								<h6>PERFORMANCE</h6>
							</div>
							<div class="col-md-2">
								<div class="rating">
									<?=$ps_accessibility?>
								</div>
								<h6>ACCESSIBILITY</h6>
							</div>
							<div class="col-md-2">
								<div class="rating">
									<?=$ps_best_practices?>
								</div>
								<h6>BEST PRACTICES</h6>
							</div>
							<div class="col-md-2">
								<div class="rating">
									<?=$ps_seo?>
								</div>
								<h6>SEO</h6>
							</div>
							<div class="col-md-2">
								<div class="rating">
									<?=$ps_pwa?>
								</div>
								<h6>Progressive Web App</h6>
							</div>

							<div class="col-md-12 lh-scorescale">
								<div class="row">
									<div class="col-md-4"><i class="fa-solid fa-triangle"></i>&nbsp;0-49</div>
									<div class="col-md-4"><i class="fa-solid fa-square"></i>&nbsp;50-89</div>
									<div class="col-md-4"><i class="fa-solid fa-circle"></i>&nbsp;90-100</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12 border-bottom p-0 my-2">

						<?php
							$fcp_color = getIndicator($desktop_audits["first-contentful-paint"]["score"]) ;
							$si_color = getIndicator($desktop_audits["speed-index"]["score"]) ;
							$lcp_color = getIndicator($desktop_audits["largest-contentful-paint"]["score"]) ;
							$cls_color = getIndicator($desktop_audits["cumulative-layout-shift"]["score"]) ;

							$desktop_i18n = unserialize($pagespeed_report["i18n"]) ;
							// echo "<pre>"; print_r( $desktop_i18n ) ; echo "</pre>";
						?>

						<div class="lh-category-header">
							<div class="text-center">
								<div class="rating">
									<?=$ps_performance?>
								</div>
								<h6>PERFORMANCE</h6>
							</div>

							<div>
								<h6>Metrics</h6>
								<ul>
									<li class="yellow">First Contentful Paint <span
											class="<?=$fcp_color?>"><?=$desktop_audits["first-contentful-paint"]["displayValue"]?></span>
									</li>
									<li class="green">Speed Index <span
											class="<?=$fcp_color?>"><?=$desktop_audits["speed-index"]["displayValue"]?></span>
									</li>
									<li class="yellow">Largest Contentful Paint <span
											class="<?=$fcp_color?>"><?=$desktop_audits["largest-contentful-paint"]["displayValue"]?></span>
									</li>
									<li class="red">Time to Interactive <span class="<?=$fcp_color?>">
											<?=$desktop_audits["interactive"]["displayValue"]?>
										</span>
									</li>
									<li class="red">Total Blocking Time <span
											class="<?=$fcp_color?>"><?=$desktop_audits["total-blocking-time"]["displayValue"]?></span>
									</li>
									<li class="red">Cumulative Layout Shift <span
											class="<?=$fcp_color?>"><?=$desktop_audits["cumulative-layout-shift"]["displayValue"]?></span>
									</li>
								</ul>

								<div class="lh-metrics__disclaimer">
									<?php // echo $desktop_i18n["rendererFormattedStrings"]["varianceDisclaimer"]?>
									<p>Values are estimated and may vary. The <a
											href="https://web.dev/performance-scoring/" target="_blank">performance
											score is calculated</a> directly from these metrics.See calculator<a
											href="https://googlechrome.github.io/lighthouse/scorecalc/#FCP=5094&SI=14865&LCP=13135&CLS=0.3&device=undefined&version=7.1.0"
											target="_blank">See calculator.</a>
									<p>
								</div>

							</div>
							<div class="lh-audit-group__header">
								<p><span>Opportunities --</span>These suggestions can help your page load faster. They
									do not <a href="https://web.dev/performance-scoring/" target="_blank">directly
										affect</a> the Performance score.</p>
							</div>
							<div class="lh-audit-group__header">

								<div class="audit-group__header_s">
									<p>Opportunity</p>
									<p>Estimated Savings</p>
								</div>


								<?php
								
								foreach ($desktop_categories["performance"]["auditRefs"]  as $performanceAuditRefs) {

									if ( (in_array($performanceAuditRefs["id"], ["first-contentful-paint","speed-index","largest-contentful-paint","cumulative-layout-shift","interactive","total-blocking-time"] ))
										
									 ) {
										continue ;
									}
									elseif ( in_array($performanceAuditRefs["id"], ["server-response-time","unused-javascript","offscreen-images","uses-rel-preload","render-blocking-resources","modern-image-formats","unused-css-rules","uses-responsive-images","uses-optimized-images"] ) ) 
									{
										
										// if ($performanceAuditRefs["id"]=="server-response-time") {
											// print_r($performanceAuditRefs) ;
											// 	break;
										// }

										foreach ($desktop_audits as $da_key => $da_value) 
										{
											if ( $performanceAuditRefs["id"] == $da_key ) {

												if( $da_value["score"] == 1 ) { continue ; }

												// details
												// echo"<pre>";print_r($da_value) ;echo"</pre>";

												$color_indicator = getIndicator($da_value["score"]) ;
												$background_indicator = backgroundIndicator($da_value["score"]) ;

												$progress_bar = 100 - ($da_value["score"]*100) ;

												?>
								<div class="accordion red">
									<?=$da_value["title"]?>
									<div class="progress_b"><span class="progress_bar <?=$background_indicator?>"
											style="width: <?=$progress_bar?>%;"></span><span
											class="<?=$color_indicator?>">
											<?=round($da_value["numericValue"]/1000,2)?> s
										</span></div>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<p>Consider lazy-loading offscreen and hidden images after all critical resources
										have finished loading to lower time to interactive. <a href="#">Learn more.</a>
									</p>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															foreach ($da_value["details"]["headings"] as $da_table) {
																?>
													<th>
														<?=$da_table["label"]?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;
																if ( $col_count == 4 ) {
																	?>
												<td class="t_img">
													<?=$da_table["node"]["snippet"]?>
												</td>
												<td class="img_link">
													<?=$da_table["url"]?>
												</td>
												<td class="Resource">
													<?=round($da_table["totalBytes"]/1000,2)?> KiB
												</td>
												<td class="Potential">
													<?=round($da_table["wastedBytes"]/1000,2)?> KiB
												</td>
												<?php
																}
																elseif ( $col_count == 2 ) {
																	?>
												<td class="img_link">
													<?=$da_table["url"]?>
												</td>
												<td class="Resource">
													<?=$da_table["responseTime"]?>
												</td>
												<?php
																}
																else {
																	?>
												<td class="img_link">
													<?=$da_table["url"]?>
												</td>
												<td class="Resource">
													<?=round($da_table["totalBytes"]/1000,2)?> KiB
												</td>
												<td class="Potential">
													<?=round($da_table["wastedBytes"]/1000,2)?> KiB
												</td>
												<?php
																}
																
																echo "</tr>" ;
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php

												break ;
											}
										}
										
									}
								}

								?>

								<!-- <div class="accordion">
									Eliminate render-blocking resources <div class="progress_b"><span
											class="progress_bar"></span><span>5.49 s</span></div>
								</div>
								<div class="panel">
									<p>Consider lazy-loading offscreen and hidden images after all critical resources
										have finished loading to lower time to interactive. <a href="#">Learn more.</a>
									</p>
									<div class="plugin_d">
										<svg viewBox="0 0 122.5 122.5" xmlns="http://www.w3.org/2000/svg">
											<g fill="#2f3439">
												<path
													d="M8.7 61.3c0 20.8 12.1 38.7 29.6 47.3l-25-68.7c-3 6.5-4.6 13.7-4.6 21.4zM96.7 58.6c0-6.5-2.3-11-4.3-14.5-2.7-4.3-5.2-8-5.2-12.3 0-4.8 3.7-9.3 8.9-9.3h.7a52.4 52.4 0 0 0-79.4 9.9h3.3c5.5 0 14-.6 14-.6 2.9-.2 3.2 4 .4 4.3 0 0-2.9.4-6 .5l19.1 57L59.7 59l-8.2-22.5c-2.8-.1-5.5-.5-5.5-.5-2.8-.1-2.5-4.5.3-4.3 0 0 8.7.7 13.9.7 5.5 0 14-.7 14-.7 2.8-.2 3.2 4 .3 4.3 0 0-2.8.4-6 .5l19 56.5 5.2-17.5c2.3-7.3 4-12.5 4-17z" />
												<path
													d="M62.2 65.9l-15.8 45.8a52.6 52.6 0 0 0 32.3-.9l-.4-.7zM107.4 36a49.6 49.6 0 0 1-3.6 24.2l-16.1 46.5A52.5 52.5 0 0 0 107.4 36z" />
												<path
													d="M61.3 0a61.3 61.3 0 1 0 .1 122.7A61.3 61.3 0 0 0 61.3 0zm0 119.7a58.5 58.5 0 1 1 .1-117 58.5 58.5 0 0 1-.1 117z" />
											</g>
										</svg>
										<p>Install a <a href="#">lazy-load WordPress plugin</a> that provides the
											ability to defer any offscreen images, or switch to a theme that provides
											that functionality. Also consider using <a href="#">the AMP plugin.</a></p>
									</div>
									<div class="lh-3p-filter">
										<div class="filter_s">
											<input type="checkbox" disabled><span>Show 3rd-party resources</span>
										</div>
									</div>

									<div class="table_ss">
										<table>
											<thead>
												<th></th>
												<th>URL</th>
												<th>Resource Size
												</th>
												<th>Potential Savings</th>
											</thead>
											<tbody>
												<tr>
													<td class="t_img"><img src="./img/co_regestration.jpg" alt=""></td>
													<td class="img_link">…01/co_regestration.jpg<span
															class="web_name">(makkpress.com)</span></td>
													<td class="Resource">304.1 KiB</td>
													<td class="Potential">304.1 KiB</td>
												</tr>
												<tr>
													<td class="t_img"><img src="./img/co_regestration.jpg" alt=""></td>
													<td class="img_link">…01/co_regestration.jpg<span
															class="web_name">(makkpress.com)</span></td>
													<td class="Resource">304.1 KiB</td>
													<td class="Potential">304.1 KiB</td>
												</tr>
												<tr>
													<td class="t_img"><img src="./img/co_regestration.jpg" alt=""></td>
													<td class="img_link">…01/co_regestration.jpg<span
															class="web_name">(makkpress.com)</span></td>
													<td class="Resource">304.1 KiB</td>
													<td class="Potential">304.1 KiB</td>
												</tr>
												<tr>
													<td class="t_img"><img src="./img/co_regestration.jpg" alt=""></td>
													<td class="img_link">…01/co_regestration.jpg<span
															class="web_name">(makkpress.com)</span></td>
													<td class="Resource">304.1 KiB</td>
													<td class="Potential">304.1 KiB</td>
												</tr>
												<tr>
													<td class="t_img"><img src="./img/co_regestration.jpg" alt=""></td>
													<td class="img_link">…01/co_regestration.jpg<span
															class="web_name">(makkpress.com)</span></td>
													<td class="Resource">304.1 KiB</td>
													<td class="Potential">304.1 KiB</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div> -->

								<!-- END Opportunities -->


								<!-- Diagnostics -->
								<div class="lh-audit-group__header">
									<p><span>Diagnostics --</span>
										More information about the performance of your application. These numbers do not
										<a href="https://web.dev/performance-scoring/" target="_blank">directly
											affect</a> the Performance score.
									</p>
								</div>


								<?php

								foreach ($desktop_categories["performance"]["auditRefs"]  as $performanceAuditRefs) {

									if ( (in_array($performanceAuditRefs["id"], ["first-contentful-paint","speed-index","largest-contentful-paint","cumulative-layout-shift","interactive","total-blocking-time","server-response-time","unused-javascript","offscreen-images","uses-rel-preload","render-blocking-resources","modern-image-formats","unused-css-rules","uses-responsive-images","uses-optimized-images"] ))
										
									 ) {
										continue ;
									}
									elseif ( in_array($performanceAuditRefs["id"], ["unsized-images","uses-long-cache-ttl","dom-size","critical-request-chains","user-timings","resource-summary","largest-contentful-paint-element","layout-shift-elements","font-display","total-byte-weight"] ) ) 
									{
										
										// if ($performanceAuditRefs["id"]=="server-response-time") {
											// print_r($performanceAuditRefs) ;
											// 	break;
										// }

										foreach ($desktop_audits as $da_key => $da_value) 
										{
											if ( $performanceAuditRefs["id"] == $da_key ) {

												if( $da_value["score"] == 1 ) { continue ; }

												// details
												// echo"<pre>";print_r($da_value) ;echo"</pre>";

												$color_indicator = getIndicator($da_value["score"]) ;
												$background_indicator = backgroundIndicator($da_value["score"]) ;

												$progress_bar = 100 - ($da_value["score"]*100) ;

												?>
								<div class="accordion red">
									<?=$da_value["title"]?>
									<span class="<?=$color_indicator?>">
										<?=$da_value["displayValue"]?>
									</span>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															$col_array = [] ;

															foreach ($da_value["details"]["headings"] as $da_table) 
															{
																$label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"] ; 

																$col_array[$label] = $da_table["key"] ;
																?>
													<th>
														<?=$label?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;

															// print_r($col_array) ;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;

																foreach ($col_array as $ca_key => $ca_val) 
																{
																	$td = $da_table[$ca_val] ;

																	if ( $ca_val == "cacheLifetimeMs" ) {
																		$td = round($td/1000,2). "s" ;
																	}
																	elseif ( $ca_val == "totalBytes " ) {
																		$td = round($td/1000,2). "Kb" ;
																	}
																	elseif ( $ca_val == "node" ) {
																		$td = $td["snippet"] ;
																	}

																	echo "<td>".$td."</td>" ;
																}
																
																echo "</tr>" ;
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php

												break ;
											}
										}
										
									}
								}
								?>

							</div>
						</div>

						<!-- -------------------------------------------------------------------- -->
						<!-- Accessibility -->
						<div class="lh-category-header accessibility_s">
							<div class="text-center">
								<div class="rating">
									<?=$ps_accessibility?>
								</div>
								<h6>Accessibility</h6>
							</div>

							<div class="lh-category-header__description">
								These checks highlight opportunities to <a href="https://web.dev/accessibility/"
									target="_blank">improve the accessibility of your
									web app</a>. Only a subset of accessibility issues can be automatically detected so
								manual testing is also encouraged.
							</div>

							<div class="lh-audit-group__header">
								<p><span>NAMES AND LABELS</span></p>
							</div>

							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "a11y-names-labels") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value["details"]) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">
									<div class="audit-group__header_s">
										<p>Low-contrast text is difficult or impossible for many users to read. <a
												rel="noopener" target="_blank" href="https://web.dev/image-alt">Learn
												more</a></p>
									</div>
									<div class="audit-group__header_s">
										<p>Failing Elements</p>
									</div>
									<div class="accessibility_lst">
										<?php

												if ( count($da_value["details"]) > 0 ) {
													
													foreach ($da_value["details"]["items"] as $key => $item) {
														?>
										<div class="accessibility_lst_item">
											<h5>
												<?=$item["node"]["nodeLabel"]?>
											</h5>
											<p>
												<?=$item["node"]["explanation"]?>
											</p>
										</div>
										<?php
													}
												}

												?>
									</div>
								</div>
								<?php
											break ;
										}
									}
										
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<p><span>BEST PRACTICES</span></p>
							</div>

							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "a11y-best-practices") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">
									<div class="audit-group__header_s">
										<p>Low-contrast text is difficult or impossible for many users to read. <a
												rel="noopener" target="_blank" href="https://web.dev/image-alt">Learn
												more</a></p>
									</div>
									<div class="accessibility_lst">
										<?php

												if ( count($da_value["details"]) > 0 ) {
													
													foreach ($da_value["details"]["items"] as $key => $item) {
														?>
										<div class="accessibility_lst_item">
											<h5>
												<?=$item["node"]["nodeLabel"]?>
											</h5>
											<p>
												<?=$item["node"]["explanation"]?>
											</p>
										</div>
										<?php
													}
												}

												?>
									</div>
								</div>
								<?php
											break ;
										}
									}
										
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<p><span>NAVIGATION</span></p>
							</div>

							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "a11y-navigation") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">
									<div class="audit-group__header_s">
										<p>Low-contrast text is difficult or impossible for many users to read. <a
												rel="noopener" target="_blank" href="https://web.dev/image-alt">Learn
												more</a></p>
									</div>
									<div class="accessibility_lst">
										<?php

												if ( count($da_value["details"]) > 0 ) {
													
													foreach ($da_value["details"]["items"] as $key => $item) {
														?>
										<div class="accessibility_lst_item">
											<h5>
												<?=$item["node"]["nodeLabel"]?>
											</h5>
											<p>
												<?=$item["node"]["explanation"]?>
											</p>
										</div>
										<?php
													}
												}

												?>
									</div>
								</div>
								<?php
											break ;
										}
									}
										
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<p><span>TABLES AND LISTS</span></p>
							</div>

							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "a11y-tables-lists") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">
									<div class="audit-group__header_s">
										<p>Low-contrast text is difficult or impossible for many users to read. <a
												rel="noopener" target="_blank" href="https://web.dev/image-alt">Learn
												more</a></p>
									</div>
									<div class="accessibility_lst">
										<?php

												if ( count($da_value["details"]) > 0 ) {
													
													foreach ($da_value["details"]["items"] as $key => $item) {
														?>
										<div class="accessibility_lst_item">
											<h5>
												<?=$item["node"]["nodeLabel"]?>
											</h5>
											<p>
												<?=$item["node"]["explanation"]?>
											</p>
										</div>
										<?php
													}
												}

												?>
									</div>
								</div>
								<?php
											break ;
										}
									}
										
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<div class="accordion yellow Passed_audits">
									Passed audits <!-- (8) -->
								</div>
								<div class="panel">

									<?php
									foreach ($desktop_categories["accessibility"]["auditRefs"]  as $performanceAuditRefs) {

										foreach ($desktop_audits as $da_key => $da_value) 
										{
											if ( $performanceAuditRefs["id"] == $da_key ) {

												if( $da_value["score"] != 1  ) { continue ; }

												?>
									<div class="accordion yellow">
										<?php echo htmlspecialchars($da_value["title"]) ;?>
									</div>
									<div class="panel">
										<?php echo htmlspecialchars($da_value["description"]) ;?>
									</div>
									<?php
												break ;
											}
										}
									}
									?>


								</div>
							</div>
						</div>

						<!-- -------------------------------------------------------------------- -->
						<!-- Best Practices -->
						<div class="lh-category-header lh-gauge__percentage">
							<div class="text-center">
								<div class="rating">
									<?=$ps_best_practices?>
								</div>
								<h6>Best Practices</h6>
							</div>

							<div class="lh-audit-group__header">
								<p><span>User Experience</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["best-practices"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "best-practices-ux") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion yellow">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															$col_array = [] ;

															foreach ($da_value["details"]["headings"] as $da_table) 
															{
																$label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"] ; 

																$col_array[$label] = $da_table["key"] ;
																?>
													<th>
														<?=$label?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;

															// print_r($col_array) ;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;

																foreach ($col_array as $ca_key => $ca_val) 
																{
																	$td = $da_table[$ca_val] ;

																	if ( $ca_val == "cacheLifetimeMs" ) {
																		$td = round($td/1000,2).' s' ;
																	}
																	elseif ( $ca_val == "totalBytes " ) {
																		$td = round($td/1000,2).' Kb' ;
																	}
																	elseif ( $ca_val == "node" ) {
																		$td = $td["snippet"] ;
																	}

																	echo "<td>".$td."</td>" ;
																}
																
																echo "</tr>" ;
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<p><span>Trust and Safety</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["best-practices"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "best-practices-trust-safety") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion yellow">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															$col_array = [] ;

															foreach ($da_value["details"]["headings"] as $da_table) 
															{
																$label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"] ; 

																$col_array[$label] = $da_table["key"] ;
																?>
													<th>
														<?=$label?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;

															// print_r($col_array) ;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;

																foreach ($col_array as $ca_key => $ca_val) 
																{
																	$td = $da_table[$ca_val] ;

																	if ( $ca_val == "cacheLifetimeMs" ) {
																		$td = round($td/1000,2). "s" ;
																	}
																	elseif ( $ca_val == "totalBytes " ) {
																		$td = round($td/1000,2)." Kb ";
																	}
																	elseif ( $ca_val == "node" ) {
																		$td = $td["snippet"] ;
																	}

																	echo "<td>".$td."</td>" ;
																}
																
																echo "</tr>" ;
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<p><span>General</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["best-practices"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "best-practices-general") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion yellow">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															$col_array = [] ;

															foreach ($da_value["details"]["headings"] as $da_table) 
															{
																$label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"] ; 

																$col_array[$label] = $da_table["key"] ;
																?>
													<th>
														<?=$label?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;

															// print_r($col_array) ;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;

																foreach ($col_array as $ca_key => $ca_val) 
																{
																	$td = $da_table[$ca_val] ;

																	if ( $ca_val == "cacheLifetimeMs" ) {
																		$td = round($td/1000,2)." s" ;
																	}
																	elseif ( $ca_val == "totalBytes " ) {
																		$td = round($td/1000,2)." Kb" ;
																	}
																	elseif ( $ca_val == "node" ) {
																		$td = $td["snippet"] ;
																	}

																	echo "<td>".$td."</td>" ;
																}
																
																echo "</tr>" ;
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<p><span>PASSED AUDITS</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["best-practices"]["auditRefs"]  as $performanceAuditRefs) {

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] != 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">
									<?php echo htmlspecialchars($da_value["description"]) ;?>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>
						</div>

						<!-- -------------------------------------------------------------------- -->
						<!-- SEO -->
						<div class="lh-category-header lh-gauge__percentage">
							<div class="text-center">
								<div class="rating">
									<?=$ps_seo?>
								</div>
								<h6>SEO</h6>
								<p>These checks ensure that your page is following basic search engine optimization
									advice. There are many additional factors Lighthouse does not score here that may
									affect your search ranking, including performance on <a
										href="https://web.dev/learn-core-web-vitals" target="_blank">Core Web
										Vitals</a>. <a rel="noopener" target="_blank"
										href="https://support.google.com/webmasters/answer/35769">Learn more</a></p>
							</div>

							<div class="lh-audit-group__header">
								<p><span>CONTENT BEST PRACTICES</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["seo"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "seo-content") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															$col_array = [] ;

															foreach ($da_value["details"]["headings"] as $da_table) 
															{
																$label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"] ; 

																$col_array[$label] = $da_table["key"] ;
																?>
													<th>
														<?=$label?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;

															// print_r($col_array) ;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;

																foreach ($col_array as $ca_key => $ca_val) 
																{
																	$td = $da_table[$ca_val] ;

																	if ( $ca_val == "cacheLifetimeMs" ) {
																		$td = round($td/1000,2)." s" ;
																	}
																	elseif ( $ca_val == "totalBytes " ) {
																		$td = round($td/1000,2)." Kb" ;
																	}
																	elseif ( $ca_val == "node" ) {
																		$td = $td["snippet"] ;
																	}

																	echo "<td>".$td."</td>" ;
																}
																
																echo "</tr>" ;
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<p><span>CRAWLING AND INDEXING</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["seo"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "seo-crawl") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															$col_array = [] ;

															foreach ($da_value["details"]["headings"] as $da_table) 
															{
																$label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"] ; 

																$col_array[$label] = $da_table["key"] ;
																?>
													<th>
														<?=$label?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;

															// print_r($col_array) ;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;

																foreach ($col_array as $ca_key => $ca_val) 
																{
																	$td = $da_table[$ca_val] ;

																	if ( $ca_val == "cacheLifetimeMs" ) {
																		$td = round($td/1000,2). "s" ;
																	}
																	elseif ( $ca_val == "totalBytes " ) {
																		$td = round($td/1000,2)." Kb" ;
																	}
																	elseif ( $ca_val == "node" ) {
																		$td = $td["snippet"] ;
																	}

																	echo "<td>".$td."</td>" ;
																}
																
																echo "</tr>";
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>


							<div class="lh-audit-group__header">
								<p><span>PASSED AUDITS</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["seo"]["auditRefs"]  as $performanceAuditRefs) {

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] != 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">
									<?php echo htmlspecialchars($da_value["description"]) ;?>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>
						</div>

						<!-- -------------------------------------------------------------------- -->
						<!-- Progressive Web App -->
						<div class="lh-category-header lh-gauge__percentage">
							<div class="text-center">
								<div class="rating">
									<?=$ps_pwa?>
								</div>
								<h6>Progressive Web App</h6>
								<p>These checks validate the aspects of a Progressive Web App.<a rel="noopener"
										target="_blank"
										href="https://developers.google.com/web/progressive-web-apps/checklist">Learn
										more</a></p>
							</div>

							<div class="lh-audit-group__header">
								<p><span>Installable</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["pwa"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "pwa-installable") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															$col_array = [] ;

															foreach ($da_value["details"]["headings"] as $da_table) 
															{
																$label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"] ; 

																$col_array[$label] = $da_table["key"] ;
																?>
													<th>
														<?=$label?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;

															// print_r($col_array) ;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;

																foreach ($col_array as $ca_key => $ca_val) 
																{
																	$td = $da_table[$ca_val] ;

																	if ( $ca_val == "cacheLifetimeMs" ) {
																		$td = round($td/1000,2)." s ";
																	}
																	elseif ( $ca_val == "totalBytes " ) {
																		$td = round($td/1000,2)." Kb" ;
																	}
																	elseif ( $ca_val == "node" ) {
																		$td = $td["snippet"] ;
																	}

																	echo "<td>".$td."</td>" ;
																}
																
																echo "</tr>" ;
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>

							<div class="lh-audit-group__header">
								<p><span>PWA Optimized</span></p>
							</div>
							<div class="lh-audit-group__header">

								<?php

								foreach ($desktop_categories["pwa"]["auditRefs"]  as $performanceAuditRefs) {

									if ($performanceAuditRefs["group"] != "pwa-optimized") {
										continue ;
									}

									foreach ($desktop_audits as $da_key => $da_value) 
									{
										if ( $performanceAuditRefs["id"] == $da_key ) {

											if( $da_value["score"] == 1  ) { continue ; }

											// details
											// echo"<pre>";print_r($da_value) ;echo"</pre>";
											?>
								<div class="accordion green">
									<?php echo htmlspecialchars($da_value["title"]) ;?>
								</div>
								<div class="panel">

									<?php
													// echo "<pre>"; print_r( $da_value["details"] ) ; echo "</pre>";
													?>

									<div class="table_ss">
										<table>
											<thead>
												<tr>
													<?php
															$col_count = 0 ;
															$col_array = [] ;

															foreach ($da_value["details"]["headings"] as $da_table) 
															{
																$label = empty($da_table["label"]) ? $da_table["text"] : $da_table["label"] ; 

																$col_array[$label] = $da_table["key"] ;
																?>
													<th>
														<?=$label?>
													</th>
													<?php
																$col_count++ ;
															}

															// echo "col_count : ".$col_count;

															// print_r($col_array) ;
															?>
												</tr>
											</thead>
											<tbody>
												<?php
															foreach ($da_value["details"]["items"] as $da_table) 
															{
																echo "<tr>" ;

																foreach ($col_array as $ca_key => $ca_val) 
																{
																	$td = $da_table[$ca_val] ;

																	if ( $ca_val == "cacheLifetimeMs" ) {
																		$td = round($td/1000,2)." s" ;
																	}
																	elseif ( $ca_val == "totalBytes " ) {
																		$td = round($td/1000,2)." Kb" ;
																	}
																	elseif ( $ca_val == "node" ) {
																		$td = $td["snippet"] ;
																	}

																	echo "<td>".$td."</td>" ;
																}
																
																echo "</tr>" ;
															}

															?>

											</tbody>
										</table>
									</div>
								</div>
								<?php
											break ;
										}
									}
								}
								?>
							</div>

							<div> <strong>Additional items to manually check -</strong>
								<?php echo  htmlspecialchars($desktop_categories["pwa"]["manualDescription"]) ; ?>
							</div>


						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

</body>

<script>
	/*
	Conic gradients are not supported in all browsers (https://caniuse.com/#feat=css-conic-gradients), so this pen includes the CSS conic-gradient() polyfill by Lea Verou (https://leaverou.github.io/conic-gradient/)
	*/

	// Find al rating items
	const ratings = document.querySelectorAll(".rating");

	// Iterate over all rating items
	ratings.forEach((rating) => {
		// Get content and get score as an int
		const ratingContent = rating.innerHTML;
		const ratingScore = parseInt(ratingContent, 10);

		// Define if the score is good, meh or bad according to its value
		const scoreClass =
			ratingScore < 49 ? "bad" : ratingScore < 89 ? "meh" : "good";

		// Add score class to the rating
		rating.classList.add(scoreClass);

		// After adding the class, get its color
		const ratingColor = window.getComputedStyle(rating).backgroundColor;

		// Define the background gradient according to the score and color
		const gradient = `background: conic-gradient(${ratingColor} ${ratingScore}%, transparent 0 100%)`;

		// Set the gradient as the rating background
		rating.setAttribute("style", gradient);

		// Wrap the content in a tag to show it above the pseudo element that masks the bar
		rating.innerHTML = `<span>${ratingScore} ${ratingContent.indexOf("%") >= 0 ? "<small>%</small>" : ""
			}</span>`;
	});

</script>

<script>
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function () {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.display === "block") {
				panel.style.display = "none";
			} else {
				panel.style.display = "block";
			}
		});
	}
</script>

</html>';

// echo $output;


$pdf_name ="Report.pdf" ;


// Instantiate and use the dompdf class 
$dompdf = new Dompdf();
$dompdf->loadHtml($output); 
$dompdf->setPaper('A4', 'portrait'); 
$dompdf->render(); 
$files = $dompdf->output();
file_put_contents('generate-page-report/'.$pdf_name, $files);
// $dompdf->stream($pdf_name, array("Attachment" => 0)); 

?>




