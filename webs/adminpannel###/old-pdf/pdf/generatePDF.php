<?php
  session_start();
  include('../config.php');
 
  ob_clean();

  $file_name = '/var/www/html/ecommercespeedy/adminpannel/pdf/pdf.pdf';
  // unlink($file_name);
  sleep(10);


  $id = base64_decode($_GET['id']);
  $userSubscription = "SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id WHERE user_subscriptions.id = '$id'";
  $user_data = mysqli_query($conn,$userSubscription);
  $userData=mysqli_fetch_assoc($user_data);
  $userName = $userData['firstname'].$userData['lastname'];
  $userEmail = $userData['email'];
  $paymentMethod = $userData['payment_method'];
  print_r($paymentMethod);
  $paidAmount = $userData['paid_amount'];
  $startPlan = $userData['plan_period_start'];
  $endPlan = $userData['plan_period_end'];
  $currentDate = date("Y-m-d");

 // echo "hii";

// $message = '';
$output = "hello";
  
  include('pdf.php');

  $pdf = new Pdf();
  $pdf->set_option('isRemoteEnabled', TRUE);
  
  $pdf->load_html($output);
  $pdf->render();
  $file = $pdf->output();
  file_put_contents($file_name, $file);



?>
