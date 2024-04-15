<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$ticket_id = $_GET['ticket_id'];

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['dis_submit_btn'])){
	// print_r($_POST);
	$site= $_POST['site'];
	$discount= $_POST['discount'];
		if($site==null || $discount==null){
		$_SESSION['error'] = "all fields are mandatory!";

		}else{

	

	 
	if ( insertTableData( $conn , "discount" , "sites,discount" , "'$site','$discount'" )) 
	{
		$_SESSION['success'] = "Discount Insert Successfully!";
	}else{
		$_SESSION['error'] = "Discount Insert error!";

	}
	}	
	
}


?>
<?php require_once("inc/style-and-script.php") ; ?>
		<style type="text/css">
			/* Mark input boxes that gets an error on validation: */
.invalid {
  background-color: #ffdddd;
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
				<div class="container-fluid content__up add_discount">
					
					<h1>Add Discount</h1>

                    <div class="back_btn_wrap">
					 <a href="<?=HOST_URL?>adminpannel/discount.php" class="btn btn-primary">Back</a>
                   </div>
			       <div class="form_h"	>	
				   <?php require_once("inc/alert-status.php") ; ?>
             		<form method="POST">

					
					<div class="form-group">
							<label for="text_site">Site</label>
							<input class="form-control"  id="text_site" name="site">
                    </div>	
					<div class="form-group">			
                       <label for="text_discount">Discount</label>
                      <input class="form-control" id="text_discount" placeholder="%" name="discount">
                    </div>
						
						
					
					

					
					<div class="form_h_submit">
						<button type="submit" class="btn btn-primary" id="dis_submit_btn" name="dis_submit_btn">Submit</button>
					</div>
					
				</form>
				</div>
</div>
				</div>
			</div>
		</div>


		
	</body>
</html>

