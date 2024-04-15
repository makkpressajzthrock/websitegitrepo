<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php');




?>


<?php

if(isset($_POST['updates'])){
	
	$ids=$_POST['id'];
	
	$names= $_POST['name'];
	$inter= $_POST['intervals'];
	// $prices= $_POST['price'];
	$trials= $_POST['trial'];
	$planss= $_POST['plan'];
	$views= $_POST['view'];
	$state= $_POST['stats'];
	$line1 = isset($_POST['line1'])?$_POST['line1']:'';
	$line2 = isset($_POST['line2'])?$_POST['line2']:'';
	$line3 = isset($_POST['line3'])?$_POST['line3']:'';
	$line4 = isset($_POST['line4'])?$_POST['line4']:'';
	$line5 = isset($_POST['line5'])?$_POST['line5']:'';
	$line6 = isset($_POST['line6'])?$_POST['line6']:'';
	$line7 = isset($_POST['line7'])?$_POST['line7']:'';
	$line8 = isset($_POST['line8'])?$_POST['line8']:'';
	$line9 = isset($_POST['line9'])?$_POST['line9']:'';
	$line10 = isset($_POST['line10'])?$_POST['line10']:'';
	$price = isset($_POST['price'])?$_POST['price']:'';
	$main_p = isset($_POST['main_p'])?$_POST['main_p']:'';



	 $qryyy="update `plans` set `name`='$names', `interval`='$inter', main_p='$main_p',  s_trial_duration='$trials',interval_plan='$planss' , page_view='$views',status='$state', s_price ='$price', price = '$price' where id='$ids'"; 

	 $con_qry= mysqli_query($conn,$qryyy);

	$already_exist = "SELECT * FROM plans_functionality WHERE plan_id = '$ids'";
	$exist_result = mysqli_query($conn,$already_exist);
	$row = mysqli_fetch_assoc($exist_result);
	// $lin=$row['line1'];
	// $idp=$row['plan_id'];

	// print_r($row);
	// die();
	if ($con_qry) 
	{
		$_SESSION['success'] = "Data Updated!";
	}
 
		if($row > 0){

		 $update_plan_func= "UPDATE `plans_functionality` SET `line1`='$line1',`line2`='$line2',`line3`='$line3',`line4`='$line4',`line5`='$line5',`line6`='$line6',`line7`='$line7',`line8`='$line8',`line9`='$line9',`line10`='$line10' WHERE `plan_id`='$ids' ";
		 $update_qry_plan= mysqli_query($conn,$update_plan_func);
		 // print_r($update_qry_plan);
		 // die();
		 $_SESSION['success'] = "Data Updated!";
}
 else{
	
		 $insert = "INSERT INTO plans_functionality(`line1`,`line2`,`line3`,`line4`,`line5`,`line6`,`line7`,`line8`,`line9`,`line10`,`plan_id`) VALUES('$line1','$line2','$line3','$line4','$line5','$line6','$line7','$line8','$line9','$line10','$ids')";

		$insert_result = mysqli_query($conn,$insert);
		$_SESSION['success'] = "Updated";

}
		// if ($insert_result) 
		// {
		// 	$_SESSION['success'] = "Updated";
		// }
		// else
		// {
		// 	$_SESSION['error'] = "Something went wrong.";
		// }
	
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
				<div class="container-fluid content__up plan_edit_s">
					
					
					
					
					
					<?php

					$id=base64_decode($_GET['edit']);

					$sele ="select * from plans where id='$id'";
					$result= mysqli_query($conn,$sele);

					$rows1= mysqli_fetch_assoc($result);


	$already_exist2 = "SELECT * FROM plans_functionality WHERE plan_id = '$id'";
	$exist_result2 = mysqli_query($conn,$already_exist2);
	$row2 = mysqli_fetch_assoc($exist_result2);
	$lin=$row2['line1'];
	//$idp=$row2['plan_id'];




					?>
					<h1> Edit Plan </h1>
					<div class="back_btn_wrap ">
						<a href="plans.php" type="button" class="btn btn-primary">Back</a>
					</div>
					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>		
					<form method="post">
					
     
	 				
	         		<input type="hidden" value="<?php echo $rows1['id'];?>" name="id">
         			<div class="form-group">
		 				<label>Name</label>
		 				<input class="form-control" value="<?php echo $rows1['name'];?>" name="name">
                    </div>
   
					<div class="form-group">
	 					<label>Interval </label>
	 					<input class="form-control" value="<?php echo $rows1['interval']; ?>" name="intervals">
	 				</div>
	   				<!-- <tr>
						<label>Price</label>
						<input  value="<?php echo $rows1['s_price']; ?>" name="price">
					</tr> -->
		 
					<div class="form-group">
					 <label>Trial Duration</label>
					 <input class="form-control" value="<?php echo $rows1['s_trial_duration']; ?>" name="trial">
					 </div>
					 
					 <div class="form-group">
					 <label>Interval Plan</label>
					 <input class="form-control" value="<?php echo $rows1['interval_plan']; ?>" name="plan">
					 </div>
					 
					 <div class="form-group">
					 <label>PageView</label>
					 <input class="form-control" value="<?php echo $rows1['page_view']; ?>" name="view">
					 </div>
					 
					 <div class="form-group">
					 <label>Status</label>
					 <input class="form-control" value="<?php echo $rows1['status']; ?>" name="stats">
					 </div>

					 <div class="form-group">
					<label>Line 1</label>
					<input class="form-control" value="<?php echo $row2['line2']; ?>" name="line2">
					</div>

					<div class="form-group">
					<label>Line 2</label>
					<input class="form-control" value="<?php if($rows1['id'] == 1){ echo "7 days free trial";} else { echo "$lin"; }?>" name="line1" >
					</div>

					<div class="form-group">
					<label>Line 3</label>
					<input class="form-control" value="<?php  echo $row2['line3'];?>" name="line3">
					</div>

					<div class="form-group">
					<label>Line 4</label>
					<input class="form-control" value="<?php echo $row2['line4']; ?>" name="line4">
					</div>	
		
					<div class="form-group">
					<label>Line 5</label>
					<input class="form-control" value="<?php echo $row2['line5'];?>" name="line5">
					</div>

					<div class="form-group">
					<label>Line 6</label>
					<input class="form-control" value="<?php echo $row2['line6']; ?>" name="line6">
					</div>	
			
					<div class="form-group">
					<label>Line 7</label>
					<input class="form-control" value="<?php echo $row2['line7']; ?>" name="line7">
					</div>

					<div class="form-group">
					<label>Line 8</label>
					<input class="form-control" value="<?php echo $row2['line8']; ?>" name="line8">
					</div>

					<div class="form-group">
					<label>Line 9</label>
					<input class="form-control" value="<?php echo $row2['line9'];?>" name="line9">
					</div>

					<div class="form-group">
					<label>User Limit</label>
					<input class="form-control" value="<?php echo $row2['line10'];?>" name="line10">
                    </div>

                    <div class="form-group">
					<label>Price</label>
					<input class="form-control" value="<?=$rows1['s_price']?>" name="price">
                    </div>
                  <div class="form-group">
                  	<label>Main Price</label>
					<input class="form-control" value="<?=$rows1['main_p']?>" name="main_p">
                    </div>

					<div class="form_h_submit">
						    <button type="submit"  name="updates" class="btn btn-primary">Update</button>
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

