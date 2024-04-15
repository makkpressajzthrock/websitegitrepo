<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;
 $subsc_id = base64_decode($_REQUEST['sid']);
 $subsc_id_url = $_REQUEST['sid'];
// $site_id = $_SESSION['site_id'];
// echo $site_id;
$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
 $user_id = $_SESSION['user_id'];

	

// echo $subsc_id;
$rowSubs = getTableData( $conn , " user_subscriptions " , " id ='".$subsc_id."' ") ;

$subs = "";

	$plan = getTableData( $conn , " plans " , " id ='".$rowSubs['plan_id']."' " ) ;
$plan_name = $plan['name']."";


$log = getTableData( $conn , " user_subscriptions_log " , " stripe_customer_id ='".$rowSubs['stripe_customer_id']."' ","order by id desc",1 ) ;

// echo '<pre>';
// print_r($rowSubs);

?>
<?php require_once("inc/style-and-script.php") ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid subscription_plan_S content__up ">
					
					<h1 class="mt-4">Subscription 
						<?php if($rowSubs['is_active'] == 1){ ?>
							<label class="bg-success green p-1 px-2 rounded text-light">Active</label>
						<?php }else{ ?>
							<label class="bg-danger rounded  sub_cancel_S">Canceled</label>
						<?php } ?>	
					</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="subscription_planS">
					<div class="details profile_tabs">
						<h3>Plan Details</h3>
 
							<table class="speedy-subscription table">
								<tr>
									<td>Name</td>
									<td><?=$plan_name?></td>
								</tr>
								<tr>
									<td>Interval</td>
									<td><?php 
													
													if($rowSubs['plan_interval'] == "Month Free"){
														        $sqlA = "SELECT * FROM coupons where code='".$rowSubs['discount_code_id']."'";
														        $resultA = mysqli_query($conn, $sqlA);
														        if(mysqli_num_rows($resultA) > 0){
														        	$coupons = mysqli_fetch_assoc($resultA);
														        	echo $coupons['duration'].' '.ucwords($rowSubs['plan_interval']);
														        }
														        else{
														        	echo ucwords($rowSubs['plan_interval']);
														        }

													}else{
														echo ucwords($rowSubs['plan_interval']);
													}

									?></td>
								</tr>
								<tr>
									<td>Recurring Amount</td>
									<td>

									<?php if(strtoupper($rowSubs['paid_amount_currency'])=="INR"){ ?>


												<?php 
													$amt = $rowSubs['plan_price'];
													$amt =  number_format((float)$amt, 2, '.', ''); 
												?>

										

									<?php 
									
										$tax = 18;
										$price = $amt;
										$total_tax = $price*$tax/100;
										$total_price = $price + $total_tax;

										$total_witn_tax = number_format((float)$total_price, 2, '.', ''); 
										echo "<br>".$total_witn_tax.' '.strtoupper($rowSubs['paid_amount_currency']);
										echo " (".$amt." ".strtoupper($rowSubs['paid_amount_currency'])." + 18% GST)";

									?>	
										

									<?php }else{ ?>	

												<?php 
													$amt = $rowSubs['plan_price'];
													$amt =  number_format((float)$amt, 2, '.', ''); 
												?>
										<?=$amt." ".strtoupper($rowSubs['paid_amount_currency'])?>

									<?php } ?>	


											
									</td>
								</tr>

								<tr>
									<td>Paid Amount</td>
									<td><?=$rowSubs['paid_amount']." ".strtoupper($rowSubs['paid_amount_currency'])?></td>
								</tr>

								<tr>
									<td>Website Limit</td>
									<td><?=$rowSubs['site_count']?></td>
								</tr>
							</table>

					</div>
                   
  

       <?php 
				
				// Start subscription cancel url
				$cancleUrl = "";

				// print_r($rowSubs) ;

				if ( $rowSubs["stripe_subscription_id"] == "xxxxxxxxxxxx" ) {
					$cancleUrl = HOST_URL."payment/cancel_subscription_manual.php?sid=".base64_encode($rowSubs['id']);
				}
				else {
					if($rowSubs['paid_amount_currency']=='usd'){
						$cancleUrl = HOST_URL."payment/cancle_subscription.php?sid=".base64_encode($rowSubs['id']);
					}
					else{
						$cancleUrl = HOST_URL."payment/cancle_subscription_ind.php?sid=".base64_encode($rowSubs['id']);
					}
				}

				// End subscription cancel url

       ?>

					<div class="details  profile_tabs ">
						<h3>Subscription Details 
							<?php if($rowSubs['is_active'] == 1 && $rowSubs['plan_interval'] != "Lifetime" && $rowSubs['plan_interval'] != "Month Free"){ ?>
							<span data_cancle="<?=$cancleUrl?>" class="text-danger subs_cancle btn btn-light">Cancel Subscription</span>
						<?php } ?>
							</h3>
 
							<table class="speedy-subscription table">
								<tr>
									<td>Subscription Id</td>
									<td><?=$rowSubs['stripe_subscription_id']?></td>
								</tr>
								<tr>
									<td>Customer Id</td>
									<td><?=$rowSubs['stripe_customer_id']?></td>
								</tr>
								<tr>
									<td>Recurring Amount</td>
									<td>

									<?php if(strtoupper($rowSubs['paid_amount_currency'])=="INR"){ ?>


												<?php 
													$amt = $rowSubs['plan_price'];
													$amt =  number_format((float)$amt, 2, '.', ''); 
												?>

										

									<?php 
									
										$tax = 18;
										$price = $amt;
										$total_tax = $price*$tax/100;
										$total_price = $price + $total_tax;

										$total_witn_tax = number_format((float)$total_price, 2, '.', ''); 
										echo "<br>".$total_witn_tax.' '.strtoupper($rowSubs['paid_amount_currency']);
										echo " (".$amt." ".strtoupper($rowSubs['paid_amount_currency'])." + 18% GST)";

									?>	
										

									<?php }else{ ?>	

												<?php 
													$amt = $rowSubs['plan_price'];
													$amt =  number_format((float)$amt, 2, '.', ''); 
												?>
										<?=$amt." ".strtoupper($rowSubs['paid_amount_currency'])?>

									<?php } ?>	

									</td>	
								</tr>									
								<tr>
									<td>Paid Amount</td>
									<td><?=$rowSubs['paid_amount']." ".strtoupper($rowSubs['paid_amount_currency'])?></td>
								</tr>

								<tr>
									<td>Plan Started At</td>
									<td><?php         $timedy= $rowSubs['plan_period_start'];
                                           $vartime = strtotime($timedy);

                                             $datetimecon= date("F d, Y H:i", $vartime);
                                             echo $datetimecon;

                                             ?></td>
								</tr>
							<?php if($rowSubs['is_active'] != 1){ ?>
								<tr>
									<td>Plan End At</td>
									<td><?php    $timedy2=  $rowSubs['cancled_at'];
                                           $vartime2 = strtotime($timedy2);

                                             $datetimecon2= date("F d, Y H:i", $vartime2);
                                             echo $datetimecon2;
                                            ?></td>
								</tr>

						    <?php } ?>
							</table>

				</div>


							</div>
					 
<hr>



				<!-- For add on  -->
				
				<?php

$websites = getTableData( $conn , " boost_website " , " manager_id = '".$user_id."' and subscription_id = '".$subsc_id."'  " , "" , 1 ) ;
// echo "dsds";
// echo $websites[0]['id'];
// echo "<prev>";
// print_r($websites);
$query = $conn->query("SELECT * FROM `addon_site` WHERE status = 'succeeded' AND user_id='".$user_id."' AND site_id='".$websites[0]['id']."'  ") ;

if($query->num_rows > 0) 
{
	$i = 1;
	while($data = $query->fetch_assoc() ) 
	{
	   $plan_frequency_interval = $data['interval'];
		  if($data['id'] ==$user_subscription_id){ $status = 'active'; } 
		  

		?>

<div class="details  profile_tabs " style="<?php if($data['is_active'] != 1){echo "display:none"; }?>">
						<h3>Subscription Details 
							<?php if($data['is_active'] == 1){ ?>
							<button type="button" class="btn btn-primary cancle_btn" count="<?php echo $data['addon_count']?>" subscription_id="<?php echo $data['id'] ?>" site_id="<?php echo $data['site_id'] ?>"  >Cancelled Addon Plan</button>
						<?php } ?>
							</h3>
 
							<table class="speedy-subscription table">
								<tr>
									<td>Subscription Id</td>
									<td><?=$data['stripe_subscription_id']?></td>
								</tr>
								<tr>
									<td>Customer Id</td>
									<td><?=$data['stripe_customer_id']?></td>
								</tr>
								<tr>
									<td>Recurring Amount</td>
									<td><?=$data['paid_amount']." ".strtoupper($data['paid_amount_currency'])?></td>
								</tr>
								<tr>
									<td>Plan Started At</td>
									<td><?=$data['plan_period_start']?></td>
								</tr>
							<?php if($data['is_active'] != 1){ ?>
								<tr>
									<td>Plan End At</td>
									<td><?=$data['cancled_at']?></td>
								</tr>

						    <?php } ?>
							</table>

				</div>

<?php

		$i++;

	}
	
	
}
	
?>
<script>	$(".cancle_btn").click(function(){
   var subscription_id =$(this).attr('subscription_id');
   var site_id=$(this).attr('site_id');
   var count= $(this).attr('count');
   var subsc_id_url =`<?php echo $subsc_id_url; ?>`;
   // alert(c+","+p);

			Swal.fire({
				  title: 'Are you sure?',
				//   text: "When you delete your subscription your data will be deleted from our store related to this website.",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Cancle Subscription'
				}).then((result) => {
				  if (result.isConfirmed) {
					window.location.href="<?=HOST_URL?>payment/cancle_addon_plan.php?sid="+subscription_id+"&project="+subsc_id_url+"&site_id="+site_id+"&count="+count+" ";
				  }
				})
 


});</script>
				<!-- End for add on -->
            <div class="profile_tabs">
			<div class="log">
				<h3>Subscription Logs</h3>
				<div class="table_S">
					<table class="speedy-table table ">
						<thead>
							<tr>
								<th>#</th>
								<th>Subscription Id</th>
								<th>Subscription Type</th>
								<th>Date Time</th>
								<th>Descrtiption</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$sno = 0 ;

							// print_r($websites) ;

						
							if ( count($log) > 0 ) 
							{
								$sno = 1 ;
								$description = "";
								foreach ($log as $subs) {
									if($sno == 1){
										// $description = $subs['description'];
									}

$log1 = getTableData( $conn , " addon_site " , " stripe_subscription_id ='".$subs['stripe_subscription_id']."' ") ;
$log2 = getTableData( $conn , " plans " , " id ='".$rowSubs['plan_id']."' ") ;
// $log1 = getTableData( $conn , " user_subscriptions_log " , " stripe_customer_id ='".$rowSubs['stripe_customer_id']."' ","order by id desc",1 ) ;

									?>
									<tr>
										<td><?=$sno?></td>
										<td><?=$subs['stripe_subscription_id']?></td>
										<td><?php
											if(count($log1)>0){
											echo "Addon for ";
											echo $log1['addon_count'].' Sites.';
											}
											else{
												echo $log2['name'];
											}
												
										?>
										</td>
										<td><?php           $timedy4= $subs['log_at'];
                                           $vartime4 = strtotime($timedy4);

                                             $datetimecon4= date("F d, Y H:i", $vartime4);

                                           echo $datetimecon4;
                                           ?></td>
										<td>
											
											<?php 
											if($subs['cancled_at'] == ""){

													// if($log[$sno]['description'] != $description ){
													if( (count($log) == $sno) && (trim($subs['description']) == 'Subscription Created')){ 
														// echo $description;
														echo $subs['description'];

													}
													elseif((trim($subs['description']) != 'Subscription Created')) {
														echo $subs['description'];
													}
													else{
														echo "Updated";
													}
											}else{
												echo "Subscription Cancel";
											}

											?>	
										</td>
										<td>
											<?php
												echo ucwords(str_replace("_"," ",$subs['subscriptions_status']));
											?>

										</td>
										 
								

									</tr>
									<?php
									$sno++ ;
								}
							}
							else {
								// echo $sno;
								if($sno<1){
								?><tr><td colspan="6"> No Data found.</td></tr><?php
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
						</div>

	</body>
</html>



<script type="text/javascript">

	$(document).ready(function(){
		$(".subs_cancle").click(function(){
			var data_url = $(this).attr("data_cancle");
			Swal.fire({
				  title: 'Are you sure?',
				  text: "When you cancel your subscription your  website data will be deleted from our store related to this website.",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Cancel Subscription'
				}).then((result) => {
				  if (result.isConfirmed) {
				     	window.location.href=(data_url);
				  }
				})
		});
	});

</script>