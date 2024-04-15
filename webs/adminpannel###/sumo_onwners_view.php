<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;


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
				<div class="container-fluid content__up sumo_owners_view">
					<h1>Owner</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="back_btn_wrap">
					<a href="<?=HOST_URL."adminpannel/sumo_onwners.php"?>" class="btn btn-primary ">Back</a>
		           </div>
				   <div class="profile_tabs dashboard_sn owner">
					<table class="table speedy-table sumo_owners">
						<tbody>
							<?php

							$manager_data = getTableData( $conn , " admin_users " , " userstatus NOT LIKE 'admin' AND id = '".$_GET["manager"]."' " ) ;

							?>
							<tr> <th>Fullname</th><td><?=$manager_data["firstname"].' '.$manager_data["lastname"];?></td></tr>
							<tr> <th>Email</th><td><?=$manager_data["email"]?></td></tr>
							<tr> <th>Phone No</th><td><?=$manager_data["phone"]?></td></tr>
							<tr> <th>Registration Date</th><td><?=$manager_data["created_at"]?></td></tr>
							<tr> <th>Full Address</th><td><?=$manager_data["address_line_1"].' '.$manager_data["address_line_2"];?></td></tr>
							<tr> <th>City</th><td><?=$manager_data["city"]?></td></tr>
							<tr> <th>State</th><td><?=$manager_data["state"]?></td></tr>
							<tr> <th>ZipCode</th><td><?=$manager_data["zipcode"]?></td></tr>
							<tr> <th>Country</th><td><?=$manager_data["country"]?></td></tr>
						</tbody>
					</table>
					
					<h4> Plan Details- </h4>
			
			           <table class="table speedy-table  sumo_owners">
					  
					
							<?php

							
						$id_manager=$_GET["manager"];
	  
	                    $qry = "select * from boost_website where manager_id= '$id_manager'";
						 
						$connect_qry= mysqli_query($conn, $qry);
				
							?>
							
							
							<?php 
							     
							 while($web1=mysqli_fetch_array($connect_qry))
								
							 {
							
							 ?>
								 <tbody>
							<tr> <th>Website URL</th><td><?=$web1["website_url"];?></td></tr>
							<tr> <th>Platform</th><td><?=$web1["platform"]?></td></tr>
							<tr> <th>Desktop Speed Old</th><td><?=$web1["desktop_speed_old"]?></td></tr>
							<tr> <th>Desktop Speed New</th><td><?=$web1["desktop_speed_new"]?></td></tr>
							<tr> <th>Mobile Speed Old</th><td><?=$web1["mobile_speed_old"]?></td></tr>
							<tr> <th>Mobile Speed New</th><td><?=$web1["mobile_speed_new"]?></td></tr>
							<tr> <th>Script Installed</th><td><?=$web1["script_installed"]?></td></tr>
							<tr> <th>Plan Id</th><td><?=$web1["plan_id"]?></td></tr>
							<tr> <th>Plan Type</th><td><?=$web1["plan_type"]?></td></tr>
							<tr> <th>Subscription Id</th><td><?=$web1["subscription_id"]?></td></tr> 
							<tr> <th>Last Update</th><td><?=$web1["updated_at"]?></td></tr> 
						 </tbody>
					
							<?php 
							
							 }
							 
							 ?>
						
					     
					   
					   </table>
					</div>
				</div>
			</div>
		</div>

	</body>

</html>