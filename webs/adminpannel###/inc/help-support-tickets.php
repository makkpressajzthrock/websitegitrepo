<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

ini_set('max_execution_time', '0');

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

if ( isset($_POST["action"]) && ($_POST["action"] == "insert-support-ticket") ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;


	$sql = " INSERT INTO `help_support_tickets`( `website_id`, `ticket_id`, `ticket_type` ) VALUES ( '$website_id' , '$ticket_id' , '$ticket_type' ) ; " ;

	if ( $conn->query($sql) === TRUE ) {
		echo json_encode(1) ;
	}
	else {
		echo json_decode(0) ;
	}

}