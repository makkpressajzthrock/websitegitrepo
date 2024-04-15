<?php

require_once('../config.php');
require_once('../inc/functions.php') ;

if ( ($_POST["action"] == "edit-profile") && isset($_POST["action"]) ) {

	$status = 0 ;
	$options = '' ;

	extract($_POST) ;

	if ( isset($_POST["country"]) ) 
	{
		// get all states according to country.
		$list_states = getTableData( $conn , " list_states " , " `countryId` = '$country' " , "" , 1 ) ;
		foreach ($list_states as $key => $state_data) 
		{
			$selected = "" ;
			$options .= '<option value="'.$state_data["id"].'" '.$selected.' >'.$state_data["statename"].'</option>' ;
		}

		if ( !empty($options) ) { $status = 1 ;}
	}
	elseif (isset($_POST["state"])) 
	{
		// get all cities by state id
		$list_cities = getTableData( $conn , " list_cities " , " state_id = '".$state."' " , "" , 1 ) ;
		foreach ($list_cities as $key => $city_data) 
		{
			$selected = "" ;
			$options .= '<option value="'.$city_data["id"].'" '.$selected.' >'.$city_data["cityName"].'</option>' ;
		}

		if ( !empty($options) ) { $status = 1 ;}
	}

	$output = array('status' => $status , 'message'=>$options );
	echo json_encode($output) ;
}
