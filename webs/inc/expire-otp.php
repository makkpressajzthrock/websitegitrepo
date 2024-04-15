<?php

require_once("../adminpannel/config.php") ;

if (isset($_POST['otp'])) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( !empty($otp) ) {

		// check email.
		$sql = " UPDATE `user_confirm` SET `otp_expire` = '1' WHERE `id` = '".$otp."'; " ;

		if ( $conn->query($sql) === TRUE ) {
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

