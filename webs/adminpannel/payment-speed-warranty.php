<?php 

require_once('config.php');
require_once('inc/functions.php') ;

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
	$speed_plans = getTableData( $conn , " plans_warranty " , " id = '".$_GET['plan']."' " ) ;
	$plan_id = $_GET['plan'] ;


if ( isset($_POST["stripeToken"]) ) {

	$project_id = $_GET['project'] ;
	// echo "<pre>";
	// print_r($_POST) ;

					$hireCardname = $_POST["hire-cardname"] ;
				$hireCardnumber = $_POST["hire-cardnumber"] ;
				$hireCardmm = $_POST["hire-cardmm"] ;
				$hireCardyyyy = $_POST["hire-cardyyyy"] ;
				$hireCardcvv = $_POST["hire-cardcvv"] ;
				$stripeToken = $_POST["stripeToken"] ;
				$itemName = $_POST["item-name"] ;
				// $itemName = $speed_plans['interval']." warranty";
				$amount =  $_POST["amount"] ; ;
				$currency = $_POST["currency"] ;

			    $amount = round($amount*100); 

	// empty($complete) ||
				if (  empty($hireCardname) || empty($hireCardnumber) || empty($hireCardmm) || empty($hireCardyyyy) || empty($hireCardcvv) ) {
					$_SESSION["error"] = "Please fill all required fields of hire us form." ;
					$flag = 1 ;
				}
				else 
				{

					require_once('gateway/stripe-php/init.php') ;

					$user_data = getTableData( $conn , " admin_users " , " id = '".$_SESSION['user_id']."' AND userstatus LIKE 'manager' " ) ;
					$payment_gateway = getTableData( $conn , " payment_gateway " , " name LIKE 'stripe' " ) ;

					\Stripe\Stripe::setApiKey($payment_gateway['secret_key']);
					//add customer to stripe
				    $customer = \Stripe\Customer::create(array(
						'name' => $user_data["firstname"].''.$user_data["lastname"],
						'description' => 'Payment warranty plan ',
				        'email' => $user_data["email"],
				        'source'  => $stripeToken
				    ));  

				    // details for which payment performed
				    $payDetails = \Stripe\Charge::create(array(
				        'customer' => $customer->id,
				        'amount'   => $amount,
				        'currency' => $currency,
				        'description' => $itemName,
				        'metadata' => array(
				            'order_id' => time()
				        )
				    ));
					// print_r($payDetails);
					// get payment details
					$paymenyResponse = $payDetails->jsonSerialize();

				    // check whether the payment is successful
				    if($paymenyResponse['amount_refunded'] == 0 && empty($paymenyResponse['failure_code']) && $paymenyResponse['paid'] == 1 && $paymenyResponse['captured'] == 1) 
				    {

						// transaction details 
				        $amountPaid = $paymenyResponse['amount'];
				        $txn_id = $paymenyResponse['balance_transaction'];
				        $paidCurrency = $paymenyResponse['currency'];
				        $paymentStatus = $paymenyResponse['status'];
				        $description = $paymenyResponse['description'];
				        $paymentDate = date("Y-m-d H:i:s");  

				        $json_data = serialize($paymenyResponse) ;  
				       
				          $amountPaid = round($amountPaid/100); 
				    	$sql = " INSERT INTO details_warranty_plans ( manager_id ,plan_id, website_id,description  , hire_cardname , hire_cardnumber , hire_cardmm , hire_cardyyy , hire_cardcvv , payment_method , stripe_customer_id , txn_id , paid_amount , paid_amount_currency , json_data , status , paymentDate ) VALUES ( '".$_SESSION['user_id']."' , '$plan_id' ,'$project_id'  ,'$description'  , '$hireCardname' , '$hireCardnumber' , '$hireCardmm' , '$hireCardyyyy' , '$hireCardcvv' , 'stripe' , '".$customer->id."' , '$txn_id' , '$amountPaid' , '$paidCurrency' , '$json_data' , '$paymentStatus' , '$paymentDate' ) ; " ;
				    		// echo "sql  -------------".$sql;

				    		$conn->query($sql) ;
				    	$_SESSION["success"] = "Payment successfully." ;

				    }
				    else {
				    	$flag = 1 ;
				    	$_SESSION["error"] = "Payment Failed." ;
				    }
				}




			

			// if ( !empty($msg) ) {
			// 	$_SESSION["success"] = $msg ; 
			// }
		
			// }

	// header("location:".HOST_URL."adminpannel/script-installation1.php?project=".$project_id) ;
	// die();
	}



$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}


?>
<?php require_once("inc/style-and-script.php") ; ?>
<script src="https://js.stripe.com/v2/"></script>
<?php $payment_gateway = getTableData( $conn , " payment_gateway " , " name LIKE 'stripe' " ) ; ?>
<script>
	Stripe.setPublishableKey('<?php echo $payment_gateway["public_key"]; ?>');
</script>
		<style>

		/* Mark input boxes that gets an error on validation: */
		.invalid {
		  background-color: #ffdddd;
		}

		/* Hide all steps by default: */
		.tab {
		  display: none;
		}

		button {
		  background-color: #04AA6D;
		  color: #ffffff;
		  border: none;
		  padding: 10px 20px;
		  font-size: 17px;
		  font-family: Raleway;
		  cursor: pointer;
		}

		button:hover {
		  opacity: 0.8;
		}

		#prevBtn {
		  background-color: #bbbbbb;
		}

		/* Make circles that indicate the steps of the form: */
		.step {
		  height: 15px;
		  width: 15px;
		  margin: 0 2px;
		  background-color: #bbbbbb;
		  border: none;  
		  border-radius: 50%;
		  display: inline-block;
		  opacity: 0.5;
		}

		.step.active {
		  opacity: 1;
		}

		/* Mark the steps that are finished and valid: */
		.step.finish {
		  background-color: #04AA6D;
		}
		</style>
	</head>
<body class="custom-tabel">
	<?php

		$user_id = $_SESSION['user_id'] ;
		$project_id = $_GET["project"] ;

		// $website = $_GET["website"] ;

		$website_data = getTableData( $conn , " boost_website " , " id = '$project_id' " ) ;

		$is_data = getTableData( $conn , " check_installation " , " website_id = '$project_id' " ) ;
		// print_r($website_data) ;

	?>

	<div class="d-flex" id="wrapper">
	<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>
		<!-- Page content wrapper-->
		<div id="page-content-wrapper">
			<?php require_once("inc/topbar.php"); ?>
			<!-- Page content-->
			<div class="container-fluid script_I content__up">
				<h1>Payment</h1>
			   <div class="form_h">
				<?php require_once("inc/alert-status.php") ; ?>

				<form  method="post" id="speed_payment" >

											<input type="hidden" name="item-name" id="item-name" value="<?= $speed_plans['interval']." warranty"?>">
											<input type="hidden" name="amount" id="amount" value="<?=$speed_plans['s_price']?>">
											<input type="hidden" name="currency" id="currency" value="USD">

											<div class="form-group">
												<label>Name on Card</label>
												<input type="text" name="hire-cardname" id="card" placeholder="Name On Card" class="form-control hire-us-input" required >
											</div>
											<div class="form-group">
												<label>Card Number</label>
												<input type="text" name="hire-cardnumber" id="cardNumber" placeholder="Enter Card Number" class="form-control hire-us-input" required >
											</div>
											
												<div class="form-group">
													<label>MM/YYYY</label>
													<div class="row">
														<div class="col-md-6">
														<input type="text" name="hire-cardmm" id="cardExpMonth" placeholder="MM" class="form-control" maxlength="2" ></div>
														<div class="col-md-6">

														<input type="text" name="hire-cardyyyy" id="cardExpYear" placeholder="YYYY" class="form-control hire-us-input" maxlength="4" >
														</div>
													</div>
												</div>
												<div class="form-group ">
													<label>CVV</label>
													<input type="text" name="hire-cardcvv" id="cardCVC" placeholder="CVV" class="form-control hire-us-input" maxlength="5" >
												</div>
												<div class="form_h_submit">
											
											<input type="button" name="payment_method" onclick="nextPrev(1)" value="Pay" class="btn btn-primary">
	</div>
				</form>
	</div>
				</div>

			</div>
		</div>
	</div>
</body>
</html>

<script>




function nextPrev(n) {


	var x = document.getElementsByClassName("tab");
	// Exit the function if any field in the current tab is invalid:
	if (n == 1 && !validateForm()) return false;

}

function validateForm() {
		var valid = true;

			if ( $('#cardNumber').length > 0 ) {
				Stripe.createToken({
					number:$('#cardNumber').val(),
					cvc:$('#cardCVC').val(),
					exp_month : $('#cardExpMonth').val(),
					exp_year : $('#cardExpYear').val()
				}, stripeResponseHandler);
			}
			else{
			valid = false;	
			}


			return valid;

}



function stripeResponseHandler(status, response) {
	
		var stripeToken = response['id'];

		if ($("input[name='stripeToken']").length > 0) {
			$("input[name='stripeToken']").val(stripeToken) ;
		}
		else {
			$('#speed_payment').append("<input type='hidden' name='stripeToken' value='" + stripeToken + "' />");
		}
		$('#speed_payment').submit();
	
}



       $('#cardNumber').on('keypress change blur', function () {
    $(this).val(function (index, value) {
        return value.replace(/[^a-z0-9]+/gi, '').replace(/(.{16})/g, '$1 ');
    });
});

if ( $("#cardExpMonth").length > 0 ) {
    document.getElementById('cardExpMonth').addEventListener('keypress', event => {
        if (!`${event.target.value}${event.key}`.match(/^[0-9]{0,2}$/)) {
            // block the input if result does not match
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    });
}

if ( $("#cardExpYear").length > 0 ) {
    document.getElementById('cardExpYear').addEventListener('keypress', event => {
        if (!`${event.target.value}${event.key}`.match(/^[0-9]{0,4}$/)) {
            // block the input if result does not match
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    });
}

if ( $("#cardCVC").length > 0 ) {
    document.getElementById('cardCVC').addEventListener('keypress', event => {
        if (!`${event.target.value}${event.key}`.match(/^[0-9]{0,3}$/)) {
            // block the input if result does not match
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    });
}



</script>
