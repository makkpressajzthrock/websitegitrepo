<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

session_start();
// Include the configuration file 
require_once 'config.php';

// Include the database connection file 
$country = "IND";
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


// echo "<pre>";
// print_r($jsonObj); die;

if ($jsonObj->request_type == 'create_customer_subscription') {
    $subscr_plan_id = !empty($jsonObj->subscr_plan_id) ? $jsonObj->subscr_plan_id : '';

    $name = !empty($jsonObj->name) ? $jsonObj->name : '';
    $email = !empty($jsonObj->email) ? $jsonObj->email : '';
    $phone = !empty($jsonObj->phone) ? $jsonObj->phone : ''; //123
    $coupon_id = !empty($jsonObj->coupon_id) ? $jsonObj->coupon_id : '';
    $gst_number = !empty($jsonObj->gst_number) ? $jsonObj->gst_number : '';
    // $plan_type = !empty($jsonObj->plan_type) ? $jsonObj->plan_type : ''; //123
    // $plan_name = !empty($jsonObj->plan_name) ? $jsonObj->plan_name : ''; //123

    // for trial
    $with_trial = !empty($jsonObj->with_trial) ? intval($jsonObj->with_trial) : 0 ;

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
            'email' => $email,
            'metadata' => [
                'phone' => $phone  // Store phone number in metadata //123
            ]
        ]);
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

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

        // Add Tax =
        if($gst_number!=""){
                try {
                    // Create price with subscription info and interval 
                    $tax_id = \Stripe\Customer::createTaxId($customer->id,[
                        'value' => $gst_number,
                        'type' => 'in_gst'
                    ]);
                } catch (Exception $e) {
                    $api_error = $e->getMessage();
                }
        }

        // Add Tax End


        if (empty($api_error) && $price) {

            // Create a new subscription 

            $subscription_arr = [
                            'customer' => $customer->id,
                            'items' => [[
                                'price' => $price->id
                            ]],
                            'payment_behavior' => 'default_incomplete',
                            'expand' => ['latest_invoice.payment_intent'],
                            'description' => "Subscription Created",
                            'default_tax_rates' => [
                                  'txr_1NN9U0SAQd0TueXd7xVWAyI4'
                            ],
                            /*** start metadata ***/
                            'metadata' => [ 
                                'website_1st_url' => $website_url,
                                'website_second_url' => $url_1,
                                'website_third_url' => $url_2,
                                'platform' => $platform,
                                'company_role' => $company_role,
                                'shopify_url' => $shopify_url,
                                'shopify_preview_url' => $shopify_preview_url,
                                'website_name' => $website_name,
                                'user_email' => $user_email,
                                'user_phone' => $user_phone,
                                'billing_email' => $billing_email,
                                'user_name' => $user_full_name,
                                'user_address' => $user_address,
                                'user_country' => $user_country,
                                'user_city' => $user_city,
                                'user_state' => $user_state,
                                'zip' => $zip,
                                'plan_type'=> $plan_type,
                                'plan_name' => $plan_name,
                            ], 
                            /*** end metadata ***/
                        ] ;

            //  for trial
            /** if ( $with_trial == 1 ) {
                $today_date = date('Y-m-d H:i:s') ;
                $seven_days_after = strtotime($today_date . " +7 days");
                $subscription_arr["trial_end"] = $seven_days_after ;
            } **/
            
            try {

                if($coupon_id!=""){
                    $subscription_arr["coupon"] = $coupon_id ;
                }

                $subscription = \Stripe\Subscription::create($subscription_arr); 


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
    $gst_number = !empty($jsonObj->gst_number) ? $jsonObj->gst_number : '';
    $discount_amount = !empty($jsonObj->discount_amount) ? $jsonObj->discount_amount : '';

 

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

        $name = $email = $phone = ''; //123
        if (!empty($customer)) {
            $name = !empty($customer->name) ? $customer->name : '';
            $email = !empty($customer->email) ? $customer->email : '';
            $phone = !empty($customer->metadata->phone) ? $customer->metadata->phone : '';  //123
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
            if($discount_amount!="")
            {
                $discount_price = $discount_amount;
            }

            // Start old subscription cancelled
            $os_query = $db->query("SELECT id , plan_id , plan_type , subscription_id FROM `boost_website` WHERE `manager_id` = '$userID' AND id = '$sid_id' AND plan_type LIKE 'Subscription';") ;
            if ( $os_query->num_rows > 0 ) {
                $os_data = $os_query->fetch_assoc() ;
                if ( !empty($os_data["plan_id"]) && ( $os_data["plan_id"] != 999 || $os_data["plan_id"] != '999' ) ) {
                    // cancel the old active subscription
                    $cos_query = $db->query(" UPDATE user_subscriptions SET is_active = 0 , cancled_at = NOW() WHERE id = '".$os_data["subscription_id"]."' ; ") ;
                }
            }
            // End old subscription cancelled

            /*** start get plan views ***/
            $requested_views = 0 ;
            if ( empty($requested_views) ) {
                $pv_query = $db->query(" SELECT page_view FROM `plans` WHERE id = '".$subscr_plan_id."' ; ");
                if ($pv_query->num_rows > 0) {
                    $pv_data = $pv_query->fetch_assoc() ;
                    $requested_views = $pv_data["page_view"] ;
                }
            }
            /*** end get plan views ***/

            // Insert transaction data into the database 
            //123
            $sqlQ = "INSERT INTO user_subscriptions (user_id,plan_id,stripe_subscription_id,stripe_customer_id,stripe_payment_intent_id,paid_amount,total_tax,discount_amount,discount_code_id,plan_price,paid_amount_currency,plan_interval,payer_email,payer_phone,created,plan_period_start,plan_period_end,status,subscription_items_id,products_id,json_data,site_count,gst,requested_views) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $db->prepare($sqlQ); //123
            $stmt->bind_param("iisssdsssssssissssssssss", $db_user_id, $db_plan_id, $db_stripe_subscription_id, $db_stripe_customer_id, $db_stripe_payment_intent_id, $db_paid_amount, $db_paid_total_tax, $bd_discount_amount,$db_discount_code_id, $db_paid_total_price, $db_paid_amount_currency, $db_plan_interval, $db_payer_email, $db_payer_phone, $db_created, $db_plan_period_start, $db_plan_period_end, $db_status, $subscription_items_id, $products_id, $json_data, $site_count, $gst_number , $requested_views);
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
            $db_payer_phone = $phone; //123
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
    $phone = !empty($jsonObj->phone) ? $jsonObj->phone : '';  //123
    $coupon_id = !empty($jsonObj->coupon_id) ? $jsonObj->coupon_id : '';
    $gst_number = !empty($jsonObj->gst_number) ? $jsonObj->gst_number : '';

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
            'email' => $email,
            'metadata' => [
                'phone' => $phone  // Store phone number in metadata //123
            ]
        ] ;

        if ( !empty($country_label) ) {
            // $customer_data['address']['country'] = $country_label ;
        }

        $customer = \Stripe\Customer::create($customer_data);

    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    if (empty($api_error) && $customer) {

        // Add Tax =
        if($gst_number!=""){
            try {
                // Create price with subscription info and interval 
                $tax_id = \Stripe\Customer::createTaxId($customer->id,[
                    'value' => $gst_number,
                    'type' => 'in_gst'
                ]);
            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }
        }

        // for saving card in customer by Checkout Session.
        try {
        
            $session = \Stripe\Checkout\Session::create([
              'payment_method_types' => ['card'],
              'mode' => 'setup',
              'customer' => $customer->id ,
              'success_url' => HOST_URL.'payment/trial-save-card-success.php?session_id={CHECKOUT_SESSION_ID}',
              'cancel_url' => $cancel_url 
            ]);

        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }

        if (empty($api_error) && $session) {

            $stripe_customer_id = $customer->id ;

            $output = [
                'sessionId' => $session->id,
                'sessionUrl' => $session->url,
                'customerId' => $customer->id
            ];

            // save details in session //123
            $_SESSION["customer_trial_data"] = array(
                'subscr_plan_id' => $subscr_plan_id , 
                'name' => $name ,
                'email' => $email ,
                'phone' => $phone ,
                'coupon_id' => $coupon_id ,
                'gst_number' => $gst_number ,
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


            // insert trial subscription details in database with stripe customer_id
            $ustd_qcheck = $db->query( " SELECT * FROM `user_subscription_trial_data` WHERE stripe_customer_id LIKE '$stripe_customer_id'; " ); 

            if ( $ustd_qcheck->num_rows > 0 ) {
                $ustd_dcheck = $ustd_qcheck->fetch_assoc() ;

                $ustd_id = $ustd_dcheck['id'] ;
                //123
                $db->query( " UPDATE `user_subscription_trial_data` SET `user_id`='$userID' ,`subscr_plan_id`='$subscr_plan_id',`name`='$name',`email`='$email', `phone`='$phone',`coupon_id`='$coupon_id',`gst_number`='$gst_number' ,`with_trial`='$with_trial',`country_label`='$country_label',`cancel_url`='$cancel_url',`planName`='$planName',`planPrice`='$planPrice',`planInterval`='$planInterval',`price`='$price',`count`='$count',`planPriceCents`='$planPriceCents',`change_id`='$change_id',`t_Price`='$t_Price',`sid_id`='$sid_id',`discount_amount`='$discount_amount',`tax_price`='$tax_price',`used`=0 WHERE `id`='$ustd_id' " ); 

            }
            else {
                //123
                $db->query( " INSERT INTO `user_subscription_trial_data`( `user_id`, `subscr_plan_id`, `name`, `email`, `phone`, `coupon_id`, `gst_number` , `with_trial`, `country_label`, `cancel_url`, `planName`, `planPrice`, `planInterval`, `price`, `count`, `planPriceCents`, `change_id`, `t_Price`, `sid_id`, `discount_amount`, `tax_price` , `stripe_customer_id` , `used` ) VALUES ( '$userID' , '$subscr_plan_id' , '$name' , '$email' , '$phone', '$coupon_id' , '$gst_number' , '$with_trial' , '$country_label' , '$cancel_url' , '$planName' , '$planPrice' , '$planInterval' , '$price' , '$count' , '$planPriceCents' , '$change_id' , '$t_Price' , '$sid_id' , '$discount_amount' , '$tax_price' , '$stripe_customer_id' , 0 ) " ); 
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