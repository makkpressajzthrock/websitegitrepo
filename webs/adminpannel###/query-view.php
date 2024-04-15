<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
error_reporting(0);

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
require_once('smtp/PHPMailerAutoload.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);



?>


							<?php

							$manager_data = getTableData( $conn , " expert_queries " , " id = '".$_GET['query']."' " ) ;

						// print_r($manager_data) ;
							
							?>

							<?php 
							$manager_id=$manager_data["manager_id"];
							$select="select * from admin_users where id = '$manager_id'";
							$connect = mysqli_query($conn, $select);
							$qry = mysqli_fetch_array($connect) ;
							
							
							?>


<?php
							
							
							if ( isset($_POST['submit_btn']) ) {

	extract($_POST) ;
	if ( empty($message_text)) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}

	
	else {
		
		$qry_id=$manager_data["id"];
		$managers_id =$manager_data["manager_id"];
		$msg= $_POST['message_text'];
		$subject=$manager_data["subject"];
		$close=$_POST['close'];
		$managerName=$qry["firstname"];
		//$qryemail=$qry["email"];
		$htype=$manager_data["help_type"];
		$message=$manager_data["message"];


			// insert
			$columns = " expert_qry_id, manager_id, reply, subject, close_queries" ;
			$values = "'$qry_id','$managers_id','$msg', '$subject' ,'$close'" ;

			if ( insertTableData( $conn , "expert_reply" , $columns , $values ) ) {
				// send mail ----------------------------------------------------------------
			// get admin details
			//$admin_data = getTableData( $conn , " admin_users " , "id='".$_GET['query']."' " ) ;
			$admin_data = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

	        $mail = new PHPMailer(); 
	        // $mail->SMTPDebug=3;
	        $mail->IsSMTP(); 
	        $mail->SMTPAuth = true; 
	        $mail->SMTPSecure = 'tls'; 
	        $mail->Host = "smtp.gmail.com";
	        $mail->Port = "587"; 
	        $mail->IsHTML(true);
	        $mail->CharSet = 'UTF-8';
	        $mail->Username = SMTP_USER ;
	        $mail->Password = SMTP_PASSWARD ;
	        $mail->SetFrom("info@ecommercespeedy.com","Website Speedy");
	        $mail->addReplyTo($admin_data["email"],$admin_data["firstname"]);
			
			if($htype=="expert help"){
	        $mail->Subject = "{$htype} : {$subject}";
	        }
			else{
				$mail->Subject = "{$htype} : {$message}";
			}
			$mail->Body = "Hello {$managerName}, <br>Subject: {$subject}
			 <br> Message : {$msg}, <br> Thanks." ;
	        //$mail->AddAddress($admin_data["email"]);
	        $mail->AddAddress($qry["email"]);
	        //$mail->AddAddress('karan.makkpress@gmail.com');
	        $mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));

	        if(!$mail->Send()) {
	            $_SESSION['error'] = "Technical error in sending the query to expert! Please try again later." ;
	        }
	        else {
	        	$_SESSION['success'] = "Your query is successfully send to our expert!" ;
	        	// header("location: ".HOST_URL."adminpannel/query-view.php");
	        }


		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}
	}
}

?>

<?php require_once("inc/style-and-script.php") ; ?>
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
		</style>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>
<?php require_once("inc/style-and-script.php"); ?>
				<!-- Page content-->
				<div class="container-fluid content__up query_view">

					<h1>Managers</h1>
					<div class="back_btn_wrap">
					<a href="<?=HOST_URL."adminpannel/expert-queries.php"?>" class="btn btn-primary ">Back</a>
		            </div>
					 
					
					<div class="table_form"> 
					<table class="table speedy-table qyery_details">
						<tbody>



							
							
							

							
							
							<tr> <th>Fullname</th><td><?php echo $qry['firstname']." ". $qry['lastname'];?></td></tr>
							<tr> <th>Email</th><td><?php echo $qry["email"];?></td></tr>
							<tr> <th>Phone No</th><td><?php echo $qry["phone"];?></td></tr>
							<tr> <th>Subject</th><td><?=$manager_data["subject"]?></td></tr>
							<tr> <th>Message</th><td><?=$manager_data["message"]?></td></tr>
							<tr> <th>Query Date</th><td><?=$manager_data["created_at"]?></td></tr>
							<tr> <th>Help Type</th><td><?=$manager_data["help_type"]?></td></tr>
						</tbody>
					</table>
					
					
					<?php
					
					
					//get expert_reply all data 
					
					
					//$reply_data = getTableData( $conn , " expert_reply " , " id = '".$_SESSION['user_id']."' " ) ;
					// $qry_id=$manager_data["id"];
					
					//print_r($qry_id);
					
					// $sele_qr = "select * from expert_reply where expert_qry_id='$qry_id'";
					// $connects = mysqli_query($conn,$sele_qr);
					

					
					
					?>
	<!-- 			<form method="post">
				<?php require_once("inc/alert-status.php") ; ?>
						<div class="row">	
					<div class="row mt-2 mb-2">
						<div class="col-md-8 close_query"><input type="checkbox" class="form-check-input"  value="1" name="close">
						<label class="form-check-label" for="collaborator">Close Queries</label>
						</div>
					</div>
					
				<h4>Reply </h4>
				
				<table class="table speedy-table reply_table">
					
					 
						<?php
			  
			  while($fetch= mysqli_fetch_array($connects))
			  {
			
				  ?>
						  <tr>
						  <td><?php echo $fetch['reply'];?></td>
						  </tr>
						  <?php 
						 }
						 ?>
					
					</table>
					
						
						<div class="col-md-1"></div>
						<div class="col-md-10">
						
							<textarea class="form-control" rows="14" id="message_text" name="message_text"></textarea>
						</div>
						<div class="col-md-1"></div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8"><input type="submit" class="btn btn-success" id="submit_btn" name="submit_btn">
					</div>
					<div class="col-md-2"></div>
				</div>
				</form> -->
						</div>
					
				</div>
			</div>
		</div>
		<!-- Bootstrap core JS-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Core theme JS-->
		<script src="js/scripts.js"></script>
	</body>

</html>