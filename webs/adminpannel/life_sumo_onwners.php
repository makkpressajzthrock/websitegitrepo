<?php 

include('config.php');
require_once('meta_details.php');
require_once('inc/functions.php') ;
   
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
				<div class="container-fluid content__up sumo_owners_s">
					<h1 class="mt-4">Lifetime Access Code</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					
					<div class="codesData profile_tabs">
						<div class="table_S">
						<table id="mytable" class="table">
							<thead>
								<tr>
									<td>S.No</td>
									<td>Fullname</td>
									<td>Email</td>
									<td>Phone No</td>
									<th>Website</th>
									<th>Status</th>
									<th>Life sumo code</th>
									<th>Action</th>
									
								</tr>
							</thead>
							<tbody>
					<?php
						$fetchCode = "SELECT * FROM `admin_users` WHERE sumo_code !='' AND user_type ='Lifetime'";
						$fetchResult = mysqli_query($conn,$fetchCode);
						$sno = 0;
						while ($num = mysqli_fetch_assoc($fetchResult)) 
						{ $manager_site = getTableData( $conn , " boost_website " , " manager_id = '".$num["id"]."' " , "" , 1 ) ; 
					$active_status = ($num["active_status"] == 1) ? "checked" : "" ;?>
								<tr>
									<td><?php echo ($sno+1);?></td>
									<td><?php echo $num['firstname']." ".$num['lastname'];?></td>
									<td><?php echo $num['email'];?></td>
									<td><?php echo $num['phone'];?></td>
									<td><?=$manager_site[0]["website_url"];?><br>	<?=$manager_site[1]["website_url"];?><br><?=$manager_site[2]["website_url"];?></td>

								<td>
									<div class="form-check form-switch">
										<input class="form-check-input manager-status-toggle" type="checkbox" data-owner="<?=$num["id"]?>" <?=$active_status?>>
									</div>
								</td>
									<td><?php echo $num['sumo_code'];?></td>
							
								<td>
									<a href="<?=HOST_URL."adminpannel/life_sumo_onwners_view.php?manager=".$num["id"]?>" class="btn btn-primary"><svg class="svg-inline--fa fa-eye" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM432 256c0 79.5-64.5 144-144 144s-144-64.5-144-144s64.5-144 144-144s144 64.5 144 144zM288 192c0 35.3-28.7 64-64 64c-11.5 0-22.3-3-31.6-8.4c-.2 2.8-.4 5.5-.4 8.4c0 53 43 96 96 96s96-43 96-96s-43-96-96-96c-2.8 0-5.6 .1-8.4 .4c5.3 9.3 8.4 20.1 8.4 31.6z"></path></svg></a>&nbsp;
								</td>
									
								</tr>
					<?php
						$sno++;		
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
	<script>

		$(document).ready(function () {
	        $('#mytable').DataTable();
	    });	

	
	</script>
	<script type="text/javascript">
	$(document).ready(function() {
	   $(".speedy-table").DataTable();

	   	$(".manager-status-toggle").click(function(){
	   		var owner = $(this).attr("data-owner") ;
	   		var checked = $(this).prop("checked") ;
	   		var status = 0 ;
	   		if ( checked ) { status = 1; }

	   		$.ajax({
	   			url:"inc/life-update-owner-status.php" ,
	   			method:"POST",
	   			dataType:"JSON",
	   			data:{owner:owner,status:status}
	   		}).done(function(response){

	   			if ( response.status == "Active" ) {
	   				$(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+ response.message+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   			}
	   			else {
	   				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+ response.message+'.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   			}

	   		}).fail(function(){
	   			console.log("error") ;
	   		});
	   	});
	});
</script>


</html>

