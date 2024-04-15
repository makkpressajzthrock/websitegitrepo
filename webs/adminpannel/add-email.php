<?php 

require_once('config.php');
require_once('meta_details.php');
require_once('inc/functions.php') ;




if(isset($_POST['subbtn'])){
	
	
	$title=$_POST['title'];
	$desc=$_POST['editor'];
	$subject=$_POST['subject'];
	
	
	$inst="INSERT INTO `email_template`( `title`, `description` , subject ) VALUES ('$title','$desc','$subject')";
	$inst_cone=mysqli_query($conn,$inst);
	if($inst_cone==true){

		 $_SESSION['success'] = "Data Save successfully!" ;
	}
	else{
		 $_SESSION['error'] = "Try again.." ;
	}
	
	header("location: ".HOST_URL."adminpannel/add-email.php") ;
	die();
	
}



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
			<div class="container-fluid content__up add_email">

				<h1 class="mt-4">Add Email Template</h1>
				
				<div class=back_btn_wrap ">
					<a href="email_template.php" type="button" class="btn btn-primary">Back</a>
				</div>
                <div class="form_h">
				<?php require_once("inc/alert-status.php") ; ?>
				
				<form method="post" class="mt-4">
					<div class="form-group">
						<label>Title</label>
						<input type="text" name="title"  class="form-control" required />
						<span> </span>
					</div>
					<div class="form-group">
						<label>Subject</label>
						<input type="text" name="subject" class="form-control" required />
						<span> </span>
					</div>
					<div class="form-group">
						<label>Description</label>
						<textarea class="ckeditor" name="editor"></textarea>
					</div>
					<div class="form_h_submit" >
						<button type="submit" name="subbtn"  class="btn btn-primary ">Submit</button>
					</div>
				</form>
                </div>
			</div>
		</div>
	</div>
</body>
</html>
