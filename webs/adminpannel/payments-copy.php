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
			<div class="container-fluid content__up web_owners">
				<h1 class="mt-4">Payment</h1>
				<?php require_once("inc/alert-status.php") ; ?>

				<?php

					// $qry = "SELECT * FROM user_subscriptions ";
					$qry = "SELECT user_subscriptions.id , user_subscriptions.user_id , user_subscriptions.created , user_subscriptions.plan_interval , user_subscriptions.payment_method , user_subscriptions.paid_amount , user_subscriptions.paid_amount_currency , user_subscriptions.plan_period_start , user_subscriptions.plan_period_end , payment_info.amount , payment_info.email , payment_info.currency , payment_info.created_at , payment_info.updated_at FROM `payment_info` LEFT JOIN user_subscriptions ON payment_info.customer_id = user_subscriptions.stripe_customer_id ORDER BY `payment_info`.`updated_at` DESC;";
					
					// user_subscriptions_log

					/***
					tung@rosental.de


					cus_PFu0fKyt5ZxDL8

					https://websitespeedy.com/adminpannel/payments-copy.php

$year = date("Y");
						$month = date("m");

$qry_month = " select sum(paid_amount) as paid_amount  from user_subscriptions where paid_amount_currency = 'usd' and CONCAT(YEAR(created),MONTH(created)) = CONCAT($year,$month)  ";

SELECT  SUM(payment_info.amount)/100 AS sum_amount , user_subscriptions.paid_amount_currency FROM `payment_info` LEFT JOIN user_subscriptions ON payment_info.customer_id = user_subscriptions.stripe_customer_id WHERE user_subscriptions.paid_amount_currency = 'usd' and CONCAT(YEAR(payment_info.created_at),MONTH(payment_info.created_at)) = CONCAT(2024,01);

500 + 500 + 250 + 250 + 25 + 20 + 20 = 1565

					***/ 

					$cont_qry=mysqli_query($conn,$qry);
				?>
				<div class="profile_tabs">
					<div class="table_S">
						<table class="table speedytable">
							<thead>
								<tr>
									<th>Date</th>
									<th>Name</th>
									<th>Email</th>
									<th> Payment Method</th>
									<th>Paid Amount</th>
									<th>Start Plan Period</th>
									<th>End Plan Period</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
								$index=1;
								$total_amount = 0 ;
								while($run_qry=mysqli_fetch_array($cont_qry))
								{

									if ( empty($run_qry['user_id']) ) {
										continue ;
									}

									$uid=$run_qry['user_id'];
									$qry2="select * from admin_users where id='$uid'";
									$cont_qry2=mysqli_query($conn,$qry2);
									$run_qry2=mysqli_fetch_array($cont_qry2) ;

									$total_amount = $total_amount + $run_qry['amount'] ;
									
									?>
									<tr>
										<td><?php echo $run_qry['updated_at']  ?></td>
										<td><?php echo $run_qry2['firstname'];?></td>
										<td><?php echo $run_qry2['email'];?></td>
										<td>
										<?php 
											if( $run_qry['plan_interval'] == "Lifetime" ){
												echo "Lifetime";
											}
											else{
												echo $run_qry['payment_method'];
											}
										?>
										</td>
										<td><?php echo number_format($run_qry['amount']/100,2);?> <?php echo strtoupper($run_qry['currency']);?> </td>
										<td>
										<?php        
											$timedy= $run_qry['plan_period_start'];
											$vartime = strtotime($timedy);

											$datetimecon= date("F d, Y H:i", $vartime); echo $datetimecon ;
										?>
										</td>
										<td>
										<?php
											$timedy2= $run_qry['plan_period_end'];
											$vartime2 = strtotime($timedy2);

											$datetimecon2= date("F d, Y H:i", $vartime2); echo $datetimecon2 ;
										?>	  	
										</td>
										<td class="button__td">
											<a href="<?=HOST_URL."adminpannel/view-payments.php?view-details=".base64_encode($run_qry["id"])?>"  title="View" class="btn btn-primary">
												<svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
													<path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path>
												</svg>
											</a>
											<a href="<?=HOST_URL."adminpannel/cron/Invoice.php?viewinvoice=".base64_encode($run_qry["id"])?>" title="Send invoice" class="btn btn-primary">
												<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">
													<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
														<path d="M69 851 l-24 -19 -3 -291 -3 -291 46 0 45 0 0 270 0 270 330 0 330 0 0 40 0 40 -349 0 c-328 0 -350 -1 -372 -19z"/>
														<path d="M255 698 c-43 -25 -44 -33 -45 -280 0 -236 0 -237 24 -265 l24 -28 161 -3 161 -4 0 46 0 46 -145 0 -145 0 0 150 c0 83 2 150 5 150 3 0 66 -38 141 -85 74 -47 141 -85 147 -85 7 0 73 38 147 85 l135 84 3 -48 c3 -42 9 -53 47 -91 l45 -44 0 163 0 163 -29 29 -29 29 -314 0 c-217 -1 -319 -4 -333 -12z m615 -90 c0 -12 -259 -180 -285 -185 -13 -3 -288 170 -293 185 -3 9 68 12 287 12 226 0 291 -3 291 -12z"/>
														<path d="M830 272 l0 -62 -80 0 -80 0 0 -45 0 -45 80 0 80 0 0 -55 c0 -30 3 -55 8 -55 4 0 42 35 85 78 l77 77 -85 85 -85 85 0 -63z"/>
													</g>
												</svg>
											</a>
										</td>
									</tr>
									<?php
								}
							?>
							</tbody>
						</table>

						<input type="hidden" value="<?=$total_amount/100?>">
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.speedytable').DataTable({"order": [[ 0, "desc" ]]});
});
</script>

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

<script>
$( document ).ready(function() {
	$("#sidebarToggle").click(function(){
		$("body").toggleClass("sb-sidenav-toggled");
	});
});
</script>
</html>