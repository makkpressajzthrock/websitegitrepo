<?php

require_once('config.php');
require_once('inc/functions.php');
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    extract($_POST);

$output = array('status' => "", 'message'=> "");
$id = base64_decode($_POST['id']);

$check_status = $_POST['status'] != '' ? $_POST['status'] : 0;
$check_access_requested = $_POST['access_requested'] != '' ? $_POST['access_requested'] : 0;
$check_optimisation_is_progress = $optimisation_is_progress != '' ? $optimisation_is_progress : 0;
if (isset($id)) {
    


if(updateTableData( $conn , "generate_script_request" , "status='$status' , access_requested='$check_access_requested',optimisation_is_progress= '$check_optimisation_is_progress', wait_for_team = 0 " , "id='".$id."'" )){
	if ($status==1) {
	    // code...
	    $output['status'] = "done";
	    $output['message'] = "Script Installation Status Completed successfully! ";
	}elseif($status==11){
	    $output['status'] = "done";
	    $output['message'] = "Script Installation Status <b>Access Pending</b> successfully! ";
	}elseif($status==12){
	    $output['status'] = "done";
	    $output['message'] = "Script Installation Status <b>Email Not Sent</b> successfully! ";
	}elseif($status==13){
	    $output['status'] = "done";
	    $output['message'] = "Script Installation Status <b>Client Will Reply Later</b> successfully! ";
	}elseif($status==14){
	    $output['status'] = "done";
	    $output['message'] = "Script Installation Status <b>Wrong Credentials</b> successfully! ";
	}elseif($status==15){
	    $output['status'] = "done";
	    $output['message'] = "Script Installation Status <b>Client Not Replying</b> successfully! ";
	}elseif($status==16){
	    $output['status'] = "done";
	    $output['message'] = "Script Installation Status <b>Not Possible</b> successfully! ";
	}elseif($check_access_requested==1){
	    $output['status'] = "done";
	    $output['message'] = "Access Requested successfully! ";
	}elseif($check_optimisation_is_progress==1){
	    $output['status'] = "done";
	    $output['message'] = "Optimisation Is Progress successfully! ";
	}

	else{
	    $output['status'] = "done";
	    $output['message'] = "Script Installation Status pending successfully! ";
	}





}else{
   $output['status'] = "erorr";
    $output['message'] = "update error  "; 
}




}
    echo json_encode($output);
