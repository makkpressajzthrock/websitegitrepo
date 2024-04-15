<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;
require_once('../smtp/PHPMailerAutoload.php');
require '../smtp-send-grid/vendor/autoload.php';

// echo "eee";
 $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
// get all active subscriptions --------- and id = 454
$user_subscriptions= getTableData( $conn , " user_subscriptions" , " is_active = 1 AND status LIKE 'succeeded' AND (plan_id =29 or plan_id=30)", " order by id desc " , 1 ) ;

// echo "<pre>";
// print_r($user_subscriptions); die;

foreach ($user_subscriptions as $key => $subscription_data){
		
	$page_view_used_date = $subscription_data["page_view_used_date"];
	$page_view_100_exausted = $subscription_data["page_view_100_exausted"];
	// echo "<pre>";
	// print_r($page_view_used_date);
	// print_r($page_view_100_exausted);

	// echo $subscription_data["user_id"];
	// echo $subscription_data["plan_id"];
	// echo $subscription_data["id"];
	// sleep(1); 

	
	$website = getTableData( $conn , " boost_website " , " manager_id = '".$subscription_data["user_id"]."' and  plan_id = '".$subscription_data["plan_id"] ."'  and  plan_type = 'Subscription' and  subscription_id = '".$subscription_data["id"]."' ", " order by id desc" , 1 ) ;
	
	$site_visit_count = getTableData( $conn , " site_visit_count " , " website_id = '".$website[0]["id"]."'  ", " order by id desc" , 1 ) ;

	$plan = getTableData( $conn , " plans " , " id = '".$website[0]["plan_id"]."'  ", " order by id desc" , 1 ) ;
	$admin_users = getTableData( $conn , " admin_users " , " id = '".$subscription_data["user_id"]."'  ", " order by id desc" , 1 ) ;
	  
	
	// echo "<pre>";
	// print_r($site_visit_count);
	// print_r($admin_users);
	// print_r($plan); die;
	$email = $admin_users[0]['email'];
	$subId =   $subscription_data['id'];
	$view_count =   $site_visit_count[0]['view_count'];
	$page_view =   $plan[0]['page_view'];	
	if ($page_view != 0) {
		$view_percentage = ($view_count / $page_view) * 100;
		// Rest of your code that depends on $view_percentage
	}
	$view_percentage = (float)$view_percentage;
	if($view_percentage>=70   && $view_percentage<=80 || $view_percentage>=100 ){
		if($view_percentage>=100){
		   // echo $view_percentage;
		   if(isset($page_view_used_date)){

			   // Get the current date in the same format as page_view_used_date
			   $currentDateStr = date('Y-m-d H:i:s');
			   $currentDate = new DateTime($currentDateStr);
			   // Assuming $pageViewUsedDateStr is the value from the database
			   $pageViewUsedDate = new DateTime($page_view_used_date);
			   // Calculate the difference
			   $diff = $pageViewUsedDate->diff($currentDate);
			   echo $diff->days;
			   if (  ($diff->d > 0) && ($diff->d <= 6) ){
		   
				   $sendemail = 0;
				   if($diff->d == 1){
					   echo "Fear based";
					   $emailContent = getEmailContent( $conn , 'Fear based' );	
					   $emailVariables = array("user" => $admin_users[0]['firstname']);
					   foreach($emailVariables as $key1 => $value1) {
					       $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
					   }					
					   $sendemail = 1;
					   sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);
		   
				   }
				   elseif($diff->d == 2){
					   echo "site-performance";
		   
					   $emailContent = getEmailContent( $conn , 'Site Performance' ) ;
					   $emailVariables = array("user" => $admin_users[0]['firstname']);
					   foreach($emailVariables as $key1 => $value1) {
					       $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
					   }
					   $sendemail = 1;
					   sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);
		   
				   }
				   elseif($diff->d == 4){
					   echo "Greed";
		   
					   $emailContent = getEmailContent( $conn , 'Greed' ) ;
					   $emailVariables = array("user" => $admin_users[0]['firstname']);
					   foreach($emailVariables as $key1 => $value1) {
					       $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
					   }
					   $sendemail = 1;
					   sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);
		   
				   }
				   elseif($diff->d == 6){
					   echo "FOMO";
					   $emailContent = getEmailContent( $conn , 'FOMO' ) ;
					   $emailVariables = array("user" => $admin_users[0]['firstname']);
					   foreach($emailVariables as $key1 => $value1) {
					       $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
					   }
					   $sendemail = 1;
					   sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);
					   updateTableData($conn,' user_subscriptions', "is_active = '0'", "id= $subId");
		   
				   }
			   }else{
				   
			   }
						   
		   }else{
			   $date = date('Y-m-d H:i:s');
			   updateTableData($conn,' user_subscriptions', "page_view_used_date = '$date'", "id= $subId");
			   
		   }

		   	/*** Mail disabled as per Ajay & ishan sir's instructions 01-03-2024 ***/ 
		   	/***
			if(empty($page_view_100_exausted)){
				echo "100%  Pageviews Exhausted";
				$emailContent = getEmailContent( $conn , '100%  Pageviews Exhausted' ) ;			
				$emailVariables = array("user" => $admin_users[0]['firstname']);
				foreach($emailVariables as $key1 => $value1) {
					$emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
				}	
				sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);
				$sendemail = 1;
				updateTableData($conn,' user_subscriptions', "page_view_100_exausted = '1'", "id= $subId");
			}
			else{
				echo 'exauted flag 1';
			}
		   	***/

		}else{
		   if($view_percentage>=70   && $view_percentage<=80){
			   echo "80% Pageviews used";
			   $emailContent = getEmailContent( $conn , '80% Pageviews used' ) ;
			   $emailVariables = array("user" => $admin_users[0]['firstname']);
			   foreach($emailVariables as $key1 => $value1) {
				   $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
			   }	
			   sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);			
			   $sendemail = 1;
		   }
		   
		}

   }else{
   }

   $plan_period_start = new DateTime($subscription_data["plan_period_start"]);
   $plan_current_date = new DateTime(); // Defaults to current date and time

// Output the dates to check their values
// echo "Plan Period Start: " . $plan_period_start->format('Y-m-d H:i:s') . "<br>";
// echo "Current Date: " . $current_date->format('Y-m-d H:i:s') . "<br>";

	// $diffs = $plan_current_date->diff($plan_period_start);
	$diffs = $plan_period_start->diff($plan_current_date);

	$daysDifference = $diffs->d;
	$invert = $diffs->invert;
	$subid = $subscription_data["user_id"];

	echo "Days Difference: $email => $daysDifference days"."<br>";
	echo "subscription Id: $subid "."<br>";

		if($diffs->d == 1){
			/*** Mail disabled as per Ajay & ishan sir's instructions 01-03-2024 ***/ 
			/***
			echo "Ready for Actions";
			$emailContent = getEmailContent( $conn , 'Ready for Action' );
			$emailVariables = array("user" => $admin_users[0]['firstname']);
			foreach($emailVariables as $key1 => $value1) {
				$emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
			}	
			sendEmail($conn,$emailContent["subject"], $email, $emailContent["body"]);			
			$sendemail = 1;
			***/
		}
		// die;
	
}

if($sendemail == 1){
		
	sendEmail($conn,$emailContent, $email, $emailContent);
}

function sendEmail($conn, $subject, $email ,$body){

	echo "Sending email...";

	$smtpDetail = getSMTPDetail($conn);

	$emailss = new \SendGrid\Mail\Mail(); 
	$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
	$emailss->setSubject($subject);
	$emailss->addTo($email,"Website Speedy");
	$emailss->addContent("text/html",$body);
	$sendgrid = new \SendGrid($smtpDetail["password"]);

	if (!$sendgrid->Send($emailss)) {

		echo "something went wrong.";
	}
	else{

		echo "sent.";	
	}
}

// function CheckEmailSend($user_id , $conn){
// 	$email_logs = getTableData( $conn , " email_logs " , "user_id = $user_id and created_at = CURDATE() and sent_by_cron = 1 ","","",1) ;
// 	// print_r($email_logs);
// 	// die;
// 	return(count($email_logs));
// }


