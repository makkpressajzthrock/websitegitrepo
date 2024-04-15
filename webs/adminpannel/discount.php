<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if ( empty($_SESSION['user_id']) || empty($_SESSION['role']) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
// check sign-up process complete
// checkSignupComplete($conn) ;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$ticket_id = $_GET['ticket_id'];

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

$user_id = $_SESSION['user_id'];



?>
<?php require_once("inc/style-and-script.php") ; ?>
		<style type="text/css">
			/* Mark input boxes that gets an error on validation: */
.invalid {
  background-color: #ffdddd;
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
				<div class="container-fluid content__up discount">
					
					<h1>Discount</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="profile_tabs">
					<a href="<?=HOST_URL?>adminpannel/add-discount.php" class="btn btn-primary">Add Discount</a>

					<?php

						$user_id = $_SESSION["user_id"] ;
					?>
                    <div class="table_S">	
					<table class="speedy-table table ">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Sites</th>
								<th>Discount</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php

							$discount = getTableData( $conn , " discount " , "" , "" , 1 ) ;

							// print_r($websites) ;

							if ( count($discount) > 0 ) 
							{
								$sno = 1 ;
								foreach ($discount as $discount_data) {

						
									

									?>
									<tr class="discount<?=$discount_data['id']?>" ><td><?=$sno?></td>
										<td><?=$discount_data['sites']?></td>
										<td><?=$discount_data['discount']?>%</td>
										<td><a href="<?=HOST_URL."adminpannel/edit-discount.php?edit=".base64_encode($discount_data['id'])?>"><button class="btn btn-success"><svg class="svg-inline--fa fa-pen-to-square" aria-hidden="true" focusable="false" data-prefix="far" data-icon="pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M373.1 24.97C401.2-3.147 446.8-3.147 474.9 24.97L487 37.09C515.1 65.21 515.1 110.8 487 138.9L289.8 336.2C281.1 344.8 270.4 351.1 258.6 354.5L158.6 383.1C150.2 385.5 141.2 383.1 135 376.1C128.9 370.8 126.5 361.8 128.9 353.4L157.5 253.4C160.9 241.6 167.2 230.9 175.8 222.2L373.1 24.97zM440.1 58.91C431.6 49.54 416.4 49.54 407 58.91L377.9 88L424 134.1L453.1 104.1C462.5 95.6 462.5 80.4 453.1 71.03L440.1 58.91zM203.7 266.6L186.9 325.1L245.4 308.3C249.4 307.2 252.9 305.1 255.8 302.2L390.1 168L344 121.9L209.8 256.2C206.9 259.1 204.8 262.6 203.7 266.6zM200 64C213.3 64 224 74.75 224 88C224 101.3 213.3 112 200 112H88C65.91 112 48 129.9 48 152V424C48 446.1 65.91 464 88 464H360C382.1 464 400 446.1 400 424V312C400 298.7 410.7 288 424 288C437.3 288 448 298.7 448 312V424C448 472.6 408.6 512 360 512H88C39.4 512 0 472.6 0 424V152C0 103.4 39.4 64 88 64H200z"></path></svg></button></a>&nbsp;<a href="javascript:void(0);" onclick="delete_dis(this);" data-dis="<?=$discount_data['id']?>"><button class="btn btn-success"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path></svg></button></a></td>
									</tr>
									<?php
									$sno++ ;
								}
							}
							else {
								?><tr><td colspan="4"> No Data found.</td></tr><?php
							}

						?>

						</tbody>
					</table>
						</div>
						</div>

				</div>
			</div>
		</div>
<script type="text/javascript">
	 function delete_dis(btn){



	 	var dis_id=$(btn).attr("data-dis");
	 	var confirm_box=confirm("do you want to delete this discount?");
	 	if(confirm_box==true){
	 		// console.log("ok");
	 	
	 	 	
		    $.ajax({
		        url: "delete-discount.php",
		        type: "POST",
		    	  dataType: "json",
		        data: {
		            dis_id: dis_id,
		            confirm_box:confirm_box

		        },
		        success: function (data) {
		        	if(data.status=="done"){
		        
		        	  $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+data.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
		        	  $(".discount"+dis_id).hide();
		        	}else{ $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">'+data.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;}
		           

		        }
		    });
		}

	 }
	
</script>
	</body>
</html>

