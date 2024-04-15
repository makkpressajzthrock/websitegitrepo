<?php
print_r($_POST);die;
    if($sele_run>0){

    $update_sql= "UPDATE `billing-address` SET `full_name`='$fName' ,`email`='$email',`address`='$address',`country`='$country' ,`state`='$state',`city`='$city',`zip`='$zip',`plan_type`='$plan_type' WHERE `manager_id`=$managerId";
    
     $results = mysqli_query($conn,$update_sql);
    
    }
    else{
    $sql = "INSERT INTO `billing-address` (manager_id, full_name, email, address, country,  state, city, zip,  plan_type) VALUES('".$managerId."','".$fName."','".$email."','".$address."','".$cName."','".$state."','".$city."','".$zip."', '".$plan_type."')";
    $result = mysqli_query($conn, $sql);
    }


?>