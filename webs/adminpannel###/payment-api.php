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
if(isset($_POST['submitbtn'])){

// 	print_r($_POST['']);
// die();


$name= $_POST['name'];
$p_key= $_POST['publickey'];
$s_key=  $_POST['scrtkey'];
$id_value='1';

 $updates ="UPDATE `payment_gateway` SET `name`='$name',`public_key`='$p_key',`secret_key`='$s_key' WHERE  id='$id_value'";


 $update_done = mysqli_query($conn,$updates);

if($update_done==true){

		$_SESSION['success'] = "Update Successfully!" ;
}
else{

			$_SESSION['error'] = "Try Again.." ;
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
					<h1 class="mt-4">Payment API Details</h1>
					


<?php
				
				 $qry="select * from payment_gateway";
				 $cont_qry=mysqli_query($conn,$qry);

				$run_qry=mysqli_fetch_array($cont_qry);

				// print_r($run_qry);

				$publickey_get=$run_qry['public_key'];
				$secretkey_get= $run_qry['secret_key'];

			
				 
				?>
<div class="form_h">
<?php require_once("inc/alert-status.php") ; ?>
<form method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" value="<?php echo $run_qry['name']; ?>" class="form-control" name="name" aria-describedby="emailHelp">
    
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Public Key</label>
    <input type="text" class="form-control" value="<?php echo $publickey_get; ?>" id="exampleInputPassword1" name="publickey" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Secret Key</label>
    <input type="text" class="form-control" value="<?php echo $secretkey_get; ?>" id="exampleInputPassword1" name="scrtkey" placeholder="Password">
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