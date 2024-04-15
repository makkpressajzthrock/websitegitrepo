<?php

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

if ( isset($_POST["website"]) && !empty($_POST["website"]) ) {

	$website = $_POST["website"] ;
	$website = intval($website) ;
	if ( $website > 0 ) {
		
		// get manager website data
		$data = getTableData( $conn , " boost_website " , " id = '".$website."' " ) ;

		if ( count($data) > 0 ) {
			
			$url = $data["website_url"] ;

			// get previous speed
			$desktop_page_insight = google_page_speed_insight($url,"desktop") ;
			$desktop_score = 0 ;
			if ( is_array($desktop_page_insight["lighthouseResult"]["categories"]) ) {
				// code...
				$categories = $desktop_page_insight["lighthouseResult"]["categories"] ;

				$desktop_score = $categories["performance"]["score"] ;
				$desktop_score = round( $desktop_score*100 , 2 ) ;
			}

			$mobile_page_insight = google_page_speed_insight($url,"mobile") ;
			$mobile_score = 0 ;
			if ( is_array($mobile_page_insight["lighthouseResult"]["categories"]) ) {
				// code...
				$categories = $mobile_page_insight["lighthouseResult"]["categories"] ;

				$mobile_score = $categories["performance"]["score"] ;
				$mobile_score = round( $mobile_score*100 , 2 ) ;
			}

			// update speed
			$columns = " desktop_speed_new = '$desktop_score' , mobile_speed_new = '$mobile_score' " ;

			updateTableData( $conn , " boost_website " , $columns , " id = '$website' " ) ;

			// insert new record
			$columns = " website_id , desktop_speed , mobile_speed " ;
			$values = " '$website' , '$desktop_score' , '$mobile_score' " ;
			insertTableData( $conn , " speed_record " , $columns , $values ) ;

			echo json_encode( array('desktop' => $desktop_score , 'mobile' => $mobile_score ) ) ;
		}
		else {
			echo json_encode(0) ;
		}
	}
	else {
		echo json_encode(0) ;
	}

}

