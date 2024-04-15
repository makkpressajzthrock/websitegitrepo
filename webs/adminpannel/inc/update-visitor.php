<?php

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

if ( isset($_POST["opt"]) && !empty($_POST["opt"]) ) {

	$return = array('label'=> '' , 'data'=> '' , 'color' => '' ) ;

	extract($_POST) ;


	switch ($opt) {
		case '24hours':
			// $query = $conn->query("SELECT website_visits.* , DATE_FORMAT(website_visits.created_at,'%l %p') AS visitor_date FROM website_visits WHERE website_visits.manager_id = '$store' AND website_visits.created_at > now() - INTERVAL 24 hour") ;
			// $query = $conn->query("SELECT website_visits.* , DATE_FORMAT(website_visits.created_at,'%l %p') AS visitor_date FROM website_visits WHERE website_visits.manager_id = '$owner' AND website_id = '$website' AND DATE(website_visits.created_at) = CURDATE()") ;

			$query =  $conn->query( " SELECT DATE_FORMAT(website_visits.created_at,'%l %p') AS visitor_date , COUNT(id) AS count FROM website_visits WHERE website_visits.manager_id = '$owner' AND website_id = '$website' AND DATE(website_visits.created_at) = CURDATE() GROUP BY visitor_date " ) ;
			break;

		case '7days':
			// $query = $conn->query("SELECT website_visits.* , DATE_FORMAT(website_visits.created_at,'%e %b') AS visitor_date FROM website_visits WHERE website_visits.manager_id = '$store' AND website_visits.created_at > now() - INTERVAL 7 day") ;

			$query = $conn->query(" SELECT DATE_FORMAT(website_visits.created_at,'%e %b') AS visitor_date , COUNT(id) AS count FROM website_visits WHERE website_visits.manager_id = '$owner' AND website_id = '$website' AND DATE(website_visits.created_at) - INTERVAL 7 day GROUP BY visitor_date ") ;
			break;

		case 'Month':

			$query = $conn->query(" SELECT DATE_FORMAT(website_visits.created_at,'%e %b') AS visitor_date , COUNT(id) AS count FROM website_visits WHERE website_visits.manager_id = '$owner' AND website_id = '$website' AND DATE(website_visits.created_at) - INTERVAL 30 day GROUP BY visitor_date ") ;
			break;

		case '6Month':

			$query = $conn->query( " SELECT DATE_FORMAT(website_visits.created_at,'%b %Y') AS visitor_date , COUNT(id) AS count FROM website_visits WHERE website_visits.manager_id = '$owner' AND website_id = '$website' AND DATE(website_visits.created_at) - INTERVAL 180 day GROUP BY visitor_date " ) ;
			break;
		
		default:
			// Year
			$query = $conn->query( " SELECT DATE_FORMAT(website_visits.created_at,'%Y') AS visitor_date , COUNT(id) AS count FROM website_visits WHERE website_visits.manager_id = '$owner' AND website_id = '$website' AND DATE(website_visits.created_at) - INTERVAL 360 day GROUP BY visitor_date " ) ;
			break;
	}

	if ( $query->num_rows > 0 ) {
		$chart_data = $query->fetch_all(MYSQLI_ASSOC) ;
	
		foreach ($chart_data as $key => $value) {

			$chartLabel[] = $value["visitor_date"] ;
			$chartData[] =  $value["count"];

			if ( $key <= count($color_arr) ) {
				$chartColor[] = $color_arr[$key] ;
			}
			else {
				$chartColor[] = $color_arr[count($chart_data)-$key] ;
			}

		}
	
	}

	$chartLabel = implode(",",$chartLabel) ;
	$chartData = implode(",",$chartData) ;
	$chartColor = implode(",",$chartColor) ;

	$return['label'] = $chartLabel ;
	$return['data'] = $chartData ;
	$return['color'] = $chartColor ;

	// if ( $query->num_rows > 0 ) {

	// 	$chart_data = $query->fetch_all(MYSQLI_ASSOC) ;

	// 	foreach ($chart_data as $key => $value) {

	// 	    if (array_key_exists($value["visitor_date"], $chartPoints)) {
	// 	        $chartPoints[$value["visitor_date"]] = $chartPoints[$value["visitor_date"]] + 1 ;
	// 	    }
	// 	    else {
	// 	        $chartPoints[$value["visitor_date"]] = 1 ;
	// 	    }
	// 	}

	// 	$chartLabel = array_keys($chartPoints) ;
	// 	$chartLabel = implode("," , $chartLabel) ;
	// 	$return['label'] = $chartLabel ;

	// 	$chartData = array_values($chartPoints) ;
	// 	$chartData = implode("," , $chartData) ;
	// 	$return['data'] = $chartData ;
	// }

	echo json_encode($return) ;

}

