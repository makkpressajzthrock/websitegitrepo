<?php

require_once("config.php");
require_once("inc/functions.php");
 
$analyze = $_GET['ref'];

 $user_id = $_SESSION['user_id'];


  if(isset($_POST['unsubscribed'])){

    $why = $_POST['why'];
   $conn->query("UPDATE admin_users set subscribe_email = 0, why_unsubscribe = '".mysqli_real_escape_string($conn, $why)."' where id = '$user_id'");
     
}
 

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
 

if ( empty(count($row)) ) {
    header("location: ".HOST_URL."adminpannel/");
    die() ;
}
 


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unsubscribed</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    <?php require_once('../inc/style-script.php'); ?>
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.js"></script> 
</head>
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
<body>
    <div class="singup_wrapper customize_wrapper">
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="glass"></div>
        <div class="container singup_container basic__details unsubscribe">



<?php 
if($row['subscribe_email']==1){
?>
            <div class="signup">
                    <div class="signup_logo">
                            <a href="<?=HOST_URL?>signup.php" ><img src="../img/signup_logo.png"></a>
                    </div>
                

                <?php

                require_once('../inc/alert-message.php');

                ?>
                    <form method="post" class="basic__details__form">

                        <div class="form-group">
                            <div class="row flex-col">

                                <div class="col-12">
                                    <h2>Are you sure!</h2>
                                    <p>You want to unsubscribe email.</p>
                                    
                                    <div class="reason m-2">
                                      <textarea class="border reason-box form-control" placeholder="Add Reason why you want to unsubscribed." required name="why"></textarea>

                                    </div>
                                 
                                      <button name="unsubscribed" class="btn btn-primary">Yes</button>   
                                      <a href="/" class="btn no_unsubscribe">No</a>                          
                                </div>
 
                        </div>

                    
                    </form>
                 


            </div> 

<?php }else{ ?>


            <div class="signup">
                    <div class="signup_logo">
                            <a href="<?=HOST_URL?>signup.php" ><img src="../img/signup_logo.png"></a>
                    </div>
                

              

                <?php

               

                ?>
                    <form method="post" class="basic__details__form">

                        <div class="form-group">
                            <div class="row flex-col">

                                <div class="col-12">
                                    <h2>Sorry to see you go!</h2>
                                    <p>You've successfully unsubscribed from Website Speedy marketing emails.</p>
                                    
                                 
                                                                   
                                </div>
 
                        </div>

                    
                    </form>
                 


            </div>


<?php }?>            

        </div>
    </div>


</body>

</html>


