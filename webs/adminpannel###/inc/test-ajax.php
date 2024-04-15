<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
ini_set('max_execution_time', '0');

include('../config.php');
require_once('../inc/functions.php') ;


$wd_data = [];
$wd_query = $conn->query("SELECT id, website_id, parent_website, categories, mobile_categories, updated_at, no_speedy, ws_status, blank_record FROM pagespeed_report WHERE website_id = '4215' AND parent_website = '0' AND no_speedy = 1 ORDER BY id ASC LIMIT 1");



if ($wd_query->num_rows > 0) {

    $speed = 1 ;

    $wd_data = $wd_query->fetch_assoc();
}

print_r($wd_data) ;