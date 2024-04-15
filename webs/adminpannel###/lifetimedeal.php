<?php 
	include('config.php');
	require_once("inc/functions.php");	
?>
<?php require_once("inc/style-and-script.php") ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			<!-- Page content wrapper-->
			<div id="page-content-wrapper" class="appsuno_container_wrapper">
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
			<div class="glass"></div>
				<div class="appsuno_container lifetimedeal">
				<div class="appsuno_left">
					<div class="logo">
						<img src="../img/signup_logo.png" alt="">
					</div>
					<div class="logo_content">
						<h2>Welcome to Websitespeedy</h2>
						<p>Start writing your blog post, creating topic ideas,
Facebook Ads and more.</p>
<br>
After registration please <a class="bold__link" style="text-transform: capitalize;" href="<?=HOST_URL?>adminpannel/">click here</a> to login.
<br>
<p>Once you log in, you can claim the lifetime deal.</p>

<p>In your Dashboard, click add code Button on top right to activate the deal.</p>
					</div>
				</div>	
				<div class="container-fluid  appsuno_right">
					<div class="alert-status"></div>
					<form class="registerform" method="post">
					   	<div class="text-center mb-10">
					      	<h1 class="text-dark mb-3">Lifetime Access</h1>
					      	<div class="text-gray-400 fw-bold fs-4">Redeem your code and get your lifetime access.</div>
					   	</div>
					   	<!---->
					   	<div class="fv-row mb-10"><label class="form-label fw-bolder text-dark fs-6">First Name<span class="madetory__star">*</span></label><input class="form-control form-control-solid" type="text" placeholder="Enter your frist Name" name="fname" required="" required></div>
					   	<div class="fv-row mb-10"><label class="form-label fw-bolder text-dark fs-6">Last Name<span class="madetory__star">*</span></label><input class="form-control form-control-solid" type="text" placeholder="Enter your last Name" name="lname" required="" required></div>
						<div class="fv-row mb-10">
							<div class="row">
								<div class="col-4">
									<label for="country_code" class="form-label fw-bolder text-dark fs-6">Contact Number</label>
									<div class="for__arrow">
									<select id="country_code" name="country_code" class="form-control form-control-solid" required>
										<option value="">Country Code</option>
										<?php
										        $list_countries1 = getTableData( $conn , " list_countries " , "" , " group by sortname order by name" , 1);
												foreach ($list_countries1 as $key => $country_data) { 
										?>			
												<option value='+<?=$country_data["phonecode"]?>'><?=$country_data["name"]?> +<?=$country_data["phonecode"]?></option>
										<?php
												}
										?>
									</select>
									</div>

								</div>
								<div class="col-8">
								    <label for="phone" class="form-label fw-bolder text-dark fs-6" style="visibility:hidden;opacity:0;">Phone</label>
									<input type="number"  class="form-control form-control-solid" id="phone" placeholder="Enter your phone number" name="phone" required>
								</div>
							</div>
						</div>

					   	
					   	<div class="fv-row mb-10"><label class="form-label fw-bolder text-dark fs-6">Email<span class="madetory__star">*</span></label><input class="form-control form-control-solid" type="email" placeholder="Enter your email" name="email" required="" required></div>
					   	<div class="mb-7 fv-row" data-kt-password-meter="true">
					      	<div class="mb-1">
					         	<label class="form-label fw-bolder text-dark fs-6">Password</label>
					         	<div class="position-relative mb-3"><input class="form-control form-control-solid" type="password" placeholder="Enter your password" name="password" required><span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility"><i class="bi bi-eye-slash fs-2"></i><i class="bi bi-eye fs-2 d-none"></i></span></div>
					         	<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
					            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
					            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
					         	</div>
					      	</div>
					      	<div class="text-muted"><!-- Use 8 or more characters with a mix of letters, numbers &amp; symbols. --></div>
					   	</div>
					   	<div class="fv-row mb-10"><label class="form-label fw-bolder text-dark fs-6">Confirm Password</label><input class="form-control form-control-solid" type="password" placeholder="Confirm your Frist Name" name="confirm-password"required></div>
					   	<div class="fv-row mb-10"><label class="form-label fw-bolder text-dark fs-6">Lifetime Access Code</label><input class="form-control form-control-solid" type="text" placeholder="Enter your code" 
					   		name="coupon-code" required></div>
					   	<!---->
					   	<div class="text-center pb-lg-0 pb-8"><button type="button" id="kt_free_trial_submit" class="btn btn-lg btn-primary fw-bolder formsubmit" name="submit"><span class="indicator-label">Create an Account</span><span class="indicator-progress" style="display: none;">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span></button></div>
					   	<div class="text-center mt-4 pb-lg-0 pb-8"><a class="bold__link text-muted me-3 text-decoration-none" href="<?=HOST_URL?>adminpannel">Already registered? Login Here</a></div>
					</form>							
				</div>

                </div>
			</div>
		</div>
	</body>
	<script>
	$('.formsubmit').click(function(){
        $.ajax({
            type: 'post',
            url: 'SaveLifetimeDeals.php',
            data: $('form').serialize(),
            success: function (response) {
            	console.log("response="+response);
            	if(response == 4) 
            	{
                    $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Invalid Code<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 7)
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Please Fill all the field<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 5)
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Invalid Email<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 3)
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Password And Confirm Password Should Have Same Value<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 2 || response == 1) 
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Error In Registration<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
            	else if(response == 0)
            	{
                    $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">Registered Successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}
                 else if(response == 6) 
            	{
            		$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Email already taken!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
            	}   
            	$(window).scrollTop(0);    	
            }
        });
	});
	</script>
</html>