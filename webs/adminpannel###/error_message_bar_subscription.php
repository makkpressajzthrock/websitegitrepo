<?php

	// error_reporting(E_ALL); ini_set('display_errors', 1); ini_set('display_startup_errors', 1); 
	// $site_id  = base64_decode($_GET['project']);
	//  echo $_SESSION['user_id'];die;

	if(isset($_GET['project'])){
		$site_id = base64_decode($_GET['project']);
		$_SESSION['siteId'] = $site_id;
	}
	else{
		$site_id = $_SESSION['siteId'];
	}
    
	$query = $conn->query(" SELECT * FROM `boost_website` WHERE id = '$site_id' ");
	$result= mysqli_fetch_array($query);

	$output =  $conn->query("SELECT user_type FROM `admin_users` WHERE id = '".$result['manager_id']."' ");
	$result1 =  mysqli_fetch_array($output); 

	
	if($result1['user_type']=='AppSumo' || $result1['user_type']== 'Dealify' ||  $result1['user_type']=='DealFuel' ){
		$_SESSION['side_message']='';
	}
	else{

		if ( ($result['subscription_id'] == "1111111111") || ($result['subscription_id'] == 1111111111) ) {

			$site_ids = base64_encode($result["id"]);
			$_SESSION['sid'] = $result["id"];
			$_SESSION['side_message'] = "<p><b>No Plan</b></p><p>Please activate plan first</p><p></p><a class='upgrade_button_loc btn btn-primary' href='/plan.php?sid=".base64_encode($result["id"])."' class='btn btn-primary'>Subscribe Now</a>" ;

		}
		else {

			if($result['plan_type'] =='Subscription') {


				// echo 1 ; die ;

				$sele_free= " SELECT * FROM `user_subscriptions` WHERE `id` ='".$result['subscription_id']."' AND(`status` LIKE 'paid' or `status` LIKE 'succeeded') ";
				$sele_free1 =mysqli_query($conn,$sele_free);
				$sele_run2= mysqli_fetch_array($sele_free1);
				//   echo "<pre>";
				$free_amount = $sele_run2['paid_amount'];
				$planId = $sele_run2['plan_id'];
				$is_canceled = 1;

				//   echo $free_amount; die;
				//   echo "<pre>"; 
				//   print_r($sele_run2); die;

				if(mysqli_num_rows($sele_free1)>0){
					// echo "Free Subs";

					$user_id = $_SESSION["user_id"] ; 
					$free_subs_id = $sele_run2["id"];
					//   echo $user_id;
					//   echo $free_subs_id; die;
					$get_site_id = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' and plan_type = 'Subscription' and subscription_id = '$free_subs_id' ");
					$site_idsall = $get_site_id->fetch_assoc();
					$sid = $site_idsall['id']; //123
					$site_ids = base64_encode($site_idsall['id']);

					$planName = $conn->query("SELECT `id`, `name` from plans WHERE `id` = $planId");
					$planName = mysqli_fetch_array($planName); 
					$planName = isset($planName['name'])?$planName['name']:'';
					// echo "<pre>";
					// print_r($planName['name']); die;



					echo "<style>.close{display:none;}</style>";
					$plan_end_date = $sele_run2["plan_period_end"] ;
					//   echo $plan_end_date;die;
					$current_date = date('Y-m-d H:i:s') ;
					$diff = date_diff(date_create($current_date) , date_create($plan_end_date) ) ;


					$plan_f= $diff->days;
					$expired= $diff->invert;
					$is_canceled = $sele_run2['is_active'];
					$ms = "";


					//   echo "pland end".$plan_end_date;
					//   echo "plan f".$plan_f;
					//   echo "expired".$expired;
					// // //   echo "plan country".$plan_country;
					// 	echo "<pre>";
					// 	print_r($diff);
					//     die;

					// $expired = 1 ;

					if($is_canceled == 0 || empty($is_canceled) ) {
						// is_active = 0

						$session=1;
						$freeTrail = $planName;
						$ms = "Expired";
						if ( $sele_run2["plan_id"] == 29 || $sele_run2["plan_id"] == 30 ) {
							$freeTrail = 'Basic Plan(Free)';  //123
						}
					}
					else {

						// echo "plan_f : ".$plan_f."<br>" ;
						// echo "free_amount : ".$free_amount."<br>" ;
						// echo "expired : ".$expired."<br>" ;

						if($plan_f>=1 && $free_amount==0.00 && $expired == 0 ) {
							$print_s = "";
							if($plan_f>1){
								$print_s = "s";
							}  
							$session = 1;
							$freeTrail = $planName;
							$ms = $plan_f." day$print_s remaining";

							if ( $sele_run2["plan_id"] == 29 || $sele_run2["plan_id"] == 30 ) {
								$session = 1 ;
								$freeTrail = 'Basic Plan(Free)';
								$ms =""; //123
							}					
							// echo 6;
						}
						elseif($plan_f>=1 && $free_amount>0.00  && $expired == 0) {
							$print_s = "s";
							if($plan_f>1 && $plan_f<7){
								$print_s = "s";
								$session = 1;
								$freeTrail = $planName;
							}
							else{
								$freeTrail = $planName;
								$session = 1;
							} 

							$ms = $plan_f." day$print_s remaining";

							if ( $sele_run2["plan_id"] == 29 || $sele_run2["plan_id"] == 30 ) {
								$ms = "";
								$session = 1 ;
								$freeTrail = 'Basic Plan(Free)';
							}	
						}
						else{
							$session=1;
							$freeTrail = $planName;
							$ms = "Expired";
							if ( $sele_run2["plan_id"] == 29 || $sele_run2["plan_id"] == 30 ) {
								$freeTrail = 'Basic Plan(Free)';
							}	
						}
					}


					if($session){
						
						if($plan_country =="" ){
							// $_SESSION['error'] = "Your free plan ".$ms." Please upgrade your plan <a class='upgrade_button_loc btn btn-primary' href='/plan.php?sid=".$_GET['project']."' class='btn btn-primary'>Upgrade Plan</a>" ;
							$_SESSION['sid'] = $sid;
							$_SESSION['side_message'] = "<p><b>$freeTrail</b></p><p>$ms</p><p></p><a class='upgrade_button_loc btn btn-primary' href='/plan.php?sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>" ;
							$_SESSION['upgrade_butt'] = "<a class='upgrade_button_loc btn btn-primary' href='/plan.php?sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>" ;

						}
						else{
							// echo 2; die;
							// $_SESSION['error'] = "Your free plan ".$ms." Please upgrade your plan <a class='upgrade_button_loc btn btn-primary' href='/plan-us.php?sid=".$_GET['project']."' class='btn btn-primary'>Upgrade Plan</a>" ;
							$_SESSION['sid'] = $sid;
							$_SESSION['side_message'] = "<p><b>$freeTrail</b></p> <p>$ms</p><p></p><a class='upgrade_button_loc btn btn-primary' href='/plan-us.php?sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>" ;
							$_SESSION['upgrade_butt'] = "<a class='upgrade_button_loc btn btn-primary' href='/plan-us.php?sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>" ;

						}
					}
					else{
						// echo 3;die;
						$_SESSION['side_message'] ='';
					}

				}


				//    exit;
			}
			else{
				// echo 2;die;
				$sele_free= " SELECT * FROM `user_subscriptions_free` WHERE `user_id` ='".$_SESSION['user_id']."' AND `status` LIKE '1'";
				$sele_free1 =mysqli_query($conn,$sele_free);
			   $sele_run2= mysqli_fetch_array($sele_free1);
		   
			   if(mysqli_num_rows($sele_free1)>0){
				   // echo "Free Subs";
		   
				   $user_id = $_SESSION["user_id"] ; 
				   $free_subs_id = $sele_run2["id"];
				   $get_site_id = $conn->query(" SELECT id FROM `boost_website` WHERE manager_id = '$user_id' and plan_type = 'Free' and subscription_id = '$free_subs_id' ");
				   $site_idsall = $get_site_id->fetch_assoc();
				   $site_ids = base64_encode($site_idsall['id']);
		   
		   
			   echo "<style>.close{display:none;}</style>";
			   $plan_end_date = $sele_run2["plan_end_date"] ;
			   $current_date = date('Y-m-d H:i:s') ;
			   $diff = date_diff(date_create($current_date) , date_create($plan_end_date) ) ;
			   $plan_f= $diff->days;
			   $expired= $diff->invert;
			   $ms = "";
			   if($plan_f>=1 && $expired == 0 ){
		   
				   $print_s = "";
				   if($plan_f>1){
					   $print_s = "s";
		   
				   }
		   
				   $ms = $plan_f." day$print_s remaining";
			   }
			   else{
				   $ms = "Expired";
			   }
		   
				   if($plan_country ==""){
				   // $_SESSION['error'] = "Your free plan ".$ms." Please upgrade your plan <a class='upgrade_button_loc btn btn-primary' href='/plan.php?sid=".$_GET['project']."' class='btn btn-primary'>Upgrade Plan</a>" ;
				   $_SESSION['sid'] = $sid;
				   $_SESSION['side_message'] = "<p><b>$planName</b></p> <p>$ms</p><p></p><a class='upgrade_button_loc btn btn-primary' href='/plan.php?sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>" ;
				   $_SESSION['upgrade_butt'] = "<a class='upgrade_button_loc btn btn-primary' href='/plan.php?sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>" ;

				   }
				   else{
				   // $_SESSION['error'] = "Your free plan ".$ms." Please upgrade your plan <a class='upgrade_button_loc btn btn-primary' href='/plan-us.php?sid=".$_GET['project']."' class='btn btn-primary'>Upgrade Plan</a>" ;
				   $_SESSION['sid'] = $sid;
				   $_SESSION['side_message'] = "<p><b>$planName</b></p> <p>$ms</p><p></p><a class='upgrade_button_loc btn btn-primary' href='/plan-us.php?sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>" ;
				   $_SESSION['upgrade_butt'] = "<a class='upgrade_button_loc btn btn-primary' href='/plan-us.php?sid=".$site_ids."' class='btn btn-primary'>Upgrade Plan</a>" ;

				   }
			   
				}
			}

		}

	}

	// echo "<pre>";
	// print_r($result); die;

		$_SESSION['success'] = "Need assistance with Website Speed? Schedule a 15-minute meeting with one of our experts. <a href ='https://makkpress.trafft.com/booking?service=27' target='_blank' class='btn btn-primary'>Book an appointment</a> " ;


 

?>