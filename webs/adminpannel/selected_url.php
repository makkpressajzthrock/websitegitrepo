<?php

require_once('config.php');
require_once('inc/functions.php') ;
		$output = array('status' => " " , 'message'=>" " );

if (isset($_POST['id']) ) {


	extract($_POST) ;
 $site_url=getTableData( $conn , "boost_website" , " id='".base64_decode($id)."'"  ) ;

$url=parse_url($site_url['website_url'])['host'];
	
	

	$output = array('status' =>"done" , 'message'=>$url );
}
	echo json_encode($output) ;
