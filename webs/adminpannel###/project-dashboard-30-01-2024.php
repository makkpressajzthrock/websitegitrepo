<?php 

include('config.php');
require_once('inc/functions.php') ;
require_once('meta_details.php') ;



// check sign-up process complete
// checkSignupComplete($conn) ;

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."'" ) ;


// Show Expire message //

$is_cancled = 1;

	$plan_country = "";
		if($row['country'] != "101"){   // Matching user country to show plan link
		$plan_country = "-us";
	}

include("error_message_bar_subscription.php");

// End Show Expire message //


		if($row['phone'] == "" || $row['phone'] == NULL){
			header("location: ".HOST_URL."basic_details.php") ;
			die();		
		}

$first_data = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " , " ORDER BY `boost_website`.`id` ASC " ) ;
if($first_data['id'] == ''){
			header("location: ".HOST_URL."customize-flow.php") ;
			die();	
}
 


if($row['flow_step']==1){

		$user_id = $_SESSION["user_id"] ; 
		$get_flow = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
		$d = $get_flow->fetch_assoc();

		if ( $row["user_type"] == "Dealify" || $row["user_type"] == "AppSumo" ) {
			header("location: ".HOST_URL."adminpannel/dashboard.php");
		}
		else{
			header("location: ".HOST_URL."plan$plan_country.php?sid=".base64_encode($d['id']));
		}

		die() ;	
}



$suco_c = 0;
		if($row['sumo_code'] !="" && $row['sumo_code'] !="null"){
			$suco_c = 1;
		}
$plan_lifetime = "";
$plan_lifetime_type = "";


if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}




// print_r($sele_run2);
// Overview
$user_id = $_SESSION["user_id"] ;
$project_id = base64_decode($_GET['project']) ;
 
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

<?php
	if($row['shareasale']==0){
		$user_ids = $_SESSION['user_id'];
		?>

			<img src="https://www.shareasale.com/sale.cfm?tracking=<?=$user_ids?>&amount=0.00&merchantID=144859&transtype=lead" width="1" height="1">

			<script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>


		<?php

		$conn->query(" UPDATE admin_users SET shareasale = 1 WHERE `id` = '".$_SESSION['user_id']."'") ;			
	}

?>



       	<div class="loader">Please Wait...</div>

		<div class="d-flex" id="wrapper">
		<div class="top-bg-img" ></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				<?php //die('1') ; ?>
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up project__dashboard">


				<?php

					
					$website_data = getTableData( $conn , " boost_website " , " id = '".$project_id."' AND manager_id = '".$user_id."' " ) ;
					$speed = getTableData( $conn , " pagespeed_report " , " website_id ='$project_id' " );

					
					// print_r($website_data) ;

					// get page view
					// $today_visitor_arr = getTableData( $conn , " website_visits " , " DATE(created_at) = CURDATE() AND manager_id='".$user_id."' AND website_id='".$project_id."' ", " GROUP BY ip " , 1 , " ip " ) ;

					$query = $conn->query(" SELECT ip FROM website_visits WHERE DATE(created_at) = CURDATE() AND manager_id='$user_id' AND website_id='$project_id' AND ip <> '' GROUP BY ip,created_at ") ;

					// echo $conn->error;

					$today_visitor_arr = ($query->num_rows > 0 ) ? $query->num_rows : 0 ;


					// $total_visitor_arr = getTableData( $conn , " website_visits " , " manager_id='".$user_id."' AND website_id='".$project_id."' ", "" , 1 , " ip " ) ;

					$query = $conn->query(" SELECT ip FROM website_visits WHERE manager_id='$user_id' AND website_id='$project_id' AND ip <> '' ") ;

					$total_visitor_arr = ($query->num_rows > 0 ) ? $query->num_rows : 0 ;
					// print_r($today_visitor_arr) ;

					// get page view

					$most_visited_country = "" ;

					$sql = " SELECT COUNT(id) AS count , country  FROM `website_visits` WHERE `manager_id` = '".$user_id."' AND `website_id` = '".$project_id."' GROUP BY country ORDER BY count DESC LIMIT 1 " ;
					$query = $conn->query($sql) ;

					if ( $query->num_rows > 0 ) {
						$mvc_data = $query->fetch_assoc() ;
						$most_visited_country = $mvc_data["country"] ;
						// print_r($most_visited_country) ;
					}

					// $query = getTableData( $conn , " website_visits " , " manager_id = '".$user_id."' AND website_id = '".$project_id."' " , " GROUP BY country ORDER BY count DESC LIMIT 1 " , 0 , " COUNT(id) AS count , country " ) ;

					// print_r($website_data) ;

					?>
					<h1 class="mt-4">Overview</h1>
						<?php require_once("inc/alert-status.php") ; ?>

					<div class="profile_tabs">
									

					<div class="row dash_overview" style="flex-wrap: wrap;">

						<div class="col-md-3 border rounded with__shadow p-0 mx-4 web_details_s dash_board_s">
							<?php
							 " SELECT * FROM `pagespeed_report` WHERE `website_id` = '".$project_id."' AND `parent_website` = '0' ORDER BY `pagespeed_report`.`id` DESC LIMIT 1 ";
								$wd_query = $conn->query(" SELECT * FROM `pagespeed_report` WHERE `website_id` = '".$project_id."' AND `parent_website` = '0' ORDER BY `pagespeed_report`.`id` DESC LIMIT 1 ") ;

								$wd_desktop = $wd_mobile = "-" ;

								if ( $wd_query->num_rows > 0 ) {
									$wd_data = $wd_query->fetch_assoc() ;
									$wd_categories = unserialize($wd_data["categories"]) ;

									$wd_performance = round($wd_categories["performance"]["score"]*100,2) ;
									$wd_desktop = $wd_performance."/100" ;

									$wd_mobile_categories = unserialize($wd_data["mobile_categories"]) ;
									$wd_mobile = round($wd_mobile_categories["performance"]["score"]*100,2)."/100" ;

								}
							?>
							<div  style="display: none;" id="page-speed-table" data-project="<?=$website_data['id']?>" data-type="page-speed">
								
								<?php
								// echo count($speed);
									if(count($speed )<1){

								?>
								<!-- //123 -->
								<button type="button" class="btn btn-primary reanalyze-btn-new" data-speedtype="old" data-website_name="<?=$website_data["website_url"]."?nospeedy"?>" data-website_url="<?=$website_data["website_url"]."?nospeedy"?>" data-website_id="<?=$website_data["id"]?>" data-additional="0"><svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg><!-- <i class="fa fa-refresh" aria-hidden="true"></i> Font Awesome fontawesome.com --></button>
								<script type="text/javascript">
									$(document).ready(function(){
									$(".reanalyze-btn-new").click();
									});
								</script>

								<?php
									
             
									}	
									 
	  $timedy2=$website_data['updated_at'];
                                           $vartime2 = strtotime($timedy2);

                                             $datetimecon2= date("F d, Y H:i", $vartime2);


                                              $small = substr($website_data["website_url"], 0, 25);

								?>								
							</div>

							<ul class="list-group">
								<li class="list-group-item">Page<span class="float-right"><a href="<?=$website_data["website_url"]?>" target="_blank"><?=$small?></a></span></li>
								<li class="list-group-item">Desktop <span class="float-right"><?=$wd_desktop?></span></li>
								<li class="list-group-item">Mobile <span class="float-right"><?=$wd_mobile?></span></li>
								<li class="list-group-item">Last Updated <span class="float-right"><?php echo $datetimecon2;  ?></span></li>
							</ul>
						</div>

						<!-- <div class="col-md-3 border rounded with__shadow">
							<h5>Site Score (Desktop)</h5>
							<h6><?=$website_data["desktop_speed_new"]?>/100</h6>
						</div>

						<div class="col-md-3 border rounded with__shadow">
							<h5>Site Score (Mobile)</h5>
							<h6><?=$website_data["mobile_speed_new"]?>/100</h6>
						</div> -->



						<?php
							$additional_websites = getTableData( $conn , " additional_websites " , " website_name <> '' and website_url <> '' and manager_id = '".$user_id."' AND website_id = '".$project_id."' " , "" , 1 ) ;
							
							foreach ($additional_websites as $key => $additional_url) {

								$ps_query = $conn->query(" SELECT * FROM `pagespeed_report` WHERE `website_id` = '".$additional_url["id"]."' AND `parent_website` = '".$project_id."' ORDER BY `pagespeed_report`.`id` DESC LIMIT 1 ") ;

								// print_r($additional_url) ;


                                          $timedy= $additional_url['updated_at'];
                                           $vartime = strtotime($timedy);

                                             $datetimecon= date("F d, Y H:i", $vartime);



								$ps_desktop = $ps_mobile = "-";

								if ( $ps_query->num_rows > 0 ) {
									$ps_data = $ps_query->fetch_assoc() ;
									$ps_categories = unserialize($ps_data["categories"]) ;

									$ps_performance = round($ps_categories["performance"]["score"]*100,2) ;
									$ps_desktop = $ps_performance."/100" ;

									$ps_mobile_categories = unserialize($ps_data["mobile_categories"]) ;
									$ps_mobile = round($ps_mobile_categories["performance"]["score"]*100,2)."/100" ;

								}

								// echo "<pre>"; print_r($additional_url) ; echo "</pre>";
								?>
								<div class="col-md-3 border rounded with__shadow p-0  mx-4 dash_board_s">
									<ul class="list-group">
										<li class="list-group-item">Page<span class="float-right"><a href="<?=$additional_url["website_url"]?>" target="_blank"><?=$additional_url["website_name"]?></a></span></li>
										<li class="list-group-item">Desktop <span class="float-right"><?=$ps_desktop?></span></li>
										<li class="list-group-item">Mobile <span class="float-right"><?=$ps_mobile?></span></li>
										<li class="list-group-item">Last Updated <span class="float-right"><?php echo  $datetimecon;?></span></li>
									</ul>
								</div>
								<?php
							}
						 $sele_day="SELECT * FROM boost_website  where id = '".$project_id."' AND  manager_id ='".$user_id."' ";
						 	$sele_qr =mysqli_query($conn,$sele_day);
						 	$sele_run= mysqli_fetch_array($sele_qr);

         // print_r($sele_run);
         
						 	 $sub_ids= $sele_run['subscription_id'];
						 	 $plan_type= $sele_run['plan_type'];
						 	 $zero= 0;
						 	 if($plan_type!="Free"){
					  $sele_day2="SELECT * FROM user_subscriptions where id ='$sub_ids'";
							// echo $sele_day;
							$sele_qr2 =mysqli_query($conn,$sele_day2);
							$sele_run2= mysqli_fetch_array($sele_qr2);

							$plan_lifetime = $sele_run2["plan_interval"];

							$is_subscription = count($sele_run2);

							$is_cancled = $sele_run2['is_active'];



							$date_expire = $sele_run2["plan_period_end"] ;
							$date_expire_start = $sele_run2["script_updated_on"] ;

							$current_date = date('Y-m-d H:i:s') ;
							$diff = date_diff(date_create($current_date) , date_create($date_expire) ) ;

							// echo "<pre>";
							// 	print_r($diff);
  // echo $date_expire;


							$zero= $diff->days;
						    $expired = $diff->invert;	

						 	 }else{

 $sele_free= " SELECT * FROM `user_subscriptions_free` WHERE `user_id` ='".$user_id."' AND `status` LIKE '1'";
 	$sele_free1 =mysqli_query($conn,$sele_free);
						 	$sele_run2= mysqli_fetch_array($sele_free1);



  $date_expire = $sele_run2["plan_end_date"] ;
  // $date_expire_start2 = $sele_run2["plan_start_date"] ;

	
	$diff = date_diff(date_create($current_date) , date_create($date_expire) ) ;
	
	// print_r($diff);
// echo "ss";
  $zero = $diff->days;
  $expired = $diff->invert;

//   echo $expired;die;

  

						 	 }
  // echo $zero;







						?>

					</div>

					<div class="row page_details" style="flex-wrap: wrap;margin-top:20px;">

				<!-- 		<div class="col-md-3 border rounded with__shadow p-0 mx-4  dash_s">
							<ul class="list-group">
								<li class="list-group-item">Today's Page Views</span></li>
								<li class="list-group-item"><?=$today_visitor_arr?></li>	
							</ul>
						</div>

						<div class="col-md-3 border rounded with__shadow p-0 mx-4 dash_s">
							<ul class="list-group total_view">
								<li class="list-group-item">Total Page Views</span></li>
								<li class="list-group-item"><?=$total_visitor_arr?></li>
							</ul>
						</div> -->

					<!-- 	<div class="col-md-3 border rounded with__shadow p-0 mx-4 dash_s">
							<ul class="list-group most_visitor">
								<li class="list-group-item">Most Visitor Country</span></li>
								<li class="list-group-item"><?=$most_visited_country?></li>
							</ul>
						</div> -->

						<div class="col-md-3 border rounded with__shadow p-0 mx-4 dash_s">
							<ul class="list-group ">

								<?php
								if($plan_lifetime != 'Lifetime'){

								$ss = '';
								if($diff->days >1)
								{
									$ss = "s";
								}

								?>


								
									<li class="list-group-item">
										<?php 

										// echo "is canceled".$is_cancled."<br>";
										// echo "is zero".$zero."<br>";
										// echo "is expire".$expired."<br>";
										// echo 'is subscription'.$is_subscription."<br>"; 
										// echo "plan type".$plan_lifetime;
										if($is_cancled != 0){	
												if ($zero == 0 ||  $expired == 1) {
													if($is_subscription > 0){ 
														if($plan_lifetime=="Month Free"){
															// echo 1; 
															echo "Your Subscription Will Expire";
														}
														else{
															// echo 2;
															echo "Your subscription will Renew";
														}
													}
													else{
														// echo 3;
													echo "Your subscription is Expired, Please subscribe to continue your website speed";
													}

												 } else{ 
													if($is_subscription > 0){ 
														if($plan_lifetime=="Month Free"){
															// echo 4;
															echo "Your Subscription Will Expire";
														}
														else{
															// echo 5;
															echo "Your subscription will Renew";
														}												
													}
													else{
														// echo 6;
													echo "Your subscription will Expire, Please subscribe to continue your website speed";
													}

												 } 
											}
											else{
												// echo 7;
												$site_ids = $_REQUEST['project'];
												echo "<a class='upgrade_button_loc btn btn-primary' href='/plan".$plan_country.".php?sid=".$site_ids."' class='btn btn-primary'>Subscribe Now</a>";
											}	 

										 ?>

										</li>
										<?php //print_r($diff); ?>
									<li class="list-group-item"><?php 

									if($is_cancled != 0){
									if ($zero >= 1 && $expired ==0 ) { 
											echo "After".' ' .($diff->days).' '. "Day".$ss;  
										} 
									else{ 

										if ($is_subscription > 0 && $zero == 0 && $expired ==0 ) {	


										}
										if($is_subscription > 0){
											$site_ids = $_REQUEST['project'];
											
													if($plan_lifetime=="Month Free"){
														if ($is_subscription > 0 && $zero == 0 && $expired ==0 ) {
															// echo 8;
																echo "Will Expire Today";
															echo "<a class='upgrade_button_loc btn btn-primary' href='/plan".$plan_country.".php?change-sid=".$site_ids."&sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>";															
														}else{
															
															// echo 9;
															echo "Expired";
															echo "<a class='upgrade_button_loc btn btn-primary' href='/plan".$plan_country.".php?change-sid=".$site_ids."&sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>";
														}
													}
													else{
														// echo 10;
														echo "Renew Today";
													}												


										}else{
											// echo 11;
											echo "Expired";
										}

									} 
								}
								else{
									// echo 12;
									echo "Canceled";
								}



									




									?></li>
									<?php
									
								}

								else{
									?>
								<li class="list-group-item"></span></li>
								<li class="list-group-item">Lifetime Access</li>


									<?php

								}


								?>

							</ul>
						</div>
					</div>

					<div class="row">
						<div class="countdown_card">
							<?php
								$user_subscription = getTableData( $conn , 
									" user_subscription , subscription_plan " , 
									" user_subscription.user_id = '".$user_id."' AND user_subscription.status LIKE 'active' AND subscription_plan.id = user_subscription.plan_id ORDER by user_subscription.id desc" , 
									"" ,
									0 , 
									" user_subscription.plan_id , user_subscription.charge_id , user_subscription.plan_active_date , user_subscription.status , user_subscription.plan_id , subscription_plan.s_type , subscription_plan.s_trial_duration , user_subscription.plan_type "
									) ;
								
								
								$current_time = strtotime(date('Y-m-d H:i:s')) ;
								
								$plan_active_date = strtotime($user_subscription["plan_active_date"]) ;
								
								if ( $user_subscription["plan_type"] == "trial" ) {
									$plan_expire_date = strtotime("+".$user_subscription["s_trial_duration"]." days", $plan_active_date ) ;
								}
								else {
									$plan_expire_date = strtotime("+30 days", $plan_active_date ) ;
								}
								
								
								
								// $diff = $current_time - $nextFive ;
								$subscription_diff = $plan_expire_date - $current_time ;
								
								?>
							<div style="position: relative;">
								<style>
									#countdown{
									width: 100%;
									height: 160px;
									text-align: center;
									background: #222;
									background-image: -webkit-linear-gradient(top, #303, #303, #303, #303);
									background-image: -moz-linear-gradient(top, #303, #303, #303, #303);
									background-image: -ms-linear-gradient(top, #303, #303, #303, #303);
									background-image: -o-linear-gradient(top, #303, #303, #303, #303);
									border: 1px solid #111;
									border-radius: 5px;
									box-shadow: 0px 0px 8px rgb(0 0 0 / 60%);
									margin: auto;
									padding: 30px 0 24px;
									position: absolute;
									top: 0;
									left: 0;
									right: 0;
									}
									#countdown:before{
									content:"";
									width: 8px;
									height: 65px;
									background: #303;
									background-image: -webkit-linear-gradient(top, #303, #303, #303, #303); 
									background-image:    -moz-linear-gradient(top, #303, #303, #303, #303);
									background-image:     -ms-linear-gradient(top, #303, #303, #303, #303);
									background-image:      -o-linear-gradient(top, #303, #303, #303, #303);
									border: 1px solid #111;
									border-top-left-radius: 6px;
									border-bottom-left-radius: 6px;
									display: block;
									position: absolute;
									top: 48px; left: -10px;
									}
									#countdown:after{
									content:"";
									width: 8px;
									height: 65px;
									background: #303;
									background-image: -webkit-linear-gradient(top, #303, #303, #303, #303); 
									background-image:    -moz-linear-gradient(top, #303, #303, #303, #303);
									background-image:     -ms-linear-gradient(top, #303, #303, #303, #303);
									background-image:      -o-linear-gradient(top, #303, #303, #303, #303);
									border: 1px solid #111;
									border-top-right-radius: 6px;
									border-bottom-right-radius: 6px;
									display: block;
									position: absolute;
									top: 48px; right: -10px;
									}
									#countdown #tiles{
									position: relative;
									z-index: 1;
									}
									#countdown #tiles > span{
									width: 92px;
									max-width: 92px;
									font: bold 48px 'Droid Sans', Arial, sans-serif;
									text-align: center;
									color: #111;
									background-color: #ddd;
									background-image: -webkit-linear-gradient(top, #bbb, #eee); 
									background-image:    -moz-linear-gradient(top, #bbb, #eee);
									background-image:     -ms-linear-gradient(top, #bbb, #eee);
									background-image:      -o-linear-gradient(top, #bbb, #eee);
									border-top: 1px solid #fff;
									border-radius: 3px;
									box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.7);
									margin: 0 7px;
									padding: 18px 0;
									display: inline-block;
									position: relative;
									}
									#countdown #tiles > span:before{
									content:"";
									width: 100%;
									height: 13px;
									background: #111;
									display: block;
									padding: 0 3px;
									position: absolute;
									top: 41%; left: -3px;
									z-index: -1;
									}
									#countdown #tiles > span:after{
									content:"";
									width: 100%;
									height: 1px;
									background: #eee;
									border-top: 1px solid #333;
									display: block;
									position: absolute;
									top: 48%; left: 0;
									}
									#countdown .labels{
									width: 100%;
									height: 25px;
									text-align: center;
									position: absolute;
									bottom: 8px;
									}
									#countdown .labels li{
									width: 102px;
									font: bold 15px 'Droid Sans', Arial, sans-serif;
									color: #f47321;
									text-shadow: 1px 1px 0px #000;
									text-align: center;
									text-transform: uppercase;
									display: inline-block;
									}
								</style>
					<!-- 			<div id="countdown">
									<div class="labels" style="top: 5px;right: 5px;">
										<li>Subscription</li>
									</div>
									<div id='tiles'><?=empty(count($user_subscription)) ? "00" : $user_subscription["s_duration"]?></div>
									<div class="labels">
										<li>Days Left</li>
									</div>
								</div> -->
							</div>
						</div>
					</div>



					<div class="row d-none">
						<div class="col-md-8">
							<select class="form-control" id="visitor-period" data-owner="<?=$row["id"];?>" data-website="<?=$project_id?>">
								<option value="24hours">Today</option>
								<option value="7days" selected>Last 7days</option>
								<option value="Month">Last Month</option>
								<!-- <option value="3Month">Last 3Month</option> -->
								<option value="6Month">Last 6Month</option>
								<option value="Year">Last Year</option>
							</select>
							<?php
								$chartPoints = [] ;
								$chartLabel = [] ;
								$chartData = [] ;
								$chartColor = [] ;
								
								// $query = $conn->query("SELECT website_visits.* , DATE_FORMAT(website_visits.created_at,'%e %b') AS visitor_date FROM website_visits WHERE website_visits.manager_id = '".$row["id"]."' AND DATE(website_visits.created_at) > now() - INTERVAL 7 day") ;
								// $query = $conn->query("SELECT website_visits.* , DATE_FORMAT(website_visits.created_at,'%e %b') AS visitor_date FROM website_visits WHERE website_visits.manager_id = '".$row["id"]."' AND website_id = '".$_GET["project"]."'  AND DATE(website_visits.created_at) - INTERVAL 7 day") ;

								$query = $conn->query(" SELECT DATE_FORMAT(website_visits.created_at,'%e %b') AS visitor_date , COUNT(id) AS count FROM website_visits WHERE website_visits.manager_id = '".$row["id"]."' AND website_id = '".$project_id."' AND DATE(website_visits.created_at) - INTERVAL 7 day GROUP BY visitor_date ") ;
								
								if ( $query->num_rows > 0 ) {
									$chart_data = $query->fetch_all(MYSQLI_ASSOC) ;
								
									foreach ($chart_data as $key => $value) {

										$chartLabel[] = $value["visitor_date"] ;
										$chartData[] =  $value["count"];

										if ( $key <= count($color_arr) ) {
											$chartColor[] = $color_arr[$key] ;
										}
										else {
											$chartColor[] = $color_arr[count($chart_data)-$key] ;
										}
								
									    // if (array_key_exists($value["visitor_date"], $chartPoints)) {
									    //     $chartPoints[$value["visitor_date"]] = $chartPoints[$value["visitor_date"]] + 1 ;
									    // }
									    // else {
									    //     $chartPoints[$value["visitor_date"]] = 1 ;
									    // }
									}
								
									// $chartLabel = array_keys($chartPoints) ;
									// $chartData = array_values($chartPoints) ;
								}

								$chartLabel = implode("','",$chartLabel) ;
								$chartData = implode("','",$chartData) ;
								$chartColor = implode("','",$chartColor) ;
								
								// print_r($chartLabel) ;
								// print_r($chartData) ;
								?>
							<div id="visitor-chart-box">
								<canvas id="visitor-chart"></canvas>
							</div>
						</div>

					</div>

					<div class="row d-none">
						<div class="col-md-4">
							<div class="card-header border-0">
								<h5 class="card-title">
									<i class="fas fa-map-marker-alt mr-1"></i>
									Top Visitor Locations
								</h5>
							</div>
							<div class="card-footer bg-transparent spin-content hide-content">
								<?php

								// subcribers by country
								$query = $conn->query( " SELECT country , COUNT(country) AS subscriber_count , COUNT(country)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' GROUP BY country ORDER BY subscriber_count DESC " ) ;

								$query2=$conn->query("SELECT browser_family , COUNT(browser_family) AS subscriber_count , COUNT(browser_family)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' and ip='' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' AND ip=''GROUP BY browser_family ORDER BY subscriber_count DESC	");
								$subscriberByCountry = $query->fetch_all(MYSQLI_ASSOC) ;
								$subscriberByCountry2 = $query2->fetch_all(MYSQLI_ASSOC) ;

								

								$visitor_country = [] ;
								$country_color = [] ;
								$country_percent = [] ;
								$browsers_name = [] ;

								foreach($subscriberByCountry as $key=>$value) 
								{
									$visitor_country[] = $value["country"] ;
									$country_percent[] = round($value["subscriber_percent"],2) ;

									if ( $key <= count($color_arr) ) {
										$country_color[] = $color_arr[$key] ;
									}
									else {
										$country_color[] = $color_arr[count($subscriberByCountry)-$key] ;
									}
								}

								$visitor_country = implode("','", $visitor_country) ;
								$country_color = implode("','", $country_color) ;
								$country_percent = implode("','", $country_percent) ;

								// by city
								// subcribers by city
								
								$query = $conn->query( " SELECT city , COUNT(city) AS subscriber_count , COUNT(city)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' GROUP BY city ORDER BY subscriber_count DESC " ) ;

								$subscriberByCity = $query->fetch_all(MYSQLI_ASSOC) ;

								$visitor_cities = [] ;
								$cities_percent = [] ;
								$city_color = [] ;

								foreach($subscriberByCity as $key=>$value) {
									$visitor_cities[] = $value["city"] ;
									$cities_percent[] = round($value["subscriber_percent"],2) ;

									if ( $key <= count($color_arr) ) {
										$city_color[] = $color_arr[$key] ;
									}
									else {
										$city_color[] = $color_arr[count($subscriberByCity)-$key] ;
									}
								}

								$visitor_cities = implode("','", $visitor_cities) ;
								$cities_percent = implode("','", $cities_percent) ;
								$city_color = implode("','", $city_color) ;


								?>
								<div class="row">
									<div class="col-4 text-center">
										<div id="sparkline-1"></div>
										<a href="javascript:void(0);">
											<div class="text-dark" onclick="showTopVisitor(1);loadVisitorChart1(['<?=$visitor_country?>'],['<?=$country_percent?>'],['<?=$country_color?>']);">By Country</div>
										</a>
									</div>
									<!-- <div class="col-4 text-center">
										<div id="sparkline-2"></div>
										<a href="javascript:void(0);">
										<div class="text-dark" onclick="showTopVisitor(2)">By State</div>
										</a>
										</div> -->
									<div class="col-4 text-center">
										<div id="sparkline-3"></div>
										<a href="javascript:void(0);">
											<div class="text-dark" onclick="showTopVisitor(3);loadVisitorChart1(['<?=$visitor_cities?>'],['<?=$cities_percent?>'],['<?=$city_color;?>']);">By City</div>
										</a>
									</div>
									<!-- ./col -->
								</div>
								<!-- /.row -->
							</div>
							<div class="card-body">
								<table class="table top-location-table">
									<thead>
										<tr>
											<th scope="col">Country</th>
											<th scope="col">Visitors</th>
										</tr>
									</thead>
									<tbody>
									<?php 
										// foreach($subscriberByCountry2 as $value) {
											
										// 	$browsers_name[] = $value["browser_family"] ;
										// 	$browers_percent[] = round($value["subscriber_percent"],2) ;
										// }
										// foreach($colors as $value) {
										// 	$color=$value;
										// }

										$query = $conn->query( " SELECT country , COUNT(country) AS subscriber_count , COUNT(country)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' GROUP BY country ORDER BY subscriber_count DESC LIMIT 5" ) ;
											

										if ( $query->num_rows > 0 ) 
										{
											$subscriberByCountry = $query->fetch_all(MYSQLI_ASSOC) ;

											foreach($subscriberByCountry as $subscriberByCountry) {
											    ?>
												<tr>
													<td><?=$subscriberByCountry['country'];?></td>
													<td><?=round($subscriberByCountry['subscriber_percent'], 2);?>%</td>
												</tr>
												<?php
											}
										}
										else {
											?><tr><td colspan="2">No Data Found</td></tr><?php
										}
									?>
									</tbody>
								</table>

								<table class="table top-location-table d-none">
									<thead>
										<tr>
											<th scope="col">State</th>
											<th scope="col">Subscribers</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$subscriberByState = [] ;
										foreach($subscriberByState as $value) {
										    ?>
											<tr>
												<td><?=$value['state'];?></td>
												<td><?=round($value['subscriber_percent'], 2);?>%</td>
											</tr>
											<?php
										}
									?>
									</tbody>
								</table>
											
							
								<table class="table top-location-table d-none">
									<thead>
										<tr>
											<th scope="col">City</th>
											<th scope="col">Visitors</th>
										</tr>
									</thead>
									<tbody>
									<?php

										$query = $conn->query( " SELECT city , COUNT(city) AS subscriber_count , COUNT(city)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' GROUP BY city ORDER BY subscriber_count DESC LIMIT 5 " ) ;
										
										if ( $query->num_rows > 0 ) 
										{
											$subscriberByCity = $query->fetch_all(MYSQLI_ASSOC) ;
											foreach($subscriberByCity as $value) {
											    ?>
												<tr>
													<td><?=$value['city'];?></td>
													<td><?=round($value['subscriber_percent'], 2);?>%</td>
												</tr>
												<?php
											}
										}
										else {
										   	?><tr><td colspan="2">No Data Found</td></tr><?php
										}
									?>
									</tbody>
								</table>


							</div>
							<!-- /.card-body-->

						</div>
						<div class="col-md-8">

							<?php

							?>
							<div id="visitor-location-box">
								<canvas id="visitor-location-chart"></canvas>
							</div>
							<div id="visitor2-location-box">
								<canvas id="visitor-location-chart"></canvas>
							</div>
						</div>
					</div>
					<?php
					// require_once('owner-dashboard.php');
				?>
				</div>
									</div>
			</div>
		</div>
<?//=$color;?>
	</body>
</html>

<script type="text/javascript">
// // set the countdown date
// var target_date = new Date().getTime() + (1000*<?=$subscription_diff;?>);

// var days, hours, minutes, seconds; // variables for time units

// var countdown = document.getElementById("tiles"); // get tag element

// getCountdown();

// var subscriptionInterval = "" ;

// function getCountdown(countdown){

//   // find the amount of "seconds" between now and target
//   var current_date = new Date().getTime();
//   var seconds_left = (target_date - current_date) / 1000;

//   days = pad( parseInt(Math.ceil(seconds_left / 86400)) );

//   // format countdown string + set tag value
//   countdown.innerHTML = "<span>" + days + "</span>"; 

// 	if ( parseInt(days) <= 0  ) {

// 		clearInterval(subscriptionInterval);
// 		countdown.innerHTML = "<span>00</span>"; 

// 		$.ajax({
// 			url: "inc/expire-plan.php",
// 			method:"POST",
// 			dataType:"JSON",
// 		}).done(function(reponse){});
// 	}
// 	else {
// 		var subscriptionInterval = setInterval(function () { getCountdown(); }, 1000);
// 	}
// }

// function pad(n) {
//   return (n < 10 ? '0' : '') + n;
// }

// ===================================================

function loadVisitorChart(label,data,backgroundColor) {

	// alert("call") ;

	var ctx = document.getElementById('visitor-chart').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'bar',

	    // The data for our dataset
	    data: {
	        labels: label,
	        datasets: [{
	            label: 'Visitor',
	            backgroundColor: backgroundColor ,
	            borderColor: backgroundColor,
	            data: data
	        }]
	    },

	    // Configuration options go here
	    options: {
		    scales: {
	        yAxes: [{
						ticks: {
							beginAtZero:true
						}
	        }]
	     	}
	    }
	});
}

loadVisitorChart(['<?=$chartLabel?>'],['<?=$chartData?>'],['<?=$chartColor?>']) ;

$("#visitor-period").change(function(){
	var v = $(this).val() ;
	var o = $(this).attr("data-owner") ;
	var p = $(this).attr("data-website") ;

	var req = $.ajax({
	    url:'inc/update-visitor.php',
	    method: 'POST',
	    dataType: 'JSON',
	    async : false ,
	    data : {opt:v , owner:o , website:p }
	}) ;

    req.done(function(reponse){

        var labels = reponse.label.split(",") ;
        var datas = reponse.data.split(",") ;
        var colors = reponse.color.split(",") ;
        
        // ----------------------- 
        $("div#visitor-chart-box").html("") ;
        $("div#visitor-chart-box").html('<canvas id="visitor-chart"></canvas>') ;
        loadVisitorChart(labels,datas,colors) ;
    });

    req.fail(function(reponse){
        console.log(reponse);
    });

    req.always(function(){});
});

function showTopVisitor(t) {
    t = t -1 ;
    $(".top-location-table").removeClass("d-none").addClass("d-none") ;
    $(".top-location-table:eq("+t+")").removeClass("d-none") ;
}

</script>

<script type="text/javascript">
loadVisitorChart1(['<?=$visitor_country?>'],['<?=$country_percent?>'],['<?=$country_color?>']);

function loadVisitorChart1(label,data,backgroundColor) {

	$("div#visitor-location-box").html("") ;
	$("div#visitor-location-box").html('<canvas id="visitor-location-chart"></canvas>') ;

	var ctx = document.getElementById('visitor-location-chart').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'pie',

	    // The data for our dataset
	    data: {
	        labels: label,
	        datasets: [{
	            label: '',
	            // backgroundColor: ['#7e7d7d','#ffc107'] ,
	            backgroundColor: backgroundColor ,
	            borderColor: backgroundColor,
	            data: data
	        }]
	    },
	    options: {
	        plugins: {
	            title: {
	                display: true,
	                text: 'Custom Chart Title'
	            }
	        }
	    }
	});
}
// function loadVisitorChart2(label,data) {

// 	$("div#visitor2-location-box").html("") ;
// 	$("div#visitor2-location-box").html('<canvas id="visitor2-location-chart"></canvas>') ;

// 	var ctx = document.getElementById('visitor2-location-chart').getContext('2d');
// 	var chart = new Chart(ctx, {
// 	    // The type of chart we want to create
// 	    type: 'pie',

// 	    // The data for our dataset
// 	    data: {
// 	        labels: label,
// 	        datasets: [{
// 	            label: 'Visitor',
// 	            backgroundColor: 'rgb(241, 56, 66)',
// 	            // backgroundColor: ["rgb(255, 99, 132)","rgb(54, 162, 235)","rgb(255, 205, 86)"],
// 	            borderColor: '#f13842',
// 	            data: data
// 	        }]
// 	    },
// 	    options: {
// 	        plugins: {
// 	            title: {
// 	                display: true,
// 	                text: 'Custom Chart Title'
// 	            }
// 	        }
// 	    }
// 	});
// }
var l = localStorage.getItem("waitTime"); 
window.localStorage.clear(); 
localStorage.setItem("waitTime",l);
</script>