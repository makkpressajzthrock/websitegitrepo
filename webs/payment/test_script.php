<?php 
session_start();
include('../adminpannel/session.php');
include('../adminpannel/config.php');
// Include the configuration file  
require_once 'config.php'; 
 
// Include the database connection file  
require_once 'dbConnect.php'; 
            $user_id = $_SESSION["user_id"] ;

        require_once("../adminpannel/generate_script_paid.php");



        ?>