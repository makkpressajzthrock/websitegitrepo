<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
ini_set('max_execution_time', '0');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

ob_start() ;
session_start();

require_once('../config.php');
require_once('../inc/functions.php') ;

if ( $_POST['action'] == 'check-speed-fetch' ) {

	$response = array('status' => '' , 'message' => '' );
	extract($_POST) ;

	// echo " SELECT  id, manager_id, website_url FROM `boost_website` WHERE `manager_id` = '$manager_id' and  `id` = '$website_id' ; " ;

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

		$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform) VALUES ( '$website_id' , 0 , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','desktop' ) " ;

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



if ( $_POST['action'] == 'check-speed-mobile-fetch' ) {

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

		$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform) VALUES ( '$website_id' , 0 , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','mobile' ) " ;

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
