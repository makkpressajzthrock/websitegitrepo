<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$confirm_box=$_POST['confirm_box'];
$plan_id=$_POST['plan_id'];
$return = array('status' => '', 'message' => '');
if($confirm_box==true){
	if(isset($plan_id)){

	 $sql="DELETE FROM `plans_warranty` WHERE `id`='$plan_id'";
	 if($conn->query($sql)){
	$return['status']="done";
	$return['message']="plan deleted successfully";
	}

	}else{
		$return['status']="error";
		$return['message']="plan id not found!";
	}
}
echo json_encode($return);


?>