<?php

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

$qry=" select count(userstatus)  AS userstatus from admin_users where userstatus='manager'";
$connect_qry = mysqli_query($conn, $qry);
$run_qry = mysqli_fetch_array($connect_qry);




?>

<?php require_once("inc/style-and-script.php") ; ?>
		
		
	</head>
<body>

 <div class="container-fluid mt-4">
 
       <div class="row">
	   
	      <div class="col-md-4">
		  
		  <a href="<?=HOST_URL."adminpannel/managers.php" ?>">
		 
		        <div class="card">
				 
				      <div class="card-body">
					    <div class="row">
						     <div class="col-md-8">
					   <h1><?php  echo $run_qry['userstatus']; ?></h1><br>
					         
							 <h3> Website Owners </h3>
					     
					          </div>
							  <div class="col-md-4 mt-4">
							  <i class='fas fa-users' style='font-size:36px'></i>
							  </div>
							  </div>
					  </div>
				
				</div>
		  </a>
		  </div>
		  
		  <?php    
		  
		   $qryy=" select count(*) as id from user_subscription ";
$connect_qryy = mysqli_query($conn, $qryy);
$run_qryy = mysqli_fetch_array($connect_qryy);


		  
		  ?>
		  
	        <div class="col-md-4">
		   
		  <a href="<?=HOST_URL."adminpannel/payments.php" ?>">
		        <div class="card">
				 
				      <div class="card-body">
					   <div class="row">
						     <div class="col-md-8">
					   <h1><?php  echo $run_qryy['id']; ?></h1><br>
					         
							 <h3> Subscription </h3>
					     
					          </div>
							  <div class="col-md-4 mt-4">
							  <i class='fas fa-user-plus' style='font-size:36px'></i>
							  </div>
							  </div>
					  </div>
				
				</div>
		    </a>
		  </div>
		  
		  	  <?php    
		  
		   $qry_month=" select sum(paid_amount) as paid_amount  from user_subscriptions where plan_interval='month' ";
$connect_qrys = mysqli_query($conn, $qry_month);


		  
		  ?>
		  
		    <div class="col-md-4">
		  
		        <div class="card">
				  <h4> Total Amount </h4>
				      <div class="card-body">
					 <div class="row">
					 <div class="col-md-6 mt-3">
					   
					  <?php
					  while($run_qryy_month = mysqli_fetch_array($connect_qrys))
					  {
					  ?>
						     
					   <h4>$ <?php  echo $run_qryy_month['paid_amount']; ?></h4><br>
					   </div>
					          <div class="col-md-6 mt-3">
							<h4> Monthly </h4>
							  </div>
							 
					     </div>
					         
							    <?php
					  }
							  ?>
							  
			
						
					  </div>
				
				</div>
		  
		  </div>
		  
		   <?php    
		  
		   $qry_yearly=" select sum(paid_amount) as paid_amount  from user_subscriptions where plan_interval='year' ";
$connect_qryss = mysqli_query($conn, $qry_yearly);


		  
		  ?>
		    
							   <?php
					  while($run_qryy_yearly = mysqli_fetch_array($connect_qryss))
					  {
					  ?>
		  
		    <div class="col-md-4">
		  
		        <div class="card">
				  <h4> Total Amount </h4>
				      <div class="card-body">
					 <div class="row">
					 <div class="col-md-6 mt-4">
					   
					  
					  <h4>$ <?php  echo $run_qryy_yearly['paid_amount']; ?></h4><br>
					   </div>
					          <div class="col-md-6 mt-4">
							<h4> Yearly </h4>
							  </div>
							 
					     </div>
					         
							    <?php
					  }
							  ?>
							  
			
						
					  </div>
				
				</div>
		  
		  </div>
		  
		
		     <?php    
		  
		   $qryg=" select count(*) as status from  user_subscriptions GROUP BY user_id ";
$connect_qr_g = mysqli_query($conn, $qryg);
$run_qr_g = mysqli_fetch_array($connect_qr_g);


		  
		  ?>
		  
		  
		  	  <div class="col-md-4">
		  
		        <div class="card">
				 
				      <div class="card-body">
					    <div class="row">
						     <div class="col-md-8">
					   <h1><?php  echo $run_qr_g['status']; ?></h1><br>
					         
							 <h3> Active Plans</h3>
					     
					          </div>
							  <div class="col-md-4 mt-4">
							<i class="fa fa-user" style="font-size:48px;"></i>
							  </div>
							  </div>
					  </div>
				
				</div>
		  
		  </div>
		  
		  
		  
		    <div class="col-md-4">
		  
		        <div class="card">
				 
				      <div class="card-body">
					    <div class="row">
						     <div class="col-md-8">
					   <h1>0</h1><br>
					         
							 <h3> Inactive Plans</h3>
					     
					          </div>
							  <div class="col-md-4 mt-4">
							<i class="fa fa-user-slash" style="font-size:38px;"></i>
							  </div>
							  </div>
					  </div>
				
				</div>
		  
		  </div>
		   
	

  
	       

	   </div>
 
 
 </div>




</body>

</html>	