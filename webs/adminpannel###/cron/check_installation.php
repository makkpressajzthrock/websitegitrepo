<?php

 ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once('../config.php');
require '../smtp-send-grid/vendor/autoload.php';
require_once("../inc/functions.php");

 $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
$variableee = "";
 
 
$install = $conn->query("SELECT id, manager_id, website_url,installation,installation_time,installation_email FROM boost_website where installation_email = 0 and installation =3 ") ;
 while($inst = $install->fetch_assoc() ) 
 {


		$url = $inst['website_url'];
		$manager_id = $inst['manager_id'];
		
		$site_id = $inst['id'];

		$scriptAll = $conn->query("SELECT * FROM script_log where site_id = '$site_id'") ;
		$scripts = $scriptAll->fetch_assoc();

		$script = $scripts['url'];
		$urlLists = explode(',', $script);

// print_r($urlLists);
		$variableee = "";
		$variableee = get_dataa($url);

		$f = 1;
		foreach ($urlLists as $urlList) {  

		   
		 
			 $code_has=check_url($variableee, $urlList) ;
											
			if($code_has == 0){
				$f = 0;

			}

		}

echo $f;

	if($f == 1){
		$conn->query("UPDATE `boost_website` SET installation_email = 2 where id = '$site_id' and manager_id = '$manager_id' ") ;
	}
	else{

		// Gettin Time is over 1 hrs

			$installation_time = $inst["installation_time"] ;
			$current_date = date('Y-m-d H:i:s') ;
			$diff = date_diff(date_create($current_date) , date_create($installation_time) ) ;

		
				if ( ($diff->invert == 1) && ($diff->h >= 1) ) 	
					{
						
						echo "Sending email to install";

							$emailContent = getEmailContent($conn, 'Website speed is holding');
							$smtpDetail = getSMTPDetail($conn);


							$userData = $conn->query("SELECT email,active_status,subscribe_email,email_status FROM admin_users where id = '$manager_id'") ;
							$user = $userData->fetch_assoc();					

							// echo $emailContent["body"];
							echo $user['email'];
							$email = $user['email'];
							if($user['active_status']==1){

										$att_url  = "https://websitespeedy.com/assets/images/websitespeedy.png";
										$filename = basename($att_url);
										$file_encoded = base64_encode(file_get_contents($att_url));

										$attachment = new SendGrid\Mail\Attachment();
										$attachment->setType("application/text");
										$attachment->setContent($file_encoded);
										$attachment->setDisposition("attachment");
										$attachment->setFilename($filename);
										 


										$emailss = new \SendGrid\Mail\Mail(); 
										$emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
										$emailss->setSubject($emailContent["subject"]);
										$emailss->addTo($email,"Website Speddy");
										$emailss->addContent("text/html",$emailContent["body"]);
										$emailss->addAttachment($attachment);
										 $sendgrid = new \SendGrid($smtpDetail["password"]);



										if($user['subscribe_email']==1 && $user['email_status']==1){

										$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('$manager_id', '".$emailContent["subject"]."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE())");

												if (!$sendgrid->Send($emailss)) {

													echo "something went wrong.";
												}
												else{

													echo "sent.";	
													$conn->query("UPDATE `boost_website` SET installation_email = 1 where id = '$site_id' and manager_id = '$manager_id' ") ;

												}
										}
										else{

										$conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at, status) VALUES('$manager_id', '".$emailContent["subject"]."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE() , 'Unsubscribed' ) ");
											echo "Unsubscribed";	

 	  										$conn->query("UPDATE `boost_website` SET installation_email = 112 where id = '$site_id' and manager_id = '$manager_id' ") ;

										}

						}
						else{
											$conn->query("UPDATE `boost_website` SET installation_email = 11 where id = '$site_id' and manager_id = '$manager_id' ") ;

						}


					}





	}

 }




	
function get_dataa($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}



function check_url($variableee, $code) {

		 
		return (strpos($variableee, $code))? 1:0;
  
}


?>   

