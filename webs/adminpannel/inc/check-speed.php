<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

// echo "<pre>";
// print_r($_POST); 
if ( isset($_POST["website_url"]) ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;


		$website_url = $website_url ;
// die;
		// echo $website_url;  
		// desktop details
		$data = google_page_speed_insight($website_url,"desktop") ;
		// echo "<pre>";
		// print_r($data); die;

		if ( is_array($data) ) 
		{
			$lighthouseResult = $data["lighthouseResult"] ;
			$requestedUrl = $lighthouseResult["requestedUrl"] ;
			$finalUrl = $lighthouseResult["finalUrl"] ;
			$userAgent = $lighthouseResult["userAgent"] ;
			$fetchTime = $lighthouseResult["fetchTime"] ;
			$environment = $conn->real_escape_string(serialize($lighthouseResult["environment"])) ;
			$runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"])) ;
			$configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"])) ;
			$audits = $conn->real_escape_string(serialize($lighthouseResult["audits"])) ;
			$categories = $conn->real_escape_string(serialize($lighthouseResult["categories"])) ;
			$categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"])) ;
			$i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"])) ;



				$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform) VALUES ( '$website_id' , 0 , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','desktop' ) " ;

			// die;

			if ( $conn->query($sql) === TRUE ) {
	
					$ps_desktop_mobile="";
				
					$desktop = $lighthouseResult["categories"]["performance"]["score"] ;
					$desktop = round($desktop*100,2);

					$accessibility = $lighthouseResult["categories"]["accessibility"]["score"] ;
					$accessibility = round($accessibility*100,2);

					$bestpractices = $lighthouseResult["categories"]["best-practices"]["score"] ;
					$bestpractices = round($bestpractices*100,2);

					$seo = $lighthouseResult["categories"]["seo"]["score"] ;
					$seo = round($seo*100,2);

					$pwa = $lighthouseResult["categories"]["pwa"]["score"] ;
					$pwa = round($pwa*100,2);

					$audits = $lighthouseResult["audits"] ;
					$FCP = $audits["first-contentful-paint"]["numericValue"] ;
					$LCP = $audits["largest-contentful-paint"]["numericValue"] ;
					$MPF = $audits["max-potential-fid"]["numericValue"] ;
					
					$CLS = round($audits["cumulative-layout-shift"]["numericValue"],3) ;
					$TBT = $audits["total-blocking-time"]["numericValue"] ;
					// $desktop = 0;

				    $return = array('desktop'=> $desktop.'/100' , 'performance' => $desktop , 'accessibility' => $accessibility , 'bestpractices' => $bestpractices , 'seo'=>$seo , 'pwa'=> $pwa , 'FCP'=> $FCP , 'LCP'=> $LCP , 'MPF' => $MPF , 'CLS' => $CLS ,'requested_Url' => $ps_desktop_mobile, 'TBT' => $TBT, 'speedtype' => $speedtype, 'lastupdate' => '30secs ago','type' => 'desktop',"table_id" =>$table_id ) ;
				


			    $response = array('status' => 'done' , 'message'=> $return );
			} 
			else 
			{
			    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
			   
			}
		}
		else {
			
			$response = array('status' => 'error' , 'message'=> 'Operation timed out. Please raise your query for help.' );
		}


	echo json_encode($response) ;

}

