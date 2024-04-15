<?php
$country = "USD";
require_once 'config.php'; 
 
// Include the database connection file 
include_once 'dbConnect.php'; 

// Include the Stripe PHP library 
require_once 'stripe-php/init.php'; 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY); 


         $sqlQ = "SELECT * FROM user_subscriptions_log WHERE flag = 0 limit 10"; 
        $stmt = $db->prepare($sqlQ);  
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        $prevRow = $result->fetch_assoc(); 

          if(!empty($prevRow)){ 

				$customer_id = $prevRow['stripe_customer_id'];
				$invoice_id = $prevRow['latest_invoice'];

				$stripe = new \Stripe\StripeClient(
				  STRIPE_API_KEY
				);
				$datas = $stripe->paymentIntents->search([
				  'query' => 'customer:\''.$customer_id.'\' ',
				]);

				echo '<pre>';
// print_r($datas);
				foreach ($datas->data as $data) {
					if($data->invoice == $invoice_id){
							print_r($data);
					    $paidAmount = $data->amount; 
				        $paidAmount = ($paidAmount/100); 
				        $paidCurrency = $data->currency; 
				        $payment_status = $data->status; 

							echo $sqlQ = "UPDATE user_subscriptions_log SET paid_amount = '$paidAmount', paid_amount_currency = '$paidCurrency' ,status = '$payment_status' , flag = 1 where latest_invoice = '$invoice_id'"; 
				           $stmt = $db->prepare($sqlQ); 
				           
				             $stmt->execute(); 

					}

					# code...
				}

}

