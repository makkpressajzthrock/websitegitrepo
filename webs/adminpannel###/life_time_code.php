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
					<h1>Dealify coupon code</h1>
					<?php require_once("inc/alert-status.php") ; ?>
					<div class="profile_tabs">
					<div class="download_csv">
						<button type="button" class="csv_generate btn btn-primary" id="csv_generate"><a href="generateCsvLife.php">Download CSV</a></button><br>
					</div>
					<div class="app_sumo_code_div">
						<form class="myform" method="post">
							<div class="generate_sumo_code float-right">
								<button type="submit" name="sumo_generate" class="sumo_generate btn btn-primary" id="sumo_generate">Generate Codes</button>
							</div>
							<div class="generate_sumo_code_num">
								<label>Number Of Codes Should Generated:</label>
								<input type="number" name="no_of_code" min="1" max="100" class="no_of_code" id="no_of_code" required>
							</div>
						</form>
					</div>
					<div class="table_S">
						<table id="mytable" class="table">
							<thead>
								<tr>
									<td>S.No</td>
									<td>Dealify coupon code</td>
									<td>Date</td>
									<td>Code Used</td>
									<td>Action</td>
								</tr>
							</thead>
							<tbody>
					<?php
						$fetchCode = "SELECT * FROM life_time";
						$fetchResult = mysqli_query($conn,$fetchCode);
						$sno = 0;
						while ($num = mysqli_fetch_assoc($fetchResult)) 
						{ ?>
								<tr>
									<td><?php echo ($sno+1);?></td>
									<td><?php echo $num['sumo_code'];?></td>
									<td><?php          $timedy=$num['date'];
                                           $vartime = strtotime($timedy);

                                             $datetimecon= date("F d, Y ", $vartime);  echo $datetimecon ; ?></td>
									<td><?=($num['used']==1)?"Used":"Unused";?></td>
									<td>
								
									<a  data-id='<?=$num['id'] ?>' href="<?=HOST_URL."adminpannel/delete-life-code.php?delete=".base64_encode($num['id'])?>" class="btn btn-primary remove"><svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M160 400C160 408.8 152.8 416 144 416C135.2 416 128 408.8 128 400V192C128 183.2 135.2 176 144 176C152.8 176 160 183.2 160 192V400zM240 400C240 408.8 232.8 416 224 416C215.2 416 208 408.8 208 400V192C208 183.2 215.2 176 224 176C232.8 176 240 183.2 240 192V400zM320 400C320 408.8 312.8 416 304 416C295.2 416 288 408.8 288 400V192C288 183.2 295.2 176 304 176C312.8 176 320 183.2 320 192V400zM317.5 24.94L354.2 80H424C437.3 80 448 90.75 448 104C448 117.3 437.3 128 424 128H416V432C416 476.2 380.2 512 336 512H112C67.82 512 32 476.2 32 432V128H24C10.75 128 0 117.3 0 104C0 90.75 10.75 80 24 80H93.82L130.5 24.94C140.9 9.357 158.4 0 177.1 0H270.9C289.6 0 307.1 9.358 317.5 24.94H317.5zM151.5 80H296.5L277.5 51.56C276 49.34 273.5 48 270.9 48H177.1C174.5 48 171.1 49.34 170.5 51.56L151.5 80zM80 432C80 449.7 94.33 464 112 464H336C353.7 464 368 449.7 368 432V128H80V432z"></path></svg></a> 
									
								</td>
								</tr>
					<?php
						$sno++;		
						}
					?>	
							</tbody>
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

			 	// print_r($_POST['sumo_generate']);
			 	// die();
			    $i = 0; 
			    for ($i = 0; $i <=$count_of_code; $i++) 
			    {
			    	$date = date("Y/m/d"." "."h:i:s");
			    	$bytes = random_bytes(25);
					$Code =  bin2hex($bytes);
				$insertcode = "INSERT INTO life_time(`sumo_code`,`date`) VALUES('$Code','$date')";
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
    $(".remove").click(function(e){
        var id = $(this).attr("data-id");

var conm= confirm('Are you sure to remove this record ?');
      
// alert(conm);	  
	   if(conm!=true){
          e.preventDefault();
		 
	   }
	   
	  
	   
    });


</script>
