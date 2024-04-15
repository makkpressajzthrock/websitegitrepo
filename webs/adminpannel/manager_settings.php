<?php 



include('config.php');
include('session.php');
require_once('inc/functions.php') ;
require_once('meta_details.php') ;
 
$manager_id=$_SESSION['user_id'];

//----------------------profile
if ( isset($_POST["save-changes"]) ) {
 
	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;
	//  || empty($phone)
	if ( empty($fname) || empty($lname) ) {
		$_SESSION['error'] = "Please fill first name and last name all fields!" ;
		//die("1");
	}
	else 
	{
 


		//$columns = " firstname = '$fname' , lastname = '$lname' , phone = '$phone'" ;

		// if ( updateTableData( $conn , " admin_users " , $columns , " id = '".$_SESSION['user_id']."' " ) ) {
		// 	$_SESSION['success'] = "Profile details are updated successfully!" ;
		// }
		// $ano_columns = "address_line_1='$add1' , address_line_2='$add2', city='$city', state='$state', zipcode='$zipcode', country='$country'";
		// if ( updateTableData( $conn , " billing-address " , $ano_columns , " manager_id = '".$_SESSION['user_id']."' " ) ) {
		// 	$_SESSION['success'] = "Profile details are updated successfully!" ;
		// }
	// 	$sql = " UPDATE `admin_users` SET `addfirstname` = $fname, `lastname` = $lname, `phone` =$phone WHERE id = '".$_SESSION['user_id']."' " ;

	// 	$sql = " UPDATE `billing-address` SET `address` = $add1, `address_2` = $add2, `country` = $country, `city` = $city ,`zip` = $zipcode WHERE manager_id = '".$_SESSION['user_id']."' " ;
	// // echo $sql ;
	// // die();
	// if ( $conn->query($sql) === TRUE ) {
	// 	$_SESSION['success'] = "Profile details are updated successfully!" ;
	// }
		// else {
		// 	$_SESSION['error'] = "Operation failed!" ;
		// 	$_SESSION['error'] = "Error: " . $conn->error;
		// }

		// Begin a transaction
$conn->begin_transaction();

try {
    // Update data in admin_users table
    $sql1 = "UPDATE `admin_users` 
             SET `firstname` = '$fname', 
                 `lastname` = '$lname', 
                 `phone` = '$phone' 
             WHERE id = '".$_SESSION['user_id']."'";

    $conn->query($sql1);

    // Update data in billing-address table
    $sql2 = "UPDATE `billing-address` 
             SET `address` = '$add1', 
                 `address_2` = '$add2', 
                 `country` = '$country', 
                 `city` = '$city',
                 `zip` = '$zipcode' 
             WHERE manager_id = '".$_SESSION['user_id']."'";

    $conn->query($sql2);
    $conn->commit();

    $_SESSION['success'] = "Profile details are updated successfully!";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = "Failed to update profile details: " . $e->getMessage();
}
$conn->close();


	}

	header("location: ".HOST_URL."adminpannel/manager_settings.php?active=profile") ;
	die() ;
}
//--------------end profile

//------------------security
if ( isset($_POST["change-password"]) ) {

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($npassword) || empty($cpassword) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	elseif ( $npassword != $cpassword ) {
		$_SESSION['error'] = "Unmatched password!" ;
	}
	else {

		$pwd = md5($npassword) ;

		if ( updateTableData( $conn , " admin_users " , " password = '$pwd' " , " id = '".$_SESSION['user_id']."' " ) ) {
			$_SESSION['success'] = "Password changed successfully!" ;
		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}

	}

	header("location: ".HOST_URL."adminpannel/manager_settings.php??active=security") ;
	die() ;
}
//------------------end security

//----------------------payment method
$payment_method_row=getTableData($conn,"payment_method_details","manager_id='$manager_id'","",1);


//----------------------end payment method

 $row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
$hst_query = $conn->query("SELECT au.firstname,au.lastname,au.email,au.phone,ba.address,ba.address_2,ba.country,ba.city,ba.zip FROM admin_users as au JOIN `billing-address` as ba ON ba.manager_id = au.id WHERE au.id = '".$_SESSION['user_id']."' ") ;

									if ( $hst_query->num_rows > 0 ) {
										$get_data = $hst_query->fetch_assoc() ;
										// print_r($row);die('hello');
									}
//echo'<pre>';print_r($row);die;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

// Show Expire message //
	$plan_country = "";
		if($row['country'] != "101"){   // Matching user country to show plan link
		$plan_country = "-us";
	}
	include("error_message_bar_subscription.php");
// End Show Expire message //


$plan_data = getTableData( $conn , " user_subscription " , " user_id ='".$_SESSION['user_id']."' AND `status` LIKE 'active' ORDER BY `user_subscription`.`id` DESC " ) ;
if (count($plan_data)>0){
	$plan_id=$plan_data['plan_id'];

}




$manager_id=$_SESSION['user_id'];

$qur_hide="select * from admin_users where id='$manager_id'";
$sele_qr_hide= mysqli_query($conn,$qur_hide);
$run_qr_hide= mysqli_fetch_array($sele_qr_hide);

			
?>


<?php require_once("inc/style-and-script.php") ; ?>
		<style type="text/css">
			#getcsv {
			float: right;
			margin-bottom: 1em;
			}
			.custom-tabel .display{padding-top: 20px;}
			.custom-tabel .display th{min-width: 50px;}
			table.display.dataTable.no-footer {
			width: 1600px !important;
			}
			.menu{
				list-style: none;
				display: flex;
				margin: 5px;
				justify-content: space-around;
			}
			.Payment_method input{
				width: 100%;
				padding: 12px;
				border: 1px solid #ccc;
				border-radius: 3px;
			}
			.Payment_method label{
				margin-bottom: 10px;
				display: block;

			}
			.payment_method_btn_wrap{
				width: 10%;
			}
			.text-h{   
				font-size: 25px;
				text-align: center;
    	}
		.subscribe_cover1 {
    display: inline-table;
    width: calc(33% - 7px);
    border-radius: 8px;
	box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    background: #fff;
	overflow: hidden;
	padding:15px;

}
			 .Polaris-Card__Section ul{
				list-style: none;
/*				text-align: center;*/
				display: flex;
				flex-direction: column;
				margin: 0;
				position: relative;}
    	 .Polaris-Card__Section li{
					margin: 0 0 10px;
					position: relative;
					font-size: 15px;
					font-weight: 500;
					margin: 7px 0;
					color: #1d1d1bc7;
					text-transform: capitalize;}

				
	
	/* The message box is shown when the user clicks on the password field */
#nmessage {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#nmessage p {
  padding: 10px 35px;
  font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
}
</style>
	</head>
	<body class="custom-tabel">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MK5VN7M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>			
		<?php require_once("inc/sidebar.php"); ?>
			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				<?php require_once("inc/topbar.php");?>
				<!-- Page content-->
				<div class="container-fluid manager_setting">
					<!-- <h1 class="mt-4">Settings</h1> -->
					<?php require_once("inc/alert-status.php"); ?>
					<div id="custom_nav">
						<ul class="menu profile_tabs">
						<?php
							if ( $run_qr_hide["userstatus"] == "manager" ) 
						
						{
							
							?>

							<li><a href="<?=HOST_URL."adminpannel/my-subscriptions.php"?>" id="my_plan"><button  type="button" data-select="my plan" class=" nav_btn nav_btn6"><svg height="24" id="myPlanSVG" fill="currentColor" version="1.1" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg"><defs id="defs2"><rect height="7.0346723" id="rect2504" width="7.9207187" x="-1.1008456" y="289.81766"/></defs><g id="g2206" transform="translate(-0.066406)"><path d="m -72.986328,81.382812 v 1.5 c -1.837891,0 -3.675781,0 -5.513672,0 v 19.734378 h 15 v -2.26758 c 0.167079,0.0151 0.329005,0.0469 0.5,0.0469 3.104706,0 5.632815,-2.528102 5.632812,-5.632808 10e-7,-3.104707 -2.528105,-5.63086 -5.632812,-5.63086 -0.170566,0 -0.333281,0.02802 -0.5,0.04297 v -6.292969 c -1.830078,0 -3.660156,0 -5.490234,0 v -1.5 z m 1,1 h 1.996094 v 1.5 H -68 v 1 h -6 v -1 h 2.013672 z m -5.513672,1.5 h 2.5 v 2 h 8 v -2 h 2.5 v 5.472657 c -1.891187,0.527127 -3.39012,2.003573 -3.919922,3.894531 h -3.447266 v 1 h 3.259766 c -0.01546,0.169613 -0.02539,0.340102 -0.02539,0.513672 0,1.204638 0.384044,2.319294 1.03125,3.236328 h -4.265626 v 1 h 5.167969 c 0.62502,0.546512 1.379018,0.937728 2.199219,1.16797 v 1.44922 h -13 z m 14.5,6.25 c 2.564267,0 4.632813,2.066593 4.632812,4.63086 0,2.564266 -2.068546,4.632812 -4.632812,4.632812 -2.564266,0 -4.632812,-2.068546 -4.632812,-4.632812 -10e-7,-2.564267 2.068545,-4.630857 4.632812,-4.63086 z m -0.5,1.5 v 4 h 4 v -1 h -3 v -3 z" id="path2515" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1" transform="translate(80,-80)"/><path d="m 5.5,2.8828125 v 1 h 2.234375 v -1 z" id="path2527" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1"/><path d="m 4,12.25 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 -1.0442708,0 -2.0885417,0 -3.1328125,0 z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.333333 0,0.666667 0,1 -0.3776042,0 -0.7552083,0 -1.1328125,0 0,-0.333333 0,-0.666667 0,-1 z" id="path2499" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1"/><path d="m 4,7.5 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 C 6.0885417,7.5 5.0442708,7.5 4,7.5 Z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.3333333 0,0.6666667 0,1 C 5.7552083,9.5 5.3776042,9.5 5,9.5 5,9.1666667 5,8.8333333 5,8.5 Z" id="path2503" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1"/><path d="m 4,17 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 C 6.0885417,17 5.0442708,17 4,17 Z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.333333 0,0.666667 0,1 C 5.7552083,19 5.3776042,19 5,19 5,18.666667 5,18.333333 5,18 Z" id="path2507" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1"/><path d="m 8.1328125,8.5 c 0,0.3333333 0,0.6666667 0,1 1.9999995,0 3.9999995,0 5.9999995,0 0,-0.3333333 0,-0.6666667 0,-1 -2,0 -4,0 -5.9999995,0 z" id="path2535" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1px;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1"/></g></svg><span>My Subscriptions</span></button></a></li>
							
							<?php
						}
						?>
							<li><a href="?active=profile" id="profile" ><button type="button" data-select="Profile" class=" nav_btn nav_btn1 "><i class="las la-user"></i><Span>My Account<span></button></a></li>
								<?php
						
						if ( $run_qr_hide["userstatus"] == "manager" ) 
						
						{
							
							?>
							<li><a href="?active=payment" id="payment_btn" ><button type="button" data-select="Payment" class=" nav_btn nav_btn2"><i class="las la-money-bill"></i><span>Payment Method</span></button></a></li>
						<?php 
					}
						?>
						<?php
						if ( $run_qr_hide["userstatus"] == "manager" ) 
						
						{
							
							?>
							<li><a href="?active=teams" id="teams" ><button type="button" data-select="Teams" class="nav_btn nav_btn3"><i class="las la-users"></i><span>Teams</span></button></a></li>
							<?php
						}
						?>
							<li><a href="?active=security" id="security"><button type="button" data-select="Security" class="nav_btn nav_btn4"><i class="las la-shield-alt"></i><span>Security</span></button></a></li>
							<?php
							if ( $run_qr_hide["userstatus"] == "manager" ) 
						
						{
							
							?>
							<!-- <li><a  href="?active=subscriptions" id="subscriptions"><button  type="button" data-select="Subscribe" class=" nav_btn nav_btn5"><i class="las la-bell"></i><span>Subscriptions</span></button></a></li> -->
							<li><a href="<?=HOST_URL."adminpannel/billing-dashboard.php" ?>"><button  type="button"  class="nav_btn nav_btn6"><i class="las la-file-alt"></i><span>Invoices</span></button></a></li>
							<?php
						}
						?>
									
						</ul>
					</div>
					
					<div class="profile_tabs">
					<div class="tab profile" id="profile-tabs">
						<div class="row">
							<div class="tab_head">
							<h3>Edit Profile</h3>
							
							<!-- <?php // print_r($get_data);die;?> -->
		                     </div>
							<form method="POST">
								<div class="form-group">
									<label for="fname">First Name</label>
									<input type="text" class="form-control" id="fname" name="fname" required value="<?=$row["firstname"]?>">
								</div>
								<div class="form-group">
									<label for="lname">Last Name</label>
									<input type="text" class="form-control" id="lname" name="lname" required value="<?=$row["lastname"]?>">
								</div>
								<div class="form-group">
									<label for="email">Email Address</label>
									<span class="form-control" readonly><?=$row["email"]?></span>
								</div>
								<div class="form-group">
									<label for="phone">Phone</label>
									<input type="tel" class="form-control" id="phone" name="phone" maxlength="20" required value="<?=$row["phone"]?>">
								</div>
								<div class="form-group">
									<label for="add1">Address Line 1</label>
									<input type="text" class="form-control" id="add1" name="add1" required value="<?php echo $get_data['address']; ?>">
								</div>
								<div class="form-group">
									<label for="add2">Address Line 2</label>
									<input type="text" class="form-control" id="add2" name="add2" required value="<?= $get_data["address_2"]?>">
								</div>
								<div class="form-group">
									<label for="country">Country</label>
									<select class="form-control" id="country" name="country" required>
										<option>Select Country</option>
										<?php
											$list_countries = getTableData( $conn , " list_countries " , " 1 " , "" , 1 ) ;
											foreach ($list_countries as $key => $country_data) {
											
												$selected = ($country_data["id"] == $get_data["country"]) ? "selected" : "" ;
											
												?>
										<option value="<?=$country_data["id"]?>" <?=$selected?> ><?=$country_data["name"]?></option>
										<?php
											}
											?>
									</select>
								</div>
					<!-- 			<div class="form-group">
									<label for="state">State</label>
									<select class="form-control" id="state" name="state" required>
										<option>Select State</option>
										<?php
											$list_states = getTableData( $conn , " list_states " , " countryId = '".$row["country"]."' " , "" , 1 ) ;
											foreach ($list_states as $key => $state_data) {
											
												$selected = ($state_data["id"] == $row["state"]) ? "selected" : "" ;
											
												?>
										<option value="<?=$state_data["id"]?>" <?=$selected?> ><?=$state_data["statename"]?></option>
										<?php
											}
											?>
									</select>
								</div> -->
								<div class="form-group">
									<label for="city">City</label>
									<input type="text"  class="form-control" value='<?=$get_data["city"]?>' name="city" required>
									<!-- <select class="form-control" id="city" name="city" required>
										<option>Select City</option>
										<?php
											$list_cities = getTableData( $conn , " list_cities " , " state_id = '".$row["state"]."' " , "" , 1 ) ;
											foreach ($list_cities as $key => $city_data) {
											
												$selected = ($city_data["id"] == $row["city"]) ? "selected" : "" ;
											
												?>
										<option value="<?=$city_data["id"]?>" <?=$selected?> ><?=$city_data["cityName"]?></option>
										<?php
											}
											?>
									</select> -->
								</div>
								<div class="form-group">
									<label for="zipcode">ZipCode</label>
									<input type="text" class="form-control" id="zipcode" name="zipcode" minlength="3" maxlength="10" required value="<?=$get_data["zip"]?>">
								</div>
								<div class="form_h_submit">
								<button type="submit" name="save-changes" class="btn btn-primary mt-2">Save Changes</button>
										</div>
										<div class="form_h_submit">
								<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary mt-2">Delete Your Account</button>
										</div>
							</form>
							<div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog">
							
							<!-- Modal content-->
							<div class="modal-content delete_user ">
								<div class="modal-header">
								<!-- <button type="button" data-dismiss="modal">&times;</button> -->
								<h4 class="modal-title">Delete User </h4>
								<button type="button" class="btn btn-default swal2-close" data-dismiss="modal">×</button>
								</div>
								<div class="modal-body">
									<div class="row">
								<div class="">You want to delete your account parmanently .</div>
								<div class=""><button type="button" name="save-changes" id="deleteAccountparam" data-uid="<?=$row['id']?>" data-email="<?= $row['email'] ?>" class="btn btn-primary">Delete</button></div>
										</div>
										<div class="row">
								<div class="">You want to disable your account .</div>
								<div class=""><button type="button" name="save-changes" id="deleteAccount" data-uid="<?=$row['id']?>" class="btn btn-primary disable">Disable</button></div>
										</div>
								</div>
							</div>
							
							</div>
						</div>
						</div>
					</div>


					<div class="tab payment_method" id="payment_methods" style="display: none;">

							<div class="Payment_method_wrap">
								<?php //print_r($payment_method_row) ?>
								<div class="add_card_details_wrap">
									<a  href="<?=HOST_URL?>adminpannel/card_details_add.php"><button type="button"class="add_card_btn btn btn-primary">Add Card Details</button></a></a>
								</div>
								<div class="table_S">
								<form id="myForm" method="post">
									<table class="table  speedy-table">
										<thead>
											<tr>
												<th>S.no</th>
												<th>preferred Card</th>
												<th>Name</th>
												<th>Card Number</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody style="text-align:initial;">
											<?php
											if(count($payment_method_row)>0){
												$s_no = 1;
												
												foreach ($payment_method_row as $key => $value) {
												
													$card__name=$value['card_name'];
													$manager_ids=$value['manager_id'];
													$pref__card=$value['prefered_card'];
													$card__num=$value['card_number'];
													$card__id=$value['id'];
												?>
											<tr class="payment<?=$card__id?>">
												<td>
													<?= $s_no++ ?>
													<input type="hidden" id="managerId" value="<?= $manager_ids ?>">
												</td>
												<td>
													<input <?php 
														if($pref__card==1){
														 echo "checked"; }
														?> type="radio" data-id='<?=$card__id ?>' class="abc" name="radio">
												</td>
												<td>
													<?= $card__name ?>
												</td>
												<td>
													<?= $card__num ?>
												</td>
												<td ><a href="<?=HOST_URL?>adminpannel/card_details_edit.php?card_id=<?=base64_encode($card__id)?>" ><button type="button"class="credit_btn btn btn-primary"><svg class="svg-inline--fa fa-pen-to-square" aria-hidden="true" focusable="false" data-prefix="far" data-icon="pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M373.1 24.97C401.2-3.147 446.8-3.147 474.9 24.97L487 37.09C515.1 65.21 515.1 110.8 487 138.9L289.8 336.2C281.1 344.8 270.4 351.1 258.6 354.5L158.6 383.1C150.2 385.5 141.2 383.1 135 376.1C128.9 370.8 126.5 361.8 128.9 353.4L157.5 253.4C160.9 241.6 167.2 230.9 175.8 222.2L373.1 24.97zM440.1 58.91C431.6 49.54 416.4 49.54 407 58.91L377.9 88L424 134.1L453.1 104.1C462.5 95.6 462.5 80.4 453.1 71.03L440.1 58.91zM203.7 266.6L186.9 325.1L245.4 308.3C249.4 307.2 252.9 305.1 255.8 302.2L390.1 168L344 121.9L209.8 256.2C206.9 259.1 204.8 262.6 203.7 266.6zM200 64C213.3 64 224 74.75 224 88C224 101.3 213.3 112 200 112H88C65.91 112 48 129.9 48 152V424C48 446.1 65.91 464 88 464H360C382.1 464 400 446.1 400 424V312C400 298.7 410.7 288 424 288C437.3 288 448 298.7 448 312V424C448 472.6 408.6 512 360 512H88C39.4 512 0 472.6 0 424V152C0 103.4 39.4 64 88 64H200z"></path></svg></button></a>
													<a class="a_delete" >
													<button type="button"class="delete_btn btn btn-primary" data-id="<?=$card__id?>"> <svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path></svg></button></a>
												</td>
											</tr>
											<?php
												}
											}else{
												
												?>
													<tr >
											<td colspan="6">
												Data not found
											</td>

										</tr>
									<?php } ?>
										</tbody>
									</table>
								</form>
							</div>
						</div>
					</div>
					<div class="tab teams_cover" id="teams_cover_tabs" style="display: none;">

							<div class=" add_terms_s"> <a href="<?=HOST_URL."adminpannel/add-teams.php" ?>" class="btn btn-primary ">Add Teams </a></div>
						
						<div class="table_S">
						<table class="table speedy-table">
								<thead>
									<tr>
										<th>S.no</th>
										<th>Name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Website</th>
										
										<th>Action</th>
									</tr>
								</thead>
								<?php
								     
											$userid=$_SESSION['user_id'];
											
									$get="select * from admin_users where parent_id='$userid' AND userstatus='team'";
									$run=mysqli_query($conn,$get);
									
									
									?>
									
								<tbody>
									<?php
									if ($run->num_rows >0) {
												$numbers=1;
										while($resul=mysqli_fetch_array($run))
										{
										
											$id2= $resul['id'];
										$getss2="select * from team_access where team_id='$id2' ";
									$runss2=mysqli_query($conn,$getss2);
									$results2=mysqli_fetch_array($runss2);
									
									
										
										?>
										<?php
										$id3= $results2['website_id'];
										$getss="select * from boost_website where id='$id3' ";
									$runss=mysqli_query($conn,$getss);
									$results=mysqli_fetch_array($runss);
									
									
										?>
									<tr class="team_tr<?=$resul['id'];?>">
										<td><?php echo $numbers++;?></td>
										<td><?php echo $resul['firstname']." ".$resul['lastname'];?></td>
										<td><?php echo $resul['phone'];?></td>
										<td><?php echo $resul['email'];?></td>
										
										
										<td><?php echo $results['website_url'];?></td>
											
											 
											
											 
											 <td>
											 
											  <a  href="<?=HOST_URL."adminpannel/edit_teams.php?edit=".$resul['id']?>" class="btn btn-primary "><i class="fa-regular fa-pen-to-square"></i>
</a> 
											   <a  href="javascript:void(0)" data-tean_id="<?=$resul['id'];?>"  class="btn btn-primary teams_delete"><i class="fa-regular fa-trash-can"></i>
</a> 
											 
											 </td>
									</tr>
									<?php
										}
									}else{ ?>
										<tr >
											<td colspan="6">
												Data not found
											</td>

										</tr>
										
									<?php } ?>
										
								</tbody>
							</table>
									</div>
						
						
						
						
						
						
						
						
					</div>
					<div class="tab security_cover  " id="security_cover_tabs" style="display: none;">

							<h3>Change Password</h3>
							<div class="form_h">
							<form method="POST">
								<div class="form-group">
									<label for="npassword">New Password</label>
									<input type="password" class="form-control" id="npassword" name="npassword" required>
								</div>
								<div class="form-group">
									<label for="cpassword">Confirm Password</label>
									<input type="password" class="form-control" id="cpassword" name="cpassword" required>
								</div>
						<div id="nmessage">
						<h3>Password must contain the following:</h3>
						<p id="nletter" class="invalid">A <b>lowercase</b> letter</p>
						<p id="ncapital" class="invalid">A <b>uppercase</b> letter</p>
						<p id="nnumber" class="invalid">A <b>number</b></p>
						<p id="nlength" class="invalid">Minimum <b>8 characters</b></p>
						</div>
								<div class="form_h_submit">
								<button type="submit" name="change-password" class="btn btn-primary">Change Password</button>
									</div>
							</form>
							</div>
					</div>
					<?php //print_r($_SESSION); ?>
					<?php //print_r($plan_data);  ?>
					<div class="tab subscribe_cover   " id="subscribe_tabs" style="display: none;">
					  <div class="subscribe_cover_f">

								<?php
								 $projects_manager = getTableData( $conn , " user_subscriptions " , " user_id = '".$_SESSION['user_id']."' and  is_active = 1" , "" , 1  ) ;
								 $projects_manager_free = getTableData( $conn , " user_subscriptions_free " , " user_id = '".$_SESSION['user_id']."' and  status = 1" , "" , 1  ) ;
					 			
					 		  if(count($projects_manager)>0){ 
								foreach ($projects_manager as $user) 
								{

								 $projects = getTableData( $conn , " boost_website " , "manager_id = '".$_SESSION['user_id']."' and subscription_id='".$user['id']."' and plan_type = 'Subscription' " , "" , 1  );
										// echo $_SESSION['user_id'];
								 $av = $user['site_count'] - count($projects);

							 	  $av = $av." Available"; 

							 	  if($user['site_count'] =="Unlimited"){
							 	  	$av ="Unlimited";
							 	  }

							 	  $is_active="";
							 	  if($user['is_active']==0){
								 	  $is_active=" - Cancled";

							 	  }
							 	  $plan = getTableData( $conn , " plans " , " id ='".$user['plan_id']."' and status = 1" ) ;
							 	  ?>

					      					
											  <div class="subscribe_cover1 ">

					      			<?php echo '<div class="plan-header" style="width: 100%;">'.$plan['name'].'</div>';?>

											  	  <div class="plan_S">
														
															<?php 
																if($user['paid_amount'] != "") {
																    ?>
															<div class="price-tag"><span class="symbol"><?php echo strtoupper($user['paid_amount_currency']);?>&nbsp;</span><span class="amount" subs="<?php echo $user['paid_amount'];?>"><?php echo  $user['paid_amount'];  ?></span> <span class="after"><span class="month-slash" >/</span><?php echo $user['plan_interval'];  ?></span>
														    </div>
																</div>
																<div class="domains_num"><span>Number of website :</span><strong> <?php echo $av." ".$is_active ;?></strong></div>	
															<?php } ?>
													

									<?php	foreach ($projects as $key => $project_data) 
											{ $web_plan_id=$project_data['plan_id'];
											 

											  ?>
											
											<!-- <div class="domains">WEDSITE URL :<strong> <?=parse_url($project_data["website_url"])["host"]?></strong></div>	 -->
										<?php } ?>
													<?php if(isset($user['plan_period_start'])) {
																	?>

															<div class="plan_time">
																	<div class="start_plan plan_S">
																		<span>Start Plan :</span><?php 	 
																		$timedyaa= 	$user['plan_period_start'];
														       $vartimeaa = strtotime($timedyaa);
                                   echo $datetimeconas= date("F d, Y H:i", $vartimeaa); ?>
																	</div>
																	<div class="end_plan plan_S">
																	<span>End Plan :</span> <?php   $timedy2= 	$user['plan_period_end'];

                                           $vartime2 = strtotime($timedy2);

                                             $datetimecon2= date("F d, Y H:i", $vartime2); echo $datetimecon2 ; ?>
																		
																	</div>
																</div>
															<?php } ?>
									
										 </div>  

										<?php } } 
 								 if(count($projects_manager_free)>0){ 
								foreach ($projects_manager_free as $user) 
								{

								 $projects = getTableData( $conn , " boost_website " , "manager_id = '".$_SESSION['user_id']."' and subscription_id='".$user['id']."' and plan_type = 'Subscription' " , "" , 1  );
										// echo $_SESSION['user_id'];
								 $av = $user['site_count'] - count($projects);

							 	  $av = $av." Available"; 

							 	  if($user['site_count'] =="Unlimited"){
							 	  	$av ="Unlimited";
							 	  }

							 	  $is_active="";
							 	  if($user['status']==0){
								 	  $is_active=" - Cancled";

							 	  }
							 	  $plan = getTableData( $conn , " plans " , " id ='".$user['plan_id']."' and status = 1" ) ;
							 	  ?>

					      					
											  <div class="subscribe_cover1 ">

					      			<?php echo '<div class="plan-header" style="width: 100%;">'.$plan['name'].'</div>';?>

											  	  <div class="plan_S">
														
															<?php 
																if($user['paid_amount'] != "") {
																    ?>
															<div class="price-tag"><span class="symbol"><?php echo strtoupper($user['paid_amount_currency']);?>&nbsp;</span><span class="amount" subs="<?php echo $user['paid_amount'];?>"><?php echo  $user['paid_amount'];  ?></span> <span class="after"><span class="month-slash" >/</span><?php echo $user['plan_interval'];  ?></span>
														    </div>
																</div>
																<div class="domains_num">Number of website :<strong> <?php echo $av." ".$is_active ;?></strong></div>	
															<?php }else{ ?>
																<div class="price-tag"><span class="amount">FREE PLAN
																</span>
														    </div>
																</div>
																<div class="domains_num">Number of website :<strong> <?php echo $av." ".$is_active ;?></strong></div>	

									<?php }	foreach ($projects as $key => $project_data) 
											{ $web_plan_id=$project_data['plan_id'];
											 

											  ?>
											
											<!-- <div class="domains">WEDSITE URL :<strong> <?=parse_url($project_data["website_url"])["host"]?></strong></div>	 -->
										<?php } ?>
													<?php if(isset($user['plan_start_date'])) {
																	?>

															<div class="plan_time">
																	<div class="start_plan plan_S">
																		<span>Start Plan :</span> <?php 

																		
																		$timedy= 	$user['plan_start_date'];
														       $vartime = strtotime($timedy);
                                   echo $datetimecon= date("F d, Y H:i", $vartime);

																		?>
																	</div>
																	<div class="end_plan plan_S">
																	<span>End Plan :</span> <?php 

																

																	$timedyA= 	$user['plan_end_date'];
														       $vartimeAA = strtotime($timedyA);
                                   echo $datetimeconAA= date("F d, Y H:i", $vartimeAA);


																	?>
																		
																	</div>
																</div>
															<?php } } } ?>
									
										 </div>  


											
								</div>
					<!-- <div class="tab invoices_cover  ">
						<div class="coming__soon">
							
					</div>
											</div> -->
				    </div>
				</div>
			</div>
		</div>
	</body>
</html>

<script>
	   $('#phone').on('keypress change blur', function () {
        $(this).val(function (index, value) {
            return value.replace(/[^a-z0-9]+/gi, '').replace(/(.{20})/g, '$1 ');
        });
    });
$(document).ready(function(){

	$("#country").change(function(){

		var country = $(this).val() ;

		$.ajax({
			url:"inc/update-csc.php",
			method:"POST",
			dataType:"JSON",
			data:{country:country , action:"edit-profile"}
		}).done(function(response)
		{
			if ( response.status == 1 ) {
				$("#state").html(response.message) ;
				$("#city").html('<option value="">Please select a state first.</option>') ;
			}
		}).fail(function(){
			console.log("error") ;
		});
	});

	$("#state").change(function(){

		var state = $(this).val() ;

		$.ajax({
			url:"inc/update-csc.php",
			method:"POST",
			dataType:"JSON",
			data:{state:state , action:"edit-profile"}
		}).done(function(response)
		{
			if ( response.status == 1 ) {
				$("#city").html(response.message) ;
			}
		}).fail(function(){
			console.log("error") ;
		});
	});

    $(".delete_btn").click(function(e){
    	var condition_delete=confirm('Are you sure you want to delete this card details?');
        if(condition_delete==true){
        	var card_id=$(this).attr("data-id");
        	// console.log(card_id);
				$.ajax({
				url: "<?=HOST_URL?>/adminpannel/card_details_delete.php",
				type: "POST",
				dataType: "json",
				data: {
				id: card_id,

				},
				success: function (data) {

					if (data.status == "done") {
						
							$(".payment"+card_id).hide();
						// setTimeout(window.location.reload(), 1000);
                        $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

					    }else{
                        $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

					    }

					}
				});
            
        }
    });

			$(".teams_delete").click(function(e){
    	var condition_delete=confirm('Are you sure you want to delete this team?');
        if(condition_delete==true){
        	var id=$(this).attr("data-tean_id");
        	// console.log(card_id);
				$.ajax({
				url: "<?=HOST_URL?>/adminpannel/delete_teams.php",
				type: "POST",
				dataType: "json",
				data: {
				id: id,

				},
				success: function (data) {

					if (data.status == "done") {
						
							$(".team_tr"+id).hide();
						// setTimeout(window.location.reload(), 1000);
							 $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					    }else{
					    	  $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					    }

					}
				});
            
        }
   });


	$(".abc").click(function(){
		
		
		  var cid	=	$(this).attr("data-id");
		 var Mid	= $("#managerId").val();
		 
		 var set=1;
		 	// alert(Mid);
			 
			 
	      $.ajax({
	        type: 'post',
	        url: 'preferred-card.php',
	        data: {'cid':cid,'set':set,'Mid':Mid},
	        success: function () {
	         
	        }
	      });
		
	});



// When the user clicks on the password field, show the message box

  document.getElementById("nmessage").style.display = "block";


// When the user clicks outside of the password field, hide the message box
setInterval(abc, 1000);

// When the user starts to type something inside the password field
function abc() {
var myInput = document.getElementById("npassword");
var letter = document.getElementById("nletter");
var capital = document.getElementById("ncapital");
var number = document.getElementById("nnumber");
var length = document.getElementById("nlength");
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}

});



       
jQuery('#payment_btn').click(function(){
// alert("hii");
jQuery('#payment_methods').css("display","block");
jQuery('#profile-tabs').css("display","none");

jQuery('#teams_cover_tabs').css("display","none");
jQuery('#subscribe_tabs').css("display","none");
})

jQuery('#profile').click(function(){

jQuery('#payment_methods').css("display","none");
jQuery('#profile-tabs').css("display","block");
jQuery('#security_cover_tabs').css("display","none");
jQuery('#teams_cover_tabs').css("display","none");
jQuery('#subscribe_tabs').css("display","none");
});

jQuery('#teams').click(function(){

jQuery('#payment_methods').css("display","none");
jQuery('#profile-tabs').css("display","none");
jQuery('#security_cover_tabs').css("display","none");
jQuery('#teams_cover_tabs').css("display","block");
jQuery('#subscribe_tabs').css("display","none");
});

jQuery('#security').click(function(){

jQuery('#payment_methods').css("display","none");
jQuery('#profile-tabs').css("display","none");

jQuery('#security_cover_tabs').css("display","block");
jQuery('#teams_cover_tabs').css("display","none");
jQuery('#subscribe_tabs').css("display","none");
});

jQuery('#subscriptions').click(function(){

jQuery('#payment_methods').css("display","none");
jQuery('#profile-tabs').css("display","none");

jQuery('#security_cover_tabs').css("display","none");
jQuery('#teams_cover_tabs').css("display","none");
jQuery('#subscribe_tabs').css("display","block");
});
	
        
        
        var active_tab = window.location.href.split('?active=')[1];
        console.log(active_tab);
        if (active_tab == 'profile') {

            $(".tab").addClass("d-none");
            $(".profile").removeClass("d-none");
            $(".nav_btn1").addClass("active");

jQuery('#payment_methods').css("display","none");
jQuery('#profile-tabs').css("display","block");
jQuery('#security_cover_tabs').css("display","none");
jQuery('#teams_cover_tabs').css("display","none");
jQuery('#subscribe_tabs').css("display","none");


        }
        if (active_tab == 'payment') {
            $(".tab").addClass("d-none");
            $(".Payment_method").removeClass("d-none");
            $(".nav_btn2").addClass("active");

jQuery('#payment_methods').css("display","block");
jQuery('#profile-tabs').css("display","none");

jQuery('#teams_cover_tabs').css("display","none");
jQuery('#subscribe_tabs').css("display","none");


        }
        if (active_tab == 'teams') {
            $(".tab").addClass("d-none");
            $(".teams_cover").removeClass("d-none");
            $(".nav_btn3").addClass("active");
jQuery('#payment_methods').css("display","none");
jQuery('#profile-tabs').css("display","none");
jQuery('#security_cover_tabs').css("display","none");
jQuery('#teams_cover_tabs').css("display","block");
jQuery('#subscribe_tabs').css("display","none");
        }
        if (active_tab == 'security') {
            $(".tab").addClass("d-none");
            $(".security_cover").removeClass("d-none");
            $(".nav_btn4").addClass("active");
            jQuery('#payment_methods').css("display","none");
jQuery('#profile-tabs').css("display","none");

jQuery('#security_cover_tabs').css("display","block");
jQuery('#teams_cover_tabs').css("display","none");
jQuery('#subscribe_tabs').css("display","none");

        }

        if (active_tab == 'subscriptions') {
            $(".tab").addClass("d-none");
            $(".nav_btn5").addClass("active");
            $(".subscribe_cover").removeClass("d-none");
            jQuery('#payment_methods').css("display","none");
jQuery('#profile-tabs').css("display","none");

jQuery('#security_cover_tabs').css("display","none");
jQuery('#teams_cover_tabs').css("display","none");
jQuery('#subscribe_tabs').css("display","block");
        }
       
   
</script>

<script>
	$('.profile_tabs li a').click(function(){
			$('.alert-status').css('display', 'none');
	})


	$(document).ready(function(){

		$('#deleteAccountparam').on('click',function(){
			let id = $(this).data('uid');
			//alert(id);
			let email = $(this).data('email');
			let status = "delete_user";
			$.ajax({
				url : "<?=HOST_URL.'adminpannel/delete_account.php'?>",
				type : "post",
				data : {
					uid : id,
					email : email,
					status : status
				},
				// dataType : "JSON",
				success:function(response){
					console.log(response);
					if(response == 1){
					window.location.href = "<?=HOST_URL.'adminpannel/logout.php'?>";
					}else{
						console.log("user is not deleted");
					}
				}
			})
			//.done(function(data){ console.log(data) ; }) ;

		})


		$('#deleteAccount').on('click',function(){
			let id = $(this).data('uid');
			let status = "disable_user";

			$.ajax({
				url : "<?=HOST_URL.'adminpannel/delete_account.php'?>",
				type : "post",
				data : {
					uid : id,
					status : status
				},
				success : function(response){
					console.log(response);
					if(response == 1){
					window.location.href = "<?=HOST_URL.'adminpannel/logout.php'?>";
					}else{
						console.log("user is not disable");
					}
				}
			})

		})
	})
</script>