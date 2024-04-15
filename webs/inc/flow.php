<?php 

require('../adminpannel/config.php');

// Read $_GET value

$data = json_decode(file_get_contents("php://input")) ;

// print_r($data);
if(isset($_POST)){ 	

	// print_r($data);
	$user_id = $data->user_id;

			$query = $conn->query(" SELECT id,platforms FROM `flow_step` WHERE user_id = '$user_id' ");

			if ($query->num_rows > 0) {
				// echo $query->id;
				$d = $query->fetch_assoc();


				if($d['platforms'] == "Shopify")
				{
					$a = explode(",", $data->data);
				    $sql = "UPDATE flow_step SET ".$data->flow." = '".$a[0]."' , shopify_domain_url = '".$a[1]."', platforms_name = '".$data->plateform."' , updated_at = now() WHERE user_id = '".$data->user_id."' ";
				}
				else{
   				 $sql = "UPDATE flow_step SET ".$data->flow." = '".$data->data."' ,shopify_domain_url = '', platforms_name = '".$data->plateform."' , updated_at = now() WHERE user_id = '".$data->user_id."'  ";
				}


				

	    		$conn->query($sql); 
			}
			else{

   				$sql = "INSERT INTO flow_step(user_id, ".$data->flow.") VALUES('".$data->user_id."','".$data->data."')";

	    		$conn->query($sql); 

			}	
   
}
?>