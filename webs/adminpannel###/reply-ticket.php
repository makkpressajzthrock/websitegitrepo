<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('config.php');
require_once('inc/functions.php') ;
require_once('smtp/PHPMailerAutoload.php');
require 'smtp-send-grid/vendor/autoload.php';
// check sign-up process complete
// checkSignupComplete($conn) ;


$ticket_id = base64_decode($_GET['ticket_id']);

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

$user_id = $_SESSION['user_id'];

// print_r($_POST);

// die;


if ( isset($_POST["submit_btn"]) ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}



	$message_text = $_POST['message_text'] ;


	if ( $message_text == '' || empty($message_text) ) {
		$_SESSION['error'] = "Please fill all required values." ;
	}
	else {

		$sql = " INSERT INTO `ticket_replies` ( `ticket_id`, `manager_id`, `reply` ) VALUES ( '$ticket_id' , '$user_id' , '$message_text' ) ; " ;

		// echo $sql;
		// die;

		if ( $conn->query($sql) === TRUE ) {

			// get admin details
			$admin_data = getTableData( $conn , " admin_users " , " userstatus LIKE 'admin' " ) ;
			$admin_data["email"] = "ajay.makkpress@gmail.com" ;

			$support_ticket = getTableData( $conn , " support_tickets " , " id ='".$ticket_id."' " ) ;
			$user_data = getTableData( $conn , " admin_users " , " id ='".$support_ticket["manager_id"]."' " ) ;
			// print_r($user_data) ;

			// send reply to manager
			$mail_variables = array("ticket"=>$ticket_id , "message_text"=>$message_text ) ;

			$body = file_get_contents('template/manager-reply-ticket.html') ;
			// foreach($mail_variables as $key => $value) {
			    // $body = str_replace('{{'.$key.'}}', $value, $body);

			    //---------------------------check Email template
			    					$tickets_tam = getTableData( $conn , " support_tickets " , "id = '$ticket_id'" ) ;
			    					// $tickets_template_sub=$tickets_tam['issue'];


					$name = "Admin" ;
					$reply_messsage=$message_text;
					$reset_link= HOST_URL."adminpannel/";
				
					echo $email = $smtpDetail["email"]  ;
					
					// $email = "ajay.makkpress@gmail.com" ;
					// get email content from database ----------
					$emailContent = getEmailContent( $conn , 'Manager Reply' ) ;

					// set email variable values ----------------
					$emailVariables = array("name" => $name , "link" => $reset_link , "reply_messsage" => $reply_messsage, "ticket_id" => $ticket_id  );

					// replace variable values from message body ------
					foreach($emailVariables as $key1 => $value1) {
					    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
					}

					// get SMTP detail ---------------
					$smtpDetail = getSMTPDetail($conn) ;
					// print_r($emailVariables) ; print_r($emailContent) ; die() ;
					// ------------------------------------------------------------------------------------

			    					

			// }

$emailss = new \SendGrid\Mail\Mail(); 
$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailss->setSubject($emailContent["subject"]);
$emailss->addTo($email,"Website Speddy");
$emailss->addContent("text/html",$emailContent["body"]);
$sendgrid = new \SendGrid($smtpDetail["password"]);
			var_dump($sendgrid->Send($emailss) );		

			// send mail ----------------------------------------------------------------
			// $mail = new PHPMailer(); 
			// // $mail->SMTPDebug=3;
			// $mail->IsSMTP(); 
			// $mail->SMTPAuth = true; 
			// $mail->SMTPSecure = $smtpDetail["smtp_secure"]; 
			// $mail->Host = $smtpDetail["host"];
			// $mail->Port = $smtpDetail["port"]; 
			// $mail->IsHTML(true);
			// $mail->CharSet = 'UTF-8';
			// $mail->Username = $smtpDetail["email"] ;
			// $mail->Password = $smtpDetail["password"] ;
			// $mail->SetFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
			// $mail->addReplyTo($smtpDetail["from_email"],$smtpDetail["from_name"]);
			// $mail->Subject = $emailContent["subject"];
			// $mail->Body = $emailContent["body"] ;
			// $mail->AddAddress($email);
			// $mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));
			// var_dump($mail->Send() );
			// ---------------------------------------------------------------------------



			$_SESSION['success'] = "Reply Sent" ;
		}
		else {
			$_SESSION['error'] = "Operation Failed." ;
		}
	}

	header("location: ".HOST_URL."adminpannel/support-ticket.php") ;
	die();
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
				<div class="container-fluid content__up reply__ticket">
					<?php require_once("inc/alert-status.php") ; ?>
					<h1 class="mt-4">Reply to the ticket</h1>
                   <div class="back_btn_wrap ">
					<a href="<?=HOST_URL?>adminpannel/support-ticket.php" class="btn btn-primary" class="btn btn-primary">Back</a>
					<?php

					$tickets = getTableData( $conn , " support_tickets " , "id = '$ticket_id'" ) ;

					?>
					</div>
					<div class="form_h">

				   <div class="replay_issue form-group">	
					<div class="content_issue">
					<h3>Issue Subject: <?=$tickets['issue']?></h3>
					<h5>Website: <?=$tickets['website']?></h5>
                       
							<?php if($tickets['resolved'] != 1){ ?>

							</div>
					<a href="resolve_ticket.php?ticket_id=<?=base64_encode($ticket_id)?>"><button class="btn btn-primary">Resolve</button></a>

				<?php } ?>
							</div>

				<div class="chat__wrapper">
					<!---------------messages-------------->
					<div id="messages_box" class="chat_box_s">
					<?php

					$replies = getTableData( $conn , " ticket_replies " , "ticket_id = '$ticket_id'", 'ORDER BY updated_at asc',1 ) ;

					// echo '<pre>';

					foreach($replies as $reply_key => $reply_content){

						if ($reply_content['manager_id'] == 1){
							echo '<div class="row">
							<span style="padding:0; font-size:12px;">Admin</span>
							<div class="alert alert-secondary" role="alert">
							
  '.$reply_content['reply'].'
</div>
</div>';
						}
						else
						{
							echo '<div class="row">
							<span style="padding:0; font-size:12px;">You</span>
	<div class="col-md-6"></div>
<div class="alert alert-primary" role="alert">


  '.$reply_content['reply'].'
</div>
</div>';
						}

					}

					// echo '</pre>';

					?>
					</div>
						


					<!---------------messages-------------->

					<?php if($tickets['resolved'] != 1){ ?>

					<form method="POST">

						<div class="form-group">
							<label for="message_text">Message</label>
							<textarea class="form-control" rows="14" id="message_text" name="message_text"></textarea>
						</div>
<!-- 					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8"><input type="checkbox" class="form-check-input" id="collaborator" onclick="checkthebox()" name="collaborator">
						<label class="form-check-label" for="collaborator">Have you sent collaborator access to ishan@makkpress.com</label>
						</div>
						<div class="col-md-2"></div>
					</div> -->
						<div class="form_h_submit"><input type="submit" class="btn btn-success btn-primary" id="submit_btn" name="submit_btn">
				</form>
					<?php
				}
						$data = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " ) ;
						
						// print_r($data) ;
					?>
					</div>
				</div>
			</div>
			</div>
		</div>


		
	</body>
</html>

