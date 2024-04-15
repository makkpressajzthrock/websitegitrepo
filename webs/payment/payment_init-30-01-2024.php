<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
session_start();
// Include the configuration file 
require_once 'config.php';

// Include the database connection file 
$country = "Other";
include_once 'dbConnect.php';

// Include the Stripe PHP library 
require_once 'stripe-php/init.php';



// Set API key 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

// Get user ID from current SESSION 
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

if($userID==1){
     echo json_encode(['error' => "Inviled token please reload the page."]);
     exit();
}

 $siteId = $_SESSION['siteId'];
$count_site =  $_SESSION["count_site"];

 

include_once 'dbConnect.php';
// die;
// Include the Stripe PHP library 
require_once 'stripe-php/init.php';
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);
// $subsc_id_url = $_REQUEST['sid'];




if ($jsonObj->request_type == 'create_customer_subscription') {
    $subscr_plan_id = !empty($jsonObj->subscr_plan_id) ? $jsonObj->subscr_plan_id : '';

    $name = !empty($jsonObj->name) ? $jsonObj->name : '';
    $email = !empty($jsonObj->email) ? $jsonObj->email : '';
    $coupon_id = !empty($jsonObj->coupon_id) ? $jsonObj->coupon_id : '';
    $vat_number = !empty($jsonObj->vat_number) ? $jsonObj->vat_number : '';

    // Fetch plan details from the database 
    $sqlQ = "SELECT * FROM plans WHERE id=?";
    $stmt = $db->prepare($sqlQ);
    $stmt->bind_param("i", $db_id);
    $db_id = $subscr_plan_id;
    $stmt->execute();
    $result = $stmt->get_result();
    $planData = $result->fetch_assoc();

    $planName = $planData['name'];
    $planPrice = $planData['us_main_p'];
    $planInterval = $planData['interval'];

    // Fetch plan details from the database 
    $query_s = $db->query(" SELECT * FROM `discount`");

    $totalValue = $planData['us_main_p'];
    $price = $planPrice;
    $count = $count_site;
    

    $planPrice =  $totalValue;

    $planPrice = !empty($jsonObj->t_Price) ? $jsonObj->t_Price : $planPrice;
 

    // Convert price to cents 
    $planPriceCents = round($planPrice * 100);

    // Add customer to stripe 
    try {
        $customer = \Stripe\Customer::create([
            'name' => $name,
            'email' => $email
        ]);
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }



// Add Tax =
        // if($vat_number!=""){
        //         try {
        //             // Create price with subscription info and interval 
        //             $tax_id = \Stripe\Customer::createTaxId($customer->id,[
        //                 'value' => $vat_number,
        //                 'type' => 'ua_vat'
        //             ]);
        //         } catch (Exception $e) {
        //             $api_error = $e->getMessage();

        //         if ($api_error == 'Invalid value for ua_vat.') { 
        //                 $api_error = "Invalid VAT Number.";
        //         }


        //         }
        // }

// Add Tax End



    if (empty($api_error) && $customer) {
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

        if (empty($api_error) && $price) {
            $today_date =  date("Y/m/d");
           $seven_days_after = strtotime($today_date . " +7 days");
            try {

                if($coupon_id!=""){
                        $subscription = \Stripe\Subscription::create([
                            'customer' => $customer->id,
                            'items' => [[
                                'price' => $price->id
                            ]],
                            'payment_behavior' => 'default_incomplete',
                            'expand' => ['latest_invoice.payment_intent'],
                            'description' => "Subscription Created",
                            'coupon' => $coupon_id,
                             
                        ]);
                }
                else{
                     $today_date =  date("Y/m/d");
           $seven_days_after = strtotime($today_date . " +7 days");
                        $subscription = \Stripe\Subscription::create([
                            'customer' => $customer->id,
                            'items' => [[
                                'price' => $price->id
                            ]],
                            'payment_behavior' => 'default_incomplete',
                            'expand' => ['latest_invoice.payment_intent'],
                            'description' => "Subscription Created",
                        ]);                    
                }



            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }

            if (empty($api_error) && $subscription) {
                $output = [
                    'subscriptionId' => $subscription->id,
                    'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret,
                    'customerId' => $customer->id
                ];

                echo json_encode($output);
            } else {

                if (str_contains($api_error, 'is used up and cannot be applied')) { 
                        echo json_encode(['error' => "Coupon is used up and cannot be applied."]);
                }else{
                        echo json_encode(['error' => $api_error]);
                  }


            }
        } else {
            echo json_encode(['error' => $api_error]);
        }
    } else {
        echo json_encode(['error' => $api_error]);
    }
} 
elseif ($jsonObj->request_type == 'payment_insert') {
    $payment_intent = !empty($jsonObj->payment_intent) ? $jsonObj->payment_intent : '';
    $subscription_id = !empty($jsonObj->subscription_id) ? $jsonObj->subscription_id : '';
    $customer_id = !empty($jsonObj->customer_id) ? $jsonObj->customer_id : '';
    $subscr_plan_id = !empty($jsonObj->subscr_plan_id) ? $jsonObj->subscr_plan_id : '';
    $t_Price = !empty($jsonObj->t_Price) ? $jsonObj->t_Price : '';
    $tax_price = !empty($jsonObj->tax_price) ? $jsonObj->tax_price : '';
    $coupon_id = !empty($jsonObj->coupon_id) ? $jsonObj->coupon_id : '';
    $vat_number = !empty($jsonObj->vat_number) ? $jsonObj->vat_number : '';

 

    // Fetch plan details from the database 
    $sqlQ = "SELECT * FROM plans WHERE id=?";
    $stmt = $db->prepare($sqlQ);
    $stmt->bind_param("i", $db_id);
    $db_id = $subscr_plan_id;
    $stmt->execute();
    $result = $stmt->get_result();
    $planData = $result->fetch_assoc();

    $planName = $planData['name'];
    $planPrice = $planData['us_main_p'];
    $planInterval = $planData['interval'];



    // Retrieve customer info 
    try {
        $customer = \Stripe\Customer::retrieve($customer_id);
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    // Check whether the charge was successful 
    if (!empty($payment_intent) && $payment_intent->status == 'succeeded') {

        // Retrieve subscription info 
        try {
            $subscriptionData = \Stripe\Subscription::retrieve($subscription_id);
        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }

 
        $payment_intent_id = $payment_intent->id;
        $paidAmount = $payment_intent->amount;
        $paidAmount = ($paidAmount / 100);
        $paidCurrency = $payment_intent->currency;
        $payment_status = $payment_intent->status;

        $created = date("Y-m-d H:i:s", $payment_intent->created);
        $current_period_start = $current_period_end = '';
        if (!empty($subscriptionData)) {
            $created = date("Y-m-d H:i:s", $subscriptionData->created);
            $current_period_start = date("Y-m-d H:i:s", $subscriptionData->current_period_start);
            $current_period_end = date("Y-m-d H:i:s", $subscriptionData->current_period_end);
        }

        $name = $email = '';
        if (!empty($customer)) {
            $name = !empty($customer->name) ? $customer->name : '';
            $email = !empty($customer->email) ? $customer->email : '';
        }

        // Check if any transaction data exists already with the same TXN ID 
        $sqlQ = "SELECT id FROM user_subscriptions WHERE stripe_payment_intent_id = ?";
        $stmt = $db->prepare($sqlQ);
        $stmt->bind_param("s", $db_txn_id);
        $db_txn_id = $payment_intent_id;
        $stmt->execute();
        $result = $stmt->get_result();
        $prevRow = $result->fetch_assoc();

        $payment_id = 0;
        if (!empty($prevRow)) {
            $payment_id = $prevRow['id'];
        } else {

            $sqlQ = "SELECT count(id)as count FROM user_subscriptions WHERE user_id = $userID";
            $stmt = $db->prepare($sqlQ);
            $stmt->execute();
            $result = $stmt->get_result();
            $prevRow1 = $result->fetch_assoc();
            $site_count_dd = 0;
            if (!empty($prevRow1)) {
                $site_count_dd = $prevRow1['count'];
            }
            // echo '<br>aa';
            // die;
             $sid_id = $jsonObj->sid_id;

            $sqlQ1 = "SELECT id,subscription_id FROM boost_website WHERE manager_id = $userID and id = '$sid_id' and plan_type = 'Free' ";
            $stmt = $db->prepare($sqlQ1);
            $stmt->execute();
            $result1 = $stmt->get_result();
            $prevRow1 = $result1->fetch_assoc();
            $site_free = 0;
            
            if (!empty($prevRow1)) {
                $site_free = 1;
                 
            }

            $sqlQ2 = "SELECT id,subscription_id FROM boost_website WHERE manager_id = $userID and id = '$sid_id'";
            $stmt = $db->prepare($sqlQ2);
            $stmt->execute();
            $result2 = $stmt->get_result();
            $prevRow1 = $result2->fetch_assoc();
            
            $sub_id = '';
            if (!empty($prevRow1)) {
                
                $sub_id = $prevRow1['subscription_id'];
            }

            $discount_price = 0;
            if($coupon_id!="")
            {
                $discount_price = $t_Price - $paidAmount;
            }


            // Insert transaction data into the database 
            $sqlQ = "INSERT INTO user_subscriptions (user_id,plan_id,stripe_subscription_id,stripe_customer_id,stripe_payment_intent_id,paid_amount,total_tax,discount_amount,discount_code_id,plan_price,paid_amount_currency,plan_interval,payer_email,created,plan_period_start,plan_period_end,status,subscription_items_id,products_id,json_data,site_count,vat) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $db->prepare($sqlQ);
            $stmt->bind_param("iisssdssssssssssssssss", $db_user_id, $db_plan_id, $db_stripe_subscription_id, $db_stripe_customer_id, $db_stripe_payment_intent_id, $db_paid_amount, $db_paid_total_tax, $bd_discount_amount,$db_discount_code_id, $db_paid_total_price, $db_paid_amount_currency, $db_plan_interval, $db_payer_email, $db_created, $db_plan_period_start, $db_plan_period_end, $db_status, $subscription_items_id, $products_id, $json_data, $site_count, $vat_number);
            $db_user_id = $userID;
            $db_plan_id = $subscr_plan_id;
            $db_stripe_subscription_id = $subscription_id;
            $db_stripe_customer_id = $customer_id;
            $db_stripe_payment_intent_id = $payment_intent_id;
            $db_paid_amount = $paidAmount;
            $db_paid_total_tax = $tax_price;

            $bd_discount_amount = $discount_price;
            $db_discount_code_id = $coupon_id;

            $db_paid_total_price = $t_Price;
            $db_paid_amount_currency = $paidCurrency;
            $db_plan_interval = $planInterval;
            $db_payer_email = $email;
            $db_created = $created;
            $db_plan_period_start = $current_period_start;
            $db_plan_period_end = $current_period_end;
            $db_status = $payment_status;
            $subscription_items_id = $subscriptionData->items->data[0]->id;
            $products_id = $subscriptionData->plan->product;
            $json_data = json_encode($subscriptionData);
            $site_count = $count_site;
            $insert = $stmt->execute();

            if ($insert) {
                $payment_id = $stmt->insert_id;

                // ------------------------------------


                    $sqlQC = "SELECT id,code FROM coupons WHERE strip_coupon_id='$coupon_id' ";
                    $stmt = $db->prepare($sqlQC);
                    $stmt->execute();
                    $result2C = $stmt->get_result();
                    $prevRow1C = $result2C->fetch_assoc();
                    if (!empty($prevRow1C)) {
                        
                        $coupon_code = $prevRow1C['code'];
                        $sql_update_c = " UPDATE coupons SET status = 2 where code='$coupon_code' ";
                        $stmt_c = $db->prepare($sql_update_c);
                        $update_c = $stmt_c->execute();          
                    }

                // ------------------------------------
                    $sql_update_c = " UPDATE coupons set  number_of_uses = number_of_uses+1, status = 2 where strip_coupon_id='$coupon_id' ";
                    $stmt_c = $db->prepare($sql_update_c);
                    $update_c = $stmt_c->execute();  
                             

            // START check trial already runing ========================================================

            $check_usf_query = $db->query(" SELECT * FROM `user_subscriptions_free` WHERE `user_id` = '$userID' AND status = 1; " );

            if ( $check_usf_query->num_rows > 0 ) {

                $usf_subscription_data = $check_usf_query->fetch_assoc();
                $plan_start_date = $usf_subscription_data["plan_start_date"] ;
                $current_date = date('Y-m-d H:i:s') ;

                $diff = date_diff( date_create($plan_start_date) , date_create($current_date) ) ; 

                $trial_switch = 1 ;
                $switch_after_days = $diff->d ;
            }
            else {
                $trial_switch = 0 ;
                $switch_after_days = -1 ;
            }

            $db->query(" UPDATE `user_subscriptions` SET `trial_switch`='$trial_switch' , `switch_after_days`='$switch_after_days' WHERE id = '$payment_id' ; " );

            // END check trial already runing ====================================================

                // if ($site_count_dd == 0) {
                 $sql_update = " UPDATE boost_website SET plan_id = $db_plan_id, plan_type = 'Subscription',subscription_id= '$payment_id' where manager_id='$db_user_id' and id = '$sid_id' ";
                    $stmt = $db->prepare($sql_update);
                    $update = $stmt->execute();
                    $stmt = $db->prepare($sql_update);
                    $update = $stmt->execute();

                    if($site_free ==1){
                    $sql_update = " UPDATE user_subscriptions_free SET status = 0 where user_id='$db_user_id' ";
                    $stmt = $db->prepare($sql_update);
                    $update = $stmt->execute();
                }
            // }



                $user_idss = $_SESSION['user_id'];
                $change_id = $jsonObj->change_id;
              


                //This Process is For Change the Plan
                if (!empty($change_id)) {

                    // Update The Booster Table Because The Content oF THE oREVIOUS ONE Save To new One 
                    $sqlzz = "SELECT * FROM user_subscriptions WHERE stripe_subscription_id = '$subscription_id' and user_id = '$userID'";
                    // die;
                    $stmtzz = $db->prepare($sqlzz);
                    $stmtzz->execute();
                    $resultzz = $stmtzz->get_result();
                    $fetchRow = $resultzz->fetch_assoc();
                    if (!empty($fetchRow)) {

                        $newSiteid = $fetchRow['id'];

                        $sqlC = "UPDATE boost_website SET subscription_id = '$newSiteid', plan_id = '$subscr_plan_id' where subscription_id= '$change_id'";
                        $stmtC = $db->prepare($sqlC);
                        $updateC = $stmtC->execute();
 

                    }

 
                    if($sub_id !=  "xxxxxxxxxxxx"){
                        $sqlQ = "SELECT * FROM user_subscriptions WHERE user_id = '$user_idss' and id = '$sub_id' and is_active = 1";
                        // die;
                        $stmt = $db->prepare($sqlQ);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $prevRow = $result->fetch_assoc();
                        if (!empty($prevRow)) {

                            $subscription = \Stripe\Subscription::retrieve($prevRow['stripe_subscription_id']);
                            // print_r($subscription);
                            $subscription->cancel();

                            $sqlx = "UPDATE user_subscriptions SET is_active = 0, cancled_at = now() where id = '$sub_id' ";
                            $stmtx = $db->prepare($sqlx);
                            $stmtx->execute();
                        }
                    }


                }
                // die;

                $sql_update_u = " UPDATE admin_users SET flow_step = 2 where id = '$user_idss' ";
                $stmtU = $db->prepare($sql_update_u);
                $updateu = $stmtU->execute();

                // card details save code ----------------------------------------------------------------
                $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);
                $payment_intent_data = $stripe->paymentIntents->retrieve($payment_intent_id);
                $user_card_detail = $payment_intent_data->charges->data[0]->payment_method_details->card;
                // echo "here";
                // print_r($user_card_detail) ;

                $cardnumber = $user_card_detail->last4;
                $cardnumber = "************" . $cardnumber;
                $exp_month = $user_card_detail->exp_month;
                $exp_year = $user_card_detail->exp_year;
                $cvc = '';

                $sql_update = " UPDATE payment_method_details SET prefered_card = 0 where manager_id = '$userID' ";
                $stmt = $db->prepare($sql_update);
                $update = $stmt->execute();

                // 

                $sql = "SELECT * FROM `payment_method_details` where card_number LIKE '" . "%" . substr($cardnumber, -4, 4) . "' AND exp_month LIKE '" . "%" . $exp_month . "' AND exp_year LIKE  '" . "%" . $exp_year . "' AND card_name LIKE '" . "%" . $name . "' AND manager_id = '$userID'";
                $payment_method_row = $db->query($sql);

                if (($payment_method_row->num_rows) <= 0) {
                    $sql_insert = " INSERT INTO payment_method_details ( manager_id , card_name , card_number , exp_month , exp_year , cvv ,  prefered_card ) VALUES ( ? , ? , ? , ? , ? , ? , ? ) ";
                    $stmt = $db->prepare($sql_insert);
                    $stmt->bind_param("sssssss", $db_user_id, $name, $cardnumber, $exp_month, $exp_year, $cvc, $prefered_card);
                    $db_user_id = $userID;
                    $name = $name;
                    $cardnumber = $cardnumber;
                    $exp_month = $exp_month;
                    $exp_year = $exp_year;
                    $cvc = $cvc;
                    $prefered_card = 1;

                    $insert = $stmt->execute();
                }else{

                    $id_payment=$payment_method_row->fetch_assoc()['id'];
                    // echo $id_payment;
                   $update_sql= "UPDATE `payment_method_details` SET prefered_card=1 WHERE  id='$id_payment' and manager_id = '$userID'";
                    // echo $update_sql;

                   $db->query($update_sql);

 
                }
 

            }
  

        }

        $output = [
            'payment_id' => base64_encode($payment_id)
        ];
        echo json_encode($output);
    } else {
        echo json_encode(['error' => 'Transaction has been failed!']);
    }
}
elseif ($jsonObj->request_type == 'create_customer_trial') {

    // for trial subscription

    $subscr_plan_id = !empty($jsonObj->subscr_plan_id) ? $jsonObj->subscr_plan_id : '';

    $name = !empty($jsonObj->name) ? $jsonObj->name : '';
    $email = !empty($jsonObj->email) ? $jsonObj->email : '';
    $coupon_id = !empty($jsonObj->coupon_id) ? $jsonObj->coupon_id : '';
    $vat_number = !empty($jsonObj->vatNumber) ? $jsonObj->vatNumber : '';

    // for trial  
    $with_trial = !empty($jsonObj->with_trial) ? intval($jsonObj->with_trial) : 0 ;
    $country_label = !empty($jsonObj->country_label) ? $jsonObj->country_label : '' ;
    $cancel_url = !empty($jsonObj->cancel_url) ? $jsonObj->cancel_url : HOST_URL ;


    $change_id = !empty($jsonObj->change_id) ? $jsonObj->change_id : '' ;
    $t_Price = !empty($jsonObj->t_Price) ? $jsonObj->t_Price : '' ;
    $sid_id = !empty($jsonObj->sid_id) ? $jsonObj->sid_id : '' ;
    $discount_amount = !empty($jsonObj->discount_amount) ? $jsonObj->discount_amount : '' ;
    $tax_price = !empty($jsonObj->tax_price) ? $jsonObj->tax_price : '' ;


    // Fetch plan details from the database 
    $sqlQ = "SELECT * FROM plans WHERE id=?";
    $stmt = $db->prepare($sqlQ);
    $stmt->bind_param("i", $db_id);
    $db_id = $subscr_plan_id;
    $stmt->execute();
    $result = $stmt->get_result();
    $planData = $result->fetch_assoc();

    $planName = $planData['name'];
    $planPrice = $planData['us_main_p'];
    $planInterval = $planData['interval'];

    // Fetch plan details from the database 
    $query_s = $db->query(" SELECT * FROM `discount`");

    $totalValue = $planData['us_main_p'];
    $price = $planPrice;
    $count = $count_site;
    

    $planPrice =  $totalValue;

    $planPrice = !empty($jsonObj->t_Price) ? $jsonObj->t_Price : $planPrice;
 

    // Convert price to cents 
    $planPriceCents = round($planPrice * 100);

    // Add customer to stripe 
    try {

        $customer_data = [
            'name' => $name,
            'email' => $email
        ] ;

        if ( !empty($country_label) ) {
            $customer_data['address']['country'] = $country_label ;
        }

        $customer = \Stripe\Customer::create($customer_data);

    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    if (empty($api_error) && $customer) {

        // for saving card in customer by Checkout Session.
        try {
        
            $session = \Stripe\Checkout\Session::create([
              'payment_method_types' => ['card'],
              'mode' => 'setup',
              'customer' => $customer->id ,
              'success_url' => HOST_URL.'payment/us-trial-save-card-success.php?session_id={CHECKOUT_SESSION_ID}',
              'cancel_url' => $cancel_url 
            ]);

        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }

        if (empty($api_error) && $session) {

            $stripe_customer_id = $customer->id ;

            // save details in session
            $_SESSION["customer_trial_data"] = array(
                'subscr_plan_id' => $subscr_plan_id , 
                'name' => $name ,
                'email' => $email ,
                'coupon_id' => $coupon_id ,
                'vat_number' => $vat_number ,
                'with_trial' => $with_trial ,
                'country_label' => $country_label ,
                'cancel_url' => $cancel_url ,
                'planName' => $planName ,
                'planPrice' => $planPrice ,
                'planInterval' => $planInterval ,
                'price' => $price ,
                'count' => $count ,
                'planPriceCents' => $planPriceCents ,
                'change_id' => $change_id , 
                't_Price' => $t_Price , 
                'sid_id' => $sid_id , 
                'discount_amount' => $discount_amount , 
                'tax_price' => $tax_price , 
            );

            $output = [
                'sessionId' => $session->id,
                'sessionUrl' => $session->url,
                'customerId' => $customer->id,
                'customer_trial_data' => $_SESSION["customer_trial_data"]
            ];


            // insert trial subscription details in database with stripe customer_id
            $ustd_qcheck = $db->query( " SELECT * FROM `user_subscription_trial_data` WHERE stripe_customer_id LIKE '$stripe_customer_id'; " ); 

            if ( $ustd_qcheck->num_rows > 0 ) {
                $ustd_dcheck = $ustd_qcheck->fetch_assoc() ;

                $ustd_id = $ustd_dcheck['id'] ;

                $db->query( " UPDATE `user_subscription_trial_data` SET `user_id`='$userID' ,`subscr_plan_id`='$subscr_plan_id',`name`='$name',`email`='$email',`coupon_id`='$coupon_id',`vat_number`='$vat_number' ,`with_trial`='$with_trial',`country_label`='$country_label',`cancel_url`='$cancel_url',`planName`='$planName',`planPrice`='$planPrice',`planInterval`='$planInterval',`price`='$price',`count`='$count',`planPriceCents`='$planPriceCents',`change_id`='$change_id',`t_Price`='$t_Price',`sid_id`='$sid_id',`discount_amount`='$discount_amount',`tax_price`='$tax_price',`used`=0 WHERE `id`='$ustd_id' " ); 

            }
            else {

                $db->query( " INSERT INTO `user_subscription_trial_data`( `user_id`, `subscr_plan_id`, `name`, `email`, `coupon_id`, `vat_number` , `with_trial`, `country_label`, `cancel_url`, `planName`, `planPrice`, `planInterval`, `price`, `count`, `planPriceCents`, `change_id`, `t_Price`, `sid_id`, `discount_amount`, `tax_price` , `stripe_customer_id` , `used` ) VALUES ( '$userID' , '$subscr_plan_id' , '$name' , '$email' , '$coupon_id' , '$vat_number' , '$with_trial' , '$country_label' , '$cancel_url' , '$planName' , '$planPrice' , '$planInterval' , '$price' , '$count' , '$planPriceCents' , '$change_id' , '$t_Price' , '$sid_id' , '$discount_amount' , '$tax_price' , '$stripe_customer_id' , 0 ) " ); 
            }


            echo json_encode($output);
        } 
        else {

            if (str_contains($api_error, 'is used up and cannot be applied')) { 
                echo json_encode(['error' => "Coupon is used up and cannot be applied."]);
            }
            else {
                echo json_encode(['error' => $api_error]);
            }
        }


    } 
    else {
        echo json_encode(['error' => $api_error]);
    }
} 