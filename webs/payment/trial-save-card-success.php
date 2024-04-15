<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

session_start();
session_regenerate_id() ;
ob_start() ;


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

	// error in creating price
	$_SESSION['error'] = "Inviled token please reload the page.";

	$return = empty($_SERVER['HTTP_REFERER']) ? HOST_URL : $_SERVER['HTTP_REFERER'] ;
	header("location: ".$return) ;
	die() ;	
}

if( empty($_SESSION['user_id']) && empty($_SESSION['role']) ){

	// error in creating price
	$_SESSION['error'] = "User session expired. Please try again after login.";

	$return = HOST_URL.'adminpannel' ;
	header("location: ".$return) ;
	die() ;	
}


$siteId = $_SESSION['siteId'];
$count_site =  $_SESSION["count_site"];

 

include_once 'dbConnect.php';
// die;
// Include the Stripe PHP library 
require_once 'stripe-php/init.php';
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);
// $subsc_id_url = $_REQUEST['sid'];



echo "<pre>" ;

print_r($_SESSION) ;


if ( isset($_GET['session_id']) && !empty($_GET['session_id']) ) {

    // retrieve Checkout Session by id
    try {
    	$session_id = trim($_GET['session_id']) ;
        $session = \Stripe\Checkout\Session::retrieve($session_id , []);
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    if (empty($api_error) && $session) {

		/** Create trial subscription **/ 
		$customer_id = $session->customer ;


	    // retrieve setup intent by setup_intent id
	    $setup_intent_id = $session->setup_intent ; 

	    // echo "setup_intent_id:".$setup_intent_id."<br>" ;

	    try {
	        $setup_intent_data = \Stripe\SetupIntent::retrieve($setup_intent_id , []);
	        print_r($setup_intent_data) ;

	        

	    } catch (Exception $e) {
	        $api_error = $e->getMessage();
	    }


	    if (empty($api_error) && $setup_intent_data) {

	    	// payment_method
	    	$sid_payment_method = $setup_intent_data->payment_method ;


	    	// echo "<hr>" ;

	    	// print_r($session) ;

	    	// update payment method in customer 
		    try {
		        $customer_update = \Stripe\Customer::update($customer_id,['invoice_settings'=> ['default_payment_method'=> $sid_payment_method ]]);

		    } catch (Exception $e) {
		        $api_error = $e->getMessage();
		    }



	    	// die("<hr>") ;

	        $name = $email = '';

	        // get trial subscription details in database with stripe customer_id
	        $ustd_id = 0 ;
	        $ustd_qcheck = $db->query( " SELECT * FROM `user_subscription_trial_data` WHERE stripe_customer_id LIKE '$customer_id'; " ); 

	        if ( $ustd_qcheck->num_rows > 0 ) {
	            $ustd_dcheck = $ustd_qcheck->fetch_assoc() ;

	            $ustd_id = $ustd_dcheck['id'] ;

				$planPriceCents = $ustd_dcheck["planPriceCents"] ;
				$planInterval = $ustd_dcheck["planInterval"] ;
				$planName = $ustd_dcheck["planName"] ;
				$with_trial = $ustd_dcheck["with_trial"] ;
				$coupon_id = $ustd_dcheck["coupon_id"] ;
				$discount_amount = $ustd_dcheck["discount_amount"] ;
				$subscr_plan_id = $ustd_dcheck["subscr_plan_id"] ;
				$name = $ustd_dcheck["name"] ;
				$email = $ustd_dcheck["email"] ;
				$gst_number = $ustd_dcheck["gst_number"] ;
				$country_label = $ustd_dcheck["country_label"] ;
				$cancel_url = $ustd_dcheck["cancel_url"] ;
				$planPrice = $ustd_dcheck["planPrice"] ;
				$price = $ustd_dcheck["price"] ;
				$count = $ustd_dcheck["count"] ;
				$change_id = $ustd_dcheck["change_id"] ;
				$t_Price = $ustd_dcheck["t_Price"] ;
				$sid_id = $ustd_dcheck["sid_id"] ;
				$tax_price = $ustd_dcheck["tax_price"] ; 

	        }
	        else {

				if ( !isset($_SESSION["customer_trial_data"]) || empty(count($_SESSION["customer_trial_data"]) <= 0) ) {

					// error in creating price
					$_SESSION['error'] = "User plan session expired. Please try again.";

					$return = HOST_URL.'adminpannel' ;
					header("location: ".$return) ;
					die() ;	

				}

				$planPriceCents = $_SESSION["customer_trial_data"]["planPriceCents"] ;
				$planInterval = $_SESSION["customer_trial_data"]["planInterval"] ;
				$planName = $_SESSION["customer_trial_data"]["planName"] ;
				$with_trial = $_SESSION["customer_trial_data"]["with_trial"] ;
				$coupon_id = $_SESSION["customer_trial_data"]["coupon_id"] ;
				$discount_amount = $_SESSION["customer_trial_data"]["discount_amount"] ;
				$subscr_plan_id = $_SESSION["customer_trial_data"]["subscr_plan_id"] ;
				$name = $_SESSION["customer_trial_data"]["name"] ;
				$email = $_SESSION["customer_trial_data"]["email"] ;
				$gst_number = $_SESSION["customer_trial_data"]["gst_number"] ;
				$country_label = $_SESSION["customer_trial_data"]["country_label"] ;
				$cancel_url = $_SESSION["customer_trial_data"]["cancel_url"] ;
				$planPrice = $_SESSION["customer_trial_data"]["planPrice"] ;
				$price = $_SESSION["customer_trial_data"]["price"] ;
				$count = $_SESSION["customer_trial_data"]["count"] ;
				$change_id = $_SESSION["customer_trial_data"]["change_id"] ;
				$t_Price = $_SESSION["customer_trial_data"]["t_Price"] ;
				$sid_id = $_SESSION["customer_trial_data"]["sid_id"] ;
				$tax_price = $_SESSION["customer_trial_data"]["tax_price"] ; 
	        }
	        // END get trial subscription details in database with stripe customer_id

			echo "session : " ; 
			print_r($session) ;

			if ( $session->status == "complete" ) {


				/** Create trial subscription **/
				/*** 
					$customer_id = $session->customer ;

					if ( !isset($_SESSION["customer_trial_data"]) || empty(count($_SESSION["customer_trial_data"]) <= 0) ) {

						// error in creating price
						$_SESSION['error'] = "User plan session expired. Please try again.";

						$return = HOST_URL.'adminpannel' ;
						header("location: ".$return) ;
						die() ;	

					}

					$planPriceCents = $_SESSION["customer_trial_data"]["planPriceCents"] ;
					$planInterval = $_SESSION["customer_trial_data"]["planInterval"] ;
					$planName = $_SESSION["customer_trial_data"]["planName"] ;
					$with_trial = $_SESSION["customer_trial_data"]["with_trial"] ;
					$coupon_id = $_SESSION["customer_trial_data"]["coupon_id"] ;
					$discount_amount = $_SESSION["customer_trial_data"]["discount_amount"] ;
					$subscr_plan_id = $_SESSION["customer_trial_data"]["subscr_plan_id"] ;
					$name = $_SESSION["customer_trial_data"]["name"] ;
					$email = $_SESSION["customer_trial_data"]["email"] ;
					$gst_number = $_SESSION["customer_trial_data"]["gst_number"] ;
					$country_label = $_SESSION["customer_trial_data"]["country_label"] ;
					$cancel_url = $_SESSION["customer_trial_data"]["cancel_url"] ;
					$planPrice = $_SESSION["customer_trial_data"]["planPrice"] ;
					$price = $_SESSION["customer_trial_data"]["price"] ;
					$count = $_SESSION["customer_trial_data"]["count"] ;
					$change_id = $_SESSION["customer_trial_data"]["change_id"] ;
					$t_Price = $_SESSION["customer_trial_data"]["t_Price"] ;
					$sid_id = $_SESSION["customer_trial_data"]["sid_id"] ;
					$tax_price = $_SESSION["customer_trial_data"]["tax_price"] ;
				***/



				// create subscription price
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

		            $subscription_arr = [
		                            'customer' => $customer_id,
		                            'items' => [[
		                                'price' => $price->id
		                            ]],
		                            'payment_behavior' => 'default_incomplete',
		                            'expand' => ['latest_invoice.payment_intent'],
		                            'description' => "Subscription Created",
		                            'default_tax_rates' => [
		                                  'txr_1NN9U0SAQd0TueXd7xVWAyI4'
		                            ],
		                            'payment_settings' => ['save_default_payment_method' => 'on_subscription']
		                        ] ;

		            //  for trial
		            if ( $with_trial == 1 ) {
		                $today_date = date('Y-m-d H:i:s') ;
		                $seven_days_after = strtotime($today_date . " +7 days");
		                // $seven_days_after = strtotime($today_date . " +15 minutes");

		                $subscription_arr["trial_end"] = $seven_days_after ;
		            }
		            
		            try {

		                if( $coupon_id!="" && !empty($coupon_id) ) {
		                    $subscription_arr["coupon"] = $coupon_id ;
		                }

		                $subscription = \Stripe\Subscription::create($subscription_arr); 


		            } catch (Exception $e) {
		                $api_error = $e->getMessage();
		            }





		            if (empty($api_error) && $subscription) {




		                // create subscription in database.

		                $subscriptionData = $subscription ;

		                $subscription_id = $subscription->id ;


		               	// update payment method in subscription 
					    try {
					        $subscription_update = \Stripe\Subscription::update($subscription_id,['default_payment_method'=> $sid_payment_method]);

					    } catch (Exception $e) {
					        $api_error = $e->getMessage();
					    }



						$payment_intent_id = empty($subscriptionData->payment_intent) ? 'xxxxxxxxxxxx' : $subscriptionData->payment_intent ;
						$paidAmount = 0 ;
						$paidAmount = ($paidAmount / 100);
						$paidCurrency = $subscriptionData->currency;
						$payment_status = $subscriptionData->latest_invoice->status;

				        $created = date("Y-m-d H:i:s");
				        $current_period_start = $current_period_end = '';
				        if (!empty($subscriptionData)) {
				            $created = date("Y-m-d H:i:s", $subscriptionData->created);
				            $current_period_start = date("Y-m-d H:i:s", $subscriptionData->current_period_start);
				            $current_period_end = date("Y-m-d H:i:s", $subscriptionData->current_period_end);
				        }

				        // $name = $email = '';
				        // if ( isset($_SESSION["customer_trial_data"])  && !empty($_SESSION["customer_trial_data"])) {
				        //     $name = $_SESSION["customer_trial_data"]["name"] ;
				        //     $email = $_SESSION["customer_trial_data"]["email"] ;
				        // }

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
				        } 
				        else {}



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

				             // $sid_id = $_SESSION["customer_trial_data"]["sid_id"];

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

				            // Insert transaction data into the database 
				            $sqlQ = "INSERT INTO user_subscriptions (user_id,plan_id,stripe_subscription_id,stripe_customer_id,stripe_payment_intent_id,paid_amount,total_tax,discount_amount,discount_code_id,plan_price,paid_amount_currency,plan_interval,payer_email,created,plan_period_start,plan_period_end,status,subscription_items_id,products_id,json_data,site_count,gst) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
				            $stmt = $db->prepare($sqlQ);
				            $stmt->bind_param("iisssdssssssssssssssss", $db_user_id, $db_plan_id, $db_stripe_subscription_id, $db_stripe_customer_id, $db_stripe_payment_intent_id, $db_paid_amount, $db_paid_total_tax, $bd_discount_amount,$db_discount_code_id, $db_paid_total_price, $db_paid_amount_currency, $db_plan_interval, $db_payer_email, $db_created, $db_plan_period_start, $db_plan_period_end, $db_status, $subscription_items_id, $products_id, $json_data, $site_count, $gst_number);
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
				                // $change_id = $jsonObj->change_id;
				              


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

				        
				        if ( !empty($ustd_id) ) {

						    // // Expire Checkout Session by id
						    // $session_expired = 0 ;
						    // try {
						    // 	$session_id = trim($_GET['session_id']) ;
						    //     $session = \Stripe\Checkout\Session::retrieve($session_id , []);

						    //     $session_expired = 1 ;
						    // } catch (Exception $e) {
						    //     $api_error = $e->getMessage();
						    // }

				        	$db->query( " UPDATE `user_subscription_trial_data` SET `used` = 1 WHERE `id`='$ustd_id' " ); 
				        }


						header("location: ".HOST_URL."payment/payment-status-ind.php?sid=".base64_encode($payment_id)."&change_id=".$change_id) ;
						die() ;	 

		                // END create subscription in database.

		            } 
		            else {

		            	// error in creating trialed subscription

		                if (str_contains($api_error, 'is used up and cannot be applied')) { 
		                    $api_error = "Coupon is used up and cannot be applied." ;
		                }

						// error in creating price
						$_SESSION['error'] = $api_error;

						$return = $cancel_url ;
						header("location: ".$return) ;
						die() ;	 
		            }

		        } 
		        else {
		            
					// error in creating price
					$_SESSION['error'] = $api_error;

					$return = HOST_URL ;
					$return = $cancel_url ;

					header("location: ".$return) ;
					die() ;	            
		        }

				/** END Create trial subscription **/ 

			}
			elseif ( $session->status == "open" ) {
				// payment method not saved yet
				header("location: ".$session->url) ;
				die() ;
			}
			else {

				// expired checkout session
				$_SESSION['error'] = "Checkout session expired, Please try gain later.";

				$return = HOST_URL ;
				// if ( isset($_SESSION["customer_trial_data"]) && !empty($_SESSION["customer_trial_data"]["cancel_url"]) ) {
				// 	$return = $_SESSION["customer_trial_data"]["cancel_url"] ;
				// }
				$return = $cancel_url ;

				header("location: ".$return) ;
				die() ;
			}

		}

    } 
    else {

		// echo "api_error : " ; 
		// print_r($api_error) ;

		$_SESSION['error'] = $api_error;

		$return = empty($_SERVER['HTTP_REFERER']) ? HOST_URL : $_SERVER['HTTP_REFERER'] ;
		if ( isset($_SESSION["customer_trial_data"]) && !empty($_SESSION["customer_trial_data"]["cancel_url"]) ) {
			$return = $_SESSION["customer_trial_data"]["cancel_url"] ;
		}

		header("location: ".$return) ;
		die() ;
    }

}
else {


	$return = HOST_URL.'adminpannel' ;
	header("location: ".$return) ;
	die() ;

}

