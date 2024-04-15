<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if ( empty($_SESSION['user_id']) || empty($_SESSION['role']) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
// print_r($_SESSION) ;
if ( isset($_POST["subbtn"]) ) {

	// print_r($_POST) ;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($email) || empty($pass) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	else {
		
		$row = getTableData( $conn , " smtp_login "  ) ;
		
		
           // $manager_id= $_SESSION['user_id'];
		$columns = " email, password";
		$values= "'$email' ,  '$pass'" ;
	
		if(count($row)>=1){
		
			$columnss = " email = '$email' , password = '$pass'" ;

		if ( updateTableData( $conn , " smtp_login " , $columnss ,  ) ) {
			$_SESSION['success'] = "Profile details are updated successfully!" ;
		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}

		}
		else{
			
			
		if ( InsertTableData( $conn , " smtp_login " , $columns , $values  ) ) {
			$_SESSION['success'] = "Profile details are Insert successfully!" ;
		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}
		
			
			
		}
		
		
	}
	
	


	header("location: ".HOST_URL."adminpannel/settings.php") ;
	die() ;
}


$row = getTableData( $conn , " smtp_login "  ) ;

?>


<?php require_once("inc/style-and-script.php") ; ?>


		<style>
			.disbale-tab {
			    pointer-events: none;
			    cursor: not-allowed;
			    opacity: 0.5;
			}
		</style>

<style>
.container {
/*	border:1px solid;*/
	padding: 40px;
}

@media (min-width: 420px) and (max-width: 659px) {
  .container {
    grid-template-columns: repeat(2, 160px);
  }
}

@media (min-width: 660px) and (max-width: 899px) {
  .container {
    grid-template-columns: repeat(3, 160px);
  }
}

@media (min-width: 900px) {
  .container {
    grid-template-columns: repeat(3, 160px);
  }
}

.container .box {
  width: 100%;
}


.container .box .chart {
	position: relative;
	width: 100%;
	height: 100%;
	text-align: center;
	font-size: 30px;
	height: 160px;
	color: black;
	padding: 50px;
}

.container .box canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  width: 100%;
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

				<!-- Page content-->
				<div class="container-fluid content__up settings_a">
					
					<h1 class="mt-4">SMTP Login</h1>

					<?php
						$data = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " ) ;
						
						// print_r($data) ;
					?>
					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>
					<form method="post" >
					    
						 
						 <div class="form-group">
						    <label>Email Id</label>
							<input type="email" name="email" id="email" placeholder="enter first Name" class="form-control" required value="<?=$row["email"]?>"/>
						 <span> </span>
						 </div>
						 
						  
						  <div class="form-group">
						    <label>Password</label>
							<input type="text" name="pass" id="pass" placeholder="enter last Name" class="form-control" required value="<?=$row["password"]?>"/>
						 
						 </div>
						 
						  <div class="form_h_submit" >
						 <button type="submit" name="subbtn"  class="btn btn-primary">Submit</button>
						 </div>
						 
						
						 </form>

                      </div>
					
					
				</div>
			</div>
		</div>
		
	</body>
</html>
