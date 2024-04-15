<?php 

session_start(); 
require_once("../adminpannel/config.php") ; 


if(isset($_SESSION['user_id']) && !empty($_SESSION["user_id"]))
{


$user_id = $_SESSION['user_id'];
$subscription = $_POST['subscription'];
$change_id = $_POST['change_id'];
$website_url = $_POST['website_url'];
$website_id = $_POST['website_id'];
$with_trial = $_POST['with_trial'];

$cart_id = base64_encode($_POST['website_url']."|".$_POST['website_id']."|".$user_id);

        $sql = "SELECT * FROM cart where user_id='$user_id' and cart_id = '$cart_id'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){

                     $update_sql= "UPDATE `cart` SET `subscription_id`='$subscription' ,`change_id`='$change_id', updated_at = now() , with_trial = '$with_trial' WHERE `cart_id`='$cart_id'";

                      $results = mysqli_query($conn,$update_sql);

        }
        else{
                            echo $sql = "INSERT INTO `cart` (cart_id, user_id, website_id, website_url, subscription_id, change_id , with_trial ) VALUES('".$cart_id."','".$user_id."','".$website_id."','".$website_url."','".$subscription."','".$change_id."' , '".$with_trial."')";
                            $result = mysqli_query($conn, $sql);
                            
        }


        header("Location: index.php?cart=".$cart_id);


}
else{
 if (isset($_SERVER["HTTP_REFERER"])) {
        $_SESSION['error'] = "Plan Not loading please try again.";
        header("Location: " . $_SERVER["HTTP_REFERER"]);

        die;
    }
}
 
?>
 