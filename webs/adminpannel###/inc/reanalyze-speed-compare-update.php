<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

ini_set('max_execution_time', '0');

include('../config.php');
require_once('../inc/functions.php') ;

/*** pagespeed_report table condition ***/ 
$pagespeed_report = PAGESPEED_REPORT_TABLE;

// $_POST = array ( "website_url" => "https://www.makkpress.in", "website_id" => 3196, "website_ws_mobile" => 43, "website_ws_desktop" => 69, "website_wos_mobile" => 36, "website_wos_desktop" => 69, "additional1_url" => "https://www.makkpress.in/case-studies", "additional1_id" => 2507, "additional1_ws_mobile" => 66, "additional1_ws_desktop" => 95, "additional1_wos_mobile" => 40, "additional1_wos_desktop" => 95, "additional2_url" => "https://www.makkpress.in/about-us", "additional2_id" => 2508, "additional2_ws_mobile" => 39, "additional2_ws_desktop" => 70, "additional2_wos_mobile" => 39, "additional2_wos_desktop" => 59, "speedtype" => "new", "action" => "reanalyze-speed-compare-update" ) ;

function remove_temp_speed_data($conn,$website_id,$parent_website=0) {

	// return false ;

	// now delete the temp_pagespeed_report data 
	if ( empty($parent_website) ) {

		$sql = "DELETE FROM temp_pagespeed_report WHERE website_id = '$website_id' AND parent_website = 0 ; " ;
	}
	else {

		$sql = "DELETE FROM temp_pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website'; " ;
	}

	// echo "sql : ".$sql ;

	$conn->query($sql) ;
}



function update_pagespeed_record($conn,$website_id,$highest_mobile_reanalyze,$highest_desktop_reanalyze,$speedtype,$wi_status,$blank_record=NULL,$parent_website=0) {

	global $pagespeed_report;

	$stats = [] ;

	// echo "update_pagespeed_record : <br>" ;
	// echo "highest_mobile_reanalyze :".$highest_mobile_reanalyze." <br>" ;
	// echo "highest_desktop_reanalyze :".$highest_desktop_reanalyze." <br>" ;


	// pagespeed variables
	$requestedUrl = $finalUrl = $userAgent = $fetchTime = $environment = $runWarnings = $configSettings = $audits = $categories = $categoryGroups = $i18n = NULL ;

	$mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n  = NULL ;

		// old funtionality for updating the new score

		// desktop ======
		if ( empty($highest_desktop_reanalyze) ) {

			// to set old updated data 
			if ( empty($parent_website) ) {
				$query1 = $conn->query(" SELECT * FROM $pagespeed_report WHERE website_id = '$website_id' AND parent_website = 0 AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ; ");
			}
			else {
				$query1 = $conn->query(" SELECT * FROM $pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website' AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ; ");
			}

			if ($query1->num_rows > 0) {

				$pagespeed_data = $query1->fetch_assoc();

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
				
			}

		}
		else {

			if ( empty($parent_website) ) {
				$query1 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'desktop' AND id = '$highest_desktop_reanalyze' ; ");
			}
			else {
				$query1 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'desktop' AND id = '$highest_desktop_reanalyze' AND parent_website = '$parent_website' ; ");
			}

			if ($query1->num_rows > 0) {

				$pagespeed_data = $query1->fetch_assoc();

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
				
			}
		}



		// mobile ======
		if ( empty($highest_mobile_reanalyze) ) {

			if ( empty($parent_website) ) {
				$query2 = $conn->query(" SELECT * FROM $pagespeed_report WHERE website_id = '$website_id' AND parent_website = 0 AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ; ");
			}
			else {
				$query2 = $conn->query(" SELECT * FROM $pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website' AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ; ");
			}

			if ($query2->num_rows > 0) {

				$pagespeed_datadesk = $query2->fetch_assoc();

				$requestedUrl = $pagespeed_datadesk['requestedUrl'];

				$finalUrl = $pagespeed_datadesk['finalUrl'];

				$mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['mobile_environment'])));
				$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['mobile_runWarnings'])));
				$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['mobile_configSettings'])));
				$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['mobile_audits'])));
				$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['mobile_categories'])));
				$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['mobile_categoryGroups'])));
				$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['mobile_i18n'])));
			}
		}
		else {

			if ( empty($parent_website) ) {
				$query2 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND id = '$highest_mobile_reanalyze' ; ");
			}
			else {
				$query2 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND id = '$highest_mobile_reanalyze' AND parent_website = '$parent_website' ; ");
			}

			if ($query2->num_rows > 0) {

				$pagespeed_datadesk = $query2->fetch_assoc();

				$requestedUrl = $pagespeed_datadesk['requestedUrl'];

				$finalUrl = $pagespeed_datadesk['finalUrl'];

				$mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['environment'])));
				$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['runWarnings'])));
				$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['configSettings'])));
				$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['audits'])));
				$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categories'])));
				$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categoryGroups'])));
				$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['i18n'])));
			}

		}




		// insert in pagespeed_report table 
		if ( empty($parent_website) ) {
			$sql = " INSERT INTO $pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy , blank_record , ws_status ) VALUES ( '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 0 , '$blank_record' , '$wi_status' ) ; " ;
		}
		else {
			$sql = " INSERT INTO $pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy , blank_record , ws_status ) VALUES ( '$website_id' , '$parent_website' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 0 , '$blank_record' , '$wi_status' ) ; " ;
		}


	if ( $conn->query($sql) === TRUE ) {

		$pr_id = $conn->insert_id;

		// echo $sql ; echo "pr_id :".$pr_id ;

		remove_temp_speed_data($conn,$website_id,$parent_website) ;

		if( $speedtype == 'new' ) {
			$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
			$conn->query($sql);
		}


		// now get updated speed and process on frontend from pagespeed_report table.

		$query4 = $conn->query(" SELECT * FROM $pagespeed_report WHERE id = '$pr_id' ; ");								
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


		$ps_si_m = $audits_m["speed-index"]["displayValue"] ;
		$ps_si = $audits_d["speed-index"]["displayValue"] ;


		$stats = array('ps_desktop' => $ps_desktop , 'ps_accessibility'=>$ps_accessibility , 'ps_best_practices'=>$ps_best_practices , 'ps_seo'=>$ps_seo , 'ps_fcp'=>$audits_d["first-contentful-paint"]["numericValue"] , 'ps_lcp'=>$audits_d["largest-contentful-paint"]["numericValue"] , 'ps_cls'=>round($audits_d["cumulative-layout-shift"]["numericValue"], 3) , 'ps_tbt'=>$audits_d["total-blocking-time"]["numericValue"] , 'ps_mobile'=>$ps_mobile , 'ps_accessibility_m'=>$ps_accessibility_m , 'ps_best_practices_m'=>$ps_best_practices_m , 'ps_seo_m'=>$ps_seo_m , 'ps_fcp_m'=>$audits_m["first-contentful-paint"]["numericValue"] , 'ps_lcp_m'=>$audits_m["largest-contentful-paint"]["numericValue"] , 'ps_cls_m'=>round($audits_m["cumulative-layout-shift"]["numericValue"], 3) , 'ps_tbt_m'=>$audits_m["total-blocking-time"]["numericValue"] , 'ps_performance_m'=>$ps_performance_m , 'ps_performance'=>$ps_performance , 'sql' => $sql , 'pr_id' => $pr_id , 'blank_record' => $blank_record , 'ps_si_m'=>$ps_si_m , 'ps_si'=>$ps_si );

	}

	return $stats ;
}



if ( isset($_POST["action"]) && ($_POST["action"] == "reanalyze-speed-compare-update") ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	// print_r($_POST); echo"<hr>" ;

	$manual_audit_needed = 0 ;
	$manual_audit_sd = 0 ;
	$popup_count = 0 ;

	$return = array(
		'url1' => array( 'id' => $website_id , 'url' => $website_url , 'parent' => 0 , 'wi_status' => '' , 'mobile_highest_reanalyze' => '' , 'desktop_highest_reanalyze' => '' , 'stats' => '' , 'mobile_less' => '' , 'desktop_less' => '' , 'platform_less' => '' ) ,
		'url2' => array( 'id' => $additional1_id , 'url' => $additional1_url , 'parent' => $website_id , 'wi_status' => '' , 'mobile_highest_reanalyze' => '' , 'desktop_highest_reanalyze' => '' , 'stats' => '', 'mobile_less' => '' , 'desktop_less' => '' , 'platform_less' => '' ) ,
		'url3' => array( 'id' => $additional2_id , 'url' => $additional2_url , 'parent' => $website_id , 'wi_status' => '' , 'mobile_highest_reanalyze' => '' , 'desktop_highest_reanalyze' => '' , 'stats' => '', 'mobile_less' => '' , 'desktop_less' => '' , 'platform_less' => '' ) ,
		'request_manual_audit' => 0 ,
		'czs_flag' => 1 ,
		'message' => '' ,
		'popup_count' => 0 
	);

	/*** check zero temp score ***/ 

	/*** END check zero temp score ***/ 
	$czs_flag = 0 ;
	$query = $conn->query(" SELECT SUM(speed_score) AS speed_score FROM `temp_pagespeed_report` WHERE `website_id` = '$website_id' OR parent_website = '$website_id' ORDER BY `id` DESC; ") ;

	if ( $query->num_rows > 0 ) {
		$czs_data = $query->fetch_assoc() ;

		$czs_score = (float)$czs_data["speed_score"] ;

		if ( $czs_score > 0 ) {
			// not get the Zero score
			$czs_flag = 1 ;
		}
	}

	if ( $czs_flag == 1 ) {

		$return["czs_flag"] = $czs_flag ;

		/*** main website speed ***/

		$wi_mobile_less = 0 ;
		$wi_mobile_highest_reanalyze = 0 ;
		$wi_desktop_less = 0 ;
		$wi_desktop_highest_reanalyze = 0 ;

		$wi_highest_mobile_speed = $wi_highest_desktop_speed = 0 ;

		// mobile
		$query1 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND parent_website = 0 ORDER BY reanalyze_count ASC; " );

		if ( $query1->num_rows > 0 ) {

			$temp_pagespeed_reports = $query1->fetch_all(MYSQLI_ASSOC) ;

			$website_ws_mobile = ((float)$website_ws_mobile < (float)$website_wos_mobile) ? (float)$website_wos_mobile : (float)$website_ws_mobile ;

			// check all new speeds are less than the old/initial mobile speed without script
			$wi_mobile_less = 1 ;
			foreach ($temp_pagespeed_reports as $key => $tpr_data) {
				if ( (float)$tpr_data["speed_score"] > (float)$website_wos_mobile ) {
					$wi_mobile_less = 0 ; // speed it greater than mobile speed without script
				}
			}
			
			if ( $wi_mobile_less == 0 ) {

				$highest_mobile_speed = 0 ;
				
				foreach ($temp_pagespeed_reports as $key => $tpr_data) {

					if ( (float)$tpr_data["speed_score"] > (float)$website_ws_mobile ) {
						// speed it greater than mobile speed with script

						if ( empty($wi_mobile_highest_reanalyze) ) {
							$wi_mobile_highest_reanalyze = $tpr_data["id"] ;
							$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
						}
						elseif ( (float)$tpr_data["speed_score"] > $highest_mobile_speed ) {
							$wi_mobile_highest_reanalyze = $tpr_data["id"] ;
							$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
						}
					}
				}

				$wi_highest_mobile_speed = $highest_mobile_speed ;
				// echo "mobile : ".$highest_mobile_speed."<br>" ;

			}
		}

		// desktop
		$query2 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$website_id' AND plateform LIKE 'desktop' AND parent_website = 0 ORDER BY reanalyze_count ASC; " );

		if ( $query2->num_rows > 0 ) {

			$temp_pagespeed_reports = $query2->fetch_all(MYSQLI_ASSOC) ;

			$website_ws_desktop = ((float)$website_ws_desktop < (float)$website_wos_desktop) ? (float)$website_wos_desktop : (float)$website_ws_desktop ;

			// check all new speeds are less than the old/initial mobile speed without script
			$wi_desktop_less = 1 ;
			foreach ($temp_pagespeed_reports as $key => $tpr_data) {
				if ( (float)$tpr_data["speed_score"] > (float)$website_wos_desktop ) {
					$wi_desktop_less = 0 ; // speed it greater than mobile speed without script
				}
			}
			
			if ( $wi_desktop_less == 0 ) {

				$highest_mobile_speed = 0 ;
				
				foreach ($temp_pagespeed_reports as $key => $tpr_data) {

					if ( (float)$tpr_data["speed_score"] > (float)$website_ws_desktop ) {
						// speed it greater than mobile speed with script

						if ( empty($wi_desktop_highest_reanalyze) ) {
							$wi_desktop_highest_reanalyze = $tpr_data["id"] ;
							$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
						}
						elseif ( (float)$tpr_data["speed_score"] > $highest_mobile_speed ) {
							$wi_desktop_highest_reanalyze = $tpr_data["id"] ;
							$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
						}
					}
				}

				$wi_highest_desktop_speed = $highest_mobile_speed ;
				// echo "desktop : ".$highest_mobile_speed."<br>" ;
			}
		}

		// echo "wi_mobile_less : ".$wi_mobile_less."<br>" ;
		// echo "wi_mobile_highest_reanalyze : ".$wi_mobile_highest_reanalyze."<br>" ;
		// echo "wi_desktop_less : ".$wi_desktop_less."<br>" ;
		// echo "wi_desktop_highest_reanalyze : ".$wi_desktop_highest_reanalyze."<br>" ;
		// echo "<hr>" ;

		$stats = [] ;
		$platform_less = "" ;
		if ( $wi_mobile_less == 1 && $wi_desktop_less == 1 ) {
			// popup & no update
			$wi_status = "popup" ;
			$platform_less = "both" ;
			$popup_count = $popup_count + 2 ;
			// remove_temp_speed_data($conn,$website_id) ;
			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$wi_status,$platform_less) ;
		}
		elseif ( $wi_mobile_less == 1 && ($wi_desktop_less == 0 || $wi_desktop_highest_reanalyze == 0) ) {
			// popup 
			$wi_status = "popup" ;
			$platform_less = "mobile" ;
			$popup_count++ ;

			// remove_temp_speed_data($conn,$website_id) ;
			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$wi_status,$platform_less) ;

			// if ( $wi_desktop_highest_reanalyze > 0 ) {

			// 	// update only highest mobile record and blank to low record
			// 	$blank_record = "mobile" ;

			// 	$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$blank_record) ;

			// 	$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
			// 	$conn->query($sql);
			// }

		}
		elseif ( $wi_desktop_less == 1 && ($wi_mobile_less == 0 || $wi_mobile_highest_reanalyze == 0) ) {
			// popup 
			$wi_status = "popup" ;
			$platform_less = "desktop" ;
			$popup_count++ ;

			// remove_temp_speed_data($conn,$website_id) ;
			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$wi_status,$platform_less) ;

			// if ( $wi_mobile_highest_reanalyze > 0 ) {

			// 	// update only highest desktop record and blank to low record
			// 	$blank_record = "desktop" ;
			// 	$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$blank_record) ;

			// 	$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
			// 	$conn->query($sql);
			// }

		}
		elseif ( $wi_mobile_highest_reanalyze == 0 && $wi_desktop_highest_reanalyze == 0 ) {
			// nonew & no update
			$wi_status = "nonew" ;
			$platform_less = "both" ;
			// remove_temp_speed_data($conn,$website_id) ;
			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$wi_status,$platform_less) ;
		}
		elseif ( $wi_mobile_highest_reanalyze > 0 || $wi_desktop_highest_reanalyze > 0 ) {
			// done & update both mobile , desktop record
			$wi_status = "done" ;
			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$wi_status,NULL) ;

			$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
			$conn->query($sql);

			$wi_mobile_speed_difference = $wi_highest_mobile_speed - $website_ws_mobile ;
			$wi_desktop_speed_difference = $wi_highest_desktop_speed - $website_ws_desktop ;
			if ( 0 < $wi_mobile_speed_difference && $wi_mobile_speed_difference < 5  ) {
				$manual_audit_needed = 1 ;
				$manual_audit_sd = $wi_mobile_speed_difference ;
			}
			elseif ( 0 < $wi_desktop_speed_difference && $wi_desktop_speed_difference < 5  ) {
				$manual_audit_needed = 1 ;
				$manual_audit_sd = $wi_desktop_speed_difference ;
			}

		}
		else {
			$wi_status = "popup" ;
			$platform_less = "both" ;
			$popup_count = $popup_count + 2 ;
			// remove_temp_speed_data($conn,$website_id) ;
			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$wi_status,$platform_less) ;
		}

		/***elseif ( ($wi_mobile_highest_reanalyze > 0 && ($wi_desktop_highest_reanalyze == 0 && $wi_desktop_less == 0) ) 
			|| ( ($wi_mobile_highest_reanalyze == 0 && $wi_mobile_less == 0) && $wi_desktop_highest_reanalyze > 0) )  {

			// elseif ( ($wi_mobile_highest_reanalyze > 0 && ($wi_desktop_highest_reanalyze == 0 && $wi_desktop_less == 0) ) 
			// 	|| (($wi_mobile_highest_reanalyze == 0 $wi_mobile_less == 0) && $wi_desktop_highest_reanalyze > 0) ) {


			// done & update only highest record and blank to low record
			$wi_status = "done" ;

			// $blank_record = ($wi_desktop_highest_reanalyze == 0) ? "desktop" : "mobile" ;
			$blank_record = NULL ;

			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$speedtype,$blank_record) ;

			$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
			$conn->query($sql);
		}
		elseif ( ($wi_mobile_less == 1 && ($wi_desktop_less == 0 && $wi_desktop_highest_reanalyze == 0)) || 
			(($wi_mobile_less == 0 && $wi_mobile_highest_reanalyze == 0) && $wi_desktop_less == 1) ) {

			// popup & no update
			$wi_status = "popup" ;
			$platform_less = ($wi_mobile_less == 1) ? "mobile" : "desktop" ;
		}***/

		$return["url1"]["wi_status"] = $wi_status ;
		$return["url1"]["mobile_highest_reanalyze"] = $wi_mobile_highest_reanalyze ;
		$return["url1"]["desktop_highest_reanalyze"] = $wi_desktop_highest_reanalyze ;
		$return["url1"]["stats"] = $stats ;
		$return["url1"]["mobile_less"] = $wi_mobile_less ;
		$return["url1"]["desktop_less"] = $wi_desktop_less ;
		$return["url1"]["platform_less"] = $platform_less ;

		/*** END main website speed ***/


		/*** main additional1 website speed ***/
		if ( !empty($additional1_id) ) {

			$aw1_mobile_less = 0 ;
			$aw1_mobile_highest_reanalyze = 0 ;
			$aw1_desktop_less = 0 ;
			$aw1_desktop_highest_reanalyze = 0 ;

			$aw1_highest_mobile_speed = $aw1_highest_desktop_speed = 0 ;

			// mobile
			$query3 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$additional1_id' AND plateform LIKE 'mobile' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " );

			if ( $query3->num_rows > 0 ) {

				$temp_pagespeed_reports = $query3->fetch_all(MYSQLI_ASSOC) ;

				$additional1_ws_mobile = ((float)$additional1_ws_mobile < (float)$additional1_wos_mobile) ? (float)$additional1_wos_mobile : (float)$additional1_ws_mobile ;

				// check all new speeds are less than the old/initial mobile speed without script
				$aw1_mobile_less = 1 ;
				foreach ($temp_pagespeed_reports as $key => $tpr_data) {
					if ( (float)$tpr_data["speed_score"] > (float)$additional1_wos_mobile ) {
						$aw1_mobile_less = 0 ; // speed it greater than mobile speed without script
					}
				}
				
				if ( $aw1_mobile_less == 0 ) {

					$highest_mobile_speed = 0 ;
					
					foreach ($temp_pagespeed_reports as $key => $tpr_data) {

						if ( (float)$tpr_data["speed_score"] > (float)$additional1_ws_mobile ) {
							// speed it greater than mobile speed with script

							if ( empty($aw1_mobile_highest_reanalyze) ) {
								$aw1_mobile_highest_reanalyze = $tpr_data["id"] ;
								$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
							}
							elseif ( (float)$tpr_data["speed_score"] > $highest_mobile_speed ) {
								$aw1_mobile_highest_reanalyze = $tpr_data["id"] ;
								$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
							}
						}
					}

					$aw1_highest_mobile_speed = $highest_mobile_speed ;
					// echo "mobile : ".$highest_mobile_speed."<br>" ;
				}
			}

			// desktop
			$query4 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$additional1_id' AND plateform LIKE 'desktop' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " );

			if ( $query4->num_rows > 0 ) {

				$temp_pagespeed_reports = $query4->fetch_all(MYSQLI_ASSOC) ;

				$additional1_ws_desktop = ((float)$additional1_ws_desktop < (float)$additional1_wos_desktop) ? (float)$additional1_wos_desktop : (float)$additional1_ws_desktop ;

				// check all new speeds are less than the old/initial mobile speed without script
				$aw1_desktop_less = 1 ;
				foreach ($temp_pagespeed_reports as $key => $tpr_data) {
					if ( (float)$tpr_data["speed_score"] > (float)$additional1_wos_desktop ) {
						$aw1_desktop_less = 0 ; // speed it greater than mobile speed without script
					}
				}
				
				if ( $aw1_desktop_less == 0 ) {

					$highest_mobile_speed = 0 ;
					
					foreach ($temp_pagespeed_reports as $key => $tpr_data) {

						if ( (float)$tpr_data["speed_score"] > (float)$additional1_ws_desktop ) {
							// speed it greater than mobile speed with script

							if ( empty($aw1_desktop_highest_reanalyze) ) {
								$aw1_desktop_highest_reanalyze = $tpr_data["id"] ;
								$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
							}
							elseif ( (float)$tpr_data["speed_score"] > $highest_mobile_speed ) {
								$aw1_desktop_highest_reanalyze = $tpr_data["id"] ;
								$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
							}
						}
					}

					$aw1_highest_desktop_speed = $highest_mobile_speed ;
					// echo "desktop : ".$highest_mobile_speed."<br>" ;

				}
			}

			// echo "aw1_mobile_less : ".$aw1_mobile_less."<br>" ;
			// echo "aw1_mobile_highest_reanalyze : ".$aw1_mobile_highest_reanalyze."<br>" ;
			// echo "aw1_desktop_less : ".$aw1_desktop_less."<br>" ;
			// echo "aw1_desktop_highest_reanalyze : ".$aw1_desktop_highest_reanalyze."<br>" ;
			// echo "<hr>" ;

			$stats = [] ;
			$platform_less = "" ;
			if ( $aw1_mobile_less == 1 && $aw1_desktop_less == 1 ) {
				// popup & no update
				$aw1_status = "popup" ;
				$platform_less = "both" ;
				$popup_count = $popup_count + 2 ;
				// remove_temp_speed_data($conn,$additional1_id,$website_id) ;
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$aw1_status,$platform_less,$website_id) ;
			}
			elseif ( $aw1_mobile_less == 1 && ($aw1_desktop_less == 0 || $aw1_desktop_highest_reanalyze == 0)  ) {
				// popup 
				$aw1_status = "popup" ;
				$platform_less = "mobile" ;
				$popup_count++ ;
				// remove_temp_speed_data($conn,$additional1_id,$website_id) ;
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$aw1_status,$platform_less,$website_id) ;

				// if ( $aw1_desktop_highest_reanalyze > 0 ) {

				// 	// update only highest mobile record and blank to low record
				// 	$blank_record = "mobile" ;

				// 	$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$blank_record,$website_id) ;


				// 	$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				// 	$conn->query($sql);
				// }

			}
			elseif ( $aw1_desktop_less == 1 && ($aw1_mobile_less == 0 || $aw1_mobile_highest_reanalyze == 0)  ) {
				// popup 
				$aw1_status = "popup" ;
				$platform_less = "desktop" ;
				$popup_count++ ;
				// remove_temp_speed_data($conn,$additional1_id,$website_id) ;
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$aw1_status,$platform_less,$website_id) ;

				// if ( $aw1_mobile_highest_reanalyze > 0 ) {

				// 	// update only highest desktop record and blank to low record
				// 	$blank_record = "desktop" ;
				// 	$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$blank_record,$website_id) ;

				// 	$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				// 	$conn->query($sql);
				// }

			}
			elseif ( $aw1_mobile_highest_reanalyze == 0 && $aw1_desktop_highest_reanalyze == 0 ) {
				// nonew & no update
				$aw1_status = "nonew" ;
				$platform_less = "both" ;
				// remove_temp_speed_data($conn,$additional1_id,$website_id) ;
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$aw1_status,$platform_less,$website_id) ;
			}
			elseif ( $aw1_mobile_highest_reanalyze > 0 || $aw1_desktop_highest_reanalyze > 0 ) {
				// done & update both mobile , desktop record
				$aw1_status = "done" ;
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$aw1_status,NULL,$website_id) ;


				$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				$conn->query($sql);

				$aw1_mobile_speed_difference = $aw1_highest_mobile_speed - $additional1_ws_mobile ;
				$aw1_desktop_speed_difference = $aw1_highest_desktop_speed - $additional1_ws_desktop ;
				if ( 0 < $aw1_mobile_speed_difference && $aw1_mobile_speed_difference < 5  ) {
					$manual_audit_needed = 1 ;
					$manual_audit_sd = $aw1_mobile_speed_difference ;
				}
				elseif ( 0 < $aw1_desktop_speed_difference && $aw1_desktop_speed_difference < 5  ) {
					$manual_audit_needed = 1 ;
					$manual_audit_sd = $aw1_desktop_speed_difference ;
				}
			}
			else {
				$aw1_status = "popup" ;
				$platform_less = "both" ;
				$popup_count = $popup_count + 2 ;
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$aw1_status,$platform_less,$website_id) ;
			}


			/***elseif ( ($aw1_mobile_highest_reanalyze > 0 && ($aw1_desktop_highest_reanalyze == 0 && $aw1_desktop_less == 0)) || 
				(($aw1_mobile_highest_reanalyze == 0 && $aw1_mobile_less == 0) && $aw1_desktop_highest_reanalyze > 0) ) {
				// done & update only highest record and blank to low record
				$aw1_status = "done" ;

				$blank_record = ($aw1_desktop_highest_reanalyze == 0) ? "desktop" : "mobile" ;
				$blank_record = NULL ;
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$aw1_desktop_highest_reanalyze,$speedtype,$blank_record,$website_id) ;

				$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				$conn->query($sql);
			}
			elseif ( ($aw1_mobile_less == 1 && ($aw1_desktop_less == 0 && $aw1_desktop_highest_reanalyze == 0)) || 
				(($aw1_mobile_less == 0 && $aw1_mobile_highest_reanalyze == 0) && $aw1_desktop_less == 1) ) {

				// popup & no update
				$aw1_status = "popup" ;
				$platform_less = ($aw1_mobile_less == 1) ? "mobile" : "desktop" ;
			} ***/

			$return["url2"]["wi_status"] = $aw1_status ;
			$return["url2"]["mobile_highest_reanalyze"] = $aw1_mobile_highest_reanalyze ;
			$return["url2"]["desktop_highest_reanalyze"] = $aw1_desktop_highest_reanalyze ;
			$return["url2"]["stats"] = $stats ;
			$return["url2"]["mobile_less"] = $aw1_mobile_less ;
			$return["url2"]["desktop_less"] = $aw1_desktop_less ;
			$return["url2"]["platform_less"] = $platform_less ;

		}
		/*** END main additional1 website speed ***/

		/*** main additional2 website speed ***/
		if ( !empty($additional2_id) ) {

			$aw2_mobile_less = 0 ;
			$aw2_mobile_highest_reanalyze = 0 ;
			$aw2_desktop_less = 0 ;
			$aw2_desktop_highest_reanalyze = 0 ;

			$aw2_highest_mobile_speed = $aw2_highest_desktop_speed = 0 ;

			// mobile
			$query5 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$additional2_id' AND plateform LIKE 'mobile' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " );

			if ( $query5->num_rows > 0 ) {

				$temp_pagespeed_reports = $query5->fetch_all(MYSQLI_ASSOC) ;

				$additional2_ws_mobile = ((float)$additional2_ws_mobile < (float)$additional2_wos_mobile) ? (float)$additional2_wos_mobile : (float)$additional2_ws_mobile ;

				// check all new speeds are less than the old/initial mobile speed without script
				$aw2_mobile_less = 1 ;
				foreach ($temp_pagespeed_reports as $key => $tpr_data) {
					if ( (float)$tpr_data["speed_score"] > $additional2_wos_mobile ) {
						$aw2_mobile_less = 0 ; // speed it greater than mobile speed without script
					}
				}
				
				if ( $aw2_mobile_less == 0 ) {

					$highest_mobile_speed = 0 ;
					
					foreach ($temp_pagespeed_reports as $key => $tpr_data) {

						if ( (float)$tpr_data["speed_score"] > (float)$additional2_ws_mobile ) {
							// speed it greater than mobile speed with script

							if ( empty($aw2_mobile_highest_reanalyze) ) {
								$aw2_mobile_highest_reanalyze = $tpr_data["id"] ;
								$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
							}
							elseif ( (float)$tpr_data["speed_score"] > $highest_mobile_speed ) {
								$aw2_mobile_highest_reanalyze = $tpr_data["id"] ;
								$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
							}
						}
					}

					$aw2_highest_mobile_speed = $highest_mobile_speed ;

				}
			}

			// desktop
			$query6 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$additional2_id' AND plateform LIKE 'desktop' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " );

			if ( $query6->num_rows > 0 ) {

				$temp_pagespeed_reports = $query6->fetch_all(MYSQLI_ASSOC) ;

				$additional2_ws_desktop = ((float)$additional2_ws_desktop < (float)$additional2_wos_desktop) ? (float)$additional2_wos_desktop : (float)$additional2_ws_desktop ;

				// check all new speeds are less than the old/initial mobile speed without script
				$aw2_desktop_less = 1 ;
				foreach ($temp_pagespeed_reports as $key => $tpr_data) {
					if ( (float)$tpr_data["speed_score"] > (float)$additional2_wos_desktop ) {
						$aw2_desktop_less = 0 ; // speed it greater than mobile speed without script
					}
				}
				
				if ( $aw2_desktop_less == 0 ) {

					$highest_mobile_speed = 0 ;
					
					foreach ($temp_pagespeed_reports as $key => $tpr_data) {

						if ( (float)$tpr_data["speed_score"] > (float)$additional2_ws_desktop ) {
							
							if ( empty($aw2_desktop_highest_reanalyze) ) {
								$aw2_desktop_highest_reanalyze = $tpr_data["id"] ;
								$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
							}
							elseif ( (float)$tpr_data["speed_score"] > $highest_mobile_speed ) {
								$aw2_desktop_highest_reanalyze = $tpr_data["id"] ;
								$highest_mobile_speed = (float)$tpr_data["speed_score"] ;
							}
						}
					}

					$aw2_highest_desktop_speed = $highest_mobile_speed ;
				}
			}

			$stats = [] ;
			$platform_less = "" ;
			if ( $aw2_mobile_less == 1 && $aw2_desktop_less == 1 ) {
				// popup & no update
				$aw2_status = "popup" ;
				$platform_less = "both" ;
				$popup_count = $popup_count + 2 ;
				// remove_temp_speed_data($conn,$additional2_id,$website_id) ;
				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$aw2_status,$platform_less,$website_id) ;
			}
			elseif ( $aw2_mobile_less == 1 && ($aw2_desktop_less == 0 || $aw2_desktop_highest_reanalyze == 0)  ) {
				// popup 
				$aw2_status = "popup" ;
				$platform_less = "mobile" ;
				$popup_count++ ;
				// remove_temp_speed_data($conn,$additional2_id,$website_id) ;
				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$aw2_status,$platform_less,$website_id) ;

				// if ( $aw2_desktop_highest_reanalyze > 0 ) {

				// 	// update only highest mobile record and blank to low record
				// 	$blank_record = "mobile" ;

				// 	$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$blank_record,$website_id) ;


				// 	$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				// 	$conn->query($sql);
				// }

			}
			elseif ( $aw2_desktop_less == 1 && ($aw2_mobile_less == 0 || $aw2_mobile_highest_reanalyze == 0)  ) {
				// popup 
				$aw2_status = "popup" ;
				$platform_less = "desktop" ;
				$popup_count++ ;
				// remove_temp_speed_data($conn,$additional2_id,$website_id) ;
				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$aw2_status,$platform_less,$website_id) ;

				// if ( $aw2_mobile_highest_reanalyze > 0 ) {

				// 	// update only highest desktop record and blank to low record
				// 	$blank_record = "desktop" ;
				// 	$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$blank_record,$website_id) ;

				// 	$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				// 	$conn->query($sql);
				// }

			}
			elseif ( $aw2_mobile_highest_reanalyze == 0 && $aw2_desktop_highest_reanalyze == 0 ) {
				// nonew & no update
				$aw2_status = "nonew" ;
				$platform_less = "both" ;
				// remove_temp_speed_data($conn,$additional2_id,$website_id) ;
				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$aw2_status,$platform_less,$website_id) ;
			}
			elseif ( $aw2_mobile_highest_reanalyze > 0 || $aw2_desktop_highest_reanalyze > 0 ) {
				// done & update both mobile , desktop record
				$aw2_status = "done" ;
				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$aw2_status,NULL,$website_id) ;
				
				$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				$conn->query($sql);

				$aw2_mobile_speed_difference = $aw2_highest_mobile_speed - $additional2_ws_mobile ;
				$aw2_desktop_speed_difference = $aw2_highest_desktop_speed - $additional2_ws_desktop ;

				if ( 0 < $aw2_mobile_speed_difference && $aw2_mobile_speed_difference < 5  ) {
					$manual_audit_needed = 1 ;
					$manual_audit_sd = $aw2_mobile_speed_difference ;
				}
				elseif ( 0 < $aw2_desktop_speed_difference && $aw2_desktop_speed_difference < 5  ) {
					$manual_audit_needed = 1 ;
					$manual_audit_sd = $aw2_desktop_speed_difference ;
				}
			}
			else {
				$aw2_status = "popup" ;
				$popup_count = $popup_count + 2 ;
				$platform_less = "both" ;
				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$aw2_status,$platform_less,$website_id) ;
			}

			/*** elseif ( ($aw2_mobile_highest_reanalyze > 0 && ($aw2_desktop_highest_reanalyze == 0 && $aw2_desktop_less == 0)) || 
				(($aw2_mobile_highest_reanalyze == 0 && $aw2_mobile_less == 0) && $aw2_desktop_highest_reanalyze > 0) ) {
				// done & update only highest record and blank to low record
				$aw2_status = "done" ;

				$blank_record = ($aw2_desktop_highest_reanalyze == 0) ? "desktop" : "mobile" ;
				$blank_record = NULL ;

				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$aw2_desktop_highest_reanalyze,$speedtype,$blank_record,$website_id) ;

				$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				$conn->query($sql);
			}
			elseif ( ($aw2_mobile_less == 1 && ($aw2_desktop_less == 0 && $aw2_desktop_highest_reanalyze == 0)) || 
				(($aw2_mobile_less == 0 && $aw2_mobile_highest_reanalyze == 0) && $aw2_desktop_less == 1) ) {

				// popup & no update
				$aw2_status = "popup" ;
				$platform_less = ($aw2_mobile_less == 1) ? "mobile" : "desktop" ;
			} ***/

			$return["url3"]["wi_status"] = $aw2_status ;
			$return["url3"]["mobile_highest_reanalyze"] = $aw2_mobile_highest_reanalyze ;
			$return["url3"]["desktop_highest_reanalyze"] = $aw2_desktop_highest_reanalyze ;
			$return["url3"]["stats"] = $stats ;
			$return["url3"]["mobile_less"] = $aw2_mobile_less ;
			$return["url3"]["desktop_less"] = $aw2_desktop_less ;
			$return["url3"]["platform_less"] = $platform_less ;

		}
		/*** END main additional2 website speed ***/

		// 8. ager 3 url ki speed badi (5+) h toh table show hogi. nhi toh popup ka content show hoga.
		if ( $popup_count >= 3 ) {
			$manual_audit_needed = 1 ;
			$manual_audit_sd = 'All' ;
		}
		elseif ( $popup_count > 0 && $popup_count <= 3 ) {
			$manual_audit_needed = 1 ;
			$manual_audit_sd = 2.5 ;
		}

		// echo "popup_count : ".$popup_count."<br>" ;
		// echo "manual_audit_needed : ".$manual_audit_needed."<br>" ;
		// echo "manual_audit_sd : ".$manual_audit_sd."<br>" ;
		// echo "<hr>" ;

		$return["popup_count"] = $popup_count ;



		/*** FOR Request manual audit for pages ***/ 
		$query7 = $conn->query(" SELECT id , website_id  FROM `website_improve_needed` WHERE website_id = '$website_id' ; ") ;

		if ( $query7->num_rows > 0 ) {

			$win_data = $query7->fetch_assoc() ;

			$sql = " UPDATE `website_improve_needed` SET `score_difference`='$manual_audit_sd',`request_manual_audit`='$manual_audit_needed' WHERE `id`='".$win_data["id"]."' " ;
		}
		else {
			$sql = " INSERT INTO `website_improve_needed`( `website_id`, `score_difference`, `request_manual_audit` ) VALUES ( '$website_id' , '$manual_audit_sd' , '$manual_audit_needed' ) " ;
		}

		$return["request_manual_audit"] = $manual_audit_needed ;
		$return["manual_audit_score"] = $manual_audit_sd ;


		$conn->query($sql) ;

		/*** END Request manual audit for pages ***/ 
		
	}
	else {

		remove_temp_speed_data($conn,$website_id) ;
		remove_temp_speed_data($conn,$additional1_id,$website_id) ;
		remove_temp_speed_data($conn,$additional1_id,$website_id) ;

		$return["czs_flag"] = $czs_flag ;
		$return["message"] = "Invalid URL, can't get the speedscore. Please check entered URL & try again.";

	}

	echo json_encode($return);
}