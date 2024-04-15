<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('../config.php');
include('../session.php');
require_once('../inc/functions.php') ;

/*** pagespeed_report table condition ***/ 
$pagespeedy_report = PAGESPEED_REPORT_TABLE;

if ( isset($_POST["id"]) ) {

$id = $_POST["id"];
$speedtype = $_POST["speedtype"];

				
				$query = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$id' and plateform = 'desktop'");
				if ($query->num_rows > 0) {
 
					$pagespeed_data = $query->fetch_assoc();

					 $pagespeed_data['id'];
					$requestedUrl = $pagespeed_data['requestedUrl'];

					$finalUrl = $pagespeed_data['finalUrl'];
					$userAgent = $conn->real_escape_string(serialize(unserialize($pagespeed_data['userAgent'])));
					$fetchTime = $pagespeed_data['fetchTime'];
					$environment = $conn->real_escape_string(serialize(unserialize($pagespeed_data['environment'])));
					$runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_data['runWarnings'])));
					$configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_data['configSettings'])));
					$audits = $conn->real_escape_string(serialize(unserialize($pagespeed_data['audits'])));
					$categories = $conn->real_escape_string(serialize(unserialize($pagespeed_data['categories'])));
					$categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_data['categoryGroups'])));
					$i18n = $conn->real_escape_string(serialize(unserialize($pagespeed_data['i18n'])));

					$queryDesk = $conn->query(" SELECT * FROM temp_pagespeed_report  WHERE website_id = '$id' and plateform = 'mobile'");
					if ($queryDesk->num_rows > 0) {

						$pagespeed_datadesk = $queryDesk->fetch_assoc();

						 $mobile_environment = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['environment'])));
						$mobile_runWarnings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['runWarnings'])));
						$mobile_configSettings = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['configSettings'])));
						$mobile_audits = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['audits'])));
						$mobile_categories = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categories'])));
						$mobile_categoryGroups = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['categoryGroups'])));
						$mobile_i18n  = $conn->real_escape_string(serialize(unserialize($pagespeed_datadesk['i18n'])));



						if($speedtype == "old"){
							  $conn->query("delete from $pagespeedy_report  WHERE website_id = '$id'");
						}

						$sql = " INSERT INTO $pagespeedy_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n , no_speedy ) VALUES ( '$id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' , 1 ) " ;


			if ( $conn->query($sql) === TRUE ) {
					echo json_encode("saved");
					$query = $conn->query("delete FROM temp_pagespeed_report  WHERE website_id = '$id'");

					$speedtype = $_POST["speedtype"];
					if($speedtype == 'new'){
						$sql = " UPDATE boost_website SET `new_speed`= 1 WHERE id = '$id' ";

						$conn->query($sql);
					}
					elseif($speedtype == 'newUrl2'){
						$sql = " UPDATE boost_website SET `url2_new_speed`= 1 WHERE id = '$id' ";

						$conn->query($sql);
					}
					elseif($speedtype == 'newUrl3'){
						$sql = " UPDATE boost_website SET `url3_new_speed`= 1 WHERE id = '$id' ";

						$conn->query($sql);
					}

				}



					}



				}


}

