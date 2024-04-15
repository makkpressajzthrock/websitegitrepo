<?php 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
// echo "aman";
require('adminpannel/config.php');


echo $_POST['asn'];

$ip = $_POST['ip'];
$city = $_POST['city'];
$country = $_POST['country'];
$country_code_iso3 = $_POST['country_code_iso3'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$timezone = $_POST['timezone'];
// Read $_GET value
// die;
// $data = json_decode(file_get_contents("php://input")) ;

// print_r($data);
if(isset($_POST)){ 	

   // Insert record
 echo   $sql = "INSERT INTO cookie_handel(user_ip,user_city,user_country,user_countryIso,user_latitude,user_longitude,user_timeZone,user_flag)
    VALUES('".$ip."','".$city."','".$country."','".$country_code_iso3."','".$latitude."','".$longitude."','".$timezone."','1')";

    	if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
   
}
?>