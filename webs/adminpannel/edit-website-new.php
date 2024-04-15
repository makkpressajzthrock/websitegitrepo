<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
require_once('meta_details.php');
require_once('inc/functions.php') ;

$project = base64_decode($_GET['project']) ;
$website_data = getTableData( $conn , " boost_website " , " id = '$project' " ) ;

if($_GET['project']==null || count($website_data)<=0 ){
	$_SESSION['error'] = "Requested URL is not valid!";

	header("location: ".HOST_URL."adminpannel/dashboard.php");
	die();
}


// ================================================================
$user_id = $_SESSION["user_id"] ;

// check additional urls to create new empty field 
$sql = "SELECT * FROM additional_websites WHERE manager_id = '".$user_id."' AND website_id= '".$project."'";
$query = $conn->query($sql) ;

if ( $query->num_rows <= 0 ) {
	// create 4 blank rows
	for ($i=2; $i < 6 ; $i++) { 

		$url_priority = $i ;

		$sql = " INSERT INTO additional_websites ( manager_id , website_id , website_name , website_url , monitoring , flag , subscribed_value , url_priority ) VALUES ( '$user_id' , '$project' , '' , '' , 0 , 'true' , 0 , $url_priority ) ; " ;
		$conn->query($sql) ;
	}

	header("Refresh: 0");
	die() ;
}



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
		$website_url = $website_data["website_url"] ;

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

			updateTableData( $conn , " boost_website " , " website_name = '$website_name_1' " , " id = '$project' " ) ;


// echo "<pre>";
// print_r($additional_website_url);
// die;

			// rest process
			foreach($current_additional_website_id as $website_id_key => $website_id_val) {

				if($additional_website_url[$website_id_key] != ''){

				 	$sql = 'UPDATE additional_websites set website_name="'.$additional_website_name[$website_id_key].'", website_url= "'.$additional_website_url[$website_id_key].'" where id = '.$website_id_val;

// die;
				// }
				// else {

				// 	$sql = "INSERT INTO additional_websites (manager_id, website_name, website_id , website_url) VALUES('$user_id', '".$additional_website_name[$website_id_key]."', '$project', '".$additional_website_url[$website_id_key]."')";
				// }

				// echo "<hr>".$sql."<hr>" ; die() ;

				
				if ( $conn->query($sql) === TRUE ) {
					$query_completed = true;
						if(!empty( mysqli_insert_id($conn))){
							$additional = mysqli_insert_id($conn);
						}else{
							$additional =$website_id_val;
						}

						if ( ! empty($additional_website_url[$website_id_key]) ) {
							// code...
							$data = google_page_speed_insight($additional_website_url[$website_id_key], "desktop");

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
								$mobile_data = google_page_speed_insight($additional_website_url[$website_id_key], "mobile");

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


								if ($additional) {
									$sql = " INSERT INTO pagespeed_report ( website_id , parent_website , requestedUrl , finalUrl , userAgent , fetchTime , environment , runWarnings , configSettings , audits , categories , categoryGroups , i18n , mobile_environment , mobile_runWarnings , mobile_configSettings , mobile_audits , mobile_categories , mobile_categoryGroups , mobile_i18n ) VALUES ( '$additional' , '$project' , '$requestedUrl' , '$finalUrl' , '$userAgent' , '$fetchTime' , '$environment' , '$runWarnings' , '$configSettings' , '$audits' , '$categories' , '$categoryGroups' , '$i18n' , '$mobile_environment' , '$mobile_runWarnings' , '$mobile_configSettings' , '$mobile_audits' , '$mobile_categories' , '$mobile_categoryGroups' , '$mobile_i18n' ) ";
									// echo "sql ".$sql."<br>";    
									if ($conn->query($sql) == true) {
										// echo "success";

									}
								}
							}
						}
				}
				else {
					$query_completed = false;
				}
			}
			}

			if(!empty($deleted_fields)){

				$deleted_websites = explode(',',$deleted_fields);

				foreach($deleted_websites as $deletedid){
					$conn->query("DELETE FROM additional_websites WHERE id = '$deletedid'");
				}
			}


			if ( $query_completed === TRUE ) {
				$_SESSION['success'] = "Updated Successfully" ;
			}
			else {
				$_SESSION['error'] = "Operation Failed." ;
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
                    <div class="back_btn_wrap ">
					<a href="<?=HOST_URL?>adminpannel/dashboard.php" class="btn btn-primary">Back</a>
</div>
					<div class="form_h">
					<!-- <div class="get_addon_btn_wrap ">
					<a href="<?=HOST_URL?>adminpannel/addon.php?project=<?=$_GET["project"]?>" class="btn btn-primary">Buy More URL</a>
						</div> -->
						<?php require_once("inc/alert-status.php") ; ?>
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
									<input type="url" class="form-control" id="website-url" value="<?=$row_data['website_url']?>" placeholder="https://abc.com" readonly>
								</div>
							<?php
							if ( $row_data['platform'] == "Shopify" ) {
								?>
								<div class="form-group shopify-domain-input">
									<label>Add shopify domain URL</label>
									<input type="url" class="form-control" id="shopify-url" placeholder="https://abc.myshopify.com" value="<?=$row_data['shopify_url']?>" readonly >
								</div>
								<?php
							}
							?>
						</div>

						<div id="new_website">
						<?php 

							$i = 0;

							//gettting additional website's data in arrays
							foreach($additional_data as $additional_data_key => $additional_data_value) {

								$additional_website_name[] = $additional_data_value['website_name'];

								$additional_website_url[] = $additional_data_value['website_url'];

								$num = $i + 2;

								$readonly = empty($additional_data_value['website_url']) ? "" : "readonly" ;

								echo '<div class="additional_websites"><div class="col-md-6"><div class="form-group"><label>Add Website Name '.$num.'</label><input '.$readonly.'  type="hidden" name="additional_web_id[]"  value="'.$additional_data_value['id'].'"><input type="text" '.$readonly.' class="form-control additonal-names " id="website_name'.$i.'" value="'.$additional_website_name[$i].'" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" ></div></div><div class="col-md-6"><div class="form-group"><label>Add Website URL '.$num.'</label><input '.$readonly.' type="url" class="form-control additonal-urls" value="'.$additional_website_url[$i].'" id="website_url'.$i.'" name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" ><small>(eg. https://abc.com , http://xyz.com)</small></div></div><button type="button" style="display:none" class="btn btn-danger" id="remove_website" style="width: 3%" name="remove_website[]" onclick="$(this).parent().remove(); btn_check('.$additional_data_value['id'].');">-</button></div>'; 

								$i++;
							}
						?>
						</div>

							<div class="form-group add_web">
								<button type="button" class="btn btn-danger" id="additonal_website" name="additonal_website" onclick="add_more_websites(this);">+</button>
							</div>
						
					
						


                        <div class="form_h_submit">
						<button type="submit" class="btn btn-primary submit__btn" name="submit_btn">Submit</button>
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

		add_more_websites()
function add_more_websites(btn){
	i++;
$('#additonal_website').hide();
if(i >= 5<?php if(isset($user_subscription_id)){echo "+".$user_subscription_id;}?>){

	// $('#new_website').append('<div class="additional_websites"><div class="form-group"><label>Add Website Name '+i+'</label><input type="hidden" name="additional_web_id[]"  value="new"><input type="text" class="form-control additonal-names" id="website_name'+i+'" value="" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" ></div><div class="form-group"><label>Add Website URL '+i+'</label><input type="url" class="form-control additonal-urls" value="" id="website_url'+i+'"  name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" ><small>(eg. https://abc.com , http://xyz.com)</small></div><button type="button" style="display:none" class="btn btn-danger" id="remove_website" style="width: 3%" name="remove_website[]" onclick="$(this).parent().remove(); btn_check();">-</button></div></div>')

}else{

$('#new_website').append('<div class="additional_websites"><div class="form-group"><label>Add Website Name '+i+'</label><input type="hidden" name="additional_web_id[]"  value="new"><input type="text" class="form-control additonal-names" id="website_name'+i+'" value="" name="additional_website_name[]" placeholder="Page Name" autocomplete="off" ></div><div class="form-group"><label>Add Website URL '+i+'</label><input type="url" class="form-control additonal-urls" value="" id="website_url'+i+'"  name="additional_website_url[]" placeholder="https://abc.com" autocomplete="off" ><small>(eg. https://abc.com , http://xyz.com)</small></div><button type="button" style="display:none" class="btn btn-danger" id="remove_website" style="width: 3%" name="remove_website[]" onclick="$(this).parent().remove(); btn_check();">-</button></div></div>')
	add_more_websites();
}

}


$(document).ready(function(){

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