<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;


 ///////////////////////////////////////////////////// Delete User Parmanently ///////////////////////////////////////////////////////////
 if($_POST['uid'] != "" && $_POST['uid'] != NULL  && $_POST['email'] != '' && $_POST['status'] == 'delete_user'){
    $id = intval($_POST['uid']);
    $email = "deleted__".$_POST['email'];
    $sql = "UPDATE `admin_users` SET active_status = 0 ,email='$email',lastname='makkpress' WHERE id = $id" ;
    $result = $conn->query($sql);
    if($result == true){
        $response = [
            'status' => '1',
            'message'=> 'user deleted successfully'
        ];
    }else{
        $response = [
            'status' => '2',
            'message'=> 'something went wrong'
        ];
    }
    $data = $response['status'];
    echo $data;
    
 }


 //////////////////////////////////////////////////////// Disable User //////////////////////////////////////////////////////////////////
 
 if($_POST['uid'] != "" && $_POST['uid'] != NULL && $_POST['status'] == 'disable_user'){

    $id = intval($_POST['uid']);
    $sql = "UPDATE `admin_users` SET active_status = 0 WHERE id = $id" ;
    $result = $conn->query($sql);
    if($result == true){
        $response = [
            'status' => '1',
            'message'=> 'user disable successfully'
        ];
    }else{
        $response = [
            'status' => '2',
            'message'=> 'something went wrong'
        ];
    }
    $data = $response['status'];
    echo $data;
 }