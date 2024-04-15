<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;




?>


<?php

if(isset($_POST['updates'])){
	
	
	
	$names= $_POST['name'];
	$inter= $_POST['intervals'];
	// $prices= $_POST['price'];
	$trials= $_POST['trial'];
	$planss= $_POST['plan'];
	$views= $_POST['view'];
	$state= $_POST['stats'];
	$line1 = $_POST['line1'];
	$line2 =$_POST['line2'];
	$line3 = $_POST['line3'];
	// $line4 = isset($_POST['line4'])?$_POST['line4']:'';
	
	$active='1';

  $inst= "INSERT INTO `plans`( `name`, `plan_frequency`, `price`, `interval`, `s_type`, `s_price`, `s_trial_duration`, `interval_plan`, `page_view`, `status`) VALUES ('$names','$inter','$trials','$planss','$views','$state','$line1','$line2','$line3','$active')";

 $result= mysqli_query($conn,$inst);

 if($result){

 $_SESSION['success'] = "Plan Add  successfully!";

 }

 else{

 	 $_SESSION['error'] = "Try again..";
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
				<div class="container-fluid content__up plan_edit_s">
					
					
					
				
					<h1> Add Plan </h1>
					<div class="back_btn_wrap ">
						<a href="plans.php" type="button" class="btn btn-primary">Back</a>
					</div>
					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>		
					<form method="post">
					
     
	 				
	         		
         			<div class="form-group">
		 				<label>Plan Name</label>
		 				<input class="form-control" value="" name="name">
                    </div>
   
					<div class="form-group">
	 					<label>Plan-Frequency </label>
	 					<input class="form-control" value="" name="intervals">
	 				</div>
	   				<!-- <tr>
						<label>Price</label>
						<input  value="" name="price">
					</tr> -->
		 
					<div class="form-group">
					 <label>Price</label>
					 <input class="form-control" value="" name="trial">
					 </div>
					 
					 <div class="form-group">
					 <label>Interval</label>
					 <input class="form-control" value="" name="plan">
					 </div>
					 
					 <div class="form-group">
					 <label>Plan type</label>
					 <input class="form-control" value="" name="view">
					 </div>
					 
					 <div class="form-group">
					 <label>Subscription Price</label>
					 <input class="form-control" value="" name="stats">
					 </div>

					 <div class="form-group">
					<label>Trial-duration</label>
					<input class="form-control" value="" name="line1" >
					</div>

					 <div class="form-group">
					<label>Interval Plan</label>
					<input class="form-control" value="" name="line2">
					</div>

					

					<div class="form-group">
					<label>Page View</label>
					<input class="form-control" value="" name="line3">
					</div>

				
					
					<div class="form_h_submit">
						    <button type="submit"  name="updates" class="btn btn-primary">Add Plan</button>
						</div>
						</form>
                    </div>			
				</div>
			</div>
	</body>
	<!-- <script>
		$('.close').click(function(){
			$('.alert').hide();
		});
	</script> -->
</html>

