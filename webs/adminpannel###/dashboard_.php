<?php 
require_once('config.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;

if($_SESSION['role'] == 'admin'){
	header("location:".HOST_URL."adminpannel/home.php");
}

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

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

		// echo $plan_country;
$user_type = ""; 
$getting_speed = 0;

if($row['flow_step']==1){
		$user_id = $_SESSION["user_id"] ; 
		$get_flow = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
		$d = $get_flow->fetch_assoc();

		if(($row['user_type'] == "AppSumo" || $row['user_type'] == "Dealify" || $row['user_type'] == "DealFuel" ) && $row['sumo_new'] == "1"){
			$user_type = "AppSumo"; 
		}else{

		header("location: ".HOST_URL."plan".$plan_country.".php?sid=".base64_encode($d['id']));
		die() ;	
	}
}
if($row['flow_step']==0){
header("location: ".HOST_URL."customize-flow.php");
	die() ;	
}


if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}


?>
<?php require_once("inc/style-and-script.php") ; ?>
<style>
	.loader {
		z-index: 9999;
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
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up dashboard_SS" >
					

					<?php
						$user_id = $_SESSION["user_id"] ;
						// My Projects
					?>
					<h1 class="mt-4">My Projects</h1>
                    <div class="alert_S">
					<?php require_once("inc/alert-status.php") ; ?>
</div>
                    	<div class="loader">Please Wait...</div>

					<div class="row custom-row-gap">
						<?php
						$websites = "<select class='form-control' id='website_popup_list'>";

						// get website projects
						$projects = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " , "order by id asc" , 1  ) ;

						$project_id = $projects[0]['id'];
						$Sumocode_site = $projects[0]['id'];
					$speed = getTableData( $conn , " pagespeed_report " , " website_id ='$project_id' " );

							if(count($projects) == 0 && $_SESSION['role'] == 'team'){

							$query = $conn->query("SELECT parent_id FROM `admin_users` WHERE id = ".$_SESSION['user_id']) ;

							$query = $query->fetch_assoc();



							$projects = getTableData( $conn , " boost_website " , " manager_id = '".$query['parent_id']."' " , "" , 1  ) ;

							}
							$ii =0;
						foreach ($projects as $key => $project_data) 
						{
							$websites .= "<option value='".$project_data['id']."'>".parse_url($project_data["website_url"])["host"]."</option>";
							// print_r($project_data) ; echo "<hr>";


							

							if ( $project_data['subscription_id'] == "111111" || $project_data['subscription_id'] == 111111 ) {
								
								$newappsumo = 0 ;

								$tmp_user_query = $conn->query( " SELECT * FROM `admin_users` WHERE `id` = '".$_SESSION['user_id']."' ; " ) ;

								$tmp_user_data =  $tmp_user_query->fetch_assoc() ;

								// print_r($tmp_user_data["user_type"]) ;
								if( $tmp_user_data["user_type"] == "Dealify" || $tmp_user_data["user_type"] == "AppSumo"  || $tmp_user_data["user_type"] == "DealFuel" ) {
									$newappsumo = 1 ;
								}
							}
							
							?>

							<div  style="display: none;" id="page-speed-table" data-project="<?=$project_data['id']?>" data-type="page-speed">
								 
								<?php
								// echo count($speed);
									if(count($speed )<1 && $ii == 0){
										$getting_speed = 1;

								?>
								
								<button type="button" class="btn btn-primary reanalyze-btn-new" data-website_name="<?=$project_data["website_url"]?>" data-website_url="<?=$project_data["website_url"]?>" data-website_id="<?=$project_data["id"]?>" data-additional="0"><svg class="svg-inline--fa fa-arrows-rotate" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrows-rotate" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H336c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v51.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V448c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H176c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"></path></svg><!-- <i class="fa fa-refresh" aria-hidden="true"></i> Font Awesome fontawesome.com --></button>
								<script type="text/javascript">
									$(document).ready(function(){
									$(".reanalyze-btn-new").click();
									});
								</script>

								<?php
									}	
									$ii++;	
								?>								
							</div>

							<?php



							// get page view
							
							$query = $conn->query(" SELECT ip FROM website_visits WHERE manager_id='$user_id' AND website_id='".$project_data["id"]."' AND ip <> '' GROUP BY ip ") ;

							$today_visitor_arr = ($query->num_rows > 0 ) ? $query->num_rows : 0 ;

							// pagespeed_report -----------------
							$wd_query = $conn->query(" SELECT * FROM `pagespeed_report` WHERE `website_id` = '".$project_data["id"]."' AND `parent_website` = '0' ORDER BY `pagespeed_report`.`id` DESC LIMIT 1 ") ;

							$wd_desktop = $wd_mobile = "-" ;

							if ( $wd_query->num_rows > 0 ) 
							{
								$wd_data = $wd_query->fetch_assoc() ;
								$wd_categories = unserialize($wd_data["categories"]) ;

								$wd_performance = round($wd_categories["performance"]["score"]*100,2) ;
								$wd_desktop = $wd_performance."/100" ;

								$wd_mobile_categories = unserialize($wd_data["mobile_categories"]) ;
								$wd_mobile = round($wd_mobile_categories["performance"]["score"]*100,2)."/100" ;
							}

							$plan__id=$project_data['plan_id'];
							$plan_info_query = "SELECT * from plans WHERE id = '$plan__id'";

							if(!empty($project_data['id'])){
								$count_url=1;
							}

				$plan_info_query = $conn->query($plan_info_query);

				 $plan_info_query = $plan_info_query->fetch_assoc();
				 $view_max = $plan_info_query['page_view'];
				 $view_max = $plan_info_query['page_view'];
				 // $web_id=;

				//  $addon_url2="SELECT *  FROM `addon_site` WHERE `site_id` = '".$project_data["id"]."' and is_active='1' ";
				 // echo $addon_url2;
				// $result_url2=$conn->query($addon_url2);
				// $additional_url_have = $result_url2->fetch_assoc()['addon_count'];

				// get count
				$sql = "SELECT SUM(addon_count)  FROM `addon_site` WHERE `site_id` = '".$project_data["id"]."' and is_active='1' ";
				$result = $conn->query($sql);
				//display data on web page
				while($row = mysqli_fetch_array($result)){

					$additional_url_have =$row['SUM(addon_count)'];
					// echo " Total cost: ". $row['SUM(addon_count)'];
					// echo "<br>";
				}	
				//get count end
				$url_data_or="SELECT COUNT(*) as url_or FROM boost_website WHERE   `id` ='".$project_data["id"]."';";
				// echo $url_data_or;

				 $result_url_or=$conn->query($url_data_or);
				 $url_or = $result_url_or->fetch_assoc()['url_or'];


				 $url_data="SELECT COUNT(*) as url_num  FROM `additional_websites` WHERE website_name <> '' and website_url <> '' and `manager_id` = '$user_id' AND `website_id`='".$project_data["id"]."' AND flag='true' ";

				 // echo "url_data".$url_data;
				 $result_url=$conn->query($url_data);
				 if ($result_url->num_rows) {
				 $additional_url = $result_url->fetch_assoc()['url_num'];
				
				 // $additional_url=7;
				 if ($additional_url >=0 ) {
				// $addon_url="SELECT COUNT(*) as num_url  FROM `addon_site` WHERE `id` = '".$project_data["id"]."' ";
				// $result_url=$conn->query($result_url);
				// $additional_url2 = $result_url->fetch_assoc()['addon_count'];
				// $additional_url2=6;
				 $additional_url_num= $additional_url ;


				 }else{
				 	$additional_url_num=0;
				 }
				 	
				 }
				 // $additional
				  $timedy2=$project_data['updated_at'];
                                          $vartime2 = strtotime($timedy2);

                                             $datetimecon2= date("F d, Y H:i", $vartime2);

				 
							$subsc_id_url = base64_encode($project_data["subscription_id"]) ; 
							?>

							<div class="col-md-4 with__shadow">
								<div href="javascript:void(0)" style="    text-decoration: none;">
								<ul class="list-group">
									<li class="list-group-item"><?=parse_url($project_data["website_url"])["host"]?></li>
									<li class="list-group-item">Platform <span class="float-right"><?=$project_data["platform"]?><?=($project_data["platform"]=="Other")?"(".$project_data["platform_name"].")":""?></span></li>
									<li class="list-group-item">Desktop Speed <span class="float-right"><?=$wd_desktop?></span></li>
									<li class="list-group-item">Mobile Speed <span class="float-right"><?=$wd_mobile?></span></li>
									<li class="list-group-item"> Pages View <span class="float-right"><?=$today_visitor_arr?>/<?=$view_max?></span></li>
									<li class="list-group-item">Consumed URL's  <span class="float-right"><?=$additional_url_num +$url_or;?>/<?=$additional_url_have +5 ;?></span></li>
									<li class="list-group-item">Last Updated <span class="float-right"><?php echo $datetimecon2;?></span>
									<li class="list-group-item">

										<?php	
										if ($project_data['plan_id'] != '999'){

										?>										
										<?php if($_SESSION['role'] != 'team'){ ?>

		

										<a href="<?=HOST_URL?>adminpannel/project-dashboard.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">View Details</a>

										<?php

										}
										else{



											$team_query_sql = "SELECT * from team_access WHERE team_id = ".$_SESSION['user_id']." AND website_id = ".$project_data["id"];

											$team_query = $conn->query($team_query_sql);

											$team_query = $team_query->fetch_assoc();

											if($team_query['dashboard'] != '' || $team_query['dashboard'] != null){

												?>

												<a href="<?=HOST_URL?>adminpannel/project-dashboard.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">View Details</a>

												<?php


											}
											else if($team_query['expert_help'] != '' || $team_query['expert_help'] != null){

												?>

												<a href="<?=HOST_URL?>adminpannel/expert-help.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">View Details</a>

												<?php


											}
											else if($team_query['speed_tracking'] != '' || $team_query['speed_tracking'] != null){


												?>

												<a href="<?=HOST_URL?>adminpannel/page-speed.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">View Details</a>

												<?php



											}
											else if($team_query['speed_warranty'] != '' || $team_query['speed_warranty'] != null){


												?>

												<a href="<?=HOST_URL?>adminpannel/speed-warranty.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">View Details</a>

												<?php



											}else if($team_query['page_speed'] != '' || $team_query['page_speed'] != null){

												?>

												<a href="<?=HOST_URL?>adminpannel/page-speed.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">View Details</a>

												<?php


											}else if($team_query['script_install'] != '' || $team_query['script_install'] != null){

												?>

												<a href="<?=HOST_URL?>adminpannel/script-installation1.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">View Details</a>

												<?php



											}else if($team_query['need_other'] != '' || $team_query['need_other'] != null){

												?>

												<a href="<?=HOST_URL?>adminpannel/other-help.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">View Details</a>

												<?php



											}
											// else{

											// 	?>
											<!-- // 	<a href="<?=HOST_URL?>adminpannel/project-dashboard.php?project=<?=$project_data["id"]?>" class="btn btn-primary">View Details</a> -->
											<?php

											// }
											
										}
										?>
										<a href="<?=HOST_URL?>adminpannel/edit-website.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">Edit Website</a>

										
									<?php } else{  

										if($newappsumo == 0){
										?>
										<a href="<?=HOST_URL?>plan<?=$plan_country?>.php?sid=<?=base64_encode($project_data['id'])?>"  class="btn btn-primary">Subscribe Now</a>

									<?php }else { ?>
										<a href="javascript:void(0);" site_id="<?=$project_data['id']?>" class="btn btn-primary pay_now_add_code">Add Code</a>										
									<?php } ?>
										<a href="<?=HOST_URL?>adminpannel/edit-website.php?project=<?=base64_encode($project_data["id"])?>" class="btn btn-primary">Edit Website</a>
																				
									<?php } ?>	
									</li>
								</ul>
								</div>
							</div>
							<?php
						}

						$websites .= "</select>";

						?>	


						
						<div class="col-md-4 add-new-project with__shadow">
							<a href="add-website.php">
							<?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN"
 "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="994.000000pt" height="759.000000pt" viewBox="0 0 994.000000 759.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,759.000000) scale(0.100000,-0.100000)"
fill="#38517A" stroke="none">
<path d="M2413 6900 c-57 -23 -72 -105 -28 -149 l25 -26 1893 -3 c1411 -2
1899 1 1920 9 28 12 57 54 57 84 0 32 -30 73 -61 84 -40 14 -3772 15 -3806 1z"/>
<path d="M1813 6108 c-12 -6 -29 -26 -38 -44 -14 -30 -14 -37 -1 -69 30 -70
-179 -65 2547 -65 2216 0 2464 2 2494 16 58 27 69 92 26 145 l-19 24 -2494 2
c-1746 1 -2500 -1 -2515 -9z"/>
<path d="M1795 5313 c-291 -62 -527 -294 -601 -593 -18 -72 -19 -146 -19
-1745 0 -1599 1 -1673 19 -1745 70 -280 282 -502 561 -586 57 -18 153 -19
1790 -21 952 -2 1894 -2 2095 -1 l365 3 -50 34 c-27 19 -79 61 -114 93 l-64
58 -1943 0 c-2160 0 -2004 -5 -2153 68 -144 71 -261 222 -302 392 -21 82 -21
3328 0 3410 41 170 158 321 302 392 150 73 -61 68 2637 68 2691 0 2482 5 2632
-67 107 -51 197 -141 249 -247 41 -82 71 -185 71 -243 l0 -33 73 0 c39 0 82
-3 95 -6 20 -6 22 -3 22 35 0 60 -28 185 -58 260 -75 190 -243 358 -433 433
-154 62 16 58 -2661 57 -2130 -1 -2452 -3 -2513 -16z"/>
<path d="M7140 4144 c-379 -35 -709 -167 -997 -399 -131 -105 -242 -228 -341
-375 -55 -82 -154 -283 -189 -385 -167 -474 -132 -985 98 -1423 70 -134 105
-188 195 -301 260 -323 625 -532 1084 -617 132 -25 428 -25 559 -1 554 104
961 378 1234 830 159 263 250 596 249 912 0 380 -123 756 -348 1060 -267 363
-673 605 -1139 680 -98 16 -325 26 -405 19z m349 -199 c369 -54 645 -189 897
-440 302 -301 457 -680 457 -1120 0 -442 -154 -817 -457 -1120 -119 -118 -222
-196 -357 -268 -462 -249 -1068 -247 -1529 6 -248 135 -474 361 -612 610 -195
353 -244 798 -133 1212 106 393 384 745 745 942 177 97 324 142 610 186 65 10
283 5 379 -8z"/>
<path d="M7219 3357 c-37 -29 -39 -47 -39 -461 l0 -406 -405 0 c-452 0 -454 0
-475 -64 -16 -49 6 -99 50 -115 23 -7 160 -11 431 -11 l399 0 0 -405 c0 -476
0 -475 91 -475 45 0 65 15 83 60 14 35 16 94 16 430 l0 390 394 0 c262 0 404
4 425 11 31 11 61 52 61 84 0 29 -28 71 -55 83 -19 9 -137 12 -425 12 l-400 0
0 390 c0 413 -3 442 -48 476 -21 17 -82 17 -103 1z"/>
</g>
</svg>

								<p>Add New Project</p>
							</a>
						</div>
					</div>
				</div>	
			</div>
		</div>




		<div class="add-project-data" style="display: none;">

           <div class="popup_wrapper">
			<div class="close-popup-btn" style="display: none; cursor: pointer;"><i class="fa fa-times" aria-hidden="true"></i>
</div>
			<div class="web_list">
			<?php
	 	  $projects = getTableData( $conn , " user_subscriptions " , " user_id = '".$_SESSION['user_id']."' and  is_active = 1" , "" , 1  ) ;
 			
 		  if(count($projects)>0){ 
			foreach ($projects as $project_data) 
			{
		 	  $webs = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' and subscription_id='".$project_data['id']."' and plan_type = 'Subscription' " , "" , 1  ) ;

		 	  $av = $project_data['site_count'] - count($webs);

		 	  $av = $av." Available"; 

		 	  if($project_data['site_count'] =="Unlimited"){
		 	  	$av ="Unlimited";
		 	  }

		 	  $is_active="";
		 	  if($project_data['is_active']==0){
			 	  $is_active=" - Cancled";

		 	  }

			  $plan = getTableData( $conn , " plans " , " id ='".$project_data['plan_id']."' and status = 1" ) ;

      			echo '<li class="dropdown-header">'.$plan['name'].' Plan ('.$av.')'.$is_active.'</li>';

		 	  	if(count($webs)>0){
					foreach ($webs as $web) 
					{ 	  
						echo '<li><a href="'.HOST_URL.'adminpannel/project-dashboard.php?project='.base64_encode($web['id']).'">'.parse_url($web['website_url'])["host"].'</a></li>';
					}
				}
				if( $project_data['is_active'] == 1){
					echo '<li><a href="'.HOST_URL.'adminpannel/add-website.php?sid='.base64_encode($project_data['id']).'">Add Project</a></li>';
				}
				else{
					echo '<li class="disabled">Add Project</li>';
				}

      
			}
		}

?>
</div>
</div>
		</div>
	</div>


<script>
 
$(document).ready(function(){
 
	<?php if(($user_type == "AppSumo" || $user_type == "Dealify"  || $user_type == "DealFuel") && $getting_speed == 0){ ?>
		Swal.fire({
		       html:
			    '<div class="flex__wrapper" ><p>Enter your code here</p> ' +
				'<div class="hover__wrapper"><span class="i_hover">i</span><div class="show__on__hover"><p>If you add one code, Power Plan will be activated.</p><p>If you add two codes, Booster Plan will be activated.</p><p>If you add three codes, Super Plan will be activated.</p></div></div></div><p class="sumo_code_error" style="color:red; display:none;">Invalid Code</p>'+
			    '<div class="codes"><div class="code__select"><input type="hidden" id="site_id" value="<?=$Sumocode_site?>">'+"<div class='site'><label>Select website</label><?=$websites?></div>"+
			    '<input type="text" code_type="code1" class="form-control mb-2 code1" id="sumo1" placeholder="Code 1"/> <button class="btn btn-primary code11 codeVerifyd">Verify</button></div>' +
			    '<div class=""><input type="text"  code_type="code2" class="form-control mb-2 code2" id="sumo2" placeholder="Code 2"/> <button class="btn code22 btn-primary codeVerifyd">Verify</button></div>' +
			    '<div class=""><input type="text" code_type="code3" class="form-control mb-4 code3" id="sumo3" placeholder="Code 3"/> <button class="btn code33 btn-primary codeVerifyd">Verify</button></div></div>' + 
			    ''+
			    ' ',
				  showCloseButton: false,
				  showCancelButton: false,
                  showConfirmButton: false,
                  allowOutsideClick: false,
                  allowEscapeKey: false,			   
		});

	<?php } ?>		

});	




		$("body").on("click",".codeVerifyd",function(){

				var site_id = $("#website_popup_list").val();
				var code_type = $(this).prev().attr("code_type");
				var value = $(this).prev().val();
				var main_div = $(this);
				var s_id = $("#site_id").val();

				// alert("code_type="+code_type+", value="+value+", site_id="+site_id);
				
				if(value == ""){
					$("."+code_type).addClass("invalid");
					setTimeout(function(){
						$("."+code_type).removeClass("invalid");
					},3000);
				}
				else{


				    $.ajax({
				      type: "POST",
				      url: "verify_coupon.php",
				      data: {"code":value,"code_type":code_type,"user_id":'<?=$user_id?>', "s_id":s_id},
				      dataType: "json",
				      encode: true,
				    }).done(function (data) {	
						

					  if(data == 11){
				      			window.location.reload();
				      }
					     if(data != 11){
							$(".sumo_code_error").html("Invalid Code");
							$(".sumo_code_error").show();
						}

setTimeout(function(){
$(".sumo_code_error").hide();
},3000);




				    });

				}


		});



// 	$("body").on("click",".code11",function(){

// 		if($("#sumo1").val()==""){
// 			$(".sumo_code_error").html("Please enter code");
//         	$(".sumo_code_error").show();
// 			$("#sumo1").addClass("invalid");
// 		}
// 		else{
// 			$("#sumo1").removeClass("invalid");
// 		}
// setTimeout(function(){
// $(".sumo_code_error").hide();
// },3000);
// 	});


// 	$("body").on("click",".code22",function(){

// 		if($("#sumo2").val()==""){
// 			$(".sumo_code_error").html("Please enter code");
//         	$(".sumo_code_error").show();
// 			$("#sumo2").addClass("invalid");
// 		}
// 		else{
// 			$("#sumo2").removeClass("invalid");
// 		}
// setTimeout(function(){
// $(".sumo_code_error").hide();
// },3000);
// 	});


// 	$("body").on("click",".code33",function(){

// 		if($("#sumo3").val()==""){
// 			$(".sumo_code_error").html("Please enter code");
//         	$(".sumo_code_error").show();
// 			$("#sumo3").addClass("invalid");
// 		}
// 		else{
// 			$("#sumo3").removeClass("invalid");
// 		}
// setTimeout(function(){
// $(".sumo_code_error").hide();
// },3000);
// 	});


// 	$("body").on("click",".codeVerifyd",function(){
// 		$(".codeVerifyd").attr("disabled",true);
// 			var c1 = $("#sumo1").val();
// 			var c2 = $("#sumo2").val();
// 			var c3 = $("#sumo3").val();
// 			var coupon = "";

// 			if(c1 == "" && c2 == "" &&  c3 == ""){
// 				// $("#sumo1").addClass("invalid");
// 			}
// 			else if(c1!=""){
// 				coupon = c1;
// 			}
// 			else if(c2!=""){
// 				coupon = c2;
// 			}
// 			else if(c3!=""){
// 				coupon = c3;
// 			}


// 			if(coupon!=""){

// 				    $.ajax({
// 				      type: "POST",
// 				      url: "verify_coupons.php",
// 				      data: {"c1":c1,"c2":c2,"c3":c3,"user_id":'<?=$user_id?>'},
// 				      dataType: "json",
// 				      encode: true,
// 				    }).done(function (data) {
// 				    	$(".codeVerifyd").attr("disabled",false);

// 				     if(data != 11){
// 						$(".sumo_code_error").html("Invalid Code");

// 				      console.log(data['c1']);
// 				      if(data['c1'] == 0){
// 				      	$(".sumo_code_error").show();
// 				      	$("#sumo1").addClass("invalid");
// 				      }
// 				      else{
// 				      	$(".sumo_code_error").show();
// 				      	$("#sumo1").removeClass("invalid");
// 				      }

// 				      if(data['c2'] == 0){
// 				      	$(".sumo_code_error").show();
// 						$("#sumo2").addClass("invalid");				      	
// 				      }
// 				      else{
// 				      	$(".sumo_code_error").show();
// 				      	$("#sumo2").removeClass("invalid");
// 				      }

// 				      if(data['c3'] == 0){
// 				      	$(".sumo_code_error").show();
// 						$("#sumo3").addClass("invalid");				      	
// 				      }
// 				      else{
// 				      	$(".sumo_code_error").show();
// 				      	$("#sumo3").removeClass("invalid");
// 				      }	
// 						setTimeout(function(){
// 						$(".sumo_code_error").hide();
// 						},3000);

				      
// 				  }

// 				      if(data == 11){
// 				      			window.location.reload();
// 				      }		      


// 				    }).fail(function(data){
// 						   $(".codeVerifyd").attr("disabled",false);
// 					});

// 			}else{
// 				$(".codeVerifyd").attr("disabled",false);
// 			}



// 	});

</script>
	</body>
</html>
