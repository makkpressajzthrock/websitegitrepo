<?php 
require_once('config.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;

if($_SESSION['role'] == 'admin'){
	header("location:".HOST_URL."adminpannel/home.php");
}

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up add_project_sss" >
					

					<?php
						$user_id = $_SESSION["user_id"] ;
						// My Projects
					?>





		<!-- <div class="add-project-data" style="display: none;"> -->
<h1>Add Project</h1>
		<div class="popup_wrapper">
			<div class="close-popup-btn" style="display: none; cursor: pointer;"><i class="fa fa-times" aria-hidden="true"></i>
</div>
			<div class="web_list">
			<?php
	 	  $projects = getTableData( $conn , " user_subscriptions " , " user_id = '".$_SESSION['user_id']."' and  is_active = 1" , "" , 1  ) ;
 			
 		  if(count($projects)>0){ 
			foreach ($projects as $project_data) 
			{
		 	  $webs = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' and subscription_id='".$project_data['id']."' and plan_type = 'Subscription' " , "" , 1  ) ;

		 	  $av = $project_data['site_count'] - count($webs);

		 	  $av = $av." Available"; 

		 	  if($project_data['site_count'] =="Unlimited"){
		 	  	$av ="Unlimited";
		 	  }

		 	  $is_active="";
		 	  if($project_data['is_active']==0){
			 	  $is_active=" - Cancled";

		 	  }

			  $plan = getTableData( $conn , " plans " , " id ='".$project_data['plan_id']."' and status = 1" ) ;

      			echo '<li class="dropdown-header">'.$plan['name'].' Plan ('.$av.')'.$is_active.'</li>';

		 	  	if(count($webs)>0){
					foreach ($webs as $web) 
					{ 	  
						echo '<li><a href="'.HOST_URL.'adminpannel/project-dashboard.php?project='.$web['id'].'">'.parse_url($web['website_url'])["host"].'</a></li>';
					}
				}
				if( $project_data['is_active'] == 1){
					echo '<li><a href="'.HOST_URL.'adminpannel/add-website.php?sid='.base64_encode($project_data['id']).'">Add Project</a></li>';
				}
				else{
					echo '<li class="disabled">Add Project</li>';
				}

      
			}
		}

?>
</div>
</div>
		<!-- </div> -->
	</div>


<script>
// 	$(".add-new-project").on("click",function(){

//      $(".close-popup-btn").css("display","block");
//     $(".add-project-data").css("display","block");
     

// });
// 	$(".close-popup-btn").on("click",function(){

//      $(".close-popup-btn").css("display","none");
//     $(".add-project-data").css("display","none");

// });

</script>
	</body>
</html>
