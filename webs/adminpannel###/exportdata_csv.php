<?php
include('config.php');
include('session.php');
require_once('inc/functions.php') ;
    // *************total data pdf download************* 

// Fetch records from database 
$user_id = $_SESSION['user_id'] ;
$query = $conn->query("SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id AND admin_users.id = '$user_id'"); 
 
if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "total-data_" . date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    // Set column headers 
    $fields = array(' NAME',  'EMAIL', 'Payment Method','Paid Amount', 'Start Plan Period', 'End Plan Period','Plan cancelled At', 'Plan Status'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){ 
        // $status = ($row['status'] == 1)?'Active':'Expired'; 
        if($row['is_active']){
				$currentDate = date("d-m-Y h:i:s");
                
        	       if ($currentDate >= $row['plan_period_end'])
									{	
										$status = "Expired";
									}
									else
									{
										$status = "Active";
										
									}

                                }else{
                                    $status = "Expired";
                                }
									
        $lineData = array( $row['first_name'], $row['email'], $row['payment_method'], $row['paid_amount'], $row['plan_period_start'], $row['plan_period_end'],$row['cancled_at'], $status); 
        fputcsv($f, $lineData, $delimiter); 
    } 
     
    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
}
else {

    $_SESSION['error'] = " No data found. " ;
    header("location: ".HOST_URL."adminpannel/billing-dashboard.php");
}

    
exit; 


    // *************end total data pdf download************* 
?>