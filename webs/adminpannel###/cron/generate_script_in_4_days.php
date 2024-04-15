<?php

// https://ecommerceseotools.com/ecommercespeedy/adminpannel/cron/subscription-expire-mail.php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('../config.php');
require_once('../inc/functions.php') ;


// get all active subscriptions ---------

 
echo "Today is " . date("Y-m-d") . "<br>";

$current_date = date("Y-m-d");


 $script_days = getTableData( $conn , " generate_script_on " , " id = 1 " ) ;
 $generate_days = 0;
 $generate_days = $script_days['days'];
 if($generate_days<=0){
 	$generate_days = 4;
 }



 

 $user_subscriptions = getTableData( $conn , " user_subscriptions " , " is_active = 1 AND status LIKE 'succeeded' AND script_available = 1  and (script_check_on < CURDATE() or script_check_on is null ) " , " limit 1 " , 1 ) ;


 // $user_subscriptions = getTableData( $conn , " user_subscriptions " , " id = '11' " , " limit 1 " , 1 ) ;

//print_r($user_subscriptions);
foreach ($user_subscriptions as $key => $subscription_data) 
{




 $date_expire = $subscription_data["plan_period_end"] ;
 $date_expire_start = $subscription_data["script_updated_on"] ;

	$current_date = date('Y-m-d H:i:s') ;
	$diff = date_diff(date_create($current_date) , date_create($date_expire) ) ;


	if ( ($diff->invert == 0)) 
	{
		$generate_on = date_diff(date_create($current_date) , date_create($date_expire_start) ) ;

echo $subscription_data["id"].' : ';
echo "<br>";		
print_r($generate_on);

			if ( ($generate_on->invert == 1) &&  ($generate_on->days >= $generate_days) ) 
			{

		echo ' | '.$diff->invert." | ".$diff->d;

		echo ' | '.$generate_on->invert.' | '.$generate_on->d.' | ';


		echo $sele = "select * from  boost_website where manager_id='".$subscription_data["user_id"]." ' and subscription_id = '".$subscription_data["id"]."' and plan_type = 'Subscription'";
		$run= mysqli_query($conn,$sele);
		$run_qr=mysqli_fetch_array($run,MYSQLI_BOTH );

		echo $website=$run_qr['id'];
	
		 $website=$run_qr['website_url'];
 

		   $upd = " UPDATE boost_website SET get_script = 0 where  manager_id='".$subscription_data["user_id"]." ' and subscription_id = '".$subscription_data["id"]."' and plan_type = 'Subscription'";

		   $run= mysqli_query($conn,$upd);
		sleep(1);
		$user_id = $subscription_data["user_id"];
		$site_id=$run_qr['id'];
		require_once('../generate_script_paid.php');





			}
// echo '<br>';
	}else{

		// echo $subscription_data["id"];
		 $last_genetare = $subscription_data["script_updated_on"] ;
		// echo '<br>';
		$current_date = date('Y-m-d H:i:s') ;
		$diff_last = date_diff(date_create($current_date) , date_create($last_genetare) ) ;

		 $diff_last->invert.' | '.$diff_last->d;
		$subscription_id = $subscription_data["id"];

		if ( ($diff_last->invert == 1) && ($diff_last->d >= $generate_days) ) 
		{

		 $sele = "select * from  boost_website where manager_id='".$subscription_data["user_id"]." ' and subscription_id = '".$subscription_data["id"]."' and plan_type = 'Subscription'";
		$run= mysqli_query($conn,$sele);
		$run_qr=mysqli_fetch_array($run,MYSQLI_BOTH );

		// echo $website=$run_qr['id'];

			echo $encFn = $run_qr['id'];
			$url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_1.js";
			unlink($url_F);
			$url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_2.js";
			unlink($url_F);
			$url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_3.js";
			unlink($url_F);

			   $conn->query(" UPDATE user_subscriptions SET script_available = 0 where id = $subscription_id") ;


		}



	}



 
$subscription_id = $subscription_data["id"];
  $conn->query(" UPDATE user_subscriptions SET script_check_on = CURDATE() where id = $subscription_id") ;
	 	// break ;
}






?>