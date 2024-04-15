<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
require_once('meta_details.php');
require_once('inc/functions.php') ;

$project = base64_decode($_GET['project']) ;
$website_data = getTableData( $conn , " boost_website " , " id = '$project' " ) ;

 
	$edited_url_on = $website_data['edited_url_on'];

	$can_i_edit_website = 0;

	if($edited_url_on == "" || $edited_url_on == null){

	$added_date =  $website_data['created_at'];
	$current_date = date('Y-m-d H:i:s') ;
	$diff = date_diff(date_create($current_date) , date_create($added_date) ) ;
	$plan_f= $diff->days;

		if($plan_f<=7){
	 		$can_i_edit_website = 1;
		 } 

	}

$can_i_edit_website = 0;



if($_GET['project']==null || count($website_data)<=0 ){
	$_SESSION['error'] = "Requested URL is not valid!";

	header("location: ".HOST_URL."adminpannel/dashboard.php");
	die();
}


// ================================================================
$user_id = $_SESSION["user_id"] ;

if(isset($_POST['submit_btn'])) {


	// echo '<pre>';print_r($_POST); echo"</pre>" ;
	// die() ;

	$data = escape_string($conn,$_POST) ;

	$website_name_1 = $_POST['website_name_1'];
	$additional_website_name = $_POST['additional_website_name'];
	$additional_website_url = $_POST['additional_website_url'];
	$current_additional_website_id = $_POST['additional_web_id'];
	$deleted_fields = $_POST['deleted_fields'];

	if ( $website_name_1 == null || empty($website_name_1) ) {
		$_SESSION['error'] = "Please fill all required values." ;
	}
	else {

		$website_data = getTableData( $conn , " boost_website " , " id = '$project' " ) ;
		if($can_i_edit_website == 1){
		   	$website_url = $_POST['website_url'];
		}else{
			$website_url = $website_data["website_url"] ;
		}
		

		// domain check condition ==================================
		$flag = 0 ;
		$website = parse_url($website_url) ;
		// print_r($website) ;

		$website_origin = $website["scheme"]."://".$website["host"] ;

		foreach ($additional_website_url as $key => $addi_url) 
		{
			if(empty($additional_website_url[$key])){
				unset($additional_website_url[$key]);
				
		
			}else{
			$addi_url = parse_url($addi_url) ;
			$addi_origin = $addi_url["scheme"]."://".$addi_url["host"] ;
				
			if ( $website_origin != $addi_origin ) {
				$flag = 1 ;
				// echo 'addi_origin '.$addi_origin ;
				// echo 'website_origin '.$website_origin ;
				// die();
			}
		}
		
		}

		if ( $flag == 1 ) {
			$_SESSION['error'] = "Invalid site domain for additional URLs. The primary domain will be the same as the previous one you have entered a different domain that is not allowed." ;
		}
		else 
		{

			if($can_i_edit_website == 1){

				$shopify_url = $_POST['shopify_url'];
				$shopify_preview_url = $_POST['shopify_preview_url'];

				
				updateTableData( $conn , " boost_website " , "website_url = '$website_url' ,shopify_url = '$shopify_url' ,shopify_preview_url = '$shopify_preview_url' , edited_url_on = now(), website_name = '$website_name_1' " , " id = '$project' " ) ;

				$query_completed = true;

						$user_id = $user_id;
						$site_id = $website_data['id'];
						if($website_data['plan_type']=="Free"){
							require_once('generate_script_free.php');
							// echo "subss";

						}else{
							// echo "sub";
							
							require_once('generate_script_paid.php');
						}


					 // start getting current page speed
						 

							$data = google_page_speed_insight($website_url, "desktop");

							if (is_array($data)) {
								$lighthouseResult = $data["lighthouseResult"];
								$requestedUrl = $lighthouseResult["requestedUrl"];
								$finalUrl = $lighthouseResult["finalUrl"];
								$userAgent = $lighthouseResult["userAgent"];
								$fetchTime = $lighthouseResult["fetchTime"];
								$environment = $conn->real_escape_string(serialize($lighthouseResult["environment"]));
								$runWarnings = $conn->real_escape_string(serialize($lighthouseResult["runWarnings"]));
								$configSettings = $conn->real_escape_string(serialize($lighthouseResult["configSettings"]));
								$audits = $conn->real_escape_string(serialize($lighthouseResult["audits"]));
								$categories = $conn->real_escape_string(serialize($lighthouseResult["categories"]));
								$categoryGroups = $conn->real_escape_string(serialize($lighthouseResult["categoryGroups"]));
								$i18n = $conn->real_escape_string(serialize($lighthouseResult["i18n"]));


								// mobile details
								$mobile_data = google_page_speed_insight($website_url, "mobile");

								if (is_array($mobile_data)) {
									$mobile_lighthouseResult = $mobile_data["lighthouseResult"];

									$mobile_environment = $conn->real_escape_string(serialize($mobile_lighthouseResult["environment"]));
									$mobile_runWarnings = $conn->real_escape_string(serialize($mobile_lighthouseResult["runWarnings"]));
									$mobile_configSettings = $conn->real_escape_string(serialize($mobile_lighthouseResult["configSettings"]));
									$mobile_audits = $conn->real_escape_string(serialize($mobile_lighthouseResult["audits"]));
									$mobile_categories = $conn->real_escape_string(serialize($mobile_lighthouseResult["categories"]));
									$mobile_categoryGroups = $conn->real_escape_string(serialize($mobile_lighthouseResult["categoryGroups"]));
									$mobile_i18n = $conn->real_escape_string(serialize($mobile_lighthouseResult["i18n"]));
								} else {
									$mobile_lighthouseResult = $mobile_environment = $mobile_runWarnings = $mobile_configSettings = $mobile_audits = $mobile_categories = $mobile_categoryGroups = $mobile_i18n = null;
								}

								$sql_delete = "Delete from pagespeed_report where website_id = ".$website_data['id'];
								$conn->query($sql_delete);

								$last_id = $website_data['id'];

								 
									$sql = " INSERT INTO pagespeed_report ( website_id , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$last_id' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) ";

									// echo "sql ".$sql."<br>";   
									// die(); 
									if ($conn->query($sql) == true) {
										$query_completed = TRUE;
									}else{
										updateTableData( $conn , " boost_website " , "website_url = '$website_url' , edited_url_on = Null, website_name = '$website_name_1' " , " id = '$project' " ) ;
									}
								 
							}
						 

						 // End getting current page speed

			}else{
				updateTableData( $conn , " boost_website " , " website_name = '$website_name_1' " , " id = '$project' " ) ;
				$query_completed = true;
			}


// print_r($current_additional_website_id);

// print_r($additional_website_url);
// die;
			// rest process

			if ( $query_completed === TRUE ) {
				$_SESSION['success_'] = "Updated Successfully" ;
			}
			else {
				$_SESSION['error_'] = "Something Went Wrong or Inviled Url Provided." ;
			}
		}
	}

	header("location: ".HOST_URL."adminpannel/edit-website.php?project=".base64_encode($project)) ;
	die();

}
// ================================================================


// check sign-up process complete
// checkSignupComplete($conn) ;

// get website/project data ====================================================

$user_id = $_SESSION["user_id"] ;
$project_id = base64_decode($_GET["project"]) ;

$project = base64_decode($_GET['project']);

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;
		$plan_country = "";
		if($row['country'] !=""){
			if($row['country'] != "101"){
				$plan_country = "-us";
			}
		}
		elseif($row['country_code'] != "+91"){
			$plan_country = "-us";
		}
// Show Expire message //
	include("error_message_bar_subscription.php");
// End Show Expire message //	



if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
$manager_id=$_SESSION['user_id'];
$row_data = getTableData( $conn , "boost_website " , "manager_id ='".$manager_id."' AND id = '$project'" ) ;

//getting additional websites data
$row_data2 = $conn->query("SELECT * FROM additional_websites WHERE manager_id = '$manager_id' AND website_id = '$project' AND flag='true'" ) ;

//checking number of websites put
if(!empty($row_data2->num_rows) && $row_data2->num_rows != 0){

$total_additional_websites = $row_data2->num_rows;

}
else{

$total_additional_websites = 0;

}

//fetching all additional data in an associative array
$additional_data = $row_data2->fetch_all(MYSQLI_ASSOC);



$additional_website_name = [];

$additional_website_url = [];

$i = 0;


//gettting additional website's data in arrays
foreach($additional_data as $additional_data_key => $additional_data_value){

$additional_website_name[] = $additional_data_value['website_name'];

$additional_website_url[] = $additional_data_value['website_url'];



}


?>
<?php require_once("inc/style-and-script.php") ; ?>
<style>
	.loader {
		background-color: #ffffff5e;
		height: 100%;
		position: absolute;
		text-align: center;
		margin: auto;
		display: none;
		width: 100%;
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
				<div class="container-fluid content__up  edit__website  ">

					<h1>Edit Website</h1>
					<?php require_once("inc/alert-status.php") ; ?>
                    <div class="back_btn_wrap ">
					<a href="<?=HOST_URL?>adminpannel/dashboard.php" class="btn btn-primary">Back</a>
</div>
					<div class="form_h">
					<!-- <div class="get_addon_btn_wrap ">
					<a href="<?=HOST_URL?>adminpannel/addon.php?project=<?=$_GET["project"]?>" class="btn btn-primary">Buy More URL</a>
						</div> -->
						
						<div class="loader"></div>

					<form id="add-website-form" method="post">

						<div class="form-group">
							<label>What is your website Platforms?</label>
							<label class="form-control"><input type="radio" class="select-platform" value="<?=$row_data['platform']?>" checked readonly><?=$row_data['platform']?></label>
						</div>
						<?php
							if ( $row_data['platform'] == "Other" ) {
								?>
								<div class="form-group other-platform-input">
									<input type="text" class="form-control" placeholder="Enter platform name" value="<?=$row_data['platform_name']?>" readonly>
								</div>
								<?php
							}
						?>
						
						<div class="form-group_S">
								<div class="form-group">
									<label>Add Website Name 1</label>
									<input type="text" class="form-control" id="website_name" name="website_name_1" placeholder="website Name" value="<?=$row_data['website_name']?>" >
							</div>
						
								<div class="form-group">
									<label>Add Website URL 1</label>
									<input type="url" class="form-control" id="website-url" value="<?=$row_data['website_url']?>" placeholder="https://abc.com" <?php if($can_i_edit_website == 0){ echo 'readonly';} else{ echo "name='website_url'"; } ?>>
								</div>
							<?php
							if ( $row_data['platform'] == "Shopify" ) {
								?>
								<div class="form-group shopify-domain-input">
									<label>Add shopify domain URL</label>
									<input type="url" class="form-control" id="shopify-url" placeholder="https://abc.myshopify.com" value="<?=$row_data['shopify_url']?>"  <?php if($can_i_edit_website == 0){ echo 'readonly';}else{echo "name='shopify_url'";} ?> >
								</div>


								<div class="form-group shopify-preview-input">
									<label>Add shopify preview URL</label>
									<input type="url" class="form-control" id="shopify-preview-url" placeholder="https://abc.myshopify.com" value="<?=$row_data['shopify_preview_url']?>"  <?php if($can_i_edit_website == 0){ echo 'readonly';}else{echo "name='shopify_preview_url'";}  ?> >
								</div>

								<?php
							}
							?>
						</div>

							<!-- //123 -->

						<!-- <div id="new_website">
							<?php 

								// $i = 0;

								// //gettting additional website's data in arrays
								// foreach($additional_data as $additional_data_key => $additional_data_value) {

								// 	$additional_website_name[] = $additional_data_value['website_name'];

								// 	$additional_website_url[] = $additional_data_value['website_url'];

								// 	$num = $i + 2;

								// 	$readonly = empty($additional_data_value['website_url']) ? "" : "readonly" ;
								// 	if($can_i_edit_website == 1){
								// 		$readonly = "";
								// 	}

								// 	echo '<div class="additional_websites"><div class="col-md-6"><div class="form-group"><label>Add Website Name '.$num.'</label><input '.$readonly.'  type="hidden" name="additional_web_id[]"  value="'.$additional_data_value['id'].'"><input type="text" '.$readonly.' class="form-control additonal-names " id="website_name'.$i.'" value="'.$additional_website_name[$i].'" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" ></div></div><div class="col-md-6"><div class="form-group"><label>Add Website URL '.$num.'</label><input '.$readonly.' type="url" class="form-control additonal-urls" value="'.$additional_website_url[$i].'" id="website_url'.$i.'" name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" ><small>(eg. https://abc.com , http://xyz.com)</small></div></div><button type="button" style="display:none" class="btn btn-danger" id="remove_website" style="width: 3%" name="remove_website[]" onclick="$(this).parent().remove(); btn_check('.$additional_data_value['id'].');">-</button></div>'; 

								// 	$i++;
								// }
							?>
						</div> -->
							<!-- <div class="form-group add_web">
								<button type="button" class="btn btn-danger" id="additonal_website" name="additonal_website" onclick="add_more_websites(this);">+</button>
							</div>
						 -->
							<!-- //123 -->

					
						


                        <div class="form_h_submit">
                       <?php if($can_i_edit_website == 0 ){ ?> 	
						<button type="submit" class="btn btn-primary submit__btn" name="submit_btn">Submit</button>
					   <?php }else{ ?>		
						<button type="submit" class="btn btn-primary submit__btn" style="display: none;" name="submit_btn">Submit</button>
						<button type="button" class="btn btn-primary submit__btn_popup" name="">Submit</button>
					   <?php } ?>		

                        </div>
						<textarea id="deleted_fields" name="deleted_fields" style="display: none;"></textarea>

					</form>
					</div>	

						
						<!-- <div class="col-md-4 border rounded text-center">
							<a href="<?=HOST_URL?>adminpannel/add-website.php">
								<i class="fa fa-plus" aria-hidden="true"></i>
								<p>Add Project</p>
							</a>
						</div> -->
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
		<?php

$sql_old= "SELECT  SUM(addon_count)  FROM `addon_site` WHERE status = 'succeeded' AND site_id='".base64_decode($_GET['project'])."' and is_active='1'  order by id desc limit 0,1 ";
                            // echo "sql_old ".$sql_old;
                            $query_old = $conn->query($sql_old) ;
                            // if($query_old->num_rows > 0) {
                            // 	$user_subscription_id = $query_old->fetch_assoc()['addon_count'] ;
                            // 	// echo $user_subscription_id;
                            // 	?>
                            // 	console.log("hi");
                            // 	<?php
                            // }
							while($row = mysqli_fetch_array($query_old)){

								$user_subscription_id =$row['SUM(addon_count)'];
								// echo " Total cost: ". $row['SUM(addon_count)'];
								// echo "<br>";
							}
?>

		var i = <?=$total_additional_websites?>+1;
		
// console.log(5<?php if(isset($user_subscription_id)){echo "+".$user_subscription_id;}?>);
		if( i >= 5<?php if(isset($user_subscription_id)){echo "+".$user_subscription_id;}?>){

			$('#additonal_website').hide();

		}

		function btn_check(id=''){

			$('#additonal_website').show();

				i--;

				if(id != ''){

			if($('#deleted_fields').val().length <= 0){

			$('#deleted_fields').append(id);

			}else{
				
				$('#deleted_fields').append(','+id);				
			}	
				}


		}

		//123

// 		add_more_websites()
// function add_more_websites(btn){
// 	i++;
// $('#additonal_website').hide();
// if(i >= 5<?php if(isset($user_subscription_id)){echo "+".$user_subscription_id;}?>){

// 	// $('#new_website').append('<div class="additional_websites"><div class="form-group"><label>Add Website Name '+i+'</label><input type="hidden" name="additional_web_id[]"  value="new"><input type="text" class="form-control additonal-names" id="website_name'+i+'" value="" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" ></div><div class="form-group"><label>Add Website URL '+i+'</label><input type="url" class="form-control additonal-urls" value="" id="website_url'+i+'"  name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" ><small>(eg. https://abc.com , http://xyz.com)</small></div><button type="button" style="display:none" class="btn btn-danger" id="remove_website" style="width: 3%" name="remove_website[]" onclick="$(this).parent().remove(); btn_check();">-</button></div></div>')

// }else{

// $('#new_website').append('<div class="additional_websites"><div class="form-group"><label>Add Website Name '+i+'</label><input type="hidden" name="additional_web_id[]"  value="new"><input type="text" class="form-control additonal-names" id="website_name'+i+'" value="" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" ></div><div class="form-group"><label>Add Website URL '+i+'</label><input type="url" class="form-control additonal-urls" value="" id="website_url'+i+'"  name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" ><small>(eg. https://abc.com , http://xyz.com)</small></div><button type="button" style="display:none" class="btn btn-danger" id="remove_website" style="width: 3%" name="remove_website[]" onclick="$(this).parent().remove(); btn_check();">-</button></div></div>')
// 	add_more_websites();
// }

// }


$(document).ready(function(){

    $(".submit__btn_popup").click(function(){
	    	Swal.fire({
			  title: 'Are you sure?',
			  text: "You can edit website link only once, you will not be able to edit it again, please confirm the updated link",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Confirm',
			  cancelButtonText: 'Continue Edit'
			}).then((result) => {
			  if (result.isConfirmed) {
			     $(".submit__btn").click();
			  }
			})
    });


	$("#add-website-form").submit(function(e){

		var valid = true ;

		$(".additonal-urls , .additonal-names , #website_name").removeClass("invalid") ;

		//  ============================================
		// e.preventDefault() ;

		// console.log("call") ;

		var website_name = $("#website_name").val() ;
		if ( website_name == undefined || website_name == '' || website_name == null ) {
			$("#website_name").addClass("invalid") ;
			valid = false;
		}

		var website = $("#website-url").val() ;
		var website_parse = new URL(website) ;
		var website_origin = website_parse.origin ;

		// console.log("website_origin : "+website_origin) ;
		// console.log($(".additonal-urls").length) ;

		// $(".additonal-names").each(function(i,o) {
		// 	var name = $(o).val();
		// 	if ( name == "" || name == null || name == undefined ) {
		// 		valid = false ;
		// 		$(o).addClass("invalid") ;
		// 	}
		// });


		var f = 0 ;
		$(".additonal-urls").each(function(i,o) {

			var url = $(o).val();
			if ( url == "" || url == null || url == undefined ) {
				// valid = false ;
				// $(o).addClass("invalid") ;
			}
			else 
			{
				url = new URL(url) ;
				var url_origin = url.origin ;
				console.log("url_origin : "+url_origin) ;

				if ( website_origin != url_origin ) {
					$(o).addClass("invalid") ;
					f = 1 ;
					valid = false ;
				}
			}
		});

		// if ( f == 1 ) {
        //     $(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Invalid site domain for additional urls.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
		// }

		if ( !valid ) { e.preventDefault() ; }

		else{
		$(".loader").show().html("<div class='loader_s'><dotlottie-player src='https://lottie.host/ce2baafe-2f55-4b3f-8042-d3b04d48212c/FV1UcE5Bkc.lottie'  background='transparent'  speed='1'  style='width: 300px; height: 300px;' loop autoplay></dotlottie-player>    <p id='loader_text'>Analizing your website. It might take 2-3 mins<p>     </div>");

	}
	});
}) ;

	// $('.submit__btn').click(function() {

	// });


	</script>

	</body>
	
</html>