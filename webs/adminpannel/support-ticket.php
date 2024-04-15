<?php 
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

include('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;

// check sign-up process complete
// checkSignupComplete($conn) ;




$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>

		<?php require_once('inc/style-and-script.php') ; ?>
		
	</head>
	<body class="custom-tabel">
		<div class="d-flex" id="wrapper">
		<div class="top-bg-img"></div>
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>

				<!-- Page content-->
				<div class="container-fluid support_ticket content__up">

				<?php
					$user_id = $_SESSION["user_id"] ;

					// Overview
					$project = base64_decode($_GET['project']) ;
					
					$website_data = getTableData( $conn , " boost_website " , " id = '".$project."' AND manager_id = '".$user_id."' " ) ;
					
					// print_r($website_data) ;

					// get page view
					$today_visitor_arr = getTableData( $conn , " website_visits " , " DATE(created_at) = CURDATE() AND manager_id='".
						$user_id."' AND website_id='".$project."' ", "" , 1 , " ip " ) ;

					$total_visitor_arr = getTableData( $conn , " website_visits " , " manager_id='".
						$user_id."' AND website_id='".$project."' ", "" , 1 , " ip " ) ;
					// print_r($today_visitor_arr) ;

					// get page view

					$most_visited_country = "" ;

					$sql = " SELECT COUNT(id) AS count , country  FROM `website_visits` WHERE `manager_id` = '".$user_id."' AND `website_id` = '".$project."' GROUP BY country ORDER BY count DESC LIMIT 1 " ;
					$query = $conn->query($sql) ;

					if ( $query->num_rows > 0 ) {
						$mvc_data = $query->fetch_assoc() ;
						$most_visited_country = $mvc_data["country"] ;
						// print_r($most_visited_country) ;
					}

					// $query = getTableData( $conn , " website_visits " , " manager_id = '".$user_id."' AND website_id = '".$project."' " , " GROUP BY country ORDER BY count DESC LIMIT 1 " , 0 , " COUNT(id) AS count , country " ) ;
					?>
					<h1 class="mt-4">Manage Tickets</h1>
					<?php require_once("inc/alert-status.php") ; ?>

					<div class="profile_tabs">

					<a href="<?=HOST_URL?>adminpannel/ticket-form.php" class="btn btn-primary">Create Ticket</a>

					<?php

						$user_id = $_SESSION["user_id"] ;


					?>

					
                   <div class="table_S">
					<table class="speedy-table table support__ticket__table">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Ticket ID</th>
								<th> Subject</th>
								<th>Website</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php

							$tickets = getTableData( $conn , " support_tickets " , " manager_id = '".$user_id."' " , "" , 1 ) ;

							// print_r($websites) ;

							if ( count($tickets) > 0 ) 
							{
								$sno = 1 ;
								foreach ($tickets as $tickets_data) {

									$replycheck = $conn->query("SELECT manager_id FROM ticket_replies WHERE ticket_id = '".$tickets_data['id']."' ORDER BY updated_at desc LIMIT 1");


									$admin_replied = ($replycheck->fetch_assoc()['manager_id'] == 1)?1:0;

									?>

									<tr><td><?=$sno?></td>
										<td><?=$tickets_data['id']?></td>
										<td><?=$tickets_data['issue']?></td>
										<td><?=$tickets_data['website']?></td>
										<td><?=$tickets_data['email']?></td>
										<td><?=$tickets_data['phone']?></td>
										<td><?=($tickets_data['resolved']==1)?'Resolved':'Unresolved'?></td>
										<td><a href="reply-ticket.php?ticket_id=<?=base64_encode($tickets_data['id'])?>" <?=($admin_replied == 0 && $tickets_data['resolved'] == 0)?'style="pointer-events: none"':''?>><button type="button" <?=($admin_replied == 0 && $tickets_data['resolved'] == 0)?'disabled':''?> class="btn btn-success"><?php if($tickets_data['resolved'] != 1) {?><?=($admin_replied == 1)?'Admin Replied':'Waiting for reply'?><?php }else{ echo 'View Transcript';} ?></button></a></td>
										
									</tr>
									<?php
									$sno++ ;
								}
							}
							else {
								?><tr><td colspan="8"> No Data found.</td></tr><?php
							}

						?>

						</tbody>
					</table>
						</div>
						</div>
				</div>
			</div>
		</div>

	</body>
</html>

<script type="text/javascript">
// set the countdown date
var target_date = new Date().getTime() + (1000*<?=$subscription_diff;?>);

var days, hours, minutes, seconds; // variables for time units

var countdown = document.getElementById("tiles"); // get tag element

getCountdown();

var subscriptionInterval = "" ;

function getCountdown(){

  // find the amount of "seconds" between now and target
  var current_date = new Date().getTime();
  var seconds_left = (target_date - current_date) / 1000;

  days = pad( parseInt(Math.ceil(seconds_left / 86400)) );

  // format countdown string + set tag value
  countdown.innerHTML = "<span>" + days + "</span>"; 

	if ( parseInt(days) <= 0  ) {

		clearInterval(subscriptionInterval);
		countdown.innerHTML = "<span>00</span>"; 

		$.ajax({
			url: "inc/expire-plan.php",
			method:"POST",
			dataType:"JSON",
		}).done(function(reponse){});
	}
	else {
		var subscriptionInterval = setInterval(function () { getCountdown(); }, 1000);
	}
}

function pad(n) {
  return (n < 10 ? '0' : '') + n;
}

// ===================================================

function loadVisitorChart(label,data,backgroundColor) {

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
	            backgroundColor: backgroundColor ,
	            borderColor: backgroundColor,
	            data: data
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
}

loadVisitorChart(['<?=$chartLabel?>'],['<?=$chartData?>'],['<?=$chartColor?>']) ;

$("#visitor-period").change(function(){
	var v = $(this).val() ;
	var o = $(this).attr("data-owner") ;
	var p = $(this).attr("data-website") ;

	var req = $.ajax({
	    url:'inc/update-visitor.php',
	    method: 'POST',
	    dataType: 'JSON',
	    async : false ,
	    data : {opt:v , owner:o , website:p }
	}) ;

    req.done(function(reponse){

        var labels = reponse.label.split(",") ;
        var datas = reponse.data.split(",") ;
        var colors = reponse.color.split(",") ;
        
        // ----------------------- 
        $("div#visitor-chart-box").html("") ;
        $("div#visitor-chart-box").html('<canvas id="visitor-chart"></canvas>') ;
        loadVisitorChart(labels,datas,colors) ;
    });

    req.fail(function(reponse){
        console.log(reponse);
    });

    req.always(function(){});
});

function showTopVisitor(t) {
    t = t -1 ;
    $(".top-location-table").removeClass("d-none").addClass("d-none") ;
    $(".top-location-table:eq("+t+")").removeClass("d-none") ;
}

</script>

<script type="text/javascript">
loadVisitorChart1(['<?=$visitor_country?>'],['<?=$country_percent?>'],['<?=$country_color?>']);

function loadVisitorChart1(label,data,backgroundColor) {

	$("div#visitor-location-box").html("") ;
	$("div#visitor-location-box").html('<canvas id="visitor-location-chart"></canvas>') ;

	var ctx = document.getElementById('visitor-location-chart').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'pie',

	    // The data for our dataset
	    data: {
	        labels: label,
	        datasets: [{
	            label: '',
	            // backgroundColor: ['#7e7d7d','#ffc107'] ,
	            backgroundColor: backgroundColor ,
	            borderColor: backgroundColor,
	            data: data
	        }]
	    },
	    options: {
	        plugins: {
	            title: {
	                display: true,
	                text: 'Custom Chart Title'
	            }
	        }
	    }
	});
}
// function loadVisitorChart2(label,data) {

// 	$("div#visitor2-location-box").html("") ;
// 	$("div#visitor2-location-box").html('<canvas id="visitor2-location-chart"></canvas>') ;

// 	var ctx = document.getElementById('visitor2-location-chart').getContext('2d');
// 	var chart = new Chart(ctx, {
// 	    // The type of chart we want to create
// 	    type: 'pie',

// 	    // The data for our dataset
// 	    data: {
// 	        labels: label,
// 	        datasets: [{
// 	            label: 'Visitor',
// 	            backgroundColor: 'rgb(241, 56, 66)',
// 	            // backgroundColor: ["rgb(255, 99, 132)","rgb(54, 162, 235)","rgb(255, 205, 86)"],
// 	            borderColor: '#f13842',
// 	            data: data
// 	        }]
// 	    },
// 	    options: {
// 	        plugins: {
// 	            title: {
// 	                display: true,
// 	                text: 'Custom Chart Title'
// 	            }
// 	        }
// 	    }
// 	});
// }

</script>