<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
?>


<?php
if(isset($_POST['subbtn'])){
$selqr="select * from `add-tax`";
$selcon=mysqli_query($conn,$selqr);
$get=mysqli_fetch_array($selcon);

$namecountry=$get['country_name'];
$nametax=$get['tax_name'];
$countryname=$_POST['country'];
	$taxnames=$_POST['taxname'];
if($namecountry==$countryname || $nametax==$taxnames){
$_SESSION['error'] = "Data already exists!" ;
    
}
else{
	
	
	
	
	$countryname=$_POST['country'];
	$taxnames=$_POST['taxname'];
	$taxrat=$_POST['taxrate'];
	
	
	$inst="INSERT INTO `add-tax`( `country_name`, `tax_name`, `tax_rate`) VALUES ('$countryname','$taxnames','$taxrat')";
	$inst_cone=mysqli_query($conn,$inst);
	 $_SESSION['success'] = "Tax Add  successfully!" ;
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
				<div class="container-fluid content__up add_tax">
					
					<h1 class="mt-4">Add Tax</h1>
					<div class="back_btn_wrap ">
						<a href="tax_rates.php" type="button" class="btn btn-primary">Back</a>
					</div>

				<?php
				
				 $qry="select * from country";
				 $cont_qry=mysqli_query($conn,$qry);
				 
				
				?>  
				<div class="form_h">
				<?php require_once("inc/alert-status.php") ; ?>
					<form method="post" >
					 
						
						<div class="form-group">
						    <label>Country Name: </label>
							 <select   name="country" value="" class="form-control">
						 <?php
						 while($run_qry=mysqli_fetch_array($cont_qry))
						 {
						 ?>
						 <option value="<?php echo $run_qry['countryname'];?>"><?php echo $run_qry['countryname'];?></option>
						 <?php
						 }
						 ?>
						 </select>
						 </div>
						 
						 <div class="form-group">
						    <label>Tax Name</label>
							<input type="text" name="taxname"  class="form-control" required />
						 <span> </span>
						 </div>
						 <div class="form-group">
						    <label>Tax Rate</label>
							<input type="text" name="taxrate"  class="form-control" required />
						 <span> </span>
						 </div>
						   <div class="form_h_submit" >
						 <button type="submit" name="subbtn"  class="btn btn-primary">Submit</button>
						 </div>
						 
						 </form>

						</form>
	
					
				</div>
			</div>
		</div>
		
	</body>
</html>
