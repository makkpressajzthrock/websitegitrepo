<?php
// error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 
   require_once("config.php") ;
   require_once('functions.php') ;

//    echo "<pre>";
//    print_r($_POST); die;
   if(isset($_POST['boostedSpeed'])){
    function getBoostedSpeedData(){
        global $conn; // Assuming $conn is defined outside this function as the MySQLi connection object
        
        // Ensure $_POST['start'] and $_POST['length'] are set and numeric
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Default records per page
        $searchValue = isset($_POST['selectPlatform']) ? $_POST['selectPlatform'] : "";

        //for-search-value-admin-table
        // $whereClause = "firstname NOT LIKE '%makkpress%' AND lastname NOT LIKE '%makkpress%' AND userstatus NOT LIKE 'admin' AND active_status = 1";
        $whereClause = '';
        if (!empty($searchValue)) {
            $search = mysqli_real_escape_string($conn, $searchValue);
            $whereClause .= " platform LIKE '%$search%' ";
        }

        // Fetch data from your_table using provided conditions and pagination
        $countAll = getTableData($conn, "pagespeed_dummy_report",$whereClause, "ORDER BY id DESC", 1);
        $result = getTableData($conn, "pagespeed_dummy_report",$whereClause, "ORDER BY id DESC LIMIT $start, $length", 1);
        $totalRecord = count($countAll);
        $resultData = [];
        
      
        if (!empty($result)) {
            $i = 1;

            foreach ($result as $key => $value) {
               
                
                $output = []; 
            
                $output[] = $value["platform"];
                $output[] = $value["website_url"];
                $output[] = $value['old_mobile_speed'];
                $output[] = $value['old_desktop_speed'];
                $output[] = $value['current_mobile_speed'];
                $output[] = $value['current_desktop_speed'];
               
                $i++;  
                array_push($resultData, $output);
            }
        //   }
        } else {
            $output[]= "No Result Found";
        }
            
        $outputt = [
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $totalRecord,
            "recordsFiltered" => $totalRecord,
            "data" => $resultData
        ];

        echo json_encode($outputt);
        }

      // Call the function
      getBoostedSpeedData();
   }


?>