<?php

include('../config.php');
include('../session.php');

function get_dataa($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function check_url($url,$code) {

    $variableee = get_dataa($url);
    return (strpos($variableee, $code))? 1:0; 
}

$url = $_POST['url'];
$script = $_POST['script'];

$urlLists = explode(',', $script);

$f = 1;
foreach ($urlLists as $urlList) {  

    $code_has = check_url($url ,$urlList) ;
    					
    if($code_has == 0){
        $f = 0;
    }

}

echo $f;
// echo 1;

	




?>