<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../config.php');
require_once('../inc/functions.php') ;

if ( isset($_POST) && ($_POST['action'] == "back-installtion-steps") ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value);
	}

	extract($_POST) ;

	$bw_query = $conn->query(" SELECT id FROM `boost_website` WHERE `id` = '".$website_id."' ; ") ;

	if ( $bw_query->num_rows > 0 ) {

		$sql = " UPDATE `boost_website` SET `installation`='".$step."' WHERE `id` = '".$website_id."' ; " ;

		if ( $step == 1 || $step == '1' ) {
			$sql = " UPDATE `boost_website` SET `installation`='".$step."' , check_first_speed = NULL WHERE `id` = '".$website_id."' ; " ;
		}

		if ( $conn->query($sql) === TRUE ) {
			echo json_encode("installation steps are updated.") ;
		}
		else {
			echo json_encode($conn->error) ;
		}		
	}
	else {
		echo json_encode("not found.") ;
	}

}

