<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

ini_set('max_execution_time', '0');

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

// $_POST = array ( "website_id" => 3252, "additional2_id" => 2701, "additional3_id" => 2702, "url" => 1 ,  "action" => "compare-url-speed" , "website_ws_mobile" => 88 , "website_ws_desktop" => 100 , "website_wos_mobile" => 70 , "website_wos_desktop" => 98 , "website_url" => "https://www.w3schools.com" ) ;

function update_record($conn,$website_id,$highest_mobile_reanalyze,$highest_desktop_reanalyze,$parent_website=0) {

	$stats = [] ;

	// desktop ======
	$ps_desktop = $ps_accessibility = $ps_best_practices = $ps_seo = $ps_pwa = $psa_fcp_d = $psa_lcp_d = $psa_cls_d = $psa_tbt_d = $psa_si_d = "" ;
	$ps_performance_m = $ps_performance = 0 ;

	if ( empty($highest_desktop_reanalyze) ) {

		// to set old updated data 
		if ( empty($parent_website) ) {
			$query1 = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '$website_id' AND parent_website = 0 ORDER BY id DESC LIMIT 1 ; ");
		}
		else {
			$query1 = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website' ORDER BY id DESC LIMIT 1 ; ");
		}
	}
	else {

		if ( empty($parent_website) ) {
			$query1 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'desktop' AND id = '$highest_desktop_reanalyze' ; ");
		}
		else {
			$query1 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'desktop' AND id = '$highest_desktop_reanalyze' AND parent_website = '$parent_website' ; ");
		}
	}

	if ($query1->num_rows > 0) {

		$pagespeed_data = $query1->fetch_assoc();

		$ps_categories_d = unserialize($pagespeed_data["categories"]);
		$audits_d = unserialize($pagespeed_data["audits"]);

		$ps_performance = round($ps_categories_d["performance"]["score"] * 100, 2);
		$ps_desktop = $ps_performance . "/100";
		$ps_accessibility = round($ps_categories_d["accessibility"]["score"] * 100, 2);
		$ps_best_practices = round($ps_categories_d["best-practices"]["score"] * 100, 2);
		$ps_seo = round($ps_categories_d["seo"]["score"] * 100, 2);
		$ps_pwa = round($ps_categories_d["pwa"]["score"] * 100, 2);

		$psa_fcp_d = round($audits_d["first-contentful-paint"]["numericValue"],2) ;
		$psa_lcp_d = round($audits_d["largest-contentful-paint"]["numericValue"],2) ;
		$psa_cls_d = round($audits_d["cumulative-layout-shift"]["numericValue"], 3) ;
		$psa_tbt_d = round($audits_d["total-blocking-time"]["numericValue"],2) ;
		$psa_si_d = $audits_d["speed-index"]["displayValue"] ;

	}



	// mobile ======

	if ( empty($highest_mobile_reanalyze) ) {

		if ( empty($parent_website) ) {
			$query2 = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '$website_id' AND parent_website = 0 ORDER BY id DESC LIMIT 1 ; ");
		}
		else {
			$query2 = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website' ORDER BY id DESC LIMIT 1 ; ");
		}
	}
	else {

		if ( empty($parent_website) ) {
			$query2 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND id = '$highest_mobile_reanalyze' ; ");
		}
		else {
			$query2 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND id = '$highest_mobile_reanalyze' AND parent_website = '$parent_website' ; ");
		}
	}

	if ($query2->num_rows > 0) {

		$pagespeed_datadesk = $query2->fetch_assoc();

		$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
		$audits_m = unserialize($pagespeed_data["mobile_audits"]);


		$ps_performance_m = round($ps_categories_m["performance"]["score"] * 100, 2);
		$ps_mobile = $ps_performance_m . "/100";
		$ps_accessibility_m = round($ps_categories_m["accessibility"]["score"] * 100, 2);
		$ps_best_practices_m = round($ps_categories_m["best-practices"]["score"] * 100, 2);
		$ps_seo_m = round($ps_categories_m["seo"]["score"] * 100, 2);
		$ps_pwa_m = round($ps_categories_m["pwa"]["score"] * 100, 2);

		$psa_fcp_m = round($audits_m["first-contentful-paint"]["numericValue"],2) ;
		$psa_lcp_m = round($audits_m["largest-contentful-paint"]["numericValue"],2) ;
		$psa_cls_m = round($audits_m["cumulative-layout-shift"]["numericValue"], 3) ;
		$psa_tbt_m = round($audits_m["total-blocking-time"]["numericValue"],2) ;
		$psa_si_m = $audits_m["speed-index"]["displayValue"] ;

	}

	$stats = array('ps_desktop' => $ps_desktop , 'ps_accessibility'=>$ps_accessibility , 'ps_best_practices'=>$ps_best_practices , 'ps_seo'=>$ps_seo , 'ps_pwa'=>$ps_pwa ,  'ps_fcp'=>$psa_fcp_d , 'ps_lcp'=>$psa_lcp_d , 'ps_cls'=>$psa_cls_d , 'ps_tbt'=>$psa_tbt_d , 'ps_si'=>$psa_si_d , 'ps_mobile'=>$ps_mobile , 'ps_accessibility_m'=>$ps_accessibility_m , 'ps_best_practices_m'=>$ps_best_practices_m , 'ps_seo_m'=>$ps_seo_m , 'ps_pwa_m'=>$ps_pwa_m , 'ps_fcp_m'=>$psa_fcp_m , 'ps_lcp_m'=>$psa_lcp_m , 'ps_cls_m'=>$psa_cls_m , 'ps_tbt_m'=>$psa_tbt_m , 'ps_si_m'=>$psa_si_m , 'ps_performance_m'=>$ps_performance_m , 'ps_performance'=>$ps_performance );

	return $stats ;
}

if ( isset($_POST["action"]) && ($_POST["action"] == "compare-url-speed") ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	// print_r($_POST); echo"<hr>" ;
	// die() ;

	$manual_audit_needed = 0 ;
	$manual_audit_sd = 0 ;
	$popup_count = 0 ;

	$return = array( 'id' => $website_id , 'url' => $website_url , 'parent' => 0 , 'wi_status' => '' , 'mobile_highest_reanalyze' => '' , 'desktop_highest_reanalyze' => '' , 'stats' => '' , 'mobile_less' => '' , 'desktop_less' => '' ) ;

	/************************************************************************/

	// get mobile speeds
	switch ($url) {
		case 1:
		case '1':
			$sql1 = " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND parent_website = 0 ORDER BY reanalyze_count ASC; " ;

			$sql2 = " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$website_id' AND plateform LIKE 'desktop' AND parent_website = 0 ORDER BY reanalyze_count ASC; " ;


			break;
		
		case 2:
		case '2':
			$sql1 = " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$additional2_id' AND plateform LIKE 'mobile' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " ;

			$sql2 = " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$additional2_id' AND plateform LIKE 'desktop' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " ;

		case 3:
		case '3':
			$sql1 = " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$additional3_id' AND plateform LIKE 'mobile' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " ;

			$sql2 = " SELECT id , website_id , plateform , speed_score , reanalyze_count FROM `temp_pagespeed_report` WHERE `website_id` = '$additional3_id' AND plateform LIKE 'desktop' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " ;

			break;
	}
	
	// echo "sql1 : ".$sql1."<br>" ;
	// echo "sql2 : ".$sql2."<br>" ;
	// echo "<hr>" ;

	// process website speed data
	$wi_mobile_less = 0 ;
	$wi_mobile_highest_reanalyze = 0 ;
	$wi_desktop_less = 0 ;
	$wi_desktop_highest_reanalyze = 0 ;

	$wi_highest_mobile_speed = $wi_highest_desktop_speed = 0 ;

	/** mobile data **/
	$query1 = $conn->query($sql1);

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

	/** desktop data **/
	$query2 = $conn->query($sql2);

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
	if ( $wi_mobile_less == 1 && $wi_desktop_less == 1 ) {
		// popup & no update
		$wi_status = "popup" ;
	}
	elseif ( $wi_mobile_less == 1 && ($wi_desktop_less == 0 || $wi_desktop_highest_reanalyze == 0) ) {
		// popup 
		$wi_status = "popup" ;
	}
	elseif ( $wi_desktop_less == 1 && ($wi_mobile_less == 0 || $wi_mobile_highest_reanalyze == 0) ) {
		// popup 
		$wi_status = "popup" ;
	}
	elseif ( $wi_mobile_highest_reanalyze == 0 && $wi_desktop_highest_reanalyze == 0 ) {
		// nonew & no update
		$wi_status = "nonew" ;
	}
	elseif ( $wi_mobile_highest_reanalyze > 0 || $wi_desktop_highest_reanalyze > 0 ) {
		// done & update both mobile , desktop record
		$wi_status = "done" ;

		switch ($url) {
			case 1:
			case '1':
				$stats = update_record($conn,$website_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze) ;
				break;
			
			case 2:
			case '2':
				$stats = update_record($conn,$additional2_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$website_id) ;

			case 3:
			case '3':
				$stats = update_record($conn,$additional3_id,$wi_mobile_highest_reanalyze,$wi_desktop_highest_reanalyze,$website_id) ;
				break;

		}

	}
	else {
		// error
		$wi_status = "error" ;
	}

	$return["wi_status"] = $wi_status ;
	$return["mobile_highest_reanalyze"] = $wi_mobile_highest_reanalyze ;
	$return["desktop_highest_reanalyze"] = $wi_desktop_highest_reanalyze ;
	$return["stats"] = $stats ;
	$return["mobile_less"] = $wi_mobile_less ;
	$return["desktop_less"] = $wi_desktop_less ;

	/************************************************************************/ 


	echo json_encode($return);
}