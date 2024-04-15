
<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
ini_set('max_execution_time', '0');

ob_start() ;
session_start();

require_once('../config.php');
require_once('../session.php');
require_once('../inc/functions.php') ;

//  require_once("../adminpannel/config.php") ;

// error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 



// $_POST = array( "managerId" => 3147 , "boostId" => 3204 , "websiteName" => '' , "website_url" => "https://www.makkpress.com/blogs" , "urlId3" => 0 , "url_priority" => 3 , "submitUrl3Btn" => "Submit" ) ;

// print_r($_POST) ; echo "<hr>" ;

if ( $_POST['submitUrl3Btn'] == 'Submit' ) {

	$response = array('status' => '' , 'message' => '' );


	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;

	// $website_url = "https://www.makkpress.com/contact-us" ;

	$query = $conn->query(" SELECT  id, manager_id, website_url FROM `boost_website` WHERE `manager_id` = '$managerId' and  `id` = '$boostId' ; ") ;

	if ( $query->num_rows > 0 ) {
		$output = $query->fetch_assoc();

		$url3check = $output['website_url']."/";

	    if ( ($url3check==$website_url) || ($output['website_url']== $website_url) ) {

	    	$response["status"] = "error";
	    	$response["message"] = "Duplicate URL found. Please enter unique URL of your website.";

	    }
	    else {

	    	// get speed with nospeedy

	    	if ( strpos($website_url, "?") !== FALSE ) {
	    		$request_website_url = $website_url."&nospeedy" ;
	    	}
	    	else {
	    		$request_website_url = $website_url."?nospeedy" ;
	    	}

	    	// echo "website_url : ".$website_url."<hr>" ;

	    	$data = google_page_speed_insight($request_website_url,"desktop") ;

	    	if ( is_array($data) ) {

	    		// print_r($data) ; echo "<hr>" ;

	    		if ( array_key_exists("error", $data) ) {

	    			$response["status"] = "error";
	    			$response["message"] = $data["error"]["message"];
	    			$response["message"] = "Invalid URL, can't get the speedscore. Please check entered URL & try again.";
	    		
	    		}
	    		else {

	    			$lighthouseResult = $data["lighthouseResult"] ;

					$desktop = $lighthouseResult["categories"]["performance"]["score"] ;
					$desktop = round($desktop*100,2);

					if ( $desktop > 0 ) {

						// url with sufficient score

						$sql = "INSERT INTO additional_websites ( manager_id , website_id , website_name , website_url , monitoring , flag, url_priority ) VALUES ( '$managerId' , '$boostId' , '$websiteName' , '$website_url' , 0 , 'true' , '$url_priority' ) " ;

						if ( $conn->query($sql) === TRUE ) {

							$last_insert_id_url3 = $conn->insert_id;

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

							$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform) VALUES ( '$last_insert_id_url3' , '$boostId' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','desktop' ) " ;

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
								
							    $response = array('status' => 'done' , 'message'=> $return , 'id' => $last_insert_id_url3 );
							} 
							else {
							    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
							}

						}


					}
					else {

						$response["status"] = "error";
						$response["message"] = "Can't get the speedscore of entered URL. Please check and try again.";

					}

	    		}

	    	}
	    	else {

	    		$response["status"] = "error";
	    		$response["message"] = "Can't get the speedscore of URL.";

	    	}


	    }

	}


	echo json_encode($response) ;
}


if ( $_POST['submitBtn'] == 'Submit' ) {

	$response = array('status' => '' , 'message' => '' );


	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	extract($_POST) ;

	// $website_url = "https://www.makkpress.com/contact-us" ;

	$query = $conn->query(" SELECT  id, manager_id, website_url FROM `boost_website` WHERE `manager_id` = '$managerId' and  `id` = '$boostId' ; ") ;

	if ( $query->num_rows > 0 ) {
		$output = $query->fetch_assoc();

		$url2check = $output['website_url']."/";

	    if ( ($url2check==$website_url) || ($output['website_url']== $website_url) ) {

	    	$response["status"] = "error";
	    	$response["message"] = "Duplicate URL found. Please enter unique URL of your website.";

	    }
	    else {

	    	// get speed with nospeedy

	    	if ( strpos($website_url, "?") !== FALSE ) {
	    		$request_website_url = $website_url."&nospeedy" ;
	    	}
	    	else {
	    		$request_website_url = $website_url."?nospeedy" ;
	    	}

	    	// echo "website_url : ".$website_url."<hr>" ;

	    	$data = google_page_speed_insight($request_website_url,"desktop") ;

	    	if ( is_array($data) ) {

	    		// print_r($data) ; echo "<hr>" ;

	    		if ( array_key_exists("error", $data) ) {

	    			$response["status"] = "error";
	    			$response["message"] = $data["error"]["message"];
	    			$response["message"] = "Invalid URL, can't get the speedscore. Please check entered URL & try again.";
	    		
	    		}
	    		else {

	    			$lighthouseResult = $data["lighthouseResult"] ;

					$desktop = $lighthouseResult["categories"]["performance"]["score"] ;
					$desktop = round($desktop*100,2);

					if ( $desktop > 0 ) {

						// url with sufficient score

						$sql = "INSERT INTO additional_websites ( manager_id , website_id , website_name , website_url , monitoring , flag, url_priority ) VALUES ( '$managerId' , '$boostId' , '$websiteName' , '$website_url' , 0 , 'true' , '$url_priority' ) " ;

						if ( $conn->query($sql) === TRUE ) {

							$last_insert_id_url3 = $conn->insert_id;

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

							$sql = " INSERT INTO temp_pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n ,plateform) VALUES ( '$last_insert_id_url3' , '$boostId' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n','desktop' ) " ;

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
								
							    $response = array('status' => 'done' , 'message'=> $return , 'id' => $last_insert_id_url3 );
							} 
							else {
							    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
							}

						}


					}
					else {

						$response["status"] = "error";
						$response["message"] = "Can't get the speedscore of entered URL. Please check and try again.";

					}

	    		}

	    	}
	    	else {

	    		$response["status"] = "error";
	    		$response["message"] = "Can't the speedscore of URL.";

	    	}


	    }

	}


	echo json_encode($response) ;
}