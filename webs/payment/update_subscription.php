<?php

session_start();
error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1);

include('../adminpannel/session.php');

require_once 'config.php'; 

// Include the database connection file 
include_once 'dbConnect.php'; 

// Include the Stripe PHP library 
require_once 'stripe-php/init.php'; 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY); 
$user_id = $_SESSION['user_id'];
$subsc_id = base64_decode($_REQUEST['sid']);
$subsc_id_url = $_REQUEST['sid'];

$current_plan = ""; 
$current_plan_price = ""; 
$current_plan_interval = ""; 


$stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

$sqlQ = "SELECT * FROM user_subscriptions WHERE id = '$subsc_id' and user_id = '".$user_id."' ; " ; 

echo "sqlQ : ".$sqlQ ;

$stmt = $db->prepare($sqlQ);  
$stmt->execute(); 
$result = $stmt->get_result(); 
$prevRow = $result->fetch_assoc(); 

print_r($result) ;
// die() ;



if(!empty($prevRow)) {

    $subscr_plan_id = $_REQUEST['pl'];
    $count_site =  $_REQUEST['cs'];
    $plan_frequency = 1;

    // Fetch current plan details from the database 
    $sqlQ = "SELECT * FROM plans WHERE id=?"; 
    $stmt = $db->prepare($sqlQ); 
    $stmt->bind_param("i", $db_id); 
    $db_id = $prevRow['plan_id']; 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    $planData = $result->fetch_assoc(); 

    $current_plan = $planData['name']; 
    $current_plan_price = $prevRow['paid_amount']; 
    $current_plan_interval = $planData['plan_interval']; 


    $subscriptionItems = $prevRow['subscription_items_id'];
    $productsId = $prevRow['products_id'];


    // Fetch plan details from the database 
    $sqlQ = "SELECT * FROM plans WHERE id=?"; 
    $stmt = $db->prepare($sqlQ); 
    $stmt->bind_param("i", $db_id); 
    $db_id = $subscr_plan_id; 
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    $planData = $result->fetch_assoc(); 

    $planName = $planData['name']; 
    $planPrice = $planData['price']; 
    $planInterval = $planData['interval']; 

    if($planData['plan_frequency'] == "1 Website"){
        $plan_frequency = 1;
    }
    else if($planData['plan_frequency'] == "2 Websites"){
        $plan_frequency = 2;
    }
    else if($planData['plan_frequency'] == "3+ Websites"){
        $plan_frequency = 'Unlimited';
    }

    // Fetch plan details from the database 
    $query_s = $db->query(" SELECT * FROM `discount`") ;

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

    $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

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


    // die;

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

    } 
    catch (Exception $e) {  

        $api_error = $e->getMessage(); 


    } 

    if(empty($api_error) && $price) { 


        print_r($price);
        //  sleep(1);

        $price_id = $price->id;  

        // die("price_id : ".$price_id) ;
        // $price_id = "price_1MXLvaD16Q5JOqS2fzyGLqr5";           
        echo     $count = $count_site;

        echo "<hr>" ;

        try { 
            // Create price with subscription info and interval 
            $subscription = $stripe->subscriptions->update(
                $charge_id,
                [
                    'items' => [
                        ['id' => $subscriptionItems,
                        'price' => $price_id, ],     
                    ], 
                    'description' => "Plan Changed from $current_plan/".ucfirst($planInterval)." to $planName/".ucfirst($planInterval)." - $current_plan_price ".STRIPE_CURRENCY." To $planPrice ".STRIPE_CURRENCY,
                ]
            );

            print_r($subscription) ;

        } 
        catch (Exception $e) {  

            $api_error2 = $e->getMessage(); 
            print_r($api_error2) ;

        } 

        if(empty($api_error2) && $subscription) {

            // print_r($subscription);
            // die;

            $sqlQ = "UPDATE user_subscriptions SET plan_id='$subscr_plan_id', site_count='$plan_frequency', paid_amount = '$planPrice' WHERE id = '$subsc_id'  and user_id='$user_id'";
            // die; 
            $stmt = $db->prepare($sqlQ); 
            $update = $stmt->execute();     

            $sqlQ = "UPDATE boost_website SET plan_id='$subscr_plan_id' WHERE subscription_id = '$subsc_id'  and manager_id='$user_id'";
            // die; 
            $stmt = $db->prepare($sqlQ); 
            $update = $stmt->execute();

            // print_r($subscription);
            //    die;
            $_SESSION['success'] = "Plan Changes Successfully." ;
            header("location: https://websitespeedy.com/adminpannel/manage-website.php?sid=".$subsc_id_url);
            die() ;


        }
        else {

            $_SESSION['error'] = $api_error2 ;
            header("location: https://websitespeedy.com/plan.php?sid=".$subsc_id_url);
            die();          

        }

    }
    else {

        $_SESSION['error'] = "Something Went Wrong." ;
        header("location: https://websitespeedy.com/plan.php?sid=".$subsc_id_url);
        die(); 
    }

}
else{

    $_SESSION['error'] = "Something Went Wrong." ;
    header("location: https://websitespeedy.com/plan.php?sid=".$subsc_id_url);
    die();

}



?>