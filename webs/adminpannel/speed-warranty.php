<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;
$manager_id=base64_decode($_GET['project']);

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>
		<style>
			.loader {
			    background-color: #ffffff5e;
			    height: 100%;
			    position: absolute;
			    text-align: center;
			    margin: auto;
			    display: none;
			    width: 100%;
			}
		</style>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up speed_warranty">

					<h1 class="mt-4">Speed warranty</h1>
					<div class="profile_tabs">
					<div class="alert-status">
						<?php require_once("inc/alert-status.php") ; ?>
					</div>
                    <div class="plans_wrapper_S">   

    <?php

    						$sql_old= "SELECT * FROM `details_warranty_plans` WHERE status = 'succeeded' AND website_id='".$manager_id."' order by id desc limit 0,1 ";
                            // echo "sql_old ".$sql_old;
                            $query_old = $conn->query($sql_old) ;
                            if($query_old->num_rows > 0) {
                            	$user_subscription_id = $query_old->fetch_assoc()['plan_id'] ;
                            	// echo $user_subscription_id;
                            }
                            $sql_old_expire= "SELECT * FROM `details_warranty_plans` WHERE status = 'expire' AND website_id='".$manager_id."' ";
                            // echo "sql_old ".$sql_old;
                            $query_old_expire = $conn->query($sql_old_expire) ;
                            if($query_old_expire->num_rows > 0) {
                            	$subscription_id_expire = $query_old_expire->fetch_assoc() ;
                           $plan_id_expire =$subscription_id_expire['plan_id'];
                           $status_expire =$subscription_id_expire['status'];
                            	// echo $user_subscription_id;
                            }


                            $query = $conn->query(" SELECT * FROM `plans_warranty` WHERE status = 1 ") ;

                            if($query->num_rows > 0) 
                            {
                                $i = 1;
                                while($data = $query->fetch_assoc() ) 
                                {
					               $plan_frequency_interval = $data['interval'];
					                  if($data['id'] ==$user_subscription_id){ $status = 'active'; } 
					                  

                                    ?>

							<div class="Polaris-Card ">
                                        <div class="Polaris-Card__Section">
                                            <div class="top-sec-card">
                                               <h2 class="plan-name">
                                                    <?php echo $data['s_type']; ?>
                                                    <?php 
                                                    if( $data['id'] == $user_subscription_id && $status == 'active'  && $plan_type == "Free"  )
                                                        { echo '<span>Current Free Plan</span>'; }
                                                    elseif($data['id'] == $user_subscription_id && $status == 'active' ){ echo '<span class="c_plan">Current Plan</span>'; } ?> 
                                                </h2>

                                               
                                                 
                                                <?php 
                                                if($data['s_price'] != "") {
                                                    ?>
                                                    <div class="price-tag"><span class="symbol"><?php if($i!=0){?>$<?php }?></span><span class="amount" subs="<?php echo $data['id'];?>"><?php echo $data['s_price'];  ?></span> <span class="after"><?php if($i!=0){?><span class="month-slash" >/</span><?php echo $data['interval'];  ?><?php }?></span></div>
                                                    <?php 
                                                } 
                                                ?>
                                                
                                            </div>
                                          
                                            <ul>
                                                
                                                <li><i class="fa-solid fa-check"></i> <?php echo $data['interval'];  ?> warranty</li>   

                                            </ul>       

                                    
										</div>
								<form method="POST" action="<?php echo HOST_URL; ?>adminpannel/payment-speed-warranty.php?project=<?=$manager_id?>&plan=<?=$data['id']?>">

                                            <button  <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'disabled'; } ?> data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class=" <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'active_plan'; } ?>  Polaris-Button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
                                            <span class="Polaris-Button__Content">
                                            <span class="Polaris-Button__Text"><?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'Pending Plan'; } elseif($data['id'] == $user_subscription_id && $status == 'active'){ echo 'Subscribed'; }
                                            elseif($data['id'] == $plan_id_expire && $status_expire == 'expire' ){ echo 'Expired'; }else { echo 'Pay'; } ?></span>

                                            </span>
                                            </button>
                                        </form>
                                           
							</div>
							    <?php

                                    $i++;

                                }
                                
                            }
                                
                        ?>
                        </div>
			</div>
			</div>
		</div>

	</body>
</html>
