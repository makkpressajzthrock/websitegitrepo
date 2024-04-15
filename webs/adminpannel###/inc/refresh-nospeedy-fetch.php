<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
ini_set('max_execution_time', '0');

ob_start() ;
session_start();

require_once('../config.php') ;
require_once('../inc/functions.php') ;

/*** pagespeed_report table condition ***/
$pagespeed_report = PAGESPEED_REPORT_TABLE;

if ( isset($_POST) && ($_POST['action'] == "refresh-nospeedy-desktop") ) {

	$response = array('status' => '' , 'message' => '' );

	extract($_POST) ;
	$url = $conn->real_escape_string($_POST['url']) ;
	$additional = $conn->real_escape_string($_POST['additional']) ;
	$website = $conn->real_escape_string($_POST['website']) ;
	$table = $conn->real_escape_string($_POST['table']) ;
	$requestedUrl = $conn->real_escape_string($_POST['requestedUrl']) ;
	$finalUrl = $conn->real_escape_string($_POST['finalUrl']) ;
	$fetchTime = $conn->real_escape_string($_POST['fetchTime']) ;
	$userAgent = $conn->real_escape_string($_POST['userAgent']) ;


	$query = $conn->query(" SELECT  id, manager_id, website_url FROM `boost_website` WHERE `id` = '$website' ; ") ;

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

		$environment = $conn->real_escape_string(serialize($environment)) ;
		$runWarnings = $conn->real_escape_string(serialize($runWarnings)) ;
		$configSettings = $conn->real_escape_string(serialize($configSettings)) ;
		$audits = $conn->real_escape_string(serialize($audits)) ;
		$categories = $conn->real_escape_string(serialize($categories)) ;
		$categoryGroups = $conn->real_escape_string(serialize($categoryGroups)) ;
		$i18n = $conn->real_escape_string(serialize($i18n)) ;


		if ( empty($additional) || $additional == 0 || $additional == "0" ) {
			$website_id = $website ;
			$parent_website = 0 ;
		}
		else {
			$website_id = $additional ;
			$parent_website = $website ;
		}


		$desktop = $tcategories["performance"]["score"] ;
		$desktop = round($desktop*100,2);

		$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform , speed_score ) VALUES ( '$website_id' , '$parent_website' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','desktop' , '$desktop' ) ; " ;

		if ( $conn->query($sql) === TRUE ) {

			$insert_id = $conn->insert_id;

			$ps_desktop_mobile="";
		
			

			$accessibility = round( $tcategories["accessibility"]["score"] * 100 , 2) ;
			$bestpractices = round($tcategories["best-practices"]["score"]*100,2);
			$seo = round($tcategories["seo"]["score"]*100,2);
			$pwa = round($tcategories["pwa"]["score"]*100,2);

			$FCP = round($taudits["first-contentful-paint"]["numericValue"],2) ;
			$LCP = round($taudits["largest-contentful-paint"]["numericValue"],2) ;
			$MPF = round($taudits["max-potential-fid"]["numericValue"],2) ;
			$CLS = round($taudits["cumulative-layout-shift"]["numericValue"],3) ;
			$TBT = round($taudits["total-blocking-time"]["numericValue"],2) ;
			$SI = $taudits["speed-index"]["displayValue"] ;

			// $desktop = 0;

		    $return = array('desktop'=> $desktop.'/100' , 'performance' => $desktop , 'accessibility' => $accessibility , 'bestpractices' => $bestpractices , 'seo'=>$seo , 'pwa'=> $pwa , 'FCP'=> $FCP , 'LCP'=> $LCP , 'MPF' => $MPF , 'CLS' => $CLS ,'requested_Url' => $ps_desktop_mobile, 'TBT' => $TBT, 'speedtype' => $speedtype, 'lastupdate' => '30secs ago' , 'type' => 'desktop',"table_id" =>$table_id , 'SI'=>$SI ) ;
			
		    $response = array('status' => 'done' , 'message'=> $return , 'id' => $insert_id );
		} 
		else {
		    $response = array('status' => 'error' , 'message'=> 'opration failed.' , "error" => $sql."<br>".$conn->error );
		}

	}
	else {
		$response = array('status' => 'error' , 'message'=> 'No website found.' );
	}

	echo json_encode($response) ;

}
elseif ( isset($_POST) && ($_POST['action'] == "refresh-nospeedy-mobile") ) {

	$response = array('status' => '' , 'message' => '' );

	extract($_POST) ;
	$url = $conn->real_escape_string($_POST['url']) ;
	$additional = $conn->real_escape_string($_POST['additional']) ;
	$website = $conn->real_escape_string($_POST['website']) ;
	$table = $conn->real_escape_string($_POST['table']) ;
	$requestedUrl = $conn->real_escape_string($_POST['requestedUrl']) ;
	$finalUrl = $conn->real_escape_string($_POST['finalUrl']) ;
	$fetchTime = $conn->real_escape_string($_POST['fetchTime']) ;
	$userAgent = $conn->real_escape_string($_POST['userAgent']) ;


	$query = $conn->query(" SELECT  id, manager_id, website_url FROM `boost_website` WHERE `id` = '$website' ; ") ;

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

		$environment = $conn->real_escape_string(serialize($environment)) ;
		$runWarnings = $conn->real_escape_string(serialize($runWarnings)) ;
		$configSettings = $conn->real_escape_string(serialize($configSettings)) ;
		$audits = $conn->real_escape_string(serialize($audits)) ;
		$categories = $conn->real_escape_string(serialize($categories)) ;
		$categoryGroups = $conn->real_escape_string(serialize($categoryGroups)) ;
		$i18n = $conn->real_escape_string(serialize($i18n)) ;


		if ( empty($additional) || $additional == 0 || $additional == "0" ) {
			$website_id = $website ;
			$parent_website = 0 ;
		}
		else {
			$website_id = $additional ;
			$parent_website = $website ;
		}


		$desktop = $tcategories["performance"]["score"] ;
		$desktop = round($desktop*100,2);

		$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform , speed_score ) VALUES ( '$website_id' , '$parent_website' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','mobile' , '$desktop' ) ; " ;

		if ( $conn->query($sql) === TRUE ) {

			$insert_id = $conn->insert_id;

			$ps_desktop_mobile="";
		

			$accessibility = round( $tcategories["accessibility"]["score"] * 100 , 2) ;
			$bestpractices = round($tcategories["best-practices"]["score"]*100,2);
			$seo = round($tcategories["seo"]["score"]*100,2);
			$pwa = round($tcategories["pwa"]["score"]*100,2);

			$FCP = round($taudits["first-contentful-paint"]["numericValue"],2) ;
			$LCP = round($taudits["largest-contentful-paint"]["numericValue"],2) ;
			$MPF = round($taudits["max-potential-fid"]["numericValue"],2) ;
			$CLS = round($taudits["cumulative-layout-shift"]["numericValue"],3) ;
			$TBT = round($taudits["total-blocking-time"]["numericValue"],2) ;
			$SI = $taudits["speed-index"]["displayValue"] ;

			// $desktop = 0;

		    $return = array('mobile'=> $desktop.'/100' , 'performance' => $desktop , 'accessibility' => $accessibility , 'bestpractices' => $bestpractices , 'seo'=>$seo , 'pwa'=> $pwa , 'FCP'=> $FCP , 'LCP'=> $LCP , 'MPF' => $MPF , 'CLS' => $CLS ,'requested_Url' => $ps_desktop_mobile, 'TBT' => $TBT, 'speedtype' => $speedtype, 'lastupdate' => '30secs ago' , 'type' => 'desktop',"table_id" =>$table_id , 'SI'=>$SI ) ;
			
		    $response = array('status' => 'done' , 'message'=> $return , 'id' => $insert_id );
		} 
		else {
		    $response = array('status' => 'error' , 'message'=> 'opration failed.' , "error" => $sql."<br>".$conn->error );
		}

	}
	else {
		$response = array('status' => 'error' , 'message'=> 'No website found.' );
	}

	echo json_encode($response) ;

}
elseif ( isset($_POST) && ($_POST['action'] == "refresh-nospeedy-merge") ) {

	$response = array('status' => '' , 'message' => '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;


	if ( empty($additional) || $additional == 0 || $additional == "0" ) {
		$website_id = $website ;
		$parent_website = 0 ;
	}
	else {
		$website_id = $additional ;
		$parent_website = $website ;
	}

	$query = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$website_id' AND parent_website = '$parent_website' AND plateform = 'desktop' ; ") ;


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

		$queryDesk = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website' AND plateform = 'mobile'");

		if ($queryDesk->num_rows > 0) {

			$pagespeed_datadesk = $queryDesk->fetch_assoc();

			$mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['environment'])));
			$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['runWarnings'])));
			$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['configSettings'])));
			$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['audits'])));
			$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categories'])));
			$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categoryGroups'])));
			$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['i18n'])));



			// delete old no speedy record
			$conn->query(" DELETE FROM $pagespeed_report  WHERE website_id = '$website_id' AND parent_website = '$parent_website' AND no_speedy = 1 ; ");


			$sql = " INSERT INTO $pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy ) VALUES ( '$website_id' , '$parent_website' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 1 ) " ;


			if ( $conn->query($sql) === TRUE ) {

				$insert_id = $conn->insert_id;

				$query = $conn->query(" DELETE FROM temp_pagespeed_report  WHERE website_id = '$website_id' AND parent_website = '$parent_website' ");

				$response = array("status" => "done" , "message"=> "Old speed record updated." , "sql" => $insert_id );

			}
			else {
				$response = array("status" => "error" , "message"=> "Unable to update the old speed record." );
			}

		}
		else {
			$response = array("status" => "error" , "message"=> "No speed record found." );
		}

	}
	else {
		$response = array("status" => "error" , "message"=> "No speed record found." );
	}


	echo json_encode($response) ;

}