<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$confirm_box=$_POST['confirm_box'];
$tax_id=$_POST['tax_id'];
$return = array('status' => '', 'message' => '');
if($confirm_box==true){
	if(isset($tax_id)){

	 $sql="DELETE FROM `add_meta` WHERE `id`='$tax_id'";
	 if($conn->query($sql)){
	$return['status']="done";
	$return['message']="Deleted successfully";
	}

	}else{
		$return['status']="error";
		$return['message']=" Id not found!";
	}
}
echo json_encode($return);


?>