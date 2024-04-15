<?php

// https://ecommerceseotools.com/ecommercespeedy/adminpannel/cron/subscription-expire-mail.php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;
require_once('../smtp/PHPMailerAutoload.php');


// Include autoloader 
require_once '../dompdf/autoload.inc.php'; 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 

ob_clean();


// echo "<pre>";

$id = base64_decode($_GET['viewinvoice']);
$seles = "select * from  user_subscriptions where id='$id ' ";
$runs= mysqli_query($conn,$seles);
$run_qrs2=mysqli_fetch_array($runs,MYSQLI_BOTH);

 $user_id = $run_qrs2['user_id'] ;
// $id = base64_decode($_GET['viewinvoice']);

 $userSubscription = "SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id WHERE user_subscriptions.id = '$id' AND admin_users.id = '$user_id' ";

// $userSubscription = "SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id WHERE user_subscriptions.id = '$id'  ";

$user_data = mysqli_query($conn,$userSubscription);
$userData=mysqli_fetch_assoc($user_data);

// get plan details
$p_query = $conn->query(" SELECT id , name , s_type , status , page_view FROM `plans` WHERE `id` = 25 ") ;
$p_data = ( $p_query->num_rows > 0 ) ? $p_query->fetch_assoc() : [] ;

// print_r($userData) ;

$pdf_name = $p_data['s_type']."-billing-".date("M_d_Y").".pdf" ;
// print_r($pdf_name) ; echo "<br>";
// // $pdf_name = base64_encode($pdf_name) ;
// print_r($userData) ;
// echo "<hr>";
// die();

// $userData['userSubscriptionId']

$billing_no = encrypt_number($userData['userSubscriptionId']) ;




$userName = $userData['firstname'].' '.$userData['lastname'];
$userEmail = $userData['email'];
$paymentMethod = $userData['payment_method'];
$paidAmount = $userData['paid_amount'];
$startPlan = $userData['plan_period_start'];
$endPlan = $userData['plan_period_end'];
$currentDate = date("Y-m-d");
$address1 = $userData['address_line_1'];
$address2 = $userData['address_line_2'];

$city_query = $conn->query(" SELECT * FROM `list_cities` WHERE `id` = '".$userData['city']."' ") ;
$city = ($city_query->num_rows > 0) ? $city_query->fetch_assoc()["cityName"] : '' ;

$state_query = $conn->query(" SELECT * FROM `list_states` WHERE `id` = '".$userData['state']."' ") ;
$state = ($state_query->num_rows > 0) ? $state_query->fetch_assoc()["statename"] : '' ;

$country_query = $conn->query(" SELECT * FROM `list_countries` WHERE `id` = '".$userData['country']."'") ;
$country = ($country_query->num_rows > 0) ? $country_query->fetch_assoc()["name"] : '' ;

$created_on = date("M d, Y",strtotime($userData["plan_period_start"])) ;

$image=file_get_contents("../img/sitelogo_s.png");
$imagedata=base64_encode($image);
// $imgpath='<img src="'.HOST_URL.'adminpannel/img/sitelogo.jpg">';
$imgpath='<img src="data:image/png;base64, '.$imagedata.'">';

// $message = '';
$output = '<!DOCTYPE html>
<html>
<body style="font-family: sans-serif;">
	<div style="height:200px;">

			<div class="billing" style="  float: left; width: 50%;">
			<div style="width: 45%;display: inline-block;margin-right: 5px; padding-right:5px; border-right: 1px solid; font-size:14px;">
				<div style="font-size:20px; font-weight:bold;" >Bill <span style="color: #2c6ecb;" >#'.$billing_no.'</span></div>
				Created on '.$created_on.'
			</div>
			<div style="width: 50%;display: inline-block;  font-size:16px;">
				30-day billing cycle<br>
				Jan '.$created_on.'
			</div>	
			</div>

			<div class="bill_address"   style="float: right; width: 25%; text-align: right; font-size:14px;">
				'.$imgpath.'
				<br><br>
				Website Speedy Pvt. Ltd.<br> 28/6, 3rd Floor Double Storey Building, Ashok <br>Nagar, Tilak Nagar (West Delhi), New Delhi, Delhi 110018 GSTIN 9921SGP29003OS0
		
				</div>
	</div>
	<div style="height:60px;  font-size:14px;">
		TOTAL DUE
		<h2>'.$userData["paid_amount"].'    <span style="font-size:14px;">'.$userData["paid_amount_currency"].'</span></h2>
	</div>

<div style="height:30px; font-size:14px;">
	OVERVIEW
</div>
<table style="width: 100%; height: 150px; border-collapse: collapse;margin-bottom:20px;">';

// get subscription websites ---------------
// echo " SELECT * FROM `boost_website` WHERE `subscription_id` = '".$userData['userSubscriptionId']."' " ;
$bw_query = $conn->query(" SELECT * FROM `boost_website` WHERE `subscription_id` = '".$userData['userSubscriptionId']."' ") ;

if ( $bw_query->num_rows > 0 ) 
{
	$bw_data = $bw_query->fetch_all(MYSQLI_ASSOC) ;
	foreach ($bw_data as $boost_website) 
	{
		// print_r($boost_website) ;

		$website_name = empty($boost_website["website_name"]) ? parse_url($boost_website["website_url"])["host"] : $boost_website["website_name"] ;

		$output .= '<tr style="border-top: 1px solid lightgray; border-bottom: 1px solid lightgray;"><th>'.$boost_website["platform"].'</th><td style="height:60px;">'.$website_name.'<br><a href="'.$boost_website["website_url"].'">'.$boost_website["website_url"].'</a></td></tr>' ;
	}

}

$output .= '</table>

<div style="display:flex; width:100%;">
	<div style="display:inline-block; width:32%; height:200px; " >
		<h4>Account billed :</h4>
		<div style="font-size:14px;">
			<div>'.$userName.'<br>'.$userEmail.'<br>'.$userData["phone"].'</div>
			<div>'.$userData["address_line_1"].','.$userData["address_line_2"].','.$city.'<br>
			'.$state.','.$country.','.$userData["zipcode"].'</div>
			</div>
	</div>
	<div  style="display:inline-block; width:32%; height:200px;">
		<h4>Payment Method :</h4>
		<div style="font-size:14px;">
			<div>'.$paymentMethod.'</div>
			<div>Plan Duration :'.date("M d",strtotime($userData["plan_interval"])).'</div>
			</div>
	</div>
	<div  style="display:inline-block; width:32%; height:200px;">
	
		<h4>Payment status :</h4>
		<div style="font-size:14px;">
			<div>Bill created '.date("M d",strtotime($userData["plan_period_start"])).'</div>
			<div>Payment will be retried '.date("M d",strtotime($userData["plan_period_end"])).'</div>
			</div>
	</div>
</div>
</body>
</html>';

// echo $output ;
// die() ;

// $dompdf = new Dompdf();
// 				$dompdf->loadHtml($content); 
// 				$dompdf->setPaper('A4', 'portrait'); 
// 				$dompdf->render(); 
// 				$output = $dompdf->output();
// 				file_put_contents('installation-documents/'.$pdf_name, $output);



// Instantiate and use the dompdf class 
$dompdf = new Dompdf();
$dompdf->loadHtml($output); 
$dompdf->setPaper('A4', 'portrait'); 
$dompdf->render(); 
$dom_output = $dompdf->output();
file_put_contents('invoices/'.$pdf_name, $dom_output);
// $dompdf->stream($pdf_name, array("Attachment" => 0));

// get all active subscriptions ---------
$user_subscriptions = getTableData( $conn , " user_subscriptions " , " is_active = 1 AND status LIKE 'succeeded' " , "" , 1 ) ;
foreach ($user_subscriptions as $key => $subscription_data) 
{
	
$sele = "select * from  admin_users where id='".$subscription_data["user_id"]." ' ";
$run= mysqli_query($conn,$sele);
$run_qr=mysqli_fetch_array($run,MYSQLI_BOTH);



$name=$run_qr['firstname'];
// $email=$run_qr['email'];
// print_r($email);
// die();
// $email="karan.makkpress@gmail.com";
// echo "email : ".$email."<hr>" ;
// 	print_r($name);
 // die();




$view_invoice = HOST_URL."adminpannel/billing-dashboard.php" ;

// get email content from database ----------

$emailContent = getEmailContent( $conn , 'Invoice' ) ;


// if(($days_left >=3)?$emailContent:$emailContent2)

// set email variable values ----------------
$emailVariables = array("name" => $userName , "view-invoice" => $view_invoice  );

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
$mail->AddAddress($userEmail);
$mail->AddAttachment('invoices/'.$pdf_name);
$mail->SMTPOptions=array('ssl'=>array( 'verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed'=>false ));
$mail->Send() ;

if($mail==true){

	header("location:../payments.php");
	$_SESSION["success"] = "Invoice Send Successfully!" ; 

}
else{

	header("location:../payments.php");
	$_SESSION["error"] = "Invoice Not Send!" ; 
}



break;

}






?>