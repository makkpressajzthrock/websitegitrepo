<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

// print_r($_SESSION) ;

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
				<div class="container-fluid content__up expert_queries ">
					<?php require_once("inc/alert-status.php") ; ?>
					<h1>Expert Queries</h1>
					<div class="profile_tabs">
                     <div class="table_S">
					<table class="table speedytable">
						<thead>
							<tr>
							   <th> #</th>
								<th>Created At</th>
								<th>Website Name</th>
								<th>Help Type</th>
								<th>Fullname</th>
								<th>Email</th>
								<th>Subject</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php

						$expert_queries = getTableData( $conn , " expert_queries " , "" , " ORDER BY created_at desc " , 1 ) ;
						// echo "<pre>";
						// print_r($expert_queries) ;
                		$index=1;
						foreach ($expert_queries as $key => $value) {
						//	$index = $key+1 ;
							// echo "<pre>";
							// print_r($value) ;
							?>
							
							<?php 
							$manager_id=$value["manager_id"];
							$website_id=$value["website_id"];

						 							
							$expert_url = getTableData( $conn , " boost_website "  , "`manager_id` = '".$value["manager_id"]."' AND `id` = '".$value["website_id"]."'" ) ;
							// foreach ($expert_url as $key => $expert__url) {
// print_r($expert_url);
							$run_qrys = getTableData( $conn , " admin_users " , " id ='$manager_id'  " ) ;
								// print_r($run_qrys); echo "<hr>";

							?>
							 
							<tr>
								<td><?=$index;?></td>
								<td><?=$value["created_at"];?></td>
								<td><?php echo parse_url($expert_url['website_url'])['host'];?></td>
								
								<td><?=$value["help_type"];?></td>
								
								<td><?php echo $run_qrys['firstname'];?></td>
								<td><?php echo $run_qrys['email'];?></td>
							
								<td><?= substr($value["subject"],0,18);?>...</td>
								<td>
									<a href="<?=HOST_URL."adminpannel/query-view.php?query=".$value["id"]?>&type=expert" class="btn btn-primary"><svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path></svg></a>&nbsp;
								</td>
							</tr>
							<?php

							$index++;
						// } 

						//$expert_queries = getTableData( $conn , " expert_queries " , "" , " ORDER BY created_at DESC " , 1 //) ;

						// print_r($expert_queries) ;

						//foreach ($expert_queries as $key => $value) {
							//$index = $index+1 ;
							?>

							<?php
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
<script type="text/javascript">
	$(document).ready(function() {
	
	$('.speedytable').DataTable({
        order: [[0, 'asc']],
    });

	} );
</script>
</html>