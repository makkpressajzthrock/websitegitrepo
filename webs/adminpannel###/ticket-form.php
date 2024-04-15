<?php 

require_once('config.php');
require_once('inc/functions.php') ;
require_once('smtp/PHPMailerAutoload.php');

require 'smtp-send-grid/vendor/autoload.php';
// check sign-up process complete
// checkSignupComplete($conn) ;

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}



// print_r($_POST);
// die;

if ( isset($_POST["submit_btn"]) ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	// print_r($_POST); die() ;

	$website_name = $_POST['website_name'] ;
	$issue = $_POST['issue'] ;
	$email = $_POST['email'] ;
	$phone = $_POST['phone'] ;


	if ( $website_name == 'Your Websites' || empty($website_name) ) {
		$_SESSION['error'] = "Please fill all required values." ;
	}
	elseif ( $issue == null || empty($issue) ) {
		$_SESSION['error'] = "Please fill all required values." ;
	}
	elseif ( $email == null || empty($email) ) {
		$_SESSION['error'] = "Please fill all required values." ;
	}
	// elseif ( $phone == null || empty($phone) ) {
	// 	$_SESSION['error'] = "Please fill all required values." ;
	// }
	else 
	{
		// if (strlen($phone) > 10 && strlen($phone) < 20) {
		// }
		// else{
		// 	$_SESSION['error']= "Your phone number Must Contain At Least 10 digits !";
		// }

		$user_id = $_SESSION['user_id'];

		$sql = " INSERT INTO `support_tickets` ( `manager_id`, `issue`, `website`, `email`, `phone` ) VALUES ( '$user_id' , '$issue' , '".$conn->real_escape_string($website_name)."' , '$email' , '$phone' ) ; " ;

		// echo $sql;
		// die;

		if ( $conn->query($sql) === TRUE ) 
		{
	        // ---------------------------------------------------------------------------
			$ticket_id = $conn->insert_id;
			$link = HOST_URL."adminpannel/support-ticket.php" ;

	        $au_query = $conn->query(" SELECT * FROM admin_users WHERE id = '$user_id' ; ") ;
	        $au_data = $au_query->fetch_assoc() ;
	        $name = empty($au_data["firstname"]) ? $au_data["email"] : $au_data["firstname"].''.$au_data["lastname"] ;

	        // get email content from database ----------
	        $emailContent = getEmailContent( $conn , 'Ticket email' ) ;

	        // set email variable values ----------------
	        $emailVariables = array("name" => $name , "ticket_id" => $ticket_id , "link" => $link );

	        // replace variable values from message body ------
	        foreach($emailVariables as $key1 => $value1) {
	            $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
	        }

	        // get SMTP detail ---------------
	        $smtpDetail = getSMTPDetail($conn) ;
	        // print_r($emailVariables) ; print_r($emailContent) ; die() ;
	        // ------------------------------------------------------------------------------------

			
			// get admin details
			$admin_data = getTableData( $conn , " admin_users " , " userstatus LIKE 'admin' " ) ;
			// $admin_data["email"] = "support@websitespeedy.com" ;
// $admin_data["email"] = "aman@makkpress.com" ;
$emailss = new \SendGrid\Mail\Mail(); 
$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailss->setSubject($emailContent["subject"]);
$emailss->addTo($admin_data["email"],"Website Speddy");
$emailss->addContent("text/html",$emailContent["body"]);
$sendgrid = new \SendGrid($smtpDetail["password"]);
// $sendgrid->send($emailss);
try {
    $response = $sendgrid->send($emailss);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}


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
	        // $mail->Password = "rmzerahocjbegncr";
	        // $mail->SetFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
	        // $mail->addReplyTo($smtpDetail["from_email"],$smtpDetail["from_name"]);
	        // $mail->Subject = $emailContent["subject"];
	        // $mail->Body = $emailContent["body"] ;
	        // $mail->AddAddress($admin_data["email"]);
	        // $mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));
	        // $mail->Send() ;

	        $_SESSION['success'] = " Created Ticket Successfully. " ;

		}
		else {
			$_SESSION['error'] = "Operation Failed." ;
		}

	}

	header("location: ".HOST_URL."adminpannel/ticket-form.php") ;
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
				<div class="container-fluid ticket_form_S content__up">
					<h1 class="mt-4">Create a Ticket</h1>
					<!-- <h5>Will affect your subscription amount.</h5> -->
                    <div class="back_btn_wrap">
					<a href="<?=HOST_URL?>adminpannel/support-ticket.php" class="btn btn-primary">Back</a>
		            </div>

					<?php
						$user_id = $_SESSION["user_id"] ;

						$user_data = getTableData( $conn , " admin_users " , " id ='".$user_id."' " ) ;

						$website_data = getTableData( $conn , " boost_website " , " manager_id ='$user_id'","",1 );




					?>
                   <div class="form_h">
				   <?php require_once("inc/alert-status.php") ; ?>
					<form id="add-website-form" method="post">

						<div class="row">
						<div class="col">
						<div class="form-group">
							<label>Select Website</label>
							<select name="website_name"  required class="form-select" aria-label="Default select example">
						  <option selected>Your Websites</option>
						  <?php foreach($website_data as $value)
						  { ?>
						  <option value="<?php echo $value['website_url'];?>"><?php echo $value['website_url']; ?></option>
						  <?php
						}
						?>
						</select>
						</div>
						</div>
						</div>

						<div class="form-group">
							<label>What is your Issue?</label>
							<textarea name="issue" required class="form-control"></textarea>
				    	</div>
						<div class="form-group">
							<label>What's your Email-ID?</label>
							<input type="email" required name="email" value="<?=$user_data["email"];?>" class="form-control">
					    </div>
							<div class="form-group">
							<label>Enter your phone number.</label>
							<input type="number" required name="phone" value="<?=$user_data["phone"];?>" maxlength="20" id="ph__number" class="form-control">
						</div>

                       <div class="form_h_submit">
						<button type="submit" class="btn btn-primary" value="Submit" name="submit_btn">Submit</button>
					</div>
					</form>
					</div>	 
				</div>
			</div>
		</div>
<script type="text/javascript">
	   $('#ph__number').on('keypress change blur', function () {
        $(this).val(function (index, value) {
            return value.replace(/[^a-z0-9]+/gi, '').replace(/(.{20})/g, '$1 ');
        });
    });
</script>
	</body>
</html>

