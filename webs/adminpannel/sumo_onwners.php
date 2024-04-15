<?php 

include('config.php');
require_once('meta_details.php');
require_once('inc/functions.php') ;
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
				<div class="container-fluid content__up sumo_owners_s">
					<h1 class="mt-4"> App Sumo Customers</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					
					<div class="codesData profile_tabs">
						<div class="table_S">
						<table id="appSumoCustomerTable" class="table">
							<thead>
								<tr>
									<td>S.No</td>
									<td>Fullname</td>
									<td>Email</td>
									<td>Phone No</td>
									<th>Website</th>
									<th>Status</th>
									<th>sumo code</th>
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
	<script>

		// $(document).ready(function () {
	    //     $('#mytable').DataTable();
	    // });	

	
	</script>
	<script type="text/javascript">
	$(document).ready(function() {
	//    $(".speedy-table").DataTable();
	//123
        $(document).on('click','.manager-status-toggle',function(){
	   	// $(".manager-status-toggle").click(function(){
	   		var owner = $(this).attr("data-owner") ;
	   		var checked = $(this).prop("checked") ;
	   		var status = 0 ;
	   		if ( checked ) { status = 1; }

	   		$.ajax({
	   			url:"inc/update-owner-status.php" ,
	   			method:"POST",
	   			dataType:"JSON",
	   			data:{owner:owner,status:status}
	   		}).done(function(response){

	   			if ( response == 1 ) {
	   				$(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">Website Owner active status is updated successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   			}
	   			else {
	   				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Operation Failed.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   			}

	   		}).fail(function(){
	   			console.log("error") ;
	   		});
	   	});
	});
</script>


</html>


<!-- //123 -->
<script>
    $(document).ready(function(){
        $('#appSumoCustomerTable').DataTable({
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
				'adminAppSumoCustomerPage' : 'adminAppSumoCustomerPage'
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

