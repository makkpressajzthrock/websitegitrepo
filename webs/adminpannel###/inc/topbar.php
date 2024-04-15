<!-- Top navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
	<div class="container-fluid header_s">
	    <a class="mobile_logo" href="/adminpannel/dashboard.php"> <img id="loginimg" class="sidebar__open" src="https://websitespeedy.com/adminpannel/img/sitelogo_s.png"></a>
		<div class="nev_leftt">
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
	echo "<script>window.location.href = '".HOST_URL."adminpannel/dashboard.php';</script>";
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
                                           
<div class="dropdown  add_web_s" >
    <button class="btn btn-primary dropdown-toggle project__dropdown" type="button" data-toggle="dropdown" aria-expanded="true"><?php $webname=getTableData( $conn , " boost_website " , " id = '".base64_decode($_GET['project'])."' " ) ;
      ?> Select a website
    <span class="caret"></span></button>
<ul class="dropdown-menu">
<?php
	$user_id = $_SESSION['user_id'] ;

	$webs_query = $conn->query(" SELECT id , website_url FROM `boost_website` WHERE `manager_id` = '".$user_id."' ORDER BY id DESC ; ") ;

	if ( $webs_query->num_rows > 0 ) {
		$webs_data = $webs_query->fetch_all(MYSQLI_ASSOC) ;
		foreach ($webs_data as $key => $web) {
			// code...
			echo '<li><a href="'.HOST_URL.'adminpannel/project-dashboard.php?project='.base64_encode($web['id']).'">'.parse_url($web['website_url'])["host"].'</a></li>';
		}
	}
	
	/*** 
	 * old code
	$projects = getTableData( $conn , " user_subscriptions " , " user_id = '".$_SESSION['user_id']."' and  is_active = 1" , "" , 1  ) ;
	$planIdss = 0;
	
	// echo "count=".count($projects)."<br>";

	if(count($projects)>0) {

		$av = 0;
	
		foreach ($projects as $project_data) {
		
			$webs = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' and subscription_id='".$project_data['id']."' and plan_type = 'Subscription' " , "" , 1  ) ;
	
	
			// if($project_data['site_count'] - count($webs) != 0 ) {
			// 	$av = $project_data['site_count'] - count($webs);
			// 	$site=$project_data['id'];
			// }
	
	
			$planIdss = $project_data['plan_id'];
	
			$plan = getTableData( $conn , " plans " , " id ='".$project_data['plan_id']."' and status = 1" ) ;
	
		 	if(count($webs)>0) {
				foreach ($webs as $web) 
				{ 	  
					// if($file1==$static_url){}
					// else{
					echo '<li><a href="'.HOST_URL.'adminpannel/project-dashboard.php?project='.base64_encode($web['id']).'">'.parse_url($web['website_url'])["host"].'</a></li>';
					// }
				}
			}
	
		}
	
		// echo '<li><a href="'.HOST_URL.'adminpannel/add-website.php" id="add_new_plan" plan="'.$av.'" site="'.$site.'">Add Project</a></li>';
	
	}
	else {

		$projects = getTableData( $conn , " user_subscriptions_free " , " user_id = '".$_SESSION['user_id']."' and  status = 1" , "" , 1  ) ;

		foreach ($projects as $project_data) {
		
			$webs = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' and subscription_id='".$project_data['id']."'  " , "" , 1) ;

			$project_planid=$project_data['plan_id'];
			// print_r($project_planid);
			$plan_table = getTableData( $conn , " plans " , " id = '".$project_planid."' "  ) ;
			$plan_table_num=$plan_table['plan_frequency'];
			$plan_table_num= (int)(trim($plan_table_num,"Website"));
			// print_r(count($webs));
	
			$av = $plan_table_num - count($webs) ;

			$av = $av." Available"; 
	
	 		if(count($webs)>0) {
				foreach ($webs as $web) { 	  
					//  if($file1==$static_url) {}
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
	***/
?>
<li><a id="add_new_plan" href="<?=HOST_URL?>adminpannel/add-website.php" id="add_new_plan" plan="0">Add Website</a></li>
</ul>
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

</div>
<div class="page_hd_top"></div>
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
						<a class="dropdown-item" href="logout.php">
						<i class="las la-sign-in-alt"></i>
							<span>Log out</span>
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
						<a class="dropdown-item" href="logout.php">
							<i class="las la-sign-in-alt"></i>
							<span>Log out</span>
						</a>

							<?php } ?>	

				<!-- <?php //if(isset($_SESSION['adminlogin']) && $_SESSION['adminlogin']!="" && $_SESSION['role']=="manager" ){}else{ ?>								
						<a class="dropdown-item" href="logout.php">
							<i class="las la-sign-in-alt"></i>
							<span>Log out</span>
						</a>
				


				<?php //}?> -->
					</div>
				</li>
				
<?php if($row['id'] !=1){?>		
       <li>
<ul class="nav_new_S">

  <li class="button-dropdown help_sd">
    <a href="javascript:void(0)" class="dropdown-toggle help_btn">
				<svg id="icon" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;}</style></defs><title/><path d="M16,2A14,14,0,1,0,30,16,14,14,0,0,0,16,2Zm0,26A12,12,0,1,1,28,16,12,12,0,0,1,16,28Z"/><circle cx="16" cy="23.5" r="1.5"/><path d="M17,8H15.5A4.49,4.49,0,0,0,11,12.5V13h2v-.5A2.5,2.5,0,0,1,15.5,10H17a2.5,2.5,0,0,1,0,5H15v4.5h2V17a4.5,4.5,0,0,0,0-9Z"/><rect class="cls-1" height="32" width="32"/></svg>
				<span>Help</span> 
    </a>
    <ul class="dropdown-menu help_dd">
      <li>
        <a href="<?=HOST_HELP_URL?>knowledge-base/website-speedy-admin/platform-wise-instructions" target="_blank">
        <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="tasks" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-tasks fa-w-16">
               <path fill="currentColor" d="M496 232H208a16 16 0 0 0-16 16v16a16 16 0 0 0 16 16h288a16 16 0 0 0 16-16v-16a16 16 0 0 0-16-16zm0 160H208a16 16 0 0 0-16 16v16a16 16 0 0 0 16 16h288a16 16 0 0 0 16-16v-16a16 16 0 0 0-16-16zm0-320H208a16 16 0 0 0-16 16v16a16 16 0 0 0 16 16h288a16 16 0 0 0 16-16V88a16 16 0 0 0-16-16zM64.3 368C38 368 16 389.5 16 416s22 48 48.3 48a48 48 0 0 0 0-96zm75.26-172.51a12.09 12.09 0 0 0-17 0l-63.66 63.3-22.68-21.94a12 12 0 0 0-17 0L3.53 252.43a11.86 11.86 0 0 0 0 16.89L51 316.51a12.82 12.82 0 0 0 17.58 0l15.7-15.59 72.17-71.74a11.86 11.86 0 0 0 .1-16.8zm0-160a12 12 0 0 0-17 0L58.91 98.65 36.22 76.58a12.07 12.07 0 0 0-17 0L3.53 92.26a11.93 11.93 0 0 0 0 16.95l47.57 47.28a12.79 12.79 0 0 0 17.6 0l15.59-15.58 72.17-72a12.05 12.05 0 0 0 .1-17z"></path>
        </svg>			
         <span>Installation instructions</span>
        </a>
      </li>
      <li>
        <a href="<?=HOST_HELP_URL?>user/support/tickets/all" target="_blank">
			<svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 492.05"><path d="M48.36 320.24 340.19 28.4l.46-.31c1.91-1.83 4.11-3.24 6.43-4.19 5.1-2.09 10.87-2.08 15.98 0a20.7 20.7 0 0 1 6.74 4.5l38.33 38.33a7.412 7.412 0 0 1 2.13 4.63c.15 1.69-.25 3.48-1.22 4.98-2.24 3.44-3.86 7.2-4.78 11.06-6.25 25.91 17.07 48.94 42.53 42.83 3.78-.9 7.49-2.47 10.86-4.64a7.597 7.597 0 0 1 5.07-1.48c1.78.13 3.53.88 4.87 2.22l38.34 38.34c1.98 1.98 3.49 4.28 4.49 6.73a21.32 21.32 0 0 1 1.58 8 21.32 21.32 0 0 1-1.58 8c-1 2.44-2.51 4.75-4.49 6.73L214.09 485.97a20.685 20.685 0 0 1-6.73 4.49 20.946 20.946 0 0 1-8 1.59c-2.71 0-5.44-.53-8-1.59-2.46-1-4.76-2.52-6.74-4.49l-38.61-38.61c-1.19-1.18-1.92-2.79-2.1-4.45-.18-1.61.14-3.34 1.01-4.82 2.12-3.37 3.59-7.04 4.4-10.81 5.39-24.77-16.55-47.47-41.85-42.2-3.75.79-7.42 2.2-10.81 4.19a8.134 8.134 0 0 1-5.03 1.23 7.505 7.505 0 0 1-4.66-2.18L48.36 349.7a20.493 20.493 0 0 1-4.5-6.74 21.15 21.15 0 0 1-1.58-7.99c0-2.7.53-5.42 1.58-7.99a20.7 20.7 0 0 1 4.5-6.74zM324.92 26.26l-10.27 10.41-20.59-20.58c-1.52-1.52-3.99-1.96-5.96-1.16-.62.26-1.22.66-1.73 1.16l-36.32 36.32c.48.31.96.68 1.43 1.15l10.4 10.4c.28.28.48.52.7.81 5.37 7.51-4.83 15.87-11.1 9.59l-10.42-10.4c-.27-.27-.46-.53-.7-.82l-.42-.63L62.49 239.98c.69.35 1.34.82 1.97 1.45l10.4 10.41c.28.28.49.53.71.81 5.37 7.52-4.83 15.88-11.11 9.6-2.77-2.79-10.22-9.2-11.83-12.42l-36.54 36.55-.58.35c-1.14 1.52-1.44 3.7-.71 5.47.24.63.65 1.22 1.15 1.72l13.46 13.46-3.91 16.65-19.69-19.7a19.47 19.47 0 0 1-4.31-6.46c-2-4.87-1.99-10.43 0-15.3a19.51 19.51 0 0 1 4.31-6.47L276.1 5.82l.44-.3c1.83-1.76 3.93-3.11 6.16-4.02 4.89-2 10.42-2 15.31 0 2.34.96 4.55 2.41 6.45 4.31l20.46 20.45zm119.87 203.97c.61.6 1.24 1.07 1.89 1.42L251.77 426.56l-.32-.49c-.24-.3-.46-.57-.74-.85l-10.85-10.86c-6.56-6.55-17.19 2.18-11.6 10.01.24.3.45.57.73.85l10.87 10.85c.42.43.86.79 1.31 1.09l-37.94 37.94c-.54.53-1.16.95-1.8 1.21-.7.28-1.46.43-2.22.43-.71 0-1.49-.16-2.21-.46-.66-.26-1.3-.66-1.79-1.18l-34.59-34.58a51.6 51.6 0 0 0 3.83-12.69c5.6-33.97-23.73-63.91-58.24-58.22-4.34.7-8.61 2-12.68 3.82l-34.59-34.59c-.53-.54-.95-1.15-1.2-1.81-.28-.66-.42-1.42-.42-2.21 0-1.2.4-2.47 1.17-3.49l.59-.37 38.13-38.13c.21.41.45.83.75 1.24.24.31.45.57.74.85l10.86 10.86c6.55 6.56 17.19-2.17 11.59-10.01-.23-.3-.45-.56-.73-.85l-10.86-10.86a8.604 8.604 0 0 0-2.06-1.52L302.47 87.57l.44.65c.24.3.45.57.73.85l10.86 10.86c6.55 6.55 17.19-2.18 11.59-10.01-.24-.3-.45-.56-.74-.85L314.5 78.22c-.48-.48-.98-.87-1.49-1.19l37.91-37.91c.52-.52 1.15-.94 1.8-1.21 2.06-.84 4.64-.37 6.22 1.21l34.27 34.28a50.213 50.213 0 0 0-4.3 12.79c-6.79 35.35 24.22 65.79 59.1 59.08 4.39-.84 8.71-2.29 12.79-4.3l34.27 34.27a5.3 5.3 0 0 1 1.2 1.81c.27.67.41 1.43.41 2.21 0 1.41-.54 2.94-1.62 4.02l-38.01 38.01c-.19-.36-.41-.71-.66-1.07-.24-.3-.45-.57-.74-.85l-10.86-10.86c-6.55-6.55-17.18 2.17-11.59 10.01.24.3.46.56.74.85l10.85 10.86zm-130.4-83.78 75.13 75.16c4.65 4.9 6.99 11.22 6.99 17.51 0 6.52-2.48 13.05-7.42 17.98L277.13 369.06c-4.93 4.94-11.47 7.42-17.97 7.42-6.52 0-13.05-2.48-17.98-7.42l-74.7-74.7c-4.94-4.93-7.42-11.46-7.42-17.98 0-6.48 2.48-13 7.42-17.95l111.96-111.99c4.95-4.95 11.47-7.42 17.98-7.42 6.51.03 13.05 2.51 17.97 7.43zm64.24 85.16-74.7-74.69a10.624 10.624 0 0 0-7.51-3.13c-2.73.03-5.46 1.06-7.52 3.12L176.94 268.87c-2.05 2.04-3.08 4.77-3.08 7.51 0 2.73 1.03 5.47 3.08 7.52l74.7 74.7c2.05 2.05 4.79 3.08 7.52 3.08 2.73 0 5.46-1.03 7.51-3.08l111.96-111.96c2.05-2.05 3.08-4.79 3.08-7.52 0-2.61-.94-5.23-2.8-7.25l-.28-.26zm-171.35 171.9c6.55 6.55 17.19-2.18 11.6-10.01-.25-.31-.46-.57-.74-.85l-10.86-10.87c-6.55-6.55-17.19 2.18-11.59 10.02.23.3.45.56.73.85l10.86 10.86zm-32.57-32.58c6.55 6.56 17.19-2.17 11.59-10.01-.24-.3-.45-.56-.74-.85l-10.85-10.86c-6.55-6.55-17.19 2.18-11.6 10.01.24.3.45.57.74.85l10.86 10.86zm-32.58-32.57c6.55 6.55 17.19-2.18 11.6-10.01-.24-.31-.45-.57-.73-.85l-10.87-10.87c-6.55-6.55-17.19 2.18-11.59 10.02.24.3.45.56.73.85l10.86 10.86zm270.09-140.71c6.56 6.56 17.19-2.17 11.6-10-.25-.31-.46-.57-.74-.85l-10.86-10.87c-6.56-6.55-17.19 2.18-11.6 10.01.25.3.46.57.75.85l10.85 10.86zm-32.57-32.57c6.55 6.55 17.19-2.18 11.59-10.01-.24-.3-.45-.56-.74-.85l-10.85-10.85c-6.56-6.56-17.19 2.17-11.6 10 .25.3.46.56.74.85l10.86 10.86zm-32.57-32.57c6.55 6.55 17.18-2.18 11.59-10.01-.25-.31-.46-.57-.74-.85l-10.85-10.86c-6.56-6.56-17.19 2.18-11.6 10 .24.31.45.57.74.85l10.86 10.87z"/></svg>
          <span>Open Ticket</span>
        </a>
      </li>

      <li>
				<a href="<?=HOST_HELP_URL?>faqs" target="_blank">
					<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="540.000000pt" height="540.000000pt" viewBox="0 0 540.000000 540.000000" preserveAspectRatio="xMidYMid meet">
						<g transform="translate(0.000000,540.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
							<path d="M2521 5385 c-194 -43 -357 -181 -434 -370 l-32 -80 -3 -725 c-2 -507
								0 -743 8 -785 31 -164 161 -335 310 -408 140 -68 122 -67 854 -67 l660 0 408
								-407 408 -407 2 404 3 405 65 6 c135 12 248 62 347 153 83 76 135 156 171 260
								l27 81 0 725 c0 676 -1 729 -18 785 -51 166 -168 310 -307 377 -148 71 -94 68
								-1312 67 -890 0 -1105 -3 -1157 -14z m1514 -1192 c92 -329 170 -606 173 -615
								4 -17 -8 -18 -149 -18 l-154 0 -41 153 -41 152 -153 0 -154 0 -31 -125 c-17
								-69 -35 -137 -38 -152 l-7 -28 -136 0 c-110 0 -135 3 -131 13 3 8 80 281 172
								608 92 327 169 597 172 602 2 4 82 7 177 7 l172 0 169 -597z"></path>
							<path d="M3643 4427 c-18 -67 -42 -165 -53 -217 -11 -52 -22 -105 -25 -117 -5
								-23 -5 -23 115 -23 66 0 120 4 120 9 0 35 -55 256 -75 301 -13 30 -27 75 -31
								100 -4 25 -9 50 -13 57 -3 6 -21 -43 -38 -110z"></path>
							<path d="M454 3335 c-225 -61 -391 -235 -439 -460 -22 -103 -22 -1397 0 -1499
								47 -224 237 -414 461 -461 38 -8 84 -15 102 -15 l32 0 0 -405 c0 -223 3 -405
								8 -405 4 0 189 182 412 405 l405 405 645 0 c719 0 726 1 860 66 105 52 217
								164 265 265 66 142 65 124 65 850 l0 657 -372 5 c-412 4 -431 6 -576 73 -194
								88 -349 254 -434 463 l-27 66 -678 2 c-548 1 -688 -1 -729 -12z m1225 -590
								c242 -57 381 -278 381 -605 0 -148 -17 -238 -64 -335 -38 -82 -80 -133 -145
								-178 l-45 -31 39 -17 c22 -10 83 -28 135 -41 52 -12 97 -23 99 -25 5 -3 -70
								-203 -76 -203 -21 0 -290 103 -418 160 -22 10 -74 23 -115 29 -210 32 -369
								190 -427 428 -24 102 -22 305 5 406 84 312 342 480 631 412z"></path>
							<path d="M1501 2529 c-75 -23 -123 -80 -162 -190 -20 -55 -23 -85 -23 -209 -1
								-123 2 -156 21 -216 40 -127 114 -194 214 -194 154 1 248 156 249 411 0 176
								-56 322 -144 374 -49 29 -108 38 -155 24z"></path>
						</g>
					</svg>
					<span>FAQs</span>
				</a>
      </li>

	  <li>
				<a href="<?=HOST_HELP_URL?>" target="_blank">
				<svg width="800px" height="800px" viewBox="0 0 1024 1024" class="icon" version="1.1"><path d="M719.8 651.8m-10 0a10 10 0 1 0 20 0 10 10 0 1 0-20 0Z" fill="#E73B37"/><path d="M512.1 64H172v896h680V385.6L512.1 64z m278.8 324.3h-280v-265l280 265zM808 916H216V108h278.6l0.2 0.2v296.2h312.9l0.2 0.2V916z" fill="#39393A"/><path d="M280.5 530h325.9v16H280.5z" fill="#39393A"/><path d="M639.5 530h90.2v16h-90.2z" fill="#E73B37"/><path d="M403.5 641.8h277v16h-277z" fill="#39393A"/><path d="M280.6 641.8h91.2v16h-91.2z" fill="#E73B37"/><path d="M279.9 753.7h326.5v16H279.9z" fill="#39393A"/><path d="M655.8 753.7h73.9v16h-73.9z" fill="#E73B37"/></svg>
					<span>Support Document</span>
				</a>
      </li>

    </ul>
  </li>
</ul>

</li>
<?php } ?>
				<?php if($user_types == "AppSumo" && $newappsumo == 1){?>
				<li class="nav-item  appSumoCode <?php if($codeverifyed==0) { echo "Add Code";}else{ echo "nav__verifieds";} ?> <?php if($codeverifyed==0){ echo 'appSumoCode';} ?> "><?php if($codeverifyed==0) { echo "Add Code";}else{ echo "Add Code";} ?> </li>
				<?php } ?>

			
			<?php if($row['id'] !=1){?>				
				<li>
					<button onclick="addProShort()" class="add__project__short">
					<!-- <svg class="feather feather-plus" fill="none" height="" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><line x1="12" x2="12" y1="5" y2="19"></line><line x1="5" x2="19" y1="12" y2="12"></line></svg> -->
						<span class="text__project">Add Website</span>
					</button>
				</li>

			<?php } ?>
			</ul>
		</div>
		<input hidden="" class="check-icon toggle_sn on_mobile" id="toggle_sn" name="check-icon" type="checkbox">
       <label class="icon-menu" for="toggle_sn">
                <div class="bar_sd bar--1"></div>
                <div class="bar_sd bar--2"></div>
                <div class="bar_sd bar--3"></div>
       </label>

	</div>
</nav>

<script>
        document.addEventListener("DOMContentLoaded", function() {
            var pageHeadingElement = document.querySelector(".container-fluid.content__up>h1");
            var pageHeadingText = pageHeadingElement.textContent;
            var pageHdElement = document.querySelector(".page_hd_top");
            pageHdElement.textContent = pageHeadingText;
        });
    </script>

<script>
	
	jQuery(document).ready(function (e) {
    function t(t) {
        e(t).bind("click", function (t) {
            t.preventDefault();
            e(this).parent().fadeOut()
        })
    }
    e(".dropdown-toggle").click(function () {
        var t = e(this).parents(".button-dropdown").children(".dropdown-menu").is(":hidden");
        e(".button-dropdown .dropdown-menu").hide();
        e(".button-dropdown .dropdown-toggle").removeClass("active");
        if (t) {
            e(this).parents(".button-dropdown").children(".dropdown-menu").toggle().parents(".button-dropdown").children(".dropdown-toggle").addClass("active")
        }
    });
    e(document).bind("click", function (t) {
        var n = e(t.target);
        if (!n.parents().hasClass("button-dropdown")) e(".button-dropdown .dropdown-menu").hide();
    });
    e(document).bind("click", function (t) {
        var n = e(t.target);
        if (!n.parents().hasClass("button-dropdown")) e(".button-dropdown .dropdown-toggle").removeClass("active");
    })
});
</script>
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
xhttp.open("POST", "<?=HOST_HELP_URL?>script/smcx_m.php", true); 

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
			    '<div class="codes_sumo"><input type="text" class="form-control code1" code_type="code1" id="sumo1" value="<?=$Sumocode1?>" <?php if($Sumocode1 != ""){ echo "readonly"; }?> placeholder="Code 1"/> <button class="btn btn-primary code11  <?php if($Sumocode1 == ""){ echo "Verify codeVerify"; }else{ echo "Verified";}?>"><?php if($Sumocode1 == ""){ echo "Verify"; }else{ echo "Verified";}?></button></div>' +
			    '<div class="codes_sumo"><input type="text" class="form-control code2" code_type="code2" id="sumo2" value="<?=$Sumocode2?>" <?php if($Sumocode2 != ""){ echo "readonly"; }?>  placeholder="Code 2"/> <button class="btn code22 btn-primary  <?php if($Sumocode2 == ""){ echo "Verify codeVerify"; }else{ echo "Verified";}?>"><?php if($Sumocode2 == ""){ echo "Verify"; }else{ echo "Verified";}?></button></div>' +
			    '<div class="codes_sumo"><input type="text" class="form-control code3" code_type="code3" id="sumo3" value="<?=$Sumocode3?>" <?php if($Sumocode3 != ""){ echo "readonly"; }?>  placeholder="Code 3"/> <button class="btn code33 btn-primary  <?php if($Sumocode3 == ""){ echo "Verify codeVerify"; }else{ echo "Verified";}?>"><?php if($Sumocode3 == ""){ echo "Verify"; }else{ echo "Verified";}?></button></div>' + 
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


// sidebar slider
$(document).ready(function(){
        $('#toggle_sn').click(function(){
            $('#sidebar-wrapper').toggleClass('sidebar_open');
        });
});

</script>



<?php
//123
// Check the condition in PHP
// if ($row['self_install'] == 'yes' ) {
//     echo '<script>
//         $(document).ready(function() {
//             // Display Swal modal on page load
//             Swal.fire({
//                title: "Thanks for contacting with us",
//                 html: ` <div class="form-group">
// 				<div class="flex-col">

// 					<div class="pp_heading">
// 						<label>Great We will get in touch with you soon, in the mean time you can -</label>
											   
// 					</div>

				 
// 			 </div>

		
		   


// 				<div class="form-group social_s_s">
// 				<a href="https://help.websitespeedy.com/" target="_blank">1. Explore our Knowledge base </a> 
// 				<a href="https://websitespeedy.com/why-website-speed-matters.php" target="_blank">2. Learn Why Speed Matters</a>
// 				3. Follow us on Social
// 				<div class="social__links">
// 				<a href="https://www.facebook.com/websitespeedy" target="_blank"><svg height="30px" width="30px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve"> <path style="fill:#385C8E;" d="M134.941,272.691h56.123v231.051c0,4.562,3.696,8.258,8.258,8.258h95.159  c4.562,0,8.258-3.696,8.258-8.258V273.78h64.519c4.195,0,7.725-3.148,8.204-7.315l9.799-85.061c0.269-2.34-0.472-4.684-2.038-6.44  c-1.567-1.757-3.81-2.763-6.164-2.763h-74.316V118.88c0-16.073,8.654-24.224,25.726-24.224c2.433,0,48.59,0,48.59,0  c4.562,0,8.258-3.698,8.258-8.258V8.319c0-4.562-3.696-8.258-8.258-8.258h-66.965C309.622,0.038,308.573,0,307.027,0  c-11.619,0-52.006,2.281-83.909,31.63c-35.348,32.524-30.434,71.465-29.26,78.217v62.352h-58.918c-4.562,0-8.258,3.696-8.258,8.258  v83.975C126.683,268.993,130.379,272.691,134.941,272.691z"/> </svg></a>
// 				<a href="https://www.instagram.com/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 32 32" fill="none"> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint0_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint1_radial_87_7153)"/> <rect x="2" y="2" width="28" height="28" rx="6" fill="url(#paint2_radial_87_7153)"/> <path d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z" fill="white"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z" fill="white"/> <defs> <radialGradient id="paint0_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)"> <stop stop-color="#B13589"/> <stop offset="0.79309" stop-color="#C62F94"/> <stop offset="1" stop-color="#8A3AC8"/> </radialGradient> <radialGradient id="paint1_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)"> <stop stop-color="#E0E8B7"/> <stop offset="0.444662" stop-color="#FB8A2E"/> <stop offset="0.71474" stop-color="#E2425C"/> <stop offset="1" stop-color="#E2425C" stop-opacity="0"/> </radialGradient> <radialGradient id="paint2_radial_87_7153" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)"> <stop offset="0.156701" stop-color="#406ADC"/> <stop offset="0.467799" stop-color="#6A45BE"/> <stop offset="1" stop-color="#6A45BE" stop-opacity="0"/> </radialGradient> </defs> </svg></a>
// 				<a href="https://www.linkedin.com/company/websitespeedy/" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -2 44 44" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-702.000000, -265.000000)" fill="#007EBB"> <path d="M746,305 L736.2754,305 L736.2754,290.9384 C736.2754,287.257796 734.754233,284.74515 731.409219,284.74515 C728.850659,284.74515 727.427799,286.440738 726.765522,288.074854 C726.517168,288.661395 726.555974,289.478453 726.555974,290.295511 L726.555974,305 L716.921919,305 C716.921919,305 717.046096,280.091247 716.921919,277.827047 L726.555974,277.827047 L726.555974,282.091631 C727.125118,280.226996 730.203669,277.565794 735.116416,277.565794 C741.21143,277.565794 746,281.474355 746,289.890824 L746,305 L746,305 Z M707.17921,274.428187 L707.117121,274.428187 C704.0127,274.428187 702,272.350964 702,269.717936 C702,267.033681 704.072201,265 707.238711,265 C710.402634,265 712.348071,267.028559 712.41016,269.710252 C712.41016,272.34328 710.402634,274.428187 707.17921,274.428187 L707.17921,274.428187 L707.17921,274.428187 Z M703.109831,277.827047 L711.685795,277.827047 L711.685795,305 L703.109831,305 L703.109831,277.827047 L703.109831,277.827047 Z" id="LinkedIn"> </path> </g> </g> </svg></a>
// 				<a href="https://www.youtube.com/channel/UC044W4qzCU9wiF1DJhl3puA" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 -7 48 48" version="1.1"> <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Color-" transform="translate(-200.000000, -368.000000)" fill="#CE1312"> <path d="M219.044,391.269916 L219.0425,377.687742 L232.0115,384.502244 L219.044,391.269916 Z M247.52,375.334163 C247.52,375.334163 247.0505,372.003199 245.612,370.536366 C243.7865,368.610299 241.7405,368.601235 240.803,368.489448 C234.086,368 224.0105,368 224.0105,368 L223.9895,368 C223.9895,368 213.914,368 207.197,368.489448 C206.258,368.601235 204.2135,368.610299 202.3865,370.536366 C200.948,372.003199 200.48,375.334163 200.48,375.334163 C200.48,375.334163 200,379.246723 200,383.157773 L200,386.82561 C200,390.73817 200.48,394.64922 200.48,394.64922 C200.48,394.64922 200.948,397.980184 202.3865,399.447016 C204.2135,401.373084 206.612,401.312658 207.68,401.513574 C211.52,401.885191 224,402 224,402 C224,402 234.086,401.984894 240.803,401.495446 C241.7405,401.382148 243.7865,401.373084 245.612,399.447016 C247.0505,397.980184 247.52,394.64922 247.52,394.64922 C247.52,394.64922 248,390.73817 248,386.82561 L248,383.157773 C248,379.246723 247.52,375.334163 247.52,375.334163 L247.52,375.334163 Z" id="Youtube"> </path> </g> </g> </svg></a>
// 				</div>                           

// 			</div>`,
//                 allowOutsideClick: false,
//                 showConfirmButton: false,
//                 allowEscapeKey: false,
//                 showCloseButton: false,
//                 showCancelButton: false,
//                 allowEnterKey: false,
//                 onOpen: function(modalElement) {
//                     // Disable interaction with overlay
//                     modalElement.style.pointerEvents = "none";
//                 }
//             });
//         });
//         </script>';
//     // Stop further execution of PHP script
//     // die();
// }
?>



