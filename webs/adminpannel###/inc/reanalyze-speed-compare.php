<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

ini_set('max_execution_time', '0');

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

// $_POST = array( "website_url" => "https://www.w3schools.com" , "speedtype" => "new" , "website_id" => "3108" , "table_id" => "new" , "wos_mobile_speed" => 58 , "action" => "reanalyze-speed-compare" ) ;
// echo "<pre>";
// print_r($_POST);
// echo "<br>"; 
// die;
function update_pagespeed_record($conn,$website_id,$highest_reanalyze,$speedtype) {

	$stats = [] ;

	$query1 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'desktop' AND reanalyze_count = '$highest_reanalyze' ; ");

	if ($query1->num_rows > 0) {

		$pagespeed_data = $query1->fetch_assoc();
		// echo "<pre>";
		// print_r($pagespeed_data);
		// echo "<br>";
		// $pagespeed_data['id'];
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


		$query2 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND reanalyze_count = '$highest_reanalyze' ; ");

		if ($query2->num_rows > 0) {

			$pagespeed_datadesk = $query2->fetch_assoc();
			// echo "<pre>";
			// print_r($pagespeed_datadesk); die;
			$mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['environment'])));
			$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['runWarnings'])));
			$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['configSettings'])));
			$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['audits'])));
			$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categories'])));
			$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categoryGroups'])));
			$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['i18n'])));


			$sql = " INSERT INTO pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) ; " ;

			if ( $conn->query($sql) === TRUE ) {

				$pr_id = $conn->insert_id;

				// now delete the temp_pagespeed_report data 
				// $query3 = $conn->query("DELETE FROM temp_pagespeed_report  WHERE website_id = '$website_id'; ");
				remove_temp_speed_data($conn,$website_id) ;

				if( $speedtype == 'new' ) {
					$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
					$conn->query($sql);
				}


				// now get updated speed and process on frontend from pagespeed_report table.

				$query4 = $conn->query(" SELECT * FROM pagespeed_report WHERE id = '$pr_id' ; ");								
				$pagespeed_data = $query4->fetch_assoc();
				$ps_categories_d = unserialize($pagespeed_data["categories"]);
				$audits_d = unserialize($pagespeed_data["audits"]);
				
				$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
				$audits_m = unserialize($pagespeed_data["mobile_audits"]);
				
				// ====
				$ps_performance = round($ps_categories_d["performance"]["score"] * 100, 2);
				$ps_desktop = $ps_performance . "/100";
				$ps_accessibility = round($ps_categories_d["accessibility"]["score"] * 100, 2);
				$ps_best_practices = round($ps_categories_d["best-practices"]["score"] * 100, 2);
				$ps_seo = round($ps_categories_d["seo"]["score"] * 100, 2);
				$ps_pwa = round($ps_categories_d["pwa"]["score"] * 100, 2);
				// ====
				
				$ps_performance_m = round($ps_categories_m["performance"]["score"] * 100, 2);
				$ps_mobile = $ps_performance_m . "/100";
				$ps_accessibility_m = round($ps_categories_m["accessibility"]["score"] * 100, 2);
				$ps_best_practices_m = round($ps_categories_m["best-practices"]["score"] * 100, 2);
				$ps_seo_m = round($ps_categories_m["seo"]["score"] * 100, 2);
				$ps_pwa_m = round($ps_categories_m["pwa"]["score"] * 100, 2);


				$stats = array('ps_desktop' => $ps_desktop , 'ps_accessibility'=>$ps_accessibility , 'ps_best_practices'=>$ps_best_practices , 'ps_seo'=>$ps_seo , 'ps_fcp'=>$audits_d["first-contentful-paint"]["numericValue"] , 'ps_lcp'=>$audits_d["largest-contentful-paint"]["numericValue"] , 'ps_cls'=>round($audits_d["cumulative-layout-shift"]["numericValue"], 3) , 'ps_tbt'=>$audits_d["total-blocking-time"]["numericValue"] , 'ps_mobile'=>$ps_mobile , 'ps_accessibility_m'=>$ps_accessibility_m , 'ps_best_practices_m'=>$ps_best_practices_m , 'ps_seo_m'=>$ps_seo_m , 'ps_fcp_m'=>$audits_m["first-contentful-paint"]["numericValue"] , 'ps_lcp_m'=>$audits_m["largest-contentful-paint"]["numericValue"] , 'ps_cls_m'=>round($audits_m["cumulative-layout-shift"]["numericValue"], 3) , 'ps_tbt_m'=>$audits_m["total-blocking-time"]["numericValue"] , 'ps_performance_m'=>$ps_performance_m );
	
			}


		}

	}


	return $stats ;
}

function remove_temp_speed_data($conn,$website_id) {
	// now delete the temp_pagespeed_report data 
	$query3 = $conn->query("DELETE FROM temp_pagespeed_report WHERE website_id = '$website_id'; ");
}

if ( isset($_POST["action"]) && ($_POST["action"] == "reanalyze-speed-compare") ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	// print_r($_POST); echo "<hr>" ;

	$query = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' ORDER BY reanalyze_count ASC; " );

	if ( $query->num_rows > 0 ) {

		$temp_pagespeed_reports = $query->fetch_all(MYSQLI_ASSOC) ;

		// check all new speeds are less than the old/initial mobile speed without script
		$lessthan_intial_speed = 1 ;
		foreach ($temp_pagespeed_reports as $key => $tpr_data) {

			if ( $tpr_data["speed_score"] > $wos_mobile_speed ) {
				// speed it greater than mobile speed without script
				$lessthan_intial_speed = 0 ;
			}
		}
		// END check all new speeds are less than the old/initial mobile speed without script

		// echo "lessthan_intial_speed : ".$lessthan_intial_speed ;

		if ( $lessthan_intial_speed == 1 ) {
			remove_temp_speed_data($conn,$website_id) ;
			$response = array('status' => 'popup' , 'message'=> 'All speeds are less than current speed.','speedtype'=>$speedtype, );
		}
		else {

			// now compare with the current updated mobile speed with script
			$highest_reanalyze = 0 ;
			$highest_mobile_speed = 0 ;
			
			foreach ($temp_pagespeed_reports as $key => $tpr_data) {

				if ( $tpr_data["speed_score"] > $current_mobile_speed ) {
					// speed it greater than mobile speed with script

					if ( empty($highest_reanalyze) ) {
						$highest_reanalyze = $tpr_data["reanalyze_count"] ;
						$highest_mobile_speed = $tpr_data["speed_score"] ;
					}
					elseif ( $tpr_data["speed_score"] > $highest_mobile_speed ) {
						$highest_reanalyze = $tpr_data["reanalyze_count"] ;
						$highest_mobile_speed = $tpr_data["speed_score"] ;
					}
				}
			}

			if ( $highest_reanalyze > 0 ) {

				$stats = update_pagespeed_record($conn,$website_id,$highest_reanalyze,$speedtype) ;
				$response = array('status' => 'done' , 'message'=> $stats,'speedtype'=>$speedtype );
				
				if($speedtype == 'new'){
					$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$project_id' ";
					$conn->query($sql);
				}
				elseif($speedtype == 'newUrl2'){
					$sql = " UPDATE boost_website SET `url2_new_speed`= 1 WHERE id = '$project_id' ";
					$conn->query($sql);
				}
				elseif($speedtype == 'newUrl3'){
					$sql = " UPDATE boost_website SET `url3_new_speed`= 1 WHERE id = '$project_id' ";
					$conn->query($sql);
				}
			}
			else {

				remove_temp_speed_data($conn,$website_id) ;
				$response = array('status' => 'nonew' , 'message'=> 'All speeds are less than current mobile speed with script.','speedtype'=>$speedtype );
			}
			
		}



		// ====================================================================

	}
	// else {
	// 	$response = array('status' => 'error' , 'message'=> 'No temp_pagespeed_report data found.','speedtype'=>$speedtype );
	// }

	// $response = array('status' => 'popup' , 'message'=> 'All speeds are less than current speed.' );
	
	echo json_encode($response) ;
	die() ;

}

