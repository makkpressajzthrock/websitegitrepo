<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
die;

require_once("adminpannel/config.php") ;
require_once("adminpannel/inc/functions.php") ;



if ( isset($_POST) ) {
	// code...
	// echo json_encode($_POST);
  $result = file_get_contents('php://input');	
  $response = json_decode($result);

  $store_id = base64_decode($response->encryptSx); // Let store id = 2

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

 	$id = base64_decode($encryptSx);
	$data = getTableData( $conn , " boost_website " , " id =".$store_id."" ) ;




// PHP code to extract IP 
  
function getVisIpAddr() {
      
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
  
// Store the IP address
$vis_ip = getVisIPAddr();
  
$ip = $vis_ip;
  
$ipdat = @json_decode(file_get_contents(
    "http://www.geoplugin.net/json.gp?ip=" . $ip));

   
$country = $ipdat->geoplugin_countryName;
$city = $ipdat->geoplugin_city;
$client_type = $ipdat->geoplugin_continentName ;
$latitude = $ipdat->geoplugin_latitude;
$longitude = $ipdat->geoplugin_longitude;
$timezone = $ipdat->geoplugin_timezone;
 $date = date('Y-m-d');
	// check for unique user
	$check_data = getTableData( $conn , " website_visits " , " ip LIKE '$ip' AND manager_id = '".$data["manager_id"]."' AND website_id = '".$data["id"]."' AND visit_date like '".$date."%' " , "" , 0 , " website_visits.id " ) ;

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
		if(updateTableData( $conn , " website_visits" , "updated_at=now()" ,"ip LIKE '$ip' AND manager_id = '".$data["manager_id"]."' AND website_id = '".$data["id"]."'"  )){
		 	echo json_encode(0.5);

		}else{
			echo json_encode(0);

		}
		
	}

}

?>