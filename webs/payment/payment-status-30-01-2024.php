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
  
require_once 'config.php'; 
 
 $country = "Other";
require_once 'dbConnect.php'; 
 
$payment_id = $statusMsg = ''; 
$status = 'error'; 
$usercount = 0;
$website_id = 0;

 
 $count_site =  $_SESSION["count_site"];
 
if(!empty($_GET['sid'])){ 
    $subscr_id  = base64_decode($_GET['sid']); 
    // $subscr_id  = $_GET['sid']; 
     
    // Fetch subscription info from the database 
    $sqlQ = "SELECT S.*, P.name as plan_name, P.us_main_p as plan_amount, U.firstname, U.lastname, U.email,S.plan_id as plan_id, S.send_email FROM user_subscriptions as S LEFT JOIN admin_users as U On U.id = S.user_id LEFT JOIN plans as P On P.id = S.plan_id WHERE S.id = ?"; 
    $stmt = $db->prepare($sqlQ);  
    $stmt->bind_param("i", $db_id); 
    $db_id = $subscr_id; 
    $stmt->execute(); 
    $result = $stmt->get_result(); 

 

    if($result->num_rows > 0){ 
        // Subscription and transaction details 
        $subscrData = $result->fetch_assoc(); 
        $stripe_subscription_id = $subscrData['stripe_subscription_id']; 
        $paid_amount = $subscrData['paid_amount']; 
        $paid_amount_currency = $subscrData['paid_amount_currency']; 
        $plan_interval = $subscrData['plan_interval']; 
        $plan_period_start = $subscrData['plan_period_start']; 
        $plan_period_end = $subscrData['plan_period_end']; 
        $subscr_status = $subscrData['status']; 
         
        $plan_name = $subscrData['plan_name']; 
        $plan_id = $subscrData['plan_name']; 
        $plan_amount = $subscrData['plan_price']; 
 
        $customer_name = $subscrData['firstname'].' '.$subscrData['lastname']; 
        $customer_email = $subscrData['payer_email']; 
         
        $status = 'success'; 
        $statusMsg = 'Your Subscription Payment has been Successful!'; 
        if ( $subscrData["paid_amount"] == 0 || $subscrData["paid_amount"] == "0" ) {
            $statusMsg = 'Your Subscription Trial has been started Successful!'; 
        }

        $subscription_items_id = $subscrData['subscription_items_id'];
        $products_id = $subscrData['products_id'];



        $paid_amount_currency = strtoupper($paid_amount_currency);



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

                $price_with_currenct = number_format((float)$paid_amount, 2, '.', '');


                
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

               

                    $emailVariables = array("plan" => $plan_name, "detail_link" => 'https://websitespeedy.com/payment/payment-status.php?sid='.$_GET['sid']);

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

    }else{ 
        $statusMsg = "Transaction has been failed!"; 
    } 
}else{ 
    header("Location: index.php"); 
    exit; 
} 
?>

<div class="payment__header confirm__page" >
    <div class="logo-cus"><a href="https://websitespeedy.com/adminpannel/" ><img    style="width: 175px;" src="https://websitespeedy.com/adminpannel/img/sitelogo_s.png" alt="Ecommercespeedy Logo"></a></div>

        <div class="breadcrumb__wrapper" >
            <span onclick="history.back()" class="select__plan disbaled" >Select Plan</span>
            <span class="toogleBtn disbaled" id="paymentDetais" >Payment Details</span>
            <span class="toggleBtn " id="orderConfim" >Order Confirmation</span>
        </div>

        <div class="help__wrapper" >
            <a href="https://help.websitespeedy.com/faqs" target="_blank" >Help</a>
        </div>
</div>
<div class="payment__conf__data">

<?php sleep(1); if(!empty($subscr_id)){ ?>
    <h3 class="<?php echo $status; ?>"><?php echo $statusMsg; ?></h3>
    
    <h2>Payment Information</h2>
    <p><b>Reference Number:</b> <?php echo $subscr_id; ?></p>
    <p><b>Subscription ID:</b> <?php echo $stripe_subscription_id; ?></p>
    <p><b>Paid Amount:</b> <?php echo $paid_amount.' '.$paid_amount_currency; ?>
</p>
    <p><b>Status:</b> <?php echo $subscr_status; ?></p>
    
    <h2>Subscription Information</h2>
    <p><b>Plan Name:</b> <?php echo $plan_name; ?></p>
    <p><b>Amount:</b> <?php echo $plan_amount.' '.STRIPE_CURRENCY; ?></p>
    <p><b>Plan Interval:</b> <?php echo $plan_interval; ?></p>
    <p><b>Period Start:</b> <?php echo $plan_period_start; ?></p>
    <p><b>Period End:</b> <?php echo $plan_period_end; ?></p>
    
    <h2>Customer Information</h2>
    <p><b>Email:</b> <?php echo $customer_email; ?></p>
    <?php
        if($usercount == 1){
            ?>
               <a class="btn__continue" href="/adminpannel/project-dashboard.php?project=<?=base64_encode($website_id)?>">Continue To Complete Setup</a>

            <?php
        }else{
     ?>
       <a class="btn__continue" href="/adminpannel/my-subscriptions.php">Continue</a>


<?php } }else{ ?>
    <h1 class="error">Your Transaction been failed!</h1>
    <p class="error"><?php echo $statusMsg; ?></p>
    <a href="/plan.php">Go Back</a>

<?php } ?>
</div>

</div>
</body>
</html>

 
