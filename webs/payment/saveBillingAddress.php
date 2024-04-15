<?php
//  error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 
 session_start();
require_once("../adminpannel/config.php") ;
    
// echo "<pre>";
// print_r($_POST);die;
   
if(isset($_POST)){

$managerId=$_SESSION['user_id'];
$fName = $_POST['firstname'];
$email = $_POST['email'];
$address = $_POST['address'];
$address2 = $_POST['address2'];
$country = $_POST['country'];
$state = $_POST['state']?:'';
$city = $_POST['city'];
$zip = $_POST['zip'];
$plan_type = "Subscription";
//123
$country_code = $_POST['country_code'];
$contact_number = $_POST['contact_number'];

$sqlCountry = "SELECT `name` FROM `list_countries` WHERE `id` = $country";
$resultCountry = mysqli_query($conn, $sqlCountry);

$countryName = mysqli_fetch_assoc($resultCountry);
$cName = $countryName['name'];

$managerId=$_SESSION['user_id'];

$sele = "SELECT * FROM `billing-address` WHERE `manager_id` = '".$managerId."'";
$sele_con = mysqli_query($conn, $sele);
$sele_run = mysqli_fetch_assoc($sele_con);

if($sele_run>0){
   // echo 1; die;
   //123
   $updateadminData =  mysqli_query($conn, "UPDATE `admin_users` SET `country_code`='$country_code' ,`phone`='$contact_number',`alt_phone`='$contact_number' WHERE `id`=$managerId");
   $update_sql= "UPDATE `billing-address` SET `full_name`='$fName' ,`email`='$email',`address`='$address',`address_2`='$address2',`country`='$country' ,`state`='$state',`city`='$city',`zip`='$zip',`plan_type`='$plan_type' WHERE `manager_id`=$managerId";
   //  $results = mysqli_query($conn,$updateadminData);
    $results = mysqli_query($conn,$update_sql);
}
else{ 
   // echo 2;die;  
   $sql = "INSERT INTO `billing-address` (manager_id, full_name, email, address, address_2, country,  state, city, zip,  plan_type) VALUES('".$managerId."','".$fName."','".$email."','".$address."','".$address2."','".$country."','".$state."','".$city."','".$zip."', '".$plan_type."')";
   $result = mysqli_query($conn, $sql);
}

}
// header("Location: " . $_SERVER["HTTP_REFERER"]);


echo 1;

?>