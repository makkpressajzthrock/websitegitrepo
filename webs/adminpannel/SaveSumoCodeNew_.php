<?php
	include('config.php');
	require_once("inc/functions.php");
	require 'smtp-send-grid/vendor/autoload.php';

	if (isset($_POST['email'])) 
	{	
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$country_code = $_POST['country_code'];
		$phone = $_POST['phone'];
		$ltd_from = $_POST['ltd_from'];


		$password = md5($_POST['password']);
		$confirm_password = md5($_POST['confirm-password']);
		 
		$created_at = date("Y/m/d"." "."h:i:s");

		if($fname!="" && $lname !="" && $email !="" && $password !="" && $confirm_password !=""  && $ltd_from !="") //&& $country_code !=""
		{

		$user = "SELECT * FROM admin_users WHERE email = '$email'";
		$userResult = mysqli_query($conn,$user);
		if(mysqli_num_rows($userResult)<=0){
 
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
		      	echo "5";
		    }
		    else
		    {
		    	if ($password==$confirm_password) 
				{	

					// code platform
					$code_platform == "AppSumo";

					if ( $ltd_from == "AppSumo" ) {
						$code_platform = "AppSumo";
					}
					else if( $ltd_from == "Dealify" ) {
						$code_platform = "Dealify";
					}
					else if( $ltd_from == "DealFuel" ){
						$code_platform = "DealFuel";
					}


					// $code_platform = "AppSumo";


					$help_pass = base64_encode($_POST['password']);
					$query = "INSERT INTO admin_users (firstname,lastname,email,phone,password,userstatus,status,
					created_at,updated_at,address_line_1,address_line_2,city,state,zipcode,country,token,platform,user_type,country_code,help_pass,sumo_new , ltd_code_origin) VALUES ('$fname','$lname','$email','$phone','$password','manager','1','$created_at','$created_at','','','','','','','','','".$code_platform."','$country_code','$help_pass','1' , '$ltd_from')";
					$execute = mysqli_query($conn,$query);
					if ($execute) 
	 				{
	 					$last_id = mysqli_insert_id($conn);

 
					$emailContent = getEmailContent($conn, 'Admin Emails');
 					$body = "
 						<tr><td>From : ".$code_platform." Registration Page</td></tr>
						<tr><td>Email : $email </td></tr>
						<tr><td>Name : $fname $lname </td></tr>
						<tr><td>Country Code : $country_code </td></tr>
						<tr><td>Phone : $phone </td></tr> 

					";
				 
						$emailContents = str_replace('{{body}}', $body, $emailContent["body"]);
				 

					// get SMTP detail ---------------
					$smtpDetail = getSMTPDetail($conn);
 
					$emailsss = new \SendGrid\Mail\Mail(); 
					$emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
					$emailsss->setSubject("We Got New Registration for ".$code_platform." from ". $email);
					$emailsss->addTo("service@websitespeedy.com","Website Speddy");
					$emailsss->addContent("text/html",$emailContents);
					$sendgrid = new \SendGrid($smtpDetail["password"]);
					// $sendgrid->Send($emailsss);
					$subject = "We Got New Registration for ".$code_platform." from ". $email;

					$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('1', '".$subject."', '".$conn->real_escape_string($emailContents)."', CURDATE())");						

// End email to admin

						$confirmEmail = $email;
 
						$emailContent = getEmailContent($conn, 'Thanks Register');
						$smtpDetail = getSMTPDetail($conn);
	
						$emailsss = new \SendGrid\Mail\Mail(); 
						$emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
						$emailsss->setSubject($emailContent["subject"]);
						$emailsss->addTo($confirmEmail,"Website Speddy");
						$emailsss->addContent("text/html",$emailContent["body"]);
						$sendgrid = new \SendGrid($smtpDetail["password"]);
 						// $sendgrid->Send($emailsss);


 						// For sending email to support@websitespeedy.com =====================

						$smtpDetail = getSMTPDetail($conn);
	 					$emailsss = '' ;
						$emailsss = new \SendGrid\Mail\Mail(); 
						$emailsss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
						$emailsss->setSubject("We Got New Registration from ".$code_platform." Email : ". $email);
						$emailsss->addTo("service@websitespeedy.com","Website Speddy");
						$emailsss->addContent("text/html",$emailContents);
						$sendgrid = new \SendGrid($smtpDetail["password"]);
						$sendgrid->Send($emailsss);

 						// END For sending email to support@websitespeedy.com =================



						$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('$last_id', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE())");


						echo "0";
 
					}
					else
					{
						echo "2";
					}
				}
				else
				{
					echo "3";
				}
		    }
 
	}
	else{
		echo "6";
	}

}
	else{
		echo "7";
	}



	}
	
?>