<?php
 

require_once('../config.php');
 
 
 echo "<pre>";
 
$subsc = $conn->query("SELECT user_subscriptions_log.subscriptions_status, user_subscriptions_log.stripe_subscription_id, user_subscriptions.is_active, user_subscriptions.payer_email FROM `user_subscriptions_log`,user_subscriptions WHERE user_subscriptions_log.stripe_subscription_id = user_subscriptions.stripe_subscription_id and user_subscriptions_log.subscriptions_status='canceled' and user_subscriptions.is_active = 1") ;
 while($subs = $subsc->fetch_assoc() ) 
 {

 	$conn->query("UPDATE `user_subscriptions` SET is_active = 0 , cancled_at = now() where stripe_subscription_id = '".$subs['stripe_subscription_id']."' ") ;
 

 }


 


?>   

