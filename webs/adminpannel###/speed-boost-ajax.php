<?php
include('config.php');
include('session.php');
require_once('inc/functions.php') ;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



	extract($_POST);


	if ( empty($platform) || empty($websiteurl) || empty($shopifyurl) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	elseif ( !filter_var($websiteurl, FILTER_VALIDATE_URL) || !filter_var($shopifyurl, FILTER_VALIDATE_URL) ) {
		$_SESSION['error'] = "Invalid urls!" ;
	}
	else {

		

		// check already saved additional url data

		
			

		// check already saved data
		$check = getTableData( $conn , " boost_website " , " manager_id = '$manager_id' " ) ;

		//  print_r($check);

		// die();

		if ( count($check) > 0 ) {
			// update
			$id = $check["id"] ;
				// print_r($check );


			

			$columns = [];

					if(!empty($deleted_urls)){
			
		$deleted_urls = explode(',',$deleted_urls);

		foreach($deleted_urls as $deletedid){

			$conn->query("DELETE FROM manager_sites WHERE id = '$deletedid'");

		}

		}


			foreach ($additional_url as $key_1 => $value_url) {

// 								

$columns[] = " additional_url = '$value_url'" ;


}


		if ( count($check_additional_url) > 0 ) {

			foreach ($check_additional_url as $key => $value) {


	// echo count($check_additional_url);	

	// die;




		// update
		$id__url = $value["id"] ;


	if($key+1 > count($additional_url))
{	
			break;

}	


				if ( updateTableData( $conn , " manager_sites " , $columns[$key] , " id = '$id__url' " ) ) {

					$_SESSION['success'] = "Speed Boost site aedditional url updated successfully!" ;
				}

				else {
					$_SESSION['error'] = "Operation failed!" ;
					$_SESSION['error'] = "Error: " . $conn->error;
				}
			}
				
				
			}
			else{
					// get previous speed
				$desktop_page_insight = google_page_speed_insight($websiteurl,"desktop") ;
				$desktop_score = 0 ;
				if ( is_array($desktop_page_insight["lighthouseResult"]["categories"]) ) {
					// code...
					$categories = $desktop_page_insight["lighthouseResult"]["categories"] ;

					$desktop_score = $categories["performance"]["score"] ;
					$desktop_score = round( $desktop_score*100 , 2 ) ;
				}

				$mobile_page_insight = google_page_speed_insight($websiteurl,"mobile") ;
				$mobile_score = 0 ;
				if ( is_array($mobile_page_insight["lighthouseResult"]["categories"]) ) {
					// code...
					$categories = $mobile_page_insight["lighthouseResult"]["categories"] ;

					$mobile_score = $categories["performance"]["score"] ;
					$mobile_score = round( $mobile_score*100 , 2 ) ;
				}

				// insert additional
				$columns = " manager_id  , website_id , additional_url , desktop_speed_old , mobile_speed_old , desktop_speed_new , mobile_speed_new " ;
				$values = " '$manager_id'  , '$id'  , '$additional_url', '$desktop_score' , '$mobile_score' , '$desktop_score' , '$mobile_score' " ;

				if ( insertTableData( $conn , " manager_sites " , $columns , $values ) ) {
					$_SESSION['success'] = "Speed Boost site updated successfully!" ;
				}
				else {
					$_SESSION['error'] = "Operation failed!" ;
					$_SESSION['error'] = "Error: " . $conn->error;
				}



			// }
			
				
			// $columns = " platform = '$platform' , website_url = '$websiteurl' , shopify_url = '$shopifyurl' " ;

			// if ( updateTableData( $conn , " boost_website " , $columns , " id = '$id' " ) ) {

			// 	$_SESSION['success'] = "Speed Boost site updated successfully!" ;
			// }
			// else {
			// 	$_SESSION['error'] = "Operation failed!" ;
			// 	$_SESSION['error'] = "Error: " . $conn->error;
			// }



// 		}
// 		else {

// 			// get previous speed
// 			$desktop_page_insight = google_page_speed_insight($websiteurl,"desktop") ;
// 			$desktop_score = 0 ;
// 			if ( is_array($desktop_page_insight["lighthouseResult"]["categories"]) ) {
// 				// code...
// 				$categories = $desktop_page_insight["lighthouseResult"]["categories"] ;

// 				$desktop_score = $categories["performance"]["score"] ;
// 				$desktop_score = round( $desktop_score*100 , 2 ) ;
// 			}

// 			$mobile_page_insight = google_page_speed_insight($websiteurl,"mobile") ;
// 			$mobile_score = 0 ;
// 			if ( is_array($mobile_page_insight["lighthouseResult"]["categories"]) ) {
// 				// code...
// 				$categories = $mobile_page_insight["lighthouseResult"]["categories"] ;

// 				$mobile_score = $categories["performance"]["score"] ;
// 				$mobile_score = round( $mobile_score*100 , 2 ) ;
// 			}


// 			// insert
// 			$columns = " manager_id , platform , website_url , shopify_url , desktop_speed_old , mobile_speed_old , desktop_speed_new , mobile_speed_new " ;
// 			$values = " '$manager_id' , '$platform' , '$websiteurl' , '$shopifyurl' , '$desktop_score' , '$mobile_score' , '$desktop_score' , '$mobile_score' " ;

// 			if ( insertTableData( $conn , " boost_website " , $columns , $values ) ) {
// 				$_SESSION['success'] = "Speed Boost site updated successfully!" ;
// 			}
// 			else {
// 				$_SESSION['error'] = "Operation failed!" ;
// 				$_SESSION['error'] = "Error: " . $conn->error;
// 			}
// 		}



	}

}	// header("location: ".HOST_URL."adminpannel/speed-boost.php") ;
	// die() ;
