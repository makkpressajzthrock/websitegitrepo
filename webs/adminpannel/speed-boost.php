<?php 

include('config.php');
include('session.php');
require_once('inc/functions.php') ;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$manager_id = $_SESSION['user_id'] ;

// 						//---------------------additional url---------------//
			$check_additional_url = getTableData( $conn , " manager_sites " , " manager_id = '$manager_id' " , "" ) ;

			// print_r($check_additional_url);





$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;
// print_r($row) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}

?>
<?php require_once("inc/style-and-script.php") ; ?>

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
				<div class="container-fluid">
					<?php require_once("inc/alert-status.php") ; ?>
					<h1 class="mt-4">Speed Boost</h1>

					<?php
						$data = getTableData( $conn , " boost_website " , " manager_id = '".$_SESSION['user_id']."' " ) ;
						
						// print_r($data) ;
					?>

					<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Form</a>
						</li>
						<li class="nav-item <?php if (empty(count($data))) { echo "disbale-tab" ; }?>">
							<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" onclick="showPageSpeed()">Google Page Speed</a>
						</li>
					</ul>
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
							<div class="speed-form">
								<form method="POST" id="additional_url_form">

									<input type="hidden" class="form-control" name="platform" value="shopify">
									<textarea id="deleted_urls" name="deleted_urls" style="display: none;"></textarea>
									<div class="form-group">
										<label for="websiteurl">Website URL</label>
										<small>(eg. abc.com)</small>
										<input type="text" class="form-control" id="websiteurl" name="websiteurl" required value="<?=(count($data)>0)? $data["website_url"]:""?>">
									</div>
									<div class="form-group">
										<label for="shopifyurl">Shopify Domain URL</label>
										<small>(eg. abc.myshopify.com)</small>
										<input type="text" class="form-control" id="shopifyurl" name="shopifyurl" required value="<?=(count($data)>0)? $data["shopify_url"]:""?>">
									</div>

									<input type="hidden" name="manager_id" value="<?=$manager_id?>">


									<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Add More URLs
</button>
<br>
<br>
<table class="table" style="width: 80%;">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Additional URLs</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
  	<?php $query = $conn->query("SELECT additional_url FROM manager_sites WHERE manager_id = '$manager_id'");

  	$i = 1;

  	while($row = $query->fetch_assoc())

  	{

  	?>
    <tr>
      <th scope="row"><?=$i?></th>
      <td><?=$row['additional_url']?></td>
      <td><button type="button" class="btn btn-danger">Delete</button></td>
    </tr>
   <?php $i++;}?>

  </tbody>
</table>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        							<div class="form-group">
										<label for="shopifyurl">Add More URL</label>
										<small>(upto 5 max)</small>
										<div id="add-domain" class="col-md-12 px-0 more-domains">
											<input type="text" class="form-control additional_urls" name="additional_url">
											<!-- <button type="button" id=btn_id class="btn btn-danger" data-box="domain-box" onclick="addDomain(this);"><i class="fa fa-plus" aria-hidden="true"></i></button> -->
										</div>
							
										<div class="col-md-12 px-0 domain-box">
									
										</div>
									</div> 


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" id="add_url" name="add_url" class="btn btn-primary">Add URL</button>


      </div>
    </div>
  </div>
</div>

									<!-- <button type="button" name="addmore" class="btn btn-primary">Add more URLs</button> -->
								</form>
							</div>
						</div>
						<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
							<div class="speed-goole">


								<div class="row">
									<div class="col-md-6">
										<div class="row">
											<div class="col-md-6">

												<h6>Old Speed</h6>



												<ol>
													<li>Desktop : 
														<div class="container">
															<div class="box">
															<div class="chart" data-percent="<?=$data["desktop_speed_old"]?>" ><?=$data["desktop_speed_old"]?></div>
															</div>
														</div>
													</li>
													<li>Mobile :
														<div class="container">
															<div class="box">
															<div class="chart" data-percent="<?=$data["mobile_speed_old"]?>" ><?=$data["mobile_speed_old"]?></div>
															</div>
														</div>
													</li>
												</ol>
											</div>
											<div class="col-md-6">
												<h6>Current/updated Speed</h6>
												<ol>
													<li>Desktop : 
														<div class="container">
															<div class="box">
															<div class="chart cd-data" data-percent="<?=$data["desktop_speed_new"]?>" ><span id="current-desktop"><?=$data["desktop_speed_new"]?></span></div>
															</div>
														</div>
													</li>
													<li>Mobile : 
														<div class="container">
															<div class="box">
															<div class="chart cm-data" data-percent="<?=$data["mobile_speed_new"]?>" ><span id="current-mobile"><?=$data["mobile_speed_new"]?></span></div>
															</div>
														</div>
													</li>
												</ol>
												<button type="button" data-website="<?=$data["id"]?>" class="btn btn-primary update-speed" >Update Record</button>
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

function showPageSpeed() {
	var desktop = $('.cd-data').attr("data-percent");
	var mobile = $('.cm-data').attr("data-percent");
	// desktop = Number(desktop) ;

	$('.cd-data').data('easyPieChart').update(0);
	$('.cm-data').data('easyPieChart').update(0);


	setTimeout(function(){
		$('.cd-data').data('easyPieChart').update(desktop);
		$('.cm-data').data('easyPieChart').update(mobile);
	},1000);

}

// function buttonmod7777(){

// 			var btn = document.getElementById('add_url');
// 	   btn.innerHTML = 'Please Wait';

// 	   $('#add_url').attr('disabled', true);

// }

$( document ).ready(function() {

	$('#additional_url_form').on('submit', function(e){

		e.preventDefault() ;

					var btn = document.getElementById('add_url');
	   btn.innerHTML = 'Please Wait';

	   $('#add_url').attr('disabled', true);


			$.ajax({

			type: 'POST',

			url: 'speed-boost-ajax.php',

			data: $('#additional_url_form').serialize(),

			success: function(response){
				console.log(response);
			}
});


	});

//     function addDomain(btn) {


// }

});


// function removeDomain(btn,id=null) {

// 	$(btn).parent().remove() ;

// 	if(id != null){

// if($('#deleted_urls').val()==""){
// $('#deleted_urls').append(id);
// }else{
// 	$('#deleted_urls').append(','+id)
// }
// 	}


// if($('#deleted_urls').val())

// 	var len = $(".more-domains").length ;
// 	if ( (len < 5) && ( $("button[data-box='domain-box']").length <= 0 ) ) {
// 			$("#add-domain").append('<button type="button" class="btn btn-danger" data-box="domain-box" onclick="addDomain(this);"><i class="fa fa-plus" aria-hidden="true"></i></button>') ;
// 	}
// }

	  		var len = $(".more-domains").length ;
	if ( len >= 5 ) {
		$("button[data-box='domain-box']").remove();
	}

	
</script>
