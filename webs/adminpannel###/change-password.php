<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;


if ( isset($_POST["change-password"]) ) {
	$passwordErr="";

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}

	
	extract($_POST) ;

	$flag=0;

	if ( empty($npassword) || empty($cpassword) ) {
		$_SESSION['error'] = "Please fill all fields!" ;

	}
	elseif(strlen($npassword) > '48') {
        $flag = 1;
        $passwordErr = "Your Password Must Be Less Than Or Equal To 48  Characters!";
        
    }	
	 elseif(strlen($npassword) < '8') {
        $flag = 1;
        $passwordErr = "Your Password Must Contain At Least 8 Characters using 1 lowercase letters, 1 Number, 1 Uppercase letters and 1 special characters(e.g., a–z, A–Z,0-9 !@#$%^&)!";
    } elseif (!preg_match("#[0-9]+#", $npassword)) {
        $flag = 1;
        $passwordErr = "Your Password Must Contain At Least 1 Number!";
    } elseif (!preg_match("#[A-Z]+#", $npassword)) {
        $flag = 1;
        $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
    } elseif (!preg_match("#[a-z]+#", $npassword)) {
        $flag = 1;
        $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
    }




	elseif ( $npassword != $cpassword ) {
		$_SESSION['error'] = "Unmatched password!" ;
	}


 
	else {

 
		$pwd = md5($npassword) ;
        
     if($flag==0){
		if ( updateTableData( $conn , " admin_users " , " password = '$pwd', token ='' " , " id = '".$_SESSION['user_id']."' " ) ) {
			$_SESSION['success_'] = "Password changed successfully!" ;
		}
	}
		else {
			$_SESSION['error_'] = "Operation failed!" ;
			$_SESSION['error_'] = "Error: " . $conn->error;
		}

	}

	if($passwordErr!=""){
		$_SESSION['error_'] = $passwordErr;
	}

	header("location: ".HOST_URL."adminpannel/change-password.php") ;
	die() ;
}


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

		$plan_country = "";
		if($row['country'] !=""){
			if($row['country'] != "101"){
				$plan_country = "-us";
			}
		}
		elseif($row['country_code'] != "+91"){
			$plan_country = "-us";
		}
// Show Expire message //
	include("error_message_bar_subscription.php");
// End Show Expire message //	


?>

		<?php require_once('inc/style-and-script.php') ; ?>
		
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
		</style>
		<style type="text/css">
	/* The message box is shown when the user clicks on the password field */
#cmessage {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#cmessage p {
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
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up change_pass">
					
					<h1>Change Password</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="form_h">
					
					<form method="POST">
						
						<div class="form-group">
							<label for="npassword">New Password</label>
							<input type="password" class="form-control" maxlength="48" id="npassword" name="npassword" required>
						</div>

						<div class="form-group">
							<label for="cpassword">Confirm Password</label>
							<input type="password" class="form-control"  maxlength="48" id="cpassword" name="cpassword" required>
						</div>
						 <span id="erromsgs" style="color:red;"><?php if($flag == 1){echo $passwordErr;} ?></span>
								<div id="cmessage">
						<h3>Password must contain the following:</h3>
						<p id="cletter" class="invalid">A <b>lowercase</b> letter</p>
						<p id="ccapital" class="invalid">A <b>uppercase</b> letter</p>
						<p id="cnumber" class="invalid">A <b>number</b></p>
						<p id="clength" class="invalid">Minimum <b>8 characters</b></p>
						</div>
                        <div class="form_h_submit">
						<button type="submit" name="change-password" onclick =" return abc() "  class="btn btn-primary">Change Password</button>
                        </div>
					</form>
		</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">


function abc()
 {

var pass = document.getElementById("npassword").value;
var pass2 = document.getElementById("cpassword").value;
var errormsg = document.getElementById("erromsgs");

// alert(pass2);

for(i=0;i<errormsg.length;i++)
{
errormsg.innerHTML="";
}

 if(!pass)
{
errormsg.innerHTML="Please Enter Password !";
return false;
}

else if(pass.length<8)
{
errormsg.innerHTML="Your Password Must Contain At Least 8 Characters!";
return false;
}

else if(pass.length>48)
{
errormsg.innerHTML="Your Password Must Be Less Than Or Equal To 48  Characters!";
return false;
}


else if(pass.search(/[A-Z]/)==-1)
{
errormsg.innerHTML="Your Password Must Contain At Least 1 Capital Letter!";
return false;
}

else if(pass.search(/[0-9]/)==-1)
{
errormsg.innerHTML="Your Password Must Contain At Least 1 Number!";
return false;
}
else if(pass.search(/[a-z]/)==-1)
{
errormsg.innerHTML="Your Password Must Contain At Least 1 Lowercase Letter!";
return false;
}
// else if(pass.search(/[!@#$%^&*]/)==-1)
// {
// errormsg.innerHTML="One Special Character";
// return false;
// }

}

			$(document).ready(function () {
				
// When the user clicks on the password field, show the message box

  document.getElementById("cmessage").style.display = "block";


// When the user clicks outside of the password field, hide the message box
setInterval(abc, 1000);

// When the user starts to type something inside the password field
function abc() {
var myInput = document.getElementById("npassword");
var letter = document.getElementById("cletter");
var capital = document.getElementById("ccapital");
var number = document.getElementById("cnumber");
var length = document.getElementById("clength");

   // console.log(myInput);
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
		</script>
	</body>
</html>