<?php

// https://ecommerceseotools.com/ecommercespeedy/adminpannel/cron/subscription-expire-mail.php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;


// get all active subscriptions ---------
 $user_subscriptions = getTableData( $conn , " user_subscriptions " , " is_active = 1 AND status LIKE 'succeeded' " , "" , 1 ) ;

//print_r($user_subscriptions);
foreach ($user_subscriptions as $key => $subscription_data) 
{
	
$date_expire = $subscription_data["plan_period_end"] ;   
$date = new DateTime($date_expire);
$now = new DateTime();

$differenceFormat = '%r%a';
  // $days_left =  $date->diff($now)->format("%d");
    $interval  = date_diff($now,$date);
    $days_left =  $interval->format($differenceFormat);

if($days_left <= 5 && $days_left >=0)
{

echo $days_left;
echo "<br>";

$sele = "select * from  boost_website where manager_id='".$subscription_data["user_id"]." ' and subscription_id = '".$subscription_data["id"]."' and plan_type = 'Subscription'";
$run= mysqli_query($conn,$sele);
$run_qr=mysqli_fetch_array($run,MYSQLI_BOTH );

echo $website=$run_qr['id'];
echo "<br>";
echo $website=$run_qr['website_url'];
echo "<br>";
echo "<br>";

   $upd = " UPDATE boost_website SET get_script = 0 where  manager_id='".$subscription_data["user_id"]." ' and subscription_id = '".$subscription_data["id"]."' and plan_type = 'Subscription'";

   $run= mysqli_query($conn,$upd);
sleep(1);
$user_id = $subscription_data["user_id"];
require_once('../generate_script_paid.php');

}


	 	// break ;
}






?>