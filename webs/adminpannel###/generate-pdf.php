<?php

require_once('config.php');
require_once('inc/functions.php') ;

// Include autoloader 
require_once 'dompdf/autoload.inc.php'; 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 

ob_clean();

// $file_name = '/var/www/html/ecommercespeedy/adminpannel/pdf/pdf.pdf';
// // unlink($file_name);
// sleep(10);

// echo "<pre>";

$user_id = $_SESSION['user_id'] ;
$id = base64_decode($_GET['id']);

$userSubscription = "SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id WHERE user_subscriptions.id = '$id' AND admin_users.id = '$user_id' ";

// $userSubscription = "SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id WHERE user_subscriptions.id = '$id'  ";

$user_data = mysqli_query($conn,$userSubscription);
$userData=mysqli_fetch_assoc($user_data);



// get plan details
$p_query = $conn->query(" SELECT id , name , s_type , status , page_view FROM `plans` WHERE `id` = 25 ") ;
$p_data = ( $p_query->num_rows > 0 ) ? $p_query->fetch_assoc() : [] ;



$p_query_add = $conn->query(" SELECT * FROM `billing-address` WHERE `manager_id` = '$user_id' ") ;
$data_add = ( $p_query_add->num_rows > 0 ) ? $p_query_add->fetch_assoc() : [] ;

$gstnumber = "";
if($userData['gst']!=""){
	$gstnumber = "GST Number: ".$userData['gst'];
}

$vatnumber = "";
if($userData['vat']!=""){
	$vatnumber = "VAT Number: ".$userData['vat'];
}


// print_r($p_data) ;

$pdf_name = $p_data['s_type']."billing-".date("M_d_Y") ;
// print_r($pdf_name) ; echo "<br>";
// $pdf_name = base64_encode($pdf_name) ;
// print_r($userData) ;
// echo "<hr>";


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



$plans_query = $conn->query(" SELECT * FROM `plans` WHERE `id` = '".$userData['plan_id']."' ") ;


$pn = $plans_query->fetch_assoc();

 $plans_name = $pn["name"];

$plans_name_interval = $pn["interval"];

if($plans_name_interval == 'year'){
	$plans_name_interval = 'Yearly';
}

if($plans_name_interval == 'month'){
	$plans_name_interval = 'Monthly';
}



$city_query = $conn->query(" SELECT * FROM `list_cities` WHERE `id` = '".$userData['city']."' ") ;
$city = ($city_query->num_rows > 0) ? $city_query->fetch_assoc()["cityName"] : '' ;

$state_query = $conn->query(" SELECT * FROM `list_states` WHERE `id` = '".$userData['state']."' ") ;
$state = ($state_query->num_rows > 0) ? $state_query->fetch_assoc()["statename"] : '' ;

$country_query = $conn->query(" SELECT * FROM `list_countries` WHERE `id` = '".$data_add['country']."'") ;
$country = ($country_query->num_rows > 0) ? $country_query->fetch_assoc()["name"] : '' ;

$created_on = date("M d, Y",strtotime($userData["plan_period_start"])) ;
$created_on_end = date("M d, Y",strtotime($userData["plan_period_end"])) ;

$image=file_get_contents("img/sitelogo_s.png");
$imagedata=base64_encode($image);
// $imgpath='<img src="'.HOST_URL.'adminpannel/img/sitelogo.jpg">';
$imgpath='<img width="130px" src="data:image/png;base64, '.$imagedata.'">';

$company_name = "MAKKPRESS TECHNOLOGIES PRIVATE LIMITED" ;
$company_gstnumber = "07AAJCM3859K1ZX" ;

$header_gst_show = '' ;
if ( strtolower($userData["paid_amount_currency"]) != "usd" ) {
	$header_gst_show = '<br><b>GST:</b> '.$company_gstnumber; 	
}

// $message = '';
$output = '<!DOCTYPE html>
<html>
<body style="font-family: sans-serif;">
	<div style="height:250px; ">

			<div class="billing" style="  float: left; width: 50%;"> 
			    <div style="width: 45%;display: inline-block;margin-right: 5px; padding-right:5px; border-right: 1px solid; font-size:14px;">
				  <div style="font-size:20px; font-weight:bold;" >Bill <span style="color: #2c6ecb;" >#'.$billing_no.'</span></div>
				  Created on '.$created_on.'
			    </div>


			    <div style="width: 50%;display: inline-block;  font-size:16px;">
				   Billing cycle: '.$plans_name_interval.'<br>
				   Started on: '.$created_on.'
			    </div>	
			</div>

			<div class="bill_address"   style="float: right; width: 55%; text-align: right; font-size:14px; line-height:22px;">
				'.$imgpath.'
				<br><br>
				Makkpress Technologies Pvt. Ltd.<br> 28/6, 3rd Floor Double Storey Building,<br> Ashok Nagar, Tilak Nagar,<br> New Delhi, Delhi 110018 '.$header_gst_show.'
		
			</div>
	</div>
	<div style="height:30px;  font-size:14px; text-align:right; margin-top:50px;">
	    <span><b>TOTAL DUE</b></span>&nbsp;
    </div>
     <div class="sss" style="border: 1px solid lightgray;  border-style: solid none; height: 210px">
	<div style="height:210px; float: left;  line-height: 22px;
    font-size: 14px;  padding:5px 0 10px; ">';?>
<?php // get subscription websites ---------------
// echo " SELECT * FROM `boost_website` WHERE `subscription_id` = '".$userData['userSubscriptionId']."' " ;
 $bw_query = $conn->query(" SELECT * FROM `boost_website` WHERE `subscription_id` = '".$userData['userSubscriptionId']."' and plan_type = 'Subscription' ") ;

if ( $bw_query->num_rows > 0 ) 
{
	$bw_data = $bw_query->fetch_all(MYSQLI_ASSOC) ;
	foreach ($bw_data as $boost_website) 
	{
		// print_r($boost_website) ;

		$website_name = empty($boost_website["website_name"]) ? parse_url($boost_website["website_url"])["host"] : $boost_website["website_name"] ;

		$output .= 'Platform : '.$boost_website["platform"].'<br> Website Name: '.$website_name.'<br>Website URL: '.$boost_website["website_url"]."<br>" ;
	}

} ?>
<?php

$currency  = strtoupper($userData["paid_amount_currency"]); 
// $currency ="";
$summ = '
		<table style="margin-left:5px;width:100%;">
			<tr style="width:100%;">
				<td>Plan Price</td>
				<td style="text-align:end;padding-left:20px">'.number_format($userData['plan_price'],2).' '.$currency.'</td>
			</tr>';

if($userData['discount_amount']!="0"){

 $discount = $conn->query(" SELECT code FROM `coupons` WHERE `strip_coupon_id` = '".$userData['discount_code_id']."' ") ;
 $bw_data_data_d = $discount->fetch_assoc() ;

$summ .= '
		 
			<tr style="width:100%;">
				<td>Discount<br> ('.$bw_data_data_d['code'].')</td>
				<td style="text-align:end;padding-left:20px">-'.number_format($userData['discount_amount'],2).' '.$currency.'</td>
			</tr>';

}


if($userData['total_tax']!=""){
 $tax = $conn->query(" SELECT tax_rate,tax_name FROM `add-tax` WHERE `country_name` = 'India' ") ;
 $bw_data_data_tax = $tax->fetch_assoc() ;

$summ .= '
		 
			<tr style="width:100%;">
				<td>'.$bw_data_data_tax['tax_name'].' ('.$bw_data_data_tax['tax_rate'].'%)</td>
				<td style="text-align:end;padding-left:20px">'.number_format($userData['total_tax'],2).' '.$currency.'</td>
			</tr>';

}


$summ .='
		</table>
';


	$output .='Plan Name: '.$plans_name.' <br>
	Start Time: '.$created_on.' <br>
	</div>

	    <div style="  border-left: 1px solid lightgray; float: right; width: 35%; height: 150px;">
	         <div style="height:50px;  font-size:14px; text-align:right; margin-top:10px;">
		        
		         
		        <div>
			        '.$summ.'
		        </div>

	         </div>
	    </div>
		
	</div>

	<div style="height:50px;  font-size:14px; text-align:right; border-bottom:1px solid lightgray; ">
		<div style="padding-top: 15px; float: left; text-align: end; width: 100px;"> <b>TOTAL :</b> </div>
		<div style="font-size:24px; width:35%; height:40px; border-left:1px solid lightgray; float:right; padding-top:10px;" >'.$userData["paid_amount"].'    <span style="font-size:14px; text-transform:uppercase;">'.$userData["paid_amount_currency"].'</span>


		</div>

		

	</div>

	</div>

    <div style="height:30px; font-size:14px; margin-top:20px;">
	   <b>OVERVIEW</b>
    </div>

  	<table style="width: 100%; height: 120px; border-collapse: collapse;margin-bottom:20px;">';



$output .= '</table>

<div style="display:flex; width:100%;">
	<div style="display:inline-block; width:31%; height:160px; " >
		<h4>Account Billed: </h4>
		<div style="font-size:14px;">
			<div>
			    '.$userName.'<br>
			    '.$userEmail.'<br>
			     '.$userData["phone"].'</br>
			</div>
			<div>
			     '.$data_add["address"].','.$data_add["address_2"].','.$data_add["zip"].'<br>
			     '.$country.','.$data_add["zip"].'<br>
			</div>

			<div>
			     <br>
					'.$gstnumber.'
					'.$vatnumber.'
					<br>
			</div>

			</div>
	</div>
	<div  style="display:inline-block; width:31%; height:160px; padding: margin:10px;">
		<h4>Payment Method: </h4>
		<div style="font-size:14px;">
			<div>'.$paymentMethod.'</div>
			 
			</div>
	</div>
	<div  style="display:inline-block; width:31%; height:160px;">
	
		<h4>Payment Status: </h4>
		<div style="font-size:14px;">
			<div>Bill created on: '.date("d M, Y",strtotime($userData["plan_period_start"])).'</div>
			<div>Payment will be retried: '.date("d M, Y",strtotime($userData["plan_period_end"])).'</div>
			</div>
	</div>
</div>' ;


if ( strtolower($userData["paid_amount_currency"]) != "usd" ) {
$output .= '<footer style="display:inline-block; font-size:14px;">
	<table>
	<tr>
	<td><h4>'.$company_name.'</h4></td>
	<td style="padding: 0 4px;">|</td>
	<td><b>GST:</b> '.$company_gstnumber.'</td>
	</tr>
	</table>
</footer> '; 	
}





$output .= '</body>
</html>';

// echo $output ;
// die() ;



// Instantiate and use the dompdf class 
$dompdf = new Dompdf();
$dompdf->loadHtml($output); 
$dompdf->setPaper('A4', 'portrait'); 
$dompdf->render(); 
$dompdf->stream($pdf_name, array("Attachment" => 0)); 

?>