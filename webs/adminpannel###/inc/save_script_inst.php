<?php
include('../config.php');
include('../session.php');

if(isset($_POST)){ 
$id = $_POST['id'];
$traffic = $_POST['traffic'];
$platform = $_POST['platform'];
$country = $_POST['country'];

       $sql = "UPDATE boost_website SET traffic = '".$traffic."' , platform = '".$platform."', country = '".$country."', updated_at = now() WHERE id = '".$id."'";
        
        

          mysqli_query($conn, $sql);


}

// echo 'ss';


?>