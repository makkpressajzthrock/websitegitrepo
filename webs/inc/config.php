<?php

require_once("/var/www/html/adminpannel/env.php") ;

$host = "localhost";
$host_name = "root";
$password = "makkweb@123A"; 
$db_select = "websitespeedy";

 

$conn = new mysqli( $host , $host_name , $password , $db_select );
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
}

