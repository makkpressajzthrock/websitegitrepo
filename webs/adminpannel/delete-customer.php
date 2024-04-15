<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);


$confirm_box=$_POST['confirm_box'];
$id=$_POST['id'];
$return = array('status' => '', 'message' => '');
	if(isset($id)){
		  $sql="DELETE FROM `addon_site` WHERE user_id='$id';";
$project_sql=mysqli_query($conn,"SELECT * FROM `boost_website` WHERE `manager_id` = '$id'");
 while ($project = mysqli_fetch_array($project_sql, MYSQLI_ASSOC)) {
	$project_id=$project['id'];
	  $sql.="DELETE FROM `core_web_vital` WHERE website_id ='$project_id';";
	  $sql.="DELETE FROM `pagespeed_report` WHERE website_id ='$project_id';";
	  $sql.="DELETE FROM `speed_record` WHERE website_id ='$project_id';";
	  $sql.="DELETE FROM `script_log` WHERE site_id ='$project_id';";
	  $sql.="DELETE FROM `team_access` WHERE website_id ='$project_id';";
	  $sql.="DELETE FROM `temp_pagespeed_report` WHERE website_id ='$project_id';";
	  $sql.="DELETE FROM `website_speed_history` WHERE website_id ='$project_id';";

}
	  $sql.="DELETE FROM admin_users WHERE `id`='$id';";
	  $sql.="DELETE FROM `additional_websites` WHERE manager_id ='$id';";
	
	  $sql.="DELETE FROM `billing-address` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `boost_website` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `check_installation` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `details_warranty_plans` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `expert_queries` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `expert_reply` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `flow_step` WHERE user_id='$id';";
	  $sql.="DELETE FROM `generate_script_request` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `manager_company` WHERE user_id='$id';";
	  $sql.="DELETE FROM `manager_sites` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `need_developer` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `other_help` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `payment_method_details` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `report_send_status` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `support_tickets` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `ticket_replies` WHERE manager_id='$id';";
	  $sql.="DELETE FROM `user_confirm` WHERE user_id='$id';";
	  $sql.="DELETE FROM `user_subscription` WHERE user_id='$id';";
	  $sql.="DELETE FROM `user_subscriptions` WHERE user_id='$id';";
	  $sql.="DELETE FROM `user_subscriptions_free` WHERE user_id='$id';";
	  $sql.="DELETE FROM `user_subscriptions_log` WHERE user_id='$id';";
	  $sql.="DELETE FROM `website_visits` WHERE user_id='$id';";
	   // echo " sql".$sql;
	 if($conn->multi_query($sql)){
	$return['status']="done";
	$return['message']="customer deleted successfully";
	}

	}else{
		$return['status']="error";
		$return['message']="customer id not found!";
	}

echo json_encode($return);


?>