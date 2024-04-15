<?php
include('../config.php');
require_once('../inc/functions.php') ;


// error_reporting(1);error_reporting(E_ALL);

if(isset($_POST['planType'])){
    $user_id = $_POST['user_id'];
    $boostSite = getTableData( $conn , " boost_website " , " manager_id = '".$user_id."' " , "" , 1, "id,manager_id,website_url,plan_id,plan_type,subscription_id,installation" );
    // echo "<pre>";
    // print_r($boostSite); die;
    $table ="<table>
      <thead>
        <tr>
          <th>#</th>          
          <th>Website URL</th>          
          <th>Plan Name</th>
          <th>Site Steps</th>
        </tr>
      </thead>
      <tbody>";
     if(isset($boostSite) ){
        $sno = 0;
        $planName = [
            'Power Plan'  => ['26','27'],
            'Booster Plan'=> ['2','14'],
            'Super Plan' => ['4','15'],
            'Basic Plan' => ['29','30']
        ];
        $dataFound = false;
        
        foreach($boostSite as $row) {
            $sno++;
            $planValue = '';
            if($row['plan_id'] !='999' &&  $row['subscription_id']!='111111' ){
                foreach($planName as $key => $value) {
                    if(in_array($row['plan_id'], $value)) {
                        $planValue = $key;
                        break; // Exit the loop once the plan is found
                    }
                }
                
                $table .="<tr>";
                $table .="<td>".$sno."</td>"; // Fix the concatenation here
                $table .="<td>".$row["website_url"]."</td>";
                $table .="<td>" .(!empty($planValue) ?$planValue : 'NA')."</td>";
                $table .="<td>".$row["installation"]."</td>";
                $table .="</tr>"; // Close the table row properly
                $dataFound = true;

            }
            
           
        }
        
        if(!$dataFound) {
            $table .= "<tr>";
            $table .= "<td colspan='4' style='text-align:center'>No Data Found</td>";     
            $table .= "</tr>"; 
        }
     }else{
        $table .="<tr>";
        $table .="<td colspan='4' style='text-align:center'>No Data Found</td>";     
        $table .="<tr>";
     }
     $table .=" </tbody></table> ";

     echo $table;
}

?>