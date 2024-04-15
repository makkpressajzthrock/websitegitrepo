<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
require_once('smtp/PHPMailerAutoload.php');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// print_r($_SESSION) ;
$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

$email = $row['email'];

$fullname = $row['firstname'].' '.$row['lastname'];

$phone = $row['phone'];

// print_r($row);

if ( isset($_POST['submit_btn']) ) {

	// print_r($_POST) ;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if($collaborator == 'on'){
		$collaborator = 1;
	}
	else{
		$collaborator = 0;
	}

$message_text= $_POST['message_text']; 
	if ( empty($message_text)) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	else {

		$manager_id = $_SESSION['user_id'] ;


		// check already saved data


  $helptype= " need other help";
  
			// insert
			$columns = " manager_id, website_id, message, help_type" ;
			$values = " '$manager_id' ,'".base64_decode($_GET['project'])."', '$message_text' ,'$helptype'" ;

			if ( insertTableData( $conn , "expert_queries" , $columns , $values ) ) {
				// send mail ----------------------------------------------------------------
			// get admin details
			$admin_data = getTableData( $conn , " admin_users " , " userstatus LIKE 'admin' " ) ;

	        $mail = new PHPMailer(); 
	        //$mail->SMTPDebug=3;
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
	        $mail->Subject = "Need Other Help ";
	        $mail->Body = "Hello admin, new help query,<br>Subject: 'Need Other Help' <br> Message : {$message_text}<br> Manager Details : <br> Name : {$fullname} <br> Email : {$email} <br> Phone: {$phone} <br> Thanks,<br>Website Speedy" ;
	        // $mail->AddAddress($admin_data["email"]);
	        	$mail->AddAddress('ajay.makkpress@gmail.com');
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
}



// print_r($row) ;



// print_r($row);

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>


		<style>
			.disbale-tab {
			    pointer-events: none;
			    cursor: not-allowed;
			    opacity: 0.5;
			}
		</style>

<style>
.container {
/*	border:1px solid;*/
	/* padding: 40px; */
}

@media (min-width: 420px) and (max-width: 659px) {
  .container {
    grid-template-columns: repeat(2, 160px);
  }
}

@media (min-width: 660px) and (max-width: 899px) {
  .container {
    grid-template-columns: repeat(3, 160px);
  }
}

@media (min-width: 900px) {
  .container {
    grid-template-columns: repeat(3, 160px);
  }
}

.container .box {
  width: 100%;
}


.container .box .chart {
	position: relative;
	width: 100%;
	height: 100%;
	text-align: center;
	font-size: 30px;
	height: 160px;
	color: black;
	padding: 50px;
}

.container .box canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  width: 100%;
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
				<div class="container-fluid other_help content__up">
					
					<h1 class="mt-4">Need other help related to your store</h1>
					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>
					<form method="POST">
					<!-- <div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<label for="name">Name</label>
							<input type="text" class="form-control" id="name" name="name">
						</div>
						<div class="col-md-1"></div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<label for="email">Email-ID</label>
							<input type="email" class="form-control" id="email" name="email">
						</div>
						<div class="col-md-1"></div>
					</div> -->
					<div class="row">
						<div class="col-md-10">
							<label for="message_text">Message</label>
							<textarea class="form-control" rows="14" id="message_text" name="message_text"></textarea>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8"><input type="checkbox" class="form-check-input" id="collaborator" onclick="checkthebox()" name="collaborator">
						<label class="form-check-label" for="collaborator">Have you sent collaborator access to ishan@makkpress.com</label>
						</div>
					</div>
					<div class="form_h_submit">
						<input type="submit" class="btn btn-success" id="submit_btn" name="submit_btn" disabled>
					</div>
				</form>
</div>
					<?php
						$data = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " ) ;
						
						// print_r($data) ;
					?>
				</div>
			</div>
		</div>

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
