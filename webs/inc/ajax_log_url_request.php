<?php

require('../adminpannel/config.php');

if(isset($_POST)){

	// print_r($_POST);


extract($_POST['details']);

$log_query = "INSERT INTO `url_logs` (`source`, `user_url`, `ip`, `city`, `country`, `latitude`, `longitude`, `timezone`) VALUES('$source', '$user_url', '$ip', '$city', '$country_name', '$latitude', '$longitude', '$timezone')";

$insert = $conn->query($log_query);

if($insert == TRUE){

	echo 'success';
}


}


?>