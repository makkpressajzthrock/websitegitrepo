<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if ( empty($_SESSION['user_id']) || empty($_SESSION['role']) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

if ( isset($_POST["save-changes"]) ) {

	// print_r($_POST) ;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($fname) || empty($lname) || empty($phone) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	else {

		$columns = " firstname = '$fname' , lastname = '$lname' , phone = '$phone' " ;

		if ( updateTableData( $conn , " admin_users " , $columns , " id = '".$_SESSION['user_id']."' " ) ) {
			$_SESSION['success'] = "Profile details are updated successfully!" ;
		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}
	}

	header("location: ".HOST_URL."adminpannel/edit-profile.php") ;
	die() ;
}


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>
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
		</style>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>
<?php require_once("inc/style-and-script.php"); ?>
				<!-- Page content-->
				<div class="container-fluid content__up manager_view">
					<?php require_once("inc/alert-status.php") ; ?>
					<h1>Owner</h1>
                    <div class="back_btn_wrap">
					<a href="<?=HOST_URL."adminpannel/managers.php"?>" class="btn btn-primary">Back</a>
		            </div>
					<div class="profile_tabs dashboard_sn owner">
					<div class="table speedy-table owner_tabel">
						  
						
							<?php

							$manager_data = getTableData( $conn , " admin_users " , " userstatus NOT LIKE 'admin' AND id = '".base64_decode($_GET["manager"])."' " ) ;

							$resend_code = getTableData( $conn , " user_confirm " , " user_id = '".$manager_data["id"]."' " ) ;

							if($manager_data["country"]!=""){
							$country = getTableData( $conn , " list_countries " , " id = '".$manager_data["country"]."' " ) ;
							$city = getTableData( $conn , " list_cities " , " id = '".$manager_data["city"]."' " ) ;
							$state = getTableData( $conn , " list_states " , " id = '".$manager_data["state"]."' " ) ;
							}
							else{
								$cc = str_replace("+","",$manager_data["country_code"]); 
								$country = getTableData( $conn , " list_countries " , " phonecode = '".$cc."' " ) ;
							}
								$steps = getTableData( $conn , " flow_step " , " user_id = '".$manager_data["id"]."' " ) ;		
							?>
							<div class="data">
								<div>Fullname</div>
								<div><?=$manager_data["firstname"].' '.$manager_data["lastname"];?></div>
							</div>

							<div class="data">
								<div>Email</div>
								<div><a href="mailto:<?=$manager_data["email"]?>" ><?=$manager_data["email"]?></a></div>
							</div>

							<div class="data">
								<div>Phone No</div>
								<div><a href="tel:<?='('.$manager_data["country_code"].') '.$manager_data["phone"]?>" ><?='('.$manager_data["country_code"].') '.$manager_data["phone"]?></a></div>
							</div>

							<div class="data">
								<div>Registration Date</div>
								<div><?php         $timedy= $manager_data["created_at"];
                                           $vartime = strtotime($timedy);

                                             $datetimecon= date("F d, Y H:i", $vartime); echo $datetimecon; ?></div>
							</div>
							
							<?php

							$address_line_1 = $manager_data["address_line_1"] ; 
							$address_line_2 = $manager_data["address_line_2"] ; 
							$cityName = $city["cityName"] ;
							$statename = $state["statename"] ;
							$zipcode = $manager_data["zipcode"] ;

							$manager_id = base64_decode($_GET["manager"]) ;
							$ba_query = $conn->query(" SELECT * FROM `billing-address` WHERE `manager_id` LIKE '".$manager_id."' ORDER BY `id` DESC LIMIT 1; ") ;

							if ( $ba_query->num_rows > 0 ) {

								$ba_data = $ba_query->fetch_assoc() ;
								$address_line_1 = $ba_data["address"] ;
								$address_line_2 = $ba_data["address_2"] ;

								$cityName = $ba_data["city"] ;
								$statename = $ba_data["state"] ;
								$zipcode = $ba_data["zip"] ;
							}

							?>

							<div class="data">
								<div>Full Address</div>
								<div><?=$address_line_1.' '.$address_line_2;?></div>
							</div>

							<div class="data">
								<div>City</div>
								<div><?=$cityName?></div>
							</div>

							<div class="data">
								<div>State</div>
								<div><?=$statename?></div>
							</div>

							<div class="data">
								<div>ZipCode</div>
								<div><?=$zipcode?></div>
							</div>

							<div class="data">
								<div>Country</div>
								<div><?=$country["name"]?></div>
							</div>

							<div class="data">
								<div>Describes Your Company</div>
								<div><?=$steps["describes_your_company"]?></div>
							</div>

							<div class="data">
								<div>Website Category</div>
								<div><?=$steps["website_category"]?></div>
							</div>

							<div class="data">
								<div>Platforms</div>
								<div><?=$steps["platforms"]?></div>
							</div>

							<div class="data">
								<div>Source</div>
								<!-- //123 -->
								<div><?=$steps["source"]?> <?php echo isset($steps['other_source_name'])? "(".$steps['other_source_name']. ")" : ''; ?></div>
							</div>
							<div class="data">
								<div>Resend Code</div>
								<div><?=$resend_code["requests"]?></div>
							</div>

					</div>
					
					<h4> Plan Details- </h4>
					<div class="tabels_wrapper">
			
			           
					  
					
							<?php

							
						$id_manager=base64_decode($_GET["manager"]);
	  
	                    $qry = "select * from boost_website where manager_id= '$id_manager'";
						 
						$connect_qry= mysqli_query($conn, $qry);
				
							?>
							
							
							<?php 
							     
							 while($web1=mysqli_fetch_array($connect_qry))

							 {

							 $project_id = $web1["id"];		
							 // echo "SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id' ORDER BY id ASC LIMIT 1 ";
					$old_speed = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id' ORDER BY id ASC LIMIT 1 ");
					
					if ($old_speed->num_rows > 0) {
													$pagespeed_data = $old_speed->fetch_assoc();
													// print_r($pagespeed_data);
													$ps_categories = unserialize($pagespeed_data["categories"]);

													// =========================
													$ps_performance = round($ps_categories["performance"]["score"] * 100, 2);
													$ps_desktop = $ps_performance . "/100";

													$ps_mobile_categories = unserialize($pagespeed_data["mobile_categories"]);
													$ps_performance_m = round($ps_mobile_categories["performance"]["score"] * 100, 2);
													$ps_mobile =  $ps_performance_m. "/100";

					}

					$queryN = $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id' ORDER BY id ASC LIMIT 1,1  ");
					$ps_desktop_n = "---";
					$ps_mobile_n = "---";

					if ($queryN->num_rows > 0) {
													$pagespeed_data_n = $queryN->fetch_assoc();
													// print_r($pagespeed_data);
													$ps_categories_n = unserialize($pagespeed_data_n["categories"]);

													// =========================
													$ps_performance_n = round($ps_categories_n["performance"]["score"] * 100, 2);
													$ps_desktop_n = $ps_performance_n . "/100";

													$ps_mobile_categories_n = unserialize($pagespeed_data_n["mobile_categories"]);
													$ps_performance_m_n = round($ps_mobile_categories_n["performance"]["score"] * 100, 2);
													$ps_mobile_n =  $ps_performance_m_n. "/100";

					}
							

							$plan = getTableData( $conn , " plans " , " id = '".$web1["plan_id"]."' " ) ;
							$subs = getTableData( $conn , " user_subscriptions " , " id = '".$web1["subscription_id"]."' " ) ;


									$is_plan = 1;	
							 		if($web1["plan_id"] == "999")
							 		{
							 			$is_plan = 0;	
							 			$plan["name"] = "---";
							 			$ps_desktop_n = "---";
							 			$ps_mobile_n = "---";

							 			$web1["plan_id"] = "---";
							 			$web1["plan_type"] = "---";
							 			$web1["subscription_id"] = "---";
							 		}			

							 		if($web1["installation"] >=4){
							 			$web1["installation"] = "Yes";
							 		}				 	
							 		else{
							 			$web1["installation"] = "No";
							 		}


							
							 ?>
						<div class="table speedy-table card__table">
							
							<div class="data"> 
								<div>Website URL</div>
								<div><a href="<?=$web1["website_url"];?>" target="_blank"><?=$web1["website_url"];?></a></div>
							</div>
							<div  class="data"> 
								<div>Platform</div>
								<div><?=$web1["platform"]?></div>
							</div>
							<div  class="data"> 
								<div>Desktop Speed Old</div>
								<div><?=$ps_desktop?></div>
							</div>
							<div  class="data"> 
								<div>Desktop Speed New</div>
								<div><?=$ps_desktop_n?></div>
							</div>
							<div  class="data"> 
								<div>Mobile Speed Old</div>
								<div><?=$ps_mobile?></div>
							</div>
							<div  class="data"> 
								<div>Mobile Speed New</div>
								<div><?=$ps_mobile_n?></div>
							</div>
							<div  class="data"> 
								<div>Script Installed</div>
								<div><?=$web1["installation"]?></div>
							</div>
							<div  class="data"> 
								<div>Plan Id</div>
								<div><?=$web1["plan_id"]?></div>
							</div>
							<div  class="data"> 
								<div>Plan Name</div>
								<!-- //123 -->
								<div>
									<?php if($plan["name"]=='Free'){
										echo "Basic Plan";
									}else{
										echo $plan["name"];
									}  
									?>
								</div>
							</div>
							<div  class="data"> 
								<div>Plan Type</div>
								<div><?=$web1["plan_type"]?></div>
							</div>
							<div  class="data"> 
								<div>Subscription Id</div>
								<div><?=$web1["subscription_id"]?></div>
							</div> 
							<div  class="data"> 
								<div>Last Update</div>
								<div><?php        $timedy2=$web1["updated_at"] ;
                                           $vartime2 = strtotime($timedy2);

                                             $datetimecon2= date("F d, Y H:i", $vartime2); echo $datetimecon2; ?></div>
											 </div> 	
							<div  class="data"> 
								<div>Subscription Status</div>
								<div><?php 
										if($subs["is_active"] == "0"){
											echo "Canceled";
										}
										else{
											echo "Active";
										}

										?></div>
							</div> 

	<div class="data">
		<!-- <div>Client Feedback</div> -->
	</div>


</div>


<?php
	// print_r($web1) ;
	$query = $conn->query(" SELECT * FROM `website_review_feedback` WHERE `manager_id` = '".$web1["manager_id"]."' AND website_id = '".$web1["id"]."' ; ") ;

	if ( $query->num_rows > 0 ) {

		$wrf_data = $query->fetch_assoc() ;

		?>
		<h4>Feedback</h4>
		<div class="table speedy-table card__table feedback-card">
			<div class="data">
				<div>Client Feedback:</div>
			</div>
			<div class="data" style="padding: 0px !important;height: 0px !important;"></div>
			
			<div class="data">
				<div>Are you satisfied with the updated speed ?</div>
				<div><?=$wrf_data["satified_or_not"]?></div>
			</div>
			<?php 
			if ( $wrf_data["satified_or_not"] == "no" ) {
				?>
				<div class="data">
					<div>Please tell us how can we improve:</div>
					<div><?=$wrf_data["improve"];?></div>
				</div>
				<?php

			}
			else {

				?>
				<div class="data">
					<div>How would you rate your experience?</div>
					<div><?=$wrf_data["rating"];?></div>
				</div>
				<?php

				if ( $wrf_data["rating"] > 8 ) {
					?>
					<div class="data">
						<div>Click the button below to review our platform on Trust Pilot</div>
						<div><?=($wrf_data["trust_pilot_review"]=="review left")?"I don’t wanna leave review.":" I have left review.";?></div>
					</div>
					<?php
				}
				else {
					?>
					<div class="data">
						<div>Please tell us how can we improve:</div>
						<div><?=$wrf_data["feedback"];?></div>
					</div>
					<?php
				}

			}
		?>
		</div>
		<?php
	}
?>


							<?php 
							
							 }
							 
							 ?>




						
					     
							</div>		   
					   
					 
				</div>
			</div>
							</div>
		</div>
		<!-- Bootstrap core JS-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Core theme JS-->
		<script src="js/scripts.js"></script>
		
		<script>
			$( document ).ready(function() {
				var dropdown = $('.nav-item.dropdown');
				var aUser = $('.user_name');
				var dropUser = $('.user__dropdown')
					dropdown.removeClass('show');
					aUser.attr("aria-expanded","false");
					dropUser.removeClass('show');
			});
			
		</script>
	</body>

</html>