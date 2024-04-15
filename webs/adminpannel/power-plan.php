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
			
	 $qry="select * from power_plan";
				 $cont_qry=mysqli_query($conn,$qry);

				$run_qry=mysqli_fetch_array($cont_qry);

				// print_r($run_qry);

				$planname=$run_qry['name'];
				$page_view= $run_qry['page_view'];


if(isset($_POST['submitbtn'])){

// 	print_r($_POST['']);
// die();


$name= $_POST['name'];
$p_view= $_POST['pageview'];
$id_value='3';

if($run_qry >= 1)
{

 $updates ="UPDATE `power_plan` SET `name`='$name',`page_view`='$p_view' WHERE  id='$id_value'";

 $update_done = mysqli_query($conn,$updates);

if($update_done==true){

		$_SESSION['success'] = "Update Successfully!" ;
}
else{

			$_SESSION['error'] = "Try Again.." ;
}

}
else{
$inst ="INSERT INTO `power_plan`( `name`, `page_view`) VALUES ('$name','$p_view')";
   
   $inst2 = mysqli_query($conn,$inst);

if($inst2==true){

		$_SESSION['success'] = "Insert Successfully!" ;
}
else{

			$_SESSION['error'] = "Try Again.." ;
}
}




}


?>
<?php require_once("inc/style-and-script.php") ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up web_owners">
					<h1 class="mt-4">Power Plan</h1>
					



<?php
		
				 $qry="select * from power_plan";
				 $cont_qry=mysqli_query($conn,$qry);

				$run_qry=mysqli_fetch_array($cont_qry);

				// print_r($run_qry);

				$planname=$run_qry['name'];
				$page_view= $run_qry['page_view'];
				 
				?>
<div class="form_h">
<?php require_once("inc/alert-status.php") ; ?>
<form method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Plan Name</label>
    <input type="text" value="<?php echo $planname; ?>" class="form-control" name="name" aria-describedby="emailHelp">
    
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Page View</label>
    <input type="text" class="form-control" value="<?php echo $page_view; ?>" id="exampleInputPassword1" name="pageview" placeholder="">
  </div>

  <div class="form_h_submit">
  <button type="submit" name="submitbtn" class="btn btn-primary">Submit</button>
</div>
</form>
</div>

				</div>
		
			</div>
		</div>

	</body>
	
</html>