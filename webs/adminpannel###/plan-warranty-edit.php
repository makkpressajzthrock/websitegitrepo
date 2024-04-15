<?php 


include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

	$plan_id=$_GET['edit'];


?>


<?php

if(isset($_POST['updates'])){
	
	$ids=$_POST['id'];
	
	$names= $_POST['name'];
	$inter= $_POST['intervals'];
	$prices= $_POST['price'];
	$trials= $_POST['trial'];
	$planss= $_POST['plan'];
	$status= $_POST['status'];
	
	 $qryyy="update `plans_warranty` set `name`='$names', `interval`='$inter',  s_trial_duration='$trials',interval_plan='$planss' , s_price='$prices',status='$state' where id='$plan_id'"; 

	if (mysqli_query($conn,$qryyy)) 
	{
		$_SESSION['success'] = "Data Updated!";
	}	
	
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
					
					
					
					
					<?php



					$sele ="select * from plans_warranty where id='$plan_id'";
					$result= mysqli_query($conn,$sele);

					$rows1= mysqli_fetch_assoc($result);

					print_r($rows1);


					?>
					<div class="back">
						<a href="warranty-plans.php" type="button" class="btn btn-primary">Back</a>
					</div>
					<form method="post">
					
					 <table class="table">
     
	 					<div class="mb-3"> <h3> Edit Warranty Plans  </h3></div>
	         		<input type="hidden" value="<?php echo $rows1['id'];?>" name="id">
         			<tr>
		 				<th>Name</th>
		 				<td><input value="<?php echo $rows1['name'];?>" name="name"></td>
		 			</tr>
   
					<tr>
	 					<th>Interval </th>
	 					<td><input value="<?php echo $rows1['interval']; ?>" name="intervals"></td>
	 				</tr>
	   				<tr>
						<th>Price</th>
						<td><input  value="<?php echo $rows1['s_price']; ?>" name="price"></td>
					</tr>
		 
					 <tr>
					 <th>Trial Duration</th>
					 <td><input value="<?php echo $rows1['s_trial_duration']; ?>" name="trial"></td>
					 </tr>
					 
					 <tr>
					 <th>Interval Plan</th>
					 <td><input value="<?php echo $rows1['interval_plan']; ?>" name="plan"></td>
					 </tr>
					 
					  
					 
					 <tr>
					 <th>Status</th>
					 <td><input value="<?php echo $rows1['status']; ?>" name="status">
					 	<small>(active = 1 and inactive = 0 )</small>
					 </td>
					 </tr>

					

		 			<tr>
						<th colspan="2">
					 	<div class="form-group" align="center">
						    <button type="submit"  name="updates" class="btn mr-5 btn-success btn-lg">Update</button>
						</div>
						</th>
					</tr>
		   				</table>
					</form>			
				</div>
			</div>
	</body>
	<script>
		$('.close').click(function(){
			$('.alert').hide();
		});
	</script>
</html>

