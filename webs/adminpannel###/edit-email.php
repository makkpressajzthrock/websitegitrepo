<?php 

require_once('config.php');
require_once('inc/functions.php') ;
require_once('meta_details.php');

$id=base64_decode($_GET['emailtemplateid']);


if(isset($_POST['updatebtn'])) {
	$id=base64_decode($_GET['emailtemplateid']);
	$title=$_POST['title'];
	$desc=$_POST['editor'];
	$subject=$_POST['subject'];

	$updates ="update email_template set title='$title' , description='$desc' , subject='$subject' where id='$id'";
	$run_qry_update=mysqli_query($conn,$updates);

	if($run_qry_update==true){
		$_SESSION['success'] = "Data Update successfully!" ;
	}
	else{
		$_SESSION['error'] = "Try Again.." ;
	}

	header("location: ".HOST_URL."adminpannel/edit-email.php?emailtemplateid=".base64_encode($id)) ;
	die();
}

$id=base64_decode($_GET['emailtemplateid']);

	 $qry="select * from email_template where id='$id'";
				 $cont_qry=mysqli_query($conn,$qry);
				 $run_qry=mysqli_fetch_array($cont_qry);

?>
	<?php require_once("inc/style-and-script.php") ; ?>
	<script src="//cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
</head>
<body class="custom-tabel">
	<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
		<?php require_once("inc/sidebar.php"); ?>
		<!-- Page content wrapper-->
		<div id="page-content-wrapper">
			<?php require_once("inc/topbar.php"); ?>
			<!-- Page content-->
			<div class="container-fluid content__up edit_email">

				<h1>Edit Email Template</h1>
				
				<div class="back_btn_wrap ">
					<a href="email_template.php" type="button" class="btn btn-primary">Back</a>
				</div>
                <div class="form_h">
				<?php require_once("inc/alert-status.php") ; ?>

				<form method="post" class="mt-3">
					<div class="form-group">
						<label>Title</label>
						<input type="text" name="title"  value="<?php echo $run_qry['title'];?>" class="form-control" required />
						<span> </span>
					</div>
					<div class="form-group">
						<label>Subject</label>
						<input type="text" name="subject"  value="<?php echo $run_qry['subject'];?>" class="form-control" required />
						<span> </span>
					</div>
					<div class="form-group">
						<label>Description</label>
						<textarea  class="ckeditor" name="editor" ><?php echo $run_qry['description']; ?></textarea>
					</div>
					<div class="form_h_submit" >
						<button type="submit" name="updatebtn"  class="btn btn-primary ">Update</button>
					</div>
				</form>
</div>
			</div>
		</div>
	</div>
</body>
</html>
