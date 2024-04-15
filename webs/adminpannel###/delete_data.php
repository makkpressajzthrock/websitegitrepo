<?php

ini_set('max_execution_time', '0'); // for infinite time of execution
include('config.php');
error_reporting(E_ALL); error_reporting(-1); ini_set('error_reporting', E_ALL);
 

$userQuery = "SELECT id  FROM `admin_users` WHERE `firstname` LIKE '%makkpress%' and deleted_status = '0' and DATE(created_at) <= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ORDER BY id ASC LIMIT 0,1";

$query0 = $conn->query($userQuery);

if ($query0->num_rows <= 0 ) {

    $userQuery = "SELECT id  FROM `admin_users` WHERE `lastname` LIKE '%makkpress%' and deleted_status = '0' and DATE(created_at) <= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ORDER BY id ASC LIMIT 0,1";
    
    $query1 = $conn->query($userQuery);

    if ($query1->num_rows <= 0 ) {

        $userQuery = " SELECT id FROM `admin_users` WHERE phone = '' and deleted_status = '0' and DATE(created_at) <= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ORDER BY id ASC LIMIT 0,1 ; ";
        
        /** do not uncomment for now **/ 
        /*** $query2 = $conn->query($userQuery);
        if ($query2->num_rows <= 0 ) {
            $userQuery = "SELECT id FROM `admin_users` WHERE `email` LIKE '%makkpress%' and deleted_status = '0' and DATE(created_at) <= DATE_SUB(CURDATE(), INTERVAL 7 DAY) ORDER BY id ASC LIMIT 0,1";
            // $query3 = $conn->query($userQuery);
        }
        ***/

    }
}

echo "userQuery : ".$userQuery."<hr>" ;
// die() ;

$query = $conn->query($userQuery);
//echo "hello";die;
if ($query->num_rows > 0 ) {
    $admin_user = $query->fetch_all(MYSQLI_ASSOC) ;
}

// echo "<pre>";
print_r($admin_user); echo "<hr>" ; 

foreach($admin_user as $au){


    $sqlNew = "SELECT id FROM boost_website where  manager_id = '".$au['id']."' ";
    echo "<hr>sqlNew : ".$sqlNew ;
    $boost = $conn->query($sqlNew);
    if ($boost->num_rows > 0 ) {
        $boost_web = $boost->fetch_all(MYSQLI_ASSOC) ;
    }
    
    echo '<hr>boost_web : ';
    print_r($boost_web);

    foreach($boost_web as $bw){

        // $help_select = "SELECT * FROM help_support_tickets where website_id = '".$bw['id']."'";
        // echo "<hr>help_select : ".$help_select ;
        // $help_sel = $conn->query($help_select);
        // $act_help = $help_sel->fetch_all(MYSQLI_ASSOC) ;

        // print_r($boost_web);

        $help_support = $conn->query("DELETE FROM help_support_tickets where website_id = '".$bw['id']."' "); // website_it 


        // $pag = "SELECT id FROM pagespeed_dummy_report where website_id = '".$bw['id']."'";
        // echo "<hr>pag : ".$pag ;
        // $pags = $conn->query($pag);
        // $page_act = $pags->fetch_all(MYSQLI_ASSOC) ;

        // print_r($page_act);
        $page_speed = $conn->query("DELETE FROM pagespeed_dummy_report where website_id = '".$bw['id']."' "); //website_id
        
        // echo '<br>';
        // $pr = "SELECT id,website_id,parent_website FROM pagespeed_report where website_id = '".$bw['id']."'";
        // echo "<hr>pr : ".$pr ;
        // $pagr = $conn->query($pr);
        // $page_report = $pagr->fetch_all(MYSQLI_ASSOC) ;

        // print_r($page_report);

        $page_report = $conn->query("DELETE FROM pagespeed_report where website_id = '".$bw['id']."' "); //website_id

        // echo $sv = "SELECT * FROM site_visit_count where website_id = '".$bw['id']."'";
        // $svr = $conn->query($sv);
        // $svr_Act = $svr->fetch_all(MYSQLI_ASSOC) ;

        // print_r($svr_Act);
        $svc = $conn->query("DELETE FROM site_visit_count where website_id = '".$bw['id']."' ");   //website

        // echo  $tv = "SELECT * FROM team_access where website_id = '".$bw['id']."'";
        // $tvr = $conn->query($tv);
        // $tvr_Act = $tvr->fetch_all(MYSQLI_ASSOC) ;

        // print_r($tvr_Act);
        $ts = $conn->query("DELETE FROM team_access where website_id = '".$bw['id']."' ");   //website

        // echo $tp = "SELECT * FROM temp_pagespeed_report where website_id = '".$bw['id']."'";
        // $tpr = $conn->query($tp);
        // $tpr_Act = $tpr->fetch_all(MYSQLI_ASSOC) ;

        // print_r($tpr_Act);
        $tpr = $conn->query("DELETE FROM temp_pagespeed_report where website_id = '".$bw['id']."' ");   //website 

        // echo $wi = "SELECT * FROM website_improve_needed where website_id = '".$bw['id']."'";
        // $wir = $conn->query($wi);
        // $wir_Act = $wir->fetch_all(MYSQLI_ASSOC) ;

        // print_r($wir_Act);
        $win = $conn->query("DELETE FROM website_improve_needed where website_id = '".$bw['id']."' ");   // website_id

        // echo  $wr = "SELECT * FROM website_review_feedback where website_id = '".$bw['id']."'";
        // $wrr = $conn->query($wr);
        // $wrr_Act = $wrr->fetch_all(MYSQLI_ASSOC) ;

        // print_r($wrr_Act);
        $wrf = $conn->query("DELETE FROM website_review_feedback where website_id = '".$bw['id']."' ");   // website_id

        // echo $ws = "SELECT * FROM website_speed_history where website_id = '".$bw['id']."'";
        // $wsr = $conn->query($ws);
        // $wsr_Act = $wsr->fetch_all(MYSQLI_ASSOC) ;

        // print_r($wsr_Act);
        $wsh = $conn->query("DELETE FROM website_speed_history where website_id = '".$bw['id']."' ");   // website_id

        /***********/
        $sl = $conn->query("DELETE FROM script_log where site_id = '".$bw['id']."' ");  
        $sl = $conn->query("DELETE FROM core_web_vital where website_id = '".$bw['id']."' ");   
        /***********/
    
    }

    // echo "another foreach loop";
    // $bi = "SELECT * FROM `billing-address` where manager_id = '".$au['id']."'";
    // $bir = $conn->query($bi);
    // $bir_Act = $bir->fetch_all(MYSQLI_ASSOC) ;

    // print_r($bir_Act);
    $billing = $conn->query("DELETE FROM `billing-address` where manager_id = '".$au['id']."' ");

    // echo $bo = "SELECT id,manager_id,platform FROM boost_website where manager_id = '".$au['id']."' ";
    // $bor = $conn->query($bo);
    // $bor_Act = $bor->fetch_all(MYSQLI_ASSOC) ;

    // print_r($bor_Act);
    $boost = $conn->query("DELETE FROM boost_website where manager_id = '".$au['id']."' ");

    // echo $add = "SELECT * FROM additional_websites where manager_id = '".$au['id']."' ";
    // $addr = $conn->query($add);
    // $addr_Act = $addr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($addr_Act);
    $additional = $conn->query("DELETE FROM additional_websites where manager_id = '".$au['id']."' ");

    // echo $ckd = "SELECT * FROM check_installation where manager_id = '".$au['id']."' ";
    // $ckdr = $conn->query($ckd);
    // $ckdr_Act = $ckdr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($ckdr_Act);
      $check = $conn->query("DELETE FROM check_installation where manager_id = '".$au['id']."' ");

    // echo $cad = "SELECT * FROM cart where user_id = '".$au['id']."' ";
    // $cadr = $conn->query($cad);
    // $cadr_Act = $cadr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($cadr_Act);
      $cart = $conn->query("DELETE FROM cart where user_id = '".$au['id']."' ");

    // echo $dad = "SELECT * FROM details_warranty_plans where manager_id = '".$au['id']."'  ";
    // $dadr = $conn->query($dad);
    // $dadr_Act = $dadr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($dadr_Act);
      $details = $conn->query("DELETE FROM details_warranty_plans where manager_id = '".$au['id']."' ");

    // echo  $emd = "SELECT * FROM email_logs where user_id = '".$au['id']."'  ";
    // $emdr = $conn->query($emd);
    // $emdr_Act = $emdr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($emdr_Act);
      $email_log = $conn->query("DELETE FROM email_logs where user_id = '".$au['id']."' ");

    // echo $fs = "SELECT id,user_id FROM flow_step where user_id = '".$au['id']."'  ";
    // $fsr = $conn->query($fs);
    // $fsr_Act = $fsr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($fsr_Act);
      $flow_step = $conn->query("DELETE FROM flow_step where user_id = '".$au['id']."' ");

    // echo $gen = "SELECT * FROM generate_script_request where manager_id = '".$au['id']."'  ";
    // $genr = $conn->query($gen);
    // $genr_Act = $genr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($genr_Act);
      $genrate = $conn->query("DELETE FROM generate_script_request where manager_id = '".$au['id']."' ");

    // echo $ip = "SELECT * FROM india_price_request where manager_id = '".$au['id']."'  ";
    // $ipr = $conn->query($ip);
    // $ipr_Act = $ipr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($ipr_Act);
    $ind_price = $conn->query("DELETE FROM india_price_request where manager_id = '".$au['id']."' "); 

    // echo  $km = "SELECT * FROM keep_me_posted where user_id = '".$au['id']."'  ";
    // $kmr = $conn->query($km);
    // $kmr_Act = $kmr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($kmr_Act);
      $keep_me = $conn->query("DELETE FROM keep_me_posted where user_id = '".$au['id']."' "); 

    // echo $mnc = "SELECT *  FROM manager_company where user_id = '".$au['id']."'  ";
    // $mncr = $conn->query($mnc);
    // $mncr_Act = $mncr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($mncr_Act);
      $manager_comp = $conn->query("DELETE FROM manager_company where user_id = '".$au['id']."' "); 

    // echo $pms = "SELECT *  FROM payment_method_details where manager_id = '".$au['id']."'  ";
    // $pmsr = $conn->query($pms);
    // $pmsr_Act = $pmsr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($pmsr_Act);
      $pmd = $conn->query("DELETE FROM payment_method_details where manager_id = '".$au['id']."' "); 

    // echo $rss = "SELECT *  FROM report_send_status where manager_id = '".$au['id']."'  ";
    // $rssr = $conn->query($rss);
    // $rssr_Act = $rssr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($rssr_Act);
      $rss = $conn->query("DELETE FROM report_send_status where manager_id = '".$au['id']."' ");   

    // //  echo $trs = "SELECT *  FROM ticket_replies where manager_id = '".$au['id']."'  ";
    // //  $trsr = $conn->query($trs);
    // //  $trsr_Act = $trsr->fetch_all(MYSQLI_ASSOC) ;

    // //  print_r($trsr_Act);
      $tr = $conn->query("DELETE FROM ticket_replies where manager_id = '".$au['id']."' ");    

    // echo $ucs = "SELECT *  FROM user_confirm where user_id = '".$au['id']."'  ";
    // $ucsr = $conn->query($ucs);
    // $ucsr_Act = $ucsr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($ucsr_Act);
      $uc = $conn->query("DELETE FROM user_confirm where user_id = '".$au['id']."' ");    

    // echo $uss = "SELECT *  FROM user_subscriptions where user_id = '".$au['id']."'  ";
    // $ussr = $conn->query($uss);
    // $ussr_Act = $ussr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($ussr_Act);
     $us = $conn->query("DELETE FROM user_subscriptions where user_id = '".$au['id']."' ");    

    // echo  $ussl = "SELECT *  FROM user_subscriptions_free where user_id = '".$au['id']."'  ";
    // $usslr = $conn->query($ussl);
    // $usslr_Act = $usslr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($usslr_Act);
    $usf = $conn->query("DELETE FROM user_subscriptions_free where user_id = '".$au['id']."' "); 

    // echo  $usll = "SELECT *  FROM user_subscriptions_log where user_id = '".$au['id']."'  ";
    // $usllr = $conn->query($usll);
    // $usllr_Act = $usllr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($usllr_Act);
     $usl = $conn->query("DELETE FROM user_subscriptions_log where user_id = '".$au['id']."' ");  

    // echo $ustl = "SELECT *  FROM user_subscription_trial_data where user_id = '".$au['id']."'  ";
    // $ustlr = $conn->query($ustl);
    // $ustlr_Act = $ustlr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($ustlr_Act);
    $ustd = $conn->query("DELETE FROM user_subscription_trial_data where user_id = '".$au['id']."' ");  

    /***********/
    $st = $conn->query("DELETE FROM support_tickets where manager_id = '".$au['id']."' ");   
    /***********/

    // echo $aul = "SELECT id,phone,email  FROM admin_users where id = '".$au['id']."'  ";
    // $aulr = $conn->query($aul);
    // $aulr_Act = $aulr->fetch_all(MYSQLI_ASSOC) ;

    // print_r($aulr_Act);
    $conn->query("UPDATE admin_users SET deleted_status = 1 where id = '".$au['id']."'");
    
}



// $userQuery = "SELECT *  FROM `admin_users` WHERE `firstname` LIKE '%makkpress%' limit 0,10";
// $userQuery = "SELECT *  FROM `admin_users` WHERE `lastname` LIKE '%makkpress%' limit 0,10";
// $userQuery = "SELECT *  FROM `admin_users` WHERE `email` LIKE '%makkpress%' limit 0,10";
?>