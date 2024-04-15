	<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

if(isset($_POST['updates'])){
	
	$ids=$_POST['id'];
	
	$title= $_POST['title'];
	$keyword= $_POST['keyword'];
	$descripton= $_POST['descripton'];
	$pageurl= $_POST['pageurl'];
	
	
	$qryyy="update `add_meta` set title='$title', `keyword`='$keyword',  descripton='$descripton', pageurl='$pageurl' where id='$ids'"; 
	
	
	$con_qry= mysqli_query($conn,$qryyy);

	if($con_qry==true){
	 $_SESSION['success'] = " Updated successfully!" ;
	}
	else{

		 $_SESSION['error'] = " Try Again..!" ;
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

	<div class="container-fluid content__up edit_tax">
					
					
					<?php

					$id=base64_decode($_GET['edit']);

					$sele ="select * from `add_meta` where id='$id'";
					$result= mysqli_query($conn,$sele);

					$rows1= mysqli_fetch_assoc($result);

					//echo $id;
					?>

				
					<h1> Edit Meta Detail </h1>
					
						<div class="back_btn_wrap ">
						<a href="meta.php" type="button" class="btn btn-primary">Back</a>
					</div>
					<div class="form_h">
					<?php require_once("inc/alert-status.php") ; ?>
					<form method="post">
					     
	 				
	         		<input type="hidden" value="<?php echo $rows1['id'];?>" name="id">
         			
   
					<div class="form-group">
	 					<label>Title </label>
	 					<input class="form-control" value="<?php echo $rows1['title']; ?>" name="title">
	 				</div>
	   				<div class="form-group">
						<label>Keyword</label>
						<input class="form-control" value="<?php echo $rows1['keyword']; ?>" name="keyword">
					</div>
		 <div class="form-group">
						<label>Description</label>
						<input class="form-control" value="<?php echo $rows1['descripton']; ?>" name="descripton">
					</div>
					 <div class="form-group">
						<label>Page URL</label>
						<input type="text" class="form-control" value="<?php echo $rows1['pageurl']; ?>" name="pageurl">
					</div>
					 	<div class="form_h_submit">
						    <button type="submit"  name="updates" class="btn btn-primary">Update</button>
						</div>
					</form>	
						</div>		
					
					
					
					
					</div>
						</div>
		</div>

	</body>
	
</html>