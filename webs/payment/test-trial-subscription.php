<pre>
<?php

error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1);

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

try {

    $subscription = \Stripe\Subscription::retrieve(
					  'sub_1OCgRVSAQd0TueXd1hd2LJaf',
					  []
					);

    echo "subscription : "; print_r($subscription) ; echo "<hr>" ;


} 
catch (Exception $e) {

    $api_error = $e->getMessage();
    echo "api_error : "; print_r($api_error) ; echo "<hr>" ;

}


echo "<hr><h2>Invoice</h2><hr>" ;

$latest_invoice = "in_1OCgg3SAQd0TueXdEYuU3gSC" ;

try {

	$invoice_obj = new \Stripe\Invoice ;


	$stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

    // $invoice_data = $stripe->invoices->sendInvoice(
	// 				  $latest_invoice,
	// 				  []
	// 				);

	// Retrieve an invoice
    $invoice_data = $stripe->invoices->retrieve(
					  $latest_invoice,
					  []
					);

    // hosted_invoice_url

    // Pay an invoice
	// $invoice_data = $stripe->invoices->pay(
	// 				  $latest_invoice,
	// 				  []
	// 				);

    echo "invoice_data : "; print_r($invoice_data) ; echo "<hr>" ;


} 
catch (Exception $e) {

    $api_error = $e->getMessage();
    echo "api_error : "; print_r($api_error) ; echo "<hr>" ;

    // sendInvoice => api_error : You can only manually send an invoice if its collection method is 'send_invoice'.
    
}


