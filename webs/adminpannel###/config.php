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


require_once("/var/www/html/adminpannel/inc/subscription-plan-check.php") ;

$color_arr = ["#9acd32" , "#cd9d32" , "#cd4032" , "#32cdb8" , "#3237cd" , "#cd32b3" , "#4e4e4e" ] ;


// bunnyCDN urls 
$bunny_css = "//websitespeedycdn.b-cdn.net/speedyweb/css/" ;
$bunny_js = "//websitespeedycdn.b-cdn.net/speedyweb/js/" ;
$bunny_image = "//websitespeedycdn.b-cdn.net/speedyweb/images/" ;
$bunny_video = "//websitespeedycdn.b-cdn.net/speedyweb/video/" ;
// END bunnyCDN urls 



if(isset($_SESSION['adminlogin']) && $_SESSION['adminlogin']!=""){

	if($_SERVER['PHP_SELF'] == "/adminpannel/logins.php"){

	}

	else if($_SERVER['PHP_SELF'] == "/adminpannel/managers.php" 
		|| $_SERVER['PHP_SELF'] == "/adminpannel/unverified-managers.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/home.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/manager-view.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/unverified-manager-view.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/payments.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/view-payments.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/payments.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/script_installation_payment.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/tickets.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/expert-queries.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/admin_ticket_reply" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/analytics.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/payments.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/power-plan.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/payment-api.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/meta.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/edit-meta.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/plans.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/plan-edit.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/plan_delete.php " 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/add-plan.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/tax_rates.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/add-tax.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/edit-taxs.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/discount.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/add-discount.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/edit-discount.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/email_template.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/add-email.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/edit-email.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/app_sumo_code.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/generateCsv.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/delete-code.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/life_time_code.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/generateCsvLife.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/delete-life-code.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/tickets.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/admin_ticket_reply.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/settings.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/delete-customers.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/script_installation_payment.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/installation_payment.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/sumo_onwners.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/life_sumo_onwners.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/viewfaq.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/addfaq.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/faqedit.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/manager_settings_admin.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/change-password-admin.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/url_log.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/email_log.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/managers_test.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/script-days.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/email_manager.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/email-view.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/script_installation_tester.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/create_coupon.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/addcoupon.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/deal_fuel_code.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/wordress-waitlist.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/customer-feedback.php" 
		|| $_SERVER['PHP_SELF'] =="/adminpannel/customer-feedback-view.php" 
		



	)
	{
		 
		if($_SESSION['role']=='manager'){
			$_SESSION['userlogin'] = $_SESSION['user_id']."|".$_SESSION['role'];

			$adminlogin = explode('|', $_SESSION['adminlogin']);

			$_SESSION['user_id'] = $adminlogin[0] ;  
			$_SESSION['role'] = $adminlogin[1];
		}

	}
	else{	

		if($_SESSION['role']=='admin'){
			 
			$adminlogin = explode('|', $_SESSION['userlogin']);
 
			$_SESSION['user_id'] = $adminlogin[0] ;  
			$_SESSION['role'] = $adminlogin[1];
		}
	}
 
	}

        $user_id = $_SESSION['user_id']; 

        $get_flow_con = $conn->query(" SELECT * FROM `admin_users` WHERE id = '$user_id' ");
        $d_con = $get_flow_con->fetch_assoc();

        if($d_con['self_install_team'] == 'wait'){
        	if($_SERVER['PHP_SELF'] != "/thanks-installation.php"){
        		header("location: ".HOST_URL."thanks-installation.php") ;
        	}
        }


        /*** pagespeed_report table condition ***/ 
        if ( $d_con['id'] > 4445 ) {
        	define("PAGESPEED_REPORT_TABLE", "pagespeed_report_part2");
        }
        else {
        	define("PAGESPEED_REPORT_TABLE", "pagespeed_report");
        }

?>