<?php
ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

require_once "config.php" ;

$sql = " SELECT * FROM `boost_website` WHERE `plan_type` IS NOT NULL AND subscription_id != 111111 ; " ;
$query = $conn->query($sql);
$plan_period_start="";
if ( $query->num_rows > 0 ) {

	$boost_websites = $query->fetch_all(MYSQLI_ASSOC);

	foreach ($boost_websites as $key => $boost_website) {
		// code...

		$refresh_views = 0 ;

		if ( $boost_website["plan_type"] == "Free" ) {	

			$sql = " SELECT * FROM `user_subscriptions_free` WHERE `id` = '".$boost_website["subscription_id"]."' AND status = '1' ; " ;
			$query = $conn->query($sql) ;

			if ( $query->num_rows > 0 ) {
				$user_subscriptions_free = $query->fetch_assoc() ;
				$plan_period_start = $user_subscriptions_free["plan_start_date"] ;
			}


		}
		elseif ( $boost_website["plan_type"] == "Subscription" ) {

			$sql = " SELECT * FROM `user_subscriptions` WHERE `id` = '".$boost_website["subscription_id"]."' AND is_active = '1' ; " ;
			$query = $conn->query($sql) ;

			if ( $query->num_rows > 0 ) {
				$user_subscriptions = $query->fetch_assoc() ;
				$plan_period_start = $user_subscriptions["plan_period_start"] ; 
			}
 

		}



			$pieces = parse_url($boost_website["website_url"]);

			$website_url = $pieces["host"] ;

			$sql = " SELECT * FROM `site_visit_count` WHERE `site_url` LIKE '%".$website_url."%' OR website_id = '".$boost_website["website_id"]."' ORDER BY id DESC LIMIT 1 ; " ;
			$query = $conn->query($sql) ;

			if ( $query->num_rows > 0 ) {
				$svc_data = $query->fetch_assoc() ;
				if($svc_data["last_refresh"] =="" || $svc_data["next_refresh"] ==""){
					$next_refresh = date('Y-m-d', strtotime('+1 month', strtotime($plan_period_start)));

					
				 	$conn->query( " UPDATE `site_visit_count` SET last_refresh = '".$plan_period_start."', next_refresh='".$next_refresh."' , website_id = '".$boost_website["website_id"]."' WHERE `id`='".$svc_data["id"]."' " );

				}

			else{

				$current_date = date('Y-m-d H:i:s') ;
				$diff = date_diff(date_create($current_date) , date_create($svc_data["next_refresh"]) ) ;

					if ( ($diff->invert == 1)) 
					{
						echo $svc_data["next_refresh"];
						print_r($diff);
					$last_refresh = $svc_data["next_refresh"];
					$next_refresh = date('Y-m-d', strtotime('+1 month', strtotime($last_refresh)));

				 	$conn->query( " UPDATE `site_visit_count` SET last_refresh = '".$last_refresh."', next_refresh='".$next_refresh."', `view_count`='0',`bandwidth`='0' , website_id = '".$boost_website["website_id"]."' WHERE `id`='".$svc_data["id"]."' " );

					}
				}	
			}
 	}

}
 