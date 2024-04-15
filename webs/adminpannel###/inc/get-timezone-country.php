<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
ini_set('max_execution_time', '0');

ob_start() ;
session_start();

require_once('../config.php');

if ( isset($_POST) && $_POST['action'] == "get-timezone-country" ) {

	$return = array( "country_codes" => [] , "timezones" => [] ) ;

	$user_country = '' ;
	$query = $conn->query(" SELECT id, country, phone, email FROM `admin_users` WHERE `id` = '".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' ; ") ;
	if ( $query->num_rows > 0 ) {
		$user_data = $query->fetch_assoc() ;
		$user_country = $user_data['country'] ;
	}	

	$query = $conn->query(" SELECT id , name , phonecode FROM `list_countries` ORDER BY id ASC ; ") ;
	if ( $query->num_rows > 0 ) {
		$country_codes = $query->fetch_all(MYSQLI_ASSOC) ;
		foreach ($country_codes as $value) {
			
			$selected = ($value['id'] == $user_country) ? "selected" : "" ;

			$return["country_codes"][] = array( "name" => $value["name"] , "phonecode" => $value["phonecode"] , "selected" => $selected ) ;
		}
	}


	$query = $conn->query(" SELECT value , label FROM `timezones` ORDER BY id ASC ; ") ;
	if ( $query->num_rows > 0 ) {
		$timezones = $query->fetch_all(MYSQLI_ASSOC) ;
		$return["timezones"] = $timezones ;
	}

	echo json_encode($return) ;
}