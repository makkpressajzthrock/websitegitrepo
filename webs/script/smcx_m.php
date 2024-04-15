<?php

   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Methods: POST");
include('../adminpannel/config.php');
require_once('../adminpannel/smtp/PHPMailerAutoload.php');
require_once('../adminpannel/inc/functions.php') ;


       $smtpDetail = getSMTPDetail($conn) ;

$data = json_decode(file_get_contents("php://input")) ;
$doby ="<h1>Someone trying to use code of website speddy.</h1>";
$doby .="<br><h3>Domain Not Matching</h3>";
$doby .= "<br>Accessing From : ".$data[7];
$doby .= "<br>User Ip : ".$data[0];
$doby .= "<br>User City : ".$data[1];
$doby .= "<br>User Country : ".$data[2];
$doby .= "<br>User CountryIso : ".$data[3];
$doby .= "<br>User Latitude : ".$data[4];
$doby .= "<br>User Longitude : ".$data[5];
$doby .= "<br>User TimeZone : ".$data[6];


$email_id = "support@websitespeedy.com";

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
        $mail->Subject = 'Website Speddy - Someone Trying To Hack Website Speddy.';
        $mail->Body = $doby ;
        $mail->AddAddress($email_id);
        // $mail->addCC($email_id_cc);
        $mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false));

        $mail->Send();

