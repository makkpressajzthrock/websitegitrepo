<?php 


include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

	$plan_id=$_GET['edit'];


?>


<?php

if(isset($_POST['updates'])){
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
	if($names==null || $inter==null || $prices==null || $trials==null || $planss==null ){
		$_SESSION['error'] = "all fields are mandatory!";

	}else{
			 $qryyy="update `plans_warranty` set `name`='$names', `interval`='$inter', `s_type`='$inter',  s_trial_duration='$trials',interval_plan='$planss' , s_price='$prices',status='$status' where id='$plan_id'"; 
				// echo "qryyy".$qryyy;
			if (mysqli_query($conn,$qryyy)) 
			{
				$_SESSION['success'] = "Plan Updated Successfully!";
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
				<div class="container-fluid content__up warranty_plan_edit ">
					
					
					
					
					
					<?php



					$sele ="select * from plans_warranty where id='$plan_id'";
					$result= mysqli_query($conn,$sele);

					$rows1= mysqli_fetch_assoc($result);

					// print_r($rows1);


					?>
					<h1> Edit Warranty Plans  </h1>
					<div class="back_btn_wrap">
						<a href="warranty-plans.php" type="button" class="btn btn-primary">Back</a>
					</div>
					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>
					<form method="post"> 					
	         	
					<div class="form-group">
		 				<label>Name</label>
		 				<input class="form-control" value="<?php echo $rows1['name'];?>" name="name">
                    </div>
   
					 <div class="form-group">
	 					<label>Interval </label>
	 					<input class="form-control" value="<?php echo $rows1['interval']; ?>" name="intervals">
						 </div>
					 <div class="form-group">
						<label>Price</label>
						<input class="form-control"  value="<?php echo $rows1['s_price']; ?>" name="price">
						</div>
		 
					<div class="form-group">
					 <tlabelh>Trial Duration</label>
					 <input class="form-control" value="<?php echo $rows1['s_trial_duration']; ?>" name="trial">
					 </div>
					 
					 <div class="form-group">
					 <label>Interval Plan</label>
					 <input class="form-control" value="<?php echo $rows1['interval_plan']; ?>" name="plan">
					 </div>
					 
					  
					 
					 <div class="form-group">
					 <label>Status</label>
					 <select name="status" class="form-control">
						    <option value="" disabled selected>Select your option</option>
						    <option value="active" <?php if($rows1['status']==1){ echo "selected";} ?> >Active</option>
						    <option value="inactive" <?php if($rows1['status']==0){ echo "selected";} ?>>Inactive</option>
						</select>
					</div>

					 	<div class="form_h_submit">
						    <button type="submit"  name="updates" class="btn btn-primary text-center mt-1">Update</button>
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

