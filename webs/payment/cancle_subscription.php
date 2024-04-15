<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include('../adminpannel/session.php');

require_once 'config.php'; 
 $country = "USD";
// Include the database connection file 
include_once 'dbConnect.php'; 
// die;
// Include the Stripe PHP library 
require_once 'stripe-php/init.php'; 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY); 
// $subsc_id_url = $_REQUEST['sid'];
 // die;
if(isset($_REQUEST['sid']) && !empty($_REQUEST['sid'])){
     $user_id = $_SESSION['user_id'];
   echo $subsc_id = base64_decode($_REQUEST['sid']);


          // echo  $sqlQ2 = "SELECT id,subscription_id FROM boost_website WHERE manager_id = $user_id and id = '$s_id'";
          //   $stmt = $db->prepare($sqlQ2);
          //   $stmt->execute();
          //   $result2 = $stmt->get_result();
          //   $prevRow1 = $result2->fetch_assoc();
            
          //   $subsc_id = '';
          //   if (!empty($prevRow1)) {
                
               
          //       $subsc_id  = $prevRow1['subscription_id'];
          //   }




        $sqlQ = "SELECT * FROM user_subscriptions WHERE user_id = '$user_id' and id = '$subsc_id' and is_active = 1"; 
        // die;
        $stmt = $db->prepare($sqlQ);  
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        $prevRow = $result->fetch_assoc();
        // print_r($prevRow);
        // die;

        if(true){

// echo $prevRow['stripe_subscription_id'];
// die;

			$subscription = \Stripe\Subscription::retrieve($prevRow['stripe_subscription_id']);
			$subscription->cancel();
      $cancled_at = ''; 
        if(!empty($subscription)){ 
            $created = date("Y-m-d H:i:s", $subscription->created); 
            $current_period_start = date("Y-m-d H:i:s", $subscription->current_period_start); 
            $current_period_end = date("Y-m-d H:i:s", $subscription->current_period_end); 
            $cancled_at = date("Y-m-d H:i:s", $subscription->canceled_at); 
        } 


     



           $sqlQ = "UPDATE user_subscriptions SET is_active = 0, cancled_at = now() where id = '$subsc_id' "; 
                $stmt = $db->prepare($sqlQ); 
             $stmt->execute();  


       echo       $query =   "SELECT * from boost_website  where subscription_id=$subsc_id ";
             $stmt1 = $db->prepare($query); 
             $stmt1->execute(); 
             $result = $stmt1->get_result(); 
             $prevRow1 = $result->fetch_assoc();
           


          // cancel addon
         

          $sqlQ = "SELECT * FROM addon_site WHERE user_id = '".$user_id."' and site_id = '".$prevRow1['id']."' and is_active='1' "; 
        
          $stmt = $db->prepare($sqlQ);  
          $stmt->execute(); 
          $result = $stmt->get_result(); 
          $prevRow = $result->fetch_assoc();
          if(!empty($prevRow)){
  
        $subscription = \Stripe\Subscription::retrieve($prevRow['stripe_subscription_id']);
        $subscription->cancel();
          }
          //cancel addon



         echo    $queryA =   "DELETE  FROM  `additional_websites` where website_id = '".$prevRow1['id']."'";
             $stmt2 = $db->prepare($queryA); 
             $stmt2->execute();  

             $query =   "DELETE FROM  `addon_site` where site_id = '".$prevRow1['id']."'";
             $stmt1 = $db->prepare($query); 
             $stmt1->execute(); 

       echo     $sqlQ = "DELETE FROM  boost_website  where subscription_id=$subsc_id "; 
                $stmt = $db->prepare($sqlQ); 
             $stmt->execute();             

          //  $sqlQ = "UPDATE boost_website SET get_script = 0, plan_type = 'Subscription_Cancled' where subscription_id = '$subsc_id' and manager_id ='$user_id' "; 
          //       $stmt = $db->prepare($sqlQ); 
          //    $stmt->execute();             

// die;
$url = "/var/www/html";

echo "<br>";
       echo  $sqlQ = "SELECT id FROM boost_website WHERE subscription_id = '$subsc_id'and manager_id ='$user_id'  "; 
        $stmt = $db->prepare($sqlQ);  
        $stmt->execute(); 
        $result = $stmt->get_result(); 
        $run_qr = $result->fetch_assoc();
         
 

      echo $encFn = $run_qr['id'];
      $url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_1.js";
      unlink($url_F);
      $url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_2.js";
      unlink($url_F);
      $url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_3.js";
      unlink($url_F);


	$_SESSION['success'] = 'Subscription Canceled successfully.';
	header("location: ".$_SERVER['HTTP_REFERER']) ;
// echo "Subscription Cancled successfully.";


        }	
        else{

	$_SESSION['error'] = "Already Canceled";
	header("location: ".$_SERVER['HTTP_REFERER']) ;


        }

}
else{
    $_SESSION['error'] = "Something went wrong.";
    header("location: ".$_SERVER['HTTP_REFERER']) ;
}

?>