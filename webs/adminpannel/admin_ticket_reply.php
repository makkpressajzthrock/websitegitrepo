<?php 

require_once('config.php');
require_once('meta_details.php');
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

	// print_r($_POST);
	// die();

	$message_text = $_POST['message_text'] ;


	if ( $message_text == '' || empty($message_text) ) {
		$_SESSION['error'] = "Please fill all required values." ;


	}
	else {

		$sql = " INSERT INTO `ticket_replies` ( `ticket_id`, `manager_id`, `reply` ) VALUES ( '$ticket_id' , '$user_id' , '$message_text' ) ; " ;

		// echo $sql;
		// die();

		if ( $conn->query($sql) === TRUE ) {

			$support_ticket = getTableData( $conn , " support_tickets " , " id ='".$ticket_id."' " ) ;
			$user_data = getTableData( $conn , " admin_users " , " id ='".$support_ticket["manager_id"]."' " ) ;
			// print_r($user_data) ;

			// send reply to manager
			$mail_variables = array("ticket"=>$ticket_id , "message_text"=>$message_text ) ;

			$body = file_get_contents('template/admin-reply-ticket.html') ;
			// foreach($mail_variables as $key => $value) {
			    // $body = str_replace('{{'.$key.'}}', $value, $body);
			// }
				//----------------------------------------check Email template
				


					$name = $user_data['firstname']." ".$user_data['lastname'] ;
					$reply_messsage=$message_text;
					$reset_link=HOST_URL."adminpannel/";
				
					// $email = "ajay.makkpress@gmail.com" ;
					$email = $user_data['email'] ;
					// get email content from database ----------
					$emailContent = getEmailContent( $conn , "Admin Reply") ;

					// set email variable values ----------------
					$emailVariables = array("name" => $name , "link" => $reset_link , "reply_messsage" => $reply_messsage, "ticket_id" => $ticket_id    );

					// replace variable values from message body ------
					foreach($emailVariables as $key1 => $value1) {
					    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
					}

					// get SMTP detail ---------------
					$smtpDetail = getSMTPDetail($conn) ;
					// print_r($emailVariables) ; print_r($emailContent) ; die() ;
					// ------------------------------------------------------------------------------------

			    					

			// }

// Send Grid
			$emailss = new \SendGrid\Mail\Mail(); 
$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailss->setSubject($emailContent["subject"]);
$emailss->addTo($email,"Website Speddy");
$emailss->addContent("text/html",$emailContent["body"]);
// $email->addContent(
//     "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
// );
$sendgrid = new \SendGrid($smtpDetail["password"]);
// var_dump($sendgrid->Send());


try {
    $response = $sendgrid->send($emailss);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}		




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

	header("location: ".HOST_URL."adminpannel/tickets.php") ;
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
			<div class="container-fluid content__up admin_ticket_replay">
				<h1 class="mt-4">Reply to the ticket</h1>
				<?php require_once("inc/alert-status.php") ; ?>
				<div class="back_btn_wrap ">
				<a href="<?=HOST_URL?>adminpannel/tickets.php" class="btn btn-primary">Back</a>
                </div>
				<div class="profile_tabs tickets_reply_s">


                <div class="issue_subject">
				<?php

					$tickets = getTableData( $conn , " support_tickets " , "id = '$ticket_id'" ) ;

					?>
				<h3>Issue Subject:
					<?=$tickets['issue']?>
				</h3>
				<h5>Website:
					<?=$tickets['website']?>
				</h5>
					<?php  
					$tickets_resolved = getTableData( $conn , " support_tickets " , "resolved = 0 AND id = '$ticket_id'" ) ; 
					if(count($tickets_resolved)>0){ ?>
				<a href="resolve_ticket.php?ticket_id=<?=base64_encode($ticket_id)?>"><button
						class="btn btn-primary">Resolve</button></a>
					<?php }else{ ?>
						<div class="issue_resolved">Issue was resolved</div>
					<?php } ?>

                </div>
				<!---------------messages-------------->
				<div id="messages_box" class="chat_s">
				<div class="glass"></div>
				<div class="glass"></div>
				<div class="glass"></div>
				<div class="glass"></div>
				<div class="glass"></div>
				<div class="glass"></div>
				<div class="glass"></div>
				<div class="glass"></div>
					<div class="messages_scroll">
					<?php

					$replies = getTableData( $conn , " ticket_replies " , "ticket_id = '$ticket_id'", 'ORDER BY updated_at asc',1 ) ;

					// echo '<pre>';

					foreach($replies as $reply_key => $reply_content){

						if ($reply_content['manager_id'] == 2){
							echo '<div class="row">
							<span>User</span>
							<div class="col-md-6"><div class="alert alert-secondary" role="alert">
							
							
							
  '.$reply_content['reply'].'
</div></div>
</div>';
						}
						else
						{
							echo '<div class="row user_s">
							<span>You</span>							
<div class="col-md-6">

<div class="alert alert-primary" role="alert">

							
							
  '.$reply_content['reply'].'
</div>
</div>
</div>';
						}

					}

					// echo '</pre>';

					?>
				



				<!---------------messages-------------->
                </div>
				<form method="POST">


						<div class="col-md-1"></div>
							<!-- <label for="message_text">Message</label> -->
							<div class="send_btn_write">
							<textarea class="form-control" rows="14" id="message_text" name="message_text" placeholder="Type your message"></textarea>


					<button type="submit" class="btn btn-primary send_btn" id="submit_btn"
								name="submit_btn" value="Send"><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
<path d="M96 904 c-18 -17 -25 6 79 -251 l62 -153 -77 -192 c-43 -105 -76 -197 -73 -204 2 -7 11 -15 19 -18 16 -6 783 373 803 398 11 13 11 19 0 32 -11 14 -713 371 -776 395 -16 6 -27 4 -37 -7z m714 -404 c0 -3 -142 -76 -315 -163 -173 -86 -315 -154 -315 -150 0 3 25 68 56 144 l56 138 152 3 151 3 0 25 0 25 -151 3 -152 3 -56 138 c-31 76 -56 141 -56 144 0 4 142 -64 315 -150 173 -87 315 -160 315 -163z"/>
</g>
</svg></button>
				</div>
				</form>
				</div>

			</div>
		</div>
		</div>
	</div>



</body>

</html>