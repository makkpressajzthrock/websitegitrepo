<?php 



include('config.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); error_reporting(E_ALL);

// print_r($_SESSION) ;
$manager_id=$_SESSION['user_id'];




$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}


$plan_data = getTableData( $conn , " user_subscription " , " user_id ='".$_SESSION['user_id']."' AND `status` LIKE 'active' ORDER BY `user_subscription`.`id` DESC " ) ;
if (count($plan_data)>0){
	$plan_id=$plan_data['plan_id'];

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
			.subscribe__cover{
						display: inline-table;
						background-color: white;
						width: 25%;
						padding: 10px;
						margin: 5px 0;

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
		</style>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			<?php require_once("inc/sidebar.php"); ?>
			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				<?php require_once("inc/topbar.php"); ?>
				<!-- Page content-->
				<div class="container-fluid manager_setting">
					
					<?php $projects = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " , "" , 1  );
					// echo $_SESSION['user_id'];
					foreach ($projects as $key => $project_data) 
						{ $web_plan_id=$project_data['plan_id'];
						 $managerid=$project_data['manager_id'];
						 $plan_type=$project_data['plan_type'];

						  ?>
						  <div class="subscribe__cover ">
						<div class="domain"><strong><?=parse_url($project_data["website_url"])["host"]?></strong></div>	


					<?php if ($web_plan_id !=0 ) { ?>
					<div class="tab subscribe_cover  ">
						<div class="row">
							<div class="Polaris-Card">
								<div class="Polaris-Card__Section">
									<div class="top-sec-card">
										<?php
											$select_plan = getTableData($conn , " plans " , " id ='".$web_plan_id."' AND status = 1 " ) ;
											?>
										<h2 class="plan-name">
											<?php echo $select_plan['s_type']; ?>
										</h2>
										<?php 
											if($select_plan['s_price'] != "") {
											    ?>
										<div class="price-tag"><span class="symbol"><?php if($i!=0){?>$<?php }?></span><span class="amount" subs="<?php echo $select_plan['id'];?>"><?php echo "$". $select_plan['s_price'];  ?></span> <span class="after"><span class="month-slash" >/</span>month</span></div>
										<?php 
											$web_plan_id_time=getTableData($conn , "user_subscriptions" , "user_id = '".$managerid."' AND plan_id = '".$web_plan_id."' AND paid_amount = '".$select_plan['s_price']."'" ) ;

											// print_r($web_plan_id_time);

											if (count($web_plan_id_time)) {
												
											
											?>
											<div class="plan_time">
												<div class="start_plan">
													Start Plan : <?=$web_plan_id_time['plan_period_start'];?>
												</div>
												<div class="end_plan">
													End Plan : <?=$web_plan_id_time['plan_period_end'];?>
													
												</div>
											</div>

											<?php  } } 
											?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php  }else{ echo "no subscription plan"; } ?>
					 </div>  

					<?php }	?>
						
						
						
						
						
					
				   
				</div>
			</div>
		</div>
	</body>
</html>


