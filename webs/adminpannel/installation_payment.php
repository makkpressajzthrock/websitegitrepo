<?php
	include('config.php');
	require_once('meta_details.php');
	require_once('inc/functions.php') ;
	if ( empty($_SESSION['user_id']) || empty($_SESSION['role']) ) 
	{
		header("location: ".HOST_URL."adminpannel/");
		die() ;
	}

// *****************Fetching Data from check_installation****************
	$id = base64_decode($_GET['id']);
	// $paymentDetail = "SELECT check_installation.*, boost_website.id, boost_website.website_url as website FROM check_installation INNER JOIN boost_website ON check_installation.website_id = boost_website.id WHERE check_installation.id = '$id'";

	$check_installation_data =getTableData( $conn , " generate_script_request " , " id='".$id."' " ) ;
	$manager=getTableData( $conn , " admin_users " , "id='".$check_installation_data['manager_id']."' " );
$updated =0;

if(isset($_POST['allow_login'])) {

	// echo "login changed";

						 $logon = "UPDATE `admin_users` SET `self_install_team` = 'login' WHERE `id` = '".$manager['id']."'";
						 mysqli_query($conn,$logon);
						 $_SESSION['success'] = "User Updated Successfully.";
						 $updated =1;
							 

	// die;



}





	$boost_website=getTableData( $conn , " boost_website " , "id='".$check_installation_data['website_id']."' " );


	$access_requested =$check_installation_data['access_requested'];
	$optimisation_is_progress = $check_installation_data['optimisation_is_progress'];
	$plan_id = $boost_website['plan_id'];
	$plan = getTableData( $conn , " plans " , "id='".$plan_id."' " );
	$plan_price=$plan['s_price'];
	$plan_name=$plan['name'];
	$managerName = $manager['firstname']." ".$manager['lastname'];
		$stats = " ";
	$status = $check_installation_data['status'];
	if ($status == 1) 
	{
		$stats = "Completed";
	}
	else if ($status == 11) 
	{
		$stats = "Pending";
	}	
	else
	{
		$stats = "Pending";
	}
	$list_countries=getTableData( $conn , " list_countries " , "id='".$check_installation_data['country']."' " );
	$country_name=$list_countries['name'];


	?>
<?php require_once("inc/style-and-script.php") ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up inst_pay">
					<h1 class="mt-4">Installation Payment Details</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="responsive ">
						<div class="back_btn_wrap "><a href="script_installation_payment.php" style="text-decoration:none;"><button type="button" class="back_btn btn btn-primary ">Back</button></a></div>
						<div class="form_h">
						
						<div>
							<label>Manager Name:</label>
							<span><?php echo $managerName;?></span>
						</div>
						<div>
							<label>Website:</label>
							<span><?php echo $check_installation_data['website_url'];?></span>
						</div>
						<div>
							<label>Traffic:</label>
							<span><?php echo $check_installation_data['traffic'];?></span>
						</div>
						<div>
							<label>Platform:</label>
							<span><?php echo $check_installation_data['platform'];?></span>
						</div>
						<div>
							<label>Country:</label>
							<span><?php echo $country_name;?></span>
						</div>
						<div class="custom__settings">
							<label>Script:</label>
							<span><?php  $script=unserialize($check_installation_data['script']);
$domain_url = "https://" . $_SERVER['HTTP_HOST'];
							  foreach ($script as $urlList) { 
				

											?>
											<code>&lt;script <?php
																echo "type='text/javascript'";
																?> src="<?php echo $domain_url . $urlList ?>" &gt;&lt;/script&gt;</code>

											<br>
										<?php 
										} ?>
						</span>
						</div>
						<div class="grid__elem__ip">
							<div>
								<label>Plan Name:</label>
								<span><?php echo $plan_name;?></span>
							</div>
							<div>
								<label>Plan Price:</label>
								<span><?php echo "$".$plan_price;?></span>
							</div>
							
							<?php	
								if($manager['self_install_team'] !="wait"){
							?>
							<div>
							<label>complete between :</label>
							<span><?php         $timedy= $check_installation_data['start_date'];
                                           $vartime = strtotime($timedy);

                                             $datetimecon= date("F d, Y ", $vartime);  echo   $datetimecon ;?> TO <?php $timedy2= $check_installation_data['end_date'];
                                           $vartime2 = strtotime($timedy2);

                                             $datetimecon2= date("F d, Y ", $vartime2);  echo   $datetimecon2 ; ?></span>
							</div>

							<?php	
							 }	
							?>	

							<?php	
								if($manager['self_install_team'] =="wait" && $updated == 0){
							?>

							<div>
								<label>Mark To Choose Plan </label>
								<span>
									<span class="mt-3">
									<input id="checkbox_id_login" class="form-check-input position-static pt-3 mt-4 pr-4 mr-4" type="checkbox" name="checkbox_login" value="0">
									</span>	

									<form method="post">
									 <button id="allow_login" type="submit" class="btn btn-primary ml-4" name="allow_login" disabled>Clicl to Allow</button>
									</form>

								</span>
							</div>							

							<?php	
							 }	
							?>	



						</div>
						<div>
							<label> Status Completed:</label>
							<input id="checkbox_id" class="form-check-input position-static border" type="radio" <?php echo ($status == 1 || $status == 3)?"checked":"";  ?> name="checkbox" value="1">							
							<span id="change_status ml-4" ></span>
						</div>

						<div>
							<label> Access Requested:</label>
							<input id="checkbox_id" class="form-check-input position-static border" type="radio" <?php echo ($access_requested == 1)?"checked":"";  ?> name="access_requested" value="1">							
							<span id="change_status ml-4" ></span>
						</div>

						<div>
							<label> Optimisation is progress:</label>
							<input id="checkbox_id" class="form-check-input position-static border" type="radio" <?php echo ($optimisation_is_progress == 1 )?"checked":"";  ?> name="optimisation_is_progress" value="1">							
							<span id="change_status ml-4" ></span>
						</div>

						<div>
							<label> Access Pending:</label>
							<input id="" class="form-check-input position-static border" type="radio" <?php echo ($status == 11)?"checked":"";  ?> name="checkbox" value="11">							
							<span id="change_status ml-4" ></span>
						</div>

						<div>
							<label> Email Not Sent:</label>
							<input id="" class="form-check-input position-static border" type="radio" <?php echo ($status == 12)?"checked":"";  ?> name="checkbox" value="12">							
							<span id="change_status ml-4" ></span>
						</div>


						<div>
							<label> Client Will Reply Later:</label>
							<input id="" class="form-check-input position-static border" type="radio" <?php echo ($status == 13)?"checked":"";  ?> name="checkbox" value="13">							
							<span id="change_status ml-4" ></span>
						</div>

						<div>
							<label> Wrong Credentials:</label>
							<input id="" class="form-check-input position-static border" type="radio" <?php echo ($status == 14)?"checked":"";  ?> name="checkbox" value="14">							
							<span id="change_status ml-4" ></span>
						</div>

						<div>
							<label> Client Not Replying:</label>
							<input id="" class="form-check-input position-static border" type="radio" <?php echo ($status == 15)?"checked":"";  ?> name="checkbox" value="15">							
							<span id="change_status ml-4" ></span>
						</div>


						<div>
							<label> Not Possible:</label>
							<input id="" class="form-check-input position-static border" type="radio" <?php echo ($status == 16)?"checked":"";  ?> name="checkbox" value="16">							
							<span id="change_status ml-4" ></span>
						</div>						

						<div>

					</div>
					<div>
						<button type="button" class="complete_btn btn btn-primary">Update Status</button></div>
					
                    </div>	
					</div>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">

		$("#checkbox_id_login").change(function(){
				if($("#checkbox_id_login").prop('checked')==true){
					$("#allow_login").attr("disabled",false);
				}else{
					$("#allow_login").attr("disabled",true);
				}

		});


 


			$(".complete_btn").click(function(){
		   var status= $('input[name="checkbox"]:checked').val() ?$('input[name="checkbox"]:checked').val() : 0 ;
		   
		    let access_requested = $('input[name="access_requested"]:checked').val() ? $('input[name="access_requested"]:checked').val() : 0;
		    let optimisation_is_progress= $('input[name="optimisation_is_progress"]:checked').val() ? $('input[name="optimisation_is_progress"]:checked').val() : 0;
		
		 //  console.log(optimisation_is_progress);
		   // if (status!=null) {
		   // 	status=1;
		   // }
      $.ajax({
       url: "installation-status.php",
        type: "POST",
        dataType: "json",
      data: {
     status:status,
	 access_requested : access_requested,
	 optimisation_is_progress : optimisation_is_progress,
     id:'<?=$_GET['id']?>'

        },
      success: function (data) {

          if (data.status == "done") {
			window.location.href = 'script_installation_payment.php';
                    $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    if (status==1) {
                    $("#change_status").html("Completed");

                }else{
                    $("#change_status").html("Pending");

                }
                 $("html, body").animate({ scrollTop: 0 }, "slow");
                }
                else {
                    $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                     $("html, body").animate({ scrollTop: 0 }, "slow");
                }

      }
    });
  });
	</script>
</html>