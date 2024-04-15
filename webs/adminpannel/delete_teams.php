<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
include('config.php');

require_once('inc/functions.php') ;
?>

<?php


$id = $_POST['id'];

    $return = array('status' => '', 'message' => '');
if (isset($id)) {

 $sql = "delete from team_access where team_id='$id'";
     $result=mysqli_query($conn, $sql);
	 
	 
 $sql2 = "delete from admin_users where id='$id'";
     $result2=mysqli_query($conn, $sql2);
     if($result2==true){
		$_SESSION['success'] = "team deleted successfully!" ;
        $return['status']="done"; 
       $return['message']="team deleted successfully!"; 
     }else{
        $return['message']="error something went wrong.!"; 
        $return['status']="error";
     }

}

echo json_encode($return);	 
?>
