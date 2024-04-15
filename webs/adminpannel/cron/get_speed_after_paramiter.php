<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../config.php');
require_once('../inc/functions.php') ;


$queryN = $conn->query(" SELECT * FROM generate_script_request WHERE status = 1 limit 1");
if ($queryN->num_rows > 0) {
	$datas = $queryN->fetch_assoc();
	// print_r($data['website_id']);
	$project = $datas['website_id'];
$queryid =  $datas['id'];

$website_data = getTableData( $conn , " boost_website " , " id = '".$project."' " ) ;

 
$query = $conn->query("UPDATE generate_script_request SET `status`= 2 WHERE id = '$queryid' ");
 // die;
	// print_r($website_data) ;

	if ( count($website_data) > 0 ) 
	{
		$website_url = $website_data["website_url"] ;

		// desktop details
		$data = google_page_speed_insight($website_url,"desktop") ;

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
			$ps_desktop_s = round($lighthouseResult["categories"]["performance"]["score"] * 100, 2);
			 
			if($ps_desktop_s <= 0)
			{
				 echo "Desktop speed 0";
				exit;
			}

 
			$mobile_data = google_page_speed_insight($website_url,"mobile") ;

			if ( is_array($mobile_data) ) 
			{
				$mobile_lighthouseResult = $mobile_data["lighthouseResult"] ;

				$mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"])) ;
				$mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"])) ;
				$mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"])) ;
				$mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"])) ;
				$mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"])) ;
				$mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"])) ;
				$mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"])) ;

			}
			else {
				$mobile_lighthouseResult = $mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n = null ;
			}

			$ps_mobile_s = round($mobile_lighthouseResult["categories"]["performance"]["score"] * 100, 2);
			 
			if($ps_mobile_s <= 0)
			{
			    echo "Mobile speed 0";
				exit;
			}


	 
				// code...
				$sql = " INSERT INTO pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n,track_source ) VALUES ( '$project' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n','paramiter_update' ) " ;
 
 
			if ( $conn->query($sql) === TRUE ) {
 				
 				$query = $conn->query("UPDATE generate_script_request SET `status`= 3 WHERE id = '$queryid' ");
				echo "Paramiter Updated speed get successfullt";	
			    
			} 

		} 
	} 
echo "Not Found Any";	 

}

