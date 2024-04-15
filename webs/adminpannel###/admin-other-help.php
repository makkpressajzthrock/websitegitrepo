<?php 

include('config.php');
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
			
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid">
					<?php require_once("inc/alert-status.php") ; ?>
					<h1 class="mt-4">Owner Queries</h1>

					<table class="table speedy-table">
						<thead>
							<tr>
								<th>Created At</th>
								<!-- <th>#</th> -->
								<th>Owner Id</th>
								<th>Help Type</th>
								<th>Subject</th>
								<th>Fullname</th>
								<th>Email</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php

						$manger_queries = getTableData( $conn , " other_help " , "" , " ORDER BY created_at DESC " , 1 ) ;

						// print_r($expert_queries) ;

						foreach ($manger_queries as $key => $value) {
							$index = $index+1 ;
							// echo "<pre>";
							// print_r($value);
							?>
							<tr>
								<td><?=$value["created_at"];?></td>
								<!-- <td><?=$index;?></td> -->
								<td><?=$value["manager_id"];?></td>
								<th>Need Other Help</th>
								<td><?=$value["message_text"];?></td>
								
								<?php
								$msg_id=$value["id"];
						$manger_details_queries = getTableData( $conn , " admin_users " , "id='".$value["manager_id"]."'", " " , 1 ) ;
						// echo "<pre>";
						// print_r($manger_details_queries);
						foreach ($manger_details_queries as $key => $value) {
						?>
								<td><?=$value["firstname"]." ".$value["lastname"];?></td>
								<td><?=$value["email"];?></td>
								
								<td>
									<a href="<?=HOST_URL."adminpannel/other-query-view.php?msg_id=".$msg_id."&query=".$value["id"]?>&type=other" class="btn btn-primary">View</a>&nbsp;
								</td>
							</tr>
							<?php
						} }
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</body>
<script type="text/javascript">
	$(document).ready(function() {
	   // $(".speedy-table").DataTable();
	$('.speedy-table').DataTable({
        order: [[0, 'desc']],
    });

	} );
</script>
</html>