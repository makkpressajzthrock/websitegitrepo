<?php
	include('config.php');
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
 
		$c1 = trim($_POST['c1']);
		$c2 = trim($_POST['c2']);
		$c3 = trim($_POST['c3']);
		$user_id = $_POST['user_id'];


		$code1 = 0;
		$code2 = 0;
		$code3 = 0;
 
//  Verity 1st coupon
		if($c1!=""){
			if($c1!="verifyed"){
				$fetchCode1 = "SELECT * FROM sumo_code WHERE sumo_code = '$c1' AND used != '1'";
				$fetchResult1 = mysqli_query($conn,$fetchCode1);
				$code1 = mysqli_fetch_assoc($fetchResult1);

				$sumoCode1 = $code1['sumo_code'];

				if ($c1 == $sumoCode1) 
				{
					$code1 = 1;
					// echo "Valid c1";



				}else{
					// echo "In-Valid c1";
					$code1 = 0;
				}
			}else{
				$code1 = 3;
			}

		}else{
			$code1 = 2;	
		}


//  Verity 2nd coupon
		if($c2!=""){
			if($c2!="verifyed"){			
					$fetchCode2 = "SELECT * FROM sumo_code WHERE sumo_code = '$c2' AND used != '1'";
					$fetchResult2 = mysqli_query($conn,$fetchCode2);
					$code2 = mysqli_fetch_assoc($fetchResult2);

					$sumoCode2 = $code2['sumo_code'];

					if ($c2 == $sumoCode2) 
					{
						$code2 = 1;
						// echo "Valid c2";

						


					}else{
						// echo "In-Valid c2";
						$code2 = 0;
					}	
			}else{
				$code2 = 3;
			}

		}else{
			$code2 = 2;	
		}

//  Verity 3rd coupon
		if($c3!=""){
			if($c3!="verifyed"){				
				$fetchCode3 = "SELECT * FROM sumo_code WHERE sumo_code = '$c3' AND used != '1'";
				$fetchResult3 = mysqli_query($conn,$fetchCode3);
				$code3 = mysqli_fetch_assoc($fetchResult3);

				$sumoCode3 = $code3['sumo_code'];

				if ($c3 == $sumoCode3) 
				{
					$code3 = 1;
					// echo "Valid c3";
						

				}else{
					// echo "In-Valid c3";
					$code3 = 0;
				}	
			}else{
				$code3 = 3;
			}

		}else{
			$code3 = 2;	
		}




		// $query = "INSERT INTO admin_users (firstname,lastname,email,phone,password,userstatus,status,
		// 			created_at,updated_at,address_line_1,address_line_2,city,state,zipcode,country,token,sumo_code,platform,user_type,country_code,help_pass) VALUES ('$fname','$lname','$email','$phone','$password','manager','1','$created_at','$created_at','','','','','','','','$sumocode','','AppSumo','$country_code','$help_pass')";
		// $execute = mysqli_query($conn,$query);

		if($code1 !=0 && $code2 !=0 && $code3 !=0)
		{
			// echo "Adding";

			$appluCodeCount = 0;

			$choosePlan = 0;
			$upgrade = 0;

				if($code1 ==1)
				{ 

					if($appluCodeCount == 0){	
						$appluCodeCount++;
						$update1 = "UPDATE sumo_code set used = 1 WHERE sumo_code = '$c1' AND used = '0'";
						mysqli_query($conn,$update1);				
						
						$update1 = "UPDATE admin_users set flow_step = 2 , sumo_code = '$c1' WHERE id = '$user_id'";
						mysqli_query($conn,$update1);		
						$choosePlan++;	
						$upgrade = 1;
					}
			 	


				}elseif($code1 ==3){
					$choosePlan++;
				}

				if($code2 ==1)
				{
 					if($appluCodeCount == 0){	
						$appluCodeCount++;

						$update2 = "UPDATE sumo_code set used = 1 WHERE sumo_code = '$c2' AND used = '0'";
						mysqli_query($conn,$update2);				
						
						$update2 = "UPDATE admin_users set flow_step = 2 , sumo_code_2 = '$c2' WHERE id = '$user_id'";
						mysqli_query($conn,$update2);
						$choosePlan++;
						$upgrade = 1;

					}
	 
					
				}elseif($code2 ==3){
					$choosePlan++;
				}			

				if($code3 ==1)
				{

 					if($appluCodeCount == 0){	
						$appluCodeCount++;

						$update3 = "UPDATE sumo_code set used = 1 WHERE sumo_code = '$c3' AND used = '0'";
						mysqli_query($conn,$update3);				
						
						$update3 = "UPDATE admin_users set flow_step = 2 , sumo_code_3 = '$c3' WHERE id = '$user_id'";
						mysqli_query($conn,$update3);
						$choosePlan++;
						$upgrade = 1;
					}	 
					
				}elseif($code3 ==3){
					$choosePlan++;
				}	

 


			if($choosePlan>0 && $upgrade == 1){	

				$plan_id= 0;

				if($choosePlan ==1)
				{
					$plan_id = 27; // Power Plan ID
				}
				elseif($choosePlan ==2){
					$plan_id = 14; // Booster Plan ID
				}
				elseif($choosePlan ==3){
 					$plan_id = 28; // Super Plan ID
				}
				

				// echo "plan Id= ".$plan_id;

				$get_flow = $conn->query(" SELECT * FROM `boost_website` WHERE manager_id = '$user_id' limit 1 ");
				$d = $get_flow->fetch_assoc();

				$get_user = $conn->query(" SELECT id,email FROM `admin_users` WHERE id = '$user_id'");
				$user = $get_user->fetch_assoc();

				$email = $user['email'];
 				$get_subs = $conn->query(" SELECT * FROM `user_subscriptions` WHERE user_id = '$user_id' limit 1 ");
			 
				

				if($get_subs->num_rows >0){
					$c_subs = $get_subs->fetch_assoc();

					$subs_id = $c_subs['id'];

					// echo "Update Subscription";

 					 $sql3 = " UPDATE user_subscriptions SET  plan_id='$plan_id' where id = '$subs_id'" ;
		   			$conn->query($sql3);


					$web_id =  $d['id'];

 					$sql3 = " UPDATE boost_website SET  plan_id='$plan_id' where id = '$web_id'" ;
		   			$conn->query($sql3); 

		   			echo 11;
		   			$_SESSION['success'] = "Plan Updraded Successfully." ;


				}
				else{
					 

				 $plan_period_end =Date('Y-m-d', strtotime('+18250 days'));	

				 $queryL = "INSERT INTO user_subscriptions (user_id,plan_id,payment_method,stripe_subscription_id,stripe_customer_id,stripe_payment_intent_id,paid_amount,
					 total_tax,plan_price,paid_amount_currency,site_count,plan_interval,plan_interval_count,payer_email,created,plan_period_start,plan_period_end,status) VALUES ('$user_id','$plan_id','stripe','xxxxxxxxxxxx','xxxxxxxxxxx','xxxxxxxxxxxxx','0','0','0','USD','1','Lifetime','1','$email',now(),now(),'$plan_period_end','succeeded')"; 	

 					$execute = mysqli_query($conn,$queryL);
 					$last_id = mysqli_insert_id($conn);

					sleep(1);

					$web_id =  $d['id'];

 					$sql3 = " UPDATE boost_website SET subscription_id = '$last_id' , plan_id='$plan_id', plan_type = 'Subscription' where id = '$web_id'" ;
		   			 $conn->query($sql3); 

		   			 echo 11;
		   			 $_SESSION['success'] = "Plan activated Successfully." ;
				}

			} 



		}
		else{
			echo json_encode(["c1" => $code1 , "c2" => $code2 , "c3" => $code3  ]);
		}



?>