<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;
require_once('../smtp/PHPMailerAutoload.php');
require '../smtp-send-grid/vendor/autoload.php';

// echo "eee";
 $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
// get all active subscriptions --------- and id = 454
$user_subscriptions_free = getTableData( $conn , " user_subscriptions_free " , "status = 1", "" , 1 ) ;


foreach ($user_subscriptions_free as $key => $subscription_data) 
{
	// print_r($subscription_data) ;
	$website = getTableData( $conn , " boost_website " , " manager_id = '".$subscription_data["user_id"]."' and  plan_id = '".$subscription_data["plan_id"] ."'  and  plan_type = 'Free' and  subscription_id = '".$subscription_data["id"]."' ", "" , 1 ) ;
	// echo $subscription_data["plan_end_date"] ;
	$plan_end_date = $subscription_data["plan_end_date"] ;
	// $plan_end_date = "2023-01-18 04:37:28" ;
	$current_date = date('Y-m-d H:i:s') ;

	// echo "current_date : ".$current_date;

 
	$diff = date_diff(date_create($current_date) , date_create($plan_end_date) ) ;

		$user_data = getTableData( $conn , " admin_users " , " id = '".$subscription_data["user_id"]."' " ) ;
		$name = empty($user_data["firstname"]) ? $user_data["email"] : $user_data["firstname"].' '.$user_data["lastname"] ;
		$subscribed_plan = $plans_link = HOST_URL."plan.php?sid=".base64_encode($website[0]['id']) ;
		$email = $user_data["email"] ;
		$user_email_subs  = $user_data["subscribe_email"];
		$email_status  = $user_data["email_status"];
		$user_email_subs = 1;	

		if($user_data["active_status"] == 0)
		{
			$user_id = $user_data["id"];
			$subscription_data_id = $subscription_data["id"];
			$conn->query("UPDATE user_subscriptions_free set status = 0 where user_id = '$user_id' and id = '$subscription_data_id' ");
			continue;
		}

     	print_r($diff);

 


	if ( ($diff->invert == 0) && ($diff->d == 0) &&  ($diff->h < 5)  ) 
	{

	print_r($diff);
 
		// ---------------------------------------------------------------------------

		$emailContent = getEmailContent( $conn , 'Your trial ends soon' ) ;

		// set email variable values ----------------
		$day_s = "'s";
		if( (($diff->days)+1) ==1){
			$day_s = "";
		}

		$emailVariables = array("name" => $name , "Subscribed_plan_link" => $subscribed_plan , "plans_link" => $plans_link , "Expire_days_left" => ($diff->days)+1 , "day_s" => $day_s);

		// replace variable values from message body ------
		foreach($emailVariables as $key1 => $value1) {
		    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
		}

		echo $emailContent["body"];
	
			// Send email Trayal
			if(CheckEmailSend($subscription_data["user_id"], $conn) == 0 ){
				if($user_email_subs == 1 && $email_status == 1){
					//sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);

					//$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message,created_at,sent_by_cron ) VALUES('".$subscription_data["user_id"]."', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE(),1)");

				}
				else{
//conn->query("INSERT INTO email_logs (user_id, email_subject, email_message,created_at ,status,sent_by_cron) VALUES('".$subscription_data["user_id"]."', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE(), 'Unsubscribed',1)");					
				}

					
			}

	}



 


	if ( ($diff->invert == 1) ) 
	{


echo "<pre>";
print_r($diff);

	if($diff->days == 0)
	{
		$emailContent = getEmailContent( $conn , 'Free Trial Expired' ) ;
		// set email variable values ----------------
		$emailVariables = array("name" => $name , "Subscribed_plan_link" => $subscribed_plan , "plans_link" => $plans_link);
		// replace variable values from message body ------
		foreach($emailVariables as $key1 => $value1) {
		    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
		}
		// Send email Trayal
		if(CheckEmailSend($subscription_data["user_id"], $conn) ==0 ){	

			if($user_email_subs == 1 && $email_status == 1){
				//sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);
				//$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at,sent_by_cron) VALUES('".$subscription_data["user_id"]."', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."',  CURDATE(),1)");
			}else{

				//$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at,status,sent_by_cron) VALUES('".$subscription_data["user_id"]."', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."',  CURDATE(),'Unsubscribed',1)");
			}


		}

	}

 

	}


}




function sendEmail($conn, $subject, $email ,$body){

					echo "Sending email...";
					echo $body;

					$smtpDetail = getSMTPDetail($conn);

					$emailss = new \SendGrid\Mail\Mail(); 
					$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
					$emailss->setSubject($subject);
					$emailss->addTo($email,"Website Speddy");
					$emailss->addContent("text/html",$body);
					$sendgrid = new \SendGrid($smtpDetail["password"]);

					if (!$sendgrid->Send($emailss)) {

						echo "something went wrong.";
					}
					else{

						echo "sent.";	
					}
}

function CheckEmailSend($user_id , $conn){
	$email_logs = getTableData( $conn , " email_logs " , "user_id = $user_id and created_at = CURDATE() and sent_by_cron = 1","","",1) ;
	
	return(count($email_logs));
}


