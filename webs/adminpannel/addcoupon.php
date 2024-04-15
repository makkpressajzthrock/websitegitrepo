<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

$bytes = random_bytes(5);
$Code =  strtoupper(bin2hex($bytes));
// Include the database connection file 
require_once '../payment/config.php';
// Include the Stripe PHP library 
require_once '../payment/stripe-php/init.php';



$country = "USD";
include '../payment/dbConnect.php';



$q = "SELECT * FROM `coupon_categories`";
$select = mysqli_query($conn, $q);

$couponError = "";

if(isset($_POST['submitbtn'])){
 
// \Stripe\Stripe::setApiKey(getGetway("USD",$conn)['STRIPE_API_KEY']);
// $stripe_usd = new \Stripe\StripeClient(getGetway("USD",$conn)['STRIPE_API_KEY']);


 
// \Stripe\Stripe::setApiKey(getGetway("IND",$conn)['STRIPE_API_KEY']);
// $stripe_ind = new \Stripe\StripeClient(getGetway("IND",$conn)['STRIPE_API_KEY']);



// echo "Ind=";

// print_r($stripe_ind);

// die;

	// print_r($_POST);

$CouponCode= $_POST['CouponCode'];
$CouponCodeFile= $_FILES['CouponCode_file']['name'];

 

if($CouponCodeFile!=""){
	echo "Coupon code File ";

    echo $name = $_FILES['CouponCode_file']['name'];
     
    $type = $_FILES['CouponCode_file']['type'];
    $tmpName = $_FILES['CouponCode_file']['tmp_name'];
       

        if(($handle = fopen($tmpName, 'r')) !== FALSE) {
           
            set_time_limit(0);

            $row = 0;

            while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // number of fields in the csv
                $col_count = count($data);
                if($data[0] !="")
	                $csv[$row] = $data[0]; 
                $row++;
            }
            fclose($handle);
        }

}
else{
	echo "coupon Code";
	$csv[0] = $CouponCode; 
} 






$CouponName= $_POST['CouponName'];
$DiscountType=  $_POST['DiscountType'];
$DiscountAmount=  $_POST['DiscountAmount'];
$ExpiryDate=  $_POST['ExpiryDate'];
$StartDate = $_POST['StartDate'];
$DiscountPlan=  $_POST['DiscountPlan'];
$enable=  $_POST['enable'];
$coupon_for =  $_POST['coupon_for'];
$duration =  $_POST['duration'];
$category_check = $_POST['coupon_category'];

$no_of_uses = $_POST['no_of_uses'];


$custom_category = (isset($_POST['other_category'])? $_POST['other_category']: "");

$coupon_category = "";  
foreach($category_check as $cate)  {

	// $find = mysqli_query($conn, "select `category_name` from coupon_categories where id ='$cate' ");
	// $res = mysqli_fetch_object($find);

	if($cate == 'other')  {
		$cate = $cate."($custom_category)";
	}
	// if($cate == '6')  {
	// 	$cate = $cate."($custom_category)";
	// }
    $coupon_category .= $cate.",";  
}

$coupon_category = rtrim($coupon_category, ",");


if($enable ==""){
	$enable = 0;
}

// Create Coupon


function SaveCoupon($conn, $CouponName, $coupon_code, $coupon_category, $coupon_for, $location,$duration, $coupon_id, $DiscountType, $DiscountAmount, $StartDate, $ExpiryDate, $DiscountPlan, $enable, $coupon, $no_of_uses){


if($DiscountType=="Lifetime"){
	$duration = "";
	$DiscountAmount = 0;
}


			 echo  $inst ="INSERT INTO `coupons`(`name`, `code`,`coupon_category`, `strip_coupon_id`, `form_location_value`, `location`, `duration`, `type`, `discount_amount`, `number_of_uses`, `uses_per_customer`, `start_date`, `expiry_date`, `plan_category_id`, `status`, `Strip_coupon_json`) 

					  		  VALUES ('".mysqli_real_escape_string($conn, $CouponName)."','$coupon_code','$coupon_category','$coupon_id','$coupon_for','$location','$duration','$DiscountType','$DiscountAmount',0,'$no_of_uses','$StartDate', '$ExpiryDate','$DiscountPlan','$enable', '".mysqli_real_escape_string($conn, json_encode($coupon))."')";

					// die;

					 $inst_done = mysqli_query($conn,$inst);

					if($inst_done==true){

							$_SESSION['success'] = "Coupon Created Successfully!" ;
					}
					else{

								$_SESSION['error'] = "Try Again.." ;
					}

}



foreach ($csv as $key => $coupon_code) {


// echo $coupon_for;

// die;
 	
			$coupon_id = "";
			
			$dis = array();
			if($DiscountType == "Percentage"){
				if($duration==1){
						$dis = [
							  'percent_off' => $DiscountAmount,
							  'duration' => 'once',
							  'name' => $CouponName
						];
				}
				else{
						$dis = [
							  'percent_off' => $DiscountAmount,
							  'duration' => 'repeating',
							  'duration_in_months' => $duration,
							  'name' => $CouponName
						];					
				}


				$coupon = null;

				if($coupon_for == "Other" || $coupon_for == "Both"){

					if($DiscountAmount != 100){	
						\Stripe\Stripe::setApiKey(getGetway("USD",$conn)['STRIPE_API_KEY']);
						$stripe_usd = new \Stripe\StripeClient(getGetway("USD",$conn)['STRIPE_API_KEY']);
						$coupon = $stripe_usd->coupons->create($dis);
						$coupon_id = $coupon['id'];
					}else{
						$coupon_id = "No";
					}

					if($coupon_id!="")
					{   $location = "Other";
					 	SaveCoupon($conn,$CouponName,$coupon_code, $coupon_category, $coupon_for,$location,$duration,$coupon_id,$DiscountType,$DiscountAmount,$StartDate,$ExpiryDate,$DiscountPlan,$enable,$coupon,$no_of_uses);
					}
				}
				if($coupon_for == "India"  || $coupon_for == "Both"){

					if($DiscountAmount != 100){						
						\Stripe\Stripe::setApiKey(getGetway("IND",$conn)['STRIPE_API_KEY']);
						$stripe_ind = new \Stripe\StripeClient(getGetway("IND",$conn)['STRIPE_API_KEY']);
						$coupon = $stripe_ind->coupons->create($dis);
						$coupon_id = $coupon['id'];
					}else{
						$coupon_id = "No";
					}

					if($coupon_id!="")
					{	$location = "India";
					 	SaveCoupon($conn,$CouponName,$coupon_code, $coupon_category, $coupon_for,$location,$duration,$coupon_id,$DiscountType,$DiscountAmount,$StartDate,$ExpiryDate,$DiscountPlan,$enable,$coupon,$no_of_uses);
					}
				}
				



			}
			else if($DiscountType == "Amount"){

				if($duration==1){
	 				  $dis = [
					  'amount_off' => ($DiscountAmount*100),
					  'duration' => 'once', 
					  'name' => $CouponName,
					  'currency' => ''
					];	
				}else{
	 				  $dis = [
					  'amount_off' => ($DiscountAmount*100),
					  'duration' => 'repeating',
					  'duration_in_months' => $duration,
					  'name' => $CouponName,
					  'currency' => ''
					];						
				}


 
				$coupon = null;

				if($coupon_for == "Other"  || $coupon_for == "Both"){
					\Stripe\Stripe::setApiKey(getGetway("USD",$conn)['STRIPE_API_KEY']);
					$stripe_usd = new \Stripe\StripeClient(getGetway("USD",$conn)['STRIPE_API_KEY']);
					$dis['currency'] = "usd";
					$coupon = $stripe_usd->coupons->create($dis);
					$coupon_id = $coupon['id'];

					if($coupon_id!="")
					{
						$location = "Other";
					 	SaveCoupon($conn,$CouponName,$coupon_code,$coupon_category, $coupon_for,$location,$duration,$coupon_id,$DiscountType,$DiscountAmount,$StartDate,$ExpiryDate,$DiscountPlan,$enable,$coupon,$no_of_uses);
					}
				}
				if($coupon_for == "India" || $coupon_for == "Both"){
					\Stripe\Stripe::setApiKey(getGetway("IND",$conn)['STRIPE_API_KEY']);
					$stripe_ind = new \Stripe\StripeClient(getGetway("IND",$conn)['STRIPE_API_KEY']);
					$dis['currency'] = "inr";
					$coupon = $stripe_ind->coupons->create($dis);
					$coupon_id = $coupon['id'];

					if($coupon_id!="")
					{
						$location = "India";
					 	SaveCoupon($conn,$CouponName,$coupon_code,$coupon_category, $coupon_for,$location,$duration,$coupon_id,$DiscountType,$DiscountAmount,$StartDate,$ExpiryDate,$DiscountPlan,$enable,$coupon,$no_of_uses);
					}
				}



			}
			else{
				$coupon_id = "No";

				$DiscountAmount=0;

				if($coupon_for == "Other"  || $coupon_for == "Both"){
					$location = "Other";
					SaveCoupon($conn,$CouponName,$coupon_code,$coupon_category,$coupon_for,$location,$duration,$coupon_id,$DiscountType,$DiscountAmount,$StartDate,$ExpiryDate,$DiscountPlan,$enable,$coupon,$no_of_uses);
				}
				if($coupon_for == "India" || $coupon_for == "Both"){
					$location = "India";
					SaveCoupon($conn,$CouponName,$coupon_code,$coupon_category, $coupon_for,$location,$duration,$coupon_id,$DiscountType,$DiscountAmount,$StartDate,$ExpiryDate,$DiscountPlan,$enable,$coupon,$no_of_uses);
				}


			}

   		



}




// Create Coupon End
		if($_SESSION['error']!=""){
			$_SESSION['error']=$_SESSION['error'].$couponError;
		}

		if($_SESSION['error']==""){
        	header("location: ".HOST_URL."adminpannel/create_coupon.php") ;
		}else{
        	header("location: ".HOST_URL."adminpannel/addcoupon.php") ;
        }

        die();

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
					<h1 class="mt-4">Create a Coupon Code</h1>
					

<div class="back_btn_wrap ">
						<a href="create_coupon.php" type="button" class="btn btn-primary">Back</a>
					</div>
<div class="form_h">
<?php require_once("inc/alert-status.php") ; ?>
<form method="post" enctype="multipart/form-data">
  <div class="form-group col-12">
  	 <div class="row">
	  	<div class="col-3 text-right">
	    	<label for="exampleInputEmail1">Coupon Code</label>
		</div>

	  	<div class="col-6">
	    	<input type="text"  class="form-control c_code" name="CouponCode" value="<?=$Code?>" required>
	    	<div class="upload">
	    		<input type="file"  class="form-control c_upload" name="CouponCode_file" accept=".csv" style="display: none;" />
	    		<label class="c_upload" style="display: none;" ><a href="<?=HOST_URL?>sample%20coupon.csv">Download Sample</a></label>
	    	</div>

		</div>
	  	<div class="col-3">
	    	<button type="button" class="btn btn-primary form-control CouponCode_code"  style="display: none;" ><i class="fa fa-close pr-1"></i></button>
	    	<button type="button" class="btn btn-primary form-control CouponCode_upload"><i class="fa fa-upload  pr-1"></i>Upload</button>

		</div>

	 </div>	
    
  </div>
  
  <div class="form-group col-12">
  	  	<div class="coupon_duplicate"><?=$couponError?></div>

  	 <div class="row">
	  	<div class="col-3 text-right">
    		<label for="exampleInputPassword1">Coupon Name</label>
    	</div>	
    	<div class="col-9">
   			<input type="text" class="form-control" name="CouponName" maxlength="40" required>
   		</div>
   	  </div>	
  </div>

  <div class="form-group col-12">
  	 <div class="row">
	  	<div class="col-3 text-right">
    		<label>Location</label>
    	</div>	
    	<div class="col-9">
   			<select name="coupon_for" class="form-control" required>
   				<option>Select</option>
   				<option value="Both">Both</option>
   				<option value="India">India</option>
   				<option value="Other">Other</option>
   			</select>
   		</div>
   	  </div>	
  </div>



  <div class="form-group col-12">
  	 <div class="row">
	  	<div class="col-3 text-right">  	
	    	<label for="exampleInputPassword1">Discount Type</label>
	    </div>

     	<div class="col-9">	
		    <!-- <div class="form-check">
		      <input type="radio" class="form-check-input border" id="AmountOff" name="DiscountType" value="Amount" checked>
		      <label class="form-check-label" for="AmountOff">Amount off on selected plan</label>
		    </div> -->
		    <div class="form-check">
		      <input type="radio" class="form-check-input border" id="PercentageOff" name="DiscountType" value="Percentage" checked>
		      <label class="form-check-label" for="PercentageOff">Percentage on selected plan</label>
		    </div>
		    <div class="form-check">
		      <input type="radio" class="form-check-input border" id="Lifetime" name="DiscountType" value="Lifetime">
		      <label class="form-check-label" for="Lifetime">Lifetime</label>
		    </div>
		</div>
	</div>	    
</div>



<!-- -------------------------category ------------------------------------- -->
<div class="form-group col-12"  id="coupon_cat"  style="display: none;">
  	 <div class="row">
	  	<div class="col-3 text-right">  	
	    	<label for="coupon_category">Category</label>
	    </div>

     	<div class="col-9">	

			<?php
				while( $cate_result = mysqli_fetch_assoc($select)){
			?>
				<div class="form-check">
					<input type="checkbox" class="select_check" id="coupon_category" name="coupon_category[]" value="<?= $cate_result['id'] ?>">
					<label> <?= $cate_result['category_name'] ?></label><br>
				</div>
			<?php
				}
			?>
			
			<div class="form-check col-12 otherDiv">
				<div class="row">  
					<div class="col-6">    	
						<input type="text" class="form-control" id="other_cateText" name="other_category">
					</div>
				</div>
			</div>

		</div>

	</div>	    
</div>


  <div class="form-group col-12 duration">
  	 <div class="row">
	  	<div class="col-3 text-right">
    		<label>Duration (In Month)</label>
    	</div>	
    	<div class="col-9">
   			<select name="duration" class="form-control">
   				<option value="1">Once</option>
   				<option value="2">2 - Month</option>
   				<option value="3">3 - Month</option>
   				<option value="4">4 - Month</option>
   				<option value="5">5 - Month</option>
   				<option value="6">6 - Month</option>
   				<option value="7">7 - Month</option>
   				<option value="8">8 - Month</option>
   				<option value="9">9 - Month</option>
   				<option value="10">10 - Month</option>
   				<option value="11">11 - Month</option>
   				<option value="12">12 - Month</option>
   			</select>

   		</div>
   	  </div>	
  </div>

  <div class="form-group col-12">
  	 <div class="row">
	  	<div class="col-3 text-right">
			<label>Number Of Uses</label>
    	</div>	
    	<div class="col-9">
			<select name="no_of_uses" class="form-control">
				<option value="1">Once</option>
				<option value="unlimited">Unlimited</option>
			</select>

   		</div>
   	  </div>	
  </div>

<!-- ---------------------------------------------------------------------- -->

  <div class="form-group col-12 DiscounttextDiv">
  	 <div class="row">
	  	<div class="col-3 text-right">    	
		    <label for="DiscountAmount">Discount</label>
		</div>    
	
	  	<div class="col-6">    	
		    <input type="text" class="form-control"  id="DiscountAmount" name="DiscountAmount" placeholder="0.00" max="100" required>
		</div>
	  	<div class="col-3">    	
		    <span id="Discounttext">off</span>
		</div>
	  </div>
  </div>

  <div class="form-group col-12">
  	 <div class="row">
	  	<div class="col-3 text-right">  
			<label for="exampleInputPassword1">Applies To</label>
 		</div>
	  	<div class="col-9">  
	  		<small>This coupon code can be used with selected plan:</small>
				    <div class="form-check">
				      <input type="radio" class="form-check-input border" id="9999" name="DiscountPlan" value="9999" checked>
				      <label class="form-check-label" for="<?=$dataMonth['id']?>">All Plan</label>
				    </div>	  		
	 	   	<?php
            $plan_month = $conn->query(" SELECT * FROM `plans` WHERE status = 1 and interval_plan = 'month'") ;
              while($dataMonth = $plan_month->fetch_assoc() ) 
               { ?>
				    <div class="form-check">
				      <input type="radio" class="form-check-input border" id="<?=$dataMonth['id']?>" name="DiscountPlan" value="<?=$dataMonth['id']?>">
				      <label class="form-check-label" for="<?=$dataMonth['id']?>"><?=$dataMonth['name']?> <small>/<?=$dataMonth['interval_plan']?></small></label>
				    </div>
         <?php }

 
	   	
            $plan_month = $conn->query(" SELECT * FROM `plans` WHERE status = 1 and interval_plan = 'year'") ;
              while($dataMonth = $plan_month->fetch_assoc() ) 
               { ?>
				    <div class="form-check">
				      <input type="radio" class="form-check-input border" id="<?=$dataMonth['id']?>" name="DiscountPlan" value="<?=$dataMonth['id']?>">
				      <label class="form-check-label" for="<?=$dataMonth['id']?>"><?=$dataMonth['name']?> <small>/<?=$dataMonth['interval_plan']?></small></label>
				    </div>
         <?php }


	   	?>
	   </div>
	</div>
</div>



  <div class="form-group col-12">
  	 <div class="row">
	  	<div class="col-3 text-right"> 
		    <label for="Enabled">Enabled</label>
	    </div>

	  	<div class="col-9"> 
	 	  <div class="form-check">
		  <input class="form-check-input" type="checkbox" id="check1" name="enable" value="1" checked>
		  <label class="form-check-label" for="check1">Yes, this coupon code is enabled and can be used.</label>
		  </div>
		</div>
	</div>	
  </div>


   <div class="form-group col-12">
  	 <div class="row">
	  	<div class="col-3 text-right">
    		<label for="StartDate">Start Date</label>
    	</div>	
	  	<div class="col-9"> 	
	    	<input type="date" class="form-control"  id="StartDate" name="StartDate" value="" required>
	    </div>
	</div>
  </div> 


   <div class="form-group col-12">
  	 <div class="row">
	  	<div class="col-3 text-right">
    		<label for="ExpiryDate">Expiry Date</label>
    	</div>	
	  	<div class="col-9"> 	
	    	<input type="date" class="form-control"  id="ExpiryDate" name="ExpiryDate"  required>
	    </div>
	</div>
  </div> 







  <div class="form_h_submit">
  <button type="submit" name="submitbtn" class="btn btn-primary">Save</button>
</div>
</form>
</div>

				</div>
		
			</div>
		</div>

	</body>
	
<script type="text/javascript">
	$(document).ready(function(){
		$('input[name="DiscountType"]').change(function(){
			var v = $('input[name="DiscountType"]:checked').val();
			$("#coupon_cat").hide();
			$(".duration").show();

			if(v=="Amount"){
					$("#Discounttext").html("off");
					$(".DiscounttextDiv").show();
					$("#DiscountAmount").attr("required",true);
			}
			else if(v=="Lifetime"){
					$(".DiscounttextDiv").hide();
					$("#DiscountAmount").attr("required",false);
					$("#coupon_cat").show();
					$(".duration").hide();
			}			
			else{
					$("#Discounttext").html("% off");
					$(".DiscounttextDiv").show();
					$("#DiscountAmount").attr("required",true);
			}
		});

		$(".otherDiv").hide();
		$("#other_cateText").attr("required",false);

		$('.select_check').change(function(){

				if(($(this).is(':checked') && $(this).val()=='other')){
					$('.otherDiv').show();
					$("#other_cateText").attr("required",true);
				}else{
					$('.otherDiv').hide();
					// $('.select_check:checked').each(function(){
					// 	$('#'+$(this).attr('coupon_category')).show();
					// 	$("#other_cateText").attr("required",true);
					// });
				}
			
		});




		$(".CouponCode_upload").click(function(){
				$(".CouponCode_code").show();
				$(".CouponCode_upload").hide();

				$(".c_code").hide();
				$(".c_upload").show();
				$(".c_upload").click();
				$(".c_upload").attr("required",true);
				$(".c_code").attr("required",false);				 

		});

		$(".CouponCode_code").click(function(){
				$(".CouponCode_upload").show();
				$(".CouponCode_code").hide();

				$(".c_upload").hide();
				$(".c_code").show();
				$(".c_upload").attr("required",false);
				$(".c_code").attr("required",true);
				$(".c_upload").val("");


		});


		setInterval(function(){
			if($(".c_upload").val()!="")
			{
						$(".CouponCode_code").show();
						$(".CouponCode_upload").hide();
						$(".c_code").hide();
						$(".c_upload").show();
						$(".c_upload").attr("required",true);
						$(".c_code").attr("required",false);			
			}
		},100);


	});
</script>

</html>