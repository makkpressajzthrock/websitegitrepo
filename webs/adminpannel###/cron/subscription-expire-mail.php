<?php

// https://ecommerceseotools.com/ecommercespeedy/adminpannel/cron/subscription-expire-mail.php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;
require_once('../smtp/PHPMailerAutoload.php');

require '../smtp-send-grid/vendor/autoload.php';

// get all active subscriptions ---------
$user_subscriptions = getTableData( $conn , " user_subscriptions " , " is_active = 1 AND status LIKE 'succeeded' " , "" , 1 ) ;

foreach ($user_subscriptions as $key => $subscription_data) 
{
	
$date_expire = $subscription_data["plan_period_end"] ;   
$date = new DateTime($date_expire);
$now = new DateTime();


 $days_left =  $date->diff($now)->format("%d");


if($days_left <= 5)
{

$sele = "select * from  admin_users where id='".$subscription_data["user_id"]." ' ";
$run= mysqli_query($conn,$sele);
$run_qr=mysqli_fetch_array($run,MYSQLI_BOTH );

$name=$run_qr['firstname'].''.$run_qr['lastname'];
$email=$run_qr['email'];
// echo "email : ".$email."<hr>" ;
	
		$user_id =  $run_qr["id"]; 
		$get_flow = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' ");
		$d = $get_flow->fetch_assoc();

$payment_link = HOST_URL."plan.php?sid=".base64_encode($d['id']);
 // print_r($email);
// get email content from database ----------
if($days_left == 5){
$emailContent = getEmailContent( $conn , '5 days before the subscription expired' ) ;
}
else{
$emailContent = getEmailContent( $conn , 'Subscription about to expire' ) ;
}
// if(($days_left >=3)?$emailContent:$emailContent2)

// set email variable values ----------------
$emailVariables = array("name" => $name , "payment-link" => $payment_link  );

// replace variable values from message body ------
foreach($emailVariables as $key1 => $value1) {
    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
}

// get SMTP detail ---------------
$smtpDetail = getSMTPDetail($conn) ;
// print_r($smtpDetail) ; //print_r($emailContent) ; //die() ;
// ------------------------------------------------------------------------------------

// send mail ----------------------------------------------------------------


$emailss = new \SendGrid\Mail\Mail(); 
$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$emailss->setSubject($emailContent["subject"]);
$emailss->addTo($email,"Website Speddy");
$emailss->addContent("text/html",$emailContent["body"]);
$sendgrid = new \SendGrid($smtpDetail["password"]);
//var_dump($sendgrid->Send($emailss));

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


}


	 	// break ;
}






?>