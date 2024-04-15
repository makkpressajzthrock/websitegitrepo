<?php 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include('config.php');

require_once('inc/functions.php') ;
?>

<?php




  $user= base64_decode($_GET['delete']);

 $sql = "delete from deal_fuel where id='$user'";
     $result=mysqli_query($conn, $sql);
	 
	
	 
	if($result==true){
		
		$_SESSION['success'] = "Delete  successfully!";
		
		
	
}
else {
	$_SESSION['error'] = "Not Delete!";
}

header("location:../adminpannel/deal_fuel_code.php");
	 
?>
