<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");


require_once("adminpannel/config.php") ;
require_once("adminpannel/inc/functions.php") ;


if ( isset($_GET) ) {
	// code...
	// echo json_encode($_GET);

	foreach ($_GET as $key => $value) {
		$_GET[$key] = $conn->real_escape_string($value) ;
	}
	extract($_GET) ;

	// check manager
	$data = getTableData( $conn , " boost_website " , " shopify_url LIKE '%$store%' OR website_url LIKE '%$store%'  " ) ;

	// $check_same_ip = getTableData( $conn , " boost_website " , " shopify_url LIKE '$store' " ) ;


	// check for unique user
	$check_data = getTableData( $conn , " website_visits " , " DATE(visit_date) = CURDATE() AND ip LIKE '$ip' AND manager_id = '".$data["manager_id"]."' AND website_id = '".$data["id"]."' " , "" , 0 , " website_visits.id " ) ;

	if ( empty(count($check_data)) ) {
		// code...
		$visit_date = date('Y-m-d H:i:s') ;

		$columns = " manager_id , ip , city , country , countryIso , latitude , longitude , timezone , website_id , browser_family , client_name , client_type , device_type , os_family , visit_date " ;
		$values = " '".$data["manager_id"]."' , '$ip' , '$city' , '$country' , '$countryIso' , '$latitude' , '$longitude' , '$timezone' , '".$data["id"]."' , '$browser_family' , '$client_name' , '$client_type' , '$device_type' , '$os_family' , '$visit_date' " ;


		if ( insertTableData( $conn , " website_visits " , $columns , $values ) ) {
		 	echo json_encode(1);
		}
		else {
			echo json_encode(0);
		}
	}
	else {
		echo json_encode(0);
	}

}

?>