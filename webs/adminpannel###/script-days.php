<?php 

require_once('config.php');
require_once('meta_details.php');
require_once('inc/functions.php') ;




if(isset($_POST['subbtn'])){
	
	
	$days=$_POST['days'];
	
	
	$inst="UPDATE `generate_script_on` SET `days`='$days' , `updated_at`=now() WHERE id = 1";
	$inst_cone=mysqli_query($conn,$inst);
	if($inst_cone==true){

		 $_SESSION['success'] = "Save successfully!" ;
	}
	else{
		 $_SESSION['error'] = "Try again.." ;
	}
	
	// header("location: ".HOST_URL."adminpannel/add-email.php") ;
	// die();
	
}

 $script_days = getTableData( $conn , " generate_script_on " , " id = 1 " ) ;
 $generate_days = 0;
 $generate_days = $script_days['days'];
 if($generate_days<=0){
 	$generate_days = 4;
 }


?>
<?php require_once("inc/style-and-script.php") ; ?>
	<script src="//cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
</head>
<body class="custom-tabel">
	<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>
		<!-- Page content wrapper-->
		<div id="page-content-wrapper">
			<?php require_once("inc/topbar.php"); ?>
			<!-- Page content-->
			<div class="container-fluid content__up add_email">

				<h1 class="mt-4">Add Email Template</h1>
				
				<div class=back_btn_wrap ">
					<a href="email_template.php" type="button" class="btn btn-primary">Back</a>
				</div>
                <div class="form_h">
				<?php require_once("inc/alert-status.php") ; ?>
				
				<form method="post" class="mt-4">
					<div class="form-group">
						<label>Script Regenerate Days</label>
						<input type="text" name="days"  class="form-control" value="<?=$generate_days?>" required />
						<span> </span>
					</div>


					<div class="form_h_submit" >
						<button type="submit" name="subbtn"  class="btn btn-primary ">Update</button>
					</div>
				</form>
                </div>
			</div>
		</div>
	</div>
</body>
</html>
