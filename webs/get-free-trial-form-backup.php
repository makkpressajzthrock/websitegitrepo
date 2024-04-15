<?php

require_once("config.php") ;

if ( isset($_POST["get-free-trial"]) ) {

	// echo "<pre>"; print_r($_SESSION) ; print_r($_POST) ;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	$firstName = $_POST['first-name'] ;
	$lastName = $_POST['last-name'] ;
	$addressLine1 = $_POST['address-line-1'] ;
	$addressLine2 = $_POST['address-line-2'] ;
	$city = $_POST['city'] ;
	$state = $_POST['state'] ;
	$zipCode = $_POST['zip-code'] ;
	$country = $_POST['country'] ;
	$phone = $_POST['phone'] ;

	if ( empty($firstName) || empty($lastName) || empty($addressLine1) || empty($city) || empty($state) || empty($zipCode) || empty($country) || empty($phone) ) {
		$_SESSION['error'] = "Please fill all required values." ;
	}
	else 
	{
		$query = $conn->query(" SELECT * FROM `subscription_plan` WHERE `s_type` LIKE 'Free' AND status = 1 ") ;
		$plan_data = $query->fetch_assoc() ;
		// print_r($plan_data) ;

		$plan_id = $plan_data["id"] ;
		$plan_duration = $plan_data["s_duration"] ;
		$plan_date = date('Y-m-d H:i:s') ;
		$user_id = $_SESSION["user_id"] ;


		$sql = " INSERT INTO `user_subscription`( `user_id`, `plan_id`, `charge_id`, `plan_active_date` ) VALUES ( '$user_id' , '$plan_id' , '11111111' , '$plan_date' ) " ;

		if ( $conn->query($sql) === TRUE ) {
			$_SESSION['success'] = " Hurray! ".$plan_duration." Days free trial activated successfully." ;
			header("location: ".HOST_URL."adminpannel/home.php");
			die();
		}
		else {
			$_SESSION['error'] = "Operation failed." ;
			$_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
		}
	}

	header("location: ".HOST_URL."get-free-trial.php");
	die() ;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
	<?php require_once('inc/style-script.php'); ?>

<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

/* Mark input boxes that gets an error on validation: */
.invalid {
  background-color: #ffdddd;
}

</style>
</head>
<body>

<div class="container">

	<?php require_once('inc/alert-message.php') ; ?>

<div> <a href="<?=HOST_URL?>plan.php" class="btn btn-primary">Back</a></div>

<?php

// get free plan data
$query = $conn->query(" SELECT * FROM `subscription_plan` WHERE `s_type` LIKE 'Free' AND status = 1 ") ;

if ( $query->num_rows > 0 ) 
{
	$plan_data = $query->fetch_assoc() ;

	// print_r($plan_data) ;

	?>
	<form method="POST" id="free-trial-form" >
		<div>
			<h3>Activate your <?=$plan_data["s_duration"]?> days free trial</h3>
			<p>Please fill the below details.</p>
		</div>

		<div class="form-group">
			<label>Full Name</label>
			<div style="column-count: 2;">
				<input type="text" class="form-control input-required" name="first-name" placeholder="First name">
				<input type="text" class="form-control input-required" name="last-name" placeholder="Last name">
			</div>
		</div>

		<div class="form-group">
			<label>Address :</label>
			<div style="column-count: 2;">
				<input type="text" class="form-control input-required" id="address-line-1" name="address-line-1" placeholder="Address Line 1">
				<input type="text" class="form-control" id="address-line-2" name="address-line-2" placeholder="Address Line 2 (optional) ">
				<input type="text" class="form-control input-required" id="city" name="city" placeholder="Enter City">
				<input type="text" class="form-control input-required" id="state" name="state" placeholder="Enter State">
				<input type="text" class="form-control input-required" id="zip-code" name="zip-code" placeholder="ZIP or postal code">
				<input type="text" class="form-control input-required" id="country" name="country" placeholder="Enter Country">
			</div>
		</div>

		<div class="form-group">
			<label for="phone">Phone</label>
			<input type="tel" class="form-control input-required" id="phone" name="phone" placeholder="">
		</div>

		<div class="form-group">
			<button class="btn btn-primary" type="submit" name="get-free-trial">Get Free Trial</button>
		</div>

	</form>
	<?php	
}
else {
	?><div>No free plan</div><?php
}

?>



</div>


</body>
</html>

<script>

$(document).ready(function() {

	$("#free-trial-form").submit(function(e){
		

		$(".input-required").removeClass("invalid") ;

		var valid = true ;

		$(".input-required").each(function(i,o)
		{
			var v = $(o).val() ;
			if ( v == undefined || v == '' || v == null ) {
				$(o).addClass("invalid") ;
				valid = false;
			}
		});

		var phone = $("#phone").val() ;
		if ( phone == undefined || phone == '' || phone == null ) {
			$("#phone").addClass("invalid") ;
			valid = false;
		}
		else if ( !isPhoneValid(phone) ) {
			$("#phone").addClass("invalid") ;
			valid = false;
		}

		if ( ! valid ) { e.preventDefault() ; }

	});

});

function isPhoneValid(userInput) {

  var res = userInput.match(/^[6-9]\d{9}$/g);
  if(res == null)
      return false;
  else
      return true;
}

</script>


