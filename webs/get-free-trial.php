<?php

require_once("adminpannel/config.php") ;
require_once("adminpannel/inc/functions.php") ;
require 'adminpannel/smtp-send-grid/vendor/autoload.php';

      $user_id = $_SESSION["user_id"] ;

if ( !checkUserLogin() ) {
    header("location: ".HOST_URL."signup.php") ;
    die() ;
}
 


    $user = $conn->query(" SELECT * FROM `admin_users` WHERE id='$user_id' ") ;
    $userdata = $user->fetch_assoc() ;
     $email  = $userdata['email'];
        
    $user_subscriptions_free = $conn->query(" SELECT * FROM `user_subscriptions_free` WHERE user_id='$user_id' ") ;
    $num_rows = $user_subscriptions_free->num_rows;
 

    if($num_rows <=0){
// die;


 
  $plan_id = $conn->real_escape_string($_GET['plan']) ;
 
  $query = $conn->query(" SELECT * FROM `plans` WHERE `id` = '".$plan_id."' AND status = 1 ") ;
  if ( $query->num_rows > 0 ) {
    $plan_data = $query->fetch_assoc() ;

 
      $plan_duration = $plan_data["s_trial_duration"] ;
      $plan_name = $plan_data["s_type"] ;
      $plan_date = date('Y-m-d H:i:s') ;
      $user_id = $_SESSION["user_id"] ;

      $today=date('Y-m-d H:i:s');
      $next_date= date('Y-m-d H:i:s', strtotime($today. ' + '.$plan_duration.' days'));

      $query_site = $conn->query(" SELECT * FROM `boost_website` WHERE manager_id='$user_id' ") ;
      $site_data = $query_site->fetch_assoc() ;

      $site_id  = $site_data['id'];
       

      $sql_free = " INSERT INTO user_subscriptions_free(user_id,plan_id, plan_end_date) VALUES ('$user_id','$plan_id', '$next_date') " ;

      $free_id = ""; 
      if ( $conn->query($sql_free) === TRUE ) {
        $free_id = $conn->insert_id;
      }


      $sql_update = " UPDATE boost_website SET plan_id = $plan_id, plan_type = 'Free',subscription_id= '$free_id' where manager_id='$user_id' " ;

      if ( $conn->query($sql_update) === FALSE ) {
        $_SESSION['error'] = "Operation failed." ;
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
      }

          require_once("adminpannel/generate_script_free.php");

        // ---------------------------------------------------------------------------
        $sql3 = " UPDATE admin_users SET flow_step = 2 where id = '$user_id'" ;
        $conn->query($sql3); 

        $au_query = $conn->query(" SELECT * FROM admin_users WHERE id = '$user_id' ; ") ;
        $au_data = $au_query->fetch_assoc() ;
        $name = empty($au_data["firstname"]) ? $au_data["email"] : $au_data["firstname"].''.$au_data["lastname"] ;

        $email = $au_data['email'];
        $email_status = $au_data['email_status'];

              // get email content from database ----------
              $emailContent = getEmailContent( $conn , 'Subscription Trial Started Email' ) ;

              // set email variable values ----------------
              $emailVariables = array("dashboard_link" => HOST_URL."adminpannel/project-dashboard.php?project=".base64_encode($site_id) );

              // replace variable values from message body ------
              foreach($emailVariables as $key1 => $value1) {
                  $emailContent["body"] = str_replace('{{'.$key1.'}}', $value1, $emailContent["body"]);
              }

       
       
                // get SMTP detail ---------------
                $smtpDetail = getSMTPDetail($conn);

                $emailss = new \SendGrid\Mail\Mail(); 
                $emailss->setFrom($smtpDetail["from_email"],$smtpDetail["from_name"]);
                $emailss->setSubject($emailContent["subject"]);
                $emailss->addTo($email,"Website Speddy");
                $emailss->addContent("text/html",$emailContent["body"]);
                $sendgrid = new \SendGrid($smtpDetail["password"]);

                if($email_status == 1){

                    $sendgrid->Send($emailss);
                    $conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at) VALUES('".$user_id."', '".$emailContent["subject"]."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE())"); 
                }else{

                $conn->query("INSERT INTO email_logs (user_id, email_subject, email_message, created_at, status) VALUES('".$user_id."', '".$emailContent["subject"]."', '".$conn->real_escape_string($emailContent["body"])."', CURDATE(), 'Email Notification Off')");                   
                }

       
              // ---------------------------------------------------------------------------


        $_SESSION['success'] = " Hurray! 7 Days free trial activated successfully." ;
        
        header("location: ".HOST_URL."adminpannel/project-dashboard.php?project=".base64_encode($site_id));
        die();

  }
  else {
    $_SESSION['error'] = "Invalid plan selected." ;
  }
 
  header("location: ".HOST_URL."plan.php?plan=".$plan_id);
  die() ;

  }
  else{
    $_SESSION['error'] = "Free Plan Already Activated." ;
  header("location: ".HOST_URL."adminpannel");
  die() ;
  }

 

?>