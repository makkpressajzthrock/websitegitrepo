<?php
 session_start();
//  ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once("../adminpannel/config.php") ;
require_once("../adminpannel/inc/functions.php") ;


if(isset($_POST) && $_POST['button'] == 'GetItNow'){
    $planId = $_POST['planId'];
    $userId = $_POST['userId'];
    $websiteId = $_POST['websiteId'];
    $paid_amount_currency = $_POST['paid_amount_currency'];
    $payer_email = $_POST['payer_email'];
    $cartId = $_POST['cartId'];
    $today=date('Y-m-d H:i:s');
    $subscription_data= getTableData( $conn , " user_subscriptions" , " is_active = 1 AND status LIKE 'succeeded' AND user_id=$userId ", "order by id desc" ,  ) ;
    $subs_id = isset($subscription_data['id'])?$subscription_data['id']:'';
    
    $selboostWebsite = "SELECT `id`, `manager_id`, `website_url`, `plan_id`, `plan_type`, `subscription_id` FROM `boost_website` WHERE id=$websiteId";
    $selBoost = mysqli_query($conn,$selboostWebsite);
    $selBoost = mysqli_fetch_assoc($selBoost);
   
    if($selBoost['plan_id']=='999' && $selBoost['subscription_id']=='111111'){
        $insertNewPlan = 'insertNewPlan';
    }else{
        $insertNewPlan = '';
    }
   
    if($planId =='30'){
        $plan_interval = 'year';
        $next_date= date('Y-m-d H:i:s', strtotime($today. ' +365 days'));
    }
    else{        
        $plan_interval = 'month';
        $next_date= date('Y-m-d H:i:s', strtotime($today. ' +30 days'));
    }

    /*** start get plan views ***/
    $requested_views = 0 ;
    $pv_query = $conn->query(" SELECT page_view FROM `plans` WHERE id = '".$planId."' ; ");
    if ($pv_query->num_rows > 0) {
        $pv_data = $pv_query->fetch_assoc() ;
        $requested_views = $pv_data["page_view"] ;
    }
    /*** end get plan views ***/


    if(!empty($subscription_data['id']) && empty($insertNewPlan)){
        if($paid_amount_currency=='inr'){
            $updateSql = "UPDATE `user_subscriptions` SET `user_id`='$userId',`plan_id`='$planId',`stripe_subscription_id`='xxxxxxxxxxxx',`stripe_customer_id`='xxxxxxxxxxxx',`stripe_payment_intent_id`='xxxxxxxxxxxx',`subscription_items_id`='xxxxxxxxxxxx',`products_id`='xxxxxxxxxxxx',`paid_amount`='0.00',`total_tax`='0.00',`plan_price`='0.00',`paid_amount_currency`='inr',`site_count`='1',`plan_interval`='$plan_interval',`plan_interval_count`='1',`payer_email`='$payer_email',`created`='$today',`plan_period_start`='$today',`plan_period_end`='$next_date',`status`='succeeded',`json_data`='NULL',`is_active`='1' , requested_views = '$requested_views' WHERE `id` = '$subs_id' and `user_id` = '$userId'";
            $updateSqlResult= $conn->query($updateSql);
        }else{
            $updateSql = "UPDATE `user_subscriptions` SET `user_id`='$userId',`plan_id`='$planId',`stripe_subscription_id`='xxxxxxxxxxxx',`stripe_customer_id`='xxxxxxxxxxxx',`stripe_payment_intent_id`='xxxxxxxxxxxx',`subscription_items_id`='xxxxxxxxxxxx',`products_id`='xxxxxxxxxxxx',`paid_amount`='0.00',`total_tax`='0.00',`plan_price`='0.00',`paid_amount_currency`='usd',`site_count`='1',`plan_interval`='$plan_interval',`plan_interval_count`='1',`payer_email`='$payer_email',`created`='$today',`plan_period_start`='$today',`plan_period_end`='$next_date',`status`='succeeded',`json_data`='NULL',`is_active`='1' , requested_views = '$requested_views' WHERE `id` = '$subs_id' and `user_id` = '$userId'";
            $updateSqlResult= $conn->query($updateSql);

        }
    }else{
        if($paid_amount_currency=='inr'){

            $sqlQ = "INSERT INTO `user_subscriptions`(`user_id`, `plan_id`, `stripe_subscription_id`, `stripe_customer_id`, `stripe_payment_intent_id`, `subscription_items_id`, `products_id`, `paid_amount`, `total_tax`, `plan_price`, `paid_amount_currency`, `site_count`, `plan_interval`, `plan_interval_count`, `created`, `plan_period_start`, `plan_period_end`, `status`, `json_data`, `is_active`,  `payer_email` , requested_views) VALUES ('$userId','$planId','xxxxxxxxxxxx','xxxxxxxxxxxx','xxxxxxxxxxxx','xxxxxxxxxxxx','xxxxxxxxxxxx','0.00','0.00','0.00','inr','1','$plan_interval','1','$today','$today','$next_date','succeeded','NULL','1','$payer_email' , '$requested_views')";
            $sqlQResult = $conn->query($sqlQ);
        }else{
             $sqlQ = "INSERT INTO `user_subscriptions`(`user_id`, `plan_id`, `stripe_subscription_id`, `stripe_customer_id`, `stripe_payment_intent_id`, `subscription_items_id`, `products_id`, `paid_amount`, `total_tax`, `plan_price`, `paid_amount_currency`, `site_count`, `plan_interval`, `plan_interval_count`, `created`, `plan_period_start`, `plan_period_end`, `status`, `json_data`, `is_active`,`payer_email` , requested_views) VALUES ('$userId','$planId','xxxxxxxxxxxx','xxxxxxxxxxxx','xxxxxxxxxxxx','xxxxxxxxxxxx','xxxxxxxxxxxx','0.00','0.00','0.00','usd','1','$plan_interval','1','$today','$today','$next_date','succeeded','NULL','1','$payer_email' , '$requested_views')";
             $sqlQResult = $conn->query($sqlQ);
        }

    }   
    

    
    // $result = mysqli_query($conn, $sqlQ);
    if($updateSqlResult){
            $lastInsertedId = $subs_id; // Get the last inserted ID
            $subsTbl  = "SELECT `id`, `user_id`, `plan_id` FROM `user_subscriptions` WHERE id=$lastInsertedId";
            $subsdata = mysqli_query($conn, $subsTbl);
            $subsdata = mysqli_fetch_assoc($subsdata);
            $subs_id = $subsdata['id'];
            $plan_id = $subsdata['plan_id'];
            $update_sql= "UPDATE `boost_website` SET `plan_id`=$plan_id,`plan_type`='Subscription',`subscription_id`=$subs_id WHERE `id`= $websiteId and `manager_id`= $userId" ;
            $results = mysqli_query($conn,$update_sql);
            if($results){
                
                  $selCountryCode = "SELECT `id`, `firstname`,`email`,`country_code`, `country` FROM `admin_users` WHERE id=$userId";
                  $selData = mysqli_query($conn,$selCountryCode);
                  $selData = mysqli_fetch_assoc($selData);
                 
                $response = [
                    'status' => 1,
                    'subs_id' => base64_encode($subs_id),
                    'countryCode' => $selData
                ];
            }else{
                $response = [
                    'status' => 2,
                    'message' => 'data has not been updated',
                ];
            }
            
            
       
    }else{
        if($sqlQResult){
            $lastInsertedId = $conn->insert_id; // Get the last inserted ID
            $subsTbl  = "SELECT `id`, `user_id`, `plan_id` FROM `user_subscriptions` WHERE id=$lastInsertedId";
            $subsdata = mysqli_query($conn, $subsTbl);
            $subsdata = mysqli_fetch_assoc($subsdata);
            $subs_id = $subsdata['id'];
            $plan_id = $subsdata['plan_id'];
            $update_sql= "UPDATE `boost_website` SET `plan_id`=$plan_id,`plan_type`='Subscription',`subscription_id`=$subs_id WHERE `id`= $websiteId and `manager_id`= $userId" ;
            $results = mysqli_query($conn,$update_sql);
            if($results){
                $adminSql = "UPDATE `admin_users` SET`flow_step`='2' WHERE  `id` = $userId";
                mysqli_query($conn,$adminSql);
                $selCountryCode = "SELECT `id`, `firstname`,`email`,`country_code`, `country` FROM `admin_users` WHERE id=$userId";
                $selData = mysqli_query($conn,$selCountryCode);
                $selData = mysqli_fetch_assoc($selData);
                
                $response = [
                    'status' => 1,
                    'subs_id' => base64_encode($subs_id),
                    'countryCode' => $selData
                ];
            }else{
                $response = [
                    'status' => 2,
                    'message' => 'data has not been updated',
                ];
            }
            
            
        }else{
            $response = [
                'status' => 2,
                'message' => 'something went wrong',
            ];
        }
    }
    echo json_encode($response);
}



?>