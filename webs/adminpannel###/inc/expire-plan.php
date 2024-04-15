<?php

require_once("../config.php") ;
require_once('../inc/functions.php') ;

if ( isset($_SESSION['user_id']) && ($_SESSION['role'] == "manager") ) {
	
	$user_id = $_SESSION["user_id"] ;

	if ( updateTableData( $conn , " user_subscription " , " status = 'expired' " , " user_id = '".$user_id."' AND status LIKE 'active' " ) ) {
		echo json_encode(1);
	}
	else {
		echo json_encode(0);
	}
}