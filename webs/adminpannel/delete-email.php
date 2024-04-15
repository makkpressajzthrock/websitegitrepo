<?php 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include('config.php');

require_once('inc/functions.php') ;
?>

<?php




  $user= $_GET['emailtemplateid'];

 $sql = "delete from email_template where id='$user'";
     $result=mysqli_query($conn, $sql);
	 


header("location:../adminpannel/email_template.php");
	 
?>
