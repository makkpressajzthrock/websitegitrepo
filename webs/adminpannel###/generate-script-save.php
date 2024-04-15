<?php

require_once('config.php');
require_once('inc/functions.php');
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

    extract($_POST);

$output = array('status' => "", 'message'=> "");

$sele_sql="SELECT * FROM generate_script_request WHERE  website_id ='".base64_decode($id)."' AND manager_id = '".$_SESSION['user_id']. "' ";

$result=$conn -> query($sele_sql);
if ($result->num_rows > 0) {
    $output['status'] = "done";
    $output['message'] = "already saved successfully! ";
} else {



    if (isset($_POST["id"])) {
        $start_date = date_create($start_date);
        $start_date = date_format($start_date, "Y/m/d H:i:s");
        // echo $start_date;
        $end_date = date_create($end_date);
        $end_date = date_format($end_date, "Y/m/d H:i:s");
        // echo $end_date;

        $sql = "INSERT INTO generate_script_request (manager_id, website_id, website_url, traffic, platform, country,script,start_date,end_date) VALUES('".$_SESSION['user_id']."','".base64_decode($id)."','".$website_url."','".$traffic."','".$platform."','".$country_id."','".$script."','".$start_date."','".$end_date."')";


        if ($conn -> query($sql)) {

            $output['status'] = "done";
            $output['message'] = "generate script request saved successfully! ";
        }

    }


}
    echo json_encode($output);
