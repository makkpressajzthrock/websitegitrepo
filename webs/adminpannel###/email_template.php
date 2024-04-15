<?php 

require_once('inc/functions.php') ;
require_once('meta_details.php') ;

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if ( empty($_SESSION['user_id']) || empty($_SESSION['role']) ) {
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
				<div class="container-fluid content__up email_template">
					<h1>Email template</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="profile_tabs">

<div class=" text-left"> <a href="<?=HOST_URL."adminpannel/add-email.php" ?>" class="btn btn-primary ">Add Email Template</a></div>
<?php
				
				 $qry="select * from email_template";
				 $cont_qry=mysqli_query($conn,$qry);
				 
				
				?>
				
				<div class="table_S">
					<table class="table speedytable">
						<thead>
							<tr>
							   <th> #</th>
								<th>Title</th>
								<th>Subject</th>
								<th>Description</th>
								<th>Action</th>
							</tr>
						</thead>
						
						<tbody>
						<?php
							$index=1;
							while($run_qry=mysqli_fetch_array($cont_qry)) {
								?>
								<tr>
									<td><?php echo $index++; ?></td>
									<td><?php echo $run_qry['title'];?></td>
									<td><?php echo $run_qry['subject'];?></td>
									<td><?php echo substr(strip_tags($run_qry['description']), 0, 80);?>...</td>
									<td>
										<a href="<?=HOST_URL."adminpannel/edit-email.php?emailtemplateid=".base64_encode($run_qry["id"])?>" class="btn btn-primary"><svg class="svg-inline--fa fa-pen-to-square" aria-hidden="true" focusable="false" data-prefix="far" data-icon="pen-to-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M373.1 24.97C401.2-3.147 446.8-3.147 474.9 24.97L487 37.09C515.1 65.21 515.1 110.8 487 138.9L289.8 336.2C281.1 344.8 270.4 351.1 258.6 354.5L158.6 383.1C150.2 385.5 141.2 383.1 135 376.1C128.9 370.8 126.5 361.8 128.9 353.4L157.5 253.4C160.9 241.6 167.2 230.9 175.8 222.2L373.1 24.97zM440.1 58.91C431.6 49.54 416.4 49.54 407 58.91L377.9 88L424 134.1L453.1 104.1C462.5 95.6 462.5 80.4 453.1 71.03L440.1 58.91zM203.7 266.6L186.9 325.1L245.4 308.3C249.4 307.2 252.9 305.1 255.8 302.2L390.1 168L344 121.9L209.8 256.2C206.9 259.1 204.8 262.6 203.7 266.6zM200 64C213.3 64 224 74.75 224 88C224 101.3 213.3 112 200 112H88C65.91 112 48 129.9 48 152V424C48 446.1 65.91 464 88 464H360C382.1 464 400 446.1 400 424V312C400 298.7 410.7 288 424 288C437.3 288 448 298.7 448 312V424C448 472.6 408.6 512 360 512H88C39.4 512 0 472.6 0 424V152C0 103.4 39.4 64 88 64H200z"></path></svg></a>
										<!-- <a href="<?=HOST_URL."adminpannel/delete-email.php?emailtemplateid=".$run_qry["id"]?>" class="btn btn-primary">Delete</a> -->
									</td>
									
								</tr>
								 <?php
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
</html>

</body>
<script type="text/javascript">
	$(document).ready(function() {
	
	$('.speedytable').DataTable({
        order: [[0, 'asc']],
    });

	} );
</script>