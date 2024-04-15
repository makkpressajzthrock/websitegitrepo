<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include "config.php";
require_once('../adminpannel/inc/functions.php') ;
require_once("../adminpannel/config.php") ;

if ( isset($_POST["subscr_plan"]) ) {
 

    extract($_POST) ;
 
     // echo "Plan=".$subscr_plan;   

        $sql = "SELECT * FROM coupons where code='$coupon_code' and location = '$location' and status <> 0 and deleted_at is Null";
        $result = mysqli_query($conn, $sql);
        $results = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){




                $rowData = mysqli_fetch_assoc($results);

                if($rowData['status']==2 && $rowData['uses_per_customer']!= 'unlimited'){  
                    $output = array('status' => 0 , 'message'=>"invalid Code" );
                    echo json_encode($output) ;  
                    exit;
                }


            
            $coupons = Null;

            $checkcouponfound = 0;
            $ck = 0;
            while ($row = mysqli_fetch_assoc($result)) {

                $ck++;

                 if($row["plan_category_id"]!="9999" &&  $row["plan_category_id"] != "$subscr_plan" ){
                 }
                 else{
                    $checkcouponfound = 1;
                    $coupons = $row;
                 }
               
            }

            if($checkcouponfound == 0){
                    if($ck==0){
                             $output = array('status' => 0 , 'message'=>"invalid Code" ); 
                             echo json_encode($output) ; 
                             exit;                
                    }
                    else{

                            $sqlPlan = "SELECT name FROM plans where id='$subscr_plan'";
                            $resultPlan = mysqli_query($conn, $sqlPlan);
                            $rowPlan = mysqli_fetch_assoc($resultPlan);

                             $output = array('status' => 0 , 'message'=>"This Coupon is not valid." ); 
                             echo json_encode($output) ; 
                             exit;                

                    }
            }


            $expired = 1;

 
                    $start_date = $coupons["start_date"] ;
                    $current_date = date('Y-m-d H:i:s') ;
                    $diff_start = date_diff(date_create($current_date) , date_create($start_date) ) ;

                    if($diff_start->invert !=1)
                    {
                         $output = array('status' => 0 , 'message'=>"invalid Code" ); 
                         echo json_encode($output) ; 
                         exit;
                    } 



            if($coupons['expiry_date'] !="")
            {

                    $expiry_date = $coupons["expiry_date"] ;
                    $current_date = date('Y-m-d H:i:s') ;
                    $diff = date_diff(date_create($current_date) , date_create($expiry_date) ) ;

                    if($diff->invert !=1)
                    {
                        $expired = 0;
                    }                    
                

            }
            else{
               $expired = 0; 
            }

           if($expired == 0){
                $tag = "";
                if($coupons['type']=="Percentage"){
                    $tag = $coupons['discount_amount']."% Off";
                }
                else if($coupons['type']=="Amount"){
                    $c = "$";

                    if($coupons["form_location_value"]=="India"){
                        $c = "₹ ";
                    }

                    $tag = $c.$coupons['discount_amount']." Off";
                }
                else{
                    $tag = $coupons['type'];
                }


                    $output = array('status' => 1 , 'message'=>"Viled Code", 'coupon_id'=> $coupons['strip_coupon_id'], 'type'=> $coupons['type'], 'discount'=> $coupons['discount_amount'],'id'=> $coupons['id'], 'tag'=> $tag, 'duration'=> $coupons['duration']  );

            }
            else{
                $output = array('status' => 0 , 'message'=>"invalid Code" );            
            }

            echo json_encode($output) ; 

        }
        else{
            $output = array('status' => 0 , 'message'=>"invalid Code" );
            echo json_encode($output) ;            
        }


} 

