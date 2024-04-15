<h5>Welcome to WebsiteSpeedy!</h5>
<div class="row">
	<div class="col-md-8 canvas__chart">
		<select class="form-control" id="visitor-period" data-store="<?=$row["id"];?>">
			<option value="24hours">Last 24hours</option>
			<option value="7days" selected>Last 7days</option>
			<option value="Month">Last Month</option>
			<!-- <option value="3Month">Last 3Month</option> -->
			<option value="6Month">Last 6Month</option>
			<option value="Year">Last Year</option>
		</select>
		<?php
			$chartPoints = [] ;
			$chartLabel = [0] ;
			$chartData = [0] ;
			
			// $query = $conn->query("SELECT website_visits.* , DATE_FORMAT(website_visits.created_at,'%e %b') AS visitor_date FROM website_visits WHERE website_visits.manager_id = '".$row["id"]."' AND DATE(website_visits.created_at) > now() - INTERVAL 7 day") ;
			$query = $conn->query("SELECT website_visits.* , DATE_FORMAT(website_visits.created_at,'%e %b') AS visitor_date FROM website_visits WHERE website_visits.manager_id = '".$row["id"]."' AND DATE(website_visits.created_at) - INTERVAL 7 day") ;
			
			if ( $query->num_rows > 0 ) {
				$chart_data = $query->fetch_all(MYSQLI_ASSOC) ;
			
				foreach ($chart_data as $key => $value) {
			
				    if (array_key_exists($value["visitor_date"], $chartPoints)) {
				        $chartPoints[$value["visitor_date"]] = $chartPoints[$value["visitor_date"]] + 1 ;
				    }
				    else {
				        $chartPoints[$value["visitor_date"]] = 1 ;
				    }
				}
			
				$chartLabel = array_keys($chartPoints) ;
				$chartData = array_values($chartPoints) ;
			}
			
			// print_r($chartLabel) ;
			// print_r($chartData) ;
			?>
		<div id="visitor-chart-box">
			<canvas id="visitor-chart"></canvas>
		</div>
	</div>
	<div class="col-md-4">
		<?php
			$user_subscription = getTableData( $conn , 
				" user_subscription , subscription_plan " , 
				" user_subscription.user_id = '".$user_id."' AND user_subscription.status LIKE 'active' AND subscription_plan.id = user_subscription.plan_id ORDER by user_subscription.id desc" , 
				"" ,
				0 , 
				" user_subscription.plan_id , user_subscription.charge_id , user_subscription.plan_active_date , user_subscription.status , user_subscription.plan_id , subscription_plan.s_type , subscription_plan.s_trial_duration , user_subscription.plan_type "
				) ;
			
			
			$current_time = strtotime(date('Y-m-d H:i:s')) ;
			
			$plan_active_date = strtotime($user_subscription["plan_active_date"]) ;
			
			if ( $user_subscription["plan_type"] == "trial" ) {
				$plan_expire_date = strtotime("+".$user_subscription["s_trial_duration"]." days", $plan_active_date ) ;
			}
			else {
				$plan_expire_date = strtotime("+30 days", $plan_active_date ) ;
			}
			
			
			
			// $diff = $current_time - $nextFive ;
			$subscription_diff = $plan_expire_date - $current_time ;
			
			?>
		<div style="position: relative;">
			<style>
				#countdown{
				width: 100%;
				height: 160px;
				text-align: center;
				background: #222;
				background-image: -webkit-linear-gradient(top, #222, #333, #333, #222);
				background-image: -moz-linear-gradient(top, #222, #333, #333, #222);
				background-image: -ms-linear-gradient(top, #222, #333, #333, #222);
				background-image: -o-linear-gradient(top, #222, #333, #333, #222);
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
				#countdown:before{
				content:"";
				width: 8px;
				height: 65px;
				background: #444;
				background-image: -webkit-linear-gradient(top, #555, #444, #444, #555); 
				background-image:    -moz-linear-gradient(top, #555, #444, #444, #555);
				background-image:     -ms-linear-gradient(top, #555, #444, #444, #555);
				background-image:      -o-linear-gradient(top, #555, #444, #444, #555);
				border: 1px solid #111;
				border-top-left-radius: 6px;
				border-bottom-left-radius: 6px;
				display: block;
				position: absolute;
				top: 48px; left: -10px;
				}
				#countdown:after{
				content:"";
				width: 8px;
				height: 65px;
				background: #444;
				background-image: -webkit-linear-gradient(top, #555, #444, #444, #555); 
				background-image:    -moz-linear-gradient(top, #555, #444, #444, #555);
				background-image:     -ms-linear-gradient(top, #555, #444, #444, #555);
				background-image:      -o-linear-gradient(top, #555, #444, #444, #555);
				border: 1px solid #111;
				border-top-right-radius: 6px;
				border-bottom-right-radius: 6px;
				display: block;
				position: absolute;
				top: 48px; right: -10px;
				}
				#countdown #tiles{
				position: relative;
				z-index: 1;
				}
				#countdown #tiles > span{
				width: 92px;
				max-width: 92px;
				font: bold 48px 'Droid Sans', Arial, sans-serif;
				text-align: center;
				color: #111;
				background-color: #ddd;
				background-image: -webkit-linear-gradient(top, #bbb, #eee); 
				background-image:    -moz-linear-gradient(top, #bbb, #eee);
				background-image:     -ms-linear-gradient(top, #bbb, #eee);
				background-image:      -o-linear-gradient(top, #bbb, #eee);
				border-top: 1px solid #fff;
				border-radius: 3px;
				box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.7);
				margin: 0 7px;
				padding: 18px 0;
				display: inline-block;
				position: relative;
				}
				#countdown #tiles > span:before{
				content:"";
				width: 100%;
				height: 13px;
				background: #111;
				display: block;
				padding: 0 3px;
				position: absolute;
				top: 41%; left: -3px;
				z-index: -1;
				}
				#countdown #tiles > span:after{
				content:"";
				width: 100%;
				height: 1px;
				background: #eee;
				border-top: 1px solid #333;
				display: block;
				position: absolute;
				top: 48%; left: 0;
				}
				#countdown .labels{
				width: 100%;
				height: 25px;
				text-align: center;
				position: absolute;
				bottom: 8px;
				}
				#countdown .labels li{
				width: 102px;
				font: bold 15px 'Droid Sans', Arial, sans-serif;
				color: #f47321;
				text-shadow: 1px 1px 0px #000;
				text-align: center;
				text-transform: uppercase;
				display: inline-block;
				}
			</style>
			<div id="countdown">
				<div class="labels" style="top: 5px;right: 5px;">
					<li>Subscription</li>
				</div>
				<div id='tiles'><?=empty(count($user_subscription)) ? "00" : $user_subscription["s_duration"]?></div>
				<div class="labels">
					<li>Days Left</li>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="card-header border-0">
			<h5 class="card-title">
				<i class="fas fa-map-marker-alt mr-1"></i>
				Top Visitor Locations
			</h5>
		</div>
		<div class="card-body">
			<table class="table top-location-table">
				<thead>
					<tr>
						<th scope="col">Country</th>
						<th scope="col">Visitors</th>
					</tr>
				</thead>
				<tbody>
				<?php
					// subcribers by country
					$query = $conn->query( " SELECT country , COUNT(country) AS subscriber_count , COUNT(country)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' GROUP BY country ORDER BY subscriber_count DESC " ) ;

					$query2=$conn->query("SELECT browser_family , COUNT(browser_family) AS subscriber_count , COUNT(browser_family)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' and ip='' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' AND ip=''GROUP BY browser_family ORDER BY subscriber_count DESC	");
					$subscriberByCountry = $query->fetch_all(MYSQLI_ASSOC) ;
					$subscriberByCountry2 = $query2->fetch_all(MYSQLI_ASSOC) ;

					$visitor_country = [] ;
					$country_percent = [] ;
					$browsers_name = [] ;

					foreach($subscriberByCountry as $value) {
						$visitor_country[] = $value["country"] ;
						$country_percent[] = round($value["subscriber_percent"],2) ;
					}
					foreach($subscriberByCountry2 as $value) {
						
						$browsers_name[] = $value["browser_family"] ;
						$browers_percent[] = round($value["subscriber_percent"],2) ;
					}

					$query = $conn->query( " SELECT country , COUNT(country) AS subscriber_count , COUNT(country)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' GROUP BY country ORDER BY subscriber_count DESC LIMIT 5" ) ;
						

					if ( $query->num_rows > 0 ) 
					{
						$subscriberByCountry = $query->fetch_all(MYSQLI_ASSOC) ;

						foreach($subscriberByCountry as $subscriberByCountry) {
						    ?>
							<tr>
								<td><?=$subscriberByCountry['country'];?></td>
								<td><?=round($subscriberByCountry['subscriber_percent'], 2);?>%</td>
							</tr>
							<?php
						}
					}
					else {
						?><tr><td colspan="2">No Data Found</td></tr><?php
					}
				?>
				</tbody>
			</table>

			<table class="table top-location-table d-none">
				<thead>
					<tr>
						<th scope="col">State</th>
						<th scope="col">Subscribers</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$subscriberByState = [] ;
					foreach($subscriberByState as $value) {
					    ?>
						<tr>
							<td><?=$value['state'];?></td>
							<td><?=round($value['subscriber_percent'], 2);?>%</td>
						</tr>
						<?php
					}
				?>
				</tbody>
			</table>
						
		
			<table class="table top-location-table d-none">
				<thead>
					<tr>
						<th scope="col">City</th>
						<th scope="col">Visitors</th>
					</tr>
				</thead>
				<tbody>
				<?php
					// subcribers by city
					
					$query = $conn->query( " SELECT city , COUNT(city) AS subscriber_count , COUNT(city)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' GROUP BY city ORDER BY subscriber_count DESC " ) ;

					$subscriberByCity = $query->fetch_all(MYSQLI_ASSOC) ;

					$visitor_cities = [] ;
					$cities_percent = [] ;

					foreach($subscriberByCity as $value) {
						$visitor_cities[] = $value["city"] ;
						$cities_percent[] = round($value["subscriber_percent"],2) ;
					}



					$query = $conn->query( " SELECT city , COUNT(city) AS subscriber_count , COUNT(city)*100/(SELECT COUNT(*) FROM website_visits WHERE manager_id = '".$user_id."' ) AS subscriber_percent FROM `website_visits` WHERE manager_id = '".$user_id."' GROUP BY city ORDER BY subscriber_count DESC LIMIT 5 " ) ;
					
					if ( $query->num_rows > 0 ) 
					{
						$subscriberByCity = $query->fetch_all(MYSQLI_ASSOC) ;
						foreach($subscriberByCity as $value) {
						    ?>
							<tr>
								<td><?=$value['city'];?></td>
								<td><?=round($value['subscriber_percent'], 2);?>%</td>
							</tr>
							<?php
						}
					}
					else {
					   	?><tr><td colspan="2">No Data Found</td></tr><?php
					}
				?>
				</tbody>
			</table>


		</div>
		<!-- /.card-body-->
		<div class="card-footer bg-transparent spin-content hide-content">
			<div class="row">
				<div class="col-4 text-center">
					<div id="sparkline-1"></div>
					<a href="javascript:void(0);">
						<div class="text-dark" onclick="showTopVisitor(1);loadVisitorChart1(['<?=implode("','", $visitor_country)?>'],['<?=implode("','", $country_percent)?>']);;loadVisitorChart2(['<?=implode("','", $browsers_name)?>'],['<?=implode("','", $browers_percent)?>']);">By Country</div>
					</a>
				</div>
				<!-- <div class="col-4 text-center">
					<div id="sparkline-2"></div>
					<a href="javascript:void(0);">
					<div class="text-dark" onclick="showTopVisitor(2)">By State</div>
					</a>
					</div> -->
				<div class="col-4 text-center">
					<div id="sparkline-3"></div>
					<a href="javascript:void(0);">
						<div class="text-dark" onclick="showTopVisitor(3);loadVisitorChart1(['<?=implode("','", $visitor_cities)?>'],['<?=implode("','", $cities_percent)?>']);">By City</div>
					</a>
				</div>
				<!-- ./col -->
			</div>
			<!-- /.row -->
		</div>
	</div>
	<div class="col-md-8">

		<?php

		?>
		<div id="visitor-location-box">
			<canvas id="visitor-location-chart"></canvas>
		</div>
		<div id="visitor2-location-box">
			<canvas id="visitor-location-chart"></canvas>
		</div>
	</div>
</div>

<script type="text/javascript">
loadVisitorChart1(['<?=implode("','", $visitor_country)?>'],['<?=implode("','", $country_percent)?>']);
// loadVisitorChart2(['<?=implode("','", $browsers_name)?>'],['<?=implode("','", $browers_percent)?>']);

function loadVisitorChart1(label,data) {

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
	            label: 'Visitor',
	            backgroundColor: '#7e7d7d,#ffc107',
	            // backgroundColor: ["rgb(255, 99, 132)","rgb(54, 162, 235)","rgb(255, 205, 86)"],
	            borderColor: '#7e7d7a',
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