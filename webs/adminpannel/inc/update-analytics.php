<?php

include('../config.php');
require_once('../inc/functions.php') ;

if ( isset($_POST["period"]) ) {

	$return = array('label'=> '' , 'data'=> '' , 'color' => '' ) ;

	extract($_POST) ;

	switch ($period) 
	{
		case 'today':
			// owners chart
			$oc_sql = " SELECT DATE_FORMAT(created_at,'%e/%c') AS labels , COUNT(DATE_FORMAT(created_at,'%e/%c')) AS datas FROM `admin_users` WHERE `userstatus` LIKE 'manager' AND  DATE(created_at) = DATE(NOW()) GROUP BY labels " ;

			// subscriptions chart
			$sc_sql = " SELECT DATE_FORMAT(created,'%e/%c') AS labels , COUNT(DATE_FORMAT(created,'%e/%c')) AS datas FROM `user_subscriptions` WHERE DATE(created) = DATE(NOW()) GROUP BY labels " ;

			// sales
			$s_sql = " SELECT * FROM `user_subscriptions` WHERE DATE(created) = DATE(NOW()) " ;
			break;

		case 'this-week':
			// owners chart
			$oc_sql = " SELECT DATE_FORMAT(created_at,'%e/%c') AS labels , COUNT(DATE_FORMAT(created_at,'%e/%c')) AS datas FROM `admin_users` WHERE `userstatus` LIKE 'manager' AND YEARWEEK(created_at) = YEARWEEK(NOW()) GROUP BY labels " ;

			// subscriptions chart
			$sc_sql = " SELECT DATE_FORMAT(created,'%e/%c') AS labels , COUNT(DATE_FORMAT(created,'%e/%c')) AS datas FROM `user_subscriptions` WHERE YEARWEEK(created) = YEARWEEK(NOW()) GROUP BY labels " ;

			// sales
			$s_sql = " SELECT * FROM `user_subscriptions` WHERE YEARWEEK(created) = YEARWEEK(NOW()) " ;
			break;

		case 'one-week':
			// owners chart
			$oc_sql = " SELECT DATE_FORMAT(created_at,'%e/%c') AS labels , COUNT(DATE_FORMAT(created_at,'%e/%c')) AS datas FROM `admin_users` WHERE `userstatus` LIKE 'manager' AND DATE(created_at) - INTERVAL 1 week GROUP BY labels " ;

			// subscriptions chart
			$sc_sql = " SELECT DATE_FORMAT(created,'%e/%c') AS labels , COUNT(DATE_FORMAT(created,'%e/%c')) AS datas FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 week GROUP BY labels " ;

			// sales
			$s_sql = " SELECT * FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 week " ;
			break;

		case 'this-month':
			// owners chart
			$oc_sql = " SELECT DATE_FORMAT(created_at,'%e/%c') AS labels , COUNT(DATE_FORMAT(created_at,'%e/%c')) AS datas FROM `admin_users` WHERE  `userstatus` LIKE 'manager' AND  MONTH(created_at) = MONTH(now()) AND YEAR(created_at) = YEAR(NOW()) GROUP BY labels " ;

			// subscriptions chart
			$sc_sql = " SELECT DATE_FORMAT(created,'%e/%c') AS labels , COUNT(DATE_FORMAT(created,'%e/%c')) AS datas FROM `user_subscriptions` WHERE MONTH(created) = MONTH(now()) AND YEAR(created) = YEAR(NOW()) GROUP BY labels " ;

			// sales
			$s_sql = " SELECT * FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 month " ;
			break;

		case 'one-month':
			// owners chart
			$oc_sql = " SELECT DATE_FORMAT(created_at,'%e/%c') AS labels , COUNT(DATE_FORMAT(created_at,'%e/%c')) AS datas FROM `admin_users` WHERE  `userstatus` LIKE 'manager' AND  DATE(created_at) - INTERVAL 1 month GROUP BY labels " ;

			// subscriptions chart
			$sc_sql = " SELECT DATE_FORMAT(created,'%e/%c') AS labels , COUNT(DATE_FORMAT(created,'%e/%c')) AS datas FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 month GROUP BY labels " ;

			// sales
			$s_sql = " SELECT * FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 month " ;
			break;

		case 'this-year':
			// owners chart
			$oc_sql = " SELECT DATE_FORMAT(created_at,'%c/%y') AS labels , COUNT(DATE_FORMAT(created_at,'%c/%y')) AS datas FROM `admin_users` WHERE  `userstatus` LIKE 'manager' AND  DATE(created_at) - INTERVAL 1 YEAR AND YEAR(created_at) = YEAR(NOW()) GROUP BY labels " ;

			// subscriptions chart
			$sc_sql = " SELECT DATE_FORMAT(created,'%c/%y') AS labels , COUNT(DATE_FORMAT(created,'%c/%y')) AS datas FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 YEAR AND YEAR(created) = YEAR(NOW()) GROUP BY labels " ;

			// sales
			$s_sql = " SELECT * FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 YEAR AND YEAR(created) = YEAR(NOW())" ;
			break;

		case 'one-year':
			// owners chart
			$oc_sql = " SELECT DATE_FORMAT(created_at,'%c/%y') AS labels , COUNT(DATE_FORMAT(created_at,'%c/%y')) AS datas FROM `admin_users` WHERE  `userstatus` LIKE 'manager' AND  DATE(created_at) - INTERVAL 1 YEAR GROUP BY labels " ;

			// $oc_sql = " SELECT DATE_FORMAT(created_at,'%c/%y') AS labels , COUNT(DATE_FORMAT(created_at,'%c/%y')) AS datas  FROM `admin_users` WHERE  `userstatus` LIKE 'manager' AND  DATE(created_at) - INTERVAL 1 YEAR GROUP BY labels ORDER BY created_at DESC " ;

			// subscriptions chart
			$sc_sql = " SELECT DATE_FORMAT(created,'%c/%y') AS labels , COUNT(DATE_FORMAT(created,'%b %Y')) AS datas FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 YEAR GROUP BY labels " ;

			// sales
			$s_sql = " SELECT * FROM `user_subscriptions` WHERE DATE(created) - INTERVAL 1 YEAR " ;
			break;
	}

	$oc_sql .= " ORDER BY created_at ASC " ;
	$sc_sql .= " ORDER BY created ASC  " ;

	$sales = $website_owners = 0 ;

	// owners chart
	$owner_label = [] ; $owner_data = [] ;
	$query =  $conn->query( $oc_sql ) ;
	// echo $conn->error ;
	if ( $query->num_rows > 0 ) {
		$owners_chart = $query->fetch_all(MYSQLI_ASSOC) ;

		// print_r($owners_chart) ;

		$owner_label = array_column($owners_chart, 'labels');
		$owner_data = array_column($owners_chart, 'datas');

		$website_owners = array_sum($owner_data) ;
	}


	// subscriptions chart
	$subscription_label = [] ; $subscription_data = [] ;
	$query =  $conn->query( $sc_sql ) ;
	if ( $query->num_rows > 0 ) {
		$subscription_chart = $query->fetch_all(MYSQLI_ASSOC) ;
		$subscription_label = array_column($subscription_chart, 'labels');
		$subscription_data = array_column($subscription_chart, 'datas');
	}

	// sales
	$query =  $conn->query( $s_sql ) ;
	if ( $query->num_rows > 0 ) {
		$sales_data = $query->fetch_all(MYSQLI_ASSOC) ;
		$paid_amount = array_column($sales_data, 'paid_amount');

		$sales = array_sum($paid_amount) ;
	}

	$return = array('owner_label'=> $owner_label , 'owner_data'=> $owner_data , 'subscription_label' => $subscription_label , 'subscription_data' => $subscription_data  , 'sales' => $sales , 'website_owners' => $website_owners ) ;

	echo json_encode($return,true) ;

}

