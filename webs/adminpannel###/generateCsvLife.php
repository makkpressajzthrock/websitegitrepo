<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	include('config.php');
	require_once('inc/functions.php');

	$sumo_code = "SELECT * FROM life_time";
	$code = mysqli_query($conn,$sumo_code);
	$num_rows = mysqli_num_rows($code);

	if ($num_rows > 0) 
	{
		$delimiter = ",";
		$filename = 'sumoCodes.csv';

		$fp = fopen('php://output', 'w');

		$fields = array('S.No.', 'App Sumo Code'); 
	    fputcsv($fp, $fields, $delimiter); 

	    while ($row = mysqli_fetch_assoc($code)) 
	    {
	    	$lineData = array($row['id'], $row['sumo_code']); 
        	fputcsv($fp, $lineData, $delimiter); 
	    }
    	fclose($fp);
		header('Content-Type: application/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $filename . '";');
	}
	
?>