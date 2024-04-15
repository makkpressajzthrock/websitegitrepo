<?php 
include('config.php');
// Delete User
$request = 1;
if(isset($_POST['request'])){
    $request = $_POST['request'];
}
 

if($request == 4){
    $id = 0;

    if(isset($_POST['id'])){
        $id = $conn-> real_escape_string(($_POST['id']));
    }

    $result = mysqli_query($conn, "SELECT * FROM admin_users WHERE id=".$id);
    $login_check = mysqli_num_rows($result);

    // echo $row['id'] . " row(s) returned.\n";
    if($login_check >0 ){
        mysqli_query($conn,"DELETE FROM admin_users WHERE id=".$id);

    echo 1;
    exit;
    }else{

       echo 0;
       exit;
    }

}


// Fetch user details
if($request == 2){
    $id = 0;

    if(isset($_POST['id'])){
        $id = $conn->real_escape_string(($_POST['id']));
    }

    $record = mysqli_query($conn, "SELECT * FROM admin_users WHERE id=".$id);

    $response = array();

    if(pg_num_rows($record) > 0){
        $row = mysqli_fetch_assoc($record);
        $response = array(
            "name" => $row['username'],
            "email" => $row['email'],
            "userstatus" => $row['userstatus']
            
        );

        echo json_encode( array("status" => 1,"data" => $response) );
        exit;
    }else{
        echo json_encode( array("status" => 0) );
        exit;
    }
}




// Update user
if($request == 3){
    $id = 0;

    if(isset($_POST['id'])){
        $id = $conn->real_escape_string(($_POST['id']));
    }

    // Check id
    $record = mysqli_query($conn, "SELECT * FROM admin_users WHERE id=".$id);
    if(mysqli_num_rows($record) > 0){

        $name = $conn->real_escape_string((trim($_POST['name'])));
        $email = $conn->real_escape_string(($_POST['email']));
        $userstatus = $conn->real_escape_string((trim($_POST['userstatus'])));
        
        

        if( $name != '' && $email != '' && $userstatus != ''){

            mysqli_query($conn,"UPDATE admin_users SET username='".$name."',email='".$email."',userstatus='".$userstatus."' WHERE id=".$id);

            echo json_encode( array("status" => 1,"message" => "Record updated.") );
            exit;
        }else{
            echo json_encode( array("status" => 0,"message" => "Please fill all fields.") );
            exit;
        }
        
    }else{
        echo json_encode( array("status" => 0,"message" => "Invalid ID.") );
        exit;
    }
}

?>