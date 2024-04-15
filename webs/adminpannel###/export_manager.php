<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

$platform = $_GET['platform'];
 


// $managers = getTableData( $conn , " admin_users " , " userstatus NOT LIKE 'admin' and active_status = 1 " , "order by id desc" , 1 ) ;

if($platform=='All') {

	// $managers = $conn->query("SELECT admin_users.firstname ,admin_users.lastname, admin_users.email, admin_users.country_code, admin_users.phone, admin_users.country as country_name,list_countries.name , admin_users.user_type , MONTHNAME(admin_users.created_at) AS registered_month FROM admin_users LEFT JOIN list_countries on list_countries.id = admin_users.country  where  admin_users.userstatus NOT LIKE 'admin' and admin_users.active_status = 1   order by admin_users.id desc "); 

	$managers = $conn->query(" SELECT admin_users.id , admin_users.firstname ,admin_users.lastname, admin_users.email , admin_users.country_code , admin_users.phone, list_countries.name as country_name , admin_users.user_type , MONTHNAME(admin_users.created_at) AS registered_month FROM admin_users LEFT JOIN list_countries ON list_countries.id = admin_users.country where admin_users.userstatus NOT LIKE 'admin' and admin_users.active_status = 1 ORDER BY admin_users.id DESC ; "); 
}
else{

	 $website = $conn->query("SELECT platform,manager_id from boost_website where  platform = '".$platform."' ");
	 // print_r($website);
	 	$mn = "";
	 	foreach ($website as $keyw => $valuew) {
	 		if($mn!=""){$mn.=",";}
			$mn.=$valuew['manager_id'];
	 	} 

	// $managers = $conn->query("SELECT admin_users.firstname ,admin_users.lastname, admin_users.email, admin_users.country_code, admin_users.phone, admin_users.country as country_name,list_countries.name , admin_users.user_type , MONTHNAME(admin_users.created_at) AS registered_month FROM admin_users LEFT JOIN list_countries on list_countries.id = admin_users.country  where  admin_users.userstatus NOT LIKE 'admin' and admin_users.active_status = 1 and admin_users.id In ($mn)   order by admin_users.id desc "); 

	$managers = $conn->query(" SELECT admin_users.id , admin_users.firstname ,admin_users.lastname, admin_users.email , admin_users.country_code , admin_users.phone, list_countries.name as country_name , admin_users.user_type , MONTHNAME(admin_users.created_at) AS registered_month FROM admin_users LEFT JOIN list_countries ON list_countries.id = admin_users.country where admin_users.userstatus NOT LIKE 'admin' and admin_users.active_status = 1 AND admin_users.id In ($mn) ORDER BY admin_users.id DESC; "); 



}


						$keys = 0;
 								
								    $delimiter = ","; 
								    $filename = "total-data_" . date('Y-m-d') . ".csv"; 
								     
								    // Create a file pointer 
								    $f = fopen('php://memory', 'w'); 	

					    // $fields = array(' First Name',  'Last Name', 'Email','Country Code', 'Phone', 'Country Code','Country Name', 'User Type' , 'Registered Month' ); 

						//  
					    $fields = array(' First Name',  'Last Name', 'Email', 'Phone','Country Name', 'User Type' , 'Registered Month' , 'Platform' , 'WebsiteURLs' , 'Plan Type' , 'Plan Paid' ); 
					    fputcsv($f, $fields, $delimiter); 


						foreach ($managers as $key => $value) {

							$full = strtolower($value["firstname"].' '.$value["lastname"]);

							if(!str_contains($full,"makkpress") ){
 								$keys++;

						      $lineData = ($value); 
						      // fputcsv($f, $lineData, $delimiter); 

						      // if ( !empty($lineData["country_name"]) ) { 
						      // 	# for skipping the empty country name row
						      // 	// fputcsv($f, $lineData, $delimiter); 
						      // }

						      	

						      	// $platforms = $plan_type = $plan_amount = "" ;
						      	$platforms = $website_urls = "" ;
						      	$plan_type = "Unpaid" ;
						      	$plan_amount = 0 ;
						      	$temp_currency = ($lineData["country_code"] == "+91") ? "INR" : "USD" ;

								$platform_query = $conn->query(" SELECT * FROM `boost_website` WHERE `manager_id` = ".$lineData["id"]." "); 

								if ($platform_query->num_rows > 0) {

									// platforms

									$temp_platform = [] ;
									$temp_urls = [] ;
									$platform_array = $platform_query->fetch_all(MYSQLI_ASSOC);

									foreach ($platform_array as $key => $platform_data) {

										if ( !empty($platform_data["platform"]) ) {

											if ( $platform_data["platform"] == 'Custom Website' || $platform_data["platform"] == 'Other' ) {

												$temp_platform[] = empty($platform_data["platform_name"]) ? $platform_data["platform"] : $platform_data["platform_name"] ;

											}
											else {
												$temp_platform[] = $platform_data["platform"] ;
											}

											$temp_urls[] = $platform_data["website_url"] ;
										}

										
									}

									$platforms = implode(",", $temp_platform) ;
									$website_urls = implode(",", $temp_urls) ;


									// plan_type & plan_amount
									foreach ($platform_array as $key => $platform_data) {

										if ( ($platform_data["plan_type"] == "Subscription") &&
											 ( $platform_data["subscription_id"] != 111111 && !empty($platform_data["subscription_id"]) ) ) {

											$plan_type = "Paid" ;

											$us_query = $conn->query("SELECT * FROM `user_subscriptions` WHERE `id` = '".$platform_data["subscription_id"]."' ; ") ;

											if ( $us_query->num_rows > 0 ) {
												$us_data = $us_query->fetch_assoc() ;
												$plan_amount += (float)$us_data["paid_amount"] ;
											}


										}

									}

								}

								$plan_amount = $temp_currency." ".$plan_amount ;

								// print_r($platform_data) ;


								unset($lineData["id"]) ; // remove id
								unset($lineData["country_code"]) ; // remove country_code
								$lineData["platforms"] = $platforms ;  // add platforms
								$lineData["website_urls"] = $website_urls ;  // add platforms
								$lineData["plan_type"] = $plan_type ;  // add plan type
								$lineData["plan_amount"] = $plan_amount ;  // add plan amount
								// echo "<br>lineData : " ; print_r($lineData) ;

								fputcsv($f, $lineData, $delimiter); 


						      	// die("<hr>") ;




						      

						        
							}

						}
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f);
 
		?>