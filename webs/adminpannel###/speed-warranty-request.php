<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;
$website_id=$_GET['project'];

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>
		<style>
			.loader {
			    background-color: #ffffff5e;
			    height: 100%;
			    position: absolute;
			    text-align: center;
			    margin: auto;
			    display: none;
			    width: 100%;
			}
		</style>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up speed_warranty_request">

					<h1 class="mt-4">Speed Warranty Request</h1>
					<div class="profile_tabs">
					<!-- <div class="alert-status"></div> -->
						<?php require_once("inc/alert-status.php") ; ?>


                        <div class="table_S"> 
						<table class="table table-bordered" id="speed-warranty-table" >
								<thead>
									<tr>
										<th>S.NO</th>
										<th>NAME</th>
										<th>SITE NAME</th>
										<th>EMAIL ID</th>
										<th>PHONE NUMBER</th>
										<th>PLAN DESCRIPTION</th>
										<th>PLAN STATUS</th>
										<th>STATUS</th>
										<th>DATE</th>
										
									</tr>
								</thead>
								<tbody>

								<?php $i=1; 
								$user_subscription_data = getTableData( $conn , " details_warranty_plans " ,"1","",1 ) ;
								        if(count($user_subscription_data) > 0) {
					                    	foreach ($user_subscription_data as $key => $user_plan) {

											$manager_data = getTableData( $conn , " admin_users " , " id = '".$user_plan['manager_id']."' " ) ;
											$site_data = getTableData( $conn , " boost_website " , " id = '".$user_plan['website_id']."' " ) ;
											if($user_plan['status']=='succeeded'){
												$status='Active';
											}else{
												$status='Expire';

											}

								                            	
								?>		<tr>
										<td><?=$i++?></td>
										<td><?php echo $manager_data['firstname']." ".$manager_data['lastname'];?></td>
										<td><?php echo parse_url($site_data['website_url'])['host'];?></td>
										<td><?php echo $manager_data['email'];?></td>
										<td><?php echo $manager_data['phone'];?></td>
										<td><?=$user_plan['description']?></td>
										<td><?=$status?></td>
										<td><a href="javascript:void(0);" onclick="check_update(this);" class="warranty_monitoring colorchange<?=$user_plan['id'];?> <?php if($user_plan['monitoring']==1)    { echo "text-danger";	 }else{ echo "text-success" ;}?> "  data-monitoring="<?=$user_plan['monitoring'];?>" data-id="<?=$user_plan['id'];?>">
											
											<svg class="svg-inline--fa fa-circle-check" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"></path></svg>
										
										
										</a></td>
										<td><?=$user_plan['paymentDate']?></td>
										</tr>

							<?php
                            	}
                            }

                            ?>


                        </tbody>
                    </table>
						</div>


			<!-- </div> -->
			</div>
		</div>
<script type="text/javascript">
	 $(document).ready( function () {
		
	$("#speed-warranty-table").DataTable();
  });
	 function check_update(btn){



	 	var id=$(btn).attr("data-id");
	 	var monitoring_val=0;


	 	// var ads=$(id).attr("data-monitoring") ;
	 	var ads = $(btn).data("monitoring") ;
	 		console.log(ads);

	 	if (ads==0) {
	 		monitoring_val=1;
	 		console.log("ok");

	 	}else{
	 		monitoring_val=0;
	 		console.log("not ok");
	 	}
	 	
	 	
    $.ajax({
        url: "check_update.php",
        type: "POST",
    	  dataType: "json",
        data: {
            id: id,
            monitoring_val:monitoring_val

        },
        success: function (data) {
        	if(data.status=="done"){
        	if(data.message==0){
        		var warranty_msg = '' ;
        		$(".colorchange"+id).addClass("text-success");
        		$(".colorchange"+id).removeClass("text-danger");
        		$(".colorchange"+id).data("monitoring",monitoring_val);
				warranty_msg = 'Status stopped!' ;


        	}else{
        		$(".colorchange"+id).addClass("text-danger");
        		$(".colorchange"+id).removeClass("text-success");
        		$(".colorchange"+id).data("monitoring",monitoring_val);
        		warranty_msg = 'Status completed!' ;

        	}
        	  $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+warranty_msg+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
        	}else{ $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;}
           

        }
    });

	 }
</script>
	</body>
</html>
