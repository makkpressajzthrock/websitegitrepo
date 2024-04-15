<?php 
session_start();
// Include the configuration file 
require_once 'config.php'; 
 
// Include the database connection file 
include_once 'dbConnect.php'; 
 
// Include the Stripe PHP library 
require_once 'stripe-php/init.php'; 
 
// Set API key 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY); 
 
// // Retrieve JSON from POST body 
// $jsonStr = file_get_contents('php://input'); 
// $jsonObj = json_decode($jsonStr); 
 

// Retrieve customer info 
try {   
    $customer = \Stripe\Customer::allSources("cus_NBYN6ZzR3VNnWp" );  

    echo "<pre>";
    print_r($customer) ;

}catch(Exception $e) {   
    $api_error = $e->getMessage();   
} 



$stripe = new \Stripe\StripeClient(STRIPE_API_KEY);
// $kk = $stripe->subscriptions->retrieve(
//   'sub_1MRBGaD16Q5JOqS29EmGEr57'
// );

$kk = $stripe->paymentIntents->retrieve(
  'pi_3MRBGbD16Q5JOqS21x9U3baB'
);
// $kk = \Stripe\PaymentIntents::retrieve("pi_3MRBGbD16Q5JOqS21x9U3baB" );  

$user_card_detail = $kk->charges->data[0]->payment_method_details->card ;

print_r($user_card_detail->exp_month) ;