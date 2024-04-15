
<?php

include('config.php');
$conn = new mysqli( $host , $host_name , $password , $db_select );

require_once('inc/functions.php') ;

if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=""){
	// echo $_SERVER['HTTP_REFERER'];
	$ref = explode('?', $_SERVER['HTTP_REFERER'])[0];
	 
	if ( $ref == HOST_URL."adminpannel/managers.php" || $ref == HOST_URL."adminpannel/managers_test.php" || $ref == HOST_URL."adminpannel/unverified-managers.php" ){


		if($_SESSION['role']!='manager'){
			$_SESSION['adminlogin'] = $_SESSION['user_id']."|".$_SESSION['role'];
		}
			 
			$row = getTableData($conn, " admin_users ", " email ='" . base64_decode($_GET['loginas']) . "' ");

			 
			$_SESSION['user_id'] = $row['id']; ;  
			$_SESSION['role'] = $row['userstatus'];

			// die("location: " . HOST_URL . "adminpannel/project-dashboard.php?project=".$_GET['project']) ;
			print_r($_SESSION);
			header("location: " . HOST_URL . "adminpannel/project-dashboard.php?project=".$_GET['project']);
				die();

	}
	else{
		echo "You Are Not Allowed to login.";
	}

}

?>