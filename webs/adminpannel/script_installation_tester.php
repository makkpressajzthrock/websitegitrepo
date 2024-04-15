<?php
	include('config.php');
	require_once('meta_details.php');
	require_once('inc/functions.php') ;
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
				<div class="container-fluid content__up script_inst_pay">
					<h1>Script Installation Payment</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="responsive profile_tabs">
						<div class="table_S">
						<table class="table" id="mytable">
							<thead>
								<tr>
									<td>S No.</td>
									<td>Manager Name</td>
									<td>Email</td>
									<td>Website</td>
									<td>Plan Name</td>
									<td>Request Type</td>
									<td>Request Date</td>
									<td>Status</td>
									<td>Action</td>
								</tr>
							</thead>
							<tbody>
							<?php $sno=0;
							$check_installation_data =getTableData( $conn , " generate_script_request " , " 1 " , " order by id desc" , 1 ) ;
								$email = "";
								foreach ($check_installation_data as $key => $value) {
									// print_r($value);
									$manager=getTableData( $conn , " admin_users " , "id='".$value['manager_id']."' " );
									$boost_website=getTableData( $conn , " boost_website " , "id='".$value['website_id']."' " );
									$help=getTableData( $conn , " help_support_tickets " , "website_id='".$value['website_id']."' " );

									$plan_id = $boost_website['plan_id'];
									$plan = getTableData( $conn , " plans " , "id='".$plan_id."' " );
									if($plan['name']=='Free'){
										$plan_name= 'Basic Plan';										
									}else{										
										$plan_name=$plan['name'];
									}
									$email = $manager['email'];
									$managerName = $manager['firstname']." ".$manager['lastname'];
										$stats = " ";
									$status = $value['status'];
									if ($status == 1  || $status == 3) 
									{
										$stats = "Completed";
									}
									elseif ($status == 11) 
									{
										$stats = "Access Pending";
									}									
									elseif ($status == 12) 
									{
										$stats = "Email Not Sent";
									}									
									elseif ($status == 13) 
									{
										$stats = "Client Will Reply Later";
									}	
									elseif ($status == 14) 
									{
										$stats = "Wrong Credentials";
									}
									elseif ($status == 15) 
									{
										$stats = "Client Not Replying";
									}
									elseif ($status == 16) 
									{
										$stats = "Not Possible";
									}
																												
									else
									{
										$stats = "Pending";
									}

									$full = strtolower($managerName);

									if(str_contains($full,"makkpress") ){

									
					
								?>
									<tr>
										<td><?php echo ($sno = $sno+1);?></td>
										<td><?php echo $managerName;?></td>
										<td><?php echo $email;?></td>
										<td><?php echo !empty($value['website_url'])?$value['website_url']:'NA' ;?></td>	
										<td><?php echo $plan_name;?></td>
										<td><?php echo isset($help['ticket_type'])?$help['ticket_type'] : 'NA' ;?></td>
										<td><?php echo isset($help['created_at']) ? $help['created_at'] : 'NA';?></td>
										<td><?php echo $stats;?></td>
										<td><a href="installation_payment.php?id=<?php echo base64_encode($value['id']);?>" style="text-decoration: none;"><button type="button" class="btn btn-success"><svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path></svg></button></a></td>
									</tr>
							<?php
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
	</body>
	<script>
		$(document).ready(function () {
	        $('#mytable').DataTable();
	    });	
	</script>
</html>