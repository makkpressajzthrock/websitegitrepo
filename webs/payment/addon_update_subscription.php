<?php
session_start();
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../adminpannel/session.php');

require_once 'config.php'; 
 
// Include the database connection file 
include_once 'dbConnect.php'; 

// Include the Stripe PHP library 
require_once 'stripe-php/init.php'; 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY); 
    $user_id = $_SESSION['user_id'];
// $subsc_id = base64_decode($_REQUEST['sid']);
    $subsc_id=$_REQUEST['sid'];
$subsc_id_url = $_REQUEST['sid'];
// echo "<pre>";
$current_plan = ""; 
$current_plan_price = ""; 
$current_plan_interval = ""; 

    // print_r($_REQUEST); 

                $stripe = new \Stripe\StripeClient(
                  STRIPE_API_KEY
                );

         $sqlQ = "SELECT * FROM addon_site WHERE id = '$subsc_id' and user_id = '".$user_id."'"; 
        $stmt = $db->prepare($sqlQ);  
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        $prevRow = $result->fetch_assoc(); 
        // echo $subsc_id;
        // print_r($prevRow);
          if(!empty($prevRow)){ 


    $subscr_plan_id = $_REQUEST['pl'];
    $count_site =  $_REQUEST['cs'];
    $plan_frequency = 1;
     // $sqlQi = "SELECT * FROM boost_website WHERE id='' "; 
     // echo $sqlQi;
     // $web_data=$db->query($sqlQi);
     // $web=$web_data->fetch_assoc();
     // $web_id=$web['id'];
     // echo "web_id".$web_id;

     // $web_id=base64_encode($web_id);
     // echo "web_id".$subsc_id;
     // echo "web_id".$user_id;
     // echo "web_id".$web_id;

     // die();

    // Fetch current plan details from the database 
    $sqlQ = "SELECT * FROM addon WHERE id=?"; 
    $stmt = $db->prepare($sqlQ); 
    $stmt->bind_param("i", $db_id); 
    $db_id = $prevRow['plan_id']; 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    $planData = $result->fetch_assoc(); 
 
    $current_plan = $planData['name']; 
    $current_plan_price = $prevRow['price']; 
    $current_plan_interval = $planData['interval']; 


$subscriptionItems = $prevRow['subscription_items_id'];
$productsId = $prevRow['products_id'];


    // Fetch plan details from the database 
    $sqlQ = "SELECT * FROM addon WHERE id=?"; 
    $stmt = $db->prepare($sqlQ); 
    $stmt->bind_param("i", $db_id); 
    $db_id = $subscr_plan_id; 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    $planData = $result->fetch_assoc();
    // print_r($planData); 
 
    $planName = $planData['urls']."url"; 
    $planPrice = $planData['price']; 
    $planInterval = $planData['interval']; 

if($planData['description'] == "Add more 5 URLs"){
  $plan_frequency = 5;
}
else if($planData['description'] == "Add more 10 URLs"){
  $plan_frequency = 10;
}
else if($planData['description'] == "Add more 15 URLs"){
  $plan_frequency = 15;
}
else if($planData['description'] == "Add more 20 URLs"){
  $plan_frequency = 20;
}



    // Fetch plan details from the database 
     // $query_s = $db->query(" SELECT * FROM `discount`") ;

    $totalValue = $planData['price'];
    $price = $planPrice;
    // $count = $count_site;
    // while($data_s = $query_s->fetch_assoc() ) 
    //   {

        
    //     $d = $data_s['discount'];
    //     if ($data_s['sites'] == $count ) {
    //         # code...
    //              $totalValue = ($price - ($price * $d) / 100) * $count;
    //             // echo number_format((float)$totalValue, 2, '.', '');
    //         break;
    //     }
    //     elseif(strpos($data_s['sites'], "+") !== false)
    //     {
    //         $p = str_replace("+","",$data_s['sites']);
    //         if($count >= $p){
    //              $totalValue = ($price - ($price * $d) / 100) * $count;
    //             // echo number_format((float)$totalValue, 2, '.', '');
    //         break;
    //         }

    //     }


    //   }

 $planPrice =  $totalValue;
 // $planItemPrice =  $totalValue/$count;

    $planPriceCents = round($planPrice*100); 
    // $planItemPriceCents = round($planItemPrice*100); 



// die;
// echo '<pre>';

// $price_id =  'price_1MNZ1tD16Q5JOqS2NNhVpV2x';

$stripe = new \Stripe\StripeClient(
  STRIPE_API_KEY
);

 $charge_id = $prevRow['stripe_subscription_id'];

// if($prevRow['plan_id'] ==  $subscr_plan_id){
//   die;
// $plans = $stripe->subscriptionItems->update(
//   $subscriptionItems,
//   ['quantity' => $count]
// );

// }
// else{

// echo '<br>';
// echo '<br>';


 $stripe->products->update(
  $productsId,
  ['name' => $planName]
);



        try { 
            // Create price with subscription info and interval 
            $price = \Stripe\Price::create([ 
                'unit_amount' => $planPriceCents, 
                'currency' => STRIPE_CURRENCY, 
                'recurring' => ['interval' => $planInterval], 
                'product_data' => ['name' => $planName], 
            ]); 
        } catch (Exception $e) {  
            $api_error = $e->getMessage(); 
        } 
         
        if(empty($api_error) && $price){ 


// echo 'RRR<br>';
// echo $price->id;
// die;
            // $plans = $stripe->subscriptionItems->update(
            //   $subscriptionItems,
            //   [
            //      'price' => $price->id,
   //        'quantity' => $count,
   //        'proration_behavior' => none
   //      ]
            // );

        }

// die;

//$product = $stripe->prices->all();

// print_r($price );

// echo 'aaa';

// die;



 
// echo '<br>ddd<br>';
// die;

            
      $count = $count_site;
            $subscription = $stripe->subscriptions->update(
        $charge_id,
        [
        'items' => [
              [
                'id' => $subscriptionItems,
                'price' => $price->id,
                
              ],     
            ], 
             // 'proration_behavior' => none,    
                   
            'description' => "Addon Plan Changed from $current_plan/".ucfirst($planInterval)." to $planName/".ucfirst($planInterval)." - $current_plan_price ".STRIPE_CURRENCY." To $planPrice ".STRIPE_CURRENCY,
        ]
      );


               $sqlQ = "UPDATE addon_site SET addon_id='$subscr_plan_id', addon_count='$plan_frequency', paid_amount = '$planPrice' WHERE id = '$subsc_id'  and user_id='$user_id'";
              // die; 
               // echo " sqlQ".$sqlQ;

                $stmt = $db->prepare($sqlQ); 
                $update = $stmt->execute();     

              //  $sqlQ = "UPDATE boost_website SET plan_id='$subscr_plan_id' WHERE subscription_id = '$subsc_id'  and manager_id='$user_id'";
              //  // echo " sqlQ".$sqlQ;
              // // die; 
              //   $stmt = $db->prepare($sqlQ); 
              //   $update = $stmt->execute();

            // print_r($subscription);
   //    die;
  $_SESSION['success'] = "Plan Changes Successfully." ;
 header("location: https://ecommerceseotools.com/ecommercespeedy/adminpannel/addon.php?project=".$_REQUEST['project']);
 die() ;
                // echo " success";
        






          }
          else{
  $_SESSION['error'] = "Something Went Wrong." ;
  header("location: https://ecommerceseotools.com/ecommercespeedy/adminpannel/addon.php?project=".$_REQUEST['project']);
  die() ;            
          }



?>