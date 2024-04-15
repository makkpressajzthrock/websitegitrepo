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


	</head>
	<body class="custom-tabel">


		<div class="d-flex" id="wrapper">
			
			<?php require_once("inc/sidebar.php"); ?>

			<!-- Page content wrapper-->
			<div id="page-content-wrapper">
				
				<?php require_once("inc/topbar.php"); ?>



<?php $row_1=google_page_speed_insight("https://swasticlothing.com","desktop"); echo "<pre>"; print_r($row_1); ?>

				
			</div>
		</div>
		
	</body>
</html>

<script type="text/javascript">


	
</script>
