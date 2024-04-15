<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
 session_start();
//  require_once("../adminpannel/config.php") ;

// error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 
 
require_once('adminpannel/config.php');
// include('session.php');
// require_once('inc/functions.php') ;


//for-satified-or-not
// echo "<pre>";
// print_r($_POST);
// if($_POST['radioButton'] =='Submit') {

//     $managerId = $_POST['managerId'];
//     $boostId = $_POST['boostId'];
//     $satified_or_not = $_POST['satified_or_not'];   
//     $sql = " UPDATE `boost_website` SET satified_or_not= '$satified_or_not' WHERE id = '$boostId' and manager_id = '$managerId' " ;
//     $result = $conn->query($sql);
//     if ( $result === TRUE ) {
//         echo json_encode(1) ;
//     }
//     else {
//         echo json_encode(0) ;
//     }   
// }

//for-rating
// echo "<pre>";
// print_r($_POST); die;
// if($_POST['ratingRadioBtn'] =='Submit'){

//     $managerId = $_POST['managerId'];
//     $boostId = $_POST['boostId'];
//     $rating = $_POST['rating'];

//     $sql = "UPDATE `boost_website` SET rating = '$rating' WHERE id = '$boostId' and manager_id = '$managerId' " ;
//     $result =  $conn->query($sql);

//     if ( $result === TRUE ) {
//         echo json_encode(1) ;
//     }
//     else {
//         echo json_encode(0) ;
//     }  
  
// }


//for-feedback
// if($_POST['feedbackBtn'] =='Submit'){
//     // echo "<pre>";
//     $managerId = $_POST['managerId'];
//     $boostId = $_POST['boostId'];
//     $feedback = $_POST['feedback'];
//     // die;

//     $sql = "UPDATE `boost_website` SET feedback = '$feedback' WHERE id = '$boostId' AND manager_id = '$managerId' " ;
//     $result =  $conn->query($sql);

//     if ( $result === TRUE ) {
//         echo json_encode(1) ;
//     }
//     else {
//         echo json_encode(0) ;
//     }    
// }


 //select--super-plan
 if($_POST['planSelect']=='Select'){
    
   
    $planId = $_POST['planId'];
    $price = $_POST['price'];

    $sql = "SELECT id, name, JSON_UNQUOTE(JSON_EXTRACT(list_of_price, '$.\"$price\"')) AS price    FROM plans    WHERE JSON_UNQUOTE(JSON_EXTRACT(list_of_price, '$.\"$price\"')) IS NOT NULL AND id = $planId";
    $result = $conn->query($sql);
    $output = mysqli_fetch_assoc($result);
 
    if($output){
        $response = [
            'status' => '1',
            'message'=> $output
        ];
        $_SESSION['price']  = $price;
    }    
   
     echo json_encode($response);   
 }





?>