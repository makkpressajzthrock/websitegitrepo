<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
// session_start();
// Include the configuration file 
require_once 'config.php';
// print_r(__DIR__);

include_once '../payment/dbConnect.php';
require_once '../payment/stripe-php/init.php';

// $userID = $_POST['user_id'];
// $userID = 3348;


// Set API key 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
// $userID = 3355;
// print_r($userID);
// die();

// Retrieve JSON from POST body 

// $query_s = $db->query("SELECT * FROM `discount`");
// echo $query_pp = $db->query("SELECT * FROM `user_subscriptions` WHERE `user_id` = $userID");
// $result_url = $query_pp->fetch_all(MYSQLI_ASSOC);

$query = "SELECT * FROM `user_subscriptions` WHERE `user_id` = $userID AND `plan_id` NOT IN ('29', '30')";

$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);



if ($row) {
    $stripe_subs_id = $row['stripe_subscription_id'];
}

$query_pp = $db->query("SELECT * FROM `additional_websites` WHERE `manager_id` = $userID");
$result_url = $query_pp->fetch_all(MYSQLI_ASSOC);

if (count($result_url) >= 1) {
    $url_1 = $result_url[0]['website_url'];
    $url_2 = $result_url[0]['website_url'];

}

$query_1st_url = $db->query("SELECT * FROM `boost_website` WHERE `manager_id` = $userID");
$result_url1 = $query_1st_url->fetch_all(MYSQLI_ASSOC);
$website_url = $result_url1[0]['website_url'];
$platform = $result_url1[0]['platform'];
$platform_name = $result_url1[0]['platform_name'];
$website_name = $result_url1[0]['website_name'];
$shopify_url = $result_url1[0]['shopify_url'];
$shopify_preview_url = $result_url1[0]['shopify_preview_url'];






$query_1st_url = $db->query("SELECT * FROM `boost_website` WHERE `manager_id` = $userID");
$result_url1 = $query_1st_url->fetch_all(MYSQLI_ASSOC);
$website_url = $result_url1[0]['website_url'];
$plan_id = $result_url1[0]['plan_id'];

// print_r($plan_id);


$plan_detail = $db->query("SELECT `name`,`interval` FROM `plans` WHERE `id` = $plan_id");
$plan_detail_result = $plan_detail->fetch_all(MYSQLI_ASSOC);


if($plan_detail_result)
{
	$plan_name = $plan_detail_result[0]['name'];
	$plan_type = $plan_detail_result[0]['interval'];
}



$query_get_info = $db->query("SELECT * FROM `admin_users` WHERE `id` = $userID");
$result_url2 = $query_get_info->fetch_all(MYSQLI_ASSOC);
// print_r($result_url2);
// die();
$user_email = $result_url2[0]['email'];
$user_phone = $result_url2[0]['phone'];
$company_role = $result_url2[0]['company_role'];


$query_get_address = $db->query("
    SELECT ba.address, ba.state, ba.city,ba.zip, ba.full_name, ba.email, c.countryname
    FROM `billing-address` AS ba
    JOIN country AS c ON ba.country = c.id
    WHERE ba.manager_id = $userID
");
$result_address = $query_get_address->fetch_all(MYSQLI_ASSOC);


$user_address = $result_address[0]['address'];
$user_state = $result_address[0]['state'];
$user_city = $result_address[0]['city'];
$billing_email = $result_address[0]['email'];
$zip = $result_address[0]['zip'];
$user_country = $result_address[0]['countryname'];
$user_full_name = $result_address[0]['full_name'];




$subscriptionId = $stripe_subs_id;

// Retrieve the subscription
$subscription = \Stripe\Subscription::retrieve($subscriptionId);

// Update subscription properties
$subscription->metadata = [
    'requested_views' => $requested_views,
    'website_1st_url' => $website_url,
    'website_second_url' => $url_1,
    'website_third_url' => $url_2,
    'platform' => $platform,
    'company_role' => $company_role, // <-- Comma was missing here
    'shopify_url' => $shopify_url,
    'shopify_preview_url' => $shopify_preview_url,
    'website_name' => $website_name,
    'user_email' => $user_email,
    'billing_email' => $billing_email,
    'user_name' => $user_full_name,
    'user_address' => $user_address,
    'user_state' => $user_state,
    'user_city' => $user_city,
    'user_country' => $user_country,
    'zip' => $zip,
    'user_phone' => $user_phone,
    'plan_type' => $plan_type,
    'plan_name' => $plan_name,
];


// Save the updated subscription
$subscription->save();

// Output success message
echo 'Subscription updated successfully!';

 ?>