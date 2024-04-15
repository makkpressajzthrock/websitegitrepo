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

		$password = md5($_POST['password']);
		$confirm_password = md5($_POST['confirm-password']);
		$sumocode = $_POST['coupon-code'];
		$created_at = date("Y/m/d"." "."h:i:s");

		if($fname!="" && $lname !="" && $email !="" && $password !="" && $confirm_password !="" && trim($sumocode) !="" && $country_code !="" && $phone !="")
		{

		$user = "SELECT * FROM admin_users WHERE email = '$email'";
		$userResult = mysqli_query($conn,$user);
		if(mysqli_num_rows($userResult)<=0){


		$fetchCode = "SELECT * FROM life_time WHERE sumo_code = '$sumocode' AND used != '1'";
		$fetchResult = mysqli_query($conn,$fetchCode);
		$code = mysqli_fetch_assoc($fetchResult);

		$sumoCode = $code['sumo_code'];

		if ($sumocode == $sumoCode) 
		{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
			{
		      	echo "5";
		    }
		    else
		    {
		    	if ($password==$confirm_password) 
				{	
					$query = "INSERT INTO admin_users (firstname,lastname,email,phone,password,userstatus,status,
					created_at,updated_at,address_line_1,address_line_2,city,state,zipcode,country,token,sumo_code,platform,user_type,country_code) VALUES ('$fname','$lname','$email','$phone','$password','manager','1','$created_at','$created_at','','','','','','','','$sumocode','','Lifetime','$country_code')";
					$execute = mysqli_query($conn,$query);
					if ($execute) 
	 				{
	 					$last_id = mysqli_insert_id($conn);



// Start send email to admin

					$emailContent = getEmailContent($conn, 'Admin Emails');
 					$body = "
 						<tr><td>From : Lifetime Deal Registration Page</td></tr>
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
					$emailsss->setSubject("We Got New Registration For Lifetime Deal From ". $email);
					$emailsss->addTo("service@websitespeedy.com","Website Speddy");
					$emailsss->addContent("text/html",$emailContents);
					$sendgrid = new \SendGrid($smtpDetail["password"]);
					$sendgrid->Send($emailsss);

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
 						$sendgrid->Send($emailsss);



						$update_sumo_code = "UPDATE `life_time` SET `used` = '1' WHERE `sumo_code` = '$sumocode'";
						$updatedsumo_code = mysqli_query($conn,$update_sumo_code);
						if ($updatedsumo_code) 
						{
							echo "0";
						}
						else
						{
							echo "1";
						}
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
		else
		{
			echo "4";
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