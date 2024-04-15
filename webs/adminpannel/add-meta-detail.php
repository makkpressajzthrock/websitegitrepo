<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;


if(isset($_POST['submitbtn'])){

// 	print_r($_POST['']);
// die();


$metatitle= $_POST['metatitle'];
$Metakeyword= $_POST['Metakeyword'];
$Metadescripton=  $_POST['Metadescripton'];
$pageurl=  $_POST['pageurl'];


  $inst ="INSERT INTO `add_meta`(`title`, `keyword`, `descripton`, `pageurl`) VALUES ('$metatitle','$Metakeyword','$Metadescripton','$pageurl')";


 $inst_done = mysqli_query($conn,$inst);

if($inst_done==true){

		$_SESSION['success'] = "Insert Successfully!" ;
}
else{

			$_SESSION['error'] = "Try Again.." ;
}


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
				<div class="container-fluid content__up web_owners">
					<h1 class="mt-4">Add Meta Details</h1>
					

<div class="back_btn_wrap ">
						<a href="meta.php" type="button" class="btn btn-primary">Back</a>
					</div>
<div class="form_h">
<?php require_once("inc/alert-status.php") ; ?>
<form method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Meta Title</label>
    <input type="text"  class="form-control" name="metatitle" >
    
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Meta keyword</label>
    <input type="text" class="form-control"  id="exampleInputPassword1" name="Metakeyword" placeholder=" Enter Meta keyword">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1"> Meta description</label>
    <input type="text" class="form-control" id="exampleInputPassword1" name=" Metadescripton" placeholder="Enter Meta description">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1"> Page URL</label>
    <input type="text" class="form-control"  id="exampleInputPassword1" name=" pageurl" placeholder="Enter Meta descripton">
  </div>
  <div class="form_h_submit">
  <button type="submit" name="submitbtn" class="btn btn-primary">Add Meta</button>
</div>
</form>
</div>

				</div>
		
			</div>
		</div>

	</body>
	
</html>