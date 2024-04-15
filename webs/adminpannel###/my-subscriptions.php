<?php

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php');

// check sign-up process complete
// checkSignupComplete($conn) ;


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;


$YesUpgrade =0;

		if(($row['user_type'] == "AppSumo" || $row['user_type'] == "Dealify" || $row['user_type'] == "DealFuel") && $row['sumo_new'] ==1){
			$YesUpgrade =1;
			$Sumocode1 =  $row['sumo_code'];
			$Sumocode2 =  $row['sumo_code_2'];
			$Sumocode3 =  $row['sumo_code_3'];
				if($Sumocode1!="" && $Sumocode2!="" && $Sumocode3!="")
				{
					 $YesUpgrade =0;
				}			

		}


		$plan_country = "";
		if($row['country'] !=""){
			if($row['country'] != "101"){
				$plan_country = "-us";
			}
		}
		elseif($row['country_code'] != "+91"){
			$plan_country = "-us";
		}
// Show Expire message //
	include("error_message_bar_subscription.php");
// End Show Expire message //	

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
 $user_id = $_SESSION['user_id'];

$free = getTableData( $conn , " user_subscriptions_free " , " user_id ='".$_SESSION['user_id']."' and status = 1" ) ;
	


$rowSubs = getTableData( $conn , " user_subscriptions " , " user_id ='".$_SESSION['user_id']."' ","order by id desc",1 ) ;

$rowSubs_cancled = getTableData( $conn , " user_subscriptions " , " user_id ='".$_SESSION['user_id']."' and is_active = 0 ","order by id desc",1 ) ;

$rowSubs_websites = getTableData( $conn , " boost_website " , " manager_id ='".$_SESSION['user_id']."' and plan_type <> 'Free'  ","order by id desc",1 ) ;


$subs = "";
// echo '<pre>';
// print_r($rowSubs_cancled);



$manager_id=$_SESSION['user_id'];

$qur_hide="select * from admin_users where id='$manager_id'";
$sele_qr_hide= mysqli_query($conn,$qur_hide);
$run_qr_hide= mysqli_fetch_array($sele_qr_hide);

?>
<?php require_once("inc/style-and-script.php"); ?>
		<style type="text/css">
			#getcsv {
			float: right;
			margin-bottom: 1em;
			}
			.custom-tabel .display{padding-top: 20px;}
			.custom-tabel .display th{min-width: 50px;}
			table.display.dataTable.no-footer {
			width: 1600px !important;
			}
			.menu{
				list-style: none;
				display: flex;
				margin: 5px;
				justify-content: space-around;
			}
			.Payment_method input{
				width: 100%;
				padding: 12px;
				border: 1px solid #ccc;
				border-radius: 3px;
			}
			.Payment_method label{
				margin-bottom: 10px;
				display: block;

			}
			.payment_method_btn_wrap{
				width: 10%;
			}
			.text-h{   
				font-size: 25px;
				text-align: center;
    	}
		.subscribe_cover1 {
    display: inline-table;
    width: calc(33% - 7px);
    border-radius: 8px;
	box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    background: #fff;
	overflow: hidden;
	padding:15px;

}
			 .Polaris-Card__Section ul{
				list-style: none;
/*				text-align: center;*/
				display: flex;
				flex-direction: column;
				margin: 0;
				position: relative;}
    	 .Polaris-Card__Section li{
					margin: 0 0 10px;
					position: relative;
					font-size: 15px;
					font-weight: 500;
					margin: 7px 0;
					color: #1d1d1bc7;
					text-transform: capitalize;}

				
	
	/* The message box is shown when the user clicks on the password field */

</style>
</head>

<body class="custom-tabel">
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>

		<!-- Page content wrapper-->
		<div id="page-content-wrapper">

			<?php require_once("inc/topbar.php"); ?>

			<!-- Page content-->
			<div class="container-fluid content__up my-subsciptions">
				<h1 class="mt-4">Manage Subscriptions</h1>
				<?php require_once("inc/alert-status.php") ; 
				?>
				<div id="custom_nav">
				<ul class="menu profile_tabs">

						<?php
						if ($run_qr_hide["userstatus"] == "manager") {
							?>
							<li><a href="<?=HOST_URL."adminpannel/my-subscriptions.php" ?>" id="my_plan"><button type="button" data-select="my plan" class="nav_btn nav_btn7 active"><svg height="24" id="myPlanSVG" fill="currentColor" version="1.1" viewBox="0 0 24 24" width="24"	 xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg">
											<defs id="defs2">
												<rect height="7.0346723" id="rect2504" width="7.9207187" x="-1.1008456" y="289.81766" />
											</defs>
											<g id="g2206" transform="translate(-0.066406)">
												<path d="m -72.986328,81.382812 v 1.5 c -1.837891,0 -3.675781,0 -5.513672,0 v 19.734378 h 15 v -2.26758 c 0.167079,0.0151 0.329005,0.0469 0.5,0.0469 3.104706,0 5.632815,-2.528102 5.632812,-5.632808 10e-7,-3.104707 -2.528105,-5.63086 -5.632812,-5.63086 -0.170566,0 -0.333281,0.02802 -0.5,0.04297 v -6.292969 c -1.830078,0 -3.660156,0 -5.490234,0 v -1.5 z m 1,1 h 1.996094 v 1.5 H -68 v 1 h -6 v -1 h 2.013672 z m -5.513672,1.5 h 2.5 v 2 h 8 v -2 h 2.5 v 5.472657 c -1.891187,0.527127 -3.39012,2.003573 -3.919922,3.894531 h -3.447266 v 1 h 3.259766 c -0.01546,0.169613 -0.02539,0.340102 -0.02539,0.513672 0,1.204638 0.384044,2.319294 1.03125,3.236328 h -4.265626 v 1 h 5.167969 c 0.62502,0.546512 1.379018,0.937728 2.199219,1.16797 v 1.44922 h -13 z m 14.5,6.25 c 2.564267,0 4.632813,2.066593 4.632812,4.63086 0,2.564266 -2.068546,4.632812 -4.632812,4.632812 -2.564266,0 -4.632812,-2.068546 -4.632812,-4.632812 -10e-7,-2.564267 2.068545,-4.630857 4.632812,-4.63086 z m -0.5,1.5 v 4 h 4 v -1 h -3 v -3 z" id="path2515" fill="currentColor" style="color:currentColor;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:currentColor;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:currentColor;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:currentColor;stop-opacity:1" transform="translate(80,-80)" />
												<path d="m 5.5,2.8828125 v 1 h 2.234375 v -1 z" id="path2527" fill="currentColor" style="color:currentColor;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:currentColor;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:currentColor;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:currentColor;stop-opacity:1" />
												<path d="m 4,12.25 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 -1.0442708,0 -2.0885417,0 -3.1328125,0 z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.333333 0,0.666667 0,1 -0.3776042,0 -0.7552083,0 -1.1328125,0 0,-0.333333 0,-0.666667 0,-1 z" id="path2499" fill="currentColor" style="color:currentColor;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:currentColor;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:currentColor;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:currentColor;stop-opacity:1" />
												<path d="m 4,7.5 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 C 6.0885417,7.5 5.0442708,7.5 4,7.5 Z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.3333333 0,0.6666667 0,1 C 5.7552083,9.5 5.3776042,9.5 5,9.5 5,9.1666667 5,8.8333333 5,8.5 Z" id="path2503" fill="currentColor" style="color:currentColor;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:currentColor;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:currentColor;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:currentColor;stop-opacity:1" />
												<path d="m 4,17 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 C 6.0885417,17 5.0442708,17 4,17 Z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.333333 0,0.666667 0,1 C 5.7552083,19 5.3776042,19 5,19 5,18.666667 5,18.333333 5,18 Z" id="path2507" fill="currentColor" style="color:currentColor;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:currentColor;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:currentColor;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:currentColor;stop-opacity:1" />
												<path d="m 8.1328125,8.5 c 0,0.3333333 0,0.6666667 0,1 1.9999995,0 3.9999995,0 5.9999995,0 0,-0.3333333 0,-0.6666667 0,-1 -2,0 -4,0 -5.9999995,0 z" id="path2535" fill="currentColor" style="color:currentColor;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:currentColor;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:currentColor;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1px;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:currentColor;stop-opacity:1" />
											</g>
										</svg><span>My Subscriptions</span></button></a>
							</li>
							<?php
						}
						?>

				<li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=profile" id="profile"><button type="button" data-select="Profile" class=" nav_btn nav_btn1 "><i class="las la-user"></i><Span>My Account</Span></button></a></li>
						<?php

						if ($run_qr_hide["userstatus"] == "manager") {

						?>
							<li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=payment" id="payment_btn"><button type="button" data-select="Payment" class="nav_btn nav_btn2"><i class="las la-money-bill"></i><span>Payment Method</span></button></a></li>
						<?php
						}
						?>
						<?php
						if ($run_qr_hide["userstatus"] == "manager") {

						?>
							<li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=teams" id="teams"><button type="button" data-select="Teams" class="nav_btn nav_btn3"><i class="las la-users"></i><span>Teams</span></button></a></li>
						<?php
						}
						?>
						<li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=security" id="security"><button type="button" data-select="Security" class="nav_btn nav_btn4"><i class="las la-shield-alt"></i><span>Security</span></button></a></li>
						<?php
						if ($run_qr_hide["userstatus"] == "manager") {

						?>
							<!-- <li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=subscriptions" id="subscriptions"><button type="button" data-select="Subscribe" class=" nav_btn nav_btn5"><i class="las la-bell"></i><span>Subscriptions</span></button></a></li>
							 -->
							<li><a href="<?= HOST_URL . "adminpannel/billing-dashboard.php" ?>"><button type="button" class="nav_btn nav_btn6"><i class="las la-file-alt"></i><span>Invoices</span></button></a></li>
						<?php
						}
						?>

					</ul>
				</div>
			 
				<div class="profile_tabs">
				

						<div class=" tab my__plan"  id="my__plans">
					<a href="<?= HOST_URL ?>adminpannel/add-website.php" class="btn btn-primary">Add New Website</a>
					<div class="table_S">
						<table class="speedy-table table " id="speedy-table">
							<thead>
								<tr>
									<th>S. No</th>
									<th>Plan Name</th>
									<th>Paid Amount</th>
									<th>Next Paid Amount</th>
									<th>Interval</th>
									<th>Site Limit</th>
									<th>Platform</th>
									<th>Site URL</th>
									<th>Plan Started At</th>
									<th>Next Payment On</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sno = 1;

								// print_r($free) ;

								if (count($free) > 0) {
									// $sno = 1 ;

									 $subsc_id = base64_encode($free['id']);
									$plan = getTableData($conn, " plans ", " id ='" . $free['plan_id'] . "' ");
									$site = getTableData($conn, " boost_website ", "manager_id='" . $user_id . "' and subscription_id ='" . $free['id'] . "'");

									// print_r($site);

									
									$timedy = 	$free['plan_start_date'];

									$vartime = strtotime($timedy);

									$datetimecon = date("F d, Y H:i", $vartime);

								?>
									<tr>
										<td><?= $sno ?></td>
										<td><?= $plan['name']." ( Basic )" ?></td>
										<td>Basic</td>
										<td>---</td>
										<td>Days</td>
										<td>Not Available</td>
										<td><?=$site['platform']?></td>
										<td><?=$site['website_url']?></td>
										<td><?= $datetimecon; ?></td>
										<td>For 7 Days Trial</td>
										<td><div class="trial" >Trial</div></td>
										<td>
											<form method="POST" action="<?= HOST_URL ?>payment/init.php">
											    <input type="hidden" name="subscription" value="<?=$site['plan_id']?>">
											    <input type="hidden" name="change_id" value="">
											    <input type="hidden" name="count_site" value="1">
											    <input type="hidden" name="sid_id" value="<?=$site['id']?>">
                                                <input type="hidden" name="website_url" value="<?php echo $site['website_url'];?>" />
                                                <input type="hidden" name="website_id" value="<?php echo $site['id']; ?>" />
											                                    
											<button data-plan-id="2" class="btn btn-success">
											<span class="card__plan__button__text__wrapper">
											  <span class="">Pay Now</span>
											</span>
											</button>
											</form>

											<a href="<?= HOST_URL ?>plan<?=$plan_country?>.php?sid=<?= base64_encode($site['id']) ?>" class="btn btn-success">Change Plan</a>
											<!-- <a href="<?= HOST_URL ?>adminpannel/manage-website.php?sid=<?= $subsc_id ?>" class="btn btn-success">Manage</a> -->
										</td>

									</tr>
									<?php
									$sno++;
								}

								if (count($rowSubs_websites) > 0) {
									// $sno = 1 ;
									foreach ($rowSubs_websites as $subs) {
										$subsc_id = base64_encode($subs['id']);
										$subscription_id = base64_encode($subs['subscription_id']);
										$plan = getTableData($conn, " plans ", " id ='" . $subs['plan_id'] . "' ");
										$site = getTableData($conn, " user_subscriptions ", "user_id='" . $user_id . "' and id ='" . $subs['subscription_id'] . "'");
										// echo "<pre>";
										 // print_r($site);
										 // die;

										// print_r($row) ;
										$newappsumo = 0;

										if ( $subs['subscription_id'] == "111111" || $subs['subscription_id'] == 111111 ) {
											
											$newappsumo = 0 ;
											if( $row["user_type"] == "Dealify"  || $row['user_type'] == "DealFuel") {
												$newappsumo = 1 ;
											}

											if( $row["user_type"] == "AppSumo"  && $row['sumo_new'] == "1") {
												$newappsumo = 1 ;
											}

										}



										if(count($site)<=0)
										{
											$plan['name'] = "---";
											$site['paid_amount'] = "---";
											$site['paid_amount_currency'] = "";
											$site['plan_period_start'] = "";
											$site['plan_period_end'] = "";
											$site['plan_interval'] = "---";
											$site['site_count'] = '---';

											
										}

									?>
										<tr>
											<td><?= $sno ?></td>
											<td><?php if($plan['name']=='Free'){echo 'Basic Plan';}else{ echo $plan['name'];} ?></td>
											<td><?= $site['paid_amount'] . " " . strtoupper($site['paid_amount_currency']) ?></td>
											<td>
												<?php 
													$amt = $site['plan_price'];
													$amt =  number_format((float)$amt, 2, '.', ''); 
												?>
												<?= $amt . " " . strtoupper($site['paid_amount_currency']) ?>
													
											</td>
											<td><?php 

													
													if($site['plan_interval'] == "Month Free"){
														        $sqlA = "SELECT * FROM coupons where code='".$site['discount_code_id']."'";
														        $resultA = mysqli_query($conn, $sqlA);
														        if(mysqli_num_rows($resultA) > 0){
														        	$coupons = mysqli_fetch_assoc($resultA);
														        	echo $coupons['duration'].' '.ucwords($site['plan_interval']);
														        }
														        else{
														        	echo ucwords($site['plan_interval']);
														        }

													}else{
														echo ucwords($site['plan_interval']);
													}

											 ?></td>
											<td>
												<?php
												$addsite = 0;
												$av = $site['site_count'];
												if($site['site_count'] == '---'){
													echo $site['site_count'];
													 
												}
												// else if ($site_count['is_active'] == 0) {
												// 	echo 'Not Available';
												// } else if ($av >= 1) {
												// 	echo 'Available';
												// 	$addsite = 1;
												// }
												 else {
													echo 'Not Available';
													 
												}


												?>

											</td>
								 
									<td><?=$subs['platform']?></td>
									<td><?=$subs['website_url']?></td>
								 

											<td><?php

												if($site['plan_period_start']!=""){
													$timedy3 = $site['plan_period_start'];
													$vartime3 = strtotime($timedy3);

													$datetimecon3 = date("F d, Y H:i", $vartime3);
													echo $datetimecon3;
												}
												else{
													echo "---";
												}
												?>
													
											</td>
											<td>

												<?php 
												if($site['plan_period_end']!=""){
												$timedy4 = $site['plan_period_end'];
												$vartime4 = strtotime($timedy4);

												$datetimecon4 = date("F d, Y H:i", $vartime4);
												echo $datetimecon4;
												}
												else{
													echo "---";
												}


												?>
													
												</td>
											<td>

												<span class="bg-success"><?php
												
													if($site['site_count'] == '---'){
														echo "<span class='in_active_badge'>In Active</span>";
													}
													else if ($site["is_active"] == 1) {
														echo "<span class='active_badge'>Active</span>";
														} 
													else {
														echo "<span class='cancelled_badge'>Canceled</span>";
													}
												?>

												</span>

											</td>
											<td>
											
											<!-- <?php if($addsite == 1){ ?>
												<a href="<?= HOST_URL ?>adminpannel/add-website.php?sid=<?= $subsc_id ?>" class="btn btn-primary">Add website</a>
											<?php } ?> -->

											<?php 

											if($site["is_active"] == 1) { 
												?>
												<a href="<?= HOST_URL ?>adminpannel/subscription.php?sid=<?= $subscription_id ?>" class="btn btn-primary">View</a>

												<?php if($site['plan_interval'] != "Lifetime"){ ?>
												<a href="<?= HOST_URL ?>plan<?=$plan_country?>.php?change-sid=<?= $subsc_id ?>&sid=<?= $subsc_id ?>"  class="btn btn-primary">Change Plan</a> 
												<?php } ?>		

												<!-- <a href="<?= HOST_URL ?>adminpannel/manage-website.php?sid=<?= $subsc_id ?>" class="btn btn-success">Manage</a> -->
												<?php 
											} 
											else { 

												// echo "sm=".$newappsumo;

												if($newappsumo == 0){
													?>
													<a href="<?= HOST_URL ?>plan<?=$plan_country?>.php?sid=<?= $subsc_id ?>"  class="btn btn-primary">Subscribe Now</a> 
													<?php 
												} 
												else { ?>
												<a href="javascript:void(0);"  site_id="<?=base64_decode($subsc_id)?>"  class="btn btn-primary pay_now_add_code">Add Code</a> 

												<?php } ?>

												<?php 
											} ?>


											<?php if($YesUpgrade==1 && $site['plan_interval'] == "Lifetime"){?>
											<li class="btn btn-primary pay_now_add_code" site_id="<?=base64_decode($subsc_id)?>" >Upgrade</li>
											<?php } ?>												
											</td>

										</tr>
									<?php
										$sno++;
									}


// cancled plan start


								
									foreach ($rowSubs_cancled as $subs) {

										$subsc_id = base64_encode($subs['id']);
										$subscription_id = base64_encode($subs['id']);
										$plan = getTableData($conn, " plans ", " id ='" . $subs['plan_id'] . "' ");
										$site = getTableData($conn, " boost_website ", "manager_id ='" . $user_id . "' and subscription_id ='" . $subs['id'] . "'");
										 
										if(count($site)<=0)
										{
											 
											 
											 
											$site['plan_period_start'] = "";
											$site['plan_period_end'] = "";
											$site['plan_interval'] = "---";
											$site['site_count'] = '---';
										}

									?>
										<tr>
											<td><?= $sno ?></td>
											<td><del><?= $plan['name'] ?></del></td>
											<td><del><?= $subs['paid_amount'] . " " . strtoupper($subs['paid_amount_currency']) ?></del></td>
											<td>---</td>
											<td><del><?= ucwords($subs['plan_interval']) ?></del></td>
											<td>
												<?php echo '---'; ?>

											</td>
								 
									<td><del><?= $site['platform'] ?></del></td>
									<td><del><?= $site['website_url'] ?></del></td>
								 

											<td><?php

												if($subs['plan_period_start']!=""){
													$timedy3 = $subs['plan_period_start'];
													$vartime3 = strtotime($timedy3);

													$datetimecon3 = date("F d, Y H:i", $vartime3);
													echo '<del>'.$datetimecon3.'</del>';
												}
												else{
													echo "---";
												}
												?>
													
											</td>
											<td>

												<?php echo "---"; ?>
													
												</td>
											<td>

												<span class="bg-success green p-1 px-2 rounded text-light">
													<span class='cancelled'>Canceled</span>
												</span>

											</td>
											<td>
				 
										 
													<a href="<?= HOST_URL ?>adminpannel/subscription.php?sid=<?= $subscription_id ?>" class="btn btn-primary">View</a>

										<?php if($YesUpgrade==1){?>
													<li class="nav-item pay_now_add_code" site_id="<?=$base64_decode($subscription_id)?>"  >Upgrade</li>
										<?php } ?>			
												 

										</tr>
									<?php
										$sno++;
									}

//  Cancled Plans

								} 

								else {
									// echo $sno;
									if ($sno < 1) {
									?><tr>
											<td colspan="4"> No Data found.</td>
										</tr><?php
											}
										}

												?>

							</tbody>
						</table>
					</div>
				</div>


			</div>
		</div>
	</div>
	<script type="text/javascript">
		$("#speedy-table").DataTable();
	</script>

</body>

</html>