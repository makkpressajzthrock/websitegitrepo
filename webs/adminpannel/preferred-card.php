 <?php
 include('config.php');
require_once('inc/functions.php') ;

$cids 			= $_POST['cid'];
$set 			= $_POST['set'];
$Mid 			= $_POST['Mid'];

$zero="0";






$update= mysqli_query($conn,"update payment_method_details set prefered_card='$zero' where manager_id='$Mid'");
 $update= mysqli_query($conn,"update payment_method_details set prefered_card='$set' where id='$cids'");
 
 ?>
 