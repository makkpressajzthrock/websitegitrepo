<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
require_once('smtp/PHPMailerAutoload.php');

 	
				 $qry="select * from email_template where id='1'";
				 $cont_qry=mysqli_query($conn,$qry);
				 
				 $run_qry=mysqli_fetch_array($cont_qry);
                  

                  $Subject=$run_qry['title'];
                  $msg=$run_qry['description'];

			// send variable name to email
                  
			$mail_variables = array("name"=>"" ) ;
			foreach($mail_variables as $key => $value) {
			    $msg = str_replace('{{'.$key.'}}', $value, $msg);
			}


                  // print_r($msg);
                  // die();

$mail = new PHPMailer(); 
				// $mail->SMTPDebug=3;
				$mail->IsSMTP(); 
				$mail->SMTPAuth = true; 
				$mail->SMTPSecure = 'tls'; 
				$mail->Host = "smtp.gmail.com";
				$mail->Port = "587"; 
				$mail->IsHTML(true);
				$mail->CharSet = 'UTF-8';
				$mail->Username = SMTP_USER ;
				$mail->Password = SMTP_PASSWARD ;
				$mail->SetFrom("info@ecommercespeedy.com","WEBSITE SPEEDY");
				// $mail->addReplyTo($developerEmail,$developerName);
				$mail->Subject = "$Subject ";
				$mail->Body = "Hey, <br> $msg </br> Thank You. ";
				$mail->AddAddress("karan.makkpress@gmail.com");
				// $mail->AddAddress("akash@makkpress.com");
				$mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));

				$mail->Send() ;



?>

