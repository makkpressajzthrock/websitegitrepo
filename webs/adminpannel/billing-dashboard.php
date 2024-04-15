<?php 

require_once('config.php');
require_once('inc/functions.php') ;
require_once('meta_details.php') ;

 //print_r($_SESSION) ;die();

$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}
// Show Expire message //
	$plan_country = "";
		if($row['country'] != "101"){   // Matching user country to show plan link
		$plan_country = "-us";
	}
	include("error_message_bar_subscription.php");
// End Show Expire message //




$manager_id=$_SESSION['user_id'];

$qur_hide="select * from admin_users where id='$manager_id'";
$sele_qr_hide= mysqli_query($conn,$qur_hide);
$run_qr_hide= mysqli_fetch_array($sele_qr_hide);

			
?>
<?php require_once("inc/style-and-script.php"); ?>
		<style type="text/css">
			#getcsv {
			float: right;
			margin-bottom: 1em;
			}
			.custom-tabel .display{padding-top: 20px;}
			.custom-tabel .display th{min-width: 50px;}
			table.display.dataTable.no-footer {
			width: 1600px !important;
			}
			.menu{
				list-style: none;
				display: flex;
				margin: 5px;
				justify-content: space-around;
			}
			.Payment_method input{
				width: 100%;
				padding: 12px;
				border: 1px solid #ccc;
				border-radius: 3px;
			}
			.Payment_method label{
				margin-bottom: 10px;
				display: block;

			}
			.payment_method_btn_wrap{
				width: 10%;
			}
			.text-h{   
				font-size: 25px;
				text-align: center;
    	}
		.subscribe_cover1 {
    display: inline-table;
    width: calc(33% - 7px);
    border-radius: 8px;
	box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    background: #fff;
	overflow: hidden;
	padding:15px;

}
			 .Polaris-Card__Section ul{
				list-style: none;
/*				text-align: center;*/
				display: flex;
				flex-direction: column;
				margin: 0;
				position: relative;}
    	 .Polaris-Card__Section li{
					margin: 0 0 10px;
					position: relative;
					font-size: 15px;
					font-weight: 500;
					margin: 7px 0;
					color: #1d1d1bc7;
					text-transform: capitalize;}

				
	
	/* The message box is shown when the user clicks on the password field */
#nmessage {
  display:none;
  background: #f1f1f1;
  color: #000;
  position: relative;
  padding: 20px;
  margin-top: 10px;
}

#nmessage p {
  padding: 10px 35px;
  font-size: 18px;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

.valid:before {
  position: relative;
  left: -35px;
  content: "✔";
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}

.invalid:before {
  position: relative;
  left: -35px;
  content: "✖";
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
				<div class="container-fluid billing_dashS content__up">
					
					
					<h1 class="mt-4">Billing Dashboard</h1>
									<?php require_once("inc/alert-status.php") ; ?>

					<?php
						$data = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' order  by id desc " ) ;
						// print_r($row);
						$userID = $row['id']
					?>
					

					<div id="custom_nav">
					<ul class="menu profile_tabs">

						<?php
						if ($run_qr_hide["userstatus"] == "manager") {
							?>
							<li><a href="<?=HOST_URL."adminpannel/my-subscriptions.php" ?>" id="my_plan"><button type="button" data-select="my plan" class=" nav_btn nav_btn7"><svg height="24" id="myPlanSVG" fill="currentColor" version="1.1" viewBox="0 0 24 24" width="24"	 xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg">
											<defs id="defs2">
												<rect height="7.0346723" id="rect2504" width="7.9207187" x="-1.1008456" y="289.81766" />
											</defs>
											<g id="g2206" transform="translate(-0.066406)">
												<path d="m -72.986328,81.382812 v 1.5 c -1.837891,0 -3.675781,0 -5.513672,0 v 19.734378 h 15 v -2.26758 c 0.167079,0.0151 0.329005,0.0469 0.5,0.0469 3.104706,0 5.632815,-2.528102 5.632812,-5.632808 10e-7,-3.104707 -2.528105,-5.63086 -5.632812,-5.63086 -0.170566,0 -0.333281,0.02802 -0.5,0.04297 v -6.292969 c -1.830078,0 -3.660156,0 -5.490234,0 v -1.5 z m 1,1 h 1.996094 v 1.5 H -68 v 1 h -6 v -1 h 2.013672 z m -5.513672,1.5 h 2.5 v 2 h 8 v -2 h 2.5 v 5.472657 c -1.891187,0.527127 -3.39012,2.003573 -3.919922,3.894531 h -3.447266 v 1 h 3.259766 c -0.01546,0.169613 -0.02539,0.340102 -0.02539,0.513672 0,1.204638 0.384044,2.319294 1.03125,3.236328 h -4.265626 v 1 h 5.167969 c 0.62502,0.546512 1.379018,0.937728 2.199219,1.16797 v 1.44922 h -13 z m 14.5,6.25 c 2.564267,0 4.632813,2.066593 4.632812,4.63086 0,2.564266 -2.068546,4.632812 -4.632812,4.632812 -2.564266,0 -4.632812,-2.068546 -4.632812,-4.632812 -10e-7,-2.564267 2.068545,-4.630857 4.632812,-4.63086 z m -0.5,1.5 v 4 h 4 v -1 h -3 v -3 z" id="path2515" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1" transform="translate(80,-80)" />
												<path d="m 5.5,2.8828125 v 1 h 2.234375 v -1 z" id="path2527" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1" />
												<path d="m 4,12.25 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 -1.0442708,0 -2.0885417,0 -3.1328125,0 z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.333333 0,0.666667 0,1 -0.3776042,0 -0.7552083,0 -1.1328125,0 0,-0.333333 0,-0.666667 0,-1 z" id="path2499" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1" />
												<path d="m 4,7.5 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 C 6.0885417,7.5 5.0442708,7.5 4,7.5 Z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.3333333 0,0.6666667 0,1 C 5.7552083,9.5 5.3776042,9.5 5,9.5 5,9.1666667 5,8.8333333 5,8.5 Z" id="path2503" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1" />
												<path d="m 4,17 c 0,1 0,2 0,3 1.0442708,0 2.0885417,0 3.1328125,0 0,-1 0,-2 0,-3 C 6.0885417,17 5.0442708,17 4,17 Z m 1,1 c 0.3776042,0 0.7552083,0 1.1328125,0 0,0.333333 0,0.666667 0,1 C 5.7552083,19 5.3776042,19 5,19 5,18.666667 5,18.333333 5,18 Z" id="path2507" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1" />
												<path d="m 8.1328125,8.5 c 0,0.3333333 0,0.6666667 0,1 1.9999995,0 3.9999995,0 5.9999995,0 0,-0.3333333 0,-0.6666667 0,-1 -2,0 -4,0 -5.9999995,0 z" id="path2535" fill="currentColor" style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:1px;stroke-linecap:square;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#000000;stop-opacity:1" />
											</g>
										</svg><span>My Subscriptions</span></button></a>
							</li>
							<?php
						}
						?>

						<li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=profile" id="profile"><button type="button" data-select="Profile" class=" nav_btn nav_btn1 "><i class="las la-user"></i><Span>My Account</Span></button></a></li>
						<?php

						if ($run_qr_hide["userstatus"] == "manager") {

						?>
							<li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=payment" id="payment_btn"><button type="button" data-select="Payment" class=" nav_btn nav_btn2"><i class="las la-money-bill"></i><span>Payment Method</span></button></a></li>
						<?php
						}
						?>
						<?php
						if ($run_qr_hide["userstatus"] == "manager") {

						?>
							<li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=teams" id="teams"><button type="button" data-select="Teams" class="nav_btn nav_btn3"><i class="las la-users"></i><span>Teams</span></button></a></li>
						<?php
						}
						?>
						<li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=security" id="security"><button type="button" data-select="Security" class="nav_btn nav_btn4"><i class="las la-shield-alt"></i><span>Security</span></button></a></li>
						<?php
						if ($run_qr_hide["userstatus"] == "manager") {

						?>
							<!-- <li><a href="<?=HOST_URL?>adminpannel/manager_settings.php?active=subscriptions" id="subscriptions"><button type="button" data-select="Subscribe" class=" nav_btn nav_btn5"><i class="las la-bell"></i><span>Subscriptions</span></button></a></li>
							 -->
							<li><a href="javascript:void(0)" id="invoices__tab"><button type="button" class="nav_btn nav_btn6 active"><i class="las la-file-alt"></i><span>Invoices</span></button></a></li>
						<?php
						}
						?>

					</ul>
				</div>
				<!-- <div class="profile_tabs"> -->
				<div class="profile_tabs">

						<div class=" tab Invoices"  id="invoices_id">


										<a   id="download_csv" href="exportdata_csv.php"  class="download_csv1  btn btn-primary">Download CSV</a>
				
					
					<div class="table_S">
					<table class="table table-bordered speedy-table">
						<thead>
							<tr>
								<th>S. No</th>
								<th>Payment Method</th>
								<th>Paid Amount</th>
								<th>Start Plan Period</th>
								<th>End Plan Period</th>
								<th>Invoice</th>
							</tr>
						</thead>
						<tbody id="tableBody">
		<?php

			$user_id = $_SESSION['user_id'] ;

			$userSubscription = "SELECT user_subscriptions.id as userSubscriptionId, user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id AND admin_users.id = '$user_id' order by  user_subscriptions.id desc";
			// $userSubscription = "SELECT user_subscriptions.id as userSubscriptionId,user_subscriptions.*, admin_users.id as adminUserId,admin_users.* FROM user_subscriptions INNER JOIN admin_users ON user_subscriptions.user_id = admin_users.id ";


			$user_data = mysqli_query($conn,$userSubscription);
if($user_data->num_rows >0){
			$index = 1 ;
			while ($userData=mysqli_fetch_assoc($user_data))
			{
				$userStripId = $userData['stripe_customer_id'];	
				$payment_method = $userData['payment_method'];	


			$userPayment= "SELECT * from  payment_info where customer_id = '$userStripId' order by  id asc";
			$user_Pay = mysqli_query($conn,$userPayment);
			if($user_Pay->num_rows >0){
						 
						while ($userPay=mysqli_fetch_assoc($user_Pay))
						{ 

                        $vartime2 = strtotime($userPay['created_time']);

						$payd = date("Y-m-d", $vartime2);


						$usersl= "SELECT plan_period_end,id from  user_subscriptions_log where stripe_customer_id = '$userStripId' and log_at like '%".$payd."%'";
						$user_sl = mysqli_query($conn,$usersl);
						$userSl=mysqli_fetch_assoc($user_sl);
						$plan_period_end = $userSl['plan_period_end'];
						$bill_id = $userSl['id'];

							$paida = $userPay['amount']/100;
							$paida = number_format((float)$paida, 2, '.', '');

							?>

							<tr class="list__item">
								<td><?php echo $index;?></td>
								<td><?php echo $payment_method;?></td>
								<td><?php echo $paida." ".strtoupper($userPay['currency']); ?></td>
								<td><?php         $timedy= $userPay['created_time'];
                                           $vartime = strtotime($timedy);

                                             $datetimecon= date("F d, Y H:i", $vartime); echo $datetimecon ; ?></td>
								<td><?php   $timedy2= $plan_period_end;
                                           $vartime2 = strtotime($timedy2);

                                             $datetimecon2= date("F d, Y H:i", $vartime2); echo $datetimecon2 ;  ?></td>
								
								<td>
									<a download href="<?=HOST_URL?>adminpannel/generate-pdf_old.php?id=<?php echo base64_encode($bill_id);?>" class="download_pdf btn btn-primary" target="blank"><i class="lar la-file-alt"></i></a>

								</td>
							</tr>
							
					<?php	 $index++ ;
				}


			}

		else{?>
			<?php if($index < 1){ ?>
				<tr>
											<td colspan="6">
												
												Data not found
												
											</td>

										</tr>

								<?php } 	

							}

		}
	}


?>
						</tbody>
					</table>

					<div class="pagination-container">
						<button class="pagination-button" id="prev-button" aria-label="Previous page" title="Previous page">
						<i class="fas fa-angle-left"></i>
						</button>

						<div id="pagination-numbers">

						</div>

						<button class="pagination-button" id="next-button" aria-label="Next page" title="Next page">
						<i class="fa-solid fa-angle-right"></i>
						</button>
					</div>
		</div>
		</div>
				</div>

				</div>
				</div>
			</div>



<script>

const paginationNumbers = document.getElementById("pagination-numbers");
const paginatedList = document.getElementById("tableBody");
const listItems = paginatedList.querySelectorAll(".list__item");
const nextButton = document.getElementById("next-button");
const prevButton = document.getElementById("prev-button");

const paginationLimit = 10;
const pageCount = Math.ceil(listItems.length / paginationLimit);
let currentPage = 1;

const disableButton = (button) => {
  button.classList.add("disabled");
  button.setAttribute("disabled", true);
};

const enableButton = (button) => {
  button.classList.remove("disabled");
  button.removeAttribute("disabled");
};

const handlePageButtonsStatus = () => {
  if (currentPage === 1) {
    disableButton(prevButton);
  } else {
    enableButton(prevButton);
  }

  if (pageCount === currentPage) {
    disableButton(nextButton);
  } else {
    enableButton(nextButton);
  }
};

const handleActivePageNumber = () => {
  document.querySelectorAll(".pagination-number").forEach((button) => {
    button.classList.remove("active");
    const pageIndex = Number(button.getAttribute("page-index"));
    if (pageIndex == currentPage) {
      button.classList.add("active");
    }
  });
};

const appendPageNumber = (index) => {
  const pageNumber = document.createElement("button");
  pageNumber.className = "pagination-number";
  pageNumber.innerHTML = index;
  pageNumber.setAttribute("page-index", index);
  pageNumber.setAttribute("aria-label", "Page " + index);

  paginationNumbers.appendChild(pageNumber);
};

const getPaginationNumbers = () => {
  for (let i = 1; i <= pageCount; i++) {
    appendPageNumber(i);
  }
};

const setCurrentPage = (pageNum) => {
  currentPage = pageNum;

  handleActivePageNumber();
  handlePageButtonsStatus();
  
  const prevRange = (pageNum - 1) * paginationLimit;
  const currRange = pageNum * paginationLimit;

  listItems.forEach((item, index) => {
    item.classList.add("hidden__data");
    if (index >= prevRange && index < currRange) {
      item.classList.remove("hidden__data");
    }
  });
};

window.addEventListener("load", () => {
  getPaginationNumbers();
  setCurrentPage(1);

  prevButton.addEventListener("click", () => {
    setCurrentPage(currentPage - 1);
  });

  nextButton.addEventListener("click", () => {
    setCurrentPage(currentPage + 1);
  });

  document.querySelectorAll(".pagination-number").forEach((button) => {
    const pageIndex = Number(button.getAttribute("page-index"));

    if (pageIndex) {
      button.addEventListener("click", () => {
        setCurrentPage(pageIndex);
      });
    }
  });
});

		</script>

	</body>

</html>
