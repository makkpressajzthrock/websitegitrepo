<?php 

require('../adminpannel/config.php');


// print_r($data);
if(isset($_POST)){ 	

	// print_r($data);
	$url = $_POST['url'];
	$user = $_POST['user'];

	 // echo " SELECT id FROM `boost_website` WHERE website_url = '$url' and manager_id = '$user' ";
			$query = $conn->query(" SELECT id FROM `boost_website` WHERE website_url = '$url' and manager_id = '$user' ");

			if ($query->num_rows > 0) {
				 echo 0;
			}
			else{
 				echo 1;
			}	
   
}
?>