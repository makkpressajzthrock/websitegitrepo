<?php
 session_start();
 require_once("../adminpannel/config.php") ;
    echo "<pre>";
    print_r($_POST); die;
if(isset($_POST)){
    $sele = "SELECT * FROM `billing-address` WHERE `id` = '".$_POST['id']."'";
    $sele_con = mysqli_query($conn, $sele);
    $result = mysqli_fetch_all($sele_con,MYSQLI_ASSOC);
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
?>