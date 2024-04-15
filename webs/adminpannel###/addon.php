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
			$project_data1 = getTableData( $conn , " boost_website " , " id = '$manager_id' " ) ;
			if($_GET['project']==null || count($project_data1)<=0 ){
	
		$_SESSION['error'] = "Requested URL is not valid!";
	
	header("location: ".HOST_URL."adminpannel/dashboard.php");
	die();
	
	}
	
	
	
	
	?>
<?php require_once("inc/style-and-script.php") ; ?>
<!-- code start for update url  -->
<?php

if(isset($_POST['submit_btnss'])) {
	// echo "<pre>";
	// print_r($_POST) ;

	$user_id = $_SESSION['user_id'] ;
	$project_id = base64_decode($_GET['project']) ;


	foreach ($_POST['url_priority'] as $key => $url_priority) {

		$website_name = $_POST['additional_website_name'][$key] ;
		$website_url = $_POST['additional_website_url'][$key] ;

		$sql = " UPDATE additional_websites SET website_name='$website_name' , website_url='$website_url' WHERE manager_id='$user_id' AND website_id='$project_id' AND url_priority = '$url_priority' " ;

		$conn->query($sql) ;
	}

	header("location: ".HOST_URL."adminpannel/addon.php?project=".$_GET['project']) ;
	die() ;
}

	if(isset($_POST['submit_btn'])) {
	
		$project = base64_decode($_GET['project']) ;
	
		// echo '<pre>';print_r($_POST);die();
	
		$data = escape_string($conn,$_POST) ;
	
		$website_name_1 = $_POST['website_name_1'];
		$additional_website_name = $_POST['additional_website_name'];
		$additional_website_url = $_POST['additional_website_url'];
		$current_additional_website_id = $_POST['additional_web_id'];
		$deleted_fields = $_POST['deleted_fields'];
	
		if ( $website_name_1 == null || empty($website_name_1) ) {
			$_SESSION['error'] = "Please fill all required values." ;
		}
		else {
	
			$website_data = getTableData( $conn , " boost_website " , " id = '$project' " ) ;
			$website_url = $website_data["website_url"] ;
	
			// domain check condition ==================================
			$flag = 0 ;
			$website = parse_url($website_url) ;
			// print_r($website) ;
	
			$website_origin = $website["scheme"]."://".$website["host"] ;
	
			foreach ($additional_website_url as $key => $addi_url) 
			{
				$addi_url = parse_url($addi_url) ;
				$addi_origin = $addi_url["scheme"]."://".$addi_url["host"] ;
	
				if ( $website_origin != $addi_origin ) {
					$flag = 1 ;
					// echo 'addi_origin '.$addi_origin ;
					// echo 'website_origin '.$website_origin ;
					// die();
				}
			}
	
			if ( $flag == 1 ) {
				$_SESSION['error'] = "Invalid site domain for additional urls." ;
			}
			else 
			{
	
				updateTableData( $conn , " boost_website " , " website_name = '$website_name_1' " , " id = '$project' " ) ;
	
				// rest process
				foreach($current_additional_website_id as $website_id_key => $website_id_val) {
	
					if($website_id_val != 'new'){
	
						$sql = 'UPDATE additional_websites set website_name="'.$additional_website_name[$website_id_key].'", website_url= "'.$additional_website_url[$website_id_key].'" where id = '.$website_id_val;
					}
					else {
	
						$sql = "INSERT INTO additional_websites (manager_id, website_name, website_id , website_url) VALUES('$user_id', '".$additional_website_name[$website_id_key]."', '$project', '".$additional_website_url[$website_id_key]."')";
					}
					
					if ( $conn->query($sql) === TRUE ) {
						$query_completed = true;
							if(!empty( mysqli_insert_id($conn))){
								$additional = mysqli_insert_id($conn);
							}else{
								$additional =$website_id_val;
							}
	
							$data = google_page_speed_insight($additional_website_url[$website_id_key], "desktop");
	
							if (is_array($data)) {
								$lighthouseResult = $data["lighthouseResult"];
								$requestedUrl = $lighthouseResult["requestedUrl"];
								$finalUrl = $lighthouseResult["finalUrl"];
								$userAgent = $lighthouseResult["userAgent"];
								$fetchTime = $lighthouseResult["fetchTime"];
								$environment = $conn->real_escape_string(serialize($lighthouseResult["environment"]));
								$runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"]));
								$configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"]));
								$audits = $conn->real_escape_string(serialize($lighthouseResult["audits"]));
								$categories = $conn->real_escape_string(serialize($lighthouseResult["categories"]));
								$categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"]));
								$i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"]));
	
	
								// mobile details
								$mobile_data = google_page_speed_insight($additional_website_url[$website_id_key], "mobile");
	
								if (is_array($mobile_data)) {
									$mobile_lighthouseResult = $mobile_data["lighthouseResult"];
	
									$mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"]));
									$mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"]));
									$mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"]));
									$mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"]));
									$mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"]));
									$mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"]));
									$mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"]));
								} else {
									$mobile_lighthouseResult = $mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n = null;
								}
	
	
								if ($additional) {
									$sql = " INSERT INTO pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$additional' , '$project' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) ";
									// echo "sql ".$sql."<br>";    
									if ($conn->query($sql) == true) {
										// echo "success";
	
									}
								}
							}
					}
					else {
						$query_completed = false;
					}
				}
	
				if(!empty($deleted_fields)){
	
					$deleted_websites = explode(',',$deleted_fields);
	
					foreach($deleted_websites as $deletedid){
						$conn->query("DELETE FROM additional_websites WHERE id = '$deletedid'");
					}
				}
	
	
				if ( $query_completed === TRUE ) {
					$_SESSION['success'] = "Updated Successfully" ;
				}
				else {
					$_SESSION['error'] = "Operation Failed." ;
				}
			}
		}
	
		header("location: ".HOST_URL."adminpannel/addon.php?project=".base64_encode($project)) ;
		die();
	
	}
	
	?>
<!-- code end for update url  -->
<style>
	.loader {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100vh;
	background: #00000066;
	color: white;
	font-size: 22px;
	z-index: 4;
	}
</style>
</head>
<body class="custom-tabel">
	<div class="loader" style="display:none"></div>
	<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>
		<!-- Page content wrapper-->
		<div id="page-content-wrapper">
			<?php require_once("inc/topbar.php"); ?>
			<!-- Page content-->
			<div class="container-fluid content__up speed_warranty addon_s">
				<h1 class="mt-4">Buy More URL</h1>
				<div class="profile_tabs">
					<div class="alert-status">
						<?php require_once("inc/alert-status.php") ; ?>
					</div>
					<!-- For show list of url -->
					<?php
						$row_data = getTableData( $conn , "boost_website " , "manager_id ='".$_SESSION['user_id']."' AND id = '$manager_id'" ) ;
						?>
					<form id="add-website-form" method="post" class="addon_b_form">
						<div class="form-group_S" >
							<div class="form-group">
								<label>Add Website Name 1</label>
								<input type="text" class="form-control" id="website_name" name="website_name_1" placeholder="website Name" value="<?=$row_data['website_name']?>"readonly >
							</div>
							<div class="form-group">
								<label>Add Website URL 1</label>
								<input type="url" class="form-control" id="website-url" value="<?=$row_data['website_url']?>" placeholder="https://abc.com" readonly>
							</div>
							<?php
								if ( $row_data['platform'] == "Shopify" ) {
									?>
							<div class="form-group shopify-domain-input">
								<label>Add shopify domain URL</label>
								<input type="url" class="form-control" id="shopify-url" placeholder="https://abc.myshopify.com" value="<?=$row_data['shopify_url']?>" readonly >
							</div>
							<?php
								}
								?>
						</div>
					<div id="new_website" class="new_web_add">
							<?php 
								// while($row = mysqli_fetch_array($result_additional_url)

								$user_id = $_SESSION['user_id'] ;
								$project_id = base64_decode ($_GET['project']) ;
								
								$sql_additional_url = "SELECT * FROM additional_websites WHERE manager_id = '".$user_id."' AND website_id= '".$project_id."'";

								// echo "sql_additional_url : ".$sql_additional_url ;
								
								$result_additional_url = $conn->query($sql_additional_url);
								$rowss = mysqli_num_rows($result_additional_url);
								
								// die;

								if($rowss > 0)
								{ 
									while($row=mysqli_fetch_array($result_additional_url))
									{
										$readonly = empty($row["website_name"]) ? "" : "readonly" ;
									 	?>
										<div class="additional_websites">
											<input type="hidden" value="<?=$row['url_priority']?>" name="url_priority[]" readonly>

											<div class="form-group">
												<label>Add Website Name <?=$row['url_priority']?></label>
												<input type="text" class="form-control additonal-url-names" value="<?php echo $row['website_name']  ?>" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" <?=$readonly?>>

											</div>
											<div class="form-group">
												<label>Add Website URL <?=$row['url_priority']?></label>
												<input type="url" class="form-control additonal-url-links" value="<?php echo $row['website_url']  ?>" id="website_url" name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" <?=$readonly?>>
												<small>(eg. https://abc.com , http://xyz.com)</small>
											</div>
										</div>
										<?php $indexx++; 
									}
								}
								else 
								{
									// create 4 blank rows
									for ($i=2; $i < 6 ; $i++) { 

										$url_priority = $i ;

										$sql = " INSERT INTO additional_websites ( manager_id , website_id , website_name , website_url , monitoring , flag , subscribed_value , url_priority ) VALUES ( '$user_id' , '$project_id' , '' , '' , 0 , 'true' , 0 , $url_priority ) ; " ;
										$conn->query($sql) ;
									}

									header("Refresh: 0");
									die() ;
								}

							?>
							<input type="hidden" name="counter" id="hidden_count" value="<?php echo $indexx; ?>" />
							<?php ?>
							<!-- // After Filling -->
							<?php
								for($j=1; $j < (5-$rowss); $j++){  
									?>
									<!-- <div class="additional_websites">
										<div class="form-group">
											<label>Add Website Name</label>
											<input type="text" class="form-control additonal-namess" id="website_namess" value="" name="additional_website_name[<?php echo $indexx; ?>]" placeholder="Page Name" autocomplete="off" >
										</div>
										<div class="form-group">
											<label>Add Website URL</label>
											<input type="url" class="form-control additonal-urlss" value="" id="website_url"  name="additional_website_url[<?php echo $indexx; ?>]" placeholder="https://abc.com" autocomplete="off" >
											<small>(eg. https://abc.com , http://xyz.com)</small>
										</div>
									</div> -->
									<?php $indexx++; 
								} 

							?>

							<input type="hidden" name="counter" id="hidden_count" value="<?php echo $indexx; ?>" />
							<?php 
								$sqlAddOn = "SELECT * from addon_site where site_id = '".$manager_id."' AND user_id ='".$_SESSION['user_id']."'";
								$resultAddon = $conn->query($sqlAddOn);
								 $addOnRows = mysqli_num_rows($resultAddon);
								
								 $sqlSubscribedValue = "SELECT * from additional_websites where manager_id = '".$_SESSION['user_id']."' AND website_id= '".$manager_id."' and subscribed_value = 1";
								$resultSubscribedValue = $conn->query($sqlSubscribedValue);
								  $countSubscribedValue = mysqli_num_rows($resultSubscribedValue);
								$sqlCountOn = "SELECT SUM(addon_count) from addon_site where site_id = '".$manager_id."' AND user_id ='".$_SESSION['user_id']."'";
								$resultCountOn = $conn->query($sqlCountOn);
								$fetchCountOn= mysqli_fetch_assoc($resultCountOn);
								// echo "Sum Value";
								$sumValue = $fetchCountOn['SUM(addon_count)'];
								$newValuess = $sumValue+$countSubscribedValue+4;
								$z=1;
								$site_counter = 0;
								// $siteCount = 0;
								if($addOnRows != 0 ){
								while($fetchAddonRows = mysqli_fetch_assoc($resultAddon)){
									echo $siteCount = $fetchAddonRows['addon_count'];
									for($z=1; $z <= ($siteCount-$countSubscribedValue); $z++){  ?>
							<div class="additional_websites">
								<div class="form-group">
									<label>Add Website Name</label>
									<input type="text" class="form-control additonal-namess" id="website_namess" value="" name="additional_website_name[<?php echo $indexx+$z;  ?>]" placeholder="Page Name" autocomplete="off" >
								</div>
								<div class="form-group">
									<label>Add Website URL</label>
									<input type="url" class="form-control additonal-urlss" value="" id="website_url"  name="additional_website_url[<?php echo $indexx+$z;  ?>]" placeholder="https://abc.com" autocomplete="off" >
									<small>(eg. https://abc.com , http://xyz.com)</small>
								</div>
							</div>
							<?php	}  ?>
							<input type="hidden" value="<?php echo $siteCount  ?>" name = "add_on_count[<?php echo $site_counter ?>]" />
							<?php	
								$site_counter++; // die;
								}  ?>
							<input type="hidden" value="subscribe_site_count" name = "subscribe_site_count" />
							<?php
								}
								 ?>
							<input type="hidden" name="counters" id="hidden_count" value="<?php echo $z+$indexx; ?>" />
							<button  class="" name="submit_btnss">Submit</button>
					</div>

					<div class="form_h_submit" style="display: none;">
						<button type="submit" class="btn btn-primary submit__btn" name="submit_btn">Submit</button>
					</div>
					<!-- </div> -->
				</form>
				<!-- code end For show list of url -->
				<div class="plans_wrapper_S">
					<?php
						$sql_old= "SELECT * FROM `addon_site` WHERE status = 'succeeded' AND site_id='".$manager_id."' AND is_active=1 order by id desc limit 0,1 ";
						                  // echo "sql_old ".$sql_old;
						                  $query_old = $conn->query($sql_old) ;
						
						                  if($query_old->num_rows > 0) {
						                  	$user_subscription = $query_old->fetch_assoc();
						                  	 $user_subscription_id=$user_subscription['addon_id'] ; //4
						                  	 $user_subscriptionid=$user_subscription['id'] ;
						                  }
						                            
						
						                  $query = $conn->query(" SELECT * FROM addon WHERE status = 1 ") ;
						
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
									+<?php echo $data['urls'];?> URL's
								</h2>
								<div class="price-tag"><span class="symbol"><?php if($i!=0){?>$<?php }?></span><span class="amount" subs="<?php echo $data['id'];?>"><?php echo $data['price'];  ?></span> <span class="after"><?php if($i!=0){?><span class="month-slash" >/</span><?php echo $data['interval'];  ?><?php }?></span></div>
							</div>
							<ul>
								<li style="display:none"><i class="fa-solid fa-check"></i> <?php echo $data['description'];  ?> warranty</li>
							</ul>
						</div>
						<?php if ($user_subscription_id) { if ($data['id'] != $user_subscription_id) { ?>
						<form method="POST" action="<?php  echo HOST_URL; ?>payment/addon.php?id=<?=base64_encode($data['id'])?>&sid=<?=base64_encode($manager_id)?>">
							<?php } }else{ ?>
						<form method="POST" action="<?php  echo HOST_URL; ?>payment/addon.php?id=<?=base64_encode($data['id'])?>&sid=<?=base64_encode($manager_id)?>">
							<?php  }  if ($user_subscription_id) { if ($data['id'] != $user_subscription_id) {
								// code...
								?>
							<button  <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'disabled'; } ?> data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class=" <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'active_plan'; } ?>  Polaris-Button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
							<span class="Polaris-Button__Content">
							<span class="Polaris-Button__Text">
							Subscribe
							</span>
							</span>
							</button>
							<?php }else{ ?>
						<form method="POST" action="<?php  echo HOST_URL; ?>payment/addon.php?id=<?=base64_encode($data['id'])?>&sid=<?=base64_encode($manager_id)?>">
							<button  <?php if($data['id'] == $user_subscription_id && $status == 'active') ?> data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class=" <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'active_plans'; } ?>  Polaris-Button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
								<span class="Polaris-Button__Content">
									<span class="Polaris-Button__Text">
										<!-- Previous Plan -->
										Subscribe
									</span>
								</span>
							</button>
							<?php  }  } else{ ?>
							<button  <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'disabled'; } ?> data-plan-id="<?php echo $data['id']; ?>" href="<?php echo HOST_URL; ?>shopify_payment.php?plan_id=<?php echo $data['id']; ?>&shop_url=<?php echo $shop_url; ?>" class=" <?php if($data['id'] == $user_subscription_id && $status == 'active'){ echo 'active_plan'; } ?>  Polaris-Button <?php if($data['id'] == $user_subscription_id && $status == 'pending'){ echo 'pending_plan'; } ?>">
							<span class="Polaris-Button__Content">
							<span class="Polaris-Button__Text">
							Subscribe
							</span>
							</span>
							</button>
							<?php }  ?>
						</form>
					</div>
					<?php
						$i++;
						
						}
						
						}
						
						?>
					<!-- <button type="button" class="btn btn-primary cancle_btn">Cancel Addon Plan</button> -->
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		<?php
			$sql_old= "SELECT  SUM(addon_count)  FROM `addon_site` WHERE status = 'succeeded' AND site_id='".base64_decode($_GET['project'])."' and is_active='1'  order by id desc limit 0,1 ";
			                            // echo "sql_old ".$sql_old;
			                            $query_old = $conn->query($sql_old) ;
			                            // if($query_old->num_rows > 0) {
			                            // 	$user_subscription_id = $query_old->fetch_assoc()['addon_count'] ;
			                            // 	// echo $user_subscription_id;
			                            // 	
			                            // 	console.log("hi");
			                            // 	<?php
				// }
				while($row = mysqli_fetch_array($query_old)){
				
				$user_subscription_id =$row['SUM(addon_count)'];
				// echo " Total cost: ". $row['SUM(addon_count)'];
				// echo "<br>";
				}
				?>
		
				var i = <?=$total_additional_websites?>+1;
				
		// console.log(5<?php if(isset($user_subscription_id)){echo "+".$user_subscription_id;}?>);
				if( i >= 5<?php if(isset($user_subscription_id)){echo "+".$user_subscription_id;}?>){
		
					$('#additonal_website').hide();
		
				}
		
				function btn_check(id=''){
		
					$('#additonal_website').show();
		
						i--;
		
						if(id != ''){
		
					if($('#deleted_fields').val().length <= 0){
		
					$('#deleted_fields').append(id);
		
					}else{
						
						$('#deleted_fields').append(','+id);				
					}	
						}
		
		
				}
		
				
			// $('.submit__btn').click(function() {
		
			// });
		
					$(".update_url_plan").click(function(){
		    var c = $(this).prev().val();
		    var p = $(this).attr('data-plan-id');
		    // alert(c+","+p);
		    window.location.href="<?=HOST_URL?>payment/addon_update_subscription.php?pl="+p+"&cs="+c+"&sid=<?=$user_subscriptionid?>&project=<?=base64_encode($manager_id)?>";
		
		
		});
			$(".cancle_btn").click(function(){
		   
		    // alert(c+","+p);
		    window.location.href="<?=HOST_URL?>payment/cancle_addon_plan.php?sid=<?=$user_subscriptionid?>&project=<?=base64_encode($manager_id)?>";
		
		
		});
				
	</script>
	<script>
		$(document).ready(function(){
			if($('.additional_websites').length<1){
				$('.form_h_submit').hide();
			}
		
			$('.active_plans').parent('form').parent('.Polaris-Card ').find('.top-sec-card').prepend('<p class="pre_plan">Previous Plan</p>')
		
		
		
		$("#add-website-form").submit(function(e){
		
			var valid = true ;
			$(".additonal-url-links , .additonal-url-names , #website_name").removeClass("invalid") ;
		
			//  ============================================
			// e.preventDefault() ;
		
			// console.log("call") ;
		
			var website_name = $("#website_name").val() ;
			if ( website_name == undefined || website_name == '' || website_name == null ) {
				$("#website_name").addClass("invalid") ;
				valid = false;
			}
		
			var website = $("#website-url").val() ;
			var website_parse = new URL(website) ;
			var website_origin = website_parse.origin ;
		
			// console.log("website_origin : "+website_origin) ;
			// console.log($(".additonal-url-links").length) ;
		
			$(".additonal-url-names").each(function(i,o) {
				var name = $(o).val();
				if ( name == "" || name == null || name == undefined ) {
					// valid = false ;
					// $(o).addClass("invalid") ;
				}
			});
		
		
			var f = 0 ;
			$(".additonal-url-links").each(function(i,o) {
		
				var url = $(o).val();
				if ( url == "" || url == null || url == undefined ) {
					// valid = false ;
					// $(o).addClass("invalid") ;
				}
				else 
				{
					url = new URL(url) ;
					var url_origin = url.origin ;
					// console.log("url_origin : "+url_origin) ;
					if ( website_origin != url_origin ) {
						$(o).addClass("invalid") ;
						f = 1 ;
						valid = false ;
					}
				}
			});
		
			// if ( f == 1 ) {
			//     $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Invalid site domain for additional urls.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
			// }
		
			
			if ( valid) {
				$(".loader").show().html("<div class='loader_s'>    <img src='.//img/Rounded blocks.gif'>  <p>Please wait getting the data from page insight it will take 5-7 mins maximum.<p></div>");	
			}
			else {
				e.preventDefault() ;
			}
		});

		}) ;
				
	</script>
</body>
</html>