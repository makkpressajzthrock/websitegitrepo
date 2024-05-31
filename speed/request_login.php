<?php
require_once("adminpannel/config.php");
require_once("adminpannel/inc/functions.php");

if( $_SERVER['HTTP_REFERER'] == HOST_HELP_URL ) {


	$user_id = $_SESSION["user_id"] ; 
	if($user_id==""){
        // header("location: "."https://shopifyspeedy.com/adminpannel") ;
        header("location: ".$_SERVER['QUERY_STRING']."?l=n") ;

        die();
	}	
		$get_flow = $conn->query(" SELECT email,help_pass FROM `admin_users` WHERE id = '$user_id' ");
		$d = $get_flow->fetch_assoc();

		$u = base64_encode($d['email']);
		$p = $d['help_pass'];

        header("location: ".$_SERVER['QUERY_STRING']."?u=$u&p=$p") ;
        die();

}  
else{
        header("location: ".$_SERVER['HTTP_REFERER']) ;
        die();	
}      

?>