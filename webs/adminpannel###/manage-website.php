<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;

 $subsc_id = base64_decode($_REQUEST['sid']);
$subsc_id_url = $_REQUEST['sid'];
// echo $subsc_id;
$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
 $user_id = $_SESSION['user_id'];
	$websites = getTableData( $conn , " boost_website " , " manager_id = '".$user_id."' and subscription_id = '".$subsc_id."'  " , "" , 1 ) ;
// echo $websites[0]['id'];
$Myplan = ""; 
$subs = "";
							    $rowSubs = getTableData( $conn , " user_subscriptions " , " id ='".$subsc_id."' ") ;
							    if(count($rowSubs)<=0){
							    $rowSubs = getTableData( $conn , " user_subscriptions_free " , " user_id ='".$_SESSION['user_id']."' ") ;
							    $Myplan = "FreePlan"; 
							    $rowSubs['is_active']=1;
							    }

   

	$plan = getTableData( $conn , " plans " , " id ='".$rowSubs['plan_id']."' " );
// echo $plan['id'];

$project_id =  $websites[0]['id'];
	$speed = getTableData( $conn , " pagespeed_report " , " website_id ='$project_id' " );
	// $conn->query(" SELECT * FROM pagespeed_report WHERE pagespeed_report.website_id = '$project_id' ORDER BY id DESC LIMIT 1 ");



   $sqlZ = "select boost_website.* , plans.`id`,plans.plan_frequency from boost_website, plans where boost_website.plan_id = plans.`id` and boost_website.subscription_id = '".$rowSubs['id']."'";

$resultZ = mysqli_query($conn, $sqlZ);
$fetchActualPlan = mysqli_fetch_assoc($resultZ);

?>



<?php require_once("inc/style-and-script.php") ; ?>
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
				<div class="container-fluid manage_web content__up">
					
					<h1>Manage Website</h1>
                    <?php require_once("inc/alert-status.php") ; ?>
					<div class="profile_tabs">
					<?php
					if($rowSubs['is_active']==1){
						if(count($websites)<=0){
					?> 	
					<a href="<?=HOST_URL?>adminpannel/add-website.php?sid=<?=$subsc_id_url?>" class="btn btn-primary">Add Website</a>

					<?php
				}
				}
					?>
				<?php if ( count($websites) > 0 ){ 
					if(!empty($rowSubs) && $rowSubs['is_active']==1 && $websites[0]['plan_type'] !="Free"){
					?>

					<!-- <a href="<?=HOST_URL?>plan.php?change-id=<?=$subsc_id_url?>&sid=<?=$subsc_id_url?>"  class="btn btn-primary">Upgrade Plan</a> -->
				<?php
					 }
					 else{
					 	if($rowSubs['is_active']==1){
					 	?>
					      <!-- <a href="<?=HOST_URL?>plan.php"  class="btn btn-primary">Upgrade Plan</a> -->
					   <?php
					}

					 }
					}
				 ?>	

					<?php

						$user_id = $_SESSION["user_id"] ;
					?>
					<label class="availablity">
						<span>
							<?php
						if($rowSubs['is_active']==1){
								if(!empty($rowSubs)){
									// echo "Paid Plan";
							    $av = $rowSubs['site_count'] - count($websites);
							    	if($av >=0 ){
												// echo $av.' Site';
												// if($rowSubs['site_count']>1){echo "s";}
												// echo ' Available To Add In This Plan.';
									}
									else{
												// echo 'Site Limit Reached Please Add New Subscriptions Plan For Adding More.';
									}
								}
								else{
									echo "Please Upgrade you plan to add new sites.";
								}
							}
							?>
						</span>
					</label>
                    <div class="table_S">
                    	<div class="loader">Please Wait...</div>
					<table class="speedy-table table " id="page-speed-table" data-project="<?=$websites[0]['id']?>" data-type="page-speed">
						<thead>
							<tr>
								<th>#</th>
								<th>Platform</th>
								<th>Url</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php


							// print_r($websites) ;

						// $fetchActualPlan['plan_frequency'] = "3+ Websites" ;
 
							if ( count($websites) > 0 ) 
							{
								$sno = 0 ;
								$counter = 1;
								foreach ($websites as $website_data) {
									 
                                    


									$platform = $website_data["platform"];
									if ( $website_data["platform"] == "Other" ) {
										$platform = $website_data["platform"]."(".$website_data["platform_name"].")" ;
									}

									$website_url = '<a href="'.$website_data["website_url"].'" target="_blank">'.$website_data["website_url"].'</a>'  ;
									if ( $website_data["platform"] == "Shopify" ) {
										$website_url .= '<br> <a href="'.$website_data["shopify_url"].'" target="_blank">'.$website_data["shopify_url"].'</a>'  ;
									}

									if($sno == 1){
										break;
									}

 
                  
									$platform = $website_data["platform"] ;
									if ( $website_data["platform"] == "Other" ) {
										$platform = $website_data["platform"]."(".$website_data["platform_name"].")" ;
									}

									$website_url = '<a href="'.$website_data["website_url"].'" target="_blank">'.$website_data["website_url"].'</a>'  ;
									if ( $website_data["platform"] == "Shopify" ) {
										$website_url .= '<br> <a href="'.$website_data["shopify_url"].'" target="_blank">'.$website_data["shopify_url"].'</a>'  ;
									}
									if($sno == 2){
										// echo "string";
										break;
									}

 
       
									$platform = $website_data["platform"] ;
									if ( $website_data["platform"] == "Other" ) {
										$platform = $website_data["platform"]."(".$website_data["platform_name"].")" ;
									}

									$website_url = '<a href="'.$website_data["website_url"].'" target="_blank">'.$website_data["website_url"].'</a>'  ;
									if ( $website_data["platform"] == "Shopify" ) {
										$website_url .= '<br> <a href="'.$website_data["shopify_url"].'" target="_blank">'.$website_data["shopify_url"].'</a>'  ;
									}
									

 


									?>
									<tr>
										<td><?=$counter?></td>
										<td><?=$platform?></td>
										<td><?=$website_url?></td>
										<td>
											 
											 <a href="javascript:void(0)" class="Polaris-Button btn btn-primary " > 
											 	<span class="Polaris-Button__Text"><?php
											  // echo $website_data["subscription_id"] ;

											if($rowSubs['is_active']==1){											 	

											  if($website_data["subscription_id"] == 0){
											  	echo "No Plan";
												}
												else{

											  if($website_data["plan_type"] == "Free")
											  {
											  	echo "Free Plan";
											  }
											  else{
													$sub = getTableData( $conn , " plans " , " id = '".$website_data["plan_id"]."' " , "" , 1 ) ;
													
													echo $sub[0]['name'];
													
													// print_r($sub);
												}
												}
												}
												else{
													echo "Cancled";
												}

											  ?>
											</span>
											  	
											  </a>

<?php
// echo count($speed);
	if(count($speed )<1){
?>
<button type="button" style="display: none;" class="btn btn-primary reanalyze-btn" data-website_name="<?=$website_data["website_url"]?>" data-ps_mobile="-" data-ps_performance="-" data-ps_accessibility="-" data-ps_best_practices="-" data-ps_seo="-" data-ps_pwa="-" data-website_url="<?=$website_data["website_url"]?>" data-ps_desktop="-" data-additional="0"><svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg><!-- <i class="fa fa-refresh" aria-hidden="true"></i> Font Awesome fontawesome.com --></button>
<script type="text/javascript">
	$(document).ready(function(){
	// $(".reanalyze-btn").click();
	});
</script>

<?php
	}		
?>

											

										</td>
									</tr>
									<?php
									$sno++ ;
									$counter++;
								}
							}
							else {
								?><tr><td colspan="4"> No Data found.</td></tr><?php
							}

						?>

						</tbody>
					</table>
						</div>
						</div>
				</div>
			</div>
		</div>
		<!-- sss -->
	</body>
</html>

