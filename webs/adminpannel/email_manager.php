<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
 
$manager = base64_decode($_GET["manager"]);

$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");
 

if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}


?>

<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>View Email</title>
	<!-- Favicon-->
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
	<?php require_once('inc/style-and-script.php') ; ?>



	<?php require_once("inc/style-and-script.php") ; ?>

<body class="custom-tabel">
	<div class="d-flex" id="wrapper">
	<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>

		<!-- Page content wrapper-->
		<div id="page-content-wrapper">

			<?php require_once("inc/topbar.php"); ?>

			<!-- Page content-->
			<div class="container-fluid content__up tickets_a email__manager">


				<h1 class="mt-4">Email Logs</h1>
                    <div class="back_btn_wrap">
					<a href="<?=HOST_URL."adminpannel/managers.php"?>" class="btn btn-primary">Back</a>
		            </div>
		            <?php
						$user_id = $_SESSION["user_id"] ;
					?>
                <div class="profile_tabs">
					<div class=" table-responsive">
					<div class="table_S">
				<table class="speedy-table table " id="mytable">
					<thead>
						<tr>
							<th>Sent On</th>
							
							<th>EmailL</th>
							<th>Subject</th> 
							<th>Status</th> 
							<th>Action</th>
							<!-- <th>Date/Time</th> -->
						</tr>
					</thead>
					<tbody>
						<?php 

						

						$data = getTableData($conn, 'email_logs where user_id = '.$manager, '', '', 1);

						$i = 1;


						foreach($data as $row)

						{

							$user_id = $row['user_id'];

							$data_user = getTableData($conn, 'admin_users', "id = '".$user_id."'");

							$date = $row['data_inserted_at'];

							$email = $data_user['email'];

							$data_inserted_at = $row['data_inserted_at'];

							$email_subject = $row['email_subject'];

							$email_message = $row['email_message'];



						?>
						<tr>
							<td><?=$date?></td>
							<td><?=$email?></td>
							<td><?=$email_subject?></td> 
							<td>
								<?php if($row['status'] != ""){ ?>
									<label class="rounded p-1 bg-danger text-light"><?=$row['status']?></label>
								<?php } ?>
							</td>
							<td>
							
								<a title="View" href="<?=HOST_URL."adminpannel/email-view.php?manager=".base64_encode($row['id'])?>" class="btn btn-primary view-email-button"><svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path></svg></a>
									
								 

							</td>
						</tr>
					<?php

					$i++;

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
	<script>
		$(document).ready(function () {
	             $("#mytable").DataTable({
				        order: [[0, 'desc']],
				    });
	    });	
	</script>

</html>

