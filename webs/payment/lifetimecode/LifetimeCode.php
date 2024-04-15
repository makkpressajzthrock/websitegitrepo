<?php

	require_once("../../adminpannel/config.php") ;
	require_once('../../adminpannel/inc/functions.php');
 
  $userID = $_SESSION['user_id'];



	if (isset($_POST['coupon_id']) &&  $_POST['coupon_id'] !="" && $userID != "") 
	{	
		$subscr_plan_id = $_POST['subscr_plan_id'];
		$customer_name = $_POST['customer_name'];
		$customer_email = $_POST['customer_email'];
		$change_id = $_POST['change_id'];
		$sid_id = $_POST['sid_id'];
		$coupon_code = $_POST['coupon_code'];
		$coupon_id = $_POST['coupon_id'];


		$fetchCode = "SELECT * FROM coupons WHERE id = '$coupon_id' AND code ='$coupon_code' AND number_of_uses = '0' AND type ='Lifetime' And strip_coupon_id='No' ";
		// $fetchCode = "SELECT * FROM coupons";
		$fetchResult = mysqli_query($conn,$fetchCode);
		$fetchResultAll = mysqli_query($conn,$fetchCode);

		$num_rows = mysqli_num_rows($fetchResult);
 
		if ($num_rows > 0 ) 
		{

            // Start old subscription cancelled
            $os_query = $conn->query("SELECT id , plan_id , plan_type , subscription_id FROM `boost_website` WHERE `manager_id` = '$userID' AND id = '$sid_id' AND plan_type LIKE 'Subscription';") ;
            if ( $os_query->num_rows > 0 ) {
                $os_data = $os_query->fetch_assoc() ;
                if ( !empty($os_data["plan_id"]) && ( $os_data["plan_id"] != 999 || $os_data["plan_id"] != '999' ) ) {
                    // cancel the old active subscription
                    $cos_query = $conn->query(" UPDATE user_subscriptions SET is_active = 0 , cancled_at = NOW() WHERE id = '".$os_data["subscription_id"]."' ; ") ;
                }
            }
            // End old subscription cancelled


                $rowData = mysqli_fetch_assoc($fetchResultAll);

                if($rowData['status']==2 && $rowData['uses_per_customer']!= 'unlimited'){  
                    echo 2; 
                    exit;
                }	

			    /*** start get plan views ***/
			    $requested_views = 0 ;
			    $pv_query = $conn->query(" SELECT page_view FROM `plans` WHERE id = '".$subscr_plan_id."' ; ");
			    if ($pv_query->num_rows > 0) {
			        $pv_data = $pv_query->fetch_assoc() ;
			        $requested_views = $pv_data["page_view"] ;
			    }
			    /*** end get plan views ***/

				 $plan_period_end =Date('Y-m-d', strtotime('+18250 days'));
				 $queryL = "INSERT INTO user_subscriptions (user_id,plan_id,payment_method,stripe_subscription_id,stripe_customer_id,stripe_payment_intent_id,paid_amount, total_tax,plan_price,paid_amount_currency,site_count,plan_interval,plan_interval_count,payer_email,created,plan_period_start,plan_period_end,status,discount_code_id,requested_views) VALUES ('$userID','$subscr_plan_id','stripe','xxxxxxxxxxxx','xxxxxxxxxxx','xxxxxxxxxxxxx','0','0','0','USD','1','Lifetime','1','$customer_email',now(),now(),'$plan_period_end','succeeded','$coupon_code' , '$requested_views')"; 	

 					$execute = mysqli_query($conn,$queryL);
 					$last_id = mysqli_insert_id($conn);
 					sleep(1);


					$getsite = "SELECT * FROM boost_website WHERE id = '$sid_id' AND manager_id ='$userID' And plan_type = 'Free' ";
					$siteResult = mysqli_query($conn,$getsite);
 					 

 					if($site = mysqli_fetch_assoc($siteResult)){
	 					$sql5 = " UPDATE user_subscriptions_free SET status = '0' where id = '".$site['subscription_id']."' and user_id = '$userID'" ;
			   			 $conn->query($sql5);  						
 					}

 					 					
 					$sql3 = " UPDATE boost_website SET subscription_id = '$last_id' , plan_id='$subscr_plan_id', plan_type = 'Subscription' where id = '$sid_id'" ;
		   			 $conn->query($sql3); 


					 $fetchCodeC = "SELECT id,code FROM coupons WHERE id='$coupon_id' ";
					$fetchResultC = mysqli_query($conn,$fetchCodeC);
			 
 					if($prevRow1C = mysqli_fetch_assoc($fetchResultC)){

                      $coupon_code = $prevRow1C['code'];
 					 $sql4 = " UPDATE coupons SET status = 2 where code='$coupon_code' " ;
		   			 $conn->query($sql4); 

					}


 					$sql4 = " UPDATE coupons SET number_of_uses = number_of_uses+1 where id = '$coupon_id'" ;
		   			 $conn->query($sql4); 


 					$sql6 = " UPDATE admin_users SET flow_step = '2' where id = '$userID'" ;
		   			 $conn->query($sql6); 


		   			 $_SESSION['success_'] ="Hurray! Subscription activated successfully.";
		   			 echo 1;

		}else{
				echo 2;
		}


	}

	else{
		echo 2;
	}

?>