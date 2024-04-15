<?php

include('config.php');
include('session.php');
require_once('meta_details.php');
require_once('inc/functions.php');
// ini_set('display_errors', 1); 
//  ini_set('display_startup_errors', 1); error_reporting(E_ALL);

// print_r($_SESSION) ;
$manager_id = $_SESSION['user_id'];
$card_id = base64_decode($_GET['card_id']);



//----------------------payment method
$payment_method_row = getTableData($conn, "payment_method_details", "id='$card_id'");

if (isset($_POST["payment_method"])) {

	// print_r($_POST) ; 

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value);
	}
	extract($_POST);

	if (empty($cardnumber) || empty($expmonth) || empty($expyear) || empty($cvv)) {
		$_SESSION['error'] = "Please fill all fields!";
		//die("1");
	} else {
		// $card__number="%".substr($cardnumber, -4, 4);
		$sql = "SELECT * FROM `payment_method_details` where card_number LIKE '" . "%" . substr($cardnumber, -4, 4) . "' AND exp_month LIKE '" . "%" . $expmonth . "' AND exp_year LIKE  '" . "%" . $expyear . "' AND cvv LIKE '" . "%" . $cvv . "'";
		$payment_method_data = $conn->query($sql);

		if (($payment_method_data->num_rows) <= 0) {
			if (count($payment_method_row) > 0) {
				$cardnumber = "************" . substr($cardnumber, -4, 4);
				$columns = " card_name = '$cardname' , card_number = '$cardnumber' , exp_month = '$expmonth' , exp_year='$expyear', cvv='$cvv'";

				if (updateTableData($conn, " payment_method_details ", $columns, "id='" . $card_id . "' ")) {
					$_SESSION['success'] = "Payment details are updated successfully!";
					header("location: " . HOST_URL . "adminpannel/manager_settings.php?active=payment");
					die();
				} else {
					$_SESSION['error'] = "Operation failed!";
					$_SESSION['error'] = "Error: " . $conn->error;
				}
			}
		} else {
			$_SESSION['error'] = "Payment details already save !";
		}
	}
}
//----------------------end payment method


$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}
if (count($payment_method_row) > 0) {
	$card__name = $payment_method_row['card_name'];
	$card__number = $payment_method_row['card_number'];
	$exp__month = $payment_method_row['exp_month'];
	$exp__year = $payment_method_row['exp_year'];
	$card__cvv = $payment_method_row['cvv'];
}

$plan_data = getTableData($conn, " user_subscription ", " user_id ='" . $_SESSION['user_id'] . "' AND `status` LIKE 'active' ORDER BY `user_subscription`.`id` DESC ");
if (count($plan_data) > 0) {
	$plan_id = $plan_data['plan_id'];
}



?>
<?php require_once("inc/style-and-script.php"); ?>
<style type="text/css">
	#getcsv {
		float: right;
		margin-bottom: 1em;
	}

	.custom-tabel .display {
		padding-top: 20px;
	}

	.custom-tabel .display th {
		min-width: 50px;
	}

	table.display.dataTable.no-footer {
		width: 1600px !important;
	}

	.Payment_method input {
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 3px;
	}

	.Payment_method label {
		margin-bottom: 10px;
		display: block;

	}

	.payment_method_btn_wrap {
		width: fit-content;
		margin: auto;
	}

	.text-h {
		font-size: 25px;
		text-align: center;
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
			<div class="container-fluid  edit_card content__up">



				<h1>Edit Card Details</h1>
				<div class=" tab Payment_method  ">
				<?php require_once("inc/alert-status.php"); ?>

					<div class="back_btn_wrap ">
						<a href="<?= HOST_URL ?>adminpannel/manager_settings.php?active='payment'" class="Polaris-Button">
							<button type="button" class="back_btn btn btn-primary "> Back</button>
						</a>
					</div>

					<div class="Payment_method_wrap">
						<div class="form_h">
							<form method="POST">
								<div class="form-group">
									<label for="cname">Name on Card</label>
									<input type="text" id="cname" class="form-control only_string" value="<?php if (count($payment_method_row) > 0) {
																									echo $card__name;
																								} ?>" name="cardname" placeholder="John More Doe">
								</div>
								<div class="form-group">
									<label for="ccnum">Credit card number</label>
									<input type="number'" id="ccnum"  maxlength=16 class="form-control" value="<?php if (count($payment_method_row) > 0) {
																									echo $card__number;
																								} ?>" name="cardnumber" placeholder="card number">
								</div>
								<div class="form-group card_D">
									<div class="exp_m">
										<label for="expmonth">Exp Month</label>
										<input type="number" maxlength="2" id="expmonth" class="form-control" value="<?php if (count($payment_method_row) > 0) {
																															echo $exp__month;
																														} ?>" name="expmonth" placeholder="Month Number ">
									</div>
									<div class="exp_y">
										<label for="expyear">Exp Year</label>
										<input type="number" maxlength="4" id="expyear" class="form-control" value="<?php if (count($payment_method_row) > 0) {
																														echo $exp__year;
																													} ?>" name="expyear" placeholder="2018">
									</div>
									<div class="card_cvv">
										<label for="cvv">CVV</label>
										<input type="number" id="cvv" class="form-control" value="<?php if (count($payment_method_row) > 0) {
																										echo $card__cvv;
																									} ?>" name="cvv" placeholder="352">
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



</body>

</html>