<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
ini_set('max_execution_time', '0');

include('../config.php');
require_once('../inc/functions.php') ;

/*** pagespeed_report table condition ***/ 
$pagespeed_report = PAGESPEED_REPORT_TABLE;


// $_POST = array( "user_id"=> 3218 ,
// "website_id"=> 3338 ,
// "additional_url"=> "https://www.w3schools.com/w3css/default.asp" ,
// "website_name"=> "" ,
// "website_url"=> "https://www.w3schools.com" ,
// "url_priority"=> 2 ,
// "action"=> "add-additional-url" );

if ( isset($_POST) && ($_POST['action'] == "add-additional-url") ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	$query = $conn->query(" SELECT  id, manager_id, website_url FROM `boost_website` WHERE `manager_id` = '$user_id' and  `id` = '$website_id' ; ") ;

	if ( $query->num_rows > 0 ) {

		$output = $query->fetch_assoc();

		$url2check = $output['website_url']."/";

		if( ($url2check == $additional_url) || ($output['website_url']== $additional_url) ) {
			$response = array('status' => 'error' , 'message'=> "Can't add domain url again, Try with another Site URL (like product,category page etc.)" );
		}
		else {

			$sql = "INSERT INTO additional_websites ( manager_id , website_id , website_name , website_url , monitoring , flag  , url_priority ) VALUES ( '$user_id' , '$website_id' , '$website_name' , '$additional_url' , 0 , 'true' , '$url_priority' ) ; " ;

			if ( $conn->query($sql) === TRUE ) {
				$insert_id = $conn->insert_id;
				$response = array('status' => 'done' , 'message'=> $insert_id );
			}
			else {
				$response = array('status' => 'error' , 'message'=> "We are experiencing a technical error and are unable to save the site URL at the moment." );				
			}

		}

	}
	else {
		$response = array('status' => 'error' , 'message'=> 'Website details not found.' );
	}

	echo json_encode($response) ;

}
elseif ( isset($_POST) && ($_POST['action'] == "remove-additional-url") ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	$query = $conn->query(" SELECT id , website_id  FROM `additional_websites` WHERE `website_id` = '$website_id' AND id = '$additional_id' ; ") ;

	if ( $query->num_rows > 0 ) {

		// $output = $query->fetch_assoc();

		$sql = " DELETE FROM `additional_websites` WHERE id = '$additional_id' ; " ;

		if ( $conn->query($sql) === TRUE ) {
			$response = array('status' => 'done' , 'message'=> "Deleted" );
		}
		else {
			$response = array('status' => 'error' , 'message'=> $conn->error );				
		}

	}
	else {
		$response = array('status' => 'error' , 'message'=> 'Website details not found.' );
	}

	echo json_encode($response) ;

}
elseif ( $_POST['action'] == 'check-speed-fetch' ) {

	$response = array('status' => '' , 'message' => '' );
	extract($_POST) ;

	$query = $conn->query(" SELECT  id, manager_id, website_url FROM `boost_website` WHERE `manager_id` = '$manager_id' and  `id` = '$website_id' ; ") ;

	if ( $query->num_rows > 0 ) {

		$output = $query->fetch_assoc();

		$environment = json_decode($environment,true) ;
		$runWarnings = json_decode($runWarnings,true) ;
		$configSettings = json_decode($configSettings,true) ;
		$audits = json_decode($audits,true) ;
		$categories = json_decode($categories,true) ;
		$categoryGroups = json_decode($categoryGroups,true) ;
		$i18n = json_decode($i18n,true) ;

		// print_r($categories) ;

		$tcategories = $categories ;
		$taudits = $audits ;

		// $requestedUrl = $lighthouseResult["requestedUrl"] ;
		// $finalUrl = $lighthouseResult["finalUrl"] ;
		// $userAgent = $lighthouseResult["userAgent"] ;
		// $fetchTime = $lighthouseResult["fetchTime"] ;
		$environment = $conn->real_escape_string(serialize($environment)) ;
		$runWarnings = $conn->real_escape_string(serialize($runWarnings)) ;
		$configSettings = $conn->real_escape_string(serialize($configSettings)) ;
		$audits = $conn->real_escape_string(serialize($audits)) ;
		$categories = $conn->real_escape_string(serialize($categories)) ;
		$categoryGroups = $conn->real_escape_string(serialize($categoryGroups)) ;
		$i18n = $conn->real_escape_string(serialize($i18n)) ;

		$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform) VALUES ( '$additional_id' , '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','desktop' ) " ;

		if ( $conn->query($sql) === TRUE ) {

			$insert_id = $conn->insert_id;

			$ps_desktop_mobile="";
		
			$desktop = $tcategories["performance"]["score"] ;
			$desktop = round($desktop*100,2);

		    $return = array('desktop'=> $desktop.'/100' ) ;
			
		    $response = array('status' => 'done' , 'message'=> $return , 'id' => $insert_id );
		} 
		else {
		    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
		}

	}
	else {
		$response = array('status' => 'error' , 'message'=> 'No website found.' );
	}

	echo json_encode($response) ;

}
elseif ( $_POST['action'] == 'check-speed-mobile-fetch' ) {

	$response = array('status' => '' , 'message' => '' );
	extract($_POST) ;

	$query = $conn->query(" SELECT  id, manager_id, website_url FROM `boost_website` WHERE `manager_id` = '$manager_id' and  `id` = '$website_id' ; ") ;

	if ( $query->num_rows > 0 ) {
		$output = $query->fetch_assoc();


		$environment = json_decode($environment,true) ;
		$runWarnings = json_decode($runWarnings,true) ;
		$configSettings = json_decode($configSettings,true) ;
		$audits = json_decode($audits,true) ;
		$categories = json_decode($categories,true) ;
		$categoryGroups = json_decode($categoryGroups,true) ;
		$i18n = json_decode($i18n,true) ;

		// print_r($categories) ;

		$tcategories = $categories ;
		$taudits = $audits ;

		// $requestedUrl = $lighthouseResult["requestedUrl"] ;
		// $finalUrl = $lighthouseResult["finalUrl"] ;
		// $userAgent = $lighthouseResult["userAgent"] ;
		// $fetchTime = $lighthouseResult["fetchTime"] ;
		$environment = $conn->real_escape_string(serialize($environment)) ;
		$runWarnings = $conn->real_escape_string(serialize($runWarnings)) ;
		$configSettings = $conn->real_escape_string(serialize($configSettings)) ;
		$audits = $conn->real_escape_string(serialize($audits)) ;
		$categories = $conn->real_escape_string(serialize($categories)) ;
		$categoryGroups = $conn->real_escape_string(serialize($categoryGroups)) ;
		$i18n = $conn->real_escape_string(serialize($i18n)) ;

		$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform) VALUES ( '$additional_id' , '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','mobile' ) " ;

		if ( $conn->query($sql) === TRUE ) {

			$insert_id = $conn->insert_id;

			$ps_desktop_mobile="";
		
			$mobile = $tcategories["performance"]["score"] ;
			$mobile = round($mobile*100,2);

		    $return = array('mobile'=> $mobile.'/100' ) ;
			
		    $response = array('status' => 'done' , 'message'=> $return , 'id' => $insert_id );
		} 
		else {
		    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
		}


	}
	else {
		$response = array('status' => 'error' , 'message'=> 'No website found.' );
	}

	echo json_encode($response) ;

}
elseif ( isset($_POST) && ($_POST['action'] == "manage-speed-fetch") ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	$query = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$additional_id' AND parent_website = '$website_id' AND plateform = 'desktop'");
	if ($query->num_rows > 0) {

		$pagespeed_data = $query->fetch_assoc();

		$pagespeed_data['id'];
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

		$queryDesk = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$additional_id' AND parent_website = '$website_id' AND plateform = 'mobile'");

		if ($queryDesk->num_rows > 0) {

			$pagespeed_datadesk = $queryDesk->fetch_assoc();

			$mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['environment'])));
			$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['runWarnings'])));
			$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['configSettings'])));
			$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['audits'])));
			$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categories'])));
			$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categoryGroups'])));
			$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['i18n'])));


			$sql = " INSERT INTO $pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy ) VALUES ( '$additional_id' , '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 1 ) " ;


			if ( $conn->query($sql) === TRUE ) {
				
				$conn->query(" DELETE FROM temp_pagespeed_report WHERE website_id = '$additional_id' AND parent_website = '$website_id' ; ");

				if($url_priority == '2' || $url_priority == 2){
					$sql = " UPDATE boost_website SET `url2_new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}
				elseif($url_priority == '3' || $url_priority == 3){
					$sql = " UPDATE boost_website SET `url3_new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}

				echo json_encode("saved");
			}

		}

	}


}