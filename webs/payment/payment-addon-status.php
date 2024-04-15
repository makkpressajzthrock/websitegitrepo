<html>
    <body>
    <head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>    
    <div class="container payment_s">
<?php 
session_start();
include('../adminpannel/session.php');
include('../adminpannel/config.php');
// Include the configuration file  
require_once 'config.php'; 
 
// Include the database connection file  
require_once 'dbConnect.php'; 
 
$payment_id = $statusMsg = ''; 
$status = 'error'; 
// echo $_SESSION['user_id'];
 // $site_id = $_SESSION['siteId'];
 $count_site =  $_SESSION["count_site"];
// die;
// Check whether the subscription ID is not empty 
if(!empty($_GET['sid'])){ 
    $subscr_id  = base64_decode($_GET['sid']); 
     
    // Fetch subscription info from the database 
    $sqlQ = "SELECT S.*, P.description as plan_name, P.price as plan_amount, U.firstname, U.lastname, U.email,S.addon_id as plan_id FROM addon_site as S LEFT JOIN admin_users as U On U.id = S.user_id LEFT JOIN addon as P On P.id = S.addon_id WHERE S.id = ?"; 
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
        $plan_amount = $subscrData['plan_amount']; 
 
        $customer_name = $subscrData['firstname'].' '.$subscrData['lastname']; 
        $customer_email = $subscrData['payer_email']; 
         
        $status = 'success'; 
        $statusMsg = 'Your Subscription Payment has been Successful!'; 

        $subscription_items_id = $subscrData['subscription_items_id'];
        $products_id = $subscrData['products_id'];

            $user_id = $_SESSION["user_id"] ;

  

                $_SESSION['success'] = " Hurray! Addon activated successfully." ;

    }else{ 
        $statusMsg = "Transaction has been failed!"; 
    } 
}else{ 
    die;
    header("Location: index.php"); 
    exit; 
} 
?>

<?php if(!empty($subscr_id)){ ?>
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
       <a href="/adminpannel/edit-website.php?project=<?=base64_encode($subscrData['site_id'])?>">Go Back</a>

<?php }else{ ?>
    <h1 class="error">Your Transaction been failed!</h1>
    <p class="error"><?php echo $statusMsg; ?></p>
    <a href="/plan.php">Go Back</a>

<?php } ?>

</div>
</body>
</html>

 
