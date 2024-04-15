<?php

include_once('smtp/PHPMailerAutoload.php');


    $mail = new PHPMailer(); 
    // $mail->SMTPDebug=3;
    $mail->IsSMTP(); 
    $mail->SMTPAuth = true; 
    $mail->SMTPSecure = 'tls'; 
    $mail->Host = "smtp.gmail.com";
    $mail->Port = "587"; 
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "apps@makkpress.com";
    $mail->Password = 'exfyxopitmkkkduj';
    $mail->SetFrom("apps@makkpress.com");
    $mail->Subject = "test makkpress";
    $mail->Body = "message body" ;
    $mail->AddAddress("akash@makkpress.com");
    $mail->SMTPOptions=array('ssl'=>array(
        'verify_peer'=>false,
        'verify_peer_name'=>false,
        'allow_self_signed'=>false
    ));

    if(!$mail->Send()){
        echo $mail->ErrorInfo;
    }
    else{
        echo 'Sent';
    }