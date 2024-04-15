<div class="payment_addon_s_wrapper right "  style="width: 50%">



<?php  
session_start();
 include('../adminpannel/session.php');
require '../adminpannel/smtp-send-grid/vendor/autoload.php';
// Include configuration file  
require_once 'config.php'; 
 
// Include the database connection file 
$country = "IND";
include_once 'dbConnect.php'; 

require_once("../adminpannel/config.php") ;
require_once('../adminpannel/inc/functions.php') ;

require_once("Common.php") ;
 
// Fetch plans from the database 
$sqlQ = "SELECT * FROM plans"; 
$stmt = $db->prepare($sqlQ); 
$stmt->execute(); 
$result = $stmt->get_result(); 

$count_site = 1;

$_SESSION["count_site"] = $count_site;


$sideEncode =  base64_encode($resultCart['website_id']);

if(isset($_SESSION['user_id']) && !empty($_SESSION["user_id"]))
{}
else{
 if (isset($_SERVER["HTTP_REFERER"])) {
        $_SESSION['error'] = "Plan Not loading please try again.";
        header("Location: " . $_SERVER["HTTP_REFERER"]);

        die;
    }
}

$common = new Common();
$allCountries = $common->getCountries($conn);

 
 
$subscription = $resultCart['subscription_id'];

$sqlPrice = "SELECT * FROM `plans` WHERE `id` = $subscription";
$resultPrices = mysqli_query($conn, $sqlPrice);
$priceValue = mysqli_fetch_assoc($resultPrices);
$price = $priceValue['s_price'];
$price = number_format((float)$price, 2, '.', '');

$total_tax = 0;
 $sqlTax = "SELECT * FROM `add-tax` WHERE `country_name` ='India'";
$resultTax = mysqli_query($conn, $sqlTax);
$priceTax = mysqli_fetch_assoc($resultTax);
$tax = $priceTax['tax_rate'];
$total_tax = $price*$tax/100;
$total_price = $price + $total_tax;

$total_price = number_format((float)$total_price, 2, '.', '');
$total_tax = number_format((float)$total_tax, 2, '.', '');



$managerId=$_SESSION['user_id'];

$sele2 = "SELECT * FROM `billing-address` WHERE `manager_id` = '".$managerId."'";
$sele_con2 = mysqli_query($conn, $sele2);
$sele_run2 = mysqli_fetch_assoc($sele_con2);

if($sele_run2['email']==""){
  $sele_run2['email'] = $payEmail;
}
 
?>


<script src="https://js.stripe.com/v3/"></script>
<script src="js/checkout_ind.js" STRIPE_PUBLISHABLE_KEY="<?php echo STRIPE_PUBLISHABLE_KEY; ?>" defer></script>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="../adminpannel/js/scripts.js"></script>

<style>
   /* .panel{
    display: none;
   } */

</style>
<!-- Billing Data -->
<div class="payment_addon_s_wrapper" >

    <div class="payment_addon_s">
 

<div id="payment__container" class="containers" style="width: 100%">

<?php
// for trialed subscription//123
if ( $resultCart["with_trial"] == 1 && ($resultCart["subscription_id"] <= '28' ) ) {

    ?>
    <div class="alert alert-info" role="alert" style="order: 1;">
        <p>Start your 7-day free trial! Add your card details below – you won't be charged until the trial period ends. Cancel anytime.</p>        
    </div>
    <?php
    //123
}else if($resultCart["subscription_id"]== '29' || $resultCart["subscription_id"]== '30'){ ?>
    <div class="alert alert-info" role="alert" style="order: 1;">
        <p>This plan will give you 100 views per month. Please click on continue for activate this</p>        
    </div>

<?php } ?>

<?php 


  ?>
    <div class="panel-heading">
<!--             <div class="order_summary">
                <h4>Order Summary</h4>
                <div class="order">
                  <p class="text"><span><?=$priceValue['name'];?></span> / <span><?=ucfirst($priceValue['interval']);?></span></p>
                  <p class="amount">₹ <?=$price?></p>
                </div>

                <div class="order discount"></div>

              <?php
               if($total_tax!=0){
              ?>  
                <div class="order">
                  <p class="text"><span><?=$priceTax['tax_name'];?></span> (<span><?=ucfirst($priceTax['tax_rate']);?></span>%)</p>
                  <p class="amount gst" default="₹ <?=$total_tax?>" >₹ <?=$total_tax?></p>
                </div>
              <?php  
              }
              ?>


                <div class="total">
                  <span>Total Amount :</span>₹ 
                  <span class="payble_amt_val"><?=$total_price?></span>
                  
                </div>
              </div> -->
        
        <div class="payble_amt" style="display: none;">Payble Amount: <?php echo  '₹ <span class="payble_amt_val">'.$total_price.'</span>'; ?></div>
        
        <!-- Plan Info -->
        <div style="display: none;">
            <b>Select Plan:</b>
        </div>
    </div>
    <div class="panel-body">
        <!-- Display status message -->
        <div id="paymentResponse" class="hidden"></div>

        <?php
        // for trialed subscription
        if ( $resultCart["with_trial"] == 1  && ($resultCart["subscription_id"] <= '28' )) {

            ?>
            <!-- Display a trial subscription form -->
            <form id="subscrTrialFrm">

                <input type="hidden" name="with_trial" id="with_trial" value="<?=empty($resultCart["with_trial"])?0:$resultCart['with_trial'];?>" />

                <?php 

                $currentURL = "http" . (isset($_SERVER['HTTPS']) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

                ?>

                <input type="hidden" name="cancel_url" id="cancel_url" value="<?=$currentURL;?>" />


                <input type="hidden" name="change_id" id="change_id" value="<?php echo $resultCart['change_id']; ?>" />
                <input type="hidden" id="subscr_plan" class="form-control" name="subscription" placeholder="Plan" required="" value="<?php echo $resultCart['subscription_id'];  ?>">
                <input type="hidden" id="price_cal" class="form-control" value="<?php echo $price; ?>"  >
                <input type="hidden" id="tax_price" class="form-control" value="<?php echo $total_tax; ?>"  >
                <input type="hidden" id="t_Price" class="form-control" value="<?php echo $price; ?>"  >
                <input type="hidden" id="coupon_id" class="form-control" value=""  >
                <input type="hidden" id="discount_amount" class="form-control" value=""  >
                <input type="hidden" id="sid_id"  class="form-control" value="<?php echo $resultCart['website_id']; ?>" />   

                <div class="badges__container">
                    <!-- Form submit button -->
                    <button id="submitBtn" class="btn btn-success">
                        <div class="spinner hidden" id="spinner">
                            <?xml version="1.0" encoding="UTF-8" standalone="no"?>
                            <svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px" viewBox="0 0 128 16" xml:space="preserve">
                                <path fill="#949494" d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z"/>
                                <g>
                                    <path fill="#949494" d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z"/>
                                    <animateTransform attributeName="transform" type="translate" values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0" calcMode="discrete" dur="1170ms" repeatCount="indefinite"/>
                                </g>
                            </svg>
                        </div>
                        <span id="buttonText">Start My Free Trial - Billing After 7 Days</span>
                    </button>
                    <div class="payment_container">
                        <div class="top_section">
                            <!--?xml version="1.0" encoding="utf-8"?-->
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="96.108px" height="122.88px" viewBox="0 0 96.108 122.88" enable-background="new 0 0 96.108 122.88" xml:space="preserve">
                                <g>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.892,56.036h8.959v-1.075V37.117c0-10.205,4.177-19.484,10.898-26.207v-0.009 C29.473,4.177,38.754,0,48.966,0C59.17,0,68.449,4.177,75.173,10.901l0.01,0.009c6.721,6.723,10.898,16.002,10.898,26.207v17.844 v1.075h7.136c1.59,0,2.892,1.302,2.892,2.891v61.062c0,1.589-1.302,2.891-2.892,2.891H2.892c-1.59,0-2.892-1.302-2.892-2.891 V58.927C0,57.338,1.302,56.036,2.892,56.036L2.892,56.036z M26.271,56.036h45.387v-1.075V36.911c0-6.24-2.554-11.917-6.662-16.03 l-0.005,0.004c-4.111-4.114-9.787-6.669-16.025-6.669c-6.241,0-11.917,2.554-16.033,6.665c-4.109,4.113-6.662,9.79-6.662,16.03 v18.051V56.036L26.271,56.036z M49.149,89.448l4.581,21.139l-12.557,0.053l3.685-21.423c-3.431-1.1-5.918-4.315-5.918-8.111 c0-4.701,3.81-8.511,8.513-8.511c4.698,0,8.511,3.81,8.511,8.511C55.964,85.226,53.036,88.663,49.149,89.448L49.149,89.448z"></path>
                                </g>
                            </svg>
                            <h4>Guaranteed Safe and Secure Checkout</h4>
                            <p class=" dummy">Powered By <span>stripe</span></p>
                        </div>
                        <div class="bottom_section">
                            <div class="visa payment-option">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="144px" height="144px">
                                    <path fill="#1565C0" d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z"></path>
                                    <path fill="#FFF" d="M15.186 19l-2.626 7.832c0 0-.667-3.313-.733-3.729-1.495-3.411-3.701-3.221-3.701-3.221L10.726 30v-.002h3.161L18.258 19H15.186zM17.689 30L20.56 30 22.296 19 19.389 19zM38.008 19h-3.021l-4.71 11h2.852l.588-1.571h3.596L37.619 30h2.613L38.008 19zM34.513 26.328l1.563-4.157.818 4.157H34.513zM26.369 22.206c0-.606.498-1.057 1.926-1.057.928 0 1.991.674 1.991.674l.466-2.309c0 0-1.358-.515-2.691-.515-3.019 0-4.576 1.444-4.576 3.272 0 3.306 3.979 2.853 3.979 4.551 0 .291-.231.964-1.888.964-1.662 0-2.759-.609-2.759-.609l-.495 2.216c0 0 1.063.606 3.117.606 2.059 0 4.915-1.54 4.915-3.752C30.354 23.586 26.369 23.394 26.369 22.206z"></path>
                                    <path fill="#FFC107" d="M12.212,24.945l-0.966-4.748c0,0-0.437-1.029-1.573-1.029c-1.136,0-4.44,0-4.44,0S10.894,20.84,12.212,24.945z"></path>
                                </svg>
                            </div>
                            <div class="mastercard payment-option">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="144px" height="144px">
                                    <linearGradient id="NgmlaCv2fU27PJOuiUvQVa" x1="20.375" x2="28.748" y1="1365.061" y2="1394.946" gradientTransform="translate(0 -1354)" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#00b3ee"></stop>
                                        <stop offset="1" stop-color="#0082d8"></stop>
                                    </linearGradient>
                                    <path fill="url(#NgmlaCv2fU27PJOuiUvQVa)" d="M43.125,9H4.875C3.287,9,2,10.287,2,11.875v24.25C2,37.713,3.287,39,4.875,39h38.25 C44.713,39,46,37.713,46,36.125v-24.25C46,10.287,44.713,9,43.125,9z"></path>
                                    <circle cx="17.053" cy="24.053" r="10.053" fill="#cf1928"></circle>
                                    <linearGradient id="NgmlaCv2fU27PJOuiUvQVb" x1="20" x2="40.107" y1="24.053" y2="24.053" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#fede00"></stop>
                                        <stop offset="1" stop-color="#ffd000"></stop>
                                    </linearGradient>
                                    <circle cx="30.053" cy="24.053" r="10.053" fill="url(#NgmlaCv2fU27PJOuiUvQVb)"></circle>
                                    <path fill="#d97218" d="M20,24.053c0,3.072,1.382,5.818,3.553,7.662c2.172-1.844,3.553-4.59,3.553-7.662 s-1.382-5.818-3.553-7.662C21.382,18.235,20,20.981,20,24.053z"></path>
                                </svg>
                            </div>
                            <div class="amex payment-option">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="144px" height="144px">
                                    <path fill="#1976D2" d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z"></path>
                                    <path fill="#FFF" d="M22.255 20l-2.113 4.683L18.039 20h-2.695v6.726L12.341 20h-2.274L7 26.981h1.815l.671-1.558h3.432l.682 1.558h3.465v-5.185l2.299 5.185h1.563l2.351-5.095v5.095H25V20H22.255zM10.135 23.915l1.026-2.44 1.066 2.44H10.135zM37.883 23.413L41 20.018h-2.217l-1.994 2.164L34.86 20H28v6.982h6.635l2.092-2.311L38.767 27h2.21L37.883 23.413zM33.728 25.516h-4.011v-1.381h3.838v-1.323h-3.838v-1.308l4.234.012 1.693 1.897L33.728 25.516z"></path>
                                </svg>
                            </div>
                            <div class="discover payment-option">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="144px" height="144px">
                                    <radialGradient id="K5rKLmYY5bGzCTT1uDwOPa" cx="-2.043" cy="1356.043" r="53.845" gradientTransform="translate(0 -1354)" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#fafafb"></stop>
                                        <stop offset="1" stop-color="#c8cdd1"></stop>
                                    </radialGradient>
                                    <path fill="url(#K5rKLmYY5bGzCTT1uDwOPa)" d="M43.125,9H4.875C3.287,9,2,10.287,2,11.875v24.25C2,37.713,3.287,39,4.875,39h38.25  C44.713,39,46,37.713,46,36.125v-24.25C46,10.287,44.713,9,43.125,9z"></path>
                                    <path fill="#4a4e52" d="M7.949,25.515c-0.378,0.341-0.869,0.49-1.646,0.49H5.981v-4.077h0.323 c0.777,0,1.249,0.139,1.646,0.499c0.416,0.37,0.666,0.944,0.666,1.535C8.615,24.553,8.365,25.144,7.949,25.515z M6.544,20.883 H4.779v6.165h1.756c0.934,0,1.608-0.22,2.2-0.712c0.703-0.582,1.119-1.459,1.119-2.367C9.854,22.151,8.495,20.883,6.544,20.883"></path>
                                    <path fill="#4a4e52" d="M10.407,27.048h1.203v-6.165h-1.203V27.048z"></path>
                                    <path fill="#4a4e52" d="M14.55,23.249c-0.722-0.267-0.934-0.443-0.934-0.776 c0-0.388,0.378-0.683,0.896-0.683c0.36,0,0.656,0.148,0.97,0.499l0.629-0.824c-0.517-0.452-1.136-0.684-1.812-0.684 c-1.091,0-1.923,0.758-1.923,1.767c0,0.849,0.387,1.284,1.517,1.691c0.471,0.166,0.71,0.277,0.831,0.351 c0.24,0.157,0.361,0.379,0.361,0.638c0,0.5-0.397,0.87-0.934,0.87c-0.574,0-1.036-0.287-1.312-0.822l-0.777,0.748 c0.554,0.813,1.219,1.174,2.134,1.174c1.25,0,2.126-0.831,2.126-2.024C16.324,24.193,15.918,23.749,14.55,23.249"></path>
                                    <path fill="#4a4e52" d="M16.703,23.97c0,1.812,1.423,3.217,3.254,3.217c0.518,0,0.961-0.102,1.508-0.359 v-1.416c-0.481,0.481-0.907,0.675-1.452,0.675c-1.211,0-2.071-0.878-2.071-2.127c0-1.184,0.887-2.117,2.015-2.117 c0.574,0,1.008,0.205,1.508,0.694v-1.415c-0.528-0.268-0.962-0.379-1.479-0.379C18.164,20.744,16.703,22.177,16.703,23.97"></path>
                                    <path fill="#4a4e52" d="M31,25.024l-1.644-4.141h-1.314l2.616,6.323h0.647l2.663-6.323h-1.304L31,25.024"></path>
                                    <path fill="#4a4e52" d="M34.513,27.048h3.41v-1.044h-2.209V24.34h2.127v-1.044h-2.127v-1.368h2.209v-1.045 h-3.41V27.048"></path>
                                    <path fill="#4a4e52" d="M40.272,23.722h-0.351v-1.867h0.37c0.749,0,1.156,0.314,1.156,0.914 C41.447,23.388,41.04,23.722,40.272,23.722z M42.684,22.703c0-1.154-0.795-1.82-2.182-1.82h-1.783v6.165h1.201v-2.477h0.157 l1.665,2.477h1.479l-1.941-2.597C42.186,24.267,42.684,23.647,42.684,22.703"></path>
                                    <path fill="none" d="M21.858,23.988c0,0,0,0,0-0.002c0-1.81,1.468-3.279,3.279-3.279c1.812,0,3.281,1.469,3.281,3.279  c0,0.002,0,0.002,0,0.002c0,1.81-1.469,3.279-3.281,3.279C23.326,27.267,21.858,25.799,21.858,23.988"></path>
                                    <radialGradient id="K5rKLmYY5bGzCTT1uDwOPb" cx="27.473" cy="26.521" r="8.792" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#f5be00"></stop>
                                        <stop offset=".633" stop-color="#c4431f"></stop>
                                        <stop offset=".775" stop-color="#751b0b"></stop>
                                    </radialGradient>
                                    <circle cx="25.143" cy="24" r="3.299" fill="url(#K5rKLmYY5bGzCTT1uDwOPb)"></circle>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
        }
        //123
        else if($resultCart["subscription_id"] == '29' || $resultCart["subscription_id"] == '30' ){ ?>
            <form class="getItNowForm" method="post">
            <input type="hidden" id="getItNowSubscrId" class="form-control" name="getItNowSubscription"   value="<?php echo $resultCart['subscription_id'];  ?>">
            <input type="hidden" id="getItNowWebsiteId" class="form-control" name="getItNowWebsiteId"   value="<?php echo $resultCart['website_id'];  ?>">
            <input type="hidden" id="getItNowUserId" class="form-control" name="getItNowUserId"   value="<?php echo $resultCart['user_id'];  ?>">
            <input type="hidden" id="getItNowCartId" class="form-control" name="getItNowCartId"   value="<?php echo $resultCart['id'];  ?>">
               <button id="submitBtn" class="btn btn-success"> <span  class="getItNow">Continue</span>  </button>
            </form>
               
    
           <?php }
        else {

            ?>
            <!-- Display a subscription form -->
            <form id="subscrFrm">

                <input type="hidden" name="with_trial" id="with_trial" value="<?=empty($resultCart["with_trial"])?0:$resultCart['with_trial'];?>" />

                  <input type="hidden" name="change_id" id="change_id" value="<?php echo $resultCart['change_id']; ?>" />
                    <input type="hidden" id="subscr_plan" class="form-control" name="subscription" placeholder="Plan" required="" value="<?php echo $resultCart['subscription_id'];  ?>">


                <h2>Payment</h2>

                <div class="sm__line__flex" >
                    <div class="form-group">
                        <label>Name <span class="required-star">*</span></label>
                        <input type="text" id="name" class="form-control" value="<?php echo $fName; ?>"  placeholder="Enter name" required="" autofocus="">
                    </div>
                    <div class="form-group">
                        <label>Email <span class="required-star">*</span></label>
                        <input type="email" id="email" class="form-control" value="<?php echo $sele_run2['email']; ?>" placeholder="Enter email" required="">
                    </div>
                </div>
                
                
                <div class="form-group card-info">
                    <label>Card Info <span class="required-star">*</span></label>
                    <div id="card-element" class="form-control">
                        <!-- Stripe.js will create card input elements here -->
                    </div>
                </div>
                
                <div class="sm__line__flex">
                <!-- <div class="form-group">
                    <label>Coupon Code <small class="text-danger" style="color: red;" id="coupon_err"></small></label>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-10">
                                <input type="text" id="coupon_code" class="form-control">
                            </div>
                            <div class="col-2">
                                <button type="button" id="apply_coupon" class="form-control">Apply</button>
                            </div>
                        </div>
                    </div>
                   <div class="col-12" id="applyed_coupon">

                   </div>                

                </div> -->

                
                <!-- <div class="form-group">
                    <label>GST Number <small class="text-danger">(Optional)</small></label>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-10">
                                <input type="text" id="gstNumber" class="form-control">
                            </div>
                        </div>
                    </div>

                </div> -->

                </div>
                 <div class="form-group d__none">
                  <input type="hidden" id="price_cal" class="form-control" value="<?php echo $price; ?>"  >
                  <input type="hidden" id="tax_price" class="form-control" value="<?php echo $total_tax; ?>"  >
                  <input type="hidden" id="t_Price" class="form-control" value="<?php echo $price; ?>"  >
                  <input type="hidden" id="coupon_id" class="form-control" value=""  >
                  <input type="hidden" id="discount_amount" class="form-control" value=""  >
                  <input type="hidden" id="sid_id"  class="form-control" value="<?php echo $resultCart['website_id']; ?>" />   
                </div>


                <div class="badges__container">
                       

                    <!-- Form submit button -->
                    <button id="submitBtn" class="btn btn-success">
                        <div class="spinner hidden" id="spinner"><?xml version="1.0" encoding="UTF-8" standalone="no"?><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px" viewBox="0 0 128 16" xml:space="preserve"><path fill="#949494" d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z"/><g><path fill="#949494" d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z"/><animateTransform attributeName="transform" type="translate" values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0" calcMode="discrete" dur="1170ms" repeatCount="indefinite"/></g></svg>
                        </div>
                        <span id="buttonText">Proceed To Pay</span>
                    </button>

                    <div class="payment_container">
                              <div class="top_section"> 
                                <!--?xml version="1.0" encoding="utf-8"?--><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="96.108px" height="122.88px" viewBox="0 0 96.108 122.88" enable-background="new 0 0 96.108 122.88" xml:space="preserve"><g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.892,56.036h8.959v-1.075V37.117c0-10.205,4.177-19.484,10.898-26.207v-0.009 C29.473,4.177,38.754,0,48.966,0C59.17,0,68.449,4.177,75.173,10.901l0.01,0.009c6.721,6.723,10.898,16.002,10.898,26.207v17.844 v1.075h7.136c1.59,0,2.892,1.302,2.892,2.891v61.062c0,1.589-1.302,2.891-2.892,2.891H2.892c-1.59,0-2.892-1.302-2.892-2.891 V58.927C0,57.338,1.302,56.036,2.892,56.036L2.892,56.036z M26.271,56.036h45.387v-1.075V36.911c0-6.24-2.554-11.917-6.662-16.03 l-0.005,0.004c-4.111-4.114-9.787-6.669-16.025-6.669c-6.241,0-11.917,2.554-16.033,6.665c-4.109,4.113-6.662,9.79-6.662,16.03 v18.051V56.036L26.271,56.036z M49.149,89.448l4.581,21.139l-12.557,0.053l3.685-21.423c-3.431-1.1-5.918-4.315-5.918-8.111 c0-4.701,3.81-8.511,8.513-8.511c4.698,0,8.511,3.81,8.511,8.511C55.964,85.226,53.036,88.663,49.149,89.448L49.149,89.448z"></path></g></svg> 
                                <h4>Guaranteed Safe and Secure Checkout</h4>
                                <p class=" dummy">Powered By <span>stripe</span></p>
                              </div>  
                        
                              <div class="bottom_section">
                                
                                    <div class="visa payment-option">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="144px" height="144px"><path fill="#1565C0" d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z"></path><path fill="#FFF" d="M15.186 19l-2.626 7.832c0 0-.667-3.313-.733-3.729-1.495-3.411-3.701-3.221-3.701-3.221L10.726 30v-.002h3.161L18.258 19H15.186zM17.689 30L20.56 30 22.296 19 19.389 19zM38.008 19h-3.021l-4.71 11h2.852l.588-1.571h3.596L37.619 30h2.613L38.008 19zM34.513 26.328l1.563-4.157.818 4.157H34.513zM26.369 22.206c0-.606.498-1.057 1.926-1.057.928 0 1.991.674 1.991.674l.466-2.309c0 0-1.358-.515-2.691-.515-3.019 0-4.576 1.444-4.576 3.272 0 3.306 3.979 2.853 3.979 4.551 0 .291-.231.964-1.888.964-1.662 0-2.759-.609-2.759-.609l-.495 2.216c0 0 1.063.606 3.117.606 2.059 0 4.915-1.54 4.915-3.752C30.354 23.586 26.369 23.394 26.369 22.206z"></path><path fill="#FFC107" d="M12.212,24.945l-0.966-4.748c0,0-0.437-1.029-1.573-1.029c-1.136,0-4.44,0-4.44,0S10.894,20.84,12.212,24.945z"></path></svg>
                                    </div>
                          
                                    <div class="mastercard payment-option">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="144px" height="144px"><linearGradient id="NgmlaCv2fU27PJOuiUvQVa" x1="20.375" x2="28.748" y1="1365.061" y2="1394.946" gradientTransform="translate(0 -1354)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#00b3ee"></stop><stop offset="1" stop-color="#0082d8"></stop></linearGradient><path fill="url(#NgmlaCv2fU27PJOuiUvQVa)" d="M43.125,9H4.875C3.287,9,2,10.287,2,11.875v24.25C2,37.713,3.287,39,4.875,39h38.25 C44.713,39,46,37.713,46,36.125v-24.25C46,10.287,44.713,9,43.125,9z"></path><circle cx="17.053" cy="24.053" r="10.053" fill="#cf1928"></circle><linearGradient id="NgmlaCv2fU27PJOuiUvQVb" x1="20" x2="40.107" y1="24.053" y2="24.053" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fede00"></stop><stop offset="1" stop-color="#ffd000"></stop></linearGradient><circle cx="30.053" cy="24.053" r="10.053" fill="url(#NgmlaCv2fU27PJOuiUvQVb)"></circle><path fill="#d97218" d="M20,24.053c0,3.072,1.382,5.818,3.553,7.662c2.172-1.844,3.553-4.59,3.553-7.662 s-1.382-5.818-3.553-7.662C21.382,18.235,20,20.981,20,24.053z"></path>
                                          </svg>
                                    </div>

                                    <div class="amex payment-option">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="144px" height="144px"><path fill="#1976D2" d="M45,35c0,2.209-1.791,4-4,4H7c-2.209,0-4-1.791-4-4V13c0-2.209,1.791-4,4-4h34c2.209,0,4,1.791,4,4V35z"></path><path fill="#FFF" d="M22.255 20l-2.113 4.683L18.039 20h-2.695v6.726L12.341 20h-2.274L7 26.981h1.815l.671-1.558h3.432l.682 1.558h3.465v-5.185l2.299 5.185h1.563l2.351-5.095v5.095H25V20H22.255zM10.135 23.915l1.026-2.44 1.066 2.44H10.135zM37.883 23.413L41 20.018h-2.217l-1.994 2.164L34.86 20H28v6.982h6.635l2.092-2.311L38.767 27h2.21L37.883 23.413zM33.728 25.516h-4.011v-1.381h3.838v-1.323h-3.838v-1.308l4.234.012 1.693 1.897L33.728 25.516z"></path>
                                          </svg>
                                    </div>

                          
                                    <div class="discover payment-option">
                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="144px" height="144px"><radialGradient id="K5rKLmYY5bGzCTT1uDwOPa" cx="-2.043" cy="1356.043" r="53.845" gradientTransform="translate(0 -1354)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fafafb"></stop><stop offset="1" stop-color="#c8cdd1"></stop></radialGradient><path fill="url(#K5rKLmYY5bGzCTT1uDwOPa)" d="M43.125,9H4.875C3.287,9,2,10.287,2,11.875v24.25C2,37.713,3.287,39,4.875,39h38.25  C44.713,39,46,37.713,46,36.125v-24.25C46,10.287,44.713,9,43.125,9z"></path><path fill="#4a4e52" d="M7.949,25.515c-0.378,0.341-0.869,0.49-1.646,0.49H5.981v-4.077h0.323 c0.777,0,1.249,0.139,1.646,0.499c0.416,0.37,0.666,0.944,0.666,1.535C8.615,24.553,8.365,25.144,7.949,25.515z M6.544,20.883 H4.779v6.165h1.756c0.934,0,1.608-0.22,2.2-0.712c0.703-0.582,1.119-1.459,1.119-2.367C9.854,22.151,8.495,20.883,6.544,20.883"></path><path fill="#4a4e52" d="M10.407,27.048h1.203v-6.165h-1.203V27.048z"></path><path fill="#4a4e52" d="M14.55,23.249c-0.722-0.267-0.934-0.443-0.934-0.776 c0-0.388,0.378-0.683,0.896-0.683c0.36,0,0.656,0.148,0.97,0.499l0.629-0.824c-0.517-0.452-1.136-0.684-1.812-0.684 c-1.091,0-1.923,0.758-1.923,1.767c0,0.849,0.387,1.284,1.517,1.691c0.471,0.166,0.71,0.277,0.831,0.351 c0.24,0.157,0.361,0.379,0.361,0.638c0,0.5-0.397,0.87-0.934,0.87c-0.574,0-1.036-0.287-1.312-0.822l-0.777,0.748 c0.554,0.813,1.219,1.174,2.134,1.174c1.25,0,2.126-0.831,2.126-2.024C16.324,24.193,15.918,23.749,14.55,23.249"></path><path fill="#4a4e52" d="M16.703,23.97c0,1.812,1.423,3.217,3.254,3.217c0.518,0,0.961-0.102,1.508-0.359 v-1.416c-0.481,0.481-0.907,0.675-1.452,0.675c-1.211,0-2.071-0.878-2.071-2.127c0-1.184,0.887-2.117,2.015-2.117 c0.574,0,1.008,0.205,1.508,0.694v-1.415c-0.528-0.268-0.962-0.379-1.479-0.379C18.164,20.744,16.703,22.177,16.703,23.97"></path><path fill="#4a4e52" d="M31,25.024l-1.644-4.141h-1.314l2.616,6.323h0.647l2.663-6.323h-1.304L31,25.024"></path><path fill="#4a4e52" d="M34.513,27.048h3.41v-1.044h-2.209V24.34h2.127v-1.044h-2.127v-1.368h2.209v-1.045 h-3.41V27.048"></path><path fill="#4a4e52" d="M40.272,23.722h-0.351v-1.867h0.37c0.749,0,1.156,0.314,1.156,0.914 C41.447,23.388,41.04,23.722,40.272,23.722z M42.684,22.703c0-1.154-0.795-1.82-2.182-1.82h-1.783v6.165h1.201v-2.477h0.157 l1.665,2.477h1.479l-1.941-2.597C42.186,24.267,42.684,23.647,42.684,22.703"></path><path fill="none" d="M21.858,23.988c0,0,0,0,0-0.002c0-1.81,1.468-3.279,3.279-3.279c1.812,0,3.281,1.469,3.281,3.279  c0,0.002,0,0.002,0,0.002c0,1.81-1.469,3.279-3.281,3.279C23.326,27.267,21.858,25.799,21.858,23.988"></path><radialGradient id="K5rKLmYY5bGzCTT1uDwOPb" cx="27.473" cy="26.521" r="8.792" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#f5be00"></stop><stop offset=".633" stop-color="#c4431f"></stop><stop offset=".775" stop-color="#751b0b"></stop></radialGradient><circle cx="25.143" cy="24" r="3.299" fill="url(#K5rKLmYY5bGzCTT1uDwOPb)"></circle>
                                      </svg>
                                    </div>

                              </div>
                        </div>


                </div>


                <!-- Form submit button -->
                <button id="submitBtn_lifetime" type="button" class="btn btn-success" style="display: none;">
                    <div class="spinner hidden" id="spinner_"><?xml version="1.0" encoding="UTF-8" standalone="no"?><svg xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" width="160px" height="20px" viewBox="0 0 128 16" xml:space="preserve"><path fill="#949494" d="M6.4,4.8A3.2,3.2,0,1,1,3.2,8,3.2,3.2,0,0,1,6.4,4.8Zm12.8,0A3.2,3.2,0,1,1,16,8,3.2,3.2,0,0,1,19.2,4.8ZM32,4.8A3.2,3.2,0,1,1,28.8,8,3.2,3.2,0,0,1,32,4.8Zm12.8,0A3.2,3.2,0,1,1,41.6,8,3.2,3.2,0,0,1,44.8,4.8Zm12.8,0A3.2,3.2,0,1,1,54.4,8,3.2,3.2,0,0,1,57.6,4.8Zm12.8,0A3.2,3.2,0,1,1,67.2,8,3.2,3.2,0,0,1,70.4,4.8Zm12.8,0A3.2,3.2,0,1,1,80,8,3.2,3.2,0,0,1,83.2,4.8ZM96,4.8A3.2,3.2,0,1,1,92.8,8,3.2,3.2,0,0,1,96,4.8Zm12.8,0A3.2,3.2,0,1,1,105.6,8,3.2,3.2,0,0,1,108.8,4.8Zm12.8,0A3.2,3.2,0,1,1,118.4,8,3.2,3.2,0,0,1,121.6,4.8Z"/><g><path fill="#949494" d="M-42.7,3.84A4.16,4.16,0,0,1-38.54,8a4.16,4.16,0,0,1-4.16,4.16A4.16,4.16,0,0,1-46.86,8,4.16,4.16,0,0,1-42.7,3.84Zm12.8-.64A4.8,4.8,0,0,1-25.1,8a4.8,4.8,0,0,1-4.8,4.8A4.8,4.8,0,0,1-34.7,8,4.8,4.8,0,0,1-29.9,3.2Zm12.8-.64A5.44,5.44,0,0,1-11.66,8a5.44,5.44,0,0,1-5.44,5.44A5.44,5.44,0,0,1-22.54,8,5.44,5.44,0,0,1-17.1,2.56Z"/><animateTransform attributeName="transform" type="translate" values="23 0;36 0;49 0;62 0;74.5 0;87.5 0;100 0;113 0;125.5 0;138.5 0;151.5 0;164.5 0;178 0" calcMode="discrete" dur="1170ms" repeatCount="indefinite"/></g></svg>
                    </div>
                    <span id="buttonText_">Proceed</span>
                </button>

            </form>
            <?php

        }

        ?>
     

        
        <!-- Display processing notification -->
        <div id="frmProcess" class="hidden"><div class="d-flex">

        <dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>   
            <span class="ring"></span> Processing...
            </div>
        </div>
    </div>
</div>
 

</div>
</div>
</div>


</div>

<script>
    $('#payBtn').click(function() {
        $('#submitBtn').click();
    })
</script>   

<script>


  $("#state").html('<option value=""> Select State </option>') ;
                $("#city").html('<option value=""> Select City</option>') ;
    
$("#country").change(function(){

        var country = $(this).val() ;
 

        $.ajax({
            url:"ajax.php",
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
            url:"ajax.php",
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

    
 function selected_url() {
   


    var country = $("#country").attr("data-id-country") ;
    var state = $("#state").attr("data-id-state") ;
    var city = $("#city").attr("data-id-city") ;
       $.ajax({
            url:"ajax.php",
            method:"POST",
            dataType:"JSON",
            data:{country:country,state:state,city:city }
        }).done(function(response)
        {
            if ( response.status == 1 ) {
                $("#state").html(response.options_s) ;
                $("#city").html(response.options_c) ;
            }

        }).fail(function(){
            console.log("error") ;
        });
   

 }
 <?php  if (isset($sele_run2['country']))

 {

?>

selected_url() ;

 <?php } ?>
 
</script>

<script>

     $("#validate_btn").on('click', function(){
//Form validation

 var flag = 0;   

if(!isvalid("#fname")){
markinviled("#fname");
flag = 1;
}
else{
markvalid("#fname");
flag = 0;
}

if(!isvalid("#email")){
markinviled("#email");
  flag = 1;
}
else{
markvalid("#email");
  flag = 0;
}
var email = $('#email').val();
 if(IsEmail(email)==false){
  markinviled("#email");
   flag = 1;
   
} 


if(!isvalid("#adr")){
markinviled("#adr");
flag = 1;
}
else{
markvalid("#adr");
flag = 0;
}

if(!isvalid("#country")){
markinviled("#country");
flag = 1;
}
else{
markvalid("#country");
flag = 0;
}


function markinviled(selector){
    
    $(selector).css("border-color","red");
}

function markvalid(selector){
    
    $(selector).css("border-color","gray");
}
     });
     function isvalid(selector){
            if($(selector).val()==""){
             
            return false;
            }
            else{
              return true;
            }
} 
 function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if(!regex.test(email)) {
    return false;
  }else{
    return true;
  }  
 }
</script>
<script>
    $("#biling-form").submit(function(){
       
 $("#main-billing").css("display", "none");
 $(".panel").css("display", "block");
 
});

// Discount Calculation


let cart_Price = parseFloat($("#t_Price").val());

let cart_tax_price = parseFloat($("#tax_price").val());
let subscription_type = "";
let subscription_duration = "";

    $("#apply_coupon").click(function(){

        var subscr_plan = $("#subscr_plan").val();
        var coupon_code = $("#coupon_code").val();

        if(coupon_code == ""){
            $("#coupon_err").html("Please enter a coupon code.");
            setTimeout(function(){$("#coupon_err").html('');},10000);
        }
        else{


          
               $.ajax({
                    url:"check_coupon.php",
                    method:"POST",
                    dataType:"JSON",
                    data:{subscr_plan:subscr_plan , coupon_code:coupon_code, 'location':'India'}
                }).done(function(response)
                {
                    console.log(response);
                    if ( response.status == 1 ) {
                         console.log(response.status);


                         var final_p = 0;
                         var final_dis = 0;

                         if(response.type=="Percentage" && response.discount != 100)
                         {
                             subscription_type= "";
                            final_dis = (cart_Price*response.discount/100).toFixed(2);
                            final_p = (cart_Price - (cart_Price*response.discount/100)+cart_tax_price).toFixed(2);
                         }
                         else if(response.type=="Amount"){
                             subscription_type= "";
                             final_p =  (cart_Price-response.discount+cart_tax_price);
                             final_dis =  (response.discount);
                             final_dis =  parseFloat(final_dis).toFixed(2);

                             $(".panel-heading").show();
                             $(".card-info").show();
                             $("#submitBtn_lifetime").hide();
                             $("#submitBtn").show();
                             $("#submitBtn_lifetime").attr("coupon_id","");
                         }
                         else{

                                   if(response.type=="Percentage" && response.discount == 100 )
                                   {
                                        subscription_type = "month free";
                                        subscription_duration = response.duration;
                                   }
                                   else{
                                        subscription_type = "lifetime";
                                        subscription_duration = "";
                                   }                          
                                   final_p = "No need to pay";
                                   final_dis = "Free";
                                   $(".panel-heading").hide();
                                   $(".card-info").hide();
                                   $("#submitBtn_lifetime").show();
                                   $("#submitBtn").hide();
                                   $("#submitBtn_lifetime").attr("coupon_id",response.id);

                         }


                         $('.order_summary .order.discount').html(`
                            
                              <p class="text"><span>Coupon Applied</span> (<span>${coupon_code}</span>)</p>
                              <p class="amount">-₹ ${final_dis}</p>
                                                     
                          `);

                         var price = (cart_Price - final_dis );
                         var cart_Prices = cart_Price.toFixed(2);
                         var tax = parseFloat("<?=$tax?>");

                          var total_tax = price*tax/100;
                          var total_price = price + total_tax;  

                          var tTax =   parseFloat(total_tax).toFixed(2);

                          final_p =  total_price.toFixed(2); 

                          if(subscription_type==""){                   

                             $("#applyed_coupon").html(`<div class="applied-discount"><div class='icon'></div><div><label>${response.tag}</label><label class="remove_coupon">✖</label></div></div>
                                <table class="table">
                                    <tr><td>Subscription Price</td><td>₹ ${cart_Prices}</td></tr>
                                    <tr><td>Coupon Applied (${coupon_code})</td><td>-₹ ${final_dis}</td></tr>
                                    <tr><td>GST (${tax}%)</td><td>₹ ${tTax}</td></tr>
                                    <tr><td>Payble Amount</td><td>₹ ${final_p}</td></tr>
                                </table>`
                                );  
                              $(".order.gst").show();

                           }else{
                            final_p = "No need to pay";
                             $("#applyed_coupon").html(`<div class="applied-discount"><div class='icon'></div><div><label>${response.tag}</label><label class="remove_coupon">✖</label></div></div>
                                <table class="table">
                                    <tr><td>Subscription Price</td><td>₹ ${cart_Prices}</td></tr>
                                    <tr><td>Coupon Applied (${coupon_code})</td><td>-₹ ${final_dis}</td></tr>
                                    <tr><td>Payble Amount</td><td>₹ ${final_p}</td></tr>
                                </table>`
                                );

                             $(".order.gst").hide();

                           }

                         $("#tax_price").val(tTax);
                         $("#discount_amount").val(final_dis);

                         $(".amount.gst").html(`₹ ${tTax}`);
                         $("#coupon_id").val(response.coupon_id);
                         // $('#t_Price').val(final_p);
                         $(".payble_amt_val").html(final_p);


                         $("#coupon_code").attr("disabled",true);
                         $("#apply_coupon").attr("disabled",true);

                            // START for minimum payment amount ==================
                            final_p = Number(final_p) ;
                            if ( final_p < 1 ) {
                                setTimeout(function(){
                                    $("#coupon_err").html("Minimum payment amount ₹1.00 required.");
                                    setTimeout(function(){$("#coupon_err").html('');},10000);   
                                    $(".remove_coupon").click() ;
                                },500);
                            }
                            // END for minimum payment amount ==================

                    }
                    else{
                     $("#coupon_err").html(response.message);   
                     setTimeout(function(){$("#coupon_err").html('');},10000);   
                    }


                }).fail(function(){
                    console.log("error") ;
                });

        }



    });


$("body").on("click",".remove_coupon",function(){
    $("#applyed_coupon").html("");
    $(".order_summary .order.discount").html("");
    $("#coupon_code").attr("disabled",false);
    $("#apply_coupon").attr("disabled",false);  
    $("#coupon_id").val("");
    $(".panel-heading").show();
    $(".card-info").show();
    $("#submitBtn_lifetime").attr("coupon_id","");
    $("#discount_amount").val("");

    $(".order.gst").show();
    $("#submitBtn_lifetime").hide();
    $("#submitBtn").show();
    $(".amount.gst").html($(".amount.gst").attr("default"));
    $("#tax_price").val($(".amount.gst").attr("default"));
    
    // $('#t_Price').val(cart_Price);
    var tp = cart_Price+cart_tax_price;
    tp = tp.toFixed(2);
    $(".payble_amt_val").html(tp); 

});




$("body").on("click","#submitBtn_lifetime",function(){

    let fname = document.getElementById("fname").value;
    let emailid = document.getElementById("emailId").value;
    let country = document.getElementById("country").value;
    let adr = document.getElementById("adr").value;
    let city = document.getElementById("city").value;
    let zip = document.getElementById("zip").value;


    if(fname!="" && emailid!="" && country!="" && adr!="" && city!="" && zip!="")
    {

    setLoading_(true);
    var ajaxUrl = "lifetimecode/LifetimeCode.php";
    if(subscription_type=="lifetime"){
        ajaxUrl = "lifetimecode/LifetimeCode.php";
    }else{
        ajaxUrl = "lifetimecode/MonthFreeCode.php";
    }


    var subscr_plan_id = document.getElementById("subscr_plan").value;
    var customer_name = document.getElementById("name").value;
    var customer_email = document.getElementById("email").value;
    var change_id = document.getElementById("change_id").value;
    var sid_id = document.getElementById("sid_id").value;
    var coupon_code = document.getElementById("coupon_code").value;
    var coupon_id = $(this).attr("coupon_id");
               $.ajax({
                type: "POST",
                url: ajaxUrl,
                data: {
                 subscr_plan_id:subscr_plan_id,
                 customer_name: customer_name,
                 customer_email : customer_email,
                 change_id : change_id,
                 sid_id : sid_id,
                 coupon_code : coupon_code,
                 coupon_id : coupon_id,
                 currency : "INR",
                 subscription_duration : subscription_duration                 
                 },
                dataType: "json",
                encode: true,
              }).done(function (data) {

                  if(data == 2){
                        showMessage("Inviled Coupon Code");
                        setLoading_(false);

                  }
                  else if(data == 1){
                    window.location.href="/adminpannel/project-dashboard.php?project=<?php echo $sideEncode;?>";
                  }
                 
              }).fail(function(data){
                 
            });

}
   else{


        if(fname==""){
            $("#fname").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(emailId==""){
            $("#emailId").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(country==""){
            $("#country").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(adr==""){
            $("#adr").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(city==""){
            $("#city").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }
        if(zip==""){
            $("#zip").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
        }        

  
            $("#main-billing h3").append('<small class="required_billing" style="color:red;">Please fill all the required field.</small>');
        
        setTimeout(function(){
            $(".required_billing").remove();
        },5000);


    }

});

// Show a spinner on payment submission
function setLoading_(isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submitBtn_lifetime").disabled = true;
        document.querySelector("#spinner_").classList.remove("hidden");
        document.querySelector("#buttonText_").classList.add("hidden");
    } else {
        // Enable the button and hide spinner
        document.querySelector("#submitBtn_lifetime").disabled = false;
        document.querySelector("#spinner_").classList.add("hidden");
        document.querySelector("#buttonText_").classList.remove("hidden");
    }
}


//123
$('.getItNowForm').on('submit',function(e){
    e.preventDefault();
    var is_valid = 1;
    var subId = $('#getItNowSubscrId').val();
    var websiteId = $('#getItNowWebsiteId').val();
    var userId = $('#getItNowUserId').val();
    var cartId = $('#getItNowCartId').val();
    var button = 'GetItNow';
    let fname = document.getElementById("fname").value;
    let emailid = document.getElementById("emailId").value;
    let country = document.getElementById("country").value;
    let adr = document.getElementById("adr").value;
    let city = document.getElementById("city").value;
    let zip = document.getElementById("zip").value;
    let contact_number  = document.getElementById("contact_number").value; //123
    contact_number = contact_number.trim();
    if(fname==""){
            $("#fname").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            is_valid=0;
        }
        if(emailId==""){
            $("#emailId").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            is_valid=0;
        }
        if(country==""){
            $("#country").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            is_valid=0;
        }
        if(adr==""){
            $("#adr").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            is_valid=0;
        }
        if(city==""){
            $("#city").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            is_valid=0;
        }
        if(zip==""){
            $("#zip").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            is_valid=0;
        } 
        //123       
        if( contact_number == "" || contact_number == undefined || contact_number == null ){
            $("#contact_number").parents(".form-group").find("label").append('<small class="required_billing" style="color:red;">Required</small>');
            is_valid=0;
        }        

  
        
        setTimeout(function(){
            $(".required_billing").remove();
        },5000);

    if(is_valid){
    $.ajax({
        url : './get-it-now.php',
        method : 'post',
        data : {
            planId : subId,
            websiteId : websiteId,
            userId: userId,
            cartId : cartId,
            button : button,
            paid_amount_currency : 'inr'
        },
        success : function(response){
            var obj = $.parseJSON(response);
            // console.log(obj.subs_id); return false;
            if(obj.status == 1){
                window.location.href = `./payment-status-ind.php?sid=${obj.subs_id}&change_id=`;

            }
        }
    })
    }
    
})

</script>



