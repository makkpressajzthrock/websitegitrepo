<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

ini_set('max_execution_time', '0');

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;


if ( isset($_POST) && $_POST['action'] == "remove-additional-url" ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;

	$conn->query(" UPDATE `boost_website` SET `installation` = '1' WHERE `id` = '$website_id'; ") ;

	$sql = " DELETE FROM additional_websites WHERE id = '$additional_id' AND website_id = '$website_id' ; " ;

	if ( $conn->query($sql) === TRUE ) {
		echo json_encode(1) ;
	}
	else {
		echo json_encode(0) ;
	}

}