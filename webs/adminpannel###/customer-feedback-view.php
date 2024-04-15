<?php 
   
   include('config.php');
   require_once('meta_details.php');
   include('session.php');
   require_once('inc/functions.php') ;
//    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
   
   
   ?>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <meta name="description" content="" />
      <meta name="author" content="" />
      <title>Admin Dashboard</title>
      <!-- Favicon-->
      <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
      <?php require_once('inc/style-and-script.php') ; ?>
      
      <?php require_once("inc/style-and-script.php") ; ?>
   <body class="custom-tabel">
      <div class="d-flex" id="wrapper">
         <div class="top-bg-img"></div>
         <?php require_once("inc/sidebar.php"); ?>
         <!-- Page content wrapper-->
         <div id="page-content-wrapper">
            <?php require_once("inc/topbar.php"); ?>
            <!-- Page content-->
            <div class="container-fluid content__up tickets_a">
               <h1 class="mt-4">Cient Feedback View</h1>


               <?php
                 $website_id  = base64_decode($_GET['customer']);
                //  $userId  = base64_decode($_GET['uid']);
                 if(isset($website_id)){
                    // print_r($web1) ;
                    $query = $conn->query(" SELECT * FROM `website_review_feedback` WHERE  website_id = '$website_id' ") ;
                    $sql = $conn->query("select `id`,`manager_id`,`website_url` from boost_website where `id` = $website_id");
                    $result = $sql->fetch_assoc();
                    if( $result){
                       $userId = $result['manager_id'];
                       $user = $conn->query("SELECT `id`, `firstname`, `email` FROM admin_users WHERE `id` = $userId AND `firstname` NOT LIKE '%makkpress%' AND `lastname` NOT LIKE '%makkpress%'");
                       $users = $user->fetch_assoc();
                      
                    }

                    if ( $query->num_rows > 0 ) {

                        $wrf_data = $query->fetch_assoc() ;
                       

                        ?>
                        <div class="profile_tabs">
                        <div class="table speedy-table card__table feedback-card">
                            <div class="data">
                                <div>Client Feedback:</div>
                            </div>
                            <div class="data" style="padding: 0px !important;height: 0px !important;"></div>
                            <div class="data">Username:  <?=isset($users['firstname'])?$users['firstname']:'NA'?></div>
                            <div class="data">Useremail:  <?= isset($users['email'])?$users['email'] : 'NA'?></div>
                            <div class="data">
                                <div>Are you satisfied with the updated speed ?</div>
                                <div><?=$wrf_data["satified_or_not"]?></div>
                            </div>
                            <?php 
                            if ( $wrf_data["satified_or_not"] == "no" ) {
                                ?>
                                <div class="data flex-column">
                                    <div>Please tell us how can we improve:</div>
                                    <div><?=isset($wrf_data["improve"])?$wrf_data["improve"]: 'NA';?></div>
                                </div>
                                <?php

                            }
                            else {

                                ?>
                                <div class="data">
                                    <div>How would you rate your experience?</div>
                                    <div><?=isset($wrf_data["rating"])?$wrf_data["rating"]: 'NA';?></div>
                                </div>
                                <?php

                                if ( $wrf_data["rating"] > 8 ) {
                                    ?>
                                    <div class="data">
                                        <div>Click the button below to review our platform on Trust Pilot</div>
                                        <div><?=($wrf_data["trust_pilot_review"]=="review left")?"I don’t wanna leave review.":" I have left review.";?></div>
                                    </div>
                                    <?php
                                }
                                else {
                                    ?>
                                    <div class="data flex-column">
                                        <div>Please tell us how can we improve:</div>
                                        <div><?=isset($wrf_data["feedback"])?$wrf_data["feedback"]:'NA';?></div>
                                    </div>
                                    <?php
                                }

                            }
                        ?>
                        </div>
                        </div>
                        <?php
                
                    }
                }
                ?>
            </div>
         </div>
      </div>
   </body>   
</html>