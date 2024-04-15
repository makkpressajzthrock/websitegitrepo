<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$ticket_id = base64_decode($_GET['ticket_id']);

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

$user_id = $_SESSION['user_id'];

		$sql = "UPDATE `support_tickets` SET `resolved` = '1' WHERE `id` = '$ticket_id'" ;

		if ( $conn->query($sql) === TRUE ) {
			$_SESSION['success'] = "Ticket Resolved" ;
		}
		else {
			$_SESSION['error'] = "Operation Failed." ;
		}




	if($user_id == 1){
	header("location: ".HOST_URL."adminpannel/tickets.php") ;
	die();
	}
	else{
	header("location: ".HOST_URL."adminpannel/support-ticket.php") ;
	die();

	}



?>
