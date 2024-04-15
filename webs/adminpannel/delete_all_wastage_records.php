<?php 
// https://websitespeedy.com/adminpannel/delete_all_wastage_records.php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
include('session.php');
// require_once('inc/functions.php') ;

// Not delete table
// add-tax
// addon
// cron_index
// country
// add_meta

// add_faq
// Not delete table



$sql1 = "DELETE FROM `additional_websites`";
//$conn->query($sql1);

$sql2 = "DELETE FROM `addon_site`";
//$conn->query($sql2);


$sql3 = "DELETE FROM `admin_reply`";
//$conn->query($sql3);

$sql4 = "DELETE FROM `admin_users` where `id`!='1' and `id`!='130'";
//$conn->query($sql4);


$sql5 = "DELETE FROM `billing-address`";
//$conn->query($sql5);



$sql6 = "DELETE FROM `boost_website`";
//$conn->query($sql6);


$sql7 = "DELETE FROM `check_installation`";
//$conn->query($sql7);


$sql8 = "DELETE FROM `cookie_handel`";
//$conn->query($sql8);


$sql9 = "DELETE FROM `core_web_vital`";
//$conn->query($sql9);



$sql10 = "DELETE FROM `expert_queries`";
//$conn->query($sql10);




$sql11 = "DELETE FROM `expert_reply`";
//$conn->query($sql11);




$sql12 = "DELETE FROM `flow_step`";
//$conn->query($sql12);



$sql13 = "DELETE FROM `generate_script_request`";
//$conn->query($sql13);


$sql14 = "DELETE FROM `manager_company`";
//$conn->query($sql14);


$sql15 = "DELETE FROM `manager_sites`";
//$conn->query($sql15);


$sql16 = "DELETE FROM `need_developer`";
//$conn->query($sql16);


$sql17 = "DELETE FROM `other_help`";
//$conn->query($sql17);


$sql18 = "DELETE FROM `pagespeed_report`";
//$conn->query($sql18);


$sql19 = "DELETE FROM `payment_info`";
//$conn->query($sql19);


$sql20 = "DELETE FROM `payment_method_details`";
//$conn->query($sql20);


$sql21 = "DELETE FROM `strip_webhook`";
//$conn->query($sql21);


$sql22 = "DELETE FROM `speed_script`";
//$conn->query($sql23);


$sql23 = "DELETE FROM `teams`";
//$conn->query($sql23);


$sql24 = "DELETE FROM `team_access`";
//$conn->query($sql24);


$sql25 = "DELETE FROM `ticket_replies`";
//$conn->query($sql25);


$sql26 = "DELETE FROM `user_confirm`";
//$conn->query($sql26);


$sql27 = "DELETE FROM `user_subscriptions`";
//$conn->query($sql27);


$sql28 = "DELETE FROM `user_subscriptions_free`";
//$conn->query($sql28);

$sql29 = "DELETE FROM `website_speed_history`";
//$conn->query($sql29);


?>