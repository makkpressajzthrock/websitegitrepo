<?php

if ( isset($_GET['project']) && !empty($_GET['project']) ) {

	$site_id = base64_decode($_GET['project']);
	$query = $conn->query(" SELECT boost_website.id , boost_website.subscription_id , boost_website.manager_id , boost_website.plan_type , boost_website.plan_id , admin_users.country FROM boost_website , admin_users WHERE boost_website.id = '$site_id' AND boost_website.manager_id = admin_users.id ; ");

	if ( $query->num_rows > 0 ) {

		$result = $query->fetch_assoc();

		if ( ($result['subscription_id'] == "1111111111") || ($result['subscription_id'] == 1111111111) || empty($result['subscription_id']) ) {

			$site_id = base64_encode($site_id);
			$plan_country = ($data['country'] != "101") ? "-us" : "" ;
			$redirect = HOST_URL."plan".$plan_country.".php?sid=".$site_id;
			header("location: ".$redirect);
			die() ;
		}

	}
	

}