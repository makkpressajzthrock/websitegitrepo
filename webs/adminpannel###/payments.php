<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
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
				<div class="container-fluid content__up web_owners">
					<h1 class="mt-4">Payment</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<?php
				
				//  $qry="select * from user_subscriptions";
				//  $cont_qry=mysqli_query($conn,$qry);
				 
				
				?>
				<div class="profile_tabs">
					<div class="table_S">
					<table class="table speedytable" id="paymentTable">
						<thead>
							<tr>
							   <th>Date</th>
							   <th>Name</th>
								<th>Email</th>
								<th> Payment Method</th>
								<th>Paid Amount</th>
								<th>Start Plan Period</th>
								<th>End Plan Period</th>
								<th>Action</th>
								
							</tr>
						</thead>
						
					
					</table>
				</div>
			</div>
				</div>
			</div>
		</div>

	</body>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Core theme JS-->
		<script src="js/scripts.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	
	$('.speedytable').DataTable(
				 {
            		"order": [[ 0, "desc" ]]
       			 } 
		);

	} );

	
</script>
<script>
			$( document ).ready(function() {
				var dropdown = $('.nav-item.dropdown');
				var aUser = $('.user_name');
				var dropUser = $('.user__dropdown')
					dropdown.removeClass('show');
					aUser.attr("aria-expanded","false");
					dropUser.removeClass('show');
			});
			
		</script>

		<script>
			$( document ).ready(function() {
				$("#sidebarToggle").click(function(){
                $("body").toggleClass("sb-sidenav-toggled");
              });
			});
			

			</script>


</html>


<!-- //123 -->
<script>
    $(document).ready(function(){
        $('#paymentTable').DataTable({
            "lengthChange": true,
            "processing":true,
            "serverSide":true,
            "ordering":false,
            "searching":true,
            "order":[],
            "lengthMenu": [10,50,100,500],
            "bDestroy": true,
            
            "ajax":{
              url:"./pagination.php",
              type:"POST",
			  data : {
				'adminPaymentPage' : 'adminPaymentPage'
			  },
              dataType:"json"
            },
           
            "columnDefs":[
              {
                "targets":'_all',
                "visible": true,
                "orderable":true,
              },
            ],
             
             "dom": 'lBfrtip',
            //  "buttons": ['excelHtml5'], 

            "pageLength": 10
          });

		
  })
     
</script>