<?php 

ob_start();
ob_clean();

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

// print_r($_SESSION) ;

if ( isset($_POST['save-next']) ) {

	// print_r($_POST) ;

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($platform) || empty($websiteurl) || empty($shopifyurl) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	elseif ( !filter_var($websiteurl, FILTER_VALIDATE_URL) || !filter_var($shopifyurl, FILTER_VALIDATE_URL) ) {
		$_SESSION['error'] = "Invalid urls!" ;
	}
	else {

		$manager_id = $_SESSION['user_id'] ;


		// check already saved data
		$check = getTableData( $conn , " boost_website " , " manager_id = '$manager_id' " ) ;
		if ( count($check) > 0 ) {
			// update
			$id = $check["id"] ;
			$columns = " platform = '$platform' , website_url = '$websiteurl' , shopify_url = '$shopifyurl' " ;

			if ( updateTableData( $conn , " boost_website " , $columns , " id = '$id' " ) ) {
				$_SESSION['success'] = "Speed Boost site updated successfully!" ;
			}
			else {
				$_SESSION['error'] = "Operation failed!" ;
				$_SESSION['error'] = "Error: " . $conn->error;
			}
		}
		else {

			// get previous speed
			$desktop_page_insight = google_page_speed_insight($websiteurl,"desktop") ;
			$desktop_score = 0 ;
			if ( is_array($desktop_page_insight["lighthouseResult"]["categories"]) ) {
				// code...
				$categories = $desktop_page_insight["lighthouseResult"]["categories"] ;

				$desktop_score = $categories["performance"]["score"] ;
				$desktop_score = round( $desktop_score*100 , 2 ) ;
			}

			$mobile_page_insight = google_page_speed_insight($websiteurl,"mobile") ;
			$mobile_score = 0 ;
			if ( is_array($mobile_page_insight["lighthouseResult"]["categories"]) ) {
				// code...
				$categories = $mobile_page_insight["lighthouseResult"]["categories"] ;

				$mobile_score = $categories["performance"]["score"] ;
				$mobile_score = round( $mobile_score*100 , 2 ) ;
			}

			// insert
			$columns = " manager_id , platform , website_url , shopify_url , desktop_speed_old , mobile_speed_old , desktop_speed_new , mobile_speed_new " ;
			$values = " '$manager_id' , '$platform' , '$websiteurl' , '$shopifyurl' , '$desktop_score' , '$mobile_score' , '$desktop_score' , '$mobile_score' " ;

			if ( insertTableData( $conn , " boost_website " , $columns , $values ) ) {
				$_SESSION['success'] = "Speed Boost site updated successfully!" ;
			}
			else {
				$_SESSION['error'] = "Operation failed!" ;
				$_SESSION['error'] = "Error: " . $conn->error;
			}
		}



	}

	header("location: ".HOST_URL."adminpannel/speed-boost.php") ;
	die() ;
}


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}




 


?>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="" />
		<title>Speed Boost</title>
		<!-- Favicon-->
		<link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

		<?php require_once("inc/style-and-script.php"); ?>


		<style>
			.disbale-tab {
			    pointer-events: none;
			    cursor: not-allowed;
			    opacity: 0.5;
			}
		</style>

<style>
.container {
/*	border:1px solid;*/
	padding: 40px;
}

@media (min-width: 420px) and (max-width: 659px) {
  .container {
    grid-template-columns: repeat(2, 160px);
  }
}

@media (min-width: 660px) and (max-width: 899px) {
  .container {
    grid-template-columns: repeat(3, 160px);
  }
}

@media (min-width: 900px) {
  .container {
    grid-template-columns: repeat(3, 160px);
  }
}

.container .box {
  width: 100%;
}


.container .box .chart {
	position: relative;
	width: 100%;
	height: 100%;
	text-align: center;
	font-size: 30px;
	height: 160px;
	color: black;
	padding: 50px;
}

.container .box canvas {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  width: 100%;
}
</style>


	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
			
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid billing_dashS">
					
					
					<h1 class="mt-4">Billing Dashoard</h1>
					<?php
						$data = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " ) ;
						// print_r($row);
						$userID = $row['id']
					?>
					<div class="profile_tabs">
					<?php require_once("inc/alert-status.php") ; ?>

										<a   id="download_csv" href="exportdata_csv.php"  class="download_csv1  btn btn-primary">Download CSV</a>
				
					
					<div class="table_S">
					<table id="table1" class="table">
						<thead>
							<tr>
								<th>User Name</th>
								<th>User Email</th>
								<th>Payment Method</th>
								<th>Paid Amount</th>
								<th>Start Plan Period</th>
								<th>End Plan Period</th>
								<th>Plan Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
		<?php
			$userSubscription = "SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id";
			$user_data = mysqli_query($conn,$userSubscription);
			while ($userData=mysqli_fetch_assoc($user_data))
			{
				$userName = $userData['firstname'].$userData['lastname'];	
				$currentDate = date("Y-m-d");
			  $userSubscriptionId = base64_encode($userData['userSubscriptionId']);
		?>
							<tr>
								<td><?php echo $userName;?></td>
								<td><?php echo $row['email'];?></td>
								<td><?php echo $userData['payment_method'];?></td>
								<td><?php echo $userData['paid_amount']; ?></td>
								<td><?php echo $userData['plan_period_start']; ?></td>
								<td><?php echo $userData['plan_period_end']; ?></td>
								<td>
								<?php
									if ($currentDate >= $userData['plan_period_end']) 
									{	
										$status = "Expired";
										echo $status;
									}
									else
									{
										$status = "Active";
										echo $status;
									}
								?>
								</td>
								<td>
									<button type="button" id="download_pdf" class="download_pdf btn btn-primary" data_id="<?php echo $userSubscriptionId;?>" data-href="https://ecommerceseotools.com/ecommercespeedy/adminpannel/pdf/generatePDF.php?id=<?php echo $userSubscriptionId;?>"  data-download-href="https://ecommerceseotools.com/ecommercespeedy/adminpannel/bill/bill_<?php echo $userSubscriptionId;?>"><i class="fa-regular fa-file-pdf"></i></button>
								</td>
							</tr>
<?php 
			} ?>
						</tbody>
					</table>

		</div>
		</div>
				</div>
			</div>
		</div>

	</body>
	<script>
	// ***Script to add DataTable for show data in table format***
    $(document).ready(function () {
        $('#table1').DataTable();
    });


    // *************pdf download*************
	$(".download_pdf").click(function(){

  	var h = $(this).attr("data-href");

  	var hd = $(this).attr("data-download-href");
  	var di = $(this).attr("data_id");
    $.ajax({
      type: "get",
      url: h,

      success: function(response)
      {	
      	console.log("response="+response);
          setTimeout(function(){
          fetch(hd, {
              method: 'GET'
          }).then(resp => resp.blob())
              .then(blob => {
                  const url = window.URL.createObjectURL(blob);
                  const a = document.createElement('a');
                  a.style.display = 'none';
                  a.href = url;
                  
                  a.download = di+".pdf"; // the filename you want
                  document.body.appendChild(a);
                  a.click();
                  window.URL.revokeObjectURL(url);
          });
          },100);
      }
    });
});
	</script>
</html>
