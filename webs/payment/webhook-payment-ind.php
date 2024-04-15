<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242
$country = "IND";
require_once 'config.php'; 
 
// Include the database connection file 
include_once 'dbConnect.php'; 

// Include the Stripe PHP library 
require_once 'stripe-php/init.php'; 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY); 

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_8kXgNsDwbb7jM4wNRwyDJfe1KAN3Va5T';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the event
switch ($event->type) {
  case 'payment_intent.succeeded':
    $paymentIntent = $event->data->object;
  // ... handle other event types
  default:
    echo 'Received unknown event type ' . $event->type;
}

   $sqlQ = "INSERT INTO strip_webhook(data,type,currency)  VALUES (?,'Payment Succeeded','IND')"; 
            $stmt = $db->prepare($sqlQ); 
            $stmt->bind_param("s", $db_user_data); 
            $db_user_data = $payload;
            $insert = $stmt->execute(); 
             
            if($insert){
              echo 'Saved';
            }
            // sleep(3);
                        // die();
           $payload_data= json_decode($db_user_data);
          $subscription = $payload_data->data->object;

           $created = date("Y-m-d H:i:s", $subscription->created);
           $payment_id=$subscription->id;
           $customer_id=$subscription->customer; 
           $amount=$subscription->amount;
           $billing_details=$subscription->charges->data[0]->billing_details;
           
           $phone=$billing_details->phone;
           $email=$billing_details->email;
           $name=$billing_details->name;
           $currency=$subscription->currency;
    
           $description=$subscription->description;

           $sql="INSERT INTO `payment_info`( payment_id, amount, email, name, phone, currency, description, customer_id, created_time) VALUES('$payment_id','$amount', '$email', '$name', '$phone', '$currency', '$description', '$customer_id',' $created')";
           // echo "sql".$sql;
           $db->query($sql);


// update the subscription details in user_subscriptions table 

$us_query = $db->query(" SELECT * FROM user_subscriptions WHERE stripe_customer_id LIKE '$customer_id' ORDER BY id DESC LIMIT 1 ; ") ;

if ( $us_query->num_rows > 0 ) {

    $us_data = $us_query->fetch_assoc() ;

    $us_id = $us_data["id"] ;

    $amount = (float)$amount/100 ;

    $db->query(" UPDATE user_subscriptions SET stripe_payment_intent_id = '$payment_id' , paid_amount = '$amount' WHERE id = '$us_id' ; ") ;

}
