<?php 

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('config.php');
require_once('meta_details.php');
include('session.php');
require_once('inc/functions.php') ;
$row = getTableData($conn, " admin_users ", " id ='" . $_SESSION['user_id'] . "' AND userstatus LIKE '" . $_SESSION['role'] . "' ");
//echo'<pre>';print_r($row) ;die;
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$ip = $_SERVER['REMOTE_ADDR'];

if ($_SESSION['role'] == "manager") {
	header("location: " . HOST_URL . "adminpannel/dashboard.php");
	die();
}

if (empty(count($row))) {
	header("location: " . HOST_URL . "adminpannel/");
	die();
}


if ( isset($_POST["save-changes"]) ) {

	

	foreach ($_POST as $key => $value) {
		$_POST[$key] = $conn->real_escape_string($value) ;
	}
	extract($_POST) ;

	if ( empty($fname) || empty($lname) || empty($phone) ) {
		$_SESSION['error'] = "Please fill all fields!" ;
	}
	else {

		$columns = " firstname = '$fname' , lastname = '$lname' , phone = '$phone' " ;

		if ( updateTableData( $conn , " admin_users " , $columns , " id = '".$_SESSION['user_id']."' " ) ) {
			$_SESSION['success'] = "Profile details are updated successfully!" ;
		}
		else {
			$_SESSION['error'] = "Operation failed!" ;
			$_SESSION['error'] = "Error: " . $conn->error;
		}
	}

	header("location: ".HOST_URL."adminpannel/edit-profile.php") ;
	die() ;
}


$row = getTableData( $conn , " admin_users " , " id ='".$_SESSION['user_id']."' AND userstatus LIKE '".$_SESSION['role']."' " ) ;

if ( empty(count($row)) ) {
	header("location: ".HOST_URL."adminpannel/");
	die() ;
}


$platform =	getTableData( $conn , " boost_website " , "" , "group by platform" , 1 ) ;
// echo "<pre>";
// print_r($platform); die;
 
$pltform = "<select id='selectPlatform' class='p-2 mr-3'><option>All</option>";
foreach ($platform as $key => $pl) {
	# code...
$pltform .= "<option value='".$pl['platform']."'>".$pl['platform']."</option>";
}
$pltform .= "</select>";


?>
<?php require_once("inc/style-and-script.php") ; ?>
		<script type="text/javascript">
			function status_active(btn){
	   	
	   		var owner = $(btn).attr("data-owner") ;
	   		var checked = $(btn).prop("checked") ;
	   		var status = 0 ;
	   		if ( checked ) { status = 1; }

	   		$.ajax({
	   			url:"inc/update-owner-status.php" ,
	   			method:"POST",
	   			dataType:"JSON",
	   			data:{owner:owner,status:status}
	   		}).done(function(response){

	   			if ( response.status == "done" ) {
	   				$(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+ response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   				$(window).scrollTop(0);
	   			}
	   			else {
	   				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Operation Failed.'+ response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   			}

	   		}).fail(function(){
	   			console.log("error") ;
	   		});
	   	}


			function status_active_email(btn){
	   	
	   		var owner = $(btn).attr("data-owner") ;
	   		var checked = $(btn).prop("checked") ;
	   		var status = 0 ;
	   		if ( checked ) { status = 1; }

	   		$.ajax({
	   			url:"inc/update-email-status.php" ,
	   			method:"POST",
	   			dataType:"JSON",
	   			data:{owner:owner,status:status}
	   		}).done(function(response){

	   			if ( response.status == "done" ) {
	   				$(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+ response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   				$(window).scrollTop(0);
	   			}
	   			else {
	   				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Operation Failed.'+ response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   			}

	   		}).fail(function(){
	   			console.log("error") ;
	   		});
	   	}

		</script>
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
					<h1 class="mt-4">Website Owners</h1>

					<?php require_once("inc/alert-status.php") ; ?>
                    <div class="profile_tabs">
					<div class="table_S">
					<table  id="myTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Fullname</th>
								<th>Email</th>

								<th>Sent Code</th>
								<th>Customer Type</th>
								<th>Views/Steps</th>
								<th>Plan Type</th>
								<th class="set__max_width">Phone No</th>
								<th>Platform</th>
								<th>Website</th>
								<!-- <th>Plan Status</th> -->
								<th>Created At</th>
								<!-- <th>Expired At</th> -->
								<th>Email</th>
								<th>Status</th>
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
<script type="text/javascript">
	//123
	// $(document).ready(function() {
	// //    $("#myTable").DataTable();
	//    $(".dataTables_length").after("<div style='margin-left:200px;'>Select Plateform: <?=$pltform?><a href='export_manager.php?platform=All' class='btn btn-primary export_managers'>Export</a></div>");




	//    $("#selectPlatform").change(function(){
	//    		$(".export_managers").attr("href","export_manager.php?platform="+$(this).val());	
	//    });
	
	// });
</script>

<script>
	//123-start
$(document).ready(function() {
    $(document).on("click",'.view-count-button', function() {
        var button = $(this);
        button.prop("disabled", true);
        addLoaderButtonJS(button);

        var user_id = button.data("user_id");

        $.ajax({
            type: "POST",
            url: "inc/count_views.php",
            data: { "user_id": user_id },
            dataType: "text",
            success: function(data) {
                console.log(data);
                button.prop("disabled", false);
                button.html("Views");
                Swal.fire({
                    html: data,
                    showConfirmButton: false,
                    width: '50%',
                    height: '50%',
                    showCloseButton: true,
                    allowOutsideClick: false
                });
            }
        });
    });
});

function addLoaderButtonJS(selector) {
    console.log("adding Loader");
    selector.html('<svg xmlns="http://www.w3.org/2000/svg" style="background:transparent; height:24px;width: auto;" viewBox="0 0 105 105" fill="#fff" style="&#10;    background: #000;&#10;"> <circle cx="12.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="0s" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="12.5" cy="52.5" r="12.5" fill-opacity=".5"> <animate attributeName="fill-opacity" begin="100ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="300ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="52.5" r="12.5"> <animate attributeName="fill-opacity" begin="600ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="12.5" r="12.5"> <animate attributeName="fill-opacity" begin="800ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="52.5" r="12.5"> <animate attributeName="fill-opacity" begin="400ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="12.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="700ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="52.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="500ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> <circle cx="92.5" cy="92.5" r="12.5"> <animate attributeName="fill-opacity" begin="200ms" dur="1s" values="1;.2;1" calcMode="linear" repeatCount="indefinite"/> </circle> </svg>');
}


	//123-start
$(document).ready(function() {
    $(document).on("click",'.plan-count-button', function() {
        var button = $(this);
		var btnHtml = button.html();
        button.prop("disabled", true);
        addLoaderButtonJS(button);

        var user_id = button.data("cus_id");

        $.ajax({
            type: "POST",
            url: "inc/view_plan_count.php",
            data: { 
				"user_id": user_id,
				"planType" : "PlanType"
			 },
            dataType: "text",
            success: function(data) {
                console.log(data);
                button.prop("disabled", false);
                button.html(btnHtml);
                Swal.fire({
                    html: data,
                    showConfirmButton: false,
                    width: '50%',
                    height: '50%',
                    showCloseButton: true,
                    allowOutsideClick: false
                });
            }
        });
    });
});



$("body").on("click", ".delete_account", function(){

	let email = $(this).attr("email");

		Swal.fire({
		  title: 'Are you sure !',
		  html: `You want to delete <b>${email}</b>.
		  		<br><br>
		  		<input class="form-control" type="password" id="delete_pass" placeholder="Please Enter Delete Passcode.">

		  `,
		  icon: 'question',	
		  showCancelButton: true,
		  confirmButtonText: 'Yes Delete',
		}).then((result) => {
		  /* Read more about isConfirmed, isDenied below */
		if (result.isConfirmed) {

			var passcode = $("#delete_pass").val();
	   		$.ajax({
	   			url:"inc/delete_user.php" ,
	   			method:"POST",
	   			dataType:"JSON",
	   			data:{email:email,passcode:passcode,userAgent:"<?=$userAgent?>",ip:"<?=$ip?>"}
	   		}).done(function(response){

	   			if ( response.status == "done" ) {
	   				$(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+ response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   				Swal.fire(
					  'Deleted!',
					  'User has been deleted successfully',
					  'Success'
					)
	   				window.location.reload();

	   			}
	   			else if(response.status=="Inviled"){
	   				Swal.fire(
					  'Oho!',
					  response.message,
					  'error'
					)
	   			}
	   			else {
	   				$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Operation Failed.'+ response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>') ;
	   			}

	   		}).fail(function(){
	   			console.log("error") ;
	   		});


 		}
		})

});
</script>

<!-- //123 -->
<script>
    $(document).ready(function(){
        $('#myTable').DataTable({
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
				'adminManagerPage' : 'adminManagerPage'
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

			$(".dataTables_length").after("<div style='margin-left:200px;'>Select Plateform: <?=$pltform?><a href='export_manager.php?platform=All' class='btn btn-primary export_managers'>Export</a></div>");




			$("#selectPlatform").change(function(){
					$(".export_managers").attr("href","export_manager.php?platform="+$(this).val());	
			});

  })



    
</script>

</html>