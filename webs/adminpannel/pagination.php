<?php
 session_start();
//  require_once("../adminpannel/config.php") ;

// error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 
 
include('config.php');
include('session.php');
require_once('inc/functions.php') ;
    if(isset($_POST['adminManagerPage'])){

       function getManagerData(){
        global $conn; // Assuming $conn is defined outside this function as the MySQLi connection object
        
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Default records per page
        $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : ''; // Search value

        //for-search-value-admin-table
        $whereClause = "firstname NOT LIKE '%makkpress%' AND lastname NOT LIKE '%makkpress%' AND userstatus NOT LIKE 'admin'";

        if (!empty($searchValue)) {
            $search = mysqli_real_escape_string($conn, $searchValue);
            $whereClause .= " AND (firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR bw.platform LIKE '%$search%' OR website_url LIKE '%$search%' OR user_type LIKE '%$search%' OR au.created_at LIKE '%$search%')";
        }

        // Fetch data from your_table using provided conditions and pagination
        $countAll = getTableData($conn, "admin_users as au JOIN boost_website as bw ON bw.manager_id = au.id",$whereClause, "ORDER BY au.id DESC", 1);
      //  $result = getTableData($conn, "admin_users",$whereClause, "ORDER BY id DESC LIMIT $start, $length", 1);
        $website_owner = $conn->query("SELECT au.*,bw.* FROM admin_users as au JOIN boost_website as bw ON au.id = bw.manager_id  WHERE au.active_status = 1 AND bw.website_url != '' AND au.phone != '' AND $whereClause GROUP BY email ORDER BY au.id DESC LIMIT $start, $length ");
        if ( $website_owner->num_rows > 0 ) {
            $website_owner_list = $website_owner->fetch_all(MYSQLI_ASSOC) ;
        }
        // echo "<pre>";
        // print_r($website_owner_list);die;

        $totalRecord = count($countAll);
        $resultData = [];
        
        //for-search-value-boost-table
       
        if (!empty($website_owner)) {
            $i = 1;

            foreach ($website_owner_list as $key => $value) {
                $resend_code = getTableData($conn, "user_confirm", "user_id = '".$value["manager_id"]."'");
                $subscription_data= getTableData( $conn , " user_subscriptions" , " is_active = 1 AND (status LIKE 'succeeded' OR status LIKE 'paid') AND user_id= '".$value["manager_id"]."' ", "order by id desc" , 1, "id, user_id,plan_id,paid_amount_currency,status,is_active,cancled_at" ) ;
                
                $freePlanCount = 0;
                $paidPlanCount = 0;
                if(isset($subscription_data)){
                    foreach($subscription_data as $rows){
                        if($rows['plan_id'] == 29 || $rows['plan_id'] == 30){
                            $freePlanCount++;
                        } else {
                            $paidPlanCount++;
                        }
                    }
                }
                
                $active_status = ($value["active_status"] == 1) ? "checked" : "" ;
                $active_email = ($value["email_status"] == 1) ? "checked" : "" ;  

                // Format your data and prepare for output
                $apdf = '';
                if ($value["user_type"] == "AppSumo") {
                    $apdf = "AS";
                } elseif ($value["user_type"] == "Dealify") {
                    $apdf = "DF";
                }else{
                    $apdf =  $resend_code["requests"];
                }

                if($value["user_type"] == "AppSumo")
                    $apdflt =  "App Sumo";
                elseif($value["user_type"] == "Dealify")
                    $apdflt = "Dealify";
                elseif($value["user_type"] == "DealFuel")
                    $apdflt= "DealFuel";
                else if($value["user_type"] == "Lifetime")
                    $apdflt = "Life Time";
                else if($value["user_type"] == "register"){
                        if($value["self_install"] == 'yes'){
                            if($value["self_install_team"] == 'wait'){
                                $apdflt = "Request Sent (Yes)";
                            }
                            else{
                                $apdflt= "Normal Process";
                            }
                        }else{
                            $apdflt= "Normal Process";
                        }            
                }
                
                if($value["installation"]!=""){
                $managerIf =   '<span class="number">'.$value["installation"].'</span> /';
                    if($value["installation"]>3){
                        $yesNo =  "Yes";
                    }else{
                        $yesNo = "No";
                    }
                }

                $viewbtn  = '<button class="btn btn-primary view-count-button" data-user_id="'.$value["manager_id"].'">Views</button> <br>';
                $viewbtn1 = '<div style="margin-top:5px; text-align:center;width: 80%;" >
                                '.$managerIf.' '.$yesNo.'
                            </div>';

                            
                
                $phoneNo  =  '<td class="set__max_width">('.$value["country_code"].') '.$value["phone"].'</td>';

                $platform = $value["platform"];
                if($value["platform_name"]!=""){
                    $platformName = $value["platform_name"];
                } 

                $websiteUrl = '<td>
                                    <a href="'.$value["website_url"].'" target="_blank">'.$value["website_url"].'</a>
                                </td>';

                $new_time = $value['created_at']; //123-new
                // if($utcTime!=""){
                //         $databsetime = strtotime($utcTime);
                //         $new_time = date("Y-m-d H:i:s", strtotime('+330 minutes', $databsetime));
                //         $new_time;
                // }    
                
                $emailActive ='<div class="form-check form-switch">
                                <input class="form-check-input email-status-toggle" type="checkbox" data-owner="'.$value["manager_id"].'"  '.$active_email.' onclick="status_active_email(this);" >
                            </div>';

                $statusActive = '<td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input manager-status-toggle" type="checkbox" data-owner="'.$value["manager_id"].'" '.$active_status.' onclick="status_active(this);" >
                                    </div>
                                    <label class="delete_account btn btn-primary" email="'.$value["email"].'" id="'.$value["manager_id"].'">
                                            <i class="fa fa-trash "></i>
                                    </label>
                                </td>';  
                                
                $action =   '<td class="table__managers__actions">
                                <a title="View" target="_blank" href="'.HOST_URL.'adminpannel/email_manager.php?manager='.base64_encode($value["manager_id"]).'" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">
                                        <g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
                                        <path d="M0 500 l0 -360 500 0 500 0 0 360 0 360 -500 0 -500 0 0 -360z m960 295 c0 -20 -42 -61 -207 -205 -114 -99 -217 -183 -230 -186 -13 -4 -33 -4 -46 0 -13 3 -117 87 -230 186 -163 143 -207 187 -207 206 l0 24 460 0 460 0 0 -25z m-777 -203 c65 -58 115 -108 110 -112 -4 -4 -63 -50 -130 -103 l-123 -97 0 216 c0 169 3 214 13 208 6 -4 65 -55 130 -112z m777 -97 l0 -215 -127 101 c-71 56 -129 103 -131 104 -4 3 248 225 254 225 2 0 4 -97 4 -215z m-485 -131 c46 -8 87 7 145 56 l49 41 145 -116 c118 -93 146 -120 146 -140 l0 -25 -461 0 -460 0 3 27 c2 21 34 51 146 140 l143 113 55 -45 c30 -24 70 -47 89 -51z"></path>
                                        </g>
                                    </svg>
                                </a>
                                <a title="View" href="'.HOST_URL.'adminpannel/manager-view.php?manager='.base64_encode($value["manager_id"]).'" class="btn btn-primary" target="_blank"><svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path></svg></a>
                                
                                <a href="'.HOST_URL.'adminpannel/loginas.php?project='.base64_encode($value["id"]).'&loginas='.base64_encode($value["email"]) .'" class="btn btn-primary" target="_blank">Login</a>									
                            </td>';

                         

                $planType = "<button style='min-width:140px' class='btn btn-primary plan-count-button' data-cus_id='".$value["manager_id"]."'>$freePlanCount Free / $paidPlanCount Paid</button> <br>";
                // $planTypeCount = "<div style='margin-top:5px; text-align:center;width: 80%;'>
                //                             $freePlanCount Free / $paidPlanCount Paid
                //                         </div>";      
                              
                
                $output = []; 
            
                $output[] = $i;
                $output[] = $value["firstname"].' '.$value["lastname"];
                $output[] = $value["email"];
                //$output[] =  $value['plan_type'] == 'Subscription' ? "<span style='display:block; width: 24px; height: 24px; border-radius: 100%; background:green;'></span>" : "<span style='display:block; width: 24px; height: 24px; border-radius: 100%; background: red;'></span>";
                $output[] = $apdf;
                $output[] = $apdflt;
                $output[] = $viewbtn.$viewbtn1 ?:'NA';
                $output[] = $planType;
                $output[] = $phoneNo ?:'NA';
                $output[] = $platform ?: $platformName;
                $output[] = $websiteUrl ?:'NA';
                $output[] = $new_time ?:'NA';
                $output[] = $emailActive ?:'NA';
                $output[] = $statusActive ?:'NA';
                $output[] = $action ?:'NA';

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
      getManagerData();
    }


    //pagination-admin-payment-page
    if(isset($_POST['adminPaymentPage'])){
        function getPaymentData(){
            global $conn; // Assuming $conn is defined outside this function as the MySQLi connection object
            
            // Ensure $_POST['start'] and $_POST['length'] are set and numeric
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Default records per page
            $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : ''; // Search value
            
            
            $countQuery = "SELECT COUNT(*) as total FROM user_subscriptions";
            $countResult = mysqli_query($conn, $countQuery);
            $totalRecord = mysqli_fetch_assoc($countResult)['total'];
    
           
            
            $whereClause = '';
            if (!empty($searchValue)) {
                $whereClause = "WHERE admin_users.firstname LIKE '%$searchValue%' OR admin_users.lastname LIKE '%$searchValue%' OR admin_users.email LIKE '%$searchValue%' OR user_subscriptions.plan_interval LIKE '%$searchValue%' OR user_subscriptions.payment_method LIKE '%$searchValue%'";
            }
            $qry = "SELECT user_subscriptions.*, admin_users.firstname,admin_users.lastname, admin_users.email
            FROM user_subscriptions
            LEFT JOIN admin_users ON user_subscriptions.user_id = admin_users.id  $whereClause ORDER BY user_subscriptions.id DESC
            LIMIT $start, $length";
            $cont_qry = mysqli_query($conn, $qry);
            
            
            $resultData = [];
           if(!empty($cont_qry)){
            $i = 1;

            while ($value = mysqli_fetch_array($cont_qry)) {
                $output = [];

                if($value['plan_interval']=="Lifetime"){
                    $planMethod = 'Lifetime';
                }else{
                    $planMethod = $value['payment_method'];
                }
                 $paidAmount = $value['paid_amount'];
                 $paidAmountCurrency = strtoupper($value['paid_amount_currency']);

                $timedy= $value['plan_period_start'];
                $vartime = strtotime($timedy);
                $planStartDate= date("F d, Y H:i", $vartime);

                    
                $timedy2= $value['plan_period_end'];
                $vartime2 = strtotime($timedy2);
                $planEndDate= date("F d, Y H:i", $vartime2);
         
                $action =   '<td class="button__td">
                                <a href="'.HOST_URL.'adminpannel/view-payments.php?view-details='.base64_encode($run_qry["id"]).'"  title="View" class="btn btn-primary"><svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path></svg></a>
                                <a href="'.HOST_URL.'adminpannel/cron/Invoice.php?viewinvoice='.base64_encode($run_qry["id"]).'" title="Send invoice" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
                                    <path d="M69 851 l-24 -19 -3 -291 -3 -291 46 0 45 0 0 270 0 270 330 0 330 0 0 40 0 40 -349 0 c-328 0 -350 -1 -372 -19z"/>
                                    <path d="M255 698 c-43 -25 -44 -33 -45 -280 0 -236 0 -237 24 -265 l24 -28 161 -3 161 -4 0 46 0 46 -145 0 -145 0 0 150 c0 83 2 150 5 150 3 0 66 -38 141 -85 74 -47 141 -85 147 -85 7 0 73 38 147 85 l135 84 3 -48 c3 -42 9 -53 47 -91 l45 -44 0 163 0 163 -29 29 -29 29 -314 0 c-217 -1 -319 -4 -333 -12z m615 -90 c0 -12 -259 -180 -285 -185 -13 -3 -288 170 -293 185 -3 9 68 12 287 12 226 0 291 -3 291 -12z"/>
                                    <path d="M830 272 l0 -62 -80 0 -80 0 0 -45 0 -45 80 0 80 0 0 -55 c0 -30 3 -55 8 -55 4 0 42 35 85 78 l77 77 -85 85 -85 85 0 -63z"/>
                                    </g>
                                    </svg>
                                </a>
                            </td>';

                $output[] = $value['created']?:'NA';
                $output[] = $value['firstname'] . ' ' . $value['lastname']; // Replace with your desired column names
                $output[] = $value['email']?:'NA'; 
                $output[] = $planMethod?:'NA'; 
                $output[] = $paidAmount." ".$paidAmountCurrency ;
                $output[] = $planStartDate; 
                $output[] = $planEndDate; 
                $output[] =  $action; 
                array_push($resultData, $output);
                $i++;  
            }
        
               
        
            $outputt = [
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => $totalRecord,
                "recordsFiltered" => $totalRecord,
                "data" => $resultData
            ];
         }else {
            $output[]= "No Result Found";
        }
        
            echo json_encode($outputt);
        }
        
        // Call the function
        getPaymentData();
    }
    

    //app-sumo-customer-pagination
    if (isset($_POST['adminAppSumoCustomerPage'])) {
        function getAppSumoCustomerData()
        {
            global $conn;


            
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
            $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

    
            // $countQuery = "SELECT COUNT(*) as total FROM admin_users WHERE sumo_code !=''";
            // $countQuery = "SELECT COUNT(*) as total FROM admin_users WHERE sumo_code !=''";
            $countQuery = "SELECT COUNT(*) as total FROM admin_users WHERE sumo_code !='' AND (admin_users.firstname LIKE '%$searchValue%' OR admin_users.lastname LIKE '%$searchValue%' OR admin_users.email LIKE '%$searchValue%' OR admin_users.phone LIKE '%$searchValue%' OR admin_users.sumo_code LIKE '%$searchValue%') ";

            $countResult = mysqli_query($conn, $countQuery);
            $totalRecord = mysqli_fetch_assoc($countResult)['total'];

            $fetchCode = "SELECT * FROM `admin_users` WHERE sumo_code !=''";

            if (!empty($searchValue)) {
                $fetchCode .= " AND (admin_users.firstname LIKE '%$searchValue%' OR admin_users.lastname LIKE '%$searchValue%' OR admin_users.email LIKE '%$searchValue%' OR admin_users.phone LIKE '%$searchValue%' OR admin_users.sumo_code LIKE '%$searchValue%')";
            }

            $fetchCode .= " ORDER BY id DESC LIMIT $start, $length";
            $fetchResult = mysqli_query($conn, $fetchCode);

    
            $resultData = [];
            $sno = 1;
            while ($num = mysqli_fetch_assoc($fetchResult)) {
                // Assuming getTableData() retrieves data as before
                $manager_site = getTableData($conn, "boost_website", "manager_id = '" . $num["id"] . "'", "", 1);
                $active_status = ($num["active_status"] == 1) ? "checked" : "";
    
                // Constructing output rows
                $output = [];
                $output[] = $sno;
                $output[] = $num['firstname'] . " " . $num['lastname'];
                $output[] = $num['email'];
                $output[] = $num['phone'];
                $output[] = $manager_site[0]["website_url"] . "<br>" . $manager_site[1]["website_url"] . "<br>" . $manager_site[2]["website_url"];
                $output[] = '<td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input manager-status-toggle" type="checkbox" data-owner="' . $num["id"] . '" ' . $active_status . '>
                                </div>
                            </td>';
                $output[] = $num['sumo_code'];
                $output[] = '<td>
                                <a href="' . HOST_URL . 'adminpannel/sumo_onwners_view.php?manager=' . $num["id"] . '" class="btn btn-primary">
                                    <svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path></svg>
                                </a>&nbsp;
                            </td>';
    
                array_push($resultData, $output);
                $sno++;
            }
    
            $outputt = [
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => $totalRecord, // Assuming $sno is the total number of records
                "recordsFiltered" => $totalRecord, // Assuming $sno is the filtered number of records
                "data" => $resultData
            ];
    
            echo json_encode($outputt);
        }
    
        // Call the function
        getAppSumoCustomerData();
    }

    
    //app-sumo-pagination
    if (isset($_POST['adminAppSumoPage'])) {
        function getAppSumoData()
        {
            global $conn;
            
            $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
            $length = isset($_POST['length']) ? intval($_POST['length']) : 10;
            $searchValue = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';

    

            $countQuery = "SELECT COUNT(*) as total FROM sumo_code";
            $countResult = mysqli_query($conn, $countQuery);
            $totalRecord = mysqli_fetch_assoc($countResult)['total'];
            
            $fetchCode = "SELECT * FROM sumo_code";
            if (!empty($searchValue)) {
                $fetchCode .= " WHERE sumo_code LIKE '%$searchValue%' OR used LIKE '%$searchValue%' OR date LIKE '%$searchValue%'";
            }
            
            $fetchCode .= " LIMIT $start, $length";
            $fetchResult = mysqli_query($conn, $fetchCode);
           
            $resultData = [];
            $sno = 1;
            while ($value = mysqli_fetch_assoc($fetchResult)) {
                 // Constructing output rows
                 $output = [];
                 $timedy=$value['date'];
                 $vartime = strtotime($timedy);
                 $datetimecon= date("F d, Y ", $vartime); 
                 
                 $action = '<a data-id="' . $value['id'] . '" href="' . HOST_URL . 'adminpannel/delete-code.php?delete=' . base64_encode($value['id']) . '" class="btn btn-primary remove"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path></svg></a>';

                 $output[] = $sno;
                 $output[] = $value['sumo_code'];
                 $output[] = $datetimecon;
                 $output[] = ($value['used'] == 1) ? "Used" : "Unused";
                 $output[] = $action;
            
                 array_push($resultData, $output);
                 $sno++;
            }
    
            $outputt = [
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => $totalRecord, // Assuming $sno is the total number of records
                "recordsFiltered" => $totalRecord, // Assuming $sno is the filtered number of records
                "data" => $resultData
            ];
    
            echo json_encode($outputt);
        }
    
        // Call the function
        getAppSumoData();
    }
    


