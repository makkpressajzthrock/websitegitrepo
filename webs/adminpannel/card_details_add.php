<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;
// ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); error_reporting(E_ALL);

// print_r($_SESSION) ;
$manager_id=$_SESSION['user_id'];
// SELECT * FROM `payment_method_details` where card_number LIKE '%4242' AND exp_month LIKE '%4' AND exp_year LIKE '%2025';
//----------------------payment method


if ( isset($_POST["payment_method"]) ) {

	 // print_r($_POST) ; 

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($cardnumber) || empty($expmonth) || empty($expyear) || empty($cvv)  ) {
		$_SESSION['error'] = "Please fill all fields!" ;
		//die("1");
	}
	else {
		// $card__number="%".substr($cardnumber, -4, 4);
		$sql="SELECT * FROM `payment_method_details` where card_number LIKE '"."%".substr($cardnumber, -4, 4)."' AND exp_month LIKE '"."%".$expmonth."' AND exp_year LIKE  '"."%".$expyear."' AND cvv LIKE '"."%".$cvv."'";
		$payment_method_row=$conn->query($sql);
		// $payment_method_row=getTableData($conn,"payment_method_details","card_number LIKE '"."%".substr($cardnumber, -4, 4)."' AND exp_month LIKE '"."%".$expmonth."' AND exp_year LIKE  '"."%".$expyear."' AND cvv LIKE '"."%".$cvv."'  ");
		if(($payment_method_row->num_rows)<=0){
				$cardnumber="************".substr($cardnumber, -4, 4);
				if(insertTableData( $conn , "payment_method_details", "`manager_id`, `card_name`, `card_number`, `exp_month`, `exp_year`, `cvv`" , "'$manager_id','$cardname','$cardnumber','$expmonth','$expyear','$cvv'")){
					$_SESSION['success'] = "Payment details saved successfully!" ;
						header("location: ".HOST_URL."adminpannel/manager_settings.php?active=payment") ;
						die() ;

				}
				else {
					$_SESSION['error'] = "Operation failed!" ;
					$_SESSION['error'] = "Error: " . $conn->error;
				}
		}
	else {
					$_SESSION['error'] = "Payment details already save !" ;

	}
	
	}


}
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
			.Payment_method label{
				margin-bottom: 10px;
				display: block;

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
		<div class="top-bg-img"></div>
			
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid add_card content__up">
				<h1>Add Card Details</h1>

					
				<div class="back_btn_wrap ">
                	<a href="<?=HOST_URL?>adminpannel/manager_settings.php?active='payment'" class="Polaris-Button">
                    <button type="button" class="back_btn btn btn-primary "> Back</button>
                </a>
                </div>
			<div class=" tab Payment_method  ">
						<div class="Payment_method_wrap ">
							<div class="form_h">
							<?php require_once("inc/alert-status.php") ; ?>		
							<form method="POST" >	
								<div class="form-group">
								<label for="cname">Name on Card</label>
									<input type="text"  id="cname"  class="form-control only_string" value="<?=$_POST['cardname']?>" name="cardname" placeholder="John More Doe">
		                        </div>
								   <div class="form-group">	
										<label for="ccnum">Credit Card Number</label>
										<input type="number"  id="ccnum"  class="form-control" value="<?=$_POST['cardnumber']?>"  maxlength=16 name="cardnumber" placeholder="card number">
									</div>
									<div class="form-group card_D">
										<div class="exp_m">
										<label for="expmonth">Exp Month</label>
										<input type="number" maxlength="2" id="expmonth"  class="form-control" value="<?=$_POST['expmonth']?>" name="expmonth" placeholder="Month Number (1-12)">
		                                </div>
							
									    <div class="exp_y">
										<label for="expyear">Exp Year</label>
										<input type="number" maxlength="4" id="expyear"  class="form-control" value="<?=$_POST['expyear']?>" name="expyear" placeholder="2018">
									     </div>
									    <div class="card_cvv">
										<label for="cvv">CVV</label>
										<input type="number"   id="cvv" value="<?=$_POST['cvv']?>"  class="form-control" name="cvv" placeholder="352">
									    </div>
		                            </div>
							   
							    <div class="payment_method_btn_wrap">
							     <input type="submit" name="payment_method" value="Save" class="btn btn-primary">
							    </div>
							</form>
							</div>
		 </div>
					</div>
         
				</div>

					
				</div>
			</div>
		</div>

	
	</body>
</html>