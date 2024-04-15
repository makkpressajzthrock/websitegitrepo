<?php
session_start();

include('../adminpannel/session.php');

require_once 'config.php'; 
 
// Include the database connection file 
include_once 'dbConnect.php'; 
// die;
// Include the Stripe PHP library 
require_once 'stripe-php/init.php'; 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY); 
// $subsc_id_url = $_REQUEST['sid'];


if(isset($_REQUEST['sid']) && !empty($_REQUEST['sid'])){
     $user_id = $_SESSION['user_id'];
    // $subsc_id = base64_decode($_REQUEST['sid']);
    $subsc_id = $_REQUEST['sid'];

        $sqlQ = "SELECT * FROM addon_site WHERE user_id = '$user_id' and id = '$subsc_id' and is_active = 1"; 
        // die;
        $stmt = $db->prepare($sqlQ);  
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        $prevRow = $result->fetch_assoc();
        if(!empty($prevRow)){

			$subscription = \Stripe\Subscription::retrieve($prevRow['stripe_subscription_id']);
			$subscription->cancel();


$cancled_at = ''; 
        if(!empty($subscription)){ 
            $created = date("Y-m-d H:i:s", $subscription->created); 
            $current_period_start = date("Y-m-d H:i:s", $subscription->current_period_start); 
            $current_period_end = date("Y-m-d H:i:s", $subscription->current_period_end); 
            $cancled_at = date("Y-m-d H:i:s", $subscription->canceled_at); 
        } 

                // echo $_REQUEST['site_id'];
                // echo $_REQUEST['count'];


                // code update flag
                $site_id_1 = $_REQUEST['site_id'];
                $limit = $_REQUEST['count'];

        $query =   "UPDATE additional_websites SET flag='false'  where website_id=$site_id_1 ORDER BY id desc LIMIT $limit";
        $stmt1 = $db->prepare($query); 
        $stmt1->execute();  
                // code end for update flag

                // echo "UPDATE additional_websites SET flag='false'  where website_id='$_REQUEST['site_id']' ORDER BY id desc LIMIT '$_REQUEST['count']'";
           $sqlQ = "UPDATE addon_site SET is_active = 0, cancled_at = now() where id = '$subsc_id' "; 
                $stmt = $db->prepare($sqlQ); 
             $stmt->execute();  

           // $sqlQ = "UPDATE boost_website SET get_script = 0, plan_type = 'Subscription_Cancled' where subscription_id = '$subsc_id' and manager_id ='$user_id' "; 
           //      $stmt = $db->prepare($sqlQ); 
           //   $stmt->execute();             

           // $sqlQ = "UPDATE boost_website SET get_script = 0, plan_type = 'Subscription_Cancled' where subscription_id = '$subsc_id' and manager_id ='$user_id' "; 
             //    $stmt = $db->prepare($sqlQ); 
             // $stmt->execute();             


// $url = "/var/www/html";

// echo "<br>";
//        echo  $sqlQ = "SELECT id FROM boost_website WHERE subscription_id = '$subsc_id'and manager_id ='$user_id'  "; 
//         $stmt = $db->prepare($sqlQ);  
//         $stmt->execute(); 
//         $result = $stmt->get_result(); 
         

//           while($prevRow = $result->fetch_assoc()){ 
//             echo   '<br>'. $site_id = $prevRow['id'];

//                 echo  $sqlQ1 = "SELECT url FROM script_log WHERE site_id = '$site_id' limit 1"; 
//                 $stmt1 = $db->prepare($sqlQ1);  
//                 $stmt1->execute(); 
//                 $result1 = $stmt1->get_result();
//                 $prevRow1 = $result1->fetch_assoc();
//                 $ur =  $prevRow1['url'];

// $main_url = $url.$ur;
// unlink($main_url);

//                 $sqlQ2 = "UPDATE script_log SET status = 0  WHERE site_id = '$site_id'"; 
//                 $stmt2 = $db->prepare($sqlQ2); 
//                 $stmt2->execute();  


//             }

// die;

	$_SESSION['success'] = 'Subscription Cancled successfully.';
	header("location: https://websitespeedy.com/adminpannel/subscription.php?sid=".$_REQUEST['project']) ;




        }	
        else{

	$_SESSION['error'] = "Already Cancled";
	header("location: ".$_SERVER['HTTP_REFERER']) ;


        }

}
else{
    $_SESSION['error'] = "Something went wrong.";
    header("location: ".$_SERVER['HTTP_REFERER']) ;
}

?>