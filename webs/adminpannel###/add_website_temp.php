<?php 

require('config.php');

// Read $_GET value

$data = json_decode(file_get_contents("php://input")) ;

// print_r($data);
if(isset($_POST)){ 	

	// print_r($data);
	
	// die;
	$user_id = $data->user_id;

			$query = $conn->query(" SELECT * FROM `boost_website_temp` WHERE manager_id = '$user_id' ");


			if ($query->num_rows > 0) {
				echo $query->id;
				$d = $query->fetch_assoc();

				if($d['platform'] == "Shopify")
				{
					if($data->field == "shopify_url"){
					$a = explode(",", $data->data);
					 echo   $sql = "UPDATE boost_website_temp SET ".$data->field." = '".$a[0]."' , updated_at = now() WHERE manager_id = '".$data->user_id."' ";
					}
					else{
					 echo   $sql = "UPDATE boost_website_temp SET ".$data->field." = '".$data->data."' ,shopify_url = '', updated_at = now() WHERE manager_id = '".$data->user_id."' ";

					}

				}
								
				else{

					if($d['platform'] == 'Other' && $data->field == "platform_name" || $d['platform'] == 'Custom Website' && $data->field == "platform_name" ){
	   					echo $sql = "UPDATE boost_website_temp SET ".$data->field." = '".$data->data."' ,shopify_url = '', updated_at = now() WHERE manager_id = '".$data->user_id."'  ";
	   				}
	   				else{
	   					echo $sql = "UPDATE boost_website_temp SET ".$data->field." = '".$data->data."' ,shopify_url = '', platform_name = '', updated_at = now() WHERE manager_id = '".$data->user_id."'  ";
	   				}
				
				}
				

	    		$conn->query($sql); 
			}
			else{

   			echo $sql = "INSERT INTO boost_website_temp(manager_id, ".$data->field.") VALUES('".$data->user_id."','".$data->data."')";
// die;
	    		$conn->query($sql); 

			}	
   
}
?>