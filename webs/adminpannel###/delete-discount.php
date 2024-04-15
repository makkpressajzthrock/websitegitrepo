<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$confirm_box=$_POST['confirm_box'];
$discount_id=$_POST['dis_id'];
$return = array('status' => '', 'message' => '');
if($confirm_box==true){
	if(isset($discount_id)){

	 $sql="DELETE FROM `discount` WHERE `id`='$discount_id'";
	 if($conn->query($sql)){
	$return['status']="done";
	$return['message']="discount deleted successfully";
	}

	}else{
		$return['status']="error";
		$return['message']="discount id not found!";
	}
}
echo json_encode($return);


?>