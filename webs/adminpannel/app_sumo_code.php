<?php 

include('config.php');
require_once('meta_details.php');
require_once('inc/functions.php') ;
   
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
				<div class="container-fluid content__up sumo_code">
					<h1>App Sumo Code</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="profile_tabs">
					<div class="download_csv">
						<button type="button" class="csv_generate btn btn-primary" id="csv_generate"><a href="generateCsv.php">Download CSV</a></button><br>
					</div>
					<div class="app_sumo_code_div">
						<form class="myform" action="app_sumo_code.php" method="post">
							<div class="generate_sumo_code float-right">
								<button type="submit" name="sumo_generate" class="sumo_generate btn btn-primary" id="sumo_generate">Generate Sumo Code</button>
							</div>
							<div class="generate_sumo_code_num">
								<label>Number Of Sumo Code Should Generated:</label>
								<input type="number" name="no_of_code" min="1" max="100" class="no_of_code" id="no_of_code" required>
							</div>
						</form>
					</div>
					<div class="table_S">
						<table id="appSumoTable" class="table">
							<thead>
								<tr>
									<td>S.No</td>
									<td>App Sumo Code</td>
									<td>Date</td>
									<td>Code Used</td>
									<td>Action</td>
								</tr>
							</thead>
							
						</table>
					</div> 
					</div>
				</div>
			</div>
		</div>
		<?php
			if (isset($_POST['sumo_generate'])) 
			{
			 	$count_of_code = $_POST['no_of_code'];
			    $i = 0; 
			    for ($i = 0; $i <$count_of_code; $i++) 
			    {
			    	$date = date("Y/m/d"." "."h:i:s");
			    	$bytes = random_bytes(25);
					$Code =  bin2hex($bytes);
					$insertcode = "INSERT INTO sumo_code(`sumo_code`,`date`) VALUES('$Code','$date')";
					$execute = mysqli_query($conn,$insertcode);

				}

				header('Location: '.$_SERVER['REQUEST_URI']);
			}	
		?>
		
	</body>
	<script>
		$(document).ready(function () {
	        $('#mytable').DataTable();
	    });	

	</script>
</html>
<script type="text/javascript">
	//123
	$(document).on('click','.remove',function(e){
        var id = $(this).attr("data-id");

	var conm= confirm('Are you sure to remove this record ?');
      
// alert(conm);	  
	   if(conm!=true){
          e.preventDefault();
		 
	   }
	   
	  
	   
    });


</script>

<!-- //123 -->
<script>
    $(document).ready(function(){
        $('#appSumoTable').DataTable({
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
				'adminAppSumoPage' : 'adminAppSumoPage'
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

