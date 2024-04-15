<?php

include('../config.php');
require_once('../inc/functions.php') ;

	$response = array('status' => '' , 'message'=> '' );
if ( isset($_POST["owner"]) && isset($_POST["status"]) ) {


	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	$manager_data = getTableData( $conn , " admin_users " , " id = '".$owner."' AND userstatus LIKE 'manager' " ) ;

	if ( empty(count($manager_data)) ) {
		echo json_encode(0) ;
	}
	else 
	{

		if ( updateTableData( $conn , " admin_users " , " active_status = '".$status."' " , " id = '".$owner."' " ) ) {
			if ($status==1) {
				$status="Active";
			}else{
				$status="Inactive";

			}
			$response['status']= "done";
			$response['message']= "User ".$status." Successfully";
		}
		else {
			$response['status']= "error";
			$response['message']= "User Update Error!";		}
			echo json_encode($response) ;

	}
	// ================================================================
}

