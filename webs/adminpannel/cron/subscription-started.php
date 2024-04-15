<?php

// https://ecommerceseotools.com/ecommercespeedy/adminpannel/cron/subscription-expire-mail.php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;
require_once('../smtp/PHPMailerAutoload.php');



// get all active subscriptions ---------



$name="Akash";
$subscription_name="NEW SUBSCRIPTION";
$email="karan.makkpress@gmail.com";
	

// get email content from database ----------

$emailContent = getEmailContent( $conn , 'Subscription Started' ) ;


// if(($days_left >=3)?$emailContent:$emailContent2)

// set email variable values ----------------
$emailVariables = array("name" => $name , "subscription_name" => $subscription_name  );

// replace variable values from message body ------
foreach($emailVariables as $key1 => $value1) {
    $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
}

// get SMTP detail ---------------
$smtpDetail = getSMTPDetail($conn) ;
// print_r($smtpDetail) ; //print_r($emailContent) ; //die() ;
// ------------------------------------------------------------------------------------

// send mail ----------------------------------------------------------------
$mail = new PHPMailer(); 
// $mail->SMTPDebug=3;
$mail->IsSMTP(); 
$mail->SMTPAuth = true; 
$mail->SMTPSecure = $smtpDetail["smtp_secure"]; 
$mail->Host = $smtpDetail["host"];
$mail->Port = $smtpDetail["port"]; 
$mail->IsHTML(true);
$mail->CharSet = 'UTF-8';
$mail->Username = $smtpDetail["email"] ;
$mail->Password = $smtpDetail["password"] ;
$mail->SetFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
$mail->addReplyTo($smtpDetail["from_email"],$smtpDetail["from_name"]);
$mail->Subject = $emailContent["subject"];
$mail->Body = $emailContent["body"] ;
$mail->AddAddress($email);
$mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));
var_dump($mail->Send() );







?>