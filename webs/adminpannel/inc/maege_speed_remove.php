<?php

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

if ( isset($_POST["id"]) ) {

$id = $_POST["id"];
			
					$query = $conn->query("delete FROM temp_pagespeed_report  WHERE website_id = '$id'");
			


}

