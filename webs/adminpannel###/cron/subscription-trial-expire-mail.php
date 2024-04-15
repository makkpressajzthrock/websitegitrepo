<?php

// https://ecommerceseotools.com/ecommercespeedy/adminpannel/cron/subscription-trial-expire-mail.php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;
require_once('../smtp/PHPMailerAutoload.php');
require '../smtp-send-grid/vendor/autoload.php';

// echo "eee";
echo $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
// get all active subscriptions ---------

// echo '<br>';
// echo base64_decode("NjM3");
// die;
$user_subscriptions_free = getTableData( $conn , " user_subscriptions_free " , "status = 1", "" , 1 ) ;

foreach ($user_subscriptions_free as $key => $subscription_data) 
{
	// print_r($user_subscriptions_free) ;
	// die;

	// echo $subscription_data["plan_end_date"] ;
	$plan_end_date = $subscription_data["plan_end_date"] ;
	// $plan_end_date = "2023-01-18 04:37:28" ;
	$current_date = date('Y-m-d H:i:s') ;

	// echo "current_date : ".$current_date;

	$diff = date_diff(date_create($current_date) , date_create($plan_end_date) ) ;




	if ( ($diff->invert == 0) && ($diff->d <= 7) ) 
	{
// print_r($diff);
		$user_data = getTableData( $conn , " admin_users " , " id = '".$subscription_data["user_id"]."' " ) ;
		// print_r($user_data) ;

		// ---------------------------------------------------------------------------

		// $name = "Akash Makkpress" ;
		$name = empty($user_data["firstname"]) ? $user_data["email"] : $user_data["firstname"].''.$user_data["lastname"] ;

		$user_id =  $user_data["id"]; 
		$get_flow = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
		$d = $get_flow->fetch_assoc();


		$subscribed_plan = $plans_link = HOST_URL."plan.php?sid=".base64_encode($d['id']);
		// $email = "akash@makkpress.com" ;
	echo	$email = $user_data["email"] ;

// echo 'email sending.';
		// die;		
		// get email content from database ----------
		$emailContent = getEmailContent( $conn , 'Subscription Trial Ended Email' ) ;

		// set email variable values ----------------
		$emailVariables = array("name" => $name , "Subscribed_plan_link" => $subscribed_plan , "plans_link" => $plans_link );

		// replace variable values from message body ------
		foreach($emailVariables as $key1 => $value1) {
		    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
		}

	

					$smtpDetail = getSMTPDetail($conn);

$emailss = new \SendGrid\Mail\Mail(); 
$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailss->setSubject($emailContent["subject"]);
$emailss->addTo($email,"Website Speddy");
$emailss->addContent("text/html",$emailContent["body"]);
 $sendgrid = new \SendGrid($smtpDetail["password"]);



					if (!$sendgrid->Send($emailss)) {

						echo "something went wrong.";
					}
					else{

						echo "sent.";	
					}



	}

	if ( ($diff->invert == 1) ) 
	{
		$boost_website = getTableData( $conn , " boost_website " , " manager_id = '".$subscription_data["user_id"]."' and plan_type = 'Free' "  ) ;
		// print_r($boost_website);
			$encFn = $boost_website['id'];
			$url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_1.js";
			unlink($url_F);
			$url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_2.js";
			unlink($url_F);
			$url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_3.js";
			unlink($url_F);



	}


}






