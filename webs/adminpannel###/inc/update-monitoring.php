<?php

include('../config.php');
require_once('../inc/functions.php') ;

if ( isset($_POST["project"]) ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($additional) ) {
		$website_data = getTableData( $conn , " boost_website " , " id = '".$project."' " ) ;
	}
	else {
		$website_data = getTableData( $conn , " additional_websites " , " id = '".$additional."' " ) ;
	}

	// print_r($website_data) ;

	if ( count($website_data) > 0 ) 
	{
		$id = $website_data["id"] ;
		$monitoring = ($website_data["monitoring"] == 1) ? 0 : 1 ;

		if ( empty($additional) ) {
			$sql = " UPDATE boost_website SET monitoring = '$monitoring' WHERE id = '$id' ; " ;
		}
		else {
			$sql = " UPDATE additional_websites SET monitoring = '$monitoring' WHERE id = '$id' ; " ;
		}

		if ( $conn->query($sql) === TRUE ) {
		    $response = array('status' => 'done' , 'message'=> $monitoring );
		} 
		else {
		    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
		}

	}
	else {
		$response = array('status' => 'error' , 'message'=> 'invalid request' );
	}

	echo json_encode($response) ;

}

