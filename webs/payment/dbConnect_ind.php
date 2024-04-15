<?php  
// Connect with the database  
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);  
  
// Display error if failed to connect  
if ($db->connect_errno) {  
    printf("Connect failed: %s\n", $db->connect_error);  
    exit();  
}


  $sele= "SELECT * FROM `payment_gateway` WHERE id='1' ";
 $sele_run= mysqli_query($db,$sele);
 $result= mysqli_fetch_array($sele_run);

  $secret_key= $result['secret_key'];
  $public_key= $result['public_key'];

// USD
  if($country != "IND"){
    $secret_key= "sk_test_51Laxg8D16Q5JOqS2hRB6fnXUYFLOg9sTxjqBShVdY42RoVZyrXO9RsgnvWxn5zRhioHGiyKzPB4UCWx90tzoakRq00ZEq5QlTf";
    $public_key= "pk_test_51Laxg8D16Q5JOqS2su3TObPnAVIIrAZ1WAXWztxZwF58r2wZpl1a4cI55np3ERPErsnkH19NqsnldFYNJUIuLsQO00DYPhkWvZ";
    define('STRIPE_CURRENCY', 'USD'); 

  }
  else{
 // INR
    $secret_key= "sk_test_51N6rufSAQd0TueXdVpBv2vzyM4XiI296ZGHOrOy277AyFbS4K47bkkzfIDD4j1f7EdT1iBtHyRWPFlrnhgv03sfl00Wdv4lBPs";
    $public_key= "pk_test_51N6rufSAQd0TueXdWQ354sZnsT6L6l4ZiIcaWywsLRKKB6sYjcQXtq8HLOV8bawhqEdjcr7FFPXbymQjgYka0CTL00oRrYDC3F";
    define('STRIPE_CURRENCY', 'INR'); 
  }


  define('STRIPE_API_KEY', $secret_key); 
  define('STRIPE_PUBLISHABLE_KEY', $public_key); 


// echo constant("STRIPE_API_KEY"); 
// echo "<br>";
// echo constant("STRIPE_PUBLISHABLE_KEY");
// echo "<br>";
// echo constant("STRIPE_CURRENCY");
