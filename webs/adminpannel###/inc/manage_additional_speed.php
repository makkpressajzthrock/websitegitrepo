<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

/*** pagespeed_report table condition ***/ 
$pagespeed_report = PAGESPEED_REPORT_TABLE;

if ( isset($_POST["action"]) && ( $_POST["action"] == "manage_additional_speed" ) ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;


	$query = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$additional_id' AND parent_website = '$website_id' AND plateform = 'desktop' ; ");

	if ($query->num_rows > 0) {

		$pagespeed_data = $query->fetch_assoc();

		$requestedUrl = $pagespeed_data['requestedUrl'];

		$finalUrl = $pagespeed_data['finalUrl'];
		$userAgent = $conn->real_escape_string(serialize(unserialize($pagespeed_data['userAgent'])));
		$fetchTime = $pagespeed_data['fetchTime'];
		$environment = $conn->real_escape_string(serialize(unserialize($pagespeed_data['environment'])));
		$runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_data['runWarnings'])));
		$configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_data['configSettings'])));
		$audits = $conn->real_escape_string(serialize(unserialize($pagespeed_data['audits'])));
		$categories = $conn->real_escape_string(serialize(unserialize($pagespeed_data['categories'])));
		$categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_data['categoryGroups'])));
		$i18n = $conn->real_escape_string(serialize(unserialize($pagespeed_data['i18n'])));


		$queryDesk = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$additional_id' AND parent_website = '$website_id' AND plateform = 'mobile' ; ");

		if ($queryDesk->num_rows > 0) {

			$pagespeed_datadesk = $queryDesk->fetch_assoc();

			$mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['environment'])));
			$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['runWarnings'])));
			$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['configSettings'])));
			$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['audits'])));
			$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categories'])));
			$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categoryGroups'])));
			$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['i18n'])));

			// if($speedtype == "old"){
			// 	  $conn->query("delete from pagespeed_report  WHERE website_id = '$id'");
			// }

			$sql = " INSERT INTO $pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$additional_id' , '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) " ;


			if ( $conn->query($sql) === TRUE ) {
				
				$query = $conn->query( " DELETE FROM temp_pagespeed_report  WHERE website_id = '$additional_id' AND parent_website = '$website_id' ");

				if($additional_url == 'new'){
					$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}
				elseif($additional_url == 'newUrl2'){
					$sql = " UPDATE boost_website SET `url2_new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}
				elseif($additional_url == 'newUrl3'){
					$sql = " UPDATE boost_website SET `url3_new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}

			}

		}

		echo json_encode(1) ;

	}
	else {
		echo json_encode(0) ;
	}

}
else {
	echo json_encode(0) ;
}


if ( isset($_POST["action"]) && ( $_POST["action"] == "manage_additional_nospeedy_speed" ) ) {
	// echo "<pre>";
	// print_r($_POST); die;
	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;


	$query = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$additional_id' AND parent_website = '$website_id' AND plateform = 'desktop' ; ");

	if ($query->num_rows > 0) {

		$pagespeed_data = $query->fetch_assoc();

		$requestedUrl = $pagespeed_data['requestedUrl'];

		$finalUrl = $pagespeed_data['finalUrl'];
		$userAgent = $conn->real_escape_string(serialize(unserialize($pagespeed_data['userAgent'])));
		$fetchTime = $pagespeed_data['fetchTime'];
		$environment = $conn->real_escape_string(serialize(unserialize($pagespeed_data['environment'])));
		$runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_data['runWarnings'])));
		$configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_data['configSettings'])));
		$audits = $conn->real_escape_string(serialize(unserialize($pagespeed_data['audits'])));
		$categories = $conn->real_escape_string(serialize(unserialize($pagespeed_data['categories'])));
		$categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_data['categoryGroups'])));
		$i18n = $conn->real_escape_string(serialize(unserialize($pagespeed_data['i18n'])));


		$queryDesk = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$additional_id' AND parent_website = '$website_id' AND plateform = 'mobile' ; ");

		if ($queryDesk->num_rows > 0) {

			$pagespeed_datadesk = $queryDesk->fetch_assoc();

			$mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['environment'])));
			$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['runWarnings'])));
			$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['configSettings'])));
			$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['audits'])));
			$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categories'])));
			$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categoryGroups'])));
			$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['i18n'])));

			// if($speedtype == "old"){
			// 	  $conn->query("delete from pagespeed_report  WHERE website_id = '$id'");
			// }

			$sql = "INSERT INTO $pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n, initial_url,no_speedy ) VALUES ( '$additional_id' , '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n',0,1 ) " ;


			if ( $conn->query($sql) === TRUE ) {
				
				$query = $conn->query( " DELETE FROM temp_pagespeed_report  WHERE website_id = '$additional_id' AND parent_website = '$website_id' ");

				if($additional_url == 'new'){
					$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}
				elseif($additional_url == 'newUrl2'){
					$sql = " UPDATE boost_website SET `url2_new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}
				elseif($additional_url == 'newUrl3'){
					$sql = " UPDATE boost_website SET `url3_new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}

			}

		}

		echo json_encode(1) ;

	}
	else {
		echo json_encode(0) ;
	}

}
else {
	echo json_encode(0) ;
}

