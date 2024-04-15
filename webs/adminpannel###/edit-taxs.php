<?php 
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
?>

<?php
$id=base64_decode($_GET['edit']);
$selqr="select * from `add-tax` where id!='$id'";
$selcon=mysqli_query($conn,$selqr);
$get=mysqli_fetch_array($selcon);

$namecountry=$get['country_name'];
$nametax=$get['tax_name'];
$names= $_POST['name'];
	$taxname= $_POST['tax_name'];
if($namecountry==$names || $nametax==$taxname){
$_SESSION['error'] = "Data already exists!" ;
    
}
else{
if(isset($_POST['updates'])){
	
	$ids=$_POST['id'];
	
	$names= $_POST['name'];
	$taxname= $_POST['tax_name'];
	$rate= $_POST['rate'];
	
	
	$qryyy="update `add-tax` set country_name='$names', `tax_name`='$taxname',  tax_rate='$rate'where id='$ids'"; 
	
	
	
	$con_qry= mysqli_query($conn,$qryyy);
	 $_SESSION['success'] = "Tax Updated successfully!" ;
	
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
				<div class="container-fluid content__up edit_tax">
					
					
					
					<?php

					$id=base64_decode($_GET['edit']);

					$sele ="select * from `add-tax` where id='$id'";
					$result= mysqli_query($conn,$sele);

					$rows1= mysqli_fetch_assoc($result);

					//echo $id;
					?>

					<?php
				
				 $qry2="select * from country";
				 $cont_qry2=mysqli_query($conn,$qry2);
				 
				
				?>
					<h1> Edit Plan </h1>
					<div class="back_btn_wrap ">
						<a href="tax_rates.php" type="button" class="btn btn-primary">Back</a>
					</div>
					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>
					<form method="post">
					     
	 				
	         		<input type="hidden" value="<?php echo $rows1['id'];?>" name="id">
         			<div class="form-group">
		 				<label> Country Name</label>
		 				 <select   name="name" value="" class="form-control">
						 <?php
						 while($run_qry2=mysqli_fetch_array($cont_qry2))
						 {
						 	 

						 ?>
						 <option  <?php if($rows1['country_name']==$run_qry2['countryname']){

						 		echo "selected"; 
						 	} ?>  value="<?php echo $run_qry2['countryname'];?>"><?php echo $run_qry2['countryname'];?></option>
						 <?php
						 }
						 ?>
						 </select>
		 				<!-- <input value="<?php echo $rows1['country_name'];?>" name="name"> -->
		 			</div>
   
					<div class="form-group">
	 					<label>Tax Name </label>
	 					<input class="form-control" value="<?php echo $rows1['tax_name']; ?>" name="tax_name">
	 				</div>
	   				<div class="form-group">
						<label>Tax Rate</label>
						<input class="form-control" value="<?php echo $rows1['tax_rate']; ?>" name="rate">
					</div>
		 
					
					 	<div class="form_h_submit">
						    <button type="submit"  name="updates" class="btn btn-primary">Update</button>
						</div>
					</form>	
						</div>		
					
					
					
					
					</div>
			</div>
		</div>

	</body>
</html>