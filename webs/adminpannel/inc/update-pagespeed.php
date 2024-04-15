<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

if ( isset($_POST["project"]) ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($additional) ) {
		$website_data = getTableData( $conn , " boost_website " , " id = '".$project."' AND monitoring = 1 " ) ;
	}
	else {
		 
		$website_data = getTableData( $conn , " additional_websites " , " id = '".$additional."' AND monitoring = 1 " ) ;
	}
 
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
			// $ps_desktop_s = 0;
			if($ps_desktop_s <= 0)
			{
			  $return = array('requested_Url' => 0 ) ;
			    $response = array('status' => 'done' , 'message'=> $return );
				echo json_encode($response) ;
				exit;
			}

			// print_r($lighthouseResult["categories"]);


			// mobile details
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
			// $ps_mobile_s = 0;
			if($ps_mobile_s <= 0)
			{
			  $return = array('requested_Url' => 0 ) ;
			  $response = array('status' => 'done' , 'message'=> $return );

				echo json_encode($response) ;
				exit;
			}


			if ( empty($additional) ) {
				
				// code...
				$sql = " INSERT INTO pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy ) VALUES ( '$project' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 1 ) " ;
					$query = $conn->query(" SELECT boost_website.id , boost_website.manager_id , boost_website.platform , boost_website.website_url , boost_website.desktop_speed_new , boost_website.mobile_speed_new , pagespeed_report.audits , pagespeed_report.id AS report_id , boost_website.monitoring , boost_website.period , boost_website.plan_id, pagespeed_report.created_at FROM boost_website , pagespeed_report WHERE boost_website.id = pagespeed_report.website_id AND boost_website.id = '$project' ORDER BY report_id DESC LIMIT 1 ");
				if ($query->num_rows > 0) {
					$pagespeed_data = $query->fetch_assoc();
						$ps_audits = unserialize($pagespeed_data["audits"]);

					$fcp = $ps_audits["first-contentful-paint"]["numericValue"];
					$lcp = $ps_audits["largest-contentful-paint"]["numericValue"];
					$mpf = $ps_audits["max-potential-fid"]["numericValue"];
					$cls = round($ps_audits["cumulative-layout-shift"]["numericValue"], 3);
					$tbt = $ps_audits["total-blocking-time"]["numericValue"];
					$sql_his = "INSERT INTO website_speed_history (website_id, website_name, website_url, manager_site, desktop, mobile, performance, accissibility, best_prectices, seo, pwa, fcp, lcp,mpf,cls, tbt ,status,active_time) VALUES('$project','$website_name','$website_url','1','$ps_desktop','$ps_mobile','$ps_performance','$ps_accessibility','$ps_best_practices','$ps_seo','$ps_pwa' , '$fcp', '$lcp','$mpf','$cls', '$tbt','1',now())";

				// insertTableData( $conn , "website_speed_history" , "" , "" );
				}
				
	
			}
			else {
				// die;
				$sql = " INSERT INTO pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy ) VALUES ( '$additional' , '$project' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 1 ) " ;

				$query = $conn->query(" SELECT boost_website.id , boost_website.manager_id , boost_website.platform , boost_website.website_url , boost_website.desktop_speed_new , boost_website.mobile_speed_new , pagespeed_report.audits , pagespeed_report.id AS report_id , boost_website.monitoring , boost_website.period , boost_website.plan_id, pagespeed_report.created_at FROM boost_website , pagespeed_report WHERE boost_website.id = pagespeed_report.website_id AND boost_website.id = '$project' ORDER BY report_id DESC LIMIT 1 ");
				if ($query->num_rows > 0) {
					$pagespeed_data = $query->fetch_assoc();
						$ps_audits = unserialize($pagespeed_data["audits"]);

					$fcp = $ps_audits["first-contentful-paint"]["numericValue"];
					$lcp = $ps_audits["largest-contentful-paint"]["numericValue"];
					$mpf = $ps_audits["max-potential-fid"]["numericValue"];
					$cls = round($ps_audits["cumulative-layout-shift"]["numericValue"], 3);
					$tbt = $ps_audits["total-blocking-time"]["numericValue"];

					 $sql_his = "INSERT INTO website_speed_history (website_id,additional_url_id, website_name, website_url, manager_site, desktop, mobile, performance, accissibility, best_prectices, seo, pwa, fcp, lcp,mpf,cls, tbt,status,active_time) VALUES('$project','$additional','$website_name','$website_url','0','$ps_desktop','$ps_mobile','$ps_performance','$ps_accessibility','$ps_best_practices','$ps_seo','$ps_pwa' , '$fcp', '$lcp','$mpf','$cls', '$tbt','1',now())";
				}
			
				

			}

				

			if ( $conn->query($sql) === TRUE ) {

	

				if ( $type == "page-speed" ) {
					$last__id=$conn->insert_id;
					// code...
					$sql_2="SELECT * FROM `pagespeed_report` WHERE `id` = '".$last__id."' ORDER BY `pagespeed_report`.`id` DESC";
					$result=$conn->query($sql_2);
					$requested_Url=$result->fetch_assoc();
					$ps_mobile_categories = unserialize($requested_Url["mobile_categories"]);
					$ps_mobile = round($ps_mobile_categories["performance"]["score"] * 100, 2);
					$ps_categories = unserialize($requested_Url["categories"]);
					$ps_desktop = round($ps_categories["performance"]["score"] * 100, 2);
					$ps_desktop_mobile="";



					if($ps_desktop==0 || $ps_mobile==0){
						$ps_desktop_mobile=0;
					}else{
						$ps_desktop_mobile=1;
						// echo "sss=".$sql_his;

						if($sql_his!=""){				
						$conn->query($sql_his);
					}
// echo $sql_his;
					}

// die;



					$desktop = $lighthouseResult["categories"]["performance"]["score"] ;
					$desktop = round($desktop*100,2);


					$mobile =  is_array($mobile_data) ? $mobile_lighthouseResult["categories"]["performance"]["score"] : 0 ;
					$mobile = round($mobile*100,2);

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
					// $ps_desktop_mobile = 0;

				    $return = array('desktop'=> $desktop.'/100' , 'mobile'=> $mobile.'/100' , 'performance' => $desktop , 'accessibility' => $accessibility , 'bestpractices' => $bestpractices , 'seo'=>$seo , 'pwa'=> $pwa , 'FCP'=> $FCP , 'LCP'=> $LCP , 'MPF' => $MPF , 'CLS' => $CLS ,'requested_Url' => $ps_desktop_mobile, 'TBT' => $TBT, 'speedtype' => $speedtype, 'lastupdate' => '30secs ago' ) ;
				}
				else {
$sql_2="SELECT * FROM `pagespeed_report` WHERE `id` = '".$last__id."' ORDER BY `pagespeed_report`.`id` DESC";
					$result=$conn->query($sql_2);
					$requested_Url=$result->fetch_assoc()['requestedUrl'];
					$audits = $lighthouseResult["audits"] ;
					$FCP = $audits["first-contentful-paint"]["numericValue"] ;
					$LCP = $audits["largest-contentful-paint"]["numericValue"] ;
					$MPF = $audits["max-potential-fid"]["numericValue"] ;
					
					$CLS = round($audits["cumulative-layout-shift"]["numericValue"],3) ;
					$TBT = $audits["total-blocking-time"]["numericValue"] ;

					$return = array('FCP'=> $FCP , 'LCP'=> $LCP , 'MPF' => $MPF , 'CLS' => $CLS , 'requested_Url' => ($requested_Url==null)?0:01, 'TBT' => $TBT , 'lastupdate' => '30secs ago' ) ;
				}

			    $response = array('status' => 'done' , 'message'=> $return );
			} 
			else 
			{
			    $response = array('status' => 'error' , 'message'=> 'opration failed.' );
			    // $response = array('status' => 'error' , 'message'=> $sql.'<hr>'.$conn->error );
			}
		}
		else {
			// $response = array('status' => 'error' , 'message'=> $data );
			$response = array('status' => 'error' , 'message'=> 'Operation timed out. Please raise your query for help.' );
		}
	}
	else {
		$response = array('status' => 'error' , 'message'=> 'invalid request' );
	}

	echo json_encode($response) ;

}

