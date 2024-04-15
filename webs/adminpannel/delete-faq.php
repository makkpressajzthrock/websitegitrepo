<?php
 
    // connect database
   include('config.php');
include('session.php');
 
    // check if FAQ existed
    $sql = "SELECT * FROM add_faq WHERE id = '".$_REQUEST["id"]."'";
    $statement = mysqli_query($conn, $sql);
    
    $faq = mysqli_fetch_assoc($statement);
 
    if (!$faq)
    {
        die("FAQ not found");
    }
 
    // delete from database
    $deletesql = "DELETE FROM add_faq WHERE id = '".$_POST["id"]."'";
     mysqli_query($conn, $deletesql);
   
 
    // redirect to previous page
    header("Location: " . $_SERVER["HTTP_REFERER"]);
 
?>