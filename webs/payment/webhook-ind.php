

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
$endpoint_secret = 'whsec_8tFggLHuJsCz4rTZPAiQnZk21RGlErTY';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

  $sqlQ = "INSERT INTO strip_webhook(data,type,currency)  VALUES (?,'Subscription','IND')"; 
            $stmt = $db->prepare($sqlQ); 
            $stmt->bind_param("s", $db_user_data); 
            $db_user_data = $payload;
            $insert = $stmt->execute(); 
             
            if($insert){
              //echo 'Saved';
            }


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
// echo $event->type;
switch ($event->type) {
  case 'customer.subscription.deleted':
    $subscription = $event->data->object;
  case 'customer.subscription.updated':
    $subscription = $event->data->object;
  default:
    echo 'Received unknown event type ' . $event->type;
}

 

$data = json_decode($payload);

echo '<pre>';
$subscription = $data->data->object;

// print_r($subscription);
sleep(5);

        $sqlQ = "SELECT * FROM user_subscriptions WHERE stripe_subscription_id = '".$subscription->id."'"; 
        $stmt = $db->prepare($sqlQ);         
        $stmt->execute(); 
        $result = $stmt->get_result(); 

        $prevRow = $result->fetch_assoc(); 
        print_r($prevRow);

        $user_id = "";
        $plan_id = "";
        $stripe_payment_intent_id = "";
        $user_id = "";

          // print_r($prevRow);

        if(!empty($prevRow)){ 
          // print_r($prevRow);
        $user_id = $prevRow['user_id'];
        $plan_id =  $prevRow['plan_id'];
        $stripe_payment_intent_id =  $prevRow['stripe_payment_intent_id'];
        }


$current_period_start = $current_period_end = $cancled_at = ''; 
        if(!empty($subscription)){ 
            $created = date("Y-m-d H:i:s", $subscription->created); 
            $current_period_start = date("Y-m-d H:i:s", $subscription->current_period_start); 
            $current_period_end = date("Y-m-d H:i:s", $subscription->current_period_end); 
            $cancled_at = date("Y-m-d H:i:s", $subscription->canceled_at); 
        } 




 
           $db_user_id = $user_id; 
           $db_plan_id = $plan_id; 
           $db_stripe_subscription_id = $subscription->id; 
           $db_stripe_customer_id = $subscription->customer; 
           $latest_invoice =  $subscription->latest_invoice;
           $db_stripe_payment_intent_id = $stripe_payment_intent_id; 
            
           $db_created = $created; 
           $db_plan_period_start = $current_period_start; 
           $db_plan_period_end = $current_period_end; 
           $description = $subscription->description;
           $subscriptions_status = $subscription->status;
           $sqlQ = "";
    if($subscription->canceled_at!="" && $subscription->canceled_at != null && $subscription->canceled_at != "null"){
         echo  $sqlQ = "INSERT INTO user_subscriptions_log (user_id, plan_id, stripe_subscription_id, stripe_customer_id, latest_invoice, stripe_payment_intent_id, created, plan_period_start, plan_period_end,description,cancled_at,subscriptions_status) VALUES ('$db_user_id', '$db_plan_id', '$db_stripe_subscription_id', '$db_stripe_customer_id', '$latest_invoice', '$db_stripe_payment_intent_id',  '$db_created', '$db_plan_period_start', '$db_plan_period_end','$description','$cancled_at','$subscriptions_status')"; 
         }
         else{
        echo   $sqlQ = "INSERT INTO user_subscriptions_log (user_id, plan_id, stripe_subscription_id, stripe_customer_id, latest_invoice, stripe_payment_intent_id, created, plan_period_start, plan_period_end,description,subscriptions_status) VALUES ('$db_user_id', '$db_plan_id', '$db_stripe_subscription_id', '$db_stripe_customer_id', '$latest_invoice', '$db_stripe_payment_intent_id',  '$db_created', '$db_plan_period_start', '$db_plan_period_end','$description','$subscriptions_status')"; 

         }
            $stmt = $db->prepare($sqlQ); 
           
            $insert = $stmt->execute(); 
            if($insert){ 
              echo "cre";
            }
            else{
              echo "else";

            }

           $sqlQ = "UPDATE user_subscriptions SET plan_period_start = '$db_created', plan_period_end = '$db_plan_period_end', script_available = 1 WHERE stripe_subscription_id = '$subscription->id'"; 
                $stmt = $db->prepare($sqlQ); 
           
             $stmt->execute();  

sleep(1);


http_response_code(200);