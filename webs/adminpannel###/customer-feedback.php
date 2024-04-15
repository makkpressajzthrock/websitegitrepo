<?php 
   
   include('config.php');
   require_once('meta_details.php');
   include('session.php');
   require_once('inc/functions.php') ;
   // ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
   
   
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
               <h1 class="mt-4">Client Feedback</h1>
              
               <div class="profile_tabs">
                  <div class=" table-responsive">
                     <div class="table_S">
                        <table class="feedback_s"  id="mytable">
                           <thead>
                              <tr>
                                 <th style="width:35px;">#</th>
                                 <th>Username</th>
                                 <th>Useremail</th>
                                 <th>Website Url</th>
                                 <th>Satisfied</th>
                                 <th>Rating</th>
                                 <th>Date</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php				
                                 $sno = 0;
                                 $data = getTableData($conn, 'website_review_feedback', '', '', 1);
                                 foreach($data as $row){ $sno++;
                                 $website_id = $row['website_id'];
                                 $sql = $conn->query("select `id`,`manager_id`,`website_url` from boost_website where `id` = $website_id");
                                 $result = $sql->fetch_assoc();
                                 if( $result){
                                    $userId = $result['manager_id'];
                                    $user = $conn->query("SELECT `id`, `firstname`, `email` FROM admin_users WHERE `id` = $userId AND `firstname` NOT LIKE '%makkpress%' AND `lastname` NOT LIKE '%makkpress%' ");
                                    $users = $user->fetch_assoc();
                                   
                                                
                                 }
                                        
                                 
                                  ?>
                                 <?php if(isset($users['firstname']) && isset($users['email'])){ ?>
                              <tr>
                                 
                                 <td><?=$sno;?></td>
                                 <td><?=isset($users['firstname'])?$users['firstname']:"NA";?></td>
                                 <td><?=isset($users['email'])?$users['email']:"NA";?></td>
                                 <td style="max-width:100% !important;"><?=$result['website_url']?></td>
                                 <td><?=$row['satified_or_not']?></td>
                                 <td><?=isset($row['rating'])?$row['rating']: 'NA'?></td>
                                 <td><?=$row['created_at']?></td>
                                 <td>
                                    <a title="View" href="<?=HOST_URL?>adminpannel/customer-feedback-view.php?customer=<?=base64_encode($website_id)?>" class="btn btn-primary" target="_blank">
                                       <svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg="">
                                          <path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path>
                                       </svg>
                                    </a>
                                 </td>
                              </tr>
                              <?php } } ?>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </body>
   <script>
      $(document).ready(function () {
             $('#mytable').DataTable();
      	
         });	
   </script>
</html>