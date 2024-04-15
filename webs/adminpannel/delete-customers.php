<?php 

include('config.php');
require_once('meta_details.php');
include('session.php');
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
				<div class="container-fluid content__up web_owners">
					<h1 class="mt-4">Delete Customers</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<?php
				
				 $qry="select * from admin_users where userstatus!='admin' AND email NOT IN ('makkpress@gmail.com','admin@makkpress.com')";
				 $cont_qry=mysqli_query($conn,$qry);
				 
				
				?>
				<div class="profile_tabs">
					<div> <button type="button" class="btn btn-primary btn_customer_delete" > Customer Delete</button>	</div>
					<div class="table_S">
					<table class="table speedytable">
						<thead>
							<tr>
							   <!-- <th> Select</th> -->
							   <th> #</th>
							   <th>Name</th>
								<th>Email</th>
								<th>Site</th>
								<th>Phone</th>
								<th>Date</th>
								<th>Ation</th>
					
								
							</tr>
						</thead>
						
						<tbody>
						<?php

						 $index=1;
						 while($run_qry=mysqli_fetch_array($cont_qry))
						 {
						$uid=$run_qry['id'];
						$websites = getTableData($conn, " boost_website ", " manager_id = '" . $uid . "'  ", "", 1);
						// print_r(expression)
				        $site = $websites[0]['website_url'];

						 ?>
							<tr class="class_wrap<?=$uid?>">
								<!-- <td><input type="checkbox"  class="checkbox_customer_delete" value="<?=$uid?>"></td> -->
								<td><?php echo $index++; ?></td>
								<td><?php echo $run_qry['firstname'];?></td>
								<td><?php echo $run_qry['email'];?></td>
								<td><?php echo $site;?></td>
								<td><?php echo $run_qry['phone'];?></td>
								<td><?php echo $run_qry['created_at'];?></td>
						
								
								<td class="button__td">
									<a href="javascript:void(0)" class="delete_customer" data-customer-id="<?=$uid?>" ><button type="button" class="btn btn-primary"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path></svg></button></a>
                           	
								</td>

								
							</tr>
							 <?php
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
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Core theme JS-->
		<script src="js/scripts.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
	
	$('.speedytable').DataTable({
        order: [[0, 'asc']],
    });

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
			// $( document ).ready(function() {
			// 	$("#sidebarToggle").click(function(){
      //           $("body").toggleClass("sb-sidenav-toggled");
      //         });
			// });
			
    $(".delete_customer").click(function(e){
    	var condition_delete=confirm('Are you sure you want to delete this customer?');
        if(condition_delete==true){
        	var cus_id=$(this).attr("data-customer-id");
        	// console.log(card_id);
				$.ajax({
				url: "delete-customer.php",
				type: "POST",
				dataType: "json",
				data: {
				id: cus_id,

				},
				success: function (data) {

					if (data.status == "done") {
						$(".class_wrap"+cus_id).hide();
 							$(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
						// setTimeout(window.location.reload(), 1000);
					    }else{
					    	$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					    }

					}
				});
            
        }
    });

    $(".btn_customer_delete").click(function(e){
    	if ($(".checkbox_customer_delete:checked").length != 0) {

    	// $(this).prop('disabled', false);

var customer_id = $(".checkbox_customer_delete:checked");
          var cus_id = [];
          for (let i = 0; i < customer_id.length; i++) {

            var website__id = customer_id[i];

// console.log($(website__id).val());
            cus_id.push($(website__id).val()); 


          }

    	var condition_delete=confirm('Are you sure you want to delete customer?');
        if(condition_delete==true){
        
        	// console.log(card_id);
				$.ajax({
				url: "delete-customer-specific.php",
				type: "POST",
				dataType: "json",
				data: {
				id: cus_id,

				},
				success: function (data) {

					if (data.status == "done") {
						for (var i in cus_id) {
							//console.log(cus_id[i]);

						$(".class_wrap"+cus_id[i]).hide();
						}
						 $(".alert-status").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

						// setTimeout(window.location.reload(), 1000);
					    }else{
					    	$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					    }

					}
				});
            
        }
    }else{
    	$(".alert-status").html('<div class="alert alert-danger alert-dismissible fade show" role="alert"> Checkbox can not be blank!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }
    });
       setInterval(check_function, 500);
    function check_function(){
        var checkbox = $(".checkbox_customer_delete:checked").length;
        if (checkbox>=1) {
          $(".btn_customer_delete").prop('disabled', false);
        }else{
          $(".btn_customer_delete").prop('disabled', true);

        }

    }
			</script>


</html>