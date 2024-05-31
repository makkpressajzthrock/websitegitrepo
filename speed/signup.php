<?php

require_once("/var/www/html/adminpannel/env.php") ;

require_once("adminpannel/config.php");
require_once("adminpannel/inc/functions.php");
require_once("adminpannel/smtp/PHPMailerAutoload.php");
require 'adminpannel/smtp-send-grid/vendor/autoload.php';
// echo "<pre>";
// print_r($_POST);
// die;
// print_r($_SESSION);
$analyze = $_GET['ref'];
$codeResendShowOtp = 0;

 if ( $_SESSION['role'] == "manager" || $_SESSION['role'] == "team" ) {

			$_SESSION['error'] = "Signup page will not show if user already logged in!";

        header("location: ".HOST_URL."adminpannel/dashboard.php") ;
        die();
    }

if (isset($_POST['register'])) {
		// echo "<pre>";
		// print_r($_POST);
		// die;
	$_SESSION['nameErr'] = $_POST['f_name'];//123		
	$_SESSION['emailErr'] = $_POST['email'];		
	$_SESSION['passwordErr'] = $_POST['password'];		
	$password = $_POST["password"];

	$flag = 0;



	if (strlen($_POST["password"]) < '8') {
		$flag = 1;
		$passwordErr = "Your Password Must Contain At Least 8 Characters using 1 lowercase letters, 1 Number, 1 Uppercase letters and 1 special characters(e.g., a–z, A–Z,0-9 !@#$%^&)!";
	}
	elseif (strlen($_POST["password"]) > '48') {
		$flag = 1;
		$passwordErr = "Your Password Must Contain At Least 8 Characters using 1 lowercase letters, 1 Number, 1 Uppercase letters and 1 special characters(e.g., a–z, A–Z,0-9 !@#$%^&)!";
	}
	 elseif (!preg_match("#[0-9]+#", $password)) {
		$flag = 1;
		$passwordErr = "Your Password Must Contain At Least 8 Characters using 1 lowercase letters, 1 Number, 1 Uppercase letters and 1 special characters(e.g., a–z, A–Z,0-9 !@#$%^&)!";
	} elseif (!preg_match("#[A-Z]+#", $password)) {
		$flag = 1;
		$passwordErr = "Your Password Must Contain At Least 8 Characters using 1 lowercase letters, 1 Number, 1 Uppercase letters and 1 special characters(e.g., a–z, A–Z,0-9 !@#$%^&)!";
	} elseif (!preg_match("#[a-z]+#", $password)) {
		$flag = 1;
		$passwordErr = "Your Password Must Contain At Least 8 Characters using 1 lowercase letters, 1 Number, 1 Uppercase letters and 1 special characters(e.g., a–z, A–Z,0-9 !@#$%^&)!";
	}




	// echo"<pre>" ; print_r($_POST) ; die() ;


	if ($flag == 0) {

		// foreach ($_POST as $key => $value) {
		// 	$_POST[$key] = $conn->real_escape_string($value) ;
		// }
		extract($_POST);

 		$email=strtolower($email);


		if (empty($email ) || empty($password)) {
			$_SESSION['error'] = "Please fill all fields!";
		} else {

			// check email.
			$query = $conn->query(" SELECT id FROM `admin_users` WHERE email LIKE '$email' ");

			if ($query->num_rows > 0) {
				// $_SESSION['error'] = "Email already taken!";
				$_SESSION['error'] = "<span>Email already taken! </span> <a href='" . HOST_URL . "adminpannel/forgetpassword.php' target='_blank'>Reset Password</a>";//123
			} else {

				unset($_SESSION['nameErr']);
				unset($_SESSION['emailErr']);
				unset($_SESSION['passwordErr']);
				$pwd = md5($password);
				$help_p = base64_encode($password);

				$sql = " INSERT INTO `admin_users`( `email`, `password`, `userstatus`,firstname, lastname, country, country_code, phone , help_pass) VALUES ( '$email' , '$pwd' , 'manager' , '$f_name' , '$l_name' , '$country' , '$country_code' , '$phone', '$help_p' ) ";

				if ($conn->query($sql) === TRUE) {

					$insert_id = $conn->insert_id;
					echo "insert_id:" . $insert_id;

					$otp = mt_rand(100000, 999999);
					$otp_time = date('Y-m-d H:i:s');

					$conn->query(" INSERT INTO `user_confirm`( `user_id` , `otp_value` , `otp_time` ) VALUES ( '$insert_id' , '$otp' , '$otp_time' ) ");


// Start send email to admin

					$emailContent = getEmailContent($conn, 'Admin Emails');
 					$body = "
						<tr><td>Email : $email </td></tr>
						<tr><td>Name : $f_name </td></tr>
						<tr><td>Country : $country </td></tr>
						<tr><td>Country Code : $country_code </td></tr>
						<tr><td>Phone : $phone </td></tr> 

					";
				 
						$emailContents = str_replace('{{body}}', $body, $emailContent["body"]);
				 

					// get SMTP detail ---------------
					$smtpDetail = getSMTPDetail($conn);
 
					$emailsss = new \SendGrid\Mail\Mail(); 
					$emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
					$emailsss->setSubject("We Got New Registration from ". $email);
					$emailsss->addTo("service@websitespeedy.com","Website Speddy");
					$emailsss->addContent("text/html",$emailContents);
					// email flow
					$emailsss->addBcc(EMAIL_ADDRESS_TESTING,EMAIL_NAME_TESTING);
					$sendgrid = new \SendGrid($smtpDetail["password"]);
					$sendgrid->Send($emailsss);
					$subject = "We Got New Registration from ". $email;

					$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('1', '".$subject."', '".$conn->real_escape_string($emailContents)."', CURDATE())");
// End email to admin

					$_SESSION['confirm-email'] = $email;
	 

 
					$emailContent = getEmailContent($conn, 'Register email');

		 
					$emailVariables = array("email" => $email, "varification-code" => $otp);

 
					foreach ($emailVariables as $key1 => $value1) {
						$emailContent["body"] = str_replace('{{' . $key1 . '}}', $value1, $emailContent["body"]);
					}

					// $smtpDetail = getSMTPDetail($conn);

					// $mail = new PHPMailer();
					// $mail->SMTPDebug = 3;
					// $mail->IsSMTP();
					// $mail->SMTPAuth = true;
					// $mail->SMTPSecure = $smtpDetail["smtp_secure"];
					// $mail->Host = $smtpDetail["host"];
					// $mail->Port = $smtpDetail["port"];
					// $mail->IsHTML(true);
					// $mail->CharSet = 'UTF-8';
					// $mail->Username = "service@websitespeedy.com";
					// $mail->Password = "xyajjjdkkluqwzhp";
					// $mail->SetFrom($smtpDetail["from_email"], $smtpDetail["from_name"]);
					// $mail->addReplyTo($smtpDetail["from_email"], $smtpDetail["from_name"]);
					// $mail->Subject = $emailContent["subject"];
					// $mail->Body = $emailContent["body"];
					// $mail->AddAddress($email);
					// $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => false));
 


					$smtpDetail = getSMTPDetail($conn);
 
					$emailss = new \SendGrid\Mail\Mail(); 
					$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
					$emailss->setSubject($emailContent["subject"]);
					$emailss->addTo($email,"Website Speddy");
					// email flow
					$emailss->addBcc(EMAIL_ADDRESS_TESTING,EMAIL_NAME_TESTING);
					$emailss->addContent("text/html",$emailContent["body"]);
					$sendgrid = new \SendGrid($smtpDetail["password"]);

					$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('".$insert_id."', '".$emailContent["subject"]."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE())");
 
					if (!$sendgrid->Send($emailss)) {
					// if(!$mail->send()) {

						$conn->query(" DELETE FROM admin_users WHERE id = '$insert_id' ");
						unset($_SESSION['confirm-email']);
						$_SESSION['error'] = "Technical error in sending confirmation code! Please try again later.";
					}


					header("location: " . HOST_URL . "signup.php?confirm=1");
					die();
				} else {
					$_SESSION['error'] = "Operation failed!";
					$_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}

		// print_r($_SESSION) ;
		header("location: " . HOST_URL . "signup.php");
		die();
	}





}
	if (isset($_GET['resend-code'])) {

		// echo"<pre>" ; print_r($_SESSION) ; die() ;

		$codeResendShowOtp = 1;

		if (empty($_SESSION["confirm-email"])) {
			unset($_SESSION["confirm-email"]);
			$_SESSION['error'] = "Invalid request.";
			header("location: " . HOST_URL . "signup.php");
			die();
		} else {
			$confirmEmail = $_SESSION["confirm-email"];

			$query = $conn->query(" SELECT id FROM `admin_users` WHERE email LIKE '$confirmEmail' ");

			if ($query->num_rows > 0) {
				$user_data = $query->fetch_assoc();
				$user_id = $user_data["id"];

				$otp = mt_rand(100000, 999999);
				$otp_time = date('Y-m-d H:i:s');

				$querys = $conn->query(" SELECT id FROM `user_confirm` WHERE user_id = '$user_id' ");

				if ($querys->num_rows > 0) {

					$sql = " UPDATE user_confirm SET `otp_value`= '$otp' , `otp_expire`= '0' , `otp_time`= '$otp_time',requests = requests + 1 WHERE user_id = '$user_id' ";
				}
				else{
				    $conn->query(" INSERT INTO `user_confirm`( `user_id` , `otp_value` , `otp_time` ) VALUES ( '$user_id' , '$otp' , '$otp_time' ) ");

				}

				if ($conn->query($sql) === TRUE) {

					// get email content from database ----------
					$emailContent = getEmailContent($conn, 'Register email');

					// set email variable values ----------------
					$emailVariables = array("email" => $confirmEmail, "varification-code" => $otp);

					// replace variable values from message body ------
					foreach ($emailVariables as $key1 => $value1) {
						$emailContent["body"] = str_replace('{{' . $key1 . '}}', $value1, $emailContent["body"]);
					}

					// get SMTP detail ---------------
					$smtpDetail = getSMTPDetail($conn);
					// print_r($emailVariables) ; print_r($emailContent) ; die() ;
					// ------------------------------------------------------------------------------------

					// send mail ----------------------------------------------------------------
$emailsss = new \SendGrid\Mail\Mail(); 
$emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailsss->setSubject($emailContent["subject"]);
$emailsss->addTo($confirmEmail,"Website Speddy");
// email flow
$emailsss->addBcc(EMAIL_ADDRESS_TESTING,EMAIL_NAME_TESTING);
$emailsss->addContent("text/html",$emailContent["body"]);
$sendgrid = new \SendGrid($smtpDetail["password"]);


 

$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('$user_id', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE())");

					// $mail = new PHPMailer();
					// $mail->SMTPDebug = 3;
					// $mail->IsSMTP();
					// $mail->SMTPAuth = true;
					// $mail->SMTPSecure = $smtpDetail["smtp_secure"];
					// $mail->Host = $smtpDetail["host"];
					// $mail->Port = $smtpDetail["port"];
					// $mail->IsHTML(true);
					// $mail->CharSet = 'UTF-8';
					// $mail->Username = "service@websitespeedy.com";
					// $mail->Password = "xyajjjdkkluqwzhp";
					// $mail->SetFrom($smtpDetail["from_email"], $smtpDetail["from_name"]);
					// $mail->addReplyTo($smtpDetail["from_email"], $smtpDetail["from_name"]);
					// $mail->Subject = $emailContent["subject"];
					// $mail->Body = $emailContent["body"];
					// $mail->AddAddress($confirmEmail);
					// $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => false));

						if (!$sendgrid->Send($emailsss)) {
				// if(!$mail->send()) {
						$conn->query(" DELETE FROM admin_users WHERE id = '$insert_id' ");
						unset($_SESSION['confirm-email']);

						$_SESSION['error'] = "Technical error in sending confirmation code! Please try again later.";
					}


					header("location: " . HOST_URL . "signup.php?confirm=1");
					die();
				} else {
					$_SESSION['error'] = "Operation failed!";
					$_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}

		// print_r($_SESSION) ;
		header("location: " . HOST_URL . "signup.php");
		die();
	}
	if (isset($_POST["confirm-email"])) {
		// echo"<pre>" ;print_r($_POST) ;print_r($_SESSION) ;die();


		$code = $conn->real_escape_string($_POST['code']);
		$confirmEmail = $_SESSION["confirm-email"];

		// check un-confirmed manager
		$query = $conn->query(" SELECT * FROM `admin_users` WHERE `email` LIKE '$confirmEmail' AND status = 0 ; ");


		if ($query->num_rows > 0) {
			$userData = $query->fetch_assoc();
			$user_id = $userData["id"];

			$query = $conn->query(" SELECT * FROM `user_confirm` WHERE `user_id` = '" . $user_id . "' ");
			$confirmData = $query->fetch_assoc();

			if ($confirmData["otp_expire"] == 1) {
				// echo 1; die;
				$_SESSION['error'] = " Expired OTP Code. Please try by click on 'Resend Code' button.";
			} else {
				// echo 2; 
				$otp_time = strtotime($confirmData["otp_time"]);
				$nextFive = strtotime('+20 seconds', $otp_time);
				$current_time = strtotime(date('Y-m-d H:i:s'));

				// $diff = $current_time - $nextFive ;
				$diff = $nextFive - $current_time;
 
					if ($confirmData["otp_value"] == $code) {
						// echo 3;die;
						$sql = " UPDATE `user_confirm` SET `otp_expire` = '1' WHERE id = '" . $confirmData['id'] . "'; ";
						$conn->query(" UPDATE admin_users SET status = '1' WHERE id = '" . $user_id . "' ; ");
						$result = $conn->query($sql);
	 


 
						// $emailContent = getEmailContent($conn, 'Thanks Register');
						// $smtpDetail = getSMTPDetail($conn);
	
						// $emailsss = new \SendGrid\Mail\Mail(); 
						// $emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
						// $emailsss->setSubject($emailContent["subject"]);
						// $emailsss->addTo($confirmEmail,"Website Speddy");
						// $emailsss->addContent("text/html",$emailContent["body"]);
						// $sendgrid = new \SendGrid($smtpDetail["password"]);

						// $conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('$user_id', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE())");

						 
						if ($result) {
							// echo 1; die;
	 
							unset($_SESSION["confirm-email"]);
							$_SESSION['user_id'] = $user_id;
							$_SESSION['role'] = $userData["userstatus"];

							// header("location:" . HOST_URL . "basic_details.php");//123
							header("location:" . HOST_URL . "customize-flow.php");
							die();
						}
 
					} else {
						// echo 4; die;
						$_SESSION['error'] = " Invalid Code.";
					}
				
			}
		} else {
		
			$_SESSION['error'] = " Invalid request. ";
		}

		header("location:" . HOST_URL . "signup.php?confirm=1");
		die();
	}

 

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <title>Home</title> -->
	<link rel="icon" type="image/x-icon" href="img/favicon.ico" />
	<script src="./js/dotlottie-player.js"></script> 
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MK5VN7M');</script>
<!-- End Google Tag Manager -->


<?php 
if(preg_match('/www/', $_SERVER['HTTP_HOST']))
{
  $url = str_replace("www.","",$_SERVER['HTTP_HOST']);
  header("location: https://$url$_SERVER[REQUEST_URI]");
  die();
}

 $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 $actual_link = explode("?", $actual_link)
?>
<link rel="canonical" href="<?=$actual_link[0]?>" />




	<?php require_once('inc/style-script.php'); ?>

	<?php 
        $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if($actual_link== HOST_URL.'signup.php' || $actual_link == HOST_URL.'signup.php/')
			{
				?>
			<title>Create Your Account - Sign Up</title>    
			<meta name="description" content="Create your account in seconds and sign up for Website Speedy. Enjoy fast and secure access to all our features, as well as exclusive discounts and offers." />
			<meta name="keywords" content="" />
				<?php 
			}

	?>

</head>
<style type="text/css">
	/* The message box is shown when the user clicks on the password field */
#message {
  display:none !important;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 30px;
  margin-bottom: 15px;
}



#message p {
  padding: 10px 35px;
  font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}
</style>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div class="register-page">
  <div class="register-form">
  <div class="logo-header">
						<a href="<?=HOST_URL?>" ><img src="./img/signup_logo.webp" alt="Website Speedy Logo"></a>
				</div>
    <div class="form-design">
      <h2 class="heading">Create Your Account</h2>


				<?php

				require_once('inc/alert-message.php');

				if (isset($_GET['confirm']) && ($_GET['confirm'] == 1 && !empty($_SESSION['confirm-email']))) {



					$confirmEmail = $_SESSION['confirm-email'];

					$query = $conn->query(" SELECT * FROM `admin_users` WHERE `email` LIKE '$confirmEmail' AND status = 0 ; ");

					// print_r($query) ;
					if ($query->num_rows <= 0) {
						header("location: " . HOST_URL . "signup.php");
						die();
					}

					$userData = $query->fetch_assoc();

					$query = $conn->query(" SELECT * FROM `user_confirm` WHERE `user_id` = '" . $userData["id"] . "' ; ");
					$confirmData = $query->fetch_assoc();

					// echo "<pre>";print_r($confirmData) ; 

					$otp_time = strtotime($confirmData["otp_time"]);
					$nextFive = strtotime('+20 seconds', $otp_time);
					$current_time = strtotime(date('Y-m-d H:i:s'));
					// $diff = $current_time - $nextFive ;

					$diff = $nextFive - $current_time;
					//123
					if ($diff <= 0) {
						// echo 5;die;
						// expire manger otp
						$conn->query(" UPDATE `user_confirm` SET `otp_expire` = '0' WHERE `id` = '" . $confirmData['id'] . "'; ");
					}


					// echo "diff : ".$diff;
					// $nextFive = strtotime('+5 minutes', $otp_time ) ;
					// $nextFive = date('i:s', $diff ) ;

					// echo "nextFive : ".$nextFive;




				?>
					<form method="post" class="form" id="confirm-email-form" data-otp="<?= $confirmData['id']; ?>">
						<h3>Confirm your email</h3>
						<p>A verification code has been sent to you at <?= $confirmEmail ?>. Enter the code below:</p>
						<div class="form-input">
							<input type="text" class="input-design" id="code" name="code" placeholder="Enter Code" required>
						</div>
						<p>You can resend the code in <span id="countdown">00:20</span></p>
						<!-- //123 -->
						<p id="useotp" <?php if($confirmData["requests"] >= 3){echo "style='display:block';";}else{echo "style='display:none';";} ?> >Use this OTP to continue : <span id="useotp_Text" class="useotp_text" onclick="copy();"><span id="mainOtp"><?=$confirmData["otp_value"]?></span><span id="copy_status">Copied</span></span></p>

						<div class="form-input">
						<button type="submit" class="submit-button" value="confirm-email" name="confirm-email">Confirm Email</button>
						<button type="button" id="resend-code" class="submit-button" href="<?= HOST_URL ?>signup.php?resend-code=<?= $confirmEmail ?>" disabled>
						 Resend Code 
						</button>
						</div>

						<div class="form-input">
						<p>Haven't received an email? please check your spam folder or contact us at <a href="mailto:support@websitespeedy.com">support@websitespeedy.com</a></p>
						<p>Entered your email incorrectly? <a href="<?=HOST_URL?>signup.php">Change your email	</a></p>
			        	</div>
					</form>
				<?php
				} else {
				?>
					<form method="post" class="form">

						<div class="form-input">
									<label class="lable-design" for="f_name">Name</label>
									<input class="input-design" type="text" class="form-control" id="f_name" value="<?=isset($_SESSION['nameErr'])?$_SESSION['nameErr']:''?>" placeholder="Enter your  Name" name="f_name" required>
						</div>		
						
						

						<div class="form-input">
							<label class="lable-design"  for="email">Email address</label>
							<input class="input-design" type="email" class="form-control" id="email" value="<?=isset($_SESSION['emailErr'])?$_SESSION['emailErr']:''?>" placeholder="Enter your email" name="email">
						</div>

						<div class="form-input pass__field">
							<label class="lable-design"  for="password">Password</label>
							<input class="input-design"  type="password" class="form-control" id="password" value="<?=isset($_SESSION['passwordErr'])?$_SESSION['passwordErr']:''?>" placeholder="Enter your password"  maxlength="48" name="password">

							<div class="icon-show-pass" onclick="show(this)">
							<svg class="hide" width="30px" height="30px" viewBox="0 0 24 24" fill="none">
								<path d="M12 16.01C14.2091 16.01 16 14.2191 16 12.01C16 9.80087 14.2091 8.01001 12 8.01001C9.79086 8.01001 8 9.80087 8 12.01C8 14.2191 9.79086 16.01 12 16.01Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M2 11.98C8.09 1.31996 15.91 1.32996 22 11.98" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M22 12.01C15.91 22.67 8.09 22.66 2 12.01" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							<svg class="show" width="30px" height="30px" viewBox="0 0 24 24" fill="none">
								<path d="M14.83 9.17999C14.2706 8.61995 13.5576 8.23846 12.7813 8.08386C12.0049 7.92926 11.2002 8.00851 10.4689 8.31152C9.73758 8.61453 9.11264 9.12769 8.67316 9.78607C8.23367 10.4444 7.99938 11.2184 8 12.01C7.99916 13.0663 8.41619 14.08 9.16004 14.83" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M12 16.01C13.0609 16.01 14.0783 15.5886 14.8284 14.8384C15.5786 14.0883 16 13.0709 16 12.01" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M17.61 6.39004L6.38 17.62C4.6208 15.9966 3.14099 14.0944 2 11.99C6.71 3.76002 12.44 1.89004 17.61 6.39004Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M20.9994 3L17.6094 6.39" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M6.38 17.62L3 21" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M19.5695 8.42999C20.4801 9.55186 21.2931 10.7496 21.9995 12.01C17.9995 19.01 13.2695 21.4 8.76953 19.23" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							</div>

							<span id="erromsgs" style="color:red;"><?php echo $passwordErr; ?></span>
						</div>
						<div id="message">
						<h3>Password must contain the following:</h3>
						<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
						<p id="capital" class="invalid">A <b>capital (UPPERCASE)</b> letter</p>
						<p id="number" class="invalid">A <b>number</b></p>
						<p id="length" class="invalid">Minimum <b>8 characters</b></p>
						</div>

						<div class="form-input ckeck-box">
          <input type="checkbox" class="form-check-input" id="check1" name="option1" value="something" checked>
          <label class="form-check-label" for="check1">I agree Websitespeedy <a href="/terms-of-use.php" target="blank">Terms of Service</a> and <a href="/privacy-policy.php" target="blank">Privacy Policy</a></label>
        </div>

						  <!-- <div class="g-recaptcha" id="rcaptcha"  data-sitekey="6Leoz1gkAAAAAH_zR0uTCDhMlnWFnzGXFPWqvRXR"></div> -->
              <!-- <span id="captcha" style="color:red"/> -->
            
						<div class="form-input">
							<button type="submit" class="submit-button" value="register" name="register">Create Your Account</button>

							<p class="already-account">Already have an account? <a href="<?= HOST_URL ?>adminpannel/" class="login_btns">Log in</a></p>
						</div>
					</form>
				<?php
				}

				?>


    </div>

    <div class="footer-design">
      <p class="copyright">Copyright © <?php $year = date("Y"); echo $year; ?> <a href="<?=HOST_URL?>">Websitespeedy</a> All rights reserved.</p>
    </div>
  </div>
  <div class="register-detail">
    <!-- <img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/speed-icon.png" class="posotion"> -->
    <div class="design">
        <div class="register-detail-design">
          <div class="item">
            <img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/Web-Vitals.webp">
            <p class="heading">Enhanced User Experience</p>
            <p class="text">Improve user satisfaction on Mobile & desktop with faster loading website.</p>
          </div>
          <div class="item">
          	<img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/seo-new.png">
            <p class="heading">Improved SEO Rankings</p>
            <p class="text">Search engines favor faster websites in their rankings.</p>
          </div>
          <div class="item">
          	<img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/conversion-rate-1.webp">
            <p class="heading">Cost Savings</p>
            <p class="text">Reduce ad costs, bounce rate & boosts marketing efficiency with faster website.</p>
          </div>
          <div class="item">
            <img src="https://websitespeedycdn.b-cdn.net/speedyweb/images/ads.webp">
            <p class="heading">Uplifted Ad Performance & Conversions</p>
            <p class="text">Improved ad quality scores leads to higher sales through Google & Facebook Ads</p>
          </div>
        </div>
		<div class="register-review_admin">
        <div class="rating__wrpper">
                    <div class="stars">
                                <img loading="lazy" height="20" width="120" src="//websitespeedycdn.b-cdn.net/speedyweb/images/review-stars-five-image-200w.webp" class="fit__content__img" alt="five star rating">
                                    <span>Rated 5 Star Across the Platforms</span>
                    </div>
                    <div class="logos">
                        <a href="//www.capterra.com/p/10005566/Website-Speedy/" aria-label="capterra" target="_blank"><img src="//websitespeedycdn.b-cdn.net/speedyweb/images/capterra-trans-new.webp" alt="Serchen logo" class="capterra"></a>
                        <a href="//www.trustpilot.com/review/websitespeedy.com" aria-label="trustpilot" target="_blank"><img  src="//websitespeedycdn.b-cdn.net/speedyweb/images/trust-pilot-logo.png" alt="Trust Pilot logo" class="trust-pilot"></a>
                        <a href="https://sourceforge.net/software/product/Website-Speedy/" aria-label="sourceforge" target="_blank">
                        <span style="display:none">sourceforge</span>    
                        <svg class="sourceforge" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 653 102.6" style="enable-background:new 0 0 653 102.6;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#ff6600;}
                            .st1{fill:#000000;}
                            .st2{fill:#000000;}
                        </style>
                        <path class="st0" d="M66.9,54.5c0-19.1-6.8-27.8-10.4-31.1c-0.7-0.6-1.8-0.1-1.7,0.9c0.7,10.8-12.9,13.5-12.9,30.4h0     c0,0,0,0.1,0,0.1c0,10.3,7.8,18.7,17.4,18.7c9.6,0,17.4-8.4,17.4-18.7c0,0,0-0.1,0-0.1h0c0-4.8-1.8-9.4-3.6-12.8     c-0.4-0.7-1.4-0.4-1.3,0.2C75.1,56.7,66.9,65.7,66.9,54.5z"></path>
                        <g>
                            <path class="st0" d="M46.2,94.8c-0.4,0-0.9-0.2-1.2-0.5L0.5,49.8c-0.6-0.6-0.6-1.7,0-2.4l47-47C47.8,0.2,48.2,0,48.6,0h13.5         c0.8,0,1.3,0.5,1.5,1c0.2,0.5,0.2,1.2-0.4,1.8L19.1,47c-0.9,0.9-0.9,2.3,0,3.2L54,85.2c0.6,0.6,0.6,1.7,0,2.4l-6.7,6.8         C47,94.6,46.6,94.8,46.2,94.8z"></path>
                        </g>
                        <g>
                            <path class="st0" d="M55.1,102.6c-0.8,0-1.3-0.5-1.5-1c-0.2-0.5-0.2-1.2,0.4-1.8l44.2-44.2c0.4-0.4,0.7-1,0.7-1.6         c0-0.6-0.2-1.2-0.7-1.6L63.2,17.4c-0.6-0.6-0.6-1.7,0-2.4l6.8-6.8c0.3-0.3,0.7-0.5,1.2-0.5S72,8,72.3,8.3l44.4,44.5         c0.3,0.3,0.5,0.7,0.5,1.2s-0.2,0.9-0.5,1.2l-47,47c-0.3,0.3-0.7,0.5-1.2,0.5H55.1z"></path>
                        </g>
                        <g>
                            <g>
                                <path class="st1" d="M167.2,32c-0.2,0.4-0.5,0.6-1,0.6c-0.3,0-0.7-0.2-1.2-0.7c-0.5-0.5-1.2-1-2-1.5c-0.9-0.6-1.9-1.1-3.2-1.5             c-1.3-0.5-2.9-0.7-4.8-0.7c-1.9,0-3.5,0.3-5,0.8c-1.4,0.5-2.6,1.3-3.6,2.2s-1.7,2-2.2,3.2c-0.5,1.2-0.8,2.5-0.8,3.8             c0,1.8,0.4,3.2,1.1,4.4c0.7,1.1,1.7,2.1,3,2.9c1.2,0.8,2.6,1.5,4.2,2c1.6,0.6,3.2,1.1,4.8,1.6c1.6,0.5,3.2,1.1,4.8,1.8             c1.6,0.6,2.9,1.5,4.2,2.4s2.2,2.2,3,3.6c0.7,1.4,1.1,3.2,1.1,5.3c0,2.2-0.4,4.2-1.1,6.1c-0.7,1.9-1.8,3.6-3.2,5             c-1.4,1.4-3.2,2.5-5.2,3.4c-2.1,0.8-4.4,1.2-7,1.2c-3.4,0-6.4-0.6-8.8-1.8c-2.5-1.2-4.6-2.9-6.5-5l1-1.6c0.3-0.4,0.6-0.5,1-0.5             c0.2,0,0.5,0.1,0.8,0.4c0.3,0.3,0.8,0.7,1.2,1.1c0.5,0.4,1.1,0.9,1.8,1.4c0.7,0.5,1.5,1,2.4,1.4c0.9,0.4,1.9,0.8,3.1,1.1             c1.2,0.3,2.5,0.4,4,0.4c2.1,0,3.9-0.3,5.5-0.9c1.6-0.6,3-1.5,4.1-2.5s2-2.4,2.6-3.8c0.6-1.5,0.9-3.1,0.9-4.7             c0-1.8-0.4-3.3-1.1-4.5c-0.7-1.2-1.7-2.2-3-3c-1.2-0.8-2.6-1.5-4.2-2c-1.6-0.5-3.2-1.1-4.8-1.6c-1.6-0.5-3.2-1.1-4.8-1.7             c-1.6-0.6-2.9-1.4-4.2-2.4c-1.2-1-2.2-2.2-3-3.7c-0.7-1.5-1.1-3.3-1.1-5.6c0-1.7,0.3-3.4,1-5c0.7-1.6,1.6-3,2.9-4.3             c1.3-1.2,2.8-2.2,4.7-3c1.9-0.7,4-1.1,6.4-1.1c2.7,0,5.1,0.4,7.3,1.3c2.1,0.9,4.1,2.2,5.9,3.9L167.2,32z"></path>
                                <path class="st2" d="M152.9,78.8c-3.5,0-6.6-0.6-9.1-1.9c-2.5-1.2-4.8-3-6.7-5.1l-0.3-0.3l1.3-2c0.6-0.7,1.1-0.8,1.5-0.8             c0.4,0,0.8,0.2,1.2,0.6c0.3,0.3,0.8,0.7,1.3,1.1c0.5,0.4,1.1,0.9,1.7,1.4c0.7,0.5,1.4,0.9,2.3,1.3c0.9,0.4,1.9,0.8,3,1             c1.1,0.3,2.4,0.4,3.9,0.4c2,0,3.8-0.3,5.3-0.9c1.5-0.6,2.8-1.4,3.9-2.4c1-1,1.9-2.2,2.4-3.6c0.6-1.4,0.8-2.9,0.8-4.5             c0-1.7-0.3-3.1-1-4.2c-0.7-1.1-1.6-2-2.8-2.8c-1.2-0.8-2.5-1.4-4-1.9c-1.5-0.5-3.1-1.1-4.8-1.6c-1.7-0.5-3.3-1.1-4.8-1.7             c-1.6-0.7-3.1-1.5-4.3-2.5c-1.3-1-2.3-2.4-3.1-3.9c-0.8-1.6-1.2-3.5-1.2-5.8c0-1.8,0.3-3.6,1-5.3c0.7-1.7,1.7-3.2,3-4.5             c1.3-1.3,3-2.3,4.9-3.1c1.9-0.8,4.2-1.2,6.6-1.2c2.8,0,5.3,0.4,7.5,1.3c2.2,0.9,4.2,2.3,6.1,4.1l0.3,0.3l-1.1,2.1             c-0.6,1.1-1.7,1.4-3.1,0.1c-0.5-0.4-1.1-0.9-2-1.4c-0.8-0.5-1.9-1-3.1-1.5c-1.2-0.4-2.7-0.7-4.6-0.7c-1.8,0-3.4,0.3-4.8,0.8             c-1.3,0.5-2.5,1.2-3.4,2.1c-0.9,0.9-1.6,1.9-2.1,3c-0.5,1.1-0.7,2.4-0.7,3.6c0,1.6,0.3,3,1,4c0.7,1.1,1.6,2,2.8,2.8             c1.2,0.8,2.5,1.4,4,2c1.5,0.5,3.1,1.1,4.8,1.6c1.6,0.5,3.3,1.1,4.8,1.8c1.6,0.7,3.1,1.5,4.3,2.5c1.3,1,2.3,2.3,3.1,3.8             c0.8,1.5,1.2,3.4,1.2,5.6c0,2.2-0.4,4.4-1.2,6.4c-0.8,2-1.9,3.7-3.4,5.2c-1.5,1.5-3.3,2.6-5.4,3.5             C158.1,78.3,155.6,78.8,152.9,78.8z M138.4,71.3c1.7,1.9,3.7,3.4,6,4.5c2.4,1.2,5.3,1.8,8.6,1.8c2.5,0,4.8-0.4,6.8-1.2             c2-0.8,3.6-1.9,5-3.2c1.3-1.3,2.4-3,3.1-4.8c0.7-1.8,1.1-3.8,1.1-5.9c0-2-0.4-3.7-1-5.1c-0.7-1.3-1.6-2.5-2.8-3.4             c-1.2-0.9-2.5-1.7-4-2.4c-1.5-0.6-3.1-1.2-4.7-1.8c-1.6-0.5-3.2-1.1-4.8-1.6c-1.6-0.6-3-1.3-4.3-2.1c-1.3-0.8-2.3-1.9-3.1-3.1             c-0.8-1.2-1.2-2.8-1.2-4.7c0-1.4,0.3-2.8,0.8-4.1c0.5-1.3,1.3-2.5,2.3-3.4c1-1,2.3-1.8,3.8-2.3c1.5-0.6,3.3-0.8,5.2-0.8             c1.9,0,3.6,0.2,5,0.7c1.3,0.5,2.5,1,3.3,1.6c0.9,0.6,1.6,1.1,2.1,1.6c0.6,0.5,0.8,0.5,0.8,0.5c0.1,0,0.3,0,0.4-0.3l0.7-1.3             c-1.6-1.5-3.4-2.7-5.3-3.5c-2.1-0.8-4.4-1.2-7-1.2c-2.3,0-4.4,0.4-6.2,1.1c-1.8,0.7-3.3,1.7-4.5,2.8c-1.2,1.2-2.1,2.5-2.8,4.1             c-0.6,1.5-0.9,3.1-0.9,4.8c0,2.1,0.4,3.9,1.1,5.3c0.7,1.4,1.6,2.6,2.8,3.5c1.2,0.9,2.5,1.7,4,2.3c1.5,0.6,3.1,1.2,4.7,1.7             c1.6,0.5,3.2,1,4.8,1.6c1.6,0.6,3,1.2,4.3,2.1c1.3,0.8,2.4,1.9,3.1,3.2c0.8,1.3,1.2,2.9,1.2,4.9c0,1.8-0.3,3.4-0.9,5             c-0.6,1.6-1.5,2.9-2.7,4c-1.2,1.1-2.6,2-4.3,2.7c-1.7,0.6-3.6,1-5.7,1c-1.5,0-2.9-0.2-4.2-0.5c-1.2-0.3-2.3-0.7-3.2-1.1             c-0.9-0.4-1.8-0.9-2.5-1.5c-0.7-0.5-1.3-1-1.8-1.4c-0.5-0.4-0.9-0.8-1.2-1.1c-0.3-0.3-0.5-0.3-0.5-0.3c-0.1,0-0.3,0-0.5,0.3             L138.4,71.3z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M226.7,51.6c0,4-0.6,7.6-1.8,10.9c-1.2,3.3-2.9,6.1-5.1,8.4c-2.2,2.3-4.8,4.1-7.8,5.4             c-3,1.3-6.4,1.9-10.1,1.9c-3.6,0-7-0.6-10-1.9c-3-1.3-5.6-3-7.8-5.4c-2.2-2.3-3.9-5.1-5.1-8.4c-1.2-3.3-1.8-6.9-1.8-10.9             c0-4,0.6-7.6,1.8-10.9c1.2-3.3,2.9-6.1,5.1-8.4c2.2-2.3,4.8-4.1,7.8-5.4c3-1.3,6.4-1.9,10-1.9c3.7,0,7.1,0.6,10.1,1.9             c3,1.3,5.6,3,7.8,5.4c2.2,2.3,3.9,5.1,5.1,8.4C226.1,44,226.7,47.6,226.7,51.6z M222.8,51.6c0-3.6-0.5-6.9-1.5-9.8             c-1-2.9-2.4-5.3-4.2-7.3c-1.8-2-4-3.5-6.6-4.6c-2.6-1.1-5.4-1.6-8.5-1.6c-3.1,0-5.9,0.5-8.5,1.6c-2.6,1.1-4.8,2.6-6.6,4.6             c-1.8,2-3.3,4.4-4.3,7.3c-1,2.9-1.5,6.1-1.5,9.8c0,3.6,0.5,6.9,1.5,9.8c1,2.9,2.4,5.3,4.3,7.3c1.8,2,4,3.5,6.6,4.6             c2.6,1.1,5.4,1.6,8.5,1.6c3.1,0,6-0.5,8.5-1.6c2.6-1,4.8-2.6,6.6-4.6c1.8-2,3.2-4.4,4.2-7.3C222.3,58.5,222.8,55.3,222.8,51.6z"></path>
                                <path class="st2" d="M202,78.7c-3.7,0-7.2-0.7-10.2-1.9c-3.1-1.3-5.8-3.1-8-5.5c-2.2-2.4-4-5.2-5.2-8.6c-1.2-3.3-1.9-7.1-1.9-11.1             c0-4,0.6-7.8,1.9-11.1c1.2-3.3,3-6.2,5.2-8.6c2.2-2.4,4.9-4.2,8-5.5c3.1-1.3,6.5-2,10.2-2c3.8,0,7.2,0.7,10.3,1.9             c3.1,1.3,5.8,3.1,8,5.5c2.2,2.4,4,5.3,5.2,8.6c1.2,3.3,1.8,7,1.8,11.1c0,4.1-0.6,7.8-1.8,11.1c-1.2,3.3-3,6.2-5.2,8.6             c-2.2,2.4-4.9,4.2-8,5.5C209.2,78.1,205.7,78.7,202,78.7z M202,25.7c-3.5,0-6.8,0.6-9.8,1.9c-2.9,1.2-5.5,3-7.6,5.2             c-2.1,2.2-3.8,5-4.9,8.2c-1.2,3.2-1.8,6.8-1.8,10.7c0,3.9,0.6,7.5,1.8,10.7c1.2,3.2,2.8,5.9,4.9,8.2c2.1,2.2,4.7,4,7.6,5.2             c2.9,1.2,6.2,1.8,9.8,1.8c3.6,0,6.9-0.6,9.8-1.8c2.9-1.2,5.5-3,7.6-5.2c2.1-2.2,3.8-5,4.9-8.1c1.2-3.2,1.8-6.8,1.8-10.7             c0-3.9-0.6-7.5-1.8-10.7c-1.2-3.2-2.8-5.9-4.9-8.2c-2.1-2.2-4.7-4-7.6-5.2C208.9,26.3,205.6,25.7,202,25.7z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M256.4,74.9c2.5,0,4.7-0.4,6.7-1.3c2-0.9,3.6-2.1,5-3.6c1.4-1.5,2.4-3.4,3.1-5.4c0.7-2.1,1.1-4.3,1.1-6.8             V25.7h3.7v32.1c0,2.9-0.5,5.5-1.4,8c-0.9,2.5-2.2,4.6-3.9,6.5c-1.7,1.8-3.8,3.3-6.2,4.3c-2.4,1-5.2,1.6-8.2,1.6             c-3,0-5.8-0.5-8.2-1.6c-2.4-1.1-4.5-2.5-6.2-4.3c-1.7-1.8-3-4-3.9-6.5c-0.9-2.5-1.4-5.2-1.4-8V25.7h3.8v32c0,2.4,0.4,4.7,1.1,6.8             c0.7,2.1,1.8,3.9,3.1,5.4c1.4,1.5,3,2.7,5,3.6C251.6,74.5,253.9,74.9,256.4,74.9z"></path>
                                <path class="st2" d="M256.4,78.8c-3.1,0-5.9-0.5-8.4-1.6c-2.5-1.1-4.7-2.6-6.4-4.5c-1.7-1.9-3.1-4.2-4-6.7             c-0.9-2.5-1.4-5.3-1.4-8.2V25.1h5v32.7c0,2.3,0.4,4.5,1,6.6c0.7,2,1.7,3.8,3,5.2c1.3,1.5,2.9,2.6,4.8,3.5c1.9,0.8,4,1.3,6.4,1.3             c2.4,0,4.6-0.4,6.4-1.2c1.9-0.8,3.5-2,4.8-3.5c1.3-1.5,2.3-3.2,3-5.2c0.7-2,1-4.2,1-6.6V25.1h5v32.7c0,2.9-0.5,5.7-1.4,8.2             c-0.9,2.5-2.3,4.8-4,6.7c-1.7,1.9-3.9,3.4-6.4,4.5C262.3,78.3,259.5,78.8,256.4,78.8z M237.3,26.3v31.5c0,2.8,0.4,5.4,1.3,7.8             c0.9,2.4,2.1,4.5,3.8,6.3c1.6,1.8,3.6,3.2,6,4.2c2.3,1,5,1.5,8,1.5c2.9,0,5.6-0.5,8-1.5c2.3-1,4.4-2.4,6-4.2             c1.6-1.8,2.9-3.9,3.8-6.3c0.9-2.4,1.3-5,1.3-7.8V26.3h-2.5v31.5c0,2.5-0.4,4.8-1.1,7c-0.7,2.2-1.8,4.1-3.3,5.7             c-1.4,1.6-3.2,2.9-5.2,3.8c-2,0.9-4.4,1.4-6.9,1.4c-2.6,0-4.9-0.5-6.9-1.4c-2-0.9-3.8-2.2-5.2-3.8c-1.4-1.6-2.5-3.5-3.2-5.7             c-0.7-2.1-1.1-4.5-1.1-7V26.3H237.3z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M297.5,51.3c1,0,0.9,0,0.9,0l2.2,0c2.3,0,4.4-0.3,6.2-0.8c1.8-0.6,3.4-1.3,4.6-2.4c1.3-1,2.2-2.3,2.9-3.7             c0.7-1.4,1-3.1,1-4.9c0-3.7-1.2-6.4-3.6-8.2c-2.4-1.8-5.9-2.7-10.6-2.7h-9.5v22.7v2.8v23.5h-3.7V25.7h13.2c6,0,10.5,1.2,13.4,3.5             c3,2.3,4.4,5.7,4.4,10.2c0,2-0.3,3.8-1,5.4c-0.7,1.6-1.7,3.1-3,4.3c-1.3,1.2-2.8,2.3-4.6,3c-1.8,0.8-3.9,1.3-6.1,1.6             c0.6,0.4,1.1,0.9,1.6,1.5l17.9,22.4h-3.3c-0.4,0-0.7-0.1-1-0.2c-0.3-0.1-0.6-0.4-0.8-0.7l-16.6-21c-0.4-0.5-0.9-0.9-1.3-1.1             c-0.5-0.2-3.4-0.3-4.4-0.3C296.3,51.6,296.7,51.3,297.5,51.3z"></path>
                                <path class="st2" d="M325,78.2h-4.5c-0.5,0-0.9-0.1-1.3-0.3c-0.4-0.2-0.7-0.5-1-0.9l-16.6-21c-0.4-0.5-0.7-0.8-1.1-1             c-0.4-0.1-2.8-0.3-4.1-0.3h-0.6v-2.6c0-0.9,0.2-1.4,1.8-1.4c0.9,0,1,0,1,0l2.2,0c2.2,0,4.2-0.3,6-0.8c1.7-0.5,3.2-1.3,4.4-2.3             c1.2-1,2.1-2.1,2.7-3.5c0.6-1.4,0.9-2.9,0.9-4.6c0-3.5-1.1-6-3.4-7.7c-2.3-1.7-5.7-2.6-10.2-2.6h-8.9v48.9h-5V25.1h13.9             c6.1,0,10.7,1.2,13.8,3.6c3.1,2.4,4.7,6,4.7,10.7c0,2.1-0.4,4-1.1,5.7c-0.7,1.7-1.8,3.2-3.1,4.5c-1.3,1.3-3,2.3-4.8,3.2             c-1.5,0.6-3.1,1.1-4.9,1.4c0.2,0.2,0.4,0.4,0.6,0.7L325,78.2z M296.9,53.5c1.1,0,3.4,0.1,4,0.4c0.6,0.3,1.1,0.7,1.6,1.3l16.6,21             c0.2,0.3,0.4,0.5,0.6,0.6c0.2,0.1,0.4,0.2,0.7,0.2h2l-17.1-21.4c-0.4-0.6-0.9-1-1.4-1.3l-1.5-0.9l1.8-0.2c2.2-0.2,4.2-0.7,5.9-1.5             c1.7-0.8,3.2-1.7,4.5-2.9c1.2-1.2,2.2-2.5,2.8-4.1c0.6-1.6,1-3.3,1-5.2c0-4.3-1.4-7.5-4.2-9.7c-2.8-2.2-7.2-3.3-13-3.3h-12.6V77             h2.5V28h10.1c4.7,0,8.4,0.9,10.9,2.8c2.6,1.9,3.9,4.8,3.9,8.7c0,1.9-0.4,3.6-1,5.1c-0.7,1.5-1.7,2.8-3.1,3.9             c-1.3,1.1-2.9,1.9-4.8,2.5c-1.9,0.6-4,0.9-6.4,0.9l-2.2,0c-0.1,0-0.2,0-0.9,0C297.3,51.9,297,51.9,296.9,53.5z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M367.6,68.8c0.2,0,0.5,0.1,0.6,0.3l1.5,1.6c-1.1,1.1-2.2,2.2-3.5,3.1c-1.3,0.9-2.7,1.7-4.2,2.3             c-1.5,0.6-3.2,1.1-4.9,1.5c-1.8,0.4-3.8,0.5-5.9,0.5c-3.6,0-6.9-0.6-9.9-1.9c-3-1.3-5.6-3-7.7-5.4c-2.1-2.3-3.8-5.1-5-8.4             c-1.2-3.3-1.8-6.9-1.8-10.9c0-3.9,0.6-7.5,1.9-10.8c1.2-3.3,3-6,5.2-8.4c2.2-2.3,4.9-4.1,8-5.4c3.1-1.3,6.6-1.9,10.3-1.9             c1.9,0,3.6,0.1,5.2,0.4c1.6,0.3,3,0.7,4.4,1.2c1.4,0.5,2.6,1.2,3.8,2c1.2,0.8,2.4,1.7,3.5,2.7l-1.1,1.6c-0.2,0.3-0.5,0.4-0.9,0.4             c-0.2,0-0.5-0.1-0.8-0.4c-0.3-0.3-0.8-0.6-1.3-1c-0.5-0.4-1.2-0.8-1.9-1.2c-0.7-0.5-1.6-0.9-2.7-1.2c-1-0.4-2.2-0.7-3.6-1             c-1.3-0.3-2.9-0.4-4.6-0.4c-3.2,0-6.1,0.5-8.7,1.6c-2.6,1.1-4.9,2.6-6.8,4.7c-1.9,2-3.4,4.5-4.5,7.3s-1.6,6.1-1.6,9.7             c0,3.7,0.5,6.9,1.6,9.8c1.1,2.9,2.5,5.3,4.4,7.3c1.9,2,4.1,3.5,6.6,4.6c2.5,1.1,5.3,1.6,8.2,1.6c1.9,0,3.5-0.1,5-0.4             c1.5-0.2,2.8-0.6,4-1.1c1.2-0.5,2.4-1.1,3.4-1.8c1.1-0.7,2.1-1.5,3.1-2.5c0.1-0.1,0.2-0.2,0.3-0.2             C367.3,68.9,367.5,68.8,367.6,68.8z"></path>
                                <path class="st2" d="M351.1,78.8c-3.7,0-7.1-0.7-10.1-1.9c-3.1-1.3-5.7-3.1-7.9-5.5c-2.2-2.4-3.9-5.2-5.1-8.6             c-1.2-3.3-1.8-7.1-1.8-11.1c0-4,0.6-7.7,1.9-11c1.3-3.3,3.1-6.2,5.3-8.6c2.3-2.4,5.1-4.3,8.2-5.6c3.2-1.3,6.7-2,10.6-2             c1.9,0,3.7,0.1,5.3,0.4c1.6,0.3,3.1,0.7,4.5,1.2c1.4,0.5,2.7,1.2,3.9,2c1.2,0.8,2.4,1.7,3.6,2.8l0.4,0.4l-1.4,2.1             c-0.2,0.3-0.6,0.7-1.4,0.7c-0.4,0-0.7-0.2-1.2-0.5c-0.3-0.3-0.8-0.6-1.3-0.9c-0.5-0.4-1.1-0.8-1.9-1.2c-0.7-0.4-1.6-0.8-2.6-1.2             c-1-0.4-2.2-0.7-3.5-0.9c-1.3-0.2-2.8-0.4-4.5-0.4c-3.1,0-5.9,0.5-8.5,1.6c-2.5,1.1-4.8,2.6-6.6,4.5c-1.8,1.9-3.3,4.3-4.3,7.1             c-1,2.8-1.6,6-1.6,9.4c0,3.6,0.5,6.8,1.5,9.6c1,2.8,2.4,5.2,4.2,7.1c1.8,1.9,3.9,3.4,6.4,4.4c2.4,1,5.1,1.5,8,1.5             c1.8,0,3.5-0.1,4.9-0.4c1.4-0.2,2.7-0.6,3.9-1.1c1.2-0.5,2.3-1.1,3.3-1.7c1-0.7,2-1.5,3-2.4c0.2-0.2,0.3-0.2,0.5-0.3             c0.5-0.3,1.3-0.2,1.7,0.3l1.9,2l-0.4,0.4c-1.1,1.2-2.3,2.2-3.6,3.2c-1.3,0.9-2.7,1.8-4.3,2.4c-1.5,0.7-3.2,1.2-5.1,1.5             C355.3,78.6,353.3,78.8,351.1,78.8z M352.2,25.7c-3.7,0-7.1,0.6-10.1,1.9c-3,1.2-5.7,3-7.8,5.3c-2.2,2.3-3.9,5-5.1,8.2             c-1.2,3.2-1.8,6.7-1.8,10.6c0,3.9,0.6,7.5,1.8,10.7c1.2,3.2,2.8,5.9,4.9,8.2c2.1,2.2,4.6,4,7.5,5.2c2.9,1.2,6.1,1.8,9.6,1.8             c2.1,0,4-0.2,5.8-0.5c1.7-0.3,3.4-0.8,4.8-1.5c1.5-0.6,2.8-1.4,4-2.3c1.1-0.8,2.1-1.7,3-2.6l-1.1-1.2c-0.1-0.1-0.2-0.1-0.3,0             c-0.1,0-0.2,0.1-0.3,0.2c-1,0.9-2.1,1.8-3.2,2.5c-1.1,0.7-2.3,1.4-3.5,1.9c-1.3,0.5-2.7,0.9-4.1,1.1c-1.5,0.2-3.2,0.4-5.1,0.4             c-3,0-5.9-0.6-8.5-1.6c-2.6-1.1-4.9-2.7-6.8-4.7c-1.9-2-3.4-4.6-4.5-7.5c-1.1-2.9-1.6-6.3-1.6-10c0-3.6,0.5-6.9,1.6-9.9             c1.1-2.9,2.6-5.5,4.6-7.5c2-2.1,4.3-3.7,7-4.8c2.7-1.1,5.7-1.7,8.9-1.7c1.7,0,3.3,0.1,4.7,0.4c1.4,0.3,2.6,0.6,3.7,1             c1.1,0.4,2,0.8,2.8,1.3c0.8,0.5,1.4,0.9,1.9,1.3c0.5,0.4,1,0.7,1.3,1c0.3,0.3,0.5,0.3,0.5,0.3c0.3,0,0.4-0.1,0.4-0.2l0.8-1.2             c-1-0.9-2-1.6-3-2.3c-1.2-0.8-2.4-1.4-3.7-1.9c-1.3-0.5-2.8-0.9-4.3-1.2C355.7,25.9,354,25.7,352.2,25.7z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M410.3,25.7v3.1H383v21h22.7v3H383v21.6h27.3v3.1h-31.1V25.7H410.3z"></path>
                                <path class="st2" d="M410.9,78.2h-32.3V25.1h32.3v4.3h-27.3v19.7h22.7v4.3h-22.7v20.4h27.3V78.2z M379.8,77h29.9v-1.9h-27.3V52.2             h22.7v-1.8h-22.7V28.2h27.3v-1.9h-29.9V77z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M456.8,25.1V33h-23.5v15.7h19.8v7.9h-19.8v21.6h-9.9v-53H456.8z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M514.3,51.6c0,3.9-0.6,7.5-1.9,10.8c-1.3,3.3-3.1,6.2-5.5,8.6c-2.3,2.4-5.2,4.3-8.5,5.7c-3.3,1.4-7,2-11,2             c-4,0-7.7-0.7-11-2c-3.3-1.4-6.1-3.2-8.5-5.7c-2.4-2.4-4.2-5.3-5.5-8.6s-1.9-6.9-1.9-10.8s0.6-7.5,1.9-10.8             c1.3-3.3,3.1-6.2,5.5-8.6c2.4-2.4,5.2-4.3,8.5-5.7c3.3-1.4,7-2,11-2c4,0,7.7,0.7,11,2.1c3.3,1.4,6.1,3.3,8.5,5.7             c2.3,2.4,4.2,5.3,5.5,8.6C513.6,44.1,514.3,47.7,514.3,51.6z M504.2,51.6c0-2.9-0.4-5.5-1.2-7.8c-0.8-2.3-1.9-4.3-3.3-5.9             c-1.4-1.6-3.2-2.8-5.3-3.7c-2.1-0.9-4.4-1.3-7-1.3c-2.6,0-4.9,0.4-7,1.3c-2.1,0.9-3.8,2.1-5.3,3.7c-1.5,1.6-2.6,3.6-3.4,5.9             c-0.8,2.3-1.2,4.9-1.2,7.8s0.4,5.5,1.2,7.8c0.8,2.3,1.9,4.3,3.4,5.9c1.5,1.6,3.2,2.8,5.3,3.7c2.1,0.9,4.4,1.3,7,1.3             c2.6,0,4.9-0.4,7-1.3c2.1-0.9,3.8-2.1,5.3-3.7c1.4-1.6,2.5-3.6,3.3-5.9C503.8,57.1,504.2,54.5,504.2,51.6z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M534.9,50.4l2.3,0c1.9,0,3.5-0.2,4.9-0.7c1.4-0.5,2.5-1.1,3.4-1.9c0.9-0.8,1.6-1.8,2-2.9             c0.4-1.1,0.7-2.4,0.7-3.7c0-2.7-0.9-4.8-2.7-6.2c-1.8-1.4-4.5-2.2-8.1-2.2H531v17.6v7.1v20.7h-9.9v-53h16.2c3.6,0,6.7,0.4,9.3,1.1             c2.6,0.7,4.7,1.8,6.3,3.1c1.6,1.3,2.9,3,3.6,4.8c0.8,1.9,1.2,3.9,1.2,6.2c0,1.8-0.3,3.5-0.8,5.1c-0.5,1.6-1.3,3-2.3,4.3             c-1,1.3-2.2,2.4-3.7,3.4c-1.5,1-3.1,1.8-5,2.3c1.2,0.7,2.3,1.7,3.2,3l13.3,19.6h-8.9c-0.9,0-1.6-0.2-2.2-0.5             c-0.6-0.3-1.1-0.8-1.5-1.5c0,0-11.1-17-11.1-17c-0.3-0.4-0.9-1.3-1.5-1.4c-1.2,0-2.4,0-3.5,0c0,0,0-6,0-6.4             C533.8,50.4,534.9,50.4,534.9,50.4z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M591.4,70.9c2.2,0,4.2-0.2,5.8-0.6c1.6-0.4,3.2-1,4.7-1.7v-12h-6.6c-0.6,0-1.1-0.2-1.5-0.5             c-0.4-0.4-0.6-0.8-0.6-1.3v-5.6h17.6V73c-1.3,1-2.7,1.8-4.2,2.5c-1.5,0.7-3,1.3-4.7,1.8c-1.7,0.5-3.4,0.8-5.3,1             c-1.9,0.2-3.9,0.3-6.1,0.3c-3.9,0-7.4-0.7-10.7-2c-3.3-1.3-6.1-3.2-8.4-5.6c-2.4-2.4-4.2-5.3-5.6-8.6c-1.3-3.3-2-7-2-10.9             c0-4,0.6-7.6,1.9-11c1.3-3.3,3.1-6.2,5.5-8.6c2.4-2.4,5.3-4.3,8.7-5.6c3.4-1.3,7.2-2,11.4-2c4.3,0,8.1,0.6,11.2,1.9             c3.2,1.3,5.8,3,8,5l-2.9,4.5c-0.6,0.9-1.3,1.4-2.2,1.4c-0.6,0-1.2-0.2-1.8-0.6c-0.8-0.5-1.6-0.9-2.4-1.4c-0.8-0.5-1.7-0.9-2.7-1.2             c-1-0.3-2.1-0.6-3.3-0.8c-1.2-0.2-2.7-0.3-4.3-0.3c-2.6,0-5,0.4-7.1,1.3c-2.1,0.9-3.9,2.1-5.4,3.8c-1.5,1.6-2.6,3.6-3.4,5.9             c-0.8,2.3-1.2,4.9-1.2,7.7c0,3.1,0.4,5.8,1.3,8.2c0.9,2.4,2.1,4.4,3.6,6s3.4,2.9,5.5,3.8S588.9,70.9,591.4,70.9z"></path>
                            </g>
                            <g>
                                <path class="st1" d="M645.7,56.8h-16.1v13.4H653v7.9h-33.4v-53H653V33h-23.5v16.3H648v5.8C648,55.1,647.9,56.8,645.7,56.8z"></path>
                            </g>
                        </g>
                        </svg></a>
                        <a href="//www.softwaresuggest.com/website-speedy" aria-label="softwaresuggest" target="_blank"><img src="//websitespeedycdn.b-cdn.net/speedyweb/images/software-suggest_1.webp" alt="Software Suggest logo" class="software-suggest_1"></a>
                    </div>
                </div>
        </div>
            
        </div>
    </div>
  </div>
</div>



<script>
function show(e) {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
    e.classList.add('swap');
  } else {
    x.type = "password";
    e.classList.remove('swap')
  }
}
</script>


<script>

	$(document).ready(function(){
		$("#resend-code").click(function(){
			var href = $(this).attr("href");
			window.location.href = href;
		});
	});

 
</script>

<script type="text/javascript">
	var target_date = new Date().getTime() + (1000 * <?= $nextFive - $current_time ?>);
	var days, hours, minutes, seconds; // variables for time units

	var countdown = document.getElementById("countdown"); // get tag element

	getCountdown();

	var counterInterval = setInterval(function() {
		getCountdown();
	}, 1000);

	function getCountdown() {

		// find the amount of "seconds" between now and target
		var current_date = new Date().getTime();
		var seconds_left = (target_date - current_date) / 1000;

		days = pad(parseInt(seconds_left / 86400));
		seconds_left = seconds_left % 86400;

		hours = pad(parseInt(seconds_left / 3600));
		seconds_left = seconds_left % 3600;

		minutes = pad(parseInt(seconds_left / 60));
		seconds = pad(parseInt(seconds_left % 60));
		console.log(minutes);
		console.log(seconds);

		// format countdown string + set tag value
		// console.log("days : " + days + " hours : " + hours + " minutes : " + minutes + " seconds : " + seconds ) ;
		countdown.innerHTML = minutes + ":" + seconds;
		// $("#resend-code").html(minutes + ":" + seconds);

		if ((parseInt(minutes) <= 0) && (parseInt(seconds) <= 0)) {
			clearInterval(counterInterval);
			// $("#resend-code").html("Resend Code");
			// $("#resend-code").removeClass("d-none");
			$("#resend-code").attr("disabled",false);

			var otp = $("#confirm-email-form").data("otp");

			// $.ajax({
			// 	url: "inc/expire-otp.php",
			// 	method: "POST",
			// 	dataType: "JSON",
			// 	data: {
			// 		otp: otp
			// 	}
			// }).done(function(reponse) {});

		}
	}

	function pad(n) {
		return (n < 10 ? '0' : '') + n;
	}
</script>

<script>
	


// When the user clicks on the password field, show the message box

  document.getElementById("message").style.display = "block";


// When the user clicks outside of the password field, hide the message box
setInterval(abc, 1000);

// When the user starts to type something inside the password field
function abc() {
var myInput = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>


<script>
    (function($) {

$.fn.niceSelect = function(method) {

    // Methods
    if (typeof method == 'string') {
        if (method == 'update') {
            this.each(function() {
                var $select = $(this);
                var $dropdown = $(this).next('.nice-select');
                var open = $dropdown.hasClass('open');

                if ($dropdown.length) {
                    $dropdown.remove();
                    create_nice_select($select);

                    if (open) {
                        $select.next().trigger('click');
                    }
                }
            });
        } else if (method == 'destroy') {
            this.each(function() {
                var $select = $(this);
                var $dropdown = $(this).next('.nice-select');

                if ($dropdown.length) {
                    $dropdown.remove();
                    $select.css('display', '');
                }
            });
            if ($('.nice-select').length == 0) {
                $(document).off('.nice_select');
            }
        } else {
            console.log('Method "' + method + '" does not exist.')
        }
        return this;
    }

    // Hide native select
    this.hide();

    // Create custom markup
    this.each(function() {
        var $select = $(this);

        if (!$select.next().hasClass('nice-select')) {
            create_nice_select($select);
        }
    });

    function create_nice_select($select) {
        $select.after($('<div></div>')
            .addClass('nice-select')
            .addClass($select.attr('class') || '')
            .addClass($select.attr('disabled') ? 'disabled' : '')
            .addClass($select.attr('multiple') ? 'has-multiple' : '')
            .attr('tabindex', $select.attr('disabled') ? null : '0')
            .html($select.attr('multiple') ? '<span class="multiple-options"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Search"/></div><ul class="list"></ul>' : '<span class="current"></span><div class="nice-select-search-box"><input type="text" class="nice-select-search" placeholder="Search"/></div><ul class="list"></ul>')
        );

        var $dropdown = $select.next();
        var $options = $select.find('option');
        if ($select.attr('multiple')) {
            var $selected = $select.find('option:selected');
            var $selected_html = '';
            $selected.each(function() {
                $selected_option = $(this);
                $selected_text = $selected_option.data('display') ||  $selected_option.text();

                if (!$selected_option.val()) {
                    return;
                }

                $selected_html += '<span class="current">' + $selected_text + '</span>';
            });
            $select_placeholder = $select.data('js-placeholder') || $select.attr('js-placeholder');
            $select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
            $selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
            $dropdown.find('.multiple-options').html($selected_html);
        } else {
            var $selected = $select.find('option:selected');
            $dropdown.find('.current').html($selected.data('display') ||  $selected.text());
        }


        $options.each(function(i) {
            var $option = $(this);
            var display = $option.data('display');

            $dropdown.find('ul').append($('<li></li>')
                .attr('data-value', $option.val())
                .attr('data-display', (display || null))
                .addClass('option' +
                    ($option.is(':selected') ? ' selected' : '') +
                    ($option.is(':disabled') ? ' disabled' : ''))
                .html($option.text())
            );
        });
    }

    /* Event listeners */

    // Unbind existing events in case that the plugin has been initialized before
    $(document).off('.nice_select');

    // Open/close
    $(document).on('click.nice_select', '.nice-select', function(event) {
        var $dropdown = $(this);

        $('.nice-select').not($dropdown).removeClass('open');
        $dropdown.toggleClass('open');

        if ($dropdown.hasClass('open')) {
            $dropdown.find('.option');
            $dropdown.find('.nice-select-search').val('');
            $dropdown.find('.nice-select-search').focus();
            $dropdown.find('.focus').removeClass('focus');
            $dropdown.find('.selected').addClass('focus');
            $dropdown.find('ul li').show();
        } else {
            $dropdown.focus();
        }
    });

    $(document).on('click', '.nice-select-search-box', function(event) {
        event.stopPropagation();
        return false;
    });
    $(document).on('keyup.nice-select-search', '.nice-select', function() {
        var $self = $(this);
        var $text = $self.find('.nice-select-search').val();
        var $options = $self.find('ul li');
        if ($text == '')
            $options.show();
        else if ($self.hasClass('open')) {
            $text = $text.toLowerCase();
            var $matchReg = new RegExp($text);
            if (0 < $options.length) {
                $options.each(function() {
                    var $this = $(this);
                    var $optionText = $this.text().toLowerCase();
                    var $matchCheck = $matchReg.test($optionText);
                    $matchCheck ? $this.show() : $this.hide();
                })
            } else {
                $options.show();
            }
        }
        $self.find('.option'),
            $self.find('.focus').removeClass('focus'),
            $self.find('.selected').addClass('focus');
    });

    // Close when clicking outside
    $(document).on('click.nice_select', function(event) {
        if ($(event.target).closest('.nice-select').length === 0) {
            $('.nice-select').removeClass('open').find('.option');
        }
    });

    // Option click
    $(document).on('click.nice_select', '.nice-select .option:not(.disabled)', function(event) {
        
        var $option = $(this);
        var $dropdown = $option.closest('.nice-select');
        if ($dropdown.hasClass('has-multiple')) {
            if ($option.hasClass('selected')) {
                $option.removeClass('selected');
            } else {
                $option.addClass('selected');
            }
            $selected_html = '';
            $selected_values = [];
            $dropdown.find('.selected').each(function() {
                $selected_option = $(this);
                var attrValue = $selected_option.data('value');
                var text = $selected_option.data('display') ||  $selected_option.text();
                $selected_html += (`<span class="current" data-id=${attrValue}> ${text} <span class="remove">X</span></span>`);
                $selected_values.push($selected_option.data('value'));
            });
            $select_placeholder = $dropdown.prev('select').data('js-placeholder') ||                                   $dropdown.prev('select').attr('js-placeholder');
            $select_placeholder = !$select_placeholder ? 'Select' : $select_placeholder;
            $selected_html = $selected_html === '' ? $select_placeholder : $selected_html;
            $dropdown.find('.multiple-options').html($selected_html);
            $dropdown.prev('select').val($selected_values).trigger('change');
        } else {
            $dropdown.find('.selected').removeClass('selected');
            $option.addClass('selected');
            var text = $option.data('display') || $option.text();
            $dropdown.find('.current').text(text);
            $dropdown.prev('select').val($option.data('value')).trigger('change');
        }
      console.log($('.mySelect').val())
    });
  //---------remove item
  $(document).on('click','.remove', function(){
    var $dropdown = $(this).parents('.nice-select');
    var clickedId = $(this).parent().data('id')
    $dropdown.find('.list li').each(function(index,item){
      if(clickedId == $(item).attr('data-value')) {
        $(item).removeClass('selected')
      }
    })
    $selected_values.forEach(function(item, index, object) {
      if (item === clickedId) {
        object.splice(index, 1);
      }
    });
    $(this).parent().remove();
    console.log($('.mySelect').val())
   })
  
    // Keyboard events
    $(document).on('keydown.nice_select', '.nice-select', function(event) {
        var $dropdown = $(this);
        var $focused_option = $($dropdown.find('.focus') || $dropdown.find('.list .option.selected'));

        // Space or Enter
        if (event.keyCode == 32 || event.keyCode == 13) {
            if ($dropdown.hasClass('open')) {
                $focused_option.trigger('click');
            } else {
                $dropdown.trigger('click');
            }
            return false;
            // Down
        } else if (event.keyCode == 40) {
            if (!$dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            } else {
                var $next = $focused_option.nextAll('.option:not(.disabled)').first();
                if ($next.length > 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $next.addClass('focus');
                }
            }
            return false;
            // Up
        } else if (event.keyCode == 38) {
            if (!$dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            } else {
                var $prev = $focused_option.prevAll('.option:not(.disabled)').first();
                if ($prev.length > 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $prev.addClass('focus');
                }
            }
            return false;
            // Esc
        } else if (event.keyCode == 27) {
            if ($dropdown.hasClass('open')) {
                $dropdown.trigger('click');
            }
            // Tab
        } else if (event.keyCode == 9) {
            if ($dropdown.hasClass('open')) {
                return false;
            }
        }
    });

    // Detect CSS pointer-events support, for IE <= 10. From Modernizr.
    var style = document.createElement('a').style;
    style.cssText = 'pointer-events:auto';
    if (style.pointerEvents !== 'auto') {
        $('html').addClass('no-csspointerevents');
    }

    return this;

};

}(jQuery));

$(document).ready(function() {
    $('#Country').niceSelect();
    $('#country_code').niceSelect();
});



</script>



<script>
	let form = document.getElementsByTagName('form');

	for (let i = 0; i < form.length; i++) {
		
		let btn = form[i].getElementsByTagName('button');

		form[i].addEventListener('submit', ()=> {
			setTimeout(() => {
				for (let j = 0; j < btn.length; j++) {
					if(btn[j].getAttribute('id') === 'resend-code') {

					}
					else {
						btn[j].setAttribute('disabled', 'true');
						btn[j].classList.add('no-click');
						btn[j].innerHTML = '<svg style="background:transparent; height:24px;width: auto;" xmlns="http://www.w3.org/2000/svg" width="135" height="140" viewBox="0 0 135 140" fill="#fff" style="&#10;    background: #000;&#10;"> <rect y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="30" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="60" width="15" height="140" rx="6"> <animate attributeName="height" begin="0s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="90" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.25s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> <rect x="120" y="10" width="15" height="120" rx="6"> <animate attributeName="height" begin="0.5s" dur="1s" values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear" repeatCount="indefinite"/> <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10" calcMode="linear" repeatCount="indefinite"/> </rect> </svg>'
					}
					
					
				}
			}, 200);
		})
		
	}

	

</script>


<script>
function copy(){
  
  var temp = document.createElement('input');
  var texttoCopy = document.getElementById('mainOtp').innerHTML;
  var status = document.getElementById('copy_status');
  temp.type = 'input';
  temp.setAttribute('value',texttoCopy);
  document.body.appendChild(temp);
  temp.select();
  document.execCommand("copy");
  temp.remove();
  status.classList.add('slide');
  setTimeout(() => {
	status.classList.remove('slide')
  }, 1000);
}
</script>

</body>

</html>