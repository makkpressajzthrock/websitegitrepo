<?php
 session_start();
require_once("../adminpannel/config.php") ;

if(isset($_POST)){
// die;

$managerId=$_SESSION['user_id'];
$fName = $_POST['firstname'];
$email = $_POST['email'];
$address = $_POST['address'];
$address2 = $_POST['address2'];
$country = $_POST['country'];
$state = $_POST['state'];
$city = $_POST['city'];
$zip = $_POST['zip'];
$plan_type = "Subscription";


$sqlCountry = "SELECT `name` FROM `list_countries` WHERE `id` = $country";
$resultCountry = mysqli_query($conn, $sqlCountry);
$countryName = mysqli_fetch_assoc($resultCountry);
$cName = $countryName['name'];

$managerId=$_SESSION['user_id'];

$sele = "SELECT * FROM `billing-address` WHERE `manager_id` = '".$managerId."'";
$sele_con = mysqli_query($conn, $sele);
$sele_run = mysqli_fetch_assoc($sele_con);

// print_r($sele_run);
// die();
if($sele_run>0){

$update_sql= "UPDATE `billing-address` SET `full_name`='$fName' ,`email`='$email',`address`='$address',`address_2`='$address2',`country`='$country' ,`state`='$state',`city`='$city',`zip`='$zip',`plan_type`='$plan_type' WHERE `manager_id`=$managerId";

 $results = mysqli_query($conn,$update_sql);

}
else{
$sql = "INSERT INTO `billing-address` (manager_id, full_name, email, address, address_2, country,  state, city, zip,  plan_type) VALUES('".$managerId."','".$fName."','".$email."','".$address."','".$address2."','".$country."','".$state."','".$city."','".$zip."', '".$plan_type."')";
$result = mysqli_query($conn, $sql);
}
// header("Location: " . $_SERVER["HTTP_REFERER"]);


echo 1;
 
}


?>