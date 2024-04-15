<?php
die;
   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Methods: POST");
include('../adminpannel/config.php');
require_once('../adminpannel/smtp/PHPMailerAutoload.php');
require_once('../adminpannel/inc/functions.php') ;


       $smtpDetail = getSMTPDetail($conn) ;

  $result = file_get_contents('php://input');
  $response = json_decode($result);


       // print_r($smtpDetail);
// 
$doby = "encryptKey = ".$response->encryptKey;
$doby .= "<br>encryptSx = ".$response->encryptSx;
$doby .= "<br>encryptdT = ".$response->encryptdT;
$doby .= "<br>encFn = ".$response->encFn;
$doby .= "<br>encSu = ".$response->encSu;

// $email_id = "support@websitespeedy.com";
// $email_id_cc = "rohan@makkpress.com";
$email_id = "rohan@makkpress.com";

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
        $mail->Subject = 'Website Speddy - Store Admin Trying to change url in script.';
        $mail->Body = $doby ;
        $mail->AddAddress($email_id);
        // $mail->addCC($email_id_cc);
        $mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false));

        $mail->Send();


$store_id = base64_decode($response->encryptSx); 

      $preurl = $response->encFn ; 
      $url = "/var/www/html/script/ecmrx/ecmrx";
     $url = $url."_".$store_id;
// echo '<br>';
 echo    $source1 = $url.'/'.$preurl.'_1.js';
    $source2 = $url.'/'.$preurl.'_2.js';
    $source3 = $url.'/'.$preurl.'_3.js';


  $myfile = fopen($source1, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);

  $myfile = fopen($source2, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);

    $myfile = fopen($source3, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);