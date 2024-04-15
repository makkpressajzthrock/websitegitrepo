<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
// Include the configuration file 
require_once 'config.php';

// Include the database connection file 
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
    $customer_id = !empty($jsonObj->customer_id) ? $jsonObj->customer_id : '';

    // Fetch plan details from the database 
    $sqlQ = "SELECT * FROM addon WHERE id=?";
    $stmt = $db->prepare($sqlQ);
    $stmt->bind_param("i", $db_id);
    $db_id = $subscr_plan_id;
    $stmt->execute();
    $result = $stmt->get_result();
    $planData = $result->fetch_assoc();

    $planName = $planData['description'];
    $planPrice = $planData['price'];
    $planInterval = $planData['interval'];
    $planPriceCents = round($planPrice * 100);



    if ($customer_id == "") {
    }

    // Add customer to stripe 
    // try {   
    //     $customer = \Stripe\Customer::create([ 
    //         'name' => $name,  
    //         'email' => $email 
    //     ]);  
    // }catch(Exception $e) {   
    //     $api_error = $e->getMessage();   
    // }



    if ($customer_id != "") {
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
            // Create a new subscription 
            try {
                $subscription = \Stripe\Subscription::create([
                    'customer' => $customer_id,
                    'items' => [[
                        'price' => $price->id,
                    ]],
                    'payment_behavior' => 'default_incomplete',
                    'expand' => ['latest_invoice.payment_intent'],
                    'description' => "Addon Subscription Created",
                ]);
            } catch (Exception $e) {
                $api_error = $e->getMessage();
            }

            if (empty($api_error) && $subscription) {
                $output = [
                    'subscriptionId' => $subscription->id,
                    'clientSecret' => $subscription->latest_invoice->payment_intent->client_secret,
                    'customerId' => $customer_id
                ];

                echo json_encode($output);
            } else {
                echo json_encode(['error' => $api_error]);
            }
        } else {
            echo json_encode(['error' => $api_error]);
        }
    } else {
        echo json_encode(['error' => $api_error]);
    }
} elseif ($jsonObj->request_type == 'payment_insert') {


    $payment_intent = !empty($jsonObj->payment_intent) ? $jsonObj->payment_intent : '';
    $subscription_id = !empty($jsonObj->subscription_id) ? $jsonObj->subscription_id : '';
    $customer_id = !empty($jsonObj->customer_id) ? $jsonObj->customer_id : '';
    $subscr_plan_id = !empty($jsonObj->subscr_plan_id) ? $jsonObj->subscr_plan_id : '';
    $site_id = !empty($jsonObj->site_id) ? $jsonObj->site_id : '';



    // Fetch plan details from the database 
    $sqlQ = "SELECT * FROM addon WHERE id=?";
    $stmt = $db->prepare($sqlQ);
    $stmt->bind_param("i", $db_id);
    $db_id = $subscr_plan_id;
    $stmt->execute();
    $result = $stmt->get_result();
    $planData = $result->fetch_assoc();

    $planPrice = $planData['price'];
    $planInterval = $planData['interval'];
    $addon_count = $planData['urls'];


    // die;

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
        $sqlQ = "SELECT id FROM addon_site WHERE stripe_payment_intent_id = ?";
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

            // Insert transaction data into the database 
            $sqlQ = "INSERT INTO addon_site (user_id,addon_id,stripe_subscription_id,stripe_customer_id,stripe_payment_intent_id,paid_amount,paid_amount_currency,plan_interval,payer_email,created,plan_period_start,plan_period_end,status,subscription_items_id,products_id,json_data,addon_count,site_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $db->prepare($sqlQ);
            $stmt->bind_param("iisssdssssssssssss", $db_user_id, $db_plan_id, $db_stripe_subscription_id, $db_stripe_customer_id, $db_stripe_payment_intent_id, $db_paid_amount, $db_paid_amount_currency, $db_plan_interval, $db_payer_email, $db_created, $db_plan_period_start, $db_plan_period_end, $db_status, $subscription_items_id, $products_id, $json_data, $addon_count, $site_id);
            $db_user_id = $userID;
            $db_plan_id = $subscr_plan_id;
            $db_stripe_subscription_id = $subscription_id;
            $db_stripe_customer_id = $customer_id;
            $db_stripe_payment_intent_id = $payment_intent_id;
            $db_paid_amount = $paidAmount;

            $db_paid_amount_currency = "USD";
            $db_plan_interval = $planInterval;
            $db_payer_email = $email;
            $db_created = $created;
            $db_plan_period_start = $current_period_start;
            $db_plan_period_end = $current_period_end;
            $db_status = $payment_status;
            $subscription_items_id = $subscriptionData->items->data[0]->id;
            $products_id = $subscriptionData->plan->product;
            $json_data = json_encode($subscriptionData);
            $addon_count = $addon_count;
            $site_id = $site_id;
            $insert = $stmt->execute();


            if ($insert) {
                $payment_id = $stmt->insert_id;


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
                $sql = "SELECT * FROM `payment_method_details` where card_number LIKE '" . "%" . substr($cardnumber, -4, 4) . "' AND exp_month LIKE '" . "%" . $exp_month . "' AND exp_year LIKE  '" . "%" . $exp_year . "' AND card_name LIKE '" . "%" . $name . "' AND manager_id = '$userID'";
                $payment_method_row = $db->query($sql);
                 // echo $sql;

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

                //    $stmtaa = $db->prepare($update_sql);
                // $stmtaa->execute();
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
