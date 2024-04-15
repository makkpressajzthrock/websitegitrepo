<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('config.php');

$query = $conn->query(" SELECT user_subscriptions.id , user_subscriptions.user_id , user_subscriptions.plan_id , user_subscriptions.is_active , user_subscriptions.paid_amount_currency , user_subscriptions.requested_views , plans.page_view , plans.list_of_price , plans.list_of_price_inr FROM user_subscriptions , plans WHERE user_subscriptions.plan_id = plans.id AND user_subscriptions.is_active = 1; ") ;

if ( $query->num_rows > 0 ) {

	$datas = $query->fetch_all(MYSQLI_ASSOC);

	foreach ($datas as $key => $value) {
		
		if ( in_array($value["plan_id"], [4,15]) && empty($value["requested_views"]) ) {
			$conn->query(" UPDATE user_subscriptions SET requested_views = '".$value["page_view"]."' WHERE id = '".$value["id"]."' ; ") ;
		}
		elseif ( !in_array($value["plan_id"], [4,15]) && empty($value["requested_views"]) ) {
			$conn->query(" UPDATE user_subscriptions SET requested_views = '".$value["page_view"]."' WHERE id = '".$value["id"]."' ; ") ;
		}

	}

}
else {
	echo "no rows found.<br>" ;
	print_r($query) ;
}


echo "<hr>" ;

$query = $conn->query(" SELECT user_subscriptions_free.id , user_subscriptions_free.user_id , user_subscriptions_free.plan_id , user_subscriptions_free.status , user_subscriptions_free.requested_views , plans.page_view , plans.list_of_price , plans.list_of_price_inr FROM user_subscriptions_free , plans WHERE user_subscriptions_free.plan_id = plans.id AND user_subscriptions_free.status = 1; ") ;

if ( $query->num_rows > 0 ) {

	$datas = $query->fetch_all(MYSQLI_ASSOC);

	foreach ($datas as $key => $value) {
		
		if ( in_array($value["plan_id"], [4,15]) && empty($value["requested_views"]) ) {
			$conn->query(" UPDATE user_subscriptions_free SET requested_views = '".$value["page_view"]."' WHERE id = '".$value["id"]."' ; ") ;
		}
		elseif ( !in_array($value["plan_id"], [4,15]) && empty($value["requested_views"]) ) {
			$conn->query(" UPDATE user_subscriptions_free SET requested_views = '".$value["page_view"]."' WHERE id = '".$value["id"]."' ; ") ;
		}

	}

}
else {
	echo "no rows found.<br>" ;
	print_r($query) ;
}