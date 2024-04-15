<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;
require_once('../smtp/PHPMailerAutoload.php');
require '../smtp-send-grid/vendor/autoload.php';

// echo "eee";
$rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);

echo "rootDir : ".$rootDir."<br>" ;

// get all active subscriptions --------- and id = 454
$user_subscriptions = getTableData( $conn , "user_subscriptions" , "created BETWEEN NOW() - INTERVAL 30 DAY AND NOW() AND is_active = 1 AND plan_interval <> 'Lifetime' ", "" , 1 ) ;


// echo "<pre>";
// print_r($user_subscriptions);
// die;

foreach ($user_subscriptions as $key => $subscription_data) 
{

 
	if ( in_array($subscription_data["plan_interval"], ["month" , "Lifetime" , "year"]) ) {


			// print_r($subscription_data) ;
			$website = getTableData( $conn , " boost_website " , " manager_id = '".$subscription_data["user_id"]."' and  plan_id = '".$subscription_data["plan_id"] ."'  and  plan_type = 'Subscription' and  subscription_id = '".$subscription_data["id"]."' ", "" , 1 ) ;
 

			$plan_end_date = $subscription_data["plan_period_end"] ;
			$plan_start_date = $subscription_data["plan_period_start"] ;
			// $plan_end_date = "2023-01-18 04:37:28" ;
			$current_date = date('Y-m-d H:i:s') ;


			// $diff = date_diff(date_create($current_date) , date_create($plan_end_date) ) ;
			$diff = date_diff(date_create($plan_start_date) , date_create($current_date) ) ;
			print_r($diff) ; echo "<br>" ;


			$user_data = getTableData( $conn , " admin_users " , " id = '".$subscription_data["user_id"]."' " ) ;
			// print_r($user_data) ; echo "<br>" ;

			$name = empty($user_data["firstname"]) ? $user_data["email"] : $user_data["firstname"].' '.$user_data["lastname"] ;
			$subscribed_plan = $plans_link = HOST_URL."plan.php?sid=".base64_encode($website[0]['id']) ;
			echo $email = $user_data["email"] ;
			$user_email_subs  = $user_data["subscribe_email"];
			$email_status  = $user_data["email_status"];

			if($user_data["active_status"] == 0)
			{
				$user_id = $user_data["id"];
				$subscription_data_id = $subscription_data["id"];
				$conn->query("UPDATE user_subscriptions set is_active = 0 where user_id = '$user_id' and id = '$subscription_data_id' ");
				continue;
			}
 




			if ( $diff->invert == 0 && $diff->days > 0 ) 
			{
				$sendemail = 0;

				$days = $diff->days ;

				if ( $subscription_data["trial_switch"] == 1 ) {
					$days = (int)$days + (int) $subscription_data["switch_after_days"] ;
				}

				echo "<hr>".$days."<hr>";

				// for monthly subscription
				if ( $subscription_data["plan_interval"] == "month" ) {

					if($days == 3) {

						echo "site-performance";

						$emailContent = getEmailContent( $conn , 'Site Performance' ) ;
						$emailVariables = array("user" => $name);
						foreach($emailVariables as $key1 => $value1) {
						    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
						}

						$sendemail = 1;

					}
					elseif($days == 4){

						echo "Case-Study-Email";

						$emailContent = getEmailContent( $conn , 'Case Study' ) ;
						$emailVariables = array("user" => $name);
						foreach($emailVariables as $key1 => $value1) {
						    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
						}

						$sendemail = 1;
					}


					elseif($days == 15){

						echo "FOMO";

						$emailContent = getEmailContent( $conn , '2 seconds will help to boost sales by 20per' ) ;
						$emailVariables = array("user" => $name);
						foreach($emailVariables as $key1 => $value1) {
						    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
						}						 

						$sendemail = 1;
					}


				}
				elseif ( $subscription_data["plan_interval"] == "year" ) {

					// for yearly subscription

					if($days == 3) {

						echo "site-performance";

						$emailContent = getEmailContent( $conn , 'Site Performance' ) ;
						$emailVariables = array("user" => $name);
						foreach($emailVariables as $key1 => $value1) {
						    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
						}

						$sendemail = 1;

					}
					elseif($days == 4){

						echo "Case-Study-Email";

						$emailContent = getEmailContent( $conn , 'Case Study' ) ;
						$emailVariables = array("user" => $name);
						foreach($emailVariables as $key1 => $value1) {
						    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
						}

						$sendemail = 1;
					}
					elseif($days == 20){

						echo "FOMO";

						$emailContent = getEmailContent( $conn , '2 seconds will help to boost sales by 20per' ) ;
						$emailVariables = array("user" => $name);
						foreach($emailVariables as $key1 => $value1) {
						    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
						}						 

						$sendemail = 1;
					}					


				}
				




				if($sendemail == 1){

					if(CheckEmailSend($subscription_data["user_id"], $conn) == 0 ){

						if($user_email_subs == 1 && $email_status == 1){

							// $email = "akash@makkpress.com" ;

							sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);

							echo "<hr>" ;

							$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message,created_at,sent_by_cron ) VALUES('".$subscription_data["user_id"]."', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE(), 1)");
						}
						else{
							$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message,created_at ,status, sent_by_cron) VALUES('".$subscription_data["user_id"]."', '".$conn->real_escape_string($emailContent["subject"])."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE(), 'Unsubscribed',1)");					
						}

					} 

				}


			}






			// break ; 


	}




}


function sendEmail($conn, $subject, $email ,$body){

	echo "Sending email...";

	// echo $body;

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
	$email_logs = getTableData( $conn , " email_logs " , "user_id = $user_id and created_at = CURDATE() and sent_by_cron = 1 ","","",1) ;
	// print_r($email_logs);
	// die;
	return(count($email_logs));
}