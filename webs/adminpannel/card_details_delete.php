<?php 
include('config.php');
include('session.php');
require_once('inc/functions.php') ;

$id = $_POST['id'];
// echo "ok".$id;

    $return = array('status' => '', 'message' => '');
if (isset($id)) {

    if($conn->query("DELETE FROM `payment_method_details` WHERE id='$id'")){
       $return['status']="done"; 
       $return['message']="Card details deleted successfully!"; 
       $_SESSION['success'] = "Card details deleted successfully!" ;
    }

}
echo json_encode($return);