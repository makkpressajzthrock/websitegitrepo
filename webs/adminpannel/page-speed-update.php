<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;


$stype1 			= $_POST['stype1'];
$changetime 			= $_POST['changetime'];
$changetime2 			= $_POST['changetime2'];
// $result = array('status' =>"" , 'message' =>"" );
$response = array('status' => '' , 'message'=> '' );
if(isset($changetime) ||  isset($stype1)){
$updates= mysqli_query($conn,"update boost_website set period='$stype1 ' where id='$changetime'");
// $response['status']="done";
// $response['message']="update successfully";

}

if(isset($changetime2) || isset($stype1)){
$update= mysqli_query($conn,"update additional_websites set period='$stype1 ' where id='$changetime2'");
$response['status']="donesss";
$response['message']="update successfully";
}

echo json_encode($response);
?>