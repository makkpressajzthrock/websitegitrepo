
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>

</head>    
    <body>

    <div class="container payment_s">

<?php 
session_start();
include('../adminpannel/session.php');
include('../adminpannel/config.php');
require '../adminpannel/smtp-send-grid/vendor/autoload.php';
require_once("../adminpannel/inc/functions.php");

// Include autoloader 
require_once '../adminpannel/dompdf/autoload.inc.php'; 
  
require_once 'config.php'; 
 
$country = "IND";
require_once 'dbConnect.php'; 
 
$payment_id = $statusMsg = ''; 
$status = 'error'; 
$usercount = 0;
$website_id = 0;

 
 $count_site =  $_SESSION["count_site"];
 
if(!empty($_GET['sid'])){ 
    $subscr_id  = base64_decode($_GET['sid']); 
    // $subscr_id  = $_GET['sid']; 
     
    // Fetch subscription info from the database //123
    $sqlQ = "SELECT S.*, P.name as plan_name, P.page_view,  U.firstname, U.lastname, U.email,  U.self_install, S.plan_id as plan_id, S.send_email FROM user_subscriptions as S LEFT JOIN admin_users as U On U.id = S.user_id LEFT JOIN plans as P On P.id = S.plan_id WHERE S.id = ?"; 
    $stmt = $db->prepare($sqlQ);  
    $stmt->bind_param("i", $db_id); 
    $db_id = $subscr_id; 
    $stmt->execute(); 
    $result = $stmt->get_result(); 


    // print_r($result) ; die() ;



 

    if($result->num_rows > 0){ 
        // die("here");

        // Subscription and transaction details 
        $subscrData = $result->fetch_assoc();
        // echo "<pre>"; 
        // print_r($subscrData) ; die() ;

        $plan_id =   $subscrData['plan_id'];
        if($plan_id =='29' || $plan_id=='30'){
           $stripe_subscription_id = $subscrData['stripe_subscription_id']; 
           $paid_amount = $subscrData['paid_amount']; 
           $paid_amount_currency = ''; 
           $plan_interval = $subscrData['plan_interval']; 
           $plan_period_start = $subscrData['plan_period_start']; 
           $plan_period_end = $subscrData['plan_period_end']; 
           $subscr_status = $subscrData['status']; 
            
           $plan_name = $subscrData['plan_name']; 
           $plan_id = $subscrData['plan_name']; 
           $plan_amount = $subscrData['plan_price']; 
           //123
            if($plan_name=='Free'){
                $plan_name = 'Basic Plan';
                $paid_amount = '0.00';
            } 

           
           $customer_name = $subscrData['firstname'].' '.$subscrData['lastname']; 
           $customer_email = $subscrData['payer_email']; 
           $customer_phone = $subscrData['payer_phone'];  //123
           $customer_selfInstYes = $subscrData['self_install']; //123
           $page_view = $subscrData['page_view'];//123
           $customer_email = $subscrData['email']; 
           $user_id = $_SESSION["user_id"] ;

           
           $sqlzz = "SELECT id FROM boost_website WHERE manager_id = '$user_id' and subscription_id = '".$subscr_id."' ";
           // die;
           $stmtzz = $db->prepare($sqlzz);
           $stmtzz->execute();
           $resultzz = $stmtzz->get_result();           
           
           if ($fetchRow = $resultzz->fetch_assoc()) {
             $usercount= 2;
             $website_id = $fetchRow['id'];
         }


        }
        else{

        $stripe_subscription_id = $subscrData['stripe_subscription_id']; 
        $paid_amount = $subscrData['paid_amount']; 
        $paid_amount_currency = strtoupper($subscrData['paid_amount_currency']); 
        $plan_interval = $subscrData['plan_interval']; 
        $plan_period_start = $subscrData['plan_period_start']; 
        $plan_period_end = $subscrData['plan_period_end']; 
        $subscr_status = $subscrData['status']; 
         
        $plan_name = $subscrData['plan_name']; 
        $plan_id = $subscrData['plan_name']; 
        $plan_amount = $subscrData['plan_price']; 
 
        $customer_name = $subscrData['firstname'].' '.$subscrData['lastname']; 
        $customer_email = $subscrData['payer_email']; 
        $customer_phone = $subscrData['payer_phone'];  //123
        $customer_selfInstYes = $subscrData['self_install']; //123
        $page_view = $subscrData['page_view'];//123
         
        $status = 'success'; 
        $statusMsg = 'Your Subscription Payment has been Successful!'; 

        if ( $subscrData["paid_amount"] == 0 || $subscrData["paid_amount"] == "0" ) {
            $statusMsg = 'Your Subscription Trial has been started Successful!'; 
        }

        $subscription_items_id = $subscrData['subscription_items_id'];
        $products_id = $subscrData['products_id'];

            $user_id = $_SESSION["user_id"] ;



                    $sqlzz = "SELECT id FROM boost_website WHERE manager_id = '$user_id' and subscription_id = '".$subscr_id."' ";
                    // die;
                    $stmtzz = $db->prepare($sqlzz);
                    $stmtzz->execute();
                    $resultzz = $stmtzz->get_result();
                    
                    

                    if ($fetchRow = $resultzz->fetch_assoc()) {
                        $usercount = 1;
                        $website_id = $fetchRow['id'];
                    }
 
            
        require_once("../adminpannel/generate_script_paid.php");


   
            if($subscrData['send_email']==0){

            $price_with_currenct = $paid_amount;
            ?>

                <img src="https://www.shareasale.com/sale.cfm?tracking=<?=$subscr_id?>&amount=<?=$price_with_currenct?>&currency=<?=$paid_amount_currency?>&merchantID=144859&transtype=sale" width="1" height="1">
                <script src="https://www.dwin1.com/58969.js" type="text/javascript" defer="defer"></script>

<?php


                // echo "sending email.";
                $sqlQEmail = "SELECT * FROM email_template where title = 'Subscription Started'"; 
                $stmtEmail = $db->prepare($sqlQEmail);  
                $stmtEmail->execute(); 
                $resultEmail = $stmtEmail->get_result(); 
                $emailContent = $resultEmail->fetch_assoc(); 

               

                    $emailVariables = array("plan" => $plan_name, "detail_link" => HOST_URL.'payment/payment-status-ind.php?sid='.$_GET['sid']);

                    // replace variable values from message body ------
                    foreach ($emailVariables as $key1 => $value1) {
                        $emailContent["description"] = str_replace('{{' . $key1 . '}}', $value1, $emailContent["description"]);
                        $emailContent["subject"] = str_replace('{{' . $key1 . '}}', $value1, $emailContent["subject"]);
                    }       

                    // print_r($emailContent["subject"]);         

                        $smtp_login = $db->query( " SELECT * FROM smtp_login ; " ) ;
                        $data_smtp = $smtp_login->fetch_assoc() ;
                         

                    $emailss = new \SendGrid\Mail\Mail(); 
                    $emailss->setFrom($data_smtp["from_email"],$data_smtp["from_name"]);
                    $emailss->setSubject($emailContent["subject"]);
                    $emailss->addTo($customer_email,"Website Speddy");
                    $emailss->addContent("text/html",$emailContent["description"]);
                    $sendgrid = new \SendGrid($data_smtp["password"]);

                    // echo $data_smtp["from_email"];

                    $db->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('".$user_id."', '".$emailContent["subject"]."', '".$db->real_escape_string($emailContent["description"])."', CURDATE())");
                    $db->query("UPDATE user_subscriptions set send_email = 1 where id = '$subscr_id' ");
                    $sendgrid->Send($emailss);

            }
       
        


         
                $_SESSION['success'] = " Hurray! Subscription activated successfully." ;

    }//123
    }  
     else{ 
        $statusMsg = "Transaction has been failed!"; 
    } 
}else{ 
    header("Location: index.php"); 
    exit; 
} 
?>

<!-- <div class="payment__header confirm__page" >
<div class="logo-cus"><a href="https://websitespeedy.com/adminpannel/" ><img    style="width: 175px;" src="https://websitespeedy.com/adminpannel/img/sitelogo_s.png" alt="Ecommercespeedy Logo"></a></div>

        <div class="breadcrumb__wrapper" >
            <span onclick="history.back()" class="select__plan disbaled" >Select Plan</span>
            <span class="toogleBtn disbaled" id="paymentDetais" >Payment Details</span>
            <span class="toggleBtn " id="orderConfim" >Order Confirmation</span>
        </div>

        <div class="help__wrapper" >
            <a href="https://help.websitespeedy.com/faqs" target="_blank" >Help</a>
        </div>
</div> -->
 <!-- //123 -->
    <?php include(__DIR__ . '/payment-statement-content-ind.php'); ?>
         
         <table style="width: 100%; margin:0;padding: 10px 40px;margin-bottom: 10px;background: #f2f2f2; font-family:arial;">
            <tr>
               <th style="width:25%; text-align:left;    padding:5px 15px 5px 0;">
                  <a style="margin: 0;padding: 0;background: none;" href="<?=HOST_URL?>adminpannel/" ><img    style="width: 175px;" src="<?=HOST_URL?>adminpannel/img/sitelogo_s.png" alt="Ecommercespeedy Logo"></a>
               </th>
               
               <th style="width:50%; text-align:center;    font-size: 17px;">
                  <span style="user-select: none;pointer-events: none;opacity: 0.4;cursor: no-drop;font-weight: normal;" onclick="history.back()" class="select__plan disbaled" >Select Plan <svg fill="#000000" height="200px" width="200px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330.00 330.00" xml:space="preserve" stroke="#000000" stroke-width="9.9" style="
                  width: 13px;height: 13px;vertical-align: middle;margin-top: -2px;margin-left: 10px;"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path id="XMLID_222_" d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001 c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213 C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606 C255,161.018,253.42,157.202,250.606,154.389z"></path> </g></svg>
                  </span>

                  <span style="user-select: none;pointer-events: none;opacity: 0.4;cursor: no-drop; margin: 0 15px;font-weight: normal;" style="margin:0 15px;" id="paymentDetais" >Payment Details <svg fill="#000000" height="200px" width="200px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330.00 330.00" xml:space="preserve" stroke="#000000" stroke-width="9.9" style="
                  width: 13px;height: 13px;vertical-align: middle;margin-top: -2px;margin-left: 10px;"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path id="XMLID_222_" d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001 c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213 C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606 C255,161.018,253.42,157.202,250.606,154.389z"></path> </g></svg></span>

                  <span style="font-weight: normal;" class="toggleBtn " id="orderConfim" ><svg style="vertical-align: middle;margin-right: 10px;margin-top: -2px;" width="16" height="16" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="#f23640" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"/></svg> Order Confirmation</span>
               </th>
                  
               <th style="width:25%; text-align:right;">
                  <a style="color: #fff;display: block;width: fit-content;margin: 0 0 0 auto;background: #f23640;text-decoration: none;padding: 6px 15px;
                     border-radius: 5px;transition: all 250ms ease;font-weight: normal;min-width: 70px;text-align: center;" href="<?=HOST_HELP_URL?>faqs#pricing_billing_faq" target="_blank" >Help</a>
               </th>
            </tr>

         </table>
<table style="max-width: 800px;margin: auto;width: 100%;padding: 10px 20px;box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;margin: 30px auto;">
<?php sleep(1);  if(!empty($subscr_id)){ 

    ?>
    <thead> <tr><td colspan="2"><h3 class="<?php echo $status; ?>"><?php echo $statusMsg; ?></td></tr></h3></thead>
            <tbody style="font-size:15px;">

            <tr><td style="padding-bottom:8px;" colspan="2"><h2 style="font-size:16px;">Payment Information</h2></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Reference Number: </td><td> <?php echo $subscr_id; ?></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Subscription ID:  </td><td><?php echo $stripe_subscription_id; ?></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Paid Amount:  </td><td><?php echo $paid_amount.' '.STRIPE_CURRENCY; ?></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Status:  </td><td><?php echo $subscr_status; ?></td></tr>
            <tr><td style="padding-bottom:8px;" colspan="2"><h2 style="font-size:16px;">Subscription Information</h2></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Plan Name:  </td><td><?php echo $plan_name; ?></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Amount: </td><td><?php echo $plan_amount.' '.STRIPE_CURRENCY; ?></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Plan Interval:  </td><td><?php echo $plan_interval; ?></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Period Start:  </td><td><?php echo $plan_period_start; ?></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Period End:  </td><td><?php echo $plan_period_end; ?></td></tr>
            <tr><td style="padding-bottom:8px;" colspan="2"><h2 style="font-size:16px;">Customer Information</h2></td></tr>
            <tr><td style="padding:8px 8px 8px 15px">Email:  </td><td><?php echo $customer_email; ?></td></tr>
            <!-- //123 -->
            <!-- <p><b>Contact No:</b> <?php echo $customer_phone; ?></p> -->

            <tr><td style="padding:30px 8px 8px 15px; text-align:center; " colspan="2">
            
    <?php 
             unset($_SESSION['sid']);
             
             if($customer_selfInstYes =='yes'){
                $selfInsBtn =   '<a style="margin-top:20p;xcolor: #fff;
                display: block;
                width: fit-content;
                margin:24px auto 0;
                background: #f23640;
                text-decoration: none;
                padding: 5px 15px;
                border-radius: 5px;
                transition: all 250ms ease;"  href="'.HOST_URL.'adminpannel/project-dashboard.php?project='.base64_encode($website_id).'">Continue</a>';
                //  $selfInsBtn = '';
               }else{
                  $selfInsBtn = '<a  style="color: #fff;
                  display: block;
                  width: fit-content;
                  margin: 24px auto 0;
                  background: #f23640;
                  text-decoration: none;
                  padding: 5px 15px;
                  border-radius: 5px;
                  transition: all 250ms ease;" href="'.HOST_URL.'adminpannel/project-dashboard.php?project='.base64_encode($website_id).'">Continue</a>';
               }
               //123
               ?>
    <?php
     $timer = "This page will redirect after <span id='timer' style='color: #f23640;font-weight: 600;'></span> seconds ";
        if($usercount == 1){
            echo "<script>
                       setTimeout(function() {
                              window.location.href = '" . HOST_URL . "adminpannel/project-dashboard.php?project=" . base64_encode($website_id) . "';
                          }, 20000); // 20 seconds delay before redirection
                     </script>";
                  echo  $timer;
                  echo $selfInsBtn;  
               
               // echo $selfInsBtn;  //123
               // echo   $output;   
                         
            }
            //123
            elseif($usercount == 2){
                echo "<script>
                       setTimeout(function() {
                              window.location.href = '" . HOST_URL . "adminpannel/project-dashboard.php?project=" . base64_encode($website_id) . "';
                          }, 20000); // 20 seconds delay before redirection
                     </script>";
                  echo  $timer;
                  echo $selfInsBtn;  
            }
        else{
     ?>
       <a class="btn__continue" href="/adminpannel/my-subscriptions.php">Continue</a>


<?php } }else{ ?>
    <h1 class="error">Your Transaction been failed!</h1>
    <p class="error"><?php echo $statusMsg; ?></p>
    <a href="/plan.php">Go Back</a>

<?php } ?>
</td></tr>
</tbody>
</table>
<script>
    // JavaScript code for the countdown timer
    function countdownTimer(duration, display) {
      let timer = duration;
      let countdown = setInterval(function() {
        display.textContent = timer;
        timer--;

        if (timer < 0) {
          clearInterval(countdown);
          display.textContent = "0";
        }
      }, 1000);
    }

    window.onload = function() {
      const display = document.getElementById("timer");
      countdownTimer(20, display);
    };
</script>
</div>
</body>
</html>

 
