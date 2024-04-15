<?php 
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include('config.php');
include('session.php');
require_once('inc/functions.php') ;
require_once('smtp/PHPMailerAutoload.php');
require 'smtp-send-grid/vendor/autoload.php';
// ini_set('display_errors', 1); 
 // ini_set('display_startup_errors', 1); error_reporting(E_ALL);
 $manager_ids=$_SESSION['user_id'];
		  //print_r($manager_ids);
		 // die();
 
 
 if(isset($_POST['subbtnn'])){
	 
		
		                  
						 $fname= $_POST['fname'];
						 $lname= $_POST['lname'];
						 $phone= $_POST['number'];
						 $emailid= $_POST['emails'];

						 $pass= $_POST['password'];
						 
						 $hashed_password = md5($pass);
						 
						 $teams="team";
						 if ($fname!="" && $lname!="" && $phone!="" && $emailid!="") {
						 	
						 
						
				    if (strlen($phone) >= 10 && strlen($phone) < 20) {
						 	// code...
						 $check_num="SELECT * FROM `admin_users` WHERE `email` LIKE '$emailid'";
						 $check__result=$conn->query($check_num);

						 $check_num2="SELECT * FROM `admin_users` WHERE `phone` LIKE '$phone'";
						 $check__result2=$conn->query($check_num2);

						 if($check__result->num_rows <= 0){

						 if($check__result2->num_rows <= 0){
					
						 $insertt1 ="INSERT INTO `admin_users` ( `firstname`, `lastname`, `email`, `phone`, `password`, `userstatus`,`parent_id`) VALUES ('$fname','$lname','$emailid','$phone','$hashed_password','$teams','$manager_ids')";
						 
						
						  $done1=mysqli_query($conn,$insertt1) or die(mysqli_error($conn));
						  
						 
						
$last_id = mysqli_insert_id($conn);
							
			$website = $_POST['website'];
 										
      for ($i = 0; $i < count($website); $i++) {
																		

					$dashboard =$website[$i]["dashboard"];
					// $exhelp = $website[$i]['exhelp'];
					$speed_tracking = $website[$i]['speed_tracking'];
					$speed_warranty = $website[$i]['speed_warranty'];
					$pgsped =$website[$i]["pgsped"];
					$scinst = $website[$i]["scinst"];
					// $nohelp =$website[$i]["nohelp"];
					$websites=$website[$i]['id'];

 
						$insertt2 = "INSERT INTO `team_access`(`team_id`, `website_id`, `dashboard`,`speed_tracking`,`speed_warranty`, `page_speed`, `script_install`) VALUES ('$last_id','$websites','$dashboard','$speed_tracking','$speed_warranty','$pgsped','$scinst')";
								 
	
						$done2 = mysqli_query($conn, $insertt2);


				}
					 
	
						
						  $billing="1";
						  $plans="1";
						  $setting="1";
						 
						 $insertt3 ="INSERT INTO `teams`(`team_id`, `billing`, `plans`, `setting`) VALUES ('$last_id','$billing','$plans','$setting')";
						 $done3=mysqli_query($conn,$insertt3);
					  
					   
					  
					  
					   if($done1|| $done2 || $done3==true){

					   	$body = '<p>You have been invited to Website Speedy<br><a href="https://ecommerceseotools.com/ecommercespeedy"><button>Click here</button></p>';


						$emailss = new \SendGrid\Mail\Mail(); 
						$emailss->setFrom("info@ecommercespeedy.com","Website Speddy");
						$emailss->setSubject("Invitation to Website Speedy");
						$emailss->addTo($emailid, "Website Speddy");
						$emailss->addContent("text/html",$body);

						$sendgrid = new \SendGrid($smtpDetail["password"]);
						$sendgrid->send($emailss);
 
						   $_SESSION['success'] = "Data Save successfully!" ;
						   	header("location: ".HOST_URL."adminpannel/manager_settings.php??active=teams") ;
								die() ;
						   
						   }

					   }else{
						   $_SESSION['error']= "phone number already exists !";

					   }
					   }else{
						   $_SESSION['error']= "email id already exists !";

					   }
					   }else{
						   $_SESSION['error']= "Your phone number Must Contain At Least 10 digits !";
					  
					 }
					}else{
						   $_SESSION['error']= "All Fields Are Not Empty! !";

					}
					   
					 }
						

// $sele="select * from boost_website where manager_id='$manager_ids'";
// $sele_qr=mysqli_query($conn,$sele);
// echo $manager_ids;
 $run=getTableData($conn, " boost_website ", " manager_id ='".$manager_ids. "'","",1);        

?>
<?php 
require_once("inc/style-and-script.php") ; ?>
		<style type="text/css">
			#getcsv {
			float: right;
			margin-bottom: 1em;
			}
			.custom-tabel .display{padding-top: 20px;}
			.custom-tabel .display th{min-width: 50px;}
			table.display.dataTable.no-footer {
			width: 1600px !important;
			}
			.menu{
				list-style: none;
				display: flex;
				margin: 5px;
				justify-content: space-around;
			}
			.Payment_method input{
				width: 100%;
				margin-bottom: 20px;
				padding: 12px;
				border: 1px solid #ccc;
				border-radius: 3px;
			}
			.Payment_method label{
				margin-bottom: 10px;
				display: block;

			}
			.payment_method_btn_wrap{
				width: 10%;
			}
			.text-h{   
				font-size: 25px;
				text-align: center;
    	}
    	 .Polaris-Card__Section ul{
				list-style: none;
				text-align: center;
				display: flex;
				flex-direction: column;
				margin: 0;
				position: relative;}
    	 .Polaris-Card__Section li{
					margin: 0 0 10px;
					position: relative;
					font-size: 15px;
					font-weight: 500;
					margin: 7px 0;
					color: #1d1d1bc7;
					text-transform: capitalize;}
		.price-tag{text-align: center;}
		.plan-name{text-align: center;}			
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
				<div class="container-fluid add_teams content__up">

				
			<h1>Add Teams</h1>
			<div class="back_btn_wrap ">
                	<a href="<?=HOST_URL?>adminpannel/manager_settings.php?active='team'" class="Polaris-Button">
                    <button type="button" class="back_btn btn btn-primary "> Back</button>
                </a>
                </div>
				<div class="form_h">
				<?php require_once("inc/alert-status.php") ; ?>

						<div class="Payment_method_wrap">
							<form method="post"  enctype="multipart/form-data" id="frmChkForm" name="form_check">
						
						<div class="form-group">
							<label for="name">First Name</label>
							<input type="text" class="form-control " id="name" name="fname" required>
						</div>

						<div class="form-group">
							<label for="cpassword">Last Name</label>
							<input type="text" class="form-control " id="" name="lname" required>
						</div>
						<div class="form-group">
							<label for="number">Phone</label>
							<input type="number"   class="form-control" id="ph_number" name="number" maxlength="20" required>
							<span class="error_mg" style="color: red;"><?php if (isset($passwordErr)){
								echo $passwordErr;
							} ?></span>
						</div>
						
						<div class="form-group">
							<label for="cpassword">Email</label>
							<input type="email" class="form-control" id="" name="emails" required>
						</div>
						
						<div class="form-group">
							<label for="cpassword">Password</label>
							<input type="password" class="form-control" id="" name="password" required>
						</div>
						
					
						<div class="addtean_web">
						 
						 <?php 
						// $run=mysqli_fetch_array($sele_qr);
						// print_r($run);

							for ($i=0; $i <count($run) ; $i++) { 
						
						?>
						<div class="card">
						<div class="body-card">
						<div class="form-check" id="group1" required> 
					<input hidden class="form-check-input position-static website"   type="text"    name="website[<?=$i?>][id]" value="<?php echo $run[$i]['id']; ?>" >
					<h4><?php echo parse_url($run[$i]['website_url'])['host']; ?></h4>
					  
					   
						</div>
						
						<div class="form-check">                                    
					<input id="fcdashboard<?=$i?>" class="form-check-input position-static" type="checkbox" class="group1" name="website[<?=$i?>][dashboard]" value="1" >
				  <label for="fcdashboard<?=$i?>">Dashboard</label>
						</div>
						
					<!-- 	<div class="form-check">                                    
					<input id="fcexpert_help<?=$i?>"  class="form-check-input position-static" type="checkbox" class="group1" name="website[<?=$i?>][exhelp]" value="1" >
					<label for="fcexpert_help<?=$i?>">Expert Help</label>
						</div> -->
						<div class="form-check">                                    
					<input id="fcexpert_help<?=$i?>"  class="form-check-input position-static" type="checkbox" class="group1" name="website[<?=$i?>][speed_tracking]" value="1" >
					<label for="fcexpert_help<?=$i?>">Speed Tracking</label>
						</div>
						<div class="form-check">                                    
					<input id="fcexpert_help<?=$i?>"  class="form-check-input position-static" type="checkbox" class="group1" name="website[<?=$i?>][speed_warranty]" value="1" >
					<label for="fcexpert_help<?=$i?>">Speed Warranty</label>
						</div>
						<div class="form-check">                                    
					<input id="fcpage_speed<?=$i?>" class="form-check-input position-static" type="checkbox" class="group1" name="website[<?=$i?>][pgsped]" value="1" >
					<label for="fcpage_speed<?=$i?>">Page Speed</label>
						</div>
						<div class="form-check">                                    
					<input id="fcscriptinst<?=$i?>" class="form-check-input position-static" type="checkbox"  class="group1" name="website[<?=$i?>][scinst]" value="1"> 
					<label for="fcscriptinst<?=$i?>">script Installation</label>
						</div>
				<!-- 		<div class="form-check">                                    
					<input id="fcnoh<?=$i?>" class="form-check-input position-static" type="checkbox" class="group1" name="website[<?=$i?>][nohelp]" value="1" >
					<label for="fcnoh<?=$i?>">Need Other Help</label>
						</div> -->
						
					 </div>
					   </div>
						  <?php
						}
					   ?>
					  
						</div>
						
						
						<div class="form_h_submit"> 
						<button type="submit" name="subbtnn" class="btn btn-primary mt-1" onclick="form_check();">Submit</button>
					</div>
					</form>
					
						</div>
					</div>
         
				</div>

			</div>
		</div>

		
	</script>
	
	</body>
</html>
<script>
   // $('#ph_number').on('keypress change blur', function () {
        // $(this).val(function (index, value) {
            // return value.replace(/[^a-z0-9]+/gi, '').replace(/(.{20})/g, '$1 ');
        // });
    // });
$(function() {
  enable_cb();
  $("#group1").click(enable_cb);
});

function enable_cb() {
  if (this.checked) {
    $("input.group1").removeAttr("disabled");
  } else {
    $("input.group1").attr("disabled", true);
  }
}
</script>>