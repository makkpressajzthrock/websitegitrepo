<?php

// admin-dashboard.php

require_once('config.php');
require_once('meta_details.php');
require_once('inc/functions.php');

// check sign-up process complete
// checkSignupComplete($conn) ;

$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");
//echo'<pre>';print_r($row) ;die;

if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}

?>
<?php require_once("inc/style-and-script.php"); ?>

<style>
	#countdown {
		width: 100%;
		height: 160px;
		text-align: center;
		background: #222;
		background-image: -webkit-linear-gradient(top, #303, #303, #303, #303);
		background-image: -moz-linear-gradient(top, #303, #303, #303, #303);
		background-image: -ms-linear-gradient(top, #303, #303, #303, #303);
		background-image: -o-linear-gradient(top, #303, #303, #303, #303);
		border: 1px solid #111;
		border-radius: 5px;
		box-shadow: 0px 0px 8px rgb(0 0 0 / 60%);
		margin: auto;
		padding: 30px 0 24px;
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
	}

	#countdown:before {
		content: "";
		width: 8px;
		height: 65px;
		background: #303;
		background-image: -webkit-linear-gradient(top, #303, #303, #303, #303);
		background-image: -moz-linear-gradient(top, #303, #303, #303, #303);
		background-image: -ms-linear-gradient(top, #303, #303, #303, #303);
		background-image: -o-linear-gradient(top, #303, #303, #303, #303);
		border: 1px solid #111;
		border-top-left-radius: 6px;
		border-bottom-left-radius: 6px;
		display: block;
		position: absolute;
		top: 48px;
		left: -10px;
	}

	#countdown:after {
		content: "";
		width: 8px;
		height: 65px;
		background: #303;
		background-image: -webkit-linear-gradient(top, #303, #303, #303, #303);
		background-image: -moz-linear-gradient(top, #303, #303, #303, #303);
		background-image: -ms-linear-gradient(top, #303, #303, #303, #303);
		background-image: -o-linear-gradient(top, #303, #303, #303, #303);
		border: 1px solid #111;
		border-top-right-radius: 6px;
		border-bottom-right-radius: 6px;
		display: block;
		position: absolute;
		top: 48px;
		right: -10px;
	}

	#countdown #tiles {
		position: relative;
		z-index: 1;
	}

	#countdown #tiles>span {
		width: 92px;
		max-width: 92px;
		font: bold 48px 'Droid Sans', Arial, sans-serif;
		text-align: center;
		color: #111;
		background-color: #ddd;
		background-image: -webkit-linear-gradient(top, #bbb, #eee);
		background-image: -moz-linear-gradient(top, #bbb, #eee);
		background-image: -ms-linear-gradient(top, #bbb, #eee);
		background-image: -o-linear-gradient(top, #bbb, #eee);
		border-top: 1px solid #fff;
		border-radius: 3px;
		box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.7);
		margin: 0 7px;
		padding: 18px 0;
		display: inline-block;
		position: relative;
	}

	#countdown #tiles>span:before {
		content: "";
		width: 100%;
		height: 13px;
		background: #111;
		display: block;
		padding: 0 3px;
		position: absolute;
		top: 41%;
		left: -3px;
		z-index: -1;
	}

	#countdown #tiles>span:after {
		content: "";
		width: 100%;
		height: 1px;
		background: #eee;
		border-top: 1px solid #333;
		display: block;
		position: absolute;
		top: 48%;
		left: 0;
	}

	#countdown .labels {
		width: 100%;
		height: 25px;
		text-align: center;
		position: absolute;
		bottom: 8px;
	}

	#countdown .labels li {
		width: 102px;
		font: bold 15px 'Droid Sans', Arial, sans-serif;
		color: #f47321;
		text-shadow: 1px 1px 0px #000;
		text-align: center;
		text-transform: uppercase;
		display: inline-block;
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
			<div class="container-fluid home_admin content__up">
				<h1 class="mt-4">Dashboard</h1>
				<?php require_once("inc/alert-status.php"); ?>
				<?php
				$user_id = $_SESSION["user_id"];
				?>
				<div class="profile_tabs">
					<div class="row">
						<div class="col-md-4">
							<a href="<?= HOST_URL . "adminpannel/managers.php" ?>">
								<div class="card">
									<div class="card-body">
										<?php
										$qry = " select count(*) as total from user_subscriptions where paid_amount != 0 and paid_amount_currency = 'usd' ";
										$connect_qry = mysqli_query($conn, $qry);
										$run_qry = mysqli_fetch_array($connect_qry);

						$managers = getTableData( $conn , " admin_users " , " userstatus NOT LIKE 'admin'" , "order by id desc" , 1 ) ;
 

						$keys = 0;

						foreach ($managers as $key => $value) {

							$full = strtolower($value["firstname"].' '.$value["lastname"]);

							if(!str_contains($full,"makkpress") ){	
								$keys++;

							}
						}									


										?>
										<div class="row">
											<h5> Website Owners </h5>
											<h4><?php echo $keys; ?></h4>

										</div>
										<div class="dash_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

												<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
													<path d="M381 944 c-116 -31 -232 -126 -290 -238 -36 -67 -38 -86 -12 -86 12 0 24 14 35 43 9 23 25 53 35 66 l18 24 54 -21 54 -20 -2 -46 c-3 -39 0 -46 16 -46 15 0 21 10 26 41 5 26 11 39 19 36 7 -3 43 -8 79 -12 65 -7 67 -8 67 -36 0 -22 5 -29 20 -29 15 0 20 7 20 29 0 28 2 29 67 36 36 4 72 9 79 12 8 3 14 -10 19 -36 5 -31 11 -41 26 -41 16 0 19 7 16 46 l-2 46 54 20 54 21 18 -24 c10 -13 26 -43 35 -66 11 -29 23 -43 35 -43 25 0 24 11 -8 78 -33 70 -119 163 -188 202 -100 57 -232 74 -344 44z m95 -222 c-3 -3 -34 -1 -68 4 -59 8 -63 11 -60 33 4 37 61 121 96 142 l31 19 3 -96 c1 -53 0 -99 -2 -102z m131 125 c38 -56 56 -98 46 -108 -4 -4 -36 -11 -70 -15 l-63 -7 0 102 0 102 30 -16 c16 -8 42 -34 57 -58z m-256 12 c-17 -22 -35 -56 -41 -75 -6 -19 -16 -34 -21 -34 -18 0 -89 33 -89 41 0 19 142 108 173 109 4 0 -6 -18 -22 -41z m339 16 c40 -21 110 -74 110 -84 0 -8 -71 -41 -89 -41 -5 0 -15 15 -21 34 -6 19 -25 53 -41 76 -29 40 -30 42 -7 35 13 -4 34 -13 48 -20z" />
													<path d="M62 568 c3 -7 15 -43 27 -80 20 -59 26 -68 46 -68 20 0 27 9 41 55 10 30 21 52 25 49 4 -2 15 -27 24 -54 14 -41 21 -50 40 -50 19 0 26 9 40 53 9 28 20 64 25 80 8 24 6 27 -14 27 -18 0 -24 -9 -35 -50 -7 -28 -16 -48 -21 -45 -4 2 -13 25 -20 50 -11 39 -16 45 -39 45 -23 0 -29 -7 -46 -52 l-19 -53 -15 53 c-13 44 -19 52 -39 52 -15 0 -22 -5 -20 -12z" />
													<path d="M365 568 c44 -143 46 -148 70 -148 20 0 27 9 41 55 10 30 21 55 24 55 3 0 14 -25 24 -55 14 -46 21 -55 41 -55 24 0 26 5 70 148 3 7 -5 12 -18 12 -19 0 -25 -8 -36 -50 -7 -28 -16 -48 -21 -45 -4 2 -13 25 -20 50 -11 39 -16 45 -40 45 -24 0 -29 -6 -40 -45 -7 -25 -16 -48 -20 -50 -5 -3 -14 17 -21 45 -11 42 -17 50 -36 50 -13 0 -21 -5 -18 -12z" />
													<path d="M662 568 c3 -7 15 -43 27 -80 20 -59 26 -68 46 -68 20 0 27 9 41 55 10 30 21 55 24 55 3 0 14 -25 24 -55 14 -46 21 -55 41 -55 20 0 26 9 46 68 12 37 24 73 27 80 3 10 -2 13 -18 10 -18 -2 -27 -15 -41 -53 l-17 -50 -11 28 c-6 15 -14 39 -17 52 -5 19 -13 25 -33 25 -23 0 -29 -7 -46 -52 l-19 -53 -15 53 c-13 44 -19 52 -39 52 -15 0 -22 -5 -20 -12z" />
													<path d="M60 369 c0 -31 61 -132 112 -185 183 -192 473 -192 656 0 51 53 112 154 112 185 0 6 -8 11 -19 11 -12 0 -24 -14 -35 -42 -9 -24 -25 -54 -35 -67 l-18 -24 -54 21 -54 20 2 46 c3 39 0 46 -16 46 -15 0 -21 -10 -26 -41 -5 -26 -11 -39 -19 -36 -7 3 -43 8 -79 12 -65 7 -67 8 -67 36 0 22 -5 29 -20 29 -15 0 -20 -7 -20 -29 0 -28 -2 -29 -67 -36 -36 -4 -72 -9 -79 -12 -8 -3 -14 10 -19 36 -5 31 -11 41 -26 41 -16 0 -19 -7 -16 -46 l2 -46 -54 -20 -54 -21 -18 24 c-10 13 -26 43 -35 67 -11 28 -23 42 -35 42 -11 0 -19 -5 -19 -11z m420 -189 l0 -101 -30 16 c-44 23 -122 148 -103 166 9 8 48 15 101 18 l32 1 0 -100z m173 81 c19 -19 -59 -143 -103 -166 l-30 -16 0 102 0 102 63 -7 c34 -4 66 -11 70 -15z m-343 -45 c6 -19 25 -53 41 -76 29 -40 30 -42 7 -35 -51 16 -158 86 -158 104 0 9 65 40 87 40 6 1 17 -14 23 -33z m450 18 c22 -9 40 -20 40 -25 0 -18 -107 -88 -158 -104 -23 -7 -22 -5 7 35 16 23 35 57 41 76 13 39 16 40 70 18z" />
												</g>
											</svg>
										</div>
									</div>
								</div>
							</a>
						</div>
						<?php
						$year = date("Y");
						$month = date("m");
						$qryy = " select count(*) as id from user_subscription  ";
						$connect_qryy = mysqli_query($conn, $qryy);
						$run_qryy = mysqli_fetch_array($connect_qryy);



						?>
						<div class="col-md-4">
							<a href="<?= HOST_URL . "adminpannel/payments.php" ?>">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5> Subscription </h5>
											<h4><?php echo $run_qry['total']; ?></h4>
										</div>
										<div class="dash_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

												<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
													<path d="M0 620 c0 -313 0 -320 20 -320 20 0 20 7 20 300 l0 300 430 0 c423 0 430 0 430 20 0 20 -7 20 -450 20 l-450 0 0 -320z" />
													<path d="M80 540 l0 -320 260 0 259 0 11 -41 c15 -54 77 -115 132 -130 89 -23 182 12 229 88 30 48 33 141 7 192 -16 30 -18 66 -18 283 l0 248 -440 0 -440 0 0 -320z m840 257 c0 -18 -41 -59 -171 -173 -175 -153 -214 -179 -248 -166 -11 4 -97 75 -192 157 -94 83 -176 152 -180 153 -5 2 -9 15 -9 28 l0 24 400 0 400 0 0 -23z m-682 -174 c53 -47 97 -88 97 -93 0 -9 -198 -170 -209 -170 -3 0 -6 81 -6 181 0 121 3 178 10 174 6 -4 54 -45 108 -92z m682 -62 l0 -159 -31 15 c-44 22 -98 27 -147 13 -55 -14 -117 -75 -132 -129 l-11 -41 -239 0 c-299 0 -296 -4 -114 140 69 55 129 100 133 100 4 0 18 -12 32 -26 13 -15 42 -35 65 -45 66 -30 92 -17 276 146 90 79 164 144 166 144 1 1 2 -71 2 -158z m-24 -196 c113 -86 64 -266 -78 -282 -58 -7 -108 15 -144 63 -24 31 -29 47 -29 94 0 46 5 63 27 92 56 74 152 88 224 33z" />
													<path d="M787 354 c-4 -4 -7 -27 -7 -50 l0 -43 -47 -3 c-35 -2 -48 -7 -48 -18 0 -11 13 -16 47 -18 l47 -3 3 -47 c2 -34 7 -47 18 -47 11 0 16 13 18 47 l3 47 47 3 c34 2 47 7 47 18 0 11 -13 16 -47 18 l-47 3 -3 46 c-3 44 -15 62 -31 47z" />
												</g>
											</svg>
										</div>
									</div>
								</div>
							</a>
						</div>
						<?php
						$qry_month = " select sum(paid_amount) as paid_amount  from user_subscriptions where paid_amount_currency = 'usd' and CONCAT(YEAR(created),MONTH(created)) = CONCAT($year,$month)  ";
						$connect_qrys = mysqli_query($conn, $qry_month);



						?>
						<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<?php
										while ($run_qryy_month = mysqli_fetch_array($connect_qrys)) {

											//echo'<pre>';print_r($run_qryy_month);die;
											if($run_qryy_month['paid_amount']==""){
												$run_qryy_month['paid_amount'] = 0;
											}
										?>
											<h5>Total Amount <span>This Monthly</span> </h5>
											<h4>$ <?php echo $run_qryy_month['paid_amount']; ?></h4>
									</div>
								<?php
										}
								?>
								<div class="dash_icon">
									<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

										<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
											<path d="M386 944 c-171 -41 -308 -192 -338 -371 -39 -232 114 -464 343 -518 103 -24 228 -16 210 14 -7 10 -35 14 -102 16 -70 1 -108 7 -149 23 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 43 17 75 22 150 22 109 0 172 -20 252 -79 106 -78 160 -189 163 -334 2 -67 6 -95 16 -102 18 -11 27 17 28 91 5 303 -276 526 -573 454z" />
											<path d="M480 770 c0 -24 -4 -30 -20 -30 -89 0 -143 -129 -85 -199 24 -28 63 -45 163 -71 55 -14 82 -41 82 -82 0 -30 0 -31 20 -13 50 46 -6 112 -118 140 -92 22 -132 53 -132 102 0 25 8 43 29 64 25 24 37 29 81 29 59 0 96 -28 106 -80 5 -21 12 -30 27 -30 17 0 19 5 14 38 -8 45 -64 102 -102 102 -21 0 -25 5 -25 30 0 23 -4 30 -20 30 -16 0 -20 -7 -20 -30z" />
											<path d="M340 390 c0 -61 62 -130 118 -130 18 0 22 -6 22 -30 0 -23 4 -30 20 -30 16 0 20 7 20 30 0 25 4 30 24 30 19 0 36 16 36 35 0 2 -26 1 -57 -2 -50 -4 -63 -1 -93 19 -24 16 -38 35 -44 61 -11 41 -46 54 -46 17z" />
											<path d="M732 389 c-48 -14 -109 -80 -123 -131 -23 -89 12 -182 88 -229 57 -36 154 -34 210 3 62 41 88 90 88 168 0 77 -26 127 -85 166 -43 29 -125 39 -178 23z m134 -45 c103 -49 125 -175 45 -255 -66 -66 -159 -65 -223 2 -122 128 19 328 178 253z" />
											<path d="M862 265 c-11 -14 -34 -41 -52 -61 l-32 -37 -28 22 c-34 25 -46 26 -53 6 -4 -9 9 -25 35 -45 23 -16 45 -30 49 -30 10 0 129 136 129 148 0 22 -30 20 -48 -3z" />
										</g>
									</svg>
								</div>
								</div>
							</div>
						</div>
						<?php
						$qry_yearly = " select sum(paid_amount) as paid_amount  from user_subscriptions where paid_amount_currency = 'usd' and  CONCAT(YEAR(created)) = CONCAT($year) ";
						$connect_qryss = mysqli_query($conn, $qry_yearly);



						?>
						<?php
						while ($run_qryy_yearly = mysqli_fetch_array($connect_qryss)) {
						?>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5>Total Amount <span>This Yearly</span>
											</h5>
											<h4>$ <?php echo $run_qryy_yearly['paid_amount']; ?></h4>
										</div>
									<?php
								}
									?>
										<div class="dash_icon">
										<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
	<path d="M386 944 c-171 -41 -308 -192 -338 -371 -39 -232 114 -464 343 -518 103 -24 228 -16 210 14 -7 10 -35 14 -102 16 -70 1 -108 7 -149 23 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 43 17 75 22 150 22 109 0 172 -20 252 -79 106 -78 160 -189 163 -334 2 -67 6 -95 16 -102 18 -11 27 17 28 91 5 303 -276 526 -573 454z" />
	<path d="M480 770 c0 -24 -4 -30 -20 -30 -89 0 -143 -129 -85 -199 24 -28 63 -45 163 -71 55 -14 82 -41 82 -82 0 -30 0 -31 20 -13 50 46 -6 112 -118 140 -92 22 -132 53 -132 102 0 25 8 43 29 64 25 24 37 29 81 29 59 0 96 -28 106 -80 5 -21 12 -30 27 -30 17 0 19 5 14 38 -8 45 -64 102 -102 102 -21 0 -25 5 -25 30 0 23 -4 30 -20 30 -16 0 -20 -7 -20 -30z" />
	<path d="M340 390 c0 -61 62 -130 118 -130 18 0 22 -6 22 -30 0 -23 4 -30 20 -30 16 0 20 7 20 30 0 25 4 30 24 30 19 0 36 16 36 35 0 2 -26 1 -57 -2 -50 -4 -63 -1 -93 19 -24 16 -38 35 -44 61 -11 41 -46 54 -46 17z" />
	<path d="M732 389 c-48 -14 -109 -80 -123 -131 -23 -89 12 -182 88 -229 57 -36 154 -34 210 3 62 41 88 90 88 168 0 77 -26 127 -85 166 -43 29 -125 39 -178 23z m134 -45 c103 -49 125 -175 45 -255 -66 -66 -159 -65 -223 2 -122 128 19 328 178 253z" />
	<path d="M862 265 c-11 -14 -34 -41 -52 -61 l-32 -37 -28 22 c-34 25 -46 26 -53 6 -4 -9 9 -25 35 -45 23 -16 45 -30 49 -30 10 0 129 136 129 148 0 22 -30 20 -48 -3z" />
</g>
</svg>
										</div>
									</div>
								</div>
							</div>
							<?php
						$sql_total = " select sum(paid_amount) as paid_amount  from user_subscriptions where paid_amount_currency = 'usd' ";
						$total_data = mysqli_query($conn, $sql_total);



						?>
						<?php
						while ($total__amount = mysqli_fetch_array($total_data)) {
						?>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5>Total Amount <span></span>
											</h5>
											<h4>$ <?php echo $total__amount['paid_amount']; ?></h4>
										</div>
									<?php
								}
									?>
									<div class="dash_icon">
										<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

											<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
												<path d="M387 914 c-4 -4 -7 -77 -7 -163 0 -172 10 -208 66 -265 112 -112 303 -71 359 78 22 58 18 351 -5 351 -13 0 -16 -27 -20 -165 -4 -143 -8 -170 -26 -200 -68 -117 -240 -117 -308 0 -18 30 -22 57 -26 199 -5 153 -11 186 -33 165z" />
												<path d="M467 914 c-4 -4 -7 -34 -7 -66 0 -56 1 -58 21 -48 17 10 20 19 17 62 -3 48 -15 68 -31 52z" />
												<path d="M547 914 c-9 -10 -9 -84 1 -84 21 1 33 20 30 50 -3 32 -17 48 -31 34z" />
												<path d="M627 914 c-14 -14 -7 -72 9 -78 25 -9 25 -8 22 36 -3 39 -16 57 -31 42z" />
												<path d="M707 914 c-4 -4 -7 -29 -7 -55 0 -38 4 -51 20 -59 20 -11 21 -9 18 51 -3 57 -14 80 -31 63z" />
												<path d="M580 762 c0 -9 -10 -22 -22 -27 -17 -7 -24 -20 -26 -46 -3 -41 7 -53 64 -71 28 -9 39 -19 39 -33 0 -30 -64 -35 -72 -6 -6 23 -43 30 -43 8 0 -20 30 -57 46 -57 8 0 14 -7 14 -15 0 -8 9 -15 20 -15 11 0 20 7 20 15 0 8 6 15 13 15 20 0 47 33 47 58 0 28 -24 50 -70 62 -44 12 -53 21 -44 44 8 20 60 21 68 1 7 -19 36 -20 36 -1 0 16 -26 46 -41 46 -5 0 -9 9 -9 20 0 13 -7 20 -20 20 -12 0 -20 -7 -20 -18z" />
												<path d="M744 407 l-120 -74 -139 61 c-176 76 -224 80 -353 27 -86 -35 -101 -46 -84 -63 7 -7 39 1 103 27 120 49 150 46 329 -32 109 -48 136 -63 138 -81 5 -36 -20 -36 -134 -2 -76 22 -110 28 -117 21 -7 -7 -8 -14 -1 -21 17 -17 217 -71 241 -65 29 7 57 49 50 76 -4 16 7 28 65 65 38 25 92 57 118 72 46 25 50 26 66 10 9 -9 14 -23 11 -31 -9 -25 -347 -277 -371 -277 -12 0 -84 22 -161 50 -77 27 -153 50 -170 50 -63 -1 -193 -85 -168 -110 9 -10 25 -3 73 29 35 23 74 41 90 41 15 0 92 -23 171 -51 106 -37 153 -49 178 -45 23 3 89 47 208 138 96 72 179 140 184 149 15 30 10 59 -16 84 -13 14 -35 25 -48 25 -12 0 -77 -33 -143 -73z" />
											</g>
										</svg>
									</div>
									</div>
								</div>
							</div>
							<?php
							$qryg = " select count(*) as status from user_subscriptions WHERE user_id != 130 and is_active = 1 ";
							$connect_qr_g = mysqli_query($conn, $qryg);
							$run_qr_g = mysqli_fetch_array($connect_qr_g);



							?>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5> Active Plans</h5>
											<h4><?php echo $run_qr_g['status']; ?></h4>
										</div>
										<div class="dash_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

												<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
													<path d="M386 945 c-226 -57 -376 -288 -338 -517 50 -299 372 -470 647 -344 68 31 165 122 205 191 63 110 76 270 30 385 -27 69 -39 84 -55 70 -10 -8 -7 -24 13 -73 36 -84 37 -224 4 -307 -45 -112 -130 -197 -242 -242 -43 -17 -75 -22 -150 -22 -75 0 -107 5 -150 22 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 41 16 77 22 145 22 105 1 167 -17 241 -69 47 -34 74 -34 61 -1 -8 20 -79 65 -141 88 -67 26 -195 32 -270 13z" />
													<path d="M670 615 l-191 -225 -82 74 c-66 61 -84 73 -95 63 -12 -10 1 -26 77 -99 50 -49 96 -88 101 -88 14 0 411 472 407 484 -2 5 -8 11 -14 13 -6 2 -97 -98 -203 -222z" />
												</g>
											</svg>
											</svg>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
<?php
							$qryg = " select count(*) as count, sum(paid_amount) as amount from user_subscriptions WHERE user_id != 130 and is_active = 0";
							$connect_qr_g = mysqli_query($conn, $qryg);
							$run_qr_g = mysqli_fetch_array($connect_qr_g);

?>


											<h5> Inactive Plans</h5>
											<h4><?php echo $run_qr_g['count']; ?></h4>
										</div>
										<div class="dash_icon">
										<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
<path d="M167 953 c-4 -3 -7 -161 -7 -350 0 -330 -1 -344 -20 -363 -11 -11 -29 -20 -40 -20 -11 0 -29 9 -40 20 -19 19 -20 33 -20 250 l0 230 40 0 c34 0 40 3 40 20 0 19 -5 21 -57 18 l-58 -3 -3 -245 c-1 -160 1 -254 9 -271 22 -54 52 -60 289 -57 183 3 215 5 215 18 0 13 -27 16 -167 20 -146 4 -166 7 -158 20 6 9 10 155 10 348 l0 332 340 0 340 0 2 -217 c3 -186 5 -218 18 -218 13 0 15 33 15 235 l0 235 -371 3 c-204 1 -373 -1 -377 -5z"/>
<path d="M320 780 c0 -19 7 -20 220 -20 213 0 220 1 220 20 0 19 -7 20 -220 20 -213 0 -220 -1 -220 -20z"/>
<path d="M320 700 c0 -19 7 -20 220 -20 213 0 220 1 220 20 0 19 -7 20 -220 20 -213 0 -220 -1 -220 -20z"/>
<path d="M320 540 c0 -19 7 -20 100 -20 93 0 100 1 100 20 0 19 -7 20 -100 20 -93 0 -100 -1 -100 -20z"/>
<path d="M560 540 c0 -19 7 -20 100 -20 93 0 100 1 100 20 0 19 -7 20 -100 20 -93 0 -100 -1 -100 -20z"/>
<path d="M320 460 c0 -19 7 -20 100 -20 93 0 100 1 100 20 0 19 -7 20 -100 20 -93 0 -100 -1 -100 -20z"/>
<path d="M560 460 c0 -26 36 -26 70 0 l25 20 -47 0 c-41 0 -48 -3 -48 -20z"/>
<path d="M679 433 c-92 -47 -137 -150 -109 -252 22 -84 123 -161 210 -161 87 0 188 77 210 161 28 101 -17 203 -109 251 -71 37 -129 37 -202 1z m176 -30 l36 -17 -123 -123 c-67 -68 -126 -123 -131 -123 -13 0 -37 66 -37 102 0 39 29 106 56 130 56 49 135 62 199 31z m89 -93 c22 -54 20 -95 -5 -148 -31 -63 -86 -97 -159 -97 -38 0 -66 6 -84 19 l-29 18 124 124 c68 68 127 124 130 124 4 0 14 -18 23 -40z"/>
<path d="M320 380 c0 -19 7 -20 100 -20 93 0 100 1 100 20 0 19 -7 20 -100 20 -93 0 -100 -1 -100 -20z"/>
<path d="M320 300 c0 -19 7 -20 100 -20 93 0 100 1 100 20 0 19 -7 20 -100 20 -93 0 -100 -1 -100 -20z"/>
</g>
</svg>
										</div>
									</div>
								</div>
							</div>



							<?php
							//select count(*) as id from user_subscription


							$qry_inst = " select count(*) as script_installed  from check_installation where script_installed='0' ";
							$connect_inst = mysqli_query($conn, $qry_inst);
							$run_qr_inst = mysqli_fetch_array($connect_inst);

							$qryscr = " select count(*) as script_installed from check_installation ";
							$connect_qr_scr = mysqli_query($conn, $qryscr);
							$run_qr_scr = mysqli_fetch_array($connect_qr_scr);



							?>
							<div class="col-md-4">

								<a href="<?= HOST_URL . "adminpannel/script_installation_payment.php" ?>">
									<div class="card">

										<div class="card-body">
											<div class="row">
												<h5> Script instalation request</h5>


												<h4> <?php echo $run_qr_inst['script_installed']; ?> / <?php echo $run_qr_scr['script_installed']; ?>
													<!-- <h1></h1>
					         
							 <h5>  Script installed</h5>
					     
					          </div>
							  <div class="col-md-6 "> -->

											</div>
											<div class="dash_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
<path d="M140 500 l0 -460 360 0 360 0 0 333 0 332 -128 128 -127 127 -233 0 -232 0 0 -460z m440 300 l0 -120 120 0 120 0 0 -300 0 -300 -320 0 -320 0 0 420 0 420 200 0 200 0 0 -120z m130 5 l85 -85 -88 0 -87 0 0 85 c0 47 1 85 3 85 1 0 40 -38 87 -85z"/>
<path d="M511 523 c-5 -21 -22 -85 -36 -143 -14 -58 -28 -113 -31 -122 -4 -13 0 -18 15 -18 22 0 20 -5 66 180 14 58 28 113 31 123 4 12 0 17 -15 17 -15 0 -23 -10 -30 -37z"/>
<path d="M330 455 l-31 -55 31 -55 c21 -37 36 -52 45 -48 20 7 19 17 -7 64 l-23 39 23 39 c26 47 27 57 7 64 -9 4 -24 -11 -45 -48z"/>
<path d="M620 500 c-13 -8 -12 -16 10 -55 l25 -46 -22 -39 c-27 -46 -28 -56 -8 -63 9 -4 24 11 45 48 l31 55 -31 55 c-17 30 -32 55 -33 55 -1 0 -9 -5 -17 -10z"/>
</g>
</svg>
											</div>
										</div>

									</div>
								</a>
							</div>


							<div class="col-md-4">

								<a href="javascript:void(0);">
									<div class="card">

										<div class="card-body">
											<div class="row">
												<h5>Monthly Subscriptions</h5>

											<?php
							$qryg = " select count(*) as count, sum(paid_amount) as amount from user_subscriptions WHERE paid_amount_currency = 'usd' and user_id != 130 and is_active = 1 and plan_interval='month' ";
							$connect_qr_g = mysqli_query($conn, $qryg);
							$run_qr_g = mysqli_fetch_array($connect_qr_g);

  
											?>	
  
												<h4> <?php echo $run_qr_g['count'];?></h4>
	 
											</div>
											<div class="dash_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

												<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
												<path d="M140 500 l0 -460 360 0 360 0 0 333 0 332 -128 128 -127 127 -233 0 -232 0 0 -460z m440 300 l0 -120 120 0 120 0 0 -300 0 -300 -320 0 -320 0 0 420 0 420 200 0 200 0 0 -120z m130 5 l85 -85 -88 0 -87 0 0 85 c0 47 1 85 3 85 1 0 40 -38 87 -85z"/>
												<path d="M511 523 c-5 -21 -22 -85 -36 -143 -14 -58 -28 -113 -31 -122 -4 -13 0 -18 15 -18 22 0 20 -5 66 180 14 58 28 113 31 123 4 12 0 17 -15 17 -15 0 -23 -10 -30 -37z"/>
												<path d="M330 455 l-31 -55 31 -55 c21 -37 36 -52 45 -48 20 7 19 17 -7 64 l-23 39 23 39 c26 47 27 57 7 64 -9 4 -24 -11 -45 -48z"/>
												<path d="M620 500 c-13 -8 -12 -16 10 -55 l25 -46 -22 -39 c-27 -46 -28 -56 -8 -63 9 -4 24 11 45 48 l31 55 -31 55 c-17 30 -32 55 -33 55 -1 0 -9 -5 -17 -10z"/>
												</g>
												</svg>
											</div>
										</div>

									</div>
								</a>
							</div>



							<div class="col-md-4">

								<a href="javascript:void(0);">
									<div class="card">

										<div class="card-body">
											<div class="row">
												<h5>Monthly Subscription Amount</h5>


												<h4>$ <?php echo $run_qr_g['amount'];?></h4>
	 
											</div>
											<div class="dash_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

												<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
												<path d="M140 500 l0 -460 360 0 360 0 0 333 0 332 -128 128 -127 127 -233 0 -232 0 0 -460z m440 300 l0 -120 120 0 120 0 0 -300 0 -300 -320 0 -320 0 0 420 0 420 200 0 200 0 0 -120z m130 5 l85 -85 -88 0 -87 0 0 85 c0 47 1 85 3 85 1 0 40 -38 87 -85z"/>
												<path d="M511 523 c-5 -21 -22 -85 -36 -143 -14 -58 -28 -113 -31 -122 -4 -13 0 -18 15 -18 22 0 20 -5 66 180 14 58 28 113 31 123 4 12 0 17 -15 17 -15 0 -23 -10 -30 -37z"/>
												<path d="M330 455 l-31 -55 31 -55 c21 -37 36 -52 45 -48 20 7 19 17 -7 64 l-23 39 23 39 c26 47 27 57 7 64 -9 4 -24 -11 -45 -48z"/>
												<path d="M620 500 c-13 -8 -12 -16 10 -55 l25 -46 -22 -39 c-27 -46 -28 -56 -8 -63 9 -4 24 11 45 48 l31 55 -31 55 c-17 30 -32 55 -33 55 -1 0 -9 -5 -17 -10z"/>
												</g>
												</svg>
											</div>
										</div>

									</div>
								</a>
							</div>


							<div class="col-md-4">

								<a href="javascript:void(0);">
									<div class="card">

										<div class="card-body">
											<div class="row">
												<h5>Yearly Subscriptions</h5>

											<?php
							$qrygy = " select count(*) as count, sum(paid_amount) as amount from user_subscriptions WHERE paid_amount_currency = 'usd' and user_id != 130 and is_active = 1 and plan_interval='year' ";
							$connect_qr_gy = mysqli_query($conn, $qrygy);
							$run_qr_gy = mysqli_fetch_array($connect_qr_gy);

  
											?>	
  
												<h4> <?php echo $run_qr_gy['count'];?></h4>

												  
	 
											</div>
											<div class="dash_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

												<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
												<path d="M140 500 l0 -460 360 0 360 0 0 333 0 332 -128 128 -127 127 -233 0 -232 0 0 -460z m440 300 l0 -120 120 0 120 0 0 -300 0 -300 -320 0 -320 0 0 420 0 420 200 0 200 0 0 -120z m130 5 l85 -85 -88 0 -87 0 0 85 c0 47 1 85 3 85 1 0 40 -38 87 -85z"/>
												<path d="M511 523 c-5 -21 -22 -85 -36 -143 -14 -58 -28 -113 -31 -122 -4 -13 0 -18 15 -18 22 0 20 -5 66 180 14 58 28 113 31 123 4 12 0 17 -15 17 -15 0 -23 -10 -30 -37z"/>
												<path d="M330 455 l-31 -55 31 -55 c21 -37 36 -52 45 -48 20 7 19 17 -7 64 l-23 39 23 39 c26 47 27 57 7 64 -9 4 -24 -11 -45 -48z"/>
												<path d="M620 500 c-13 -8 -12 -16 10 -55 l25 -46 -22 -39 c-27 -46 -28 -56 -8 -63 9 -4 24 11 45 48 l31 55 -31 55 c-17 30 -32 55 -33 55 -1 0 -9 -5 -17 -10z"/>
												</g>
												</svg>
											</div>
										</div>

									</div>
								</a>
							</div>

							<div class="col-md-4">

								<a href="javascript:void(0);">
									<div class="card">

										<div class="card-body">
											<div class="row">
												<h5>Yearly Subscription Amount</h5>


												<h4>$ <?php echo $run_qr_gy['amount'];?></h4>
	 
											</div>
											<div class="dash_icon">
											<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

												<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
												<path d="M140 500 l0 -460 360 0 360 0 0 333 0 332 -128 128 -127 127 -233 0 -232 0 0 -460z m440 300 l0 -120 120 0 120 0 0 -300 0 -300 -320 0 -320 0 0 420 0 420 200 0 200 0 0 -120z m130 5 l85 -85 -88 0 -87 0 0 85 c0 47 1 85 3 85 1 0 40 -38 87 -85z"/>
												<path d="M511 523 c-5 -21 -22 -85 -36 -143 -14 -58 -28 -113 -31 -122 -4 -13 0 -18 15 -18 22 0 20 -5 66 180 14 58 28 113 31 123 4 12 0 17 -15 17 -15 0 -23 -10 -30 -37z"/>
												<path d="M330 455 l-31 -55 31 -55 c21 -37 36 -52 45 -48 20 7 19 17 -7 64 l-23 39 23 39 c26 47 27 57 7 64 -9 4 -24 -11 -45 -48z"/>
												<path d="M620 500 c-13 -8 -12 -16 10 -55 l25 -46 -22 -39 c-27 -46 -28 -56 -8 -63 9 -4 24 11 45 48 l31 55 -31 55 c-17 30 -32 55 -33 55 -1 0 -9 -5 -17 -10z"/>
												</g>
												</svg>
											</div>
										</div>

									</div>
								</a>
							</div>






							<?php


							$qry_open = " select count(*) as resolved  from `support_tickets` where resolved='0' ";
							$connect_open = mysqli_query($conn, $qry_open);
							$run_qr_open = mysqli_fetch_array($connect_open);

							$qrytict = " select count(*) as resolved from support_tickets ";
							$connect_qrytict = mysqli_query($conn, $qrytict);
							$run_connect_qrytict = mysqli_fetch_array($connect_qrytict);



							?>
<!-- 
							<div class="col-md-4">

								<a href="<?= HOST_URL . "adminpannel/tickets.php" ?>">
									<div class="card">

										<div class="card-body">
											<div class="row">
												<h5> Total Tickets</h5>


												<h4> <?php echo $run_qr_open['resolved']; ?> / <?php echo $run_connect_qrytict['resolved']; ?> -->
													<!-- <h1></h1>
					         
							 <h5>  Script installed</h5>
					     
					          </div>
							  <div class="col-md-6 "> -->

											<!-- </div>
											<div class="dash_icon">
												<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

													<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
														<path d="M202 858 c-7 -7 -12 -39 -12 -75 l0 -63 -79 0 c-104 0 -113 -8 -109 -104 3 -68 4 -71 33 -85 45 -20 63 -40 70 -73 9 -39 -10 -82 -43 -99 -56 -28 -62 -38 -62 -109 0 -38 5 -71 12 -78 17 -17 769 -17 786 0 7 7 12 39 12 75 l0 63 78 0 c103 0 114 11 110 106 l-3 69 -40 21 c-86 44 -86 129 1 178 39 21 39 22 42 90 2 41 -2 73 -9 82 -16 19 -768 21 -787 2z m768 -76 c0 -57 -1 -59 -35 -75 -50 -24 -77 -71 -72 -125 5 -49 43 -96 84 -106 23 -6 24 -10 21 -69 l-3 -62 -370 0 -370 0 -3 60 c-3 61 -3 61 32 78 46 21 71 61 71 112 0 51 -26 92 -71 111 -33 14 -34 15 -34 74 l0 60 375 0 375 0 0 -58z m-710 -117 c56 -50 45 -120 -23 -157 l-42 -23 -3 -75 c-2 -62 0 -77 14 -87 14 -10 87 -13 296 -13 l278 0 0 -60 0 -60 -375 0 -375 0 0 60 c0 59 1 60 35 74 100 42 96 178 -7 232 -25 13 -28 20 -28 68 0 29 3 56 8 60 4 4 50 6 103 4 80 -2 101 -7 119 -23z" />
														<path d="M380 795 c0 -12 7 -15 25 -13 33 4 33 22 0 26 -18 2 -25 -1 -25 -13z" />
														<path d="M470 595 c0 -184 2 -215 15 -215 13 0 15 31 15 215 0 184 -2 215 -15 215 -13 0 -15 -31 -15 -215z" />
														<path d="M380 714 c0 -11 8 -14 27 -12 39 4 42 28 4 28 -21 0 -31 -5 -31 -16z" />
														<path d="M385 650 c-4 -7 -3 -16 3 -22 13 -13 54 0 49 15 -5 16 -43 21 -52 7z" />
														<path d="M564 649 c-12 -20 20 -29 106 -29 86 0 118 9 106 29 -5 7 -44 11 -106 11 -62 0 -101 -4 -106 -11z" />
														<path d="M380 565 c0 -12 7 -15 25 -13 33 4 33 22 0 26 -18 2 -25 -1 -25 -13z" />
														<path d="M565 550 c-3 -5 0 -13 8 -16 23 -8 82 0 82 11 0 14 -82 19 -90 5z" />
														<path d="M690 544 c0 -11 8 -14 27 -12 39 4 42 28 4 28 -21 0 -31 -5 -31 -16z" />
														<path d="M380 485 c0 -12 7 -15 25 -13 33 4 33 22 0 26 -18 2 -25 -1 -25 -13z" />
														<path d="M380 405 c0 -12 7 -15 25 -13 33 4 33 22 0 26 -18 2 -25 -1 -25 -13z" />
													</g>
												</svg>
											</div>
										</div>

									</div>
								</a>
							</div> -->




<!-- 
							<?php


							$qry_expert = " select count(*) as id  from `expert_reply`  ";
							$connect_qry_expert = mysqli_query($conn, $qry_expert);
							$run_qry_expert = mysqli_fetch_array($connect_qry_expert);

							$qry_expert_solve = " select count(*) as close_queries  from `expert_reply` where close_queries=''  ";
							$connect_qry_expert_solve = mysqli_query($conn, $qry_expert_solve);
							$run_qry_expert_solve = mysqli_fetch_array($connect_qry_expert_solve);

							?> -->

							<!-- <div class="col-md-4">

								<a href="<?= HOST_URL . "adminpannel/expert-queries.php" ?>">
									<div class="card">

										<div class="card-body">
											<div class="row">
												<h5> Total Expert Queries</h5>


												<h4><?php echo $run_qry_expert_solve['close_queries']; ?>/<?php echo $run_qry_expert['id']; ?> </h4>
											 -->	<!-- <h1></h1>
					         
							 <h5>  Script installed</h5>
					     
					          </div>
							  <div class="col-md-6 "> -->

								<!-- 			</div>
											<div class="dash_icon">
												<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

													<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
														<path d="M530 915 c-68 -19 -150 -74 -184 -123 -32 -48 -56 -105 -56 -137 0 -25 -1 -25 -81 -25 -93 0 -136 -16 -162 -59 -14 -24 -17 -55 -17 -170 l0 -140 30 -34 c26 -30 30 -43 30 -90 0 -31 4 -58 8 -61 5 -3 45 19 90 49 l81 55 130 0 c139 0 167 6 200 41 41 44 17 54 -31 14 -28 -24 -35 -25 -166 -25 l-137 0 -67 -45 c-37 -25 -70 -45 -73 -45 -3 0 -5 20 -5 44 0 36 -6 50 -30 74 l-30 30 0 137 0 137 29 29 c28 27 34 29 114 29 l84 0 7 -37 c20 -121 152 -234 286 -245 67 -5 61 18 -9 30 -153 27 -252 133 -252 271 -1 193 204 325 408 263 204 -62 279 -285 145 -434 -34 -37 -34 -39 -25 -89 8 -36 7 -49 -1 -46 -86 35 -114 43 -135 38 -39 -10 -39 -32 1 -30 18 1 64 -11 101 -26 37 -15 71 -25 74 -22 3 4 0 34 -6 67 l-12 61 36 41 c38 45 65 119 65 178 0 200 -229 353 -440 295z" />
														<path d="M716 719 c-15 -12 -26 -30 -26 -45 0 -32 16 -31 32 4 17 34 54 36 75 5 14 -21 13 -25 -16 -52 -19 -18 -31 -39 -31 -55 0 -32 16 -34 26 -4 4 12 17 31 30 41 34 26 33 79 -2 106 -15 12 -34 21 -44 21 -10 0 -29 -9 -44 -21z" />
														<path d="M425 690 c-3 -5 0 -13 8 -15 24 -10 202 -1 202 10 0 14 -201 19 -210 5z" />
														<path d="M425 620 c-3 -5 0 -13 8 -15 24 -10 202 -1 202 10 0 14 -201 19 -210 5z" />
														<path d="M420 541 c0 -5 7 -12 16 -15 20 -8 168 -8 188 0 40 15 7 24 -94 24 -60 0 -110 -4 -110 -9z" />
														<path d="M183 515 c-3 -10 -17 -28 -29 -41 -13 -12 -24 -29 -24 -36 0 -25 30 -56 60 -63 23 -5 30 -12 30 -30 0 -29 -22 -38 -47 -20 -24 19 -33 18 -33 0 0 -8 9 -20 20 -27 11 -7 20 -18 20 -25 0 -21 20 -25 27 -6 4 10 16 25 26 34 22 17 26 61 8 83 -6 7 -25 16 -41 20 -23 4 -30 11 -30 30 0 29 24 41 44 21 8 -8 19 -15 25 -15 18 0 12 29 -9 42 -11 7 -20 18 -20 25 0 20 -20 25 -27 8z" />
														<path d="M750 505 c0 -8 5 -15 10 -15 6 0 10 7 10 15 0 8 -4 15 -10 15 -5 0 -10 -7 -10 -15z" />
													</g>
												</svg>
											</div>
										</div>

									</div>
								</a>
							</div> -->




<!-- New Code For Indian payment -->

<hr>



						<!-- Add coloum for indian subscription -->


							<?php
						$year = date("Y");
						$month = date("m");
						$select_indian = " select count(*) as id from user_subscriptions WHERE stripe_subscription_id <> 'xxxxxxxxxxxx' AND paid_amount_currency='inr' ";
						$connect_qryy1 = mysqli_query($conn, $select_indian);
						$run_qryy1 = mysqli_fetch_array($connect_qryy1);



						?>

						<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="row">
									
											<h5>Total Indian Subscription </h5>
											<h4><?php echo $run_qryy1['id']; ?></h4>
									</div>
								
								
								<div class="dash_icon">
									<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

										<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
											<path d="M386 944 c-171 -41 -308 -192 -338 -371 -39 -232 114 -464 343 -518 103 -24 228 -16 210 14 -7 10 -35 14 -102 16 -70 1 -108 7 -149 23 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 43 17 75 22 150 22 109 0 172 -20 252 -79 106 -78 160 -189 163 -334 2 -67 6 -95 16 -102 18 -11 27 17 28 91 5 303 -276 526 -573 454z" />
											<path d="M480 770 c0 -24 -4 -30 -20 -30 -89 0 -143 -129 -85 -199 24 -28 63 -45 163 -71 55 -14 82 -41 82 -82 0 -30 0 -31 20 -13 50 46 -6 112 -118 140 -92 22 -132 53 -132 102 0 25 8 43 29 64 25 24 37 29 81 29 59 0 96 -28 106 -80 5 -21 12 -30 27 -30 17 0 19 5 14 38 -8 45 -64 102 -102 102 -21 0 -25 5 -25 30 0 23 -4 30 -20 30 -16 0 -20 -7 -20 -30z" />
											<path d="M340 390 c0 -61 62 -130 118 -130 18 0 22 -6 22 -30 0 -23 4 -30 20 -30 16 0 20 7 20 30 0 25 4 30 24 30 19 0 36 16 36 35 0 2 -26 1 -57 -2 -50 -4 -63 -1 -93 19 -24 16 -38 35 -44 61 -11 41 -46 54 -46 17z" />
											<path d="M732 389 c-48 -14 -109 -80 -123 -131 -23 -89 12 -182 88 -229 57 -36 154 -34 210 3 62 41 88 90 88 168 0 77 -26 127 -85 166 -43 29 -125 39 -178 23z m134 -45 c103 -49 125 -175 45 -255 -66 -66 -159 -65 -223 2 -122 128 19 328 178 253z" />
											<path d="M862 265 c-11 -14 -34 -41 -52 -61 l-32 -37 -28 22 c-34 25 -46 26 -53 6 -4 -9 9 -25 35 -45 23 -16 45 -30 49 -30 10 0 129 136 129 148 0 22 -30 20 -48 -3z" />
										</g>
									</svg>
								</div>
								</div>
							</div>
						</div>


							<!-- Add Coloumn for Indian Data  -->

							<?php
								$qry_month = "SELECT SUM(paid_amount) AS paid_amount 
					              FROM user_subscriptions 
					              WHERE CONCAT(YEAR(created), MONTH(created)) = CONCAT($year, $month)
					              AND paid_amount_currency = 'inr'
					                AND stripe_subscription_id <> 'xxxxxxxxxxxx'";



								$connect_qrys = mysqli_query($conn, $qry_month);



						?>


							<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<?php
										while ($run_qryy_month = mysqli_fetch_array($connect_qrys)) {

											//echo'<pre>';print_r($run_qryy_month);die;
										?>
											<h5>Total Amount <span>This Monthly</span> </h5>
											<h4> <span>&#8377;</span> <?php echo $run_qryy_month['paid_amount']; ?></h4>
									</div>
								<?php
										}
								?>
								<div class="dash_icon">
									<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

										<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
											<path d="M386 944 c-171 -41 -308 -192 -338 -371 -39 -232 114 -464 343 -518 103 -24 228 -16 210 14 -7 10 -35 14 -102 16 -70 1 -108 7 -149 23 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 43 17 75 22 150 22 109 0 172 -20 252 -79 106 -78 160 -189 163 -334 2 -67 6 -95 16 -102 18 -11 27 17 28 91 5 303 -276 526 -573 454z" />
											<path d="M480 770 c0 -24 -4 -30 -20 -30 -89 0 -143 -129 -85 -199 24 -28 63 -45 163 -71 55 -14 82 -41 82 -82 0 -30 0 -31 20 -13 50 46 -6 112 -118 140 -92 22 -132 53 -132 102 0 25 8 43 29 64 25 24 37 29 81 29 59 0 96 -28 106 -80 5 -21 12 -30 27 -30 17 0 19 5 14 38 -8 45 -64 102 -102 102 -21 0 -25 5 -25 30 0 23 -4 30 -20 30 -16 0 -20 -7 -20 -30z" />
											<path d="M340 390 c0 -61 62 -130 118 -130 18 0 22 -6 22 -30 0 -23 4 -30 20 -30 16 0 20 7 20 30 0 25 4 30 24 30 19 0 36 16 36 35 0 2 -26 1 -57 -2 -50 -4 -63 -1 -93 19 -24 16 -38 35 -44 61 -11 41 -46 54 -46 17z" />
											<path d="M732 389 c-48 -14 -109 -80 -123 -131 -23 -89 12 -182 88 -229 57 -36 154 -34 210 3 62 41 88 90 88 168 0 77 -26 127 -85 166 -43 29 -125 39 -178 23z m134 -45 c103 -49 125 -175 45 -255 -66 -66 -159 -65 -223 2 -122 128 19 328 178 253z" />
											<path d="M862 265 c-11 -14 -34 -41 -52 -61 l-32 -37 -28 22 c-34 25 -46 26 -53 6 -4 -9 9 -25 35 -45 23 -16 45 -30 49 -30 10 0 129 136 129 148 0 22 -30 20 -48 -3z" />
										</g>
									</svg>
								</div>
								</div>
							</div>
						</div>





						<!-- Total Amount This Yearly In INR  -->

							<?php
						$qry_yearly_indian = " select sum(paid_amount) as paid_amount  from user_subscriptions where CONCAT(YEAR(created)) = CONCAT($year) AND paid_amount_currency ='inr' AND stripe_subscription_id <> 'xxxxxxxxxxxx'";
						$connect_qryss1 = mysqli_query($conn, $qry_yearly_indian);



						?>
						<?php
						while ($run_qryy_yearly_indian = mysqli_fetch_array($connect_qryss1)) {
						?>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5>Total Amount <span>This Yearly (INR)</span>
											</h5>
											<h4> <span>&#8377;</span> <?php echo $run_qryy_yearly_indian['paid_amount']; ?></h4>
										</div>
									<?php
								}
									?>
										<div class="dash_icon">
										<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
	<path d="M386 944 c-171 -41 -308 -192 -338 -371 -39 -232 114 -464 343 -518 103 -24 228 -16 210 14 -7 10 -35 14 -102 16 -70 1 -108 7 -149 23 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 43 17 75 22 150 22 109 0 172 -20 252 -79 106 -78 160 -189 163 -334 2 -67 6 -95 16 -102 18 -11 27 17 28 91 5 303 -276 526 -573 454z" />
	<path d="M480 770 c0 -24 -4 -30 -20 -30 -89 0 -143 -129 -85 -199 24 -28 63 -45 163 -71 55 -14 82 -41 82 -82 0 -30 0 -31 20 -13 50 46 -6 112 -118 140 -92 22 -132 53 -132 102 0 25 8 43 29 64 25 24 37 29 81 29 59 0 96 -28 106 -80 5 -21 12 -30 27 -30 17 0 19 5 14 38 -8 45 -64 102 -102 102 -21 0 -25 5 -25 30 0 23 -4 30 -20 30 -16 0 -20 -7 -20 -30z" />
	<path d="M340 390 c0 -61 62 -130 118 -130 18 0 22 -6 22 -30 0 -23 4 -30 20 -30 16 0 20 7 20 30 0 25 4 30 24 30 19 0 36 16 36 35 0 2 -26 1 -57 -2 -50 -4 -63 -1 -93 19 -24 16 -38 35 -44 61 -11 41 -46 54 -46 17z" />
	<path d="M732 389 c-48 -14 -109 -80 -123 -131 -23 -89 12 -182 88 -229 57 -36 154 -34 210 3 62 41 88 90 88 168 0 77 -26 127 -85 166 -43 29 -125 39 -178 23z m134 -45 c103 -49 125 -175 45 -255 -66 -66 -159 -65 -223 2 -122 128 19 328 178 253z" />
	<path d="M862 265 c-11 -14 -34 -41 -52 -61 l-32 -37 -28 22 c-34 25 -46 26 -53 6 -4 -9 9 -25 35 -45 23 -16 45 -30 49 -30 10 0 129 136 129 148 0 22 -30 20 -48 -3z" />
</g>
</svg>
										</div>
									</div>
								</div>
							</div>





					<!-- Add coloumn for Total Amount in INR -->

						<?php
						$sql_total_inr = " select sum(paid_amount) as paid_amount  from user_subscriptions WHERE paid_amount_currency ='inr'  AND stripe_subscription_id <> 'xxxxxxxxxxxx'";
						$total_data_inr = mysqli_query($conn, $sql_total_inr);



						?>
						<?php
						while ($total__amount_inr = mysqli_fetch_array($total_data_inr)) {
						?>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5>Total Amount (INR) <span></span>
											</h5>
											<h4> <span>&#8377;</span> <?php echo $total__amount_inr['paid_amount']; ?></h4>
										</div>
									<?php
								}
									?>
									<div class="dash_icon">
										<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

											<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
												<path d="M387 914 c-4 -4 -7 -77 -7 -163 0 -172 10 -208 66 -265 112 -112 303 -71 359 78 22 58 18 351 -5 351 -13 0 -16 -27 -20 -165 -4 -143 -8 -170 -26 -200 -68 -117 -240 -117 -308 0 -18 30 -22 57 -26 199 -5 153 -11 186 -33 165z" />
												<path d="M467 914 c-4 -4 -7 -34 -7 -66 0 -56 1 -58 21 -48 17 10 20 19 17 62 -3 48 -15 68 -31 52z" />
												<path d="M547 914 c-9 -10 -9 -84 1 -84 21 1 33 20 30 50 -3 32 -17 48 -31 34z" />
												<path d="M627 914 c-14 -14 -7 -72 9 -78 25 -9 25 -8 22 36 -3 39 -16 57 -31 42z" />
												<path d="M707 914 c-4 -4 -7 -29 -7 -55 0 -38 4 -51 20 -59 20 -11 21 -9 18 51 -3 57 -14 80 -31 63z" />
												<path d="M580 762 c0 -9 -10 -22 -22 -27 -17 -7 -24 -20 -26 -46 -3 -41 7 -53 64 -71 28 -9 39 -19 39 -33 0 -30 -64 -35 -72 -6 -6 23 -43 30 -43 8 0 -20 30 -57 46 -57 8 0 14 -7 14 -15 0 -8 9 -15 20 -15 11 0 20 7 20 15 0 8 6 15 13 15 20 0 47 33 47 58 0 28 -24 50 -70 62 -44 12 -53 21 -44 44 8 20 60 21 68 1 7 -19 36 -20 36 -1 0 16 -26 46 -41 46 -5 0 -9 9 -9 20 0 13 -7 20 -20 20 -12 0 -20 -7 -20 -18z" />
												<path d="M744 407 l-120 -74 -139 61 c-176 76 -224 80 -353 27 -86 -35 -101 -46 -84 -63 7 -7 39 1 103 27 120 49 150 46 329 -32 109 -48 136 -63 138 -81 5 -36 -20 -36 -134 -2 -76 22 -110 28 -117 21 -7 -7 -8 -14 -1 -21 17 -17 217 -71 241 -65 29 7 57 49 50 76 -4 16 7 28 65 65 38 25 92 57 118 72 46 25 50 26 66 10 9 -9 14 -23 11 -31 -9 -25 -347 -277 -371 -277 -12 0 -84 22 -161 50 -77 27 -153 50 -170 50 -63 -1 -193 -85 -168 -110 9 -10 25 -3 73 29 35 23 74 41 90 41 15 0 92 -23 171 -51 106 -37 153 -49 178 -45 23 3 89 47 208 138 96 72 179 140 184 149 15 30 10 59 -16 84 -13 14 -35 25 -48 25 -12 0 -77 -33 -143 -73z" />
											</g>
										</svg>
									</div>
									</div>
								</div>
							</div>





							<!-- Monthly Subs indian -->
								<?php
						$sql_monthly_subs_ind = " select count(*) as id from user_subscriptions WHERE stripe_subscription_id <> 'xxxxxxxxxxxx' AND plan_interval ='month' AND paid_amount_currency='inr' and user_id != 130 and is_active = 1";
						$total_data_ind = mysqli_query($conn, $sql_monthly_subs_ind);



						?>
						<?php
						while ($total_subs_ind = mysqli_fetch_array($total_data_ind)) {
						?>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5>Monthly Subscription Indian <span></span>
											</h5>
											<h4> <?php echo $total_subs_ind['id']; ?></h4>
										</div>
									<?php
								}
									?>
									<div class="dash_icon">
										<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

											<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
												<path d="M387 914 c-4 -4 -7 -77 -7 -163 0 -172 10 -208 66 -265 112 -112 303 -71 359 78 22 58 18 351 -5 351 -13 0 -16 -27 -20 -165 -4 -143 -8 -170 -26 -200 -68 -117 -240 -117 -308 0 -18 30 -22 57 -26 199 -5 153 -11 186 -33 165z" />
												<path d="M467 914 c-4 -4 -7 -34 -7 -66 0 -56 1 -58 21 -48 17 10 20 19 17 62 -3 48 -15 68 -31 52z" />
												<path d="M547 914 c-9 -10 -9 -84 1 -84 21 1 33 20 30 50 -3 32 -17 48 -31 34z" />
												<path d="M627 914 c-14 -14 -7 -72 9 -78 25 -9 25 -8 22 36 -3 39 -16 57 -31 42z" />
												<path d="M707 914 c-4 -4 -7 -29 -7 -55 0 -38 4 -51 20 -59 20 -11 21 -9 18 51 -3 57 -14 80 -31 63z" />
												<path d="M580 762 c0 -9 -10 -22 -22 -27 -17 -7 -24 -20 -26 -46 -3 -41 7 -53 64 -71 28 -9 39 -19 39 -33 0 -30 -64 -35 -72 -6 -6 23 -43 30 -43 8 0 -20 30 -57 46 -57 8 0 14 -7 14 -15 0 -8 9 -15 20 -15 11 0 20 7 20 15 0 8 6 15 13 15 20 0 47 33 47 58 0 28 -24 50 -70 62 -44 12 -53 21 -44 44 8 20 60 21 68 1 7 -19 36 -20 36 -1 0 16 -26 46 -41 46 -5 0 -9 9 -9 20 0 13 -7 20 -20 20 -12 0 -20 -7 -20 -18z" />
												<path d="M744 407 l-120 -74 -139 61 c-176 76 -224 80 -353 27 -86 -35 -101 -46 -84 -63 7 -7 39 1 103 27 120 49 150 46 329 -32 109 -48 136 -63 138 -81 5 -36 -20 -36 -134 -2 -76 22 -110 28 -117 21 -7 -7 -8 -14 -1 -21 17 -17 217 -71 241 -65 29 7 57 49 50 76 -4 16 7 28 65 65 38 25 92 57 118 72 46 25 50 26 66 10 9 -9 14 -23 11 -31 -9 -25 -347 -277 -371 -277 -12 0 -84 22 -161 50 -77 27 -153 50 -170 50 -63 -1 -193 -85 -168 -110 9 -10 25 -3 73 29 35 23 74 41 90 41 15 0 92 -23 171 -51 106 -37 153 -49 178 -45 23 3 89 47 208 138 96 72 179 140 184 149 15 30 10 59 -16 84 -13 14 -35 25 -48 25 -12 0 -77 -33 -143 -73z" />
											</g>
										</svg>
									</div>
									</div>
								</div>
							</div>



						<!-- Add coloumn for monthly subscription Amount INR -->


							<?php
						$qry_month_amount = "SELECT SUM(paid_amount) AS paid_amount 
			              FROM user_subscriptions 
			              WHERE CONCAT(YEAR(created), MONTH(created)) = CONCAT($year, $month)
			              AND paid_amount_currency = 'inr' AND plan_interval ='month'
			                AND stripe_subscription_id <> 'xxxxxxxxxxxx'  and user_id != 130 and is_active = 1";



						$connect_qrys2 = mysqli_query($conn, $qry_month_amount);



						?>


							<div class="col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="row">
										<?php
										while ($run_qryy_month_inr = mysqli_fetch_array($connect_qrys2)) {

											//echo'<pre>';print_r($run_qryy_month);die;
											if($run_qryy_month_inr['paid_amount']==""){
												$run_qryy_month_inr['paid_amount'] = "0.00";
											}
										?>
											<h5>Monthly Subscription Amount <span>INR</span> </h5>
											<h4> <span>&#8377;</span> <?php echo $run_qryy_month_inr['paid_amount']; ?></h4>
									</div>
								<?php
										}
								?>
								<div class="dash_icon">
									<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

										<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
											<path d="M386 944 c-171 -41 -308 -192 -338 -371 -39 -232 114 -464 343 -518 103 -24 228 -16 210 14 -7 10 -35 14 -102 16 -70 1 -108 7 -149 23 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 43 17 75 22 150 22 109 0 172 -20 252 -79 106 -78 160 -189 163 -334 2 -67 6 -95 16 -102 18 -11 27 17 28 91 5 303 -276 526 -573 454z" />
											<path d="M480 770 c0 -24 -4 -30 -20 -30 -89 0 -143 -129 -85 -199 24 -28 63 -45 163 -71 55 -14 82 -41 82 -82 0 -30 0 -31 20 -13 50 46 -6 112 -118 140 -92 22 -132 53 -132 102 0 25 8 43 29 64 25 24 37 29 81 29 59 0 96 -28 106 -80 5 -21 12 -30 27 -30 17 0 19 5 14 38 -8 45 -64 102 -102 102 -21 0 -25 5 -25 30 0 23 -4 30 -20 30 -16 0 -20 -7 -20 -30z" />
											<path d="M340 390 c0 -61 62 -130 118 -130 18 0 22 -6 22 -30 0 -23 4 -30 20 -30 16 0 20 7 20 30 0 25 4 30 24 30 19 0 36 16 36 35 0 2 -26 1 -57 -2 -50 -4 -63 -1 -93 19 -24 16 -38 35 -44 61 -11 41 -46 54 -46 17z" />
											<path d="M732 389 c-48 -14 -109 -80 -123 -131 -23 -89 12 -182 88 -229 57 -36 154 -34 210 3 62 41 88 90 88 168 0 77 -26 127 -85 166 -43 29 -125 39 -178 23z m134 -45 c103 -49 125 -175 45 -255 -66 -66 -159 -65 -223 2 -122 128 19 328 178 253z" />
											<path d="M862 265 c-11 -14 -34 -41 -52 -61 l-32 -37 -28 22 c-34 25 -46 26 -53 6 -4 -9 9 -25 35 -45 23 -16 45 -30 49 -30 10 0 129 136 129 148 0 22 -30 20 -48 -3z" />
										</g>
									</svg>
								</div>
								</div>
							</div>
						</div>



						<!-- Yearly Subs Indian -->
						<?php
						$sql_yearly_subs_ind = " select count(*) as id from user_subscriptions WHERE stripe_subscription_id <> 'xxxxxxxxxxxx' AND plan_interval ='year' AND paid_amount_currency='inr' and user_id != 130 and is_active = 1";
						$yearly_data_ind = mysqli_query($conn, $sql_yearly_subs_ind);



						?>
						<?php
						while ($yearly_subs_ind = mysqli_fetch_array($yearly_data_ind)) {
						?>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5>Yearly Subscription Indian <span></span>
											</h5>
											<h4> <?php echo $yearly_subs_ind['id']; ?></h4>
										</div>
									<?php
								}
									?>
									<div class="dash_icon">
										<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

											<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
												<path d="M387 914 c-4 -4 -7 -77 -7 -163 0 -172 10 -208 66 -265 112 -112 303 -71 359 78 22 58 18 351 -5 351 -13 0 -16 -27 -20 -165 -4 -143 -8 -170 -26 -200 -68 -117 -240 -117 -308 0 -18 30 -22 57 -26 199 -5 153 -11 186 -33 165z" />
												<path d="M467 914 c-4 -4 -7 -34 -7 -66 0 -56 1 -58 21 -48 17 10 20 19 17 62 -3 48 -15 68 -31 52z" />
												<path d="M547 914 c-9 -10 -9 -84 1 -84 21 1 33 20 30 50 -3 32 -17 48 -31 34z" />
												<path d="M627 914 c-14 -14 -7 -72 9 -78 25 -9 25 -8 22 36 -3 39 -16 57 -31 42z" />
												<path d="M707 914 c-4 -4 -7 -29 -7 -55 0 -38 4 -51 20 -59 20 -11 21 -9 18 51 -3 57 -14 80 -31 63z" />
												<path d="M580 762 c0 -9 -10 -22 -22 -27 -17 -7 -24 -20 -26 -46 -3 -41 7 -53 64 -71 28 -9 39 -19 39 -33 0 -30 -64 -35 -72 -6 -6 23 -43 30 -43 8 0 -20 30 -57 46 -57 8 0 14 -7 14 -15 0 -8 9 -15 20 -15 11 0 20 7 20 15 0 8 6 15 13 15 20 0 47 33 47 58 0 28 -24 50 -70 62 -44 12 -53 21 -44 44 8 20 60 21 68 1 7 -19 36 -20 36 -1 0 16 -26 46 -41 46 -5 0 -9 9 -9 20 0 13 -7 20 -20 20 -12 0 -20 -7 -20 -18z" />
												<path d="M744 407 l-120 -74 -139 61 c-176 76 -224 80 -353 27 -86 -35 -101 -46 -84 -63 7 -7 39 1 103 27 120 49 150 46 329 -32 109 -48 136 -63 138 -81 5 -36 -20 -36 -134 -2 -76 22 -110 28 -117 21 -7 -7 -8 -14 -1 -21 17 -17 217 -71 241 -65 29 7 57 49 50 76 -4 16 7 28 65 65 38 25 92 57 118 72 46 25 50 26 66 10 9 -9 14 -23 11 -31 -9 -25 -347 -277 -371 -277 -12 0 -84 22 -161 50 -77 27 -153 50 -170 50 -63 -1 -193 -85 -168 -110 9 -10 25 -3 73 29 35 23 74 41 90 41 15 0 92 -23 171 -51 106 -37 153 -49 178 -45 23 3 89 47 208 138 96 72 179 140 184 149 15 30 10 59 -16 84 -13 14 -35 25 -48 25 -12 0 -77 -33 -143 -73z" />
											</g>
										</svg>
									</div>
									</div>
								</div>
							</div>



										<!-- Yearly Subscription Amount In INR -->

										<?php
						$select_subs_amount_inr = "select sum(paid_amount) as paid_amount  from user_subscriptions where paid_amount_currency ='inr' AND plan_interval='year' AND stripe_subscription_id <> 'xxxxxxxxxxxx' and user_id != 130 and is_active = 1";
						$connect_qryss_inr_yearly = mysqli_query($conn, $select_subs_amount_inr);



						?>
						<?php
						while ($run_qryy_yearly_amount_inr = mysqli_fetch_array($connect_qryss_inr_yearly)) {
						?>
							<div class="col-md-4">
								<div class="card">
									<div class="card-body">
										<div class="row">
											<h5>Yearly Subscription Amount <span> (INR)</span>
											</h5>
											<h4> <span>&#8377;</span> <?php
											 if($run_qryy_yearly_amount_inr['paid_amount']==""){
											 	$run_qryy_yearly_amount_inr['paid_amount'] = "0.00";
											 }
											 echo $run_qryy_yearly_amount_inr['paid_amount']; 
											 ?></h4>
										</div>
									<?php
								}
									?>
										<div class="dash_icon">
										<svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

										<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
											<path d="M386 944 c-171 -41 -308 -192 -338 -371 -39 -232 114 -464 343 -518 103 -24 228 -16 210 14 -7 10 -35 14 -102 16 -70 1 -108 7 -149 23 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 43 17 75 22 150 22 109 0 172 -20 252 -79 106 -78 160 -189 163 -334 2 -67 6 -95 16 -102 18 -11 27 17 28 91 5 303 -276 526 -573 454z" />
											<path d="M480 770 c0 -24 -4 -30 -20 -30 -89 0 -143 -129 -85 -199 24 -28 63 -45 163 -71 55 -14 82 -41 82 -82 0 -30 0 -31 20 -13 50 46 -6 112 -118 140 -92 22 -132 53 -132 102 0 25 8 43 29 64 25 24 37 29 81 29 59 0 96 -28 106 -80 5 -21 12 -30 27 -30 17 0 19 5 14 38 -8 45 -64 102 -102 102 -21 0 -25 5 -25 30 0 23 -4 30 -20 30 -16 0 -20 -7 -20 -30z" />
											<path d="M340 390 c0 -61 62 -130 118 -130 18 0 22 -6 22 -30 0 -23 4 -30 20 -30 16 0 20 7 20 30 0 25 4 30 24 30 19 0 36 16 36 35 0 2 -26 1 -57 -2 -50 -4 -63 -1 -93 19 -24 16 -38 35 -44 61 -11 41 -46 54 -46 17z" />
											<path d="M732 389 c-48 -14 -109 -80 -123 -131 -23 -89 12 -182 88 -229 57 -36 154 -34 210 3 62 41 88 90 88 168 0 77 -26 127 -85 166 -43 29 -125 39 -178 23z m134 -45 c103 -49 125 -175 45 -255 -66 -66 -159 -65 -223 2 -122 128 19 328 178 253z" />
											<path d="M862 265 c-11 -14 -34 -41 -52 -61 l-32 -37 -28 22 c-34 25 -46 26 -53 6 -4 -9 9 -25 35 -45 23 -16 45 -30 49 -30 10 0 129 136 129 148 0 22 -30 20 -48 -3z" />
										</g>
										</svg>
										</div>
									</div>
								</div>
							</div>






<!-- New Code For Indian payment end-->

					</div>


				</div>


			</div>
		</div>
	</div>

</body>

</html>

<script type="text/javascript">
	// set the countdown date
	var target_date = new Date().getTime() + (1000 * <?= $subscription_diff; ?>);

	var days, hours, minutes, seconds; // variables for time units

	var countdown = document.getElementById("tiles"); // get tag element

	getCountdown();

	var subscriptionInterval = "";

	function getCountdown() {

		// find the amount of "seconds" between now and target
		var current_date = new Date().getTime();
		var seconds_left = (target_date - current_date) / 1000;

		days = pad(parseInt(Math.ceil(seconds_left / 86400)));

		// format countdown string + set tag value
		countdown.innerHTML = "<span>" + days + "</span>";

		if (parseInt(days) <= 0) {

			clearInterval(subscriptionInterval);
			countdown.innerHTML = "<span>00</span>";

			$.ajax({
				url: "inc/expire-plan.php",
				method: "POST",
				dataType: "JSON",
			}).done(function(reponse) {});
		} else {
			var subscriptionInterval = setInterval(function() {
				getCountdown();
			}, 1000);
		}
	}

	function pad(n) {
		return (n < 10 ? '0' : '') + n;
	}

	// ===================================================

	function loadVisitorChart(label, data) {

		// alert("call") ;

		var ctx = document.getElementById('visitor-chart').getContext('2d');
		var chart = new Chart(ctx, {
			// The type of chart we want to create
			type: 'bar',

			// The data for our dataset
			data: {
				labels: label,
				datasets: [{
					label: 'Visitor',
					backgroundColor: 'rgb(241, 56, 66)',
					borderColor: 'rgb(241, 56, 66)',
					data: data
				}]
			},

			// Configuration options go here
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		});
	}

	loadVisitorChart(['<?= implode("','", $chartLabel) ?>'], ['<?= implode("','", $chartData) ?>']);

	$("#visitor-period").change(function() {
		var v = $(this).val();
		var s = $(this).attr("data-store");

		var req = $.ajax({
			url: 'inc/update-visitor.php',
			method: 'POST',
			dataType: 'JSON',
			async: false,
			data: {
				opt: v,
				store: s
			}
		});

		req.done(function(reponse) {

			var labels = reponse.label.split(",");
			var datas = reponse.data.split(",");

			// ----------------------- 
			$("div#visitor-chart-box").html("");
			$("div#visitor-chart-box").html('<canvas id="visitor-chart"></canvas>');
			loadVisitorChart(labels, datas);
		});

		req.fail(function(reponse) {
			console.log(reponse);
		});

		req.always(function() {});
	});

	function showTopVisitor(t) {
		t = t - 1;
		$(".top-location-table").removeClass("d-none").addClass("d-none");
		$(".top-location-table:eq(" + t + ")").removeClass("d-none");
	}
</script>