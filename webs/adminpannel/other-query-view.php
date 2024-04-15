<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;
require_once('smtp/PHPMailerAutoload.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
$msg_id=$_GET["msg_id"];
if ( isset($_POST['submit_btn']) ) {

	extract($_POST) ;
	if ( empty($message_text)) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}

	
	else {

		// $manager_id = $_SESSION['user_id'] ;


		// check already saved data



			// insert
			$columns = " msg_id ,message" ;
			$values = " '$msg_id' , '$message_text'" ;

			if ( insertTableData( $conn , "admin_reply" , $columns , $values ) ) {
				// send mail ----------------------------------------------------------------
			// get admin details
			$admin_data = getTableData( $conn , " admin_users " , "id='".$_GET['query']."' " ) ;

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
	        $mail->Subject = "Admin ";
	        $mail->Body = "Hello manger, new help query,<br>Subject: 'Need Other Help' <br> Message : {$message_text}" ;
	        $mail->AddAddress($admin_data["email"]);
	        	// $mail->AddAddress('ajay.makkpress@gmail.com');
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
			.container {
/*	border:1px solid;*/
	padding: 40px;
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
			
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid">
					<?php require_once("inc/alert-status.php") ; ?>
					<h1 class="mt-4">Managers</h1>
					<a href="<?=HOST_URL."adminpannel/admin-other-help.php"?>" class="btn btn-primary float-right">Back</a>
					<table class="table speedy-table">
						<tbody>
							<?php

							$manager_data = getTableData( $conn , " other_help " , " id = '".$msg_id."' " ) ;

							// print_r($manager_data) ;
						$manger_details_queries = getTableData( $conn , " admin_users " , "id='".$manager_data["manager_id"]."'", " " , 0 ) ;

						// print_r($manger_details_queries);
							?>
							<tr> <th>Subject</th><td>Need Other Help</td></tr>
							<tr> <th>Message</th><td><?=$manager_data["message_text"]?></td></tr>
							<tr> <th>Query Date</th><td><?=$manager_data["created_at"]?></td></tr>
							<tr> <th>Fullname</th><td><?=$manger_details_queries["firstname"]." ".$manger_details_queries["lastname"];?></td></tr>
							<tr> <th>Email</th><td><?=$manger_details_queries["email"]?></td></tr>
							<tr> <th>Phone No</th><td><?=$manger_details_queries["phone"]?></td></tr>
						</tbody>
					</table>
					<h4>Reply </h4>
						<form method="post">
						<div class="row">
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
				</form>
				</div>
			</div>
		</div>

	</body>

</html>