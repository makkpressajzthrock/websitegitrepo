<?php 
session_start();
 include('../adminpannel/session.php');

// Include configuration file  
require_once 'config.php'; 
 
// Include the database connection file 
include_once 'dbConnect.php'; 

require_once("../adminpannel/config.php") ;
require_once('../adminpannel/inc/functions.php') ;

require_once("Common.php") ;


if(isset($_SESSION['user_id']) && !empty($_SESSION["user_id"]))
{}
else{
 if (isset($_SERVER["HTTP_REFERER"])) {
        $_SESSION['error'] = "Plan Not loading please try again.";
        header("Location: " . $_SERVER["HTTP_REFERER"]);

        die;
    }
}

  $id = base64_decode($_GET['id']) ;
  $sid = base64_decode($_GET['sid']) ;

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
$addon = getTableData( $conn , " addon " , " id ='$id' " ) ;

$site_id = getTableData( $conn , " boost_website " , " id ='$sid' " ) ;

$subscription = $site_id['subscription_id'];

$user_subscriptions = getTableData( $conn , " user_subscriptions " , " id ='$subscription' " ) ;
$customer = $user_subscriptions['stripe_customer_id'];

$total_price = $addon['price'];

$fName = $row['firstname'];
$email = $row['email'];

?>

<script src="https://js.stripe.com/v3/"></script>
<script src="js/checkout_addon.js" STRIPE_PUBLISHABLE_KEY="<?php echo STRIPE_PUBLISHABLE_KEY; ?>" defer></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<div class="payment_addon_s_wrapper">
<div class="payment_addon_s">
<div class="glass"></div>
		<div class="glass"></div>
		<div class="glass"></div>
		<div class="glass"></div>
		<div class="glass"></div>
		<div class="glass"></div>
		<div class="glass"></div>
		<div class="glass"></div>
<div class="container">
<?php 


  ?>

        <div class="logo-cus"><img src="https://ecommerceseotools.com/ecommercespeedy/adminpannel/img/sitelogo.png" alt="Ecommercespeedy Logo"></div>
    <div class="panel-heading">
        <h3 class="panel-title">Subscription with Stripe</h3>
        
        <div class="payble_amt">Payble Amount: <?php echo  '$ '.$total_price; ?></div>
        <br>
        <!-- Plan Info -->
        <div style="display: none;">
            <b>Select Plan:</b>
             

        </div>
    </div>
    <div class="panel-body">
        <!-- Display status message -->
        <div id="paymentResponse" class="hidden"></div>
     
        <!-- Display a subscription form -->
        <form id="subscrFrm">
             

            <div class="form-group">
                <label>NAME</label>
                <input type="text" id="name" class="form-control" value="<?php echo $fName; ?>" placeholder="Enter name" required="" autofocus="">
            </div>
            <div class="form-group">
                <label>EMAIL</label>
                <input type="email" id="email" class="form-control" value="<?php echo $email; ?>" placeholder="Enter email" required="">
            </div>
            
            <div class="form-group">
                <label>CARD INFO</label>
                <div id="card-element" class="form-control">
                    <!-- Stripe.js will create card input elements here -->
                </div>
            </div>
            


             <div class="form-group">
                <input type="hidden" id="subscr_plan" class="form-control" value="<?php echo $id; ?>"  >
                <input type="hidden" id="t_Price" class="form-control" value="<?php echo $total_price; ?>"  >
                <input type="hidden" id="customer_id" class="form-control" value="<?php echo $customer; ?>"  >
                <input type="hidden" id="site_id" class="form-control" value="<?php echo $sid; ?>"  >
            </div>

            <!-- Form submit button -->
            <button id="submitBtn" class="btn btn-success">
                <div class="spinner hidden" id="spinner"><svg version="1.1" id="L7" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"> <path fill="#fff" d="M31.6,3.5C5.9,13.6-6.6,42.7,3.5,68.4c10.1,25.7,39.2,38.3,64.9,28.1l-3.1-7.9c-21.3,8.4-45.4-2-53.8-23.3 c-8.4-21.3,2-45.4,23.3-53.8L31.6,3.5z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform> </path> <path fill="#fff" d="M42.3,39.6c5.7-4.3,13.9-3.1,18.1,2.7c4.3,5.7,3.1,13.9-2.7,18.1l4.1,5.5c8.8-6.5,10.6-19,4.1-27.7 c-6.5-8.8-19-10.6-27.7-4.1L42.3,39.6z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="-360 50 50" repeatCount="indefinite"></animateTransform> </path> <path fill="#fff" d="M82,35.7C74.1,18,53.4,10.1,35.7,18S10.1,46.6,18,64.3l7.6-3.4c-6-13.5,0-29.3,13.5-35.3s29.3,0,35.3,13.5 L82,35.7z"> <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform> </path> </svg></div>
                <span id="buttonText">Proceed</span>
            </button>
        </form>
        
        <!-- Display processing notification -->
        <div id="frmProcess" class="hidden">
            <span class="ring"></span> Processing...
        </div>
    </div>
</div>
</div>
</div>

