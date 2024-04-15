<?php 


include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

$plan_id = base64_decode($_GET['plan_id']);


if($conn->query("DELETE FROM `plans` WHERE id =  ".$plan_id."")){

	header("location: ".HOST_URL."adminpannel/plans.php");

	$_SESSION['success'] == 'Deleted!';

}



?>



