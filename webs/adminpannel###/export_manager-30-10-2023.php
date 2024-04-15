<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

$platform = $_GET['platform'];
 


// $managers = getTableData( $conn , " admin_users " , " userstatus NOT LIKE 'admin' and active_status = 1 " , "order by id desc" , 1 ) ;

if($platform=='All'){
 $managers = $conn->query("SELECT admin_users.firstname ,admin_users.lastname, admin_users.email, admin_users.country_code, admin_users.phone, admin_users.country as country_name,list_countries.name , admin_users.user_type from admin_users LEFT JOIN list_countries on list_countries.id = admin_users.country  where  admin_users.userstatus NOT LIKE 'admin' and admin_users.active_status = 1   order by admin_users.id desc "); 
 }
else{

	 $website = $conn->query("SELECT platform,manager_id from boost_website where  platform = '".$platform."' ");
	 // print_r($website);
	 	$mn = "";
	 	foreach ($website as $keyw => $valuew) {
	 		if($mn!=""){$mn.=",";}
			$mn.=$valuew['manager_id'];
	 	} 

	 $managers = $conn->query("SELECT admin_users.firstname ,admin_users.lastname, admin_users.email, admin_users.country_code, admin_users.phone, admin_users.country as country_name,list_countries.name , admin_users.user_type from admin_users LEFT JOIN list_countries on list_countries.id = admin_users.country  where  admin_users.userstatus NOT LIKE 'admin' and admin_users.active_status = 1 and admin_users.id In ($mn)   order by admin_users.id desc "); 



}


						$keys = 0;
 								
								    $delimiter = ","; 
								    $filename = "total-data_" . date('Y-m-d') . ".csv"; 
								     
								    // Create a file pointer 
								    $f = fopen('php://memory', 'w'); 	

					    $fields = array(' First Name',  'Last Name', 'Email','Country Code', 'Phone', 'Country Code','Country Name', 'User Type'); 
					    fputcsv($f, $fields, $delimiter); 


						foreach ($managers as $key => $value) {

							$full = strtolower($value["firstname"].' '.$value["lastname"]);

							if(!str_contains($full,"makkpress") ){
 								$keys++;

						      $lineData = ($value); 
						        fputcsv($f, $lineData, $delimiter); 
							}

						}
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f);
 
		?>