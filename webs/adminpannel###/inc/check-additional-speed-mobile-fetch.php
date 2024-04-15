<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
ini_set('max_execution_time', '0');

ob_start() ;
session_start();

require_once('../config.php');
require_once('../session.php');
require_once('../inc/functions.php') ;

function real_escape_string($conn,$post) {

	foreach ($post as $key => $value) {
		if ( is_array($value) ) {
			$post[$key] = real_escape_string($conn,$post[$key]) ;
		}
		else {
			$post[$key] = $conn->real_escape_string($value) ;
		}
	}

	return $post ;
}


if ( $_POST['submitUrl3Btn'] == 'Submit' ) {

	$response = array('status' => '' , 'message' => '' );

	extract($_POST) ;

	$environment = json_decode($environment,true) ;
	$runWarnings = json_decode($runWarnings,true) ;
	$configSettings = json_decode($configSettings,true) ;
	$audits = json_decode($audits,true) ;
	$categories = json_decode($categories,true) ;
	$categoryGroups = json_decode($categoryGroups,true) ;
	$i18n = json_decode($i18n,true) ;

	$tcategories = $categories ;
	$taudits = $audits ;

	$environment = $conn->real_escape_string(serialize($environment)) ;
	$runWarnings = $conn->real_escape_string(serialize($runWarnings)) ;
	$configSettings = $conn->real_escape_string(serialize($configSettings)) ;
	$audits = $conn->real_escape_string(serialize($audits)) ;
	$categories = $conn->real_escape_string(serialize($categories)) ;
	$categoryGroups = $conn->real_escape_string(serialize($categoryGroups)) ;
	$i18n = $conn->real_escape_string(serialize($i18n)) ;

	$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n,plateform ) VALUES ( '$additional_id' , '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','mobile' ) " ;

	// die;

	if ( $conn->query($sql) === TRUE ) {

		$ps_desktop_mobile="";
	
		$desktop = $tcategories["performance"]["score"] ;
		$desktop = round($desktop*100,2);

		$accessibility = $tcategories["accessibility"]["score"] ;
		$accessibility = round($accessibility*100,2);

		$bestpractices = $tcategories["best-practices"]["score"] ;
		$bestpractices = round($bestpractices*100,2);

		$seo = $tcategories["seo"]["score"] ;
		$seo = round($seo*100,2);

		$pwa = $tcategories["pwa"]["score"] ;
		$pwa = round($pwa*100,2);

		$FCP = round($taudits["first-contentful-paint"]["numericValue"],2) ;
		$LCP = round($taudits["largest-contentful-paint"]["numericValue"],2) ;
		$MPF = round($taudits["max-potential-fid"]["numericValue"],2) ;
		
		$CLS = round($taudits["cumulative-layout-shift"]["numericValue"],3) ;
		$TBT = round($taudits["total-blocking-time"]["numericValue"],2) ;

		$SI = $taudits["speed-index"]["displayValue"] ;

	    $return = array('mobile'=> $desktop.'/100' , 'performance' => $desktop , 'accessibility' => $accessibility , 'bestpractices' => $bestpractices , 'seo'=>$seo , 'pwa'=> $pwa , 'FCP'=> $FCP , 'LCP'=> $LCP , 'MPF' => $MPF , 'CLS' => $CLS ,'requested_Url' => $ps_desktop_mobile, 'TBT' => $TBT, 'speedtype' => $speedtype, 'lastupdate' => '30secs ago','type' => 'mobile',"table_id" =>$table_id , 'SI'=>$SI  ) ;
		
	    $response = array('status' => 'done' , 'message'=> $return,'speedyType' => $speedtype );
	} 
	else {
	    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
	}

	// $website_url = "https://www.makkpress.com/contact-us" ;

	echo json_encode($response) ;
	
}


if ( $_POST['submitBtn'] == 'Submit' ) {

	$response = array('status' => '' , 'message' => '' );

	// $_POST = real_escape_string($conn,$_POST) ;

	extract($_POST) ;

	$environment = json_decode($environment,true) ;
	$runWarnings = json_decode($runWarnings,true) ;
	$configSettings = json_decode($configSettings,true) ;
	$audits = json_decode($audits,true) ;
	$categories = json_decode($categories,true) ;
	$categoryGroups = json_decode($categoryGroups,true) ;
	$i18n = json_decode($i18n,true) ;

	$tcategories = $categories ;
	$taudits = $audits ;

	$environment = $conn->real_escape_string(serialize($environment)) ;
	$runWarnings = $conn->real_escape_string(serialize($runWarnings)) ;
	$configSettings = $conn->real_escape_string(serialize($configSettings)) ;
	$audits = $conn->real_escape_string(serialize($audits)) ;
	$categories = $conn->real_escape_string(serialize($categories)) ;
	$categoryGroups = $conn->real_escape_string(serialize($categoryGroups)) ;
	$i18n = $conn->real_escape_string(serialize($i18n)) ;

	$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n,plateform ) VALUES ( '$additional_id' , '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','mobile' ) " ;

	// die;

	if ( $conn->query($sql) === TRUE ) {

		$ps_desktop_mobile="";
	
		$desktop = $tcategories["performance"]["score"] ;
		$desktop = round($desktop*100,2);

		$accessibility = $tcategories["accessibility"]["score"] ;
		$accessibility = round($accessibility*100,2);

		$bestpractices = $tcategories["best-practices"]["score"] ;
		$bestpractices = round($bestpractices*100,2);

		$seo = $tcategories["seo"]["score"] ;
		$seo = round($seo*100,2);

		$pwa = $tcategories["pwa"]["score"] ;
		$pwa = round($pwa*100,2);

		$FCP = $taudits["first-contentful-paint"]["numericValue"] ;
		$LCP = $taudits["largest-contentful-paint"]["numericValue"] ;
		$MPF = $taudits["max-potential-fid"]["numericValue"] ;
		
		$CLS = round($taudits["cumulative-layout-shift"]["numericValue"],3) ;
		$TBT = $taudits["total-blocking-time"]["numericValue"] ;

	    $return = array('mobile'=> $desktop.'/100' , 'performance' => $desktop , 'accessibility' => $accessibility , 'bestpractices' => $bestpractices , 'seo'=>$seo , 'pwa'=> $pwa , 'FCP'=> $FCP , 'LCP'=> $LCP , 'MPF' => $MPF , 'CLS' => $CLS ,'requested_Url' => $ps_desktop_mobile, 'TBT' => $TBT, 'speedtype' => $speedtype, 'lastupdate' => '30secs ago','type' => 'mobile',"table_id" =>$table_id  ) ;
		
	    $response = array('status' => 'done' , 'message'=> $return,'speedyType' => $speedtype );
	} 
	else {
	    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
	}

	// $website_url = "https://www.makkpress.com/contact-us" ;

	echo json_encode($response) ;
}