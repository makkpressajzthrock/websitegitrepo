<?php 


include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

	$plan_id=$_GET['edit'];


?>


<?php

if(isset($_POST['save'])){
	// print_r($_POST);
	$names= $_POST['name'];
	$inter= $_POST['intervals'];
	$prices= $_POST['price'];
	$trials= $_POST['trial'];
	$planss= $_POST['plan'];
	$status= $_POST['status'];
	if($status=="inactive"){
		$status=0;
	}elseif($status=="active"){
		$status=1;

	}

	// echo "status".$status;
	if($names==null || $inter==null || $prices==null || $trials==null || $planss==null || $status==null){
		$_SESSION['error'] = "all fields are mandatory!";

	}else{	

			 $sql="INSERT INTO `plans_warranty`(`name`,`interval`,`s_type`,`s_price`,`s_trial_duration`,`interval_plan`,`status`) VALUES ('$names','$inter','$inter','$prices','$trials','$planss','$status')"; 
				// echo "sql ".$sql;

			 
			if ($conn->query($sql)) 
			{
				$_SESSION['success'] = "Plan Insert Successfully!";
			}else{
				$_SESSION['error'] = "Plan Insert error!";

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
				<div class="container-fluid content__up warranty_plan_add ">
					
					
					
					
					
					<?php



					$sele ="select * from plans_warranty where id='$plan_id'";
					$result= mysqli_query($conn,$sele);

					$rows1= mysqli_fetch_assoc($result);

					// print_r($rows1);


					?>
					<h1>Insert Warranty Plans  </h1>

					<div class="back_btn_wrap">
						<a href="warranty-plans.php" type="button" class="btn btn-primary">Back</a>
					</div>
					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>
					<form method="post">

         			<div class="form-group">
		 				<label for="name">Name</label>
		 				<input  name="name" class="form-control"></td>
                    </div>
   
					<div class="form-group">
					<label for="interval">Interval </label>
	 				<input  name="intervals" class="form-control">
						 </div>
					 <div class="form-group">
						<label for="price">Price</label>
						<input  name="price" class="form-control">
						</div>
		 
					<div class="form-group">
					 <label>Trial Duration</label>
					 <input  name="trial" class="form-control">
					 </div>
					 
					 <div class="form-group">
					 <label>Interval Plan</label>
					 <input  name="plan" class="form-control">
                     </div>
					 
					  
					 
					 <div class="form-group">
					 <label>Status</label>
					 <select name="status" class="form-control">
						    <option value="" disabled selected>Select your option</option>
						    <option value="active">Active</option>
						    <option value="inactive">Inactive</option>
					</select>	
                     </div>
					    <div class="form_h_submit">				
						    <button type="submit"  name="save" class="btn btn-primary text-center mt-1" >Save</button>
						</div>
					</form>	
</div>		
				</div>
			</div>
	</body>
	<script>
		$('.close').click(function(){
			$('.alert').hide();
		});
	</script>
</html>

