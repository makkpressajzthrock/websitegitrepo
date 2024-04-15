<?php
include('../config.php');
include('../session.php');

if(isset($_POST)){ 
$id = $_POST['id']; 

       $sql = "UPDATE generate_script_request SET wait_for_team = '1', updated_at = now() WHERE manager_id = '".$id."'";
        
        

          mysqli_query($conn, $sql);


}


?>