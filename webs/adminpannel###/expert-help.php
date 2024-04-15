<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
require_once('config.php');
//include('meta_details.php');
require_once('session.php');
require_once('inc/functions.php') ;
require_once('smtp/PHPMailerAutoload.php');

// print_r($_SESSION) ;

if ( isset($_POST["submit_btn"]) ) {

	print_r($_POST) ;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($subject)   ||  empty($message) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	else {

          $helptype="expert help";
		$columns = " manager_id , website_id, subject , message , help_type " ;
		$values = " '".$_SESSION["user_id"]."' ,'".base64_decode($_GET['project'])."', '$subject' , '$message' ,'$helptype'" ;
		
		//  insert manager query
		if( insertTableData( $conn , " expert_queries " , $columns , $values ) ) {

			// send mail ----------------------------------------------------------------
			// get admin details
			$admin_data = getTableData( $conn , " admin_users " , " userstatus LIKE 'admin' ") ;
			//$manageridd= $_SESSION["user_id"];
			//$manager_data = getTableData( $conn , " admin_users " , " where id='$manageridd' ") ;
			
			
			$manageridd= $_SESSION["user_id"];
			$manag_query = "select * from admin_users where id='$manageridd'";
			 $runquery= mysqli_query($conn,$manag_query);
			 $data = mysqli_fetch_assoc($runquery);
			 
			// print_r($manager_data);
			
			$name = $data['firstname'] ;
			$email = $data['email'] ;
			$phone = $data['phone'] ;
			 
			// die();
			 
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
	        $mail->addReplyTo($email,$fullname);
	        $mail->Subject = "Expert Help:".$subject;
	        $mail->Body = "Hello admin, new help query,<br>Subject: {$subject} <br> Message : {$message}<br> Manager Details : <br> Name : {$name} <br> Email : {$email} <br> Phone:{$phone}<br> Thanks,<br>Website Speedy" ;
	        $mail->AddAddress($admin_data["email"]);
	        $mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));

	        if(!$mail->Send()) {
	            $_SESSION['error'] = "Technical error in sending the query to expert! Please try again later." ;
	        }
	        else {
	        	$_SESSION['success'] = "Your query is successfully send to our expert!" ;
	        }
		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}
	}

	// print_r($_SESSION) ;

	header("location: ".HOST_URL."adminpannel/expert-help.php") ;
	die() ;
}


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
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
				<div class="container-fluid expert__h content__up">
					
					<h1 class="mt-4">Expert Help</h1>
					<h5>Please fill the form below.</h5>
					<?php //print_r($row) ;?>
                 <div class="form_h">
				 <?php require_once("inc/alert-status.php") ; ?>
					<form method="POST">
						
						
						<div class="form-group">
							<label for="subject">Subject</label>
							<input type="text" class="form-control" id="subject" name="subject" placeholder="query subject" required>
						</div>

						
					  	<div class="form-group">
							<label for="message">Message</label>
							<textarea class="form-control" id="message" name="message" required></textarea>
					  	</div>

                      <div class="form-group  mt-1">
							
						<label class="form-check-label mb-2" for="collaborator"> <input type="checkbox" class="form-check-input " id="collaborator" onclick="checkthebox()" name="collaborator">Have you sent collaborator access to ishan@makkpress.com</label>
					  	</div>

                         <div class="form_h_submit">
						<button type="submit" id="submit_btn" name="submit_btn" disabled class="btn btn-success">Submit Query</button>
		</div>
					</form>
		</div>
				</div>
			</div>
		</div>
		<!-- Bootstrap core JS-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Core theme JS-->
		<script type="text/javascript">
			var checkbox = document.getElementById('collaborator');
			var submit_btn = document.getElementById('submit_btn');

		

			function checkthebox(){

				if(!checkbox.checked){

				submit_btn.disabled = true;

			}
			else{

				submit_btn.disabled = false;

			}

			}

		</script>
	</body>
</html>