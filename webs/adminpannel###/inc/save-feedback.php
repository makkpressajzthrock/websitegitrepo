<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

ini_set('max_execution_time', '0');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include('../config.php');
include('../session.php');
require_once('../inc/functions.php');
require_once '../smtp-send-grid/vendor/autoload.php';

if( $_POST['action'] == 'save-satisfied' ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;

	$query = $conn->query( " SELECT id FROM `website_review_feedback` WHERE website_id = '$website_id' ; " ) ;

	if ( $query->num_rows > 0 ) {

		$data = $query->fetch_assoc() ;

		$id = $data["id"] ;

		$sql = " UPDATE `website_review_feedback` SET `satified_or_not`='$satifiedVal' , `step_completed`='1' WHERE `id`='$id' ; " ;

	}
	else {

		$sql = " INSERT INTO `website_review_feedback`( `website_id`, `manager_id`, `satified_or_not` , `step_completed` ) VALUES ( '$website_id', '$manager_id', '$satifiedVal' , '1' ) ; " ;

	}


	if ( $conn->query($sql) === TRUE ) {
		echo json_encode(1) ;
	}
	else {
		echo json_encode(0) ;
	}

}
elseif( $_POST['action'] == 'save-rating' ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;

	$query = $conn->query( " SELECT id FROM `website_review_feedback` WHERE website_id = '$website_id' ; " ) ;

	if ( $query->num_rows > 0 ) {

		$data = $query->fetch_assoc() ;

		$id = $data["id"] ;

		$sql = " UPDATE `website_review_feedback` SET `rating`='$rating' , `step_completed`='2' WHERE `id`='$id' ; " ;

	}
	else {

		$sql = " INSERT INTO `website_review_feedback`( `website_id`, `rating` , `step_completed` ) VALUES ( '$website_id' , '$rating' , '2' ) ; " ;

	}


	if ( $conn->query($sql) === TRUE ) {
		echo json_encode(1) ;
	}
	else {
		echo json_encode(0) ;
	}

}
elseif( $_POST['action'] == 'save-trust-pilot' ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;

	$query = $conn->query( " SELECT id FROM `website_review_feedback` WHERE website_id = '$website_id' ; " ) ;

	if ( $query->num_rows > 0 ) {

		$data = $query->fetch_assoc() ;

		$id = $data["id"] ;

		$sql = " UPDATE `website_review_feedback` SET `trust_pilot_review`='$trust_pilot_review' , `step_completed`='3' WHERE `id`='$id' ; " ;
	}
	else {
		$sql = " INSERT INTO `website_review_feedback`( `website_id` , `trust_pilot_review` , `step_completed` ) VALUES ( '$website_id' , '$trust_pilot_review' , '3' ) ; " ;
	}


	if ( $conn->query($sql) === TRUE ) {
		echo json_encode(1) ;
	}
	else {
		echo json_encode(0) ;
	}

}
elseif( $_POST['action'] == 'save-feedback' ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;

	$query = $conn->query( " SELECT id FROM `website_review_feedback` WHERE website_id = '$website_id' ; " ) ;

	if ( $query->num_rows > 0 ) {

		$data = $query->fetch_assoc() ;

		$id = $data["id"] ;

		$sql = " UPDATE `website_review_feedback` SET `feedback`='$user_feedback' , `step_completed`='4' WHERE `id`='$id' ; " ;
	}
	else {
		$sql = " INSERT INTO `website_review_feedback`( `website_id` , `feedback` , `step_completed` ) VALUES ( '$website_id' , '$user_feedback' , '4' ) ; " ;
	}


	if ( $conn->query($sql) === TRUE ) {


		/* $query1 = $conn->query("SELECT rf.*, bw.website_url, au.firstname, au.email 
		FROM `website_review_feedback` as rf 
		JOIN boost_website as bw ON rf.website_id = bw.id 
		JOIN admin_users as au ON rf.manager_id = au.id  
		WHERE  website_id = '$website_id'");
		if($query1->num_rows > 0){
			$value = $query1->fetch_assoc();
			// echo "<pre>";
			// print_r($value); die;
			$satified_or_not  = $value['satified_or_not'];
			$rating  = $value['rating'];
			$feedback  = $value['feedback'];
			$improve  = isset($value['improve'])?$value['improve']:"NA";
			$website_url  = $value['website_url'];
			$firstname  = $value['firstname'];
			$email  = $value['email'];

			$smtp_login = $conn->query("SELECT * FROM smtp_login");
			$data_smtp = $smtp_login->fetch_assoc();
			
			$output = "<div style='padding:15px;background:#f5f5f5;color:#424242cc'
			><table style='width: 100%; max-width: 650px; background: #fff; margin: auto; border-collapse: collapse; font-family: Montserrat, Arial,sans-serif; font-size: 14px; line-height: 1.8; box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px,
			rgba(17, 17, 26, 0.1) 0px 0px 8px;'>
			<thead>
			  <tr style='border-bottom: 1px solid #e1e1e1;'>
				<td style='padding: 20px 15px;'>
				  <a href='https://websitespeedy.com/' target='_blank' style='cursor: pointer;'><img
					  style='width: 140px; height: auto; vertical-align: middle;'
					  src='https://websitespeedy.com/adminpannel/img/sitelogo_s.png' alt=''>
				  </a>
				</td>
				<td style='text-align: right; padding: 20px 15px;'><a href='https://websitespeedy.com/adminpannel/'
					target='_blank' style=' color: #f23640; font-weight: bold;'>Login</a></td>
			  </tr>
			</thead>
			<tbody>
			  <tr>
			  <td colspan='2' style='padding:20px;'>
			  <p style='margin:0 0 10px;'>User name : $firstname</p> 
			  <p style='margin:0 0 10px;'>User email : $email</p> 
			  <p style='margin:0 0 10px;'>Satisfied or Not: $satified_or_not </p> 
			  <p style='margin:0 0 10px;'>Review rating : $rating </p> 
			  <p style='margin:0 0 10px;'>Feedback : $feedback </p> 
			  <p style='margin:0 0 10px;'>Improve : $improve </p> 
			  <p style='margin:0;'>Website_url : $website_url</p>
				</td>
			  </tr>
			  <tr>
				<td colspan='2' style='padding:20px 0; text-align: left; border-top: 1px solid #e1e1e1; border-bottom: 1px solid #e1e1e1;'>
				  <h3 style='text-align: center; color: #424242; font-size: 18px; margin:0;'>Why Choose Website Speedy</h3>
				  <table style='width: 100%; border-spacing: 15px; line-height: 1.6;'>
					<tr>
					  <td colspan='2'
						style='padding: 10px; background: #e7e7e7;border-radius: 2px; vertical-align: top; width: 50%;'>
						<p style='font-size: 16px; font-weight: 600; color: #f23640;'> Mobile First Experience
						</p>
						<p style='font-size: 13px;'>Websitespeedy makes your website load 5X faster in mobile
						  devices, ensuring seamless
						  user experiences and higher engagement on smartphones and tablets.</p>
					  </td>
					  <td colspan='2'
						style='padding: 10px; background: #e7e7e7;border-radius: 2px; vertical-align: top; width: 50%'>
						<p style='font-size: 16px; font-weight: 600; color: #f23640;'> #f23640uced Bounce Rate</p>
						<p style='font-size: 13px;'>Websitespeedy eliminates render blocking and fixes other
						  critical issues resulting in #f23640uced bounce rates, increase engagement rates, and
						  conversion opportunities by 5x.</p>
					  </td>
					</tr>
					<tr>
					  <td colspan='2'
						style='padding: 10px; background: #e7e7e7;border-radius: 2px;vertical-align: top; width: 50%'>
						<p style='font-size: 16px; font-weight: 600; color: #f23640;'>Huge Saving on Ad-Spent
						</p>
						<p style='font-size: 13px;'>By optimizing website speed and user experience,
						  Websitespeedy maximizes the effectiveness of your ad campaigns, resulting in huge
						  cost savings up to 50%.
						</p>
					  </td>
					  <td colspan='2'
						style='padding: 10px; background: #e7e7e7;border-radius: 2px;vertical-align: top; width: 50%'>
						<p style='font-size: 16px; font-weight: 600; color: #f23640;'>Higher Google Rankings</p>
						<p style='font-size: 13px;'>Your site’s speed tells Google that you’re a professional,
						  well-designed store worthy of higher rankings. SEO success can be yours!</p>
					  </td>
					</tr>
				  </table>
				</td>
			  </tr>
			  <tr>
				<td colspan='2' style=' padding:20px 0; text-align: center;'>
				  <h3 style='text-align: center; color: #424242; font-size: 18px; margin:0;'>Check out these helpful
					resources</h3>
				  <table style='width: 100%; padding-top: 10px;'>
					<tr>
					  <td><a style='color: #424242;cursor: pointer;' href='https://websitespeedy.com/case-study.php'
						  target='_blank'>
						  <img src='https://websitespeedy.com/img/successstory.png' style='width:25px;'
							alt='Success Stories'><br>Success Stories
						</a>
					  </td>
					  <td colspan='2' style='border: 1px solid #e1e1e1; border-style: none solid;'>
						<a style='color: #424242;cursor: pointer;' href='https://websitespeedy.com/why-website-speed-matters.php'
						  target='_blank'>
						  <img src='https://websitespeedy.com/img/whyspeedmatters.png' style='width:25px;'
							alt='Why Speed Matters'><br>Why Speed Matters</a>
					  </td>
					  <td><a style='color: #424242;cursor: pointer;' href='https://help.websitespeedy.com/' target='_blank'><img
							src='https://websitespeedy.com/img/knowledgebase.png' alt='Knowledge Base'
							style='width:25px;'><br>Knowledge Base</a></td>
					</tr>
				  </table>
				</td>
			  </tr>
		  
			  <tr>
				<td colspan='2' style='padding: 0 15px; text-align: center;'>
				  <table style='width: 100%;'>
					<tr>
					  <td colspan='2' style='background: #e7e7e7; padding: 20px 0;'>
						<h3 style='text-align: center; color: #424242; font-size: 18px;'>Question Feedback</h3>
						<p>If you need any assistance or have questions, please contact our support team at<br>
						  <a style='color: #f2394f; font-weight: bold;     text-decoration: none;'
							href='mailto:support@websitespeedy.com'>support@websitespeedy.com</a> or call us
						  at <a style='color: #f2394f; font-weight: bold;    text-decoration: none;' href='tel:307-212-6877'>+1
							307-212-6877</a>
						</p>
					  </td>
					</tr>
				  </table>
				</td>
			  </tr>
		  
			  <tr>
				<td colspan='2' style='padding:20px 15px; text-align:center;'>
				  <p>
					<span style='margin: 0 5px;'><a href='https://www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA'
						target='blank'><img src='https://websitespeedy.com/img/youtube.png' style='width:25px;cursor: pointer;'
						  alt='Youtube'></a>
					</span>
					<span style='margin: 0 5px;'><a href='https://www.linkedin.com/company/websitespeedy/' target='blank'><img
						  src='https://websitespeedy.com/img/linkedin.png' style='width:25px;cursor: pointer;' alt='Linkedin'></a>
					</span>
					<span style='margin: 0 5px;'><a href='https://www.instagram.com/websitespeedy/' target='blank'>
						<img src='https://websitespeedy.com/img/instagram.png' style='width:25px;cursor: pointer;'
						  alt='Instagram'>
					  </a></span>
		  
					<span style='margin: 0 5px;'><a href='https://www.facebook.com/websitespeedy' target='blank'><img
						  src='https://websitespeedy.com/img/facebook.png' style='width:25px;cursor: pointer;' alt='Facebook'></a>
					</span>
		  
				  </p>
				  <p><span style='margin: 0 15px;'><a href='https://websitespeedy.com/privacy-policy.php'
						style='color: #424242; text-decoration: none;cursor: pointer;'>Privacy
						policy</a></span><span style='margin: 0 15px;'><a
						href='https://websitespeedy.com/adminpannel/unsubscribe.php'
						style='text-decoration: none; font-weight: bold; color: #424242;cursor: pointer;'>Unsubscribe</a></span><span
					  style='margin: 0 15px;'>Copyright © 2024 Websitespeedy</span></p>
				</td>
			  </tr>
			</tbody>
		  </table></div>
				";
			
			// SendGrid Email Sending
			$email = new \SendGrid\Mail\Mail();
			$email->setFrom($data_smtp["from_email"], $data_smtp["from_name"]);
			$email->setSubject('Review And Feedback');
			$email->addTo($data_smtp["from_email"], "Website Speedy");

			// Attach the PDF content as an attachment to the email
			
			// Add HTML content to the email body
			$email->addContent("text/html", $output);

			// Send the email using SendGrid
			$sendgrid = new \SendGrid($data_smtp["password"]);
			$paymentMail = $sendgrid->send($email);
		} */


		echo json_encode(1);

	}
	else {
		echo json_encode(0) ;
	}

}
elseif( $_POST['action'] == 'save-improve' ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;

	$query = $conn->query( " SELECT id FROM `website_review_feedback` WHERE website_id = '$website_id' ; " ) ;

	if ( $query->num_rows > 0 ) {

		$data = $query->fetch_assoc() ;

		$id = $data["id"] ;

		$sql = " UPDATE `website_review_feedback` SET `improve`='$user_feedback' , `step_completed`='5' WHERE `id`='$id' ; " ;
	}
	else {
		$sql = " INSERT INTO `website_review_feedback`( `website_id` , `improve` , `step_completed` ) VALUES ( '$website_id' , '$user_feedback' , '5' ) ; " ;
	}


	if ( $conn->query($sql) === TRUE ) {
		echo json_encode(1) ;
	}
	else {
		echo json_encode(0) ;
	}

}