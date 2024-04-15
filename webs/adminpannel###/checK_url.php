<?php

require_once('config.php');
require_once('inc/functions.php');

    $output = array('status' => "" );

if  (isset($_POST["url"]) ) {

  

    extract($_POST) ;
            $status=0;

    
     
            $website_data = getTableData($conn, " boost_website ", " 1"," ",1);
            $website_data2 = getTableData($conn, " boost_website ", " id='".$web_id."' ");
      
            $url=strtolower(parse_url($url)['host']) ;
        foreach ($website_data as $key => $value) 
        {
if ( $url==strtolower(parse_url($value['website_url'])['host'])  ) {
    // code...
            $status=1;
}elseif (strtolower(parse_url($website_data2['website_url'])['host']==$url)) {
            $status=2;
    // code...
}

        }

      

    $output = array('status' => $status );
}
    echo json_encode($output) ;
