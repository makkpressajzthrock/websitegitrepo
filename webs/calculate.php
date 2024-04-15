<?php
include("adminpannel/config.php") ;
$count = $_REQUEST['count'];
$subs = $_REQUEST['subs'];

// echo $count;
// echo '<br>';
// echo $subs;
$price = 0;
if(isset($_REQUEST['subs']) && !empty($_REQUEST['subs'])){
   $query = $conn->query(" SELECT * FROM `plans` WHERE status = 1 and id = $subs") ;
    while($data = $query->fetch_assoc() ) 
      {
      	$price = $data['s_price'];
      }


   $query_s = $conn->query(" SELECT * FROM `discount`") ;
    while($data_s = $query_s->fetch_assoc() ) 
      {
      	
      	$d = $data_s['discount'];
      	if ($data_s['sites'] == $count ) {
      		# code...
    			echo $totalValue = ($price - ($price * $d) / 100) * $count;
	      		// echo number_format((float)$totalValue, 2, '.', '');
      		exit;
      	}
      	elseif(strpos($data_s['sites'], "+") !== false)
      	{
      		$p = str_replace("+","",$data_s['sites']);
      		if($count >= $p){
    			echo $totalValue = ($price - ($price * $d) / 100) * $count;
	      		// echo number_format((float)$totalValue, 2, '.', '');
      		exit;
      		}

      	}


      }


  }

// echo $price;


?>