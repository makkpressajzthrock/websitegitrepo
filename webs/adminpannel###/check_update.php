<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

$id=$_POST['id'];
$monitoring=$_POST['monitoring_val'];
$return = array('status' => '', 'message' => '');

if(isset($id)){

 updateTableData( $conn , "details_warranty_plans" , "monitoring='".$monitoring."'" ,"id='".$id."'" ) ;
$return['status']="done";
$return['message']=$monitoring;

}else{
	$return['status']="error";
	$return['message']="id not found!";
}
echo json_encode($return);


?>