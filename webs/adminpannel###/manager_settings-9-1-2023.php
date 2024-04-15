<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); error_reporting(E_ALL);

// print_r($_SESSION) ;
$manager_id=$_SESSION['user_id'];

//----------------------profile
if ( isset($_POST["save-changes"]) ) {

	 // print_r($_POST) ; 

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($fname) || empty($lname) || empty($phone) || empty($add1) || empty($city) || empty($state) || empty($zipcode) || empty($country) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
		//die("1");
	}
	else {
		
		//die("2" );

		$columns = " firstname = '$fname' , lastname = '$lname' , phone = '$phone' , address_line_1='$add1' , address_line_2='$add2', city='$city', state='$state', zipcode='$zipcode', country='$country'" ;

		if ( updateTableData( $conn , " admin_users " , $columns , " id = '".$_SESSION['user_id']."' " ) ) {
			$_SESSION['success'] = "Profile details are updated successfully!" ;
		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}
	}

	header("location: ".HOST_URL."adminpannel/manager_settings.php") ;
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

	header("location: ".HOST_URL."adminpannel/manager_settings.php") ;
	die() ;
}
//------------------end security

//----------------------payment method
$payment_method_row=getTableData($conn,"payment_method_details","manager_id='$manager_id'","",1);


//----------------------end payment method


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}


$plan_data = getTableData( $conn , " user_subscription " , " user_id ='".$_SESSION['user_id']."' AND `status` LIKE 'active' ORDER BY `user_subscription`.`id` DESC " ) ;
if (count($plan_data)>0){
	$plan_id=$plan_data['plan_id'];

}



?>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<title>Admin Dashboard</title>
		<!-- Favicon-->
		<link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
		<!-- Core theme CSS (includes Bootstrap)-->
		<link href="css/styles.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/js/all.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
		    <script>
        $(document).ready(function () {
            $('#table_id').DataTable();
        });
    </script>
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
				margin-bottom: 20px;
				padding: 12px;
				border: 1px solid #ccc;
				border-radius: 3px;
			}
			.Payment_method label{
				margin-bottom: 10px;
				display: block;

			}
			.Payment_method_wrap{
				padding: 50px;
			}
			.payment_method_btn_wrap{
				width: 10%;
			}
			.text-h{   
				font-size: 25px;
				text-align: center;
    	}
    	 .Polaris-Card__Section ul{
				list-style: none;
				text-align: center;
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
		.price-tag{text-align: center;}
		.plan-name{text-align: center;}			
		</style>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid">
					<?php require_once("inc/alert-status.php") ; ?>

			<div id="custom_nav">
			<ul class="menu">
			    <li><a><button type="button" data-select="Profile" class="btn btn-primary nav_btn nav_btn1 active">Profile</button></a></li>
			    <li><a><button  type="button" data-select="Payment" class="btn btn-primary nav_btn nav_btn2">Payment Method</button></a></li>
			    <li><a><button  type="button" data-select="Teams" class=" btn btn-primary nav_btn nav_btn3">Teams</button></a></li>
			    <li><a><button type="button" data-select="Security" class=" btn btn-primary nav_btn nav_btn4">Security</button></a></li>
			    <li><a><button  type="button" data-select="Subscribe" class=" btn btn-primary nav_btn nav_btn5">Subscribe</button></a></li>
			    <li><a><button  type="button" data-select="Invoices" class="btn btn-primary nav_btn nav_btn6">Invoices</button></a></li>
			</ul>
			</div>


			<div class="tab profile ">
				<div class="row">
					<h1 class="mt-4">Edit Profile</h1>
					<?php //print_r($row) ;?>
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
							<label for="email">Email address</label>
							<span class="form-control" readonly><?=$row["email"]?></span>
					  	</div>

						<div class="form-group">
							<label for="phone">Phone</label>
							<input type="tel" class="form-control" id="phone" name="phone" required value="<?=$row["phone"]?>">
						</div>
						
						<div class="form-group">
							<label for="add1">Address Line 1</label>
							<input type="text" class="form-control" id="add1" name="add1" required value="<?=$row["address_line_1"]?>">
						</div>


						<div class="form-group">
							<label for="add2">Address Line 2</label>
							<input type="text" class="form-control" id="add2" name="add2" required value="<?=$row["address_line_2"]?>">
						</div>

							<div class="form-group">
							<label for="city">City</label>
							<input type="text" class="form-control" id="city" name="city" required value="<?=$row["city"]?>">
						</div>
						<div class="form-group">
							<label for="state">State</label>
							<input type="text" class="form-control" id="state" name="state" required value="<?=$row["state"]?>">
						</div>
						<div class="form-group">
							<label for="zipcode">ZipCode</label>
							<input type="text" class="form-control" id="zipcode" name="zipcode" required value="<?=$row["zipcode"]?>">
						</div>
						<div class="form-group">
							<label for="country">Country</label>
							<input type="text" class="form-control" id="country" name="country" required value="<?=$row["country"]?>">
						</div>
						
						
						<button type="submit" name="save-changes" class="btn btn-primary mt-2">Save Changes</button>
					</form>
					
				</div>
			</div>

			<div class=" tab Payment_method  ">
				<div class="row">
						<div class="Payment_method_wrap">
							<?php //print_r($payment_method_row) ?>
							<div class="add_card_details_wrap">
								<a  href="<?=HOST_URL?>adminpannel/card_details_add.php?project=<?=$manager_id?>"><button type="button"class="add_card_btn btn btn-primary">Add Card Details</button></a></a>
							</div>
                  <form id="myForm" method="post">
                                    <table class="table table-bordered speedy-table">
									 
									 
													 
									 
									
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
                                                $s_no = 1;

                                                foreach ($payment_method_row as $key => $value) {
                                                
                                                	$card__name=$value['card_name'];
                                                	$manager_ids=$value['manager_id'];
                                                	$pref__card=$value['prefered_card'];
                                                	$card__num=$value['card_number'];
                                                	$card__id=$value['id'];
                                                ?>
                                                <tr>
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
                                                	
                                                    <td ><a href="<?=HOST_URL?>adminpannel/card_details_edit.php?card_id=<?=$card__id?>" ><button type="button"class="credit_btn btn btn-primary">Edit</button></a>
   

												<a class="a_delete" >
										<button type="button"class="delete_btn btn btn-primary" data-id="<?=$card__id?>"> Delete</button></a></td>
                                                </tr>
                                                <?php
                                                }

                                                ?>

                                            </tbody>


                                    </table>
  </form>
						</div>
					</div>
         
				</div>
			


			<div class=" tab teams_cover  ">
				<div class="row">
					<div style="font-size: 32px;text-align: center;">coming soon</div>
				</div>
			</div>

			<div class="tab security_cover  ">
				<div class="row">
					<h1 class="mt-4">Change Password</h1>
					<form method="POST">
						
						<div class="form-group">
							<label for="npassword">New Password</label>
							<input type="password" class="form-control" id="npassword" name="npassword" required>
						</div>

						<div class="form-group">
							<label for="cpassword">Confirm Password</label>
							<input type="password" class="form-control" id="cpassword" name="cpassword" required>
						</div>

						<button type="submit" name="change-password" class="btn btn-primary">Change Password</button>
					</form>
				</div>
			</div>
<?php //print_r($_SESSION); ?>
<?php //print_r($plan_data);  ?>
			<div class="tab subscribe_cover  ">
				<div class="row">
					<div class="text-h mt-4">Current Subscription Plans</div>
					<div class="Polaris-Card">
						<div class="Polaris-Card__Section">
							<div class="top-sec-card">
					<?php
					 $select_plan = getTableData($conn , " plans " , " id ='".$plan_id."' AND status = 1 " ) ;
					 // $select_plan = getTableData($conn , " plans " , " id =4 AND status = 1 " ) ;
					// print_r($select_plan);
					?>
											<h2 class="plan-name">
												<?php echo $select_plan['s_type']; ?>
											</h2>
											 <?php 
                                                if($select_plan['s_price'] != "") {
                                                    ?>
                                                    <div class="price-tag"><span class="symbol"><?php if($i!=0){?>$<?php }?></span><span class="amount" subs="<?php echo $select_plan['id'];?>"><?php echo "$". $select_plan['s_price'];  ?></span> <span class="after"><span class="month-slash" >/</span>month</span></div>
                                                    <?php 
                                                } 
                                                ?>
					         			 	<ul>
                                               <li><?php echo number_format($select_plan['page_view']); ?>  PAGEVIEWS /MONTH</li>

                                                <?php
                                                    if($select_plan['s_type'] == "Silver" || $select_plan['s_type'] == "Gold" || $select_plan['s_type'] == "Diamond" || $select_plan['s_type'] == "Pro"){
                                                    ?>
                                                <li>Full Site Optimization</li>
                                                <?php
                                                    }
                                                    if($select_plan['s_type'] == "Silver" || $select_plan['s_type'] == "Gold" || $select_plan['s_type'] == "Diamond" || $select_plan['s_type'] == "Pro"){
                                                    ?>
                                                <li>Easy Manual For Adding Code</li>
                                                <?php
                                                    }
                                                    if($select_plan['s_type'] == "Silver"){
                                                    ?>
                                                <li>Boost 2X Speed</li>
                                                <?php
                                                    }
                                                    
                                                    if($select_plan['s_type'] == "Gold" || $select_plan['s_type'] == "Diamond" || $select_plan['s_type'] == "Pro"){
                                                    ?>
                                                <li>Boost 3X Speed</li>
                                                <?php
                                                    }
                                                    
                                                    if($select_plan['s_type'] == "Gold" || $select_plan['s_type'] == "Diamond" || $select_plan['s_type'] == "Pro"){
                                                    ?>
                                                <li>Expert Help</li>
                                                <li>Other Help</li>
                                                <?php
                                                    }
                                                    
                                                    if($select_plan['s_type'] == "Free"){
                                                    ?>
                                                <li><?=$select_plan['s_duration']?> days free trial</li>
                                                <?php
                                                    }

                                                    if($select_plan['s_type'] == "Diamond" || $select_plan['s_type'] == "Pro"){
                                                    ?>
                                                <li>Help Support</li>
                                                <?php
                                                    }
                                                    
                                                    ?>
                                        </ul>
                                        <div class="subscribe_btn_wrap text-center">
                                        	<a href="<?=HOST_URL?>plan.php" class="Polaris-Button">
                                            <button type="button" class="compare_btn btn btn-primary ">Compare Plans</button>
                                        </a>
                                        </div>
							</div>                                            
						</div>                                            
					</div>
					
				</div>
			</div>

			<div class="tab invoices_cover  ">
				<div class="row">
					<div style="font-size: 32px;text-align: center;">coming soon</div>
				</div>
			</div>

					
					
				</div>
			</div>
		</div>
		<!-- Bootstrap core JS-->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Core theme JS-->
		<script src="js/scripts.js">
	</script>
	<script language="JavaScript" type="text/javascript">

    $(".delete_btn").click(function(e){
    	var condition_delete=confirm('Are you sure you want to delete this card details?');
        if(condition_delete==true){
        	var card_id=$(this).attr("data-id");
        	// console.log(card_id);
				$.ajax({
				url: "https://ecommerceseotools.com/ecommercespeedy/adminpannel/card_details_delete.php",
				type: "POST",
				dataType: "json",
				data: {
				id: card_id,

				},
				success: function (data) {

					if (data.status == "done") {
						

						setTimeout(window.location.reload(), 1000);
					    }

					}
				});
            
        }
    });

</script>
	
	</body>
</html>
<script type="text/javascript">


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


 
   
</script>