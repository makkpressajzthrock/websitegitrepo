<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

print_r($_SESSION) ;


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

print_r(count($row)) ;


$result = mysqli_query($conn, "select * from admin_users where id='$session_id'")or die('Error In Session');
$row = mysqli_fetch_assoc($result);

if($row['userstatus'] == 'inactive'){
header("location: index.php");
}

// $result1 = pg_query($conn, "select * from stately_quiz_data where email != '' ORDER BY id DESC limit 100");
// $row1 = pg_fetch_array($result1);
$result1 = mysqli_query($conn, "select * from stately_quiz_data where email != '' ORDER BY id DESC limit 10000");


// var_dump($row1);

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
		<!-- Core theme CSS (includes Bootstrap)-->
		<link href="css/styles.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
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
			<!-- Sidebar-->
			<div class="border-end bg-white" id="sidebar-wrapper">
				<div class="sidebar-heading border-bottom bg-light">Admin</div>
				<div class="list-group list-group-flush">
					<a class="list-group-item list-group-item-action list-group-item-light p-3 active" href="#">Stately Quiz Customer Data</a>
					<?php if($row['status'] == 1) {?>
					<a class="list-group-item list-group-item-action list-group-item-light p-3" href="additional_quiz_data.php">Additional Style Preference Data</a>
					<a class="list-group-item list-group-item-action list-group-item-light p-3" href="alluser.php">All Staff</a>
					<?php }?>
					<a class="list-group-item list-group-item-action list-group-item-light p-3" href="logout.php">Log out</a>
					<!-- <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Overview</a>
						<a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Events</a>
						<a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Profile</a>
						<a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Status</a> -->
				</div>
			</div>
			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				<!-- Top navigation-->
				<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
					<div class="container-fluid">
						<button class="btn btn-primary" id="sidebarToggle">Toggle Menu</button>
						<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
						<div class="collapse navbar-collapse" id="navbarSupportedContent">
							<ul class="navbar-nav ms-auto mt-2 mt-lg-0">
								<li class="nav-item active">Welcome: <?php echo $row['email']; ?></li>
								<!-- <li class="nav-item active"><a class="nav-link" href="#!">Home</a></li> -->
								<!-- <li class="nav-item"><a class="nav-link" href="#!">Link</a></li> -->
								<!-- <li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
									<div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="#!">Action</a>
									<a class="dropdown-item" href="#!">Another action</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="#!">Something else here</a>
									</div>
									</li> -->
							</ul>
						</div>
					</div>
				</nav>
				<!-- Page content-->
				<div class="container-fluid">
					<?php require_once("inc/alert-status.php") ; ?>
					<h1 class="mt-4">Stately Quiz Customer Data</h1>
					<form method='post' action='download.php'>
						<input class="btn btn-primary " type='submit' value='Export' id="getcsv" name='Export'>
						<table id="example" class="display" style="width:100%">
							<thead>
								<tr>
									<th>Email</th>
									<th>How do you dress for work?</th>
									<th>How about on the weekend?</th>
									<th>What size tops do you wear?</th>
									<th>How do you like your shirts to fit?</th>
									<th>What is your pants waist size?</th>
									<th>What is your pants inseam measurement?</th>
									<th>How do you like your pants to fit?</th>
									<th>What is your shoe size?</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$user_arr = array();
									while($array= mysqli_fetch_assoc($result1))
									{
										
										echo '<tr>
										<td>'. $array['email'].'</td>
										<td>'. $array['que_1'].'</td>
										<td>'. $array['que_2'].'</td>
										<td>'. $array['que_3'].'</td>
										<td>'. $array['que_4'].'</td>
										<td>'. $array['que_5'].'</td>
										<td>'. $array['que_6'].'</td>
										<td>'. $array['que_7'].'</td>
										<td>'. $array['que_8'].'</td>
										
									
									
									
										</tr>';
										$id = $array['id'];
										$email = $array['email'];
										$que_1 = $array['que_1'];
										$que_2 = $array['que_2'];
										$que_3 = $array['que_3'];
										$que_4 = $array['que_4'];
										$que_5 = $array['que_5'];
										$que_6 = $array['que_6'];
										$que_7 = $array['que_7'];
										$que_8 = $array['que_8'];
										
									
									
										$user_arr[] = array($id,$email,$que_1,$que_2,$que_3,$que_4,$que_5,$que_6,$que_7,$que_8);
									}
									
									?>
								<!-- New php code  -->
							</tbody>
						</table>
						<?php 
							$serialize_user_arr = serialize($user_arr);
							?>
						<textarea name='export_data' style='display: none;'><?php echo $serialize_user_arr; ?></textarea>
					</form>
				</div>
			</div>
		</div>
		<!-- Bootstrap core JS-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Core theme JS-->
		<script src="js/scripts.js"></script>
	</body>
</html>
<script type="text/javascript">
	$(document).ready(function() {
	   $('#example').DataTable( {
	       "scrollX": true,
		
	       "ordering": false
	   } );
	} );
</script>