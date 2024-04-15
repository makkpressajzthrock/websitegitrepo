<?php
include('config.php');
include('session.php');
require_once('inc/functions.php') ;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$current_date = date("Y-m-d h:i");
$current_date = strtotime($current_date);



$warranty_plans_data = mysqli_query($conn, "SELECT * FROM `details_warranty_plans` WHERE `status` = 'succeeded' ");
$output = mysqli_num_rows($warranty_plans_data);

// echo "<pre>";
// echo "output : " . $output;
if ($output > 0) {
// echo "output : " . $output."<br>";

	while ($customer = mysqli_fetch_array($warranty_plans_data, MYSQLI_ASSOC)) {
	// print_r($customer);	
		
	$starting_date=$customer['paymentDate'];
	$plans_data_id=$customer['id'];
	$plan_id=$customer['plan_id'];

	if($plan_id==1){
	$newDate = date("Y-m-d h:i", strtotime($starting_date."+2 Month"));

	// echo "newDate ".$newDate."<br>";
	// echo "starting_date ".$starting_date."<br>";
	$newDate = strtotime($newDate);
		if($current_date>$newDate){
			$description=$customer['description'];


		// echo "active"."<br>";
		// echo $description."<br>";

		updateTableData( $conn , "details_warranty_plans" , "status='expire'" ,"id='".$plans_data_id."'" );
		}

	}

	if($plan_id==2){
	$newDate = date("Y-m-d h:i", strtotime($starting_date."+6 Month"));

	// echo "newDate ".$newDate."<br>";
	// echo "starting_date ".$starting_date."<br>";
	$newDate = strtotime($newDate);
	if($current_date>$newDate){
			$description=$customer['description'];

		// echo "active"."<br>";
		// echo $description."<br>";
		updateTableData( $conn , "details_warranty_plans" , "status='expire'" ,"id='".$plans_data_id."'" );

	}

	}

	if($plan_id==3){
	$newDate = date("Y-m-d h:i", strtotime($starting_date."+1 Year"));

	// echo "newDate ".$newDate."<br>";
	// echo "starting_date ".$starting_date."<br>";
	$newDate = strtotime($newDate);
		if($current_date>$newDate){
			$description=$customer['description'];

		
		// echo "active"."<br>";
		// echo $description."<br>";
		updateTableData( $conn , "details_warranty_plans" , "status='expire'" ,"id='".$plans_data_id."'" );


		}

	}


	}
}
?>