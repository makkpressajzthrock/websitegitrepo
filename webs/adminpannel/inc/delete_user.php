<?php

include('../config.php');
require_once('../inc/functions.php') ;

$response = array('status' => '' , 'message'=> '' );

if ( isset($_POST["passcode"]) ) {

if($_POST["passcode"]!="Makkdelcus@123#@!"){
		$response = array('status' => 'Inviled' , 'message'=> 'Invalid passcode. Please check the passcode and try again.' );
		echo json_encode($response);
		exit;

}

if ( isset($_POST["email"]) ) {

   $email = $_POST["email"];
   $userAgent = $_POST["userAgent"];
   $ip = $_POST["ip"];

   $sql = "SELECT id FROM admin_users where email = '".$email."' " ;
   $row = $conn->query($sql);

   if( $row->num_rows > 0 ){
   	 $user = $row->fetch_assoc(); 
   	 $manager_id = $user['id'];
   	 $conn->query("UPDATE boost_website set get_script = 0 where manager_id = '".$manager_id."' ");

	 $conn->query("UPDATE user_subscriptions set is_active = 0, script_available = 0 where user_id = '".$manager_id."' ");

	 $demail = "deleted_".$email;
	 $conn->query("UPDATE admin_users set active_status = 0, email = '".$demail."' where id = '".$manager_id."' ");

 	 $conn->query("INSERT INTO delete_user_log (deleted_email, userAgent, ip) VALUES ('".$email."', '".$userAgent."', '".$ip."' ) ");



	 $response = array('status' => 'done' , 'message'=> 'User Deleted Successfully.' );

   }
   else{
   	$response = array('status' => 'Error' , 'message'=> 'Email not exist' );
   }

   echo json_encode($response);
 
}



}



