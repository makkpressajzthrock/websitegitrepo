
<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
 session_start();
//  require_once("../adminpannel/config.php") ;

// error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 
 
include('config.php');
include('session.php');
require_once('inc/functions.php') ;

// echo "<pre>";
// print_r($_POST);
// die;

//for-adding-value-url2
// echo "<pre>";
// print_r($_POST);die;
if($_POST['submitBtn'] =='Submit'){
    $managerId = $_POST['managerId'];
    $boostId = $_POST['boostId'];
    $website_url = $_POST['website_url'];
    $websiteName = $_POST['websiteName'];
    $url_priority = $_POST['url_priority'];

    // die;
    $query = "SELECT  id, manager_id, website_url FROM `boost_website` WHERE `manager_id` = '$managerId' and  `id` = '$boostId'  ";
    $res =  $conn->query($query);
    $output =   mysqli_fetch_assoc($res);
    $url2check = $output['website_url']."/";

    if($url2check==$website_url || $output['website_url']== $website_url){
        $response =  [
            'status' => '5',
            'message' => "Please don't enter same url"
        ];
        // echo "Please don't enter same url";
        echo json_encode($response); die;
    }else{
        $sql = "INSERT INTO additional_websites ( manager_id , website_id , website_name , website_url , monitoring , flag  , url_priority ) VALUES ( '$managerId' , '$boostId' , '$websiteName' , '$website_url' , 0 , 'true' , '$url_priority'  )" ;
            // $sql = "UPDATE `boost_website` SET url2=' $website_url' WHERE id = '$boostId' and manager_id = '$managerId' " ;
        $result =  $conn->query($sql);
        $last_insert_id_url2 = $conn->insert_id;
        if($result){
            
            $sql1 = "SELECT * FROM `additional_websites` WHERE `manager_id` = '$managerId' and  `website_id` = '$boostId'  and id = '$last_insert_id_url2'";
            $result1 =  $conn->query($sql1);
            $result1 =   mysqli_fetch_assoc($result1);
            if($result1){
            $response = [
                'status' => '1',
                'message' => $result1
                ];
            }else{
            $response = [
                'status' => '1',
                'message' => 'Something went wrong !!'
                ];
            }
        //  print_r($result1);die;
        echo json_encode($response);
            
        }else{
            echo "Something went wrong !!";
        }   
    }
    
}

//for-adding-value-url3
if($_POST['submitUrl3Btn'] =='Submit'){
    // echo "<pre>";
    // print_r($_POST);die;
    $managerId = $_POST['managerId'];
    $boostId = $_POST['boostId'];
    $website_url = $_POST['website_url'];
    $websiteName = $_POST['websiteName'];
    $url_priority = $_POST['url_priority'];
    // die;

    $query = "SELECT  id, manager_id, website_url FROM `boost_website` WHERE `manager_id` = '$managerId' and  `id` = '$boostId'  ";
    $res =  $conn->query($query);
    $output =   mysqli_fetch_assoc($res);
    $url3check = $output['website_url']."/";
    // echo $url3check;
    if($url3check==$website_url || $output['website_url']== $website_url){
        $response =  [
            'status' => '5',
            'message' => "Please don't enter same url"
        ];
        // echo "Please don't enter same url";
        echo json_encode($response); die;
    }else{
                // $sql = "UPDATE `boost_website` SET url3=' $website_url' WHERE id = '$boostId' and manager_id = '$managerId' " ;
        $sql = "INSERT INTO additional_websites ( manager_id , website_id , website_name , website_url , monitoring , flag, url_priority ) VALUES ( '$managerId' , '$boostId' , '$websiteName' , '$website_url' , 0 , 'true' , '$url_priority' ) " ;
        $result =  $conn->query($sql);
        $last_insert_id_url3 = $conn->insert_id;
        if($result){
        $sql1 = "SELECT * FROM `additional_websites` WHERE `manager_id` = '$managerId' and  `website_id` = '$boostId'  and id = '$last_insert_id_url3'";
        $result1 =  $conn->query($sql1);
        $result1 =   mysqli_fetch_assoc($result1);
        if($result1){
            $response = [
                'status' => '1',
                'message' => $result1
            ];
        }else{
            $response = [
                'status' => '1',
                'message' => 'Something went wrong !!'
            ];
        }
        //  print_r($response);die;
        echo json_encode($response);
        
        }else{
            echo "Something went wrong !!";
        }   
    }

  
}

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


if($_POST['reviewandfeedback'] =='Submit'){
    // echo "<pre>";
    $managerId = $_POST['managerId'];
    $boostId = $_POST['boostId'];
    $satified_or_not = $_POST['satified_or_not'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    $trustPilot = $_POST['reviewLeft'];
    // die;

    $sql = "UPDATE `boost_website` SET feedback = '$feedback',satified_or_not='$satified_or_not', rating='$rating', feedback='$feedback', trust_pilot_review='$trustPilot' WHERE id = '$boostId' AND manager_id = '$managerId' " ;
    $result =  $conn->query($sql);

    if ( $result === TRUE ) {
        echo json_encode(1) ;
    }
    else {
        echo json_encode(0) ;
    }    
}





if($_POST['changeValue']== 'onChangeBillingAddress'){
    $sele = "SELECT * FROM `billing-address` WHERE `id` = '".$_POST['id']."'";
    $sele_con = mysqli_query($conn, $sele);
    $result = mysqli_fetch_assoc($sele_con);
    if($result){
      
        $response = [
            'status' => '1',
            'data' => $result
        ];
    }else{
        $response = [
            'status' => '0',
            'data' => 'error'
        ];
    }
    echo json_encode($response);

}

// echo "<pre>";
// print_r($_POST);die;
//install-mode
if($_POST['installModeRadio']=='Submit'){
  
    $managerId = $_POST['managerId'];
    $boostId = $_POST['boostId'];
    $installModeVal = $_POST['installModeVal'];
    $newWebsite = 'new_website';
     if($installModeVal=='no'){         
         $newWeb = 'NULL';
     }else{
        $newWeb = 'new_website';
     }
 
    // die;

    $sql = "UPDATE `boost_website` SET  new_website = '$newWeb',  self_install = '$installModeVal' WHERE id = '$boostId' AND manager_id = '$managerId' " ;
    $result =  $conn->query($sql);

    if ( $result === TRUE ) {
        $sql1 = "SELECT `id`,  `manager_id`, `self_install`, `self_install_team`  FROM `boost_website` WHERE  id = '$boostId' AND manager_id = '$managerId'";
        $sele_con = mysqli_query($conn, $sql1);
        $output = mysqli_fetch_assoc($sele_con);
        $response  = [
            'status' => '1',
            'message'=> $output
        ];
    }
    else {
        $response = [
            'status' => '2',
            'message'=> 'something went wrong'
        ];
    } 
     echo json_encode($response);   
 }

 //thanks-installation-self-btn
if($_POST['selfInsBtn']=='Submit'){
  
    $managerId = $_POST['managerId'];
    $boostId = $_POST['boostId'];
    $selfVal = $_POST['selfVal'];
    $newWebsite = 'new_website';
 
    // die;

    $sql = "UPDATE `boost_website` SET new_website =NULL, self_install_team = '$selfVal' WHERE id = '$boostId' AND manager_id = '$managerId' " ;
    $result =  $conn->query($sql);

    if ( $result === TRUE ) {
        $sql1 = "SELECT `id`,  `manager_id`, `self_install`, `self_install_team`  FROM `boost_website` WHERE  id = '$boostId' AND manager_id = '$managerId'";
        $sele_con = mysqli_query($conn, $sql1);
        $output = mysqli_fetch_assoc($sele_con);
        $response  = [
            'status' => '1',
            'message'=> $output
        ];
    }
    else {
        $response = [
            'status' => '2',
            'message'=> 'something went wrong'
        ];
    } 
     echo json_encode($response);   
 }


 //check-confirm-speed-first
 if($_POST['checkConfirmSpeedFirstBtn']=='Submit'){
  
    $managerId = $_POST['managerId'];
    $boostId = $_POST['boostId'];
    $checkedspeed = $_POST['checkedspeed'];
 
    // die;

    $sql = "UPDATE `boost_website` SET check_first_speed = '$checkedspeed' WHERE id = '$boostId' AND manager_id = '$managerId' " ;
    $result =  $conn->query($sql);

    if ( $result === TRUE ) {
        $sql1 = "SELECT `id`,  `manager_id`, `check_first_speed`  FROM `boost_website` WHERE  id = '$boostId' AND manager_id = '$managerId'";
        $sele_con = mysqli_query($conn, $sql1);
        $output = mysqli_fetch_assoc($sele_con);
        $response  = [
            'status' => '1',
            'message'=> $output
        ];
    }
    else {
        $response = [
            'status' => '2',
            'message'=> 'something went wrong'
        ];
    } 
     echo json_encode($response);   
 }



//  echo "<pre>";
//  print_r($_POST); die;
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
