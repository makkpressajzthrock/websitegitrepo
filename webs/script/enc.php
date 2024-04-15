<?php

   header("Access-Control-Allow-Origin: *");
   header("Access-Control-Allow-Methods: POST");
include('../adminpannel/config.php');
  $result = file_get_contents('php://input');
  $response = json_decode($result);
$url = "/var/www/html/script/ecmrx/ecmrx";
$preurl = $response->encFn;
$store_id = base64_decode($response->encryptSx); // Let store id = 2

$user_id = base64_decode($response->enuSuId); 
// echo 'loading';
if(isset($response->encryptKey) && isset($response->encryptSx) && isset($response->encryptdT) && isset($response->encFn)  &&     !empty($response->encryptKey) && !empty($response->encryptSx) && !empty($response->encryptdT) && !empty($response->encFn)  ){
 " SELECT * FROM boost_website WHERE id = $store_id and website_url = '$response->encSu' ";
   $query = $conn->query(" SELECT * FROM boost_website WHERE id = $store_id and website_url = '$response->encSu' ") ;

   if($data = $query->fetch_assoc() ) 
    {
 		// echo 'Viled';

	}
	else{
	// echo 'error loading...';
  die;
// 			$preurl = $response->encFn ; 
// // echo 'renaming';
// 		 $url = $url."_".$store_id;
// // echo '<br>';
// 		$source1 = $url.'/'.$preurl.'_1.js';
// 		$source2 = $url.'/'.$preurl.'_2.js';
// 		$source3 = $url.'/'.$preurl.'_3.js';
// // echo '<br>';
// 		$dest1 = $url.'/'.$preurl.'_attack_1_'.date("Y-m-d--h-i-s-a").'.js';
// 		$dest2 = $url.'/'.$preurl.'_attack_2_'.date("Y-m-d--h-i-s-a").'.js';
// 		$dest3 = $url.'/'.$preurl.'_attack_3_'.date("Y-m-d--h-i-s-a").'.js';	

// if(rename($source1,$dest1)){
// }	
// if(rename($source2,$dest2)){
// }	
// if(rename($source3,$dest3)){
// }


$store_id = base64_decode($response->encryptSx); 

      $preurl = $response->encFn ; 
      $url = "/var/www/html/script/ecmrx/ecmrx";
     $url = $url."_".$store_id;
// echo '<br>';
     $source1 = $url.'/'.$preurl.'_1.js';
    $source2 = $url.'/'.$preurl.'_2.js';
    $source3 = $url.'/'.$preurl.'_3.js';


  $myfile = fopen($source1, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);

  $myfile = fopen($source2, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);

    $myfile = fopen($source3, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);




	}

}
else{
	// echo 'error loading..ss.';
die;
$store_id = base64_decode($response->encryptSx); 

      $preurl = $response->encFn ; 
      $url = "/var/www/html/script/ecmrx/ecmrx";
     $url = $url."_".$store_id;
// echo '<br>';
     $source1 = $url.'/'.$preurl.'_1.js';
    $source2 = $url.'/'.$preurl.'_2.js';
    $source3 = $url.'/'.$preurl.'_3.js';


  $myfile = fopen($source1, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);

  $myfile = fopen($source2, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);

    $myfile = fopen($source3, "w") or die("Unable to open file!");
  $txt = '';
  fwrite($myfile, $txt); 
  fclose($myfile);	
 // die;
// 			$preurl = $response->encFn ; 
// // echo 'renaming';
// 		 $url = $url."_".$store_id;
// // echo '<br>';
// 		$source1 = $url.'/'.$preurl.'_1.js';
// 		$source2 = $url.'/'.$preurl.'_2.js';
// 		$source3 = $url.'/'.$preurl.'_3.js';
// // echo '<br>';
// 		$dest1 = $url.'/'.$preurl.'_attack_1_'.date("Y-m-d--h-i-s-a").'.js';
// 		$dest2 = $url.'/'.$preurl.'_attack_2_'.date("Y-m-d--h-i-s-a").'.js';
// 		$dest3 = $url.'/'.$preurl.'_attack_3_'.date("Y-m-d--h-i-s-a").'.js';

 
// 	// echo	$preurl = $response->encFn ; 

// if(rename($source1,$dest1)){
// }	
// if(rename($source2,$dest2)){
// }	
// if(rename($source3,$dest3)){
// }	
 

}

// rename($url.$preurl.'/'.$preurl.'.js',$url.$preurl.'/'.$preurl.'_expired_'.date("Y-m-d--h-i-s-a").'.js')
?>