<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;



?>

<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>Admin Dashboard</title>
	<!-- Favicon-->
	<link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
	<?php require_once('inc/style-and-script.php') ; ?>


<!-- <script>
	$(document).ready( function () {
$("a.paginate_button").attr("onclick", "table_data(this)");
	table_data(btn){
		var table_no=$(btn).attr("data-dt-idx");
		console.log(table_no);
	}
});

</script> -->
	<?php require_once("inc/style-and-script.php") ; ?>

<style>
.w_waitinglist tr th:last-child {
    width: max-content;
    text-align: center;
}
</style>

<body class="custom-tabel">
	<div class="d-flex" id="wrapper">
	<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>

		<!-- Page content wrapper-->
		<div id="page-content-wrapper">

			<?php require_once("inc/topbar.php"); ?>

			<!-- Page content-->
			<div class="container-fluid content__up tickets_a">


				<h1 class="mt-4">Wordpress Waitlist</h1>
				<?php 
				// require_once("inc/alert-status.php") ; ?>
				<!-- <a href="<?=HOST_URL?>adminpannel/ticket-form.php" class="btn btn-primary">Create Ticket</a> -->
				<?php

						$user_id = $_SESSION["user_id"] ;
					?>
                <div class="profile_tabs">
					<div class=" table-responsive">
					<div class="table_S">
				<table class="w_waitinglist"  id="w_waitinglist">
					<thead>
						<tr>
							<th style="width:35px;">#</th>
							<th>Platform</th>
                            <th>Email</th>
							<th>Website Url</th>
							<th>Date</th>
							
						</tr>
					</thead>
					<tbody>
						<?php				
                            $sno = 0;
						    $data = getTableData($conn, 'keep_me_posted', '', '', 1);
						    foreach($data as $row){ $sno++?>
                               <tr>
                                    <td><?=$sno;?></td>
                                   <td><?=$row['platform']?></td>
                                   <td><?=$row['email']?></td>
                                   <td><?=$row['website_url']?></td>
                                   <td><?=$row['created_at']?></td>
                               </tr> 
						
                            <?php } ?>
				

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
	        $('#w_waitinglist').DataTable();
	    });	
	</script>

</html>

