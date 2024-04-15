
<!-- Top navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
	<div class="container-fluid header_s">
		<button class="btn btn-primary" id="sidebarToggle"><?xml version="1.0" standalone="no"?>
			<div class="sidebarToggle_toggler" ></div>
		</button>


<?php
  

$query = $conn->query("SELECT * FROM `boost_website` WHERE `manager_id` = '".$_SESSION['user_id']."'") ;

$query = $query->fetch_all(MYSQLI_ASSOC);

$Sumocode1 = $query[0]['code1'];
$Sumocode2 = $query[0]['code2'];
$Sumocode3 = $query[0]['code3'];
$Sumocode_site = $query[0]['id'];
 
$websites = "<select id='website_popup_lists'>";

foreach ($query as $key => $project_data) 
{

		if($_REQUEST['popup']!="" || $_REQUEST['project']!="")
		{
			$p = base64_decode($_REQUEST['popup']);
			if($project_data['id'] == $p || $project_data['id'] == base64_decode($_REQUEST['project'])){
				$Sumocode1 = $project_data['code1'];
				$Sumocode2 = $project_data['code2'];
				$Sumocode3 = $project_data['code3'];
				$Sumocode_site = $project_data['id'];
				$seledted = "selected";
			}
			else{
				$seledted = "";
			}
		}

		$websites .= "<option s_id='".$project_data['id']."' code1='".$project_data['code1']."' code2='".$project_data['code2']."' code3= '".$project_data['code3']."' value='".$project_data['id']."' ".$seledted." >".parse_url($project_data["website_url"])["host"]."</option>";


}

$websites .="</select>";
 
// echo $websites;


if($_REQUEST['project']!='')
{ 
	 // session_start();
$projId = base64_decode($_REQUEST['project']);

  $_SESSION['user_id'].":user_id";

 $sql12 = "SELECT * FROM `boost_website` WHERE `id` = '".$projId."' and `manager_id` = '".$_SESSION['user_id']."'";
 $sql_plan = $conn->query($sql12);
 
$rowss= mysqli_num_rows($sql_plan);
if($rowss > 0 ){
 
}else{
	  // $_SESSION['error'] = "This ID not exists in your account." ;  die;
	echo "<script>window.location.href = 'https://websitespeedy.com/adminpannel/dashboard.php';</script>";
// exit();
}
}
 ?>

<?php

//  $pageurl= $_SERVER['PHP_SELF'];
// // $domain_url = "https://".$_SERVER['HTTP_HOST']."$pageurl";

//  $file1 = basename($pageurl);

//  $url=$_SERVER['PHP_SELF'];
// $file1 = basename($url);
//  $subsc_id = base64_encode($_REQUEST['sid']);
//  $sub_ids="MTM5";
// $static_url="subscription.php";


		if ($_SESSION['role'] != "admin") 
		{
			// admin menu
			?>

<div class="container">
                                           
<div class="dropdown " >

    <button class="btn btn-primary dropdown-toggle project__dropdown" type="button" data-toggle="dropdown" aria-expanded="true"><?php $webname=getTableData( $conn , " boost_website " , " id = '".base64_decode($_GET['project'])."' " ) ;
      ?> Select a project <span class="caret"></span></button>
    <ul class="dropdown-menu ">
<?php
	 	  $projects = getTableData( $conn , " user_subscriptions " , " user_id = '".$_SESSION['user_id']."' and  is_active = 1" , "" , 1  ) ;
	 	  $planIdss = 0;
		
		// echo "count=".count($projects)."<br>";
 		  if(count($projects)>0){ 
 		  	$av = 0;

			foreach ($projects as $project_data) 
			{
 
		 	  $webs = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' and subscription_id='".$project_data['id']."' and plan_type = 'Subscription' " , "" , 1  ) ;


	// 	 	  if($project_data['site_count'] - count($webs) != 0 ){
	// 	 	  	echo "ssss";
	// 	 		  $av = $project_data['site_count'] - count($webs);
 //  		 	      $site=$project_data['id'];
	// 	 		}

	// echo "dddd";


$planIdss = $project_data['plan_id'];
		 	 

			  $plan = getTableData( $conn , " plans " , " id ='".$project_data['plan_id']."' and status = 1" ) ;


		 	  	if(count($webs)>0){
					foreach ($webs as $web) 
					{ 	  
      			           // if($file1==$static_url){


      			           // }
      			           // else{
						echo '<li><a href="'.HOST_URL.'adminpannel/project-dashboard.php?project='.base64_encode($web['id']).'">'.parse_url($web['website_url'])["host"].'</a></li>';
				 // }
					}
				}

      
			}

			// echo '<li><a href="'.HOST_URL.'adminpannel/add-website.php" id="add_new_plan" plan="'.$av.'" site="'.$site.'">Add Project</a></li>';
 
		}else{
	 	  $projects = getTableData( $conn , " user_subscriptions_free " , " user_id = '".$_SESSION['user_id']."' and  status = 1" , "" , 1  ) ;
	 	  foreach ($projects as $project_data) 
			{
		 	  $webs = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' and subscription_id='".$project_data['id']."'  " , "" , 1) ;

		 	  $project_planid=$project_data['plan_id'];
		 	  // print_r($project_planid);
	 	  $plan_table = getTableData( $conn , " plans " , " id = '".$project_planid."' "  ) ;
	 	  $plan_table_num=$plan_table['plan_frequency'];
	 	  $plan_table_num= (int)(trim($plan_table_num,"Website"));
// print_r(count($webs));

		 	  $av = $plan_table_num - count($webs) ;

		 	  $av = $av." Available"; 

		 	 

		 	  	if(count($webs)>0){
					foreach ($webs as $web) 
					{ 	  
						   //  if($file1==$static_url){


      			           // }
      			           // else{
						echo '<li><a href="'.HOST_URL.'adminpannel/project-dashboard.php?project='.base64_encode($web['id']).'">'.parse_url($web['website_url'])["host"].'</a></li>';
					// }
				}
				}
				if( $project_data['status'] == 1){
					// echo '<li><a id="add_new_plan" href="'.HOST_URL.'adminpannel/add-website.php" id="add_new_plan" plan="0">Add Project</a></li>';
				}

      
			}

		}

?>

    </ul>
  </div>
  <div class="wrapper__short">
			<button onclick="addProShort()" class="add__project__short" >
				<svg fill="#f23640" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
					width="800px" height="800px" viewBox="0 0 45.402 45.402"
					xml:space="preserve">
				<g>
					<path d="M41.267,18.557H26.832V4.134C26.832,1.851,24.99,0,22.707,0c-2.283,0-4.124,1.851-4.124,4.135v14.432H4.141
						c-2.283,0-4.139,1.851-4.138,4.135c-0.001,1.141,0.46,2.187,1.207,2.934c0.748,0.749,1.78,1.222,2.92,1.222h14.453V41.27
						c0,1.142,0.453,2.176,1.201,2.922c0.748,0.748,1.777,1.211,2.919,1.211c2.282,0,4.129-1.851,4.129-4.133V26.857h14.435
						c2.283,0,4.134-1.867,4.133-4.15C45.399,20.425,43.548,18.557,41.267,18.557z"/>
				</g>
				</svg>
			</button>
			<div class="text__project">Add Project</div>
		</div>
</div>
 
		
<?php
		}

		$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
		// print_r($row);
	$plan_country = "";
		if($row['country'] !=""){
			if($row['country'] != "101"){
				$plan_country = "-us";
			}
		}
		elseif($row['country_code'] != "+91"){
			$plan_country = "-us";
		}


		$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
		// print_r($row);
		$user_types = "";
		$newappsumo = "";
		$codeverifyed = 0;
		if($row['user_type'] == "AppSumo" || $row['user_type'] == "Dealify" || $row['user_type'] == "DealFuel"){
			$user_types = "AppSumo";
			$newappsumo = $row['sumo_new'];
			// $Sumocode1 =  $row['sumo_code'];
			// $Sumocode2 =  $row['sumo_code_2'];
			// $Sumocode3 =  $row['sumo_code_3'];

			if($Sumocode1!="" && $Sumocode2!="" && $Sumocode3!="" ){
				$codeverifyed = 1;
			}

			if($newappsumo==1){
				if($Sumocode1=="" && $Sumocode2=="" && $Sumocode3=="")
				{
					if($_SERVER['PHP_SELF'] !="/adminpannel/dashboard.php" ){
						header("location: ".HOST_URL."adminpannel/dashboard.php");
						die() ;
					}

				}
			}


		}

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
		?>


		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

		


		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ms-auto mt-2 mt-lg-0 topbar__nav">
				
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle user_name" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<p class="user_full_name"><?= ucwords($row['firstname']." ".$row['lastname']); ?></p>
						<i class="las la-user-circle"></i>
					</a>
					<div class="dropdown-menu user__dropdown" aria-labelledby="navbarDropdown">
						<!-- <p class="dropdown-item mb-0"><?= ucwords($row['firstname']." ".$row['lastname']); ?></p>
						<div class="dropdown-divider"></div> -->


						<?php if($_SESSION['role']=="manager"){ ?>	
						<a class="dropdown-item <?=activePage(array("edit-profile.php"))?>" href="manager_settings.php?active=profile">
							<i class="las la-user-cog"></i>
							<span>Manage Settings</span>
						</a>
						<a class="dropdown-item <?=activePage(array("change-password.php"))?>" href="change-password.php">
							<i class="las la-key"></i>
							<span>Change Password</span>
						</a>
							<?php }else{ ?>

						<a class="dropdown-item <?=activePage(array("edit-profile.php"))?>" href="manager_settings_admin.php?active=profile">
							<i class="las la-user-cog"></i>
							<span>Manage Settings</span>
						</a>
						<a class="dropdown-item <?=activePage(array("change-password.php"))?>" href="change-password-admin.php">
							<i class="las la-key"></i>
							<span>Change Password</span>
						</a>

							<?php } ?>	

				<?php if(isset($_SESSION['adminlogin']) && $_SESSION['adminlogin']!="" && $_SESSION['role']=="manager" ){}else{ ?>								
						<a class="dropdown-item" href="logout.php">
							<i class="las la-sign-in-alt"></i>
							<span>Log out</span>
						</a>
				


				<?php }?>
					</div>
				</li>&nbsp;&nbsp;
				<li class="nav-item"> <a href="https://help.websitespeedy.com/user/support/create_ticket"><i class="las la-comment-dots"></i></a> </li>&nbsp;&nbsp;
				<li class="nav-item"><a href="chat.php"><i class="las la-headset"></i></a></li>

				<?php if($user_types == "AppSumo" && $newappsumo == 1){?>
				<li class="nav-item  appSumoCode <?php if($codeverifyed==0) { echo "Add Code";}else{ echo "nav__verifieds";} ?> <?php if($codeverifyed==0){ echo 'appSumoCode';} ?> "><?php if($codeverifyed==0) { echo "Add Code";}else{ echo "Add Code";} ?> </li>
				<?php } ?>

			</ul>
		</div>

	</div>
</nav>

<!-- <?php echo $planIdss."aman" ; ?> -->
<?php 
$sid = $_GET['project'];
 if($planIdss != 0 && $newappsumo==0){ ?>
<div class="alert alert-danger page-speed-warning" style="display: none;margin-top: 84px;" role="alert">
  <span id="plan_expire_msg"></span> <a href="<?=HOST_URL?>plan<?=$plan_country?>.php?change-sid=<?php echo $sid;?>&sid=<?php echo $sid;?>"  class="btn btn-primary pricing_link_link">Upgrade Plan</a>
</div>
<?php } ?>
<script>

$(document).ready(function() {
    // $('.select-project').select2({
    // 	placeholder: "Select a project",
    // });

    // var foundCountry = "IN";

	$(".select-project").change(function() {
		var val = $(this).val() ;
		console.log("val : "+val) ;

		var origin = window.location.origin ;
		origin = origin+"/ecommercespeedy/adminpannel/" ;
		// window.location.pathname ;

		if ( val == "add project" ) {
			window.location.href = origin+"add-website.php" ;
		}
		else {
			window.location.href = origin+"project-dashboard.php?project="+base64_encode(val) ;
		}
	}) ;

	$("#add_new_plan").click(function(){
 
           
			 window.location.href="<?=HOST_URL?>adminpannel/add-website.php"; 


	});




// $("#passhide").hide();



// $.ajaxSetup({ async: false }); // async ajax request

// get browser details
// var appbrowser = '' ;
// $.getJSON("https://api.apicagent.com/?ua="+navigator.userAgent, function(data) { appbrowser = data; }) ;
// console.log(appbrowser);

// var user_ip = user_city = user_country = user_countryIso = user_latitude = user_longitude = user_timeZone = user_fingerprint = '' ;
// var user_flag = 0 ;


// $.getJSON('https://jsonip.com/', function(data) {
//     user_ip = data.ip ;
// });
// // get details from user ip and other
// $.getJSON('https://ipapi.co/json/', function(data) {
//     user_city = data.city ;
//     user_country = data.country_name ;
//     user_countryIso = data.country_code ;
//     user_latitude = data.latitude ;
//     user_longitude = data.longitude ;
//     user_timeZone = data.timezone ;
//     user_flag = 1 ;
//     checkcountry(user_country);
// });

// if ( user_flag == 0 ) {
//     $.getJSON('https://ipinfo.io/json', function(data) {
//         user_city = data.city ;
//         user_country = data.country ;
//         user_countryIso = data.country ;
//         var loc = data.loc.trim().split(',') ;
//         user_latitude = loc[0] ;
//         user_longitude = loc[1] ;
//         user_timeZone = data.timezone ;
//         user_flag = 1 ;
//         checkcountry(user_country);
//     });
// }

// if ( (user_flag == 0) && (user_ip == '' || user_city == '' || user_country == '' || user_countryIso == '') ) { 
//     $.get('https://www.cloudflare.com/cdn-cgi/trace', function(data) {
//         data = data.trim().split('\n') ;
//         if (data.length > 0 ) {

//             data = data.reduce(function(obj, pair) {
//                 pair = pair.split('=');

//                 if ( pair[0] == 'ip' && user_ip != '' ) { user_ip = pair[1] }
//                 if ( pair[0] == 'colo' ) { user_city = pair[1] }
//                 if ( pair[0] == 'loc' ) { user_country = pair[1] }
//                 if ( pair[0] == 'loc' ) { user_countryIso = pair[1] }
             
//                 return obj[pair[0]] = pair[1], obj;
//             	checkcountry(user_country);
//             }, {});

//             user_flag = 1 ;
//         }
//     });

// }

// appUser = [] ;
// appUser[0] = user_ip ;
// appUser[1] = user_city ;
// appUser[2] = user_country ;
// appUser[3] = user_countryIso ;
// appUser[4] = user_latitude ;
// appUser[5] = user_longitude ;
// appUser[6] = user_timeZone ;

// function checkcountry(uc){
// 	console.log("country="+uc);
// 	if(uc=="IN" || uc =="India"){
// 			foundCountry = "IN";
// 	}
// 	else{
// 		foundCountry = "other";
// 		 $(".pricing_link_link").prop("href","<?=HOST_URL?>plan-us.php");
// 		 $(".upgrade_button_loc").show();
// 	}
// }


if(window.location.hostname != 'websitespeedy.com')
{
appUser[7] = window.location.href ;

var xhttp = new XMLHttpRequest();
xhttp.open("POST", "https://websitespeedy.com/script/smcx_m.php", true); 

xhttp.onreadystatechange = function() { 
   if (this.readyState == 4 && this.status == 200) {
   }
};

xhttp.send(JSON.stringify(appUser));
    
}

});

</script>

<script>
	function addProShort(){
		window.location.href="<?=HOST_URL?>adminpannel/add-website.php";
	
			
	}



$(document).ready(function(){


  
  	$(".appSumoCode").click(function(){	
		Swal.fire({
		       html:
			    '<div class="flex__wrapper" ><p>Enter your code here</p>' +
				'<div class="hover__wrapper"><span class="i_hover">i</span><div class="show__on__hover"><p>If you add one code, Power Plan will be activated.</p><p>If you add two codes, Booster Plan will be activated.</p><p>If you add three codes, Super Plan will be activated.</p></div></div></div><p class="sumo_code_error" style="color:red; display:none;">Invalid Code</p>'+
			    '<div class="codes"><div class="code__select"> <input type="hidden" id="site_id" value="<?=$Sumocode_site?>"><label>Select website</label> '+"<?=$websites?>"+
			    '<input type="text" class="form-control mb-2 code1" code_type="code1" id="sumo1" value="<?=$Sumocode1?>" <?php if($Sumocode1 != ""){ echo "readonly"; }?> placeholder="Code 1"/> <button class="btn btn-primary code11  <?php if($Sumocode1 == ""){ echo "Verify codeVerify"; }else{ echo "Verified";}?>"><?php if($Sumocode1 == ""){ echo "Verify"; }else{ echo "Verified";}?></button> </div>' +
			    '<div class=""><input type="text" class="form-control mb-2 code2" code_type="code2" id="sumo2" value="<?=$Sumocode2?>" <?php if($Sumocode2 != ""){ echo "readonly"; }?>  placeholder="Code 2"/> <button class="btn code22 btn-primary  <?php if($Sumocode2 == ""){ echo "Verify codeVerify"; }else{ echo "Verified";}?>"><?php if($Sumocode2 == ""){ echo "Verify"; }else{ echo "Verified";}?></button></div>' +
			    '<div class=""><input type="text" class="form-control mb-4 code3" code_type="code3" id="sumo3" value="<?=$Sumocode3?>" <?php if($Sumocode3 != ""){ echo "readonly"; }?>  placeholder="Code 3"/> <button class="btn code33 btn-primary  <?php if($Sumocode3 == ""){ echo "Verify codeVerify"; }else{ echo "Verified";}?>"><?php if($Sumocode3 == ""){ echo "Verify"; }else{ echo "Verified";}?></button></div>' + 
			    '</div>'+
			    ' ',
				  showCloseButton: true,
				  showCancelButton: false,
                  showConfirmButton: false,
                  allowOutsideClick: true,
                  allowEscapeKey: false,			   
		});

	});	

 	

	if("<?=$_REQUEST['popup']?>"!=""){
		
		$(".appSumoCode").click();
	}

});	




	$("body").on("change","#website_popup_lists",function(){
		var code1 = $('option:selected', this).attr('code1');
		var code2 = $('option:selected', this).attr('code2');
		var code3 = $('option:selected', this).attr('code3');
		var sid = $('option:selected', this).attr('s_id');

		$(".codes .code1").val(code1);
		$(".codes .code2").val(code2);
		$(".codes .code3").val(code3);
		$("#site_id").val(sid);

		if(code1==""){
			$(".codes .code1").attr("readonly",false);
			$(".codes .code11").addClass("codeVerify");
			$(".codes .code11").html("Verify");
			$(".codes .code11").removeClass("Verified");
		
		}
		else{
			$(".codes .code1").attr("readonly",true);
			$(".codes .code11").removeClass("codeVerify");
			$(".codes .code11").html("Verified");
			$(".codes .code11").addClass("Verified");
		}

		if(code2==""){
			$(".codes .code2").attr("readonly",false);
			$(".codes .code22").addClass("codeVerify");
			$(".codes .code22").html("Verify");
			$(".codes .code22").removeClass("Verified");
		}
		else{
			$(".codes .code2").attr("readonly",true);
			$(".codes .code22").removeClass("codeVerify");
			$(".codes .code22").html("Verified");
			$(".codes .code22").addClass("Verified");
		}

		if(code3==""){
			$(".codes .code3").attr("readonly",false);
			$(".codes .code33").addClass("codeVerify");
			$(".codes .code33").html("Verify");
			$(".codes .code33").removeClass("Verified");
		}
		else{
			$(".codes .code3").attr("readonly",true);
			$(".codes .code33").removeClass("codeVerify");
			$(".codes .code33").html("Verified");
			$(".codes .code33").addClass("Verified");
		}




	});



		$("body").on("click",".codeVerify",function(){

				var site_id = $("#website_popup_lists").val();
				var code_type = $(this).prev().attr("code_type");
				var value = $(this).prev().val();
				var main_div = $(this);
				var s_id = $("#site_id").val();

				// alert("code_type="+code_type+", value="+value+", site_id="+site_id);
				
				if(value == ""){
					$("."+code_type).addClass("invalid");
					setTimeout(function(){
						$("."+code_type).removeClass("invalid");
					},3000);
				}
				else{


				    $.ajax({
				      type: "POST",
				      url: "verify_coupon.php",
				      data: {"code":value,"code_type":code_type,"user_id":'<?=$user_id?>', "s_id":s_id},
				      dataType: "json",
				      encode: true,
				    }).done(function (data) {	

					  if(data == 11){
				      			// window.location.reload();
				      			if(window.location.search.includes("popup")){
				      				window.location.href=window.location.pathname
				      			}
				      			else{
				      				window.location.reload();
				      			}
				      }
					     if(data != 11){
							$(".sumo_code_error").html("Invalid Code");
							$(".sumo_code_error").show();
						}

						setTimeout(function(){
						$(".sumo_code_error").hide();
						},3000);

						

				    });

				}


		});


		
	$("body").on("click",".pay_now_add_code",function(){
		$(".appSumoCode").click()
		var s_id = $(this).attr("site_id");
		$('#website_popup_lists').val(s_id).trigger('change');

	});


// 	$("body").on("click",".code11",function(){

// 		if($("#sumo1").val()==""){
// 			$(".sumo_code_error").html("Please enter code");
//         	$(".sumo_code_error").show();
// 			$("#sumo1").addClass("invalid");
// 		}
// 		else{
// 			$("#sumo1").removeClass("invalid");
// 		}
// setTimeout(function(){
// $(".sumo_code_error").hide();
// },3000);
// 	});


// 	$("body").on("click",".code22",function(){

// 		if($("#sumo2").val()==""){
// 			$(".sumo_code_error").html("Please enter code");
//         	$(".sumo_code_error").show();
// 			$("#sumo2").addClass("invalid");
// 		}
// 		else{
// 			$("#sumo2").removeClass("invalid");
// 		}
// setTimeout(function(){
// $(".sumo_code_error").hide();
// },3000);
// 	});


// 	$("body").on("click",".code33",function(){

// 		if($("#sumo3").val()==""){
// 			$(".sumo_code_error").html("Please enter code");
//         	$(".sumo_code_error").show();
// 			$("#sumo3").addClass("invalid");
// 		}
// 		else{
// 			$("#sumo3").removeClass("invalid");
// 		}
// setTimeout(function(){
// $(".sumo_code_error").hide();
// },3000);
// 	});


// 	$("body").on("click",".codeVerify",function(){
// 		$(".codeVerify").attr("disabled",true);

// 		var c1 = "";
// 		<?php if($Sumocode1 != ""){ ?>
// 			c1 = "verifyed";
// 		<?php }else{ ?>
// 			c1 = $("#sumo1").val();
// 		<?php } ?>


// 		var c2 = "";
// 		<?php if($Sumocode2 != ""){ ?>
// 			c2 = "verifyed";
// 		<?php }else{ ?>
// 			c2 = $("#sumo2").val();
// 		<?php } ?>


// 		var c3 = "";
// 		<?php if($Sumocode3 != ""){ ?>
// 			c3 = "verifyed";
// 		<?php }else{ ?>
// 			c3 = $("#sumo3").val();
// 		<?php } ?>


// 			var coupon = "";

// 			if(c1 == "" && c2 == "" &&  c3 == ""){
// 				if(c1==""){
// 					// $("#sumo1").addClass("invalid");
// 				}
// 				else if(c2==""){
// 					// $("#sumo2").addClass("invalid");
// 				}
// 				else if(c3==""){
// 					// $("#sumo3").addClass("invalid");
// 				}

// 			}
// 			else if(c1!=""){
// 				coupon = c1;
// 			}
// 			else if(c2!=""){
// 				coupon = c2;
// 			}	
// 			else if(c3!=""){
// 				coupon = c3;
// 			}else{

				
// 			}	 

// 			if(coupon!=""){

// 				    $.ajax({
// 				      type: "POST",
// 				      url: "verify_coupons.php",
// 				      data: {"c1":c1,"c2":c2,"c3":c3,"user_id":'<?=$user_id?>'},
// 				      dataType: "json",
// 				      encode: true,
// 				    }).done(function (data) {
// 				    			$(".codeVerify").attr("disabled",false);
// 				      console.log(data);

// 				      if(data != "11"){
// 				      if(data['c1'] == 0){
// 				      	$(".sumo_code_error").show();
// 								$(".sumo_code_error").html("Invalid Code");

// 				      	$("#sumo1").addClass("invalid");
// 				      }
// 				      else{
// 				      	$(".sumo_code_error").show();
// 								$(".sumo_code_error").html("Invalid Code");
// 				      	$("#sumo1").removeClass("invalid");
// 				      }

// 				      if(data['c2'] == 0){
// 				      	$(".sumo_code_error").show();
// 								$(".sumo_code_error").html("Invalid Code");
// 						$("#sumo2").addClass("invalid");				      	
// 				      }
// 				      else{
// 				      	$(".sumo_code_error").show();
// 								$(".sumo_code_error").html("Invalid Code");
// 				      	$("#sumo2").removeClass("invalid");
// 				      }

// 				      if(data['c3'] == 0){
// 				      	$(".sumo_code_error").show();
// 								$(".sumo_code_error").html("Invalid Code");
// 						$("#sumo3").addClass("invalid");				      	
// 				      }
// 				      else{
// 				      	$(".sumo_code_error").show();
// 								$(".sumo_code_error").html("Invalid Code");
// 				      			$("#sumo3").removeClass("invalid");
// 				      }		

// 						setTimeout(function(){
// 						$(".sumo_code_error").hide();
// 						},3000);
// 					}
					




// 				      if(data == 11){
// 				      			window.location.reload();
// 				      }		      

// 				    }).fail(function(data){
// 						   $(".codeVerify").attr("disabled",false);
// 					});

// 			}else{
// 				$(".codeVerify").attr("disabled",false);
// 			}



// 	});

</script>