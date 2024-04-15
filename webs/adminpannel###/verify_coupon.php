<?php
	include('config.php');
		$code = trim($_POST['code']);
		$code_type = trim($_POST['code_type']);
		$user_id = trim($_POST['user_id']);
		$s_id = trim($_POST['s_id']);

		if($code!=""){
 
			 
				$fetchCus = "SELECT * FROM admin_users WHERE id = '$user_id'";
				$fetchResultCus = mysqli_query($conn,$fetchCus);
				$cus = mysqli_fetch_assoc($fetchResultCus);
				$ltd_code_origin = $cus['ltd_code_origin'];

 

				$sumoCodeFlag = 0 ;
				$sumoCode = 0 ;


				if($ltd_code_origin == "DealFuel"){



						$sumoFetchQuery = "SELECT * FROM deal_fuel WHERE code = '$code' AND used != '1'";
						$sumoFetchResult = $conn->query($sumoFetchQuery) ; 

						

						if ( $sumoFetchResult->num_rows > 0 ) {
							$sumoFetchCode = $sumoFetchResult->fetch_assoc() ;
							$sumoCodeFlag = 2 ;
							$sumoCode = $sumoFetchCode['code'] ;
						}



				}
				else{


						$sumoFetchQuery = "SELECT * FROM sumo_code WHERE sumo_code = '$code' AND used != '1'";
						$sumoFetchResult = $conn->query($sumoFetchQuery) ; 

						if ( $sumoFetchResult->num_rows > 0 ) {
							$sumoFetchCode = $sumoFetchResult->fetch_assoc() ;
							$sumoCodeFlag = 1 ;
							$sumoCode = $sumoFetchCode['sumo_code'] ;
						}
						else 
						{
							$dealifyFetchQuery = "SELECT * FROM life_time WHERE sumo_code = '$code' AND used != '1'";

							// echo $dealifyFetchQuery ;
							$dealifyFetchResult = $conn->query($dealifyFetchQuery) ; 



							if ( $dealifyFetchResult->num_rows > 0 ) {

								// print_r($dealifyFetchResult->num_rows) ;
								$dealifyFetchCode = $dealifyFetchResult->fetch_assoc() ;
								$sumoCodeFlag = 0 ;
								$sumoCode = $dealifyFetchCode['sumo_code'] ;
							}
							else {
								$sumoCodeFlag = -1 ;
							}
						}



				}


				// echo "sumoCodeSaved : ".$sumoCodeSaved."<hr>";
				// echo "sumoCodeFlag : ".$sumoCodeFlag."<hr>";


				if ($code == $sumoCode) 
				{
						if ( $sumoCodeFlag == 1 ) {
							$update1 = "UPDATE sumo_code set used = 1 WHERE sumo_code = '$code' AND used = '0'";
						}
						else if ( $sumoCodeFlag == 2 ) {
							$update1 = "UPDATE deal_fuel set used = 1 WHERE code = '$code' AND used = '0'";
						}
						else {
							$update1 = "UPDATE life_time set used = 1 WHERE sumo_code = '$code' AND used = '0'";
						}
						mysqli_query($conn,$update1);

						$update1 = "UPDATE admin_users set flow_step = 2  WHERE id = '$user_id'";
						mysqli_query($conn,$update1);	


					$get_flow = $conn->query(" SELECT * FROM `boost_website` WHERE manager_id = '$user_id' and id='$s_id' limit 1 ");
					$d = $get_flow->fetch_assoc();
					$code1 =  $d['code1'];
					$code2 =  $d['code2'];
					$code3 =  $d['code3'];
					$subscription_id = $d['subscription_id'];

					$appluCodeCount = 0;
					$choosePlan = 0;

					if($code1 !=""){
						$appluCodeCount++;
					}

					if($code2 !=""){
						$appluCodeCount++;
					}
					if($code3 !=""){
						$appluCodeCount++;
					}

					$appluCodeCount ++;

					$plan_id= 0;

					if($appluCodeCount == 1)
					{
						$plan_id = 27; // Power Plan ID
					}
					elseif($appluCodeCount == 2){
						$plan_id = 14; // Booster Plan ID
					}
					elseif($appluCodeCount == 3){
	 					$plan_id = 28; // Super Plan ID
					}


					// echo "Plan=".$plan_id;

	 				$get_subs =$conn->query(" SELECT * FROM `user_subscriptions` WHERE user_id = '$user_id' and id = '$subscription_id'");
						 
	 
					if($get_subs->num_rows >0){
						$c_subs = $get_subs->fetch_assoc();

						$subs_id = $c_subs['id'];


	 					 $sql3 = " UPDATE user_subscriptions SET  plan_id='$plan_id' where id = '$subs_id'" ;
			   			$conn->query($sql3);


						$web_id =  $d['id'];

	 					$sql3 = " UPDATE boost_website SET  plan_id='$plan_id' ,  $code_type = '$code' where id = '$web_id'" ;
			   			$conn->query($sql3); 

			   			echo 11;
			   			$_SESSION['success_'] = "Plan Upgraded Successfully." ;


					}
					else{
					 	$plan_period_end =Date('Y-m-d', strtotime('+18250 days'));	

					   	$queryL = "INSERT INTO user_subscriptions (user_id,plan_id,payment_method,stripe_subscription_id,stripe_customer_id,stripe_payment_intent_id,paid_amount,
						 total_tax,plan_price,paid_amount_currency,site_count,plan_interval,plan_interval_count,payer_email,created,plan_period_start,plan_period_end,status) VALUES ('$user_id','$plan_id','stripe','xxxxxxxxxxxx','xxxxxxxxxxx','xxxxxxxxxxxxx','0','0','0','USD','1','Lifetime','1','$email',now(),now(),'$plan_period_end','succeeded')"; 	

	 					$execute = mysqli_query($conn,$queryL);
	 					$last_id = mysqli_insert_id($conn);

						sleep(1);

						$web_id =  $d['id'];

	 					$sql3 = " UPDATE boost_website SET subscription_id = '$last_id' , plan_id='$plan_id', plan_type = 'Subscription',  $code_type = '$code'  where id = '$web_id'" ;
			   			 $conn->query($sql3); 

			   			 echo 11;
			   			 $_SESSION['success_'] = "Plan activated Successfully." ;
					}



				}
				else{
					echo 0;
				}
			 

		}else{
			echo 2;	
		}



?>