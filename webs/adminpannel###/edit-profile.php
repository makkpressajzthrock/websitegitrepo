<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

// print_r($_SESSION) ;

if ( isset($_POST["save-changes"]) ) {

	 print_r($_POST) ; 

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($fname) || empty($lname) || empty($phone) || empty($add1) || empty($city) || empty($state) || empty($zipcode) || empty($country) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
		//die("1");
	}
	else {
		
		//die("2" );

		$columns = " firstname = '$fname' , lastname = '$lname' , phone = '$phone' , address_line_1='$add1' , address_line_2='$add2', city='$city', state='$state', zipcode='$zipcode', country='$country'" ;

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
					<h1 class="mt-4">Edit Profile</h1>
					<?php //print_r($row) ;?>
					<form method="POST">
						
						<div class="form-group">
							<label for="fname">First Name</label>
							<input type="text" class="form-control" id="fname" name="fname" required value="<?=$row["firstname"]?>">
						</div>

						<div class="form-group">
							<label for="lname">Last Name</label>
							<input type="text" class="form-control" id="lname" name="lname" required value="<?=$row["lastname"]?>">
						</div>

					  	<div class="form-group">
							<label for="email">Email address</label>
							<span class="form-control" readonly><?=$row["email"]?></span>
					  	</div>

						<div class="form-group">
							<label for="phone">Phone</label>
							<input type="tel" class="form-control" id="phone" name="phone" required value="<?=$row["phone"]?>">
						</div>
						
						<div class="form-group">
							<label for="add1">Address Line 1</label>
							<input type="text" class="form-control" id="add1" name="add1" required value="<?=$row["address_line_1"]?>">
						</div>


						<div class="form-group">
							<label for="add2">Address Line 2</label>
							<input type="text" class="form-control" id="add2" name="add2" required value="<?=$row["address_line_2"]?>">
						</div>

							<div class="form-group">
							<label for="city">City</label>
							<input type="text" class="form-control" id="city" name="city" required value="<?=$row["city"]?>">
						</div>
						<div class="form-group">
							<label for="state">State</label>
							<input type="text" class="form-control" id="state" name="state" required value="<?=$row["state"]?>">
						</div>
						<div class="form-group">
							<label for="zipcode">ZipCode</label>
							<input type="text" class="form-control" id="zipcode" name="zipcode" required value="<?=$row["zipcode"]?>">
						</div>
						<div class="form-group">
							<label for="country">Country</label>
							<input type="text" class="form-control" id="country" name="country" required value="<?=$row["country"]?>">
						</div>
						
						
						<button type="submit" name="save-changes" class="btn btn-primary mt-2">Save Changes</button>
					</form>
				</div>
			</div>
		</div>

	</body>
</html>