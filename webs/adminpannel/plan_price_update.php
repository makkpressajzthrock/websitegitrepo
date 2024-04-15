<?php 

	include('config.php');
	require_once('meta_details.php');
	include('session.php');
	require_once('inc/functions.php') ;

	$id = $_GET['id'];

	// $selected = "SELECT * FROM plan_price WHERE id = '$id'";
	// $result = mysqli_query($conn,$selected);
	// $row = mysqli_fetch_assoc($result);


	// print_r($row);
?>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		
		<title></title>
		<!-- Favicon-->
		<link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
		<?php require_once('inc/style-and-script.php') ; ?>
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid content__up plan_price_update">
					<h1>Plan price update</h1>
					
					<div class="back_btn_wrap">
					  <a href="plan_price_edit.php" class="btn btn-primary">Back</a>
                    </div>

					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>
					<?php $row = getTableData($conn,"plan_price","id ='".$id."'"); ?>
					<form>
						<div class="planid">
							<input type="hidden" name="plan" class="plan" id="plan" value="<?=$row['id'];?>">
						</div>
						<div class="form-group">
							<label>Name :</label>
							<input type="text" name="name" class="name form-control" id="name" value="<?=$row['name'];?>">
						</div>
						<div class="form-group">
							<label>Price :</label>
							<input type="text" name="price" class="price form-control" id="price" value="<?=$row['price'];?>">
						</div>
						<div class="form_h_submit">
							<button type="button" name="edit" class="btn btn-danger edit" id="edit">Update</button>
						</div>
					</form>	
                    </div>		
				</div>
			</div>
	</body>
	<script>
		$(".edit").click(function(){
			$.ajax({
	            type: 'post',
	            url: 'updated_plan_price.php',
	            data: $('form').serialize(),
	            success: function () {
             		window.location.reload();
            	}
            });
		});
	</script>
	<script>
		$('#close').click(function(){
			$('.success_notification').hide();
		});
	</script>
</html>
