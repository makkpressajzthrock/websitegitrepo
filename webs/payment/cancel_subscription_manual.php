<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

session_start();

include('../adminpannel/session.php');

require_once 'config.php'; 
$country = "USD";
// Include the database connection file 
include_once 'dbConnect.php'; 

if(isset($_REQUEST['sid']) && !empty($_REQUEST['sid'])) {

    $user_id = $_SESSION['user_id'];
    echo $subsc_id = base64_decode($_REQUEST['sid']);


    $sqlQ = "SELECT * FROM user_subscriptions WHERE user_id = '$user_id' and id = '$subsc_id' and is_active = 1"; 

    $query = $db->query($sqlQ);

    if ( $query->num_rows > 0 ) {

        $sqlQ = " UPDATE user_subscriptions SET is_active = 0, cancled_at = now() where id = '$subsc_id' ; "; 

        if ( $db->query($sqlQ) === TRUE ) {

            $query = "SELECT * from boost_website where subscription_id=$subsc_id ";
            $bw_query = $db->query($query) ;

            if ( $bw_query->num_rows > 0 ) {

                $bw_data = $bw_query->fetch_assoc();

                $query = "DELETE FROM  `addon_site` where site_id = '".$bw_data['id']."'";
                $db->query($query) ;

                $queryA = "DELETE  FROM  `additional_websites` where website_id = '".$bw_data['id']."'";
                $db->query($queryA) ;


                $encFn = $bw_data['id'];
                $url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_1.js";
                unlink($url_F);
                $url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_2.js";
                unlink($url_F);
                $url_F = "/var/www/html/script/ecmrx/ecmrx_".$encFn."/ecmrx_".$encFn."_3.js";
                unlink($url_F);

                // $sqlQ = "DELETE FROM  boost_website  where id = '".$bw_data['id']."' ; "; 
                $sqlQ = "UPDATE boost_website SET plan_id = '999' , plan_type = 'Subscription' , subscription_id = '111111' , get_script = 0 where id = '".$bw_data['id']."' ; "; 
                $db->query($sqlQ) ;

                $_SESSION['success'] = 'Subscription Canceled successfully.';
                header("location: ".$_SERVER['HTTP_REFERER']) ;
            }

        }
        else {
            $_SESSION['error'] = "Something went wrong.";
            header("location: ".$_SERVER['HTTP_REFERER']) ;
        }
    }
    else {
        $_SESSION['error'] = "Already Canceled";
        header("location: ".$_SERVER['HTTP_REFERER']) ;
    }
}
else{
    $_SESSION['error'] = "Something went wrong.";
    header("location: ".$_SERVER['HTTP_REFERER']) ;
}

?>