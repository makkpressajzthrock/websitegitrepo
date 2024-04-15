<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

ini_set('max_execution_time', '0');

include('../config.php');
require_once('../inc/functions.php') ;

// echo "<pre>";
// print_r($_POST);die;

if ( isset($_POST["website_url"]) ) {

	$response = array('status' => '' , 'message'=> '' );

	$website_url = $conn->real_escape_string($_POST['website_url']) ;
	$speedtype = $conn->real_escape_string($_POST['speedtype']) ;
	$website_id = $conn->real_escape_string($_POST['website_id']) ;
	$table_id = $conn->real_escape_string($_POST['table_id']) ;
	$reanalyze_count = $conn->real_escape_string($_POST['reanalyze_count']) ;
	$additional_id = $conn->real_escape_string($_POST['additional_id']) ;
	$requestedUrl = $conn->real_escape_string($_POST['requestedUrl']) ;
	$finalUrl = $conn->real_escape_string($_POST['finalUrl']) ;
	$userAgent = $conn->real_escape_string($_POST['userAgent']) ;
	$fetchTime = $conn->real_escape_string($_POST['fetchTime']) ;

	// foreach ($_POST as $key => $value) {
	// 	$_POST[$key] = $conn->real_escape_string($value) ;
	// }
	extract($_POST) ;

	$website_url = $website_url ;

	$tcategories = json_decode($categories,true) ;
	$taudits = json_decode($audits,true) ; ;

	$environment = $conn->real_escape_string(serialize(json_decode($environment,true) )) ;
	$runWarnings = $conn->real_escape_string(serialize(json_decode($runWarnings,true) )) ;
	$configSettings = $conn->real_escape_string(serialize(json_decode($configSettings,true) )) ;
	$audits = $conn->real_escape_string(serialize($taudits )) ;
	$categories = $conn->real_escape_string(serialize($tcategories )) ;
	$categoryGroups = $conn->real_escape_string(serialize(json_decode($categoryGroups,true) )) ;
	$i18n = $conn->real_escape_string(serialize(json_decode($i18n,true) )) ;

	// speed_score
	$desktop = $tcategories["performance"]["score"] ;
	$desktop = round($desktop*100,2);

	if ( empty($additional_id) || $additional_id == 0 ) {

		$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform , speed_score , reanalyze_count ) VALUES ( '$website_id' , 0 , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','desktop' , '$desktop' , '$reanalyze_count' ) " ;
	}
	else {

		$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform , speed_score , reanalyze_count ) VALUES ( '$additional_id' , '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','desktop' , '$desktop' , '$reanalyze_count' ) " ;
	}

	
	if ( $conn->query($sql) === TRUE ) {

		$ps_desktop_mobile="";
	
		// $desktop = $tcategories["performance"]["score"] ;
		// $desktop = round($desktop*100,2);

		$accessibility = $tcategories["accessibility"]["score"] ;
		$accessibility = round($accessibility*100,2);

		$bestpractices = $tcategories["best-practices"]["score"] ;
		$bestpractices = round($bestpractices*100,2);

		$seo = $tcategories["seo"]["score"] ;
		$seo = round($seo*100,2);

		$pwa = $tcategories["pwa"]["score"] ;
		$pwa = round($pwa*100,2);

		$audits = $lighthouseResult["audits"] ;
		$FCP = $taudits["first-contentful-paint"]["numericValue"] ;
		$LCP = $taudits["largest-contentful-paint"]["numericValue"] ;
		$MPF = $taudits["max-potential-fid"]["numericValue"] ;
		
		$CLS = round($taudits["cumulative-layout-shift"]["numericValue"],3) ;
		$TBT = $taudits["total-blocking-time"]["numericValue"] ;
		// $desktop = 0;

	    $return = array('desktop'=> $desktop.'/100' , 'performance' => $desktop , 'accessibility' => $accessibility , 'bestpractices' => $bestpractices , 'seo'=>$seo , 'pwa'=> $pwa , 'FCP'=> $FCP , 'LCP'=> $LCP , 'MPF' => $MPF , 'CLS' => $CLS ,'requested_Url' => $ps_desktop_mobile, 'TBT' => $TBT, 'speedtype' => $speedtype, 'lastupdate' => '30secs ago','type' => 'desktop',"table_id" =>$table_id ) ;

	    $response = array('status' => 'done' , 'message'=> $return, 'dreanalyze_count' => $reanalyze_count  );

	} 
	else {
	    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
	}

	echo json_encode($response) ;

}

