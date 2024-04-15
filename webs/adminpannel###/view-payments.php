<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
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
				<div class="container-fluid content__up view_payments">
					<?php require_once("inc/alert-status.php") ; ?>
					<?php

					$id=base64_decode($_GET['view-details']);
				
				 $qry="select * from user_subscriptions where id='$id'";
				 $cont_qry=mysqli_query($conn,$qry);
				 $run_qry=mysqli_fetch_array($cont_qry);

				 $uid=$run_qry['user_id'];
						$qry2="select * from admin_users where id='$uid'";
				 $cont_qry2=mysqli_query($conn,$qry2);
				 $run_qry2=mysqli_fetch_array($cont_qry2)
				
				?>
                <h1>View Details</h1>
				<div class="back_btn_wrap ">
					<a href="payments.php" type="button" class="btn btn-primary">Back</a>
				</div>

					<table class="table speedytable view_payment_d">
						
							<tr>
							   <th>Name</th>
							   <td><?php echo $run_qry2['firstname']; echo $run_qry2['lastname'];?></td>
							   </tr>

								<tr>
							   <th>Email id</th>
							   <td><?php echo $run_qry2['email']; ?></td>
							   </tr>

							   <tr>
							   <th>Phone</th>
							   <td><?php echo $run_qry2['phone']; ?></td>
							   </tr>
							   <tr>
							   <th>Address</th>
							   <td><?php echo $run_qry2['address_line_1']; ?> <?php  echo $run_qry2['address_line_2']; ?></td>
							   </tr>
							   <tr>
							   <th>Payment Method</th>
							   <td><?php echo $run_qry['payment_method']; ?> </td>
							   </tr>
							   <tr>
							   <th>Payment Status</th>
							   <td><?php echo $run_qry['status']; ?> </td>
							   </tr>
								 <tr>
							   <th>Paid Amount</th>
							   <td><?php echo $run_qry['paid_amount']; ?> </td>
							   </tr>
								 <tr>
							   <th>Start Plan Period</th>
							   <td><?php         $timedy=  $run_qry['plan_period_start'];
                                           $vartime = strtotime($timedy);

                                             $datetimecon= date("F d, Y H:i", $vartime); echo $datetimecon; ?> </td>
							   </tr>
							    <tr>
							   <th>End Plan Period</th>
							   <td><?php         $timedy2=  $run_qry['plan_period_end'];
                                           $vartime2 = strtotime($timedy2);

                                             $datetimecon2= date("F d, Y H:i", $vartime2); echo $datetimecon2 ; ?> </td>
							   </tr>
								
								 <tr>
							   <th>Site</th>
							   <td><?php echo $run_qry['site_count']; ?> </td>
							   </tr>
							   <tr>
							   <th>Plan Interval</th>
							   <td><?php echo $run_qry['plan_interval']; ?> </td>
							   </tr>
							   <tr>
							   <th>Payer Email</th>
							   <td><?php echo $run_qry['payer_email']; ?> </td>
							   </tr>
							    <tr>
							   <th>Status</th>
							   
							   <td><?php $ac="Active";
							    $inc="Inactive";
                                     $is_ac=$run_qry['is_active'];
							   	if($is_ac==1){

							   		echo $ac;
							   	}else{
							   		echo $inc;
							   	}
							   ?> </td>
							   </tr>
			

						
						
					</table>
				</div>
			</div>
		</div>

	</body>
</html>
