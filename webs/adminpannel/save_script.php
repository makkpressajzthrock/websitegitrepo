<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( isset($_POST['submit_btn']) ) {
extract($_POST) ;
	 
		// check already saved data
		$check = getTableData( $conn , " speed_script "  ) ;
		if ( count($check) > 0 ) {
			// update
			$id = $check["id"] ;
			$columns = " script = '".mysqli_real_escape_string($conn, $script)."' " ;

			if ( updateTableData( $conn , " speed_script " , $columns , " 1 " ) ) {
				$_SESSION['success'] = "Saved." ;
			}
			else {
				$_SESSION['error'] = "Operation failed!" ;
				$_SESSION['error'] = "Error: " . $conn->error;
			}
		}
		else { 
			// insert
			$columns = "script " ;
			$values = " '$script'" ;

			if ( insertTableData( $conn , " speed_script " , $columns , $values ) ) {
				$_SESSION['success'] = "Saved." ;
			}
			else {
				$_SESSION['error'] = "Operation failed!" ;
				$_SESSION['error'] = "Error: " . $conn->error;
			}
		}



}

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>
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
					<h1 class="mt-4">Booster Script</h1>

					<?php
						$data = getTableData( $conn , " speed_script " , " id = '1' " ) ;
						// echo '<pre>';
						// print_r($data) ;
					?>

					<form method="POST">
						<textarea  class="form-control" rows="24" name="script"><?=$data["script"]?></textarea>
						<button  class="btn btn-success"  name="submit_btn" >Save Script</button>
					</form>

					

				</div>
			</div>
		</div>
		
	</body>
</html>
