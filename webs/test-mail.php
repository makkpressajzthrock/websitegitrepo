<?php

require_once("adminpannel/smtp/PHPMailerAutoload.php");

// send mail ----------------------------------------------------------------
$mail = new PHPMailer(); 
$mail->SMTPDebug=3;
$mail->IsSMTP(); 
$mail->SMTPAuth = true; 
$mail->SMTPSecure = 'tls'; 
$mail->Host = "smtp.gmail.com";
$mail->Port = "587"; 
$mail->IsHTML(true);
$mail->CharSet = 'UTF-8';
$mail->Username = "audit@ecommerceseotools.com" ;
$mail->Password = "fvfnuvesvucwgkdr" ;
$mail->SetFrom("info@ecommercespeedy.com","Website Speedy");
$mail->Subject = "Activation Code for Website Speedy" ;
$mail->Body = "Hello!<br>We’re excited you’ve joined us! To verify your email address, enter the code provided below on the email confirmation page.<br>Verification code:<br>{123456}<br>If you did not make this request, please ignore this email.<br>Thanks,<br>Website Speedy" ;
$mail->AddAddress("akash@makkpress.com");
$mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));

if(!$mail->Send()) {
	echo "Mail not send.";
}
else {
	echo " Mail send." ;
}
