<pre>
<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

ini_set('max_execution_time', '0');

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

$_POST = array ( "website_url" => "https://www.w3schools.com", "website_id" => 3338, "website_ws_mobile" => 0, "website_ws_desktop" => 0, "website_wos_mobile" => 70, "website_wos_desktop" => 87, "additional1_url" => "https://www.w3schools.com/howto/default.asp", "additional1_id" => 2797, "additional1_ws_mobile" => 0, "additional1_ws_desktop" => 0, "additional1_wos_mobile" => 66, "additional1_wos_desktop" => 73, "additional2_url" => "https://www.w3schools.com/c/index.php", "additional2_id" => 2796, "additional2_ws_mobile" => 0, "additional2_ws_desktop" => 0, "additional2_wos_mobile" => 77, "additional2_wos_desktop" => 98, "speedtype" => "new", "action" => "reanalyze-speed-compare-update" ) ;

// die() ;

function remove_temp_speed_data($conn,$website_id,$parent_website=0,$plateform="both") {

	// return false ;

	if ( $plateform == "both" ) {
		// now delete the temp_pagespeed_report data 
		if ( empty($parent_website) ) {

			$sql = "DELETE FROM temp_pagespeed_report WHERE website_id = '$website_id'; " ;
		}
		else {

			$sql = "DELETE FROM temp_pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website'; " ;
		}
	}
	else {
		// now delete the temp_pagespeed_report data 
		if ( empty($parent_website) ) {

			$sql = "DELETE FROM temp_pagespeed_report WHERE website_id = '$website_id' AND plateform LIKE '$plateform'; " ;
		}
		else {

			$sql = "DELETE FROM temp_pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website' AND plateform LIKE '$plateform'; " ;
		}
	}


	// echo "sql : ".$sql ;

	// $conn->query($sql) ;
}



// function update_pagespeed_record($conn,$website_id,$highest_mobile_reanalyze,$speedtype,$blank_record=NULL,$parent_website=0) 

function update_pagespeed_record($conn,$website_id,$highest_reanalyze,$speedtype,$parent_website=0,$blank_record=NULL,$status="nonew") {

	$stats = [] ;

	// echo "update_pagespeed_record : <br>" ;
	// echo "highest_reanalyze :".$highest_reanalyze." <br>" ;

	// desktop ======
	$requestedUrl = $finalUrl = $userAgent = $fetchTime = $environment = $runWarnings = $configSettings = $audits = $categories = $categoryGroups = $i18n = NULL ;

	// mobile ======
	$mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n  = NULL ;

	if ( $status == "nonew" ) {

		if ( empty($parent_website) ) {
			$query2 = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '$website_id' AND parent_website = 0 AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ; ");
		}
		else {
			$query2 = $conn->query(" SELECT * FROM pagespeed_report WHERE website_id = '$website_id' AND parent_website = '$parent_website' AND (fetchTime <> '' AND fetchTime IS NOT NULL) ORDER BY id DESC LIMIT 1 ; ");
		}

	}
	else {

		if ( empty($parent_website) ) {
			$query2 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND id = '$highest_reanalyze' ; ");
		}
		else {
			$query2 = $conn->query(" SELECT * FROM temp_pagespeed_report WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND id = '$highest_reanalyze' AND parent_website = '$parent_website' ; ");
		}

	}


	if ($query2->num_rows > 0) {

		$pagespeed_datadesk = $query2->fetch_assoc();

		$requestedUrl = $pagespeed_datadesk['requestedUrl'];
		$finalUrl = $pagespeed_datadesk['finalUrl'];
		$userAgent = $conn->real_escape_string(serialize(unserialize($pagespeed_data['userAgent'])));
		$fetchTime = $pagespeed_data['fetchTime'];

		$mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['environment'])));
		$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['runWarnings'])));
		$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['configSettings'])));
		$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['audits'])));
		$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categories'])));
		$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categoryGroups'])));
		$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['i18n'])));


		// insert in pagespeed_report table 
		if ( empty($parent_website) ) {
			$sql = " INSERT INTO pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy , blank_record , speed_data ) VALUES ( '$website_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 0 , '$blank_record' , 1 ) ; " ;
		}
		else {
			$sql = " INSERT INTO pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy , blank_record , speed_data ) VALUES ( '$website_id' , '$parent_website' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 0 , '$blank_record' , 1 ) ; " ;
		}


		if ( $conn->query($sql) === TRUE ) {

			$pr_id = $conn->insert_id;

			// echo $sql ; echo "pr_id :".$pr_id ;

			remove_temp_speed_data($conn,$website_id,$parent_website,"mobile") ;

			if( $speedtype == 'new' ) {
				$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				$conn->query($sql);
			}


			// now get updated speed and process on frontend from pagespeed_report table.

			$query4 = $conn->query(" SELECT * FROM pagespeed_report WHERE id = '$pr_id' ; ");								
			$pagespeed_data = $query4->fetch_assoc();
			
			$ps_categories_m = unserialize($pagespeed_data["mobile_categories"]);
			$audits_m = unserialize($pagespeed_data["mobile_audits"]);
			
			$ps_performance_m = round($ps_categories_m["performance"]["score"] * 100, 2);
			$ps_mobile = $ps_performance_m . "/100";
			$ps_accessibility_m = round($ps_categories_m["accessibility"]["score"] * 100, 2);
			$ps_best_practices_m = round($ps_categories_m["best-practices"]["score"] * 100, 2);
			$ps_seo_m = round($ps_categories_m["seo"]["score"] * 100, 2);
			$ps_pwa_m = round($ps_categories_m["pwa"]["score"] * 100, 2);


			$ps_fcp_m = $audits_m["first-contentful-paint"]["numericValue"] ;
			$ps_lcp_m = $audits_m["largest-contentful-paint"]["numericValue"] ;
			$ps_cls_m = $audits_m["cumulative-layout-shift"]["numericValue"] ;
			$ps_tbt_m = $audits_m["total-blocking-time"]["numericValue"] ;
			$ps_si_m = $audits_m["speed-index"]["displayValue"] ;


			$stats = array( 'ps_mobile'=>$ps_mobile , 'ps_accessibility_m'=>$ps_accessibility_m , 'ps_best_practices_m'=>$ps_best_practices_m , 'ps_seo_m'=>$ps_seo_m , 'ps_fcp_m'=>$ps_fcp_m , 'ps_lcp_m'=>$ps_lcp_m , 'ps_cls_m'=>round($ps_cls_m, 3) , 'ps_tbt_m'=>$ps_tbt_m , 'ps_performance_m'=>$ps_performance_m , 'pr_id' => $pr_id , 'blank_record' => $blank_record , 'ps_si_m'=>$ps_si_m );

		}


	}

	return $stats ;
}



if ( isset($_POST["action"]) && ($_POST["action"] == "reanalyze-speed-compare-update") ) {

	$response = array('status' => '' , 'message'=> '' );

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	print_r($_POST); echo"<hr>" ; 

	$manual_audit_needed = 0 ;
	$manual_audit_sd = 0 ;
	$popup_count = 0 ;

	$return = array(
		'url1' => array( 'id' => $website_id , 'url' => $website_url , 'parent' => 0 , 'wi_status' => '' , 'mobile_highest_reanalyze' => '' , 'stats' => '' , 'mobile_less' => '' , 'platform_less' => '' ) ,
		'url2' => array( 'id' => $additional1_id , 'url' => $additional1_url , 'parent' => $website_id , 'wi_status' => '' , 'mobile_highest_reanalyze' => '' , 'stats' => '', 'mobile_less' => '' , 'platform_less' => '' ) ,
		'url3' => array( 'id' => $additional2_id , 'url' => $additional2_url , 'parent' => $website_id , 'wi_status' => '' , 'mobile_highest_reanalyze' => '' , 'stats' => '', 'mobile_less' => '' , 'platform_less' => '' ) ,
		'request_manual_audit' => 0 ,
		'czs_flag' => 1 ,
		'message' => '' ,
		'popup_count' => 0 ,
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

	echo "czs_flag : ".$czs_flag."<br>" ;
	echo "<hr>" ;

	if ( $czs_flag == 1 ) {

		$return["czs_flag"] = $czs_flag ;

		/*** main website speed ***/

		$wi_mobile_less = 0 ;
		$wi_mobile_highest_reanalyze = 0 ;

		$wi_highest_mobile_speed = 0 ;

		// mobile
		$query1 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count , parent_website FROM `temp_pagespeed_report` WHERE `website_id` = '$website_id' AND plateform LIKE 'mobile' AND parent_website = 0 ORDER BY reanalyze_count ASC; " );

		if ( $query1->num_rows > 0 ) {

			$temp_pagespeed_reports = $query1->fetch_all(MYSQLI_ASSOC) ;

			// print_r($temp_pagespeed_reports) ; echo "<hr>";

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

		// echo "wi_mobile_less : ".$wi_mobile_less."<br>" ;
		// echo "wi_mobile_highest_reanalyze : ".$wi_mobile_highest_reanalyze."<br>" ;
		// echo "<hr>" ;

		$stats = [] ;
		$platform_less = "" ;
		if ( $wi_mobile_less == 1 ) {

			// popup 
			$wi_status = "popup" ;
			$platform_less = "mobile" ;
			$popup_count++ ;

			remove_temp_speed_data($conn,$website_id,0,'mobile') ;
		}
		elseif ( $wi_mobile_highest_reanalyze == 0 ) {
			// nonew & no update
			$wi_status = "nonew" ;

			// create a new row with same score
			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$speedtype,0,NULL,$wi_status) ;
		}
		elseif ( $wi_mobile_highest_reanalyze > 0 ) {
			// done & update both mobile , desktop record
			$wi_status = "done" ;
			
			$stats = update_pagespeed_record($conn,$website_id,$wi_mobile_highest_reanalyze,$speedtype,0,NULL,$wi_status); 

			$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
			$conn->query($sql);

			$wi_mobile_speed_difference = $wi_highest_mobile_speed - $website_ws_mobile ;
			if ( 0 <= $wi_mobile_speed_difference && $wi_mobile_speed_difference <= 5  ) {
				$manual_audit_needed = 1 ;
				$manual_audit_sd = $wi_mobile_speed_difference ;
			}
		}

		$return["url1"]["wi_status"] = $wi_status ;
		$return["url1"]["mobile_highest_reanalyze"] = $wi_mobile_highest_reanalyze ;
		$return["url1"]["stats"] = $stats ;
		$return["url1"]["mobile_less"] = $wi_mobile_less ;
		$return["url1"]["platform_less"] = $platform_less ;

		/*** END main website speed ***/


		/*** main additional1 website speed ***/
		if ( !empty($additional1_id) ) {

			$aw1_mobile_less = 0 ;
			$aw1_mobile_highest_reanalyze = 0 ;

			$aw1_highest_mobile_speed = 0 ;

			// mobile
			$query3 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count , parent_website FROM `temp_pagespeed_report` WHERE `website_id` = '$additional1_id' AND plateform LIKE 'mobile' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " );

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

			// echo "aw1_mobile_less : ".$aw1_mobile_less."<br>" ;
			// echo "aw1_mobile_highest_reanalyze : ".$aw1_mobile_highest_reanalyze."<br>" ;
			// echo "<hr>" ;

			$stats = [] ;
			$platform_less = "" ;
			if ( $aw1_mobile_less == 1 ) {
				// popup 
				$aw1_status = "popup" ;
				$platform_less = "mobile" ;
				$popup_count++ ;
				remove_temp_speed_data($conn,$additional1_id,$website_id,"mobile") ;
			}
			elseif ( $aw1_mobile_highest_reanalyze == 0 ) {
				// nonew & no update
				$aw1_status = "nonew" ;

				// create a new row with same score
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$speedtype,$website_id,NULL,$aw1_status) ;
			}
			elseif ( $aw1_mobile_highest_reanalyze > 0 ) {
				// done & update both mobile , desktop record
				$aw1_status = "done" ;
				$stats = update_pagespeed_record($conn,$additional1_id,$aw1_mobile_highest_reanalyze,$speedtype,$website_id,NULL,$aw1_status) ;

				$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				$conn->query($sql);

				$aw1_mobile_speed_difference = $aw1_highest_mobile_speed - $additional1_ws_mobile ;
				if ( 0 <= $aw1_mobile_speed_difference && $aw1_mobile_speed_difference <= 5  ) {
					$manual_audit_needed = 1 ;
					$manual_audit_sd = $aw1_mobile_speed_difference ;
				}
			}


			$return["url2"]["wi_status"] = $aw1_status ;
			$return["url2"]["mobile_highest_reanalyze"] = $aw1_mobile_highest_reanalyze ;
			$return["url2"]["stats"] = $stats ;
			$return["url2"]["mobile_less"] = $aw1_mobile_less ;
			$return["url2"]["platform_less"] = $platform_less ;

		}
		/*** END main additional1 website speed ***/

		/*** main additional2 website speed ***/
		if ( !empty($additional2_id) ) {

			$aw2_mobile_less = 0 ;
			$aw2_mobile_highest_reanalyze = 0 ;

			$aw2_highest_mobile_speed = 0 ;

			// mobile
			$query5 = $conn->query( " SELECT id , website_id , plateform , speed_score , reanalyze_count , parent_website FROM `temp_pagespeed_report` WHERE `website_id` = '$additional2_id' AND plateform LIKE 'mobile' AND parent_website = '$website_id' ORDER BY reanalyze_count ASC; " );

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

			$stats = [] ;
			$platform_less = "" ;
			if ( $aw2_mobile_less == 1 ) {
				// popup 
				$aw2_status = "popup" ;
				$platform_less = "mobile" ;
				$popup_count++ ;
				remove_temp_speed_data($conn,$additional2_id,$website_id,'mobile') ;
			}
			elseif ( $aw2_mobile_highest_reanalyze == 0 ) {
				// nonew & no update
				$aw2_status = "nonew" ;

				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$speedtype,$website_id,NULL,$aw2_status) ;
			}
			elseif ( $aw2_mobile_highest_reanalyze > 0 || $aw2_desktop_highest_reanalyze > 0 ) {
				// done & update both mobile , desktop record
				$aw2_status = "done" ;

				$stats = update_pagespeed_record($conn,$additional2_id,$aw2_mobile_highest_reanalyze,$speedtype,$website_id,NULL,$aw2_status) ;

				$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$website_id' ";
				$conn->query($sql);

				$aw2_mobile_speed_difference = $aw2_highest_mobile_speed - $additional2_ws_mobile ;
				if ( 0 < $aw2_mobile_speed_difference && $aw2_mobile_speed_difference < 5  ) {
					$manual_audit_needed = 1 ;
					$manual_audit_sd = $aw2_mobile_speed_difference ;
				}

			}

			$return["url3"]["wi_status"] = $aw2_status ;
			$return["url3"]["mobile_highest_reanalyze"] = $aw2_mobile_highest_reanalyze ;
			$return["url3"]["stats"] = $stats ;
			$return["url3"]["mobile_less"] = $aw2_mobile_less ;
			$return["url3"]["platform_less"] = $platform_less ;

		}
		/*** END main additional2 website speed ***/

		if ( $popup_count >= 3 ) {
			$manual_audit_needed = 1 ;
			$manual_audit_sd = 'All' ;
		}

		echo "popup_count : ".$popup_count."<br>" ;
		echo "manual_audit_needed : ".$manual_audit_needed."<br>" ;
		echo "manual_audit_sd : ".$manual_audit_sd."<br>" ;
		echo "<hr>" ;

		// save popup count
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

		remove_temp_speed_data($conn,$website_id,0,"both") ;
		remove_temp_speed_data($conn,$additional1_id,$website_id,"both") ;
		remove_temp_speed_data($conn,$additional1_id,$website_id,"both") ;

		$return["czs_flag"] = $czs_flag ;
		$return["message"] = "All URLs get Zero speedscore. Please check entered URL & try again.";

	}

	print_r($return); echo "<hr>" ;
	echo json_encode($return);
}