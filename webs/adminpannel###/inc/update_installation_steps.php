<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../config.php');
include('../session.php');

if(isset($_POST)){ 

    $id = $_POST['id'];
    $step = (int) $_POST['step'];

    if(!empty($_POST['satisfy'])){
        $satisfy = $_POST['satisfy'];  
    }
    else{
        $satisfy = null;
    }

    $method = $_POST['method'];

    if($method != null && !empty($method)){
        $method_query = "method = '$method',";
    }
    else{
        $method_query = null;
    }


    $satisfy_val = null;
    if ($satisfy!= null && !empty($satisfy) && $satisfy != " ") {
        $satisfy_val= 'satisfy='.$satisfy.',';
    }

    $query = $conn->query(" SELECT * FROM `boost_website` WHERE `id` = '".$id."' ; ") ;

    if ( $query->num_rows > 0 ) {

        $boost_website = $query->fetch_assoc() ;

        $installation = (int) $boost_website["installation"] ;

        $step = ($step > $installation) ? $step : $installation ;

        $sql = "UPDATE boost_website SET installation = '".$step."', $method_query ".$satisfy_val." updated_at = now(), installation_time = now() WHERE id = '".$id."'";

        mysqli_query($conn, $sql);

        if($step >3 ){

            $sql = "UPDATE boost_website SET installation_email = '11' WHERE id = '".$id."'";

            mysqli_query($conn, $sql);
        }

        echo json_encode(1) ;

    }
    else {
        echo json_encode(0) ;
    }

}

?>