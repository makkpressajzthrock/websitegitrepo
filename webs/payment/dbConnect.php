<?php  
// Connect with the database  
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);  
  
// Display error if failed to connect  
if ($db->connect_errno) {  
    printf("Connect failed: %s\n", $db->connect_error);  
    exit();  
}

// host define
define('HOST_URL', 'https://websitespeedy.com/'); 

// USD
  if($country != "IND"){

  $sele= "SELECT * FROM `payment_gateway` WHERE id='1' ";
 $sele_run= mysqli_query($db,$sele);
 $result= mysqli_fetch_array($sele_run);

  $secret_key= $result['secret_key'];
  $public_key= $result['public_key'];
    define('STRIPE_CURRENCY', 'USD'); 

  }
  else{
 // INR

  $sele= "SELECT * FROM `payment_gateway` WHERE id='2' ";
 $sele_run= mysqli_query($db,$sele);
 $result= mysqli_fetch_array($sele_run);

  $secret_key= $result['secret_key'];
  $public_key= $result['public_key'];
    define('STRIPE_CURRENCY', 'INR'); 
  }

  define('STRIPE_API_KEY', $secret_key); 
  define('STRIPE_PUBLISHABLE_KEY', $public_key); 


function getGetway($loc,$db){

  if($loc=="IND"){
    $sele= "SELECT * FROM `payment_gateway` WHERE id='2' ";
    $sele_run= mysqli_query($db,$sele);
    $result= mysqli_fetch_array($sele_run);
    $secret_key= $result['secret_key'];
    $public_key= $result['public_key'];    
    return ["STRIPE_API_KEY"=>$secret_key, "STRIPE_PUBLISHABLE_KEY"=> $public_key, "STRIPE_CURRENCY"=>"INR"];

  }
  else{
    $sele= "SELECT * FROM `payment_gateway` WHERE id='1' ";
    $sele_run= mysqli_query($db,$sele);
    $result= mysqli_fetch_array($sele_run);
    $secret_key= $result['secret_key'];
    $public_key= $result['public_key'];
    return ["STRIPE_API_KEY"=>$secret_key, "STRIPE_PUBLISHABLE_KEY"=> $public_key, "STRIPE_CURRENCY"=>"USD"];
  }


}


// echo constant("STRIPE_API_KEY"); 
// echo "<br>";
// echo constant("STRIPE_PUBLISHABLE_KEY");
// echo "<br>";
// echo constant("STRIPE_CURRENCY");
