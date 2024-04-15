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

<body class="custom-tabel">
	<div class="d-flex" id="wrapper">
	<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>

		<!-- Page content wrapper-->
		<div id="page-content-wrapper">

			<?php require_once("inc/topbar.php"); ?>

			<!-- Page content-->
			<div class="container-fluid content__up tickets_a">


				<h1 class="mt-4">URL Search Log</h1>
				<?php 
				// require_once("inc/alert-status.php") ; ?>
				<!-- <a href="<?=HOST_URL?>adminpannel/ticket-form.php" class="btn btn-primary">Create Ticket</a> -->
				<?php

						$user_id = $_SESSION["user_id"] ;
					?>
                <div class="profile_tabs">
					<div class=" table-responsive">
					<div class="table_S">
				<table class="speedy-table table url__log__table"  id="mytable">
					<thead>
						<tr>
							<th>Date</th>
							<th>Search URL</th>
							<th>Country</th>
							<th>City</th>
							<th>Latitude</th>
							<th>Longitude</th>
							<th>Timezone</th>
							<th>IP Address (ipv6)</th>
							<th>Source</th>
							<!-- <th>Date/Time</th> -->
						</tr>
					</thead>
					<tbody>
						<?php 

						

						$data = getTableData($conn, 'url_logs', '', '', 1);

						$i = 1;


						foreach($data as $row)

						{

							$source = $row['source'];

							$user_url = $row['user_url'];

							$ip = $row['ip'];

							$city = $row['city'];

							$country = $row['country'];

							$latitude = $row['latitude'];

							$longitude = $row['longitude'];

							$timezone = $row['timezone'];

							$date = $row['created_at'];

						?>
						<tr>
							<td><?=$date?></td>

							<td><?=$user_url?></td>
							<td><?=$country?></td>
							<td><?=$city?></td>
							<td><?=$latitude?></td>
							<td><?=$longitude?></td>
							<td><?=$timezone?></td>
							<td><?=$ip?></td>
						
							<td><?=$source?></td>
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
	        $('#mytable').DataTable(
				 {
            		"order": [[ 0, "desc" ]]
       			 } 

	        );
	    });	
	</script>

</html>

