<?php 
include('config.php');

if( isset($_POST['resetpass'])) {

    $confirmpassword = $_POST['confirmpassword'];
    $password = $_POST['password'];
        $flag = 0;


    if(isset($_GET['token'])) {
        $token = $_GET['token'];

    if (strlen($_POST["password"]) < '8') {
        $flag = 1;
        $passwordErr = "Your Password Must Contain At Least 8 Characters using 1 lowercase letters, 1 Number, 1 Uppercase letters and 1 special characters(e.g., a–z, A–Z,0-9 !@#$%^&)!";
    }
    if (strlen($_POST["password"]) > '48') {
        $flag = 1;
        $passwordErr = "Your Password Must Be Less Than Or Equal To 48  Characters!";
    }    
     elseif (!preg_match("#[0-9]+#", $password)) {
        $flag = 1;
        $passwordErr = "Your Password Must Contain At Least 1 Number!";
    } elseif (!preg_match("#[A-Z]+#", $password)) {
        $flag = 1;
        $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
    } elseif (!preg_match("#[a-z]+#", $password)) {
        $flag = 1;
        $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
    }else{
        if ($flag == 0) {

        if($password == $confirmpassword){
            $hashpassword = md5($password);
            $sql = "UPDATE admin_users SET token ='', password='".$hashpassword."' WHERE token='".$token."'";
            $result = $conn->query($sql); 

            if ( $result ) {
                $_SESSION['success'] = 'Password successfully updated! You can now log in to your account.';
                header( "refresh:2;url=".HOST_URL."adminpannel/" );
            }
        }
        else{
            $_SESSION['error'] = 'password Not matech!';
        }
    }
    }

    }
    else{
        $_SESSION['error'] = 'Token is not valid!';
    }
if ($flag == 0) {
    header("location: ".HOST_URL."adminpannel/") ;
    die() ;
}
}


   $token = $_GET['token'];
   $sqlt = "SELECT id from admin_users  WHERE token='".$token."'";
   $resultt = $conn->query($sqlt);
    if($resultt->num_rows <= 0){
         $_SESSION['error'] = 'Your password reset link has expired.';
        header("location: ".HOST_URL."adminpannel/forgetpassword.php") ;
        die() ;         
    }

?>
<?php require_once("inc/style-and-script.php") ; ?>
<!-- <html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" ></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" ></script> -->
        <style type="text/css">
    /* The message box is shown when the user clicks on the password field */
#message {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#message p {
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
    <body class="custum_liquid">
       
        <div class="form-wrapper">
            <div class="loginlgimg"><img id="loginimg" src="<?=HOST_URL?>adminpannel/img/sitelogo.png"></div>
            <?php require_once("inc/alert-status.php") ; ?>
            <form action="#" method="post">
                <h3 style="color: #000;" class="reset-pas">Reset Password here</h3>
                <div class="form-item">
                <label for="email">Password</label>
                    <input type="password" id="password" name="password"  maxlength="48" required="required" placeholder="Password" autofocus required></input>
                </div>
                <div class="form-item">
                <label for="email">Confirm password</label>
                    <input type="password" id="password2" name="confirmpassword"  maxlength="48" required="required" placeholder="Confirm Password" required></input>
                </div>
                <span id="erromsgs" style="color:red;"><?php if($flag == 1){echo $passwordErr;} ?></span>
                <div id="message">
                        <h3>Password must contain the following:</h3>
                        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                        <p id="capital" class="invalid">A <b>uppercase</b> letter</p>
                        <p id="number" class="invalid">A <b>number</b></p>
                        <p id="length" class="invalid">Minimum <b>8 characters</b></p>
                        </div>
                <div class="button-panel">
                    <input type="submit" class="button"  onclick =" return abc()" name="resetpass" value="Reset Password"></input>
                </div>
            </form>
            <div class="login">
                <a href="<?=HOST_URL;?>adminpannel/">Login</a>
            </div>
        </div>
    </body>
</html>

<script>

 function abc()
 {

var pass = document.getElementById("password").value;
var pass2 = document.getElementById("password2").value;
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

else if(pass.length<5)
{
errormsg.innerHTML="Your Password Must Contain At Least 5 Characters!";
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

  document.getElementById("message").style.display = "block";


// When the user clicks outside of the password field, hide the message box
setInterval(abc, 1000);

// When the user starts to type something inside the password field
function abc() {
var myInput = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");
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
