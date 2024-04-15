<?php
include('config.php');
require_once('meta_details.php');
require_once('inc/functions.php') ;


if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if ( empty($_SESSION['user_id']) || empty($_SESSION['role']) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
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
				<div class="container-fluid content__up analytics">
					<h1>Analytics</h1>

					<?php require_once("inc/alert-status.php") ; ?>
                   <div class="profile_tabs">
					<div class="col-md-12 ">


<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
	<li class="nav-item" role="presentation">
		<button data-period="today" class="period-tab nav-link active" id="today-tab" type="button">Today</button>
	</li>
	<li class="nav-item" role="presentation">
		<button data-period="this-week" class="period-tab nav-link" id="this-week-tab" type="button">This week</button>
	</li>
	<li class="nav-item" role="presentation">
		<button data-period="one-week" class="period-tab nav-link" id="one-week-tab" type="button">One week</button>
	</li>
	<li class="nav-item" role="presentation">
		<button data-period="this-month" class="period-tab nav-link" id="this-week-tab" type="button">This month</button>
	</li>
	<li class="nav-item" role="presentation">
		<button data-period="one-month" class="period-tab nav-link" id="one-week-tab" type="button">One month</button>
	</li>
	<li class="nav-item" role="presentation">
		<button data-period="this-year" class="period-tab nav-link" id="this-week-tab" type="button">This year</button>
	</li>
	<li class="nav-item" role="presentation">
		<button data-period="one-year" class="period-tab nav-link" id="one-week-tab" type="button">One year</button>
	</li>
</ul>

<div class="tab-content" id="pills-tabContent">
	<div class="tab-pane fade show active" id="today" role="tabpanel" aria-labelledby="today-tab">
		<?php

			// today's manager count
			$sql = " SELECT * FROM `admin_users` WHERE `userstatus` LIKE 'manager' AND DATE(created_at) = CURDATE(); " ;
			$query = $conn->query($sql) ;
			$today_manager = 0 ;
			if ( $query->num_rows > 0 ) {
				$today_manager = $query->num_rows ;
			}

			// today's sales/subscription purchase count
			$sql = " SELECT * FROM `user_subscriptions` WHERE DATE(created) = CURDATE(); " ;
			$query = $conn->query($sql) ;
			$today_subscription = 0 ;
			if ( $query->num_rows > 0 ) {

				$user_subscriptions = $query->fetch_all(MYSQLI_ASSOC);
				$paid_amount = array_column($user_subscriptions, 'paid_amount');
				$today_subscription = array_sum($paid_amount) ;
			}
		?>
		<div class="analytics_report">
			<div class="sales_web">
				<div class="col-md-6 sales_s">
					<div class="sales_h4">
					<p>Sales</p>
					<h4><span id="analytics-sales"><?=$today_subscription?></span> USD</h4>
		            </div>
					<div class="dash_icon">
		            <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

										<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
											<path d="M386 944 c-171 -41 -308 -192 -338 -371 -39 -232 114 -464 343 -518 103 -24 228 -16 210 14 -7 10 -35 14 -102 16 -70 1 -108 7 -149 23 -112 45 -197 130 -242 242 -31 78 -31 222 0 300 45 112 130 197 242 242 43 17 75 22 150 22 109 0 172 -20 252 -79 106 -78 160 -189 163 -334 2 -67 6 -95 16 -102 18 -11 27 17 28 91 5 303 -276 526 -573 454z"></path>
											<path d="M480 770 c0 -24 -4 -30 -20 -30 -89 0 -143 -129 -85 -199 24 -28 63 -45 163 -71 55 -14 82 -41 82 -82 0 -30 0 -31 20 -13 50 46 -6 112 -118 140 -92 22 -132 53 -132 102 0 25 8 43 29 64 25 24 37 29 81 29 59 0 96 -28 106 -80 5 -21 12 -30 27 -30 17 0 19 5 14 38 -8 45 -64 102 -102 102 -21 0 -25 5 -25 30 0 23 -4 30 -20 30 -16 0 -20 -7 -20 -30z"></path>
											<path d="M340 390 c0 -61 62 -130 118 -130 18 0 22 -6 22 -30 0 -23 4 -30 20 -30 16 0 20 7 20 30 0 25 4 30 24 30 19 0 36 16 36 35 0 2 -26 1 -57 -2 -50 -4 -63 -1 -93 19 -24 16 -38 35 -44 61 -11 41 -46 54 -46 17z"></path>
											<path d="M732 389 c-48 -14 -109 -80 -123 -131 -23 -89 12 -182 88 -229 57 -36 154 -34 210 3 62 41 88 90 88 168 0 77 -26 127 -85 166 -43 29 -125 39 -178 23z m134 -45 c103 -49 125 -175 45 -255 -66 -66 -159 -65 -223 2 -122 128 19 328 178 253z"></path>
											<path d="M862 265 c-11 -14 -34 -41 -52 -61 l-32 -37 -28 22 c-34 25 -46 26 -53 6 -4 -9 9 -25 35 -45 23 -16 45 -30 49 -30 10 0 129 136 129 148 0 22 -30 20 -48 -3z"></path>
										</g>
									</svg>
				    </div>
				</div>

				<div class="col-md-6 web_owners">
					<div class="sales_h4">
					<p>Website Owners</p>
					<h4 id="analytics-owners"><?=$today_manager?></h4>
          		    </div>

					  <div class="dash_icon">
					  <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000" preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
	<path d="M381 944 c-116 -31 -232 -126 -290 -238 -36 -67 -38 -86 -12 -86 12 0 24 14 35 43 9 23 25 53 35 66 l18 24 54 -21 54 -20 -2 -46 c-3 -39 0 -46 16 -46 15 0 21 10 26 41 5 26 11 39 19 36 7 -3 43 -8 79 -12 65 -7 67 -8 67 -36 0 -22 5 -29 20 -29 15 0 20 7 20 29 0 28 2 29 67 36 36 4 72 9 79 12 8 3 14 -10 19 -36 5 -31 11 -41 26 -41 16 0 19 7 16 46 l-2 46 54 20 54 21 18 -24 c10 -13 26 -43 35 -66 11 -29 23 -43 35 -43 25 0 24 11 -8 78 -33 70 -119 163 -188 202 -100 57 -232 74 -344 44z m95 -222 c-3 -3 -34 -1 -68 4 -59 8 -63 11 -60 33 4 37 61 121 96 142 l31 19 3 -96 c1 -53 0 -99 -2 -102z m131 125 c38 -56 56 -98 46 -108 -4 -4 -36 -11 -70 -15 l-63 -7 0 102 0 102 30 -16 c16 -8 42 -34 57 -58z m-256 12 c-17 -22 -35 -56 -41 -75 -6 -19 -16 -34 -21 -34 -18 0 -89 33 -89 41 0 19 142 108 173 109 4 0 -6 -18 -22 -41z m339 16 c40 -21 110 -74 110 -84 0 -8 -71 -41 -89 -41 -5 0 -15 15 -21 34 -6 19 -25 53 -41 76 -29 40 -30 42 -7 35 13 -4 34 -13 48 -20z"></path>
	<path d="M62 568 c3 -7 15 -43 27 -80 20 -59 26 -68 46 -68 20 0 27 9 41 55 10 30 21 52 25 49 4 -2 15 -27 24 -54 14 -41 21 -50 40 -50 19 0 26 9 40 53 9 28 20 64 25 80 8 24 6 27 -14 27 -18 0 -24 -9 -35 -50 -7 -28 -16 -48 -21 -45 -4 2 -13 25 -20 50 -11 39 -16 45 -39 45 -23 0 -29 -7 -46 -52 l-19 -53 -15 53 c-13 44 -19 52 -39 52 -15 0 -22 -5 -20 -12z"></path>
	<path d="M365 568 c44 -143 46 -148 70 -148 20 0 27 9 41 55 10 30 21 55 24 55 3 0 14 -25 24 -55 14 -46 21 -55 41 -55 24 0 26 5 70 148 3 7 -5 12 -18 12 -19 0 -25 -8 -36 -50 -7 -28 -16 -48 -21 -45 -4 2 -13 25 -20 50 -11 39 -16 45 -40 45 -24 0 -29 -6 -40 -45 -7 -25 -16 -48 -20 -50 -5 -3 -14 17 -21 45 -11 42 -17 50 -36 50 -13 0 -21 -5 -18 -12z"></path>
	<path d="M662 568 c3 -7 15 -43 27 -80 20 -59 26 -68 46 -68 20 0 27 9 41 55 10 30 21 55 24 55 3 0 14 -25 24 -55 14 -46 21 -55 41 -55 20 0 26 9 46 68 12 37 24 73 27 80 3 10 -2 13 -18 10 -18 -2 -27 -15 -41 -53 l-17 -50 -11 28 c-6 15 -14 39 -17 52 -5 19 -13 25 -33 25 -23 0 -29 -7 -46 -52 l-19 -53 -15 53 c-13 44 -19 52 -39 52 -15 0 -22 -5 -20 -12z"></path>
	<path d="M60 369 c0 -31 61 -132 112 -185 183 -192 473 -192 656 0 51 53 112 154 112 185 0 6 -8 11 -19 11 -12 0 -24 -14 -35 -42 -9 -24 -25 -54 -35 -67 l-18 -24 -54 21 -54 20 2 46 c3 39 0 46 -16 46 -15 0 -21 -10 -26 -41 -5 -26 -11 -39 -19 -36 -7 3 -43 8 -79 12 -65 7 -67 8 -67 36 0 22 -5 29 -20 29 -15 0 -20 -7 -20 -29 0 -28 -2 -29 -67 -36 -36 -4 -72 -9 -79 -12 -8 -3 -14 10 -19 36 -5 31 -11 41 -26 41 -16 0 -19 -7 -16 -46 l2 -46 -54 -20 -54 -21 -18 24 c-10 13 -26 43 -35 67 -11 28 -23 42 -35 42 -11 0 -19 -5 -19 -11z m420 -189 l0 -101 -30 16 c-44 23 -122 148 -103 166 9 8 48 15 101 18 l32 1 0 -100z m173 81 c19 -19 -59 -143 -103 -166 l-30 -16 0 102 0 102 63 -7 c34 -4 66 -11 70 -15z m-343 -45 c6 -19 25 -53 41 -76 29 -40 30 -42 7 -35 -51 16 -158 86 -158 104 0 9 65 40 87 40 6 1 17 -14 23 -33z m450 18 c22 -9 40 -20 40 -25 0 -18 -107 -88 -158 -104 -23 -7 -22 -5 7 35 16 23 35 57 41 76 13 39 16 40 70 18z"></path>
</g>
</svg>
								</div>
				</div>
		    </div>

			<div class="col-md-12 websales profile_tabs">

<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="sales-chart-tab" data-toggle="pill" data-target="#sales-chart" type="button" role="tab" aria-controls="sales-chart" aria-selected="true">Sales</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="owners-chart-tab" data-toggle="pill" data-target="#owners-chart" type="button" role="tab" aria-controls="owners-chart" aria-selected="false">Website Owners</button>
	</li>
</ul>
<div class="tab-content" id="pills-tabContent">
	<div class="tab-pane fade chart-tab show active" id="sales-chart" role="tabpanel" aria-labelledby="sales-chart-tab">
		<div id="salesChart-box">
			<canvas id="salesChart"></canvas>
		</div>
	</div>
	<div class="tab-pane fade chart-tab" id="owners-chart" role="tabpanel" aria-labelledby="owners-chart-tab">
		<div id="managerChart-box">
			<canvas id="managerChart"></canvas>
		</div>
	</div>
</div>

		</div>
			</div>
		</div>
	</div>
</div>


					</div>
					
				</div>
			</div>
		</div>

	</body>
</html>

<script type="text/javascript">
function loadAnalyticChart(owner_label,owner_data,subscription_label,subscription_data) {

	var index = $(".chart-tab.active").index() ;
	// $(".chart-tab").hasClass("active") ;
	$(".chart-tab").addClass("show active") ;
	// =============================================================
	$("#salesChart-box").html("") ;
	$("#salesChart-box").html('<canvas id="salesChart"></canvas>') ;
	var ctx = document.getElementById('salesChart').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'line',
	    // The data for our dataset
	    data: {
	        labels: subscription_label,
	        datasets: [{
	            label: 'Sales',
	            backgroundColor: '#0000ff' ,
	            borderColor: '#0000ff',
	            data: subscription_data,
	            fill: false
	        }]
	    },

	    // Configuration options go here
	    options: {
		    scales: {
	        yAxes: [{
					ticks: {
						beginAtZero:true
					}
	        	}]
	     	}
	    }
	});

	// ================================================================
	$("#managerChart-box").html("") ;
	$("#managerChart-box").html('<canvas id="managerChart"></canvas>') ;
	var ctx = document.getElementById('managerChart').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'line',
	    // The data for our dataset
	    data: {
	        labels: owner_label,
	        datasets: [{
	            label: 'Onwers',
	            backgroundColor: '#0000ff' ,
	            borderColor: '#0000ff',
	            data: owner_data,
	            fill: false
	        }]
	    },

	    // Configuration options go here
	    options: {
		    scales: {
	        yAxes: [{
					ticks: {
						beginAtZero:true
					}
	        	}]
	     	}
	    }
	});

	$(".chart-tab").removeClass("show active") ;
	$(".chart-tab:eq("+index+")").addClass("show active") ;
}

function loadAnalyticDetails(period) {

	$.ajax({
		url:"inc/update-analytics.php",
		method:"POST",
		dataType:"JSON",
		data:{period:period}
	}).done(function(response){

		loadAnalyticChart(response.owner_label,response.owner_data,response.subscription_label,response.subscription_data) ;

		$("#analytics-sales").html(response.sales);
		$("#analytics-owners").html(response.website_owners);

	}).fail(function(){
		console.log("error") ;
	});

}

$(document).ready(function(){

	loadAnalyticDetails("today") ;

	$(".period-tab").click(function(){
		$(".period-tab").removeClass("active") ;
		$(this).addClass("active") ;

		var period = $(this).attr("data-period");
		loadAnalyticDetails(period) ;
	}) ;
});

</script>
