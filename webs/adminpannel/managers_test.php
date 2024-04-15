<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");
//echo'<pre>';print_r($row) ;die;

if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}
// print_r($_SESSION) ;

if ( isset($_POST["save-changes"]) ) {

	// print_r($_POST) ;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($fname) || empty($lname) || empty($phone) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	else {

		$columns = " firstname = '$fname' , lastname = '$lname' , phone = '$phone' " ;

		if ( updateTableData( $conn , " admin_users " , $columns , " id = '".$_SESSION['user_id']."' " ) ) {
			$_SESSION['success'] = "Profile details are updated successfully!" ;
		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}
	}

	header("location: ".HOST_URL."adminpannel/edit-profile.php") ;
	die() ;
}


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>
		<script type="text/javascript">
			function status_active(btn){
	   	
	   		var owner = $(btn).attr("data-owner") ;
	   		var checked = $(btn).prop("checked") ;
	   		var status = 0 ;
	   		if ( checked ) { status = 1; }

	   		$.ajax({
	   			url:"inc/update-owner-status.php" ,
	   			method:"POST",
	   			dataType:"JSON",
	   			data:{owner:owner,status:status}
	   		}).done(function(response){

	   			if ( response.status == "done" ) {
	   				$(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+ response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   				$(window).scrollTop(0);
	   			}
	   			else {
	   				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Operation Failed.'+ response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   			}

	   		}).fail(function(){
	   			console.log("error") ;
	   		});
	   	}
		</script>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); 


				?>
				
				<!-- Page content-->
				<div class="container-fluid content__up web_owners">
					<h1 class="mt-4">Website Owners</h1>

					<?php require_once("inc/alert-status.php") ; ?>
                    <div class="profile_tabs">
					<div class="table_S">

						<?php




						?>


					<table class="table speedy-table">
						<thead>
							<tr>
								<th>#</th>
								<th>Fullname</th>
								<th>Email</th>
								<th>Sent Code</th>
								<th>Customer Type</th>
								<th>Views/Steps</th>
								<th class="set__max_width">Phone No</th>
								<th>Platform</th>
								<th>Website</th>
								<!-- <th>Plan Status</th> -->
								<th>Created At</th>
								<!-- <th>Expired At</th> -->
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php

						$managers = getTableData( $conn , " admin_users " , " userstatus NOT LIKE 'admin' and active_status = 1 " , "order by id desc" , 1 ) ;

						//echo'<pre>'; print_r($managers) ;die;

						$keys = 0;

						foreach ($managers as $key => $value) {

							$full = strtolower($value["firstname"].' '.$value["lastname"]);

							if(str_contains($full,"makkpress") ){

							$manager_site = getTableData( $conn , " boost_website " , " manager_id = '".$value["id"]."' " , "order by id asc" , 1 ) ;
                            $resend_code = getTableData( $conn , " user_confirm " , " user_id = '".$value["id"]."' " ) ;
   
							//print_r($manager_site) ;
							// $plan_status = "";
							// if($value["user_type"] != "AppSumo"){
							//   $free = getTableData( $conn , " user_subscriptions_free " , " user_id ='".$value['id']."' and status = 1" ) ;
							// 	if (count($free) > 0) {

							// 		$plan_end_date = $free["plan_end_date"] ;
							// 		$current_date = date('Y-m-d H:i:s') ;
							// 		$diff = date_diff(date_create($current_date) , date_create($plan_end_date) ) ;
							// 		$plan_f= $diff->days;
							// 		$expired= $diff->invert;
							// 		$ms = "";
							// 		if($expired == 1 ){

							// 			$plan_status = "<span class='free expired'>(Free) Expired</span>";
							// 		}else{
							// 			$plan_status = "<span class='free active'>Not Expired</span>";
							// 		}


									
							// 	}
							// }else{
							// 	$plan_status = "<span class='free'>App Sumo</span>";
							// }


							$active_status = ($value["active_status"] == 1) ? "checked" : "" ;
                              
							   
							$keys ++;
							$index = $keys ;
							?>
							
							<tr>
								<td><?=$index;?></td>
								<td><?=$value["firstname"].' '.$value["lastname"];?></td>
								<td><?=$value["email"];?></td>
								<td><?php
									if($value["user_type"] == "AppSumo")
										echo "AS";
									elseif($value["user_type"] == "Dealify")
										echo "DF";
									else
										echo $resend_code["requests"];

								?></td>

								<td>
									<?php
										if($value["user_type"] == "AppSumo")
											echo "App Sumo";
										elseif($value["user_type"] == "Dealify")
											echo "Dealify";
										else if($value["user_type"] == "Lifetime")
											echo "Life Time";
										else if($value["user_type"] == "register"){
										   if($value["self_install"] == 'yes'){
										   	 if($value["self_install_team"] == 'wait'){
										   		 echo "Request Sent (Yes)";
										   	}
										   	else{
										   		echo "Normal Process";
										   	}
										   }else{
										   	 echo "Normal Process";
										   }
										  
										}

									?>
									
								</td>
								<td>
								  <button class="btn btn-primary view-count-button" user_id="<?=$value["id"]?>">Views</button>
								  <?php if($manager_site[0]["installation"]!=""){ ?> 
								<div style="margin-top:5px; text-align:center;width: 80%;" >
								  <br><span class="number"><?=$manager_site[0]["installation"];?></span>
								  /<?php if($manager_site[0]["installation"]>3){echo "Yes";}else{echo "No";} ?>
								  </div>
								 <?php  } ?>


								</td>								
								<td class="set__max_width"><?= '('.$value['country_code'].') '.$value["phone"];?></td>
								<td><?=$manager_site[0]["platform"];?>
									<?php if($manager_site[0]["platform_name"]!=""){
										echo "( ".$manager_site[0]["platform_name"]." )";
									} ?>

								</td>
							
								<td>
								<a href="<?=$manager_site[0]["website_url"];?>" target="_blank"><?=$manager_site[0]["website_url"];?></a>
								<br><a href="<?=$manager_site[1]["website_url"];?>" target="_blank" ><?=$manager_site[1]["website_url"];?></a>
								<br><a href="<?=$manager_site[2]["website_url"];?>" target="_blank" ><?=$manager_site[2]["website_url"];?></a>
								</td>

								<!-- <td><?=$plan_status?></td> -->
								<td><?php 

									$utcTime = $manager_site[0]['created_at'];

									$databsetime = strtotime($utcTime);

									$new_time = date("Y-m-d H:i:s", strtotime('+330 minutes', $databsetime));

									echo $new_time;

								 ?></td>
								<!-- <td></td> -->

								<td>
									<div class="form-check form-switch">
										<input class="form-check-input manager-status-toggle" type="checkbox" data-owner="<?=$value["id"]?>" <?=$active_status?> onclick="status_active(this);" >
									</div>
								</td>
							
								<td class="table__managers__actions">
									<a title="View" href="<?=HOST_URL."adminpannel/email_manager.php?manager=".base64_encode($value["id"])?>" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

									<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
									<path d="M0 500 l0 -360 500 0 500 0 0 360 0 360 -500 0 -500 0 0 -360z m960 295 c0 -20 -42 -61 -207 -205 -114 -99 -217 -183 -230 -186 -13 -4 -33 -4 -46 0 -13 3 -117 87 -230 186 -163 143 -207 187 -207 206 l0 24 460 0 460 0 0 -25z m-777 -203 c65 -58 115 -108 110 -112 -4 -4 -63 -50 -130 -103 l-123 -97 0 216 c0 169 3 214 13 208 6 -4 65 -55 130 -112z m777 -97 l0 -215 -127 101 c-71 56 -129 103 -131 104 -4 3 248 225 254 225 2 0 4 -97 4 -215z m-485 -131 c46 -8 87 7 145 56 l49 41 145 -116 c118 -93 146 -120 146 -140 l0 -25 -461 0 -460 0 3 27 c2 21 34 51 146 140 l143 113 55 -45 c30 -24 70 -47 89 -51z"></path>
									</g>
									</svg></a>

									<a title="View" href="<?=HOST_URL."adminpannel/manager-view.php?manager=".base64_encode($value["id"])?>" class="btn btn-primary"><svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path></svg></a>
									<a href="<?=HOST_URL."adminpannel/loginas.php?project=".base64_encode($manager_site[0]["id"])."&loginas=".base64_encode($value["email"]) ?>" class="btn btn-primary" target="_blank">Login</a>									
								</td>
							</tr>
						
							<?php

						}
						}
						?>
						</tbody>
					</table>
					</div>
					</div>
				</div>
			</div>
		</div>

	</body>
<script type="text/javascript">
	$(document).ready(function() {
	   $(".speedy-table").DataTable();
	   $(".dataTables_length").after("<a href='export_manager.php' class='btn btn-promary export_managers'>Export</a>");
	
	});
</script>

<script>
var viewButtons = document.querySelectorAll(".view-count-button");

viewButtons.forEach(function(button) {
  button.addEventListener("click", function() {

  	var button = this;
  	this.disabled = true;

  	addLoaderButtonJS(button);


    var user_id = this.getAttribute("user_id");
 

        $.ajax({
	      type: "POST",
	      url: "inc/count_views.php",
	      data: {"user_id": user_id},
	      dataType: "text",
	     success: function(data) {
	         	console.log(data);
	         	button.disabled = false;
	         	button.innerHTML = "Views";
				Swal.fire({
			      html: data,
			      showConfirmButton: false,
			      width: '50%',
			   	  height: '50%',
			      showCloseButton: true,
			      allowOutsideClick: false
			    });  
	      }

	    });


 




  });
});



function addLoaderButtonJS(selector){
			console.log("adding Loader");	
			selector.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" style="background:transparent; height:24px;width: auto;" viewBox="0 0 105 105" fill="#fff" style="&#10;    background: #000;&#10;"> <circle cx="12.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="0s" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="12.5" cy="52.5" r="12.5" fill-opacity=".5"> <animate attributeName="fill-opacity" begin="100ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="300ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="52.5" r="12.5"> <animate attributeName="fill-opacity" begin="600ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="800ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="52.5" r="12.5"> <animate attributeName="fill-opacity" begin="400ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="12.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="700ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="500ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="200ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> </svg>';
}



</script>

</html>